<?php
include 'navbar.php';
include 'includes/db.php';

// Get category from URL
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$valid_categories = ['cars', 'furniture', 'electronics', 'real_estate', 'other'];

if (!in_array($category, $valid_categories)) {
    header("Location: index.php");
    exit();
}

// Initialize pagination variables
$items_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Get total items for pagination
$total_query = "SELECT COUNT(*) as total FROM auction_items WHERE category = '$category'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Get items for current page
$query = "SELECT ai.*, a.company_title, a.opening_date, a.closing_date 
          FROM auction_items ai 
          JOIN auctions a ON ai.auction_id = a.id 
          WHERE ai.category = '$category' 
          ORDER BY ai.created_at DESC 
          LIMIT $offset, $items_per_page";
$result = $conn->query($query);

// Format category name for display
$category_display = ucwords(str_replace('_', ' ', $category));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_display; ?> - Kwetu Auctions</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .category-header {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .pagination a {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination a:hover:not(.active) {
            background-color: #f8f9fa;
        }
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .item-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .item-details {
            padding: 15px;
        }
        .item-title {
            font-size: 1.1em;
            margin: 0 0 10px 0;
            color: #333;
        }
        .item-price {
            font-weight: bold;
            color: #007bff;
        }
        .no-items {
            text-align: center;
            padding: 50px;
            color: #666;
        }
    </style>
</head>
<body>
    <div style="margin-top: 10rem;" class="container">
        <div class="category-header">
            <h1><?php echo $category_display; ?></h1>
            <p>Browse our selection of <?php echo strtolower($category_display); ?> available for auction</p>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="items-grid">
                <?php while ($row = $result->fetch_assoc()): 
                    $price_in_ugx = $row['price'] * 3800; // Using the same exchange rate
                ?>
                    <div class="item-card">
                        <a href="lot_details.php?id=<?php echo $row['id']; ?>">
                            <img src="admin/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="item-image">
                            <div class="item-details">
                                <h3 class="item-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p class="item-price">UGX <?php echo number_format($price_in_ugx); ?></p>
                                <p>Lot #: <?php echo htmlspecialchars($row['lot_number']); ?></p>
                                <p>Closing: <?php echo date('M d, Y', strtotime($row['closing_date'])); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?category=<?php echo $category; ?>&page=<?php echo $i; ?>" 
                       class="<?php echo $page == $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <div class="no-items">
                <h2>No items found</h2>
                <p>There are currently no <?php echo strtolower($category_display); ?> available for auction.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
