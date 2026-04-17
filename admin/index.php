<?php
session_start();
include '../connect.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

$module = isset($_GET['module']) ? $_GET['module'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Book Web</title>
</head>
<body>
    <table width="100%" border="1">
        <tr>
            <td width="20%" valign="top">
                <h3>DANH MỤC</h3>
                <ul>
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="index.php?module=category">Quản lý Loại truyện</a></li>
                    <li><a href="index.php?module=story">Quản lý Truyện</a></li>
                    <li><a href="index.php?module=user">Quản lý User</a></li>
                    <li><a href="../logout.php">Đăng xuất</a></li>
                </ul>
            </td>
            <td valign="top">
                <?php
                switch ($module) {
                    case 'category':
                        include 'modules/category.php';
                        break;
                    case 'story':
                        include 'modules/story.php';
                        break;
                    case 'chapter':
                        include 'modules/chapter.php';
                        break;
                    case 'user':
                        include 'modules/user.php';
                        break;
                    default:
                        echo "<h1>Chào mừng Admin</h1>";
                        echo "Chọn chức năng bên trái để làm việc.";
                        break;
                }
                ?>
            </td>
        </tr>
    </table>
</body>
</html>