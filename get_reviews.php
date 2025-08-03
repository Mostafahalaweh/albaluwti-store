<?php
require_once '../php/db.php';

header('Content-Type: application/json');

$product_id = $_GET['product_id'] ?? 0;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'معرف المنتج مطلوب']);
    exit();
}

try {   
    $stmt = $conn->prepare('
        SELECT r.*, u.username 
        FROM reviews r 
        LEFT JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? 
        ORDER BY r.created_at DESC
    ');
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = [
            'id' => $row['id'],
            'username' => $row['username'] ?: 'مستخدم',
            'rating' => $row['rating'],
            'comment' => $row['comment'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode(['success' => true, 'reviews' => $reviews]);
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'حدث خطأ في جلب التقييمات']);
}
?> 