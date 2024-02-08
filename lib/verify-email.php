<?php
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

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT id FROM login WHERE token = ? AND status = 'inactive'";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare statement failed: " . $conn->error);
    }

    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Update user status and clear token
        $updateSql = "UPDATE login SET status = 'active', token = '' WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt === false) {
            die("Prepare statement failed: " . $conn->error);
        }

        $updateStmt->bind_param('i', $user_id);
        $updateStmt->execute();

        echo "Email verified successfully! Your account is now active. You can <a href='../login.php'>login</a>.";
    } else {
        echo "Invalid URL.";
    }
} else {
    echo "Token not provided.";
}

// Close the MySQLi connection
$conn->close();
?>
