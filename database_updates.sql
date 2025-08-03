-- ===== تحديثات قاعدة البيانات للميزات الجديدة =====

-- جدول المفضلة
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_product_unique` (`user_id`, `product_id`),
    KEY `user_id` (`user_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول نشاط المستخدم
CREATE TABLE IF NOT EXISTS `user_activities` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `type` varchar(50) NOT NULL,
    `description` text NOT NULL,
    `related_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `type` (`type`),
    CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول نقاط الولاء
CREATE TABLE IF NOT EXISTS `user_points` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `points` int(11) NOT NULL DEFAULT 0,
    `reason` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    CONSTRAINT `user_points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول معاملات الولاء
CREATE TABLE IF NOT EXISTS `loyalty_transactions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `points` int(11) NOT NULL,
    `type` enum('earned','redeemed') NOT NULL,
    `reason` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `type` (`type`),
    CONSTRAINT `loyalty_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الإشعارات
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `type` enum('order','delivery','payment','promotion','system') NOT NULL DEFAULT 'system',
    `action_url` varchar(500) DEFAULT NULL,
    `is_read` tinyint(1) NOT NULL DEFAULT 0,
    `read_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `is_read` (`is_read`),
    KEY `type` (`type`),
    CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول إعدادات المستخدم
CREATE TABLE IF NOT EXISTS `user_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
    `sms_notifications` tinyint(1) NOT NULL DEFAULT 0,
    `marketing_emails` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول إعدادات الخصوصية
CREATE TABLE IF NOT EXISTS `user_privacy` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `public_profile` tinyint(1) NOT NULL DEFAULT 0,
    `order_history` tinyint(1) NOT NULL DEFAULT 1,
    `public_reviews` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id` (`user_id`),
    CONSTRAINT `user_privacy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إضافة عمود phone للمستخدمين إذا لم يكن موجوداً
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL AFTER `email`;

-- إضافة عمود updated_at للمستخدمين إذا لم يكن موجوداً
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- إضافة عمود gallery للمنتجات إذا لم يكن موجوداً
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `gallery` text DEFAULT NULL AFTER `image`;

-- إضافة عمود featured للمنتجات إذا لم يكن موجوداً
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `featured` tinyint(1) NOT NULL DEFAULT 0 AFTER `status`;

-- إضافة عمود sale_price للمنتجات إذا لم يكن موجوداً
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `sale_price` decimal(10,2) DEFAULT NULL AFTER `price`;

-- إضافة عمود stock_quantity للمنتجات إذا لم يكن موجوداً
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `stock_quantity` int(11) DEFAULT NULL AFTER `sale_price`;

-- إضافة عمود reviewer_name للتقييمات إذا لم يكن موجوداً
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `reviewer_name` varchar(255) DEFAULT NULL AFTER `user_id`;

-- إضافة عمود title للتقييمات إذا لم يكن موجوداً
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `title` varchar(255) DEFAULT NULL AFTER `reviewer_name`;

-- إضافة عمود order_id للتقييمات إذا لم يكن موجوداً
ALTER TABLE `reviews` ADD COLUMN IF NOT EXISTS `order_id` int(11) DEFAULT NULL AFTER `product_id`;

-- إضافة فهارس لتحسين الأداء
CREATE INDEX IF NOT EXISTS `idx_products_featured` ON `products` (`featured`);
CREATE INDEX IF NOT EXISTS `idx_products_status` ON `products` (`status`);
CREATE INDEX IF NOT EXISTS `idx_products_category` ON `products` (`category_id`);
CREATE INDEX IF NOT EXISTS `idx_orders_user_status` ON `orders` (`user_id`, `status`);
CREATE INDEX IF NOT EXISTS `idx_orders_created` ON `orders` (`created_at`);
CREATE INDEX IF NOT EXISTS `idx_reviews_product` ON `reviews` (`product_id`);
CREATE INDEX IF NOT EXISTS `idx_reviews_user` ON `reviews` (`user_id`);

-- إدراج بيانات تجريبية للمفضلة (اختياري)
-- INSERT INTO `wishlist` (`user_id`, `product_id`) VALUES (1, 1), (1, 2), (1, 3);

-- إدراج بيانات تجريبية لنشاط المستخدم (اختياري)
-- INSERT INTO `user_activities` (`user_id`, `type`, `description`) VALUES 
-- (1, 'order_placed', 'تم إنشاء طلب جديد'),
-- (1, 'profile_updated', 'تم تحديث الملف الشخصي');

-- إدراج بيانات تجريبية لنقاط الولاء (اختياري)
-- INSERT INTO `user_points` (`user_id`, `points`, `reason`) VALUES (1, 100, 'تسجيل جديد');

-- إدراج بيانات تجريبية للإشعارات (اختياري)
-- INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`) VALUES 
-- (1, 'مرحباً بك!', 'شكراً لك على التسجيل في متجر البلوطي', 'system');

-- إدراج بيانات تجريبية لإعدادات المستخدم (اختياري)
-- INSERT INTO `user_settings` (`user_id`, `email_notifications`, `sms_notifications`, `marketing_emails`) 
-- VALUES (1, 1, 0, 1);

-- إدراج بيانات تجريبية لإعدادات الخصوصية (اختياري)
-- INSERT INTO `user_privacy` (`user_id`, `public_profile`, `order_history`, `public_reviews`) 
-- VALUES (1, 0, 1, 1); 