<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

include  '../../Api/koneksi.php';

// Get montir data based on ID
if (isset($_GET['id'])) {
    $id_montir = $_GET['id'];
    $query = "SELECT * FROM account WHERE email = ?";
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
            <div class="form">
                <form action="proses.php" method="post">
                    <input type="hidden" name="temail" value="<?= htmlspecialchars($data['email']);?>">
                    <div>
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="temailupdate" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="tnama" value="<?php echo htmlspecialchars($data['name']); ?>" required>
                    </div>
                    <div>
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control" name="tno_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="tpassword" placeholder="Enter new password to change" value="<?php echo htmlspecialchars($data['password']); ?>">
                        <input type="hidden" name="old_password" value="<?php echo htmlspecialchars($data['password']); ?>">
                    </div>
                    <!-- <div>
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="temail" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div> -->
                    <div>
                        <input type="hidden" name="update" value="true">
                        <button type="submit" class="btn" name="bupdate">Update</button>
                        <a href="montir.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="assets/montir.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>