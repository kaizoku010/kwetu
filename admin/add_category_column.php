<?php
include '../includes/db.php';

// Create category column if it doesn't exist
$check_column = $conn->query("SHOW COLUMNS FROM auction_items LIKE 'category'");
if ($check_column->num_rows == 0) {
    $alter_query = "ALTER TABLE auction_items ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT 'other'";
    if ($conn->query($alter_query)) {
        echo "Successfully added category column";
    } else {
        echo "Failed to add column: " . $conn->error;
    }
} else {
    echo "Category column already exists";
}
?>