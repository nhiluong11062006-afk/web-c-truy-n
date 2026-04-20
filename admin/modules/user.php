<?php
// 1. TRUY VẤN DANH SÁCH THÀNH VIÊN
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
                </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($res) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($res)): 
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
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="table-empty">Không có thành viên nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>