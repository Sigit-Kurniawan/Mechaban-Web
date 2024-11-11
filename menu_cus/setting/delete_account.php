<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("File koneksi database tidak ditemukan.");
}
include_once('../../Api/koneksi.php');


$email_customer = $_SESSION["email"];


$query = "DELETE FROM account WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email_customer);

if ($stmt->execute()) {
    // Menghapus sesi setelah akun dihapus
    session_unset();
    session_destroy();
    header("Location: \Mechaban-Web\login.php?account_deleted=true");
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus akun.";
}

$stmt->close();
$conn->close();
?>