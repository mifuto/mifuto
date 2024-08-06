<?php
class AlbumSubscription {
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

	public function save(){
		$data=array();
		
		if(isset($_REQUEST['name'])) $data["name"]=$_REQUEST['name'];
		if(isset($_REQUEST['period'])) $data["period"]=$_REQUEST['period'];
		if(isset($_REQUEST['amount'])) $data["amount"]=$_REQUEST['amount'];
		if(isset($_REQUEST['pamount'])) $data["pamount"]=$_REQUEST['pamount'];
		if(isset($_REQUEST['signature'])) $data["signature"]=$_REQUEST['signature'] == 'true' || $_REQUEST['signature'] == 1 ? 1 : 0;
		if(isset($_REQUEST['photo_count'])) $data["photo_count"]=$_REQUEST['photo_count'];
		if(isset($_REQUEST['online'])) $data["online"]=$_REQUEST['online'] == 'true' || $_REQUEST['online'] == 1 ? 1 : 0;
		if(isset($_REQUEST['features'])) $data["featurs"]=implode(',', $_REQUEST['features']);

		if(isset($_REQUEST['active'])) $data["active"]=$_REQUEST['active'];
		if(isset($_REQUEST['is_primary'])) $data["is_primary"]=$_REQUEST['is_primary'];

		// print_r($data);
		// die;
		if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];
		
		if($id != "") {

			$name = $_REQUEST['name'];
			$period = $_REQUEST['period'];
			$amount = $_REQUEST['amount'];
			$pamount = $_REQUEST['pamount'];
			$signature = $_REQUEST['signature'] == 'true' || $_REQUEST['signature'] == 1 ? 1 : 0;
			$photo_count = $_REQUEST['photo_count'];
			$online = $_REQUEST['online'] == 'true' || $_REQUEST['online'] == 1 ? 1 : 0;
			$features = implode(',', $_REQUEST['features']);
			
			$sql = "UPDATE tblalbumsubscription SET `name` = '$name' , `period` = $period, amount = $amount , pamount= $pamount , `signature`=$signature, photo_count=$photo_count , `online`=$online , featurs='$features' ,updated_on = now() , is_primary =0 WHERE id = $id ";

			// echo $sql;

			$result = $this->dbc->update_row($sql);

			if(isset($result))self::sendResponse("1", "Subscription updated successfull");
			else self::sendResponse("2", "Failed to update subscription");

		} else {
			$result = $this->dbc->insert_query($data, 'tblalbumsubscription');
		}

