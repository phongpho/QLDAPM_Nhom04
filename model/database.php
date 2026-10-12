<?php
/**
 * GYMSHOP - Kết nối cơ sở dữ liệu MySQL bằng PDO.
 * Cấu hình mặc định cho XAMPP (root / không mật khẩu).
 * Import gymshop.sql vào phpMyAdmin trước khi chạy.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'gymshop');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $conn = new PDO($dsn, DB_USER, DB_PASS, array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ));
} catch (PDOException $e) {
    http_response_code(500);
    exit('Không thể kết nối CSDL. Kiểm tra model/database.php và import gymshop.sql. Chi tiết: ' . $e->getMessage());
}
