<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';
session_start();
$settings = getSettings();
$page_title = 'العروض الخاصة - متجر البلوطي';
$active = 'offers';
include __DIR__ . '/../includes/header.php';
?>

<div class="container offers-page fade-in slide-up">
    <section class="page-header">
        <h1 class="page-title">العروض الخاصة</h1>
        <p class="page-desc">اكتشف أحدث العروض والخصومات الحصرية</p>
    </section>

    <section class="offers-filter">
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">جميع العروض</button>
            <button class="filter-btn" data-filter="discount">خصومات</button>
            <button class="filter-btn" data-filter="new">عروض جديدة</button>
            <button class="filter-btn" data-filter="limited">عروض محدودة</button>
        </div>
    </section>

    <section class="offers-grid-section">
        <div class="offers-grid" id="offersGrid">
            <!-- عرض 1 -->
            <div class="offer-card scale-hover" data-category="discount">
                <div class="offer-badge">خصم 25%</div>
                <img src="/albaluwti_backup/assets/images/offer1.jpg" alt="عرض المشروبات" class="offer-image" onerror="this.src='/albaluwti_backup/assets/images/placeholder.png'">
                <div class="offer-content">
                    <h3>عرض المشروبات</h3>
                    <p>خصم 25% على جميع المشروبات والمرطبات</p>
                    <div class="offer-details">
                        <div class="offer-price">
                            <span class="original-price">4.00 د.أ</span>
                            <span class="discount-price">3.00 د.أ</span>
                        </div>
                        <div class="offer-timer">
                            <span class="timer-label">ينتهي خلال:</span>
                            <div class="countdown" data-end="2024-12-31">
                                <span class="days">00</span> يوم
                                <span class="hours">00</span> ساعة
                            </div>
                        </div>
                    </div>
                    <a href="/albaluwti_backup/pages/products.php?category=beverages" class="btn cta-btn">تسوق الآن</a>
                </div>
            </div>

            <!-- عرض 2 -->
            <div class="offer-card scale-hover" data-category="new">
                <div class="offer-badge new">عرض جديد</div>
                <img src="/albaluwti_backup/assets/images/offer2.jpg" alt="عرض العائلة" class="offer-image" onerror="this.src='/albaluwti_backup/assets/images/placeholder.png'">
                <div class="offer-content">
                    <h3>عرض العائلة</h3>
                    <p>خصم 15% على طلبات أكثر من 50 دينار</p>
                    <div class="offer-details">
                        <div class="offer-code">
                            <span>كود الخصم: FAMILY15</span>
                        </div>
                        <div class="offer-validity">
                            <span>صالح حتى: 31 ديسمبر 2024</span>
                        </div>
                    </div>
                    <a href="/albaluwti_backup/pages/products.php" class="btn cta-btn">تسوق الآن</a>
                </div>
            </div>

            <!-- عرض 3 -->
            <div class="offer-card scale-hover" data-category="limited">
                <div class="offer-badge limited">عرض محدود</div>
                <img src="/albaluwti_backup/assets/images/offer3.jpg" alt="عرض الخضار" class="offer-image" onerror="this.src='/albaluwti_backup/assets/images/placeholder.png'">
                <div class="offer-content">
                    <h3>عرض الخضار الطازجة</h3>
                    <p>خصم 30% على جميع الخضار والفواكه الطازجة</p>
                    <div class="offer-details">
                        <div class="offer-price">
                            <span class="original-price">6.00 د.أ</span>
                            <span class="discount-price">4.20 د.أ</span>
                        </div>
                        <div class="offer-stock">
                            <span>المتبقي: 50 كيلو</span>
                        </div>
                    </div>
                    <a href="/albaluwti_backup/pages/products.php?category=vegetables" class="btn cta-btn">تسوق الآن</a>
                </div>
            </div>

            <!-- عرض 4 -->
            <div class="offer-card scale-hover" data-category="discount">
                <div class="offer-badge">خصم 20%</div>
                <img src="/albaluwti_backup/assets/images/offer4.jpg" alt="عرض المنظفات" class="offer-image" onerror="this.src='/albaluwti_backup/assets/images/placeholder.png'">
                <div class="offer-content">
                    <h3>عرض المنظفات</h3>
                    <p>خصم 20% على جميع منتجات التنظيف</p>
                    <div class="offer-details">
                        <div class="offer-price">
                            <span class="original-price">8.00 د.أ</span>
                            <span class="discount-price">6.40 د.أ</span>
                        </div>
                        <div class="offer-timer">
                            <span class="timer-label">ينتهي خلال:</span>
                            <div class="countdown" data-end="2024-12-25">
                                <span class="days">00</span> يوم
                                <span class="hours">00</span> ساعة
                            </div>
                        </div>
                    </div>
                    <a href="/albaluwti_backup/pages/products.php?category=cleaning" class="btn cta-btn">تسوق الآن</a>
                </div>
            </div>
        </div>
    </section>

    <section class="newsletter-section">
        <div class="newsletter-content">
            <h2>اشترك في النشرة الإخبارية</h2>
            <p>احصل على أحدث العروض والخصومات مباشرة في بريدك الإلكتروني</p>
            <form class="newsletter-form">
                <input type="email" placeholder="بريدك الإلكتروني" required>
                <button type="submit" class="btn cta-btn">اشتراك</button>
            </form>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
// تصفية العروض
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // إزالة الفئة النشطة من جميع الأزرار
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        // إضافة الفئة النشطة للزر المحدد
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const offers = document.querySelectorAll('.offer-card');
        
        offers.forEach(offer => {
            if (filter === 'all' || offer.dataset.category === filter) {
                offer.style.display = 'block';
                offer.classList.add('fade-in');
            } else {
                offer.style.display = 'none';
                offer.classList.remove('fade-in');
            }
        });
    });
});

// العداد التنازلي
function updateCountdown() {
    const countdowns = document.querySelectorAll('.countdown');
    
    countdowns.forEach(countdown => {
        const endDate = new Date(countdown.dataset.end).getTime();
        const now = new Date().getTime();
        const distance = endDate - now;
        
        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            countdown.querySelector('.days').textContent = days.toString().padStart(2, '0');
            countdown.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
        } else {
            countdown.innerHTML = '<span style="color: #ff6b6b;">انتهى العرض</span>';
        }
    });
}

// تحديث العداد كل دقيقة
setInterval(updateCountdown, 60000);
updateCountdown();

// نموذج النشرة الإخبارية
document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = this.querySelector('input[type="email"]').value;
    const submitBtn = this.querySelector('button');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'جاري الاشتراك...';
    submitBtn.disabled = true;
    
    // محاكاة الاشتراك
    setTimeout(() => {
        showToast('تم الاشتراك بنجاح! ستصل إليك أحدث العروض قريباً.', 'success');
        this.reset();
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }, 2000);
});

// دالة إظهار الرسائل
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script> 