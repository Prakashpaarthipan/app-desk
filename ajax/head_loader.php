<?php
header("Access-Control-Allow-Origin: *");
session_start();
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/function_connect.php');
echo("-----live----------");
//$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//print_r($_REQUEST);

$supplier = select_query_json("select rre.REQSTAT, rre.reqnumb from service_request rre where rre.reqnumb='".$_REQUEST['reqnumb']."'","Centra","TCS");
$clr='';
$bg_clr_class='label-info';
$text='';
if($supplier[0]['REQSTAT']=='N'){ $clr='blue'; $bg_clr_class='label-danger'; $text='NOT ASSIGNED'; }
if($supplier[0]['REQSTAT']=='A'){ $clr='FireBrick'; $bg_clr_class='label-warning'; $text='ASSIGNED'; }
if($supplier[0]['REQSTAT']=='C'){ $clr='green'; $bg_clr_class='label-success'; $text='CLOSED'; }
?>
<div style="line-height: 35px; width: 100%; text-align: center; padding-left: 10px;">
    <div style="float: left; line-height: 35px; padding-left: 10px;"><button class="btn btn-success" onclick="close_call();"><b>Close Call</b></button></div>
    <div style="float: left; line-height: 35px; padding-left: 10px;"><span><b class="highlight_blacktitle">Request Id : <?echo $supplier[0]['REQNUMB']?></b></span></div>
    <div style="float: left; line-height: 35px; padding-left: 10px;"><span class="label <?=$bg_clr_class?> label-form"><b><?echo $text?></b></span></div>
</div>
