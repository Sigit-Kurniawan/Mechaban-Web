<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

include_once('../../Api/koneksi.php');

$email_customer = $_SESSION["email"];


function formatNopol($nopol)
{
    if (preg_match('/^([A-Za-z]{1,2})(\d{3,4})([A-Za-z]{1,2})$/', $nopol, $matches)) {
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }
    return $nopol; // Jika tidak cocok dengan format, tampilkan apa adanya
}


// Query untuk mendapatkan data booking yang sedang diproses
$query_sedang_proses = "SELECT b.id_booking, b.tgl_booking, b.total_biaya, b.status, b.nopol, 
                               GROUP_CONCAT(s.nama_servis SEPARATOR ', ') AS servis
                        FROM booking b
                        left JOIN detail_servis ds ON b.id_booking = ds.id_booking
                        left JOIN data_servis s ON ds.id_data_servis = s.id_data_servis
                        left JOIN car c ON b.nopol = c.nopol
                        WHERE c.email_customer = '$email_customer' 
                        AND  b.status IN ('pending', 'diterima', 'dikerjakan')
                        GROUP BY b.id_booking ORDER BY b.tgl_booking DESC";

$result_sedang_proses = mysqli_query($conn, $query_sedang_proses);



// Query untuk mendapatkan riwayat transaksi yang sudah selesai
$query_riwayat_selesai = "SELECT b.id_booking,  b.tgl_booking, b.total_biaya,  b.status, b.nopol, 
                            GROUP_CONCAT(DISTINCT s.nama_servis SEPARATOR ', ') AS servis
                            FROM booking b
                            left JOIN detail_servis ds ON b.id_booking = ds.id_booking
                            left JOIN data_servis s ON ds.id_data_servis = s.id_data_servis
                            left JOIN car c ON b.nopol = c.nopol
                            WHERE c.email_customer = '$email_customer' 
                            AND b.status in ('selesai', 'batal')
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
                                <th>Pengerjaan</th>
                                <th>Total(Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = mysqli_fetch_assoc($result_sedang_proses)): ?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime($booking['tgl_booking'])); ?></td>
                                <td><?php echo formatNopol($booking['nopol']); ?></td> <!-- Terapkan formatNopol -->
                                <td>
                                    <?php
                                        // Pastikan bahwa servis tidak null
                                        $servis = isset($booking['servis']) ? $booking['servis'] : '';
                                        $servis_array = explode(', ', $servis);
                                        $unique_servis = array_unique($servis_array); // Menghapus duplikasi
                                        echo implode(', ', $unique_servis); // Menampilkan servis yang unik
                                        ?>
                                </td>
                                <td class="status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucwords($booking['status']); ?>
                                </td>
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
                <div class="booking-selesai">
                    <div class="cardHeader">
                        <h2>Riwayat Transaksi</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nopol</th>
                                <th>Servis</th>
                                <th>Pengerjaan</th>
                                <th>Total(Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = mysqli_fetch_assoc($result_riwayat_selesai)): ?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime($booking['tgl_booking'])); ?></td>
                                <td><?php echo formatNopol($booking['nopol']); ?></td> <!-- Terapkan formatNopol -->
                                <td>
                                    <?php
                                        // Pastikan bahwa servis tidak null
                                        $servis = isset($booking['servis']) ? $booking['servis'] : '';
                                        $servis_array = explode(', ', $servis);
                                        $unique_servis = array_unique($servis_array); // Menghapus duplikasi
                                        echo implode(', ', $unique_servis); // Menampilkan servis yang unik
                                        ?>
                                </td>
                                <td class="status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucwords($booking['status']); ?>
                                </td>
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
    <script src="../../assets/js/main.js"></script>
</body>

</html>