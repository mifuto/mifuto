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
    
    $inpEmail = $_REQUEST['inpEmail'];
   
	$chkImgName = $_FILES['images']['name'][0];
	
	$filename = $_FILES['images']['name'][0];
	$filesize = $_FILES['images']['size'][0]; 
	
	$targetDir = "companyLogos/";
	$targetFilePath = $targetDir .'logo_'.time(). $filename;

	$imagePath = $_FILES['images']['tmp_name'][0];

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
    
    $sql6 = "UPDATE tblprovideruserlogin SET `company_logo_url` = '$targetFilePathUrl' WHERE `email` = '$inpEmail' ";
    $result = $dbc->update_row($sql6);
  
	echo $targetFilePathUrl;
	
	
} else {
    // No files provided
    echo 'No files were uploaded.';
}


?>