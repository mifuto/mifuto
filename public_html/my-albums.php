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

$AlbumsList = [];

 
$sql3 = "SELECT a.* FROM tbeeventalbum_data a left join place_order_userservices b on a.project_id = b.id where a.deleted=0 and b.user_id=".$user_id." and b.service_status =4 order by a.id desc "; 

$result3 = $DBC->query($sql3);

$count3 = mysqli_num_rows($result3);

if($count3 > 0) {		
    while ($row3 = mysqli_fetch_assoc($result3)) {
        array_push($AlbumsList,$row3);
      
    }
}




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
                        <div class="bg par-elem "  data-bg="/images/bg/1.jpg" data-scrollax="properties: { translateY: '30%' }"></div>
                        <div class="overlay"></div>
                        <div class="container">
                            <div class="section-title center-align big-title">
                                <div class="section-title-separator"><span></span></div>
                                <h2><span>My Albums</span></h2>
                                <span class="section-separator"></span>
                                <h4>A collection of your favorite memories and moments, beautifully organized in one place.</h4>
                            </div>
                        </div>
                        <div class="header-sec-link">
                            <div class="container"><a href="#sec1" class="custom-scroll-link color-bg"><i class="fal fa-angle-double-down"></i></a></div>
                        </div>
                    </section>
                    <!--  section  end-->
                    <div class="breadcrumbs-fs fl-wrap">
                        <div class="container">
                            <div class="breadcrumbs fl-wrap"><a href="#">Home</a><a href="#">Cards</a><span>My Albums</span></div>
                        </div>
                    </div>
                    
                    
                   
                    
                    
                    
                     <!--section -->
                    <section class="grey-blue-bg small-padding" id="sec1">
                        <div class="container">
                            <div class="row">
                                <!--listing -->
                                <div class="col-md-12">
                                    <div class="mobile-list-controls fl-wrap mar-bot-cont">
                                        <div class="mlc show-list-wrap-search fl-wrap"><i class="fal fa-filter"></i> Filter</div>
                                    </div>
                                    
                                    <?php if(count($AlbumsList) > 0) { ?>
                                    
                                    <!--col-list-wrap -->
                                    <div class="col-list-wrap fw-col-list-wrap">
                                        <!-- list-main-wrap-->
                                        <div class="list-main-wrap fl-wrap card-listing">
                                            <!-- list-main-wrap-opt-->
                                            <div class="list-main-wrap-opt fl-wrap">
                                               
                                                <!-- price-opt-->
                                                <div class="grid-opt">
                                                    <ul>
                                                        <li><span class="two-col-grid act-grid-opt"><i class="fas fa-th-large"></i></span></li>
                                                        <li><span class="one-col-grid"><i class="fas fa-bars"></i></span></li>
                                                    </ul>
                                                </div>
                                                <!-- price-opt end-->                               
                                            </div>
                                            <!-- list-main-wrap-opt end-->
                                            <!-- listing-item-container -->
                                            <div class="listing-item-container init-grid-items fl-wrap three-columns-grid">
                                                
                                                
                                                <?php
                                         
                                          
                                                    foreach ($AlbumsList as $key => $album) { 
                                                        
                                                        $purchaseID = $album['project_id'];
                                                        
                                                        
                                                         $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
                                                    	$cardData1r = $DBC->query($psql);
                                                    	$cardData1 = mysqli_fetch_assoc($cardData1r);
                                                    		
                                                		$decodedKey = $cardData1['inpServiceID'];
                                                		
                                                	    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.machoose_user_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
                                                		$cardData = $DBC->query($psql1);
                                                		
                                                		$service = mysqli_fetch_assoc($cardData);
                                                		
                                                		
                                                    $time = $cardData1['inpEventTime'];
                                                     $time = new DateTime($time);
                                                    $amPmTime = $time->format('h:i A');
                                                    
                                                     $ctime = new DateTime($cardData1['created_date']);
                                                    $camPmTime = $ctime->format('Y-m-d h:i A');
                                                    
                                                    $eventID = $album['id'];
                                                    
                                                     $timestamp = time();
                                            		    $decodeId = base64_encode($timestamp . "_".$eventID);
                                            		    $decodeId = str_rot13($decodeId);
                                                    
                                                 
                                            	
                                                ?>
                                                
                                                
                                                <!-- listing-item  -->
                                                <div class="listing-item" >
                                                    <article class="geodir-category-listing fl-wrap">
                                                        <div class="geodir-category-img">
                                                            <a href="view-album.php?eventID=<?=$decodeId?>" target="_blank"><img src="<?=$album['cover_image_path']?>" alt=""></a>
                                                         
                                                          
                                                        </div>
                                                        <div class="geodir-category-content fl-wrap title-sin_item">
                                                            <div class="geodir-category-content-title fl-wrap">
                                                                <div class="geodir-category-content-title-item">
                                                                    <h3 class="title-sin_map"><?=$album['folder_name']?></h3>
                                                                    <div class="geodir-category-location fl-wrap"><a href="#" class="map-item"><i class="fas fa-map-marker-alt"></i> <?=$service['company_name']?> - <?=$service['company_address']?> </a></div>
                                                                
                                                                    
                                                                </div>
                                                            </div>
                                                            <p><?=$service['name']?> - <?=$cardData1['inpEventDate']?> <?=$amPmTime?></p>

                                                        </div>
                                                    </article>
                                                </div>
                                                <!-- listing-item end -->
                                                
                                           <?php } ?>
                                              
                                                                    
                                            </div>
                                            <!-- listing-item-container end-->
                                           
                                        </div>
                                        <!-- list-main-wrap end-->
                                    </div>
                                    <!--col-list-wrap end -->
                                    
                                    <?php }else{ ?>
                                        <h3 style="color:red;">You have no albums available.</h3>
                                        <p style="color:red;">Create and organize your favorite memories and moments in albums.</p>
                                
                                
                                    <?php } ?>
                                    
                                    
                                    
                                </div>
                                <!--listing  end-->
                            </div>
                            <!--row end-->
                        </div>
                    </section>
                    <!--section end -->
                    
                  
             
            
                </div>
                <!-- content end-->
            </div>
            <!--wrapper end -->
            
      
<?php 

include("templates/footer.php");

?>


<script>

    $(document).ready(function() {
        $('#album-menu').addClass('act-link');
    });
    
   


    </script>