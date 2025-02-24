<?php
session_start(); // ✅ Start session
include './includes/db.php';

// ✅ Check if admin is logged in
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// ✅ Set Exchange Rate
$exchange_rate = 3800;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ Include jQuery -->
    
    <style>
        /* ✅ Ensures all images have a fixed size inside cards */
        .fixed-img {
            width: 100%; /* Ensures full width inside the card */
            height: 250px; /* Fixed height for uniformity */
            object-fit: cover; /* Crops & maintains aspect ratio */
        }
    </style>

    <script>
        function switchCurrency(itemId, priceInUsd) {
            let exchangeRate = 3800;
            let priceInUgx = priceInUsd * exchangeRate;

            if (document.getElementById("current-price-" + itemId).innerText.includes("UGX")) {
                document.getElementById("current-price-" + itemId).innerHTML = "$" + priceInUsd.toFixed(2);
                document.getElementById("currency-button-" + itemId).innerText = "Switch to UGX";
            } else {
                document.getElementById("current-price-" + itemId).innerHTML = "UGX " + priceInUgx.toLocaleString();
                document.getElementById("currency-button-" + itemId).innerText = "Switch to USD";
            }
        }
    </script>

</head>
<body>

<?php include 'navbar.php'; ?> <!-- ✅ Added navbar.php -->
    <?php include 'navbar2.php'; ?>

    <div class="container mt-5">
        <?php
        $auction_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($auction_id == 0) {
            die("<h2 class='text-center text-danger'>Invalid Auction ID</h2>");
        }
        

        // ✅ Fetch Auction Items securely using prepared statements
        $stmt = $conn->prepare("SELECT * FROM auction_items WHERE auction_id = ?");
        $stmt->bind_param("i", $auction_id);
        $stmt->execute();
        $auction_result = $stmt->get_result();

        if ($auction_result->num_rows > 0) {
            echo "<h2 class='text-center'>Auction Items</h2>";

            echo "<div class='row'>";
            while ($item = $auction_result->fetch_assoc()) {
                $price_in_ugx = $item['price'] * $exchange_rate; // Convert price to UGX by default

                echo '<div class="col-md-4">
                        <div class="card auction-item">
                            <div class="card-header">
                                <strong>Lot: ' . htmlspecialchars($item['lot_number'], ENT_QUOTES, 'UTF-8') . '</strong>
                            </div>
                            
                            <!-- ✅ Fixed Image Size -->
                            <img src="' . htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8') . '" class="card-img-top fixed-img" alt="Item Image">

                            <div class="card-body">
                                
                                <!-- ✅ Title Box -->
                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Title:</h6>
                                    <p>' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</p>
                                </div>

                                <!-- ✅ Bids Box -->
                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Bids:</h6>
                                    <p><span id="bids-count-' . $item['id'] . '">' . htmlspecialchars($item['bidders'], ENT_QUOTES, 'UTF-8') . '</span></p>
                                </div>

                                <!-- ✅ Current Price Box -->
                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Current Price:</h6>
                                    <p id="current-price-' . $item['id'] . '">UGX ' . number_format($price_in_ugx) . '</p>
                                    <button id="currency-button-' . $item['id'] . '" class="btn btn-sm btn-primary" onclick="switchCurrency(' . $item['id'] . ', ' . $item['price'] . ')">Switch to USD</button>
                                </div>

                                <!-- ✅ Condition Box -->
                                <div class="bg-light p-3 rounded mb-2">
                                    <h6 class="fw-bold">Condition:</h6>
                                    <p>' . nl2br(htmlspecialchars($item['condition'], ENT_QUOTES, 'UTF-8')) . '</p>
                                </div>

                                <!-- ✅ Description Box (Limited to 4 Lines) -->
                                <div class="bg-light p-3 rounded" style="max-height: 6rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical;">
                                    <h6 class="fw-bold">Description:</h6>
                                    <p>' . nl2br(htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8')) . '</p>
                                </div>

                                <!-- ✅ Admin Controls (Edit & Delete) -->
                                ' . ($is_admin ? '
                                <div class="mt-3">
                                    <a href="edit_item.php?id=' . $item['id'] . '" class="btn btn-warning w-100 mb-2">Edit</a>
                                    <a href="delete_item.php?id=' . $item['id'] . '" class="btn btn-danger w-100" onclick="return confirm(\'Are you sure you want to delete this item?\')">Delete</a>
                                </div>' : '') . '
                            </div>

                            <!-- ✅ Full-Width Green Button -->
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
