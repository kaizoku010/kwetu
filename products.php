<?php include './includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Kwetu Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- Navbar Included -->

    <div class="container">
        <h2 class="text-center mt-4">Available Products</h2>
        <div class="row">
            <?php
            $result = $conn->query("SELECT * FROM products");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4">
                            <div class="card">
                                <img src="' . $row['image'] . '" class="card-img-top" alt="Product">
                                <div class="card-body">
                                    <h5 class="card-title">' . $row['name'] . '</h5>
                                    <p class="card-text">' . $row['description'] . '</p>
                                    <p><strong>Price:</strong> $' . $row['price'] . '</p>
                                    <a href="#" class="btn btn-primary">Buy Now</a>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                echo "<p class='text-center'>No products available.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>
