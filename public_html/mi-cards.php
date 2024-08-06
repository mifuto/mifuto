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

$stateName = 'Kerala';

    
$Cards = [];

 
$sql3 = "SELECT a.* FROM tbluser_cards a WHERE a.active=0 order by a.amount asc ";

$result3 = $DBC->query($sql3);

$count3 = mysqli_num_rows($result3);

if($count3 > 0) {		
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($Cards,$row3);
      
    }
}


$ordersData = [];
 
 $sqlcart = "SELECT * FROM place_order_usercard WHERE user_id=".$user_id." and newpurchaseID !='' order by id desc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);

if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}


$activeCardName = "";

include("templates/header.php");



?>

<style>

.displayCardNumber {
    padding-left: 100px;
}

.displayExpDate {
    padding-top: 0px;
    padding-left: 55%;
    font-size: smaller;
}

.displayCardNumber1 {
    padding-top: 10px;
    padding-left: 100px;
}

.displayExpDate1 {
    padding-top: 7px;
    padding-left: 170px;
    font-size: smaller;
}

@media only screen and (max-width: 480px) {
    .displayCardNumber1 {
        padding-top: 0px;
        padding-left: 55px;
    }
    
    .displayExpDate1 {
        padding-top: 0px;
        padding-left: 135px;
        font-size: smaller;
    }
}


 .black-dot {
      width: 10px;
      height: 10px;
      background-color: #a0a2a4;
      border-radius: 50%;
      display: inline-block;
    }
    
    
    
    
    
.modal-body{

    background-color: black;
}



.intro-1{

    font-size: 16px;
}

.close{

    color: #fff;
}


.close:hover{

    color: #fff;
}


.intro-2{

    font-size: 13px;
}


/* Apply styles to the table */
table {
  width: 100%; /* Set table width to 100% of its container */
  border-collapse: collapse; /* Collapse table borders */
  border-spacing: 0; /* Set border spacing to 0 */
}

/* Apply styles to table header cells */
th {
  background-color: #f2f2f2; /* Set background color for header cells */
  color: #333; /* Set text color for header cells */
  font-weight: bold; /* Make header text bold */
  padding: 8px; /* Add padding to header cells */
  border: 1px solid #ddd; /* Add border to header cells */
}

/* Apply styles to table data cells */
td {
  padding: 8px; /* Add padding to data cells */
  border: 1px solid #ddd; /* Add border to data cells */
}

/* Apply alternate background color to even rows */
tr:nth-child(even) {
  background-color: #f9f9f9; /* Set alternate background color for even rows */
}

