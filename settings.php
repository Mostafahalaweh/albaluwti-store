<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$page_title = 'الإعدادات - متجر البلوطي';
$active = 'settings';

// الحصول على معلومات المستخدم
$user = getUserInfo($_SESSION['user_id']);

// معالجة تحديث الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_profile':
            $name = sanitizeInput($_POST['name']);
            $email = sanitizeInput($_POST['email']);
            $phone = sanitizeInput($_POST['phone']);
            
            if (updateUserProfile($_SESSION['user_id'], $name, $email, $phone)) {
                setSuccessMessage('تم تحديث الملف الشخصي بنجاح');
            } else {
                setErrorMessage('حدث خطأ أثناء تحديث الملف الشخصي');
            }
            break;
            
        case 'change_password':
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($new_password !== $confirm_password) {
                setErrorMessage('كلمة المرور الجديدة غير متطابقة');
            } elseif (!verifyPassword($_SESSION['user_id'], $current_password)) {
                setErrorMessage('كلمة المرور الحالية غير صحيحة');
            } elseif (changeUserPassword($_SESSION['user_id'], $new_password)) {
                setSuccessMessage('تم تغيير كلمة المرور بنجاح');
            } else {
                setErrorMessage('حدث خطأ أثناء تغيير كلمة المرور');
            }
            break;
            
        case 'update_notifications':
            $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
            $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
            $marketing_emails = isset($_POST['marketing_emails']) ? 1 : 0;
            
            if (updateNotificationSettings($_SESSION['user_id'], $email_notifications, $sms_notifications, $marketing_emails)) {
                setSuccessMessage('تم تحديث إعدادات الإشعارات بنجاح');
            } else {
                setErrorMessage('حدث خطأ أثناء تحديث الإعدادات');
            }
            break;
            
        case 'delete_account':
            $password = $_POST['delete_password'];
            if (verifyPassword($_SESSION['user_id'], $password)) {
                if (deleteUserAccount($_SESSION['user_id'])) {
                    session_destroy();
                    header('Location: ../index.php');
                    exit();
                } else {
                    setErrorMessage('حدث خطأ أثناء حذف الحساب');
                }
            } else {
                setErrorMessage('كلمة المرور غير صحيحة');
            }
            break;
    }
    
    // إعادة توجيه لتجنب إعادة إرسال النموذج
    header('Location: settings.php');
    exit();
}

include '../includes/header.php';
?>

