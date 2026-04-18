<h3>QUẢN LÝ LOẠI TRUYỆN</h3>

<?php
// 1. XỬ LÝ LƯU (CẢ THÊM MỚI VÀ CẬP NHẬT)
if (isset($_POST['btn_save_cat'])) {
    $c_id = $_POST['category_id']; // Nếu có ID là đang sửa, không có là thêm mới
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['cat_description']);

    if ($c_id == "") {
        // THÊM MỚI
        $sql = "INSERT INTO category (name, description) VALUES ('$name', '$desc')";
        $msg = "Đã thêm thể loại: $name";
    } else {
        // CẬP NHẬT
        $sql = "UPDATE category SET name='$name', description='$desc' WHERE category_id = $c_id";
        $msg = "Đã cập nhật thể loại!";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<b style='color:green;'>$msg</b>";
        // Sau khi lưu xong thì reset biến để form quay về trạng thái "Thêm mới"
        echo "<script>window.location='index.php?module=category';</script>";
    }
}

// 2. XỬ LÝ XÓA
if (isset($_GET['delete_cat'])) {
    $id = $_GET['delete_cat'];
    mysqli_query($conn, "DELETE FROM stories_category WHERE category_id = $id");
    mysqli_query($conn, "DELETE FROM category WHERE category_id = $id");
    echo "<script>window.location='index.php?module=category';</script>";
}

// 3. LẤY DỮ LIỆU CŨ KHI BẤM "SỬA"
$edit_id = ""; $edit_name = ""; $edit_desc = "";
if (isset($_GET['edit_cat'])) {
    $id = $_GET['edit_cat'];
    $res_edit = mysqli_query($conn, "SELECT * FROM category WHERE category_id = $id");
    $row_edit = mysqli_fetch_assoc($res_edit);
    $edit_id = $row_edit['category_id'];
    $edit_name = $row_edit['name'];
    $edit_desc = $row_edit['description'];
}

// 4. TRUY VẤN DANH SÁCH (Kết hợp đếm số truyện)
$sql = "SELECT c.category_id, c.name, c.description, COUNT(sc.stories_id) AS total_stories 
        FROM category c 
        LEFT JOIN stories_category sc ON c.category_id = sc.category_id 
        GROUP BY c.category_id, c.name, c.description
        ORDER BY c.category_id DESC";
$res = mysqli_query($conn, $sql);
$total_types = mysqli_num_rows($res);
?>

<p><b>Tổng số loại truyện hiện có: <?php echo $total_types; ?></b></p>

<fieldset>
    <legend><b><?php echo ($edit_id != "") ? "Sửa loại truyện" : "Thêm loại truyện mới"; ?></b></legend>
    <form method="POST">
        <input type="hidden" name="category_id" value="<?php echo $edit_id; ?>">
        
        Tên loại: <input type="text" name="cat_name" value="<?php echo $edit_name; ?>" required><br><br>
        Mô tả:<br>
        <textarea name="cat_description" rows="3" style="width: 400px;"><?php echo $edit_desc; ?></textarea><br><br>
        
        <button type="submit" name="btn_save_cat"><?php echo ($edit_id != "") ? "Cập nhật" : "Thêm mới"; ?></button>
        <?php if($edit_id != "") echo " <a href='index.php?module=category'>Hủy bỏ</a>"; ?>
    </form>
</fieldset>

<br>
<table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Tên loại</th>
        <th>Mô tả</th>
        <th>Số lượng truyện</th>
        <th>Hành động</th>
    </tr>
    <?php
    while($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
                <td align='center'>{$row['category_id']}</td>
                <td><b>{$row['name']}</b></td>
                <td><small>{$row['description']}</small></td>
                <td align='center'><b>{$row['total_stories']}</b> truyện</td>
                <td align='center'>
                    <a href='index.php?module=category&edit_cat={$row['category_id']}'>Sửa</a> |
                    <a href='index.php?module=category&delete_cat={$row['category_id']}' 
                       onclick='return confirm(\"Xóa loại này sẽ mất phân loại của các truyện liên quan. Tiếp tục?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
</table>