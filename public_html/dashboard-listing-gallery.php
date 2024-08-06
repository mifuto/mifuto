<?php 

include("templates/header.php");

?>
            
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!-- section-->
                    <section class="flat-header color-bg adm-header">
                        <div class="wave-bg wave-bg2"></div>
                        <div class="container">
                            <div class="dasboard-wrap fl-wrap">
                                <div class="dasboard-breadcrumbs breadcrumbs"><a href="#">Home</a><a href="dashboard-myprofile.php">Dashboard</a><span>Profile page</span></div>
                                <!--dasboard-sidebar-->
                                <div class="dasboard-sidebar">
                                    <div class="dasboard-sidebar-content fl-wrap">
                                        <div class="dasboard-avatar">
                                            <img src="images/avatar/1.jpg" alt="">
                                        </div>
                                        <div class="dasboard-sidebar-item fl-wrap">
                                            <h3>
                                                <span>Welcome </span>
                                                Customer Name
                                            </h3>
                                        </div>
                                        <a href="dashboard-add-listing.html" class="ed-btn">CARD WITH CUSTOMER</a>                                        
                                        <div class="user-stats fl-wrap">
                                            <ul>
                                                <li>
                                                    Finished	
                                                    <span>4</span>
                                                </li>
                                                <li>
                                                    upcoming
                                                    <span>32</span>	
                                                </li>
                                                <li>
                                                    cancelled	
                                                    <span>9</span>	
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="#" class="log-out-btn color-bg">Log Out <i class="far fa-sign-out"></i></a>
                                    </div>
                                </div>
                                <!--dasboard-sidebar end--> 
                                <!-- dasboard-menu-->
                                <div class="dasboard-menu">
                                    <div class="dasboard-menu-btn color3-bg">Dashboard Menu <i class="fal fa-bars"></i></div>
                                    <ul class="dasboard-menu-wrap">
                                        <li>
                                            <a href="dashboard-myprofile.php"><i class="far fa-user"></i>Profile</a>
                                            <ul>
                                                <li><a href="dashboard-myprofile.php">Edit profile</a></li>
                                                <li><a href="dashboard-password.php">Change Password</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="dashboard-messages.php"><i class="far fa-envelope"></i> Messages <span>3</span></a></li>
                                        <li>
                                            <a href="dashboard-listing-gallery.php" class="user-profile-act"><i class="far fa-th-list"></i> My Gallery  </a>
                                            
                                        </li>
                                        <li><a href="dashboard-bookings.php"> <i class="far fa-calendar-check"></i> Recent Bookings <span>2</span></a>
                                         <ul>
                                                <li><a href="#">Finishes</a><span>5</span></li>
                                                <li><a href="#">Upcoming</a><span>2</span></li>
                                                <li><a href="#">Cancelled</a><span>3</span></li>
                                            </ul>
                                        </li>
                                        <li><a href="dashboard-review.php"><i class="far fa-comments"></i> Reviews </a></li>
                                    </ul>
                                </div>
                                <!--dasboard-menu end-->
                                <!--Tariff Plan menu-->
                                <div   class="tfp-btn"><span>ACTIVE CARD: </span> <strong>current card details</strong></div>
                                <div class="tfp-det">
                                    <p>Your card will expire on DATE. Use link bellow to view details or upgrade. </p>
                                    <a href="pricing-tables.php" class="tfp-det-btn color2-bg">Details</a>
                                </div>
                                <!--Tariff Plan menu end-->
                            </div>
                        </div>
                    </section>
                    <!-- section end-->
                    <!-- section-->
                    <section class="middle-padding">
                        <div class="container">
                            <!--dasboard-wrap-->
                            <div class="dasboard-wrap fl-wrap">
                                <!-- dashboard-content--> 
                                <div class="dashboard-content fl-wrap">
                                    <div class="dashboard-list-box fl-wrap">
                                        <div class="dashboard-header fl-wrap">
                                            <h3>Your Recent Gallery</h3>
                                        </div>
                                        <!-- dashboard-list  -->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <span class="new-dashboard-item">New</span>
                                                <div class="dashboard-listing-table-image">
                                                    <a href="listing-single.html"><img src="images/gal/1.jpg" alt=""></a>
                                                </div>
                                                <div class="dashboard-listing-table-text">
                                                    <h4><a href="listing-single.html">Premium Plaza Hotel</a></h4>
                                                    <span class="dashboard-listing-table-address"><i class="far fa-map-marker"></i><a  href="#">USA 27TH Brooklyn NY</a></span>
                                                    <ul class="dashboard-listing-table-opt  fl-wrap">
                                                        <li><a href="#">Edit <i class="fal fa-edit"></i></a></li>
                                                        <li><a href="#" class="del-btn">Delete <i class="fal fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->    
                                        <!-- dashboard-list -->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <div class="dashboard-listing-table-image">
                                                    <a href="listing-single.html"><img src="images/gal/1.jpg" alt=""></a>
                                                </div>
                                                <div class="dashboard-listing-table-text">
                                                    <h4><a href="listing-single.html">Grand Hero Palace</a></h4>
                                                    <span class="dashboard-listing-table-address"><i class="far fa-map-marker"></i><a  href="#"> W 85th St, New York,  USA</a></span>
                                                    <ul class="dashboard-listing-table-opt  fl-wrap">
                                                        <li><a href="#">Edit <i class="fal fa-edit"></i></a></li>
                                                        <li><a href="#" class="del-btn">Delete <i class="fal fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->                                               
                                        <!-- dashboard-list  -->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <div class="dashboard-listing-table-image">
                                                    <a href="listing-single.html"><img src="images/gal/1.jpg" alt=""></a>
                                                </div>
                                                <div class="dashboard-listing-table-text">
                                                    <h4><a href="listing-single.html">Park Central</a></h4>
                                                    <span class="dashboard-listing-table-address"><i class="far fa-map-marker"></i><a  href="#">40 Journal Square Plaza, NJ,  USA</a></span>
                                                    <ul class="dashboard-listing-table-opt  fl-wrap">
                                                        <li><a href="#">Edit <i class="fal fa-edit"></i></a></li>
                                                        <li><a href="#" class="del-btn">Delete <i class="fal fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->   
                                        <!-- dashboard-list  -->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <div class="dashboard-listing-table-image">
                                                    <a href="listing-single.html"><img src="images/gal/1.jpg" alt=""></a>
                                                </div>
                                                <div class="dashboard-listing-table-text">
                                                    <h4><a href="listing-single.html">Gold Plaza Hotel </a></h4>
                                                    <span class="dashboard-listing-table-address"><i class="far fa-map-marker"></i><a  href="#">34-42 Montgomery St , NY, USA</a></span>
                                                    <ul class="dashboard-listing-table-opt  fl-wrap">
                                                        <li><a href="#">Edit <i class="fal fa-edit"></i></a></li>
                                                        <li><a href="#" class="del-btn">Delete <i class="fal fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->  
                                    </div>
                                    <!-- pagination-->
                                    <div class="pagination">
                                        <a href="#" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                                        <a href="#">1</a>
                                        <a href="#" class="current-page">2</a>
                                        <a href="#">3</a>
                                        <a href="#">4</a>
                                        <a href="#" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                                    </div>
                                </div>
                                <!-- dashboard-list-box end--> 
                            </div>
                            <!-- dasboard-wrap end-->
                        </div>
                    </section>
                    <div class="limit-box fl-wrap"></div>
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
            
            
<?php 

include("templates/footer.php");

?>