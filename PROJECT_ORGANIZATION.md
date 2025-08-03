# 📁 دليل ترتيب المشروع - متجر البلوطي الإلكتروني

## 🎯 هدف الترتيب
تنظيم ملفات المشروع بشكل احترافي ومهني لسهولة الصيانة والتطوير.

---

## 📋 خطة الترتيب

### 1. الملفات الأساسية (Root Directory)
```
albaluwti_backup/
├── index.php                    # الصفحة الرئيسية
├── .htaccess                    # إعدادات Apache
├── robots.txt                   # إعدادات محركات البحث
├── sitemap.xml                  # خريطة الموقع
├── README.md                    # دليل المستخدم الرئيسي
├── LICENSE                      # رخصة المشروع
├── .gitignore                   # ملفات Git المحظورة
└── CHANGELOG.md                 # سجل التحديثات
```

### 2. مجلدات المشروع الأساسية
```
albaluwti_backup/
├── includes/                    # ملفات التضمين
│   ├── header.php              # رأس الصفحة
│   └── footer.php              # تذييل الصفحة
├── php/                        # ملفات PHP الأساسية
│   ├── db.php                  # اتصال قاعدة البيانات
│   └── functions.php           # الدوال المساعدة
├── pages/                      # صفحات المتجر
│   ├── products.php            # المنتجات
│   ├── cart.php                # السلة
│   ├── checkout.php            # إتمام الطلب
│   ├── login.php               # تسجيل الدخول
│   ├── register.php            # التسجيل
│   └── ...                     # باقي الصفحات
├── admin/                      # لوحة الإدارة
│   ├── login.php               # تسجيل دخول الإدارة
│   ├── dashboard.php           # لوحة التحكم
│   ├── products.php            # إدارة المنتجات
│   └── ...                     # باقي صفحات الإدارة
├── assets/                     # الأصول الثابتة
│   ├── css/                    # ملفات التصميم
│   ├── js/                     # ملفات JavaScript
│   ├── images/                 # الصور
│   └── manifest.json           # ملف PWA
└── db/                         # ملفات قاعدة البيانات
    ├── database.sql            # هيكل قاعدة البيانات
    └── payment_tables.sql      # جداول الدفع
```

### 3. مجلدات النظام
```
albaluwti_backup/
├── uploads/                    # الملفات المرفوعة
│   ├── products/               # صور المنتجات
│   └── avatars/                # صور المستخدمين
├── cache/                      # التخزين المؤقت
├── logs/                       # سجلات النظام
└── temp/                       # الملفات المؤقتة
```

### 4. مجلد التوثيق
```
albaluwti_backup/
└── docs/                       # التوثيق
    ├── README_COMPLETE.md      # دليل شامل
    ├── INSTALL.md              # دليل التثبيت
    ├── CONTRIBUTING.md         # دليل المساهمة
    ├── PAYMENT_SETUP.md        # إعداد الدفع
    └── PROJECT_COMPLETE_CHECKLIST.md  # قائمة التحقق
```

---

## 🗂️ الملفات المطلوب نقلها

### نقل إلى مجلد temp_files/
- `test_all_pages.php`
- `test_path_fix.php`
- `fix_all_pages_paths.php`
- `test_pages_fix.php`
- `fix_admin_pages.php`
- `test_admin_complete.html`
- `complete_optimization.php`
- `detailed_enhancements.php`
- `final_test.php`
- `create_admin.php`
- `settings_guide.html`

### نقل إلى مجلد docs/
- `README_COMPLETE.md`
- `README_FINAL.md`
- `README_FIXES.md`
- `README_DATABASE.md`
- `INSTALL.md`
- `CONTRIBUTING.md`
- `PAYMENT_SETUP.md`
- `ADMIN_COMPLETE_GUIDE.md`
- `ADMIN_SETTINGS_GUIDE.md`
- `PROJECT_COMPLETE_CHECKLIST.md`
- `FINAL_COMPLETE_SUMMARY.md`
- `FINAL_DETAILED_SUMMARY.md`
- `FINAL_PATH_FIX_SUMMARY.md`
- `FINAL_SUMMARY.md`
- `ADMIN_COMPLETE_FINAL_SUMMARY.md`
- `ADMIN_UPDATE_SUMMARY.md`
- `SETTINGS_UPDATE_SUMMARY.md`
- `IMAGE_FIXES_SUMMARY.md`
- `CONNECTION_ERROR_FIX.md`
- `SOLUTION.md`
- `SOLUTION_COMPLETE.md`
- `QUICK_START.md`
- `SUMMARY.md`
- `CHANGELOG.md`

