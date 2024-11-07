<?php
session_start();
include '../../Api/koneksi.php';

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['bsimpan'])) {
    // Get form data
    $email = $_POST['tid'];
    $nama = $_POST['tnama'];
    $no_hp = $_POST['tno_hp'];
    $password = $_POST['tpassword']; // Password stored without hashing

    // Check if email already exists
    $checkEmail = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");

    if (mysqli_num_rows($checkEmail) == 0) { // Only insert if email does not exist
        $simpan = mysqli_query($conn, "INSERT INTO account (email, name, no_hp, password, role)
                                    VALUES ('$email', '$nama', '$no_hp', '$password', 'montir')");
        if ($simpan) {
            echo "<script>
                    alert('Data saved successfully.');
                    window.location.href = 'montir.php';
                  </script>";
        } else {
            echo "<script>alert('Failed to save data.');</script>";
        }
    } else {
        echo "<script>alert('Email already exists.');</script>";
    }
}

if (isset($_POST['bupdate'])) {
    $email = $_POST['temail'];
    $nama = $_POST['tnama'];
    $no_hp = $_POST['tno_hp'];
    $password = $_POST['tpassword'];
    $emailUpdate = $_POST['temailupdate'];

    // If new password is provided, use it; otherwise, use the old password
    if (!empty($_POST['tpassword'])) {
        $password = $_POST['tpassword']; // Store password without hashing
    } else {
        $password = $_POST['old_password']; // Use the existing password
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
    $email = $_POST['tid'];

    $check_query = "SELECT email FROM account WHERE email = ? AND role = 'montir'";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM account WHERE email = ? AND role = 'montir'";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "s", $email);
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