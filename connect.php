<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = "localhost";
$user = "root";
$pass = "";
$db   = "truyen"; // Tên database bạn đã tạo

$conn = mysqli_connect($host, $user, $pass, $db);

// Dòng này cực kỳ quan trọng để hiển thị tiếng Việt không bị lỗi font
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
?>