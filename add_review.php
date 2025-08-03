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
    $product_id = $input['product_id'] ?? 0;
    $rating = $input['rating'] ?? 5;
    $comment = $input['comment'] ?? '';
    
    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'معرف المنتج مطلوب']);
        exit();
    }
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'التقييم يجب أن يكون بين 1 و 5']);
        exit();
    }
    
    try {
        $stmt = $conn->prepare('INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiis', $user_id, $product_id, $rating, $comment);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'تم إضافة التقييم بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ في إضافة التقييم']);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ في إضافة التقييم']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة طلب غير صحيحة']);
}
?> 