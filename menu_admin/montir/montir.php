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

                <button id="myBtn">Tambah Montir</button>

                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="montir-tambah">
                            <h2>Form Tambah Montir</h2>
                            <div class="form">
                                <?php if ($errors): ?>
                                    <div class="errors">
                                        <?php foreach ($errors as $error): ?>
                                            <p><?php echo $error; ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <form action="" method="post">
                                    <div class="formLabel">
                                        <label for="id_montir">ID</label>
                                        <input type="text" name="id_montir" id="fId" placeholder="Id.."
                                            value="<?php echo htmlspecialchars($id_montir); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="nama_montir">Nama</label>
                                        <input type="text" name="nama_montir" id="nama_montir" placeholder="Nama"
                                            value="<?php echo htmlspecialchars($nama_montir); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="No HP"
                                            value="<?php echo htmlspecialchars($no_hp); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password">
                                        <span>Password must be at least 8 characters, including uppercase letters,
                                            numbers, and symbols (@$!%*?&).</span>
                                    </div>
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email"
                                            value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="input">
                                        <input type="submit" name="simpan" value="Save Data" class="btn">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="montir-view">
                    <div class="cardHeader">
                        <h2>Montir</h2>
                        <button><a href="tambah.php" class="btn-tambah">Tambah</a></button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Password</th>
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