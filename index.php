<?php include 'navbar.php'; ?>
<?php include './includes/db.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwetu Store - Home</title>
    <style>
        /* ✅ General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }


        .hero {
            background: url('assets/auc.jpg') center/cover no-repeat;
            height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            font-weight: bold;
            text-shadow: none !important;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 18px;
        }

        /* ✅ Search Bar */
        .search-container {
            width: 60%;
            max-width: 600px;
            margin-top: 20px;
            display: flex;
        }

        .search-input {
            flex-grow: 1;
            padding: 12px;
            border: none;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
        }

        .search-btn {
            background-color: #f78b00;
            color: white;
            border: none;
            padding: 12px 15px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .search-btn:hover {
            background-color: #0056b3;
        }

        /* ✅ Categories Section */
        .categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 40px auto;
            width: 90%;
            max-width: 1000px;
        }

        .category-card {
            width: 30%;
            min-width: 250px;
            margin: 15px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .category-card img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .category-card h3 {
            font-size: 18px;
            margin-top: 10px;
        }

        /* ✅ CTA (Call-to-Action) Section */
        .cta-section {
            background: #f78b00;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 40px;
        }

        .cta-section h2 {
            font-size: 28px;
        }

        .cta-section p {
            font-size: 16px;
            margin: 10px 0;
        }

.ic-box-text{
    font-weight: normal !important;
}

  .cta-btn {
  display: inline-block;
  padding: 4px 30px;
  background: white;
  color: black;
  font-weight: normal;
  text-decoration: none;
  border-radius: 5px;
  margin-top: 15px;
  transition: background 0.3s;
  font-size: 1rem;
}

        .cta-btn:hover {
            background: #f1f1f1;
        }
    </style>
</head>
<body>

    <!-- ✅ Hero Section -->
    <div class="hero">
        <h1>Your One-Stop Auction Platform</h1>
        <!-- <p>Your One-Stop Auction Platform</p> -->

        <!-- ✅ Search Bar -->
        <div class="search-container">
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="query" class="search-input" placeholder="Search for auctions, products, and more..." required>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        <div class="categories-container">
            
            <div class="ic-box">
                <image class="ic-box-img" src="assets/ics/device.png" alt="Search Icon">
                <h5 class="ic-box-text">Electronics Auctions</h5>    
            </div>  
            <div class="ic-box">
                <image class="ic-box-img" src="assets/ics/property.png" alt="Search Icon">
                <h5 class="ic-box-text">Real Estate Auctions</h5>    
            </div>  
            <div class="ic-box">
                <image class="ic-box-img" src="assets/ics/furniture.png" alt="Search Icon">
                <h5 class="ic-box-text">Furniture Auctions</h5>    
            </div>  
            
            <div class="ic-box">
                <image class="ic-box-img" src="assets/ics/vases.png" alt="Search Icon">
                <h5 class="ic-box-text">Other Auctions</h5>    
            </div>  
              <div class="ic-box">
                <image class="ic-box-img" src="assets/ics/car.png" alt="Search Icon">
                <h5 class="ic-box-text">Car Auctions</h5>    
            </div>          
        </div>

    </div>

    <!-- ✅ Categories Section -->
    <!-- <div class="categories">
        <div class="category-card">
            <img src="assets/cars.jpg" alt="Cars">
            <h3>Car Auctions</h3>
        </div>
        <div class="category-card">
            <img src="assets/electronics.jpg" alt="Electronics">
            <h3>Electronics Auction</h3>
        </div>
        <div class="category-card">
            <img src="assets/realestate.jpg" alt="Real Estate">
            <h3>Real Estate Auctions</h3>
        </div>
        <div class="category-card">
            <img src="assets/antiques.jpg" alt="Antiques">
            <h3>Antiques & Collectibles Auction</h3>
        </div>
        <div class="category-card">
            <img src="assets/furniture.jpg" alt="Furniture">
            <h3>Furniture Auctions</h3>
        </div>
    </div> -->

    <!-- ✅ Call-to-Action (CTA) Section -->
    <div class="cta-section">
        <h2>Want to Sell Your Items?</h2>
        <p>Partner with Kwetu Auctions and reach thousands of buyers!</p>
        <a style="margin-top:0px !important" href="sell_with_us.php" class="cta-btn">Get Started</a>
    </div>

    <?php include 'auctions.php'; ?>
    <?php include 'navbar2.php'; ?>

</body>
</html>
