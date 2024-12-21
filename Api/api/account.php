<?php
header("Content-Type: application/json");
include("connect.php");
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"));
$response = [];

function login($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
    $response = [];

    $result = mysqli_query($conn, "SELECT email, password, role FROM account WHERE email = '$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0 && password_verify($password, $row["password"])) {
        $response["code"] = 200;
        $response["data"] = [
            'email' => $row["email"],
            'role' => $row["role"]
        ];
    } else {
        $response["code"] = 400;
    }

    return $response;
}

function register($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($data->no_hp, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $otp = htmlspecialchars($data->otp, ENT_QUOTES, 'UTF-8');
    $response = [];

    $stmt = mysqli_prepare($conn, "SELECT otp_code FROM otp WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && $row['otp_code'] === $otp) {
        try {
            $sql = "INSERT INTO account (email, name, no_hp, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $name, $no_hp, $hashedPassword);
            mysqli_stmt_execute($stmt);

            $stmt = mysqli_prepare($conn, "DELETE FROM otp WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $response["code"] = 200;
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
        }
    } else {
        $response["code"] = 400;
    }

    return $response;
}

function readAccount($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $response = [];
    try {
        $result = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");
        $data = mysqli_fetch_assoc($result);
        $response['code'] = 200;
        $response['data'] = [
            'email' => $data['email'],
            'name' => $data['name'],
            'no_hp' => $data['no_hp'],
            'photo' => $data['photo']
        ];
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function updateAccount($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $emailUpdate = $data->emailUpdate;
    $name = htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($data->no_hp, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $photo = null;
    $oldPhotoName = "../../uploads/customers/" . str_replace('.', '_', (explode('@', $email)[0])) . ".jpg";
    $photoName = str_replace(".", "_", (explode('@', $emailUpdate)[0])) . ".jpg";

    if ($data->updatePhoto === false) {
        if ((file_exists('../../uploads/customers/' . $photoName) && $email !== $emailUpdate) || (file_exists($oldPhotoName) && $email !== $emailUpdate)) {
            $photo = $photoName;
            rename($oldPhotoName, "../../uploads/customers/" . $photoName);
        } elseif ((file_exists('../../uploads/customers/' . $photoName) && $email === $emailUpdate) || (file_exists($oldPhotoName) && $email === $emailUpdate)) {
            $photo = $photoName;
        }
    } else {
        if (file_exists($oldPhotoName) && $email !== $emailUpdate) {
            unlink($oldPhotoName);
        }
        $image = base64_decode($data->photo);
        $photo = $photoName;
        file_put_contents("../../uploads/customers/" . $photo, $image);
    }

    $response = [];

    try {
        $query = "UPDATE account SET email = '$emailUpdate', name = '$name', no_hp = '$no_hp', photo = '$photo'";
        if (!empty($password)) {
            $query .= ", password = '$hashedPassword'";
        }
        $query .= " WHERE email = '$email'";
        mysqli_query($conn, $query);
        if ($email != $emailUpdate) {
            $response['code'] = 200;
        } else {
            $response['code'] = 201;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function deleteAccount($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $response = [];

    try {
        mysqli_query($conn, "DELETE FROM account WHERE email = '$email'");
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function forgetPassword($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $otp = htmlspecialchars($data->otp, ENT_QUOTES, 'UTF-8');
    $response = [];

    try {
        $stmt = mysqli_prepare($conn, "SELECT otp_code FROM otp WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($otp === $row['otp_code']) {
            $response['code'] = 200;

            $stmt = mysqli_prepare($conn, "DELETE FROM otp WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
        } else {
            $response['code'] = 400;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function changePassword($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($data->password, ENT_QUOTES, 'UTF-8');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $response = [];

    try {
        mysqli_query($conn, "UPDATE account SET password = '$hashedPassword' WHERE email = '$email'");
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function sendVerificationEmail($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $mail = new PHPMailer(true);
    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $otpCode = rand(1000, 9999);

    $response = [];

    $result = mysqli_query($conn, "SELECT email FROM account WHERE email = '$email'");

    if (mysqli_fetch_assoc($result)) {
        $response["code"] = 400;
    } else {
        $stmt = mysqli_prepare($conn, "REPLACE INTO otp (email, otp_code) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $email, $otpCode);
        mysqli_stmt_execute($stmt);

        try {
            // Konfigurasi server email
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bengkel.mechaban@gmail.com';
            $mail->Password   = $_ENV['APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Penerima email
            $mail->setFrom('verification@mechaban.com', 'Mechaban');
            $mail->addAddress($email);

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Akun';
            $mail->Body    = 'Masukkan kode OTP berikut di Mechaban untuk memvalidasi email Anda: <b>' . $otpCode . '</b>';

            $mail->send();
            $response['code'] = 200;
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
        }
    }

    return $response;
}

function verificationForgetPassword($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $mail = new PHPMailer(true);
    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $otpCode = rand(1000, 9999);

    $response = [];

    $result = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");

    if (mysqli_num_rows($result) > 0) {
        $stmt = mysqli_prepare($conn, "REPLACE INTO otp (email, otp_code) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $email, $otpCode);
        mysqli_stmt_execute($stmt);

        try {
            // Konfigurasi server email
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bengkel.mechaban@gmail.com';
            $mail->Password   = $_ENV['APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Penerima email
            $mail->setFrom('verification@mechaban.com', 'Mechaban');
            $mail->addAddress($email);

            // Isi email
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Akun';
            $mail->Body    = 'Masukkan kode OTP berikut di Mechaban untuk memvalidasi email Anda: <b>' . $otpCode . '</b>';

            $mail->send();
            $response['code'] = 200;
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
        }
    } else {
        $response['code'] = 404;
    }

    return $response;
}

function resendCodeOtp($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $mail = new PHPMailer(true);
    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $otpCode = rand(1000, 9999);

    $response = [];

    $stmt = mysqli_prepare($conn, "REPLACE INTO otp (email, otp_code) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $email, $otpCode);
    mysqli_stmt_execute($stmt);

    try {
        // Konfigurasi server email
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bengkel.mechaban@gmail.com';
        $mail->Password   = $_ENV['APP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Penerima email
        $mail->setFrom('verification@mechaban.com', 'Mechaban');
        $mail->addAddress($email);

        // Isi email
        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Akun';
        $mail->Body    = 'Masukkan kode OTP berikut di Mechaban untuk memvalidasi email Anda: <b>' . $otpCode . '</b>';

        $mail->send();
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readMontir($data, $conn)
{
    $response = [];

    try {
        $result = mysqli_query($conn, "SELECT name, email, no_hp, photo FROM account WHERE role = 'montir'");
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $response['code'] = 200;
        $response['list'] = $data;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

if (isset($data->action)) {
    if ($data->action === 'login' && isset($data->email) && isset($data->password)) {
        $response = login($data, $conn);
    } elseif ($data->action === 'register' && isset($data->email) && isset($data->name) && isset($data->no_hp) && isset($data->password) && isset($data->otp)) {
        $response = register($data, $conn);
    } elseif ($data->action === 'read' && isset($data->email)) {
        $response = readAccount($data, $conn);
    } elseif ($data->action === 'update' && isset($data->email) && isset($data->emailUpdate) && isset($data->name) && isset($data->no_hp)) {
        $response = updateAccount($data, $conn);
    } elseif ($data->action === 'delete' && isset($data->email)) {
        $response = deleteAccount($data, $conn);
    } elseif ($data->action === 'verification' && isset($data->email)) {
        $response = sendVerificationEmail($data, $conn);
    } elseif ($data->action === 'verification_forget' && isset($data->email)) {
        $response = verificationForgetPassword($data, $conn);
    } elseif ($data->action === 'forget_password' && isset($data->email) && isset($data->otp)) {
        $response = forgetPassword($data, $conn);
    } elseif ($data->action === 'change_password' && isset($data->email) && isset($data->password)) {
        $response = changePassword($data, $conn);
    } elseif ($data->action === 'resend_code_otp' && isset($data->email)) {
        $response = resendCodeOtp($data, $conn);
    } elseif ($data->action === 'read_montir') {
        $response = readMontir($data, $conn);
    } else {
        $response["code"] = 400;
        $response["message"] = "Parameter tidak lengkap";
    }
} else {
    $response["code"] = 400;
    $response["message"] = "Aksi tidak diketahui";
}

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
