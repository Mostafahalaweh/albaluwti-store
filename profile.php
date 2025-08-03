<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'حسابي - متجر البلوطي';
$active = 'profile';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: /albaluwti_backup/pages/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// جلب بيانات المستخدم
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// جلب الطلبات الخاصة بالمستخدم
$orders = [];
$stmt = $conn->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// تحديث البيانات الشخصية
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $email = sanitizeInput($_POST['email']);
    $stmt = $conn->prepare('UPDATE users SET full_name = ?, phone = ?, email = ? WHERE id = ?');
    $stmt->bind_param('sssi', $full_name, $phone, $email, $user_id);
    if ($stmt->execute()) {
        $message = 'تم تحديث البيانات بنجاح';
        $user['full_name'] = $full_name;
        $user['phone'] = $phone;
        $user['email'] = $email;
    } else {
        $message = 'حدث خطأ أثناء التحديث';
    }
    $stmt->close();
}

// رفع صورة الحساب
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if (in_array($ext, $allowed)) {
        if (!is_dir('/albaluwti_backup/assets/images/avatars')) mkdir('/albaluwti_backup/assets/images/avatars', 0777, true);
        $avatar_name = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], '/albaluwti_backup/assets/images/avatars/' . $avatar_name);
        $stmt = $conn->prepare('UPDATE users SET avatar = ? WHERE id = ?');
        $stmt->bind_param('si', $avatar_name, $user_id);
        $stmt->execute();
        $stmt->close();
        $user['avatar'] = $avatar_name;
        $message = 'تم تحديث صورة الحساب بنجاح';
    } else {
        $message = 'الرجاء اختيار صورة بصيغة صحيحة';
    }
}

include __DIR__ . '/../includes/header.php';
?>

<!-- إضافة ملف CSS الحديث -->
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/modern-design.css">

