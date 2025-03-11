<?php
include './includes/db.php';

if (!isset($_GET['company'])) {
    die("Invalid request.");
}

$company_name = urldecode($_GET['company']);

// ✅ Fetch Company Details (Including Bank Info)
$stmt = $conn->prepare("SELECT company_title, image, description, bank_name, account_number, swift_code, payment_deadline, how_to_pay FROM auctions WHERE company_title = ?");
$stmt->bind_param("s", $company_name);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Check if company exists
if ($result->num_rows == 0) {
    die("Company not found.");
} else {
    $company = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($company['company_title']); ?> - Payment Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding-top: 80px;
        }
        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .company-title {
            font-size: 24px;
            font-weight: bold;
            color: #f78b00;
            margin-top: 10px;
        }
        .company-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 5px;
            margin: 20px auto;
        }
        .payment-info, .how-to-pay {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            text-align: left;
            font-size: 16px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <!-- ✅ Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2 class="company-title"><?php echo htmlspecialchars($company['company_title']); ?> - Payment Information</h2>
        <img src="<?php echo htmlspecialchars($company['image']); ?>" class="company-image" alt="Company Logo">
        
        <!-- ✅ Payment Details Section -->
        <div class="payment-info">
            <h4>Bank Payment Details</h4>
            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($company['bank_name']); ?></p>
            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($company['account_number']); ?></p>
            <p><strong>SWIFT Code:</strong> <?php echo htmlspecialchars($company['swift_code']); ?></p>
            <p><strong>Payment Deadline:</strong> <?php echo htmlspecialchars($company['payment_deadline']); ?></p>
        </div>

        <!-- ✅ How to Pay Section -->
        <div class="how-to-pay">
            <h4>How to Pay for Items</h4>
            <p><?php echo nl2br(htmlspecialchars($company['how_to_pay'])); ?></p>
        </div>
    </div>

</body>
</html>
