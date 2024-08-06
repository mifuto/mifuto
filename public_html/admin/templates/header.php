<?php
include("config.php");
$DBC = mysqli_connect('localhost', 'root', '', 'mi_db');
// $projId = 14;

//session_start();
if($_SESSION['MachooseAdminUser']['user_id'] == ""){
  header("Location: login.php");
  // print_r("sasaa");
}

$isAdmin = $_SESSION['isAdmin'];

$manage_type = $_SESSION['manage_type'];
$city = $_SESSION['city'];
$state = $_SESSION['state'];
$county_id = $_SESSION['county_id'];

$loggedUserIdVal = $_SESSION['MachooseAdminUser']['id'];

$topManageType = '';
if($isAdmin){
    $topManageType = '';
}else{
    if($manage_type == 'County'){
       // user type County
       
       $sql3 = "SELECT short_name FROM tblcountries WHERE country_id=".$county_id;
        $result3 = $DBC->query($sql3);
        $row3 = mysqli_fetch_assoc($result3);
    
    
    $CountyName = $row3['short_name'];
       
       
       
       $topManageType = 'County - '.$CountyName;
              
               
   }else if($manage_type == 'State'){
       // user type State
       $topManageType = 'State - '.$state;
     
   }else {
       // user type City
       $topManageType = 'City - '.$city;
       
   }
}








