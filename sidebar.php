<?php global $conn; ?>
<div class="sidebar">
    <div class="sidebar-box shadow-sm mb-4">
        <h3 class="sidebar-title">Top 10 Mới Cập Nhật</h3>
        <ul class="sidebar-list">
        <?php
        $sql = "SELECT stories_id, title, created_at FROM stories ORDER BY created_at DESC LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $stt = 1;
        while($row = mysqli_fetch_assoc($result)): ?>
            <li class="sidebar-item">
                <span class="rank rank-<?= $stt <= 3 ? $stt : 'other' ?>"><?= $stt ?></span>
                <a href="detail.php?id=<?= $row['stories_id'] ?>"><?= htmlspecialchars($row['title']) ?></a>
                <span class="sidebar-date"><?= date('d/m', strtotime($row['created_at'])) ?></span>
            </li>
        <?php $stt++; endwhile; ?>
        </ul>
    </div>

    <div class="sidebar-box shadow-sm">
        <h3 class="sidebar-title">Top 10 Được Yêu Thích</h3>
        <ul class="sidebar-list">
        <?php
        $sql2 = "SELECT stories_id, title, view_count FROM stories ORDER BY view_count DESC LIMIT 10";
        $result2 = mysqli_query($conn, $sql2);
        $stt2 = 1;
        while($row2 = mysqli_fetch_assoc($result2)): ?>
            <li class="sidebar-item">
                <span class="rank rank-<?= $stt2 <= 3 ? $stt2 : 'other' ?>"><?= $stt2 ?></span>
                <a href="detail.php?id=<?= $row2['stories_id'] ?>"><?= htmlspecialchars($row2['title']) ?></a>
                <span class="sidebar-views">👁 <?= number_format($row2['view_count']) ?></span>
            </li>
        <?php $stt2++; endwhile; ?>
        </ul>
    </div>
</div>
