<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "64649";
$dbname = "ec";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    
    // Only show error message if not in production
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        die("Connection failed: " . $conn->connect_error);
    } else {
        die("Internal server error");
    }
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");
?>
