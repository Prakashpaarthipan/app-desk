<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

$pathid = 1;
$sql_dynamic_option = select_query_json("select * from approval_npo_applist where apmcode = ".$slt_approval_listings." ", "Centra", 'TCS');
if(count($sql_dynamic_option) > 0) { $pathid = $sql_dynamic_option[0]['PATHID']; }
if($slt_approval_listings == 786 or $slt_approval_listings == 408 or $slt_approval_listings == 143 or $slt_approval_listings == 97 or $slt_approval_listings==105){
	$pathid = 2;
}
if($pathid == 1){
	/* if($_SESSION['tcs_empsrno']==43878){
		echo "CAME";
	} */
	$brnc = $slt_branch;
	$depc = $deptid;
	$core_dep = $core_deptid;
	$targtno = explode("||", $target_no);
	$tarno = $targtno[0];
	$ttl_lock = 0;

	$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
	// $frdt = $sql_tarno[0]['PTFDATE'];
	// $todt = $sql_tarno[0]['PTTDATE'];

	$explode_yr = explode("-", $current_year[0]['PORYEAR']);
	$explode_cryr = substr($explode_yr[0], 2);
	if($action == 'choose') {
		/* $date_quarter = date_quarter() + 1;
		switch ($date_quarter) {
			case 1:
					$frdt = "01-MAR-".date("y");
					break;
			case 2:
					$frdt = "01-JUN-".date("y");
					break;
			case 3:
					$frdt = "01-OCT-".date("y");
					break;
			default:
					$frdt = "01-JAN-".date("y", strtotime('+1 year'));
					break;
		} */

		$frdt = date("01-M-y", strtotime('+1 month'));
	} else {
		// $date_quarter = date_quarter();
		$frdt = date("01-M-y");
	}

	// ECHO "**".$frdt."**".date("01-M-y")."**".$date_quarter."**";
	/* switch ($date_quarter) {
		case 1:
			$todt = "30-JUN-".$explode_cryr;
			$bud_quarter = "BUDQUARTER_1";
			$app_quarter = "APPQUARTER_1";
			break;

		case 2:
			$todt = "30-SEP-".$explode_cryr;
			$bud_quarter = "BUDQUARTER_2";
			$app_quarter = "APPQUARTER_2";
			break;

		case 3:
			$todt = "31-DEC-".$explode_cryr;
			$bud_quarter = "BUDQUARTER_3";
			$app_quarter = "APPQUARTER_3";
			break;

		case 4:
			$todt = "31-MAR-".$explode_yr[1];
			$bud_quarter = "BUDQUARTER_4";
			$app_quarter = "APPQUARTER_4";
			break;
		
		default:
			$todt = "31-MAR-".$explode_yr[1];
			$bud_quarter = "BUDQUARTER_4";
			$app_quarter = "APPQUARTER_4";
			break;
	} *//// GA - 27-09-2018 for Quarter to Financial Year Based approvals

	$todt = "31-MAR-".$explode_yr[1];
	$bud_quarter = "BUDQUARTER_3";
	$app_quarter = "APPQUARTER_3";
	// break;

	$nxtyr = $current_year[0]['PORYEAR']; // 07-03-2018
	$minvl = date('m/Y', strtotime($frdt));
	$maxvl = date('m/Y', strtotime($todt));
	$minvl1 = date('m,Y', strtotime($frdt));
	$maxvl1 = date('m,Y', strtotime($todt));

	$budyr = date('Y', strtotime($frdt));
	$budmn = date('m', strtotime($frdt));

	$prev_month_ts = strtotime(date('01-m-Y').' -1 month');
	$crnyr = date('Y', $prev_month_ts);
	$crnmn = date('m', $prev_month_ts);

	$fdt = explode("/", $minvl);
	$tdt = explode("/", $maxvl);
	$ivl = 0; $ii = ''; $fstmnth = ''; $lstmnth = '';
	$can_edit = 1; $add_month = '';
	if($slt_approval_listings == '807' || $slt_approval_listings == '777') { // 807 - STAFF MONTHLY SALARY & 777 - BANK SALARY CREDIT
		if($slt_approval_listings == '807') { // 807 - STAFF MONTHLY SALARY
			$notin = " and status not in ('C') ";
		} elseif($slt_approval_listings == '777') { // 777 - BANK SALARY CREDIT
			$notin = " and status not in ('B') ";
		}

		$sql_paymntyr = select_query_json("select * from auto_update_process where AUPCODE = 34", "Centra", 'TCS');
		$sql_mntsal = select_query_json("select * from attn_monthly_salary_detail 
											where brncode = ".$brnc." and payyear = ".$sql_paymntyr[0]['PAYYEAR']." and paymont = ".$sql_paymntyr[0]['PAYMONT']." 
												and status in ('N', 'C', 'B') ".$notin."", "Centra", 'TCS');
		
		// echo "select * from attn_monthly_salary_detail where brncode = ".$brnc." and payyear = ".$crnyr." and paymont = ".$crnmn." and status in ('N', 'C', 'B') ".$notin."";
		if(count($sql_mntsal) > 0) {
			$add_month = $sql_mntsal[0]['PAYMONT'].", ".$sql_mntsal[0]['PAYYEAR'];
			$can_edit = 0;
		} else {
			$can_edit = 1;
		}

		$add_month_name = findmonth($sql_mntsal[0]['PAYMONT']).", ".$sql_mntsal[0]['PAYYEAR'];
		if($slt_approval_listings == '807') { // 807 - STAFF MONTHLY SALARY
			$bank_cash = $sql_mntsal[0]['CASHPART'];
		} elseif($slt_approval_listings == '777') { // 777 - BANK SALARY CREDIT
			$bank_cash = $sql_mntsal[0]['BANKPART'];
		}
	}

	/* if($_SESSION['tcs_empsrno'] == 43878) {
		echo "select * from budget_planner_yearly 
										where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."";
	} */

	$sql_tarno = select_query_json("select * from budget_planner_yearly 
										where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep."", "Centra", 'TCS');
	if(count($sql_tarno) > 0) { ?>
		<div>
		<table style='clear:both; float:left; width:90%;'>
		<tr><td><table class="monthyr_wrap" style='width:100%;'>
		<? 
		$sql_extr = select_query_json("select sum(nvl(APPRVAL, 0)) aprvlu from approval_budget_planner_temp 
												where BRNCODE=".$brnc." and APRYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep." and deleted = 'N' and USEDVAL = '".$slt_submission."'", "Centra", 'TCS'); //  ATYCODE = USEDVAL
		/* if($_SESSION['tcs_empsrno']==43878){
			echo "select sum(nvl(APPRVAL, 0)) aprvlu from approval_budget_planner_temp 
											where BRNCODE=".$brnc." and APRYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep." and deleted = 'N' and USEDVAL = '".$slt_submission."'";
		} */
		if($sql_extr[0]['APRVLU'] > 0) {
			/* if($_SESSION['tcs_empsrno']==43878){
				echo "select sum(distinct nvl(sm.".$bud_quarter.", 0)) BUDVALUE, (sum(distinct nvl(sm.".$app_quarter.", 0)) + sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE, 
														(sum(distinct nvl(sm.".$bud_quarter.", 0)) - sum(distinct nvl(sm.".$app_quarter.", 0)) - sum(distinct nvl(tm.APPRVAL, 0))) pendingvalue
													from budget_planner_head_sum sm, approval_budget_planner_temp tm 
													where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N' and sm.BRNCODE=".$brnc." and 
														sm.BUDYEAR = '".$nxtyr."' and sm.EXPSRNO = ".$core_dep." and USEDVAL = '".$slt_submission."'";
			} */
			$sql_yrlyttl = select_query_json("select sum(distinct nvl(sm.".$bud_quarter.", 0)) BUDVALUE, (sum(distinct nvl(sm.".$app_quarter.", 0)) + sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE, 
														(sum(distinct nvl(sm.".$bud_quarter.", 0)) - sum(distinct nvl(sm.".$app_quarter.", 0)) - sum(distinct nvl(tm.APPRVAL, 0))) pendingvalue
													from budget_planner_head_sum sm, approval_budget_planner_temp tm 
													where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N' and sm.BRNCODE=".$brnc." and 
														sm.BUDYEAR = '".$nxtyr."' and sm.EXPSRNO = ".$core_dep." and USEDVAL = '".$slt_submission."'", "Centra", 'TCS'); //  ATYCODE = USEDVAL
		} else { 
			/* if($_SESSION['tcs_empsrno']==43878){
				echo "select sum(nvl(".$bud_quarter.", 0)) BUDVALUE, sum(nvl(".$app_quarter.", 0)) APPVALUE, (sum(nvl(".$bud_quarter.", 0)) - sum(nvl(".$app_quarter.", 0))) pendingvalue
													from budget_planner_head_sum where BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep."";
			} */
			$sql_yrlyttl = select_query_json("select sum(nvl(".$bud_quarter.", 0)) BUDVALUE, sum(nvl(".$app_quarter.", 0)) APPVALUE, (sum(nvl(".$bud_quarter.", 0)) - sum(nvl(".$app_quarter.", 0))) pendingvalue
													from budget_planner_head_sum where BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and EXPSRNO = ".$core_dep."", "Centra", 'TCS'); 
		}

		if($slt_submission == 7) {
			$ttl_lock = 10000000000000;
		} else {
			if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) {
				$ttl_lock = 0; 
			} else {
				$ttl_lock = $sql_yrlyttl[0]['PENDINGVALUE']; 
			}
		}
		// $ttl_lock = 1;

		if($slt_submission != 7) { ?>
			<tr>
				<td colspan="3" style='text-align: center; font-weight:bold;'>
					Budget Value : <? if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) { echo "0"; } else { echo moneyFormatIndia($sql_yrlyttl[0]['PENDINGVALUE']); } ?>
				</td>
			</tr>
		<? }

		if($action != 'show_budgetvalue') { 
			if($add_month != '') { ?>
				<tr>
					<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$sql_mntsal[0]['PAYMONT']?>' class='form-control' value='<?=$sql_mntsal[0]['PAYMONT']?>,<?=$fdt[1]?>'><span><?=$add_month_name?></span> : </td>
					<td style='width:5%;'></td>
					<td style='width:40%;'><input type='text' tabindex='18' readonly="readonly" required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$sql_mntsal[0]['PAYMONT']?>' class='form-control ttlsum ttlsumrequired' value="<?=$bank_cash?>" onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$sql_mntsal[0]['PAYMONT']?>, this.value, '<?=$bank_cash?>');" maxlength='10' style='margin: 2px 0px;'></td>
					<td style='width:30%; text-align:center; font-weight:bold;'><input type='hidden' id='ttl_lock_<?=$sql_mntsal[0]['PAYMONT']?>' name='ttl_locks[]' value='<? echo $bank_cash; ?>'></td>
				</tr>
		<? 	}

		if($fdt[1] == $tdt[1]) {
			for($i = $fdt[0]; $i <= $tdt[0]; $i++) { $ivl++;
				if($i < 10 && strlen($i) == 2) {
					$i = ltrim($i, '0');
				}
				$ii = findmonth($i);
				if($ivl == 1) {
					$fstmnth = $i.",".$fdt[1];
				}

				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and TARNUMB = ".$tarno." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$i.") 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */

				$sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") and 
															TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep." 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS');
														
				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.")
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */

				// $ttl_lock += $sql_yrly[0]['BUDVALU'];
				$sql_yr = select_query_json("select * from budget_planner_branch 
													where taryear+1=".substr($fdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and 
														BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS'); 
				$sql_non_check = select_query_json("select * from trandata.non_purchase_target@tcscentr where PTNUMB=".$target_no." and DEPCODE = ".$deptid."", "Centra", 'TCS');
				if(count($sql_yr) == 0 && count($sql_non_check) > 0) { 
					// Insert budget_planner_branch
					$tbl_docs = "budget_planner_branch";
					$field_docs['BRNCODE'] = $slt_branch;
					$field_docs['TARYEAR'] = substr(($fdt[1] - 1), 2);
					$field_docs['TARMONT'] = $i;
					$field_docs['DEPCODE'] = $deptid;
					$field_docs['TARVALU'] = '0';
					$field_docs['TARNUMB'] = $target_no;
					$field_docs['PURTVAL'] = '0';
					$field_docs['RESRVAL'] = '0';
					$field_docs['EXTRVAL'] = '0';
					$field_docs['TOTBVAL'] = '0';
					$field_docs['DEDVAL']  = '0';
					// $insert_docs = insert_query($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Insert budget_planner_branch
				} 
				// echo "**".ltrim($budmn, "0")."**".$i."**";
				if(ltrim($budmn, "0") == $i) { $check_month = 1; } ?>
					<tr>
						<td style='text-align:right; width:25%;'><? if($action == 'choose') { $can_edit = 0; ?><input type="checkbox" checked name="chk_month_find[]" id="chk_month_find_<?=$i?>" class="class_month_find" value="<?=$i?>" onclick="find_chk_fixedbudget('<?=$i?>')"><? } ?> <input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span> <?=$ii?>, <?=$fdt[1]?></span> : </td>
						<td style='width:5%;'></td>
						<td style='width:40%;'><input type='text' tabindex='18' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
						<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<? if($sql_yrlyttl[0]['PENDINGVALUE'] == '' or $sql_yrlyttl[0]['PENDINGVALUE'] <= 0) { echo "0"; } else { echo $sql_yrlyttl[0]['PENDINGVALUE']; } ?>'></td>
					</tr>
				<?
			}
		} else { 
			for($i = $fdt[0]; $i <= 12; $i++) { $ivl++;
				if($i < 10 && strlen($i) == 2) {
					$i = ltrim($i, '0');
				}
				$ii = findmonth($i);
				if($ivl == 1) {
					$fstmnth = $i+","+$fdt[1];
				}
				
				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and TARNUMB = ".$tarno." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$i.") 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */
				
				$sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and 
															BUDMONTH in (".$i.") and TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep." 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS');
														
				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */
				// $ttl_lock += $sql_yrly[0]['BUDVALU'];
				$sql_yr = select_query_json("select * from budget_planner_branch 
													where taryear+1=".substr($fdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and 
														BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS'); 
				if(count($sql_yr) == 0) { 
					// Insert budget_planner_branch
					$tbl_docs = "budget_planner_branch";
					$field_docs['BRNCODE'] = $slt_branch;
					$field_docs['TARYEAR'] = substr(($fdt[1] - 1), 2);
					$field_docs['TARMONT'] = $i;
					$field_docs['DEPCODE'] = $deptid;
					$field_docs['TARVALU'] = '0';
					$field_docs['TARNUMB'] = $target_no;
					$field_docs['PURTVAL'] = '0';
					$field_docs['RESRVAL'] = '0';
					$field_docs['EXTRVAL'] = '0';
					$field_docs['TOTBVAL'] = '0';
					$field_docs['DEDVAL']  = '0';
					// $insert_docs = insert_query($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Insert budget_planner_branch
				} 
				// echo "**".ltrim($budmn, "0")."**".$i."**";
				if(ltrim($budmn, "0") == $i) { $check_month = 1; } ?>
					<tr>
						<td style='text-align:right; width:25%;'><? if($action == 'choose') { $can_edit = 0; ?><input type="checkbox" checked name="chk_month_find[]" id="chk_month_find_<?=$i?>" class="class_month_find" value="<?=$i?>" onclick="find_chk_fixedbudget('<?=$i?>')"><? } ?> <input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span> <?=$ii?>, <?=$fdt[1]?></span> : </td>
						<td style='width:5%;'></td>
						<td style='width:40%;'><input type='text' tabindex='18' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
						<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<?=$sql_yrly[0]['BUDVALU']?>'></td>
					</tr>
				<?
			}
			$lstmnth = ($i-1)+","+$fdt[1];
			
			for($i = 1; $i <= $tdt[0]; $i++) { $ivl++;
				if($i < 10 && strlen($i) == 2) {
					$i = ltrim($i, '0');
				}
				$ii = findmonth($i);
				if($ivl == 1) {
					$fstmnth = $i+","+$tdt[1];
				}
			
				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and TARNUMB = ".$tarno." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$i.") 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */

				$sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") and 
															TARNUMB = ".$tarno." and EXPSRNO = ".$core_dep." 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); 

				/* $sql_yrly = select_query_json("select * from budget_planner_yearly 
														where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$nxtyr."' and BUDMONTH in (".$i.") 
														order by BUDYEAR, BUDMONTH, TARNUMB, BRNCODE, DEPCODE", "Centra", 'TCS'); */
				// $ttl_lock += $sql_yrly[0]['BUDVALU'];
				$sql_yr = select_query_json("select * from budget_planner_branch where taryear+1=".substr($tdt[1], 2)." and tarmont=".$i." and tarnumb=".$target_no." and BRNCODE = ".$slt_branch." and DEPCODE = ".$deptid."", "Centra", 'TCS'); 
				if(count($sql_yr) == 0) { 
					// Insert budget_planner_branch
					$tbl_docs = "budget_planner_branch";
					$field_docs['BRNCODE'] = $slt_branch;
					$field_docs['TARYEAR'] = substr(($tdt[1] - 1), 2);
					$field_docs['TARMONT'] = $i;
					$field_docs['DEPCODE'] = $deptid;
					$field_docs['TARVALU'] = '0';
					$field_docs['TARNUMB'] = $target_no;
					$field_docs['PURTVAL'] = '0';
					$field_docs['RESRVAL'] = '0';
					$field_docs['EXTRVAL'] = '0';
					$field_docs['TOTBVAL'] = '0';
					$field_docs['DEDVAL']  = '0';
					// $insert_docs = insert_query($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Insert budget_planner_branch
				} 
				// echo "**".ltrim($budmn, "0")."**".$i."**";
				if(ltrim($budmn, "0") == $i) { $check_month = 1; } ?>
					<tr>
						<td style='text-align:right; width:25%;'><? if($action == 'choose') { $can_edit = 0; ?><input type="checkbox" checked name="chk_month_find[]" id="chk_month_find_<?=$i?>" class="class_month_find" value="<?=$i?>" onclick="find_chk_fixedbudget('<?=$i?>')"><? } ?> <input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?>' class='form-control' value='<?=$i?>,<?=$tdt[1]?>'><span> <?=$ii?>, <?=$tdt[1]?></span> : </td>
						<td style='width:5%;'></td>
						<td style='width:40%;'><input type='text' tabindex='18' <? if($can_edit == 0) { ?> readonly="readonly" <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?>' class='form-control ttlsum ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='enable_month(); return isNumber(event)' onKeyup='calculate_sum()' onblur="calculate_sum(); allow_zero(<?=$i?>, this.value, '<?=$sql_yrly[0]['BUDVALU']?>');" maxlength='10' style='margin: 2px 0px;'></td>
						<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<?=$sql_yrly[0]['BUDVALU']?>'></td>
					</tr>
				<?
			}
			$lstmnth = ($i-1)+","+$tdt[1];
		}

		/* $sql_mnthperc = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) mnthpercent
												from budget_planner_yearly where DEPCODE=".$depc." and BRNCODE=".$brnc." and TARNUMB = ".$tarno." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$budmn.")", "Centra", 'TCS');
		$sql_yearperc = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) yearpercent
												from budget_planner_yearly where DEPCODE=".$depc." and BRNCODE=".$brnc." and TARNUMB = ".$tarno." and BUDYEAR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TCS');

		$sql_mnthperc_dept = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) mnthpercent
												from budget_planner_yearly where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$budmn.")"), "Centra", 'TCS';
		$sql_yearperc_dept = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) yearpercent
												from budget_planner_yearly where DEPCODE=".$depc." and BRNCODE=".$brnc." and BUDYEAR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TCS');

		$sql_mnthperc_branch = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) mnthpercent
												from budget_planner_yearly where BRNCODE=".$brnc." and BUDYEAR = '".$current_year[0]['PORYEAR']."' and BUDMONTH in (".$budmn.")", "Centra", 'TCS');
		$sql_yearperc_branch = select_query_json("select sum(BUDVALU) BUDVALU, sum(SALVAL_APX) SALVAL_APX, sum(SALVAL_ACT) SALVAL_ACT, sum(REQVALU) REQVALU, sum(APPVALU) APPVALU, sum(PORVALU) PORVALU, sum(PAYVALU) PAYVALU, 
													sum(TARVALU) TARVALU, sum(RESVALU) RESVALU, sum(EXTVALU) EXTVALU, round((sum(REQVALU) / sum(nvl(SALVAL_APX, 1))) * 100, 2) yearpercent
												from budget_planner_yearly where BRNCODE=".$brnc." and BUDYEAR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TCS'); */
		?>
		<tr><td colspan='2' style='width:40%; text-align:right; padding-right:10%; font-weight:bold;'>TOTAL : </td><td style='width:60%; font-weight:bold;'><span id='ttl_mntyr'><? /* if($slt_submission == 6) { echo $ttl_lock; } else { */ ?>0<? /* } */ ?></span></td></tr>
		</table></td></tr>
		</table>
		</div>

		<input type='hidden' id='frmdate' name='frmdate' value='<?=$minvl?>'>
		<input type='hidden' id='todate' name='todate' value='<?=$maxvl?>'>
		<input type='hidden' id='minvl' name='minvl' value='<?=$minvl?>'>
		<input type='hidden' id='maxvl' name='maxvl' value='<?=$maxvl?>'>
		<input type='hidden' id='fstmnth' name='fstmnth' value='<?=$fstmnth?>'>
		<input type='hidden' id='lstmnth' name='lstmnth' value='<?=$lstmnth?>'>
		<input type='hidden' id='hidapryear' name='hidapryear' value='<?=$nxtyr?>'>
		<input type='hidden' id='ttl_lock' name='ttl_lock' value='<?=$ttl_lock?>'>
		<input type='hidden' id='slry_status' name='slry_status' value='<?=$sql_mntsal[0]['STATUS']?>'>

		<? /* <div>
			<table style='clear:both; float:left; width:100%;'>
				<tr><td>
					<table class="monthyr_wrap" style='width:100%; text-align:center; text-transform: uppercase;'>
						<tr style='background-color:#737373; min-height:30px; color:#FFFFFF; text-transform: uppercase; font-weight:bold; line-height:30px;'>
							<td>Budget</td>
							<td>Month %</td>
							<td>Year %</td>
						</tr>
						<tr style='min-height:30px; line-height:30px;'>
							<td class='brdclr'><b>Target No %</b></td>
							<td class='brdclr'><?=round($sql_mnthperc[0]['MNTHPERCENT'], 2)?></td>
							<td class='brdclr'><?=round($sql_yearperc[0]['YEARPERCENT'], 2)?></td>
						</tr>
						<tr style='min-height:30px; line-height:30px;'>
							<td class='brdclr'><b>Department %</b></td>
							<td class='brdclr'><?=round($sql_mnthperc_dept[0]['MNTHPERCENT'], 2)?></td>
							<td class='brdclr'><?=round($sql_yearperc_dept[0]['YEARPERCENT'], 2)?></td>
						</tr>
						<tr style='min-height:30px; line-height:30px;'>
							<td class='brdclr'><b>Branch %</b></td>
							<td class='brdclr'><?=round($sql_mnthperc_branch[0]['MNTHPERCENT'], 2)?></td>
							<td class='brdclr'><?=round($sql_yearperc_branch[0]['YEARPERCENT'], 2)?></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</div> */ 
		} else { ?>
			<input type='hidden' id='ttl_pndlock' name='ttl_pndlock' value='<?=$ttl_lock?>'>
		<? }
	} else {
		/////////// 04-11-2017 - Comment due to inappropriate use
		/* $sql_exist = select_query_json("select * from non_purchase_target where ptnumb = ".$tarno." and DEPCODE = ".$depc);
		if(count($sql_exist) > 0) {
			// If budget_planner_yearly values are empty, must insert here
			// Insert budget_planner_yearly
			$tbl_docs = "budget_planner_yearly";
			$field_docs['BUDYEAR'] 		= $nxtyr;
			$field_docs['BUDMONTH']		= date('m');
			$field_docs['BRNCODE'] 		= $brnc;
			$field_docs['DEPCODE'] 		= $depc;
			$field_docs['BUDVALU'] 		= '0';
			$field_docs['SALVAL_APX'] 	= '0';
			$field_docs['SALVAL_ACT'] 	= '0';
			$field_docs['REQVALU'] 		= '0';
			$field_docs['APPVALU'] 		= '0';
			$field_docs['PORVALU'] 		= '0';
			$field_docs['PAYVALU'] 		= '0';
			$field_docs['TARNUMB'] 		= $tarno;
			$field_docs['TARVALU'] 		= '0';
			$field_docs['RESVALU'] 		= '0';
			$field_docs['EXTVALU'] 		= '0';
			$field_docs['BUDVALU_APPX'] = '0';
			$field_docs['EXPSRNO'] 		= $core_dep;
			$field_docs['EXP_BUDGET'] 	= '0';

			$insert_docs = insert_query($field_docs, $tbl_docs);
			// print_r($field_docs);
			// Insert budget_planner_yearly
		} */ 
		/////////// 04-11-2017 - Comment due to inappropriate use
	} ?>
	<input type="hidden" name="npobudget" id="npobudget" value="1">
<? } else { ?>
<? } ?>
<input type="hidden" name="txt_fixedbudget" id="txt_fixedbudget" value="0">