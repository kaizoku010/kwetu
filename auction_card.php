<div class="col-lg-4 col-md-6 col-sm-12">
    <a href="auction.php?id=<?= $auction['id'] ?? $row['id'] ?>" class="auction-card-link">
        <div class="auction-card">
            <div class="auction-image">
                <img src="<?= $auction['image'] ?? $row['image'] ?>" alt="Auction Image">
            </div>
            <div class="auction-details">
                <h4 class="company-title"><?= htmlspecialchars($auction['company_title'] ?? $row['company_title']) ?></h4>
                <p><strong>Opening Date:</strong> <?= $auction['opening_date'] ?? $row['opening_date'] ?></p>
                <p><strong>Closing Date:</strong> <span class="text-danger"><?= $auction['closing_date'] ?? $row['closing_date'] ?></span></p>
                <p><strong>Location:</strong> <span class="text-success"><?= htmlspecialchars($auction['location'] ?? $row['location']) ?></span></p>
                <p class="auction-description"><?= htmlspecialchars($auction['description'] ?? $row['description']) ?></p>
                <p id="closing-timer"><strong class="text-darkred">Closing In:</strong> 
                    <span class="countdown-timer text-darkred font-weight-bold" data-closing="<?= $auction['closing_date'] ?? $row['closing_date'] ?>"></span>
                </p>
            </div>
            <span class="btn btn-primary participate-btn">Participate</span>
        </div>
    </a>
</div>
