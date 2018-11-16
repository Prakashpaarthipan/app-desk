<?php
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');


	$sql_projectc = select_query_json("select corname,esecode from empcore_section where topcore='1'", "Centra", 'TEST');
	print_r($sql_projectc)?>
	