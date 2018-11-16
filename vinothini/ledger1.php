<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include('../../../approval-desk/lib/config.php');
include_once('../lib/function_connect.php');
//$filter = $_GET["id"];
//$filter = $_POST['top_core'];

$sqlCompany = select_query_json("select cmpcode,cmpname from approval_contractors where deleted = 'N' order by cmpcode",'Centra','TEST');
											 //and atc.ATCCODE = '".$filter."'
//".$_SESSION['tcs_empsrno']."
//	print_r($sql_descode);
//print_r($sqlCompany);
$users_arr = array();

 foreach ($sqlCompany as $key => $sres){
$id=$sres['CMPCODE'];	
 $name = $sres['CMPNAME'];
//$name1 = explode(" - ",$name);
$users_arr[] = array("id" => $id, "name" => $name);
 }
echo json_encode($users_arr);
?>