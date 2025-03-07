<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost:3306";
$username   = "kwetzigc_luke";
$password   = "{n0P.7e0a3t2";
$dbname     = "kwetzigc_db2";

try {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

echo("<script>console.log('connection set');</script>");
