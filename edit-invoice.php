<?php

session_start();
require_once 'lib/LoginClass.php';

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}
include_once("lib/indexclass.php");
include_once("lib/customer.php");
include_once("lib/additem.php");
// Create an instance of the ClientDatabase class
$databaseindex = new ClientDatabaseindex(); 
$databaseitem = new itemDatabase();
$database = new ClientDatabase();  
$id = isset($_GET['id'])? $_GET['id']:'';
if (isset($_POST["updateinvoice"])) {
    // Assuming the form data is submitted via POST

    // Retrieve form data
    
    $project_name = $_POST["projectName"];
    $payment_status = $_POST["paymentStatus"];
    $cost = $_POST["totalCost"];
    $date = $_POST["date"];
    $due_date = $_POST["dueDate"];
    $subtotal = $_POST["subtotal"];
    $tax = $_POST["taxes"];
    $total = $_POST["alltotal"];
    $cust_id = $_POST["cust_id"];
    $gst = $_POST["gst"];
    // Insert data into the database
    $i = 0;
	$items = [];
	foreach($_POST['product'] as $item){
		$items[] = [
            'product'=>$item,
            'quantity'=>$_POST['quantity'][$i],
            'price'=>$_POST['price'][$i],
            'discount'=>$_POST['discount'][$i],
            'totalPrice'=>$_POST['totalPrice'][$i]
        ];
		$i++;
	}
	$items = json_encode($items);
    $result = $databaseindex->updateinvoice($cust_id ,$project_name , $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $invoice_status, $id);
}
$invoice_details = $databaseindex->get_invoice_details($id);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- DataTables Select CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">
    
    <!-- add google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <!--Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
