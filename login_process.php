<?php
session_start();
include("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $query = "SELECT * FROM account WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            $_SESSION['login'] = true;
            header("Location: index.php");
            exit;

            // if ($user['role'] === 'admin') {
            //     header("Location: dashboard-admin.php");
            //     exit();
            // } elseif ($user['role'] === 'customer') {
            //     header("Location: dashboard-cus.php");
            //     exit();
            // }
        } else {
            echo "Email atau password salah";
        }
    }
}

mysqli_close($conn);
