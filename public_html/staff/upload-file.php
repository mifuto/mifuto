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
 
 $sqlcart = "SELECT a.* FROM place_order_userservices a left join tblprovider_services ins on a.inpServiceID = ins.id left join tblproviderusercompany b on b.id=ins.main_id left join tblmifutostaffuserlogin s on s.id=b.machoose_user_id WHERE a.photographerID='".$user_id."' and a.newpurchaseID !='' and a.service_status =3 order by a.inpEventDate asc";


$resultcart = $DBC->query($sqlcart);
$countcart = mysqli_num_rows($resultcart);



if($countcart > 0) {		
    while ($rowcart = mysqli_fetch_assoc($resultcart)) {
        array_push($ordersData,$rowcart);
    }
}


// include("header.php");

?>

<link href="/admin/assets/css/imageuploadify.min.css" rel="stylesheet"></link>


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Upload Files</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Upload Files</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
     <!-- Main content -->
    <section class="content" id="listEvents">
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
                        
                        if ($album['inpEventDate'] === $todayDate) {
                            $todayShoot = true;
                        }
                        
                        
                        $psql22 = "SELECT * FROM tbeeventalbum_data WHERE deleted=0 and project_id = $purchaseID ";
                    	$eventData = $DBC->query($psql22);
                    	$eventDataCount = mysqli_num_rows($eventData);
                    
                        
                        ?>
                        
                        
                        <div class="row pt-2" >
                            <div class="col-12 d-flex align-items-stretch flex-column">
                                <div class="card bg-light d-flex flex-fill">
                                   <div class="card-body pt-4">
                                       <div class="row" onclick="showFileUpload(<?=$purchaseID?>);">
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
                                                
                                            
                                               
                                               
                                            </div>
                                            <div class="col-2 text-center pt-4">
                                                <img src="<?=$service['company_logo_url']?>" alt="" class="img-circle img-fluid">
                                                
                                            </div>
                                            
                                           
                                            
                                        </div>
                                        
                                         <?php if($eventDataCount > 0 ){ ?>
                                         
                                         <div class="row" >
                                            
                                             <div class="col-3 text-center pt-4">
                                                 
                                                 <a class="btn btn-info btn-block" onclick="showFileUpload(<?=$purchaseID?>);"><b>View Events</b></a>
                                                 
                                                 
                                                 </div>
                                                  <div class="col-3 text-center pt-4">
                                                 
                                                 <a class="btn btn-success btn-block" onclick="completeFileUpload(<?=$purchaseID?>);"><b>Upload Completed</b></a>
                                                 
                                                 
                                                 </div>
                                        </div>
                                                
                                        <?php } ?>
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        
                        
                     
                
                <?php } 
            
         ?>
            
            <?php }else{ ?>
            <div class="dashboard-list">
            
                     <div class="dashboard-message-text">
                         <h4 style="color:red;">Shoot Complete Services Unavailable </h4>
                         <p>Shoot complete services are currently unavailable. Please wait for an available service.</p>
                         
                         </div>
                         
             </div>
            
            
            <?php } ?>
                                        
          
          
          
          
          
          
             
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    
    
    
     <div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"  >
        <div class="modal-header">
          <h5 class="modal-title">Create Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form id="createSigAlbmEventForm" class="g-3 needs-validation" novalidate="">
            <div class="modal-body" >
              <div class="row mb-3 mt-4">
                <label for="sigAlbmEventName" class="col-sm-3 col-form-label">Event Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="sigAlbmEventName" name="sigAlbmEventName" required>
                  <div class="text-danger" id="sigAlbmEventNameErr">Plese enter the event name!.</div>
                 
                </div>
              </div>

              <div class="row mb-3" style="padding-left: 10px;padding-right: 10px;">
                <label for="EventCoverImgFile" class="col-form-label" style="padding-left: 0;">Cover Image</label>
                <input type="file" id="EventCoverImgFile" name="EventCoverImgFile[]" accept="image/*" multiple>
                <div class="text-danger" id="EventCoverImgFilerr"></div>
              </div>

              <div class="row mb-3 d-none" style="padding-left: 10px;padding-right: 10px;">
                <label for="EventCoverImgFile" class="col-form-label" style="padding-left: 0;">Event Images</label>
                <input type="file" id="signatureAlbumEventFiles" name="signatureAlbumEventFiles[]" accept="image/*" multiple>
                <div class="text-danger" id="signatureAlbumEventFilesErr"></div>
              </div>
              <div class="progress mt-3">
                <div class="progress-bar progress-bar-striped bg-danger d-none" id="signalbmEventUploadStatus" role="progressbar" style="width: 50%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <div id="uploadStatus"></div>
            </div>
            <div class="modal-footer">
           
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="createEventSubmit">Create</button>
              <button class="btn btn-primary d-none" type="button" id="createEventSubmitLoadingButton" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Please wait...
              </button>
            </div>
        </form>
      </div>
    </div>
  </div>
    
    
    
    
    <section class="content d-none" id="showEventsUpload">
      <div class="container-fluid">
          
          
          <div class="row pt-2">
                <div class="col-12 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill">
                       <div class="card-body pt-4">
                           <div class="row">
                               
                               <div class="col-12 pt-2" align="right">
                                    <button  class="btn btn-success " type="button" onclick="showUploadModal();"> Create new event</button>
                                    <button  class="btn btn-danger " type="button" onclick="cancelUpload();"> Cancel</button>
                                </div>
                               
                               
                               
                               
                               <div class="col-12" id="listAllEvents"> 
                               
                               
                               </div>
                            </div>
                        </div>
                    </div>
                     
                </div>
          
           </div>
          
          
          
          
      </div>
      </section>
      
      
      
      
      <section class="content d-none" id="showEventsImageUpload">
      <div class="container-fluid">
          
          
          <div class="row pt-2">
                <div class="col-12 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill">
                       <div class="card-body pt-4">
                           <div class="row">
                               
                               <div class="col-12 pt-2" align="right">
                                   <button  class="btn btn-primary " type="button" onclick="cancelImageUpload();"> Back</button>
                                    <button  class="btn btn-success " type="button" onclick="showUploadEventImageModal();"> Upload Images</button>
                                    <button  class="btn btn-info " type="button" data-toggle="modal" data-target="#modal-default" onclick="uploadImageStart();"> Upload Vedio</button>
                                    

                                </div>
                               
                               
                               
                               
                               <div class="col-12" id="listAllEventsImages"> 
                               
                               
                               </div>
                            </div>
                        </div>
                    </div>
                     
                </div>
          
           </div>
          
          
          
          
      </div>
      </section>
    
    
      
  </div>
  <!-- /.content-wrapper -->
  
  
  
  
  
   <div class="modal fade" id="uploadImageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"  >
        <div class="modal-header">
          <h5 class="modal-title">Upload Images</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form id="uploadSigExtrafilesForm" class="g-3 needs-validation" novalidate="">
            <div class="modal-body" >
              <div class="row mb-3 mt-4">
                <label for="sigAlbmEventName" class="col-sm-3 col-form-label">Event Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="uploadsigAlbmFolderName" name="uploadsigAlbmFolderName" value="" disabled>
                  <div class="invalid-feedback">
                    Plese enter the event name!.
                  </div>
                </div>
              </div>
              
              
               <div class="row mb-3" style="padding-left: 10px;padding-right: 10px;">
                <label for="EventCoverImgFile" class="col-form-label" style="padding-left: 0;">Images</label>
                <input type="file" class="custom-file-input" id="uploadSignatureAlbumFiles" name="uploadSignatureAlbumFiles[]" accept="image/*" multiple>
                <div class="text-danger" id="uploadSignatureAlbumFilesErr"></div>
              </div>
              
               <hr>
                <h5 id="disUploadImgTitle">Uploaded images</h5>
                <div class="mt-3" id="imageList"></div>
              
            
              
              
              <div class="progress mt-3">
                <!-- Update the ID to match the selector used in the JavaScript -->
                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
              <div id="uploadMoreStatus"></div>
            </div>
            <div class="modal-footer">
                <h5 id="disUploadImgTitlenew" style="flex: auto;"></h5>
              <input type="hidden" id="selectedUplSigfile_folder" name="selectedUplSigfile_folder" value="">
            
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="uplSigFilesSubmit" onclick="uploadMultipleImg();">Upload Image</button>
              <button type="button" class="btn btn-primary d-none" id="rUplSigFilesSubmit" onclick="reloadUploadMultipleImg();" >Try again</button>
              <button class="btn btn-primary d-none" type="button" id="uplSigFilesLoadingButton" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Please wait...
              </button>

            </div>
        </form>
      </div>
    </div>
  </div>
  
  
  
  
   <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Upload Vedio</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form id="uploadCompanyLogoForm" class="g-3 needs-validation" novalidate="">
                
                
                <div class="modal-body">
                    
                       <div class="row mb-3" style="padding-left: 10px;padding-right: 10px;">
                    <label for="EventCoverImgFile" class="col-form-label" style="padding-left: 0;">Upload Vedio</label>
                    <input type="file" class="custom-file-input" id="uploadLogoFiles" name="uploadLogoFiles[]" accept="video/*" multiple>
                    <div class="text-danger" id="uploadLogoFilesErr"></div>
                  </div>
                    
                    
                    <!--<div class="container ">-->
                    <!--    <div class="card p-4">-->
                    <!--        <div class="custom-file">-->
                    <!--            <strong>Upload Vedio<br>-->
                    <!--            <input type="file"  id="uploadLogoFiles" name="uploadLogoFiles[]" accept="video/*" multiple>-->
                                <!--<label class="custom-file-label" for="imageUploader">Upload logo (image size 300x48px)</label>-->
                    <!--            <div class="text-danger" id="uploadLogoFilesErr"></div>-->
                                
                    <!--        </div>-->
                    <!--        <br>-->
                    <!--    </div>-->
                       
                    <!--</div>-->
                    
                    <div class="progress mt-3">
                        <!-- Update the ID to match the selector used in the JavaScript -->
                        <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar12" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
            
                  
                  
                  
                  
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  
                  
                  <button type="button" class="btn btn-primary" id="submitButton13" onclick="showUploadEventVedioModal();">Upload Vedio</button>
                  <button class="btn btn-primary d-none" type="button" id="submitLoadingButton13" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Please wait...
                  </button>
                  
                  
                  
                </div>
                
                </form>
                
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        
        
        
        
        

  
  
  
        
  
  
