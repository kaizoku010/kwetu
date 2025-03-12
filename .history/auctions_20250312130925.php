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

    


        @media (max-width: 1699px) {
            .auction-container{
                max-width: 1100px !important;
            }
        }

        @media (min-width: 768px) {
            .second-image {
                display: none !important;
            }
            .auction-card {
                flex-direction: row;
            }

            .auction-car {
                display: flex;
                flex-direction: colum;
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
    /* //promo styles */

            .auction-car {
                display: flex;
                flex-direction: colum;
            }

            .promo-details{
                text-align: left !important;
                display: flex;
                background-color: white;
                flex-direction: column;
                padding: 2rem;
                justify-content: space-between;
            }


            .promo-details > p{
                font-size: small;
                color: gray;
            }

            .mdx-promos-image img{
                width: 45rem !important;
                height: fit-content;
            } 

          .mdx-promos{
            margin-bottom: 2rem;
            width: 100% !important;
           }

        .promo-text{
            text-align: left;
        }

        .promo-title{

    font-size: 2rem !important;
    font-weight: bolder !important;
        }

        .promo-link{
            text-decoration: none;
            /* color: #f7951d; */
            font-size: large;
            font-weight: 500;
          color: black;
            text-transform: uppercase;
        }

        .promo-link:hover{
            color: #f7951d;
        }

        @media (max-width: 768px) {
            .sidebar {
        display: none !important;
    }
            /* promo styles */
    .auction-car {
    display: flex;
    flex-direction: column !important;
    }

    .mdx-promos-image img {
    width: 100% !important;
    height: fit-content;
}


.mdx-promos {
    margin-bottom: 1rem !important;
    width: 100% !important;
    background: white !important;
    border-radius: 12px !important;
    padding-top: 1rem !important;
}


.promo-title {
    font-size: 1.2rem !important;
    font-weight: bolder !important;
}

.promo-link {
    text-decoration: none;
    /* color: #f7951d; */
    font-size: medium !important;
    font-weight: 500;
    color: black;
    text-transform: uppercase;
}

.promo-details {
    text-align: left !important;
    display: flex;
    background-color: white;
    flex-direction: column;
    padding: 1rem !important;
    justify-content: space-between;
}
        }
    </style>

    <!-- JavaScript for Infinite Scroll -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let page = 1;
        let loading = false;
        let isRecycling = false;
        let hasMoreItems = true; // We'll keep this true always since we're recycling
        let promoCounter = 0;

        $(document).ready(function() {
            loadAuctions();
            
            // Enhanced infinite scroll with debounce
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if(!loading) { // Remove hasMoreItems check since we always want to load
                        const scrollPosition = $(window).scrollTop() + $(window).height();
                        const triggerPosition = $(document).height() - 200;
                        
                        if(scrollPosition > triggerPosition) {
                            loadAuctions();
                        }
                    }
                }, 100);
            });
        });
        
        function loadAuctions() {
            if(loading) return;
            
            loading = true;
            $('#loading-spinner').removeClass('d-none');
            
            $.ajax({
                url: './fetch_auctions.php',
                type: 'GET',
                data: {
                    page: page,
                    recycling: isRecycling
                },
                dataType: 'json',
                success: function(response) {
                    $('#loading-spinner').addClass('d-none');
                    
                    if(response.auctions && response.auctions.length > 0) {
                        response.auctions.forEach(function(auction, index) {
                            $('#auction-list').append(auction);
                            
                            // After every 3rd auction
                            if((index + 1) % 1 === 0) {
                                // Instead of making another AJAX call, directly insert the promotional card
                                const promoCard = getPromotionalCardHtml(promoCounter);
                                $('#auction-list').append(promoCard);
                                promoCounter++;
                            }
                        });
                        
                        initCountdownTimers();
                        
                        if(response.isLastPage) {
                            isRecycling = true;
                            page = 1;
                        } else {
                            page++;
                        }
                    }
                    loading = false;
                },
                error: function(xhr, status, error) {
                    $('#loading-spinner').addClass('d-none');
                    loading = false;
                    console.error('Failed to load auctions:', error);
                    $('#auction-list').append(
                        '<div class="alert alert-danger">Failed to load more auctions. Please refresh the page.</div>'
                    );
                }
            });
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

        // Add this function to generate promotional card HTML
        function getPromotionalCardHtml(counter) {
            const cards = [
                `<div class="col-md-4 mdx-promos">
                    <div class="auction-car">
                      <div class="auction-image mdx-promos-image">
                            <img src="assets/ad.jpg" alt="Featured Auctions">
                        </div>  
                    <div class="auction-details promo-details">
                            <div class="promo-text">
                            <p style="font-size:small; color:gray; font-weight: 600;">EXPERT INSIGHTS</p>
                            <h4 class="promo-title">ADVERTISE HERE</h4>
                            <p style="font-size:small; color:gray">From the desk of Sam Kwetu</p>
                            </div>

                            <a href="/featured" class="promo-link">Explore Now > </a>
                        </div>
                      
                    </div>
                </div>`,
                `<div class="col-md-4 mdx-promos">
                    <div class="auction-car">
                        <div class="auction-image mdx-promos-image">
                            <img src="assets/ad.jpg" alt="Featured Auctions">
                        </div>
                    <div class="auction-details promo-details">
                            <div class="promo-text">
                            <p style="font-size:small; color:gray; font-weight: 600;">EXPERT INSIGHTS</p>
                            <h4 class="promo-title">ADVERTISE HERE</h4>
                            <p style="font-size:small; color:gray">From the desk of Sam Kwetu</p>
                            </div>

                            <a href="/featured" class="promo-link">Explore Now > </a>
                        </div>
                    
                    </div>
                </div>`,
                `<div class="col-md-4 mdx-promos">
                    <div class="auction-car">
                        <div class="auction-image mdx-promos-image">
                            <img src="assets/ad.jpg" alt="Featured Auctions">
                        </div>
                 <div class="auction-details promo-details">
                            <div class="promo-text">
                            <p style="font-size:small; color:gray; font-weight: 600;">EXPERT INSIGHTS</p>
                            <h4 class="promo-title">ADVERTISE HERE</h4>
                            <p style="font-size:small; color:gray">From the desk of Sam Kwetu</p>
                            </div>

                            <a href="/featured" class="promo-link">Explore Now > </a>
                        </div>
                    
                    </div>
                </div>`
            ];
            
            return cards[counter % 3];
        }
    </script>
</body>
</html>
