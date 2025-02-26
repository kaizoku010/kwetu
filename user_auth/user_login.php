<?php

session_start(); // ✅ Start session
include '../includes/db.php';
include '../navbar.php';

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

    <style>


        #user-login {
               height: 100vh;
               display: flex !important;
               background-image: url('../assets/bg2.png');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;

        }

        #left-user-login {
            background-color: transparent;
            height: 100vh;
            width: 70%;
        }

        #user-login-form {
  padding: 10rem;
  /* width: 99%; */
  height: 100vh;
  text-align: left !important;
  align-items: center;
  display: flex;
  background-color: #171515;
  flex-direction: column;
  justify-content: center;
  text-align: left;
        }

        

        #form-side {
background-color: #80808012;
            height: 100vh;
            width: 33%;
        }

        #login-btn{
  margin-top: 1rem;
  width: 100%;
  align-self: center;
  border-radius: 30px;
        }

        .text-left{
            color: white;
            font-size: small;
            align-self: flex-start;
        }

        .login-title{
            font-weight: bold;
        }

        .login-form{
width: 25rem;
        }

        .brand-logo{
            margin-bottom: 1rem;
            width: 7rem;
        }

    </style>
</head>
<body>

<div id="user-login">
<div id="left-user-login">
</div>
<div id="form-side">
 <div class="" id="user-login-form">
        <img src="../assets/logo.png" class="brand-logo" alt="Kwetu Logo">
        <!-- ✅ Display Logout Success Message -->
        <?php echo $logout_message; ?>

        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

        <form class="login-form" action="user_login.php" method="POST">
            <div style="text-align: left !important;" class="mb-3">
                <label  class="text-left" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div style="text-align: left !important;" class="mb-3">
                <label  class="text-left" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
<div style="display:flex; flex-direction: column; width: 100%;">
            <button id="login-btn" type="submit" class="btn btn-primary">Login</button>

</div>

            <p style="margin-top: 1rem; color: white; font-size: small;" >Don't have an account? <a style="text-decoration: none; color: #0d6efd;" href="user_registration.php">Create Account</a></p>
        </form>
    </div>
</div>
</div>
   

</body>
</html>
