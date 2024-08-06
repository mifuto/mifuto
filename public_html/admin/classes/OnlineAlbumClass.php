<?php

require_once('pdfSplitter.php');
require_once('sendMailClass.php');
require_once('DashboardClass.php');
require_once('vendor/autoload.php');


use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class OnlineAlbum {
    private $dbc;
    private $error_message;
    
    private $bucketName;
    private $s3Client;
	
    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
		// echo "dddddd";die;
		
		 $this->s3Client = new S3Client([
            'version'     => 'latest',
            'region' => 'ap-south-2', // e.g., us-east-1
            'credentials' => [
                'key'    => AWS_KEY,
                'secret' => AWS_SECRET,
                'region' => AWS_REGION, // e.g., us-east-1
            ],
        ]);
    	    
	    $this->bucketName = AWS_BUCKET;
	}
	
    public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}
	
	
	public function pdftoimg(){
	    die('======');
	}

	public function getEventList(){
        // echo("I am here !!!!!");
		$sel_user=$_REQUEST["sel_user"];
		
		 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
		
		
		if($sel_user == ""){
		    
		      if($isAdmin){
            	$sql = "SELECT ev.*, ct.firstname, ct.lastname , (SELECT COUNT(*) FROM tbevents_views
                    WHERE project_id = ev.id) AS viewCounts ,(SELECT COUNT(*) FROM onl_alb_shares
                        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM tbevents_views
                        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM onl_alb_comments
                        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM onl_alb_like
                    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM tbevents_data as ev 
                			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id
                			WHERE ev.deleted=0 ORDER BY ev.id DESC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
			
			
			
                }else{
                    
                      if($manage_type == 'County'){
                           // user type County
                           
                           $sql = "SELECT ev.*, ct.firstname, ct.lastname , (SELECT COUNT(*) FROM tbevents_views
                    WHERE project_id = ev.id) AS viewCounts ,(SELECT COUNT(*) FROM onl_alb_shares
                        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM tbevents_views
                        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM onl_alb_comments
                        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM onl_alb_like
                    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM tbevents_data as ev 
                			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
                			WHERE ev.deleted=0 and cct.country = '$county_id' ORDER BY ev.id DESC";
                           
                           
                       }else if($manage_type == 'State'){
                           // user type State
                           
                           $sql = "SELECT ev.*, ct.firstname, ct.lastname , (SELECT COUNT(*) FROM tbevents_views
                    WHERE project_id = ev.id) AS viewCounts ,(SELECT COUNT(*) FROM onl_alb_shares
                        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM tbevents_views
                        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM onl_alb_comments
                        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM onl_alb_like
                    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM tbevents_data as ev 
                			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
                			WHERE ev.deleted=0 and cct.state = '$state' ORDER BY ev.id DESC";
                          
                       }else {
                           // user type City
                           
                              $sql = "SELECT ev.*, ct.firstname, ct.lastname , (SELECT COUNT(*) FROM tbevents_views
                    WHERE project_id = ev.id) AS viewCounts ,(SELECT COUNT(*) FROM onl_alb_shares
                        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM tbevents_views
                        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM onl_alb_comments
                        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM onl_alb_like
                    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM tbevents_data as ev 
                			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
                			WHERE ev.deleted=0 and cct.city = '$city' ORDER BY ev.id DESC";
                			
                       }
                    
                    
                    
                    
                    
                    
                    
                }
		    
		    
		
			
			
		}else{
			$sql = "SELECT ev.*, ct.firstname, ct.lastname , (SELECT COUNT(*) FROM tbevents_views
    WHERE project_id = ev.id) AS viewCounts ,(SELECT COUNT(*) FROM onl_alb_shares
        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM tbevents_views
        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM onl_alb_comments
        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM onl_alb_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts  FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id
			WHERE ev.deleted=0 AND ev.user_id = $sel_user ORDER BY ev.id DESC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		}

		

		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}

	public function editEvent(){
		$id=$_REQUEST["eventId"];
		// echo("I am here !!!!!-------".$id);
		$sql = "SELECT * FROM tbevents_data
		WHERE id=$id";
		//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);

        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");

	}

    public function getUsersList(){
        // echo("I am here !!!!!");
		// $id=$_REQUEST["id"];
		
		   $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
        if($isAdmin){
            $sql = "SELECT id, firstname, lastname FROM `tblcontacts` WHERE active=1 ORDER BY firstname ASC";
       }else{
             if($manage_type == 'County'){
               // user type County
               
               $sql = "SELECT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid WHERE a.active=1 AND b.country = '$county_id' ORDER BY a.firstname ASC";
               
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid WHERE a.active=1 AND b.state = '$state' ORDER BY a.firstname ASC";
              
              
             
           }else {
               // user type City
               $sql = "SELECT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid WHERE a.active=1 AND b.city = '$city' ORDER BY a.firstname ASC";
               
               
               
           }
       }
           
		
		
		
		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}
	
	 public function getUsersAllList(){
       
            $sql = "SELECT id, firstname, lastname FROM `tblcontacts` WHERE active=1 ORDER BY firstname ASC";
      
		
		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}
	
	public function getstaffusers(){
      
		$sql = "SELECT staffid as id, firstname, lastname FROM `tblstaff` WHERE active=1 ORDER BY firstname ASC";
	
		$result = $this->dbc->get_rows($sql);
       
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}

	public function saveEvents(){

		$send = new sendMails(true);

		$folderName = preg_replace('/[^A-Za-z0-9]/', '', $_REQUEST['folderName']);
		$datetime = date("YmdHis");
		$folderName = $folderName."_".$datetime;
		$view_token = rand(1000, 9999);

		$data=array();
		$data["user_id"]=$_REQUEST['usersList'];
		$data["event_name"]=$_REQUEST['eventName'];
		$data["venue"]=$_REQUEST['venue'];
		// $data["page_number"]=$_REQUEST['pageNumber'];
		$data["folder_name"]=$folderName;
		$data["event_date"]=$_REQUEST['eventdate'];
		$data["description"]=$_REQUEST['description'];
		$data["album_type"]=$_REQUEST['gridRadios'];
		// $data["album_width"]=$_REQUEST['albmWidth'];
		// $data["album_height"]=$_REQUEST['albmHeight'];
		$data["upload_date"]=$_REQUEST['uploadedDate'];
		$data["created_by"]="";
		$coverImage = $_FILES['coverImage'];
		$albumPdf = $_FILES['albumPdf'];
		$extension = pathinfo($coverImage['name'], PATHINFO_EXTENSION);
		
// print_r($_REQUEST);die;
		// if($extension === 'zip') {
		// Set the target directory
			// $targetDir = __DIR__."/"."uploads/";
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['usersList']."_".$folderName;
// 			mkdir($uploadDidectory.$directory, 0777);
			$targetDir = $uploadDidectory.$directory."/";
			// $uploadedFileName = $_REQUEST['usersList']."_".$_REQUEST['eventName'].".".$extension;
			$uploadedPdfFileName = $albumPdf['name'];
			$uploadedCoverImgeName = $coverImage['name'];
			$targetPdfPath = $targetDir . uniqid() . '-'. $uploadedPdfFileName;
			$targetCoverPath = $targetDir . uniqid() . '-'.$uploadedCoverImgeName;
			
			$targetPdfPathNew = $uploadDidectory . $uploadedPdfFileName;
			
// 			move_uploaded_file($coverImage['tmp_name'], $targetCoverPath);
			
			try {
                // Upload the file to S3
                $out = $this->s3Client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => $targetCoverPath,
                    'SourceFile' => $coverImage['tmp_name'],
                ]);
                
                $coverImgFilePath = $out['ObjectURL'];
            
               
            } catch (AwsException $e) {
                // Handle errors
                // echo 'Error uploading image: ' . $e->getMessage();
                self::sendResponse("2", $e->getMessage());
                die;
            }
			
			
		
			move_uploaded_file($albumPdf['tmp_name'], $targetPdfPathNew);
            
            try{
			    
			    splitPdf($targetPdfPathNew);
			    
    			try {
                    // Upload the file to S3
                    $out = $this->s3Client->putObject([
                        'Bucket' => $this->bucketName,
                        'Key'    => $targetPdfPath,
                        'SourceFile' => $targetPdfPathNew,
                    ]);
                    
                    $albmPdfName = $out['ObjectURL'];
                
                   
                } catch (AwsException $e) {
                    // Handle errors
                    // echo 'Error uploading image: ' . $e->getMessage();
                    self::sendResponse("2", $e->getMessage());
                    die;
                }
			
			    
			     $current_file = $targetPdfPathNew;
                $path = dirname($current_file);
                $filename = pathinfo($current_file, PATHINFO_FILENAME);
                $extension = pathinfo($current_file, PATHINFO_EXTENSION);
                
                $new_file = $path. '/'. $filename . '_orig.' . $extension;
                unlink($new_file);
			    
			    
			    unlink($targetPdfPathNew);
			    
			    
			    
			   
			    $user_id=$_REQUEST['usersList'];
        		$event_name=$_REQUEST['eventName'];
        		$venue=$_REQUEST['venue'];
        		// $page_number=$_REQUEST['pageNumber'];
        		$folder_name=$folderName;
        		$event_date=$_REQUEST['eventdate'];
        // 		$description=$_REQUEST['description'];
        		$album_type=$_REQUEST['gridRadios'];
        		// $album_width=$_REQUEST['albmWidth'];
        		// $album_height=$_REQUEST['albmHeight'];
        		$upload_date=$_REQUEST['uploadedDate'];
        
        		$coverImgName = $coverImage['name'];
        		$coverImgSize = $coverImage['size'];
        // 		$albmPdfName = $albumPdf['name'];
        		$albmPdfSize = $albumPdf['size'];
        		
        		$description = str_replace("'", '"', $_REQUEST['description']);
        
        		// $sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`) VALUES ('$_REQUEST['usersList']','$_REQUEST['eventName']','$_REQUEST['venue']','$_REQUEST['pageNumber']','$folderName','$_REQUEST['eventdate']','$_REQUEST['description']','$_REQUEST['gridRadios']','$_REQUEST['albmWidth']','$_REQUEST['albmHeight']','$_REQUEST['uploadedDate']')";

				$StaringDate = date("Y-m-d ");

				$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

        // 		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`,`expiry_date`) VALUES ('$user_id','$event_name','$venue','$page_number','$folder_name','$directory','$event_date','$description','$album_type','$album_width','$album_height','$upload_date','$newExpDate')";
        		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `upload_date`,`expiry_date`,`view_token`) VALUES ('$user_id','$event_name','$venue','$folder_name','$directory','$event_date','$description','$album_type','$upload_date','$newExpDate','$view_token')";
        		// $result = $this->dbc->insert_row($data, 'tbevents_data');
        		
        	
        		
        		$result = $this->dbc->insert_row($sql);
        		
        	
        	    $sql1 = "INSERT INTO `tbeevent_files`(`event_id`, `pdffile_name`, `pdffile_size`, `covering_name`, `covering_size`) VALUES ('$result','$albmPdfName','$albmPdfSize','$coverImgFilePath','$coverImgSize')";
        		
        		$result = $this->dbc->insert_row($sql1);
        
        		// die;
        	
				$sqlM = "SELECT a.* FROM mail_templates a left join mail_type b on a.mail_type = b.id  WHERE a.`active`=1 AND b.mail_type='Online album' AND a.deleted=0 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
				$userList = $this->dbc->get_rows($sql1);
				$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
				$eventUserEmail = $userList[0]['email'];

				$html = $mailTemplate[0]['mail_body'];

				if($album_type == 1) $atv = "Portraits Album";
				else $atv = "Landscape album";

				$html = str_replace("--username",$eventUser,$html);
				$html = str_replace("--event_name",$event_name,$html);
				$html = str_replace("--venue",$venue,$html);
				$html = str_replace("--event_dt",$event_date,$html);
				$html = str_replace("--description",$description,$html);
				$html = str_replace("--album_type",$atv,$html);
				$html = str_replace("--upload_dt",$upload_date,$html);
				$html = str_replace("--expiry_dt",$newExpDate,$html);
				$html = str_replace("--view_token",$view_token,$html);

				$recentActivity = new Dashboard(true);
				
				 $isAdmin = $_SESSION['isAdmin'];
               $isCounty_id = $_SESSION['county_id'];
               $isState_id = $_SESSION['state_id'];
               $isCity_id = $_SESSION['city_id'];
               $isUsername = $_SESSION['Username'];
               
               $activityMeg = "New online album ".$event_name." is created by ".$isUsername." for user ".$eventUser;
				$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

				$activityMeg1 = "Your Online album ".$event_name." is created";
				$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,'online-album.php');

				$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
				//sub,from name,from mail,content,to user, to mail

				//print_r($mailRes);
        
        		if($result != "")self::sendResponse("1", "Event saved successfully");
                else self::sendResponse("2", "Not inserted data");
            } catch (Exception $e) {
                // var_dump($e);
                // unlink($targetCoverPath);
                // unlink($targetPdfPath);
                // print($e->getMessage());
				self::sendResponse("2", "The uploaded PDF file is currupted. Please build new PDF file and upload it again.");
            }

			// print_r($directory);die;
			// $zip = new ZipArchive;
			// $res = $zip->open($targetFilePath);
			// if ($res === TRUE) {
			// 	$zip->extractTo($targetDir);
			// 	$zip->close();
			// 	unlink($targetFilePath);
			// } else {
			// 	echo 'Unable to extract zip !';
			// }
			// Save the uploaded file to the target directory
			// $stat = 
			// $dfdf = self::createPath($targetDir);
			// print_r(EVENT_UPLOAD_PATH);
		// }
		
		
		

	}
	
	public function copy_saveEvents(){

		$send = new sendMails(true);

		$folderName = $_REQUEST['folderName'];
		$datetime = date("YmdHis");
		$folderName = $folderName."_".$datetime;
		$view_token = rand(1000, 9999);

		$data=array();
		$data["user_id"]=$_REQUEST['usersList'];
		$data["event_name"]=$_REQUEST['eventName'];
		$data["venue"]=$_REQUEST['venue'];
		// $data["page_number"]=$_REQUEST['pageNumber'];
		$data["folder_name"]=$folderName;
		$data["event_date"]=$_REQUEST['eventdate'];
		$data["description"]=$_REQUEST['description'];
		$data["album_type"]=$_REQUEST['gridRadios'];
		// $data["album_width"]=$_REQUEST['albmWidth'];
		// $data["album_height"]=$_REQUEST['albmHeight'];
		$data["upload_date"]=$_REQUEST['uploadedDate'];
		$data["created_by"]="";
		$coverImage = $_FILES['coverImage'];
		$albumPdf = $_FILES['albumPdf'];
		$extension = pathinfo($coverImage['name'], PATHINFO_EXTENSION);
		
// print_r($_REQUEST);die;
		// if($extension === 'zip') {
		// Set the target directory
			// $targetDir = __DIR__."/"."uploads/";
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['usersList']."_".$folderName;
			mkdir($uploadDidectory.$directory, 0777);
			$targetDir = $uploadDidectory.$directory."/";
			// $uploadedFileName = $_REQUEST['usersList']."_".$_REQUEST['eventName'].".".$extension;
			$uploadedPdfFileName = $albumPdf['name'];
			$uploadedCoverImgeName = $coverImage['name'];
			$targetPdfPath = $targetDir . $uploadedPdfFileName;
			$targetCoverPath = $targetDir . $uploadedCoverImgeName;
			move_uploaded_file($coverImage['tmp_name'], $targetCoverPath);
			move_uploaded_file($albumPdf['tmp_name'], $targetPdfPath);
            
            try{
			    
			    splitPdf($targetPdfPath);
			   
			    $user_id=$_REQUEST['usersList'];
        		$event_name=$_REQUEST['eventName'];
        		$venue=$_REQUEST['venue'];
        		// $page_number=$_REQUEST['pageNumber'];
        		$folder_name=$folderName;
        		$event_date=$_REQUEST['eventdate'];
        // 		$description=$_REQUEST['description'];
        		$album_type=$_REQUEST['gridRadios'];
        		// $album_width=$_REQUEST['albmWidth'];
        		// $album_height=$_REQUEST['albmHeight'];
        		$upload_date=$_REQUEST['uploadedDate'];
        
        		$coverImgName = $coverImage['name'];
        		$coverImgSize = $coverImage['size'];
        		$albmPdfName = $albumPdf['name'];
        		$albmPdfSize = $albumPdf['size'];
        		
        		$description = str_replace("'", '"', $_REQUEST['description']);
        
        		// $sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`) VALUES ('$_REQUEST['usersList']','$_REQUEST['eventName']','$_REQUEST['venue']','$_REQUEST['pageNumber']','$folderName','$_REQUEST['eventdate']','$_REQUEST['description']','$_REQUEST['gridRadios']','$_REQUEST['albmWidth']','$_REQUEST['albmHeight']','$_REQUEST['uploadedDate']')";

				$StaringDate = date("Y-m-d ");

				$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

        // 		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`,`expiry_date`) VALUES ('$user_id','$event_name','$venue','$page_number','$folder_name','$directory','$event_date','$description','$album_type','$album_width','$album_height','$upload_date','$newExpDate')";
        		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `upload_date`,`expiry_date`,`view_token`) VALUES ('$user_id','$event_name','$venue','$folder_name','$directory','$event_date','$description','$album_type','$upload_date','$newExpDate','$view_token')";
        		// $result = $this->dbc->insert_row($data, 'tbevents_data');
        		$result = $this->dbc->insert_row($sql);
        		
        	
        	    $sql1 = "INSERT INTO `tbeevent_files`(`event_id`, `pdffile_name`, `pdffile_size`, `covering_name`, `covering_size`) VALUES ('$result','$albmPdfName','$albmPdfSize','$coverImgName','$coverImgSize')";
        		
        		$result = $this->dbc->insert_row($sql1);
        
        		// die;
        	
				$sqlM = "SELECT a.* FROM mail_templates a left join mail_type b on a.mail_type = b.id  WHERE a.`active`=1 AND b.mail_type='Online album' AND a.deleted=0 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
				$userList = $this->dbc->get_rows($sql1);
				$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
				$eventUserEmail = $userList[0]['email'];

				$html = $mailTemplate[0]['mail_body'];

				if($album_type == 1) $atv = "Portraits Album";
				else $atv = "Landscape album";

				$html = str_replace("--username",$eventUser,$html);
				$html = str_replace("--event_name",$event_name,$html);
				$html = str_replace("--venue",$venue,$html);
				$html = str_replace("--event_dt",$event_date,$html);
				$html = str_replace("--description",$description,$html);
				$html = str_replace("--album_type",$atv,$html);
				$html = str_replace("--upload_dt",$upload_date,$html);
				$html = str_replace("--expiry_dt",$newExpDate,$html);
				$html = str_replace("--view_token",$view_token,$html);

				$recentActivity = new Dashboard(true);
				$activityMeg = "Online album ".$event_name." is created for ".$eventUser;
				$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

				$activityMeg1 = "Your Online album ".$event_name." is created";
				$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,'online-album.php');

				$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
				//sub,from name,from mail,content,to user, to mail

				//print_r($mailRes);
        
        		if($result != "")self::sendResponse("1", "Event saved successfully");
                else self::sendResponse("2", "Not inserted data");
            } catch (Exception $e) {
                // var_dump($e);
                unlink($targetCoverPath);
                unlink($targetPdfPath);
                // print($e->getMessage());
				self::sendResponse("2", "The uploaded PDF file is currupted. Please build new PDF file and upload it again.");
            }

			// print_r($directory);die;
			// $zip = new ZipArchive;
			// $res = $zip->open($targetFilePath);
			// if ($res === TRUE) {
			// 	$zip->extractTo($targetDir);
			// 	$zip->close();
			// 	unlink($targetFilePath);
			// } else {
			// 	echo 'Unable to extract zip !';
			// }
			// Save the uploaded file to the target directory
			// $stat = 
			// $dfdf = self::createPath($targetDir);
			// print_r(EVENT_UPLOAD_PATH);
		// }
		
		
		

	}

	public function updateEvents(){
		
		
		$data=array();
		$data["id"]=$_REQUEST['eventId'];
		$data["user_id"]=$_REQUEST['usersList'];
		$data["event_name"]=$_REQUEST['eventName'];
		$data["venue"]=$_REQUEST['venue'];
		// $data["page_number"]=$_REQUEST['pageNumber'];
		$data["folder_name"]=$_REQUEST['folderName'];
		$data["event_date"]=$_REQUEST['eventdate'];
		$data["description"]=$_REQUEST['description'];
		$data["album_type"]=$_REQUEST['gridRadios'];
		// $data["album_width"]=$_REQUEST['albmWidth'];
		// $data["album_height"]=$_REQUEST['albmHeight'];
		$data["upload_date"]=$_REQUEST['uploadedDate'];
		$data["created_by"]="";

		$event_id=$_REQUEST['eventId'];

		if(isset($_FILES['coverImage']) && $_FILES['coverImage']['name'] !="" ){
			$coverImage = $_FILES['coverImage'];
			
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['folderName'];
			

			$targetDir = $uploadDidectory.$directory."/";
			$uploadedCoverImgeName = $coverImage['name'];
// 			$targetCoverPath = $targetDir . $uploadedCoverImgeName;
			$targetCoverPath = $targetDir . uniqid() . '-'.$uploadedCoverImgeName;

// 			move_uploaded_file($coverImage['tmp_name'], $targetCoverPath);
			
			try {
                // Upload the file to S3
                $out = $this->s3Client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => $targetCoverPath,
                    'SourceFile' => $coverImage['tmp_name'],
                ]);
                
                $coverImgFilePath = $out['ObjectURL'];
            
               
            } catch (AwsException $e) {
                // Handle errors
                // echo 'Error uploading image: ' . $e->getMessage();
                self::sendResponse("2", $e->getMessage());
                die;
            }
			
			
			
			
			
			
			$coverImgName = $coverImage['name'];
			$coverImgSize = $coverImage['size'];

			$query1 = "UPDATE `tbeevent_files` SET `covering_name`='$coverImgFilePath',`covering_size`='$coverImgSize' WHERE `event_id`=$event_id";

			$this->dbc->update_row($query1);


		}

		if(isset($_FILES['albumPdf']) && $_FILES['albumPdf']['name'] !="" ){
			$albumPdf = $_FILES['albumPdf'];
			$uploadedPdfFileName = $albumPdf['name'];
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['folderName'];
			$targetDir = $uploadDidectory.$directory."/";
			$targetPdfPath = $targetDir . $uploadedPdfFileName;
			
			
// 			move_uploaded_file($albumPdf['tmp_name'], $targetPdfPath);
			
// 			splitPdf($targetPdfPath);
			
			
			$targetPdfPathNew = $uploadDidectory . $uploadedPdfFileName;
			
			move_uploaded_file($albumPdf['tmp_name'], $targetPdfPathNew);
            
           
			    splitPdf($targetPdfPathNew);
			    
			try {
                // Upload the file to S3
                $out = $this->s3Client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => $targetPdfPath,
                    'SourceFile' => $targetPdfPathNew,
                ]);
                
                $albmPdfName = $out['ObjectURL'];
            
               
            } catch (AwsException $e) {
                // Handle errors
                // echo 'Error uploading image: ' . $e->getMessage();
                self::sendResponse("2", $e->getMessage());
                die;
            }
			
			    
			     $current_file = $targetPdfPathNew;
                $path = dirname($current_file);
                $filename = pathinfo($current_file, PATHINFO_FILENAME);
                $extension = pathinfo($current_file, PATHINFO_EXTENSION);
                
                $new_file = $path. '/'. $filename . '_orig.' . $extension;
                unlink($new_file);
			    
			    
			    unlink($targetPdfPathNew);
			
			
			
			

