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
            <?php if (isset($_SESSION["photo"]) && !empty($_SESSION["photo"])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION["photo"]); ?>" alt="User Profile Picture">
            <?php else: ?>
                <img src="/project3/assets/img/user.png" alt="Default User Picture" class="user-img">
            <?php endif; ?>

            <div class="user-status <?php echo ($_SESSION["is_online"]) ? 'online' : 'offline'; ?>"></div>
        </div>

        <div class="user-info">
            <div class="username">
                <span class="name"><?php echo $_SESSION["name"]; ?></span>
                <span class="role"><?php echo $_SESSION["role"]; ?></span>
            </div>
        </div>
    </div>
</div>