<?php
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
$cm = $_REQUEST['approval_listings_id'];
$kind_attn  = $_REQUEST['kind_attn'];
$budgettype = $_REQUEST['budgettype'];

/*
$sql_appmgt = select_query_json("select * from APPROVAL_MANAGEMENT where deleted = 'N' and empname like '%".$kind_attn."%' order by AMGCODE Asc", "Centra", 'TCS');
$sql_attn = select_query_json("select distinct(DESSRNO) 
									from employee_office emp, designation des, employee_salary sal 
									where emp.descode = des.descode and emp.empsrno = sal.empsrno and emp.empcode in ('".$sql_appmgt[0]['EMPCODE']."') 
										and des.deleted = 'N' and sal.PAYCOMPANY = 1
								union
									select distinct(DESSRNO) 
									from employee_office emp, new_designation des, employee_salary sal 
									where emp.descode = des.descode and emp.empsrno = sal.empsrno and emp.empcode in ('".$sql_appmgt[0]['EMPCODE']."') 
										and des.deleted = 'N' and sal.PAYCOMPANY = 2", "Centra", 'TCS');
*/ 

$ceo_available = 0;
if($slt_project == 13 or $slt_project == 14 or $slt_project == 15 or $slt_project == 16 or $slt_project == 19 or $slt_project == 26 or $slt_project == 27 or $slt_project == 28 or $slt_project == 29 or $slt_project == 30) { // PROJECT ID (5 Airport (CBE, HYD, MUM, MDU, CHN), Tailyou, Online, ZF, Kanmani, Clean 
	$ceo_available = 1;
}

$aksir_targetno = array("7565", "7566", "7567", "7568", "7569", "7570", "7571", "7670", "7585", "7586", "7587", "7588", "7633", "7635", "7671", "7680"); // AK Sir level approvals Target No Based
if(in_array($tarnum, $aksir_targetno)){
	$ceo_available = 1;
}

$topcore_head_not_available = array("1652"); // AK Sir instruction, Top Core head is not available

$sql_finaluser = select_query_json("select * from approval_listing_head where apmcode = '".$cm."'", "Centra", 'TCS');
function allmds() {
	find_finaluser(21344); // AK Sir
	find_finaluser(43400); // PS Madam
	find_finaluser(20118); // KS Sir
}

function allgms() {
	// 1062 - NSM sir
	if(!in_array(1062, $_SESSION['chkexistuser'])){
		$_SESSION['sess_1'] .= 'SIVALINGAM.N. - 1062 [ 1 Day(s) ] <br>';
		$_SESSION['sess_2'] += 1;
		$_SESSION['sess_3'] .= "1062~~";
		$_SESSION['chkexistuser'][] = 1062;
	}
	// 1062 - NSM sir

	// 2001 - LGanesh sir
	if(!in_array(2001, $_SESSION['chkexistuser'])){
		$_SESSION['sess_1'] .= 'GANESH L - 2001 [ 1 Day(s) ] <br>';
		$_SESSION['sess_2'] += 1;
		$_SESSION['sess_3'] .= "2001~~";
		$_SESSION['chkexistuser'][] = 2001;
	}
	// 2001 - LGanesh sir

	// 1986 - KKumaran sir
	if(!in_array(1986, $_SESSION['chkexistuser'])){
		$_SESSION['sess_1'] .= 'KUMARAN K - 1986 [ 1 Day(s) ] <br>';
		$_SESSION['sess_2'] += 1; 
		$_SESSION['sess_3'] .= "1986~~"; 
		$_SESSION['chkexistuser'][] = 1986;
	}
	// 1986 - KKumaran sir

	// 2444 - RDTM sir
	if(!in_array(2444, $_SESSION['chkexistuser'])){
		$_SESSION['sess_1'] .= 'THERUMURTHE R.D - 2444 [ 1 Day(s) ] <br>';
		$_SESSION['sess_2'] += 1;
		$_SESSION['sess_3'] .= "2444~~";
		$_SESSION['chkexistuser'][] = 2444;
	}
	// 2444 - RDTM sir
}

function akks() {
	find_finaluser(21344); // AK Sir
	find_finaluser(20118); // KS Sir
}