// 			$albmPdfName = $albumPdf['name'];
			$albmPdfSize = $albumPdf['size'];
			

			$query11 = "UPDATE `tbeevent_files` SET `pdffile_name`='$albmPdfName',`pdffile_size`='$albmPdfSize' WHERE `event_id`=$event_id";

			$this->dbc->update_row($query11);

		}

		
		$id=$_REQUEST['eventId'];
		$user_id=$_REQUEST['usersList'];
		$event_name=$_REQUEST['eventName'];
		$venue=$_REQUEST['venue'];
		// $page_number=$_REQUEST['pageNumber'];
		$folder_name=$_REQUEST['folderName'];
		$event_date=$_REQUEST['eventdate'];
// 		$description=$_REQUEST['description'];
		$album_type=$_REQUEST['gridRadios'];
		// $album_width=$_REQUEST['albmWidth'];
		// $album_height=$_REQUEST['albmHeight'];
		$upload_date=$_REQUEST['uploadedDate'];
		
		$description = str_replace("'", '"', $_REQUEST['description']);

		$query = "UPDATE `tbevents_data` SET `user_id`='$user_id',`event_name`='$event_name',`venue`='$venue',`event_date`='$event_date',`description`='$description',`album_type`='$album_type' WHERE `id`=$id";
// 		print_r($query);die;
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
		$userList = $this->dbc->get_rows($sql1);
		$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];

		
		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       
       $activityMeg = "Online album ".$event_name." for user ".$eventUser." is updated by ".$isUsername;
       
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Your Online album ".$event_name." is updated";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,'online-album.php');

		if($result != "")self::sendResponse("1", "Successfully udated event");
        else self::sendResponse("2", "Not updated data");

	}

	public function deleteEvents(){

		$data=array();
		$data["id"]=$_REQUEST['eventId'];
		
		// print_r($_REQUEST);die;
		$id=$_REQUEST['eventId'];

		$sqlD = "SELECT * FROM tbevents_data WHERE id=$id  ";
		$evtData = $this->dbc->get_rows($sqlD);
		$user_id = $evtData[0]['user_id'];


		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=1 AND mail_template=4 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];

		$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
		$userList = $this->dbc->get_rows($sql1);
		$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
		$eventUserEmail = $userList[0]['email'];

		$html = $mailTemplate[0]['mail_body'];

		if($evtData[0]['album_type'] == 1) $atv = "Portraits Album";
		else $atv = "Landscape album";

		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--event_name",$evtData[0]['event_name'],$html);
		$html = str_replace("--venue",$evtData[0]['venue'],$html);
		$html = str_replace("--event_dt",$evtData[0]['event_date'],$html);
		$html = str_replace("--description",$evtData[0]['description'],$html);
		$html = str_replace("--album_type",$atv,$html);
		$html = str_replace("--upload_dt",$evtData[0]['upload_date'],$html);
		$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

		$send = new sendMails(true);

		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       
		$activityMeg = "Online album ".$evtData[0]['event_name']." for user ".$eventUser." is deleted by ".$isUsername;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Your Online album ".$evtData[0]['event_name']." is deleted";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,'online-album.php');


		$query = "UPDATE `tbevents_data` SET `deleted`='1' WHERE `id`=$id";
		
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		if($result != "")self::sendResponse("1", "Event deleted successfully");
        else self::sendResponse("2", "Not updated data");

	}

	public function saveExtendEventDate(){

		$data=array();
		
		// print_r($_REQUEST);die;
		$id=$_REQUEST['id'];
		$date=$_REQUEST['date'];

		$sqlD = "SELECT * FROM tbevents_data WHERE id=$id  ";
		$evtData = $this->dbc->get_rows($sqlD);
		$user_id = $evtData[0]['user_id'];


		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=1 AND mail_template=13 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];

		$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
		$userList = $this->dbc->get_rows($sql1);
		$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
		$eventUserEmail = $userList[0]['email'];

		$html = $mailTemplate[0]['mail_body'];

		if($evtData[0]['album_type'] == 1) $atv = "Portraits Album";
		else $atv = "Landscape album";

		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--event_name",$evtData[0]['event_name'],$html);
		$html = str_replace("--venue",$evtData[0]['venue'],$html);
		$html = str_replace("--event_dt",$evtData[0]['event_date'],$html);
		$html = str_replace("--description",$evtData[0]['description'],$html);
		$html = str_replace("--album_type",$atv,$html);
		$html = str_replace("--upload_dt",$evtData[0]['upload_date'],$html);
		$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

		$send = new sendMails(true);

		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

		$recentActivity = new Dashboard(true);
		
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		$activityMeg = "Online album ".$evtData[0]['event_name']." for user ".$eventUser." expiry date is extended by ".$isUsername;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "extend",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Your Online album ".$evtData[0]['event_name']." expiry date is extended";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "extend" ,$user_id,'online-album.php');


		$query = "UPDATE `tbevents_data` SET `expiry_date`='$date' WHERE `id`=$id";
		
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		if($result != "")self::sendResponse("1", "Event extend successfully");
        else self::sendResponse("2", "Not updated data");

	}

	public function addViewCount(){
		$projId=$_REQUEST['projId'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
	
		$vs = "INSERT INTO `tbevents_views`(`project_id`, `IP`)
		SELECT '$projId', '$ip'
		WHERE NOT EXISTS (
			SELECT * FROM `tbevents_views`
			WHERE `project_id` = '$projId' AND `IP` = '$ip'
		)";


		$this->dbc->insert_row($vs);
	}

	public function addShareCount(){
		$userId=$_REQUEST['userId'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}


		$vs = "INSERT INTO `tbevents_shares`(`userId`, `IP` ) VALUES ('$userId','$ip')";

		$sql1 = "SELECT firstname, lastname, email , id as userID FROM `tblcontacts` WHERE id=$userId ";
		$userList = $this->dbc->get_rows($sql1);
		$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
		$userID = $userList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Online album  for ".$eventUser." is Shared";
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = "Your online album is shared";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "share" ,$userID,'online-album.php');


		$this->dbc->insert_row($vs);
	}
	
	
	public function likeOnlAlb(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM onl_alb_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `onl_alb_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `onl_alb_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `onl_alb_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId_id_like ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$userID = $AlbumList[0]['userID'];
		
		if($user_type_val == 2){
		    $chkemail = "SELECT * FROM tbeguest_users WHERE id= '$user_id_like' ";
    		$reslArr = $this->dbc->get_rows($chkemail);
    		$guestName = $reslArr[0]['name'];
		}else{
		    $chkemail = "SELECT * FROM tblcontacts WHERE id= '$user_id_like' ";
    		$reslArr = $this->dbc->get_rows($chkemail);
    		$firstname = $reslArr[0]['firstname'];
    		$lastname = $reslArr[0]['lastname'];
    		$guestName = $firstname." ".$lastname;
		}

		$recentActivity = new Dashboard(true);
        $activityMeg = "Online album ".$prjName." for user ".$eventUser." is ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
        
        		
			$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId_id_like);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	


		$activityMeg1 = $guestName." ".$sts." your online album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "like" ,$userID,$encodedStringUrl);
		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM onl_alb_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}
	
	public function addShare(){
		$projId=(int)$_REQUEST['projId'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email,b.id as user_id FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$user_id = $AlbumList[0]['user_id'];

		$recentActivity = new Dashboard(true);
        $activityMeg = "Share online album ".$prjName." for user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");
        
        		
			$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	


		$activityMeg1 = "Your online album ".$prjName." is shared" ;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,$encodedStringUrl);
		
	
		$vs = "INSERT INTO `onl_alb_shares`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	public function addView(){
		$projId=(int)$_REQUEST['projId'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

	
		$vs = "INSERT INTO `tbevents_views`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	public function getOAlbumList(){
	    
	    $userId=$_REQUEST["userId"];
	    $albumDisType=$_REQUEST["albumDisType"];
	    $todayDate = $_REQUEST["todayDate"];
	    

	    
	       $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       
	  
	    $where = "";
	    if($userId != "" && $userId != null && $userId != "null") $where = " AND ct.id =$userId ";
	    
	       if($albumDisType == 1){ $where .= " AND ev.expiry_date > DATE_ADD(CURDATE(), INTERVAL 30 DAY) "; }
	    else if($albumDisType == 2){ $where .= " AND ev.expiry_date < '$todayDate' "; }
	    else if($albumDisType == 3){ $where .= " AND ev.expiry_date BETWEEN '$todayDate' AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) "; }
	  
	  
       
	        
	         if($isAdmin){
                   $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE ev.deleted=0 $where ORDER BY ev.id DESC";
			
               }else{
                   
                    if($manage_type == 'County'){
                       // user type County
                       
                       $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.country = '$county_id' and ev.deleted=0 $where ORDER BY ev.id DESC";
                       
                      
                   }else if($manage_type == 'State'){
                       // user type State
                       $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.state = '$state' and ev.deleted=0 $where ORDER BY ev.id DESC";
                       
                     
                   }else {
                       // user type City
                        $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.city = '$city' and ev.deleted=0 $where ORDER BY ev.id DESC";
			
                   }
                   
               }
        	        
	        
	    
	 
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	public function getOAlbumListDashboard(){
	    
	    $userId=$_REQUEST["userId"];
	    $albumDisType=$_REQUEST["albumDisType"];
	    $todayDate = $_REQUEST["todayDate"];
	    

	    
	       $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       
	  
	    $where = "";
	    if($userId != "" && $userId != null && $userId != "null") $where = " AND ct.id =$userId ";
	    
	       if($albumDisType == 1){ $where .= " AND ev.expiry_date > DATE_ADD(CURDATE(), INTERVAL 30 DAY) "; }
	    else if($albumDisType == 2){ $where .= " AND ev.expiry_date < '$todayDate' "; }
	    else if($albumDisType == 3){ $where .= " AND ev.expiry_date BETWEEN '$todayDate' AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) "; }
	  
	  
       
	        
	         if($isAdmin){
                   $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
			
               }else{
                   
                    if($manage_type == 'County'){
                       // user type County
                       
                       $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.country = '$county_id' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
                       
                      
                   }else if($manage_type == 'State'){
                       // user type State
                       $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.state = '$state' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
                       
                     
                   }else {
                       // user type City
                        $sql = "SELECT ev.*, ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.city = '$city' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
			
                   }
                   
               }
        	        
	        
	    
	 
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	public function getPriceDetails(){
	    
	    $selYear=$_REQUEST["selYear"];
	    $photo_count=$_REQUEST["photo_count"];
	    
          $sql = "SELECT * FROM `tblalbumsubscription` WHERE `period`='$selYear' AND photo_count='$photo_count' AND `online`=1 AND `delete`=0 ";
      
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No users found");
		
	}
	


}

?>