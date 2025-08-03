<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

// منع CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php?error=طريقة طلب غير صحيحة');
    exit();
}

// التحقق من وجود البيانات المطلوبة
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: login.php?error=جميع الحقول مطلوبة');
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

// تنظيف وتصحيح البيانات
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: login.php?error=البريد الإلكتروني غير صحيح');
    exit();
}

try {
    $pdo = getConnection();
    
    // البحث عن المستخدم
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // تسجيل محاولة تسجيل دخول فاشلة
        logFailedLogin($email, $_SERVER['REMOTE_ADDR']);
        header('Location: login.php?error=البريد الإلكتروني أو كلمة المرور غير صحيحة');
        exit();
    }
    
    // التحقق من كلمة المرور
    if (!password_verify($password, $user['password'])) {
        // تسجيل محاولة تسجيل دخول فاشلة
        logFailedLogin($email, $_SERVER['REMOTE_ADDR']);
        header('Location: login.php?error=البريد الإلكتروني أو كلمة المرور غير صحيحة');
        exit();
    }
    
    // التحقق من حظر الحساب
    if ($user['status'] === 'banned') {
        header('Location: login.php?error=تم حظر حسابك. يرجى التواصل مع الإدارة');
        exit();
    }
    
    // التحقق من عدد محاولات تسجيل الدخول الفاشلة
    $failedAttempts = getFailedLoginAttempts($email);
    if ($failedAttempts >= 5) {
        $lastAttempt = getLastFailedLoginTime($email);
        $timeDiff = time() - $lastAttempt;
        
        if ($timeDiff < 900) { // 15 دقيقة
            $remainingTime = ceil((900 - $timeDiff) / 60);
            header("Location: login.php?error=تم حظر تسجيل الدخول مؤقتاً. حاول مرة أخرى بعد {$remainingTime} دقيقة");
            exit();
        } else {
            // إعادة تعيين محاولات تسجيل الدخول الفاشلة
            resetFailedLoginAttempts($email);
        }
    }
    
    // تسجيل تسجيل الدخول الناجح
    logSuccessfulLogin($user['id'], $_SERVER['REMOTE_ADDR']);
    
    // إنشاء جلسة جديدة
    session_regenerate_id(true);
    
    // حفظ بيانات المستخدم في الجلسة
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['login_time'] = time();
    $_SESSION['last_activity'] = time();
    
    // إذا اختار "تذكرني"
    if ($remember) {
        $token = generateRememberToken();
        $expires = time() + (30 * 24 * 60 * 60); // 30 يوم
        
        // حفظ التوكن في قاعدة البيانات
        saveRememberToken($user['id'], $token, $expires);
        
        // حفظ التوكن في الكوكيز
        setcookie('remember_token', $token, $expires, '/', '', true, true);
    }
    
    // تحديث آخر تسجيل دخول
    updateLastLogin($user['id']);
    
    // إضافة نشاط المستخدم
    addUserActivity($user['id'], 'login', 'تم تسجيل الدخول بنجاح');
    
    // إرسال إشعار ترحيب
    sendNotification($user['id'], 'مرحباً بك!', 'تم تسجيل دخولك بنجاح إلى حسابك', 'success');
    
    // إعادة التوجيه إلى الصفحة المطلوبة أو لوحة التحكم
    $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : 'dashboard.php';
    unset($_SESSION['redirect_after_login']);
    
    header("Location: $redirect?success=تم تسجيل الدخول بنجاح");
    exit();
    
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    header('Location: login.php?error=حدث خطأ في النظام. يرجى المحاولة مرة أخرى');
    exit();
}

// دوال مساعدة
function logFailedLogin($email, $ip) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO failed_logins (email, ip_address, attempt_time) VALUES (?, ?, ?)");
        $stmt->execute([$email, $ip, time()]);
    } catch (PDOException $e) {
        error_log("Failed to log failed login: " . $e->getMessage());
    }
}

function logSuccessfulLogin($userId, $ip) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO user_logins (user_id, ip_address, login_time) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $ip, time()]);
    } catch (PDOException $e) {
        error_log("Failed to log successful login: " . $e->getMessage());
    }
}

function getFailedLoginAttempts($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM failed_logins WHERE email = ? AND attempt_time > ?");
        $stmt->execute([$email, time() - 900]); // آخر 15 دقيقة
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Failed to get failed login attempts: " . $e->getMessage());
        return 0;
    }
}

function getLastFailedLoginTime($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT MAX(attempt_time) FROM failed_logins WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        error_log("Failed to get last failed login time: " . $e->getMessage());
        return 0;
    }
}

function resetFailedLoginAttempts($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM failed_logins WHERE email = ?");
        $stmt->execute([$email]);
    } catch (PDOException $e) {
        error_log("Failed to reset failed login attempts: " . $e->getMessage());
    }
}

function generateRememberToken() {
    return bin2hex(random_bytes(32));
}

function saveRememberToken($userId, $token, $expires) {
    global $pdo;
    try {
        // حذف التوكن القديمة إن وجدت
        $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        // حفظ التوكن الجديدة
        $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $token, $expires]);
    } catch (PDOException $e) {
        error_log("Failed to save remember token: " . $e->getMessage());
    }
}

function updateLastLogin($userId) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE users SET last_login = ? WHERE id = ?");
        $stmt->execute([time(), $userId]);
    } catch (PDOException $e) {
        error_log("Failed to update last login: " . $e->getMessage());
    }
}
?> 