<?php
require_once '../php/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
        exit();
    }
    
    $user_id = $input['user_id'] ?? 1; // يمكن تغيير هذا حسب نظام المستخدمين
    $items = $input['items'] ?? [];
    $total = $input['total'] ?? 0;
    $payment_method = $input['payment_method'] ?? 'نقداً';
    
    if (empty($items)) {
        echo json_encode(['success' => false, 'message' => 'السلة فارغة']);
        exit();
    }
    
    try {
        // بدء المعاملة
        $conn->begin_transaction();
        
        // إنشاء الطلب
        $stmt = $conn->prepare('INSERT INTO orders (user_id, total, payment_method) VALUES (?, ?, ?)');
        $stmt->bind_param('ids', $user_id, $total, $payment_method);
        $stmt->execute();
        $order_id = $conn->insert_id;
        $stmt->close();
        
        // إضافة تفاصيل الطلب
        $stmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
        
        foreach ($items as $item) {
            $stmt->bind_param('iiid', $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
            
            // تحديث كمية المنتج
            $update_stmt = $conn->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ?');
            $update_stmt->bind_param('ii', $item['quantity'], $item['id']);
            $update_stmt->execute();
            $update_stmt->close();
        }
        
        $stmt->close();
        
        // تأكيد المعاملة
        $conn->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم إرسال الطلب بنجاح',
            'order_id' => $order_id
        ]);
        
    } catch (Exception $e) {
        // التراجع عن المعاملة في حالة الخطأ
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في معالجة الطلب']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
}
?> 