<?php
require_once('./config.php');
require_once('sendMailClass.php');
// include("../get_session.php");



// $mail = new PHPMailer(true);
class Comments {
    private $dbc;
    private $error_message;
    private $SERVER_HTTP_URL;

    function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
	    
	    $this->$SERVER_HTTP_URL = 'https://machooosinternational.com/';

		
	}

    public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}
	
	public function saveComments(){
		
		$userType = $_REQUEST['userType'];
		$commentingUserId = $_REQUEST['commentingUserId'];
		$userDtls = "";
		$commentUserName = "";
		$commentUserEmail = "";
		$commentUserPhone = "";
		
		
// 		print_r($commentUserName);die();
		$projId = (int) $_REQUEST['projId'];
		$commentId = (int) $_REQUEST['commentId'];
		$commentUserName = $_REQUEST['commentUserName'];
		$commentUserEmail = $_REQUEST['commentUserEmail'];
		$commentUserPhone = $_REQUEST['commentUserPhone'];
		$imogiText = $_REQUEST['imogiText'];
		
		$user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
		
		if($userType == 2){
		    $usersql = "SELECT * FROM tbeguest_users WHERE id=$commentingUserId ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $commentUserName = $userDtls[0]['name'];
    		$commentUserEmail = $userDtls[0]['email'];
    		$commentUserPhone = $userDtls[0]['phone'];
		}else{
		    $usersql = "SELECT * FROM tblcontacts WHERE id=$commentingUserId ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $commentUserName = $userDtls[0]['firstname'].' '.$userDtls[0]['lastname'];
    		$commentUserEmail = $userDtls[0]['email'];
    		$commentUserPhone = $userDtls[0]['phonenumber'];
		}
		
		if($commentId !=""){

			$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];
			$userID = $AlbumList[0]['userID'];

			$recentActivity = new Dashboard(true);
			$activityMeg = "Edit user ".$commentUserName." (".$commentUserPhone.",".$commentUserEmail.") comment signature album ".$prjName." for user ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
			
			$timestamp = time(); // Get the current timestamp
			$encodedString = base64_encode($timestamp . "_".$projId);
			$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;
			
			$activityMeg1 = $commentUserName." edited commented in your signature album ".$prjName;
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,$encodedStringUrl);
			
			


		    $qry = "UPDATE `tbeproject_comments` SET `name`='$commentUserName', `email`='$commentUserEmail', `phone`='$commentUserPhone', `comment`='$imogiText' WHERE `id`= $commentId";
		    $result = $this->dbc->update_row($qry);
		}else{

    		$user_data = get_session();
    		
    		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];
			$userID = $AlbumList[0]['userID'];
			$mailId = $AlbumList[0]['email'];
			
			
    		if(isset($user_data['userID']) && $user_data['userID'] > 0) {
    			$status = 1;
    		}else{
    			$status = 0;
    			
    			$queapproval = "SELECT id FROM `user_guest_comment_approval` WHERE `user_type`='$user_type_val' AND `user_id`='$userID' AND `guest_id`='$user_id_like' ";
    			$queapprovalList = $this->dbc->get_rows($queapproval);
    			if(sizeof($queapprovalList) >0 ) $status = 1;

    		}
    	
    		$qry = "INSERT INTO `tbeproject_comments`(`project_id`, `name`, `email`, `phone`, `comment`,`status`, created_by,`user_type`,`commented_user_id`) VALUES ($projId,'$commentUserName','$commentUserEmail','$commentUserPhone','$imogiText','$status', $commentingUserId ,$user_type_val , $user_id_like )";
    		// $qry = "INSERT INTO `tbeproject_comments`(`project_id`, `name`, `email`, `phone`, `comment`) VALUES ($projId,'$commentUserName','$commentUserEmail','$commentUserPhone','$imogiText')";
    
    		$result = $this->dbc->insert_row($qry);
    		$result = array(
                "data" => $result,
                "approval_status" => $status
            );
    		
    	

			$recentActivity = new Dashboard(true);
			$activityMeg = $commentUserName." commented signature album ".$prjName." for ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

			$timestamp = time(); // Get the current timestamp
			$encodedString = base64_encode($timestamp . "_".$projId);
			$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

			$activityMeg1 = $commentUserName." commented your signature album ".$prjName;
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "comment" ,$userID,$encodedStringUrl);
			
			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=17 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];

			$html = $mailTemplate[0]['mail_body'];

			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--album_name",$prjName,$html);
			$html = str_replace("--email",$commentUserEmail,$html);
			$html = str_replace("--phone",$commentUserPhone,$html);
			$html = str_replace("--comment",$imogiText,$html);
			$html = str_replace("--name",$commentUserName,$html);
		
			$send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );


		}
// 		die($result);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}

	public function saveCommentsOldFunc(){
		// die($_REQUEST);
		$projId = (int) $_REQUEST['projId'];
		$commentId = (int) $_REQUEST['commentId'];
		$commentUserName = $_REQUEST['commentUserName'];
		$commentUserEmail = $_REQUEST['commentUserEmail'];
		$commentUserPhone = $_REQUEST['commentUserPhone'];
		$imogiText = $_REQUEST['imogiText'];
		$commentUserPhone = $_REQUEST['commentUserPhone'];
		
		if($commentId !=""){

			$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];

			$recentActivity = new Dashboard(true);
			$activityMeg = "Edit user ".$commentUserName." (".$commentUserPhone.",".$commentUserEmail.") comment signature album ".$prjName." for user ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");


		    $qry = "UPDATE `tbeproject_comments` SET `name`='$commentUserName', `email`='$commentUserEmail', `phone`='$commentUserPhone', `comment`='$imogiText' WHERE `id`= $commentId";
		    $result = $this->dbc->update_row($qry);
		}else{

    		$user_data = get_session();
    		if(isset($user_data['userID']) && $user_data['userID'] > 0) {
    			$status = 1;
    		}else{
    			$status = 0;
    		}
    		
    		$qry = "INSERT INTO `tbeproject_comments`(`project_id`, `name`, `email`, `phone`, `comment`,`status`) VALUES ($projId,'$commentUserName','$commentUserEmail','$commentUserPhone','$imogiText','$status')";
    		// $qry = "INSERT INTO `tbeproject_comments`(`project_id`, `name`, `email`, `phone`, `comment`) VALUES ($projId,'$commentUserName','$commentUserEmail','$commentUserPhone','$imogiText')";
    
    		$result = $this->dbc->insert_row($qry);

			$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['project_name'];
			$userID = $AlbumList[0]['userID'];

			$recentActivity = new Dashboard(true);
			$activityMeg = $commentUserName." commented signature album ".$prjName." for ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

			$timestamp = time(); // Get the current timestamp
			$encodedString = base64_encode($timestamp . "_".$projId);
			$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

			$activityMeg1 = $commentUserName." commented your signature album ".$prjName;
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "comment" ,$userID,$encodedStringUrl);

// 			setcookie('commentUserName', $commentUserName, time() + (86400 * 30), "/");
//     		setcookie('commentUserEmail', $commentUserEmail, time() + (86400 * 30), "/");
//     		setcookie('commentUserPhone', $commentUserPhone, time() + (86400 * 30), "/");


		}
