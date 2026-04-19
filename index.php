<?php 
include 'connect.php'; 

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
if($page < 1) $page = 1;
$start = ($page - 1) * $limit; 

$sql_count = "SELECT COUNT(*) AS total FROM stories";
$res_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_records = $row_count['total']; 
$total_pages = ceil($total_records / $limit); 

$sql = "SELECT * FROM stories ORDER BY created_at DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Đọc Truyện Online - Trang <?php echo $page; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="row">
            
            <div class="col-12">
                <h1 style="margin-top:20px;">TRUYỆN MỚI CẬP NHẬT</h1>
                
                <div class="list-truyen">
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="item-truyen" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <img src="uploads/<?php echo $row['image']; ?>" alt="Ảnh truyện" style="width: 100px; float: left; margin-right: 15px;">
                            <h3><?php echo $row['title']; ?></h3>
                            <p style="font-size: 13px; color: #666;">Tác giả: <?php echo $row['author']; ?></p>
                            <a href="detail.php?id=<?php echo $row['stories_id']; ?>" class="btn-read">Đọc ngay</a>
                            <div style="clear: both;"></div>
                        </div>
                    <?php } ?>
                </div>

                <?php if($total_pages > 1): ?>
                <ul class="pagination" style="display: flex; list-style: none; gap: 10px; padding: 20px 0;">
                    <?php if($page > 1): ?>
                        <li><a href="index.php?page=1">Đầu</a></li>
                        <li><a href="index.php?page=<?php echo $page-1; ?>">‹</a></li>
                    <?php endif; ?>

                    <?php 
                    for($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                        <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if($page < $total_pages): ?>
                        <li><a href="index.php?page=<?php echo $total_pages; ?>">Cuối</a></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </div>

            <div class="col-12" style="margin-top: 50px; border-top: 2px solid #000;">
                <?php include 'sidebar.php'; ?>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>