<?php
include './includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auctions - Kwetu Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="d-flex justify-content-between align-items-center mb-4 dixon">
       
    <h2 class="auction-heading">Active & Upcoming Auctions</h2>
      
        <div class="auction-filters">
            <button id="mdx-white" class="btn btn-outline-secondary me-2 filter-btn active" data-filter="all">
                <i class="fas fa-list"></i> All
            </button>
            <button id="mdx-white" class="btn btn-outline-secondary me-2 filter-btn" data-filter="upcoming">
                <i class="fas fa-clock"></i> Upcoming
            </button>
            <button id="mdx-white" class="btn btn-outline-success me-2 filter-btn" data-filter="active">
                <i class="fas fa-gavel"></i> Active
            </button>

             <button id="mdx-white" class="btn btn-outline-success me-2 filter-btn" data-filter="active">
                <i class="fas fa-gavel"></i> Locations
            </button>
            <button id="mdx-white" class="btn btn-danger me-2 filter-btn" data-filter="closed">
                <i class="fas fa-lock"></i> Closed
            </button>
        </div>
    </div>

    <div class="auction-container"> 
        <div class="row justify-content-center">
            <?php
                $isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
                $result = $conn->query("SELECT * FROM auctions") or die("SQL Error: " . $conn->error);

                if ($result->num_rows > 0) {
                    while ($auction = $result->fetch_assoc()) {
                        $now = new DateTime();
                        $start_time = new DateTime($auction['opening_date']);
                        $end_time = new DateTime($auction['closing_date']);
                        
                        if ($now < $start_time) {
                            $status = 'upcoming';
                        } elseif ($now >= $start_time && $now <= $end_time) {
                            $status = 'active';
                        } else {
                            $status = 'closed';
                        }
                        
                        echo '<div class="" data-status="' . $status . '">
                                <a href="auction.php?id=' . $auction['id'] . '" class="auction-card-link">
                                    <div class="auction-card d-flex flex-column flex-md-row align-items-stretch">
                                
                                    <div class="auction-images order-md-1" style="background-image: url(\'' . $auction['image'] . '\'); background-size: cover; background-position: center; min-height: 250px;">
                                        </div>
                                        <div class="auction-box flex-grow-1 text-start">
                                            <h4 class="company-title fw-bold text-black">' . $auction['company_title'] . '</h4>
                                            <p class="mdx "><strong class="dateTime">Opening Date |</strong> ' . $auction['opening_date'] . '</p>
                                            <p class="mdx"><strong class="dateTime">Closing Date |</strong> <span class="text-danger">' . $auction['closing_date'] . '</span></p>
                                            <p class="mdx"><strong id="closing-timer2">Location:</strong> <span id="bid-location" class="text-success">' . $auction['location'] . '</span></p>
                                           <div class="sperator"></div>
                                            <div class="">
                                                <p class="auc-desc">' . $auction['description'] . '</p>
                                            </div>
                                            <p id="closing-timer"><strong>Closing In:</strong> 
                                            <span class="countdown-timer" data-closing="' . $auction['closing_date'] . '"></span></p>';

                        // Admin-only Edit & Delete Buttons
                        if ($isAdmin) {
                            echo '<div class="mt-2">
                                    <a href="edit_auction.php?id=' . $auction['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_auction.php?id=' . $auction['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this auction?\');">Delete</a>
                                  </div>';
                        }

                        echo '</div></div></a></div>';
                    }
                } else {
                    echo '<p class="text-center">No auctions available.</p>';
                }
            ?>
        </div>
    </div>

    <style>
        .auction-box {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .auction-images {
            width: 100%;
            height: auto;
            object-fit: cover;
            display: block;
        }
        .description-box {
            background-color: #d3d3d3;
            border: 1px solid black;
            padding: 5px;
            margin-top: 5px;
            border-radius: 5px;
            font-size: 12px;
        }
        .btn-sm {
            margin-top: 5px;
            width: 48%;
            display: inline-block;
            text-align: center;
        }
        @media (min-width: 768px) {
            .second-image {
        display: none !important;
    }
            .auction-card {
                flex-direction: row;
            }
            .auction-images {
                flex: 0 0 40%;
            }
        }
        .auction-container {
            width: 100%;
            max-width: 1400px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .countdown-timer {
            font-weight: bold;
            color: #8B0000; /* Deep Red */
            font-size: 16px;
            font-family: 'Arial Black', sans-serif;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
    </style>
<!-- JavaScript for Countdown Timer -->
<script>
        document.addEventListener("DOMContentLoaded", function () {
            const timers = document.querySelectorAll(".countdown-timer");

            timers.forEach(timer => {
                const closingTime = new Date(timer.getAttribute("data-closing")).getTime();

                function updateTimer() {
                    const now = new Date().getTime();
                    const timeLeft = closingTime - now;

                    if (timeLeft <= 0) {
                        timer.innerHTML = "<span  class='white font-weight-bold'>Closed</span>";
                    } else {
                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                        timer.innerHTML = `<span class="countdown-timer">${days}d ${hours}h ${minutes}m ${seconds}s</span>`;
                    }
                }

                updateTimer();
                setInterval(updateTimer, 1000);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all filter buttons
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Add click event to each filter button
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Get the filter value
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Get all auction items
                    const auctionItems = document.querySelectorAll('.auction-item');
                    
                    // Show/hide items based on filter
                    auctionItems.forEach(item => {
                        if (filterValue === 'all' || item.getAttribute('data-status') === filterValue) {
                            item.style.display = '';
                            item.style.animation = 'fadeIn 0.5s';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
            
            // Set 'All' as active by default
            document.querySelector('[data-filter="all"]').classList.add('active');
        });
    </script>

</body>
</html>
