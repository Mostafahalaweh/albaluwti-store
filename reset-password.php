<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من وجود جلسة نشطة
if (isLoggedIn()) {
    redirect('profile.php');
}

$message = '';
$message_type = '';
$valid_token = false;
$user_id = null;

// التحقق من وجود رمز إعادة التعيين
if (isset($_GET['token'])) {
    $reset_token = sanitizeInput($_GET['token']);
    
    // التحقق من صحة الرمز وتاريخ انتهاء الصلاحية
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()');
    $stmt->bind_param('s', $reset_token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        $valid_token = true;
        $user_id = $user['id'];
    } else {
        $message = 'رابط إعادة تعيين كلمة المرور غير صالح أو منتهي الصلاحية.';
        $message_type = 'error';
    }
} else {
    $message = 'رابط غير صالح.';
    $message_type = 'error';
}

// معالجة إعادة تعيين كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    // التحقق من CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'طلب غير صالح. يرجى إعادة المحاولة.';
        $message_type = 'error';
    } else {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($password) || empty($confirm_password)) {
            $message = 'جميع الحقول مطلوبة.';
            $message_type = 'error';
        } elseif ($password !== $confirm_password) {
            $message = 'كلمة المرور غير متطابقة.';
            $message_type = 'error';
        } elseif (strlen($password) < 6) {
            $message = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.';
            $message_type = 'error';
        } else {
            // تحديث كلمة المرور وحذف رمز إعادة التعيين
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
            $stmt->bind_param('si', $hashed_password, $user_id);
            
            if ($stmt->execute()) {
                $message = 'تم تغيير كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول بكلمة المرور الجديدة.';
                $message_type = 'success';
                $valid_token = false; // منع إعادة الإرسال
            } else {
                $message = 'حدث خطأ أثناء تغيير كلمة المرور. يرجى المحاولة مرة أخرى.';
                $message_type = 'error';
            }
            $stmt->close();
        }
    }
}

include '../includes/header.php';
?>

<div class="container reset-password-page fade-in slide-up">
    <div class="auth-form card">
        <h2 class="section-title">إعادة تعيين كلمة المرور</h2>
        
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if ($valid_token): ?>
            <p class="form-description">أدخل كلمة المرور الجديدة.</p>
            <form method="post" class="reset-password-form">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                
                <label>كلمة المرور الجديدة</label>
                <input type="password" name="password" placeholder="أدخل كلمة المرور الجديدة" required>
                
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="confirm_password" placeholder="أعد إدخال كلمة المرور" required>
                
                <button type="submit" class="btn cta-btn scale-hover">تغيير كلمة المرور</button>
            </form>
        <?php endif; ?>
        
        <div class="auth-links">
            <p><a href="login.php">العودة لتسجيل الدخول</a></p>
            <p>نسيت كلمة المرور؟ <a href="forgot-password.php">طلب إعادة تعيين</a></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 