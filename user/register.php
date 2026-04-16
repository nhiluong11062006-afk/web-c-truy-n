<?php 

include '../connect.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-box { width: 350px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #214b62; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #5cb85c; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .msg { text-align: center; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Đăng ký thành viên</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <button type="submit" name="btn_register">Tạo tài khoản</button>
        </form>
        <div class="msg">Đã có tài khoản? <a href="login.php">Đăng nhập</a></div>

        <?php
        if (isset($_POST['btn_register'])) {
            $u = mysqli_real_escape_string($conn, $_POST['username']);
            $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $f = mysqli_real_escape_string($conn, $_POST['full_name']);

            $check = mysqli_query($conn, "SELECT * FROM user WHERE username = '$u'");
            if (mysqli_num_rows($check) > 0) {
                echo "<p style='color:red; text-align:center;'>Tên đăng nhập đã tồn tại!</p>";
            } else {
                // Mặc định role = 0 cho người dùng thường
                $sql = "INSERT INTO user (username, password, full_name, role) VALUES ('$u', '$p', '$f', 0)";
                if (mysqli_query($conn, $sql)) {
                    echo "<p style='color:green; text-align:center;'>Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a></p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>