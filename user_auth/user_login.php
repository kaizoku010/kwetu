<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Force HTTPS on production
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit();
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/db.php';

// Clear any existing session data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset();
    session_regenerate_id(true);
}

// Initialize logout message
$logout_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = '<div class="alert alert-success">You have been successfully logged out.</div>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_logged_in'] = true;
        
        // Use absolute path for redirect
        // header("Location: /user_auth/profile.php");
        header("Location: ../index.php");

        exit();
    }
    $error = "Invalid email or password";
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

  height: 100vh;
  text-align: left !important;
  align-items: center;
  display: flex;
                 background-image: url('../assets/wavy.jpg');
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
  width: 100% !important;
    background-color: #f78b00 !important;
border-color: #f78b00 !important;
          font-size: .8rem;
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

.brand-logo {
  margin-bottom: .8rem;
  width: 10rem;
}
.back-nav {
  margin-top: 0rem;
  width: 25rem;
            font-size: .8rem;

  background-color: transparent;
  align-self: center;
border: 1px solid #f78b00; 
    border-radius: 30px;

  color: white;
  text-decoration: none;
  display: flex;
  justify-content: center;
  margin-top: .8rem;
  padding: .3rem;
}
        

.back-nav:hover{
    color:white;
     background-color: #f78b00 !important;
}

/* Responsive Design */
@media (max-width: 900px) {


    
    #login-btn{
  margin-top: 2rem;
  width: 45%;
  background-color: #f78b00 !important;
  align-self: center;
  border-radius: 30px;
        }

          
  .back-nav {
  margin-top: 0rem;
  width: 100%;
  background-color: transparent;
    border-radius: 30px;

  align-self: center;
  color: white;
  text-decoration: none;
  display: flex;
  justify-content: center;
  margin-top: .8rem;
  padding: .4rem;
}

    .create-account-text{
        text-align: center;
    }
.form-control {
border-radius: 28px;
  margin-top: .5rem;
}
      .user-login {
        height: 100vh;
        flex-direction: column;
        display: flex !important;
        background-image: unset !important;
        background-size: cover;
        background-position: center;
        background-color: black !important;
        background-repeat: no-repeat;
    }

    #left-user-login {
  background-color: transparent;
  height: 100vh;
  width: 70%;
  display: none;
}

#form-side {
  background-color: #80808012;
  height: 100%;
  width: 100%;
}

#user-login-form {
  padding: 2rem;
  width: 100%;
  height: 100vh;
  text-align: left !important;
  align-items: center;
  display: flex;
  background-color: #171515;
  flex-direction: column;
  justify-content: center;
  text-align: left;
}

.login-form {
  width: 20rem;
}

}

    </style>
</head>
<body>

<div class="user-login" id="user-login">
<div id="left-user-login">
</div>
<div id="form-side">
 <div class="" id="user-login-form">
        <img onclick="window.location.href='/'" src="../assets/logo-full.svg" class="brand-logo" alt="Kwetu Logo">
        <!-- ✅ Display Logout Success Message -->
        <?php echo $logout_message; ?>

        <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>

        <form class="login-form" action="user_login.php" method="POST">
            <div style="text-align: left !important;" class="mb-3">
                <label  class="text-left" class="form-label">Email</label>
                <input style="border-radius: 30px;" type="email" class="form-control" name="email" required>
            </div>

            <div style="text-align: left !important;" class="mb-3">
                <label  class="text-left" class="form-label">Password</label>
                <input style="border-radius: 30px;" type="password" class="form-control" name="password" required>
            </div>
<div style="display:flex; flex-direction: column; width: 100%;">
            <button id="login-btn" type="submit" class="btn btn-primary">Login</button>
            <a class="back-nav" href="/">Back</a>

</div>

            <p class="create-account-text text-center" style="margin-top: 1rem; color: white; font-size: small;" >
                Don't have an account? <a style="text-decoration: none; color: #f78b00;" href="user_registration.php">Create Account</a></p>
        </form>
    </div>
</div>
</div>
   

</body>
</html>
