<?php
include './includes/db.php';

if (!isset($_GET['type']) || !isset($_GET['user_id'])) {
    die("Invalid request.");
}

$type = $_GET['type'];
$user_id = (int)$_GET['user_id'];

// ✅ Fetch Winning Bids
if ($type === "winning") {
    $query = "SELECT ai.lot_number, ai.title, b.bid_amount 
              FROM bids b 
              JOIN auction_items ai ON b.lot_id = ai.id 
              WHERE b.user_id = ? 
              AND b.bid_amount = (SELECT MAX(bid_amount) FROM bids WHERE lot_id = ai.id)";
} 
// ✅ Fetch Losing Bids (User placed a bid but is NOT the highest bidder)
else if ($type === "losing") {
    $query = "SELECT ai.lot_number, ai.title, b.bid_amount 
              FROM bids b 
              JOIN auction_items ai ON b.lot_id = ai.id 
              WHERE b.user_id = ? 
              AND b.bid_amount < (SELECT MAX(bid_amount) FROM bids WHERE lot_id = ai.id)";
} 
else {
    die("Invalid type.");
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>
                <strong>Lot " . htmlspecialchars($row['lot_number']) . ":</strong> " . htmlspecialchars($row['title']) . 
                " - UGX " . number_format($row['bid_amount'] * 3800) . "
              </li>";
    }
} else {
    echo "<li class='list-group-item'>No bids found.</li>";
}

$stmt->close();
?>
