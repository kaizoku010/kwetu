<?php
include '../includes/db.php';

$alterQueries = [
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS starting_time TIME",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS closing_time TIME",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS bank_name VARCHAR(255)",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS account_number VARCHAR(50)",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS swift_code VARCHAR(50)",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS payment_deadline VARCHAR(100)",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS how_to_pay TEXT",
    "ALTER TABLE auctions ADD COLUMN IF NOT EXISTS second_image VARCHAR(255)"
];

foreach ($alterQueries as $query) {
    if ($conn->query($query)) {
        echo "Successfully executed: $query<br>";
    } else {
        echo "Error executing: $query - " . $conn->error . "<br>";
    }
}

echo "Table update complete!";
?>