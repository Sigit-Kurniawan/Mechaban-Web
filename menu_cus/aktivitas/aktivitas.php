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


// Query untuk mendapatkan data booking yang sedang diproses
$query_sedang_proses = "SELECT b.id_booking, b.tgl_booking, b.total_biaya, b.metode_bayar, b.status_bayar, b.status_pengerjaan, b.nopol, 
                               GROUP_CONCAT(s.nama_servis SEPARATOR ', ') AS servis,
                               GROUP_CONCAT( br.nama_barang SEPARATOR ', ') AS barang
                        FROM booking b
                        left JOIN detail_booking db ON b.id_booking = db.id_booking
                        left JOIN detail_servis_booking dsb ON db.id_detail_booking = dsb.id_detail_booking
                        left JOIN data_servis s ON dsb.id_data_servis = s.id_data_servis
                         left JOIN detail_barang_booking dbb ON db.id_detail_booking = dbb.id_detail_booking
                         left JOIN barang br ON dbb.id_barang = br.id_barang
                       left JOIN car c ON b.nopol = c.nopol
                        WHERE c.email_customer = '$email_customer' 
                        AND (
                            (b.status_bayar = 'sudah' AND b.status_pengerjaan IN ('pending', 'diterima', 'dikerjakan'))
                            or (b.status_bayar = 'belum' AND b.status_pengerjaan IN ('pending', 'diterima', 'dikerjakan'))
                            OR (b.status_bayar = 'belum' AND b.status_pengerjaan = 'selesai')
                        )
                        GROUP BY b.id_booking
                        ORDER BY b.tgl_booking DESC";

$result_sedang_proses = mysqli_query($conn, $query_sedang_proses);



// Query untuk mendapatkan riwayat transaksi yang sudah selesai
$query_riwayat_selesai = "SELECT 
                               b.id_booking,  b.tgl_booking, b.total_biaya, b.metode_bayar, b.status_bayar,  b.status_pengerjaan, b.nopol, 
                      
                               GROUP_CONCAT(DISTINCT br.nama_barang SEPARATOR ', ') AS barang,
                               GROUP_CONCAT(DISTINCT s.nama_servis SEPARATOR ', ') AS servis
                           FROM booking b
                           left JOIN detail_booking db ON b.id_booking = db.id_booking
                           left JOIN detail_servis_booking dsb ON db.id_detail_booking = dsb.id_detail_booking
                           left JOIN data_servis s ON dsb.id_data_servis = s.id_data_servis
                           left JOIN detail_barang_booking dbb ON db.id_detail_booking = dbb.id_detail_booking
                           left JOIN barang br ON dbb.id_barang = br.id_barang
                           left JOIN car c ON b.nopol = c.nopol
                           WHERE c.email_customer = '$email_customer' 
                           AND b.status_bayar = 'sudah' 
                           AND b.status_pengerjaan = 'selesai'
                           GROUP BY b.id_booking
                           ORDER BY b.tgl_booking DESC";
$result_riwayat_selesai = mysqli_query($conn, $query_riwayat_selesai);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="aktivitas.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>

        <div class="main">
            <?php include '../header.php'; ?>


            <div class="view">
                <!-- Sedang Proses -->
                <div class="booking-sedang-proses">
                    <div class="cardHeader">
                        <h2>Sedang Proses</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nopol</th>
                                <th>Servis</th>
                                <th>Barang</th>
                                <th>Bayar</th>
                                <th>Pengerjaan</th>
                                <th>Total(Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = mysqli_fetch_assoc($result_sedang_proses)): ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($booking['tgl_booking'])); ?></td>
                                    <td><?php echo $booking['nopol']; ?></td>
                                    <td>
                                        <?php
                                        // Pastikan bahwa servis tidak null
                                        $servis = isset($booking['servis']) ? $booking['servis'] : '';
                                        $servis_array = explode(', ', $servis);
                                        $unique_servis = array_unique($servis_array); // Menghapus duplikasi
                                        echo implode(', ', $unique_servis); // Menampilkan servis yang unik
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        // Pastikan bahwa barang tidak null
                                        $barang = isset($booking['barang']) ? $booking['barang'] : '';
                                        $barang_array = explode(', ', $barang);
                                        $unique_barang = array_unique($barang_array); // Menghapus duplikasi
                                        echo ($unique_barang[0] != '') ? implode(', ', $unique_barang) : '-'; // Menampilkan barang yang unik
                                        ?>
                                    </td>

                                    <td><?php echo ucwords($booking['status_bayar']); ?></td>
                                    <td><?php echo ucwords($booking['status_pengerjaan']); ?></td>
                                    <td><?php echo number_format($booking['total_biaya'], 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="aktivitas_detail.php?id_booking=<?php echo $booking['id_booking']; ?>"
                                            class="btn-detail">Detail</a>

                                        <a href="batal_booking.php?id_booking=<?php echo $booking['id_booking']; ?>"
                                            class="btn-batal"
                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan servis ini?');">Batal</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Riwayat Transaksi -->
                <div class=" booking-selesai">
                    <div class="cardHeader">
                        <h2>Riwayat Transaksi</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nopol</th>
                                <th>Servis</th>
                                <th>Barang</th>
                                <th>Bayar</th>
                                <th>Pengerjaan</th>
                                <th>Total(Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = mysqli_fetch_assoc($result_riwayat_selesai)): ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($booking['tgl_booking'])); ?></td>
                                    <td><?php echo $booking['nopol']; ?></td>
                                    <td>
                                        <?php
                                        // Pastikan bahwa servis tidak null
                                        $servis = isset($booking['servis']) ? $booking['servis'] : '';
                                        $servis_array = explode(', ', $servis);
                                        $unique_servis = array_unique($servis_array); // Menghapus duplikasi
                                        echo implode(', ', $unique_servis); // Menampilkan servis yang unik
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        // Pastikan bahwa barang tidak null
                                        $barang = isset($booking['barang']) ? $booking['barang'] : '';
                                        $barang_array = explode(', ', $barang);
                                        $unique_barang = array_unique($barang_array); // Menghapus duplikasi
                                        echo ($unique_barang[0] != '') ? implode(', ', $unique_barang) : '-'; // Menampilkan barang yang unik
                                        ?>
                                    </td>

                                    <td><?php echo ucwords($booking['status_bayar']); ?></td>
                                    <td><?php echo ucwords($booking['status_pengerjaan']); ?></td>
                                    <td><?php echo number_format($booking['total_biaya'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <a href="aktivitas_detail.php?id_booking=<?php echo $booking['id_booking']; ?>"
                                            class="btn-detail">Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>




    </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="\Mechaban-Web\assets\js\main.js"></script>
</body>

</html>