<div class="settings-container">
    <div class="settings-header">
        <h1>الإعدادات</h1>
        <p>إدارة إعدادات حسابك وتفضيلاتك</p>
    </div>

    <?php displayMessages(); ?>

    <div class="settings-tabs">
        <button class="tab-btn active" data-tab="profile">الملف الشخصي</button>
        <button class="tab-btn" data-tab="security">الأمان</button>
        <button class="tab-btn" data-tab="notifications">الإشعارات</button>
        <button class="tab-btn" data-tab="privacy">الخصوصية</button>
    </div>

    <div class="settings-content">
        <!-- الملف الشخصي -->
        <div class="tab-content active" id="profile">
            <div class="settings-card">
                <h2>الملف الشخصي</h2>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="name">الاسم الكامل</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    
                    <button type="submit" class="btn">حفظ التغييرات</button>
                </form>
            </div>
        </div>

        <!-- الأمان -->
        <div class="tab-content" id="security">
            <div class="settings-card">
                <h2>تغيير كلمة المرور</h2>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="form-group">
                        <label for="current_password">كلمة المرور الحالية</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">كلمة المرور الجديدة</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <small>يجب أن تحتوي على 8 أحرف على الأقل</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">تأكيد كلمة المرور</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn">تغيير كلمة المرور</button>
                </form>
            </div>
            
            <div class="settings-card">
                <h2>حذف الحساب</h2>
                <p class="warning-text">⚠️ تحذير: حذف الحساب نهائي ولا يمكن التراجع عنه</p>
                <form method="POST" class="settings-form" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف حسابك نهائياً؟')">
                    <input type="hidden" name="action" value="delete_account">
                    
                    <div class="form-group">
                        <label for="delete_password">كلمة المرور للتأكيد</label>
                        <input type="password" id="delete_password" name="delete_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-danger">حذف الحساب</button>
                </form>
            </div>
        </div>

        <!-- الإشعارات -->
        <div class="tab-content" id="notifications">
            <div class="settings-card">
                <h2>إعدادات الإشعارات</h2>
                <form method="POST" class="settings-form">
                    <input type="hidden" name="action" value="update_notifications">
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="email_notifications" <?= getUserNotificationSetting($_SESSION['user_id'], 'email') ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            إشعارات البريد الإلكتروني
                        </label>
                        <small>استلام إشعارات عبر البريد الإلكتروني</small>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="sms_notifications" <?= getUserNotificationSetting($_SESSION['user_id'], 'sms') ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            إشعارات الرسائل النصية
                        </label>
                        <small>استلام إشعارات عبر الرسائل النصية</small>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="marketing_emails" <?= getUserNotificationSetting($_SESSION['user_id'], 'marketing') ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            رسائل التسويق
                        </label>
                        <small>استلام عروض ورسائل تسويقية</small>
                    </div>
                    
                    <button type="submit" class="btn">حفظ الإعدادات</button>
                </form>
            </div>
        </div>

        <!-- الخصوصية -->
        <div class="tab-content" id="privacy">
            <div class="settings-card">
                <h2>إعدادات الخصوصية</h2>
                
                <div class="privacy-option">
                    <div class="option-header">
                        <h3>الملف الشخصي العام</h3>
                        <label class="switch">
                            <input type="checkbox" id="public_profile" <?= getUserPrivacySetting($_SESSION['user_id'], 'public_profile') ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p>السماح للآخرين برؤية معلوماتك الشخصية</p>
                </div>
                
                <div class="privacy-option">
                    <div class="option-header">
                        <h3>تاريخ الطلبات</h3>
                        <label class="switch">
                            <input type="checkbox" id="order_history" <?= getUserPrivacySetting($_SESSION['user_id'], 'order_history') ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p>السماح برؤية تاريخ طلباتك</p>
                </div>
                
                <div class="privacy-option">
                    <div class="option-header">
                        <h3>التقييمات العامة</h3>
                        <label class="switch">
                            <input type="checkbox" id="public_reviews" <?= getUserPrivacySetting($_SESSION['user_id'], 'public_reviews') ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p>إظهار تقييماتك للمنتجات للآخرين</p>
                </div>
                
                <button type="button" class="btn" onclick="savePrivacySettings()">حفظ إعدادات الخصوصية</button>
            </div>
            
            <div class="settings-card">
                <h2>تصدير البيانات</h2>
                <p>يمكنك تحميل نسخة من بياناتك الشخصية</p>
                <button type="button" class="btn btn-outline" onclick="exportUserData()">تصدير البيانات</button>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header {
    text-align: center;
    margin-bottom: 2rem;
}

.settings-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.settings-header p {
    font-size: 1.1rem;
    color: #666;
}

.settings-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
    overflow-x: auto;
}

.tab-btn {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    font-weight: 500;
}

.tab-btn.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

.tab-btn:hover {
    color: #667eea;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.settings-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.settings-card h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
}

.settings-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 500;
    color: #333;
}

.form-group input {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group small {
    color: #666;
    font-size: 0.85rem;
}

.checkbox-group {
    flex-direction: row;
    align-items: flex-start;
    gap: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #667eea;
    border-color: #667eea;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.8rem;
    font-weight: bold;
}

.warning-text {
    color: #dc3545;
    font-weight: 500;
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8d7da;
    border-radius: 8px;
    border: 1px solid #f5c6cb;
}

.privacy-option {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.option-header h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.privacy-option p {
    color: #666;
    margin: 0;
}

.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #667eea;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

@media (max-width: 768px) {
    .settings-tabs {
        flex-direction: column;
        gap: 0;
    }
    
    .tab-btn {
        border-bottom: 1px solid #f0f0f0;
        border-radius: 0;
    }
    
    .option-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .checkbox-group {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
// تبديل التبويبات
document.querySelectorAll('.tab-btn').forEach(button => {
    button.addEventListener('click', function() {
        const tabId = this.dataset.tab;
        
        // إزالة الفئة النشطة من جميع الأزرار
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        // إضافة الفئة النشطة للزر المحدد
        this.classList.add('active');
        
        // إخفاء جميع المحتويات
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        
        // إظهار المحتوى المحدد
        document.getElementById(tabId).classList.add('active');
    });
});

// حفظ إعدادات الخصوصية
function savePrivacySettings() {
    const settings = {
        public_profile: document.getElementById('public_profile').checked,
        order_history: document.getElementById('order_history').checked,
        public_reviews: document.getElementById('public_reviews').checked
    };
    
    fetch('../pages/privacy_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(settings)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم حفظ إعدادات الخصوصية بنجاح', 'success');
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الحفظ', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

// تصدير بيانات المستخدم
function exportUserData() {
    fetch('../pages/export_user_data.php')
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'user_data.json';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    })
    .catch(error => {
        showNotification('حدث خطأ أثناء تصدير البيانات', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<?php include '../includes/footer.php'; ?> 