<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// جلب بيانات المستخدم
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// جلب محتويات السلة
$cart_items = getCartItems();
$cart_total = calculateCartTotal();

// إذا كانت السلة فارغة، توجيه إلى صفحة السلة
if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

// الحصول على الإعدادات
$settings = getSettings();
$shipping_cost = floatval($settings['shipping_cost'] ?? 30.00);
$free_shipping_threshold = floatval($settings['free_shipping_threshold'] ?? 200.00);

// حساب تكلفة الشحن
$final_shipping_cost = ($cart_total >= $free_shipping_threshold) ? 0 : $shipping_cost;
$final_total = $cart_total + $final_shipping_cost;

$message = '';
$message_type = '';

// معالجة إتمام الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = sanitizeInput($_POST['customer_name']);
    $customer_phone = sanitizeInput($_POST['customer_phone']);
    $customer_email = sanitizeInput($_POST['customer_email']);
    $shipping_address = sanitizeInput($_POST['shipping_address']);
    $payment_method = sanitizeInput($_POST['payment_method']);
    $notes = sanitizeInput($_POST['notes'] ?? '');
    
    // التحقق من صحة البيانات
    $errors = [];
    
    if (empty($customer_name)) {
        $errors[] = 'اسم العميل مطلوب';
    }
    
    if (empty($customer_phone)) {
        $errors[] = 'رقم الهاتف مطلوب';
    } elseif (!preg_match('/^[0-9+\-\s()]+$/', $customer_phone)) {
        $errors[] = 'رقم الهاتف غير صحيح';
    }
    
    if (empty($customer_email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    } elseif (!isValidEmail($customer_email)) {
        $errors[] = 'البريد الإلكتروني غير صحيح';
    }
    
    if (empty($shipping_address)) {
        $errors[] = 'عنوان الشحن مطلوب';
    }
    
    if (empty($payment_method)) {
        $errors[] = 'طريقة الدفع مطلوبة';
    }
    
    if (empty($errors)) {
        try {
            // بدء المعاملة
            $conn->begin_transaction();
            
            // إنشاء الطلب
            $stmt = $conn->prepare('
                INSERT INTO orders (
                    user_id, customer_name, phone, address, 
                    order_details, total_price, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, "pending", NOW())
            ');
            
            // تجميع تفاصيل الطلب
            $order_details = "طريقة الدفع: $payment_method\n";
            $order_details .= "البريد الإلكتروني: $customer_email\n";
            if (!empty($notes)) {
                $order_details .= "ملاحظات: $notes\n";
            }
            
            $stmt->bind_param('issssd', 
                $user_id, $customer_name, $customer_phone, 
                $shipping_address, $order_details, $final_total
            );
            
            if ($stmt->execute()) {
                $order_id = $conn->insert_id;
                
                // إضافة منتجات الطلب
                $stmt2 = $conn->prepare('
                    INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ');
                
                foreach ($cart_items as $item) {
                    $item_price = $item['price']; // استخدام السعر الأساسي
                    
                    $stmt2->bind_param('iiid', $order_id, $item['product_id'], $item['quantity'], $item_price);
                    $stmt2->execute();
                    
                    // تحديث كمية المنتج
                    $update_stmt = $conn->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?');
                    $update_stmt->bind_param('ii', $item['quantity'], $item['product_id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $stmt2->close();
                
                // تسجيل النشاط
                logActivity($user_id, 'order_created', "تم إنشاء طلب جديد برقم: #$order_id");
                
                // تأكيد المعاملة
                $conn->commit();
                
                // تفريغ السلة
                clearCart();
                
                // إرسال بريد إلكتروني تأكيد (اختياري)
                if (function_exists('sendHTMLEmail')) {
                    $subject = "تأكيد طلبك - متجر البلوطي";
                    $html_message = "
                        <h2>مرحباً $customer_name</h2>
                        <p>شكراً لك على طلبك. تم استلام طلبك بنجاح.</p>
                        <p><strong>رقم الطلب:</strong> #$order_id</p>
                        <p><strong>المجموع:</strong> " . formatPrice($final_total) . "</p>
                        <p>سنقوم بمراجعة طلبك والتواصل معك قريباً.</p>
                    ";
                    sendHTMLEmail($customer_email, $subject, $html_message);
                }
                
                // توجيه إلى صفحة تأكيد الطلب
                header('Location: order-confirmation.php?id=' . $order_id);
                exit();
            } else {
                throw new Exception('فشل في إنشاء الطلب');
            }
            $stmt->close();
            
        } catch (Exception $e) {
            // التراجع عن المعاملة في حالة الخطأ
            $conn->rollback();
            $message = 'حدث خطأ أثناء إنشاء الطلب: ' . $e->getMessage();
            $message_type = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $message_type = 'error';
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container checkout-page fade-in slide-up">
    <h2 class="section-title">إتمام الطلب</h2>
    
    <?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>">
        <?= $message ?>
    </div>
    <?php endif; ?>
    
    <div class="checkout-container">
        <!-- نموذج الطلب -->
        <div class="checkout-form">
            <form method="POST" id="checkoutForm" class="needs-validation" novalidate>
                <div class="form-section">
                    <h3>معلومات العميل</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_name">الاسم الكامل *</label>
                            <input type="text" id="customer_name" name="customer_name" 
                                   value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">رقم الهاتف *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customer_email">البريد الإلكتروني *</label>
                        <input type="email" id="customer_email" name="customer_email" 
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>عنوان الشحن</h3>
                    <div class="form-group">
                        <label for="shipping_address">العنوان التفصيلي *</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required 
                                  placeholder="الحي، الشارع، رقم المنزل، المدينة، الرمز البريدي"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>طريقة الدفع</h3>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="cash" name="payment_method" value="نقداً عند الاستلام" checked>
                            <label for="cash">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>نقداً عند الاستلام</span>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="bank" name="payment_method" value="تحويل بنكي">
                            <label for="bank">
                                <i class="fas fa-university"></i>
                                <span>تحويل بنكي</span>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="card" name="payment_method" value="بطاقة ائتمان">
                            <label for="card">
                                <i class="fas fa-credit-card"></i>
                                <span>بطاقة ائتمان</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>ملاحظات إضافية</h3>
                    <div class="form-group">
                        <label for="notes">ملاحظات (اختياري)</label>
                        <textarea id="notes" name="notes" rows="3" 
                                  placeholder="أي ملاحظات إضافية أو تعليمات خاصة"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn cta-btn btn-large">
                        <i class="fas fa-check"></i>
                        إتمام الطلب
                    </button>
                    <a href="cart.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للسلة
                    </a>
                </div>
            </form>
        </div>
        
        <!-- ملخص الطلب -->
        <div class="order-summary">
            <h3>ملخص الطلب</h3>
            <div class="summary-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="summary-item">
                    <div class="item-info">
                        <img src="<?= htmlspecialchars($item['image'] ?? '../assets/images/placeholder.png') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="item-details">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <span class="quantity">الكمية: <?= $item['quantity'] ?></span>
                        </div>
                    </div>
                    <div class="item-price">
                        <?php 
                        $item_price = isset($item['sale_price']) && $item['sale_price'] && $item['sale_price'] < $item['price'] 
                            ? $item['sale_price'] : $item['price'];
                        echo formatPrice($item_price * $item['quantity']);
                        ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-totals">
                <div class="total-row">
                    <span>المجموع الفرعي:</span>
                    <span><?= formatPrice($cart_total) ?></span>
                </div>
                <div class="total-row">
                    <span>تكلفة الشحن:</span>
                    <span>
                        <?php if ($final_shipping_cost == 0): ?>
                            <span class="free-shipping">مجاناً</span>
                        <?php else: ?>
                            <?= formatPrice($final_shipping_cost) ?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($final_shipping_cost > 0 && $cart_total < $free_shipping_threshold): ?>
                <div class="shipping-notice">
                    <i class="fas fa-info-circle"></i>
                    أضف <?= formatPrice($free_shipping_threshold - $cart_total) ?> للحصول على شحن مجاني
                </div>
                <?php endif; ?>
                <div class="total-row final-total">
                    <span>المجموع النهائي:</span>
                    <span><?= formatPrice($final_total) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-page {
    padding: 2rem 0;
}

.checkout-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.checkout-form {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-method {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: var(--primary-color);
}

.payment-method input[type="radio"] {
    margin-right: 1rem;
}

.payment-method label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    margin: 0;
}

.payment-method i {
    font-size: 1.2rem;
    color: var(--primary-color);
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.order-summary {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.order-summary h3 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    text-align: center;
}

.summary-items {
    margin-bottom: 2rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.summary-item:last-child {
    border-bottom: none;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.item-info img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.item-details h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
}

.quantity {
    font-size: 0.8rem;
    color: #666;
}

.item-price {
    font-weight: 600;
    color: var(--primary-color);
}

.summary-totals {
    border-top: 2px solid #eee;
    padding-top: 1rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.final-total {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary-color);
    border-top: 1px solid #eee;
    padding-top: 1rem;
    margin-top: 1rem;
}

.free-shipping {
    color: #28a745;
    font-weight: 600;
}

.shipping-notice {
    background: #fff3cd;
    color: #856404;
    padding: 0.75rem;
    border-radius: 8px;
    margin: 1rem 0;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<!-- تضمين ملف معالجة الدفع -->
<script src="../assets/js/payment.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?> 