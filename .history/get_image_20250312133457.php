<?php
include './includes/db.php';

if (!isset($_GET['id'])) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$id = (int)$_GET['id'];

// Modified to get any image, not just primary
$stmt = $conn->prepare("SELECT image_path FROM item_images WHERE item_id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (file_exists($row['image_path'])) {
        $mime = mime_content_type($row['image_path']);
        header("Content-Type: $mime");
        readfile($row['image_path']);
        exit;
    }
}

// Fallback to auction_items table
$stmt = $conn->prepare("SELECT image FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && !empty($row['image'])) {
    if (file_exists($row['image'])) {
        $mime = mime_content_type($row['image']);
        header("Content-Type: $mime");
        readfile($row['image']);
        exit;
    }
}

header("HTTP/1.0 404 Not Found");
?>
