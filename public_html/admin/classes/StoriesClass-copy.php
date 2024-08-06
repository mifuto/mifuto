<?php
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


  
}

?>