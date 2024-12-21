<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== 'admin') {
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
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'komponen') {
        $id_data_komponen = isset($_POST['id_data_komponen']) ? trim($_POST['id_data_komponen']) : '';
        $nama = isset($_POST['nama_komponen']) ? trim($_POST['nama_komponen']) : '';
        $edit_id = isset($_POST['edit_id_data_komponen']) ? trim($_POST['edit_id_data_komponen']) : '';

        // Validate ID format for Komponen
        if (!preg_match('/^DK\d{2}[A-Z]{3}$/', $id_data_komponen)) {
            $errors[] = "Format ID Komponen tidak valid. Gunakan format: DK + 2 digit + 3 huruf besar.";
        }

        if (empty($nama) || empty($id_data_komponen)) {
            $errors[] = "Semua field harus diisi.";
        } else {
            try {
                if (!empty($edit_id)) {
                    $query = "UPDATE data_komponen SET nama_komponen = ? WHERE id_data_komponen = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ss", $nama, $edit_id);
                } else {
                    $query = "INSERT INTO data_komponen (id_data_komponen, nama_komponen) VALUES (?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ss", $id_data_komponen, $nama);
                }

                if ($stmt->execute()) {
                    header("Location: servis.php?success=" . (!empty($edit_id) ? 'edit_komponen' : 'save_komponen'));
                    exit();
                } else {
                    $errors[] = "Gagal menyimpan data: " . $stmt->error;
                }
                $stmt->close();
            } catch (Exception $e) {
                $errors[] = "Error: " . $e->getMessage();
            }
        }
    } elseif ($action === 'data_servis') {
        $id_data_servis = isset($_POST['id_data_servis']) ? trim($_POST['id_data_servis']) : '';
        $nama_servis = isset($_POST['nama_servis']) ? trim($_POST['nama_servis']) : '';
        $harga = isset($_POST['harga']) ? trim($_POST['harga']) : '';
        $komponen = isset($_POST['komponen']) ? trim($_POST['komponen']) : '';
        $edit_id = isset($_POST['edit_id_data_servis']) ? trim($_POST['edit_id_data_servis']) : '';

        // Validate ID format for Servis
        if (!preg_match('/^DS\d{2}[A-Z]{3}$/', $id_data_servis)) {
            $errors[] = "Format ID Servis tidak valid. Gunakan format: DS + 2 digit + 3 huruf besar.";
        }

        // Validate harga is a valid number
        if (!is_numeric($harga)) {
            $errors[] = "Harga harus berupa angka.";
        }

        if (empty($nama_servis) || empty($harga) || empty($komponen) || empty($id_data_servis)) {
            $errors[] = "Semua field harus diisi.";
        } else {
            try {
                if (!empty($edit_id)) {
                    $query = "UPDATE data_servis SET nama_servis = ?, harga_servis = ?, id_data_komponen = ? WHERE id_data_servis = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("siss", $nama_servis, $harga, $komponen, $edit_id);
                } else {
                    $query = "INSERT INTO data_servis (id_data_servis, nama_servis, harga_servis, id_data_komponen) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssis", $id_data_servis, $nama_servis, $harga, $komponen);
                }

                if ($stmt->execute()) {
                    header("Location: servis.php?success=" . (!empty($edit_id) ? 'edit_servis' : 'save_servis'));
                    exit();
                } else {
                    $errors[] = "Gagal menyimpan data: " . $stmt->error;
                }
                $stmt->close();
            } catch (Exception $e) {
                $errors[] = "Error: " . $e->getMessage();
            }
        }
    }
}

// Delete Komponen or Servis
if (isset($_GET['delete_komponen'])) {
    $id_to_delete = $_GET['delete_komponen'];

    // First check if komponen is used in data_servis
    $check_query = "SELECT COUNT(*) as count FROM data_servis WHERE id_data_komponen = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $id_to_delete);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        header("Location: servis.php?error=delete_komponen");
        exit();
    }

    // If not used, proceed with deletion
    $delete_query = "DELETE FROM data_komponen WHERE id_data_komponen = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $id_to_delete);

    if ($delete_stmt->execute()) {
        header("Location: servis.php?success=delete_komponen");
        exit();
    } else {
        header("Location: servis.php?error=delete_komponen");
        exit();
    }
    $delete_stmt->close();
}


