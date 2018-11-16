<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
//$sql=select_query_json("Select * From order_tracking_detail where supcode in(29577,29542,29337,27546,27364)", "Centra", 'TCS'); 
// foreach($sql as $key=>$master){
// //print_r($master);
// $g_table="ORDER_TRACKING_DETAIL";
// 			$g_fld['PORYEAR']=$master['PORYEAR'];
// 			$g_fld['PORNUMB']=$master['PORNUMB'];
// 			$g_fld['PORQTY']=$master['PORQTY'];
// 			$g_fld['PORVAL']=$master['PORVAL'];
// 			$g_fld['PORDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['POREDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['SUPCODE']=$master['SUPCODE'];
// 			$g_fld['ZNECODE']=$master['ZNECODE'];
// 			$g_fld['ZNEPCDE']=$master['ZNEPCDE'];
// 			$g_fld['ZNEDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['ZNEFIND']=$master['ZNEFIND'];
// 			$g_fld['ZNESTAT']=$master['ZNESTAT'];
// 			$g_fld['ZNEDUED']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['DUESTAT']=$master['DUESTAT'];
// 			$g_fld['AUTUSER']=$master[$j]['AUTUSER'];
// 			$g_fld['AUTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['REMARKS']=$master['REMARKS'];
// 			$g_fld['BUFFCNT']=$master['BUFFCNT'];
// 			$g_fld['PORSTAT']=$master['PORSTAT'];
// 			$g_fld['ADDUSER']=$master['ADDUSER'];
// 			$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate ;
// 			$g_fld['EDTUSER']=$master['EDTUSER'];
// 			$g_fld['EDTDATE']=$master['EDTDATE'];
// 			$g_fld['DELUSER']=$master['DELUSER'];
// 			$g_fld['DELDATE']=$master['DELDATE'];
// 			$g_fld['DELETED']=$master['DELETED'];
// 			print_r($g_fld);
// 			$g_insert_subject = insert_test_dbquery($g_fld, $g_table);



// }
$sql= select_query_json("select * from order_tracking_detail_031018", "Centra", 'TEST'); 
foreach($sql as $key=> $master){
			$g_table="ORDER_TRACKING_DETAIL";
			$g_fld['PORYEAR']=$master['PORYEAR'];
			$g_fld['PORNUMB']=$master['PORNUMB'];
			$g_fld['PORQTY']=$master['PORQTY'];
			$g_fld['PORVAL']=$master['PORVAL'];
			$g_fld['PORDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['POREDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['SUPCODE']=$master['SUPCODE'];
			$g_fld['ZNECODE']=$master['ZNECODE'];
			$g_fld['ZNEPCDE']=$i;
			$g_fld['ZNEDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['ZNEFIND']=$master['ZNEFIND'];
			$g_fld['ZNESTAT']='T';
			$g_fld['ZNEDUED']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['DUESTAT']=$master['DUESTAT'];
			$g_fld['AUTUSER']=$master['AUTUSER'];
			$g_fld['AUTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['REMARKS']=$master['REMARKS'];
			$g_fld['BUFFCNT']=$master['BUFFCNT'];
			$g_fld['PORSTAT']=$master['PORSTAT'];
			$g_fld['ADDUSER']=$master['ADDUSER'];
			$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['EDTUSER']=$master['EDTUSER'];
			$g_fld['EDTDATE']=$master['EDTDATE'];
			$g_fld['DELUSER']=$master['DELUSER'];
			$g_fld['DELDATE']=$master['DELDATE'];
			$g_fld['DELETED']=$master['DELETED'];
			print_r($g_fld);
			$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
		}
	
	
	
	
	
		 



?>