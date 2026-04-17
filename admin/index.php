<?php
session_start();
// Chú ý: connect.php nằm ngoài thư mục admin nên dùng ../
include '../connect.php'; 

// 1. Kiểm tra quyền admin (role = 1)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../index.php");
    exit();
}

// 2. Lấy tên module từ URL (mặc định để trống)
$module = isset($_GET['module']) ? $_GET['module'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản trị</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="sidebar">
        <h2>ADMIN CP</h2>
        <ul>
            <li><a href="index.php?module=category">Quản trị Loại truyện</a></li>
            <li><a href="index.php?module=story" >Quản trị Truyện</a></li>
            <li><a href="index.php?module=user">Quản trị Người dùng</a></li>
            <hr>
            <li><a href="../index.php">Xem Website</a></li>
            <li><a href="logout.php">Đăng xuất</a></li>
        </ul>
    </div>

    <div class="content">
        <?php
        // 3. Kỹ thuật điều hướng Module chuẩn xác
        switch ($module) {
            case 'category':
                include 'modules/category.php';
                break;
            case 'story':
                include 'modules/story.php';
                break;
            case 'user':
                include 'modules/user.php';
                break;
            default:
                // Trang chào mừng khi chưa chọn module nào
                echo "<h1>Chào mừng Admin " . ($_SESSION['full_name'] ?? 'Quản trị viên') . "</h1>";
                echo "<p>Vui lòng chọn một chức năng ở thanh bên trái để bắt đầu.</p>";
                break;
        }
        ?>
    </div>
</body>
</html>