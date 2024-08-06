<?php

require_once('DashboardClass.php');

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
		
		$data["county_id"]=$_REQUEST['selCounty'];
		$data["state_id"]=$_REQUEST['multipleSel'];
// 		$data["city_id"]=$_REQUEST['selCity'];
		


		// $data["import_image"]=$_REQUEST['import_image'];
		$description = str_replace("'", '"', $_REQUEST['long_description']);

		$data["long_description"]=$description;
		$data["active"]=0;


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


	    $isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];



		if($_REQUEST['id']=='' ){
			
			$recentActivity = new Dashboard(true);
			$tittletext = $_REQUEST['tittle'];
			$activityMeg = "New blog ".$tittletext." is created by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
			$result = $this->dbc->InsertUpdate($data, 'blogs');

		}else{

			$recentActivity = new Dashboard(true);
			$tittletext = $_REQUEST['tittle'];
			$activityMeg = "Blog ".$tittletext." is updated by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
			
			$data_id=array(); $data_id["id"]=$_REQUEST['id'];
			$result=$this->dbc->update_query($data, 'blogs', $data_id);
		}

		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "Error in saving data");

	}

	
	public function getBlogs(){
	    
	    	 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       $state_id = $_SESSION['state_id'];
		
		
			if($isAdmin){
		     
        		$sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
        	
        		    
        		}else{
        		    
        		    
        		      if($manage_type == 'County'){
                       // user type County
                        
		$sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and b.country_id ='$county_id' ORDER BY a.id DESC";
                       
                   }else {
                       // user type City
                       
                       $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and FIND_IN_SET($state_id, a.state_id) ORDER BY a.id DESC";
                       
// 		$sql = "SELECT a.*,b.short_name as county_id,c.state as state_id,d.city as city_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and d.city ='$city' ORDER BY a.id DESC";
                       
                       
                       
                   }
        	}
	    
	    
	    
	   
		$result = $this->dbc->get_rows($sql);
    	$data=array("Blogs"=>$result);
		self::sendResponse("1", $data);
	}
	
	
	public function getBlogsNew(){
	    
	    	 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       $state_id = $_SESSION['state_id'];
		
		
			if($isAdmin){
		     
        		$sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
        	
        		    
        		}else{
        		    
        		    
        		      if($manage_type == 'County'){
                       // user type County
                        
		$sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and b.country_id ='$county_id' ORDER BY a.id DESC";
                       
                   }else {
                       // user type City
                       
                       $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and FIND_IN_SET($state_id, a.state_id) ORDER BY a.id DESC";
                       
// 		$sql = "SELECT a.*,b.short_name as county_id,c.state as state_id,d.city as city_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and d.city ='$city' ORDER BY a.id DESC";
                       
                       
                       
                   }
        	}
	    
	    
	    
	   
		$result = $this->dbc->get_rows($sql);
		if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
		
		
	}
	
	public function getBlogs1(){
		$sql = "SELECT a.*,b.short_name as county_id,c.state as state_id,d.city as city_id FROM blogs a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
			$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
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

		$dlt_id = $_REQUEST['id'];
		$sql1 = "SELECT * FROM `blogs` WHERE id=$dlt_id ";
		$blogsList = $this->dbc->get_rows($sql1);
		$tittle = $blogsList[0]['tittle'];
		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       
       $activityMeg = "Blog ".$tittle." is deleted by ".$isUsername;
	
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

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

		$active = $_REQUEST['active'];
		if($active == 1) $sts = "active";
		else $sts = "in-active";

		$blogsId = $_REQUEST['id'];
		$sql1 = "SELECT * FROM `blogs` WHERE id=$blogsId ";
		$blogsList = $this->dbc->get_rows($sql1);
		$tittle = $blogsList[0]['tittle'];
		$recentActivity = new Dashboard(true);
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];
       
       $activityMeg = $isUsername." set blog ".$tittle." to ".$sts;
		$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);


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
		
			
		$data["county_id"]=$_REQUEST['selCounty'];
		$data["state_id"]=$_REQUEST['multipleSel'];
