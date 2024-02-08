<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>

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
    <!-- Add this script to your HTML -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


</head>
<body>
    <div class="im-main-content">
            <header class="im-header">
            <div class="im-header-left">
                <img src="assets/images/Rectanglee.png" alt="Icon">
            </div>
            <div class="im-header-right">
                <!-- <button id="toggleModeBtn">Toggle Mode</button> -->
                <div class="profile-info">
                    <!-- Display admin profile info here -->
                    <a href="admin-page.php">
                        <img src="assets/images/Ellipse 2.png" alt="Profile Icon">
                    </a>
                </div>
            </div>
            </header>
            <div class="im-content">
            <?php
            include_once("sidebar.php");
            ?>
            <div class="im-datatable-container">
                    
           

    