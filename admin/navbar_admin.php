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
    
    <!-- ✅ Custom CSS for Navbar -->
    <style>
        /* ===== ✅ Global Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== ✅ Navbar Styling ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color:rgb(3, 3, 3); /* Blue */
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .navbar .nav-links {
            list-style: none;
            display: flex;
        }

        .navbar .nav-links li {
            margin: 0 15px;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .navbar .nav-links a:hover {
            color: #f8f9fa; /* Lighter shade */
        }

        /* ✅ Authentication Buttons */
        .auth-buttons a {
            background-color: white;
            color: #007bff;
            padding: 8px 12px;
            margin-left: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .auth-buttons a:hover {
            background-color: #f8f9fa;
        }

        /* ===== ✅ Responsive Navbar ===== */
        .hamburger {
            display: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: #007bff;
                padding: 10px 0;
            }

            .nav-links li {
                margin: 10px 0;
                text-align: center;
            }

            .nav-links.active {
                display: flex;
            }

            .hamburger {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- ✅ Custom Navbar -->
<nav class="navbar">
    <a href="../index.php" class="logo">Kwetu Auctions</a>

    <!-- ✅ Navbar Links -->
    <ul class="nav-links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../auction_guide.php">Auction Guide</a></li>
        <li><a href="../transport_services.php">Transport Services</a></li>
        <li><a href="../sell_with_us.php">Sell With Us</a></li>
        <li><a href="../about_us.php">About Us</a></li>
        <li><a href="../career.php">Careers</a></li>
        <li><a href="../faq.php">FAQ</a></li>
        <li><a href="admin_login.php">Admin</a></li>
    </ul>

    <!-- ✅ Authentication Buttons -->
    <div class="auth-buttons">
        <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
            <a href="user_auth/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="user_auth/user_logout.php">Logout</a>
        <?php else: ?>
            <a href="user_auth/user_login.php">Login</a>
            <a href="user_auth/user_registration.php">Register</a>
        <?php endif; ?>
    </div>

    <!-- ✅ Mobile Menu Toggle -->
    <div class="hamburger" onclick="toggleMenu()">☰</div>
</nav>

<!-- ✅ JavaScript for Mobile Menu -->
<script>
    function toggleMenu() {
        var nav = document.querySelector(".nav-links");
        nav.classList.toggle("active");
    }
</script>

</body>
</html>
