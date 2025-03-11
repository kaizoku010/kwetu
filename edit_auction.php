<?php
session_start();
include 'includes/db.php'; // Ensure database connection

// ✅ Check if the user is an admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    die("Access denied. Admins only.");
}

// ✅ Check if the auction ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Auction ID is missing.");
}

$auction_id = intval($_GET['id']);

// ✅ Fetch auction details
$query = $conn->prepare("SELECT * FROM auctions WHERE id = ?");
$query->bind_param("i", $auction_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    die("Auction not found.");
}

$auction = $result->fetch_assoc();

// ✅ Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_title = $_POST['company_title'];
    $opening_date = $_POST['opening_date'];
    $closing_date = $_POST['closing_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $image = $auction['image']; // Default to existing image

    // ✅ Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "assets/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                die("Error uploading image.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.");
        }
    }

    // ✅ Update auction details in the database
    $updateQuery = $conn->prepare("UPDATE auctions SET company_title=?, opening_date=?, closing_date=?, location=?, description=?, image=? WHERE id=?");
    $updateQuery->bind_param("ssssssi", $company_title, $opening_date, $closing_date, $location, $description, $image, $auction_id);

    if ($updateQuery->execute()) {
        header("Location: auctions.php?success=Auction Updated");
        exit();
    } else {
        die("Error updating auction: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Auction</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            background: white;
            padding: 20px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #f78b00;
            font-weight: bold;
            text-align: center;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            width: 100%;
            background: #f78b00;
            border: none;
            padding: 10px;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }
        .img-thumbnail {
            display: block;
            margin: 10px auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Auction</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Company Title</label>
            <input type="text" name="company_title" class="form-control" value="<?= htmlspecialchars($auction['company_title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Opening Date</label>
            <input type="datetime-local" name="opening_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($auction['opening_date'])) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Closing Date</label>
            <input type="datetime-local" name="closing_date" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($auction['closing_date'])) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($auction['location']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($auction['description']) ?></textarea>
        </div>

        <div class="mb-3 text-center">
            <label class="form-label">Current Image</label>
            <br>
            <img src="<?= $auction['image'] ?>" alt="Auction Image" class="img-thumbnail" width="200">
        </div>

        <div class="mb-3">
            <label class="form-label">Change Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Auction</button>
        <a href="auctions.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
