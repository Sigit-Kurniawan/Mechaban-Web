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
// Process form if save button is pressed
if (isset($_POST['simpan'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $name = trim(strip_tags($_POST['name']));
    $no_hp = preg_replace('/[^0-9]/', '', $_POST['no_hp']);
    $password = $_POST['password'];
    $edit_email = $_POST['edit_email']; // Hidden field for editing

    // Input validation
    if (empty($email) || empty($name) || empty($no_hp)) {
        $errors[] = "Email, nama, dan nomor HP wajib diisi.";
    } else {
        // Password validation - only check if password is provided or it's a new entry
        $password_valid = true;
        if (!empty($password)) {
            // Validate password if it's provided
            $password_valid = (
                strlen($password) >= 8 &&
                preg_match("/[A-Z]/", $password) &&
                preg_match("/[0-9]/", $password) &&
                preg_match("/[@$!%*?&]/", $password)
            );
            if (!$password_valid) {
                $errors[] = "Password harus minimal 8 karakter, mengandung huruf besar, angka, dan simbol (@$!%*?&).";
            }
        } else if (empty($edit_email)) {
            // Require password only for new entries
            $errors[] = "Password wajib diisi untuk akun baru.";
            $password_valid = false;
        }

        if (!empty($edit_email)) {
            // Update existing montir
            if (empty($password)) {
                // Update without changing password
                $query = "UPDATE account SET email = ?, name = ?, no_hp = ?";
                $params = [$email, $name, $no_hp];
                $types = "sss";
            } else {
                // Update including new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE account SET email = ?, name = ?, no_hp = ?, password = ?";
                $params = [$email, $name, $no_hp, $hashed_password];
                $types = "ssss";
            }
            
                // Add WHERE clause
                $query .= " WHERE email = ?";
                $params[] = $edit_email;
                $types .= "s";
                
                $stmt = $conn->prepare($query);
                $stmt->bind_param($types, ...$params);
                
                if ($stmt->execute()) {
                    header("Location: montir.php?success=edit");
                    exit();
                } else {
                    $errors[] = "Gagal mengedit data: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Insert new montir
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO account (email, name, no_hp, password, role) 
                         VALUES (?, ?, ?, ?, 'montir')";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $email, $name, $no_hp, $hashed_password);

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


function handlePhotoUpload($file, $email) {
    define('UPLOAD_DIR', '../../uploads/customers/');
    
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }
    
    // Create filename from email
    $email_parts = explode('@', $email); // Split email at @
    $sanitized_email = str_replace('.', '_', $email_parts[0]); // Replace dots with underscore in first part
    $filename = $sanitized_email . '.jpg';
    $destination = UPLOAD_DIR . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    } else {
        throw new Exception("Failed to upload file");
    }

    return $filename;
}


function validatePhoto($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!in_array($file['type'], $allowed_types)) {
        return false;
    }
    if ($file['size'] > $max_size) {
        return false;
    }
    return true;
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
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT email, name, no_hp, password FROM account WHERE role = 'montir'";
if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ? OR no_hp LIKE ?)";
}

$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$query .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_param = "%$search%";
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $items_per_page, $offset);
} else {
    $stmt->bind_param("ii", $items_per_page, $offset);
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
            
            <div class="alert-container" id="alertContainer"></div>

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


                                <form id="formMontir" action="" method="post" enctype="multipart/form-data">
                                    <div class="photo-upload-container">
                                        <img id="photoPreview" src="
                                        <?php 
                                            // Show existing photo or default if editing
                                            if (!empty($edit_email)) {
                                                $edit_query = "SELECT photo FROM account WHERE email = ?";
                                                $edit_stmt = $conn->prepare($edit_query);
                                                $edit_stmt->bind_param("s", $edit_email);
                                                $edit_stmt->execute();
                                                $edit_result = $edit_stmt->get_result();
                                                $edit_row = $edit_result->fetch_assoc();
                                                echo !empty($edit_row['photo']) 
                                                    ? '../../uploads/customers/' . htmlspecialchars($edit_row['photo']) 
                                                    : '../../assets/img/default-profile.png';
                                            } else {
                                                echo '../../assets/img/default-profile.png';
                                            }
                                        ?>" alt="Profile preview" class="photo-preview">
                                        <label for="photo" class="photo-upload-label">
                                            <ion-icon name="camera"></ion-icon>
                                        </label>
                                        <input type="file" id="photo" name="photo" class="photo-upload-input"
                                            accept="image/jpeg,image/png,image/jpg">
                                    </div>

                                    <div class="file-hints">
                                        Format: JPG, JPEG, PNG (Max. 2MB)
                                    </div>

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
                                        <label for="password">
                                            Password <?php echo !empty($_POST['edit_email']) ? '(Biarkan kosong jika tidak ingin mengubah)' : ''; ?>
                                        </label>
                                        <div class="password-input">
                                            <input type="password" 
                                                name="password" 
                                                id="password" 
                                                placeholder="<?php echo !empty($_POST['edit_email']) ? 'Kosongkan jika tidak ingin mengubah' : ''; ?>"
                                                <?php echo empty($_POST['edit_email']) ? 'required' : ''; ?>>
                                            <span class="toggle-password" onclick="togglePassword()">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </span>
                                        </div>
                                        <div class="password-requirements">
                                            Min. 8 karakter, huruf besar, angka, dan simbol (@$!%*?&)
                                        </div>
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

                <!-- Delete Confirmation Modal -->
                <div id="deleteModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Konfirmasi Hapus</h2>
                        <p>Apakah kamu ingin menghapus data Montir <span id="montirName"></span>?</p>
                        <div class="modal-actions">
                            <button id="confirmDelete" class="btn-hapus">Hapus</button>
                            <button onclick="document.getElementById('deleteModal').style.display='none'"
                                class="btn-cancel">Batal</button>
                        </div>
                    </div>
                </div>

                <div class="montir-view">
                    <div class="view">
                    <div class="cardHeader">
                        <h2>Daftar Montir</h2>
                    </div>
                    <?php if (isset($_GET['success'])): ?>
                        <div id="success-alert" class="success-alert">
                            <?php
                            switch ($_GET['success']) {
                                case 'save':
                                    echo "Data montir berhasil ditambahkan.";
                                    break;
                                case 'edit':
                                    echo "Data montir berhasil diperbarui.";
                                    break;
                                case 'delete':
                                    echo "Data montir berhasil dihapus.";
                                    break;
                                default:
                                    echo "Operasi berhasil.";
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div id="error-alert" class="error-alert">
                            <?php foreach($errors as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Initialize the counter
                            $no = 1;

                            // Get search parameter
                            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

                            // Prepare the query with search functionality
                            $query = "SELECT * FROM account WHERE role = 'montir'";
                            if (!empty($search)) {
                                $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR no_hp LIKE '%$search%')";
                            }
                            $query .= " ORDER BY name";

                            // Execute the query
                            $result = mysqli_query($conn, $query);

                            if ($result) {
                                while ($r2 = mysqli_fetch_array($result)) {
                                    $email = htmlspecialchars($r2['email']);
                                    $nama_montir = htmlspecialchars($r2['name']);
                                    $no_hp = htmlspecialchars($r2['no_hp']);
                                    ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $nama_montir; ?></td>
                                <td><?php echo $no_hp; ?></td>
                                <td>
                                    <?php 
                                    $photo = !empty($r2['photo']) ? htmlspecialchars($r2['photo']) : null;
                                    ?>
                                    <img src="<?php echo $photo ? '../../uploads/customers/' . $photo : '../../assets/img/default-profile.png'; ?>"
                                        alt="Profile photo" class="customer-photo"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                </td>

                                <td>
                                    <a href="#"
                                        onclick="editMontir('<?php echo $email; ?>', '<?php echo $nama_montir; ?>', '<?php echo $no_hp; ?>', '<?php echo $password; ?>')"
                                        class="btn-edit">Edit</a>
                                    <a href="#" 
                                        onclick="confirmDelete('<?php echo $email; ?>', '<?php echo $nama_montir; ?>')" 
                                        class="btn-hapus">Hapus</a>
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