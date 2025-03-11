<?php
include './includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define admin status and exchange rate
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$exchange_rate = 3800;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .fixed-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .aucs-title {
    text-align: center !important;
    margin-top: 10rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5rem;
}

.mdx-card-sama > p{
 margin-bottom: 0px !important;
}

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'navbar2.php'; ?>

    <div class="container mt-5">
        <?php
        $auction_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($auction_id == 0) {
            die("<h2 class='text-center text-danger'>Invalid Auction ID</h2>");
        }

        $stmt = $conn->prepare("SELECT * FROM auction_items WHERE auction_id = ?");
        $stmt->bind_param("i", $auction_id);
        $stmt->execute();
        $auction_result = $stmt->get_result();

        if ($auction_result->num_rows > 0) {
            echo "<h2 class='text-center aucs-title'>Auction Items</h2>";
            echo "<div class='row'>";
            
            while ($item = $auction_result->fetch_assoc()) {
                $price_in_ugx = $item['price'] * $exchange_rate;
                
                echo '<div class="col-md-4">
                        <div class="card auction-item">
                            <div class="card-header">
                                <strong>Lot: ' . htmlspecialchars($item['lot_number'], ENT_QUOTES, 'UTF-8') . '</strong>
                            </div>
                            
                            <img src="' . htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') . '" class="card-img-top fixed-img" alt="Item Image">

                            <div class="card-body mdx-card-sama">
                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Title:</h6>
                                    <p>' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</p>
                                </div>

                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Bids:</h6>
                                    <p><span id="bids-count-' . $item['id'] . '">' . htmlspecialchars($item['bidders'], ENT_QUOTES, 'UTF-8') . '</span></p>
                                </div>

                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Current Price:</h6>
                                    <p class="price-display" 
                                       data-original-price="' . $price_in_ugx . '" 
                                       data-original-currency="UGX"
                                       id="current-price-' . $item['id'] . '">
                                        UGX ' . number_format($price_in_ugx) . '
                                    </p>
                                </div>

                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Condition:</h6>
                                    <p>' . nl2br(htmlspecialchars($item['condition'], ENT_QUOTES, 'UTF-8')) . '</p>
                                </div>

                                <div class="bg-light p-3 rounded" style="max-height: 6rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                    <h6 class="fw-bold">Description:</h6>
                                    <p>' . nl2br(htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8')) . '</p>
                                </div>

                                ' . ($is_admin ? '
                                <div class="mt-3">
                                    <a href="edit_item.php?id=' . $item['id'] . '" class="btn btn-warning w-100 mb-2">Edit</a>
                                    <a href="delete_item.php?id=' . $item['id'] . '" class="btn btn-danger w-100" onclick="return confirm(\'Are you sure you want to delete this item?\')">Delete</a>
                                </div>' : '') . '
                            </div>

                            <div class="card-footer">
                                <a href="lot_details.php?id=' . $item['id'] . '" class="btn btn-success w-100">View This Lot</a>
                            </div>
                        </div>
                    </div>';
            }
            echo "</div>";
        } else {
            echo "<h2 class='text-center text-danger'>No Items Found</h2>";
        }

        $stmt->close();
        ?>
    </div>

    <script>
        $(document).ready(function() {
            $(".place-bid-btn").click(function() {
                let itemId = $(this).data("item-id");
                let currency = $(".currency-selector[data-item-id='" + itemId + "']").val();
                let manualBid = $("#manual-bid-" + itemId).val();

                $.ajax({
                    url: "update_price.php",
                    type: "POST",
                    data: { item_id: itemId, currency: currency, manual_bid: manualBid },
                    success: function(response) {
                        let data = JSON.parse(response);
                        if (data.error) {
                            alert(data.error);
                        } else {
                            $("#current-price-" + itemId).text(data.currency + " " + data.price);
                            $("#bids-count-" + itemId).text(data.bids);
                        }
                    },
                    error: function() {
                        alert("Error updating bid.");
                    }
                });
            });
        });
    </script>
</body>
</html>
