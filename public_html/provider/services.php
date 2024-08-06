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

$sql = "SELECT * FROM tblproviderusercompany WHERE is_accept_company = 1 and user_id=".$logedUserID;
$result = $DBC->query($sql);
$rowcount = mysqli_num_rows($result);
if($rowcount > 0) $isNoCompany = false;
else $isNoCompany = true;



?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Services</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Our Services</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
          
          
          
    <?php if($isNoCompany){ ?>
    
    
            <div class="callout callout-danger">
              <h5><i class="fas fa-info"></i> You have no approved companies</h5>
              <p class="text-muted pt-2">You cannot access or add services because company acceptance is pending. Please navigate to the <a href="/provider/companies.php">Our Companies</a> menu, complete the company details, and await acceptance. Once acceptance is granted, you can add your provided services.</p>
            </div>
    
    
    <?php }else{ ?>
    
    
        <div id="StateListSection">
            
               <div class="row pt-2">
                    <div class="col-6">
                        
                    </div>
                
                    <div class="col-6 ">
                       <div align="right">
                        
                            <button type="button" class="btn btn-primary float-right" onclick="showAddStateSection();"><i class="fas fa-plus"></i> Add service</button>
                       </div>
                    </div>
                
                </div><br>
                
                <div class="card">
                    <div class="card-body">
                        
                        <div class="row mb-3">
                            <label for="" class="col-12 col-form-label text-dark">Select Company</label>
                           
                            <div class="col-10 col-sm-5 col-md-3">
                                
                                 <select class="form-control select2" aria-label="Default select example" id="selServiceProviderList" name="selServiceProviderList" onchange="getStateListData();">
                                    </select>
                                
                                
                                
                                <div class="invalid-feedback">
                                Select from accepted company !.
                                </div>
                            </div>
                            
                        </div>
                   
                
                
                
                
                
                            <div id="serviceListDiv"></div>
                            
                 </div>
                    </div>
            
            
            
            
        </div>
        
        
        <div class="d-none" id="StateFormSection">
            
               <div class="row pt-2">
                    <div class="col-12">
                        <h4 class=" text-muted" id="addEVT">My Services</h4>
                    </div>
                
                  
                
                </div><br>
                
                
                 <div class="card">
                            <div class="card-body">
                            <h4 class="card-title text-primary">
                            <strong id="addEVT"></strong>
                            </h4><br>
                                    
                    
           
    
                 
                  <form id="addCountyForm"  >
                      
                      
                        <div class="row mb-3">
                            <label for="" class="col-12 col-form-label text-dark">Seelct company</label>
                           
                            <div class="col-12">
                                
                                 <select class="form-control select2" aria-label="Default select example" id="selServiceProvider" name="selServiceProvider">
                                    </select>
                                
                                
                                
                                <div class="invalid-feedback">
                                Select from accepted company
                                </div>
                            </div>
                            
                        </div>
                      
                      
                      
                   
                    
                        <div class="row mb-3">
                            <label for="" class="col-12 col-form-label">Service name</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="inpServiceName" name="inpServiceName">
        
                                <div class="invalid-feedback">
                                Please enter the Service name!.
                                </div>
                            </div>
                        </div>
                        
           
                    
                    
                     <div class="row mb-3">
                        <label for="" class="col-12 col-form-label">Upload service images (Maximum of 5 images can be uploaded)</label>
                        <div class="col-12">
                            <input type="file" class="form-control" id="import_image" name="import_image[]" accept="image/*" multiple>
    
                            <div class="invalid-feedback" id="imageErr">
                            Please upload service images!.
                            </div>
                        </div>
                    </div>
                    
                    <div id="displayCompanyDocumentsDiv" class="pb-4"></div>
                    
                    
                    
                    
                    <div class="row mb-3">
                        <label for="" class="col-12 col-form-label">Description</label>
                        <div class="col-12">
                            <textarea class="form-control" id="inpDescription" name="inpDescription"></textarea>

                            <div class="invalid-feedback">
                            Please enter Description!.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3 d-none">
                        <label for="" class="col-12 col-form-label">Service provide price</label>
                        <div class="col-12">
                            <input type="text" class="form-control" id="inpServicePrice" name="inpServicePrice">
    
                            <div class="invalid-feedback">
                            Please enter the Service provide price!.
                            </div>
                        </div>
                    </div>
                    
                    
                        
                        <div class="row mb-3 d-none">
                            <label for="" class="col-12 col-form-label text-dark">Allowed maximum numbers of family members</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="inpNumberOfMembers" name="inpNumberOfMembers">
        
                                <div class="invalid-feedback">
                                Please enter the Allowed maximum numbers of family members!.
                                </div>
                            </div>
                           
                        </div>
                        
                        <div class="row mb-3 d-none">
                            <label for="" class="col-12 col-form-label text-dark">Extra price per head</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="inpExtraPrice" name="inpExtraPrice">
        
                                <div class="invalid-feedback">
                                Please enter the Extra price per head!.
                                </div>
                            </div>
                           
                        </div>
                        
                        
                        <div class="row mb-3">
                            <label for="" class="col-12 col-form-label text-dark">Service adding</label>
                           
                            <div class="col-12">
                                
                                 <select class="form-control select2" aria-label="Default select example" id="selServiceAdding" name="selServiceAdding" onchange="changeServiceAdding();">
                                   
                                    </select>
                                
                                <div class="invalid-feedback">
                                Please select the Service adding!.
                                </div>
                            </div>
                            
                        </div>
                        
                        
                         <div class="row mb-3">
                            <label for="" class="col-12 col-form-label text-dark">Staff type</label>
                           
                            <div class="col-12">
                                
                                 <select class="form-control select2" aria-label="Default select example" id="selStaffType" name="selStaffType" multiple>
                                   
                                    </select>
                                
                                <div class="invalid-feedback">
                                Please select the Staff type!.
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row mb-3 d-none" id="ServiceAddingOtherDiv">
                            <!--<label for="" class="col-12 col-form-label">Service adding</label>-->
                            <div class="col-12">
                                <input type="text" class="form-control" id="inpServiceAddingOther" name="inpServiceAddingOther">
        
                                <div class="invalid-feedback">
                                Please enter the service adding!.
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="progress mt-3">
                            <!-- Update the ID to match the selector used in the JavaScript -->
                            <div class="progress-bar progress-bar-striped bg-danger" id="signalbmUploadStatus" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            <div id="uploadStatus"></div>
                        </div>
                    
                    
            
                    
                   
                    <div class="row mb-3 mt-4">
                      <div class="col-sm-9"></div>
                      <div class="col-sm-3">
                          <div class="float-right">
                            <input type="hidden" id="hiddenEventId" name="hiddenEventId" value="">
                            <input type="hidden" id="save" name="save" value="add">
                            <input type="hidden" id="oldType" name="oldType" value="">
                            <button type="submit" id="submitButton" class="btn btn-primary float-right">SAVE</button>
                            <button class="btn btn-primary d-none" type="button" id="submitLoadingButton" disabled>
                              <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                              Please wait...
                            </button>
                            <button type="button" class="btn btn-danger" onclick="cancelCountyForm();">Cancel</button>
                          </div>
                      </div>
                    </div>
    
                  </form><!-- End General Form Elements -->
                  
                  
                  
                   </div>
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
    $('#navOurServices').addClass('active');
    $('#navProfile').removeClass('active');
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
    var imgCount = 0;
    
    
    
    
    
    
    $( document ).ready(function() {
      
      getStateListData();
      
      getServiceProvider('selServiceProvider');
      getServiceProvider('selServiceProviderList');
      
      getServiceAddingType('selServiceAdding');
      

      getAttributeStaffLink("selStaffType");
    

  });
  
  
   function getAttributeStaffLink(selectId,val="") {
     
        successFn = function(resp)  {
            // resp = JSON.parse(resp);
          
          var users = resp["data"]['attribute_options'];
          var staffArray = users.split(",");

          var options = "<option selected value=''>Select staff type</option>";
          
            for (var i = 0; i < staffArray.length; i++) {
                
                options += "<option value='"+staffArray[i]+"'>"+staffArray[i]+"</option>";
            }
          
       
    
          $("#"+selectId).html(options);
        //   $("#"+selectId).select2();
        
        if(val != '') $("#"+selectId).val(val).trigger('change');
          
        }
        data = { "function": 'SystemManage',"method": "geteditServicesAttributesFeildList",'sel_id':1};
        
        apiCallForProvider(data,successFn);
        
    }
  
  
  function getServiceAddingType(selectId,val="") {
    

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select service adding</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(val == value.id) options += "<option value='"+value.id+"' selected>"+value.center_name+"</option>";
        else options += "<option value='"+value.id+"'>"+value.center_name+"</option>";
        
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
      
    
      
    }
    data = { "function": 'SystemManage',"method": "getServicesAddingTypeListData" };
    
    apiCallForProvider(data,successFn);
    
}
  
  
  
  
  
  function getServiceAddingType(selectId,val="") {
    

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select service adding</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(val == value.id) options += "<option value='"+value.id+"' selected>"+value.center_name+"</option>";
        else options += "<option value='"+value.id+"'>"+value.center_name+"</option>";
        
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
      
    
      
    }
    data = { "function": 'SystemManage',"method": "getServicesAddingTypeListData" };
    
    apiCallForProvider(data,successFn);
    
}
  
  
  
  
  
  
  function changeServiceAdding(){
      var selServiceAdding = $('#selServiceAdding').val();
      if(selServiceAdding == 'Other') $('#ServiceAddingOtherDiv').removeClass('d-none');
      else $('#ServiceAddingOtherDiv').addClass('d-none');
  }
  
  
  function getServiceProvider(selectId,val="") {
    

    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
      var users = resp["data"];
      var options = "<option selected value=''>Select from accepted company</option>";
      $.each(users, function(key,value) {
        // console.log(value.id);
        if(val == value.id) options += "<option value='"+value.id+"' selected>"+value.company_name+"</option>";
        else options += "<option value='"+value.id+"'>"+value.company_name+"</option>";
        
      });
    //   alert("#"+selectId);

      $("#"+selectId).html(options);
    //   $("#"+selectId).select2();
      
    
      
    }
    data = { "function": 'SystemManage',"method": "getServiceProviderForProvider" };
    
    apiCallForProvider(data,successFn);
    
}
  
    var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "June",
    "July", "Aug", "Sept", "Oct", "Nov", "Dec" ];
    
    
    function showAddStateSection(){
      
      emptyForm();
      

     
    $("#StateListSection").addClass("d-none");
        $('#addEVT').html('Add Service');
        
       
        $('#StateFormSection').removeClass("d-none");
      
  }
  
  function emptyForm(){
      $("#addCountyForm .form-control").removeClass("is-invalid");
      $('#addCountyForm').removeClass('is-invalid');
      $('#addCountyForm').removeClass('was-validated');
       $("#hiddenEventId").val("");
       $("#save").val("add");
       
       $("#inpServiceName").val("");
       $("#inpDescription").val("");
       $("#inpServicePrice").val("");
       $("#import_image").val("");
       
       $("#inpNumberOfMembers").val("");
       $("#inpExtraPrice").val("");
       
        $(".progress-bar").width('0%');
        $('#import_image').removeClass('is-invalid');
        
        $("#selServiceProvider").val('').trigger('change');
        
        $("#selServiceAdding").val('').trigger('change');
        
        $('#displayCompanyDocumentsDiv').html('');
        
        $('#ServiceAddingOtherDiv').addClass('d-none');
        $("#inpServiceAddingOther").val("");
        
        imgCount = 0;
        
        $("#selStaffType").val("").trigger('change');
      
       
       $('#submitLoadingButton').addClass('d-none');
       $("#submitButton").removeClass("d-none");


  }
  
  
   function cancelCountyForm(){
      emptyForm();
      $('#StateFormSection').addClass("d-none");
      $("#StateListSection").removeClass("d-none");
  }
  
  
  
  $("#addCountyForm").submit(function(event) {
    event.preventDefault();
}).validate({
    submitHandler: function(form) {
        
        var save = $("#save").val();
        
        var selStaffType = $('#selStaffType').val();
        
        if(selStaffType == ''){
          $('#selStaffType').addClass('is-invalid');
            return false;
      }
        
     
        $('#inpServiceAddingOther').removeClass('is-invalid');
        
        var selServiceAdding = $('#selServiceAdding').val();
          if(selServiceAdding == 'Other'){
              var inpServiceAddingOther = $('#inpServiceAddingOther').val();
              if(inpServiceAddingOther == ''){
                  $('#inpServiceAddingOther').addClass('is-invalid');
                    return false;
              }
              
          }else{
              $('#inpServiceAddingOther').val('');
          }
      
    
        
        var import_image = $("#import_image").val();
        
        $('#import_image').removeClass('is-invalid');
            
        if(import_image == "" && save == "add"){
            
            $('#imageErr').html('Please upload service images!.');
            $('#import_image').addClass('is-invalid');
            return false;
        }
        
        // Get the input element
        var inputElement = document.getElementById('import_image');
        // Get the files selected
        var files = inputElement.files;
        // Get the number of files selected
        var numberOfFiles = files.length;
        
        var newCountChk = numberOfFiles + imgCount ;
        if(newCountChk > 5){
            $('#imageErr').html('Please upload '+(5 - imgCount)+' images!.');
            $('#import_image').addClass('is-invalid');
            return false;
        }
        
        
        
        
        var form = $("#addCountyForm");
        var formData = new FormData(form[0]);
        
        formData.append('function', 'SystemManage');
        formData.append('method', 'saveProviderService');
        formData.append('staffTypes', selStaffType);
        
       
        return new swal({
                title: "Are you sure?",
                text: "You want to "+save+" this Service",
                icon: false,
                // buttons: true,
                // dangerMode: true,
                showCancelButton: true,
                confirmButtonText: 'Yes'
                }).then((confirm) => {
                    // console.log(confirm.isConfirmed);
                    if (confirm.isConfirmed) {
                        
                        $('#submitLoadingButton').removeClass('d-none');
                        $("#submitButton").addClass("d-none");
                        
                        
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
                            $('#signalbmUploadStatus').removeClass('d-none');
                        },
                     
                            error:function(){
                               $("#submitButton").removeClass("d-none");
                                $("#submitLoadingButton").addClass("d-none");
                                // $("#hiddenEventId").val("");
                                $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                            },
                            success: function(resp){
                                // console.log(resp);
                                resp=JSON.parse(resp);
                                if(resp.status == 1){
                                    Swal.fire({
                                        icon: 'success',
                                        // title: resp.data,
                                        title: "Service "+save+" successfully",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    // $('#uploadForm')[0].reset();
                                    emptyForm();
                                    getStateListData();
                                    
                                    cancelCountyForm();
                                    
                                    // $("#updateEventButton").removeClass("d-none");
                                    // $("#submitLoadingButton").addClass("d-none");
                                    }else{
                                        Swal.fire({
                                            icon: 'error',
                                            title: resp.data,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        $("#submitButton").removeClass("d-none");
                                        $("#submitLoadingButton").addClass("d-none");
                                    }
                                    
                                }
                        });
                        
                        
                        
                        
                       
                    }else{
                        $("#submitButton").removeClass("d-none");
                        $("#submitLoadingButton").addClass("d-none");
                        // $("#hiddenEventId").val("");
                    }
            });
            
            
    
    },
    rules: {
        inpServiceName: {
            required: true
        },
        inpDescription: {
            required: true
        },
        //  inpServicePrice: {
        //     required: true
        // },
        // inpNumberOfMembers: {
        //     required: true
        // },
        //  inpExtraPrice: {
        //     required: true
        // },
          selServiceProvider: {
            required: true
        },
          selServiceAdding: {
            required: true
        },
       
    },
    messages: {
       
       
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');
    element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
    $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass('is-invalid');
    }
});


function getStateListData(){
    
    var selServiceProviderList = $('#selServiceProviderList').val();
    if(selServiceProviderList == null || selServiceProviderList == "") selServiceProviderList = '';

    
     $('#serviceListDiv').html('');
    
    successFn = function(resp)  {
        
        if(resp.status == 1){
            
            var list = resp.data ;
            var tbl = '';
            if(list.length > 0 ){
                
                
                tbl +='<div class="row">';
                
                
                for (var i = 0; i < list.length; i++) {
                
                
                    tbl +='<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">';
                    tbl +='<div class="card card-widget widget-user-2">';
                    
                    tbl +='<div class="ribbon-wrapper ribbon-lg">';
                    
                    if(list[i]['is_accept'] == 0) tbl +='<div class="ribbon bg-info">Pending</div>';
                    else if(list[i]['is_accept'] == 1) tbl +='<div class="ribbon bg-success">Accepted</div>';
                    else if(list[i]['is_accept'] == 2) tbl +='<div class="ribbon bg-danger">Rejected</div>';
                    
                    tbl +='</div>';
                    
                    
                    
                        
                    tbl +='<div class="widget-user-header bg-warning">';
                    tbl +='<div class="widget-user-image">';
                    tbl +='<img class="img-circle elevation-2" src="'+list[i]['company_logo_url']+'" alt="User Avatar">';
                    tbl +='</div>';
                    tbl +='<h3 class="widget-user-username"><b>'+list[i]['name']+'</b></h3>';
                    tbl +='<h5 class="widget-user-desc text-secondary">'+list[i]['company_name']+'</h5>';
                    tbl +='</div>';
                          
                    tbl +='<div class="card-footer p-0">';
                    tbl +='<ul class="nav flex-column">';
                    tbl +='<li class="nav-item"><a href="#" class="nav-link">UPCOMING BOOKINGS <span class="float-right badge bg-primary">0</span></a></li>';
                    tbl +='<li class="nav-item"><a href="#" class="nav-link">CONFIRMING BOOKINGS <span class="float-right badge bg-info">0</span></a></li>';
                    tbl +='<li class="nav-item"><a href="#" class="nav-link">FINISHED BOOKINGS <span class="float-right badge bg-success">0</span></a></li>';
                    tbl +='<li class="nav-item"><a href="#" class="nav-link">CANCELLED <span class="float-right badge bg-danger">0</span></a></li>';
                    
                    tbl +='<li class="nav-item">';
                    
                       tbl +='<div class="text-right pt-2 pb-2 pr-2">';
                    tbl +='<a href="#" class="btn btn-sm bg-danger" onclick="deleteState(`'+list[i]['id']+'`);" ><i class="fas fa-trash"></i></a>';
                    tbl +='<a href="#" class="btn btn-sm btn-primary" onclick="editStateList(`'+list[i]['id']+'`);"><i class="nav-icon fas fa-edit"></i></a>';
                    tbl +='</div>';
                    
                    tbl +='</li>';
                    
                    tbl +='</ul>';
                    
               
                    
                    
                    tbl +='</div>';
                          
                    tbl +='</div>';
                    tbl +='</div>';
                    
                    
                }
                
                
                
                
                
                
                
                tbl +='</div>';
                
                
                
                
                
            }else{
                
                
                tbl +='<div class="callout callout-danger">';
                  tbl +='<h5 ><i class="fas fa-info"></i> You have no services</h5>';
                  tbl +='<p class="text-muted pt-2">Currently, there are no services available. Please add services to view and manage your offerings.</p>';
                tbl +='</div>';
          
            
                
            }
            $('#serviceListDiv').html(tbl);
            
           
            
        }
        
        
        
        
        
       
    }
    data = { "function": 'SystemManage',"method": "getProviderServiceListDataNew" ,'selServiceProviderList':selServiceProviderList };
    
    apiCallForProvider(data,successFn);
}


function editStateList(id){
    
    $("#addCountyForm .form-control").removeClass("is-invalid");
      $('#addCountyForm').removeClass('is-invalid');
      $('#addCountyForm').removeClass('was-validated');
      
    //   emptyForm();
       $('#submitLoadingButton').addClass('d-none');
       $("#submitButton").removeClass("d-none");

    
        $('#addEVT').html('Update service');
          $('#StateFormSection').removeClass("d-none");
                $("#StateListSection").addClass("d-none");
                
                
                $('#displayCompanyDocumentsDiv').html('');
        
        
        
        successFn = function(resp)  {
            if(resp.status == 1){
              
                var eventList = resp.data;

                $("#hiddenEventId").val(id);
                $("#save").val("edit");
             
               
                 $("#inpServiceName").val(eventList['name']);
               $("#inpDescription").val(eventList['description']);
               $("#inpServicePrice").val(eventList['price']);
               $("#import_image").val("");
               
                $("#inpNumberOfMembers").val(eventList['number_of_members']);
               $("#inpExtraPrice").val(eventList['additional_member_price']);
               
                $(".progress-bar").width('0%');
                $('#import_image').removeClass('is-invalid');
                
                $("#selServiceProvider").val(eventList['main_id']).trigger('change');
                $("#selServiceAdding").val(eventList['service_add']).trigger('change');
                
                getAllDoc(id);
                
                $("#inpServiceAddingOther").val(eventList['service_add_other']);
                changeServiceAdding();
                
                
                
                  var op3 = eventList['staff_types'].replace(/^,/, '');
                var valuesArray2 = op3.split(",");
                
        $("#selStaffType").val(valuesArray2).trigger('change');
              
            

            }
           
            
          
        }
        data = { "function": 'SystemManage',"method": "geteditProviderServiceList" ,"sel_id":id };
        
        apiCallForProvider(data,successFn);
        
        
        
        
      
  }
  
  
  function getAllDoc(id){
     
     $('#displayCompanyDocumentsDiv').html('');

     
     
    successFn = function(resp)  {
        // resp = JSON.parse(resp);
      
        if(resp.status == 1){
            var images = resp.data;
            imgCount = images.length;
            
            if(images.length > 0){
                
                var disD = '';
                var disD1 = '';

                
                   disD +='<div class="card ">';
                    disD +='<div class="card-body">';
                    disD +='<h4 class="card-title text-primary">';
                    disD +='<strong>Service images</strong> ';
                    disD +='</h4><br>';
                
                
                
                    
                
                for(var i=0;i<images.length;i++){
                    
                    var filepath = images[i]['file_path'];
                    disD +='<img src="'+filepath+'" width="100" height="auto"></img> <i onclick="deleteDocs('+images[i]['id']+','+id+');" class="fa fa-trash text-danger"></i>';

                }
                
                disD +='</div>';
                    disD +='</div>';
                
                
            }else{
                
                var disD = '';
                 disD +='<div class="card ">';
                    disD +='<div class="card-body">';
                    disD +='<h4 class="card-title text-primary">';
                    disD +='<strong>Service images</strong> ';
                    disD +='</h4><br>';
                disD +='<p class="text-muted">Service image not uploaded, Please update your service image</p>';
                disD +='</div>';
                    disD +='</div>';
                
             
                
                
            }
            
            $('#displayCompanyDocumentsDiv').html(disD);
            
            
        }
        
    }
    data = { "function": 'SystemManage',"method": "getAllServiceImages",'selectedServiceId':id };
    
    apiCallForProvider(data,successFn);
 }
 
 
 function deleteDocs(id,Eid){
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
                        getAllDoc(Eid);
                     }
                     data = { "function": 'SystemManage',"method": "deleteServiceImages" ,"sel_id":id };
                     apiCallForProvider(data,successFn);
                 }
         });
}
 
 
  
  

function deleteState(id){
     return new swal({
             title: "Are you sure?",
             text: "You want to delete this Service",
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
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });
                             emptyForm();
                            getStateListData();
                             
                         }else{
                             Swal.fire({
                                 icon: 'error',
                                 title: resp.data,
                                 showConfirmButton: false,
                                 timer: 1500
                             });
                         }
                     }
                     data = { "function": 'SystemManage',"method": "deleteProviderService" ,"sel_id":id };
                     apiCallForProvider(data,successFn);
                 }
         });
}
 
 
 
    
    
</script>





