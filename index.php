<?php


session_start();
require_once 'lib/LoginClass.php';

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}


include_once("includes/header.php");
include_once("lib/indexclass.php");
include_once("lib/customer.php");
include_once("lib/additem.php");
// Create an instance of the ClientDatabase class
$databaseindex = new ClientDatabaseindex(); // Uses default values for localhost, root, abcd, client_management,3307
// Create an instance of the ClientDatabase class
$database = new ClientDatabase();
$databaseitem = new itemDatabase();

// Fetch customer data by passing the database connection
if (isset($_POST["cust_id"])) {
    $cust_id = $_POST["cust_id"];
    $project_name = $_POST["projectName"];
    $payment_status = $_POST["paymentStatus"];
    $cost = $_POST["totalCost"];
    $date = $_POST["date"];
    $due_date = $_POST["dueDate"];
    $subtotal = $_POST["subtotal"];
    $tax = $_POST["taxes"];
    $total = $_POST["alltotal"];
    $gst = $_POST["gst"];
    $sign_from=$sign_to='';
    //$sign_from = $_POST["sign_from"];
    //$sign_to = $_POST["sign_to"];
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
    $result=$databaseindex->insertitem($cust_id, $project_name , $payment_status, $cost, $date, $due_date, $items, $subtotal, $tax, $total, $sign_from, $sign_to, $gst);
    if ($result['type'] === 'success') {
        echo '<div style="color: green;">' . $result['msg'] . '</div>';
        } else {
        echo '<div style="color: red;">' . $result['msg'] . '</div>';
        }
}
if (isset($_POST["deleteitem"])) {
    // Assuming the form data is submitted via POST

    // Retrieve form data
    
    
    $id = $_POST["custid"];
    $result = $databaseindex->delete_invoice($id);
    if ($result['type'] === 'success') {
    echo '<div style="color: green;">' . $result['msg'] . '</div>';
    } else {
    echo '<div style="color: red;">' . $result['msg'] . '</div>';
    }

    
}

$itemdatalist = $databaseindex->getindexData();



?>

<div id="im-datatable-header">
        <div id="im-datatable-left-corner">
            <!-- Text in the left corner -->
            <h3>All Invoice</h3>
        </div>
        <div id="im-datatable-middle">
            <!-- Search field and filter button in the middle -->
            <input type="text" id="search-input" placeholder="Search here...">
            <button id="filter-button"><img src="assets/images/equalizer-line.svg" alt="Logout"></button>
        </div>
        <div id="im-datatable-right-corner">
            <!-- Button with image in the right corner -->
            <button id="create-invoice-button">
                <img src="assets\images\add-line.svg" alt="Button Image">
            Create Invoice</button>
        </div>
</div>
<table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" class="select-all-checkbox"></th>
                <th>Invoice ID</th>
                <th>Customer Name</th>
                <th>Project Name</th>
                <th>Created Date</th>
                <th>Due Date</th>
                <th>Total cost</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($itemdatalist as $itemdata): ?>
            <tr>
                <!-- <td><input type="checkbox" class="select-checkbox"></td> -->
                <td></td>
                <td><?php echo $itemdata['invoice_id']; ?></td>
                <td><?php echo $itemdata['name']; ?></td>
                <td><?php echo $itemdata['project_name']; ?></td>
                <td><?php echo $itemdata['created_date']; ?></td>
                <td><?php echo $itemdata['due_date']; ?></td>
                <td><span class="invoice-status-activee"><?php echo number_format($itemdata['total']); ?></span></td>
                <td><span class="payment-status-paidFull"><?php echo $itemdata['payment_status']; ?></span></td>
                <td>
                    <a href="edit-invoice.php?id=<?php echo $itemdata['id']; ?>" class="view-invoice" title="View"><img src="assets/images/pencil-line.svg" alt="View" class="action-icon"></a>
                    <!-- <a href="#" title="Share"><img src="assets/images/share-forward-fill.svg" alt="Share" class="action-icon"></a> -->
                    <a href="im-pdf.php?id=<?php echo $itemdata['id']; ?>" title="Download"><img src="assets/images/download-2-fill.svg" alt="Download" class="action-icon"></a>
                    <a href="#" class="delete-invoice" title="Delete"><img src="assets/images/delete-bin-6-fill.svg" alt="Delete" class="action-icon"></a>
                </td>
            </tr> 
            
           
            <?php endforeach; ?>   
                     
        </tbody>
        <tfoot>
            <tr>
                <th><input type="checkbox" id="selectAllFooter" class="select-all-checkbox"></th>
                <th>Invoice ID</th>
                <th>Customer Name</th>
                <th>Project Name</th>
                <th>Created Date</th>
                <th>Due Date</th>
                <th>Invoice Status</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

