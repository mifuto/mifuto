<?php 
include("header.php");

// require_once("../admin/config.php");
// $DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

// session_start();
// print_r($_SESSION['MachooseAdminUser']['user_id']);
if($_SESSION['MachooseAdminUser']['id'] == ""){
  header("Location: login.php");
  // print_r("sasaa");
}
// include("templates/provider-header.php");

$isProvider = $_SESSION['isProvider'];

if(!$isProvider){
    echo '<script>';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    
}

$user_id = $_SESSION['MachooseAdminUser']['id']; 



$ordersData = [];

$sqlcart = "SELECT a.* FROM place_order_userservices a left join tblprovider_services ins on a.inpServiceID = ins.id left join tblproviderusercompany b on b.id=ins.main_id left join tblprovideruserlogin s on s.id=b.user_id WHERE s.id='".$user_id."' and a.newpurchaseID !='' order by a.id desc";
 
//  $sqlcart = "SELECT a.* FROM place_order_userservices a left join tblprovider_services ins on a.inpServiceID = ins.id left join tblproviderusercompany b on b.id=ins.main_id left join tblprovideruserlogin s on s.id=b.machoose_user_id WHERE s.id='".$user_id."' and a.newpurchaseID !='' order by a.id desc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);



if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}




?>

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
                        
                        
                        
                        
                        ?>
                        
                        
                        <div class="row pt-2">
                            <div class="col-12 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                   <div class="card-body pt-4">
                                       <div class="row">
                                           <div class="col-10">
                                               
                                               
                                               <h4><?=$service['name']?> -<span><?=$album['inpEventDate']?> <?=$amPmTime?></span> ID: <span class="text-primary"><?=$cardData1['newpurchaseID']?></span></h4>
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
                                                ==============================================
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Total Amount for service:</span>   
                                                    <span class="booking-text text-primary" ><b>₹<?=$album['inpTotalCost']?></b></span>
                                                </div>
                                                
                                                 <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Advance Paid:</span>   
                                                    <span class="booking-text text-primary" ><b>₹<?=$album['numberOfItemsTotalAmount']?></b></span>
                                                </div>
                                                 <div class="booking-details fl-wrap">
                                                                                                   ==============================================
                                                </div>
                                                
                                                
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Payment Status :</span> 
                                                    <?php if($album['razorpay_payment_status'] == 1){ ?>
                                                        <span class="booking-text"> <strong class="done-paid text-white bg-success">HALF PAYMENT DONE  </strong>  using Online payment</span>
                                                    <?php }else{ ?>
                                                    <span class="booking-text"> <strong class="done-paid text-white bg-danger">Failed  </strong> </span>
                                                    
                                                    <?php } 
                                
                                                        ?>
                                                    
                                                </div>
                                                
                                                <div class="booking-details fl-wrap">
                                                    <span class="booking-title">Deliverable Status :</span> 
                                                   
                                                    <span class="booking-text"> <strong class="done-paid text-white bg-primary">In-Process  </strong> </span>
                                                    
                                                
                                                </div>
                                               
                                               
                                               
                                            </div>
                                            <div class="col-2 text-center pt-4">
                                                <img src="<?=$service['company_logo_url']?>" alt="" class="img-circle img-fluid">
                                            </div>
                                            
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
    
   
    
    $( document ).ready(function() {
   
  });
  
 
 
    
    
</script>





