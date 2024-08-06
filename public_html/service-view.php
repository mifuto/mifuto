<?php 

if (isset($_GET['key'])) {
    // Get the values of key and value parameters
    $key = $_GET['key'];

    date_default_timezone_set ("Asia/Calcutta");

    // Decode the base64 encoded values
    $decodedKey = base64_decode($key);

}else {
    header('Location: services.php');
    exit;
}

require_once('admin/config.php');

$logginStatus = false;
// session_start();
if (isset($_SESSION['isLogin']) && isset($_SESSION['mifutoUser']['id']) && $_SESSION['mifutoUser']['id'] != "") {
  $isLogin = $_SESSION['isLogin'];

  if ($isLogin) {
    $logginStatus = true;
  }
}


$stateName = 'Kerala';



$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

$services = [];
$servicesImages = [];

$sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link,cc.category_name as sub_cat,scc.center_name as service_center_name FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add left join tblservicecentersubcategory cc on cc.id = a.rating_val left join tblservicescenter scc on scc.id=a.servicescenter_id where ins.id = '$decodedKey' ";
$result = $DBC->query($sql);
$count = mysqli_num_rows($result);
if($count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($services,$row);
    }
}

$getimgsql = "SELECT a.* FROM tbeservice_folderfiles a where a.service_id='$decodedKey' and a.hide=0 order by a.id desc";
$imgresult = $DBC->query($getimgsql);
while ($row1 = mysqli_fetch_assoc($imgresult)) {
    array_push($servicesImages,$row1);
}


$row = mysqli_fetch_assoc($imgresult);

$serviceType = [];
$sql3 = "SELECT * FROM tblservicesaddingtype where active =0 order by center_name asc";
$result3 = $DBC->query($sql3);
$count3 = mysqli_num_rows($result3);
if($count3 > 0) {
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($serviceType,$row3);
    }
}


$anotherServices = [];

$selProvider=$services[0]['main_id'];
$where = " and a.id=$selProvider ";

$sqls = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,a.company_address,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,say.center_name as service_add,(SELECT file_path FROM tbeservice_folderfiles WHERE service_id = ins.id AND hide = 0 ORDER BY id DESC LIMIT 1) as file_path FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id != $decodedKey and ins.active = 0 $where  order by ins.id desc LIMIT 6 ";
$result4 = $DBC->query($sqls);
$count4 = mysqli_num_rows($result4);
if($count4 > 0) {
    while ($row4 = mysqli_fetch_assoc($result4)) {
        array_push($anotherServices,$row4);
    }
}

$selservicescenter_id=$services[0]['servicescenter_id'];
$simillerServices = [];
$sqls1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,a.company_address,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,say.center_name as service_add,(SELECT file_path FROM tbeservice_folderfiles WHERE service_id = ins.id AND hide = 0 ORDER BY id DESC LIMIT 1) as file_path FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id != $decodedKey and ins.active = 0 and a.servicescenter_id = $selservicescenter_id  order by ins.id desc LIMIT 6 ";
$result5 = $DBC->query($sqls1);
$count5= mysqli_num_rows($result5);
if($count5 > 0) {
    while ($row5 = mysqli_fetch_assoc($result5)) {
        array_push($simillerServices,$row5);
    }
}



$Cards = [];
if($logginStatus) $user_id = $_SESSION['mifutoUser']['id'];
else $user_id = '';



$numberOfServiceAvl = 0;
 
$sql3 = "SELECT * FROM place_order_usercard WHERE razorpay_payment_status=1 AND completed=1 AND isNew=1 AND user_id='$user_id' AND exp_date >= CURDATE() ";

$result3 = $DBC->query($sql3);

$count3 = mysqli_num_rows($result3);

if($count3 > 0) {		
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($Cards,$row3);
      
    }
}


include("templates/header.php");

?>

<style>
    .brucher-link {
      color: blue; /* Set link color to blue */
      padding-bottom: 3px; /* Add space between text and underline */
    }
    
    .no-border-ls {
        border-bottom: none !important;
        padding-bottom: 5px !important;
        margin-bottom: 0px !important;
    }
    
  

