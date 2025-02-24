<?php include 'navbar_admin.php'; ?>
<?php include 'admin_dashboard.php'; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Set Exchange Rate
$exchange_rate = 3800;

// ✅ Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $lot_number = mysqli_real_escape_string($conn, $_POST['lot_number']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $bidders = mysqli_real_escape_string($conn, $_POST['bidders']);
    $price = mysqli_real_escape_string($conn, $_POST['price']) / $exchange_rate; // Convert UGX to USD before storing
    $min_bid = mysqli_real_escape_string($conn, $_POST['min_bid']) / $exchange_rate;
    $max_bid = mysqli_real_escape_string($conn, $_POST['max_bid']) / $exchange_rate;
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // ✅ Retrieve auction ID using company name
    $auction_query = "SELECT id FROM auctions WHERE company_title = '$company_name' LIMIT 1";
    $auction_result = $conn->query($auction_query);

    if ($auction_result->num_rows == 0) {
        die("<script>alert('Error: Company does not exist!'); window.history.back();</script>");
    }

    $auction_row = $auction_result->fetch_assoc();
    $auction_id = $auction_row['id'];

    // ✅ Handle Image Upload
    $image_path = "";
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "assets/" . basename($image);

        if (!move_uploaded_file($image_tmp, $image_path)) {
            die("Error uploading image.");
        }
    }

    // ✅ Insert into Database
    $query = "INSERT INTO auction_items (auction_id, lot_number, title, bidders, price, min_bid, max_bid, `condition`, description, image) 
              VALUES ('$auction_id', '$lot_number', '$title', '$bidders', '$price', '$min_bid', '$max_bid', '$condition', '$description', '$image_path')";

    if ($conn->query($query)) {
        echo "<script>
                alert('Item Added Successfully!');
                window.location.href = 'auction_items_admin.php';
              </script>";
        exit();
    } else {
        die("Database Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Auction Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 1100px; /* ✅ Maximum width set */
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            text-align: left;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            margin-bottom: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .currency-switch {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Admin Panel - Add Auction Item</h2>

        <form action="auction_items_admin.php" method="POST" enctype="multipart/form-data">
            <div class="section">
                <h4>Auction Details</h4>
                <label>Company Name</label>
                <input type="text" name="company_name" required placeholder="Enter Company Name">

                <label>Lot Number</label>
                <input type="text" name="lot_number" required>

                <label>Item Title</label>
                <input type="text" name="title" required>

                <label>Number of Bidders</label>
                <input type="number" name="bidders" required>
            </div>

            <div class="section">
                <h4>Pricing & Bidding</h4>
                <label>Price (UGX)</label>
                <input type="number" name="price" id="price" required>

                <label>Minimum Bid (UGX)</label>
                <input type="number" name="min_bid" id="min_bid" required>

                <label>Maximum Bid (UGX)</label>
                <input type="number" name="max_bid" id="max_bid" required>
            </div>

            <div class="section">
                <label>Condition</label>
                <textarea name="condition" rows="2" required></textarea>

                <label>Description</label>
                <textarea name="description" rows="4" required></textarea>

                <label>Item Image</label>
                <input type="file" name="image">
            </div>

            <button type="submit" class="btn btn-success">Add Item</button>
        </form>
    </div>

</body>
</html>