// 		$data["city_id"]=$_REQUEST['selCity'];
		

		$description = str_replace("'", '"', $_REQUEST['description']);

		$data["description"]=$description;
		$data["small_description"]=$_REQUEST['small_description'];

		

		if($_REQUEST['FileImageVideo']==1){

			if(isset($_FILES['image_story']['name']) && $_FILES['image_story']['name']!=''){
				$target_1 = 'storyImages/story_'.time().$_FILES['image_story']['name'];
				
				// move_uploaded_file($_FILES['image_story']['tmp_name'], $target_1);
				
				
				
        			 $imagePath1 = $_FILES['image_story']['tmp_name'];
        
                    $targetFilePath1 = $target_1;
                    
                    $targetSizeMB = 2;
                    
                    // Convert target size from MB to bytes
                    $targetSizeBytes = $targetSizeMB * 1024 * 1024;
                
                    // Load the image
                    $image = imagecreatefromjpeg($imagePath1);
                
                    // Initialize quality and compression variables
                    $quality = 90;
                    $compressedImage = null;
                
                    // Loop until the image size is less than the target size
                    while (filesize($imagePath1) > $targetSizeBytes) {
                        // Create a temporary image with reduced quality
                        ob_start();
                        imagejpeg($image, null, $quality);
                        $compressedImageData = ob_get_clean();
                
                        // Save the compressed image data to a temporary file
                        $tempImagePath = tempnam(sys_get_temp_dir(), 'compressed_image');
                        file_put_contents($tempImagePath, $compressedImageData);
                
                        // Check the size of the temporary compressed image
                        $tempFileSize = filesize($tempImagePath);
                
                        if ($tempFileSize <= $targetSizeBytes) {
                            // The temporary image is within the target size
                            $compressedImage = imagecreatefromjpeg($tempImagePath);
                            unlink($imagePath1); // Delete the original image
                            rename($tempImagePath, $imagePath1); // Replace with the compressed image
                            break;
                        }
                
                        // Reduce the quality and continue the loop
                        $quality -= 10;
                
                        // If quality becomes too low, break the loop to prevent infinite looping
                        if ($quality < 10) {
                            break;
                        }
                    }
                
                    // Clean up resources
                    imagedestroy($compressedImage);
                
                   
                    move_uploaded_file($imagePath1, $targetFilePath1);
        				
				$data['image_story']=$target_1;
				
				
				
				
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
		
		$isAdmin = $_SESSION['isAdmin'];
       $isCounty_id = $_SESSION['county_id'];
       $isState_id = $_SESSION['state_id'];
       $isCity_id = $_SESSION['city_id'];
       $isUsername = $_SESSION['Username'];

		if($_REQUEST['id']=='' ){
			$recentActivity = new Dashboard(true);
			$main_tittle = $_REQUEST['main_tittle'];
			$activityMeg = "New storie ".$main_tittle." is created by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "create",$isCounty_id,$isState_id,$isCity_id);
		}else{
			$recentActivity = new Dashboard(true);
			$main_tittle = $_REQUEST['main_tittle'];
			$activityMeg = "Storie ".$main_tittle." is updated by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);
		}

		


		$this->dbc->InsertUpdate($data, 'stories');

		
	}
	
	
	public function getStories1(){
	    
	    	 $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
       $state_id = $_SESSION['state_id'];
		
		 if($isAdmin){
		     
		     	$sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM stories a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
		
		
		     
		 }else{
		     
		      if($manage_type == 'County'){
               // user type County
               
               $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM stories a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and b.country_id ='$county_id' ORDER BY a.id DESC";
             
           }else {
               // user type City
              
              	 $sql = "SELECT a.*,b.short_name as county_id,d.city as city_id ,(SELECT GROUP_CONCAT(c.state) FROM tblstate c WHERE FIND_IN_SET(c.id, a.state_id) > 0) AS state_id FROM stories a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 and FIND_IN_SET($state_id, a.state_id) ORDER BY a.id DESC";
		
               
               
           }
		     
		 }
	    
	    
	    
	    
	
		
		
			$result = $this->dbc->get_rows($sql);
      
        if($result != "")self::sendResponse("1", $result);
        else self::sendResponse("2", "No data found");
	}
	
	
	
	

	public function getStories(){
		$sql = "SELECT a.*,b.short_name as county_id,c.state as state_id,d.city as city_id FROM stories a left join tblcountries b on a.county_id = b.country_id left join tblstate c on a.state_id = c.id left join tblcity d on a.city_id = d.id WHERE a.deleted=0 ORDER BY a.id DESC";
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

			$active = $_REQUEST['active'];
			if($active == 1) $sts = "active";
			else $sts = "in-active";

			$storiesId = $_REQUEST['id'];
			$sql1 = "SELECT * FROM `stories` WHERE id=$storiesId ";
			$storiesList = $this->dbc->get_rows($sql1);
			$tittle = $storiesList[0]['main_tittle'];
			$recentActivity = new Dashboard(true);
			
			$isAdmin = $_SESSION['isAdmin'];
           $isCounty_id = $_SESSION['county_id'];
           $isState_id = $_SESSION['state_id'];
           $isCity_id = $_SESSION['city_id'];
           $isUsername = $_SESSION['Username'];
			
			
			$activityMeg = $isUsername." set storie ".$tittle." to ".$sts;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "update",$isCounty_id,$isState_id,$isCity_id);


			self::sendResponse("1", 'Record update Successfully');
		}else{
			self::sendResponse("0", 'Failed in update Record');
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

			$dlt_id = $_REQUEST['id'];

			$sql1 = "SELECT * FROM `stories` WHERE id=$dlt_id ";
			$storiesList = $this->dbc->get_rows($sql1);
			$tittle = $storiesList[0]['main_tittle'];

			$recentActivity = new Dashboard(true);
			
				$isAdmin = $_SESSION['isAdmin'];
           $isCounty_id = $_SESSION['county_id'];
           $isState_id = $_SESSION['state_id'];
           $isCity_id = $_SESSION['city_id'];
           $isUsername = $_SESSION['Username'];
			
			$activityMeg = "Storie ".$tittle." is deleted by ".$isUsername;
			$recentActivity->addRecentActivity($this->dbc , $activityMeg , "delete",$isCounty_id,$isState_id,$isCity_id);

			self::sendResponse("1", 'Record Deleted Successfully');


		}else{
			self::sendResponse("0", 'Failed in Deleting Record');
		}
	}


	

}

?>