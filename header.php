<?php include 'connect.php'; ?>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    
    <!-- QUAN TRỌNG: Dòng này giúp web hiển thị đúng trên điện thoại -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <title>Truyện Hay</title>
</head>
<body>
<div class="navbar">
    <a href="index.php" class="nav-brand">Truyện Hay</a>

    <ul class="nav-menu">
        <li class="nav-item">
            Danh sách ▼
            <div class="dropdown-content">
                <?php
                $sql_cat = "SELECT * FROM category";
                $res_cat = mysqli_query($conn, $sql_cat);
                while($cat = mysqli_fetch_assoc($res_cat)) {
                    echo '<a href="category.php?id='.$cat['category_id'].'">'.$cat['name'].'</a>';
                }
                ?>
            </div>
        </li>
    </ul>

    <div class="search-container">
        <form action="search.php" method="GET" class="search-box">
            <input type="text" id="search-input" name="keyword" placeholder="Tìm kiếm truyện..." autocomplete="off">
            <button type="submit">🔍</button>
        </form>
        <div id="search-results"></div>
    </div>

    <div class="user-area">
        <?php if(isset($_SESSION['user_id'])): ?>
            <span>Chào, <span class="user-name"><?php echo $_SESSION['full_name']; ?></span></span>
            
            <?php if($_SESSION['role'] == 1): ?>
                <a href="admin/index.php" style="background: #d9534f; padding: 5px 10px; border-radius: 3px;">Quản trị</a>
            <?php endif; ?>
            
            <a href="user/logout.php">Thoát</a>
        <?php else: ?>
            <a href="user/login.php">Đăng nhập</a>
            <span>|</span>
            <a href="user/register.php">Đăng ký</a>
        <?php endif; ?>
    </div>
</div>

<script>
    /* ... (Giữ nguyên JS tìm kiếm cũ của bạn) ... */
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        let query = this.value;
        if (query.length > 0) {
            fetch('ajax_search.php?keyword=' + query)
                .then(response => response.text())
                .then(data => {
                    searchResults.innerHTML = data;
                    searchResults.style.display = 'block';
                });
        } else {
            searchResults.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
</script>