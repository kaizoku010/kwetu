<?php
// Define the root path
define('ROOT_PATH', dirname(dirname(__FILE__)));

// Include files using absolute paths
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/navbar.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session data
if (!isset($_SESSION['user_id'])) {
    die("Session expired or user not logged in. Please <a href='user_login.php'>login again</a>.");
}

// Add session debugging
echo "<!-- Debug Info: ";
echo "User ID: " . $_SESSION['user_id'];
echo " Session Status: " . session_status();
echo " -->";

    $user_id       = $_SESSION['user_id'];
    $exchange_rate = 3800; // Consistent with other files

    // Fetch user details - modified to handle missing phone column
    $user_stmt = $conn->prepare("SELECT username, email, 
        CASE WHEN EXISTS (
            SELECT * FROM information_schema.COLUMNS 
            WHERE TABLE_NAME = 'users' 
            AND COLUMN_NAME = 'phone'
        ) 
        THEN phone 
        ELSE NULL 
        END as phone 
        FROM users WHERE id = ?");
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user = $user_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Kwetu Auctions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }

        .bid-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.2s;
        }
        .bid-card:hover {
            transform: translateY(-5px);
        }
        .winning {
            border-left: 4px solid #28a745;
        }
        .losing {
            border-left: 4px solid #dc3545;
        }
        .ongoing {
            border-left: 4px solid #f78b00;
        }
        .nav-pills .nav-link.active {
            background-color: #f78b00;
        }
        .nav-pills .nav-link {
            color: #333;
        }

        .btn-primary{
            background-color: #f78b00 !important;
            border-color: #f78b00 !important;
        /* width: 30%; */
        }

        .btn-primary:hover{
            background-color:rgb(206, 123, 14) !important;
            border-color:rgb(206, 123, 14) !important;
        }

          .mdx-row{
                margin-top: 4rem;
            }



.alert-dismissible {
  padding-right: 3rem;
  margin-top: 6rem !important;
}



        @media (max-width: 900px) {

#finance-tab{
        font-size: .8rem !important;
    
}

            .home-main{
            }

            #ongoing-tab{
                font-size: .8rem !important;

            }

            #lost-tab{
                font-size: .8rem !important;
            }

            #winning-tab{
                font-size: .8rem !important;
            }

            .home-text{
                font-size: small;
                margin-bottom: .1rem;
            }


            .kaizoku-small{
                font-size: small;
            }

            .profile-header h1 {
                font-size: 1rem;
            }
            .bid-card {
                margin: 0.5rem;
            }


            .mdx-row{
                margin-top: 6rem;
            }

            .mdx-magic{
                display: none;
            }


            .profile-header {
            background: #f78b00;
            color: white;
            display: none;
            padding: 2rem 0;
            /* margin-bottom: 2rem; */
            margin-top: 4rem;

        }
        }
    </style>