// 		die($result);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}

	public function sendGustUserMail(){

		$commentUserName = $_REQUEST['UserName'];
		$commentUserEmail = $_REQUEST['UserEmail'];
		$commentUserPhone = $_REQUEST['UserPhone'];

		//Save gust user here
		$chkemail = "SELECT * FROM tblcontacts WHERE email= '$commentUserEmail' ";
		$reslArr = $this->dbc->get_rows($chkemail);

		$ary = array();
		
	
		if(sizeof($reslArr) == 0){

			$token = mt_rand(100000, 999999);
			$chkalrdyGust = "SELECT * FROM tbeguest_users WHERE email= '$commentUserEmail' AND active=1 ";
			$reslGustArr = $this->dbc->get_rows($chkalrdyGust);

			if(sizeof($reslGustArr) == 0){

				$chkalrdyGustNOActive = "SELECT * FROM tbeguest_users WHERE email= '$commentUserEmail' ";
				$reslGustNOActiveArr = $this->dbc->get_rows($chkalrdyGustNOActive);
				
				// print_r(sizeof($reslGustNOActiveArr));
				// die;

				if(sizeof($reslGustNOActiveArr) == 0){
					$qry = "INSERT INTO `tbeguest_users`( `name`, `email`, `phone`, `token`) VALUES ('$commentUserName','$commentUserEmail',$commentUserPhone,'$token')";
					$this->dbc->update_row($qry);
				}else{
					$qry = "UPDATE `tbeguest_users` SET `token`='$token' WHERE `email`='$commentUserEmail' ";
					$this->dbc->update_row($qry);
				}

				$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=14 AND `active`=1 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$html = $mailTemplate[0]['mail_body'];

				$html = str_replace("--username",$commentUserName,$html);
				$html = str_replace("--mailId",$commentUserEmail,$html);
				$html = str_replace("--phoneno",$commentUserPhone,$html);
				$html = str_replace("--token",$token,$html);

				$send = new sendMails(true);
				$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $commentUserName, $commentUserEmail );

				$ary ["status"] =1;
				$ary ["message"] ="Mail send to mail";
				self::sendResponse("1", $ary  );

			}else{
				$ary ["status"] =0;
				$ary ["message"] ="Already have an account";
				self::sendResponse("1", $ary  );
			}



		}else{

			$ary ["status"] =0;
			$ary ["message"] ="Already have an account";
			self::sendResponse("1", $ary  );
		}


	}
	
	public function checkUserAndUpdate(){
	    
	    $name = $_REQUEST['name'];
	    $email = $_REQUEST['email'];
	    $phone = $_REQUEST['phone'];
	    $selCounty = $_REQUEST['selCounty'];
	    $selState = $_REQUEST['selState'];
	    $callFrom = $_REQUEST['callFrom'] ?? 'Machooos International';
	    
	  
	    $chkmainemail = "SELECT * FROM tblcontacts WHERE email= '$email' ";
	    $reslmainArr = $this->dbc->get_rows($chkmainemail);
	    if(sizeof($reslmainArr)>0){
	        self::sendResponse("2", "Password required"  );
	        die;
	    }else{
	        
	        $chkdemail = "SELECT * FROM tbeguest_users WHERE email= '$email' AND deleted=1 ";
	        $resldArr = $this->dbc->get_rows($chkdemail);
	        if(sizeof($resldArr)>0){
    	        self::sendResponse("3", "Account deactivated, More info please contact now!"  );
    	        die;
    	    }
	        
	        
	        $chkemail = "SELECT * FROM tbeguest_users WHERE email= '$email' AND active=1 ";
	        $reslArr = $this->dbc->get_rows($chkemail);
	        if(sizeof($reslArr)>0){
	            
	            $guestUserId = $reslArr[0]['id'];
        		$guestLoginName = $reslArr[0]['name'];
        		$guestLoginEmail = $reslArr[0]['email'];
        		$guestLoginPhone = $reslArr[0]['phone'];
        		
        		$qry = "UPDATE `tbeguest_users` SET `county_id`='$selCounty',`state`='$selState' WHERE `id`='$guestUserId' ";
				$this->dbc->update_row($qry);
		
	            $_SESSION["GUESTUSERDETAILS"] = $reslArr[0];
	            
	            setcookie('guestLoginId', $guestUserId, time() + (86400 * 30), "/");
		        setcookie('guestLoginName', $guestLoginName, time() + (86400 * 30), "/");
		        setcookie('guestLoginEmail', $guestLoginEmail, time() + (86400 * 30), "/");
		        setcookie('guestLoginPhone', $guestLoginPhone, time() + (86400 * 30), "/");
		        
		        $getState = "SELECT id FROM tblstate WHERE state= '$selState' ";
		        $statereslArr = $this->dbc->get_rows($getState);
		        
		        setcookie('user_state_val', $statereslArr[0]['id'], time() + (86400 * 30), "/");
                setcookie('user_county_val', $selCounty, time() + (86400 * 30), "/");
                setcookie('user_state_name', $selState, time() + (86400 * 30), "/");
		        
		        
		        $recentActivity = new Dashboard(true);
	
        		$activityMeg2 = "New Login Alert";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$guestUserId,"#");
        		
                $activityMeg = "User ".$guestLoginName."(guest user) logged";
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$selCounty,$statereslArr[0]['id']);
        		
        		
		        
				$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=15 AND `active`=1 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$html = $mailTemplate[0]['mail_body'];

				$html = str_replace("--username",$guestLoginName,$html);
				$html = str_replace("--mailId",$guestLoginEmail,$html);
				$html = str_replace("--phoneno",$guestLoginPhone,$html);

				$send = new sendMails(true);
				$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $guestLoginName, $guestLoginEmail );
		        
				self::sendResponse("0", "Login success" );
		        
	        }else{
	            
	            $token = mt_rand(100000, 999999);

	            $chkemail1 = "SELECT * FROM tbeguest_users WHERE email= '$email' ";
	            $reslArr1 = $this->dbc->get_rows($chkemail1);
	            
	            if(sizeof($reslArr1)>0){
	                $guestUserId = $reslArr1[0]['id'];
	                $qry = "UPDATE `tbeguest_users` SET `county_id`='$selCounty',`state`='$selState',`token`='$token' WHERE `id`='$guestUserId' ";
				    $this->dbc->update_row($qry);
	            }else{
	                $qry = "INSERT INTO `tbeguest_users`( `name`, `email`, `phone`, `token`,`county_id`,`state`,`callFrom`) VALUES ('$name','$email',$phone,'$token','$selCounty','$selState','$callFrom')";
				    $this->dbc->update_row($qry);
	            }
	            
				
				$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=14 AND `active`=1 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$html = $mailTemplate[0]['mail_body'];

				$html = str_replace("--username",$name,$html);
				$html = str_replace("--mailId",$email,$html);
				$html = str_replace("--phoneno",$phone,$html);
				$html = str_replace("--token",$token,$html);

				$send = new sendMails(true);
				$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $name, $email );

				$ary ["status"] =1;
				$ary ["message"] ="Mail send to mail";
				self::sendResponse("1", $ary  );
	            
	            
	        }
	        
	        
	        
	        
	    }
	    
	}
	
	
	public function checkUserAndUpdateNew(){
	    
	    $email = $_REQUEST['email'];
	   
	    $chkmainemail = "SELECT * FROM tblcontacts WHERE email= '$email' ";
	    $reslmainArr = $this->dbc->get_rows($chkmainemail);
	    if(sizeof($reslmainArr)>0){
	        self::sendResponse("2", "Password required"  );
	        die;
	    }else{
	        
	        $chkdemail = "SELECT * FROM tbeguest_users WHERE email= '$email' AND deleted=1 ";
	        $resldArr = $this->dbc->get_rows($chkdemail);
	        if(sizeof($resldArr)>0){
    	        self::sendResponse("3", "Account deactivated, More info please contact now!"  );
    	        die;
    	    }
	        
	        
	        self::sendResponse("1", "Guest User" );
	        die;
	        
	        
	        
	    }
	    
	}
	
	
	
	
	
	
	public function guestUserlogin(){

		$commentUserEmail = $_REQUEST['UserEmail'];

		//Save gust user here
		$chkemail = "SELECT * FROM tbeguest_users WHERE email= '$commentUserEmail' ";
		$reslArr = $this->dbc->get_rows($chkemail);
		$guestUserId = $reslArr[0]['id'];
		$guestLoginName = $reslArr[0]['name'];
		$guestLoginEmail = $reslArr[0]['email'];
		$guestLoginPhone = $reslArr[0]['phone'];

		
		if(sizeof($reslArr)>0){
		    $_SESSION["GUESTUSERDETAILS"] = $reslArr[0];
		  //  print_r($reslArr);die;
		      //  if(!isset($_COOKIE['guestLoginData'])) {
		      //  setcookie('guestLoginData', $reslArr, time() + (86400 * 30), "/");
		        setcookie('guestLoginId', $guestUserId, time() + (86400 * 30), "/");
		        setcookie('guestLoginName', $guestLoginName, time() + (86400 * 30), "/");
		        setcookie('guestLoginEmail', $guestLoginEmail, time() + (86400 * 30), "/");
		        setcookie('guestLoginPhone', $guestLoginPhone, time() + (86400 * 30), "/");
		        
		        
		        $recentActivity = new Dashboard(true);
	
        // 		$timestamp = time(); // Get the current timestamp
        // 		$encodedString = base64_encode($timestamp . "_".$projId);
        // 		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;
        
        		$activityMeg2 = "New Login Alert";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$guestUserId,"#");
		        
		        
				$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=15 AND `active`=1 ";
				$mailTemplate = $this->dbc->get_rows($sqlM);

				//send mail here
				$subject = $mailTemplate[0]['subject'];

				$html = $mailTemplate[0]['mail_body'];

				$html = str_replace("--username",$guestLoginName,$html);
				$html = str_replace("--mailId",$guestLoginEmail,$html);
				$html = str_replace("--phoneno",$guestLoginPhone,$html);

				$send = new sendMails(true);
				$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $guestLoginName, $guestLoginEmail );
		        
		        
                    // setcookie('guestLoginData', $reslArr[0]);
                    // $_COOKIE['guestLoginData'] = $reslArr[0];
                // }
                // print_r($_COOKIE['guestLoginId']);die;
				self::sendResponse("1", $reslArr[0] );
		}else{
		    self::sendResponse("0", "No user data"  );
		}

	}
	
	public function validateSCOTP(){

		$commentUserEmail = $_REQUEST['UserEmail'];
		$token = $_REQUEST['token'];
		
		$selCounty = $_REQUEST['selCounty'];
	    $selState = $_REQUEST['selState'];
	    
		
		$chkemail = "SELECT * FROM tbeguest_users WHERE email= '$commentUserEmail' ";
		$reslArr = $this->dbc->get_rows($chkemail);
		$saveToken = $reslArr[0]['token'];
		
		if($token == $saveToken){
		    	$qry = "UPDATE `tbeguest_users` SET active=1 WHERE `email`='$commentUserEmail' ";
				$this->dbc->update_row($qry);
				
				setcookie('guestLoginId', $reslArr[0]['id'], time() + (86400 * 30), "/");
		        setcookie('guestLoginName', $reslArr[0]['name'], time() + (86400 * 30), "/");
		        setcookie('guestLoginEmail', $reslArr[0]['email'], time() + (86400 * 30), "/");
		        setcookie('guestLoginPhone', $reslArr[0]['phone'], time() + (86400 * 30), "/");
		        
		         $getState = "SELECT id FROM tblstate WHERE state= '$selState' ";
		        $statereslArr = $this->dbc->get_rows($getState);
		        
		        setcookie('user_state_val', $statereslArr[0]['id'], time() + (86400 * 30), "/");
		        
		         setcookie('user_state_name', $selState, time() + (86400 * 30), "/");
                setcookie('user_county_val', $selCounty, time() + (86400 * 30), "/");
		        
		        
		         $recentActivity = new Dashboard(true);
	
        		$activityMeg2 = "Welcome to Machooos International. You are a guest user.";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$reslArr[0]['id'],"#");
        		
        		$activityMeg = "User ".$reslArr[0]['name']."(guest user) create new account";
                $recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$selCounty,$statereslArr[0]['id']);
				
				if(!isset($_COOKIE['guestLoginData'])) {
                    setcookie('guestLoginData', $reslArr[0]);
                    $_COOKIE['guestLoginData'] = $reslArr[0];
                }
				self::sendResponse("1", "Otp verified"  );
		}else{
		    self::sendResponse("0", "Invalid Otp"  );
		}
	}
	
	
	public function validateOTP(){

		$commentUserEmail = $_REQUEST['UserEmail'];
		$token = $_REQUEST['token'];

		$chkemail = "SELECT * FROM tbeguest_users WHERE email= '$commentUserEmail' ";
		$reslArr = $this->dbc->get_rows($chkemail);
		$saveToken = $reslArr[0]['token'];
		
		if($token == $saveToken){
		    	$qry = "UPDATE `tbeguest_users` SET active=1 WHERE `email`='$commentUserEmail' ";
				$this->dbc->update_row($qry);
				
				setcookie('guestLoginId', $reslArr[0]['id'], time() + (86400 * 30), "/");
		        setcookie('guestLoginName', $reslArr[0]['name'], time() + (86400 * 30), "/");
		        setcookie('guestLoginEmail', $reslArr[0]['email'], time() + (86400 * 30), "/");
		        setcookie('guestLoginPhone', $reslArr[0]['phone'], time() + (86400 * 30), "/");
		        
		         $recentActivity = new Dashboard(true);
	
        // 		$timestamp = time(); // Get the current timestamp
        // 		$encodedString = base64_encode($timestamp . "_".$projId);
        // 		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;
        
        		$activityMeg2 = "Welcome to Machooos International. You are a guest user.";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$reslArr[0]['id'],"#");
				
				if(!isset($_COOKIE['guestLoginData'])) {
                    setcookie('guestLoginData', $reslArr[0]);
                    $_COOKIE['guestLoginData'] = $reslArr[0];
                }
				self::sendResponse("1", "Otp verified"  );
		}else{
		    self::sendResponse("0", "Invalid Otp"  );
		}
	}

	public function getGuestUserDetails(){

		$commentUserEmail = $_REQUEST['UserEmail'];

		//Save gust user here
		$chkemail = "SELECT * FROM tblguest_user WHERE email= '$commentUserEmail'";
		$reslArr = $this->dbc->get_rows($chkemail);
		
		if(sizeof($reslArr)>0){
		    
				self::sendResponse("1", $reslArr[0]  );
		}else{
		    self::sendResponse("0", "No user data"  );
		}



	}
	
	public function updateMainComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		$qry = "UPDATE `tbeproject_comments` SET `comment`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment in signature album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = "Your signature album ".$prjName." main comment is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$userID,$encodedStringUrl);



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function updateReplyComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		
		$qry = "UPDATE `tbecomment_reply` SET `comment_reply`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id left join tbecomment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];
		

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment reply in signature album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = "Your signature album ".$prjName." comment reply is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,$encodedStringUrl);



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function deleteProjectComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `tbeproject_comments` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , b.id as userID , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$userID = $AlbumList[0]['userID'];
		$projId = $AlbumList[0]['projId'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $commentUserName." deleted comment in signature album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = $commentUserName." deleted comment in your signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$userID,$encodedStringUrl);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}

	public function getProjectComments(){
		$projId = $_REQUEST['projId'];
		$numberOfDisedCmt = $_REQUEST['numberOfDisedCmt'];
		
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];

		$qry = "SELECT a.*,b.user_id,(SELECT COUNT(*) FROM tbecomment_reply
        WHERE comment_id = a.id AND deleted = 0 ) AS commentCount , (SELECT `status` FROM signature_album_comment_like
        WHERE comment_id = a.id AND user_id='$user_id_like' AND user_type='$user_type_val' ) AS commentLikeStatus , (SELECT COUNT(*) FROM signature_album_comment_like
        WHERE comment_id = a.id AND `status`=1 ) AS commentLikeCount , (SELECT COUNT(*) FROM signature_album_comment_like
        WHERE comment_id = a.id AND `status`=2 ) AS commentDislikeCount FROM `tbeproject_comments` a left join tbesignaturealbum_projects b on a.project_id = b.id WHERE a.status = 1 AND a.deleted = 0 AND a.project_id = '$projId'  ORDER BY a.id DESC LIMIT $numberOfDisedCmt ";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function addLikeDislikeComment(){
	    
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$commentId = (int)$_REQUEST["commentId"];
		
		$sts ="Remove like/dislike";
		if($status == 1) $sts ="Liked";
		else if($status == 2) $sts ="Dislike";
		
	
	    $sql1 = "SELECT * FROM signature_album_comment_like WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
	    $AlbumList = $this->dbc->get_rows($sql1);
	    
	    if(sizeof($AlbumList) > 0 ){
	        $vs = "UPDATE `signature_album_comment_like` SET `status`='$status'  WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val'  ";
	        $result = $this->dbc->update_row($vs);
	    }else{
	        $vs = "INSERT INTO `signature_album_comment_like`(`comment_id`, `user_id` , `user_type`,`status` ) VALUES ('$commentId','$user_id_like','$user_type_val','$status')";
	        $result = $this->dbc->insert_row($vs);
	    }
	    
	    $sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , b.id as userID , a.id as projId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$userID = $AlbumList[0]['userID'];
		$projId = $AlbumList[0]['projId'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $sts." comment in signature album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = $sts." comment in your signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,$encodedStringUrl);
	    
	 
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error");
	}

	public function getCommentsReply(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `tbecomment_reply` WHERE deleted = 0 AND comment_id = '$commentId'  ORDER BY id ASC";

		//echo $qry;

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function deleteCommentReply(){
	    $commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `tbecomment_reply` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);


		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id left join tbecomment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Delete comment reply user ".$commentUserName." (".$commentUserPhone.",".$commentUserEmail.") signature album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function editComments(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `tbeproject_comments` WHERE deleted = 0 AND id = $commentId";

		

		$result = $this->dbc->get_rows($qry);
// echo $result;die;
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No comment found");
	}

	public function getPendingComments(){
		$projId = $_REQUEST['projId'];
		// die("I am here");
		$qry = "SELECT * FROM `tbeproject_comments` WHERE status = 0 AND deleted = 0 AND project_id = '$projId' ORDER BY id DESC";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}

	public function approveComments(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `tbeproject_comments` SET `status`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);
		
		
		
		$sql = "SELECT * FROM tbeproject_comments WHERE id=$commentId";
		$CmtList = $this->dbc->get_rows($sql);
		$projId = $CmtList[0]['project_id'];
		
		$username = $CmtList[0]['name'];
		$mailId = $CmtList[0]['email'];
		$imogiText = $CmtList[0]['comment'];
		
		$user_type = $CmtList[0]['user_type'];
		$commented_user_id = $CmtList[0]['commented_user_id'];
		
		
		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$userID = $AlbumList[0]['userID'];
		
		
		$qryA = "INSERT INTO `user_guest_comment_approval`(`user_type`, `user_id`, `guest_id` ) VALUES ('$user_type','$userID','$commented_user_id')";

		$this->dbc->insert_row($qryA);
		
		
		$recentActivity = new Dashboard(true);
		$activityMeg = $eventUser." accept comment for signature album ".$prjName." for user ".$username;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=18 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];

		$html = $mailTemplate[0]['mail_body'];

		$html = str_replace("--username",$username,$html);
		$html = str_replace("--album_name",$prjName,$html);
		$html = str_replace("--comment",$imogiText,$html);
		$html = str_replace("--name",$eventUser,$html);
		
		$server_url = $this->$SERVER_HTTP_URL;
		
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = $server_url.'signature_album_sa.php?pId='.$encodedString;
		$html = str_replace("--link",$encodedStringUrl,$html);
	
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );
		
		
		
		if($user_type ==2){
		    
        		$activityMeg2 = "Your comment accept for signature album";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$commented_user_id,$encodedStringUrl);
		}
		
		
		
		
		

		if($result != "")self::sendResponse("1", "Comment approved successfully");
        else self::sendResponse("2", "Not updated data");
	}

	public function deleteComments(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `tbeproject_comments` SET `deleted`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);

		if($result != "")self::sendResponse("1", "Comment deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}

	public function saveCommentsReply(){
	    
		$commentId = $_REQUEST['commentId'];
		$commentsReply = $_REQUEST['commentsReply'];
		$created_Id = $_REQUEST['created_by'];
		$userType = $_REQUEST['userType'];
		$commentedUserName = $_REQUEST['commentedUserName'];
		
		$user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
		
		
// 		$email = $_REQUEST['email'];
// 		$Phno = $_REQUEST['Phno'];

    	if($userType == 2){
		    $usersql = "SELECT * FROM tbeguest_users WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['name'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phone'];
		}else{
		    $usersql = "SELECT * FROM tblcontacts WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['firstname'].' '.$userDtls[0]['lastname'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phonenumber'];
		}
		



		$sql1 = "SELECT a.project_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , b.id as userID , a.id as projId,c.user_type as cmtUserType, c.commented_user_id as  cmtUserId FROM tbesignaturealbum_projects a left join `tblcontacts` b on b.id = a.user_id left join tbeproject_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['project_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$userID = $AlbumList[0]['userID'];
		$projId = $AlbumList[0]['projId'];
		
		$qry = "INSERT INTO `tbecomment_reply`(`comment_id`, `comment_reply`, `created_by` , `phone_no` , `email` ,`userId`,`user_type`,`commented_user_id`,`prj_user_id`,`commentedUserName`) VALUES ('$commentId','$commentsReply','$created_by','$Phno','$email','$created_Id','$user_type_val','$user_id_like','$userID','$commentedUserName')";

		$result = $this->dbc->insert_row($qry);

		$recentActivity = new Dashboard(true);
		$activityMeg = $created_by." replyed comment signature album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'signature_album_sa.php?pId='.$encodedString;

		$activityMeg1 = $created_by." replyed your signature album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "reply" ,$userID,$encodedStringUrl);
		
	

// 		setcookie('commentUserName', $created_by, time() + (86400 * 30), "/");
// 		setcookie('commentUserEmail', $email, time() + (86400 * 30), "/");
// 		setcookie('commentUserPhone', $Phno, time() + (86400 * 30), "/");

	    $cmtUserType = $AlbumList[0]['cmtUserType'];
		$cmtUserId = $AlbumList[0]['cmtUserId'];
		
		if($cmtUserType == 2){
		    
		   
        		$activityMeg2 = $created_by." replyed your comment for signature album ".$prjName;
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$cmtUserId,$encodedStringUrl);
		    
		}


		// die($result);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");
	}

	public function sendEnqiryMail(){
		
		$send = new sendMails(true);
		$eventUser = $_REQUEST['eventUser'];
		$eventUserEmail = $_REQUEST['eventUserEmail'];
		$eventType = $_REQUEST['eventType'];
		$eventDate = $_REQUEST['eventDate'];
		$eventWhere = $_REQUEST['eventWhere'];
		$guestsCount = $_REQUEST['guestsCount'];
		// $occasionType = $_REQUEST['occasionType'];
		$comments = $_REQUEST['comments'];


		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=3 AND mail_template=11 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		if(sizeof($mailTemplate) == 1){
			$subject = $mailTemplate[0]['subject'];

			$html = $mailTemplate[0]['mail_body'];
			$html = str_replace("--eventUser",$eventUser,$html);
			$html = str_replace("--mailId",$eventUserEmail,$html);
			$html = str_replace("--eventType",$eventType,$html);
			$html = str_replace("--eventDate",$eventDate,$html);
			$html = str_replace("--eventWhere",$eventWhere,$html);
			$html = str_replace("--guestsCount",$guestsCount,$html);
			// $html = str_replace("--occasionType",$occasionType,$html);
			$html = str_replace("--comments",$comments,$html);

		}else{
			
			// die(" I am here !");
			$subject = "Enquiry mail from website";
			$html = '<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="padding: 0 2.5em; text-align: center; padding-bottom: 3em;">
					<div class="text">
						<h2>Hi, I am '.$eventUser.'</h2>
					</div>
				</td>
			</tr>
			</table>
			<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="text-align: left;">
						
						<div class="text-author">
							<table>
								<tr>
									<td style="padding-bottom: 20px;">email</td>
									<td style="padding-bottom: 20px;">: '.$eventUserEmail.'</td>
								</tr>
								<tr>
									<td style="padding-bottom: 20px;">Event Type</td>
									<td style="padding-bottom: 20px;">: '.$eventType.'</td>
								</tr>
								<tr>
									<td style="padding-bottom: 20px;">Event Date</td>
									<td style="padding-bottom: 20px;">: '.$eventDate.'</td>
								</tr>
								<tr>
									<td style="padding-bottom: 20px;">Where</td>
									<td style="padding-bottom: 20px;">: '.$eventWhere.'</td>
								</tr>
								<tr>
									<td style="padding-bottom: 20px;">Guests Count</td>
									<td style="padding-bottom: 20px;">: '.$guestsCount.'</td>
								</tr>
								
							</table>
							<h3 class="name" style="text-align: left; padding: 10px">'.$comments.'</h3>
							<!--<span class="position">CEO, Founder at e-Verify</span>
							<p><a href="#" class="btn btn-primary">Accept Request</a></p>
							<p><a href="#" class="btn-custom">Ignore Request</a></p>-->
					</div>
					</td>
				</tr>
			</table>';


		}

		
		$mailRes = $send->sendMail($subject ,$eventUser, $eventUserEmail , $html ,"Machoose International" , "enquirywebmachoos@gmail.com"  );

		$qry = "INSERT INTO `tbeenquiry_data`(`customer_name`, `customer_email`, `event_type`, `event_date`, `event_place`, `guest_count`, `comments`) VALUES ('$eventUser','$eventUserEmail','$eventType','$eventDate','$eventWhere','$guestsCount','$comments')";

		$recentActivity = new Dashboard(true);
		$activityMeg = "New enquiry from ".$eventUser." (".$eventUserEmail.") ";
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

		$result = $this->dbc->insert_row($qry);
		// die($result);
		if($result != "")self::sendResponse("1", "Thanks! We're on it and will reply soon.");
        else self::sendResponse("2", "Not inserted data");
		// die($mailRes);
		
	}
	
	
	
	
	
	
	
	
	public function saveFilmComments(){
	    
	    $imogiText = $_REQUEST['imogiText'];
	    
	    $user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
		
		$projId_id_like = $_REQUEST['projId_id_like'];
		
		$logginUserName = $_REQUEST['logginUserName'];
		$userphonenumber = $_REQUEST['userphonenumber'];
		$useremail = $_REQUEST['useremail'];
		
		$prjtUserId = $_REQUEST['prjtUserId'];
		
		if($commentId !=""){
		    
		}else{
		    
		    $user_data = get_session();
    		
    		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , b.id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId_id_like ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['tittle'];
			$userID = $AlbumList[0]['userID'];
			$mailId = $AlbumList[0]['email'];
			
			
    		if(isset($user_data['userID']) && $user_data['userID'] > 0) {
    			$status = 1;
    		}else{
    			$status = 0;
    			
    			$queapproval = "SELECT id FROM `user_guest_comment_approval` WHERE `user_type`='$user_type_val' AND `user_id`='$userID' AND `guest_id`='$user_id_like' ";
    			$queapprovalList = $this->dbc->get_rows($queapproval);
    			if(sizeof($queapprovalList) >0 ) $status = 1;

    		}
    		
    		$qry = "INSERT INTO `films_comments`(`project_id`, `name`, `email`, `phone`, `comment`,`status`, created_by,`user_type`,`commented_user_id`) VALUES ($projId_id_like,'$logginUserName','$useremail','$userphonenumber','$imogiText','$status', $user_id_like ,$user_type_val , $user_id_like )";
    	
    		$result = $this->dbc->insert_row($qry);
    		$result = array(
                "data" => $result,
                "approval_status" => $status
            );
            
            
            
			$recentActivity = new Dashboard(true);
			$activityMeg = $logginUserName." commented wedding film ".$prjName." for ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");

			$activityMeg1 = $logginUserName." commented your wedding film ".$prjName;
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "comment" ,$userID,'wedding_films.php');
			
			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=20 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];

			$html = $mailTemplate[0]['mail_body'];

			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--album_name",$prjName,$html);
			$html = str_replace("--email",$useremail,$html);
			$html = str_replace("--phone",$userphonenumber,$html);
			$html = str_replace("--comment",$imogiText,$html);
			$html = str_replace("--name",$logginUserName,$html);
		
			$send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );
		    
		    
		    
		}
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}
	
	
	
	
	
	
	public function getFilmsProjectComments(){
		$projId = $_REQUEST['projId'];
		$numberOfDisedCmt = $_REQUEST['numberOfDisedCmt'];
		
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];

		$qry = "SELECT a.*,b.user_id,(SELECT COUNT(*) FROM film_comment_reply
        WHERE comment_id = a.id AND deleted = 0 ) AS commentCount , (SELECT `status` FROM film_comment_like
        WHERE comment_id = a.id AND user_id='$user_id_like' AND user_type='$user_type_val' ) AS commentLikeStatus , (SELECT COUNT(*) FROM film_comment_like
        WHERE comment_id = a.id AND `status`=1 ) AS commentLikeCount , (SELECT COUNT(*) FROM film_comment_like
        WHERE comment_id = a.id AND `status`=2 ) AS commentDislikeCount FROM `films_comments` a left join wedding_films b on a.project_id = b.id WHERE a.status = 1 AND a.deleted = 0 AND a.project_id = '$projId'  ORDER BY a.id DESC LIMIT $numberOfDisedCmt ";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	
	
	public function editFilmComments(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `films_comments` WHERE id = $commentId";

		

		$result = $this->dbc->get_rows($qry);
// echo $result;die;
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No comment found");
	}
	
	
	
	
	public function updateFilmMainComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		$qry = "UPDATE `films_comments` SET `comment`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment in wedding film ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
	
		$activityMeg1 = "Your wedding film ".$prjName." main comment is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "edit" ,$userID,"wedding_films.php");



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	
	public function deleteFilmProjectComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `films_comments` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $commentUserName." deleted comment in wedding film ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

	

		$activityMeg1 = $commentUserName." deleted comment in your wedding film ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$userID,"wedding_films.php");

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	
	public function addFilmLikeDislikeComment(){
	    
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$commentId = (int)$_REQUEST["commentId"];
		
		$sts ="Remove like/dislike";
		if($status == 1) $sts ="Liked";
		else if($status == 2) $sts ="Dislike";
		
	
	    $sql1 = "SELECT * FROM film_comment_like WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
	    $AlbumList = $this->dbc->get_rows($sql1);
	    
	    if(sizeof($AlbumList) > 0 ){
	        $vs = "UPDATE `film_comment_like` SET `status`='$status'  WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val'  ";
	        $result = $this->dbc->update_row($vs);
	    }else{
	        $vs = "INSERT INTO `film_comment_like`(`comment_id`, `user_id` , `user_type`,`status` ) VALUES ('$commentId','$user_id_like','$user_type_val','$status')";
	        $result = $this->dbc->insert_row($vs);
	    }
	    
	    $sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $sts." comment in wedding film ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = $sts." comment in your wedding film ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,"wedding_films.php");
	    
	 
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error");
	}
	
	
	public function saveFilmCommentsReply(){
	    
		$commentId = $_REQUEST['commentId'];
		$commentsReply = $_REQUEST['commentsReply'];
		$created_Id = $_REQUEST['created_by'];
		$userType = $_REQUEST['userType'];
		$commentedUserName = $_REQUEST['commentedUserName'];
		
		$user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
	
    	if($userType == 2){
		    $usersql = "SELECT * FROM tbeguest_users WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['name'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phone'];
		}else{
		    $usersql = "SELECT * FROM tblcontacts WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['firstname'].' '.$userDtls[0]['lastname'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phonenumber'];
		}
		



		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID,c.user_type as cmtUserType,c.commented_user_id as cmtUserId FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];
	
		
		$qry = "INSERT INTO `film_comment_reply`(`comment_id`, `comment_reply`, `created_by` , `phone_no` , `email` ,`userId`,`user_type`,`commented_user_id`,`prj_user_id`,`commentedUserName`) VALUES ('$commentId','$commentsReply','$created_by','$Phno','$email','$created_Id','$user_type_val','$user_id_like','$userID','$commentedUserName')";

		$result = $this->dbc->insert_row($qry);

		$recentActivity = new Dashboard(true);
		$activityMeg = $created_by." replyed comment wedding album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");

		$activityMeg1 = $created_by." replyed your wedding film ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "reply" ,$userID,"wedding_films.php");
		
			
		$cmtUserType = $AlbumList[0]['cmtUserType'];
		$cmtUserId = $AlbumList[0]['cmtUserId'];
		
		if($cmtUserType == 2){
		    
		    	
        		$timestamp = time(); // Get the current timestamp
        		$encodedString = base64_encode($timestamp . "_".$projId);
        		$encodedStringUrl = $server_url.'wedding_film_view.php?pId='.$encodedString;
		    
		    
        		$activityMeg2 = $created_by." replyed your comment for wedding film ".$prjName;
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$cmtUserId,$encodedStringUrl);
		    
		}
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");
	}
	
	public function getFilmCommentsReply(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `film_comment_reply` WHERE deleted = 0 AND comment_id = '$commentId'  ORDER BY id ASC";

		//echo $qry;

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function updateFilmReplyComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		
		$qry = "UPDATE `film_comment_reply` SET `comment_reply`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);
		
		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id left join film_comment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];
		

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment reply in wedding film ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
	
		$activityMeg1 = "Your wedding film ".$prjName." comment reply is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,"wedding_films.php");



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function deleteFilmCommentReply(){
	    $commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `film_comment_reply` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);


		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id left join films_comments c on a.id = c.project_id left join film_comment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Delete comment reply user ".$commentUserName." wedding film ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function getFilmPendingComments(){
		$projId = $_REQUEST['projId'];
		// die("I am here");
		$qry = "SELECT * FROM `films_comments` WHERE status = 0 AND deleted = 0 AND project_id = '$projId' ORDER BY id DESC";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function deleteFilmComment(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `films_comments` SET `deleted`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);

		if($result != "")self::sendResponse("1", "Comment deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	public function approveFilmComments(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `films_comments` SET `status`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);
		
		
		
		$sql = "SELECT * FROM films_comments WHERE id=$commentId";
		$CmtList = $this->dbc->get_rows($sql);
		$projId = $CmtList[0]['project_id'];
		
		$username = $CmtList[0]['name'];
		$mailId = $CmtList[0]['email'];
		$imogiText = $CmtList[0]['comment'];
		
		$user_type = $CmtList[0]['user_type'];
		$commented_user_id = $CmtList[0]['commented_user_id'];
		
		
		$sql1 = "SELECT a.tittle , b.firstname, b.lastname, b.email , b.id as userID FROM wedding_films a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['tittle'];
		$userID = $AlbumList[0]['userID'];
		
		
		$qryA = "INSERT INTO `user_guest_comment_approval`(`user_type`, `user_id`, `guest_id` ) VALUES ('$user_type','$userID','$commented_user_id')";

		$this->dbc->insert_row($qryA);
		
		
		$recentActivity = new Dashboard(true);
		$activityMeg = $eventUser." accept comment for wedding film ".$prjName." for user ".$username;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=21 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];

		$html = $mailTemplate[0]['mail_body'];

		$html = str_replace("--username",$username,$html);
		$html = str_replace("--album_name",$prjName,$html);
		$html = str_replace("--comment",$imogiText,$html);
		$html = str_replace("--name",$eventUser,$html);
		
		$server_url = $this->$SERVER_HTTP_URL;
		
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = $server_url.'wedding_film_view.php?pId='.$encodedString;
		$html = str_replace("--link",$encodedStringUrl,$html);
	
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );
		
		if($user_type ==2){
		    
        		$activityMeg2 = "Your comment accept for wedding film";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$commented_user_id,$encodedStringUrl);
		}
		
		

		if($result != "")self::sendResponse("1", "Comment approved successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	
	
	
	
	
	
	public function saveOAComments(){
	    
	    $imogiText = $_REQUEST['imogiText'];
	    
	    $user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
		
		$projId_id_like = $_REQUEST['projId_id_like'];
		
		$logginUserName = $_REQUEST['logginUserName'];
		$userphonenumber = $_REQUEST['userphonenumber'];
		$useremail = $_REQUEST['useremail'];
		
		$prjtUserId = $_REQUEST['prjtUserId'];
		
		if($commentId !=""){
		    
		}else{
		    
		    $user_data = get_session();
    		
    		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId_id_like ";

			$AlbumList = $this->dbc->get_rows($sql1);
			$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
			$prjName = $AlbumList[0]['event_name'];
			$userID = $AlbumList[0]['userID'];
			$mailId = $AlbumList[0]['email'];
			
			
    		if(isset($user_data['userID']) && $user_data['userID'] > 0) {
    			$status = 1;
    		}else{
    			$status = 0;
    			
    			$queapproval = "SELECT id FROM `user_guest_comment_approval` WHERE `user_type`='$user_type_val' AND `user_id`='$userID' AND `guest_id`='$user_id_like' ";
    			$queapprovalList = $this->dbc->get_rows($queapproval);
    			if(sizeof($queapprovalList) >0 ) $status = 1;

    		}
    		
    		$qry = "INSERT INTO `onl_alb_comments`(`project_id`, `name`, `email`, `phone`, `comment`,`status`, created_by,`user_type`,`commented_user_id`) VALUES ($projId_id_like,'$logginUserName','$useremail','$userphonenumber','$imogiText','$status', $user_id_like ,$user_type_val , $user_id_like )";
    	
    		$result = $this->dbc->insert_row($qry);
    		$result = array(
                "data" => $result,
                "approval_status" => $status
            );
            
            
            
			$recentActivity = new Dashboard(true);
			$activityMeg = $logginUserName." commented online album ".$prjName." for ".$eventUser;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create");
			
			$timestamp = time(); // Get the current timestamp
    		$encodedString = base64_encode($timestamp . "_".$projId_id_like);
    		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

			$activityMeg1 = $logginUserName." commented your online album ".$prjName;
			$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "comment" ,$userID,$encodedStringUrl);
			
			$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=22 AND `active`=1 ";
			$mailTemplate = $this->dbc->get_rows($sqlM);

			//send mail here
			$subject = $mailTemplate[0]['subject'];

			$html = $mailTemplate[0]['mail_body'];

			$html = str_replace("--username",$eventUser,$html);
			$html = str_replace("--album_name",$prjName,$html);
			$html = str_replace("--email",$useremail,$html);
			$html = str_replace("--phone",$userphonenumber,$html);
			$html = str_replace("--comment",$imogiText,$html);
			$html = str_replace("--name",$logginUserName,$html);
		
			$send = new sendMails(true);
			$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );
		    
		    
		    
		}
		
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}
	
	
		
	public function getOAProjectComments(){
		$projId = $_REQUEST['projId'];
		$numberOfDisedCmt = $_REQUEST['numberOfDisedCmt'];
		
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];

		$qry = "SELECT a.*,b.user_id,(SELECT COUNT(*) FROM onl_albm_comment_reply
        WHERE comment_id = a.id AND deleted = 0 ) AS commentCount , (SELECT `status` FROM onl_alb_comment_like
        WHERE comment_id = a.id AND user_id='$user_id_like' AND user_type='$user_type_val' ) AS commentLikeStatus , (SELECT COUNT(*) FROM onl_alb_comment_like
        WHERE comment_id = a.id AND `status`=1 ) AS commentLikeCount , (SELECT COUNT(*) FROM onl_alb_comment_like
        WHERE comment_id = a.id AND `status`=2 ) AS commentDislikeCount FROM `onl_alb_comments` a left join tbevents_data b on a.project_id = b.id WHERE a.status = 1 AND a.deleted = 0 AND a.project_id = '$projId'  ORDER BY a.id DESC LIMIT $numberOfDisedCmt ";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function editOAComments(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `onl_alb_comments` WHERE id = $commentId";

		

		$result = $this->dbc->get_rows($qry);
