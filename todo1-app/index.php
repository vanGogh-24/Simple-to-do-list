<?php
// 1. Yêu cầu bảo vệ: Phải đăng nhập
require_once 'includes/auth_check.php';
require_once 'config/database.php';
$page_title = 'Dashboard'; // Đặt tiêu đề cho trang
require_once 'includes/header.php';

$user_id = $_SESSION['user_id'];
$errors = [];

// === XỬ LÝ THÊM CÔNG VIỆC MỚI (CREATE) ===
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;

    if (empty($title)) {
        $errors[] = "Tiêu đề công việc là bắt buộc.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $description, $due_date]);
            // Tải lại trang để tránh gửi lại form
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}

// === XỬ LÝ LẤY DANH SÁCH CÔNG VIỆC (READ) ===
// 1. Lọc (Filter)
$status_filter = $_GET['status'] ?? 'all'; // Mặc định là 'all'
// 2. Sắp xếp (Sort)
$sort_by = $_GET['sort'] ?? 'due_date_asc'; // Mặc định

// Xây dựng câu truy vấn SQL
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$user_id];

// Áp dụng Lọc
if ($status_filter != 'all') {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

// Áp dụng Sắp xếp
switch ($sort_by) {
    case 'due_date_desc':
        $sql .= " ORDER BY due_date DESC";
        break;
    case 'created_at_desc':
        $sql .= " ORDER BY created_at DESC";
        break;
    default:
        $sql .= " ORDER BY due_date ASC"; // Mặc định
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

?>

<h1 class="mb-4 text-center text-primary"><i class="fas fa-tasks me-2"></i>Quản lý công việc của bạn</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger fade show" role="alert">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm công việc mới</h5>
            </div>
            <div class="card-body">
                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề:</label>
                        <input type="text" name="title" id="title" class="form-control" required placeholder="Tên công việc của bạn">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả (tùy chọn):</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Mô tả chi tiết công việc"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Ngày hết hạn (tùy chọn):</label>
                        <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="add_task" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Lọc & Sắp xếp</h5>
            </div>
            <div class="card-body">
                <form action="index.php" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label for="status" class="form-label visually-hidden">Lọc theo trạng thái:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="all" <?php echo ($status_filter == 'all') ? 'selected' : ''; ?>>Tất cả trạng thái</option>
                            <option value="pending" <?php echo ($status_filter == 'pending') ? 'selected' : ''; ?>>Đang chờ</option>
                            <option value="in_progress" <?php echo ($status_filter == 'in_progress') ? 'selected' : ''; ?>>Đang làm</option>
                            <option value="completed" <?php echo ($status_filter == 'completed') ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="sort" class="form-label visually-hidden">Sắp xếp theo:</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="due_date_asc" <?php echo ($sort_by == 'due_date_asc') ? 'selected' : ''; ?>>Ngày hết hạn (Tăng dần)</option>
                            <option value="due_date_desc" <?php echo ($sort_by == 'due_date_desc') ? 'selected' : ''; ?>>Ngày hết hạn (Giảm dần)</option>
                            <option value="created_at_desc" <?php echo ($sort_by == 'created_at_desc') ? 'selected' : ''; ?>>Ngày tạo (Mới nhất)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-info text-white"><i class="fas fa-search me-2"></i>Áp dụng</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Danh sách công việc của bạn</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tasks)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        Chưa có công việc nào được thêm. Hãy thêm một công việc mới!
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($tasks as $task): ?>
                            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                                <div class="mb-2 mb-md-0 me-md-3 flex-grow-1">
                                    <h6 class="mb-1 
                                        <?php echo ($task['status'] == 'completed') ? 'text-decoration-line-through text-muted' : ''; ?>">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                    </h6>
                                    <p class="text-muted small mb-1"><?php echo htmlspecialchars($task['description'] ?? 'Không có mô tả'); ?></p>
                                    <small class="text-muted">
                                        <span class="badge status-badge 
                                            <?php 
                                                if ($task['status'] == 'completed') echo 'bg-success';
                                                elseif ($task['status'] == 'in_progress') echo 'bg-warning text-dark';
                                                else echo 'bg-secondary';
                                            ?>">
                                            <?php echo ucfirst($task['status']); ?>
                                        </span>
                                        <?php if ($task['due_date']): ?>
                                            <span class="ms-2"><i class="far fa-calendar-alt me-1"></i>Hết hạn: <?php echo date('d/m/Y', strtotime($task['due_date'])); ?></span>
                                        <?php endif; ?>
                                        <span class="ms-2"><i class="far fa-clock me-1"></i>Tạo lúc: <?php echo date('H:i d/m/Y', strtotime($task['created_at'])); ?></span>
                                    </small>
                                </div>
                                <div class="d-flex align-items-center flex-shrink-0">
                                    <?php if ($task['status'] != 'completed'): ?>
                                        <a href="update_status.php?id=<?php echo $task['id']; ?>&status=completed" class="btn btn-sm btn-success me-2" title="Đánh dấu hoàn thành"><i class="fas fa-check"></i></a>
                                    <?php else: ?>
                                        <a href="update_status.php?id=<?php echo $task['id']; ?>&status=pending" class="btn btn-sm btn-secondary me-2" title="Đánh dấu chưa hoàn thành"><i class="fas fa-undo"></i></a>
                                    <?php endif; ?>
                                    <a href="edit_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-info me-2" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa công việc này?');"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>