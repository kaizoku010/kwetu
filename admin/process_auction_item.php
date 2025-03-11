<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Get form data
    $company_name = $_POST['company_name'];
    $lot_number = $_POST['lot_number'];
    $title = $_POST['title'];
    $bidders = $_POST['bidders'];
    $exchange_rate = 3800;
    $price = $_POST['price'] / $exchange_rate;
    $min_bid = $_POST['min_bid'] / $exchange_rate;
    $max_bid = $_POST['max_bid'] / $exchange_rate;
    $condition = $_POST['condition'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Get auction ID
    $result = $conn->query("SELECT id FROM auctions WHERE company_title = '$company_name'");
    if ($result->num_rows == 0) {
        throw new Exception('Company does not exist: ' . $company_name);
    }
    $auction_row = $result->fetch_assoc();
    $auction_id = $auction_row['id'];

    // Handle main image
    $image_path = null;
    if (!empty($_FILES['main_image']['name'])) {
        $image_name = basename($_FILES['main_image']['name']);
        $target_dir = "../assets/";
        $target_file = $target_dir . $image_name;
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file);
        $image_path = "assets/" . $image_name;
    }

    // Insert main item data
    $query = "INSERT INTO auction_items (
        auction_id, lot_number, title, bidders, price, 
        min_bid, max_bid, `condition`, description, image,
        category
    ) VALUES (
        '$auction_id', '$lot_number', '$title', '$bidders', '$price',
        '$min_bid', '$max_bid', '$condition', '$description', '$image_path',
        '$category'
    )";

    if (!$conn->query($query)) {
        throw new Exception('Failed to save item data: ' . $conn->error);
    }

    $item_id = $conn->insert_id;

    // Handle additional images
    if (!empty($_FILES['additional_images']['name'][0])) {
        foreach ($_FILES['additional_images']['name'] as $key => $name) {
            if (empty($name)) continue;

            $image_name = basename($name);
            $target_file = "../assets/" . $image_name;
            
            if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$key], $target_file)) {
                $additional_image_path = "assets/" . $image_name;
                $conn->query("INSERT INTO item_images (item_id, image_path, is_primary) 
                            VALUES ('$item_id', '$additional_image_path', FALSE)");
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Item added successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
