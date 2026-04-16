<?php 
include '../connect.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-box { width: 350px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #214b62; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #214b62; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Đăng nhập</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="btn_login">Vào đọc truyện</button>
        </form>
        <div style="text-align: center; margin-top: 15px; font-size: 14px;">
            Chưa có tài khoản? <a href="register.php">Đăng ký</a>
        </div>

        <?php
        if (isset($_POST['btn_login'])) {
            $u = mysqli_real_escape_string($conn, $_POST['username']);
            $p = $_POST['password'];

            $res = mysqli_query($conn, "SELECT * FROM user WHERE username = '$u'");
            if (mysqli_num_rows($res) > 0) {
                $user = mysqli_fetch_assoc($res);
                if (password_verify($p, $user['password'])) {
                    // Lưu thông tin vào Session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // QUAN TRỌNG: Nhảy ra ngoài thư mục gốc để về trang chủ
                    header("Location: ../index.php"); 
                    exit();
                } else {
                    echo "<p style='color:red; text-align:center;'>Sai mật khẩu!</p>";
                }
            } else {
                echo "<p style='color:red; text-align:center;'>Tài khoản không tồn tại!</p>";
            }
        }
        ?>
    </div>
</body>
</html>