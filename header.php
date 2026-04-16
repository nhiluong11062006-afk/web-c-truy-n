<?php include 'connect.php'; ?>
<style>
    /* ... (Giữ nguyên các CSS cũ của bạn) ... */
    .navbar { background-color: #70c1e1; display: flex; align-items: center; padding: 10px 5%; color: white; font-family: Arial, sans-serif; position: relative; }
    .nav-brand { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-right: 30px; text-decoration: none; color: white; }
    .nav-menu { display: flex; list-style: none; margin: 0; padding: 0; flex-grow: 1; }
    .nav-item { position: relative; padding: 10px 15px; cursor: pointer; }
    .dropdown-content { display: none; position: absolute; background-color: #f9f9f9; min-width: 200px; box-shadow: 0px 8px 16px rgba(0,0,0,0.2); z-index: 1000; top: 100%; left: 0; border-radius: 4px; }
    .dropdown-content a { color: #333; padding: 12px 16px; text-decoration: none; display: block; }
    .dropdown-content a:hover { background-color: #ddd; }
    .nav-item:hover .dropdown-content { display: block; }

    .search-container { position: relative; }
    .search-box { display: flex; background: white; border-radius: 4px; padding: 5px; }
    .search-box input { border: none; padding: 5px; outline: none; width: 200px; color: #333; }
    .search-box button { background: none; border: none; cursor: pointer; padding: 0 5px; }

    #search-results { position: absolute; top: 100%; left: 0; right: 0; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1001; display: none; border-radius: 0 0 4px 4px; overflow: hidden; }
    #search-results a { display: block; padding: 10px 15px; color: #333; text-decoration: none; border-bottom: 1px solid #eee; font-size: 14px; }
    #search-results a:hover { background: #f1f1f1; }
    #search-results a.view-more { background: #eee; font-weight: bold; text-align: center; color: #214b62; }

    /* CSS MỚI CHO PHẦN ĐĂNG NHẬP / ĐĂNG KÝ */
    .user-area {
        margin-left: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 14px;
        white-space: nowrap; /* Tránh việc bị nhảy dòng */
    }
    .user-area a {
        color: white;
        text-decoration: none;
    }
    .user-area a:hover {
        color: #ffc107; /* Màu vàng khi di chuột vào */
    }
    .user-area .user-name {
        color: #fff183;
        font-weight: bold;
    }
</style>

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