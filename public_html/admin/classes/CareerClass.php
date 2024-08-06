<?php
require_once('DashboardClass.php');
require_once('sendMailClass.php');


class Career {
    private $dbc;
    private $error_message;

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	}

    

    public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}
	
   
    public function saveCareer(){
        
         
        $data=array();
        $data["tittle"]=$_REQUEST['eventTitle'];
        $data["inputJobId"]=$_REQUEST['inputJobId'];
        
        
		$data["sub_tittle"]=str_replace("'", '"', $_REQUEST['eventDescription']);
		$data["state"]=$_REQUEST['inputState'];
		$data["district"]=$_REQUEST['inputDistrict'];
		$data["city"]=$_REQUEST['inputCity'];
		
		$data["County"]=$_REQUEST['inputCounty'];
		
		$data["jobsummary"]=str_replace("'", '"', $_REQUEST['jobsummary']);
		$data["Workigconditions"]=$_REQUEST['Workigconditions'];
		$data["Jobduties"]=$_REQUEST['Jobduties'];
		$data["Qualifications"]=$_REQUEST['Qualifications'];
		$data["Experience"]=$_REQUEST['Experience'];
		
		$data["Skills"]=str_replace("'", '"', $_REQUEST['Skills']);
		$data["Responsibiities"]=str_replace("'", '"', $_REQUEST['Responsibiities']);
        
        $coverImage = $_FILES['EventCoverImgFile'];
        $uploadDidectory = CAREER_UPLOAD_PATH;
        $t=time();
        
        $countfiles = $_FILES['EventCoverImgFile']['name'][0];
       
        if($countfiles != ""){
            
            $removedSpacesString = str_replace(' ', '', $coverImage['name'][0]);
            
              
        $coverImgDirectoryImagePath = $uploadDidectory.$t.'_'.$removedSpacesString;
        

         $imagePath1 = $coverImage['tmp_name'][0];

            $targetFilePath1 = $coverImgDirectoryImagePath;
            
            $targetSizeMB = 3;
            
            // Convert target size from MB to bytes
            $targetSizeBytes = $targetSizeMB * 1024 * 1024;
        
            // Load the image
            $image = imagecreatefromjpeg($imagePath1);
        
            // Initialize quality and compression variables
            $quality = 90;
            $compressedImage = null;
        
            // Loop until the image size is less than the target size
            while (filesize($imagePath1) > $targetSizeBytes) {
                // Create a temporary image with reduced quality
                ob_start();
                imagejpeg($image, null, $quality);
                $compressedImageData = ob_get_clean();
        
                // Save the compressed image data to a temporary file
                $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
                file_put_contents($tempImagePath, $compressedImageData);
        
                // Check the size of the temporary compressed image
                $tempFileSize = filesize($tempImagePath);
        
                if ($tempFileSize <= $targetSizeBytes) {
                    // The temporary image is within the target size
                    $compressedImage = imagecreatefromjpeg($tempImagePath);
                    unlink($imagePath1); // Delete the original image
                    rename($tempImagePath, $imagePath1); // Replace with the compressed image
                    break;
                }
        
                // Reduce the quality and continue the loop
                $quality -= 10;
        
                // If quality becomes too low, break the loop to prevent infinite looping
                if ($quality < 10) {
                    break;
                }
            }
        
            // Clean up resources
            imagedestroy($compressedImage);
        
           
            move_uploaded_file($imagePath1, $targetFilePath1);
		
		
		    $data["image"]=$targetFilePath1;
		
        
        
        
        
        
        }
        
     

		if($_REQUEST['hiddenEventId']=='' ){
		  
			$result = $this->dbc->InsertUpdate($data, 'tbl_career');

		}else{
		    
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tbl_career', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getCareerList(){
	    
	
		$sql = "SELECT a.*,b.position_name,b.position_code FROM tbl_career a left join tblhr_job_position b on a.tittle = b.position_id
			WHERE a.active=0 ORDER BY a.id DESC";
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditCareerList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbl_career
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deletecarrer(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbl_career` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete career");
        else self::sendResponse("2", "Failed to delete career");
	
	}
	
	public function Disablecarrer(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbl_career` SET `disabled`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully disable career");
        else self::sendResponse("2", "Failed to disable career");
	
	}
	
	public function Enablecarrer(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$currentDate = date("Y-m-d");
	
        $query = "UPDATE `tbl_career` SET `disabled`='0',`created_date`='$currentDate' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully enable career");
        else self::sendResponse("2", "Failed to enable career");
	
	}
		
	public function getjobs(){
	    
	  
		$sql = "SELECT * FROM tblhr_job_position order by position_name ASC";
		$result = $this->dbc->get_rows($sql);
        
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function applyJob(){
	   
         
        $data=array();
        $data["Name"]=$_REQUEST['Name'];
        $data["Email"]=$_REQUEST['Email'];
		$data["Phone1"]=$_REQUEST['Phone1'];
		$data["Phone2"]=$_REQUEST['Phone2'];
		$data["Address1"]=$_REQUEST['Address1'];
		$data["Address2"]=$_REQUEST['Address2'];
		$data["Nationality"]=$_REQUEST['Nationality'];
		$data["State"]=$_REQUEST['inputState'];
		$data["District"]=$_REQUEST['inputDistrict'];
		
		$data["Experienece"]=$_REQUEST['Experienece'];
		$data["Education"]=$_REQUEST['Education'];
		$data["AboutUs"]=$_REQUEST['AboutUs'];
		
		$data["JobId"]=$_REQUEST['jobId'];
		$data["camera"]=$_REQUEST['camera'];
		
		$data["SocialMedia1"]=$_REQUEST['SocialMedia1'];
		$data["SocialMedia2"]=$_REQUEST['SocialMedia2'];
		
		$data["PersonalWeb"]=$_REQUEST['PersonalWeb'];
		$data["OtherMedia"]=$_REQUEST['OtherMedia'];
	
		
		
        $uploadDidectory = CAREER_DATA_UPLOAD_PATH;
        $t=time();
        
        $CV = $_FILES['uploadCV'];
    	$filename = $CV['name'];
		$fileTempName = $CV['tmp_name'];
		$ImgFilePath = $uploadDidectory."/".$t.'_'.$filename;
		move_uploaded_file($fileTempName, $ImgFilePath);
		$data["uploadCV"]=$ImgFilePath;
		
		$uploadAadhar = $_FILES['uploadAadhar'];
    	$filename1 = $uploadAadhar['name'];
		$fileTempName1 = $uploadAadhar['tmp_name'];
		$ImgFilePath1 = $uploadDidectory."/".$t.'_'.$filename1;
		move_uploaded_file($fileTempName1, $ImgFilePath1);
		$data["uploadAadhar"]=$ImgFilePath1;
		
		$uploadPassport = $_FILES['uploadPassport'];
    	$filename2 = $uploadPassport['name'];
		$fileTempName2 = $uploadPassport['tmp_name'];
		$ImgFilePath2 = $uploadDidectory."/".$t.'_'.$filename2;
		move_uploaded_file($fileTempName2, $ImgFilePath2);
		$data["uploadPassport"]=$ImgFilePath2;
		
		
		
		$jobId = $_REQUEST['jobId'];
		
		$sqlj = "SELECT a.*,b.position_name,b.position_code FROM tbl_career a left join tblhr_job_position b on a.tittle = b.position_id
			WHERE a.id='$jobId' ";
	

		$jobDetails = $this->dbc->get_rows($sqlj);
	
		$send = new sendMails(true);
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=9 AND mail_template=79 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);
		
		$subject = $mailTemplate[0]['subject'];

		$html = $mailTemplate[0]['mail_body'];
		
		$html = str_replace("--name",$_REQUEST['Name'],$html);
		$html = str_replace("--email",$_REQUEST['Email'],$html);
		$html = str_replace("--phone1",$_REQUEST['Phone1'],$html);
		$html = str_replace("--phone2",$_REQUEST['Phone2'],$html);
		$html = str_replace("--address1",$_REQUEST['Address1'],$html);
		$html = str_replace("--address2",$_REQUEST['Address2'],$html);
		$html = str_replace("--nationality",$_REQUEST['Nationality'],$html);
		$html = str_replace("--state",$_REQUEST['inputState'],$html);
		$html = str_replace("--district",$_REQUEST['inputDistrict'],$html);
		$html = str_replace("--experienece",$_REQUEST['Experienece'],$html);
		$html = str_replace("--education",$_REQUEST['Education'],$html);
		$html = str_replace("--got_from",$_REQUEST['AboutUs'],$html);
		
		$html = str_replace("--camera",$_REQUEST['camera'],$html);
		$html = str_replace("--JobId",$jobDetails[0]['position_code'],$html);
		$html = str_replace("--Job_tittle",$jobDetails[0]['position_name'],$html);
	
		$mailRes = $send->sendMail($subject ,$_REQUEST['Name'], $_REQUEST['Email'] , $html ,"Machoose International" , "machoosinternational@gmail.com"  );
		
		$recentActivity = new Dashboard(true);
		$activityMeg = "New job application for ".$jobDetails[0]['position_name']." (".$jobDetails[0]['position_code'].") post from ".$_REQUEST['Name'];
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
		
		
		
		$result = $this->dbc->InsertUpdate($data, 'tbl_career_applications');
		
		
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");
		
        
		
	}
	
	public function getApplicationList(){
	    
	    $jobId = $_REQUEST['jobId'];
	    $applicationStatus = $_REQUEST['applicationStatus'];
	    
	    if($applicationStatus != ""){
	        $sql = "SELECT * FROM tbl_career_applications
			WHERE JobId='$jobId' AND active ='$applicationStatus' ORDER BY created_date DESC";
	    }else{
	        $sql = "SELECT * FROM tbl_career_applications
			WHERE JobId='$jobId' ORDER BY created_date DESC";
	    }
	
		
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getApplicationDataList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbl_career_applications
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function applyApplicationStatus(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$status=$_REQUEST["status"];
	
        $query = "UPDATE `tbl_career_applications` SET `active`='$status' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
		
		
		$jobId = $_REQUEST['jobId'];
		
		$sqlj = "SELECT a.*,b.position_name,b.position_code,c.Name as UName,c.Email as UEmail FROM tbl_career a left join tblhr_job_position b on a.tittle = b.position_id left join tbl_career_applications c on c.JobId = a.id	WHERE c.JobId='$sel_id' ";
	
		$jobDetails = $this->dbc->get_rows($sqlj);
		
		$send = new sendMails(true);
		
		
		if($status == 2){
		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=9 AND mail_template=80 AND `active`=1 ";
		}else if($status == 3){
		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=9 AND mail_template=81 AND `active`=1 ";
		}
		
		if($status == 2 || $status == 3){
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		
    		$subject = $mailTemplate[0]['subject'];
    
    		$html = $mailTemplate[0]['mail_body'];
    	
    	    $html = str_replace("--username",$jobDetails[0]['UName'],$html);
    		$html = str_replace("--Job_id",$jobDetails[0]['position_code'],$html);
    		$html = str_replace("--Job_name",$jobDetails[0]['position_name'],$html);
    		
    		$html = str_replace("--state",$jobDetails[0]['state'],$html);
    		$html = str_replace("--district",$jobDetails[0]['district'],$html);
    		$html = str_replace("--city",$jobDetails[0]['city'],$html);
    		$html = str_replace("--job_summary",$jobDetails[0]['jobsummary'],$html);
    		$html = str_replace("--workig_conditions",$jobDetails[0]['Workigconditions'],$html);
    		$html = str_replace("--Job_duties",$jobDetails[0]['Jobduties'],$html);
    		$html = str_replace("--qualifications",$jobDetails[0]['Qualifications'],$html);
    		$html = str_replace("--experience",$jobDetails[0]['Experience'],$html);
    		$html = str_replace("--skills",$jobDetails[0]['Skills'],$html);
    		
    		$html = str_replace("--responsibiities",$jobDetails[0]['Responsibiities'],$html);
    		$html = str_replace("--county",$jobDetails[0]['County'],$html);
    		
    		$html = str_replace("--job_description",$jobDetails[0]['sub_tittle'],$html);
    	
    		$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $jobDetails[0]['UName'], $jobDetails[0]['UEmail'] );
		
		
		}
		
	
		$recentActivity = new Dashboard(true);
		if($status == 1) $activityMeg = "Job application for ".$jobDetails[0]['UName']." - ".$jobDetails[0]['position_name']." (".$jobDetails[0]['position_code'].") is short listed ";
		else if($status == 2) $activityMeg = "Job application for ".$jobDetails[0]['UName']." - ".$jobDetails[0]['position_name']." (".$jobDetails[0]['position_code'].") is accepted ";
		else if($status == 3) $activityMeg = "Job application for ".$jobDetails[0]['UName']." - ".$jobDetails[0]['position_name']." (".$jobDetails[0]['position_code'].") is declined ";
		
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
		
		
		
	
      
        if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Failed");
	
	}
	
	public function setSummaryData(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT COUNT(*) as Count,
               (SELECT COUNT(*) FROM tbl_career_applications WHERE JobId = tbl_career.id AND active = 1) AS shortListCount,
               (SELECT COUNT(*) FROM tbl_career_applications WHERE JobId = tbl_career.id AND active = 2) AS acceptCount,
               (SELECT COUNT(*) FROM tbl_career_applications WHERE JobId = tbl_career.id AND active = 3) AS declineCount,
               (SELECT COUNT(*) FROM tbl_career_applications WHERE JobId = tbl_career.id AND active = 0) AS pendingCount,
               (SELECT COUNT(*) FROM tbl_career_applications WHERE JobId = tbl_career.id ) AS totalCount
        FROM tbl_career
        WHERE id = $sel_id";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}


  
}

?>