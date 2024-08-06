<?php
require_once('sendMailClass.php');
require_once('sendSMSClass.php');

class Services {
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
	
	public function getPopularServices(){
	    
	    $where = "";
	   
	    $selProvider=$_REQUEST["selProvider"];
	    if($selProvider !="") $where .= " and a.id=$selProvider ";
        
        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,a.company_address,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,say.center_name as service_add,(SELECT file_path FROM tbeservice_folderfiles WHERE service_id = ins.id AND hide = 0 ORDER BY id DESC LIMIT 1) as file_path FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 $where  order by ins.id desc LIMIT 6 ";
	    $result = $this->dbc->get_rows($sql);
        
        self::sendResponse("1", $result);
	    
	}
	
	public function getBruchers(){
	    
	    $id= $_REQUEST["selectedCompanyId"];
	  
	         $sql = "SELECT a.* FROM tbebrucher_folderfiles a where a.user_id='$id' and a.hide=0 order by a.id desc "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getPriceDetails(){
	    
	    $serviceID= $_REQUEST["serviceID"];
	    $selCard= $_REQUEST["selCard"];
	    
	    $sl = "SELECT a.city_id,ins.service_add,a.rating_val FROM tblprovider_services ins left join tblproviderusercompany a on a.id=ins.main_id WHERE ins.id='$serviceID' "; 
	    $slresult = $this->dbc->get_rows($sl);
	    
	    $loggedUser = '';
	    
	    if (isset($_SESSION['isLogin']) && isset($_SESSION['mifutoUser']['id']) && $_SESSION['mifutoUser']['id'] != "") {
          $isLogin = $_SESSION['isLogin'];
        
          if ($isLogin) {
            $loggedUser = $_SESSION['mifutoUser']['id'];
          }
        }
	    
	    
	    $sql3 = "SELECT a.id,a.name FROM tblprojects a left join tblcontacts b on a.clientid = b.userid left join mifuto_users c on c.email = b.email where c.id='$loggedUser' and a.start_date <= CURDATE() order by a.id asc ";
	    
	    $slresult3 = $this->dbc->get_rows($sql3);
	    if(isset($slresult3[0])){
	        if($selCard == ''){
	            $userType = 'CUSTOMER';
    	    }else{
    	        $userType = 'CUSTOMER WITH CARD';
    	    }
	    }else{
	        if($selCard == ''){
	            $userType = 'GUEST USER';
    	    }else{
    	        $userType = 'GUEST USER WITH CARD';
    	    }
	    }
	    
	   // a.service_center_sub_id = 
	    

	    
	    $staffType ='IN HOUSE STAFF';
	    
	    $city_id = $slresult[0]['city_id'];
	    $service_add = $slresult[0]['service_add'];
	    $rating_val = $slresult[0]['rating_val'];
	    $whr = "";
	    if($rating_val != NULL && $rating_val !="" && $rating_val != null ){
	        $whr = " AND a.service_center_sub_id ='$rating_val' ";
	    }
	    
	    $sql = "SELECT b.* FROM tblservicelinkattributes a left join tblservicespricedetails b on a.id=b.price_category_id WHERE a.staff_types='$staffType' and a.user_types='$userType' and a.active=0 and b.active=0 and b.service_type_id = $service_add and FIND_IN_SET($city_id, b.city_id) $whr  ";
	    
	   // echo $sql;
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getCardBenfits(){
	
		
	
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
	
	
	
	public function userServicePlaceOrderNow(){
	    
	    $data=array();
	    
	    $data["user_id"]=$_REQUEST['user_id'];
	    $data["isSte"]=$_REQUEST['isSte'];
	    $data["inpServiceID"]=$_REQUEST['inpServiceID'];
	    $data["inpPhotographerPrice"]=$_REQUEST['inpPhotographerPrice'];
	    $data["inpVediographerPrice"]=$_REQUEST['inpVediographerPrice'];
	    $data["inpEventDate"]=$_REQUEST['inpEventDate'];
	    $data["inpEventTime"]=$_REQUEST['inpEventTime'];
	    $data["inpExtraTime"]=$_REQUEST['inpExtraTime'];
	    $data["inpExtraPeople"]=$_REQUEST['inpExtraPeople'];
	    $data["inpExtraPeoplePrice"]=$_REQUEST['inpExtraPeoplePrice'];
	    $data["inpNumPhotographer"]=$_REQUEST['inpNumPhotographer'];
	    $data["inpNumVediographer"]=$_REQUEST['inpNumVediographer'];
	    $data["inpDays"]=$_REQUEST['inpDays'];
	    $data["inpHrs"]=$_REQUEST['inpHrs'];
	    $data["inpMins"]=$_REQUEST['inpMins'];
	    $data["inpSelCard"]=$_REQUEST['inpSelCard'];
	    $data["inpTotalCost"]=$_REQUEST['inpTotalCost'];
	    $data["mins_time_interval"]=$_REQUEST['mins_time_interval'];
	    
	    $couponDiscount = $_REQUEST['couponDiscount'];
	    $ItemsPrice = floatval($_REQUEST['inpTotalCost']) - floatval($couponDiscount);
	    $PayableAmt = floatval($ItemsPrice) / 2;
	    
	    
	    $data["numberOfItemsPrice"]=$ItemsPrice;
		$data["numberOfItemsDiscount"]=0;
		$data["numberOfItemsTotalAmount"]=$PayableAmt;
		$data["numberOfItemssave"] = $couponDiscount;
		$data["couponApplyDiscount"] = $couponDiscount;
		
		
		$data["extra_people_price"]=$_REQUEST['extraPeopleSinglePrice'];
	    $data["extra_pic_price"]=$_REQUEST['extraTimeSinglePriceForPic'];
	    $data["extra_vedio_price"]=$_REQUEST['extraTimeSinglePriceForVedio'];
	    
	    $data["finalGstVal"]=$_REQUEST['finalGstVal'];
	    $data["gstVal"]=$_REQUEST['gstVal'];
	    $data["priceGenID"]=$_REQUEST['priceGenID'];
		
		
	  
		$result = $this->dbc->insert_query($data, 'place_order_userservices');
	
	
		if($result != ""){
		    $timestamp = time();
		    $id = $result['InsertId'];
		    $decodeId = base64_encode($timestamp . "_".$id);
		    $decodeId = str_rot13($decodeId);
		    
		    self::sendResponse("1", $decodeId);
		    
		}else self::sendResponse("0", "Failed to place order");

	

	}
	
	public function applyCardServiceCouponcode(){
	
		$Couponcode = $_REQUEST['Couponcode'];
		$sql1 = "SELECT * FROM `tblcardservicescoupons` WHERE CouponCode='$Couponcode' AND `delete`=0 AND CouponsStartDate <= CURDATE() AND CouponsEndDate >= CURDATE() ";
// 		echo $sql1;
		$result = $this->dbc->get_rows($sql1);
	
	
		self::sendResponse("1", $result);

	

	}
	

	
	
	
	
	

}

?>