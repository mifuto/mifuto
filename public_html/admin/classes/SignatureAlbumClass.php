<?php
require_once('sendMailClass.php');
require_once('DashboardClass.php');
require_once('vendor/autoload.php');
require_once('PHPExcel.php');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class SignatureAlbum {
    private $dbc;
    private $error_message;
    
  
    private $bucketName;
    private $s3Client;
    

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	    
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
	
	public function DwdSAlbumExcelList(){
	    
	    $data=$_REQUEST["data"];
	    
	    // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");
                                     
                                     
                                     
        
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Email')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Phone Number')
                    ->setCellValue('D1', 'Expiry Date');
                    
        $i = 1;
                    
        foreach($data as $val){
            $i++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$i", $val[0])
                    ->setCellValue("B$i", $val[1])
                    ->setCellValue("C$i", $val[2])
                    ->setCellValue("D$i", $val[3]);
	    }
        
       
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Save the file on the server
        $filePath = 'signature-album-info.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);
        
        // Return the file path
        self::sendResponse("1", $filePath);

	    
	    
	}
	
	public function DwdOAlbumExcelList(){
	    
	    $data=$_REQUEST["data"];
	    
	    // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");
                                     
                                     
                                     
        
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Email')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Phone Number')
                    ->setCellValue('D1', 'Expiry Date');
                    
        $i = 1;
                    
        foreach($data as $val){
            $i++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$i", $val[0])
                    ->setCellValue("B$i", $val[1])
                    ->setCellValue("C$i", $val[2])
                    ->setCellValue("D$i", $val[3]);
	    }
        
       
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Save the file on the server
        $filePath = 'online-album-info.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);
        
        // Return the file path
        self::sendResponse("1", $filePath);

	    
	    
	}
	
	
	public function DwdMainUserExcelList(){
	    
	    $data=$_REQUEST["data"];
	    
	    // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");
                                     
                                     
                                     
        
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Email')
                    ->setCellValue('B1', 'Name')
                    ->setCellValue('C1', 'Phone Number');

        $i = 1;
                    
        foreach($data as $val){
            $i++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$i", $val[0])
                    ->setCellValue("B$i", $val[1])
                    ->setCellValue("C$i", $val[2]);
	    }
        
       
        
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        
        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Save the file on the server
        $filePath = 'main-users-info.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);
        
        // Return the file path
        self::sendResponse("1", $filePath);

	    
	    
	}
	
	
	
	public function getSAlbumList(){
	    
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
                   $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber ,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE ev.deleted=0 $where ORDER BY ev.id DESC";
			
		
               }else{
                   
                    if($manage_type == 'County'){
                       // user type County
                       $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.country = '$county_id' and ev.deleted=0 $where ORDER BY ev.id DESC";
                     
                      
                   }else if($manage_type == 'State'){
                       // user type State
                          $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.state = '$state' and ev.deleted=0 $where ORDER BY ev.id DESC";
                     
                     
                   }else {
                       // user type City
                          $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.city = '$city' and ev.deleted=0 $where ORDER BY ev.id DESC";
                     
                   }
                   
               }
	        
	    
	 
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	public function getSAlbumListDashboard(){
	    
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
                   $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber ,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
			
		
               }else{
                   
                    if($manage_type == 'County'){
                       // user type County
                       $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.country = '$county_id' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
                     
                      
                   }else if($manage_type == 'State'){
                       // user type State
                          $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.state = '$state' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
                     
                     
                   }else {
                       // user type City
                          $sql = "SELECT ev.project_name as event_name,ev.expiry_date,ev.crated_in as created_date, ev.id , ct.firstname, ct.lastname,ct.email ,ct.phonenumber,z.short_name as country,cct.city,cct.state , (SELECT COUNT(*) FROM tbeproject_comments
    WHERE project_id = ev.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
    WHERE project_id = ev.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
    WHERE project_folder_id = ev.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
    WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = ev.id AND deleted=0 )) AS imageCount FROM tbesignaturealbum_projects as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid left join tblcountries z on z.country_id = cct.country
			WHERE cct.city = '$city' and ev.deleted=0 $where ORDER BY ev.id DESC LIMIT 50";
                     
                   }
                   
               }
	        
	    
	 
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	

	public function addMoreSelImgs(){
		
		$eventID=(int)$_REQUEST["eventID"];
		$selType=(int)$_REQUEST["selType"];
		
		if($selType == 0){
		    $query = "UPDATE `tbesignaturealbum_data` SET `completeImgSel`=0 WHERE `id`='$eventID' ";
		    $result = $this->dbc->update_row($query);
		}else{
		    $user_id = (int)$_REQUEST["user_id"];
		    
		    $query = "UPDATE `tbesignaturealbum_subuser_data` SET `completeImgSel`=0 WHERE `album_id`='$eventID' and `user_id`='$user_id'  ";
		    $result = $this->dbc->update_row($query);
		    
		    
		}
		
		
		
		if($result != "")self::sendResponse("1", "Image remove success");
        else self::sendResponse("2", "Error");
	}
	
	public function removeFrmList(){
		
		$imgID=(int)$_REQUEST["imgID"];
		$selType=(int)$_REQUEST["selType"];
		
		if($selType == 0){
		    $query = "UPDATE `tbesignalbm_folderfiles` SET `user_sel_img`=0 WHERE `id`='$imgID' ";
		    $result = $this->dbc->update_row($query);
		}else{
		    $user_id = (int)$_REQUEST["user_id"];
		    $query = "UPDATE `tbesignalbm_subuser_folderfiles` SET `user_sel_img`=0 WHERE `image_id`='$imgID' and `user_id`='$user_id' ";
		    $result = $this->dbc->update_row($query);
		    
		}
		
		
		
		
		
		if($result != "")self::sendResponse("1", "Image remove success");
        else self::sendResponse("2", "Error");
	}
	
	public function removeMulFrmList(){
		
		$selType=(int)$_REQUEST["selType"];
		$checkedValues = $_REQUEST["imgID"];
		 
		foreach ($checkedValues as $chk) {
		    
		    if($selType == 0){
    		    $query = "UPDATE `tbesignalbm_folderfiles` SET `user_sel_img`=0 WHERE `id`='$chk' ";
    		    $result = $this->dbc->update_row($query);
    		}else{
    		    $user_id = (int)$_REQUEST["user_id"];
    		    $query = "UPDATE `tbesignalbm_subuser_folderfiles` SET `user_sel_img`=0 WHERE `image_id`='$chk' and `user_id`='$user_id' ";
    		    $result = $this->dbc->update_row($query);
    		    
    		}
		    
        }
        
		if($result != "")self::sendResponse("1", "Image remove success");
        else self::sendResponse("2", "Error");
	}
	

	
	public function getFilesFromFolderSel(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$selType=(int)$_REQUEST["selType"];
		
		if($selType == 0){
		    $sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." and a.user_sel_img = 1 ORDER BY a.file_name asc";
		}else{
		    
		    $user_id = (int)$_REQUEST["user_id"];
		    
		    
		    $sql = "SELECT a.*,b.imageSel,c.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id left join tbesignaturealbum_subuser_data c on c.album_id = b.id left join tbesignalbm_subuser_folderfiles d on d.image_id = a.id WHERE a.album_id=".$albumId." and d.user_sel_img = 1 and c.user_id =".$user_id." and d.user_id =".$user_id." ORDER BY a.file_name asc";
		  
		}
	
     
		$result = $this->dbc->get_rows($sql);
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}
	
	
	
	public function getSelAlbumFiles(){
		
		$albumId=(int)$_REQUEST["albumId"];

		$sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." AND a.user_sel_img=1 ORDER BY a.file_name asc";
	
		$result = $this->dbc->get_rows($sql);
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}
	
	public function getSelImgs(){
	    
		$albumId=$_REQUEST["albumId"];
		$albumDisList=$_REQUEST["albumDisList"];
		$albumSubUserList=$_REQUEST["albumSubUserList"];
		
		if($albumSubUserList == ""){
		    $sql = "SELECT * FROM tbesignalbm_folderfiles
			WHERE user_sel_img=1 and album_id='$albumId' ORDER BY file_name ASC";
		}else{
		    
		    $sql = "SELECT a.* FROM tbesignalbm_folderfiles a left join tbesignalbm_subuser_folderfiles b on b.image_id = a.id
			WHERE b.user_sel_img=1 and a.album_id='$albumId' and b.user_id ='$albumSubUserList'  ORDER BY a.file_name ASC";
		
		}
		
		
			
		

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getNoOfComEvents(){
	    $count = 0;
	    
	    	 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
        if($isAdmin){
    	    $sql = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_data
			WHERE completeImgSel=1 and deleted=0 ";
			
			$sql1 = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_subuser_data
			WHERE completeImgSel=1 ";
	
        }else{
            
            if($manage_type == 'County'){
            // user type County
            
            $sql = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and deleted=0 and cct.country = '$county_id' ";
			
			$sql1 = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_subuser_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and cct.country = '$county_id' ";
			
                           
           }else if($manage_type == 'State'){
               // user type State
               
               $sql = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and deleted=0 and cct.state = '$state' ";
			
			$sql1 = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_subuser_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and cct.state = '$state' ";
               
           }else {
               // user type City
               
               $sql = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and deleted=0 and cct.city = '$city' ";
			
			$sql1 = "SELECT COUNT(*) as Cunt FROM tbesignaturealbum_subuser_data a LEFT JOIN tblcontacts as ct ON ct.id = a.user_id left join tblclients cct on cct.userid = ct.userid
			WHERE completeImgSel=1 and cct.city = '$city' ";
               
           }
            
            
            
            
        }
	    
	  

		$result = $this->dbc->get_rows($sql);
		$count = $count + intval($result[0]['Cunt']);
		
		$result1 = $this->dbc->get_rows($sql1);
		
		$count = $count + intval($result1[0]['Cunt']);
      
        if($result != "")self::sendResponse("1", $count);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function setSelImgsAsFinished(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$albumSubUserList=$_REQUEST["albumSubUserList"];
		
		if($albumSubUserList == ''){
		    $query = "UPDATE `tbesignaturealbum_data` SET `completeImgSel`=2 WHERE `id`=$albumId";
		    $result = $this->dbc->update_row($query);
		}else{
		    $query = "UPDATE `tbesignaturealbum_subuser_data` SET `completeImgSel`=2 WHERE `album_id`='$albumId' and user_id='$albumSubUserList' ";
		    $result = $this->dbc->update_row($query);
		}
		
		
		
		if($result != "")self::sendResponse("1", "Event image finish successfully");
        else self::sendResponse("2", "Error");
	}
	
	public function setSelImgsAsreset(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$albumSubUserList=$_REQUEST["albumSubUserList"];
		
		if($albumSubUserList == ''){
		
		    $query = "UPDATE `tbesignaturealbum_data` SET `completeImgSel`=5 WHERE `id`=$albumId";
		    
		}else{
		    $query = "UPDATE `tbesignaturealbum_subuser_data` SET `completeImgSel`=5 WHERE `album_id`='$albumId' and user_id='$albumSubUserList' ";
		}
		
		$result = $this->dbc->update_row($query);
		
		if($result != "")self::sendResponse("1", "Event image release successfully");
        else self::sendResponse("2", "Error");
	}
	
	public function getEventUserEventList(){
	    
	    $albumUserList = (int)$_REQUEST['albumUserList'];
	    $albumDisList=$_REQUEST["albumDisList"];
	    $albumUserProjectList=$_REQUEST["albumUserProjectList"];
	    $albumSubUserList=$_REQUEST["albumSubUserList"];
	    
	    if($albumSubUserList == ""){
	        $sql = "SELECT a.id, a.folder_name FROM tbesignaturealbum_data a left join tbesignaturealbum_projects b on b.id = a.project_folder_id WHERE a.user_id=$albumUserList and a.deleted=0 and a.completeImgSel = $albumDisList and b.id=$albumUserProjectList ORDER BY a.folder_name ASC";
		    $result = $this->dbc->get_rows($sql);
	    }else{
	        
	        $sql = "SELECT a.id, a.folder_name FROM tbesignaturealbum_data a left join tbesignaturealbum_projects b on b.id = a.project_folder_id left join tbesignaturealbum_subuser_data c on c.album_id = a.id WHERE a.user_id=$albumUserList and a.deleted=0 and c.completeImgSel = $albumDisList and b.id=$albumUserProjectList and c.user_id = $albumSubUserList  ORDER BY a.folder_name ASC";
		    $result = $this->dbc->get_rows($sql);
		    
		   
	    }
	    
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	
	}
	
	public function getProjectEventsForSel(){
		$projId = (int) $_REQUEST["projId"];
		$selType = (int) $_REQUEST["selType"];
		
		if($selType == 0){
		    	$sql = "SELECT sa.*, cvi.image_path, ct.firstname, ct.lastname FROM tbesignaturealbum_data as sa LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id LEFT JOIN tbesignaturealbum_coverimage as cvi ON cvi.folder_Id=sa.id WHERE sa.project_folder_id=".$projId." AND sa.deleted=0 AND sa.completeImgSel = 5 ORDER BY sa.id DESC";

		}else{
		    
		    $user_id = (int) $_REQUEST["user_id"];
		    
		    $sql = "SELECT sa.*, cvi.image_path, ct.firstname, ct.lastname,sub.completeImgSel as completeSubuserImgSel FROM tbesignaturealbum_data as sa LEFT JOIN tblcontacts as ct ON ct.id = sa.user_id LEFT JOIN tbesignaturealbum_coverimage as cvi ON cvi.folder_Id=sa.id left join tbesignaturealbum_subuser_data sub on sub.album_id = sa.id WHERE sa.project_folder_id=".$projId." AND sa.deleted=0 AND sub.completeImgSel = 5 AND sub.user_id =".$user_id."  ORDER BY sa.id DESC";
		    
		}
	

	
	
		$result = $this->dbc->get_rows($sql);

		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function getEventUserProjectList(){
	    
	    $albumUserList = (int)$_REQUEST['albumUserList'];
	    $albumDisList=$_REQUEST["albumDisList"];
	    
	    $albumSubUserList=$_REQUEST["albumSubUserList"];
	    
	    if($albumSubUserList == ""){
	        $sql = "SELECT DISTINCT a.id, a.project_name FROM tbesignaturealbum_projects a left join tbesignaturealbum_data b on a.id = b.project_folder_id WHERE a.user_id=$albumUserList and b.deleted=0 and b.completeImgSel = $albumDisList and a.deleted = 0 ORDER BY a.project_name ASC";
	    }else{
	        
	        $sql = "SELECT DISTINCT a.id, a.project_name FROM tbesignaturealbum_projects a left join tbesignaturealbum_data b on a.id = b.project_folder_id left join tbesignaturealbum_subuser_data c on c.album_id = b.id WHERE a.user_id=$albumUserList and b.deleted=0 and c.completeImgSel = $albumDisList and a.deleted = 0 and c.user_id =$albumSubUserList ORDER BY a.project_name ASC";
	       
	    }
	    
	    
       
		$result = $this->dbc->get_rows($sql);
        
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	
	}
	
	
	public function fetchUserLists(){
	    
	    $albumDisList=$_REQUEST["albumDisList"];
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
           
       if($isAdmin){
           $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id where s.deleted=0 and s.completeImgSel = $albumDisList  ORDER BY a.firstname ASC";
       }else{
           
            if($manage_type == 'County'){
               // user type County
               $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id where cct.country = '$county_id' and s.deleted=0 and s.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
              
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id where cct.state = '$state' and s.deleted=0 and s.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
             
           }else {
               // user type City
                $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id where cct.city = '$city' and s.deleted=0 and s.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
           }
           
       }
       
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	public function fetchSubUserLists(){
	    
	    $albumDisList=$_REQUEST["albumDisList"];
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
           
       if($isAdmin){
           $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id left join tbesignaturealbum_subuser_data sub on sub.album_id = s.id where s.deleted=0 and sub.completeImgSel = $albumDisList  ORDER BY a.firstname ASC";
       }else{
           
            if($manage_type == 'County'){
               // user type County
               $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id left join tbesignaturealbum_subuser_data sub on sub.album_id = s.id where cct.country = '$county_id' and s.deleted=0 and sub.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
              
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id left join tbesignaturealbum_subuser_data sub on sub.album_id = s.id where cct.state = '$state' and s.deleted=0 and sub.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
             
           }else {
               // user type City
                $sql = "SELECT DISTINCT a.*,z.short_name as country,cct.city,cct.state FROM tblcontacts a left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country left join tbesignaturealbum_data s on a.id = s.user_id left join tbesignaturealbum_subuser_data sub on sub.album_id = s.id where cct.city = '$city' and s.deleted=0 and sub.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
           }
           
       }
       
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	
	
	public function getEventUserList(){
	    
	    $albumDisList=$_REQUEST["albumDisList"];
       
		$sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM tblcontacts a left join tbesignaturealbum_data b on a.id = b.user_id WHERE a.active=1 and b.deleted=0 and b.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
		$result = $this->dbc->get_rows($sql);
        
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	
	}
	
	public function getEventSubUserList(){
	    
	    $albumUserList=$_REQUEST["albumUserList"];
	    $albumDisList=$_REQUEST["albumDisList"];
	    
	    $sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM tblcontacts a left join tbesignaturealbum_subuser_data sub on sub.user_id = a.id left join tbesignaturealbum_data b on b.id = sub.album_id WHERE a.active=1 and a.main_user_id = '$albumUserList' and b.deleted=0 and sub.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
	   
// 		$sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM tblcontacts a left join tbesignaturealbum_data b on a.id = b.user_id left join tbesignaturealbum_subuser_data sub on sub.album_id = b.id WHERE a.active=1 and b.user_id = '$albumUserList' and b.deleted=0 and sub.completeImgSel = $albumDisList ORDER BY a.firstname ASC";
		$result = $this->dbc->get_rows($sql);

        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	
	}
	
	
	
	public function hideEvents(){
		$status = (int)$_REQUEST['status'];
		$evtId = (int)$_REQUEST['evtId'];
		
		if($status == 1)$sts="Show";
		else $sts="Hide";
	

		$qry = "UPDATE `tbesignaturealbum_data` SET `status`=$status WHERE id=$evtId";
		$result = $this->dbc->update_row($qry);
		
		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name , b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$evtId ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$fldName = $AlbumList[0]['folder_name'];
		$user_id = $AlbumList[0]['user_id'];
		$projId = $AlbumList[0]['projId'];

		$recentActivity = new Dashboard(true);
        $activityMeg = $sts." event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
        
        
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = $sts." event ".$fldName." for signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,$encodedStringUrl);

		if($result != "")self::sendResponse("1", "Success");
		else self::sendResponse("2", "Not updated data");

	}
	
	
	public function enableDisEvent(){
		$status = (int)$_REQUEST['status'];
		$evtId = (int)$_REQUEST['evtId'];
		
		if($status == 1)$sts="Enable image selecting";
		else $sts="Disable image selecting";
	

		$qry = "UPDATE `tbesignaturealbum_data` SET `imageSel`=$status WHERE id=$evtId";
		$result = $this->dbc->update_row($qry);
		
		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name , b.id as user_id , a.id as projId ,a.token,a.expiry_date FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$evtId ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$fldName = $AlbumList[0]['folder_name'];
		$user_id = $AlbumList[0]['user_id'];
		$projId = $AlbumList[0]['projId'];
		
		$token = $AlbumList[0]['token'];
		$expiry_date = $AlbumList[0]['expiry_date'];

		$recentActivity = new Dashboard(true);
        $activityMeg = $sts." event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
        
        
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = $sts." event ".$fldName." for signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,$encodedStringUrl);
		
		if($status == 1){
		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=2 AND mail_template=78 AND `active`=1 ";
            $mailTemplate = $this->dbc->get_rows($sqlM);
    
            //send mail here
            $subject = $mailTemplate[0]['subject'];
    
            $sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
            $userList = $this->dbc->get_rows($sql1);
            $eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
            $eventUserEmail = $userList[0]['email'];
    
            $html = $mailTemplate[0]['mail_body'];
    
          
    		$html = str_replace("--username",$eventUser,$html);
    		$html = str_replace("--project_name",$prjName,$html);
    		$html = str_replace("--token",$token,$html);
    		$html = str_replace("--expiry_dt",$expiry_date,$html);
    		$html = str_replace("--event_name",$fldName,$html);
    
            $send = new sendMails(true);
            $mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		}
		
	

		if($result != "")self::sendResponse("1", "Success");
		else self::sendResponse("2", "Not updated data");

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
		    
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				//to check ip is pass from proxy
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
			$vs = "INSERT INTO `tbeproject_views`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		
			$this->dbc->insert_row($vs);
		

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

		$recentActivity = new Dashboard(true);
		$prjName = $evtData[0]['project_name'];
		
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		$activityMeg = "Signature album ".$prjName." for ".$eventUser." is deleted by ".$isUsername;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Signature album ".$prjName." is deleted";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,'signature_album.php');

        $send = new sendMails(true);
        $mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

		$qry = "UPDATE `tbesignaturealbum_projects` SET `deleted`='1' WHERE id=$projId";
		$result = $this->dbc->update_row($qry);

		if($result != "")self::sendResponse("1", "Project deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	public function deleteImageFromAlbum(){
	    $imagid = $_REQUEST["image"];
	  

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name , b.id as user_id , a.id as pId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id left join tbesignalbm_folderfiles d on c.id = d.album_id WHERE d.id=$imagid ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$fldName = $AlbumList[0]['folder_name'];
		$user_id = $AlbumList[0]['user_id'];
		$pId = $AlbumList[0]['pId'];

		$recentActivity = new Dashboard(true);
		
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       
        $activityMeg = $isUsername." delete Image from event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Delete Image from event ".$fldName." for signature album ".$prjName;
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$pId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,$encodedStringUrl);

		$sql = "DELETE FROM `tbesignalbm_folderfiles` WHERE id=".$imagid;
	    $result = $this->dbc->query($sql);

        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function deleteMultipleImageFromAlbum(){
	    $checkedValues = $_REQUEST["image"];
	    $imagid = $checkedValues[0];
	    
	   
	    
	  
		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name , b.id as user_id , a.id as pId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id left join tbesignalbm_folderfiles d on c.id = d.album_id WHERE d.id=$imagid ";

		$AlbumList = $this->dbc->get_rows($sql1);
		
	
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$fldName = $AlbumList[0]['folder_name'];
		$user_id = $AlbumList[0]['user_id'];
		$pId = $AlbumList[0]['pId'];

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
        $activityMeg = $isUsername." delete multiple images from event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Delete multiple images from event ".$fldName." for signature album ".$prjName;
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$pId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,$encodedStringUrl);
		
		foreach ($checkedValues as $chk) {
            	$sql = "DELETE FROM `tbesignalbm_folderfiles` WHERE id=".$chk;
	            $result = $this->dbc->query($sql);
        }

        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
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
		$view_token = rand(1000, 9999);

		// print_r($proj_id);die;
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;
		
	
		$t=time();
		if($proj_id == ""){
		    
		    $proj_nameF = preg_replace('/[^A-Za-z0-9]/', '', $proj_name);
			
			$projFolder = $proj_nameF."_".$user_id."_".$t;
			$projFolder_path = $uploadDidectory.$projFolder;
			// echo $projFolder_path;
// 			mkdir($projFolder_path, 0777, true);

			$coverImgDirectory = $projFolder_path.'/coverImages';
// 			mkdir($coverImgDirectory, 0777, true);
			
			$countfiles = count($coverImageFile);
			// print_r($coverImageFile);
			$filename = $coverImageFile['name'];
			$filesize = $coverImageFile['size'];
			$fileType = $coverImageFile['type'];
			$fileTempName = $coverImageFile['tmp_name'];
			
			$coverImgFilePath = $coverImgDirectory."/".$filename;
			
// 			move_uploaded_file($fileTempName, $coverImgFilePath);
			
			
			
			 $imagePath1 = $fileTempName;

            $targetFilePath1 = $coverImgFilePath;
            
            $targetSizeMB = 2;
            
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
            
            // print_r($credentials);
            
          
            try {
                // Upload the file to S3
                $out = $this->s3Client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => $targetFilePath1,
                    'SourceFile' => $imagePath1,
                ]);
                
                $coverImgFilePath = $out['ObjectURL'];
            
               
            } catch (AwsException $e) {
                // Handle errors
                // echo 'Error uploading image: ' . $e->getMessage();
                self::sendResponse("2", $e->getMessage());
                die;
            }
        
            // move_uploaded_file($imagePath1, $targetFilePath1);
		
		

            // $this->imagickImage($coverImgFilePath,1024.0, 90 );
			
            
// 	print_r($originalWidth); die;
			$StaringDate = date("Y-m-d ");

			$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

			$qry = "INSERT INTO `tbesignaturealbum_projects`(`user_id`, `project_name`, `project_folder_name`, `proj_folder_path`, `cover_img_path`, `token`,`expiry_date` ,`view_token`) VALUES ($user_id,'$proj_name','$projFolder','$projFolder_path','$coverImgFilePath','$token','$newExpDate','$view_token')";

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
			$html = str_replace("--view_token",$view_token,$html);

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		$activityMeg = "New signature album ".$proj_name." for user ".$eventUser." is created by ".$isUsername;
			
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

			$activityMeg1 = "Signature album ".$proj_name." is created ";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,'signature_album.php');

		
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

			//print_r($mailRes);

		}else{
			
			$sql = "SELECT * FROM `tbesignaturealbum_projects` WHERE id=".$proj_id;//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
			
			$result = $this->dbc->get_rows($sql);
			

			$coverImgDirectory = $result[0]['proj_folder_path'].'/coverImages';
			$oldFile = $result[0]['cover_img_path'];
			$project_name = $result[0]['project_name'];
			$coverImgFilePath = $result[0]['cover_img_path'];
			if($project_name != $proj_name){
				$project_name =  $proj_name;
			}

			$filename = $coverImageFile['name'];
			$filesize = $coverImageFile['size'];
			$fileType = $coverImageFile['type'];
			$fileTempName = $coverImageFile['tmp_name'];
			if($filename !== ""){
				
				// unlink($oldFile);
				// echo "Not empty"; die;
				$coverImgFilePath = $coverImgDirectory."/".uniqid() . '-'.$filename;
				// move_uploaded_file($fileTempName, $coverImgFilePath);
				
				
				
				
				 $imagePath1 = $fileTempName;
    
                $targetFilePath1 = $coverImgFilePath;
                
                $targetSizeMB = 2;
                
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
                
                try {
                // Upload the file to S3
                    $out = $this->s3Client->putObject([
                        'Bucket' => $this->bucketName,
                        'Key'    => $targetFilePath1,
                        'SourceFile' => $imagePath1,
                    ]);
                    
                    $coverImgFilePath = $out['ObjectURL'];
                    
                
                   
                } catch (AwsException $e) {
                    // Handle errors
                    // echo 'Error uploading image: ' . $e->getMessage();
                    self::sendResponse("2", $e->getMessage());
                    die;
                }
                
               
                // move_uploaded_file($imagePath1, $targetFilePath1);
				
				
				
				
				// $this->imagickImage($coverImgFilePath,1024.0, 90 );
			}
			$qry = "UPDATE `tbesignaturealbum_projects` SET `project_name`='$project_name',`cover_img_path`='$coverImgFilePath',`upload_server`=1 WHERE id=$proj_id ";
// 			echo $qry;
			
			$result = $this->dbc->update_row($qry);
		
			$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
			$userList = $this->dbc->get_rows($sql1);
			$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];

			$recentActivity = new Dashboard(true);
			
				$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			$activityMeg = "Signature album ".$proj_name." for user ".$eventUser." is updated by ".$isUsername;
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" ,$isCounty_id,$isState_id,$isCity_id);

			$activityMeg1 = "Signature album ".$proj_name." is updated";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,'signature_album.php');
		}
		
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}


	public function copy_saveProjects(){

		$send = new sendMails(true);

		
		$coverImageFile = $_FILES['signatureAlbumCover'];
		$user_id=$_REQUEST['selectedProjUserId'];
		$proj_name=$_REQUEST['sigAlbmProjName'];
		$proj_id=$_REQUEST['selectedProjId'];

		$token=$_REQUEST['token'];
		$view_token = rand(1000, 9999);

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
			
// 			move_uploaded_file($fileTempName, $coverImgFilePath);
			
			
			
			 $imagePath1 = $fileTempName;

            $targetFilePath1 = $coverImgFilePath;
            
            $targetSizeMB = 2;
            
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
		
		
			
			
			
			
			

            // $this->imagickImage($coverImgFilePath,1024.0, 90 );
			
            
// 	print_r($originalWidth); die;
			$StaringDate = date("Y-m-d ");

			$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + 1 year"));

			$qry = "INSERT INTO `tbesignaturealbum_projects`(`user_id`, `project_name`, `project_folder_name`, `proj_folder_path`, `cover_img_path`, `token`,`expiry_date` ,`view_token`) VALUES ($user_id,'$proj_name','$projFolder','$projFolder_path','$coverImgFilePath','$token','$newExpDate','$view_token')";

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
			$html = str_replace("--view_token",$view_token,$html);

			$recentActivity = new Dashboard(true);
			$activityMeg = "Signature album ".$proj_name." is created for ".$eventUser;
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

			$activityMeg1 = "Signature album ".$proj_name." is created ";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,'signature_album.php');

		
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );

			//print_r($mailRes);

		}else{
			
			$sql = "SELECT * FROM `tbesignaturealbum_projects` WHERE id=".$proj_id;//"UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
			
			$result = $this->dbc->get_rows($sql);
			

			$coverImgDirectory = $result[0]['proj_folder_path'].'/coverImages';
			$oldFile = $result[0]['cover_img_path'];
			$project_name = $result[0]['project_name'];
			$coverImgFilePath = $result[0]['cover_img_path'];
			if($project_name != $proj_name){
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
				// move_uploaded_file($fileTempName, $coverImgFilePath);
				
				
				
				
				 $imagePath1 = $fileTempName;
    
                $targetFilePath1 = $coverImgFilePath;
                
                $targetSizeMB = 2;
                
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
				
				
				
				
				
				
				
				
				
				
				// $this->imagickImage($coverImgFilePath,1024.0, 90 );
			}
			$qry = "UPDATE `tbesignaturealbum_projects` SET `project_name`='$project_name',`cover_img_path`='$coverImgFilePath' WHERE id=$proj_id";
			// echo $qry; die;

			$result = $this->dbc->update_row($qry);

			$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
			$userList = $this->dbc->get_rows($sql1);
			$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];


			$recentActivity = new Dashboard(true);
			$activityMeg = "Signature album ".$proj_name." for ".$eventUser." is updated";
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );

			$activityMeg1 = "Signature album ".$proj_name." is updated";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,'signature_album.php');
		}
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}
	
    public function compressAndUploadImage($imagePath, $targetDirectory, $targetSizeMB = 2)
    {
        // Convert target size from MB to bytes
        $targetSizeBytes = $targetSizeMB * 1024 * 1024;
    
        // Load the image
        $image = imagecreatefromjpeg($imagePath);
    
        // Initialize quality and compression variables
        $quality = 90;
        $compressedImage = null;
    
        // Loop until the image size is less than the target size
        while (filesize($imagePath) > $targetSizeBytes) {
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
                unlink($imagePath); // Delete the original image
                rename($tempImagePath, $imagePath); // Replace with the compressed image
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
    
        // Now, you can upload the compressed image to your target directory
        $targetFilePath = $targetDirectory . '/' . basename($imagePath);
        
        if (move_uploaded_file($imagePath, $targetFilePath)) {
            echo "Compressed image uploaded successfully to $targetFilePath";
        } else {
            echo "Error uploading the compressed image.";
        }
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
        WHERE project_id = tbesignaturealbum_projects.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
        WHERE project_id = tbesignaturealbum_projects.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
        WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
        WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0 )) AS imageCount FROM `tbesignaturealbum_projects` WHERE `user_id`=$userId AND `deleted`=0";
		$result = $this->dbc->get_rows($qry);

