<?php
// (Tưởng tượng đây là tệp /config/database.php)

$host = '127.0.0.1'; // Hoặc 'localhost'
$db   = 'todo_app';
$user = 'root'; // Thay bằng user của bạn
$pass = '';     // Thay bằng mật khẩu của bạn
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>