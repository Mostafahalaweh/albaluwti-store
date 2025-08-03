<?php
// ====== إنشاء قاعدة البيانات SQLite لمتجر البلوطي ======

// إعدادات قاعدة البيانات
define('DB_PATH', __DIR__ . '/db/database.db');

// التأكد من وجود مجلد قاعدة البيانات
$dbDir = dirname(DB_PATH);
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

try {
    // إنشاء اتصال SQLite
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // تفعيل المفاتيح الأجنبية
    $pdo->exec('PRAGMA foreign_keys = ON');
    
    // تعيين الترميز
    $pdo->exec('PRAGMA encoding = "UTF-8"');
    
    echo "✅ تم إنشاء اتصال قاعدة البيانات بنجاح\n";
    
    // إنشاء الجداول
    
    // جدول المستخدمين
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            address TEXT,
            role VARCHAR(20) DEFAULT 'user',
            status VARCHAR(20) DEFAULT 'active',
            reset_token VARCHAR(64),
            reset_expires DATETIME,
            last_login INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ تم إنشاء جدول المستخدمين\n";
    
    // جدول الفئات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            image_url VARCHAR(255),
            status VARCHAR(20) DEFAULT 'active',
            sort_order INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ تم إنشاء جدول الفئات\n";
    
    // جدول المنتجات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(200) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            original_price DECIMAL(10,2),
            image_url VARCHAR(255),
            category_id INTEGER,
            stock INTEGER DEFAULT 0,
            sku VARCHAR(50),
            weight DECIMAL(8,2),
            dimensions VARCHAR(100),
            status VARCHAR(20) DEFAULT 'active',
            featured BOOLEAN DEFAULT 0,
            views INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        )
    ");
    echo "✅ تم إنشاء جدول المنتجات\n";
    
    // جدول الطلبات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            order_number VARCHAR(50) UNIQUE NOT NULL,
            customer_name VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            shipping_address TEXT NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            shipping_cost DECIMAL(10,2) DEFAULT 0.00,
            tax DECIMAL(10,2) DEFAULT 0.00,
            discount DECIMAL(10,2) DEFAULT 0.00,
            payment_method VARCHAR(20) DEFAULT 'cash',
            payment_status VARCHAR(20) DEFAULT 'pending',
            status VARCHAR(20) DEFAULT 'pending',
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✅ تم إنشاء جدول الطلبات\n";
    
    // جدول عناصر الطلبات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            product_id INTEGER,
            product_name VARCHAR(200) NOT NULL,
            quantity INTEGER NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
        )
    ");
    echo "✅ تم إنشاء جدول عناصر الطلبات\n";
    
    // جدول السلة
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cart (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            session_id VARCHAR(255),
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "✅ تم إنشاء جدول السلة\n";
    
    // جدول التقييمات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
            comment TEXT,
            status VARCHAR(20) DEFAULT 'approved',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "✅ تم إنشاء جدول التقييمات\n";
    
    // جدول رسائل الاتصال
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(200) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(20) DEFAULT 'unread',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ تم إنشاء جدول رسائل الاتصال\n";
    
    // جدول الإعدادات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            description TEXT,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ تم إنشاء جدول الإعدادات\n";
    
    // جدول سجل النشاطات
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS activity_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            action VARCHAR(100) NOT NULL,
            description TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    echo "✅ تم إنشاء جدول سجل النشاطات\n";
    
    // جداول الأمان الجديدة
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS failed_logins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email VARCHAR(255) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            attempt_time INTEGER NOT NULL,
            user_agent TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ تم إنشاء جدول محاولات تسجيل الدخول الفاشلة\n";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_logins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            login_time INTEGER NOT NULL,
            user_agent TEXT,
            session_id VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✅ تم إنشاء جدول تسجيلات تسجيل الدخول\n";
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS remember_tokens (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            token VARCHAR(255) NOT NULL UNIQUE,
            expires_at INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✅ تم إنشاء جدول توكن تذكرني\n";
    
    // إنشاء الفهارس لتحسين الأداء
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)",
        "CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)",
        "CREATE INDEX IF NOT EXISTS idx_users_status ON users(status)",
        "CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id)",
        "CREATE INDEX IF NOT EXISTS idx_products_status ON products(status)",
        "CREATE INDEX IF NOT EXISTS idx_products_featured ON products(featured)",
        "CREATE INDEX IF NOT EXISTS idx_products_price ON products(price)",
        "CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status)",
        "CREATE INDEX IF NOT EXISTS idx_orders_date ON orders(created_at)",
        "CREATE INDEX IF NOT EXISTS idx_cart_user ON cart(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_cart_session ON cart(session_id)",
        "CREATE INDEX IF NOT EXISTS idx_reviews_product ON reviews(product_id)",
        "CREATE INDEX IF NOT EXISTS idx_reviews_user ON reviews(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_failed_logins_email ON failed_logins(email)",
        "CREATE INDEX IF NOT EXISTS idx_failed_logins_time ON failed_logins(attempt_time)",
        "CREATE INDEX IF NOT EXISTS idx_user_logins_user ON user_logins(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_remember_tokens_user ON remember_tokens(user_id)",
        "CREATE INDEX IF NOT EXISTS idx_remember_tokens_token ON remember_tokens(token)"
    ];
    
    foreach ($indexes as $index) {
        $pdo->exec($index);
    }
    echo "✅ تم إنشاء جميع الفهارس\n";
    
    // إضافة بيانات تجريبية
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT OR IGNORE INTO users (username, email, password, full_name, role, status) 
        VALUES ('admin', 'admin@albaluwti.com', '$admin_password', 'مدير النظام', 'admin', 'active')
    ");
    echo "✅ تم إضافة حساب المدير\n";
    
    // إضافة فئات تجريبية
    $categories = [
        ['name' => 'الإلكترونيات', 'description' => 'أحدث الأجهزة الإلكترونية'],
        ['name' => 'الملابس', 'description' => 'أزياء عصرية ومريحة'],
        ['name' => 'المنزل والحديقة', 'description' => 'مستلزمات المنزل والحديقة'],
        ['name' => 'الرياضة', 'description' => 'معدات رياضية وملابس رياضية']
    ];
    
    foreach ($categories as $category) {
        $pdo->exec("
            INSERT OR IGNORE INTO categories (name, description) 
            VALUES ('{$category['name']}', '{$category['description']}')
        ");
    }
    echo "✅ تم إضافة الفئات التجريبية\n";
    
    // إضافة منتجات تجريبية
    $products = [
        ['name' => 'هاتف ذكي متطور', 'price' => 999.99, 'category_id' => 1, 'description' => 'هاتف ذكي بأحدث التقنيات'],
        ['name' => 'لابتوب للألعاب', 'price' => 1499.99, 'category_id' => 1, 'description' => 'لابتوب قوي للألعاب'],
        ['name' => 'سماعات لاسلكية', 'price' => 199.99, 'category_id' => 1, 'description' => 'سماعات عالية الجودة'],
        ['name' => 'ساعة ذكية', 'price' => 299.99, 'category_id' => 1, 'description' => 'ساعة ذكية متطورة'],
        ['name' => 'قميص قطني', 'price' => 49.99, 'category_id' => 2, 'description' => 'قميص قطني مريح'],
        ['name' => 'بنطلون جينز', 'price' => 79.99, 'category_id' => 2, 'description' => 'بنطلون جينز كلاسيكي'],
        ['name' => 'طقم أواني طبخ', 'price' => 199.99, 'category_id' => 3, 'description' => 'طقم أواني طبخ عالية الجودة'],
        ['name' => 'كرة قدم', 'price' => 29.99, 'category_id' => 4, 'description' => 'كرة قدم احترافية']
    ];
    
    foreach ($products as $product) {
        $pdo->exec("
            INSERT OR IGNORE INTO products (name, price, category_id, description, stock, featured) 
            VALUES ('{$product['name']}', {$product['price']}, {$product['category_id']}, '{$product['description']}', 100, 1)
        ");
    }
    echo "✅ تم إضافة المنتجات التجريبية\n";
    
    // إضافة إعدادات الموقع
    $settings = [
        ['setting_key' => 'site_name', 'setting_value' => 'متجر البلوطي', 'description' => 'اسم الموقع'],
        ['setting_key' => 'site_description', 'setting_value' => 'متجر إلكتروني متكامل', 'description' => 'وصف الموقع'],
        ['setting_key' => 'shipping_cost', 'setting_value' => '10.00', 'description' => 'تكلفة الشحن'],
        ['setting_key' => 'tax_rate', 'setting_value' => '0.15', 'description' => 'نسبة الضريبة'],
        ['setting_key' => 'currency', 'setting_value' => 'د.ك', 'description' => 'العملة']
    ];
    
    foreach ($settings as $setting) {
        $pdo->exec("
            INSERT OR IGNORE INTO settings (setting_key, setting_value, description) 
            VALUES ('{$setting['setting_key']}', '{$setting['setting_value']}', '{$setting['description']}')
        ");
    }
    echo "✅ تم إضافة إعدادات الموقع\n";
    
    echo "\n🎉 تم إنشاء قاعدة البيانات بنجاح!\n";
    echo "📁 مسار قاعدة البيانات: " . DB_PATH . "\n";
    echo "👤 بيانات المدير: admin / admin123\n";
    echo "🌐 يمكنك الآن تشغيل الموقع\n";
    
} catch (PDOException $e) {
    echo "❌ خطأ في إنشاء قاعدة البيانات: " . $e->getMessage() . "\n";
}
?> 