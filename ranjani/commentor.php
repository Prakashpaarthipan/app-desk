<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
//print_r($_REQUEST);


$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//echo "current year".$current_year;
//echo "------------1".$data."1----------------";
$commentno = select_query_json("Select nvl(Max(CMTSRNO),0)+1 MAXENTRY From PROCESS_REQUIREMENT_COMMENT prc where prc.entryyr='".$_REQUEST['entryyr']."' and prc.entryno='".$_REQUEST['entryno']."' and prc.entsrno='".$_REQUEST['entsrno']."'ORDER BY ADDDATE ASC","Centra","TEST");
//echo ("Select nvl(Max(CMTSRNO),0)+1 MAXENTRY From PROCESS_REQUIREMENT_COMMENT where ENTRYYR = '".$_REQUEST['ENTRYYR']."'");
//print_r($commentno);

		$g_table = "PROCESS_REQUIREMENT_COMMENT";
		$g_fld = array();
		$g_fld4['ENTRYYR'] = $_REQUEST['entryyr'];
		$g_fld4['ENTRYNO'] = $_REQUEST['entryno'];
		$g_fld4['ENTSRNO'] = $_REQUEST['entsrno'];
		$g_fld4['CMTSRNO'] = $commentno[0]['MAXENTRY'];
		$g_fld4['REQCMNT'] = $_REQUEST['comment'];
		$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_insert_subject = insert_dbquery($g_fld4,$g_table);
		//print_r($g_fld);
		//echo "done";

//echo('1');
?>