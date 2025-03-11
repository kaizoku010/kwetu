<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include './includes/db.php';

session_start();

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Validate Item ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid item ID.");
}

$item_id = (int)$_GET['id'];

// ✅ Fetch Existing Item Data
$stmt = $conn->prepare("SELECT * FROM auction_items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Item not found.");
}

$item = $result->fetch_assoc();

// ✅ Handle Form Submission for Editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $bidders = mysqli_real_escape_string($conn, $_POST['bidders']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $min_bid = mysqli_real_escape_string($conn, $_POST['min_bid']);
    $max_bid = mysqli_real_escape_string($conn, $_POST['max_bid']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // ✅ Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "assets/" . basename($image);

        if (!move_uploaded_file($image_tmp, $image_path)) {
            die("Error uploading image.");
        }

        // ✅ Update Image Path in DB
        $update_image_stmt = $conn->prepare("UPDATE auction_items SET image = ? WHERE id = ?");
        $update_image_stmt->bind_param("si", $image_path, $item_id);
        $update_image_stmt->execute();
    }

    // ✅ Update Item Details
    $update_stmt = $conn->prepare("UPDATE auction_items SET title = ?, bidders = ?, price = ?, min_bid = ?, max_bid = ?, `condition` = ?, description = ? WHERE id = ?");
    $update_stmt->bind_param("siddsssi", $title, $bidders, $price, $min_bid, $max_bid, $condition, $description, $item_id);

    if ($update_stmt->execute()) {
        echo "<script>
                alert('Item updated successfully!');
                window.location.href = 'auction.php?id=1';
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
    <title>Edit Auction Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">Edit Auction Item</h2>

        <form action="edit_item.php?id=<?php echo $item_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Item Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Number of Bidders</label>
                <input type="number" class="form-control" name="bidders" value="<?php echo htmlspecialchars($item['bidders']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
            </div>

            <!-- ✅ New Fields: Edit Min & Max Bid -->
            <div class="mb-3">
                <label class="form-label">Minimum Bid Increment</label>
                <input type="number" class="form-control" name="min_bid" value="<?php echo htmlspecialchars($item['min_bid']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Maximum Bid Increment</label>
                <input type="number" class="form-control" name="max_bid" value="<?php echo htmlspecialchars($item['max_bid']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Condition</label>
                <textarea class="form-control" name="condition" rows="2" required><?php echo htmlspecialchars($item['condition']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" required><?php echo htmlspecialchars($item['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image" class="img-fluid" style="max-width: 200px;">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload New Image (Optional)</label>
                <input type="file" class="form-control" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Update Item</button>
        </form>

        <a href="auction.php" class="btn btn-secondary mt-3">Back to Auction</a>
    </div>

</body>
</html>
