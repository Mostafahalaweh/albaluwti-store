<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'تسجيل الدخول - متجر البلوطي';
$active = 'login';

include '../includes/header.php';
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
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h1 class="auth-title">تسجيل الدخول</h1>
                    <p class="auth-subtitle">مرحباً بك مرة أخرى! سجل دخولك للوصول إلى حسابك</p>
                </div>

                <!-- رسائل الخطأ أو النجاح -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error" id="errorAlert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success" id="successAlert">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($_GET['success']); ?>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- نموذج تسجيل الدخول -->
                <form method="post" action="login_process.php" class="auth-form" id="loginForm">
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
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText">قوة كلمة المرور</span>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-between align-center">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" class="checkbox-input" id="rememberMe">
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">تذكرني لمدة 30 يوم</span>
                        </label>
                        <a href="forgot-password.php" class="forgot-link">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="btn-text">تسجيل الدخول</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                            جاري التحقق...
                        </span>
                    </button>
                    
                    <!-- عداد المحاولات المتبقية -->
                    <div class="attempts-counter" id="attemptsCounter" style="display: none;">
                        <i class="fas fa-shield-alt"></i>
                        <span id="attemptsText">المحاولات المتبقية: 5</span>
                    </div>
                </form>

                <!-- خط فاصل -->
                <div class="divider">
                    <span>أو</span>
                </div>

                <!-- تسجيل الدخول بالوسائل الاجتماعية -->
                <div class="social-login">
                    <button type="button" class="btn btn-ghost btn-lg w-100 mb-2" onclick="socialLogin('google')">
                        <i class="fab fa-google"></i>
                        <span>تسجيل الدخول بـ Google</span>
                    </button>
                    <button type="button" class="btn btn-ghost btn-lg w-100" onclick="socialLogin('facebook')">
                        <i class="fab fa-facebook"></i>
                        <span>تسجيل الدخول بـ Facebook</span>
                    </button>
                </div>

                <!-- روابط إضافية -->
                <div class="auth-links text-center mt-4">
                    <p class="text-secondary">
                        ليس لديك حساب؟ 
                        <a href="register.php" class="link-primary">إنشاء حساب جديد</a>
                    </p>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="auth-info">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>حماية آمنة</h3>
                    <p>بياناتك محمية ومشفرة بأحدث تقنيات الأمان</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>توصيل سريع</h3>
                    <p>احصل على طلباتك بسرعة مع خدمة التوصيل المميزة</p>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>دعم 24/7</h3>
                    <p>فريق دعم متاح على مدار الساعة لمساعدتك</p>
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
    position: relative;
    overflow: hidden;
}

.auth-container::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, var(--primary-color) 0%, transparent 50%, var(--accent-color) 100%);
    opacity: 0.05;
    animation: float 20s ease-in-out infinite;
    z-index: -1;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(-10px, -10px) rotate(1deg); }
    50% { transform: translate(10px, -20px) rotate(-1deg); }
    75% { transform: translate(-10px, -10px) rotate(1deg); }
}

/* Auth Card */
.auth-card {
    background: var(--bg-primary);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-dark);
    padding: var(--spacing-xxl);
    max-width: 500px;
    margin: 0 auto;
    width: 100%;
    position: relative;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.auth-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

/* تحسين تأثيرات الحقول */
.form-control {
    transition: all 0.3s ease;
    border: 2px solid var(--border-color);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
}

.form-control:hover {
    border-color: var(--primary-color);
}

.auth-header {
    margin-bottom: var(--spacing-xl);
}

.auth-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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

/* Alert Styling */
.alert {
    position: relative;
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-lg);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    animation: slideIn 0.3s ease;
}

.alert-error {
    background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    color: white;
    border: 1px solid #ff4757;
}

