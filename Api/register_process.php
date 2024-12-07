<?php
session_start();
include("koneksi.php");
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
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
        $mail->Subject = 'Verifikasi Akun';
        $mail->Body    = 'Masukkan kode OTP berikut di Mechaban untuk memvalidasi email Anda: <b>' . $otp . '</b>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($_POST['no_hp'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $konfirm_password = htmlspecialchars($_POST['konfirm_password'], ENT_QUOTES, 'UTF-8');
    $role = isset($_POST['role']) ? htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8') : '';

    // Validate input fields are not empty
    if (empty($name) || empty($email) || empty($no_hp) || empty($password) || empty($konfirm_password)) {
        header("Location: ../register.php?error=empty_fields");
        exit;
    }

    // Validate role
    $valid_roles = ['customer', 'admin', 'montir'];
    if (!in_array($role, $valid_roles)) {
        header("Location: ../register.php?error=invalid_role");
        exit;
    }
  
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=email_invalid");
        exit;
    }

    // Password validation
    // Requires at least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/', $password)) {
        header("Location: ../register.php?error=password_invalid");
        exit;
    }

    // Confirm password validation
    if ($password !== $konfirm_password) {
        header("Location: ../register.php?error=konfirm_password_invalid");
        exit;
    }

    // Check if email already exists
    $check_email_query = "SELECT email FROM account WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        header("Location: ../register.php?error=email_exists");
        exit;
    }

    // Generate and store OTP
    $otp = generateOTP();
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Store registration data in session for later use
    $_SESSION['register_data'] = [
        'name' => $name,
        'email' => $email,
        'no_hp' => $no_hp,
        'password' => $hashed_password,
        'role' => $role
    ];

    // Send OTP via email
    if (sendEmail($email, $otp)) {
        // Store OTP in database
        $stmt_otp = mysqli_prepare($conn, "INSERT INTO otp (email, otp_code, time) VALUES (?, ?, NOW())");
        mysqli_stmt_bind_param($stmt_otp, "ss", $email, $otp);
        mysqli_stmt_execute($stmt_otp);
        // Redirect to OTP verification page
        header("Location: verify_email/verify_otp.php");
        exit;
    } else {
        // Handle email sending failure
        header("Location: ../register.php?error=email_send_failed");
        exit;
    }
}

mysqli_close($conn);