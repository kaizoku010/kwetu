<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

session_start();

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Check if an item ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid item ID.");
}

$item_id = (int)$_GET['id'];

// ✅ Fetch item details (To Delete Image)
$stmt = $conn->prepare("SELECT image FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Item not found.");
}

$item = $result->fetch_assoc();
$image_path = $item['image'];

// ✅ Delete item from database
$delete_stmt = $conn->prepare("DELETE FROM auction_items WHERE id = ?");
$delete_stmt->bind_param("i", $item_id);

if ($delete_stmt->execute()) {
    // ✅ Remove image file if it exists
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    echo "<script>
            alert('Item deleted successfully!');
            window.location.href = 'auction.php?id=1';
          </script>";
    exit();
} else {
    die("Database Error: " . $conn->error);
}
?>
