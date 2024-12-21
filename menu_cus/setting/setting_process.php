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

    // Check if new email already exists (skip current user's email)
    if($email !== $email_customer) {
        $check_email = "SELECT COUNT(*) FROM account WHERE email = ? AND email != ?";
        $check_stmt = $conn->prepare($check_email);
        $check_stmt->bind_param("ss", $email, $email_customer);
        $check_stmt->execute();
        $check_stmt->bind_result($email_count);
        $check_stmt->fetch();
        $check_stmt->close();

        if($email_count > 0) {
            header("Location: setting.php?error=email_exists");
            exit();
        }
    }

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
          // Update email in car table
          $update_car = "UPDATE car SET email_customer = ? WHERE email_customer = ?";
          $car_stmt = $conn->prepare($update_car);
          $car_stmt->bind_param("ss", $email, $email_customer);
          $car_stmt->execute();
          $car_stmt->close();

          // Update session
          $_SESSION["name"] = $name;
          $_SESSION["email"] = $email;

          header("Location: setting.php?success=save");
          exit();
    } else {
        echo "Terjadi kesalahan saat memperbarui data akun.";
    }
    $stmt->close();
}

$conn->close();
?>