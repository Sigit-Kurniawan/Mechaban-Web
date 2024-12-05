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

// Cek apakah ada id_booking yang dikirimkan
if (isset($_GET['id_booking'])) {
    $id_booking = $_GET['id_booking'];

    // Update status booking menjadi 'batal'
    $query = "UPDATE booking SET status = 'batal' WHERE id_booking = '$id_booking'";

    if (mysqli_query($conn, $query)) {
        // Jika update berhasil, redirect ke halaman riwayat transaksi
        header("Location: aktivitas.php"); // Pindah ke halaman riwayat transaksi
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "No booking ID provided.";
}
?>