<?php
$conn = new mysqli("localhost", "root", "root", "foodfusion_db");

if ($conn->connect_error) {
    die("Database connection failed");
}
?>