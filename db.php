<!-- <?php
$conn = new mysqli("localhost", "root", "root", "foodfusion_db");

if ($conn->connect_error) {
    die("Database connection failed");
}
?> -->

<?php
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'root';
$db   = getenv('MYSQLDATABASE') ?: 'foodfusion_db';
$port = getenv('MYSQLPORT') ?: 8889;

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>