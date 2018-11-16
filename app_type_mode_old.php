<?php
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_POST);
$cm = $_REQUEST['subtype_value_id'];
$slt_submission = $_REQUEST['slt_submission'];

// $sql_approval_type_mode = select_query("select * from approval_master where ATMCODE = '".$cm."' and ATYCODE = '".$slt_submission."' and DELETED = 'N' order by APMNAME Asc", "Centra", 'TEST');
if($slt_submission == 1) {
	$sql_approval_type_mode = select_query_json("select * from approval_master 
														where ATYCODE in (1, 6, 7) and DELETED = 'N' and DELUSER is null and tarnumb = '".$slt_targetno."' 
														order by APMNAME Asc", "Centra", 'TCS'); 
} 
	// if($slt_subcore != '' and $slt_submission == 4) {
	elseif($slt_submission == 4) {
		$sql_approval_type_mode = select_query_json("select * from approval_master 
															where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore = '".$slt_subcore."' 
															order by APMNAME Asc", "Centra", 'TEST');
		if(count($sql_approval_type_mode) <= 0) {
			$sql_ur = select_query_json("select distinct sec.grpsrno, sgrp.SECNAME from attn_section_group sgrp, section sec 
												where sgrp.seccode=sec.seccode and sgrp.ESECODE in (".$slt_subcore.")");
			if($sql_ur[0]['GRPSRNO'] == 1 or $sql_ur[0]['GRPSRNO'] == 3) { // 1 or 3 READYMADE
				$sql_approval_type_mode = select_query_json("select * from approval_master 
																	where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore = '-2'  
																	order by APMNAME Asc", "Centra", 'TCS');
			} elseif($sql_ur[0]['GRPSRNO'] == 2 or $sql_ur[0]['GRPSRNO'] == 4) { // TEXTILE
				$sql_approval_type_mode = select_query_json("select * from approval_master 
																	where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore = '-1'  
																	order by APMNAME Asc", "Centra", 'TCS');
			}
		}
	}
	/*
	elseif($slt_submission == 9) {// testing renewal catagories
		$sql_approval_type_mode = select_query_json("select * from approval_master 
															where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore = '".$slt_subcore."'
															order by APMNAME Asc", "Centra", 'TEST');
	}
	elseif($slt_submission == 10 || $slt_submission == 11) {// testing renewal catagories
		$sql_approval_type_mode = select_query_json("select * from approval_master 
															where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore = '".$slt_subcore."'
															order by APMNAME Asc", "Centra", 'TEST');
	}*/
	

	
	 else
	 {
		$sql_approval_type_mode = select_query_json("select * from approval_master 
															where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null and subcore =  '".$slt_subcore."'
															order by APMNAME Asc", "Centra", 'TEST');
	}
	
	/* } else {
		$sql_approval_type_mode = select_query_json("select * from approval_master 
															where ATYCODE = '".$slt_submission."' and DELETED = 'N' and DELUSER is null 
															order by APMNAME Asc", "Centra", 'TCS');
	} */




/* <!-- Approval Type Mode -->
<div class="form-group trbg">
	<div class="col-lg-3 col-md-3">
		<label style='height:27px;'>Approval Listings <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-md-9"> */


	//Prakash Comment 13.10.2018
	
	/*if($slt_submission == 10 || $slt_submission == 11) {?>
			<select class="form-control custom-select chosn" tabindex='6' required name='slt_approval_listings' id='slt_approval_listings' data-toggle="tooltip" data-placement="top" title="Approval Listings">
		<option value=''>Choose The Option </option>
		<?  for($approval_type_mode_i = 0; $approval_type_mode_i < count($sql_approval_type_mode); $approval_type_mode_i++) { ?>
			<option value='<?=$sql_approval_type_mode[$approval_type_mode_i]['APMCODE']?>' <? if($sql_reqid[0]['APMCODE'] == $sql_approval_type_mode[$approval_type_mode_i]['APMCODE']) { ?> selected <? } ?>><?=$sql_approval_type_mode[$approval_type_mode_i]['APMNAME']?></option>
		<? } ?>
		</select>
		<?}
		else{*/?>
		<select class="form-control custom-select chosn" tabindex='6' required name='slt_approval_listings' id='slt_approval_listings' data-toggle="tooltip" data-placement="top" title="Approval Listings" <? if($_REQUEST['action'] == 'edit') { ?> disabled <? } else { ?> onChange="get_advancedetails(); getapproval_listings(this.value)" onblur="call_days()" <? } ?>>
		
		<?  for($approval_type_mode_i = 0; $approval_type_mode_i < count($sql_approval_type_mode); $approval_type_mode_i++) { ?>
			<option value='<?=$sql_approval_type_mode[$approval_type_mode_i]['APMCODE']?>' <? if($sql_reqid[0]['APMCODE'] == $sql_approval_type_mode[$approval_type_mode_i]['APMCODE']) { ?> selected <? } ?>><?=$sql_approval_type_mode[$approval_type_mode_i]['APMNAME']?></option>
		<? } ?>
		</select>
	<?
	
	 /* </div>
</div>
<div class='clear clear_both'></div>
<!-- Approval Type Mode --> */ ?>