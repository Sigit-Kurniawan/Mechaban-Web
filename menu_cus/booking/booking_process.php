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
    $metode_bayar = $_POST['metode_bayar'];

    // Mendapatkan email customer dari sesi
    $email_customer = $_SESSION["email"];

    // Menghitung total biaya dari servis yang dipilih
    $total_biaya = 0;
    $servis_ids = [];
    if (isset($_POST['servis'])) {
        $servis_ids = $_POST['servis'];
        foreach ($servis_ids as $servis_id) {
            // Ambil harga servis
            $query_servis = "SELECT harga_servis FROM data_servis WHERE id_data_servis = '$servis_id'";
            $result_servis = mysqli_query($conn, $query_servis);
            if ($result_servis && mysqli_num_rows($result_servis) > 0) {
                $servis = mysqli_fetch_assoc($result_servis);
                $total_biaya += $servis['harga_servis'];
            }
        }
    }

    // Generate id_booking dengan format B0001, B0002, dst
    $query_last_booking = "SELECT id_booking FROM booking ORDER BY id_booking DESC LIMIT 1";
    $result_last_booking = mysqli_query($conn, $query_last_booking);
    $last_booking = mysqli_fetch_assoc($result_last_booking);
    if ($last_booking) {
        $last_id = substr($last_booking['id_booking'], 1); // Mengambil angka setelah "B"
        $new_id = 'B' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $new_id = 'B0001';
    }

    // Insert data booking
    $tgl_booking = date('Y-m-d');
    $query_booking = "INSERT INTO booking (id_booking, tgl_booking, total_biaya, metode_bayar, nopol, status_bayar, status_pengerjaan)
                      VALUES ('$new_id', '$NOW(),', '$total_biaya', 'Tunai', '$mobil', 'belum', 'diterima')";

    if (mysqli_query($conn, $query_booking)) {
        $id_booking = $new_id;

        // Generate id_detail_booking dengan format D0001, D0002, dst
        $query_last_detail_booking = "SELECT id_detail_booking FROM detail_booking ORDER BY id_detail_booking DESC LIMIT 1";
        $result_last_detail_booking = mysqli_query($conn, $query_last_detail_booking);
        $last_detail_booking = mysqli_fetch_assoc($result_last_detail_booking);

        if ($last_detail_booking) {
            $last_detail_id = substr($last_detail_booking['id_detail_booking'], 1); // Mengambil angka setelah "D"
            $new_detail_id = 'D' . str_pad($last_detail_id + 1, 4, '0', STR_PAD_LEFT);
        } else {

            $new_detail_id = 'D0001';
        }

        // Insert ke tabel detail_booking
        $query_detail_booking = "INSERT INTO detail_booking (id_detail_booking, id_booking, subtotal) 
                                 VALUES ('$new_detail_id', '$id_booking', '$total_biaya')";
        mysqli_query($conn, $query_detail_booking);

        // Insert ke detail_servis_booking
        foreach ($servis_ids as $servis_id) {
            $query_detail_servis = "INSERT INTO detail_servis_booking (id_detail_booking, id_data_servis) 
                                    VALUES ('$new_detail_id', '$servis_id')";
            mysqli_query($conn, $query_detail_servis);
        }


        echo "<script>alert('Booking berhasil'); window.location.href = 'booking.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>