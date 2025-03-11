<?php
include './includes/db.php';

$current_time = date("Y-m-d H:i:s");

// ✅ Fetch Upcoming Auctions (Opening Date is in the Future)
$query = "SELECT id, company_title, opening_date 
          FROM auctions 
          WHERE opening_date > ? 
          ORDER BY opening_date ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $current_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item'>
                <a href='auction.php?id=" . $row['id'] . "' class='text-dark text-decoration-none'>
                    <strong>" . htmlspecialchars($row['company_title']) . "</strong>
                    <br>Opening on: <span class='text-primary'>" . date("M d, Y H:i", strtotime($row['opening_date'])) . "</span>
                </a>
              </li>";
    }
} else {
    echo "<li class='list-group-item'>No upcoming auctions found.</li>";
}

$stmt->close();
?>
