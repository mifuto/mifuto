<?php

require_once('sendMailClass.php');


class SignatureAlbum {
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
	public function getProjectEvents(){
		$projId = (int) $_REQUEST["projId"];
		$addview = (int) $_REQUEST["addview"];
		$userIdVal = $_REQUEST["userIdVal"];


		// $projId = 14;
		$sql = "SELECT sa.*, cvi.image_path, ct.firstname, ct.lastname FROM tbesignaturealbum_data as sa LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id LEFT JOIN tbesignaturealbum_coverimage as cvi ON cvi.folder_Id=sa.id WHERE sa.project_folder_id=".$projId." AND sa.deleted=0 ORDER BY sa.id DESC";

		// $sql = "SELECT * FROM tbesignaturealbum_data";
		// print_r($this->dbc->get_rows($sql));
		// echo $sql; die;
		$result = $this->dbc->get_rows($sql);

		if($result[0]['user_id'] != $userIdVal){
			if($addview == 1){
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
					//to check ip is pass from proxy
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				
				$vs = "INSERT INTO `tbeproject_views`(`project_id`, `IP`)
				SELECT '$projId', '$ip'
				WHERE NOT EXISTS (
				  SELECT * FROM `tbeproject_views`
				  WHERE `project_id` = '$projId' AND `IP` = '$ip'
				)";
				$this->dbc->insert_row($vs);
			}

		}
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}

