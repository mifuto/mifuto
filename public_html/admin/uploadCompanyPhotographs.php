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
    
    $userId = $_REQUEST['userId'];
   
	$chkImgName = $_FILES['images']['name'][0];
	
	$filename = $_FILES['images']['name'][0];
	$filesize = $_FILES['images']['size'][0]; 
	
	$targetDir = "companyPhotographs/company_".$userId."/";
	$targetFilePath = $targetDir .'img_'.time(). $filename;

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
    
    $qry1 = "INSERT INTO `tbephotographs_folderfiles` (`user_id`, `file_path`) VALUES ('$userId','$targetFilePathUrl')";
	$result = $dbc->insert_row($qry1);
    
	echo $targetFilePathUrl;
	
	
} else {
    // No files provided
    echo 'No files were uploaded.';
}


?>