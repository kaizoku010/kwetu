<?php
include './includes/db.php';

if (!isset($_GET['id'])) {
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT image FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['image']) {
    header("Content-Type: image/jpeg");
    echo $row['image'];
}
