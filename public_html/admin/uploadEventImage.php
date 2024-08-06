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
    
    $AlbumId = $_REQUEST['selectedUplSigAlbmId'];
	$targetDir = $_REQUEST['selectedUplSigfile_folder'];
	$uploadsDirectory = SIGNATUREALBUM_UPLOAD_PATH;
	
	$chkImgName = $_FILES['images']['name'][0];
	

	$chkSqlImg = "SELECT id FROM tbesignalbm_folderfiles WHERE `file_name`='$chkImgName' AND `album_id`='$AlbumId' ";
	$chkSqlImgList = $dbc->get_rows($chkSqlImg);

	if(sizeof($chkSqlImgList) == 0){
	    
	    
	
		$filename = $_FILES['images']['name'][0];
		$filesize = $_FILES['images']['size'][0]; 
		
		
		
		$targetDir = $targetDir."/";
		$targetFilePath = $targetDir ."images/". $filename;
		$thumbnailsFilePath = $targetDir ."thumbnails/". $filename;
		
		$imagePath = $_FILES['images']['tmp_name'][0];
        $targetDirectory = $targetDir;
        
       
        
        // move_uploaded_file($imagePath, $targetFilePath);
        
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
        
        
        
        
        
           
        // $targetSizeMB = 0.5;
        
        // // Convert target size from MB to bytes
        // $targetSizeBytes = $targetSizeMB * 1024 * 1024;
    
        // // Load the image
        // $image = imagecreatefromjpeg($imagePath);
    
        // // Initialize quality and compression variables
        // $quality = 90;
        // $compressedImage = null;
    
        // // Loop until the image size is less than the target size
        // while (filesize($imagePath) > $targetSizeBytes) {
        //     // Create a temporary image with reduced quality
        //     ob_start();
        //     imagejpeg($image, null, $quality);
        //     $compressedImageData = ob_get_clean();
    
        //     // Save the compressed image data to a temporary file
        //     $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
        //     file_put_contents($tempImagePath, $compressedImageData);
    
        //     // Check the size of the temporary compressed image
        //     $tempFileSize = filesize($tempImagePath);
    
        //     if ($tempFileSize <= $targetSizeBytes) {
        //         // The temporary image is within the target size
        //         $compressedImage = imagecreatefromjpeg($tempImagePath);
        //         unlink($imagePath); // Delete the original image
        //         rename($tempImagePath, $imagePath); // Replace with the compressed image
        //         break;
        //     }
    
        //     // Reduce the quality and continue the loop
        //     $quality -= 10;
    
        //     // If quality becomes too low, break the loop to prevent infinite looping
        //     if ($quality < 10) {
        //         break;
        //     }
        // }
    
        // // Clean up resources
        // imagedestroy($compressedImage);
    
       imagickImage($imagePath,3072, 60 );
        // move_uploaded_file($imagePath, $targetFilePath);
        
        try {
            // Upload the file to S3
            $out = $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key'    => $thumbnailsFilePath,
                'SourceFile' => $imagePath,
            ]);
            
            $thumbnailsFilePath = $out['ObjectURL'];
        
           
        } catch (AwsException $e) {
            // Handle errors
            // echo 'Error uploading image: ' . $e->getMessage();
            echo 'No files were uploaded.';
            die;
        }
        
        
        // copy($targetFilePath, $thumbnailsFilePath);
        // imagickImage($thumbnailsFilePath,3072, 60 );
        
        $qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`,`file_path`,`thumb_image_path`) VALUES ('$filename','$filesize','$AlbumId','$targetFilePathUrl','$thumbnailsFilePath')";
		$result = $dbc->insert_row($qry1);
           
			

		echo $targetFilePath;
		
	
	}else{
        echo 'Already uploading image.';
	}
	
	
	
} else {
    // No files provided
    echo 'No files were uploaded.';
}



function imagickImage($imgFilePath,$cDim, $quality ){
    
		$Cvrimage = new Imagick($imgFilePath);
	
		$originalWidth = $Cvrimage->getImageWidth();
        $originalHeight = $Cvrimage->getImageHeight();
        
        // $cDim = 1024.0;
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;
        try {
            if($originalWidth > $cDim || $originalHeight > $cDim) {
                if($originalWidth > $originalHeight) {
                    $newWidth = $cDim;
                    $newHeight = (int)((float)$originalHeight / (float)$originalWidth * $cDim);
                } else {
                    $newHeight = $cDim;
                    $newWidth = (int)((float)$originalWidth / (float)$originalHeight * $cDim);
                }
            }
        } catch(Exception $e) {
            var_dump($e);
        }
        // $quality = 80;
        $Cvrimage->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);
        $Cvrimage->setImageCompressionQuality($quality);
        $Cvrimage->writeImage($imgFilePath);
        $Cvrimage->destroy();
}








?>