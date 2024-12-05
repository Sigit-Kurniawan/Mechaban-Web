<?php
session_start();

if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

if (!file_exists('../../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../../Api/koneksi.php');
$email_customer = $_SESSION["email"];

// Ambil data dari form
$id_booking = isset($_POST['id_booking']) ? $_POST['id_booking'] : null;
$review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : null;
$rating = isset($_POST['rating']) ? $_POST['rating'] : null;

// Validasi input
if (empty($id_booking) || empty($review_text) || empty($rating)) {
    die("Semua data review harus diisi.");
}

// Validasi rating (harus antara 1 dan 5, sesuai dengan enum di database)
if (!in_array($rating, ['1', '2', '3', '4', '5'])) {
    die("Rating tidak valid.");
}

// Validasi ID booking
$query = "
    SELECT b.id_booking
    FROM booking b
    JOIN car c ON b.nopol = c.nopol
    JOIN account a ON c.email_customer = a.email
    WHERE a.email = ? 
    AND b.id_booking = ? 
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $email_customer, $id_booking);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("ID booking tidak valid.");
}

// Generate ID review unik
$id_review = 'RC' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT) . strtolower(substr(bin2hex(random_bytes(2)), 0, 4));

date_default_timezone_set('Asia/Jakarta');
// Tanggal review dalam format WIB
$tgl_review = date("Y-m-d H:i:s");

// Simpan review ke database dengan rating
$insert_query = "
    INSERT INTO review_customer (id_review, id_booking, teks_review, rating, tgl_review)
    VALUES (?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, "sssis", $id_review, $id_booking, $review_text, $rating, $tgl_review);

if (mysqli_stmt_execute($stmt)) {
    echo "Review berhasil dikirim!";
    header("Location: review.php");
} else {
    echo "Gagal mengirim review. Error: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>