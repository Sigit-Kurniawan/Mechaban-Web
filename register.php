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
                    <h2 class="head-title">Register</h2>
                </div>
            </div>


            <div class="card-content">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert">
                        <?php
                        if ($_GET['error'] === 'empty_fields') {
                            echo "Semua field harus diisi!";
                        } elseif ($_GET['error'] === 'email_exists') {
                            echo "Email telah terdaftar.";
                        } elseif ($_GET['error'] === 'password_invalid') {
                            echo "Password tidak memenuhi syarat.";
                        } elseif ($_GET['error'] === 'konfirm_password_invalid') {
                            echo "Konfirmasi password tidak sesuai.";
                        } elseif ($_GET['error'] === 'email_invalid') {
                            echo "Email tidak sesuai format.";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="card-content">
                    <form action="Api/register_process.php" method="POST">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" name="name" placeholder="Masukkan nama anda">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Masukkan email anda">
                            <span class="teks-span">Contoh: email@gmail.com
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="no_hp">No. Hp</label>
                            <input type="text" id="no_hp" name="no_hp" placeholder=" Masukkan no. hp anda">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Buat password anda ">
                            <span class="teks-span">Password minimal 8 karakter, termasuk huruf kapital,
                                huruf kecil, angka dan simbol(@$!%*?&)
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="password">Konfirmasi Password</label>
                            <input type="password" id="konfirm_password" name="konfirm_password"
                                placeholder="Konfirmasi password anda">
                        </div>

                        <div class="form-group">
                            <label for="role" id="role">Role</label>
                            <select name="role" class="role">
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit">Register</button>

                        <div class="teks">
                            <p>Sudah punya akun? <a href="login.php"> Login</a></p>
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