<!-- Popup Form -->
<div id="create-invoice-popup" class="create-invoice-popup">
    <div class="create-invoice-popup-content">
        <div class="im-create-container">

            <form id="invoiceForm" method="POST" enctype="multipart/form-data">
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
                                        <?php $database->get_customer_option_list();?>
                                    </select>

                                    <!-- <button id="client-select-btn" type="submit">Submit Order</button> -->
                                   <!--<button type="button" id="newCustomerButton">+ New Customer</button>-->

                                </div>
                                <div class="invoiceForm-row1-col1-right">                 
                                    <input type="text" id="customerName" name="customerName" placeholder="Enter customer name" >    
                                    <textarea id="customerAddress" name="address" placeholder="Enter customer address" rows="3" ></textarea>   
                                    <input type="text" id="customerphoneNumber" name="phoneNumber" placeholder="Enter phone number" >
                                    <input type="email" id="customeremail" name="email" placeholder="Enter email address" >           
                                </div>
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
                            <input type="text" id="projectName" name="projectName" placeholder="Enter Project Name" required>
                            </div>
                            
                            <div class="totalCost-container">
                            <label for="totalCost">GST No. :</label>
                            <input type="text" id="totalCost" name="totalCost" min="0.01" step="0.01" placeholder="Enter GST NO." >
                            </div>

                            <div class="paymentStatus-container">
                            <label for="paymentStatus">Payment Status:</label>
                            <select id="paymentStatus" name="paymentStatus" required>
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                                <option value="Late">Late</option>
                            </select>
                            </div>
                            </div>
                            <div class="invoiceForm-row2-col2">

                            <div class="invoiceNo-container">
                            <!--<label for="invoiceNo">Invoice No:</label>
                            <input type="text" id="invoiceNo" name="invoiceNo" placeholder="Enter Invoice No:" >-->
                            </div>
                            
                            <div class="date-container">
                            <label for="date">Date:</label>
                            <input type="date" id="date" name="date" required>
                            </div>
                            
                            <div class="dueDate-container">
                            <label for="dueDate">Due Date:</label>
                            <input type="date" id="dueDate" name="dueDate" required>
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
                                    <tr class="im-Trow">
                                        <td>
                                            <select class="productSelect" name="product[]">
                                            <?php $databaseitem->get_item_option_list();?>
                                            </select>
                                        </td>
                                        <td><input type="number" class="quantity" name="quantity[]" min="1" value="1"></td>
                                        <td><input type="number" class="price" name="price[]" min="0" step="0.01" value="0.00"></td>
                                        <td><input type="number" class="discount" name="discount[]" min="0" max="100" step="1" value="0"></td>
                                        <td><input type="number" class="totalPrice" name="totalPrice[]" value="0.00" readonly></td>
                                        <td><button class="remove-row"><img src="assets/images/close-circle-line.svg" alt=""></button></td>
                                    </tr>
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
                                    <input type="number" id="gst" name="gst" value="" >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="im-create-container-row3">
                        <div class="im-create-container-row3-upper">
                            <div class="subtotal-container">
                                <label for="subtotal">Subtotal</label>
                                <input type="text" id="subtotal" name="subtotal" readonly>
                            </div>
                            <div class="taxes-container">
                                <label for="taxes">Taxes</label>
                                <input type="text" id="taxes" name="taxes" readonly>
                            </div>
                            <div class="alltotal-container">
                                <label for="alltotal">Total</label>
                                <input type="text" id="alltotal" name="alltotal" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="im-create-container-row4">

                        <div class="img-upload-container1">
                            <label for="sign-fileInput" class="custom-signature-label">
                                <span class="sign-box">Signature From:</span>
                                <div class="company-signature-box">
                                <img class="file-upload-company-icon" src="assets/images/image-line.svg" alt="">
                                <img id="uploaded-company-Signature" class="uploaded-company-signature" alt="">
                                </div>
                            </label>
                            <input type="file" id="sign-fileInput" name="sign_from_fileInput" class="company-file-upload">

                        </div>

                        <div class="img-upload-container2">
                            <label for="client-sign-fileInput" class="custom-signature-label">
                                <span class="sign-box">Signature To:</span>
                                <div class="client-signature-box">
                                <img class="file-upload-client-icon" src="assets/images/image-line.svg" alt="">
                                <img id="uploaded-client-Signature" class="uploaded-client-signature" alt="">
                                </div>
                            </label>
                            <input type="file" id="client-sign-fileInput" name="sign_to_fileInput" class="client-file-upload">
                        </div>


                    </div>
                    <div class="im-create-container-row5">
                        <div class="im-create-container-row5-upper">
                            <h2>Thank You!</h2>
                            <p>"Your payment has given us the funds we need to hire a professional clown to entertain our staff during long meetings. We hope this brings a smile to your face when you think of us!"</p>
                        </div>
                        <div class="im-create-container-row5-lower">
                            <button class="cancel-invoice-btn"><img src="assets/images/close-line.svg" alt="">Cancel</button>
                            <!-- <button class="share-invoice-btn"><img src="assets/images/share-forward-line.svg" alt="">Share</button>-->
                            <!-- <button class="print-invoice-btn"><img src="assets/images/printer-line.svg" alt="">Print</button>  -->
                            <button class="save-invoice-btn"><img src="assets/images/save-3-line.svg" alt="">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Close button/icon -->
        <span class="close-invoice-popup">&times;</span>
    </div>
</div>


<!-- delete popup -->
<div id="delete-popup" class="delete-popup">
    <div class="delete-popup-content">
        <div class="delete-popup-box">
            <div class="delete-popup-box-upper"><img src="assets\images\Group 48096421.svg" alt=""></div>
            <div class="delete-popup-box-lower">
                <h3>Confirm Delete?</h3>
                <p>Are you sure you want to “DELETE”</p>
                <form method="POST">
                <div class="confirm-delete-box">
                <input type="hidden" id="item-id" name="custid" value="<?php echo $itemdata['id']; ?>">
                <input type="hidden" name="deleteitem">
                <button class="confirm-to-delete" type="submit" >Yes, Delete</button>
                <button class="cancel-to-delete" type="button" >Cancel</button>
                </div>
                </form>
            </div>
        </div>
        <!-- Close button/icon -->
        <span class="close-delete-popup">&times;</span>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>
<script>
// JavaScript code
$(document).ready(function() {
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