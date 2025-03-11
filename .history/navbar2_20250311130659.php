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
    /* Collapsed Sidebar Styles */
    .sidebar {
        width: 250px;
        transition: all 0.3s ease;
        position: fixed;
        height: 100vh;
        overflow-x: hidden;
    }

    .sidebar.collapsed {
        width: 60px;
    }

    /* Hide text when collapsed */
    .sidebar.collapsed .nav-link span,
    .sidebar.collapsed h5,
    .sidebar.collapsed .hover-dropdown ul {
        display: none;
    }

    /* Center icons when collapsed */
    .sidebar.collapsed .nav-link {
        text-align: center;
        padding: 10px 5px;
    }

    /* Toggle button styles */
    .collapse-toggle {
        position: absolute;
        right: -20px;
        top: 20px;
        background: #343a40;
        border: none;
        color: white;
        padding: 8px;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        z-index: 1001;
    }

    /* Responsive behavior */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -250px;
        }
        .sidebar.collapsed {
            margin-left: 0;
            width: 250px;
        }
    }
</style>

<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar fixed-sidebar">
    <div class="position-sticky">
        <!-- Collapse Button -->
        <button class="navbar-toggler text-white" type="button" id="sidebarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

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
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.querySelector('.collapse-toggle');
        
        // Restore the state on page load
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }
        
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    });
</script>
