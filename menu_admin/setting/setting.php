<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');

// Define upload directory (make sure this matches the one in upload_photo.php)
define('UPLOAD_DIR', '../../uploads/customers/');

// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];

// Query untuk mengambil data akun berdasarkan email
$query = "SELECT name, email, no_hp, photo FROM account WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email_customer);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Data akun tidak ditemukan.";
    exit();
}

// Prepare photo URL
$photo_url = !empty($user['photo']) ? UPLOAD_DIR . htmlspecialchars($user['photo']) : '../../assets/img/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="http://localhost/Mechaban-Web/assets/img/logo.png" type="image/png">
    <title>Mechaban - Edit Profil</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="setting.css">
</head>

<body>

    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>



            <div class="view">
                <!-- Error/Success Alerts -->
                <?php if (isset($_GET['error'])): ?>
                    <div id="error-alert" class="error-alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <div id="success-alert" class="success-alert">
                        <?php 
                        switch ($_GET['success']) {
                            case 'upload':
                                echo "Foto profil berhasil diperbarui.";
                                break;
                            case 'save':
                                echo "Profil berhasil diperbarui.";
                                break;
                            default:
                                echo "Operasi berhasil.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="akun-view">
                    <div class="cardHeader">
                        <h2>Informasi Akun</h2>
                    </div>
                    <div class="view-informasi-akun">
                        <div class="photo-upload-container">
                            <img id="photoPreview" 
                                 src="<?php echo $photo_url; ?>" 
                                 alt="Profile preview" 
                                 class="profile-img"
                                 onclick="showPhotoModal(this.src)">
                            
                            <form id="photoForm" action="upload_photo.php" method="post" enctype="multipart/form-data" class="upload-form">
                                <div class="file-input-container">
                                    <label for="photo">
                                        <ion-icon name="camera"></ion-icon> 
                                        Pilih Foto
                                    </label>
                                    <span class="file-input-text">Tidak ada file dipilih</span>
                                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" style="display:none;">
                                </div>
                                <div class="file-hints">
                                    Format: JPG, JPEG, PNG (Maks. 2MB)
                                </div>
                                <button type="submit" name="upload" class="upload-btn" disabled>Upload Foto</button>
                            </form>
                        </div>

                        <!-- Rest of your existing form fields -->
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" class="input-field" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" class="input-field" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="no_hp">No. HP</label>
                            <input type="text" id="phone" class="input-field" value="<?php echo htmlspecialchars($user['no_hp']); ?>" disabled>
                        </div>

                        <div class="button-container">
                            <button class="edit-akun" id="myBtn">Edit Akun</button>
                            <form action="delete_account.php" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun?');">
                                <button type="submit" class="delete-account-btn">Hapus Akun</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Modal -->
            <div id="photoModal" class="modal">
                <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                <div class="photo-modal-content">
                    <img id="modalPhoto" src="" alt="Enlarged photo">
                </div>
            </div>

            <!-- Modal Edit Akun -->
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div class="akun-edit">
                        <h2 id="modalTitle">Form Edit Profil</h2>

                        <div class="form">
                            <form id="formAkun" action="setting_process.php" method="post">
                                <div class="formLabel">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" id="name"
                                        value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="formLabel">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email"
                                        value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="formLabel">
                                    <label for="no_hp">No Hp</label>
                                    <input type="text" name="no_hp" id="no_hp"
                                        value="<?php echo htmlspecialchars($user['no_hp']); ?>" required>
                                </div>
                                <div class="formLabel">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password">
                                    <small>* Kosongkan jika tidak ingin mengubah password</small>
                                </div>

                                <input type="hidden" name="edit_email" id="edit_email">
                                <div class="input">
                                    <input type="submit" name="simpan" value="Simpan Data" class="btn">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="setting.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>