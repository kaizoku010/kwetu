<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include '../includes/db.php';

// Fetch Site Data from the Database
$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$active_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE closing_date >= NOW()")->fetch_assoc()['total'];
$sell_requests = $conn->query("SELECT COUNT(*) AS total FROM sell_requests")->fetch_assoc()['total'];
$closed_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE closing_date < NOW()")->fetch_assoc()['total'];
$upcoming_auctions = $conn->query("SELECT COUNT(*) AS total FROM auctions WHERE opening_date > NOW()")->fetch_assoc()['total'];
$active_users = $conn->query("SELECT COUNT(*) AS total FROM users WHERE last_active >= NOW() - INTERVAL 10 MINUTE")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar_admin.php'; ?>
    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="welcome-section">
            <h1>Welcome, Admin</h1>
            <p>Select an option from the sidebar to manage the system. This dashboard provides you with complete control over your auction platform.</p>
        </div>

        <div class="container">
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
    </div>

</body>
</html>
