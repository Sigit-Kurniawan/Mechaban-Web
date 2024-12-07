<!-- reset_password.php -->
<?php
session_start();
require_once '../Api/koneksi.php';

// Check if OTP was verified
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: lupa_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Hash the password (using PHP's built-in password hashing)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in database
        $stmt = $conn->prepare("UPDATE account SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        
        if ($stmt->execute()) {
            // Clear sessions
            unset($_SESSION['reset_email']);
            unset($_SESSION['otp_verified']);

            echo "<div style='text-align: center; margin-top: 50px;'>
                <h2 style='color: #007bff;'>Password berhasil diubah</h2>
                <p>Password anda berhasil diubah. Anda dapat masuk menggunakan password baru anda.</p>
                <a href='../login.php' style='display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Login</a>
                </div>";

            exit();
        } else {
            $error = "Error updating password";
        }
    } else {
        $error = "Passwords do not match";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="lupa_password.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-head">
                <div class="head-bg">
                    <img src="../assets/img/logo2.png" alt="Logo" class="head-logo">
                    <h2 class="head-title">Reset Password</h2>
                </div>
            </div>

            <?php if(isset($error)) echo "<p>$error</p>"; ?>
            <form method="post">
                <input type="password" name="new_password" required placeholder="New Password">
                <input type="password" name="confirm_password" required placeholder="Confirm Password">
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>