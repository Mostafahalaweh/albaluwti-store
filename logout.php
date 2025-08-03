<?php
session_start();

// حذف جميع بيانات الجلسة
session_destroy();

// حذف بيانات المستخدم من localStorage (سيتم تنفيذها بواسطة JavaScript)
echo "<script>localStorage.removeItem('user');</script>";

// إعادة التوجيه للصفحة الرئيسية
header('Location: /albaluwti_backup/pages/index.php');
exit();
?>          