<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST); ?>
<ul class="list-tags">
<? 
$save_data[] = '';
$save_term[] = '';
$save_prcs[] = '';

// Top Core
if($txtTopcore != '') { 
	$sql_project = select_query_json("select * from approval_topcore where deleted = 'N' and ATCCODE = '".$txtTopcore."' order by ATCSRNO", 'Centra', 'TCS'); 
	if($sql_project[0]['ATCCODE'] != '') { ?>
		<li>
			<a href="requirement-search-result.php?data=TOPCORE&term=<?=$sql_project[0]['ATCCODE']?>&process=<?=$sql_project[0]['ATCNAME']?>" target="_blank" class="li_redtags"><span class="fa fa-tag"></span> <?=$sql_project[0]['ATCNAME']?></a>
			<input type="hidden" name="txttag_data[]" id="txttag_data_project" maxlength="100" value="TOPCORE">
			<input type="hidden" name="txttag_term[]" id="txttag_term_project" maxlength="100" value="<?=$sql_project[0]['ATCCODE']?>">
			<input type="hidden" name="txttag_process[]" id="txttag_process_project" maxlength="100" value="<?=$sql_project[0]['ATCNAME']?>">
		</li>
<? 	
	$save_data[] = 'TOPCORE';
	$save_term[] = $sql_project[0]['ATCCODE'];
	$save_prcs[] = $sql_project[0]['ATCNAME'];
	}
} 
// Top Core
//priority
if($txtTopcore != '') { 
	$sql_project = select_query_json("select * from APPROVAL_Priority where deleted = 'N' and PRICODE = '".$Priority."' order by PRICODE", 'Centra', 'TCS'); 
	if($sql_project[0]['PRICODE'] != '') { ?>
		<li>
			<a href="requirement-search-result.php?data=TOPCORE&term=<?=$sql_project[0]['PRICODE']?>&process=<?=$sql_project[0]['PRINAME']?>" target="_blank" class="li_redtags"><span class="fa fa-tag"></span><?=$sql_project[0]['PRICODE']?>-<?=$sql_project[0]['PRINAME']?></a>
			<input type="hidden" name="txttag_data[]" id="txttag_data_project" maxlength="100" value="PRIORITY">
			<input type="hidden" name="txttag_term[]" id="txttag_term_project" maxlength="100" value="<?=$sql_project[0]['PRICODE']?>">
			<input type="hidden" name="txttag_process[]" id="txttag_process_project" maxlength="100" value="<?=$sql_project[0]['PRINAME']?>">
		</li>
<? 	
	$save_data[] = 'PRIORITY';
	$save_term[] = $sql_project[0]['PRICODE'];
	$save_prcs[] = $sql_project[0]['PRINAME'];
	}
} 
//priority
//assign member
if($txt_assign != '') { 
	$expl = explode(" - ", $txt_assign);
	$sql_employee = select_query_json("select * from employee_office where EMPCODE = '".$expl[0]."' order by EMPCODE", 'Centra', 'TCS'); 
	if($sql_employee[0]['EMPSRNO'] != '') { ?>
		<li>
			<a href="search-result.php?data=EMPLOYEE&term=<?=$sql_employee[0]['EMPSRNO']?>&process=<?=$sql_employee[0]['EMPCODE']." - ".$sql_employee[0]['EMPNAME']?>" target="_blank" class="li_greentags"><span class="fa fa-tag"></span> <?=$sql_employee[0]['EMPCODE']." - ".$sql_employee[0]['EMPNAME']?></a>
			<input type="hidden" name="txttag_data[]" id="txttag_data_project" maxlength="100" value="EMPLOYEE">
			<input type="hidden" name="txttag_term[]" id="txttag_term_project" maxlength="100" value="<?=$sql_employee[0]['EMPSRNO']?>">
			<input type="hidden" name="txttag_process[]" id="txttag_process_project" maxlength="100" value="<?=$sql_employee[0]['EMPCODE']." - ".$sql_employee[0]['EMPNAME']?>">
		</li>
<? 
	}
} 
//assign memeber
//core
if($txt_core != '') { 
	$expl = explode(" - ", $txt_core);
	$sql_employee = select_query_json("select corname,esecode from empcore_section where esecode='".$txt_core."'", 'Centra', 'TCS'); 
	if($sql_employee[0]['ESECODE'] != '') { ?>
		<li>
			<a href="search-result.php?data=EMPLOYEE&term=<?=$sql_employee[0]['EMPSRNO']?>&process=<?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?>" target="_blank" class="li_greentags"><span class="fa fa-tag"></span> <?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?></a>
			<input type="hidden" name="txttag_data[]" id="txttag_data_project" maxlength="100" value="CORE">
			<input type="hidden" name="txttag_term[]" id="txttag_term_project" maxlength="100" value="<?=$sql_employee[0]['ESECODE']?>">
			<input type="hidden" name="txttag_process[]" id="txttag_process_project" maxlength="100" value="<?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?>">
		</li>
<? 
	}
} 

//core

//tar date
/*if($tar_date != '') { 
	$expl = explode(" - ", $tar_date);
	$sql_employee = select_query_json("select corname,esecode from empcore_section where esecode='".$txt_core."'", 'Centra', 'TCS'); 
	if($sql_employee[0]['ESECODE'] != '') { ?>
		<li>
			<a href="search-result.php?data=EMPLOYEE&term=<?=$sql_employee[0]['EMPSRNO']?>&process=<?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?>" target="_blank" class="li_greentags"><span class="fa fa-tag"></span> <?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?></a>
			<input type="hidden" name="txttag_data[]" id="txttag_data_project" maxlength="100" value="EMPLOYEE">
			<input type="hidden" name="txttag_term[]" id="txttag_term_project" maxlength="100" value="<?=$sql_employee[0]['ESECODE']?>">
			<input type="hidden" name="txttag_process[]" id="txttag_process_project" maxlength="100" value="<?=$sql_employee[0]['ESECODE']." - ".$sql_employee[0]['CORNAME']?>">
		</li>
<? 
	}
} */
//tar date

?>
</ul>
