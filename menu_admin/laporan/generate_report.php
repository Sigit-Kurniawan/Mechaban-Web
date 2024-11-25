<?php
// generate_report.php
function generateBookingReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT * FROM riwayatbooking 
              WHERE tgl_booking BETWEEN ? AND ?
              ORDER BY tgl_booking DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateMontirReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT * FROM view_order_montir 
              WHERE tgl_booking BETWEEN ? AND ?
              ORDER BY tgl_booking DESC, total_order DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateIncomeReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT * FROM view_pemasukan 
              WHERE tgl_booking BETWEEN ? AND ?
              ORDER BY tgl_booking ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateServiceReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT vs.*, COUNT(dsb.id_data_servis) as total_orders,
              SUM(b.total_biaya) as total_revenue
              FROM view_servis vs
              LEFT JOIN detail_servis_booking dsb ON vs.id_data_servis = dsb.id_data_servis
              LEFT JOIN detail_booking db ON dsb.id_detail_booking = db.id_detail_booking
              LEFT JOIN booking b ON db.id_booking = b.id_booking
              WHERE b.tgl_booking BETWEEN ? AND ?
              GROUP BY vs.id_data_servis
              ORDER BY total_orders DESC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function exportToPDF($data, $report_type) {
    require_once('tcpdf/tcpdf.php');
    // PDF generation logic here
}

function exportToExcel($data, $report_type) {
    require_once('PhpSpreadsheet/autoload.php');
    // Excel generation logic here
}