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

$logedUserID = $_SESSION['MachooseAdminUser']['id'];

$sql3 = "SELECT a.*,b.state,c.city,cu.short_name FROM tblmifutostaffuserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id left join tblcountries cu on cu.country_id = a.county_id WHERE a.id='$logedUserID'  ";
$result3 = $DBC->query($sql3);
$rowU = mysqli_fetch_assoc($result3);

// include("header.php");


?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Profile </li>
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
        
                        <p class="text-muted text-center">Service Provider Staff</p>
        
                    
        
                        <a class="btn btn-secondary btn-block" data-toggle="modal" data-target="#modal-default"><b>Update profile pic</b></a>
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
                              <?=$rowU['name']?> <?=$rowU['lastname']?>
                            </p>
            
                            <hr>
                            
            
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            
                            <p class="text-muted"><?=$rowU['city']?>, <?=$rowU['state']?>, <?=$rowU['short_name']?></p>
            
                            <hr>
            
                            <strong><i class="fas fa-phone mr-1"></i> Phone</strong>
            
                            <p class="text-muted">
                              <?=$rowU['phone']?>
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
                              <li class="nav-item" ><a class="nav-link active" id="pre" href="#Profile" data-toggle="tab" onclick="updateProfile();">PROFILE</a></li>
                              <li class="nav-item" ><a class="nav-link"  href="#password" data-toggle="tab" onclick="updateProfile();">PASSWORD CHANGE</a></li>
                              <li class="nav-item" ><a class="nav-link" id="cert" href="#certificate" data-toggle="tab" onclick="updateProfile();">UPLOAD CERTIFICATES</a></li>
                            </ul>
                          </div><!-- /.card-header -->
                          
                            <div class="card-body">
                                <div class="tab-content">
                                    
                                    <div class="active tab-pane" id="Profile">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                        
                                        
                                        
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">Enter first name</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="inpName" name="inpName" placeholder="Enter first name" value="<?=$rowU['name']?>">
                                
                                                        <div class="invalid-feedback">
                                                        Please enter the first name!.
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">Enter last name</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" id="inpName2" name="inpName2" placeholder="Enter last name" value="<?=$rowU['lastname']?>">
                                
                                                        <div class="invalid-feedback">
                                                        Please enter the last name!.
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
                                                
                                                
                                                <div class="row mb-3 d-none">
                                                    <label for="" class="col-12 col-form-label">Service Center Type</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selServiceCenter" name="selServiceCenter">
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the Service Center Type!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                    <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter phone</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="inpPhone" name="inpPhone" placeholder="Enter phone number" value="<?=$rowU['phone']?>">
                                    
                                                            <div class="invalid-feedback">
                                                            Please enter the phone number!.
                                                            </div>
                                                        </div>
                                                    </div>
                                    
                                                
                                                <div class="row mb-3">
                                                    <label for="" class="col-12 col-form-label">Gender</label>
                                                   
                                                    <div class="col-12">
                                                        
                                                         <select class="form-control select2" aria-label="Default select example" id="selGender" name="selGender" >
                                                             
                                                             <option value="" selected>Select Gender</option>
                                                             
                                                             <?php 
                                                             if($rowU['gender'] == 'Male') echo '<option value="Male" selected>Male</option>';
                                                             else echo '<option value="Male">Male</option>';
                                                             
                                                             if($rowU['gender'] == 'Female') echo '<option value="Female" selected>Female</option>';
                                                             else echo '<option value="Female">Female</option>';
                                                             
                                                             if($rowU['gender'] == 'Other') echo '<option value="Other" selected>Other</option>';
                                                             else echo '<option value="Other">Other</option>';
                                                             
                                                             ?>
                                                             
                                                          
                                                            </select>
                                                        
                                                        
                                                        
                                                        <div class="invalid-feedback">
                                                        Please select the gender!.
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                   <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter DOB</label>
                                                        <div class="col-12">
                                                            <input type="date" class="form-control" id="inpDOB" name="inpDOB" value="<?=$rowU['dob']?>">
                                    
                                                            <div class="invalid-feedback">
                                                            Please select DOB!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter address</label>
                                                        <div class="col-12">
                                                            <textarea class="form-control" id="inpAddress" name="inpAddress" placeholder="Enter address"><?=$rowU['address']?></textarea>
                                                            
                                                            <div class="invalid-feedback">
                                                            Please enter the address!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    
                                                     <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter postal code</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="inpZip" name="inpZip" placeholder="Enter postal code" value="<?=$rowU['zip']?>">
                                    
                                                            <div class="invalid-feedback">
                                                            Please enter the postal code!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                     <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter photography business name</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="inpPBN" name="inpPBN" placeholder="Enter photography business name" value="<?=$rowU['pbn']?>">
                                    
                                                            <div class="invalid-feedback">
                                                            Please enter the photography business name!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                     <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Website/Portfolio URL</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" id="inpWebsite" name="inpWebsite" placeholder="Enter Website/Portfolio URL" value="<?=$rowU['website']?>">
                                    
                                                            <div class="invalid-feedback">
                                                            Please enter the Website/Portfolio URL!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                     <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter specialization</label>
                                                        <div class="col-12">
                                                            <textarea class="form-control" id="inpSpecialization" name="inpSpecialization" placeholder="Types of photography (wedding, portrait, landscape,fashion)"><?=$rowU['specialization']?></textarea>
                                                            
                                                            <div class="invalid-feedback">
                                                            Please enter the specialization!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter experience level</label>
                                                        <div class="col-12">
                                                            <textarea class="form-control" id="inpExperienceLevel" name="inpExperienceLevel" placeholder="Number of years in photography or a brief description of experience"><?=$rowU['experience_level']?></textarea>
                                                            
                                                            <div class="invalid-feedback">
                                                            Please enter the experience level!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                      <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Enter biography</label>
                                                        <div class="col-12">
                                                            <textarea class="form-control" id="inpBiography" name="inpBiography" placeholder="Enter the biography"><?=$rowU['biography']?></textarea>
                                                            
                                                            <div class="invalid-feedback">
                                                            Please enter the biography!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                     <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Social media links</label>
                                                        <div class="col-12">
                                                            <textarea class="form-control" id="inpSocialMediaLinks" name="inpSocialMediaLinks" placeholder="Links to social media profiles (Instagram, Facebook, etc.)"><?=$rowU['social_media_links']?></textarea>
                                                            
                                                            <div class="invalid-feedback">
                                                            Please enter the social media links!.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <label for="" class="col-12 col-form-label">Certifications</label>
                                                        <div class="col-12">
                                                            
                                                            <input type="checkbox" <?php if( $rowU['AdobeCertification']  == 1) { echo 'checked'; } ?> name="selAdobeCertification" id="selAdobeCertification" > Adobe Certification <br>
                                                            
                                                            <input type="checkbox" <?php if( $rowU['PVCertifications']  == 1) { echo 'checked'; } ?> name="selPVCertifications" id="selPVCertifications" > Photography/videography Experience certificate <br>
                                                            
                                                            <input type="checkbox" <?php if( $rowU['PExpCertifications']  == 1) { echo 'checked'; } ?> name="selPExpCertifications" id="selPExpCertifications" > Any Previous Experience certificate <br>
                                                            
                                                            <input type="checkbox" <?php if( $rowU['EyeCertifications']  == 1) { echo 'checked'; } ?> name="selEyeCertifications" id="selEyeCertifications" > Eye Testing Certificate <br>
                                                            
                                                            <input type="checkbox" <?php if( $rowU['PCCertifications']  == 1) { echo 'checked'; } ?> name="selPCCertifications" id="selPCCertifications" > Police Clearance Certificate <br>
                                                            
                                                       
                                                          
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
                                    
                                    <div class=" tab-pane" id="certificate">
                                        
                                        <?php if( $rowU['ExperienceCertificate'] != "" || $rowU['user_status'] == 0  ){ ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                        
                                                <div class="row mb-3">
                                                    <div class="container ">
                                                        <div class="card p-4">
                                                            
                                                            <?php if($rowU['user_status'] == 0){ ?>
                                                            
                                                            <div class="custom-file">
                                                                <strong>Experience certificate<br>
                                                                <input type="file"  id="experienceCertificateFiles" name="experienceCertificateFiles[]" accept="image/*,.pdf" >
                                                                <div class="text-danger" id="experienceCertificateFilesErr"></div>
                                                               
                                                            </div>
                                                            <br>
                                                            
                                                            <div class="progress mt-3">
                                                                <!-- Update the ID to match the selector used in the JavaScript -->
                                                                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar1" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class=" mt-2">
                                                                <button type="button" class="btn btn-primary"  onclick="uploadExperienceCertificateNow();">Upload</button>
                                                             
                                                            </div>
                                                            
                                                            <?php } ?>
                                                            
                                                         
                                                            <?php if($rowU['ExperienceCertificate'] != ""){ ?>
                                                            
                                                                <div class="row mt-3">
                                                                <?php if($rowU['user_status'] == 0){ ?>
                                                               
                                                                <div class="col-12">
                                                                 <a href="<?=$rowU['ExperienceCertificate']?>" target="_blank">Open Experience certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-warning">&nbsp;&nbsp;Upload completed</i> 
                                                                 </div>
                                                                  <div class="col-12">
                                                                <span class="text-warning">Please wait as your verification processing is ongoing </span>
                                                                </div>
                                                                <?php }else{ ?>
                                                                 <a href="<?=$rowU['ExperienceCertificate']?>" target="_blank">Open Experience certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-success">&nbsp;&nbsp;Verified</i> 
                                                                <?php } ?>
                                                        
                                                                

                                                                </div>
                                                          
                                                                
                                                           <?php } ?>
                                                            
                                                                          
                    
                                                             
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                                    </div>
                                                    
                                                    
                                                </div>
                                                
                                               
                                                
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                        <?php if($rowU['EyeTestingCertificate'] != "" || $rowU['user_status'] == 0 ){ ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                        
                                                <div class="row mb-3">
                                                    <div class="container ">
                                                        <div class="card p-4">
                                                            
                                                            <?php if($rowU['user_status'] == 0){ ?>
                                                            <div class="custom-file">
                                                                <strong>Eye Testing Certificate<br>
                                                                <input type="file"  id="eyeTestingCertificateFiles" name="eyeTestingCertificateFiles[]" accept="image/*,.pdf" >
                                                                <div class="text-danger" id="eyeTestingCertificateFilesErr"></div>
                                                               
                                                            </div>
                                                            <br>
                                                            
                                                            <div class="progress mt-3">
                                                                <!-- Update the ID to match the selector used in the JavaScript -->
                                                                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar2" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class=" mt-2">
                                                                <button type="button" class="btn btn-primary"  onclick="uploadEyeTestingCertificateFilesNow();">Upload</button>
                                                             
                                                            </div>
                                                            <?php } ?>
                                                            
                                                            <?php if($rowU['EyeTestingCertificate'] != ""){ ?>
                                                            
                                                                 <div class="row mt-3">
                                                                <?php if($rowU['user_status'] == 0){ ?>
                                                               
                                                                <div class="col-12">
                                                                 <a href="<?=$rowU['EyeTestingCertificate']?>" target="_blank">Open Eye Testing certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-warning">&nbsp;&nbsp;Upload completed</i> 
                                                                 </div>
                                                                  <div class="col-12">
                                                                <span class="text-warning">Please wait as your verification processing is ongoing </span>
                                                                </div>
                                                                <?php }else{ ?>
                                                                 <a href="<?=$rowU['EyeTestingCertificate']?>" target="_blank">Open Eye Testing certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-success">&nbsp;&nbsp;Verified</i> 
                                                                <?php } ?>
                                                        
                                                                </div>
                                                          
                                                                
                                                           <?php } ?>
                                                            
                                                          
                    
                                                             
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                                    </div>
                                                    
                                                
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                        <?php if( $rowU['PoliceClearanceCertificate'] != "" || $rowU['user_status'] == 0  ){ ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                        
                                                <div class="row mb-3">
                                                    <div class="container ">
                                                        <div class="card p-4">
                                                            
                                                            <?php if($rowU['user_status'] == 0){ ?>
                                                            <div class="custom-file">
                                                                <strong>Police Clearance Certificate<br>
                                                                <input type="file"  id="policeClearanceCertificateFiles" name="policeClearanceCertificateFiles[]" accept="image/*,.pdf" >
                                                                <div class="text-danger" id="policeClearanceCertificateFilesErr"></div>
                                                               
                                                            </div>
                                                            <br>
                                                            
                                                            <div class="progress mt-3">
                                                                <!-- Update the ID to match the selector used in the JavaScript -->
                                                                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar3" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class=" mt-2">
                                                                <button type="button" class="btn btn-primary" onclick="uploadPoliceClearanceCertificateNow();">Upload</button>
                                                             
                                                            </div>
                                                            <?php } ?>
                                                            
                                                              <?php if($rowU['PoliceClearanceCertificate'] != ""){ ?>
                                                            
                                                                 <div class="row mt-3">
                                                                <?php if($rowU['user_status'] == 0){ ?>
                                                               
                                                                <div class="col-12">
                                                                 <a href="<?=$rowU['PoliceClearanceCertificate']?>" target="_blank">Open Police Clearance certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-warning">&nbsp;&nbsp;Upload completed</i> 
                                                                 </div>
                                                                  <div class="col-12">
                                                                <span class="text-warning">Please wait as your verification processing is ongoing </span>
                                                                </div>
                                                                <?php }else{ ?>
                                                                 <a href="<?=$rowU['PoliceClearanceCertificate']?>" target="_blank">Open Police Clearance certificate</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-success">&nbsp;&nbsp;Verified</i> 
                                                                <?php } ?>
                                                        
                                                                </div>
                                                          
                                                                
                                                           <?php } ?>
                                                          
                    
                                                             
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                                    </div>
                                                    
                                                
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                        <?php if( $rowU['Aadhar'] != "" || $rowU['user_status'] == 0  ){ ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                        
                                                <div class="row mb-3">
                                                    <div class="container ">
                                                        <div class="card p-4">
                                                            
                                                            <?php if($rowU['user_status'] == 0){ ?>
                                                            <div class="custom-file">
                                                                <strong>Aadhar<br>
                                                                <input type="file"  id="aadharFiles" name="aadharFiles[]" accept="image/*,.pdf" >
                                                                <div class="text-danger" id="aadharFilesErr"></div>
                                                               
                                                            </div>
                                                            <br>
                                                            
                                                            <div class="progress mt-3">
                                                                <!-- Update the ID to match the selector used in the JavaScript -->
                                                                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar4" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class=" mt-2">
                                                                <button type="button" class="btn btn-primary"  onclick="uploadAadharNow();">Upload</button>
                                                             
                                                            </div>
                                                            <?php } ?>
                                                            
                                                                  <?php if($rowU['Aadhar'] != ""){ ?>
                                                            
                                                                  <div class="row mt-3">
                                                                <?php if($rowU['user_status'] == 0){ ?>
                                                               
                                                                <div class="col-12">
                                                                 <a href="<?=$rowU['Aadhar']?>" target="_blank">Open Aadhar</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-warning">&nbsp;&nbsp;Upload completed</i> 
                                                                 </div>
                                                                  <div class="col-12">
                                                                <span class="text-warning">Please wait as your verification processing is ongoing </span>
                                                                </div>
                                                                <?php }else{ ?>
                                                                 <a href="<?=$rowU['Aadhar']?>" target="_blank">Open Aadhar</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-success">&nbsp;&nbsp;Verified</i> 
                                                                <?php } ?>
                                                        
                                                                </div>
                                                          
                                                                
                                                           <?php } ?>
                                                          
                                                          
                    
                                                             
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                                    </div>
                                                    
                                                
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        

                                        <?php if( $rowU['Passport'] != "" || $rowU['user_status'] == 0  ){ ?>
                                         <div class="row">
                                            <div class="col-md-12">
                                        
                                                <div class="row mb-3">
                                                    <div class="container ">
                                                        <div class="card p-4">
                                                            
                                                            <?php if($rowU['user_status'] == 0){ ?>
                                                            <div class="custom-file">
                                                                <strong>Passport<br>
                                                                <input type="file"  id="passportFiles" name="passportFiles[]" accept="image/*,.pdf" >
                                                                <div class="text-danger" id="passportFilesErr"></div>
                                                               
                                                            </div>
                                                            <br>
                                                            
                                                            <div class="progress mt-3">
                                                                <!-- Update the ID to match the selector used in the JavaScript -->
                                                                <div class="progress-bar progress-bar-striped bg-danger" id="progress-bar5" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class=" mt-2">
                                                                <button type="button" class="btn btn-primary"  onclick="uploadPassportNow();">Upload</button>
                                                             
                                                            </div>
                                                            <?php } ?>
                                                          
                                                          
                                                              <?php if($rowU['Passport'] != ""){ ?>
                                                            
                                                                   <div class="row mt-3">
                                                                <?php if($rowU['user_status'] == 0){ ?>
                                                               
                                                                <div class="col-12">
                                                                 <a href="<?=$rowU['Passport']?>" target="_blank">Open Passport</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-warning">&nbsp;&nbsp;Upload completed</i> 
                                                                 </div>
                                                                  <div class="col-12">
                                                                <span class="text-warning">Please wait as your verification processing is ongoing </span>
                                                                </div>
                                                                <?php }else{ ?>
                                                                 <a href="<?=$rowU['Passport']?>" target="_blank">Open Passport</a>&nbsp;&nbsp;
                                                                 <i class="nav-icon fa fa-check-circle text-success">&nbsp;&nbsp;Verified</i> 
                                                                <?php } ?>
                                                        
                                                                </div>
                                                                
                                                           <?php } ?>
                    
                                                             
                                                            
                                                        </div>
                                                        
                                                        
                                                       
                                                    </div>
                                                    
                                                
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
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
                  <h4 class="modal-title">Upload profile pic</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                
                <form id="uploadCompanyLogoForm" class="g-3 needs-validation" novalidate="">
                
                
                <div class="modal-body">
                    
                    
                    <div class="container ">
                        <div class="card p-4">
                            <div class="custom-file">
                                <strong>Upload profile pic<br>
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
    $('#navBookings').removeClass('active');
    $('#navProfile').addClass('active');
    $('#navFAQ').removeClass('active');
    $('#navTermsAndConditions').removeClass('active');
    
     var servicescenter_id = 1;
    
   
    
    $( document ).ready(function() {
    
     showProfile();
     updateProfile();

    });
    
     window.onload = function() {
            // Check if the reload flag is set
            if (localStorage.getItem('reloadPage') === 'true') {
                // Remove the flag from localStorage
                localStorage.removeItem('reloadPage');
                // Execute the desired function
                afterReloadFunction();
            }
        };

        function afterReloadFunction() {
            // Your function to be executed after page reload
            console.log('Page reloaded and function executed');
            $('#certificate').addClass('active');
            $('#Profile').removeClass('active');
            $('#password').removeClass('active');
            
            $('#cert').addClass('active');
            $('#pre').removeClass('active');
            
            
        }
        
        
        function updateProfileNow(){
      
       $('#changeProfileErr').addClass('d-none');
      
       $('#inpName').removeClass('is-invalid');
     $('#selCounty').removeClass('is-invalid');
     $('#selState').removeClass('is-invalid');
     $('#selCity').removeClass('is-invalid');
     
     
     $('#inpName2').removeClass('is-invalid');
     $('#inpPhone').removeClass('is-invalid');
     $('#selGender').removeClass('is-invalid');
     $('#inpDOB').removeClass('is-invalid');
     $('#inpAddress').removeClass('is-invalid');
     $('#inpZip').removeClass('is-invalid');
     $('#inpPBN').removeClass('is-invalid');
     $('#inpWebsite').removeClass('is-invalid');
     $('#inpExperienceLevel').removeClass('is-invalid');
     $('#inpBiography').removeClass('is-invalid');
     $('#inpSocialMediaLinks').removeClass('is-invalid');
     
     
    
     var inpName = $('#inpName').val();
     var selCounty = $('#selCounty').val();
     var selState = $('#selState').val();
     var selCity = $('#selCity').val();
     
     
     var inpName2 = $('#inpName2').val();
     var inpPhone = $('#inpPhone').val();
     var selGender = $('#selGender').val();
     var inpDOB = $('#inpDOB').val();
     var inpAddress = $('#inpAddress').val();
     var inpZip = $('#inpZip').val();
     var inpPBN = $('#inpPBN').val();
     var inpWebsite = $('#inpWebsite').val();
     var inpSpecialization = $('#inpSpecialization').val();
     var inpExperienceLevel = $('#inpExperienceLevel').val();
     var inpBiography = $('#inpBiography').val();
     var inpSocialMediaLinks = $('#inpSocialMediaLinks').val();
     
      if(inpName == ""){
         $('#inpName').addClass('is-invalid');
         $('#inpName').focus();
         return false;
     }
     
      if(inpName2 == ""){
         $('#inpName2').addClass('is-invalid');
         $('#inpName2').focus();
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
     
      if(inpPhone == ""){
         $('#inpPhone').addClass('is-invalid');
         $('#inpPhone').focus();
         return false;
     }
     
        if(selGender == ""){
         $('#selGender').addClass('is-invalid');
         $('#selGender').focus();
         return false;
     }
     
      
        if(inpDOB == ""){
         $('#inpDOB').addClass('is-invalid');
         $('#inpDOB').focus();
         return false;
     }
     
       
        if(inpAddress == ""){
         $('#inpAddress').addClass('is-invalid');
         $('#inpAddress').focus();
         return false;
     }
     
       
        if(inpZip == ""){
         $('#inpZip').addClass('is-invalid');
         $('#inpZip').focus();
         return false;
     }
     
        if(inpPBN == ""){
         $('#inpPBN').addClass('is-invalid');
         $('#inpPBN').focus();
         return false;
     }

 if(inpWebsite == ""){
         $('#inpWebsite').addClass('is-invalid');
         $('#inpWebsite').focus();
         return false;
     }

if(inpSpecialization == ""){
         $('#inpSpecialization').addClass('is-invalid');
         $('#inpSpecialization').focus();
         return false;
     }

if(inpExperienceLevel == ""){
         $('#inpExperienceLevel').addClass('is-invalid');
         $('#inpExperienceLevel').focus();
         return false;
     }

if(inpBiography == ""){
         $('#inpBiography').addClass('is-invalid');
         $('#inpBiography').focus();
         return false;
     }

if(inpSocialMediaLinks == ""){
         $('#inpSocialMediaLinks').addClass('is-invalid');
         $('#inpSocialMediaLinks').focus();
         return false;
     }
     
     
     var selAdobeCertification = 0;
    var selPVCertifications = 0;
    var selPExpCertifications = 0;
    var selEyeCertifications = 0;
    var selPCCertifications = 0;
    
    if (document.getElementById('selAdobeCertification').checked) {
        selAdobeCertification = 1;
    }
    
    if (document.getElementById('selPVCertifications').checked) {
        selPVCertifications = 1;
    }
    
     if (document.getElementById('selPExpCertifications').checked) {
        selPExpCertifications = 1;
    }
    
     if (document.getElementById('selEyeCertifications').checked) {
        selEyeCertifications = 1;
    }

 if (document.getElementById('selPCCertifications').checked) {
        selPCCertifications = 1;
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
    data = { "function": 'User',"method": "updateServiceProviderStaffProfile" ,'name':inpName ,'county':selCounty,'state':selState ,'city':selCity ,'inpName2':inpName2,'inpPhone':inpPhone,'selGender':selGender,'inpDOB':inpDOB,'inpAddress':inpAddress,'inpZip':inpZip,'inpPBN':inpPBN,'inpWebsite':inpWebsite,'inpSpecialization':inpSpecialization,'inpExperienceLevel':inpExperienceLevel,'inpBiography':inpBiography,'inpSocialMediaLinks':inpSocialMediaLinks  ,'selAdobeCertification':selAdobeCertification,'selPVCertifications':selPVCertifications,'selPExpCertifications':selPExpCertifications,'selEyeCertifications':selEyeCertifications,'selPCCertifications':selPCCertifications };
    
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
    //   getServiceCenter('selServiceCenter');
       
       
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
    data = { "function": 'User',"method": "changeServiceProviderStaffPassword" ,"password":inpPassword, 'oldPassword':inpOldPassword };
    
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
  
  function uploadPoliceClearanceCertificateNow(){
      var inpEmail = '<?=$rowU['email']?>';

     $("#policeClearanceCertificateFilesErr").html("");
     
     var files = document.getElementById("policeClearanceCertificateFiles").files;
     if (files.length > 0) {
         
         
         let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      

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
                            $("#progress-bar3").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar3").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadPoliceClearanceCertificateForStaff.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar3").width('0%');
                    
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // window.location.reload();
                    // Set a flag in localStorage to indicate the page is reloading
                    localStorage.setItem('reloadPage', 'true');
                    // Reload the page
                    window.location.reload();
                    
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
               
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
         
     }else{
        $("#policeClearanceCertificateFilesErr").html("Please upload Police Clearance Certificate");
        return false;
    }
     
     
     
  }
  
  
  function uploadAadharNow(){
      var inpEmail = '<?=$rowU['email']?>';

     $("#aadharFilesErr").html("");
     
     var files = document.getElementById("aadharFiles").files;
     if (files.length > 0) {
         
         
         let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      

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
                            $("#progress-bar4").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar4").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadAadharForStaff.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar4").width('0%');
                    
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // window.location.reload();
                    // Set a flag in localStorage to indicate the page is reloading
                    localStorage.setItem('reloadPage', 'true');
                    // Reload the page
                    window.location.reload();
                    
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
               
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
         
     }else{
        $("#aadharFilesErr").html("Please upload Aadhar");
        return false;
    }
     
     
     
  }
  
  function uploadEyeTestingCertificateFilesNow(){
      var inpEmail = '<?=$rowU['email']?>';

     $("#eyeTestingCertificateFilesErr").html("");
     
     var files = document.getElementById("eyeTestingCertificateFiles").files;
     if (files.length > 0) {
         
         
         let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      

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
                            $("#progress-bar2").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar2").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadEyeTestingCertificateForStaff.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar2").width('0%');
                    
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // window.location.reload();
                    // Set a flag in localStorage to indicate the page is reloading
                    localStorage.setItem('reloadPage', 'true');
                    // Reload the page
                    window.location.reload();
                    
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
               
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
         
     }else{
        $("#eyeTestingCertificateFilesErr").html("Please upload Eye Testing Certificate");
        return false;
    }
     
     
     
  }
  
  
  
   function uploadPassportNow(){
      var inpEmail = '<?=$rowU['email']?>';

     $("#passportFilesErr").html("");
     
     var files = document.getElementById("passportFiles").files;
     if (files.length > 0) {
         
         
         let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      

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
                            $("#progress-bar5").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar5").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadPassportForStaff.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar5").width('0%');
                    
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // window.location.reload();
                    // Set a flag in localStorage to indicate the page is reloading
                    localStorage.setItem('reloadPage', 'true');
                    // Reload the page
                    window.location.reload();
                    
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
               
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
         
     }else{
        $("#passportFilesErr").html("Please upload Passport");
        return false;
    }
     
     
     
  }
  
  
  function uploadExperienceCertificateNow(){
      var inpEmail = '<?=$rowU['email']?>';

     $("#experienceCertificateFilesErr").html("");
     
     var files = document.getElementById("experienceCertificateFiles").files;
     if (files.length > 0) {
         
         
         let file = files[0];
        let formData = new FormData();
        
        var fileSizeInBytes = file.size;
        var fileSizeInKB = fileSizeInBytes / 1024;
        
      

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
                            $("#progress-bar1").width(percentComplete.toFixed(0) + '%');
                            $("#progress-bar1").html(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '/admin/uploadExperienceCertificateForStaff.php', // Replace with your PHP upload script
                type: 'POST',
                beforeSend: function(){
                    $("#progress-bar1").width('0%');
                    
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // window.location.reload();
                    // Set a flag in localStorage to indicate the page is reloading
                    localStorage.setItem('reloadPage', 'true');
                    // Reload the page
                    window.location.reload();
                    
                },
                error: function () {
                    
                    Swal.fire(
                      'Error',
                      "Something went wrong, please try again",
                      'error'
                    )
                   
               
                    return false;
                }
            });
        };
        reader.readAsDataURL(file);
        
        
         
         
         
         
         
         
     }else{
        $("#experienceCertificateFilesErr").html("Please upload Experience Certificate");
        return false;
    }
     
     
     
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
                url: '/admin/uploadProfilePicForStaff.php', // Replace with your PHP upload script
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
        $("#uploadLogoFilesErr").html("Please upload profile pic");
        $("#submitButton13").removeClass("d-none");
        $("#submitLoadingButton13").addClass("d-none");
        return false;
    }
     
     
    
     
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
  
  function getServiceCenter(selectId,val="") {
     
 

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
        
        
  
 
    
</script>





