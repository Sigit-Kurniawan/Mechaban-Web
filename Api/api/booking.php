<?=
header("Content-Type: application/json");
include("connect.php");
date_default_timezone_set("Asia/Jakarta");

$data = json_decode(file_get_contents("php://input"));
$response = [];

function createBooking($data, $conn)
{
    $idBooking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $nopol = htmlspecialchars($data->nopol, ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($data->tgl_booking, ENT_QUOTES, 'UTF-8');
    $status = "pending";
    $latitude = htmlspecialchars($data->latitude, ENT_QUOTES, 'UTF-8');
    $longitude = htmlspecialchars($data->longitude, ENT_QUOTES, 'UTF-8');
    $services = $data->services;
    $response = [];

    try {
        $sql = "INSERT INTO booking (id_booking, tgl_booking, nopol, status, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $idBooking, $date, $nopol, $status, $latitude, $longitude);
        mysqli_stmt_execute($stmt);

        $sql = "INSERT INTO detail_servis (id_booking, id_data_servis) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        foreach ($services as $item) {
            $idDataServis = htmlspecialchars($item->id_data_servis, ENT_QUOTES, 'UTF-8');
            mysqli_stmt_bind_param($stmt, "ss", $idBooking, $idDataServis);
            mysqli_stmt_execute($stmt);
        }

        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readBooking($data, $conn)
{
    $idBooking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');

    $response = [];

    try {
        $sql = "SELECT 
                    b.id_booking,
                    b.tgl_booking,
                    b.latitude,
                    b.longitude,
                    acc_customer.name,
                    acc_customer.email,
                    acc_customer.no_hp,
                    c.nopol,
                    c.merk,
                    c.type,
                    c.transmition,
                    c.year,
                    b.status,
                    ds.nama_servis,
                    ds.harga_servis,
                    acc_ketua.email AS email_ketua_montir,
                    acc_ketua.name AS ketua_montir,
                    acc_ketua.no_hp AS no_hp_ketua,
                    acc_anggota.email AS email_anggota_montir,
                    acc_anggota.name AS anggota_montir,
                    b.total_biaya,
                    rc.rating,
                    rc.teks_review
                FROM 
                    booking b
                LEFT JOIN car c ON b.nopol = c.nopol
                LEFT JOIN account acc_customer ON acc_customer.email = c.email_customer
                LEFT JOIN detail_servis dsv ON b.id_booking = dsv.id_booking
                LEFT JOIN data_servis ds ON dsv.id_data_servis = ds.id_data_servis
                LEFT JOIN detail_montir dm ON b.id_booking = dm.id_booking
                LEFT JOIN account acc_ketua ON dm.email_ketua_montir = acc_ketua.email
                LEFT JOIN anggota_montir am ON dm.id_detail_montir = am.id_detail_montir
                LEFT JOIN account acc_anggota ON am.email_anggota_montir = acc_anggota.email
                LEFT JOIN review_customer rc ON b.id_booking = rc.id_booking
                WHERE b.id_booking = '$idBooking'
                ORDER BY b.id_booking, ds.nama_servis, acc_anggota.name";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $response['code'] = 200;

            while ($row = mysqli_fetch_assoc($result)) {
                if (!isset($response['data'])) {
                    $response['data'] = [
                        'id_booking' => $row['id_booking'],
                        'tgl_booking' => $row['tgl_booking'],
                        'latitude' => $row['latitude'],
                        'longitude' => $row['longitude'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'no_hp' => $row['no_hp'],
                        'nopol' => $row['nopol'],
                        'merk' => $row['merk'],
                        'type' => $row['type'],
                        'transmition' => $row['transmition'],
                        'year' => $row['year'],
                        'status' => $row['status'],
                        'email_ketua_montir' => $row['email_ketua_montir'],
                        'ketua_montir' => $row['ketua_montir'],
                        'no_hp_ketua' => $row['no_hp_ketua'],
                        'total_biaya' => $row['total_biaya'],
                        'rating' => $row['rating'],
                        'teks_review' => $row['teks_review'],
                        'role' => null,
                        'services' => [],
                        'anggota_montir' => []
                    ];
                }

                if ($email === $row['email']) {
                    $response['data']['role'] = 'customer';
                } elseif ($email === $row['email_ketua_montir']) {
                    $response['data']['role'] = 'ketua';
                } elseif ($email === $row['email_anggota_montir']) {
                    $response['data']['role'] = 'anggota';
                }

                if (!in_array(['nama_anggota' => $row['anggota_montir']], $response['data']['anggota_montir'])) {
                    $response['data']['anggota_montir'][] = [
                        'nama_anggota' => $row['anggota_montir']
                    ];
                }

                $serviceEntry = [
                    'nama_servis' => $row['nama_servis'],
                    'harga_servis' => $row['harga_servis']
                ];

                if (!in_array($serviceEntry, $response['data']['services'])) {
                    $response['data']['services'][] = $serviceEntry;
                }
            }
        } else {
            $response['code'] = 404;
            $response['message'] = 'Data tidak ditemukan';
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readListBooking($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');

    $response = [];

    try {
        $sql = "SELECT 
                    b.id_booking, 
                    b.tgl_booking, 
                    c.nopol, 
                    c.merk, 
                    c.type, 
                    b.status, 
                    b.total_biaya 
                FROM booking b
                JOIN car c ON b.nopol = c.nopol 
                JOIN account a ON c.email_customer = a.email
                WHERE a.email = '$email' 
                GROUP BY b.id_booking 
                ORDER BY b.tgl_booking DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['code'] = 200;
            $list = [];
            while ($data = mysqli_fetch_assoc($result)) {
                $list[] = [
                    'id_booking' => $data['id_booking'],
                    'tgl_booking' => $data['tgl_booking'],
                    'nopol' => $data['nopol'],
                    'merk' => $data['merk'],
                    'type' => $data['type'],
                    'status' => $data['status'],
                    'total_biaya' => $data['total_biaya'],
                ];
            }
            $response['list'] = $list;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readListBookingMontir($data, $conn)
{
    $email = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');

    $response = [];

    try {
        $sql = "SELECT 
                    b.id_booking, 
                    b.tgl_booking, 
                    c.nopol, 
                    c.merk, 
                    c.type, 
                    b.status,
                    b.latitude,
                    b.longitude,
                    CASE 
                        WHEN dm.email_ketua_montir = '$email' THEN 'ketua'
                        WHEN am.email_anggota_montir = '$email' THEN 'anggota'
                        ELSE NULL
                    END AS role
                FROM booking b
                JOIN car c ON b.nopol = c.nopol 
                JOIN detail_montir dm ON b.id_booking = dm.id_booking
                LEFT JOIN anggota_montir am ON dm.id_detail_montir = am.id_detail_montir
                WHERE dm.email_ketua_montir = '$email' OR am.email_anggota_montir = '$email'
                GROUP BY 
                    b.id_booking, 
                    b.tgl_booking, 
                    c.nopol, 
                    c.merk, 
                    c.type, 
                    b.status,
                    b.latitude,
                    b.longitude, 
                    dm.email_ketua_montir, 
                    am.email_anggota_montir 
                ORDER BY b.tgl_booking DESC";
        $result = mysqli_query($conn, $sql);
        $response['code'] = 200;
        $list = [];
        while ($data = mysqli_fetch_assoc($result)) {
            $list[] = [
                'id_booking' => $data['id_booking'],
                'tgl_booking' => $data['tgl_booking'],
                'nopol' => $data['nopol'],
                'merk' => $data['merk'],
                'type' => $data['type'],
                'status' => $data['status'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'role' => $data['role'],
            ];
        }
        $response['list'] = $list;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function readAllBooking($data, $conn)
{
    $response = [];

    try {
        $sql = "SELECT 
                    b.id_booking, 
                    b.tgl_booking,
                    a.name, 
                    a.email,
                    a.no_hp,
                    b.nopol,
                    c.merk,
                    c.type,
                    c.transmition,
                    c.year,
                    b.status, 
                    b.latitude, 
                    b.longitude,
                    ds.nama_servis,
                    ds.harga_servis,
                    b.total_biaya
                FROM booking b
                JOIN car c ON b.nopol = c.nopol
                JOIN account a ON c.email_customer = a.email
                JOIN detail_servis dsb ON b.id_booking = dsb.id_booking
                JOIN data_servis ds ON dsb.id_data_servis = ds.id_data_servis
                WHERE b.status = 'pending'
                ORDER BY b.tgl_booking ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['code'] = 200;
            $bookings = [];

            while ($data = mysqli_fetch_assoc($result)) {
                $id_booking = $data['id_booking'];
                if (!isset($bookings[$id_booking])) {
                    $bookings[$id_booking] = [
                        'id_booking' => $data['id_booking'],
                        'tgl_booking' => $data['tgl_booking'],
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'no_hp' => $data['no_hp'],
                        'nopol' => $data['nopol'],
                        'merk' => $data['merk'],
                        'type' => $data['type'],
                        'transmition' => $data['transmition'],
                        'year' => $data['year'],
                        'status_pengerjaan' => $data['status'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'services' => [],
                        'total_biaya' => 0
                    ];
                }

                $bookings[$id_booking]['services'][] = [
                    'nama_servis' => $data['nama_servis'],
                    'harga_servis' => $data['harga_servis']
                ];

                $bookings[$id_booking]['total_biaya'] += $data['harga_servis'];
            }

            $response['list'] = array_values($bookings);
        } else {
            $response['code'] = 404;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function setBookingDiterima($data, $conn)
{
    $idBooking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $montir = $data->emails;

    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < 4; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    $idDetailMontir = "DTS" . $randomString;
    $emailMontir = htmlspecialchars($data->email, ENT_QUOTES, 'UTF-8');

    $response = [];

    $sql = "SELECT * FROM booking WHERE id_booking = '$idBooking' AND status = 'batal'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $response['code'] = 400;
    } else {
        try {
            $sql = "INSERT INTO detail_montir (id_detail_montir, id_booking, email_ketua_montir) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $idDetailMontir, $idBooking, $emailMontir);
            mysqli_stmt_execute($stmt);

            foreach ($montir as $email) {
                $sql = "INSERT INTO anggota_montir (id_detail_montir, email_anggota_montir) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $idDetailMontir, $email);
                mysqli_stmt_execute($stmt);
            }

            $response['code'] = 200;
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
        }
    }

    return $response;
}

function setStatusBooking($data, $conn)
{
    $idBooking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $status = htmlspecialchars($data->status, ENT_QUOTES, 'UTF-8');

    if ($status == 'diterima') {
        $status = 'dikerjakan';
    } elseif ($status == 'dikerjakan') {
        $status = 'selesai';
    } else {
        $status = null;
    }

    $response = [];

    try {
        $sql = "UPDATE booking SET status = '$status' WHERE id_booking = '$idBooking'";
        mysqli_query($conn, $sql);

        if ($status == 'selesai') {
            $sql = "
                SELECT 
                    dm.email_ketua_montir AS email_ketua,
                    am.email_anggota_montir AS email_anggota
                FROM 
                    detail_montir dm
                LEFT JOIN 
                    anggota_montir am ON dm.id_detail_montir = am.id_detail_montir
                WHERE 
                    dm.id_booking = ?
            ";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $idBooking);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $sqlUpdate = "UPDATE account SET status = 0 WHERE email = ?";
            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);

            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($row['email_ketua'])) {
                    mysqli_stmt_bind_param($stmtUpdate, "s", $row['email_ketua']);
                    mysqli_stmt_execute($stmtUpdate);
                }

                if (!empty($row['email_anggota'])) {
                    mysqli_stmt_bind_param($stmtUpdate, "s", $row['email_anggota']);
                    mysqli_stmt_execute($stmtUpdate);
                }
            }
        }

        if (mysqli_affected_rows($conn) > 0) {
            $response['code'] = 200;
        }
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function setRating($data, $conn)
{
    $idBooking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $rating = htmlspecialchars($data->rating, ENT_QUOTES, 'UTF-8');
    $review = htmlspecialchars($data->review, ENT_QUOTES, 'UTF-8');
    $tglBooking = htmlspecialchars($data->tgl_booking, ENT_QUOTES, 'UTF-8');
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    $idReview = "RC" . $randomString;

    $response = [];

    try {
        $sql = "INSERT review_customer (id_review, id_booking, teks_review, rating, tgl_review) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $idReview, $idBooking, $review, $rating, $tglBooking);
        mysqli_stmt_execute($stmt);

        $response['code'] = 200;
    } catch (Exception $e) {
        $response['code'] = 500;
        $response['message'] = $e->getMessage();
    }

    return $response;
}

function cancelBooking($data, $conn)
{
    $id_booking = htmlspecialchars($data->id_booking, ENT_QUOTES, 'UTF-8');
    $response = [];

    $sql = "SELECT * FROM booking WHERE id_booking = '$id_booking' AND status = 'diterima'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $response['code'] = 400;
    } else {
        try {
            $sql = "UPDATE booking SET status = 'batal' WHERE id_booking = '$id_booking'";
            mysqli_query($conn, $sql);
            if (mysqli_affected_rows($conn) > 0) {
                $response['code'] = 200;
            }
        } catch (Exception $e) {
            $response['code'] = 500;
            $response['message'] = $e->getMessage();
        }
    }

    return $response;
}

if (isset($data->action)) {
    if ($data->action === 'create' && isset($data->id_booking) && isset($data->tgl_booking) && isset($data->nopol) && isset($data->latitude) && isset($data->longitude) && isset($data->services)) {
        $response = createBooking($data, $conn);
    } elseif ($data->action === 'read' && isset($data->id_booking) && isset($data->email)) {
        $response = readBooking($data, $conn);
    } elseif ($data->action === 'list' && isset($data->email)) {
        $response = readListBooking($data, $conn);
    } elseif ($data->action === 'list_montir' && isset($data->email)) {
        $response = readListBookingMontir($data, $conn);
    } elseif ($data->action === 'all') {
        $response = readAllBooking($data, $conn);
    } elseif ($data->action === 'status' && isset($data->id_booking) && isset($data->status)) {
        $response = setStatusBooking($data, $conn);
    } elseif ($data->action === 'diterima' && isset($data->id_booking) && isset($data->email) && isset($data->emails)) {
        $response = setBookingDiterima($data, $conn);
    } elseif ($data->action === 'rating' && isset($data->id_booking) && isset($data->rating) && isset($data->review) && isset($data->tgl_booking)) {
        $response = setRating($data, $conn);
    } elseif ($data->action === 'cancel' && isset($data->id_booking)) {
        $response = cancelBooking($data, $conn);
    } else {
        $response["code"] = 400;
        $response["message"] = "Parameter tidak lengkap";
    }
} else {
    $response["code"] = 400;
    $response["message"] = "Parameter tidak lengkap";
}

echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
