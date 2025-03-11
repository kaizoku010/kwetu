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

    // Start transaction
    $conn->begin_transaction();

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'delete_selected':
            if (empty($_POST['items']) || !is_array($_POST['items'])) {
                throw new Exception('No items selected');
            }

            // Convert all items to integers to prevent SQL injection
            $items = array_map('intval', $_POST['items']);
            $itemsList = implode(',', $items);

            // 1. Get affected auction IDs before deletion
            $stmt = $conn->prepare("SELECT DISTINCT auction_id FROM auction_items WHERE id IN ($itemsList)");
            $stmt->execute();
            $result = $stmt->get_result();
            $affectedAuctions = [];
            while ($row = $result->fetch_assoc()) {
                $affectedAuctions[] = $row['auction_id'];
            }

            // 2. Delete bids
            $conn->query("DELETE FROM bids WHERE item_id IN ($itemsList)");
            
            // 3. Delete item images
            $conn->query("DELETE FROM item_images WHERE item_id IN ($itemsList)");
            
            // 4. Get and delete physical image files
            $stmt = $conn->prepare("SELECT image FROM auction_items WHERE id IN ($itemsList)");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image'])) {
                    $fullPath = '../' . $row['image'];
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            // 5. Delete auction items
            $stmt = $conn->prepare("DELETE FROM auction_items WHERE id IN ($itemsList)");
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete items');
            }

            // 6. Delete auctions that have no items left
            if (!empty($affectedAuctions)) {
                $auctionsList = implode(',', $affectedAuctions);
                $conn->query("DELETE FROM auctions WHERE id IN ($auctionsList) 
                            AND NOT EXISTS (
                                SELECT 1 FROM auction_items 
                                WHERE auction_id = auctions.id
                            )");
            }

            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Items and empty auctions deleted successfully',
                'deleted_ids' => $items
            ]);
            break;

        case 'delete_all':
            // 1. Delete all bids
            $conn->query("DELETE FROM bids");
            
            // 2. Delete all physical image files
            $result = $conn->query("SELECT image FROM auction_items");
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image'])) {
                    $fullPath = '../' . $row['image'];
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            // 3. Delete all item images records
            $conn->query("DELETE FROM item_images");

            // 4. Delete all auction items
            $conn->query("DELETE FROM auction_items");

            // 5. Delete all auctions
            $conn->query("DELETE FROM auctions");

            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'All items and auctions deleted successfully'
            ]);
            break;

        default:
            throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close connection
$conn->close();
