<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('You must be logged in to place a bid.'); window.location.href='user_login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// ✅ Validate Form Submission
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['lot_id']) || !isset($_POST['bid_amount'])) {
    die("<script>alert('Invalid request. Please try again.'); window.history.back();</script>");
}

$lot_id = (int)$_POST['lot_id'];
$bid_amount_ugx = (float)$_POST['bid_amount']; // ✅ User enters bid in UGX

// ✅ Set Exchange Rate
$exchange_rate = 3800;

// ✅ Convert User's Bid to USD before storing
$bid_amount = $bid_amount_ugx / $exchange_rate;

// ✅ Fetch Current Price, Min & Max Bid from Database
$stmt = $conn->prepare("SELECT price, min_bid, max_bid FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $lot_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("<script>alert('Lot not found.'); window.history.back();</script>");
}

$lot = $result->fetch_assoc();
$current_price_ugx = (float)$lot['price'] * $exchange_rate; // ✅ Convert DB price to UGX
$min_bid_increment_ugx = (float)$lot['min_bid'] * $exchange_rate;
$max_bid_increment_ugx = (float)$lot['max_bid'] * $exchange_rate;

// ✅ Calculate the Allowed Min & Max Bid in UGX
$min_allowed_bid_ugx = $current_price_ugx + $min_bid_increment_ugx;
$max_allowed_bid_ugx = $current_price_ugx + $max_bid_increment_ugx;

// ✅ Validate Bid Amount in UGX
if ($bid_amount_ugx < $min_allowed_bid_ugx) {
    die("<script>alert('Your bid must be at least UGX " . number_format($min_allowed_bid_ugx) . "!'); window.history.back();</script>");
}

if ($bid_amount_ugx > $max_allowed_bid_ugx) {
    die("<script>alert('Your bid cannot exceed UGX " . number_format($max_allowed_bid_ugx) . "!'); window.history.back();</script>");
}

// ✅ Insert Bid into `bids` Table (store bid in USD)
$bid_stmt = $conn->prepare("INSERT INTO bids (lot_id, user_id, bid_amount) VALUES (?, ?, ?)");
$bid_stmt->bind_param("iid", $lot_id, $user_id, $bid_amount);

if (!$bid_stmt->execute()) {
    die("<script>alert('Database Error: Unable to place bid.'); window.history.back();</script>");
}

// ✅ Update Current Price in `auction_items` (store in USD)
$update_stmt = $conn->prepare("UPDATE auction_items SET price = ? WHERE id = ?");
$update_stmt->bind_param("di", $bid_amount, $lot_id);
$update_stmt->execute();

// Redirect back to lot details page with success parameter
header("Location: lot_details.php?id=$lot_id&bid_success=1&amount=" . urlencode(number_format($bid_amount_ugx)));
exit();

// After successful bid placement, notify WebSocket server
$ch = curl_init('https://kwetuauctions.com/notify');
$bidData = [
    'lot_id' => $lot_id,
    'current_price_ugx' => $bid_amount_ugx,
    'highest_bid_ugx' => $bid_amount_ugx,
    'user_bid_value' => $bid_amount_ugx,
    'is_winning' => true
];
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bidData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Add timeout
curl_exec($ch);
curl_close($ch);
?>
