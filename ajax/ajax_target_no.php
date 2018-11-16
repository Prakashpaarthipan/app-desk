<?php
session_start();
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$brnc = $slt_branch;
$depc = $deptid;
$cur_mn = date("m");
// $cur_mn = '03';
?>
<? /* <!-- Type of Submission Type & Sub Type -->
<select class="form-control custom-select chosn" tabindex='4' required name='slt_targetno' id='slt_targetno' data-toggle="tooltip" data-placement="top" title="Target No" onblur="get_targetdates()" onchange="get_targetdates()">
<? 	$sql_tarno = select_query("select round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||( select decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname 
											from trandata.non_purchase_target@tcscentr tar, trandata.department_asset@tcscentr Dep 
											where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) Depname 
										from trandata.budget_planner_branch@tcscentr bpl 
										where depcode=".$depc." and brncode=".$brnc." and TARYEAR=".(date("y")-1)." and TARMONT=".date("m")." 
										order by Depname");
	for($tarno_i = 0; $tarno_i < count($sql_tarno); $tarno_i++) { ?>
		<option value='<?=$sql_tarno[$tarno_i]['TARNUMB']?>' <? if($sql_reqid[0]['TARNUMB'] == $sql_tarno[$tarno_i]['TARNUMB']) { ?> selected <? } ?>><?=$sql_tarno[$tarno_i]['DEPNAME']?></option>
<? } ?>
</select>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Sub Type --> */ ?>

<!-- Target No -->
<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Target No <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<select class="form-control" tabindex='4' required name='slt_targetno' id='slt_targetno' data-toggle="tooltip" data-placement="top" title="Target No" onblur="get_targetdates()">
		<? 	$sql_tarno = select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||( select distinct decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname 
													from non_purchase_target tar, department_asset Dep 
													where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) Depname 
												from budget_planner_branch bpl 
												where depcode=".$depc." and brncode=".$brnc." and TARYEAR=".(date("y")-1)." and TARMONT=".date("m")." and (tarnumb>8000 or tarnumb in (7632, 7630))   
												order by Depname", "Centra", 'TCS');
			for($tarno_i = 0; $tarno_i < count($sql_tarno); $tarno_i++) { ?>
				<option value='<?=$sql_tarno[$tarno_i]['TARNUMB']?>' <? if($sql_reqid[0]['TARNUMB'] == $sql_tarno[$tarno_i]['TARNUMB']) { ?> selected <? } ?>><?=$sql_tarno[$tarno_i]['DEPNAME']?></option>
		<? } ?>
		</select>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Target No -->