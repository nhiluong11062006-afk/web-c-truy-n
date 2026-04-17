<h3>Quản lý loại truyện</h3>
<form method="POST">
    <input type="text" name="cat_name" placeholder="Tên loại truyện mới" required>
    <button type="submit" name="add_cat" class="btn btn-add">Thêm mới</button>
</form>

<?php
// Xử lý thêm
if(isset($_POST['add_cat'])) {
    $name = $_POST['cat_name'];
    mysqli_query($conn, "INSERT INTO category (name) VALUES ('$name')");
}

// Xử lý xóa
if(isset($_GET['delete_cat'])) {
    $id = $_GET['delete_cat'];
    mysqli_query($conn, "DELETE FROM category WHERE category_id = $id");
}
?>

<table>
    <tr>
        <th>ID</th>
        <th>Tên loại</th>
        <th>Hành động</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM category");
    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td>{$row['category_id']}</td>
                <td>{$row['name']}</td>
                <td>
                    <a href='?module=category&delete_cat={$row['category_id']}' class='btn btn-delete' onclick='return confirm(\"Chắc chưa?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
</table>