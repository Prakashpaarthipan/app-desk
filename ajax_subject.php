<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
if($_REQUEST['action']=='alter')
{	print_r($_REQUEST);
	$arr=array();
	$sql_cor = select_query_json("select * from approval_branch_head where tarnumb = '".$_REQUEST['tarnumb']."' and deleted='N' order by BRNHDCD,BRNHDSR", "Centra", "TEST");
	foreach($sql_cor as $key=>$value)
	{	
		$arr[$value['BRNHDCD']][count($arr[$value['BRNHDCD']])]=$value;
	}
	$newflow=$_REQUEST['newflow'];
	$newflow=array_flip($newflow);

	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	echo("++".$newflow[$value[$i]['EMPCODE']]."   =>");
			if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $value[$i]['BRNHDSR']+50;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".$value[$i]['BRNHDSR']."' and TARNUMB='".$_REQUEST['tarnumb']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
				echo('\n');
			}
		}
		
	}
	foreach($arr as $key=>$value)
	{
		for($i=0;$i<sizeof($value);$i++)
		{	if(!is_null($newflow[$value[$i]['EMPCODE']]))
			{	
				$g_table = "APPROVAL_BRANCH_HEAD";
				$g_fld4 = array();
				$g_fld4['BRNHDSR'] = $newflow[$value[$i]['EMPCODE']]+5;
				$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan="BRNHDCD = '".$value[$i]['BRNHDCD']."' and BRNHDSR = '".($value[$i]['BRNHDSR']+50)."' and TARNUMB='".$_REQUEST['tarnumb']."'";
				print_r($g_fld4);
				print_r($where_appplan);
				$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
				echo('\n');
			}
		}
	}
}
?>