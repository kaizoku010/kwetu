<?php
include '../includes/db.php';

if (!empty($_FILES['image']['name'])) {
    $image_name = basename($_FILES['image']['name']);
    $target_dir = "../assets/";
    $target_file = $target_dir . $image_name;
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_path = "assets/" . $image_name;
        
        // Insert the item with image path
        $stmt = $conn->prepare("INSERT INTO auction_items (auction_id, title, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $auction_id, $title, $description, $image_path);
        
        if (!$stmt->execute()) {
            die("Error saving to database: " . $stmt->error);
        }
    }
}
