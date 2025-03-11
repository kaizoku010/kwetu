<?php 
include './includes/db.php'; 
session_start();

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $opening_date = $_POST['opening_date'];
    $closing_date = $_POST['closing_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // ✅ Move uploaded image to 'images' folder
    move_uploaded_file($image_tmp, "images/$image");

    // ✅ Insert auction into database
    $query = "INSERT INTO auctions (company_title, opening_date, closing_date, location, description, image) 
              VALUES ('$title', '$opening_date', '$closing_date', '$location', '$description', 'images/$image')";
    
    if ($conn->query($query)) {
        echo "<script>alert('Auction Added Successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Auction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">Admin Panel - Add Auction</h2>
        
        <form action="auctions_admin.php" method="POST" enctype="multipart/form-data">  <!-- ✅ Updated Filename -->
            <div class="mb-3">
                <label class="form-label">Auction Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Opening Date</label>
                <input type="date" class="form-control" name="opening_date" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Closing Date</label>
                <input type="date" class="form-control" name="closing_date" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="location" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Auction Image</label>
                <input type="file" class="form-control" name="image" required>
            </div>

            <button type="submit" class="btn btn-success">Add Auction</button>
        </form>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

</body>
</html>
