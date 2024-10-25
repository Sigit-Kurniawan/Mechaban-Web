<?php
session_start();
include("koneksi.php");

if (isset($_POST["login"])) {
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
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $_SESSION['login'] = true;
            // header("Location: ..");
            // exit;

            if ($user['role'] === 'Admin') {
                header("Location: dashboard-admin.php");
                exit();
            } elseif ($user['role'] === 'Customer') {
                header("Location: dashboard-cus.php");
                exit();
            }
        } else {
            echo "Email atau password salah";
        }
        mysqli_close($conn);
    }
}
