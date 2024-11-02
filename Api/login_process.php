<?php
session_start();
include("koneksi.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //mengambil input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');


    //validasi email valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../login.php?error=1");
        exit();
    }

    //mengambil data pengguna dari database
    $query = "SELECT * FROM account WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    //verifikasi password
    if ($user && password_verify($password, $user['password'])) {

        //menyiapkan sesi
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login'] = true;

        //mengarahkan berdasarkan role pengguna
        if ($user['role'] === 'admin') {
            header("Location: ../menu_admin/home_admin.php");
            exit();
        } elseif ($user['role'] === 'customer') {
            header("Location: ../menu_cus/home_cus.php");
            exit();
        }
    } else {
        //menangani login gagal
        header("Location: ../login.php?error=1");
        exit();
    }
}


mysqli_close($conn);
?>