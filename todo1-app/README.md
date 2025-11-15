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

##  Cấu trúc thư mục
##  Cài đặt và Chạy dự án

Thực hiện các bước sau để chạy dự án trên máy local của bạn.

### 1. Yêu cầu
* Một môi trường server web (ví dụ: [XAMPP](https://www.apachefriends.org/index.html) hoặc [WAMP](https://www.wampserver.com/en/)).
* Trình duyệt web.
* Trình quản lý CSDL (như phpMyAdmin, đi kèm với XAMPP/WAMP).

### 2. Cài đặt

1.  **Tải mã nguồn:**
    * Tải hoặc clone dự án này vào thư mục `htdocs` (của XAMPP) hoặc `www` (của WAMP).
    * Ví dụ: `C:/xampp/htdocs/todo-app`

2.  **Khởi động Server:**
    * Mở XAMPP/WAMP Control Panel và khởi động **Apache** và **MySQL**.

3.  **Tạo Cơ sở dữ liệu:**
    * Mở trình duyệt và truy cập `http://localhost/phpmyadmin`.
    * Tạo một CSDL mới với tên là `todo_app` (khuyến khích dùng Bảng mã `utf8mb4_unicode_ci`).
    * Chọn CSDL `todo_app` vừa tạo, nhấp vào tab **Import** (Nhập).
    * Tải lên tệp `database.sql` từ thư mục dự án và thực thi nó. Thao tác này sẽ tự động tạo bảng `users` và `tasks`.

4.  **Cấu hình Kết nối CSDL:**
    * Mở tệp `config/database.php`.
    * Chỉnh sửa các thông tin sau cho phù hợp với môi trường của bạn:
        ```php
        $host = '127.0.0.1'; // Hoặc 'localhost'
        $db   = 'todo_app';
        $user = 'root'; // User CSDL của bạn
        $pass = '';     // Mật khẩu CSDL của bạn
        ```
    * (Thông thường, cài đặt mặc định của XAMPP/WAMP là `root` và không có mật khẩu).

5.  **Chạy ứng dụng:**
    * Mở trình duyệt và truy cập: `http://localhost/todo-app/`
    * Bạn sẽ được tự động chuyển đến trang `login.php` nếu chưa đăng nhập.
    * Hãy bắt đầu bằng cách tạo một tài khoản mới trên trang `register.php`!

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