<?php 

include("footer.php");



?>

 <script src="/admin/assets/js/imageuploadify.min.js"></script>

<script>
    $('#navDashboard').removeClass('active');
    $('#navOurCompanies').removeClass('active');
    $('#navOurServices').removeClass('active');
    $('#navProfile').removeClass('active');
    $('#navBookings').removeClass('active');
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
    $('#navFiles').addClass('active');
    
    var selEventId = '';
    var selCoverId = '';
    
    var folder_name_val = '';
    var file_folder = '';
    
    
    var totalImgUpload = 0;
var uploadInProgress = false;

 
var uploadImg = 0;
var succImg = 0;
    
    
    
    
    $( document ).ready(function() {
        
        $('#EventCoverImgFile').imageuploadify();
        $('#uploadSignatureAlbumFiles').imageuploadify();
        $('#uploadLogoFiles').imageuploadify();
        
        
   
  });
  
  
  function completeFileUpload(id){
      
       return new swal({
        title: "Are you sure?",
        text: "Do you want to complete this upload",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                 successFn = function(resp)  {
                     
                     if(resp.status == 1){
                         
                          Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Successfully completed uploading",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            location.reload();

                         
                     }else{
                         
                         Swal.fire({
                                        icon: 'error',
                                        title: "Failed to complete uploading",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                         
                     }
                     
                 }
                data = { "function": 'SystemManage',"method": "completeFileUpload","selEventId":id };
                
                apiCallForProvider(data,successFn);
                
                
                
            }
        });
      
  }
  
  
  
  
  
  function uploadImageStart(){
       $("#submitButton13").removeClass("d-none");
        $("#submitLoadingButton13").addClass("d-none");
                        
        $('.ri-close-circle-line').click();
        
        
     var progressBar = document.getElementById("progress-bar12");
        
    // Set the width of the progress bar to 0%
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
  }
  
  
  
  function reloadUploadMultipleImg(){
     return new swal({
        title: "confirmation of intent",
        text: "Would you like to continue?",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                uploadMultipleImg();
            }else{
                return false;
            }
        });
}