// 		print_r($result); die;

		foreach($result as $rs){
			$sid = $rs['id'];
			$planExpDate =  $rs['expiry_date'] ;

			$is_planAvl = 1;
		
			$row=array("id"=>$rs['id'],"user_id"=>$rs['user_id'],"project_name"=>$rs['project_name'],"project_folder_name"=>$rs['project_folder_name'],"proj_folder_path"=>$rs['proj_folder_path'],"cover_img_path"=>$rs['cover_img_path'],"deleted"=>$rs['deleted'],"crated_in"=>$rs['crated_in'],"commentCount"=>$rs['commentCount'],"viewCounts"=>$rs['viewCounts'],"shareCounts"=>$rs['shareCounts'],"likeCounts"=>$rs['likeCounts'],"is_planAvl"=>$is_planAvl,"planExpDate"=>$planExpDate, "imageCount"=>$rs['imageCount'], "eventsCount"=>$rs['eventsCount'], "view_token"=>$rs['view_token']);
			array_push($Output,$row);

		}

		if($result != "")self::sendResponse("1", $Output);
        else self::sendResponse("2", "Not found images");
	}
	
	public function getSignatureAlbumsProjectsForUser(){
		// echo "I am here !---";
		$Output = array();
		$userId=$_REQUEST["userId"];
		$main_user_id=$_REQUEST["main_user_id"];

//(SELECT COUNT(*) FROM tbesignaturealbum_data WHERE project_folder_id = 28) AS viewCounts
        // $qry1 = "SELECT id FROM `tbesignaturealbum_projects` WHERE `user_id`=$userId AND `deleted`=0";
        // $result = $this->dbc->get_rows($qry1);
        // print_r($result->data);
        
        
		$qry = "SELECT * , (SELECT COUNT(*) FROM tbeproject_comments
        WHERE project_id = tbesignaturealbum_projects.id) AS commentCount , (SELECT COUNT(*) FROM tbeproject_views
        WHERE project_id = tbesignaturealbum_projects.id) AS viewCounts , (SELECT COUNT(*) FROM tbeproject_shares
        WHERE project_id = tbesignaturealbum_projects.id) AS shareCounts,(SELECT COUNT(*) FROM signature_album_like
        WHERE project_id = tbesignaturealbum_projects.id AND status=1 AND active=0 ) AS likeCounts, (SELECT COUNT(*) FROM tbesignaturealbum_data 
        WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0) AS eventsCount, (SELECT COUNT(*) FROM `tbesignalbm_folderfiles` 
        WHERE album_id IN (SELECT id FROM tbesignaturealbum_data WHERE project_folder_id = tbesignaturealbum_projects.id AND deleted=0 )) AS imageCount FROM `tbesignaturealbum_projects` WHERE ( `user_id`='$userId' or `user_id` = '$main_user_id' ) AND `deleted`=0";
		$result = $this->dbc->get_rows($qry);

