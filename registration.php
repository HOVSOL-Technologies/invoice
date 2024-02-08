<?php
session_start();

// Include the database connection code here (replace with your actual database details)
$host = "localhost";
$dbname = "client_management";
$username = "root";
$password = "abcd";
$port = "3307"; // Change this to your MySQL port if it's different

// Create a MySQLi connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $usernameEmail = $_POST["usernameEmail"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $time = time();

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<div class='alert alert-danger' role='alert'>Passwords do not match.</div>";
    } else {
        // Check if the user already exists with an "inactive" status and a blank token
        $checkExistingUserSql = "SELECT token FROM login WHERE username = ? AND status = 'inactive' AND token = ''";
        $checkExistingUserStmt = $conn->prepare($checkExistingUserSql);
        $checkExistingUserStmt->bind_param('s', $usernameEmail);
        $checkExistingUserStmt->execute();
        $existingUserResult = $checkExistingUserStmt->get_result();

        if ($existingUserResult->num_rows > 0) {
            // User already registered with blank token, return account already exists
            echo "<div class='alert alert-info' role='alert'>Account already exists. If you haven't received the verification email, you can request it again.</div>";
        } else {
            // Proceed with registration since the user is not registered or the existing token is not valid
            $token = bin2hex(random_bytes(30)); // Generate a 32-character random token

            $sql = "INSERT INTO login (username, password, token, status, timestamp) VALUES (?, ?, ?, 'inactive', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssi', $usernameEmail, $hashedPassword, $token, $time);
            $stmt->execute();

            // Send verification email with the link
            $verification_link = "http://localhost:3000/lib/verify-email.php?token=$token";
            // Your email sending code here, including the $verification_link
            // Email sending code
            $to = $usernameEmail;
            $subject = "Account Verification";
            $message = "Thank you for registering. Please click the following link to verify your account: $verification_link";
            $headers = "From: a@example.com"; // Replace with your email address

            // Check if the email is sent successfully
            if (mail($to, $subject, $message, $headers)) {
            echo "<div class='alert alert-success' role='alert'>Registration successful! Check your email to verify your account.</div>";
            } else {
            echo "<div class='alert alert-danger' role='alert'>Failed to send verification email. Please try again.</div>";
            }
        }
    }
}

// Close the MySQLi connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="company-img">
        <img src="assets/images/Rectanglee.png" alt="company-Icon">
    </div>
    <!--  form started -->
    <div class="registration-container">
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="exampleInputUsername" class="form-label">Username/Email</label>
                <input type="email" name="usernameEmail" class="form-control" id="adminUsernameEmail" placeholder="Enter a valid user name or email" required>
            </div>
            <div class="mb-3 ">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="adminPassword" placeholder="Enter your Password" required>
            </div>
            <div class="mb-3 ">
                <label for="exampleInputPassword1" class="form-label">Confirm-Password</label>
                <input type="password" name="confirmPassword" class="form-control" id="adminconfirmPassword" placeholder="Confirm your Password" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputSecretCode" class="form-label"></label>
                <input type="hidden" name="secretCode" class="form-control" id="exampleInputSecretCode" placeholder="Enter your secret code" required>
            </div>
            <button type="submit" name="submit" class="adminRegister-btn">Submit</button>
        </form>
        <a href="login.php">Login here</a>
    </div>
</body>
</html>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
