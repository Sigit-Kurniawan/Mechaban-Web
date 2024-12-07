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

// Define photo upload settings
define('UPLOAD_DIR', '../../uploads/customers/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error: " . $file['error']);
    }

    // Validate file size
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception("File terlalu besar. Maksimum 2MB.");
    }

    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_TYPES)) {
        throw new Exception("Tipe file tidak diizinkan. Hanya jpg, jpeg, dan png yang diperbolehkan.");
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Gagal mengupload file.");
    }

    return $filename;
}

// Process form submission for adding/editing customer
if (isset($_POST['simpan'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $no_hp = $_POST['no_hp'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $edit_email = $_POST['edit_email'];

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
            $photo_filename = null;

            // Handle photo upload if file is selected
            if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                try {
                    $photo_filename = handleFileUpload($_FILES['photo']);
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                    // Continue with form processing even if photo upload fails
                }
            }

            if (empty($errors)) {
                if (!empty($edit_email)) {
                    // Update existing customer
                    $query = "UPDATE account SET email = ?, name = ?, no_hp = ?";
                    $params = [$email, $name, $no_hp];

                    if (!empty($password)) {
                        $query .= ", password = ?";
                        $params[] = password_hash($password, PASSWORD_DEFAULT);
                    }

                    if ($photo_filename) {
                        $query .= ", photo = ?";
                        $params[] = $photo_filename;

                        // Delete old photo if exists
                        $old_photo_query = "SELECT photo FROM account WHERE email = ?";
                        $stmt = $conn->prepare($old_photo_query);
                        $stmt->bind_param("s", $edit_email);
                        $stmt->execute();
                        $stmt->bind_result($old_photo);
                        if ($stmt->fetch() && $old_photo) {
                            @unlink(UPLOAD_DIR . $old_photo);
                        }
                        $stmt->close();
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

                        $query = "INSERT INTO account (email, name, no_hp, password, role, photo) VALUES (?, ?, ?, ?, 'customer', ?)";

                        $stmt = $conn->prepare($query);
                        if (!$stmt) {
                            throw new Exception("Prepare failed: " . $conn->error);
                        }

                        $stmt->bind_param("sssss", $email, $name, $no_hp, $hashed_password, $photo_filename);

                        if (!$stmt->execute()) {
                            throw new Exception("Execute failed: " . $stmt->error);
                        }

                        header("Location: customers.php?success=save");
                        exit();
                    }
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $errors[] = "Terjadi kesalahan: " . $e->getMessage();
            // Clean up uploaded file if database operation fails
            if ($photo_filename && file_exists(UPLOAD_DIR . $photo_filename)) {
                @unlink(UPLOAD_DIR . $photo_filename);
            }
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $email_to_delete = $_GET['delete'];

    // Get photo filename before deleting record
    $photo_query = "SELECT photo FROM account WHERE email = ? AND role = 'customer'";
    $photo_stmt = $conn->prepare($photo_query);
    $photo_stmt->bind_param("s", $email_to_delete);
    $photo_stmt->execute();
    $photo_stmt->bind_result($photo_filename);
    $photo_stmt->fetch();
    $photo_stmt->close();

    $delete_query = "DELETE FROM account WHERE email = ? AND role = 'customer'";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $email_to_delete);

    if ($delete_stmt->execute()) {
        // Delete photo file if exists
        if ($photo_filename && file_exists(UPLOAD_DIR . $photo_filename)) {
            @unlink(UPLOAD_DIR . $photo_filename);
        }
        header("Location: customers.php?success=delete");
        exit();
    } else {
        $errors[] = "Gagal menghapus data: " . $delete_stmt->error;
    }
    $delete_stmt->close();
}

// Modify the table to display photo
$table_photo_column = '
<th>Photo</th>
';

$table_row_photo = '
<td>
    <?php if (!empty($row["photo"])): ?>
        <img src="' . UPLOAD_DIR . '<?php echo htmlspecialchars($row["photo"]); ?>" 
             alt="Profile photo" 
             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
    <?php else: ?>
        <img src="../../assets/img/default-profile.png" 
             alt="Default profile" 
             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
    <?php endif; ?>
</td>
';

// Update form enctype for file upload
$form_enctype = 'enctype="multipart/form-data"';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/Mechaban-Web/assets/img/logo.png" type="image/png">
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="cus.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <?php include '../header.php'; ?>

            <div class="view">
                <!-- <button id="myBtn" class="tambah-cus">Tambah Customers</button> -->

                <!-- Modal Form -->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="cus-tambah">
                            <h2 id="modalTitle">Form Tambah Customers</h2>
                            <?php if (!empty($errors)): ?>
                                <div class="errors">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?php echo htmlspecialchars($error); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form">
                                <form id="formCustomer" action="" method="post" enctype="multipart/form-data">
                                    <div class="photo-upload-container">
                                        <img id="photoPreview" src="../../assets/img/default-profile.png"
                                            alt="Profile preview" class="photo-preview">
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
                    <div id="photoModal" class="photo-modal">
                        <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                        <div class="photo-modal-content">
                            <img id="modalPhoto" src="" alt="Enlarged photo">
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Photo</th>
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