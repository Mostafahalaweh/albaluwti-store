-- جداول الأمان الجديدة لنظام تسجيل الدخول المحسن

-- جدول محاولات تسجيل الدخول الفاشلة
CREATE TABLE IF NOT EXISTS failed_logins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempt_time INTEGER NOT NULL,
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- جدول تسجيلات تسجيل الدخول الناجحة
CREATE TABLE IF NOT EXISTS user_logins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    login_time INTEGER NOT NULL,
    user_agent TEXT,
    session_id VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- جدول توكن "تذكرني"
CREATE TABLE IF NOT EXISTS remember_tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- إضافة عمود last_login إلى جدول المستخدمين إذا لم يكن موجوداً
ALTER TABLE users ADD COLUMN last_login INTEGER DEFAULT NULL;

-- إضافة عمود status إلى جدول المستخدمين إذا لم يكن موجوداً
ALTER TABLE users ADD COLUMN status VARCHAR(20) DEFAULT 'active';

-- إضافة عمود role إلى جدول المستخدمين إذا لم يكن موجوداً
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';

-- إنشاء فهارس لتحسين الأداء
CREATE INDEX IF NOT EXISTS idx_failed_logins_email ON failed_logins(email);
CREATE INDEX IF NOT EXISTS idx_failed_logins_time ON failed_logins(attempt_time);
CREATE INDEX IF NOT EXISTS idx_user_logins_user_id ON user_logins(user_id);
CREATE INDEX IF NOT EXISTS idx_user_logins_time ON user_logins(login_time);
CREATE INDEX IF NOT EXISTS idx_remember_tokens_user_id ON remember_tokens(user_id);
CREATE INDEX IF NOT EXISTS idx_remember_tokens_token ON remember_tokens(token);
CREATE INDEX IF NOT EXISTS idx_remember_tokens_expires ON remember_tokens(expires_at);

-- تنظيف البيانات القديمة (حذف محاولات تسجيل الدخول الفاشلة الأقدم من 24 ساعة)
DELETE FROM failed_logins WHERE attempt_time < (strftime('%s', 'now') - 86400);

-- تنظيف توكن "تذكرني" المنتهية الصلاحية
DELETE FROM remember_tokens WHERE expires_at < strftime('%s', 'now');

-- إضافة بيانات تجريبية (اختياري)
-- INSERT INTO users (name, email, password, status, role) VALUES 
-- ('مستخدم تجريبي', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', 'user');

-- تعليق: كلمة المرور في المثال أعلاه هي "password" مشفرة 