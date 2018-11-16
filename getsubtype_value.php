<?
session_start();
error_reporting(0);
include("../db_connect/public_functions.php");
extract($_POST);
if($_SESSION['auditor_login'] == 1) { ?>
	<script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
	<?
	exit();
}
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
 <title>JQ - Image Upload</title>
<style>
.MultiFile-label { padding:3px 10px; background-color:#d8d8d8; color:#000000; border:1px solid #666666; float:left; min-width:100px; width:auto; margin:5px 3px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
</style>
</head>

<body>
<div class="MultiFile-wrap" id="txt_submission_fieldimpl_wrap">
<?php
$cm = $_REQUEST['subtype_value_id'];

switch($cm)
{
	case 1: 
		$slt_submission = 1;
		break;
	case 7: 
		$slt_submission = 1;
		break;
	case 11: 
		$slt_submission = 1;
		break;
	case 15: 
		$slt_submission = 1;
		break;
		
	case 2: 
		$slt_submission = 2;
		break;
	case 8: 
		$slt_submission = 2;
		break;
	case 12: 
		$slt_submission = 2;
		break;
	case 16: 
		$slt_submission = 2;
		break;
		
	case 5: 
		$slt_submission = 3;
		break;
	case 9: 
		$slt_submission = 3;
		break;
	case 13: 
		$slt_submission = 3;
		break;
	case 17: 
		$slt_submission = 3;
		break;
		
	case 6: 
		$slt_submission = 4;
		break;
	case 10: 
		$slt_submission = 4;
		break;
	case 14: 
		$slt_submission = 4;
		break;
	case 18: 
		$slt_submission = 4;
		break;
		
	case 3: 
		$slt_submission = 5;
		break;
		
	case 4: 
		$slt_submission = 6;
		break;
		
	default: 
		$slt_submission = 1;
		break;
}
// echo "**".$slt_submission."**";
?>
<!-- Type of Submission Type & Request by -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Request by <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? 	
			$sql_emp = select_query("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
												from trandata.employee_office@tcscentr emp, trandata.empsection@tcscentr sec, trandata.designation@tcscentr des, trandata.employee_salary@tcscentr sal 
												where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empsrno = ".$sql_reqid[0]['DELUSER'].") and sec.deleted = 'N' and sec.deleted = 'N' 
													and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno 
											union
												select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
												from trandata.employee_office@tcscentr emp, trandata.new_empsection@tcscentr sec, trandata.new_designation@tcscentr des, trandata.employee_salary@tcscentr sal 
												where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empsrno = ".$sql_reqid[0]['DELUSER'].") and sec.deleted = 'N' and sec.deleted = 'N' 
													and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno 
												order by EMPCODE");

			if($_REQUEST['action'] == 'view') {
				// echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".$sql_emp[0]['ESENAME']." (".$sql_emp[0]['DATEOFJOIN'].")";
				echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".$sql_emp[0]['ESENAME']."";
		   } else { ?>
			<? /* <input class="form-control" placeholder="Material Request by" onfocus="call_days()" tabindex='9' required maxlength='50' name='txt_submission_requser' id='txt_submission_requser' readonly value='<?=strtoupper($_SESSION['tcs_username'])?>' data-toggle="tooltip" data-placement="top" title="Material Request by">
			<input class="form-control" placeholder="Material Request by" onfocus="call_days()" tabindex='9' required maxlength='8' name='txt_submission_reqby' id='txt_submission_reqby' value='<?=$_SESSION['tcs_empsrno']?>' style='display:none;' data-toggle="tooltip" data-placement="top" title="Material Request by">?>
			<select class="form-control" tabindex='9' required name='txt_submission_reqby' id='txt_submission_reqby' onchange="getapproval_salaryadvance(this.value)" onfocus="getapproval_salaryadvance(this.value)" data-toggle="tooltip" data-placement="top" title="Request by">
			<? $sql_project = select_query("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY from employee_office emp, empsection sec, designation des, employee_salary sal 
where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode > 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
union
select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode > 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno order by EMPCODE");
				for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					<option value='<?=$sql_project[$project_i]['EMPSRNO']?>' <? if($sql_reqid[0]['DELUSER'] == $_SESSION['tcs_empsrno']) { ?> selected <? } ?>><? echo $sql_project[$project_i]['EMPCODE']." - ".$sql_project[$project_i]['EMPNAME']." - ".$sql_project[$project_i]['ESENAME']." "; ?></option>
			<? /* ?>
					<option value='<?=$sql_project[$project_i]['EMPSRNO']?>' <? if($sql_reqid[0]['DELUSER'] == $_SESSION['tcs_empsrno']) { ?> selected <? } ?>><? echo $sql_project[$project_i]['EMPCODE']." - ".$sql_project[$project_i]['EMPNAME']." - ".$sql_project[$project_i]['ESENAME']." (".$sql_project[$project_i]['DATEOFJOIN'].")"; ?></option>
			<?  } */ ?>
				
				<input type='text' class="form-control" tabindex='9' required name='txt_submission_reqby' id='txt_submission_reqby' onblur="getapproval_salaryadvance(this.value)" onfocus="getapproval_salaryadvance(this.value)" data-toggle="tooltip" data-placement="top" title="Request by" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
			
			</select>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Request by -->

<? if($slt_submission == 1 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
<!-- Type of Submission Type & Budget Supporting Documents -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Budget Supporting Documents</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_fieldimpl_", $sql_reqid[0]['FLDIMPI'], 'fieldimpl');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Budget Supporting Documents" tabindex='10' maxlength='150' type='file' name='txt_submission_fieldimpl[]' id='txt_submission_fieldimpl' multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Budget Supporting Documents">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_fieldimpl_", $sql_reqid[0]['FLDIMPI'], 'fieldimpl'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Budget Supporting Documents -->

<!-- Other Documents -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Other Documents</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_othersupdocs_", $sql_reqid[0]['FLDIMPI'], 'othersupdocs');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Other Documents" tabindex='10' maxlength='150' type='file' name='txt_submission_othersupdocs[]' id='txt_submission_othersupdocs' multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Other Documents">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_othersupdocs_", $sql_reqid[0]['FLDIMPI'], 'othersupdocs'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Other Documents -->
<? } ?>

<? if($slt_submission == 2 or $slt_submission == 5) { ?>
<!-- Type of Submission Type & Last Approval -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Last Approval</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_lastapproval_", $sql_reqid[0]['LSTAPRI'], 'lastapproval');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Last Approval" tabindex='11' maxlength='150' type='file' name='txt_submission_last_approval[]' id='txt_submission_last_approval' multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Last Approval">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_lastapproval_", $sql_reqid[0]['LSTAPRI'], 'lastapproval'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Last Approval -->
<? } ?>

<? if($slt_submission == 1 or $slt_submission == 2 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
<!-- Type of Submission Type & Quotations -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Quotations</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_quotations_", $sql_reqid[0]['QUTAT1I'], 'quotations');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Quotations" tabindex='12' maxlength='150' type='file' name='txt_submission_quotations[]' id='txt_submission_quotations' multiple value='' accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" data-toggle="tooltip" data-placement="top" title="Quotations">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_quotations_", $sql_reqid[0]['QUTAT1I'], 'quotations'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Quotations -->
<? } ?>

<!-- Type of Submission Type & Color Photo / Sample -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Color Photo Sample</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_clrphoto_", $sql_reqid[0]['SMPLPTI'], 'clrphoto');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Color Photo / Sample" tabindex='13' <? /* if($_REQUEST['action'] == 'edit') { } else { ?>required<? } */ ?> maxlength='150' type='file' name='txt_submission_clrphoto[]' id='txt_submission_clrphoto' multiple value='' accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" data-toggle="tooltip" data-placement="top" title="Color Photo / Sample">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_clrphoto_", $sql_reqid[0]['SMPLPTI'], 'clrphoto'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both' style='margin-bottom:0px'></div>
<!-- Type of Submission Type & Color Photo / Sample -->

<? //////////////////////////////////////////////////////////////////////// OPEN ONLY THIS ART WORK ?>
<!-- Type of Submission Type & Art Work Sample -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Art Work</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_clrphoto_", $sql_reqid[0]['SMPLPTI'], 'clrphoto');
		   } else { ?>
				<div><input class="form-control multi" placeholder="Art Work" tabindex='13' <? ?> maxlength='150' type='file' name='txt_submission_artwork[]' id='txt_submission_artwork' multiple value='' accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,application/pdf,.pdf" data-toggle="tooltip" data-placement="top" title="Art Work">( Allow only pdf, jpg, png, gif images )</div>
				<div class='clear clear_both' style='min-height:10px;'></div>
				<div><? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqid[0]['ARQCODE']."_".$sql_reqid[0]['ATYCODE']."_".$sql_reqid[0]['ATCCODE']."_".$sql_reqid[0]['ARQYEAR']."_clrphoto_", $sql_reqid[0]['SMPLPTI'], 'clrphoto'); } ?></div>
				<div class='clear clear_both' style='min-height:10px;'></div>
		<? } ?>
	</div>
