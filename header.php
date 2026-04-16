<?php include 'connect.php'; ?>


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