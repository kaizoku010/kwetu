<?php
include '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Auctions - Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 50%;
        }
    </style>
</head>
<body>
    <?php include 'navbar_admin.php'; ?>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-wrapper">
        <h2>Manage Auctions</h2>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions mb-3">
            <button id="selectAll" class="btn btn-secondary">Select All</button>
            <button id="deleteSelected" class="btn btn-danger">Delete Selected</button>
            <button id="deleteAll" class="btn btn-danger">Delete All Auctions</button>
        </div>

        <!-- Auctions Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="masterCheckbox"></th>
                        <th>Company Title</th>
                        <th>Opening Date</th>
                        <th>Closing Date</th>
                        <th>Location</th>
                        <th>Items Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT a.*, COUNT(ai.id) as items_count 
                             FROM auctions a 
                             LEFT JOIN auction_items ai ON a.id = ai.auction_id 
                             GROUP BY a.id 
                             ORDER BY a.opening_date DESC";
                    $result = $conn->query($query);
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="auctions[]" value="' . $row['id'] . '"></td>';
                        echo '<td>' . htmlspecialchars($row['company_title']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['opening_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['closing_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                        echo '<td>' . $row['items_count'] . '</td>';
                        echo '<td>
                                <button class="btn btn-sm btn-primary edit-auction" data-id="' . $row['id'] . '">Edit</button>
                                <button class="btn btn-sm btn-danger delete-single" data-id="' . $row['id'] . '">Delete</button>
                              </td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Select All functionality
        $('#masterCheckbox').change(function() {
            $('input[name="auctions[]"]').prop('checked', $(this).prop('checked'));
        });

        // Delete Selected Auctions
        $('#deleteSelected').click(function() {
            if (!confirm('Are you sure you want to delete selected auctions? This will also delete all associated items.')) return;
            
            const selectedAuctions = $('input[name="auctions[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedAuctions.length === 0) {
                alert('Please select auctions to delete');
                return;
            }

            deleteAuctions(selectedAuctions);
        });

        // Delete Single Auction
        $('.delete-single').click(function() {
            if (!confirm('Are you sure you want to delete this auction? This will also delete all associated items.')) return;
            
            const auctionId = $(this).data('id');
            deleteAuctions([auctionId]);
        });

        // Delete All Auctions
        $('#deleteAll').click(function() {
            if (!confirm('Are you sure you want to delete ALL auctions? This will also delete all items. This cannot be undone!')) return;
            
            $.ajax({
                url: 'delete_auctions.php',
                type: 'POST',
                data: { action: 'delete_all' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });

        function deleteAuctions(auctionIds) {
            $.ajax({
                url: 'delete_auctions.php',
                type: 'POST',
                data: { 
                    auctions: auctionIds,
                    action: 'delete_selected'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        }
    });
    </script>
</body>
</html>
