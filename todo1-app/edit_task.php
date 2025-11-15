<?php
// 1. Yêu cầu bảo vệ
require_once 'includes/auth_check.php';
require_once 'config/database.php';
$page_title = 'Chỉnh sửa công việc'; // Đặt tiêu đề cho trang
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? null;
$errors = [];

if (!$task_id) {
    header("Location: index.php");
    exit;
}

// === XỬ LÝ CẬP NHẬT (UPDATE) ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;
    $status = $_POST['status'];

    if (empty($title)) {
        $errors[] = "Tiêu đề là bắt buộc.";
    }

    if (empty($errors)) {
        try {
            // Bảo mật: Phải kiểm tra cả task_id VÀ user_id
            $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $description, $due_date, $status, $task_id, $user_id]);

            header("Location: index.php?status_updated=success"); // Có thể thêm thông báo thành công
            exit;
        } catch (PDOException $e) {
            $errors[] = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}

// === LẤY THÔNG TIN CÔNG VIỆC HIỆN TẠI (ĐỂ ĐIỀN VÀO FORM) ===
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$task_id, $user_id]);
$task = $stmt->fetch();

// Nếu không tìm thấy task (hoặc task không thuộc về user này)
if (!$task) {
    echo "<div class='alert alert-danger'>Không tìm thấy công việc hoặc bạn không có quyền chỉnh sửa.</div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="card-title text-center mb-4"><i class="fas fa-edit me-2"></i>Chỉnh sửa công việc</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="edit_task.php?id=<?php echo $task['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề:</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả:</label>
                        <textarea name="description" id="description" class="form-control" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Ngày hết hạn:</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="<?php echo $task['due_date']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" <?php echo ($task['status'] == 'pending') ? 'selected' : ''; ?>>Đang chờ</option>
                            <option value="in_progress" <?php echo ($task['status'] == 'in_progress') ? 'selected' : ''; ?>>Đang làm</option>
                            <option value="completed" <?php echo ($task['status'] == 'completed') ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Cập nhật</button>
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>