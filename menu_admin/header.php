<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- ----search---- -->
    <div class="search">
        <label>
            <input type="text" placeholder="Search here.....">
            <ion-icon name="search-outline"></ion-icon>
        </label>
    </div>
    <!-- ----user img---- -->
    <div class="user">
        <div class="user-img-container">
            <?php if (isset($_SESSION["photo"]) && !empty($_SESSION["photo"])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION["photo"]); ?>" alt="User Profile Picture">
            <?php else: ?>
                <img src="../assets/img/user.png" alt="Default User Picture">
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