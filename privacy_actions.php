<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit();
}

// التحقق من نوع الطلب
$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $result = updatePrivacySettings($_SESSION['user_id'], $input);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'تم حفظ إعدادات الخصوصية بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حفظ الإعدادات']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']);
}
?> 