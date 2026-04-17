<?php
// 1. XỬ LÝ XÓA TRUYỆN
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM stories_category WHERE stories_id = $id");
    mysqli_query($conn, "DELETE FROM chapter WHERE stories_id = $id");
    mysqli_query($conn, "DELETE FROM stories WHERE stories_id = $id");
    echo "<script>alert('Đã xóa!'); window.location='index.php?module=story';</script>";
}

// 2. XỬ LÝ LƯU DỮ LIỆU (CẢ THÊM VÀ SỬA)
if (isset($_POST['btn_save'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // Xử lý ảnh
    if ($_FILES['image']['name'] != "") {
        $img = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);
    } else {
        $img = $_POST['old_image']; // Giữ lại ảnh cũ nếu không chọn ảnh mới
    }

    if ($id == "") {
        // THÊM MỚI
        $sql = "INSERT INTO stories (title, author, description, image, view_count) VALUES ('$title', '$author', '$desc', '$img', 0)";
        mysqli_query($conn, $sql);
        $story_id = mysqli_insert_id($conn);
    } else {
        // CẬP NHẬT
        $sql = "UPDATE stories SET title='$title', author='$author', description='$desc', image='$img' WHERE stories_id = $id";
        mysqli_query($conn, $sql);
        $story_id = $id;
        // Xóa thể loại cũ để lưu lại cái mới
        mysqli_query($conn, "DELETE FROM stories_category WHERE stories_id = $story_id");
    }

    // Lưu thể loại
    if (isset($_POST['cat_list'])) {
        foreach ($_POST['cat_list'] as $cat_id) {
            mysqli_query($conn, "INSERT INTO stories_category (stories_id, category_id) VALUES ($story_id, $cat_id)");
        }
    }
    echo "<script>alert('Thành công!'); window.location='index.php?module=story';</script>";
}

// 3. LẤY DỮ LIỆU CŨ KHI BẤM "SỬA"
$row_old = ['stories_id'=>'','title'=>'','author'=>'','description'=>'','image'=>'','cat_ids'=>[]];
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM stories WHERE stories_id = $id");
    $row_old = mysqli_fetch_assoc($res);
    
    // Lấy danh sách ID thể loại đã chọn của truyện này
    $res_c = mysqli_query($conn, "SELECT category_id FROM stories_category WHERE stories_id = $id");
    while($c = mysqli_fetch_assoc($res_c)) { $row_old['cat_ids'][] = $c['category_id']; }
}
?>

<hr>
<h3><?php echo ($row_old['stories_id'] != '') ? "SỬA TRUYỆN ID: ".$row_old['stories_id'] : "THÊM TRUYỆN MỚI"; ?></h3>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $row_old['stories_id']; ?>">
    <input type="hidden" name="old_image" value="<?php echo $row_old['image']; ?>">
    
    Tên truyện: <input type="text" name="title" value="<?php echo $row_old['title']; ?>" required><br><br>
    Tác giả: <input type="text" name="author" value="<?php echo $row_old['author']; ?>"><br><br>
    Mô tả: <textarea name="description"><?php echo $row_old['description']; ?></textarea><br><br>
    
    Ảnh bìa hiện tại: <img src="../uploads/<?php echo $row_old['image']; ?>" width="50"><br>
    Thay ảnh mới: <input type="file" name="image"><br><br>

    <strong>Thể loại:</strong><br>
    <?php
    $res_cat = mysqli_query($conn, "SELECT * FROM category");
    while ($cat = mysqli_fetch_assoc($res_cat)) {
        $checked = (in_array($cat['category_id'], $row_old['cat_ids'])) ? "checked" : "";
        echo "<label><input type='checkbox' name='cat_list[]' value='{$cat['category_id']}' $checked> {$cat['name']}</label> ";
    }
    ?><br><br>
    
    <button type="submit" name="btn_save">Lưu thông tin</button>
    <?php if($row_old['stories_id'] != '') echo "<a href='index.php?module=story'>Hủy sửa</a>"; ?>
</form>

<hr>
<h3>DANH SÁCH TRUYỆN</h3>
<table border="1" width="100%" cellpadding="10" style="border-collapse: collapse;">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Bìa</th>
        <th>Tên truyện</th>
        <th>Lượt đọc</th> <th>Thể loại</th>
        <th>Thao tác</th>
    </tr>
    <?php
    $sql_list = "SELECT * FROM stories ORDER BY stories_id DESC";
    $res_list = mysqli_query($conn, $sql_list);
    
    while ($row = mysqli_fetch_assoc($res_list)) {
        $s_id = $row['stories_id'];
    ?>
    <tr>
        <td align="center"><?php echo $s_id; ?></td>
        <td align="center">
            <img src="../uploads/<?php echo $row['image']; ?>" width="60" height="80" style="object-fit: cover;">
        </td>
        <td><b><?php echo $row['title']; ?></b></td>
        <td align="center"><?php echo number_format($row['view_count']); ?></td> <td>
            <?php
            $sql_my_cat = "SELECT category.name FROM stories_category 
                           JOIN category ON stories_category.category_id = category.category_id 
                           WHERE stories_category.stories_id = $s_id";
            $res_my_cat = mysqli_query($conn, $sql_my_cat);
            $cat_names = [];
            while($c = mysqli_fetch_assoc($res_my_cat)) { $cat_names[] = $c['name']; }
            echo implode(", ", $cat_names);
            ?>
        </td>
        <td align="center">
            <a href="index.php?module=chapter&stories_id=<?php echo $s_id; ?>">[Quản lý chương]</a> |
            <a href="index.php?module=story&action=edit&id=<?php echo $s_id; ?>">Sửa</a> |
            <a href="index.php?module=story&action=delete&id=<?php echo $s_id; ?>" style="color: red;" onclick="return confirm('Xóa?')">Xóa</a>
        </td>
    </tr>
    <?php } ?>
</table>