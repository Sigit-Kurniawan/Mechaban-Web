<?php
session_start();

// Validasi apakah user sudah login dan memiliki email di session
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek keberadaan file koneksi
if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');

// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];

$query_data_montir = "SELECT a.name, a.no_hp FROM account a WHERE a.role = 'montir'";

// Eksekusi query
$result = mysqli_query($conn, $query_data_montir);

// Cek apakah query berhasil
if (!$result) {
    echo "Query gagal: " . mysqli_error($conn);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="help.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>

        <div class="main">
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
                            ? '../../uploads/' . htmlspecialchars($_SESSION["photo"])
                            : '../../assets/img/default-profile.png';
                        ?>
                        <img src="<?php echo $userPhoto; ?>" alt="User Profile Picture" class="user-img"
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

            <div class="konten-help">

                <div class="header-konten">
                    <h2>Anda butuh bantuan? Silakan baca panduan di bawah ini.</h2>
                </div>

                <div class="konten">
                    <div class="pertanyaan">
                        <p>Bagaimana Cara Menambahkan Mobil?</p>
                    </div>
                    <div class="jawaban">
                        <div class="isi-jawaban">
                            <ul>
                                <li>Klik menu mobil</li>
                                <li>Klik tambah mobil</li>
                                <li>Isi data mobil dengan benar</li>
                                <li>Klik simpan data</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="konten">
                    <div class="pertanyaan">
                        <p>Bagaimana Cara Melakukan Booking?</p>
                    </div>
                    <div class="jawaban">
                        <div class="isi-jawaban">
                            <ul>
                                <li>Klik menu booking</li>
                                <li>Pilih mobil yang akan diservis</li>
                                <li>Pilih jenis servis</li>
                                <li>Pilih lokasi anda saat ini</li>
                                <li>Klik Booking</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="konten">
                    <div class="pertanyaan">
                        <p>Bagaimana Cara Mengubah Password?</p>
                    </div>
                    <div class="jawaban">
                        <div class="isi-jawaban">
                            <ul>
                                <li>Klik menu setting</li>
                                <li>Klik edit akun</li>
                                <li>Masukkan password baru</li>
                                <li>Klik simpan data</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tabel montir -->
                <div class="header-konten">
                    <h3>Anda ingin berkonsultasi? Hubungi nomor di bawah ini.</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Montir</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan data montir dalam tabel
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>


            </div>





        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="/Mechaban-Web/assets/js/main.js"></script>
    <script src="help.js"></script>
</body>

</html>