### نقل إلى مجلد db/
- `albaluwti_database.sql`

### حذف (متكررة أو غير ضرورية)
- `css/` (مدمج في assets/css/)
- `fix/` (مجلد فارغ)

---

## 🚀 خطوات التنفيذ

### 1. إنشاء المجلدات المطلوبة
```bash
mkdir docs
mkdir temp_files
```

### 2. نقل الملفات المؤقتة
```bash
# نقل ملفات الاختبار
mv test_*.php temp_files/
mv fix_*.php temp_files/
mv *_test.* temp_files/
mv complete_*.php temp_files/
mv detailed_*.php temp_files/
mv create_admin.php temp_files/
mv settings_guide.html temp_files/
```

### 3. نقل ملفات التوثيق
```bash
# نقل ملفات التوثيق
mv README_*.md docs/
mv *_SUMMARY.md docs/
mv *_GUIDE.md docs/
mv INSTALL.md docs/
mv CONTRIBUTING.md docs/
mv PAYMENT_SETUP.md docs/
mv SOLUTION*.md docs/
mv QUICK_START.md docs/
mv SUMMARY.md docs/
mv CHANGELOG.md docs/
mv CONNECTION_ERROR_FIX.md docs/
mv IMAGE_FIXES_SUMMARY.md docs/
```

### 4. نقل ملفات قاعدة البيانات
```bash
mv albaluwti_database.sql db/
```

### 5. حذف المجلدات غير الضرورية
```bash
rm -rf css/
rm -rf fix/
```

---

## ✅ النتيجة النهائية

### هيكل المشروع النظيف:
```
albaluwti_backup/
├── index.php                    # الصفحة الرئيسية
├── .htaccess                    # إعدادات Apache
├── robots.txt                   # إعدادات محركات البحث
├── sitemap.xml                  # خريطة الموقع
├── README.md                    # دليل المستخدم الرئيسي
├── LICENSE                      # رخصة المشروع
├── .gitignore                   # ملفات Git المحظورة
├── admin_complete_fix.php       # رابط الإدارة المحسن
├── includes/                    # ملفات التضمين
├── php/                         # ملفات PHP الأساسية
├── pages/                       # صفحات المتجر
├── admin/                       # لوحة الإدارة
├── assets/                      # الأصول الثابتة
├── db/                          # ملفات قاعدة البيانات
├── uploads/                     # الملفات المرفوعة
├── cache/                       # التخزين المؤقت
├── logs/                        # سجلات النظام
├── temp/                        # الملفات المؤقتة
├── docs/                        # التوثيق
└── temp_files/                  # الملفات المؤقتة والاختبارية
```

---

## 🎯 فوائد الترتيب

### 1. سهولة الصيانة
- هيكل واضح ومنظم
- فصل الملفات حسب الوظيفة
- سهولة العثور على الملفات

### 2. الأمان
- حماية الملفات الحساسة
- فصل ملفات النظام عن ملفات التطبيق
- تنظيم صلاحيات الوصول

### 3. الأداء
- تحسين تحميل الملفات
- تقليل حجم المجلد الرئيسي
- تحسين التخزين المؤقت

### 4. التطوير
- سهولة إضافة ميزات جديدة
- تنظيم الكود بشكل احترافي
- سهولة التعاون بين المطورين

---

## 📞 معلومات إضافية

### 🔧 الدعم التقني
- **البريد الإلكتروني:** info@albaluwti.com
- **الهاتف:** +962 79 7755888
- **الموقع:** www.albaluwti.com

### 📋 ملاحظات مهمة
- تأكد من تحديث الروابط بعد النقل
- اختبر جميع الوظائف بعد الترتيب
- احتفظ بنسخة احتياطية قبل الترتيب
- وثق أي تغييرات في الروابط

---

**تم إنشاء هذا الدليل لضمان ترتيب احترافي للمشروع**

**آخر تحديث: ديسمبر 2024** 