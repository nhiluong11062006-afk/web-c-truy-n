<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = "localhost";
$user = "root";
$pass = "";
$db   = "truyen"; 

$conn = mysqli_connect($host, $user, $pass, $db);


mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
?>