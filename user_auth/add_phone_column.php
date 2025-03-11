<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/db.php';

try {
    // Add phone column if it doesn't exist
    $check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'phone'");
    
    if ($check_column->num_rows == 0) {
        $alter_query = "ALTER TABLE users ADD COLUMN phone VARCHAR(15) DEFAULT NULL";
        if ($conn->query($alter_query)) {
            echo "Successfully added phone column to users table";
        } else {
            throw new Exception("Failed to add column: " . $conn->error);
        }
    } else {
        echo "Phone column already exists in users table";
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$conn->close();
?>
