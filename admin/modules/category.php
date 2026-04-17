<h3>QUẢN LÝ LOẠI TRUYỆN</h3>

<form method="POST">
    <input type="text" name="cat_name" placeholder="Tên loại truyện mới" required>
    <button type="submit" name="add_cat">Thêm mới</button>
</form>

<?php
// Xử lý thêm: Nhi dùng mysqli_real_escape_string để tránh lỗi khi tên có dấu hoặc ký tự đặc biệt
if(isset($_POST['add_cat'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    mysqli_query($conn, "INSERT INTO category (name) VALUES ('$name')");
    echo "<b>Đã thêm thể loại: $name</b>";
}

// Xử lý xóa
if(isset($_GET['delete_cat'])) {
    $id = $_GET['delete_cat'];
    mysqli_query($conn, "DELETE FROM category WHERE category_id = $id");
    // Dùng JavaScript để reset lại URL, tránh việc F5 bị xóa tiếp
    echo "<script>window.location='index.php?module=category';</script>";
}
?>

<br>
<table border="1" width="50%">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Tên loại</th>
        <th>Hành động</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM category ORDER BY category_id DESC");
    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td align='center'>{$row['category_id']}</td>
                <td>{$row['name']}</td>
                <td align='center'>
                    <a href='index.php?module=category&delete_cat={$row['category_id']}' onclick='return confirm(\"Chắc chưa?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
</table>