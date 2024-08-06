<?php

require_once("../admin/config.php");
$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

// session_start();


if(isset($_SESSION['MachooseAdminUser']['user_id']) && $_SESSION['MachooseAdminUser']['user_id']!="" ){
  header("Location: login.php");
  // print_r("sasaa");
}


if(!isset($_SESSION['MachooseAdminUser']['id']) && $_SESSION['MachooseAdminUser']['id']=="" ){
  header("Location: login.php");
  // print_r("sasaa");
}

$isProvider = $_SESSION['isProviderStaff'];
if(!$isProvider){
    header("Location: login.php");
}


$logedUserID = $_SESSION['MachooseAdminUser']['id'];
$loggedUserIdVal = $_SESSION['MachooseAdminUser']['id'];
$topManageType = '';

$sql = "SELECT a.*,b.state,c.city FROM tblmifutostaffuserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.id='$logedUserID'  ";
$result = $DBC->query($sql);
$row3 = mysqli_fetch_assoc($result);

$Username = $row3['name']." ".$row3['lastname'];
$RoleName = 'MIfuto Staff';

$city = $row3['city'];
$state = $row3['state'];
$county_id = $row3['county_id'];
$loggedUsercompany_logo_url = $row3['profile_pic_url'];

if($row3['user_status'] == 1) $verified = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check-fill text-success" viewBox="0 0 16 16">
  <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
</svg>';
else $verified = "";

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MIfuto</title>
  
  <link rel="shortcut icon" href="/images/favicon.ico">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  
  
  <style>
        /* Search box and pagination alignment */
        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            margin-bottom: 10px;
        }
        .dataTables_wrapper .dataTables_paginate {
            text-align: right;
        }
        
       
    </style>
  

  
  
  
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?=$loggedUsercompany_logo_url?>" alt="" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/staff/index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
       <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Service provider
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Customer
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Admin
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
<li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 2 New Bookings
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 5 Customer Review
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> New Notifications 
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
        <div class="user-panel d-flex pt-1" class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
         <!--   <div class="image">-->
         <!--     <img src="/admin/assets/img/profile-icon-design-free-vector.jpg" class="img-circle " alt="User Image">-->
         <!--   </div>-->
          
         <!--</div>-->
      
        
        
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
   

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="mt-3 pb-3 mb-3 d-flex" style="border-bottom: 1px solid #4f5962;">
          
        <div class="image pt-2">
            <a href="/staff/index.php">
          <img src="<?=$loggedUsercompany_logo_url?>" class="img-circle elevation-2" alt="User Image" width="50" height="50">
          </a>
        </div>
        <div class="info mt-2 ml-3">
          <a href="/staff/index.php" class="d-block"><?=$Username?> <?=$verified?></a>
          <a href="/staff/index.php" class="d-block">(<?=$RoleName?>)</a>

        </div>
        
    
      </div>
      
      
      

    

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
           <li class="nav-item">
            <a href="/staff/index.php" class="nav-link active" id="navDashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="/staff/profile.php" class="nav-link" id="navProfile">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profile
              </p>
            </a>
          </li>
          
          
           <li class="nav-item">
            <a href="/staff/companies.php" class="nav-link" id="navOurCompanies">
              <i class="nav-icon fas fa-building"></i>
              <p>
                My Companies
              </p>
            </a>
          </li>
          
          
           <li class="nav-item">
            <a href="/staff/bookings.php" class="nav-link" id="navBookings">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Bookings
              </p>
            </a>
          </li>
          
             <li class="nav-item">
            <a href="/staff/upload-file.php" class="nav-link" id="navFiles">
              <i class="nav-icon fas fa-upload"></i>
              <p>
                Upload Files
              </p>
            </a>
          </li>
          
          
              <li class="nav-item">
            <a href="/staff/finished-services.php" class="nav-link" id="navComplete">
              <i class="nav-icon 	fas fa-list-ul"></i>
              <p>
                Finished Services
              </p>
            </a>
          </li>
          
          
          
         
          
        
          
          <li class="nav-header">HELP</li>
          
          
          
             <li class="nav-item">
            <a href="/staff/FAQ.php" class="nav-link" id="navFAQ">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                FAQ
              </p>
            </a>
          </li>
          
          
             <li class="nav-item">
            <a href="/staff/MI_terms_and_conditions.php" class="nav-link" id="navTermsAndConditions">
              <i class="nav-icon fas fa-exclamation"></i>
              <p>
                 MI Terms and Conditions 
              </p>
            </a>
          </li>
          
          
          
          
        
          <li class="nav-item">
            <hr>
          </li>
          
          
          
          <li class="nav-item">
            <a href="/admin/logout-provider-staff.php" class="nav-link btn ">
              <i class="nav-icon fas fa-arrow-right"></i>
              <p>
                Sign Out
              </p>
            </a>
          </li>
          
         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
  