<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';
include '../includes/header.php';

$order = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $stmt = $conn->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();
    
    if (!$order) {
        $message = 'لم يتم العثور على الطلب. يرجى التأكد من رقم الطلب.';
    }
}
?>

<div class="container track-order-page fade-in slide-up">
    <h2 class="section-title">تتبع طلبك</h2>
    <div class="track-form card">
        <form method="post" class="track-order-form">
            <label>رقم الطلب:</label>
            <input type="number" name="order_id" placeholder="أدخل رقم الطلب" required>
            <button type="submit" class="btn cta-btn scale-hover">تتبع الطلب</button>
        </form>
    </div>
    
    <?php if ($message): ?>
        <div class="message error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <?php if ($order): ?>
        <div class="order-status card fade-in">
            <h3>تفاصيل الطلب #<?= $order['id'] ?></h3>
            <div class="order-info">
                <div><strong>التاريخ:</strong> <?= htmlspecialchars($order['created_at']) ?></div>
                <div><strong>المجموع:</strong> <?= htmlspecialchars($order['total']) ?> د.أ</div>
                <div><strong>الحالة:</strong> <span class="order-status status-<?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></div>
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
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?> 