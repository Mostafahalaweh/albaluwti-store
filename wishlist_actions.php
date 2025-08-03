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
    case 'add':
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $result = addToWishlist($_SESSION['user_id'], $productId);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'تم إضافة المنتج إلى المفضلة']);
            } else {
                echo json_encode(['success' => false, 'message' => 'المنتج موجود بالفعل في المفضلة']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'معرف المنتج غير صحيح']);
        }
        break;
        
    case 'remove':
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $result = removeFromWishlist($_SESSION['user_id'], $productId);
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'تم إزالة المنتج من المفضلة']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الإزالة']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'معرف المنتج غير صحيح']);
        }
        break;
        
    case 'clear':
        $db = getDB();
        if ($db) {
            try {
                $stmt = $db->prepare('DELETE FROM wishlist WHERE user_id = ?');
                $result = $stmt->execute([$_SESSION['user_id']]);
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'تم مسح جميع المنتجات من المفضلة']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء المسح']);
                }
            } catch (Exception $e) {
                error_log('خطأ في مسح المفضلة: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'حدث خطأ في النظام']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
        }
        break;
        
    case 'check':
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $result = isInWishlist($_SESSION['user_id'], $productId);
            echo json_encode(['success' => true, 'in_wishlist' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'معرف المنتج غير صحيح']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'إجراء غير معروف']);
        break;
}
?> 