/* Apply hover effect to table rows */
tr:hover {
  background-color: #f2f2f2; /* Set background color on hover */
}

    
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
            
            
            <!--  wrapper  -->
            <div id="wrapper">
                <!-- content-->
                <div class="content">
                    <!--  section  -->
                    <section class="parallax-section single-par" data-scrollax-parent="true">
                        <div class="bg par-elem "  data-bg="images/bg/1.jpg" data-scrollax="properties: { translateY: '30%' }"></div>
                        <div class="overlay"></div>
                        <div class="container">
                            <div class="section-title center-align big-title">
                                <div class="section-title-separator"><span></span></div>
                                <h2><span>Our Card Plans</span></h2>
                                <span class="section-separator"></span>
                                <h4>Explore our diverse range of card plans tailored to your needs.</h4>
                            </div>
                        </div>
                        <div class="header-sec-link">
                            <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                        </div>
                    </section>
                    <!--  section  end-->
                    <div class="breadcrumbs-fs fl-wrap">
                        <div class="container">
                            <div class="breadcrumbs fl-wrap"><a href="#">Home</a><a href="#">Cards</a><span>Our Cards</span></div>
                        </div>
                    </div>
                    <!--  section  -->
                    <section  id="sec1" class="grey-b lue-bg middle-padding">
                        
                        
                        <div class="container">
                            

                            <div id="cardDisplayDiv">
                                
                                <h1 style="color: black;text-align: left;font-weight: normal !important;margin-bottom: 0.75rem;font-size: 1.25rem;">Active Cards</h1>
                                
                                <?php if(count($Cards) > 0) { ?>
                                    <div class="row">
                                        
                                        <?php
                                          $isPurchaseable = false;
                                          $purchaseCardNumber = '0000 0000 0000 0000';
                                          
                                          
                                          foreach ($Cards as $key => $card) { 
                                          
                                          $id = $card['id'];
                                          $exp = $card['exp']." year";
                                          
                                          $timestamp = time();
                                          $decodeId = base64_encode($timestamp . "_".$id);
                                          $decodeId = str_rot13($decodeId);
                                          
                                           $psql = "SELECT * FROM place_order_usercard WHERE razorpay_payment_status=1 AND completed=1 AND isNew=1 AND card_id='$id' AND user_id='$user_id' ";
                                		    $presult = $DBC->query($psql);
                                		    $presult1 = $DBC->query($psql);
        
                                            $pcount = mysqli_num_rows($presult);
                                            if($pcount > 0){ 
                                                $isPurchaseable = true;
                                                $prow2 = mysqli_fetch_assoc($presult1);
                                                $purchaseCardNumber = $prow2['card_number'];
                                                
                                            }
                                  
                                  
                                  
                                          
                                        ?>
                                        
                                        
                                        <?php if($isPurchaseable > 0){ ?>
                                        
                                        
                                            <?php if($pcount > 0){ 
                                                                        
                                                $prow1 = mysqli_fetch_assoc($presult);
                                                $cardNumber = $prow1['card_number'];
                                                $cardExp = $prow1['exp_date'];
                                                $cardNumber = chunk_split($cardNumber, 4, ' ');
                                                
                                                $dateTime = new DateTime($cardExp);

                                                // Format the date
                                                $formattedDate = $dateTime->format('d/m/Y');
                                                
                                                
                                                
                                                $cardExp = $formattedDate;
                                                
                                                $cardExpDate = $prow1['exp_date'];
                                               // Convert the given date string to a DateTime object
                                                $givenDate = new DateTime($cardExpDate);
                                                
                                                // Get today's date as a DateTime object
                                                $today = new DateTime();
                                                
                                                $isExp = false;
                                                
                                                if ($givenDate >= $today) {
                                                    $isExp = false;
                                                } else {
                                                    $isExp = true;
                                                    $purchaseCardExp = true;
                                                }
                                                
                                                $activeImageUrl = "/admin/".$card['image'];
                                                $activeName = strtoupper($card['card_name']);
                                                $activeExp = $cardExp;
                                                $activeNumber = $cardNumber;
                                                $activeUserName = $userName;
                                                
                                                $userPurchasedOrderId = $prow1['id'];
                                                
                                               
                                              $activeCardName = strtoupper($card['card_name']);

                                            ?>
                                            
                                            
                                                    <?php if($isExp){ ?>
                                                                                            
                                                    <div class="col-md-4" style="height: 208px;padding-top:5px;" onclick="showCardBenfits(<?=$card['id']?>,`<?=$decodeId?>`);">
                                                    <div class="card" style="background-image: url('https://machooosinternational.com/admin/<?= $card['image'] ?>'); background-size: cover; background-position: center;width: 100% !important;height: 100% !important;opacity: .3;" >
                                                    
                                                    <?php }else{ ?>
                                                    

                                                    <div class="col-md-4" style="height: 208px;padding-top:5px;" onclick="showCardBenfits(<?=$card['id']?>,`<?=$decodeId?>`);">
                                                    <div class="card" style="background-image: url('https://machooosinternational.com/admin/<?= $card['image'] ?>'); background-size: cover; background-position: center;width: 100% !important;height: 100% !important;opacity: 1;" >
                                                   
                                                    
                                                    <?php } ?>
                                                    
                                                    
                                                     
                                                        
                                                    
                                                            <div class="card-body" style="padding-top: 20px; !important">
                                                                   <div class="clearfix" style="text-align: right;">
                                                                           <b class=" float-right text-white"  style="padding-right: 27px;padding-top: 0px;color: white;"><?=strtoupper($userName)?></b>
                                                                  </div>
                                                                  <h4 class="card-title font-weight-normal text-warning" style="padding-top: 62px;padding-left: 65px;color: #FFC105 !important;font-weight: normal !important;font-size: 1.5rem;line-height: 1.1;text-align: right !important;
        padding-right: 40% !important;"><?=strtoupper($card['card_name'])?></h4>
                                                                  <h5 class="card-title font-weight-normal text-white displayCardNumber" style="color: white;font-weight: normal !important;margin-bottom: 0.75rem;font-size: 1.25rem;text-align: left !important;
        "><?=$purchaseCardNumber?></h5>
                                                                  <h6 class="card-title font-weight-normal text-white displayExpDate" style="color: white;text-align: left !important;padding-right: 30% !important;" ><?=$activeExp?></h6>
                                                                  
                                                                  
                                                                  
                                                             
                                                            </div>
                                                            
                                                            
                                                            <?php if($isExp){ ?>
                                                                                            
                                                                <!-- Add a centered button -->
                                                              <div style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%);">
                                                                 <img class="text-white" src="https://machooosinternational.com/dashboard/images/icons/lock-white.png" alt="" style="width: 30%;height: 30%;">
                                              
                                                            <?php } ?>
                                                            
                                                            
                                                            
                                                             
                                                            
                                                            
                                                            
                                                            
                                                            
                                                    </div>
                                                  </div>
                                                  
                                                    
                                                    
                                                    
                                                    
                                                    
                                            
                                            
                                            
                                            
                                            <?php }else{ ?>
                                            
                                            
                                            
                                            
                                                 <div class="col-md-4" style="height: 208px;padding-top:5px;" onclick="showCardBenfits(<?=$card['id']?>,`<?=$decodeId?>`);">
                                                    <div class="card" style="background-image: url('https://machooosinternational.com/admin/<?= $card['image'] ?>'); background-size: cover; background-position: center;width: 100% !important;height: 100% !important;opacity: .3;" >
                                                        
                                                    
                                                            <div class="card-body" style="padding-top: 20px; !important">
                                                                   <div class="clearfix" style="text-align: right;">
                                                                           <b class=" float-right text-white"  style="padding-right: 27px;padding-top: 0px;color: white;"><?=strtoupper($userName)?></b>
                                                                  </div>
                                                                  <h4 class="card-title font-weight-normal text-warning" style="padding-top: 62px;padding-left: 65px;color: #FFC105 !important;font-weight: normal !important;font-size: 1.5rem;line-height: 1.1;text-align: right !important;
        padding-right: 40% !important;"><?=strtoupper($card['card_name'])?></h4>
                                                                  <h5 class="card-title font-weight-normal text-white displayCardNumber" style="color: white;font-weight: normal !important;margin-bottom: 0.75rem;font-size: 1.25rem;text-align: left !important;
        "><?=$purchaseCardNumber?></h5>
                                                                  <h6 class="card-title font-weight-normal text-white displayExpDate" style="color: white;text-align: left !important;padding-right: 30% !important;" ><?=strtoupper($exp)?></h6>
                                                                  
                                                                  
                                                                  
                                                             
                                                            </div>
                                                            
                                                            
                                                            
                                                             <!-- Add a centered button -->
                                                              <div style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%);">
                                                                 <img class="text-white" src="https://machooosinternational.com/dashboard/images/icons/lock-white.png" alt="" style="width: 30%;height: 30%;">
                                                              </div>
                                                             
                                                            
                                                            
                                                            
                                                            
                                                            
                                                    </div>
                                                  </div>
                                                  
                                            
                                            
                                            
                                            
                                            
                                            <?php } ?>
                                        
                                        
                                        
                                        <?php }else{ ?>
                                        
                                        
                                        
                                        
                                        
                                    
                                                 <div class="col-md-4" style="height: 208px;padding-top:5px;" onclick="showCardBenfits(<?=$card['id']?>,`<?=$decodeId?>`);">
                                                    <div class="card" style="background-image: url('https://machooosinternational.com/admin/<?= $card['image'] ?>'); background-size: cover; background-position: center;width: 100% !important;height: 100% !important;opacity: .3;" >
                                                        
                                                    
                                                            <div class="card-body" style="padding-top: 20px; !important">
                                                                   <div class="clearfix" style="text-align: right;">
                                                                           <b class=" float-right text-white"  style="padding-right: 27px;padding-top: 0px;color: white;"><?=strtoupper($userName)?></b>
                                                                  </div>
                                                                  <h4 class="card-title font-weight-normal text-warning" style="padding-top: 62px;padding-left: 65px;color: #FFC105 !important;font-weight: normal !important;font-size: 1.5rem;line-height: 1.1;text-align: right !important;
        padding-right: 40% !important;"><?=strtoupper($card['card_name'])?></h4>
                                                                  <h5 class="card-title font-weight-normal text-white displayCardNumber" style="color: white;font-weight: normal !important;margin-bottom: 0.75rem;font-size: 1.25rem;text-align: left !important;
        "><?=$purchaseCardNumber?></h5>
                                                                  <h6 class="card-title font-weight-normal text-white displayExpDate" style="color: white;text-align: left !important;padding-right: 30% !important;" ><?=strtoupper($exp)?></h6>
                                                                  
                                                                  
                                                                  
                                                             
                                                            </div>
                                                            
                                                            
                                                            
                                                             <!-- Add a centered button -->
                                                              <div style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%);">
                                                                 <img class="text-white" src="https://machooosinternational.com/dashboard/images/icons/lock-white.png" alt="" style="width: 30%;height: 30%;">
                                                              </div>
                                                             
                                                            
                                                            
                                                            
                                                            
                                                            
                                                    </div>
                                                  </div>
                                                  
                                                  
                                                  <?php } ?>
                                                  
                                        <?php } ?>
                                      
                                    </div>
                                    
                                <?php }else{ ?>
                                
                                
                                <?php } ?>
                                
                             </div>
                            
                            <div id="cardDetailsDiv" style="padding-top:10px;">
                                
                              
                                
                                <div id="cardDetailsDisplayDiv"></div>
                                
                                
                               
                            
                            
                            </div>
                        
                    
                                
                            <span class="fw-separator"></span>
                            
                            
                            
                            
                            <div class="clearfix"></div>
                            
                            
                            
                            <?php if(count($ordersData) > 0) { ?>
             
             
                <div class="row mb-2">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-body">
                          <h1 style="color: black;text-align: left;font-weight: normal !important;margin-bottom: 0.75rem;font-size: 1.25rem;">Transactions</h1>
                          <div class="table-responsive mb-2" style="overflow-x: auto;">
                            <table class="table center-aligned-table" >
                                
                              <thead>
                                <tr class="text-primary">
                                  <th>No</th>
                                  <th>Transaction ID</th>
                                  <th>Card Name</th>
                                  <th>Card Number</th>
                                  <th>Utilize</th>
                                  
                                  <th>Card Validity</th>
                                 
                                
                                  <th>Orginal Price</th>
                                  <th>Discount</th>
                                   <th>Coupon</th>
                                  <th>Created</th>
                                    <th>Expire</th>
                                    
                                  <th>Status</th>
                                  <th>Price</th>
                                  <th></th>
                                  <!--<th></th>-->
                                </tr>
                              </thead>
                              
                              <tbody>
                                  
                                  <?php 
                                      $i =0;
                                                foreach ($ordersData as $key => $album) { 
                                                    $i++;
                                                    $id = $album['id'];
                                                    $numberOfItemsPrice = $album['numberOfItemsPrice'];
                                                    $numberOfItemsDiscount = $album['numberOfItemsDiscount'];
                                                    $numberOfItemsTotalAmount = $album['numberOfItemsTotalAmount'];
                                                    $razorpay_payment_status = $album['razorpay_payment_status'];
                                                    
                                                    $couponApplyDiscount = $album['couponApplyDiscount'];
                                                    
                                                    $card_number = $album['card_number'];
                                                    $exp_date = $album['exp_date'];
                                                    
                                                    $num_services = $album['num_services'];
                                                    
                                                    
                                                    $card_type = $album['card_type'];
                                                    
                                                    if($card_type == 2) $crdtyd = '<label class="badge badge-success">upgraded</label>';
                                                    else if($card_type == 1) $crdtyd = '<label class="badge badge-primary">activated</label>';
                                                    else $crdtyd = '';
                                                    
                                                    
                                                    
                                                                 $planExpDate1 = new DateTime($exp_date);
            
                                                                // Get year, month, and day part from the date
                                                                $year1 = $planExpDate1->format('Y');
                                                                $month1 = $planExpDate1->format('n');
                                                                $day1 = $planExpDate1->format('d');
                                                                
                                                                // Assuming $monthNames is an array with month names
                                                                $monthNames1 = array(
                                                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
                                                                );
                                                                
                                                                $exp_date = $day1 . ' ' . $monthNames1[$month1 - 1] . ' ' . $year1;
                                                    
                                                    $newpurchaseID = $album['newpurchaseID'];
                                                    
                                                    $created_date = $album['created_date'];
                                                    
                                                    
                                                                 $planExpDate = new DateTime($created_date);
            
                                                                // Get year, month, and day part from the date
                                                                $year = $planExpDate->format('Y');
                                                                $month = $planExpDate->format('n');
                                                                $day = $planExpDate->format('d');
                                                                
                                                                // Assuming $monthNames is an array with month names
                                                                $monthNames = array(
                                                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
                                                                );
                                                                
                                                                $formattedExpDate = $day . ' ' . $monthNames[$month - 1] . ' ' . $year;
                                                                
                                                                
                                                                $timestamp = time();
                                                    		    $decodeId = base64_encode($timestamp . "_".$id);
                                                    		    $decodeId = str_rot13($decodeId);
                                                    		    
                                                    		    
                                                    		    $card_id = $album['card_id'];
                                                        		$sqlcard = "SELECT a.* FROM tbluser_cards a WHERE a.id='$card_id' ";
                                                        		$resultcard = $DBC->query($sqlcard);
                                                        		$rowcard = mysqli_fetch_assoc($resultcard);
                                                        		
                                                        		
                                                        		 $cardExpDate1 = $album['exp_date'];
                                                        		 $isNew = $album['isNew'];
                                                               // Convert the given date string to a DateTime object
                                                                $givenDate1 = new DateTime($cardExpDate1);
                                                                
                                                                // Get today's date as a DateTime object
                                                                $today1 = new DateTime();
                                                                
                                                                $isExpDay = '';
                                                                if($isNew == 1){
                                                                    if ($givenDate1 >= $today1) {
                                                                        
                                                                        // Define the target date (e.g., 2025-01-19)
                                                                        $targetDate = new DateTime($cardExpDate1);
                                                                        
                                                                        // Get the current date
                                                                        $currentDate = new DateTime();
                                                                        
                                                                        // Calculate the difference in days
                                                                        $interval = $currentDate->diff($targetDate);
                                                                        $numberOfDays = $interval->days;
                                                                        
                                                                        $isExpDay = '<label class=" text-success">'.$numberOfDays.' days</label>';
                                                                    } else {
                                                                        $isExpDay = '<label class=" text-danger">Expired</label>';
                                                                    }
                                                                }else{
                                                                    $isExpDay = '<label class=" text-danger">Deactivated</label>';
                                                                }
                                                                
                                                                
                                                        		
                                                        		
                                                    		    
                                                    		    
                                                    		    
                                                    		    
                                                                
                                            ?>
                                  
                                  
                                                <tr class="">
                                                  <td><?=$i?></td>
                                                  <td>#<?=$newpurchaseID?></td>
                                                  
                                                  <td><?=$rowcard['card_name']?> <?=$crdtyd?></td>
                                                  <td><?=$card_number?></td>
                                                  <td><?=$num_services?> Services</td>
                                                  <td><?=$isExpDay?></td>
                                                  
                                                 
                                                  
                                                  
                                                  <td>₹<?=$numberOfItemsPrice?></td>
                                                  <td>-₹<?=$numberOfItemsDiscount?></td>
                                                  <td>-₹<?=$couponApplyDiscount?></td>
                                                  <td><?=$formattedExpDate?></td>
                                                   <td><?=$exp_date?></td>
                                                   
                                                  
                                                  
                                                  <?php if($razorpay_payment_status == 1){ ?>
                                                        <td><label class="badge badge-success" style="color:green">Success</label></td>
                                                          <td>₹<?=$numberOfItemsTotalAmount?></td>
                                                          <td><a onclick="printNow(<?=$id?>);" role="button" class="text-primary" style="color:blue">
                                                             Print
                                                          </a></td>
                                                          <!--<td><a onclick="downloadNow(<?=$id?>,`<?=$newpurchaseID?>`);" role="button" class="text-primary" style="color:blue">-->
                                                          <!--    Download-->
                                                          <!--</a></td>-->
                                                      
                                                  <?php }else{ ?>
                                                        <td><label class="badge badge-danger" style="color:red">Failed</label></td>
                                                          <td>₹<?=$numberOfItemsTotalAmount?></td>
                                                          <td></td>
                                                          <!--<td></td>-->
                                                  <?php } ?>
                                                  
                                                
                                                </tr>
                                            
                                            <?php } 
                                        
                                     ?>
                                       
                               
                              </tbody>
                            </table>
                          </div>
                          
                          
                          
                        </div>
                      </div>
                    </div>
                  </div>
             
                
            <?php } ?>
                            
                            
                            
                            
                            
                            
                            
                            
                                  
                            <span class="fw-separator"></span>
                            
                            
                            
                            
                            <div class="clearfix"></div>
                            
                            
                            
                            
                            
                            
                            <!-- process-wrap-->
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- process-item-->
                                    <div class="process-item big-pad-pr-item">
                                        <span class="process-count"> </span>
                                        <div class="time-line-icon"><i class="fal fa-headset"></i></div>
                                        <h4><a href="#"> Best service guarantee</a></h4>
                                        <p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.</p>
                                    </div>
                                    <!-- process-item end -->
                                </div>
                                <div class="col-md-4">
                                    <!-- process-item-->
                                    <div class="process-item big-pad-pr-item">
                                        <span class="process-count"> </span>
                                        <div class="time-line-icon"><i class="fal fa-gift"></i></div>
                                        <h4> <a href="#">Exclusive gifts</a></h4>
                                        <p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.</p>
                                    </div>
                                    <!-- process-item end -->                                
                                </div>
                                <div class="col-md-4">
                                    <!-- process-item-->
                                    <div class="process-item big-pad-pr-item nodecpre">
                                        <span class="process-count"> </span>
                                        <div class="time-line-icon"><i class="fal fa-credit-card"></i></div>
                                        <h4><a href="#"> Get more from your card</a></h4>
                                        <p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.</p>
                                    </div>
                                    <!-- process-item end -->                                
                                </div>
                            </div>
                            <!--process-wrap   end-->
                        </div>
                        
                    
                        
                        <div class="section-decor"></div>
                        
                        
                        
                        
                        
                    </section>
                    <!-- section end -->
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
            
            
            
            <iframe id="printFrame" style="display: none;" title="CustomFileName"></iframe> 
      
       
            
            
            
