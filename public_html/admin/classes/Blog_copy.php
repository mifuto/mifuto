<?php

class Blog {
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

	public function addBlog(){

		$data=array();
		$data["tittle"]=$_REQUEST['tittle'];
		$data["sub_tittle"]=$_REQUEST['sub_tittle'];
		$data["posted_date"]=$_REQUEST['posted_date'];
		$data["author"]=$_REQUEST['author'];
		$data["small_description"]=$_REQUEST['small_description'];
		


		// $data["import_image"]=$_REQUEST['import_image'];
		$description = str_replace("'", '"', $_REQUEST['long_description']);

		$data["long_description"]=$description;
		$data["active"]=0;
		$data['image']='';


		// three images
		// print_r($_FILES['import_image']['name']);
		// if(isset($_FILES['import_image']['name'])){
		// 	for($i=0;$i<count($_FILES["import_image"])-1;$i++){
		// 	    if($data['image']!='')$data['image'].=',';
		// 		// echo $_FILES['import_image']['name'][$i];
		// 		$target_1 = 'blogImages/blog_'.$_FILES['import_image']['name'][$i];
		// 	    move_uploaded_file($file_tmp=$_FILES["import_image"]["tmp_name"][$i], $target_1);
		// 	    $data['image'].=$target_1;

		// 	}
		// }
// Array ( [name] => Array ( [0] => 2.jpg ) [type] => Array ( [0] => image/jpeg ) [tmp_name] => Array ( [0] => C:\xampp\tmp\php3B9A.tmp ) [error] => Array ( [0] => 0 ) [size] => Array ( [0] => 551818 ) )
		// print_r($_FILES['import_image']);

		if(isset($_FILES['import_image']['name']) && $_FILES['import_image']['name']!=''){
			$target_1 = 'blogImages/img_'.time().$_FILES['import_image']['name'];
			$data['image']=$target_1;
			move_uploaded_file($_FILES['import_image']['tmp_name'], $target_1);
		}









		if($_REQUEST['FileImageVideo']==1){

			if(isset($_FILES['import_image']['name']) && $_FILES['import_image']['name']!=''){
				$target_1 = 'blogImages/img_'.time().$_FILES['import_image']['name'];
				$data['image']=$target_1;
				move_uploaded_file($_FILES['import_image']['tmp_name'], $target_1);
			}
		}else{		

			if($_REQUEST['FileVideoURL']==1){
				$split = explode('watch?v=',$_REQUEST['import_url']);
					$data['video']='https://www.youtube.com/embed/'.$split[1];
			}else{
				if(isset($_FILES['import_video']['name']) && $_FILES['import_video']['name']!=''){
					$target_1 = 'blogImages/vid_'.time().$_FILES['import_video']['name'];
					$data['video']=$target_1;
					move_uploaded_file($_FILES['import_video']['tmp_name'], $target_1);
				}
			}

		}


		



		if($_REQUEST['id']=='' ){
			$result = $this->dbc->InsertUpdate($data, 'blogs');
		}else{
			// echo 'ldibuflaisjud';
			$data_id=array(); $data_id["id"]=$_REQUEST['id'];
			$result=$this->dbc->update_query($data, 'blogs', $data_id);
		}

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");

	}

	
	public function getBlogs(){
		$sql = "SELECT * FROM blogs WHERE deleted=0 ORDER BY id DESC";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Blogs"=>$result);
		self::sendResponse("1", $data);
	}

	public function getBlog(){
		$sql = "SELECT * FROM blogs WHERE id=".$_REQUEST['id'] ;
		$result = $this->dbc->get_rows($sql);
    	$data=array("Blog"=>$result[0]);
		self::sendResponse("1", $data);
	}

	public function deleteBlog(){
		$data=array();
		$data["deleted"]=1;
		$data["deleted_date"]=date('Y-m-d H:i:s');

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];

		$Deleted=$this->dbc->update_query($data, 'blogs', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Deleted['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

	

	public function setActiveInactive(){
		$data=array();
		$data["active"]=$_REQUEST['active'];

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];
		$Update=$this->dbc->update_query($data, 'blogs', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Update['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}










	public function addStory(){
		$data=array();
		$data["event_date"]=$_REQUEST['event_date'];
		$data["event_place"]=$_REQUEST['event_place'];
		$data["main_tittle"]=$_REQUEST['main_tittle'];

		$description = str_replace("'", '"', $_REQUEST['description']);

		$data["description"]=$description;
		$data["small_description"]=$_REQUEST['small_description'];

		

		if($_REQUEST['FileImageVideo']==1){

			if(isset($_FILES['image_story']['name']) && $_FILES['image_story']['name']!=''){
				$target_1 = 'storyImages/story_'.time().$_FILES['image_story']['name'];
				$data['image_story']=$target_1;
				move_uploaded_file($_FILES['image_story']['tmp_name'], $target_1);
			}
		}else{		

			if($_REQUEST['FileVideoURL']==1){
				$split = explode('watch?v=',$_REQUEST['import_url']);
					$data['video']='https://www.youtube.com/embed/'.$split[1];
			}else{
				if(isset($_FILES['import_video']['name']) && $_FILES['import_video']['name']!=''){
					$target_1 = 'storyImages/vid_'.time().$_FILES['import_video']['name'];
					$data['video']=$target_1;
					move_uploaded_file($_FILES['import_video']['tmp_name'], $target_1);
				}
			}

		}


		$this->dbc->InsertUpdate($data, 'stories');

		
	}

	public function getStories(){
		$sql = "SELECT * FROM stories WHERE deleted=0 ORDER BY id DESC";
		$result = $this->dbc->get_rows($sql);
    	$data=array("Stories"=>$result);
		self::sendResponse("1", $data);
	}
	public function getStory(){
		$sql = "SELECT * FROM stories WHERE id=".$_REQUEST['id'];
		$result = $this->dbc->get_rows($sql);
    	$data=array("Story"=>$result[0]);
		self::sendResponse("1", $data);
	}
	public function setStoryActiveInactive(){
		$data=array();
		$data["active"]=$_REQUEST['active'];

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];
		$Update=$this->dbc->update_query($data, 'stories', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Update['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}

	public function deleteStory(){
		$data=array();
		$data["deleted"]=1;
		$data["deleted_date"]=date('Y-m-d H:i:s');

      	$data_id=array(); $data_id["id"]=$_REQUEST['id'];

		$Deleted=$this->dbc->update_query($data, 'stories', $data_id);

		// print_r($Deleted['AffectedRows']);
		if($Deleted['AffectedRows']>0){
			self::sendResponse("1", 'Record Deleted Successfully');
		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}


	

}

?>