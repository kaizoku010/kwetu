<?php
$servername = "localhost:3306";
$username = "kwetzigc_luke";
$password = "{n0P.7e0a3t2";
$dbname = "kwetzigc_db2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo ("<script>console.log('connection failed');</script>");

    die("Connection failed: " . $conn->connect_error);
}

echo ("<script>console.log('connection set');</script>");

?>