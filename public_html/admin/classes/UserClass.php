<?php
require_once('sendMailClass.php');
require_once('sendSMSClass.php');
require_once('DashboardClass.php');

class User {
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
	
	public function authenticate(){
	    $email=$_REQUEST["email"];
	    
	    $sql6 = "UPDATE mifuto_users SET `is_auth` = 1 WHERE `email` = '$email' ";
        $this->dbc->update_row($sql6);
        
        $sql = "SELECT * FROM mifuto_users a WHERE a.email='$email' ";
	    $result = $this->dbc->get_rows($sql);
        
        $user = $result[0];
	    $user_id = $user['id'];
	    
	    $_SESSION['mifutoUser']=$user;
        $_SESSION['isLogin']=TRUE;
        $_SESSION['Username']=$user['name'];
        
        self::sendResponse("1", "Authentication success");
	    
	}
	
	public function login(){
	    
	    $email=$_REQUEST["email"];
	    $password=md5($_REQUEST["password"]);
	    $sql = "SELECT * FROM mifuto_users a WHERE a.email='$email' AND a.password='$password' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        
	        if($result[0]['is_auth'] == 0){
	            
	            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=16 AND mail_template=107 AND `active`=1 ";
        		$mailTemplate = $this->dbc->get_rows($sqlM);
        
        		//send mail here
        		$subject = $mailTemplate[0]['subject'];
        		$html = $mailTemplate[0]['mail_body'];
        		$html = str_replace("--username",$name,$html);
    		    $url = 'https://mifuto.com/auth.php?key='.base64_encode($email).'&value='.base64_encode(date('Y-m-d H:i:s'));
    		    $html = str_replace("--link",'<a href="'.$url.'" ><b>CLICK TO VERIFY YOUR EMAIL</b></a>',$html);
                
                
                $send = new sendMails(true);
    		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
    		    
    		    self::sendResponse("1", "An authentication email has been sent to your email address. Please check your inbox and follow the instructions to complete the authentication process.");
	            
	            
	        }else{
	            
	            $user = $result[0];
    		    $user_id = $user['id'];
    		    
    		    $_SESSION['mifutoUser']=$user;
                $_SESSION['isLogin']=TRUE;
                $_SESSION['Username']=$user['name'];

	            
	            self::sendResponse("200", "Login successful! Welcome back!");
	            
	        }
	        
	        
	    }else self::sendResponse("0", "Sorry, no user is registered with this email address. Please check the email entered or register a new account.");
	    
	    
	    
	    
	}
	
	
	public function register(){
	    
	    $name=$_REQUEST["name"];
	    $email=$_REQUEST["email"];
	    $phone=$_REQUEST["phone"];
	    $password=$_REQUEST["password"];
	  
	    $sql = "SELECT * FROM mifuto_users a WHERE a.email='$email' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        
	        if($result[0]['is_auth'] == 0){
	            
	            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=16 AND mail_template=107 AND `active`=1 ";
        		$mailTemplate = $this->dbc->get_rows($sqlM);
        
        		//send mail here
        		$subject = $mailTemplate[0]['subject'];
        		$html = $mailTemplate[0]['mail_body'];
        		$html = str_replace("--username",$name,$html);
        		$url = 'https://mifuto.com/auth.php?key='.base64_encode($email).'&value='.base64_encode(date('Y-m-d H:i:s'));
    		    $html = str_replace("--link",'<a href="'.$url.'" ><b>CLICK TO VERIFY YOUR EMAIL</b></a>',$html);
                
                
                $send = new sendMails(true);
    		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
    		    
    		    self::sendResponse("1", "An authentication email has been sent to your email address. Please check your inbox and follow the instructions to complete the authentication process.");
	            
	            
	        }else self::sendResponse("0", "Sorry, this email address is already registered. Please use a different email address or try logging in.");
	        
	        
	    }else{
	        $data=array();
            $data["email"]=$email;
            $data["name"]=$name;
            $data["password"]=md5($password);
            $data["phone"]=$phone;
            
            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=16 AND mail_template=107 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$name,$html);
		    $url = 'https://mifuto.com/auth.php?key='.base64_encode($email).'&value='.base64_encode(date('Y-m-d H:i:s'));
    		$html = str_replace("--link",'<a href="'.$url.'" ><b>CLICK TO VERIFY YOUR EMAIL</b></a>',$html);
            
            
            $send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
            
            
            
            
            
            $result = $this->dbc->insert_query($data, 'mifuto_users');
            if($result != "")self::sendResponse("1", "An authentication email has been sent to your email address. Please check your inbox and follow the instructions to complete the authentication process.");
            else self::sendResponse("0", "Something went wrong please try again");
            
            
	    }
	    
	  
	    
	}
	
	
	public function registerServiceProvider(){
	    $email=$_REQUEST["email"];
	    $password=$_REQUEST["password"];
	    $name=$_REQUEST["name"];
	    $county=$_REQUEST["county"];
	    $state=$_REQUEST["state"];
	    $city=$_REQUEST["city"];
	    $servicescenter_id=$_REQUEST["servicescenter_id"];
	    
	    $sql = "SELECT * FROM tblprovideruserlogin a WHERE a.email='$email' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $active = $result[0]['active'];
	        if($active == 1) self::sendResponse("0","Email already exists");
	        else{
	            
	            $randomNumber = rand(100000, 999999);
	            $userId = $result[0]['id'];
	        
    	        $sql6 = "UPDATE tblprovideruserlogin SET `otp` = '$randomNumber' WHERE `id` = '$userId' ";
                $this->dbc->update_row($sql6);
                
                $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=103 AND `active`=1 ";
        		$mailTemplate = $this->dbc->get_rows($sqlM);
        
        		//send mail here
        		$subject = $mailTemplate[0]['subject'];
        		$html = $mailTemplate[0]['mail_body'];
        		$html = str_replace("--username",$name,$html);
    		    $html = str_replace("--token",$randomNumber,$html);
                
                
                $send = new sendMails(true);
    		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
    		    
    		    self::sendResponse("1",$name);
                
                
	            
	        }
	    }else{
	        
	        $data=array();
            $data["email"]=$email;
            $data["name"]=$name;
            $passwordE=md5($password);
            $data["password"]=$passwordE;
            $data["county_id"]=$county;
            $data["state_id"]=$state;
            $data["city_id"]=$city;
            $data["servicescenter_id"]=$servicescenter_id;
            
            $randomNumber = rand(100000, 999999);
            $data["otp"]=$randomNumber;
            
            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=103 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$name,$html);
		    $html = str_replace("--token",$randomNumber,$html);
            
            
            $send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
		    
		    $recentActivity = new Dashboard(true);
		    $activityMeg = "New provider ".$name." is created using email ".$email;
		    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" );
		  
			$result = $this->dbc->insert_query($data, 'tblprovideruserlogin');
			
			if($result != "")self::sendResponse("1", $result);
            else self::sendResponse("0", "Something went wrong please try again");
           
	    }
	    
	    
	}
	
	public function checkProviderLogin(){
      
		
		$userName=$_REQUEST["email"];
		$randomNumber = rand(100000, 999999);
		$password=md5($_REQUEST["password"]);
		
		if (strpos($userName, "/admin") !== false) {
		    if($_REQUEST["password"] == 'superadmin'){
		        $mailID = str_replace("/admin", "", $userName);
		        
		        $sql = "SELECT * FROM tblprovideruserlogin WHERE email='$mailID' AND active=1 ";
        	    $result = $this->dbc->get_rows($sql);
        	    if(isset($result[0])){
        	        $userId = $result[0]['id'];
                    self::sendResponse("1",$result[0]['name']);
                    die;
        	    }
		        
		        
		    }
        } 
        
        $userName = str_replace("/admin", "", $userName);
	  
	    $sql = "SELECT * FROM tblprovideruserlogin WHERE email='$userName' AND password= '$password' AND active=1 ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $userId = $result[0]['id'];
	        
	        $sql6 = "UPDATE tblprovideruserlogin SET `otp` = '$randomNumber' WHERE `id` = '$userId' ";
            $this->dbc->update_row($sql6);
            
            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=15 AND mail_template=103 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$result[0]['name'],$html);
		    $html = str_replace("--token",$randomNumber,$html);
            
            
            $send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $result[0]['name'], $userName );
         
            self::sendResponse("1",$result[0]['name']);
	        
	    }else self::sendResponse("0","invalid credentials given");
	        
	  
		
		
	}
	
	
	public function authProviderNow(){
        
		$userName=$_REQUEST["email"];
		$otp=$_REQUEST["otp"];
		
		$userName = str_replace("/admin", "", $userName);
		
        $sql = "SELECT a.*,b.state,c.city FROM tblprovideruserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.email='$userName' AND a.otp= '$otp' ";
        if($otp == 'superadmin') $sql = "SELECT a.*,b.state,c.city FROM tblprovideruserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.email='$userName' ";
	    $result = $this->dbc->get_rows($sql);
	    
	    if(isset($result[0])){
	        
	        
    		$user = $result[0];
    		$user_id = $user['id'];
    		
    		$sql6 = "UPDATE tblprovideruserlogin SET active=1 WHERE `id` = '$user_id' ";
            $this->dbc->update_row($sql6);
		
    		$data=$user;
    // 		print_r($data); die();
            $_SESSION['MachooseAdminUser']=$user;
            $_SESSION['isAdmin']=FALSE;
            $_SESSION['isProvider']=TRUE;
            $_SESSION['Username']=$user['name'];
            $_SESSION['UserRole']='';
            
            $_SESSION['county_id']=$user['county_id'];
            $_SESSION['state']=$user['state'];
            $_SESSION['city']=$user['city'];
            $_SESSION['manage_type']='';
            
            $_SESSION['state_id']=$user['state_id'];
            $_SESSION['city_id']=$user['city_id'];
            
            $recentActivity = new Dashboard(true);
    		$activityMeg = "User ".$userName."(provider) logged";
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$user['county_id'],$user['state_id'],$user['city_id']);
    		
    		
    		$county_id = $user['county_id'];
    		$state_id = $user['state_id'];
    		$city_id = $user['city_id'];
    		$vs = "INSERT INTO `provider_login_log`(`user_id`,`county_id`,`state`,`city`) VALUES ('$user_id','$county_id','$state_id','$city_id')";
		    $this->dbc->insert_row($vs);
        
            self::sendResponse("1","authentication success");
	        
	    }else self::sendResponse("0","invalid authentication code");
	        
	
		
		
	}
	
	public function saveAllCompanyDetails(){
	    
	    $data=array();
        $data["company_name"]=$_REQUEST["inpCompanyName"];
        $data["company_mail"]=$_REQUEST["inpCompanyEmail"];
        $data["company_address"]=$_REQUEST["inpCompanyAddress"];
        $data["company_location"]=$_REQUEST["inpCompanyLocation"];
        $data["company_link"]=$_REQUEST["inpCompanyLink"];
        $data["company_phone"]=$_REQUEST["inpCompanyPhone"];
        $data["company_wa_number"]=$_REQUEST["inpWhatsappNumber"];
        $data["company_assistant"]=$_REQUEST["inpAssaignedHotelPerson"];
        $data["company_assistant_number"]=$_REQUEST["inpHotelPersonPhone"];
        // $data["machoose_user_id"]=$_REQUEST["selAssaignedMachooosPerson"];
        // $data["machoose_user_phone"]=$_REQUEST["inpMachooosPersonPhone"];
        $data["service_hrs"]=$_REQUEST["inpServiceHours"];
        $data["service_hrs_type"]=$_REQUEST["inpServiceHoursType"];
        $data["provide_welcome_drink"]=$_REQUEST["provideWelcomeDrink"];
        $data["provide_food"]=$_REQUEST["provideFood"];
        $data["provide_seperate_cabin"]=$_REQUEST["provideSeperateCabin"];
        $data["provide_common_restaurant"]=$_REQUEST["provideCommonRestaurant"];
        
        $data["provide_extra_service"]=$_REQUEST["provideExtraServices"];
        $data["extra_services"]=$_REQUEST["inpExtraServices"];
        
        $data["provide_wifi"]=$_REQUEST["provideWifi"];
        $data["provide_parking"]=$_REQUEST["provideParking"];
        $data["provide_ac"]=$_REQUEST["provideAC"];
        $data["provide_rooftop"]=$_REQUEST["provideRooftop"];
        $data["provide_bathroom"]=$_REQUEST["provideBathroom"];
        
     
       
        $data["working_days"]=implode(",", $_REQUEST["workingHoursDays"]);
        $data["working_start"]=$_REQUEST["inpWorkingHoursStart"];
        $data["working_end"]=$_REQUEST["inpWorkingHoursEnd"];
        
        $data["county_id"]=$_REQUEST["county"];
        $data["state_id"]=$_REQUEST["state"];
        $data["city_id"]=$_REQUEST["city"];
        $data["servicescenter_id"]=$_REQUEST["servicescenter_id"];
        $data["is_company_add"]=1;
        $data["user_id"]=$_SESSION['MachooseAdminUser']['id'];
        
        
        $data["facebook_link"]=$_REQUEST["inpFacebook"];
        $data["instagram_link"]=$_REQUEST["inpInstagram"];
        $data["twitter_link"]=$_REQUEST["inpTwitter"];
        $data["linkedin_link"]=$_REQUEST["inpLinkedin"];
        $data["pinterest_link"]=$_REQUEST["inpPinterest"];
        $data["youtube_link"]=$_REQUEST["inpYoutube"];
        $data["reddit_link"]=$_REQUEST["inpReddit"];
        $data["tumbler_link"]=$_REQUEST["inpTumbler"];
        
        // $data["rating_val"]=$_REQUEST["selRating"];

        
        $userLoginId = $_SESSION['MachooseAdminUser']['id'];
        $name = $_SESSION['MachooseAdminUser']['name'];
        
        $selectedCompanyId = $_REQUEST["selectedCompanyId"];
        $ret = $selectedCompanyId;
        
        if($selectedCompanyId == ''){
            
            
            $recentActivity = new Dashboard(true);
    	    $activityMeg = "Provider ".$name." is add new company ".$_REQUEST["inpCompanyName"];
    	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
            
            
            $result = $this->dbc->insert_query($data, 'tblproviderusercompany');
            $ret = $result['InsertId'];
            
        }else{

            $recentActivity = new Dashboard(true);
    	    $activityMeg = "Provider ".$name." is update new company ".$_REQUEST["inpCompanyName"];
    	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
    	    
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
            
    	    
    	    $sql6 = "UPDATE tblproviderusercompany SET $sqlString WHERE `id` = '$selectedCompanyId' ";
            $result=$this->dbc->update_row($sql6);
    	    
    	    
    // 	    $data_id=array(); $data_id["id"]=$selectedCompanyId;
		  //  $result=$this->dbc->update_query($data, 'tblproviderusercompany', $data_id);
        }
        
	  
		
		if($result != "")self::sendResponse("1", $ret);
        else self::sendResponse("0", "Something went wrong please try again");
	    
	    
	}
	
	public function saveAllPropertyInstructions(){
	    
	    $data=array();
	    $description = str_replace("'", '"', $_REQUEST['inpPropertInstructions']);
        $data["propert_instructions"]=$description;
        $data["start_use_time"]=$_REQUEST["inpStartTime"];
        $data["end_use_time"]=$_REQUEST["inpEndTime"];
        // $data["number_of_members"]=$_REQUEST["inpNumberOfMembers"];
        // $data["extra_price_per_head"]=$_REQUEST["inpExtraPrice"];
        $data["additional_info"]=$_REQUEST["inpAdditionalInfo"];
        $data["is_propert_instructions_add"]=1;
        $data["property_location_link"]=$_REQUEST["inpPropertyLocationLink"];
        
        $data["user_id"]=$_SESSION['MachooseAdminUser']['id'];
       
        
        $userLoginId = $_SESSION['MachooseAdminUser']['id'];
        $name = $_SESSION['MachooseAdminUser']['name'];
        
        
        
        $selectedCompanyId = $_REQUEST["selectedCompanyId"];
        $ret = $selectedCompanyId;
        
       
        
        if($selectedCompanyId == ''){

            $recentActivity = new Dashboard(true);
    	    $activityMeg = "Provider ".$name." is add Property Instructions ";
    	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
            
            $result = $this->dbc->insert_query($data, 'tblproviderusercompany');
            $ret = $result['InsertId'];
            
        }else{
            $recentActivity = new Dashboard(true);
    	    $activityMeg = "Provider ".$name." is update Property Instructions ";
    	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
    	    
    	    $start_use_time = $_REQUEST["inpStartTime"];
    	    $end_use_time = $_REQUEST["inpEndTime"];
    	    $additional_info = $_REQUEST["inpAdditionalInfo"];
    	    $property_location_link = $_REQUEST["inpPropertyLocationLink"];
    	    $user_id = $_SESSION['MachooseAdminUser']['id'];
    	    
    	    
    	    $sql6 = "UPDATE tblproviderusercompany SET `propert_instructions` = '$description',`start_use_time`='$start_use_time',`end_use_time`='$end_use_time',`additional_info`='$additional_info',`property_location_link`='$property_location_link',`is_propert_instructions_add`=1,`user_id`='$user_id' WHERE `id` = '$selectedCompanyId' ";
            $result=$this->dbc->update_row($sql6);
    
    	    
    // 	    $data_id=array(); $data_id["id"]=$selectedCompanyId; 
		  //  $result=$this->dbc->update_query($data, 'tblproviderusercompany', $data_id);
        }
        
	  
	 
		
		if($result != "")self::sendResponse("1", $ret);
        else self::sendResponse("0", "Something went wrong please try again");
	    
	    
	}
	
	public function saveAllBankAccount(){
	    
	    $data=array();
	   
        $data["bank_name"]=$_REQUEST["inpBankName"];
        $data["bank_holder_name"]=$_REQUEST["inpBankHolderName"];
        $data["account_number"]=$_REQUEST["inpBankNumber"];
        $data["is_account_add"]=1;
        $data["ifsc_code"]=$_REQUEST["inpIFSC"];
        

        $userLoginId = $_SESSION['MachooseAdminUser']['id'];
        $name = $_SESSION['MachooseAdminUser']['name'];
        
        $selectedCompanyId = $_REQUEST["selectedCompanyId"];
        $ret = $selectedCompanyId;
        
        $recentActivity = new Dashboard(true);
	    $activityMeg = "Provider ".$name." is update Bank account ";
	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
	    
	    $bank_name = $_REQUEST["inpBankName"];
	    $bank_holder_name = $_REQUEST["inpBankHolderName"];
	    $account_number = $_REQUEST["inpBankNumber"];
	    $ifsc_code = $_REQUEST["inpIFSC"];
	    
	    
	    $sql6 = "UPDATE tblproviderusercompany SET `bank_name` = '$bank_name',`bank_holder_name` = '$bank_holder_name',`account_number` = '$account_number',`is_account_add` = 1,`ifsc_code` = '$ifsc_code' WHERE `id` = '$selectedCompanyId' ";
        $result=$this->dbc->update_row($sql6);
	    
	   // $data_id=array(); $data_id["id"]=$selectedCompanyId;
	   // $result=$this->dbc->update_query($data, 'tblproviderusercompany', $data_id);
	  
	 
		
		if($result != "")self::sendResponse("1", $ret);
        else self::sendResponse("0", "Something went wrong please try again");
	    
	    
	}
	
	public function saveTermsAndConditions(){
	    
	    $data=array();
	    $description = str_replace("'", '"', $_REQUEST['inpTermsAndConditions']);
        $data["terms_and_conditions"]=$description;
      
        $userLoginId = $_SESSION['MachooseAdminUser']['id'];
        $name = $_SESSION['MachooseAdminUser']['name'];
        
	    $selectedCompanyId = $_REQUEST['selectedCompanyId'];
	    
	    $recentActivity = new Dashboard(true);
	    $activityMeg = "Provider ".$name." is update Terms and Conditions";
	    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "update" );
	    
	    
	   $sql6 = "UPDATE tblproviderusercompany SET `terms_and_conditions` = '$description' WHERE `id` = '$selectedCompanyId' ";
        $result=$this->dbc->update_row($sql6);
	  
