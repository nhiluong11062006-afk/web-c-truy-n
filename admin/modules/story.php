<?php
// 1. XỬ LÝ XÓA TRUYỆN
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Xóa dữ liệu ở các bảng liên quan trước để tránh lỗi khóa ngoại
    mysqli_query($conn, "DELETE FROM stories_category WHERE stories_id = $id");
    mysqli_query($conn, "DELETE FROM chapter WHERE stories_id = $id");
    
    // Cuối cùng mới xóa truyện
    mysqli_query($conn, "DELETE FROM stories WHERE stories_id = $id");
    
    echo "<script>alert('Đã xóa truyện và các dữ liệu liên quan!'); window.location='index.php?module=story';</script>";
}

// 2. XỬ LÝ THÊM TRUYỆN MỚI
if (isset($_POST['btn_add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Upload ảnh bìa
    $img = $_FILES['image']['name'];
    if ($img != "") {
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);
    } else {
        $img = "default.jpg"; // Ảnh mặc định nếu không chọn ảnh
    }

    // Lưu vào bảng stories
    $sql_story = "INSERT INTO stories (title, author, description, image, view_count) 
                  VALUES ('$title', '$author', '$desc', '$img', 0)";
    
    if (mysqli_query($conn, $sql_story)) {
        $new_story_id = mysqli_insert_id($conn); // Lấy ID truyện vừa tạo

        // Lưu thể loại vào bảng trung gian stories_category
        if (isset($_POST['cat_list']) && is_array($_POST['cat_list'])) {
            foreach ($_POST['cat_list'] as $cat_id) {
                mysqli_query($conn, "INSERT INTO stories_category (stories_id, category_id) VALUES ($new_story_id, $cat_id)");
            }
        }
        echo "<b style='color:green;'>Thêm truyện thành công!</b>";
    } else {
        echo "<b style='color:red;'>Lỗi: " . mysqli_error($conn) . "</b>";
    }
}
?>

<hr>
<h2>QUẢN LÝ TRUYỆN</h2>

<fieldset>
    <legend><b>Thêm truyện mới</b></legend>
    <form method="POST" enctype="multipart/form-data">
        <table cellpadding="5">
            <tr>
                <td>Tên truyện:</td>
                <td><input type="text" name="title" required style="width: 300px;"></td>
            </tr>
            <tr>
                <td>Tác giả:</td>
                <td><input type="text" name="author" style="width: 300px;"></td>
            </tr>
            <tr>
                <td>Mô tả:</td>
                <td><textarea name="description" rows="4" style="width: 300px;"></textarea></td>
            </tr>
            <tr>
                <td>Ảnh bìa:</td>
                <td><input type="file" name="image"></td>
            </tr>
            <tr>
                <td valign="top">Thể loại:</td>
                <td>
                    <div style="max-height: 100px; overflow-y: auto; border: 1px solid #ccc; padding: 5px; width: 300px;">
                    <?php
                    $res_cat = mysqli_query($conn, "SELECT * FROM category");
                    while ($row_cat = mysqli_fetch_assoc($res_cat)) {
                        echo "<label><input type='checkbox' name='cat_list[]' value='{$row_cat['category_id']}'> {$row_cat['name']}</label><br>";
                    }
                    ?>
                    </div>
                    <small>(Có thể chọn nhiều thể loại)</small>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit" name="btn_add">Lưu truyện</button></td>
            </tr>
        </table>
    </form>
</fieldset>

<br>
<hr>

<h3>Danh sách truyện hiện có</h3>
<table border="1" width="100%" cellpadding="10" style="border-collapse: collapse;">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Bìa</th>
        <th>Tên truyện</th>
        <th>Tác giả</th>
        <th>Thể loại</th>
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
        <td><?php echo $row['author']; ?></td>
        <td>
            <?php
            // Truy vấn lấy các thể loại của riêng truyện này
            $sql_my_cat = "SELECT category.name FROM stories_category 
                           JOIN category ON stories_category.category_id = category.category_id 
                           WHERE stories_category.stories_id = $s_id";
            $res_my_cat = mysqli_query($conn, $sql_my_cat);
            $cat_names = [];
            while($c = mysqli_fetch_assoc($res_my_cat)) {
                $cat_names[] = $c['name'];
            }
            echo implode(", ", $cat_names); // Nối các tên bằng dấu phẩy
            ?>
        </td>
        <td align="center">
            <a href="index.php?module=chapter&stories_id=<?php echo $s_id; ?>"><b>[Quản lý chương]</b></a>
            <br><br>
            <a href="index.php?module=story&action=delete&id=<?php echo $s_id; ?>" 
               style="color: red;" onclick="return confirm('Xóa truyện này sẽ xóa cả các chương liên quan. Bạn chắc chứ?')">Xóa</a>
        </td>
    </tr>
    <?php } ?>
</table>