	public function deleteProject(){
		$projId=$_REQUEST["projid"];

        $sqlD = "SELECT * FROM tbesignaturealbum_projects WHERE id=$projId  ";
        $evtData = $this->dbc->get_rows($sqlD);
        $user_id = $evtData[0]['user_id'];

        $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=8 AND `active`=1 ";
        $mailTemplate = $this->dbc->get_rows($sqlM);

        //send mail here
        $subject = $mailTemplate[0]['subject'];

        $sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
        $userList = $this->dbc->get_rows($sql1);
        $eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
        $eventUserEmail = $userList[0]['email'];

        $html = $mailTemplate[0]['mail_body'];

      
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--project_name",$evtData[0]['project_name'],$html);
		$html = str_replace("--token",$evtData[0]['token'],$html);
		$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

        $send = new sendMails(true);
        $mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

		$qry = "UPDATE `tbesignaturealbum_projects` SET `deleted`='1' WHERE id=$projId";
		$result = $this->dbc->update_row($qry);

		if($result != "")self::sendResponse("1", "Project deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	public function deleteImageFromAlbum(){
	    $imagid = $_REQUEST["image"];
	   // $imgUrl = $_REQUEST["imgUrl"];
	    $sql = "DELETE FROM `tbesignalbm_folderfiles` WHERE id=".$imagid;
	    $result = $this->dbc->query($sql);
	   // print_r($result[0]['file_path']);
	   // unlink("$result[0]['file_path']");
    //     print_r($result[0]['thumb_image_path']);die;
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	    die($imgUrl);
	}

	public function getSignatureAlbums(){
        // echo("I am here !!!!!");
		$id=$_REQUEST["userId"];
		
		// $sql = "SELECT uf.id, ct.firstname, ct.lastname FROM `tbesignaturealbum_userfolder` as uf 
		// LEFT JOIN tbesignaturealbum_data as slb ON slb.id = uf.user_id 
		// LEFT JOIN tblcontacts as ct ON ct.id = uf.user_id WHERE uf.user_id=".$id." AND sa.deleted=0 ORDER BY sa.id DESC";
		// echo $sql;
		$sql = "SELECT sa.*, cvi.image_path, ct.firstname, ct.lastname FROM tbesignaturealbum_data as sa LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id LEFT JOIN tbesignaturealbum_coverimage as cvi ON cvi.folder_Id=sa.id WHERE sa.user_id=".$id." AND sa.deleted=0 ORDER BY sa.id DESC";
		// echo $sql;
		//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		
		$result = $this->dbc->get_rows($sql);
        // print_r($sql);die;
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}

    public function getUsersList(){
        // echo("I am here !!!!!");
		// $id=$_REQUEST["id"];
		$sql = "SELECT id, firstname, lastname FROM `tblcontacts` WHERE active=1 ORDER BY firstname ASC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		print_r($sql);
		$result = $this->dbc->get_rows($sql);
        
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}


	public function saveProjects(){

		$send = new sendMails(true);

		
		$coverImageFile = $_FILES['signatureAlbumCover'];
		$user_id=$_REQUEST['selectedProjUserId'];
		$proj_name=$_REQUEST['sigAlbmProjName'];
		$proj_id=$_REQUEST['selectedProjId'];

		$token=$_REQUEST['token'];

		// print_r($proj_id);die;
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;
		

		$t=time();
		if($proj_id == ""){
			
			$projFolder = $proj_name."_".$user_id."_".$t;
			$projFolder_path = $uploadDidectory.$projFolder;
			// echo $projFolder_path;
			mkdir($projFolder_path, 0777, true);

			$coverImgDirectory = $projFolder_path.'/coverImages';
			mkdir($coverImgDirectory, 0777, true);
			
			$countfiles = count($coverImageFile);
			// print_r($coverImageFile);
			$filename = $coverImageFile['name'];
			$filesize = $coverImageFile['size'];
			$fileType = $coverImageFile['type'];
			$fileTempName = $coverImageFile['tmp_name'];
			
			$coverImgFilePath = $coverImgDirectory."/".$filename;
			
			move_uploaded_file($fileTempName, $coverImgFilePath);

            $this->imagickImage($coverImgFilePath,1024.0, 80 );
			
            
// 	print_r($originalWidth); die;
			$StaringDate = date("Y-m-d ");

			$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

			$qry = "INSERT INTO `tbesignaturealbum_projects`(`user_id`, `project_name`, `project_folder_name`, `proj_folder_path`, `cover_img_path`, `token`,`expiry_date` ) VALUES ($user_id,'$proj_name','$projFolder','$projFolder_path','$coverImgFilePath','$token','$newExpDate')";

			$result = $this->dbc->insert_row($qry);

			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=5 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];
			
			$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
			$userList = $this->dbc->get_rows($sql1);
			$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
			$eventUserEmail = $userList[0]['email'];

			$html = $mailTemplate[0]['mail_body'];

			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--project_name",$proj_name,$html);
			$html = str_replace("--token",$token,$html);
			$html = str_replace("--expiry_dt",$newExpDate,$html);

		
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

			//print_r($mailRes);

		}else{
			
			$sql = "SELECT * FROM `tbesignaturealbum_projects` WHERE id=".$proj_id;//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
			
			$result = $this->dbc->get_rows($sql);
			

			$coverImgDirectory = $result[0]['proj_folder_path'].'/coverImages';
			$oldFile = $result[0]['cover_img_path'];
			$project_name = $result[0]['project_name'];
			$coverImgFilePath = $result[0]['cover_img_path'];
			if($project_name !== $proj_name){
				$project_name =  $proj_name;
			}

			$filename = $coverImageFile['name'];
			$filesize = $coverImageFile['size'];
			$fileType = $coverImageFile['type'];
			$fileTempName = $coverImageFile['tmp_name'];
			if($filename !== ""){
				
				unlink($oldFile);
				// echo "Not empty"; die;
				$coverImgFilePath = $coverImgDirectory."/".$filename;
				move_uploaded_file($fileTempName, $coverImgFilePath);
				$this->imagickImage($coverImgFilePath,1024.0, 80 );
			}
			$qry = "UPDATE `tbesignaturealbum_projects` SET `project_name`='$project_name',`cover_img_path`='$coverImgFilePath' WHERE id=$proj_id";
			// echo $qry; die;

			$result = $this->dbc->update_row($qry);
		}
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}
	
