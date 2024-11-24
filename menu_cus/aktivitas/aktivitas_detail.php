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

// Query untuk mendapatkan data booking, servis, dan barang
$detail_sedang_proses = $conn->prepare("
    SELECT 
        b.tgl_booking, 
        b.id_booking, 
        b.nopol, 
        GROUP_CONCAT(DISTINCT s.nama_servis ORDER BY s.nama_servis SEPARATOR ', ') AS servis,
        GROUP_CONCAT(DISTINCT s.harga_servis ORDER BY s.nama_servis SEPARATOR ', ') AS harga_servis,
        br.nama_barang, 
        dbb.jumlah_barang, 
        br.harga AS harga_barang,
        (dbb.jumlah_barang * br.harga) AS subtotal_barang,
        b.total_biaya,
        b.metode_bayar,
        b.status_bayar,
        b.status_pengerjaan,
        GROUP_CONCAT(DISTINCT km.name ORDER BY km.name SEPARATOR ', ') AS ketua_montir,  -- Nama ketua montir dari tabel account
        GROUP_CONCAT(DISTINCT am_montir.name ORDER BY am_montir.name SEPARATOR ', ') AS anggota_montir  -- Nama anggota montir diambil dari tabel account
    FROM booking b
    LEFT JOIN detail_booking db ON b.id_booking = db.id_booking
    LEFT JOIN detail_servis_booking dsb ON db.id_detail_booking = dsb.id_detail_booking
    LEFT JOIN data_servis s ON dsb.id_data_servis = s.id_data_servis
    LEFT JOIN detail_barang_booking dbb ON db.id_detail_booking = dbb.id_detail_booking
    LEFT JOIN barang br ON dbb.id_barang = br.id_barang
    LEFT JOIN detail_servis_montir dsm ON db.id_detail_booking = dsm.id_detail_booking
    LEFT JOIN account km ON km.email = dsm.email_ketua_montir AND km.role = 'montir'  -- Ketua montir dengan role montir
    LEFT JOIN anggota_montir am ON dsm.id_det_servis_montir = am.id_det_servis_montir
    LEFT JOIN account am_montir ON am.email_anggota_montir = am_montir.email AND am_montir.role = 'montir'  -- Anggota montir dengan role montir
    WHERE b.id_booking = ? 
    GROUP BY 
        b.id_booking, 
        b.tgl_booking, 
        b.nopol, 
        br.nama_barang, 
        dbb.jumlah_barang, 
        br.harga
    ORDER BY b.tgl_booking DESC
");

// Bind parameter dan eksekusi query
$detail_sedang_proses->bind_param("s", $id_booking);
$detail_sedang_proses->execute();
$result_detail_proses = $detail_sedang_proses->get_result();

// Cek apakah ada hasil
if ($result_detail_proses->num_rows > 0) {
    // Buat array untuk menyimpan semua data servis dan barang
    $servis_list = [];
    $barang_list = [];

    while ($row = $result_detail_proses->fetch_assoc()) {
        // Data servis (nama dan harga dalam bentuk array)
        $servis_list = array_map(
            null,
            explode(', ', $row['servis']),
            explode(', ', $row['harga_servis'])
        );

        // Data barang
        $barang_list[] = [
            'nama_barang' => $row['nama_barang'],
            'jumlah_barang' => $row['jumlah_barang'],
            'harga_barang' => $row['harga_barang'],
            'subtotal_barang' => $row['subtotal_barang'],
        ];

        // Data booking umum (ambil satu kali saja)
        $data_booking = [
            'tgl_booking' => $row['tgl_booking'],
            'id_booking' => $row['id_booking'],
            'nopol' => $row['nopol'],
            'total_biaya' => $row['total_biaya'],
            'metode_bayar' => $row['metode_bayar'],
            'status_bayar' => $row['status_bayar'],
            'status_pengerjaan' => $row['status_pengerjaan'],
        ];

        // Nama ketua dan anggota montir
        $ketua_montir = $row['ketua_montir'];
        $anggota_montir = $row['anggota_montir'];
    }
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
            <?php include '../header.php'; ?>

            <div class="detail-servis">
                <div class="title-detail">
                    <h1>Detail Servis</h1>
                </div>

                <div class="info-umum">
                    <!-- Info Umum -->
                    <h2>Informasi Umum</h2>
                    <table class="info-umum">
                        <tbody>
                            <tr>
                                <th>Tanggal</th>
                                <td><?php echo htmlspecialchars($data_booking['tgl_booking']); ?></td>
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

                <!-- Rincian Barang -->
                <div class="rincian-barang">
                    <h3>Rincian Barang</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Barang</th>
                                <th>Harga Barang</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($barang_list)) {
                                // Jika barang_list kosong, tampilkan satu baris dengan tanda "-"
                                echo '<tr><td colspan="5" style="text-align: center;">-</td></tr>';
                            } else {
                                $no_barang = 1;
                                foreach ($barang_list as $barang) {
                                    echo '<tr>';
                                    echo '<td>' . $no_barang++ . '</td>';
                                    echo '<td>' . (isset($barang['nama_barang']) ? htmlspecialchars($barang['nama_barang']) : '-') . '</td>';
                                    echo '<td>Rp ' . (isset($barang['harga_barang']) ? number_format($barang['harga_barang'], 0, ',', '.') : '-') . '</td>';
                                    echo '<td>' . (isset($barang['jumlah_barang']) ? htmlspecialchars($barang['jumlah_barang']) : '-') . '</td>';
                                    echo '<td>Rp ' . (isset($barang['subtotal_barang']) ? number_format($barang['subtotal_barang'], 0, ',', '.') : '-') . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>



                <div class="total-status">
                    <!-- Total Biaya -->
                    <h2>Total dan Status</h2>
                    <table class="total-info">
                        <tbody>
                            <tr>
                                <th>Total Biaya</th>
                                <td>Rp <?php echo number_format($data_booking['total_biaya'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td><?php echo htmlspecialchars($data_booking['metode_bayar']); ?></td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td><?php echo ucfirst(htmlspecialchars($data_booking['status_bayar'])); ?></td>
                            </tr>
                            <tr>
                                <th>Status Pengerjaan</th>
                                <td><?php echo ucfirst(htmlspecialchars($data_booking['status_pengerjaan'])); ?></td>
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



                <div class="kembali">
                    <a href="aktivitas.php" class="btn-kembali">Kembali ke Aktivitas</a>
                </div>

            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>