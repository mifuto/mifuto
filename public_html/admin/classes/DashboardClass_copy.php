<?php
class Dashboard {
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

	public function getCounts(){
       $data =array();

       $sql = "SELECT t1.Count AS totalOnlineAlbum, t2.Count AS SignatureAlbum, t3.Count AS noOfCustomers FROM (SELECT COUNT(id) AS Count FROM tbevents_data WHERE `deleted` = 0 ) AS t1, (SELECT COUNT(id) AS Count FROM tbesignaturealbum_projects WHERE `deleted` = 0 ) AS t2, (SELECT COUNT(id) AS Count FROM tblcontacts WHERE `active` = 1 ) AS t3 "; 

       $result = $this->dbc->get_rows($sql);
       
      $sql2 = "SELECT COUNT(id) AS Count FROM tbesignaturealbum_data WHERE `deleted`=0 AND project_folder_id IN (SELECT id FROM tbesignaturealbum_projects WHERE `deleted` = 0);";
    //   print_r($sql2); die;
      $result2 = $this->dbc->get_rows($sql2);
       
       $data["totalOnlineAlbum"]=$result[0]['totalOnlineAlbum'];
       $data["SignatureAlbum"]=$result[0]['SignatureAlbum'];
       $data["noOfCustomers"]=$result[0]['noOfCustomers'];
       $data["noOfSignatureAlbumEvents"]=$result2[0]['Count'];

       self::sendResponse("1",$data);
	}

	
}

?>