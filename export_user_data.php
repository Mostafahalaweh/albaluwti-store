<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('غير مصرح');
}

// تصدير البيانات
$data = exportUserData($_SESSION['user_id']);

if ($data) {
    // تعيين headers للتحميل
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="user_data_' . date('Y-m-d_H-i-s') . '.json"');
    header('Content-Length: ' . strlen($data));
    
    echo $data;
} else {
    http_response_code(500);
    echo json_encode(['error' => 'حدث خطأ أثناء تصدير البيانات']);
}
?> 