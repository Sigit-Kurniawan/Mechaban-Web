<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include  '../../Api/koneksi.php';
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
                    <div>
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="tid" required>
                    </div>
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="tnama" required>
                    </div>
                    <div>
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control" name="tno_hp" required>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="tpassword" required>
                    </div>
                    <!-- <div>
                        <label for="role" id="role">Role</label>
                        <select name="role" class="role">s
                            <option value="customer">Montir</option>
                        </select>
                    </div> -->
                    <!-- <div>
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="temail" required>
                    </div> -->
                    <div>
                        <button type="submit" class="btn" name="bsimpan">Simpan</button>
                        <a href="montir.php" class="btn-cancel">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="assets/montir.js"></script>
    <script src="../../../assets/js/main.js"></script>
</body>

</html>
