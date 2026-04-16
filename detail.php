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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $truyen['title']; ?> - Truyện Hay</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        
        /* Style cho thanh Breadcrumb */
        .breadcrumb {
            background-color: #eeeeee; 
            padding: 10px 10%; 
            font-size: 14px;
            color: #777;
            border-bottom: 1px solid #ddd;
        }
        .breadcrumb a {
            text-decoration: none;
            color: #333;
        }
        .breadcrumb a:hover {
            color: #214b62;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 { color: #214b62; text-transform: uppercase; margin-bottom: 5px; }
        
        /* Style cho phần thông số lượt xem */
        .view-info {
            color: #888;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .description { line-height: 1.6; color: #444; text-align: justify; }
        
        .chapter-list {
            list-style: none;
            padding: 0;
        }
        .chapter-list li {
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }
        .chapter-list li a {
            text-decoration: none;
            color: #214b62;
        }
        .chapter-list li a:hover {
            color: #5a8d23;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">🏠 Truyện</a> / <span><?php echo $truyen['title']; ?></span>
    </div>

    <div class="container">
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
    </div>
</body>
</html>