function uploadMultipleImg(){
    
    $("#uplSigFilesSubmit").addClass("d-none");
    $("#uplSigFilesLoadingButton").removeClass("d-none");
    totalImgUpload = 0;
    
    var selectedUplSigAlbmId = selCoverId;
    
  
    
    var files = document.getElementById("uploadSignatureAlbumFiles").files;
    
     

    if (files.length > 0) {
        
      
        successFn = function(resp)  {
            // console.log("rrerere");
            console.log(resp.data);
            var imgArr = resp.data;
            
            var imageList = $('#imageList');
            imageList.html('');
            
            totalImgUpload = files.length; 
            
            $('#disUploadImgTitlenew').html('Uploading images - Total ( '+totalImgUpload+' ) images');
            $('#rUplSigFilesSubmit').removeClass('d-none');
            
             
            
            uploadImg = 0;
            succImg = 0;
            uploadInProgress = false;
            uploadImages(files,imgArr,0);
            
           
        }
        errorFn = function(resp){
            console.log(resp);
            
            Swal.fire({
                icon: 'error',
                title: "Failed to save event",
                showConfirmButton: false,
                timer: 1500
            });
            $("#uplSigFilesSubmit").removeClass("d-none");
            $("#uplSigFilesLoadingButton").addClass("d-none");
         
            return false;
        }
    
        data = { "function": 'SystemManage',"method": "fetchAllUploadImage", 'selectedUplSigAlbmId': selectedUplSigAlbmId };
        apiCallForProvider(data,successFn,errorFn);
    
        
        
    }else{
        $("#uploadSignatureAlbumFilesErr").html("Plese upload the event images!.");
        $("#uplSigFilesSubmit").removeClass("d-none");
        $("#uplSigFilesLoadingButton").addClass("d-none");
        return false;
    }
    
    
}

