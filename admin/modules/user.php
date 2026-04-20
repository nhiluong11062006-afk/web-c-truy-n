<?php
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res_u = mysqli_query($conn, "SELECT active FROM user WHERE user_id = $id");
    if ($row_u = mysqli_fetch_assoc($res_u)) {
        $new_active = ($row_u['active'] == 1) ? 0 : 1;
        mysqli_query($conn, "UPDATE user SET active = $new_active WHERE user_id = $id");
    }
    echo "<script>window.location='index.php?module=user';</script>";
}

$res   = mysqli_query($conn, "SELECT * FROM user ORDER BY user_id DESC");
$total = mysqli_num_rows($res);
?>

<div class="page-header">
    <div>
        <h2>Quản lý Thành viên</h2>
        <p class="page-sub">Tổng cộng <?php echo $total; ?> tài khoản</p>
    </div>
</div>

<div class="table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Họ tên</th>
                <th class="center">Quyền</th>
                <th class="center">Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($res) > 0):
            while ($row = mysqli_fetch_assoc($res)):
                $is_admin = ($row['role'] == 1);
        ?>
            <tr>
                <td class="muted">#<?php echo $row['user_id']; ?></td>
                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['full_name'] ?? '—'); ?></td>
                <td class="center">
                    <?php if ($is_admin): ?>
                        <span class="badge badge-admin"> Admin</span>
                    <?php else: ?>
                        <span class="badge badge-member"> Thành viên</span>
                    <?php endif; ?>
                </td>
                <td class="center">
                    <?php if (!$is_admin): ?>
                    <a href="index.php?module=user&action=toggle&id=<?php echo $row['user_id']; ?>" 
                       class="btn btn-outline btn-sm"
                       onclick="return confirm('Thay đổi trạng thái tài khoản này?')">
                        Khoá / Mở
                    </a>
                    <?php else: ?>
                    <span class="muted" style="font-size:0.78rem;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; else: ?>
            <tr><td colspan="5" class="table-empty">Không có thành viên nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
