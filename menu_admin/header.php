<div class="header">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    <!-- ----search---- -->
    <div class="search">
        <form action="" method="get">
            <label>
                <input type="text" name="search" placeholder="Search here....."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                    autocomplete="off">
                <button type="submit" class="search-button">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
                <?php if (!empty($_GET['search'])): ?>
                <a href="<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>" class="clear-search">
                    &times;
                </a>
                <?php endif; ?>
            </label>
        </form>
    </div>

    <!-- ----user img---- -->
    <div class="user">
        <div class="user-img-container">
            <?php
            // Determine the photo path
            $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                ? '../../uploads/' . htmlspecialchars($_SESSION["photo"])
                : '../../assets/img/default-profile.png';
            ?>
            <img src="<?php echo $userPhoto; ?>" alt="User Profile Picture" class="user-img"
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

    <!-- Photo Modal (can be added to the bottom of your page) -->
    <div id="photoModal" class="modal">
        <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
        <div class="photo-modal-content">
            <img id="modalPhoto" src="" alt="Enlarged photo">
        </div>
    </div>
</div>