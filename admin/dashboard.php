<?php
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure Admin is Logged In
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Fetch Site Data from the Database

// Total Registered Users
$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Total Active Auctions (where closing_date is in the future)
$active_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE closing_date >= NOW()")->fetch_assoc()['total'];

// Total Sell Requests
$sell_requests = $conn->query("SELECT COUNT(*) AS total FROM sell_requests")->fetch_assoc()['total'];

// Total Closed Auctions (where closing_date is in the past)
$closed_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE closing_date < NOW()")->fetch_assoc()['total'];

// Total Upcoming Auctions (where opening_date is in the future)
$upcoming_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE opening_date > NOW()")->fetch_assoc()['total'];

// Currently Active Users (assume last activity is tracked in a 'last_active' column)
$active_users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE last_active >= NOW() - INTERVAL 10 MINUTE")->fetch_assoc()['total'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ✅ Dashboard Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding-top: 80px; /* ✅ Push content below navbar */
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .dashboard-header {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* ✅ Box Styles */
        .dashboard-box {
            background: #343a40; /* Deep Gray */
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
        }

        .dashboard-box:hover {
            transform: scale(1.05);
        }

        .dashboard-title {
            font-size: 18px;
            font-weight: bold;
        }

        .dashboard-number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* ✅ Responsive Grid */
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .col {
            flex: 1 1 calc(33.333% - 20px);
            min-width: 250px;
        }
    </style>
</head>
<body>

    <!-- ✅ Include Navbar -->
    <?php include 'navbar_admin.php'; ?>
    <?php include 'admin_dashboard.php'; ?>

    <div class="container">
        <h2 class="dashboard-header">Admin Dashboard</h2>

        <div class="row">
            <!-- Registered Users -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Registered Users</div>
                    <div class="dashboard-number"><?php echo $user_count; ?></div>
                </div>
            </div>

            <!-- Active Auctions -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Active Auctions</div>
                    <div class="dashboard-number"><?php echo $active_auctions; ?></div>
                </div>
            </div>

            <!-- Sell Requests -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Sell Requests</div>
                    <div class="dashboard-number"><?php echo $sell_requests; ?></div>
                </div>
            </div>

            <!-- Closed Auctions -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Closed Auctions</div>
                    <div class="dashboard-number"><?php echo $closed_auctions; ?></div>
                </div>
            </div>

            <!-- Upcoming Auctions -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Upcoming Auctions</div>
                    <div class="dashboard-number"><?php echo $upcoming_auctions; ?></div>
                </div>
            </div>

            <!-- Currently Active Users -->
            <div class="col">
                <div class="dashboard-box">
                    <div class="dashboard-title">Active Users</div>
                    <div class="dashboard-number"><?php echo $active_users; ?></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
