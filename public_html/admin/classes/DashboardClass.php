<?php
class Dashboard {
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

	public function getCounts(){
       $data =array();
       
       $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city'];
       $state = $_SESSION['state'];
       $county_id = $_SESSION['county_id'];
           
       if($isAdmin){
            $sql = "SELECT t1.Count AS totalOnlineAlbum, t2.Count AS SignatureAlbum, t3.Count AS noOfCustomers ,t4.Count AS noOfWeddingFilms,t5.Count AS noOfDwdFiles FROM (SELECT COUNT(id) AS Count FROM tbevents_data WHERE `deleted` = 0 ) AS t1, (SELECT COUNT(id) AS Count FROM tbesignaturealbum_projects WHERE `deleted` = 0 ) AS t2, (SELECT COUNT(id) AS Count FROM tblcontacts WHERE `active` = 1 ) AS t3, (SELECT COUNT(id) AS Count FROM wedding_films WHERE `active`=0 ) AS t4,(SELECT COUNT(id) AS Count FROM dwd_count ) AS t5 "; 

       }else{
          
           
           if($manage_type == 'County'){
               // user type County
                $sql = "SELECT t1.Count AS totalOnlineAlbum, t2.Count AS SignatureAlbum, t3.Count AS noOfCustomers ,t4.Count AS noOfWeddingFilms,t5.Count AS noOfDwdFiles FROM (SELECT COUNT(tbevents_data.id) AS Count FROM tbevents_data LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbevents_data.deleted = 0 AND tblclients.country = '$county_id' ) AS t1, (SELECT COUNT(tbesignaturealbum_projects.id) AS Count FROM tbesignaturealbum_projects LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbesignaturealbum_projects.deleted = 0 AND tblclients.country = '$county_id' ) AS t2, (SELECT COUNT(tblcontacts.id) AS Count FROM tblcontacts LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblcontacts.active = 1 AND tblclients.country = '$county_id' ) AS t3, (SELECT COUNT(wedding_films.id) AS Count FROM wedding_films LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE wedding_films.active=0 AND tblclients.country = '$county_id' ) AS t4,(SELECT COUNT(id) AS Count FROM dwd_count ) AS t5 "; 
               
           }else if($manage_type == 'State'){
               // user type State
               $sql = "SELECT t1.Count AS totalOnlineAlbum, t2.Count AS SignatureAlbum, t3.Count AS noOfCustomers ,t4.Count AS noOfWeddingFilms,t5.Count AS noOfDwdFiles FROM (SELECT COUNT(tbevents_data.id) AS Count FROM tbevents_data LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbevents_data.deleted = 0 AND tblclients.state = '$state' ) AS t1, (SELECT COUNT(tbesignaturealbum_projects.id) AS Count FROM tbesignaturealbum_projects LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbesignaturealbum_projects.deleted = 0 AND tblclients.state = '$state' ) AS t2, (SELECT COUNT(tblcontacts.id) AS Count FROM tblcontacts LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblcontacts.active = 1 AND tblclients.state = '$state' ) AS t3, (SELECT COUNT(wedding_films.id) AS Count FROM wedding_films LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE wedding_films.active=0 AND tblclients.state = '$state' ) AS t4,(SELECT COUNT(id) AS Count FROM dwd_count ) AS t5 "; 
             
           }else {
               // user type City
                $sql = "SELECT t1.Count AS totalOnlineAlbum, t2.Count AS SignatureAlbum, t3.Count AS noOfCustomers ,t4.Count AS noOfWeddingFilms,t5.Count AS noOfDwdFiles FROM (SELECT COUNT(tbevents_data.id) AS Count FROM tbevents_data LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbevents_data.deleted = 0 AND tblclients.city = '$city' ) AS t1, (SELECT COUNT(tbesignaturealbum_projects.id) AS Count FROM tbesignaturealbum_projects LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tbesignaturealbum_projects.deleted = 0 AND tblclients.city = '$city' ) AS t2, (SELECT COUNT(tblcontacts.id) AS Count FROM tblcontacts LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblcontacts.active = 1 AND tblclients.city = '$city' ) AS t3, (SELECT COUNT(wedding_films.id) AS Count FROM wedding_films LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE wedding_films.active=0 AND tblclients.city = '$city' ) AS t4,(SELECT COUNT(id) AS Count FROM dwd_count ) AS t5 "; 
               
           }
           
           
       }
     
      
       $result = $this->dbc->get_rows($sql);
       
       if($isAdmin){
       
            $sql2 = "SELECT COUNT(id) AS Count FROM tbesignaturealbum_data WHERE `deleted`=0 AND project_folder_id IN (SELECT id FROM tbesignaturealbum_projects WHERE `deleted` = 0);";
       }else{
           
            if($manage_type == 'County'){
               // user type County
               $sql2 = "SELECT COUNT(tbesignaturealbum_data.id) AS Count FROM tbesignaturealbum_data LEFT JOIN tblcontacts on tbesignaturealbum_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid  WHERE tbesignaturealbum_data.deleted=0 AND tblclients.country = '$county_id'  AND tbesignaturealbum_data.project_folder_id IN (SELECT id FROM tbesignaturealbum_projects WHERE `deleted` = 0) ;";
               
           }else if($manage_type == 'State'){
               // user type State
               $sql2 = "SELECT COUNT(tbesignaturealbum_data.id) AS Count FROM tbesignaturealbum_data LEFT JOIN tblcontacts on tbesignaturealbum_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid  WHERE tbesignaturealbum_data.deleted=0 AND tblclients.state = '$state'  AND tbesignaturealbum_data.project_folder_id IN (SELECT id FROM tbesignaturealbum_projects WHERE `deleted` = 0) ;";
              
             
           }else {
               // user type City
               $sql2 = "SELECT COUNT(tbesignaturealbum_data.id) AS Count FROM tbesignaturealbum_data LEFT JOIN tblcontacts on tbesignaturealbum_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid  WHERE tbesignaturealbum_data.deleted=0 AND tblclients.city = '$city'  AND tbesignaturealbum_data.project_folder_id IN (SELECT id FROM tbesignaturealbum_projects WHERE `deleted` = 0) ;";
               
               
           }
           
       }
      
    //   print_r($sql2); die;
        $result2 = $this->dbc->get_rows($sql2);
    
        if($isAdmin){
              
              
              $sql3 = "SELECT COUNT(*) AS noOfOAView FROM tbevents_views";
              $result3 = $this->dbc->get_rows($sql3);
              
              $sql4 = "SELECT COUNT(*) AS noOfSAView FROM tbeproject_views";
              $result4 = $this->dbc->get_rows($sql4);
              
              $sql5 = "SELECT COUNT(*) AS noOfWFView FROM wedding_film_views";
              $result5 = $this->dbc->get_rows($sql5);
              
        }else{
             if($manage_type == 'County'){
               // user type County
               
                $sql3 = "SELECT COUNT(tbevents_views.id) AS noOfOAView FROM tbevents_views LEFT JOIN tbevents_data on tbevents_views.project_id = tbevents_data.id LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.country = '$county_id' ";
              $result3 = $this->dbc->get_rows($sql3);
              
              $sql4 = "SELECT COUNT(tbeproject_views.id) AS noOfSAView FROM tbeproject_views LEFT JOIN tbesignaturealbum_projects on tbeproject_views.project_id = tbesignaturealbum_projects.id LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.country = '$county_id' ";
              $result4 = $this->dbc->get_rows($sql4);
              
              $sql5 = "SELECT COUNT(wedding_film_views.id) AS noOfWFView FROM wedding_film_views LEFT JOIN wedding_films on wedding_film_views.project_id = wedding_films.id LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.country = '$county_id'  ";
              $result5 = $this->dbc->get_rows($sql5);
              
              
               
               }else if($manage_type == 'State'){
                   // user type State
                     $sql3 = "SELECT COUNT(tbevents_views.id) AS noOfOAView FROM tbevents_views LEFT JOIN tbevents_data on tbevents_views.project_id = tbevents_data.id LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.state = '$state' ";
              $result3 = $this->dbc->get_rows($sql3);

              $sql4 = "SELECT COUNT(tbeproject_views.id) AS noOfSAView FROM tbeproject_views LEFT JOIN tbesignaturealbum_projects on tbeproject_views.project_id = tbesignaturealbum_projects.id LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.state = '$state' ";
              $result4 = $this->dbc->get_rows($sql4);

              $sql5 = "SELECT COUNT(wedding_film_views.id) AS noOfWFView FROM wedding_film_views LEFT JOIN wedding_films on wedding_film_views.project_id = wedding_films.id LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.state = '$state'  ";
              $result5 = $this->dbc->get_rows($sql5);
              
        
               }else {
                   // user type City
                   
                    $sql3 = "SELECT COUNT(tbevents_views.id) AS noOfOAView FROM tbevents_views LEFT JOIN tbevents_data on tbevents_views.project_id = tbevents_data.id LEFT JOIN tblcontacts on tbevents_data.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.city = '$city' ";
              $result3 = $this->dbc->get_rows($sql3);
              
              $sql4 = "SELECT COUNT(tbeproject_views.id) AS noOfSAView FROM tbeproject_views LEFT JOIN tbesignaturealbum_projects on tbeproject_views.project_id = tbesignaturealbum_projects.id LEFT JOIN tblcontacts on tbesignaturealbum_projects.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.city = '$city' ";
              $result4 = $this->dbc->get_rows($sql4);
              
              $sql5 = "SELECT COUNT(wedding_film_views.id) AS noOfWFView FROM wedding_film_views LEFT JOIN wedding_films on wedding_film_views.project_id = wedding_films.id LEFT JOIN tblcontacts on wedding_films.user_id = tblcontacts.id LEFT JOIN tblclients on tblclients.userid = tblcontacts.userid WHERE tblclients.city = '$city'  ";
              $result5 = $this->dbc->get_rows($sql5);
                  
                   
               }
            
        }
      
      
      
       
       $data["totalOnlineAlbum"]=$result[0]['totalOnlineAlbum'];
       $data["SignatureAlbum"]=$result[0]['SignatureAlbum'];
       $data["noOfCustomers"]=$result[0]['noOfCustomers'];
       $data["noOfWeddingFilms"]=$result[0]['noOfWeddingFilms'];
       $data["noOfSignatureAlbumEvents"]=$result2[0]['Count'];
       $data["noOfDwdFiles"]=$result[0]['noOfDwdFiles'];
       
       
       $data["noOfSAView"]=$result4[0]['noOfSAView'];
       $data["noOfOAView"]=$result3[0]['noOfOAView'];
       $data["noOfWFView"]=$result5[0]['noOfWFView'];
 

       self::sendResponse("1",$data);
	}


