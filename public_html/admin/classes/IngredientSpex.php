<?php
class IngredientSpex {
  	private $dbc;
  	private $error_message;
  	function __construct($dbc){
	    $this->dbc = $dbc;
	    $this->error_message="";
		isset($_REQUEST["tab"])? $tab=$_REQUEST["tab"] : $tab="";
	    if($tab=="IngredientCategory") 					$this->Table ='ing_main_category';

		else if($tab=="IngredientSubCategory") 			$this->Table ='ing_category';
		else if($tab=="IngredientCriticalParameters") 	$this->Table ='ing_critical_parameters';
		else if($tab=="IngredientMaterialType") 		$this->Table ='ing_material_type';
		else if($tab=="IngredientBrand") 				$this->Table ='ing_brand';
		else if($tab=="IngredientList") 				$this->Table ='ingredient_list';
		else if($tab=="IngredientSupplierList") 		$this->Table ='ing_supplier';
		else if($tab=="IngredientSuppliers") 			$this->Table ='ing_supplier_link';

		// else if($tab=="IngredientMaster") 				$this->Table ='m_ingredient';
		// else if($tab=="IngredientRateMaster") 			$this->Table ='m_ingredient_rate';
		// else if($tab=="IngredientPhysicalStock") 		$this->Table ='m_physical_ingredient';
		
	}

	public static function sendResponse($status,$payload,$errorMsg=""){
		$resp = array();
		$resp["status"]=$status;
		if ( isset($errorMsg) && $errorMsg != "" ) $resp["error"]=$errorMsg;
		$resp["data"]=$payload;
		echo json_encode($resp);
		die();
	}	

	public function Confirm_IngredientSpex(){
		$id=$_REQUEST["id"];
		$sql = "UPDATE ".$this->Table." SET active='0' WHERE id='$id'";
		$result = $this->dbc->update_row($sql);
		if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		else self::sendResponse("2", "Failed");
	}

	public function Pending_IngredientSpex(){
		$id=$_REQUEST["id"];
		$sql = "UPDATE ".$this->Table." SET active='1' WHERE id='$id'";
		$result = $this->dbc->update_row($sql);
		if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		else self::sendResponse("2", "Failed");
	}
	
	public function Delete_IngredientSpex(){
		$ids=$_REQUEST['ids'];
		$id=implode("',' ", $ids);
		$sql= "UPDATE ".$this->Table." SET deleted='1', active='0', deleted_by=".$_SESSION['ForStarUser']['id'].",deleted_date=".date('Y-m-d H:i:s')." WHERE id IN ('$id')";
		$result = $this->dbc->update_row($sql);
		if($result['AffectedRows']>=1)self::sendResponse("1", "Updated Successfully");
		else self::sendResponse("2", "Failed");
	}

