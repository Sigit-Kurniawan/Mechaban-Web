<?php
session_start();
require_once '../Api/koneksi.php';
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token Validation
    $csrf_token = $_POST['csrf_token'] ?? '';

    // Add detailed logging for debugging
    error_log("Session CSRF Token: " . ($_SESSION['csrf_token'] ?? 'Not set'));
    error_log("Submitted CSRF Token: " . $csrf_token);

    if (!validateCSRFToken($csrf_token)) {
        // Log more details about the validation failure
        error_log("CSRF Token Validation Failed");
        error_log("POST Data: " . print_r($_POST, true));
        die("CSRF token validation failed. Please refresh the page and try again.");
    }

    // Sanitize and validate email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    try {
        // Check if email exists in account table
        $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Check OTP rate limit
            if (!checkOTPRateLimit($email, $conn)) {
                die("Too many OTP requests. Please try again later.");
            }

            // Generate OTP
            $otp = generateOTP();

            // Delete any existing OTP for this email
            $stmt = $conn->prepare("DELETE FROM otp WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Insert new OTP
            $stmt = $conn->prepare("INSERT INTO otp (email, otp_code, time) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $email, $otp);

            if ($stmt->execute()) {
                // Send OTP via email
                if (sendEmail($email, $otp)) {
                    // Store email in session for verification later
                    $_SESSION['reset_email'] = $email;

                    // Redirect to OTP verification page
                    header("Location: verify_otp.php");
                    exit();
                } else {
                    die("Failed to send OTP email");
                }
            } else {
                die("Error generating OTP");
            }
        } else {
            die("Email not found in our system");
        }
    } catch (Exception $e) {
        error_log("Password reset process failed: " . $e->getMessage());
        die("An unexpected error occurred");
    }
}
?>
