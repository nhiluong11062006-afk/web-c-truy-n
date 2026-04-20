<?php 
session_start(); // CỰC KỲ QUAN TRỌNG: Phải có dòng này ở đầu tiên để dùng được $_SESSION
include '../connect.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Truyện Hay</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-page"> <div class="form-box">
        <h2>Đăng nhập</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="btn_login">Vào đọc truyện</button>
        </form>
        <div style="text-align: center; margin-top: 15px; font-size: 14px;">
            Chưa có tài khoản? <a href="register.php" style="color: #d45c82; font-weight: bold;">Đăng ký</a>
        </div>

        <?php
        if (isset($_POST['btn_login'])) {
            $u = mysqli_real_escape_string($conn, $_POST['username']);
            $p = $_POST['password'];

            $res = mysqli_query($conn, "SELECT * FROM user WHERE username = '$u'");
            if (mysqli_num_rows($res) > 0) {
                $user = mysqli_fetch_assoc($res);
                if (password_verify($p, $user['password'])) {
                    // Lưu thông tin vào Session để các trang khác (như header) dùng được
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // KIỂM TRA QUYỀN ĐỂ ĐIỀU HƯỚNG
                    if ($_SESSION['role'] == 1) {
                        // Nếu là Admin -> Nhảy vào trang quản trị
                        header("Location: ../admin/index.php");
                    } else {
                        // Nếu là User -> Nhảy ra trang chủ
                        header("Location: ../index.php"); 
                    }
                    exit();
                } else {
                    echo "<p style='color:red; text-align:center; margin-top:10px;'>Sai mật khẩu!</p>";
                }
            } else {
                echo "<p style='color:red; text-align:center; margin-top:10px;'>Tài khoản không tồn tại!</p>";
            }
        }
        ?>
    </div>
</body>
</html>