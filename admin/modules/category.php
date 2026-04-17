<h3>QUẢN LÝ LOẠI TRUYỆN</h3>

<?php
// 1. XỬ LÝ THÊM LOẠI TRUYỆN
if(isset($_POST['add_cat'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    mysqli_query($conn, "INSERT INTO category (name) VALUES ('$name')");
    echo "<b style='color:green;'>Đã thêm thể loại: $name</b>";
}

// 2. XỬ LÝ XÓA LOẠI TRUYỆN
if(isset($_GET['delete_cat'])) {
    $id = $_GET['delete_cat'];
    // Xóa liên kết ở bảng trung gian trước để tránh lỗi
    mysqli_query($conn, "DELETE FROM stories_category WHERE category_id = $id");
    mysqli_query($conn, "DELETE FROM category WHERE category_id = $id");
    echo "<script>window.location='index.php?module=category';</script>";
}

// 3. CÂU LỆNH SQL ĐỂ ĐẾM SỐ TRUYỆN TRONG MỖI THỂ LOẠI
// Giải thích: Lấy tất cả category, kết hợp với bảng trung gian để đếm số stories_id
$sql = "SELECT c.category_id, c.name, COUNT(sc.stories_id) AS total_stories 
        FROM category c 
        LEFT JOIN stories_category sc ON c.category_id = sc.category_id 
        GROUP BY c.category_id, c.name 
        ORDER BY c.category_id DESC";

$res = mysqli_query($conn, $sql);
$total_types = mysqli_num_rows($res);
?>

<p><b>Tổng số loại truyện hiện có: <?php echo $total_types; ?></b></p>

<form method="POST">
    <input type="text" name="cat_name" placeholder="Tên loại truyện mới" required>
    <button type="submit" name="add_cat">Thêm mới</button>
</form>

<br>
<table border="1" width="60%" cellpadding="8" style="border-collapse: collapse;">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Tên loại</th>
        <th>Số lượng truyện</th> <th>Hành động</th>
    </tr>
    <?php
    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td align='center'>{$row['category_id']}</td>
                <td>{$row['name']}</td>
                <td align='center'><b>{$row['total_stories']}</b> truyện</td>
                <td align='center'>
                    <a href='index.php?module=category&delete_cat={$row['category_id']}' 
                       onclick='return confirm(\"Xóa loại này sẽ mất phân loại của các truyện liên quan. Tiếp tục?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
</table>