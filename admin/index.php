<?php
session_start();
include '../connect.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

$module = isset($_GET['module']) ? $_GET['module'] : '';

$module_names = [
    'category' => 'Quản lý Thể loại',
    'story'    => 'Quản lý Truyện',
    'chapter'  => 'Quản lý Chương',
    'user'     => 'Quản lý Thành viên',
];
$page_title = isset($module_names[$module]) ? $module_names[$module] : 'Bảng điều khiển';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> — Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-wrapper">

    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">Truyện Hay</div>
            <div class="brand-sub">Quản trị viên</div>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section">Điều hướng</div>
            <ul>
                <li><a href="index.php" class="<?php echo $module === '' ? 'active' : ''; ?>"><span class="nav-icon"></span> Trang chủ Admin</a></li>
            </ul>
            <div class="sidebar-section">Nội dung</div>
            <ul>
                <li><a href="index.php?module=story" class="<?php echo $module === 'story' ? 'active' : ''; ?>"><span class="nav-icon">📚</span> Quản lý Truyện</a></li>
                <li><a href="index.php?module=category" class="<?php echo $module === 'category' ? 'active' : ''; ?>"><span class="nav-icon">🏷️</span> Quản lý Thể loại</a></li>
                <li><a href="index.php?module=chapter" class="<?php echo $module === 'chapter' ? 'active' : ''; ?>"><span class="nav-icon">📄</span> Quản lý Chương</a></li>
            </ul>
            <div class="sidebar-section">Hệ thống</div>
            <ul>
                <li><a href="index.php?module=user" class="<?php echo $module === 'user' ? 'active' : ''; ?>"><span class="nav-icon">👥</span> Quản lý Thành viên</a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="../index.php">← Về trang chủ</a>
            <a href="../user/logout.php" style="margin-top:0.25rem;"> Đăng xuất</a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-title"><?php echo $page_title; ?></div>
            <div class="topbar-right">
                <span>👤 <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                <a href="../user/logout.php">Đăng xuất</a>
            </div>
        </header>

        <div class="admin-page">
            <?php if ($module !== ''): ?>
            <div class="admin-breadcrumb">
                <a href="index.php">Admin</a>
                <span class="sep">›</span>
                <span class="current"><?php echo $page_title; ?></span>
            </div>
            <?php endif; ?>

            <?php
            switch ($module) {
                case 'category': include 'modules/category.php'; break;
                case 'story':    include 'modules/story.php';    break;
                case 'chapter':  include 'modules/chapter.php';  break;
                case 'user':     include 'modules/user.php';     break;
                default:
            ?>
                <div class="welcome-banner">
                    <h2>Chào mừng trở lại </h2>
                    <p>Chọn một chức năng bên dưới hoặc từ menu bên trái để bắt đầu quản lý.</p>
                    <div class="quick-nav">
                        <a class="quick-nav-item" href="index.php?module=story"><span class="qn-icon"></span>Quản lý Truyện</a>
                        <a class="quick-nav-item" href="index.php?module=category"><span class="qn-icon"></span>Quản lý Thể loại</a>
                        <a class="quick-nav-item" href="index.php?module=chapter"><span class="qn-icon"></span>Quản lý Chương</a>
                        <a class="quick-nav-item" href="index.php?module=user"><span class="qn-icon"></span>Quản lý Thành viên</a>
                    </div>
                </div>
            <?php break; } ?>
        </div>
    </div>

</div>
</body>
</html>
