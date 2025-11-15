<?php
require_once 'config/database.php';
$page_title = 'Đăng nhập'; // Đặt tiêu đề cho trang
require_once 'includes/header.php'; // Đã có session_start() bên trong

$error = '';

// Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập cả tên đăng nhập và mật khẩu.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="card-title text-center mb-4"><i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger fade show" role="alert"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success fade show" role="alert">Đăng ký thành công! Vui lòng đăng nhập.</div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</button>
                    </div>
                    <p class="text-center mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký ngay!</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>