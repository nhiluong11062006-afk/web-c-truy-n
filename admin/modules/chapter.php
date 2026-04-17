<?php
// 1. Lấy ID truyện từ URL
$stories_id = isset($_GET['stories_id']) ? $_GET['stories_id'] : '';

if ($stories_id == '') {
    echo "<h3>Vui lòng chọn một bộ truyện từ <a href='index.php?module=story'>Quản lý truyện</a> để làm việc!</h3>";
} else {
    // Lấy tên truyện hiển thị tiêu đề
    $st_res = mysqli_query($conn, "SELECT title FROM stories WHERE stories_id = $stories_id");
    $st_data = mysqli_fetch_assoc($st_res);

    // 2. XỬ LÝ LƯU (CẢ THÊM MỚI VÀ CẬP NHẬT)
    if (isset($_POST['btn_save_chapter'])) {
        $c_id = $_POST['chapter_id']; // Nếu trống là thêm mới, có ID là sửa
        $c_num = $_POST['chapter_number'];
        $c_title = mysqli_real_escape_string($conn, $_POST['title']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);

        if ($c_id == "") {
            // THÊM MỚI
            $sql = "INSERT INTO chapter (stories_id, chapter_number, title, content) 
                    VALUES ('$stories_id', '$c_num', '$c_title', '$content')";
            $msg = "Đã thêm chương mới!";
        } else {
            // CẬP NHẬT
            $sql = "UPDATE chapter SET chapter_number='$c_num', title='$c_title', content='$content' 
                    WHERE chapter_id = $c_id";
            $msg = "Đã cập nhật chương!";
        }
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('$msg'); window.location='index.php?module=chapter&stories_id=$stories_id';</script>";
        }
    }

    // 3. XỬ LÝ XÓA
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $id_del = $_GET['id'];
        mysqli_query($conn, "DELETE FROM chapter WHERE chapter_id = $id_del");
        echo "<script>window.location='index.php?module=chapter&stories_id=$stories_id';</script>";
    }

    // 4. LẤY DỮ LIỆU CŨ KHI BẤM "SỬA"
    $edit_data = ['chapter_id' => '', 'chapter_number' => '', 'title' => '', 'content' => ''];
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        $id_edit = $_GET['id'];
        $res_edit = mysqli_query($conn, "SELECT * FROM chapter WHERE chapter_id = $id_edit");
        $edit_data = mysqli_fetch_assoc($res_edit);
    }
?>

    <hr>
    <h3>QUẢN LÝ CHƯƠNG: <span style="color:blue;"><?php echo $st_data['title']; ?></span></h3>
    <a href="index.php?module=story"><- Quay lại danh sách truyện</a>
    <br><br>

    <fieldset>
        <legend><b><?php echo ($edit_data['chapter_id'] != '') ? "Sửa chương" : "Thêm chương mới"; ?></b></legend>
        <form method="POST">
            <input type="hidden" name="chapter_id" value="<?php echo $edit_data['chapter_id']; ?>">
            
            Số chương: <input type="number" name="chapter_number" required 
                             value="<?php echo $edit_data['chapter_number']; ?>" style="width: 50px;">
            
            Tiêu đề chương: <input type="text" name="title" required style="width: 300px;" 
                                  value="<?php echo $edit_data['title']; ?>" placeholder="Tên chương..."><br><br>
            
            Nội dung chương:<br>
            <textarea name="content" rows="15" style="width: 100%;" required><?php echo $edit_data['content']; ?></textarea><br><br>
            
            <button type="submit" name="btn_save_chapter">Lưu chương</button>
            <?php if($edit_data['chapter_id'] != '') echo " <a href='index.php?module=chapter&stories_id=$stories_id'>Hủy bỏ</a>"; ?>
        </form>
    </fieldset>

    <hr>
    <h4>Danh sách chương hiện có:</h4>
    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
        <tr style="background: #eee;">
            <th width="10%">Chương số</th>
            <th>Tiêu đề chương</th>
            <th width="20%">Hành động</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM chapter WHERE stories_id = $stories_id ORDER BY chapter_number ASC");
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td align='center'>{$row['chapter_number']}</td>
                        <td>{$row['title']}</td>
                        <td align='center'>
                            <a href='index.php?module=chapter&stories_id=$stories_id&action=edit&id={$row['chapter_id']}'>Sửa</a> | 
                            <a href='index.php?module=chapter&stories_id=$stories_id&action=delete&id={$row['chapter_id']}' 
                               onclick='return confirm(\"Xóa chương này?\")' style='color:red;'>Xóa</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3' align='center'>Truyện này chưa có chương nào.</td></tr>";
        }
        ?>
    </table>

<?php } ?>