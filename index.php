<?php
session_start();
require_once 'php/db.php';
require_once 'php/functions.php';

$page_title = 'متجر البلوطي - الصفحة الرئيسية';
$active = 'home';

include 'includes/header.php';
?>

<!-- إضافة ملف CSS الحديث -->
<link rel="stylesheet" href="assets/css/modern-design.css">

<main class="main-content">
    <!-- قسم الترحيب -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">مرحباً بك في متجر البلوطي</h1>
                <p class="hero-subtitle">اكتشف تشكيلة واسعة من المنتجات المميزة بأفضل الأسعار</p>
                <div class="hero-buttons">
                    <a href="pages/products.php" class="btn btn-primary btn-lg">تصفح المنتجات</a>
                    <a href="pages/about.php" class="btn btn-ghost btn-lg">تعرف علينا</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero-image.jpg" alt="متجر البلوطي" class="hero-img">
            </div>
        </div>
    </section>

    <!-- قسم الميزات -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">لماذا تختار متجر البلوطي؟</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>توصيل سريع</h3>
                    <p>احصل على طلباتك خلال 24 ساعة مع خدمة التوصيل المميزة</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>ضمان الجودة</h3>
                    <p>جميع منتجاتنا مضمونة الجودة مع ضمان استرداد الأموال</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>دعم 24/7</h3>
                    <p>فريق دعم متاح على مدار الساعة لمساعدتك في أي وقت</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>أسعار منافسة</h3>
                    <p>أفضل الأسعار مع عروض وخصومات مستمرة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم المنتجات المميزة -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">المنتجات المميزة</h2>
            <div class="products-grid">
                <?php
                try {
                    $pdo = getConnection();
                    $stmt = $pdo->prepare("
                        SELECT p.*, c.name as category_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.featured = 1 AND p.status = 'active' 
                        ORDER BY p.created_at DESC 
                        LIMIT 8
                    ");
                    $stmt->execute();
                    $featured_products = $stmt->fetchAll();
                    
                    if (empty($featured_products)) {
                        // إضافة منتجات تجريبية إذا لم تكن موجودة
                        $sample_products = [
                            ['name' => 'هاتف ذكي متطور', 'price' => 999.99, 'image' => 'phone.jpg'],
                            ['name' => 'لابتوب للألعاب', 'price' => 1499.99, 'image' => 'laptop.jpg'],
                            ['name' => 'سماعات لاسلكية', 'price' => 199.99, 'image' => 'headphones.jpg'],
                            ['name' => 'ساعة ذكية', 'price' => 299.99, 'image' => 'smartwatch.jpg']
                        ];
                        
                        foreach ($sample_products as $product) {
                            echo '<div class="product-card">';
                            echo '<div class="product-image">';
                            echo '<img src="assets/images/products/' . $product['image'] . '" alt="' . $product['name'] . '">';
                            echo '<div class="product-overlay">';
                            echo '<a href="pages/product-details.php?id=1" class="btn btn-sm">عرض التفاصيل</a>';
                            echo '<button class="btn btn-sm add-to-cart" data-product-id="1">إضافة للسلة</button>';
                            if (isset($_SESSION['user_id'])) {
                                echo '<button class="wishlist-btn" data-product-id="1"><i class="far fa-heart"></i></button>';
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="product-info">';
                            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                            echo '<p class="product-price">$' . number_format($product['price'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        foreach ($featured_products as $product) {
                            echo '<div class="product-card">';
                            echo '<div class="product-image">';
                            echo '<img src="assets/images/products/' . ($product['image'] ?: 'default.jpg') . '" alt="' . htmlspecialchars($product['name']) . '">';
                            echo '<div class="product-overlay">';
                            echo '<a href="pages/product-details.php?id=' . $product['id'] . '" class="btn btn-sm">عرض التفاصيل</a>';
                            echo '<button class="btn btn-sm add-to-cart" data-product-id="' . $product['id'] . '">إضافة للسلة</button>';
                            if (isset($_SESSION['user_id'])) {
                                echo '<button class="wishlist-btn" data-product-id="' . $product['id'] . '"><i class="far fa-heart"></i></button>';
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="product-info">';
                            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                            echo '<p class="product-price">$' . number_format($product['price'], 2) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<p class="text-center">عذراً، حدث خطأ في تحميل المنتجات</p>';
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="pages/products.php" class="btn btn-primary">عرض جميع المنتجات</a>
            </div>
        </div>
    </section>

    <!-- قسم الفئات -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">تصفح حسب الفئة</h2>
            <div class="categories-grid">
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE status = 'active' ORDER BY name LIMIT 6");
                    $stmt->execute();
                    $categories = $stmt->fetchAll();
                    
                    if (empty($categories)) {
                        $sample_categories = [
                            ['name' => 'الإلكترونيات', 'description' => 'أحدث الأجهزة الإلكترونية'],
                            ['name' => 'الملابس', 'description' => 'أزياء عصرية وأنيقة'],
                            ['name' => 'المنزل', 'description' => 'مستلزمات المنزل والديكور'],
                            ['name' => 'الرياضة', 'description' => 'معدات رياضية وملابس رياضية'],
                            ['name' => 'الكتب', 'description' => 'كتب متنوعة في جميع المجالات'],
                            ['name' => 'الألعاب', 'description' => 'ألعاب للأطفال والكبار']
                        ];
                        
                        foreach ($sample_categories as $category) {
                            echo '<div class="category-card">';
                            echo '<div class="category-icon"><i class="fas fa-box"></i></div>';
                            echo '<h3>' . htmlspecialchars($category['name']) . '</h3>';
                            echo '<p>' . htmlspecialchars($category['description']) . '</p>';
                            echo '<a href="pages/products.php?category=1" class="category-link">تصفح الفئة</a>';
                            echo '</div>';
                        }
                    } else {
                        foreach ($categories as $category) {
                            echo '<div class="category-card">';
                            echo '<div class="category-icon"><i class="fas fa-box"></i></div>';
                            echo '<h3>' . htmlspecialchars($category['name']) . '</h3>';
                            echo '<p>' . htmlspecialchars($category['description'] ?: 'تصفح منتجات هذه الفئة') . '</p>';
                            echo '<a href="pages/products.php?category=' . $category['id'] . '" class="category-link">تصفح الفئة</a>';
                            echo '</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<p class="text-center">عذراً، حدث خطأ في تحميل الفئات</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- قسم الإحصائيات -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">منتج متنوع</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">عميل راضي</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">فئة منتجات</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">دعم فني</div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- CSS إضافي للصفحة الرئيسية -->
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('assets/images/pattern.svg') repeat;
    opacity: 0.1;
}

.hero-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    animation: fadeInUp 1s ease;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    animation: fadeInUp 1s ease 0.2s both;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    animation: fadeInUp 1s ease 0.4s both;
}

/* Features Section */
.features-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 3rem;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.feature-icon i {
    font-size: 2rem;
    color: white;
}

.feature-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
}

/* Featured Products */
.featured-products {
    padding: 80px 0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-info {
    padding: 1.5rem;
}

.product-info h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #667eea;
}

/* Categories Section */
.categories-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.category-card {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.category-icon i {
    font-size: 1.5rem;
    color: white;
}

.category-card h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.category-card p {
    color: #666;
    margin-bottom: 1rem;
}

.category-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.category-link:hover {
    text-decoration: underline;
}

/* Stats Section */
.stats-section {
    padding: 60px 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .features-grid,
    .products-grid,
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Wishlist Button */
.wishlist-btn {
    background: rgba(255,255,255,0.9);
    color: #666;
    border: none;
    padding: 8px 12px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.wishlist-btn:hover {
    background: #ff4757;
    color: white;
    transform: scale(1.1);
}

.wishlist-btn.in-wishlist {
    background: #ff4757;
    color: white;
}

.wishlist-btn.in-wishlist i {
    color: white;
}
</style>

<?php include 'includes/footer.php'; ?> 