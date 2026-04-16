<?php 
include 'connect.php'; 

// 1. Lấy ID thể loại từ URL
if(isset($_GET['id'])) {
    $cat_id = $_GET['id'];
} else {
    header("Location: index.php");
    exit();
}

// --- 2. PHẦN LOGIC PHÂN TRANG ---
$limit = 7; // Số truyện hiển thị trên 1 trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại, mặc định là 1
if($page < 1) $page = 1;
$start = ($page - 1) * $limit; // Vị trí bắt đầu lấy bản ghi trong SQL

// 3. Đếm tổng số truyện thuộc thể loại này để tính tổng số trang
$sql_count = "SELECT COUNT(*) AS total FROM stories_category WHERE category_id = $cat_id";
$res_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_records = $row_count['total']; // Tổng số truyện
$total_pages = ceil($total_records / $limit); // Tổng số trang (làm tròn lên)

// 4. Lấy thông tin thể loại hiện tại
$sql_cat = "SELECT * FROM category WHERE category_id = $cat_id";
$res_cat = mysqli_query($conn, $sql_cat);
$category = mysqli_fetch_assoc($res_cat);

// 5. Lấy danh sách truyện (Sử dụng LIMIT để phân trang)
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truyện <?php echo $category['name']; ?> - Trang <?php echo $page; ?></title>
    
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">🏠 Truyện</a> / <span><?php echo $category['name']; ?></span>
    </div>

    <div class="container">
        <div class="main-content">
            <h1 class="cat-title">Truyện <?php echo $category['name']; ?></h1>
            
            <div class="story-list">
                <?php 
                if(mysqli_num_rows($res_stories) > 0) {
                    while($row = mysqli_fetch_assoc($res_stories)) { 
                ?>
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
                <?php 
                    } 
                } else {
                    echo '<div class="no-data">Chưa có truyện nào trong mục này.</div>';
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <ul class="pagination">
                <?php if($page > 1): ?>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=1">Đầu</a></li>
                    <li><a href="category.php?id=<?php echo $cat_id; ?>&page=<?php echo $page-1; ?>">‹</a></li>
                <?php endif; ?>

                <?php 
                // Hiển thị tối đa 5 trang xung quanh trang hiện tại để tránh thanh phân trang quá dài
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
            <div class="cat-desc-box">
                <strong>Giới thiệu thể loại:</strong><br>
                <?php echo !empty($category['description']) ? nl2br($category['description']) : "Đang cập nhật mô tả..."; ?>
            </div>
        </div>
    </div>

</body>
</html>