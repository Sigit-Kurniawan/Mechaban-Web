<?php
session_start();
if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <title>Mechaban</title>
</head>

<body>
    <div class="card-container">
        <div class="card">
            <div class="card-head">
                <div class="head-bg">
                    <img src="assets/img/logo2.png" alt="Logo" class="head-logo">
                    <h2 class="head-title">Login</h2>
                </div>
            </div>

            <div class="card-content">
                <!-- Tampilkan pesan error jika terjadi kesalahan -->
                <?php
                if (isset($_GET['error'])):
                    $error = intval($_GET['error']);
                    if ($error === 1) {
                        echo '<div class="alert">Email atau password salah.</div>';
                    }
                endif;
                ?>

                <form action="Api/login_process.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email anda">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password anda">
                    </div>

                    <button type="submit" name="login">Login</button>

                    <div class="teks">
                        <p>Belum punya akun? <a href="register.php"> Register</a></p>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Menghilangkan elemen dengan class "alert" setelah 3 detik
        setTimeout(() => {
            const alertElement = document.querySelector('.alert');
            if (alertElement) {
                alertElement.style.transition = 'opacity 0.5s';
                alertElement.style.opacity = '0';
                setTimeout(() => alertElement.remove(), 500); // Menghapus elemen setelah transisi selesai
            }
        }, 3000);
    </script>


</body>

</html>