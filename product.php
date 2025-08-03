<?php
require_once '../php/db.php';
require_once '../php/functions.php';
session_start();
include '../includes/header.php';

// جلب بيانات المنتج
$product = null;
if (isset($_GET['id'])) {
    $stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
if (!$product) {
    echo '<div class="container"><div class="message error">المنتج غير موجود.</div></div>';
    include '../includes/footer.php';
    exit;
}
?>
<div class="container product-page fade-in slide-up">
    <div class="product-details card">
        <div class="product-img-box">
            <img src="<?= htmlspecialchars($product['image'] ? '../assets/images/products/' . $product['image'] : '../assets/images/placeholder.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
        </div>
        <div class="product-info">
            <h2 class="product-title"><?= htmlspecialchars($product['name']) ?></h2>
            <div class="product-category">الفئة: <?= htmlspecialchars($product['category']) ?></div>
            <div class="product-price"><?= htmlspecialchars($product['price']) ?> د.أ</div>
            <div class="product-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
            <form class="add-to-cart-form" method="post" action="cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label>الكمية:</label>
                <input type="number" name="quantity" value="1" min="1" class="qty-input">
                <button type="submit" class="btn cta-btn scale-hover">أضف إلى السلة</button>
            </form>
        </div>
    </div>
    <section class="reviews-section card fade-in">
        <h3 class="section-title">التقييمات</h3>
        <div id="reviews-list">
            <?php
            $stmt = $conn->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC');
            $stmt->bind_param('i', $product['id']);
            $stmt->execute();
            $reviews = $stmt->get_result();
            if ($reviews->num_rows === 0) {
                echo '<div class="message">لا توجد تقييمات بعد.</div>';
            } else {
                while ($review = $reviews->fetch_assoc()) {
                    echo '<div class="review-item">';
                    echo '<div class="review-user">' . htmlspecialchars($review['user_name']) . '</div>';
                    echo '<div class="review-rating">' . str_repeat('★', (int)$review['rating']) . '</div>';
                    echo '<div class="review-text">' . nl2br(htmlspecialchars($review['review'])) . '</div>';
                    echo '<div class="review-date">' . htmlspecialchars($review['created_at']) . '</div>';
                    echo '</div>';
                }
            }
            $stmt->close();
            ?>
        </div>
        <form class="add-review-form" method="post" action="add_review.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <label>التقييم:</label>
            <select name="rating" required>
                <option value="">اختر...</option>
                <option value="5">5 ★</option>
                <option value="4">4 ★</option>
                <option value="3">3 ★</option>
                <option value="2">2 ★</option>
                <option value="1">1 ★</option>
            </select>
            <textarea name="review" placeholder="اكتب رأيك..." required></textarea>
            <button type="submit" class="btn btn-sm cta-btn scale-hover">إرسال التقييم</button>
        </form>
    </section>
    <section class="related-products-section fade-in">
        <h3 class="section-title">منتجات ذات صلة</h3>
        <div class="products-grid">
            <?php
            $stmt = $conn->prepare('SELECT * FROM products WHERE category = ? AND id != ? ORDER BY RAND() LIMIT 4');
            $stmt->bind_param('si', $product['category'], $product['id']);
            $stmt->execute();
            $related = $stmt->get_result();
            if ($related->num_rows === 0) {
                echo '<div class="message">لا يوجد منتجات ذات صلة.</div>';
            } else {
                while ($rel = $related->fetch_assoc()) {
                    echo '<div class="product-card scale-hover">';
                    echo '<a href="product.php?id=' . $rel['id'] . '">';
                    echo '<img src="' . htmlspecialchars($rel['image'] ? '../assets/images/products/' . $rel['image'] : '../assets/images/placeholder.png') . '" alt="' . htmlspecialchars($rel['name']) . '" class="product-image">';
                    echo '<div class="product-name">' . htmlspecialchars($rel['name']) . '</div>';
                    echo '<div class="product-price">' . htmlspecialchars($rel['price']) . ' د.أ</div>';
                    echo '</a>';
                    echo '</div>';
                }
            }
            $stmt->close();
            ?>
        </div>
    </section>
</div>
<?php include '../includes/footer.php'; ?>
