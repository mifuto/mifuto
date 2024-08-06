<?php

require_once('pdfSplitter.php');
require_once('sendMailClass.php');


class OnlineAlbum {
    private $dbc;
    private $error_message;
	
    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
		// echo "dddddd";die;
	}

    public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}

	public function getEventList(){
        // echo("I am here !!!!!");
		$sel_user=$_REQUEST["sel_user"];
		if($sel_user == ""){
			$sql = "SELECT ev.*, ct.firstname, ct.lastname FROM tbevents_data as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id
			WHERE ev.deleted=0 ORDER BY ev.id DESC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		}else{
			$sql = "SELECT ev.*, ct.firstname, ct.lastname FROM tbevents_data as ev 
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
		$sql = "SELECT id, firstname, lastname FROM `tblcontacts` WHERE active=1 ORDER BY firstname ASC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}

	public function saveEvents(){

		$send = new sendMails(true);

		$folderName = $_REQUEST['folderName'];
		$datetime = date("YmdHis");
		$folderName = $folderName."_".$datetime;

		$data=array();
		$data["user_id"]=$_REQUEST['usersList'];
		$data["event_name"]=$_REQUEST['eventName'];
		$data["venue"]=$_REQUEST['venue'];
		$data["page_number"]=$_REQUEST['pageNumber'];
		$data["folder_name"]=$folderName;
		$data["event_date"]=$_REQUEST['eventdate'];
		$data["description"]=$_REQUEST['description'];
		$data["album_type"]=$_REQUEST['gridRadios'];
		$data["album_width"]=$_REQUEST['albmWidth'];
		$data["album_height"]=$_REQUEST['albmHeight'];
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
        		$page_number=$_REQUEST['pageNumber'];
        		$folder_name=$folderName;
        		$event_date=$_REQUEST['eventdate'];
        		$description=$_REQUEST['description'];
        		$album_type=$_REQUEST['gridRadios'];
        		$album_width=$_REQUEST['albmWidth'];
        		$album_height=$_REQUEST['albmHeight'];
        		$upload_date=$_REQUEST['uploadedDate'];
        
        		$coverImgName = $coverImage['name'];
        		$coverImgSize = $coverImage['size'];
        		$albmPdfName = $albumPdf['name'];
        		$albmPdfSize = $albumPdf['size'];
        
        		// $sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`) VALUES ('$_REQUEST['usersList']','$_REQUEST['eventName']','$_REQUEST['venue']','$_REQUEST['pageNumber']','$folderName','$_REQUEST['eventdate']','$_REQUEST['description']','$_REQUEST['gridRadios']','$_REQUEST['albmWidth']','$_REQUEST['albmHeight']','$_REQUEST['uploadedDate']')";

				$StaringDate = date("Y-m-d ");

				$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

        // 		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`,`expiry_date`) VALUES ('$user_id','$event_name','$venue','$page_number','$folder_name','$directory','$event_date','$description','$album_type','$album_width','$album_height','$upload_date','$newExpDate')";
        		$sql = "INSERT INTO `tbevents_data`( `user_id`, `event_name`, `venue`, `page_number`, `folder_name`, `uploader_folder`, `event_date`, `description`, `album_type`, `album_width`, `album_height`, `upload_date`,`expiry_date`) VALUES ('$user_id','$event_name','$venue','$page_number','$folder_name','$directory','$event_date','$description','$album_type','$album_width','$album_height','$upload_date','$newExpDate')";
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
		$data["page_number"]=$_REQUEST['pageNumber'];
		$data["folder_name"]=$_REQUEST['folderName'];
		$data["event_date"]=$_REQUEST['eventdate'];
		$data["description"]=$_REQUEST['description'];
		$data["album_type"]=$_REQUEST['gridRadios'];
		$data["album_width"]=$_REQUEST['albmWidth'];
		$data["album_height"]=$_REQUEST['albmHeight'];
		$data["upload_date"]=$_REQUEST['uploadedDate'];
		$data["created_by"]="";

		$event_id=$_REQUEST['eventId'];

		if(isset($_FILES['coverImage']) && $_FILES['coverImage']['name'] !="" ){
			$coverImage = $_FILES['coverImage'];
			
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['folderName'];
			

			$targetDir = $uploadDidectory.$directory."/";
			$uploadedCoverImgeName = $coverImage['name'];
			$targetCoverPath = $targetDir . $uploadedCoverImgeName;

			move_uploaded_file($coverImage['tmp_name'], $targetCoverPath);
			
			$coverImgName = $coverImage['name'];
			$coverImgSize = $coverImage['size'];

			$query1 = "UPDATE `tbeevent_files` SET `covering_name`='$coverImgName',`covering_size`='$coverImgSize' WHERE `event_id`=$event_id";

			$this->dbc->update_row($query1);


		}

		if(isset($_FILES['albumPdf']) && $_FILES['albumPdf']['name'] !="" ){
			$albumPdf = $_FILES['albumPdf'];
			$uploadedPdfFileName = $albumPdf['name'];
			$uploadDidectory = EVENT_UPLOAD_PATH;
			$directory = $_REQUEST['folderName'];
			$targetDir = $uploadDidectory.$directory."/";
			$targetPdfPath = $targetDir . $uploadedPdfFileName;
			move_uploaded_file($albumPdf['tmp_name'], $targetPdfPath);
			
			splitPdf($targetPdfPath);

			$albmPdfName = $albumPdf['name'];
			$albmPdfSize = $albumPdf['size'];
			

			$query11 = "UPDATE `tbeevent_files` SET `pdffile_name`='$albmPdfName',`pdffile_size`='$albmPdfSize' WHERE `event_id`=$event_id";

			$this->dbc->update_row($query11);

		}

		
		$id=$_REQUEST['eventId'];
		$user_id=$_REQUEST['usersList'];
		$event_name=$_REQUEST['eventName'];
		$venue=$_REQUEST['venue'];
		$page_number=$_REQUEST['pageNumber'];
		$folder_name=$_REQUEST['folderName'];
		$event_date=$_REQUEST['eventdate'];
		$description=$_REQUEST['description'];
		$album_type=$_REQUEST['gridRadios'];
		$album_width=$_REQUEST['albmWidth'];
		$album_height=$_REQUEST['albmHeight'];
		$upload_date=$_REQUEST['uploadedDate'];

		$query = "UPDATE `tbevents_data` SET `user_id`='$user_id',`event_name`='$event_name',`venue`='$venue',`page_number`='$page_number',`event_date`='$event_date',`description`='$description',`album_type`='$album_type',`album_width`='$album_width',`album_height`='$album_height' WHERE `id`=$id";
// 		print_r($query);die;
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

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
		$this->dbc->insert_row($vs);
	}


}

?>