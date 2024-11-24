<?php
// laporan.php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include_once('../../Api/koneksi.php');
require_once('generate_report.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $format = $_POST['format'];
    
    // Get report data based on type
    switch($report_type) {
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
    }
    
    // Export based on chosen format
    if ($format === 'pdf') {
        exportToPDF($data, $report_type);
    } else if ($format === 'excel') {
        exportToExcel($data, $report_type);
    }
}
?>

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
            <?php include '../header.php'; ?>
            
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