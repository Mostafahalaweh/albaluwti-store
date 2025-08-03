<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

header('Content-Type: application/json; charset=utf-8');

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// الحصول على اتصال قاعدة البيانات
$conn = getMysqliConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
    exit();
}

switch ($action) {
    case 'add':
        addToCart();
        break;
    case 'remove':
        removeFromCart();
        break;
    case 'update':
        updateCartQuantity();
        break;
    case 'clear':
        clearCart();
        break;
    case 'get':
        getCart();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'إجراء غير صالح']);
}

function addToCart() {
    global $conn, $user_id;
    
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    if (!$product_id || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
        return;
    }
    
    // التحقق من وجود المنتج
    $stmt = $conn->prepare('SELECT id, name, price, stock, image_url FROM products WHERE id = ? AND status = "active"');
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'المنتج غير موجود']);
        return;
    }
    
    if ($product['stock'] < $quantity) {
        echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون']);
        return;
    }
    
    // التحقق من وجود المنتج في السلة
    $stmt = $conn->prepare('SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?');
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($cart_item) {
        // تحديث الكمية
        $new_quantity = $cart_item['quantity'] + $quantity;
        if ($new_quantity > $product['stock']) {
            echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون']);
            return;
        }
        
        $stmt = $conn->prepare('UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?');
        $stmt->bind_param('ii', $new_quantity, $cart_item['id']);
        $success = $stmt->execute();
        $stmt->close();
    } else {
        // إضافة منتج جديد
        $stmt = $conn->prepare('INSERT INTO cart (user_id, product_id, quantity, price, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())');
        $stmt->bind_param('iiid', $user_id, $product_id, $quantity, $product['price']);
        $success = $stmt->execute();
        $stmt->close();
    }
    
    if ($success) {
        $cart_count = getCartCount($user_id);
        echo json_encode([
            'success' => true, 
            'message' => 'تم إضافة المنتج إلى السلة بنجاح',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء إضافة المنتج']);
    }
}

function removeFromCart() {
    global $conn, $user_id;
    
    $cart_id = (int)($_POST['cart_id'] ?? 0);
    
    if (!$cart_id) {
        echo json_encode(['success' => false, 'message' => 'معرف السلة غير صحيح']);
        return;
    }
    
    $stmt = $conn->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $cart_id, $user_id);
    $success = $stmt->execute();
    $stmt->close();
    
    if ($success) {
        $cart_count = getCartCount($user_id);
        echo json_encode([
            'success' => true, 
            'message' => 'تم حذف المنتج من السلة',
            'cart_count' => $cart_count
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حذف المنتج']);
    }
}

function updateCartQuantity() {
    global $conn, $user_id;
    
    $cart_id = (int)($_POST['cart_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    
    if (!$cart_id || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
        return;
    }
    
    // التحقق من المخزون
    $stmt = $conn->prepare('
        SELECT c.id, c.quantity, p.stock, p.name 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.id = ? AND c.user_id = ?
    ');
    $stmt->bind_param('ii', $cart_id, $user_id);
    $stmt->execute();
    $cart_item = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$cart_item) {
        echo json_encode(['success' => false, 'message' => 'المنتج غير موجود في السلة']);
        return;
    }
    
    if ($quantity > $cart_item['stock']) {
        echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون']);
        return;
    }
    
    $stmt = $conn->prepare('UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?');
    $stmt->bind_param('ii', $quantity, $cart_id);
    $success = $stmt->execute();
    $stmt->close();
    
    if ($success) {
        echo json_encode([
            'success' => true, 
            'message' => 'تم تحديث الكمية بنجاح'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء تحديث الكمية']);
    }
}

function clearCart() {
    global $conn, $user_id;
    
    $stmt = $conn->prepare('DELETE FROM cart WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $success = $stmt->execute();
    $stmt->close();
    
    if ($success) {
        echo json_encode([
            'success' => true, 
            'message' => 'تم تفريغ السلة بنجاح',
            'cart_count' => 0
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء تفريغ السلة']);
    }
}

function getCart() {
    global $conn, $user_id;
    
    $stmt = $conn->prepare('
        SELECT 
            c.id as cart_id,
            c.quantity,
            c.price,
            p.id as product_id,
            p.name,
            p.image_url,
            p.stock,
            p.description
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ? AND p.status = "active"
        ORDER BY c.created_at DESC
    ');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cart_items = [];
    $total = 0;
    
    while ($row = $result->fetch_assoc()) {
        $item_total = $row['price'] * $row['quantity'];
        $total += $item_total;
        
        $cart_items[] = [
            'cart_id' => $row['cart_id'],
            'product_id' => $row['product_id'],
            'name' => $row['name'],
            'image_url' => $row['image_url'] ?: '/albaluwti_backup/assets/images/placeholder.png',
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'stock' => $row['stock'],
            'item_total' => $item_total,
            'description' => $row['description']
        ];
    }
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'cart_items' => $cart_items,
        'total' => $total,
        'cart_count' => count($cart_items)
    ]);
}

function getCartCount($user_id) {
    global $conn;
    
    $stmt = $conn->prepare('SELECT COUNT(*) as count FROM cart WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    return $result['count'];
}
?> 