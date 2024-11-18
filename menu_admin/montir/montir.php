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
$email = $nama_montir = $no_hp = '';

// Process form submission for adding/editing montir
if (isset($_POST['simpan'])) {
    $email = $_POST['email'];
    $nama_montir = $_POST['nama_montir'];
    $no_hp = $_POST['no_hp'];
    $type = $_POST['type']; // 'ketua' or 'anggota'
    $edit_email = $_POST['edit_email']; // For editing existing records

    // Get password safely
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validation
    if (empty($email) || empty($nama_montir) || empty($no_hp)) {
        $errors[] = "Email, Nama, dan No. HP wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    } elseif ($type === 'ketua' && (empty($edit_email) && empty($password))) {
        $errors[] = "Password untuk Ketua Montir wajib diisi.";
    } elseif (!empty($password) && strlen($password) < 8) {
        $errors[] = "Password harus minimal 8 karakter.";
    } else {
        try {
            if (!empty($edit_email)) {
                // Update existing montir
                $table = $type . '_montir';
                $email_field = 'email_' . $type . '_montir';
                $nama_field = 'nama_' . $type . '_montir';
                
                $query = "UPDATE $table SET $email_field = ?, $nama_field = ?, no_hp = ?";
                $params = [$email, $nama_montir, $no_hp];
                
                if ($type === 'ketua' && !empty($password)) {
                    $query .= ", password = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $query .= " WHERE $email_field = ?";
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

                header("Location: montir.php?success=edit");
                exit();
            } else {
                // Add new montir
                $table = $type . '_montir';
                $email_field = 'email_' . $type . '_montir';
                $nama_field = 'nama_' . $type . '_montir';
                
                // Check if email already exists
                $check_query = "SELECT COUNT(*) FROM $table WHERE $email_field = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $check_stmt->bind_result($count);
                $check_stmt->fetch();
                $check_stmt->close();

                if ($count > 0) {
                    $errors[] = "Email sudah terdaftar.";
                } else {
                    // Generate a random password for anggota montir
                    $hashed_password = $type === 'ketua' 
                        ? password_hash($password, PASSWORD_DEFAULT) 
                        : password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

                    // Dynamically determine which columns to insert based on the table
                    $columns = [
                        $email_field,
                        $nama_field,
                        'no_hp',
                        'password'
                    ];

                    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
                    $column_list = implode(', ', $columns);

                    $query = "INSERT INTO $table ($column_list) VALUES ($placeholders)";
                    
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }

                    $params = [$email, $nama_montir, $no_hp, $hashed_password];
                    $param_types = str_repeat('s', count($params));
                    $stmt->bind_param($param_types, ...$params);

                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }

                    header("Location: montir.php?success=save");
                    exit();
                }
            }
        } catch (Exception $e) {
            // Log the full error for debugging
            error_log($e->getMessage());
            $errors[] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $email_to_delete = $_GET['delete'];
    $type = $_GET['type']; // 'ketua' or 'anggota'

    $table = $type . '_montir';
    $email_field = 'email_' . $type . '_montir';

    $delete_query = "DELETE FROM $table WHERE $email_field = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $email_to_delete);

    if ($delete_stmt->execute()) {
        header("Location: montir.php?success=delete");
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
    <title>Mechaban - Manajemen Montir</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="montir.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <button id="myBtn" class="tambah-montir">Tambah Montir</button>

                <!-- Modal Form -->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="montir-tambah">
                            <h2 id="modalTitle">Form Tambah Montir</h2>
                            <?php if (!empty($errors)): ?>
                                <div class="errors">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form">
                                <form id="formMontir" action="" method="post">
                                    <div class="formLabel">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="nama_montir">Nama</label>
                                        <input type="text" name="nama_montir" id="nama_montir" placeholder="Nama Montir" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="no_hp">No. HP</label>
                                        <input type="text" name="no_hp" id="no_hp" placeholder="Nomor HP" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password">
                                        <span class="password-hint">Password minimal 8 karakter, termasuk huruf kapital,
                                        huruf kecil, angka dan simbol(@$!%*?&)</span>
                                    </div>
                                    <div class="formLabel">
                                        <label for="type">Tipe Montir</label>
                                        <select name="type" id="type" required>
                                            <option value="ketua">Ketua Montir</option>
                                            <option value="anggota">Anggota Montir</option>
                                        </select>
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

                <!-- Ketua Montir Table -->
                <div class="montir-view">
                    <div class="cardHeader">
                        <h2>Ketua Montir</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM ketua_montir ORDER BY email_ketua_montir";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['email_ketua_montir']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_ketua_montir']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="openEditModal('ketua', '<?php echo htmlspecialchars($row['email_ketua_montir']); ?>', '<?php echo htmlspecialchars($row['nama_ketua_montir']); ?>', '<?php echo htmlspecialchars($row['no_hp']); ?>')" class="btn-edit">Edit</a>
                                        <a href="?delete=<?php echo urlencode($row['email_ketua_montir']); ?>&type=ketua" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Anggota Montir Table -->
                <div class="montir-view">
                    <div class="cardHeader">
                        <h2>Anggota Montir</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM anggota_montir ORDER BY email_anggota_montir";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['email_anggota_montir']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_anggota_montir']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="openEditModal('anggota', '<?php echo htmlspecialchars($row['email_anggota_montir']); ?>', '<?php echo htmlspecialchars($row['nama_anggota_montir']); ?>', '<?php echo htmlspecialchars($row['no_hp']); ?>')" class="btn-edit">Edit</a>
                                        <a href="?delete=<?php echo urlencode($row['email_anggota_montir']); ?>&type=anggota" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
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
    <script src="montir.js"></script>
</body>

</html>

<?php $conn->close(); ?>