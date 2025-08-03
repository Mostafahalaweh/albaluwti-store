<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$page_title = 'المفضلة - متجر البلوطي';
$active = 'wishlist';

// الحصول على المنتجات المفضلة
$wishlist_items = getWishlistItems($_SESSION['user_id'], 50);

include '../includes/header.php';
?>

<div class="wishlist-container">
    <div class="wishlist-header">
        <h1>قائمة المفضلة</h1>
        <p>المنتجات التي أضفتها إلى قائمة المفضلة</p>
    </div>

    <?php if (empty($wishlist_items)): ?>
        <div class="empty-wishlist">
            <div class="empty-icon">❤️</div>
            <h2>قائمة المفضلة فارغة</h2>
            <p>لم تقم بإضافة أي منتجات إلى المفضلة بعد</p>
            <a href="../pages/products.php" class="btn cta-btn">تصفح المنتجات</a>
        </div>
    <?php else: ?>
        <div class="wishlist-actions">
            <button class="btn btn-outline" id="addAllToCart">إضافة الكل للسلة</button>
            <button class="btn btn-danger" id="clearWishlist">مسح الكل</button>
        </div>

        <div class="wishlist-grid">
            <?php foreach ($wishlist_items as $item): ?>
                <div class="wishlist-item" data-product-id="<?= $item['id'] ?>">
                    <div class="item-image">
                        <img src="../assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="item-overlay">
                            <a href="product.php?id=<?= $item['id'] ?>" class="btn btn-sm">عرض التفاصيل</a>
                        </div>
                    </div>
                    <div class="item-content">
                        <h3 class="item-title"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="item-description"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                        <div class="item-price"><?= formatPrice($item['price']) ?></div>
                        <div class="item-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= ($item['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                            <?php endfor; ?>
                            <span class="rating-count">(<?= $item['review_count'] ?? 0 ?>)</span>
                        </div>
                        <div class="item-actions">
                            <button class="btn btn-sm add-to-cart" data-product-id="<?= $item['id'] ?>">
                                إضافة للسلة
                            </button>
                            <button class="btn btn-sm btn-outline remove-wishlist" data-product-id="<?= $item['id'] ?>">
                                إزالة من المفضلة
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="wishlist-summary">
            <div class="summary-card">
                <h3>ملخص المفضلة</h3>
                <div class="summary-stats">
                    <div class="stat">
                        <span class="stat-label">عدد المنتجات:</span>
                        <span class="stat-value"><?= count($wishlist_items) ?></span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">إجمالي السعر:</span>
                        <span class="stat-value"><?= formatPrice(array_sum(array_column($wishlist_items, 'price'))) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.wishlist-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.wishlist-header {
    text-align: center;
    margin-bottom: 3rem;
}

.wishlist-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.wishlist-header p {
    font-size: 1.1rem;
    color: #666;
}

.empty-wishlist {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-wishlist h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-wishlist p {
    color: #666;
    margin-bottom: 2rem;
}

.wishlist-actions {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
    flex-wrap: wrap;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.wishlist-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.wishlist-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.item-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-item:hover .item-image img {
    transform: scale(1.05);
}

.item-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wishlist-item:hover .item-overlay {
    opacity: 1;
}

.item-content {
    padding: 1.5rem;
}

.item-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.item-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.item-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.item-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 1rem;
}

.item-rating .fas {
    color: #ffd700;
    font-size: 0.9rem;
}

.rating-count {
    font-size: 0.8rem;
    color: #666;
    margin-left: 0.5rem;
}

.item-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.item-actions .btn {
    flex: 1;
    min-width: 120px;
}

.wishlist-summary {
    display: flex;
    justify-content: center;
}

.summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    text-align: center;
    min-width: 300px;
}

.summary-card h3 {
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
}

.summary-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.stat:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat-value {
    font-weight: 600;
    font-size: 1.1rem;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}

@media (max-width: 768px) {
    .wishlist-grid {
        grid-template-columns: 1fr;
    }
    
    .wishlist-actions {
        flex-direction: column;
    }
    
    .item-actions {
        flex-direction: column;
    }
    
    .summary-card {
        min-width: auto;
        width: 100%;
    }
}
</style>

<script>
// إضافة للسلة
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        addToCart(productId);
    });
});

// إزالة من المفضلة
document.querySelectorAll('.remove-wishlist').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        removeFromWishlist(productId);
    });
});

// إضافة الكل للسلة
document.getElementById('addAllToCart')?.addEventListener('click', function() {
    const productIds = Array.from(document.querySelectorAll('.wishlist-item')).map(item => 
        item.dataset.productId
    );
    
    if (productIds.length === 0) {
        showNotification('لا توجد منتجات لإضافتها', 'info');
        return;
    }
    
    addAllToCart(productIds);
});

// مسح الكل
document.getElementById('clearWishlist')?.addEventListener('click', function() {
    if (confirm('هل أنت متأكد من رغبتك في مسح جميع المنتجات من المفضلة؟')) {
        clearWishlist();
    }
});

function addToCart(productId) {
    fetch('../pages/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم إضافة المنتج إلى السلة بنجاح', 'success');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الإضافة', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function removeFromWishlist(productId) {
    fetch('../pages/wishlist_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم إزالة المنتج من المفضلة', 'success');
            const item = document.querySelector(`[data-product-id="${productId}"]`);
            if (item) {
                item.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    item.remove();
                    updateWishlistCount();
                }, 300);
            }
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الإزالة', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function addAllToCart(productIds) {
    const promises = productIds.map(productId => 
        fetch('../pages/cart_actions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=add&product_id=${productId}&quantity=1`
        }).then(response => response.json())
    );
    
    Promise.all(promises)
        .then(results => {
            const successCount = results.filter(result => result.success).length;
            if (successCount > 0) {
                showNotification(`تم إضافة ${successCount} منتج إلى السلة`, 'success');
                updateCartCount(results[0].cart_count);
            } else {
                showNotification('حدث خطأ أثناء إضافة المنتجات', 'error');
            }
        })
        .catch(error => {
            showNotification('حدث خطأ في الاتصال', 'error');
        });
}

function clearWishlist() {
    fetch('../pages/wishlist_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=clear'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم مسح جميع المنتجات من المفضلة', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'حدث خطأ أثناء المسح', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function updateWishlistCount() {
    const items = document.querySelectorAll('.wishlist-item');
    if (items.length === 0) {
        location.reload();
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
    }
}

// إضافة CSS للانيميشن
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.8); }
    }
`;
document.head.appendChild(style);
</script>

<?php include '../includes/footer.php'; ?> 