  public function addRecentActivity($dbc , $mes , $action , $county_id ="" , $state_id="" , $city_id= "" , $is_complete = 0 ){
      
      if($county_id !="" && $county_id !="all"){
          $sqlC = "SELECT short_name FROM tblcountries WHERE country_id='$county_id' ";
          $resultC = $dbc->get_rows($sqlC);
          $mes = $mes." from ".$resultC[0]['short_name'];
      }
      
      if($state_id !="" && $state_id !="all"){
          $sqlS = "SELECT state FROM tblstate WHERE id='$state_id' ";
          $resultS = $dbc->get_rows($sqlS);
          $mes = $mes.", ".$resultS[0]['state'];
      }
      
       if($city_id !="" && $city_id !="all"){
          $sqlD = "SELECT city FROM tblcity WHERE id='$city_id' ";
          $resultD = $dbc->get_rows($sqlD);
          $mes = $mes.", ".$resultD[0]['city'];
      }

    $vs = "INSERT INTO `tblrecent_activity`(`task`,`action`,`county_id`,`state_id`,`city_id`,`is_complete`) VALUES ('$mes','$action','$county_id','$state_id','$city_id','$is_complete')";
		$dbc->insert_row($vs);
  }


  public function getRecentActivity(){

    $filter=$_REQUEST["filter"];
    
       $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city_id'];
       $state = $_SESSION['state_id'];
       $county_id = $_SESSION['county_id'];
       
        if($isAdmin){
            if($filter == 1){
              $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id  WHERE DATE(a.created_in) = CURDATE() ORDER BY a.created_in desc";
            }else if($filter == 2){
              $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id  WHERE a.created_in >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) ORDER BY a.created_in desc";
            }else{
              $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id ORDER BY a.created_in desc";
            }
            
        }else{
            
            if($manage_type == 'County'){
                       // user type County
                       
                        if($filter == 1){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE DATE(a.created_in) = CURDATE() and a.county_id='$county_id' ORDER BY a.created_in desc";
                        }else if($filter == 2){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.created_in >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) and a.county_id='$county_id' ORDER BY a.created_in desc";
                        }else{
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.county_id='$county_id' ORDER BY a.created_in desc";
                        }
                       
                      
                      
                   }else if($manage_type == 'State'){
                       // user type State
                       
                        if($filter == 1){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE DATE(a.created_in) = CURDATE() and a.state_id='$state' ORDER BY a.created_in desc";
                        }else if($filter == 2){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.created_in >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) and a.state_id='$state' ORDER BY a.created_in desc";
                        }else{
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.state_id='$state' ORDER BY a.created_in desc";
                        }
                      
                     
                   }else {
                       // user type City
                       
                        if($filter == 1){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE DATE(a.created_in) = CURDATE() and a.city_id='$city' ORDER BY a.created_in desc";
                        }else if($filter == 2){
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.created_in >= DATE_SUB(CURRENT_DATE, INTERVAL 1 WEEK) and a.city_id='$city' ORDER BY a.created_in desc";
                        }else{
                          $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.city_id='$city' ORDER BY a.created_in desc";
                        }
                       
			
                   }
            
            
            
            
        }

    

    $result = $this->dbc->get_rows($sql);
     
    if($result != "")self::sendResponse("1", $result);
    else self::sendResponse("2", "No data found");
   
  }
  
  
  public function getRecentActivityForSelectImage(){


       $isAdmin = $_SESSION['isAdmin'];
        $manage_type = $_SESSION['manage_type'];
       $city = $_SESSION['city_id'];
       $state = $_SESSION['state_id'];
       $county_id = $_SESSION['county_id'];
       
        if($isAdmin){
            $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id  WHERE a.is_complete=1  ORDER BY a.created_in desc";
            
        }else{
            
            if($manage_type == 'County'){
                       // user type County
                       
                        $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.is_complete=1  and a.county_id='$county_id' ORDER BY a.created_in desc";
                       
                      
                      
                   }else if($manage_type == 'State'){
                       // user type State
                       
                       $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.is_complete=1  and a.state_id='$state' ORDER BY a.created_in desc";
                     
                   }else {
                       // user type City
                       
                        $sql = "SELECT a.* , CURRENT_TIMESTAMP as nowtime,z.short_name as country ,b.state,c.city FROM tblrecent_activity a left join tblcountries z on z.country_id = a.county_id left join tblstate b on a.state_id = b.id left join tblcity c on a.city_id = c.id WHERE a.is_complete=1  and a.city_id='$city' ORDER BY a.created_in desc";
                       
			
                   }
            
            
            
            
        }

    

    $result = $this->dbc->get_rows($sql);
     
    if($result != "")self::sendResponse("1", $result);
    else self::sendResponse("2", "No data found");
   
  }
  
  
  
  
  

  public function addUserRecentActivity($dbc , $mes , $action ,$userId , $url ){
    $vs = "INSERT INTO `tblrecent_activity_user`(`task`,`action`,`user_id`,`url`) VALUES ('$mes','$action',$userId,'$url')";
		$dbc->insert_row($vs);
  }
  
   public function addGuestUserRecentActivity($dbc , $mes , $action ,$userId , $url ){
    $vs = "INSERT INTO `tblrecent_activity_user`(`task`,`action`,`user_id`,`url`,`user_type`) VALUES ('$mes','$action',$userId,'$url',2)";
		$dbc->insert_row($vs);
  }

  public function getUserNotification(){
    $user_id=$_REQUEST["user_id"];
    $user_type=$_REQUEST["user_type"];
   
    $sql = "SELECT * , CURRENT_TIMESTAMP as nowtime FROM `tblrecent_activity_user` WHERE user_id =$user_id AND user_type =$user_type ORDER BY created_in desc";
   
    $result = $this->dbc->get_rows($sql);

    if($result != "")self::sendResponse("1", $result);
    else self::sendResponse("2", "No data found");
   
  }

  public function setNotificationRead(){
    $notfy_id=$_REQUEST["notfy_id"];
    $updtqry = "UPDATE `tblrecent_activity_user` SET `read`=1 WHERE `id`=$notfy_id ";
		$result = $this->dbc->update_row($updtqry);
  }
  
