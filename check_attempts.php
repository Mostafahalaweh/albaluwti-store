<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../php/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

if (!isset($_POST['email']) || empty($_POST['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email is required']);
    exit();
}

$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit();
}

try {
    $pdo = getConnection();
    
    // حساب عدد المحاولات الفاشلة في آخر 15 دقيقة
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as attempts 
        FROM failed_logins 
        WHERE email = ? AND attempt_time > ?
    ");
    $stmt->execute([$email, time() - 900]); // 15 دقيقة = 900 ثانية
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $attempts = (int)$result['attempts'];
    $remaining = max(0, 5 - $attempts);
    
    echo json_encode([
        'attempts' => $attempts,
        'remaining' => $remaining,
        'blocked' => $attempts >= 5
    ]);
    
} catch (PDOException $e) {
    error_log("Check attempts error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}
?> 