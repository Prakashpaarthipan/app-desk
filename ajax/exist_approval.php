<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$brnc = $slt_branch;
$depc = $deptid;
$core_dep = $core_deptid;
$tarno = $target_no;
$curntyr = $currentyr;
$ttl_lock = 0;
$bud_type = $slt_submission;

$sql_tarno = select_query_json("SELECT * FROM approval_request where BRNCODE = ".$brnc." and DEPCODE = ".$depc." and TARNUMB = ".$tarno." and ARQYEAR = '".$curntyr."' and BUDTYPE = '".$bud_type."' order by aprnumb, arqsrno", "Centra", 'TCS'); 
if(count($sql_tarno) > 0) {
	echo 1;
} else {
	echo 0;
}
?>