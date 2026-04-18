<?php
require_once 'connect.php';
?>

<div style="width:270px; min-width:270px; flex-shrink:0;">

    <!-- TOP 10 TRUYỆN MỚI CẬP NHẬT -->
    <div style="background:white; border-radius:8px; padding:15px; 
                margin-bottom:20px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        
        <h3 style="color:#c0392b; font-size:15px; font-weight:bold;
                   border-bottom:2px solid #c0392b; padding-bottom:8px; 
                   margin-bottom:12px; margin-top:0;">
            🆕 Top 10 Mới Cập Nhật
        </h3>
        
        <ul style="list-style:none; padding:0; margin:0;">
        <?php
        $sql = "SELECT stories_id, title, created_at 
                FROM stories 
                ORDER BY created_at DESC 
                LIMIT 10";
        $result = mysqli_query($conn, $sql);
        $stt = 1;
        while($row = mysqli_fetch_assoc($result)):
        ?>
        <li style="display:flex; align-items:center; gap:8px; 
                   padding:7px 0; border-bottom:1px solid #f0f0f0;">
            
            <span style="min-width:24px; height:24px; border-radius:50%;
                         background:<?= $stt==1?'#f39c12':($stt==2?'#95a5a6':($stt==3?'#cd6133':'#ddd')) ?>;
                         color:<?= $stt<=3?'white':'#555' ?>;
                         display:flex; align-items:center; justify-content:center;
                         font-weight:bold; font-size:12px; flex-shrink:0;">
                <?= $stt ?>
            </span>
            
            <a href="detail.php?id=<?= $row['stories_id'] ?>"
               style="flex:1; color:#333; font-size:13px;
                      overflow:hidden; white-space:nowrap; text-overflow:ellipsis;
                      text-decoration:none;">
                <?= htmlspecialchars($row['title']) ?>
            </a>
            
            <span style="font-size:11px; color:#999; white-space:nowrap;">
                <?= date('d/m', strtotime($row['created_at'])) ?>
            </span>
        </li>
        <?php $stt++; endwhile; ?>
        </ul>
    </div>

    <!-- TOP 10 TRUYỆN ĐƯỢC YÊU THÍCH (nhiều view nhất) -->
    <div style="background:white; border-radius:8px; padding:15px;
                box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        
        <h3 style="color:#c0392b; font-size:15px; font-weight:bold;
                   border-bottom:2px solid #c0392b; padding-bottom:8px; 
                   margin-bottom:12px; margin-top:0;">
            ❤️ Top 10 Được Yêu Thích
        </h3>
        
        <ul style="list-style:none; padding:0; margin:0;">
        <?php
        $sql2 = "SELECT stories_id, title, view_count 
                 FROM stories 
                 ORDER BY view_count DESC 
                 LIMIT 10";
        $result2 = mysqli_query($conn, $sql2);
        $stt2 = 1;
        while($row2 = mysqli_fetch_assoc($result2)):
        ?>
        <li style="display:flex; align-items:center; gap:8px;
                   padding:7px 0; border-bottom:1px solid #f0f0f0;">
            
            <span style="min-width:24px; height:24px; border-radius:50%;
                         background:<?= $stt2==1?'#f39c12':($stt2==2?'#95a5a6':($stt2==3?'#cd6133':'#ddd')) ?>;
                         color:<?= $stt2<=3?'white':'#555' ?>;
                         display:flex; align-items:center; justify-content:center;
                         font-weight:bold; font-size:12px; flex-shrink:0;">
                <?= $stt2 ?>
            </span>
            
            <a href="detail.php?id=<?= $row2['stories_id'] ?>"
               style="flex:1; color:#333; font-size:13px;
                      overflow:hidden; white-space:nowrap; text-overflow:ellipsis;
                      text-decoration:none;">
                <?= htmlspecialchars($row2['title']) ?>
            </a>
            
            <span style="font-size:11px; color:#e74c3c; white-space:nowrap;">
                👁 <?= number_format($row2['view_count']) ?>
            </span>
        </li>
        <?php $stt2++; endwhile; ?>
        </ul>
    </div>

</div>