</div>
<div class='clear clear_both' style='margin-bottom:10px'></div>
<!-- Type of Submission Type & Art Work Sample -->


<? /* 
<!-- Type of Submission Type & Request by -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Material Request by <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
			$sql_emp = select_query("select * from employee_office where empsrno = ".$sql_reqsd[0]['DELUSER']);
			echo ": ".$sql_emp[0][3];
		   } else { ?>
			<input class="form-control" placeholder="Material Request by" onfocus="call_days()" tabindex='9' required maxlength='50' name='txt_submission_requser' id='txt_submission_requser' readonly value='<?=strtoupper($_SESSION['tcs_username'])?>' data-toggle="tooltip" data-placement="top" title="Material Request by">
			<input class="form-control" placeholder="Material Request by" onfocus="call_days()" tabindex='9' maxlength='8' name='txt_submission_reqby' id='txt_submission_reqby' value='<?=$_SESSION['tcs_empsrno']?>' style='display:none;' data-toggle="tooltip" data-placement="top" title="Material Request by">
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Request by -->

<? if($slt_submission == 1 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
<!-- Type of Submission Type & Budget Supporting Documents -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Budget Supporting Documents</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqsd[0]['FLDIMPL'], $sql_reqsd[0]['FLDIMPI']);
		   } else { ?>
				<input class="form-control" placeholder="Budget Supporting Documents" tabindex='10' maxlength='150' type='file' name='txt_submission_fieldimpl[]' id='txt_submission_fieldimpl' multiple accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/plain,image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Budget Supporting Documents">
				<? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqsd[0]['FLDIMPL'], $sql_reqsd[0]['FLDIMPI']); } ?>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Budget Supporting Documents -->
<? } ?>

<? if($slt_submission == 5) { ?>
<!-- Type of Submission Type & Due Date -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Due Date</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo ": ".$sql_reqsd[0]['DUEDATE'];
		   } else { ?>
				<div class='input-group date' id='datetimepicker8' tabindex='14' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input3">
					<input type='text' class="form-control" size="20" tabindex='15' name='txtdue_date' placeholder='Due Date' id='txtdue_date' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqsd[0]['DUEDATE']?>"<? } else { ?>value="<?=date("d-m-y h:00 A")?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="Due Date" />
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
				<input type="hidden" id="dtp_input3" name='dtp_input3' value="" />
		<? } ?>
	</div>
</div>
<div class='clear' style='clear:both;'></div>
<!-- Type of Submission Type & Due Date -->
<? } ?>

<? if($slt_submission == 2 or $slt_submission == 5) { ?>
<!-- Type of Submission Type & Last Approval -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Last Approval</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqsd[0]['LSTAPPR'], $sql_reqsd[0]['LSTAPRI']);
		   } else { ?>
				<input class="form-control" placeholder="Last Approval" tabindex='11' maxlength='150' type='file' name='txt_submission_last_approval[]' id='txt_submission_last_approval' multiple accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/plain,image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Last Approval">
				<? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqsd[0]['LSTAPPR'], $sql_reqsd[0]['LSTAPRI']); } ?>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Last Approval -->
<? } ?>

<? if($slt_submission == 1 or $slt_submission == 2 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
<!-- Type of Submission Type & Quotations -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Quotations</label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqsd[0]['QUTATN1'], $sql_reqsd[0]['QUTAT1I']);
		   } else { ?>
				<input class="form-control" placeholder="Quotations" tabindex='12' maxlength='150' type='file' name='txt_submission_quotations[]' id='txt_submission_quotations' multiple value='' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/plain,image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" data-toggle="tooltip" data-placement="top" title="Quotations">
				<? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqsd[0]['QUTATN1'], $sql_reqsd[0]['QUTAT1I']); } ?>
		<? } ?>
	</div>
</div>
<div class='clear clear_both'></div>
<!-- Type of Submission Type & Quotations -->
<? } ?>

<!-- Type of Submission Type & Color Photo / Sample -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Color Photo / Sample <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-md-9">
		<? if($_REQUEST['action'] == 'view') {
				echo find_data($sql_reqsd[0]['SMPLPTO'], $sql_reqsd[0]['SMPLPTI']);
		   } else { ?>
				<input class="form-control" placeholder="Color Photo / Sample" tabindex='13' <? if($_REQUEST['action'] == 'edit') { } else { ?>required<? } ?> maxlength='150' type='file' name='txt_submission_clrphoto[]' id='txt_submission_clrphoto' multiple value='' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,text/plain,image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" data-toggle="tooltip" data-placement="top" title="Color Photo / Sample">
				<? if($_REQUEST['action'] == 'edit') { echo find_data($sql_reqsd[0]['SMPLPTO'], $sql_reqsd[0]['SMPLPTI']); } ?>
		<? } ?>
	</div>
</div>
<div class='clear clear_both' style='margin-bottom:10px'></div>
<!-- Type of Submission Type & Color Photo / Sample --> <? */ ?>

<? /* <!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Upload & Preview -->
<script src='js/jquery.MultiFile.js' type='text/javascript' language='javascript'></script> */ ?>
<!-- Upload & Preview -->
</div>
</body>
</html>