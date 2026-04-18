<h3>Danh sách thành viên</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Tên đăng nhập</th>
        <th>Họ tên</th>
        <th>Quyền</th>
    </tr>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM user");
    while($row = mysqli_fetch_assoc($res)) {
        $role_text = ($row['role'] == 1) ? "<b>Admin</b>" : "Thành viên";
        echo "<tr>
                <td>{$row['user_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['full_name']}</td>
                <td>$role_text</td>
              </tr>";
    }
    ?>
</table>