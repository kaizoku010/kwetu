<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers - Join Our Team</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        /* ✅ Hero Section */
        .hero {
            background: url('assets/career-bg.jpg') no-repeat center center/cover;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .hero h1 {
            font-size: 50px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        .hero-btn {
            margin-top: 20px;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            background: #f78b00;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        .hero-btn:hover {
            background: #0056b3;
        }

        /* ✅ Content Sections */
        .section {
            width: 90%;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .section img {
            width: 40%;
            border-radius: 8px;
        }
        .section-text {
            width: 60%;
        }
        .section h2 {
            color: #f78b00;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .section p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        /* ✅ Core Values */
        .core-values {
            text-align: center;
            margin: 50px auto;
            width: 90%;
            max-width: 1000px;
        }
        .core-values h2 {
            font-size: 28px;
            color: #f78b00;
            margin-bottom: 20px;
        }
        .values-container {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        .value-box {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .value-box h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .value-box p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- ✅ Include Navbar -->

    <!-- ✅ Join Our Team Hero Section -->
    <div class="hero">
        <h1>Join Our Team</h1>
       / <!-- <a href="open_positions.php" class="hero-btn">View Open Positions</a> -->
    </div>

    <!-- ✅ Who Are We Section -->
    <div class="section">
        <img src="assets/who-we-are.jpg" alt="Who We Are">
        <div class="section-text">
            <h2>Who Are We?</h2>
            <p>At Kwetu Auctions, we are more than just an auction platform. We are innovators, problem solvers, and dedicated professionals bringing transparency to the online auction industry.</p>
            <p>Founded in 2015, our platform has grown to become one of the most trusted names in online auctions. We are driven by passion and technology, ensuring the best experience for both buyers and sellers.</p>
        </div>
    </div>

    <!-- ✅ Who Are We Looking For? -->
    <div class="section">
        <div class="section-text">
            <h2>Who Are We Looking For?</h2>
            <p>We seek passionate, talented, and driven individuals who are ready to make an impact in the online auction industry.</p>
            <p>Whether you are a seasoned professional or just starting your career, if you are eager to grow and innovate, we would love to hear from you!</p>
        </div>
        <img src="assets/who-we-look-for.jpg" alt="Who We Look For">
    </div>

    <!-- ✅ Core Values Section -->
    <div class="core-values">
        <h2>Our Core Values</h2>
        <div class="values-container">
            <div class="value-box">
                <h3>Integrity</h3>
                <p>We uphold the highest ethical standards in all our business operations.</p>
            </div>
            <div class="value-box">
                <h3>Innovation</h3>
                <p>We continuously seek new ways to improve our platform and services.</p>
            </div>
            <div class="value-box">
                <h3>Customer Focus</h3>
                <p>Our customers are at the heart of everything we do.</p>
            </div>
            <div class="value-box">
                <h3>Excellence</h3>
                <p>We strive to deliver high-quality results and exceed expectations.</p>
            </div>
        </div>
    </div>
<?php include 'includes/footer.php'; ?>

</body>
</html>
