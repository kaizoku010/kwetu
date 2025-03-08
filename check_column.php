<?php
include './includes/db.php';

// Show all columns in the table
echo "All columns in auction_items table:<br>";
$columns = $conn->query("SHOW COLUMNS FROM auction_items");
while($row = $columns->fetch_assoc()) {
    echo $row['Field'] . "<br>";
}

// Try to add the category column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM auction_items LIKE 'category'");
if($result->num_rows == 0) {
    echo "<br>Adding category column...<br>";
    $alter_query = "ALTER TABLE auction_items ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT 'other'";
    if($conn->query($alter_query)) {
        echo "Successfully added category column";
    } else {
        echo "Failed to add column: " . $conn->error;
    }
}

// Show all categories currently in use
echo "<br><br>Current categories in use:<br>";
$categories = $conn->query("SELECT DISTINCT category FROM auction_items");
while($row = $categories->fetch_assoc()) {
    echo $row['category'] . "<br>";
}
?>
