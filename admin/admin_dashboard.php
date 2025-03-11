
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <style>
        /* Add these styles to properly position main content */
        body {
            margin-left: 250px; /* Match sidebar width */
        }
        
        .main-content {
            padding: 20px;
            width: calc(100% - 250px); /* Full width minus sidebar */
            margin-left: 250px;
        }

        /* Ensure sidebar stays fixed */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            
            <!-- Auction Management Group -->
            <div class="group-header">Auction Management</div>
            <li><a href="auctions_admin.php">🏢 Add Auction Company</a></li>
            <li><a href="manage_auctions.php">📋 Manage Auctions</a></li>
            <li><a href="manage_items.php">📦 Manage Items</a></li>
            <li><a href="auction_items_admin.php">📋 Add New Items</a></li>
            
            <!-- User Management -->
            <div class="group-header">User Management</div>
            <li><a href="manage_users.php">👥 Manage Users</a></li>
            <li><a href="admin_sell_requests.php">📩 Sell Requests</a></li>
            
            <!-- Content Management -->
            <div class="group-header">Content Management</div>
            <li><a href="admin_about_us.php">📜 Edit About Us</a></li>
            <li><a href="edit_careers.php">💼 Edit Careers</a></li>
            <li><a href="edit_services.php">🛠️ Edit Services</a></li>
            <li><a href="edit_contact.php">📞 Edit Contact</a></li>
            
            <!-- Communications -->
            <div class="group-header">Communications</div>
            <li><a href="subscriptions.php">📩 Subscriptions</a></li>
            
            <!-- Logout -->
            <li><a href="admin_logout.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <!-- <div class="main-content">
        <div class="welcome-section">
            <h1>Welcome, Admin</h1>
            <p>Select an option from the sidebar to manage the system. This dashboard provides you with complete control over your auction platform.</p>
        </div>
    </div> -->

</body>
</html>
