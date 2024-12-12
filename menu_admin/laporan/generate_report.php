<?php
require_once '../../vendor/autoload.php'; // For PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generateBookingReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT * FROM view_rincian_booking 
              WHERE tgl_booking BETWEEN ? AND ?
              ORDER BY tgl_booking DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateMontirReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT a.email, a.name AS montir_name, 
                     COALESCE(vto.total_order, 0) AS total_orders,
                     SUM(b.total_biaya) AS total_revenue
              FROM account a
              LEFT JOIN view_total_order_montir vto ON a.email = vto.email_montir
              LEFT JOIN detail_montir dm ON a.email = dm.email_ketua_montir
              LEFT JOIN booking b ON dm.id_booking = b.id_booking
              WHERE a.role = 'montir' 
                AND b.tgl_booking BETWEEN ? AND ?
                AND b.status = 'selesai'
              GROUP BY a.email
              ORDER BY total_orders DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateIncomeReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT tgl_booking AS booking_date, 
                     total_pemasukan AS total_income
              FROM view_pemasukan
              WHERE tgl_booking BETWEEN ? AND ?
              ORDER BY tgl_booking ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function generateServiceReport($start_date, $end_date) {
    global $conn;
    $query = "SELECT vs.nama_servis, 
                     vs.nama_komponen, 
                     vs.harga_servis,
                     COUNT(ds.id_booking) AS total_orders,
                     SUM(b.total_biaya) AS total_revenue
              FROM view_servis vs
              LEFT JOIN detail_servis ds ON vs.id_data_servis = ds.id_data_servis
              LEFT JOIN booking b ON ds.id_booking = b.id_booking
              WHERE b.tgl_booking BETWEEN ? AND ?
              GROUP BY vs.id_data_servis
              ORDER BY total_orders DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function exportToPDF($data, $report_type, $start_date, $end_date) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle(ucfirst($report_type) . ' Report');
    $pdf->SetHeaderData('', 0, ucfirst($report_type) . ' Report', 
                        "Date Range: $start_date to $end_date");

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $html = '<table border="1" cellpadding="4">';

    switch($report_type) {
        case 'booking':
            $html .= '<tr><th>ID Booking</th><th>Tgl Booking</th><th>Email Customer</th><th>NOPOL</th><th>Servis</th><th>Total Biaya</th><th>Status</th></tr>';
            while ($row = $data->fetch_assoc()) {
                $html .= sprintf(
                    '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                    $row['id_booking'], $row['tgl_booking'], $row['email_customer'], 
                    $row['nopol'], $row['servis'], $row['total_biaya'], $row['status']
                );
            }
            break;

        case 'montir':
            $html .= '<tr><th>Mechanic Name</th><th>Email</th><th>Total Orders</th><th>Total Revenue</th></tr>';
            while ($row = $data->fetch_assoc()) {
                $html .= sprintf(
                    '<tr><td>%s</td><td>%s</td><td>%d</td><td>%s</td></tr>',
                    $row['montir_name'], $row['email'], $row['total_orders'], $row['total_revenue']
                );
            }
            break;

        case 'income':
            $html .= '<tr><th>Date</th><th>Total Income</th></tr>';
            while ($row = $data->fetch_assoc()) {
                $html .= sprintf(
                    '<tr><td>%s</td><td>%s</td></tr>',
                    $row['booking_date'], $row['total_income']
                );
            }
            break;

        case 'service':
            $html .= '<tr><th>Service Name</th><th>Component</th><th>Price</th><th>Total Orders</th><th>Total Revenue</th></tr>';
            while ($row = $data->fetch_assoc()) {
                $html .= sprintf(
                    '<tr><td>%s</td><td>%s</td><td>%s</td><td>%d</td><td>%s</td></tr>',
                    $row['nama_servis'], $row['nama_komponen'], $row['harga_servis'], 
                    $row['total_orders'], $row['total_revenue']
                );
            }
            break;
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('report_' . $report_type . '_' . date('YmdHis') . '.pdf', 'D');
}

function exportToExcel($data, $report_type, $start_date, $end_date) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle(ucfirst($report_type) . ' Report');

    $sheet->setCellValue('A1', ucfirst($report_type) . ' Report');
    $sheet->setCellValue('A2', "Date Range: $start_date to $end_date");

    $row = 4;

    switch($report_type) {
        case 'booking':
            $sheet->fromArray([
                'Booking ID', 'Date', 'Customer', 'Vehicle', 
                'Services', 'Total Cost', 'Status', 'Mechanic(s)', 
                'Review', 'Rating'
            ], null, 'A' . $row);
            $row++;
            while ($record = $data->fetch_assoc()) {
                $sheet->fromArray([
                    $record['id_booking'], 
                    $record['tgl_booking'], 
                    $record['email_customer'], 
                    $record['nopol'], 
                    $record['servis'], 
                    $record['total_biaya'], 
                    $record['status'], 
                    $record['ketua_montir'] . ' ' . $record['anggota_montir'],
                    $record['review'],
                    $record['rating']
                ], null, 'A' . $row);
                $row++;
            }
            break;

        case 'montir':
            $sheet->fromArray([
                'Mechanic Name', 'Email', 'Total Orders', 'Total Revenue'
            ], null, 'A' . $row);
            $row++;
            while ($record = $data->fetch_assoc()) {
                $sheet->fromArray([
                    $record['montir_name'], 
                    $record['email'], 
                    $record['total_orders'], 
                    $record['total_revenue']
                ], null, 'A' . $row);
                $row++;
            }
            break;

        case 'income':
            $sheet->fromArray([
                'Date', 'Total Income'
            ], null, 'A' . $row);
            $row++;
            while ($record = $data->fetch_assoc()) {
                $sheet->fromArray([
                    $record['booking_date'], 
                    $record['total_income']
                ], null, 'A' . $row);
                $row++;
            }
            break;

        case 'service':
            $sheet->fromArray([
                'Service Name', 'Component', 'Price', 'Total Orders', 'Total Revenue'
            ], null, 'A' . $row);
            $row++;
            while ($record = $data->fetch_assoc()) {
                $sheet->fromArray([
                    $record['nama_servis'], 
                    $record['nama_komponen'], 
                    $record['harga_servis'], 
                    $record['total_orders'], 
                    $record['total_revenue']
                ], null, 'A' . $row);
                $row++;
            }
            break;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'report_' . $report_type . '_' . date('YmdHis') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}