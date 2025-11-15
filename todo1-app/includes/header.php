<?php
// BẮT BUỘC khởi động session ở đầu tệp
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ứng dụng Quản lý Công việc - <?php echo $page_title ?? 'Trang chủ'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .card {
            border-radius: .75rem;
            box-shadow: 0 4px 10px rgba(0,0,0,.05);
        }
        .form-control, .form-select {
            border-radius: .5rem;
        }
        .btn {
            border-radius: .5rem;
        }
        .list-group-item {
            border-radius: .5rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e9ecef;
        }
        .list-group-item:last-child {
            margin-bottom: 0;
        }
        .status-badge {
            min-width: 80px;
            text-align: center;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .main-content {
            flex: 1;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        footer {
            margin-top: auto; /* Đẩy footer xuống cuối trang */
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-check-double me-2"></i>To-Do List</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            Xin chào, <strong class="text-info"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Đăng ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container main-content">