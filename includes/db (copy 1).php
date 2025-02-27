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


<
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
