<?php
include '../includes/db.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'delete_selected':
            if (empty($_POST['items']) || !is_array($_POST['items'])) {
                throw new Exception('No items selected');
            }

            // Convert all items to integers to prevent SQL injection
            $items = array_map('intval', $_POST['items']);
            $itemsList = implode(',', $items);

            // First, get all image paths
            $stmt = $conn->prepare("SELECT image FROM auction_items WHERE id IN ($itemsList)");
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image']) && file_exists('../' . $row['image'])) {
                    unlink('../' . $row['image']);
                }
            }

            // Delete additional images
            $stmt = $conn->prepare("DELETE FROM item_images WHERE item_id IN ($itemsList)");
            $stmt->execute();

            // Delete items
            $stmt = $conn->prepare("DELETE FROM auction_items WHERE id IN ($itemsList)");
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete items');
            }

            echo json_encode(['success' => true, 'message' => 'Items deleted successfully']);
            break;

        case 'delete_all':
            // First, get all image paths
            $result = $conn->query("SELECT image FROM auction_items");
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image']) && file_exists('../' . $row['image'])) {
                    unlink('../' . $row['image']);
                }
            }

            // Delete all additional images
            $conn->query("DELETE FROM item_images");

            // Delete all items
            if (!$conn->query("DELETE FROM auction_items")) {
                throw new Exception('Failed to delete all items');
            }

            echo json_encode(['success' => true, 'message' => 'All items deleted successfully']);
            break;

        default:
            throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}