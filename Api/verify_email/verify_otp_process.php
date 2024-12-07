<?php
session_start();
include("../koneksi.php");

// Check if registration data exists in session
if (!isset($_SESSION['register_data'])) {
    header("Location: ../../register.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = htmlspecialchars($_POST['otp'], ENT_QUOTES, 'UTF-8');
    $email = $_SESSION['register_data']['email'];

    // Verify OTP
    $stmt = mysqli_prepare($conn, "SELECT * FROM otp WHERE email = ? AND otp_code = ? AND time > NOW() - INTERVAL 10 MINUTE");
    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // OTP is valid, proceed with registration
        $register_data = $_SESSION['register_data'];

        // Insert user into account table
        $insert_query = "INSERT INTO account (name, email, no_hp, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "sssss", 
            $register_data['name'], 
            $register_data['email'], 
            $register_data['no_hp'], 
            $register_data['password'], 
            $register_data['role']
        );

        if (mysqli_stmt_execute($stmt_insert)) {
            // Remove used OTP
            $stmt_delete_otp = mysqli_prepare($conn, "DELETE FROM otp WHERE email = ? AND otp_code = ?");
            mysqli_stmt_bind_param($stmt_delete_otp, "ss", $email, $otp);
            mysqli_stmt_execute($stmt_delete_otp);

            // Clear registration data from session
            unset($_SESSION['register_data']);

            // Redirect to login
            header("Location: ../../login.php?success=registration_complete");
            exit;
        } else {
            // Handle registration failure
            header("Location: verify_otp.php?error=registration_failed");
            exit;
        }
    } else {
        // Invalid or expired OTP
        header("Location: verify_otp.php?error=invalid_otp");
        exit;
    }
}

mysqli_close($conn);