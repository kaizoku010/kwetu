<?php 
include '../includes/db.php';



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title            = mysqli_real_escape_string($conn, $_POST['title']);
    $opening_date     = mysqli_real_escape_string($conn, $_POST['opening_date']);
    $starting_time    = mysqli_real_escape_string($conn, $_POST['starting_time']);
    $closing_date     = mysqli_real_escape_string($conn, $_POST['closing_date']);
    $closing_time     = mysqli_real_escape_string($conn, $_POST['closing_time']);
    $location         = mysqli_real_escape_string($conn, $_POST['location']);
    $description      = mysqli_real_escape_string($conn, $_POST['description']);
    $bank_name        = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $account_number   = mysqli_real_escape_string($conn, $_POST['account_number']);
    $swift_code       = mysqli_real_escape_string($conn, $_POST['swift_code']);
    $payment_deadline = mysqli_real_escape_string($conn, $_POST['payment_deadline']);
    $how_to_pay       = mysqli_real_escape_string($conn, $_POST['how_to_pay']);
    
    // Correct file handling
    $image            = $_FILES['image']['name'];
    $image_tmp        = $_FILES['image']['tmp_name'];
    $second_image     = $_FILES['second_image']['name'] ?? '';
    $second_image_tmp = $_FILES['second_image']['tmp_name'] ?? '';

    // Create assets directory if it doesn't exist
    if (!is_dir("../assets")) {
        mkdir("../assets", 0777, true);
    }

    // Move uploaded files
    $image_path = '';
    $second_image_path = '';
    
    if (move_uploaded_file($image_tmp, "../assets/$image")) {
        $image_path = "assets/$image";
    } else {
        echo "<script>alert('Error uploading primary image');</script>";
        exit;
    }

    if (!empty($second_image) && !empty($second_image_tmp)) {
        if (move_uploaded_file($second_image_tmp, "../assets/$second_image")) {
            $second_image_path = "assets/$second_image";
        }
    }

    $query = "INSERT INTO auctions (
        company_title, opening_date, starting_time, closing_date, 
        closing_time, location, description, image, second_image, 
        bank_name, account_number, swift_code, payment_deadline, how_to_pay
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssssssssssss", 
        $title, $opening_date, $starting_time, $closing_date,
        $closing_time, $location, $description, $image_path, $second_image_path,
        $bank_name, $account_number, $swift_code, $payment_deadline, $how_to_pay
    );

    if ($stmt->execute()) {
        echo "<script>alert('Auction Added Successfully!'); window.location.href='auctions_admin.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel - Add Auction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding-top: 80px;
        }
        .container {
            width: 80%;
            max-width: 1100px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2, h4 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel - Add Auction</h2>
        <form action="auctions_admin.php" method="POST" enctype="multipart/form-data">
            <label>Auction Title</label>
            <input type="text" name="title" required>

            <label>Opening Date</label>
            <input type="date" name="opening_date" required>
             
             
            <label>Starting Time</label>
                <input type="time" name="starting_time" required>

            <label>Closing Date</label>
            <input type="date" name="closing_date" required>
            
            <label>Closing Time</label>
                <input type="time" name="closing_time" required>
            

            <label>Location</label>
            <input type="text" name="location" required>

            <label>Description</label>
            <textarea name="description" rows="3" required></textarea>

            <label>Primary Auction Image</label>
            <input type="file" name="image" required>

            <label>Second Auction Image (Optional)</label>
            <input type="file" name="second_image">

            <h4>Payment Information</h4>
            <label>Bank Name</label>
            <input type="text" name="bank_name" required>

            <label>Account Number</label>
            <input type="text" name="account_number" required>

            <label>SWIFT Code</label>
            <input type="text" name="swift_code" required>

            <label>Payment Deadline</label>
            <input type="text" name="payment_deadline" required>

            <label>How to Pay for Items</label>
            <textarea name="how_to_pay" rows="5" required></textarea>

            <button type="submit" class="btn btn-success">Add Auction</button>
        </form>
    </div>
</body>
</html>

