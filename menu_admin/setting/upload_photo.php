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

// Define upload settings
define('UPLOAD_DIR', '../../uploads/customers/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file, $email)
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

    // Create filename from email
    $email_parts = explode('@', $email); // Split email at @
    $sanitized_email = str_replace('.', '_', $email_parts[0]); // Replace dots with underscore in first part
    $filename = $sanitized_email . '.jpg';
    $destination = UPLOAD_DIR . $filename;


    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Gagal mengupload file.");
    }

    return $filename;
}


// Get current user's email
$email_customer = $_SESSION["email"];

// Process photo upload
if (isset($_POST['upload']) && isset($_FILES['photo'])) {
    try {
        // Check if a file was uploaded
        if ($_FILES['photo']['size'] > 0) {
            // Handle file upload with email
            $photo_filename = handleFileUpload($_FILES['photo'], $email_customer);

            // Rest of the code remains the same...

            // Update photo in database
            $update_query = "UPDATE account SET photo = ? WHERE email = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ss", $photo_filename, $email_customer);

            // Delete old photo if exists
            $old_photo_query = "SELECT photo FROM account WHERE email = ?";
            $old_photo_stmt = $conn->prepare($old_photo_query);
            $old_photo_stmt->bind_param("s", $email_customer);
            $old_photo_stmt->execute();
            $old_photo_stmt->bind_result($old_photo);
            $old_photo_stmt->fetch();
            $old_photo_stmt->close();

            // Execute update
            if ($stmt->execute()) {
                // Delete old photo file if exists
                if ($old_photo && file_exists(UPLOAD_DIR . $old_photo)) {
                    @unlink(UPLOAD_DIR . $old_photo);
                }

                // Update session with new photo filename
                $_SESSION["photo"] = 'customers/' . $photo_filename;

                // Redirect with success message
                header("Location: setting.php?success=upload");
                exit();
            } else {
                throw new Exception("Gagal memperbarui foto profil.");
            }
        } else {
            throw new Exception("Tidak ada file yang dipilih.");
        }
    } catch (Exception $e) {
        // Redirect with error message
        header("Location: setting.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect if accessed incorrectly
    header("Location: setting.php");
    exit();
}

$conn->close();
?>