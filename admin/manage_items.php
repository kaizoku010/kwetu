<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    <title>Manage Items - Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .table img {
            max-width: 100px;
            height: auto;
        }
        .filters {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'navbar_admin.php'; ?>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content-wrapper">
        <h2>Manage Auction Items</h2>
        
        <!-- Filters -->
        <div class="filters">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-control" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php
                        $categories = $conn->query("SELECT DISTINCT category FROM auction_items ORDER BY category");
                        while ($cat = $categories->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($cat['category']) . "'>" . 
                                 htmlspecialchars($cat['category']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="companyFilter">
                        <option value="">All Companies</option>
                        <?php
                        $companies = $conn->query("SELECT DISTINCT a.id, a.company_title 
                                                 FROM auctions a 
                                                 JOIN auction_items ai ON a.id = ai.auction_id 
                                                 ORDER BY a.company_title");
                        while ($comp = $companies->fetch_assoc()) {
                            echo "<option value='" . $comp['id'] . "'>" . 
                                 htmlspecialchars($comp['company_title']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search items...">
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions mb-3">
            <button id="selectAll" class="btn btn-secondary">Select All</button>
            <button id="deleteSelected" class="btn btn-danger">Delete Selected</button>
            <button id="deleteAll" class="btn btn-danger">Delete All Items</button>
        </div>

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="masterCheckbox"></th>
                        <th>Image</th>
                        <th>Lot #</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <?php
                    $query = "SELECT ai.*, a.company_title 
                             FROM auction_items ai 
                             JOIN auctions a ON ai.auction_id = a.id 
                             ORDER BY ai.created_at DESC";
                    $result = $conn->query($query);
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="items[]" value="' . $row['id'] . '"></td>';
                        echo '<td><img src="../' . htmlspecialchars($row['image']) . '" alt="Item Image"></td>';
                        echo '<td>' . htmlspecialchars($row['lot_number']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['company_title']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                        echo '<td>$' . number_format($row['price'], 2) . '</td>';
                        echo '<td>
                                <button class="btn btn-sm btn-primary edit-item" data-id="' . $row['id'] . '">Edit</button>
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
        // Filter functionality
        function filterItems() {
            const category = $('#categoryFilter').val();
            const company = $('#companyFilter').val();
            const search = $('#searchInput').val().toLowerCase();

            $('#itemsTableBody tr').each(function() {
                const categoryMatch = !category || $(this).find('td:eq(5)').text() === category;
                const companyMatch = !company || $(this).find('td:eq(4)').text() === company;
                const searchMatch = !search || $(this).text().toLowerCase().includes(search);

                $(this).toggle(categoryMatch && companyMatch && searchMatch);
            });
        }

        $('#categoryFilter, #companyFilter, #searchInput').on('change keyup', filterItems);

        // Select All functionality
        $('#masterCheckbox').change(function() {
            $('input[name="items[]"]').prop('checked', $(this).prop('checked'));
        });

        // Delete Selected Items
        $('#deleteSelected').click(function() {
            if (!confirm('Are you sure you want to delete selected items?')) return;
            
            const selectedItems = $('input[name="items[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedItems.length === 0) {
                alert('Please select items to delete');
                return;
            }

            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { 
                    items: selectedItems,
                    action: 'delete_selected'
                },
                dataType: 'json',
                beforeSend: function() {
                    // Disable delete buttons while processing
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        // Remove deleted rows from the table
                        selectedItems.forEach(function(itemId) {
                            $(`input[name="items[]"][value="${itemId}"]`).closest('tr').fadeOut(400, function() {
                                $(this).remove();
                            });
                        });
                        alert('Items deleted successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while deleting items');
                },
                complete: function() {
                    // Re-enable delete buttons
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', false);
                }
            });
        });

        // Delete Single Item
        $('.delete-single').click(function() {
            if (!confirm('Are you sure you want to delete this item?')) return;
            
            const itemId = $(this).data('id');
            const $row = $(this).closest('tr');

            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { 
                    items: [itemId],
                    action: 'delete_selected'
                },
                dataType: 'json',
                beforeSend: function() {
                    // Disable delete buttons while processing
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(400, function() {
                            $(this).remove();
                        });
                        alert('Item deleted successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while deleting item');
                },
                complete: function() {
                    // Re-enable delete buttons
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', false);
                }
            });
        });

        // Delete All Items
        $('#deleteAll').click(function() {
            if (!confirm('Are you sure you want to delete ALL items? This cannot be undone!')) return;
            
            $.ajax({
                url: 'delete_items.php',
                type: 'POST',
                data: { action: 'delete_all' },
                dataType: 'json',
                beforeSend: function() {
                    // Disable delete buttons while processing
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        // Remove all rows from the table
                        $('#itemsTableBody tr').fadeOut(400, function() {
                            $(this).remove();
                        });
                        alert('All items deleted successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while deleting items');
                },
                complete: function() {
                    // Re-enable delete buttons
                    $('.delete-single, #deleteSelected, #deleteAll').prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>
