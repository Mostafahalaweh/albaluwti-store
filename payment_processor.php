<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

header('Content-Type: application/json');

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
    exit();
}

$user_id = $_SESSION['user_id'];
$payment_method = sanitizeInput($input['payment_method'] ?? '');
$payment_data = $input['payment_data'] ?? [];
$order_data = $input['order_data'] ?? [];

// التحقق من البيانات المطلوبة
if (empty($payment_method)) {
    echo json_encode(['success' => false, 'message' => 'طريقة الدفع مطلوبة']);
    exit();
}

if (empty($order_data)) {
    echo json_encode(['success' => false, 'message' => 'بيانات الطلب مطلوبة']);
    exit();
}

try {
    // جلب محتويات السلة
    $cart_items = getCartItems();
    $cart_total = calculateCartTotal();
    
    if (empty($cart_items)) {
        echo json_encode(['success' => false, 'message' => 'السلة فارغة']);
        exit();
    }
    
    // الحصول على الإعدادات
    $settings = getSettings();
    $shipping_cost = floatval($settings['shipping_cost'] ?? 30.00);
    $free_shipping_threshold = floatval($settings['free_shipping_threshold'] ?? 200.00);
    
    // حساب تكلفة الشحن
    $final_shipping_cost = ($cart_total >= $free_shipping_threshold) ? 0 : $shipping_cost;
    $final_total = $cart_total + $final_shipping_cost;
    
    // التحقق من صحة البيانات
    $customer_name = sanitizeInput($order_data['customer_name'] ?? '');
    $customer_phone = sanitizeInput($order_data['customer_phone'] ?? '');
    $customer_email = sanitizeInput($order_data['customer_email'] ?? '');
    $shipping_address = sanitizeInput($order_data['shipping_address'] ?? '');
    $notes = sanitizeInput($order_data['notes'] ?? '');
    
    $errors = [];
    
    if (empty($customer_name)) $errors[] = 'اسم العميل مطلوب';
    if (empty($customer_phone)) $errors[] = 'رقم الهاتف مطلوب';
    if (empty($customer_email)) $errors[] = 'البريد الإلكتروني مطلوب';
    if (empty($shipping_address)) $errors[] = 'عنوان الشحن مطلوب';
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit();
    }
    
    // معالجة الدفع حسب الطريقة
    $payment_status = 'pending';
    $payment_reference = '';
    $payment_notes = '';
    
    switch ($payment_method) {
        case 'نقداً عند الاستلام':
            $payment_status = 'pending';
            $payment_reference = 'COD-' . time();
            $payment_notes = 'الدفع عند الاستلام';
            break;
            
        case 'تحويل بنكي':
            // التحقق من بيانات التحويل البنكي
            $bank_reference = sanitizeInput($payment_data['bank_reference'] ?? '');
            $bank_name = sanitizeInput($payment_data['bank_name'] ?? '');
            $transfer_date = sanitizeInput($payment_data['transfer_date'] ?? '');
            
            if (empty($bank_reference) || empty($bank_name) || empty($transfer_date)) {
                echo json_encode(['success' => false, 'message' => 'بيانات التحويل البنكي مطلوبة']);
                exit();
            }
            
            $payment_status = 'pending';
            $payment_reference = $bank_reference;
            $payment_notes = "تحويل بنكي - $bank_name - $transfer_date";
            break;
            
        case 'بطاقة ائتمان':
            // محاكاة معالجة البطاقة (في التطبيق الحقيقي ستستخدم بوابة دفع)
            $card_number = sanitizeInput($payment_data['card_number'] ?? '');
            $expiry_month = sanitizeInput($payment_data['expiry_month'] ?? '');
            $expiry_year = sanitizeInput($payment_data['expiry_year'] ?? '');
            $cvv = sanitizeInput($payment_data['cvv'] ?? '');
            
            if (empty($card_number) || empty($expiry_month) || empty($expiry_year) || empty($cvv)) {
                echo json_encode(['success' => false, 'message' => 'بيانات البطاقة مطلوبة']);
                exit();
            }
            
            // محاكاة معالجة الدفع
            $payment_status = 'processing';
            $payment_reference = 'CARD-' . time() . '-' . substr($card_number, -4);
            $payment_notes = 'بطاقة ائتمان - قيد المعالجة';
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'طريقة دفع غير مدعومة']);
            exit();
    }
    
    // بدء المعاملة
    $conn->begin_transaction();
    
    // إنشاء الطلب
    $stmt = $conn->prepare('
        INSERT INTO orders (
            user_id, customer_name, customer_phone, customer_email, 
            shipping_address, notes, total, shipping_cost, 
            final_total, payment_method, payment_status, payment_reference,
            payment_notes, status, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "pending", NOW())
    ');
    
    $stmt->bind_param('isssssdddsis', 
        $user_id, $customer_name, $customer_phone, $customer_email, 
        $shipping_address, $notes, $cart_total, $final_shipping_cost, 
        $final_total, $payment_method, $payment_status, $payment_reference,
        $payment_notes
    );
    
    if (!$stmt->execute()) {
        throw new Exception('فشل في إنشاء الطلب');
    }
    
    $order_id = $conn->insert_id;
    $stmt->close();
    
    // إضافة منتجات الطلب
    $stmt = $conn->prepare('
        INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
        VALUES (?, ?, ?, ?, ?)
    ');
    
    foreach ($cart_items as $item) {
        $item_price = isset($item['sale_price']) && $item['sale_price'] && $item['sale_price'] < $item['price'] 
            ? $item['sale_price'] : $item['price'];
        $subtotal = $item_price * $item['quantity'];
        
        $stmt->bind_param('iiidd', $order_id, $item['product_id'], $item['quantity'], $item_price, $subtotal);
        
        if (!$stmt->execute()) {
            throw new Exception('فشل في إضافة منتجات الطلب');
        }
        
        // تحديث كمية المنتج
        $update_stmt = $conn->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ?');
        $update_stmt->bind_param('ii', $item['quantity'], $item['product_id']);
        $update_stmt->execute();
        $update_stmt->close();
    }
    $stmt->close();
    
    // تسجيل النشاط
    logActivity($user_id, 'order_created', "تم إنشاء طلب جديد برقم: #$order_id");
    
    // تسجيل معاملة الدفع
    $stmt = $conn->prepare('
        INSERT INTO payment_transactions (
            order_id, payment_method, amount, status, reference, notes, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');
    $stmt->bind_param('isdsis', $order_id, $payment_method, $final_total, $payment_status, $payment_reference, $payment_notes);
    $stmt->execute();
    $stmt->close();
    
    // تأكيد المعاملة
    $conn->commit();
    
    // تفريغ السلة
    clearCart();
    
    // إرسال بريد إلكتروني تأكيد
    if (function_exists('sendHTMLEmail')) {
        $subject = "تأكيد طلبك - متجر البلوطي";
        $html_message = "
            <h2>مرحباً $customer_name</h2>
            <p>شكراً لك على طلبك. تم استلام طلبك بنجاح.</p>
            <p><strong>رقم الطلب:</strong> #$order_id</p>
            <p><strong>المجموع:</strong> " . formatPrice($final_total) . "</p>
            <p><strong>طريقة الدفع:</strong> $payment_method</p>
            <p>سنقوم بمراجعة طلبك والتواصل معك قريباً.</p>
        ";
        sendHTMLEmail($customer_email, $subject, $html_message);
    }
    
    // إرسال إشعار للمدير (اختياري)
    if (function_exists('sendNotificationToAdmin')) {
        sendNotificationToAdmin("طلب جديد #$order_id", "تم استلام طلب جديد بقيمة " . formatPrice($final_total));
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم إتمام الطلب بنجاح',
        'order_id' => $order_id,
        'payment_status' => $payment_status,
        'payment_reference' => $payment_reference,
        'redirect_url' => "order-confirmation.php?id=$order_id"
    ]);
    
} catch (Exception $e) {
    // التراجع عن المعاملة في حالة الخطأ
    if ($conn->inTransaction()) {
        $conn->rollback();
    }
    
    error_log('خطأ في معالجة الدفع: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'حدث خطأ في معالجة الدفع: ' . $e->getMessage()
    ]);
}
?> 