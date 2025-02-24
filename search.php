
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

    <?php include 'navbar.php'; ?> <!-- Navbar Included -->

    <!-- Search Bar -->
    <div class="container text-center">
        <h2 class="text-white mt-5">Search Anything</h2>
        <form action="search.php" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="query" class="form-control search-input" placeholder="Search for auctions, products, and more..." required>
                <button type="submit" class="btn btn-primary search-btn">Search</button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    <div class="container mt-4">
        <?php
        if (isset($_GET['query'])) {
            $query = $conn->real_escape_string($_GET['query']);
            
            echo "<h4 class='text-white'>Search Results for: <strong>$query</strong></h4>";
            echo "<div class='row'>";

            // Search in Products Table
            $product_result = $conn->query("SELECT * FROM products WHERE name LIKE '%$query%' OR description LIKE '%$query%'");
            if ($product_result->num_rows > 0) {
                echo "<h5 class='text-white'>Products</h5>";
                while ($row = $product_result->fetch_assoc()) {
                    echo '<div class="col-md-4">
                            <div class="card">
                                <img src="' . $row['image'] . '" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row['name'] . '</h5>
                                    <p class="card-text">' . $row['description'] . '</p>
                                    <p><strong>Price:</strong> $' . $row['price'] . '</p>
                                    <a href="#" class="btn btn-primary">View Product</a>
                                </div>
                            </div>
                        </div>';
                }
            }

            // Search in Auctions Table
            $auction_result = $conn->query("SELECT * FROM auctions WHERE company_title LIKE '%$query%' OR location LIKE '%$query%'");
            if ($auction_result->num_rows > 0) {
                echo "<h5 class='text-white'>Auctions</h5>";
                while ($row = $auction_result->fetch_assoc()) {
                    echo '<div class="col-md-4">
                            <div class="card">
                                <img src="' . $row['image'] . '" class="card-img-top" alt="Auction Image">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row['company_title'] . '</h5>
                                    <p><strong>Location:</strong> ' . $row['location'] . '</p>
                                    <p><strong>Opening Date:</strong> ' . $row['opening_date'] . '</p>
                                    <p><strong>Closing Date:</strong> ' . $row['closing_date'] . '</p>
                                    <a href="#" class="btn btn-primary">View Auction</a>
                                </div>
                            </div>
                        </div>';
                }
            }

            echo "</div>"; // Close row div
        }
        ?>
    </div>

</body>
</html>
