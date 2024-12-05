<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/logo.png" type="image/png">
    <title>Mechaban - Data Servis</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="servis.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="user">
                    <div class="user-img-container">
                        <?php
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
                            <span class="name"><?php echo htmlspecialchars($_SESSION["name"]); ?></span>
                            <span class="role"><?php echo htmlspecialchars($_SESSION["role"]); ?></span>
                        </div>
                    </div>
                </div>

                <div id="photoModal" class="modal">
                    <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                    <div class="photo-modal-content">
                        <img id="modalPhoto" src="" alt="Enlarged photo">
                    </div>
                </div>
            </div>

            <div class="view">
                <div class="cardHeader">
                    <h2>DAFTAR SERVIS</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Data Servis</th>
                            <th>Nama Servis</th>
                            <th>Harga</th>
                            <th>ID Komponen</th>
                            <th>Nama Komponen</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>