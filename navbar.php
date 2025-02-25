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
            background-color: rgb(3, 3, 3);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .logo {
            font-size: 22px;
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

        /* ✅ Mobile Menu */
        .hamburger {
            display: none;
            font-size: 24px;
            color: white;
            cursor: pointer;
        }

        @media screen and (max-width: 992px) {
            .nav-links {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100vh;
                background-color: rgba(3, 3, 3, 0.95);
                padding: 20px;
                text-align: left;
                transition: left 0.3s ease-in-out;
                justify-content: flex-start;
                align-items: flex-start;
            }

            .nav-links.active {
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

        .nav-links li {
        margin: 0 0px !important;
        }

        /* dixon's css */
        .nav-container{
            /* background-color: red; */
            display: flex;

            justify-content: end !important;
            flex-direction: column;
        }

        .auth-buttons {
            margin-bottom:1rem;
            display: flex;
            margin-right: .8rem;
            align-items: center;
            justify-content: end  !important;
         }


        .login-button{
        background-color: transparent !important;
        color:white !important;
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
    <a href="index.php" class="logo">
    <image src="assets/logo-full.png" alt="Kwetu Auctions" class="logo-image">
    </a>
    
    <div class="hamburger" onclick="toggleMenu()">☰</div>
    

  <div class="nav-container">

   <div class="auth-buttons">
        <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
            <a href="user_auth/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="user_auth/user_logout.php">Logout</a>
        <?php else: ?>
            <a class="login-button" href="user_auth/user_login.php">Sign In</a>
            <a class="register-button" href="user_auth/user_registration.php">Create Account</a>
        <?php endif; ?>
    </div>

  <ul class="nav-links">
        <button class="close-menu" onclick="toggleMenu()">✖</button>
        <li><a href="index.php">Home</a></li>
        <li><a href="auction_guide.php">Auction Guide</a></li>
        <li><a href="transport_services.php">Transport Services</a></li>
        <li><a href="sell_with_us.php">Sell With Us</a></li>
        <li><a href="about_us.php">About Us</a></li>
        <li><a href="career.php">Careers</a></li>
        <li><a href="faq.php">FAQ</a></li>
        <li><a href="./admin/admin_login.php">Admin</a></li>
    </ul>
    </div>
  
</nav>

<script>
    function toggleMenu() {
        var nav = document.querySelector(".nav-links");
        nav.classList.toggle("active");
    }

    document.addEventListener("click", function(event) {
        var nav = document.querySelector(".nav-links");
        var hamburger = document.querySelector(".hamburger");
        
        if (!nav.contains(event.target) && !hamburger.contains(event.target)) {
            nav.classList.remove("active");
        }
    });
</script>

</body>
</html>