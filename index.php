<?php
session_start();
require_once __DIR__ . '/php/db.php';
require_once __DIR__ . '/php/functions.php';

$page_title = 'الرئيسية - متجر البلوطي';
$active = 'home';

// الحصول على المنتجات المميزة
$featured_products = getFeaturedProducts(6);
$new_products = getNewProducts(8);
$categories = getCategories();

include 'includes/header.php';

// إضافة رابط الإدارة للمديرين
if (isset($_SESSION['admin_id'])) {
    echo '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; border-radius: 10px; margin: 20px 0; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
        <h3 style="color: white; margin: 0 0 10px 0; font-size: 18px;">🔧 لوحة الإدارة</h3>
        <a href="admin_complete_fix.php" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 20px; text-decoration: none; border-radius: 20px; border: 2px solid rgba(255,255,255,0.3); transition: all 0.3s ease; display: inline-block; font-size: 14px;">
            🚀 الدخول للوحة الإدارة
        </a>
    </div>';
}
?>

<!-- القسم الرئيسي -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">مرحباً بك في متجر البلوطي</h1>
                <p class="hero-description">اكتشف تشكيلة واسعة من المنتجات المميزة بأسعار منافسة وجودة عالية</p>
                <div class="hero-actions">
                    <a href="pages/products.php" class="btn cta-btn scale-hover">تصفح المنتجات</a>
                    <a href="pages/offers.php" class="btn btn-outline scale-hover">العروض المميزة</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero-image.jpg" alt="متجر البلوطي" class="hero-img">
            </div>
        </div>
    </div>
</section>

<!-- قسم المميزات -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">لماذا تختار متجر البلوطي؟</h2>
        <div class="features-grid">
            <div class="feature-card card fade-in">
                <div class="feature-icon">🚚</div>
                <h3>شحن سريع</h3>
                <p>نوفر خدمة شحن سريعة وآمنة لجميع أنحاء المملكة</p>
            </div>
            <div class="feature-card card fade-in">
                <div class="feature-icon">🛡️</div>
                <h3>ضمان الجودة</h3>
                <p>جميع منتجاتنا مضمونة الجودة مع إمكانية الإرجاع</p>
            </div>
            <div class="feature-card card fade-in">
                <div class="feature-icon">💰</div>
                <h3>أسعار منافسة</h3>
                <p>نوفر أفضل الأسعار مع عروض وخصومات مستمرة</p>
            </div>
            <div class="feature-card card fade-in">
                <div class="feature-icon">📞</div>
                <h3>دعم 24/7</h3>
                <p>فريق دعم متاح على مدار الساعة لمساعدتك</p>
            </div>
        </div>
    </div>
</section>

<!-- قسم الفئات -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-title">تصفح حسب الفئة</h2>
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
            <div class="category-card card fade-in">
                <div class="category-icon">📦</div>
                <h3><?= htmlspecialchars($category['name']) ?></h3>
                <p><?= htmlspecialchars($category['description'] ?? '') ?></p>
                <a href="pages/products.php?category=<?= $category['id'] ?>" class="category-link">تصفح الفئة</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- قسم المنتجات المميزة -->
<section class="featured-products-section">
    <div class="container">
        <h2 class="section-title">المنتجات المميزة</h2>
        <div class="products-grid">
            <?php foreach ($featured_products as $product): ?>
            <div class="product-card card fade-in">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image'] ? 'assets/images/products/' . $product['image'] : 'assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                    <div class="product-overlay">
                        <a href="pages/product.php?id=<?= $product['id'] ?>" class="btn btn-sm">عرض التفاصيل</a>
                        <button class="btn btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">إضافة للسلة</button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-price"><?= formatPrice($product['price']) ?></p>
                    <div class="product-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= ($product['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                        <span class="rating-count">(<?= $product['review_count'] ?? 0 ?>)</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-actions">
            <a href="pages/products.php" class="btn btn-outline">عرض جميع المنتجات</a>
        </div>
    </div>
</section>

<!-- قسم المنتجات الجديدة -->
<section class="new-products-section">
    <div class="container">
        <h2 class="section-title">أحدث المنتجات</h2>
        <div class="products-grid">
            <?php foreach ($new_products as $product): ?>
            <div class="product-card card fade-in">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image'] ? 'assets/images/products/' . $product['image'] : 'assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                    <div class="product-overlay">
                        <a href="pages/product.php?id=<?= $product['id'] ?>" class="btn btn-sm">عرض التفاصيل</a>
                        <button class="btn btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">إضافة للسلة</button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-price"><?= formatPrice($product['price']) ?></p>
                    <div class="product-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= ($product['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                        <span class="rating-count">(<?= $product['review_count'] ?? 0 ?>)</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- قسم الإحصائيات -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">1000+</div>
                <div class="stat-label">منتج متنوع</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5000+</div>
                <div class="stat-label">عميل راضي</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">دعم متواصل</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">ضمان الجودة</div>
            </div>
        </div>
    </div>
</section>

<!-- قسم النشرة البريدية -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>اشترك في النشرة البريدية</h2>
            <p>احصل على آخر العروض والتحديثات مباشرة في بريدك الإلكتروني</p>
            <form class="newsletter-form" id="newsletterForm">
                <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                <button type="submit" class="btn">اشتراك</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<style>
/* تصميم الصفحة الرئيسية */
.hero-section {
    background: linear-gradient(135deg, #00897b 0%, #00acc1 100%);
    color: white;
    padding: 4rem 0;
    margin-top: 80px;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline:hover {
    background: white;
    color: #00897b;
}

.hero-image {
    text-align: center;
}

.hero-img {
    max-width: 100%;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.features-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feature-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.categories-section {
    padding: 4rem 0;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 3rem;
}

.category-card {
    text-align: center;
    padding: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    background: #00897b;
    color: white;
}

.category-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.category-link {
    color: inherit;
    text-decoration: none;
    font-weight: 600;
    margin-top: 1rem;
    display: inline-block;
}

.featured-products-section,
.new-products-section {
    padding: 4rem 0;
}

.featured-products-section {
    background: #f8f9fa;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-card {
    overflow: hidden;
    position: relative;
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
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 1.5rem;
    background: #fff;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.product-card:hover .product-info {
    transform: translateY(0);
}

.product-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #333;
}

.product-price {
    font-size: 1.2rem;
    font-weight: 800;
    color: #00897b;
    margin-bottom: 0.5rem;
}

.product-rating {
    display: flex;
    align-items: center;
    color: #ffd700; /* Gold color for stars */
}

.rating-count {
    font-size: 0.8rem;
    margin-left: 5px;
    color: #666;
}

.add-to-cart {
    background: #00897b;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
}

.add-to-cart:hover {
    background: #00796b;
}

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

.stats-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #2c3e50, #34495e);
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
}

.newsletter-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #00897b, #00acc1);
    color: white;
    text-align: center;
}

.newsletter-content h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.newsletter-content p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.newsletter-form {
    display: flex;
    gap: 1rem;
    max-width: 500px;
    margin: 0 auto;
    flex-wrap: wrap;
}

.newsletter-form input {
    flex: 1;
    padding: 1rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    min-width: 200px;
}

.newsletter-form button {
    padding: 1rem 2rem;
    background: #ffd600;
    color: #333;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter-form button:hover {
    background: #ffed4e;
    transform: translateY(-2px);
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2rem;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-actions {
        justify-content: center;
    }
    
    .features-grid,
    .categories-grid,
    .products-grid,
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .newsletter-form {
        flex-direction: column;
    }
    
    .newsletter-form input,
    .newsletter-form button {
        width: 100%;
    }
}
</style>