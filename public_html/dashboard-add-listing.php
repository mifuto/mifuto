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
                                            <a href="dashboard.html"><i class="far fa-user"></i>Profile</a>
                                            <ul>
                                                <li><a href="dashboard-myprofile.html">Edit profile</a></li>
                                                <li><a href="dashboard-password.html">Change Password</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="dashboard-messages.html"><i class="far fa-envelope"></i> Messages <span>3</span></a></li>
                                        <li>
                                            <a href="dashboard-listing-table.html" class="user-profile-act"><i class="far fa-th-list"></i> My listigs  </a>
                                            <ul>
                                                <li><a href="#">Active</a><span>5</span></li>
                                                <li><a href="#">Pending</a><span>2</span></li>
                                                <li><a href="#">Expire</a><span>3</span></li>
                                            </ul>
                                        </li>
                                        <li><a href="dashboard-bookings.html"> <i class="far fa-calendar-check"></i> Bookings <span>2</span></a></li>
                                        <li><a href="dashboard-review.html"><i class="far fa-comments"></i> Reviews </a></li>
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
                    <!-- section  -->
                    <section class="middle-padding">
                        <div class="container">
                            <!--dasboard-wrap-->
                            <div class="dasboard-wrap fl-wrap">
                                <!-- dashboard-content--> 
                                <div class="dashboard-content fl-wrap">
                                    <div class="box-widget-item-header">
                                        <h3> Basic Informations</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <label>Hotel Title <i class="fal fa-briefcase"></i></label>
                                            <input type="text" placeholder="Name of your Hotel" value=""/>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Type / Category</label>
                                                    <div class="listsearch-input-item">
                                                        <select data-placeholder="Apartments" class="chosen-select no-search-select" >
                                                            <option>All Categories</option>
                                                            <option>Apartments</option>
                                                            <option>Hotel</option>
                                                            <option>Hostel</option>
                                                            <option>Guest House</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Keywords <i class="fa fa-key"></i></label>
                                                    <input type="text" placeholder="Maximum 15, should be separated by commas" value=""/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end--> 
                                    <div class="box-widget-item-header">
                                        <h3> Location / Contacts</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <label>Address<i class="fal fa-map-marker"></i></label>
                                            <input type="text" placeholder="Address of your Hotel" value=""/>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Longitude<i class="fal fa-long-arrow-alt-right"></i>  </label>
                                                    <input type="text" placeholder="Map Longitude"  id="long" value=""/>                                                
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Latitude<i class="fal fa-long-arrow-alt-down"></i> </label>
                                                    <input type="text" placeholder="Map Latitude"  id="lat" value=""/>                                            
                                                </div>
                                            </div>
											<div class="map-container">
												<div id="singleMap" class="vis-map" data-latitude="40.7427837" data-longitude="-73.11445617675781"></div>
											</div>
											
                                            <label>City / Location</label>
                                            <div class="listsearch-input-item">
                                                <select data-placeholder="City" class="chosen-select no-search-select" >
                                                    <option>All Cities</option>
                                                    <option>New York</option>
                                                    <option>London</option>
                                                    <option>Paris</option>
                                                    <option>Kiev</option>
                                                    <option>Moscow</option>
                                                    <option>Dubai</option>
                                                    <option>Rome</option>
                                                    <option>Beijing</option>
                                                </select>
                                            </div>
                                            <label>Email Address<i class="far fa-envelope"></i>  </label>
                                            <input type="text" placeholder="JessieManrty@domain.com" value=""/>
                                            <label>Phone<i class="far fa-phone"></i>  </label>
                                            <input type="text" placeholder="+7(123)987654" value=""/>
                                            <label> Website <i class="far fa-globe"></i>  </label>
                                            <input type="text" placeholder="themeforest.net" value=""/>
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->    
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Header Media</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <div class="row">
                                                <!--col --> 
                                                <div class="col-md-4">
                                                    <div class="add-list-media-header" style="margin-bottom:20px">
                                                        <label class="radio inline"> 
                                                        <input type="radio" name="gender"  checked>
                                                        <span>Background image</span> 
                                                        </label>
                                                    </div>
                                                    <div class="add-list-media-wrap">
                                                        <div class="add-list-media-wrap">
															<form class="fuzone">
																<div class="fu-text">
																	<span><i class="far fa-cloud-upload-alt"></i> Click here or drop files to upload</span>
																	<div class="photoUpload-files fl-wrap"></div>
																</div>
																<input type="file" class="upload">
															</form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--col end--> 
                                                <!--col --> 
                                                <div class="col-md-4">
                                                    <div class="add-list-media-header" style="margin-bottom:20px">
                                                        <label class="radio inline"> 
                                                        <input type="radio" name="gender">
                                                        <span>Carousel</span> 
                                                        </label>
                                                    </div>
                                                    <div class="add-list-media-wrap">
															<form class="fuzone">
																<div class="fu-text">
																	<span><i class="far fa-cloud-upload-alt"></i> Click here or drop files to upload</span>
																	<div class="photoUpload-files fl-wrap"></div>
																</div>
																<input type="file" class="upload">
															</form>
                                                    </div>
                                                </div>
                                                <!--col end--> 
                                                <!--col --> 
                                                <div class="col-md-4">
                                                    <div class="add-list-media-header" style="margin-bottom:20px">
                                                        <label class="radio inline"> 
                                                        <input type="radio" name="gender">
                                                        <span>Video</span> 
                                                        </label>
                                                    </div>
                                                    <div class="add-list-media-wrap">
                                                        <label>Youtube  <i class="fab fa-youtube"></i></label>
                                                        <input type="text" placeholder="https://www.youtube.com/" value=""/>   
                                                        <label>Vimeo <i class="fab fa-vimeo-v"></i></label>
                                                        <input type="text" placeholder="https://vimeo.com/" value=""/> 
                                                    </div>
                                                </div>
                                                <!--col end-->                                                   
                                            </div>
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                        
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Details</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <label>Text</label>
                                            <textarea cols="40" rows="3" placeholder="Datails"></textarea> 
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                       
                                    <!-- profile-edit-container end-->                                        
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Facilities</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <!-- Checkboxes -->
                                            <ul class="fl-wrap filter-tags">
                                                <li>
                                                    <input id="check-aaa5" type="checkbox" name="check" checked>
                                                    <label for="check-aaa5">Free WiFi</label>
                                                </li>
                                                <li>
                                                    <input id="check-bb5" type="checkbox" name="check" checked>
                                                    <label for="check-bb5">Parking</label>
                                                </li>
                                                <li>                                       
                                                    <input id="check-dd5" type="checkbox" name="check">
                                                    <label for="check-dd5">Fitness Center</label>
                                                </li>
                                                <li>                                          
                                                    <input id="check-cc5" type="checkbox" name="check">
                                                    <label for="check-cc5">Non-smoking Rooms</label>
                                                </li>
                                                <li>                                       
                                                    <input id="check-ff5" type="checkbox" name="check" checked>
                                                    <label for="check-ff5">Airport Shuttle</label>
                                                </li>
                                                <li>                                          
                                                    <input id="check-c4" type="checkbox" name="check">
                                                    <label for="check-c4">Air Conditioning</label>
                                                </li>
                                            </ul>
                                            <!-- Checkboxes end -->
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->  
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Facts</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <!-- hotel-facts -->
                                            <div class="hotel-facts fl-wrap">
                                                <input type="text" placeholder="Title" value=""/>                                                        
                                                <input type="text" placeholder="Number" value=""/>  
                                                <input type="text" placeholder="Icon" value=""/>
                                                <span class="add-button color-bg">Add Fact + </span>
                                            </div>
                                            <!-- hotel-facts end -->
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                                 
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Content Widgets</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <!-- act-widget--> 
                                                    <div class="act-widget fl-wrap">
                                                        <div class="act-widget-header">
                                                            <h4>1. Promo video</h4>
                                                            <div class="onoffswitch">
                                                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch5" checked>
                                                                <label class="onoffswitch-label" for="myonoffswitch5">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="add-list-media-wrap">
                                                            <label>Video url :  <i class="fa fa-youtube"></i></label>
                                                            <input type="text" placeholder="https://www.youtube.com/" value=""/>   
                                                        </div>
                                                    </div>
                                                    <!-- act-widget end-->
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- act-widget--> 
                                                    <div class="act-widget fl-wrap">
                                                        <div class="act-widget-header">
                                                            <h4>2. Gallery Thumbnails</h4>
                                                            <div class="onoffswitch">
                                                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch6" checked>
                                                                <label class="onoffswitch-label" for="myonoffswitch6">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="add-list-media-wrap">
															<form class="fuzone">
																<div class="fu-text">
																	<span><i class="far fa-cloud-upload-alt"></i> Click here or drop files to upload</span>
																	<div class="photoUpload-files fl-wrap"></div>
																</div>
																<input type="file" class="upload">
															</form>
                                                        </div>
                                                    </div>
                                                    <!-- act-widget end-->
                                                </div>
                                                <div class="col-md-4">
                                                    <!-- act-widget--> 
                                                    <div class="act-widget fl-wrap">
                                                        <div class="act-widget-header">
                                                            <h4>3. Slider</h4>
                                                            <div class="onoffswitch">
                                                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch7">
                                                                <label class="onoffswitch-label" for="myonoffswitch7">
                                                                <span class="onoffswitch-inner"></span>
                                                                <span class="onoffswitch-switch"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="add-list-media-wrap">
															<form class="fuzone">
																<div class="fu-text">
																	<span><i class="far fa-cloud-upload-alt"></i> Click here or drop files to upload</span>
																	<div class="photoUpload-files fl-wrap"></div>
																</div>
																<input type="file" class="upload">
															</form>
                                                        </div>
                                                    </div>
                                                    <!-- act-widget end-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                         
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Rooms</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <div class="room-add-item">
                                                <label>Room tite <i class="fal fa-warehouse-alt"></i></label>
                                                <input type="text" placeholder="Standart Numder" value=""/>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Price <i class="fal fa-dollar-sign"></i></label>
                                                        <input type="text" placeholder="56$" value=""/>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Guest <i class="fal fa-briefcase"></i></label>
                                                        <input type="text" placeholder=" Numder of guests" value=""/>                                                        
                                                    </div>
                                                </div>
                                                <label>Room Details</label>
                                                <textarea cols="40" rows="3" placeholder="Datails"></textarea>
                                                <div class="profile-edit-container" style="margin-top:30px">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Gallery</label>
                                                            <div class="add-list-media-wrap">
															<form class="fuzone">
																<div class="fu-text">
																	<span><i class="far fa-cloud-upload-alt"></i> Click here or drop files to upload</span>
																	<div class="photoUpload-files fl-wrap"></div>
																</div>
																<input type="file" class="upload">
															</form>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <!-- Checkboxes -->
                                                            <ul class="fl-wrap filter-tags" style="margin-top:24px">
                                                                <li>
                                                                    <input id="check-aaa51" type="checkbox" name="check" checked>
                                                                    <label for="check-aaa51">Free WiFi</label>
                                                                </li>
                                                                <li>
                                                                    <input id="check-bb51" type="checkbox" name="check" checked>
                                                                    <label for="check-bb51">1 Bathroom</label>
                                                                </li>
                                                                <li>                                       
                                                                    <input id="check-dd51" type="checkbox" name="check">
                                                                    <label for="check-dd51">Air conditioner</label>
                                                                </li>
                                                                <li>                                          
                                                                    <input id="check-cc51" type="checkbox" name="check">
                                                                    <label for="check-cc51">Tv Inside</label>
                                                                </li>
                                                                <li>                                       
                                                                    <input id="check-ff51" type="checkbox" name="check" checked>
                                                                    <label for="check-ff51">Breakfast</label>
                                                                </li>
                                                            </ul>
                                                            <!-- Checkboxes end -->
                                                            <!-- hotel-facts -->
                                                            <div class="hotel-facts fl-wrap">
                                                                <label>Facts   </label>
                                                                <input type="text" placeholder="Title" value=""/>                                                        
                                                                <input type="text" placeholder="Number" value=""/>  
                                                                <input type="text" placeholder="Icon" value=""/>
                                                                <span class="add-button color-bg">Add Fact + </span>
                                                            </div>
                                                            <!-- hotel-facts end -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="add-button color-bg">Add Room + </span>
                                    </div>
                                    <!-- profile-edit-container end-->                                             
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Sidebar Widgets</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <!-- act-widget--> 
                                            <div class="act-widget fl-wrap">
                                                <div class="act-widget-header">
                                                    <h4>1. Booking Form</h4>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                                                        <label class="onoffswitch-label" for="myonoffswitch">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- act-widget end--> 
                                            <!-- act-widget--> 
                                            <div class="act-widget fl-wrap">
                                                <div class="act-widget-header">
                                                    <h4>2. Discount </h4>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch2">
                                                        <label class="onoffswitch-label" for="myonoffswitch2">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <label>Promo Date<i class="fal fa-calendar"></i></label>
                                                <input type="text" placeholder="Date Example : 09/12/2019" value=""/>      
                                                <label>Promo Text</label>
                                                <textarea cols="40" rows="3" placeholder="Banner Text" style="margin-bottom:20px;"></textarea>
                                            </div>
                                            <!-- act-widget end-->                                                    
                                            <!-- act-widget--> 
                                            <div class="act-widget fl-wrap">
                                                <div class="act-widget-header">
                                                    <h4>3. Weather</h4>
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch3" checked>
                                                        <label class="onoffswitch-label" for="myonoffswitch3">
                                                        <span class="onoffswitch-inner"></span>
                                                        <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <label>City<i class="fab fa-mixcloud"></i></label>
                                                <input type="text" placeholder="City" value=""/> 
                                            </div>
                                            <!-- act-widget end--> 
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                    
                                    <div class="box-widget-item-header mat-top">
                                        <h3>Your  Socials</h3>
                                    </div>
                                    <!-- profile-edit-container--> 
                                    <div class="profile-edit-container">
                                        <div class="custom-form">
                                            <label>Facebook <i class="fab fa-facebook"></i></label>
                                            <input type="text" placeholder="https://www.facebook.com/" value=""/>
                                            <label>Twitter<i class="fab fa-twitter"></i>  </label>
                                            <input type="text" placeholder="https://twitter.com/" value=""/>
                                            <label>Vkontakte<i class="fab fa-vk"></i>  </label>
                                            <input type="text" placeholder="https://vk.com" value=""/>
                                            <label> Instagram <i class="fab fa-instagram"></i>  </label>
                                            <input type="text" placeholder="https://www.instagram.com/" value=""/>
                                            <button class="btn    color2-bg  float-btn">Send Listing<i class="fal fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                    <!-- profile-edit-container end-->                                    
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