<?php 
include 'connect.php';

// --- PHÂN TRANG ---
$limit = 9;
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

// --- Top 10 lượt xem (Sidebar dùng) ---
$sql_top = "SELECT stories_id, title, view_count FROM stories ORDER BY view_count DESC LIMIT 10";
$res_top = mysqli_query($conn, $sql_top);

// --- CẬP NHẬT: Top 10 mới nhất theo Chapter (Sidebar dùng) ---
$sql_new = "SELECT s.stories_id, s.title, COALESCE(MAX(c.created_at), s.created_at) AS last_update 
            FROM stories s
            LEFT JOIN chapter c ON s.stories_id = c.stories_id 
            GROUP BY s.stories_id 
            ORDER BY last_update DESC 
            LIMIT 10";
$res_new = mysqli_query($conn, $sql_new);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Web Đọc Truyện Online - Trang <?php echo $page; ?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container home">

        <div class="main-content">
            <h1>TRUYỆN MỚI CẬP NHẬT</h1>
            <div class="list-truyen">
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="item-truyen">
                        <img src="uploads/<?php echo $row['image']; ?>" alt="Ảnh truyện">
                        <h3><?php echo $row['title']; ?></h3>
                        <p>Tác giả: <?php echo $row['author']; ?></p>
                        <a href="detail.php?id=<?php echo $row['stories_id']; ?>" class="btn-read">Đọc ngay</a>
                    </div>
                <?php } ?>
            </div>

            <?php if($total_pages > 1): ?>
            <ul class="pagination">
                <?php if($page > 1): ?>
                    <li><a href="index.php?page=1">Đầu</a></li>
                    <li><a href="index.php?page=<?php echo $page-1; ?>">‹</a></li>
                <?php endif; ?>
                <?php 
                $start_loop = max(1, $page - 2);
                $end_loop = min($total_pages, $page + 2);
                for($i = $start_loop; $i <= $end_loop; $i++): ?>
                    <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if($page < $total_pages): ?>
                    <li><a href="index.php?page=<?php echo $page+1; ?>">›</a></li>
                    <li><a href="index.php?page=<?php echo $total_pages; ?>">Cuối</a></li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

    </div> <footer class="site-footer">
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