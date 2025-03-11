<?php
include './includes/db.php';

// Define exchange rates
$exchange_rates = [
    'UGX' => 1,
    'USD' => 1/3800,
    'EUR' => 1/4500,
    'GBP' => 1/5200
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 'UGX';
    $manual_bid = isset($_POST['manual_bid']) ? (float)$_POST['manual_bid'] : 0;

    if ($item_id > 0) {
        // Convert bid to UGX for storage
        $bid_in_ugx = $manual_bid / $exchange_rates[$currency];
        
        // Update price & increase bid count
        $update_query = "UPDATE auction_items SET price = $bid_in_ugx, bidders = bidders + 1 WHERE id = $item_id";
        
        if ($conn->query($update_query)) {
            // Return updated values in requested currency
            $response = [
                'success' => true,
                'price' => $manual_bid,
                'currency' => $currency,
                'bids' => $conn->query("SELECT bidders FROM auction_items WHERE id = $item_id")->fetch_assoc()['bidders']
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Failed to update bid']);
        }
    }
}

$conn->close();
?>
