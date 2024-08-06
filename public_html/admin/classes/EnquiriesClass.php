<?php

class Enquiries {
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

	public function getEnquiriesList(){
        
		$sql = "SELECT * FROM tbeenquiry_data ORDER BY id DESC";
		$result = $this->dbc->get_rows($sql);
       
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}

   

}

?>