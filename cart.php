<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'سلة التسوق - متجر البلوطي';
$active = 'cart';

// الحصول على محتويات السلة
$cart_items = getCartItems();
$cart_total = calculateCartTotal();

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم السلة -->
<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1 class="section-title">🛒 سلة التسوق</h1>
            <div class="cart-summary">
                <span class="items-count"><?= count($cart_items) ?> منتج</span>
            </div>
        </div>
        
        <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <h2>سلة التسوق فارغة</h2>
            <p>لم تقم بإضافة أي منتجات إلى السلة بعد</p>
            <a href="products.php" class="btn cta-btn">تصفح المنتجات</a>
        </div>
        <?php else: ?>
        
        <div class="cart-container">
            <!-- قائمة المنتجات -->
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item card fade-in" data-product-id="<?= $item['product_id'] ?>">
                    <div class="item-image">
                        <img src="<?= htmlspecialchars($item['image'] ?? '../assets/images/placeholder.png') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                        <?php if (isset($item['sale_price']) && $item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                            <div class="sale-badge">
                                <?= round((($item['price'] - $item['sale_price']) / $item['price']) * 100) ?>% خصم
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-details">
                        <h3 class="item-name">
                            <a href="product-details.php?id=<?= $item['product_id'] ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h3>
                        <div class="item-category"><?= htmlspecialchars($item['category_name'] ?? 'عام') ?></div>
                        
                        <div class="item-price">
                            <?php if (isset($item['sale_price']) && $item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                                <span class="original-price"><?= formatPrice($item['price']) ?></span>
                                <span class="sale-price"><?= formatPrice($item['sale_price']) ?></span>
                            <?php else: ?>
                                <span class="current-price"><?= formatPrice($item['price']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="item-quantity">
                        <label>الكمية:</label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn decrease-btn" data-product-id="<?= $item['product_id'] ?>">-</button>
                            <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" min="1" max="99" 
                                   data-product-id="<?= $item['product_id'] ?>">
                            <button type="button" class="qty-btn increase-btn" data-product-id="<?= $item['product_id'] ?>">+</button>
                        </div>
                    </div>
                    
                    <div class="item-total">
                        <span class="total-label">المجموع:</span>
                        <span class="total-price">
                            <?php 
                            $item_price = isset($item['sale_price']) && $item['sale_price'] && $item['sale_price'] < $item['price'] ? $item['sale_price'] : $item['price'];
                            echo formatPrice($item_price * $item['quantity']);
                            ?>
                        </span>
                    </div>
                    
                    <div class="item-actions">
                        <button class="remove-btn remove-from-cart-btn" data-product-id="<?= $item['product_id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="favorite-btn" onclick="moveToWishlist(<?= $item['product_id'] ?>)">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- ملخص الطلب -->
            <div class="cart-summary-sidebar">
                <div class="summary-card card">
                    <h3>ملخص الطلب</h3>
                    
                    <div class="summary-items">
                        <div class="summary-item">
                            <span>المجموع الفرعي:</span>
                            <span><?= formatPrice($cart_total) ?></span>
                        </div>
                        
                        <div class="summary-item">
                            <span>الشحن:</span>
                            <span id="shipping-cost">
                                <?php 
                                $shipping_cost = $cart_total >= 200 ? 0 : 30;
                                echo $shipping_cost == 0 ? 'مجاني' : formatPrice($shipping_cost);
                                ?>
                            </span>
                        </div>
                        
                        <?php if ($cart_total < 200): ?>
                        <div class="free-shipping-notice">
                            <i class="fas fa-truck"></i>
                            <span>أضف <?= formatPrice(200 - $cart_total) ?> أخرى للحصول على شحن مجاني</span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="summary-total">
                            <span>المجموع الكلي:</span>
                            <span><?= formatPrice($cart_total + $shipping_cost) ?></span>
                        </div>
                    </div>
                    
                    <div class="summary-actions">
                        <button class="checkout-btn btn cta-btn">
                            <i class="fas fa-credit-card"></i>
                            إتمام الطلب
                        </button>
                        
                        <button class="continue-shopping-btn btn btn-outline" onclick="window.location.href='products.php'">
                            <i class="fas fa-arrow-right"></i>
                            متابعة التسوق
                        </button>
                    </div>
                    
                    <div class="payment-methods">
                        <h4>طرق الدفع المقبولة:</h4>
                        <div class="payment-icons">
                            <i class="fab fa-cc-visa" title="فيزا"></i>
                            <i class="fab fa-cc-mastercard" title="ماستركارد"></i>
                            <i class="fab fa-cc-paypal" title="باي بال"></i>
                            <i class="fas fa-money-bill-wave" title="الدفع عند الاستلام"></i>
                        </div>
                    </div>
                </div>
                
                <!-- كوبون الخصم -->
                <div class="coupon-card card">
                    <h4>كوبون الخصم</h4>
                    <div class="coupon-form">
                        <input type="text" id="couponCode" placeholder="أدخل كود الخصم">
                        <button onclick="applyCoupon()" class="apply-coupon-btn">
                            <i class="fas fa-tag"></i>
                            تطبيق
                        </button>
                    </div>
                    <div id="couponMessage"></div>
                </div>
                
                <!-- نصائح الأمان -->
                <div class="security-notice card">
                    <div class="security-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>أمان مضمون</h4>
                    <p>جميع المعاملات محمية بتقنيات التشفير المتقدمة</p>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
</section>

<!-- قسم المنتجات المقترحة -->
<?php if (!empty($cart_items)): ?>
<section class="suggested-products-section">
    <div class="container">
        <h2 class="section-title">قد يعجبك أيضاً</h2>
        <div class="products-grid">
            <?php 
            $suggested_products = getSuggestedProducts(array_column($cart_items, 'product_id'), 4);
            foreach ($suggested_products as $product): 
            ?>
            <div class="product-card card fade-in">
                <div class="product-image">
                    <img src="<?= htmlspecialchars($product['image'] ?? '../assets/images/placeholder.png') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="product-overlay">
                        <button class="quick-view-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="favorite-btn" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="product-details.php?id=<?= $product['id'] ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </h3>
                    <div class="product-price">
                        <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <span class="original-price"><?= formatPrice($product['price']) ?></span>
                            <span class="sale-price"><?= formatPrice($product['sale_price']) ?></span>
                        <?php else: ?>
                            <span class="current-price"><?= formatPrice($product['price']) ?></span>
                        <?php endif; ?>
                    </div>
                                            <button class="add-to-cart-btn btn cta-btn" data-product-id="<?= $product['id'] ?>">
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

<!-- تضمين ملف إدارة السلة -->
<script src="../assets/js/cart.js"></script>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة السلة */
.cart-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.cart-summary {
    background: #f0f0f0;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    color: #666;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-cart-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-cart h2 {
    color: #333;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: #666;
    margin-bottom: 2rem;
}

.cart-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
    align-items: start;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 120px 1fr auto auto auto;
    gap: 1.5rem;
    align-items: center;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.item-image {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cart-item:hover .item-image img {
    transform: scale(1.05);
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.item-name a {
    color: #333;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 600;
    transition: color 0.3s ease;
}

.item-name a:hover {
    color: #00897b;
}

.item-category {
    color: #666;
    font-size: 0.9rem;
}

.item-price {
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
    font-weight: 700;
    font-size: 1.1rem;
}

.item-quantity {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.item-quantity label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
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
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: #e0e0e0;
}

.quantity-controls input {
    border: none;
    padding: 0.5rem;
    text-align: center;
    width: 50px;
    font-size: 0.9rem;
}

.quantity-controls input:focus {
    outline: none;
}

.item-total {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.total-label {
    font-size: 0.9rem;
    color: #666;
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #00897b;
}

.item-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.remove-btn,
.favorite-btn {
    background: #f5f5f5;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn:hover {
    background: #f44336;
    color: white;
    transform: scale(1.1);
}

.favorite-btn:hover {
    background: #e91e63;
    color: white;
    transform: scale(1.1);
}

/* ملخص الطلب */
.cart-summary-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.summary-card {
    padding: 2rem;
}

.summary-card h3 {
    color: #333;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-total {
    font-size: 1.2rem;
    font-weight: 700;
    color: #00897b;
    border-top: 2px solid #e0e0e0;
    padding-top: 1rem;
    margin-top: 1rem;
}

.free-shipping-notice {
    background: #e8f5e8;
    color: #2e7d32;
    padding: 0.75rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    margin: 1rem 0;
}

.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.checkout-btn {
    padding: 1rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.continue-shopping-btn {
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.payment-methods {
    border-top: 1px solid #e0e0e0;
    padding-top: 1.5rem;
}

.payment-methods h4 {
    color: #333;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.payment-icons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.payment-icons i {
    font-size: 2rem;
    color: #666;
    transition: color 0.3s ease;
}

.payment-icons i:hover {
    color: #00897b;
}

/* كوبون الخصم */
.coupon-card {
    padding: 1.5rem;
}

.coupon-card h4 {
    color: #333;
    margin-bottom: 1rem;
}

.coupon-form {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.coupon-form input {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 0.9rem;
}

.coupon-form input:focus {
    border-color: #00897b;
    outline: none;
}

.apply-coupon-btn {
    background: #00897b;
    color: white;
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.apply-coupon-btn:hover {
    background: #00695c;
}

#couponMessage {
    font-size: 0.9rem;
    padding: 0.5rem;
    border-radius: 4px;
}

#couponMessage.success {
    background: #e8f5e8;
    color: #2e7d32;
}

#couponMessage.error {
    background: #ffebee;
    color: #c62828;
}

/* نصائح الأمان */
.security-notice {
    padding: 1.5rem;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.security-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.security-notice h4 {
    margin-bottom: 0.5rem;
}

.security-notice p {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* قسم المنتجات المقترحة */
.suggested-products-section {
    padding: 4rem 0;
    background: #f8f9fa;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.product-card {
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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
    width: 40px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
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

.product-name a {
    color: #333;
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #00897b;
}

.add-to-cart-btn {
    width: 100%;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
    }
    
    .cart-item {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .item-image {
        width: 100%;
        height: 200px;
    }
    
    .item-quantity,
    .item-total,
    .item-actions {
        justify-self: center;
    }
    
    .item-actions {
        flex-direction: row;
        gap: 1rem;
    }
    
    .cart-summary-sidebar {
        position: static;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// نقل إلى المفضلة
function moveToWishlist(productId) {
    // محاكاة نقل إلى المفضلة
    const btn = event.target;
    const icon = btn.querySelector('i');
    
    icon.classList.remove('far');
    icon.classList.add('fas');
    icon.style.color = '#e91e63';
    
    // إزالة من السلة بعد ثانية
    setTimeout(() => {
        if (window.cartManager) {
            window.cartManager.removeFromCart(productId);
        }
    }, 1000);
}

// تطبيق كوبون الخصم
function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim();
    const messageElement = document.getElementById('couponMessage');
    
    if (!couponCode) {
        messageElement.textContent = 'يرجى إدخال كود الخصم';
        messageElement.className = 'error';
        return;
    }
    
    // محاكاة تطبيق الكوبون
    if (couponCode.toLowerCase() === 'discount10') {
        messageElement.textContent = 'تم تطبيق خصم 10% بنجاح!';
        messageElement.className = 'success';
        
        // تحديث الأسعار
        updatePricesWithDiscount(0.1);
    } else {
        messageElement.textContent = 'كود الخصم غير صحيح';
        messageElement.className = 'error';
    }
}

// تحديث الأسعار مع الخصم
function updatePricesWithDiscount(discountRate) {
    const totalElement = document.querySelector('.summary-total span:last-child');
    const currentTotal = parseFloat(totalElement.textContent.replace(/[^\d.]/g, ''));
    const discountedTotal = currentTotal * (1 - discountRate);
    
    totalElement.textContent = discountedTotal.toFixed(2) + ' ر.س';
}
</script> 