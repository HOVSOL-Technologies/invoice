<?php
session_start();
require_once 'lib/LoginClass.php';

$user = new LoginClass();

if (!$user->isLoggedIn()) {
    header('Location: login.php');
}
include_once("includes/header.php");
?>

<div class="admin-page-container">
    <div class="company-details">
        <a class="back-to-dashboard" href="index.php"><img src="assets\images\Vector.svg" alt="">Back</a>
        <h3>Company Details</h3>
        <div class="company-logo-box">
          <img src="assets/images/Rectangle.svg" alt="">
        </div>
       
        <form id="admin-from" action="">
            <div class="admin-details-form">
                     <!-- Company Name -->
                    <div class="company-frm-input">            
                    <label for="company-name">Company Name<span class="red-clr">*</span></label>
                    <input type="text" id="client-name" name="client-name" placeholder="Hovsol technologies" required>
                    </div>

                    <!-- Company Email -->
                    <div class="company-frm-input">
                    <label for="company-email">Email<span class="red-clr">*</span></label>
                    <input type="email" id="company-email" name="company-email" placeholder="hovsoltechnologies@gmail.com" required>
                    </div>

                    <div class="company-frm-input">
                    <!-- Company Name -->
                    <label for="company-name">Phone<span class="red-clr">*</span></label>
                    <input type="number" id="company-number" name="company-number" placeholder="(+91) 000 000 0000" required>
                    </div>

                    <!-- Address -->
                    <div class="company-frm-input textara">
                    <label for="company-address">Address<span class="red-clr">*</span></label>
                    <textarea id="company-address" name="company-address" rows="3" placeholder="Malbazar, South Colony, Pin - 735221" required></textarea>
                    </div>

           

            </div>
        </form>
        
    </div>
            <div class="security-details">
                       <h3>Security</h3>
                       <div class="company-info-box">
                        <!-- Company Email -->
                            <div class="security-frm-input">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="hovsoltechnologies@gmail.com" readonly>
                            </div>
                            
                            <div class="security-frm-input company-password-box">
                                <div class="admin-update-password-box">
                                    <label for="password">Password</label>
                                    <input type="password" id="company-password" name="company-password" readonly>
                                    <img id="password-image" src="assets/images/eye-line.svg" alt="Lock Icon">
                                    
                                    <!-- Password Update Link -->
                                    <a class="update-password" href="#">Update Password</a>
                                </div>

                                <div class="change-password-container">
                                        
                                        <form id="securty-from" action="">
                                        <!-- Change Password Form -->
                                        
                                            <label for="currentPassword">Enter Current Password:</label>
                                            <input type="password" id="currentPassword" name="currentPassword" required>

                                            <label for="newPassword">Enter New Password:</label>
                                            <input type="password" id="newPassword" name="newPassword" required>

                                            <label for="confirmNewPassword">Confirm New Password:</label>
                                            <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>

                                            <div class="change-password-btn-container">
                                                <button class="password-discard-btn">Discard</button>
                                                <button class="password-Save-btn">Save</button>
                                            </div>
                                        
                                        </form>
                                </div>

                            </div>
                        </div>
                    

            </div>
</div>



<?php
include_once("includes/footer.php");
?>