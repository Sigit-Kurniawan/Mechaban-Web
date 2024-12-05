<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} // Memulai session hanya jika belum ada session aktif
?>
<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>


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
                <span class="name">
                    <?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Guest'; ?>
                </span>
                <span class="role">
                    <?php echo isset($_SESSION["role"]) ? htmlspecialchars($_SESSION["role"]) : 'Visitor'; ?>
                </span>
            </div>
        </div>
    </div>
</div>