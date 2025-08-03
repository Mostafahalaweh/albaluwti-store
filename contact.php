<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'اتصل بنا - متجر البلوطي';
$active = 'contact';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message_text = sanitizeInput($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = 'جميع الحقول مطلوبة.';
        $message_type = 'error';
    } else {
        // التحقق من الاتصال بقاعدة البيانات
        if (!$conn) {
            $message = 'خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة مرة أخرى لاحقاً.';
            $message_type = 'error';
        } else {
            // حفظ الرسالة في قاعدة البيانات (إذا كان هناك جدول للرسائل)
            $stmt = $conn->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())');
            if ($stmt) {
                $stmt->bind_param('ssss', $name, $email, $subject, $message_text);
                if ($stmt->execute()) {
                    $message = 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.';
                    $message_type = 'success';
                } else {
                    $message = 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message = 'حدث خطأ في إعداد الاستعلام. يرجى المحاولة مرة أخرى.';
                $message_type = 'error';
            }
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container contact-page fade-in slide-up">
    <h2 class="section-title">تواصل معنا</h2>
    
    <div class="contact-content">
        <div class="contact-info card fade-in">
            <h3>معلومات التواصل</h3>
            <div class="contact-item">
                <span class="contact-icon">📞</span>
                <div>
                    <strong>الهاتف:</strong><br>
                    <a href="tel:+962790000000">+962 79 000 0000</a>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📧</span>
                <div>
                    <strong>البريد الإلكتروني:</strong><br>
                    <a href="mailto:info@albaluwti.com">info@albaluwti.com</a>
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <div>
                    <strong>العنوان:</strong><br>
                    شارع الملك حسين، عمان، الأردن
                </div>
            </div>
            <div class="contact-item">
                <span class="contact-icon">🕒</span>
                <div>
                    <strong>ساعات العمل:</strong><br>
                    الأحد - الخميس: 8:00 ص - 10:00 م<br>
                    الجمعة - السبت: 9:00 ص - 11:00 م
                </div>
            </div>
        </div>
        
        <div class="contact-form-container card fade-in">
            <h3>أرسل لنا رسالة</h3>
            <?php if ($message): ?>
                <div class="message <?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form method="post" class="contact-form">
                <label>الاسم الكامل</label>
                <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                
                <label>الموضوع</label>
                <input type="text" name="subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                
                <label>الرسالة</label>
                <textarea name="message" rows="5" placeholder="اكتب رسالتك هنا..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                
                <button type="submit" class="btn cta-btn scale-hover">إرسال الرسالة</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?> 