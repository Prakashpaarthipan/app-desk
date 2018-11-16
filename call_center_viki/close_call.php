<?php
header("Access-Control-Allow-Origin: *");
session_start();
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
print_r($_REQUEST);

$g_table = "service_register_entry";
$g_fld4 = array();
$g_fld4['REQSTAT'] = 'C';
$g_fld4['RESLVUSER'] =$_SESSION['tcs_user'];
$g_fld4['RESLVDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
$where_appplan="reqnumb ='".$_REQUEST['reqnumb']."'";
$insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
?>