<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل جديد - متجر البلوطي</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/albaluwti_backup/assets/css/main.css">
</head>
<body>
<nav class="navbar">
    <ul>
        <li><a href="index.php">الرئيسية</a></li>
        <li><a href="products.php">المنتجات</a></li>
        <li><a href="cart.php">السلة</a></li>
        <li><a href="login.php">دخول</a></li>
        <li><a href="register.php" class="active">تسجيل</a></li>
    </ul>
</nav>
<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'إنشاء حساب جديد - متجر البلوطي';
$active = 'register';

include __DIR__ . '/../includes/header.php';
?>

<!-- إضافة ملف CSS الحديث -->
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/modern-design.css">

<main class="main-content">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <!-- رأس البطاقة -->
                <div class="auth-header text-center mb-4">
                    <div class="auth-icon mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="auth-title">إنشاء حساب جديد</h1>
                    <p class="auth-subtitle">انضم إلينا واستمتع بتجربة تسوق مميزة</p>
                </div>

                <!-- رسائل الخطأ أو النجاح -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- نموذج التسجيل -->
                <form method="post" action="register_process.php" class="auth-form" id="registerForm">
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user icon-sm"></i>
                                الاسم الأول
                            </label>
                            <input type="text" name="first_name" class="form-control" 
                                   placeholder="أدخل اسمك الأول" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user icon-sm"></i>
                                اسم العائلة
                            </label>
                            <input type="text" name="last_name" class="form-control" 
                                   placeholder="أدخل اسم العائلة" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope icon-sm"></i>
                            البريد الإلكتروني
                        </label>
                        <input type="email" name="email" class="form-control" 
                               placeholder="أدخل بريدك الإلكتروني" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone icon-sm"></i>
                            رقم الهاتف
                        </label>
                        <input type="tel" name="phone" class="form-control" 
                               placeholder="أدخل رقم هاتفك">
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt icon-sm"></i>
                            العنوان
                        </label>
                        <textarea name="address" class="form-control" rows="3" 
                                  placeholder="أدخل عنوانك"></textarea>
                    </div>

                    <div class="grid grid-2">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock icon-sm"></i>
                                كلمة المرور
                            </label>
                            <div class="password-input">
                                <input type="password" name="password" class="form-control" 
                                       placeholder="أدخل كلمة المرور" required id="passwordInput">
                                <button type="button" class="password-toggle" id="passwordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock icon-sm"></i>
                                تأكيد كلمة المرور
                            </label>
                            <div class="password-input">
                                <input type="password" name="confirm_password" class="form-control" 
                                       placeholder="أعد إدخال كلمة المرور" required id="confirmPasswordInput">
                                <button type="button" class="password-toggle" id="confirmPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" class="checkbox-input" required>
                            <span class="checkbox-custom"></span>
                            أوافق على <a href="terms.php" class="link-primary">الشروط والأحكام</a> و 
                            <a href="privacy.php" class="link-primary">سياسة الخصوصية</a>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter" class="checkbox-input">
                            <span class="checkbox-custom"></span>
                            أريد استلام النشرة الإخبارية والعروض الخاصة
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-user-plus"></i>
                        إنشاء الحساب
                    </button>
                </form>

                <!-- خط فاصل -->
                <div class="divider">
                    <span>أو</span>
                </div>

                <!-- التسجيل بالوسائل الاجتماعية -->
                <div class="social-login">
                    <button class="btn btn-ghost btn-lg w-100 mb-2">
                        <i class="fab fa-google"></i>
                        التسجيل بـ Google
                    </button>
                    <button class="btn btn-ghost btn-lg w-100">
                        <i class="fab fa-facebook"></i>
                        التسجيل بـ Facebook
                    </button>
                </div>

                <!-- روابط إضافية -->
                <div class="auth-links text-center mt-4">
                    <p class="text-secondary">
                        لديك حساب بالفعل؟ 
                        <a href="login.php" class="link-primary">تسجيل الدخول</a>
                    </p>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="auth-info">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3>عروض حصرية</h3>
                    <p>احصل على عروض وخصومات حصرية لأعضاء الموقع</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>نقاط المكافآت</h3>
                    <p>اكسب نقاط مع كل عملية شراء واستبدلها بخصومات</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>شحن مجاني</h3>
                    <p>شحن مجاني للطلبات التي تزيد عن 200 د.ك</p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- CSS إضافي للصفحة -->
<style>
/* Auth Container */
.auth-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xxl);
    align-items: center;
    min-height: 80vh;
    padding: var(--spacing-xl) 0;
}

/* Auth Card */
.auth-card {
    background: var(--bg-primary);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-dark);
    padding: var(--spacing-xxl);
    max-width: 600px;
    margin: 0 auto;
    width: 100%;
}

.auth-header {
    margin-bottom: var(--spacing-xl);
}

.auth-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
}

.auth-icon i {
    font-size: 2.5rem;
    color: white;
}

.auth-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.auth-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Form Styling */
.auth-form .form-group {
    margin-bottom: var(--spacing-lg);
}

.form-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-weight: 600;
    color: var(--text-primary);
}

.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: var(--spacing-md);
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: var(--spacing-xs);
    transition: color var(--transition-fast);
}

.password-toggle:hover {
    color: var(--secondary-color);
}

/* Checkbox Styling */
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-sm);
    cursor: pointer;
    font-weight: 500;
    color: var(--text-primary);
    line-height: 1.5;
}

