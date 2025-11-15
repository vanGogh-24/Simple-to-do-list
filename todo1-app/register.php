<?php
require_once 'config/database.php';
$page_title = 'Đăng ký'; // Đặt tiêu đề cho trang
require_once 'includes/header.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate
    if (empty($username)) $errors[] = "Tên đăng nhập là bắt buộc.";
    if (empty($password)) $errors[] = "Mật khẩu là bắt buộc.";
    if (strlen($password) < 6) $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
    if ($password !== $confirm_password) $errors[] = "Mật khẩu xác nhận không khớp.";

    // Kiểm tra username hoặc email đã tồn tại chưa
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Tên đăng nhập hoặc Email đã tồn tại.";
        }
    }

    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email]);
            
            header("Location: login.php?registered=success");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="card-title text-center mb-4"><i class="fas fa-user-plus me-2"></i>Đăng Ký Tài Khoản</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Chọn tên đăng nhập" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email (Tùy chọn):</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="your@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Ít nhất 6 ký tự" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-user-plus me-2"></i>Đăng ký</button>
                    </div>
                    <p class="text-center mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập!</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>