function uploadImages(files,imgArr,index = 0) {
    console.log(index+".  "+uploadInProgress)
    

    
    var folderName = $("#uploadsigAlbmFolderName").val();
    var selectedUplSigfile_folder = $("#selectedUplSigfile_folder").val();
    var selectedUplSigAlbmId = selCoverId;
   

    for (var i = index ; i < files.length; i++) {
        
          
        if(uploadInProgress){
            
            // setTimeout(function () {
            //     uploadImages(files,imgArr,index);
            // }, 50000);
           
        }else{
            
                let file = files[i];
                let formData = new FormData();
              
                // if(imgArr.length == 0) var exists = true;
                // else 
                let exists = imgArr.some(imgArr => imgArr.file_name === file['name']);
                
                console.log(exists);
        
                
                if (!exists) {
                    
                    uploadInProgress = true;
                    var fuCalbk = parseInt(i);
                     
                    formData.append('images[]', file);
                
                  
                    formData.append('folderName', folderName);
                    formData.append('selectedUplSigfile_folder', selectedUplSigfile_folder);
                    formData.append('selectedUplSigAlbmId', selectedUplSigAlbmId);
                  
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        
                       
                        
                        console.log('ajax call');
                       
                       
                        // Upload the image using AJAX
                        $.ajax({
                            xhr: function() {
                              var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = ((evt.loaded / evt.total) * 100);
                                        // Update the ID in the selector to match the HTML element ID
                                        $("#progress-bar").width(percentComplete.toFixed(0) + '%');
                                        $("#progress-bar").html(percentComplete.toFixed(0) + '%');
                                    }
                                }, false);
                                return xhr;
                            },
                            url: '/admin/uploadEventAlbumImage.php', // Replace with your PHP upload script
                            type: 'POST',
                            beforeSend: function(){
                                $("#progress-bar").width('0%');
                                // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                                $('#signalbmEventUploadStatus').removeClass('d-none');
                            },
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                             
                                
                                // Handle the response (e.g., save the image URL)
                                var imgElement = $('<img class="img-thumbnail" style="max-width: 5% !important;">');
                                imgElement.attr('src', e.target.result);
                                $('#imageList').append(imgElement);
                                uploadImg ++;
                                succImg ++;
                                
                                $('#disUploadImgTitlenew').html('Uploading images - Total ( '+uploadImg+' of '+totalImgUpload+' - Uploaded ) images');
                                
                              
                                if(totalImgUpload == succImg){
                                    
                                      
                                    
                                        Swal.fire({
                                            icon: 'success',
                                            // title: resp.data,
                                            title: "Image upload successfully completed",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
            
                                        // $('#uploadForm')[0].reset();
                                      $("#uploadImageModal").modal('hide');
                                        $("#uploadsigAlbmFolderName").val("");
                                        $("#uploadSignatureAlbumFiles").val("");   
                                        $("#uplSigFilesSubmit").removeClass("d-none");
                                        $("#uplSigFilesLoadingButton").addClass("d-none");
                                        
                                      console.log('succ');
                                        // getSignatureALbumList();
                                        
                                        showUploadImageModal(selCoverId,folderName,selectedUplSigfile_folder)
                                        
                                        
                                        
                                        
                                    
                                }else if(totalImgUpload == uploadImg){
                                      uploadMultipleImg();
                                 }
                                 
                                 uploadInProgress = false;
                                uploadImages(files,imgArr,fuCalbk+1);
                                    
                                
            
                                console.log('Image uploaded:', response);
                            },
                            error: function () {
                                console.error('Error uploading image');
                              uploadImg ++;
                              $('#disUploadImgTitlenew').html('Uploading images - Total ( '+uploadImg+' of '+totalImgUpload+' - Pending ) images');
                              if(totalImgUpload == uploadImg){
                                  uploadMultipleImg();
                              }
                              
                              uploadInProgress = false;
                                uploadImages(files,imgArr,fuCalbk+1);
                              
                              
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                } else {
                    uploadImg ++;
                    succImg ++;
                    
                    $('#disUploadImgTitlenew').html('Uploading images - Total ( '+uploadImg+' of '+totalImgUpload+' - Duplicate ) images');
                    
                    
                    
                      if(totalImgUpload == succImg){
                          Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Image upload successfully completed",
                                showConfirmButton: false,
                                timer: 1500
                            });
                
                            // $('#uploadForm')[0].reset();
                          $("#uploadImageModal").modal('hide');
                            $("#uploadsigAlbmFolderName").val("");
                            $("#uploadSignatureAlbumFiles").val("");   
                            $("#uplSigFilesSubmit").removeClass("d-none");
                            $("#uplSigFilesLoadingButton").addClass("d-none");
                            
                          console.log('succ');
                            // getSignatureALbumList();
                            
                             showUploadImageModal(selCoverId,folderName,selectedUplSigfile_folder)
                            
                            
                            
                            
                      }else if(totalImgUpload == uploadImg){
                          uploadMultipleImg();
                      }
                      
                  
                      
                }
                 
        }
        
        
    }
}




