<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'سياسة الخصوصية - متجر البلوطي';
$active = 'privacy';

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم سياسة الخصوصية -->
<section class="privacy-section">
    <div class="container">
        <div class="privacy-header">
            <h1 class="section-title">سياسة الخصوصية</h1>
            <p class="section-description">نلتزم بحماية خصوصية بياناتك الشخصية</p>
        </div>
        
        <div class="privacy-content">
            <div class="privacy-card card">
                <h2>جمع المعلومات</h2>
                <p>نقوم بجمع المعلومات التالية:</p>
                <ul>
                    <li>الاسم والعنوان ورقم الهاتف</li>
                    <li>عنوان البريد الإلكتروني</li>
                    <li>معلومات الدفع (لا نخزن بيانات البطاقات)</li>
                    <li>تاريخ الطلبات والمنتجات المشتراة</li>
                </ul>
            </div>
            
            <div class="privacy-card card">
                <h2>استخدام المعلومات</h2>
                <p>نستخدم معلوماتك للأغراض التالية:</p>
                <ul>
                    <li>معالجة الطلبات والتوصيل</li>
                    <li>التواصل معك حول طلباتك</li>
                    <li>تحسين خدماتنا</li>
                    <li>إرسال العروض والمنتجات الجديدة (بموافقتك)</li>
                </ul>
            </div>
            
            <div class="privacy-card card">
                <h2>حماية المعلومات</h2>
                <p>نطبق إجراءات أمان صارمة لحماية بياناتك:</p>
                <ul>
                    <li>تشفير البيانات أثناء النقل</li>
                    <li>حماية قواعد البيانات</li>
                    <li>تقليل الوصول للمعلومات الحساسة</li>
                    <li>مراجعة دورية لإجراءات الأمان</li>
                </ul>
            </div>
            
            <div class="privacy-card card">
                <h2>مشاركة المعلومات</h2>
                <p>لا نشارك معلوماتك مع أي طرف ثالث إلا في الحالات التالية:</p>
                <ul>
                    <li>شركات الشحن (للتوصيل فقط)</li>
                    <li>مزودي خدمات الدفع (للمعاملات المالية)</li>
                    <li>بموجب القانون أو أمر قضائي</li>
                </ul>
            </div>
            
            <div class="privacy-card card">
                <h2>حقوقك</h2>
                <p>لديك الحق في:</p>
                <ul>
                    <li>الوصول لبياناتك الشخصية</li>
                    <li>تصحيح أي معلومات غير دقيقة</li>
                    <li>حذف بياناتك (مع مراعاة القوانين)</li>
                    <li>إلغاء الاشتراك في النشرات الإخبارية</li>
                </ul>
            </div>
            
            <div class="privacy-card card">
                <h2>التواصل</h2>
                <p>للاستفسار حول سياسة الخصوصية، يمكنك التواصل معنا:</p>
                <div class="contact-info">
                    <p><strong>البريد الإلكتروني:</strong> privacy@albaluwti.com</p>
                    <p><strong>الهاتف:</strong> +966 50 123 4567</p>
                    <p><strong>العنوان:</strong> المملكة العربية السعودية</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
.privacy-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.privacy-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.privacy-content {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.privacy-card {
    padding: 2rem;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.privacy-card h2 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.privacy-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.privacy-card ul {
    list-style: none;
    padding: 0;
}

.privacy-card li {
    color: #666;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
    position: relative;
    padding-right: 1.5rem;
}

.privacy-card li:before {
    content: "•";
    color: #00897b;
    font-weight: bold;
    position: absolute;
    right: 0;
}

.contact-info p {
    margin-bottom: 0.5rem;
}

.contact-info strong {
    color: #333;
}
</style> 