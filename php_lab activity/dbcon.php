<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "php_db";

// Create database connection using new mysqli()
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>