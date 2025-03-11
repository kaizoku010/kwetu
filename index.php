<!-- <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?> -->
<?php include 'navbar.php'; ?>
<?php include './includes/db.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwetu Store - Home</title>
    <link rel="icon" type="image/x-icon" href="assets/favi.png">
    <style>
        /* ✅ General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .hero {
/*             background: url('assets/auc.jpg') center/cover no-repeat;
    background: url('https://asset.cloudinary.com/dnko3bvt0/40cc04863b2f72003d888c5574bfc060') center/cover no-repeat !important;
            https://asset.cloudinary.com/dnko3bvt0/40cc04863b2f72003d888c5574bfc060height: 400px; */
            background: url('assets/auc.jpg') center/cover no-repeat !important;
            display: flex;
            flex-direction: column;
            background-color: black;
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
        }

        /* ✅ Category Grid */
        .categories-section {
            padding: 40px 20px;
            background: white;
            margin-top: -50px;
            position: relative;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .category-box {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .category-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            font-size: 2em;
            margin-bottom: 10px;
            color: #007bff;
        }

        .category-name {
            font-size: 1.2em;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .category-count {
            color: #666;
            font-size: 0.9em;
        }

        /* ✅ Search Bar */
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
            background-color:rgba(247, 140, 0, 0.88) !important;
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

        .ic-box-text {
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
            font-size: .8rem;
        }

        .search-btn:active{
     background: #f78b00 !important;
        }

        .cta-btn:hover {
            background: #f1f1f1;
        }

        .hero p {
            font-weight: 300;    
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <!-- ✅ Hero Section -->
    <div class="hero">
        <h1>Your One-Stop Auction Platform</h1>
        <p>Discover Amazing Deals on Quality Items</p>
        <!-- ✅ Search Bar -->
        <div class="search-container">

            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="query" class="search-input"
                    placeholder="Search for auctions, products, and more..." required>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        <div class="categories-container">

            <div class="ic-box">
                <a style="text-decoration:none" href="category.php?category=electronics">
                    <image class="ic-box-img" src="assets/ics/device.png" alt="Search Icon">
                        <h5 class="ic-box-text">Electronics Auctions</h5>
                </a>
            </div>
            <div class="ic-box">
                <a style="text-decoration:none"  href="category.php?category=real_estate">
                    <image class="ic-box-img" src="assets/ics/property.png" alt="Search Icon">
                        <h5 class="ic-box-text">Real Estate Auctions</h5>
                </a>

            </div>
            <div class="ic-box">
                <a style="text-decoration:none"  href="category.php?category=furniture">
                    <image class="ic-box-img" src="assets/ics/furniture.png" alt="Search Icon">
                        <h5 class="ic-box-text">Furniture Auctions</h5>
            </a>
                    </div>
           


            <div class="ic-box">
                <a style="text-decoration:none"  href="category.php?category=other">
                    <image class="ic-box-img" src="assets/ics/vases.png" alt="Search Icon">
                        <h5 class="ic-box-text">Other Auctions</h5>
                </a>
            </div>
            <div class="ic-box">
                <a style="text-decoration:none" href="category.php?category=cars">
                    <image class="ic-box-img" src="assets/ics/car.png" alt="Search Icon">
                        <h5 class="ic-box-text">Car Auctions</h5>
                </a>

            </div>
        </div>

    </div>



    <!-- ✅ Call-to-Action (CTA) Section -->
    <div class="cta-section">
        <h2>Want to Sell Your Items?</h2>
        <p>Partner with Kwetu Auctions and reach thousands of buyers!</p>
        <a style="margin-top:0px !important" href="sell_with_us.php" class="cta-btn">Get Started</a>
    </div>

<?php include './auctions.php'; ?>
<?php include 'navbar2.php'; ?>

<button id="scrollToTop" title="Go to top">↑</button>

<style>


#scrollToTop {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 99;
  border: none;
  outline: none;
  background-color: #f78b00;
  color: #fdfdfd;
  cursor: pointer;
  padding: 15px;
  border-radius: 50%;
  font-size: 18px;
  width: 56px;
  height: 56px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
  font-weight: bold !important;
}

    #scrollToTop:hover {
        background-color: #f78c00da;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
</style>

<script>
    // Get the button
    const scrollToTopBtn = document.getElementById("scrollToTop");

    // Combine both scroll handlers
    window.onscroll = function() {
        // Scroll to top button visibility
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };

    // When the user clicks on the button, scroll to the top of the document
    scrollToTopBtn.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
</script>
</body>

</html>
