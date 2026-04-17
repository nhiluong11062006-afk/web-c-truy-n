<?php
session_start();
include '../connect.php';

// Kiểm tra quyền admin (role = 1)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Quản trị</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="sidebar">
        <h2>ADMIN CP</h2>
        <ul>
            <li><a href="index.php?module=category" style="color:white">Quản trị Loại truyện</a></li>
            <li><a href="index.php?module=story" style="color:white">Quản trị Truyện</a></li>
            <li><a href="index.php?module=user" style="color:white">Quản trị Người dùng</a></li>
            <li><a href="../index.php" style="color:orange">Xem Website</a></li>
            <li><a href="logout.php" style="color:red">Đăng xuất</a></li>
        </ul>
    </div>

    <div class="content">
        <?php
        // Kỹ thuật điều hướng Module
        $module = isset($_GET['module']) ? $_GET['module'] : '';

        if ($module == 'category') {
            include 'modules/category.php';
        } elseif ($module == 'story') {
            include 'modules/story.php';
        } elseif ($module == 'user') {
            include 'modules/user.php';
        } else {
            echo "<h1>Chào mừng Admin " . $_SESSION['full_name'] . "</h1>";
        }
        ?>
    </div>
</body>
</html>