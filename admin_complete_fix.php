<?php
/**
 * Admin Complete Fix - إصلاح شامل للوحة الإدارة
 * يضمن عمل جميع صفحات الإدارة وتفاعلها مع الموقع الرئيسي
 */

// بدء الجلسة
session_start();

// تضمين ملفات قاعدة البيانات والدوال
require_once __DIR__ . '/php/db.php';
require_once __DIR__ . '/php/functions.php';

// التحقق من تسجيل دخول المدير
function checkAdminAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin/login.php');
        exit();
    }
}

// دالة لإنشاء رابط الإدارة الرئيسي
function createMainAdminLink() {
    $adminLink = '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 15px; margin: 20px 0; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h2 style="color: white; margin: 0 0 15px 0; font-size: 24px;">🔧 لوحة الإدارة الشاملة</h2>
        <p style="color: rgba(255,255,255,0.9); margin: 0 0 20px 0;">إدارة كاملة للموقع مع جميع الميزات</p>
        <a href="admin/admin_complete.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease; display: inline-block;">
            🚀 الدخول للوحة الإدارة
        </a>
    </div>';
    return $adminLink;
}

// دالة لإنشاء قائمة الإدارة السريعة
function createQuickAdminMenu() {
    $menu = '<div style="background: white; border-radius: 10px; padding: 20px; margin: 20px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <h3 style="color: #333; margin: 0 0 15px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">⚡ قائمة الإدارة السريعة</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="admin/dashboard.php" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                📊 لوحة التحكم
            </a>
            <a href="admin/products.php" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                📦 المنتجات
            </a>
            <a href="admin/orders.php" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                🛒 الطلبات
            </a>
            <a href="admin/users.php" style="background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                👥 المستخدمين
            </a>
            <a href="admin/settings.php" style="background: linear-gradient(135deg, #fa709a, #fee140); color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                ⚙️ الإعدادات
            </a>
            <a href="admin/reviews.php" style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #333; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                ⭐ التقييمات
            </a>
        </div>
    </div>';
    return $menu;
}

// دالة لإنشاء إحصائيات سريعة
function createQuickStats() {
    global $conn;
    
    $stats = [];
    
    // عدد المنتجات
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    $stats['products'] = $result->fetch_assoc()['count'];
    
    // عدد الطلبات
    $result = $conn->query("SELECT COUNT(*) as count FROM orders");
    $stats['orders'] = $result->fetch_assoc()['count'];
    
    // عدد المستخدمين
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $stats['users'] = $result->fetch_assoc()['count'];
    
    // عدد التقييمات
    $result = $conn->query("SELECT COUNT(*) as count FROM reviews");
    $stats['reviews'] = $result->fetch_assoc()['count'];
    
    $html = '<div style="background: white; border-radius: 10px; padding: 20px; margin: 20px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <h3 style="color: #333; margin: 0 0 15px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">📈 إحصائيات سريعة</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
            <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">' . $stats['products'] . '</div>
                <div>المنتجات</div>
            </div>
            <div style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">' . $stats['orders'] . '</div>
                <div>الطلبات</div>
            </div>
            <div style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">' . $stats['users'] . '</div>
                <div>المستخدمين</div>
            </div>
            <div style="background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">' . $stats['reviews'] . '</div>
                <div>التقييمات</div>
            </div>
        </div>
    </div>';
    
    return $html;
}

// دالة لإنشاء روابط سريعة للموقع
function createQuickSiteLinks() {
    $links = '<div style="background: white; border-radius: 10px; padding: 20px; margin: 20px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <h3 style="color: #333; margin: 0 0 15px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">🌐 روابط سريعة للموقع</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="index.php" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                🏠 الصفحة الرئيسية
            </a>
            <a href="pages/products.php" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                📦 المنتجات
            </a>
            <a href="pages/cart.php" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                🛒 السلة
            </a>
            <a href="pages/login.php" style="background: linear-gradient(135deg, #43e97b, #38f9d7); color: white; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                🔐 تسجيل الدخول
            </a>
            <a href="pages/register.php" style="background: linear-gradient(135deg, #fa709a, #fee140); color: white; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                📝 التسجيل
            </a>
            <a href="pages/contact.php" style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #333; padding: 12px; text-decoration: none; border-radius: 8px; text-align: center; transition: transform 0.3s ease;">
                📞 اتصل بنا
            </a>
        </div>
    </div>';
    return $links;
}

