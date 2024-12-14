<?php
session_start();
require('Api/koneksi.php');

$sql = "SELECT a.name, rc.rating, rc.teks_review, a.photo FROM review_customer rc JOIN booking b ON rc.id_booking = b.id_booking JOIN car c ON b.nopol = c.nopol JOIN account a ON c.email_customer = a.email";
$result = $conn->query($sql);
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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> -->

    <link rel="icon" type="image/png" href="assets/img/favicon.png" />

    <title>Mechaban</title>
</head>

<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="assets/img/logo.png" alt="Mechaban Logo">
                <span class="logo-text">Mechaban</span>
            </div>
            <nav>
                <ul>
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#mengapa-mechaban">Tentang Kami</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <?php if (isset($_SESSION["login"])): ?>
                        <li><a href="<?php if ($_SESSION['role'] == "customer") {
                                            echo "menu_cus/home_cus.php";
                                        } else if ($_SESSION['role'] == "admin") {
                                            echo "menu_admin/home_admin.php";
                                        }; ?> " class="masuk-btn">Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="masuk-btn">Masuk</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="background">
        <div class="container">

            <!-- Hero Section -->
            <section class="hero" id="hero">
                <div class="row">
                    <div class="col-4 hero-content">
                        <h1>Servis Mobil Lebih Mudah dengan Mechaban</h1>
                        <button class="booking-btn"><a href="login.php">Booking Sekarang</a></button>
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

    <!-- Layanan Kami -->
    <div class="card-container">
        <section class="card-section" id="layanan-kami">
            <div class="card">
                <div class="card-head">
                    <h1 class="card-title-2" id="title-layanan">Layanan Kami</h1>
                </div>
                <div class="card-content">
                    <div class="card-layanan">
                        <img src="assets/img/layanan/mesin.png" alt="mesin" class="img-layanan">
                        <h2 class="name-card-2">Mesin</h2>
                    </div>
                    <div class="card-layanan">
                        <img src="assets/img/layanan/ban.png" alt="ban" class="img-layanan">
                        <h2 class="name-card-2">Ban</h2>
                    </div>
                    <div class="card-layanan">
                        <img src="assets/img/layanan/rem.png" alt="rem" class="img-layanan">
                        <h2 class="name-card-2">Rem</h2>
                    </div>
                    <div class="card-layanan">
                        <img src="assets/img/layanan/oli.png" alt="oli" class="img-layanan">
                        <h2 class="name-card-2">Oli</h2>
                    </div>
                    <div class="card-layanan">
                        <img src="assets/img/layanan/kaki-kaki.png" alt="kaki2" class="img-layanan">
                        <h2 class=" name-card-2">Kaki-Kaki</h2>
                    </div>
                    <div class="card-layanan">
                        <img src="assets/img/layanan/berkala.png" alt="berkala" class="img-layanan">
                        <h2 class="name-card-2">Berkala</h2>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!--Mengapa harus Mechaban-->
    <div class="card-container">
        <section class="card-section" id="mengapa-mechaban">
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
        <section class="card-section" id="merk">
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

    <!--Testimoni-->
    <div class="card-container">
        <section class="card-section" id="testimoni">
            <div class="card">
                <div class="card-head">
                    <h1 class="card-title-2">Testimoni</h1>
                </div>
                <div class="card-content" id="testimoni-card">
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data setiap baris
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card-fitur">';
                            echo '<img src="uploads/customers/' . htmlspecialchars($row["photo"]) . '" alt="Foto ' . htmlspecialchars($row["name"]) . '">';
                            echo '<h3>' . htmlspecialchars($row["name"]) . '</h3>';
                            echo '<div class="stars">' . str_repeat('★', $row["rating"]) . str_repeat('☆', 5 - $row["rating"]) . '</div>';
                            echo '<p class="name-card-desc-testimoni">' . htmlspecialchars($row["teks_review"]) . '</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <!--FAQ-->
    <div class="card-container">
        <section class="card-section" id="faq">
            <div class="card">
                <div class="card-head">
                    <h2 class="card-title">Frequently Asked Question (FAQ)</h2>
                    <p class="title-desc">Pertanyaan-pertanyaan yang sering ditanyakan oleh pengguna tentang <span
                            class="mechaban">Mechaban</spanc>
                    </p>
                </div>
                <div class="accordion">
                    <div class="accordion-item">
                        <button class="accordion-header">Apa itu di Mechaban?
                            <img src="assets\img\panah_faq.png" clas="panah-faq">
                        </button>
                        <div class="accordion-content">
                            <p>Mechaban merupakan aplikasi servis mobil online yang dapat membantu dan mempermudah
                                proses servis mobil Anda. Dengan Mechaban, Anda dapat melakukan booking servis secara
                                online dan memilih jenis servis yang diperlukan. Dengan Mechaban anda tidak perlu
                                repot-repot datang ke bengkel, anda hanya perlu menunggu di rumah.</p>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-header">Bagaimana cara mendaftar di Mechaban?
                            <img src="assets\img\panah_faq.png" clas="panah-faq">
                        </button>
                        <div class="accordion-content">
                            <p>Silakan klik Masuk pada navbar di atas dan pilih Register. Anda juga dapat mendaftarkan
                                diri di aplikasi mobile Mechaban</p>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">
                            Bagaimana cara booking servis di Mechaban?
                            <img src="assets\img\panah_faq.png" clas="panah-faq">
                        </button>
                        <div class="accordion-content">
                            <p>This is the content for section 2.</p>
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
                <h1 class="teks-iklan">Dapatkan fitur lebih dari Mechaban di dalam genggaman</h1>
                <p class="teks-iklan">Di dalam aplikasi memiliki fitur memanajemen mobil dan menyimpan hasil inspeksi
                    mobil.</p>
            </div>
            <div class="gambar-hp">
                <img src="assets/img/hp.png" alt="">
            </div>
        </div>
    </div>

    <!-- Booking sekarang -->
    <div class="card-container">
        <div class="booking-sekarang" id="booking-sekarang">
            <span>Tunggu apa lagi? Yuk </span><button class="booking-btn"><a href="login.php">Booking
                    Sekarang</a></button>
        </div>
    </div>

    <!--Footer-->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom 1: Logo dan Lokasi -->
                <div class="col-md-4">
                    <div class="head-footer">
                        <img src="assets/img/logo.png" alt="Mechaban Logo">
                        <span class="logo-text">Mechaban</span>
                    </div>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15789.429274349659!2d114.13384924547573!3d-8.366466972177113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd15545503ba621%3A0x246ebdd1553c5b2e!2sBengkel%20MW%20Marchaban!5e0!3m2!1sen!2sid!4v1730480167263!5m2!1sen!2sid"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <!-- Kolom 2: Link Cepat -->
                <div class="col-md-4">
                    <h4 class="halaman">Halaman</h4>
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

    <script src="assets/js/landing_page.js"></script>
    <!-- <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 3,
            /* Menampilkan 3 review */
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true,
            /* Aktifkan loop untuk terus berputar */
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                480: {
                    slidesPerView: 1,
                }
            }
        });
    </script> -->
</body>

</html>