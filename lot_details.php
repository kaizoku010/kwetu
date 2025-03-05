<?php include 'navbar.php'; ?> <!-- ✅ Added navbar.php -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('You must be logged in to view this lot.'); window.location.href='./user_auth/user_login.php';</script>");
}

$user_id = $_SESSION['user_id'];

// ✅ Validate Lot ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h2 class='text-center text-danger'>Invalid lot ID.</h2>");
}

$lot_id = (int)$_GET['id'];

// ✅ Fetch Lot Details
$stmt = $conn->prepare("SELECT a.*, b.closing_date FROM auction_items a 
                        JOIN auctions b ON a.auction_id = b.id WHERE a.id = ?");
$stmt->bind_param("i", $lot_id);
$stmt->execute();
$lot_result = $stmt->get_result();

if ($lot_result->num_rows == 0) {
    die("<h2 class='text-center text-danger'>Lot not found.</h2>");
}

$lot = $lot_result->fetch_assoc();
$closing_time = strtotime($lot['closing_date']);
$current_time = time();
$is_closed = ($closing_time <= $current_time); // ✅ Auction is closed if time has passed

//  Tryin Exchange Rate
$exchange_rate = 3800;

// ✅ Convert Prices to UGX
$current_price_ugx = (float)$lot['price'] * $exchange_rate;
$min_bid_increment_ugx = (float)$lot['min_bid'] * $exchange_rate;
$max_bid_increment_ugx = (float)$lot['max_bid'] * $exchange_rate;

// ✅ Calculate Allowed Min & Max Bid in UGX
$min_allowed_bid_ugx = $current_price_ugx + $min_bid_increment_ugx;
$max_allowed_bid_ugx = $current_price_ugx + $max_bid_increment_ugx;

// ✅ Fetch User's Last Bid
$bid_stmt = $conn->prepare("SELECT bid_amount FROM bids WHERE lot_id = ? AND user_id = ? ORDER BY bid_time DESC LIMIT 1");
$bid_stmt->bind_param("ii", $lot_id, $user_id);
$bid_stmt->execute();
$bid_result = $bid_stmt->get_result();

$user_bid_value = 0;
if ($bid_result->num_rows > 0) {
    $user_bid_data = $bid_result->fetch_assoc();
    $user_bid_value = (float)$user_bid_data['bid_amount'] * $exchange_rate;
}

// ✅ Fetch Highest Bid for this Lot
$highest_bid_stmt = $conn->prepare("SELECT user_id, bid_amount FROM bids WHERE lot_id = ? ORDER BY bid_amount DESC LIMIT 1");
$highest_bid_stmt->bind_param("i", $lot_id);
$highest_bid_stmt->execute();
$highest_bid_result = $highest_bid_stmt->get_result();

$is_winning = false;
$highest_bid_ugx = 0;

if ($highest_bid_result->num_rows > 0) {
    $highest_bid = $highest_bid_result->fetch_assoc();
    $highest_bid_ugx = (float)$highest_bid['bid_amount'] * $exchange_rate;
    $is_winning = ($highest_bid['user_id'] == $user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lot Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .fixed-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .bid-status {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        .bg-winning {
            background-color: blue !important;
            color: white;
            animation: flashing 1s infinite alternate;
        }
        .bg-losing {
            background-color: red !important;
            color: white;
            animation: flashing 1s infinite alternate;
        }
        .bg-no-bid {
            background-color: darkbrown !important;
            color: white;
        }
        @keyframes flashing {
            from { opacity: 1; }
            to { opacity: 0.5; }
        }

        /* .black-txt-area > p{
            color: red;
        } */
    </style>
</head>
<body>

    <?php include 'navbar2.php'; ?>

    <div class=" container mt-5">
        <h2 style="margin-top: 6rem; margin-bottom: 2rem;" class="text-center">Lot Details</h2>
        <div class="row">
            <!-- ✅ Left Side: Image & Description -->
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($lot['image']); ?>" class="fixed-img rounded mb-3" alt="Lot Image">

                <div class="bg-light p-3 rounded">
                    <h6 class="fw-bold">Description:</h6>
                    <p><?php echo nl2br(htmlspecialchars($lot['description'])); ?></p>
                </div>

                <div class="bg-light p-3 rounded mt-3">
                    <h6 class="fw-bold">Condition:</h6>
                    <p><?php echo nl2br(htmlspecialchars($lot['condition'])); ?></p>
                </div>
            </div>

            <!-- ✅ Right Side: Bidding Details -->
            <div class="col-md-6">
                <div class="bg-light p-3 rounded mb-2">
                    <h6 class="fw-bold">Current Price:</h6>
                    <p>UGX <span id="current-price"><?php echo number_format($current_price_ugx); ?></span></p>
                </div>

                <div class="bg-light p-3 rounded mb-2 black-txt-area">
                    <h6 class="fw-bold">Your Last Bid:</h6>
                    <p class="black-text"><?php echo ($user_bid_value > 0) ? "UGX " . number_format($user_bid_value) : "You haven't bided on this lot"; ?></p>
                </div>

                <div class="bg-light p-3 rounded mb-2">
                    <h6 class="fw-bold">Highest Bid:</h6>
                    <p>UGX <?php echo number_format($highest_bid_ugx); ?></p>
                </div>

                <div class="bg-light p-3 rounded mb-2">
                    <h6 class="fw-bold">Minimum Allowed Bid:</h6>
                    <p>UGX <?php echo number_format($min_allowed_bid_ugx); ?></p>
                </div>

                <div class="bg-light p-3 rounded mb-2">
                    <h6 class="fw-bold">Maximum Allowed Bid:</h6>
                    <p>UGX <?php echo number_format($max_allowed_bid_ugx); ?></p>
                </div>

                <!-- ✅ Winning or Losing Message -->
                <div class="p-3 rounded text-center mb-2 
                    <?php echo ($user_bid_value == 0) ? 'bg-no-bid' : ($is_winning ? 'bg-winning' : 'bg-losing'); ?>">
                    <h6><?php echo ($user_bid_value == 0) ? 'You haven\'t bided on this lot.' : ($is_winning ? 'You are winning!' : 'You are losing. Place a higher bid!'); ?></h6>
                </div>

                <!-- ✅ Display Proper Message if Auction is Closed -->
                <form action="place_bid.php" method="POST" class="mt-3">
                    <input type="hidden" name="lot_id" value="<?php echo $lot_id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Enter Your Bid (UGX)</label>
                        <input type="number"
                         id="bid_amount"
                         name="bid_amount" 
                         value="<?php echo $current_price_ugx;?>"
                         class="form-control"
                         step="<?php echo $min_allowed_bid_ugx;?>"
                         placeholder="Enter your bid value"
                         required <?php echo $is_closed ? 'disabled' : ''; ?>>
                    </div>

                    <button type="submit" class="btn btn-success w-100" <?php echo $is_closed ? 'disabled' : ''; ?>>
                        <?php echo $is_closed ? 'This auction has already closed' : 'Place Bid'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Format input value to show in thousands
        const bidInput = document.getElementById('bid_amount');
        bidInput.addEventListener('input', function(e) {
            const value = this.value;
            if (value) {
                const formattedValue = Math.floor(parseInt(value) / 1000);
                this.setAttribute('title', formattedValue + 'K UGX');
            }
        });

        // Format initial value
        if (bidInput.value) {
            const initialValue = Math.floor(parseInt(bidInput.value) / 1000);
            bidInput.setAttribute('title', initialValue + 'K UGX');
        }

    //lets use a websocket instead of polling
        const ws = new WebSocket('ws://kwetuauctions.com:8080');
        const lotId = <?php echo $lot_id; ?>;

        ws.onopen = function() {
            // Subscribe to updates for this lot
            ws.send(JSON.stringify({
                type: 'subscribe',
                lot_id: lotId
            }));
        };

        ws.onmessage = function(event) {
            const data = JSON.parse(event.data);
            
            // Update DOM elements
            $('#current-price').text(data.current_price_ugx.toLocaleString());
            $('#min-allowed-bid').text(data.min_allowed_bid_ugx.toLocaleString());
            $('#max-allowed-bid').text(data.max_allowed_bid_ugx.toLocaleString());
            $('#user-bid-value').text(data.user_bid_value > 0 ? 
                "UGX " + data.user_bid_value.toLocaleString() : 
                "You haven't bided on this lot"
            );
            $('#highest-bid').text(data.highest_bid_ugx.toLocaleString());

            // Update winning/losing status
            const bidStatus = $('#bid-status');
            if (data.user_bid_value == 0) {
                bidStatus.removeClass('bg-winning bg-losing')
                        .addClass('bg-no-bid')
                        .html("<h6>You haven't bided on this lot.</h6>");
            } else {
                bidStatus.removeClass('bg-no-bid')
                        .addClass(data.is_winning ? 'bg-winning' : 'bg-losing')
                        .html("<h6>" + (data.is_winning ? 'You are winning!' : 'You are losing. Place a higher bid!') + "</h6>");
            }
        };

        // Remove the existing interval
        // setInterval(fetchLotDetails, 5000); // Removed
    </script>
</body>
</html>