if (isset($_GET['delete_servis'])) {
    $id_to_delete = $_GET['delete_servis'];

    // Check if servis is used in booking
    $check_query = "SELECT COUNT(*) as count FROM detail_servis WHERE FIND_IN_SET(?, id_data_servis) > 0";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $id_to_delete);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        header("Location: servis.php?error=delete_servis");
        exit();
    }

    // If not used, proceed with deletion
    $delete_query = "DELETE FROM data_servis WHERE id_data_servis = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("s", $id_to_delete);

    if ($delete_stmt->execute()) {
        header("Location: servis.php?success=delete_servis");
        exit();
    } else {
        header("Location: servis.php?error=delete_servis");
        exit();
    }
    $delete_stmt->close();
}



// Fetch Components for Dropdown
$komponenQuery = "SELECT id_data_komponen, nama_komponen FROM data_komponen";
$komponenResult = $conn->query($komponenQuery);

// Fetch Services
$servicesQuery = "SELECT ds.id_data_servis, ds.nama_servis, ds.harga_servis, 
                  dk.id_data_komponen, dk.nama_komponen 
                  FROM data_servis ds 
                  JOIN data_komponen dk ON ds.id_data_komponen = dk.id_data_komponen";

$servicesResult = $conn->query($servicesQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href=" ../../assets/img/logo.png" type="image/png">
    <title>Mechaban - Service Management</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="servis.css">
</head>

<body>
    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
            <!-- header -->
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <!-- ----search---- -->

                <!-- ----user img---- -->
                <div class="user">
                    <div class="user-img-container">
                        <?php
                        // Determine the photo path
                        $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                            ? '../../uploads/' . htmlspecialchars($_SESSION["photo"])
                            : '../../assets/img/default-profile.png';
                        ?>
                        <img src="<?php echo $userPhoto; ?>" alt="User Profile Picture" class="user-img"
                            onclick="showPhotoModal('<?php echo $userPhoto; ?>')">

                        <div class="user-status <?php echo ($_SESSION["is_online"]) ? 'online' : 'offline'; ?>"></div>
                    </div>

                    <div class="user-info">
                        <div class="username">
                            <span class="name"><?php echo $_SESSION["name"]; ?></span>
                            <span class="role"><?php echo $_SESSION["role"]; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Photo Modal (can be added to the bottom of your page) -->
                <div id="photoModal" class="modal">
                    <span class="photo-modal-close" onclick="closePhotoModal()">&times;</span>
                    <div class="photo-modal-content">
                        <img id="modalPhoto" src="" alt="Enlarged photo">
                    </div>
                </div>
            </div>

            <div class="view">
                <div class="actions">
                    <button class="tambah-komponen" id="komponenBtn">Tambah Komponen</button>
                    <button class="tambah-servis" id="servisBtn">Tambah Servis</button>
                </div>

                <!-- Komponen Modal -->
                <div id="komponenModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="komponen-tambah">
                            <h2 id="komponenModalTitle">Form Tambah Komponen</h2>

                            <div class="form">
                                <?php if ($errors): ?>
                                <div class="errors">
                                    <?php foreach ($errors as $error): ?>
                                    <p><?php echo $error; ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <form id="formKomponen" action="" method="post">
                                    <input type="hidden" name="action" value="komponen">
                                    <div class="formLabel">
                                        <label for="nama">ID Komponen</label>
                                        <input type="text" name="id_data_komponen" id="id_data_komponen"
                                            placeholder="ID Komponen" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="nama">Nama Komponen</label>
                                        <input type="text" name="nama_komponen" id="nama_komponen"
                                            placeholder="Nama Komponen" required>
                                    </div>
                                    <input type="hidden" name="edit_id_data_komponen" id="edit_id_data_komponen">
                                    <div class="input">
                                        <input type="submit" name="simpan" value="Simpan Data" class="btn-simpan">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servis Modal -->
                <div id="servisModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="servis-tambah">
                            <h2 id="servisModalTitle">Form Tambah Servis</h2>

                            <div class="form">
                                <?php if ($errors): ?>
                                <div class="errors">
                                    <?php foreach ($errors as $error): ?>
                                    <p><?php echo $error; ?></p>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <form id="formServis" action="" method="post">
                                    <input type="hidden" name="action" value="data_servis">
                                    <div class="formLabel">
                                        <label for="id_data_servis">ID Servis</label>
                                        <input type="text" name="id_data_servis" id="id_data_servis"
                                            placeholder="ID Servis" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="nama_servis">Nama Servis</label>
                                        <input type="text" name="nama_servis" id="nama_servis" placeholder="Nama Servis"
                                            required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="harga">Harga</label>
                                        <input type="number" name="harga" id="harga" placeholder="Harga" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="komponen">Komponen</label>
                                        <select name="komponen" id="komponen" required>
                                            <option value="">Pilih Komponen</option>
                                            <?php while ($komponen = $komponenResult->fetch_assoc()): ?>
                                            <option value="<?php echo $komponen['id_data_komponen']; ?>">
                                                <?php echo htmlspecialchars($komponen['nama_komponen']); ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="edit_id_data_servis" id="edit_id_data_servis">
                                    <div class="input">
                                        <input type="submit" name="simpan" value="Simpan Data" class="btn-simpan">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert-container" id="alertContainer"></div>

                <div class="delete-modal">
                    <div class="delete-modal-content">
                        <div class="delete-modal-icon">
                            <ion-icon name="warning-outline"></ion-icon>
                        </div>
                        <h3 class="delete-modal-title">Konfirmasi Hapus</h3>
                        <p class="delete-modal-message">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak
                            dapat dibatalkan.</p>
                        <div class="delete-modal-buttons">
                            <a href="" class="delete-modal-btn confirm-delete">Ya, Hapus</a>
                            <button class="delete-modal-btn cancel-delete">Batal</button>
                        </div>
                    </div>
                </div>

                <div class="komponen-view">
                    <div class="cardHeader">
                        <h2>Daftar Komponen</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Komponen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reset the pointer for komponenResult
                            $komponenResult->data_seek(0);
                            while ($row = $komponenResult->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><?php echo $row['id_data_komponen']; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_komponen']); ?></td>
                                <td class="table-action-buttons">
                                    <a href="javascript:void(0);" onclick="window.openEditKomponenModal(
                                        '<?php echo $row['id_data_komponen']; ?>', 
                                        '<?php echo addslashes($row['nama_komponen']); ?>')"
                                        class="btn btn-edit">Edit</a>
                                    <a href="?delete_komponen=<?php echo $row['id_data_komponen']; ?>"
                                        class="btn btn-hapus">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="servis-view">
                    <div class="cardHeader">
                        <h2>Daftar Servis</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Servis</th>
                                <th>Harga</th>
                                <th>Komponen</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $servicesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id_data_servis']; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_servis']); ?></td>
                                <td>Rp. <?php echo number_format($row['harga_servis'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_komponen']); ?></td>
                                <td class="table-action-buttons">
                                    <a href="javascript:void(0);" onclick="window.openEditServisModal(
                                        '<?php echo $row['id_data_servis']; ?>', 
                                        '<?php echo addslashes($row['nama_servis']); ?>', 
                                        <?php echo $row['harga_servis']; ?>, 
                                        '<?php echo $row['id_data_komponen']; ?>')" class="btn btn-edit">Edit</a>
                                    <a href="?delete_servis=<?php echo $row['id_data_servis']; ?>"
                                        class="btn btn-hapus">Hapus</a>

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
    <script src="servis.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>

<?php
$conn->close();
?>