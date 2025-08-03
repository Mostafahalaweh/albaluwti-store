<?php
session_start();

// إذا كان المستخدم مسجل دخول بالفعل، لا حاجة للتحقق
if (isset($_SESSION['user_id'])) {
    return;
}

// التحقق من وجود توكن "تذكرني"
if (isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../php/db.php';
    require_once __DIR__ . '/../php/functions.php';
    
    try {
        $pdo = getConnection();
        $token = $_COOKIE['remember_token'];
        
        // البحث عن التوكن في قاعدة البيانات
        $stmt = $pdo->prepare("
            SELECT rt.*, u.* 
            FROM remember_tokens rt 
            JOIN users u ON rt.user_id = u.id 
            WHERE rt.token = ? AND rt.expires_at > ? AND u.status = 'active'
        ");
        $stmt->execute([$token, time()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // إنشاء جلسة جديدة
            session_regenerate_id(true);
            
            // حفظ بيانات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();
            $_SESSION['auto_login'] = true;
            
            // تحديث آخر تسجيل دخول
            updateLastLogin($user['user_id']);
            
            // إضافة نشاط المستخدم
            addUserActivity($user['user_id'], 'auto_login', 'تم تسجيل الدخول التلقائي');
            
            // إرسال إشعار
            sendNotification($user['user_id'], 'تسجيل دخول تلقائي', 'تم تسجيل دخولك تلقائياً من جهاز معروف', 'info');
            
            // تحديث التوكن
            $newToken = generateRememberToken();
            $expires = time() + (30 * 24 * 60 * 60); // 30 يوم
            
            // حذف التوكن القديمة
            $stmt = $pdo->prepare("DELETE FROM remember_tokens WHERE token = ?");
            $stmt->execute([$token]);
            
            // حفظ التوكن الجديدة
            $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user['user_id'], $newToken, $expires]);
            
            // تحديث الكوكيز
            setcookie('remember_token', $newToken, $expires, '/', '', true, true);
        } else {
            // التوكن غير صالحة، حذفها
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
    } catch (PDOException $e) {
        error_log("Auto login error: " . $e->getMessage());
        // حذف الكوكيز في حالة الخطأ
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// دوال مساعدة
function generateRememberToken() {
    return bin2hex(random_bytes(32));
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