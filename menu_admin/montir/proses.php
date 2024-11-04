<?php
session_start();
include '../../Api/koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['bsimpan'])) {
    // Get form data
    $id = $_POST['tid'];
    $nama = $_POST['tnama'];
    $no_hp = $_POST['tno_hp'];
    $password = $_POST['tpassword'];
    $email = $_POST['temail'];

    $checkEmail = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $simpan = mysqli_query($conn, "INSERT INTO montir (id_montir, nama_montir, no_hp, password, email)
                                          VALUES ('$id', '$nama', '$no_hp', '$password', '$email')");

        if ($simpan) {
            echo "Data saved successfully.";
        }
    }
}

if (isset($_POST['bupdate'])) {
    $email = $_POST['temail'];
    $nama = $_POST['tnama'];
    $no_hp = $_POST['tno_hp'];
    $password = $_POST['tpassword'];
    $emailUpdate = $_POST['temailupdate'];

    // Jika password baru tidak kosong, gunakan password baru, jika tidak, gunakan password lama
    if (!empty($_POST['tpassword'])) {
        $password = password_hash($_POST['tpassword'], PASSWORD_DEFAULT); // Lakukan hashing pada password baru
    } else {
        $password = $_POST['old_password']; // Menggunakan password lama yang sudah diambil dari database sebelumnya
    }

    $query = "UPDATE account SET 
              email = ?,
              name = ?,
              no_hp = ?,
              password = ?
              WHERE email = ?";
              
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $emailUpdate, $nama, $no_hp, $password, $email);
    
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: montir.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}



if (isset($_POST['bdelete'])) {
    $id = $_POST['tid'];

    $check_query = "SELECT id_montir FROM montir WHERE id_montir = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {

        $delete_query = "DELETE FROM montir WHERE id_montir = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "s", $id);
        $delete_result = mysqli_stmt_execute($delete_stmt);
        
        if ($delete_result) {

            header("Location: montir.php");
            exit();
        } else {

            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {

        echo "Record not found";
    }
}

?>
