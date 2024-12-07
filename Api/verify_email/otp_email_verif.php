<?php
session_start();
require_once '../koneksi.php';
require_once '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

function generateOTP()
{
    return sprintf("%04d", mt_rand(1000, 9999));
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
        $mail->Subject = 'Verifikasi Akun';
        $mail->Body    = 'Masukkan kode OTP berikut di Mechaban untuk memvalidasi email Anda: <b>' . $otp . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

// CSRF Protection
function generateCSRFToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) &&
        !empty($token) &&
        hash_equals($_SESSION['csrf_token'], $token);
}

// Rate Limiting for OTP Generation
function checkOTPRateLimit($email, $conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM otp WHERE email = ? AND time > NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] < 3; // Limit to 3 OTP requests in 5 minutes
}