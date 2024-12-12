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
            <?php
            // Determine the photo path
            $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                ? '../uploads/' . htmlspecialchars($_SESSION["photo"])
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