.checkbox-input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    position: relative;
    transition: all var(--transition-fast);
    flex-shrink: 0;
    margin-top: 2px;
}

.checkbox-input:checked + .checkbox-custom {
    background: var(--secondary-color);
    border-color: var(--secondary-color);
}

.checkbox-input:checked + .checkbox-custom::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

/* Divider */
.divider {
    text-align: center;
    margin: var(--spacing-xl) 0;
    position: relative;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border-color);
}

.divider span {
    background: var(--bg-primary);
    padding: 0 var(--spacing-md);
    color: var(--text-secondary);
    font-weight: 500;
}

/* Social Login */
.social-login {
    margin-bottom: var(--spacing-lg);
}

.social-login .btn {
    border: 2px solid var(--border-color);
    background: var(--bg-primary);
    color: var(--text-primary);
    transition: all var(--transition-normal);
}

.social-login .btn:hover {
    border-color: var(--secondary-color);
    background: var(--secondary-color);
    color: white;
    transform: translateY(-2px);
}

/* Auth Links */
.auth-links {
    border-top: 1px solid var(--border-color);
    padding-top: var(--spacing-lg);
}

.link-primary {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.link-primary:hover {
    color: var(--secondary-dark);
    text-decoration: underline;
}

/* Auth Info */
.auth-info {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.info-card {
    background: var(--bg-primary);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    text-align: center;
    box-shadow: var(--shadow-light);
    transition: all var(--transition-normal);
    border: 1px solid var(--border-color);
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.info-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.info-icon i {
    font-size: 1.5rem;
    color: white;
}

.info-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.info-card p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

/* Utility Classes */
.w-100 {
    width: 100%;
}

/* Password Strength Indicator */
.password-strength {
    margin-top: var(--spacing-sm);
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
}

.password-strength.weak {
    background: rgba(244, 67, 54, 0.1);
    color: var(--error-color);
    border: 1px solid var(--error-color);
}

.password-strength.medium {
    background: rgba(255, 152, 0, 0.1);
    color: var(--warning-color);
    border: 1px solid var(--warning-color);
}

.password-strength.strong {
    background: rgba(76, 175, 80, 0.1);
    color: var(--success-color);
    border: 1px solid var(--success-color);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .auth-container {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .auth-info {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .info-card {
        flex: 1;
        min-width: 250px;
    }
}

@media (max-width: 768px) {
    .auth-card {
        padding: var(--spacing-lg);
        margin: 0 var(--spacing-sm);
    }
    
    .auth-title {
        font-size: 1.75rem;
    }
    
    .grid-2 {
        grid-template-columns: 1fr;
    }
    
    .auth-info {
        flex-direction: column;
    }
    
    .info-card {
        min-width: auto;
    }
}

@media (max-width: 480px) {
    .auth-container {
        padding: var(--spacing-md) 0;
    }
    
    .auth-card {
        padding: var(--spacing-md);
    }
    
    .auth-icon {
        width: 60px;
        height: 60px;
    }
    
    .auth-icon i {
        font-size: 2rem;
    }
    
    .auth-title {
        font-size: 1.5rem;
    }
}

/* Dark Mode Adjustments */
body.dark-mode .auth-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
}

body.dark-mode .info-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
}

body.dark-mode .divider span {
    background: var(--bg-secondary);
}
</style>

<!-- JavaScript للصفحة -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const passwordInput = document.getElementById('passwordInput');
    const passwordToggle = document.getElementById('passwordToggle');
    const confirmPasswordInput = document.getElementById('confirmPasswordInput');
    const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
    
    // Password toggle
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Confirm password toggle
    if (confirmPasswordToggle && confirmPasswordInput) {
        confirmPasswordToggle.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // Password strength checker
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }
    
    // Form validation
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="password"]');
            const confirmPassword = this.querySelector('input[name="confirm_password"]');
            const terms = this.querySelector('input[name="terms"]');
            let isValid = true;
            
            // Password validation
            if (password.value.length < 8) {
                password.classList.add('error');
                isValid = false;
            } else {
                password.classList.remove('error');
            }
            
            // Confirm password validation
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('error');
                isValid = false;
            } else {
                confirmPassword.classList.remove('error');
            }
            
            // Terms validation
            if (!terms.checked) {
                terms.parentElement.classList.add('error');
                isValid = false;
            } else {
                terms.parentElement.classList.remove('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                showAlert('يرجى التأكد من صحة البيانات المدخلة', 'error');
            }
        });
    }
    
    // Password strength function
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = '';
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        let strengthIndicator = document.getElementById('passwordStrength');
        if (!strengthIndicator) {
            strengthIndicator = document.createElement('div');
            strengthIndicator.id = 'passwordStrength';
            strengthIndicator.className = 'password-strength';
            passwordInput.parentNode.appendChild(strengthIndicator);
        }
        
        if (strength < 3) {
            strengthIndicator.className = 'password-strength weak';
            feedback = 'كلمة المرور ضعيفة';
        } else if (strength < 4) {
            strengthIndicator.className = 'password-strength medium';
            feedback = 'كلمة المرور متوسطة';
        } else {
            strengthIndicator.className = 'password-strength strong';
            feedback = 'كلمة المرور قوية';
        }
        
        strengthIndicator.textContent = feedback;
    }
    
    // Alert function
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            ${message}
        `;
        
        const container = document.querySelector('.auth-card');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    // Auto-focus on first name input
    const firstNameInput = document.querySelector('input[name="first_name"]');
    if (firstNameInput) {
        firstNameInput.focus();
    }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?> 