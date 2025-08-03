<?php
// products_api.php - API for products page (categories, products with filters/search/pagination)
require_once __DIR__ . '/../php/db.php';
header('Content-Type: application/json; charset=utf-8');

// Helper: Send JSON response and exit
function send_json($data) {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'categories') {
    // Fetch all categories for filter dropdown
    $conn = getMysqliConnection();
    if (!$conn) {
        send_json(['error' => 'Database connection failed']);
    }
    
    $cats = [];
    $res = $conn->query("SELECT id, name FROM categories ORDER BY name");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $cats[] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];
        }
    }
    send_json($cats);
}

if ($action === 'products' || empty($action)) {
    // Get database connection
    $conn = getMysqliConnection();
    if (!$conn) {
        send_json(['error' => 'Database connection failed']);
    }
    
    // --- Filters ---
    $where = ["p.status = 'active'"];
    $params = [];
    // Category
    if (!empty($_POST['category'])) {
        $where[] = 'p.category_id = ?';
        $params[] = $_POST['category'];
    }
    // Price min/max
    if (isset($_POST['min_price']) && is_numeric($_POST['min_price'])) {
        $where[] = 'p.price >= ?';
        $params[] = $_POST['min_price'];
    }
    if (isset($_POST['max_price']) && is_numeric($_POST['max_price'])) {
        $where[] = 'p.price <= ?';
        $params[] = $_POST['max_price'];
    }
    // Availability
    if (!empty($_POST['availability'])) {
        if ($_POST['availability'] === 'in') {
            $where[] = 'p.stock > 0';
        } elseif ($_POST['availability'] === 'out') {
            $where[] = 'p.stock <= 0';
        }
    }
    // Search
    if (!empty($_POST['search'])) {
        $where[] = '(p.name LIKE ? OR p.description LIKE ?)';
        $search = '%' . $_POST['search'] . '%';
        $params[] = $search;
        $params[] = $search;
    }
    $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // --- Pagination ---
    $per_page = 12;
    $page = max(1, intval($_POST['page'] ?? 1));
    $offset = ($page - 1) * $per_page;

    // --- Count total products ---
    $count_sql = "SELECT COUNT(*) FROM products p $where_sql";
    $stmt = $conn->prepare($count_sql);
    if ($stmt && $params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
    } else {
        $total = 0;
    }
    $pages = max(1, ceil($total / $per_page));
    if ($page > $pages) $page = $pages;
    $offset = ($page - 1) * $per_page;

    // --- Fetch products ---
    $sql = "SELECT p.id, p.name, p.price, p.image_url, p.stock, p.description, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            $where_sql
            ORDER BY p.id DESC
            LIMIT $per_page OFFSET $offset";
    $stmt = $conn->prepare($sql);
    if ($stmt && $params) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
    } else {
        $res = false;
    }

    // --- Build HTML ---
    $html = '';
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $image = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : '/albaluwti_backup/assets/images/placeholder.png';
            $name = htmlspecialchars($row['name']);
            $category = htmlspecialchars($row['category_name'] ?? 'بدون تصنيف');
            $price = number_format($row['price'], 2);
            $stock = intval($row['stock']);
            $description = htmlspecialchars($row['description'] ?? '');
            $availability = $stock > 0 ? '' : "<span class='out-of-stock'>(غير متوفر)</span>";
            
            $html .= "<div class='product-card fade-in'>
                        <div class='product-image'>
                            <img src='{$image}' alt='{$name}' onerror=\"this.src='/albaluwti_backup/assets/images/placeholder.png'\">
                            <button class='favorite-btn' data-product-id='{$row['id']}' title='إضافة إلى المفضلة'>🤍</button>
                        </div>
                        <div class='product-info'>
                            <div class='product-category'>{$category}</div>
                            <h3 class='product-name'>{$name}</h3>
                            <p class='product-description'>{$description}</p>
                            <div class='product-price'>{$price} د.أ {$availability}</div>
                            <div class='product-actions'>
                                <button class='add-to-cart-btn' data-product-id='{$row['id']}' data-product-name='{$name}'" . ($stock > 0 ? '' : ' disabled') . ">
                                    " . ($stock > 0 ? 'أضف إلى السلة' : 'غير متوفر') . "
                                </button>
                            </div>
                        </div>
                      </div>";
        }
    } else {
        $html = "<div class='empty-state'>
                    <div class='empty-icon'>🛍️</div>
                    <h3>لا توجد منتجات مطابقة</h3>
                    <p>جرب تغيير معايير البحث أو التصفية</p>
                    <button onclick='loadProducts(1)' class='btn cta-btn'>عرض كل المنتجات</button>
                  </div>";
    }
    $stmt->close();

    // --- Build Pagination ---
    $pagination = '';
    if ($pages > 1) {
        $pagination = '<div class="pagination">';
        
        // Previous button
        if ($page > 1) {
            $pagination .= "<a href='#' class='page-link' data-page='" . ($page - 1) . "'>السابق</a>";
        }
        
        // Page numbers
        $start = max(1, $page - 2);
        $end = min($pages, $page + 2);
        
        if ($start > 1) {
            $pagination .= "<a href='#' class='page-link' data-page='1'>1</a>";
            if ($start > 2) {
                $pagination .= "<span class='page-dots'>...</span>";
            }
        }
        
        for ($i = $start; $i <= $end; $i++) {
            $active = $i == $page ? ' active' : '';
            $pagination .= "<a href='#' class='page-link{$active}' data-page='{$i}'>{$i}</a>";
        }
        
        if ($end < $pages) {
            if ($end < $pages - 1) {
                $pagination .= "<span class='page-dots'>...</span>";
            }
            $pagination .= "<a href='#' class='page-link' data-page='{$pages}'>{$pages}</a>";
        }
        
        // Next button
        if ($page < $pages) {
            $pagination .= "<a href='#' class='page-link' data-page='" . ($page + 1) . "'>التالي</a>";
        }
        
        $pagination .= '</div>';
    }

    // --- Response ---
    send_json([
        'html' => $html,
        'pagination' => $pagination,
        'page' => $page,
        'pages' => $pages,
        'count' => $total
    ]);
}

// Invalid action
send_json(['error' => 'إجراء غير صالح']); 