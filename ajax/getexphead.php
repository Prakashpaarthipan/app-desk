<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$subtype_id = $_REQUEST['subtype_id'];
$expsrno_hid = "";
if($subtype_id == 7) {
	$expsrno_hid = " and expsrno in (32) ";
}
?>
<select class="form-control" tabindex='4' required name='slt_core_department' id='slt_core_department' data-toggle="tooltip" data-placement="top" onblur="get_dept(this.value)" onChange="get_dept(this.value)" title="Core Department">
<? 	$sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset where DELETED = 'N' and expsrno > 0 ".$expsrno_hid." order by EXPNAME", "Centra", 'TCS');
	for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
		<option value='<?=$sql_project[$project_i]['EXPSRNO']?>' <? if($sql_reqid[0]['EXPSRNO'] == $sql_project[$project_i]['EXPSRNO']) { ?> selected <? } ?>><?=$sql_project[$project_i]['EXPNAME']?></option>
<? } ?>
</select>