</style>

  
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!--  section  -->
                    <section class="list-single-hero" data-scrollax-parent="true" id="sec1">
                        <div class="bg par-elem "  data-bg="<?=$servicesImages[0]['file_path']?>" data-scrollax="properties: { translateY: '30%' }">
                        </div>
                        <div class="list-single-hero-title fl-wrap">
                            <div class="container">
                                
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="listing-rating-wrap">
                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                        </div>
                                        <h2><span><?=$services[0]['name']?></span></h2>
                                        <div class="list-single-header-contacts fl-wrap">
                                            <ul>
                                                <li><i class="far fa-phone"></i><a  href="#"><?=$services[0]['company_phone']?></a></li>
                                                <li><i class="far fa-map-marker-alt"></i><a  href="#"><?=$services[0]['city_id']?>, <?=$services[0]['state_id']?>, <?=$services[0]['county_id']?></a></li>
                                                <li><i class="far fa-envelope"></i><a  href="#"><?=$services[0]['company_mail']?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <!--  list-single-hero-details-->
                                        <div class="list-single-hero-details fl-wrap">
                                            <!--  list-single-hero-rating-->
                                            <div class="list-single-hero-rating">
                                                <div class="rate-class-name">
                                                    <div class="score"><strong>Very Good</strong>2 Reviews </div>
                                                    <span>4.5</span>                                             
                                                </div>
                                                <!-- list-single-hero-rating-list-->
                                                <div class="list-single-hero-rating-list">
                                                    <!-- rate item-->
                                                    <div class="rate-item fl-wrap">
                                                        <div class="rate-item-title fl-wrap"><span>Cleanliness</span></div>
                                                        <div class="rate-item-bg" data-percent="100%">
                                                            <div class="rate-item-line color-bg"></div>
                                                        </div>
                                                        <div class="rate-item-percent">5.0</div>
                                                    </div>
                                                    <!-- rate item end-->
                                                    <!-- rate item-->
                                                    <div class="rate-item fl-wrap">
                                                        <div class="rate-item-title fl-wrap"><span>Comfort</span></div>
                                                        <div class="rate-item-bg" data-percent="90%">
                                                            <div class="rate-item-line color-bg"></div>
                                                        </div>
                                                        <div class="rate-item-percent">5.0</div>
                                                    </div>
                                                    <!-- rate item end-->                                                        
                                                    <!-- rate item-->
                                                    <div class="rate-item fl-wrap">
                                                        <div class="rate-item-title fl-wrap"><span>Staf</span></div>
                                                        <div class="rate-item-bg" data-percent="80%">
                                                            <div class="rate-item-line color-bg"></div>
                                                        </div>
                                                        <div class="rate-item-percent">4.0</div>
                                                    </div>
                                                    <!-- rate item end-->  
                                                    <!-- rate item-->
                                                    <div class="rate-item fl-wrap">
                                                        <div class="rate-item-title fl-wrap"><span>Facilities</span></div>
                                                        <div class="rate-item-bg" data-percent="90%">
                                                            <div class="rate-item-line color-bg"></div>
                                                        </div>
                                                        <div class="rate-item-percent">4.5</div>
                                                    </div>
                                                    <!-- rate item end--> 
                                                </div>
                                                <!-- list-single-hero-rating-list end-->
                                            </div>
                                            <!--  list-single-hero-rating  end-->
                                            <div class="clearfix"></div>
                                            <!-- list-single-hero-links-->
                                            <div class="list-single-hero-links">
                                                <a class="lisd-link" href="booking-single.html"><i class="fal fa-bookmark"></i> Book Now</a>
                                                <a class="custom-scroll-link lisd-link" href="#sec6"><i class="fal fa-comment-alt-check"></i> Add review</a>
                                            </div>
                                            <!--  list-single-hero-links end-->                                            
                                        </div>
                                        <!--  list-single-hero-details  end-->
                                    </div>
                                </div>
                                <div class="breadcrumbs-hero-buttom fl-wrap">
                                    <div class="breadcrumbs"><a href="#">Home</a><a href="#">Services</a><a href="#">Popular Services</a><span><?=$services[0]['name']?></span></div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--  section  end-->
                    
              
                    
                    <!--  section  -->
                    <section class="grey-blue-bg small-padding scroll-nav-container" id="sec2">
                        <!--  scroll-nav-wrapper  -->
                        <div class="scroll-nav-wrapper fl-wrap">
                            <div class="hidden-map-container fl-wrap">
                                <input id="pac-input" class="controls fl-wrap controls-mapwn" type="text" placeholder="What Nearby ?   Bar , Gym , Restaurant ">
                                <div class="map-container">
                                    <div id="singleMap" data-latitude="40.7427837" data-longitude="-73.11445617675781"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="container">
                                <nav class="scroll-nav scroll-init">
                                    <ul>
                                        <li><a class="act-scrlink" href="#sec1">Top</a></li>
                                        <li><a href="#sec2">Details</a></li>
                                        <li><a href="#sec22">Price Details</a></li>
                                        <li><a href="#sec23">Deliverables</a></li>
                                        <li><a href="#sec3">Amenities</a></li>
                                        <li><a href="#sec4">Gallerys</a></li>
                                        <li><a href="#sec5">Reviews</a></li>
                                    </ul>
                                </nav>
                                <a href="#" class="show-hidden-map">  <span>On The Map</span> <i class="fal fa-map-marked-alt"></i></a>
                            </div>
                        </div>
                        <!--  scroll-nav-wrapper end  -->     
                        
                        <div class="container hide" id="serviceUnavailabeMeg">
                            <div class="row" style="background: white;padding: 20px;margin-bottom: 20px;">
                                <div class="col-md-12">
                                    
                                    <h1 style="color:red;padding-bottom:15px;">ATTENTION VALUED CUSTOMERS,</h1>
                                    <h3 style="color:red;padding-bottom:15px;">WE APOLOGIZE FOR THE INCONVENIENCE BUT OUR SERVICE IS TEMPORARILY UNAVAILABLE AT THE MOMENT.<br> WE ARE WORKING DILIGENTLY TO RESOLVE THE ISSUE AND GET BACK UP AND RUNNING AS SOON AS POSSIBLE. <br>YOUR PATIENCE AND UNDERSTANDING ARE GREATLY APPRECIATED.</h3>
                                    
                                </div>
                                    
                            </div>
                                    
                        </div>
                        
                        
                        
                        
                        <!--   container  -->
                        <div class="container">
                            <!--   row  -->
                            <div class="row">
                                <!--   datails -->
                                <div class="col-md-8">
                                    <div class="list-single-main-container ">
                                        <!-- fixed-scroll-column  -->
                                        <div class="fixed-scroll-column">
                                            <div class="fixed-scroll-column-item fl-wrap">
                                                <div class="showshare sfcs fc-button"><i class="far fa-share-alt"></i><span>Share </span></div>
                                                <div class="share-holder fixed-scroll-column-share-container">
                                                    <div class="share-container  isShare"></div>
                                                </div>
                                                <a class="fc-button custom-scroll-link" href="#sec6"><i class="far fa-comment-alt-check"></i> <span>  Add review </span></a>
                                                <a class="fc-button" href="#"><i class="far fa-heart"></i> <span>Save</span></a>
                                                <a class="fc-button" href="booking-single.html"><i class="far fa-bookmark"></i> <span> Book Now </span></a>
                                            </div>
                                        </div>
                                        <!-- fixed-scroll-column end   -->
                                        <div class="list-single-main-media fl-wrap">
                                            <!-- gallery-items   -->
                                            <div class="gallery-items grid-small-pad  list-single-gallery three-coulms lightgallery">
                                                
                                                <?php if(count($servicesImages) > 0) {
                                                    foreach ($servicesImages as $key => $album) { 
                                       
                                                ?>
                                                
                                                <div class="gallery-item ">
                                                    <div class="grid-item-holder">
                                                        <div class="box-item">
                                                            <img  src="<?=$album['file_path']?>"   alt="">
                                                            <a href="<?=$album['file_path']?>" class="gal-link popup-image"><i class="fa fa-search"></i></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <?php } ?>
                                                <?php } ?>
                                                
                                                
                                         
                                                
                                                
                                            </div>
                                            <!-- end gallery items -->                                          
                                        </div>
                                        <!-- list-single-header end -->
                                        <div class="list-single-facts fl-wrap">
                                            <!-- inline-facts -->
                                            <div class="inline-facts-wrap">
                                                <div class="inline-facts">
                                                    <i class="fal fa-bed"></i>
                                                    <div class="milestone-counter">
                                                        <div class="stats animaper">
                                                            45
                                                        </div>
                                                    </div>
                                                    <h6>Total Finished Service</h6>
                                                </div>
                                            </div>
                                            <!-- inline-facts end -->
                                            <!-- inline-facts  -->
                                            <div class="inline-facts-wrap">
                                                <div class="inline-facts">
                                                    <i class="fal fa-users"></i>
                                                    <div class="milestone-counter">
                                                        <div class="stats animaper">
                                                            2557
                                                        </div>
                                                    </div>
                                                    <h6>Customers Every Year</h6>
                                                </div>
                                            </div>
                                            <!-- inline-facts end -->
                                            <!-- inline-facts -->
                                            <div class="inline-facts-wrap">
                                                <div class="inline-facts">
                                                    <i class="fal fa-taxi"></i>
                                                    <div class="milestone-counter">
                                                        <div class="stats animaper">
                                                            15
                                                        </div>
                                                    </div>
                                                    <h6>Distance to Center</h6>
                                                </div>
                                            </div>
                                            <!-- inline-facts end -->
                                            <!-- inline-facts -->
                                            <div class="inline-facts-wrap">
                                                <div class="inline-facts">
                                                    <i class="fal fa-cocktail"></i>
                                                    <div class="milestone-counter">
                                                        <div class="stats animaper">
                                                            4
                                                        </div>
                                                    </div>
                                                    <h6>Restaurant Inside</h6>
                                                </div>
                                            </div>
                                            <!-- inline-facts end -->                                                                        
                                        </div>
                                        <!--   list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Description for <?=$services[0]['name']?> </h3>
                                            </div>
                                            <p><?=$services[0]['description']?> </p>
                                            <p></p>
                                            <a href="https://vimeo.com/70851162" class="btn flat-btn color-bg big-btn float-btn image-popup">Video Presentation <i class="fal fa-play"></i></a>
                                        </div>
                                        <!--   list-single-main-item end -->
                                        
                                        <!--   list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec22">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Price Details</h3>
                                            </div>
                                            <div class="listing-features fl-wrap">
                                                
                                                <p>
                                                    Thank you for considering our services. Here are the details regarding pricing and payment:<br>
                                                    <b>Payment Structure:</b> <br>
                                                    
                                                    A 50% advance payment is required to confirm your booking.
                                                    The remaining balance is due on the day of the photo shoot.<br>
                                                    <b>Payment Methods:</b><br>
                                                    
                                                     All payments must be made online through your Mifuto account.
                                                    We do not accept cash payments.<br>
                                                    <b>Tipping Policy:</b><br>
                                                    
                                                    Please do not provide tips to our photographers.
                                                    We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.
                                                    
                                                    
                                                </p>
                                                
                                                
                                            </div>
                                           
                                        </div>
                                        <!--   list-single-main-item end -->  
                                        
                                        <!--   list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec23">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Deliverables you receive</h3>
                                            </div>
                                            <div class="listing-features fl-wrap">
                                                
                                                <p>
                                                    
                                                    <?php if($logginStatus){ ?>
                                                        Dear <b><?=$_SESSION['Username']?>,</b>
                                                    <?php }else{ ?>
                                                        Dear <b>Customer,</b>
                                                    <?php } ?>
                                                    <br>
                                                    
                                                    Thank you for choosing our services <b><?=$services[0]['name']?></b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>
                                                    
                                                    Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>
                                                    Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>
                                                    2 Photo Frames<br>
                                                    1 Calendar<br>
                                                    We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>
                                                    
                                                    Best regards,
                                                    
                                                </p>
                                                
                                                
                                            </div>
                                           
                                        </div>
                                        <!--   list-single-main-item end --> 
                                        
                                        
                                        
                                        
                                        
                                        
                                        <!--   list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec3">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Amenities</h3>
                                            </div>
                                            <div class="listing-features fl-wrap">
                                                <ul>
                                                    
                                                    <?php
                                                    
                                                        if($services[0]['provide_wifi'] == 1) echo '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
                                                        if($services[0]['provide_parking'] == 1) echo '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
                                                        if($services[0]['provide_ac'] == 1) echo '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
                                                        if($services[0]['provide_rooftop'] == 1) echo '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
                                                        if($services[0]['provide_bathroom'] == 1) echo '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
                                                        
                                                         if($services[0]['provide_welcome_drink'] == 1) echo '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
                                                        if($services[0]['provide_food'] == 1) echo '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
                                                        if($services[0]['provide_seperate_cabin'] == 1) echo '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
                                                        if($services[0]['provide_common_restaurant'] == 1) echo '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
                                                    
                                                    ?>
                                                    
                                                
                                                </ul>
                                            </div>
                                            <!--<span class="fw-separator"></span>-->
                                            <!--<div class="list-single-main-item-title no-dec-title fl-wrap">-->
                                            <!--    <h3>Tags</h3>-->
                                            <!--</div>-->
                                            <!--<div class="list-single-tags tags-stylwrap">-->
                                            <!--    <a href="#">Hotel</a>-->
                                            <!--    <a href="#">Hostel</a>-->
                                            <!--    <a href="#">Room</a>-->
                                            <!--    <a href="#">Spa</a>-->
                                            <!--    <a href="#">Restourant</a>-->
                                            <!--    <a href="#">Parking</a>                                                                               -->
                                            <!--</div>-->
                                        </div>
                                        <!--   list-single-main-item end -->     
                                        <!-- accordion-->
                                        <div class="accordion mar-top">
                                            <a class="toggle act-accordion" href="#"> Service/Property Usage Instructions  <span></span></a>
                                            <div class="accordion-inner visible">
                                                <p><?=$services[0]['propert_instructions']?></p>
                                            </div>
                                            <a class="toggle" href="#"> Terms and Conditions of the Company  <span></span></a>
                                            <div class="accordion-inner">
                                                <p><?=$services[0]['terms_and_conditions']?></p>
                                            </div>
                                            <a class="toggle" href="#"> Our Brochures  <span></span></a>
                                            <div class="accordion-inner">
                                                <p><div id="displayCompanyBruchers"></div></p>
                                            </div>
                                        </div>
                                        <!-- accordion end -->                                                     
                                        <!--   list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec4">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Recently complated services</h3>
                                            </div>
                                            <!--   rooms-container -->
                                            <div class="rooms-container fl-wrap">
                                                <!--  rooms-item -->
                                                <div class="rooms-item fl-wrap">
                                                    <div class="rooms-media">
                                                        <img src="images/gal/01.jpg" alt="">
                                                        <div class="dynamic-gal more-photos-button" data-dynamicPath="[{'src': 'images/gal/slider/1.jpg'}, {'src': 'images/gal/slider/1.jpg'},{'src': 'images/gal/slider/1.jpg'}]">  View Gallery <span>3 photos</span> <i class="far fa-long-arrow-right"></i></div>
                                                    </div>
                                                    <div class="rooms-details">
                                                        <div class="rooms-details-header fl-wrap">
                                                            <span class="rooms-price">$81 <strong> / person</strong></span>
                                                            <h3>Mery's Baptism</h3>
                                                            <h5>Max Guests: <span>50 persons</span></h5>
                                                        </div>
                                                        <p>Morbi varius, nulla sit amet rutrum elementum, est elit finibus tellus, ut tristique elit risus at metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                                        <div class="facilities-list fl-wrap">
                                                            <ul>
                                                                <li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>
                                                                <li><i class="fal fa-bath"></i><span>1 Bathroom</span></li>
                                                                <li><i class="fal fa-snowflake"></i><span>Air conditioner</span></li>
                                                                <li><i class="fal fa-tv"></i><span> Tv Inside</span></li>
                                                                <li><i class="fas fa-concierge-bell"></i><span>Breakfast</span></li>
                                                            </ul>
                                                            <a href="rooms/room1.html" class="btn color-bg ajax-link">Details<i class="fas fa-caret-right"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  rooms-item end -->
                                                <!--  rooms-item -->
                                                <div class="rooms-item fl-wrap">
                                                    <div class="rooms-media">
                                                        <img src="images/gal/02.jpg" alt="">
                                                        <div class="dynamic-gal more-photos-button" data-dynamicPath="[{'src': 'images/gal/slider/1.jpg'}, {'src': 'images/gal/slider/1.jpg'}, {'src': 'images/gal/slider/1.jpg'} ]">View Gallery <span>3 photos</span> <i class="far fa-long-arrow-right"></i></div>
                                                    </div>
                                                    <div class="rooms-details">
                                                        <div class="rooms-details-header fl-wrap">
                                                            <span class="rooms-price">$122 <strong> / person</strong></span>
                                                            <h3>Superior Double Room</h3>
                                                            <h5>Max Guests: <span>4 persons</span></h5>
                                                        </div>
                                                        <p>Morbi varius, nulla sit amet rutrum elementum, est elit finibus tellus, ut tristique elit risus at metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                                        <div class="facilities-list fl-wrap">
                                                            <ul>
                                                                <li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>
                                                                <li><i class="fal fa-parking"></i><span>Parking</span></li>
                                                                <li><i class="fal fa-smoking-ban"></i><span>Non-smoking Rooms</span></li>
                                                                <li><i class="fal fa-utensils"></i><span> Restaurant</span></li>
                                                            </ul>
                                                            <a href="rooms/room2.html" class="btn color-bg ajax-link">Details<i class="fas fa-caret-right"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  rooms-item end -->   
                                                <!--  rooms-item -->
                                                <div class="rooms-item fl-wrap">
                                                    <div class="rooms-media">
                                                        <img src="images/gal/04.jpg" alt="">
                                                        <div class="dynamic-gal more-photos-button" data-dynamicPath="[{'src': 'images/gal/slider/1.jpg'},{'src': 'images/gal/slider/1.jpg'}, {'src': 'images/gal/slider/1.jpg'},{'src': 'images/gal/slider/1.jpg'}]"> View Gallery <span>4 photos</span> <i class="far fa-long-arrow-right"></i> </div>
                                                    </div>
                                                    <div class="rooms-details">
                                                        <div class="rooms-details-header fl-wrap">
                                                            <span class="rooms-price">$310 <strong> / person</strong></span>
                                                            <h3>Deluxe Single Room</h3>
                                                            <h5>Max Guests: <span>2 persons</span></h5>
                                                        </div>
                                                        <p>Morbi varius, nulla sit amet rutrum elementum, est elit finibus tellus, ut tristique elit risus at metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                                        <div class="facilities-list fl-wrap">
                                                            <ul>
                                                                <li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>
                                                                <li><i class="fal fa-parking"></i><span>Parking</span></li>
                                                                <li><i class="fal fa-smoking-ban"></i><span>Non-smoking Rooms</span></li>
                                                                <li><i class="fal fa-utensils"></i><span> Restaurant</span></li>
                                                            </ul>
                                                            <a href="rooms/room3.html" class="btn color-bg ajax-link">Details<i class="fas fa-caret-right"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--  rooms-item end -->                                                      
                                            </div>
                                            <!--   rooms-container end -->
                                        </div>
                                        <!-- list-single-main-item end -->
                                        <!-- list-single-main-item -->   
                                        <div class="list-single-main-item fl-wrap" id="sec5">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Item Reviews -  <span> 2 </span></h3>
                                            </div>
                                            <!--reviews-score-wrap-->   
                                            <div class="reviews-score-wrap fl-wrap">
                                                <div class="review-score-total">
                                                    <span>
                                                    4.5
                                                    <strong>Very Good</strong>
                                                    </span>
                                                    <a href="#" class="color2-bg">Add Review</a>
                                                </div>
                                                <div class="review-score-detail">
                                                    <!-- review-score-detail-list-->
                                                    <div class="review-score-detail-list">
                                                        <!-- rate item-->
                                                        <div class="rate-item fl-wrap">
                                                            <div class="rate-item-title fl-wrap"><span>Cleanliness</span></div>
                                                            <div class="rate-item-bg" data-percent="100%">
                                                                <div class="rate-item-line color-bg"></div>
                                                            </div>
                                                            <div class="rate-item-percent">5.0</div>
                                                        </div>
                                                        <!-- rate item end-->
                                                        <!-- rate item-->
                                                        <div class="rate-item fl-wrap">
                                                            <div class="rate-item-title fl-wrap"><span>Comfort</span></div>
                                                            <div class="rate-item-bg" data-percent="90%">
                                                                <div class="rate-item-line color-bg"></div>
                                                            </div>
                                                            <div class="rate-item-percent">5.0</div>
                                                        </div>
                                                        <!-- rate item end-->                                                        
                                                        <!-- rate item-->
                                                        <div class="rate-item fl-wrap">
                                                            <div class="rate-item-title fl-wrap"><span>Staf</span></div>
                                                            <div class="rate-item-bg" data-percent="80%">
                                                                <div class="rate-item-line color-bg"></div>
                                                            </div>
                                                            <div class="rate-item-percent">4.0</div>
                                                        </div>
                                                        <!-- rate item end-->  
                                                        <!-- rate item-->
                                                        <div class="rate-item fl-wrap">
                                                            <div class="rate-item-title fl-wrap"><span>Facilities</span></div>
                                                            <div class="rate-item-bg" data-percent="90%">
                                                                <div class="rate-item-line color-bg"></div>
                                                            </div>
                                                            <div class="rate-item-percent">4.5</div>
                                                        </div>
                                                        <!-- rate item end--> 
                                                    </div>
                                                    <!-- review-score-detail-list end-->
                                                </div>
                                            </div>
                                            <!-- reviews-score-wrap end -->   
                                            <div class="reviews-comments-wrap">
                                                <!-- reviews-comments-item -->  
                                                <div class="reviews-comments-item">
                                                    <div class="review-comments-avatar">
                                                        <img src="images/avatar/1.jpg" alt=""> 
                                                    </div>
                                                    <div class="reviews-comments-item-text">
                                                        <h4><a href="#">Liza Rose</a></h4>
                                                        <div class="review-score-user">
                                                            <span>4.4</span>
                                                            <strong>Good</strong>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <p>" Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. "</p>
                                                        <div class="reviews-comments-item-date"><span><i class="far fa-calendar-check"></i>12 April 2018</span><a href="#"><i class="fal fa-reply"></i> Reply</a></div>
                                                    </div>
                                                </div>
                                                <!--reviews-comments-item end--> 
                                                <!-- reviews-comments-item -->  
                                                <div class="reviews-comments-item">
                                                    <div class="review-comments-avatar">
                                                        <img src="images/avatar/1.jpg" alt=""> 
                                                    </div>
                                                    <div class="reviews-comments-item-text">
                                                        <h4><a href="#">Adam Koncy</a></h4>
                                                        <div class="review-score-user">
                                                            <span>4.7</span>
                                                            <strong>Very Good</strong>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <p>" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. "</p>
                                                        <div class="reviews-comments-item-date"><span><i class="far fa-calendar-check"></i>03 December 2017</span><a href="#"><i class="fal fa-reply"></i> Reply</a></div>
                                                    </div>
                                                </div>
                                                <!--reviews-comments-item end-->                                                                  
                                            </div>
                                        </div>
                                        <!-- list-single-main-item end -->   
                                        <!-- list-single-main-item -->   
                                        <div class="list-single-main-item fl-wrap" id="sec6">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Add Review</h3>
                                            </div>
                                            <!-- Add Review Box -->
                                            <div id="add-review" class="add-review-box">
                                                <!-- Review Comment -->
                                                <form id="add-comment" class="add-comment  custom-form" name="rangeCalc" >
                                                    <fieldset>
                                                        <div class="review-score-form fl-wrap">
                                                            <div class="review-range-container">
                                                                <!-- review-range-item-->
                                                                <div class="review-range-item">
                                                                    <div class="range-slider-title">Cleanliness</div>
                                                                    <div class="range-slider-wrap ">
                                                                        <input type="text" class="rate-range" data-min="0" data-max="5"  name="rgcl"  data-step="1" value="4">
                                                                    </div>
                                                                </div>
                                                                <!-- review-range-item end --> 
                                                                <!-- review-range-item-->
                                                                <div class="review-range-item">
                                                                    <div class="range-slider-title">Comfort</div>
                                                                    <div class="range-slider-wrap ">
                                                                        <input type="text" class="rate-range" data-min="0" data-max="5"  name="rgcl"  data-step="1"  value="1">
                                                                    </div>
                                                                </div>
                                                                <!-- review-range-item end --> 
                                                                <!-- review-range-item-->
                                                                <div class="review-range-item">
                                                                    <div class="range-slider-title">Staf</div>
                                                                    <div class="range-slider-wrap ">
                                                                        <input type="text" class="rate-range" data-min="0" data-max="5"  name="rgcl"  data-step="1" value="5" >
                                                                    </div>
                                                                </div>
                                                                <!-- review-range-item end --> 
                                                                <!-- review-range-item-->
                                                                <div class="review-range-item">
                                                                    <div class="range-slider-title">Facilities</div>
                                                                    <div class="range-slider-wrap">
                                                                        <input type="text" class="rate-range" data-min="0" data-max="5"  name="rgcl"  data-step="1" value="3">
                                                                    </div>
                                                                </div>
                                                                <!-- review-range-item end -->                                     
                                                            </div>
                                                            <div class="review-total">
                                                                <span><input type="text" name="rg_total"  data-form="AVG({rgcl})" value="0"></span>    
                                                                <strong>Your Score</strong>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label><i class="fal fa-user"></i></label>
                                                                <input type="text" placeholder="Your Name *" value=""/>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label><i class="fal fa-envelope"></i>  </label>
                                                                <input type="text" placeholder="Email Address*" value=""/>
                                                            </div>
                                                        </div>
                                                        <textarea cols="40" rows="3" placeholder="Your Review:"></textarea>
                                                    </fieldset>
                                                    <button class="btn  big-btn flat-btn float-btn color2-bg" style="margin-top:30px">Submit Review <i class="fal fa-paper-plane"></i></button>
                                                </form>
                                            </div>
                                            <!-- Add Review Box / End -->
                                        </div>
                                        <!-- list-single-main-item end -->                                    
                                    </div>
                                </div>
                                <!--   datails end  -->
                                <!--   sidebar  -->
                                <div class="col-md-4">
                                    <!--box-widget-wrap -->  
                                    <div class="box-widget-wrap">
                                        <!--box-widget-item -->
                                        <div class="box-widget-item fl-wrap">
                                            <div class="box-widget">
                                                <div class="box-widget-content">
                                                    <div class="box-widget-item-header">
                                                        <h3> Book This Service</h3>
                                                    </div>
                                                    <form name="bookFormCalc"   class="book-form custom-form">
                                                        <fieldset>
                                                            
                                                            
                                                            
                                                            
                                                            <div class="cal-item">
                                                                <div class="listsearch-input-item">
                                                                    <label>Event Category</label>
                                                                    
                                                                    <?php if($services[0]['sub_cat'] == ""){ ?>
                                                                        <label > <b style="color: #F9B90F;"><?=$services[0]['service_center_name']?> </b> </label>
                                                                    
                                                                    <?php }else{ ?>
                                                                    
                                                                    <label > <b style="color: #F9B90F;"><?=$services[0]['service_center_name']?> - <?=$services[0]['sub_cat']?></b> </label>
                                                                    
                                                                        
                                                                    <?php } ?>
                                                                    
                                                                    
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item">
                                                                <div class="listsearch-input-item">
                                                                    <label>Event Type</label>
                                                                    <select data-placeholder="Event Type" name="eventType" id="eventType"  class="chosen-select no-search-select" disabled>
                                                                        <option value="" selected>Select Event Type</option>
                                                                        <?php if(count($serviceType) > 0) {
                                                                            foreach ($serviceType as $key => $album) { 
                                                                                if($services[0]['eventTypeID'] == $album['id']) $slc = 'selected';
                                                                                else $slc ='';
                                                                            ?>
                                                                            
                                                                            <option value="<?=$album['id']?>" <?=$slc?>><?=$album['center_name']?></option>
                                                                            
                                                                            <?php } ?>
                                                                            <?php } ?>
                                                                    </select>
                                                                   
                                                                </div>
                                                            </div>
                                                            
                                                             <div class="cal-item">
                                                                <div class="quantity-item  fl-wrap">
                                                                    <label style="color: #F9B90F;"> This service is for maximum <?=$services[0]['number_of_members']?> peoples</label>
                                                                   
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item" >
                                                                <div class="quantity-item  fl-wrap">
                                                                    <label> Extra Peoples</label>
                                                                    <div class="quantity">
                                                                        <input type="number" name="numOfHeadcount" id="numOfHeadcount" min="0" max="1000" step="1" value="0" onchange="getServicePrice();">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                             <div class="cal-item" >
                                                                <div class="listsearch-input-item" >
                                                                   <label style="border-bottom: 1px solid #eee;">&nbsp;</label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item" >
                                                                <div class="quantity-item  fl-wrap">
                                                                    <label> Photographer</label>
                                                                    <div class="quantity">
                                                                        <input type="number" name="numOfPhotographer" id="numOfPhotographer" min="0" max="10" step="1" value="1" onchange="getServicePrice();">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item">
                                                                <div class="quantity-item  fl-wrap">
                                                                    <label> Videographer</label>
                                                                    <div class="quantity">
                                                                        <input type="number" name="numOfVideographer" id="numOfVideographer" min="0" max="10" step="1" value="1" onchange="getServicePrice();">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item" >
                                                                <div class="listsearch-input-item" >
                                                                   <label style="border-bottom: 1px solid #eee;">&nbsp;</label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item">
                                                                <div class="listsearch-input-item">
                                                                    <label style="color: #F9B90F;"> This service is for a maximum of <span id="minServiceTime"></span> minutes only Please select if you need more time  </label> 
                                                                    
                                                                </div>
                                                                 
                                                            </div>
                                                            
                                                              <div class="cal-item">
                                                                <div class="quantity-item  fl-wrap" style="width: 32% !important;">
                                                                    <div class="quantity">
                                                                        <input type="number" name="moreDays" id="moreDays" min="0" max="10" step="1" value="0" onchange="getServicePrice();">
                                                                    </div>
                                                                     <label> days</label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item">
                                                                <div class="quantity-item  fl-wrap" style="width: 32% !important;">
                                                                    <div class="quantity">
                                                                        <input type="number" name="moreHrs" id="moreHrs" min="0" max="12" step="1" value="0" onchange="getServicePrice();">
                                                                    </div>
                                                                     <label> Hrs</label>
                                                                </div>
                                                            </div>
                                                            
                                                             <div class="cal-item">
                                                                <div class="quantity-item  fl-wrap" style="width: 32% !important;">
                                                                    <div class="quantity">
                                                                        <input type="number" name="moreMins" id="moreMins" min="0" max="60" step="1" value="0" onchange="getServicePrice();">
                                                                    </div>
                                                                     <label> Mins</label>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item" >
                                                                <div class="listsearch-input-item" >
                                                                   <label style="border-bottom: 1px solid #eee;">&nbsp;</label>
                                                                </div>
                                                            </div>
                                                            
                                                           
                                                            
                                                            <div class="cal-item">
                                                                <div class="bookdate-container  fl-wrap">
                                                                    <label>Event Date</label>
                                                                    <input type="date"  class="coustom-input"  name="EventDate" id="EventDate" onchange="getServicePrice();"   value=""/>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="cal-item">
                                                                <div class="bookdate-container  fl-wrap">
                                                                    <label>Event Start Time</label>
                                                                    <input type="time" class="coustom-input"   name="EventTime" id="EventTime" onchange="getServicePrice();"  value=""/>
                                                                </div>
                                                            </div>
                                                            
                                                          
                                                            
                                                            
                                                            <div class="cal-item">
                                                                <div class="listsearch-input-item">
                                                                    
                                                                    <select data-placeholder="Room Type" name="selCard"  id="selCard" class="chosen-select no-search-select" onchange="getServicePrice();">
                                                                        <option value="" selected>Select Card</option>
                                                                        
                                                                        <?php if(count($Cards) > 0) { ?>
                                                                        
                                                                            <?php
                                                                             
                                                                              foreach ($Cards as $key => $card) { 
                                                                                  
                                                                                  $id = $card['id'];
                                                                                  
                                                                                  $numberOfServiceAvl = $card['num_services'];
                                                                                  
                                                                            ?>
                                                                            <option value="<?=$id?>"><?=$card['card_number']?></option>
                                                                            
                                                                            <?php } ?>
                                                                        
                                                                        
                                                                        <?php } ?>
                                                                       
                                                                    </select>
                                                                    <label style="color: red;" id="cardErrMeg"></label> 
                                                                    
                                                                    
                                                                    <?php if(count($Cards) <= 0) { ?>
                                                                    
                                                                        <?php if($logginStatus){ ?>
                                                                            <label style="color: #F9B90F;"> If you do not have a card, <a href="mi-cards.php" style="color:#3AACED;">purchase</a> a new card  </label>  
                                                                            
                                                                        <?php }else{ ?>
                                                                            <label style="color: #F9B90F;"> If you do not have a card, <a class="modal-open" href="#" style="color:#3AACED;">purchase</a> a new card  </label>  
                                                                        <?php } ?>
                                                                        
                                                            
                                                                    <?php } ?>
                                                                    
                                                                    
                                                                   
                                                                </div>
                                                            </div>
                                                            
                                                           
                                                            
                                                            
                                                            <div class="box-widget-list hide" id="priceDetailedView">
                                                                
                                                               
                                                                <div class="cal-item">
                                                                    <div class="bookdate-container  fl-wrap">
                                                                        <label>Have coupon?</label>
                                                                        <input type="text" class="coustom-input" placeholder="Coupon code"  name="couponCode" id="couponCode" onchange="getServicePrice();"  value=""/>
                                                                    </div>
                                                                    
                                                                    <div class="box-widget-list hide" id="couponcodeErr">
                                                                        
                                                                    </div>
                                                                    
                                                                    
                                                                    
                                                                </div>
                                                            
                                                              
                                                                 <div class="cal-item" >
                                                                    <div class="listsearch-input-item" >
                                                                       <label style="border-bottom: 1px solid #eee;">&nbsp;</label>
                                                                    </div>
                                                                </div>
                                                                
                                                              
                                                                
                                                                <ul>
                                                                    
                                                                    <li class="no-border-ls"><span>Date booked :</span> <span id="dateTime"></span></li>
                                                                    
                                                                    
                                                                    <li class="no-border-ls"><span>Photographer No & price :</span> <span id="photographerPrice"></span></li>
                                                                    <li class="no-border-ls"><span>Vediographer No & price :</span> <span id="vediographerPrice"></span></li>
                                                                    
                                                                    <li class="no-border-ls"><span>Extra time :</span> <span id="extraTimeVal"></span></li>
                                                                    <li class="no-border-ls"><span>Extra Head count & price :</span> <span id="extraHeadVal"></span></li>
                                                                    <li class="no-border-ls" id="gstDisplay"></li>
                                                                    
                                                                    
                                                                </ul>
                                                            </div>
                                                            
                                                            <div class="box-widget-list hide" id="priceNotAvlView">
                                                                <label style="color:red;">Service not available at this moment</label>
                                                            </div>
                                                            
                                                            
                                                        
                                                        </fieldset>
                                                        
                                                        <div class="hide" id="bookBtnDiv">
                                                            <div class="total-coast fl-wrap"><strong>Sub Total</strong> <span id="finalPrice"></span></div>
                                                            
                                                             <?php if($logginStatus){ ?>
                                                             <div class="box-widget-list " >
                                                                    <label style="color:#5ECFB1;" id="bookInfoMeg"></label>
                                                                </div>
                                                                
                                                               <button class="btnaplly color2-bg" onclick="bookNow();" id="preventSubmitButton" style="margin-top:10px;">Book Now<i class="fal fa-paper-plane"></i></button>
                                                               
                                                               
                                                                
                                                            <?php }else{ ?>
                                                                <button class="btnaplly color2-bg modal-open" id="preventSubmitButton">Book Now<i class="fal fa-paper-plane"></i></button>
                                                               
                                                            <?php } ?>
                                                            
                                                            <div class="box-widget-list " style="padding-top:10px;">
                                                                <label style="color:red;" id="bookErrMeg"></label>
                                                            </div>
                                                            
                                                            
                                                        </div>

                                                        
                                                        
                                                        
                                                        
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--box-widget-item end -->                                      
                                       
                                                                           
                                        <!--box-widget-item -->
                                        <div class="box-widget-item fl-wrap">
                                            <div class="box-widget">
                                                <div class="box-widget-content">
                                                    <div class="box-widget-item-header">
                                                        <h3> Contact Information</h3>
                                                    </div>
                                                    <div class="box-widget-list">
                                                        <ul>
                                                            <li><span><i class="fal fa-map-marker"></i> Adress :</span> <a href="#"><?=$services[0]['company_address']?><br><?=$services[0]['city_id']?>, <?=$services[0]['state_id']?>, <?=$services[0]['county_id']?></a></li>
                                                            <li><span><i class="fal fa-phone"></i> Phone :</span> <a href="#"><?=$services[0]['company_phone']?></a></li>
                                                            <li><span><i class="fal fa-envelope"></i> Mail :</span> <a href="#"><?=$services[0]['company_mail']?></a></li>
                                                            <li><span><i class="fal fa-browser"></i> Website :</span> <a href="<?=$services[0]['company_link']?>"><?=$services[0]['company_link']?></a></li>
                                                        </ul>
                                                    </div>
                                                    <div class="list-widget-social">
                                                        <ul>
                                                            
                                                            <?php 
                                                            
                                                                if($services[0]['facebook_link'] != "") echo '<li><a href="'.$services[0]['facebook_link'].'" target="_blank" ><i class="fab fa-facebook-f"></i></a></li>';
                                                                if($services[0]['instagram_link'] != "") echo '<li><a href="'.$services[0]['instagram_link'].'" target="_blank" ><i class="fab fa-instagram"></i></a></li>';
                                                                if($services[0]['twitter_link'] != "") echo '<li><a href="'.$services[0]['twitter_link'].'" target="_blank"><i class="fab fa-twitter"></i></a></li>';
                                                                if($services[0]['linkedin_link'] != "") echo '<li><a href="'.$services[0]['linkedin_link'].'" target="_blank"><i class="fab fa-linkedin"></i></a></li>';
                                                                
                                                                if($services[0]['pinterest_link'] != "") echo '<li><a href="'.$services[0]['pinterest_link'].'" target="_blank"><i class="fab fa-pinterest"></i></a></li>';
                                                                if($services[0]['youtube_link'] != "") echo '<li><a href="'.$services[0]['youtube_link'].'" target="_blank"><i class="fab fa-youtube"></i></a></li>';
                                                                if($services[0]['reddit_link'] != "") echo '<li><a href="'.$services[0]['reddit_link'].'" target="_blank"><i class="fab fa-reddit"></i></a></li>';
                                                              
                                                            
                                                            ?>
                                                            
                                                            
                                                
                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--box-widget-item end -->                          
                                                                  
                                     
                                        <!--box-widget-item -->
                                        <div class="box-widget-item fl-wrap">
                                            <div class="box-widget widget-posts">
                                                <div class="box-widget-content">
                                                    <div class="box-widget-item-header">
                                                        <h3>Other Services</h3>
                                                    </div>
                                                    
                                                    
                                                    <?php if(count($anotherServices) > 0) {
                                                        foreach ($anotherServices as $key => $album) { 
                                           
                                                    ?>
                                                    
                                                    
                                                    <!--box-image-widget-->
                                                    <div class="box-image-widget" >
                                                        <div class="box-image-widget-media"><img src="<?=$album['file_path']?>" alt="">
                                                            <a href="#" onclick="viewService(<?=$album['id']?>);" class="color2-bg" >Details</a>
                                                        </div>
                                                        <div class="box-image-widget-details">
                                                            <h4><?=$album['name']?> </h4>
                                                            <p><?=$album['description']?></p>
                                                        </div>
                                                    </div>
                                                    <!--box-image-widget end -->
    
                                                    
                                                    <?php } ?>
                                                    <?php } ?>
                                                    
                                                    
                                                                               
                                                </div>
                                            </div>
                                        </div>
                                        <!--box-widget-item end -->                           
                                        <!--box-widget-item -->
                                        <div class="box-widget-item fl-wrap">
                                            <div class="box-widget">
                                                <div class="box-widget-content">
                                                    <div class="box-widget-item-header">
                                                        <h3>Hosted By</h3>
                                                    </div>
                                                    <div class="box-widget-author fl-wrap">
                                                        <div class="box-widget-author-title fl-wrap">
                                                            <div class="box-widget-author-title-img">
                                                                <img src="<?=$services[0]['company_logo_url']?>" alt=""> 
                                                            </div>
                                                            <a ><?=$services[0]['company_name']?></a>
                                                            <span><?=$services[0]['city_id']?>, <?=$services[0]['state_id']?></span>
                                                        </div>
                                                        <a onclick="viewProviderProfile(<?=$services[0]['main_id']?>);" class="btn flat-btn color-bg   float-btn image-popup">View Profile<i class="fal fa-user-alt"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--box-widget-item end -->                              
                                        <!--box-widget-item -->
                                        <div class="box-widget-item fl-wrap">
                                            <div class="box-widget">
                                                <div class="box-widget-content">
                                                    <div class="box-widget-item-header">
                                                        <h3>Similar Services</h3>
                                                    </div>
                                                    <div class="widget-posts fl-wrap">
                                                        <ul>
                                                            
                                                             <?php if(count($simillerServices) > 0) {
                                                                foreach ($simillerServices as $key => $album) { 
                                                   
                                                            ?>
                                                            
                                                              
                                                            
                                                            <li class="clearfix" onclick="viewService(<?=$album['id']?>);" >
                                                                <a href="#" class="widget-posts-img"><img src="<?=$album['file_path']?>" class="respimg" alt=""></a>
                                                                <div class="widget-posts-descr">
                                                                    <a href="#" title=""><?=$album['name']?></a>
                                                                    <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                                                    <div class="geodir-category-location fl-wrap"><a href="#"><i class="fas fa-map-marker-alt"></i> <?=$album['city_id']?>, <?=$album['state_id']?></a></div>
                                                                </div>
                                                            </li>
                                                            
                                                       
            
                                                            
                                                            <?php } ?>
                                                            <?php } ?>
                                                            
                                                     
                                                        </ul>
                                                        <a class="widget-posts-link" href="services.php">See All Listing <i class="fal fa-long-arrow-right"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--box-widget-item end -->                            
                                    </div>
                                    <!--box-widget-wrap end -->  
                                </div>
                                <!--   sidebar end  -->
                            </div>
                            <!--   row end  -->
                        </div>
                        <!--   container  end  -->
                    </section>
                    <!--  section  end-->
                </div>
                <!-- content end-->
                <div class="limit-box fl-wrap"></div>
            </div>
            <!--wrapper end -->
            
            
          <?php 