// Function to compress an image using canvas
function compressImage(file, maxWidth, maxHeight, quality) {
    console.log(file.name);
    
    return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            var img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                var canvas = document.createElement("canvas");
                var ctx = canvas.getContext("2d");
                let newWidth = img.width;
                let newHeight = img.height;

                if (img.width > maxWidth) {
                    newWidth = maxWidth;
                    newHeight = (img.height * maxWidth) / img.width;
                }

                if (newHeight > maxHeight) {
                    newHeight = maxHeight;
                    newWidth = (img.width * maxHeight) / img.height;
                }

                canvas.width = newWidth;
                canvas.height = newHeight;
                ctx.drawImage(img, 0, 0, newWidth, newHeight);

                // Convert the canvas to a compressed data URL
                canvas.toBlob(
                    (blob) => {
                         const compressedBlob = new Blob([blob], { type: file.type });
                        compressedBlob.name = "qwertyuio." + file.name.split(".").pop();
                        resolve(compressedBlob);
                    },
                    file.type,
                    quality
                );
            };
        };
        reader.onerror = (error) => {
            reject(error);
        };
    });
}

  
  
  
  
  
  
  
  $("#createSigAlbmEventForm").submit(function(event) {
      
       $("#EventCoverImgFilerr").html("");
        $('#sigAlbmEventNameErr').html('');
    
  
    event.preventDefault();
    // $("#createEventSubmit").addClass("d-none");
    // $("#createEventSubmitLoadingButton").removeClass("d-none");
    var form = $("#createSigAlbmEventForm");
    var formData = new FormData(form[0]);
    var eventFile = $('#signatureAlbumEventFiles')[0].files;
    var eventCoverFile = $('#EventCoverImgFile')[0].files;
    
    var sigAlbmEventName = $("#sigAlbmEventName").val();
    if(sigAlbmEventName == ""){
        $('#sigAlbmEventNameErr').html('Plese enter the event name!.');
        return false;
    }
    
    

    if(eventCoverFile.length == 0){
        $("#EventCoverImgFilerr").html("Plese upload the cover image!.");
        return false;
    }else if(eventCoverFile.length > 1){
        $("#EventCoverImgFilerr").html("Plese You can upload only one image !.");
        return false;
    }else{
        $("#EventCoverImgFilerr").html("");
    }
    
 
    console.log(eventFile);
    formData.append('function', 'SystemManage');
    formData.append('method', 'saveEventAlbum');
    formData.append('save', "add");
    formData.append('selEventId', selEventId );
    formData.append('selCoverId', selCoverId );
    
    // formData.append('signatureAlbumFiles', zipFile);
// return false;
            return new swal({
                title: "Are you sure?",
                text: "You want to save this event",
                icon: false,
                // buttons: true,
                // dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Yes'
                }).then((confirm) => {
                    // console.log(confirm.isConfirmed);
                    if (confirm.isConfirmed) {
                        $.ajax({
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = ((evt.loaded / evt.total) * 100);
                                        $(".progress-bar").width(percentComplete.toFixed(0) + '%');
                                        $(".progress-bar").html(percentComplete.toFixed(0) +'%');
                                    }
                                }, false);
                                return xhr;
                            },
                            type: 'POST',
                            url: '/admin/ajaxHandler.php',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData:false,
                            beforeSend: function(){
                                $(".progress-bar").width('0%');
                                // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                                $('#signalbmEventUploadStatus').removeClass('d-none');
                            },
                            error:function(){
                                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                                 $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
                            },
                            success: function(resp){
                                // console.log(resp);
                                resp=JSON.parse(resp);
                                if(resp.status == 1){
                                    Swal.fire({
                                        icon: 'success',
                                        // title: resp.data,
                                        title: "Successfully save event",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
        
                                    // $('#uploadForm')[0].reset();
                                    $("#createEventModal").modal('hide');
                                    $("#sigAlbmEventName").val("");
                                    $("#EventCoverImgFile").val("");
                                    $("#signatureAlbumEventFiles").val("");
                                    $("#signatureAlbumFiles").val("");   
                                    $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
                                    
                                    
                                    showFileUpload(selEventId);
        
                                   
                                }else{
                                    Swal.fire({
                                        icon: 'error',
                                        title: "Failed to save event",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $("#createEventSubmit").removeClass("d-none");
                                    $("#createEventSubmitLoadingButton").addClass("d-none");
                                }
                                
                            }
                        });
                    }else{
                        $("#createEventSubmit").removeClass("d-none");
                        $("#createEventSubmitLoadingButton").addClass("d-none");
                    }
                });
})


