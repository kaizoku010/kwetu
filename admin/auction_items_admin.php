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
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $starting_date = mysqli_real_escape_string($conn, $_POST['starting_date']);
    $starting_time = mysqli_real_escape_string($conn, $_POST['starting_time']);
    $closing_date = mysqli_real_escape_string($conn, $_POST['closing_date']);
    $closing_time = mysqli_real_escape_string($conn, $_POST['closing_time']);

    // Combine date and time
    $starting_datetime = $starting_date . ' ' . $starting_time;
    $closing_datetime = $closing_date . ' ' . $closing_time;

    // ✅ Retrieve auction ID using company name
    $auction_query = "SELECT id FROM auctions WHERE company_title = '$company_name' LIMIT 1";
    $auction_result = $conn->query($auction_query);

    if ($auction_result->num_rows == 0) {
        die("<script>alert('Error: Company does not exist!'); window.history.back();</script>");
    }

    $auction_row = $auction_result->fetch_assoc();
    $auction_id = $auction_row['id'];

    // Initialize $image_path with a default value
    $image_path = null;

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "../assets/";
        $target_file = $target_dir . $image_name;
        
        // Create assets directory if it doesn't exist
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0755, true)) {
                die("<script>alert('Failed to create assets directory. Please contact administrator.'); window.history.back();</script>");
            }
            // Set proper permissions
            chmod($target_dir, 0755);
        }
        
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            die("<script>alert('Error uploading image'); window.history.back();</script>");
        }
        $image_path = "assets/" . $image_name;
    }

    // First verify all your variables are set
    $auction_id = $auction_row['id'];
    $lot_number = mysqli_real_escape_string($conn, $_POST['lot_number']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $bidders = mysqli_real_escape_string($conn, $_POST['bidders']);
    $price = mysqli_real_escape_string($conn, $_POST['price']) / $exchange_rate;
    $min_bid = mysqli_real_escape_string($conn, $_POST['min_bid']) / $exchange_rate;
    $max_bid = mysqli_real_escape_string($conn, $_POST['max_bid']) / $exchange_rate;
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $starting_datetime = $starting_date . ' ' . $starting_time;
    $closing_datetime = $closing_date . ' ' . $closing_time;

    // ✅ Insert into Database
    $query = "INSERT INTO auction_items (
        auction_id, lot_number, title, bidders, price, 
        min_bid, max_bid, `condition`, description, image,
        category, starting_time, closing_time
    ) VALUES (
        '$auction_id', '$lot_number', '$title', '$bidders', '$price',
        '$min_bid', '$max_bid', '$condition', '$description', '$image_path',
        '$category', '$starting_datetime', '$closing_datetime'
    )";

    if ($conn->query($query)) {
        echo "<script>
                alert('Item Added Successfully!');
                window.location.href = 'auction_items_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
                window.history.back();
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Auction Items</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 60%;
            max-width: 100%; /* ✅ Maximum width set */
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
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input:focus, textarea:focus, select:focus {
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
        .select2-container {
            width: 100% !important;
            margin-bottom: 15px;
        }



.select2-selection__clear{
    width: unset !important;
}


.select2-container--default .select2-selection--single .select2-selection__clear {
  cursor: pointer;
  float: right;
  font-weight: bold;
  height: unset !important;
  margin-right: 20px;
  padding-right: 0px;
}

        .select2-selection {
            height: 45px !important;
            padding: 8px !important;
            border: 1px solid #ddd !important;
            border-radius: 5px !important;
        }
        .select2-selection__arrow {
            height: 44px !important;
        }
        /* Add these new styles */
        .select2-container--default .select2-selection--single {
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
        }
        .select2-container--default .select2-results > .select2-results__options {
            max-height: 200px;
            overflow-y: auto;
            text-align: left !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #999;
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
                <select name="company_name" class="company-select" required>
                    <option value="">Select a company</option>
                    <?php
                    $company_query = "SELECT DISTINCT company_title FROM auctions ORDER BY company_title";
                    $company_result = $conn->query($company_query);
                    
                    while ($company = $company_result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($company['company_title']) . '">' 
                             . htmlspecialchars($company['company_title']) . '</option>';
                    }
                    ?>
                </select>

                <label>Category</label>
                <select name="category" required>
                    <option value="">Select a category</option>
                    <option value="cars">Cars</option>
                    <option value="furniture">Furniture</option>
                    <option value="electronics">Electronics</option>
                    <option value="real_estate">Real Estate</option>
                    <option value="other">Other</option>
                </select>
                    <h4>Timing</h4>
                <label>Starting Date</label>
                <input type="date" name="starting_date" required>

                <label>Starting Time</label>
                <input type="time" name="starting_time" required>

                <label>Closing Date</label>
                <input type="date" name="closing_date" required>

                <label>Closing Time</label>
                <input type="time" name="closing_time" required>

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

    <script>
        $(document).ready(function() {
            $('.company-select').select2({
                placeholder: 'Search for a company...',
                allowClear: true
            });
        });
    </script>

</body>
</html>
