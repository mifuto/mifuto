<?php
require_once('DashboardClass.php');
require_once('sendMailClass.php');


class WeddingFilms {
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
	
   
    public function saveFilm(){
        
        $data=array();
        $data["user_id"]=$_REQUEST['usersList'];
		$data["tittle"]=$_REQUEST['eventTitle'];
		$data["sub_tittle"]=$_REQUEST['eventDescription'];
		$data["video_type"]=$_REQUEST['vedioType'];
		
		
		$coverImage = $_FILES['EventCoverImgFile'];
		$uploadDidectory = 'weddingFilms';
		
		$t=time();
		
		$coverImgDirectoryImagePath = $uploadDidectory.'/'.$t.'_'.$coverImage['name'][0];
		
		$imagePath1 = $coverImage['tmp_name'][0];
	
		if($imagePath1 != ""){
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
    		
    		
    		$data["cover_image"]=$targetFilePath1;
    		
		}
		
		
            
            
        
            
		
		

		if($_REQUEST['vedioType']=='url'){
		    $data["orginal_url"]=$_REQUEST['eventURL'];
			$split = explode('watch?v=',$_REQUEST['eventURL']);
			$data['video_upload']='https://www.youtube.com/embed/'.$split[1];
			
		}else{
			if(isset($_FILES['eventUpload']['name']) && $_FILES['eventUpload']['name']!=''){
				$target_1 = 'weddingFilms/vid_'.time().$_FILES['eventUpload']['name'];
				$data['video_upload']=$target_1;
				move_uploaded_file($_FILES['eventUpload']['tmp_name'], $target_1);
			}
		}
		
		$user_id = $_REQUEST['usersList'];
		$tittletext = $_REQUEST['eventTitle'];
		$description = $_REQUEST['eventDescription'];
		
		$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
		$userList = $this->dbc->get_rows($sql1);
		$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];
		$eventUserEmail = $userList[0]['email'];
		

		if($_REQUEST['hiddenEventId']=='' ){
		  
			$recentActivity = new Dashboard(true);
			
		
		
			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=6 AND mail_template=19 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];
			
		

			$html = $mailTemplate[0]['mail_body'];

			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--tittle",$tittletext,$html);
			$html = str_replace("--sub_tittle",$description,$html);
			
				$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];

			$activityMeg = "New wedding film ".$tittletext." for user ".$eventUser." is created by ".$isUsername;
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

			$activityMeg1 = "Wedding film ".$tittletext." is created ";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "create" ,$user_id,'wedding_films.php');

		    $send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
			
			
			
			
			$result = $this->dbc->InsertUpdate($data, 'wedding_films');

		}else{

			$recentActivity = new Dashboard(true);
			
				$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
			$activityMeg = "Wedding film ".$tittletext." for user ".$eventUser." is updated by ".$isUsername;
        	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

			$activityMeg1 = "Wedding film ".$tittletext." is updated ";
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$user_id,'wedding_films.php');
			
			// echo 'ldibuflaisjud';
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'wedding_films', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getWeddingFilms(){
	    
		$sel_user=$_REQUEST["sel_user"];
		
		 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
		
		
		if($sel_user == ""){
		    
		     if($isAdmin){
                     
            			$sql = "SELECT ev.*, ct.firstname, ct.lastname ,(SELECT COUNT(*) FROM wedding_film_shares
                    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
                    WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
                    WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
                WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films as ev 
            			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id
            			WHERE ev.active=0 ORDER BY ev.id DESC";
        
               }else{
                   
                   
                     if($manage_type == 'County'){
                           // user type County
                           
                           
            			$sql = "SELECT ev.*, ct.firstname, ct.lastname ,(SELECT COUNT(*) FROM wedding_film_shares
                    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
                    WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
                    WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
                WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films as ev 
            			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
            			WHERE ev.active=0 and cct.country = '$county_id' ORDER BY ev.id DESC";
                           
                           
                       }else if($manage_type == 'State'){
                           // user type State
                           
                               
            			$sql = "SELECT ev.*, ct.firstname, ct.lastname ,(SELECT COUNT(*) FROM wedding_film_shares
                    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
                    WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
                    WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
                WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films as ev 
            			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
            			WHERE ev.active=0 and cct.state = '$state' ORDER BY ev.id DESC";
                          
                       }else {
                           // user type City
                           	$sql = "SELECT ev.*, ct.firstname, ct.lastname ,(SELECT COUNT(*) FROM wedding_film_shares
                    WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
                    WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
                    WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
                WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films as ev 
            			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id left join tblclients cct on cct.userid = ct.userid
            			WHERE ev.active=0 and cct.city = '$city' ORDER BY ev.id DESC";
                           
                       }
           
                   
                   
                   
                   
                   
               }
		    
		   
			
			
			
		}else{
			$sql = "SELECT ev.*, ct.firstname, ct.lastname ,(SELECT COUNT(*) FROM wedding_film_shares
        WHERE project_id = ev.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
        WHERE project_id = ev.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
        WHERE project_id = ev.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
    WHERE project_id = ev.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films as ev 
			LEFT JOIN tblcontacts as ct ON ct.id = ev.user_id
			WHERE ev.active=0 AND ev.user_id = $sel_user ORDER BY ev.id DESC";
		}

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	
	}
	
	public function getEditWeddingFilm(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT *,(SELECT COUNT(*) FROM wedding_film_shares
        WHERE project_id = wedding_films.id) AS shareCounts,(SELECT COUNT(*) FROM wedding_film_views
        WHERE project_id = wedding_films.id) AS viewsCounts,(SELECT COUNT(*) FROM films_comments
        WHERE project_id = wedding_films.id AND status = 1 AND deleted = 0 ) AS commentCounts,(SELECT COUNT(*) FROM wedding_film_like
    WHERE project_id = wedding_films.id AND status=1 AND active=0 ) AS likeCounts FROM wedding_films WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		
	public function deleteFilm(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `wedding_films` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
		
		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email,b.id as user_id FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$sel_id ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$user_id = $AlbumList[0]['user_id'];

		$recentActivity = new Dashboard(true);
		
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
        $activityMeg = $isUsername." deleted wedding film ".$prjName." for user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$activityMeg1 = "Delete wedding film ".$prjName ;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,'wedding_films.php');
      
        if($result != "")self::sendResponse("1", "Successfully to delete wedding film");
        else self::sendResponse("2", "Failed to delete wedding film");
	
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

		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email,b.id as user_id FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$user_id = $AlbumList[0]['user_id'];

		$recentActivity = new Dashboard(true);
        $activityMeg = "Share wedding film ".$prjName." for user ".$eventUser;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

		$activityMeg1 = "Your wedding film ".$prjName." is shared" ;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$user_id,'wedding_films.php');
		
	
		$vs = "INSERT INTO `wedding_film_shares`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
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

	
		$vs = "INSERT INTO `wedding_film_views`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	public function likeWeddingFilm(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM wedding_film_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `wedding_film_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `wedding_film_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `wedding_film_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , b.id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId_id_like ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
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
        $activityMeg = "Wedding film ".$prjName." for user ".$eventUser." is ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = $guestName." ".$sts." your wedding film ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "like" ,$userID,'wedding_films.php');
		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM wedding_film_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}



  
}

?>