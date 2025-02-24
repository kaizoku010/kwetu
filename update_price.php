<?php
include './includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $currency = isset($_POST['currency']) ? $_POST['currency'] : 'USD';
    $manual_bid = isset($_POST['manual_bid']) ? (float)$_POST['manual_bid'] : 0;

    if ($item_id > 0) {
        // ✅ Fetch current price
        $result = $conn->query("SELECT price FROM auction_items WHERE id = $item_id");
        $row = $result->fetch_assoc();
        $current_price = (float)$row['price'];

        // ✅ Convert UGX to USD if necessary (1 USD = 3800 UGX)
        if ($currency == "UGX") {
            $manual_bid = $manual_bid / 3800;
        }

        // ✅ Ensure manual bid is greater than current price
        if ($manual_bid > $current_price) {
            // ✅ Update price & increase bid count
            $update_query = "UPDATE auction_items SET price = $manual_bid, bidders = bidders + 1 WHERE id = $item_id";
            if ($conn->query($update_query)) {
                // ✅ Fetch updated values
                $result = $conn->query("SELECT price, bidders FROM auction_items WHERE id = $item_id");
                $row = $result->fetch_assoc();
                
                echo json_encode([
                    "price" => number_format($row['price'], 2),
                    "bids" => $row['bidders'],
                    "currency" => $currency
                ]);
            } else {
                echo json_encode(["error" => "Failed to update"]);
            }
        } else {
            echo json_encode(["error" => "Bid must be higher than the current price"]);
        }
    }
}

$conn->close();
?>
