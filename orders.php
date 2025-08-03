<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

$page_title = 'طلباتي - متجر البلوطي';
$active = 'orders';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: /albaluwti_backup/pages/login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// جلب الطلبات الخاصة بالمستخدم
$orders = [];
$stmt = $conn->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

include '../includes/header.php';
?>

<div class="container orders-page fade-in slide-up">
    <h2 class="section-title">طلباتي</h2>
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>لا توجد طلبات بعد</h3>
            <p>ابدأ التسوق الآن وستظهر طلباتك هنا</p>
            <a href="/albaluwti_backup/pages/products.php" class="btn cta-btn">تسوق الآن</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>التاريخ</th>
                    <th>المجموع</th>
                    <th>الحالة</th>
                    <th>تفاصيل</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td><?= htmlspecialchars($order['total']) ?> د.أ</td>
                        <td><span class="order-status status-<?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                        <td><a href="/albaluwti_backup/pages/view_order.php?id=<?= $order['id'] ?>" class="btn btn-sm">عرض</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>          