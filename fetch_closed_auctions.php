<?php
include './includes/db.php';

$current_time = date("Y-m-d H:i:s");

// ✅ Fetch Closed Auctions (Closing Date has passed)
$query = "SELECT id, company_title, closing_date 
          FROM auctions 
          WHERE closing_date <= ? 
          ORDER BY closing_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $current_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>
                <a href='auction.php?id=" . $row['id'] . "' class='text-dark text-decoration-none'>
                    <strong>" . htmlspecialchars($row['company_title']) . "</strong>
                    <br>Closed on: <span class='text-danger'>" . date("M d, Y H:i", strtotime($row['closing_date'])) . "</span>
                </a>
              </li>";
    }
} else {
    echo "<li class='list-group-item'>No closed auctions found.</li>";
}

$stmt->close();
?>
