<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- ----search---- -->
    <?php
    // Cek apakah halaman saat ini adalah 'setting.php dan booking.php'
    if (basename($_SERVER['PHP_SELF']) !== 'setting.php' && basename($_SERVER['PHP_SELF']) !== 'booking.php'): ?>
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
            <!-- Mengecek apakah foto profil ada di session -->
            <?php if (isset($_SESSION["photo"]) && !empty($_SESSION["photo"])): ?>
                <!-- Jika ada foto profil, tampilkan foto tersebut -->
                <img src="<?php echo htmlspecialchars($_SESSION["photo"]); ?>" alt="User Profile Picture">
            <?php else: ?>
                <!-- Jika tidak ada foto, tampilkan foto default -->
                <img src="http://localhost/Mechaban-Web/assets/img/user_profile.png" alt="Default User Picture"
                    class="user-img">
            <?php endif; ?>
        </div>

        <div class="user-info">
            <div class="username">
                <span class="name"><?php echo $_SESSION["name"]; ?></span>
                <span class="role"><?php echo $_SESSION["role"]; ?></span>
            </div>
        </div>
    </div>
</div>