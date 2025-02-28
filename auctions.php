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
  
  
  <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
.dixon {
  margin-bottom: 20px;
  padding: 10px;
  animation: fadeIn 0.5s ease-out;
  transition: all 0.3s ease;
  margin-left: 18rem;
  margin-right: 18rem;
  display: flex;
  margin-top: 3rem !important;
  justify-content: space-between;
  /* background-color: red; */
  align-items: center;
}


    </style>
</head>
<body>
    <div class="dixon">
       
    <h2 class="auction-heading">Active & Upcoming Auctions</h2>
      
        <div class="auction-filters">
            <button id="mdx-white" class="btn btn-outline-secondary me-2 filter-btn active" data-filter="all">
                <i class="fas fa-list"></i> All
            </button>
            <!-- <button id="mdx-white" class="btn btn-outline-secondary me-2 filter-btn" data-filter="upcoming">
                <i class="fas fa-clock"></i> Upcoming
            </button> -->
            <button id="mdx-white" class="btn btn-outline-success me-2 filter-btn" data-filter="active">
                <i class="fas fa-gavel"></i> Active
            </button>

             <button id="mdx-white" class="btn btn-outline-success me-2 filter-btn" data-filter="active">
                <i class="fas fa-gavel"></i> Locations
            </button>
            <!-- <button id="mdx-white" class="btn btn-danger me-2 filter-btn" data-filter="closed">
                <i class="fas fa-lock"></i> Closed
            </button> -->
        </div>
    </div>

    <div class="auction-container"> 
        <div id="auction-list" class="row justify-content-center">
            <!-- Auctions will be loaded here -->
        </div>
        <div id="loading-spinner" class="text-center mt-4 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
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
        }
        .countdown-timer {
            font-weight: bold;
            color: #8B0000; /* Deep Red */
            font-size: 16px;
            font-family: 'Arial Black', sans-serif;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
    </style>

    <!-- JavaScript for Infinite Scroll and Countdown Timer -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let page = 1;
        let loading = false;
        let currentFilter = 'all';
        let hasMoreAuctions = true;

        // Load initial auctions
        $(document).ready(function() {
            loadAuctions();
            
            // Add scroll event listener
            $(window).scroll(function() {
                if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                    if(!loading && hasMoreAuctions) {
                        loadAuctions();
                    }
                }
            });
            
            // Filter buttons
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                
                currentFilter = $(this).data('filter');
                $('#auction-list').empty();
                page = 1;
                hasMoreAuctions = true;
                loadAuctions();
            });
        });
        
        function loadAuctions() {
            if(loading) return;
            
            loading = true;
            $('#loading-spinner').removeClass('d-none');
            
            $.ajax({
                url: 'fetch_auctions.php',
                type: 'GET',
                data: {
                    page: page,
                    filter: currentFilter
                },
                dataType: 'json',
                success: function(response) {
                    $('#loading-spinner').addClass('d-none');
                    
                    if (response.error) {
                        console.error('Server error:', response.error);
                        $('#auction-list').append('<div class="col-12 text-center"><p>Error: ' + response.error + '</p></div>');
                        return;
                    }
                    
                    if(response.auctions && response.auctions.length > 0) {
                        response.auctions.forEach(function(auction) {
                            $('#auction-list').append(auction);
                        });
                        
                        initCountdownTimers();
                        page++;
                        hasMoreAuctions = response.hasMore;
                    } else {
                        hasMoreAuctions = false;
                        if (page === 1) {
                            $('#auction-list').html('<div class="col-12 text-center"><p>No auctions available.</p></div>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading-spinner').addClass('d-none');
                    console.error('AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                    $('#auction-list').append('<div class="col-12 text-center"><p>Failed to load auctions. Please try again later.</p></div>');
                }
            });
        }
        
        function recycleAuctions() {
            // Clone existing auctions and append them to create illusion of infinite content
            const existingAuctions = $('#auction-list > div').clone();
            
            // Only append if we have auctions to recycle
            if(existingAuctions.length > 0) {
                $('#auction-list').append(existingAuctions);
                initCountdownTimers();
            }
        }
        
        function initCountdownTimers() {
            const timers = document.querySelectorAll(".countdown-timer");

            timers.forEach(timer => {
                if(timer.getAttribute("data-initialized") === "true") return;
                
                const closingTime = new Date(timer.getAttribute("data-closing")).getTime();
                
                function updateTimer() {
                    const now = new Date().getTime();
                    const timeLeft = closingTime - now;

                    if (timeLeft <= 0) {
                        timer.innerHTML = "<span class='white font-weight-bold'>Closed</span>";
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
                timer.setAttribute("data-initialized", "true");
            });
        }
    </script>
</body>
</html>
