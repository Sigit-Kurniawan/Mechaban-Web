<?php
include("connect.php");

if ($_POST) {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    $response = [];
    $result = mysqli_query($conn, "SELECT email, password FROM account WHERE email = '$email' AND password = '$password'");

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($email === $row["email"] && $password === $row["password"]) {
            $response["status"] = true;
            $response["message"] = "Login berhasil";
            $response["data"] = [
                'email' => $row["email"]
            ];
        } else {
            $response["status"] = false;
            $response["message"] = "Email atau password salah";
        }
    } else {
        $response["status"] = false;
        $response["message"] = "Email belum terdaftar";
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
}

mysqli_close($conn);
