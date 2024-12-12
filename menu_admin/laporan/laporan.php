<?php
session_start();
// Restrict access to admin only
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once('../../Api/koneksi.php');
require_once('generate_report.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $format = $_POST['format'];

    // Validate input
    if (empty($report_type) || empty($start_date) || empty($end_date)) {
        $_SESSION['error_message'] = "Please fill all report details.";
        header("Location: laporan.php");
        exit();
    }

    // Get report data based on type
    switch ($report_type) {
        case 'booking':
            $data = generateBookingReport($start_date, $end_date);
            break;
        case 'montir':
            $data = generateMontirReport($start_date, $end_date);
            break;
        case 'income':
            $data = generateIncomeReport($start_date, $end_date);
            break;
        case 'service':
            $data = generateServiceReport($start_date, $end_date);
            break;
        default:
            $_SESSION['error_message'] = "Invalid report type.";
            header("Location: laporan.php");
            exit();
    }

    // Check if data retrieval was successful
    if ($data === false) {
        $_SESSION['error_message'] = "Failed to generate report. Please try again.";
        header("Location: laporan.php");
        exit();
    }

    // Export based on chosen format
    if ($format === 'pdf') {
        exportToPDF($data, $report_type, $start_date, $end_date);
    } else if ($format === 'excel') {
        exportToExcel($data, $report_type, $start_date, $end_date);
    }
}
?>

<!-- Rest of the existing laporan.php HTML remains the same -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="laporan.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
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
                            : '../../assets/img/default-profile.png';
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

                <div class="report-container">
                    <form action="" method="POST" class="report-form">
                        <div class="form-group">
                            <label for="report_type">Jenis Laporan:</label>
                            <select name="report_type" id="report_type" required>
                                <option value="" disabled selected>Pilih Jenis Laporan</option>
                                <option value="booking">Laporan Booking</option>
                                <option value="montir">Laporan Kinerja Montir</option>
                                <option value="income">Laporan Pemasukan</option>
                                <option value="service">Laporan Layanan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Tanggal Akhir:</label>
                            <input type="date" name="end_date" id="end_date" required>
                        </div>

                        <div class="form-group">
                            <label for="format">Format Laporan:</label>
                            <select name="format" id="format" required>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-generate">
                                <ion-icon name="document-text-outline"></ion-icon>
                                Generate Laporan
                            </button>
                            <button type="reset" class="btn-reset">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
    <script src="laporan.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>