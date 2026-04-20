<?php
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM stories_category WHERE stories_id = $id");
    mysqli_query($conn, "DELETE FROM chapter WHERE stories_id = $id");
    mysqli_query($conn, "DELETE FROM stories WHERE stories_id = $id");
    echo "<script>alert('Đã xóa!'); window.location='index.php?module=story';</script>";
}

if (isset($_POST['btn_save'])) {
    $title  = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $desc   = mysqli_real_escape_string($conn, $_POST['description']);
    $id     = isset($_POST['id']) ? $_POST['id'] : '';

    if ($_FILES['image']['name'] != "") {
        $img = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img);
    } else {
        $img = $_POST['old_image'];
    }

    if ($id == "") {
        $sql = "INSERT INTO stories (title, author, description, image, view_count) VALUES ('$title', '$author', '$desc', '$img', 0)";
        mysqli_query($conn, $sql);
        $story_id = mysqli_insert_id($conn);
    } else {
        $sql = "UPDATE stories SET title='$title', author='$author', description='$desc', image='$img' WHERE stories_id = $id";
        mysqli_query($conn, $sql);
        $story_id = $id;
        mysqli_query($conn, "DELETE FROM stories_category WHERE stories_id = $story_id");
    }

    if (isset($_POST['cat_list'])) {
        foreach ($_POST['cat_list'] as $cat_id) {
            mysqli_query($conn, "INSERT INTO stories_category (stories_id, category_id) VALUES ($story_id, $cat_id)");
        }
    }
    echo "<script>alert('Thành công!'); window.location='index.php?module=story';</script>";
}

$row_old = ['stories_id'=>'','title'=>'','author'=>'','description'=>'','image'=>'','cat_ids'=>[]];
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = (int)$_GET['id'];
    $res = mysqli_query($conn, "SELECT * FROM stories WHERE stories_id = $id");
    $row_old = mysqli_fetch_assoc($res);
    $res_c = mysqli_query($conn, "SELECT category_id FROM stories_category WHERE stories_id = $id");
    while ($c = mysqli_fetch_assoc($res_c)) { $row_old['cat_ids'][] = $c['category_id']; }
}

$is_edit = ($row_old['stories_id'] != '');
?>

<div class="page-header">
    <div>
        <h2><?php echo $is_edit ? "Sửa truyện #" . $row_old['stories_id'] : "Quản lý Truyện"; ?></h2>
        <p class="page-sub"><?php echo $is_edit ? htmlspecialchars($row_old['title']) : "Thêm mới hoặc chỉnh sửa truyện"; ?></p>
    </div>
</div>

<!-- Form -->
<div class="form-section">
    <div class="form-section-title">
        <?php echo $is_edit ? " Sửa thông tin truyện" : " Thêm truyện mới"; ?>
    </div>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row_old['stories_id']; ?>">
        <input type="hidden" name="old_image" value="<?php echo $row_old['image']; ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Tên truyện <span class="required">*</span></label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row_old['title']); ?>" required placeholder="Nhập tên truyện...">
            </div>
            <div class="form-group">
                <label>Tác giả</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($row_old['author']); ?>" placeholder="Tên tác giả...">
            </div>
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" rows="4" placeholder="Giới thiệu nội dung truyện..."><?php echo htmlspecialchars($row_old['description']); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ảnh bìa</label>
                <input type="file" name="image" accept="image/*">
                <?php if ($row_old['image']): ?>
                <div class="img-preview-wrap" style="margin-top:0.5rem;">
                    <img src="../uploads/<?php echo htmlspecialchars($row_old['image']); ?>" alt="Ảnh bìa hiện tại">
                    <small style="color:var(--text-dim);font-size:0.75rem;margin-top:0.25rem;display:block;">Ảnh hiện tại — chọn file mới để thay</small>
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Thể loại</label>
                <div class="checkbox-group">
                <?php
                $res_cat = mysqli_query($conn, "SELECT * FROM category");
                while ($cat = mysqli_fetch_assoc($res_cat)):
                    $checked = in_array($cat['category_id'], $row_old['cat_ids']) ? 'checked' : '';
                ?>
                    <label class="checkbox-item">
                        <input type="checkbox" name="cat_list[]" value="<?php echo $cat['category_id']; ?>" <?php echo $checked; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </label>
                <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" name="btn_save" class="btn btn-primary"> Lưu thông tin</button>
            <?php if ($is_edit): ?>
            <a href="index.php?module=story" class="btn btn-outline">Hủy sửa</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Danh sách -->
<div class="card-header" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg) var(--radius-lg) 0 0;padding:1rem 1.25rem;">
    <h3>Danh sách truyện</h3>
</div>
<div class="table-wrap" style="border-radius:0 0 var(--radius-lg) var(--radius-lg);border-top:none;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bìa</th>
                <th>Tên truyện</th>
                <th class="center">Lượt đọc</th>
                <th>Thể loại</th>
                <th class="center">Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res_list = mysqli_query($conn, "SELECT * FROM stories ORDER BY stories_id DESC");
        if (mysqli_num_rows($res_list) > 0):
            while ($row = mysqli_fetch_assoc($res_list)):
                $s_id = $row['stories_id'];
                $res_my_cat = mysqli_query($conn, "SELECT category.name FROM stories_category 
                    JOIN category ON stories_category.category_id = category.category_id 
                    WHERE stories_category.stories_id = $s_id");
                $cat_names = [];
                while ($c = mysqli_fetch_assoc($res_my_cat)) { $cat_names[] = $c['name']; }
        ?>
            <tr>
                <td class="muted">#<?php echo $s_id; ?></td>
                <td><img class="table-thumb" src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt=""></td>
                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong><br><small style="color:var(--text-dim)"><?php echo htmlspecialchars($row['author']); ?></small></td>
                <td class="center"><?php echo number_format($row['view_count']); ?></td>
                <td>
                    <div class="tag-list">
                        <?php foreach ($cat_names as $cn): ?>
                        <span class="badge badge-pink"><?php echo htmlspecialchars($cn); ?></span>
                        <?php endforeach; ?>
                    </div>
                </td>
                <td class="center">
                    <div class="table-actions" style="justify-content:center;">
                        <a href="index.php?module=chapter&stories_id=<?php echo $s_id; ?>" class="btn btn-outline btn-sm">📄 Chương</a>
                        <a href="index.php?module=story&action=edit&id=<?php echo $s_id; ?>" class="btn btn-outline btn-sm">✏️ Sửa</a>
                        <a href="index.php?module=story&action=delete&id=<?php echo $s_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa truyện này và toàn bộ chương?')">🗑️</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="6" class="table-empty">Chưa có truyện nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
