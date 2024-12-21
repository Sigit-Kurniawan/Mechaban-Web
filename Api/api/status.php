<?=
header("Content-Type: application/json");
include("connect.php");

$data = json_decode(file_get_contents("php://input"));
$response = [];

$result = mysqli_query($conn, "SELECT status_bengkel FROM status");
$row = mysqli_fetch_assoc($result);

try {
    if (mysqli_num_rows($result) > 0) {
        $response["code"] = 200;
        $response["data"] = $row["status_bengkel"];
    } else {
        $response["code"] = 404;
    }
} catch (Exception $e) {
    $response['code'] = 500;
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
