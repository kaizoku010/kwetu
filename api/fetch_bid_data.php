<?php
header('Content-Type: application/json');
include './includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$response = [
    "current_price" => 0,
    "user_bid" => 0,
    "highest_bid" => 0,
    "min_bid" => 0,
    "max_bid" => 0,
    "is_winning" => false
];

// ✅ Validate Lot ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode($response);
    exit;
}

$lot_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'] ?? 0;
$exchange_rate = 3800;

// ✅ Fetch Lot Details
$stmt = $conn->prepare("SELECT a.price, a.min_bid, a.max_bid FROM auction_items a WHERE a.id = ?");
$stmt->bind_param("i", $lot_id);
$stmt->execute();
$lot_result = $stmt->get_result();

if ($lot_result->num_rows > 0) {
    $lot = $lot_result->fetch_assoc();
    $response["current_price"] = (float)$lot['price'] * $exchange_rate;
    $response["min_bid"] = $response["current_price"] + ((float)$lot['min_bid'] * $exchange_rate);
    $response["max_bid"] = $response["current_price"] + ((float)$lot['max_bid'] * $exchange_rate);
}

// ✅ Fetch User's Last Bid
$bid_stmt = $conn->prepare("SELECT bid_amount FROM bids WHERE lot_id = ? AND user_id = ? ORDER BY bid_time DESC LIMIT 1");
$bid_stmt->bind_param("ii", $lot_id, $user_id);
$bid_stmt->execute();
$bid_result = $bid_stmt->get_result();

if ($bid_result->num_rows > 0) {
    $user_bid_data = $bid_result->fetch_assoc();
    $response["user_bid"] = (float)$user_bid_data['bid_amount'] * $exchange_rate;
}

// ✅ Fetch Highest Bid for this Lot
$highest_bid_stmt = $conn->prepare("SELECT user_id, bid_amount FROM bids WHERE lot_id = ? ORDER BY bid_amount DESC LIMIT 1");
$highest_bid_stmt->bind_param("i", $lot_id);
$highest_bid_stmt->execute();
$highest_bid_result = $highest_bid_stmt->get_result();

if ($highest_bid_result->num_rows > 0) {
    $highest_bid = $highest_bid_result->fetch_assoc();
    $response["highest_bid"] = (float)$highest_bid['bid_amount'] * $exchange_rate;
    $response["is_winning"] = ($highest_bid['user_id'] == $user_id);
}

// ✅ Return JSON Response
echo json_encode($response);
?>
