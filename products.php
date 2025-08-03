<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'المنتجات - متجر البلوطي';
$active = 'products';

// معالجة الفلترة
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : null;
$price_min = isset($_GET['price_min']) ? (float)$_GET['price_min'] : null;
$price_max = isset($_GET['price_max']) ? (float)$_GET['price_max'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// الحصول على المنتجات مع الفلترة
$products = getFilteredProducts($category_filter, $price_min, $price_max, $sort, $search);
$categories = getCategories();

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم البحث والفلترة -->
<section class="filters-section">
    <div class="container">
        <div class="filters-container">
            <form class="filters-form" method="GET">
                <div class="search-box">
                    <input type="text" name="search" placeholder="ابحث عن منتج..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <div class="filters-row">
                    <div class="filter-group">
                        <label>الفئة:</label>
                        <select name="category">
                            <option value="">جميع الفئات</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category_filter == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>السعر:</label>
                        <div class="price-range">
                            <input type="number" name="price_min" placeholder="من" value="<?= $price_min ?>">
                            <span>-</span>
                            <input type="number" name="price_max" placeholder="إلى" value="<?= $price_max ?>">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>الترتيب:</label>
                        <select name="sort">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>الأحدث</option>
                            <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>الأقدم</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>السعر: من الأقل</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>السعر: من الأعلى</option>
                            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>الاسم: أ-ي</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="apply-filters-btn">
                        <i class="fas fa-filter"></i>
                        تطبيق الفلترة
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- قسم المنتجات -->
<section class="products-section">
    <div class="container">
        <div class="products-header">
            <h1 class="section-title">المنتجات</h1>
            <div class="products-count">
                <span><?= count($products) ?> منتج</span>
            </div>
        </div>
        
        <?php if (empty($products)): ?>
        <div class="no-products">
            <div class="no-products-icon">🔍</div>
            <h3>لا توجد منتجات</h3>
            <p>جرب تغيير معايير البحث أو الفلترة</p>
            <a href="products.php" class="btn cta-btn">عرض جميع المنتجات</a>
        </div>
        <?php else: ?>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card card fade-in">
                <div class="product-badge">
                    <?php if (isset($product['featured']) && $product['featured']): ?>
                        <span class="badge featured">مميز</span>
                    <?php endif; ?>
                    <?php if (isset($product['sale_price']) && $product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <span class="badge sale">
                            <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% خصم
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image'] ? '../assets/images/products/' . $product['image'] : '../assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="product-overlay">
                        <button class="quick-view-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <div class="product-info">
                    <div class="product-category"><?= htmlspecialchars($product['category_name'] ?? 'عام') ?></div>
                    <h3 class="product-name">
                        <a href="product-details.php?id=<?= $product['id'] ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </h3>
                    <p class="product-description">
                        <?= htmlspecialchars(substr($product['description'] ?? '', 0, 80)) ?>...
                    </p>
                    
                    <div class="product-price">
                        <?php if (isset($product['sale_price']) && $product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <span class="original-price"><?= formatPrice($product['price']) ?></span>
                            <span class="sale-price"><?= formatPrice($product['sale_price']) ?></span>
                        <?php else: ?>
                            <span class="current-price"><?= formatPrice($product['price']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= ($product['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-count">(<?= $product['review_count'] ?? 0 ?>)</span>
                    </div>
                    
                    <div class="product-actions">
                        <button class="add-to-cart-btn btn cta-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="fas fa-shopping-cart"></i>
                            إضافة للسلة
                        </button>
                        <a href="product-details.php?id=<?= $product['id'] ?>" class="view-details-btn">
                            التفاصيل
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php endif; ?>
    </div>
</section>

<!-- Modal للعرض السريع -->
<div id="quickViewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="quickViewContent"></div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة المنتجات */
.filters-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
    margin-top: 80px;
}

.filters-container {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.filters-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.search-box {
    position: relative;
    max-width: 500px;
    margin: 0 auto;
}

.search-box input {
    width: 100%;
    padding: 1rem 3rem 1rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 50px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #00897b;
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 137, 123, 0.1);
}

.search-btn {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: #00897b;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #00695c;
    transform: translateY(-50%) scale(1.1);
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-weight: 600;
    color: #333;
}

.filter-group select,
.filter-group input {
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-group select:focus,
.filter-group input:focus {
    border-color: #00897b;
    outline: none;
}

.price-range {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.price-range input {
    flex: 1;
    min-width: 80px;
}

.apply-filters-btn {
    background: #00897b;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.apply-filters-btn:hover {
    background: #00695c;
    transform: translateY(-2px);
}

.products-section {
    padding: 3rem 0;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.products-count {
    background: #f0f0f0;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    color: #666;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.product-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.product-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
}

.badge.featured {
    background: #ff9800;
}

.badge.sale {
    background: #f44336;
}

.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.quick-view-btn,
.favorite-btn {
    background: white;
    border: none;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.quick-view-btn:hover,
.favorite-btn:hover {
    transform: scale(1.1);
    background: #00897b;
    color: white;
}

.product-info {
    padding: 1.5rem;
}

.product-category {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.product-name a {
    color: #333;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: 700;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #00897b;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    margin: 0.5rem 0;
    line-height: 1.5;
}

.product-price {
    margin: 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 0.9rem;
}

.sale-price,
.current-price {
    color: #00897b;
    font-size: 1.3rem;
    font-weight: 700;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 0.2rem;
}

.stars .fa-star {
    color: #ddd;
    font-size: 0.9rem;
}

.stars .fa-star.filled {
    color: #ffc107;
}

.rating-count {
    color: #666;
    font-size: 0.9rem;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.add-to-cart-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.view-details-btn {
    padding: 0.75rem 1rem;
    background: transparent;
    color: #00897b;
    border: 2px solid #00897b;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.view-details-btn:hover {
    background: #00897b;
    color: white;
}

.no-products {
    text-align: center;
    padding: 4rem 2rem;
}

.no-products-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-products h3 {
    color: #333;
    margin-bottom: 1rem;
}

.no-products p {
    color: #666;
    margin-bottom: 2rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 2rem;
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    position: relative;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 2rem;
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.close:hover {
    color: #333;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .filters-row {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .products-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .product-actions {
        flex-direction: column;
    }
}
</style>

<script>
// تفعيل العرض السريع
document.querySelectorAll('.quick-view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        // هنا يمكن إضافة AJAX لجلب تفاصيل المنتج
        showQuickView(productId);
    });
});

// تفعيل المفضلة
document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        toggleFavorite(productId, this);
    });
});

// تفعيل إضافة للسلة - سيتم التعامل معها من خلال cart.js
// document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
//     btn.addEventListener('click', function() {
//         const productId = this.dataset.productId;
//         addToCart(productId);
//     });
// });

// Modal
const modal = document.getElementById('quickViewModal');
const closeBtn = document.querySelector('.close');

closeBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function showQuickView(productId) {
    // محاكاة جلب البيانات
    const content = `
        <h2>عرض سريع للمنتج</h2>
        <p>معرف المنتج: ${productId}</p>
        <p>سيتم إضافة تفاصيل المنتج هنا...</p>
    `;
    document.getElementById('quickViewContent').innerHTML = content;
    modal.style.display = "block";
}

function toggleFavorite(productId, btn) {
    const icon = btn.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        icon.style.color = '#e91e63';
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        icon.style.color = '';
    }
}

// إزالة الدالة القديمة - سيتم استخدام cart.js بدلاً منها
// function addToCart(productId) { ... }
</script> 