<?php 
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$sql_sub=select_query_json("select p.empphot from employee_personal p,employee_office o where p.empsrno=o.empsrno and o.empsrno=".$_GET['profile_img'], "Centra", 'TCS');
$img = $sql_sub[0][0]->load();
header("Content-type: image/pjpeg");
echo $img;
?>