<?php include('./includes/db.php'); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;
?>

<link rel="stylesheet" href="./css/styles.css">
<style>
    /* ✅ Dropdown Positioning */
    .hover-dropdown {
        position: relative;
        display: block;
        width: 100%;
    }

    .hover-dropdown a {
        display: block;
        padding: 10px;
        font-size: 16px;
        color: black;
        text-decoration: none;
    }

    /* ✅ Dropdown Background Colors */
    .winning-bids-menu {
        background: #d4edda !important;
        /* Light Green */
        border-left: 4px solid #28a745;
    }

    .losing-bids-menu {
        background: #f8d7da !important;
        /* Light Red */
        border-left: 4px solid #dc3545;
    }

    .active-auctions-menu {
        background: #87CEFA !important;
        /* Sky Blue */
        border-left: 4px solid #007BFF;
    }

    .closed-auctions-menu {
        background: #d6d6d6 !important;
        /* Light Gray */
        border-left: 4px solid #6c757d;
    }

    .upcoming-auctions-menu {
        background:rgb(253, 253, 253) !important;
        /* Brown */
        border-left: 4px solid #5A2D0C;
    }


    .scrollable-menu {
        max-height: 300px;
        overflow-y: auto;
    }

    .hover-dropdown ul {
        display: none;
        position: absolute;
        left: 100%;
        /* Open to the right */
        top: 0;
        background: white;
        list-style: none;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        min-width: 250px;
        z-index: 1000;
    }

    .hover-dropdown:hover ul {
        display: block;
    }

    .nav-item > li{
        font-size: small !important;
    }

    .hover-dropdown ul li {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }

    .hover-dropdown ul li:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }
    }

    .navbar2-text{}

    /* Add these styles to your existing CSS */
    .sidebar {
        transition: transform 0.3s ease;
        transform: translateX(0);
    }

    .sidebar.collapsed {
        transform: translateX(-80%);
    }

    /* Add collapse button */
    .collapse-toggle {
        position: absolute;
        right: -30px;
        top: 50%;
        transform: translateY(-50%);
        background: #343a40;
        border: none;
        color: white;
        padding: 10px;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        z-index: 1001;
    }

    .collapse-toggle i {
        transition: transform 0.3s ease;
    }

    .sidebar.collapsed .collapse-toggle i {
        transform: rotate(180deg);
    }
</style>

<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar fixed-sidebar">
    <button class="collapse-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left"></i>
    </button>
    <div class="position-sticky">
        
        <h5 style="font-size: medium;" class="text-white text-center mt-3">Auction Dashboard</h5>
        <ul class="nav flex-column mt-4">
            <!-- ✅ Winning Bids Hover Dropdown -->
            <li class="nav-item hover-dropdown">
                <a class="nav-link text-white" href="#">🏆 Winning Bids</a>
                <ul id="winning-bids-menu" class="winning-bids-menu scrollable-menu p-2 rounded"></ul>
            </li>

            <!-- ✅ Losing Bids Hover Dropdown -->
            <li class="nav-item hover-dropdown">
                <a class="nav-link text-white" href="#">❌ Losing Bids</a>
                <ul id="losing-bids-menu" class="losing-bids-menu scrollable-menu p-2 rounded"></ul>
            </li>

            <!-- ✅ Active Auctions Dropdown (Sky Blue) -->
            <li class="nav-item hover-dropdown">
                <a class="nav-link text-white" href="#">✅ Active Auctions</a>
                <ul id="active-auctions-menu" class="active-auctions-menu scrollable-menu p-2 rounded"></ul>
            </li>

            <!-- ✅ Closed Auctions Dropdown (Gray) -->
            <li class="nav-item hover-dropdown">
                <a class="nav-link text-white" href="#">📁 Closed Auctions</a>
                <ul id="closed-auctions-menu" class="closed-auctions-menu scrollable-menu p-2 rounded"></ul>
            </li>

            <!-- ✅ Upcoming Auctions Dropdown (Brown) -->
            <li class="nav-item hover-dropdown">
                <a class="nav-link text-white" href="#">📅 Upcoming Auctions</a>
                <ul id="upcoming-auctions-menu" class="upcoming-auctions-menu scrollable-menu p-2 rounded"></ul>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="finance.php">💰 Finance</a>
            </li>

        </ul>

        <!-- Include the Image Slider Below the Navigation -->
        <div class="mt-4">
            <?php include 'header_slide.php'; ?>
        </div>
    </div>
</nav>

<!-- ✅ JavaScript to Load Winning, Losing, Active, Closed & Upcoming Auctions on Hover -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let userId = <?php echo json_encode($user_id); ?>;

        function fetchBids(type, menuId) {
            if (!userId) {
                document.getElementById(menuId).innerHTML = `
                    <li class='list-group-item text-center'>
                        <a href='user_auth/user_login.php' class='navbar2-text'>
                            Please login to view your ${type} bids
                        </a>
                    </li>`;
                return;
            }

            console.log(`Fetching ${type} bids for user ${userId}`);
            fetch("fetch_bids.php?type=" + type + "&user_id=" + userId)
                .then(response => {
                    console.log('Response:', response);
                    return response.text();
                })
                .then(data => {
                    console.log('Data received:', data);
                    document.getElementById(menuId).innerHTML = data;
                })
                .catch(error => console.error("Error fetching bids:", error));
        }

        function fetchData(endpoint, menuId) {
            fetch(endpoint)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(menuId).innerHTML = data;
                })
                .catch(error => console.error(`Error fetching ${endpoint}:`, error));
        }

        document.querySelector(".hover-dropdown:nth-child(1)").addEventListener("mouseover", function () {
            fetchBids("winning", "winning-bids-menu");
        });

        document.querySelector(".hover-dropdown:nth-child(2)").addEventListener("mouseover", function () {
            fetchBids("losing", "losing-bids-menu");
        });

        document.querySelector(".hover-dropdown:nth-child(3)").addEventListener("mouseover", function () {
            fetchData("fetch_active_auctions.php", "active-auctions-menu");
        });

        document.querySelector(".hover-dropdown:nth-child(4)").addEventListener("mouseover", function () {
            fetchData("fetch_closed_auctions.php", "closed-auctions-menu");
        });

        document.querySelector(".hover-dropdown:nth-child(5)").addEventListener("mouseover", function () {
            fetchData("fetch_upcoming_auctions.php", "upcoming-auctions-menu");
        });
    });
</script>

<script>
</script>
