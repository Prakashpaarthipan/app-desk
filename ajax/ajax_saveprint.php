<?php 
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$expl_cnt = explode("||", $cnt);
$ttl_cnt = count($expl_cnt);

if($ttl_cnt > 0 && $access == ''){
    /* Update into approval_request Table for Verify the Duplicate or Original Print */
	$tbl_approval_request = "approval_request";
	$field_approval_request = array();
	$field_approval_request['APPRMRK'] 	= $cnt.$_SESSION['tcs_empsrno']."-".date("d-m-y")."||";	
	$where_approval_request = " arqsrno = 1 and aprnumb like '".$aprnumb."' ";
	// print_r($field_approval_request);
	echo $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
    /* Update into approval_request Table for Verify the Duplicate or Original Print */
} else {
	echo 0;
}
?>