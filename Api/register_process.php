<?php
include("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($_POST['no_hp'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $konfirm_password = htmlspecialchars($_POST['konfirm_password'], ENT_QUOTES, 'UTF-8');
    $role = isset($_POST['role']) ? htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8') : '';

    // validasi input agar tidak kososng
    if (empty($name) || empty($email) || empty($no_hp) || empty($password)) {
        header("Location: ../register.php?error=empty_fields");
        exit();
    }


    // validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=email_invalid");
        exit();
    }

    //validasi password
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$/', $password)) {
        header("Location: ../register.php?error=password_invalid");
        exit();
    }

    //validasi konfirm password
    if ($password !== $konfirm_password) {
        header("Location: ../register.php?error=konfirm_password_invalid");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    //mengecek apakah email sudah ada di database
    $result = mysqli_query($conn, "SELECT email FROM account WHERE email = '$email'");
    if (mysqli_fetch_assoc($result)) {
        header("Location: ../register.php?error=email_exists");
        mysqli_free_result($result);

    } else {
        try {
            //memasukkan data ke database
            $sql = "INSERT INTO account (name, email, no_hp, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $no_hp, $hashed_password, $role);
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../login.php");
                    exit();
                } else {
                    throw new Exception("Gagal mengeksekusi query: " . mysqli_stmt_error($stmt));
                }
            } else {
                throw new Exception("Gagal mempersiapkan query: " . mysqli_error($conn));
            }
        } catch (Exception $e) {
            //pesan kesalahan
            echo $e->getMessage();
        }
    }
}

mysqli_close($conn);
