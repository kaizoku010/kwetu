<?php
include './includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Number of auctions per page
$offset = ($page - 1) * $limit;

// Get total count first
$countQuery = "SELECT COUNT(*) as total FROM auctions";
$totalResult = $conn->query($countQuery);
$totalCount = $totalResult->fetch_assoc()['total'];

$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$query = "SELECT * FROM auctions LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$auctions = [];
if ($result->num_rows > 0) {
    while ($auction = $result->fetch_assoc()) {
        $auctionHtml = '<div class="col-sm-12">
            <a href="auction.php?id=' . $auction['id'] . '" class="auction-card-link">
                <div class="auction-card d-flex flex-column flex-md-row align-items-stretch">
                    <div class="auction-images order-md-1" style="background-image: url(\'' . $auction['image'] . '\'); background-size: cover; background-position: center; min-height: 250px;">
                    </div>
                    <div class="auction-box flex-grow-1 text-start">
                        <h4 class="company-title fw-bold text-black">' . $auction['company_title'] . '</h4>
                        <p class="mdx"><strong class="dateTime">Opening Date |</strong> ' . $auction['opening_date'] . '</p>
                        <p class="mdx"><strong class="dateTime">Closing Date |</strong> <span class="text-danger">' . $auction['closing_date'] . '</span></p>
                        <p class="mdx"><strong>Location:</strong> <span class="text-success">' . $auction['location'] . '</span></p>
                        <div class="sperator"></div>
                        <div class="">
                            <p class="auc-desc">' . $auction['description'] . '</p>
                        </div>
                        <p><strong>Closing In:</strong> 
                        <span class="countdown-timer" data-closing="' . $auction['closing_date'] . '"></span></p>';

        if ($isAdmin) {
            $auctionHtml .= '<div class="mt-2">
                <a href="edit_auction.php?id=' . $auction['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_auction.php?id=' . $auction['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this auction?\');">Delete</a>
              </div>';
        }

        $auctionHtml .= '</div></div></a></div>';
        $auctions[] = $auctionHtml;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'auctions' => $auctions,
    'hasMore' => ($offset + $limit) < $totalCount,
    'totalCount' => $totalCount,
    'currentCount' => $offset + count($auctions)
]);
?>