include("templates/footer.php");

?>

<script>

    var decodedKey = '<?=$decodedKey?>';
    var allowedMaxNumberOfPerson = '<?=$services[0]['number_of_members']?>';
    
    
    var inpServiceID = '<?=$decodedKey?>';
    var inpPhotographerPrice = 0;
    var inpVediographerPrice = 0;
    var inpEventDate  = "";
    var inpEventTime  = "";
    var inpExtraTime  = "";
    var inpExtraPeople  = "";
    var inpExtraPeoplePrice  = 0;
    var inpNumPhotographer = 0;
    var inpNumVediographer = 0;
    var inpDays = 0;
    var inpHrs = 0;
    var inpMins = 0;
    var inpSelCard = "";
    var inpTotalCost = 0;
    var inpPayableAmt = 0;
    
    var isCouponApply = 0;
    var couponDiscount = 0;
    
    var mins_time_interval = 0;
    
    var extraPeopleSinglePrice = 0;
    var extraTimeSinglePriceForPic = 0;
    var extraTimeSinglePriceForVedio = 0;
    
    var gstVal = 18;
    var finalGstVal = 0;
    var priceGenID = '';

    

    $(document).ready(function() {
        $('#service-menu').addClass('act-link');
        var companyId = '<?=$services[0]['main_id']?>';
        getAllBrucher(companyId);
        
        getServicePrice();
        
         document.getElementById('preventSubmitButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
        });

    });
    
    
    
    function bookNow(){
        
        $('#bookErrMeg').html('');
        
        
        if(inpNumPhotographer == 0 && inpNumVediographer == 0){
            $('#bookErrMeg').html('Please select the number of photographers and videographers.');
            return false;
        }
        
        if(inpEventDate == ""){
            $('#bookErrMeg').html('Please select the Event Date.');
            return false;
        }
        
        if(inpEventTime == ""){
            $('#bookErrMeg').html('Please select the Event Time.');
            return false;
        }
        
        if(inpTotalCost <= 0){
            $('#bookErrMeg').html('Something went wrong.');
            return false;
        }
        
        // Given datetime string
        var givenDatetimeStr = inpEventDate+" "+inpEventTime;
        
        // Convert the given datetime string to a Date object
        var givenDatetime = new Date(givenDatetimeStr.replace(' ', 'T'));
        
        // Get the current datetime
        var currentDatetime = new Date();
        
        var date1 = new Date(currentDatetime);
        var date2 = new Date(givenDatetime);

        // Calculate the difference in milliseconds
        var diffInMs = date2 - date1;

        // Convert the difference from milliseconds to minutes
        var diffInMinutes = diffInMs / 1000 / 60;
        

        // Check if the given datetime is less than or equal to the current datetime
        if (givenDatetime <= currentDatetime) {
            $('#bookErrMeg').html('Only services booked 3 hours in advance are accepted Check your selected date and time');
            return false;
        }else{
            if(diffInMinutes <= 180){
                $('#bookErrMeg').html('Only services booked 3 hours in advance are accepted Check your selected date and time');
                return false;
            }
            
        }
        

         var user_id = '<?=$user_id?>';
        var stateName = '<?=$stateName?>';
        var isSte = 0;
        if(stateName == 'Kerala' || stateName == 'kerala') isSte = 1;
        
      
        var postData = {
            function: 'Services',
            method: "userServicePlaceOrderNow",
            'user_id':user_id,
            'isSte':isSte,
            'inpServiceID': inpServiceID,
            'inpPhotographerPrice': inpPhotographerPrice,
            'inpVediographerPrice': inpVediographerPrice,
            'inpEventDate': inpEventDate,
            'inpEventTime': inpEventTime,
            'inpExtraTime':inpExtraTime,
            'inpExtraPeople':inpExtraPeople,
            'inpExtraPeoplePrice':inpExtraPeoplePrice,
            'inpNumPhotographer':inpNumPhotographer,
            'inpNumVediographer':inpNumVediographer,
            'inpDays':inpDays,
            'inpHrs':inpHrs,
            'inpMins':inpMins,
            'inpSelCard':inpSelCard,
            'inpTotalCost':inpTotalCost,
            'inpPayableAmt':inpPayableAmt,
            'couponDiscount':couponDiscount,
            'mins_time_interval':mins_time_interval,
            'extraPeopleSinglePrice':extraPeopleSinglePrice,
            'extraTimeSinglePriceForPic':extraTimeSinglePriceForPic,
            'extraTimeSinglePriceForVedio':extraTimeSinglePriceForVedio,
            'finalGstVal':finalGstVal,
            'gstVal':gstVal,
            'priceGenID':priceGenID
            
          }
          

          $.ajax({
            url: '/admin/ajaxHandler.php',
            type: 'POST',
            data: postData,
            dataType: "json",
            success: function (data) {
                // console.log(data);
                // console.log(data.status);
                //called when successful
                if (data.status == 1) {
                    window.location.href = 'https://machooosinternational.com/dashboard/purchase-user-services.php?purchaseID='+data.data;
                    

               
                }
               
            },
            error: function (x,h,r) {
            //called when there is an error
                console.log(x);
                console.log(h);
                console.log(r);
               
            }
        });
        
  
    }
    
    
    
    
    function getServicePrice(){
        
        $('#priceDetailedView').addClass('hide');
        $('#priceNotAvlView').addClass('hide');
        
        $('#bookBtnDiv').addClass('hide');
        $('#serviceUnavailabeMeg').addClass('hide');
        
        $('#cardErrMeg').html('');
        $('#bookErrMeg').html('');
        $('#bookInfoMeg').html('');
        
        
        var isCardApply = "";
        
        var numberOfServiceAvl = '<?=$numberOfServiceAvl?>';
        var selCard = $('#selCard').val();
        if(selCard != ""){
            if(numberOfServiceAvl <= 0){
                $('#cardErrMeg').html('You have no available services on the selected card. The card discount can`t be applied.');
            }else{
                isCardApply = selCard;
            }
        }
        
        
        
       
        var numOfHeadcount = $('#numOfHeadcount').val();
        var numOfPhotographer = $('#numOfPhotographer').val();
        var numOfVideographer = $('#numOfVideographer').val();
        var moreDays = $('#moreDays').val();
        var moreHrs = $('#moreHrs').val();
        var moreMins = $('#moreMins').val();
        
        
        var EventDate = $('#EventDate').val();
        var EventTime = $('#EventTime').val();
        

        var extraTime = parseFloat(moreMins) + ( parseFloat(moreHrs) * 60 ) + ( parseFloat(moreDays) * 1440 ) ;
        

        
        $('#photographerPrice').html('0 nos 0 RS');
        $('#vediographerPrice').html('0 nos 0 RS');
        $('#dateTime').html(EventDate+' '+EventTime);
        $('#extraTimeVal').html(extraTime+' mins');
        $('#extraHeadVal').html('0 nos 0 RS');
        $('#gstDisplay').html('');
        
   
        
        $('#finalPrice').html('0 /- including gst');
        
        inpDays = moreDays;
        inpHrs = moreHrs;
        inpMins = moreMins;
        inpSelCard = isCardApply;
        inpNumPhotographer = numOfPhotographer;
        inpNumVediographer = numOfVideographer;
        inpExtraPeople = numOfHeadcount;
        
        inpEventDate = EventDate;
        inpEventTime = EventTime;
        inpExtraTime = extraTime;
        
        
        inpPhotographerPrice = 0;
        inpVediographerPrice = 0;
        inpTotalCost = 0;
        inpExtraPeoplePrice  = 0;
        inpPayableAmt = 0;
        mins_time_interval = 0;
        
        extraPeopleSinglePrice = 0;
        extraTimeSinglePriceForPic = 0;
        extraTimeSinglePriceForVedio = 0;
        
        gstVal = 18;
        finalGstVal = 0;
        priceGenID = '';
        
        
        generateServicePrice(decodedKey,numOfHeadcount,isCardApply,extraTime,allowedMaxNumberOfPerson);

    }
    
    
    
    function generateServicePrice(serviceID,numOfHeadcount,selCard,extraTime,allowedMaxNumberOfPerson){
                
             
                 var postData = {
                    function: 'Services',
                    method: "getPriceDetails",
                    'serviceID':serviceID,
                    'selCard':selCard,
                  }
              
                $.ajax({
                    url: '/admin/ajaxHandler.php',
                    type: 'POST',
                    data: postData,
                    dataType: "json",
                    success: function (data) {
                        
                        
                        if (data.status == 1) {
                            
                            var out = data.data;
                            
                            
                            if(out.length <= 0){
                                $('#finalPrice').html('--');
                                $('#priceNotAvlView').removeClass('hide');
                                $('#serviceUnavailabeMeg').removeClass('hide');
                                return false;
                            }
                            
                            var generatePriceTime = parseFloat(out[0]['mins_time_interval']) + parseFloat(extraTime) ;
                            var numberOfPersonMax = parseFloat(allowedMaxNumberOfPerson) + parseFloat(numOfHeadcount);
                            var extraPricePerHead = out[0]['price_per_head'];
                            
                             extraPeopleSinglePrice = extraPricePerHead;
                            
                            
                             var finalExtraPricePerHead = 0;
                              var morePerson = 0;
                              
                              if( parseFloat(numberOfPersonMax) > parseFloat(allowedMaxNumberOfPerson)  ){
                                  morePerson = parseFloat(numberOfPersonMax) - parseFloat(allowedMaxNumberOfPerson);
                                  finalExtraPricePerHead = morePerson * parseFloat(extraPricePerHead) ; 
                                  
                              }
                              
                          
                              var gstPercentage = out[0]['gst_val'];
                              
                              priceGenID = out[0]['id'];
                          
                              
                              var runFn = 0;
                              
                              
                               var isRun = true;
                                  var finalPicPrice = 0;
                               var finalVidPrice = 0;
                               
                               var finalPicExtraPrice = "";
                               var finalVidExtraPrice = "";
                               
                               
                                  var finalmiCommissionPrice = 0;
                               var finalproviderCommissionPrice = 0;
                               
                                var finalmiCommissionExtraPrice = "";
                               var finalproviderCommissionExtraPrice = "";
                               
                               
                                var extraMin_1 = out[0]['mins_time_interval'];
                              var phtoPrice_1 = out[0]['mins_pic_price'];
                              var vedioPrice_1 = out[0]['mins_vedio_price'];
                              var phtoExtraPrice_1 = out[0]['mins_extra_pic_price'];
                              var vedioPriceExtra_1 = out[0]['mins_extra_vedio_price'];
                              
                              extraTimeSinglePriceForPic = out[0]['mins_extra_pic_price'];
                              extraTimeSinglePriceForVedio = out[0]['mins_extra_pic_price'];
                              
                             
                              
                              if(parseFloat(generatePriceTime) <= parseFloat(extraMin_1) && isRun ){
                               
                                finalPicPrice = phtoPrice_1;
                                finalVidPrice = vedioPrice_1;
                                
                                isRun = false;
                                
                                runFn = 1;
                                
                                
                            }else if(parseFloat(generatePriceTime) < 60 && isRun){
                            
                                var extraMins = parseFloat(generatePriceTime) - parseFloat(extraMin_1) ;
                                
                                finalPicExtraPrice = "( ₹"+phtoPrice_1+" for "+extraMin_1+" mins & ₹"+parseFloat(extraMins*phtoExtraPrice_1)+" for extra "+extraMins+" mins  )";
                                finalVidExtraPrice = "( ₹"+vedioPrice_1+" for "+extraMin_1+" mins & ₹"+parseFloat(extraMins*vedioPriceExtra_1)+" for extra "+extraMins+" mins  )";
                             
                                finalPicPrice = (parseFloat(phtoPrice_1) + parseFloat(extraMins*phtoExtraPrice_1) );
                                finalVidPrice = (parseFloat(vedioPrice_1) + parseFloat(extraMins*vedioPriceExtra_1) );
                                
                                isRun = false;
                                runFn = 2;
                            }
                            
                            
                            var extraMin_2 = out[0]['hrs_time_interval'];
                              var phtoPrice_2 = out[0]['hrs_pic_price'];
                              var vedioPrice_2 = out[0]['hrs_vedio_price'];
                              var phtoExtraPrice_2 = out[0]['hrs_extra_pic_price'];
                              var vedioPriceExtra_2 = out[0]['hrs_extra_vedio_price'];
                              
                              var hrToMin = parseFloat(extraMin_2) * 60;
                              
                              if(parseFloat(generatePriceTime) <= parseFloat(hrToMin) && isRun){
                                  
                           
                                finalPicPrice = phtoPrice_2;
                                finalVidPrice = vedioPrice_2;
                                
                                isRun = false;
                                runFn = 3;
                                  
                              }else if(parseFloat(generatePriceTime) < 1440 && isRun){
                             
                                var extraMins = parseFloat(generatePriceTime) - parseFloat(hrToMin) ;
                                
                                finalPicExtraPrice = "( ₹"+phtoPrice_2+" for "+extraMin_2+" hr & ₹"+parseFloat(extraMins*phtoExtraPrice_2)+" for extra "+extraMins+" mins  )";
                                finalVidExtraPrice = "( ₹"+vedioPrice_2+" for "+extraMin_2+" hr & ₹"+parseFloat(extraMins*vedioPriceExtra_2)+" for extra "+extraMins+" mins  )";
                          
                                finalPicPrice = (parseFloat(phtoPrice_2) + parseFloat(extraMins*phtoExtraPrice_2) );
                                finalVidPrice = (parseFloat(vedioPrice_2) + parseFloat(extraMins*vedioPriceExtra_2) );
                                  
                                  isRun = false;
                                  runFn = 4;
                                  
                              }
                              
                              var extraMin_3 = out[0]['day_time_interval'];
                              var phtoPrice_3 = out[0]['day_pic_price'];
                              var vedioPrice_3 = out[0]['day_vedio_price'];
                              var phtoExtraPrice_3 = out[0]['day_extra_pic_price'];
                              var vedioPriceExtra_3 = out[0]['day_extra_vedio_price'];
                              
                              var dayToMin = parseFloat(extraMin_3) * 1440 ;
                              
                               if(parseFloat(generatePriceTime) <= parseFloat(dayToMin) && isRun){
                            
                                finalPicPrice = phtoPrice_3;
                                finalVidPrice = vedioPrice_3;
                                
                                isRun = false;
                                runFn = 5;
                                  
                              }else if(isRun){
                              
                                var extraMins = parseFloat(generatePriceTime) - parseFloat(dayToMin) ;
                                
                                finalPicExtraPrice = "( ₹"+phtoPrice_3+" for "+extraMin_3+" day & ₹"+parseFloat(extraMins*phtoExtraPrice_3)+" for extra "+extraMins+" mins  )";
                                finalVidExtraPrice = "( ₹"+vedioPrice_3+" for "+extraMin_3+" day & ₹"+parseFloat(extraMins*vedioPriceExtra_3)+" for extra "+extraMins+" mins  )";
                             
                                finalPicPrice = (parseFloat(phtoPrice_3) + parseFloat(extraMins*phtoExtraPrice_3) );
                                finalVidPrice = (parseFloat(vedioPrice_3) + parseFloat(extraMins*vedioPriceExtra_3) );
                                  
                                  isRun = false;
                                  
                                  runFn = 6;
                                  
                              }
                              
                              
                              gstVal = gstPercentage;
                            
                            
                               
                               var ActualAmt = parseFloat(finalPicPrice) + parseFloat(finalVidPrice);
                               
                             
                             
                                 
                                var miCommission_1 = out[0]['mins_mi_commission'];
                                  var miCommissionType_1 = out[0]['mins_mi_commission'];
                                  var miCommissionExtra_1 = out[0]['mins_mi_commission_extra'];
                                  var miCommissionExtraType_1 = out[0]['mins_mi_commission_extra_type'];
                                  var providerCommission_1 = out[0]['mins_provider_commission'];
                                   var providerCommissionType_1 = out[0]['mins_provider_commission_type'];
                                  var providerCommissionExtra_1 = out[0]['mins_provider_commission_extra'];
                                  var providerCommissionExtraType_1 = out[0]['mins_provider_commission_extra_type'];
                                  
                                  var miCommission_2 = out[0]['hrs_mi_commission'];
                                  var miCommissionType_2 = out[0]['hrs_mi_commission_type'];
                                  var miCommissionExtra_2 = out[0]['hrs_mi_commission_extra'];
                                  var miCommissionExtraType_2 = out[0]['hrs_mi_commission_extra_type'];
                                  var providerCommission_2 = out[0]['hrs_provider_commission'];
                                   var providerCommissionType_2 = out[0]['hrs_provider_commission_type'];
                                  var providerCommissionExtra_2 = out[0]['hrs_provider_commission_extra'];
                                  var providerCommissionExtraType_2 = out[0]['hrs_provider_commission_extra_type'];
                                  
                                  var miCommission_3 = out[0]['day_mi_commission'];
                                  var miCommissionType_3 = out[0]['day_mi_commission_type'];
                                  var miCommissionExtra_3 = out[0]['day_mi_commission_extra'];
                                  var miCommissionExtraType_3 = out[0]['day_mi_commission_extra_type'];
                                  var providerCommission_3 = out[0]['day_provider_commission'];
                                   var providerCommissionType_3 = out[0]['day_provider_commission_type'];
                                  var providerCommissionExtra_3 = out[0]['day_provider_commission_extra'];
                                  var providerCommissionExtraType_3 = out[0]['day_provider_commission_extra_type'];
                                
                              
                                
                                
                                if(runFn == 1 ){
                                    if(miCommissionType_1 =='amount'){
                                        finalmiCommissionPrice = miCommission_1;
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_1))/100;
                                    }
                                    
                                    if(providerCommissionType_1 =='amount'){
                                        finalproviderCommissionPrice = providerCommission_1;
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_1))/100;
                                    }
                                    
                                
                                    
                                }else if(runFn == 2 ){
                                    if(miCommissionType_1 =='amount'){
                                        
                                        finalmiCommissionPrice = miCommission_1;
                                        
                                        if(miCommissionExtraType_1 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+miCommission_1+" for "+extraMin_1+" mins & ₹"+miCommissionExtra_1+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(miCommission_1) + parseFloat(miCommissionExtra_1) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_1))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_1+" mins & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                        
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_1))/100;
                                        
                                        if(miCommissionExtraType_1 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_1+" mins & ₹"+miCommissionExtra_1+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(miCommissionExtra_1) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_1))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_1+" mins & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                    
                        
                                    }
                                    
                                    if(providerCommissionType_1 =='amount'){
                                        
                                        finalproviderCommissionPrice = providerCommission_1;
                                        
                                        if(providerCommissionExtraType_1 == 'amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+providerCommission_1+" for "+extraMin_1+" mins & ₹"+providerCommissionExtra_1+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(providerCommission_1) + parseFloat(providerCommissionExtra_1) ;
                                        }else{
                                             var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_1))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_1+" mins & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                            
                                        }
                                      
                                        
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_1))/100;
                                        
                                        if(providerCommissionExtraType_1 =='amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_1+" mins & ₹"+providerCommissionExtra_1+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(providerCommissionExtra_1) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_1))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_1+" mins & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                    
                                        
                                    }
                                 
                                }else if(runFn == 3 ){
                                    if(miCommissionType_2 =='amount'){
                                        finalmiCommissionPrice = miCommission_2;
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_2))/100;
                                    }
                                    
                                    if(providerCommissionType_2 =='amount'){
                                        finalproviderCommissionPrice = providerCommission_2;
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_2))/100;
                                    }
                                    
                                
                                    
                                }else if(runFn == 4 ){
                                    if(miCommissionType_2 =='amount'){
                                        
                                        finalmiCommissionPrice = miCommission_2;
                                        
                                        
                                        if(miCommissionExtraType_2 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+miCommission_2+" for "+extraMin_2+" hr & ₹"+miCommissionExtra_2+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(miCommission_2) + parseFloat(miCommissionExtra_2) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_2))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_2+" hr & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                        
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_2))/100;
                                        
                                        if(miCommissionExtraType_2 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_2+" hr & ₹"+miCommissionExtra_2+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(miCommissionExtra_2) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_2))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_2+" hr & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                      
                        
                                    }
                                    
                                    if(providerCommissionType_2 =='amount'){
                                        
                                        finalproviderCommissionPrice = providerCommission_2;
                                        
                                        if(providerCommissionExtraType_2 == 'amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+providerCommission_2+" for "+extraMin_2+" hr & ₹"+providerCommissionExtra_2+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(providerCommission_2) + parseFloat(providerCommissionExtra_2) ;
                                        }else{
                                             var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_2))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_2+" hr & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                            
                                        }
                                        
                                 
                                        
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_2))/100;
                                        
                                        if(providerCommissionExtraType_2 =='amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_2+" hr & ₹"+providerCommissionExtra_2+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(providerCommissionExtra_2) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_2))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_2+" hr & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                        
                                    }
                                 
                                }else if(runFn == 5 ){
                                     if(miCommissionType_3 =='amount'){
                                        finalmiCommissionPrice = miCommission_3;
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_3))/100;
                                    }
                                    
                                    if(providerCommissionType_3 =='amount'){
                                        finalproviderCommissionPrice = providerCommission_3;
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_3))/100;
                                    }
                                    
                                
                                    
                                }else if(runFn == 6 ){
                                    if(miCommissionType_3 =='amount'){
                                        
                                        finalmiCommissionPrice = miCommission_3;
                                        finalmiCommissionExtraPrice = "( extra time used )";
                                        
                                        if(miCommissionExtraType_3 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+miCommission_3+" for "+extraMin_3+" day & ₹"+miCommissionExtra_3+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(miCommission_3) + parseFloat(miCommissionExtra_3) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_3))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_3+" day & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                        
                                    }else{
                                        finalmiCommissionPrice = (parseFloat(ActualAmt)*parseFloat(miCommission_3))/100;
                                        
                                        if(miCommissionExtraType_3 =='amount'){
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_3+" day & ₹"+miCommissionExtra_3+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(miCommissionExtra_3) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalmiCommissionPrice)*parseFloat(miCommissionExtra_3))/100)*extraMins;
                                            
                                            finalmiCommissionExtraPrice = "( ₹"+finalmiCommissionPrice+" for "+extraMin_3+" day & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalmiCommissionPrice = parseFloat(finalmiCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                     
                        
                                    }
                                    
                                    if(providerCommissionType_3 =='amount'){
                                        
                                        finalproviderCommissionPrice = providerCommission_3;
                                        
                                        if(providerCommissionExtraType_3 == 'amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+providerCommission_3+" for "+extraMin_3+" day & ₹"+providerCommissionExtra_3+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(providerCommission_3) + parseFloat(providerCommissionExtra_3) ;
                                        }else{
                                             var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_3))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_3+" day & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                            
                                        }
                                        
                                      
                                        
                                    }else{
                                        finalproviderCommissionPrice = (parseFloat(ActualAmt)*parseFloat(providerCommission_3))/100;
                                        
                                         if(providerCommissionExtraType_3 =='amount'){
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_3+" day & ₹"+providerCommissionExtra_3+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(providerCommissionExtra_3) ;
                                            
                                        }else{
                                            var extra = ((parseFloat(finalproviderCommissionPrice)*parseFloat(providerCommissionExtra_3))/100)*extraMins;
                                            
                                            finalproviderCommissionExtraPrice = "( ₹"+finalproviderCommissionPrice+" for "+extraMin_3+" day & ₹"+extra.toFixed(2)+" for extra "+extraMins+" mins  )";
                                            finalproviderCommissionPrice = parseFloat(finalproviderCommissionPrice) + parseFloat(extra) ;
                                        }
                                        
                                        
                                    }
                                 
                                }
                                
                                
                                var totalPicPay = ( parseFloat(finalPicPrice) + ( parseFloat(finalExtraPricePerHead) / 2 ) ) - ( ( parseFloat(finalmiCommissionPrice) / 2 ) + ( parseFloat(finalproviderCommissionPrice) / 2 ) ) ;
                                var totalVidPay = ( parseFloat(finalVidPrice) + ( parseFloat(finalExtraPricePerHead) / 2 ) ) - ( ( parseFloat(finalmiCommissionPrice) / 2 ) + ( parseFloat(finalproviderCommissionPrice) / 2 ) ) ;
                                
                        
                             var numOfPhotographer = $('#numOfPhotographer').val();
                            var numOfVideographer = $('#numOfVideographer').val();
                            
                            $('#minServiceTime').html(parseFloat(out[0]['mins_time_interval']));
                            
                            mins_time_interval = parseFloat(out[0]['mins_time_interval']);
                            
                            $('#photographerPrice').html(numOfPhotographer+' nos '+parseFloat(finalPicPrice)*parseFloat(numOfPhotographer)+' RS');
                            $('#vediographerPrice').html(numOfVideographer+' nos '+parseFloat(finalVidPrice)*parseFloat(numOfVideographer)+' RS');
                            $('#dateTime').html();
                            $('#extraTimeVal').html();
                            $('#extraHeadVal').html(numOfHeadcount+' nos '+parseFloat(finalExtraPricePerHead)*parseFloat(numOfHeadcount)+' RS');
                            
                            var genfinalPrice = (parseFloat(finalPicPrice)*parseFloat(numOfPhotographer)) + (parseFloat(finalVidPrice)*parseFloat(numOfVideographer)) + (parseFloat(finalExtraPricePerHead)*parseFloat(numOfHeadcount));
                            
                              
                               var finalGst = (parseFloat(genfinalPrice)*gstPercentage)/100;
                               

                             finalGstVal = finalGst;
                            
                            
                            $('#gstDisplay').html('<span>GST ('+gstPercentage+'%) :</span> <span>'+finalGstVal+' /-</span>');
                            
                            
                            inpTotalCost = parseFloat(genfinalPrice) + parseFloat(finalGst)   ;

                           
                                $('#priceDetailedView').removeClass('hide');
                                $('#finalPrice').html(inpTotalCost+' /- including gst');
                                
                                $('#bookBtnDiv').removeClass('hide');
                                
                       
                            
                            inpPhotographerPrice = parseFloat(finalPicPrice);
                            inpVediographerPrice = parseFloat(finalVidPrice);
                            
                            inpExtraPeoplePrice  = parseFloat(finalExtraPricePerHead);
                            
                            inpPayableAmt = parseFloat(inpTotalCost) / 2;
                            
                            applyCoupon();
                            
                            
                            $('#bookInfoMeg').html('Total amount payable by you is '+inpTotalCost+'/- <br> Now half of the booking fee '+inpPayableAmt+'/- is to be paid and the rest after the service is completed');
                            
                        }
                    },
                    error: function (x,h,r) {
                    //called when there is an error
                        console.log(x);
                        console.log(h);
                        console.log(r);
                        
                    }
                });
                
                
                
             
            }
            
            
            function applyCoupon(){
                var couponCode = $('#couponCode').val();
                $('#couponcodeErr').addClass('hide');
                isCouponApply = 0;
                couponDiscount = 0;
                if(couponCode == "") return false;
            
                  var postData = {
                    function: 'Services',
                    method: "applyCardServiceCouponcode",
                    'Couponcode': couponCode,
                   
                   
                  }
              
                $.ajax({
                    url: '/admin/ajaxHandler.php',
                    type: 'POST',
                    data: postData,
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        // console.log(data.status);
                        //called when successful
                        if (data.status == 1) {
                          var arrayLength = data.data.length;
                          if(arrayLength == 0){
                              $('#couponcodeErr').removeClass('hide');
                                 $('#couponcodeErr').html('<label style="color:red;">Invalid coupon code</label>');
                                 return false;
                          }else{
                              
                            
                              var coupon = data.data[0] ;
                              if(coupon['DiscountType'] == 1){
                                  //amt
                                  var minusAmt = coupon['CouponDiscount'] ;
                                  var newAmt = (inpTotalCost - minusAmt).toFixed(2) ;
                                  
                                  
                                  $('#couponcodeErr').removeClass('hide');
                                 $('#couponcodeErr').html('<label style="color:green;">You will save ₹'+minusAmt+' on this order</label>');
                                 
                                 isCouponApply = 1;
                                couponDiscount = minusAmt;
                                  
                            
                                  
                              }else{
                                  //offer
                                  var minusAmt = coupon['CouponDiscount'] ;
                                  var oftlAmt = ( (inpTotalCost / 100 ) * minusAmt ).toFixed(2) ;
                                  
                                  var newAmt = (inpTotalCost - oftlAmt).toFixed(2) ;
                                  
                                   $('#couponcodeErr').removeClass('hide');
                                 $('#couponcodeErr').html('<label style="color:green;">You will save ₹'+oftlAmt+' on this order</label>');
                                 
                                 isCouponApply = 1;
                                couponDiscount = oftlAmt;
                                  
                               
                              }
                              
                              var newhalf = parseFloat(newAmt) / 2;
                              $('#finalPrice').html(newAmt+' /- including gst');
                              
                              
                              $('#bookInfoMeg').html('Total amount payable by you is '+newAmt+'/- <br> Now half of the booking fee '+newhalf+'/- is to be paid and the rest after the service is completed');
                              
                              
                              
                              
                            
                          }
                          
                       
                        }
                       
                    },
                    error: function (x,h,r) {
                    //called when there is an error
                        console.log(x);
                        console.log(h);
                        console.log(r);
                       
                    }
                });
                        
                
            }
    
    
    function getAllBrucher(selectedCompanyId){
        

         $('#displayCompanyBruchers').html('');
         
         
           var postData = {
            function: 'Services',
            "method": "getBruchers",
            'selectedCompanyId':selectedCompanyId
          }
          
           $.ajax({
                url: '/admin/ajaxHandler.php',
                type: 'POST',
                data: postData,
                dataType: "json",
                success: function (resp) {
                    console.log(resp);
                    console.log(resp.status);
                    //called when successful
                    if(resp.status == 1){
                        var images = resp.data;
                        if(images.length > 0){
                            
                            var disD = '';
                            var disD1 = '';

                            for(var i=0;i<images.length;i++){
                                
                                var filepath = images[i]['file_path'];
                                disD +='<a href="'+filepath+'" target="_blank" class="brucher-link">'+images[i]['file_name']+' - '+images[i]['created_date']+' </a> <br>';
            
                            }
                            
                            
                        }else{
                            
                            var disD = '';
                            disD +='<p class="text-muted">Brochures not uploaded</p>';
                            
                         
                            
                            
                        }
                        
                        $('#displayCompanyBruchers').html(disD);
                        
                        
                    }
                        
                        
                        
                },
                error: function (x,h,r) {
                //called when there is an error
                    console.log(x);
                    console.log(h);
                    console.log(r);
                    $('#btnLogIn').removeClass('d-none');
                   
                }
            });
    
     
     }


    
    
    
</script>