<?php 

include("templates/footer.php");

?>


<script>

    $(document).ready(function() {
        $('#card-menu').addClass('act-link');
    });



  var purchaseNowId = '';
    var purchaseCardType = 0;
    var purchaseCardNumber = '';
    
    var totalItemPrice = 0;
    var ItemDiscount = 0;
    var ItemTotalAmount = 0;
    var Itemsave = 0;
    var cardValid = 0;
    
    var numberOfServicesUse = 0;
    
    
     function purchaseNow(){
        
        var user_id = '<?=$user_id?>';
        var stateName = '<?=$stateName?>';
        var isSte = 0;
        if(stateName == 'Kerala' || stateName == 'kerala') isSte = 1;
        
      
   
        var postData = {
            function: 'Services',
            method: "userCardPlaceOrderNow",
            'purchaseNowId': purchaseNowId,
            'user_id':user_id,
            'totalItemPrice': totalItemPrice,
            'ItemDiscount': ItemDiscount,
            'ItemTotalAmount': ItemTotalAmount,
            'Itemsave': Itemsave,
            'exp':cardValid,
            'CN':purchaseCardNumber,
            'purchaseCardType':purchaseCardType,
            'CardServices':numberOfServicesUse,
            'isSte':isSte,
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
                window.location.href = 'https://machooosinternational.com/dashboard/purchase-user-card.php?purchaseID='+data.data;
                
                // window.open('https://machooosinternational.com/dashboard/purchase-user-card.php?purchaseID=' + data.data, '_blank');

           
            }
           
        },
        error: function (x,h,r) {
        //called when there is an error
            console.log(x);
            console.log(h);
            console.log(r);
           
        }
    });
        
        
        
        
        
        
        // window.location.href = 'place-order.php?purchaseID='+purchaseNowId;
    }
    
   
    
    
    function showCardBenfits(cardID,purchaseId){
        
        $('#cardDetailsDisplayDiv').html('');
        $('#cardNameDis').html('');
        
        purchaseNowId = purchaseId;
        
        var activeCardName = '<?=$activeCardName?>';
      
         var postData = {
            function: 'Services',
            method: "getCardBenfits",
            cardId: cardID,
           
          }
      
        $.ajax({
            url: '/admin/ajaxHandler.php',
            type: 'POST',
            data: postData,
            dataType: "json",
            success: function (data) {
                console.log(data);
                // console.log(data.status);
                //called when successful
                if (data.status == 1) {
                    var cardDetails = data.data;
                //   alert(cardDetails[0]['card_name']);
                
                // $('#cardNameDis').html(cardDetails[0]['card_name']);
                
                 var amount = data.data[0]['amount'];
                var discout = data.data[0]['discout'];
                var discout_type = data.data[0]['discout_type'];
                var exp = data.data[0]['exp'];
                
                var displayData = '';
                
                cardValid = exp;
                        
                    totalItemPrice = amount;
                    
                    var displayData = '';
                    
                    
                    displayData +='<div class="card-post-content fl-wrap">';
                        displayData +='<h3>'+cardDetails[0]["card_name"]+'</h3>';
                        // displayData +='<h3>Card valid for <b>'+exp+' year</h3>';
                        displayData +='<p>'+data.data[0]['description']+'</p>';
                        
                            displayData +='<div class="row">';
                            
                                displayData +='<div class="col-md-2" style="padding-left: 0px;padding-right: 0px;">';
                                    displayData +='<div >';
                                        displayData +='<div class="time-line-icon"><i class="fa fa-bed"></i></div>';
                                        displayData +='<h4> NUMBER OF SERVICE : '+data.data[0]['number_of_service']+' </h4>';
                                    displayData +='</div>';
                                displayData +='</div>';
                                
                                 displayData +='<div class="col-md-2" style="padding-left: 0px;padding-right: 0px;">';
                                    displayData +='<div >';
                                        displayData +='<div class="time-line-icon"><i class="fa fa-calendar"></i></div>';
                                        displayData +='<h4>VALIDITY : '+exp+' YEAR</h4>';
                                    displayData +='</div>';
                                displayData +='</div>';
                              
                               
                            displayData +='</div>';
                            
                            

                        
                        
                        
                       displayData +='<h3>Price details</h3>';
                       
                       
                    numberOfServicesUse = data.data[0]['number_of_service'];
                       
                    
                    var payablePrice = 0;
                    if(discout_type == 1){
                        ItemDiscount = parseInt(discout);
                        payablePrice = parseInt(amount) - parseInt(discout);
                    }else{
                        ItemDiscount = ( ( parseInt(amount) / 100 ) * parseInt(discout) ).toFixed(2) ;
                        payablePrice = (parseInt(amount) - ( ( parseInt(amount) / 100 ) * parseInt(discout) )).toFixed(2) ;
                    }
                    
                    ItemTotalAmount = payablePrice;
                    Itemsave = ItemDiscount;
                    
                
                    displayData +='<h1 style="float: left;width: 100%;text-align: left;color: #666;font-size: 16px;font-weight: 700;">₹ '+payablePrice+' / <label class="dollar" style="font-size: 10px;font-weight: blod;"><del>₹ '+amount+'</del><h1>';

                       
                       if(discout_type == 1) displayData +='<h1 style="color:green;">You save ₹'+discout+' on this card </h1>';
                    else displayData +='<h1 style="color:green;">You save '+discout+'% off on this card</h1>';
                    
                    if(activeCardName != "") displayData +='<h1 style="color:red;padding-top:5px;">CURRENTLY USING '+activeCardName+' CARD</h1>';
                    
                 
                    
                    
                    displayData +='<div style="float: right;width: 100%;">';
                    
                    displayData +='<button class="btn float-btn " onclick="purchaseNow();" style="margin-top:15px;margin-bottom:15px;background: green;border: none;margin-right:5px;" >Purchase Now<i class="fal fa-angle-right"></i></button>';
                    
                     displayData +='<button onclick="cancelCard();" class="btn float-btn " style="margin-top:15px;margin-bottom:15px;background: red;border: none;" >Cancel<i class="fa fa-reply"></i></button>';
                    
                    displayData +='</div>';
                    
                    
                    displayData +='</div>';
                
                
                   
                //   $('#cardDisplayDiv').addClass('hide');
                  $('#cardDetailsDiv').removeClass('hide');
                   
                   $('#cardDetailsDisplayDiv').html(displayData);
                    
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
    
    function cancelCard(){
        // $('#cardDisplayDiv').removeClass('hide');
        $('#cardDetailsDiv').addClass('hide');
    }
    
   
     function printNow(id){
        const iframe = document.getElementById("printFrame");
        iframe.src = "/dwd-mifuto-card-invoice.php?purchaseID="+id;
     
        iframe.onload = function() {
            // Wait for the iframe to load, then trigger the print dialog
            iframe.contentWindow.print();
        };
    }
    
    function downloadNow(id,newpurchaseID){
         const iframe = document.getElementById("printFrame");
            iframe.src = "/dwd-mifuto-card-invoice-pdf.php?purchaseID="+id;
            
            iframe.onload = function() {
                const content = iframe.contentDocument.body;
                
                
                const options = {
                    margin: 1,
                    filename: 'Invoice_'+newpurchaseID+'.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                };
                
               
                // // New Promise-based usage:
                // html2pdf().set(options).from(content).save();
                
                // Old monolithic-style usage:
                html2pdf(content, options);
               
             
            };
    }
  

    </script>