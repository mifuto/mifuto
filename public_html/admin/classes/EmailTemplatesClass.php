<?php

class EmailTemplates {
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

    

	public function addTemplates(){
		$data=array();
		$data["mail_type"]=$_REQUEST['mail_type'];
		$data["subject"]=$_REQUEST['subject'];
		
		$description = str_replace("'", '"', $_REQUEST['description']);

		$data["mail_body"]=$description;

		$data["mail_template"]=$_REQUEST['mail_template'];




		if($_REQUEST['id']=='' ){
			
		}else{
			$temId = $_REQUEST['id'];
			$sqlm = "SELECT * FROM `mail_templates` WHERE id=$temId ";
			$mList = $this->dbc->get_rows($sqlm);
			$mail_template = $mList[0]['mail_template'];

			$sql1 = "SELECT a.mail_type , b.mail_template  FROM mail_type a left join mail_template_names b on a.id = b.mail_type  WHERE b.id=$mail_template ";
			$mail_templateList = $this->dbc->get_rows($sql1);
			$mail_typeName = $mail_templateList[0]['mail_type'];
			$mail_templateName = $mail_templateList[0]['mail_template'];

			$recentActivity = new Dashboard(true);
			$activityMeg = "Change ".$mail_typeName." ".$mail_templateName." mail template";
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update");
		}

		
		// $save=$_REQUEST['save'];
		// if($save=='add' || $save=='insert'){
			$this->dbc->InsertUpdate($data, 'mail_templates');
		// }else if($save=='update'){
		// 	$id =$_REQUEST['id'];
		// 	$subject=$_REQUEST['subject'];
		// 	$description=$_REQUEST['description'];

		// 	$sql1 = "UPDATE mail_templates SET	`subject` = '$subject' , mail_body = '$description'  WHERE id = $id ";
		// 	echo $sql1;
		// 	$result = $this->dbc->query($sql1);

		// 	if(isset($result))self::sendResponse("1", "Record Updated Successfully");
        // 	else self::sendResponse("2", "Failed In Updating Data");

		// }
		
		
	}

	public function getTemplatesforEdit(){
		$id = $_REQUEST['id'];
		$sql = "SELECT * FROM mail_templates WHERE id=$id ";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Templates"=>$result[0]);
		self::sendResponse("1", $data);
	}

	
	public function getTemplates(){

		$sel_mail_type = $_REQUEST['sel_mail_type'];
		$sel_mail_template = $_REQUEST['sel_mail_template'];

        if($sel_mail_template == ""){
			//,CONCAT( SUBSTRING(a.mail_body, 1, 60) , ' ...') as view_mail_body
            if($sel_mail_type == ""){
				$sql = "SELECT a.* ,b.mail_type as mail_type_view,c.mail_template as mail_template_view FROM mail_templates a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id WHERE a.deleted=0 ORDER BY a.active DESC";
			}else{
				$sql = "SELECT a.* ,b.mail_type as mail_type_view,c.mail_template as mail_template_view FROM mail_templates a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id WHERE a.deleted=0 AND a.mail_type = $sel_mail_type  ORDER BY a.active DESC";
			}
        }else{
            $sql = "SELECT a.*,b.mail_type as mail_type_view,c.mail_template as mail_template_view FROM mail_templates a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id WHERE a.deleted=0 AND a.mail_template = $sel_mail_template ORDER BY a.active DESC";
        }


		$result = $this->dbc->get_rows($sql);
    	$data=array("Templates"=>$result);
		self::sendResponse("1", $data);
	}

	public function deleteTemplates(){
		$data=array();
		$data["deleted"]=1;
		$data["updated_date"]=date('Y-m-d H:i:s');

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];

		$Deleted=$this->dbc->update_query($data, 'mail_templates', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Deleted['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

	public function setTemplatesActiveInactive(){
		$data=array();
		$data["active"]=$_REQUEST['active'];
		$type=$_REQUEST['type'];

		if($_REQUEST['active'] == 1){
			$query = "UPDATE `mail_templates` SET `active`=0 WHERE `mail_template`='$type' ";
			$resulte = $this->dbc->update_row($query);
		}

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];
		$Update=$this->dbc->update_query($data, 'mail_templates', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Update['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

  

    public function getMailTypeForSel(){
		$sql = "SELECT * FROM mail_type WHERE active=1";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Type"=>$result);
		self::sendResponse("1", $data);
	}

	public function getmailtemplateForSel(){
		$mail_type = $_REQUEST['mail_type'];
		$sql = "SELECT * FROM mail_template_names WHERE active=1 and mail_type=$mail_type ";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Type"=>$result);
		self::sendResponse("1", $data);
	}

    public function LinkFields(){
		$data=array();
		$data["mail_type"]=$_REQUEST['mail_type'];
        $data["mail_field"]=$_REQUEST['mail_field'];
		$data["mail_template"]=$_REQUEST['mail_template'];
		$this->dbc->InsertUpdate($data, 'mail_field');

		
	}

    public function getLinkFields(){
        $sel_mail_type = $_REQUEST['sel_mail_type'];
		$sel_mail_template = $_REQUEST['sel_mail_template'];

        if($sel_mail_template == ""){
            if($sel_mail_type == ""){
				$sql = "SELECT a.id,a.mail_field,a.active,b.mail_type,c.mail_template FROM mail_field a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id order by a.id desc";
			}else{
				$sql = "SELECT a.id,a.mail_field,a.active,b.mail_type,c.mail_template FROM mail_field a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id WHERE a.mail_type =$sel_mail_type  order by a.id desc";
			}
        }else{
            $sql = "SELECT a.id,a.mail_field,a.active,b.mail_type,c.mail_template FROM mail_field a left join mail_type b on a.mail_type = b.id left join mail_template_names c on a.mail_template = c.id WHERE a.mail_template =$sel_mail_template  order by a.id desc";
        }

		
		
		$result = $this->dbc->get_rows($sql);
    	$data=array("Type"=>$result);
		self::sendResponse("1", $data);
	}

    public function setMailFieldsActiveInactive(){
		$data=array();
		$data["active"]=$_REQUEST['active'];
		
      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];
		$Update=$this->dbc->update_query($data, 'mail_field', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Update['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

    public function deleteFields(){
		$data=array();
		$data["deleted"]=1;
		$data["updated_date"]=date('Y-m-d H:i:s');

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];

		$Deleted=$this->dbc->update_query($data, 'mail_field', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Deleted['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

    public function getMailFieldWithTypeid(){
        $mail_template = $_REQUEST['mail_template'];
		$sql = "SELECT * FROM mail_field WHERE active=1 and `mail_template`=$mail_template ";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Fields"=>$result);
		self::sendResponse("1", $data);
	}

    
	public function getMailTemplates(){
		$mail_type=$_REQUEST['mail_type'];
		$mail_template=$_REQUEST['mail_template'];

		$sql = "SELECT id FROM mail_templates WHERE deleted=0 AND mail_type='$mail_type' AND mail_template='$mail_template' AND `active`=1 ";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Templates"=>$result);
		self::sendResponse("1", $data);
	}








   

}

?>