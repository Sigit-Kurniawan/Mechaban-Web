<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!file_exists('../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../Api/koneksi.php');

// Initialize variables
$errors = [];
$name = $email = $no_hp = '';

// Process form submission for adding/editing customer
if (isset($_POST['simpan'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $no_hp = $_POST['no_hp'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $edit_email = $_POST['edit_email']; // For editing existing records

    // Validation
    if (empty($email) || empty($name) || empty($no_hp)) {
        $errors[] = "Email, Nama, dan No. HP wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } elseif (empty($edit_email) && empty($password)) {
        $errors[] = "Password wajib diisi untuk pelanggan baru.";
    } elseif (!empty($password) && strlen($password) < 8) {
        $errors[] = "Password harus minimal 8 karakter.";
    } else {
        try {
            if (!empty($edit_email)) {
                // Update existing customer
                $query = "UPDATE account SET email = ?, name = ?, no_hp = ?";
                $params = [$email, $name, $no_hp];
                
                if (!empty($password)) {
                    $query .= ", password = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $query .= " WHERE email = ? AND role = 'customer'";
                $params[] = $edit_email;
                
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $param_types = str_repeat('s', count($params));
                $stmt->bind_param($param_types, ...$params);

                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }

                header("Location: customers.php?success=edit");
                exit();
            } else {
                // Add new customer
                // Check if email already exists
                $check_query = "SELECT COUNT(*) FROM account WHERE email = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $check_stmt->bind_result($count);
                $check_stmt->fetch();
                $check_stmt->close();

                if ($count > 0) {
                    $errors[] = "Email sudah terdaftar.";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $query = "INSERT INTO account (email, name, no_hp, password, role) VALUES (?, ?, ?, ?, 'customer')";
                    
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }

                    $stmt->bind_param("ssss", $email, $name, $no_hp, $hashed_password);

                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }

                    header("Location: customers.php?success=save");
                    exit();
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $errors[] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $email_to_delete = $_GET['delete'];

    $delete_query = "DELETE FROM account WHERE email = ? AND role = 'customer'";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $email_to_delete);

    if ($delete_stmt->execute()) {
        header("Location: customers.php?success=delete");
        exit();
    } else {
        $errors[] = "Gagal menghapus data: " . $delete_stmt->error;
    }
    $delete_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/project3/assets/img/logo.png" type="image/png">
    <title>Mechaban - Manajemen Pelanggan</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="cus.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <button id="myBtn" class="tambah-cus">Tambah Customers</button>

                <!-- Modal Form -->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="cus-tambah">
                            <h2 id="modalTitle">Form Tambah Customersn</h2>
                            <?php if (!empty($errors)): ?>
                                <div class="errors">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form">
                                <form id="formCustomer" action="" method="post">
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name" id="name" placeholder="Nama Pelanggan" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="Nomor HP" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password">
                                        <span class="password-hint">Password minimal 8 karakter</span>
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

                <!-- Customers Table -->
                <div class="cus-view">
                    <div class="cardHeader">
                        <h2>Daftar Customers</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM account WHERE role = 'customer' ORDER BY name";
                            $result = $conn->query($query);
                            $no = 1;
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="openEditModal('<?php echo htmlspecialchars($row['email']); ?>', '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['no_hp']); ?>')" class="btn-edit">Edit</a>
                                        <a href="?delete=<?php echo urlencode($row['email']); ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script src="cus.js"></script>
</body>

</html>

<?php $conn->close(); ?>