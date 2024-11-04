<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include '../../Api/koneksi.php';

// Get montir data based on ID
if (isset($_GET['id'])) {
    $id_montir = $_GET['id'];
    $query = "SELECT * FROM montir WHERE id_montir = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $id_montir);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        header("Location: montir.php");
        exit();
    }
} else {
    header("Location: montir.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/project3/assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="assets/montir.css">
</head>

<body>
    <div class="container">
        <?php include_once '../sidebar.php'; ?>
        <div class="main">
            <div class="delete-confirmation">
                <h2>Konfirmasi Penghapusan</h2>
                <div class="montir-details">
                    <p><strong>ID:</strong> <?php echo htmlspecialchars($data['id_montir']); ?></p>
                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($data['nama_montir']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($data['email']); ?></p>
                    <p><strong>No. HP:</strong> <?php echo htmlspecialchars($data['no_hp']); ?></p>
                </div>
                <p>Apakah Anda yakin ingin menghapus data montir ini?</p>
                <div class="button-group">
                    <form action="proses.php" method="post">
                        <input type="hidden" name="tid" value="<?php echo htmlspecialchars($data['id_montir']); ?>">
                        <button type="submit" name="bdelete" class="btn-delete">Hapus</button>
                    </form>
                    <a href="montir.php" class="btn-cancel">Batal</a>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="montir.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>