	public function imagickImage($imgFilePath,$cDim, $quality ){
	    
			
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

	public function getSignatureAlbumsProjects(){
		// echo "I am here !---";
		$Output = array();
		$userId=$_REQUEST["userId"];

//(SELECT COUNT(*) FROM tbesignaturealbum_data WHERE project_folder_id = 28) AS viewCounts
        // $qry1 = "SELECT id FROM `tbesignaturealbum_projects` WHERE `user_id`=$userId AND `deleted`=0";
        // $result = $this->dbc->get_rows($qry1);
        // print_r($result->data);
        
        
		$qry = "SELECT * , (SELECT COUNT(*) FROM tbeproject_comments
        WHERE project_id = tbesignaturealbum_projects.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
        WHERE project_id = tbesignaturealbum_projects.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
        WHERE project_id = tbesignaturealbum_projects.id) AS shareCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
        WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
        WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0 )) AS imageCount FROM `tbesignaturealbum_projects` WHERE `user_id`=$userId AND `deleted`=0";
		$result = $this->dbc->get_rows($qry);

// 		print_r($result); die;

		foreach($result as $rs){
			$sid = $rs['id'];
			$planExpDate =  $rs['expiry_date'] ;

			$is_planAvl = 1;
		
			$row=array("id"=>$rs['id'],"user_id"=>$rs['user_id'],"project_name"=>$rs['project_name'],"project_folder_name"=>$rs['project_folder_name'],"proj_folder_path"=>$rs['proj_folder_path'],"cover_img_path"=>$rs['cover_img_path'],"deleted"=>$rs['deleted'],"crated_in"=>$rs['crated_in'],"commentCount"=>$rs['commentCount'],"viewCounts"=>$rs['viewCounts'],"shareCounts"=>$rs['shareCounts'],"is_planAvl"=>$is_planAvl,"planExpDate"=>$planExpDate, "imageCount"=>$rs['imageCount'], "eventsCount"=>$rs['eventsCount']);
			array_push($Output,$row);

		}

		if($result != "")self::sendResponse("1", $Output);
        else self::sendResponse("2", "Not found images");
	}

	public function editSignatureAlbumsProjects(){
		$projId=$_REQUEST["projId"];

		$qry = "SELECT * FROM `tbesignaturealbum_projects` WHERE `id`=$projId";
		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}

	public function saveSignatureAlbum(){

		$signatureAlbumEventFiles = $_FILES['signatureAlbumEventFiles']['name'];
		$user_id=$_REQUEST['selectedEventUserId'];
		$sigAlbmEventName=$_REQUEST['sigAlbmEventName'];
		$projId = $_REQUEST['selectedProjecEventtId'];
		$coverImage = $_FILES['EventCoverImgFile'];
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;

		$geProjQry = "SELECT * FROM `tbesignaturealbum_projects` WHERE id=$projId";
		$projDetails = $this->dbc->get_rows($geProjQry);
		
		$userFolderInsertedId = $projDetails[0]['id'];
		$userFolderName = $projDetails[0]['project_folder_name'];
		
		
		$t=time();
		$event_folder_name = $sigAlbmEventName."_".$t;
		$eventDirectory = $uploadDidectory.$userFolderName.'/'.$event_folder_name;
			
		mkdir($eventDirectory, 0777);

		$coverImgDirectory = $eventDirectory.'/coverImages';
		$coverImgDirectoryImagePath = $coverImgDirectory.'/'.$coverImage['name'][0];
		mkdir($coverImgDirectory, 0777);
		move_uploaded_file($coverImage['tmp_name'][0], $coverImgDirectoryImagePath);
		$this->imagickImage($coverImgDirectoryImagePath,1024.0, 80 );

		$eventThumpDirectory = $eventDirectory.'/'.'thumbnails';
		mkdir($eventThumpDirectory, 0777);

		$evntQry = "INSERT INTO `tbesignaturealbum_data`(`user_id`, `project_folder_id`, `folder_name`, `file_folder`, `cover_image_path`) VALUES ('$user_id','$projId','$sigAlbmEventName','$eventDirectory','$coverImgDirectoryImagePath')";

		$userFolderInsertedId = $this->dbc->insert_row($evntQry);
		
		
		
		// print_r($userFolderName);die;
		// $arquivo = array();
		// $file_ary = array();
		// $file_count = count($signatureAlbumEventFiles);
		// $file_keys = array_keys($signatureAlbumEventFiles);

	
		$countfiles = count($signatureAlbumEventFiles);

		
		for($i=0;$i<$countfiles;$i++){
			
			$filename = $_FILES['signatureAlbumEventFiles']['name'][$i];
			$filesize = $_FILES['signatureAlbumEventFiles']['size'][$i];
			$fileType = $_FILES['signatureAlbumEventFiles']['type'][$i];
			$fileTempName = $_FILES['signatureAlbumEventFiles']['tmp_name'][$i];
			
			if($fileType == "application/zip"){

				$targetFilePath = $eventDirectory."/".$filename;
				
				move_uploaded_file($fileTempName, $targetFilePath);
				
				$zip = new ZipArchive;
				$res = $zip->open($targetFilePath);
				if ($res === TRUE) {
					$zip->extractTo($eventDirectory);
					$zip->close();
					unlink($targetFilePath);
				} else {
					echo 'Unable to extract zip !';
				}	
			}else{
				
					$str_to_arry = explode('.',$filename);
					$ext   = end($str_to_arry);
					$fileActName = $str_to_arry[0]."_".$t.'.'.$ext;
					$targetFilePath = $eventDirectory."/".$fileActName;
					move_uploaded_file($fileTempName, $targetFilePath);
				// 	$this->imagickImage($targetFilePath,1024.0, 80 );
			}
		}
		
		$result = "";

		$handle = opendir($eventDirectory);
			if ($handle) {
				while (($entry = readdir($handle)) !== FALSE) {
					if($entry != '.' && $entry != '..'){
						$str_to_arry = explode('.',$entry);
						$extension   = end($str_to_arry);

						if($extension == 'jpg' || $extension == 'jpeg'){
						 
							$pth = $eventDirectory.'/'.$entry;
							$thmPth = $eventThumpDirectory."/";
							$filesize = filesize($pth);
							$thump_pth = $eventThumpDirectory.'/'.$entry;
				// 			die($pth."=======".$thump_pth);
							copy($pth, $thump_pth);
							$this->imagickImage($thump_pth,512.0, 80 );
							$this->imagickImage($pth,1024.0, 80 );
							// print_r($eventThumpDirectory);
				// 			$this->cwUpload($pth, $entry, TRUE, $thmPth,'400');
							// imagejpeg($image, $destination_url, $quality);
							$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`, `file_path`, `thumb_image_path`) VALUES ('$entry','$filesize','$userFolderInsertedId', '$pth', '$thump_pth')";
				// 			echo $qry1; die;
							$result = $this->dbc->insert_row($qry1);
						}
						
					}
					
				}
			}
		closedir($handle);
		// print_r($countfiles);die;
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}


	public function saveSignatureAlbum_old(){

		$zipFile = $_FILES['signatureAlbumFiles'];
		$user_id=$_REQUEST['selectedUserId'];
		$folder_name=$_REQUEST['sigAlbmFolderName'];
		$coverImage=$_FILES['signatureAlbumCover'];
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;

		$getUserQry = "SELECT * FROM `tblcontacts` WHERE id=$user_id";
		$userDetails = $this->dbc->get_rows($getUserQry);

		$userFolder = strtolower($userDetails[0]["firstname"])."_".strtolower($userDetails[0]["lastname"])."_".$user_id;
		$userFolderInsertedId = "";
		$userFolderName = "";
		if (!file_exists($uploadDidectory.$userFolder)) {
			mkdir($uploadDidectory.$userFolder, 0777, true);
			$userFolderQry = "INSERT INTO `tbesignaturealbum_userfolder`(`user_id`, `user_folder`) VALUES ($user_id, '$userFolder')";
			$userFolderInsertedId = $this->dbc->insert_row($userFolderQry);
			$userFolderName = $userFolder;
		}else{
			$getuserFolderQry = "SELECT * FROM `tbesignaturealbum_userfolder` WHERE user_id=$user_id";
			$userFolderDetails = $this->dbc->get_rows($getuserFolderQry);
			$userFolderInsertedId = $userFolderDetails[0]["id"];
			$userFolderName = $userFolderDetails[0]["user_folder"];
		}
		
		$t=time();
		$event_folder_name = $_REQUEST['selectedUserId']."_".$_REQUEST['sigAlbmFolderName']."_".$t;
		$eventDirectory = $uploadDidectory.$userFolderName.'/'.$event_folder_name;
		$eventThumpDirectory = $eventDirectory.'/'.'thumbnails';
		// print_r($event_folder_name);
		// print_r("<pre/>");
		$evntFolderQry = "INSERT INTO `tbesignaturealbum_data`(`user_id`, `project_folder_id`, `folder_name`, `file_folder`) VALUES ('$user_id', $userFolderInsertedId, '$folder_name','$eventDirectory')";
		
		$userFolderInsertedId = $this->dbc->insert_row($evntFolderQry);
		if (!file_exists($eventDirectory)) {
			mkdir($eventDirectory, 0777);
		}

		if (!file_exists($eventThumpDirectory)) {
			mkdir($eventThumpDirectory, 0777);
		}
		
		$coverImgDirectory = $eventDirectory.'/coverImages';
		$coverImgDirectoryImagePath = $eventDirectory.'/'.'coverImages/'.$coverImage['name'];
		if (!file_exists($coverImgDirectory)) {
			$coverFolderQry = "INSERT INTO `tbesignaturealbum_coverimage`(`folder_Id`, `image_path`, `cover_image`) VALUES ('$userFolderInsertedId', '$coverImgDirectoryImagePath', '$folder_name')";
			// print_r($coverImgDirectoryImagePath);
			$coverFolderId = $this->dbc->insert_row($coverFolderQry);
			mkdir($coverImgDirectory, 0777);
			move_uploaded_file($coverImage['tmp_name'], $coverImgDirectoryImagePath);
		}
		
		// print_r($userFolderName);die;
		$arquivo = array();
		$file_ary = array();
		$file_count = count($zipFile);
		$file_keys = array_keys($zipFile);

		$countfiles = count($_FILES['signatureAlbumFiles']);
		for($i=0;$i<$countfiles;$i++){

			$file_ary = array();
			$file_ary ["name"] =$_FILES['signatureAlbumFiles']['name'][$i];
			$file_ary ["type"] =$_FILES['signatureAlbumFiles']['type'][$i];
			$file_ary ["tmp_name"] =$_FILES['signatureAlbumFiles']['tmp_name'][$i];
			$file_ary ["size"] =$_FILES['signatureAlbumFiles']['size'][$i]; 
			$file_ary ["error"] = $_FILES['signatureAlbumFiles']["error"][$i];
			array_push($arquivo,$file_ary);
			
			$filename = $_FILES['signatureAlbumFiles']['name'][$i];
			$filesize = $_FILES['signatureAlbumFiles']['size'][$i];
			$fileType = $_FILES['signatureAlbumFiles']['type'][$i];
			$fileTempName = $_FILES['signatureAlbumFiles']['tmp_name'][$i];
			
			if($fileType == "application/zip"){

				$targetFilePath = $eventDirectory."/".$filename;
				
				move_uploaded_file($fileTempName, $targetFilePath);
				$zip = new ZipArchive;
				$res = $zip->open($targetFilePath);
				if ($res === TRUE) {
					$zip->extractTo($eventDirectory);
					$zip->close();
					unlink($targetFilePath);
				} else {
					echo 'Unable to extract zip !';
				}	
			}else{
				
				// if (!file_exists($targetFilePath)) {
				// 	print_r("sdsdsds");
					$str_to_arry = explode('.',$filename);
					$ext   = end($str_to_arry);
					$fileActName = $str_to_arry[0]."_".$t.'.'.$ext;
					$targetFilePath = $eventDirectory."/".$fileActName;
					move_uploaded_file($fileTempName, $targetFilePath);
				// }
				
			}
		}


		$result = "";

		$handle = opendir($eventDirectory);
			if ($handle) {
				while (($entry = readdir($handle)) !== FALSE) {
					if($entry != '.' && $entry != '..'){
						$str_to_arry = explode('.',$entry);
						$extension   = end($str_to_arry);

						if($extension == 'jpg'){
							$pth = $eventDirectory.'/'.$entry;
							$thmPth = $eventThumpDirectory."/";
							$filesize = filesize($pth);
							$thump_pth = $eventThumpDirectory.'/'.$entry;
							// print_r($eventThumpDirectory);
							$this->cwUpload($pth, $entry, TRUE, $thmPth,'400');
							// imagejpeg($image, $destination_url, $quality);
							$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`, `file_path`, `thumb_image_path`) VALUES ('$entry','$filesize','$userFolderInsertedId', '$pth', '$thump_pth')";
				// 			echo $qry1; die;
							$result = $this->dbc->insert_row($qry1);
						}
						
					}
					
				}
			}
		closedir($handle);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}

	function cwUpload( $target_folder = '', $file_name = '', $thumb = FALSE, $thumb_folder = '', $thumb_width = ''){

		//folder path setup
		$target_path = $target_folder;
		$thumb_path = $thumb_folder;

		$upload_image = $target_path;
			if($thumb == TRUE)
			{
				
				$thumbnail = $thumb_path.$file_name;
				
				list($width,$height) = getimagesize($upload_image);
				
				$source = imagecreatefromjpeg($upload_image);
				
				$width = imagesx($source);
    			$height = imagesy($source);
				// print_r($source);
				$thumb_height = floor($height * ($thumb_width / $width));
				
				$thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
				
	
				// imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				imagejpeg($source,$thumbnail,20);
				// imagejpeg($thumb_create,$thumbnail,90);
	
			}
	
			//return $fileName;
			return ;
	}

	public function getFilesFromFolder(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=(int)$_REQUEST["albumId"];
		$isHide=$_REQUEST["isHide"];
		$start=$_REQUEST["start"];
        
		if($isHide == 1){
// 			$sql = "SELECT * FROM tbesignalbm_folderfiles WHERE album_id=".$albumId." LIMIT 20 OFFSET ".$start;
			$sql = "SELECT * FROM tbesignalbm_folderfiles WHERE album_id=".$albumId." ORDER BY file_name";
		}else{
			$sql = "SELECT * FROM tbesignalbm_folderfiles
			WHERE album_id=".$albumId." AND hide =0 ORDER BY file_name";
		}
        
        //$sql = "SELECT * FROM tbesignalbm_folderfiles WHERE album_id=".$albumId." ";

		$result = $this->dbc->get_rows($sql);
		// print_r($result);
		// $arrFiles = array();
		// $handle = opendir(SIGNATUREALBUM_UPLOAD_PATH.$folderName);
		// // echo $folderName;die;
		// if ($handle) {
		// 	while (($entry = readdir($handle)) !== FALSE) {
		// 		$arrFiles[] = $entry;
		// 	}
		// }
		
		// closedir($handle);
		// // unset($arrFiles[0]);
		// // echo "sds";die;
		// $arrFiles = array_slice($arrFiles, 2); 
		// print_r($arrFiles);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}

	public function getSignatureAlbumList(){
        // echo("I am here !!!!!");
		// $id=$_REQUEST["id"];
		$statusFilter = $_REQUEST["statusFilter"];
		$cond = "";
		// print_r($statusFilter);die;
		if($statusFilter != "" && $statusFilter != "select"){
			$cond = " AND sa.status=".$statusFilter;
		}
		// die($cond);
		$sql = "SELECT sa.*, ct.firstname, ct.lastname, ct.email, ct.phonenumber, (SELECT COUNT(*) FROM tbesignalbm_folderfiles WHERE album_id = sa.id) AS imagecount  FROM tbesignaturealbum_data as sa 
		LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id
		WHERE sa.deleted=0 $cond ORDER BY sa.id DESC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		// print_r($sql);die;
		$result = $this->dbc->get_rows($sql);
        // print_r($sql);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}

	public function deleteSignatureAlbum(){
		$data=array();
		$data["id"]=(int)$_REQUEST['albumId'];
		
		// print_r($_REQUEST);die;
		$id = (int)$_REQUEST['albumId'];

		$query = "UPDATE `tbesignaturealbum_data` SET `deleted`='1' WHERE `id`=$id";
		
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		if($result != "")self::sendResponse("1", "Album deleted successfully");
        else self::sendResponse("2", "Not deleted album");
	}

	public function saveSignatureAlbumExtraFiles(){

		
		$zipFile = $_FILES['uploadSignatureAlbumFiles'];
		$UserId = $_REQUEST['selectedUplSigUserId'];
		//$signatureAlbumFiles = $_REQUEST['signatureAlbumFiles'];
		$AlbumId = $_REQUEST['selectedUplSigAlbmId'];
		$folderName = $_REQUEST['folderName'];
		$targetDir = $_REQUEST['selectedUplSigfile_folder'];
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;

		// print_r(sizeof($zipFile));
		$arquivo = array();
		$file_ary = array();
		$file_count = count($zipFile);
		$file_keys = array_keys($zipFile);

		$countfiles = count($_FILES['uploadSignatureAlbumFiles']['name']);

		// Looping all files
		for($i=0;$i<$countfiles;$i++){

			$file_ary = array();
			$file_ary ["name"] =$_FILES['uploadSignatureAlbumFiles']['name'][$i];
			$file_ary ["type"] =$_FILES['uploadSignatureAlbumFiles']['type'][$i];
			$file_ary ["tmp_name"] =$_FILES['uploadSignatureAlbumFiles']['tmp_name'][$i];
			$file_ary ["size"] =$_FILES['uploadSignatureAlbumFiles']['size'][$i]; 
			$file_ary ["error"] = $_FILES['uploadSignatureAlbumFiles']["error"][$i];
			array_push($arquivo,$file_ary);
			
		}
		$result = "";

		foreach($arquivo as $files)
		{
			// print_r($files["name"]);
			$filename = $files["name"];
			$filesize = $files["size"];
			
			$targetDir = $targetDir."/";
			$targetFilePath = $targetDir . $filename;
			$thumbnailsFilePath = $targetDir ."thumbnails/". $filename;

		
			move_uploaded_file($files['tmp_name'], $targetFilePath);
			copy($targetFilePath, $thumbnailsFilePath);
			$this->imagickImage($thumbnailsFilePath,512.0, 80 );
			$this->imagickImage($targetFilePath,1024.0, 80 );
			
			// $albmid = $files["name"];
			$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`,`file_path`,`thumb_image_path`) VALUES ('$filename','$filesize','$AlbumId','$targetFilePath','$thumbnailsFilePath')";
			$result = $this->dbc->insert_row($qry1);
		}
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted files");
	}

	public function hidePhoto(){


		$photoId = (int)$_REQUEST['photoId'];
		$projectId = (int)$_REQUEST['projectId'];
		$token = $_REQUEST['token'];

		$qr = "SELECT id FROM tbesignaturealbum_projects WHERE id=$projectId and token='$token' ";
		$res = $this->dbc->get_rows($qr);

		
		if(sizeof($res) > 0){

			$sqlD = "SELECT * FROM tbesignaturealbum_projects WHERE id=$projectId  ";
			$evtData = $this->dbc->get_rows($sqlD);
			$user_id = $evtData[0]['user_id'];

			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=9 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];

			$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
			$userList = $this->dbc->get_rows($sql1);
			$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
			$eventUserEmail = $userList[0]['email'];

			$html = $mailTemplate[0]['mail_body'];

		
			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--project_name",$evtData[0]['project_name'],$html);
			$html = str_replace("--token",$evtData[0]['token'],$html);
			$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

			$send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );


			$qry = "UPDATE `tbesignalbm_folderfiles` SET `hide`='1' WHERE id=$photoId";
			$result = $this->dbc->update_row($qry);

			if($result != "")self::sendResponse("1", "Image hide successfully");
			else self::sendResponse("2", "Not updated data");

		}else self::sendResponse("2", "Invalid token, Please try again!");

	}

	
	public function showPhoto(){
		$photoId = (int)$_REQUEST['photoId'];
		$projectId = (int)$_REQUEST['projectId'];
		$token = $_REQUEST['token'];

		$qr = "SELECT id FROM tbesignaturealbum_projects WHERE id=$projectId and token='$token' ";
		$res = $this->dbc->get_rows($qr);

		if(sizeof($res) > 0){

			$sqlD = "SELECT * FROM tbesignaturealbum_projects WHERE id=$projectId  ";
			$evtData = $this->dbc->get_rows($sqlD);
			$user_id = $evtData[0]['user_id'];

			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=10 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];

			$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
			$userList = $this->dbc->get_rows($sql1);
			$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
			$eventUserEmail = $userList[0]['email'];

			$html = $mailTemplate[0]['mail_body'];

		
			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--project_name",$evtData[0]['project_name'],$html);
			$html = str_replace("--token",$evtData[0]['token'],$html);
			$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

			$send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );


			$qry = "UPDATE `tbesignalbm_folderfiles` SET `hide`='0' WHERE id=$photoId";
			$result = $this->dbc->update_row($qry);

			if($result != "")self::sendResponse("1", "Image show successfully");
			else self::sendResponse("2", "Not updated data");

		}else self::sendResponse("2", "Invalid token, Please try again!");

	}

	
	public function saveExtendSADate(){

		$data=array();
		
		// print_r($_REQUEST);die;
		$id=(int)$_REQUEST['id'];
		$date=$_REQUEST['date'];

		$sqlD = "SELECT * FROM tbesignaturealbum_projects WHERE id=$id  ";
        $evtData = $this->dbc->get_rows($sqlD);
        $user_id = $evtData[0]['user_id'];

        $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=12 AND `active`=1 ";
        $mailTemplate = $this->dbc->get_rows($sqlM);

        //send mail here
        $subject = $mailTemplate[0]['subject'];

        $sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
        $userList = $this->dbc->get_rows($sql1);
        $eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
        $eventUserEmail = $userList[0]['email'];

        $html = $mailTemplate[0]['mail_body'];

      
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--project_name",$evtData[0]['project_name'],$html);
		$html = str_replace("--token",$evtData[0]['token'],$html);
		$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

        $send = new sendMails(true);
        $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

		$query = "UPDATE `tbesignaturealbum_projects` SET `expiry_date`='$date' WHERE `id`=$id";
		
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		if($result != "")self::sendResponse("1", "Signature album extend successfully");
        else self::sendResponse("2", "Not updated data");

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
		
	
		$vs = "INSERT INTO `tbeproject_shares`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}


}

?>