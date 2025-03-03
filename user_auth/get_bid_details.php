<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'Not authenticated']));
}

include '../includes/db.php';
$user_id = $_SESSION['user_id'];
$type = $_GET['type'] ?? '';
$exchange_rate = 3800;

switch($type) {
    case 'total':
        $query = "SELECT b.bid_time as bid_date, 
                         ai.title as item_title, 
                         b.bid_amount * ? as bid_amount,
                         ai.id as lot_id,
                         CASE 
                             WHEN b.bid_amount = (SELECT MAX(bid_amount) FROM bids WHERE lot_id = b.lot_id) THEN 'winning'
                             ELSE 'losing'
                         END as status
                  FROM bids b
                  JOIN auction_items ai ON b.lot_id = ai.id
                  WHERE b.user_id = ?
                  ORDER BY b.bid_time DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $exchange_rate, $user_id);
        break;

    case 'amount':
        $query = "SELECT b.bid_time as bid_date, 
                         ai.title as item_title, 
                         b.bid_amount * ? as bid_amount,
                         CASE 
                             WHEN b.bid_amount = (SELECT MAX(bid_amount) FROM bids WHERE lot_id = b.lot_id) THEN 'winning'
                             ELSE 'losing'
                         END as status
                  FROM bids b
                  JOIN auction_items ai ON b.lot_id = ai.id
                  WHERE b.user_id = ?
                  ORDER BY b.bid_amount DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $exchange_rate, $user_id);
        break;

    case 'won':
        $query = "SELECT b.bid_time as win_date, 
                         ai.title as item_title, 
                         b.bid_amount * ? as winning_bid,
                         ai.id as lot_id
                  FROM bids b
                  JOIN auction_items ai ON b.lot_id = ai.id
                  WHERE b.user_id = ?
                  AND b.bid_amount = (SELECT MAX(bid_amount) FROM bids WHERE lot_id = b.lot_id)
                  ORDER BY b.bid_time DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $exchange_rate, $user_id);
        break;

    default:
        die(json_encode(['error' => 'Invalid type']));
}

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);