<?php
try {
    $conn = mysqli_connect("localhost", "mece5739_mechaban", "Mechaban1234@#$%^", "mece5739_mechaban");
} catch (Exception $e) {
    die($e->getMessage());
}