// دالة لإنشاء صفحة الإدارة المحسنة
function createEnhancedAdminPage() {
    $html = '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة الشاملة - Albaluwti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .header h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.2em;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }
        
        .stat-label {
            color: rgba(255,255,255,0.9);
            font-size: 1.1em;
        }
        
        .admin-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .admin-link {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }
        
        .admin-link:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.2);
        }
        
        .admin-link h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        
        .admin-link p {
            color: rgba(255,255,255,0.8);
            margin-bottom: 20px;
        }
        
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            display: inline-block;
        }
        
        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }
        
        .site-links {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .site-links h3 {
            color: white;
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .site-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .site-link {
            background: rgba(255,255,255,0.1);
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .site-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2em;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-links {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 لوحة الإدارة الشاملة</h1>
            <p>إدارة كاملة للموقع مع جميع الميزات والتحكم</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">' . getProductCount() . '</div>
                <div class="stat-label">المنتجات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">' . getOrderCount() . '</div>
                <div class="stat-label">الطلبات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">' . getUserCount() . '</div>
                <div class="stat-label">المستخدمين</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">' . getReviewCount() . '</div>
                <div class="stat-label">التقييمات</div>
            </div>
        </div>
        
        <div class="admin-links">
            <a href="admin/dashboard.php" class="admin-link">
                <h3>📊 لوحة التحكم</h3>
                <p>عرض الإحصائيات والتحكم العام</p>
                <div class="btn">الدخول</div>
            </a>
            <a href="admin/products.php" class="admin-link">
                <h3>📦 إدارة المنتجات</h3>
                <p>إضافة وتعديل وحذف المنتجات</p>
                <div class="btn">الدخول</div>
            </a>
            <a href="admin/orders.php" class="admin-link">
                <h3>🛒 إدارة الطلبات</h3>
                <p>متابعة وتحديث حالة الطلبات</p>
                <div class="btn">الدخول</div>
            </a>
            <a href="admin/users.php" class="admin-link">
                <h3>👥 إدارة المستخدمين</h3>
                <p>إدارة حسابات المستخدمين</p>
                <div class="btn">الدخول</div>
            </a>
            <a href="admin/settings.php" class="admin-link">
                <h3>⚙️ إعدادات الموقع</h3>
                <p>تخصيص إعدادات الموقع</p>
                <div class="btn">الدخول</div>
            </a>
            <a href="admin/reviews.php" class="admin-link">
                <h3>⭐ إدارة التقييمات</h3>
                <p>مراجعة وإدارة تقييمات المنتجات</p>
                <div class="btn">الدخول</div>
            </a>
        </div>
        
        <div class="site-links">
            <h3>🌐 روابط سريعة للموقع</h3>
            <div class="site-grid">
                <a href="index.php" class="site-link">🏠 الصفحة الرئيسية</a>
                <a href="pages/products.php" class="site-link">📦 المنتجات</a>
                <a href="pages/cart.php" class="site-link">🛒 السلة</a>
                <a href="pages/login.php" class="site-link">🔐 تسجيل الدخول</a>
                <a href="pages/register.php" class="site-link">📝 التسجيل</a>
                <a href="pages/contact.php" class="site-link">📞 اتصل بنا</a>
            </div>
        </div>
    </div>
</body>
</html>';
    
    return $html;
}

// دوال مساعدة للحصول على الإحصائيات
function getProductCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM products");
    return $result->fetch_assoc()['count'];
}

function getOrderCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM orders");
    return $result->fetch_assoc()['count'];
}

function getUserCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    return $result->fetch_assoc()['count'];
}

function getReviewCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM reviews");
    return $result->fetch_assoc()['count'];
}

// عرض الصفحة
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'main_link':
            echo createMainAdminLink();
            break;
        case 'quick_menu':
            echo createQuickAdminMenu();
            break;
        case 'quick_stats':
            echo createQuickStats();
            break;
        case 'site_links':
            echo createQuickSiteLinks();
            break;
        case 'enhanced_page':
            echo createEnhancedAdminPage();
            break;
        default:
            echo createEnhancedAdminPage();
    }
} else {
    echo createEnhancedAdminPage();
}
?> 