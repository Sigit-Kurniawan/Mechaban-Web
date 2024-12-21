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

$email_customer = $_SESSION["email"];

// Fungsi untuk memformat nomor polisi
function formatNopol($nopol)
{
    if (preg_match('/^([A-Za-z]{1,2})(\d{3,4})([A-Za-z]{1,2})$/', $nopol, $matches)) {
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }
    return $nopol; // Jika tidak cocok dengan format, tampilkan apa adanya
}

// Mengambil data mobil yang belum dibooking atau status pengerjaannya selesai
$query_mobil = "
    SELECT nopol, merk 
    FROM car 
    WHERE email_customer = '$email_customer' 
    AND nopol NOT IN (
        SELECT nopol 
        FROM booking 
        WHERE status NOT IN ('selesai', 'batal') 
        AND nopol IS NOT NULL
    )
";
$result_mobil = mysqli_query($conn, $query_mobil);
if (!$result_mobil) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil data komponen
$query_komponen = "SELECT * FROM data_komponen";
$result_komponen = mysqli_query($conn, $query_komponen);
if (!$result_komponen) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil data lokasi dari URL (jika ada)
$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : null;
$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : null;
$address = isset($_GET['address']) ? $_GET['address'] : 'Alamat tidak ditemukan'; // Default jika tidak ada alamat
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="booking.css">

    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer>
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body>
    <div class="container">
        <?php include '../sidebar.php' ?>

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

            <form method="POST" action="booking_process.php" class="booking-input">
                <div class="header-booking">
                    <h2>Booking Servis</h2>
                </div>

                <div class="lokasi">
                    <label>Lokasi Anda</label><br>
                    <a href="lokasi.php">Pilih Lokasi</a>
                    <?php if ($latitude && $longitude): ?>
                    <div class="alamat-box">
                        <?php echo $address ? ucwords(htmlspecialchars($address)) : 'Lokasi tidak ditemukan'; ?><br>
                    </div>
                    <input type="hidden" name="latitude" value="<?php echo $latitude; ?>">
                    <input type="hidden" name="longitude" value="<?php echo $longitude; ?>">
                    <?php else: ?>
                    <div class="alamat-box">
                        Lokasi belum dipilih.
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Step 1: Pilih Mobil -->
                <div class="mobil">
                    <label>Mobil</label><br>
                    <select name="nopol" id="mobil" class="mobil-combobox" required>
                        <option value="" disabled selected>Pilih Mobil</option>
                        <?php while ($mobil = mysqli_fetch_assoc($result_mobil)): ?>
                        <option value="<?php echo $mobil['nopol']; ?>">
                            <?php echo formatNopol($mobil['nopol']) . ' - ' . $mobil['merk']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>

                </div>
                

                <!-- Step 2: Pilih Komponen dan Servis -->
                <div class="komponen-container">
                    <label>Servis</label><br>
                    <select name="komponen" class="komponen-combobox" required>
                        <option value="" disabled selected>Pilih Komponen</option>
                        <?php while ($komponen = mysqli_fetch_assoc($result_komponen)): ?>
                        <option value="<?php echo $komponen['id_data_komponen']; ?>">
                            <?php echo $komponen['nama_komponen']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>

                    <!-- Servis list -->
                    <?php
                    mysqli_data_seek($result_komponen, 0); // Reset result set pointer
                    while ($komponen = mysqli_fetch_assoc($result_komponen)):
                        $id_data_komponen = mysqli_real_escape_string($conn, $komponen['id_data_komponen']);
                        $query_servis = "SELECT * FROM data_servis WHERE id_data_komponen = '$id_data_komponen'";
                        $result_servis = mysqli_query($conn, $query_servis);
                        ?>
                    <div class="servis-container" data-komponen="<?php echo $komponen['id_data_komponen']; ?>">
                        <?php if ($result_servis && mysqli_num_rows($result_servis) > 0): ?>
                        <?php while ($servis = mysqli_fetch_assoc($result_servis)): ?>
                        <div class="servis-item">
                            <label>
                                <input type="checkbox" class="servis-checkbox" name="servis[]"
                                    value="<?php echo $servis['id_data_servis']; ?>">
                                <?php echo $servis['nama_servis']; ?>
                            </label>
                            <span
                                class="servis-price">Rp<?php echo number_format($servis['harga_servis'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <p>Tidak ada servis untuk komponen ini.</p>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Step 3: Lokasi -->

                <!-- Step 4: Submit Button -->
                <div class="submit-button">
                    <button type="submit" name="submit_booking">Booking</button>
                </div>
            </form>

            <div class="alert-container" id="alertContainer"></div>

            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
            <script src="booking.js"></script>
            <script src="../../assets/js/main.js"></script>

</body>

</html>