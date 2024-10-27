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
    <link rel="icon" href="assets/img/logo.png" type="image/png">
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
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        Email atau password salah
                    </div>
                <?php endif; ?>

                <form action="Api/login_process.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="Masukkan email anda">
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

</body>

</html>