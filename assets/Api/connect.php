<?php
try {
    $conn = mysqli_connect("localhost", "root", "", "mechaban");
} catch (Exception $e) {
    die($e->getMessage());
}
