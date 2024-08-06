<?php
require_once('DashboardClass.php');
require_once('sendMailClass.php');

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


class SystemManage {
    private $dbc;
    private $error_message;
    
    private $bucketName;
    private $s3Client;

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	    
	     $this->s3Client = new S3Client([
            'version'     => 'latest',
            'region' => 'ap-south-2', // e.g., us-east-1
            'credentials' => [
                'key'    => AWS_KEY,
                'secret' => AWS_SECRET,
                'region' => AWS_REGION, // e.g., us-east-1
            ],
        ]);
    	    
	    $this->bucketName = AWS_BUCKET;
	}

    public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}


	function getCountries() {
	    
	  $sql = "SELECT * FROM tblcountries order by short_name asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	 
    public function savestate(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state"]=$_REQUEST['inpState'];
        
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		   
           
           $activityMeg = "State ".$_REQUEST['inpState']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblstate');

		}else{
		    
		     $activityMeg = "State ".$_REQUEST['inpState']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblstate', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}


	
	public function getStateListData(){
	    
	    $disType = $_REQUEST['disType'];
	    
	    if($disType == ""){
	        $sql = "SELECT a.*,b.short_name as short_name FROM tblstate a left join tblcountries b on a.county_id = b.country_id
			WHERE a.active=0 ORDER BY a.id DESC";
	    }else{
	        $sql = "SELECT a.*,b.short_name as short_name FROM tblstate a left join tblcountries b on a.county_id = b.country_id
			WHERE a.active=0 AND a.county_id='$disType' ORDER BY a.id DESC";
	    }
	
		
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteState(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblstate` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete state");
        else self::sendResponse("2", "Failed to delete state");
	
	}
	
	public function geteditStateList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblstate
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}

	function getState() {
	    
	    $selCounty=$_REQUEST["selCounty"];
	    
	  $sql = "SELECT * FROM tblstate where county_id='$selCounty' order by state asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	public function savecity(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['selState'];
        $data["city"]=$_REQUEST['inpCity'];
        $data["address"]=$_REQUEST['inpAddress'];
        
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "District ".$_REQUEST['inpCity']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblcity');

		}else{
		    
		    $activityMeg = "District ".$_REQUEST['inpCity']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblcity', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getCityListData(){
	     $disType = $_REQUEST['disType'];
	     $disType1 = $_REQUEST['disType1'];
	     $where = "";
	     
	     if($disType1 !="") $where = " and c.id='$disType1' ";
	    
	    if($disType == ""){
	        $sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblcity a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.active=0 $where ORDER BY a.id DESC";
	    }else{
	        $sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblcity a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.active=0 AND a.county_id='$disType' $where ORDER BY a.id DESC";
	    }
	
		
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteCity(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblcity` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete District");
        else self::sendResponse("2", "Failed to delete District");
	
	}
	
	public function geteditcityList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblcity
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	function getCity() {
	    
	    $selState=$_REQUEST["selState"];
	    
	  $sql = "SELECT a.* FROM tblcity a left join tblstate b on a.state_id = b.id where b.state='$selState' order by state asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
		 
    public function saveRoles(){
        
         
        $data=array();
        $data["userPermissions"]=$_REQUEST['userPermissions'];
        $data["role"]=$_REQUEST['inpRole'];
        
        $data["isProvider"]=$_REQUEST['isProviderVal'];
        
        $description = str_replace("'", '"', $_REQUEST['inpCSD']);
        $data["description"]=$description;
        
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
        

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "Role ".$_REQUEST['inpRole']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tbluserroles');

		}else{
		    
		    $activityMeg = "Role ".$_REQUEST['inpRole']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tbluserroles', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
		public function getRolesListData(){
	    

		$sql = "SELECT * FROM tbluserroles WHERE active=0 ORDER BY id DESC";
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
        
	
	}
	
		public function geteditRoleList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbluserroles
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	function getStaff() {
	    
	  $sql = "SELECT staffid,firstname,lastname FROM tblstaff order by email asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	public function changeUserStaff(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblstaff
    WHERE staffid = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	function getRoleData() {
	    
	  $sql = "SELECT id,role FROM tbluserroles order by role asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
		 
    public function saveStaffData(){
        
         
        $data=array();
        $data["user_id"]=$_REQUEST['selUser'];
        $data["email"]=$_REQUEST['inpEmail'];
        $data["name"]=$_REQUEST['inpName'];
        $data["role_id"]=$_REQUEST['selRole'];
        $data["password"]=$_REQUEST['inpPassword'];
        
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['selState'];
        $data["city_id"]=$_REQUEST['selCity'];
        
        $data["manage_type"]=$_REQUEST['manageType'];
        
        $data["office_number"]=$_REQUEST['inpOfficePhone'];
        
        $email = $_REQUEST['inpEmail'];
        $id=$_REQUEST['hiddenEventId'];
        $sql = "SELECT * FROM tblstaffuserlogin WHERE `email`='$email' AND  active=0 AND id !='$id' "; 
        $result = $this->dbc->get_rows($sql);
        
        
       
  
        
        if(isset($result[0])){
            self::sendResponse("2", "Email already using");
            die;
        }
        
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
        
     
		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "Staff ".$_REQUEST['inpName']." created by ".$isUsername;
	
		    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
		
			
			 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=11 AND mail_template=83 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$eventUser = $_REQUEST['inpName'];
    		$eventUserEmail = $_REQUEST['inpEmail'];
    	
    		
    		$sql1 = "SELECT short_name FROM `tblcountries` WHERE country_id=".$_REQUEST['selCounty'];
    		$countyData = $this->dbc->get_rows($sql1);
    		$short_name = $countyData[0]['short_name'];
    		
    		$sql2 = "SELECT role FROM `tbluserroles` WHERE id=".$_REQUEST['selRole'];
    		$roleData = $this->dbc->get_rows($sql2);
    		$role = $roleData[0]['role'];
    		
    		$sql3 = "SELECT `state` FROM `tblstate` WHERE id=".$_REQUEST['selState'];
    		$stateData = $this->dbc->get_rows($sql3);
    		$state = $stateData[0]['state'];
    		
    		$sql4 = "SELECT city FROM `tblcity` WHERE id=".$_REQUEST['selCity'];
    		$cityData = $this->dbc->get_rows($sql4);
    		$city = $cityData[0]['city'];
    		
    		
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$eventUser,$html);
    		$html = str_replace("--email",$eventUserEmail,$html);
    		$html = str_replace("--role",$role,$html);
    		$html = str_replace("--password",$_REQUEST['inpPassword'],$html);
    		$html = str_replace("--county",$short_name,$html);
    		$html = str_replace("--state",$state,$html);
    		$html = str_replace("--district",$city,$html);
    		$html = str_replace("--manage_type",$_REQUEST['manageType'],$html);
    		$html = str_replace("--office_number",$_REQUEST['inpOfficePhone'],$html);
    		
    		
    		
    		
    		$send = new sendMails(true);
    		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
    		
    		
    		$result = $this->dbc->insert_query($data, 'tblstaffuserlogin');
            

		}else{
		    
		    $activityMeg = "Staff ".$_REQUEST['inpName']." updated by ".$isUsername;
	
		    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblstaffuserlogin', $data_id);
			
			
			
			
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getUserListData(){
	    
	  
	        $sql = "SELECT a.id,a.created_date,a.email,a.name,b.role as role_id,c.short_name as county_id , d.state as state_id,e.city as city_id ,a.manage_type,a.active,a.password,a.office_number FROM tblstaffuserlogin a left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id order by a.email asc"; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditUserEList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblstaffuserlogin
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		
	public function setactiveUser(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblstaffuserlogin` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." user");
        else self::sendResponse("2", "Failed to ".$dis." user");
	
	}


	function getCityListData1() {
	    
	    $selState=$_REQUEST["selState"];
	    
	  $sql = "SELECT * FROM tblcity where state_id='$selState' order by city asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	public function saveEventType(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['multipleSel'];
        $data["name"]=$_REQUEST['inpEventType'];

       
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "Event Type ".$_REQUEST['inpEventType']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblcustomers_groups');

		}else{
		    
		   $activityMeg = "Event Type ".$_REQUEST['inpEventType']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblcustomers_groups', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getEVTypeListData(){
	     $disType = $_REQUEST['disType'];
	     $disType1 = $_REQUEST['disType1'];
	     $where = "";
	     
	     if($disType1 !="") $where = " and c.id='$disType1' ";
	    
	    if($disType == ""){
	        $sql = "SELECT a.*,b.short_name as short_name,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state FROM tblcustomers_groups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.id !='' $where ORDER BY a.id DESC";
	    }else{
	        $sql = "SELECT a.*,b.short_name as short_name,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state FROM tblcustomers_groups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.county_id='$disType' $where ORDER BY a.id DESC";
	    }
	


		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function geteditEventTypeList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblcustomers_groups
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function deleteEvType(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "DELETE FROM `tblcustomers_groups` WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Event Type");
        else self::sendResponse("2", "Failed to delete Event Type");
	
	}
	
	function getET() {
	    
	    $selState=$_REQUEST["selState"];
	    $q = "SELECT id FROM `tblstate` where `state`='$selState' ";
	    $rq = $this->dbc->get_rows($q);
	    $state_id = $rq[0]['id'];
	    
	    
	  $sql = "SELECT a.name FROM tblcustomers_groups a where FIND_IN_SET($state_id, a.state_id) order by a.name asc"; 
	  
	
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	public function saveServiceType(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['multipleSel'];
        $data["name"]=$_REQUEST['inpServiceType'];

       
       $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
        
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Service Type ".$_REQUEST['inpServiceType']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservice_type');

		}else{
		    
		   $activityMeg = "Service Type ".$_REQUEST['inpServiceType']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblservice_type', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getSerTypeListData(){
	     $disType = $_REQUEST['disType'];
	     $disType1 = $_REQUEST['disType1'];
	     $where = "";
	     
	     if($disType1 !="") $where = " and c.id='$disType1' ";
	    
	    if($disType == ""){
	        $sql = "SELECT a.*,b.short_name as short_name,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state FROM tblservice_type a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.id !='' $where ORDER BY a.id DESC";
	    }else{
	        $sql = "SELECT a.*,b.short_name as short_name ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state FROM tblservice_type a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE a.county_id='$disType' $where ORDER BY a.id DESC";
	    }
	    
	   
      
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditServiceTypeList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservice_type
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function setsetactiveeventtype(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblservice_type` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Event type");
        else self::sendResponse("2", "Failed to ".$dis." Event type");
	
	}
	
	
	function getCategoryListData1() {
	    
	    $selState=$_REQUEST["selState"];
	    if($selState == '') $sql = "SELECT a.* FROM tblservice_type a where a.active=0 order by a.name asc"; 
	    else $sql = "SELECT a.* FROM tblservice_type a where a.active=0 and FIND_IN_SET($selState, a.state_id) order by a.name asc"; 
	    
	  

		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	
	public function saveHV(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['multipleSel'];
        $data["description"]=$_REQUEST['inpDescription'];
        $data["exp"]=$_REQUEST['inpExp'];
        
        if(isset($_FILES['import_video']['name']) && $_FILES['import_video']['name']!=''){
            $target_1 = 'homevedioupload/vid_'.time().$_FILES['import_video']['name'];
            move_uploaded_file($_FILES['import_video']['tmp_name'], $target_1);
            $data['vedio']=$target_1;
        }
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
     
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Add new home vedio with description ".$_REQUEST['inpDescription']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblhome_vedio');

		}else{
		    
		    $activityMeg = "Update home vedio with description ".$_REQUEST['inpDescription']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblhome_vedio', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getHVListData(){
	    
	     $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
		
			if($isAdmin){
		     
        		 $sql = "SELECT a.*,b.short_name as short_name,c.state as state ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tblhome_vedio a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			 ORDER BY a.id DESC";
	
        		    
        		}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                        
		$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tblhome_vedio a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE b.country_id ='$county_id' ORDER BY a.id DESC";
                       
                   }else if($manage_type == 'State'){
                       // user type State
                        
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tblhome_vedio a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";       
                     
                   }else {
                       // user type City
                       
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tblhome_vedio a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";           
                       
                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
	    
	   


		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditHVList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblhome_vedio
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function setsetactiveevHVtype(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblhome_vedio` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Vedio");
        else self::sendResponse("2", "Failed to ".$dis." Vedio");
	
	}
	
	
	
	
	
	public function savePopups(){
        
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['selState'];
        $data["exp"]=$_REQUEST['inpExp'];
        $data["url_address"]=$_REQUEST['inpURL'];
        $data["user_type"]=$_REQUEST['selUserType'];

        
         if(isset($_FILES['uploadImg']['name']) && $_FILES['uploadImg']['name']!=''){
            $target_1 = 'popupimages/vid_'.time().$_FILES['uploadImg']['name'];
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
		    
		     $activityMeg = "Popup with url ".$_REQUEST['inpURL']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblpopups');

		}else{
		    
		    $activityMeg = "Popup with url ".$_REQUEST['inpURL']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblpopups', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getPopupListData(){
	    
	     $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
		
			if($isAdmin){
		     
        		 $sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblpopups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			 ORDER BY a.id DESC";
	
        		    
        		}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                        
		$sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblpopups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE b.country_id ='$county_id' ORDER BY a.id DESC";
                       
                   }else if($manage_type == 'State'){
                       // user type State
                        
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblpopups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";       
                     
                   }else {
                       // user type City
                       
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state FROM tblpopups a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";           
                       
                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
	    
	   


		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditPopupList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblpopups
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	
	public function setsetactiveevPopuptype(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblpopups` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Popup");
        else self::sendResponse("2", "Failed to ".$dis." Popup");
	
	}
	
	function getandshowPopup() {
	    
	    $county_val=$_REQUEST["county_val"];
	    $state_val=$_REQUEST["state_val"];
	    $userType=$_REQUEST["userType"];
	    
	    $sql = "SELECT * FROM tblpopups where county_id='$county_val' and state_id='$state_val' and active=0 and exp >= CURDATE() and user_type='$userType' order by id desc"; 
	    
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	
	public function saveNews(){
        
         
        $data=array();
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['multipleSel'];
        $data["location"]=$_REQUEST['inpLocation'];
        $data["title"]=$_REQUEST['inpTitle'];
        $data["description"]=str_replace("'", '"', $_REQUEST['inpSmallDescription']);
        $data["news"]=str_replace("'", '"', $_REQUEST['inpNews']);
        $data["dis_date"]=$_REQUEST['inpDate'];
        
      
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
     
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Add new news ".$_REQUEST['inpTitle']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tbl_latest_news');

		}else{
		    
		    $activityMeg = "Update news ".$_REQUEST['inpTitle']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tbl_latest_news', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getNewsListData(){
	    
	     $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
		
			if($isAdmin){
		     
        		 $sql = "SELECT a.*,b.short_name as short_name,c.state as state ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_latest_news a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			 ORDER BY a.id DESC";
	
        		    
        		}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                        
		$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_latest_news a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE b.country_id ='$county_id' ORDER BY a.id DESC";
                       
                   }else if($manage_type == 'State'){
                       // user type State
                        
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_latest_news a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";       
                     
                   }else {
                       // user type City
                       
	$sql = "SELECT a.*,b.short_name as short_name,c.state as state,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM tbl_latest_news a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id
			WHERE c.state ='$state' ORDER BY a.id DESC";           
                       
                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
	    
	   


		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function geteditNewsList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbl_latest_news
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		
	public function setsetactiveevNewstype(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tbl_latest_news` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." News");
        else self::sendResponse("2", "Failed to ".$dis." News");
	
	}
	
	function getProjectNames() {
	    
	  $sql = "SELECT * FROM tblprojects order by name asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
		
	public function saveInsentive(){
        
         
        $data=array();
        
        $data["name"]=$_REQUEST['Username'];
        $data["code"]=$_REQUEST['loggedUserIdVal'];
        $data["project_name"]=$_REQUEST['selProjectName'];
        $data["start_date"]=$_REQUEST['inpStartDate'];
        $data["sel_services"]=$_REQUEST['selServices'];
        
        
        
        $data["end_date"]=$_REQUEST['inpProjectEndDate'];
        $data["description"]=str_replace("'", '"', $_REQUEST['inpDescription']);
        $data["role"]=$_REQUEST['inpRole'];
        
        $CHK = $_REQUEST['CHK'];
        if($CHK == 1){
            $data["total_amt"]=intval($_REQUEST['inpTotalAmount']);
            $data["discount_amt"]=intval($_REQUEST['inpDiscountedAmount']);
            $data["total_paid_amt"]=intval ( intval($_REQUEST['inpTotalAmount']) - intval($_REQUEST['inpDiscountedAmount']) );
            
            
        }else{
            $data["total_amt"]=intval($_REQUEST['STP']);
            $data["discount_amt"]=0;
            $data["total_paid_amt"]=intval($_REQUEST['STP']);
            
        }
        
        $data["is_chk"]=$_REQUEST['CHK'];
        
        $data["achievements"]=str_replace("'", '"', $_REQUEST['inpAchievements']);
        $data["challenges"]=str_replace("'", '"', $_REQUEST['inpChallenges']);
        $data["suggestions"]=str_replace("'", '"', $_REQUEST['inpSuggestions']);
        
        
        $data["selected_project"]=$_REQUEST['selectedProject'];
        
        
        if(isset($_FILES['import_video']['name']) && $_FILES['import_video']['name']!=''){
            $target_1 = 'insentiveUploads/file_'.time().$_FILES['import_video']['name'];
            move_uploaded_file($_FILES['import_video']['tmp_name'], $target_1);
            $data['vedio']=$target_1;
        }
        
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
     
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Add new Insentive for project ".$_REQUEST['selProjectName']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblinsentive');

		}else{
		    
		    $activityMeg = "Update Insentive for project ".$_REQUEST['selProjectName']." by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblinsentive', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getInsentiveListData(){
	    
	     $userId = $_REQUEST['userId'];
	     
	      $sql = "SELECT a.* FROM tblinsentive a WHERE a.code = '$userId' and a.active = 0
			 ORDER BY a.id  DESC";
      
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditInsentiveList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT a.*,(SELECT GROUP_CONCAT(c.name) FROM tblinsentive_roles c WHERE FIND_IN_SET(c.id, a.sel_services) > 0) AS sel_services_names FROM tblinsentive a WHERE a.id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function deleteInsentive(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblinsentive` SET `active`=1 WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Insentive");
        else self::sendResponse("2", "Failed to delete Insentive");
	
	}
	
	
	public function getinsentiveUserListData(){
	    
	         $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
	    
	     
	     $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        $disType=$_REQUEST["disType"];
        
      
        
        	if($isAdmin){
		     
        		  if($disType == ""){
            
            	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate'  order by ins.id desc"; 
                    }else{
                        
            	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.code = '$disType'  order by ins.id desc"; 
                    }
                    
        
        
        		    
        	}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                       
                         if($disType == ""){
            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.code = '$disType' and c.country_id ='$county_id'  order by ins.id desc"; 
                        }
        
                       
                   }else if($manage_type == 'State'){
                       // user type State
                       
                         if($disType == ""){
            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.code = '$disType' and d.state ='$state'  order by ins.id desc"; 
                        }
                       
                      
                     
                   }else {
                       // user type City
                       
                        if($disType == ""){
            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,a.name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.code = '$disType' and e.city ='$city'  order by ins.id desc"; 
                        }
                       

                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
	    
	    
	  
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function acceptInsentive(){
	    
		$sel_id=$_REQUEST["sel_id"];
		
		$sqlC = "SELECT a.description,a.selected_project,a.total_paid_amt,a.role,b.email,b.name FROM tblinsentive a left join tblstaffuserlogin b on a.code = b.id where a.id=$sel_id "; 
		$resultC = $this->dbc->get_rows($sqlC);
		
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=14 AND mail_template=100 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['name'],$html);
    		$html = str_replace("--project_name",$resultC[0]['selected_project'],$html);
    		$html = str_replace("--project_description",$resultC[0]['description'],$html);
    		$html = str_replace("--project_role",$resultC[0]['role'],$html);
    		$html = str_replace("--approved_amt",$resultC[0]['total_paid_amt'],$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['name'], $resultC[0]['email'] );
		    
		
	
        $query = "UPDATE `tblinsentive` SET `status`=1 WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully accept Insentive");
        else self::sendResponse("2", "Failed to accept Insentive");
	
	}
	
	public function rejectInsentive(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$description=str_replace("'", '"', $_REQUEST['description']);
		
		
			$sqlC = "SELECT a.description,a.selected_project,a.total_paid_amt,a.role,b.email,b.name FROM tblinsentive a left join tblstaffuserlogin b on a.code = b.id where a.id=$sel_id "; 
		$resultC = $this->dbc->get_rows($sqlC);
		
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=14 AND mail_template=101 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['name'],$html);
    		$html = str_replace("--project_name",$resultC[0]['selected_project'],$html);
    		$html = str_replace("--project_description",$resultC[0]['description'],$html);
    		$html = str_replace("--project_role",$resultC[0]['role'],$html);
    		$html = str_replace("--approved_amt",$resultC[0]['total_paid_amt'],$html);
    		$html = str_replace("--more_info",$description,$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['name'], $resultC[0]['email'] );
		    
		
        $query = "UPDATE `tblinsentive` SET `status`=2,`reject_description`='$description' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully reject Insentive");
        else self::sendResponse("2", "Failed to reject Insentive");
	
	}
	
	
	function getUserRolesList() {
	    

	  $sql = "SELECT * FROM tbluserroles where active=0 order by role asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	
	public function saveInsentiveRoles(){
        
         
        $data=array();
        $data["role_id"]=$_REQUEST['multipleSel'];
        $data["name"]=$_REQUEST['inpInsentive'];
        $data["price"]=intval($_REQUEST['inpPrice']);

       
        $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "Insentive Role ".$_REQUEST['inpInsentive']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblinsentive_roles');

		}else{
		    
		   $activityMeg = "Insentive Role ".$_REQUEST['inpInsentive']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblinsentive_roles', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getInsentiveRolesListData(){
	    
	     $sql = "SELECT a.*,(SELECT GROUP_CONCAT(c.role) FROM tbluserroles c WHERE FIND_IN_SET(c.id, a.role_id) > 0) AS role_id FROM tblinsentive_roles a
			WHERE a.active=0 ORDER BY a.id DESC";


		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function geteditInsentiveRolesList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblinsentive_roles
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteInsentiveRoles(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        // $query = "DELETE FROM `tblinsentive_roles` WHERE `id`=$sel_id";
        $query = "UPDATE `tblinsentive_roles` SET `active`=1 WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Insentive Role");
        else self::sendResponse("2", "Failed to delete Insentive Role");
	
	}
	
	
	function getAllStaffsData() {
	    
	    
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city_id = $_SESSION['city_id'];
       $state_id = $_SESSION['state_id'];
       $county_id = $_SESSION['county_id'];
       
       
       
           
       if($isAdmin){
           $sql = "SELECT a.id,a.name FROM tblstaffuserlogin a left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where a.active=0 order by a.name asc"; 
           
       }else{
           
            if($manage_type == 'County'){
               // user type County
              $sql = "SELECT a.id,a.name FROM tblstaffuserlogin a left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where c.country_id='$county_id' and a.active=0 order by a.name asc"; 
              
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT a.id,a.name FROM tblstaffuserlogin a left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where d.id='$state_id' and a.active=0 order by a.name asc"; 
             
           }else {
               // user type City
                $sql = "SELECT a.id,a.name FROM tblstaffuserlogin a left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where e.id='$city_id' and a.active=0 order by a.name asc"; 
           }
           
       }
	    
  
	    
// 	  $sql = "SELECT * FROM tblstaffuserlogin where active=0 order by name asc"; 
	  
		$result = $this->dbc->get_rows($sql);
		
		if($result != "")self::sendResponse("1", $result);
		else self::sendResponse("2", "No data found");
	}
	
	public function payInsentive(){
	    
		$sel_id=$_REQUEST["sel_id"];
		
		
			$sqlC = "SELECT a.description,a.selected_project,a.total_paid_amt,a.role,b.email,b.name FROM tblinsentive a left join tblstaffuserlogin b on a.code = b.id where a.id=$sel_id "; 
		$resultC = $this->dbc->get_rows($sqlC);
		
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=14 AND mail_template=102 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['name'],$html);
    		$html = str_replace("--project_name",$resultC[0]['selected_project'],$html);
    		$html = str_replace("--project_description",$resultC[0]['description'],$html);
    		$html = str_replace("--project_role",$resultC[0]['role'],$html);
    		$html = str_replace("--approved_amt",$resultC[0]['total_paid_amt'],$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['name'], $resultC[0]['email'] );
		    
		
		
		
		
	
        $query = "UPDATE `tblinsentive` SET `is_paid`=1 WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully pay Insentive");
        else self::sendResponse("2", "Failed to pay Insentive");
	
	}
	
	
	 function getTotalCount(){
	     
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
          
	    $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        $disType=$_REQUEST["disType"];
        
        
        
        
        	if($isAdmin){
		     
        		  if($disType == ""){
            
                        $sql = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate'  ";
                        $sql1 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate' and a.status=1  ";
                        $sql2 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate' and a.status=1 and a.is_paid=1  ";
                        
            
                    }else{
                        
                        $sql = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate' and a.code = '$disType'  ";
                        $sql1 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate' and a.code = '$disType' and a.status=1  ";
                        $sql2 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.created_date >= '$startDate' and a.created_date < '$endDate' and a.code = '$disType' and a.status=1 and a.is_paid=1  ";
                       
                    }
        
        
        
        		    
        	}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                       
                         if($disType == ""){
            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' and ins.status=1  "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' and ins.status=1 and ins.is_paid=1  "; 
                	        
                	        
                	        
                        }else{
                            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' and ins.code = '$disType' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' and ins.status=1 and ins.code = '$disType' "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' and ins.status=1 and ins.is_paid=1 and ins.code = '$disType' ";
                        }
        
                       
                   }else if($manage_type == 'State'){
                       // user type State
                       
                        if($disType == ""){
            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' and ins.status=1  "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' and ins.status=1 and ins.is_paid=1  "; 
                	        
                	        
                	        
                        }else{
                            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' and ins.code = '$disType' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' and ins.status=1 and ins.code = '$disType' "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' and ins.status=1 and ins.is_paid=1 and ins.code = '$disType' ";
                        }
                       
                       
                     
                   }else {
                       // user type City
                       
                        if($disType == ""){
            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' and ins.status=1  "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' and ins.status=1 and ins.is_paid=1  "; 
                	        
                	        
                	        
                        }else{
                            
                	        $sql = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' and ins.code = '$disType' "; 
                	        
                	        $sql1 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' and ins.status=1 and ins.code = '$disType' "; 
                	        
                	        $sql2 = "SELECT SUM(ins.total_paid_amt) AS sumOfTotal FROM tblinsentive ins left join   tblstaffuserlogin a on a.id=ins.code left join tbluserroles b on a.role_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' and ins.status=1 and ins.is_paid=1 and ins.code = '$disType' ";
                        }
                        
                     
                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
        
        
       
        $result = $this->dbc->get_rows($sql);
        $result1 = $this->dbc->get_rows($sql1);
        $result2 = $this->dbc->get_rows($sql2);
        
        $pending = intval($result1[0]['sumOfTotal']) - intval($result2[0]['sumOfTotal']);
        
        $finalArray = array(
            'sumOfTotal'=> $result[0]['sumOfTotal'],
            'sumOfTotalAccepted'=> $result1[0]['sumOfTotal'],
            'sumOfTotalPaid'=> $result2[0]['sumOfTotal'],
            'sumOfTotalPending'=> $pending,
        );
         
        if($result != "")self::sendResponse("1", $finalArray);
        else self::sendResponse("2", "No data found");
          
      }
      
      
      
     function getTotalCountUser(){
          
	   
        $disType=$_REQUEST["disType"];
        
        if($disType == ""){
            
            $sql = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 ";
            $sql1 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0  and a.status=1  ";
            $sql2 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0  and a.status=1 and a.is_paid=1  ";
            

        }else{
            
            $sql = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.code = '$disType'  ";
            $sql1 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.code = '$disType' and a.status=1  ";
            $sql2 = "SELECT SUM(a.total_paid_amt) AS sumOfTotal FROM tblinsentive a where a.active = 0 and a.code = '$disType' and a.status=1 and a.is_paid=1  ";
           
        }
	    
       
        $result = $this->dbc->get_rows($sql);
        $result1 = $this->dbc->get_rows($sql1);
        $result2 = $this->dbc->get_rows($sql2);
        
        $pending = intval($result1[0]['sumOfTotal']) - intval($result2[0]['sumOfTotal']);
        
        $finalArray = array(
            'sumOfTotal'=> $result[0]['sumOfTotal'],
            'sumOfTotalAccepted'=> $result1[0]['sumOfTotal'],
            'sumOfTotalPaid'=> $result2[0]['sumOfTotal'],
            'sumOfTotalPending'=> $pending,
        );
         
        if($result != "")self::sendResponse("1", $finalArray);
        else self::sendResponse("2", "No data found");
          
      }
      
      
      
    public function saveServicesCenter(){
        
         
        $data=array();
        $data["center_name"]=$_REQUEST['inpServicesCenter'];
        $data["description"]=$_REQUEST['inpDescription'];
        $data["isRating"]=$_REQUEST['chkRating'];
        
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		   
           
           $activityMeg = "Services Center ".$_REQUEST['inpServicesCenter']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicescenter');

		}else{
		    
		     $activityMeg = "Services Center ".$_REQUEST['inpServicesCenter']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblservicescenter', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	public function getServicesCenterListData(){
	    
	  
	        $sql = "SELECT a.* FROM tblservicescenter a
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function geteditServicesCenterList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicescenter
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteServicesCenter(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicescenter` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Service Center");
        else self::sendResponse("2", "Failed to delete Service Center");
	
	}
	
	
	public function getservicesprovidersUserListData(){
	    
	    $provider_id=$_REQUEST["provider_id"];
	    
	    if($provider_id == ""){
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 order by cmp.id desc";
	    }else{
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where cmp.user_id='$provider_id' and a.active =1 and cmp.active =0 and cmp.is_company_add = 1 order by cmp.id desc";
	    }
	    
	  
	       
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	
	public function getServiceCenterActiveList(){
	    
	  
	        $sql = "SELECT a.* FROM tblservicescenter a
			WHERE a.active=0 ORDER BY a.id DESC";
	   
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function getCompanyNumber(){
		    
		    
	    $selAssaignedMachooosPerson= $_REQUEST["selAssaignedMachooosPerson"];
	  
	        $sql = "SELECT a.office_number FROM tblstaffuserlogin a
			WHERE a.id='$selAssaignedMachooosPerson' ";
	   
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function getAssaignedMachooosPersonActiveList(){
		    
		    
	    $selCity= $_REQUEST["selCity"];
	  
	        $sql = "SELECT a.name,a.id FROM tblstaffuserlogin a
			WHERE a.active=0 and a.city_id='$selCity' and a.role_id=8 ORDER BY a.name DESC";
	   
// 	echo $sql;

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getCompanyDetails(){
	    
	    $id= $_SESSION['MachooseAdminUser']['id']; 
	  
	         $sql = "SELECT a.*,b.center_name,b.isRating,c.short_name , d.state,e.city,m.name as staff , NULL as category_name_val FROM tblprovideruserlogin a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$id' "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getAllPhotographs(){
	    
	    $id= $_SESSION['MachooseAdminUser']['id']; 
	  
	         $sql = "SELECT a.* FROM tbephotographs_folderfiles a where a.user_id='$id' and a.hide=0 "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getCompanyDetailsForAdmin(){
	    
	    $id= $_REQUEST["userID"];
	  
	         $sql = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff FROM tblprovideruserlogin a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$id' "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getAllPhotographsForAdmin(){
	    
	    $id= $_REQUEST["userID"];
	  
	         $sql = "SELECT a.file_path FROM tbephotographs_folderfiles a where a.user_id='$id' and a.hide=0 "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function acceptCompany(){
	    
		$sel_id=$_REQUEST["sel_id"];
		
		$selCounty=$_REQUEST["selCounty"];
		$selState=$_REQUEST["selState"];
		$selCity=$_REQUEST["selCity"];
		
		$inpMachooosPersonPhone=$_REQUEST["inpMachooosPersonPhone"];
		$selAssaignedMachooosPerson=$_REQUEST["selAssaignedMachooosPerson"];
		
// 		$sqlC = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff FROM tblprovideruserlogin a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$sel_id' "; 

        $sqlC = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff , u.name, u.email FROM tblproviderusercompany a left join tblprovideruserlogin u on u.id=a.user_id left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$sel_id' "; 
	        
		
	
		
		
		$resultC = $this->dbc->get_rows($sqlC);
		
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=104 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['name'],$html);
    		$html = str_replace("--company_name",$resultC[0]['company_name'],$html);
    		$html = str_replace("--company_mail",$resultC[0]['company_mail'],$html);
    		$html = str_replace("--company_address",$resultC[0]['company_address'],$html);
    		$html = str_replace("--company_location",$resultC[0]['company_location'],$html);
    		$html = str_replace("--company_link",$resultC[0]['company_link'],$html);
    		$html = str_replace("--company_phone",$resultC[0]['company_phone'],$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['name'], $resultC[0]['email'] );
		    
		
	
        $query = "UPDATE `tblproviderusercompany` SET `is_accept_company`=1,`machoose_user_id`='$selAssaignedMachooosPerson',`machoose_user_phone`='$inpMachooosPersonPhone',`staff_state_id`='$selState',`staff_county_id`='$selCounty',`staff_city_id`='$selCity' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
		
		 $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
       
        $activityMeg = "Company ".$resultC[0]['company_name']." for provider ".$resultC[0]['name']." is accepted by ".$isUsername;
    	
    	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
	
      
        if($result != "")self::sendResponse("1", "Successfully accept Company");
        else self::sendResponse("2", "Failed to accept Company");
	
	}
	
	
	public function changeProviderStaff(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$selCounty=$_REQUEST["selCounty"];
		$selState=$_REQUEST["selState"];
		$selCity=$_REQUEST["selCity"];
		
		$inpMachooosPersonPhone=$_REQUEST["inpMachooosPersonPhone"];
		$selAssaignedMachooosPerson=$_REQUEST["selAssaignedMachooosPerson"];
	
        $sqlC = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff , u.name, u.email FROM tblproviderusercompany a left join tblprovideruserlogin u on u.id=a.user_id left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$sel_id' "; 
	        
		$resultC = $this->dbc->get_rows($sqlC);
		
	
	
        $query = "UPDATE `tblproviderusercompany` SET `machoose_user_id`='$selAssaignedMachooosPerson',`machoose_user_phone`='$inpMachooosPersonPhone',`staff_state_id`='$selState',`staff_county_id`='$selCounty',`staff_city_id`='$selCity' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
		
		 $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
       
        $activityMeg = "Company ".$resultC[0]['company_name']." for provider ".$resultC[0]['name']." staff changed by ".$isUsername;
    	
    	$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
	
      
        if($result != "")self::sendResponse("1", "Successfully change staff");
        else self::sendResponse("2", "Failed to change staff");
	
	}
	
	
	public function saveProviderService(){
	    
	    $data=array();
        $data["name"]=$_REQUEST['inpServiceName'];
        $data["description"]=$_REQUEST['inpDescription'];
        $data["price"]=intval($_REQUEST['inpServicePrice']);
        $data["user_id"]=$_SESSION['MachooseAdminUser']['id']; 
        
        $data["number_of_members"]=intval($_REQUEST['inpNumberOfMembers']);
        $data["additional_member_price"]=intval($_REQUEST['inpExtraPrice']);
        
        $data["main_id"]=$_REQUEST['selServiceProvider'];
        $data["service_add"]=$_REQUEST['selServiceAdding'];
        $data["service_add_other"]=$_REQUEST['inpServiceAddingOther'];
        
        $data["staff_types"]=$_REQUEST['staffTypes'];
        
        
      
        // if(isset($_FILES['import_image']['name']) && $_FILES['import_image']['name']!=''){
        //     $target_1 = 'providerserviceimages/img_'.time().$_FILES['import_image']['name'];
        //     $out = $this->s3Client->putObject([
        //         'Bucket' => $this->bucketName,
        //         'Key'    => $target_1,
        //         'SourceFile' => $_FILES['import_image']['tmp_name'],
        //     ]);
            
        //     $data['image'] = $out['ObjectURL'];
            
        // }
	  
      $recentActivity = new Dashboard(true);
      $main_service_id = '';

		if($_REQUEST['hiddenEventId']=='' ){
		    
		     $activityMeg = "Provider ".$_SESSION['MachooseAdminUser']['name']." add new service ".$_REQUEST['inpServiceName'];
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
		
			$result = $this->dbc->insert_query($data, 'tblprovider_services');
			$main_service_id = $result['InsertId'];

		}else{
		    
		    $activityMeg = "Provider ".$_SESSION['MachooseAdminUser']['name']." update service ".$_REQUEST['inpServiceName'];
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		// Convert the associative array to a string formatted for SQL
            $sqlParts = array_map(function($key, $value) {
                // Handle null or empty string values
                if ($value === null) {
                    return "`$key`=NULL";
                } else {
                    // Escape single quotes in values
                    $value = addslashes($value);
                    return "`$key`='$value'";
                }
            }, array_keys($data), $data);
            
            // Implode the parts into a single string
            $sqlString = implode(", ", $sqlParts);
            
            $updateId = $_REQUEST['hiddenEventId'];
            
    	    
    	    $sql6 = "UPDATE tblprovider_services SET $sqlString WHERE `id` = '$updateId' ";
            $result=$this->dbc->update_row($sql6);
		   
// 			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
// 			$result=$this->dbc->update_query($data, 'tblprovider_services', $data_id);
			
			
			$main_service_id = $_REQUEST['hiddenEventId'];
		}
		
		
		

		
	
		
		 if(isset($_FILES['import_image']) && !empty($_FILES['import_image']['name'][0])) {
            // $data['images'] = []; // Initialize an array to store image URLs
            
            // Loop through each uploaded file
            foreach($_FILES['import_image']['tmp_name'] as $key => $tmp_name) {
                // Check if the file upload was successful
                if($_FILES['import_image']['error'][$key] === UPLOAD_ERR_OK) {
                    // Generate a unique target path for the image
                    $target = 'providerserviceimages/img_' . time() . $_FILES['import_image']['name'][$key];
                    
                    // Upload the image to S3 bucket
                    $out = $this->s3Client->putObject([
                        'Bucket' => $this->bucketName,
                        'Key'    => $target,
                        'SourceFile' => $_FILES['import_image']['tmp_name'][$key],
                    ]);
                    
                    // Store the uploaded image URL
                    $targetFilePathUrl = $out['ObjectURL'];
                    
                    // Insert image details into the database
                    $qry1 = "INSERT INTO `tbeservice_folderfiles` (`service_id`, `file_path`,`file_name`) VALUES ('$main_service_id','$targetFilePathUrl','".$_FILES['import_image']['name'][$key]."')";
                    $result = $this->dbc->insert_row($qry1);
                    
                    // Add the uploaded image URL to the array
                    // $data['images'][] = $targetFilePathUrl;
                }
            }
        }

      

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
		
	public function getProviderServiceListData(){
	    
	    $userId = $_SESSION['MachooseAdminUser']['id']; 
	   
	    $sql = "SELECT a.*,b.company_name FROM tblprovider_services a left join tblproviderusercompany b on b.id = a.main_id
			WHERE a.active=0 and a.user_id='$userId' ORDER BY a.id DESC";
	  

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getProviderServiceListDataNew(){
	    
	    $userId = $_SESSION['MachooseAdminUser']['id']; 
	    $selServiceProviderList = $_REQUEST['selServiceProviderList'];
	    
	    if($selServiceProviderList == ''){
	        $sql = "SELECT a.*,b.company_name,b.company_logo_url FROM tblprovider_services a left join tblproviderusercompany b on b.id = a.main_id
			WHERE a.active=0 and a.user_id='$userId' ORDER BY a.id DESC";
	    }else{
	        $sql = "SELECT a.*,b.company_name,b.company_logo_url FROM tblprovider_services a left join tblproviderusercompany b on b.id = a.main_id
			WHERE a.active=0 and a.user_id='$userId' and a.main_id ='$selServiceProviderList' ORDER BY a.id DESC";
	    } 
	    
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function geteditProviderServiceList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblprovider_services
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteProviderService(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblprovider_services` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete service");
        else self::sendResponse("2", "Failed to delete service");
	
	}
	
	
	public function getProviderServiceUserListData(){
	    
	         $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
	    
	     
	     $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        $disType=$_REQUEST["disType"];
        
        
        
      
        
        	if($isAdmin){
		     
        		  if($disType == ""){
            
            	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate'  order by ins.id desc"; 
                    }else{
                        
            	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.main_id = '$disType'  order by ins.id desc"; 
                    }
                    
        
        
        		    
        	}else{
        		    
        		    
        		    
        		     if($manage_type == 'County'){
                       // user type County
                       
                         if($disType == ""){
            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and c.country_id ='$county_id' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.main_id = '$disType' and c.country_id ='$county_id'  order by ins.id desc"; 
                        }
        
                       
                   }else if($manage_type == 'State'){
                       // user type State
                       
                         if($disType == ""){
            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and d.state ='$state' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.main_id = '$disType' and d.state ='$state'  order by ins.id desc"; 
                        }
                       
                      
                     
                   }else {
                       // user type City
                       
                        if($disType == ""){
            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and e.city ='$city' order by ins.id desc"; 
                        }else{
                            
                	        $sql = "SELECT ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_name,say.center_name as service_add FROM tblprovider_services ins left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where ins.active = 0 and ins.created_date >= '$startDate' and ins.created_date < '$endDate' and ins.main_id = '$disType' and e.city ='$city'  order by ins.id desc"; 
                        }
                       

                       
                   }
        		    
        		    
        		    
        		    
        		    
        		    
        		    
        		}
	   
	    
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function acceptProviderService(){
	    
		$sel_id=$_REQUEST["sel_id"];
		
		$sqlC = "SELECT a.*,b.email,b.name as username,b.company_name FROM tblprovider_services a left join tblprovideruserlogin b on a.user_id = b.id where a.id=$sel_id "; 
		$resultC = $this->dbc->get_rows($sqlC);
	
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=105 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['username'],$html);
    		$html = str_replace("--company_name",$resultC[0]['company_name'],$html);
    		$html = str_replace("--service_name",$resultC[0]['name'],$html);
    		$html = str_replace("--description",$resultC[0]['description'],$html);
    		$html = str_replace("--price",$resultC[0]['price'],$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['username'], $resultC[0]['email'] );
		    
		
	
        $query = "UPDATE `tblprovider_services` SET `is_accept`=1 WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully accept Service");
        else self::sendResponse("2", "Failed to accept Service");
	
	}
	
	
	public function rejectProviderService(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$description=str_replace("'", '"', $_REQUEST['description']);
		
		
			$sqlC = "SELECT a.*,b.email,b.name as username,b.company_name FROM tblprovider_services a left join tblprovideruserlogin b on a.user_id = b.id where a.id=$sel_id "; 
		$resultC = $this->dbc->get_rows($sqlC);
		
		
		 $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=106 AND `active`=1 ";
    	$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$resultC[0]['username'],$html);
    		$html = str_replace("--company_name",$resultC[0]['company_name'],$html);
    		$html = str_replace("--service_name",$resultC[0]['name'],$html);
    		$html = str_replace("--description",$resultC[0]['description'],$html);
    		$html = str_replace("--price",$resultC[0]['price'],$html);
    		$html = str_replace("--more_info",$description,$html);
    	
    		$send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $resultC[0]['username'], $resultC[0]['email'] );
		    
		
        $query = "UPDATE `tblprovider_services` SET `is_accept`=2,`reject_description`='$description' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully reject Service");
        else self::sendResponse("2", "Failed to reject Service");
	
	}
	
		public function getAllBruchers(){
	    
	    $id= $_REQUEST["selectedCompanyId"];
	  
	         $sql = "SELECT a.* FROM tbebrucher_folderfiles a where a.user_id='$id' and a.hide=0 order by a.id desc "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteBrucher(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbebrucher_folderfiles` SET `hide`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Brucher");
        else self::sendResponse("2", "Failed to delete Brucher");
	
	}
	
		public function deletePhotographs(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbephotographs_folderfiles` SET `hide`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Brucher");
        else self::sendResponse("2", "Failed to delete Brucher");
	
	}
	
	
	
	public function getAllCompanyDetails(){
	    
	    $id= $_SESSION['MachooseAdminUser']['id']; 
	  
	         $sql = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff,'' as staff_lastname FROM tblproviderusercompany a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.user_id='$id' and a.active=0 order by a.id desc "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getAllCompanyEditDetails(){
	    
	    $id= $_REQUEST["selectedCompanyId"];
	  
	         $sql = "SELECT a.*,b.center_name,b.isRating,c.short_name , d.state,e.city,'' as staff_lastname,m.name as staff, cn.category_name as category_name_val FROM tblproviderusercompany a left join tblservicescenter b on a.servicescenter_id = b.id left join tblservicecentersubcategory cn on cn.id=a.rating_val left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where a.id='$id' "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getNewAllPhotographs(){
	    
	    $id= $_REQUEST["selectedCompanyId"]; 
	  
	         $sql = "SELECT a.* FROM tbephotographs_folderfiles a where a.user_id='$id' and a.hide=0 "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteCompany(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblproviderusercompany` SET `active`='1',`is_accept_company`='0' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Brucher");
        else self::sendResponse("2", "Failed to delete Brucher");
	
	}
	
	
	public function getActiveProvidersList(){
	    
	  
	        $sql = "SELECT a.name,a.id FROM tblprovideruserlogin a
			WHERE a.active=1 ORDER BY a.name DESC";
	   
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function setsetactiveevCompanytype(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblproviderusercompany` SET `is_add_service`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Company");
        else self::sendResponse("2", "Failed to ".$dis." Company");
	
	}
	
	
	public function getServiceProvider(){
		    
		    
	        $sql = "SELECT a.company_name,a.id FROM tblproviderusercompany a
			WHERE a.is_add_service=0 and a.active=0 and a.is_accept_company=1 ORDER BY a.company_name asc";
	   
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getServiceProviderForProvider(){
		    
		    $id= $_SESSION['MachooseAdminUser']['id']; 
		    
	        $sql = "SELECT a.company_name,a.id FROM tblproviderusercompany a
			WHERE a.is_add_service=0 and a.active=0 and a.is_accept_company=1 and a.user_id='$id' ORDER BY a.company_name asc";
	   
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getServiceProviderForAdminSide(){
	    
	      $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
	    
	    if($isAdmin){
	        $sql = "SELECT a.company_name,a.id FROM tblproviderusercompany a
			WHERE a.is_add_service=0 and a.active=0 and a.is_accept_company=1 ORDER BY a.company_name asc";
	    }else{
	        $id= $_SESSION['MachooseAdminUser']['id']; 
		    
	        $sql = "SELECT a.company_name,a.id FROM tblproviderusercompany a
			WHERE a.is_add_service=0 and a.active=0 and a.is_accept_company=1 and a.machoose_user_id='$id' ORDER BY a.company_name asc";
	    }
	    

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getServicesForAdminSide(){
	    
	   
	    
	        $selServiceProvider= $_REQUEST["selServiceProvider"];
		    
	        $sql = "SELECT a.name,a.id FROM tblprovider_services a
			WHERE a.is_accept=1 and a.active=0 and a.main_id ='$selServiceProvider' ORDER BY a.name asc";
	  

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function getAllDocs(){
	    
	    $id= $_REQUEST["selectedCompanyId"];
	  
	         $sql = "SELECT a.* FROM tbelegaldocuments_folderfiles a where a.user_id='$id' and a.hide=0 order by a.id desc "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteDocs(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbelegaldocuments_folderfiles` SET `hide`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Legal document");
        else self::sendResponse("2", "Failed to delete Legal document");
	
	}
	
	
	public function getrequestedprovidersUserListData(){
	    
	    $provider_id=$_REQUEST["provider_id"];
	    
	    if($provider_id == ""){
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_accept_company=0 and cmp.is_add_service=0 order by cmp.id desc";
	    }else{
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where cmp.user_id='$provider_id' and a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_accept_company=0 and cmp.is_add_service=0 order by cmp.id desc";
	    }
	    
	  
	       
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getacceptedprovidersUserListData(){
	    
	    $provider_id=$_REQUEST["provider_id"];
	    
	    if($provider_id == ""){
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_accept_company=1 and cmp.is_add_service=0 order by cmp.id desc";
	    }else{
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where cmp.user_id='$provider_id' and a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_accept_company=1 and cmp.is_add_service=0 order by cmp.id desc";
	    }
	    
	  
	       
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function getrejectedprovidersUserListData(){
	    
	    $provider_id=$_REQUEST["provider_id"];
	    
	    if($provider_id == ""){
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_add_service=1 order by cmp.id desc";
	    }else{
	          $sql = "SELECT cmp.*,a.name,a.email,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblproviderusercompany cmp left join tblprovideruserlogin a on a.id = cmp.user_id left join tblservicescenter b on cmp.servicescenter_id = b.id left join tblcountries c on c.country_id = cmp.county_id left join tblstate d on d.id=cmp.state_id left join tblcity e on e.id = cmp.city_id where cmp.user_id='$provider_id' and a.active =1 and cmp.active =0 and cmp.is_company_add = 1 and cmp.is_add_service=1 order by cmp.id desc";
	    }
	    
	  
	       
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function getAllServiceImages(){
	    
	    $id= $_REQUEST["selectedServiceId"];
	  
	         $sql = "SELECT a.* FROM tbeservice_folderfiles a where a.service_id='$id' and a.hide=0 order by a.id desc "; 
	        
	  
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteServiceImages(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tbeservice_folderfiles` SET `hide`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete image");
        else self::sendResponse("2", "Failed to delete image");
	
	}
	
	
	
	 public function saveFAQ(){
        
         
        $data=array();
        
        $data["role"]=$_REQUEST['inpRole'];
        
        $description = str_replace("'", '"', $_REQUEST['inpCSD']);
        $data["description"]=$description;
        
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
        

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "FAQ ".$_REQUEST['inpRole']." is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblFAQ');

		}else{
		    
		    $activityMeg = "FAQ ".$_REQUEST['inpRole']." is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblFAQ', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
			public function getFAQListData(){
	    

		$sql = "SELECT * FROM tblFAQ WHERE active=0 ORDER BY id DESC";
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
        
	
	}
	
	
	
		public function geteditFAQList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblFAQ
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function deleteFAQ(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblFAQ` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete FAQ");
        else self::sendResponse("2", "Failed to delete FAQ");
	
	}
	
	
	
	 public function saveMITAC(){
        
         
        $data=array();
        
        $description = str_replace("'", '"', $_REQUEST['inpCSD']);
        $data["description"]=$description;
        
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
        

		if($_REQUEST['hiddenEventId']=='' ){
		    
		    $activityMeg = "MI Terms and Conditions is created by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tbl_tac');

		}else{
		    
		    $activityMeg = "MI Terms and Conditions is updated by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tbl_tac', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	
	
		public function geteditTACList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tbl_tac
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
			public function getTACList(){
	    

		$sql = "SELECT * FROM tbl_tac WHERE active=0 ORDER BY id DESC";
	
		
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
        
	
	}
	
	
		
	public function getloginprovidersUserListData(){
	    

	  $sql = "SELECT a.*,b.center_name,c.short_name as county_id , d.state as state_id,e.city as city_id FROM tblprovideruserlogin a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id where a.active =1  order by a.id desc";
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function saveServicesAddingType(){
        
         
        $data=array();
        $data["center_name"]=$_REQUEST['inpServicesCenter2'];
        $data["description"]=$_REQUEST['inpDescription'];
        $data["number_of_members"]=$_REQUEST['inpMembers'];
        
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId2']=='' ){
		    
		   
           
           $activityMeg = "Services adding type ".$_REQUEST['inpServicesCenter2']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicesaddingtype');

		}else{
		    
		     $activityMeg = "Services adding type ".$_REQUEST['inpServicesCenter2']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId2'];
			$result=$this->dbc->update_query($data, 'tblservicesaddingtype', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getServicesAddingTypeListData(){
	    
	  
	        $sql = "SELECT a.* FROM tblservicesaddingtype a
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function geteditServicesAddingTypeList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicesaddingtype
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteServicesAddingType(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicesaddingtype` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Service adding type");
        else self::sendResponse("2", "Failed to delete Service adding type");
	
	}
	
	
	
	public function saveServicesAttributes(){
        
         
        $data=array();
        $data["attribute_name"]=$_REQUEST['inpServicesCenter'];
       
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		   
           
           $activityMeg = "Attribute ".$_REQUEST['inpServicesCenter']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicesattribute');

		}else{
		    
		     $activityMeg = "Attribute ".$_REQUEST['inpServicesCenter']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblservicesattribute', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	
		public function getServicesAttributesListData(){
	    
	  
	        $sql = "SELECT a.* FROM tblservicesattribute a
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function geteditServicesAttributesList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicesattribute
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteServicesAttributes(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicesattribute` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Attribute");
        else self::sendResponse("2", "Failed to delete Attribute");
	
	}
	
	
	
	public function saveServicesAttributeFeild(){
        
         
        $data=array();
        $data["attribute_id"]=$_REQUEST['selAttribute'];
        $data["attribute_feild"]=$_REQUEST['inpServicesCenter1'];
        $data["attribute_type"]=$_REQUEST['selFieldType'];
        $data["attribute_min"]=$_REQUEST['inpMin'];
        $data["attribute_max"]=$_REQUEST['inpMax'];
        $data["attribute_options"]=$_REQUEST['inpOptions'];
        $data["attribute_checkedvalue"]=$_REQUEST['checkedValue'];
       
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId1']=='' ){
		    
		   
           
           $activityMeg = "Attribute feild ".$_REQUEST['inpServicesCenter1']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicesattributefeild');

		}else{
		    
		     $activityMeg = "Attribute feild ".$_REQUEST['inpServicesCenter1']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId1'];
			$result=$this->dbc->update_query($data, 'tblservicesattributefeild', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
		
		public function getServicesAttributesFeildListData(){
	    
	  
	        $sql = "SELECT a.*,b.attribute_name FROM tblservicesattributefeild a left join tblservicesattribute b on b.id=a.attribute_id
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		
	public function geteditServicesAttributesFeildList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicesattributefeild
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteServicesAttributesFeild(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicesattributefeild` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Attribute feild");
        else self::sendResponse("2", "Failed to delete Attribute feild");
	
	}
	
	
	public function saveServicescentersubcat(){
        
         
        $data=array();
        $data["category_name"]=$_REQUEST['inpServicesCenterSubCat'];
        $data["service_center_id"]=$_REQUEST['selAttributeServiceCenters'];
       
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId12']=='' ){
		    
		   
           
           $activityMeg = "Service center category ".$_REQUEST['inpServicesCenterSubCat']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicecentersubcategory');

		}else{
		    
		     $activityMeg = "Service center category ".$_REQUEST['inpServicesCenterSubCat']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId12'];
			$result=$this->dbc->update_query($data, 'tblservicecentersubcategory', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	public function getServicesServicescentersubcatListData(){
	    
	  
	        $sql = "SELECT a.*,b.center_name FROM tblservicecentersubcategory a left join tblservicescenter b on b.id=a.service_center_id
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		public function geteditServicescentersubcatList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicecentersubcategory
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteServicescentersubcat(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicecentersubcategory` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete category");
        else self::sendResponse("2", "Failed to delete category");
	
	}
	
	
	
	public function saveServicesAttLink(){
        
         
        $data=array();
        
        $data["staff_types"]=$_REQUEST['mulSelselAttributeStafftypeLink'];
        $data["user_types"]=$_REQUEST['mulSelselAttributeUsertypeLink'];
        $data["link_name"]=$_REQUEST['inpServicesLinkName'];
        $data["service_center_id"]=$_REQUEST['selAttributeServiceCentersLink'];
        
        $data["service_center_sub_id"]=$_REQUEST['attributeServiceCentersSubLink'];
       
       
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId123']=='' ){
		    
		   
           
           $activityMeg = "Link Attribute ".$_REQUEST['inpServicesLinkName']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicelinkattributes');

		}else{
		    
		     $activityMeg = "Link Attribute ".$_REQUEST['inpServicesLinkName']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId123'];
			$result=$this->dbc->update_query($data, 'tblservicelinkattributes', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
		public function getServicesServicesAttLinkListData(){
	    
	  
	        $sql = "SELECT a.*,b.center_name ,(SELECT GROUP_CONCAT(c.category_name) FROM tblservicecentersubcategory c WHERE FIND_IN_SET(c.id, a.service_center_sub_id) > 0) AS service_center_sub_names FROM tblservicelinkattributes a left join tblservicescenter b on b.id=a.service_center_id
			WHERE a.active=0 ORDER BY a.id DESC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
		public function deleteServicesAttLink(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
        $query = "UPDATE `tblservicelinkattributes` SET `active`='1' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully delete Link");
        else self::sendResponse("2", "Failed to delete Link");
	
	}
	
	
		public function geteditServicesAttLinkList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicelinkattributes
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getSCSubCat(){
	    
	    $sel_id=$_REQUEST["selAttributeServiceCentersLink"];
	    
	  
	        $sql = "SELECT a.* FROM tblservicecentersubcategory a
			WHERE a.active=0 and a.service_center_id='$sel_id' ORDER BY a.category_name ASC";
	   

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getServicescentersubcatListForSel(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicecentersubcategory
    WHERE service_center_id = $sel_id and active=0 ORDER BY category_name ASC ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function saveServicesPriceDetails(){
        
         
        $data=array();
        
        
        $data["county_id"]=$_REQUEST['selCounty'];
        $data["state_id"]=$_REQUEST['selState'];
        $data["city_id"]=$_REQUEST['multipleSel'];
        
        $data["price_category_id"]=$_REQUEST['selPriceCategory'];
        $data["service_type_id"]=$_REQUEST['selServiceType'];
        $data["price_per_head"]=$_REQUEST['extraPricePerHead'];
        $data["gst_val"]=$_REQUEST['gst_val'];
        
        
        
        $data["mins_row_id"]=$_REQUEST['runMinId_1'];
        $data["mins_time_interval"]=$_REQUEST['extraMin_1'];
        $data["mins_pic_price"]=$_REQUEST['phtoPrice_1'];
        $data["mins_vedio_price"]=$_REQUEST['vedioPrice_1'];
        $data["mins_extra_pic_price"]=$_REQUEST['phtoExtraPrice_1'];
        $data["mins_extra_vedio_price"]=$_REQUEST['vedioPriceExtra_1'];
        
        $data["hrs_row_id"]=$_REQUEST['runMinId_2'];
        $data["hrs_time_interval"]=$_REQUEST['extraMin_2'];
        $data["hrs_pic_price"]=$_REQUEST['phtoPrice_2'];
        $data["hrs_vedio_price"]=$_REQUEST['vedioPrice_2'];
        $data["hrs_extra_pic_price"]=$_REQUEST['phtoExtraPrice_2'];
        $data["hrs_extra_vedio_price"]=$_REQUEST['vedioPriceExtra_2'];
        
        $data["day_row_id"]=$_REQUEST['runMinId_3'];
        $data["day_time_interval"]=$_REQUEST['extraMin_3'];
        $data["day_pic_price"]=$_REQUEST['phtoPrice_3'];
        $data["day_vedio_price"]=$_REQUEST['vedioPrice_3'];
        $data["day_extra_pic_price"]=$_REQUEST['phtoExtraPrice_3'];
        $data["day_extra_vedio_price"]=$_REQUEST['vedioPriceExtra_3'];
        
        
        
        $data["mins_commission_id"]=$_REQUEST['commissionId_1'];
        $data["mins_mi_commission"]=$_REQUEST['miCommission_1'];
        $data["mins_mi_commission_type"]=$_REQUEST['miCommissionType_1'];
        $data["mins_mi_commission_extra"]=$_REQUEST['miCommissionExtra_1'];
        $data["mins_mi_commission_extra_type"]=$_REQUEST['miCommissionExtraType_1'];
        $data["mins_provider_commission"]=$_REQUEST['providerCommission_1'];
        $data["mins_provider_commission_type"]=$_REQUEST['providerCommissionType_1'];
        $data["mins_provider_commission_extra"]=$_REQUEST['providerCommissionExtra_1'];
        $data["mins_provider_commission_extra_type"]=$_REQUEST['providerCommissionExtraType_1'];
        
        
        $data["hrs_commission_id"]=$_REQUEST['commissionId_2'];
        $data["hrs_mi_commission"]=$_REQUEST['miCommission_2'];
        $data["hrs_mi_commission_type"]=$_REQUEST['miCommissionType_2'];
        $data["hrs_mi_commission_extra"]=$_REQUEST['miCommissionExtra_2'];
        $data["hrs_mi_commission_extra_type"]=$_REQUEST['miCommissionExtraType_2'];
        $data["hrs_provider_commission"]=$_REQUEST['providerCommission_2'];
        $data["hrs_provider_commission_type"]=$_REQUEST['providerCommissionType_2'];
        $data["hrs_provider_commission_extra"]=$_REQUEST['providerCommissionExtra_2'];
        $data["hrs_provider_commission_extra_type"]=$_REQUEST['providerCommissionExtraType_2'];
        
        $data["day_commission_id"]=$_REQUEST['commissionId_3'];
        $data["day_mi_commission"]=$_REQUEST['miCommission_3'];
        $data["day_mi_commission_type"]=$_REQUEST['miCommissionType_3'];
        $data["day_mi_commission_extra"]=$_REQUEST['miCommissionExtra_3'];
        $data["day_mi_commission_extra_type"]=$_REQUEST['miCommissionExtraType_3'];
        $data["day_provider_commission"]=$_REQUEST['providerCommission_3'];
        $data["day_provider_commission_type"]=$_REQUEST['providerCommissionType_3'];
        $data["day_provider_commission_extra"]=$_REQUEST['providerCommissionExtra_3'];
        $data["day_provider_commission_extra_type"]=$_REQUEST['providerCommissionExtraType_3'];
        
        
        
        
        $sel_id = $_REQUEST['selPriceCategory'];
        $sqlg = "SELECT link_name FROM tblservicelinkattributes
    WHERE id = $sel_id ";
	
		$resultg = $this->dbc->get_rows($sqlg);
		
	
         $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       $recentActivity = new Dashboard(true);
      
      

		if($_REQUEST['hiddenEventId']=='' ){
		    
		   
           
           $activityMeg = "Price ".$resultg[0]['link_name']." is created by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		  
			$result = $this->dbc->insert_query($data, 'tblservicespricedetails');

		}else{
		    
		     $activityMeg = "Price ".$resultg[0]['link_name']." is updated by ".$isUsername;
    	
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		   
			$data_id=array(); $data_id["id"]=$_REQUEST['hiddenEventId'];
			$result=$this->dbc->update_query($data, 'tblservicespricedetails', $data_id);
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");


		
	}
	
	
	
	public function getServicePriceListData(){
	    
	     $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
	
        		 $sql = "SELECT a.*,b.short_name as short_name,c.state as state ,(SELECT GROUP_CONCAT(d.city) FROM tblcity d WHERE FIND_IN_SET(d.id, a.city_id) > 0) AS city_id,sa.link_name,st.center_name as serviceType FROM tblservicespricedetails a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblservicelinkattributes sa on sa.id = a.price_category_id left join tblservicesaddingtype st on st.id = a.service_type_id
			 ORDER BY a.id DESC";
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	
	public function setactiveServicePrice(){
	    
		$sel_id=$_REQUEST["sel_id"];
		$setVal=$_REQUEST["setVal"];
		$dis=$_REQUEST["dis"];
	
        $query = "UPDATE `tblservicespricedetails` SET `active`='$setVal' WHERE `id`=$sel_id";
		$result = $this->dbc->update_row($query);
	
      
        if($result != "")self::sendResponse("1", "Successfully ".$dis." Price");
        else self::sendResponse("2", "Failed to ".$dis." Price");
	
	}
	
	
	public function geteditServicePriceList(){
	    
		$sel_id=$_REQUEST["sel_id"];
	
		$sql = "SELECT * FROM tblservicespricedetails
    WHERE id = $sel_id ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result[0]);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function getBookedServiceUserListData(){
	    
	         $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
		
	    
	     
	     $startDate=$_REQUEST["startDate"];
        $endDate=$_REQUEST["endDate"];
        $disType=$_REQUEST["disType"];
        
        
        		  if($disType == ""){
        		      
        		      
        		      $sql = "SELECT pou.id as pouID,pou.created_date as po_created_date,pou.razorpay_payment_status,pou.numberOfItemsTotalAmount,pou.inpTotalCost,pou.inpEventDate,pou.inpEventTime,ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.machoose_user_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM place_order_userservices pou left join tblprovider_services ins on pou.inpServiceID = ins.id left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where pou.newpurchaseID !='' and pou.created_date < '$endDate' order by pou.id desc ";
        		      
        		      
                    }else{
                        
                        $sql = "SELECT pou.id as pouID,pou.created_date as po_created_date,pou.razorpay_payment_status,pou.numberOfItemsTotalAmount,pou.inpTotalCost,pou.inpEventDate,pou.inpEventTime,ins.*,c.short_name as county_id , d.state as state_id,e.city as city_id,a.company_logo_url,a.company_name,say.center_name as service_add,a.company_phone,a.machoose_user_phone,a.servicescenter_id,a.company_address,a.company_link,a.company_mail ,a.provide_wifi,a.provide_parking,a.provide_ac,a.provide_rooftop,a.provide_bathroom,a.provide_welcome_drink,a.provide_food,a.provide_seperate_cabin,a.provide_common_restaurant,a.propert_instructions,a.terms_and_conditions, say.id as eventTypeID, say.number_of_members, a.facebook_link,a.instagram_link,a.twitter_link,a.linkedin_link,a.pinterest_link,a.youtube_link,a.reddit_link,a.tumbler_link FROM place_order_userservices pou left join tblprovider_services ins on pou.inpServiceID = ins.id left join   tblproviderusercompany a on a.id=ins.main_id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblservicesaddingtype say on say.id = ins.service_add where pou.newpurchaseID !='' and pou.created_date < '$endDate' and a.id = '$disType' order by pou.id desc ";
                        
            	      
                    }
  

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
		
	public function getAllCompanyDetailsForStaff(){
	    
	    $id= $_SESSION['MachooseAdminUser']['id']; 
	  
	         $sql = "SELECT a.*,b.center_name,c.short_name , d.state,e.city,m.name as staff,'' as staff_lastname FROM tblproviderusercompany a left join tblservicescenter b on a.servicescenter_id = b.id left join tblcountries c on c.country_id = a.county_id left join tblstate d on d.id=a.state_id left join tblcity e on e.id = a.city_id left join tblstaffuserlogin m on m.id=a.machoose_user_id where FIND_IN_SET($id, a.photographers) and a.active=0 order by a.id desc "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function pauseService(){
	    
	    $selItemId = $_REQUEST['selItemId'];
	    $shoot_time = $_REQUEST['shoot_time'];
	    
	    $endTime = date('Y-m-d H:i:s');
	    
	    $sql = "SELECT * FROM service_time_manage WHERE status = 0 AND `orderID`=$selItemId";
		$result = $this->dbc->get_rows($sql);
		
		$startTime = $result[0]['startTime'];
		
		// Create DateTime objects
        $date1 = new DateTime($startTime);
        $date2 = new DateTime($endTime);
        
        // Calculate the difference
        $interval = $date1->diff($date2);
        
        // Convert the difference to minutes
        $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        
        $finalTime = intval($minutesDifference) + intval($shoot_time);
        
        $query1 = "UPDATE `place_order_userservices` SET `shoot_time`='$finalTime' WHERE `id`=$selItemId";
		$result1 = $this->dbc->update_row($query1);
		
	    
	    $query = "UPDATE `service_time_manage` SET `endTime`='$endTime',`status`=1 WHERE status = 0 AND `orderID`=$selItemId";
		$result = $this->dbc->update_row($query);
		
      
        if($result != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	
	}
	
	public function resumeService(){
	    $selItemId = $_REQUEST['selItemId'];
	    
	    $data=array();
        $data["orderID"]=$selItemId;
        $data["startTime"]= date('Y-m-d H:i:s');
        $data["status"]=0;
        
        $res = $this->dbc->insert_query($data, 'service_time_manage');
        
        if($res != "")self::sendResponse("1", "Service started");
        else self::sendResponse("2", "Error");
	    
	}
	
	public function stopService(){
	    
	    $selItemId = $_REQUEST['selItemId'];
	    $shoot_time = $_REQUEST['shoot_time'];
	    $run_type = $_REQUEST['run_type'];
	    
	    if($run_type == 0){
	        
	        $endTime = date('Y-m-d H:i:s');
	    
    	    $sql = "SELECT * FROM service_time_manage WHERE status = 0 AND `orderID`=$selItemId";
    		$result = $this->dbc->get_rows($sql);
    		
    		$startTime = $result[0]['startTime'];
    		
    		// Create DateTime objects
            $date1 = new DateTime($startTime);
            $date2 = new DateTime($endTime);
            
            // Calculate the difference
            $interval = $date1->diff($date2);
            
            // Convert the difference to minutes
            $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
            
            $finalTime = intval($minutesDifference) + intval($shoot_time);
            
            $query1 = "UPDATE `place_order_userservices` SET `shoot_time`='$finalTime' WHERE `id`=$selItemId";
    		$result1 = $this->dbc->update_row($query1);
    		
    	    
    	    $query = "UPDATE `service_time_manage` SET `endTime`='$endTime',`status`=1 WHERE status = 0 AND `orderID`=$selItemId";
    		$result = $this->dbc->update_row($query);
	        
	    }
	    
	    
	    $query4 = "UPDATE `place_order_userservices` SET `service_status`=2 WHERE `id`=$selItemId";
    	$result4 = $this->dbc->update_row($query4);
    	
    	$sql7 = "SELECT * FROM place_order_userservices WHERE `id`=$selItemId";
	
		$result7 = $this->dbc->get_rows($sql7);
		
		$setm = intval($result7[0]['mins_time_interval']) + intval($result7[0]['inpExtraTime']) ;
		$shoot_time = $result7[0]['shoot_time'];
		
		$additionalTime = intval($shoot_time) - intval($setm);
        if($additionalTime <= 0 ) $additionalTime = 0;
        
        $photographerAdditionalPrice = intval($additionalTime) * intval($result7[0]['extra_pic_price']);
        $vediographerAdditionalPrice = intval($additionalTime) * intval($result7[0]['extra_vedio_price']);
        
        $extraPeoplePrice = intval($result7[0]['service_extra_person']) * intval($result7[0]['extra_people_price']);
        
        $finalPhotographerAdditionalPrice = intval($photographerAdditionalPrice) * intval($result7[0]['inpNumPhotographer']);
        $finalVediographerAdditionalPrice = intval($vediographerAdditionalPrice) * intval($result7[0]['inpNumVediographer']);
        
        $finalAdditinalExtraPrice = intval($extraPeoplePrice) + intval($finalPhotographerAdditionalPrice) + intval($finalVediographerAdditionalPrice);
        
        $query41 = "UPDATE `place_order_userservices` SET `extraPeoplePrice`='$extraPeoplePrice',`photographer_single_price`='$photographerAdditionalPrice',`vediographer_single_price`='$vediographerAdditionalPrice',`extra_photographer_price`='$finalPhotographerAdditionalPrice',`extra_vediographer_price`='$finalVediographerAdditionalPrice',`final_extra_price`='$finalAdditinalExtraPrice',`additional_time`='$additionalTime' WHERE `id`=$selItemId";
    	$result41 = $this->dbc->update_row($query41);
        
	
        if($result41 != "")self::sendResponse("1", "Success");
        else self::sendResponse("2", "Error");
	
	}
	
	public function serviceOtpVerification(){
	    
	    $selItemId = $_REQUEST['selItemId'];
	    $otp = $_REQUEST['otp'];
	    
	    $sql = "SELECT id FROM place_order_userservices WHERE id='$selItemId' AND otp='$otp' ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result[0] != "")self::sendResponse("1", "Otp verified");
        else self::sendResponse("2", "Error");
	
	}
	
	
	public function startService(){
	    
	    $selItemId = $_REQUEST['selItemId'];
	    $selExtraPerson = $_REQUEST['selExtraPerson'];
	    
	   $query = "UPDATE `place_order_userservices` SET `service_extra_person`='$selExtraPerson',`service_start_time`=CURRENT_TIMESTAMP(),`service_status`=1 WHERE `id`=$selItemId";
		$result = $this->dbc->update_row($query);
		
		$data=array();
        $data["orderID"]=$selItemId;
        $data["startTime"]= date('Y-m-d H:i:s');
        $data["status"]=0;
        
        $res = $this->dbc->insert_query($data, 'service_time_manage');
        
	
		$psql = "SELECT * FROM place_order_userservices WHERE id = $selItemId ";
    	$cardData1 = $this->dbc->get_rows($psql);
    		
		$user_id = $cardData1[0]['user_id'];
		$decodedKey = $cardData1[0]['inpServiceID'];
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=111 AND `active`=1 ";
    	
    		
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
    		
		$today = date('Y-m-d H:i:s');
		
		$html = str_replace("--username",$eventUser,$html);
		$html = str_replace("--service_name",$cardData[0]['name'],$html);
	    $html = str_replace("--provider_name",$cardData[0]['company_name'],$html);
	    $html = str_replace("--description",$cardData[0]['description'],$html);
	    $html = str_replace("--start_datetime",$today,$html);
		
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
      
        if($result != "")self::sendResponse("1", "Service started");
        else self::sendResponse("2", "Error");
	
	}
	
		public function getAllPhotographsList(){
	    
	    $ids= $_REQUEST["selectedIds"]; 
	  
	         $sql = "SELECT a.* FROM tblmifutostaffuserlogin a where a.id IN ($ids) and a.user_status=1 "; 
	        
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	
	public function saveEventAlbum(){
	    
	  
		$sigAlbmEventName=$_REQUEST['sigAlbmEventName'];
		$projId = $_REQUEST['selEventId'];
		$coverImage = $_FILES['EventCoverImgFile'];
		$uploadDidectory = 'eventUpload/';
		
		$selCoverId = $_REQUEST['selCoverId'];
		if($selCoverId == ""){
		    
		    $t=time();
    		$event_folder_name = $sigAlbmEventName."_".$t;
    		$eventDirectory = $uploadDidectory.$event_folder_name;
		
		    
		}else{
		    $sql = "SELECT a.* FROM tbeeventalbum_data a where a.id ='$selCoverId' ";
		    $result = $this->dbc->get_rows($sql);
		    $eventDirectory = $result[0]['file_folder'];
		}
	
	
		
	

		$coverImgDirectory = $eventDirectory.'/coverImages';
		$coverImgDirectoryImagePath = $coverImgDirectory.'/'.$coverImage['name'][0];
		
		

		// Usage example:
            $imagePath1 = $coverImage['tmp_name'][0];

            $targetFilePath1 = $coverImgDirectoryImagePath;
            
         
           
            // move_uploaded_file($imagePath1, $targetFilePath1);
            
             try {
                // Upload the file to S3
                    $out = $this->s3Client->putObject([
                        'Bucket' => $this->bucketName,
                        'Key'    => $targetFilePath1,
                        'SourceFile' => $imagePath1,
                    ]);
                    
                    $coverImgDirectoryImagePath = $out['ObjectURL'];
                    
                
                   
                } catch (AwsException $e) {
                    // Handle errors
                    // echo 'Error uploading image: ' . $e->getMessage();
                    self::sendResponse("2", $e->getMessage());
                    die;
                }
		
			$recentActivity = new Dashboard(true);
	
	    if($selCoverId == ""){

    		$evntQry = "INSERT INTO `tbeeventalbum_data`(`project_id`, `folder_name`, `file_folder`, `cover_image_path`) VALUES ('$projId','$sigAlbmEventName','$eventDirectory','$coverImgDirectoryImagePath')";
    
    		$userFolderInsertedId = $this->dbc->insert_row($evntQry);
    		
    		$result = $userFolderInsertedId;
    		
    		  $activityMeg = " Create new event ".$sigAlbmEventName." by photographer ".$_SESSION['Username'] ;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" );

	    }else{
	        
	        $query = "UPDATE `tbeeventalbum_data` SET `cover_image_path`='$coverImgDirectoryImagePath' WHERE `id`=$selCoverId";
		$result = $this->dbc->update_row($query);
		
		  $activityMeg = " Update event ".$sigAlbmEventName." by photographer ".$_SESSION['Username'] ;
        $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" );

	        
	    }
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not save event");

	}
	
	
	public function getAllEventsForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	  
	         $sql = "SELECT a.* FROM tbeeventalbum_data a where a.deleted=0 and a.project_id ='$projId' order by a.id desc "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteEventsForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	    
	    $query = "UPDATE `tbeeventalbum_data` SET `deleted`=1 WHERE `id`=$projId";
		$result = $this->dbc->update_row($query);
		
		
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "error");
	
	}
	
	public function fetchAllUploadImage(){
		$selectedUplSigAlbmId=$_REQUEST["selectedUplSigAlbmId"];
		
	
		$sql = "SELECT `file_name` FROM tbeeventalbum_folderfiles WHERE hide=0 and `album_id`='$selectedUplSigAlbmId' ";
	
		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	}
	
	
	public function getAllEventsImagesForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	  
	         $sql = "SELECT a.* FROM tbeeventalbum_folderfiles a where a.hide=0 and a.album_id ='$projId' order by a.file_name asc "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteEventsImageForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	    
	    $query = "UPDATE `tbeeventalbum_folderfiles` SET `hide`=1 WHERE `id`=$projId";
		$result = $this->dbc->update_row($query);
		
		
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "error");
	
	}

	
	
		public function getAllEventsVedioForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	  
	         $sql = "SELECT a.* FROM tbeeventalbumvedio_folderfiles a where a.hide=0 and a.album_id ='$projId' order by a.file_name asc "; 
	        
	  
	

		$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	
	}
	
	public function deleteEventsVedioForStaff(){
	    
	    $projId = $_REQUEST['selEventId'];
	    
	    $query = "UPDATE `tbeeventalbumvedio_folderfiles` SET `hide`=1 WHERE `id`=$projId";
		$result = $this->dbc->update_row($query);
		
		
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "error");
	
	}
	
	
	public function completeFileUpload(){
	    
	    $projId = $_REQUEST['selEventId'];
	    
	    
	    $query = "UPDATE `place_order_userservices` SET `service_status`=4 WHERE `id`=$projId";
		$result = $this->dbc->update_row($query);
		
			$recentActivity = new Dashboard(true);
			 $activityMeg = "Photographer ".$_SESSION['Username']." is complete image uploading " ;
            $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" );
            
            
            $purchaseID = $_REQUEST['selEventId'];
            
            
            $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    	$cardData1 = $this->dbc->get_rows($psql);
    		
		$user_id = $cardData1[0]['user_id'];
		$decodedKey = $cardData1[0]['inpServiceID'];
    		
    	
    		
    	$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=114 AND `active`=1 ";
    	
    		
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
	    $html = str_replace("--amenities",$amenities,$html);
	    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
	    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
	    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
	    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
	    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
	    
	 
	
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
            
          
		
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "error");
	
	}
	
	
	
	public function cancelServiceNow(){
	    
	    $purchaseNowId = $_REQUEST['purchaseNowId'];
	    $projIdString = str_rot13($purchaseNowId);
        $projIdString = base64_decode($projIdString);
        
        $arr = explode('_', $projIdString);
        $projId = $arr[1];
	    
	 
		
            
            $purchaseID = $projId;
            
            
            $psql = "SELECT * FROM place_order_userservices WHERE id = $purchaseID ";
    	$cardData1 = $this->dbc->get_rows($psql);
    		
		$user_id = $cardData1[0]['user_id'];
		$decodedKey = $cardData1[0]['inpServiceID'];
		$photographerID = $cardData1[0]['photographerID'];
		
		$refoundPercentage = 0;
		
		// Define the future date
        $futureDate = $cardData1[0]['inpEventDate'];
        
        // Get today's date
        $today = date('Y-m-d');
        
        // Convert the dates to DateTime objects
        $date1 = new DateTime($futureDate);
        $date2 = new DateTime($today);
        
        // Calculate the difference between the dates
        $interval = $date1->diff($date2);
        
        // Get the number of days
        $daysBetween = $interval->days;
        
        $psql22 = "SELECT * FROM tbl_scrp WHERE active = 0 order by num_day asc ";
    	$policyData = $this->dbc->get_rows($psql22);
    	
        
        foreach($policyData as $po){
            $num_day = $po['num_day'];
            if( (intval($daysBetween) >= intval($po['num_day'])) ){
                $refoundPercentage = $po['percentage_val'];
            }
        }
        
        $refoundAmt = 0;
        if($refoundPercentage != 0){
            $refoundAmt = ( intval($refoundPercentage) / 100 ) * floatval($cardData1[0]['inpTotalCost']) ;
            
            
        }
        
      
    	$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=115 AND `active`=1 ";
    	
    		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
    		

    	$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
		$sqlU = "SELECT a.*,c.short_name FROM mifuto_users a LEFT JOIN tblcountries c on a.country = c.country_id WHERE a.id='$user_id' "; 
	    $UserList = $this->dbc->get_rows($sqlU);
		    
	    $eventUser = $UserList[0]['name'];
	    $eventUserEmail = $UserList[0]['email'];
	    
	    $oldWalletAmt = $UserList[0]['wallet_balance'];
		    
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
	    $html = str_replace("--amenities",$amenities,$html);
	    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
	    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
	    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
	    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
	    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
	    
	    
	    $newWalletAmt = floatval($refoundAmt) + floatval($oldWalletAmt) ;
	    $lossedAmt = floatval($cardData1[0]['inpTotalCost']) - floatval($refoundAmt) ;
	    
	    $html = str_replace("--wallet-balance",$newWalletAmt,$html);
	    $html = str_replace("--lossed-amount",$lossedAmt,$html);
	    $html = str_replace("--cancel-date",$today,$html);
	    $html = str_replace("--refund-percentage",$refoundPercentage,$html);
	    $html = str_replace("--refund-amount",$refoundAmt,$html);
	    $html = str_replace("--number-of-days",$daysBetween,$html);
	    
	    
	
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
            
            
            
            	$recentActivity = new Dashboard(true);
			 $activityMeg = "Service ".$cardData[0]['name']." is canceled for ".$eventUser ;
            $recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete" );
            
            
            
            
            
            	
    	$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=17 AND mail_template=116 AND `active`=1 ";
    	
    		
		$mailTemplate = $this->dbc->get_rows($sqlM);
	    //send mail here
		$subject = $mailTemplate[0]['subject'];
    		

    	$html = $mailTemplate[0]['mail_body'];
    		
    	
    		
		$sqlU = "SELECT a.* FROM tblmifutostaffuserlogin a WHERE a.id='$photographerID' "; 
	    $UserList = $this->dbc->get_rows($sqlU);
		    
	    $eventUser = $UserList[0]['name']." ".$UserList[0]['lastname'];
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
	    $html = str_replace("--amenities",$amenities,$html);
	    $html = str_replace("--property_use_instructions",$cardData[0]['propert_instructions'],$html);
	    $html = str_replace("--company_tac",$cardData[0]['terms_and_conditions'],$html);
		    
	    $html = str_replace("--event_type",$cardData[0]['service_add'],$html);
	    $html = str_replace("--event_date",$cardData1[0]['inpEventDate'],$html);
	    $html = str_replace("--event_time",$cardData1[0]['inpEventTime'],$html);
	    

		$mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $eventUser, $eventUserEmail );
		
		$currentDateTime = date('Y-m-d H:i:s');
		
	    $query = "UPDATE `place_order_userservices` SET `service_status`=5,`refoundAmt`='$refoundAmt',`refoundPercentage`='$refoundPercentage',`cancelDate`='$currentDateTime',`number-of-days`='$daysBetween',`lossed-amount`='$lossedAmt' WHERE `id`=$projId";
		$result = $this->dbc->update_row($query);
		
		
		
		$query22 = "UPDATE `mifuto_users` SET `wallet_balance`='$newWalletAmt' WHERE `id`=$user_id";
		$result22 = $this->dbc->update_row($query22);
		
		
		$data=array();
        $data["user_id"]=$user_id;
        $data["service_id"]= $projId;
        $data["amount"]=$refoundAmt;
        
        $this->dbc->insert_query($data, 'mifuto_users_wallet');
        $this->dbc->insert_query($data, 'mifuto_admin_wallet');
		
		
	
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "error");
	
	}



	
	
	
	
	

}
?>