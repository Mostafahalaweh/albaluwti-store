<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';

$page_title = 'من نحن - متجر البلوطي';
$active = 'about';

include 'includes/header.php';
?>

<!-- إضافة ملف CSS الحديث -->
<link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/modern-design.css">

<main class="main-content">
    <!-- رأس الصفحة -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">مرحباً بكم في متجر البلوطي</h1>
                <p class="hero-subtitle">نحن نقدم لكم أفضل المنتجات بجودة عالية وأسعار منافسة</p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">عميل سعيد</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">منتج متنوع</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">دعم متواصل</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قصة الشركة -->
    <section class="story-section">
        <div class="container">
            <div class="grid grid-2 align-center">
                <div class="story-content">
                    <h2 class="section-title">قصتنا</h2>
                    <p class="section-text">
                        بدأت رحلتنا في عام 2020 بهدف تقديم تجربة تسوق مميزة لعملائنا الكرام. 
                        نحن نؤمن بأن الجودة والأمانة هما أساس النجاح في عالم التجارة الإلكترونية.
                    </p>
                    <p class="section-text">
                        اليوم، نحن فخورون بأن نكون وجهة التسوق المفضلة للعديد من العائلات، 
                        حيث نقدم تشكيلة واسعة من المنتجات عالية الجودة بأسعار منافسة.
                    </p>
                    <div class="story-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>جودة عالية مضمونة</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>أسعار منافسة</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>خدمة عملاء متميزة</span>
                        </div>
                    </div>
                </div>
                <div class="story-image">
                    <div class="image-placeholder">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- رؤيتنا ورسالتنا -->
    <section class="vision-mission-section">
        <div class="container">
            <div class="grid grid-2">
                <div class="vision-card card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="card-title">رؤيتنا</h3>
                    </div>
                    <div class="card-body">
                        <p>نسعى لأن نكون الخيار الأول للتسوق الإلكتروني في المنطقة، 
                        من خلال تقديم منتجات عالية الجودة وخدمة عملاء متميزة.</p>
                    </div>
                </div>

                <div class="mission-card card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="card-title">رسالتنا</h3>
                    </div>
                    <div class="card-body">
                        <p>تقديم تجربة تسوق سلسة وممتعة لعملائنا، مع ضمان الجودة 
                        والموثوقية في كل منتج نقدمه.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- قيمنا -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title text-center">قيمنا</h2>
            <div class="grid grid-4">
                <div class="value-card card text-center">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>الجودة</h4>
                    <p>نحرص على تقديم أفضل المنتجات بجودة عالية</p>
                </div>

                <div class="value-card card text-center">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>الأمانة</h4>
                    <p>نلتزم بالشفافية والأمانة في جميع تعاملاتنا</p>
                </div>

                <div class="value-card card text-center">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>خدمة العملاء</h4>
                    <p>نضع رضا عملائنا في المقام الأول</p>
                </div>

                <div class="value-card card text-center">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4>الابتكار</h4>
                    <p>نطور خدماتنا باستمرار لتلبية احتياجاتكم</p>
                </div>
            </div>
        </div>
    </section>

    <!-- فريق العمل -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title text-center">فريق العمل</h2>
            <p class="section-subtitle text-center">تعرف على الفريق المتميز الذي يعمل خلف الكواليس</p>
            
            <div class="grid grid-3">
                <div class="team-card card text-center">
                    <div class="team-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>أحمد محمد</h4>
                    <p class="team-role">المدير التنفيذي</p>
                    <p class="team-description">يقود الفريق نحو التميز والنجاح</p>
                </div>

                <div class="team-card card text-center">
                    <div class="team-avatar">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h4>فاطمة علي</h4>
                    <p class="team-role">مديرة العمليات</p>
                    <p class="team-description">تضمن سير العمليات بسلاسة وكفاءة</p>
                </div>

                <div class="team-card card text-center">
                    <div class="team-avatar">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>محمد حسن</h4>
                    <p class="team-role">مدير خدمة العملاء</p>
                    <p class="team-description">يضمن رضا العملاء وتجربة مميزة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- الإنجازات -->
    <section class="achievements-section">
        <div class="container">
            <h2 class="section-title text-center">إنجازاتنا</h2>
            <div class="achievements-grid">
                <div class="achievement-item">
                    <div class="achievement-number">2020</div>
                    <div class="achievement-title">تأسيس الشركة</div>
                    <div class="achievement-description">بداية رحلة النجاح</div>
                </div>

                <div class="achievement-item">
                    <div class="achievement-number">2021</div>
                    <div class="achievement-title">1000 عميل</div>
                    <div class="achievement-description">وصول عدد العملاء لألف</div>
                </div>

                <div class="achievement-item">
                    <div class="achievement-number">2022</div>
                    <div class="achievement-title">توسع الخدمات</div>
                    <div class="achievement-description">إضافة خدمات جديدة</div>
                </div>

                <div class="achievement-item">
                    <div class="achievement-number">2023</div>
                    <div class="achievement-title">التميز</div>
                    <div class="achievement-description">حصولنا على جوائز التميز</div>
                </div>
            </div>
        </div>
    </section>

    <!-- دعوة للعمل -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content text-center">
                <h2>انضم إلينا اليوم</h2>
                <p>استمتع بتجربة تسوق مميزة مع أفضل المنتجات وأجودها</p>
                <div class="cta-buttons">
                    <a href="products.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag"></i>
                        تصفح المنتجات
                    </a>
                    <a href="contact.php" class="btn btn-outline btn-lg">
                        <i class="fas fa-envelope"></i>
                        اتصل بنا
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- CSS إضافي للصفحة -->
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: var(--spacing-xxl) 0;
    text-align: center;
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
    color: white;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: var(--spacing-xl);
    opacity: 0.9;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: var(--spacing-sm);
}

