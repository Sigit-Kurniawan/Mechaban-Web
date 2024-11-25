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
// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];


// Mendapatkan id_booking dari URL
if (isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking'];

    // Pastikan id_booking valid dan aman dari SQL injection
    $id_booking = mysqli_real_escape_string($conn, $id_booking);

    // Query untuk menghapus data booking dan detail terkait
    $query_delete_detail = "DELETE db, dsb 
                            FROM detail_booking db
                            LEFT JOIN detail_servis_booking dsb ON db.id_detail_booking = dsb.id_detail_booking
                            WHERE db.id_booking = '$id_booking'";

    $query_delete_booking = "DELETE FROM booking WHERE id_booking = '$id_booking'";

    // Menjalankan query untuk menghapus detail dan booking
    if (mysqli_query($conn, $query_delete_detail) && mysqli_query($conn, $query_delete_booking)) {
        // Mengarahkan kembali ke halaman utama setelah berhasil
        header("Location: aktivitas.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "ID booking tidak ditemukan.";
}
?>