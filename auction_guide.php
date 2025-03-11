<?php include 'navbar.php'; ?>
<?php include 'navbar2.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auction Guide - How to Participate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333;
        }
      .container {
  width: 90%;
  max-width: 800px;
  margin: 50px auto;
    margin-top: 50px;
  background: white;
    background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  margin-top: 10rem;
}
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #f78b00;
            margin-bottom: 20px;
        }
        .step {
            text-align: left;
            padding: 15px;
            border-radius: 8px;
            background: #ffffff;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .step h2 {
            font-size: 22px;
            color: #f78b00;
        }
        .step p {
            font-size: 16px;
            line-height: 1.6;
        }
        .highlight {
            font-weight: bold;
            color: #f78b00;
        }
        .note {
            background: #ffecb3;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="title">How to Participate in an Auction</h1>

        <!-- ✅ Step 1 -->
        <div class="step">
            <h2>Step 1: Choose a Company</h2>
            <p>To get started, browse through the available auction companies and select the one that has the items you're interested in bidding for.</p>
        </div>

        <!-- ✅ Step 2 -->
        <div class="step">
            <h2>Step 2: Select Your Items</h2>
            <p>Once you’ve chosen a company, explore their auction listings and pick the items you want to bid on.</p>
        </div>

        <!-- ✅ Step 3 -->
        <div class="step">
            <h2>Step 3: Place Your Bids</h2>
            <p>Start bidding on your selected items. You can place bids on **multiple items**, even if they belong to **different companies**.</p>
        </div>

        <!-- ✅ Step 4 -->
        <div class="step">
            <h2>Step 4: Check Your Winning Bids</h2>
            <p>When the auction ends, check the <span class="highlight">Finance Page</span> under your Auction Dashboard. This will show:</p>
            <ul>
                <li>The **items you won**</li>
                <li>The **amount to be paid** for each item</li>
                <li>The **total amount due per company**</li>
            </ul>
        </div>

        <!-- ✅ Step 5 -->
        <div class="step">
            <h2>Step 5: View Payment Details</h2>
            <p>To see how and where to make payments, click **View Payment Info** under each company whose bids you have won.</p>
        </div>

        <!-- ✅ Important Payment Note -->
        <div class="note">
            <strong>⚠️ Important:</strong> Pay attention to the **payment deadline**. Payments will **not** be accepted after the deadline unless you have made special arrangements with the company.
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
