<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'الشروط والأحكام - متجر البلوطي';
$active = 'terms';

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم الشروط والأحكام -->
<section class="terms-section">
    <div class="container">
        <div class="terms-header">
            <h1 class="section-title">الشروط والأحكام</h1>
            <p class="section-description">يرجى قراءة هذه الشروط بعناية قبل استخدام الموقع</p>
        </div>
        
        <div class="terms-content">
            <div class="terms-card card">
                <h2>1. القبول بالشروط</h2>
                <p>باستخدامك لهذا الموقع، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء من هذه الشروط، يرجى عدم استخدام الموقع.</p>
            </div>
            
            <div class="terms-card card">
                <h2>2. استخدام الموقع</h2>
                <p>يجب استخدام الموقع للأغراض القانونية فقط. يحظر:</p>
                <ul>
                    <li>استخدام الموقع لأي غرض غير قانوني</li>
                    <li>محاولة اختراق الموقع أو أنظمته</li>
                    <li>إرسال محتوى ضار أو مسيء</li>
                    <li>انتهاك حقوق الملكية الفكرية</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>3. الحسابات والمستخدمين</h2>
                <p>عند إنشاء حساب، يجب عليك:</p>
                <ul>
                    <li>تقديم معلومات دقيقة وكاملة</li>
                    <li>حماية كلمة المرور الخاصة بك</li>
                    <li>إخطارنا فوراً بأي استخدام غير مصرح به</li>
                    <li>أن تكون عمرك 18 عاماً أو أكثر</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>4. المنتجات والأسعار</h2>
                <p>نحتفظ بالحق في:</p>
                <ul>
                    <li>تغيير الأسعار في أي وقت</li>
                    <li>إيقاف أو تعديل المنتجات</li>
                    <li>رفض أي طلب</li>
                    <li>تحديد الكميات المتاحة</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>5. الطلبات والدفع</h2>
                <p>عند تقديم طلب:</p>
                <ul>
                    <li>يجب أن تكون جميع المعلومات دقيقة</li>
                    <li>سيتم تأكيد الطلب عبر البريد الإلكتروني</li>
                    <li>الدفع مطلوب عند الطلب</li>
                    <li>نحتفظ بالحق في رفض الطلب</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>6. الشحن والتوصيل</h2>
                <p>سياسة الشحن:</p>
                <ul>
                    <li>الشحن متاح لجميع مناطق المملكة</li>
                    <li>الشحن مجاني للطلبات 200 ريال وأكثر</li>
                    <li>وقت التوصيل من 1-7 أيام عمل</li>
                    <li>نحن غير مسؤولين عن التأخير خارج إرادتنا</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>7. الإرجاع والاسترداد</h2>
                <p>سياسة الإرجاع:</p>
                <ul>
                    <li>يمكن الإرجاع خلال 14 يوم من الاستلام</li>
                    <li>يجب أن يكون المنتج بحالته الأصلية</li>
                    <li>الإرجاع مجاني للمنتجات المعيبة</li>
                    <li>سيتم الاسترداد خلال 3-5 أيام عمل</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>8. المسؤولية القانونية</h2>
                <p>نحن غير مسؤولين عن:</p>
                <ul>
                    <li>الأضرار غير المباشرة</li>
                    <li>فقدان البيانات أو الأرباح</li>
                    <li>الأضرار الناتجة عن سوء الاستخدام</li>
                    <li>مشاكل الإنترنت أو الخدمات الخارجية</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>9. الملكية الفكرية</h2>
                <p>جميع المحتويات محمية بموجب حقوق الملكية الفكرية:</p>
                <ul>
                    <li>الشعارات والعلامات التجارية</li>
                    <li>التصميم والواجهة</li>
                    <li>المحتوى والنصوص</li>
                    <li>البرمجيات والتقنيات</li>
                </ul>
            </div>
            
            <div class="terms-card card">
                <h2>10. تعديل الشروط</h2>
                <p>نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم إخطارك بالتغييرات عبر البريد الإلكتروني أو نشرها على الموقع.</p>
            </div>
            
            <div class="terms-card card">
                <h2>11. القانون المطبق</h2>
                <p>تخضع هذه الشروط لقوانين المملكة العربية السعودية. أي نزاع سيتم حله في المحاكم السعودية.</p>
            </div>
            
            <div class="terms-card card">
                <h2>12. التواصل</h2>
                <p>للاستفسار حول هذه الشروط، يمكنك التواصل معنا:</p>
                <div class="contact-info">
                    <p><strong>البريد الإلكتروني:</strong> legal@albaluwti.com</p>
                    <p><strong>الهاتف:</strong> +966 50 123 4567</p>
                    <p><strong>العنوان:</strong> المملكة العربية السعودية</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
.terms-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.terms-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.terms-content {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.terms-card {
    padding: 2rem;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.terms-card h2 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.terms-card p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.terms-card ul {
    list-style: none;
    padding: 0;
}

.terms-card li {
    color: #666;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
    position: relative;
    padding-right: 1.5rem;
}

.terms-card li:before {
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