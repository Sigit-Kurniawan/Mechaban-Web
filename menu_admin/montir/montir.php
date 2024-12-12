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

// Process form if save button is pressed
$errors = [];
if (isset($_POST['simpan'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];
    $edit_email = $_POST['edit_email']; // Hidden field for editing

    // Input validation
    if (empty($email) || empty($name) || empty($no_hp) || empty($password)) {
        $errors[] = "Semua kolom wajib diisi.";
    } else {
        // Password validation
        if (strlen($password) < 8 || 
            !preg_match("/[A-Z]/", $password) || 
            !preg_match("/[0-9]/", $password) || 
            !preg_match("/[@$!%*?&]/", $password)) {
            $errors[] = "Password harus minimal 8 karakter, mengandung huruf besar, angka, dan simbol (@$!%*?&).";
        } else {
            if (!empty($edit_email)) {
                // Update existing montir
                $query = "UPDATE account SET email = ?, name = ?, no_hp = ?, password = ? WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssss", $email, $name, $no_hp, $password, $edit_email);

                if ($stmt->execute()) {
                    header("Location: montir.php?success=edit");
                    exit();
                } else {
                    $errors[] = "Gagal mengedit data: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Check if email already exists
                $queryCheck = "SELECT COUNT(*) AS count FROM account WHERE email = ?";
                $stmtCheck = $conn->prepare($queryCheck);
                $stmtCheck->bind_param("s", $email);
                $stmtCheck->execute();
                $stmtCheck->bind_result($count);
                $stmtCheck->fetch();
                $stmtCheck->close();

                if ($count > 0) {
                    echo "<script>alert('Email sudah terdaftar');</script>";
                } else {
                    // Insert new montir
                    $role = 'montir'; // Set role as montir
                    $query = "INSERT INTO account (email, name, no_hp, password, role) 
                             VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssss", $email, $name, $no_hp, $password, $role);

                    if ($stmt->execute()) {
                        header("Location: montir.php?success=save");
                        exit();
                    } else {
                        $errors[] = "Gagal menyimpan data: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        }
    }
}

// Delete montir
if (isset($_GET['delete_email'])) {
    $email_to_delete = $_GET['delete_email'];

    $delete_query = "DELETE FROM account WHERE email=? AND role='montir'";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $email_to_delete);

    if ($delete_stmt->execute()) {
        header("Location: montir.php?success=delete");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data: " . $delete_stmt->error . "');</script>";
    }
    $delete_stmt->close();
}

// Query to display montir data
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT email, name, no_hp, password FROM account WHERE role = 'montir'";
if (!empty($search)) {
    $query .= " AND (email LIKE ? OR name LIKE ?)";
}

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("ss", $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="montir.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <button class="tambah-montir" id="myBtn">Tambah Montir</button>

                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="montir-tambah">
                            <h2 id="modalTitle">Form Tambah Montir</h2>

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

                <div class="montir-view">
                    <div class="cardHeader">
                        <h2>Daftar Montir</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Password</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset the result to use the search query
                            if (!empty($search)) {
                                $search_param = "%$search%";
                                $query = "SELECT * FROM account WHERE role = 'customer' AND (name LIKE ? OR email LIKE ? OR no_hp LIKE ?) ORDER BY name";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("sss", $search_param, $search_param, $search_param);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                // If no search, fetch all customers
                                $result = $conn->query("SELECT * FROM account WHERE role = 'customer' ORDER BY name");
                            }

                            $no = 1;
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td>
                                        <?php if (!empty($row['photo'])): ?>
                                            <img src="<?php echo UPLOAD_DIR . htmlspecialchars($row['photo']); ?>"
                                                alt="Profile photo"
                                                class="customer-photo"
                                                onclick="showPhotoModal('<?php echo UPLOAD_DIR . htmlspecialchars($row['photo']); ?>')">
                                        <?php else: ?>
                                            <img src="../../assets/img/default-profile.png"
                                                alt="Default profile"
                                                class="customer-photo">
                                        <?php endif; ?>
                                    </td>
                                    <td>
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
    <script src="montir.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>