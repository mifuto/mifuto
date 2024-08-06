<?php
class DBConnection extends mysqli{
  private $queryresult;
  private $intFields = array("display","custom","StatusOn");
  //Create DB connection
  function __construct($host=HOST,$dbUser=DB_USER,$dbPass=DB_PASS,$dbName=DB_NAME) {
    
    @parent::__construct($host,$dbUser,$dbPass,$dbName);
    if (mysqli_connect_error()){
      die("Failed to connect to MySQL: " .mysqli_connect_error());
    }
  }

  // insert_query($data, $table);
  // update_query($data, $table, $whereFields);

  public function get_rows($sql){
    // print_r($sql);
    $result = $this->query($sql);
    
    return $result->fetch_all(MYSQLI_ASSOC);
  }
  
  public function update_row($sql){
    $result = $this->query($sql);
    $RowDetail=array("Insert_Id"=> $this->insert_id, "AffectedRows"=>$this->affected_rows);
    return $RowDetail;  
  }

  public function insert_row($sql){
    // print_r($sql);die;
    // $result = $this->query('INSERT INTO `tbeproject_comments`(`project_id`, `name`, `email`, `phone`, `comment`) VALUES (14, "sdsd", "aa@mail.com", "9878767655", "asasasasasa 😘")');
    $result = $this->query($sql);
    return $this->insert_id;
  }


  public function inserted_row($sql){
    $result = $this->query($sql);
    $RowDetail=array($this->affected_rows, $this->insert_id);
    return $RowDetail;      
  }



  //Added By Merrin
  public function insert_query($data, $table){
    // echo "kfkkfkkfkfkf";
    $query = 'INSERT INTO ' . $table . ' (';
    
    foreach($data as $key => $value){
      $query .= $key . ', ';
      
    }

    // while(list($key, $columns) = each($data)) {
    //   echo $key;
    //   $query .= $columns . ', ';
    // };
   
    $query = substr($query, 0, -2) . ') values (';
    reset($data);
    
    // while (list($key, $value) = each($data)) {
    foreach($data as $key => $value){
      // print_r($query);
      switch ((string)$value) {
        case 'now()':
          $query .= 'now(), ';
          break;
        case 'null':
          $query .= 'null, ';
          break;
        default:
          $query .= '\'' . addslashes($value) . '\', ';
          break;
      }
    }
    $query = substr($query, 0, -2) . ')';
    
    // die($query);
    $this->query($query);
    // die($query);

    $InsertId=$this->insert_id;
    $AffectedRows=$this->affected_rows;
    $RowDetail=array("InsertId"=>$InsertId, "AffectedRows"=>$AffectedRows);
    // print_r($RowDetail);
    // die();
    return $RowDetail; 
  }

  public function update_query($mas, $table, $whereFields){
    if(is_array($whereFields)){
      while(list($idn,$idv)=each($whereFields)){
          if( in_array($idn, $this->intFields)) $where[] = $idn."= $idv";
          else $where[] = $idn."='$idv'";
      }
    }else{
        $where[] = "$whereFields";
    }
    while(list($k,$v)=each($mas)){
      if( in_array($k, $this->intFields)) $to[] = $k."=$v";
      else if($v == 'now()' || $v == 'null')  $to[] = $k."=$v";
      else $to[] = $k."='$v'";
    }
    $sql = "UPDATE $table SET ".implode(',',$to)." WHERE ".implode(" AND ",$where);
    // die($sql);
    $result = $this->query($sql);
    $RowDetail=array("AffectedRows"=>$this->affected_rows);
    // print_r($RowDetail);
    // die();
    return $RowDetail;
  }

