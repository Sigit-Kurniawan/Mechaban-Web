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

    <link rel="icon" href="../assets/img/logo.png" type="image/png">
    <title>Mechaban</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container">
        <?php include_once 'sidebar_cus.php'; ?>


        <div class="main">
        <?php include 'header.php' ?>

            <div class="cardbox">
                <div class="card">
                    <div>
                        <div class="number">1,504</div>
                        <div class="cardname">Mobil</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="car-sport-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="number">1,504</div>
                        <div class="cardname">Daily Views</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="button">
                    <a href="#">
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