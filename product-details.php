<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

$page_title = 'تفاصيل المنتج - متجر البلوطي';
$active = 'products';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductDetails($product_id);

if (!$product) {
    header('Location: products.php');
    exit();
}

$related_products = getRelatedProducts($product_id, $product['category_id'], 4);
$reviews = getProductReviews($product_id);

include '../includes/header.php';
?>

<!-- قسم تفاصيل المنتج -->
<section class="product-details-section">
    <div class="container">
        <div class="product-details-container">
            <!-- معرض الصور -->
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?= htmlspecialchars($product['image'] ?? '../assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" id="mainImage">
                </div>
                <?php if (!empty($product['gallery'])): ?>
                <div class="thumbnail-images">
                    <?php 
                    $gallery = json_decode($product['gallery'], true);
                    foreach ($gallery as $index => $image): 
                    ?>
                    <div class="thumbnail" onclick="changeMainImage('<?= htmlspecialchars($image) ?>')">
                        <img src="<?= htmlspecialchars($image) ?>" alt="صورة <?= $index + 1 ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- معلومات المنتج -->
            <div class="product-info-details">
                <div class="product-badges">
                    <?php if ($product['featured']): ?>
                        <span class="badge featured">مميز</span>
                    <?php endif; ?>
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <span class="badge sale">
                            <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>% خصم
                        </span>
                    <?php endif; ?>
                </div>
                
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-category">
                    <i class="fas fa-tag"></i>
                    <a href="products.php?category=<?= $product['category_id'] ?>">
                        <?= htmlspecialchars($product['category_name'] ?? 'عام') ?>
                    </a>
                </div>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= ($product['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text">
                        <?= number_format($product['rating'] ?? 0, 1) ?> من 5
                        (<?= $product['review_count'] ?? 0 ?> تقييم)
                    </span>
                </div>
                
                <div class="product-price">
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                        <span class="original-price"><?= formatPrice($product['price']) ?></span>
                        <span class="sale-price"><?= formatPrice($product['sale_price']) ?></span>
                        <span class="savings">توفير <?= formatPrice($product['price'] - $product['sale_price']) ?></span>
                    <?php else: ?>
                        <span class="current-price"><?= formatPrice($product['price']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>الوصف</h3>
                    <p><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
                </div>
                
                <?php if (!empty($product['specifications'])): ?>
                <div class="product-specifications">
                    <h3>المواصفات</h3>
                    <div class="specs-list">
                        <?php 
                        $specs = json_decode($product['specifications'], true);
                        if ($specs):
                            foreach ($specs as $key => $value): 
                        ?>
                        <div class="spec-item">
                            <span class="spec-label"><?= htmlspecialchars($key) ?>:</span>
                            <span class="spec-value"><?= htmlspecialchars($value) ?></span>
                        </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <label>الكمية:</label>
                        <div class="quantity-controls">
                            <button type="button" onclick="changeQuantity(-1)" class="qty-btn">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?? 999 ?>">
                            <button type="button" onclick="changeQuantity(1)" class="qty-btn">+</button>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="add-to-cart-btn btn cta-btn" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart"></i>
                            إضافة إلى السلة
                        </button>
                        
                        <button class="wishlist-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                        
                        <button class="share-btn" onclick="shareProduct()">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <i class="fas fa-box"></i>
                        <span>المخزون: <?= $product['stock_quantity'] ?? 'متوفر' ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-shipping-fast"></i>
                        <span>شحن مجاني للطلبات فوق 200 ريال</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-undo"></i>
                        <span>إرجاع مجاني خلال 14 يوم</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- قسم التقييمات -->
<?php if (!empty($reviews)): ?>
<section class="reviews-section">
    <div class="container">
        <h2 class="section-title">تقييمات العملاء</h2>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
            <div class="review-card card fade-in">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="reviewer-details">
                            <h4><?= htmlspecialchars($review['reviewer_name']) ?></h4>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'filled' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="review-date">
                        <?= formatDateArabic($review['created_at']) ?>
                    </div>
                </div>
                <div class="review-content">
                    <h5><?= htmlspecialchars($review['title']) ?></h5>
                    <p><?= htmlspecialchars($review['comment']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- قسم المنتجات ذات الصلة -->
<?php if (!empty($related_products)): ?>
<section class="related-products-section">
    <div class="container">
        <h2 class="section-title">منتجات ذات صلة</h2>
        <div class="products-grid">
            <?php foreach ($related_products as $related_product): ?>
            <div class="product-card card fade-in">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($related_product['image'] ?? '../assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($related_product['name']) ?>">
                    <div class="product-overlay">
                        <button class="quick-view-btn" data-product-id="<?= $related_product['id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="wishlist-btn" data-product-id="<?= $related_product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="product-details.php?id=<?= $related_product['id'] ?>">
                            <?= htmlspecialchars($related_product['name']) ?>
                        </a>
                    </h3>
                    <div class="product-price">
                        <?php if ($related_product['sale_price'] && $related_product['sale_price'] < $related_product['price']): ?>
                            <span class="original-price"><?= formatPrice($related_product['price']) ?></span>
                            <span class="sale-price"><?= formatPrice($related_product['sale_price']) ?></span>
                        <?php else: ?>
                            <span class="current-price"><?= formatPrice($related_product['price']) ?></span>
                        <?php endif; ?>
                    </div>
                    <button class="add-to-cart-btn btn cta-btn" onclick="addToCart(<?= $related_product['id'] ?>)">
                        <i class="fas fa-shopping-cart"></i>
                        إضافة للسلة
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة تفاصيل المنتج */
.product-details-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.product-details-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: start;
}

.product-gallery {
    position: sticky;
    top: 100px;
}

.main-image {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
}

.main-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.main-image:hover img {
    transform: scale(1.05);
}

.thumbnail-images {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.thumbnail {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail:hover {
    border-color: #00897b;
    transform: scale(1.05);
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info-details {
    padding: 1rem;
}

.product-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
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

.product-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.product-category {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    color: #666;
}

.product-category a {
    color: #00897b;
    text-decoration: none;
    font-weight: 600;
}

.product-category a:hover {
    text-decoration: underline;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stars {
    display: flex;
    gap: 0.2rem;
}

.stars .fa-star {
    color: #ddd;
    font-size: 1.1rem;
}

.stars .fa-star.filled {
    color: #ffc107;
}

.rating-text {
    color: #666;
    font-size: 0.9rem;
}

.product-price {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 1.1rem;
}

.sale-price,
.current-price {
    color: #00897b;
    font-size: 2rem;
    font-weight: 700;
}

.savings {
    background: #e8f5e8;
    color: #2e7d32;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.product-description,
.product-specifications {
    margin-bottom: 2rem;
}

.product-description h3,
.product-specifications h3 {
    color: #333;
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.product-description p {
    color: #666;
    line-height: 1.6;
}

.specs-list {
    display: grid;
    gap: 0.5rem;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.spec-label {
    font-weight: 600;
    color: #333;
}

.spec-value {
    color: #666;
}

.product-actions {
    margin-bottom: 2rem;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.quantity-selector label {
    font-weight: 600;
    color: #333;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    background: #f5f5f5;
    border: none;
    padding: 0.75rem 1rem;
    cursor: pointer;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: #e0e0e0;
}

#quantity {
    border: none;
    padding: 0.75rem;
    text-align: center;
    width: 60px;
    font-size: 1rem;
}

#quantity:focus {
    outline: none;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.add-to-cart-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.favorite-btn,
.share-btn {
    background: #f5f5f5;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.favorite-btn:hover,
.share-btn:hover {
    background: #00897b;
    color: white;
    transform: scale(1.1);
}

.product-meta {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    display: grid;
    gap: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #666;
}

.meta-item i {
    color: #00897b;
    width: 20px;
}

/* قسم التقييمات */
.reviews-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.review-card {
    padding: 1.5rem;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    background: #00897b;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.reviewer-details h4 {
    color: #333;
    margin-bottom: 0.25rem;
}

.review-rating {
    display: flex;
    gap: 0.2rem;
}

.review-rating .fa-star {
    color: #ddd;
    font-size: 0.9rem;
}

.review-rating .fa-star.filled {
    color: #ffc107;
}

.review-date {
    color: #666;
    font-size: 0.9rem;
}

.review-content h5 {
    color: #333;
    margin-bottom: 0.5rem;
}

.review-content p {
    color: #666;
    line-height: 1.6;
}

/* قسم المنتجات ذات الصلة */
.related-products-section {
    padding: 4rem 0;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .product-details-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .product-gallery {
        position: static;
    }
    
    .main-image img {
        height: 300px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .add-to-cart-btn {
        width: 100%;
    }
    
    .reviews-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}

function changeQuantity(delta) {
    const input = document.getElementById('quantity');
    const newValue = parseInt(input.value) + delta;
    const max = parseInt(input.max);
    
    if (newValue >= 1 && newValue <= max) {
        input.value = newValue;
    }
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
    btn.disabled = true;
    
    // إرسال طلب AJAX لإضافة المنتج إلى السلة
    fetch('cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check"></i> تم الإضافة';
            btn.style.background = '#4caf50';
            
            // تحديث عداد السلة إذا كان موجوداً
            const cartCount = document.querySelector('.cart-count');
            if (cartCount && data.cart_count !== undefined) {
                cartCount.textContent = data.cart_count;
            }
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.background = '';
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'حدث خطأ أثناء إضافة المنتج');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function toggleFavorite(productId) {
    const btn = event.target;
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

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($product['name']) ?>',
            text: '<?= htmlspecialchars(substr($product['description'] ?? '', 0, 100)) ?>',
            url: window.location.href
        });
    } else {
        // نسخ الرابط للحافظة
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('تم نسخ الرابط للحافظة');
        });
    }
}
</script> 