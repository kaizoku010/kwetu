<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Set execution time limit
set_time_limit(30);

// Clean all output buffers
while (ob_get_level()) {
    ob_end_clean();
}

include './includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get lot_id from query parameter
$lot_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'] ?? 0;

// Validate inputs
if (!$lot_id || !$user_id) {
    echo "event: error\n";
    echo "data: Invalid parameters\n\n";
    exit();
}

while (true) {
    // Fetch current bid data
    $stmt = $conn->prepare("SELECT a.price, b.bid_amount as highest_bid, b.user_id as highest_bidder 
                           FROM auction_items a 
                           LEFT JOIN (
                               SELECT lot_id, MAX(bid_amount) as bid_amount, user_id
                               FROM bids 
                               WHERE lot_id = ?
                               GROUP BY lot_id, user_id
                               ORDER BY bid_amount DESC
                               LIMIT 1
                           ) b ON a.id = b.lot_id
                           WHERE a.id = ?");
    
    $stmt->bind_param("ii", $lot_id, $lot_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    // Check if auction exists
    if (!$data) {
        echo "event: error\n";
        echo "data: Auction not found\n\n";
        exit();
    }
    
    // Get user's last bid
    $bid_stmt = $conn->prepare("SELECT bid_amount FROM bids WHERE lot_id = ? AND user_id = ? ORDER BY bid_time DESC LIMIT 1");
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
        'current_price' => (float)($data['price'] ?? 0) * $exchange_rate,
        'highest_bid' => (float)($data['highest_bid'] ?? 0) * $exchange_rate,
        'user_bid' => $user_bid * $exchange_rate,
        'is_winning' => ($data['highest_bidder'] ?? 0) == $user_id
    ];
    
    echo "data: " . json_encode($response) . "\n\n";
    flush();
    
    // Wait before next update
    sleep(1);
} 
