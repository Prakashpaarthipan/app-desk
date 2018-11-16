<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');

$btn_show = select_query_json("select * from approval_project_heirarchy where APPSTAT in ('N','Y') and EMPSRNO = 188 ", "Centra", "TEST");

//print_r ($btn_show);

$i = 0;
for($i=0 ; $i <count($btn_show) ; $i++){

if( $btn_show[$i]['APPSTAT'] == 'Y'){
	echo '1';
}
elseif( $btn_show[$i]['APPSTAT'] == 'N'){
	echo '2';
}
else{
	echo '0';
}
}							
?>