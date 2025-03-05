
<?php 
include '../includes/db.php';
session_start();

// ✅ Handle Registration Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Check if email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email already exists. Please login.";
    } else {
        // ✅ Securely Hash the Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Insert User into Database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // ✅ Redirect to Login Page After Successful Registration
            header("Location: user_login.php?registered=success");
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../css/mobile_styles.css">


    
</head>
<body>
<div class="mdx-reg-section">
<!-- form side -->
<div class="container mt-5 mdx-reg">
 <img onclick="window.location.href='/'" src="../assets/logo-full.svg" class="brand-logo-reg" alt="Kwetu Logo">
  <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
  <form action="user_registration.php" method="POST">
  <div class="mb-3 mdx-margins">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-success mdx-btn-reg">Register</button>
            <button onclick="window.location.href='/'" class="btn btn-success mdx-btn-bck">Back Home</button>

        </form>
        <p class="text-center mt-3">Already have an account? <a style="color: #f78b00; text-decoration: none;" href="user_login.php">Login here</a></p>
</div>   

<!-- mdx filler -->
<div class="mdx-filler"></div>

</div>



</body>
</html>
