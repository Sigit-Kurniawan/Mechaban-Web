<?=
header("Content-Type: application/json");
include("connect.php");

$data = json_decode(file_get_contents("php://input"));
$response = [];

function readMontir($data, $conn)
{
    $response = [];

    try {
        $sql = "SELECT name, email, no_hp, photo FROM account WHERE role = 'montir' AND status = 0";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['code'] = 200;
            $list = [];
            while ($data = mysqli_fetch_assoc($result)) {
                $list[] = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'no_hp' => $data['no_hp'],
                    'photo' => $data['photo']
                ];
            }
            $response['list'] = $list;
        } else {
            $response['code'] = 404;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

$response = readMontir($data, $conn);

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
