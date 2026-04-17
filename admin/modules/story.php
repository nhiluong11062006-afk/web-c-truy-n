<?php
// 1. XỬ LÝ XÓA TRUYỆN (Nếu có yêu cầu xóa)
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM stories WHERE stories_id = $id");
    header("Location: index.php?module=story");
}

// 2. XỬ LÝ THÊM TRUYỆN MỚI (Khi nhấn nút Lưu)
if (isset($_POST['btn_add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $desc = $_POST['description'];
    
    // Upload ảnh
    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);

    $sql = "INSERT INTO stories (title, author, description, image, view_count) 
            VALUES ('$title', '$author', '$desc', '$img', 0)";
    mysqli_query($conn, $sql);
    echo "<b>Đã thêm truyện thành công!</b>";
}
?>

<hr>
<h3>THÊM TRUYỆN MỚI</h3>
<form method="POST" enctype="multipart/form-data">
    Tên truyện: <input type="text" name="title" required><br>
    Tác giả: <input type="text" name="author"><br>
    Mô tả: <textarea name="description"></textarea><br>
    Ảnh bìa: <input type="file" name="image"><br>
    <button type="submit" name="btn_add">Lưu truyện</button>
</form>

<hr>
<h3>DANH SÁCH TRUYỆN</h3>
<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Tên truyện</th>
        <th>Tác giả</th>
        <th>Thao tác</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM stories ORDER BY stories_id DESC");
    while ($row = mysqli_fetch_assoc($res)) {
    ?>
    <tr>
        <td><?php echo $row['stories_id']; ?></td>
        <td><img src="../uploads/<?php echo $row['image']; ?>" width="50"></td>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['author']; ?></td>
        <td>
            <a href="index.php?module=chapter&stories_id=<?php echo $row['stories_id']; ?>"><b>[Quản lý chương]</b></a> 
            | 
            <a href="index.php?module=story&action=delete&id=<?php echo $row['stories_id']; ?>" onclick="return confirm('Chắc chắn xóa?')">Xóa</a>
        </td>
    </tr>
    <?php } ?>
</table>