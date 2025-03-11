<?php
function getPromotionalCard($position) {
    // You can create different cards based on position number
    switch ($position % 1) {
        case 0:
            return <<<HTML
                <div class="auction-card">
                    <div class="auction-details">
                        <
                        <h4>Featured Auctions</h4>
                        <p>Discover exclusive items and unique finds!</p>
                        <a href="/featured" class="btn btn-primary">Explore Now</a>
                    </div>
                    <div class="auction-image">
                        <img src="assets/promo/featured.jpg" alt="Featured Auctions">
                    </div>
                </div>
            HTML;
        case 1:
            return <<<HTML
                <div class="auction-card">
                    <div class="auction-details">
                        <h4>New Arrivals</h4>
                        <p>Be the first to bid on our latest items!</p>
                        <a href="/new-arrivals" class="btn btn-primary">View Latest</a>
                    </div>
                    <div class="auction-image">
                        <img src="assets/promo/new.jpg" alt="New Arrivals">
                    </div>
                </div>
            HTML;
        case 2:
            return <<<HTML
                <div class="auction-card">
                    <div class="auction-details">
                        <h4>Ending Soon</h4>
                        <p>Last chance to bid on these amazing items!</p>
                        <a href="/ending-soon" class="btn btn-primary">Bid Now</a>
                    </div>
                    <div class="auction-image">
                        <img src="assets/promo/ending.jpg" alt="Ending Soon">
                    </div>
                </div>
            HTML;
    }
}
?>

<style>
/* These styles will complement your existing auction-card styles */
.auction-card.promo-card {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px solid #dee2e6;
}

.auction-card.promo-card .auction-details h4 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.auction-card.promo-card .auction-details p {
    color: #666;
    font-size: 1.1rem;
}

.auction-card.promo-card .btn-primary {
    background-color: #f78b00;
    border: none;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.auction-card.promo-card .btn-primary:hover {
    background-color: #e67a00;
    transform: translateY(-2px);
}
</style>