<?php include 'navbar.php'; ?> <!-- ✅ Added navbar.php -->
<?php include 'navbar2.php'; ?> <!-- ✅ Added navbar.php -->
<?php
include './includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $company_email = mysqli_real_escape_string($conn, $_POST['company_email']);
    $company_location = mysqli_real_escape_string($conn, $_POST['company_location']);
    $company_phone = mysqli_real_escape_string($conn, $_POST['company_phone']);
    $items_sold = mysqli_real_escape_string($conn, $_POST['items_sold']);
    $auction_date = mysqli_real_escape_string($conn, $_POST['auction_date']);
    $inspection_date = mysqli_real_escape_string($conn, $_POST['inspection_date']);

    // ✅ Create `uploads/` directory if it doesn't exist
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // ✅ Handle multiple file uploads
    $imagePaths = [];
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if (!empty($tmpName)) {
            $imageName = basename($_FILES['images']['name'][$index]);
            $imagePath = $uploadDir . $imageName;

            if (move_uploaded_file($tmpName, $imagePath)) {
                $imagePaths[] = $imagePath;
            } else {
                die("Error uploading image: " . $_FILES['images']['name'][$index]);
            }
        }
    }

    // ✅ Convert array to comma-separated string for database storage
    $images = implode(",", $imagePaths);

    // ✅ Insert into database
    $query = "INSERT INTO auction_requests 
        (company_name, company_email, company_location, company_phone, items_sold, auction_date, inspection_date, images) 
        VALUES 
        ('$company_name', '$company_email', '$company_location', '$company_phone', '$items_sold', '$auction_date', '$inspection_date', '$images')";

    if ($conn->query($query)) {
        echo "<script>alert('Your auction request has been submitted successfully!'); window.location.href='sell_with_us.php';</script>";
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
    <title>Sell With Us - Kwetu Auctions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            text-align: center;
        }
    .container {
  width: 90%;
  max-width: 700px;
  margin: 50px auto;
    margin-top: 50px;
  background-color: white;
  padding: 20px;
  border-radius: 8px;

  margin-top: 10rem;
}
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        form {
            text-align: left;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Sell With Us</h2>
        <p>Fill out the form below to request an auction for your items.</p>

        <form action="sell_with_us.php" method="POST" enctype="multipart/form-data">
            <label>Company Name</label>
            <input type="text" name="company_name" required>

            <label>Company Email</label>
            <input type="email" name="company_email" required>

            <label>Company Location</label>
            <input type="text" name="company_location" required>

            <label>Company Phone Number</label>
            <input type="text" name="company_phone" required>

            <label>Items Being Sold</label>
            <textarea name="items_sold" rows="3" required></textarea>

            <label>Auction Date</label>
            <input type="date" name="auction_date" required>

            <label>Inspection Date</label>
            <input type="date" name="inspection_date" required>

            <label>Upload Photos</label>
            <input type="file" name="images[]" multiple accept="image/*" required>

            <button type="submit">Submit Request</button>
        </form>
    </div>

</body>
</html>
