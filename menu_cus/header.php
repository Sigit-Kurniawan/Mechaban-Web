<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- ----search---- -->
    <div class="search">
        <form action="mobil.php" method="GET"> <!-- Form menuju ke halaman yang sama -->
            <label>
                <input type="text" name="search" placeholder="Search here....."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <ion-icon name="search-outline"></ion-icon>
            </label>
        </form>
    </div>
    <!-- ----user img---- -->
    <div class="user">
        <div class="user-img-container">
            <img class="user-img"
                src="<?php echo !empty($_SESSION['photo']) ? $_SESSION['photo'] : '../assets/img/user_profile.png'; ?>"
                alt="User Profile Picture">
            <div class="user-status"></div>
        </div>

        <div class="user-info">
            <div class="username">
                <span class="name"><?php echo $_SESSION["name"]; ?></span>
                <span class="role"><?php echo $_SESSION["role"]; ?></span>
            </div>
        </div>
    </div>
</div>