  public function InsertUpdate($data, $table){
    // print_r($_REQUEST['save']);
    $save=$_REQUEST['save'];
    if($save=='add' || $save=='insert'){
      // $data["created_by"]=$_SESSION['ForStarUser']['id'];
      $data["created_date"]=date('Y-m-d H:i:s');
      $Inserted = $this->insert_query($data, $table);
    //   print_r($Inserted);die;
      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0) ajaxResponse("1", 'Record Inserted Successfully');
      else ajaxResponse("0", 'Failed In Inserting Data');
    }else if($save=='update'){
      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
      // $data["updated_by"]=$_SESSION['ForStarUser']['id'];
      $data["updated_date"]=date('Y-m-d H:i:s');
      $Updated = $this->update_query($data, $table, $data_id);
      if($Updated['AffectedRows']>0) ajaxResponse("1", 'Record Updated Successfully');
      else ajaxResponse("0", 'Failed In Updating Data');
    }
  }
  
  public function InsertUpdateNew($data, $table){
    // print_r($_REQUEST['save']);
    $save=$_REQUEST['save'];
    if($save=='add' || $save=='insert'){
      // $data["created_by"]=$_SESSION['ForStarUser']['id'];
      $data["created_date"]=date('Y-m-d H:i:s');
      $Inserted = $this->insert_query($data, $table);
      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0) ajaxResponse("1", $Inserted['InsertId']);
      else ajaxResponse("0", 'Failed In Inserting Data');
    }else if($save=='update'){
      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
      // $data["updated_by"]=$_SESSION['ForStarUser']['id'];
      $data["updated_date"]=date('Y-m-d H:i:s');
      $Updated = $this->update_query($data, $table, $data_id);
      if($Updated['AffectedRows']>0) ajaxResponse("1", 'Record Updated Successfully');
      else ajaxResponse("0", 'Failed In Updating Data');
    }
  }
  
  public function InsertUpdateNewReturn($data, $table){
    // print_r($_REQUEST['save']);
    $save=$_REQUEST['save'];
    if($save=='add' || $save=='insert'){
      // $data["created_by"]=$_SESSION['ForStarUser']['id'];
      $data["created_date"]=date('Y-m-d H:i:s');
      $Inserted = $this->insert_query($data, $table);
      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0) return $Inserted['InsertId'];
      else return "";
    }else if($save=='update'){
      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
      // $data["updated_by"]=$_SESSION['ForStarUser']['id'];
      $data["updated_date"]=date('Y-m-d H:i:s');
      $Updated = $this->update_query($data, $table, $data_id);
      if($Updated['AffectedRows']>0) ajaxResponse("1", 'Record Updated Successfully');
      else ajaxResponse("0", 'Failed In Updating Data');
    }
  }


  public function history_insert($data, $table, $his_table, $his_col_name){
    $UserId=$_SESSION['ForStarUser']['id'];
    $date=date('Y-m-d H:i:s');
    $save=$_REQUEST['save'];
    $InsertId=0;
    $this->autocommit(FALSE);
    if($save=='add' || $save=='insert'){
      $data["created_by"]=$UserId;
      $data["created_date"]=$date;
      $Inserted = $this->insert_query($data, $table );
      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0) $InsertId=$Inserted['InsertId'];
      else ajaxResponse("0", 'Failed In Inserting Data');
    }else if($save=='update'){
      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
      $data["updated_by"]=$UserId;
      $data["updated_date"]=$date;
      $Updated = $this->update_query($data, $table, $data_id);
      if($Updated['AffectedRows']>0) $InsertId=$_REQUEST['id'];
      else ajaxResponse("0", 'Failed In Updating Data');
    }
    $data[$his_col_name]=$InsertId;
    $result=$this->insert_query($data, $his_table);
    if($result['AffectedRows']>0 && $result['InsertId']>0 && $InsertId>0){
      $this->commit();
      ajaxResponse("1", 'Record Updated Sucessfully');
    }else{
      $this->rollback();
      ajaxResponse("0", 'Failed in Saving Record');
    }
  }

  public function delete_query($table, $whereFields){
    if(is_array($whereFields)){
      while(list($idn,$idv)=each($whereFields)){
          if( in_array($idn, $this->intFields)) $where[] = $idn."= $idv";
          else $where[] = $idn."='$idv'";
      }
    }else{
        $where[] = "$whereFields";
    }
   
    $sql = "DELETE FROM  $table   WHERE ".implode(" AND ",$where);
    // die($sql);
    $result = $this->query($sql);
    $RowDetail=array("AffectedRows"=>$this->affected_rows);
    return $RowDetail;
  }




}

?>