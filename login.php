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
                    $errorMessages = [
                        1 => "Montir hanya bisa login melalui aplikasi mobile Android.",
                        2 => "Email atau password salah.",
                        3 => "Role tidak sesuai.",
                        4 => "Terjadi kesalahan pada server. Silakan coba lagi nanti."
                    ];

                    // Check if the error code exists in the messages array
                    if (array_key_exists($_GET['error'], $errorMessages)) {
                        echo '<div class="alert">' . htmlspecialchars($errorMessages[$_GET['error']], ENT_QUOTES, 'UTF-8') . '</div>';
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
                        <p>Anda lupa password? <a href="/Mechaban-Web/lupa_password/lupa_password.php"> Lupa
                                Password</a></p>
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