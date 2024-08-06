<?php
require_once('DashboardClass.php');
require_once('sendMailClass.php');


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
			
			$sql = "UPDATE tblalbumsubscription SET `name` = '$name' , `period` = $period, amount = $amount , pamount= $pamount , `signature`=$signature, photo_count='$photo_count' , `online`=$online , featurs='$features' ,updated_on = now() , is_primary =0 WHERE id = $id ";

// 			echo $sql;

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			
			$activityMeg = $isUsername." update subscription plan ".$name." with  period ".$period ." year and amount ₹".$amount;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

			$result = $this->dbc->update_row($sql);

			if(isset($result))self::sendResponse("1", "Subscription updated successfull");
			else self::sendResponse("2", "Failed to update subscription");

		} else {

			$name = $_REQUEST['name'];
			$period = $_REQUEST['period'];
			$pamount = $_REQUEST['pamount'];

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			$activityMeg = $isUsername." create new subscription plan ".$name." with  period ".$period ." year and amount ₹".$amount;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);


			$result = $this->dbc->insert_query($data, 'tblalbumsubscription');
		}

		if($result != "")self::sendResponse("1", "Successfully add new subscription plan");
        else self::sendResponse("2", "Failed to add new subscription plan");

	}

	function get() {
	    
	    $disType = $_REQUEST["disType"];
	    if($disType == ""){
	        $sql = "SELECT * FROM tblalbumsubscription order by id asc";
	    }else if($disType == 1){
	        $sql = "SELECT * FROM tblalbumsubscription where online=1 order by id asc";
	    }else if($disType == 2){
	        $sql = "SELECT * FROM tblalbumsubscription where signature=1 order by id asc";
	    }
	    
	    
		
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

		$sqlf = "SELECT * FROM tblalbumsubscription WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$name = $planList[0]['name'];
		$period = $planList[0]['period'];
		$pamount = $planList[0]['amount'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		$activityMeg = $isUsername." deleted subscription plan ".$name." with  period ".$period ." year and amount ₹".$pamount;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted the Plan");
        else self::sendResponse("2", "Failed to deleted the Plan");

	}

	public function restore() {
		$id = $_REQUEST['id'];

		$sqlf = "SELECT * FROM tblalbumsubscription WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$name = $planList[0]['name'];
		$period = $planList[0]['period'];
		$pamount = $planList[0]['pamount'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		$activityMeg = $isUsername." restore subscription plan ".$name." with  period ".$period ." year and amount ₹".$pamount;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

		$sql = "UPDATE tblalbumsubscription SET `delete` = 0 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Successfully restored the plan");
        else self::sendResponse("2", "Failed to restored the plan");

	}

	public function permanentDelete() {
		$id = $_REQUEST['id'];

		
		$sqlf = "SELECT * FROM tblalbumsubscription WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$name = $planList[0]['name'];
		$period = $planList[0]['period'];
		$pamount = $planList[0]['pamount'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		$activityMeg = $isUsername." permanently delete subscription plan ".$name." with  period ".$period ." year and amount ₹".$pamount;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		$sql = "DELETE FROM tblalbumsubscription WHERE id = $id;
		";
		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Deleted plan permanently");
        else self::sendResponse("2", "Unable to delete");

	}

	public function setPlanActivate() {
		$id = $_REQUEST['id'];
		$state = $_REQUEST['state'];

		if($state == 1) $dsas = "Activate";
		else $dsas = "Deactivate";


		$sqlf = "SELECT * FROM tblalbumsubscription WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$name = $planList[0]['name'];
		$period = $planList[0]['period'];
		$pamount = $planList[0]['pamount'];
		$recentActivity = new Dashboard(true);
		$activityMeg = $dsas." subscription plan ".$name." with  period ".$period ." year and amount ₹".$pamount;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");


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

		if($is_set == 1) $dsas = "Set as primary";
		else $dsas = "Set as default";

		$sqlf = "SELECT * FROM tblalbumsubscription WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$name = $planList[0]['name'];
		$period = $planList[0]['period'];
		$pamount = $planList[0]['pamount'];
		$recentActivity = new Dashboard(true);
		$activityMeg = $dsas." subscription plan ".$name." with  period ".$period ." year and amount ₹".$pamount;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

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

					if($state == 1){
						
						$album_id =(int)$_REQUEST['album_id'];
						
						$sqlD = "SELECT * FROM tbesignaturealbum_projects WHERE id=$album_id  ";
						$evtData = $this->dbc->get_rows($sqlD);
						$user_id = $evtData[0]['user_id'];

						$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
						$userList = $this->dbc->get_rows($sql1);
						$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];

						$planNam=$result[0]['name'];
						$planPrd=$result[0]['period'];
						$planAmt=$result[0]['pamount'];

						$recentActivity = new Dashboard(true);
						$prjName = $evtData[0]['project_name'];
						$activityMeg = "User ".$eventUser." purchased ".$planNam." for ".$planPrd." years with amount of ₹".$planAmt." for signature album ".$prjName;
						$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");


					}else{
						
						$album_id =(int)$_REQUEST['album_id'];

						$sqlD = "SELECT * FROM tbevents_data WHERE id=$album_id  ";
						$evtData = $this->dbc->get_rows($sqlD);
						$user_id = $evtData[0]['user_id'];

						$sql1 = "SELECT firstname, lastname, email FROM `tblcontacts` WHERE id=$user_id ";
						$userList = $this->dbc->get_rows($sql1);
						$eventUser = $userList[0]['firstname']." ".$userList[0]['lastname'];

						$planNam=$result[0]['name'];
						$planPrd=$result[0]['period'];
						$planAmt=$result[0]['pamount'];

						$recentActivity = new Dashboard(true);
						$prjName = $evtData[0]['event_name'];
						$activityMeg = "User ".$eventUser." purchased ".$planNam." for ".$planPrd." years with amount of ₹".$planAmt." for online album ".$prjName;
						$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");


					}

					if($resulte != "")self::sendResponse("1", "Successfully purchase this plan");
				} else {
					if($_REQUEST['payment_status'] != 1) self::sendResponse("1", "Payment was unsuccessful due to a temporary issue. If amount got deducted, it will be refunded within 5-7 working days.");
				}
			}
		}

		self::sendResponse("2", "Failed to purchase this plan");

	}
	
	
	
	
	
	
	public function saveCoupon(){
		$data=array();
		
		if(isset($_REQUEST['CouponCode'])) $data["CouponCode"]=$_REQUEST['CouponCode'];
		if(isset($_REQUEST['AlbumType'])) $data["AlbumType"]=$_REQUEST['AlbumType'];
		if(isset($_REQUEST['CouponsEndDate'])) $data["CouponsEndDate"]=$_REQUEST['CouponsEndDate'];
		if(isset($_REQUEST['CouponsStartDate'])) $data["CouponsStartDate"]=$_REQUEST['CouponsStartDate'];
		if(isset($_REQUEST['DiscountType'])) $data["DiscountType"]=$_REQUEST['DiscountType'];

		if(isset($_REQUEST['CouponDiscount'])) $data["CouponDiscount"]=$_REQUEST['CouponDiscount'];

		// print_r($data);
		// die;
		if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];
		
		$CouponCodeData = $_REQUEST['CouponCode'];
		
		$chkSql = "SELECT id FROM tblalbumsubscriptioncoupon where `delete`=0 AND CouponCode='$CouponCodeData' AND id !='$id' ";
		$chkList = $this->dbc->get_rows($chkSql);
		
		if(isset($chkList[0])){
		    self::sendResponse("2", "Coupon code already exists.");
		}
		
	
		
		if($id != "") {

			$CouponCode = $_REQUEST['CouponCode'];
			$AlbumType = $_REQUEST['AlbumType'];
			$CouponsEndDate = $_REQUEST['CouponsEndDate'];
			$CouponsStartDate = $_REQUEST['CouponsStartDate'];
			$DiscountType = $_REQUEST['DiscountType'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			
			$sql = "UPDATE tblalbumsubscriptioncoupon SET `CouponCode` = '$CouponCode' , `AlbumType` = '$AlbumType', CouponsEndDate = '$CouponsEndDate' , CouponsStartDate= '$CouponsStartDate' , `DiscountType`='$DiscountType', CouponDiscount='$CouponDiscount' ,updated_on = now() WHERE id = $id ";

// 			echo $sql;

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			if($DiscountType == 1) $activityMeg = $isUsername." update Coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." update Coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

			$result = $this->dbc->update_row($sql);

			if(isset($result))self::sendResponse("1", "Coupon updated successfull");
			else self::sendResponse("2", "Failed to update coupon");

		} else {

			$CouponCode = $_REQUEST['CouponCode'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			$DiscountType = $_REQUEST['DiscountType'];
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			if($DiscountType == 1) $activityMeg = $isUsername." create new Coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." create new Coupon ".$CouponCode." with  discount ".$CouponDiscount."%";

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);


			$result = $this->dbc->insert_query($data, 'tblalbumsubscriptioncoupon');
		}

		if($result != "")self::sendResponse("1", "Successfully add new coupon");
        else self::sendResponse("2", "Failed to add new coupon");

	}
	
	function getCouponDiscount() {
	    
	    $disType = $_REQUEST["disType"];
	    if($disType == ""){
	        $sql = "SELECT * FROM tblalbumsubscriptioncoupon where `delete`=0 order by id desc ";
	    }else {
	        $sql = "SELECT * FROM tblalbumsubscriptioncoupon where `delete`=0 AND AlbumType='$disType' order by id desc ";
	    }
	    
	        
	   
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	function getCouponOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tblalbumsubscriptioncoupon WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	public function deleteCoupon() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tblalbumsubscriptioncoupon SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tblalbumsubscriptioncoupon WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CouponCode = $planList[0]['CouponCode'];
		$CouponDiscount = $planList[0]['CouponDiscount'];
		$DiscountType = $planList[0]['DiscountType'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
		if($DiscountType == 1) $activityMeg = $isUsername." deleted Coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
		else $activityMeg = $isUsername." deleted Coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted coupon");
        else self::sendResponse("2", "Failed to deleted the coupon");

	}
	
	
	public function validateAddress(){
		$data=array();
		
	
		$userid=$_REQUEST['userid'];
		
	
		$InputCountry = $_REQUEST['InputCountry'];
		$InputState = $_REQUEST['InputState'];
		$InputCity = $_REQUEST['InputCity'];
		$InputZip = $_REQUEST['InputZip'];
		$TextAddress = $_REQUEST['TextAddress'];
	
		$sql = "UPDATE tblclients SET `country` = '$InputCountry' , `city` = '$InputCity', zip = '$InputZip' , state= '$InputState' , `address`='$TextAddress' WHERE userid = $userid ";



		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Address updated successfull");
		else self::sendResponse("2", "Failed to update address");

	

	}
	
	public function validateMifutoAddress(){
		$data=array();
		
	
		$userid=$_REQUEST['userid'];
		
	
		$InputCountry = $_REQUEST['InputCountry'];
		$InputState = $_REQUEST['InputState'];
		$InputCity = $_REQUEST['InputCity'];
		$InputZip = $_REQUEST['InputZip'];
		$TextAddress = $_REQUEST['TextAddress'];
	
		$sql = "UPDATE mifuto_users SET `country` = '$InputCountry' , `city` = '$InputCity', zip = '$InputZip' , state= '$InputState' , `address`='$TextAddress' WHERE id = $userid ";



		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Address updated successfull");
		else self::sendResponse("2", "Failed to update address");

	

	}
	
	public function validateMifutoAddressNew(){
		$data=array();
		
	
		$userid=$_REQUEST['userid'];
		
	
		$InputCountry = $_REQUEST['InputCountry'];
		$InputState = $_REQUEST['InputState'];
		$InputCity = $_REQUEST['InputCity'];
		$InputZip = $_REQUEST['InputZip'];
		$TextAddress = $_REQUEST['TextAddress'];
		$InputPhone = $_REQUEST['InputPhone'];
	
		$sql = "UPDATE mifuto_users SET `country` = '$InputCountry' , `city` = '$InputCity', zip = '$InputZip' , state= '$InputState' , `address`='$TextAddress', phone='$InputPhone' WHERE id = $userid ";



		$result = $this->dbc->update_row($sql);

		if(isset($result))self::sendResponse("1", "Address updated successfull");
		else self::sendResponse("2", "Failed to update address");

	

	}
	
	public function addCart(){
		$data=array();
		
		
	
		
		$data["album_id"]=$_REQUEST['albumID'];
		$data["album_type"]=$_REQUEST['albumType'];
		$data["quantity"]=$_REQUEST['quantity'];
		$data["user_id"]=$_REQUEST['user_id'];
		
		
		$album_id = $_REQUEST['albumID'];
		$albumType = $_REQUEST['albumType'];
		$quantity = $_REQUEST['quantity'];
		$imageCount = $_REQUEST['imageCount'];
		$user_id = $_REQUEST['user_id'];
		
		
	
		
		$data["imageCount"]=$_REQUEST['imageCount'];
		
		if($albumType == 'OA'){
		    $sql3 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1 AND `online`=1 AND `delete`=0 order by period asc";
		}else{
		    if($imageCount > 4999) $sql3 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count='Unlimited' AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 2999) $sql3 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=4999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 1499) $sql3 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=2999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else $sql3 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1499 AND `signature`=1 AND `delete`=0 order by period asc ";
		}
	
	
		$AlbumList1 = $this->dbc->get_rows($sql3);
		
		$data["offer"]=$AlbumList1[0]['pamount'];
		$data["amount"]=$AlbumList1[0]['amount'];
		
	
		$offerPriceP = intval($AlbumList1[0]['pamount']);
        $actualPrice = intval($AlbumList1[0]['amount']);
        
        $finalPrice = ($actualPrice - (($actualPrice / 100) * $offerPriceP));
        // $finalPrice = number_format($finalPrice, 2);
		
		
		$data["final_amount"]=$finalPrice;
		$data["quantity"]=$quantity;
		$data["plan_id"]=$AlbumList1[0]['id'];
	
		$sql1 = "SELECT COUNT(*) as Count FROM cart WHERE album_id = '$album_id' AND `active`=0 AND album_type='$albumType' ";
		$AlbumList = $this->dbc->get_rows($sql1);
		$Count = $AlbumList[0]['Count'];
		
		if($Count > 0){
		    self::sendResponse("2", "Failed to add");
		}

		$result = $this->dbc->insert_query($data, 'cart');

		if(isset($result))self::sendResponse("1", "Add to cart");
		else self::sendResponse("2", "Failed to add");

	

	}
	
	public function quantityValSetSA(){
	
		
	
		$quantity = $_REQUEST['quantity'];
		$albumType = $_REQUEST['albumType'];
		
		$imageCount = $_REQUEST['imageCount'];
		
		if($albumType == 'OA'){
		    $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1 AND `online`=1 AND `delete`=0 order by period asc";
		}else{
		    
		     if($imageCount > 4999) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count='Unlimited' AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 2999) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=4999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 1499) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=2999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1499 AND `signature`=1 AND `delete`=0 order by period asc ";
                                                        
		   
		    
		}
	   // echo $sql1;
	
		$AlbumList = $this->dbc->get_rows($sql1);

	
		self::sendResponse("1", $AlbumList[0]);

	

	}
	
	public function quantityValSet(){
	
		
	
		$quantity = $_REQUEST['quantity'];
		$albumType = $_REQUEST['albumType'];
		
		if($albumType == 'OA'){
		    $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1 AND `online`=1 AND `delete`=0 order by period asc";
		}else{
		    $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1 AND `signature`=1 AND `delete`=0 order by period asc";
		}
	
	
		$AlbumList = $this->dbc->get_rows($sql1);

	
		self::sendResponse("1", $AlbumList[0]);

	

	}
	
	public function quantityValSetCart(){
	
		
	
		$quantity = $_REQUEST['quantity'];
		$albumType = $_REQUEST['albumType'];
		
		$imageCount = $_REQUEST['imageCount'];
		$cartID = $_REQUEST['cartID'];
		
		if($albumType == 'OA'){
		    $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1 AND `online`=1 AND `delete`=0 order by period asc";
		}else{
		    
		     if($imageCount > 4999) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count='Unlimited' AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 2999) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=4999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else if($imageCount > 1499) $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=2999 AND `signature`=1 AND `delete`=0 order by period asc ";
            else $sql1 = "SELECT * FROM `tblalbumsubscription` WHERE `period` >= $quantity AND photo_count=1499 AND `signature`=1 AND `delete`=0 order by period asc ";
                                                        
		   
		    
		}
	   // echo $sql1;
	
		$AlbumList = $this->dbc->get_rows($sql1);
		
