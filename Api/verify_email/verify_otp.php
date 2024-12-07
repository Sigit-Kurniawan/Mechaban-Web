<?php
session_start();
include("../koneksi.php");

// Check if registration data exists in session
if (!isset($_SESSION['register_data'])) {
    header("Location: ../../register.php");
    exit;
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
    <title>Verify OTP - Mechaban</title>
</head>
<body>
    <div class="card-container">
        <div class="card">
            <div class="card-head">
                <div class="head-bg">
                    <img src="../../assets/img/logo2.png" alt="Logo" class="head-logo">
                    <h2 class="head-title">Verify Email</h2>
                </div>
            </div>

            <div class="card-content">
                <?php if ($error): ?>
                    <div class="alert">
                        <?php
                        if ($error === 'invalid_otp') {
                            echo "Kode OTP tidak valid atau sudah kadaluarsa.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <form action="verify_otp_process.php" method="POST">
                    <div class="form-group">
                        <label for="otp">Masukkan Kode OTP</label>
                        <input type="text" id="otp" name="otp" placeholder="Masukkan 4 digit kode OTP" maxlength="4" required>
                        <span class="teks-span">Kode OTP telah dikirim ke email <?php echo $_SESSION['register_data']['email']; ?></span>
                    </div>

                    <button type="submit">Verifikasi</button>

                    <div class="teks">
                        <p>Tidak menerima kode? <a href="resend_otp.php">Kirim Ulang</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>