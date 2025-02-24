<?php

session_start(); // ✅ Start session
include '../includes/db.php';

// ✅ Display Logout Success Message if Redirected
$logout_message = "";
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = "<p class='text-success text-center'>You have successfully logged out.</p>";
}

// ✅ Handle Login Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Check if User Exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // ✅ Verify Password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $id; // ✅ Store user ID in session

            // ✅ Update last_active to track online users
            $conn->query("UPDATE users SET last_active = NOW() WHERE id = " . $_SESSION['user_id']);

            header("Location: ../index.php");
            exit();
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        $error = "No user found with this email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center">User Login</h2>

        <!-- ✅ Display Logout Success Message -->
        <?php echo $logout_message; ?>

        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

        <form action="user_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
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
