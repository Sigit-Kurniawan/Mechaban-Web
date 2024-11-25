<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="servis.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <button class="tambah-montir" id="myBtn">Tambah Servis</button>

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="servis-tambah">
                            <h2 id="modalTitle">Form Tambah Servis</h2>

                            <div class="form">
                                <?php if ($errors): ?>
                                    <div class="errors">
                                        <?php foreach ($errors as $error): ?>
                                            <p><?php echo $error; ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <form id="formMontir" action="" method="post">
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name" id="name" placeholder="Nama" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="No HP" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password" required>
                                        <span class="password-hint">Password must be at least 8 characters, including uppercase letters, numbers, and symbols (@$!%*?&)</span>
                                    </div>
                                    <input type="hidden" name="edit_email" id="edit_email">
                                    <div class="input">
                                        <input type="submit" name="simpan" value="Simpan Data" class="btn-simpan">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="servis.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>