.stat-label {
    font-size: 1rem;
    opacity: 0.8;
}

/* Story Section */
.story-section {
    padding: var(--spacing-xxl) 0;
    background: var(--bg-light);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-lg);
    color: var(--text-primary);
}

.section-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-lg);
}

.story-features {
    margin-top: var(--spacing-lg);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    font-size: 1.1rem;
    color: var(--text-primary);
}

.feature-item i {
    color: var(--success-color);
    font-size: 1.25rem;
}

.story-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-placeholder {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

/* Vision Mission Section */
.vision-mission-section {
    padding: var(--spacing-xxl) 0;
    background: var(--bg-primary);
}

.card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: var(--spacing-md);
}

/* Values Section */
.values-section {
    padding: var(--spacing-xxl) 0;
    background: var(--bg-light);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto var(--spacing-lg);
}

.value-card h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-md);
    color: var(--text-primary);
}

.value-card p {
    color: var(--text-secondary);
    line-height: 1.6;
}

/* Team Section */
.team-section {
    padding: var(--spacing-xxl) 0;
    background: var(--bg-primary);
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin-bottom: var(--spacing-xl);
}

.team-avatar {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    margin: 0 auto var(--spacing-lg);
}

.team-card h4 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.team-role {
    color: var(--primary-color);
    font-weight: 500;
    margin-bottom: var(--spacing-md);
}

.team-description {
    color: var(--text-secondary);
    line-height: 1.6;
}

/* Achievements Section */
.achievements-section {
    padding: var(--spacing-xxl) 0;
    background: var(--bg-light);
}

.achievements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.achievement-item {
    text-align: center;
    padding: var(--spacing-lg);
    background: var(--bg-primary);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-light);
    transition: all var(--transition-normal);
}

.achievement-item:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-medium);
}

.achievement-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: var(--spacing-sm);
}

.achievement-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: var(--spacing-sm);
    color: var(--text-primary);
}

.achievement-description {
    color: var(--text-secondary);
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, var(--secondary-color), var(--secondary-dark));
    color: white;
    padding: var(--spacing-xxl) 0;
    text-align: center;
}

.cta-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-md);
    color: white;
}

.cta-content p {
    font-size: 1.25rem;
    margin-bottom: var(--spacing-xl);
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: var(--spacing-lg);
    flex-wrap: wrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: var(--spacing-lg);
    }
    
    .grid-2,
    .grid-3,
    .grid-4 {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .achievements-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero-section {
        padding: var(--spacing-xl) 0;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .image-placeholder {
        width: 200px;
        height: 200px;
        font-size: 3rem;
    }
}

/* Dark Mode Adjustments */
body.dark-mode .story-section,
body.dark-mode .values-section,
body.dark-mode .achievements-section {
    background: var(--bg-secondary);
}

body.dark-mode .achievement-item {
    background: var(--bg-primary);
}

body.dark-mode .team-section {
    background: var(--bg-secondary);
}
</style>

<?php include 'includes/footer.php'; ?> 