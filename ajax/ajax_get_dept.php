<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
?>
<!-- Department Asset -->
<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Department <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<? if($_REQUEST['action'] == 'view') { ?>
			: <?=$sql_reqid[0]['DEPNAME']?>
		<? } else { ?>
			<select class="form-control custom-select chosn" tabindex='3' required name='slt_department_asset' id='slt_department_asset' data-toggle="tooltip" data-placement="top" onblur="get_advancedetails(this.value)" onchange="get_advancedetails(this.value)" title="Department Asset">
			<? 	$sql_project = select_query_json("select * from department_asset where DELETED = 'N' and expsrno = ".$core_deptid." and expsrno > 0 order by DEPNAME", "Centra", 'TCS');
				for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					<option value='<?=$sql_project[$project_i]['DEPCODE']?>' <? if($sql_reqid[0]['DEPCODE'] == $sql_project[$project_i]['DEPCODE']) { ?> selected <? } ?>><?=$sql_project[$project_i]['DEPNAME']?></option>
			<? } ?>
			</select>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Department Asset -->