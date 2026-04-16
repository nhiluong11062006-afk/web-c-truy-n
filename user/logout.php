<?php
session_start();
session_destroy();
// Sau khi thoát, nhảy ra ngoài về trang chủ
header("Location: ../index.php");
exit();
?>