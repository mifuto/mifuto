<?php
class Blogs {
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

	public function getNumberOfPages(){
       $data =array();
       $sql = "SELECT Count(id) as noOfBlogs FROM blogs WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 

       $result = $this->dbc->get_rows($sql);
       
       $data["noOfBlogs"]=$result[0]['noOfBlogs'];
 
       self::sendResponse("1",$data);
	}

    public function getBlogRecs(){

        $offset = $_REQUEST['offset'];
        $limit = $_REQUEST['limit'];
        
        $data =array();
        $sql = "SELECT id,sub_tittle,tittle,`image`,small_description,posted_date,author,video, (SELECT COUNT(*) FROM blogs_views
        WHERE blogs_id = blogs.id) AS viewCounts , (SELECT COUNT(*) FROM blogs_shares
        WHERE blogs_id = blogs.id) AS shareCounts FROM blogs WHERE `deleted` = 0 AND `active` = 1 order by id desc  limit $offset,$limit "; 
 
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }

    public function getBlogRec(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        $sql = "SELECT *, (SELECT COUNT(*) FROM blogs_views
        WHERE blogs_id = blogs.id) AS viewCounts FROM blogs WHERE id = $id "; 
 
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
        
        // $vs = "INSERT INTO `blogs_views`(`blogs_id`, `IP` ) VALUES ('$id','$ip')";
        $vs = "INSERT INTO `blogs_views`(`blogs_id`, `IP`)
        SELECT '$id', '$ip'
        WHERE NOT EXISTS (
            SELECT * FROM `blogs_views`
            WHERE `blogs_id` = '$id' AND `IP` = '$ip'
        )";

        $this->dbc->insert_row($vs);
  
        self::sendResponse("1",$data);
     }

     public function getNxtPrv(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        $sql = "SELECT id,tittle FROM blogs WHERE `deleted` = 0 AND `active` = 1 order by id desc "; 
 
        $result = $this->dbc->get_rows($sql);
        $prv ='';
        $nxt ='';
        $prvName ='';
        $nxtName ='';

        if( sizeof($result) == 1){
            $prv =$result[0]['id'];
            $nxt =$result[0]['id'];
            $prvName =$result[0]['tittle'];
            $nxtName =$result[0]['tittle'];
        }else if( sizeof($result) == 2){
            $prv =$result[0]['id'];
            $nxt =$result[1]['id'];
            $prvName =$result[0]['tittle'];
            $nxtName =$result[1]['tittle'];
        }else{
            for($i=0;$i<sizeof($result);$i++){
                if( $id == $result[$i]['id'] ){
                    if($i == 0){
                        $prv =$result[0]['id'];
                        $nxt =$result[1]['id'];
                        $prvName =$result[0]['tittle'];
                        $nxtName =$result[1]['tittle'];
                    }else if($i == (sizeof($result)-1) ){
                        $prv =$result[$i-1]['id'];
                        $nxt =$result[$i]['id'];
                        $prvName =$result[$i-1]['tittle'];
                        $nxtName =$result[$i]['tittle'];
                    }else{
                        $prv =$result[$i-1]['id'];
                        $nxt =$result[$i+1]['id'];
                        $prvName =$result[$i-1]['tittle'];
                        $nxtName =$result[$i+1]['tittle'];
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
        $sql = "SELECT id,sub_tittle,tittle,`image`,small_description,posted_date,author,video FROM blogs WHERE `deleted` = 0 AND `active` = 1 AND id != $id order by id desc  limit 0,6 "; 
 
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


		$vs = "INSERT INTO `blogs_shares` (`blogs_id`, `IP` ) VALUES ('$Id','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	public function getAllBlogsRecs(){
	    
	       $selStart = $_REQUEST['selStart'];
        $selStop = $_REQUEST['selStop'];
        
        
          $user_state_vals = $_REQUEST['user_state_val'];
        $where = '';
        if($user_state_vals != "")$where = " and FIND_IN_SET($user_state_vals, a.state_id) ";


      
        $data =array();
        
         if($selStart == ""){
            
        $sql = "SELECT a.*,b.firstname, b.lastname FROM blogs a left join tblstaff b on a.author=b.staffid WHERE a.deleted=0 AND a.active=1 $where ORDER BY a.id DESC"; 
        
        }else{
             
        $sql = "SELECT a.*,b.firstname, b.lastname FROM blogs a left join tblstaff b on a.author=b.staffid WHERE a.deleted=0 AND a.active=1 AND a.created_date BETWEEN '$selStart' AND '$selStop' $where ORDER BY a.id DESC"; 
        
        }
      
      
        $result = $this->dbc->get_rows($sql);
        
        $data["res"]=$result;
  
        self::sendResponse("1",$data);
     }
     
     
    public function getBlogsRec(){
        $id = (int)$_REQUEST['id'];
        $data =array();
        
            
        $user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
        
        
        
        $sql = "SELECT a.*,b.firstname, b.lastname, (SELECT COUNT(*) FROM blogs_views
        WHERE blogs_id = a.id) AS viewCounts, (SELECT COUNT(*) FROM blogs_shares
        WHERE blogs_id = a.id) AS shareCounts ,(SELECT COUNT(*) FROM blogs_album_like WHERE project_id = a.id AND status=1 AND active=0) as likeCount,(SELECT `status` FROM blogs_album_like WHERE project_id=a.id AND user_id='$user_id_like' AND user_type='$user_type_val' AND active=0) as like_status FROM blogs a left join tblstaff b on a.author=b.staffid WHERE id = $id "; 
 
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
        
        $vs = "INSERT INTO `blogs_views` (`blogs_id`, `IP` ) VALUES ('$id','$ip')";
		$this->dbc->insert_row($vs);
  
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
        $activityMeg = "Share blog ".$name;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "share");

		$vs = "INSERT INTO `blogs_shares`(`blogs_id`, `IP` ) VALUES ('$projId','$ip')";
		$this->dbc->insert_row($vs);
	}
	
	
	public function likeBlogs(){
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$projId_id_like = (int)$_REQUEST["projId_id_like"];
		
		$sts = "";
	
		if( $status == 1 ){

		    $sql1 = "SELECT * FROM blogs_album_like WHERE project_id='$projId_id_like' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
		    $AlbumList = $this->dbc->get_rows($sql1);
		    
		    if(sizeof($AlbumList) > 0 ){
		        $vs = "UPDATE `blogs_album_like` SET `active`=0 , `status`=1  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		        $result = $this->dbc->update_row($vs);
		    }else{
		        $vs = "INSERT INTO `blogs_album_like`(`project_id`, `user_id` , `user_type`,`active`,`status` ) VALUES ('$projId_id_like','$user_id_like','$user_type_val',0,1)";
		        $result = $this->dbc->insert_row($vs);
		    }
		    
		    $sts = "liked";
		
		}else{

		    $vs = "UPDATE `blogs_album_like` SET `active`=0 , `status`=0  WHERE `project_id`='$projId_id_like' AND `user_id`='$user_id_like' AND `user_type`='$user_type_val'  ";
		    $result = $this->dbc->update_row($vs);
		    $sts = "dislike";
		}
		

		$sql1 = "SELECT tittle FROM blogs WHERE id=$projId_id_like ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$prjName = $AlbumList[0]['tittle'];

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
        $activityMeg = "Blog ".$prjName." ".$sts." by ".$guestName;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		
		$sqlCount ="SELECT COUNT(*) as likeCount FROM blogs_album_like WHERE project_id = '$projId_id_like' AND status=1 AND active=0 ";
		$CountList = $this->dbc->get_rows($sqlCount);
		$likeCount = $CountList[0]['likeCount'];
		

		if($result != "")self::sendResponse("1", $likeCount);
        else self::sendResponse("2", "Error");
	}




  
}

?>