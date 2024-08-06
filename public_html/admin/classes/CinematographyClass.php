<?php

class Cinematography {
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
        $activityMeg = "Share cinematography ".$name;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "share");

		$vs = "INSERT INTO `cinem_shares`(`project_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}

    




	public function addCinematography(){
		$data=array();
		$data["category"]=$_REQUEST['category'];
		$data["event_place"]=$_REQUEST['event_place'];
		$data["main_tittle"]=$_REQUEST['main_tittle'];

        $description = str_replace("'", '"', $_REQUEST['description']);
		$data["description"]=$description;
		$data["small_description"]=$_REQUEST['small_description'];
        $data["client"]=$_REQUEST['client'];
		$data["camera"]=$_REQUEST['camera'];
		
			$data["county_id"]=$_REQUEST['selCounty'];
		$data["state_id"]=$_REQUEST['multipleSel'];
// 		$data["city_id"]=$_REQUEST['selCity'];

        if($_REQUEST['FileVideoURL']==1){
            $split = explode('watch?v=',$_REQUEST['import_url']);
                $data['video']='https://www.youtube.com/embed/'.$split[1];
        }else{
            if(isset($_FILES['import_video']['name']) && $_FILES['import_video']['name']!=''){
                $target_1 = 'cinematographyImages/vid_'.time().$_FILES['import_video']['name'];
                $data['video']=$target_1;
                move_uploaded_file($_FILES['import_video']['tmp_name'], $target_1);
            }
        }
        
        	$isAdmin = $_SESSION['isAdmin'];
           $isCounty_id = $_SESSION['county_id'];
           $isState_id = $_SESSION['state_id'];
           $isCity_id = $_SESSION['city_id'];
           $isUsername = $_SESSION['Username'];

        if($_REQUEST['id']=='' ){
			$recentActivity = new Dashboard(true);
			$main_tittle = $_REQUEST['main_tittle'];
			$activityMeg = "New cinematography ".$main_tittle." is created by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		}else{
			$recentActivity = new Dashboard(true);
			$main_tittle = $_REQUEST['main_tittle'];
			$activityMeg = "Cinematography ".$main_tittle." is updated by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		}


		$this->dbc->InsertUpdate($data, 'tbl_cinematography');

		
	}
	
		public function getCinematography12(){
		    
		    	 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
        $state_id = $_SESSION['state_id'];
       
       	if($isAdmin){
		       $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_cinematography a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
		
		    
		}else{
		    
		      if($manage_type == 'County'){
               // user type County
               
               $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_cinematography a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and b.country_id='$county_id' ORDER BY a.id DESC";
		
	
               
           }else {
               // user type City
              $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_cinematography a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and FIND_IN_SET($state_id, a.state_id) ORDER BY a.id DESC";
		
	
               
               
           }
		    
		    
		    
		    
		    
		}
		    
		    
		    
		    
		    
	  

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}

    public function getCinematography(){
		$sql = "SELECT a.*,b.short_name as county_id,c.state as state_id,d.city as city_id FROM tbl_cinematography a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Cinematography"=>$result);
		self::sendResponse("1", $data);
	}

    public function getLastRec(){
		$sql = "SELECT id FROM tbl_cinematography WHERE deleted=0 ORDER BY id DESC";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Cinematography"=>$result);
		self::sendResponse("1", $data);
	}


    public function setCinematographyActiveInactive(){
		$data=array();
		$data["active"]=$_REQUEST['active'];

      	$data_id=array(); $data_id["id"]=(int)$_REQUEST['id'];
		$Update=$this->dbc->update_query($data, 'tbl_cinematography', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Update['AffectedRows']>0){

            $active = $_REQUEST['active'];
            if($active == 1) $sts = "active";
            else $sts = "in-active";
    
            $cneId = $_REQUEST['id'];
            $sql1 = "SELECT * FROM `tbl_cinematography` WHERE id=$cneId ";
            $cneList = $this->dbc->get_rows($sql1);
            $tittle = $cneList[0]['main_tittle'];
            $recentActivity = new Dashboard(true);
            
            $isAdmin = $_SESSION['isAdmin'];
           $isCounty_id = $_SESSION['county_id'];
           $isState_id = $_SESSION['state_id'];
           $isCity_id = $_SESSION['city_id'];
           $isUsername = $_SESSION['Username'];
            
            
            
            $activityMeg = $isUsername." set cinematography ".$tittle." to ".$sts;
            $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);



			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

    public function deleteCinematography(){
		$data=array();
		$data["deleted"]=1;
		$data["deleted_date"]=date('Y-m-d H:i:s');

      	$data_id=array(); $data_id["id"]=(int)$_REQUEST['id'];

		$Deleted=$this->dbc->update_query($data, 'tbl_cinematography', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Deleted['AffectedRows']>0){

            $dlt_id = $_REQUEST['id'];
            $sql1 = "SELECT * FROM `tbl_cinematography` WHERE id=$dlt_id ";
            $cneList = $this->dbc->get_rows($sql1);
            $tittle = $cneList[0]['main_tittle'];
            $recentActivity = new Dashboard(true);
            
              $isAdmin = $_SESSION['isAdmin'];
           $isCounty_id = $_SESSION['county_id'];
           $isState_id = $_SESSION['state_id'];
           $isCity_id = $_SESSION['city_id'];
           $isUsername = $_SESSION['Username'];
            
            
            $activityMeg = "Cinematography ".$tittle." is deleted by ".$isUsername;
            $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);


			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

    public function getCinematographyId(){
		$sql = "SELECT * FROM tbl_cinematography WHERE id=".(int)$_REQUEST['id'];
		$result = $this->dbc->get_rows($sql);
    	$data=array("CMA"=>$result[0]);
		self::sendResponse("1", $data);
	}


    public function getCinematographyRec(){
        $id = (int)$_REQUEST['id'];
        
        $user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
        
        
        $data =array();
        $sql = "SELECT *, (SELECT COUNT(*) FROM cinematography_views
        WHERE cinematography_id = tbl_cinematography.id) AS viewCounts, (SELECT COUNT(*) FROM cinem_shares
        WHERE project_id = tbl_cinematography.id) AS shareCounts,(SELECT COUNT(*) FROM cne_album_like WHERE project_id = tbl_cinematography.id AND status=1 AND active=0) as likeCount,(SELECT `status` FROM cne_album_like WHERE project_id=tbl_cinematography.id AND user_id='$user_id_like' AND user_type='$user_type_val' AND active=0) as like_status FROM tbl_cinematography WHERE id = $id "; 
 
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
        
      
         $vs = "INSERT INTO `cinematography_views` (`cinematography_id`, `IP` ) VALUES ('$id','$ip')";
		$this->dbc->insert_row($vs);
  
        self::sendResponse("1",$data);
     }

     public function getNxtPrv(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        $sql = "SELECT id,main_tittle FROM tbl_cinematography WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 
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


	 public function getNumberOfPages(){
		$data =array();
		$sql = "SELECT Count(id) as noOfCinematography FROM tbl_cinematography WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 
 
		$result = $this->dbc->get_rows($sql);
		
		$data["noOfCinematography"]=$result[0]['noOfCinematography'];
  
		self::sendResponse("1",$data);
	 }

	 public function getCinematographyRecs(){

        $offset = $_REQUEST['offset'];
        $limit = $_REQUEST['limit'];
        
        $data =array();
        $sql = "SELECT id,main_tittle,image_story,`description`,category,event_place,small_description,video, (SELECT COUNT(*) FROM cinematography_views
        WHERE cinematography_id = tbl_cinematography.id) AS viewCounts FROM tbl_cinematography WHERE `deleted` = 0 AND `active` = 1 order by id desc  limit $offset,$limit "; 

        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }
     
     public function getAllCinematographyRecs(){
         
        $selStart = $_REQUEST['selStart'];
        $selStop = $_REQUEST['selStop'];
        
        $user_state_vals = $_REQUEST['user_state_val'];
        $where = '';
        if($user_state_vals != "")$where = " and FIND_IN_SET($user_state_vals, state_id) ";
        
     
        $data =array();
        
        if($selStart == ""){
             $sql = "SELECT *, (SELECT COUNT(*) FROM cinematography_views
        WHERE cinematography_id = tbl_cinematography.id) AS viewCounts FROM tbl_cinematography WHERE `deleted` = 0 AND `active` = 1 $where order by id desc"; 
        }else{
             $sql = "SELECT *, (SELECT COUNT(*) FROM cinematography_views
        WHERE cinematography_id = tbl_cinematography.id) AS viewCounts FROM tbl_cinematography WHERE `deleted` = 0 AND `active` = 1 AND `created_date` BETWEEN '$selStart' AND '$selStop'   $where order by id desc"; 
        }

        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }
     
     
     public function likeCinematography(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM cne_album_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `cne_album_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `cne_album_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `cne_album_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT main_tittle FROM tbl_cinematography WHERE id=$projId_id_like ";

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
        $activityMeg = "Cinematography ".$prjName." ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM cne_album_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}





   

}

?>