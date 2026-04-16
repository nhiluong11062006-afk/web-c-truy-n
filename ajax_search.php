<?php
include 'connect.php';

if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    
    // Tìm truyện có tên chứa từ khóa (chỉ lấy 5 kết quả đầu tiên cho gọn)
    $sql = "SELECT * FROM stories WHERE title LIKE '%$keyword%' LIMIT 5";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="detail.php?id=' . $row['stories_id'] . '">' . $row['title'] . '</a>';
        }
        // Dòng cuối cùng để xem thêm tất cả kết quả
        echo '<a href="search.php?keyword=' . $keyword . '" class="view-more">Xem thêm kết quả khác 🔍</a>';
    } else {
        echo '<p style="padding: 10px; color: #777; margin:0;">Không tìm thấy truyện...</p>';
    }
}
?>