.alert-success {
    background: linear-gradient(135deg, #2ed573, #1e90ff);
    color: white;
    border: 1px solid #2ed573;
}

.alert-info {
    background: linear-gradient(135deg, #3742fa, #2f3542);
    color: white;
    border: 1px solid #3742fa;
}

.alert-warning {
    background: linear-gradient(135deg, #ffa502, #ff6348);
    color: white;
    border: 1px solid #ffa502;
}

/* تحسين تأثيرات التنبيهات */
.alert {
    position: relative;
    overflow: hidden;
}

.alert::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.alert-close {
    position: absolute;
    right: var(--spacing-sm);
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    padding: var(--spacing-xs);
    border-radius: 50%;
    transition: all 0.2s ease;
}

.alert-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Form Styling */
.auth-form .form-group {
    margin-bottom: var(--spacing-lg);
    position: relative;
}

.form-control.error {
    border-color: #ff4757;
    box-shadow: 0 0 0 2px rgba(255, 71, 87, 0.2);
}

.field-error {
    color: #ff4757;
    font-size: 0.85rem;
    margin-top: var(--spacing-xs);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    animation: slideIn 0.3s ease;
}

.field-error::before {
    content: '⚠️';
    font-size: 0.75rem;
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
    color: var(--primary-color);
    transform: scale(1.1);
}

.password-toggle {
    transition: all 0.2s ease;
}

.password-input:focus-within {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    border-color: var(--primary-color);
}

/* Password Strength */
.password-strength {
    margin-top: var(--spacing-sm);
    display: none;
}

.password-strength.show {
    display: block;
    animation: fadeIn 0.3s ease;
}

.strength-bar {
    width: 100%;
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: var(--spacing-xs);
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-fill.weak {
    width: 25%;
    background: #ff4757;
}

.strength-fill.fair {
    width: 50%;
    background: #ffa502;
}

.strength-fill.good {
    width: 75%;
    background: #2ed573;
}

.strength-fill.strong {
    width: 100%;
    background: #1e90ff;
}

.strength-text {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Checkbox Styling */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    cursor: pointer;
    font-weight: 500;
    color: var(--text-primary);
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
}

.checkbox-input:checked + .checkbox-custom {
    background: var(--primary-color);
    border-color: var(--primary-color);
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

.forgot-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition-fast);
}

.forgot-link:hover {
    color: var(--primary-dark);
    text-decoration: underline;
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
    border-color: var(--primary-color);
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.social-login .btn:active {
    transform: translateY(0);
}

.social-login .btn:first-child:hover {
    background: linear-gradient(135deg, #4285f4, #34a853);
    border-color: #4285f4;
}

.social-login .btn:last-child:hover {
    background: linear-gradient(135deg, #1877f2, #42a5f5);
    border-color: #1877f2;
}

/* Attempts Counter */
.attempts-counter {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-top: var(--spacing-md);
    padding: var(--spacing-sm) var(--spacing-md);
    background: linear-gradient(135deg, #ffa502, #ff6348);
    color: white;
    border-radius: var(--border-radius-md);
    font-size: 0.9rem;
    font-weight: 500;
    animation: slideIn 0.3s ease;
}

.attempts-counter i {
    font-size: 1rem;
}

/* Button Loading State */
.btn-loading {
    display: none;
}

.btn.loading .btn-text {
    display: none;
}

.btn.loading .btn-loading {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.btn.loading {
    pointer-events: none;
    opacity: 0.8;
}

/* Auth Links */
.auth-links {
    border-top: 1px solid var(--border-color);
    padding-top: var(--spacing-lg);
}

.link-primary {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color var(--transition-fast);
}

.link-primary:hover {
    color: var(--primary-dark);
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
    background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
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
    
    .auth-container::before {
        display: none;
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
    
    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
            
            // إضافة تأثير بصري
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        });
        
        // إضافة تأثير عند التركيز
        passwordInput.addEventListener('focus', function() {
            this.parentNode.style.boxShadow = '0 0 0 2px rgba(var(--primary-color-rgb), 0.2)';
        });
        
        passwordInput.addEventListener('blur', function() {
            this.parentNode.style.boxShadow = '';
        });
    }
        
        // Password strength checker
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    }
    
    // Password strength function
    function checkPasswordStrength(password) {
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        if (!password) {
            strengthDiv.classList.remove('show');
            return;
        }
        
        strengthDiv.classList.add('show');
        
        let score = 0;
        let feedback = '';
        
        // Length check
        if (password.length >= 8) score += 1;
        if (password.length >= 12) score += 1;
        
        // Character variety checks
        if (/[a-z]/.test(password)) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        
        // Remove old classes
        strengthFill.className = 'strength-fill';
        
        if (score <= 2) {
            strengthFill.classList.add('weak');
            feedback = 'ضعيفة جداً';
        } else if (score <= 3) {
            strengthFill.classList.add('fair');
            feedback = 'ضعيفة';
        } else if (score <= 4) {
            strengthFill.classList.add('good');
            feedback = 'جيدة';
        } else {
            strengthFill.classList.add('strong');
            feedback = 'قوية جداً';
        }
        
        strengthText.textContent = `قوة كلمة المرور: ${feedback}`;
    }
    
    // Form validation and submission
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[name="email"]');
            const password = this.querySelector('input[name="password"]');
            let isValid = true;
            
            // Reset previous errors
            document.querySelectorAll('.form-control.error').forEach(input => {
                input.classList.remove('error');
            });
            
            // Email validation
            if (!email.value.trim()) {
                email.classList.add('error');
                showFieldError(email, 'البريد الإلكتروني مطلوب');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                email.classList.add('error');
                showFieldError(email, 'البريد الإلكتروني غير صحيح');
                isValid = false;
            }
            
            // Password validation
            if (!password.value.trim()) {
                password.classList.add('error');
                showFieldError(password, 'كلمة المرور مطلوبة');
                isValid = false;
            }
            
            if (!isValid) {
                showAlert('يرجى التأكد من صحة البيانات المدخلة', 'error');
                return;
            }
            
            // Show loading state
            loginBtn.classList.add('loading');
            
            // Submit form
            this.submit();
        });
    }
    
    // Show field error function
    function showFieldError(field, message) {
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    // Email validation function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
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
    
    // Auto-focus on email input
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.focus();
    }
    
    // Remember me functionality
    const rememberMe = document.getElementById('rememberMe');
    if (rememberMe) {
        // Check if user previously chose to remember
        if (localStorage.getItem('rememberMe') === 'true') {
            rememberMe.checked = true;
        }
        
        rememberMe.addEventListener('change', function() {
            localStorage.setItem('rememberMe', this.checked);
        });
    }
    
    // Social login function
    window.socialLogin = function(provider) {
        showAlert(`سيتم إضافة تسجيل الدخول بـ ${provider} قريباً`, 'info');
        // TODO: Implement social login
    };
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
    
    // فحص المحاولات المتبقية عند تحميل الصفحة
    checkRemainingAttempts();
    
    // دالة فحص المحاولات المتبقية
    function checkRemainingAttempts() {
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput && emailInput.value.trim()) {
            fetch('check_attempts.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(emailInput.value.trim())
            })
            .then(response => response.json())
            .then(data => {
                if (data.attempts < 5) {
                    showAttemptsCounter(5 - data.attempts);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    // دالة عرض عداد المحاولات
    function showAttemptsCounter(remaining) {
        const counter = document.getElementById('attemptsCounter');
        const text = document.getElementById('attemptsText');
        
        if (counter && text) {
            text.textContent = `المحاولات المتبقية: ${remaining}`;
            counter.style.display = 'flex';
        }
    }
    
    // فحص المحاولات عند تغيير البريد الإلكتروني
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                checkRemainingAttempts();
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?> 