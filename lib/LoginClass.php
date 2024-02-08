<?php

class LoginClass {
    private $dbHost = "localhost";
    private $dbUser = "root";
    private $dbPass = "abcd";
    private $dbName = "client_management";
    private $port = 3307;
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->port);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }   
    public function getStatus($username) {
            // Assuming 'users' is your table name
        $sql = "SELECT status FROM login WHERE username = ?";
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();

        $stmt->close(); // Close the statement

        return $status;
    }


    
    // Other methods...
    public function login($username, $password) {
        $query = "SELECT * FROM login WHERE username = ?";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
    
        // Bind parameters and execute
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            // Verify the password against the hash in the database
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username; // Assuming session_start() has been called at the beginning of your script
                return true;
            } else {
                return false; // Password does not match
            }
        } else {
            return false; // No user found
        }
    }
    

    public function logout() {
        session_destroy();
        header('Location: login.php');
    }

    public function isLoggedIn() {
        return isset($_SESSION['username']);
    }
    

    public function closeConnection() {
        $this->conn->close();
    }
}