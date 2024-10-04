<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Web</title>

    <!-- Import Icon Unicons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <!-- Import Icon Boxicon -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
    <div class="sidebar">
        <?php 
            if (file_exists('sidebar.php')) {
                include 'sidebar.php';
            } else {
                echo '<p>Sidebar tidak ditemukan.</p>';
            }
        ?>
    </div>

    <main class="main-content" role="main">
        <section class="home-section">
            <header>
                <div class="sidebar-button" aria-label="Toggle sidebar">
                    <i class='bx bx-menu'></i>
                    <h2 class="dashboard">Dashboard</h2>
                </div>
                <div class="search-box">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Cari...">
                </div>
                <div class="profile">
                    <img src="assets/images/logo.jpg" alt="Profile Picture">
                    <span class="admin_name">Naraya</span>
                    <i class='bx bxs-chevron-down'></i>
                </div>
            </header>
            
            <div class="content">
                <div class="row">
                    <div class="col-12">
                        <h1 class="mb-4">Selamat Datang di Dashboard Admin</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pelanggan</h5>
                                <p class="card-text">150</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Reservasi Aktif</h5>
                                <p class="card-text">5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Reservasi Selesai</h5>
                                <p class="card-text">10</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Pendapatan Hari Ini</h5>
                                <p class="card-text">Rp.500.000</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="recent-activity">
                            <h2>Aktivitas sebelumnya</h2>
                            <ul>
                                <li>Montir Saleh mengisi reservasi baru.</li>
                                <li>Montir Antasari Login.</li>
                                <li>Reservasi dikonfirmasi.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="quick-actions">
                            <h2>Quick Actions</h2>
                            <button class="btn btn-primary">Add New User</button>
                            <button class="btn btn-success">Create New Reservation</button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="chart-placeholder">
                            <h2>Revenue Chart</h2>
                            <p>[Chart will go here]</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script> 
</body>
</html>