<?php
// Sidebar dùng chung cho tất cả các trang
// File này được include vào trong .container của từng trang

$sql_top = "SELECT stories_id, title, view_count FROM stories ORDER BY view_count DESC LIMIT 10";
$res_top = mysqli_query($conn, $sql_top);

$sql_new_sb = "SELECT stories_id, title, created_at FROM stories ORDER BY created_at DESC LIMIT 10";
$res_new_sb = mysqli_query($conn, $sql_new_sb);
?>

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
        while($s = mysqli_fetch_assoc($res_new_sb)) {
            echo '<a href="detail.php?id='.$s['stories_id'].'" class="sidebar-item">
                <span class="sidebar-item-title">'.$s['title'].'</span>
                <span class="sidebar-date">'.date('d/m', strtotime($s['created_at'])).'</span>
            </a>';
        }
        ?>
    </div>

</div>
