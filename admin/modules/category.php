<?php
if (isset($_POST['btn_save_cat'])) {
    $c_id = $_POST['category_id'];
    $name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['cat_description']);
    if ($c_id == "") {
        $sql = "INSERT INTO category (name, description) VALUES ('$name', '$desc')";
    } else {
        $sql = "UPDATE category SET name='$name', description='$desc' WHERE category_id = $c_id";
    }
    if (mysqli_query($conn, $sql)) {
        echo "<script>window.location='index.php?module=category';</script>";
    }
}

if (isset($_GET['delete_cat'])) {
    $id = (int)$_GET['delete_cat'];
    mysqli_query($conn, "DELETE FROM stories_category WHERE category_id = $id");
    mysqli_query($conn, "DELETE FROM category WHERE category_id = $id");
    echo "<script>window.location='index.php?module=category';</script>";
}

$edit_id = ""; $edit_name = ""; $edit_desc = "";
if (isset($_GET['edit_cat'])) {
    $id = (int)$_GET['edit_cat'];
    $res_edit = mysqli_query($conn, "SELECT * FROM category WHERE category_id = $id");
    $row_edit = mysqli_fetch_assoc($res_edit);
    $edit_id = $row_edit['category_id'];
    $edit_name = $row_edit['name'];
    $edit_desc = $row_edit['description'];
}

$sql = "SELECT c.category_id, c.name, c.description, COUNT(sc.stories_id) AS total_stories 
        FROM category c 
        LEFT JOIN stories_category sc ON c.category_id = sc.category_id 
        GROUP BY c.category_id, c.name, c.description
        ORDER BY c.category_id DESC";
$res = mysqli_query($conn, $sql);
$total_types = mysqli_num_rows($res);
?>

<div class="page-header">
    <div>
        <h2>Thể loại truyện</h2>
        <p class="page-sub">Tổng cộng <?php echo $total_types; ?> thể loại</p>
    </div>
</div>

<!-- Form thêm/sửa -->
<div class="form-section">
    <div class="form-section-title">
        <?php echo ($edit_id != "") ? "✏️ Sửa thể loại" : "➕ Thêm thể loại mới"; ?>
    </div>
    <form method="POST">
        <input type="hidden" name="category_id" value="<?php echo $edit_id; ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Tên thể loại <span class="required">*</span></label>
                <input type="text" name="cat_name" value="<?php echo htmlspecialchars($edit_name); ?>" required placeholder="VD: Tiểu thuyết, Phiêu lưu...">
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <input type="text" name="cat_description" value="<?php echo htmlspecialchars($edit_desc); ?>" placeholder="Mô tả ngắn về thể loại...">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="btn_save_cat" class="btn btn-primary">
                <?php echo ($edit_id != "") ? "💾 Cập nhật" : "➕ Thêm mới"; ?>
            </button>
            <?php if ($edit_id != ""): ?>
            <a href="index.php?module=category" class="btn btn-outline">Hủy bỏ</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Danh sách -->
<div class="table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thể loại</th>
                <th>Mô tả</th>
                <th class="center">Số truyện</th>
                <th class="center">Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($res) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td class="muted">#<?php echo $row['category_id']; ?></td>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td class="muted"><?php echo htmlspecialchars($row['description']); ?></td>
                <td class="center">
                    <span class="badge badge-pink"><?php echo $row['total_stories']; ?> truyện</span>
                </td>
                <td class="center">
                    <div class="table-actions" style="justify-content:center;">
                        <a href="index.php?module=category&edit_cat=<?php echo $row['category_id']; ?>" class="btn btn-outline btn-sm">✏️ Sửa</a>
                        <a href="index.php?module=category&delete_cat=<?php echo $row['category_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa thể loại này sẽ mất phân loại của các truyện liên quan. Tiếp tục?')">🗑️ Xóa</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="table-empty">Chưa có thể loại nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
