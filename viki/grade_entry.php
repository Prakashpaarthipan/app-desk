<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(0);
print_r($_REQUEST);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$g_table = "EMPLOYEE_HEAD_USER";
	$head=explode(' - ',$_REQUEST['txt_employee_head'][0]);
	$g_fld4 = array();
	$sql_head = select_query_json("select empsrno from employee_office where empcode='".$head[0]."'", "Centra", "TEST");
	for($i=0;$i<count($_REQUEST['txt_employee_code']);$i++)
	{	$emp=explode(' - ',$_REQUEST['txt_employee_code'][$i]);
//print_r($emp);
		$sql_emp = select_query_json("select empsrno from employee_office where empcode='".$emp[0]."'", "Centra", "TEST");
		$g_fld['EMPHDSR'] = $sql_head[0]['EMPSRNO'];
		$g_fld['EMPSRNO'] = $sql_emp[0]['EMPSRNO'];
		$g_fld['EMPCODE'] = $emp[0];
		$g_fld['EMPNAME'] = $emp[1];
		$g_fld['DELETED'] = 'N';
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		echo("----------------");
		print_r($g_fld);
		$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
	}
	//

?>