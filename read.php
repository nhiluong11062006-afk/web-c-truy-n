<?php 
// 1. Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php'; 

// 2. Lấy ID chương từ URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: index.php");
    exit();
}

// 3. Lấy thông tin chương VÀ tên truyện (Dùng JOIN)
$sql = "SELECT chapter.*, stories.title AS story_title 
        FROM chapter 
        JOIN stories ON chapter.stories_id = stories.stories_id 
        WHERE chapter.chapter_id = $id";

$res = mysqli_query($conn, $sql);
$chuong_hien_tai = mysqli_fetch_assoc($res);

if(!$chuong_hien_tai) {
    echo "Chương này không tồn tại trong hệ thống.";
    exit();
}

$stories_id = $chuong_hien_tai['stories_id'];
$current_no = $chuong_hien_tai['chapter_number'];

// 4. Tìm ID của chương TRƯỚC
$sql_prev = "SELECT chapter_id FROM chapter 
             WHERE stories_id = $stories_id AND chapter_number < $current_no 
             ORDER BY chapter_number DESC LIMIT 1";
$res_prev = mysqli_query($conn, $sql_prev);
$prev_chapter = mysqli_fetch_assoc($res_prev);

// 5. Tìm ID của chương TIẾP THEO
$sql_next = "SELECT chapter_id FROM chapter 
             WHERE stories_id = $stories_id AND chapter_number > $current_no 
             ORDER BY chapter_number ASC LIMIT 1";
$res_next = mysqli_query($conn, $sql_next);
$next_chapter = mysqli_fetch_assoc($res_next);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $chuong_hien_tai['story_title']; ?> - <?php echo $chuong_hien_tai['title']; ?></title>
    
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">🏠 Truyện</a> / 
        <a href="detail.php?id=<?php echo $stories_id; ?>"><?php echo $chuong_hien_tai['story_title']; ?></a> / 
        <span>Chương <?php echo $chuong_hien_tai['chapter_number']; ?></span>
    </div>

    <div class="container">
        <div class="chapter-header">
            <h1 class="story-title"><?php echo $chuong_hien_tai['story_title']; ?></h1>
            <div class="chapter-no">Chương <?php echo $chuong_hien_tai['chapter_number']; ?>: <?php echo $chuong_hien_tai['title']; ?></div>
        </div>

        <div class="navigation">
            <?php if($prev_chapter): ?>
                <a href="read.php?id=<?php echo $prev_chapter['chapter_id']; ?>" class="nav-btn">❮ trước</a>
            <?php else: ?>
                <a href="#" class="nav-btn disabled">❮ trước</a>
            <?php endif; ?>

            <a href="detail.php?id=<?php echo $stories_id; ?>" class="list-icon">📋</a>

            <?php if($next_chapter): ?>
                <a href="read.php?id=<?php echo $next_chapter['chapter_id']; ?>" class="nav-btn">tiếp ❯</a>
            <?php else: ?>
                <a href="#" class="nav-btn disabled">tiếp ❯</a>
            <?php endif; ?>
        </div>

        <hr>

        <div class="content-text">
            <?php echo nl2br($chuong_hien_tai['content']); ?>
        </div>

        <hr>

        <div class="navigation">
            <?php if($prev_chapter): ?>
                <a href="read.php?id=<?php echo $prev_chapter['chapter_id']; ?>" class="nav-btn">❮ trước</a>
            <?php else: ?>
                <a href="#" class="nav-btn disabled">❮ trước</a>
            <?php endif; ?>

            <a href="detail.php?id=<?php echo $stories_id; ?>" class="list-icon">📋</a>

            <?php if($next_chapter): ?>
                <a href="read.php?id=<?php echo $next_chapter['chapter_id']; ?>" class="nav-btn">tiếp ❯</a>
            <?php else: ?>
                <a href="#" class="nav-btn disabled">tiếp ❯</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>