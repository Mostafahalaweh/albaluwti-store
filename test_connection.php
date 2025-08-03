<?php
// اختبار الاتصال بقاعدة البيانات
try {
    require_once 'php/db.php';
    
    $pdo = getConnection();
    
    if ($pdo) {
        echo "✅ تم الاتصال بقاعدة البيانات بنجاح!\n";
        
        // إنشاء جدول المستخدمين إذا لم يكن موجوداً
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                status VARCHAR(20) DEFAULT 'active',
                role VARCHAR(20) DEFAULT 'user',
                last_login INTEGER DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        echo "✅ تم إنشاء جدول المستخدمين\n";
        
        // إضافة مستخدم تجريبي
        $stmt = $pdo->prepare("
            INSERT OR IGNORE INTO users (name, email, password, phone, status, role) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            'مستخدم تجريبي',
            'test@example.com',
            password_hash('123456', PASSWORD_DEFAULT),
            '0123456789',
            'active',
            'user'
        ]);
        
        echo "✅ تم إضافة المستخدم التجريبي\n";
        echo "📧 البريد الإلكتروني: test@example.com\n";
        echo "🔑 كلمة المرور: 123456\n";
        
    } else {
        echo "❌ فشل في الاتصال بقاعدة البيانات\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}
?> 