if($isAdmin){
    $Username = 'Machooos International';
    $RoleName = 'Super Admin';
    
    $userPermissions = 'Dashboard,Online-Album,Signature-Album,Wedding-Films,Website,Career,Sales,User-management,Staff-management,Reports,System-settings,Enquiries,Cards,Service-Provider,Companies,Provider-management,Provider-staff,Add-one';
}else{
    
    $UserRole = $_SESSION['UserRole'];
    $Username = $_SESSION['Username'];
    
    $sql = "SELECT * FROM tbluserroles WHERE id=".$UserRole;
    $result = $DBC->query($sql);
    $row = mysqli_fetch_assoc($result);
    
    
    $RoleName = $row['role'];
    $userPermissions = $row['userPermissions'];
    
    
}

 

 $systemMenus = array(
    'Dashboard' => 'Dashboard',
    'Online-Album' => 'Online album',
    'Signature-Album' => 'Signature Album',
    'Wedding-Films' => 'Wedding Films',
    'Website' => 'Website',
    'Career' => 'Career',
    'Sales' => 'Sales',
    'User-management' => 'User management',
    'Staff-management' => 'Staff management',
    'Reports' => 'Reports',
    'System-settings' => 'System settings',
    'Enquiries' => 'Enquiries',
    'Cards' => 'Cards',
    'Insentive' =>'Insentive',
    'Service-Provider' =>'Service Provider',
    'Companies'=>'Companies',
    'Provider-management'=>'Provider management',
    'Provider-staff'=>'Provider staff',
    'Add-one'=>'Add one',
);


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
        max-height: 600px; 
        overflow-y: auto; 
        border: 1px solid #ccc;
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
        
        <li class="nav-item dropdown pe-3">
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="text-dark d-none d-md-block ps-2"><?=$topManageType?></span>
          </a>
          
       
        </li>
        
       

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
              <a class="dropdown-item d-flex align-items-center" href="profile-details.php">
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
        
        
        <?php if (in_array('Dashboard', explode(',', $userPermissions))) { ?>
            <li class="nav-item">
                <a class="nav-link " href="dashboard.php">
                  <i class="bi bi-grid"></i>
                  <span>DASHBOARD</span>
                </a>
            </li>
        
        <?php } ?>
        
        
        
        
        <?php if (!$isAdmin) { ?>
         <li class="nav-item">
            <a class="nav-link collapsed" href="staff-instructions.php">
              <i class="bi bi-info-circle"></i>
              <span>Staff instructions</span>
            </a>
        </li>
       <?php } ?>
        
      <?php if (in_array('Online-Album', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-journal-text"></i><span>ONLINE ALBUM</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
    
              <li>
                <a href="aadEvent.php">
                  <i class="bi bi-circle"></i><span>Add event</span>
                </a>
              </li>
              <li>
                
              </li>
            </ul>
        </li>
        
        <?php } ?>
        <?php if (in_array('Signature-Album', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i><span>Signature Album</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
              
                
              <li>
                <a href="signatureAbum.php">
                  <i class="bi bi-circle"></i><span>Create</span>
    				
                </a>
              </li>
              
                <li>
                    <a href="signature-albums.php">
                      <i class="bi bi-circle"></i><span>Albums</span>
        				
                    </a>
                  </li>
                
              
              <li>
                <a href="signatureAbumSelImgs.php">
                  <i class="bi bi-circle"></i><span>Selected images <span class="badge bg-primary badge-number" id="CSIC">0</span> </span>
    				
                </a>
              </li>
             
            </ul>
        </li>
        
        <?php } ?>
        <?php if (in_array('Wedding-Films', explode(',', $userPermissions))) { ?>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="wedding-films.php">
              <i class="bi bi-play-btn"></i>
              <span>Wedding Films</span>
            </a>
        </li>
         
         <?php } ?> 
         <?php if (in_array('Website', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-button-wide"></i><span>Website</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
             
              <li>
                <a href="blog.php">
                  <i class="bi bi-circle"></i><span>Blog</span>
                </a>
              </li>
              <li>
                <a href="stories.php">
                  <i class="bi bi-circle"></i><span>Stories</span>
                </a>
              </li>
              <li>
                <a href="cinematography.php">
                  <i class="bi bi-circle"></i><span> Cinematography</span>
                </a>
              </li>
    
                <li>
                  <a href="provide-services.php">
                    <i class="bi bi-circle"></i><span> Services That I Provide</span>
                  </a>
                </li>
                
                 
                 <li>
                    <a href="home-video.php">
                      <i class="bi bi-circle"></i><span>Home Vedio</span>
        				
                    </a>
                </li>
                
                   <li>
                    <a href="latest-news.php">
                      <i class="bi bi-circle"></i><span>Latest News</span>
        				
                    </a>
                </li>
                
                  <li>
                  <a href="popups.php">
                    <i class="bi bi-circle"></i><span> User Popups</span>
                  </a>
                </li>
    
              <li>
    			  <a href="homePage.php">
                  <i class="bi bi-circle"></i><span>Homepage</span>
                </a>
              </li>
            </ul>
        </li>
        
        <?php } ?>
        <?php if (in_array('Career', explode(',', $userPermissions))) { ?>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="career.php">
              <i class="bi bi-person-workspace"></i>
              <span>Career</span>
            </a>
         </li>
         <?php } ?>
         
         <?php if (in_array('Provider-management', explode(',', $userPermissions))) { ?>
         <li class="nav-heading">MI Futo</li>
         <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#addone-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-app"></i><span>Add one</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="addone-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                
                <li>
                    <a href="amnities.php">
                      <i class="bi bi-circle"></i><span>Amnities</span>
        				
                    </a>
                  </li>
                  
                  <!--  <li>-->
                  <!--  <a href="services-adding.php">-->
                  <!--    <i class="bi bi-circle"></i><span>Service Adding Type</span>-->
        				
                  <!--  </a>-->
                  <!--</li>-->
                  
                  
                     <li>
                    <a href="deliverables.php">
                      <i class="bi bi-circle"></i><span>Deliverables</span>
        				
                    </a>
                  </li>
                  
                  <!--  <li>-->
                  <!--  <a href="services-attribute-fields.php">-->
                  <!--    <i class="bi bi-circle"></i><span>Attribute Fields</span>-->
        				
                  <!--  </a>-->
                  <!--</li>-->
                  
                  
                  
                
              <li>
                <a href="cancel-and-refund-policy.php">
                  <i class="bi bi-circle"></i><span>Cancel & Refund Policy</span>
    				
                </a>
              </li>
              
               <li>
                <a href="pricetermsconditions.php">
                  <i class="bi bi-circle"></i><span>Price Terms and details</span>
    				
                </a>
              </li>
            </ul>
         </li>
          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav94" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-app"></i><span>Provider management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav94" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                
                <li>
                    <a href="services-centers.php">
                      <i class="bi bi-circle"></i><span>Service Centers</span>
        				
                    </a>
                  </li>
                  
                  <!--  <li>-->
                  <!--  <a href="services-adding.php">-->
                  <!--    <i class="bi bi-circle"></i><span>Service Adding Type</span>-->
        				
                  <!--  </a>-->
                  <!--</li>-->
                  
                  
                     <li>
                    <a href="services-attributes.php">
                      <i class="bi bi-circle"></i><span>Attributes</span>
        				
                    </a>
                  </li>
                  
                  <!--  <li>-->
                  <!--  <a href="services-attribute-fields.php">-->
                  <!--    <i class="bi bi-circle"></i><span>Attribute Fields</span>-->
        				
                  <!--  </a>-->
                  <!--</li>-->
                  
                  
                  
                
              <li>
                <a href="add-provider-faq.php">
                  <i class="bi bi-circle"></i><span>Add FAQ</span>
    				
                </a>
              </li>
              
               <li>
                <a href="add-provider-tac.php">
                  <i class="bi bi-circle"></i><span>MI Terms and Conditions</span>
    				
                </a>
              </li>
              
               <li>
                <a href="list-provider-info.php">
                  <i class="bi bi-circle"></i><span>Providers</span>
    				
                </a>
              </li>
              
             
              
            
             
            </ul>
         </li>
         
         
         
         
          <?php } ?>
          
             <?php if (in_array('Companies', explode(',', $userPermissions))) { ?>
         
          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav291" data-bs-toggle="collapse" href="#">
              <i class="bi bi-door-open"></i><span>Companies</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav291" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
               
              
               <li>
                <a href="requested-companies.php">
                  <i class="bi bi-circle"></i><span>Newly requested companies</span>
    				
                </a>
              </li>
              
               <li>
                <a href="accepted-companies.php">
                  <i class="bi bi-circle"></i><span>Accepted companies</span>
    				
                </a>
              </li>
              
               <li>
                <a href="rejected-companies.php">
                  <i class="bi bi-circle"></i><span>Rejected companies</span>
    				
                </a>
              </li>
              
             
              
                 
            </ul>
        </li>
         
         <?php } ?>
         
         
          
         
         <?php if (in_array('Service-Provider', explode(',', $userPermissions))) { ?>
         
          <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav29" data-bs-toggle="collapse" href="#">
              <i class="bi bi-ui-checks"></i><span>Services</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav29" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                <li>
                    <a href="services-price.php">
                      <i class="bi bi-circle"></i><span>Service Price</span>
        				
                    </a>
                </li>
                
               
              
              <!-- <li>-->
              <!--  <a href="services-providers.php">-->
              <!--    <i class="bi bi-circle"></i><span>Companies</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
               <li>
                <a href="services-requests.php">
                  <i class="bi bi-circle"></i><span>Service Requests</span>
    				
                </a>
              </li>
              
              <li>
                <a href="services-coupons.php">
                  <i class="bi bi-circle"></i><span>Coupons</span>
    				
                </a>
              </li>
              
               <li>
                <a href="booked-services.php">
                  <i class="bi bi-circle"></i><span>Booked Services</span>
    				
                </a>
              </li>
              
              
              
              
            
                 
            </ul>
        </li>
         
         <?php } ?>
         
         
       <?php if (in_array('Provider-staff', explode(',', $userPermissions))) { ?>
       
       
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav290" data-bs-toggle="collapse" href="#">
              <i class="bi bi-person-lines-fill"></i><span>Provider Staff</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav290" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                <li>
                    <a href="provider-staffs.php">
                      <i class="bi bi-circle"></i><span>Staffs</span>
        				
                    </a>
                </li>
                
                 
            </ul>
        </li>
         
         
         
         <?php } ?>
         
         
         
         
         
         
         
         <?php if (in_array('Sales', explode(',', $userPermissions))) { ?>
         
        <li class="nav-heading">Sales</li>
         
         
    
        
         <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav9455" data-bs-toggle="collapse" href="#">
              <i class="bi bi-receipt"></i><span>Invoices</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav9455" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="invoice-list.php">
                  <i class="bi bi-circle"></i><span>Invoices for albums</span>
    				
                </a>
              </li>
              
             
              
            
             
            </ul>
         </li>
        
        
        
         
         <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav4" data-bs-toggle="collapse" href="#">
              <i class="bi bi-cash-stack"></i><span>Packages</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav4" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="packages.php">
                  <i class="bi bi-circle"></i><span>Subscription plans</span>
    				
                </a>
              </li>
              
              <li>
                <a href="coupons.php">
                  <i class="bi bi-circle"></i><span>Coupons</span>
    				
                </a>
              </li>
              
           
             
            </ul>
         </li>
         
         <?php } ?>
         
         
         
            
          <?php if (in_array('Cards', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav26" data-bs-toggle="collapse" href="#">
              <i class="bi bi-credit-card"></i><span>Cards</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav26" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                
                
                   <li>
                <a href="user-cards.php">
                  <i class="bi bi-circle"></i><span>Cards</span>
    				
                </a>
              </li>
              
              
               <li>
                <a href="card-coupons.php">
                  <i class="bi bi-circle"></i><span>Coupons</span>
    				
                </a>
              </li>
              
                 <li>
                <a href="user-card-services.php">
                  <i class="bi bi-circle"></i><span>Card Services</span>
    				
                </a>
              </li>
              
                
                
                
                
                
                
                
                
              <!--   <li>-->
              <!--  <a href="cards.php">-->
              <!--    <i class="bi bi-circle"></i><span>Cards</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
              <!--  <li>-->
              <!--  <a href="card-services.php">-->
              <!--    <i class="bi bi-circle"></i><span>Card Services</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
              <!-- <li>-->
              <!--  <a href="sub-cards.php">-->
              <!--    <i class="bi bi-circle"></i><span>Sub Cards</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
              <!-- <li>-->
              <!--  <a href="card-requests.php">-->
              <!--    <i class="bi bi-circle"></i><span>Card Requests</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
              
              <!--  <li>-->
              <!--  <a href="card-services-used.php">-->
              <!--    <i class="bi bi-circle"></i><span>Services Used</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
              <!-- <li>-->
              <!--  <a href="card-invoice-list.php">-->
              <!--    <i class="bi bi-circle"></i><span>Invoices for cards</span>-->
    				
              <!--  </a>-->
              <!--</li>-->
              
           
             
            </ul>
        </li>
        <?php } ?>
         
         
         
         
        
         
         
         
         <?php if (in_array('User-management', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav2" data-bs-toggle="collapse" href="#">
              <i class="bi bi-person-lines-fill"></i><span>User management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav2" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="users-details.php">
                  <i class="bi bi-circle"></i><span>Main users</span>
    				
                </a>
              </li>
              
              <li>
                <a href="guest-users.php">
                  <i class="bi bi-circle"></i><span>Guest Users</span>
    				
                </a>
              </li>
              
                <li>
                    <a href="sub-user.php">
                      <i class="bi bi-circle"></i><span>Create Sub User</span>
        				
                    </a>
                  </li>
                  
                    <li>
                        <a href="event-type-users-details.php">
                          <i class="bi bi-circle"></i><span>Main users by event type</span>
            				
                        </a>
                      </li>
                  
                  <?php if($isAdmin){ ?>
                      <li>
                        <a href="send-common-mail.php">
                          <i class="bi bi-circle"></i><span>Send common mail</span>
            				
                        </a>
                      </li>
                  <?php } ?>
                  
             
            </ul>
        </li>
        <?php } ?>
        <?php if (in_array('Staff-management', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav8" data-bs-toggle="collapse" href="#">
              <i class="bi bi-person-lines-fill"></i><span>Staff management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav8" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                <li>
                    <a href="userRoles.php">
                      <i class="bi bi-circle"></i><span>Staffs Roles</span>
        				
                    </a>
                </li>
                  
                <li>
                    <a href="machoose-users.php">
                      <i class="bi bi-circle"></i><span>Staffs</span>
        				
                    </a>
                </li>
                
                 <li>
                    <a href="insentive-roles.php">
                      <i class="bi bi-circle"></i><span>Insentive Roles</span>
        				
                    </a>
                </li>
                
                 <li>
                    <a href="insentive-requests.php">
                      <i class="bi bi-circle"></i><span>Insentive Requests</span>
        				
                    </a>
                </li>
             
            </ul>
        </li>
        <?php } ?>
        
        
         <?php if (in_array('Insentive', explode(',', $userPermissions))) { ?>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="insentive-page.php">
              <i class="bi bi-cash-coin"></i>
              <span>Insentive</span>
            </a>
        </li>
         
         <?php } ?> 
        
        
        
        
        
        
        
        <?php if (in_array('Reports', explode(',', $userPermissions))) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav9" data-bs-toggle="collapse" href="#">
              <i class="bi bi-receipt"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav9" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                
                <li>
                    <a href="signature-album-report.php">
                      <i class="bi bi-circle"></i><span>Signature Album Report</span>
        				
                    </a>
                </li>
                
                 <li>
                    <a href="online-album-report.php">
                      <i class="bi bi-circle"></i><span>Online Album Report</span>
        				
                    </a>
                </li>
                
                <li>
                    <a href="user-albums.php">
                      <i class="bi bi-circle"></i><span>Manage User Albums</span>
        				
                    </a>
                </li>
                
                <li>
                    <a href="mails.php">
                      <i class="bi bi-circle"></i><span>Mails</span>
        				
                    </a>
                </li>
                
                 <li>
                    <a href="staff-login.php">
                      <i class="bi bi-circle"></i><span>Staff Login</span>
        				
                    </a>
                </li>
                  
               
             
            </ul>
        </li>
        <?php } ?>
        <?php if (in_array('System-settings', explode(',', $userPermissions))) { ?>
       
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav5" data-bs-toggle="collapse" href="#">
              <i class="bi bi-gear-fill"></i><span>System settings</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav5" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="countries.php">
                  <i class="bi bi-circle"></i><span>Countries</span>
    				
                </a>
              </li>
              
              <li>
                <a href="state.php">
                  <i class="bi bi-circle"></i><span>State</span>
    				
                </a>
              </li>
              
                <li>
                    <a href="city.php">
                      <i class="bi bi-circle"></i><span>District</span>
        				
                    </a>
                </li>
                
                <li>
                    <a href="event-type.php">
                      <i class="bi bi-circle"></i><span>Event Type</span>
        				
                    </a>
                </li>
                
                 <li>
                    <a href="service-type.php">
                      <i class="bi bi-circle"></i><span>Service Type</span>
        				
                    </a>
                </li>
                
                 
            
                
                
                
                
                
                <li>
                    <a href="email-templates.php">
                      <i class="bi bi-circle"></i><span>Email Templates</span>
        				
                    </a>
                </li>
                
                <li>
                    <a href="manage-cron-job.php">
                      <i class="bi bi-circle"></i><span>Manage Cron Job</span>
        				
                    </a>
                </li>
                
                  
               
             
            </ul>
        </li>
      
      
        <?php } ?>
      
      

      

      <li class="nav-heading">Pages</li>
      
       
      
      
      
      
     

      <li class="nav-item">
        <a class="nav-link collapsed" href="faq.php">
          <i class="bi bi-question-circle"></i>
          <span>AF..Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->


    <?php if (in_array('Enquiries', explode(',', $userPermissions))) { ?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="enquiries.php">
          <i class="bi bi-person-workspace"></i>
          <span>Enquiries</span>
        </a>
      </li><!-- End Enquiries Page Nav -->
      
      <?php } ?>

    

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">