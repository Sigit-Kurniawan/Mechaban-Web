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

// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];

//Tampilkan mobil
$query_mobil = "SELECT nopol, merk FROM car WHERE email_customer = '$email_customer'";
$result_mobil = mysqli_query($conn, $query_mobil);
if (!$result_mobil) {
    die("Query error: " . mysqli_error($conn));
}

// Ambil data komponen dan data servis
$query_komponen = "SELECT * FROM data_komponen";
$result_komponen = mysqli_query($conn, $query_komponen);
if (!$result_komponen) {
    die("Query error: " . mysqli_error($conn));
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
    <link rel="stylesheet" href="http://localhost/Mechaban-Web/menu_cus/booking/booking.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async
        defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body>
    <div class="container">
        <?php include '../sidebar.php' ?>

        <div class="main">
            <?php include '../header.php'; ?>

            <form method="POST" action="booking_process.php" class="booking-input">
                <div class="header-booking">
                    <h2>Booking Servis</h2>
                </div>

                <!-- Step 1: Select Mobil -->
                <div class="mobil">
                    <label>Mobil</label><br>
                    <select name="nopol" id="mobil" class="mobil-combobox" required>
                        <option value="" disabled selected>Pilih Mobil</option>
                        <?php while ($mobil = mysqli_fetch_assoc($result_mobil)): ?>
                            <option value="<?php echo $mobil['nopol']; ?>">
                                <?php echo $mobil['nopol'] . ' - ' . $mobil['merk']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Step 2: Select Komponen and Servis -->
                <div class="komponen-container">
                    <label>Servis</label><br>
                    <select name="komponen" class="komponen-combobox" required>
                        <option value="" disabled selected>Pilih Servis</option>
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

                <!-- Step 3: Metode Pembayaran (Auto filled to "Tunai") -->


                <!-- Step 4: Submit Button -->
                <div class="submit-button">
                    <button type="submit" name="submit_booking">Booking</button>
                </div>
            </form>

            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
            <script src="http://localhost/Mechaban-Web/menu_cus/booking/booking.js"></script>

</body>

</html>