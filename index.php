<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
</head>

<body>
    <nav id="navbar" class="flex fixed z-50 items-center justify-center gap-64 lg:gap-96 bg-white bg-opacity-20 backdrop-blur-md px-4 py-4 lg:py-6 lg:px-24 w-full">
        <div id="title" class="flex items-center text-white flex-row">
            <img src="assets/img/logo.png" alt="" class="h-8 lg:h-10">
            <h1 class="font-semibold text-2xl ml-2 hidden lg:block">Mechaban</h1>
        </div>

        <button id="hamburger" class="lg:hidden text-white text-3xl focus:outline-none">
            &#9776;
        </button>

        <div id="menu" class="hidden lg:flex flex-row gap-8 text-2xl font-semibold text-white items-center">
            <a href="#">Beranda</a>
            <a href="#" class="inline whitespace-nowrap">Tentang Kami</a>
            <a href="#faq">FAQ</a>
            <?php if (isset($_SESSION["login"])): ?>
                <a id="login" class="bg-white py-2 px-4 text-primary rounded-lg shadow-lg" href="
          <?php if ($_SESSION['role'] == "customer") {
                    echo "menu_cus/home_cus.php";
                } else if ($_SESSION['role'] == "admin") {
                    echo "menu_admin/home_admin.php";
                }; ?> ">Dashboard</a>
            <?php else: ?>
                <a id="login" class="bg-white py-2 px-4 text-primary rounded-lg shadow-lg" href="login.php">Masuk</a>
            <?php endif; ?>
        </div>

        <div id="drawer" class="fixed top-0 right-0 h-screen w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex justify-end p-4">
                <button id="close-drawer" class="text-gray-500 text-4xl font-semibold focus:outline-none">&times;</button>
            </div>
            <div class="flex flex-col gap-6 px-6 pb-6 text-xl font-semibold text-gray-700">
                <a href="#">Beranda</a>
                <a href="#">Tentang Kami</a>
                <a href="#faq">FAQ</a>
                <?php if (isset($_SESSION["login"])): ?>
                    <a class="bg-primary py-2 px-4 text-white text-center rounded-lg" href="
          <?php if ($_SESSION['role'] == "customer") {
                        echo "menu_cus/home_cus.php";
                    } else if ($_SESSION['role'] == "admin") {
                        echo "menu_admin/home_admin.php";
                    }; ?> ">Dashboard</a>
                <?php else: ?>
                    <a class="bg-primary py-2 px-4 text-white text-center rounded-lg" href="login.php">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <a href="https://wa.me/6283832566069">
        <img src="assets/img/wa.png" alt="" class="fixed bottom-6 lg:bottom-8 right-6 lg:right-8 w-14 lg:w-16 z-50">
    </a>

    <div class="bg-cover bg-no-repeat bg-fixed w-full pt-48 lg:pt-12 pb-44" style="background-image: url('assets/img/bg.png');">
        <div class="flex flex-col lg:flex-row items-center h-[32rem] justify-center">
            <div class="flex flex-col lg:justify-start max-lg:items-center max-lg:text-center">
                <h1 class="text-4xl font-semibold text-white">Servis Mobil Lebih Mudah<br>dengan Mechaban</h1>
                <a href="login.php" class="bg-white py-2 px-4 my-8 text-primary font-semibold text-xl text-center rounded-lg w-fit">Booking Sekarang</a>
                <div class="flex flex-col">
                    <p class="text-white font-semibold">Tersedia juga di</p>
                    <img class="w-36" src="assets/img/playstore.png" alt="Google Play Badge">
                </div>
            </div>
            <div class="flex items-center justify-center lg:justify-end">
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
    </div>

    <?php
    $services = ["mesin", "Ban", "rem", "oli", "kaki-kaki", "berkala"];
    ?>

    <div class="flex flex-col bg-white px-4 sm:px-8 max-sm:mx-4 md:px-12 py-8 gap-4 sm:w-3/4 mx-auto rounded-lg shadow-lg -mt-32 lg:max-w-5xl">
        <h2 class="text-2xl sm:text-3xl font-bold text-primary text-center">Layanan Kami</h2>
        <div class="swiper mySwiper w-full max-w-full overflow-hidden relative">
            <div class="swiper-wrapper">
                <?php foreach ($services as $service) : ?>
                    <div class="swiper-slide">
                        <div class="flex flex-col gap-4 justify-center items-center p-4">
                            <img src="assets/img/layanan/<?php echo $service; ?>.png"
                                alt="<?php echo ucfirst($service); ?>"
                                class="h-12 sm:h-16 md:h-20">
                            <h2 class="font-semibold text-sm sm:text-base md:text-lg text-center">
                                <?php echo ucfirst($service); ?>
                            </h2>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <?php
    $mengapa = [
        ["harga-terjangkau", "Harga Terjangkau dan Transparan", "Mengetahui harga sebelum ke bengkel"],
        ["garansi", "Jaminan Garansi Selama 1 Bulan", "Menjamin garansi perbaikan setelah 1 bulan diperbaiki"],
        ["lokasi", "Memantau Lokasi Montir", "Mampu melacak lokasi montir di perjalanan"]
    ];
    ?>

    <div class="flex flex-col bg-white px-12 py-8 gap-4 w-fit mx-auto max-sm:mx-4 rounded-lg shadow-lg mt-16 lg:max-w-5xl">
        <h2 class="text-3xl font-bold text-primary text-center">Mengapa Memilih Mechaban?</h2>
        <div class="flex flex-col gap-4 md:flex-row md:justify-center">
            <?php foreach ($mengapa as $mengap) : ?>
                <div class="flex flex-col gap-4 items-center p-8 rounded-lg shadow-xl hover:transform hover:translate-y-[-8px] transition-transform duration-300">
                    <img src="assets/img/card/<?php echo $mengap[0]; ?>.png" alt="<?php echo ucfirst($mengap[1]); ?>" class="h-16 object-contains">
                    <h2 class="font-bold text-lg text-center text-primary"><?php echo $mengap[1]; ?></h2>
                    <p class="text-center"><?php echo $mengap[2]; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    $merks = ["honda", "toyota", "mitsubishi", "nissan", "mazda", "lexus", "suzuki", "bmw", "audi", "chevrolet"];
    ?>

    <div class="flex flex-col bg-white px-6 py-8 gap-6 max-w-5xl mx-auto rounded-lg shadow-lg mt-16">
        <h2 class="text-2xl lg:text-3xl font-bold text-primary text-center">Merek-Merek Terkenal yang Kami Layani</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 lg:grid-rows-2 gap-4">
            <?php foreach ($merks as $merk) : ?>
                <div class="flex items-center justify-center p-4">
                    <img class="h-16 sm:h-20 lg:h-24 object-contain" src="assets/img/logomobil/<?php echo $merk; ?>.png" alt="<?php echo ucfirst($merk); ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
    include 'Api/api/connect.php';

    $sql = "SELECT a.name, rc.rating, rc.teks_review, a.photo FROM review_customer rc JOIN booking b ON rc.id_booking = b.id_booking JOIN car c ON b.nopol = c.nopol JOIN account a ON c.email_customer = a.email";
    $result = $conn->query($sql);
    ?>

    <div class="flex flex-col bg-white px-4 sm:px-8 max-sm:mx-4 md:px-12 py-8 gap-4 sm:w-3/4 mx-auto rounded-lg shadow-lg mt-16 lg:max-w-5xl">
        <h2 class="text-3xl font-bold text-primary text-center">Kata Mereka Tentang Mechaban</h2>
        <div class="swiper mySwiper w-full max-w-full overflow-hidden relative">
            <div class="swiper-wrapper">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="swiper-slide">
                        <div class="flex flex-col gap-4 justify-center items-center p-4">
                            <img src="uploads/customers/<?php echo $row['photo']; ?>" alt="" class="h-16 rounded-full">
                            <div class="text-yellow-300 text-3xl"><?= str_repeat('★', $row["rating"]) . str_repeat('☆', 5 - $row["rating"]); ?></div>
                            <h2 class="font-bold text-lg text-center"><?php echo $row['name']; ?></h2>
                            <p class="text-center line-clamp-3 w-full"><?php echo $row['teks_review']; ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <div id="faq" class="flex flex-col bg-white px-12 py-8 gap-4 w-fit mx-auto rounded-lg shadow-lg mt-16 lg:max-w-5xl">
        <h2 class="text-3xl font-bold text-primary text-center">Frequently Asked Questions (FAQ)</h2>
        <p class="text-center text-xl">Pertanyaan-pertanyaan yang sering ditanyakan oleh pengguna tentang <span class="text-xl text-primary">Mechaban</span></p>
        <details class="group mb-4 border rounded-lg overflow-hidden">
            <summary class="cursor-pointer bg-gray-100 px-4 py-3 flex items-center justify-between hover:bg-gray-200">
                <span class="font-semibold">Apa itu di Mechaban?</span>
                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>
            <div class="px-4 py-3 text-gray-700 bg-white">
                Mechaban merupakan aplikasi servis mobil online yang dapat membantu dan mempermudah
                proses servis mobil Anda. Dengan Mechaban, Anda dapat melakukan booking servis secara
                online dan memilih jenis servis yang diperlukan. Dengan Mechaban anda tidak perlu
                repot-repot datang ke bengkel, anda hanya perlu menunggu di rumah.
            </div>
        </details>
        <details class="group mb-4 border rounded-lg overflow-hidden">
            <summary class="cursor-pointer bg-gray-100 px-4 py-3 flex items-center justify-between hover:bg-gray-200">
                <span class="font-semibold">Bagaimana cara mendaftar di Mechaban?</span>
                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>
            <div class="px-4 py-3 text-gray-700 bg-white">
                Silakan klik Masuk pada navbar di atas dan pilih Register. Anda juga dapat mendaftarkan
                diri di aplikasi mobile Mechaban
            </div>
        </details>
        <details class="group mb-4 border rounded-lg overflow-hidden">
            <summary class="cursor-pointer bg-gray-100 px-4 py-3 flex items-center justify-between hover:bg-gray-200">
                <span class="font-semibold">Bagaimana cara booking servis di Mechaban?</span>
                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </summary>
            <div class="px-4 py-3 text-gray-700 bg-white">
                Silakan klik Masuk pada navbar di atas dan pilih Register. Anda juga dapat mendaftarkan
                diri di aplikasi mobile Mechaban
            </div>
        </details>
    </div>

    <div class="relative bg-cover bg-no-repeat bg-fixed mx-auto rounded-lg shadow-lg mt-8 px-4 py-6 sm:mt-12 sm:px-8 sm:py-10 lg:mt-44 lg:px-12 lg:py-16 lg:max-w-5xl" style="background-image: url('assets/img/bg-iklan.png');">
        <img src="assets/img/hp.png" alt="" class="absolute z-10 right-4 -top-12 sm:right-[5%] sm:-top-28 lg:-top-36">
        <h2 class="text-base sm:text-lg lg:text-2xl font-bold text-white items-center w-full sm:w-3/4 lg:w-1/2">
            Dapatkan fitur lebih dari Mechaban di dalam genggaman
        </h2>
    </div>


    <div class="flex flex-col md:flex-row mt-28 gap-4 justify-center items-center text-center">
        <h1 class="text-2xl md:text-3xl font-bold">
            Tunggu apa lagi? Yuk
            <a href="login.php"
                class="text-white py-2 px-4 mt-4 md:mt-0 bg-primary font-bold text-xl md:text-3xl text-center rounded-lg w-fit inline-block hover:bg-primary-dark transition">
                Booking Sekarang
            </a>
        </h1>
    </div>

    <footer class="bg-primary w-full shadow-lg mt-16">
        <div class="flex flex-col justify-between lg:flex-row px-6 lg:px-12 py-8 gap-8 mx-auto lg:max-w-5xl">
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-3">
                    <img src="assets/img/logo.png" alt="Mechaban Logo" class="h-8 lg:h-10">
                    <h2 class="text-xl lg:text-2xl font-bold text-white">Mechaban</h2>
                </div>
                <iframe
                    class="w-full h-48 lg:h-64"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15789.429274349659!2d114.13384924547573!3d-8.366466972177113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd15545503ba621%3A0x246ebdd1553c5b2e!2sBengkel%20MW%20Marchaban!5e0!3m2!1sen!2sid!4v1730480167263!5m2!1sen!2sid"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="flex flex-row gap-6">
                <div class="">
                    <p class="text-white text-lg font-semibold">Halaman</p>
                    <div class="flex flex-col mt-4 space-y-2">
                        <a href="#" class="text-white text-base lg:text-lg hover:text-gray-300">Beranda</a>
                        <a href="#" class="text-white text-base lg:text-lg hover:text-gray-300">Tentang Kami</a>
                        <a href="#" class="text-white text-base lg:text-lg hover:text-gray-300">FAQ</a>
                    </div>
                </div>
                <div class="">
                    <p class="text-white text-lg font-semibold">Informasi Bisnis</p>
                    <p class="text-white text-base lg:text-lg mt-4">
                        Jalan Meliwis, No. 45, RT. 02, RW. 02<br>
                        Dus. Sawahan, Desa Genteng Kulon,<br>
                        Kec. Genteng, Kab. Banyuwangi
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-[#1550d0] text-white py-2 text-center text-sm lg:text-base">
            Mechaban | &copy; 2024
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="assets/js/landing_page.js"></script>
</body>

</html>