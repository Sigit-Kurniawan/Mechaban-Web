<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Search and filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';

// Build query with filters
$where_conditions = [];
if ($search) {
    $where_conditions[] = "(id_booking LIKE '%$search%' OR nopol LIKE '%$search%')";
}
if ($status_filter) {
    $where_conditions[] = "status_pengerjaan = '$status_filter'";
}
if ($date_from && $date_to) {
    $where_conditions[] = "tgl_booking BETWEEN '$date_from' AND '$date_to'";
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Count total records for pagination
$count_query = "SELECT COUNT(*) as total FROM booking $where_clause";
$count_result = $conn->query($count_query);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Main query with pagination
$query = "SELECT * FROM booking $where_clause ORDER BY tgl_booking DESC LIMIT $offset, $records_per_page";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Add DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="aktivitas.css">
</head>

<body>
    <div class="container">
        <?php include_once '../sidebar.php'; ?>
        <div class="main">
            <!-- header -->
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <!-- ----search---- -->
                
                <!-- ----user img---- -->
                <div class="user">
                    <div class="user-img-container">
                        <?php
                        // Determine the photo path
                        $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                            ? '../../uploads/' . htmlspecialchars($_SESSION["photo"])
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

            <div class="view">
                <div class="cardheader">
                    <h2>DATA BOOKING</h2>
                    <!-- <button class="export-btn" onclick="exportToExcel()">Export to Excel</button> -->
                </div>

                <!-- Search and Filter Section -->
                <div class="filters">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Search ID or NOPOL" value="<?php echo htmlspecialchars($search); ?>">
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="In Progress" <?php echo $status_filter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo $status_filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                        <input type="date" name="date_from" value="<?php echo $date_from; ?>">
                        <input type="date" name="date_to" value="<?php echo $date_to; ?>">
                        <button type="submit">Filter</button>
                        <button type="button" onclick="window.location.href='aktivitas.php'">Reset</button>
                    </form>
                </div>

                <table id="bookingTable" class="display">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>ID BOOKING</th>
                            <th>TGL BOOKING</th>
                            <th>NOPOL</th>
                            <th>TOTAL BIAYA</th>
                            <th>METODE BAYAR</th>
                            <th>STATUS BAYAR</th>
                            <th>STATUS PENGERJAAN</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = $offset + 1;
                        while ($row = $result->fetch_assoc()):
                            $status_class = strtolower(str_replace(' ', '', $row['status_pengerjaan']));
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['id_booking']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tgl_booking'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nopol']); ?></td>
                                <td>Rp <?php echo number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['metode_bayar']); ?></td>
                                <td><?php echo htmlspecialchars($row['status_bayar']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($row['status_pengerjaan']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button onclick="viewDetails('<?php echo $row['id_booking']; ?>')" title="View Details">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </button>
                                    <button onclick="updateStatus('<?php echo $row['id_booking']; ?>')" title="Update Status">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>"
                            class="<?php echo $page === $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="aktivitas.js"></script>
</body>

</html>