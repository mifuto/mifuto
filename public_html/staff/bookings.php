<?php 

include("header.php");


// session_start();
// print_r($_SESSION['MachooseAdminUser']['user_id']);
if($_SESSION['MachooseAdminUser']['id'] == ""){
  header("Location: login.php");
  // print_r("sasaa");
}
// include("templates/provider-header.php");

$isProvider = $_SESSION['isProviderStaff'];

if(!$isProvider){
    echo '<script>';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    
}

$user_id = $_SESSION['MachooseAdminUser']['id']; 


$ordersData = [];
 
 $sqlcart = "SELECT a.* FROM place_order_userservices a left join tblprovider_services ins on a.inpServiceID = ins.id left join tblproviderusercompany b on b.id=ins.main_id left join tblmifutostaffuserlogin s on s.id=b.machoose_user_id WHERE a.photographerID='".$user_id."' and a.newpurchaseID !='' and a.service_status <=2 order by a.inpEventDate asc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);



if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}


// include("header.php");

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

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Booked Services</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Bookings</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
          
          
          <?php if(count($ordersData) > 0) { ?>
                                        
                    <?php 
                    $cc = 0;
                    foreach ($ordersData as $key => $album) { 
                        
                        $cc++;
                        
                        $purchaseID = $album['id'];
                        
                        $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
                    	$cardData1r = $DBC->query($psql);
                    	$cardData1 = mysqli_fetch_assoc($cardData1r);
                    		
                		$user_id = $album['user_id'];
                		$decodedKey = $album['inpServiceID'];
                		
                		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
            		    $UserList = $DBC->query($sqlU);
            		    $UserList = mysqli_fetch_assoc($UserList);
            		    
            		    $eventUser = $UserList['name'];
            		    $eventUserEmail = $UserList['email'];
                		
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
                        
                        $todayDate = date('Y-m-d');
                        $todayShoot = false;
                        
                        if ($album['inpEventDate'] === $todayDate ) {
                            $todayShoot = true;
                        }
                        
                        
                        
                        
                        
                        
                        ?>
                        
                        
                        <div class="row pt-2">
                            <div class="col-12 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                   <div class="card-body pt-4">
                                       <div class="row">
                                           <div class="col-10">
                                               
                                               
                                               <h4><?=$service['name']?> BOOKING ID: <span class="text-primary"><?=$cardData1['newpurchaseID']?></span> Date :<span class="text-primary"><?=$album['inpEventDate']?> <?=$amPmTime?></span> </h4>
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Customer Name :</span>
                                                    <span class="booking-text text-primary"><?=$eventUser?></span>
                                                </div>
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Customer Contact:</span>   
                                                    <span class="booking-text text-primary">+91 <?=$UserList['phone']?>, <?=$UserList['email']?></span>
                                                </div>
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Service Booking Date & Time:</span>   
                                                    <span class="booking-text text-primary"><?=$camPmTime?></span>
                                                </div>
                                                
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Company:</span>   
                                                    <span class="booking-text text-primary"><?=$service['company_name']?></span>
                                                </div>
                                                
                                                  <div class="booking-details fl-wrap">                                                               
                                                    <span class="booking-title">Service Type:</span>  
                                                    <span class="booking-text text-primary"><?=$service['service_add']?></span>
                                                </div>
                                                
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Booking id:</span>   
                                                    <span class="booking-text text-primary"><?=$cardData1['newpurchaseID']?></span>
                                                </div>
                                                
                                                
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Bill No:</span>   
                                                    <span class="booking-text text-primary"><?=$cardData1['newpurchaseID']?></span>
                                                </div>
                                               
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Advance Receipt Number</span>   
                                                    <span class="booking-text text-primary"><?=$cardData1['newpurchaseID']?></span>
                                                </div>
                                                
                                               =============================================
                                                
                                                  <!--  <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Total Amount for service:</span>   
                                                    <span class="booking-text text-primary" >₹<?=$album['inpTotalCost']?></span>
                                                </div>
                                                
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Advance Paid:</span>   
                                                    <span class="booking-text text-primary" >₹<?=$album['numberOfItemsTotalAmount']?></span>
                                                </div>
                                                 <div class="booking-details fl-wrap">
                                                </div>-->
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Balance:</span>   
                                                    <span class="booking-text text-primary"><font color="red">₹<?=$album['numberOfItemsTotalAmount']?></font></span>
                                                </div>
                                                 <div class="booking-details fl-wrap">
                                                </div>
                                                
                                                =============================================
                                                
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Payment Status :</span> 
                                                    <?php if($album['razorpay_payment_status'] == 1){ ?>
                                                        <span class="booking-text"> <strong class="done-paid text-white bg-success">HALF PAYMENT  </strong>  using Online payment</span>
                                                    <?php }else{ ?>
                                                    <span class="booking-text"> <strong class="done-paid text-white bg-danger">Failed  </strong> </span>
                                                    
                                                    <?php } 
                                
                                                        ?>
                                                    
                                                </div>
                                                
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Deliverable Status :</span> 
                                                    
                                                    <?php if( $album['service_status'] == 0){ ?>
                                                    <span class="booking-text"> <strong class="done-paid text-white bg-primary">In-Process </strong> </span>
                                                    <?php }else if( $album['service_status'] == 1){ ?>
                                                    <span class="booking-text"> <strong class="text-white bg-warning">Started </strong> </span>
                                                    <?php }else{ ?>
                                                    <span class="booking-text"> <strong class="text-white bg-success">Shoot Completed </strong> </span>
                                                    <?php }?>
                                                
                                                </div>
                                               
                                               
                                               
                                            </div>
                                            <div class="col-2 text-center pt-4">
                                                <img src="<?=$service['company_logo_url']?>" alt="" class="img-circle img-fluid">
                                                
                                            </div>
                                            
                                             <?php if( $album['service_status'] == 2){
                                                 
                                                 $map = intval($service['number_of_members']) + intval($album['inpExtraPeople']) ;
                                                 $setm = intval($album['mins_time_interval']) + intval($album['inpExtraTime']) ;
                                                 
                                                 
                                                   
                                                    
                                                    
                                                    $psql13 = "SELECT * FROM service_time_manage WHERE orderID='$purchaseID' ORDER BY id DESC ";
                                            		$timeData = $DBC->query($psql13);
                                            		
                                            		$serviceTime = mysqli_fetch_assoc($timeData);
                                            		
                                            		$serviceStatus = $serviceTime['status'];
                                            		$startTime = $serviceTime['startTime'];
                                            		
                                            		$shoot_time = $album['shoot_time'];
                                            		$serviceRunTime = $shoot_time;
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
                                             
                                                <div class="col-12 ">
                                                    <hr>
                                                    
                                                    <div class="row">
                                                        
                                                        <div class="col-6 pt-4" >
                                                    
                                                            <div align="left">
                                                                
                                                                <span for="" class="col-12">This is <b><?=$service['service_add']?></b> service  </span><br>
                                                                <span for="" class="col-12">Maximum allowed <b><?=$map?> </b> person  </span><br>
                                                                <span for="" class="col-12">Extra <b><?=$album['service_extra_person']?> </b> person  </span><br>
                                                                
                                                                <span for="" class="col-12">Service time <b><?=$setm?> </b> mins  </span>
                                                                
                               
                                                             
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="col-6 pt-2" align="center">
                                                            
                                                            <h2 class="text-dark" id="serviceTimeDis_<?=$purchaseID?>"><span style="font-size: 1rem;">SERVICE TIME :</span> <?=$timeRun?> </h2>
                                                            <h5 class="text-danger" id="serviceRemainingTimeDis_<?=$purchaseID?>"><span style="font-size: .7rem;">REMAINING TIME :</span> <?=$time?> </h5>
                                                            
                                                            <?php if($album['service_status'] == 1){ ?>
                                                            
                                                                <?php if($serviceStatus == 0){ ?>
                                                                    <button  class="btn btn-success btn-sm" type="button" onclick="pauseService(<?=$purchaseID?>,<?=$shoot_time?>);"> PAUSE</button>
                                                                <?php }else{ ?>
                                                                    <button  class="btn btn-success btn-sm" type="button" onclick="resumeService(<?=$purchaseID?>);"> RESUME</button>
                                                                <?php } ?>
                                                           
                                                            
                                                            <button  class="btn btn-danger btn-sm" type="button" onclick="stopService(<?=$purchaseID?>,<?=$shoot_time?>,<?=$serviceStatus?>);"> STOP</button>
                                                            
                                                            <br>
                                                            <?php } ?>
                                                            
                                                            <span class="text-primary" id="serviceExtraTimeDis_<?=$purchaseID?>">EXTRA TIME : <?=$timeRunExt?>  </span><br>
                                                            <!--<span class="text-primary">GRAND TOTAL FOR AFTER COMPLETE SERVICE : ₹0.00  </span><br>-->
                                                            
                                                        </div>
                                                        
                                                        <?php if($album['service_status'] == 2){ ?>
                                                        
                                                            <div class="col-12 pt-4" >
                                                        
                                                                <div align="left">
                                                                    
                                                                    <span class="text-primary">EXTRA HEAD : <?=$album['service_extra_person']?> nos ₹<?=$album['extraPeoplePrice']?>  </span><br>
                                                                    
                                                                    <span class="text-primary">EXTRA PHOTOGRAPHER PRICE : <?=$album['inpNumPhotographer']?> nos ₹<?=$album['extra_photographer_price']?>  </span><br>
                                                                    
                                                                    <span class="text-primary">EXTRA VEDIOGRAPHER PRICE : <?=$album['inpNumVediographer']?> nos ₹<?=$album['extra_vediographer_price']?>  </span><br>
                                                                    
                                                                    <span class="text-primary">FINAL EXTRA PRICE : ₹<?=$album['final_extra_price']?>  </span><br>
                                                                    
                                                                    <h2 class="text-success"><span style="font-size: 1rem;">FINAL PRICE :</span> ₹<?=intval($album['final_extra_price'])+intval($album['numberOfItemsTotalAmount'])?> </h2>
                                                                    
                                                               
                                                                    
                                   
                                                                 
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                        
                                                        <?php } ?>
                                                 
                                                       
                                                        
                                                    </div>
                                                    
                                                    <hr>
                                                
                                                </div>
                                        
                                            
                                            <?php } ?>
                                            
                                            
                                            
                                            
                                            
                                            <div class="col-12 pt-4" id="accordion">
                                            
                                                <div class="card card-primary card-outline">
                                                    <a class="d-block w-100" data-toggle="collapse" href="#collapse_<?=$cc?>">
                                                        <div class="card-header">
                                                            <h4 class="card-title w-100">
                                                                Full service details
                                                            </h4>
                                                        </div>
                                                    </a>
                                                    <div id="collapse_<?=$cc?>" class="collapse" data-parent="#accordion">
                                                        <div class="card-body">
                                                            
                                                            
                                                            <p>
                                                                <div class="row">
                                                                    
                                                                    <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                        <h4>Description of Service</h4>
                                                                        <p><?=$service['description']?></p>
                                                                    
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                        <h4>Cancellation & Amendment Policy</h4>
                                                                        <p>FREE Cancellation until Oct 10, 2017 12:00 hours
                                                                        <br>Non-Refundable if cancelled after Oct 10, 2017 12:00 hour
                                                                        <br>Any Add On charges are non-refundable.</p>
                                                                    
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-12" style="border-bottom: 1px solid #eee;">
                                                                        <h4>Price Details</h4>
                                                                        <p><?=$priceDetails?></p>
                                                                    
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-12">
                                                                        <h4>Deliverables receive</h4>
                                                                        <p><?=$deliverables?></p>
                                                                    
                                                                    </div>
                                                                    
                                                                </div>
                                                            </p>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                             
                                            </div>
                                            
                                            <?php if($todayShoot && $album['service_status'] == 0){ ?>
                                            
                                            <div class="col-12 pt-2" align="right">
                                                
                                                
                                                <button  class="btn btn-success " type="button" onclick="startNow(<?=$purchaseID?>,`<?=$service['number_of_members']?>`,`<?=$album['mins_time_interval']?>`,`<?=$album['inpExtraPeople']?>`,`<?=$album['inpExtraTime']?>`,`<?=$service['service_add']?>`);"> Start Service</button>

                                            </div>
                                            
                                                
                                            <?php } ?>
                                            
                                            <?php if( $album['service_status'] == 2){ 
                                                
                                                $timestamp = time();
                                            		    $decodeId = base64_encode($timestamp . "_".$purchaseID);
                                            		    $decodeId = str_rot13($decodeId);
                                            		    
                                            		    
                                            		    
                                            		    
                                            		    $projIdString = str_rot13($decodeId);
                                                        $projIdString = base64_decode($projIdString);
                                                        
                                                        $arr = explode('_', $projIdString);
                                                        $purchaseID = $arr[1];
                                                        
                                                        $timestamp = time();
                                                        $setT = 'MI'.$timestamp.'C'.$purchaseID;
                                                        
                                                        $newpurchaseID = str_rot13($setT);
                                                        
                                                        $fppv = intval($album['final_extra_price'])+intval($album['numberOfItemsTotalAmount']);
                                                        
                                                        
                                                        $final_extra_price = $album['final_extra_price'];
                                    
                                                        $IGST = number_format( ((floatval($final_extra_price) * 18 )/ 118 ) , 2 ) ;
                                                        
                                                        $CGST = number_format( ((floatval($final_extra_price) * 9 )/ 118), 2 ) ;
                                                        $SGST = number_format( ((floatval($final_extra_price) * 9 )/ 118), 2 ) ;
                                            
                                            
                                            
                                            ?>
                                            
                                                <div class="col-12 pt-2" align="right">
                                                    <button  class="btn btn-success " type="button" onclick="payNow(`<?=$newpurchaseID?>`,`<?=$purchaseID?>`,`<?=$fppv?>`,`<?=$IGST?>`,`<?=$CGST?>`,`<?=$SGST?>`,`<?=$eventUser?>`);"> Collect cash</button>
                                                </div>

                                            <?php } ?>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        
                        
                     
                
                <?php } 
            
         ?>
            
            <?php }else{ ?>
            <div class="dashboard-list">
            
                     <div class="dashboard-message-text">
                         <h4 style="color:red;">Reserved Services Unavailable </h4>
                         <p>Reserved services are currently unavailable. Please wait for an available service.</p>
                         
                         </div>
                         
             </div>
            
            
            <?php } ?>
                                        
          
          
          
          
          
          
             
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
    
    
    
    
    
    
      
  </div>
  <!-- /.content-wrapper -->
  
  
  
  
   <div class="modal fade" id="modal-start-service">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Start Service</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal();">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
             
                <div class="modal-body">
                    
                    
                    <h5 class="text-info">PLEASE ENTER DETAILS BEFORE STARTING SERVICES </h5>
                    
                     <div class="row mb-3">
                        <label for="" class="col-12 col-form-label">Enter pin number</label>
                        <div class="col-6">
                            <input type="text" class="form-control" id="inpPIN" name="inpPIN" placeholder="Enter pin number" >
    
                            <div class="invalid-feedback">
                            Please enter the pin number!.
                            </div>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-success" onclick="verifyOtp();">Verify</button>
                            
                        </div>
                        <div class="col-12 mt-2" align="center"><div id="otpStatus"></div></div>
                        
                    </div>
                    
                    
                    <div id="serviceModalDetails"></div>
                    
                     <div class="row mb-3">
                        <label for="" class="col-12 col-form-label">Extra person</label>
                       
                        <div class="col-6">
                            
                             <select class="form-control select2" aria-label="Default select example" id="selExtraPerson" name="selExtraPerson" >
                                 <option value="0" selected>--Select--</option>
                                 <?php for($i=1;$i<=50;$i++){
                                     echo '<option value="'.$i.'">'.$i.'</option>';
                                 }?>
                                </select>
                            
                            
                            
                            <div class="invalid-feedback">
                            Please select the extra person!.
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="col-12 mt-2" align="center"><div id="serviceStartStatus"></div></div>
            
                  
                  
                  
                  
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal();">Close</button>
                  
                  
                  <button type="button" class="btn btn-primary" id="submitButton13" onclick="startService();">Start service</button>
                  <button class="btn btn-primary d-none" type="button" id="submitLoadingButton13" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Please wait...
                  </button>
                  
                  
                  
                </div>
                

              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        
        
        
        
        
        
        
   <div class="modal fade" id="modal-cash-recive-service">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Collect cash</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal1();">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
             
                <div class="modal-body">
                    
                    

                    <div id="servicePaymentModalDetails"></div>
                    
                    <div class="col-12 mt-2 text-danger d-none" id="cashChkErr">Please check the checkbox</div>
                    
                 
                  
                </div>
                <div class="modal-footer justify-content-between">
              
                  <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal1();">Close</button>
                  
                  
                  <button type="button" class="btn btn-primary" id="submitButton131" onclick="completePayment();">Collected cash</button>
                  <button class="btn btn-primary d-none" type="button" id="submitLoadingButton131" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Please wait...
                  </button>
                  
                  
                  
                </div>
                

              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        
        
        
  
  
