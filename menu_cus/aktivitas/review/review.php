<?php
session_start();
if (!isset($_SESSION["login"]) || !isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

if (!file_exists('../../../Api/koneksi.php')) {
    die("Database connection file not found.");
}
include_once('../../../Api/koneksi.php');
$email_customer = $_SESSION["email"];

// Ambil ID booking dari URL
$id_booking = isset($_GET['id_booking']) ? $_GET['id_booking'] : null;


$query = "
    SELECT b.id_booking
    FROM booking b
    JOIN car c ON b.nopol = c.nopol
    JOIN account a ON c.email_customer = a.email
    WHERE a.email = ? 
    AND b.id_booking = ? 
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $email_customer, $id_booking);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


// Add this after the existing query
$check_review_query = "
    SELECT * FROM review_customer
    WHERE id_booking = ? 
    AND teks_review IS NOT NULL
";

$check_stmt = mysqli_prepare($conn, $check_review_query);
mysqli_stmt_bind_param($check_stmt, "s", $id_booking);
mysqli_stmt_execute($check_stmt);
$review_result = mysqli_stmt_get_result($check_stmt);
$has_review = mysqli_num_rows($review_result) > 0;

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../../assets/img/favicon.png" />
    <title>Mechaban</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="review.css">
</head>

<body>
    <div class="container">
        <?php include '../../sidebar.php'; ?>
        <div class="main">
            <div class="header">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>


                <!-- ----user img---- -->
                <div class="user">

                    <div class="user-img-container">
                        <?php
                        // Determine the photo path
                        $userPhoto = isset($_SESSION["photo"]) && !empty($_SESSION["photo"])
                            ? '../../../uploads/' . htmlspecialchars($_SESSION["photo"])
                            : '../../../assets/img/default-profile.png';
                        ?>
                        <img src="<?php echo $userPhoto; ?>" alt="User Profile Picture" class="user-img"
                            onclick="showPhotoModal('<?php echo $userPhoto; ?>')">

                        <div class="user-status <?php echo ($_SESSION["is_online"]) ? 'online' : 'offline'; ?>"></div>
                    </div>


                    <div class="user-info">
                        <div class="username">
                            <span class="name">
                                <?php echo isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"]) : 'Guest'; ?>
                            </span>
                            <span class="role">
                                <?php echo isset($_SESSION["role"]) ? htmlspecialchars($_SESSION["role"]) : 'Visitor'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="review">
                <?php if ($has_review): ?>
                <?php $review_data = mysqli_fetch_assoc($review_result); ?>
                <h2>Review Anda</h2>
                <div class="existing-review">
                    <div class="rating-display">
                        Rating:
                        <?php echo str_repeat('★', $review_data['rating']) . str_repeat('☆', 5 - $review_data['rating']); ?>
                    </div>
                    <div class="review-text">
                        <p><?php echo htmlspecialchars($review_data['teks_review']); ?></p>
                    </div>
                    <div class="button-container">
                        <a href="../aktivitas_detail.php?id_booking=<?php echo $id_booking; ?>"
                            class="btn-kembali">Kembali</a>
                    </div>
                </div>
                <?php else: ?>
                <h2>Berikan Review Anda</h2>
                    <form action="review_process.php" method="POST">
                        <input type="hidden" name="id_booking" value="<?php echo $id_booking; ?>">

                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <div class="stars">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star1"></label>

                                <input type="radio" name="rating" value="4" id="star4" required>
                                <label for="star2"></label>

                                <input type="radio" name="rating" value="3" id="star3" required>
                                <label for="star3"></label>

                                <input type="radio" name="rating" value="2" id="star2" required>
                                <label for="star4"></label>

                                <input type="radio" name="rating" value="1" id="star1" required>
                                <label for="star5"></label>
                            </div>

                        </div>


                        <div class="form-group">
                            <label for="review_text">Komentar Anda:</label>
                            <textarea id="review_text" name="review_text" rows="5" required
                                placeholder="Tulis komentar Anda di sini"></textarea>
                        </div>

                        <div class="button-container">
                            <button type="submit" class="submit-btn">Kirim Review</button>
                        </div>

                    </form>
                    <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const stars = document.querySelectorAll('.stars input');

        stars.forEach((star, index) => {
            star.addEventListener('change', function() {
                const rating = this.value;
                // Clear all stars
                stars.forEach(s => s.checked = false);
                // Fill stars up to selected rating
                for (let i = 0; i < rating; i++) {
                    stars[i].checked = true;
                }
            });
        });
    });
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../../assets/js/main.js"></script>
    <script src="review.js"></script>
</body>

</html>