<?php

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

	public function getSignatureAlbums(){
        // echo("I am here !!!!!");
		$id=$_REQUEST["userId"];
		
		$sql = "SELECT sa.*, ct.firstname, ct.lastname FROM tbesignaturealbum_data as sa 
		LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id
		WHERE sa.user_id=".$id." AND sa.deleted=0 ORDER BY sa.id DESC";//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
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
	
	public function saveSignatureAlbum(){
		// print_r($_REQUEST);die;
		$data=array();
		$data["user_id"]=$_REQUEST['selectedUserId'];
		$data["folder_name"]=$_REQUEST['sigAlbmFolderName'];
		$user_id=$_REQUEST['selectedUserId'];
		$folder_name=$_REQUEST['sigAlbmFolderName'];
		// $data["created_by"]=0;
		$zipFile = $_FILES['signatureAlbumFiles'];
		$extension = pathinfo($zipFile['name'], PATHINFO_EXTENSION);
		// print_r($zipFile);
		if($extension === 'zip') {
			$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;
			
			$directory = $_REQUEST['selectedUserId']."_".$_REQUEST['sigAlbmFolderName'];
			mkdir($uploadDidectory.$directory, 0777);
			$targetDir = $uploadDidectory.$directory."/";
			$thumbDirec = $targetDir.'thumbnails/';
			mkdir($thumbDirec, 0777);
			
			$uploadedFileName = $_REQUEST['selectedUserId']."_".$_REQUEST['sigAlbmFolderName'].".".$extension;
			$targetFilePath = $targetDir . $uploadedFileName;
			move_uploaded_file($zipFile['tmp_name'], $targetFilePath);
			
			$zip = new ZipArchive;
			$res = $zip->open($targetFilePath);
			if ($res === TRUE) {
				$zip->extractTo($targetDir);
				$zip->close();
				unlink($targetFilePath);
			} else {
				echo 'Unable to extract zip !';
			}
		}
		$data["file_folder"] = $directory;
		$file_folder = $directory;
		$qry = "INSERT INTO `tbesignaturealbum_data`(`user_id`, `folder_name`, `file_folder`) VALUES ('$user_id','$folder_name','$file_folder')";
		
		$result = $this->dbc->insert_row($qry);
		// print_r($directory);
		$arrFiles = array();
		$handle = opendir(SIGNATUREALBUM_UPLOAD_PATH.$directory);
		if ($handle) {
			while (($entry = readdir($handle)) !== FALSE) {

				
				if($entry != '.' && $entry != '..'){
					$str_to_arry = explode('.',$entry);
					
					$extension   = end($str_to_arry);
					
					if($extension == 'jpg'){
						$filesize = filesize($targetDir.$entry);
						
						// print_r($filesize);
						$this->cwUpload($targetDir.$entry, $entry, TRUE, $thumbDirec,'150');
						$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`) VALUES ('$entry','$filesize','$result')";
						$result1 = $this->dbc->insert_row($qry1);
						$arrFiles[] = $entry;
					}
					
				}
				
			}
		}
		
		closedir($handle);
		
		// die;

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
				
	
				imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
				imagejpeg($thumb_create,$thumbnail,100);
	
			}
	
			return $fileName;
	}

	public function saveSignatureAlbumOld(){
		// print_r($_REQUEST);die;
		$data=array();
		$data["user_id"]=$_REQUEST['selectedUserId'];
		$data["folder_name"]=$_REQUEST['sigAlbmFolderName'];
		$user_id=$_REQUEST['selectedUserId'];
		$folder_name=$_REQUEST['sigAlbmFolderName'];
		// $data["created_by"]=0;
		$zipFile = $_FILES['signatureAlbumFiles'];
		$extension = pathinfo($zipFile['name'], PATHINFO_EXTENSION);
		// print_r($zipFile);
		if($extension === 'zip') {
			$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;
			$directory = $_REQUEST['selectedUserId']."_".$_REQUEST['sigAlbmFolderName'];
			mkdir($uploadDidectory.$directory, 0777);
			$targetDir = $uploadDidectory.$directory."/";
			$uploadedFileName = $_REQUEST['selectedUserId']."_".$_REQUEST['sigAlbmFolderName'].".".$extension;
			$targetFilePath = $targetDir . $uploadedFileName;
			move_uploaded_file($zipFile['tmp_name'], $targetFilePath);
			
			$zip = new ZipArchive;
			$res = $zip->open($targetFilePath);
			if ($res === TRUE) {
				$zip->extractTo($targetDir);
				$zip->close();
				unlink($targetFilePath);
			} else {
				echo 'Unable to extract zip !';
			}
		}
		$data["file_folder"] = $directory;
		$file_folder = $directory;
		$qry = "INSERT INTO `tbesignaturealbum_data`(`user_id`, `folder_name`, `file_folder`) VALUES ('$user_id','$folder_name','$file_folder')";
		// $result = $this->dbc->InsertUpdate($data, 'tbesignaturealbum_data');
		$result = $this->dbc->insert_row($qry);
		// print_r($directory);
		$arrFiles = array();
		$handle = opendir(SIGNATUREALBUM_UPLOAD_PATH.$directory);
		if ($handle) {
			while (($entry = readdir($handle)) !== FALSE) {

				
				if($entry != '.' && $entry != '..'){
					$str_to_arry = explode('.',$entry);
					
					$extension   = end($str_to_arry);
					
					if($extension == 'jpg'){
						$filesize = filesize($targetDir.$entry);
						// print_r($filesize);
						$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`) VALUES ('$entry','$filesize','$result')";
						$result1 = $this->dbc->insert_row($qry1);
						$arrFiles[] = $entry;
					}
					
				}
				
			}
		}
		
		closedir($handle);
		// print_r($arrFiles);
		// die;

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}

	public function getFilesFromFolder(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=$_REQUEST["albumId"];

		$sql = "SELECT * FROM tbesignalbm_folderfiles
		WHERE album_id=".$albumId ;//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
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
		$data["id"]=$_REQUEST['albumId'];
		
		// print_r($_REQUEST);die;
		$id = $_REQUEST['albumId'];

		$query = "UPDATE `tbesignaturealbum_data` SET `deleted`='1' WHERE `id`=$id";
		
		// $result = $this->dbc->InsertUpdate($data, 'tbevents_data');
		$result = $this->dbc->update_row($query);

		if($result != "")self::sendResponse("1", "Album deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}

	public function saveSignatureAlbumExtraFiles(){
		
		$zipFile = $_FILES['uploadSignatureAlbumFiles'];
		$UserId = $_REQUEST['selectedUplSigUserId'];
		$signatureAlbumFiles = $_REQUEST['signatureAlbumFiles'];
		$AlbumId = $_REQUEST['selectedUplSigAlbmId'];
		$folderName = $_REQUEST['folderName'];
		
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
			$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;

			$directory = $UserId."_".$folderName;
			$targetDir = $uploadDidectory.$directory."/";
			$targetFilePath = $targetDir . $filename;
			$thumbDirec = $targetDir.'thumbnails/';
			move_uploaded_file($files['tmp_name'], $targetFilePath);
			
			$this->cwUpload($targetFilePath, $filename, TRUE, $thumbDirec,'150');
			// $albmid = $files["name"];
			$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`) VALUES ('$filename','$filesize','$AlbumId')";
			$result = $this->dbc->insert_row($qry1);
		}
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted files");
	}
}

?>