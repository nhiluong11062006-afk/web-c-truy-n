<h3>QUẢN LÝ LOẠI TRUYỆN</h3>

<?php
// Tính tổng số lượng thể loại
$res_count = mysqli_query($conn, "SELECT * FROM category");
$total_cats = mysqli_num_rows($res_count);
?>
<p><b>Tổng số thể loại hiện có: <?php echo $total_cats; ?></b></p>

<form method="POST">
    <input type="text" name="cat_name" placeholder="Tên loại truyện mới" required>
    <button type="submit" name="add_cat">Thêm mới</button>
</form>

<br>
<table border="1" width="50%" cellpadding="5" style="border-collapse: collapse;">
    <tr style="background: #eee;">
        <th>ID</th>
        <th>Tên loại</th>
        <th>Hành động</th>
    </tr>
    <?php
    // Reset lại kết quả để lặp hiển thị bảng
    mysqli_data_seek($res_count, 0); 
    while($row = mysqli_fetch_assoc($res_count)) {
        echo "<tr>
                <td align='center'>{$row['category_id']}</td>
                <td>{$row['name']}</td>
                <td align='center'>
                    <a href='index.php?module=category&delete_cat={$row['category_id']}' onclick='return confirm(\"Xóa thể loại này?\")'>Xóa</a>
                </td>
              </tr>";
    }
    ?>
</table>