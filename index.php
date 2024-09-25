<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Admin</title>

    <!-- Import Icon Unicons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <!-- Import Icon Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Link ke CSS Sidebar dan Home Section -->
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/home-section.css">
</head>
<body>

    <!-- Sidebar -->
    <nav>
        <?php include 'sidebar/sidebar.php'; ?>
    </nav>
    
    <section class="home-section">
        <header>
            <div class="home-logo">
                <span class="dashboard">Dashboard</span>
            </div>

            <div class="search-box">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Cari...">
            </div>

            <div class="profile">
                <img src="images/logo.jpg" alt="Profile Picture">
                <span class="admin_name">Naraya</span>
            </div>
        </header>
    </section>

</body>
</html>
