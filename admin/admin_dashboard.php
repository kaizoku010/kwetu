
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure admin is logged in
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
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- External CSS file -->
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">📊 Dashboard</a></li>
            <li><a href="auctions_admin.php">🏢 Manage Auction Companies</a></li>
            <li><a href="auction_items_admin.php">📋 Manage Company Listings</a></li>
            <li><a href="manage_users.php">👥 Manage Users</a></li>
            <li><a href="admin_sell_requests.php">📩 Sell Requests</a></li>
            <li><a href="admin_about_us.php">📜 Edit About Us Page</a></li>
            <li><a href="edit_careers.php">💼 Edit Careers Page</a></li>
            <li><a href="edit_services.php">🛠️ Edit Services Page</a></li>
            <li><a href="edit_contact.php">📞 Edit Contact Us Page</a></li>
            <li><a href="subscriptions.php">📩 Subscriptions</a></li>
            <li><a href="admin_logout.php" class="logout-btn">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <h1>Welcome, Admin</h1>
        <p>Select an option from the sidebar to manage the system.</p>
    </div>

</body>
</html>
