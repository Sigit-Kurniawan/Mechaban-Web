<!-- verify_otp.php -->
<?php
session_start();
require_once '../Api/koneksi.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: lupa_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_entered = $_POST['otp'];
    $email = $_SESSION['reset_email'];

    // Check OTP validity
    $stmt = $conn->prepare("SELECT * FROM otp WHERE email = ? AND otp_code = ? AND time > NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("ss", $email, $otp_entered);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP is valid
        // Delete used OTP
        $stmt = $conn->prepare("DELETE FROM otp WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Move to password reset page
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Invalid or expired OTP";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="lupa_password.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-head">
                <div class="head-bg">
                    <img src="../assets/img/logo2.png" alt="Logo" class="head-logo">
                    <h2 class="head-title">Verifikasi Otp</h2>
                </div>
            </div>

            <?php if(isset($error)) echo "<p>$error</p>"; ?>
            <form method="post">
                <input type="text" name="otp" required placeholder="Enter 4-digit OTP">
                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>

</html>