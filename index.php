<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Web Đọc Truyện Online</title>
    <style>
        /* CSS cơ bản để dàn trang */
        .container { width: 80%; margin: auto; }
        .list-truyen { display: flex; flex-wrap: wrap; gap: 20px; }
        .item-truyen { width: 200px; border: 1px solid #ddd; padding: 10px; text-align: center; }
        .item-truyen img { width: 100%; height: 250px; object-fit: cover; }
        h3 { font-size: 16px; height: 40px; overflow: hidden; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>TRUYỆN MỚI CẬP NHẬT</h1>
        <div class="list-truyen">
            <?php
            // Lấy danh sách truyện từ database
            $sql = "SELECT * FROM stories ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="item-truyen">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="Ảnh truyện">
                    <h3><?php echo $row['title']; ?></h3>
                    <p>Tác giả: <?php echo $row['author']; ?></p>
                    <a href="detail.php?id=<?php echo $row['stories_id']; ?>">Đọc ngay</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>