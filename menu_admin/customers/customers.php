<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

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
    <link rel="stylesheet" href="cus.css">
</head>

<body>
    <div class="container">
        <?php include_once '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <!-- Trigger/Open The Modal -->
                <button id="myBtn">Open Modal</button>

                <!-- The Modal -->
                <div id="myModal" class="modal">

                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="cus-tambah">
                            <h2 id="modalTitle" >Form Tambah Customers</h2>

                            <div class="form">
                                <form id="formMobil" action="" method="post">
                                    <div class="formLabel">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name" id="name">
                                    </div>
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email">
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp">
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="text" name="password" id="password">
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="cus-view">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Photo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $tampil = mysqli_query($conn, "SELECT * FROM account WHERE role = 'customer' ORDER BY name DESC");
                        if ($tampil && mysqli_num_rows($tampil) > 0) {
                            while ($data = mysqli_fetch_array($tampil)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($data['name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($data['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($data['no_hp'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($data['photo'] ?? 'N/A'); ?></td>
                                    <td></td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6'>No data found</td></tr>";
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

    <script src="cus.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>