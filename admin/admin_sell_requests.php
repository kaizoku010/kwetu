<?php include 'navbar_admin.php'; ?>
<?php include 'admin_dashboard.php'; ?>
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

// ✅ Handle Request Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM auction_requests WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Request deleted successfully!'); window.location.href='admin_sell_requests.php';</script>";
    } else {
        echo "<script>alert('Error deleting request.');</script>";
    }
}

// ✅ Fetch Auction Requests
$result = $conn->query("SELECT * FROM auction_requests ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sell Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-delete {
            background-color: red;
            color: white;
        }
        .btn-delete:hover {
            background-color: darkred;
        }
        .btn-approve {
            background-color: green;
            color: white;
        }
        .btn-approve:hover {
            background-color: darkgreen;
        }
        .btn-view {
            background-color: #17a2b8;
            color: white;
        }
        .btn-view:hover {
            background-color: #138496;
        }
        .image-preview {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Sell Requests</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Items Sold</th>
                        <th>Auction Date</th>
                        <th>Inspection Date</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($request = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['id']); ?></td>
                            <td><?php echo htmlspecialchars($request['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['company_email']); ?></td>
                            <td><?php echo htmlspecialchars($request['company_phone']); ?></td>
                            <td><?php echo htmlspecialchars($request['items_sold']); ?></td>
                            <td><?php echo htmlspecialchars($request['auction_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['inspection_date']); ?></td>
                            <td>
                                <?php
                                $images = explode(',', $request['images']);
                                foreach ($images as $image) {
                                    echo '<img src="' . htmlspecialchars($image) . '" class="image-preview" alt="Auction Item">';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="?delete_id=<?php echo $request['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this request?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h4 class="text-danger">No auction requests found.</h4>
        <?php endif; ?>
    </div>

</body>
</html>
