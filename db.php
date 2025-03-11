<?php
$servername = "localhost";
$username = "root";
$password = "64649";
$dbname = "ec";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<!--
// $servername = "localhost:3306";
// $username = "kwetzigc_luke";
// $password = "{n0P.7e0a3t2";
// $dbname = "kwetzigc_db2";

// $conn = new mysqli($servername, $username, $password, $dbname);
// hello sir dixon yes your code is sent well

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
