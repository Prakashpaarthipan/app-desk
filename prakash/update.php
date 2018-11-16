<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		

		
		$year = 2018-19;
		$mcode = 1;
		$pcode = 1;
		//$where_read = select_query_json("select DELETED from approval_project_master where PRMSYER = '2018-19' and PRMSCOD = '1' ","Centra","TEST");
		$g_table = "approval_project_master";
		$where = "PRMSYER = '2018-19' and PRMSCOD ='1' ";
		$g_fld = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld['DELUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DELETED'] = 'Y';
		//$update_read = update_test_dbquery($g_fld, $g_table, $where);
		//echo ($reject_project);
		
		
		
		
		//$tbl_read = "approval_request";
		//$field_read = array();
		//$field_read['INTPESC'] = 1; // This 1 is indicate us, this approval is read by approval user
		// print_r($field_read);
		//$where_read = " ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$arsrno."' and ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."'";
		//$update_read = update_test_dbquery($field_read, $tbl_read, $where_read);

		echo $where_read;
		
		print_r($g_fld);
		
		echo "deleted";

?>