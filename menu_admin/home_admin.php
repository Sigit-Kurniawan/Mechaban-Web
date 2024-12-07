<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// Tampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan file koneksi ada
if (!file_exists('../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../Api/koneksi.php');

// Definisikan direktori untuk upload foto
define('UPLOAD_DIR', '../../uploads/customers/');

// Hitung total customers
$query_customers = "SELECT COUNT(*) AS total_customers FROM account WHERE role = 'customer'";
$result_customers = $conn->query($query_customers);
$total_customers = $result_customers->fetch_assoc()['total_customers'] ?? 0;

$query_montir = "SELECT COUNT(*) AS total_montir FROM account WHERE role = 'montir'";
$result_montir = $conn->query($query_montir);
$total_montir = $result_montir->fetch_assoc()['total_montir'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <?php include_once 'sidebar.php'; ?>

        <div class="main">
            <!-- header -->
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <!-- ----search---- -->
                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here.....">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>
                <!-- ----user img---- -->
                <div class="user">
                    <div class="user-img-container">
                        <?php
                        // Determine the photo path
                        $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                            ? '../uploads/' . htmlspecialchars($_SESSION["photo"])
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
                            <span class="name"><?php echo $_SESSION["name"]; ?></span>
                            <span class="role"><?php echo $_SESSION["role"]; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Photo Modal (can be added to the bottom of your page) -->
                <div id="photoModal" class="modal">
                    <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                    <div class="photo-modal-content">
                        <img id="modalPhoto" src="" alt="Enlarged photo">
                    </div>
                </div>
            </div>

            <!-- Card Section -->
            <div class="cardbox">
                <div class="card">
                    <div>
                        <div class="number"><?php echo $total_customers; ?></div>
                        <div class="cardname">Customers</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="number"><?php echo $total_montir; ?></div>
                        <div class="cardname">Montir</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="construct-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <a href="aktivitas/aktivitas.php" class="btn-viewAll">View All</a>
                        <div class="cardname">Aktivitas</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <a href="laporan/laporan.php" class="btn-viewAll">View All</a>
                        <div class="cardname">Laporan</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="documents-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="details">
                <div class="recentOrder">
                    <div class="cardHeader">
                        <h2>Recent Transactions</h2>
                        <a href="aktivitas/aktivitas.php" class="btn">View All</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Name</td>
                                <td>License Plate</td>
                                <td>Vehicle</td>
                                <td>Item</td>
                                <td>Quantity</td>
                                <td>Service</td>
                                <td>Total Cost</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Retrieve the latest 3 transactions from the riwayatbooking table
                            $query = "SELECT * FROM riwayatbooking ORDER BY tgl_booking DESC LIMIT 3";
                            $result = $conn->query($query);

                            $index = 1;
                            while ($row = $result->fetch_assoc()) {
                                // Use null coalescing and explicit type conversion to avoid deprecation warnings
                                echo "<tr>";
                                echo "<td>" . $index++ . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['nama_customer'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['nopol'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['merk_mobil'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['barang'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['jumlah_barang'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['servis'] ?? 'N/A')) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($row['total_biaya'] ?? 'N/A')) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="recentCustomers">
                <div class="cardHeader">
                    <h2>Recent Customers</h2>
                </div>

                <!-- Photo Modal -->
                <div id="photoModal" class="photo-modal">
                    <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                    <div class="photo-modal-content">
                        <img id="modalPhoto" src="" alt="Enlarged photo">
                    </div>
                </div>

                <table>
                    <?php
                    // Query data pelanggan
                    $query = "SELECT * FROM account WHERE role = 'customer' ORDER BY name LIMIT 8";
                    $result = $conn->query($query);

                    // Tampilkan data pelanggan
                    while ($row = $result->fetch_assoc()):
                        // Tentukan path foto
                        $photo = $row['photo'] ?? ''; // Nilai default jika NULL
                        $photo_path = UPLOAD_DIR . htmlspecialchars($photo);
                        $photo_exists = !empty($photo) && file_exists($photo_path);
                    ?>
                        <tr>
                            <td width="60px">
                                <?php if ($photo_exists): ?>
                                    <img src="<?php echo $photo_path; ?>"
                                        alt="<?php echo htmlspecialchars($row['name'] ?? 'Unknown'); ?>"
                                        class="customer-photo"
                                        onclick="showPhotoModal('<?php echo $photo_path; ?>')">
                                <?php else: ?>
                                    <img src="../assets/img/default-profile.png"
                                        alt="Default profile"
                                        class="customer-photo">
                                <?php endif; ?>
                            </td>
                            <td>
                                <h4><?php echo htmlspecialchars($row['name'] ?? 'Unknown'); ?>
                                    <br>
                                    <span><?php echo htmlspecialchars($row['email'] ?? ''); ?></span>
                                </h4>
                            </td>
                        </tr>
                    <?php endwhile; ?>
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