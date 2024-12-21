<?php
header("Content-Type: application/json");
include("connect.php");

$data = json_decode(file_get_contents("php://input"));
$response = [];

function createCar($data, $conn)
{
    $nopol = htmlspecialchars($data->nopol, ENT_QUOTES, 'UTF-8');
    $merk = htmlspecialchars($data->merk, ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($data->type, ENT_QUOTES, 'UTF-8');
    $transmition = htmlspecialchars($data->transmition, ENT_QUOTES, 'UTF-8');
    $year = htmlspecialchars($data->year, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');

    $response = [];

    try {
        $sql = "INSERT INTO car (nopol, merk, type, transmition, year, email_customer) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $nopol, $merk, $type, $transmition, $year, $email);
        mysqli_stmt_execute($stmt);
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readCar($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');
    $response = [];

    try {
        $result = mysqli_query($conn, "SELECT * FROM car WHERE email_customer = '$email' ORDER BY status ASC, created DESC");
        if (mysqli_num_rows($result) <= 0) {
            $response['code'] = 200;
        } else {
            $response['code'] = 201;
            $cars = [];
            while ($data = mysqli_fetch_assoc($result)) {
                $cars[] = [
                    'nopol' => $data['nopol'],
                    'merk' => $data['merk'],
                    'type' => $data['type'],
                    'transmition' => $data['transmition'],
                    'year' => $data['year'],
                    'status' => $data['status']
                ];
            }
            $response['cars'] = $cars;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readCarDetail($data, $conn)
{
    $nopol = htmlspecialchars($data->nopol, ENT_QUOTES, 'UTF-8');
    $response = [];

    try {
        $result = mysqli_query($conn, "SELECT * FROM car WHERE nopol = '$nopol'");
        $data = mysqli_fetch_assoc($result);
        $response['code'] = 200;
        $response['data'] = [
            'nopol' => $data['nopol'],
            'merk' => $data['merk'],
            'type' => $data['type'],
            'transmition' => $data['transmition'],
            'year' => $data['year'],
        ];
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function updateCar($data, $conn)
{
    $nopol = htmlspecialchars($data->nopol, ENT_QUOTES, 'UTF-8');
    $nopolUpdate = $data->nopolUpdate;
    $merk = htmlspecialchars($data->merk, ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($data->type, ENT_QUOTES, 'UTF-8');
    $transmition = htmlspecialchars($data->transmition, ENT_QUOTES, 'UTF-8');
    $year = htmlspecialchars($data->year, ENT_QUOTES, 'UTF-8');

    $response = [];

    try {
        mysqli_query($conn, "UPDATE car SET nopol = '$nopolUpdate', merk = '$merk', type = '$type', transmition = '$transmition', year = '$year' WHERE nopol = '$nopol'");
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function deleteCar($data, $conn)
{
    $nopol = htmlspecialchars($data->nopol, ENT_QUOTES, 'UTF-8');
    $response = [];

    try {
        mysqli_query($conn, "DELETE FROM car WHERE nopol = '$nopol'");
        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

if (isset($data->action)) {
    if ($data->action === 'create' && isset($data->nopol) && isset($data->merk) && isset($data->type) && isset($data->transmition) && isset($data->year) && isset($data->email)) {
        $response = createCar($data, $conn);
    } elseif ($data->action === 'read' && isset($data->email)) {
        $response = readCar($data, $conn);
    } elseif ($data->action === 'detail' && isset($data->nopol)) {
        $response = readCarDetail($data, $conn);
    } elseif ($data->action === 'update' && isset($data->nopol) && isset($data->nopolUpdate) && isset($data->merk) && isset($data->type) && isset($data->transmition) && isset($data->year)) {
        $response = updateCar($data, $conn);
    } elseif ($data->action === 'delete' && isset($data->nopol)) {
        $response = deleteCar($data, $conn);
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
