<?php
$servername = getenv('DB_HOST') ?: "localhost:3306";
$username = getenv('DB_USER') ?: "kwetzigc_luke";
$password = getenv('DB_PASS') ?: "{n0P.7e0a3t2";
$dbname = getenv('DB_NAME') ?: "kwetzigc_db2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed");
}

// Enable connection pooling
$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
?>