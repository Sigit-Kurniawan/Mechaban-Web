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

    // Menghitung total biaya dari servis dan barang yang dipilih
    $total_biaya = 0;
    $servis_ids = [];
    $barang_ids = [];

    // Perhitungan harga servis dan harga barang
    if (isset($_POST['servis']) || isset($_POST['barang'])) {
        if (isset($_POST['servis'])) {
            $servis_ids = $_POST['servis'];
            foreach ($servis_ids as $servis_id) {
                $query_servis = "SELECT harga_servis FROM data_servis WHERE id_data_servis = '$servis_id'";
                $result_servis = mysqli_query($conn, $query_servis);
                if ($result_servis && mysqli_num_rows($result_servis) > 0) {
                    $servis = mysqli_fetch_assoc($result_servis);
                    $total_biaya += $servis['harga_servis'];
                }
            }
        }

        if (isset($_POST['barang'])) {
            $barang_ids = $_POST['barang'];
            foreach ($barang_ids as $barang_id) {
                $query_barang = "SELECT harga FROM barang WHERE id_barang = '$barang_id'";
                $result_barang = mysqli_query($conn, $query_barang);
                if ($result_barang && mysqli_num_rows($result_barang) > 0) {
                    $barang = mysqli_fetch_assoc($result_barang);
                    $total_biaya += $barang['harga'];
                }
            }
        }
    }

    date_default_timezone_set('Asia/Jakarta');

    // Format id_booking = Tanggal+Waktu+Nopol (YYYYMMDDHHMMSSNOPOL)
    $tgl_booking = date('YmdHis'); // Format YYYYMMDDHHMMSS
    $id_booking = $tgl_booking . $mobil; // Tanpa pemisah (-)

    // Insert data booking
    $query_booking = "INSERT INTO booking (id_booking, tgl_booking, total_biaya, metode_bayar, nopol, status_bayar, status_pengerjaan, latitude, longitude)
                      VALUES ('$id_booking', NOW(), '$total_biaya', 'Tunai', '$mobil', 'belum', 'pending', '$latitude', '$longitude')";

    if (mysqli_query($conn, $query_booking)) {
        // Generate id_detail_booking = Waktu+Nopol (HHMMSSNOPOL)
        $waktu_booking = date('His'); // Format HHMMSS
        $id_detail_booking = $waktu_booking . $mobil; // Tanpa pemisah (-)

        // Insert ke tabel detail_booking
        $query_detail_booking = "INSERT INTO detail_booking (id_detail_booking, id_booking, subtotal) 
                                 VALUES ('$id_detail_booking', '$id_booking', '$total_biaya')";
        mysqli_query($conn, $query_detail_booking);

        // Insert ke detail_servis_booking
        foreach ($servis_ids as $servis_id) {
            $query_detail_servis = "INSERT INTO detail_servis_booking (id_detail_booking, id_data_servis) 
                                    VALUES ('$id_detail_booking', '$servis_id')";
            mysqli_query($conn, $query_detail_servis);
        }

        // Insert ke detail_barang_booking
        foreach ($barang_ids as $barang_id) {
            $query_detail_barang = "INSERT INTO detail_barang_booking (id_detail_booking, id_barang) 
                                    VALUES ('$id_detail_booking', '$barang_id')";
            mysqli_query($conn, $query_detail_barang);
        }

        echo "<script>alert('Booking berhasil'); window.location.href = 'booking.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>