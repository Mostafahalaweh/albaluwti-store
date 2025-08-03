<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من وجود معرف الطلب
if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// جلب تفاصيل الطلب (للمستخدم المحدد فقط)
$stmt = $conn->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: orders.php');
    exit();
}

// جلب منتجات الطلب
$order_items = [];
$stmt = $conn->prepare('SELECT oi.*, p.name, p.image_url, p.description FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $order_items[] = $row;
}
$stmt->close();

include '../includes/header.php';
?>

<div class="container view-order-page fade-in slide-up">
    <div class="order-header">
        <h2 class="section-title">تفاصيل الطلب #<?= $order['id'] ?></h2>
        <a href="orders.php" class="btn btn-secondary scale-hover">العودة للطلبات</a>
    </div>
    
    <div class="order-content">
        <div class="order-status-card card fade-in">
            <h3>حالة الطلب</h3>
            <div class="status-info">
                <span class="status-badge status-<?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span>
                <p class="order-date">تاريخ الطلب: <?= htmlspecialchars($order['created_at']) ?></p>
            </div>
            
            <div class="order-progress">
                <div class="progress-step <?= in_array($order['status'], ['pending', 'confirmed', 'shipped', 'delivered']) ? 'active' : '' ?>">
                    <span class="step-icon">📋</span>
                    <span class="step-text">تم الطلب</span>
                </div>
                <div class="progress-step <?= in_array($order['status'], ['confirmed', 'shipped', 'delivered']) ? 'active' : '' ?>">
                    <span class="step-icon">✅</span>
                    <span class="step-text">تم التأكيد</span>
                </div>
                <div class="progress-step <?= in_array($order['status'], ['shipped', 'delivered']) ? 'active' : '' ?>">
                    <span class="step-icon">🚚</span>
                    <span class="step-text">في الطريق</span>
                </div>
                <div class="progress-step <?= $order['status'] === 'delivered' ? 'active' : '' ?>">
                    <span class="step-icon">🏠</span>
                    <span class="step-text">تم التوصيل</span>
                </div>
            </div>
        </div>
        
        <div class="order-details card fade-in">
            <h3>تفاصيل الطلب</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">رقم الطلب:</span>
                    <span class="detail-value">#<?= $order['id'] ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">تاريخ الطلب:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['created_at']) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">المجموع:</span>
                    <span class="detail-value"><?= htmlspecialchars($order['total']) ?> د.أ</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">طريقة الدفع:</span>
                    <span class="detail-value">الدفع عند الاستلام</span>
                </div>
            </div>
        </div>
        
        <div class="customer-info card fade-in">
            <h3>معلومات العميل</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">الاسم:</span>
                    <span class="info-value"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">الهاتف:</span>
                    <span class="info-value"><?= htmlspecialchars($order['customer_phone']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span>
                    <span class="info-value"><?= htmlspecialchars($order['customer_email']) ?></span>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">عنوان التوصيل:</span>
                    <span class="info-value"><?= htmlspecialchars($order['shipping_address']) ?></span>
                </div>
            </div>
        </div>
        
        <div class="order-items card fade-in">
            <h3>المنتجات المطلوبة</h3>
            <div class="items-list">
                <?php foreach ($order_items as $item): ?>
                    <div class="order-item">
                        <div class="item-image">
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="item-details">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <p class="item-description"><?= htmlspecialchars($item['description'] ?? '') ?></p>
                            <div class="item-meta">
                                <span class="quantity">الكمية: <?= $item['quantity'] ?></span>
                                <span class="price">السعر: <?= htmlspecialchars($item['price']) ?> د.أ</span>
                            </div>
                        </div>
                        <div class="item-total">
                            <span class="total-amount"><?= htmlspecialchars($item['price'] * $item['quantity']) ?> د.أ</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="order-summary">
                <div class="summary-line">
                    <span>المجموع الفرعي:</span>
                    <span><?= htmlspecialchars($order['total']) ?> د.أ</span>
                </div>
                <div class="summary-line">
                    <span>الشحن:</span>
                    <span>مجاناً</span>
                </div>
                <div class="summary-line total">
                    <span>الإجمالي:</span>
                    <span><?= htmlspecialchars($order['total']) ?> د.أ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 