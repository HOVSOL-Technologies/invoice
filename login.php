<?php
session_start();
require_once 'lib/LoginClass.php';

$user = new LoginClass();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usernameEmail'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        // Check if the user's status is 'active' before allowing login
        if ($user->getStatus($username) === 'active') {
            header('Location: index.php');
        } else {
            $error = 'Your account is not active. Please verify your email.';
        }
    } else {
        $error = 'Invalid username or password';
    }
    

    echo "<div class='alert alert-danger' role='alert' style='color: red;'>$error</div>";

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div>
        <div class="company-img">
        <img src="assets/images/Rectanglee.png" alt="company-Icon">
    </div>
    <div class="login-container">
            <form action="" method="POST" autocomplete="off">
            <div class="mb-3" >
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="text"  name="usernameEmail" class="form-control" id="adminUsernameEmail" aria-describedby="emailHelp" placeholder="Enter a valid user name or email" required>
            </div>
            <div class="mb-3 ">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="adminPassword" placeholder="Enter password" required>
            </div>
            <button type="submit" name="submit" class="adminLogin-btn">Login</button>
            </form>
          
            <a href="registration.php">Register</a><br>
    </div>
   
</body>
</html>
<script>
   if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