function find_finaluser($finaluser = 20118) {
	switch ($finaluser) {
		case 20118:
			// Sr. KS Sir Level
			if(!in_array(1, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'SIVALINGAM K <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "1~~";
				$_SESSION['chkexistuser'][] = 1;
			}
			// Sr. KS Sir Level
			break;

		case 43400:
			// Mrs. PS Madam Level
			if(!in_array(2, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'PADHMA SIVLINGAM S <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "2~~";
				$_SESSION['chkexistuser'][] = 2;
			}
			// Mrs. PS Madam Level
			break;

		case 21344:
			// Mr. AK Sir Level
			if(!in_array(3, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'S KAARTHI <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "3~~";
				$_SESSION['chkexistuser'][] = 3;
			}
			// Mr. AK Sir Level  // 05082018-SKSIR

			/* // Mrs. PS Madam Level
			if(!in_array(2, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'PADHMA SIVLINGAM S <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "2~~";
				$_SESSION['chkexistuser'][] = 2;
			}
			// Mrs. PS Madam Level */ // 11082018-SKSIR
			break;

		case 65945:
			// Mrs. ANUMALERVILI S Madam Level
			if(!in_array(4, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'ANUMALERVILI S <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "4~~";
				$_SESSION['chkexistuser'][] = 4;
			}
			// Mrs. ANUMALERVILI S Madam Level
			break;

		case 66114:
			// Mrs. SIVASANKARI BABU Madam Level
			if(!in_array(5, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'SIVASANKARI BABU <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "5~~";
				$_SESSION['chkexistuser'][] = 5;
			}
			// Mrs. SIVASANKARI BABU Madam Level
			break;
		
		default:
			// Sr. KS Sir Level
			if(!in_array(1, $_SESSION['chkexistuser'])){
				$_SESSION['sess_1'] .= 'SIVALINGAM K <br>';
				$_SESSION['sess_2'] += 1; 
				$_SESSION['sess_3'] .= "1~~";
				$_SESSION['chkexistuser'][] = 1;
			}
			// Sr. KS Sir Level
			break;
	}
}

$addmd = 0; $admd = 0; $newentry = 0; $mdak = 0; $mdks = 0; $mdps = 0; $ismdavail = 0; $isakavail = 0; $ntallow = 0; $ignore_user = array();
$_SESSION['sess_1'] = '';
$_SESSION['sess_2'] = '';
$_SESSION['sess_3'] = '';
$_SESSION['chkexistuser'] = array();
$hwuser = array("704", "591"); // APPROVAL LISTING ID / CODE

function find_dept_incharge($tcsempsrno, $topcore, $slt_core_department, $deptid, $brncode, $tarnumb) { // Find the Department Incharge
	$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
												where brncode = ".$brncode." and depcode = ".$deptid." and tarnumb = ".$tarnumb." and deleted = 'N' 
												ORDER BY BRNHDCD", "Centra", 'TCS'); // atccode = 4 and 
	array_push($ignore_user, $sql_dptinchr1[0]['APPHEAD']);
	if($sql_dptinchr1[0]['APPHEAD'] != '') {
		if(!in_array($sql_dptinchr1[0]['APPHEAD'], $_SESSION['chkexistuser'])){
			$_SESSION['sess_1'] .= $sql_dptinchr1[0]['EMPNAME']." - ".$sql_dptinchr1[0]['APPHEAD']." [ 1 Day(s) ] <br>";
			$_SESSION['sess_2'] += 1;
			$_SESSION['sess_3'] .= $sql_dptinchr1[0]['APPHEAD']."~~";
			$_SESSION['chkexistuser'][] = $sql_dptinchr1[0]['APPHEAD'];
		}
	}

	$sql_dptinchr = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_dept_incharge 
											where expsrno = ".$slt_core_department." and depcode = ".$deptid." and deleted = 'N'", "Centra", 'TCS'); // atccode = 4 and 
	array_push($ignore_user, $sql_dptinchr[0]['APPHEAD']);
	if($sql_dptinchr[0]['APPHEAD'] != '') {
		if(!in_array($sql_dptinchr[0]['APPHEAD'], $_SESSION['chkexistuser'])){
			$_SESSION['sess_1'] .= $sql_dptinchr[0]['EMPNAME']." - ".$sql_dptinchr[0]['APPHEAD']." [ 1 Day(s) ] <br>";
			$_SESSION['sess_2'] += 1;
			$_SESSION['sess_3'] .= $sql_dptinchr[0]['APPHEAD']."~~";
			$_SESSION['chkexistuser'][] = $sql_dptinchr[0]['APPHEAD'];
		}
	}
}

function find_hod_bydept($sub_topcore, $slt_core_department, $approval_listings_id) { 
	$sql_dptinchr = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
													where deleted = 'N' and apmcode = '".$approval_listings_id."' 
													ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 
	foreach ($sql_dptinchr as $key => $dptinchr_value) {
		$apphd_notin .= $dptinchr_value['APPHEAD'].",";
	}
	$apphd_notin = rtrim($apphd_notin, ",");

	$sql_operation = select_query_json("select distinct sec.grpsrno, sgrp.SECNAME from attn_section_group sgrp, section sec 
												where sgrp.seccode=sec.seccode and sgrp.ESECODE in (".$_SESSION['tcs_esecode'].")", "Centra", 'TCS');
	if(count($sql_operation) > 0) {
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
											where ESECODE in (".$_SESSION['tcs_esecode'].") and EMPCODE not in (".$_SESSION['tcs_user'].", ".$apphd_notin.") and DESCODE in (132, 189) and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		if(!in_array($sql_hod[0]['APPHEAD'], $_SESSION['chkexistuser'])) {
			if($sql_hod[0]['EMPNAME'] !='') {
				$_SESSION['sess_1'] .= $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
				$_SESSION['sess_2'] += 1;
				$_SESSION['sess_3'] .= $sql_hod[0]['APPHEAD']."~~";
				$_SESSION['chkexistuser'][] = $sql_hod[0]['APPHEAD'];
			}
		}

		if($_SESSION['tcs_company_code'] == 1) {
			$sql_tp1 = select_query_json("select DELUSER from empcore_section 
												where DELETED = 'N' and TOPCORE in (3) and ESECODE in (".$_SESSION['tcs_esecode'].") order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
			if($sql_tp1[0]['DELUSER'] == '1') { // SR.GM
				if(!in_array(2444, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2444' and EMPCODE not in (".$apphd_notin.") 
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR 
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 2444) { 
							$_SESSION['sess_1'] .= 'THERUMURTHE R.D - 2444 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "2444~~";
							$_SESSION['chkexistuser'][] = 2444;
						}
					}
				}
			} else { // GM
				if(!in_array(2001, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2001' and EMPCODE not in (".$apphd_notin.")  
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 2001) { 
							$_SESSION['sess_1'] .= 'GANESH L - 2001 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "2001~~";
							$_SESSION['chkexistuser'][] = 2001;
						}
					}
				}
			}
		}		
	}

	$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
										where ESECODE in (".$_SESSION['tcs_esecode'].") and DESCODE in (132, 189, 19, 169) and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
	if(!in_array($sql_hod[0]['APPHEAD'], $_SESSION['chkexistuser'])){
		if($sql_hod[0]['EMPNAME'] !='') {
			$_SESSION['sess_1'] .= $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
			$_SESSION['sess_2'] += 1;
			$_SESSION['sess_3'] .= $sql_hod[0]['APPHEAD']."~~";
			$_SESSION['chkexistuser'][] = $sql_hod[0]['APPHEAD'];
		}
	}
}

function find_hod_by_selected_dept($sub_topcore, $slt_core_department, $approval_listings_id) { 
	$sql_dptinchr = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
													where deleted = 'N' and apmcode = '".$approval_listings_id."' 
													ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 
		
	foreach ($sql_dptinchr as $key => $dptinchr_value) {
		$apphd_notin .= $dptinchr_value['APPHEAD'].",";
	}
	$apphd_notin = rtrim($apphd_notin, ",");

	$sql_operation = select_query_json("select distinct sec.grpsrno, sgrp.SECNAME from attn_section_group sgrp, section sec 
												where sgrp.seccode=sec.seccode and sgrp.ESECODE in (".$sub_topcore.")", "Centra", 'TCS');
	if(count($sql_operation) > 0) {
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
											where ESECODE in (".$sub_topcore.") and EMPCODE not in (".$_SESSION['tcs_user'].", ".$apphd_notin.") and DESCODE in (132, 189) and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		if(!in_array($sql_hod[0]['APPHEAD'], $_SESSION['chkexistuser'])) {
			if($sql_hod[0]['EMPNAME'] !='') {
				$_SESSION['sess_1'] .= $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
				$_SESSION['sess_2'] += 1;
				$_SESSION['sess_3'] .= $sql_hod[0]['APPHEAD']."~~";
				$_SESSION['chkexistuser'][] = $sql_hod[0]['APPHEAD'];
			}
		}

		if($_SESSION['tcs_company_code'] == 1) {
			$sql_tp1 = select_query_json("select DELUSER from empcore_section 
												where DELETED = 'N' and TOPCORE in (3) and ESECODE in (".$sub_topcore.") order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
			if($sql_tp1[0]['DELUSER'] == '1') { // SR.GM
				if(!in_array(2444, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2444' and EMPCODE not in (".$apphd_notin.") 
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR 
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 2444) { 
							$_SESSION['sess_1'] .= 'THERUMURTHE R.D - 2444 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "2444~~";
							$_SESSION['chkexistuser'][] = 2444;
						}
					}
				}
			} else { // GM
				if(!in_array(2001, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2001' and EMPCODE not in (".$apphd_notin.")  
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 2001) { 
							$_SESSION['sess_1'] .= 'GANESH L - 2001 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "2001~~";
							$_SESSION['chkexistuser'][] = 2001;
						}
					}
				}
			}
		}		
	}

	$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
										where ESECODE in (".$sub_topcore.") and DESCODE in (132, 189, 19, 169) and EMPCODE >= 1000 and EMPCODE not in (".$apphd_notin.") 
										order by APPHEAD", "Centra", 'TCS');
	if(!in_array($sql_hod[0]['APPHEAD'], $_SESSION['chkexistuser'])){
		if($sql_hod[0]['EMPNAME'] !='') {
			$_SESSION['sess_1'] .= $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
			$_SESSION['sess_2'] += 1;
			$_SESSION['sess_3'] .= $sql_hod[0]['APPHEAD']."~~";
			$_SESSION['chkexistuser'][] = $sql_hod[0]['APPHEAD'];
		}
	}
}


function find_hod($tcsempsrno, $sub_topcore, $slt_core_department, $topcore, $approval_listings_id) {
	$alw_tpcr = 0;
	$sect = select_query_json("select ESECODE, BRNCODE from employee_office where empsrno = ".$tcsempsrno, "Centra", 'TCS');
	if($sect[0]['ESECODE'] == 97) { 
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
											where ESECODE in (".$sect[0]['ESECODE'].", 12) and DESCODE in (189) and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
	} elseif($sect[0]['BRNCODE'] == 888) { 
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office emp, designation des 
											where emp.descode = des.descode and brncode = ".$sect[0]['BRNCODE']." and emp.ESECODE = ".$sect[0]['ESECODE']." and emp.DESCODE in (132, 189) and EMPCODE >= 1000 
											order by des.dessrno desc, APPHEAD", "Centra", 'TCS'); 
	} elseif($sect[0]['BRNCODE'] == 201) { // BM - NAGARATHINAM A 
		$alw_tpcr++;
		$sect1 = select_query_json("select ESECODE, BRNCODE from employee_office where ESECODE != 11 and empsrno = ".$tcsempsrno, "Centra", 'TCS'); 
		if(count($sect1) > 0) {
			$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPCODE = 4562 and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		}
	} elseif($sect[0]['BRNCODE'] == 203) { // MDU TJ BM - PALANI N
		$alw_tpcr++;
		$sect1 = select_query_json("select ESECODE, BRNCODE from employee_office where ESECODE != 11 and empsrno = ".$tcsempsrno, "Centra", 'TCS'); 
		if(count($sect1) > 0) {
			$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPCODE = 29607 and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		}
	} elseif($sect[0]['BRNCODE'] == 204) { // TUP TJ BM - RMS 
		$alw_tpcr++;
		$sect1 = select_query_json("select ESECODE, BRNCODE from employee_office where ESECODE != 11 and empsrno = ".$tcsempsrno, "Centra", 'TCS'); 
		if(count($sect1) > 0) {
			$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPCODE = 1067 and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		}
	} elseif($sect[0]['BRNCODE'] == 300) { // CORP -TJ - Naveen Prakash BT - 4980
		$alw_tpcr++;
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where DESCODE in (110) and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
	} elseif($sect[0]['BRNCODE'] == 206) { // DGL TJ BM - SEKAR S
		$alw_tpcr++;
		$sect1 = select_query_json("select ESECODE, BRNCODE from employee_office where ESECODE != 11 and empsrno = 60890", "Centra", 'TCS'); 
	} elseif($sect[0]['BRNCODE'] == 1) { // 1 - TUP DGM - SIVAKUMAR P
		$alw_tpcr++;
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPSRNO in (12702) order by APPHEAD", "Centra", 'TCS');
	} elseif($sect[0]['BRNCODE'] == 10) { // 10 - EKM BM - BHARATHRAM K R
		$alw_tpcr++;
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPSRNO in (26098) order by APPHEAD", "Centra", 'TCS');
	} elseif($sect[0]['BRNCODE'] == 14) { // 14 - MDU BM - RATHEESH
		$alw_tpcr++;
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPSRNO in (2) order by APPHEAD", "Centra", 'TCS');
	} elseif($sect[0]['BRNCODE'] == 23) { // 23 - DGL BM - 127 - RAJA / 4297 - SAJI THOMAS
		$alw_tpcr++;
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPSRNO in (9463) order by APPHEAD", "Centra", 'TCS');
	} else { 
		$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office 
											where brncode = ".$sect[0]['BRNCODE']." and DESCODE in (92) and EMPCODE >= 1000 order by DESCODE", "Centra", 'TCS');
	}
	
	/* $alw0 = 0;
	if($slt_core_department == 11 or $slt_core_department == 16) {
		$alw0 = 1;
	} 
	if($sub_topcore == 11 or $sub_topcore == 72) {
		$alw0 = 1;
	}
	// echo "*****".$slt_core_department."*****".$sub_topcore."*****".$alw0."*****";

	if($alw0 == 1) {
		$sql_hw = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where EMPSRNO = 34593 and EMPCODE >= 1000 order by APPHEAD", "Centra", 'TCS');
		array_push($ignore_user, $sql_hw[0]['APPHEAD']);
		// echo $sql_hw[0]['EMPNAME']." - ".$sql_hw[0]['APPHEAD']." [ 1 Day(s) ] <br>";
		// echo "<br>//////<br>";
		if(!in_array($sql_hw[0]['APPHEAD'], $_SESSION['chkexistuser'])){
			$_SESSION['sess_1'] .= $sql_hw[0]['EMPNAME']." - ".$sql_hw[0]['APPHEAD']." [ 1 Day(s) ] <br>";
			$_SESSION['sess_2'] += 1;
			$_SESSION['sess_3'] .= $sql_hw[0]['APPHEAD']."~~";
			$_SESSION['chkexistuser'][] = $sql_hw[0]['APPHEAD'];
		}
	} */
	
	$sql_reqto = select_query_json("select distinct(DESSRNO) 
											from employee_office emp, designation des, employee_salary sal 
											where emp.descode = des.descode and emp.empsrno = sal.empsrno and emp.empsrno in ('".$tcsempsrno."') 
												and des.deleted = 'N' and sal.PAYCOMPANY = 1
										union
											select distinct(DESSRNO) 
											from employee_office emp, new_designation des, employee_salary sal 
											where emp.descode = des.descode and emp.empsrno = sal.empsrno and emp.empsrno in ('".$tcsempsrno."') 
												and des.deleted = 'N' and sal.PAYCOMPANY = 2", "Centra", 'TCS');
	
	for ($desg_i = 0; $desg_i < count($sql_hod); $desg_i++) { 
		if($sql_reqto[0]['DESSRNO'] != '' and $sql_hod[$desg_i]['EMPNAME'] != '') { $alw_tpcr++;
			// if(count($sql_app_hierarchy) > 0 && $skip == 0) {
				if(!in_array($sql_hod[$desg_i]['APPHEAD'], $_SESSION['chkexistuser'])){
					$_SESSION['sess_1'] .= $sql_hod[$desg_i]['EMPNAME']." - ".$sql_hod[$desg_i]['APPHEAD']." [ 1 Day(s) ] <br>";
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $sql_hod[$desg_i]['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $sql_hod[$desg_i]['APPHEAD'];
				}
			// } // 
		}
	}

	// echo "++".$alw_tpcr."++".$topcore."++".$sub_topcore."++".$sect[0]['ESECODE']."++";
	if($alw_tpcr == 0 and $sect[0]['BRNCODE'] == 888) {
		$sql_dptinchr = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
													where deleted = 'N' and apmcode = '".$approval_listings_id."' 
													ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 
		foreach ($sql_dptinchr as $key => $dptinchr_value) {
			$apphd_notin .= $dptinchr_value['APPHEAD'].",";
		}
		$apphd_notin = rtrim($apphd_notin, ",");

		// Topcore Based GM Add
		switch($topcore) {
			case 1 : // S-TEAM APPROVALS
				break;
			case 2 : // MANAGEMENT APPROVALS
				if(!in_array(1062, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '1062' and EMPCODE not in (".$apphd_notin.")  
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 1062) { 
							$_SESSION['sess_1'] .= 'SIVALINGAM.N. - 1062 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "1062~~";
							$_SESSION['chkexistuser'][] = 1062;
						}
					}
				}
				break;
			case 3 : // OPERATION APPROVALS
				$sql_tp1 = select_query_json("select DELUSER from empcore_section 
													where DELETED = 'N' and TOPCORE in (3) and ESECODE in (".$sub_topcore.") order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
				if($sql_tp1[0]['DELUSER'] == '1') { // SR.GM
					if(!in_array(2444, $_SESSION['chkexistuser'])){
						$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2444' and EMPCODE not in (".$apphd_notin.") 
																group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR 
																ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
						if(count($sql_exchk) > 0){
							if($sql_exchk[0]['APPHEAD'] != 2444) { 
								$_SESSION['sess_1'] .= 'THERUMURTHE R.D - 2444 [ 1 Day(s) ] <br>';
								$_SESSION['sess_2'] += 1;
								$_SESSION['sess_3'] .= "2444~~";
								$_SESSION['chkexistuser'][] = 2444;
							}
						}
					}
				} else { // GM
					if(!in_array(2001, $_SESSION['chkexistuser'])){
						$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2001' and EMPCODE not in (".$apphd_notin.")  
																group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
																ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
						if(count($sql_exchk) > 0){
							if($sql_exchk[0]['APPHEAD'] != 2001) { 
								$_SESSION['sess_1'] .= 'GANESH L - 2001 [ 1 Day(s) ] <br>';
								$_SESSION['sess_2'] += 1;
								$_SESSION['sess_3'] .= "2001~~";
								$_SESSION['chkexistuser'][] = 2001;
							}
						}
					}
				}
				break;
			default : // ADMIN APPROVALS
				if(!in_array(1986, $_SESSION['chkexistuser'])){
					$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
															where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '1986' and EMPCODE not in (".$apphd_notin.")  
															group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
															ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
					if(count($sql_exchk) > 0){
						if($sql_exchk[0]['APPHEAD'] != 1986) { 
							$_SESSION['sess_1'] .= 'KUMARAN K - 1986 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "1986~~";
							$_SESSION['chkexistuser'][] = 1986;
						}
					}
				}
				break;
		}
	}
}
?>
<!-- Request To -->
<div class="form-group trbg" style='min-height:40px;'>
	<div class="col-lg-3 col-md-3" style="text-align: right;">
		<label style='height:27px;'>Request To <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-md-9" style="font-weight:bold;">
	<?
	if($budgetidtype == 8) { // Only AGREEMENT Type of Submission
		// find_hod($_SESSION['tcs_empsrno'], $sub_topcore, $slt_core_department, $topcore, $approval_listings_id); // Choose HOD / BM User
		$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
														where deleted = 'N' and apmcode = '".$approval_listings_id."' 
														ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 

		foreach ($sql_dptinchr1 as $key => $dptincharge_value) {
			array_push($ignore_user, $dptincharge_value['APPHEAD']);
			/* if($dptincharge_value['APPHEAD'] == 3) { // 05082018-SKSIR
				find_finaluser(43400); // Find MD User - PS Madam
			}

			if($dptincharge_value['APPHEAD'] != '' and $dptincharge_value['APPHEAD'] != 3) { // 05082018-SKSIR */
			if($dptincharge_value['APPHEAD'] != '') { // 11082018-SKSIR
				if(!in_array($dptincharge_value['APPHEAD'], $_SESSION['chkexistuser'])){
					if($dptincharge_value['APPHEAD'] == 1 or $dptincharge_value['APPHEAD'] == 2 or $dptincharge_value['APPHEAD'] == 3) {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." <br>";
					} else {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." - ".$dptincharge_value['APPHEAD']." [ 1 Day(s) ] <br>";
					}
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $dptincharge_value['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $dptincharge_value['APPHEAD'];
				}
			}
		}
		// Only AGREEMENT Type of Submission
	}
	elseif($budgetidtype == 3) { // Only POLICY Type of Submission
		// find_hod($_SESSION['tcs_empsrno'], $sub_topcore, $slt_core_department, $topcore, $approval_listings_id); // Choose HOD / BM User
		
		$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
														where deleted = 'N' and apmcode = '".$approval_listings_id."' 
														ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 

		foreach ($sql_dptinchr1 as $key => $dptincharge_value) {
			array_push($ignore_user, $dptincharge_value['APPHEAD']);
			/* if($dptincharge_value['APPHEAD'] == 3) { // 05082018-SKSIR
				find_finaluser(43400); // Find MD User - PS Madam
			}

			if($dptincharge_value['APPHEAD'] != '' and $dptincharge_value['APPHEAD'] != 3) { // 05082018-SKSIR */
			if($dptincharge_value['APPHEAD'] != '') { // 11082018-SKSIR
				if(!in_array($dptincharge_value['APPHEAD'], $_SESSION['chkexistuser'])){
					if($dptincharge_value['APPHEAD'] == 1 or $dptincharge_value['APPHEAD'] == 2 or $dptincharge_value['APPHEAD'] == 3) {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." <br>";
					} else {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." - ".$dptincharge_value['APPHEAD']." [ 1 Day(s) ] <br>";
					}
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $dptincharge_value['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $dptincharge_value['APPHEAD'];
				}
			}
		}
		// Only POLICY Type of Submission
	}
	elseif($budgetidtype == 2) { // Only IMPLEMENTATION Type of Submission
		// find_hod($_SESSION['tcs_empsrno'], $sub_topcore, $slt_core_department, $topcore, $approval_listings_id); // Choose HOD / BM User

		$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
														where deleted = 'N' and apmcode = '".$approval_listings_id."' 
														ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 

		foreach ($sql_dptinchr1 as $key => $dptincharge_value) {
			array_push($ignore_user, $dptincharge_value['APPHEAD']);

			/* // 05082018-SKSIR
			if($approval_listings_id == 856) {
				$all_cont = ($dptincharge_value['APPHEAD'] != '');
			} else {
				if($dptincharge_value['APPHEAD'] == 3) { 
					find_finaluser(43400); // Find MD User - PS Madam
				}
				$all_cont = ($dptincharge_value['APPHEAD'] != '' and $dptincharge_value['APPHEAD'] != 3);
			}

			if($all_cont) { // 05082018-SKSIR */
			if($dptincharge_value['APPHEAD'] != '') {
				if(!in_array($dptincharge_value['APPHEAD'], $_SESSION['chkexistuser'])){
					if($dptincharge_value['APPHEAD'] == 1 or $dptincharge_value['APPHEAD'] == 2 or $dptincharge_value['APPHEAD'] == 3) {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." <br>";
					} else {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." - ".$dptincharge_value['APPHEAD']." [ 1 Day(s) ] <br>";
					}
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $dptincharge_value['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $dptincharge_value['APPHEAD'];
				}
			}
		}
	}
	elseif($budgetidtype == 4) { // Only INTERNAL REQUEST Type of Submission

		find_hod($_SESSION['tcs_empsrno'], $sub_topcore, $slt_core_department, $topcore, $approval_listings_id); // Choose HOD / BM User
		if(!in_array($approval_listings_id, $topcore_head_not_available)) {
			find_hod_bydept($sub_topcore, $slt_core_department, $approval_listings_id); // Chosen Department Based HOD / DGM
		}
		find_hod_by_selected_dept($sub_topcore, $slt_core_department, $approval_listings_id); // Chosen Department Based HOD / DGM
		

		if($slt_brnch == 888) {
			switch($topcore) {
				case 1 : // S-TEAM APPROVALS
					/* if(!in_array(1986, $_SESSION['chkexistuser'])){
						$_SESSION['sess_1'] .= 'KUMARAN K - 1986 [ 1 Day(s) ] <br>';
						$_SESSION['sess_2'] += 1;
						$_SESSION['sess_3'] .= "1986~~";
						$_SESSION['chkexistuser'][] = 1986;
					} */
					break;
				case 2 : // MANAGEMENT APPROVALS
					if(!in_array(1062, $_SESSION['chkexistuser'])){
						$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '1062' 
																group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
																ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
						if($sql_exchk[0]['APPHEAD'] != 1062) { 
							$_SESSION['sess_1'] .= 'SIVALINGAM.N. - 1062 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "1062~~";
							$_SESSION['chkexistuser'][] = 1062;
						}
					}
					break;
				case 3 : // OPERATION APPROVALS
					$sql_tp1 = select_query_json("select DELUSER from empcore_section 
														where DELETED = 'N' and TOPCORE in (3) and CORCODE in (".$sub_topcore.") order by TOPCORE, CORNAME Asc", "Centra", 'TCS');
					if($sql_tp1[0]['DELUSER'] == '1') { // SR.GM
						if(!in_array(2444, $_SESSION['chkexistuser'])){
							$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																	where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2444'
																	group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR 
																	ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
							if($sql_exchk[0]['APPHEAD'] != 2444) { 
								$_SESSION['sess_1'] .= 'THERUMURTHE R.D - 2444 [ 1 Day(s) ] <br>';
								$_SESSION['sess_2'] += 1;
								$_SESSION['sess_3'] .= "2444~~";
								$_SESSION['chkexistuser'][] = 2444;
							}
						}
					} else { // GM
						if(!in_array(2001, $_SESSION['chkexistuser'])){
							$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																	where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '2001' 
																	group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
																	ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
							if($sql_exchk[0]['APPHEAD'] != 2001) { 
								$_SESSION['sess_1'] .= 'GANESH L - 2001 [ 1 Day(s) ] <br>';
								$_SESSION['sess_2'] += 1;
								$_SESSION['sess_3'] .= "2001~~";
								$_SESSION['chkexistuser'][] = 2001;
							}
						}
					}
					break;
				default : // ADMIN APPROVALS
					if(!in_array(1986, $_SESSION['chkexistuser'])){
						$sql_exchk = select_query_json("select max(BRNHDSR) MAXBRNHDSR, EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
																where deleted = 'N' and apmcode = '".$approval_listings_id."' and EMPCODE = '1986' 
																group by EMPSRNO, EMPCODE, EMPNAME, BRNHDSR
																ORDER BY BRNHDSR desc", "Centra", 'TCS'); 
						if($sql_exchk[0]['APPHEAD'] != 1986) { 
							$_SESSION['sess_1'] .= 'KUMARAN K - 1986 [ 1 Day(s) ] <br>';
							$_SESSION['sess_2'] += 1;
							$_SESSION['sess_3'] .= "1986~~";
							$_SESSION['chkexistuser'][] = 1986;
						}
					}
					break;
			}
		}
		// Only INTERNAL REQUEST Type of Submission

		//////////** CURRENT REGULAR PROCESS..
		/* $sql_dptinchr1 = select_query_json("select emp.EMPSRNO, emp.EMPCODE APPHEAD, emp.EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days 
													from approval_head_flow flw, employee_office emp
													where emp.empsrno = flw.empsrno and flw.apmcode = ".$approval_listings_id." and flw.deleted = 'N' 
													ORDER BY flw.APFLCDE, flw.APFLSRN", "Centra", 'TCS'); */

		$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days from approval_branch_head 
													where deleted = 'N' and apmcode = '".$approval_listings_id."' 
													ORDER BY BRNHDCD, BRNHDSR", "Centra", 'TCS'); 
		foreach ($sql_dptinchr1 as $key => $dptincharge_value) {
			array_push($ignore_user, $dptincharge_value['APPHEAD']);
			
			/* // 05082018-SKSIR
			if($approval_listings_id == 1371 or $approval_listings_id == 1372) {
				$all_cont = ($dptincharge_value['APPHEAD'] != '');
			} else {
				if($dptincharge_value['APPHEAD'] == 3) { 
					find_finaluser(43400); // Find MD User - PS Madam
				}
				$all_cont = ($dptincharge_value['APPHEAD'] != '' and $dptincharge_value['APPHEAD'] != 3);
			}

			if($all_cont) { // 05082018-SKSIR */
			if($dptincharge_value['APPHEAD'] != '') {
				if(!in_array($dptincharge_value['APPHEAD'], $_SESSION['chkexistuser'])){
					if($dptincharge_value['APPHEAD'] == 1 or $dptincharge_value['APPHEAD'] == 2 or $dptincharge_value['APPHEAD'] == 3) {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." <br>";
					} else {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." - ".$dptincharge_value['APPHEAD']." [ 1 Day(s) ] <br>";
					}
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $dptincharge_value['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $dptincharge_value['APPHEAD'];
				} 
			}
		}
		//////////** CURRENT REGULAR PROCESS..
	} else { // other approvals - BUDGET
		// find_hod($_SESSION['tcs_empsrno'], $sub_topcore, $slt_core_department, $topcore, $approval_listings_id); // Choose HOD / BM User

		$vlu_cont = "";
		/* if($txtrequest_value <= 100000) {
			$vlu_cont = " and APRVALU <= 100000 ";
		} else {
			$vlu_cont = " and APRVALU > 100000 ";
		} */

		if($slt_brnch == 100 or $slt_brnch == 102 or $slt_brnch == 107 or $slt_brnch == 300 or $slt_brnch == 110 or $slt_brnch == 113 or $slt_brnch == 114 or $slt_brnch == 115 or $slt_brnch == 118 or $slt_brnch == 30 or $slt_brnch == 33) {
			$slt_brnch1 = $slt_brnch;
			$slt_brnch = 888;
		}
		
		if($type == 1 or $type == 6) { // fixed budget & reserved budget
			$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days, min(aprvalu) aprvalu, max(BRNHDSR) brnhdsr 	
														from approval_branch_head 
														where brncode = ".$slt_brnch." and depcode = ".$deptid." and tarnumb = ".$tarnum." and deleted = 'N' and APRVALU > 0 
															and ".$txtrequest_value." < aprvalu ".$vlu_cont." 
														group by EMPSRNO, EMPCODE, EMPNAME, 'DEPARTMENT INCHARGE', 1, BRNHDCD
														ORDER BY BRNHDCD, BRNHDSR, APRVALU", "Centra", 'TCS');
		} elseif($type == 7) { // new proposal
			// $vlu_cont = " and APRVALU > 100000 ";
			$sql_dptinchr1 = select_query_json("select EMPSRNO, EMPCODE APPHEAD, EMPNAME, 'DEPARTMENT INCHARGE' APPTITL, 1 allow_days, min(aprvalu) aprvalu, max(BRNHDSR) brnhdsr
														from approval_branch_head 
														where brncode = ".$slt_brnch." and depcode = ".$deptid." and tarnumb = ".$tarnum." and deleted = 'N' and APRVALU > 0 
															and ".$txtrequest_value." < aprvalu ".$vlu_cont." 
														group by EMPSRNO, EMPCODE, EMPNAME, 'DEPARTMENT INCHARGE', 1, BRNHDCD
														ORDER BY BRNHDCD, BRNHDSR, APRVALU", "Centra", 'TCS'); 
		} 

		$vl = 0;
		foreach ($sql_dptinchr1 as $key => $dptincharge_value) {
			array_push($ignore_user, $dptincharge_value['APPHEAD']);
			/* if($dptincharge_value['APPHEAD'] == 3) { // 05082018-SKSIR
				find_finaluser(43400); // Find MD User - PS Madam
			}

			if($dptincharge_value['APPHEAD'] != '' and $dptincharge_value['APPHEAD'] != 3) { // 05082018-SKSIR */
			if($dptincharge_value['APPHEAD'] != '') {
				if(!in_array($dptincharge_value['APPHEAD'], $_SESSION['chkexistuser'])){ $vl++;
					if($dptincharge_value['APPHEAD'] == 1 or $dptincharge_value['APPHEAD'] == 2 or $dptincharge_value['APPHEAD'] == 3) {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." <br>";
					} else {
						$_SESSION['sess_1'] .= $dptincharge_value['EMPNAME']." - ".$dptincharge_value['APPHEAD']." [ 1 Day(s) ] <br>";
					}
					$_SESSION['sess_2'] += 1;
					$_SESSION['sess_3'] .= $dptincharge_value['APPHEAD']."~~";
					$_SESSION['chkexistuser'][] = $dptincharge_value['APPHEAD'];
				}
			}
		}

		if($slt_brnch1 == 100 or $slt_brnch1 == 102 or $slt_brnch1 == 107 or $slt_brnch1 == 300 or $slt_brnch1 == 110 or $slt_brnch1 == 113 or $slt_brnch1 == 114 or $slt_brnch1 == 115 or $slt_brnch1 == 118 or $slt_brnch1 == 30 or $slt_brnch1 == 33) {
			$slt_brnch = $slt_brnch1;
		}

		if($type == 7 and $slt_brnch != 120 and $slt_brnch != 114 and $slt_brnch != 121 and $vl > 0) { // new proposal must add KS Sir except tailyou, clean today branches
		// if($type == 7 and $vl > 0) { // new proposal
			find_finaluser(20118); // Find MD User - KS SIR
		}

		if($slt_budgetmode == '7' and $vl > 0) { // Happay Card Payment must add to SK sir
			find_finaluser(21344); // Find MD User - SK SIR // 05082018-SKSIR
			// find_finaluser(43400); // Find MD User - PS Madam // 11082018-SKSIR
		}
	}

	echo $_SESSION['sess_1'];
	$appdays = $_SESSION['sess_2'];
	$appuser = $_SESSION['sess_3'];
	
	$_SESSION['sess_1'] = '';
	$_SESSION['sess_2'] = '';
	$_SESSION['sess_3'] = '';
	// exit; ?>
	</div>
</div>
<input type="hidden" name="hid_noofdays" id="hid_noofdays" value="<?=$appdays?>">
<input type="hidden" name="hid_appuser" id="hid_appuser" value="<?=$appuser?>">
<? /* <input type="hidden" name="hid_newentry" id="hid_newentry" value="<?=$newentry?>"> */ ?>
<input type="hidden" name="hid_newentry" id="hid_newentry" value="1">
<input type="hidden" name="hid_apmcd" id="hid_apmcd" value="<?=$approval_listings_id?>">

<input type="hidden" name="hid_mdak" id="hid_mdak" value="<?=$mdak?>">
<input type="hidden" name="hid_mdks" id="hid_mdks" value="<?=$mdks?>">
<div class='clear clear_both'></div>
<!-- Request To -->