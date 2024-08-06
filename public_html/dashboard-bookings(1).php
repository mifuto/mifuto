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
                                <div class="dasboard-breadcrumbs breadcrumbs"><a href="#">Home</a><a href="#">Dashboard</a><span>Profile page</span></div>
                                <!--dasboard-sidebar-->
                                <div class="dasboard-sidebar">
                                    <div class="dasboard-sidebar-content fl-wrap">
                                        <div class="dasboard-avatar">
                                            <img src="images/avatar/1.jpg" alt="">
                                        </div>
                                        <div class="dasboard-sidebar-item fl-wrap">
                                            <h3>
                                                <span>Welcome </span>
                                                Jessie Manrty
                                            </h3>
                                        </div>
                                        <a href="dashboard-add-listing.html" class="ed-btn">Add Hotel</a>                                        
                                        <div class="user-stats fl-wrap">
                                            <ul>
                                                <li>
                                                    Listings	
                                                    <span>4</span>
                                                </li>
                                                <li>
                                                    Bookings
                                                    <span>32</span>	
                                                </li>
                                                <li>
                                                    Reviews	
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
                                            <a href="dashboard-myprofile.php"></i>Profile</a>
                                            <ul>
                                                <li><a href="dashboard-myprofile.php">Edit profile</a></li>
                                                <li><a href="dashboard-password.php">Change Password</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="dashboard-messages.php"><i class="far fa-envelope"></i> Messages <span>3</span></a></li>
                                        <li>
                                            <a href="dashboard-listing-gallery.php" ><i class="far fa-th-list"></i> My Gallery  </a>
                                            
                                        </li>
                                        <li><a href="dashboard-bookings.php"  class="user-profile-act"> <i class="far fa-calendar-check"></i> Recent Bookings <span>2</span></a>
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
                                <div   class="tfp-btn"><span>Tariff Plan : </span> <strong>Extended</strong></div>
                                <div class="tfp-det">
                                    <p>You Are on <a href="#">Extended</a> . Use link bellow to view details or upgrade. </p>
                                    <a href="#" class="tfp-det-btn color2-bg">Details</a>
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
                                            <h3>Bookings</h3>
                                        </div>
                                        <!-- dashboard-list end-->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <span class="new-dashboard-item">New</span>
                                                <div class="dashboard-message-avatar">
                                                    <img src="images/avatar/1.jpg" alt="">
                                                </div>
                                                <div class="dashboard-message-text">
                                                    <h4>Andy Smith - <span>27 December 2018</span></h4>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Listing Item :</span> :
                                                        <span class="booking-text"><a href="listing-sinle.html">Premium Plaza Hotel</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Persons :</span>   
                                                        <span class="booking-text">4 Peoples</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Booking Date :</span>   
                                                        <span class="booking-text">02.03.2018  - 10.03.2018</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">                                                               
                                                        <span class="booking-title">Mail :</span>  
                                                        <span class="booking-text"><a href="#" target="_top">yormail@domain.com</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Phone :</span>   
                                                        <span class="booking-text"><a href="tel:+496170961709" target="_top">+496170961709</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Payment State :</span> 
                                                        <span class="booking-text"> <strong class="done-paid">Paid  </strong>  using Paypal</span>
                                                    </div>
                                                    <span class="fw-separator"></span>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->    
                                        <!-- dashboard-list end-->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <div class="dashboard-message-avatar">
                                                    <img src="images/avatar/1.jpg" alt="">
                                                </div>
                                                <div class="dashboard-message-text">
                                                    <h4>Andy Smith - <span>27 December 2018</span></h4>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Listing Item :</span>  
                                                        <span class="booking-text"><a href="listing-sinle.html">Moonlight Hotel</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Persons :</span>   
                                                        <span class="booking-text">4 Peoples</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Booking Date :</span>  
                                                        <span class="booking-text">02.03.2018  - 10.03.2018</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">                                                               
                                                        <span class="booking-title">Mail :</span>  
                                                        <span class="booking-text"><a href="#" target="_top">yormail@domain.com</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Phone :</span>  
                                                        <span class="booking-text"><a  href="tel:+496170961709" target="_top">+496170961709</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Payment State :</span> 
                                                        <span class="booking-text"> <strong class="done-paid">Paid  </strong>  using Paypal</span>
                                                    </div>
                                                    <span class="fw-separator"></span>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->                                             
                                        <!-- dashboard-list end-->    
                                        <div class="dashboard-list">
                                            <div class="dashboard-message">
                                                <div class="dashboard-message-avatar">
                                                    <img src="images/avatar/avatar-bg.png" alt="">
                                                </div>
                                                <div class="dashboard-message-text">
                                                    <h4>Andy Smith - <span>27 December 2018</span></h4>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Listing Item :</span>  
                                                        <span class="booking-text"><a href="listing-sinle.html">Moonlight Hotel</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Persons :</span>   
                                                        <span class="booking-text">4 Peoples</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Booking Date :</span>   
                                                        <span class="booking-text">02.03.2018  - 10.03.2018</span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">                                                               
                                                        <span class="booking-title">Mail :</span>  
                                                        <span class="booking-text"><a href="#" target="_top">yormail@domain.com</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Phone :</span>  
                                                        <span class="booking-text"><a  href="tel:+496170961709" target="_top">+496170961709</a></span>
                                                    </div>
                                                    <div class="booking-details fl-wrap">
                                                        <span class="booking-title">Payment State :</span> 
                                                        <span class="booking-text"> <strong class="done-paid">Paid  </strong>  using Paypal</span>
                                                    </div>
                                                    <span class="fw-separator"></span>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- dashboard-list end-->                                            
                                    </div>
                                    <!-- pagination-->
                                    <div class="pagination">
                                        <a href="#" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                                        <a href="#" class="current-page">1</a>
                                        <a href="#">2</a>
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