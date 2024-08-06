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
 
 $sqlcart = "SELECT * FROM place_order_userservices WHERE user_id=".$user_id." and newpurchaseID !='' and service_status <=2 order by inpEventDate asc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);

if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}



include("templates/header.php");

?>
            
       
<script>
     function setTime(purchaseID,serviceRunTime,serviceTime){
         
        document.addEventListener('DOMContentLoaded', function() {
            // Your code here
            console.log('DOM fully loaded and parsed');
            
            // Example function to run
            runBeforeLoad(purchaseID,serviceRunTime,serviceTime);
        
          
        });
         
       
      }
</script>     
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!-- section-->
                    <section class="flat-header color-bg adm-header">
                        <div class="wave-bg wave-bg2"></div>
                        <div class="container">
                            <div class="dasboard-wrap fl-wrap">
                                <div class="dasboard-breadcrumbs breadcrumbs"><a href="#">Home</a><a href="#">Bookings</a><span>Reserved Services</span></div>
                               
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
                                            <h3>Reserved Services</h3>
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
                                                    
                                                    
                                                    
                                                      $timestamp2 = time();
                                            		    $decodeId2 = base64_encode($timestamp2 . "_".$purchaseID);
                                            		    $decodeId2 = str_rot13($decodeId2);
                                                    
                                                    
                                                    
                                                    
                                                    ?>
                                        
                                             <!-- dashboard-list end-->    
                                            <div class="dashboard-list">
                                                <div class="dashboard-message">
                                                    <span class="new-dashboard-item" onclick="cancelService(`<?=$decodeId2?>`);">Cancel service</span>
                                                    <div class="dashboard-message-avatar">
                                                        <img src="<?=$service['company_logo_url']?>" alt="">
                                                    </div>
                                                    <div class="dashboard-message-text">
                                                        <h4><?=$service['name']?> - <span><?=$album['inpEventDate']?> <?=$amPmTime?></span></h4>
                                                        
                                                        <h1 style="color:green;font-weight: 500;font-size: 16px;padding-bottom: 5px;">Verification Code : <?=$album['otp']?></h1>
                                                        
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
                                                            <?php if($album['razorpay_payment_status'] == 1){ ?>
                                                                <span class="booking-text"> <strong class="done-paid">HALF PAYMENT DONE  </strong>  using Online payment</span>
                                                            <?php }else{ ?>
                                                            <span class="booking-text"> <strong class="done-paid">Failed  </strong>  using Online payment</span>
                                                            
                                                            <?php } 
                                        
                                                                ?>
                                                            
                                                        </div>
                                                        
                                                        
                                                        
                                                         <?php if( $album['service_status'] == 2){
                                                 
                                                 $map = intval($service['number_of_members']) + intval($album['inpExtraPeople']) ;
                                                 $setm = intval($album['mins_time_interval']) + intval($album['inpExtraTime']) ;
                                                 
                                              
                                            		
                                            		$shoot_time = $album['shoot_time'];
                                                		$serviceRunTime = $shoot_time;
                                            		

                                                        $psql13 = "SELECT * FROM service_time_manage WHERE orderID='$purchaseID' ORDER BY id DESC ";
                                                		$timeData = $DBC->query($psql13);
                                                	
                                                		
                                                		
                                                		$serviceTime = mysqli_fetch_assoc($timeData);
                                                		
                                                	
                                                		
                                                		$serviceStatus = $serviceTime['status'];
                                                		$startTime = $serviceTime['startTime'];
                                                		
                                                		
                                                		if($serviceStatus == 0){
                                                		    
                                                		    $datetime1 = new DateTime($startTime);
    
                                                            // Current datetime
                                                            $datetime2 = new DateTime();
                                                            
                                                            // Calculate the difference
                                                            $interval = $datetime1->diff($datetime2);
                                                            
                                                            // Convert the difference to total minutes
                                                            $minutes3 = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                                                            
                                                            $serviceRunTime = intval($serviceRunTime) + intval($minutes3) ;
                                                            
                                                            echo '<script>';
                                                            echo 'setTime('.$purchaseID.','.$serviceRunTime.','.$setm.');';
                                                            echo '</script>';
                                                		    
    
                                                		}else{
                                                		  //  echo $shoot_time;
                                                		}

                                            		$minutes1 = $serviceRunTime;
                                                    $seconds1 = $minutes1 * 60;
                                                    
                                                    // Convert seconds to hours, minutes, and seconds
                                                    $hours1 = floor($seconds1 / 3600);
                                                    $minutes1 = floor(($seconds1 % 3600) / 60);
                                                    $seconds1 = $seconds1 % 60;
                                                    
                                                    // Format the result as hr:min:sec
                                                    $timeRun = sprintf('%02d:%02d:%02d', $hours1, $minutes1, $seconds1);
                                                    
                                                    $additionalTime = intval($serviceRunTime) - intval($setm);
                                                    if($additionalTime <= 0 ) $additionalTime = 0;
                                                    
                                                    $minutes11 = $additionalTime;
                                                    $seconds11 = $minutes11 * 60;
                                                    
                                                    // Convert seconds to hours, minutes, and seconds
                                                    $hours11 = floor($seconds11 / 3600);
                                                    $minutes11 = floor(($seconds11 % 3600) / 60);
                                                    $seconds11 = $seconds11 % 60;
                                                    
                                                    // Format the result as hr:min:sec
                                                    $timeRunExt = sprintf('%02d:%02d:%02d', $hours11, $minutes11, $seconds11);
                                                    
                                                    if($additionalTime <= 0 ) $pendingTime = intval($setm) - intval($serviceRunTime);
                                                    else $pendingTime = 0;
                                                    
                                                    $minutes = $pendingTime;
                                                    $seconds = $minutes * 60;
                                                    
                                                    // Convert seconds to hours, minutes, and seconds
                                                    $hours = floor($seconds / 3600);
                                                    $minutes = floor(($seconds % 3600) / 60);
                                                    $seconds = $seconds % 60;
                                                    
                                                    // Format the result as hr:min:sec
                                                    $time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                                    
                                             
                                             ?>
                                             
                                                <div class="dashboard-message-text" style="padding-top: 15px;">
                                                    
                                                    <h1 style="color:black;font-weight: 500;font-size: 16px;padding-bottom: 5px;">This is <b><?=$service['service_add']?></b> service</h1>
                                                    <h1 style="color:black;font-weight: 500;font-size: 16px;padding-bottom: 5px;">Maximum allowed <b><?=$map?> </b> person</h1>
                                                    <h1 style="color:black;font-weight: 500;font-size: 16px;padding-bottom: 5px;">Extra <b><?=$album['service_extra_person']?> </b> person</h1>
                                                    <h1 style="color:black;font-weight: 500;font-size: 16px;padding-bottom: 5px;">Service time <b><?=$setm?> </b> mins </h1>
                                                    
                                                    
                                                    <h1 style="color:green;font-weight: 500;font-size: 1.5rem;padding-bottom: 5px;" id="serviceTimeDis_<?=$purchaseID?>"><span style="font-size: 1rem;">SERVICE TIME :</span> <?=$timeRun?>  </h1>
                                                    
                                                    <h1 style="color:red;font-weight: 500;font-size: 1rem;padding-bottom: 5px;" id="serviceRemainingTimeDis_<?=$purchaseID?>"><span style="font-size: .7rem;">REMAINING TIME :</span> <?=$time?>   </h1>
                                                    
                                                    <h1 style="color:blue;font-weight: 500;font-size: 1rem;padding-bottom: 5px;" id="serviceExtraTimeDis_<?=$purchaseID?>"><span style="font-size: .7rem;">REMAINING TIME :</span> <?=$time?>    </h1>
                                                    
                                                    <?php if($album['service_status'] == 2){ 
                                                        
                                                        
                                                        $timestamp = time();
                                            		    $decodeId = base64_encode($timestamp . "_".$purchaseID);
                                            		    $decodeId = str_rot13($decodeId);
                                                    
                                                    
                                                    
                                                    
                                                    ?>
                                                    
                                                        <br>
                                                        <hr>
                                                        <br>
                                                        
                                                        <h4>PRICE DETAILS</h4>
                                                        
                                                        <h1 style="color:blue;font-weight: 500;font-size: .8rem;">EXTRA HEAD : <?=$album['service_extra_person']?> nos ₹<?=$album['extraPeoplePrice']?> </h1>
                                                        
                                                        <h1 style="color:blue;font-weight: 500;font-size: .8rem;">EXTRA PHOTOGRAPHER PRICE : <?=$album['inpNumPhotographer']?> nos ₹<?=$album['extra_photographer_price']?>  </h1>
                                                        
                                                        <h1 style="color:blue;font-weight: 500;font-size: .8rem;">EXTRA VEDIOGRAPHER PRICE : <?=$album['inpNumVediographer']?> nos ₹<?=$album['extra_vediographer_price']?>  </h1>
                                                        
                                                        <h1 style="color:blue;font-weight: 500;font-size: .8rem;">FINAL EXTRA PRICE : ₹<?=$album['final_extra_price']?>  </h1>
                                                        
                                                        <h1 style="color:green;font-weight: 500;font-size: 1.5rem;">FINAL PRICE :</span> ₹<?=intval($album['final_extra_price'])+intval($album['numberOfItemsTotalAmount'])?>   </h1>
                                                        
                                                        <br>
                                                        
                                                        <a onclick="payNow(`<?=$decodeId?>`);" style="padding: 12px 30px;border-radius: 4px;color: #fff;background: green;" >Pay ₹<?=intval($album['final_extra_price'])+intval($album['numberOfItemsTotalAmount'])?></a>
                                                        
                                                        
                                                        <?php } ?>
                                                    
                                                    
                                                   
                                                
                                                </div>
                                        
                                            
                                            <?php } ?>
                                            
                                            
                                                        
                                                         <div class="accordion mar-top">
                                                           
                                                            <a class="toggle" href="#"> Full service details  <span></span></a>
                                                            <div class="accordion-inner">
                                                                <p>
                                                                    <div class="row">
                                                                         <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                            <h1>Important Note</h1>
                                                                            <p>Booked & Payable at mifuto.com</p>
                                                                        
                                                                        </div>
                                                                        <br>
                                                                        <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                            <h1>Description of Service</h1>
                                                                            <p><?=$service['description']?></p>
                                                                        
                                                                        </div>
                                                                        <br>
                                                                        <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                            <h1>Cancellation & Amendment Policy</h1>
                                                                            <p>FREE Cancellation until Oct 10, 2017 12:00 hours
                                                                            <br>Non-Refundable if cancelled after Oct 10, 2017 12:00 hour
                                                                            <br>Any Add On charges are non-refundable.</p>
                                                                        
                                                                        </div>
                                                                        <br>
                                                                        <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                            <h1>Price Details</h1>
                                                                            <p><?=$priceDetails?></p>
                                                                        
                                                                        </div>
                                                                        <br>
                                                                        <div class="col-12">
                                                                            <h1>Deliverables you receive</h1>
                                                                            <p><?=$deliverables?></p>
                                                                        
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </p>
                                                            </div>
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
                                                     <h4 style="color:red;">Reserved Services Unavailable </h4>
                                                     <p>Reserved services are not available. Please book services before loading this page. </p>
                                                     
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
    
    function payNow(id){
        window.location.href = 'https://machooosinternational.com/dashboard/complete-user-services.php?purchaseID='+id;
    }
    
 
    
    function cancelService(id){
      
       return new swal({
        title: "Are you sure?",
        text: "Do you want to cancel this service",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                
                 var postData = {
                    function: 'SystemManage',
                    method: "cancelServiceNow",
                    'purchaseNowId': id,
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
                       location.reload();
        
                   
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
        });
      
  }
    
    
    
    
  
    function runBeforeLoad(purchaseID,serviceRunTime,serviceTime) {

        var totalSeconds = parseInt(serviceRunTime) * 60;
        startTimer(totalSeconds,purchaseID,serviceTime);
        
    }
    
    function updateTimerDisplay(totalSeconds,purchaseID,serviceTime) {
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        var timeString = sprintf('%02d:%02d:%02d', hours, minutes, seconds);
      
        $('#serviceTimeDis_'+purchaseID).html('<span style="font-size: 1rem;">SERVICE TIME :</span> '+timeString);
        
        var totalMins = parseInt(totalSeconds) / 60;
        var reTime = parseInt(serviceTime) - parseInt(totalMins);
        if(parseInt(reTime) > 0 ){
            
            var reSec = parseInt(serviceTime) * 60;
            var callSec = parseInt(reSec) - parseInt(totalSeconds);
            
            updateTimerDisplayForRem(callSec,purchaseID);
            updateTimerDisplayForAdditional(0,purchaseID);
            
        }else{
            var reSec = parseInt(serviceTime) * 60;
            var callSec = parseInt(totalSeconds) - parseInt(reSec);
            updateTimerDisplayForAdditional(callSec,purchaseID);
            updateTimerDisplayForRem(0,purchaseID);
            
            
        }
        
      

    }
    
     function updateTimerDisplayForAdditional(totalSeconds,purchaseID) {
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        var timeString = sprintf('%02d:%02d:%02d', hours, minutes, seconds);
        
        $('#serviceExtraTimeDis_'+purchaseID).html('EXTRA TIME : '+timeString);
      

    }
    
    function updateTimerDisplayForRem(totalSeconds,purchaseID) {
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        var timeString = sprintf('%02d:%02d:%02d', hours, minutes, seconds);
        
        $('#serviceRemainingTimeDis_'+purchaseID).html('<span style="font-size: .7rem;">REMAINING TIME :</span> '+timeString);
      

    }

    function startTimer(totalSeconds,purchaseID,serviceTime) {
        setInterval(function() {
            totalSeconds++;
            updateTimerDisplay(totalSeconds,purchaseID,serviceTime);
        }, 1000);
    }

    // Helper function to format time
    function sprintf(format, ...args) {
        return format.replace(/%(\d+)?d/g, function(_, width) {
            var value = args.shift();
            return String(value).padStart(width || 2, '0');
        });
    }
  


    
    
    
</script>


