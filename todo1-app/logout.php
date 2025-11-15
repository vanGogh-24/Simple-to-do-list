<?php
// (Tưởng tượng đây là tệp /logout.php)
session_start();

// Hủy tất cả các biến session
$_SESSION = [];

// Hủy session
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit;
?>