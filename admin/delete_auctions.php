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
            if (empty($_POST['auctions']) || !is_array($_POST['auctions'])) {
                throw new Exception('No auctions selected');
            }

            // Convert all auctions to integers to prevent SQL injection
            $auctions = array_map('intval', $_POST['auctions']);
            $auctionsList = implode(',', $auctions);

            // 1. Get all item IDs associated with these auctions
            $stmt = $conn->prepare("SELECT id, image FROM auction_items WHERE auction_id IN ($auctionsList)");
            $stmt->execute();
            $result = $stmt->get_result();
            $itemIds = [];
            
            // Delete physical image files
            while ($row = $result->fetch_assoc()) {
                $itemIds[] = $row['id'];
                if (!empty($row['image'])) {
                    $fullPath = '../' . $row['image'];
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            if (!empty($itemIds)) {
                $itemsList = implode(',', $itemIds);
                
                // 2. Delete bids for these items
                $conn->query("DELETE FROM bids WHERE item_id IN ($itemsList)");
                
                // 3. Delete item images records
                $conn->query("DELETE FROM item_images WHERE item_id IN ($itemsList)");
                
                // 4. Delete auction items
                $conn->query("DELETE FROM auction_items WHERE id IN ($itemsList)");
            }

            // 5. Delete auction images
            $stmt = $conn->prepare("SELECT image, second_image FROM auctions WHERE id IN ($auctionsList)");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                foreach (['image', 'second_image'] as $imageField) {
                    if (!empty($row[$imageField])) {
                        $fullPath = '../' . $row[$imageField];
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
            }

            // 6. Delete auctions
            $stmt = $conn->prepare("DELETE FROM auctions WHERE id IN ($auctionsList)");
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete auctions');
            }

            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Auctions and associated items deleted successfully'
            ]);
            break;

        case 'delete_all':
            // 1. Delete all bids
            $conn->query("DELETE FROM bids");
            
            // 2. Delete all item images (physical files)
            $result = $conn->query("SELECT image FROM auction_items");
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['image'])) {
                    $fullPath = '../' . $row['image'];
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }
            }

            // 3. Delete auction images (physical files)
            $result = $conn->query("SELECT image, second_image FROM auctions");
            while ($row = $result->fetch_assoc()) {
                foreach (['image', 'second_image'] as $imageField) {
                    if (!empty($row[$imageField])) {
                        $fullPath = '../' . $row[$imageField];
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                }
            }

            // 4. Delete all records
            $conn->query("DELETE FROM item_images");
            $conn->query("DELETE FROM auction_items");
            $conn->query("DELETE FROM auctions");

            $conn->commit();
            echo json_encode([
                'success' => true,
                'message' => 'All auctions and items deleted successfully'
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