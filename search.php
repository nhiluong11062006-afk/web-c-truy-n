<?php 
include 'connect.php'; 
$keyword = $_GET['keyword'];
$sql = "SELECT * FROM stories WHERE title LIKE '%$keyword%'";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">  
    <title>Kết quả tìm kiếm: <?php echo $keyword; ?></title>
    <style>
        .container { width: 80%; margin: 20px auto; font-family: Arial; }
        .list-truyen { display: flex; flex-wrap: wrap; gap: 20px; }
        .item { width: 180px; text-align: center; }
        .item img { width: 100%; height: 250px; object-fit: cover; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Kết quả tìm kiếm cho: "<?php echo $keyword; ?>"</h1>
        <div class="list-truyen">
            <?php while($row = mysqli_fetch_assoc($res)) { ?>
                <div class="item">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="">
                    <p><strong><?php echo $row['title']; ?></strong></p>
                    <a href="detail.php?id=<?php echo $row['stories_id']; ?>">Xem chi tiết</a>
                </div>
            <?php } ?>
        </div>
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