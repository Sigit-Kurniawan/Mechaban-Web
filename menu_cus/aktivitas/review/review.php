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
            <?php include '../../header.php'; ?>

            <div class="review">
                <h2>Berikan Review Anda</h2>


                <form action="review_process.php" method="POST">
                    <input type="hidden" name="id_booking" value="<?php echo $id_booking; ?>">

                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <div class="stars">

                            <input type="radio" name="rating" value="1" id="star1" required>
                            <label for="star1"></label>
                            <span>1</span>

                            <input type="radio" name="rating" value="2" id="star2" required>
                            <label for="star2"></label>
                            <span>2</span>


                            <input type="radio" name="rating" value="3" id="star3" required>
                            <label for="star3"></label>
                            <span>3</span>



                            <input type="radio" name="rating" value="4" id="star4" required>
                            <label for="star4"></label>
                            <span>4</span>



                            <input type="radio" name="rating" value="5" id="star5" required>
                            <label for="star5"></label>
                            <span>5</span>




                        </div>
                    </div>


                    <div class="form-group">
                        <label for="review_text">Komentar Anda:</label>
                        <textarea id="review_text" name="review_text" rows="5" required
                            placeholder="Tulis komentar Anda di sini"></textarea>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="submit-btn">Kirim Review</button>
                        <a href="../aktivitas_detail.php?id_booking=<?php echo $id_booking; ?>"
                            class="btn-kembali">Kembali</a>
                    </div>

                </form>








            </div>
        </div>
    </div>
    <script>document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const ratingInputs = document.querySelectorAll('input[name="rating"]');

            // Ketika form disubmit, pastikan hanya rating tertinggi yang dikirimkan
            form.addEventListener('submit', function (event) {
                let highestRating = 0;

                // Menentukan rating tertinggi
                ratingInputs.forEach(input => {
                    if (input.checked) {
                        highestRating = Math.max(highestRating, parseInt(input.value));
                    }
                });

                // Menyimpan rating tertinggi ke dalam input tersembunyi
                const hiddenRatingInput = document.createElement('input');
                hiddenRatingInput.type = 'hidden';
                hiddenRatingInput.name = 'rating';
                hiddenRatingInput.value = highestRating;

                // Menambahkan input tersembunyi ke form
                form.appendChild(hiddenRatingInput);
            });
        });
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../../assets/js/main.js"></script>
    <script src="review.js"></script>
</body>

</html>