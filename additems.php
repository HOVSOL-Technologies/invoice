<?php

session_start();
require_once 'lib/LoginClass.php';

include_once("includes/header.php");
include_once("lib/additem.php");

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}

$database = new itemDatabase();

if (isset($_POST["insertitem"])) {
    // Assuming the form data is submitted via POST
     // Insert data into the database
     $item= $price= $status=' ';
    // Retrieve form data
    $item = $_POST["item-name"];
    $price = $_POST["unit-price"];
    $status = $_POST["item-status"];
   
    $result = $database->insertitem($item, $price, $status);
    if ($result['type'] === 'success') {
    echo '<div style="color: green;">' . $result['msg'] . '</div>';
    } else {
    echo '<div style="color: red;">' . $result['msg'] . '</div>';
    }

    
}


if (isset($_POST["updateitem"])) {
    // Assuming the form data is submitted via POST

    // Retrieve form data
    
    $item= $price= $status=' ';
    // Retrieve form data
    $item = $_POST["name"];
    $price = $_POST["price"];
    $status = $_POST["status"];
    $id = $_POST["custid"];
   
    $result = $database->updateitem($item, $price, $status, $id);
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
    $result = $database->deleteitem($id);
    if ($result['type'] === 'success') {
    echo '<div style="color: green;">' . $result['msg'] . '</div>';
    } else {
    echo '<div style="color: red;">' . $result['msg'] . '</div>';
    }

    
}



$itemdata = $database->getitemData();


?>
<div id="im-datatable-header">
        <div id="im-datatable-left-corner">
            <!-- Text in the left corner -->
            <h3>All Items</h3>
        </div>
        <div id="im-datatable-middle">
            <!-- Search field and filter button in the middle -->
            <input type="text" id="search-input" placeholder="Search...">
            <button id="filter-button"><img src="assets/images/equalizer-line.svg" alt="filter"></button>
        </div>
        <div id="im-datatable-right-corner">
            <!-- Button with image in the right corner -->
            <button id="add-items-button">
                <img src="assets\images\add-line.svg" alt="Button Image">
            Add New Items</button>
        </div>
</div>
<table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" class="select-all-checkbox"></th>
                <th>Item Name</th>
                <th>Price/Units</th>
                <th>Created Date</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($itemdata as $itemdata): ?>
            <tr>
                <!-- <td><input type="checkbox" class="select-checkbox"></td> -->
                
                <td></td>
                
                <td><?php echo $itemdata['item_name']; ?></td>
                <td><?php echo $itemdata['price_units']; ?></td>
                <td><?php echo $itemdata['Date']; ?></td>
                <td>
                <span class="<?php echo $itemdata['item_status'] == 'Enable' ? 'item-status-enable' : 'item-status-disable'; ?>">
                 <?php echo $itemdata['item_status']; ?>
                </span>

                </td>
                <td>
                <a class="view-item-popup" href="#" title="View">
                <img src="assets/images/eye-fill.svg" alt="View" class="action-icon" data-item-id="<?php echo $itemdata['id']; ?>" data-item-name="<?php echo $itemdata['item_name']; ?>" data-item-price="<?php echo $itemdata['price_units']; ?>" data-item-status="<?php echo $itemdata['item_status']; ?>"></a>
                <a class="delete-item" href="#" title="Delete"><img src="assets/images/delete-bin-6-fill.svg" alt="Delete" class="action-icon"></a>
                </td>
            </tr>
            <?php endforeach; ?>
            
        </tbody>
        <tfoot>
            <tr>
                <th><input type="checkbox" id="selectAllFooter" class="select-all-checkbox"></th>
                <th>Item Name</th>
                <th>Price/Units</th>
                <th>Created Date</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>

<!-- Popup Form add items-->
<div id="popup-form" class="popup">
    <div class="popup-content">
        <!-- Add your form content here -->
        <h2>Add New Items</h2>
        <!-- Your form elements go here -->
        <form id="item-form" method="POST">
                <!-- Item Name -->
                <input type="hidden" id="cust-id" name="insertitem">
                <div class="item-name">
                <label for="item-name">Item Name<span class="red-clr">*</span></label>
                <input type="text" id="item-name" name="item-name" placeholder="Enter item name" required>
                </div>

                <!-- Unit/Price -->
                <div class="item-price">
                <label for="unit-price">Price/Units<span class="red-clr">*</span></label>
                <input type="text" id="unit-price" name="unit-price" placeholder="Enter Price/Units" required>
                </div>

                <!-- Enable/Disable Dropdown -->
                <div class="item-status">
                <label for="item-status">Set Item Status<span class="red-clr">*</span></label>
                <select id="item-status" name="item-status" required>
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
                </select>
                </div>

                <!-- Buttons -->
                <div class="submit-status-buttons">
                <button id="additem-submit-btn" type="submit">Submit</button>
                <button class="additem-discard-btn" type="button">Discard</button>
                </div>
        </form> 


        <!-- Close button/icon -->
        <span class="close-popup" >&times;</span>
    </div>
</div>

<!-- Popup Form view items-->
<div id="popup-form-view" class="popup">
    <div class="popup-content">
        <!-- Add your form content here -->
        <h2>Add New Items</h2>
        <!-- Your form elements go here -->
        <form id="item-form-view" method="POST">
                <!-- Item Name -->
                <input type="hidden" id="item-id" name="custid">
                <input type="hidden" name="updateitem">
                <div class="item-name">
                <label for="edit-item-name">Item Name<span class="red-clr">*</span></label>
                <input type="text" id="edit-item-name" name="name" placeholder="Enter item name" required>
                </div>

                <!-- Unit/Price -->
                <div class="item-price">
                <label for="edit-unit-price">Price/Units<span class="red-clr">*</span></label>
                <input type="text" id="edit-unit-price" name="price" placeholder="Enter Price/Units" required>
                </div>

                <!-- Enable/Disable Dropdown -->
                <div class="item-status">
                <label for="edit-item-status">Set Item Status<span class="red-clr">*</span></label>
                <select id="edit-item-status" name="status" required>
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
                </select>
                </div>
                
                <!-- Buttons -->
                <div class="submit-status-buttons">
                <button id="additem-update-btn" type="submit">Update</button>
                <button class="additem-discard-btn" type="button">Discard</button>
                </div>
                
        </form> 


        <!-- Close button/icon -->
        <span class="close-popup" >&times;</span>
    </div>
</div>

<!-- delete popup -->
<div id="delete-popup" class="delete-popup">
    <div class="delete-popup-content">
        <div class="delete-popup-box">
            <div class="delete-popup-box-upper"><img src="assets\images\Group 48096421.svg" alt=""></div>
            <div class="delete-popup-box-lower">
                <h3>Confirm Delete?</h3>
                <p>Are you sure you want to "DELETE"</p>
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
    $(function(){
        $(".action-icon").click(function(){
            var item_id = $(this).attr("data-item-id");
            var item_name = $(this).attr("data-item-name");
            var item_price = $(this).attr("data-item-price");
            var item_status = $(this).attr("data-item-status");

            $("#item-id").val(item_id);
            $("#edit-item-name").val(item_name);
            $("#edit-unit-price").val(item_price);
            $("#edit-item-status").val(item_status);
        });
    })
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>