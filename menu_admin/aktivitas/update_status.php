<?php
include_once('../../Api/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['bookingId'];
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id_booking = ?");
    $stmt->bind_param("ss", $new_status, $booking_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>