<?php 

include("footer.php");



?>

<script>
    $('#navDashboard').removeClass('active');
    $('#navOurCompanies').removeClass('active');
    $('#navOurServices').removeClass('active');
    $('#navProfile').removeClass('active');
    $('#navBookings').addClass('active');
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
    var selItemId = '';
    var selOtpVal = '';
    
    var newpurchaseIDVal = '';
    var purchaseIDVal = '';
    
    var IGSTval = '';
    var CGSTval = '';
    var SGSTval = '';
    
    
    $( document ).ready(function() {
   
  });
  
  function completePayment(){
      
      $('#cashChkErr').addClass('d-none');
      
      // Get the checkbox element
        var checkbox = document.getElementById('checkCashRecived');
        
        // Check if the checkbox is checked
        if (checkbox.checked) {
            
        } else {
            checkbox.focus();
            $('#cashChkErr').removeClass('d-none');
            return false;
        }

   
      
       $('#submitButton131').addClass('d-none');
     $('#submitLoadingButton131').removeClass('d-none');
      
    
        var postData = {
            function: 'AlbumSubscription',
            method: "updateMifutoUserCardServiceCompletePayment",
            'newpurchaseID': newpurchaseIDVal,
            'purchaseID': purchaseIDVal,
            'razorpay_payment_id': 'razorpay_payment_id',
            'razorpay_payment_status': 1,
            'razorpay_signature':'razorpay_signature',
            
            'IGST': IGSTval,
            'CGST':CGSTval,
            'SGST':SGSTval,
            
         }
         
         
        successFn = function(resp)  {
        
            if(resp.status == 1){
              location.reload();
                
            }
            
             $('#submitButton131').removeClass('d-none');
                $('#submitLoadingButton131').addClass('d-none');
               
          
        }
        data = postData;
        
        apiCallForProvider(data,successFn);
         
         
       
      
  }
  

   function payNow(newpurchaseID,purchaseID,Price,IGST,CGST,SGST,eventUser){
       
       $('#cashChkErr').addClass('d-none');
       
       newpurchaseIDVal = newpurchaseID;
       purchaseIDVal = purchaseID;
       
       IGSTval = IGST;
       CGSTval = CGST;
       SGSTval = SGST;
       
       
       $('#servicePaymentModalDetails').html('');
       
       // Get today's date
        var today = new Date();
        
        // Format the date as YYYY-MM-DD
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        var dd = String(today.getDate()).padStart(2, '0');
        
        var formattedToday = yyyy + '-' + mm + '-' + dd;
       
         var tbl = '';
      tbl += '<div class="row mb-3">';
      tbl += '<span for="" class="col-12">Date: <b>'+formattedToday+'</b>  </span><br>';
      tbl += '<span for="" class="col-12">Transaction ID: <b>#'+newpurchaseID+'</b>  </span><br><br>';
      
       tbl += '<span for="" class="col-12 text-success"><h2>Final amount due : <b>₹'+Price+'</b>  </h2></span><br><br><br>';
       
       tbl += '<div class="form-check">';
       tbl += '<input type="checkbox" class="form-check-input" id="checkCashRecived">';
       tbl += '<label class="form-check-label" >I have received the balance amount from <b>'+eventUser+'</b> in cash.</label>';
       tbl += '</div>';
         
               
      
    
      tbl += '</div>';
      
      
      $('#servicePaymentModalDetails').html(tbl);
       
       
       

       $('#modal-cash-recive-service').modal('show');
       
        // window.location.href = 'https://machooosinternational.com/dashboard/complete-user-services-by-cash.php?purchaseID='+id;
    }
    
    function closeModal1(){
      $('#modal-cash-recive-service').modal('hide');
  }
  
    
  
  
  
  
  function stopService(selItemId,shoot_time,run_type){
      
       successFn = function(resp)  {
        
        if(resp.status == 1){
          location.reload();
            
        }
      
    }
    data = { "function": 'SystemManage',"method": "stopService" ,'selItemId':selItemId,"shoot_time":shoot_time,"run_type":run_type };
    
    apiCallForProvider(data,successFn);
      
  }
  
  
  
  
  function pauseService(selItemId,shoot_time){
      
       successFn = function(resp)  {
        
        if(resp.status == 1){
          location.reload();
            
        }
      
    }
    data = { "function": 'SystemManage',"method": "pauseService" ,'selItemId':selItemId,"shoot_time":shoot_time };
    
    apiCallForProvider(data,successFn);
      
  }
  
  function resumeService(selItemId){
      
    successFn = function(resp)  {
        
        if(resp.status == 1){
          location.reload();
            
        }
      
    }
    data = { "function": 'SystemManage',"method": "resumeService" ,'selItemId':selItemId };
    
    apiCallForProvider(data,successFn);
      
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
  
 
  
  function startService(){
      
      $('#serviceStartStatus').html('');
      
      $('#inpPIN').removeClass('is-invalid');
      var inpPIN = $('#inpPIN').val();
      
       if(inpPIN == ""){
         $('#inpPIN').addClass('is-invalid');
         $('#inpPIN').focus();
         return false;
     }
     
     if(selOtpVal == ""){
         $('#otpStatus').html('<b class="text-warning ">Otp verification pending</b>');
         return false;
     }
     
     var selExtraPerson = $('#selExtraPerson').val();
     
     $('#submitButton13').addClass('d-none');
     $('#submitLoadingButton13').removeClass('d-none');
     
     
    successFn = function(resp)  {
        
        if(resp.status == 1){
         location.reload();
            
        }else{
          $('#serviceStartStatus').html('<b class="text-danger ">Something went wrong, please try again</b>');
        }
        
         $('#submitButton13').removeClass('d-none');
     $('#submitLoadingButton13').addClass('d-none');
      
    }
    data = { "function": 'SystemManage',"method": "startService" ,'selItemId':selItemId ,'selExtraPerson':selExtraPerson };
    
    apiCallForProvider(data,successFn);
 
  }
  
  
  
  
  function verifyOtp(){
      
       $('#submitButton13').removeClass('d-none');
     $('#submitLoadingButton13').addClass('d-none');
      
      selOtpVal = '';
      $('#serviceStartStatus').html('');
      
      $('#inpPIN').removeClass('is-invalid');
      var inpPIN = $('#inpPIN').val();
      
       if(inpPIN == ""){
         $('#inpPIN').addClass('is-invalid');
         $('#inpPIN').focus();
         return false;
     }
     
     
      successFn = function(resp)  {
        
        if(resp.status == 1){
           selOtpVal = 1;
         $('#otpStatus').html('<b class="text-success ">Otp verified</b>');
            
        }else{
          $('#otpStatus').html('<b class="text-danger ">Otp verification failed</b>');
        }
        
     
      
    }
    data = { "function": 'SystemManage',"method": "serviceOtpVerification" ,'selItemId':selItemId ,'otp':inpPIN };
    
    apiCallForProvider(data,successFn);
     
     
  }
  

  function startNow(id,number_of_members,mins_time_interval,inpExtraPeople,inpExtraTime,service_add){
      selItemId = id;
      selOtpVal = '';
      
      $('#submitButton13').removeClass('d-none');
     $('#submitLoadingButton13').addClass('d-none');
      
      $('#serviceModalDetails').html('');
      $('#serviceStartStatus').html('');
      $('#otpStatus').html('');
      var tbl = '';
      tbl += '<div class="row mb-3">';
      tbl += '<span for="" class="col-12">This is <b>'+service_add+'</b> service  </span><br>';
      tbl += '<span for="" class="col-12">Maximum allowed <b>'+( parseInt(number_of_members) + parseInt(inpExtraPeople) )+'</b> person  </span><br>';
      tbl += '<span for="" class="col-12">Service time <b>'+( parseInt(mins_time_interval) + parseInt(inpExtraTime) )+'</b> mins  </span>';
      tbl += '</div>';
      
      
      $('#serviceModalDetails').html(tbl);
                 
      
      
      $('#modal-start-service').modal('show');
  }
  
  function closeModal(){
      $('#modal-start-service').modal('hide');
  }
  
 
 
    
    
</script>





