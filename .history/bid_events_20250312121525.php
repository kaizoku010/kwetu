<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Prevent buffering
if (ob_get_level() > 0) {
    ob_end_clean();
}

include './includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lot_id = isset($_GET['lot_id']) ? (int)$_GET['lot_id'] : 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Set reasonable timeout
set_time_limit(30);

while (true) {
    // Query current price and highest bid
    $query = "SELECT price, highest_bid, highest_bidder FROM auction_items WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $lot_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // Get user's bid if any
    $bid_query = "SELECT bid_amount FROM bids WHERE lot_id = ? AND user_id = ? ORDER BY bid_amount DESC LIMIT 1";
    $bid_stmt = $conn->prepare($bid_query);
    $bid_stmt->bind_param("ii", $lot_id, $user_id);
    $bid_stmt->execute();
    $bid_result = $bid_stmt->get_result();
    
    $user_bid = 0;
    if ($bid_result->num_rows > 0) {
        $user_bid_data = $bid_result->fetch_assoc();
        $user_bid = (float)$user_bid_data['bid_amount'];
    }
    
    $exchange_rate = 3800;
    
    $response = [
        'current_price' => (float)$data['price'] * $exchange_rate,
        'highest_bid' => (float)($data['highest_bid'] ?? 0) * $exchange_rate,
        'user_bid' => $user_bid * $exchange_rate,
        'is_winning' => ($data['highest_bidder'] == $user_id)
    ];
    
    echo "data: " . json_encode($response) . "\n\n";
    flush();
    
    // Poll every 2 seconds
    sleep(2);
}
