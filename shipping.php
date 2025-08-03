<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'سياسة الشحن - متجر البلوطي';
$active = 'shipping';

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم سياسة الشحن -->
<section class="shipping-section">
    <div class="container">
        <div class="shipping-header">
            <h1 class="section-title">سياسة الشحن</h1>
            <p class="section-description">تعرف على تفاصيل خدمة الشحن والتوصيل</p>
        </div>
        
        <div class="shipping-content">
            <!-- تكلفة الشحن -->
            <div class="shipping-card card">
                <div class="card-icon">💰</div>
                <h2>تكلفة الشحن</h2>
                <div class="shipping-costs">
                    <div class="cost-item">
                        <span class="cost-label">الطلبات أقل من 200 ريال:</span>
                        <span class="cost-value">30 ريال</span>
                    </div>
                    <div class="cost-item free">
                        <span class="cost-label">الطلبات 200 ريال وأكثر:</span>
                        <span class="cost-value">شحن مجاني</span>
                    </div>
                </div>
            </div>
            
            <!-- وقت التوصيل -->
            <div class="shipping-card card">
                <div class="card-icon">⏰</div>
                <h2>وقت التوصيل</h2>
                <div class="delivery-times">
                    <div class="time-item">
                        <h4>المدن الرئيسية</h4>
                        <p>1-3 أيام عمل</p>
                        <ul>
                            <li>الرياض</li>
                            <li>جدة</li>
                            <li>الدمام</li>
                            <li>مكة المكرمة</li>
                            <li>المدينة المنورة</li>
                        </ul>
                    </div>
                    <div class="time-item">
                        <h4>المناطق الأخرى</h4>
                        <p>3-7 أيام عمل</p>
                        <ul>
                            <li>جميع المدن والمناطق الأخرى</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- مناطق التوصيل -->
            <div class="shipping-card card">
                <div class="card-icon">🗺️</div>
                <h2>مناطق التوصيل</h2>
                <p>نوفر خدمة التوصيل لجميع مناطق المملكة العربية السعودية</p>
                <div class="delivery-areas">
                    <div class="area-group">
                        <h4>المنطقة الوسطى</h4>
                        <p>الرياض، القصيم، حائل، الجوف</p>
                    </div>
                    <div class="area-group">
                        <h4>المنطقة الغربية</h4>
                        <p>مكة المكرمة، المدينة المنورة، جدة، الطائف</p>
                    </div>
                    <div class="area-group">
                        <h4>المنطقة الشرقية</h4>
                        <p>الدمام، الخبر، الظهران، الأحساء</p>
                    </div>
                    <div class="area-group">
                        <h4>المنطقة الجنوبية</h4>
                        <p>أبها، جازان، نجران، الباحة</p>
                    </div>
                </div>
            </div>
            
            <!-- معلومات إضافية -->
            <div class="shipping-card card">
                <div class="card-icon">ℹ️</div>
                <h2>معلومات مهمة</h2>
                <div class="important-info">
                    <div class="info-item">
                        <h4>أوقات التوصيل</h4>
                        <p>من الأحد إلى الخميس: 8:00 ص - 10:00 م</p>
                        <p>الجمعة والسبت: 10:00 ص - 8:00 م</p>
                    </div>
                    <div class="info-item">
                        <h4>تتبع الطلب</h4>
                        <p>يمكنك تتبع طلبك من خلال رقم الطلب أو من خلال حسابك الشخصي</p>
                    </div>
                    <div class="info-item">
                        <h4>التواصل</h4>
                        <p>في حالة وجود أي استفسار حول الشحن، يمكنك التواصل معنا على الرقم: +966 50 123 4567</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة سياسة الشحن */
.shipping-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.shipping-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.shipping-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.shipping-card {
    padding: 2rem;
    text-align: center;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.shipping-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.shipping-card h2 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

/* تكلفة الشحن */
.shipping-costs {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.cost-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.cost-item.free {
    background: #e8f5e8;
    color: #2e7d32;
}

.cost-label {
    font-weight: 600;
}

.cost-value {
    font-weight: 700;
    font-size: 1.1rem;
}

/* وقت التوصيل */
.delivery-times {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.time-item {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
}

.time-item h4 {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.time-item p {
    color: #00897b;
    font-weight: 700;
    margin-bottom: 1rem;
}

.time-item ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.time-item li {
    color: #666;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

/* مناطق التوصيل */
.delivery-areas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.area-group {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.area-group h4 {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.area-group p {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

/* معلومات مهمة */
.important-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-item {
    text-align: right;
}

.info-item h4 {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.info-item p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .shipping-content {
        grid-template-columns: 1fr;
    }
    
    .delivery-times {
        grid-template-columns: 1fr;
    }
    
    .delivery-areas {
        grid-template-columns: 1fr;
    }
    
    .cost-item {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}
</style> 