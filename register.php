<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="icon" href="assets/img/Logo.png" type="image/png">
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

                <form action="register_process.php" method="POST">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama anda">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" placeholder="Msukkan email anda">
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. Hp</label>
                        <input type="text" id="no_hp" name="no_hp" placeholder=" Masukkan no. hp anda">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Buat password anda ">
                        <span class="teks-password">Password minimal 8 karakter, termasuk huruf kapital,
                            huruf kecil, angka dan simbol(@$!%*?&)
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="role" id="role">Role</label>
                        <select name="role" class="role">
                            <option value="Admin">Admin</option>
                            <option value="Montir">Montir</option>
                            <option value="Customer">Customer</option>
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

</body>

</html>