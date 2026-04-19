<?php 
include 'connect.php'; 

$id = $_GET['id']; // Lấy ID từ thanh địa chỉ URL

// --- PHẦN MỚI: TĂNG LƯỢT XEM ---
// Cập nhật view_count trước khi lấy dữ liệu để hiển thị số mới nhất
$sql_update_view = "UPDATE stories SET view_count = view_count + 1 WHERE stories_id = $id";
mysqli_query($conn, $sql_update_view);
// ------------------------------

// 1. Lấy thông tin chi tiết của truyện
$sql_truyen = "SELECT * FROM stories WHERE stories_id = $id";
$res_truyen = mysqli_query($conn, $sql_truyen);
$truyen = mysqli_fetch_assoc($res_truyen);

// 2. Lấy danh sách các chương của truyện đó
$sql_chuong = "SELECT * FROM chapter WHERE stories_id = $id ORDER BY chapter_number ASC";
$res_chuong = mysqli_query($conn, $sql_chuong);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $truyen['title']; ?> - Truyện Hay</title>
    
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">🏠 Truyện</a> / <span><?php echo $truyen['title']; ?></span>
    </div>

    <div class="container single">
        <h1><?php echo $truyen['title']; ?></h1>
        
        <div class="view-info">
            <span>👤 Tác giả: <strong><?php echo $truyen['author']; ?></strong></span>
            <span>|</span>
            <span>👁️ Lượt xem: <strong><?php echo number_format($truyen['view_count']); ?></strong></span>
        </div>
        
        <p class="description">
            <strong>Mô tả:</strong> <br>
            <?php echo nl2br($truyen['description']); ?>
        </p>
        
        <hr>
        
        <h3>DANH SÁCH CHƯƠNG</h3>
        <ul class="chapter-list">
            <?php while($chuong = mysqli_fetch_assoc($res_chuong)) { ?>
                <li>
                    <a href="read.php?id=<?php echo $chuong['chapter_id']; ?>">
                        Chương <?php echo $chuong['chapter_number']; ?>: <?php echo $chuong['title']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        </div><!-- end main-content -->
        <?php include 'sidebar.php'; ?>
    </div>
    <footer class="site-footer">
    <div class="footer-links">
        <a href="#">Giới thiệu</a>
        <a href="#">Liên hệ</a>
        <a href="#">Thể loại</a>
        <a href="#">Truyện mới</a>
        <span class="sep">|</span>
        <a href="#">Điều khoản</a>
        <a href="#">Bảo mật</a>
        <a href="#">Trợ giúp</a>
    </div>
    <div class="footer-copy">
        © 2026 Truyện Hay — Website đọc truyện online miễn phí
    </div>
</footer>
</body>
</html>