<?php
require_once '../php/db.php';
header('Content-Type: application/json; charset=utf-8');
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$order_id) {
    echo json_encode(['error' => 'رقم الطلب غير صحيح']);
    exit();
}
// جلب بيانات الطلب
$stmt = $conn->prepare('SELECT status FROM orders WHERE id = ?');
if (!$stmt) {
    echo json_encode(['error' => 'خطأ في النظام']);
    exit();
}
$stmt->bind_param('i', $order_id);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'خطأ في تنفيذ الاستعلام']);
    exit();
}
$result = $stmt->get_result();
if (!$result) {
    echo json_encode(['error' => 'خطأ في الحصول على النتيجة']);
    exit();
}
$order = $result->fetch_assoc();
$stmt->close();
if (!$order) {
    echo json_encode(['error' => 'الطلب غير موجود']);
    exit();
}

// جلب سجل الحالات
$status_history = [];
$stmt = $conn->prepare('SELECT status, note, created_at FROM order_status_history WHERE order_id = ? ORDER BY created_at ASC');
if ($stmt) {
    $stmt->bind_param('i', $order_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $status_history[] = $row;
            }
        }
    }
    $stmt->close();
}
echo json_encode([
    'status' => $order['status'],
    'history' => $status_history
]); 