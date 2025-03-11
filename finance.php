<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

session_start();

// Check if this is a direct access or included
$is_included = (strpos($_SERVER['SCRIPT_NAME'], 'profile.php') !== false);

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('You must be logged in to access finance.'); window.location.href='./user_auth/user_login.php';</script>");
}

$user_id = $_SESSION['user_id'];
$exchange_rate = 3800; // 1 USD = 3800 UGX

// ✅ Fetch Closed Auctions
$closed_auctions_stmt = $conn->prepare("SELECT id, company_title, image FROM auctions WHERE closing_date <= NOW()");
$closed_auctions_stmt->execute();
$closed_auctions_result = $closed_auctions_stmt->get_result();

$company_winning_bids = [];
$total_winning_amount_overall = 0;

// ✅ Loop Through Closed Auctions
while ($auction = $closed_auctions_result->fetch_assoc()) {
    $auction_id = $auction['id'];
    $company_title = $auction['company_title'];

    // ✅ Use 'assets/' folder for images, ensure image exists
    $company_image = (!empty($auction['image']) && file_exists('assets/' . basename($auction['image'])))
        ? 'assets/' . basename($auction['image'])
        : 'assets/default-company.jpg';

    // ✅ Fetch Winning Bids in Closed Auctions (grouped by company)
    $winning_bids_stmt = $conn->prepare("
        SELECT a.title, a.image, b.bid_amount 
        FROM bids b
        JOIN auction_items a ON b.lot_id = a.id
        WHERE a.auction_id = ? AND b.user_id = ? 
        AND b.bid_amount = (SELECT MAX(bid_amount) FROM bids WHERE lot_id = a.id)");
    
    $winning_bids_stmt->bind_param("ii", $auction_id, $user_id);
    $winning_bids_stmt->execute();
    $winning_bids_result = $winning_bids_stmt->get_result();
    
    $company_total = 0;
    while ($bid = $winning_bids_result->fetch_assoc()) {
        // ✅ Check if item image exists
        $image_path = (!empty($bid['image']) && file_exists('assets/' . basename($bid['image'])))
            ? 'assets/' . basename($bid['image'])
            : 'assets/default.jpg';

        $company_winning_bids[$company_title]['items'][] = [
            'title' => $bid['title'],
            'image' => $image_path,
            'amount' => $bid['bid_amount'],
            'amount_ugx' => $bid['bid_amount'] * $exchange_rate
        ];
        $company_total += $bid['bid_amount'] * $exchange_rate;
    }

    // ✅ Store total and payment details for each company
    if (!empty($company_winning_bids[$company_title]['items'])) {
        $company_winning_bids[$company_title]['total'] = $company_total;
        $company_winning_bids[$company_title]['image'] = $company_image;
        $total_winning_amount_overall += $company_total;
    }
}

// ✅ Add Test Items If No Winning Bids
if (empty($company_winning_bids)) {
    $example_companies = [
        "Tech Auctions Ltd." => [
            'items' => [['title' => 'Gaming Laptop', 'image' => 'assets/laptop.jpg', 'amount' => 1200]],
            'total' => 1200 * $exchange_rate,
            'image' => 'assets/auction1.jpg'
        ],
        "Luxury Auction House" => [
            'items' => [['title' => 'Luxury Watch', 'image' => 'assets/watch.jpg', 'amount' => 1500]],
            'total' => 1500 * $exchange_rate,
            'image' => 'assets/auction1.jpg'
        ],
        "Vehicle Auction Center" => [
            'items' => [['title' => 'Toyota Corolla 2020', 'image' => 'assets/car.jpg', 'amount' => 8500]],
            'total' => 8500 * $exchange_rate,
            'image' => 'assets/auction1.jpg'
        ]
    ];

    foreach ($example_companies as $company => $data) {
        $company_winning_bids[$company] = $data;
        $total_winning_amount_overall += $data['total'];
    }
}
?>

<?php if (!$is_included): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Summary</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding-top: 80px;
        }

        .finance-container {
            width: 90%;
            max-width: 1000px;
            margin: auto;
            margin-top: 10rem;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .company-block {
            margin-bottom: 40px;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            min-height: 450px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .company-title {
            font-size: 24px;
            font-weight: bold;
            color: #f78b00;
            text-decoration: underline;
            text-align: center;
        }

        .company-image {
            width: 250px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            display: block;
            margin: 10px auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th, .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f78b00;
            color: white;
            font-weight: bold;
        }

        .payment-btn-container {
            text-align: center;
            margin-top: auto;
        }

        .payment-btn {
            background-color: #f78b00;
            color: white;
            padding: 12px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease;
            display: inline-block;
        }
<?php include 'navbar2.php'; ?>
        .payment-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php endif; ?>

    <?php include 'navbar.php'; ?>
    <?php include 'navbar2.php'; ?>

    <div class="finance-container">
        <h2 class="finance-header">Finance Summary</h2>

        <?php foreach ($company_winning_bids as $company => $data): ?>
            <div class="company-block">
                <h3 class="company-title"><?php echo htmlspecialchars($company); ?></h3>
                <img src="<?php echo $data['image']; ?>" class="company-image">

                <table class="table">
                    <thead>
                        <tr><th>Item</th><th>Image</th><th>Winning Price (USD)</th><th>Winning Price (UGX)</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['items'] as $bid): ?>
                            <tr>
                                <td><?php echo $bid['title']; ?></td>
                                <td><img src="<?php echo $bid['image']; ?>" width="80"></td>
                                <td>$<?php echo number_format($bid['amount']); ?></td>
                                <td>UGX <?php echo number_format($bid['amount'] * $exchange_rate); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- <div class="payment-btn-container">
                    <a href="payment_info.php?company=<?php echo urlencode($company); ?>" class="payment-btn">View Payment Info</a>
                </div> -->
            </div>
        <?php endforeach; ?>
    </div>

<?php if (!$is_included): ?>
<?php include 'includes/footer.php'; ?>
</body>
</html>
<?php endif; ?>
