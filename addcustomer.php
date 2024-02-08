<?php
session_start();
require_once 'lib/LoginClass.php';

include_once("includes/header.php");
include_once("lib/customer.php");

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}

$database = new ClientDatabase();





if (isset($_POST["insertcustomer"])) {
    // Assuming the form data is submitted via POST

    // Retrieve form data
    $clientName = $_POST["client-name"];
    $email = $_POST["email"];
    $companyName = $_POST["company-name"];
    $websiteURL = $_POST["website-url"];
    $phoneNumber = $_POST["phone-number"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $postal = $_POST["postal"];
    $country = $_POST["country"];
    $type = $_POST["type"];
    $currency = $_POST["currency"];
    $newImageName = '';
    // Insert data into the database
    $result = $database->insertClient($newImageName, $clientName, $email, $companyName, $websiteURL, $phoneNumber, $address, $city, $state, $postal, $country,$type,$currency);

   
    if ($result['type'] === 'success') {
    echo '<div style="color: green;">' . $result['msg'] . '</div>';
    } else {
    echo '<div style="color: red;">' . $result['msg'] . '</div>';
    }

    
}

if (isset($_POST["updatecustomer"])) {
    // Assuming the form data is submitted via POST

    // Retrieve form data
    
    $name = $_POST["name"];
    $email = $_POST["email"];
    $company = $_POST["company"];
    $website = $_POST["websiteurl"];
    $phone = $_POST["phonenumber"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $postal = $_POST["postal"];
    $country = $_POST["country"];
    $type = $_POST["edittype"];
    $currency = $_POST["editcurrency"];
    $cust_id = $_POST["custId"];
    // Insert data into the database
    $result = $database->updateClient( $name, $email, $company, $website, $phone, $address, $city, $state, $postal, $country, $type, $currency, $cust_id);

   
    if ($result['type'] === 'success') {
    echo '<div style="color: green;">' . $result['msg'] . '</div>';
    } else {
    echo '<div style="color: red;">' . $result['msg'] . '</div>';
    }

    
}

$customer = $database->getCustomerData();



$database->closeConnection();

?>

<div id="im-datatable-header">
        <div id="im-datatable-left-corner">
            <!-- Text in the left corner -->
            <h3>All Customer</h3>
        </div>
        <div id="im-datatable-middle">
            <!-- Search field and filter button in the middle -->
            <input type="text" id="search-input" placeholder="Search...">
            <button id="filter-button"><img src="assets/images/equalizer-line.svg" alt="Logout"></button>
        </div>
        <div id="im-datatable-right-corner">
            <!-- Button with image in the right corner -->
            <button id="add-customer-button" onclick="openCustomerPopup()">
                <img src="assets\images\add-line.svg" alt="Button Image">
            Add new Customer </button>

        </div>
</div>


<div class="im-add-customer-container">
    <?php foreach ($customer as $customer): ?>
        <div class="im-add-customer">
            <div class="add-customer-image">
                <?php 
                    $imageName = $customer['image'];
                    $imagePath = 'uploads/customer/' . $imageName;
                    echo '<img class="add-customer-img" src="' . $imagePath . '" alt="">';
                ?>
            </div>
            <div class="add-customer-details">
                <div class="add-customer-details-title">
                    <h3 class="customer-title"><?php echo $customer['name']; ?></h3><button class="edit_customer-button" data-customer-id="<?php echo $customer['id']; ?>" 
                    data-customer-name="<?php echo $customer['name']; ?>" data-customer-email="<?php echo $customer['email']; ?>" data-customer-phone="<?php echo $customer['phone']; ?>" data-customer-postalcode="<?php echo $customer['postal_code']; ?>" data-customer-city="<?php echo $customer['city']; ?>" data-customer-state="<?php echo $customer['state']; ?>" data-customer-address="<?php echo $customer['address']; ?>" data-customer-image="<?php echo $customer['image']; ?>" data-customer-country="<?php echo $customer['country']; ?>" data-customer-company="<?php echo $customer['company']; ?>" data-customer-website="<?php echo $customer['website']; ?>" data-customer-currency="<?php echo $customer['currency']; ?>" data-customer-type="<?php echo $customer['type']; ?>">
                    <img class="edit-customer-details" src="assets/images/pencil-line.svg" alt="edit">
                    </button>

                </div>
                <div class="add-customer-details-info">
                    <p><?php echo $customer['address']; ?></p>
                    <p><?php echo $customer['phone']; ?></p>
                    <p><?php echo $customer['email']; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



<!-- Popup Form -->
<!-- Popup Form for Add Customer -->
<div id="customer-popup" class="customer-popup">
    <div class="customer-popup-content">
    <form id="customer-form" method="POST" action="" enctype="multipart/form-data">
            <div class="customer-form-container">
                 <div class="customer-img-upload-container">
                    <label for="fileInput" class="customer-img-file-label">
                      <img class="customer-file-upload-icon" src="assets/images/building-2-line.svg" alt="">
                      <img id="uploadedImage" class="uploaded-image" alt=""><span class="upload-label-text">Upload Logo</span>
                    </label>
                      <input type="file" name="fileInput" id="fileInput" class="file-upload" enctype="multipart/form-data">

                </div>


                <div class="customer-basic-information">
                        
                    <div class="customer-basic-information-col11">
                    
                    <h3>Basic Client Details</h3>

                     <!-- Client Name -->
                    <div class="customer-frm-input">
                    <label for="client-name">Client Name<span class="red-clr">*</span></label>
                    <input type="text" id="client-name" name="client-name" required>
                    </div>

                    <!-- Email -->
                    <div class="customer-frm-input">
                    
                    <label for="email">Email<span class="red-clr">*</span></label>
                    <input type="email" id="email" name="email" required>
                    </div>

                    <div class="customer-frm-input">
                    <!-- Company Name -->
                    <label for="company-name">Company Name<span class="red-clr">*</span></label>
                    <input type="text" id="company-name" name="company-name" required>
                    </div>

                    <!-- Website URL -->
                    <div class="customer-frm-input">                   
                    <label for="website-url">Website URL</label>
                    <input type="url" id="website-url" name="website-url">
                    </div> 
                    <div class="customer-frm-input">
                <label for="country">Currency<span class="red-clr">*</span></label>
                <select id="country" name="currency" required>
                    <option value="Dollar">Dollar($)</option>
                    <option value="Rupees">Rupees(₹)</option>
                </select>
                </div>
                    </div>
                    
                    <div class="customer-basic-information-col11">
                    <h3>Contact Informations</h3>

                     <!-- Phone Number -->
                    <div class="customer-frm-input">
                    <label for="phone-number">Phone Number<span class="red-clr">*</span></label>
                    <input type="tel" id="phone-number" name="phone-number" required>
                    </div>

                     <!-- Address -->
                    <div class="customer-frm-input">
                    <label for="address">Address<span class="red-clr">*</span></label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                    </div>

                    <!-- City -->
                    <div class="customer-frm-input">       
                    <label for="city">City<span class="red-clr">*</span></label>
                    <input type="text" id="city" name="city" required>
                    </div> 

                    <div class="state-postal-country-con">
                    <div class="customer-frm-input">
                    <label for="city">State<span class="red-clr">*</span></label>
                    <input type="text" id="state" name="state" required>
                    </div>

                    <div class="customer-frm-input">
                    <label for="postal">Postal Code<span class="red-clr">*</span></label>
                    <input type="text" id="postal" name="postal" required>
                    </div>

                    <div class="customer-frm-input">
                    <label for="country">Country<span class="red-clr">*</span></label>
                    <input type="text" id="country" name="country" required>
                    </div>

                   
                    </div>
                    <div class="customer-frm-input">
                <label for="country">Customer Type<span class="red-clr">*</span></label>
                <select id="country" name="type" required>
                    <option value="Domestic">Domestic</option>
                    <option value="International">International</option>
                </select>
                </div>
                    </div>
                
            </div>
                     <!-- Buttons -->
                <div class="submit-customer-details-buttons">
                <button id="customer-additem-submit-btn" type="submit">Save Customer</button>
                <input type="hidden" name="insertcustomer">
                <button class="additem-discard-btn" type="button">Discard</button>
                </div>
                
            </div>
            
        </form>
        <span class="close-customer-popup">&times;</span>
    </div>
</div>
<!-- view customer popup -->
<div id="customer-popup-view" class="customer-popup-view">
    <div class="customer-popup-content">
        <form id="customer-form-view" method="POST" action="" enctype="multipart/form-data">
            <div class="customer-form-container">
                 <div class="customer-img-upload-container">
                    <label for="fileInput" class="customer-img-file-label">
                      <img class="customer-file-upload-icon" src="assets/images/building-2-line.svg" alt="">
                      <img id="uploaded-image" class="uploaded-image" alt=""><span class="upload-label-text">Upload Logo</span>
                    </label>
                      <input type="file" id="fileInput" class="file-upload">
                </div>


                <div class="customer-basic-information">
                        
                    <div class="customer-basic-information-col11">
                    
                    <h3>Basic Client Details</h3>
                    
                     <!-- Client Name -->
                    <div class="customer-frm-input">
                    <label for="edit-client-name">Client Name<span class="red-clr">*</span></label>
                    <input type="text" id="edit-client-name" name="name" placeholder="Enter client name" required>
                    </div>

                    <!-- Email -->
                    <div class="customer-frm-input">
                    
                    <label for="edit-email">Email<span class="red-clr">*</span></label>
                    <input type="email" id="edit-email" name="email" placeholder="Enter email address" required>
                    </div>

                    <div class="customer-frm-input">
                    <!-- Company Name -->
                    <label for="edit-company-name">Company Name<span class="red-clr">*</span></label>
                    <input type="text" id="edit-company-name" name="company" placeholder="Enter company name" required>
                    </div>

                    <!-- Website URL -->
                    <div class="customer-frm-input">                   
                    <label for="edit-website-url">Website URL<span class="red-clr">*</span></label>
                    <input type="url" id="edit-website-url" name="websiteurl" placeholder="Enter website URL" required>
                    </div> 
                    <div class="customer-frm-input">
                <label for="country">Currency<span class="red-clr">*</span></label>
                <select id="edit-currency" name="editcurrency" required>
                    <option value="Dollar">Dollar($)</option>
                    <option value="Rupees">Rupees(₹)</option>
                </select>
                </div>
                    </div>
                    
                    <div class="customer-basic-information-col11">
                    <h3>Contact Informations</h3>

                     <!-- Phone Number -->
                    <div class="customer-frm-input">
                    <label for="edit-phone-number">Phone Number<span class="red-clr">*</span></label>
                    <input type="tel" id="edit-phone-number" name="phonenumber" placeholder="Enter phone number" required>
                    </div>

                     <!-- Address -->
                    <div class="customer-frm-input">
                    <label for="edit-address">Address<span class="red-clr">*</span></label>
                    <textarea id="edit-address" name="address" rows="3" placeholder="Enter address" required></textarea>
                    </div>

                    <!-- City -->
                    <div class="customer-frm-input">       
                    <label for="edit-city">City<span class="red-clr">*</span></label>
                    <input type="text" id="edit-city" name="city" placeholder="Enter city" required>
                    </div> 

                    <div class="state-postal-country-con">
                    <div class="customer-frm-input">
                    <label for="edit-state">State<span class="red-clr">*</span></label>
                    <input type="text" id="edit-state" name="state" placeholder="Enter state" required>
                    </div>

                    <div class="customer-frm-input">
                    <label for="edit-postal">Postal Code<span class="red-clr">*</span></label>
                    <input type="text" id="edit-postal" name="postal" placeholder="Enter code" required>
                    </div>

                    <div class="customer-frm-input">
                    <label for="edit-country">Country<span class="red-clr">*</span></label>
                    <input type="text" id="edit-country" name="country" placeholder="Enter country" required>
                    </div>
                    

                    <input type="hidden" id="cust-id" name="custId" value="">
                    </div>
                    <div class="customer-frm-input">
                    <label for="edit-type">Customer Type<span class="red-clr">*</span></label>
                    <select id="edit-type" name="edittype" required>
                    <option value="Domestic">Domestic</option>
                    <option value="International">International</option>
                    </select>
                    </div>
                    </div>
                    
            </div>
                     <!-- Buttons -->
                <div class="submit-customer-details-buttons">
                <button id="customer-update-btn" type="submit">Update</button>
                <input type="hidden" name="updatecustomer">
                <button class="additem-discard-btn" type="button">Discard</button>
                </div>

            </div>
            
        </form>
        <span class="close-customer-popup">&times;</span>
    </div>
</div>

<?php
include_once("includes/footer.php");
?>
<script>
    $(function(){
        $(".edit_customer-button").click(function(){
            var cust_id = $(this).attr("data-customer-id");
            var cust_name = $(this).attr("data-customer-name");
            var cust_phone = $(this).attr("data-customer-phone");
            var cust_email = $(this).attr("data-customer-email");
            var cust_address = $(this).attr("data-customer-address");
            var cust_country = $(this).attr("data-customer-country");
            var cust_postalcode = $(this).attr("data-customer-postalcode");
            var cust_company = $(this).attr("data-customer-company");
            var cust_website = $(this).attr("data-customer-website");
            var cust_city = $(this).attr("data-customer-city");
            var cust_state = $(this).attr("data-customer-state");
            var cust_image = $(this).attr("data-customer-image");
            var cust_type = $(this).attr("data-customer-type");
            var cust_currency = $(this).attr("data-customer-currency");
            $("#cust-id").val(cust_id);
            $("#edit-client-name").val(cust_name);
            $("#edit-phone-number").val(cust_phone);
            $("#edit-email").val(cust_email);
            $("#edit-address").val(cust_address);
            $("#edit-country").val(cust_country);
            $("#edit-postal").val(cust_postalcode);
            $("#edit-company-name").val(cust_company);
            $("#edit-website-url").val(cust_website);
            $("#edit-city").val(cust_city);
            $("#edit-state").val(cust_state);
            $("#uploaded-image").attr("src", "uploads/customer/" + cust_image);
            $("#edit-type").val(cust_type);
            $("#edit-currency").val(cust_currency);
        });
    })
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
