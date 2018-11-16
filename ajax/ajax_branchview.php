<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
?>
<!-- Choose Branch -->
<div class="col-lg-3 col-xs-3">
	<label style='height:27px;'>Branch <span style='color:red'>*</span></label>
</div>
<div class="col-lg-9 col-xs-9">
	<? if($_REQUEST['action'] == 'view') { ?>
		: <?=$sql_reqid[0]['BRANCH']?>
	<? } else { $allow_branch = explode(",", $_SESSION['tcs_allowed_branch']); 
			if(($_SESSION['tcs_brncode'] == 888 or $_SESSION['tcs_brncode'] == 100) and ($brnch_y_n == 'Y')) {
				$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn 
															where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') and (brn.brncode in (select distinct brncode 
																from budget_planner_head_sum) or brn.brncode in (109,114,117,120)) and 
																brn.brncode not in (11, 22, 202, 205, 119)
															order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
			} else {
				$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn 
															where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') 
																and (brn.brncode in (".$_SESSION['tcs_brncode'].")) and brn.brncode not in (11, 22, 202, 205, 119)
															order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
			} 

		$project_i = -1;
		foreach ($sql_project as $brn_key => $brn_value) { $project_i++; 
			if($project_i % 2 == 0) { 
				$bgclr = "#f0f0f0";
			} else {
				$bgclr = "#ffffff";
			} ?>
			<div style="line-height: 32px; background-color: <?=$bgclr?>">
				<div class="col-xs-4" style="border: 1px solid #c0c0c0; text-align: right;"><?=$sql_project[$project_i]['BRANCH']?> : </div>
				<div class="col-xs-8" style="border: 1px solid #c0c0c0;">
					<input type="hidden" class="form-control" name="slt_brnch[]" id="slt_brnch_<?=$project_i?>" value="<?=$sql_project[$project_i]['BRNCODE']?>" style="margin:2px;">
					<input type="text" class="form-control" name="txt_brnvalue[]" id="txt_brnvalue_<?=$project_i?>" value="" style="margin:2px;">
				</div>
				<div class='clear clear_both'></div>
			</div>
			<div class='clear clear_both'></div>
		<? }
	} ?>
<div class='clear clear_both'></div>
</div>
<div class='clear clear_both'></div>
<!-- Choose Branch -->