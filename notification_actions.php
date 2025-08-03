<?php
session_start();
require_once '../php/db.php';
require_once '../php/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'يجب تسجيل الدخول أولاً']);
    exit();
}

// التحقق من نوع الطلب
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'mark_read':
        $notificationId = (int)($_POST['notification_id'] ?? 0);
        if ($notificationId > 0) {
            $result = markNotificationAsRead($notificationId, $_SESSION['user_id']);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'تم تحديد الإشعار كمقروء']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء التحديث']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'معرف الإشعار غير صحيح']);
        }
        break;
        
    case 'mark_all_read':
        $db = getDB();
        if ($db) {
            try {
                $stmt = $db->prepare('
                    UPDATE notifications 
                    SET is_read = 1, read_at = NOW() 
                    WHERE user_id = ? AND is_read = 0
                ');
                $result = $stmt->execute([$_SESSION['user_id']]);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'تم تحديد جميع الإشعارات كمقروءة']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء التحديث']);
                }
            } catch (Exception $e) {
                error_log('خطأ في تحديث الإشعارات: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
        }
        break;
        
    case 'delete':
        $notificationId = (int)($_POST['notification_id'] ?? 0);
        if ($notificationId > 0) {
            $db = getDB();
            if ($db) {
                try {
                    $stmt = $db->prepare('DELETE FROM notifications WHERE id = ? AND user_id = ?');
                    $result = $stmt->execute([$notificationId, $_SESSION['user_id']]);
                    if ($result) {
                        echo json_encode(['success' => true, 'message' => 'تم حذف الإشعار']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
                    }
                } catch (Exception $e) {
                    error_log('خطأ في حذف الإشعار: ' . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'معرف الإشعار غير صحيح']);
        }
        break;
        
    case 'clear_all':
        $db = getDB();
        if ($db) {
            try {
                $stmt = $db->prepare('DELETE FROM notifications WHERE user_id = ?');
                $result = $stmt->execute([$_SESSION['user_id']]);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'تم مسح جميع الإشعارات']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء المسح']);
                }
            } catch (Exception $e) {
                error_log('خطأ في مسح الإشعارات: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
        }
        break;
        
    case 'get_count':
        $unreadCount = getUnreadNotificationsCount($_SESSION['user_id']);
        echo json_encode(['success' => true, 'count' => $unreadCount]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'إجراء غير معروف']);
        break;
}
?> 