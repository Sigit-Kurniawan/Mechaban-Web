<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // Redirect ke halaman login
exit();
