<?php
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

if($action == 'find_apptype') {
	$slaset = 0; $slexpn = 0;
	if($slt_core_department == 29) { 
		$slaset = 1;
	} else {
		$slexpn = 1;
	} 

	// echo "**".$slexpn."**"; ?>
	<select class="form-control custom-select chosn" tabindex='19' name='slt_apptype' id='slt_apptype' data-toggle="tooltip" data-placement="top" title="Approval Type">
		<? if($slaset == 1) { ?><option value='ASSET' <? if($sql_reqid[0]['APPTYPE'] == 'ASSET') { ?> selected <? } ?>>ASSET</option><? } ?>
		<? if($slexpn == 1) { ?><option value='EXPENSE' <? if($sql_reqid[0]['APPTYPE'] == 'EXPENSE') { ?> selected <? } ?>>EXPENSE</option><? } ?>
	</select>
	<?
}
?>