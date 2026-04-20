<?php
$stories_id = isset($_GET['stories_id']) ? (int)$_GET['stories_id'] : 0;

if ($stories_id == 0) {
    echo "<div class='alert alert-warning'>⚠️ Vui lòng chọn một bộ truyện từ <a href='index.php?module=story'>Quản lý truyện</a> để làm việc!</div>";
} else {
    $st_res  = mysqli_query($conn, "SELECT title FROM stories WHERE stories_id = $stories_id");
    $st_data = mysqli_fetch_assoc($st_res);

    if (isset($_POST['btn_save_chapter'])) {
        $c_id    = $_POST['chapter_id'];
        $c_num   = (int)$_POST['chapter_number'];
        $c_title = mysqli_real_escape_string($conn, $_POST['title']);
        $content = mysqli_real_escape_string($conn, $_POST['content']);

        if ($c_id == "") {
            $sql = "INSERT INTO chapter (stories_id, chapter_number, title, content) VALUES ('$stories_id', '$c_num', '$c_title', '$content')";
            $msg = "Đã thêm chương mới!";
        } else {
            $sql = "UPDATE chapter SET chapter_number='$c_num', title='$c_title', content='$content' WHERE chapter_id = $c_id";
            $msg = "Đã cập nhật chương!";
        }
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('$msg'); window.location='index.php?module=chapter&stories_id=$stories_id';</script>";
        }
    }

    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $id_del = (int)$_GET['id'];
        mysqli_query($conn, "DELETE FROM chapter WHERE chapter_id = $id_del");
        echo "<script>window.location='index.php?module=chapter&stories_id=$stories_id';</script>";
    }

    $edit_data = ['chapter_id' => '', 'chapter_number' => '', 'title' => '', 'content' => ''];
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        $id_edit   = (int)$_GET['id'];
        $res_edit  = mysqli_query($conn, "SELECT * FROM chapter WHERE chapter_id = $id_edit");
        $edit_data = mysqli_fetch_assoc($res_edit);
    }

    $is_edit = ($edit_data['chapter_id'] != '');
?>

<div class="page-header">
    <div>
        <h2>Quản lý Chương</h2>
        <p class="page-sub"><?php echo htmlspecialchars($st_data['title']); ?></p>
    </div>
    <a href="index.php?module=story" class="btn btn-outline">← Danh sách truyện</a>
</div>

<div class="form-section">
    <div class="form-section-title">
        <?php echo $is_edit ? "✏️ Sửa chương" : "➕ Thêm chương mới"; ?>
    </div>
    <form method="POST">
        <input type="hidden" name="chapter_id" value="<?php echo $edit_data['chapter_id']; ?>">
        <div class="form-row triple">
            <div class="form-group">
                <label>Số chương <span class="required">*</span></label>
                <input type="number" name="chapter_number" required value="<?php echo $edit_data['chapter_number']; ?>" placeholder="1">
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Tiêu đề chương <span class="required">*</span></label>
                <input type="text" name="title" required value="<?php echo htmlspecialchars($edit_data['title']); ?>" placeholder="Tên chương...">
            </div>
        </div>
        <div class="form-group content-editor">
            <label>Nội dung chương <span class="required">*</span></label>
            <textarea name="content" rows="16" required placeholder="Nhập nội dung chương tại đây..."><?php echo htmlspecialchars($edit_data['content']); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" name="btn_save_chapter" class="btn btn-primary">💾 Lưu chương</button>
            <?php if ($is_edit): ?>
            <a href="index.php?module=chapter&stories_id=<?php echo $stories_id; ?>" class="btn btn-outline">Hủy bỏ</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Danh sách chương -->
<div class="table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th class="center" style="width:80px;">Chương</th>
                <th>Tiêu đề</th>
                <th class="center">Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM chapter WHERE stories_id = $stories_id ORDER BY chapter_number ASC");
        if (mysqli_num_rows($res) > 0):
            while ($row = mysqli_fetch_assoc($res)):
        ?>
            <tr>
                <td class="center"><span class="badge badge-pink">Chương <?php echo $row['chapter_number']; ?></span></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td class="center">
                    <div class="table-actions" style="justify-content:center;">
                        <a href="index.php?module=chapter&stories_id=<?php echo $stories_id; ?>&action=edit&id=<?php echo $row['chapter_id']; ?>" class="btn btn-outline btn-sm">✏️ Sửa</a>
                        <a href="index.php?module=chapter&stories_id=<?php echo $stories_id; ?>&action=delete&id=<?php echo $row['chapter_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa chương này?')">🗑️ Xóa</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="3" class="table-empty">Truyện này chưa có chương nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php } ?>
