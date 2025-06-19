<!-- cấu hình -->
<?php
    $server = "localhost";
    $username = "root";
    $passwordroot = "";
    $database = "laptop_store";

    // Kết nối đến MySQL với PHP sử dụng MySQLi
    $conn = new mysqli($server, $username, $passwordroot, $database, 3307);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Thiết lập bộ ký tự cho kết nối
    $conn->set_charset("utf8mb4");
?>