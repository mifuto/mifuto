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

$logedUserID = $_SESSION['MachooseAdminUser']['id'];

$sql3 = "SELECT a.*,b.state,c.city,cu.short_name,sc.center_name FROM tblprovideruserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id left join tblcountries cu on cu.country_id = a.county_id left join tblservicescenter sc on sc.id = a.servicescenter_id WHERE a.id='$logedUserID'  ";
$result3 = $DBC->query($sql3);
$rowU = mysqli_fetch_assoc($result3);




?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">My Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
          <div class="row" id="profileViewDiv">
                  <div class="col-md-3">
        
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                      <div class="card-body box-profile">
                        <div class="text-center">
                          <img class="profile-user-img img-fluid img-circle" src="<?=$loggedUsercompany_logo_url?>" alt="User profile picture">
                        </div>
        
                        <h3 class="profile-username text-center"><?=$Username?></h3>
        
                        <p class="text-muted text-center">Service Provider</p>
        
                    
        
                        <a class="btn btn-secondary btn-block" data-toggle="modal" data-target="#modal-default"><b>Update Logo</b></a>
                      </div>
                      <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    
                    
                     <!-- About Me Box -->
                        <div class="card card-primary">
                          <div class="card-header">
                            <h3 class="card-title">ABOUT ME</h3>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                            <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
            
                            <p class="text-muted">
                              <?=$rowU['email']?>
                            </p>
            
                            <hr>
                            
                             <strong><i class="fas fa-user mr-1"></i> Name</strong>
            
                            <p class="text-muted">
                              <?=$rowU['name']?>
                            </p>
            
                            <hr>
                            
            
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            
                            <p class="text-muted"><?=$rowU['city']?>, <?=$rowU['state']?>, <?=$rowU['short_name']?></p>
            
                            <hr>
            
                            <strong><i class="fas fa-building mr-1"></i> Service Center</strong>
            
                            <p class="text-muted">
                              <?=$rowU['center_name']?>
                            </p>
                            
                         
                          </div>
                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    
                    
                    
                    
                    
        
                    
                  </div>
                  
                  <div class="col-md-9">
                      
                      
                        <div class="card">
                          <div class="card-header p-2">
                            <ul class="nav nav-pills">
                              <li class="nav-item" ><a class="nav-link active" href="#Profile" data-toggle="tab" onclick="updateProfile();">PROFILE</a></li>
                              <li class="nav-item" ><a class="nav-link" href="#password" data-toggle="tab" onclick="updateProfile();">PASSWORD CHANGE</a></li>
                            </ul>
                          </div><!-- /.card-header -->
                          
                            <div class="card-body">
                                <div class="tab-content">
                                    
                                    <div class="active tab-pane" id="Profile">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                        
                                        
                                        
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">Enter Name</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="inpName" name="inpName" placeholder="Enter name" value="<?=$rowU['name']?>">
                                
                                                        <div class="invalid-feedback">
                                                        Please enter the Name!.
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                 <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">County</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selCounty" name="selCounty" onchange="getState('selState');">
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the County!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                 <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">State</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selState" name="selState" onchange="getCity('selCity');">
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the State!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">District</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selCity" name="selCity">
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the District!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">Service Center Type</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selServiceCenter" name="selServiceCenter">
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the Service Center Type!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                
                                                <div class="text-danger d-none" id="changeProfileErr" ></div>
                                                
                                                
                                        
                                              
                                                <div class="col-12 mt-4 ">
                                                  <button id="submitButton112" class="btn btn-primary w-100" type="button" onclick="updateProfileNow();">Update Profile</button>
                                                  <button class="btn btn-primary w-100 d-none" type="button" id="submitLoadingButton112" disabled>
                                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                    Please wait...
                                                    </button>
                                                </div>
                                            
                                        
                                        </div>
                                        </div>
                                        
                                        
                                        
                                    </div>
                                    
                                    <div class=" tab-pane" id="password">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                        
                                        
                                         <div class="row mb-3 ">
                                                <label for="" class="col-12 col-form-label">Enter Old Password</label>
                                                <div class="col-12">
                                                    <input type="password" class="form-control" id="inpOldPassword" name="inpOldPassword" placeholder="Enter password">
                                
                                                    <div class="invalid-feedback">
                                                    Please enter the New valid password!.
                                                    </div>
                                                </div>
                                            </div>
                                             
                                             <div class="row mb-3 ">
                                                <label for="" class="col-12 col-form-label">Enter New Password</label>
                                                <div class="col-12">
                                                    <input type="password" class="form-control" id="inpPassword" name="inpPassword" placeholder="Enter password">
                                
                                                    <div class="invalid-feedback">
                                                    Please enter the password!.
                                                    </div>
                                                </div>
                                            </div>
                                            
                                             <div class="row mb-3">
                                                <label for="" class="col-12 col-form-label">Enter Re-Password</label>
                                                <div class="col-12">
                                                    <input type="password" class="form-control" id="inpRePassword" name="inpRePassword" placeholder="Enter password">
                                
                                                    <div class="invalid-feedback">
                                                    Please enter the matched re-password.
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                               
                                             <div class="text-danger d-none" id="changePassFailedErr" ></div>
                                             <div class="text-success d-none" id="changePassSuccMeg" ></div>
                                            
                                            
                                    
                                          
                                            <div class="col-12 mt-4 ">
                                              <button id="submitButton11" class="btn btn-primary w-100" type="button" onclick="changePasswordNow();">Change password</button>
                                              <button class="btn btn-primary w-100 d-none" type="button" id="submitLoadingButton11" disabled>
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                Please wait...
                                                </button>
                                            </div>
                                                                
                                        
                                         </div>
                                        </div>
                                        
                                        
                                        
                                        
                                    </div>
                                    
                                </div>
                                    
                            </div>
                          
                          
                          
                          
                          
                          
                          
                          
                        </div>
                     
                
                     
                     
                  </div>
         
        </div>
        
        
        
        
        
          <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Upload logo</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form id="uploadCompanyLogoForm" class="g-3 needs-validation" novalidate="">
                
                
                <div class="modal-body">
                    
                    
                    <div class="container ">
                        <div class="card p-4">
                            <div class="custom-file">
                                <strong>Upload logo</strong> (maximum image size is 500KB)<br>
                                <input type="file"  id="uploadLogoFiles" name="uploadLogoFiles[]" accept="image/*" >
                                <!--<label class="custom-file-label" for="imageUploader">Upload logo (image size 300x48px)</label>-->
                                <div class="text-danger" id="uploadLogoFilesErr"></div>
                                
                            </div>
                            <br>
                        </div>
                       
                    </div>
                    
                    <div class="progress mt-3">
                        <!-- Update the ID to match the selector used in the JavaScript -->
                        <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
            
                  
                  
                  
                  
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  
                  
                  <button type="button" class="btn btn-primary" id="submitButton13" onclick="uploadCompanyLogoNow();">Update Logo</button>
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
    $('#navProfile').addClass('active');
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
   
    
    $( document ).ready(function() {
     showProfile();
     updateProfile();
    

  });
  
  function updateProfileNow(){
      
       $('#changeProfileErr').addClass('d-none');
      
       $('#inpName').removeClass('is-invalid');
     $('#selCounty').removeClass('is-invalid');
     $('#selState').removeClass('is-invalid');
     $('#selCity').removeClass('is-invalid');
     $('#selServiceCenter').removeClass('is-invalid');
     
     var inpName = $('#inpName').val();
     var selCounty = $('#selCounty').val();
     var selState = $('#selState').val();
     var selCity = $('#selCity').val();
     var selServiceCenter = $('#selServiceCenter').val();
     
     
      if(inpName == ""){
         $('#inpName').addClass('is-invalid');
         $('#inpName').focus();
         return false;
     }
     
      if(selCounty == ""){
         $('#selCounty').addClass('is-invalid');
         $('#selCounty').focus();
         return false;
     }
     
      if(selState == ""){
         $('#selState').addClass('is-invalid');
         $('#selState').focus();
         return false;
     }
      
      if(selCity == ""){
         $('#selCity').addClass('is-invalid');
         $('#selCity').focus();
         return false;
     }
     
      if(selServiceCenter == ""){
         $('#selServiceCenter').addClass('is-invalid');
         $('#selServiceCenter').focus();
         return false;
     }
     
     
      
      $('#submitLoadingButton112').removeClass('d-none');
    $("#submitButton112").addClass("d-none");
    
    
     successFn = function(resp)  {
        
        if(resp.status == 1){
           
            $('#submitLoadingButton112').addClass('d-none');
            $("#submitButton112").removeClass("d-none");
            
            $('#changeProfileErr').addClass('d-none');
            
            window.location.reload();
          
    
            
        }else{
            $("#changeProfileErr").removeClass("d-none");
            $("#changeProfileErr").html(resp.data);
           
        }
        
       
        $('#submitLoadingButton112').addClass('d-none');
        $("#submitButton112").removeClass("d-none");
      
    }
    data = { "function": 'User',"method": "updateServiceProviderProfile" ,'name':inpName ,'county':selCounty,'state':selState ,'city':selCity ,'servicescenter_id':selServiceCenter };
    
    apiCallForProvider(data,successFn);
    
     
     
     
     
     
      
  }
  
  
  
  
  
  function updateProfile(){
     
      
      $('#changePassFailedErr').html('');
      $('#changePassSuccMeg').html('');
      
      $('#changePassFailedErr').addClass('d-none');
      $('#changePassSuccMeg').addClass('d-none');
      
      
      
       $('#submitLoadingButton11').addClass('d-none');
        $("#submitButton11").removeClass("d-none");
        
         $('#inpPassword').removeClass('is-invalid');
     $('#inpRePassword').removeClass('is-invalid');
     $('#inpOldPassword').removeClass('is-invalid');
     
     
      $('#inpName').removeClass('is-invalid');
     $('#selCounty').removeClass('is-invalid');
     $('#selState').removeClass('is-invalid');
     $('#selCity').removeClass('is-invalid');
     $('#selServiceCenter').removeClass('is-invalid');
     
     
       getCounty("selCounty");
       getState('selState');
       getCity('selCity');
       getServiceCenter('selServiceCenter');
       
       
        $('#changeProfileErr').html('');
      
      $('#changeProfileErr').addClass('d-none');
      
       $('#submitLoadingButton112').addClass('d-none');
        $("#submitButton112").removeClass("d-none");
       
       
       

     
     
     
      
      
      
  }
  
  function changePasswordNow(){
      
      $('#changePassFailedErr').addClass('d-none');
      $('#changePassSuccMeg').addClass('d-none');
      
       $('#inpPassword').removeClass('is-invalid');
     $('#inpRePassword').removeClass('is-invalid');
     $('#inpOldPassword').removeClass('is-invalid');
     
       var inpPassword = $('#inpPassword').val();
     var inpRePassword = $('#inpRePassword').val();
     var inpOldPassword = $('#inpOldPassword').val();
     
     
          if(inpOldPassword == ""){
         $('#inpOldPassword').addClass('is-invalid');
         $('#inpOldPassword').focus();
         return false;
     }
     
     
          if(inpPassword == ""){
         $('#inpPassword').addClass('is-invalid');
         $('#inpPassword').focus();
         return false;
     }
      
      if(inpRePassword != inpPassword){
         $('#inpRePassword').addClass('is-invalid');
         $('#inpRePassword').focus();
         return false;
     }
     
     
      $('#submitLoadingButton11').removeClass('d-none');
    $("#submitButton11").addClass("d-none");
    
    
     successFn = function(resp)  {
        
        if(resp.status == 1){
           
            $('#submitLoadingButton11').addClass('d-none');
            $("#submitButton11").removeClass("d-none");
            
         
            
            $('#changePassFailedErr').addClass('d-none');
            $('#changePassSuccMeg').removeClass('d-none');
            $('#changePassSuccMeg').html('Successfully update new passeord');
            
          
    
            
        }else{
            $("#changePassFailedErr").removeClass("d-none");
            $("#changePassFailedErr").html(resp.data);
            $('#inpOldPassword').addClass('is-invalid');
            $('#inpOldPassword').focus();
        }
        
       
        $('#submitLoadingButton11').addClass('d-none');
        $("#submitButton11").removeClass("d-none");
      
    }
    data = { "function": 'User',"method": "changeServiceProviderPassword" ,"password":inpPassword, 'oldPassword':inpOldPassword };
    
    apiCallForProvider(data,successFn);
    
    
    
     
     
     
      
      
      
  }
  
  
  
  
  
  
  function showProfile(){
       $('#profileViewDiv').removeClass('d-none');
      $('#updateLogoDiv').addClass('d-none');
  }
  
  function changelogo(){
      $('#profileViewDiv').addClass('d-none');
      $('#updateLogoDiv').removeClass('d-none');
      
       $('#submitLoadingButton13').addClass('d-none');
        $("#submitButton13").removeClass("d-none");
       
        var progressBar = document.getElementById("progress-bar");
        
        // Set the width of the progress bar to 0%
        progressBar.style.width = "0%";
        progressBar.setAttribute("aria-valuenow", "0");
        
        $("#submitButton13").removeClass("d-none");
        $("#uploadLogoFiles").val("");
        $('#uploadLogoFiles').val(null);
        
        $('#uploadLogoFilesErr').html('');
      
  }
  
  
  function uploadCompanyLogoNow(){
     var inpEmail = '<?=$rowU['email']?>';

     $("#uploadLogoFilesErr").html("");
     
     var files = document.getElementById("uploadLogoFiles").files;
     if (files.length > 0) {
         
        let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
        if(fileSizeInKB > 500){
            $("#uploadLogoFilesErr").html("Please upload logo (Maximum image size is 500KB)");
            $("#submitButton13").removeClass("d-none");
            $("#submitLoadingButton13").addClass("d-none");
            return false;
        }
      

        formData.append('images[]', file);
        formData.append('inpEmail', inpEmail);
        
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
                            $("#progress-bar").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadCompanyLogoNew.php', // Replace with your PHP upload script
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
                    window.location.reload();
                    
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
        
        
         
         
         
         
         
     }else{
        $("#uploadLogoFilesErr").html("Please upload logo (Maximum image size is 500KB)");
        $("#submitButton13").removeClass("d-none");
        $("#submitLoadingButton13").addClass("d-none");
        return false;
    }
     
     
    
     
 }
 
 
 
 
 function getServiceCenter(selectId,val="") {
     
     var servicescenter_id = '<?=$rowU['servicescenter_id']?>';
      

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select Service Center Type</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(val == value.id) options += "<option value='"+value.id+"' selected>"+value.center_name+"</option>";
        else options += "<option value='"+value.id+"'>"+value.center_name+"</option>";
        
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
    
     $("#"+selectId).val(servicescenter_id).trigger('change');
      
    
      
    }
    data = { "function": 'SystemManage',"method": "getServiceCenterActiveList" };
    
    apiCallForProvider(data,successFn);
    
}
  
  
    function getCounty(selectId) {
        
        var county_id = '<?=$rowU['county_id']?>';
     
    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select Country</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(county_id == value.country_id) options += "<option selected value='"+value.country_id+"'>"+value.short_name+"</option>";
        else options += "<option value='"+value.country_id+"'>"+value.short_name+"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
    
    $("#"+selectId).val(county_id).trigger('change');
      
    }
    data = { "function": 'SystemManage',"method": "getCountries"};
    
    apiCallForProvider(data,successFn);
    
}


  function getState(selectId,val="") {
      
      var selCounty = $('#selCounty').val();
      
      var state_id = '<?=$rowU['state_id']?>';
      val = state_id;
     
    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select State</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(val == value.id) options += "<option value='"+value.id+"' selected>"+value.state+"</option>";
        else options += "<option value='"+value.id+"'>"+value.state+"</option>";
        
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
    
    $("#"+selectId).val(state_id).trigger('change');
      
    
      
    }
    data = { "function": 'SystemManage',"method": "getState" , "selCounty":selCounty};
    
    apiCallForProvider(data,successFn);
    
}


function getCity(selectId,val="",selState="") {
      
      if(selState == "") selState = $('#selState').val();
      
      var city_id = '<?=$rowU['city_id']?>';
      val = city_id;
     
    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select District</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        options += "<option value='"+value.id+"'>"+value.city+"</option>";
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
      
      if(val !="")$("#selCity").val(val).trigger('change');
      
      $("#"+selectId).val(city_id).trigger('change');
      
      
    }
    data = { "function": 'SystemManage',"method": "getCityListData1" , "selState":selState};
    
    apiCallForProvider(data,successFn);
    
}
  
 
    
    
</script>





