<?php include 'navbar.php'; ?>
<?php include 'navbar2.php'; ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .hero {
            background: url('assets/transport-banner2.jpg') no-repeat center center/cover;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .hero h1 {
            font-size: 48px;
            font-weight: bold;
            margin: 0;
        }
        .hero p {
            font-size: 18px;
            max-width: 70%;
            margin: 10px auto;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }
        .transport-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .transport-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }
        .transport-info {
            flex: 1;
        }
        .transport-info h3 {
            font-size: 22px;
            margin-bottom: 5px;
            color: #f78b00;
        }
        .transport-info p {
            margin: 5px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- ✅ Hero Section with Background Image -->
    <div class="hero">
        <h1>Transport Services</h1>
        <p>
            Transportation is a critical part of our industry. We provide solutions to ensure that your equipment arrives safely and efficiently.
            Equipment transportation demands specialized knowledge and skill. When it comes to secure transport, entrust it to one of our 3rd party specialists.
        </p>
    </div>

    <!-- ✅ Transport Companies Section -->
    <div class="container">
        <?php
        // ✅ Example Transport Companies
        $transport_companies = [
            [
                "name" => "FastTrack Logistics",
                "location" => "Kampala, Uganda",
                "contact" => "+256 700 123 456",
                "image" => "assets/transport1.jpg"
            ],
            [
                "name" => "SafeHaul Transporters",
                "location" => "Nairobi, Kenya",
                "contact" => "+254 711 234 567",
                "image" => "assets/transport2.jpg"
            ],
            [
                "name" => "CargoMile Express",
                "location" => "Dar es Salaam, Tanzania",
                "contact" => "+255 789 345 678",
                "image" => "assets/transport3.jpg"
            ],
            [
                "name" => "Swift Heavy Movers",
                "location" => "Lusaka, Zambia",
                "contact" => "+260 965 456 789",
                "image" => "assets/transport4.jpg"
            ],
            [
                "name" => "TransSecure Freight",
                "location" => "Johannesburg, South Africa",
                "contact" => "+27 822 567 890",
                "image" => "assets/transport5.jpg"
            ],
            [
                "name" => "HaulMaster Logistics",
                "location" => "Mombasa, Kenya",
                "contact" => "+254 799 678 901",
                "image" => "assets/transport6.jpg"
            ]
        ];

        // ✅ Loop Through and Display Transport Companies
        foreach ($transport_companies as $company) {
            echo '
            <div class="transport-card">
                <img src="' . $company['image'] . '" alt="Transport Company">
                <div class="transport-info">
                    <h3>' . $company['name'] . '</h3>
                    <p><strong>Location:</strong> ' . $company['location'] . '</p>
                    <p><strong>Contact:</strong> ' . $company['contact'] . '</p>
                </div>
            </div>';
        }
        ?>
    </div>
<?php include 'includes/footer.php'; ?>

</body>
</html>
