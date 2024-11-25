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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];

    // Memeriksa apakah password diubah
    if (!empty($password)) {
        // Jika password diubah, hash password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE account SET name = ?, email = ?, no_hp = ?, password = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $name, $email, $no_hp, $hashed_password, $email_customer);
    } else {

        $query = "UPDATE account SET name = ?, email = ?, no_hp = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $no_hp, $email_customer);
    }

    if ($stmt->execute()) {
        // Update nama di session
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email; //update session ke email baru

        header("Location: setting.php?success=save");
        exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui data akun.";
    }
    $stmt->close();
}

$conn->close();
?>