function deleteEvent(id){
    
     return new swal({
        title: "Are you sure?",
        text: "You want to delete this event",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                 successFn = function(resp)  {
                     
                     if(resp.status == 1){
                         
                          Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Successfully delete event",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            showFileUpload(selEventId);
                         
                     }else{
                         
                         Swal.fire({
                                        icon: 'error',
                                        title: "Failed to delete event",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                         
                     }
                     
                 }
                data = { "function": 'SystemManage',"method": "deleteEventsForStaff","selEventId":id };
                
                apiCallForProvider(data,successFn);
                
                
                
            }
        });
    
}

function showUploadEventVedioModal(){
    
    var totalUpload = 0;
    var totalFiles = 0;
    
    
    
     $("#uploadLogoFilesErr").html("");
     
     var files = document.getElementById("uploadLogoFiles").files;
     if (files.length > 0) {
         
         totalFiles = files.length;
         
           $("#submitButton13").addClass("d-none");
            $("#submitLoadingButton13").removeClass("d-none");
                    
                    
        for (var i = 0 ; i < files.length; i++) {
            
            
                 
            let file = files[i];
            let formData = new FormData();
            
          
    
            formData.append('images[]', file);
            formData.append('selCoverId', selCoverId);
            formData.append('file_folder', file_folder);
            
            var reader = new FileReader();
            reader.onload = function (e) {
               
                // Upload the image using AJAX
                $.ajax({
                    xhr: function() {
                      var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                // Update the ID in the selector to match the HTML element ID
                                $("#progress-bar12").width(percentComplete.toFixed(0) + '%');
                                $("#progress-bar12").html(percentComplete.toFixed(0) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    url: '/admin/uploadEventVedio.php', // Replace with your PHP upload script
                    type: 'POST',
                    beforeSend: function(){
                        $("#progress-bar12").width('0%');
                        // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                        $('#signalbmEventUploadStatus').removeClass('d-none');
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                         showUploadImageModal(selCoverId,folder_name_val,file_folder);
                         totalUpload++;
                         
                         if(totalUpload == totalFiles){
                             $('.btn.btn-default[data-dismiss="modal"]').click();
                              $("#submitButton13").removeClass("d-none");
                        $("#submitLoadingButton13").addClass("d-none");
                         }
                         
                         
                         
                         
                        
                    },
                    error: function () {
                        

                        Swal.fire(
                          'Error',
                          "Something went wrong, please try again",
                          'error'
                        )
                       
                        $("#submitButton13").removeClass("d-none");
                        $("#submitLoadingButton13").addClass("d-none");
                        return false;
                    }
                });
            };
            reader.readAsDataURL(file);
        
        
            
            
            
        }
                    
            
         
     }else{
        $("#uploadLogoFilesErr").html("Please upload vedio");
        $("#submitButton13").removeClass("d-none");
        $("#submitLoadingButton13").addClass("d-none");
        return false;
    }
     
     
    
    
    
    
    
    
    
    
    
    
    
    
}

  
  function showUploadEventImageModal(){
      

       $("#uploadsigAlbmFolderName").val(folder_name_val);
   
    $("#selectedUplSigfile_folder").val(file_folder);
    $('.ri-close-circle-line').click();
    $("#uploadImageModal").modal('show');
     var imageList = $('#imageList');
    imageList.html('');
    
    $('#uploadStatus').html('');
    $('#uploadMoreStatus').html('');
    
    $('#rUplSigFilesSubmit').addClass('d-none');
    $('#disUploadImgTitlenew').html('');
    
  
     var progressBar = document.getElementById("progress-bar");
        
    // Set the width of the progress bar to 0%
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
      
       $("#uplSigFilesSubmit").removeClass("d-none");
            $("#uplSigFilesLoadingButton").addClass("d-none");
      
      
      
  }
  
  
  function showUploadModal(id="",folder_name=""){
      
        $('#createSigAlbmEventForm').removeClass('was-validated');
        $('.ri-close-circle-line').click();
        $("#signalbmEventUploadStatus").width('0%');
        $("#signalbmEventUploadStatus").html('0%');
        $("#createEventModal").modal('show');
        
        $("#sigAlbmEventName").prop('disabled', false);
        
        $("#sigAlbmEventName").val(folder_name);
        if(id != "") $("#sigAlbmEventName").prop('disabled', true);
        
        $('#uploadStatus').html('');
        $('#uploadMoreStatus').html('');
        
        $("#createEventSubmit").removeClass("d-none");
        $("#createEventSubmitLoadingButton").addClass("d-none");
        $("#EventCoverImgFilerr").html("");
        $('#sigAlbmEventNameErr').html('');
        
        selCoverId = id;
    
  }
  
  
  
   function deleteVedio(id){
      return new swal({
        title: "Are you sure?",
        text: "You want to delete this vedio",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                 successFn = function(resp)  {
                     
                     if(resp.status == 1){
                         
                          Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Successfully delete vedio",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            showUploadImageModal(selCoverId,folder_name_val,file_folder);
                         
                     }else{
                         
                         Swal.fire({
                                        icon: 'error',
                                        title: "Failed to delete vedio",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                         
                     }
                     
                 }
                data = { "function": 'SystemManage',"method": "deleteEventsVedioForStaff","selEventId":id };
                
                apiCallForProvider(data,successFn);
                
                
                
            }
        });
  }
  
  function deleteImage(id){
      return new swal({
        title: "Are you sure?",
        text: "You want to delete this image",
        icon: false,
        // buttons: true,
        // dangerMode: true,
        showCancelButton: true,
        confirmButtonText: 'Yes'
        }).then((confirm) => {
            // console.log(confirm.isConfirmed);
            if (confirm.isConfirmed) {
                
                 successFn = function(resp)  {
                     
                     if(resp.status == 1){
                         
                          Swal.fire({
                                icon: 'success',
                                // title: resp.data,
                                title: "Successfully delete image",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            showUploadImageModal(selCoverId,folder_name_val,file_folder);
                         
                     }else{
                         
                         Swal.fire({
                                        icon: 'error',
                                        title: "Failed to delete image",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                         
                     }
                     
                 }
                data = { "function": 'SystemManage',"method": "deleteEventsImageForStaff","selEventId":id };
                
                apiCallForProvider(data,successFn);
                
                
                
            }
        });
  }
  
  function showUploadImageModal(id,folder_name,file_folder_val){
      
      $('#showEventsUpload').addClass('d-none');
      $('#listAllEventsImages').html('');
      
      $('#showEventsImageUpload').removeClass('d-none');
      
      selCoverId = id;
      folder_name_val = folder_name;
      file_folder = file_folder_val;
      
      
      var tbl = '';
      
      tbl +='<div class="row pt-2">';
    tbl +='<div class="col-6">';
    tbl +='<h4 class=" text-muted">'+folder_name+'</h4>';
    tbl +='</div>';
    
    tbl +='</div><br>';
    
    
     successFn = function(resp)  {
        
        if(resp.status == 1){
            var list = resp.data ;
            var tbl = '';
            if(list.length > 0 ){
                
                tbl +='<div id="listAllVedio"></div>';
                
                tbl +='<div class="row">';
                
                for (var i = 0; i < list.length; i++) {
                    
                    tbl += '<div class="col-2">';
                    tbl += '<img src="' + list[i]['thumb_image_path'] + '" alt="" class="img-fluid">';
                    tbl += '<button class="btn btn-sm btn-danger" onclick="deleteImage(' + list[i]['id'] + ');">Delete</button>';
                    tbl += '</div>';
                    
                    
                }
    
    
                tbl +='</div>';
                
            }else{
                
                
                tbl +='<br><div class="callout callout-danger">';
                  tbl +='<h5 ><i class="fas fa-info"></i> No images uploaded</h5>';
                  tbl +='<p class="text-muted pt-2">There are currently no images uploaded. Please upload images.</p>';
                tbl +='</div>';
    
    
            }
            
            $('#listAllEventsImages').html(tbl);
            
            getAllVedioNow(id);
            
        }
        
        
    }
    data = { "function": 'SystemManage',"method": "getAllEventsImagesForStaff","selEventId":id };
    
    apiCallForProvider(data,successFn);
    
    
      
  }
  
  function getAllVedioNow(id){
      $('#listAllVedio').html('');
      var tbl = '';
      
       successFn = function(resp)  {
        
        if(resp.status == 1){
            var list = resp.data ;
            var tbl = '';
            if(list.length > 0 ){
                
                tbl +='<br><div class="row">';
                
                for (var i = 0; i < list.length; i++) {
                    
                    tbl += '<div class="col-4">';
                    
                    tbl += '<video width="340" height="180" controls>';
                        tbl += '<source src="' + list[i]['file_path'] + '" type="video/mp4">';
                        tbl += 'Your browser does not support the video tag.';
                    tbl += '</video>';
                    
                    
                    tbl += '<button class="btn btn-sm btn-danger" onclick="deleteVedio(' + list[i]['id'] + ');">Delete</button>';
                    tbl += '</div>';
                    
                    
                }
    
    
                tbl +='</div><br><hr><br>';
                
            }
            
            $('#listAllVedio').html(tbl);
            
        }
        
        
    }
    data = { "function": 'SystemManage',"method": "getAllEventsVedioForStaff","selEventId":id };
    
    apiCallForProvider(data,successFn);
      
  }
  
  
  
  
  function showFileUpload(id){
      $('#listEvents').addClass('d-none');
      selEventId = id;
      $('#listAllEvents').html('');
      $('#showEventsImageUpload').addClass('d-none');
      
    successFn = function(resp)  {
        
        if(resp.status == 1){
            var list = resp.data ;
            var tbl = '';
            if(list.length > 0 ){
                
                 tbl +='<div class="row pt-2">';
                tbl +='<div class="col-6">';
                tbl +='<h4 class=" text-muted">Events</h4>';
                tbl +='</div>';
                
                tbl +='</div><br>';
                
                tbl +='<div class="row">';
                
                for (var i = 0; i < list.length; i++) {
                    
                    tbl +='<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" onclick="showUploadImageModal('+list[i]['id']+',`'+list[i]['folder_name']+'`,`'+list[i]['file_folder']+'`);">';
                    tbl +='<div class="card bg-light d-flex flex-fill">';
                    
                    
                     tbl +='<div class="card-body pt-4">';
                    tbl +='<div class="row">';
                    tbl +='<div class="col-12">';
                    tbl +='<img src="'+list[i]['cover_image_path']+'" alt="" class="img-fluid">';
                    tbl +='<h2 class="lead pt-2"><b>'+list[i]['folder_name']+'</b></h2>';
                    
                    
                    tbl +='<ul class="ml-4 mb-0 fa-ul text-muted ">';
                    
                    tbl +='<button  class="btn btn-info btn-sm " type="button" onclick="showUploadModal('+list[i]['id']+',`'+list[i]['folder_name']+'`);"> Edit</button>';
                    tbl +='<button  class="btn btn-danger btn-sm " type="button" onclick="deleteEvent('+list[i]['id']+');"> Delete</button></li>';
                    
                    tbl +='</ul>';
                    
                    
                
                     tbl +='</div>';
                    tbl +='</div>';
                    tbl +='</div>';
                    
                    
                    
                     tbl +='</div>';
                    tbl +='</div>';
                
                    
                    
                    
                    
                    
                }
                
                
                
                
                
                tbl +='</div>';
                
                
                
                
            }else{
                  tbl +='<br><div class="callout callout-danger">';
                  tbl +='<h5 ><i class="fas fa-info"></i> No Events Available</h5>';
                  tbl +='<p class="text-muted pt-2">There are currently no events available. Please create a new event to get started.</p>';
                tbl +='</div>';
          
            }
            
            
            $('#listAllEvents').html(tbl);
            
            
        }
        
        
    }
    data = { "function": 'SystemManage',"method": "getAllEventsForStaff","selEventId":id };
    
    apiCallForProvider(data,successFn);
      

      $('#showEventsUpload').removeClass('d-none');
      
  }
  
  function cancelUpload(){
       $('#showEventsUpload').addClass('d-none');
       $('#listEvents').removeClass('d-none');
       $('#showEventsImageUpload').addClass('d-none');
  }
 
 function cancelImageUpload(){
       $('#showEventsUpload').removeClass('d-none');
       $('#listEvents').addClass('d-none');
       $('#showEventsImageUpload').addClass('d-none');
  }
    
</script>





