
<?php
include './includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define admin status and exchange rate
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
$exchange_rate = 3800;

function getImageUrl($item) {
    // First check if image is a path to assets folder
    if (!empty($item['image']) && strpos($item['image'], 'assets/') === 0) {
        // It's a file path
        if (file_exists($item['image'])) {
            return $item['image'];
        }
    }
    
    // If not in assets or file doesn't exist, use the database image endpoint
    return 'get_image.php?id=' . $item['id'];
}

// Validate auction ID
$auction_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($auction_id === 0) {
    die("<h2 class='text-center text-danger'>Invalid Auction ID</h2>");
}

// First verify the auction exists
$auction_check = $conn->prepare("SELECT * FROM auctions WHERE id = ?");
if (!$auction_check) {
    die("Prepare failed: " . $conn->error);
}
$auction_check->bind_param("i", $auction_id);
$auction_check->execute();
$auction_result = $auction_check->get_result();

if ($auction_result->num_rows === 0) {
    die("<h2 class='text-center text-danger'>Auction not found</h2>");
}

// Now fetch the items
$stmt = $conn->prepare("SELECT * FROM auction_items WHERE auction_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $auction_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$auction_result = $stmt->get_result();
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'navbar2.php'; ?>

    <div class="container mt-5">
        <?php
        if ($auction_result->num_rows > 0) {
            echo "<h2 class='text-center aucs-title'>Auction Items</h2>";
            echo "<div class='row'>";
            
            while ($item = $auction_result->fetch_assoc()) {
                $price_in_ugx = $item['price'] * $exchange_rate;
                $image_url = getImageUrl($item);
                
                echo '<div class="col-md-4 mb-4">
                        <div class="card auction-item">
                            <div class="card-header">
                                <h5 class="card-title">' . htmlspecialchars($item['title']) . '</h5>
                            </div>
                            <img src="' . htmlspecialchars($image_url) . '" class="card-img-top fixed-img" alt="Item Image">
                            <div class="card-body">
                                <p class="card-text">' . htmlspecialchars($item['description']) . '</p>
                                <p class="card-text">
                                    <strong>Current Bid:</strong> UGX ' . number_format($price_in_ugx) . ' 
                                    (USD ' . number_format($item['price'], 2) . ')
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="lot_details.php?id=' . $item['id'] . '" class="btn w-100" style="background-color: #f78b00 !important; color: white;">View This Lot</a>
                            </div>
                        </div>
                    </div>';
            }
            echo "</div>";
        } else {
            echo "<h2 style='margin-top: 10rem;' class='text-center text-danger'>No Items Found in This Auction</h2>";
        }

        $stmt->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
