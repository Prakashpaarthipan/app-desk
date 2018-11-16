<?php
error_reporting(0);
include_once('config.php');
include_once('../general_functions.php');
extract($_REQUEST);
// $current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

// connect and login to FTP server
$ftp_conn = ftp_connect(ftpvri_server_apdsk, 5022) or die("Could not connect to ftpvri_server_apdsk");
$login = ftp_login($ftp_conn, ftpvri_user_name_apdsk, ftpvri_user_pass_apdsk);

// Find User Rights
function find_user_sub_rights($mainmenu,$submenu, $user)
{
	$inner_mainmenu = select_query_json("select * from SRM_MENU mnu, SRM_MENU_ACCESS acc 
												where mnu.MNUCODE = acc.MNUCODE and mnu.MAINMENU = '".$mainmenu."' and mnu.submenu='".$submenu."' and acc.entsrno = ".$user." 
												order by mnu.MNUCODE Asc", "Centra", 'TEST');
	//print_r($inner_mainmenu);
	$access = 0;
	for($menu = 0; $menu < count($inner_mainmenu); $menu++)
	{
		if($inner_mainmenu[$menu]['VEWVALU'] == 'Y')
		{
			$access++;
		}
	}
	if($access == 0) { return 0; }
	else { return 1; }
}
// Find User Rights
function find_user_rights($mainmenu, $user)
{
	$inner_mainmenu = select_query_json("select * from SRM_MENU mnu, SRM_MENU_ACCESS acc 
												where mnu.MNUCODE = acc.MNUCODE and mnu.MAINMENU = '".$mainmenu."' and acc.entsrno = ".$user." 
												order by mnu.MNUCODE Asc", "Centra", 'TCS');
	$access = 0;
	for($menu = 0; $menu < count($inner_mainmenu); $menu++)
	{
		if($inner_mainmenu[$menu]['VEWVALU'] == 'Y')
		{
			$access++;
		}
	}
	if($access == 0) { return 0; }
	else { return 1; }
}

// Insert Database Query
function insert_dbquery($field_values, $tbl_names)  
{	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date('".$expld[1]."', '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "'".$org_value[$ii]."', ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		$sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$save_query = save_query_json($sql_insert, "Centra", 'TEST');
		return $save_query;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
// Insert Database Query

// Insert Database Query
function insert_test_dbquery($field_values, $tbl_names)
{	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			// echo "!!"; print_r($expld); echo "!!";
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date('".$expld[1]."', '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "'".$org_value[$ii]."', ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		echo $sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		echo $save_query = save_test_query_json($sql_insert, "Centra", 'TEST');
		return $save_query;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
// Insert Database Query

// Update Database Query
function update_dbquery($field_values, $tbl_names, $where_condition)
{	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kyvl .= $key_values[$ii]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";	
			}	
		}
		$kyvl = rtrim($kyvl, ", ");
		
		$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		$save_query = save_query_json($sql_update, "Centra", 'TEST');
		return $save_query;
	}
	catch(Exception $e) {
		//echo 'Message: ' .$e->getMessage();
	}
}

function update_test_dbquery($field_values, $tbl_names, $where_condition)
{	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kyvl .= $key_values[$ii]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";	
			}	
		}
		$kyvl = rtrim($kyvl, ", ");
		
		echo "<br>##".$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		echo "<br>**".$save_query = save_test_query_json($sql_update, "Centra", 'TEST');
		return $save_query;
	}
	catch(Exception $e) {
		//echo 'Message: ' .$e->getMessage();
	}
}
// Update Database Query

