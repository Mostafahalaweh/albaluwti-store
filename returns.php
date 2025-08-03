<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'سياسة الإرجاع - متجر البلوطي';
$active = 'returns';

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم سياسة الإرجاع -->
<section class="returns-section">
    <div class="container">
        <div class="returns-header">
            <h1 class="section-title">سياسة الإرجاع</h1>
            <p class="section-description">تعرف على شروط وإجراءات إرجاع المنتجات</p>
        </div>
        
        <div class="returns-content">
            <!-- شروط الإرجاع -->
            <div class="returns-card card">
                <div class="card-icon">📋</div>
                <h2>شروط الإرجاع</h2>
                <div class="return-conditions">
                    <div class="condition-item">
                        <i class="fas fa-check"></i>
                        <span>يمكن إرجاع المنتج خلال 14 يوم من تاريخ الاستلام</span>
                    </div>
                    <div class="condition-item">
                        <i class="fas fa-check"></i>
                        <span>يجب أن يكون المنتج بحالته الأصلية وغير مستخدم</span>
                    </div>
                    <div class="condition-item">
                        <i class="fas fa-check"></i>
                        <span>يجب الاحتفاظ بالعبوة الأصلية والفاتورة</span>
                    </div>
                    <div class="condition-item">
                        <i class="fas fa-check"></i>
                        <span>لا يمكن إرجاع المنتجات الاستهلاكية والمواد الغذائية</span>
                    </div>
                </div>
            </div>
            
            <!-- إجراءات الإرجاع -->
            <div class="returns-card card">
                <div class="card-icon">🔄</div>
                <h2>إجراءات الإرجاع</h2>
                <div class="return-steps">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>طلب الإرجاع</h4>
                            <p>اذهب إلى "طلباتي" واختر الطلب المطلوب إرجاعه</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>موافقة الإدارة</h4>
                            <p>سيتم مراجعة طلبك والموافقة عليه خلال 24 ساعة</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>إرسال المنتج</h4>
                            <p>سنقوم بجمع المنتج من عنوانك أو يمكنك إرساله لنا</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>استرداد المبلغ</h4>
                            <p>سيتم استرداد المبلغ خلال 3-5 أيام عمل</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- المنتجات غير القابلة للإرجاع -->
            <div class="returns-card card">
                <div class="card-icon">❌</div>
                <h2>المنتجات غير القابلة للإرجاع</h2>
                <div class="non-returnable">
                    <div class="non-returnable-item">
                        <i class="fas fa-times"></i>
                        <span>المواد الغذائية والمنتجات الاستهلاكية</span>
                    </div>
                    <div class="non-returnable-item">
                        <i class="fas fa-times"></i>
                        <span>المنتجات المخصصة حسب الطلب</span>
                    </div>
                    <div class="non-returnable-item">
                        <i class="fas fa-times"></i>
                        <span>المنتجات التالفة أو المستخدمة</span>
                    </div>
                    <div class="non-returnable-item">
                        <i class="fas fa-times"></i>
                        <span>المنتجات التي لا تحتوي على العبوة الأصلية</span>
                    </div>
                </div>
            </div>
            
            <!-- معلومات إضافية -->
            <div class="returns-card card">
                <div class="card-icon">ℹ️</div>
                <h2>معلومات مهمة</h2>
                <div class="important-info">
                    <div class="info-item">
                        <h4>تكلفة الإرجاع</h4>
                        <p>الإرجاع مجاني إذا كان المنتج معيباً أو تم إرسال منتج خاطئ</p>
                    </div>
                    <div class="info-item">
                        <h4>طريقة الاسترداد</h4>
                        <p>سيتم استرداد المبلغ بنفس طريقة الدفع المستخدمة في الطلب الأصلي</p>
                    </div>
                    <div class="info-item">
                        <h4>التواصل</h4>
                        <p>للاستفسار حول الإرجاع، اتصل بنا على: +966 50 123 4567</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة سياسة الإرجاع */
.returns-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.returns-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.returns-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.returns-card {
    padding: 2rem;
    text-align: center;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.returns-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.returns-card h2 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

/* شروط الإرجاع */
.return-conditions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.condition-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    text-align: right;
}

.condition-item i {
    color: #4caf50;
    font-size: 1.2rem;
}

/* إجراءات الإرجاع */
.return-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.step-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
}

.step-number {
    width: 40px;
    height: 40px;
    background: #00897b;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.step-content {
    text-align: right;
}

.step-content h4 {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.step-content p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

/* المنتجات غير القابلة للإرجاع */
.non-returnable {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.non-returnable-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #fff3e0;
    border-radius: 8px;
    text-align: right;
}

.non-returnable-item i {
    color: #f57c00;
    font-size: 1.2rem;
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
    margin: 0;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .returns-content {
        grid-template-columns: 1fr;
    }
    
    .condition-item,
    .step-item,
    .non-returnable-item {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
}
</style> 