<?php
// ملف إنشاء قاعدة البيانات وجداولها
require_once 'php/db.php';

try {
    $pdo = getConnection();
    
    if (!$pdo) {
        die("فشل في الاتصال بقاعدة البيانات");
    }
    
    echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n";
    
    // إنشاء الجداول الأساسية
    $tables = [
        // جدول المستخدمين
        "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            status VARCHAR(20) DEFAULT 'active',
            role VARCHAR(20) DEFAULT 'user',
            last_login INTEGER DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // جدول محاولات تسجيل الدخول الفاشلة
        "CREATE TABLE IF NOT EXISTS failed_logins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            attempt_time INTEGER NOT NULL,
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // جدول تسجيلات تسجيل الدخول الناجحة
        "CREATE TABLE IF NOT EXISTS user_logins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            login_time INTEGER NOT NULL,
            user_agent TEXT,
            session_id VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // جدول توكن "تذكرني"
        "CREATE TABLE IF NOT EXISTS remember_tokens (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // جدول المنتجات
        "CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            sale_price DECIMAL(10,2),
            category_id INTEGER,
            image VARCHAR(255),
            gallery TEXT,
            featured BOOLEAN DEFAULT 0,
            stock_quantity INTEGER DEFAULT 0,
            status VARCHAR(20) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // جدول الفئات
        "CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            parent_id INTEGER DEFAULT NULL,
            status VARCHAR(20) DEFAULT 'active',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
        )",
        
        // جدول الطلبات
        "CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            shipping_address TEXT,
            payment_method VARCHAR(50),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // جدول تفاصيل الطلبات
        "CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )",
        
        // جدول المفضلة
        "CREATE TABLE IF NOT EXISTS wishlist (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            UNIQUE(user_id, product_id)
        )",
        
        // جدول نشاطات المستخدم
        "CREATE TABLE IF NOT EXISTS user_activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            activity_type VARCHAR(50) NOT NULL,
            description TEXT,
            related_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // جدول الإشعارات
        "CREATE TABLE IF NOT EXISTS notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            type VARCHAR(20) DEFAULT 'info',
            action_url VARCHAR(255),
            is_read BOOLEAN DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // جدول إعدادات المستخدم
        "CREATE TABLE IF NOT EXISTS user_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            setting_key VARCHAR(100) NOT NULL,
            setting_value TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(user_id, setting_key)
        )",
        
        // جدول خصوصية المستخدم
        "CREATE TABLE IF NOT EXISTS user_privacy (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            profile_visibility VARCHAR(20) DEFAULT 'public',
            email_visibility BOOLEAN DEFAULT 1,
            activity_visibility BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];
    
    // إنشاء الجداول
    foreach ($tables as $sql) {
        $pdo->exec($sql);
        echo "✅ تم إنشاء الجدول بنجاح\n";
    }
    
    // إنشاء الفهارس
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_failed_logins_email ON failed_logins(email)",
        "CREATE INDEX IF NOT EXISTS idx_failed_logins_time ON failed_logins(attempt_time)",
        "CREATE INDEX IF NOT EXISTS idx_user_logins_user_id ON user_logins(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_user_logins_time ON user_logins(login_time)",
        "CREATE INDEX IF NOT EXISTS idx_remember_tokens_user_id ON remember_tokens(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_remember_tokens_token ON remember_tokens(token)",
        "CREATE INDEX IF NOT EXISTS idx_remember_tokens_expires ON remember_tokens(expires_at)",
        "CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id)",
        "CREATE INDEX IF NOT EXISTS idx_products_featured ON products(featured)",
        "CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status)",
        "CREATE INDEX IF NOT EXISTS idx_wishlist_user_id ON wishlist(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_notifications_user_id ON notifications(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_notifications_read ON notifications(is_read)"
    ];
    
    foreach ($indexes as $sql) {
        $pdo->exec($sql);
        echo "✅ تم إنشاء الفهرس بنجاح\n";
    }
    
    // إضافة مستخدم تجريبي
    $testUser = [
        'name' => 'مستخدم تجريبي',
        'email' => 'test@example.com',
        'password' => password_hash('123456', PASSWORD_DEFAULT),
        'phone' => '0123456789',
        'status' => 'active',
        'role' => 'user'
    ];
    
    $stmt = $pdo->prepare("
        INSERT OR IGNORE INTO users (name, email, password, phone, status, role) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $testUser['name'],
        $testUser['email'],
        $testUser['password'],
        $testUser['phone'],
        $testUser['status'],
        $testUser['role']
    ]);
    
    echo "✅ تم إضافة المستخدم التجريبي\n";
    echo "📧 البريد الإلكتروني: test@example.com\n";
    echo "🔑 كلمة المرور: 123456\n";
    
    // إضافة فئة تجريبية
    $stmt = $pdo->prepare("
        INSERT OR IGNORE INTO categories (name, description) 
        VALUES (?, ?)
    ");
    $stmt->execute(['الإلكترونيات', 'جميع أنواع الأجهزة الإلكترونية']);
    
    echo "✅ تم إضافة فئة تجريبية\n";
    
    echo "\n🎉 تم إنشاء قاعدة البيانات بنجاح!\n";
    echo "📊 عدد الجداول: " . count($tables) . "\n";
    echo "🔍 عدد الفهارس: " . count($indexes) . "\n";
    
} catch (PDOException $e) {
    die("❌ خطأ في إنشاء قاعدة البيانات: " . $e->getMessage());
}
?> 