<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   About Us
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #007bff;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar img {
            height: 50px;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
            margin-left: 700px;
        }
        .navbar ul li {
            margin: 0 15px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500px;
        }
        .navbar .auth-buttons a {
            text-decoration: none;
            color: #007bff;
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            margin-left: 10px;
        }
        .header {
            background: url('assets/img/background.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 150px 20px 100px;
            position: relative;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .header h1 {
            font-size: 48px;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .header p {
            font-size: 18px;
            margin: 10px 0 0;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 50px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: left;
        }
        .content .text {
            max-width: 600px;
            margin-right: 20px;
        }
        .content h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }
        .content p {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        .content img {
            max-width: 100%;
            border-radius: 10px;
        }
        .unique-factors {
            background-color: #f8f9fa;
            padding: 50px 20px;
            text-align: center;
        }
        .unique-factors h2 {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 40px;
        }
        .unique-factors .factors {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .unique-factors .factor {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #007bff;
            flex: 1;
            max-width: 200px;
        }
        .unique-factors .factor.active {
            background-color: #007bff;
            color: #fff;
        }
        .container {
            padding: 20px;
        }
        h1 {
            color: #007bff;
            font-size: 40px;
            text-align: center;
        }
        
        .services {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .service-item {
            text-align: center;
            margin: 10px;
        }
        .service-item img {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .service-item p {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }
        
        .container1 {
            display: flex;
            align-items: center;
        }
        .image {
            margin-left: 100px;

        }
        .image img {
            width: 300px;
            height: 300px;
        }
        .text {
            max-width: 800px;
        }
        .text h2 {
            color: #007bff;
            margin: 20px;
        }
        .text p {
            margin: 20px;
            color: #333333;
        }
        .container2 {
            text-align: center;
            max-width: 1000px;
            top: 50px;
            margin: auto;
            color: #007bff;
        }
        .title {
            font-size: 35px;
            font-weight: 700;
            margin-bottom: 20px;
            margin-top: 10%;
        }
        .carousel {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease;
        }
        .testimonial {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 10px;
            flex-shrink: 0;
        }
        .testimonial img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .testimonial .name {
            font-weight: 700;
            margin-top: 10px;
            color: #007bff;
        }
        .testimonial .position {
            color: #757575;
            font-size: 14px;
        }
        .testimonial .company {
            color: #757575;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .testimonial .text {
            font-size: 14px;
            color: #333;
        }
        .navigation {
            margin-top: 50px;
        }
        .navigation .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background: #e0e0e0;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
        }
        .navigation .dot.active {
            background: #333;
        }
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
        }
        .arrow.left {
            left: 10px;
        }
        .arrow.right {
            right: 10px;
        }
        .cta {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 50px 20px;
        }
        .cta h2 {
            font-size: 24px;
            margin: 0 0 20px;
        }
        .cta a {
            text-decoration: none;
            color: #007bff;
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        footer {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    /* Mengatur ruang di antara elemen */
    padding: 20px 0;
    /* Padding atas dan bawah */
    background-color: #0697f2;
    /* Warna latar belakang footer */
    color: white;
    /* Warna teks footer */
}

.container-fluid {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.col-md-4 {
    flex: 1;
    padding: 10px;
}

.head-footer {
    display: flex;
    align-items: center;
}

.head-footer img {
    width: 60px;
    height: 30px;
    margin-right: 10px;
    max-width: 100%;
    height: auto;
}

.logo-text {
    font-size: 24px;
    font-weight: bold;
}


/* Gaya iframe untuk peta */

iframe {
    width: 100%;
    height: 200px;
    border: none;
    border-radius: 5px;
    margin-top: 15px;
}

.col-md-4 h4 {
    font-size: 24px;
    margin-bottom: 10px;
    margin-top: 0px;
}

.halaman {
    margin-left: 30%;
}


/* Gaya untuk daftar tautan */

.col-md-4 ul {
    font-size: 18px;
    list-style-type: none;
    /* Menghapus bullet list */
    padding: 0;
    margin-top: 15px;
    margin-left: 30%;
}

li {
    margin: 8px 0;
}

li a {
    color: white;
    text-decoration: none;
}

li a:hover {
    text-decoration: underline;
}


/* Gaya untuk informasi bisnis */

.col-md-4 p {
    font-size: 18px;
    margin: 5px 0;
    margin-top: 15px;
}


/* Styling untuk bagian bawah footer */

.footer-bottom {
    margin-bottom: 0;
    text-align: center;
    padding: 20px;
    /* Ruang atas dan bawah */
    background-color: #1550d0;
}


/* Gaya untuk teks hak cipta */

.footer-bottom p {
    margin: 0;
    /* Menghapus margin */
    font-size: 14px;
    /* Ukuran font untuk hak cipta */
}
  </style>
 </head>
 <body>
  <div class="navbar">
   <img src="assets/img/logo.png" alt="Mechaban Logo">
   <ul>
    <li>
     <a href="index.php">
      Beranda
     </a>
    </li>
    <li>
     <a href="#">
      Tentang
     </a>
    </li>
    <li>
     <a href="#">
      FAQ
     </a>
    </li>
   </ul>
   <div class="auth-buttons">
    <a href="#">
     Sign up
    </a>
   </div>
  </div>
  <header class="header">
   <h1>
    About us
   </h1>
   <p>
   Bikin pengalaman servis to the next level dengan pengingat digital kesehatan mobil dan harga dijamin transparan.
   </p>
  </header>
  <section class="content">
   <div class="text">
    <h2>
     About Our Company
    </h2>
    <p>
    Bengkel Mobil mechaban merupakan bengkel mobil modern yang bertujuan untuk merawat mobil agar lebih baik dari sebelumnya yang Merupakan poin terpentingnya.
    </p>
    <p>
    Dengan kualitas yang transparan dan masa garansi di seluruh cabang untuk jaminan layanannya. Mechaban Mobil, merupakan bengkel modern berteknologi canggih yang terus memfokuskan diri. Untuk terus berkembang dan merawat mesin mobil kesayangan Anda.
    </p>
   </div>
   <img alt="Company" height="400" src="assets/img/about-our-compeny.jpg" width="600"/>
  </section>
  <div class="container">
   <h1>
    Layanan Kami
   </h1>
   <div class="services">
    <div class="service-item">
    <img src="assets/img/layanan/mesin.png" alt="mesin" class="img-layanan">
     <p>
      Mesin
     </p>
    </div>
    <div class="service-item">
    <img src="assets/img/layanan/ban.png" alt="ban" class="img-layanan">
     <p>
      Ban
     </p>
    </div>
    <div class="service-item">
    <img src="assets/img/layanan/rem.png" alt="rem" class="img-layanan">
     <p>
      Rem
     </p>
    </div>
    <div class="service-item">
    <img src="assets/img/layanan/oli.png" alt="oli" class="img-layanan">
     <p>
      Oli
     </p>
    </div>
    <div class="service-item">
    <img src="assets/img/layanan/kaki-kaki.png" alt="kaki2" class="img-layanan">
     <p>
      Kaki-Kaki
     </p>
    </div>
    <div class="service-item">
    <img src="assets/img/layanan/berkala.png" alt="berkala" class="img-layanan">
     <p>
      Berkala
     </p>
    </div>
   </div>
  </div>
  <div class="container1">
   <div class="image">
    <img alt="hp" height="200" src="assets/img/car-service.jpeg" width="100"/>
   </div>
   <div class="text">
    <h2>
     Visi
    </h2>
    <p>
     Menjadi mitra terpercaya untuk segala kebutuhan otomotif Anda.
    </p>
    <h2>
     Misi
    </h2>
    <p>
     Menyediakan layanan yang transparan, akurat, dan terjangkau untuk semua pemilik kendaraan, dengan mengedepankan teknologi dan profesionalisme dalam setiap layanan yang diberikan.
    </p>
   </div>
  </div>
 
  <div class="container2">
        <div class="title">Mereka Tentang Kita</div>
        <div class="carousel">
            <button class="arrow left" onclick="prevSlide()">&#10094;</button>
            <div class="carousel-inner">
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Rizki Muzaki">
                    <div class="name">Rizki Muzaki</div>
                    <div class="position">Kepala Mekanik</div>
                    <div class="company">Bengkel Mili Klinik Motor Pasuruan</div>
                    <div class="text">Selama menggunakan MECHABAN, memasukkan data konsumen menjadi lebih mudah dan cepat. Kualitas hasil laporan sangat memuaskan. MECHABAN juga bisa diakses dimanapun saya berada.</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Tonih">
                    <div class="name">Tonih</div>
                    <div class="position">Kepala Bengkel</div>
                    <div class="company">Honda Sentra Armada Motor Tangsel</div>
                    <div class="text">MECHABAN sangat mempermudah untuk membuat laporan keuangan & sparepart. Usul kami setiap ada update dapat disosialisasikan agar bisa dipelajari untuk dijalankan.</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Inton Merli Purnomo</div>
                    <div class="position">PIC SPAREPART</div>
                    <div class="company">PT. Artha Sentra Oto Jakarta Timur</div>
                    <div class="text">Lebih mudah pencarian data, program mudah dioperasikan, tampilan OK</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Ibnu Malik</div>
                    <div class="position">PIC SPAREPART</div>
                    <div class="company">PT. Pecinta tranpostasi</div>
                    <div class="text">Menarik dan mempermudah pengguna</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Nahdya Afkarina</div>
                    <div class="position">PROMOTOR KESEHATAN</div>
                    <div class="company">PT. KESEHATAN MOBIL</div>
                    <div class="text">Sangat membantu dan aman</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Ilham budi pratama</div>
                    <div class="position">Shoppy food</div>
                    <div class="company">PT. SHOOPY TERPERCAYA</div>
                    <div class="text">Sangat membantu saya dalam masalah waktu di jalan</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Rayhan Ramadani</div>
                    <div class="position">BENDAHARA</div>
                    <div class="company">PT. OTOKLIK</div>
                    <div class="text">Sangat bisa di andalkan dan sangat memuaskan</div>
                </div>
                <div class="testimonial">
                    <img src="https://placehold.co/50x50" alt="Profile picture of Inton Merli Purnomo">
                    <div class="name">Ahmad Fauzan</div>
                    <div class="position">PIC SPAREPART</div>
                    <div class="company">PT. MITRA JAYA </div>
                    <div class="text">Sangat bermanfaat dan harga kantong</div>
                </div>
            </div>
            <button class="arrow right" onclick="nextSlide()">&#10095;</button>
        </div>
        <div class="navigation">
            <span class="dot" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot active" onclick="currentSlide(2)"></span>
        </div>
    </div>
    <script>
        let currentIndex = 0;

        function showSlide(index) {
            const slides = document.querySelectorAll('.testimonial');
            const dots = document.querySelectorAll('.dot');
            if (index >= slides.length) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = slides.length - 1;
            } else {
                currentIndex = index;
            }
            const offset = -currentIndex * (slides[0].offsetWidth + 20);
            document.querySelector('.carousel-inner').style.transform = `translateX(${offset}px)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        function currentSlide(index) {
            showSlide(index);
        }
    </script>

  <section class="cta">
   <h2>
    Tunggu apa lagi? Yuk
   </h2>
   <a href="#">
    Booking sekarang
   </a>
  </section>
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
</body>

</html>