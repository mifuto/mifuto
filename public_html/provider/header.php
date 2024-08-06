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

$isProvider = $_SESSION['isProvider'];
if(!$isProvider){
    header("Location: login.php");
}

$logedUserID = $_SESSION['MachooseAdminUser']['id'];
$loggedUserIdVal = $_SESSION['MachooseAdminUser']['id'];
$topManageType = '';

$sql = "SELECT a.*,b.state,c.city FROM tblprovideruserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.id='$logedUserID'  ";
$result = $DBC->query($sql);
$row3 = mysqli_fetch_assoc($result);

$Username = $row3['name'];
$RoleName = 'Service Provider';

$city = $row3['city'];
$state = $row3['state'];
$county_id = $row3['county_id'];
$loggedUsercompany_logo_url = $row3['company_logo_url'];

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Machooos International</title>
  
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
        <a href="/provider/index.php" class="nav-link">Home</a>
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
            <a href="/provider/index.php">
          <img src="<?=$loggedUsercompany_logo_url?>" class="img-circle elevation-2" alt="User Image" width="50" height="50">
          </a>
        </div>
        <div class="info mt-2 ml-3">
          <a href="/provider/index.php" class="d-block"><?=$Username?></a>
          <a href="/provider/index.php" class="d-block">(<?=$RoleName?>)</a>

        </div>
        
    
      </div>
      
      
      

    

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
           <li class="nav-item">
            <a href="/provider/index.php" class="nav-link active" id="navDashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="/provider/profile.php" class="nav-link" id="navProfile">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profile
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/provider/pages/calendar.php" class="nav-link" id="navProfile">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My calendar
              </p>
            </a>
          </li>
          
          <li class="nav-header">ENTERPRISE</li>
          
           <li class="nav-item">
            <a href="/provider/companies.php" class="nav-link" id="navOurCompanies">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Our Companies
              </p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="/provider/services.php" class="nav-link" id="navOurServices">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Our Services
              </p>
            </a>
          </li>
          
          
            <li class="nav-item">
            <a href="/provider/bookings.php" class="nav-link" id="navBookings">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Bookings
              </p>
            </a>
          </li>
         
          
          
          
          
          
          <li class="nav-header">HELP</li>
          
          
          
             <li class="nav-item">
            <a href="/provider/FAQ.php" class="nav-link" id="navFAQ">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                FAQ
              </p>
            </a>
          </li>
          
          
             <li class="nav-item">
            <a href="/provider/MI_terms_and_conditions.php" class="nav-link" id="navTermsAndConditions">
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
            <a href="/admin/logout-provider.php" class="nav-link btn ">
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
  
  