</head>
<body>
    <!-- Modals for detailed information -->
    <div class="modal fade" id="totalBidsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Your Bid History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="d-md-table-cell">Item</th>  <!-- Hidden on mobile -->
                                    <th>Amount</th>
                                    <th class="d-md-table-cell">Status</th>  <!-- Hidden on mobile -->
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="totalBidsDetails">
                                <!-- Will be populated by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="totalAmountModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Total Amount Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="totalAmountDetails">
                                <!-- Will be populated by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wonItemsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Items Won</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date Won</th>
                                    <th>Item</th>
                                    <th>Amount<t</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="wonItemsDetails">
                                <!-- Will be populated by AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../navbar.php'; ?>

    <div class="profile-header">
        <div class="container">
            <h1>Welcome,                         <?php echo htmlspecialchars($user['username']); ?></h1>
            <p>Member since:                             <?php echo date('F Y', strtotime($user['registration_date'])); ?></p>
        </div>
    </div>

    <div class="container mb-5">
        <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Profile updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add Bid Statistics Section -->
        <div class="mdx-row row mb-4">
            <div class="col-md-12">
                <div class="card" style="margin-top: 2rem;">
                    <div class="card-body">
                        <h5 class="card-title kaizoku-small">Your Bidding Statistics</h5>
                        <div class="row text-center">
                            <?php
                                // Get total bids placed
                                $total_bids_query = "SELECT COUNT(*) as total FROM bids WHERE user_id = ?";
                                $stmt             = $conn->prepare($total_bids_query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $total_bids = $stmt->get_result()->fetch_assoc()['total'];

                                $total_amount_query = "SELECT SUM(bid_amount) as total FROM bids WHERE user_id = ?";
                                $stmt               = $conn->prepare($total_amount_query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $total_amount = $stmt->get_result()->fetch_assoc()['total'] * $exchange_rate;

                                // Get number of items won
                                $won_items_query = "SELECT COUNT(DISTINCT b1.lot_id) as total
                                              FROM bids b1
                                              WHERE b1.user_id = ?
                                              AND b1.bid_amount = (
                                                  SELECT MAX(bid_amount)
                                                  FROM bids b2
                                                  WHERE b2.lot_id = b1.lot_id
                                              )";
                                $stmt = $conn->prepare($won_items_query);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $won_items = $stmt->get_result()->fetch_assoc()['total'];
                            ?>

                            <div class="col-md-3">
                                <div class="p-3 border rounded bg-light cursor-pointer" data-bs-toggle="modal" data-bs-target="#totalBidsModal" onclick="loadTotalBidsDetails()">
                                    <h3 class="text-primary kaizoku-small"><?php echo number_format($total_bids); ?></h3>
                                    <p class="mb-0 kaizoku-small">Total Bids Placed</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 border rounded bg-light cursor-pointer" data-bs-toggle="modal" data-bs-target="#totalAmountModal" onclick="loadTotalAmountDetails()">
                                    <h3 class="kaizoku-small text-success">UGX                                                                               <?php echo number_format($total_amount); ?></h3>
                                    <p class="mb-0 kaizoku-small">Total Amount Bid</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 border rounded bg-light cursor-pointer" data-bs-toggle="modal" data-bs-target="#wonItemsModal" onclick="loadWonItemsDetails()">
                                    <h3 class="text-warning kaizoku-small"><?php echo number_format($won_items); ?></h3>
                                    <p class="mb-0 kaizoku-small">Items Won</p>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="p-3 border rounded bg-light">
                                    <h3 class="text-info kaizoku-small"><?php echo $total_bids > 0 ? number_format(($won_items / $total_bids) * 100, 1) : '0'; ?>%</h3>
                                    <p class="mb-0 kaizoku-small">Win Rate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4 mdx-magic">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile Info</h5>
                        <p class="card-text">
                            <strong>Username:</strong>                                                       <?php echo htmlspecialchars($user['username']); ?><br>
                            <strong>Email:</strong>                                                    <?php echo htmlspecialchars($user['email']); ?><br>
                            <strong>Phone:</strong>                                                    <?php echo ! empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Not provided'; ?>
                        </p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Modal -->
            <div class="modal fade" id="editProfileModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="update_profile.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+256...">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password (leave blank to keep current)</label>
                                    <input type="password" class="form-control" name="new_password">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <ul class="nav nav-pills mb-4" id="bidTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="ongoing-tab" data-bs-toggle="pill" href="#ongoing" role="tab">Ongoing Bids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="winning-tab" data-bs-toggle="pill" href="#winning" role="tab">Winning Bids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lost-tab" data-bs-toggle="pill" href="#lost" role="tab">Lost Bids</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="finance-tab" data-bs-toggle="pill" href="#finance" role="tab">Finance</a>
                    </li>
                </ul>

                <div class="tab-content" id="bidTabContent">
                    <!-- Ongoing Bids -->
                    <div class="tab-pane fade show active" id="ongoing" role="tabpanel">
                        <?php
                            $ongoing_query = "SELECT ai.*, b.bid_amount, a.closing_date
                                        FROM bids b
                                        JOIN auction_items ai ON b.lot_id = ai.id
                                        JOIN auctions a ON ai.auction_id = a.id
                                        WHERE b.user_id = ? AND a.closing_date > NOW()
                                        ORDER BY b.bid_time DESC";
                            $stmt = $conn->prepare($ongoing_query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $ongoing_bids = $stmt->get_result();

                            while ($bid = $ongoing_bids->fetch_assoc()) {
                                echo '<div class="bid-card ongoing p-3">
                                    <h5 class="home-main">' . htmlspecialchars($bid['title']) . '</h5>
                                    <p class="home-text">Your Bid: UGX ' . number_format($bid['bid_amount'] * $exchange_rate) . '</p>
                                    <p class="home-text">Closes: ' . date('M d, Y H:i', strtotime($bid['closing_date'])) . '</p>
                                    <a href="../lot_details.php?id=' . $bid['id'] . '" class="btn btn-primary btn-sm">View Lot</a>
                                  </div>';
                            }
                            if ($ongoing_bids->num_rows === 0) {
                                echo '<p class="text-muted">No ongoing bids found.</p>';
                            }
                        ?>
                    </div>

                    <!-- Winning Bids -->
                    <div class="tab-pane fade" id="winning" role="tabpanel">
                        <?php
                            $winning_query = "SELECT ai.*, b.bid_amount
                                        FROM bids b
                                        JOIN auction_items ai ON b.lot_id = ai.id
                                        WHERE b.user_id = ?
                                        AND b.bid_amount = (
                                            SELECT MAX(bid_amount)
                                            FROM bids
                                            WHERE lot_id = ai.id
                                        )";
                            $stmt = $conn->prepare($winning_query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $winning_bids = $stmt->get_result();

                            while ($bid = $winning_bids->fetch_assoc()) {
                                echo '<div class="bid-card winning p-3">
                                    <h5>' . htmlspecialchars($bid['title']) . '</h5>
                                    <p class="home-text">Winning Bid: UGX ' . number_format($bid['bid_amount'] * $exchange_rate) . '</p>
                                    <a href="../lot_details.php?id=' . $bid['id'] . '" class="btn btn-success btn-sm">View Lot</a>
                                  </div>';
                            }
                            if ($winning_bids->num_rows === 0) {
                                echo '<p class="text-muted">No winning bids found.</p>';
                            }
                        ?>
                    </div>

                    <!-- Lost Bids -->
                    <div class="tab-pane fade" id="lost" role="tabpanel">
                        <?php
                            $lost_query = "SELECT ai.*, b.bid_amount,
                                     (SELECT MAX(bid_amount) FROM bids WHERE lot_id = ai.id) as highest_bid
                                     FROM bids b
                                     JOIN auction_items ai ON b.lot_id = ai.id
                                     WHERE b.user_id = ?
                                     AND b.bid_amount < (
                                         SELECT MAX(bid_amount)
                                         FROM bids
                                         WHERE lot_id = ai.id
                                     )";
                            $stmt = $conn->prepare($lost_query);
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $lost_bids = $stmt->get_result();

                            while ($bid = $lost_bids->fetch_assoc()) {
                                echo '<div class="bid-card losing p-3">
                                    <h5>' . htmlspecialchars($bid['title']) . '</h5>
                                    <p class="home-text">Your Bid: UGX ' . number_format($bid['bid_amount'] * $exchange_rate) . '</p>
                                    <p class="home-text">Highest Bid: UGX ' . number_format($bid['highest_bid'] * $exchange_rate) . '</p>
                                    <a href="../lot_details.php?id=' . $bid['id'] . '" class="btn btn-danger btn-sm">View Lot</a>
                                  </div>';
                            }
                            if ($lost_bids->num_rows === 0) {
                                echo '<p class="text-muted">No lost bids found.</p>';
                            }
                        ?>
                    </div>

                    <!-- Finance Tab -->
                    <div class="tab-pane fade" id="finance" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Items Won</th>
                                        <th>Total Amount (USD)</th>
                                        <th>Total Amount (UGX)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Get closed auctions with winning bids
                                        $closed_auctions_stmt = $conn->prepare("
                                        SELECT DISTINCT
                                            a.company_title,
                                            COUNT(DISTINCT ai.id) as items_won,
                                            SUM(b.bid_amount) as total_amount
                                        FROM auctions a
                                        JOIN auction_items ai ON a.id = ai.auction_id
                                        JOIN bids b ON ai.id = b.lot_id
                                        WHERE a.closing_date <= NOW()
                                        AND b.user_id = ?
                                        AND b.bid_amount = (
                                            SELECT MAX(bid_amount)
                                            FROM bids
                                            WHERE lot_id = ai.id
                                        )
                                        GROUP BY a.company_title
                                    ");

                                        $closed_auctions_stmt->bind_param("i", $user_id);
                                        $closed_auctions_stmt->execute();
                                        $result = $closed_auctions_stmt->get_result();

                                        if ($result->num_rows === 0) {
                                            // Add example data if no winning bids found
                                            $example_companies = [
                                                "Tech Auctions Ltd."     => [
                                                    'items_won'    => 1,
                                                    'total_amount' => 1200,
                                                ],
                                                "Luxury Auction House"   => [
                                                    'items_won'    => 1,
                                                    'total_amount' => 1500,
                                                ],
                                                "Vehicle Auction Center" => [
                                                    'items_won'    => 1,
                                                    'total_amount' => 8500,
                                                ],
                                            ];

                                            foreach ($example_companies as $company => $data) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($company) . '</td>';
                                                echo '<td>' . $data['items_won'] . '</td>';
                                                echo '<td>$' . number_format($data['total_amount']) . '</td>';
                                                echo '<td>UGX ' . number_format($data['total_amount'] * $exchange_rate) . '</td>';
                                                echo '<td><a href="../finance.php?company=' . urlencode($company) . '" class="btn btn-primary btn-sm">View Details</a></td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($row['company_title']) . '</td>';
                                                echo '<td>' . $row['items_won'] . '</td>';
                                                echo '<td>$' . number_format($row['total_amount']) . '</td>';
                                                echo '<td>UGX ' . number_format($row['total_amount'] * $exchange_rate) . '</td>';
                                                echo '<td><a href="../finance.php?company=' . urlencode($row['company_title']) . '" class="btn btn-primary btn-sm">View Details</a></td>';
                                                echo '</tr>';
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
function loadTotalBidsDetails() {
    fetch('get_bid_details.php?type=total')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(bid => {
                html += `
                    <tr>
                        <td>${bid.bid_date}</td>
                        <td>${bid.item_title}</td>
                        <td>${new Intl.NumberFormat().format(bid.bid_amount)}</td>
                        <td>
                            <span class="badge ${bid.status === 'winning' ? 'bg-success' :
                                              bid.status === 'losing' ? 'bg-danger' :
                                              'bg-warning'}">${bid.status}</span>
                        </td>

                    </tr>`;
            });
            document.getElementById('totalBidsDetails').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

function loadTotalAmountDetails() {
    fetch('get_bid_details.php?type=amount')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(bid => {
                html += `
                    <tr>
                        <td>${bid.bid_date}</td>
                        <td>${bid.item_title}</td>
                        <td>${new Intl.NumberFormat().format(bid.bid_amount)}</td>
                        <td>
                            <span class="badge ${bid.status === 'winning' ? 'bg-success' :
                                              bid.status === 'losing' ? 'bg-danger' :
                                              'bg-warning'}">${bid.status}</span>
                        </td>
                    </tr>`;
            });
            document.getElementById('totalAmountDetails').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}

function loadWonItemsDetails() {
    fetch('get_bid_details.php?type=won')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(item => {
                html += `
                    <tr>
                        <td>${item.win_date}</td>
                        <td>${item.item_title}</td>
                        <td>${new Intl.NumberFormat().format(item.winning_bid)}</td>
                        <td>
                            <a href="../lot_details.php?id=${item.lot_id}" class="btn btn-sm btn-primary">View Lot</a>
                        </td>
                    </tr>`;
            });
            document.getElementById('wonItemsDetails').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
}
</script>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .cursor-pointer:hover {
        background-color: #f8f9fa;
    }
    .badge {
        padding: 0.5em 1em;
    }
    .modal-dialog {
        max-width: 800px;
    }
    .table th {
        background-color: #f78b00;
        color: white;
    }
</style>
<?php
    // Get the current page filename
    $current_page = basename($_SERVER['PHP_SELF']);
    
    // Array of pages where footer should not appear
    $no_footer_pages = ['index.php', 'user_login.php', 'user_registration.php'];
    
    // Include footer if not in no_footer_pages array
    if (!in_array($current_page, $no_footer_pages)) {
        include '../includes/footer.php';
    }
    ?>
</body>
</html>
