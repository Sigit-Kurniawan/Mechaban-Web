<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="icon" href="assets/img/Logo.png" type="image/png">
    <title>Mechaban</title>
</head>

<body>
    <div class="background">
        <div class="container">
            <header>
                <div class="logo">
                    <img src="assets/img/Logo.png" alt="Mechaban Logo">
                    <span class="logo-text">Mechaban</span>
                </div>
                <nav>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">FAQ</a></li>
                        <?php if (isset($_SESSION["login"])): ?>
                            <li><a href="<?php if ($_SESSION['role'] == "customer") {
                                                echo "dashboard-cus.php";
                                            } else if ($_SESSION['role'] == "admin") {
                                                echo "dashboard-admin.php";
                                            }; ?> " class="masuk-btn">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="masuk-btn">Masuk</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </header>

            <!-- Hero Section -->
            <section class="hero" id="hero">
                <div class="row">
                    <div class="col-4 hero-content">
                        <h1>Servis Mobil Lebih Mudah dengan Mechaban</h1>
                        <button class="booking-btn">Booking Sekarang</button>
                        <div class="download">
                            <h2>Tersedia juga di</h2>
                            <img src="assets/img/playstore.png" alt="Google Play Badge">
                        </div>
                    </div>
                    <div class="col-7 hero-image">
                        <div class="hero-slider">
                            <div class="slide">
                                <img src="assets/img/bengkel1.png" alt="Mobil di bengkel">
                            </div>
                            <div class="slide">
                                <img src="assets/img/bengkel2.png" alt="Mekanik Mechaban">
                            </div>
                            <div class="slide">
                                <img src="assets/img/bengkel3.png" alt="Mekanik Mechaban">
                            </div>
                            <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                            <a class="next" onclick="changeSlide(1)">&#10095;</a>
                        </div>
                    </div>
                </div>
            </section>
            <div class="whatsapp-icon">
                <img src="assets/img/wa.png" alt="Whatsapp Icon">
            </div>
        </div>
    </div>

    <!--Fitur Andalan-->
    <div class="card-container">
        <section class="card-section">
            <div class="card">
                <div class="card-head">
                    <h1 class="card-title">Fitur Andalan</h1>
                    <p class="title-desc">Fitur-fitur andalan kami di <span class="mechaban">Mechaban</span></p>
                </div>
                <div class="card-content">
                    <div class="card-fitur">
                        <img src="assets/img/card/home-servis.png" class="img-card">
                        <h2 class="name-card">Home Service</h2>
                        <p class="name-card-desc">Booking layanan secara online dari rumah</p>
                    </div>
                    <div class="card-fitur">
                        <img src="assets/img/card/inspection-booking.png" class="img-card">
                        <h2 class="name-card">Inspection Booking</h2>
                        <p class="name-card-desc">Menginspeksi masalah pada mobil pelanggan secara detail via aplikasi
                        </p>
                    </div>
                    <div class="card-fitur">
                        <img src="assets/img/card/reminder-servis.png" class="img-card">
                        <h2 class="name-card">Reminder Service</h2>
                        <p class="name-card-desc">Mendapatkan pengingat rutin untuk service mobil via aplikasi</p>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!--Mengapa harus Mechaban-->
    <div class="card-container">
        <section class="card-section">
            <div class="card">
                <div class="card-head">
                    <h1 class="card-title-2">Mengapa Harus Booking di Mechaban?</h1>
                </div>
                <div class="card-content">
                    <div class="card-fitur">
                        <img src="assets/img/card/harga-terjangkau.png" class="img-card">
                        <h2 class="name-card-2">Harga terjangkau dan transparan</h2>
                        <p class="name-card-desc">Mengetahui harga sebelum ke bengkel</p>
                    </div>
                    <div class="card-fitur">
                        <img src="assets/img/card/garansi.png" class="img-card">
                        <h2 class="name-card-2">Jaminan Garansi Selama 1 Bulan</h2>
                        <p class="name-card-desc">Terdapat garansi perbaikan setelah 1 bulan diperbaiki</p>
                    </div>
                    <div class="card-fitur">
                        <img src="assets/img/card/payment.png" class="img-card">
                        <h2 class="name-card-2">Fleksibilitas Pembayaran</h2>
                        <p class="name-card-desc">Pembayaran bisa melalui online maupun langsung di bengkel</p>
                    </div>
                    <div class="card-fitur">
                        <img src="assets/img/card/lokasi.png" class="img-card">
                        <h2 class="name-card-2">Memantau Lokasi Montir</h2>
                        <p class="name-card-desc">Mampu melacak lokasi montir di perjalanan</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!--Merk yang kami layani-->
    <div class="card-container">
        <section class="card-section">
            <div class="card" id="merk">
                <div class="card-head">
                    <h1 class="card-title-2">Merek-Merek Terkenal yang Kami Layani</h1>
                </div>

                <div class="merk-galeri">
                    <img src="assets/img/logomobil/honda.png" alt="Honda" class="merk-logo">
                    <img src="assets/img/logomobil/toyota.png" alt="Toyota" class="merk-logo">
                    <img src="assets/img/logomobil/mitsubishi.png" alt="Mitsubishi" class="merk-logo">
                    <img src="assets/img/logomobil/nissan.png" alt="Nissan" class="merk-logo">
                    <img src="assets/img/logomobil/mazda.png" alt="BMW" class="merk-logo">
                    <img src="assets/img/logomobil/lexus.png" alt="BMW" class="merk-logo">
                    <img src="assets/img/logomobil/suzuki.png" alt="BMW" class="merk-logo">
                    <img src="assets/img/logomobil/bmw.png" alt="BMW" class="merk-logo">
                    <img src="assets/img/logomobil/audi.png" alt="BMW" class="merk-logo">
                    <img src="assets/img/logomobil/chevrolet.png" alt="Chevrolet" class="merk-logo">
                </div>
            </div>
        </section>
    </div>

    <!--FAQ-->
    <div class="card-container">
        <section class="card-section">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">Frequently Asked Question (FAQ)</h2>
                    <p class="title-desc">Pertanyaan-pertanyaan yang sering ditanyakan oleh pengguna tentang <span
                            class="mechaban">Mechaban</spanc>
                    </p>
                </div>
                <div class="accordion">
                    <div class="accordion-item">
                        <button class="accordion-header">Bagaimana cara mendaftar di Mechaban?</button>
                        <div class="accordion-content">
                            <p>This is the content for section 1.</p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">Bagaimana cara mendaftar di Mechaban?</button>
                        <div class="accordion-content">
                            <p>This is the content for section 2.</p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">Bagaimana cara mendaftar di Mechaban?</button>
                        <div class="accordion-content">
                            <p>This is the content for section 3.</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>



    <!--Iklan-->
    <div class="card-container">
        <div class="iklan-hp" id="iklan">
            <div class="iklan-content">
                <h1>Dapatkan fitur lebih dari Mechaban di dalam genggaman</h1>
                <p>Di dalam aplikasi memiliki fitur memanajemen mobil dan menyimpan hasil inspeksi mobil.</p>
            </div>
            <div class="gambar-hp">
                <img src="assets/img/hp.png" alt="">
            </div>
        </div>
    </div>

    <!--Footer-->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom 1: Logo dan Gambar -->
                <div class="col-md-4">
                    <div class="logo">
                        <img src="assets/img/Logo.png" alt="Mechaban Logo">
                        <span class="logo-text">Mechaban</span>
                    </div>
                    <img src="path/to/map-image.png" alt="Map" class="footer-map">
                </div>

                <!-- Kolom 2: Link Cepat -->
                <div class="col-md-4">
                    <h4>Halaman</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Informasi Bisnis -->
                <div class="col-md-4">
                    <h4>Informasi Bisnis</h4>
                    <p>Jalan Meliwis, No. 45, RT. 02, RW. 02</p>
                    <p>Dus. Sawahan, Desa Genteng Kulon, Kec. Genteng, Kab. Banyuwangi</p>

                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Mechaban | &copy; 2024</p>
        </div>
    </footer>


    <script src="assets/js/main.js"></script>
</body>

</html>