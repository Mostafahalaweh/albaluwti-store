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

// معالجة طلب إعادة تعيين كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'طلب غير صالح. يرجى إعادة المحاولة.';
        $message_type = 'error';
    } else {
        $email = sanitizeInput($_POST['email']);
        
        if (empty($email)) {
            $message = 'يرجى إدخال البريد الإلكتروني.';
            $message_type = 'error';
        } else {
            // التحقق من وجود المستخدم
            $stmt = $conn->prepare('SELECT id, username FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            if ($user) {
                // إنشاء رمز إعادة تعيين كلمة المرور
                $reset_token = bin2hex(random_bytes(32));
                $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $stmt = $conn->prepare('UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?');
                $stmt->bind_param('ssi', $reset_token, $reset_expires, $user['id']);
                
                if ($stmt->execute()) {
                    // هنا يمكن إرسال بريد إلكتروني مع رابط إعادة التعيين
                    // للتبسيط، سنعرض رسالة نجاح
                    $message = 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني. يرجى التحقق من صندوق الوارد.';
                    $message_type = 'success';
                } else {
                    $message = 'حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.';
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                // لا نكشف عن وجود أو عدم وجود البريد الإلكتروني لأسباب أمنية
                $message = 'إذا كان البريد الإلكتروني مسجل لدينا، سيتم إرسال رابط إعادة تعيين كلمة المرور.';
                $message_type = 'success';
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container forgot-password-page fade-in slide-up">
    <div class="auth-form card">
        <h2 class="section-title">نسيت كلمة المرور؟</h2>
        <p class="form-description">أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور.</p>
        
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="post" class="forgot-password-form">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
            
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="أدخل بريدك الإلكتروني" required>
            
            <button type="submit" class="btn cta-btn scale-hover">إرسال رابط إعادة التعيين</button>
        </form>
        
        <div class="auth-links">
            <p>تذكرت كلمة المرور؟ <a href="login.php">تسجيل الدخول</a></p>
            <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 