<?php
session_start();
include 'includes/db.php'; // Ensure database connection

// ✅ Check if the user is an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied. Admins only.");
}

// ✅ Check if auction ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Auction ID is missing.");
}

$auction_id = intval($_GET['id']);

// ✅ Delete auction from database
$deleteQuery = $conn->prepare("DELETE FROM auctions WHERE id = ?");
$deleteQuery->bind_param("i", $auction_id);

if ($deleteQuery->execute()) {
    $message = "Auction has been successfully deleted.";
} else {
    $message = "Error deleting auction: " . $conn->error;
}

// ✅ Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Deleted</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            text-align: center;
            padding-top: 100px;
        }
        .message-box {
            max-width: 500px;
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
    <script>
        // Redirect to home page after 3 seconds
        setTimeout(function() {
            window.location.href = "index.php";
        }, 3000);
    </script>
</head>
<body>

<div class="message-box">
    <h2><?= $message ?></h2>
    <p>Redirecting to home page...</p>
</div>

</body>
</html>
