<?php 
include 'connect.php';

// --- PHÂN TRANG: 9 truyện/trang, 3 truyện/dòng ---
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

// --- Top 10 lượt xem ---
$sql_top = "SELECT stories_id, title, view_count FROM stories ORDER BY view_count DESC LIMIT 10";
$res_top = mysqli_query($conn, $sql_top);

// --- Top 10 mới nhất ---
$sql_new = "SELECT stories_id, title, created_at FROM stories ORDER BY created_at DESC LIMIT 10";
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

        <!-- CỘT TRÁI: Danh sách truyện -->
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

        <!-- CỘT PHẢI: Sidebar -->
        <div class="sidebar">

            <!-- Top 10 được yêu thích -->
            <div class="sidebar-box">
                <div class="sidebar-title">🔥 Top 10 Được Yêu Thích</div>
                <?php
                $rank = 1;
                while($s = mysqli_fetch_assoc($res_top)) {
                    $cls = $rank <= 3 ? 'rank-top' : '';
                    echo '<a href="detail.php?id='.$s['stories_id'].'" class="sidebar-item '.$cls.'">
                        <span class="rank-num">'.$rank.'</span>
                        <span class="sidebar-item-title">'.$s['title'].'</span>
                        <span class="sidebar-views">'.number_format($s['view_count']).'</span>
                    </a>';
                    $rank++;
                }
                ?>
            </div>

            <!-- Top 10 mới cập nhật -->
            <div class="sidebar-box">
                <div class="sidebar-title">🆕 Mới Cập Nhật</div>
                <?php
                while($s = mysqli_fetch_assoc($res_new)) {
                    echo '<a href="detail.php?id='.$s['stories_id'].'" class="sidebar-item">
                        <span class="sidebar-item-title">'.$s['title'].'</span>
                        <span class="sidebar-date">'.date('d/m', strtotime($s['created_at'])).'</span>
                    </a>';
                }
                ?>
            </div>

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