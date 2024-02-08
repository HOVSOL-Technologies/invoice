<?php

class ClientDatabase {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $port; // Add a property for the port
    private $conn;

    public function __construct(
        $servername = "localhost",
        $username = "root",
        $password = "abcd",
        $dbname = "client_management",
        $port = 3307
    ) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->conn = $this->connect();
    }
    
    private function connect() {
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        return $conn;
    }

    public function insertClient($newImageName, $name, $email, $company, $website, $phone, $address, $city, $state, $postal_code, $country, $type, $currency) {
        // Check if the email already exists
        if ($this->isEmailExists($email)) {
            return ['type' => 'error', 'msg' => 'Email already exists.'];
        }
        // Insert other data into the database
        $stmt = $this->conn->prepare("INSERT INTO clients (image, name, email, company, website, phone, address, city, state, postal_code, country, type, currency) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Check if the image is not provided or the file upload failed
        if (empty($_FILES["fileInput"]["tmp_name"])) {
        // Insert other data into the database
        $newImageName = '';
        $stmt->bind_param("sssssssssssss", $newImageName, $name, $email, $company, $website, $phone, $address, $city, $state, $postal_code, $country, $type,$currency);
        $stmt->execute();
        return ['type' => 'success', 'msg' => 'Client added successfully!'];
        }
        else{
        // Generate a unique name for the image
        $stmt->bind_param("sssssssssssss", $newImageName, $name, $email, $company, $website, $phone, $address, $city, $state, $postal_code, $country, $type,$currency);
        $check = getimagesize($_FILES["fileInput"]["tmp_name"]); 
        if ($check) {
        if ($stmt->execute()) {
            // Get the last inserted ID
            $lastInsertedId = $stmt->insert_id;
    
            // Process the uploaded image
            $targetDir = "uploads/customer/"; // Change this to your desired directory
            $imageFileType = strtolower(pathinfo($_FILES["fileInput"]["name"], PATHINFO_EXTENSION));
    
            // Generate a unique name for the image
            $newImageName = "image_" . uniqid() . $lastInsertedId . "." . $imageFileType;
    
            // Move the uploaded file to the desired directory with the new name
            $destinationPath = $targetDir . $newImageName;
            
           
                if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $destinationPath)) {
                    // Update the image column with the new value
                    $updateStmt = $this->conn->prepare("UPDATE clients SET image = ? WHERE id = ?");
                    $updateStmt->bind_param("si", $newImageName, $lastInsertedId);
                    $updateStmt->execute();
        
                    return ['type' => 'success', 'msg' => 'Client added successfully!'];
                }else{
                    return ['type' => 'success', 'msg' => 'Error uploading image!'];
                }
            
            } else {
                // Return an error message for file upload failure
                return ['type' => 'error', 'msg' => 'Error adding client.'];
            }
        } else {
            // Return an error message
            return ['type' => 'error', 'msg' => 'Please upload a valid image.'];
        }
    }

    }
    public function updateClient( $name, $email, $company, $website, $phone, $address, $city, $state, $postal, $country, $type, $currency, $cust_id) {
        // Check if the email already exists
        /*if ($this->isEmailExists($email)) {
            return ['type' => 'error', 'msg' => 'item already exists.'];
        }else{*/
            $stmt = $this->conn->prepare("UPDATE clients SET name = ?, email = ?, company = ?, website = ?, phone = ?, address = ?, city = ?, state = ?, postal_code = ?, country = ?, type =?, currency=? WHERE id = ?") ;
            $stmt->bind_param("sssssssssssss",  $name, $email, $company, $website, $phone, $address, $city, $state, $postal, $country, $type, $currency, $cust_id);
            $stmt->execute();
            return ['type' => 'success', 'msg' => 'Item updated successfully!'];
    //}
}
    private function isEmailExists($email) {
        $count = 0; // Initialize count variable
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
    public function getCustomerData() {
        $sql = "SELECT * FROM clients";
        $result = $this->conn->query($sql);
        $customerData = array();
    
        while ($row = $result->fetch_assoc()) {
            $customerData[] = $row;
        }
    
        return $customerData;
    }
    public function closeConnection() {
        $this->conn->close();
    }
    
    public function get_customer_option_list($id=''){
        $result = $this->conn->query("SELECT id, name, address, phone, email, image FROM clients");
    
        if($result->num_rows > 0){
            while($data = $result->fetch_object()){
                ?>
               <option value="<?php echo $data->id; ?>"
        name="<?php echo $data->name; ?>"
        data-address="<?php echo $data->address; ?>"
        data-phone="<?php echo $data->phone; ?>"
        data-email="<?php echo $data->email; ?>"
        data-image="<?php echo htmlspecialchars("uploads/customer/" . $data->image); ?>" <?php echo $id== $data->id?'selected':''; ?>
        >
        <?php echo $data->name; ?>
        </option>
    
                <?php
            }
        }
    }
    public function get_customer_details($id){
        $id = $this->conn->real_escape_string($id);
        if(!empty($id)){
            $result = $this->conn->query("SELECT * FROM clients WHERE id=$id");
            if($result->num_rows>0){
                $data = $result->fetch_object();
                return $data;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function delete_invoice($id) {
            $id = $this->conn->real_escape_string($id);
            $result = $this->conn->query("SELECT image FROM clients WHERE id=$id");
            $data = $result->fetch_object();
            unlink("uploads/customer/".$data->image);
            if($this->conn->query("DELETE FROM invoice WHERE id='$id'")){
                return ['type' => 'success', 'msg' => 'Client deleted successfully!'];
            }else{
                return ['type' => 'error', 'msg' => 'Error: Please try again!'];
            }
    }
}

