<?php
// Database connection configuration
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "trackit_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for proper emoji and character support
$conn->set_charset("utf8mb4");

// Optional: Set timezone
date_default_timezone_set('Asia/Manila');
?>