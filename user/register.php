<?php 
session_start(); 
include '../connect.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản - Truyện Hay</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-page"> <div class="form-box">
        <h2>Đăng ký thành viên</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <button type="submit" name="btn_register">Tạo tài khoản</button>
        </form>
        
        <div style="text-align: center; margin-top: 15px; font-size: 14px;">
            Đã có tài khoản? <a href="login.php" style="color: #d45c82; font-weight: bold;">Đăng nhập</a>
        </div>

        <?php
        if (isset($_POST['btn_register'])) {
            $u = mysqli_real_escape_string($conn, $_POST['username']);
            // Băm mật khẩu để bảo mật 
            $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $f = mysqli_real_escape_string($conn, $_POST['full_name']);

            // Kiểm tra trùng tên đăng nhập
            $check = mysqli_query($conn, "SELECT * FROM user WHERE username = '$u'");
            if (mysqli_num_rows($check) > 0) {
                echo "<p style='color:red; text-align:center; margin-top:10px;'>Tên đăng nhập đã tồn tại!</p>";
            } else {
                // Mặc định role = 0 cho người dùng thường
                $sql = "INSERT INTO user (username, password, full_name, role) VALUES ('$u', '$p', '$f', 0)";
                if (mysqli_query($conn, $sql)) {
                    echo "<p style='color:green; text-align:center; margin-top:10px;'>Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a></p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>