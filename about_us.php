<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Kwetu Auctions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 80px auto 20px auto; /* Added top margin for spacing */
            padding: 20px;
            border-radius: 10px;
        }

        .about-title {
        font-size: 30px;
        font-weight: bold;
        text-align: center;
        color: #f78b00;
        margin-bottom: 20px;
        margin-top: 7rem;
     }
        .about-section {
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            text-align: justify;
        }
        .about-section img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .highlight {
            color: #f78b00;
            font-weight: bold;
        }
        .testimonial {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-style: italic;
            text-align: center;
        }
        .testimonial strong {
            color: #f78b00;
        }
        .contact-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- ✅ Include Navbar -->

    <div class="container">
        <h1 class="about-title">About Kwetu</h1>

        <!-- ✅ Introduction -->
        <div class="about-section">
            <img src="assets/aucs2.jpg" alt="Auction House">
            <p>
                Welcome to <span class="highlight">Kwetu Auctions</span>, your trusted platform for online auctions!
                Established in <b>2015</b>, we have revolutionized the way people buy and sell valuable assets.
                Whether you’re looking for rare collectibles, cars, real estate, or luxury goods, we connect buyers and sellers
                in a <span class="highlight">secure, transparent, and exciting</span> bidding environment.
            </p>
        </div>

        <!-- ✅ Our Mission -->
        <div class="about-section">
            <h2 class="highlight">Our Mission</h2>
            <p>
                Our mission is to make online auctions <b>accessible, fair, and enjoyable</b> for everyone.
                We strive to provide a seamless experience by ensuring:
            </p>
            <ul>
                <li>✔️ Fair and competitive bidding</li>
                <li>✔️ 100% secure transactions</li>
                <li>✔️ Wide variety of high-quality listings</li>
                <li>✔️ A user-friendly platform for buyers and sellers</li>
            </ul>
        </div>

        <!-- ✅ How It Works -->
        <div class="about-section">
            <h2 class="highlight">How It Works</h2>
            <img src="assets/how-it-works.png" alt="How Auctions Work">
            <p>
                Participating in an auction on Kwetu Auctions is easy! Follow these steps:
            </p>
            <ol>
                <li><b>Register</b>: Create an account for free.</li>
                <li><b>Browse Auctions</b>: Explore different auction categories.</li>
                <li><b>Place Your Bid</b>: Enter your bid before the auction ends.</li>
                <li><b>Win & Pay</b>: If you have the highest bid, complete the payment and receive your item!</li>
            </ol>
        </div>

        <!-- ✅ Why Choose Us? -->
        <div class="about-section">
            <h2 class="highlight">Why Choose Kwetu Auctions?</h2>
            <p>
                We stand out from other auction platforms because of our commitment to <b>trust, security, and customer satisfaction.</b>
                Here’s why thousands of users prefer us:
            </p>
            <ul>
                <li>⭐ Verified Sellers & Authentic Listings</li>
                <li>⭐ 24/7 Customer Support</li>
                <li>⭐ Secure Payment Methods</li>
                <li>⭐ Fast Shipping & Pickup Options</li>
            </ul>
        </div>

        <!-- ✅ Customer Testimonials -->
        <div class="about-section">
            <h2 class="highlight">What Our Customers Say</h2>
            <div class="testimonial">
                <p>"Kwetu Auctions helped me buy my dream car at an amazing price! The process was so smooth!"</p>
                <strong>- John Kamau, Nairobi</strong>
            </div>
            <div class="testimonial">
                <p>"I was skeptical at first, but now I buy all my collectibles from this platform. Safe and reliable!"</p>
                <strong>- Grace Nakato, Kampala</strong>
            </div>
        </div>

    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