// Delete Database Query
function delete_dbquery($delete_qry) 
{
	try {
		// $sql_delete = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		$sql_delete = $delete_qry;
		$save_query = save_query_json($sql_delete, "Centra", 'TEST');
		return $save_query;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}

function delete_test_dbquery($delete_qry) 
{
	try {
		// echo "<br>##".$sql_delete = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		echo "<br>##".$sql_delete = $delete_qry;
		echo "<br>**".$save_query = save_test_query_json($sql_delete, "Centra", 'TEST');
		return $save_query;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
// Delete Database Query


// Select Query JSON Response
function select_query_login_check_json($usrlogin, $usrpass, $brn_connection = 'Centra', $schema_source = 'TEST') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->User_Login = $usrlogin;
	$get_parameter->User_Pass = $usrpass;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	// $get_parameter->C_Mode='TEST';
	try{
		$get_result=$client->Php_Login_Check($get_parameter)->Php_Login_CheckResult;
		// print_r($get_result);
	}
	catch(SoapFault $fault){
		/* echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE; */
		if ($client != null)
		{
			$client=null;
		}
		// exit();
	}
	$soapClient = null;
	return $get_result;
	// return json_decode($get_result, true); 
}

// Select Query JSON Response
function select_query_json($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TCS') {
	// echo "**".$sqlqry_select."**".$brn_connection."**".$schema_source."**";
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	// $get_parameter->C_Mode='TEST';
	try{
		//echo "!!";
		// print_r($get_parameter);
		$get_result=$client->GetData_Json($get_parameter)->GetData_JsonResult;
	}
	catch(SoapFault $fault){
		/* echo "##";
		echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE; */
		if ($client != null)
		{
			$client=null;
		}
		// exit();
	}
	$soapClient = null;
	return json_decode($get_result,true); 
}
// Select Query JSON Response

// Save Query JSON Response
function save_query_json($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TEST') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	// $get_parameter->C_Mode='TEST';
	try{
		$get_result = $client->Php_Store_Data($get_parameter)->Php_Store_DataResult;
	}
	catch(SoapFault $fault){
		$get_result = 0;
		/* echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE; */
		if ($client != null)
		{
			$client=null;
		}
		// exit();
	}
	$soapClient = null;
	return json_decode($get_result,true); 
}

function save_test_query_json($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TEST') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	// $get_parameter->C_Mode='TEST';
	try{
		$get_result = $client->Php_Store_Data($get_parameter)->Php_Store_DataResult;
		// echo "=================".$get_result."=================";
	}
	catch(SoapFault $fault){
		$get_result = 0;
		/* echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE; */
		if ($client != null)
		{
			$client=null;
		}
		// exit();
	}
	$soapClient = null;
	return json_decode($get_result,true); 
}
// Save Query JSON Response


function select_query_webservice($sqlqry_select) {
	// $service_url = 'http://tcstextile.in/tcs_service/TCSservice.asmx/App_TRN_List?TRN=A';
	$curl = curl_init($sqlqry_select);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	
	$respxpl = explode('<string xmlns="http://tempuri.org/">', $curl_response);
	$respxplode = explode('</string>', $respxpl[1]);

	$jsonverify = isJson($respxplode[0]);
	$mainarray = json_decode($respxplode[0], true);
	
	if(count($mainarray[0]) == 0) { $innerarray[0] = $mainarray; }
	else { $innerarray = $mainarray; }
	return $innerarray;
}

// JSON is available
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}
// JSON is available

// *** Find the Browser Type ***
function find_browser () {
	 if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
	   return 'IE';
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
		return 'IE';
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
	   return 'Firefox';
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
	   return 'Chrome';
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
	   return "Opera";
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
	   return "Opera";
	 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
	   return "Safari";
	 else
	   return 'Fail';
}
// *** Find the Browser Type ***

// *** Password Encrypt ***
function encrypt_pwd($password)
{
	$lenpass = strlen($password);
	$ks = 50;
	$txt = '';
	$txt1 = '';
	$txt2 = '';

	for($i = 0; $i < $lenpass; $i++)
	{
		$txt1 = ord(substr($password, $i, 1));
		$txt1 += $ks;
		if($txt1 >= 255)
		{
			$txt1 = $txt1 - 200;
		}
		$txt2 = chr($txt1);
		$txt .= $txt2;
		$ks = $ks + 15;
	}
	//echo $password = $txt;
	return $txt;
	// return md5($txt);
}
// *** Password Encrypt ***


function store_procedure_query_json($sqlqry_select, $brn_connection = 'Req', $schema_source = 'TCS') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	try{
		$get_result = $client->Store_Procedure($get_parameter)->Store_ProcedureResult;
	}
	catch(SoapFault $fault){
		$get_result = 0;
		if ($client != null)
		{
			$client=null;
		}
	}
	$soapClient = null;
	return json_decode($get_result,true);
}



