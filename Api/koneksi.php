<?php
$host = 'localhost';
$user = 'mece5739_mechaban';
$password = 'Mechaban1234@#$%^';
$database = 'mece5739_mechaban';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
