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

// Mendapatkan email customer dari sesi
$email_customer = $_SESSION["email"];

function formatNopol($nopol)
{
    if (preg_match('/^([A-Za-z]{1,2})(\d{3,4})([A-Za-z]{1,2})$/', $nopol, $matches)) {
        return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
    }
    return $nopol; // Jika tidak cocok dengan format, tampilkan apa adanya
}



// Memproses form jika tombol simpan ditekan
$errors = [];
if (isset($_POST['simpan'])) {
    $nopol = str_replace(' ', '', $_POST['nopol']); // Menghapus semua spasi sebelum menyimpan
    $merk = $_POST['merk'];
    $type = $_POST['type'];
    $transmition = $_POST['transmition'];
    $year = $_POST['year'];
    $edit_nopol = $_POST['edit_nopol']; // Ambil nilai dari hidden field

    // Validasi input
    if (empty($nopol) || empty($merk) || empty($type) || empty($transmition) || empty($year)) {
        $errors[] = "Semua kolom wajib diisi.";
    } else {
        if (!empty($edit_nopol)) {
            // Update data mobil
            $query = "UPDATE car SET nopol = ?, merk = ?, type = ?, transmition = ?, year = ? WHERE nopol = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssss", $nopol, $merk, $type, $transmition, $year, $edit_nopol);

            if ($stmt->execute()) {
                header("Location: mobil.php?success=edit");

                exit();
            } else {
                $errors[] = "Gagal mengedit data: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Memeriksa apakah nopol sudah ada di database
            $queryCheck = "SELECT COUNT(*) AS count FROM car WHERE nopol = ?";
            $stmtCheck = $conn->prepare($queryCheck);
            $stmtCheck->bind_param("s", $nopol);
            $stmtCheck->execute();
            $stmtCheck->bind_result($count);
            $stmtCheck->fetch();
            $stmtCheck->close();

            if ($count > 0) {
                echo "<script>alert('Nopol sudah terdaftar');</script>";
            } else {
                // Query untuk memasukkan data ke database
                $query = "INSERT INTO car (nopol, merk, type, transmition, year, email_customer) 
                      VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssss", $nopol, $merk, $type, $transmition, $year, $email_customer);

                if ($stmt->execute()) {
                    header("Location: mobil.php?success=save");
                    exit();
                } else {
                    $errors[] = "Gagal menyimpan data: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

//hapus data mobil
if (isset($_GET['delete_nopol'])) {
    $nopol_to_delete = $_GET['delete_nopol'];

    $delete_query = "DELETE FROM car WHERE nopol=? AND email_customer=?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ss", $nopol_to_delete, $email_customer);

    if ($delete_stmt->execute()) {
        header("Location: mobil.php?success=delete");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data: " . $delete_stmt->error . "');</script>";
    }
    $delete_stmt->close();
}

// Query untuk menampilkan data mobil berdasarkan email customer yang login
$query = "SELECT nopol, merk, type, transmition, year
FROM car
WHERE email_customer = ? 
ORDER BY created DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email_customer);
$stmt->execute();
$result = $stmt->get_result();


// Query untuk pencarian 

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query dasar
$query = "SELECT * FROM car WHERE email_customer = ?";

// Tambahkan kondisi pencarian jika parameter 'search' ada
if (!empty($search)) {
    $query .= " AND (nopol LIKE ? OR merk LIKE ?)";
}

$stmt = $conn->prepare($query);

if (!empty($search)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("sss", $email_customer, $search_param, $search_param);
} else {
    $stmt->bind_param("s", $email_customer);
}

$stmt->execute();
$result = $stmt->get_result();
?>





<!-- Form HTML dan Tampilan Data Mobil -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="mobil.css">
</head>

<body>

    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="notificationMessage"></p>
        </div>
    </div>


    <div class="container">
        <?php include '../sidebar.php'; ?>
        <div class="main">
        <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>


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
                            <span class="name">
                                <?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Guest'; ?>
                            </span>
                            <span class="role">
                                <?php echo isset($_SESSION["role"]) ? htmlspecialchars($_SESSION["role"]) : 'Visitor'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="view">
                <div class="head-mobil">
                    <button class="tambah-mobil" id="myBtn">Tambah Mobil</button>

                    <div class="search">
                        <form action="mobil.php" method="GET">
                            <label>
                                <input type="text" id="searchInput" name="search" placeholder="Cari Nopol atau Merk..."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </form>
                    </div>

                </div>


                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <div class="mobil-tambah">
                            <h2 id="modalTitle">Form Tambah Mobil</h2>

                            <div class="form">
                                <form id="formMobil" action="" method="post">
                                    <div class="formLabel">
                                        <label for="nopol">Nopol</label>
                                        <input type="text" name="nopol" id="nopol"
                                            placeholder="Nopol. Contoh : AB 1234 C" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="merk">Merk</label>
                                        <input type="text" name="merk" id="merk" placeholder="Merk" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="type">Tipe</label>
                                        <input type="text" name="type" id="type" placeholder="Tipe" required>
                                    </div>
                                    <div class="formLabel">
                                        <label for="transmition">Transmisi</label>
                                        <select name="transmition" id="transmition" required>
                                            <option value="manual">Manual</option>
                                            <option value="auto">Auto</option>
                                        </select>
                                    </div>
                                    <div class="formLabel">
                                        <label for="year">Tahun</label>
                                        <input type="number" name="year" id="year" placeholder="Tahun" required>
                                    </div>
                                    <input type="hidden" name="edit_nopol" id="edit_nopol">
                                    <!-- Hidden field untuk nopol yang di-edit -->
                                    <div class="input">
                                        <input type="submit" name="simpan" value="Simpan Data" class="btn">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mobil-view">
                    <div class="cardHeader">
                        <h2>Data Mobil</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nopol</th>
                                <th>Merk</th>
                                <th>Tipe</th>
                                <th>Transmisi</th>
                                <th>Tahun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo formatNopol($row['nopol']); ?> <!-- Terapkan formatNopol --></td>
                                    <td><?php echo htmlspecialchars($row['merk']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['transmition']); ?></td>
                                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                                    <td class="table-action-buttons">
                                        <a href="javascript:void(0);"
                                            onclick="openEditModal('<?php echo htmlspecialchars($row['nopol']); ?>', '<?php echo htmlspecialchars($row['merk']); ?>', '<?php echo htmlspecialchars($row['type']); ?>', '<?php echo htmlspecialchars($row['transmition']); ?>', '<?php echo htmlspecialchars($row['year']); ?>')"
                                            class="btn btn-edit">Edit</a>

                                        <a href="?delete_nopol=<?php echo htmlspecialchars($row['nopol']); ?>"
                                            class="btn btn-hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
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
    <script src="mobil.js"></script>
    <script src="\Mechaban-Web\assets\js\main.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>