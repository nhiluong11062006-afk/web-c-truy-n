<?php 
include 'connect.php'; 

if(isset($_GET['id'])) {
    $cat_id = $_GET['id'];
} else {
    header("Location: index.php");
    exit();
}

// Logic phân trang và lấy dữ liệu giữ nguyên như bạn đã viết
$limit = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) AS total FROM stories_category WHERE category_id = $cat_id";
$res_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

$sql_cat = "SELECT * FROM category WHERE category_id = $cat_id";
$res_cat = mysqli_query($conn, $sql_cat);
$category = mysqli_fetch_assoc($res_cat);

$sql_stories = "SELECT stories.* FROM stories 
                JOIN stories_category ON stories.stories_id = stories_category.stories_id 
                WHERE stories_category.category_id = $cat_id 
                ORDER BY stories.stories_id DESC 
                LIMIT $start, $limit";
$res_stories = mysqli_query($conn, $sql_stories);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truyện <?php echo $category['name']; ?> - Trang <?php echo $page; ?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <div class="container_mini" style="max-width: 1200px; margin: 0 auto; padding: 10px 15px;">
            <a href="index.php">🏠 Truyện</a> / <span><?php echo $category['name']; ?></span>
        </div>
    </div>

    <div class="container home"> 

        <div class="main-content">
            <h1 class="cat-title">Truyện <?php echo $category['name']; ?></h1>

            <?php if(!empty($category['description'])): ?>
            <div class="cat-desc-box" style="margin-bottom: 1.5rem; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                <strong style="color: #d45c82;">Giới thiệu thể loại:</strong><br>
                <span style="font-size: 0.95rem; color: #555;"><?php echo nl2br($category['description']); ?></span>
            </div>
            <?php endif; ?>

            <div class="story-list">
                <?php 
                if(mysqli_num_rows($res_stories) > 0) {
                    while($row = mysqli_fetch_assoc($res_stories)) { ?>
                    <div class="story-item">
                        <div class="story-left">
                            <img src="uploads/<?php echo $row['image']; ?>" class="story-thumb" alt="thumb">
                            <div class="story-info">
                                <h3><a href="detail.php?id=<?php echo $row['stories_id']; ?>"><?php echo $row['title']; ?></a></h3>
                                <div class="author">🖋️ <?php echo $row['author']; ?></div>
                            </div>
                        </div>
                        <a href="detail.php?id=<?php echo $row['stories_id']; ?>" class="btn-detail">Đọc truyện</a>
                    </div>
                <?php }
                } else {
                    echo '<div class="no-data">Chưa có truyện nào trong mục này.</div>';
                } ?>
            </div>

            <?php if($total_pages > 1): ?>
            <ul class="pagination">
                <?php if($page > 1): ?>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=1">Đầu</a></li>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=<?php echo $page-1; ?>">‹</a></li>
                <?php endif; ?>
                <?php 
                $start_loop = max(1, $page - 2);
                $end_loop = min($total_pages, $page + 2);
                for($i = $start_loop; $i <= $end_loop; $i++): ?>
                    <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a href="category.php?id=<?php echo $cat_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if($page < $total_pages): ?>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=<?php echo $page+1; ?>">›</a></li>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=<?php echo $total_pages; ?>">Cuối</a></li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <?php include 'sidebar.php'; ?>
        </div>

    </div> <footer class="site-footer">
        <div class="footer-links">
            <a href="#">Giới thiệu</a> <a href="#">Liên hệ</a> <a href="#">Thể loại</a>
            <span class="sep">|</span>
            <a href="#">Điều khoản</a> <a href="#">Bảo mật</a>
        </div>
        <div class="footer-copy">
            © 2026 Truyện Hay — Website đọc truyện online miễn phí
        </div>
    </footer>
</body>
</html>