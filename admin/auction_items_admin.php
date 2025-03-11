<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include admin navigation and sidebar first, before setting JSON header
include 'navbar_admin.php';
include 'admin_dashboard.php';

// Only set JSON header for POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
}

include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Set Exchange Rate
$exchange_rate = 3800;

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ['success' => false, 'message' => '', 'debug' => []];
    
    try {
        // Log the incoming data
        $response['debug']['post'] = $_POST;
        $response['debug']['files'] = $_FILES;

        if (empty($_POST['company_name'])) {
            throw new Exception("Company name is required");
        }

        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $lot_number = mysqli_real_escape_string($conn, $_POST['lot_number']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $bidders = mysqli_real_escape_string($conn, $_POST['bidders']);
        $price = floatval($_POST['price']) / $exchange_rate;
        $min_bid = floatval($_POST['min_bid']) / $exchange_rate;
        $max_bid = floatval($_POST['max_bid']) / $exchange_rate;
        $condition = mysqli_real_escape_string($conn, $_POST['condition']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $starting_datetime = $_POST['starting_date'] . ' ' . $_POST['starting_time'];
        $closing_datetime = $_POST['closing_date'] . ' ' . $_POST['closing_time'];

        // Get auction ID
        $result = $conn->query("SELECT id FROM auctions WHERE company_title = '$company_name'");
        if ($result->num_rows == 0) {
            throw new Exception("Company not found: $company_name");
        }

        $auction_row = $result->fetch_assoc();
        $auction_id = $auction_row['id'];

        // Handle main image
        $image_path = null;
        if (!empty($_FILES['main_image']['name'])) {
            $image_name = time() . '_' . basename($_FILES['main_image']['name']);
            $target_dir = "../assets/";
            $target_file = $target_dir . $image_name;
            
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    throw new Exception("Failed to create assets directory");
                }
            }
            
            if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
                throw new Exception("Failed to upload main image. Upload error code: " . $_FILES['main_image']['error']);
            }
            $image_path = "assets/" . $image_name;
        }

        // Insert main item data
        $stmt = $conn->prepare("INSERT INTO auction_items (
            auction_id, lot_number, title, bidders, price, 
            min_bid, max_bid, `condition`, description, image,
            category, starting_time, closing_time
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "isssdddssssss",
            $auction_id, $lot_number, $title, $bidders, $price,
            $min_bid, $max_bid, $condition, $description, $image_path,
            $category, $starting_datetime, $closing_datetime
        );

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $item_id = $stmt->insert_id;
        $response['debug']['item_id'] = $item_id;

        // Handle additional images
        if (!empty($_FILES['additional_images']['name'][0])) {
            $stmt_additional = $conn->prepare("INSERT INTO item_images (item_id, image_path, is_primary) VALUES (?, ?, FALSE)");
            
            if (!$stmt_additional) {
                throw new Exception("Prepare additional images failed: " . $conn->error);
            }

            foreach ($_FILES['additional_images']['name'] as $key => $name) {
                if (empty($name)) continue;

                $image_name = time() . '_' . basename($name);
                $target_file = "../assets/" . $image_name;
                
                if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$key], $target_file)) {
                    $additional_image_path = "assets/" . $image_name;
                    $stmt_additional->bind_param("is", $item_id, $additional_image_path);
                    if (!$stmt_additional->execute()) {
                        throw new Exception("Failed to save additional image: " . $stmt_additional->error);
                    }
                }
            }
        }

        $response['success'] = true;
        $response['message'] = 'Item added successfully';
        echo json_encode($response);
        exit;

    } catch (Exception $e) {
        $response['success'] = false;
        $response['message'] = $e->getMessage();
        $response['debug']['error'] = $e->getTraceAsString();
        echo json_encode($response);
        exit;
    }
}

// If not a POST request, show the HTML
if ($_SERVER["REQUEST_METHOD"] != "POST") {
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
            margin-left: 270px; /* Add this to account for sidebar width */
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
        .progress {
            height: 20px;
            margin-bottom: 20px;
            display: none;
        }
        .upload-status {
            margin-top: 10px;
            display: none;
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

        <form id="auctionItemForm" method="POST" enctype="multipart/form-data">
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
                <label>Main Item Image</label>
                <input type="file" name="main_image" id="main_image" required>

                <label>Additional Images (Optional)</label>
                <input type="file" name="additional_images[]" id="additional_images" multiple>
                <small class="text-muted">You can select multiple images at once</small>

                <label>Condition</label>
                <textarea name="condition" rows="2" required></textarea>

                <label>Description</label>
                <textarea name="description" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Add Item</button>

            <!-- Progress bar moved below the submit button -->
            <div class="mt-3">
                <div class="progress" style="display: none;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div class="alert upload-status mt-2"></div>
            </div>
        </form>
    </div>


    <script>
    $(document).ready(function() {
        // Show success alert after item addition
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        alert('Item added successfully!');
        <?php endif; ?>

        // Select All functionality
        $('#masterCheckbox').change(function() {
            $('input[name="items[]"]').prop('checked', $(this).prop('checked'));
        });

        // Delete Selected Items
        $('#deleteSelected').click(function() {
            if (!confirm('Are you sure you want to delete selected items?')) return;
            
            const selectedItems = $('input[name="items[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedItems.length === 0) {
                alert('Please select items to delete');
                return;
            }

            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { items: selectedItems, action: 'delete_selected' },
                success: function(response) {
                    if (response.success) {
                        alert('Selected items deleted successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });

        // Delete All Items
        $('#deleteAll').click(function() {
            if (!confirm('Are you absolutely sure you want to delete ALL items? This cannot be undone!')) return;
            
            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { action: 'delete_all' },
                success: function(response) {
                    if (response.success) {
                        alert('All items deleted successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });

        // Single Item Delete
        $('.delete-single').click(function() {
            if (!confirm('Are you sure you want to delete this item?')) return;
            
            const itemId = $(this).data('id');
            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { items: [itemId], action: 'delete_selected' },
                success: function(response) {
                    if (response.success) {
                        alert('Item deleted successfully');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
    </script>

</body>
</html>
<?php
}
?>
