<?php
header('Content-Type: text/html; charset=utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
include_once('../lib/function_connect.php');?>

	<select class="form-control custom-select chosn" tabindex='1' required name='txt_core' id='txt_core' data-toggle="tooltip" data-placement="top" data-original-title="Top Core" onChange="find_tags();">
	<?$sql_projectc = select_query_json("select corname,esecode from empcore_section where topcore='".$_REQUEST['topcore']."'", "Centra", 'TEST');
	for($project_i = 0; $project_i < count($sql_projectc); $project_i++) { ?>
	
	<option value='<?=$sql_projectc[$project_i]['ESECODE']?>' <? if($sql_projectc[$project_i]['ESECODE'] == $sql_search[0]['CORCODE']) { ?> selected <? } ?>><?=$sql_projectc[$project_i]['CORNAME'] ?></option>
	
<? } ?>
	</select>