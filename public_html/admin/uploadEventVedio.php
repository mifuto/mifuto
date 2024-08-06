<?php

require_once('classes/sendMailClass.php');
require_once('classes/DashboardClass.php');
require_once('config.php');

require_once('classes/vendor/autoload.php');


use Aws\S3\S3Client;
use Aws\Exception\AwsException;

 $s3Client = new S3Client([
    'version'     => 'latest',
    'region' => 'ap-south-2', // e.g., us-east-1
    'credentials' => [
        'key'    => AWS_KEY,
        'secret' => AWS_SECRET,
        'region' => AWS_REGION, // e.g., us-east-1
    ],
]);
    
$bucketName = AWS_BUCKET;


// upload.php
if ($_FILES) {
    
    $AlbumId = $_REQUEST['selCoverId'];
    $targetDir = $_REQUEST['file_folder'];
    
    
    $chkImgName = $_FILES['images']['name'][0];
	

	$chkSqlImg = "SELECT id FROM tbeeventalbumvedio_folderfiles WHERE `file_name`='$chkImgName' AND `album_id`='$AlbumId' ";
	$chkSqlImgList = $dbc->get_rows($chkSqlImg);
	
	if(sizeof($chkSqlImgList) == 0){
    
        
    	$filename = $_FILES['images']['name'][0];
    	$filesize = $_FILES['images']['size'][0]; 
    	
    	
    	
    	$targetDir = $targetDir."/vedios/";
    	$targetFilePath = $targetDir . $filename;
    
    	$imagePath = $_FILES['images']['tmp_name'][0];
        $targetDirectory = $targetDir;
        
        
    
       try {
            // Upload the file to S3
            $out = $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key'    => $targetFilePath,
                'SourceFile' => $imagePath,
            ]);
            
            $targetFilePathUrl = $out['ObjectURL'];
        
           
        } catch (AwsException $e) {
            // Handle errors
            // echo 'Error uploading image: ' . $e->getMessage();
            echo 'No files were uploaded.';
            die;
        }
        
         $qry1 = "INSERT INTO `tbeeventalbumvedio_folderfiles`(`file_name`, `file_size`, `album_id`,`file_path`) VALUES ('$filename','$filesize','$AlbumId','$targetFilePathUrl')";
    	$result = $dbc->insert_row($qry1);
               
    	
    	echo $targetFilePathUrl;
	}else{
        echo 'Already uploading vedio.';
	}
	
	
} else {
    // No files provided
    echo 'No files were uploaded.';
}


?>