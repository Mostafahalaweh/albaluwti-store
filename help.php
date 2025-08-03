<?php
session_start();
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'المساعدة - متجر البلوطي';
$active = 'help';

include __DIR__ . '/../includes/header.php';
?>

<!-- قسم المساعدة -->
<section class="help-section">
    <div class="container">
        <div class="help-header">
            <h1 class="section-title">مركز المساعدة</h1>
            <p class="section-description">إجابات على الأسئلة الشائعة ومساعدة في استخدام الموقع</p>
        </div>
        
        <!-- البحث في المساعدة -->
        <div class="help-search">
            <div class="search-box">
                <input type="text" id="helpSearch" placeholder="ابحث في الأسئلة الشائعة...">
                <button type="button" onclick="searchHelp()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <!-- الأسئلة الشائعة -->
        <div class="faq-section">
            <h2>الأسئلة الشائعة</h2>
            
            <div class="faq-categories">
                <button class="faq-category active" data-category="general">عام</button>
                <button class="faq-category" data-category="orders">الطلبات</button>
                <button class="faq-category" data-category="shipping">الشحن</button>
                <button class="faq-category" data-category="payment">الدفع</button>
                <button class="faq-category" data-category="returns">الإرجاع</button>
            </div>
            
            <div class="faq-content">
                <!-- الأسئلة العامة -->
                <div class="faq-group active" id="general">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كيف يمكنني التسجيل في الموقع؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>يمكنك التسجيل بسهولة من خلال النقر على "تسجيل جديد" في أعلى الصفحة، ثم ملء النموذج بالمعلومات المطلوبة.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كيف يمكنني تغيير كلمة المرور؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>يمكنك تغيير كلمة المرور من خلال الذهاب إلى "حسابي" ثم "إعدادات الحساب" وتحديد "تغيير كلمة المرور".</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>هل يمكنني التسوق بدون تسجيل؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>نعم، يمكنك تصفح المنتجات وإضافتها للسلة، لكن ستحتاج للتسجيل لإتمام عملية الشراء.</p>
                        </div>
                    </div>
                </div>
                
                <!-- أسئلة الطلبات -->
                <div class="faq-group" id="orders">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كيف يمكنني تتبع طلبي؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>يمكنك تتبع طلبك من خلال الذهاب إلى "طلباتي" في حسابك، أو استخدام رقم الطلب في صفحة "تتبع الطلب".</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>هل يمكنني إلغاء طلبي؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>نعم، يمكنك إلغاء طلبك خلال 24 ساعة من وقت الطلب، قبل بدء عملية التحضير والشحن.</p>
                        </div>
                    </div>
                </div>
                
                <!-- أسئلة الشحن -->
                <div class="faq-group" id="shipping">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كم تكلفة الشحن؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>الشحن مجاني للطلبات التي تزيد عن 200 ريال، وإلا تبلغ تكلفة الشحن 30 ريال.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كم يستغرق وقت التوصيل؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>يستغرق التوصيل من 1-3 أيام عمل في المدن الرئيسية، ومن 3-7 أيام في المناطق الأخرى.</p>
                        </div>
                    </div>
                </div>
                
                <!-- أسئلة الدفع -->
                <div class="faq-group" id="payment">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>ما هي طرق الدفع المتاحة؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>نقبل البطاقات الائتمانية (فيزا، ماستركارد)، باي بال، والدفع عند الاستلام.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>هل الدفع آمن؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>نعم، جميع المعاملات محمية بتقنيات التشفير المتقدمة، ولا نخزن بيانات البطاقات الائتمانية.</p>
                        </div>
                    </div>
                </div>
                
                <!-- أسئلة الإرجاع -->
                <div class="faq-group" id="returns">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>هل يمكنني إرجاع المنتج؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>نعم، يمكنك إرجاع المنتج خلال 14 يوم من تاريخ الاستلام، بشرط أن يكون بحالته الأصلية.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>كيف يمكنني طلب إرجاع؟</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>يمكنك طلب الإرجاع من خلال الذهاب إلى "طلباتي" ثم اختيار الطلب المطلوب إرجاعه.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- قسم التواصل -->
        <div class="contact-help-section">
            <h2>لم تجد إجابة لسؤالك؟</h2>
            <p>فريق الدعم متاح لمساعدتك على مدار الساعة</p>
            <div class="contact-methods">
                <div class="contact-method">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>اتصل بنا</h4>
                        <p>+966 50 123 4567</p>
                    </div>
                </div>
                <div class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>راسلنا</h4>
                        <p>support@albaluwti.com</p>
                    </div>
                </div>
                <div class="contact-method">
                    <i class="fab fa-whatsapp"></i>
                    <div>
                        <h4>واتساب</h4>
                        <p>+966 50 123 4567</p>
                    </div>
                </div>
            </div>
            <a href="contact.php" class="btn cta-btn">تواصل معنا</a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
