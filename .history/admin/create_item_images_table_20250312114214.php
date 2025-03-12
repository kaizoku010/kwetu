<?php
include '../includes/db.php';

$sql = "CREATE TABLE IF NOT EXISTS item_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES auction_items(id) ON DELETE CASCADE
)";

if ($conn->query($sql)) {
    echo "Table 'item_images' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


// no wau out
?>