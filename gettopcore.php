<?php
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
$cm = $_REQUEST['project_id'];

if($action == 'find_topcore') {
	$sql_projects = select_query_json("select distinct TOPCORE from department_asset where expsrno = '".$slt_core_department."'", "Centra", 'TCS');
	echo $sql_projects[0]['TOPCORE'];
} 
elseif($action == 'find_topcore_withname') {
	$sql_projects = select_query_json("select distinct apm.APMCODE, apm.APMNAME, apm.TARNUMB, atc.ATCCODE, atc.ATCNAME, sec.esecode, substr(sec.esename, 4, 25) esename 
											from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec 
											where sec.esecode = apm.subcore and apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' 
												and apm.SUBCORE = '".$slt_subcore."' and rownum <= 1 order by apm.APMNAME asc", "Centra", 'TEST');
	if(count($sql_projects) <= 0) {
		$sql_projects = select_query_json("select distinct apm.APMCODE, apm.APMNAME, apm.TARNUMB, atc.ATCCODE, atc.ATCNAME, sec.esecode, substr(sec.esename, 4, 25) esename 
												from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec 
												where apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' 
													and apm.SUBCORE = '".$slt_subcore."' and rownum <= 1 order by apm.APMNAME asc", "Centra", 'TEST');
		if(count($sql_projects) <= 0) {
			$sql_ur = select_query_json("select distinct sec.grpsrno, sgrp.SECNAME from attn_section_group sgrp, section sec 
												where sgrp.seccode=sec.seccode and sgrp.ESECODE in (".$slt_subcore.")");
			if(count($sql_ur) > 0) { $sql_projects[0]['ATCCODE'] = '3'; $sql_projects[0]['ATCNAME'] = 'OPERATION'; }
		}
	}
	echo $sql_projects[0]['ATCCODE']."!!".$sql_projects[0]['ATCNAME'];
}

 else { 
	if($slt_core_department != '' and ($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7 )) {
		$sql_projects = select_query_json("select distinct TOPCORE from department_asset where expsrno = '".$slt_core_department."'", "Centra", 'TCS');
		$sql_project = select_query_json("select * from APPROVAL_TOPCORE where DELETED = 'N' and atccode in (".$sql_projects[0]['TOPCORE'].") order by ATCSRNO Asc", "Centra", 'TCS');
	} else {
		$sql_projects = select_query_json("select ATCCODE from APPROVAL_PROJECT where DELETED = 'N' order by APRNAME Asc", "Centra", 'TCS');
		$sql_project = select_query_json("select * from APPROVAL_TOPCORE where DELETED = 'N' order by ATCSRNO Asc", "Centra", 'TCS');
	}
	?>
	<!-- Top Core -->
	<div class="form-group trbg">
		<div class="col-lg-3 col-md-3">
			<label style='height:27px;'>Top Core <span style='color:red'>*</span></label>
		</div>
		<div class="col-lg-9 col-md-9">
			<? if($_REQUEST['action'] == 'view') { ?>
				<? 	$sql_project = select_query_json("select * from approval_topcore where DELETED = 'N' and ATCCODE = '".$sql_reqid[0]['ATCCODE']."' order by ATCSRNO Asc", "Centra", 'TCS');
					for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					: <?=$sql_project[$project_i]['ATCNAME']?>
				<? } ?>
			<? } else { ?>
				<? if($_REQUEST['action'] == 'edit') { ?> <input type='hidden' name='hid_slt_topcore' id='hid_slt_topcore' value='<?=$sql_reqid[0]['ATCCODE']?>'> <? } ?>
				<select class="form-control" tabindex='5' required name='slt_topcore' id='slt_topcore' data-toggle="tooltip" data-placement="top" <? if($_REQUEST['action'] == 'edit') { ?> readonly <? } ?> title="Top Core" onChange="getsubcore(this.value)" onBlur="getsubcore(this.value)">
				<? 	for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
						<option value='<?=$sql_project[$project_i]['ATCCODE']?>' <? if($sql_reqid[0]['ATCCODE'] == $sql_project[$project_i]['ATCCODE']) { ?> selected <? } ?>><?=$sql_project[$project_i]['ATCNAME']?></option>
				<? 	} ?>
				</select>
			<? } ?>
		</div>
	</div>
	<div class='clear clear_both'></div>
	<!-- Top Core -->
<? } ?>