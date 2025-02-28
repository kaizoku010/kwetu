
<?php
session_start();
include '../includes/db.php';

// ✅ Check if admin is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php"); // ✅ Updated Redirection
    exit();
}

// ✅ Handle Login Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ✅ Default Admin Credentials
    $admin_user = "admin";
    $admin_pass = "password123";  // Change this password for security!

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php"); // ✅ Updated Redirection
        exit();
    } else {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	 <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">Admin Login</h2>

        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

        <form action="admin_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

</body>
</html>