// echo $result;die;
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No comment found");
	}
	
		
	public function updateOAMainComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		$qry = "UPDATE `onl_alb_comments` SET `comment`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment in online album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;
	
		$activityMeg1 = "Your online album ".$prjName." main comment is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "edit" ,$userID,$encodedStringUrl);



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function deleteOAProjectComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `onl_alb_comments` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);

		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $commentUserName." deleted comment in online album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	

		$activityMeg1 = $commentUserName." deleted comment in your online album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "delete" ,$userID,$encodedStringUrl);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function saveOACommentsReply(){
	    
		$commentId = $_REQUEST['commentId'];
		$commentsReply = $_REQUEST['commentsReply'];
		$created_Id = $_REQUEST['created_by'];
		$userType = $_REQUEST['userType'];
		$commentedUserName = $_REQUEST['commentedUserName'];
		
		$user_type_val = $_REQUEST['user_type_val'];
		$user_id_like = $_REQUEST['user_id_like'];
	
    	if($userType == 2){
		    $usersql = "SELECT * FROM tbeguest_users WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['name'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phone'];
		}else{
		    $usersql = "SELECT * FROM tblcontacts WHERE id=$created_Id ";
		    $userDtls = $this->dbc->get_rows($usersql);
		    $created_by = $userDtls[0]['firstname'].' '.$userDtls[0]['lastname'];
    		$email = $userDtls[0]['email'];
    		$Phno = $userDtls[0]['phonenumber'];
		}
		



		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID,c.user_type as cmtUserType,c.commented_user_id as  cmtUserId FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];
		
		$qry = "INSERT INTO `onl_albm_comment_reply`(`comment_id`, `comment_reply`, `created_by` , `phone_no` , `email` ,`userId`,`user_type`,`commented_user_id`,`prj_user_id`,`commentedUserName`) VALUES ('$commentId','$commentsReply','$created_by','$Phno','$email','$created_Id','$user_type_val','$user_id_like','$userID','$commentedUserName')";

		$result = $this->dbc->insert_row($qry);

		$recentActivity = new Dashboard(true);
		$activityMeg = $created_by." replyed comment online album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
			$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	

		$activityMeg1 = $created_by." replyed your online film ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "reply" ,$userID,$encodedStringUrl);
		
		
				
		$cmtUserType = $AlbumList[0]['cmtUserType'];
		$cmtUserId = $AlbumList[0]['cmtUserId'];
		
		if($cmtUserType == 2){
		    
		   
        		$activityMeg2 = $created_by." replyed your comment for online album ".$prjName;
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$cmtUserId,$encodedStringUrl);
		    
		}
		
		
		
		
		
		
		
		

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");
	}
	
	public function getOACommentsReply(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `onl_albm_comment_reply` WHERE deleted = 0 AND comment_id = '$commentId'  ORDER BY id ASC";

		//echo $qry;

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function updateOAReplyComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		
		$qry = "UPDATE `onl_albm_comment_reply` SET `comment_reply`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);
		
		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id left join onl_albm_comment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];
		

		$recentActivity = new Dashboard(true);
		$activityMeg = "Edit comment reply in online album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
			
			$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	
		
	
		$activityMeg1 = "Your online album ".$prjName." comment reply is edited ";
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,$encodedStringUrl);



		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function deleteOACommentReply(){
	    $commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `onl_albm_comment_reply` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);


		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id left join onl_albm_comment_reply d on c.id = d.comment_id WHERE d.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = "Delete comment reply user ".$commentUserName." online album ".$prjName." for user ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete");

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function addOALikeDislikeComment(){
	    
		$user_type_val = (int) $_REQUEST["user_type_val"];
		$user_id_like = (int) $_REQUEST["user_id_like"];
		$status = (int)$_REQUEST["status"];
		$commentId = (int)$_REQUEST["commentId"];
		
		$sts ="Remove like/dislike";
		if($status == 1) $sts ="Liked";
		else if($status == 2) $sts ="Dislike";
		
	
	    $sql1 = "SELECT * FROM onl_alb_comment_like WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val' ";
	    $AlbumList = $this->dbc->get_rows($sql1);
	    
	    if(sizeof($AlbumList) > 0 ){
	        $vs = "UPDATE `onl_alb_comment_like` SET `status`='$status'  WHERE comment_id='$commentId' AND user_id='$user_id_like' AND user_type='$user_type_val'  ";
	        $result = $this->dbc->update_row($vs);
	    }else{
	        $vs = "INSERT INTO `onl_alb_comment_like`(`comment_id`, `user_id` , `user_type`,`status` ) VALUES ('$commentId','$user_id_like','$user_type_val','$status')";
	        $result = $this->dbc->insert_row($vs);
	    }
	    
	    $sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , c.name , c.email , c.phone , a.id as projId, a.user_id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id left join onl_alb_comments c on a.id = c.project_id WHERE c.id=$commentId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$commentUserName = $AlbumList[0]['name'];
		$commentUserPhone = $AlbumList[0]['phone'];
		$commentUserEmail = $AlbumList[0]['email'];
		$projId = $AlbumList[0]['projId'];
		$userID = $AlbumList[0]['userID'];

		$recentActivity = new Dashboard(true);
		$activityMeg = $sts." comment in online album ".$prjName." for ".$eventUser;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
			
			$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = 'online_album_sa.php?pId='.$encodedString;

	

		$activityMeg1 = $sts." comment in your online album ".$prjName;
		$recentActivity->addUserRecentActivity($this->dbc , $activityMeg1 , "update" ,$userID,$encodedStringUrl );
	    
	 
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error");
	}
	
	public function getOAPendingComments(){
		$projId = $_REQUEST['projId'];
		// die("I am here");
		$qry = "SELECT * FROM `onl_alb_comments` WHERE status = 0 AND deleted = 0 AND project_id = '$projId' ORDER BY id DESC";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function deleteOAComment(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `onl_alb_comments` SET `deleted`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);

		if($result != "")self::sendResponse("1", "Comment deleted successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	public function approveOAComments(){
		$commentId = $_REQUEST['commentId'];
		$qry = "UPDATE `onl_alb_comments` SET `status`='1' WHERE id=$commentId";
		$result = $this->dbc->update_row($qry);
		
		
		
		$sql = "SELECT * FROM onl_alb_comments WHERE id=$commentId";
		$CmtList = $this->dbc->get_rows($sql);
		$projId = $CmtList[0]['project_id'];
		
		$username = $CmtList[0]['name'];
		$mailId = $CmtList[0]['email'];
		$imogiText = $CmtList[0]['comment'];
		
		$user_type = $CmtList[0]['user_type'];
		$commented_user_id = $CmtList[0]['commented_user_id'];
		
		
		$sql1 = "SELECT a.event_name , b.firstname, b.lastname, b.email , b.id as userID FROM tbevents_data a left join `tblcontacts` b on b.id = a.user_id WHERE a.id=$projId ";

		$AlbumList = $this->dbc->get_rows($sql1);
		$eventUser = $AlbumList[0]['firstname']." ".$AlbumList[0]['lastname'];
		$prjName = $AlbumList[0]['event_name'];
		$userID = $AlbumList[0]['userID'];
		
		
		$qryA = "INSERT INTO `user_guest_comment_approval`(`user_type`, `user_id`, `guest_id` ) VALUES ('$user_type','$userID','$commented_user_id')";

		$this->dbc->insert_row($qryA);
		
		
		$recentActivity = new Dashboard(true);
		$activityMeg = $eventUser." accept comment for online album ".$prjName." for user ".$username;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		
		
		$sqlM = "SELECT * FROM mail_templates WHERE deleted=0 AND mail_type=5 AND mail_template=23 AND `active`=1 ";
		$mailTemplate = $this->dbc->get_rows($sqlM);

		//send mail here
		$subject = $mailTemplate[0]['subject'];

		$html = $mailTemplate[0]['mail_body'];

		$html = str_replace("--username",$username,$html);
		$html = str_replace("--album_name",$prjName,$html);
		$html = str_replace("--comment",$imogiText,$html);
		$html = str_replace("--name",$eventUser,$html);
		
		$server_url = $this->$SERVER_HTTP_URL;
		
		
		$timestamp = time(); // Get the current timestamp
		$encodedString = base64_encode($timestamp . "_".$projId);
		$encodedStringUrl = $server_url.'online_album_sa.php?pId='.$encodedString;
		$html = str_replace("--link",$encodedStringUrl,$html);
		
		$send = new sendMails(true);
		$mailRes = $send->sendMail($subject , "Machoos International" , "machoos522@gmail.com" , $html , $eventUser, $mailId );
		
		if($user_type ==2){
		    
        		$activityMeg2 = "Your comment accept for online album";
        		$recentActivity->addGuestUserRecentActivity($this->dbc , $activityMeg2 , "create" ,$commented_user_id,$encodedStringUrl);
		}
		
		
		

		if($result != "")self::sendResponse("1", "Comment approved successfully");
        else self::sendResponse("2", "Not updated data");
	}
	
	
	
	
	
	

}

?>