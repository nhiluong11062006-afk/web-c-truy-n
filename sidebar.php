<?php
// Sidebar dùng chung cho tất cả các trang

// 1. Top 10 được yêu thích (Giữ nguyên)
$sql_top = "SELECT stories_id, title, view_count FROM stories ORDER BY view_count DESC LIMIT 10";
$res_top = mysqli_query($conn, $sql_top);

// 2. PHẦN CẬP NHẬT: Top 10 truyện có chương mới nhất
$sql_new_sb = "SELECT s.stories_id, s.title, MAX(c.created_at) AS last_update 
               FROM stories s
               JOIN chapter c ON s.stories_id = c.stories_id 
               GROUP BY s.stories_id 
               ORDER BY last_update DESC 
               LIMIT 10";
$res_new_sb = mysqli_query($conn, $sql_new_sb);
?>

<div class="sidebar">

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

    <div class="sidebar-box">
        <div class="sidebar-title">🆕  Mới Cập Nhật</div>
        <?php
        while($s = mysqli_fetch_assoc($res_new_sb)) {
            echo '<a href="detail.php?id='.$s['stories_id'].'" class="sidebar-item">
                <span class="sidebar-item-title">'.$s['title'].'</span>
                <span class="sidebar-date">'.date('d/m', strtotime($s['last_update'])).'</span>
            </a>';
        }
        ?>
    </div>

</div>