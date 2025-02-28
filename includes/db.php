<?php
$servername = "localhost";
$username = "root";
$password = "64649";
$dbname = "ec";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo ("<script>console.log('connection failed');</script>");

    die("Connection failed: " . $conn->connect_error);
}
echo ("<script>console.log('connection set');</script>");
?>
