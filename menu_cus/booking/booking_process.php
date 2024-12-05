<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

include_once('../../Api/koneksi.php');

if (isset($_POST['submit_booking'])) {
    // Ambil data dari form
    $mobil = $_POST['nopol'];
    $komponen_id = $_POST['komponen'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $email_customer = $_SESSION["email"];

    // Menghitung total biaya dari servis yang dipilih
    $total_biaya = 0;
    $servis_ids = [];

    if (isset($_POST['servis'])) {
        $servis_ids = $_POST['servis'];
        foreach ($servis_ids as $servis_id) {
            // Mengambil harga servis dari data_servis
            $query_servis = "SELECT harga_servis FROM data_servis WHERE id_data_servis = ?";
            if ($stmt = mysqli_prepare($conn, $query_servis)) {
                mysqli_stmt_bind_param($stmt, "s", $servis_id); // Ganti "i" ke "s" karena id_data_servis adalah CHAR
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $harga_servis);
                if (mysqli_stmt_fetch($stmt)) {
                    $total_biaya += $harga_servis; // Menjumlahkan harga_servis ke total_biaya
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    date_default_timezone_set('Asia/Jakarta');

    // Format id_booking = Tanggal+Waktu+Nopol (YYYYMMDDHHMMSSNOPOL)
    $tgl_booking = date('YmdHis'); // Format YYYYMMDDHHMMSS
    $id_booking = $tgl_booking . $mobil; // Tanpa pemisah (-)

    // Insert data booking with prepared statement
    $query_booking = "INSERT INTO booking (id_booking, tgl_booking, total_biaya, nopol, status, latitude, longitude)
                      VALUES (?, NOW(), ?, ?, 'pending', ?, ?)";
    if ($stmt = mysqli_prepare($conn, $query_booking)) {
        mysqli_stmt_bind_param($stmt, "sdsdd", $id_booking, $total_biaya, $mobil, $latitude, $longitude);
        if (mysqli_stmt_execute($stmt)) {
            // Insert data into detail_servis for each selected servis
            if (!empty($servis_ids)) {
                foreach ($servis_ids as $servis_id) {
                    echo "id_booking: $id_booking, id_data_servis: $servis_id<br>";

                    $query_detail_servis = "INSERT INTO detail_servis (id_booking, id_data_servis) VALUES (?, ?)";
                    if ($stmt_detail = mysqli_prepare($conn, $query_detail_servis)) {
                        mysqli_stmt_bind_param($stmt_detail, "ss", $id_booking, $servis_id); // Ganti "i" ke "s" karena id_data_servis adalah CHAR
                        if (!mysqli_stmt_execute($stmt_detail)) {
                            echo "Error: " . mysqli_error($conn) . "<br>";
                        }
                        mysqli_stmt_close($stmt_detail);
                    }
                }
            }

            mysqli_stmt_close($stmt);
            // Redirect with success message
            echo "<script>alert('Booking berhasil'); window.location.href = 'booking.php';</script>";
            exit();
        }
    }

    // Display failure message if booking fails
    echo "<script>alert('Gagal booking'); window.location.href = 'booking.php';</script>";
    exit();
}
?>