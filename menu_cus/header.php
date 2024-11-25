<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- ----search---- -->
    <?php
    // Cek apakah halaman saat ini adalah 'setting.php dan booking.php'
    if (
        basename($_SERVER['PHP_SELF']) !== 'setting.php' && basename($_SERVER['PHP_SELF']) !== 'booking.php' && basename($_SERVER['PHP_SELF']) !== 'aktivitas.php'
        && basename($_SERVER['PHP_SELF']) !== 'aktivitas_detail.php'
    ): ?>
        <div class="search">
            <form action="mobil.php" method="GET"> <!-- Form menuju ke halaman yang sama -->
                <label>
                    <input type="text" name="search" placeholder="Search here....."
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <ion-icon name="search-outline"></ion-icon>
                </label>
            </form>
        </div>
    <?php endif; ?>


    <!-- ----user img---- -->
    <div class="user">

        <div class="user-img-container">
            <?php
            // Determine the photo path
            $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                ? '../../uploads/' . htmlspecialchars($_SESSION["photo"])
                : '../assets/img/default-profile.png';
            ?>
            <img src="<?php echo $userPhoto; ?>"
                alt="User Profile Picture"
                class="user-img"
                onclick="showPhotoModal('<?php echo $userPhoto; ?>')">

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