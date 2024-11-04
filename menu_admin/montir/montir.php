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
            <?php include '../header.php'; ?>

            <div class="view">
                <div class="montir-view">
                    <div class="cardHeader">
                        <h2>Montir</h2>
                        <button><a href="tambah.php" class="btn-tambah">Tambah</a></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Password</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Initialize the counter
                            $no = 1;

                            $sql4 = "SELECT * FROM account WHERE role = 'montir' ORDER BY email DESC";
                            $q2 = mysqli_query($conn, $sql4);

                            if ($q2) {
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id_montir = htmlspecialchars($r2['email']);
                                    $nama_montir = htmlspecialchars($r2['name']);
                                    $no_hp = htmlspecialchars($r2['no_hp']);
                                    $password = htmlspecialchars($r2['password']);
                                    // $email = htmlspecialchars($r2['email']);
                            ?>
                                    <tr>
                                        <td><?php echo $id_montir; ?></td>
                                        <td><?php echo $nama_montir; ?></td>
                                        <td><?php echo $no_hp; ?></td>
                                        <td><?php echo $password; ?></td>
                                        
                                        <td>
                                            <a href="edit.php?id=<?php echo $id_montir; ?>" class="btn-edit">Edit</a>
                                            <a href="delete.php?id=<?php echo $id_montir; ?>" class="btn-hapus">Hapus</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7'>No data found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="assets/montir.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>