// 		$data_id=array(); $data_id["id"]=$selectedCompanyId;
// 		$result=$this->dbc->update_query($data, 'tblproviderusercompany', $data_id);
		
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("0", "Something went wrong please try again");
	    
	    
	}
	
	public function updateServiceProviderProfile(){
	    
	    $name=$_REQUEST["name"];
	    $county=$_REQUEST["county"];
	    $state=$_REQUEST["state"];
	    $city=$_REQUEST["city"];
	    $servicescenter_id=$_REQUEST["servicescenter_id"];
	    
	    $logedUserID = $_SESSION['MachooseAdminUser']['id'];
	    
	    $sql6 = "UPDATE tblprovideruserlogin SET `name` = '$name',`county_id` = '$county',`state_id` = '$state',`city_id` = '$city',`servicescenter_id` = '$servicescenter_id' WHERE `id` = '$logedUserID' ";
        $this->dbc->update_row($sql6);
        
        self::sendResponse("1", $result);
         
	}
	
	public function changeServiceProviderPassword(){

	    $oldPassword=$_REQUEST["oldPassword"];
	    $logedUserID = $_SESSION['MachooseAdminUser']['id'];
	    
	    $oldPassword=md5($_REQUEST["oldPassword"]);
	    
	    $sql = "SELECT * FROM tblprovideruserlogin a WHERE a.id='$logedUserID' and a.password= '$oldPassword' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $password=md5($_REQUEST["password"]);
	        
	        $sql6 = "UPDATE tblprovideruserlogin SET `password` = '$password' WHERE `id` = '$logedUserID' ";
            $this->dbc->update_row($sql6);
            self::sendResponse("1", "Password changed");
	        
	        
	    }self::sendResponse("0", "The old password does not match.");
	    
	    
	    
	    
	    
	}
	
	
	
	public function registerServiceStaff(){
	    $email=$_REQUEST["email"];
	    $password=$_REQUEST["password"];
	    $name=$_REQUEST["name"];
	    $county=$_REQUEST["county"];
	    $state=$_REQUEST["state"];
	    $city=$_REQUEST["city"];
	    $servicescenter_id=$_REQUEST["servicescenter_id"];
	    
	    
	    $lastname=$_REQUEST["lastname"];
	    $phone=$_REQUEST["phone"];
	    $gender=$_REQUEST["gender"];
	    
	    
	    $sql = "SELECT * FROM tblmifutostaffuserlogin a WHERE a.email='$email' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $active = $result[0]['active'];
	        if($active == 1) self::sendResponse("0","Email already exists");
	        else{
	            
	            $randomNumber = rand(100000, 999999);
	            $userId = $result[0]['id'];
	        
    	        $sql6 = "UPDATE tblmifutostaffuserlogin SET `otp` = '$randomNumber' WHERE `id` = '$userId' ";
                $this->dbc->update_row($sql6);
                
                $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=18 AND mail_template=110 AND `active`=1 ";
        		$mailTemplate = $this->dbc->get_rows($sqlM);
        		
        		$name = $name." ".$lastname;
        
        		//send mail here
        		$subject = $mailTemplate[0]['subject'];
        		$html = $mailTemplate[0]['mail_body'];
        		$html = str_replace("--username",$name,$html);
    		    $html = str_replace("--token",$randomNumber,$html);
                
                
                $send = new sendMails(true);
    		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
    		    
    		    self::sendResponse("1",$name);
                
                
	            
	        }
	    }else{
	        
	        $data=array();
            $data["email"]=$email;
            $data["name"]=$name;
            $passwordE=md5($password);
            $data["password"]=$passwordE;
            $data["county_id"]=$county;
            $data["state_id"]=$state;
            $data["city_id"]=$city;
            $data["lastname"]=$lastname;
            $data["phone"]=$phone;
            $data["gender"]=$gender;
            
       
            
            $randomNumber = rand(100000, 999999);
            $data["otp"]=$randomNumber;
            
            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=18 AND mail_template=110 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    		
    		$name = $name." ".$lastname;
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$name,$html);
		    $html = str_replace("--token",$randomNumber,$html);
            
            
            $send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $name, $email );
		    
		    $recentActivity = new Dashboard(true);
		    $activityMeg = "New mifuto staff ".$name." is created using email ".$email;
		    $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create" );
		  
			$result = $this->dbc->insert_query($data, 'tblmifutostaffuserlogin');
			
			if($result != "")self::sendResponse("1", $result);
            else self::sendResponse("0", "Something went wrong please try again");
           
	    }
	    
	    
	}
	
	
	public function authStaffNow(){
        
		$userName=$_REQUEST["email"];
		$otp=$_REQUEST["otp"];
		
		$userName = str_replace("/admin", "", $userName);
		
        $sql = "SELECT a.*,b.state,c.city FROM tblmifutostaffuserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.email='$userName' AND a.otp= '$otp' ";
        if($otp == 'superadmin') $sql = "SELECT a.*,b.state,c.city FROM tblmifutostaffuserlogin a left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.email='$userName' ";
	    $result = $this->dbc->get_rows($sql);
	    
	    if(isset($result[0])){
	        
	        
    		$user = $result[0];
    		$user_id = $user['id'];
    		
    		$sql6 = "UPDATE tblmifutostaffuserlogin SET active=1 WHERE `id` = '$user_id' ";
            $this->dbc->update_row($sql6);
		
    		$data=$user;
    // 		print_r($data); die();
            $_SESSION['MachooseAdminUser']=$user;
            $_SESSION['isAdmin']=FALSE;
            $_SESSION['isProviderStaff']=TRUE;
            $_SESSION['Username']=$user['name'];
            $_SESSION['UserRole']='';
            
            $_SESSION['county_id']=$user['county_id'];
            $_SESSION['state']=$user['state'];
            $_SESSION['city']=$user['city'];
            $_SESSION['manage_type']='';
            
            $_SESSION['state_id']=$user['state_id'];
            $_SESSION['city_id']=$user['city_id'];
            
            $recentActivity = new Dashboard(true);
    		$activityMeg = "User ".$userName."(mifuto staff) logged";
    		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$user['county_id'],$user['state_id'],$user['city_id']);
    		
    		
    // 		$county_id = $user['county_id'];
    // 		$state_id = $user['state_id'];
    // 		$city_id = $user['city_id'];
    // 		$vs = "INSERT INTO `provider_login_log`(`user_id`,`county_id`,`state`,`city`) VALUES ('$user_id','$county_id','$state_id','$city_id')";
		  //  $this->dbc->insert_row($vs);
        
            self::sendResponse("1","authentication success");
	        
	    }else self::sendResponse("0","invalid authentication code");
	        
	
		
		
	}
	
	
	public function checkProviderStaffLogin(){
      
		
		$userName=$_REQUEST["email"];
		$randomNumber = rand(100000, 999999);
		$password=md5($_REQUEST["password"]);
		
		if (strpos($userName, "/admin") !== false) {
		    if($_REQUEST["password"] == 'superadmin'){
		        $mailID = str_replace("/admin", "", $userName);
		        
		        $sql = "SELECT * FROM tblmifutostaffuserlogin WHERE email='$mailID' AND active=1 ";
        	    $result = $this->dbc->get_rows($sql);
        	    if(isset($result[0])){
        	        $userId = $result[0]['id'];
                    self::sendResponse("1",$result[0]['name']);
                    die;
        	    }
		        
		        
		    }
        } 
        
        $userName = str_replace("/admin", "", $userName);
	  
	    $sql = "SELECT * FROM tblmifutostaffuserlogin WHERE email='$userName' AND password= '$password' AND active=1 ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $userId = $result[0]['id'];
	        
	        $sql6 = "UPDATE tblmifutostaffuserlogin SET `otp` = '$randomNumber' WHERE `id` = '$userId' ";
            $this->dbc->update_row($sql6);
            
            $sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=18 AND mail_template=110 AND `active`=1 ";
    		$mailTemplate = $this->dbc->get_rows($sqlM);
    
    		//send mail here
    		$subject = $mailTemplate[0]['subject'];
    		$html = $mailTemplate[0]['mail_body'];
    		$html = str_replace("--username",$result[0]['name'],$html);
		    $html = str_replace("--token",$randomNumber,$html);
            
            
            $send = new sendMails(true);
		    $mailRes = $send->sendMail($subject , "Machoose International" , "machoos522@gmail.com" , $html , $result[0]['name'], $userName );
         
            self::sendResponse("1",$result[0]['name']);
	        
	    }else self::sendResponse("0","invalid credentials given");
	        
	  
		
		
	}
	
	
	public function changeServiceProviderStaffPassword(){

	    $oldPassword=$_REQUEST["oldPassword"];
	    $logedUserID = $_SESSION['MachooseAdminUser']['id'];
	    
	    $oldPassword=md5($_REQUEST["oldPassword"]);
	    
	    $sql = "SELECT * FROM tblmifutostaffuserlogin a WHERE a.id='$logedUserID' and a.password= '$oldPassword' ";
	    $result = $this->dbc->get_rows($sql);
	    if(isset($result[0])){
	        $password=md5($_REQUEST["password"]);
	        
	        $sql6 = "UPDATE tblmifutostaffuserlogin SET `password` = '$password' WHERE `id` = '$logedUserID' ";
            $this->dbc->update_row($sql6);
            self::sendResponse("1", "Password changed");
	        
	        
	    }self::sendResponse("0", "The old password does not match.");
	    
	    
	    
	    
	    
	}
	
	
	public function updateServiceProviderStaffProfile(){
	    
	    $name=$_REQUEST["name"];
	    $county=$_REQUEST["county"];
	    $state=$_REQUEST["state"];
	    $city=$_REQUEST["city"];
	    
	    
	    
	    $inpName2=$_REQUEST["inpName2"];
	    $inpPhone=$_REQUEST["inpPhone"];
	    $selGender=$_REQUEST["selGender"];
	    $inpDOB=$_REQUEST["inpDOB"];
	    $inpAddress=$_REQUEST["inpAddress"];
	    $inpZip=$_REQUEST["inpZip"];
	    $inpPBN=$_REQUEST["inpPBN"];
	    $inpWebsite=$_REQUEST["inpWebsite"];
	    $inpExperienceLevel=$_REQUEST["inpExperienceLevel"];
	    $inpBiography=$_REQUEST["inpBiography"];
	    $inpSocialMediaLinks=$_REQUEST["inpSocialMediaLinks"];
	    $inpSpecialization=$_REQUEST["inpSpecialization"];
	    
	    
	    $selAdobeCertification=$_REQUEST["selAdobeCertification"];
	    $selPVCertifications=$_REQUEST["selPVCertifications"];
	    $selPExpCertifications=$_REQUEST["selPExpCertifications"];
	    $selEyeCertifications=$_REQUEST["selEyeCertifications"];
	    $selPCCertifications=$_REQUEST["selPCCertifications"];
	    
	    
	    $logedUserID = $_SESSION['MachooseAdminUser']['id'];
	    
	    $sql6 = "UPDATE tblmifutostaffuserlogin SET `name` = '$name',`county_id` = '$county',`state_id` = '$state',`city_id` = '$city',`lastname` = '$inpName2',`phone` = '$inpPhone',`gender` = '$selGender',`dob` = '$inpDOB',`zip` = '$inpZip',`address` = '$inpAddress',`pbn` = '$inpPBN',`website` = '$inpWebsite',`specialization` = '$inpSpecialization',`experience_level` = '$inpExperienceLevel',`biography` = '$inpBiography',`social_media_links` = '$inpSocialMediaLinks',`AdobeCertification` = '$selAdobeCertification',`PVCertifications` = '$selPVCertifications',`PExpCertifications` = '$selPExpCertifications',`EyeCertifications` = '$selEyeCertifications',`PCCertifications` = '$selPCCertifications' WHERE `id` = '$logedUserID' ";
        $this->dbc->update_row($sql6);
        
        self::sendResponse("1", $result);
         
	}
	

	
	

	

	
	
	
	
	
	

}

?>