<main class="main-content">
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="page-header text-center mb-5">
            <h1 class="mb-2">👤 حسابي</h1>
            <p class="text-secondary">إدارة معلوماتك الشخصية وطلباتك</p>
        </div>

        <!-- معلومات المستخدم -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="grid grid-2">
                <!-- البطاقة الشخصية -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user icon"></i>
                            المعلومات الشخصية
                        </h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="update_profile.php" id="profileForm">
                            <div class="form-group">
                                <label class="form-label">الاسم الكامل</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" 
                                       placeholder="أدخل اسمك الكامل" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" 
                                       placeholder="أدخل بريدك الإلكتروني" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" 
                                       placeholder="أدخل رقم هاتفك">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">العنوان</label>
                                <textarea name="address" class="form-control" rows="3" 
                                          placeholder="أدخل عنوانك"><?php echo htmlspecialchars($_SESSION['user_address'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                        </form>
                    </div>
                </div>

                <!-- إحصائيات سريعة -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar icon"></i>
                            إحصائياتي
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-2">
                            <div class="text-center p-3">
                                <div class="icon-lg mb-2" style="color: var(--primary-color);">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <h4 class="mb-1">0</h4>
                                <p class="text-secondary">إجمالي الطلبات</p>
                            </div>
                            
                            <div class="text-center p-3">
                                <div class="icon-lg mb-2" style="color: var(--success-color);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h4 class="mb-1">0</h4>
                                <p class="text-secondary">الطلبات المكتملة</p>
                            </div>
                            
                            <div class="text-center p-3">
                                <div class="icon-lg mb-2" style="color: var(--warning-color);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4 class="mb-1">0</h4>
                                <p class="text-secondary">الطلبات قيد المعالجة</p>
                            </div>
                            
                            <div class="text-center p-3">
                                <div class="icon-lg mb-2" style="color: var(--accent-color);">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h4 class="mb-1">0</h4>
                                <p class="text-secondary">المفضلة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تغيير كلمة المرور -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock icon"></i>
                        تغيير كلمة المرور
                    </h3>
                </div>
                <div class="card-body">
                    <form method="post" action="change_password.php" id="passwordForm">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label class="form-label">كلمة المرور الحالية</label>
                                <input type="password" name="current_password" class="form-control" 
                                       placeholder="أدخل كلمة المرور الحالية" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" name="new_password" class="form-control" 
                                       placeholder="أدخل كلمة المرور الجديدة" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="confirm_password" class="form-control" 
                                   placeholder="أعد إدخال كلمة المرور الجديدة" required>
                        </div>
                        
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-key"></i>
                            تغيير كلمة المرور
                        </button>
                    </form>
                </div>
            </div>

            <!-- طلباتي الأخيرة -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-between align-center">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt icon"></i>
                        طلباتي الأخيرة
                    </h3>
                    <a href="orders.php" class="btn btn-outline btn-sm">
                        <i class="fas fa-eye"></i>
                        عرض جميع الطلبات
                    </a>
                </div>
                <div class="card-body">
                    <div class="text-center p-4">
                        <div class="icon-lg mb-3" style="color: var(--text-muted);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h4 class="mb-2">لا توجد طلبات بعد</h4>
                        <p class="text-secondary mb-3">ابدأ التسوق الآن واطلب منتجاتك المفضلة</p>
                        <a href="products.php" class="btn btn-primary">
                            <i class="fas fa-shopping-bag"></i>
                            تصفح المنتجات
                        </a>
                    </div>
                </div>
            </div>

            <!-- الإعدادات الإضافية -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog icon"></i>
                        الإعدادات الإضافية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="list">
                        <div class="list-item d-flex justify-between align-center">
                            <div>
                                <h5 class="mb-1">الإشعارات</h5>
                                <p class="text-secondary mb-0">استلام إشعارات عن الطلبات والعروض</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="list-item d-flex justify-between align-center">
                            <div>
                                <h5 class="mb-1">النشرة الإخبارية</h5>
                                <p class="text-secondary mb-0">استلام آخر العروض والمنتجات الجديدة</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="list-item d-flex justify-between align-center">
                            <div>
                                <h5 class="mb-1">الوضع الداكن</h5>
                                <p class="text-secondary mb-0">تفعيل المظهر الداكن للموقع</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="darkModeToggle">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- رسالة تسجيل الدخول -->
            <div class="card text-center">
                <div class="card-body p-5">
                    <div class="icon-lg mb-4" style="color: var(--primary-color);">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h3 class="mb-3">يجب تسجيل الدخول</h3>
                    <p class="text-secondary mb-4">لتتمكن من الوصول إلى صفحة حسابك، يرجى تسجيل الدخول أولاً</p>
                    <div class="d-flex justify-center gap-3">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول
                        </a>
                        <a href="register.php" class="btn btn-outline">
                            <i class="fas fa-user-plus"></i>
                            إنشاء حساب جديد
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- CSS إضافي للصفحة -->
<style>
/* Switch Toggle */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
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
    background-color: var(--border-color);
    transition: var(--transition-normal);
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: var(--transition-normal);
    border-radius: 50%;
}

input:checked + .slider {
    background-color: var(--primary-color);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

/* Gap utility */
.gap-3 {
    gap: var(--spacing-md);
}

/* Page header styling */
.page-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: var(--spacing-xxl) 0;
    border-radius: var(--border-radius-lg);
    margin-bottom: var(--spacing-xxl);
}

.page-header h1 {
    color: white;
    margin-bottom: var(--spacing-sm);
}

.page-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

/* Card enhancements */
.card {
    border: none;
    box-shadow: var(--shadow-light);
    transition: all var(--transition-normal);
}

.card:hover {
    box-shadow: var(--shadow-medium);
}

.card-header {
    background: var(--bg-light);
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
    margin: calc(-1 * var(--spacing-lg)) calc(-1 * var(--spacing-lg)) var(--spacing-lg) calc(-1 * var(--spacing-lg));
    padding: var(--spacing-lg);
}

/* Form enhancements */
.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

/* Button enhancements */
.btn {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

/* Statistics cards */
.icon-lg {
    font-size: 2rem;
    margin-bottom: var(--spacing-md);
}

/* List enhancements */
.list-item {
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-sm);
}

.list-item:hover {
    background: var(--bg-light);
    transform: translateX(4px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        padding: var(--spacing-xl) 0;
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
    
    .d-flex.justify-between {
        flex-direction: column;
        gap: var(--spacing-md);
    }
}
</style>

<!-- JavaScript للصفحة -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', this.checked);
        });
        
        // Load saved preference
        const savedDarkMode = localStorage.getItem('darkMode') === 'true';
        darkModeToggle.checked = savedDarkMode;
        if (savedDarkMode) {
            document.body.classList.add('dark-mode');
        }
    }
    
    // Form validation
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showAlert('يرجى ملء جميع الحقول المطلوبة', 'error');
            }
        });
    }
    
    // Password form validation
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = this.querySelector('input[name="new_password"]');
            const confirmPassword = this.querySelector('input[name="confirm_password"]');
            
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.classList.add('error');
                showAlert('كلمة المرور الجديدة غير متطابقة', 'error');
            }
        });
    }
});

// Alert function
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
        ${message}
    `;
    
    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
