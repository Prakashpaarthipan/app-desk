<?php 
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_GET['type'] == 'employee'){
    $result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, empsection sec, designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000 or emp.empcode = 557) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' ) 
									union
										select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000 or emp.empcode = 557) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' ) and rownum<=10 
										order by EMPCODE Asc", "Centra", 'TCS');
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']." - ".substr($result[$rowi]['ESENAME'], 3));
    }    
    echo json_encode($data);
} elseif($_GET['type'] == 'branch_employee'){
    $result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, empsection sec, designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' ) and emp.brncode = '".$branch."' 
									union
										select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' ) and emp.brncode = '".$branch."' 
										order by EMPCODE Asc", "Centra", 'TCS');
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']." - ".substr($result[$rowi]['ESENAME'], 3));
    }    
    echo json_encode($data);
} /* elseif($_GET['action'] == 'allemp'){
    $result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, empsection sec, designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' ) 
									union
										select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' )  
										order by EMPCODE Asc", "Centra", 'TCS');
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']." - ".substr($result[$rowi]['ESENAME'], 3));
    }    
    echo json_encode($data);
} */ elseif($_GET['type'] == 'ex_employee'){
	$ex = explode(" - ", $empcode);
    $result = select_query_json("select * from APPROVAL_ALTERNATE_USER alt where deleted = 'N' and empcode = '".$ex[0]."' order by aptsrno", "Centra", 'TEST');
    if( count($result) > 0) {
    	echo "1";
    } else {
    	echo "0";
    }
} elseif($_GET['type'] == 'ex_dly_employee'){
	$ex = explode(" - ", $empcode);
    $result = select_query_json("select * from APPROVAL_ALTERNATE_daily alt where deleted = 'N' and empcode = '".$ex[0]."' order by APDSRNO", "Centra", 'TEST');
    if( count($result) > 0) {
    	echo "1";
    } else {
    	echo "0";
    }
} elseif($_GET['action'] == 'save_priority'){

	$tbl_appplan = "approval_request";
	$field_appplan = array();
	$field_appplan['PRICODE'] = $priority_code;
	// print_r($field_appplan);
	$where_appplan = " APRNUMB='".$aprnumb."' and arqsrno = '".$arqsrno."' ";
	$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);

	$sql_reqid = select_query_json("select req.*, (select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and 
												ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE) as ADDEDDATE
											from APPROVAL_REQUEST req 
											where APRNUMB='".$aprnumb."' and arqsrno = '".$arqsrno."'", 'Centra', 'TEST');

	$start_time = formatSeconds(strtotime('now') - strtotime($sql_reqid[0]['ADDEDDATE']));
	$sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request 
                                        where aprnumb like '".$aprnumb."' and appfrwd = 'I' 
                                        order by arqsrno", "Centra", "TEST");
	$duedate = 0;
    switch ($sql_reqid[0]['PRICODE']) {
        case 1:
            $duedate = 1;
            $css_cls = "#FF0000";
            if($start_time <= 1) {
                $css_clstime = "#299654";
            } else {
                $css_clstime = "#FF0000";
            }
            if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
            break;
        case 2:
            $duedate = 2;
            $css_cls = "#D58B0A";
            if($start_time <= 2) {
                $css_clstime = "#299654";
            } else {
                $css_clstime = "#FF0000";
            }
            if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
            break;
        case 3:
            $duedate = 3;
            $css_cls = "#299654";
            if($start_time <= 3) {
                $css_clstime = "#299654";
            } else {
                $css_clstime = "#FF0000";
            }
            if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
            break;

        default:
            $duedate = 1;
            $css_cls = "#FF0000";
            if($start_time <= 1) {
                $css_clstime = "#299654";
            } else {
                $css_clstime = "#FF0000";
            }
            if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
            break;
    } ?>
	<span class="label label-info label-form" style="background-color:<?=$css_clstime?>; padding: 5px; color: #FFFFFF; font-weight: bold;">Due Date : <?=$duedate?> Days & Process Date : <?=$start_time?> Days</span>

	Process Priority : <? $allow_priority = 0;
	if($_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_descode'] == 9) {
		// DGM / GM / Sr.GM / MD's can change the Process Priority
		$allow_priority = 1;
	}

	if($_SESSION['tcs_descode'] == 132) {
		// HDO can change the Process Priority
		$allow_priority = 2;
	}

	switch ($sql_reqid[0]['PRICODE']) {
		case 1:
			$clrcod = 'badge-danger';
			$clrcod1 = '#FF0000';
			break;
		case 2:
			$clrcod = 'badge-warning';
			$clrcod1 = '#D58B0A';
			break;

		default:
			$clrcod = 'badge-success';
			$clrcod1 = '#299654';
			break;
	}

	if($allow_priority == 1) { // DGM / GM / Sr.GM / MD's can move to any Priority ?>
		<input type="radio" name="slt_priority" id="slt_priority_1" value="1" onclick="save_priority(1, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$arqsrno?>')" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 1) { ?> checked <? } ?>>&nbsp;<span class="badge badge-danger" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" style="font-size:20px; background-color:#FF0000; font-weight:bold;">1</span>&nbsp;
		<input type="radio" name="slt_priority" id="slt_priority_2" value="2" onclick="save_priority(2, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$arqsrno?>')" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 2) { ?> checked <? } ?>>&nbsp;<span class="badge badge-warning" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" style="font-size:20px; background-color:#D58B0A; font-weight:bold;">2</span>&nbsp;
		<input type="radio" name="slt_priority" id="slt_priority_3" value="3" onclick="save_priority(3, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$arqsrno?>')" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 3) { ?> checked <? } elseif($sql_reqid[0]['PRICODE'] == '') { ?> checked <? } ?>>&nbsp;<span class="badge badge-success" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:20px; background-color:#299654; font-weight:bold;">3</span>&nbsp;
	<? } 
	elseif($allow_priority == 2) { // HOD can move 3rd to 2nd Priority ?>
		<input type="radio" disabled name="slt_priority" id="slt_priority_1" value="1" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 1) { ?> checked <? } ?>>&nbsp;<span class="badge badge-danger" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" style="font-size:20px; background-color:#FF0000; font-weight:bold;">1</span>&nbsp;
		<input type="radio" name="slt_priority" id="slt_priority_2" value="2" onclick="save_priority(2, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$arqsrno?>')" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 2) { ?> checked <? } ?>>&nbsp;<span class="badge badge-warning" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" style="font-size:20px; background-color:#D58B0A; font-weight:bold;">2</span>&nbsp;
		<input type="radio" name="slt_priority" id="slt_priority_3" value="3" onclick="save_priority(3, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$arqsrno?>')" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" <? if($sql_reqid[0]['PRICODE'] == 3) { ?> checked <? } elseif($sql_reqid[0]['PRICODE'] == '') { ?> checked <? } ?>>&nbsp;<span class="badge badge-success" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:20px; background-color:#299654; font-weight:bold;">3</span>&nbsp;
	<? } else { ?>
		<span class="badge badge-success" style="font-size:20px; background-color:<?=$clrcod1?>; font-weight:bold;"><? if($sql_reqid[0]['PRICODE'] != '') { echo $sql_reqid[0]['PRICODE']; } else { echo "3"; } ?></span>
	<? } 

} elseif($_GET['type'] == 'core_employee') {
	/* if($_SESSION['tcs_empsrno'] == 8422 or $_SESSION['tcs_empsrno'] == 53864) { // Travel Desk Balaji (8422), HR Madhan (53864) */

		if($_SESSION['tcs_empsrno'] == 14180 or $_SESSION['tcs_empsrno'] == 2158) { // Project Manohar, Guna
			$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY, 
												decode(empcode, '".strtoupper($_GET['name_startsWith'])."', 0, empcode) emp_order 
											from employee_office emp, empsection sec, designation des, employee_salary sal, approval_topcore top 
											where emp.ESECODE = emp.ESECODE and emp.ESECODE = sec.ESECODE and top.DELETED = 'N' and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) 
												and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.descode = 92 or 
												emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or emp.EMPNAME like '".strtoupper($_GET['name_startsWith'])."%' ) 
										union
											select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY, 
												decode(empcode, '".strtoupper($_GET['name_startsWith'])."', 0, empcode) emp_order 
											from employee_office emp, new_empsection sec, new_designation des, employee_salary sal, approval_topcore top 
											where emp.ESECODE = sec.ESECODE and top.DELETED = 'N' and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and 
												des.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '".strtoupper($_GET['name_startsWith'])."%' 
												or emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' or emp.descode = 67 )  
											order by emp_order, EMPCODE Asc", "Centra", 'TCS');
		} else {
			$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY, 
													decode(empcode, '".strtoupper($_GET['name_startsWith'])."', 0, empcode) emp_order 
												from employee_office emp, empsection sec, designation des, employee_salary sal, approval_topcore top 
												where emp.ESECODE = emp.ESECODE and emp.ESECODE = sec.ESECODE and top.DELETED = 'N' and top.ATCCODE in (".$topcr.") and 
													emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and sal.PAYCOMPANY = 1 and 
													emp.empsrno = sal.empsrno and ( emp.EMPCODE like '".strtoupper($_GET['name_startsWith'])."%' 
													or emp.EMPNAME like '%".strtoupper($_GET['name_startsWith'])."%' or emp.descode = 92 ) 
											union
												select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY, 
													decode(empcode, '".strtoupper($_GET['name_startsWith'])."', 0, empcode) emp_order 
												from employee_office emp, new_empsection sec, new_designation des, employee_salary sal, approval_topcore top 
												where emp.ESECODE = sec.ESECODE and top.DELETED = 'N' and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' 
													and des.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and top.ATCCODE in (".$topcr.") and ( emp.descode = 67 or 
													emp.EMPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or emp.EMPNAME like '".strtoupper($_GET['name_startsWith'])."%' )  
												order by emp_order, EMPCODE Asc", "Centra", 'TCS');
		}
	/* } else {
		$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
											from employee_office emp, empsection sec, designation des, employee_salary sal, empcore_section sub, approval_topcore top 
											where sub.ESECODE = emp.ESECODE and emp.ESECODE = sec.ESECODE and top.ATCCODE = sub.TOPCORE and top.DELETED = 'N' and sub.DELETED = 'N' and 
												sub.TOPCORE in (".$topcr.") and sub.CORCODE in (".$subcr.") and sub.esecode > 0 and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' 
												and des.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '".strtoupper($_GET['name_startsWith'])."%' 
												or emp.EMPNAME like '".strtoupper($_GET['name_startsWith'])."%' ) 
										union
											select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
											from employee_office emp, new_empsection sec, new_designation des, employee_salary sal, empcore_section sub, approval_topcore top 
											where sub.ESECODE = emp.ESECODE and emp.ESECODE = sec.ESECODE and top.ATCCODE = sub.TOPCORE and top.DELETED = 'N' and sub.DELETED = 'N' and 
												sub.TOPCORE in (".$topcr.") and sub.CORCODE in (".$subcr.") and sub.esecode > 0 and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' 
												and des.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '".strtoupper($_GET['name_startsWith'])."%' 
												or emp.EMPNAME like '".strtoupper($_GET['name_startsWith'])."%' )  
											order by EMPCODE Asc", "Centra", 'TCS');
	} */
    $data = array();
    if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']." - ".substr($result[$rowi]['ESENAME'], 3));
	    }   
	} else {
		array_push($data, 'No User Available in this Top core and Sub Core');
	}
    echo json_encode($data);
} elseif($_GET['type'] == 'approval_no') {
	$result = select_query_json("select distinct APRNUMB, APPFVAL from trandata.APPROVAL_REQUEST@tcscentr 
									where arqsrno = 1 and appfval > 0 and appstat not in ('A', 'R') and adddate >= add_months(trunc(sysdate, 'MM'), -3) 
										and aprnumb like '%".strtoupper($_GET['name_startsWith'])."%' and ATCCODE = '".$_GET['topcore']."' 
									order by APRNUMB", "Centra", 'TCS');
    $data = array();
    if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result); $rowi++) {
			array_push($data, $result[$rowi]['APRNUMB']." ( Rs. ".$result[$rowi]['APPFVAL']." )");
	    }   
	} else {
		array_push($data, 'Approval No is not available');
	}
    echo json_encode($data);
} elseif($action == "allbrnemp"){
	$mnth  = strtoupper(date("M"));
	$yr    = strtoupper(date("Y"));
	$table = "attn_time_det_".$mnth."_".$yr;

	$slt_emp = strtoupper($slt_emp);
	$result = select_query_json("select emp.empcode, emp.empname, ese.esename from employee_office emp, empsection ese 
										where emp.esecode=ese.esecode and ( emp.empcode like '%".$slt_emp."%' or emp.empname like '%".$slt_emp."%' )", "Centra", 'TCS');
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']);
    }    
    echo json_encode($data);	
} else {
	$mnth  = strtoupper(date("M"));
	$yr    = strtoupper(date("Y"));
	$table = "attn_time_det_".$mnth."_".$yr;

	if($brncode == 100){
		$brncode=888;
	}

	$slt_emp = strtoupper($slt_emp);
	$result = select_query_json("select emp.empcode, emp.empname, ese.esename from employee_office emp, empsection ese 
										where emp.esecode=ese.esecode and emp.brncode=".$brncode." and ( emp.empcode like '%".$slt_emp."%' or emp.empname like '%".$slt_emp."%' )
									union
										select distinct  emp.empcode, emp.empname, ese.esename from employee_office emp, empsection ese, ".$table." attn 
										where emp.empsrno=attn.empsrno and emp.esecode=ese.esecode and trunc(attn.sftdate)=trunc(sysdate) and attn.brncode=".$brncode." and 
											( emp.empcode like '%".$slt_emp."%' or emp.empname like '%".$slt_emp."%')", "Centra", 'TCS');
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['EMPCODE']." - ".$result[$rowi]['EMPNAME']);
    }    
    echo json_encode($data);
    
}
?>