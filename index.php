<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
                        <li><button class="masuk-btn">Masuk</button></li>
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
                        <img src="assets/img/card/reminder-service.png" class="img-card">
                        <h2 class="name-card">Reminder Service</h2>
                        <p class="name-card-desc">Mendapatkan pengingat rutin untuk service mobil via aplikasi</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="container">
        <!--Section Merk-->
        <section class="merk">
            <h2>Merk Mobil yang Dilayani</h2>
            <div class="merk-gallery">
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
                <!-- Tambahkan gambar merk mobil lainnya sesuai kebutuhan -->
            </div>
        </section>
    </div>

    <script src="assets/js/main.js"></script>
</body>

</html>