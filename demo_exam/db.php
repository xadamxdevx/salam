<?php
$connect = mysqli_connect("localhost", "root", "", "demo_exam");

if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($connect, "utf8mb4");
?>