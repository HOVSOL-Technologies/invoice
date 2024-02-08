<?php

class ClientDatabaseindex {
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

    public function insertitem($cust_id, $project_name , $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $sign_from, $sign_to, $gst) {
        
            $stmt = $this->conn->prepare("INSERT INTO invoice (cust_id, project_name , payment_status, cost, date, due_date, items, subtotal, tax, total, sign_from, sign_to, gst) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss",$cust_id, $project_name , $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $sign_from, $sign_to, $gst);
            $check_from = getimagesize($_FILES["sign_from_fileInput"]["tmp_name"]);
            $check_to = getimagesize($_FILES["sign_to_fileInput"]["tmp_name"]);
            if ($check_from && $check_to) {
                if ($stmt->execute()) {
                    // Get the last inserted ID
                    $lastInsertedId = $stmt->insert_id;
                    $result = $this->conn->query("SELECT type FROM clients WHERE id=$cust_id");
                    $data= $result->fetch_object();
                    $cust_type=$data->type=='Domestic'?'D':'';
                    $invoice_number=$this->create_inv_no($lastInsertedId,$cust_type);
                    $this->conn->query("UPDATE invoice SET invoice_id = '$invoice_number' WHERE id = $lastInsertedId");
                    
                    $targetDirFrom = "uploads/invoice/image_sign_from/"; // Change this to your desired directory
                    $imageFileTypeFrom = strtolower(pathinfo($_FILES["sign_from_fileInput"]["name"], PATHINFO_EXTENSION));
                    $sign_from = "image_from_" . uniqid() . $lastInsertedId . "." . $imageFileTypeFrom;
                    $destinationPathFrom = $targetDirFrom . $sign_from;
                    
                    if (move_uploaded_file($_FILES["sign_from_fileInput"]["tmp_name"], $destinationPathFrom)) {
                        // Update the "sign-from" column with the new value
                        $updateStmtFrom = $this->conn->prepare("UPDATE invoice SET sign_from = ? WHERE id = ?");
                        $updateStmtFrom->bind_param("si", $sign_from, $lastInsertedId);
                        $updateStmtFrom->execute();
                        $updateStmtFrom->close();
                    } else {
                        return ['type' => 'error', 'msg' => 'Error uploading "sign-from" image!'];
                    }
        
                    // Process the uploaded "sign-to" image
                    $targetDirTo = "uploads/invoice/image_sign_to/"; // Change this to your desired directory
                    $imageFileTypeTo = strtolower(pathinfo($_FILES["sign_to_fileInput"]["name"], PATHINFO_EXTENSION));
                    $sign_to = "image_to_" . uniqid() . $lastInsertedId . "." . $imageFileTypeTo;
                    $destinationPathTo = $targetDirTo . $sign_to;
        
                    if (move_uploaded_file($_FILES["sign_to_fileInput"]["tmp_name"], $destinationPathTo)) {
                        // Update the "sign-to" column with the new value
                        $updateStmtTo = $this->conn->prepare("UPDATE invoice SET sign_to = ? WHERE id = ?");
                        $updateStmtTo->bind_param("si", $sign_to, $lastInsertedId);
                        $updateStmtTo->execute();
                        $updateStmtTo->close();
                            return ['type' => 'success', 'msg' => 'Invoice added successfully!'];
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
            function create_inv_no($id,$cust_type=''){
                $ret_val = '';
                $min_len = 8;
                $len = strlen($id);
                $new_id = '';
                if($len<$min_len){
                    $count = ($min_len-$len);
                    for($i=1;$i<=$count;$i++){
                        $new_id .= '0';
                    }
                    $new_id .= $id;
                }else{
                    $new_id = $id;
                }
                $ret_val = 'HSL'.$cust_type.$new_id;
                return $ret_val;
            }
            public function get_invoice_details($id){
                $id = $this->conn->real_escape_string($id);
                if(!empty($id)){
                    $result = $this->conn->query("SELECT * FROM invoice WHERE id=$id");
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
public function getindexData() {
    $sql = "SELECT inv.id,inv.invoice_id,cl.name,inv.project_name,inv.created_date,inv.due_date,inv.total,inv.payment_status FROM invoice AS inv, clients AS cl WHERE cl.id=inv.cust_id";
    $result = $this->conn->query($sql);
    $indexData = array();

    while ($row = $result->fetch_assoc()) {
        $indexData[] = $row;
    }

    return $indexData;
}

public function delete_invoice($id) {
        $id = $this->conn->real_escape_string($id);
        $result = $this->conn->query("SELECT sign_from, sign_to FROM invoice WHERE id=$id");
        $data = $result->fetch_object();
        unlink("uploads/invoice/image_sign_from/".$data->sign_from);
        unlink("uploads/invoice/image_sign_to/".$data->sign_to);
        if($this->conn->query("DELETE FROM invoice WHERE id='$id'")){
            return ['type' => 'success', 'msg' => 'Invoice deleted successfully!'];
        }else{
            return ['type' => 'error', 'msg' => 'Error: Please try again!'];
        }
}
public function updateinvoice($cust_id, $project_name, $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $gst, $id) {
    $stmt = $this->conn->prepare("UPDATE invoice SET cust_id=?, project_name=?, payment_status=?, cost=?, date=?, due_date=?, items=?, subtotal=?, tax=?, total=?, gst=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $cust_id, $project_name, $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $gst, $id);
    $stmt->execute();

    $change_image_from = isset($_FILES["sign_from_fileInput"]) ? $_FILES["sign_from_fileInput"]["tmp_name"] : null;
    if ($change_image_from !== null && $change_image_from !== "") {
        $result = $this->conn->query("SELECT sign_from FROM invoice WHERE id=$id");
        if ($result) {
            $data = $result->fetch_object();
            unlink("uploads/invoice/image_sign_from/" . $data->sign_from);
            
            $targetDirFrom = "uploads/invoice/image_sign_from/";
            $imageFileTypeFrom = strtolower(pathinfo($_FILES["sign_from_fileInput"]["name"], PATHINFO_EXTENSION));
            $sign_from = "image_from_" . uniqid() . $id . "." . $imageFileTypeFrom;
            $destinationPathFrom = $targetDirFrom . $sign_from;

            if ($_FILES["sign_from_fileInput"]["error"] == UPLOAD_ERR_OK && move_uploaded_file($_FILES["sign_from_fileInput"]["tmp_name"], $destinationPathFrom)) {
                // Update the "sign-from" column with the new value
                $updateStmtFrom = $this->conn->prepare("UPDATE invoice SET sign_from = ? WHERE id = ?");
                $updateStmtFrom->bind_param("si", $sign_from, $id);
                $updateStmtFrom->execute();
                $updateStmtFrom->close();
            } else {
                return ['type' => 'error', 'msg' => 'Error uploading "sign-from" image!'];
            }
        } else {
            return ['type' => 'error', 'msg' => 'Error fetching "sign-from" data!'];
        }
    }

    $change_image_to = isset($_FILES["sign_to_fileInput"]) ? $_FILES["sign_to_fileInput"]["tmp_name"] : null;
    if ($change_image_to !== null && $change_image_to !== "") {
        $result = $this->conn->query("SELECT sign_to FROM invoice WHERE id=$id");
        if ($result) {
            $data = $result->fetch_object();
            unlink("uploads/invoice/image_sign_to/" . $data->sign_to);

            $targetDirTo = "uploads/invoice/image_sign_to/";
            $imageFileTypeTo = strtolower(pathinfo($_FILES["sign_to_fileInput"]["name"], PATHINFO_EXTENSION));
            $sign_to = "image_to_" . uniqid() . $id . "." . $imageFileTypeTo;
            $destinationPathTo = $targetDirTo . $sign_to;

            if ($_FILES["sign_to_fileInput"]["error"] == UPLOAD_ERR_OK && move_uploaded_file($_FILES["sign_to_fileInput"]["tmp_name"], $destinationPathTo)) {
                // Update the "sign-to" column with the new value
                $updateStmtTo = $this->conn->prepare("UPDATE invoice SET sign_to = ? WHERE id = ?");
                $updateStmtTo->bind_param("si", $sign_to, $id);
                $updateStmtTo->execute();
                $updateStmtTo->close();
                return ['type' => 'success', 'msg' => 'Invoice updated successfully!'];
            } else {
                return ['type' => 'error', 'msg' => 'Error uploading "sign-to" image!'];
            }
        } else {
            return ['type' => 'error', 'msg' => 'Error fetching "sign-to" data!'];
        }
    }

    return ['type' => 'success', 'msg' => 'Invoice updated successfully!'];
}

}