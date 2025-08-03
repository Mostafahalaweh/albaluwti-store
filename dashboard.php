<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$page_title = 'لوحة التحكم - متجر البلوطي';
$active = 'dashboard';

// الحصول على معلومات المستخدم
$user = getUserInfo($_SESSION['user_id']);
$orders = getUserOrders($_SESSION['user_id'], 5);
$recent_activities = getRecentActivities($_SESSION['user_id'], 10);
$wishlist_items = getWishlistItems($_SESSION['user_id'], 6);
$recommendations = getRecommendedProducts($_SESSION['user_id'], 6);

include '../includes/header.php';
?>

<div class="dashboard-container">
    <!-- شريط الترحيب -->
    <div class="welcome-banner">
        <div class="welcome-content">
            <h1>مرحباً <?= htmlspecialchars($user['name']) ?>! 👋</h1>
            <p>إليك ملخص نشاطك في متجر البلوطي</p>
        </div>
        <div class="welcome-actions">
            <a href="profile.php" class="btn btn-outline">تعديل الملف الشخصي</a>
            <a href="orders.php" class="btn">عرض جميع الطلبات</a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <h3><?= count($orders) ?></h3>
                <p>إجمالي الطلبات</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💳</div>
            <div class="stat-content">
                <h3><?= calculateTotalSpent($_SESSION['user_id']) ?> ر.س</h3>
                <p>إجمالي المشتريات</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-content">
                <h3><?= getUserRating($_SESSION['user_id']) ?></h3>
                <p>تقييمك العام</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎁</div>
            <div class="stat-content">
                <h3><?= getUserPoints($_SESSION['user_id']) ?></h3>
                <p>نقاط الولاء</p>
            </div>
        </div>
    </div>

    <!-- الأقسام الرئيسية -->
    <div class="dashboard-sections">
        <!-- الطلبات الأخيرة -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>الطلبات الأخيرة</h2>
                <a href="orders.php" class="view-all">عرض الكل</a>
            </div>
            <div class="orders-list">
                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>لا توجد طلبات بعد</h3>
                        <p>ابدأ التسوق الآن واحصل على أول طلب لك!</p>
                        <a href="../pages/products.php" class="btn">تصفح المنتجات</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <div class="order-info">
                                <h4>طلب رقم #<?= $order['id'] ?></h4>
                                <p class="order-date"><?= formatDateArabic($order['created_at']) ?></p>
                                <span class="order-status status-<?= $order['status'] ?>">
                                    <?= getStatusText($order['status']) ?>
                                </span>
                            </div>
                            <div class="order-actions">
                                <a href="view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm">عرض التفاصيل</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- المنتجات المفضلة -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>المفضلة</h2>
                <a href="wishlist.php" class="view-all">عرض الكل</a>
            </div>
            <div class="wishlist-grid">
                <?php if (empty($wishlist_items)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">❤️</div>
                        <h3>قائمة المفضلة فارغة</h3>
                        <p>أضف المنتجات التي تحبها إلى قائمة المفضلة</p>
                        <a href="../pages/products.php" class="btn">تصفح المنتجات</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($wishlist_items as $item): ?>
                        <div class="wishlist-item">
                            <img src="../assets/images/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="item-info">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <p class="price"><?= formatPrice($item['price']) ?></p>
                            </div>
                            <div class="item-actions">
                                <button class="btn btn-sm add-to-cart" data-product-id="<?= $item['id'] ?>">إضافة للسلة</button>
                                <button class="btn btn-sm btn-outline remove-wishlist" data-product-id="<?= $item['id'] ?>">إزالة</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- التوصيات الشخصية -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>توصيات لك</h2>
                <a href="../pages/products.php" class="view-all">تصفح المزيد</a>
            </div>
            <div class="recommendations-grid">
                <?php foreach ($recommendations as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="../assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="product-overlay">
                                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm">عرض التفاصيل</a>
                                <button class="btn btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">إضافة للسلة</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h4><?= htmlspecialchars($product['name']) ?></h4>
                            <p class="price"><?= formatPrice($product['price']) ?></p>
                            <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= ($product['rating'] ?? 0) ? 'filled' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- النشاط الأخير -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>النشاط الأخير</h2>
            </div>
            <div class="activity-timeline">
                <?php foreach ($recent_activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <?= getActivityIcon($activity['type']) ?>
                        </div>
                        <div class="activity-content">
                            <p><?= htmlspecialchars($activity['description']) ?></p>
                            <span class="activity-time"><?= timeAgo($activity['created_at']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.welcome-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.welcome-content h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.welcome-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    font-size: 2rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #333;
}

.stat-content p {
    color: #666;
    margin: 0;
}

.dashboard-sections {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.dashboard-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.view-all {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.view-all:hover {
    color: #764ba2;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.empty-state p {
    color: #666;
    margin-bottom: 1.5rem;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.order-item:hover {
    background: #e9ecef;
}

.order-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    color: #333;
}

.order-date {
    color: #666;
    font-size: 0.9rem;
    margin: 0 0 0.5rem 0;
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-processing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1e7dd; color: #0f5132; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.wishlist-item {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.wishlist-item:hover {
    transform: translateY(-5px);
}

.wishlist-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.item-info {
    padding: 1rem;
}

.item-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
    color: #333;
}

.price {
    font-weight: 600;
    color: #667eea;
    margin: 0;
}

.item-actions {
    padding: 0 1rem 1rem;
    display: flex;
    gap: 0.5rem;
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.product-card {
    background: #f8f9fa;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    height: 120px;
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
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-info {
    padding: 1rem;
}

.product-info h4 {
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
    color: #333;
}

.rating {
    color: #ffd700;
    font-size: 0.8rem;
}

.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.activity-icon {
    font-size: 1.2rem;
    color: #667eea;
}

.activity-content p {
    margin: 0 0 0.25rem 0;
    color: #333;
}

.activity-time {
    font-size: 0.8rem;
    color: #666;
}

@media (max-width: 768px) {
    .dashboard-sections {
        grid-template-columns: 1fr;
    }
    
    .welcome-banner {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
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
            location.reload();
        } else {
            showNotification(data.message || 'حدث خطأ أثناء الإزالة', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
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
</script>

<?php include '../includes/footer.php'; ?> 