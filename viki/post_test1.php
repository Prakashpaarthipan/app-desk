<?php
header("Access-Control-Allow-Origin: *");

//header('Location: ../process_requirement_entry.php');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');

header("Content-Type: application/json; charset=UTF-8");
echo("running");
$result = select_query_json("exec skip_timer()", "Centra", 'TEST');
print_r($result);
?>
