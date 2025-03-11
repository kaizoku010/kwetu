<?php
function getPromotionalCard($position) {
    // You can create different cards based on position number
    switch ($position % 3) { // Use modulo to cycle through 3 different card types
        case 0:
            return <<<HTML
                <div class="col-md-4">
                    <div class="card promo-card promo-primary">
                        <div class="card-body text-center">
                            <h4>Featured Auctions</h4>
                            <p>Discover exclusive items!</p>
                            <a href="/featured" class="btn btn-light">Explore Now</a>
                        </div>
                    </div>
                </div>
            HTML;
        case 1:
            return <<<HTML
                <div class="col-md-4">
                    <div class="card promo-card promo-secondary">
                        <div class="card-body text-center">
                            <h4>New Arrivals</h4>
                            <p>Be the first to bid!</p>
                            <a href="/new-arrivals" class="btn btn-light">View Latest</a>
                        </div>
                    </div>
                </div>
            HTML;
        case 2:
            return <<<HTML
                <div class="col-md-4">
                    <div class="card promo-card promo-tertiary">
                        <div class="card-body text-center">
                            <h4>Ending Soon</h4>
                            <p>Last chance to bid!</p>
                            <a href="/ending-soon" class="btn btn-light">Bid Now</a>
                        </div>
                    </div>
                </div>
            HTML;
    }
}
?>

<style>
.promo-card {
    margin: 15px 0;
    transition: transform 0.3s ease;
    color: white;
    border: none;
}

.promo-card:hover {
    transform: translateY(-5px);
}

.promo-primary {
    background: linear-gradient(135deg, #6e8efb, #a777e3);
}

.promo-secondary {
    background: linear-gradient(135deg, #ff9966, #ff5e62);
}

.promo-tertiary {
    background: linear-gradient(135deg, #56ab2f, #a8e063);
}

.promo-card .btn-light {
    color: #333;
    font-weight: bold;
    transition: all 0.3s ease;
}

.promo-card .btn-light:hover {
    transform: scale(1.05);
    background-color: white;
}
</style>