<div id="create-invoice-popup-view" class="create-invoice-popup-view">
    
    <div class="create-invoice-popup-content-view">
        <?php  
        if (isset($result['type'])){
            if($result['type'] === 'success'){
                echo '<div style="color: green;">' . $result['msg'] . '</div>';
            } else {
                echo '<div style="color: red;">' . $result['msg'] . '</div>';
            }
        }
        ?>
        <div class="im-create-container">

            <form id="invoiceForm-view" method="POST">
                <div class="im-create-container-main">
                    <div class="im-create-container-row1">
                    <div class="im-create-container-row1-col1" style="background-color: #964FA7;">
                        <h2>INVOICE</h2>
                        </div>
                        <div class="im-create-container-row1-col2">

                        <div class="im-create-container-row1-col2-row0">
                        <div class="im-bill-to"><h3>Bill To:</h3></div>
                        <div class="im-bill-from"><h3>Bill From:</h3></div>
                        </div>
                        <div class="im-create-container-row1-col2-row1">

                            <div class="invoiceForm-row1-col1">
                                <div class="invoiceForm-row1-col1-left">

                                <div class="customer-img-upload-container">
                                    <label for="fileInput" class="custom-file-label">
                                    <img class="customer-img" src="assets\images\image-line.svg" alt="">
                                    <img id="uploadedImage" class="uploaded-image" alt="">
                                    </label>
                                    <input type="file" id="fileInput" class="file-upload-client">
                                </div>

                                </div>
                                <div class="invoiceForm-row1-col1-right-client-box">
                                            
                                    <select id="select-customer" name="cust_id">
                                        <option value="">Select Customer</option>
                                        <?php $database->get_customer_option_list($invoice_details->cust_id);?>
                                    </select>

                                    <!-- <button id="client-select-btn" type="submit">Submit Order</button> -->
                                    <!--<button type="button" id="newCustomerButton">+ New Customer</button>-->

                                </div>
                               <!-- <div class="invoiceForm-row1-col1-right">                 
                                    <input type="text" id="customerName" name="customerName" placeholder="Enter customer name" required>    
                                    <textarea id="customerAddress" name="address" placeholder="Enter customer address" rows="3" required></textarea>   
                                    <input type="tel" id="customerphoneNumber" name="phoneNumber" placeholder="Enter phone number" required>
                                    <input type="email" id="customeremail" name="email" placeholder="Enter email address" required>     
                                </div>-->      
                                <div class="invoiceForm-row1-col1-right2">
                                    <h2 class="clientNamee"></h2>
                                    <p class="clientAddresss"></p>
                                    <p class="clientNumberr"></p>
                                    <p class="clientEmaill"></p>                         
                                </div>
                                <div class="invoiceForm-row1-col1-righ-cancel-btn"><img src="assets/images/Vector (10).svg" alt="cancel-logo"></div>
                            </div>
                            <div class="invoiceForm-row1-col2">
                            <div class="invoiceForm-row1-col2-left">
                                <div class="company-logo"><img src="assets/images/Rectangle.svg" alt=""></div>
                            </div>

                            <div class="invoiceForm-row1-col2-right">
                                    <h2 class="clientName">Hovsol Technologies</h2>
                                    <p class="clientAddress">Malbazar, South Colony, Pin - 735221</p>
                                    <p class="clientNumber">(+91) 000 000 0000</p>
                                    <p class="clientEmail">hovsoltechnologies@gmail.com</p> 
                                
                            </div>

                            </div>
                        </div>
                        <div class="im-create-container-row1-col2-row2">
                            

                            <div class="invoiceForm-row2-col1">

                            <div class="projectName-container">
                            <label for="projectName">Project Name:</label>
                            <input type="text" id="projectName" name="projectName" placeholder="Enter Project Name" value="<?php echo $invoice_details->project_name; ?>" required>
                            </div>
                            
                            <div class="totalCost-container">
                            <label for="totalCost">GST No. :</label>
                            <input type="text" id="totalCost" name="totalCost" min="0.01" step="0.01" placeholder="Enter GST No." value="<?php echo $invoice_details->cost; ?>" required>
                            </div>

                            <div class="paymentStatus-container">
                            <label for="paymentStatus">Payment Status:</label>
                            <select id="paymentStatus" name="paymentStatus" required>
                                <option value="Pending" <?php echo $invoice_details->payment_status=='Pending'?'selected':""; ?>>Pending</option>
                                <option value="Paid" <?php echo $invoice_details->payment_status=='Paid'?'selected':""; ?>>Paid</option>
                                <option value="Late" <?php echo $invoice_details->payment_status=='Late'?'selected':""; ?>>Late</option>
                            </select>
                            </div>
                            </div>
                            <div class="invoiceForm-row2-col2">

                           
                            
                            <div class="date-container">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" value="<?php echo $invoice_details->date; ?>" required>
                            </div>
                            
                            <div class="dueDate-container">
                            <label for="dueDate">Due Date:</label>
                            <input type="date" id="dueDate" name="dueDate" value="<?php echo $invoice_details->due_date; ?>" required>
                            </div>
                            </div>
                        
                        </div>
                        </div>
                    </div>
                    <div class="im-create-container-row2">
                        <div class="im-create-container-row2-container">
                            <table id="orderTable">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Qty/hour</th>
                                        <th>Price/Unit</th>
                                        <th>Discount(%)</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="im-Tbody">
                                    <?php 
                                        $items = json_decode($invoice_details->items,true);
                                        foreach($items as $item){ ?>
                                            <tr class="im-Trow">
                                        <td>
                                            <select class="productSelect" name="product[]">
                                            <?php $databaseitem->get_item_option_list($item['product']);?>
                                            </select>
                                        </td>
                                        <td><input type="number" class="quantity" name="quantity[]" min="1" value="<?php echo $item['quantity']; ?>"></td>
                                        <td><input type="number" class="price" name="price[]" min="0" step="0.01" value="<?php echo $item['price']; ?>"></td>
                                        <td><input type="number" class="discount" name="discount[]" min="0" max="100" step="1" value="<?php echo $item['discount']; ?>"></td>
                                        <td><input type="number" class="totalPrice" name="totalPrice[]" value="<?php echo $item['totalPrice']; ?>" readonly></td>
                                        <td><button class="remove-row"><img src="assets/images/close-circle-line.svg" alt=""></button></td>
                                    </tr>
                                        <?php
                                        }
                                    ?>
                                    
                                </tbody>
                            </table>
                            <div class="add-item-row">
                                <button id="add-row-btn" type="button">+ Add New Item</button>
                            </div>

                            <div class="im-gst-container">
                                <div class="im-gst-container-left">
                                    <h3>Does your invoice include GST?</h3>
                                </div>
                                <div class="im-gst-container-right">
                                    <label for="gst">Enter GST In %:</label>
                                    <input type="number" id="gst" name="gst" value="<?php echo $invoice_details->gst;?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="im-create-container-row3">
                        <div class="im-create-container-row3-upper">
                            <div class="subtotal-container">
                                <label for="subtotal">Subtotal</label>
                                <input type="text" id="subtotal" name="subtotal" value="<?php echo $invoice_details->subtotal; ?>" readonly>
                            </div>
                            <div class="taxes-container">
                                <label for="taxes">Taxes</label>
                                <input type="text" id="taxes" name="taxes" value="<?php echo $invoice_details->tax; ?>" readonly>
                            </div>
                            <div class="alltotal-container">
                                <label for="alltotal">Total</label>
                                <input type="text" id="alltotal" name="alltotal" value="<?php echo $invoice_details->total; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="im-create-container-row4">

                        <div class="img-upload-container1">
                            <label for="sign-fileInput" class="custom-signature-label">
                                <span class="sign-box">Signature From:</span>
                                <div class="company-signature-box">
                                <!--<img class="file-upload-company-icon" src="assets/images/image-line.svg" alt="">-->
                                <img id="uploaded-company-Signature" class="uploaded-company-signature" src="<?php echo "uploads/invoice/image_sign_from/" .$invoice_details->sign_from; ?>"alt="">
                                </div>
                            </label>
                            <input type="file" id="sign-fileInput" class="company-file-upload">
                        </div>

                        <div class="img-upload-container2">
                            <label for="client-sign-fileInput" class="custom-signature-label">
                                <span class="sign-box">Signature To:</span>
                                <div class="client-signature-box">
                                <!--<img class="file-upload-client-icon" src="assets/images/image-line.svg" alt="">-->
                                <img id="uploaded-client-Signature" class="uploaded-client-signature" src="<?php echo "uploads/invoice/image_sign_to/" .$invoice_details->sign_to; ?>" alt="">
                                </div>
                            </label>
                            <input type="file" id="client-sign-fileInput" class="client-file-upload">
                        </div>


                    </div>
                    <div class="im-create-container-row5">
                        <div class="im-create-container-row5-upper">
                            <h2>Thank You!</h2>
                            <p>"Your payment has given us the funds we need to hire a professional clown to entertain our staff during long meetings. We hope this brings a smile to your face when you think of us!"</p>
                        </div>
                        <div class="im-create-container-row5-lower">
                            
                            <a class="cancel-invoice-btn" href="index.php" ><img src="assets/images/close-line.svg" alt="">Cancel</a>
                            <!-- <button class="share-invoice-btn"><img src="assets/images/share-forward-line.svg" alt="">Share</button>-->
                            <!-- <button class="print-invoice-btn"><img src="assets/images/printer-line.svg" alt="">Print</button>  -->
                            
                            <input type="hidden" name="updateinvoice" id="update-item">
                            <button class="update-invoice-btn"><img src="assets/images/save-3-line.svg"  alt="">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Close button/icon -->
       <a href="index.php"><img class="close-delete-popup-view" src="assets\images\Vector.svg" alt=""></a>
        
    </div>
</div>

<!-- JS -->
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <!-- DataTables JavaScript -->
       <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
       <!-- DataTables Bootstrap JavaScript -->
       <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <!-- popup Bootstrap JavaScript -->
       <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->

       <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>

       <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script> -->
       
   <!-- custom JavaScript -->
       <script src="assets/js/script.js"></script>
         
</body>
</html>
<script>
// JavaScript code
$(document).ready(function() {
    setTimeout(function(){
        $('#select-customer').change();
    },100);
    $('#select-customer').change();
    // Attach onchange event to the select element
    $('#select-customer').on('change', function() {
        // Get the selected option
        var selectedOption = $(this).find(':selected');

        
        // Update the content of the second div based on the selected option
        $('.clientNamee').text(selectedOption.attr('name'));
        $('.clientAddresss').text(selectedOption.data('address'));
        $('.clientNumberr').text(selectedOption.data('phone'));
        $('.clientEmaill').text(selectedOption.data('email'));
        $('.customer-img').attr('src', selectedOption.data('image'));
    });
});


	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>