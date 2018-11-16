<?php 
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$slt_project = select_query_json("select * from APPROVAL_PROJECT where APRCODE = '".$projectcode."'", "Centra", 'TCS');
if(count($slt_project) > 0) {
	echo 0;
} else {
	echo 1;
}
?>