	public function CheckLinked_IngredientSpex(){
		$ids=$_REQUEST['ids'];
		$tab=$_REQUEST['tab'];
		$id=implode("',' ", $ids);
		if($tab=="IngredientCategory") {
		 	$sql = "SELECT ( 
			 	(SELECT COUNT(id) FROM ing_category WHERE deleted=0 AND main_category_id IN ('$id') ) 
			   +(SELECT COUNT(id) FROM ingredient_list WHERE deleted=0 AND main_category_id IN ('$id') ) 
		 		
				 ) AS count FROM dual";


		}else if($tab=="IngredientSubCategory"){
		 	$sql = "SELECT ( 
			 	(SELECT COUNT(id) FROM ingredient_list 	WHERE deleted=0 AND category_id IN ('$id') ) 
			   +(SELECT COUNT(id) FROM ingredient_list 	WHERE deleted=0 AND category_id IN ('$id') ) 
		 		
				 )AS count FROM dual";


		}else if($tab=="IngredientCriticalParameters"){

		}else if($tab=="IngredientMaterialType") {
		 	$sql = "SELECT ( 
			 	(SELECT COUNT(id) FROM ingredient_list WHERE deleted=0 AND material_type IN ('$id') ) 
			   +(SELECT COUNT(id) FROM ingredient_list WHERE deleted=0 AND main_category_id IN ('$id') ) 
		 		
				 ) AS count FROM dual";
		}else if($tab=="IngredientBrand"){
			$count=0;

			$qry="SELECT GROUP_CONCAT(brand) AS brand FROM recipe_list WHERE deleted=0 ";
			$res=$this->dbc->get_rows($qry);
			$ids=array_keys(array_flip(explode(',', $res[0]['brand'])));
			if (in_array($id, $ids))$count++;

			$data=array("rows"=>$count);
			ajaxResponse("1", $data);
			
		}else if($tab=="IngredientList"){

		}else if($tab=="IngredientSupplierList"){

		}else if($tab=="IngredientSuppliers"){

		}
		
		// die($sql);
		$result = $this->dbc->get_rows($sql);
		$data=array("rows"=>$result[0]['count']);
		self::sendResponse("1", $data);
	}
	// Common Functions End 

	//Main Category ing_main_category
	public function Category() {
		$sql1 = "SELECT id, code, name, description, active FROM ing_main_category WHERE deleted=0 ORDER BY name ";
		$result1 = $this->dbc->get_rows($sql1);
		$data=array("Category"=>$result1);
		self::sendResponse("1", $data);
	}
	public function Category_A($send='') {
		$sql1 = "SELECT id, name FROM ing_main_category WHERE active=1 AND deleted=0 ORDER BY name ";
		$result1 = $this->dbc->get_rows($sql1);
		$data=array("Category"=>$result1);
		if($send=='')self::sendResponse("1", $data);
		else return $data;
	}

	public function CheckMainCategoryName(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_main_category WHERE name='" . $_REQUEST['name'] ."' ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function CheckMainCategoryCode(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_main_category WHERE code='" . $_REQUEST['code'] ."' ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function EditIngredientCategory(){
		$sql1 = "SELECT id,name,code, description FROM ing_main_category WHERE id=" . $_REQUEST['id'];
		$result1 = $this->dbc->get_rows($sql1);
		$data=array("Category"=>$result1[0]);
		self::sendResponse("1", $data);
	}


	public function RegisterIngredientMainCategory(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$data["name"]=$_REQUEST['IngredientCategoryName'];
		$data["code"]=strtoupper($_REQUEST['IngredientCategoryCode']);
		$data["description"]=$_REQUEST['IngredientCategoryDescription'];
		$this->dbc->InsertUpdate($data, 'ing_main_category');		
	}


	//Category or sub category ing_main_category
	public function SubCategory() {
		$sql2 = "SELECT a.id, a.name, a.description, a.main_category_id, b.name AS mainCategory, a.active FROM ing_category a LEFT JOIN ing_main_category b ON a.main_category_id=b.id WHERE a.deleted=0 AND b.deleted=0 ORDER BY a.name ASC";
		$result2 = $this->dbc->get_rows($sql2);
		$data=array( "subCategory"=>$result2);
		self::sendResponse("1", $data);
	}

	public function SubCategory_A($send='',$id2='') {
		if($send!=''){
			$id=$id2;
		}else{
			$id=$_REQUEST['id'];
		}
		$sql = "SELECT id,name FROM ing_category WHERE deleted=0 AND main_category_id=".$id." ORDER BY name ASC";
		// echo $sql;
		$result = $this->dbc->get_rows($sql);
		$data=array( "SubCategory"=>$result);
		self::sendResponse("1", $data);
	}


	public function CheckIngredientSubCategoryName(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_category WHERE name='" . $_REQUEST['name'] ."' ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function RegisterIngredientSubCategory(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}

		$data=array();
		$data["name"]=$_REQUEST['IngredientSubCategoryName'];
		$data["description"]=$_REQUEST['IngredientSubCategoryDescription'];
		$data["main_category_id"]=$_REQUEST['IngredientSubCategoryMain'];

		$this->dbc->InsertUpdate($data, 'ing_category');		
	}




	//Critical Parameters ing_critical_parameters, ing_critical_master
	public function CriticalParameters() {
		$sql3 = "SELECT * FROM ing_critical_parameters WHERE deleted=0 ORDER BY name ";
		$result3 = $this->dbc->get_rows($sql3);
		$data=array("critical"=>$result3);
		self::sendResponse("1", $data);
	}

	public function checkDuplicate_CriticalParameter(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_critical_parameters WHERE deleted= 0 AND name='" . $_REQUEST['name'] ."' ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function RegisterCriticalParameters() {
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}

		$data=array();
		$data["name"]=$_REQUEST['IngredientCriticalParametersName'];
		$data["description"]=$_REQUEST['IngredientCriticalParametersDescription'];
		$data["mandatory"]=$_REQUEST['IngredientCriticalParametersMandatory'];
		$data["entry_type"]=$_REQUEST['IngredientCriticalParametersEntryType'];

		$options='';
		if($_REQUEST['IngredientCriticalParametersEntryType']=='select'){
			$OptionCount=$_REQUEST['OptionCount'];
			for($i=1;$i<=$OptionCount;$i++){
				if(!isset($_REQUEST['option_'.$i])) continue;
				if($options!='')$options.=',';
				if($_REQUEST['option_'.$i] !=''){
					$options.=$_REQUEST['option_'.$i];
				}
			}
		}
		$data["options"]=$options;
		$this->dbc->InsertUpdate($data, 'ing_critical_parameters');		
	}


	//Material Type, ing_material_type
	public function MaterialType() {
		$sql3 = "SELECT * FROM ing_material_type WHERE deleted=0 ORDER BY id";
		$result3 = $this->dbc->get_rows($sql3);
		$data=array("MaterialType"=>$result3);
		self::sendResponse("1", $data);
	}
	public function MaterialType_A($send='') {
		$sql3 = "SELECT * FROM ing_material_type WHERE deleted=0 AND active=1 ORDER BY id";
		$result3 = $this->dbc->get_rows($sql3);
		$data=array("MaterialType"=>$result3);
		if($send=='')self::sendResponse("1", $data);
		else return $data;
	}

	public function CheckDuplicateMaterialType(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_material_type WHERE name='" . $_REQUEST['name'] ."' AND deleted=0 ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function RegisterMaterialType(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$data["name"]=$_REQUEST['IngredientMaterialTypeName'];
		
		$this->dbc->InsertUpdate($data, 'ing_material_type');		
	}

	public function Ingredient_Brand(){
		$sql = "SELECT id, name, active FROM ing_brand  WHERE deleted=0 ORDER BY name ";
		$result = $this->dbc->get_rows($sql);
		$data=array("IngredientBrand"=>$result);
		ajaxResponse("1", $data);
	}
	public function Ingredient_Brand_A(){
		$sql = "SELECT id, name, active FROM ing_brand  WHERE deleted=0 AND active=1 ORDER BY name ";
		$result = $this->dbc->get_rows($sql);
		$data=array("IngredientBrand"=>$result);
		ajaxResponse("1", $data);
	}

	public function CheckDuplicateBrandName(){
		$qry='';
		if($_REQUEST['id']!=''){
			$qry=' AND id!='.$_REQUEST['id']. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_brand WHERE name='" . $_REQUEST['name'] ."' AND deleted=0 ". $qry;
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function EditIngredientBrand() {
		$id=$_REQUEST['id'];
		$sql = "SELECT id, name FROM ing_brand WHERE id=".$id ;
		$result = $this->dbc->get_rows($sql);
		$data=array("Brand"=>$result[0]);
		self::sendResponse("1", $data);
	}	

	public function RegisterIngredientBrand(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$data["name"]=$_REQUEST['ingredient_brand_name'];
		$this->dbc->InsertUpdate($data, 'ing_brand');		
	}



	public function Ingredient_List(){
		$categoryQry='';
		$subCategoryQry='';
		$materialTypeQry='';

		if($_REQUEST['Category']!='') 		$categoryQry=' AND il.main_category_id='.$_REQUEST['Category'].' ';
		if($_REQUEST['SubCategory']!='') 	$subCategoryQry=' AND il.category_id='.$_REQUEST['SubCategory'].' ';
		if($_REQUEST['MaterialType']!='') 	$materialTypeQry=' AND il.material_type='.$_REQUEST['MaterialType'].' ';

		$sql = "SELECT il.*, mc.name AS main_category, c.name AS sub_category, mt.name AS material_type
				FROM ingredient_list il 
				INNER JOIN ing_main_category mc  ON mc.id=il.main_category_id
				INNER JOIN ing_category c  ON c.id=il.category_id
				INNER JOIN ing_material_type mt  ON mt.id=il.material_type
				WHERE il.deleted=0 " . $categoryQry . $subCategoryQry . $materialTypeQry . "
				ORDER BY il.name ";
				// echo $sql;
		$result = $this->dbc->get_rows($sql);
		$data=array("IngredientList"=>$result);
		ajaxResponse("1", $data);
	}

	public function GetCategoryMaterialType(){
		$category=$this->Category_A('get');
		$MaterialType=$this->MaterialType_A('get');
		$data=array("IngredientCategory"=>$category['Category'],"MaterialType"=>$MaterialType['MaterialType']);
		ajaxResponse("1", $data);
	}

	public function CheckDuplicateIngredientListName(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		
		$sql1 = "SELECT Count(id) AS Count FROM ingredient_list WHERE (( surname LIKE '" . $_REQUEST['name'] ."' OR  surname LIKE '" . $_REQUEST['name'] .",%' OR  surname LIKE '%," . $_REQUEST['name'] .",%' OR  surname LIKE '%," . $_REQUEST['name'] ."') OR  name='" . $_REQUEST['name'] ."') AND deleted=0 ". $qry;
		// die($sql1);	
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function CheckDuplicateIngredientListCode(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ingredient_list WHERE code='" . $_REQUEST['code'] ."' AND deleted=0 ". $qry;
		// die($sql1);
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}


	public function GetItemForMaterialType_0(){
		ajaxResponse("1", '');
	}

	public function GetItemForMaterialType_1(){
		$sql5 = "SELECT id,name FROM ingredient_list WHERE material_type=0 AND  active=1 AND deleted=0  ORDER BY name ASC";
		// echo $sql5;
		$result5 = $this->dbc->get_rows($sql5);
		$data=array("Ingredient"=>$result5);
		ajaxResponse("1", $data);
	}
	public function GetItemForMaterialType_2(){
		$sql4 = "SELECT id, CONCAT(recipe_version, ' - ',name, ' - ',version)AS name FROM recipe_list WHERE active=1 ORDER BY name ASC";
		$result4 = $this->dbc->get_rows($sql4);

		$sql1 = "SELECT id, name FROM ingredient_list WHERE active=1 AND deleted=0 AND material_type=1 ORDER BY name ASC";
		$result1 = $this->dbc->get_rows($sql1);

		$data=array("Recipe"=>$result4,"IngredientCleaned"=>$result1);
		ajaxResponse("1", $data);
	}


	public function EditIngredientList() {
		$id=$_REQUEST['id'];
		$sql = "SELECT * FROM ingredient_list WHERE id=".$id ;
		$result = $this->dbc->get_rows($sql);

		$data=array("Ingredient"=>$result[0],"url"=>url());
		self::sendResponse("1", $data);
	}




	public function RegisterIngredientList(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		// print_r($_FILES['Ingredient_Upload']['name']);
		// die();
		
		$data=array();
		$data["name"]=$_REQUEST['IngSpex_ingredient_name'];
		$data["code"]=$_REQUEST['ingredient_code'];
		$data["surname"]=$_REQUEST['ingredient_sur_name'];
		$data["main_category_id"]=$_REQUEST['ingredient_main_category'];
		$data["category_id"]=$_REQUEST['ingredient_sub_category'];
		$data["material_type"]=$_REQUEST['ingredient_material_type'];

		$data["active"]=0;

		if(isset($_REQUEST['ingredient_critical']) && $_REQUEST['ingredient_material_type']==0 ){
			$data["critical"]=1;
		}else{
			$data["critical"]=0;
		}

		if($_REQUEST['ingredient_material_type']==0){
			$data["can_buy_before"]=$_REQUEST['ingredient_canBuyBefore'];
			$data["min_order_per"]=$_REQUEST['ingredient_min_order_per'];
			$data["max_order_per"]=$_REQUEST['ingredient_max_order_per'];

			$data["ingredient_id"]=0;
			$data["yeild"]=0;
			$data["recipe_id"]=0;
		}else if($_REQUEST['ingredient_material_type']==1){
			$data["ingredient_id"]=$_REQUEST['ingredient_ing_id'];
			$data["yeild"]=$_REQUEST['ingredient_yeild'];

			$data["can_buy_before"]=0;
			$data["min_order_per"]=0;
			$data["max_order_per"]=0;
			$data["process_charge_per_kg"]=$_REQUEST['ingredient_processCharge'];
			
			$data["recipe_id"]=0;
		}else if($_REQUEST['ingredient_material_type']==2){
			$data["yeild"]=$_REQUEST['ingredient_processYeild'];
			$data["process_charge_per_kg"]=$_REQUEST['ingredient_processCharge'];

			if($_REQUEST['ingredient_recipe']!=''){
				$data["recipe_as_ingredient"]=1;
				$data["recipe_id"]=$_REQUEST['ingredient_recipe'];
			}else if($_REQUEST['ingredient_cleaned']!=''){
				$data["ingredient_id"]=$_REQUEST['ingredient_cleaned'];
			}

			$data["can_buy_before"]=0;
			$data["min_order_per"]=0;
			$data["max_order_per"]=0;
		}


		$save=$_REQUEST['save'];
		$table='ingredient_list';
		$dataImage_Id=array();

	    if($save=='add' || $save=='insert'){
	      $data["created_by"]=$_SESSION['ForStarUser']['id'];
	      $data["created_date"]=date('Y-m-d H:i:s');
	      $Inserted = $this->dbc->insert_query($data, $table);
	      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0){
			$message='Record Inserted Successfully';
			$dataImage_Id['id']=$Inserted['InsertId'];
	      } 
	      else ajaxResponse("0", 'Failed In Inserting Data');
	    }else if($save=='update'){
	      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
	      $data["updated_by"]=$_SESSION['ForStarUser']['id'];
	      $data["updated_date"]=date('Y-m-d H:i:s');
	      $Updated = $this->dbc->update_query($data, $table, $data_id);
	      if($Updated['AffectedRows']>0){
	      	$message='Record Updated Successfully';
			$dataImage_Id['id']=$_REQUEST['id'];
		  } else ajaxResponse("0", 'Failed In Updating Data');
	    }

	    if(isset($dataImage_Id['id']) && $dataImage_Id['id']!=''){
	    	$dataImage=array();
			if(isset($_FILES['Ingredient_Upload']['name']) && $_FILES['Ingredient_Upload']['name']!=''){
				$info_1 = pathinfo($_FILES['Ingredient_Upload']['name']);
				$ext_1 = $info_1['extension']; 
				$target_1 = 'upload/ingredient/Ingredient_'.$dataImage_Id['id'].'.'.$ext_1;
				move_uploaded_file( $_FILES['Ingredient_Upload']['tmp_name'], $target_1);
				$dataImage['image']=$target_1;
				$UpdatedImage = $this->dbc->update_query($dataImage, $table, $dataImage_Id);
				if($UpdatedImage['AffectedRows']>0){
					ajaxResponse("1", $message);
			  	} else ajaxResponse("0", 'Failed In Updating Data');
			}else{
				ajaxResponse("1", $message . ' Without Image');
			}
	    }


	}


	public function fetchIngredientSuppliersList() {
		$sql = "SELECT * FROM ing_supplier WHERE deleted=0";
		$result= $this->dbc->get_rows($sql);
		$data=array( "IngredientSupplierList"=>$result);
		ajaxResponse("1", $data);
	}


	function fetchIngSuppliers() {
		$sql = "SELECT a.id, s.name AS sup_name, il.name AS ing_name, (CASE WHEN (a.noofdays =0) THEN ' ' ELSE a.noofdays END) as noofdays, a.active,a.start_date,a.rate
				from ing_supplier_link a  
				INNER JOIN ing_supplier s ON s.id=a.supplier_id
				INNER JOIN ingredient_list il ON il.id=a.ingredient_id
				WHERE a.deleted=0";
		$result = $this->dbc->get_rows($sql);


		$data=array( "IngSuppliers"=>$result);
		self::sendResponse("1", $data);
	}


	public function CheckDuplicateIngredientSupplierCode(){
		$id=$_REQUEST['id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql = "SELECT Count(id) AS Count FROM ing_supplier WHERE deleted=0 AND code='" . $_REQUEST['code'] ."' ". $qry;
		$result = $this->dbc->get_rows($sql);
		if($result[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}


	public function RegisterIngredientSupplierList(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$data["name"]=$_REQUEST['IngSupplier_Name'];
		$data["code"]=$_REQUEST['IngSupplier_Code'];
		$data["address"]=$_REQUEST['IngSupplier_Address'];
		$data["pincode"]=$_REQUEST['IngSupplier_PinCode'];
		$data["phone"]=$_REQUEST['IngSupplier_PhoneNo'];
		$data["phone2"]=$_REQUEST['IngSupplier_PhoneNo2'];
		$data["fax"]=$_REQUEST['IngSupplier_FaxNo'];
		$data["email"]=$_REQUEST['IngSupplier_Email'];
		$data["fssai"]=$_REQUEST['IngSupplier_FSSAI'];
		$data["gst"]=$_REQUEST['IngSupplier_GST'];
		$data["cin"]=$_REQUEST['IngSupplier_CIN'];
		$data["pan"]=$_REQUEST['IngSupplier_PAN'];

		if(isset($_REQUEST["IngSupplier_ExciseApplicable"]) && $_REQUEST["IngSupplier_ExciseApplicable"]=='on'){
			$data["excise_applicable"]=1;
			$data["notification"]=$_REQUEST['IngSupplier_Notification'];
			$data["division_range"]=$_REQUEST['IngSupplier_Range'];
			$data["division"]=$_REQUEST['IngSupplier_Division'];
			$data["commission_rate"]=$_REQUEST['IngSupplier_CommissioneRate'];
			$data["ecc_no"]=$_REQUEST['IngSupplier_ECCNO'];
		}else{
			$data["excise_applicable"]=0;
			$data["notification"]=NULL;
			$data["division_range"]=NULL;
			$data["division"]=NULL;
			$data["commission_rate"]=NULL;
			$data["ecc_no"]=NULL;
		}

		$date=date('Y-m-d H:i:s');
		$UserId=$_SESSION['ForStarUser']['id'];
		$save=$_REQUEST['save'];
		$InsertId=0;
		$this->dbc->autocommit(FALSE);
	    if($save=='add' || $save=='insert'){
	      $data["created_by"]=$UserId;
	      $data["created_date"]=$date;
	      $Inserted = $this->dbc->insert_query($data, 'ing_supplier');
	      if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0) $InsertId=$Inserted['InsertId'];
	      else ajaxResponse("0", 'Failed In Inserting Data');
	    }else if($save=='update'){
	      $data_id=array(); $data_id["id"]=$_REQUEST['id'];
	      $data["updated_by"]=$UserId;
	      $data["updated_date"]=$date;
	      $Updated = $this->dbc->update_query($data, 'ing_supplier', $data_id);
	      if($Updated['AffectedRows']>0) $InsertId=$_REQUEST['id'];
	      else ajaxResponse("0", 'Failed In Updating Data');
	    }

	    $UpdatedBankId=array();
	    $UpdatedBankId["company_id"]=$InsertId;
	    $UpdatedBank=array();
	    $UpdatedBank["deleted"]=1;
	    $UpdatedBank["deleted_by"]=$UserId;
	    $UpdatedBank["deleted_date"]=$date;
	    $UpdatedBank = $this->dbc->update_query($UpdatedBank, 'bank_ing_supplier', $UpdatedBankId);

	    $InsertedRow=0;
	    $DeletedRow=0;
	    $UpdatedRow=0;
	    for($i=1;$i<=$_REQUEST['hid_IngSupplier_Rows'];$i++){
			if(isset($_REQUEST['IngSupplier_AccNo_'.$i]) && $_REQUEST['IngSupplier_AccNo_'.$i]!=''){
	    		$dataBank=array();
				$dataBank["company_id"]=$InsertId;
				$dataBank["acc_no"]=$_REQUEST['IngSupplier_AccNo_'.$i];
				$dataBank["name"]=$_REQUEST['IngSupplier_BankName_'.$i];
				$dataBank["address"]=$_REQUEST['IngSupplier_BankAddress_'.$i];
				$dataBank["ifsc"]=$_REQUEST['IngSupplier_ADCode_'.$i];
				$dataBank["deleted"]=0;
				$dataBank["deleted_by"]='';
	    		$dataBank["deleted_date"]='';
				if(isset($_REQUEST['IngSupplier_Default_'.$i]) && $_REQUEST['IngSupplier_Default_'.$i]=='on'){
					$dataBank["default_bank"]=1;
				}else{
					$dataBank["default_bank"]=0;
				}

				if($_REQUEST['IngSupplier_Id_'.$i]==''){
					$dataBank["created_by"]=$UserId;
					$dataBank["created_date"]=$date;
	      			$Inserted = $this->dbc->insert_query($dataBank, 'bank_ing_supplier');
	      			if($Inserted['AffectedRows']>0) $InsertedRow++;
				}else{
	    			$dataBankId=array();
					$dataBankId["id"]=$_REQUEST['IngSupplier_Id_'.$i];
					$dataBank["updated_by"]=$UserId;
					$dataBank["updated_date"]=$date;
	      			$Updated = $this->dbc->update_query($dataBank,'bank_ing_supplier',$dataBankId);
	      			if($Updated['AffectedRows']>0) $UpdatedRow++;
				}
			}else{
				$DeletedRow++;
			}
		}
		// die('InsertId :'.$InsertId ." Rows : ".$_REQUEST['hid_IngSupplier_Rows'] .' Sum: ' .$Sum.' UpdatedRow: ' .$UpdatedRow .' InsertedRow: ' .$InsertedRow  );
		if($InsertId>0 && $_REQUEST['hid_IngSupplier_Rows']== ($InsertedRow+$UpdatedRow+$DeletedRow)){
			$this->dbc->commit();
			ajaxResponse("1", 'Record Updated Sucessfully');
		}else{
			$this->dbc->rollback();
			ajaxResponse("0", 'Failed in Saving Record');
		}
	}


	// public function RegisterIngredientSupplierList(){
	// 	if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
	// 		ajaxResponse("0", 'USER_ID is null');
	// 	}
	// 	$InsertId='';
	// 	$RowCount=$_POST['hid_row_count_bank'];
	// 	// die($RowCount);
	// 	$data=array();
	// 	$data["name"]=$_REQUEST['Ing_Comp_Name'];
	// 	$data["code"]=$_REQUEST['Ing_Comp_Code'];
	// 	$data["address"]=$_REQUEST['Ing_Comp_Address'];
	// 	$data["phone"]=$_REQUEST['Ing_Comp_PhoneNo'];
	// 	$data["phone2"]=$_REQUEST['Ing_Comp_PhoneNo2'];
	// 	$data["email"]=$_REQUEST['Ing_Comp_Email'];
	// 	$data["pincode"]=$_REQUEST['Ing_Comp_PinCode'];
	// 	$data["fssai"]=$_REQUEST['Ing_Comp_FSSAI'];
	// 	$data["gst"]=$_REQUEST['Ing_Comp_GST'];
	// 	$data["cin"]=$_REQUEST['Ing_Comp_CIN'];
	// 	$data["pan"]=$_REQUEST['Ing_Comp_PAN'];
	// 	$data["fax"]=$_REQUEST['Ing_Comp_FaxNo'];
	// 	// $this->dbc->InsertUpdate($data, 'pack_supplier');		
 //    	$save=$_REQUEST['save'];
	// 	$table='ing_supplier';
	// 	$this->dbc->autocommit(FALSE);
	// 	if($save=='add'){
	//       $data["created_by"]=$_SESSION['ForStarUser']['id'];
	//       $data["created_date"]=date('Y-m-d H:i:s');
	//       $Inserted = $this->dbc->insert_query($data, $table);
	//       if($Inserted['AffectedRows']>0 && $Inserted['InsertId']>0){
	//       	$InsertId=$Inserted['InsertId'];
	//       }
	//     }else if($save=='update'){
	//       $data_id=array(); $data_id["id"]=$_REQUEST['id'];
	//       $data["updated_by"]=$_SESSION['ForStarUser']['id'];
	//       $data["updated_date"]=date('Y-m-d H:i:s');
	//       $Updated = $this->dbc->update_query($data, $table, $data_id);
	//       if($Updated['AffectedRows']>0){
	//       	$InsertId=$_REQUEST['id'];
	//       }
	//     }

	//     $CountIndex=0;
	//     $CountDeleted=0;
	// 	for($i=1;$i<=$RowCount;$i++){

	// 		if(isset($_POST['Ing_Comp_BankName_'.$i]) ){
	// 			$Bankdata=array();
	// 			$BankId=$_POST['Ing_Comp_Id_'.$i];
	// 			$default=0;
	// 			if(isset($_POST['Ing_Comp_Default_'.$i])){
	// 				$default=1;
	// 			}

	// 			$bankData['company_id']=$InsertId;
	// 			$bankData['default_bank']=$default;
	// 			$bankData['acc_no']=$_POST['Ing_Comp_AccNo_'.$i];
	// 			$bankData['name']=$_POST['Ing_Comp_BankName_'.$i];
	// 			$bankData['address']=$_POST['Ing_Comp_BankAddress_'.$i];
	// 			$bankData['ifsc']=$_POST['Ing_Comp_ADCode_'.$i];

	// 			// die();
	// 			if($BankId=='' && $InsertId!=''){
	//       			$InsertedBank = $this->dbc->insert_query($bankData, 'bank_rte');
	//       			if($InsertedBank['AffectedRows']>0 && $InsertedBank['InsertId']>0){
	//       				$CountIndex++;
	//       			}	
	// 			}else if($BankId!='' && $InsertId!=''){
	// 				$bankData_Id['id']=$BankId;
	//       			$UpdatedBank = $this->dbc->update_query($bankData, 'bank_rte', $bankData_Id);
	//       			$CountIndex++;
	// 			}else {
	// 				ajaxResponse("0", 'Failed in Saving Record');
	// 			}
	// 		}else{
	// 			$CountDeleted++;
	// 			// echo 'in asdfasdf '.$CountDeleted;
	// 		}
	// 	}
	// 	$total=$CountIndex+$CountDeleted;
	// 	// echo 'InsertId :' .$InsertId .', CountInd ' .$CountIndex .', RowCount ' .$RowCount .', CountDeleted ' .$CountDeleted . ', total '.$total;
	// 	if($InsertId!='' && $RowCount==$total){
	// 		$this->dbc->commit();
	// 		ajaxResponse("1", 'Record Updated Successfully');
	// 	}else{
	// 		$this->dbc->rollback();
	// 		ajaxResponse("0", 'Failed in Saving Record');
	// 	}

	// }


	public function EditIngSupplierList(){
		$id=$_REQUEST['id'];
		$sql = "SELECT * FROM ing_supplier WHERE id='$id'";
		$result= $this->dbc->get_rows($sql);
		
		$sql1 = "SELECT * FROM bank_ing_supplier WHERE company_id='$id'";
		$result1= $this->dbc->get_rows($sql1);

		$data=array("SuppliersList"=>$result,"Bank"=>$result1);
		ajaxResponse("1", $data);
	}


	public function GetActiveIngredientSupplier(){
		$sql = "SELECT id, name FROM ing_supplier  WHERE deleted=0 AND active=1 ORDER BY name ";
		$result = $this->dbc->get_rows($sql);
		$sql2 = "SELECT id, name FROM ingredient_list  WHERE deleted=0 AND active=1 ORDER BY name ";
		$result2 = $this->dbc->get_rows($sql2);
		$data=array("Ingredient"=>$result2,"Supplier"=>$result);
		ajaxResponse("1", $data);
	}

	public function CheckIngredientSupplierDuplicate(){
		$id=$_REQUEST['id'];
		$supplier_id=$_REQUEST['supplier_id'];
		$ingredient_id=$_REQUEST['ingredient_id'];
		$qry='';
		if($id!=''){
			$qry=' AND id!='.$id. ' ';
		}
		$sql1 = "SELECT Count(id) AS Count FROM ing_supplier_link WHERE supplier_id='$supplier_id' AND  ingredient_id='$ingredient_id' AND deleted= 0 ". $qry;
		// die($sql1);
		$result1 = $this->dbc->get_rows($sql1);
		if($result1[0]['Count']>0){
			self::sendResponse(0,false);
		}else{
			self::sendResponse(1,true);
		}
	}

	public function RegisterIngredientSupplier(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$save=$_REQUEST['save'];
    	if($save=='add' || $save=='insert'){
    		$data["supplier_id"]=$_REQUEST['IngSup_Supplier'];
			$data["ingredient_id"]=$_REQUEST['IngSup_Ingredient'];
		}
		$data["rate"]=$_REQUEST['IngSup_Rate'];
		$data["noofdays"]=$_REQUEST['IngSup_NoOfDaysToGet'];
		$data["start_date"]=$_REQUEST['IngSup_EffectiveDate'];
		$this->dbc->InsertUpdate($data, 'ing_supplier_link');		
	}

	public function editSupplierIngredient(){
		$id=$_REQUEST['id'];
		$sql = "SELECT id, supplier_id, ingredient_id, noofdays, rate, start_date FROM ing_supplier_link WHERE id='$id'";
		$result = $this->dbc->get_rows($sql);
		$data=array("SuppIng"=>$result[0]);
		self::sendResponse("1", $data);
	}


	public function fetchIngRateMaster() {
		$sql = "SELECT il.id, il.name, mt.name AS material_type, il.rate_per_kg, il.highest_price, il.lowest_price, il.last_price, il.actual_rate_per_kg  FROM ingredient_list il 
			INNER JOIN ing_material_type mt ON il.material_type=mt.id WHERE il.deleted=0 ORDER BY il.name";
		$result = $this->dbc->get_rows($sql);
		$data=array( "IngRateMaster"=>$result);
		self::sendResponse("1", $data);
	}

	public function getForRateMaster() {

		$sql="SELECT id, name FROM ingredient_list WHERE deleted=0 AND active=1 AND material_type=0  ORDER BY name";
		// echo $sql;
		$result = $this->dbc->get_rows($sql);


		$sql1="SELECT id, name FROM ing_material_type WHERE deleted=0 AND active=1";
		$result1 = $this->dbc->get_rows($sql1);

		$data=array("Ingredient"=>$result,"MaterialType"=>$result1);
		self::sendResponse("1", $data);

	}

	public function selectIng_MatType() {
		$sql="SELECT id, name FROM ingredient_list WHERE deleted=0 AND active=1 AND material_type=".$_REQUEST['id'] . " ORDER BY name";
		// echo $sql;
		$result = $this->dbc->get_rows($sql);
		$data=array("Ingredient"=>$result);
		self::sendResponse("1", $data);
	}
	public function fetchIngredientRate() {
		$sql="SELECT id, yeild, rate_per_kg, actual_rate_per_kg FROM ingredient_list WHERE id=".$_REQUEST['id']." LIMIT 1";
		$result = $this->dbc->get_rows($sql);
		$data=array("Ingredient"=>$result[0]);
		self::sendResponse("1", $data);
	}

	public function RegisterIngredientRate(){
		if(!isset($_SESSION['ForStarUser']['id']) || ($_SESSION['ForStarUser']['id'] == "") ){
			ajaxResponse("0", 'USER_ID is null');
		}
		$data=array();
		$data["actual_rate_per_kg"]=$_REQUEST['IngredientRateMaster_actual_rate_per_kg'];
		$data["yeild"]=$_REQUEST['IngredientRateMaster_yeild'];
		$data["rate_per_kg"]=$_REQUEST['IngredientRateMaster_rate_per_kg'];
		$data["effective_date"]=$_REQUEST['IngredientRateMasterEffectiveDate'];

		$sql="SELECT id,rate_per_kg, highest_price, lowest_price, last_price  FROM ingredient_list WHERE id=".$_REQUEST['id']." LIMIT 1";
		// echo $sql;
		$result = $this->dbc->get_rows($sql);
		if($result[0]['highest_price']==''){
			$data["highest_price"]=$_REQUEST['IngredientRateMaster_rate_per_kg'];
			$data["lowest_price"]=$_REQUEST['IngredientRateMaster_rate_per_kg'];
			$data["last_price"]=$_REQUEST['IngredientRateMaster_rate_per_kg'];
		}else{
			
			$data["last_price"]=$_REQUEST['rate_per_kg'];
		}
		$this->dbc->InsertUpdate($data, 'ingredient_list');		
	}






}
?>