public function setAllNotificationRead(){
    $userId=$_REQUEST["userId"];
    $userType=$_REQUEST["userType"];
    $updtqry = "UPDATE `tblrecent_activity_user` SET `read`=1 WHERE `user_id`=$userId AND `user_type`=$userType ";
		$result = $this->dbc->update_row($updtqry);
		self::sendResponse("1", $result);
  }
  
  public function deactivateGuestUser(){
    $userId=$_REQUEST["sel_id"];
    $val=$_REQUEST["val"];
    $updtqry = "UPDATE `tbeguest_users` SET `deleted`=$val WHERE `id`=$userId  ";
		$result = $this->dbc->update_row($updtqry);
		self::sendResponse("1", $result);
  }
  
  
   public function getGuestUsers(){

    $filter=$_REQUEST["filter"];

     $sql = "SELECT * , CURRENT_TIMESTAMP as nowtime FROM `tbeguest_users` WHERE active=1 ORDER BY created_in desc";
   
    $result = $this->dbc->get_rows($sql);
     
    if($result != "")self::sendResponse("1", $result);
    else self::sendResponse("2", "No data found");
   
  }
  
  public function getCronJobs(){

    $disType=$_REQUEST["disType"];
    if($disType == 1) $sql = "SELECT * FROM `cron_job_status` ORDER BY id desc";
    else if($disType == 2) $sql = "SELECT * FROM `cron_job_2_status` ORDER BY id desc";
    else $sql = "SELECT * FROM `cron_job_3_status` ORDER BY id desc";
     
   
    $result = $this->dbc->get_rows($sql);
     
    if($result != "")self::sendResponse("1", $result);
    else self::sendResponse("2", "No data found");
   
  }
  
  	public function addDownloadCount(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

	
		$vs = "INSERT INTO `dwd_count`(`IP` ) VALUES ('$ip')";
		$this->dbc->insert_row($vs);
	}


	
}

?>