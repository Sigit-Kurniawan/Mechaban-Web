<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Periksa file koneksi database
if (!file_exists('../Api/koneksi.php')) {
    die("File koneksi database tidak ditemukan.");
}
include_once('../Api/koneksi.php');

// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];


function formatNopol($nopol)
{
    if (preg_match('/^([A-Za-z]{1,2})(\d{3,4})([A-Za-z]{1,2})$/', $nopol, $matches)) {
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }
    return $nopol; // Jika tidak cocok dengan format, tampilkan apa adanya
}

// Query untuk menghitung jumlah mobil
$query_jmlh_mobil = "SELECT COUNT(nopol) AS jumlah_mobil FROM car WHERE email_customer = ?";
$stmt = $conn->prepare($query_jmlh_mobil);
$stmt->bind_param("s", $email_customer); // Mengikat parameter (email_customer)
$stmt->execute();
$stmt->bind_result($jumlah_mobil);
$stmt->fetch();
$jumlah_mobil = $jumlah_mobil ?? 0;
$stmt->close();

// Query untuk menghitung mobil yang sedang diservis
$query_mobil_diservis = "
    SELECT COUNT(*) as sedang_proses 
    FROM booking b
    JOIN car c ON b.nopol = c.nopol 
    WHERE c.email_customer = ? 
    AND b.status IN ('pending', 'diterima', 'dikerjakan')
";
$stmt = $conn->prepare($query_mobil_diservis);
$stmt->bind_param("s", $email_customer);
$stmt->execute();
$stmt->bind_result($sedang_proses);
$stmt->fetch();
$sedang_proses = $sedang_proses ?? 0;
$stmt->close();

// Query untuk mendapatkan daftar mobil yang sedang diservis
$query_list_mobil = "
    SELECT 
        b.tgl_booking, 
        b.nopol, 
        b.status, 
        b.total_biaya,  
        GROUP_CONCAT(DISTINCT s.nama_servis SEPARATOR ', ') AS servis
    FROM booking b
    JOIN car c ON b.nopol = c.nopol
    LEFT JOIN detail_servis ds ON b.id_booking = ds.id_booking
    LEFT JOIN data_servis s ON ds.id_data_servis = s.id_data_servis
    WHERE c.email_customer = ?
    AND b.status IN ('pending', 'diterima', 'dikerjakan')
    GROUP BY b.id_booking
    order by tgl_booking desc;
";
$stmt = $conn->prepare($query_list_mobil);
$stmt->bind_param("s", $email_customer);
$stmt->execute();
$result_mobil_diservis = $stmt->get_result();
$stmt->close();


// Query untuk mendapatkan status bengkel
$query_status = "SELECT status_bengkel FROM status LIMIT 1";
$result_status = $conn->query($query_status);
$status_bengkel = $result_status->fetch_assoc()['status_bengkel'] ?? 0; // Default ke 0 jika tidak ada hasil
$status_bengkel_text = ($status_bengkel == 1) ? "Buka" : "Tutup";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style_customer.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <div class="main">

            <?php include 'header.php'; ?>

            <div class="row-status-reservasi">
                <div class="status-bengkel">
                    <h3>Status Bengkel: <span class="status-<?php echo strtolower($status_bengkel_text); ?>">
                            <?php echo $status_bengkel_text; ?>
                        </span></h3>
                </div>
                <div class="button">
                    <a href="/booking/booking.php">
                        <button class="btn-reservasi">Booking Sekarang</button>
                    </a>
                </div>
            </div>

            <div class="cardbox">
                <div class="card" onclick="location.href='/mobil/mobil.php'">
                    <div>
                        <div class="angka">
                            <?php echo htmlspecialchars($jumlah_mobil); ?>
                        </div>
                        <div class="cardname">Mobil</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="car-sport-outline"></ion-icon>
                    </div>
                </div>

                <div class="card" onclick="location.href='/aktivitas/aktivitas.php'">
                    <div>
                        <div class="angka"><?php echo htmlspecialchars($sedang_proses); ?></div>
                        <div class="cardname">Sedang Diservis</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="build-outline"></ion-icon>
                    </div>
                </div>


            </div>

            <div class="detail_mobil_diservis">
                <div class="recentOrder">
                    <div class="cardtitle">
                        <h2>Mobil Diservis</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nopol</th>
                                <th>Servis</th>
                                <th>Status</th>
                                <th>Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($mobil = $result_mobil_diservis->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo formatNopol($mobil['nopol']); ?></td> <!-- Terapkan formatNopol -->
                                    <td><?php echo htmlspecialchars($mobil['servis']); ?></td>
                                    <td class="status-<?php echo strtolower($mobil['status']); ?>">
                                        <?php echo ucwords($mobil['status']); ?>
                                    </td>
                                    <td><?php echo number_format($mobil['total_biaya'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>