<?php
header('Content-Type: text/html; charset=utf-8');
header('Location:report_entry2.php');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');
$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);
$table='SUPMAIL_PROCESS_ENTRY';
$entry=array();
$ENTRYNO = select_query_json("Select nvl(Max(TEMPNO),0)+1 MAXENTRY From SUPMAIL_PROCESS_ENTRY","Centra","TEST");
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$current_yr=$current_year[0]['PORYEAR'];
$entryno=$ENTRYNO[0]['MAXENTRY'];
$processno=$_REQUEST['processname'];
$processyear=select_query_json("Select PRCSYR From SUPMAIL_PROCESS where PRCSNO='".$processno."'","Centra","TEST");
$processyr=$processyear[0]['PRCSYR'];
$langcode=$_REQUEST['language'];
$comment=$_REQUEST['comments'];
$currentdate=strtoupper(date('d-M-Y h:i:s A'));

$entry['TEMPYR']=$current_yr;
$entry['TEMPNO']=$entryno;
$entry['PRCSYR']=$processyr;
$entry['PRCSNO']=$processno;
$entry['LANGCOD']=$langcode;
$entry['TEMPCMNT']=$comment;
$entry['DELETED']='N';
$entry['ADDUSER']=$_SESSION['tcs_usrcode'];
$entry['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
$entry['EDTUSER']="";
$entry_insert = insert_test_dbquery($entry,$table);


$fieldname=$_REQUEST['field_name'];
$fielvalue=$_REQUEST['field_val'];
		for($i=0;$i<count($fieldname);$i++){
		$g_table = "SUPMAIL_PROCESS_VALUE";
		
		$g_fld = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld['PRCSYR'] =$processyr;
		$g_fld['PRCSNO'] = $processno;
		$g_fld['FIELDNM'] = $fieldname[$i];
		$g_fld['FIELDVAL'] =$fielvalue[$i] ;
		$g_fld['DELETED'] = 'N';		
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DELUSER']='';
		$g_fld['DELDATE']='';
		$g_fld['EDTUSER']='';

		
		$update_read = insert_test_dbquery($g_fld,$g_table);
		//echo ($reject_project);
		

}





?>

