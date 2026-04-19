<?php 
include '../connect.php'; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Truyện Hay</title>
    <link rel="stylesheet" href="../style.css?v=2">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:2rem;">

    <div class="form-box">
        <h2>Đăng Nhập</h2>
        <p class="subtitle">— Truyện Hay —</p>

        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" name="btn_login">Vào đọc truyện</button>
        </form>

        <div class="link-row">
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
                    $_SESSION['user_id']   = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role']      = $user['role'];
                    header("Location: ../index.php");
                    exit();
                } else {
                    echo "<p class='msg-error'>Sai mật khẩu!</p>";
                }
            } else {
                echo "<p class='msg-error'>Tài khoản không tồn tại!</p>";
            }
        }
        ?>
    </div>

</body>
</html>