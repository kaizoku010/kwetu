<?php
$servername = "localhost:3306";
$username = "kwetzigc_luke";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo ("<script>console.log('connection failed');</script>");

    die("Connection failed: " . $conn->connect_error);
}

echo ("<script>console.log('connection set');</script>");

?>
