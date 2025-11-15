#  Ứng dụng Quản lý Công việc (Simple To-Do List)

Đây là một ứng dụng web quản lý công việc cá nhân đơn giản được xây dựng bằng **PHP thuần** và **MySQL**. Dự án này được tạo ra để thực hành các kỹ năng PHP cơ bản, làm việc với CSDL (sử dụng PDO), triển khai các tính năng xác thực người dùng và các thao tác CRUD.

Giao diện người dùng được thiết kế sạch sẽ, responsive bằng **Bootstrap 5** và **Font Awesome**.

##  Tính năng chính

* **Xác thực người dùng:**
    * Đăng ký tài khoản mới (Mật khẩu được băm an toàn).
    * Đăng nhập bằng `username` và `password` (Sử dụng `password_verify` và `Session`).
    * Đăng xuất và hủy Session.
* **Bảo vệ Trang:** Người dùng không thể truy cập các trang quản lý công việc nếu chưa đăng nhập.
* **Quản lý Công việc (CRUD):**
    * **Create:** Thêm công việc mới (với tiêu đề, mô tả, ngày hết hạn).
    * **Read:** Hiển thị danh sách tất cả công việc *chỉ thuộc về người dùng đang đăng nhập*.
    * **Update:** Chỉnh sửa thông tin công việc (tiêu đề, mô tả, ngày hết hạn, trạng thái).
    * **Delete:** Xóa công việc.
* **Tính năng Phụ:**
    * **Lọc** công việc theo trạng thái (Đang chờ, Đang làm, Hoàn thành).
    * **Sắp xếp** công việc (theo Ngày hết hạn, Ngày tạo).
* **Bảo mật:**
    * Sử dụng **Prepared Statements (PDO)** để ngăn chặn SQL Injection trong mọi truy vấn.
    * Sử dụng `password_hash()` và `password_verify()` để bảo mật mật khẩu người dùng.

##  Công nghệ sử dụng

* **Backend:** PHP 8+ (Hoạt động tốt trên 7.4+)
* **Database:** MySQL
* **Kết nối CSDL:** PDO (PHP Data Objects)
* **Frontend:** HTML5, CSS3, Bootstrap 5, Font Awesome
* **Môi trường:** XAMPP, WAMP, MAMP, hoặc LAMP.

##  Cấu trúc Cơ sở dữ liệu

### Bảng `users`
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT |
| `username` | VARCHAR(50) | NOT NULL, UNIQUE |
| `password` | VARCHAR(255) | NOT NULL (Đã băm) |
| `email` | VARCHAR(100) | UNIQUE, NULL |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

### Bảng `tasks`
| Tên cột | Kiểu dữ liệu | Ghi chú |
| :--- | :--- | :--- |
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT |
| `user_id` | INT | NOT NULL, FOREIGN KEY (users.id) |
| `title` | VARCHAR(255) | NOT NULL |
| `description` | TEXT | NULL |
| `due_date` | DATE | NULL |
| `status` | ENUM('pending', 'in_progress', 'completed') | DEFAULT 'pending' |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |

---