/* تصميم صفحة المساعدة */
.help-section {
    padding: 3rem 0;
    margin-top: 80px;
}

.help-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-description {
    color: #666;
    font-size: 1.1rem;
    margin-top: 1rem;
}

/* البحث في المساعدة */
.help-search {
    margin-bottom: 3rem;
}

.search-box {
    display: flex;
    max-width: 600px;
    margin: 0 auto;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
}

.search-box input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: none;
    font-size: 1rem;
}

.search-box input:focus {
    outline: none;
}

.search-box button {
    background: #00897b;
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.search-box button:hover {
    background: #00695c;
}

/* الأسئلة الشائعة */
.faq-section {
    margin-bottom: 4rem;
}

.faq-section h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
    font-size: 2rem;
}

.faq-categories {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.faq-category {
    background: #f5f5f5;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}

.faq-category.active,
.faq-category:hover {
    background: #00897b;
    color: white;
}

.faq-group {
    display: none;
    max-width: 800px;
    margin: 0 auto;
}

.faq-group.active {
    display: block;
}

.faq-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    margin-bottom: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    cursor: pointer;
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question h3 {
    margin: 0;
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
}

.faq-question i {
    color: #00897b;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer {
    padding: 0 1.5rem;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.faq-item.active .faq-answer {
    padding: 0 1.5rem 1.5rem;
    max-height: 200px;
}

.faq-answer p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* قسم التواصل */
.contact-help-section {
    background: #f8f9fa;
    padding: 3rem;
    border-radius: 16px;
    text-align: center;
}

.contact-help-section h2 {
    color: #333;
    margin-bottom: 1rem;
    font-size: 2rem;
}

.contact-help-section p {
    color: #666;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}

.contact-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.contact-method i {
    font-size: 2rem;
    color: #00897b;
}

.contact-method h4 {
    color: #333;
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.contact-method p {
    color: #666;
    margin: 0;
    font-size: 1rem;
}

/* الاستجابة للشاشات الصغيرة */
@media (max-width: 768px) {
    .faq-categories {
        flex-direction: column;
        align-items: center;
    }
    
    .faq-category {
        width: 100%;
        max-width: 300px;
        text-align: center;
    }
    
    .contact-methods {
        grid-template-columns: 1fr;
    }
    
    .contact-method {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
// البحث في المساعدة
function searchHelp() {
    const searchTerm = document.getElementById('helpSearch').value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('h3').textContent.toLowerCase();
        const answer = item.querySelector('p').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
            item.classList.add('active');
        } else {
            item.style.display = 'none';
        }
    });
}

// تبديل فئات الأسئلة
document.querySelectorAll('.faq-category').forEach(category => {
    category.addEventListener('click', function() {
        // إزالة الفئة النشطة
        document.querySelectorAll('.faq-category').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.faq-group').forEach(g => g.classList.remove('active'));
        
        // تفعيل الفئة المختارة
        this.classList.add('active');
        const targetGroup = document.getElementById(this.dataset.category);
        targetGroup.classList.add('active');
    });
});

// فتح وإغلاق الأسئلة
document.querySelectorAll('.faq-question').forEach(question => {
    question.addEventListener('click', function() {
        const faqItem = this.parentElement;
        const isActive = faqItem.classList.contains('active');
        
        // إغلاق جميع الأسئلة
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // فتح السؤال المختار إذا لم يكن مفتوحاً
        if (!isActive) {
            faqItem.classList.add('active');
        }
    });
});

// البحث عند الكتابة
document.getElementById('helpSearch').addEventListener('input', function() {
    if (this.value.length > 2) {
        searchHelp();
    } else {
        // إظهار جميع الأسئلة
        document.querySelectorAll('.faq-item').forEach(item => {
            item.style.display = 'block';
        });
    }
});
</script> 