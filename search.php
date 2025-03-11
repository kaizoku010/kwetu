
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Kwetu Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body class="search-page">
    <?php include 'navbar.php'; ?>

    <div class="container text-center">
        <h2 class="text-white mt-5">Search Anything</h2>
        <form action="search.php" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="query" class="form-control search-input" placeholder="Search for auctions, products, and more..." required>
                <button type="submit" class="btn btn-primary search-btn">Search</button>
            </div>
        </form>
    </div>

    <div class="container mt-4">
        <?php
        if (isset($_GET['query'])) {
            $query = $conn->real_escape_string($_GET['query']);
            
            echo "<h4 class='text-white'>Search Results for: <strong>" . htmlspecialchars($query) . "</strong></h4>";
            echo "<div class='row'>";

            // Search in auction_items Table
            $items_result = $conn->query("SELECT ai.*, a.company_title, a.location 
                                        FROM auction_items ai 
                                        JOIN auctions a ON ai.auction_id = a.id 
                                        WHERE ai.title LIKE '%$query%' 
                                        OR ai.description LIKE '%$query%' 
                                        OR ai.category LIKE '%$query%'");

            if ($items_result && $items_result->num_rows > 0) {
                echo "<h5 class='text-white'>Auction Items</h5>";
                while ($row = $items_result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Item Image">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                                    <p class="card-text">' . htmlspecialchars(substr($row['description'], 0, 100)) . '...</p>
                                    <p><strong>Price:</strong> $' . htmlspecialchars($row['price']) . '</p>
                                    <p><strong>Category:</strong> ' . htmlspecialchars($row['category']) . '</p>
                                    <p><strong>Company:</strong> ' . htmlspecialchars($row['company_title']) . '</p>
                                    <a href="lot_details.php?id=' . $row['id'] . '" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>';
                }
            }

            // Search in Auctions Table
            $auction_result = $conn->query("SELECT * FROM auctions 
                                          WHERE company_title LIKE '%$query%' 
                                          OR location LIKE '%$query%'");

            if ($auction_result && $auction_result->num_rows > 0) {
                echo "<h5 class='text-white mt-4'>Auctions</h5>";
                while ($row = $auction_result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Auction Image">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($row['company_title']) . '</h5>
                                    <p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>
                                    <p><strong>Opening Date:</strong> ' . htmlspecialchars($row['opening_date']) . '</p>
                                    <p><strong>Closing Date:</strong> ' . htmlspecialchars($row['closing_date']) . '</p>
                                    <a href="auction.php?id=' . $row['id'] . '" class="btn btn-primary">View Auction</a>
                                </div>
                            </div>
                        </div>';
                }
            }

            if ((!$items_result || $items_result->num_rows === 0) && 
                (!$auction_result || $auction_result->num_rows === 0)) {
                echo "<p class='text-white'>No results found.</p>";
            }

            echo "</div>";
        }
        ?>
    </div>

    <?php include 'navbar2.php'; ?>
</body>
</html>
