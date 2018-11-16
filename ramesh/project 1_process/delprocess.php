<?php
//header('Content-Type: text/html; charset=utf-8');

include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');

$sel=select_query_json("select PRCSNO,PRCSYR from SUPMAIL_PROCESS_ENTRY where TEMPYR='".$_REQUEST['tempyr']."' and TEMPNO='".$_REQUEST['tempno']."'","Centra","TEST");

$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);
$currentdate=strtoupper(date('d-M-Y h:i:s A'));
$table='SUPMAIL_PROCESS_ENTRY';
$entry=array();
$entry['DELETED']='Y';
$entry['DELDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
$entry['DELUSER']=$_SESSION['tcs_usrcode'];

$where="TEMPYR='".$_REQUEST['tempyr']."' and TEMPNO='".$_REQUEST['tempno']."'";
$entry_insert = update_test_dbquery($entry,$table,$where);



		
		$g_table = "SUPMAIL_PROCESS_VALUE";
		
		$g_fld = array();
		$where1="PRCSYR='".$sel[0]['PRCSYR']."' and PRCSNO='".$sel[0]['PRCSNO']."'";
		$g_fld['DELETED'] = 'Y';
		$g_fld['DELDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DELUSER']=$_SESSION['tcs_usrcode'];
			
		
		
		$update_read = update_test_dbquery($g_fld,$g_table,$where1);
		//echo ($reject_project);
		






?>