// 		print_r($result); die;

		foreach($result as $rs){
			$sid = $rs['id'];
			$planExpDate =  $rs['expiry_date'] ;

			$is_planAvl = 1;
		
			$row=array("id"=>$rs['id'],"user_id"=>$rs['user_id'],"project_name"=>$rs['project_name'],"project_folder_name"=>$rs['project_folder_name'],"proj_folder_path"=>$rs['proj_folder_path'],"cover_img_path"=>$rs['cover_img_path'],"deleted"=>$rs['deleted'],"crated_in"=>$rs['crated_in'],"commentCount"=>$rs['commentCount'],"viewCounts"=>$rs['viewCounts'],"shareCounts"=>$rs['shareCounts'],"likeCounts"=>$rs['likeCounts'],"is_planAvl"=>$is_planAvl,"planExpDate"=>$planExpDate, "imageCount"=>$rs['imageCount'], "eventsCount"=>$rs['eventsCount']);
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

// 		$signatureAlbumEventFiles = $_FILES['signatureAlbumEventFiles']['name'];
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
			
// 		mkdir($eventDirectory, 0777);

		$coverImgDirectory = $eventDirectory.'/coverImages';
		$coverImgDirectoryImagePath = $coverImgDirectory.'/'.$coverImage['name'][0];
// 		mkdir($coverImgDirectory, 0777);
		

		
// 		move_uploaded_file($coverImage['tmp_name'][0], $coverImgDirectoryImagePath);
		
		
		// Usage example:
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
        
           
            // move_uploaded_file($imagePath1, $targetFilePath1);
            
             try {
                // Upload the file to S3
                    $out = $this->s3Client->putObject([
                        'Bucket' => $this->bucketName,
                        'Key'    => $targetFilePath1,
                        'SourceFile' => $imagePath1,
                    ]);
                    
                    $coverImgDirectoryImagePath = $out['ObjectURL'];
                    
                
                   
                } catch (AwsException $e) {
                    // Handle errors
                    // echo 'Error uploading image: ' . $e->getMessage();
                    self::sendResponse("2", $e->getMessage());
                    die;
                }
		
		
		
		
		
		
		
		
		
// 		$this->imagickImage($coverImgDirectoryImagePath,1024.0, 90 );

		$eventThumpDirectory = $eventDirectory.'/'.'thumbnails';
		mkdir($eventThumpDirectory, 0777);

		$evntQry = "INSERT INTO `tbesignaturealbum_data`(`user_id`, `project_folder_id`, `folder_name`, `file_folder`, `cover_image_path`) VALUES ('$user_id','$projId','$sigAlbmEventName','$eventDirectory','$coverImgDirectoryImagePath')";

		$userFolderInsertedId = $this->dbc->insert_row($evntQry);
		
		$result = $userFolderInsertedId;
		
		
		
		// print_r($userFolderName);die;
		// $arquivo = array();
		// $file_ary = array();
		// $file_count = count($signatureAlbumEventFiles);
		// $file_keys = array_keys($signatureAlbumEventFiles);
		
		
// 		$arquivo = array();
// 		$file_ary = array();

// 		$countfiles = count($_FILES['signatureAlbumEventFiles']['name']);

// 		// Looping all files
// 		for($i=0;$i<$countfiles;$i++){

// 			$file_ary = array();
// 			$chkImgName = $_FILES['signatureAlbumEventFiles']['name'][$i];
// 			$file_ary ["name"] =$_FILES['signatureAlbumEventFiles']['name'][$i];
// 			$file_ary ["type"] =$_FILES['signatureAlbumEventFiles']['type'][$i];
// 			$file_ary ["tmp_name"] =$_FILES['signatureAlbumEventFiles']['tmp_name'][$i];
// 			$file_ary ["size"] =$_FILES['signatureAlbumEventFiles']['size'][$i]; 
// 			$file_ary ["error"] = $_FILES['signatureAlbumEventFiles']["error"][$i];
// 			array_push($arquivo,$file_ary);
		
// 		}
// 		$result = "";

// 		foreach($arquivo as $files)
// 		{
// 			// print_r($files["name"]);
// 			$filename = $files["name"];
// 			$filesize = $files["size"];
			
// 			$targetFilePath = $eventDirectory."/".$filename;
// 			$thumbnailsFilePath = $eventThumpDirectory.'/'. $filename;
			
			
// 			// Usage example:
//             $imagePath = $files['tmp_name'];

//             $targetSizeMB = 2;
            
//             // Convert target size from MB to bytes
//             $targetSizeBytes = $targetSizeMB * 1024 * 1024;
        
//             // Load the image
//             $image = imagecreatefromjpeg($imagePath);
        
//             // Initialize quality and compression variables
//             $quality = 90;
//             $compressedImage = null;
        
//             // Loop until the image size is less than the target size
//             while (filesize($imagePath) > $targetSizeBytes) {
//                 // Create a temporary image with reduced quality
//                 ob_start();
//                 imagejpeg($image, null, $quality);
//                 $compressedImageData = ob_get_clean();
        
//                 // Save the compressed image data to a temporary file
//                 $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
//                 file_put_contents($tempImagePath, $compressedImageData);
        
//                 // Check the size of the temporary compressed image
//                 $tempFileSize = filesize($tempImagePath);
        
//                 if ($tempFileSize <= $targetSizeBytes) {
//                     // The temporary image is within the target size
//                     $compressedImage = imagecreatefromjpeg($tempImagePath);
//                     unlink($imagePath); // Delete the original image
//                     rename($tempImagePath, $imagePath); // Replace with the compressed image
//                     break;
//                 }
        
//                 // Reduce the quality and continue the loop
//                 $quality -= 10;
        
//                 // If quality becomes too low, break the loop to prevent infinite looping
//                 if ($quality < 10) {
//                     break;
//                 }
//             }
        
//             // Clean up resources
//             imagedestroy($compressedImage);
        
           
//             move_uploaded_file($imagePath, $targetFilePath);
            
            
		
// // 			move_uploaded_file($files['tmp_name'], $targetFilePath);
			
// // 			$this->compressImage($targetFilePath, 2);
			
			
// 			copy($targetFilePath, $thumbnailsFilePath);
// 			$this->imagickImage($thumbnailsFilePath,1024, 60 );
//             // $this->compressImage1($thumbnailsFilePath, 1);
			
			
			
			
			
// // 			$this->imagickImage($targetFilePath,1024.0, 90 );
			
// 			// $albmid = $files["name"];
// 			$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`,`file_path`,`thumb_image_path`) VALUES ('$filename','$filesize','$userFolderInsertedId','$targetFilePath','$thumbnailsFilePath')";
			
// 			$result = $this->dbc->insert_row($qry1);
// 		}
		
		
		
		
		

	
// 		$countfiles = count($signatureAlbumEventFiles);

		
// 		for($i=0;$i<$countfiles;$i++){
			
// 			$filename = $_FILES['signatureAlbumEventFiles']['name'][$i];
// 			$filesize = $_FILES['signatureAlbumEventFiles']['size'][$i];
// 			$fileType = $_FILES['signatureAlbumEventFiles']['type'][$i];
// 			$fileTempName = $_FILES['signatureAlbumEventFiles']['tmp_name'][$i];
			
// 			if($fileType == "application/zip"){

// 				$targetFilePath = $eventDirectory."/".$filename;
				
// 				move_uploaded_file($fileTempName, $targetFilePath);
				
// 				$zip = new ZipArchive;
// 				$res = $zip->open($targetFilePath);
// 				if ($res === TRUE) {
// 					$zip->extractTo($eventDirectory);
// 					$zip->close();
// 					unlink($targetFilePath);
// 				} else {
// 					echo 'Unable to extract zip !';
// 				}	
// 			}else{
				
// 				// 	$str_to_arry = explode('.',$filename);
// 				// 	$ext   = end($str_to_arry);
// 					$fileActName = $filename;
// 					$targetFilePath = $eventDirectory."/".$fileActName;
// 					move_uploaded_file($fileTempName, $targetFilePath);
// 				// 	$this->imagickImage($targetFilePath,1024.0, 90 );
// 			}
// 		}
		
		

// 		$handle = opendir($eventDirectory);
// 			if ($handle) {
// 				while (($entry = readdir($handle)) !== FALSE) {
// 					if($entry != '.' && $entry != '..'){
// 						$str_to_arry = explode('.',$entry);
// 						$extension   = end($str_to_arry);

// 						if($extension == 'jpg' || $extension == 'jpeg'){
						 
// 							$pth = $eventDirectory.'/'.$entry;
// 							$thmPth = $eventThumpDirectory."/";
// 							$filesize = filesize($pth);
// 							$thump_pth = $eventThumpDirectory.'/'.$entry;
// 				// 			die($pth."=======".$thump_pth);
// 							copy($pth, $thump_pth);
// 							$this->imagickImage($thump_pth,512.0, 90 );
// 				// 			$this->imagickImage($pth,1024.0, 90 );
// 							// print_r($eventThumpDirectory);
// 				// 			$this->cwUpload($pth, $entry, TRUE, $thmPth,'400');
// 							// imagejpeg($image, $destination_url, $quality);
// 							$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`, `file_path`, `thumb_image_path`) VALUES ('$entry','$filesize','$userFolderInsertedId', '$pth', '$thump_pth')";
// 				// 			echo $qry1; die;
// 							$result = $this->dbc->insert_row($qry1);
// 						}
						
// 					}
					
// 				}
// 			}
// 		closedir($handle);
		// print_r($countfiles);die;

		$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
        $userList = $this->dbc->get_rows($sql1);
        $eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];

		$recentActivity = new Dashboard(true);
        $prjName = $projDetails[0]['project_name'];
        
        	$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
        
        
        
        $activityMeg = $isUsername." create new event ".$sigAlbmEventName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = "Create a new event ".$sigAlbmEventName." for signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,$encodedStringUrl);





		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not save event");

	}
	
	public function fetchAllUploadImage(){
		$selectedUplSigAlbmId=$_REQUEST["selectedUplSigAlbmId"];
		
	
		$sql = "SELECT `file_name` FROM tbesignalbm_folderfiles WHERE `album_id`='$selectedUplSigAlbmId' ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
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
	
	public function setSubuserImageAsSel(){
		
		$status=$_REQUEST["status"];
		$imageID=(int)$_REQUEST["imageID"];
		$subuser_sel_img_id=$_REQUEST["subuser_sel_img_id"];
		$userIdVal=$_REQUEST["userIdVal"];
		
		if($subuser_sel_img_id == ""){
		    
		    $mqry1 = "INSERT INTO `tbesignalbm_subuser_folderfiles`(`image_id`, `user_sel_img`,`user_id`) VALUES ('$imageID','$status','$userIdVal')";
			$result = $this->dbc->insert_row($mqry1);
		    
		}else{
		    $query = "UPDATE `tbesignalbm_subuser_folderfiles` SET `user_sel_img`='$status' WHERE `id`=$subuser_sel_img_id";
		    $result = $this->dbc->update_row($query);
		}
		
		
		if($result != "")self::sendResponse("1", "Image select successfully");
        else self::sendResponse("2", "Not select image");
	}
	
	public function setImageAsSel(){
		
		$status=$_REQUEST["status"];
		$imageID=(int)$_REQUEST["imageID"];
		
		$query = "UPDATE `tbesignalbm_folderfiles` SET `user_sel_img`='$status' WHERE `id`=$imageID";
		$result = $this->dbc->update_row($query);
		
		if($result != "")self::sendResponse("1", "Image select successfully");
        else self::sendResponse("2", "Not select image");
	}
	
	public function checkAndUpdateImgSel(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$sql = "SELECT COUNT(a.user_sel_img) as totalSelImg ,b.imageSel,b.folder_name,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE b.id=".$albumId." AND a.user_sel_img =1 ";
		
		$result = $this->dbc->get_rows($sql);
		
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not select image");
	}
	
	
	public function checkAndUpdateSunuserImgSel(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$userIdVal=(int)$_REQUEST["userIdVal"];
		$sel_main_userIdVal=(int)$_REQUEST["sel_main_userIdVal"];
		
		$sql = "SELECT COUNT(d.user_sel_img) as totalSelImg,b.imageSel,b.folder_name,c.completeImgSel FROM tbesignalbm_subuser_folderfiles d left join tbesignalbm_folderfiles a on a.id=d.image_id left join tbesignaturealbum_data b on a.album_id = b.id left join tbesignaturealbum_subuser_data c on c.album_id = b.id  WHERE d.user_id ='$userIdVal' AND b.id=".$albumId." AND d.user_sel_img =1 AND c.user_id ='$userIdVal' ";
		
// 		$sql = "SELECT COUNT(a.user_sel_img) as totalSelImg ,b.imageSel,b.folder_name,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE b.id=".$albumId." AND a.user_sel_img =1 ";

		$result = $this->dbc->get_rows($sql);
		
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not select image");
	}
	
	
	public function checkInitAndUpdateSunuserImgSel(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$userIdVal=(int)$_REQUEST["userIdVal"];
		$sel_main_userIdVal=(int)$_REQUEST["sel_main_userIdVal"];
		
		$sql = "SELECT 0 as totalSelImg,b.imageSel,b.folder_name,c.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id left join tbesignaturealbum_subuser_data c on c.album_id = b.id  WHERE b.id=".$albumId." AND c.user_id ='$userIdVal' ";
		
// 		$sql = "SELECT COUNT(a.user_sel_img) as totalSelImg ,b.imageSel,b.folder_name,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE b.id=".$albumId." AND a.user_sel_img =1 ";

		$result = $this->dbc->get_rows($sql);
		
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not select image");
	}
	
	
	public function completeSelection(){
		
		$albumId=(int)$_REQUEST["albumId"];
			
		$query = "UPDATE `tbesignaturealbum_data` SET `completeImgSel`=1 WHERE `id`=$albumId";
		$result = $this->dbc->update_row($query);
		
		
		 $sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name ,b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$albumId ";
        		$AlbumList = $this->dbc->get_rows($sql1);
        		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
        		$prjName = $AlbumList[0]['project_name'];
        		$fldName = $AlbumList[0]['folder_name'];
        		$user_id = $AlbumList[0]['user_id'];
        		$projId = $AlbumList[0]['projId'];
        		
        		$user_state_val = $_COOKIE['user_state_val'];
                $user_county_val = $_COOKIE['user_county_val'];
        
        
        		$recentActivity = new Dashboard(true);
                $activityMeg = "Complete Image selecting for event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" ,$user_county_val,$user_state_val,"",1);
		
		
		
		
		
		
		
		
		if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	}
	
	
	public function completeSelectionNew(){
		
		$albumId=(int)$_REQUEST["albumId"];
			
		$query = "UPDATE `tbesignaturealbum_data` SET `completeImgSel`=5 WHERE `id`=$albumId";
		$result = $this->dbc->update_row($query);
		
		
		if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	}
	
	public function completeSubuserSelectionNew(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$userIdVal=(int)$_REQUEST["userIdVal"];
			
		$query = "UPDATE `tbesignaturealbum_subuser_data` SET `completeImgSel`=5 WHERE `album_id`='$albumId' AND `user_id`='$userIdVal' ";
		$result = $this->dbc->update_row($query);
		
		
		 $sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name ,b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$albumId ";
        		$AlbumList = $this->dbc->get_rows($sql1);
        		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
        		$prjName = $AlbumList[0]['project_name'];
        		$fldName = $AlbumList[0]['folder_name'];
        		$user_id = $AlbumList[0]['user_id'];
        		$projId = $AlbumList[0]['projId'];
        		
        		$sdsds = get_session();
        		$logedUser = $sdsds['firstname'].' '.$sdsds['lastname'];
        
        
        		$recentActivity = new Dashboard(true);
                $activityMeg = "Image selecting for event ".$fldName." for signature album ".$prjName." and user ".$eventUser." is completed by sub user ".$logedUser;
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
		
		
		
		
		
		
		
		
		if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	}
	
	public function completeSubuserSelection(){
		
		$albumId=(int)$_REQUEST["albumId"];
		$userIdVal=(int)$_REQUEST["userIdVal"];
			
		$query = "UPDATE `tbesignaturealbum_subuser_data` SET `completeImgSel`=1 WHERE `album_id`='$albumId' AND `user_id`='$userIdVal' ";
		$result = $this->dbc->update_row($query);
		
		
		 $sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name ,b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$albumId ";
        		$AlbumList = $this->dbc->get_rows($sql1);
        		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
        		$prjName = $AlbumList[0]['project_name'];
        		$fldName = $AlbumList[0]['folder_name'];
        		$user_id = $AlbumList[0]['user_id'];
        		$projId = $AlbumList[0]['projId'];
        		
        		$user_state_val = $_COOKIE['user_state_val'];
                $user_county_val = $_COOKIE['user_county_val'];
                
                $sdsds = get_session();
        		$logedUser = $sdsds['firstname'].' '.$sdsds['lastname'];
        
        
        		$recentActivity = new Dashboard(true);
                $activityMeg = "Image selecting for event ".$fldName." for signature album ".$prjName." and user ".$eventUser." is completed by sub user ".$logedUser;
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create", $user_county_val,$user_state_val,"",1);
		
		
		
		
		
		
		
		
		if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	}
	
	
	public function setSignatureAlbumUsingPagenation(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=(int)$_REQUEST["albumId"];
		$isHide=$_REQUEST["isHide"];
		
		$limit=$_REQUEST["limit"];
		$offset=$_REQUEST["offset"];
		
	
		if($isHide == 1){
			$sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." ORDER BY a.file_name asc LIMIT $limit OFFSET $offset ";
		}else{
			$sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." AND a.hide =0 ORDER BY a.file_name asc LIMIT $limit OFFSET $offset ";
		}
        
		$result = $this->dbc->get_rows($sql);
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}
	
	public function setSignatureAlbumSubUserUsingPagenation(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=(int)$_REQUEST["albumId"];
		$isHide=$_REQUEST["isHide"];
		
		$limit=$_REQUEST["limit"];
		$offset=$_REQUEST["offset"];
		
		$sel_main_userIdVal=$_REQUEST["sel_main_userIdVal"];
		$userIdVal=$_REQUEST["userIdVal"];
		
		
		
		$msql = "SELECT * FROM tbesignaturealbum_subuser_data WHERE user_id='$userIdVal' AND album_id='$albumId' ";
		$mresult = $this->dbc->get_rows($msql);
		if(!isset($mresult[0])){
		    $mqry1 = "INSERT INTO `tbesignaturealbum_subuser_data`(`user_id`, `album_id`) VALUES ('$userIdVal','$albumId')";
			$this->dbc->insert_row($mqry1);
		}
		
	
	
		if($isHide == 1){
			$sql = "SELECT a.*,b.imageSel,c.completeImgSel,d.user_sel_img as subuser_sel_img,d.id as subuser_sel_img_id FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id left join tbesignaturealbum_subuser_data c on a.album_id = c.album_id left join tbesignalbm_subuser_folderfiles d on a.id=d.image_id WHERE a.album_id=".$albumId." AND c.user_id ='$userIdVal' AND d.user_id='$userIdVal' ORDER BY a.file_name asc LIMIT $limit OFFSET $offset ";
		}else{
			$sql = "SELECT a.*,b.imageSel,c.completeImgSel,d.user_sel_img as subuser_sel_img,d.id as subuser_sel_img_id FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id left join tbesignaturealbum_subuser_data c on a.album_id = c.album_id left join tbesignalbm_subuser_folderfiles d on a.id=d.image_id WHERE a.album_id=".$albumId." AND a.hide =0 ORDER BY a.file_name asc LIMIT $limit OFFSET $offset ";
		}
        
		$result = $this->dbc->get_rows($sql);
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}
	
	
	public function getFilesFromFolderCount(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=(int)$_REQUEST["albumId"];
		$isHide=$_REQUEST["isHide"];

		if($isHide == 1){
			$sql = "SELECT COUNT(*) AS total_count FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." ";
		}else{
			$sql = "SELECT COUNT(*) AS total_count FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." AND a.hide =0 ";
		}
        

		$result = $this->dbc->get_rows($sql);
	
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not found images");
	}


	public function getFilesFromFolder(){
		
		$folderName=$_REQUEST["folderName"];
		$albumId=(int)$_REQUEST["albumId"];
		$isHide=$_REQUEST["isHide"];
		// $start=$_REQUEST["start"];
        
		if($isHide == 1){
// 			$sql = "SELECT * FROM tbesignalbm_folderfiles WHERE album_id=".$albumId." LIMIT 20 OFFSET ".$start;
			$sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." ORDER BY a.file_name asc";
		}else{
			$sql = "SELECT a.*,b.imageSel,b.completeImgSel FROM tbesignalbm_folderfiles a left join tbesignaturealbum_data b on a.album_id = b.id WHERE a.album_id=".$albumId." AND a.hide =0 ORDER BY a.file_name asc";
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

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name , b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$id ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$fldName = $AlbumList[0]['folder_name'];
		$user_id = $AlbumList[0]['user_id'];
		$projId = $AlbumList[0]['projId'];

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
        $activityMeg = $isUsername." deleted event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = "Delete event ".$fldName." for signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,$encodedStringUrl);

		if($result != "")self::sendResponse("1", "Album deleted successfully");
        else self::sendResponse("2", "Not deleted album");
	}

	public function saveSignatureAlbumExtraFiles(){

		
		$AlbumId = $_REQUEST['selectedUplSigAlbmId'];
		$targetDir = $_REQUEST['selectedUplSigfile_folder'];
		$uploadDidectory = SIGNATUREALBUM_UPLOAD_PATH;
		
	

		// print_r(sizeof($zipFile));
		$arquivo = array();
		$file_ary = array();
	
		$numberofRecInsert = 0;

		$countfiles = count($_FILES['uploadSignatureAlbumFiles']['name']);
		

		// Looping all files
		for($i=0;$i<$countfiles;$i++){

			$file_ary = array();
			$chkImgName = $_FILES['uploadSignatureAlbumFiles']['name'][$i];
			
		
// 			$chkSqlImg = "SELECT id FROM tbesignalbm_folderfiles WHERE `file_name`='$chkImgName' AND `album_id`='$AlbumId' ";
// 			$chkSqlImgList = $this->dbc->get_rows($chkSqlImg);
		
// 			if(sizeof($chkSqlImgList) == 0){
			    	
    			$file_ary ["name"] =$_FILES['uploadSignatureAlbumFiles']['name'][$i];
    			$file_ary ["type"] =$_FILES['uploadSignatureAlbumFiles']['type'][$i];
    			$file_ary ["tmp_name"] =$_FILES['uploadSignatureAlbumFiles']['tmp_name'][$i];
    			$file_ary ["size"] =$_FILES['uploadSignatureAlbumFiles']['size'][$i]; 
    			$file_ary ["error"] = $_FILES['uploadSignatureAlbumFiles']["error"][$i];
    			array_push($arquivo,$file_ary);
    			$numberofRecInsert++;
			
// 			}
			
		
		
		}
		$result = "";
		$instCut = 0;
		
		$t=time();
		$kk = 0;
	
		foreach($arquivo as $files)
		{
			// print_r($files["name"]);
			$filename = $files["name"];
			$filesize = $files["size"];
			
			$filename = $t."_".$kk;
			$kk++;

			$targetDir = $targetDir."/";
			$targetFilePath = $targetDir . $filename;
			$thumbnailsFilePath = $targetDir ."thumbnails/". $filename;
			
		
			
			// Usage example:
            $imagePath = $files['tmp_name'];
            $targetDirectory = $targetDir;
            
            move_uploaded_file($imagePath, $targetFilePath);
            
            // $targetSizeMB = 2;
            
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
        
           
            // move_uploaded_file($imagePath, $targetFilePath);
           
			
			copy($targetFilePath, $thumbnailsFilePath);
			
			$this->imagickImage($thumbnailsFilePath,3072, 60 );
// 			$this->compressImage1($thumbnailsFilePath, 1);
			
			
			

		
// 			move_uploaded_file($files['tmp_name'], $targetFilePath);
// 			copy($targetFilePath, $thumbnailsFilePath);
// 			$this->imagickImage($thumbnailsFilePath,512.0, 90 );
// 			$this->imagickImage($targetFilePath,1024.0, 90 );
			
			// $albmid = $files["name"];
			$qry1 = "INSERT INTO `tbesignalbm_folderfiles`(`file_name`, `file_size`, `album_id`,`file_path`,`thumb_image_path`) VALUES ('$filename','$filesize','$AlbumId','$targetFilePath','$thumbnailsFilePath')";
			$result = $this->dbc->insert_row($qry1);
			$instCut++;
		}
		
	
		if($instCut >0 ){
		    
		    if($instCut == $numberofRecInsert){
    		    $sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.folder_name ,b.id as user_id , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbesignaturealbum_data c on a.id = c.project_folder_id WHERE c.id=$AlbumId ";
        		$AlbumList = $this->dbc->get_rows($sql1);
        		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
        		$prjName = $AlbumList[0]['project_name'];
        		$fldName = $AlbumList[0]['folder_name'];
        		$user_id = $AlbumList[0]['user_id'];
        		$projId = $AlbumList[0]['projId'];
        
        
        		$recentActivity = new Dashboard(true);
                $activityMeg = "Upload Image to event ".$fldName." for signature album ".$prjName." and user ".$eventUser;
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
        
        		$timestamp = time(); // Get the current timestamp
        		$encodedString = base64_encode($timestamp . "_".$projId);
        		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;
        
        		$activityMeg1 = "Upload Image to event ".$fldName." for signature album ".$prjName;
        		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,$encodedStringUrl);
		    }
		    
		}else{
		    if($numberofRecInsert == 0){
		        $result = "All images are duplicate";
		    }

		}



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted files");
	}
	
	public function compressImage1($imagePath, $targetSizeMB = 1)
    {
        // Convert target size from MB to bytes
        $targetSizeBytes = 512.0;
    
        // Load the image
        $image = imagecreatefromjpeg($imagePath);
    
        // Initialize quality and compression variables
        $quality = 90;
        $compressedImage = null;
    
        // Loop until the image size is less than the target size
        while (filesize($imagePath) > $targetSizeBytes) {
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
                imagedestroy($image);
                unlink($tempImagePath); // Delete the temporary file
                break;
            }
    
            // Reduce the quality and continue the loop
            $quality -= 10;
    
            // If quality becomes too low, break the loop to prevent infinite looping
            if ($quality < 10) {
                break;
            }
        }
    
        // Save the compressed image to the original file path
        imagejpeg($compressedImage, $imagePath, $quality);
    
        // Clean up resources
        imagedestroy($compressedImage);
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

			$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projectId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];
			$projId = $AlbumList[0]['projId'];

			$recentActivity = new Dashboard(true);
			$activityMeg = $eventUser." hide photo from  signature album ".$prjName ." using token";
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

			$timestamp = time(); // Get the current timestamp
			$encodedString = base64_encode($timestamp . "_".$projId);
			$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

			$activityMeg1 = $eventUser." hide photo from  signature album ".$prjName ." using token";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,$encodedStringUrl);

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

			$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projectId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];

			$recentActivity = new Dashboard(true);
			$activityMeg = $eventUser." show photo from  signature album ".$prjName ." using token";
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

			$timestamp = time(); // Get the current timestamp
			$encodedString = base64_encode($timestamp . "_".$projectId);
			$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

			$activityMeg1 = $eventUser." show photo from  signature album ".$prjName ." using token";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,$encodedStringUrl);

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

        $sql1 = "SELECT firstname, lastname, email  FROM `tblcontacts` WHERE id=$user_id ";
        $userList = $this->dbc->get_rows($sql1);
        $eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
        $eventUserEmail = $userList[0]['email'];


        $html = $mailTemplate[0]['mail_body'];

      
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--project_name",$evtData[0]['project_name'],$html);
		$html = str_replace("--token",$evtData[0]['token'],$html);
		$html = str_replace("--expiry_dt",$evtData[0]['expiry_date'],$html);

		$recentActivity = new Dashboard(true);
		$prjName = $evtData[0]['project_name'];
		
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
		$activityMeg = "Signature album ".$prjName." for user ".$eventUser." expiry date is extended by ".$isUsername;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "extend",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Your signature album ".$prjName." expiry date is extended";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "extend" ,$user_id,'signature_album.php');

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

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
        $activityMeg = "Signature album ".$prjName." for user ".$eventUser." is shared";
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = "Your signature album ".$prjName." is shared";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "share" ,$userID,'signature_album.php');
		
	
		$vs = "INSERT INTO `tbeproject_shares`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	
	public function likeSignaturealbum(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM signature_album_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `signature_album_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `signature_album_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `signature_album_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId_id_like ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
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
        $activityMeg = "Signature album ".$prjName." for user ".$eventUser." is ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = $guestName." ".$sts." your signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "like" ,$userID,'signature_album.php');
		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM signature_album_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}
	
	
	public function getPriceDetails(){
	    
	    $selYear=$_REQUEST["selYear"];
	    $photo_count=$_REQUEST["photo_count"];
	    
          $sql = "SELECT * FROM `tblalbumsubscription` WHERE `period`='$selYear' AND photo_count='$photo_count' AND `signature`=1 AND `delete`=0 ";
      
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	public function getFileNameForFR(){
	    
	    $projectID=$_REQUEST["projectID"];

          $sql = "SELECT proj_folder_path FROM `tbesignaturealbum_projects` WHERE `id`='$projectID' ";
      
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	public function setSelectImageAsRead(){
		
	
	    $query = "UPDATE `tblrecent_activity` SET `is_read`=1 ";
	    $result = $this->dbc->update_row($query);
	
		
		if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	}
	
	
	

	


}

?>