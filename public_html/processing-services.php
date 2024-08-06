<?php 

require_once('admin/config.php');

$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

// session_start();
$isLogin = $_SESSION['isLogin'];
$logginStatus = false;

if($_SESSION['mifutoUser']['id'] != "" && $isLogin){
  $logginStatus = true;
}

if(!$logginStatus) {
    header("Location: index.php");
    exit();
}

$userName = $_SESSION['Username'];
$user_id = $_SESSION['mifutoUser']['id'];

$ordersData = [];
 
 $sqlcart = "SELECT * FROM place_order_userservices WHERE user_id=".$user_id." and newpurchaseID !='' and service_status =3 order by inpEventDate asc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);

if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}



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
                                <div class="dasboard-breadcrumbs breadcrumbs"><a href="#">Home</a><a href="#">Bookings</a><span>Processing Services</span></div>
                               
                            </div>
                        </div>
                    </section>
                    <!-- section end-->
                    <!-- section-->
                    <section class="middle-padding">
                        <div class="container">
                            <!--dasboard-wrap-->
                            <div class="fl-wrap">
                                <!-- dashboard-content--> 
                                <div class="dashboard-content fl-wrap">
                                    <div class="dashboard-list-box fl-wrap">
                                        <div class="dashboard-header fl-wrap">
                                            <h3>Processing Services</h3>
                                        </div>
                                        
                                        <?php if(count($ordersData) > 0) { ?>
                                        
                                                <?php 
                                                foreach ($ordersData as $key => $album) { 
                                                    
                                                    $purchaseID = $album['id'];
                                                    
                                                    
                                                    $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
                                                	$cardData1r = $DBC->query($psql);
                                                	$cardData1 = mysqli_fetch_assoc($cardData1r);
                                                		
                                            		$user_id = $album['user_id'];
                                            		$decodedKey = $album['inpServiceID'];
                                            		
                                            	    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.machoose_user_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
                                            		$cardData = $DBC->query($psql1);
                                            		
                                            		$service = mysqli_fetch_assoc($cardData);
                                            		
                                            		$priceDetails='<p>Thank you for considering our services. Here are the details regarding pricing and payment:<br><b>Payment Structure:</b> <br>A 50% advance payment is required to confirm your booking.The remaining balance is due on the day of the photo shoot.<br><b>Payment Methods:</b><br>All payments must be made online through your Mifuto account.We do not accept cash payments.<br><b>Tipping Policy:</b><br>Please do not provide tips to our photographers.We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.</p>';
                                                    
                                                    $deliverables = 'Thank you for choosing our services <b>'.$service['name'].'</b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>2 Photo Frames<br>1 Calendar<br>We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>Best regards,</p>';
                                                    
                                                

                                                    $time = $album['inpEventTime'];
                                                     $time = new DateTime($time);
                                                    $amPmTime = $time->format('h:i A');
                                                    
                                                     $ctime = new DateTime($album['created_date']);
                                                    $camPmTime = $ctime->format('Y-m-d h:i A');
                                                    
                                                    
                                                    
                                                    
                                                    ?>
                                        
                                             <!-- dashboard-list end-->    
                                            <div class="dashboard-list">
                                                <div class="dashboard-message">
                                                    <div class="dashboard-message-avatar">
                                                        <img src="<?=$service['company_logo_url']?>" alt="">
                                                    </div>
                                                    <div class="dashboard-message-text">
                                                        <h4><?=$service['name']?> - <span><?=$album['inpEventDate']?> <?=$amPmTime?></span></h4>
                                                        
                                                        <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Service Description :</span> :
                                                            <span class="booking-text"><a href="listing-sinle.html"><?=$service['description']?></a></span>
                                                        </div>
                                                         <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Service Provider :</span>   
                                                            <span class="booking-text"><?=$service['company_name']?></span>
                                                        </div>
                                                        <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Service Provider Address:</span>   
                                                            <span class="booking-text"><?=$service['company_address']?></span>
                                                        </div>
                                                        <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Service Booking Date :</span>   
                                                            <span class="booking-text"><?=$camPmTime?></span>
                                                        </div>
                                                        <div class="booking-details fl-wrap">                                                               
                                                            <span class="booking-title">Mail :</span>  
                                                            <span class="booking-text"><a href="#" target="_top"><?=$service['company_mail']?></a></span>
                                                        </div>
                                                        <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Mifuto Assaigned staff Phone :</span>   
                                                            <span class="booking-text"><a href="tel:<?=$service['machoose_user_phone']?>" target="_top"><?=$service['machoose_user_phone']?></a></span>
                                                        </div>
                                                        
                                                         <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Total Amount for service :</span>   
                                                            <span class="booking-text" style="color:#F9B90F">₹<?=$album['inpTotalCost']?></span>
                                                        </div>
                                                        
                                                         <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Advance Paid :</span>   
                                                            <span class="booking-text" style="color:#F9B90F">₹<?=$album['numberOfItemsTotalAmount']?></span>
                                                        </div>
                                                        
                                                        
                                                        <div class="booking-details fl-wrap">
                                                            <span class="booking-title">Payment State :</span> 
                                                            <span class="booking-text"> <strong class="done-paid">FULL PAYMENT DONE  </strong>  </span>
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                            
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- dashboard-list end-->   
                                            
                                            <?php } 
                                        
                                     ?>
                                        
                                        <?php }else{ ?>
                                        <div class="dashboard-list">
                                        
                                                 <div class="dashboard-message-text">
                                                     <h4 style="color:red;">Processing Services Unavailable </h4>
                                                     <p>Processing services are not available.  </p>
                                                     
                                                     </div>
                                                     
                                         </div>
                                        
                                        
                                        <?php } ?>
                                        
                                        
                                        
                                        
                                       
                                      
                                        
                                        
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

<script>

    $(document).ready(function() {
        $('#bookings-menu').addClass('act-link');
    });
    
   
    
    
</script>