// 		print_r($AlbumList);
		
		$newPrice = $AlbumList[0];
		$offer = $AlbumList[0]['pamount'];
		$amount = $AlbumList[0]['amount'];
		$offerPriceP = $AlbumList[0]['pamount'];
        $actualPrice = $AlbumList[0]['amount'];
        $plan_id = $AlbumList[0]['id'];
        
        $finalPrice = ($actualPrice - (($actualPrice / 100) * $offerPriceP));
        // $finalPrice = number_format($finalPrice, 2);
		
		$sql = "UPDATE cart SET `offer` = '$offer' , amount = '$amount' ,final_amount = '$finalPrice',quantity='$quantity' , plan_id='$plan_id' WHERE id = $cartID ";
		$result = $this->dbc->update_row($sql);
		
		self::sendResponse("1", "Cart updated");

	

	}
	
	public function deleteItem(){
	
		$cartID = $_REQUEST['cartID'];
		$sql = "DELETE FROM cart WHERE id = $cartID ";
		$result = $this->dbc->update_row($sql);
	
	
		self::sendResponse("1", "Item removed");

	

	}
	
	public function applyCouponcode(){
	
		$Couponcode = $_REQUEST['Couponcode'];
		$sql1 = "SELECT * FROM `tblalbumsubscriptioncoupon` WHERE CouponCode='$Couponcode' AND `delete`=0 AND CouponsStartDate <= CURDATE() AND CouponsEndDate >= CURDATE() ";
// 		echo $sql1;
		$result = $this->dbc->get_rows($sql1);
	
	
		self::sendResponse("1", $result);

	

	}
	
	
	public function placeOrderNow(){
	   
		$mainArray = $_REQUEST['mainArray'];
		$numberOfItems = $_REQUEST['numberOfItems'];
		$numberOfItemsPrice = $_REQUEST['numberOfItemsPrice'];
		$numberOfItemsDiscount = $_REQUEST['numberOfItemsDiscount'];
		$numberOfItemsExtraCharge = $_REQUEST['numberOfItemsExtraCharge'];
		$ifCouponApply = $_REQUEST['ifCouponApply'];
		$couponApplyDiscount = $_REQUEST['couponApplyDiscount'];
		$numberOfItemsTotalAmount = $_REQUEST['numberOfItemsTotalAmount'];
		$numberOfItemssave = $_REQUEST['numberOfItemssave'];
		$Couponcode = $_REQUEST['Couponcode'];
		$DiscountType = $_REQUEST['DiscountType'];
		$CouponDiscount = $_REQUEST['CouponDiscount'];
		$user_id = $_REQUEST['user_id'];
	
		// Decode the JSON string to a PHP array
        $cartArray = json_decode($mainArray, true);
        
        // Loop through the array
        foreach ($cartArray as $cartItem) {
            $cartID = $cartItem['cartID'];
            $newExpPackDate = $cartItem['newExpPackDate'];
            $isExtra = $cartItem['isExtra'];
            $extraAmt = $cartItem['extraAmt'];
            
            $sql = "UPDATE cart SET `newExpPackDate` = '$newExpPackDate' , isExtra = '$isExtra' ,extraAmt = '$extraAmt', active=1 WHERE id = $cartID ";
		    $result = $this->dbc->update_row($sql);
            
            
        
            // // Now you can work with the individual elements in each cart item
            // echo "Cart ID: $cartID<br>";
            // echo "New Expiry Pack Date: $newExpPackDate<br>";
            // echo "Is Extra: " . ($isExtra ? 'Yes' : 'No') . "<br>";
            // echo "Extra Amount: $extraAmt<br>";
            // echo "<br>";
        }
        
        $data=array();
        
    	$data["mainArray"]=$mainArray;
		$data["numberOfItems"]=$numberOfItems;
		$data["numberOfItemsPrice"]=$numberOfItemsPrice;
		$data["numberOfItemsDiscount"]=$numberOfItemsDiscount;
		$data["numberOfItemsExtraCharge"]=$numberOfItemsExtraCharge;
		$data["ifCouponApply"]=$ifCouponApply;
		$data["couponApplyDiscount"]=$couponApplyDiscount;
		$data["numberOfItemsTotalAmount"]=$numberOfItemsTotalAmount;

		$data["numberOfItemssave"] = $numberOfItemssave;
		$data["Couponcode"] = $Couponcode;
		$data["DiscountType"] = $DiscountType;
		$data["CouponDiscount"] = $CouponDiscount;
		$data["user_id"] = $user_id;
		
		$result = $this->dbc->insert_query($data, 'place_order');
	
	
		if($result != ""){
		    $timestamp = time();
		    $id = $result['InsertId'];
		    $decodeId = base64_encode($timestamp . "_".$id);
		    $decodeId = str_rot13($decodeId);
		    
		    self::sendResponse("1", $decodeId);
		    
		}else self::sendResponse("0", "Failed to place order");

	

	}
	
	
	
	
	public function updatePayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
		
		$sql = "UPDATE place_order SET `newpurchaseID` = '$newpurchaseID' , razorpay_payment_id = '$razorpay_payment_id' ,razorpay_payment_status = '$razorpay_payment_status',razorpay_signature='razorpay_signature' WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
		
		if($razorpay_payment_status == 1){
		    
		    	$sql1 = "SELECT * FROM `place_order` WHERE id = $purchaseID ";
        		$result1 = $this->dbc->get_rows($sql1);
        		
        		$cartItems = $result1[0];
        		$cart = $cartItems['mainArray'];
        		$cartArray = json_decode($cart, true);
        		
        		
        		// Loop through the array
                foreach ($cartArray as $cartItem) {
                    $cartID = $cartItem['cartID'];
                    $newExpPackDate = $cartItem['newExpPackDate'];
                    $isExtra = $cartItem['isExtra'];
                    $extraAmt = $cartItem['extraAmt'];
                    
                    $sqlcart = "SELECT * FROM `cart` WHERE id = $cartID ";
                    $resultcart = $this->dbc->get_rows($sqlcart);
                    $cartItemsArr = $resultcart[0];
                    
                    $album_type = $cartItemsArr['album_type'];
                    $album_id = $cartItemsArr['album_id'];
                    if($album_type == 'SA'){
                        $sql5 = "UPDATE tbesignaturealbum_projects SET `expiry_date` = '$newExpPackDate' WHERE id = $album_id ";
        		        $result5 = $this->dbc->update_row($sql5);
                    }else{
                        $sql5 = "UPDATE tbevents_data SET `expiry_date` = '$newExpPackDate' WHERE id = $album_id ";
        		        $result5 = $this->dbc->update_row($sql5);
                    }
                    
                    // print_r($cartItemsArr);
                    
                    // // Now you can work with the individual elements in each cart item
                    // echo "Cart ID: $cartID<br>";
                    // echo "New Expiry Pack Date: $newExpPackDate<br>";
                    // echo "Is Extra: " . ($isExtra ? 'Yes' : 'No') . "<br>";
                    // echo "Extra Amount: $extraAmt<br>";
                    // echo "<br>";
                }
                
                $sql6 = "UPDATE place_order SET `is_apply_cart` = 1, completed=1 WHERE id = $purchaseID ";
		        $result6 = $this->dbc->update_row($sql6);
                
                
		    
		}else{
		    $sql6 = "UPDATE place_order SET completed=1 WHERE id = $purchaseID ";
		      $result6 = $this->dbc->update_row($sql6);
		}
		
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    self::sendResponse("1", $decodeId);
		


	}


	public function sendInvoice(){
	
		$purchaseID = $_REQUEST['purchaseID'];
		$user_id = $_REQUEST['user_id'];
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=10 AND mail_template=82 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		
		$sql1 = "SELECT * FROM place_order WHERE id=$purchaseID ";
		$AlbumList = $this->dbc->get_rows($sql1);
		
		$dateTime = new DateTime($AlbumList[0]['created_date']);
        $dateInv = $dateTime->format("Y-m-d");
		
		$html = str_replace("--invoice_no",$AlbumList[0]['newpurchaseID'],$html);
		$html = str_replace("--invoice_date",$dateInv,$html);
		$html = str_replace("--sub_total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amt_total_paid",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amount_due",0,$html);
		$html = str_replace("--price",$AlbumList[0]['numberOfItemsPrice'],$html);
		$html = str_replace("--discount",$AlbumList[0]['numberOfItemsDiscount'],$html);
		$html = str_replace("--no_items",$AlbumList[0]['numberOfItems'],$html);
		$html = str_replace("--service_charge",$AlbumList[0]['numberOfItemsExtraCharge'],$html);
		$html = str_replace("--coupon",$AlbumList[0]['couponApplyDiscount'],$html);
		$html = str_replace("--save_amt",$AlbumList[0]['numberOfItemssave'],$html);
		
		
		
		
		$itm = '<table width="100%" border="1" >';
		
		$itm .='<tr>';
		$itm .='<th>#</th>';
		$itm .='<th>Item</th>';
		$itm .='<th>Year</th>';
		$itm .='<th>Price</th>';
		$itm .='<th>Discount</th>';
		$itm .='<th>Service Charge</th>';
		$itm .='<th>Total</th>';
		$itm .='</tr>';
		
		$cart = $AlbumList[0]['mainArray'];
		$cartArray = json_decode($cart, true);
		
		
		// Loop through the array
		$i = 1;
        foreach ($cartArray as $cartItem) {
            $cartID = $cartItem['cartID'];
            $newExpPackDate = $cartItem['newExpPackDate'];
            $isExtra = $cartItem['isExtra'];
            $extraAmt = $cartItem['extraAmt'];
            
            $sqlcart = "SELECT * FROM `cart` WHERE id = $cartID ";
            $resultcart = $this->dbc->get_rows($sqlcart);
            $cartItemsArr = $resultcart[0];
            
            $album_type = $cartItemsArr['album_type'];
            $album_id = $cartItemsArr['album_id'];
            
            if($album_type == 'SA'){
                $sql5 = "SELECT * FROM tbesignaturealbum_projects WHERE id = $album_id ";
		        $result5 = $this->dbc->get_rows($sql5);
		        $disItem = $result5[0]['project_name']." (Signature album)";
		        
		        
            }else{
                $sql5 = "SELECT * FROM tbevents_data WHERE id = $album_id ";
		        $result5 = $this->dbc->get_rows($sql5);
		        $disItem = $result5[0]['event_name']." (Online album)";
		        
		        
            }
            
            
            $itm .='<tr>';
    		$itm .='<td>'.$i.'</td>';
    		$itm .='<td>'.$disItem.'</td>';
    		$itm .='<td>'.$cartItemsArr['quantity'].'</td>';
    		$itm .='<td>₹'.$cartItemsArr['amount'].'</td>';
    		
    		$disct = floatval($cartItemsArr['amount']) - floatval($cartItemsArr['final_amount']) ;
    		$disct = number_format($disct, 2);
    		
    		$itm .='<td>₹'.$disct.'</td>';
    		$itm .='<td>₹'.$cartItemsArr['extraAmt'].'</td>';
    		$cartItemTotal = floatval($cartItemsArr['final_amount']) + floatval($cartItemsArr['extraAmt']);
    		$cartItemTotal = number_format($cartItemTotal, 2);
    		
    		
    		$itm .='<th>₹'.$cartItemTotal.'</th>';
    		$itm .='</tr>';
            
            
            $i++;
            
            
            
        }
		
		
		
		
		$itm .='</table>';
		
		
		$decimalValue = $AlbumList[0]['numberOfItemsTotalAmount']; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		
		$html = str_replace("--items",$itm,$html);
		
		
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order SET invoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	public function numberToWords($number) {
        $ones = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine'
        );
    
        $tens = array(
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );
    
        if ($number < 10) {
            return $ones[$number];
        } elseif ($number < 20) {
            return $tens[$number];
        } elseif ($number < 100) {
            $tens_digit = (int) ($number / 10) * 10;
            $ones_digit = $number % 10;
            return $tens[$tens_digit] . ($ones_digit ? ' ' . $ones[$ones_digit] : '');
        } elseif ($number < 1000) {
            $hundreds_digit = (int) ($number / 100);
            $remainder = $number % 100;
            return $ones[$hundreds_digit] . ' Hundred' . ($remainder ? ' and ' . $this->numberToWords($remainder) : '');
        } else {
            return 'Number too large to convert';
        }
    }
    
    
     public function getInvoiceList(){
    
        $usersList=$_REQUEST["usersList"];
        $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        
        $invoiceType=$_REQUEST["invoiceType"];
        
        
           $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       
        
        $join = " left join tblcountries z on z.country_id = cct.country ";
        
        
        
        
        $where = '';
        if($invoiceType != "") $where .= " and a.razorpay_payment_status='$invoiceType' " ;
        
        
        if($usersList != "" && $usersList != null){
            $sql = "SELECT a.*,b.firstname,b.lastname,z.short_name as country,cct.city,cct.state FROM `place_order` a left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where a.newpurchaseID !='' and a.user_id='$usersList' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
        }else{
            
            
            if($isAdmin){
                $sql = "SELECT a.*,b.firstname,b.lastname,z.short_name as country,cct.city,cct.state FROM `place_order` a left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
            }else{
                
                 if($manage_type == 'County'){
                       // user type County
                       $sql = "SELECT a.*,b.firstname,b.lastname,z.short_name as country,cct.city,cct.state FROM `place_order` a left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.country = '$county_id' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                       
                   }else if($manage_type == 'State'){
                       // user type State
                        $sql = "SELECT a.*,b.firstname,b.lastname,z.short_name as country,cct.city,cct.state FROM `place_order` a left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.state = '$state' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                      
                   }else {
                       // user type City
                        $sql = "SELECT a.*,b.firstname,b.lastname,z.short_name as country,cct.city,cct.state FROM `place_order` a left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.city = '$city' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                       
                       
                   }
                
            }
            
            
            
            
            
           
        }
        
        
        //  echo $sql;
       
        $result = $this->dbc->get_rows($sql);
         
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
       
      }
      
      public function ExtendAlbumNow(){
          $purchaseID = $_REQUEST['purchaseID'];
          
          $sql1 = "SELECT * FROM `place_order` WHERE id = $purchaseID ";
		$result1 = $this->dbc->get_rows($sql1);
		
		$cartItems = $result1[0];
		$cart = $cartItems['mainArray'];
		$cartArray = json_decode($cart, true);
		
		
		// Loop through the array
        foreach ($cartArray as $cartItem) {
            $cartID = $cartItem['cartID'];
            $newExpPackDate = $cartItem['newExpPackDate'];
            $isExtra = $cartItem['isExtra'];
            $extraAmt = $cartItem['extraAmt'];
            
            $sqlcart = "SELECT * FROM `cart` WHERE id = $cartID ";
            $resultcart = $this->dbc->get_rows($sqlcart);
            $cartItemsArr = $resultcart[0];
            
            $album_type = $cartItemsArr['album_type'];
            $album_id = $cartItemsArr['album_id'];
            if($album_type == 'SA'){
                $sql5 = "UPDATE tbesignaturealbum_projects SET `expiry_date` = '$newExpPackDate' WHERE id = $album_id ";
		        $result5 = $this->dbc->update_row($sql5);
            }else{
                $sql5 = "UPDATE tbevents_data SET `expiry_date` = '$newExpPackDate' WHERE id = $album_id ";
		        $result5 = $this->dbc->update_row($sql5);
            }
            
            // print_r($cartItemsArr);
            
            // // Now you can work with the individual elements in each cart item
            // echo "Cart ID: $cartID<br>";
            // echo "New Expiry Pack Date: $newExpPackDate<br>";
            // echo "Is Extra: " . ($isExtra ? 'Yes' : 'No') . "<br>";
            // echo "Extra Amount: $extraAmt<br>";
            // echo "<br>";
        }
        
        $sql6 = "UPDATE place_order SET `is_apply_cart` = 1, completed=1 WHERE id = $purchaseID ";
        $result6 = $this->dbc->update_row($sql6);
        
        if($result6 != "")self::sendResponse("1", $result6);
        else self::sendResponse("2", "error");
        
        
        
      }
      
      
      function getTotalCount(){
          
        $usersList=$_REQUEST["usersList"];
        $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        
        $invoiceType=$_REQUEST["invoiceType"];
        
         $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
        
        $where = '';
        if($invoiceType != "") $where .= " and a.razorpay_payment_status='$invoiceType' " ;
        if($usersList != "") $where .= " and a.user_id='$usersList' " ;
        
        
        if($isAdmin){
       
             $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsExtraCharge) AS sumOfExtraCharge, SUM(a.couponApplyDiscount) AS sumOfCoupon, SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order` a where a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
             
       }else{
           
            if($manage_type == 'County'){
               // user type County
              $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsExtraCharge) AS sumOfExtraCharge, SUM(a.couponApplyDiscount) AS sumOfCoupon, SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.country = '$county_id' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
               
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsExtraCharge) AS sumOfExtraCharge, SUM(a.couponApplyDiscount) AS sumOfCoupon, SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.state = '$state' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";

           }else {
               // user type City
                $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsExtraCharge) AS sumOfExtraCharge, SUM(a.couponApplyDiscount) AS sumOfCoupon, SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.city = '$city' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
               
           }
           
       }
        
        
       
        
        
        //  echo $sql;
       
        $result = $this->dbc->get_rows($sql);
         
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
          
      }
      
       public function saveCardService(){
		$data=array();
		
		$data["CardService"]=str_replace("'", '"', $_REQUEST['CardService']);
			$data["county_id"]=$_REQUEST['selCounty'];
		$data["state_id"]=$_REQUEST['multipleSel'];
	

// 		print_r($data);
// 		die;
		$id=$_REQUEST['id'];
		
		$CardService = str_replace("'", '"', $_REQUEST['CardService']);

		
		if($id != "") {

	

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			$activityMeg = $isUsername." update Card service ".$CardService ;
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);


            $data_id=array(); $data_id["id"]=$_REQUEST['id'];
			$result=$this->dbc->update_query($data, 'tbl_card_services', $data_id);



			if(isset($result))self::sendResponse("1", "Card service updated successfull");
			else self::sendResponse("2", "Failed to update card service");

		} else {
		    
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			$activityMeg = $isUsername." create new Card service ".$CardService;

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
			
			$result = $this->dbc->insert_query($data, 'tbl_card_services');


		}

		if($result != "")self::sendResponse("1", "Successfully add new card service");
        else self::sendResponse("2", "Failed to add new card service");

	}
	
	function getAllCardservices() {
	    
	     
	   $sql = "SELECT a.*,b.short_name as county_id,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_card_services a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id where a.delete=0 ORDER BY a.id DESC ";
	   
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	function getCardServiceState() {
		$selState = $_REQUEST["selState"];

		$sql = "SELECT * FROM tbl_card_services WHERE FIND_IN_SET($selState, state_id) ";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	

	
	
	
	function getCardServiceOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tbl_card_services WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	
	public function deleteCardService() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tbl_card_services SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tbl_card_services WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CardName = $planList[0]['CardService'];
	

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." deleted Card service ".$CardName;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted card service");
        else self::sendResponse("2", "Failed to deleted the card service");

	}
      
      
      public function saveCards(){
		$data=array();
		
		$data["CardName"]=$_REQUEST['CardName'];
		$data["county_id"]=$_REQUEST['selCounty'];
		$data["state_id"]=$_REQUEST['multipleSel'];
		
		

// 		print_r($data);
// 		die;
		$id=$_REQUEST['id'];
		
		$CardName = $_REQUEST['CardName'];
		
// 		$chkSql = "SELECT id FROM tbl_cards where `delete`=0 AND CardName='$CardName' AND id !='$id' ";
// 		$chkList = $this->dbc->get_rows($chkSql);
		
// 		if(isset($chkList[0])){
// 		    self::sendResponse("2", "Card name already exists.");
// 		}
		
	
		
		if($id != "") {

		
			
// 			$sql = "UPDATE tbl_cards SET `CardName` = '$CardName' , `county_id` = '$selCounty', state_id = '$selState' ,updated_on = now() WHERE id = $id ";

// 			echo $sql;

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			$activityMeg = $isUsername." update Card ".$CardName ;
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

// 			$result = $this->dbc->update_row($sql);

            $data_id=array(); $data_id["id"]=$_REQUEST['id'];
			$result=$this->dbc->update_query($data, 'tbl_cards', $data_id);



			if(isset($result))self::sendResponse("1", "Card updated successfull");
			else self::sendResponse("2", "Failed to update card");

		} else {
		    
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			$activityMeg = $isUsername." create new Card ".$CardName;

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
			
			$result = $this->dbc->insert_query($data, 'tbl_cards');


// 			$result = $this->dbc->insert_query($data, 'tbl_cards');
		}

		if($result != "")self::sendResponse("1", "Successfully add new card");
        else self::sendResponse("2", "Failed to add new card");

	}
	
	function getAllCards() {
	   
	   $sql = "SELECT a.*,b.short_name as county_id,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_cards a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id where a.delete=0 ORDER BY a.id DESC ";
	   
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	function getCardOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tbl_cards WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	public function deleteCard() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tbl_cards SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tbl_cards WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CardName = $planList[0]['CardName'];
	

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." deleted Card ".$CardName;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted card");
        else self::sendResponse("2", "Failed to deleted the card");

	}
	
	function getmainCards() {
	    
	  $sql = "SELECT * FROM tbl_cards where `delete`=0 order by CardName asc"; 

		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	function getCardState() {
	    
	    $selData=$_REQUEST["selData"];
	    $cleanedString = trim($selData, ',');
	    if($cleanedString != ''){
	        $sql = "SELECT * FROM tblstate where id IN ($cleanedString) order by state asc"; 
	  
		    $result = $this->dbc->get_rows($sql);
	    }else $result = [];
	    
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	public function saveSubCard(){
        
        $data=array();
        $data["card_id"]=$_REQUEST['selCard'];
        $data["city_id"]=$_REQUEST['multipleSel'];
        $data["state_id"]=$_REQUEST['selState'];
        $data["exp"]=$_REQUEST['inpExp'];
        $data["sel_services"]=$_REQUEST['selServices'];
        
        $data["CardPurchase"]=$_REQUEST['CardPurchase'];
        $data["CardPurchaseType"]=$_REQUEST['CardPurchaseType'];
        
        $description = str_replace("'", '"', $_REQUEST['inpCSD']);
        $data["description"]=$description;
        
        $data["amount"]=$_REQUEST['inpAamount'];
        $data["discout"]=$_REQUEST['inpDamount'];
        $data["discout_type"]=$_REQUEST['selDiscoutType'];
        $data["exp_amount"]=$_REQUEST['inpExpiredAamount'];
        $data["exp_discout"]=$_REQUEST['inpExpiredDamount'];
        $data["exp_discout_type"]=$_REQUEST['selExpiredDiscoutType'];

        
         if(isset($_FILES['uploadImg']['name']) && $_FILES['uploadImg']['name']!=''){
            $target_1 = 'cards/img_'.time().$_FILES['uploadImg']['name'];
            move_uploaded_file($_FILES['uploadImg']['tmp_name'], $target_1);
            $data['image']=$target_1;
        }
        
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
       
     
		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Sub card for ".$_REQUEST['main_card']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblsubcards');

		}else{
		    
		    $activityMeg = "Sub card for ".$_REQUEST['main_card']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblsubcards', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getCardsListData(){
	    
	   $sql = "SELECT a.*,b.CardName,c.short_name as short_name,d.state as state ,(SELECT GROUP_CONCAT(g.city) FROM tblcity g WHERE FIND_IN_SET(g.id, a.city_id) > 0) AS city FROM tblsubcards a left join tbl_cards b on a.card_id = b.id left join tblcountries c on b.county_id = c.country_id left join tblstate d on d.id = a.state_id WHERE b.delete = 0  ";

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditSubCardList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblsubcards
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function setsetactiveevCard(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblsubcards` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Card");
        else self::sendResponse("2", "Failed to ".$dis." Card");
	
	}
	
	public function getCardBenfits(){
	
		
	
		$cardId = $_REQUEST['cardId'];
	
		$sql1 = "SELECT * FROM `tblsubcards` WHERE `id`=$cardId ";
	
		$AlbumList = $this->dbc->get_rows($sql1);
		

		self::sendResponse("1", $AlbumList);

	

	}
	

	
	public function cardPlaceOrderNow(){
	   
		$purchaseNowId = $_REQUEST['purchaseNowId'];
		$purchaseCardType = $_REQUEST['purchaseCardType'];
		
		
		$projIdString = str_rot13($purchaseNowId);
        $projIdString = base64_decode($projIdString);
        
        $arr = explode('_', $projIdString);
        $purchaseID = $arr[1];
        
        $user_id = $_REQUEST['user_id'];
        
        $totalItemPrice = $_REQUEST['totalItemPrice'];
        $ItemDiscount = $_REQUEST['ItemDiscount'];
        $ItemTotalAmount = $_REQUEST['ItemTotalAmount'];
        $Itemsave = $_REQUEST['Itemsave'];
        
        $exp = $_REQUEST['exp'];
        $purchaseCardNumber = $_REQUEST['CN'];
        if($purchaseCardNumber == '') {
            $randomLNumber = mt_rand(10000, 99999);
            $currentYear = date('Y');
            $randomNumber = '0017777'.$currentYear.''.$randomLNumber;
        }
        else $randomNumber = $purchaseCardNumber;
        
        $today = date("Y-m-d");

        // Calculate the date after one year
        $afterExpYear = date("Y-m-d", strtotime($today . " +$exp year"));
        
		
        $data=array();
        
    	$data["card_id"]=$purchaseID;
    	$data["numberOfItemsPrice"]=$totalItemPrice;
		$data["numberOfItemsDiscount"]=$ItemDiscount;
		$data["numberOfItemsTotalAmount"]=$ItemTotalAmount;
		$data["numberOfItemssave"] = $Itemsave;

		$data["user_id"] = $user_id;
		
		$data["card_number"] = $randomNumber;
		$data["exp_date"] = $afterExpYear;
		
		$data["card_type"] = $purchaseCardType;
		$data["card_services"] = $_REQUEST['CardServices'];
		$data["prj_event_id"] = $_REQUEST['purchaseUserEventID'];
		
		$result = $this->dbc->insert_query($data, 'place_order_card');
	
	
		if($result != ""){
		    $timestamp = time();
		    $id = $result['InsertId'];
		    $decodeId = base64_encode($timestamp . "_".$id);
		    $decodeId = str_rot13($decodeId);
		    
		    self::sendResponse("1", $decodeId);
		    
		}else self::sendResponse("0", "Failed to place order");

	

	}
	
	
	public function sendCardRequest(){
	   
	
        
        $user_id = $_REQUEST['user_id'];
        
        $data=array();
		$data["user_id"] = $user_id;
		$result = $this->dbc->insert_query($data, 'card_request');
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=96 AND `active`=1 ";
		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
			
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email,a.phonenumber FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		$html = str_replace("--phone",$UserList[0]['phonenumber'],$html);
		$html = str_replace("--email",$eventUserEmail,$html);
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , "Machooos International", 'machoosinternational@gmail.com' );
		
		$activityMeg = $eventUser." request a card purchase request ";

		$recentActivity = new Dashboard(true);
		
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
	
	
		if($result != ""){
		   
		    self::sendResponse("1", "Request sent");
		    
		}else self::sendResponse("0", "Failed to sent request");

	

	}
	
		
	public function updateCardPayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
		
		if($razorpay_payment_status == 1){
		    $psql = "SELECT * FROM place_order_card WHERE id = $purchaseID ";
    		$cardData = $this->dbc->get_rows($psql);
    		
    		$card_id = $cardData[0]['card_id'];
    		$user_id = $cardData[0]['user_id'];
    		$prj_event_id = $cardData[0]['prj_event_id'];
    		
    		$sql6 = "UPDATE place_order_card SET isNew=0 WHERE user_id='$user_id' AND prj_event_id='$prj_event_id' ";
    		$result6 = $this->dbc->update_row($sql6);
    		
    		$card_type = $cardData[0]['card_type'];
    		
    		if($card_type == 2){
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=93 AND `active`=1 ";
    		}else{
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=92 AND `active`=1 ";
    		}
    		
    		
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		    //send mail here
    		$subject = $mailTemplate[0]['subject'];
    	
    		$html = $mailTemplate[0]['mail_body'];
    		
    		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		    $UserList = $this->dbc->get_rows($sqlU);
		    
		    $eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		    $eventUserEmail = $UserList[0]['email'];
		    
		    $today = date("Y-m-d");
		    
		    $html = str_replace("--username",$eventUser,$html);
		    $html = str_replace("--card_number",$cardData[0]['card_number'],$html);
		    $html = str_replace("--exp_date",$cardData[0]['exp_date'],$html);
		    $html = str_replace("--purchase_date",$today,$html);
		    
		    
		    $sqlqwe = "SELECT a.*,b.CardName FROM tblsubcards a left join tbl_cards b on a.card_id = b.id WHERE a.id = $card_id  ";
		    $cardDataqwe = $this->dbc->get_rows($sqlqwe);
    		
    		$CardName = $cardDataqwe[0]['CardName'];
    		$Cardexp = $cardDataqwe[0]['exp'];
    		$Carddescription = $cardDataqwe[0]['description'];
		    
		    $html = str_replace("--card_validity",$Cardexp,$html);
		    $html = str_replace("--card_benfits",$Carddescription,$html);
		    $html = str_replace("--card_name",$CardName,$html);
		    
		    $servicesIds = $cardData[0]['card_services'];
    		$sersql1 = "SELECT * FROM `tbl_card_services` WHERE id IN ($servicesIds) ";
    		$ServicesList = $this->dbc->get_rows($sersql1);
    		
    		$serhtml = '<div>';
    		foreach ($ServicesList as $service) {
                $serhtml .= $service['CardService'] . "<br>";
            }
    		
    		$serhtml .= '</div>';
		    
		    
		    $html = str_replace("--services",$serhtml,$html);
		    
    		
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
    		
    		
    		
		}
		
		$sql = "UPDATE place_order_card SET `newpurchaseID` = '$newpurchaseID' , razorpay_payment_id = '$razorpay_payment_id' ,razorpay_payment_status = '$razorpay_payment_status',razorpay_signature='razorpay_signature', completed=1, isNew=1, invoice_snt=0 WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
	
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    self::sendResponse("1", $decodeId);
		


	}
	
	
	public function sendCardInvoice(){
	
		$purchaseID = $_REQUEST['purchaseID'];
		$user_id = $_REQUEST['user_id'];
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=10 AND mail_template=91 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		
		$sql1 = "SELECT * FROM place_order_card WHERE id=$purchaseID ";
		$AlbumList = $this->dbc->get_rows($sql1);
		
		$dateTime = new DateTime($AlbumList[0]['created_date']);
        $dateInv = $dateTime->format("Y-m-d");
		
		$html = str_replace("--invoice_no",$AlbumList[0]['newpurchaseID'],$html);
		$html = str_replace("--invoice_date",$dateInv,$html);
		$html = str_replace("--sub_total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amt_total_paid",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amount_due",0,$html);
		$html = str_replace("--price",$AlbumList[0]['numberOfItemsPrice'],$html);
		$html = str_replace("--discount",$AlbumList[0]['numberOfItemsDiscount'],$html);
		$html = str_replace("--no_items",$AlbumList[0]['numberOfItems'],$html);
		$html = str_replace("--service_charge",0,$html);
		$html = str_replace("--coupon",0,$html);
		$html = str_replace("--save_amt",$AlbumList[0]['numberOfItemssave'],$html);
		
		
		
		
		$itm = '<table width="100%" border="1" >';
		
		$itm .='<tr>';
		$itm .='<th>#</th>';
		$itm .='<th>Item</th>';
		$itm .='<th>Year</th>';
		$itm .='<th>Expiry Date</th>';
		$itm .='<th>Price</th>';
		$itm .='<th>Discount</th>';
		$itm .='<th>Total</th>';
		$itm .='</tr>';
		
		$card_id = $AlbumList[0]['card_id'];
		$sqlcard = "SELECT a.*,b.CardName  FROM tblsubcards a left join tbl_cards b on a.card_id=b.id WHERE a.id='$card_id' ";
		$cardList = $this->dbc->get_rows($sqlcard);
		
		
		$itm .='<tr>';
		$itm .='<td>1</td>';
		$itm .='<td>'.$cardList[0]['CardName'].' card</td>';
		$itm .='<td>'.$cardList[0]['exp'].'</td>';
		$itm .='<td>'.$AlbumList[0]['exp_date'].'</td>';
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsPrice'].'</td>';
	
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsDiscount'].'</td>';
	
		$itm .='<th>₹'.$AlbumList[0]['numberOfItemsTotalAmount'].'</th>';
		$itm .='</tr>';
		
		
		$itm .='</table>';
		
		
		$decimalValue = $AlbumList[0]['numberOfItemsTotalAmount']; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		
		$html = str_replace("--items",$itm,$html);
		
		
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order_card SET invoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	
	  public function getCardInvoiceList(){
    
        $usersList=$_REQUEST["usersList"];
        $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        
        $invoiceType=$_REQUEST["invoiceType"];
        
        
           $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
        
        
        
        $join = " left join tblcountries z on z.country_id = cct.country ";
        
        
        $where = '';
        if($invoiceType != "") $where .= " and a.razorpay_payment_status='$invoiceType' " ;
        
        
        if($usersList != "" && $usersList != null){
            $sql = "SELECT a.*,b.firstname,b.lastname,w.CardName,z.short_name as country,cct.city,cct.state FROM `place_order_card` a left join tblsubcards q on a.card_id=q.id left join tbl_cards w on q.card_id = w.id left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where a.newpurchaseID !='' and a.user_id='$usersList' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
        }else{
            
            
            if($isAdmin){
                $sql = "SELECT a.*,b.firstname,b.lastname,w.CardName,z.short_name as country,cct.city,cct.state FROM `place_order_card` a left join tblsubcards q on a.card_id=q.id left join tbl_cards w on q.card_id = w.id left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
            }else{
                
                 if($manage_type == 'County'){
                       // user type County
                       $sql = "SELECT a.*,b.firstname,b.lastname,w.CardName,z.short_name as country,cct.city,cct.state FROM `place_order_card` a left join tblsubcards q on a.card_id=q.id left join tbl_cards w on q.card_id = w.id left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.country = '$county_id' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                       
                   }else if($manage_type == 'State'){
                       // user type State
                        $sql = "SELECT a.*,b.firstname,b.lastname,w.CardName,z.short_name as country,cct.city,cct.state FROM `place_order_card` a left join tblsubcards q on a.card_id=q.id left join tbl_cards w on q.card_id = w.id left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.state = '$state' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                      
                   }else {
                       // user type City
                        $sql = "SELECT a.*,b.firstname,b.lastname,w.CardName,z.short_name as country,cct.city,cct.state FROM `place_order_card` a left join tblsubcards q on a.card_id=q.id left join tbl_cards w on q.card_id = w.id left join tblcontacts b on a.user_id=b.id left join tblclients cct on cct.userid = b.userid $join where cct.city = '$city' and a.newpurchaseID !='' and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ORDER BY a.id desc";
                       
                       
                   }
                
            }
            
            
            
            
            
           
        }
        
        
        //  echo $sql;
       
        $result = $this->dbc->get_rows($sql);
         
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
       
      }
      
      
       function getCardTotalCount(){
          
        $usersList=$_REQUEST["usersList"];
        $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        
        $invoiceType=$_REQUEST["invoiceType"];
        
         $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
        
        $where = '';
        if($invoiceType != "") $where .= " and a.razorpay_payment_status='$invoiceType' " ;
        if($usersList != "") $where .= " and a.user_id='$usersList' " ;
        
        
        if($isAdmin){
       
             $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order_card` a where a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
             
       }else{
           
            if($manage_type == 'County'){
               // user type County
              $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order_card` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.country = '$county_id' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
               
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order_card` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.state = '$state' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";

           }else {
               // user type City
                $sql = "SELECT SUM(a.numberOfItemsPrice) AS sumOfTotal , SUM(a.numberOfItemsDiscount) AS sumOfDiscount , SUM(a.numberOfItemsTotalAmount) AS sumItemTotal FROM `place_order_card` a left join tblcontacts b on a.user_id = b.id left join tblclients cct on cct.userid = b.userid where cct.city = '$city' and a.newpurchaseID !='' and a.razorpay_payment_status =1 and a.created_date >= '$startDate' and a.created_date < '$endDate' $where ";
               
           }
           
       }
        
        
       
        
        
        //  echo $sql;
       
        $result = $this->dbc->get_rows($sql);
         
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
          
      }
	
	
	public function getAllUserCardServices(){
	
		
	
		$cardId = $_REQUEST['cardServicesIds'];
	
		$sql1 = "SELECT * FROM `tbl_card_services` WHERE id IN ($cardId) ";
		

		$AlbumList = $this->dbc->get_rows($sql1);
		

		self::sendResponse("1", $AlbumList);

	

	}
	
		
	public function getCardRequestUserList(){
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       

       if($isAdmin){
           $sql = "SELECT a.*,z.short_name as country,cct.city,cct.state,ccr.status as Rstatus,ccr.id as Rid,ccr.description FROM card_request ccr left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country  ORDER BY ccr.status ASC";
       }else{
           
            if($manage_type == 'County'){
               // user type County
               $sql = "SELECT a.*,z.short_name as country,cct.city,cct.state,ccr.status as Rstatus,ccr.id as Rid,ccr.description FROM card_request ccr left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.country = '$county_id' ORDER BY ccr.status ASC";
              
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT a.*,z.short_name as country,cct.city,cct.state,ccr.status as Rstatus,ccr.id as Rid,ccr.description FROM card_request ccr left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.state = '$state' ORDER BY ccr.status ASC";
             
           }else {
               // user type City
                $sql = "SELECT a.*,z.short_name as country,cct.city,cct.state,ccr.status as Rstatus,ccr.id as Rid,ccr.description FROM card_request ccr left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.city = '$city' ORDER BY ccr.status ASC";
           }
           
       }
       
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		
	}
	
	
	
	public function acceptCardRequest() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE card_request SET `status` = 1 WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM card_request WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$user_id = $planList[0]['user_id'];
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=97 AND `active`=1 ";
		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
			
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email,a.phonenumber FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		$html = str_replace("--phone",$UserList[0]['phonenumber'],$html);
		$html = str_replace("--email",$eventUserEmail,$html);
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." accepted Card request for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully accepted request");
        else self::sendResponse("2", "Failed to accept request");

	}
	
	
	public function declineCardRequest() {
		$id = $_REQUEST['id'];
		$description = $_REQUEST['description'];

		$sql = "UPDATE card_request SET `status` = 2 , `description`='$description' WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM card_request WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$user_id = $planList[0]['user_id'];
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=98 AND `active`=1 ";
		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
			
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email,a.phonenumber FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		$html = str_replace("--phone",$UserList[0]['phonenumber'],$html);
		$html = str_replace("--email",$eventUserEmail,$html);
		
		$html = str_replace("--more_info",$description,$html);
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." declined Card request for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully declined request");
        else self::sendResponse("2", "Failed to decline request");

	}
	
	
	 public function getcardusers(){
        // echo("I am here !!!!!");
		// $id=$_REQUEST["id"];
		
		   $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       $join =" left join place_order_card pp on pp.user_id=a.id ";
       $whr = " AND pp.razorpay_payment_status=1 AND pp.completed=1 ";
       
        if($isAdmin){
            $sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM `tblcontacts` a $join WHERE a.active=1 $whr ORDER BY a.firstname ASC";
       }else{
             if($manage_type == 'County'){
               // user type County
               
               $sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid $join WHERE a.active=1 AND b.country = '$county_id' $whr ORDER BY a.firstname ASC";
               
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid $join WHERE a.active=1 AND b.state = '$state' $whr ORDER BY a.firstname ASC";
              
              
             
           }else {
               // user type City
               $sql = "SELECT DISTINCT a.id, a.firstname, a.lastname FROM `tblcontacts` a left join tblclients b on b.userid = a.userid $join WHERE a.active=1 AND b.city = '$city' $whr ORDER BY a.firstname ASC";
               
               
               
           }
       }
           
		
		
		
		$result = $this->dbc->get_rows($sql);
        // print_r($result);
		// $data=array("users"=>$result);
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
		// if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		// else self::sendResponse("2", "Failed");
	}
	
	
	function getUserCardDetails(){
          
        $user_id=$_REQUEST["selUser"];
       
       
        $sql = "SELECT a.card_id,a.card_number,a.exp_date,c.CardName,a.card_services,a.id as order_id FROM place_order_card a left join tblsubcards b on a.card_id=b.id left join tbl_cards c on b.card_id = c.id WHERE a.razorpay_payment_status=1 AND a.completed=1 AND a.isNew=1 AND a.user_id='$user_id' ";
             
        $result = $this->dbc->get_rows($sql);
         
        if($result != ""){
            $order_id = $result[0]['order_id'];
            $userServ = $result[0]['card_services'];
            $sql4 ="SELECT service_id FROM `card_service_used` WHERE user_id='$user_id' and active=0 and order_id='$order_id' ";
            $result4 = $this->dbc->get_rows($sql4);
            

            foreach ($result4 as $value) {
                $elementToRemove = $value['service_id'];
                
                $userServ = str_replace($elementToRemove, "", strval($userServ));
                

            }
            
            $userServ = trim($userServ, ',');
            $userServ = str_replace(",,", ",", $userServ);
            if($userServ == '') $result3 = [];
            else{
                $sql3 ="SELECT CardService , id FROM `tbl_card_services` WHERE id IN ($userServ)";
                $result3 = $this->dbc->get_rows($sql3);
            }
       
            
          
            $final = array(
                "card" => $result,
                "service" => $result3,
            );
            
            
            self::sendResponse("1", $final);
            
        }
        else self::sendResponse("2", "No data found");
          
      }
      
      
      function getUserCardDetailsNew(){
          
        $order_id=$_REQUEST["order_id"];
        $user_id=$_REQUEST["selUser"];
       
       
        $sql = "SELECT a.card_id,a.card_number,a.exp_date,c.CardName,a.card_services,a.id as order_id FROM place_order_card a left join tblsubcards b on a.card_id=b.id left join tbl_cards c on b.card_id = c.id WHERE a.razorpay_payment_status=1 AND a.completed=1 AND a.isNew=1 AND a.id='$order_id' ";
             
     
        $result = $this->dbc->get_rows($sql);
         
        if($result != ""){
            $order_id = $result[0]['order_id'];
            $userServ = $result[0]['card_services'];
            $sql4 ="SELECT service_id FROM `card_service_used` WHERE user_id='$user_id' and active=0 and order_id='$order_id' ";
            $result4 = $this->dbc->get_rows($sql4);

            foreach ($result4 as $value) {
                $elementToRemove = $value['service_id'];
                
                $userServ = str_replace($elementToRemove, "", strval($userServ));
                

            }
            
            $userServ = trim($userServ, ',');
            $userServ = str_replace(",,", ",", $userServ);
            if($userServ == '') $result3 = [];
            else{
                $sql3 ="SELECT CardService , id FROM `tbl_card_services` WHERE id IN ($userServ)";
                $result3 = $this->dbc->get_rows($sql3);
            }
       
            
          
            $final = array(
                "card" => $result[0],
                "service" => $result3,
            );
            
            
            self::sendResponse("1", $final);
            
        }
        else self::sendResponse("2", "No data found");
          
      }
      
      
      public function createServiceUsed(){
	   
	
        
        $user_id = $_REQUEST['user_id'];
        $card_id = $_REQUEST['card_id'];
        $service_id = $_REQUEST['service_id'];
        $description = $_REQUEST['description'];
        $order_id = $_REQUEST['order_id'];
        
        $data=array();
		$data["user_id"] = $user_id;
		$data["card_id"] = $card_id;
		$data["service_id"] = $service_id;
		$data["description"] = $description;
		$data["order_id"] = $order_id;
		
		
		$result = $this->dbc->insert_query($data, 'card_service_used');
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=99 AND `active`=1 ";
		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
			
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email,a.phonenumber FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		$sqlFullD = "SELECT a.card_number,a.exp_date,c.CardName FROM place_order_card a left join tblsubcards b on b.id=a.card_id left join tbl_cards c on c.id=b.card_id WHERE a.id='$order_id' ";
		$resultFullD = $this->dbc->get_rows($sqlFullD);
		
		$currentDate = date("Y-m-d");
		
		$sqlService = "SELECT CardService FROM tbl_card_services WHERE id='$service_id' ";
		$resultService = $this->dbc->get_rows($sqlService);
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--card_number",$resultFullD[0]['card_number'],$html);
		$html = str_replace("--exp_date",$resultFullD[0]['exp_date'],$html);
		$html = str_replace("--card_name",$resultFullD[0]['CardName'],$html);
		$html = str_replace("--used_service",$resultService[0]['CardService'],$html);
		$html = str_replace("--service_description",$description,$html);
		$html = str_replace("--used_date",$currentDate,$html);
		
	
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
			$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." is create record for use service ".$resultService[0]['CardService']." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		
		
	
		if($result != ""){
		   
		    self::sendResponse("1", "Record created");
		    
		}else self::sendResponse("0", "Failed to created record");

	

	}
	
	
	public function getUserServiceUsedData(){
	    
	    $user_id = $_REQUEST['user_id'];
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       $join =" left join place_order_card poc on poc.id=ccr.order_id left join tblsubcards tsc on tsc.id=poc.card_id left join tbl_cards tc on tc.id=tsc.card_id left join tbl_card_services tcs on tcs.id=ccr.service_id ";
       

       if($isAdmin){
           
           $sql = "SELECT ccr.*,z.short_name as country,cct.city,cct.state,tc.CardName,poc.card_number,tcs.CardService FROM card_service_used ccr $join left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where a.id='$user_id'  ORDER BY ccr.id desc";
           
        
       }else{
           
            if($manage_type == 'County'){
               // user type County
               $sql = "SELECT ccr.*,z.short_name as country,cct.city,cct.state,tc.CardName,poc.card_number,tcs.CardService FROM card_service_used ccr $join left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.country = '$county_id' and a.id='$user_id' ORDER BY ccr.status desc";
              
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT ccr.*,z.short_name as country,cct.city,cct.state,tc.CardName,poc.card_number,tcs.CardService FROM card_service_used ccr $join left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.state = '$state' and a.id='$user_id' ORDER BY ccr.status desc";
             
           }else {
               // user type City
                $sql = "SELECT ccr.*,z.short_name as country,cct.city,cct.state,tc.CardName,poc.card_number,tcs.CardService FROM card_service_used ccr $join left join tblcontacts a on ccr.user_id = a.id left join tblclients cct on cct.userid = a.userid left join tblcountries z on z.country_id = cct.country where cct.city = '$city' and a.id='$user_id' ORDER BY ccr.status desc";
           }
           
       }
       
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
		
	}
	
	
	public function getAllUserActiveCardServices(){
	
		
	
		$cardId = $_REQUEST['cardServicesIds'];
		$order_id=$_REQUEST['order_id'];
	
		$sql1 = "SELECT * FROM `tbl_card_services` WHERE id IN ($cardId) ";
		$AlbumList = $this->dbc->get_rows($sql1);
		
		$sql4 ="SELECT * FROM `card_service_used` WHERE active=0 and order_id='$order_id' ";
        $result4 = $this->dbc->get_rows($sql4);
        
         $final = array(
            "service_used" => $result4,
            "service" => $AlbumList,
        );
		

		self::sendResponse("1", $final);

	

	}
	
	
	public function getAllUserActiveCardServicesUsingOrder(){
	
		
	
		$order_id=$_REQUEST['order_id'];
	
		$sql41 ="SELECT * FROM `place_order_card` WHERE id='$order_id' ";
		$result41 = $this->dbc->get_rows($sql41);
		$cardId = $result41[0]['card_services'];
		
		
		$sql4 ="SELECT * FROM `card_service_used` WHERE active=0 and order_id='$order_id' ";
        $result4 = $this->dbc->get_rows($sql4);
        
        
        $sql1 = "SELECT * FROM `tbl_card_services` WHERE id IN ($cardId) ";
		$AlbumList = $this->dbc->get_rows($sql1);
        
         $final = array(
            "service_used" => $result4,
            "service" => $AlbumList,
        );
		

		self::sendResponse("1", $final);

	

	}
	
	
	public function saveUserCard(){
        
        $data=array();
        $data["card_name"]=$_REQUEST['inpCardName'];
        $data["exp"]=$_REQUEST['inpExp'];
        $description = str_replace("'", '"', $_REQUEST['cardDetails']);
        $data["description"]=$description;
        $data["number_of_service"]=$_REQUEST['inpNumberOfServices'];
        
        $data["amount"]=$_REQUEST['inpAamount'];
        $data["discout"]=$_REQUEST['inpDamount'];
        $data["discout_type"]=$_REQUEST['selDiscoutType'];
        $data["exp_amount"]=$_REQUEST['inpExpiredAamount'];
        $data["exp_discout"]=$_REQUEST['inpExpiredDamount'];
        $data["exp_discout_type"]=$_REQUEST['selExpiredDiscoutType'];
        
        $data["guestuser_additional_amt"]=$_REQUEST['inpAdditionalamount'];
        $data["guestuser_additional_amt_type"]=$_REQUEST['inpAdditionalamountType'];
        
        $data["CardPurchase"]=$_REQUEST['CardPurchase'];
        $data["CardPurchaseType"]=$_REQUEST['CardPurchaseType'];
        
        
         if(isset($_FILES['uploadImg']['name']) && $_FILES['uploadImg']['name']!=''){
            $target_1 = 'cards/img_'.time().$_FILES['uploadImg']['name'];
            move_uploaded_file($_FILES['uploadImg']['tmp_name'], $target_1);
            $data['image']=$target_1;
        }
        
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
       
     
		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Card ".$_REQUEST['inpCardName']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tbluser_cards');

		}else{
		    
		    $activityMeg = "Card ".$_REQUEST['inpCardName']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tbluser_cards', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getUserCardsListData(){
	    
	   $sql = "SELECT a.* FROM tbluser_cards a  ";

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		
	public function geteditUserCardList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbluser_cards
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function setsetactiveevUserCard(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tbluser_cards` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Card");
        else self::sendResponse("2", "Failed to ".$dis." Card");
	
	}
	
	public function getCardServicesList(){
	
		
	
		$cardId = $_REQUEST['cardId'];
	
		$sql1 = "SELECT * FROM `tbluser_cards` WHERE `id`=$cardId ";
	
		$AlbumList = $this->dbc->get_rows($sql1);
		

		self::sendResponse("1", $AlbumList);

	

	}
	
	
	public function userCardPlaceOrderNow(){
	   
		$purchaseNowId = $_REQUEST['purchaseNowId'];
		$purchaseCardType = $_REQUEST['purchaseCardType'];
		
		
		$projIdString = str_rot13($purchaseNowId);
        $projIdString = base64_decode($projIdString);
        
        $arr = explode('_', $projIdString);
        $purchaseID = $arr[1];
        
        $user_id = $_REQUEST['user_id'];
        
        $totalItemPrice = $_REQUEST['totalItemPrice'];
        $ItemDiscount = $_REQUEST['ItemDiscount'];
        $ItemTotalAmount = $_REQUEST['ItemTotalAmount'];
        $Itemsave = $_REQUEST['Itemsave'];
        
        $exp = $_REQUEST['exp'];
        $purchaseCardNumber = $_REQUEST['CN'];
        if($purchaseCardNumber == '') {
            $randomLNumber = mt_rand(10000, 99999);
            $currentYear = date('Y');
            $randomNumber = '0017777'.$currentYear.''.$randomLNumber;
        }
        else $randomNumber = $purchaseCardNumber;
        
        $today = date("Y-m-d");

        // Calculate the date after one year
        $afterExpYear = date("Y-m-d", strtotime($today . " +$exp year"));
        
		
        $data=array();
        
    	$data["card_id"]=$purchaseID;
    	$data["numberOfItemsPrice"]=$totalItemPrice;
		$data["numberOfItemsDiscount"]=$ItemDiscount;
		$data["numberOfItemsTotalAmount"]=$ItemTotalAmount;
		$data["numberOfItemssave"] = $Itemsave;

		$data["user_id"] = $user_id;
		
		$data["card_number"] = $randomNumber;
		$data["exp_date"] = $afterExpYear;
		
		$data["card_type"] = $purchaseCardType;
		$data["num_services"] = $_REQUEST['CardServices'];
		$data["isSte"] = $_REQUEST['isSte'];
		
		$result = $this->dbc->insert_query($data, 'place_order_usercard');
	
	
		if($result != ""){
		    $timestamp = time();
		    $id = $result['InsertId'];
		    $decodeId = base64_encode($timestamp . "_".$id);
		    $decodeId = str_rot13($decodeId);
		    
		    self::sendResponse("1", $decodeId);
		    
		}else self::sendResponse("0", "Failed to place order");

	

	}
	
	
	public function updateMifutoUserCardServicePayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
		
		$ItemsTotalPrice = $_REQUEST['ItemsTotalPrice'];
		$TotalSave = $_REQUEST['TotalSave'];
		$couponApply = $_REQUEST['couponApply'];
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
		$otp = rand(100000, 999999);
		

		if($razorpay_payment_status == 1){
		    $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    		$cardData1 = $this->dbc->get_rows($psql);
    		
    		$user_id = $cardData1[0]['user_id'];
    		$decodedKey = $cardData1[0]['inpServiceID'];
    		
    	
    		
    		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=108 AND `active`=1 ";
    	
    		
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		    //send mail here
    		$subject = $mailTemplate[0]['subject'];
    		

    		$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
    		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
		    $UserList = $this->dbc->get_rows($sqlU);
		    
		    $eventUser = $UserList[0]['name'];
		    $eventUserEmail = $UserList[0]['email'];
		    
		    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
    		$cardData = $this->dbc->get_rows($psql1);
    		
		    $today = date("Y-m-d");
		    
		    $html = str_replace("--username",$eventUser,$html);
		    $html = str_replace("--service_name",$cardData[0]['name'],$html);
		    $html = str_replace("--provider_name",$cardData[0]['company_name'],$html);
		    $html = str_replace("--provider_phone",$cardData[0]['company_phone'],$html);
		    $html = str_replace("--provider_email",$cardData[0]['company_mail'],$html);
		    $html = str_replace("--provider_address",$cardData[0]['company_address'],$html);
		    $html = str_replace("--provider_website",$cardData[0]['company_link'],$html);
		    $html = str_replace("--provider_country",$cardData[0]['county_id'],$html);
		    $html = str_replace("--provider_state",$cardData[0]['state_id'],$html);
		    $html = str_replace("--provider_city",$cardData[0]['city_id'],$html);
		    $html = str_replace("--description",$cardData[0]['description'],$html);
		    
		    $priceDetails='<p>Thank you for considering our services. Here are the details regarding pricing and payment:<br><b>Payment Structure:</b> <br>A 50% advance payment is required to confirm your booking.The remaining balance is due on the day of the photo shoot.<br><b>Payment Methods:</b><br>All payments must be made online through your Mifuto account.We do not accept cash payments.<br><b>Tipping Policy:</b><br>Please do not provide tips to our photographers.We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.</p>';
		    
		    $deliverables = '<p>Dear <b>'.$eventUser.',</b><br>Thank you for choosing our services <b>'.$cardData[0]['name'].'</b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>2 Photo Frames<br>1 Calendar<br>We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>Best regards,</p>';
		    
		    $amenities = '';
		    if($cardData[0]['provide_wifi'] == 1) $amenities .= '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
            if($cardData[0]['provide_parking'] == 1) $amenities .= '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
            if($cardData[0]['provide_ac'] == 1) $amenities .= '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
            if($cardData[0]['provide_rooftop'] == 1) $amenities .= '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
            if($cardData[0]['provide_bathroom'] == 1) $amenities .= '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
            
             if($cardData[0]['provide_welcome_drink'] == 1) $amenities .= '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
            if($cardData[0]['provide_food'] == 1) $amenities .= '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
            if($cardData[0]['provide_seperate_cabin'] == 1) $amenities .= '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
            if($cardData[0]['provide_common_restaurant'] == 1) $amenities .= '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
		    
		    $html = str_replace("--deliverables",$deliverables,$html);
		    $html = str_replace("--price_details",$priceDetails,$html);
		    $html = str_replace("--amenities",$amenities,$html);
		    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
		    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
		    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
		    $html = str_replace("--max_peoples",$cardData[0]['number_of_members'],$html);
		    $html = str_replace("--extra_peoples",$cardData1[0]['inpExtraPeople'],$html);
		    $html = str_replace("--num_photographer",$cardData1[0]['inpNumPhotographer'],$html);
		    $html = str_replace("--num_videographer",$cardData1[0]['inpNumVediographer'],$html);
		    $html = str_replace("--max_time",$cardData1[0]['mins_time_interval'].'mins',$html);
		    $html = str_replace("--extra_time",$cardData1[0]['inpExtraTime'].'mins',$html);
		    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
		    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
		    
		    $inpSelCard = $cardData1[0]['inpSelCard'];
		    $cardDetails = '';
		    if($inpSelCard != "" && $inpSelCard != 0){
		        $sql3 = "SELECT * FROM place_order_usercard WHERE id=$inpSelCard ";
		        $usercardData = $this->dbc->get_rows($sql3);
		        $cardDetails = 'Card number : '.$usercardData[0]['card_number'].' <br> Card holder name : '.$eventUser.' <br> Expiry : '.$usercardData[0]['exp_date'].' ';
		        
		    }
		    
		    $html = str_replace("--card_details",$cardDetails,$html);
		    
		    $html = str_replace("--photographer_price",( floatval($cardData1[0]['inpPhotographerPrice'])*floatval($cardData1[0]['inpNumPhotographer']) ),$html);
		    $html = str_replace("--vediographer_price",( floatval($cardData1[0]['inpVediographerPrice'])*floatval($cardData1[0]['inpNumVediographer']) ),$html);
		    $html = str_replace("--extra_head_price",$cardData1[0]['inpExtraPeoplePrice'],$html);
		    $html = str_replace("--coupon_discount",$cardData1[0]['couponApplyDiscount'],$html);
		    $html = str_replace("--total_cost",$cardData1[0]['inpTotalCost'],$html);
		    $html = str_replace("--paid_cost",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		    $html = str_replace("--save_price",$cardData1[0]['numberOfItemssave'],$html);
		    $html = str_replace("--book_datetime",$today,$html);
		    $html = str_replace("--otp",$otp,$html);
		
		 

    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		    
    		
		}
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
		$sql = "UPDATE place_order_userservices SET `newpurchaseID` = '$newpurchaseID' , razorpay_payment_id = '$razorpay_payment_id' ,razorpay_payment_status = '$razorpay_payment_status',razorpay_signature='razorpay_signature', completed=1, invoice_snt=0, `IGST`='$IGST', `CGST`='$CGST', `SGST`='$SGST',`otp`='$otp'  WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    $this->sendMifutoUserCardServiceInvoice($purchaseID,$user_id);
	    
	    self::sendResponse("1", $decodeId);
		


	}
	
	public function sendMifutoUserCardServiceInvoice($purchaseID,$user_id){
	
	
		
		$psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    	$cardData1 = $this->dbc->get_rows($psql);
    		
		$user_id = $cardData1[0]['user_id'];
		$decodedKey = $cardData1[0]['inpServiceID'];
    		
    	
    		
    	$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=109 AND `active`=1 ";
    	
    		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
    		

    	$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
	    $UserList = $this->dbc->get_rows($sqlU);
		    
	    $eventUser = $UserList[0]['name'];
	    $eventUserEmail = $UserList[0]['email'];
		    
	    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
		$cardData = $this->dbc->get_rows($psql1);
    		
		$today = date("Y-m-d");
		
		$html = str_replace("--invoice_no",$cardData1[0]['newpurchaseID'],$html);
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--invoice_date",$today,$html);
		
		$html = str_replace("--user_address",$UserList[0]['address'],$html);
		$html = str_replace("--user_city",$UserList[0]['city'],$html);
		$html = str_replace("--user_state",$UserList[0]['state'],$html);
		$html = str_replace("--user_country",$UserList[0]['short_name'],$html);
		$html = str_replace("--user_zip",$UserList[0]['zip'],$html);
		
		
		$html = str_replace("--IGST",$cardData1[0]['IGST'],$html);
		$html = str_replace("--CGST",$cardData1[0]['CGST'],$html);
		$html = str_replace("--SGST",$cardData1[0]['SGST'],$html);
		$html = str_replace("--taxable_value",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		
		$html = str_replace("--service_name",$cardData[0]['name'],$html);
	    $html = str_replace("--provider_name",$cardData[0]['company_name'],$html);
	    $html = str_replace("--provider_phone",$cardData[0]['company_phone'],$html);
	    $html = str_replace("--provider_email",$cardData[0]['company_mail'],$html);
	    $html = str_replace("--provider_address",$cardData[0]['company_address'],$html);
	    $html = str_replace("--provider_website",$cardData[0]['company_link'],$html);
	    $html = str_replace("--provider_country",$cardData[0]['county_id'],$html);
	    $html = str_replace("--provider_state",$cardData[0]['state_id'],$html);
	    $html = str_replace("--provider_city",$cardData[0]['city_id'],$html);
	    $html = str_replace("--description",$cardData[0]['description'],$html);
		
		    
	    $priceDetails='<p>Thank you for considering our services. Here are the details regarding pricing and payment:<br><b>Payment Structure:</b> <br>A 50% advance payment is required to confirm your booking.The remaining balance is due on the day of the photo shoot.<br><b>Payment Methods:</b><br>All payments must be made online through your Mifuto account.We do not accept cash payments.<br><b>Tipping Policy:</b><br>Please do not provide tips to our photographers.We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.</p>';
	    
	    $deliverables = '<p>Dear <b>'.$eventUser.',</b><br>Thank you for choosing our services <b>'.$cardData[0]['name'].'</b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>2 Photo Frames<br>1 Calendar<br>We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>Best regards,</p>';
	    
	    $amenities = '';
	    if($cardData[0]['provide_wifi'] == 1) $amenities .= '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
        if($cardData[0]['provide_parking'] == 1) $amenities .= '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
        if($cardData[0]['provide_ac'] == 1) $amenities .= '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
        if($cardData[0]['provide_rooftop'] == 1) $amenities .= '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
        if($cardData[0]['provide_bathroom'] == 1) $amenities .= '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
        
         if($cardData[0]['provide_welcome_drink'] == 1) $amenities .= '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
        if($cardData[0]['provide_food'] == 1) $amenities .= '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
        if($cardData[0]['provide_seperate_cabin'] == 1) $amenities .= '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
        if($cardData[0]['provide_common_restaurant'] == 1) $amenities .= '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
	    
	    $html = str_replace("--deliverables",$deliverables,$html);
	    $html = str_replace("--price_details",$priceDetails,$html);
	    $html = str_replace("--amenities",$amenities,$html);
	    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
	    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
	    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
	    $html = str_replace("--max_peoples",$cardData[0]['number_of_members'],$html);
	    $html = str_replace("--extra_peoples",$cardData1[0]['inpExtraPeople'],$html);
	    $html = str_replace("--num_photographer",$cardData1[0]['inpNumPhotographer'],$html);
	    $html = str_replace("--num_videographer",$cardData1[0]['inpNumVediographer'],$html);
	    $html = str_replace("--max_time",$cardData1[0]['mins_time_interval'].'mins',$html);
	    $html = str_replace("--extra_time",$cardData1[0]['inpExtraTime'].'mins',$html);
	    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
	    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
	    
	    $inpSelCard = $cardData1[0]['inpSelCard'];
	    $cardDetails = '';
	    if($inpSelCard != "" && $inpSelCard != 0){
	        $sql3 = "SELECT * FROM place_order_usercard WHERE id=$inpSelCard ";
	        $usercardData = $this->dbc->get_rows($sql3);
	        $cardDetails = 'Card number : '.$usercardData[0]['card_number'].' <br> Card holder name : '.$eventUser.' <br> Expiry : '.$usercardData[0]['exp_date'].' ';
	        
	    }
	    
	    $html = str_replace("--card_details",$cardDetails,$html);
	    
	    $html = str_replace("--photographer_price",( floatval($cardData1[0]['inpPhotographerPrice'])*floatval($cardData1[0]['inpNumPhotographer']) ),$html);
	    $html = str_replace("--vediographer_price",( floatval($cardData1[0]['inpVediographerPrice'])*floatval($cardData1[0]['inpNumVediographer']) ),$html);
	    $html = str_replace("--extra_head_price",$cardData1[0]['inpExtraPeoplePrice'],$html);
	    $html = str_replace("--coupon_discount",$cardData1[0]['couponApplyDiscount'],$html);
	    $html = str_replace("--total_cost",$cardData1[0]['inpTotalCost'],$html);
	    $html = str_replace("--paid_cost",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--save_price",$cardData1[0]['numberOfItemssave'],$html);
		$html = str_replace("--sub_total",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		
		$decimalValue = $cardData1[0]['numberOfItemsTotalAmount']; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order_userservices SET invoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
// 		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	
	
	public function updateMifutoUserCardPayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
		
		$ItemsTotalPrice = $_REQUEST['ItemsTotalPrice'];
		$TotalSave = $_REQUEST['TotalSave'];
		$couponApply = $_REQUEST['couponApply'];
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		

		if($razorpay_payment_status == 1){
		    $psql = "SELECT * FROM place_order_usercard WHERE id = $purchaseID ";
    		$cardData = $this->dbc->get_rows($psql);
    		
    		$card_id = $cardData[0]['card_id'];
    		$user_id = $cardData[0]['user_id'];

    		$sql6 = "UPDATE place_order_usercard SET isNew=0 WHERE user_id='$user_id' ";
    		$result6 = $this->dbc->update_row($sql6);
    		
    		$card_type = $cardData[0]['card_type'];
    		
    		if($card_type == 2){
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=93 AND `active`=1 ";
    		}else{
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=92 AND `active`=1 ";
    		}
    		
    		
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		    //send mail here
    		$subject = $mailTemplate[0]['subject'];
    		

    		$html = $mailTemplate[0]['mail_body'];
    		
    		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a left join place_order_usercard b on a.id = b.user_id LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
		    $UserList = $this->dbc->get_rows($sqlU);
		    
		    $eventUser = $UserList[0]['name'];
		    $eventUserEmail = $UserList[0]['email'];
		    
		    $today = date("Y-m-d");
		    
		    $html = str_replace("--username",$eventUser,$html);
		    $html = str_replace("--card_number",$cardData[0]['card_number'],$html);
		    $html = str_replace("--exp_date",$cardData[0]['exp_date'],$html);
		    $html = str_replace("--purchase_date",$today,$html);
		    
		    
		    $sqlqwe = "SELECT a.* FROM tbluser_cards a WHERE a.id = $card_id  ";
		    $cardDataqwe = $this->dbc->get_rows($sqlqwe);
    		
    		$CardName = $cardDataqwe[0]['card_name'];
    		$Cardexp = $cardDataqwe[0]['exp'];
    		$Carddescription = $cardDataqwe[0]['description'];
		    
		    $html = str_replace("--card_validity",$Cardexp,$html);
		    $html = str_replace("--card_benfits",$Carddescription,$html);
		    $html = str_replace("--card_name",$CardName,$html);
		    
		    $serhtml = 'You have use '.$cardData[0]['num_services'].' services in this card';
		    
		    
		    $html = str_replace("--services",$serhtml,$html);
		    
    		
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		    
		    $this->sendMifutoUserCardInvoice($purchaseID,$user_id);
    		
    		
    		
		}
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
		$sql = "UPDATE place_order_usercard SET `newpurchaseID` = '$newpurchaseID' , razorpay_payment_id = '$razorpay_payment_id' ,razorpay_payment_status = '$razorpay_payment_status',razorpay_signature='razorpay_signature', completed=1, isNew=1, invoice_snt=0, `numberOfItemsTotalAmount`='$ItemsTotalPrice',`numberOfItemssave`='$TotalSave',`couponApplyDiscount`='$couponApply' , `IGST`='$IGST', `CGST`='$CGST', `SGST`='$SGST'  WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    self::sendResponse("1", $decodeId);
		


	}
	
	public function sendMifutoUserCardInvoice($purchaseID,$user_id){
	
	
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=10 AND mail_template=91 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a left join place_order_usercard b on a.id = b.user_id LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['name'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		
		$sql1 = "SELECT * FROM place_order_usercard WHERE id=$purchaseID ";
		$AlbumList = $this->dbc->get_rows($sql1);
		
		$dateTime = new DateTime($AlbumList[0]['created_date']);
        $dateInv = $dateTime->format("Y-m-d");
		
		$html = str_replace("--invoice_no",$AlbumList[0]['newpurchaseID'],$html);
		$html = str_replace("--invoice_date",$dateInv,$html);
		$html = str_replace("--sub_total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amt_total_paid",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amount_due",0,$html);
		$html = str_replace("--price",$AlbumList[0]['numberOfItemsPrice'],$html);
		$html = str_replace("--discount",$AlbumList[0]['numberOfItemsDiscount'],$html);
		$html = str_replace("--no_items",$AlbumList[0]['numberOfItems'],$html);
		$html = str_replace("--service_charge",0,$html);
		$html = str_replace("--coupon",$AlbumList[0]['couponApplyDiscount'],$html);
		$html = str_replace("--save_amt",$AlbumList[0]['numberOfItemssave'],$html);
		
		
		
		
		$itm = '<table width="100%" border="1" >';
		
		$itm .='<tr>';
		$itm .='<th>#</th>';
		$itm .='<th>Item</th>';
		$itm .='<th>Year</th>';
		$itm .='<th>Expiry Date</th>';
		$itm .='<th>Price</th>';
		$itm .='<th>Discount</th>';
		$itm .='<th>Coupon</th>';
		$itm .='<th>Total</th>';
		$itm .='</tr>';
		
		$card_id = $AlbumList[0]['card_id'];
		$sqlcard = "SELECT a.*  FROM tbluser_cards a WHERE a.id='$card_id' ";
		$cardList = $this->dbc->get_rows($sqlcard);
		
		
		$itm .='<tr>';
		$itm .='<td>1</td>';
		$itm .='<td>'.$cardList[0]['card_name'].' card</td>';
		$itm .='<td>'.$cardList[0]['exp'].'</td>';
		$itm .='<td>'.$AlbumList[0]['exp_date'].'</td>';
	
		
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsPrice'].'</td>';
	
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsDiscount'].'</td>';
		$itm .='<td>₹'.$AlbumList[0]['couponApplyDiscount'].'</td>';
	
		$itm .='<th>₹'.$AlbumList[0]['numberOfItemsTotalAmount'].'</th>';
		$itm .='</tr>';
		
		
		$itm .='</table>';
		
		
		$decimalValue = $AlbumList[0]['numberOfItemsTotalAmount']; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		
		$html = str_replace("--items",$itm,$html);
		
		
			
		$CGST = $AlbumList[0]['CGST'];
		$SGST = $AlbumList[0]['SGST'];
		$IGST = $AlbumList[0]['IGST'];
	
        if($AlbumList[0]['isSte'] == 1){
            $html = str_replace("--IGST",0,$html);
            $html = str_replace("--CGST",$CGST,$html);
            $html = str_replace("--SGST",$SGST,$html);
            
            $Taxablevalue = number_format( (floatval($AlbumList[0]['numberOfItemsTotalAmount']) - ( $CGST + $SGST ) ), 2 );
            
            
        }else{
            $html = str_replace("--IGST",$IGST,$html);
            $html = str_replace("--CGST",0,$html);
            $html = str_replace("--SGST",0,$html);
            
            $Taxablevalue = number_format( (floatval($AlbumList[0]['numberOfItemsTotalAmount']) - ( $IGST ) ), 2 );
            
            
        }
        
        
        $html = str_replace("--taxable_value",$Taxablevalue,$html);
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order_usercard SET invoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
// 		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	
	public function updateUserCardPayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
		
		$ItemsTotalPrice = $_REQUEST['ItemsTotalPrice'];
		$TotalSave = $_REQUEST['TotalSave'];
		$couponApply = $_REQUEST['couponApply'];
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		

		if($razorpay_payment_status == 1){
		    $psql = "SELECT * FROM place_order_usercard WHERE id = $purchaseID ";
    		$cardData = $this->dbc->get_rows($psql);
    		
    		$card_id = $cardData[0]['card_id'];
    		$user_id = $cardData[0]['user_id'];

    		$sql6 = "UPDATE place_order_usercard SET isNew=0 WHERE user_id='$user_id' ";
    		$result6 = $this->dbc->update_row($sql6);
    		
    		$card_type = $cardData[0]['card_type'];
    		
    		if($card_type == 2){
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=93 AND `active`=1 ";
    		}else{
    		    $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=13 AND mail_template=92 AND `active`=1 ";
    		}
    		
    		
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		    //send mail here
    		$subject = $mailTemplate[0]['subject'];
    		

    		$html = $mailTemplate[0]['mail_body'];
    		
    		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		    $UserList = $this->dbc->get_rows($sqlU);
		    
		    $eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		    $eventUserEmail = $UserList[0]['email'];
		    
		    $today = date("Y-m-d");
		    
		    $html = str_replace("--username",$eventUser,$html);
		    $html = str_replace("--card_number",$cardData[0]['card_number'],$html);
		    $html = str_replace("--exp_date",$cardData[0]['exp_date'],$html);
		    $html = str_replace("--purchase_date",$today,$html);
		    
		    
		    $sqlqwe = "SELECT a.* FROM tbluser_cards a WHERE a.id = $card_id  ";
		    $cardDataqwe = $this->dbc->get_rows($sqlqwe);
    		
    		$CardName = $cardDataqwe[0]['card_name'];
    		$Cardexp = $cardDataqwe[0]['exp'];
    		$Carddescription = $cardDataqwe[0]['description'];
		    
		    $html = str_replace("--card_validity",$Cardexp,$html);
		    $html = str_replace("--card_benfits",$Carddescription,$html);
		    $html = str_replace("--card_name",$CardName,$html);
		    
		    $serhtml = 'You have use '.$cardData[0]['num_services'].' services in this card';
		    
		    
		    $html = str_replace("--services",$serhtml,$html);
		    
    		
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
    		
    		
    		
		}
		
		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
		$sql = "UPDATE place_order_usercard SET `newpurchaseID` = '$newpurchaseID' , razorpay_payment_id = '$razorpay_payment_id' ,razorpay_payment_status = '$razorpay_payment_status',razorpay_signature='razorpay_signature', completed=1, isNew=1, invoice_snt=0, `numberOfItemsTotalAmount`='$ItemsTotalPrice',`numberOfItemssave`='$TotalSave',`couponApplyDiscount`='$couponApply' , `IGST`='$IGST', `CGST`='$CGST', `SGST`='$SGST'  WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    self::sendResponse("1", $decodeId);
		


	}
	
	
	
	public function sendUserCardInvoice(){
	
		$purchaseID = $_REQUEST['purchaseID'];
		$user_id = $_REQUEST['user_id'];
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=10 AND mail_template=91 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];
	
		$html = $mailTemplate[0]['mail_body'];
		
		$sqlU = "SELECT b.*,c.short_name,a.firstname,a.lastname,a.email FROM tblcontacts a left join tblclients b on a.userid = b.userid left join tblcountries c on b.country = c.country_id WHERE a.id='$user_id' "; 
		$UserList = $this->dbc->get_rows($sqlU);
		
		$eventUser = $UserList[0]['firstname']." ".$UserList[0]['lastname'];
		$eventUserEmail = $UserList[0]['email'];
		
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--address",$UserList[0]['address'],$html);
		$html = str_replace("--city",$UserList[0]['city'],$html);
		$html = str_replace("--state",$UserList[0]['state'],$html);
		$html = str_replace("--country",$UserList[0]['short_name'],$html);
		$html = str_replace("--zip",$UserList[0]['zip'],$html);
		
		
		$sql1 = "SELECT * FROM place_order_usercard WHERE id=$purchaseID ";
		$AlbumList = $this->dbc->get_rows($sql1);
		
		$dateTime = new DateTime($AlbumList[0]['created_date']);
        $dateInv = $dateTime->format("Y-m-d");
		
		$html = str_replace("--invoice_no",$AlbumList[0]['newpurchaseID'],$html);
		$html = str_replace("--invoice_date",$dateInv,$html);
		$html = str_replace("--sub_total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--total",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amt_total_paid",$AlbumList[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--amount_due",0,$html);
		$html = str_replace("--price",$AlbumList[0]['numberOfItemsPrice'],$html);
		$html = str_replace("--discount",$AlbumList[0]['numberOfItemsDiscount'],$html);
		$html = str_replace("--no_items",$AlbumList[0]['numberOfItems'],$html);
		$html = str_replace("--service_charge",0,$html);
		$html = str_replace("--coupon",$AlbumList[0]['couponApplyDiscount'],$html);
		$html = str_replace("--save_amt",$AlbumList[0]['numberOfItemssave'],$html);
		
		
		
		
		$itm = '<table width="100%" border="1" >';
		
		$itm .='<tr>';
		$itm .='<th>#</th>';
		$itm .='<th>Item</th>';
		$itm .='<th>Year</th>';
		$itm .='<th>Expiry Date</th>';
		$itm .='<th>Price</th>';
		$itm .='<th>Discount</th>';
		$itm .='<th>Coupon</th>';
		$itm .='<th>Total</th>';
		$itm .='</tr>';
		
		$card_id = $AlbumList[0]['card_id'];
		$sqlcard = "SELECT a.*  FROM tbluser_cards a WHERE a.id='$card_id' ";
		$cardList = $this->dbc->get_rows($sqlcard);
		
		
		$itm .='<tr>';
		$itm .='<td>1</td>';
		$itm .='<td>'.$cardList[0]['card_name'].' card</td>';
		$itm .='<td>'.$cardList[0]['exp'].'</td>';
		$itm .='<td>'.$AlbumList[0]['exp_date'].'</td>';
	
		
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsPrice'].'</td>';
	
		$itm .='<td>₹'.$AlbumList[0]['numberOfItemsDiscount'].'</td>';
		$itm .='<td>₹'.$AlbumList[0]['couponApplyDiscount'].'</td>';
	
		$itm .='<th>₹'.$AlbumList[0]['numberOfItemsTotalAmount'].'</th>';
		$itm .='</tr>';
		
		
		$itm .='</table>';
		
		
		$decimalValue = $AlbumList[0]['numberOfItemsTotalAmount']; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		
		$html = str_replace("--items",$itm,$html);
		
		
			
		$CGST = $AlbumList[0]['CGST'];
		$SGST = $AlbumList[0]['SGST'];
		$IGST = $AlbumList[0]['IGST'];
	
        if($AlbumList[0]['isSte'] == 1){
            $html = str_replace("--IGST",0,$html);
            $html = str_replace("--CGST",$CGST,$html);
            $html = str_replace("--SGST",$SGST,$html);
            
            $Taxablevalue = number_format( (floatval($AlbumList[0]['numberOfItemsTotalAmount']) - ( $CGST + $SGST ) ), 2 );
            
            
        }else{
            $html = str_replace("--IGST",$IGST,$html);
            $html = str_replace("--CGST",0,$html);
            $html = str_replace("--SGST",0,$html);
            
            $Taxablevalue = number_format( (floatval($AlbumList[0]['numberOfItemsTotalAmount']) - ( $IGST ) ), 2 );
            
            
        }
        
        
        $html = str_replace("--taxable_value",$Taxablevalue,$html);
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order_usercard SET invoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	
	public function saveCardServicesCoupon(){
		$data=array();
		
		if(isset($_REQUEST['CouponCode'])) $data["CouponCode"]=$_REQUEST['CouponCode'];
		if(isset($_REQUEST['CouponsEndDate'])) $data["CouponsEndDate"]=$_REQUEST['CouponsEndDate'];
		if(isset($_REQUEST['CouponsStartDate'])) $data["CouponsStartDate"]=$_REQUEST['CouponsStartDate'];
		if(isset($_REQUEST['DiscountType'])) $data["DiscountType"]=$_REQUEST['DiscountType'];

		if(isset($_REQUEST['CouponDiscount'])) $data["CouponDiscount"]=$_REQUEST['CouponDiscount'];

		// print_r($data);
		// die;
		if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];
		
		$CouponCodeData = $_REQUEST['CouponCode'];
		
		$chkSql = "SELECT id FROM tblcardservicescoupons where `delete`=0 AND CouponCode='$CouponCodeData' AND id !='$id' ";
		$chkList = $this->dbc->get_rows($chkSql);
		
		if(isset($chkList[0])){
		    self::sendResponse("2", "Coupon code already exists.");
		}
		
	
		
		if($id != "") {

			$CouponCode = $_REQUEST['CouponCode'];
			$CouponsEndDate = $_REQUEST['CouponsEndDate'];
			$CouponsStartDate = $_REQUEST['CouponsStartDate'];
			$DiscountType = $_REQUEST['DiscountType'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			
			$sql = "UPDATE tblcardservicescoupons SET `CouponCode` = '$CouponCode' , CouponsEndDate = '$CouponsEndDate' , CouponsStartDate= '$CouponsStartDate' , `DiscountType`='$DiscountType', CouponDiscount='$CouponDiscount' ,updated_on = now() WHERE id = $id ";

// 			echo $sql;

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			if($DiscountType == 1) $activityMeg = $isUsername." update Card services coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." update Card services coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

			$result = $this->dbc->update_row($sql);

			if(isset($result))self::sendResponse("1", "Coupon updated successfull");
			else self::sendResponse("2", "Failed to update coupon");

		} else {

			$CouponCode = $_REQUEST['CouponCode'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			$DiscountType = $_REQUEST['DiscountType'];
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			if($DiscountType == 1) $activityMeg = $isUsername." create new Card services coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." create new Card services coupon ".$CouponCode." with  discount ".$CouponDiscount."%";

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);


			$result = $this->dbc->insert_query($data, 'tblcardservicescoupons');
		}

		if($result != "")self::sendResponse("1", "Successfully add new coupon");
        else self::sendResponse("2", "Failed to add new coupon");

	}
	
	
	public function saveCardCoupon(){
		$data=array();
		
		if(isset($_REQUEST['CouponCode'])) $data["CouponCode"]=$_REQUEST['CouponCode'];
		if(isset($_REQUEST['CouponsEndDate'])) $data["CouponsEndDate"]=$_REQUEST['CouponsEndDate'];
		if(isset($_REQUEST['CouponsStartDate'])) $data["CouponsStartDate"]=$_REQUEST['CouponsStartDate'];
		if(isset($_REQUEST['DiscountType'])) $data["DiscountType"]=$_REQUEST['DiscountType'];

		if(isset($_REQUEST['CouponDiscount'])) $data["CouponDiscount"]=$_REQUEST['CouponDiscount'];

		// print_r($data);
		// die;
		if(isset($_REQUEST['id'])) $id=$_REQUEST['id'];
		
		$CouponCodeData = $_REQUEST['CouponCode'];
		
		$chkSql = "SELECT id FROM tblcardcoupons where `delete`=0 AND CouponCode='$CouponCodeData' AND id !='$id' ";
		$chkList = $this->dbc->get_rows($chkSql);
		
		if(isset($chkList[0])){
		    self::sendResponse("2", "Coupon code already exists.");
		}
		
	
		
		if($id != "") {

			$CouponCode = $_REQUEST['CouponCode'];
			$CouponsEndDate = $_REQUEST['CouponsEndDate'];
			$CouponsStartDate = $_REQUEST['CouponsStartDate'];
			$DiscountType = $_REQUEST['DiscountType'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			
			$sql = "UPDATE tblcardcoupons SET `CouponCode` = '$CouponCode' , CouponsEndDate = '$CouponsEndDate' , CouponsStartDate= '$CouponsStartDate' , `DiscountType`='$DiscountType', CouponDiscount='$CouponDiscount' ,updated_on = now() WHERE id = $id ";

// 			echo $sql;

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			if($DiscountType == 1) $activityMeg = $isUsername." update Card coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." update Card coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);

			$result = $this->dbc->update_row($sql);

			if(isset($result))self::sendResponse("1", "Coupon updated successfull");
			else self::sendResponse("2", "Failed to update coupon");

		} else {

			$CouponCode = $_REQUEST['CouponCode'];
			$CouponDiscount = $_REQUEST['CouponDiscount'];
			$DiscountType = $_REQUEST['DiscountType'];
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			if($DiscountType == 1) $activityMeg = $isUsername." create new Card coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
			else $activityMeg = $isUsername." create new Card coupon ".$CouponCode." with  discount ".$CouponDiscount."%";

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);


			$result = $this->dbc->insert_query($data, 'tblcardcoupons');
		}

		if($result != "")self::sendResponse("1", "Successfully add new coupon");
        else self::sendResponse("2", "Failed to add new coupon");

	}
	
		function getCardServiceCouponDiscount() {
	    
	    
	        $sql = "SELECT * FROM tblcardservicescoupons where `delete`=0 order by id desc ";
	
	        
	   
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	
	
	function getCardCouponDiscount() {
	    
	    
	        $sql = "SELECT * FROM tblcardcoupons where `delete`=0 order by id desc ";
	
	        
	   
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	function getCardServiceCouponOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tblcardservicescoupons WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	function getCardCouponOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tblcardcoupons WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	
	public function deleteCardCoupon() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tblcardcoupons SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tblcardcoupons WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CouponCode = $planList[0]['CouponCode'];
		$CouponDiscount = $planList[0]['CouponDiscount'];
		$DiscountType = $planList[0]['DiscountType'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
		if($DiscountType == 1) $activityMeg = $isUsername." deleted Card coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
		else $activityMeg = $isUsername." deleted Card coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted coupon");
        else self::sendResponse("2", "Failed to deleted the coupon");

	}
	
		public function deleteCardServiceCoupon() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tblcardservicescoupons SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tblcardservicescoupons WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CouponCode = $planList[0]['CouponCode'];
		$CouponDiscount = $planList[0]['CouponDiscount'];
		$DiscountType = $planList[0]['DiscountType'];


		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
		if($DiscountType == 1) $activityMeg = $isUsername." deleted Card service coupon ".$CouponCode." with  discount ₹".$CouponDiscount;
		else $activityMeg = $isUsername." deleted Card service coupon ".$CouponCode." with  discount ".$CouponDiscount."%";
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted coupon");
        else self::sendResponse("2", "Failed to deleted the coupon");

	}
	
	
	public function applyCardCouponcode(){
	
		$Couponcode = $_REQUEST['Couponcode'];
		$sql1 = "SELECT * FROM `tblcardcoupons` WHERE CouponCode='$Couponcode' AND `delete`=0 AND CouponsStartDate <= CURDATE() AND CouponsEndDate >= CURDATE() ";
// 		echo $sql1;
		$result = $this->dbc->get_rows($sql1);
	
	
		self::sendResponse("1", $result);

	

	}
	
	
		public function applyCardServiceCouponcode(){
	
		$Couponcode = $_REQUEST['Couponcode'];
		$sql1 = "SELECT * FROM `tblcardservicescoupons` WHERE CouponCode='$Couponcode' AND `delete`=0 AND CouponsStartDate <= CURDATE() AND CouponsEndDate >= CURDATE() ";
// 		echo $sql1;
		$result = $this->dbc->get_rows($sql1);
	
	
		self::sendResponse("1", $result);

	

	}
	
	
	
	
	public function saveUserCardService(){
		$data=array();
		
		$data["CardService"]=str_replace("'", '"', $_REQUEST['CardService']);
		$CardService = str_replace("'", '"', $_REQUEST['CardService']);
		$id=$_REQUEST['id'];
		
	
		$data["provider_id"]=$_REQUEST['selServiceProvider'];
		$data["service_id"]=$_REQUEST['selService'];
		
		
		$data["actual_amt"]=$_REQUEST['inpAamount'];
		$data["discount_amt"]=$_REQUEST['inpDamount'];
		$data["discount_type"]=$_REQUEST['selDiscoutType'];
		$data["num_of_member"]=$_REQUEST['inpNumberOfMembers'];
		$data["extra_price"]=$_REQUEST['inpExtraPrice'];
		$data["finish_time"]=$_REQUEST['inpFinishTime'];
		$data["finish_time_type"]=$_REQUEST['selFinishTimeType'];
		$data["photographers"]=$_REQUEST['selPhotographer'];
		$data["videographers"]=$_REQUEST['selVideographer'];
		
		
		if($id != "") {

	

			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			$activityMeg = $isUsername." update Card service ".$CardService ;
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);


            $data_id=array(); $data_id["id"]=$_REQUEST['id'];
			$result=$this->dbc->update_query($data, 'tbl_usercard_services', $data_id);



			if(isset($result))self::sendResponse("1", "Card service updated successfull");
			else self::sendResponse("2", "Failed to update card service");

		} else {
		    
			$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
			
			
			$activityMeg = $isUsername." create new Card service ".$CardService;

			$recentActivity = new Dashboard(true);
			
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
			
			$result = $this->dbc->insert_query($data, 'tbl_usercard_services');


		}

		if($result != "")self::sendResponse("1", "Successfully add new card service");
        else self::sendResponse("2", "Failed to add new card service");

	}
	
	
	
	
    function getAlluserCardservicesList() {
        
        
           $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       
       $selServiceProvider=$_REQUEST['selServiceProvider'];
       $where = '';
       if($selServiceProvider != "") $where = " and a.provider_id='$selServiceProvider' ";
	    
	    if($isAdmin){
	        $sql = "SELECT a.*,b.company_name,c.name as service_name FROM tbl_usercard_services a left join tblproviderusercompany b on a.provider_id = b.id left join tblprovider_services c on a.service_id = c.id where a.delete=0 and b.is_add_service=0 and b.active=0 and b.is_accept_company=1 and c.is_accept=1 and c.active=0 $where ORDER BY a.id DESC ";
	    }else{
	        $id= $_SESSION['MachooseAdminUser']['id']; 
	        
	        $sql = "SELECT a.*,b.company_name,c.name as service_name FROM tbl_usercard_services a left join tblproviderusercompany b on a.provider_id = b.id left join tblprovider_services c on a.service_id = c.id where a.delete=0 and b.is_add_service=0 and b.active=0 and b.is_accept_company=1 and c.is_accept=1 and c.active=0 and b.machoose_user_id='$id' $where ORDER BY a.id DESC ";
		    
	     
	    }
	    
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
		
	function getUserCardServiceOne() {
		$id = (int)$_REQUEST["id"];

		$sql = "SELECT * FROM tbl_usercard_services WHERE id=$id";
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No plans found");
	}
	
	
	public function deleteUserCardService() {
		$id = $_REQUEST['id'];

		$sql = "UPDATE tbl_usercard_services SET `delete` = 1 , deleted_on = now() ,updated_on = now() WHERE id = $id ";
		$result = $this->dbc->update_row($sql);

		$sqlf = "SELECT * FROM tbl_usercard_services WHERE id = $id ";
		$planList = $this->dbc->get_rows($sqlf);
		$CardName = $planList[0]['CardService'];
	

		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
		
		
		
	    $activityMeg = $isUsername." deleted Card service ".$CardName;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

		if(isset($result))self::sendResponse("1", "Successfully deleted card service");
        else self::sendResponse("2", "Failed to deleted the card service");

	}
	
	
	
	
	
	public function updateMifutoUserCardServiceCompletePayment(){
	   
		$newpurchaseID = $_REQUEST['newpurchaseID'];
		$purchaseID = $_REQUEST['purchaseID'];
		$razorpay_payment_id = $_REQUEST['razorpay_payment_id'];
		$razorpay_payment_status = $_REQUEST['razorpay_payment_status'];
		$razorpay_signature = $_REQUEST['razorpay_signature'];
	
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
	
		if($razorpay_payment_status == 1){
		    $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    		$cardData1 = $this->dbc->get_rows($psql);
    		
    		$user_id = $cardData1[0]['user_id'];
    		$decodedKey = $cardData1[0]['inpServiceID'];
    		
    	
    		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=113 AND `active`=1 ";
    	
    		
		    $mailTemplate = $this->dbc->get_rows($sqlM);
		    //send mail here
    		$subject = $mailTemplate[0]['subject'];
    		

    		$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
    		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
		    $UserList = $this->dbc->get_rows($sqlU);
		    
		    $eventUser = $UserList[0]['name'];
		    $eventUserEmail = $UserList[0]['email'];
		    
		    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link,a.photographers,a.service_photographer,a.id as companyID FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
    		$cardData = $this->dbc->get_rows($psql1);
    		
    		$photographers = $cardData[0]['photographers'];
    		$service_photographer = $cardData[0]['service_photographer'];
    		
    		$array = explode(",", $photographers);
    		$ast = false;
    		foreach($array as $ph){
    		    if($photographerID == '' ) $photographerID = $ph;
    		    if($ast ) $photographerID = $ph;
    		    if($ph == $service_photographer) $ast = true;
    		    else $ast = false;
    		    
    		}
    		
    		$companyID = $cardData[0]['companyID'];
    		$sqlq = "UPDATE tblproviderusercompany SET `service_photographer` = '$photographerID'  WHERE id = $companyID ";
		    $resultq = $this->dbc->update_row($sqlq);
    		
    	
		    $today = date("Y-m-d");
		    
		    $html = str_replace("--username",$eventUser,$html);
		    $html = str_replace("--service_name",$cardData[0]['name'],$html);
		    $html = str_replace("--provider_name",$cardData[0]['company_name'],$html);
		    $html = str_replace("--provider_phone",$cardData[0]['company_phone'],$html);
		    $html = str_replace("--provider_email",$cardData[0]['company_mail'],$html);
		    $html = str_replace("--provider_address",$cardData[0]['company_address'],$html);
		    $html = str_replace("--provider_website",$cardData[0]['company_link'],$html);
		    $html = str_replace("--provider_country",$cardData[0]['county_id'],$html);
		    $html = str_replace("--provider_state",$cardData[0]['state_id'],$html);
		    $html = str_replace("--provider_city",$cardData[0]['city_id'],$html);
		    $html = str_replace("--description",$cardData[0]['description'],$html);
		    
		    $priceDetails='<p>Thank you for considering our services. Here are the details regarding pricing and payment:<br><b>Payment Structure:</b> <br>A 50% advance payment is required to confirm your booking.The remaining balance is due on the day of the photo shoot.<br><b>Payment Methods:</b><br>All payments must be made online through your Mifuto account.We do not accept cash payments.<br><b>Tipping Policy:</b><br>Please do not provide tips to our photographers.We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.</p>';
		    
		    $deliverables = '<p>Dear <b>'.$eventUser.',</b><br>Thank you for choosing our services <b>'.$cardData[0]['name'].'</b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>2 Photo Frames<br>1 Calendar<br>We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>Best regards,</p>';
		    
		    $amenities = '';
		    if($cardData[0]['provide_wifi'] == 1) $amenities .= '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
            if($cardData[0]['provide_parking'] == 1) $amenities .= '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
            if($cardData[0]['provide_ac'] == 1) $amenities .= '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
            if($cardData[0]['provide_rooftop'] == 1) $amenities .= '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
            if($cardData[0]['provide_bathroom'] == 1) $amenities .= '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
            
             if($cardData[0]['provide_welcome_drink'] == 1) $amenities .= '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
            if($cardData[0]['provide_food'] == 1) $amenities .= '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
            if($cardData[0]['provide_seperate_cabin'] == 1) $amenities .= '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
            if($cardData[0]['provide_common_restaurant'] == 1) $amenities .= '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
		    
		    $html = str_replace("--deliverables",$deliverables,$html);
		    $html = str_replace("--price_details",$priceDetails,$html);
		    $html = str_replace("--amenities",$amenities,$html);
		    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
		    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
		    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
		    $html = str_replace("--max_peoples",$cardData[0]['number_of_members'],$html);
		    $html = str_replace("--extra_peoples",$cardData1[0]['inpExtraPeople'],$html);
		    $html = str_replace("--num_photographer",$cardData1[0]['inpNumPhotographer'],$html);
		    $html = str_replace("--num_videographer",$cardData1[0]['inpNumVediographer'],$html);
		    $html = str_replace("--max_time",$cardData1[0]['mins_time_interval'].'mins',$html);
		    $html = str_replace("--extra_time",$cardData1[0]['inpExtraTime'].'mins',$html);
		    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
		    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
		    
		    $inpSelCard = $cardData1[0]['inpSelCard'];
		    $cardDetails = '';
		    if($inpSelCard != "" && $inpSelCard != 0){
		        $sql3 = "SELECT * FROM place_order_usercard WHERE id=$inpSelCard ";
		        $usercardData = $this->dbc->get_rows($sql3);
		        $cardDetails = 'Card number : '.$usercardData[0]['card_number'].' <br> Card holder name : '.$eventUser.' <br> Expiry : '.$usercardData[0]['exp_date'].' ';
		        
		    }
		    
		    $html = str_replace("--card_details",$cardDetails,$html);
		    
		    $html = str_replace("--photographer_price",( floatval($cardData1[0]['inpPhotographerPrice'])*floatval($cardData1[0]['inpNumPhotographer']) ),$html);
		    $html = str_replace("--vediographer_price",( floatval($cardData1[0]['inpVediographerPrice'])*floatval($cardData1[0]['inpNumVediographer']) ),$html);
		    $html = str_replace("--extra_head_price",$cardData1[0]['inpExtraPeoplePrice'],$html);
		    $html = str_replace("--coupon_discount",$cardData1[0]['couponApplyDiscount'],$html);
		    $html = str_replace("--total_cost",$cardData1[0]['inpTotalCost'],$html);
		    $html = str_replace("--paid_cost",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		    $html = str_replace("--save_price",$cardData1[0]['numberOfItemssave'],$html);
		    $html = str_replace("--mail_sent_datetime",$today,$html);
		    
		    $html = str_replace("--final_extra_price",$cardData1[0]['final_extra_price'],$html);
		    $html = str_replace("--extra_vediographer_price",$cardData1[0]['extra_vediographer_price'],$html);
		    $html = str_replace("--extra_photographer_price",$cardData1[0]['extra_photographer_price'],$html);
		    $html = str_replace("--extra_people_price",$cardData1[0]['extra_people_price'],$html);
		    $html = str_replace("--shoot_time",$cardData1[0]['shoot_time'],$html);
		    $html = str_replace("--additional_time",$cardData1[0]['additional_time'],$html);
		    $html = str_replace("--service_extra_person",$cardData1[0]['service_extra_person'],$html);
		    

    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		    
    		
		}

		
		$IGST = $_REQUEST['IGST'];
		$CGST = $_REQUEST['CGST'];
		$SGST = $_REQUEST['SGST'];
		
		$sql = "UPDATE place_order_userservices SET `CnewpurchaseID` = '$newpurchaseID' , Crazorpay_payment_id = '$razorpay_payment_id' ,Crazorpay_payment_status = '$razorpay_payment_status',Crazorpay_signature='razorpay_signature', service_status=3, Cinvoice_snt=0, `CIGST`='$IGST', `CCGST`='$CGST', `CSGST`='$SGST'  WHERE id = $purchaseID ";
		$result = $this->dbc->update_row($sql);
	
		$timestamp = time();
	    $decodeId = base64_encode($timestamp . "_".$purchaseID);
	    $decodeId = str_rot13($decodeId);
	    
	    $this->sendMifutoUserCardServiceCompleteInvoice($purchaseID,$user_id);
	    
	    self::sendResponse("1", $decodeId);
		


	}
	
	
	public function sendMifutoUserCardServiceCompleteInvoice($purchaseID,$user_id){
	
	
		$psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    	$cardData1 = $this->dbc->get_rows($psql);
    		
		$user_id = $cardData1[0]['user_id'];
		$decodedKey = $cardData1[0]['inpServiceID'];
    		
    	
    		
    	$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=112 AND `active`=1 ";
    	
    		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
    		

    	$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
	    $UserList = $this->dbc->get_rows($sqlU);
		    
	    $eventUser = $UserList[0]['name'];
	    $eventUserEmail = $UserList[0]['email'];
		    
	    $psql1 = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.id = '$decodedKey' ";
		$cardData = $this->dbc->get_rows($psql1);
    		
		$today = date("Y-m-d");
		
		$html = str_replace("--invoice_no",$cardData1[0]['CnewpurchaseID'],$html);
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--invoice_date",$today,$html);
		
		$html = str_replace("--user_address",$UserList[0]['address'],$html);
		$html = str_replace("--user_city",$UserList[0]['city'],$html);
		$html = str_replace("--user_state",$UserList[0]['state'],$html);
		$html = str_replace("--user_country",$UserList[0]['short_name'],$html);
		$html = str_replace("--user_zip",$UserList[0]['zip'],$html);
		
		
		$html = str_replace("--IGST",$cardData1[0]['IGST'],$html);
		$html = str_replace("--CGST",$cardData1[0]['CGST'],$html);
		$html = str_replace("--SGST",$cardData1[0]['SGST'],$html);
		$taxable_value = intval($cardData1[0]['inpTotalCost']) - intval($cardData1[0]['IGST']);
		$html = str_replace("--taxable_value",$taxable_value,$html);
		
		$html = str_replace("--service_name",$cardData[0]['name'],$html);
	    $html = str_replace("--provider_name",$cardData[0]['company_name'],$html);
	    $html = str_replace("--provider_phone",$cardData[0]['company_phone'],$html);
	    $html = str_replace("--provider_email",$cardData[0]['company_mail'],$html);
	    $html = str_replace("--provider_address",$cardData[0]['company_address'],$html);
	    $html = str_replace("--provider_website",$cardData[0]['company_link'],$html);
	    $html = str_replace("--provider_country",$cardData[0]['county_id'],$html);
	    $html = str_replace("--provider_state",$cardData[0]['state_id'],$html);
	    $html = str_replace("--provider_city",$cardData[0]['city_id'],$html);
	    $html = str_replace("--description",$cardData[0]['description'],$html);
		
		    
	    $priceDetails='<p>Thank you for considering our services. Here are the details regarding pricing and payment:<br><b>Payment Structure:</b> <br>A 50% advance payment is required to confirm your booking.The remaining balance is due on the day of the photo shoot.<br><b>Payment Methods:</b><br>All payments must be made online through your Mifuto account.We do not accept cash payments.<br><b>Tipping Policy:</b><br>Please do not provide tips to our photographers.We appreciate your understanding and cooperation. Should you have any questions or need assistance with the payment process, please feel free to contact us.</p>';
	    
	    $deliverables = '<p>Dear <b>'.$eventUser.',</b><br>Thank you for choosing our services <b>'.$cardData[0]['name'].'</b> for your photo shoot. We are pleased to inform you of the following deliverables and their timelines: <br>Digital Photos: All photos will be available online within 2 days of the photo shoot. <br>Physical Products: You will receive the following items within 10-15 days via courier to your address:<br>2 Photo Frames<br>1 Calendar<br>We appreciate your business and look forward to delivering your beautiful photos and products promptly. Should you have any questions, please feel free to contact us.<br>Best regards,</p>';
	    
	    $amenities = '';
	    if($cardData[0]['provide_wifi'] == 1) $amenities .= '<li><i class="fal fa-wifi"></i><span>Free WiFi</span></li>';
        if($cardData[0]['provide_parking'] == 1) $amenities .= '<li><i class="fal fa-parking"></i><span>Parking</span></li>';
        if($cardData[0]['provide_ac'] == 1) $amenities .= '<li><i class="fas fa-cloud-meatball"></i><span>AC</span></li>';
        if($cardData[0]['provide_rooftop'] == 1) $amenities .= '<li><i class="fal fa-hotel"></i><span>Rooftop</span></li>';
        if($cardData[0]['provide_bathroom'] == 1) $amenities .= '<li><i class="fas fa-bath"></i><span>Bathroom</span></li>';
        
         if($cardData[0]['provide_welcome_drink'] == 1) $amenities .= '<li><i class="fas fa-wine-glass-alt"></i><span>Welcome Drink</span></li>';
        if($cardData[0]['provide_food'] == 1) $amenities .= '<li><i class="fas fa-hamburger"></i><span>Food</span></li>';
        if($cardData[0]['provide_seperate_cabin'] == 1) $amenities .= '<li><i class="fas fa-car"></i></i><span>Seperate Cabin</span></li>';
        if($cardData[0]['provide_common_restaurant'] == 1) $amenities .= '<li><i class="fas fa-utensils-alt"></i><span>Common Restaurant</span></li>';
	    
	    $html = str_replace("--deliverables",$deliverables,$html);
	    $html = str_replace("--price_details",$priceDetails,$html);
	    $html = str_replace("--amenities",$amenities,$html);
	    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
	    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
	    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
	    $html = str_replace("--max_peoples",$cardData[0]['number_of_members'],$html);
	    $html = str_replace("--extra_peoples",$cardData1[0]['inpExtraPeople'],$html);
	    $html = str_replace("--num_photographer",$cardData1[0]['inpNumPhotographer'],$html);
	    $html = str_replace("--num_videographer",$cardData1[0]['inpNumVediographer'],$html);
	    $html = str_replace("--max_time",$cardData1[0]['mins_time_interval'].'mins',$html);
	    $html = str_replace("--extra_time",$cardData1[0]['inpExtraTime'].'mins',$html);
	    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
	    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
	    
	    $inpSelCard = $cardData1[0]['inpSelCard'];
	    $cardDetails = '';
	    if($inpSelCard != "" && $inpSelCard != 0){
	        $sql3 = "SELECT * FROM place_order_usercard WHERE id=$inpSelCard ";
	        $usercardData = $this->dbc->get_rows($sql3);
	        $cardDetails = 'Card number : '.$usercardData[0]['card_number'].' <br> Card holder name : '.$eventUser.' <br> Expiry : '.$usercardData[0]['exp_date'].' ';
	        
	    }
	    
	    $html = str_replace("--card_details",$cardDetails,$html);
	    
	    $html = str_replace("--photographer_price",( floatval($cardData1[0]['inpPhotographerPrice'])*floatval($cardData1[0]['inpNumPhotographer']) ),$html);
	    $html = str_replace("--vediographer_price",( floatval($cardData1[0]['inpVediographerPrice'])*floatval($cardData1[0]['inpNumVediographer']) ),$html);
	    $html = str_replace("--extra_head_price",$cardData1[0]['inpExtraPeoplePrice'],$html);
	    $html = str_replace("--coupon_discount",$cardData1[0]['couponApplyDiscount'],$html);
	    $html = str_replace("--total_cost",$cardData1[0]['inpTotalCost'],$html);
	    $html = str_replace("--paid_cost",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		$html = str_replace("--save_price",$cardData1[0]['numberOfItemssave'],$html);
		$html = str_replace("--sub_total",$cardData1[0]['numberOfItemsTotalAmount'],$html);
		
		
		$html = str_replace("--service_extra_person",$cardData1[0]['service_extra_person'],$html);
		$html = str_replace("--shoot_time",$cardData1[0]['shoot_time'],$html);
		$html = str_replace("--extra_people_price",$cardData1[0]['extra_people_price'],$html);
		$html = str_replace("--extra_photographer_price",$cardData1[0]['extra_photographer_price'],$html);
		$html = str_replace("--extra_vediographer_price",$cardData1[0]['extra_vediographer_price'],$html);
		$html = str_replace("--final_extra_price",$cardData1[0]['final_extra_price'],$html);
		$html = str_replace("--additional_time",$cardData1[0]['additional_time'],$html);
		
		$grand_total = intval($cardData1[0]['inpTotalCost']) + intval($cardData1[0]['final_extra_price']) ;
		$html = str_replace("--grand_total",$grand_total,$html);
		$payable_grand_total = intval($cardData1[0]['numberOfItemsTotalAmount']) + intval($cardData1[0]['final_extra_price']) ;
		$html = str_replace("--payable_grand_total",$payable_grand_total,$html);
		
		$html = str_replace("--I_GST_new",$cardData1[0]['CIGST'],$html);
		$html = str_replace("--C_GST_new",$cardData1[0]['CCGST'],$html);
		$html = str_replace("--S_GST_new",$cardData1[0]['CSGST'],$html);
		
		$taxable_value_new = intval($cardData1[0]['final_extra_price']) - intval($cardData1[0]['CIGST']);
		
		$html = str_replace("--taxble_value_new",$taxable_value_new,$html);
		
		
		$decimalValue = $payable_grand_total; // Replace with your decimal value
        $integerPart = (int) $decimalValue;
        $fractionalPart = round(($decimalValue - $integerPart) * 100);
        
        $integerWords = $this->numberToWords($integerPart);
        $fractionalWords = $this->numberToWords($fractionalPart);
        
        if ($fractionalPart == 0) {
            $inWrd = ucfirst($integerWords) . ' Rupees';
        } else {
            $inWrd = ucfirst($integerWords) . ' Rupees and ' . $fractionalWords . ' Paise';
        }
     
   
		$html = str_replace("--amount_with_words",$inWrd,$html);
		


		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$sql6 = "UPDATE place_order_userservices SET Cinvoice_snt=1 WHERE id = $purchaseID ";
		 $result6 = $this->dbc->update_row($sql6);
		
		
	
// 		self::sendResponse("1", "Invoice send to mail.");

	

	}
	
	
	
	
	
	
	
	


	

}
?>