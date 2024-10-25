<?php
include("connect.php");

if ($_POST) {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $response = [];
    try {
        $result = mysqli_query($conn, "SELECT email, name FROM account WHERE email = '$email'");
        $data = mysqli_fetch_assoc($result);
        $response['data'] = [
            'name' => $data['name'],
            'email' => $data['email']
        ];
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
}

mysqli_close($conn);
