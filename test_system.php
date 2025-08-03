<?php
// ====== اختبار شامل لنظام متجر البلوطي ======

echo "🔍 بدء اختبار شامل لنظام متجر البلوطي\n";
echo "=====================================\n\n";

// اختبار 1: اتصال قاعدة البيانات
echo "1️⃣ اختبار اتصال قاعدة البيانات...\n";
try {
    require_once 'php/db.php';
    $pdo = getConnection();
    if ($pdo) {
        echo "✅ تم الاتصال بقاعدة البيانات بنجاح\n";
    } else {
        echo "❌ فشل في الاتصال بقاعدة البيانات\n";
        exit;
    }
} catch (Exception $e) {
    echo "❌ خطأ في الاتصال: " . $e->getMessage() . "\n";
    exit;
}

// اختبار 2: فحص الجداول
echo "\n2️⃣ اختبار وجود الجداول...\n";
$required_tables = [
    'users', 'categories', 'products', 'orders', 'order_items', 
    'cart', 'reviews', 'contact_messages', 'settings', 'activity_log',
    'failed_logins', 'user_logins', 'remember_tokens'
];

$existing_tables = [];
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($row = $stmt->fetch()) {
    $existing_tables[] = $row['name'];
}

foreach ($required_tables as $table) {
    if (in_array($table, $existing_tables)) {
        echo "✅ جدول $table موجود\n";
    } else {
        echo "❌ جدول $table مفقود\n";
    }
}

// اختبار 3: فحص البيانات التجريبية
echo "\n3️⃣ اختبار البيانات التجريبية...\n";

// فحص المستخدمين
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$user_count = $stmt->fetch()['count'];
echo "👥 عدد المستخدمين: $user_count\n";

// فحص الفئات
$stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
$category_count = $stmt->fetch()['count'];
echo "📂 عدد الفئات: $category_count\n";

// فحص المنتجات
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
$product_count = $stmt->fetch()['count'];
echo "🛍️ عدد المنتجات: $product_count\n";

// فحص الإعدادات
$stmt = $pdo->query("SELECT COUNT(*) as count FROM settings");
$settings_count = $stmt->fetch()['count'];
echo "⚙️ عدد الإعدادات: $settings_count\n";

// اختبار 4: اختبار الدوال المساعدة
echo "\n4️⃣ اختبار الدوال المساعدة...\n";
try {
    require_once 'php/functions.php';
    echo "✅ تم تحميل ملف الدوال المساعدة\n";
    
    // اختبار دالة تنظيف النص
    $test_text = "<script>alert('test')</script>Hello World";
    $cleaned = cleanInput($test_text);
    if ($cleaned !== $test_text) {
        echo "✅ دالة تنظيف النص تعمل بشكل صحيح\n";
    } else {
        echo "❌ دالة تنظيف النص لا تعمل\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في تحميل الدوال المساعدة: " . $e->getMessage() . "\n";
}

// اختبار 5: اختبار الملفات المهمة
echo "\n5️⃣ اختبار الملفات المهمة...\n";
$important_files = [
    'index.php',
    'includes/header.php',
    'includes/footer.php',
    'pages/login.php',
    'pages/products.php',
    'pages/cart.php',
    'admin/dashboard.php',
    'assets/css/main.css',
    'assets/js/main.js'
];

foreach ($important_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file موجود\n";
    } else {
        echo "❌ $file مفقود\n";
    }
}

// اختبار 6: اختبار الصلاحيات
echo "\n6️⃣ اختبار صلاحيات الملفات...\n";
$writable_dirs = [
    'db',
    'uploads',
    'logs',
    'cache'
];

foreach ($writable_dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        echo "✅ مجلد $dir قابل للكتابة\n";
    } else {
        echo "❌ مجلد $dir غير قابل للكتابة\n";
    }
}

