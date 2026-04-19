<?php
global $conn;
?>
<div class="sidebar">
    <div class="sidebar-box">
        <h3 class="sidebar-title">Top 10 Mới Cập Nhật</h3>
        <ul class="sidebar-list">
        <?php
        $sql = "SELECT stories_id, title, created_at 
                FROM stories 
                ORDER BY created_at DESC 
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $stt = 1;
        while($row = mysqli_fetch_assoc($result)) {
            $date = date('d/m/Y', strtotime($row['created_at']));
        ?>
        <li class="sidebar-item">
                <div class="new-dot"></div>
                <div class="sidebar-item-info">
                    <a href="detail.php?id=<?php echo $row['stories_id']; ?>" class="sidebar-item-title">
                        <?php echo $row['title']; ?>
                    </a>
                    <span class="sidebar-item-meta">📅 <?php echo $date; ?></span>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
 
</div>
 
        
        <h3 class="sidebar-title">Top 10 Được Yêu Thích</h3>
        
        <ul class="sidebar-list">
        <?php
        $sql2 = "SELECT stories_id, title, view_count 
                 FROM stories 
                 ORDER BY view_count DESC 
                 LIMIT 10";
        $res_top = mysqli_query($conn, $sql2);
            $rank = 1;
            while($row = mysqli_fetch_assoc($res_top)) {
                $rankClass = $rank <= 3 ? 'rank-top' : '';
            ?>
            <li class="sidebar-item">
                <span class="rank-num <?php echo $rankClass; ?>"><?php echo $rank; ?></span>
                <div class="sidebar-item-info">
                    <a href="detail.php?id=<?php echo $row['stories_id']; ?>" class="sidebar-item-title">
                        <?php echo $row['title']; ?>
                    </a>
                    <span class="sidebar-item-meta">👁️ <?php echo number_format($row['view_count']); ?> lượt xem</span>
                </div>
            </li>
            <?php $rank++; } ?>
        </ul>
    </div>