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
echo 'User IP - '.$_SERVER['REMOTE_ADDR'];
$txt_assign=explode(' - ',$_REQUEST['txt_assign']);
print_r($txt_assign);
//echo('1');
?>