// اختبار 7: اختبار الاتصال بقاعدة البيانات
echo "\n7️⃣ اختبار استعلامات قاعدة البيانات...\n";
try {
    // اختبار استعلام المستخدمين
    $stmt = $pdo->query("SELECT * FROM users LIMIT 1");
    $user = $stmt->fetch();
    if ($user) {
        echo "✅ استعلام المستخدمين يعمل\n";
    } else {
        echo "❌ استعلام المستخدمين لا يعمل\n";
    }
    
    // اختبار استعلام المنتجات
    $stmt = $pdo->query("SELECT * FROM products LIMIT 1");
    $product = $stmt->fetch();
    if ($product) {
        echo "✅ استعلام المنتجات يعمل\n";
    } else {
        echo "❌ استعلام المنتجات لا يعمل\n";
    }
    
    // اختبار استعلام الفئات
    $stmt = $pdo->query("SELECT * FROM categories LIMIT 1");
    $category = $stmt->fetch();
    if ($category) {
        echo "✅ استعلام الفئات يعمل\n";
    } else {
        echo "❌ استعلام الفئات لا يعمل\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في استعلامات قاعدة البيانات: " . $e->getMessage() . "\n";
}

// اختبار 8: اختبار الجلسات
echo "\n8️⃣ اختبار إدارة الجلسات...\n";
try {
    session_start();
    $_SESSION['test'] = 'test_value';
    if (isset($_SESSION['test']) && $_SESSION['test'] === 'test_value') {
        echo "✅ إدارة الجلسات تعمل بشكل صحيح\n";
    } else {
        echo "❌ إدارة الجلسات لا تعمل\n";
    }
    session_destroy();
} catch (Exception $e) {
    echo "❌ خطأ في إدارة الجلسات: " . $e->getMessage() . "\n";
}

// اختبار 9: اختبار الأمان
echo "\n9️⃣ اختبار ميزات الأمان...\n";

// اختبار تشفير كلمات المرور
$test_password = 'test123';
$hashed = password_hash($test_password, PASSWORD_DEFAULT);
if (password_verify($test_password, $hashed)) {
    echo "✅ تشفير كلمات المرور يعمل\n";
} else {
    echo "❌ تشفير كلمات المرور لا يعمل\n";
}

// اختبار CSRF token
$csrf_token = bin2hex(random_bytes(32));
if (strlen($csrf_token) === 64) {
    echo "✅ توليد CSRF token يعمل\n";
} else {
    echo "❌ توليد CSRF token لا يعمل\n";
}

// اختبار 10: اختبار الأداء
echo "\n🔟 اختبار الأداء...\n";
$start_time = microtime(true);

// اختبار استعلام معقد
$stmt = $pdo->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.status = 'active' 
    ORDER BY p.created_at DESC 
    LIMIT 10
");
$stmt->execute();
$products = $stmt->fetchAll();

$end_time = microtime(true);
$execution_time = ($end_time - $start_time) * 1000; // بالمللي ثانية

if ($execution_time < 100) {
    echo "✅ أداء قاعدة البيانات ممتاز ($execution_time ms)\n";
} elseif ($execution_time < 500) {
    echo "⚠️ أداء قاعدة البيانات جيد ($execution_time ms)\n";
} else {
    echo "❌ أداء قاعدة البيانات بطيء ($execution_time ms)\n";
}

// اختبار 11: فحص الإعدادات
echo "\n1️⃣1️⃣ فحص إعدادات الموقع...\n";
try {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = $stmt->fetchAll();
    
    foreach ($settings as $setting) {
        echo "✅ {$setting['setting_key']}: {$setting['setting_value']}\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ في قراءة الإعدادات: " . $e->getMessage() . "\n";
}

// اختبار 12: فحص البيانات التجريبية
echo "\n1️⃣2️⃣ فحص البيانات التجريبية...\n";

// فحص حساب المدير
$stmt = $pdo->prepare("SELECT username, email, role FROM users WHERE role = 'admin'");
$stmt->execute();
$admin = $stmt->fetch();

if ($admin) {
    echo "✅ حساب المدير موجود: {$admin['username']} ({$admin['email']})\n";
} else {
    echo "❌ حساب المدير مفقود\n";
}

// فحص المنتجات المميزة
$stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE featured = 1");
$featured_count = $stmt->fetch()['count'];
echo "⭐ عدد المنتجات المميزة: $featured_count\n";

// فحص الفئات النشطة
$stmt = $pdo->query("SELECT COUNT(*) as count FROM categories WHERE status = 'active'");
$active_categories = $stmt->fetch()['count'];
echo "📂 عدد الفئات النشطة: $active_categories\n";

// ملخص النتائج
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 ملخص نتائج الاختبار\n";
echo str_repeat("=", 50) . "\n";

$total_tests = 12;
$passed_tests = 0;

// حساب الاختبارات الناجحة (تقريبي)
if ($user_count > 0) $passed_tests++;
if ($category_count > 0) $passed_tests++;
if ($product_count > 0) $passed_tests++;
if ($settings_count > 0) $passed_tests++;
if ($execution_time < 500) $passed_tests++;
if ($admin) $passed_tests++;
if ($featured_count > 0) $passed_tests++;
if ($active_categories > 0) $passed_tests++;

$success_rate = round(($passed_tests / $total_tests) * 100, 1);

echo "🎯 نسبة النجاح: $success_rate%\n";
echo "✅ الاختبارات الناجحة: $passed_tests/$total_tests\n";

if ($success_rate >= 90) {
    echo "🎉 النظام جاهز للاستخدام!\n";
} elseif ($success_rate >= 70) {
    echo "⚠️ النظام يعمل بشكل جيد مع بعض المشاكل البسيطة\n";
} else {
    echo "❌ النظام يحتاج إلى إصلاحات\n";
}

echo "\n📋 التوصيات:\n";
if ($user_count == 0) echo "- إضافة مستخدمين تجريبيين\n";
if ($product_count == 0) echo "- إضافة منتجات تجريبية\n";
if ($execution_time > 500) echo "- تحسين أداء قاعدة البيانات\n";
if (!is_writable('uploads')) echo "- إصلاح صلاحيات مجلد uploads\n";

echo "\n🚀 يمكنك الآن تشغيل الموقع على:\n";
echo "🌐 http://localhost/albaluwti_backup/\n";
echo "🔧 لوحة الإدارة: http://localhost/albaluwti_backup/admin/\n";
echo "👤 بيانات المدير: admin / admin123\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ انتهى الاختبار الشامل\n";
echo str_repeat("=", 50) . "\n";
?> 