<?php
// 1. Lấy ID truyện từ URL (Ví dụ: index.php?module=chapter&stories_id=5)
$stories_id = isset($_GET['stories_id']) ? $_GET['stories_id'] : '';

if ($stories_id == '') {
    echo "<h3>Vui lòng chọn một bộ truyện từ menu <a href='index.php?module=story'>Quản lý truyện</a> để xem chương!</h3>";
} else {
    // Lấy tên truyện để hiển thị cho đỡ nhầm
    $sql_name = mysqli_query($conn, "SELECT title FROM stories WHERE stories_id = $stories_id");
    $story_data = mysqli_fetch_assoc($sql_name);
    $story_title = $story_data['title'];

    // 2. XỬ LÝ THÊM CHƯƠNG MỚI
    if (isset($_POST['btn_add_chapter'])) {
        $chapter_num = $_POST['chapter_number'];
        $chapter_title = mysqli_real_escape_string($conn, $_POST['title']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);

        $sql_ins = "INSERT INTO chapter (stories_id, chapter_number, title, content) 
                    VALUES ('$stories_id', '$chapter_num', '$chapter_title', '$content')";
        
        if (mysqli_query($conn, $sql_ins)) {
            echo "<b style='color:green;'>Đã đăng chương $chapter_num thành công!</b>";
        }
    }

    // 3. XỬ LÝ XÓA CHƯƠNG
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $id_del = $_GET['id'];
        mysqli_query($conn, "DELETE FROM chapter WHERE chapter_id = $id_del");
        echo "<script>window.location='index.php?module=chapter&stories_id=$stories_id';</script>";
    }
?>

    <hr>
    <h3>QUẢN LÝ CHƯƠNG: <?php echo $story_title; ?></h3>
    <a href="index.php?module=story"><- Quay lại danh sách truyện</a>
    <br><br>

    <fieldset>
        <legend>Thêm chương mới</legend>
        <form method="POST">
            Số chương: <input type="number" name="chapter_number" required placeholder="VD: 1"><br><br>
            Tiêu đề chương: <input type="text" name="title" required style="width: 300px;" placeholder="VD: Sự khởi đầu"><br><br>
            Nội dung chương:<br>
            <textarea name="content" rows="15" style="width: 100%;" required placeholder="Dán nội dung truyện vào đây..."></textarea><br><br>
            <button type="submit" name="btn_add_chapter">Đăng chương</button>
        </form>
    </fieldset>

    <hr>
    <h4>Danh sách chương hiện có:</h4>
    <table border="1" width="100%">
        <tr style="background: #eee;">
            <th>Thứ tự</th>
            <th>Tiêu đề chương</th>
            <th>Hành động</th>
        </tr>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM chapter WHERE stories_id = $stories_id ORDER BY chapter_number ASC");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<tr>
                    <td align='center'>Chương {$row['chapter_number']}</td>
                    <td>{$row['title']}</td>
                    <td align='center'>
                        <a href='index.php?module=chapter&stories_id=$stories_id&action=delete&id={$row['chapter_id']}' onclick='return confirm(\"Xóa chương này?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

<?php } // Đóng ngoặc else check stories_id ?>