		if($result != "")self::sendResponse("1", "Successfully add new subscription plan");
        else self::sendResponse("2", "Failed to add new subscription plan");

	}

	function get() {
		$sql = "SELECT * FROM tblalbumsubscription";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}

	function getOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tblalbumsubscription WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}

	public function delete() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tblalbumsubscription SET `delete` = 1 , deleted_on = now() ,updated_on = now(), is_primary = 0 WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Successfully deleted the Plan");
        else self::sendResponse("2", "Failed to deleted the Plan");

	}

	public function restore() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tblalbumsubscription SET `delete` = 0 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Successfully restored the plan");
        else self::sendResponse("2", "Failed to restored the plan");

	}

	public function permanentDelete() {
		$id = $_REQUEST['id'];

		$sql = "DELETE FROM tblalbumsubscription WHERE id = $id;
		";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Deleted plan permanently");
        else self::sendResponse("2", "Unable to delete");

	}

	public function setPlanActivate() {
		$id = $_REQUEST['id'];
		$state = $_REQUEST['state'];

		$sql = "UPDATE tblalbumsubscription SET active = $state , updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Successfully set active status");
        else self::sendResponse("2", "Not updated data");
	}

	public function setPlanAsPrimary() {
		$id = $_REQUEST['id'];
		$is_set = $_REQUEST['is_set'];

		$signature = $_REQUEST['signature'];
		$online = $_REQUEST['online'];

		$sql = "UPDATE tblalbumsubscription SET is_primary = 0 , updated_on = now() WHERE is_primary = 1 and `signature`=$signature and `online`=$online ";
		$res = $this->dbc->update_row($sql);

		if($is_set == 1){
			
			$sql1 = "UPDATE tblalbumsubscription SET is_primary = 1 , updated_on = now() WHERE id = $id ";
			$result = $this->dbc->update_row($sql1);

			if(isset($result))self::sendResponse("1", "Successfully set primary");
        	else self::sendResponse("2", "Failed to set primary");
		}

		if(isset($res))self::sendResponse("1", "Successfully release primary");
        else self::sendResponse("2", "Failed to release primary");
	}

	function getSA() {

		$signature = $_REQUEST['signature'];
		$online = $_REQUEST['online'];

		$sql = "SELECT * FROM tblalbumsubscription where active=1 and `delete` = 0 and `signature` =$signature and `online`=$online ";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}


	public function purchaseSA(){ 
		$data=array();
		$isSA = true;
		
		if(isset($_REQUEST['plan_id'])) $data["plan_id"]=(int)$_REQUEST['plan_id'];

		$state = (int)$_REQUEST['state'];
		if($state == 1){
			if(isset($_REQUEST['album_id'])) $data["signature_album_id"]=(int)$_REQUEST['album_id'];
			$album_id =(int)$_REQUEST['album_id'];
			$extendSql = "SELECT `expiry_date` FROM tbesignaturealbum_projects WHERE id=$album_id";
			$isSA = true;
		}else{
			if(isset($_REQUEST['album_id'])) $data["online_album_id"]=(int)$_REQUEST['album_id'];
			$album_id =(int)$_REQUEST['album_id'];
			$extendSql = "SELECT `expiry_date` FROM tbevents_data WHERE id=$album_id";
			$isSA = false;
		}

		$extendResult = $this->dbc->get_rows($extendSql);
		$StaringDate = $extendResult[0]['expiry_date'] ;
		
		$plan_id = (int)$_REQUEST['plan_id'];

		$sql = "SELECT * FROM tblalbumsubscription WHERE id=$plan_id";
		$result = $this->dbc->get_rows($sql);

		if($result != ""){
			if(sizeof($result) > 0 ){
				$data["name"]=$result[0]['name'];
				$data["period"]=$result[0]['period'];
				$data["amount"]=$result[0]['amount'];
				$data["pamount"]=$result[0]['pamount'];
				$data["signature"]=$result[0]['signature'];
				$data["photo_count"]=$result[0]['photo_count'];
				$data["online"]=$result[0]['online'];
				$data["featurs"]=$result[0]['featurs'];
				$data["active"]=1;
				$period = $result[0]['period'];
				
				$data["payment_status"] = $_REQUEST['payment_status'];
				$data["payment_id"] = $_REQUEST['payment_id'];
				$data["order_id"] = $_REQUEST['order_id'];
				$data["razorpay_signature"] = $_REQUEST['razorpay_signature'];
				$data["error_code"] = $_REQUEST['error_code'];
				$data["error_reason"] = $_REQUEST['error_reason'];

				$result1 = $this->dbc->insert_query($data, 'tblsignaturealbumsubscription');

				if($result1 != "" && $_REQUEST['payment_status'] == 1){
					$newExpDate = date("Y-m-d ", strtotime(date("Y-m-d", strtotime($StaringDate)) . " + $period year"));

					if($isSA ){
						$query = "UPDATE `tbesignaturealbum_projects` SET `expiry_date`='$newExpDate' WHERE `id`=$album_id";
						$resulte = $this->dbc->update_row($query);
					}else{
						$query = "UPDATE `tbevents_data` SET `expiry_date`='$newExpDate' WHERE `id`=$album_id";
						$resulte = $this->dbc->update_row($query);
					}
					
					if($resulte != "")self::sendResponse("1", "Successfully purchase this plan");
				} else {
					if($_REQUEST['payment_status'] != 1) self::sendResponse("1", "Payment was unsuccessful due to a temporary issue. If amount got deducted, it will be refunded within 5-7 working days.");
				}
			}
		}

		self::sendResponse("2", "Failed to purchase this plan");

	}

	

}
?>