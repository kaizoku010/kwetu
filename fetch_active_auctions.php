<?php
include './includes/db.php';

$current_time = date("Y-m-d H:i:s");

// ✅ Fetch Active Auctions (Opening Date has passed, and Closing Date is in the future)
$query = "SELECT id, company_title, opening_date, closing_date 
          FROM auctions 
          WHERE opening_date <= ? AND closing_date > ? 
          ORDER BY closing_date ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $current_time, $current_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>
                <a href='auction.php?id=" . $row['id'] . "' class='text-dark text-decoration-none'>
                    <strong>" . htmlspecialchars($row['company_title']) . "</strong>
                    <br>Closing: <span class='text-danger'>" . date("M d, Y H:i", strtotime($row['closing_date'])) . "</span>
                </a>
              </li>";
    }
} else {
    echo "<li class='list-group-item'>No active auctions found.</li>";
}

$stmt->close();
?>
