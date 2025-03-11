
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

<style>

    .admin_login_page{
        background-image: url('../assets/wavy.jpg');
        background-color: black;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
          padding-left: 10rem;
       padding-right: 10rem;
    }

    

  .brand-logo2 {
  width: 10rem;
  margin-bottom: 3rem;
  
}


  .admin_login_form {
  background-color: transparent !important;
  color: white !important;
  display: flex;
  flex-direction: column;
}

.mdx-label{
    text-align: left !important;
}

.form-control {
  border-radius: 30px;
  width: 20rem;
}

.mdx-btn {
  background-color: #f78b00 !important;
  border-color: #f78b00 !important;
  border-radius: 30px;

    width: 20rem;

}

.admin_form{
    width: fit-content;
    align-self: center;
}

@media (max-width: 900px) {
 .form-control .mdx-btn {
  width: 100%;
}

.mdx-btn {
  background-color: #f78b00 !important;
  border-color: #f78b00 !important;
  border-radius: 30px;
  width: 100%; 

}

  .brand-logo2 {
  width: 10rem;
  margin-bottom: 3rem;
  align-self: center;
  margin-left: -5.8rem;
 }
}

</style>
<body>

<div class="admin_login_page">
   <div class="container mt-5 admin_login_form">
        <!-- <h2 class="text-center">Admin Login</h2> -->
        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
        <form class="admin_form" action="admin_login.php" method="POST">
        <img src="../assets/logo-full.svg" class="brand-logo2" alt="Kwetu Logo"/>

        <div class="mb-3 mdx-label">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3 mdx-label">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary mdx-btn">Login</button>
        </form>
    </div>

</div>

 

</body>
</html>
