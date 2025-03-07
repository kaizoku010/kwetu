<?php
session_start();
include '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current user data
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();

// Validate and sanitize input
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<script>alert('Invalid email format.'); window.history.back();</script>");
}

// Validate phone number if provided
if (!empty($phone) && !preg_match("/^[0-9+\-\s()]{10,15}$/", $phone)) {
    die("<script>alert('Please enter a valid phone number.'); window.history.back();</script>");
}

// Check if email is already taken by another user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    die("<script>alert('Email already taken by another user.'); window.history.back();</script>");
}

// Handle password update if provided
if (!empty($new_password)) {
    if ($new_password !== $confirm_password) {
        die("<script>alert('New passwords do not match.'); window.history.back();</script>");
    }
    if (strlen($new_password) < 6) {
        die("<script>alert('Password must be at least 6 characters long.'); window.history.back();</script>");
    }
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update with new password
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $phone, $hashed_password, $user_id);
} else {
    // Update without changing password
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $phone, $user_id);
}

if ($stmt->execute()) {
    header("Location: profile.php?update=success");
} else {
    die("<script>alert('Error updating profile. Please try again.'); window.history.back();</script>");
}

$stmt->close();
$conn->close();
?> 