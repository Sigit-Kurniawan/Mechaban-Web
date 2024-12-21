<?php
require_once '../../Api/koneksi.php';

if (isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking'];
    
    $query = "SELECT * FROM view_rincian_booking WHERE id_booking = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id_booking);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Format the data
        $bookingDetails = [
            'id_booking' => $row['id_booking'],
            'tgl_booking' => $row['tgl_booking'],
            'email_customer' => $row['email_customer'],
            'nopol' => $row['nopol'],
            'servis' => $row['servis'],
            'harga_servis' => $row['harga_servis'],
            'total_biaya' => number_format($row['total_biaya'], 0, ',', '.'),
            'status' => $row['status'],
            'ketua_montir' => $row['ketua_montir'],
            'anggota_montir' => $row['anggota_montir'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'rating' => $row['rating'],
            'review' => $row['review']
        ];
        
        echo json_encode($bookingDetails);
    } else {
        echo json_encode(['error' => 'Booking not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No booking ID provided']);
}

$conn->close();
