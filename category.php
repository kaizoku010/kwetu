<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include './includes/db.php';
$exchange_rate = 3800;

$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$valid_categories = ['cars', 'furniture', 'electronics', 'real_estate', 'other'];

if (!in_array($category, $valid_categories)) {
    header("Location: index.php");
    exit();
}

$items_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Simpler query first for debugging
$total_query = "SELECT COUNT(*) as total FROM auction_items WHERE category = '$category'";
$total_result = $conn->query($total_query);

if (!$total_result) {
    die("Query failed: " . $conn->error);
}

$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Simpler items query
$query = "SELECT * FROM auction_items WHERE category = '$category' LIMIT $offset, $items_per_page";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Format category name for display
$category_display = ucwords(str_replace('_', ' ', $category));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category_display); ?> - Kwetu Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h2 style="margin-top:6rem" class="text-center mb-4"><?php echo htmlspecialchars($category_display); ?> Auctions</h2>
        
        <div class="row">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($item = $result->fetch_assoc()) {
                    $price_in_ugx = $item['price'] * $exchange_rate;


                    echo '<div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="' 
                                . htmlspecialchars($item['image']) 
                                . '" class="card-img-top" alt="Item Image">
                                <div class="card-body" style="text-align:left;>
                                    <h5 class="card-title">' 
                                    . htmlspecialchars($item['title']) 
                                    . '</h5>
                                    <p class="card-text" style="text-align:left; ">' 
                                    . htmlspecialchars(substr($item['description'], 0, 100)) 
                                    . '...</p>
                                    <p class="price-display" 
                                       data-original-price="' . $price_in_ugx 
                                       . '" 
                                       data-original-currency="UGX"
                                       id="current-price-' . $item['id'] 
                                       . '">
                                        UGX ' . number_format($price_in_ugx) 
                                        . '
                                    </p>                      
                                    <p><strong>Company:</strong> ' 
                                    . htmlspecialchars($item['company_title']) 
                                    . '</p>
                                    <p><strong>Closing Date:</strong> ' 
                                    . htmlspecialchars($item['closing_date']) 
                                    . '</p>
                                    <a href="lot_details.php?id=' 
                                    . $item['id'] 
                                    . '" class="btn" style="background-color: #f78b00; color: white;">View Details</a>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<div class="col-12 text-center">
                        <p>No items found in this category.</p>
                      </div>';
            }
            ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?category=<?php echo urlencode($category); ?>&page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <?php include 'navbar2.php'; ?>
</body>
</html>
