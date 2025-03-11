<?php
session_start(); // ✅ Start session

// ✅ Destroy all session variables
$_SESSION = array();

// ✅ Destroy session
session_destroy();

// ✅ Redirect to login page with success message
header("Location: ../user_auth/user_login.php?logout=success"); 
exit();
?>
