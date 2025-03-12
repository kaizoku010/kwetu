<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

// Test database connection
echo "<h3>Database Connection Test:</h3>";
if ($conn->ping()) {
    echo "Database connection is working<br>";
} else {
    echo "Database connection failed<br>";
}

// Test image paths
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 30; // Use 30 as default
echo "<h3>Testing for item ID: $item_id</h3>";

// Check main image
$stmt = $conn->prepare("SELECT image FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

echo "Main image path: " . ($item['image'] ?? 'Not found') . "<br>";
if (!empty($item['image'])) {
    if (strpos($item['image'], 'assets/') === 0) {
        echo "File exists in assets?: " . (file_exists($item['image']) ? 'Yes' : 'No') . "<br>";
    }
}

// Check additional images
$stmt = $conn->prepare("SELECT image_path FROM item_images WHERE item_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h3>Additional Images:</h3>";
while ($image = $result->fetch_assoc()) {
    echo "Path: " . $image['image_path'] . "<br>";
    if (strpos($image['image_path'], 'assets/') === 0) {
        echo "File exists?: " . (file_exists($image['image_path']) ? 'Yes' : 'No') . "<br>";
    }
}
?>