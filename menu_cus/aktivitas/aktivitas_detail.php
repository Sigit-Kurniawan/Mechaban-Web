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

// Mendapatkan ID booking dari URL
if (!isset($_GET['id_booking'])) {
    die("ID booking tidak ditemukan.");
}
$id_booking = $_GET['id_booking']; // Dapatkan ID booking dari parameter URL

// Query untuk mendapatkan data dari view_detail_booking
$detail_aktivitas = $conn->prepare("
    SELECT 
        tgl_booking, 
        id_booking, 
        nopol, 
        latitude,
        longitude,
        servis,
        harga_servis,
        total_biaya,
        status,
        ketua_montir,
        anggota_montir,
        review,
        rating
    FROM view_rincian_booking
    WHERE id_booking = ?
");

// Bind parameter dan eksekusi query
$detail_aktivitas->bind_param("s", $id_booking);
$detail_aktivitas->execute();
$result_detail_aktivitas = $detail_aktivitas->get_result();

// Cek apakah ada hasil
if ($result_detail_aktivitas->num_rows > 0) {
    // Ambil data dari view
    $row = $result_detail_aktivitas->fetch_assoc();

    // Data servis (nama dan harga dalam bentuk array)
    $servis_list = array_map(
        null,
        explode(', ', $row['servis']),
        explode(', ', $row['harga_servis'])
    );

    // Data booking umum
    $data_booking = [
        'tgl_booking' => $row['tgl_booking'],
        'id_booking' => $row['id_booking'],
        'nopol' => $row['nopol'],
        'total_biaya' => $row['total_biaya'],
        'status' => $row['status'],
    ];

    // Nama ketua dan anggota montir
    $ketua_montir = $row['ketua_montir'];
    $anggota_montir = $row['anggota_montir'];
} else {
    die("Data booking tidak ditemukan.");
}
?>




<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="aktivitas.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
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

            <div class="detail-servis">
                <div class="title-detail">
                    <h1>Detail Servis</h1>
                </div>

                <div class="info-umum">
                    <!-- Info Umum -->
                    <h3>Informasi Umum</h3>
                    <table class="info-umum">
                        <tbody>
                            <tr>
                                <th>Tanggal</th>
                                <?php
                                // Membuat objek DateTime dari tgl_booking
                                $tgl_booking = new DateTime($data_booking['tgl_booking']);
                                ?>
                                <td><?php echo $tgl_booking->format('d-m-y H:i:s'); ?></td>
                            </tr>
                            <tr>
                                <th>ID Booking</th>
                                <td><?php echo htmlspecialchars($data_booking['id_booking']); ?></td>
                            </tr>
                            <tr>
                                <th>No. Polisi</th>
                                <td><?php echo htmlspecialchars($data_booking['nopol']); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>


                <!-- Rincian Servis -->
                <div class="rincian-servis">
                    <h3>Rincian Servis</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Servis</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no_servis = 1;
                            foreach ($servis_list as $servis) { ?>
                                <tr>
                                    <td><?php echo $no_servis++; ?></td>
                                    <td><?php echo htmlspecialchars($servis[0]); ?></td>
                                    <td>Rp <?php echo number_format($servis[1], 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>





                <div class="total-status">
                    <!-- Total Biaya -->
                    <h3>Total dan Status</h3>
                    <table class="total-info">
                        <tbody>
                            <tr>
                                <th>Total Biaya</th>
                                <td>Rp <?php echo number_format($data_booking['total_biaya'], 0, ',', '.'); ?></td>
                            </tr>

                            <tr>
                                <th>Status Pengerjaan</th>
                                <td><?php echo ucfirst(htmlspecialchars($data_booking['status'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <!-- Detail Montir -->
                <div class="detail-montir">
                    <h3>Montir</h3>
                    <table>
                        <tr>
                            <th>Nama Ketua Montir</th>
                            <th>Nama Anggota Montir</th>
                        </tr>

                        <?php
                        // Cek apakah ketua montir dan anggota montir ada
                        if (empty($ketua_montir) && empty($anggota_montir)) {
                            // Jika ketua montir dan anggota montir kosong, tampilkan pesan "Sedang Menunggu Montir"
                            echo "<tr><td colspan='2'>Sedang Menunggu Montir</td></tr>";
                        } else {
                            // Jika montir sudah ditugaskan, tampilkan nama ketua dan anggota montir
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($ketua_montir) . "</td>";

                            // Cek jika ada anggota montir
                            if (isset($anggota_montir) && !empty($anggota_montir)) {
                                $anggota_montir_array = explode(', ', $anggota_montir);
                                echo "<td><ol>";
                                foreach ($anggota_montir_array as $index => $anggota) {
                                    echo "<li>" . htmlspecialchars($anggota) . "</li>";
                                }
                                echo "</ol></td>";
                            } else {
                                echo "<td>-</td>";
                            }

                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>


                <!-- RATING -->
                <div class="review">
                    <h3>Review</h3> <!-- Tetap di dalam div.review -->

                    <?php if (!empty($row['review']) && !empty($row['rating'])): ?>
                        <div class="review-container">
                            <div class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $row['rating'] ? '<span class="star filled">★</span>' : '<span class="star">☆</span>';
                                }
                                ?>
                            </div>
                            <textarea class="review-box" readonly><?php echo htmlspecialchars($row['review']); ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                      <div class="kembali">
                          <a href="aktivitas.php" class="btn-kembali">Kembali</a>
                          <?php if ($data_booking['status'] === 'selesai' && empty($row['review'])): ?>
                              <a href="review/review.php?id_booking=<?php echo $data_booking['id_booking']; ?>" class="btn-review">Review</a>
                          <?php endif; ?>
                      </div>
                </div>

            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>