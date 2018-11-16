<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
include_once('../lib/function_connect.php');
//$currentdate = strtoupper(date('d-M-Y h:i:s A'));
if($_REQUEST['action']=='get_bio' )
{
	$sql_data = select_query_json("select EMPSRNO,EMPTHUMP from EMPLOYEE_THUMP_SECUGEN", "Centra", 'TEST');
$data=json_encode($sql_data);
echo($data);
}
if($_REQUEST['action']=='reg_bio' )
{
	$empcode = select_query_json("select empsrno from employee_office where empcode='".$_REQUEST['empsrno']."'", "Centra", 'TCS');
	$g_table="EMPLOYEE_THUMP_SECUGEN";
	$g_fld['EMPSRNO']=$empcode[0]['EMPSRNO'];
	$g_fld['EMPTHUMP']=$_REQUEST['img'];
	$g_fld['ADDUSER']='2280588';
	$g_fld['THUMP_ACTV']='Y';
	$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	
	print_r($g_fld);
	echo "...........".$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
}
if($_REQUEST['action']=='user')
{
	$usrcode = select_query_json("select usrcode from userid where empsrno='".$_REQUEST['empsrno']."'", "Centra", 'TCS');
	echo($usrcode[0]['USRCODE']);
}
?>