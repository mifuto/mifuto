<?php

require_once('sendMailClass.php');
include("../get_session.php");



// $mail = new PHPMailer(true);
class Comments {
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

	public function saveComments(){
		// die($_REQUEST);
		$projId = (int) $_REQUEST['projId'];
		$commentId = (int) $_REQUEST['commentId'];
		$commentUserName = $_REQUEST['commentUserName'];
		$commentUserEmail = $_REQUEST['commentUserEmail'];
		$commentUserPhone = $_REQUEST['commentUserPhone'];
		$imogiText = $_REQUEST['imogiText'];
		$commentUserPhone = $_REQUEST['commentUserPhone'];
		
		if($commentId !=""){
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
    
    		setcookie('commentUserName', $commentUserName, time() + (86400 * 30), "/");
    		setcookie('commentUserEmail', $commentUserEmail, time() + (86400 * 30), "/");
    		setcookie('commentUserPhone', $commentUserPhone, time() + (86400 * 30), "/");

		}
// 		die($result);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not inserted data");

	}
	
	public function updateMainComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$imogiText = $_REQUEST['imogiText'];
		$qry = "UPDATE `tbeproject_comments` SET `comment`='$imogiText' WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}
	
	public function deleteProjectComment(){
		$commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `tbeproject_comments` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Not deleted comment.");
	}

	public function getProjectComments(){
		$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `tbeproject_comments` WHERE status = 1 AND deleted = 0 AND project_id = '$projId'  ORDER BY id DESC";

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}

	public function getCommentsReply(){
		$commentId = $_REQUEST['commentId'];

		//$projId = $_REQUEST['projId'];

		$qry = "SELECT * FROM `tbecomment_reply` WHERE deleted = 0 AND comment_id = '$commentId'  ORDER BY id DESC";

		//echo $qry;

		$result = $this->dbc->get_rows($qry);

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No users found");
	}
	
	public function deleteCommentReply(){
	    $commentId = (int) $_REQUEST['commentId'];
		$qry = "UPDATE `tbecomment_reply` SET `deleted`=1 WHERE `id`= $commentId";
		$result = $this->dbc->update_row($qry);
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
		$created_by = $_REQUEST['created_by'];
		$email = $_REQUEST['email'];
		$Phno = $_REQUEST['Phno'];

		$qry = "INSERT INTO `tbecomment_reply`(`comment_id`, `comment_reply`, `created_by` , `phone_no` , `email` ) VALUES ('$commentId','$commentsReply','$created_by','$Phno','$email')";

		$result = $this->dbc->insert_row($qry);

		setcookie('commentUserName', $created_by, time() + (86400 * 30), "/");
		setcookie('commentUserEmail', $email, time() + (86400 * 30), "/");
		setcookie('commentUserPhone', $Phno, time() + (86400 * 30), "/");


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

		$result = $this->dbc->insert_row($qry);
		// die($result);
		if($result != "")self::sendResponse("1", "Thanks! We're on it and will reply soon.");
        else self::sendResponse("2", "Not inserted data");
		// die($mailRes);
		
	}

}

?>