<?php 

require_once('admin/config.php'); 

$DBC = mysqli_connect(HOST, DB_USER, DB_PASS,DB_NAME);

$projIdString = str_rot13($_REQUEST['eventID']);
$projIdString = base64_decode($projIdString);

$arr = explode('_', $projIdString);
$eventID = $arr[1];


$sql3 = "SELECT a.*,c.name,b.inpEventTime,b.inpEventDate FROM tbeeventalbum_data a left join place_order_userservices b on a.project_id = b.id left join tblprovider_services c on c.id = b.inpServiceID where a.deleted=0 and a.id=".$eventID." and b.service_status =4 order by a.id desc "; 

$result3 = $DBC->query($sql3);

$event = mysqli_fetch_assoc($result3);

 $time = $event['inpEventTime'];
     $time = new DateTime($time);
    $amPmTime = $time->format('h:i A');
    

$AlbumsList = [];
$sql31 = "SELECT a.* FROM tbeeventalbum_folderfiles a where a.hide=0 and a.album_id ='$eventID' order by a.file_name asc ";

$result31 = $DBC->query($sql31);

$count31 = mysqli_num_rows($result31);

if($count31 > 0) {		
    while ($row31 = mysqli_fetch_assoc($result31)) {
        array_push($AlbumsList,$row31);
      
    }
}

    
$VAlbumsList = [];
$sql32 = "SELECT a.* FROM tbeeventalbumvedio_folderfiles a where a.hide=0 and a.album_id ='$eventID' order by a.file_name asc ";

$result32 = $DBC->query($sql32);

$count32 = mysqli_num_rows($result32);

if($count32 > 0) {		
    while ($row32 = mysqli_fetch_assoc($result32)) {
        array_push($VAlbumsList,$row32);
      
    }
}

$file_folder = $event['file_folder'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    
     <meta charset="UTF-8">
        <title>MIfuto-online photographer booking</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        
         <link rel="shortcut icon" href="images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- lightGallery CSS -->
  
    <link rel="stylesheet" href="/dist/css/lightbox.min.css">
    <style>
        .logo-header {
            background-color: #18458B;
            padding: 10px;
            position: absolute;
            top: 20px;
            left: 20px;
            border-radius: 5px;
        }
        .cover-image {
            position: relative;
            width: 100%;
            height: 100vh;
            background: url('<?=$event['cover_image_path']?>') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cover-content {
            text-align: center;
            color: white;
            padding-top: 10%;
            
        }
        
        .cover-content-elm {
            background-color: #18458B;
            padding: 5%;
            opacity:.8;
            border-radius: 5px;

        }
        
         .err-content {
            text-align: center;
            color: red;
            padding: 5%;
            
        }
        
        .footer-bg{
            background-color: #18458B;
        }
        
        .img-view{
            padding-right: 2px; 
            padding-left: 2px;
        }
        
        
         .nav-tabs {
            position: relative;
        }
        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }
        .nav-tabs .nav-link {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-right: 0.125rem;
        }
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .nav-tabs .nav-link:hover {
            border-color: #0056b3;
            color: #0056b3;
        }
        .nav-tabs .right-button {
            position: absolute;
            right: 0;
            top: 0;
        }
        .nav-tabs .right-button button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
        }
        .nav-tabs .right-button button:hover {
            background-color: #0056b3;
        }
        
       
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <!-- Cover Image with Title and Logo -->
    <div class="cover-image">
        <div class="logo-header">
            <img src="images/logo.png" alt="Logo" class="img-fluid" style="width: 100px; height: auto;">
        </div>
        <div class="cover-content">
            <div class="cover-content-elm">
                <h1><?=strtoupper($event['folder_name'])?></h1>
                <p><?=$event['name']?> - <?=$event['inpEventDate']?> <?=$amPmTime?></p>
                
            </div>
            
        </div>
    </div>
    
    <!-- Tabs -->
   <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="true">Images</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="videos-tab" data-toggle="tab" href="#videos" role="tab" aria-controls="videos" aria-selected="false">Videos</a>
        </li>
       
    </ul>
    
    <div class="tab-content" id="myTabContent">
            <!-- Images Tab -->
        <div class="tab-pane fade show active" id="images" role="tabpanel" aria-labelledby="images-tab">
            <div class="row mt-2">
                <?php if(count($AlbumsList) > 0) { ?>
                    <div class="col-12 mb-2" align="right">
                        <div class="right-button">
                            <button class="btn btn-sm btn-success" onclick="downloadFolder(1)">Download Images</button>
                        </div>
                    </div>
                    
                    
                <?php     foreach ($AlbumsList as $key => $album) { ?>
                        <div class="col-3 mb-1 img-view" >
                            <a class="example-image-link" href="<?=$album['file_path']?>" data-lightbox="example-set" data-title="<?=$album['file_name']?>">
                                <img class="example-image img-fluid" src="<?=$album['thumb_image_path']?>" alt=""/>
                            </a>
                        </div>
                <?php } } else { ?>
                    <div class="col-12">
                        <h3 class="err-content">You have no images available.</h3>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Videos Tab -->
        <div class="tab-pane fade" id="videos" role="tabpanel" aria-labelledby="videos-tab">
            <div class="row mt-2">
                <?php if(count($VAlbumsList) > 0) { 
                    
                    ?>
                    <div class="col-12 mb-2" align="right">
                        <div class="right-button">
                            <button class="btn btn-sm btn-success" onclick="downloadFolder(2)">Download Vedios</button>
                        </div>
                    </div>
                    
                    
                <?php
                    foreach ($VAlbumsList as $key => $album) { ?>
                        <div class="col-4 mb-1 img-view">
                            <div class="embed-responsive embed-responsive-16by9">
                                <video class="embed-responsive-item" controls>
                                    <source src="<?=$album['file_path']?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                <?php } } else { ?>
                    <div class="col-12">
                        <h3 class="err-content">You have no videos available.</h3>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


 <!-- Footer -->
    <footer class="footer-bg text-white py-3 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-6 text-left">
                    <p class="mb-0">&copy;  MIfuto 2024 . All rights reserved.</p>
                </div>
                <div class="col-6 text-right">
                    <a href="https://mifuto.com" class="text-white">mifuto.com</a>
                </div>
            </div>
        </div>
    </footer>





<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script src="/dist/js/lightbox-plus-jquery.min.js"></script>

<script>

 function downloadFolder(mode) {
    var folderName = '<?=$file_folder?>'; // Replace with actual folder name or dynamic value
    window.location.href = `/files/downloadFolder.php?folder=${encodeURIComponent(folderName)}&mode=${encodeURIComponent(mode)}`;
}
   
   
   
</script>
</body>
</html>







