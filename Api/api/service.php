<?=
header("Content-Type: application/json");
include("connect.php");

$data = json_decode(file_get_contents("php://input"));
$response = [];

function readService($conn)
{
    $response = array();

    try {
        $result = mysqli_query($conn, "SELECT * FROM view_servis");
        while ($data = mysqli_fetch_assoc($result)) {
            $component = $data['nama_komponen'];
            if (!isset($service[$component])) {
                $service[$component] = array();
            }

            $service[$component][] = array(
                'id_data_servis' => $data['id_data_servis'],
                'nama_servis' => $data['nama_servis'],
                'harga_servis' => $data['harga_servis'],
            );
        }

        $response['data'] = $service;
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    return $response;
}

$response = readService($conn);

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
