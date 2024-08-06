<?php
// require_once "classes/CommonFunctions.php";
class CommandHandler {

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

  	public function checkUser(){	
		$userName=$_REQUEST["userName"];
		$password=base64_encode($_REQUEST["password"]);
		// echo $password;
        $sql = "SELECT a.id, a.username, a.password, a.role_id, b.name, a.last_login_time FROM user a , role b WHERE a.username=? AND a.password= ? AND a.role_id = b.id"; 
		$stmt = $this->dbc->prepare($sql); 
		$stmt->bind_param("ss", $userName, $password);
		$stmt->execute();
		$result = $stmt->get_result(); 
		$user = $result->fetch_assoc();
		$data=$user;
        $_SESSION['ForStarUser']=$user;
        // print_r($_SESSION['ForStarUser']);
        self::sendResponse("1",$data);

        // $updateLoginTime = $userObj->UpdateLoginTime($userId);

	}
	

	// Code for Sunday Holiday
	// $weekDay = date('w', strtotime($PlannedDate));
	// if($weekDay ==0){
	// 	$date = new DateTime($PlannedDate);
	// 	$date->modify('+1 day');
	// 	$PlannedDate= $date->format('Y-m-d');
	// }
	 


	//Code for group bundle 

		// if(!isset($companies[$x])){
	// 			$company=array();
	// 			$company['comp']=$row["companyname"];
	// 			$company['users']=array();

	// 			$companies[$x]=$company;
//     	}
    	
//    		$company=$companies[$x];
//    		array_push($company["users"],$row);
//    		$companies[$x]=$company;

//     }

 //    	$resp = array();
 //    	$resp["status"]=1;
 //    	$resp["data"]=array_values($companies);
 //    	echo json_encode($resp);

	
}

?>
