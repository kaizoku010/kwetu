<?php
include './includes/db.php';

// Debug output (temporary)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id'])) {
    error_log("No ID provided");
    header("HTTP/1.0 404 Not Found");
    exit;
}

$id = (int)$_GET['id'];

// Debug the current working directory
error_log("Current working directory: " . getcwd());

// First check for additional images
$stmt = $conn->prepare("SELECT image_path FROM item_images WHERE item_id = ? AND is_primary = TRUE LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    error_log("Found image path: " . $row['image_path']);
    if (file_exists($row['image_path'])) {
        $mime = mime_content_type($row['image_path']);
        header("Content-Type: $mime");
        readfile($row['image_path']);
        exit;
    }
    error_log("File does not exist at path: " . $row['image_path']);
}

// Fallback to auction_items table
$stmt = $conn->prepare("SELECT image FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && !empty($row['image'])) {
    error_log("Found fallback image: " . $row['image']);
    if (file_exists($row['image'])) {
        $mime = mime_content_type($row['image']);
        header("Content-Type: $mime");
        readfile($row['image']);
        exit;
    }
    error_log("Fallback file does not exist at path: " . $row['image']);
}

error_log("No image found for ID: " . $id);
header("HTTP/1.0 404 Not Found");
?>
