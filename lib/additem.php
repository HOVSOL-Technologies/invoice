<?php

class itemDatabase {
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

    public function insertitem($item, $price, $status) {
        // Check if the email already exists
        if ($this->isitemExists($item)) {
            return ['type' => 'error', 'msg' => 'item already exists.'];
        }else{
            $stmt = $this->conn->prepare("INSERT INTO items (item_name, price_units , item_status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $item, $price, $status);
            $stmt->execute();
            return ['type' => 'success', 'msg' => 'Item added successfully!'];
    }
}
    
    private function isitemExists($item) {
        $count = 0; // Initialize count variable
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM items WHERE item_name = ?");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
    public function getitemData() {
        $sql = "SELECT * FROM items";
        $result = $this->conn->query($sql);
        $itemData = array();
    
        while ($row = $result->fetch_assoc()) {
            $itemData[] = $row;
        }
    
        return $itemData;
    }
    public function updateitem( $item, $price, $status, $id) {
        // Check if the email already exists
        /*if ($this->isEmailExists($email)) {
            return ['type' => 'error', 'msg' => 'item already exists.'];
        }else{*/
            $stmt = $this->conn->prepare("UPDATE items SET item_name = ?, price_units = ?, item_status = ? WHERE id = ?") ;
            $stmt->bind_param("ssss",  $item, $price, $status, $id);
            $stmt->execute();
            return ['type' => 'success', 'msg' => 'Item updated successfully!'];
    //}

   
  
}
public function deleteitem($id) {
    // Check if the email already exists
    /*if ($this->isEmailExists($email)) {
        return ['type' => 'error', 'msg' => 'item already exists.'];
    }else{*/
        $stmt = $this->conn->prepare("DELETE FROM items WHERE id='$id'") ;
        $stmt->execute();
        return ['type' => 'success', 'msg' => 'Item deleted successfully!'];
//}



}
public function get_item_option_list($id=''){
    $result = $this->conn->query("SELECT id,item_name FROM items");
    if($result->num_rows>0){
        while($data = $result->fetch_object()){
            ?>
            <option value="<?php echo $data->id; ?>" <?php echo $id==$data->id?'selected':''; ?>><?php echo $data->item_name; ?></option>
            <?php
        }
    }
}
public function get_item_details($id){
    $id = $this->conn->real_escape_string($id);
    if(!empty($id)){
        $result = $this->conn->query("SELECT * FROM items WHERE id=$id");
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
}
