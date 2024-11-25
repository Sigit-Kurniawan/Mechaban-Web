<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../assets/img/favicon.png" type="image/png">
    <title>Mechaban</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>


        <div class="main">
            <!-- header -->
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <!-- ----search---- -->
                <?php
                // Cek apakah halaman saat ini adalah 'setting.php dan booking.php'
                if (
                    basename($_SERVER['PHP_SELF']) !== 'setting.php' && basename($_SERVER['PHP_SELF']) !== 'booking.php' && basename($_SERVER['PHP_SELF']) !== 'aktivitas.php'
                    && basename($_SERVER['PHP_SELF']) !== 'aktivitas_detail.php'
                ): ?>
                    <div class="search">
                        <form action="mobil.php" method="GET"> <!-- Form menuju ke halaman yang sama -->
                            <label>
                                <input type="text" name="search" placeholder="Search here....."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <ion-icon name="search-outline"></ion-icon>
                            </label>
                        </form>
                    </div>
                <?php endif; ?>


                <!-- ----user img---- -->
                <div class="user">

                    <div class="user-img-container">
                        <?php
                        // Determine the photo path
                        $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                            ? '../uploads/' . htmlspecialchars($_SESSION["photo"])
                            : '../assets/img/default-profile.png';
                        ?>
                        <img src="<?php echo $userPhoto; ?>"
                            alt="User Profile Picture"
                            class="user-img"
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
            </div>

            <div class="cardbox">
                <div class="card">
                    <div>
                        <div class="number">12</div> <!-- Menampilkan jumlah mobil -->
                        <div class="cardname">Mobil</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="car-sport-outline"></ion-icon>
                    </div>
                </div>


                <div class="card">
                    <div>
                        <div class="number">1,504</div>
                        <div class="cardname">Sedang Proses</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="button">
                    <a href="\Mechaban-Web\menu_cus\booking\booking.php">
                        <button class="btn-reservasi">Reservasi Sekarang</button>
                    </a>
                </div>
            </div>


            <div class="details">
                <div class="recentOrder">
                    <div class="cardHeader">
                        <h2>Riwayat Transaksi</h2>
                        <a href="#" class="btn">View All</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <td>Nama</td>
                                <td>Harga</td>
                                <td>Pembayaran</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Star Refrigerator</td>
                                <td>Rp1.200.000</td>
                                <td>Terbayar</td>
                                <td><span class="status">Perbaikan</span></td>
                            </tr>

                            <tr>
                                <td>Star Refrigerator</td>
                                <td>Rp1.200.000</td>
                                <td>Terbayar</td>
                                <td><span class="status">Perbaikan</span></td>
                            </tr>

                            <tr>
                                <td>Star Refrigerator</td>
                                <td>Rp1.200.000</td>
                                <td>Terbayar</td>
                                <td><span class="status">Perbaikan</span></td>
                            </tr>

                            <tr>
                                <td>Star Refrigerator</td>
                                <td>Rp1.200.000</td>
                                <td>Terbayar</td>
                                <td><span class="status">Perbaikan</span></td>
                            </tr>

                            <tr>
                                <td>Star Refrigerator</td>
                                <td>Rp1.200.000</td>
                                <td>Terbayar</td>
                                <td><span class="status">Perbaikan</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Customers</h2>
                    </div>
                    <table>
                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>

                        <tr>
                            <td width="60px">
                                <div class="imgBx"><img src="../assets/images/user.jpg"></div>
                            </td>
                            <td>
                                <h4>David<br><span>Italy</span></h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../assets/js/main.js"></script>
</body>

</html>