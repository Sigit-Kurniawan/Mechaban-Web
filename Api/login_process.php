<?php
session_start();
include("koneksi.php");

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../login.php?error=1");
        exit();
    }

    // Prepare and execute the query
    $query = "SELECT * FROM account WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login'] = true;

        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header("Location: ../menu_admin/home_admin.php");
            exit();
        } elseif ($user['role'] === 'customer') {
            header("Location: ../menu_cus/home_cus.php");
            exit();
        }
    } else {
        // Redirect to login with error if credentials are incorrect
        header("Location: ../login.php?error=1");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>
