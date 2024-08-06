<?php
include("../config.php");
$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);
// $projId = 14;

session_start();

if($_SESSION['MachooseAdminUser']['id'] == ""){
  header("Location: service-provider-login.php");
  // print_r("sasaa");
}

$isProvider = $_SESSION['isProvider'];

if(!$isProvider){
    echo '<script>';
    echo 'window.location.href = "service-provider-login.php";';
    echo '</script>';
    
}


$city = $_SESSION['city'];
$state = $_SESSION['state'];
$county_id = $_SESSION['county_id'];

$loggedUserIdVal = $_SESSION['MachooseAdminUser']['id'];

$topManageType = '';

$Username = $_SESSION['Username'];
$RoleName = 'Service Provider';


?>



<!DOCTYPE html>
<html lang="en">
    
    

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Machooos</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/masonry.css" rel="stylesheet"></link>
  <link href="assets/css/select2.css" rel="stylesheet"></link>
  <link href="assets/css/imageuploadify.min.css" rel="stylesheet"></link>
  
  <script src="assets/js/jquery3.6.0.js"></script>
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<style>
    #messagesDisplay {
        max-height: 600px; /* Set a maximum height to limit the scrolling area */
        overflow-y: auto; /* Enable vertical scrolling */
        border: 1px solid #ccc; /* Optional: Add a border for visual clarity */
    }
</style>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="dashboard.php" class="logo d-flex align-items-center">
         <img src="../images/logo.png" alt="">
        <span class="d-none d-lg-block"></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->
        
       
       

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="recent-activity.php" >
            <i class="bi bi-bell"></i>
            <!--<span class="badge bg-primary badge-number">4</span>-->
          </a><!-- End Notification Icon -->


        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" onclick="setSelectImageAsRead();">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number" id="messagesCount1"></span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have <label id="messagesCount"></label> new image selections &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <!--<a href="recent-activity.php"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>-->
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            
            <div id="messagesDisplay"></div>

       

            <li class="dropdown-footer">
              <a href="recent-activity.php">Show all notifications</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!--<img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">-->
            <span class="d-none d-md-block dropdown-toggle ps-2"><?=$Username?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?=$Username?></h6>
              <span><?=$RoleName?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <!--<li>-->
            <!--  <a class="dropdown-item d-flex align-items-center" href="users-profile.html">-->
            <!--    <i class="bi bi-gear"></i>-->
            <!--    <span>Account Settings</span>-->
            <!--  </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--  <hr class="dropdown-divider">-->
            <!--</li>-->

            <!--<li>-->
            <!--  <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">-->
            <!--    <i class="bi bi-question-circle"></i>-->
            <!--    <span>Need Help?</span>-->
            <!--  </a>-->
            <!--</li>-->
            <!--<li>-->
            <!--  <hr class="dropdown-divider">-->
            <!--</li>-->

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        
       
            <li class="nav-item">
                <a class="nav-link " href="provider-dashboard.php">
                  <i class="bi bi-grid"></i>
                  <span>DASHBOARD</span>
                </a>
            </li>
            
             <li class="nav-item">
                <a class="nav-link collapsed" href="provider-details.php">
                  <i class="bi bi-door-open"></i>
                  <span>Our Organization</span>
                </a>
            </li>
            
               <li class="nav-item">
                <a class="nav-link collapsed" href="provider-services.php">
                  <i class="bi bi-truck-front"></i>
                  <span>Our Services</span>
                </a>
            </li>
            
        
     
      
         
    

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">