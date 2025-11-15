<?php
// (Tưởng tượng đây là tệp /includes/auth_check.php)

// header.php đã gọi session_start() rồi
// Nếu tệp này được gọi riêng, hãy đảm bảo session_start() đã chạy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu user_id không tồn tại trong session
if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit; // Dừng thực thi script ngay lập tức
}
?>