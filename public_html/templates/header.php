<?php

require_once("admin/config.php");
$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

$logginStatus = false;
$walletAmount = 0 ;
// session_start();
if (isset($_SESSION['isLogin']) && isset($_SESSION['mifutoUser']['id']) && $_SESSION['mifutoUser']['id'] != "") {
  $isLogin = $_SESSION['isLogin'];

  if ($isLogin) {
    $logginStatus = true;
    
    $sqlur = "SELECT * FROM mifuto_users WHERE id=".$_SESSION['mifutoUser']['id'] ;
    $resultur = $DBC->query($sqlur);
    $urData = mysqli_fetch_assoc($resultur);
    

    $walletAmount = floatval($urData['wallet_balance']) ;
    
  }
}

?>



<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--=============== basic  ===============-->
        <meta charset="UTF-8">
        <title>MIfuto-online photographer booking</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="robots" content="index, follow"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <!--=============== css  ===============-->
        <link type="text/css" rel="stylesheet" href="css/reset.css">
        <link type="text/css" rel="stylesheet" href="css/plugins.css">
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link type="text/css" rel="stylesheet" href="css/color.css">
        <!--=============== favicons ===============-->
        <link rel="shortcut icon" href="images/favicon.ico">
        
      
        <style>
            .error-input {
                border: 1px solid red !important; /* Red border style */
            }
            
          
            .error-message {
                color: red;
                font-size: 12px;
                margin-bottom: 20px;
                display: none; /* Initially hidden */
            }
            
            .success-message {
                color: green;
                font-size: 12px;
                margin-bottom: 20px;
                display: none; /* Initially hidden */
            }
            
            
            .coustom-input {
                float: left;
                border: 1px solid #eee;
                background: #F7F9FB;
                width: 100%;
                padding: 14px 20px 14px 45px;
                border-radius: 6px;
                color: #666;
                font-size: 13px;
                -webkit-appearance: none;
            }
            
            
            
            
        </style>
        
       
        
    </head>
    <body>
        
    
        <!--loader-->
        <div class="loader-wrap">
            <div class="pin">
                <div class="pulse"></div>
            </div>
        </div>
        <!--loader end-->
        <!-- Main  -->
        <div id="main">
            <!-- header-->
            <header class="main-header">
                <!-- header-top-->
                <div class="header-top fl-wrap">
                    <div class="container">
                        <div class="logo-holder">
                            <a href="index.php"><img src="images/logo.png" alt=""></a>
                        </div>
                        
                        <a href="https://mifuto.com/staff/login.php" target="_blank" class="add-hotel">Photographer+<span></span></a>
                        <a href="https://mifuto.com/provider/login.php" target="_blank" class="add-hotel">Business+<span></span></a>
                        
                        
                        <?php if($logginStatus){ ?>
                        
                            <div class="show-reg-form"><i class="fa fa-wallet"></i>₹ <?=$walletAmount?></div>
                        
                        
                        <?php } ?>
                        
                        
                        
                        
                        
                        
                        
                        <?php if($logginStatus){ ?>
                            <div class="show-reg-form " onclick="logout();"><i class="fa fa-sign-out"></i>Sign Out</div>
                            
                        <?php }else{ ?>
                            <div class="show-reg-form modal-open"><i class="fa fa-sign-in"></i>Sign In</div>
                        <?php } ?>
                        
                    </div>
                </div>
                <!-- header-top end-->
                <!-- header-inner-->
                <div class="header-inner fl-wrap">
                    <div class="container">
                        <div class="show-search-button"><span>Search</span> <i class="fas fa-search"></i> </div>
                        
                         <?php if($logginStatus){ ?>
                            
                            <div class="wishlist-link"><i class="fal fa-heart"></i><span class="wl_counter">3</span></div>
                            <div class="header-user-menu">
                                <div class="header-user-name">
                                    <span><img src="images/avatar/1.jpg" alt=""></span>
                                    <?=$_SESSION['Username']?>
                                </div>
                                <ul>
                                    <li><a href="dashboard-myprofile.php"> Edit profile</a></li>
                                    <li><a href="dashboard-listing-gallery.php"> Add Listing</a></li>
                                    <li><a href="dashboard-bookings.php">  Bookings  </a></li>
                                    <li><a href="dashboard-review.php"> Reviews </a></li>
                                    <li><a onclick="logout();">Log Out</a></li>
                                </ul>
                            </div>
                        <?php }else{ ?>
                        
                            <div class="header-user-menu modal-open">
                                <div class="header-user-name">
                                    <span><img src="images/avatar/1.jpg" alt=""></span>
                                    My account
                                </div>
                               
                            </div>
                        
                        
                        <?php } ?>
                        
                        
                    
                        
                        
                        
                        <div class="home-btn"><a href="index.php"><i class="fas fa-home"></i></a></div>
                        <!-- nav-button-wrap-->
                        <div class="nav-button-wrap color-bg">
                            <div class="nav-button">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <!-- nav-button-wrap end-->
                        <!--  navigation -->
                        <div class="nav-holder main-menu">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="index.php" name="list-menu" id="home-menu">Home</a>
                                       
                                    </li>
                                    
                                    <li>
                                        <a href="services.php" name="list-menu" id="service-menu">Services</a>
                                    </li>
                                    
                                    <?php if($logginStatus){ ?>
                                        <li>
                                            <a href="mi-cards.php" name="list-menu" id="card-menu">Cards</a>
                                        </li>
                                        
                                        <!--<li>-->
                                        <!--    <a href="dashboard-bookings.php" name="list-menu" id="bookings-menu">Bookings</a>-->
                                        <!--</li>-->
                                        
                                        
                                         <li>
                                            <a href="#" name="list-menu" id="bookings-menu">Bookings <i class="fas fa-caret-down"></i></a>
                                                <!--second level -->
                                                <ul>
                                                    <li><a href="dashboard-bookings.php" >Reserved Services</a></li>
                                                    <li><a href="processing-services.php">Processing Services</a></li>
                                                    <li><a href="finished-services.php">Finished Services</a></li>
                                                    <li><a href="dashboard-bookings.php">Canceled Services</a></li>
                                                   
                                                </ul>
                                                <!--second level end-->
                                            </li>
                                            
                                            
                                        <li>
                                            <a href="my-albums.php" name="list-menu" id="album-menu">My Albums</a>
                                        </li>
                                        
                                        
                                        
                                        
                                    
                                    <?php }else{ ?>
                                        <li>
                                            <a class="modal-open" name="list-menu" id="card-menu">Cards</a>
                                        </li>
                                    <?php } ?>
                                    
                                   
                                    
                                
                                </ul>
                            </nav>
                        </div>
                        <!-- navigation  end -->
                        
                        <!-- wishlist-wrap-->            
                        <div class="wishlist-wrap scrollbar-inner novis_wishlist">
                            <div class="box-widget-content">
                                <div class="widget-posts fl-wrap">
                                    <ul>
                                        <li class="clearfix">
                                            <a href="#"  class="widget-posts-img"><img src="images/gal/1.jpg" class="respimg" alt=""></a>
                                            <div class="widget-posts-descr">
                                                <a href="#" title="">Park Central</a>
                                                <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i class="fas fa-map-marker-alt"></i> 40 JOURNAL SQUARE PLAZA, NJ, US</a></div>
                                                <span class="rooms-price">$80 <strong> /  Awg</strong></span>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#"  class="widget-posts-img"><img src="images/gal/1.jpg" class="respimg" alt=""></a>
                                            <div class="widget-posts-descr">
                                                <a href="#" title="">Holiday Home</a>
                                                <div class="listing-rating card-popup-rainingvis" data-starrating2="3"></div>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i class="fas fa-map-marker-alt"></i> 75 PRINCE ST, NY, USA</a></div>
                                                <span class="rooms-price">$50 <strong> /   Awg</strong></span>
                                            </div>
                                        </li>
                                        <li class="clearfix">
                                            <a href="#"  class="widget-posts-img"><img src="images/gal/1.jpg" class="respimg" alt=""></a>
                                            <div class="widget-posts-descr">
                                                <a href="#" title="">Moonlight Hotel</a>
                                                <div class="listing-rating card-popup-rainingvis" data-starrating2="4"></div>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i class="fas fa-map-marker-alt"></i>  70 BRIGHT ST NEW YORK, USA</a></div>
                                                <span class="rooms-price">$105 <strong> /  Awg</strong></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- wishlist-wrap end--> 
                    </div>
                </div>
                <!-- header-inner end-->
                <!-- header-search -->
                <div class="header-search vis-search">
                    <div class="container">
                        <div class="row">
                            <!-- header-search-input-item -->
                            <div class="col-sm-6">
                                <div class="header-search-input-item fl-wrap location autocomplete-container">
                                    <label>Destination or Hotel Name</label>
                                    <span class="header-search-input-item-icon"><i class="fal fa-map-marker-alt"></i></span>
                                    <input type="text" placeholder="Location" class="autocomplete-input" id="autocompleteid" value=""/>
                                    <a href="#"><i class="fal fa-dot-circle"></i></a>
                                </div>
                            </div>
                            <!-- header-search-input-item end -->
                            <!-- header-search-input-item -->
                            <div class="col-sm-4">
                                <div class="header-search-input-item fl-wrap date-parent">
                                    <label>Date In-Out </label>
                                    <span class="header-search-input-item-icon"><i class="fal fa-calendar-check"></i></span>
                                    <input type="text" placeholder="When" name="header-search"   value=""/>
                                </div>
                            </div>
                            <!-- header-search-input-item end -->                             
                                             
                            <!-- header-search-input-item -->
                            <div class="col-sm-2">
                                <div class="header-search-input-item fl-wrap">
                                    <button class="header-search-button" onclick="window.location.href='listing.html'">Search <i class="far fa-search"></i></button>
                                </div>
                            </div>
                            <!-- header-search-input-item end -->                                                          
                        </div>
                    </div>
                    <div class="close-header-search"><i class="fal fa-angle-double-up"></i></div>
                </div>
                <!-- header-search  end -->
            </header>
            <!--  header end -->
            