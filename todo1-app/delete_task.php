<?php
// (Tưởng tượng đây là tệp /delete_task.php)
require_once 'includes/auth_check.php';
require_once 'config/database.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? null;

if ($task_id) {
    try {
        // **Bảo mật: Luôn kiểm tra user_id khi UPDATE hoặc DELETE**
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$task_id, $user_id]);
    } catch (PDOException $e) {
        // Có thể ghi log lỗi ở đây
    }
}

// Quay lại trang chủ
header("Location: index.php");
exit;
?>