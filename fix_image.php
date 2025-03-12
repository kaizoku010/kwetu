<?php
// Option 1: Update the database record to point to the correct location
$stmt = $conn->prepare("UPDATE auction_items SET image = ? WHERE id = 25");
$new_path = "assets/new_correct_image.jpg"; // Replace with actual path
$stmt->bind_param("s", $new_path);
$stmt->execute();

// Option 2: Add a new image to item_images table as primary
$stmt = $conn->prepare("INSERT INTO item_images (item_id, image_path, is_primary) VALUES (?, ?, TRUE)");
$image_path = "assets/new_image.jpg"; // Replace with actual path
$item_id = 25;
$stmt->bind_param("is", $item_id, $image_path);
$stmt->execute();