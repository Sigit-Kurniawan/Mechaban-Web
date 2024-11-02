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

$id_montir = $nama_montir = $no_hp = $password = $email = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $id_montir = $_POST['id_montir'];
    $nama_montir = $_POST['nama_montir'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Basic validation
    if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[@$!%*?&]/", $password)) {
        $errors[] = "Password must be at least 8 characters, including uppercase letters, numbers, and symbols.";
    }

    // Check if email exists in `account` table
    $check_email_query = $conn->prepare("SELECT * FROM account WHERE email = ?");
    $check_email_query->bind_param("s", $email);
    $check_email_query->execute();
    $result = $check_email_query->get_result();

    if ($result->num_rows == 0) {
        $errors[] = "The email must exist in the account table.";
    } else if (empty($errors)) {
        // Insert only if the email exists in the account table and no other errors are present
        $stmt = $conn->prepare("INSERT INTO montir (id_montir, nama_montir, no_hp, password, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id_montir, $nama_montir, $no_hp, $password, $email);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $check_email_query->close();


    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO montir (id_montir, nama_montir, no_hp, password, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id_montir, $nama_montir, $no_hp, $password, $email);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
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
    <link rel="stylesheet" href="montir.css">
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
                                        <input type="text" name="id_montir" id="fId" placeholder="Id.." value="<?php echo htmlspecialchars($id_montir); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="nama_montir">Nama</label>
                                        <input type="text" name="nama_montir" id="nama_montir" placeholder="Nama" value="<?php echo htmlspecialchars($nama_montir); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="No HP" value="<?php echo htmlspecialchars($no_hp); ?>">
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password">
                                        <span>Password must be at least 8 characters, including uppercase letters, numbers, and symbols (@$!%*?&).</span>
                                    </div>
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
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
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Password</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql4 = "SELECT * FROM montir ORDER BY id_montir DESC";
                            $q2 = mysqli_query($conn, $sql4);

                            if ($q2) {
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id_montir = htmlspecialchars($r2['id_montir']);
                                    $nama_montir = htmlspecialchars($r2['nama_montir']);
                                    $no_hp = htmlspecialchars($r2['no_hp']);
                                    $password = htmlspecialchars($r2['password']);
                                    $email = htmlspecialchars($r2['email']);
                            ?>
                                    <tr>
                                        <td><?php echo $id_montir; ?></td>
                                        <td><?php echo $nama_montir; ?></td>
                                        <td><?php echo $no_hp; ?></td>
                                        <td><?php echo $password; ?></td>
                                        <td><?php echo $email; ?></td>
                                        <td>
                                            <a href="#" class="btn-edit">Edit</a>
                                            <a href="#" class="btn-hapus">Delete</a>
                                        </td>
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

    <script src="montir.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>