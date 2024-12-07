<!-- lupa_password.php -->
<?php
require_once 'lupa_password_process.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="lupa_password.css">
</head>

<body>
    <div class="container">
        <div class="card">

            <div class="card-head">
                <div class="head-bg">
                    <img src="../assets/img/logo2.png" alt="Logo" class="head-logo">
                    <h2 class="head-title">Lupa Password</h2>
                </div>
            </div>

            <form action="lupa_password_process.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <button type="submit">Send OTP</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>