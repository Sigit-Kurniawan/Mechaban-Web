<?php
session_start();
include("../koneksi.php");
require_once '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generateOTP()
{
    return sprintf("%04d", mt_rand(1000, 9999));
}

function sendEmail($email, $otp)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bengkel.mechaban@gmail.com';
        $mail->Password   = $_ENV['APP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('verification@mechaban.com', 'Mechaban');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Akun - Kode OTP Baru';
        $mail->Body    = 'Kode OTP baru Anda untuk memvalidasi email: <b>' . $otp . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

// Check if registration data exists in session
if (!isset($_SESSION['register_data'])) {
    header("Location: ../../register.php");
    exit;
}

$email = $_SESSION['register_data']['email'];

// Generate new OTP
$new_otp = generateOTP();

// Delete previous OTPs for this email
$stmt_delete = mysqli_prepare($conn, "DELETE FROM otp WHERE email = ?");
mysqli_stmt_bind_param($stmt_delete, "s", $email);
mysqli_stmt_execute($stmt_delete);

// Send new OTP
if (sendEmail($email, $new_otp)) {
    // Store new OTP
    $stmt_otp = mysqli_prepare($conn, "INSERT INTO otp (email, otp_code, time) VALUES (?, ?, NOW())");
    mysqli_stmt_bind_param($stmt_otp, "ss", $email, $new_otp);
    mysqli_stmt_execute($stmt_otp);

    header("Location: verify_otp.php?success=otp_resent");
    exit;
} else {
    header("Location: verify_otp.php?error=email_send_failed");
    exit;
}

mysqli_close($conn);