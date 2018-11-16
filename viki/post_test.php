<?php
header("Access-Control-Allow-Origin: *");

//header('Location: ../process_requirement_entry.php');


header("Content-Type: application/json; charset=UTF-8");
//$fh = fopen('track.txt','a');
//fwrite($fh,"came and writing");
//fclose($fh);

session_start();



error_reporting(0);
//include_once('../lib/function_connect.php');
print_r($_REQUEST);
print_r($_FILES);
$EMP=explode(' - ',$_REQUEST['employee']);
print_r($EMP[0]);
// echo 'User IP - '.$_SERVER['REMOTE_ADDR'];
// $txt_assign=explode(' - ',$_REQUEST['txt_assign']);
print_r($txt_assign);
$search_fromdate=$_REQUEST['search_fromdate'];
if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
$exp1 = explode("-", $search_fromdate);
$frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
echo($frm_date);
//echo('1');
?>
