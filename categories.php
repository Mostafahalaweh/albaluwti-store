<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'الفئات - متجر البلوطي';
$active = 'categories';

// الحصول على الفئات
$categories = getCategories();

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم الفئات -->
<section class="categories-section">
    <div class="container">
        <div class="categories-header">
            <h1 class="section-title">فئات المنتجات</h1>
            <p class="section-description">تصفح منتجاتنا حسب الفئة التي تفضلها</p>
        </div>
        
        <?php if (empty($categories)): ?>
        <div class="no-categories">
            <div class="no-categories-icon">📦</div>
            <h3>لا توجد فئات متاحة حالياً</h3>
            <p>سيتم إضافة الفئات قريباً</p>
            <a href="products.php" class="btn cta-btn">تصفح جميع المنتجات</a>
        </div>
        <?php else: ?>
        
        <div class="categories-grid">
            <?php foreach ($categories as $category): ?>
            <div class="category-card card fade-in">
                <div class="category-image">
                    <img src="<?= htmlspecialchars($category['image'] ?? '../assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($category['name']) ?>">
                </div>
                <div class="category-info">
                    <h3 class="category-name"><?= htmlspecialchars($category['name']) ?></h3>
                    <p class="category-description">
                        <?= htmlspecialchars($category['description'] ?? 'تصفح تشكيلة متنوعة من المنتجات في هذه الفئة') ?>
                    </p>
                    <div class="category-stats">
                        <span class="products-count">
                            <i class="fas fa-box"></i>
                            <?= $category['products_count'] ?? 'متعدد' ?> منتج
                        </span>
                    </div>
                    <a href="products.php?category=<?= $category['id'] ?>" class="category-link btn cta-btn">
                        <i class="fas fa-arrow-left"></i>
                        تصفح الفئة
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php endif; ?>
    </div>
</section>

<!-- قسم الفئات الشائعة -->
<section class="popular-categories-section">
    <div class="container">
        <h2 class="section-title">الفئات الأكثر شعبية</h2>
        <div class="popular-categories-grid">
            <div class="popular-category card">
                <div class="category-icon">🥤</div>
                <h3>المشروبات</h3>
                <p>مشروبات منعشة ومياه معدنية</p>
                <a href="products.php?category=beverages" class="category-link">تصفح الفئة</a>
            </div>
            <div class="popular-category card">
                <div class="category-icon">🍞</div>
                <h3>المخبوزات</h3>
                <p>خبز طازج ومعجنات شهية</p>
                <a href="products.php?category=bakery" class="category-link">تصفح الفئة</a>
            </div>
            <div class="popular-category card">
                <div class="category-icon">🥛</div>
                <h3>الألبان</h3>
                <p>حليب طازج ومشتقات الألبان</p>
                <a href="products.php?category=dairy" class="category-link">تصفح الفئة</a>
            </div>
            <div class="popular-category card">
                <div class="category-icon">🍎</div>
                <h3>الفواكه والخضروات</h3>
                <p>فواكه وخضروات طازجة</p>
                <a href="products.php?category=produce" class="category-link">تصفح الفئة</a>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة الفئات */
.categories-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.categories-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.no-categories {
    text-align: center;
    padding: 4rem 2rem;
}

.no-categories-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-categories h3 {
    color: #333;
    margin-bottom: 1rem;
}

.no-categories p {
    color: #666;
    margin-bottom: 2rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.category-card {
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.category-image {
    height: 200px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.05);
}

.category-info {
    padding: 1.5rem;
}

.category-name {
    color: #333;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.category-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.category-stats {
    margin-bottom: 1.5rem;
}

.products-count {
    color: #00897b;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.category-link {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-link:hover {
    transform: translateY(-2px);
}

/* قسم الفئات الشائعة */
.popular-categories-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.popular-categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.popular-category {
    text-align: center;
    padding: 2rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.popular-category:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.category-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.popular-category h3 {
    color: #333;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.popular-category p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.popular-category .category-link {
    color: #00897b;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.popular-category .category-link:hover {
    color: #00695c;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .popular-categories-grid {
        grid-template-columns: 1fr;
    }
    
    .category-card {
        margin-bottom: 1rem;
    }
}
</style> 