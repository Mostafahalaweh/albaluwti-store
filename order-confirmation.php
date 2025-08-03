<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

// التحقق من وجود معرف الطلب
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$order_id = intval($_GET['id']);

// جلب تفاصيل الطلب
$stmt = $conn->prepare('
    SELECT o.*, u.name as user_name, u.email as user_email 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = ?
');
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: index.php');
    exit();
}

// التحقق من أن المستخدم يملك هذا الطلب
if (isset($_SESSION['user_id']) && $order['user_id'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit();
}

// جلب منتجات الطلب
$stmt = $conn->prepare('
    SELECT oi.*, p.name, p.image_url, p.slug
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
');
$stmt->bind_param('i', $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// الحصول على الإعدادات
$settings = getSettings();

include __DIR__ . '/../includes/header.php';
?>

<div class="container order-confirmation-page fade-in slide-up">
    <div class="confirmation-header">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="section-title">تم إتمام طلبك بنجاح!</h1>
        <p class="confirmation-message">
            شكراً لك على طلبك. تم استلام طلبك وسنقوم بمراجعته والتواصل معك قريباً.
        </p>
    </div>
    
    <div class="order-details-container">
        <!-- معلومات الطلب -->
        <div class="order-info">
            <h3>معلومات الطلب</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">رقم الطلب:</span>
                    <span class="value order-number">#<?= $order_id ?></span>
                </div>
                <div class="info-item">
                    <span class="label">تاريخ الطلب:</span>
                    <span class="value"><?= formatDateArabic($order['created_at']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">حالة الطلب:</span>
                    <span class="value status-<?= $order['status'] ?>">
                        <?php
                        $status_labels = [
                            'pending' => 'قيد المراجعة',
                            'confirmed' => 'مؤكد',
                            'processing' => 'قيد التحضير',
                            'shipped' => 'تم الشحن',
                            'delivered' => 'تم التوصيل',
                            'cancelled' => 'ملغي'
                        ];
                        echo $status_labels[$order['status']] ?? $order['status'];
                        ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">طريقة الدفع:</span>
                    <span class="value"><?= htmlspecialchars($order['payment_method']) ?></span>
                </div>
            </div>
        </div>
        
        <!-- معلومات العميل -->
        <div class="customer-info">
            <h3>معلومات العميل</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">الاسم:</span>
                    <span class="value"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">رقم الهاتف:</span>
                    <span class="value"><?= htmlspecialchars($order['customer_phone']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">البريد الإلكتروني:</span>
                    <span class="value"><?= htmlspecialchars($order['customer_email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">عنوان الشحن:</span>
                    <span class="value"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></span>
                </div>
                <?php if (!empty($order['notes'])): ?>
                <div class="info-item">
                    <span class="label">ملاحظات:</span>
                    <span class="value"><?= nl2br(htmlspecialchars($order['notes'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- منتجات الطلب -->
        <div class="order-items">
            <h3>المنتجات المطلوبة</h3>
            <div class="items-list">
                <?php foreach ($order_items as $item): ?>
                <div class="order-item">
                    <div class="item-image">
                        <img src="<?= htmlspecialchars($item['image_url'] ?? '../assets/images/placeholder.png') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="item-details">
                        <h4 class="item-name">
                            <a href="product-details.php?id=<?= $item['product_id'] ?>&slug=<?= $item['slug'] ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </h4>
                        <div class="item-meta">
                            <span class="quantity">الكمية: <?= $item['quantity'] ?></span>
                            <span class="price">السعر: <?= formatPrice($item['price']) ?></span>
                        </div>
                    </div>
                    <div class="item-total">
                        <?= formatPrice($item['subtotal']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- ملخص التكلفة -->
        <div class="order-summary">
            <h3>ملخص التكلفة</h3>
            <div class="summary-table">
                <div class="summary-row">
                    <span>المجموع الفرعي:</span>
                    <span><?= formatPrice($order['total']) ?></span>
                </div>
                <div class="summary-row">
                    <span>تكلفة الشحن:</span>
                    <span>
                        <?php if ($order['shipping_cost'] == 0): ?>
                            <span class="free-shipping">مجاناً</span>
                        <?php else: ?>
                            <?= formatPrice($order['shipping_cost']) ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="summary-row final-total">
                    <span>المجموع النهائي:</span>
                    <span><?= formatPrice($order['final_total']) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- الخطوات التالية -->
    <div class="next-steps">
        <h3>الخطوات التالية</h3>
        <div class="steps-grid">
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <h4>تأكيد الطلب</h4>
                <p>سنقوم بالتواصل معك خلال 24 ساعة لتأكيد طلبك</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h4>تحضير الطلب</h4>
                <p>سيتم تحضير طلبك وتغليفه بعناية</p>
            </div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h4>الشحن والتوصيل</h4>
                <p>سيتم شحن طلبك والتواصل معك لتحديد موعد التوصيل</p>
            </div>
        </div>
    </div>
    
    <!-- معلومات الاتصال -->
    <div class="contact-info">
        <h3>معلومات الاتصال</h3>
        <div class="contact-grid">
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h4>الهاتف</h4>
                    <p><?= htmlspecialchars($settings['site_phone'] ?? '+966-50-123-4567') ?></p>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>البريد الإلكتروني</h4>
                    <p><?= htmlspecialchars($settings['site_email'] ?? 'info@albaluwti.com') ?></p>
                </div>
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>العنوان</h4>
                    <p><?= htmlspecialchars($settings['site_address'] ?? 'الرياض، المملكة العربية السعودية') ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- أزرار الإجراءات -->
    <div class="action-buttons">
        <a href="track_order.php?id=<?= $order_id ?>" class="btn cta-btn">
            <i class="fas fa-search"></i>
            تتبع الطلب
        </a>
        <a href="orders.php" class="btn btn-secondary">
            <i class="fas fa-list"></i>
            جميع طلباتي
        </a>
        <a href="products.php" class="btn btn-outline">
            <i class="fas fa-shopping-bag"></i>
            متابعة التسوق
        </a>
    </div>
</div>

<style>
.order-confirmation-page {
    padding: 2rem 0;
}

.confirmation-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border-radius: 12px;
}

.success-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.confirmation-message {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-top: 1rem;
}

.order-details-container {
    display: grid;
    gap: 2rem;
    margin-bottom: 3rem;
}

.order-info,
.customer-info,
.order-items,
.order-summary {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.order-info h3,
.customer-info h3,
.order-items h3,
.order-summary h3 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
}

.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-weight: 600;
    color: #666;
    min-width: 120px;
}

.info-item .value {
    color: #333;
    text-align: left;
    flex: 1;
}

.order-number {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.status-pending { color: #ffc107; font-weight: 600; }
.status-confirmed { color: #17a2b8; font-weight: 600; }
.status-processing { color: #007bff; font-weight: 600; }
.status-shipped { color: #28a745; font-weight: 600; }
.status-delivered { color: #28a745; font-weight: 600; }
.status-cancelled { color: #dc3545; font-weight: 600; }

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 8px;
}

.item-image img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.item-details {
    flex: 1;
}

.item-name {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
}

.item-name a {
    color: var(--primary-color);
    text-decoration: none;
}

.item-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
    color: #666;
}

.item-total {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 1.1rem;
}

.summary-table {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
}

.final-total {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    border-top: 2px solid #eee;
    padding-top: 1rem;
    margin-top: 0.5rem;
}

.free-shipping {
    color: #28a745;
    font-weight: 600;
}

.next-steps {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.next-steps h3 {
    color: var(--primary-color);
    margin-bottom: 2rem;
    text-align: center;
    font-size: 1.3rem;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.step {
    text-align: center;
    padding: 1.5rem;
}

.step-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.step h4 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.step p {
    color: #666;
    font-size: 0.9rem;
}

.contact-info {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.contact-info h3 {
    color: var(--primary-color);
    margin-bottom: 2rem;
    text-align: center;
    font-size: 1.3rem;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
}

.contact-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
    width: 40px;
    text-align: center;
}

.contact-item h4 {
    margin: 0 0 0.25rem 0;
    color: var(--primary-color);
}

.contact-item p {
    margin: 0;
    color: #666;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 150px;
}

@media (max-width: 768px) {
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?> 