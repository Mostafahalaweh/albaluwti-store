<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

header('Content-Type: application/json');

// التحقق من الاتصال بقاعدة البيانات
if (!$conn || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'حدث خطأ في الاتصال']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
    exit();
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'معرف المنتج غير صحيح']);
            exit();
        }
        
        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'الكمية يجب أن تكون أكبر من صفر']);
            exit();
        }
        
        // التحقق من وجود المنتج
        $stmt = $conn->prepare('SELECT id, name, price, stock_quantity, status FROM products WHERE id = ?');
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'خطأ في إعداد الاستعلام']);
            exit();
        }
        
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (!$result->num_rows) {
            echo json_encode(['success' => false, 'message' => 'المنتج غير موجود']);
            exit();
        }
        
        $product = $result->fetch_assoc();
        $stmt->close();
        
        if ($product['status'] !== 'active') {
            echo json_encode(['success' => false, 'message' => 'المنتج غير متاح حالياً']);
            exit();
        }
        
        // التحقق من المخزون
        if ($product['stock_quantity'] < $quantity) {
            echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون']);
            exit();
        }
        
        // إضافة إلى السلة
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        // حساب عدد العناصر في السلة
        $cart_count = array_sum($_SESSION['cart']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم إضافة المنتج إلى السلة',
            'cart_count' => $cart_count
        ]);
        break;
        
    case 'update':
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($product_id <= 0 || $quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
            exit();
        }
        
        if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$product_id])) {
            echo json_encode(['success' => false, 'message' => 'المنتج غير موجود في السلة']);
            exit();
        }
        
        // التحقق من المخزون
        $stmt = $conn->prepare('SELECT stock_quantity FROM products WHERE id = ? AND status = "active"');
        if ($stmt) {
            $stmt->bind_param('i', $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
                               if ($result->num_rows) {
                       $product = $result->fetch_assoc();
                       if ($product['stock_quantity'] < $quantity) {
                           echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون']);
                           exit();
                       }
                   }
            $stmt->close();
        }
        
        // تحديث الكمية
        $_SESSION['cart'][$product_id] = $quantity;
        
        // حساب عدد العناصر في السلة
        $cart_count = array_sum($_SESSION['cart']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم تحديث الكمية',
            'cart_count' => $cart_count
        ]);
        break;
        
    case 'remove':
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if ($product_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'معرف المنتج غير صحيح']);
            exit();
        }
        
        if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$product_id])) {
            echo json_encode(['success' => false, 'message' => 'المنتج غير موجود في السلة']);
            exit();
        }
        
        // إزالة المنتج من السلة
        unset($_SESSION['cart'][$product_id]);
        
        // حساب عدد العناصر في السلة
        $cart_count = array_sum($_SESSION['cart']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم إزالة المنتج من السلة',
            'cart_count' => $cart_count
        ]);
        break;
        
    case 'clear':
        // تفريغ السلة
        $_SESSION['cart'] = [];
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم تفريغ السلة',
            'cart_count' => 0
        ]);
        break;
        
    case 'get_count':
        // الحصول على عدد العناصر في السلة
        $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
        
        echo json_encode([
            'success' => true, 
            'cart_count' => $cart_count
        ]);
        break;
        
    case 'get_cart':
        // الحصول على محتويات السلة
        $cart_items = getCartItems();
        $cart_total = calculateCartTotal();
        
        echo json_encode([
            'success' => true,
            'cart_items' => $cart_items,
            'cart_total' => $cart_total,
            'cart_count' => isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'إجراء غير معروف']);
        break;
}
?> 