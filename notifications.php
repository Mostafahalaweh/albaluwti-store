<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$page_title = 'الإشعارات - متجر البلوطي';
$active = 'notifications';

// الحصول على الإشعارات
$notifications = getNotifications($_SESSION['user_id'], 50);

include '../includes/header.php';
?>

<div class="notifications-container">
    <div class="notifications-header">
        <h1>الإشعارات</h1>
        <div class="header-actions">
            <button class="btn btn-outline" id="markAllRead">تحديد الكل كمقروء</button>
            <button class="btn btn-danger" id="clearAll">مسح الكل</button>
        </div>
    </div>

    <?php if (empty($notifications)): ?>
        <div class="empty-notifications">
            <div class="empty-icon">🔔</div>
            <h2>لا توجد إشعارات</h2>
            <p>ستظهر هنا جميع الإشعارات المهمة</p>
        </div>
    <?php else: ?>
        <div class="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?= $notification['is_read'] ? 'read' : 'unread' ?>" 
                     data-id="<?= $notification['id'] ?>">
                    <div class="notification-icon">
                        <?php
                        $icon = match($notification['type']) {
                            'order' => '📦',
                            'delivery' => '🚚',
                            'payment' => '💳',
                            'promotion' => '🎁',
                            'system' => '⚙️',
                            default => '🔔'
                        };
                        echo $icon;
                        ?>
                    </div>
                    <div class="notification-content">
                        <h3 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h3>
                        <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                        <span class="notification-time"><?= timeAgo($notification['created_at']) ?></span>
                    </div>
                    <div class="notification-actions">
                        <?php if (!$notification['is_read']): ?>
                            <button class="btn btn-sm mark-read" data-id="<?= $notification['id'] ?>">
                                تحديد كمقروء
                            </button>
                        <?php endif; ?>
                        <?php if ($notification['action_url']): ?>
                            <a href="<?= htmlspecialchars($notification['action_url']) ?>" class="btn btn-sm">
                                عرض التفاصيل
                            </a>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-outline delete-notification" data-id="<?= $notification['id'] ?>">
                            حذف
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="notifications-summary">
            <div class="summary-stats">
                <div class="stat">
                    <span class="stat-label">إجمالي الإشعارات:</span>
                    <span class="stat-value"><?= count($notifications) ?></span>
                </div>
                <div class="stat">
                    <span class="stat-label">غير مقروءة:</span>
                    <span class="stat-value"><?= count(array_filter($notifications, fn($n) => !$n['is_read'])) ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.notifications-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.notifications-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.empty-notifications {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-notifications h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-notifications p {
    color: #666;
}

.notifications-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.notification-item {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.notification-item.unread {
    border-left-color: #667eea;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.notification-icon {
    font-size: 1.5rem;
    min-width: 40px;
    text-align: center;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin: 0 0 0.5rem 0;
}

.notification-message {
    color: #666;
    font-size: 0.9rem;
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.notification-time {
    font-size: 0.8rem;
    color: #999;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 120px;
}

.notification-actions .btn {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.notifications-summary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
}

.summary-stats {
    display: flex;
    justify-content: space-around;
    gap: 2rem;
}

.stat {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
}

@media (max-width: 768px) {
    .notifications-header {
        flex-direction: column;
        text-align: center;
    }
    
    .notification-item {
        flex-direction: column;
        text-align: center;
    }
    
    .notification-actions {
        flex-direction: row;
        justify-content: center;
        min-width: auto;
    }
    
    .summary-stats {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<script>
// تحديد كمقروء
document.querySelectorAll('.mark-read').forEach(button => {
    button.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        markAsRead(notificationId);
    });
});

// حذف إشعار
document.querySelectorAll('.delete-notification').forEach(button => {
    button.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        deleteNotification(notificationId);
    });
});

// تحديد الكل كمقروء
document.getElementById('markAllRead')?.addEventListener('click', function() {
    markAllAsRead();
});

// مسح الكل
document.getElementById('clearAll')?.addEventListener('click', function() {
    if (confirm('هل أنت متأكد من رغبتك في مسح جميع الإشعارات؟')) {
        clearAllNotifications();
    }
});

function markAsRead(notificationId) {
    fetch('../pages/notification_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=mark_read&notification_id=${notificationId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            if (item) {
                item.classList.remove('unread');
                item.classList.add('read');
                const markReadBtn = item.querySelector('.mark-read');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
                updateNotificationCount();
            }
        } else {
            showNotification(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function deleteNotification(notificationId) {
    fetch('../pages/notification_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=delete&notification_id=${notificationId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-id="${notificationId}"]`);
            if (item) {
                item.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    item.remove();
                    updateNotificationCount();
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        } else {
            showNotification(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function markAllAsRead() {
    fetch('../pages/notification_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=mark_all_read'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.classList.add('read');
                const markReadBtn = item.querySelector('.mark-read');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            });
            updateNotificationCount();
            showNotification('تم تحديد جميع الإشعارات كمقروءة', 'success');
        } else {
            showNotification(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function clearAllNotifications() {
    fetch('../pages/notification_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=clear_all'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('تم مسح جميع الإشعارات', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'حدث خطأ', 'error');
        }
    })
    .catch(error => {
        showNotification('حدث خطأ في الاتصال', 'error');
    });
}

function updateNotificationCount() {
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    const totalCount = document.querySelectorAll('.notification-item').length;
    
    // تحديث الإحصائيات
    const stats = document.querySelectorAll('.stat-value');
    if (stats.length >= 2) {
        stats[0].textContent = totalCount;
        stats[1].textContent = unreadCount;
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// إضافة CSS للانيميشن
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.8); }
    }
`;
document.head.appendChild(style);
</script>

<?php include '../includes/footer.php'; ?> 