<?php
include("connect.php");

if ($_POST) {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($_POST['no_hp'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $confirm_password = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8');

    $response = [];
    $result = mysqli_query($conn, "SELECT email FROM account WHERE email = '$email'");

    if (mysqli_fetch_assoc($result)) {
        $response["status"] = false;
        $response["message"] = "Email telah terdaftar";
    } elseif ($password != $confirm_password) {
        $response["status"] = false;
        $response["message"] = "Password tidak sama";
    } else {
        try {
            $sql = "INSERT INTO account (email, name, no_hp, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $name, $no_hp, $password);
            mysqli_stmt_execute($stmt);
            $response["status"] = true;
            $response["message"] = "Registrasi berhasil";
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
}

mysqli_close($conn);
