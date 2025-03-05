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
    <link rel="stylesheet" href="./css/mobile_styles.css">

   <style>
        /* ===== ✅ Global Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .desktop-menu{
            margin-bottom: -.2rem !important;
        }

     .currency-selector-container {
  margin-right: 15px !important;
  width: 5rem;
  background-color: #f7951d !important;
  border-radius: 4px !important;
  align-self: flex-end !important;
  margin-bottom: 0.4rem;
}


.currency-selector-container {
  margin-right: 15px;
  width: 5rem;
  background-color: #f7951d !important;
  border-radius: 4px !important;
  align-self: flex-end !important;
  margin-bottom: 0.4rem;
}
#globalCurrencySelector {
  background-color: transparent;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 2px 8px;
}

#globalCurrencySelector option {
  background-color: #333;
  color: white;
}


        .currency-selector-container {
            margin-right: 15px;
            width: 5rem;
            background-color: #f7951d !important;
            border-radius: 4px !important;
            align-self: flex-end !important;
margin-bottom: 0.3rem;

}

        #globalCurrencySelector {
            background-color: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
        }

        #globalCurrencySelector option {
            background-color: #333;
            color: white;
        }

        #userDropdown {
            background-color: black !important;
        }

        /* Hide the desktop menu on mobile devices */
        @media screen and (max-width: 992px) {
            .desktop-menu {
                display: none !important;
            }

            .kai-sama {
                display: none !important;
            }

        }

        /* Hide the mobile menu on desktop devices */
        @media screen and (min-width: 993px) {
            .mobile-menu {
                display: none !important;
            }
        }

        */

        /* ===== Mobile Menu Styling (Completely Separate) ===== */
        .mobile-hamburger {
            display: none;
            /* Hidden by default */
            font-size: 24px;
            color: white;
            cursor: pointer;
            z-index: 1100;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .mobile-drawer {
            display: none;
            /* Hidden by default */
            flex-direction: column;
            position: fixed;
            top: 0;
            left: -280px;
            /* Start off-screen */
            width: 280px;
            height: 100vh;
            background-color: rgba(3, 3, 3, 0.95);
            padding: 20px;
            text-align: left;
            transition: left 0.3s ease-in-out;
            z-index: 1050;
        }

        .mobile-drawer.active {
            left: 0;
            /* Slide in from left */
            display: flex;
        }

        .close-drawer {
            display: block;
            font-size: 24px;
            color: white;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
        }

        .drawer-links {
            list-style: none;
            padding: 0;
            margin-top: 50px;
        }

        .drawer-links li {
            margin: 15px 0;
            width: 100%;
        }

        .drawer-links a {
            display: block;
            width: 100%;
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 0;
        }

        .drawer-links a:hover {
            color: #ccc;
        }

        /* Show hamburger on mobile */
        @media screen and (max-width: 992px) {
            .mobile-hamburger {
                display: block;
            }

            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                padding: 1.5rem !important;
                background-color: rgb(3, 3, 3);
                padding: 12px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 1000;
            }

            .desktop-menu {
                display: none !important;

            }
        }

        /* ===== ✅ Navbar Styling ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgb(3, 3, 3);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            width: 7.2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        /* ✅ Navigation Links */
        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: .8rem !important;
            font-weight: bold;
            padding: 8px 12px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: transparent !important;
            border-radius: 0px !important;
            color: gray !important
        }

        /* ✅ Authentication Buttons */
        .auth-buttons {
            display: flex;
            align-items: center;
        }

        .auth-buttons a {
            background-color: white;
            color: #f78b00;
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

        /* ✅ Mobile Menu */
        .hamburger {
            display: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
            z-index: 1100;
            /* Ensure it's above other elements */
        }

        #mobile-links:hover {
            color: gray;
        }

        @media screen and (max-width: 992px) {

            
            .logo-image {
                width: 6rem !important;
                height: auto;
                margin-left: -2rem;
            }

            .nav-links {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: -250px;
                /* Start off-screen */
                width: 250px;
                height: 100vh;
                background-color: rgba(3, 3, 3, 0.95);
                padding: 20px;
                text-align: left;
                transition: left 0.3s ease-in-out;
                justify-content: flex-start;
                align-items: flex-start;
                z-index: 1050;
                /* Ensure it's above other elements */
            }

            .nav-links.active {
                left: 0;
                /* Slide in from left */
            }

            .dx-links.active {
                left: 0;

            }

            .nav-links li {
                margin: 15px 0;
                width: 100%;
            }

            .nav-links a {
                display: block;
                width: 100%;
            }

            .close-menu {
                display: block;
                font-size: 24px;
                color: white;
                cursor: pointer;
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
            }

            .hamburger {
                display: block;
            }
        }

        @media screen and (min-width: 993px) {
            .close-menu {
                display: none;
            }
        }


        #hamburger-btn {
            display: block;
            position: absolute;
            right: 20px;
            top: unset !important;
            background: none;
            border: medium;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            height: fit-content;
        }

        .logo {
            font-weight: bold;
            color: white;

            text-decoration: none;

        }

        .nav-links li {
            margin: 0 0px !important;
        }

        /* dixon's css */
        .nav-container {
            /* background-color: red; */
            display: flex;

            justify-content: end !important;
            flex-direction: column;
        }

        .auth-buttons {
            margin-bottom: 1rem;
            display: flex;
            margin-right: .8rem;
            align-items: center;
            justify-content: end !important;
        }


        .login-button {
            background-color: transparent !important;
            color: white !important;
            font-size: .8rem !important;
        }

        .register-button {
            color: black !important;
            font-size: .8rem !important;

        }

        .register-button:hover {
            color: white !important;
            background-color: black !important;

        }

        .login-button:hover {
            color: gray !important;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <image src="/assets/logo-full.svg" alt="Kwetu Auctions" class="logo-image">
        </a>
        <!-- Simple hamburger button -->
        <button id="hamburger-btn"
            style="display: none;
            position: absolute;
            width: fit-content;
            right: 20px; top: 20px; background: none;
            border: none; color: white; font-size: 24px;
            cursor: pointer;">☰</button>

        <div class="nav-container">
            <!-- Add this before the auth-buttons div -->
            <div class="currency-selector-container">
                <select id="globalCurrencySelector" class="form-select form-select-sm">
                    <option value="UGX">UGX</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <!-- Auth buttons -->
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                    <li class="kai-sama nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            👤                                 <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/user_auth/profile.php">My Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/user_auth/user_logout.php">Logout</a></li>
                        </ul>
                    <?php else: ?>
                        <a class="login-button" href="user_auth/user_login.php">Sign In</a>
                        <a class="register-button" href="/user_auth/user_registration.php">Create Account</a>
                    <?php endif; ?>
            </div>

            <!-- Desktop Menu -->
            <ul class="nav-links desktop-menu">
                <li><a href="/">Home</a></li>
                <li><a href="./auction_guide.php">Auction Guide</a></li>
                <li><a href="./transport_services.php">Transport Services</a></li>
                <li><a href="sell_with_us.php">Sell With Us</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="career.php">Careers</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="./admin/admin_login.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu (simple overlay) -->
    <div id="mobile-menu"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.95); z-index: 1000; padding: 20px;">
        <button id="close-btn"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: white; font-size: 24px; cursor: pointer;">✖</button>

        <ul style="list-style: none; margin-top: 60px; padding: 0; text-align: left;">
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                <li style="margin: 15px 0;">
                    <span style="color: white; font-size: 18px; display: block; margin-bottom: 10px;">
                        👤                             <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <ul style="list-style: none; padding-left: 20px;">
                        <li style="margin: 10px 0;">
                            <a id="mobile-links" href="/user_auth/profile.php"
                                style="color: white; text-decoration: none; font-size: 16px;">My Profile</a>
                        </li>
                        <li style="margin: 10px 0;">
                            <a id="mobile-links" href="/user_auth/user_logout.php"
                                style="color: white; text-decoration: none; font-size: 16px;">Logout</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
            <li style="margin: 15px 0;"><a id="mobile-links" href="index.php"
                    style="color: white; text-decoration: none; font-size: 18px;">Home</a></li>

            <?php if (! isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true): ?>
                <li style="margin: 15px 0;"><a id="mobile-links" href="/user_auth/user_login.php"
                        style="color: white; text-decoration: none; font-size: 18px;">Sign In</a></li>
                <li style="margin: 15px 0;"><a id="mobile-links" href="/user_auth/user_registration.php"
                        style="color: white; text-decoration: none; font-size: 18px;">Register</a></li>
            <?php endif; ?>
            <li style="margin: 15px 0;"><a id="mobile-links" href="auction_guide.php"
                    style="color: white; text-decoration: none; font-size: 18px;">Auction Guide</a></li>
            <li style="margin: 15px 0;"><a id="mobile-links" href="transport_services.php"
                    style="color: white; text-decoration: none; font-size: 18px;">Transport Services</a></li>
            <li style="margin: 15px 0;"><a id="mobile-links" href="sell_with_us.php"
                    style="color: white; text-decoration: none; font-size: 18px;">Sell With Us</a></li>
            <li style="margin: 15px 0;"><a id="mobile-links" href="about_us.php"
                    style="color: white; text-decoration: none; font-size: 18px;">About Us</a></li>
            <li style="margin: 15px 0;"><a id="mobile-links" href="career.php"
                    style="color: white; text-decoration: none; font-size: 18px;">Careers</a></li>
            <li style="margin: 15px 0;"><a id="mobile-links" href="faq.php"
                    style="color: white; text-decoration: none; font-size: 18px;">FAQ</a></li>
            <!-- <li style="margin: 15px 0;"><a id="mobile-links" href="./admin/admin_login.php" style="color: white; text-decoration: none; font-size: 18px;">Admin</a></li> -->
        </ul>
    </div>

    <script>
        // Simple JavaScript that will definitely work
        document.addEventListener('DOMContentLoaded', function () {
            // Check if we're on mobile
            function checkMobile() {
                if (window.innerWidth <= 992) {
                    document.getElementById('hamburger-btn').style.display = 'block';
                    document.querySelector('.desktop-menu').style.display = 'none';
                } else {
                    document.getElementById('hamburger-btn').style.display = 'none';
                    document.querySelector('.desktop-menu').style.display = 'flex';
                }
            }

            // Run on page load
            checkMobile();

            // Run on window resize
            window.addEventListener('resize', checkMobile);

            // Toggle mobile menu
            document.getElementById('hamburger-btn').addEventListener('click', function () {
                document.getElementById('mobile-menu').style.display = 'block';
            });

            document.getElementById('close-btn').addEventListener('click', function () {
                document.getElementById('mobile-menu').style.display = 'none';
            });
        });
    </script>

    <script src="/js/currency-converter.js"></script>
</body>

</html>



