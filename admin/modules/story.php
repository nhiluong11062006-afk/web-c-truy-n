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
<?php
// 1. XỬ LÝ THÊM TRUYỆN MỚI (Đã cập nhật thêm thể loại)
if (isset($_POST['btn_add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Upload ảnh
    $img = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);

    // Lưu vào bảng stories trước
    $sql = "INSERT INTO stories (title, author, description, image, view_count) 
            VALUES ('$title', '$author', '$desc', '$img', 0)";
    
    if (mysqli_query($conn, $sql)) {
        // Lấy ID của truyện vừa mới thêm xong
        $new_story_id = mysqli_insert_id($conn);

        // Kiểm tra nếu có chọn thể loại thì lưu vào bảng trung gian stories_category
        if (isset($_POST['cat_list']) && is_array($_POST['cat_list'])) {
            foreach ($_POST['cat_list'] as $cat_id) {
                mysqli_query($conn, "INSERT INTO stories_category (stories_id, category_id) VALUES ($new_story_id, $cat_id)");
            }
        }
        echo "<b style='color:green;'>Đã thêm truyện và phân loại thành công!</b>";
    }
}
?>

<hr>
<h3>THÊM TRUYỆN MỚI</h3>
<form method="POST" enctype="multipart/form-data">
    Tên truyện: <input type="text" name="title" required><br><br>
    Tác giả: <input type="text" name="author"><br><br>
    Mô tả: <textarea name="description"></textarea><br><br>
    Ảnh bìa: <input type="file" name="image"><br><br>

    <strong>Chọn thể loại cho truyện:</strong><br>
    <?php
    // Lấy danh sách thể loại từ bảng category ra để làm checkbox
    $res_cat = mysqli_query($conn, "SELECT * FROM category");
    while ($row_cat = mysqli_fetch_assoc($res_cat)) {
        echo "<input type='checkbox' name='cat_list[]' value='{$row_cat['category_id']}'> {$row_cat['name']} &nbsp;";
    }
    ?>
    <br><br>
    <button type="submit" name="btn_add">Lưu truyện</button>
</form>