<?php
// (Tưởng tượng đây là tệp /update_status.php)
require_once 'includes/auth_check.php';
require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? null;
$new_status = $_GET['status'] ?? 'pending';

// Validate status
$allowed_statuses = ['pending', 'in_progress', 'completed'];
if (!$task_id || !in_array($new_status, $allowed_statuses)) {
    header("Location: index.php");
    exit;
}

try {
    // **Bảo mật: Luôn kiểm tra user_id khi UPDATE hoặc DELETE**
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_status, $task_id, $user_id]);
} catch (PDOException $e) {
    // Có thể ghi log lỗi ở đây
}

// Quay lại trang chủ
header("Location: index.php");
exit;
?>