<?php 
include 'connect.php'; 

// Kiểm tra ID từ URL
if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// TĂNG LƯỢT XEM
$sql_update_view = "UPDATE stories SET view_count = view_count + 1 WHERE stories_id = $id";
mysqli_query($conn, $sql_update_view);

// Lấy thông tin truyện
$sql_truyen = "SELECT * FROM stories WHERE stories_id = $id";
$res_truyen = mysqli_query($conn, $sql_truyen);
$truyen = mysqli_fetch_assoc($res_truyen);

// Lấy danh sách chương
$sql_chuong = "SELECT * FROM chapter WHERE stories_id = $id ORDER BY chapter_number ASC";
$res_chuong = mysqli_query($conn, $sql_chuong);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $truyen['title']; ?> - Truyện Hay</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">🏠 Truyện</a> / <span><?php echo $truyen['title']; ?></span>
    </div>

    <div class="container single">
        <h1 class="story-detail-title"><?php echo $truyen['title']; ?></h1>
        
        <div class="view-info">
            <span>👤 Tác giả: <strong><?php echo $truyen['author']; ?></strong></span>
            <span>|</span>
            <span>👁️ Lượt xem: <strong><?php echo number_format($truyen['view_count']); ?></strong></span>
        </div>
        
        <div class="description-box">
            <p class="description">
                <strong>Mô tả:</strong> <br>
                <?php echo nl2br($truyen['description']); ?>
            </p>
        </div>
        
        <hr>
        
        <div class="chapter-section">
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
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-links">
            <a href="#">Giới thiệu</a>
            <a href="#">Liên hệ</a>
            <a href="#">Điều khoản</a>
            <a href="#">Bảo mật</a>
        </div>
        <div class="footer-copy">
            © 2026 Truyện Hay — Website đọc truyện online miễn phí
        </div>
    </footer>
</body>
</html>