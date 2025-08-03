<?php
require_once '../php/db.php';   
header('Content-Type: application/json');

// جلب إعدادات الموقع
$settings = [];
$settings_result = $conn->query('SELECT setting_key, setting_value FROM settings');
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

echo json_encode($settings);
?>              