//save procedure TEST

function store_procedure_test_query_json($sqlqry_select, $brn_connection = 'Centra', $schema_source = 'TEST') {
	$client = new SoapClient("http://templive.thechennaisilks.com:5088/service.asmx?Wsdl", array('exceptions'=>true, 'trace' => true));
	$get_parameter->str = $sqlqry_select;
	$get_parameter->B_Code = $brn_connection; // 'Centra';
	$get_parameter->C_Mode = $schema_source; // 'TCS';
	try{
		$get_result = $client->Store_Procedure($get_parameter)->Store_ProcedureResult;
	}
	catch(SoapFault $fault){
		$get_result = 0;
		if ($client != null)
		{
			$client=null;
		}
	}
	$soapClient = null;
	return json_decode($get_result,true);
}


function call_session_time() {
// $inactive = 1800; // Inactive After 1800 Seconds or 30 Mins
$inactive = 3600; // Inactive After 3600 Seconds or 60 Mins
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
	
	if (in_array($menu_name, $_SESSION['tcs_submenu_access']))
	{
		$tbl_apply = 'srm_userlog';
		$exist_apply = select_query_json("select nvl(max(USERLOG)+1,1) from ".$tbl_apply."", "Centra", 'TEST');
		$exist_menu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' and MAINMENU = 'Supplier' order by MNUCODE Asc", "Centra", 'TEST');

	//echo "<br><br>===".$_SESSION['cur_filename']."***".$exist_menu[0][0]."*****";
		if($_SESSION['cur_filename'] != $exist_menu[0][0]) {
			if($_SESSION['cur_filename'] != '') {
				$tbl_name='srm_userlog';
				$field_value1=array();
				$field_value1['OUTTIME'] = date('d-m-Y H:i:s A');
				$field_value1['LOGSTAT'] = 'Y';
				$where_conditions = "MNUCODE = '".$_SESSION['cur_filename']."' and USERLOG = '".$_SESSION['lastid']."' and LOGTIME like '".date('d-m-Y')."%' and ( SUPCODE = '".$_SESSION['tcs_userid']."' or EMPSRNO = '".$_SESSION['tcs_userid']."' )";
				$ilo = update_query($field_value1, $tbl_name, $where_conditions);
				//echo "!!*******";
			}	
			$_SESSION['cur_filename'] = $exist_menu[0][0];

			/* $tbl_name="srm_userlog";
			$field_value=array();
			$field_value['USERLOG'] = $exist_apply[0][0];	
			
			if($_SESSION['tcs_empsrno'] != '') {
				$field_value['SUPCODE'] = '0';
				$field_value['EMPSRNO'] = $_SESSION['tcs_userid'];	
			} else {
				$field_value['SUPCODE'] = $_SESSION['tcs_userid'];
				$field_value['EMPSRNO'] = '0';
			}
			
			$field_value['MNUCODE'] = $exist_menu[0][0];	
			$field_value['LOGTIME'] = date('d-m-Y H:i:s A');
			$field_value['OUTTIME'] = null; 
			$field_value['LOGSTAT'] = 'N';
			//print_r($field_value);
			$lol = insert_query($field_value, $tbl_name); */
			//echo "@@******";
			//echo "***".$lol."***"; //exit();
		}
	} else {
	?>
	<script>/* alert('Error GA'); window.history.back(); */ </script>
	<?
	}
	
	session_destroy(); 
	?>
	<script>window.location="<?=$siteurl?>logout.php?mode=session";</script>
	<?
	exit();
	}
}
$_SESSION['start'] = time();
}
call_session_time();
?>