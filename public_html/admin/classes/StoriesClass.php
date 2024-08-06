<?php
require_once('DashboardClass.php');

class Stories {
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
    public function deleteStorysIMgfile(){
		
        $id=(int)$_REQUEST['id'];

        $query = "UPDATE `story_imgfiles` SET `hide`=1 WHERE `id`=$id";
        $result = $this->dbc->update_row($query);

        $storiesId = $_REQUEST['id'];
        $sql1 = "SELECT a.* FROM `stories` a left join story_imgfiles b on a.id = b.story_id WHERE b.id=$id ";
        $storiesList = $this->dbc->get_rows($sql1);
        $tittle = $storiesList[0]['main_tittle'];
        $recentActivity = new Dashboard(true);
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
        
        
        $activityMeg = $isUsername." delete image from storie ".$tittle;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

        if(isset($result))self::sendResponse("1", "Record deleted Successfully");
        else self::sendResponse("2", "Failed In deleting Data");

		
	}

    public function getStorysIMgfiles(){
		$sql = "SELECT * FROM story_imgfiles WHERE story_id=".(int)$_REQUEST['id']." AND `hide`=0 ";
		$result = $this->dbc->get_rows($sql);
    	$data=array("SRV"=>$result);
		self::sendResponse("1", $data);
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

    public function addImages(){

        $StoryImgFiles = $_FILES['StoryImgFiles']['name'];
        $uploadDidectory = "storyImages/";
        $main_id = $_REQUEST['id'];

        $t=time();
		$event_folder_name = $main_id."_".$t;
		$eventDirectory = $uploadDidectory.$event_folder_name;
        mkdir($eventDirectory, 0777);

        $countfiles = count($StoryImgFiles);

        for($i=0;$i<$countfiles;$i++){
			
			$filename = $_FILES['StoryImgFiles']['name'][$i];
			$filesize = $_FILES['StoryImgFiles']['size'][$i];
			$fileType = $_FILES['StoryImgFiles']['type'][$i];
			$fileTempName = $_FILES['StoryImgFiles']['tmp_name'][$i];
			
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

                    $this->imagickImage($targetFilePath,1024.0, 80 );
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
							$filesize = filesize($pth);
							
							$qry1 = "INSERT INTO `story_imgfiles`(`file_name`, `file_size`, `story_id`, `file_path`) VALUES ('$entry','$filesize','$main_id', '$pth')";

							$result = $this->dbc->insert_row($qry1);
						}
						
					}
					
				}
			}
		closedir($handle);
		// print_r($countfiles);die;

        $storiesId = $_REQUEST['id'];
        $sql1 = "SELECT * FROM `stories` WHERE id=$storiesId ";
        $storiesList = $this->dbc->get_rows($sql1);
        $tittle = $storiesList[0]['main_tittle'];
        $recentActivity = new Dashboard(true);
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
        
        
        $activityMeg = $isUsername." upload new image(s) to storie ".$tittle;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");


		
	}

	public function getNumberOfPages(){
       $data =array();
       $sql = "SELECT Count(id) as noOfStories FROM stories WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 

       $result = $this->dbc->get_rows($sql);
       
       $data["noOfStories"]=$result[0]['noOfStories'];
 
       self::sendResponse("1",$data);
	}

    public function getStoriesRecs(){

        $offset = $_REQUEST['offset'];
        $limit = $_REQUEST['limit'];
        
        $data =array();
        $sql = "SELECT id,main_tittle,image_story,`description`,event_date,event_place,small_description,video, (SELECT COUNT(*) FROM stories_views
        WHERE stories_id = stories.id) AS viewCounts , (SELECT COUNT(*) FROM stories_shares
        WHERE stories_id = stories.id) AS shareCounts FROM stories WHERE `deleted` = 0 AND `active` = 1 order by id desc  limit $offset,$limit "; 
 
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }

    public function getStoriesRec(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        $sql = "SELECT *, (SELECT COUNT(*) FROM stories_views
        WHERE stories_id = stories.id) AS viewCounts FROM stories WHERE id = $id "; 
 
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        // $vs = "INSERT INTO `stories_views`(`stories_id`, `IP` ) VALUES ('$id','$ip')";
        $vs = "INSERT INTO `stories_views`(`stories_id`, `IP`)
        SELECT '$id', '$ip'
        WHERE NOT EXISTS (
            SELECT * FROM `stories_views`
            WHERE `stories_id` = '$id' AND `IP` = '$ip'
        )";
        $this->dbc->insert_row($vs);
  
        self::sendResponse("1",$data);
     }

     public function getNxtPrv(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        $sql = "SELECT id,main_tittle FROM stories WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 
        $result = $this->dbc->get_rows($sql);
        $prv ='';
        $nxt ='';
        $prvName ='';
        $nxtName ='';

        if( sizeof($result) == 1){
            $prv =$result[0]['id'];
            $nxt =$result[0]['id'];
            $prvName =$result[0]['main_tittle'];
            $nxtName =$result[0]['main_tittle'];
        }else if( sizeof($result) == 2){
            $prv =$result[0]['id'];
            $nxt =$result[1]['id'];
            $prvName =$result[0]['main_tittle'];
            $nxtName =$result[1]['main_tittle'];
        }else{
            for($i=0;$i<sizeof($result);$i++){
                if( $id == $result[$i]['id'] ){
                    if($i == 0){
                        $prv =$result[0]['id'];
                        $nxt =$result[1]['id'];
                        $prvName =$result[0]['main_tittle'];
                        $nxtName =$result[1]['main_tittle'];
                    }else if($i == (sizeof($result)-1) ){
                        $prv =$result[$i-1]['id'];
                        $nxt =$result[$i]['id'];
                        $prvName =$result[$i-1]['main_tittle'];
                        $nxtName =$result[$i]['main_tittle'];
                    }else{
                        $prv =$result[$i-1]['id'];
                        $nxt =$result[$i+1]['id'];
                        $prvName =$result[$i-1]['main_tittle'];
                        $nxtName =$result[$i+1]['main_tittle'];
                    }
                }
            }


        }
        
     

        
        $data["prv"]=$prv;
        $data["prvName"]=$prvName;
        $data["nxt"]=$nxt;
        $data["nxtName"]=$nxtName;
  
        self::sendResponse("1",$data);
     }


     public function getTrendingStories(){

        $id = (int)$_REQUEST['Id'];
        $data =array();
        $sql = "SELECT id,main_tittle,image_story,`description`,event_date,event_place,small_description,video FROM stories WHERE `deleted` = 0 AND `active` = 1 AND id != $id order by id desc  limit 0,6 "; 
 
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }


     public function addShareCount(){
		$Id=(int)$_REQUEST['Id'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}


		$vs = "INSERT INTO `stories_shares` (`stories_id`, `IP` ) VALUES ('$Id','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	 public function getAllStoriesRecs(){
	     
	        $selStart = $_REQUEST['selStart'];
        $selStop = $_REQUEST['selStop'];
        
          $user_state_vals = $_REQUEST['user_state_val'];
        $where = '';
        if($user_state_vals != "")$where = " and FIND_IN_SET($user_state_vals, state_id) ";

      
        $data =array();
        
         if($selStart == ""){
             $sql = "SELECT * FROM stories WHERE `deleted` = 0 AND `active` = 1 $where order by id desc"; 
        }else{
             $sql = "SELECT * FROM stories WHERE `deleted` = 0 AND `active` = 1 AND `created_date` BETWEEN '$selStart' AND '$selStop' $where order by id desc"; 
        }
        
       
        
      
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }
     
     public function addShare(){
		$projId=(int)$_REQUEST['projId'];
		$name=$_REQUEST['name'];
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

	
		$recentActivity = new Dashboard(true);
        $activityMeg = "Share stories ".$name;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "share");

		$vs = "INSERT INTO `stories_shares`(`stories_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	public function addViewCount(){
	    $projId=(int)$_REQUEST['projId'];
	     if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$vs = "INSERT INTO `stories_views`(`stories_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);

	}
	
	
	 public function likeStories(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM stories_album_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `stories_album_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `stories_album_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `stories_album_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT main_tittle FROM stories WHERE id=$projId_id_like ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$prjName = $AlbumList[0]['main_tittle'];

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
        $activityMeg = "Storie ".$prjName." ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM stories_album_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}






  
}

?>