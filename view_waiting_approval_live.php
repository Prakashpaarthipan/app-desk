<?
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if($_REQUEST['rsrid'] == '') {
    $rqsrno = 1;
} else {
    $rqsrno = $_REQUEST['rsrid'];
}

if($status != 'archive') {
	// AFTER MAR/1/2017 - FOR AK SIR
	$mr_ak_date = ""; $mr_ak_date0 = "";
	if($_SESSION['tcs_usrcode'] == 3000000) {
		$mr_ak_date = " and trunc(req.APPRSFR) >= TO_DATE('01-MAR-17','dd-Mon-yy') ";
		$mr_ak_date0 = " and trunc(req.APPRSFR) >= TO_DATE('01-MAR-17','dd-Mon-yy') ";
	}
}

if($_REQUEST['action'] == 'pview') {
	$pend_view = " req.REQSTBY = '".$_SESSION['tcs_empsrno']."' ";
	$action = 'view';
	$mdaction = 'view';
	$usrid = $_SESSION['tcs_empsrno'];
} elseif($_REQUEST['action'] == 'view' and $md != '') {
	$pend_view = " req.REQSTFR = '".$_REQUEST['md']."' ";
	$action = 'view';
	$mdaction = 'view';
	$usrid = 20118;
} else {
	$action = 'view';
	$mdaction = 'md';
	$usrid = $_SESSION['tcs_empsrno'];
}


$sql_exist = select_query_json("select arqsrno from APPROVAL_REQUEST req
										where req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.appstat = 'W' and req.deleted='N' and
											req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' and req.REQSTBY = '".$usrid."' ".$usr." ".$mr_ak_date."
										order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TEST');
$arsrno = $_REQUEST['rsrid'];
if(count($sql_exist) > 0) {
	$arsrno = $sql_exist[0]['ARQSRNO'];
}
// echo "**".$arsrno."**";

// Define user rights
$accrights = 1;
$tmporlive = 0; // 0 - TEMP Table / 1 - LIVE Table
switch ($_SESSION['tcs_empsrno']) {
	case 61579: 	// Selva Muthu Kumar - 17108
	case 59006: 	// Ranganathan - 15613
	case 48237: 	// SELVAGANAPATHI - 20446
		$accrights = 2;
		$tmporlive = 0;
		break;

	case 125: 		// PKN - 1118
	case 188: 		// Ashok - 1657
	case 62762: 	// Ramakrishnan - 14659
	case 23684: 	// prem - 5078
	case 200:	 	// balamurugan - 1845
	case 14180: 	// Manoharan - 4317
	case 82237: 	// Dhinesh Khanna - 24262
	case 53864: 	// Madhan - 12232
	case 86464: 	// Nanthakumar - 15601
		$accrights = 3;
		$sql_expkn = select_query_json("select * from APPROVAL_REQUEST
												where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ATCCODE = '".$_REQUEST['creid']."' and
													ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and REQSTBY = 61579
												order by ARQSRNO", "Centra", 'TEST');
		if(count($sql_expkn) > 0) {
			$tmporlive = 1;
		} else {
			$tmporlive = 0;
		}
		break;

	case 1418: 		// Narayana Moorthy - 2834
	case 10: 		// RDTM - 2444
	case 168: 		// NSM - 1062
	case 177: 		// Ganesh L - 2001
	case 452:		// Kumaran K - 1986
	case 20118:		// Sri KS Sir - 1
	case 21344:		// Mr. AK Sir - 3
	case 43400:		// Mrs. PS Madam - 2
	case 65945:		// Mrs. Anumalervili Madam - 4
	case 66114:		// Mrs. Sivasankari Babu Madam - 5
		$accrights = 4;
		$tmporlive = 1;
		break;

	default: // All Other Users
		$accrights = 1;
		$sql_expkn = select_query_json("select APRNUMB from APPROVAL_REQUEST
												where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ATCCODE = '".$_REQUEST['creid']."'
													and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N'
												order by ARQSRNO", "Centra", 'TEST');
		$sql_tmporlive = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
													where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_expkn[0]['APRNUMB']."' and but.aprsrno = 1", "Centra", 'TCS');
		if(count($sql_tmporlive) > 0) {
			$tmporlive = 1;
		} else {
			$tmporlive = 0;
		}
		break;
}
// echo "##".$accrights."##".$tmporlive."##";
// Define user rights

$tbl_read = "approval_request";
$field_read = array();
$field_read['INTPESC'] = 1; // This 1 is indicate us, this approval is read by approval user
// print_r($field_read);
$where_read = " ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$_REQUEST['rsrid']."' and ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."'";
$update_read = update_dbquery($field_read, $tbl_read, $where_read);

$rturl = 'waiting_approval.php';
if($_SESSION['tcs_empsrno'] == '20118' or $_SESSION['tcs_empsrno'] == '21344' or $_SESSION['tcs_empsrno'] == '66114' or $_SESSION['tcs_empsrno'] == '65945' or $_SESSION['tcs_empsrno'] == '43400' or $_SESSION['tcs_empsrno'] == '939') {
	$rturl = 'waiting_approval.php';
}
if($urlstatus == 'reports' and $_SESSION['tcs_empsrno'] == '43400') {
	// $rturl = 'waiting_mdapproval_reports.php';
	$rturl = 'waiting_approval.php';
}

$next_url = "http://$_SERVER[HTTP_HOST]/approval-desk/".$rturl."?status=success";
// echo "<pre>";
// $_SESSION['tcs_esecode'] = $_SESSION['tcs_originalesecode'];

$stmlgl_desk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", "Centra", 'TCS');
$appdesk = $stmlgl_desk[0]['DESKUSR'];
$steam = $stmlgl_desk[0]['STAMUSR'];
$legalteam = $stmlgl_desk[0]['LEGLUSR'];

$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$hidapryear = $current_year[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$sysip = $_SERVER['REMOTE_ADDR'];
$ftp_server = "www.tcstextile.in";


if($_SERVER['REQUEST_METHOD']=="POST")
{
	// echo "***"; print_r($_REQUEST); echo "***<br>"; // exit;
	/* echo "***".$_REQUEST['sbmt_approve']."***".$_REQUEST['sbmt_verification']."***".$_REQUEST['sbmt_forward']."***".$_REQUEST['sbmt_pending']."***".$_REQUEST['sbmt_reject']."***".$_REQUEST['sbmt_query']."***".$_REQUEST['sbmt_response']."***".$_REQUEST['sbmt_update']."***".$_REQUEST['sbmt_request']."***".$_REQUEST['hid_action']."***";
	exit; */// txt_tmporlive
	$fnupdt = 0; $fncretr = '';
	$sql_live = select_query_json("select * from APPROVAL_REQUEST
										where ARQCODE = '".$hid_reqid."' and APPSTAT = 'N' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and
											ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'", "Centra", 'TEST');
	$cntlive = count($sql_live);
	if($cntlive > 0) {
		// Update in APPROVAL_REQUEST Table
		$tbl_apprq = "APPROVAL_REQUEST";
		$field_apprq['APPSTAT'] = 'F';
		$where_apprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'N' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
		// print_r($field_appreq); //echo "<br>";
		$update_apprq = update_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		// echo "!!!".$update_apprq."@@@";
		// Update in APPROVAL_REQUEST Table
	} else { $update_apprq = 0; ?>
			<script>alert('Already You have provided the remarks and status'); window.location='<?=$rturl?>';</script>
		<?php exit;
	}

	$update_apprq = 1; $cntlive = 1;
	if($update_apprq == 1 and $cntlive > 0) {
		if($slt_branch == 888) { $slt_branch = '100'; }

		// Move the Temp Table to Live Table - PKN Login
		$tarnochang_allowornot = 1; // 0 - Not Allowed / 1 - Allowed
		if($_SESSION['tcs_empsrno'] == 61579 and $txt_extarno != $slt_targetno and $txtrequest_value > 0) {
			$sql_extr = select_query_json("select sum(nvl(APPRVAL, 0)) aprvlu from approval_budget_planner_temp
												where BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$slt_core_department." and deleted = 'N'", "Centra", 'TEST');
			if(count($sql_extr) > 0) {
				$sql_yrlyttl = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) + sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE,
														(sum(distinct nvl(sm.BUDVALUE, 0)) - sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) pendingvalue
													from budget_planner_head_sum sm, approval_budget_planner_temp tm
													where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N' and sm.BRNCODE=".$slt_branch."
														and sm.BUDYEAR = '".$hid_year."' and sm.EXPSRNO = ".$slt_core_department."", "Centra", 'TEST');
			} else {
				$sql_yrlyttl = select_query_json("select sum(nvl(BUDVALUE, 0)) BUDVALUE, sum(nvl(APPVALUE, 0)) APPVALUE, (sum(nvl(BUDVALUE, 0)) - sum(nvl(APPVALUE, 0))) pendingvalue
														from budget_planner_head_sum where BRNCODE=".$slt_branch." and BUDYEAR = '".$hid_year."' and EXPSRNO = ".$slt_core_department."", "Centra", 'TEST');
			}

			if($slt_submission == 7) {
				$ttl_lock = 10000000000000;
				$tarnochang_allowornot = 1;
			} else {
				$ttl_lock = $sql_yrlyttl[0]['PENDINGVALUE'];
				if($sql_yrlyttl[0]['PENDINGVALUE'] <= $txtrequest_value) {
					$tarnochang_allowornot = 0;
				} else {
					$tarnochang_allowornot = 1;
				}
			}

			if($tarnochang_allowornot == 1) {
				// Step 1 : Update the Temp Table records - TMTARNO with Existing Target NO & Update the New Target NO with TARNUMB
				$tbl_appplan = "approval_budget_planner_temp";
				$field_appplan = array();
				$field_appplan['TARNUMB'] = $slt_targetno;
				$field_appplan['EXPSRNO'] = $slt_core_department;
				$field_appplan['TMTARNO'] = $txt_extarno;
				// print_r($field_appplan);
				$where_appplan = " APRNUMB='".$txt_approval_number."' and DELETED = 'N' ";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		// echo "<br>%%".$tarnochang_allowornot."%%"; // exit;
		// if(parseInt($('#ttl_pndlock').val()) < parseInt($('#txtrequest_value').val())) {
		if($tarnochang_allowornot == 0) { ?>
			<script>alert('Request Value exceeds for this Target Number. So kindly reject this approval and create new approval for this!'); window.location='<?=$rturl?>';</script>
		<?php exit;
		}
		// Move the Temp Table to Live Table - PKN Login
		// exit;

		// $topcore = select_query_json("Select ATCNAME, ATCCODE From APPROVAL_TOPCORE where deleted = 'N' and ATCCODE = ".$slt_topcore, "Centra", 'TCS'); // Topcore
		// $current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
		$maxarqcode = select_query_json("Select nvl(Max(ARQCODE),1) maxarqcode, nvl(Max(ARQSRNO),0)+1 maxarqsrno From APPROVAL_REQUEST
												WHERE ATCCODE = ".$slt_topcore." and APMCODE = '".$slt_approval_listings."' and ATMCODE = '".$slt_subtype."' and
													ATYCODE = '".$slt_submission."' and ARQYEAR = '".$hid_year."' and ARQCODE = '".$hid_reqid."'", "Centra", 'TEST'); // Get the Last record + 1 from APPROVAL_REQUEST

		$txtfrom_date1 = strtotime($txtfrom_date);
		$txtfrom_date2 = strtoupper(date('d-M-Y h:i:s A', $txtfrom_date1));
		$txtto_date1 = strtotime($txtto_date);
		$txtto_date2 = strtoupper(date('d-M-Y h:i:s A', $txtto_date1));

		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		$currentdate1 = strtoupper(date('d-m-Y'));
		$currenttime = strtoupper(date('H:i A'));
		$srno = str_pad($hid_reqid, 6, '0', STR_PAD_LEFT);
		$dept = select_query_json("select DEPNAME from approval_department where ESECODE like '%".$_SESSION['tcs_esecode']."%'", "Centra", 'TCS'); // Get user department from approval_department

		$noofattachment = $_REQUEST['hid_appattn_cnt'];
		$attch = $_REQUEST['hid_appattn_cnt'];
		if($_FILES['txt_submission_fieldimpl']['name'][0] != '') {
			$assign=$_FILES['txt_submission_fieldimpl']['name'];
			$noofattachment += count($_FILES['txt_submission_fieldimpl']['name']);
		}

		if($_FILES['txt_submission_othersupdocs']['name'][0] != '') {
			$assign0=$_FILES['txt_submission_othersupdocs']['name'];
			$noofattachment += count($_FILES['txt_submission_othersupdocs']['name']);
		}

		if($_FILES['txt_submission_quotations']['name'][0] != '') {
			$assign1=$_FILES['txt_submission_quotations']['name'];
			$noofattachment += count($_FILES['txt_submission_quotations']['name']);
		}

		if($_FILES['txt_submission_clrphoto']['name'][0] != '') {
			$assign2=$_FILES['txt_submission_clrphoto']['name'];
			$noofattachment += count($_FILES['txt_submission_clrphoto']['name']);
		}

		if($_FILES['txt_submission_last_approval']['name'][0] != '') {
			$assign3=$_FILES['txt_submission_last_approval']['name'];
			$noofattachment += count($_FILES['txt_submission_last_approval']['name']);
		}

		if($_FILES['txt_submission_artwork']['name'][0] != '') {
			$assign4=$_FILES['txt_submission_artwork']['name'];
			$noofattachment += count($_FILES['txt_submission_artwork']['name']);
		}


		$t = 0;
		$apuser = explode("~~", $hid_appuser);
		// print_r($apuser); echo "<br>***".$apfrwrdusr."*****".$apusr."*****".$hid_appuser."*****".$apuser."*******########<br>"; exit;
		if($apuser[0] == '' or $apuser[0] == 0)
		{
			if($_SESSION['tcs_user'] == $appdesk or $_SESSION['tcs_user'] == $steam or $_SESSION['tcs_user'] == $legalteam) {
				// echo "+++++++++++";
				$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", "Centra", 'TCS');
				if($_REQUEST['steam_legal'] == 1)
				{
					$apfrwrdusr = $sql_apdesk[0]['STAMUSR'];
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$cnt_v = count($sql_app_hierarchy) - 1;
					$apusr = $sql_app_hierarchy[$cnt_v]['APPHEAD'];
				} elseif($_REQUEST['steam_legal'] == 2) {
					$apfrwrdusr = $apuser[0];
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$cnt_v = count($sql_app_hierarchy) - 1;
					$apusr = $sql_app_hierarchy[$cnt_v]['APPHEAD'];
				} else {
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", "Centra", 'TEST');
					$cnt_v = count($sql_app_hierarchy) - 1;
					$apusr = $sql_app_hierarchy[$cnt_v]['APPHEAD'];
					$apfrwrdusr = $sql_app_hierarchy[0]['APPHEAD'];
				}
			}  elseif($_REQUEST['slt_intermediate_team'] != '' and $_REQUEST['hid_action'] == 'sbmt_verification') {
				$apusr = $_SESSION['tcs_user'];
			}
		} else {
			for($apusri = 0; $apusri < count($apuser)-1; $apusri++)
			{
				$empg = select_query_json("select * from employee_office where EMPCODE = ".$apuser[$apusri], "Centra", 'TEST');
				$emapr = select_query_json("select APPROVAL_REQUEST.*, to_char(APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time, to_char(APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time,
													to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time, to_char(INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time,
													to_char(APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time
												from APPROVAL_REQUEST
												where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
													deleted = 'N' and APPSTAT in ('F', 'P') and RQBYDES like '".$apuser[$apusri]." - %'
												order by ARQCODE", "Centra", 'TEST'); // reqid=$hid_reqid&year=$hid_year&rsrid=$hid_rsrid&creid=$hid_creid&typeid=$hid_typeid

				// if($empg[0][0] != $_SESSION['tcs_empsrno'] and count($emapr) == 0) {
				if($empg[0]['EMPSRNO'] != $_SESSION['tcs_empsrno'] and count($emapr) <= 1) {
					$sql_ex_intverify2 = select_query_json("select REQSTBY, appfrwd from APPROVAL_REQUEST
																where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
																	deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and aprnumb like '".$txt_approval_number."'
																order by ARQSRNO asc", "Centra", 'TEST');
					if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and count($sql_ex_intverify2) > 0) {
						$usr_apr = select_query_json("select RQTODES from APPROVAL_REQUEST where aprnumb like '".$txt_approval_number."' and arqsrno = 1", "Centra", 'TEST');
						$exusr_apr = explode(" - ", $usr_apr[0]['RQTODES']);
						$apusr = $exusr_apr[0];
					} else {
						$apusr = $apuser[$apusri];
					}

					if($t == 0) {
						// echo "<br>***".$_SESSION['tcs_user']."***".$appdesk;

						if($_SESSION['tcs_descode'] == 132) {

							// Enable this
							$sql_apdesk_requiredornot = select_query_json("select VRFYREQ from APPROVAL_MODE_HIERARCHY
																				where APMCODE = '".$slt_approval_listings."' and DELETED = 'N' order by APMCODE, AMHSRNO desc", "Centra", 'TEST');
							$avoid_steam = 0; // Avoid S-Team, Legal and Approval Desk
							if($sql_apdesk_requiredornot[0]['VRFYREQ'] == 0) {
								$avoid_steam = 1;
							} else {
								$avoid_steam = 0;
							}
							// Enable this
							// exit;

							/* // Remove this
							$avoid_steam = 0; // Avoid S-Team, Legal and Approval Desk
							if($_REQUEST['slt_approval_listings'] == 91 or $_REQUEST['slt_approval_listings'] == 92)
							{
								$avoid_steam = 1;
							}
							/* // Remove this */

							if($avoid_steam == 1) {
								$apfrwrdusr = $apuser[$apusri];
							} elseif($apuser[0] != '') { // HOD of HW
								$apfrwrdusr = $apuser[$apusri]; // HOD of HW
							} else {
								$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", "Centra", 'TCS');
								$apfrwrdusr = $sql_apdesk[0]['DESKUSR'];
							}
						} // Forward to Approval desk from HOD
						elseif($_SESSION['tcs_user'] == $appdesk) {
							$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", "Centra", 'TCS');
							if($_REQUEST['steam_legal'] == 1) { $apfrwrdusr = $sql_apdesk[0]['STAMUSR']; }
							elseif($_REQUEST['steam_legal'] == 2) { $apfrwrdusr = $apuser[0]; }
							else { $apfrwrdusr = $sql_apdesk[0]['LEGLUSR']; }
						} // Forward to S-Team or Legal Team from Approval desk
						else { $apfrwrdusr = $apuser[$apusri]; } // Forward to Next Level Approval
					}
					$t++;
				}
			}
		}
		// echo "<br>####***".$apfrwrdusr."*****".$apusr."*****"; exit();

		/* Finish by */
		$end = 0;
		if($apfrwrdusr == '')
		{
			$end = 1;
		}
		if($_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78)
		{
			// $end = 1;
		}
		/* Finish by */

		$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apusr, "Centra", 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($emp[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}
		$todesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$emp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
		$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section
		// echo $_REQUEST['hid_action']."-----select * from employee_office where EMPCODE = ".$apusr;

		// echo "<br>^^^^^^^^".$_REQUEST['slt_intermediate_team']."^^^^".$_REQUEST['hid_action']."^^^^^^^^<br>";
		if($_REQUEST['hid_action'] == 'sbmt_query') {
			$sql_creator = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."'
														and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P')
													order by ARQCODE", "Centra", 'TEST');
			if(count($sql_creator) <= 0) {
				$sql_creator = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
															and deleted = 'N' and APPSTAT in ('F', 'P')
														order by ARQCODE", "Centra", 'TEST');
			}

			$apfrwrdusr = $sql_creator[0]['REQSTBY'];
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section
		} elseif($_REQUEST['hid_action'] == 'sbmt_response') {
			$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and
														ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'N', 'P')
													order by ARQCODE", "Centra", 'TEST');
			if(count($sql_raiser) <= 0) {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."'
															and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'N', 'P')
														order by ARQCODE", "Centra", 'TEST');
			}

			$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section

			$sql_lastuser = select_query_json("select * from APPROVAL_mdhierarchy where aprnumb like '".$txt_approval_number."' and AMHSRNO = 1", "Centra", 'TCS');
			$lstur = $sql_lastuser[0]['APPHEAD'];
			if($sql_lastuser[0]['APPHEAD'] == '') {
				$sql_lastuser = select_query_json("select RQTODES from APPROVAL_REQUEST where aprnumb like '".$txt_approval_number."' and ARQSRNO = 1", "Centra", 'TEST');
				$lstur1 = explode(" - ", $sql_lastuser[0]['RQTODES']);
				$lstur = $lstur1[0];
			}
			$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$lstur, "Centra", 'TCS');
			$todesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$emp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
			$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section
		} elseif($_REQUEST['slt_intermediate_team'] != '' and $_REQUEST['hid_action'] == 'sbmt_verification') {
			$apfrwrdusr = $slt_intermediate_team;
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apfrwrdusr, "Centra", 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section
		} else {
			// echo "<br>***".$hid_int_verification."***<br>";
			$sql_ex_intverify1 = select_query_json("select ARQSRNO from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
															and deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and REQSTBY = ".$txt_requestby."
														order by ARQSRNO desc", "Centra", 'TEST');

			$sql_ex_intverify2 = select_query_json("select REQSTBY, appfrwd from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
															and deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and aprnumb like '".$txt_approval_number."'
														order by ARQSRNO asc", "Centra", 'TEST');

			if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and count($sql_ex_intverify2) > 0) {
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empsrno = ".$sql_ex_intverify2[0]['REQSTBY'], "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section

			} elseif($hid_int_verification == 'I') {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."'
															and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P', 'N')
														order by ARQCODE", "Centra", 'TEST');
				if(count($sql_raiser) <= 0) {
					$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
															where ARQCODE = '".$reqid."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."'
																and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P', 'N')
															order by ARQCODE", "Centra", 'TEST');
				}

				$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section

				$sql_creator = select_query_json("select RQESTTO from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."'
														and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P')
													order by ARQCODE", "Centra", 'TEST');
				$apfrwrdusr = $sql_creator[0]['RQESTTO'];
				$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($emp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$todesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$emp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
				$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section
			} elseif($_REQUEST['hid_action'] == 'sbmt_approve') {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."'
															and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('A', 'F', 'P')
        													order by ARQCODE", "Centra", 'TEST');
				if(count($sql_raiser) <= 0) {
					$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
															where ARQCODE = '".$reqid."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
																and deleted = 'N' and APPSTAT in ('A', 'F', 'P')
															order by ARQCODE", "Centra", 'TEST');
				}

				// echo "select REQSTBY from APPROVAL_REQUEST where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P') order by ARQCODE";
				$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section

				$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($emp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$todesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$emp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
				$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section
			} else {
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apfrwrdusr, "Centra", 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section
			}
		}

		$frwrdemp_g = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$_SESSION['tcs_user'], "Centra", 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($frwrdemp_g[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}

		// print_r($frwrdemp); echo "<br>***".$_REQUEST['hid_action']."*****".$frwrdemp[0]['EMPSRNO']."*****".$emp[0][0]."*****"; exit();
		$bydesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$_SESSION['tcs_descode'], "Centra", 'TCS'); // Req.By user designation
		$bysection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], "Centra", 'TCS'); // Req.By user section
		if(count($bysection) <= 0) {
			$bysection = select_query_json("Select ESENAME From new_empsection where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], "Centra", 'TCS'); // Req.To user section
		}

		/* Query for find the target balance */
		$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal,
													sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and
													non.brncode=brn.brncode and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and
													dep.depcode=".$slt_department_asset." and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');

		$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
											from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
											where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and
												non.brncode=brn.brncode and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and
												dep.depcode=".$slt_department_asset." and decode(nvl(non.ptdesc,'-'),'-',dep.depname,non.ptdesc) = '".$slt_tardesc."'
											group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		if(count($sql_targetno) <= 0) {
			$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and
													non.brncode=brn.brncode and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and
													dep.depcode=".$slt_department_asset." and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		}

		if(count($sql_targetno) <= 0) {
			$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and
													non.brncode=brn.brncode and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and
													dep.depcode=".$slt_department_asset." and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		}
		$expname = select_query_json("select distinct round(tarnumb) tarnumb, ( select distinct decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
											from non_purchase_target tar, department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and
											dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) Depname
										from budget_planner_branch bpl
										where depcode=".$slt_department_asset." and brncode=".$slt_branch." and tarnumb=".$slt_targetno."
										order by Depname", "Centra", 'TCS');
		$group_wise = select_query_json("select EXPSRNO from department_asset where deleted = 'N' and depcode in (".$slt_department_asset.") order by expsrno", "Centra", 'TCS');
		/* Query for find the target balance */


		// echo "!!".$slt_submission."!!";
		if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
			// echo "@@".count($mnt_yr)."@@";
			if(count($mnt_yr) > 0) {
				// echo "##";

				if($slt_approval_listings != 807 and $slt_approval_listings != 777) {
					// Remove existing month value and move that value to current month
					$currnt_mnth = ltrim(date("m,Y"), 0);
					if($txt_tmporlive == 0) {
						$tbl_appplan = "approval_budget_planner_temp";
					} else {
						$tbl_appplan = "approval_budget_planner";
					}

					$sql_ext_curr_month = select_query_json("select APRSRNO, APPRVAL from ".$tbl_appplan."
																	where aprnumb like '".$txt_approval_number."' and APRPRID = '".$currnt_mnth."' order by aprsrno", "Centra", 'TEST');
					$sql_ext_curr_month_all = select_query_json("select * from ".$tbl_appplan."
																	where aprnumb like '".$txt_approval_number."' and aprsrno <= '".$sql_ext_curr_month[0]['APRSRNO']."'
																	order by aprsrno", "Centra", 'TEST');
					$aprvl = 0; $resvl = 0; $extvl = 0;
					for($vi = 0; $vi < count($sql_ext_curr_month_all); $vi++) {
						$aprvl += $sql_ext_curr_month_all[$vi]['APPRVAL'];
						$resvl += $sql_ext_curr_month_all[$vi]['RESVALU'];
						$extvl += $sql_ext_curr_month_all[$vi]['EXTVALU'];

						$field_appplan = array();
						$field_appplan['APPRVAL'] = 0;
						$field_appplan['RESVALU'] = 0;
						$field_appplan['EXTVALU'] = 0;
						// print_r($field_appplan);
						$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno." and APRSRNO = '".$sql_ext_curr_month_all[$vi]['APRSRNO']."'";
						$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
					}

					// echo "**".$tbl_appplan."**";
					$field_appplan = array();
					$field_appplan['APPRVAL'] = $aprvl;
					$field_appplan['RESVALU'] = $resvl;
					$field_appplan['EXTVALU'] = $extvl;
					// print_r($field_appplan);
					$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno." and APRSRNO = '".$sql_ext_curr_month[0]['APRSRNO']."'";
					$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
					// exit;
					// Remove existing month value and move that value to current month
				}

				// echo "<br>**".$cntmntyr."**";
				for($cntmntyr = 0; $cntmntyr < count($mnt_yr); $cntmntyr++) {
					// This is used Verify the current month and previous month
					$exp1 = explode(",", $mnt_yr[$cntmntyr]);
					$lastmonth = date("01-".$exp1[0]."-".$exp1[1]);
					$crntmonth = date("01-m-Y");
					$different = strtotime($crntmonth) - strtotime($lastmonth);

					// echo "<br>++".$mnt_yr_amt[$cntmntyr]."++".$mnt_yr_amt1[$cntmntyr]."++".$mnt_yr_amt[$cntmntyr]."++";
					// This is for "IF ANY CHANGES OCCURS IN APPROVAL VALUE", it will update
					if($mnt_yr_amt[$cntmntyr] != $mnt_yr_amt1[$cntmntyr] and $mnt_yr_amt[$cntmntyr] > 0) {
						if($txt_tmporlive == 0) {
							$tbl_appplan = "approval_budget_planner_temp";
						} else {
							$tbl_appplan = "approval_budget_planner";
						}
						// echo "<br>|".$tbl_appplan;
						$field_appplan = array();
						if($slt_submission == 1) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
						} elseif($slt_submission == 6) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
							$field_appplan['RESVALU'] = $mnt_yr_amt1[$cntmntyr];
						} elseif($slt_submission == 7) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
							$field_appplan['EXTVALU'] = $mnt_yr_amt1[$cntmntyr];
						}
						$field_appplan['EXISTVL'] = $mnt_yr_amt[$cntmntyr];
						$field_appplan['EDTUSER'] = $_SESSION['tcs_usrcode'];
						$field_appplan['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
						// print_r($field_appplan);
						$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno." and APRSRNO = '".$mnt_yraprsrno[$cntmntyr]."'";
						$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
					} elseif($txtrequest_value < $ttl_lock) {
						if($txt_tmporlive == 0) {
							$tbl_appplan = "approval_budget_planner_temp";
						} else {
							$tbl_appplan = "approval_budget_planner";
						}
						// echo "<br>|".$tbl_appplan;
						$field_appplan = array();
						if($slt_submission == 1 and $mnt_yr_amt1[$cntmntyr] > 0) {
							$field_appplan['APPRVAL'] = $txtrequest_value;
							$field_appplan['EXISTVL'] = $ttl_lock;
						} elseif($slt_submission == 6 and $mnt_yr_amt1[$cntmntyr] > 0) {
							$field_appplan['APPRVAL'] = $txtrequest_value;
							$field_appplan['RESVALU'] = $txtrequest_value;
							$field_appplan['EXISTVL'] = $ttl_lock;
						} elseif($slt_submission == 7 and $mnt_yr_amt1[$cntmntyr] > 0) {
							$field_appplan['APPRVAL'] = $txtrequest_value;
							$field_appplan['EXTVALU'] = $txtrequest_value;
							$field_appplan['EXISTVL'] = $ttl_lock;
						}
						$field_appplan['EDTUSER'] = $_SESSION['tcs_usrcode'];
						$field_appplan['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
						// print_r($field_appplan);
						$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno." and APRSRNO = '".$mnt_yraprsrno[$cntmntyr]."'";
						$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
					}
					// This is for "IF ANY CHANGES OCCURS IN APPROVAL VALUE", it will update
				}
			}
		}
		// exit;

		// Insert in APPROVAL_REQUEST Table
		$tbl_appreq = "APPROVAL_REQUEST";
		$field_appreq = array();
		$field_appreq['ARQPCOD'] = $hid_arqpcod;
		$field_appreq['ARQCODE'] = $hid_reqid;
		// $field_appreq['ARQYEAR'] = $current_year[0]['PORYEAR'];
		$field_appreq['ARQYEAR'] = $hid_year;
		$field_appreq['ARQSRNO'] = $maxarqcode[0]['MAXARQSRNO'];
		$field_appreq['ATYCODE'] = $slt_submission;
		$field_appreq['ATMCODE'] = $slt_subtype;
		$field_appreq['APMCODE'] = $slt_approval_listings;
		$field_appreq['ATCCODE'] = $slt_topcore;
		$field_appreq['APPRFOR'] = $slt_submitfor;
		$field_appreq['REQSTTO'] = $txt_kind_attn;
		/* $field_appreq['APPRSUB'] = strtoupper($txtsubject);
		$field_appreq['APPRDET'] = strtoupper($txtdetails); */

		// Detail Content generate in a txt file
		$txtdetails++;
		$description = $_REQUEST['FCKeditor1'];
		$lpdyear = $hid_year;
		$txt_srcfilename = "apd_".$hid_year."_".$slt_aprno."_".$txtdetails.".txt";

		$local_file = "uploads/text_approval_source/".$txt_srcfilename;
		$myfile = fopen($local_file, "w");
		fwrite($myfile, $description);
		fclose($myfile);

		$server_file = 'approval_desk/text_approval_source/'.$lpdyear.'/'.$txt_srcfilename;
		if ((!$conn_id) || (!$login_result)) {
			$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
			unlink($local_file);
		}
		// Detail Content generate in a txt file
		// exit;

		$field_appreq['APPRSUB'] = str_replace("'", "", $lpdyear.'/'.$txt_srcfilename);
		$field_appreq['APPRDET'] = str_replace("'", "", $txtdetails);

		$field_appreq['APPRSFR'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtfrom_date2;
		$field_appreq['APPRSTO'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtto_date2;
		$field_appreq['APPATTN'] = $noofattachment;
		$field_appreq['APRQVAL'] = $txtrequest_value;
		$field_appreq['APPDVAL'] = $txtrequest_value;
		$field_appreq['APPFVAL'] = $txtrequest_value;

		if(count($target_balance) > 0 and $target_balance[0]['BRNCODE'] != '') { // echo "!!";
			$field_appreq['BRNCODE'] = $target_balance[0]['BRNCODE'];
			$field_appreq['DEPCODE'] = $target_balance[0]['DEPCODE'];
			$field_appreq['TARNUMB'] = $target_balance[0]['TARNUMBER'];
			$field_appreq['TARBALN'] = $target_balance[0]['BALRELEASE'];
			$field_appreq['TARDESC'] = $expname[0]['DEPNAME'];
		} else { // echo "@@";
			$field_appreq['BRNCODE'] = $slt_branch;
			$field_appreq['DEPCODE'] = $slt_department_asset;
			$field_appreq['TARNUMB'] = $slt_targetno;
			$field_appreq['TARBALN'] = $slt_tarbaln;
			$field_appreq['TARDESC'] = $slt_tardesc;
		}

		$field_appreq['REQSTBY'] = $_SESSION['tcs_empsrno'];
		$field_appreq['RQBYDES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
		$field_appreq['REQDESC'] = $_SESSION['tcs_descode'];
		$field_appreq['REQESEC'] = $_SESSION['tcs_esecode'];
		$field_appreq['REQDESN'] = $bydesignation[0]['DESNAME'];
		$field_appreq['REQESEN'] = $bysection[0]['ESENAME'];

		if($frwrdemp[0]['EMPSRNO'] == 452 and $slt_topcore == 2 and $_SESSION['tcs_empsrno'] != '168' and $txt_requestby != '168') { /// 07092017
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = 1062", "Centra", 'TCS'); // NSM sir added dynamically
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.forward user section

			$field_appreq['REQSTFR'] = $frwrdemp[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp[0]['EMPCODE']." - ".$frwrdemp[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection[0]['ESENAME'];
		} else {
			$field_appreq['REQSTFR'] = $frwrdemp[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp[0]['EMPCODE']." - ".$frwrdemp[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection[0]['ESENAME'];
		} /// 07092017

		$field_appreq['RQESTTO'] = $emp[0]['EMPSRNO'];
		$field_appreq['RQTODES'] = $emp[0]['EMPCODE']." - ".$emp[0]['EMPNAME'];
		$field_appreq['RQTODSC'] = $emp[0]['DESCODE'];
		$field_appreq['RQTOESC'] = $emp[0]['ESECODE'];
		$field_appreq['RQTODSN'] = $todesignation[0]['DESNAME'];
		$field_appreq['RQTOESN'] = $tosection[0]['ESENAME'];

		$field_appreq['APRNUMB'] = $txt_approval_number;
		///////////////////// $field_appreq['APPSTAT'] = 'W'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query; W - Waiting for Approval in Print format screen;
		//////**// $field_appreq['APPSTAT'] = 'W'; // 10702 - THANGADURAI 31/10/2017 - For Avoid Approve during Edit -> Update
		$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query; W - Waiting for Approval in Print format screen;
		$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
		$field_appreq['APPINTP'] = 'N'; // Y - Yes; N - No;
		$field_appreq['INTPDES'] = 0;
		$field_appreq['INTPDSC'] = 0; // This 1 is indicate us, this is coming from gpanel home screen; This 0 is indicate us, this is coming from direct approval screen; This 2 is indicate us, this is coming from print screen approval page
		$field_appreq['INTPESC'] = 0; // This 1 is indicate us, this approval is read by approval user
		if($sql_targetno[0]['TARNUMBER'] != '') {
			$field_appreq['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
		} else {
			$field_appreq['INTPDSN'] = 0;
		}
		$field_appreq['INTPESN'] = '-';

		$field_appreq['INTPAPR'] = '-';
		$field_appreq['INTSUGG'] = '-';
		$field_appreq['INTPFRD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$field_appreq['INTPTOD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$field_appreq['ADDUSER'] = $_SESSION['tcs_empsrno']; // $_SESSION['tcs_usrcode'];
		$field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$field_appreq['EDTUSER'] = '';
		$field_appreq['EDTDATE'] = '';
		$field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
		$field_appreq['DELUSER'] = $txt_submission_reqby;
		$field_appreq['DELDATE'] = '';

		$field_appreq['APRCODE'] = $slt_project;
		$field_appreq['APRHURS'] = $txtnoofhours;
		$field_appreq['APRDAYS'] = $txtnoofdays;
		$field_appreq['APRDUED'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtdue_date;
		$field_appreq['APPRMRK'] = strtoupper($txt_remarks);
		$field_appreq['APRTITL'] = $slt_title;

		$field_appreq['FINSTAT'] = 'N';
		$field_appreq['FINUSER'] = '';
		$field_appreq['FINCMNT'] = '';
		$field_appreq['FINDATE'] = '';
		$field_appreq['USRSYIP'] = $sysip;
		$field_appreq['PRJPRCS'] = $slt_project_type;
		$field_appreq['PRICODE'] = $slt_priority;

		$field_appreq['INTPEMP'] = 0; // REMOVE THIS
		$field_appreq['NXLVLUS'] = 1; // REMOVE THIS
		$subj = explode(" - ", $txtsubject);

		if($_REQUEST['hid_action'] == 'sbmt_response') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'S'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		if($_REQUEST['hid_action'] == 'sbmt_forward') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		// Current Year Record
		$cur_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal,
												decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per
											from budget_planner_branch bpl, non_sales_target non
											where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($cur,-2)."'
												and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
											group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL
											order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS');

		// Last Year Record
		$last_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal,
												decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per
											from budget_planner_branch bpl, non_sales_target non
											where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($lat,-2)."'
												and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
											group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL
											order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS');

		$field_appreq['TARVLCY'] = $cur_year[0]['BUDGETVAL'];
		$field_appreq['TARVLLY'] = $last_year[0]['BUDGETVAL'];
		$field_appreq['EXPNAME'] = $expname[0]['DEPNAME'];
		$field_appreq['TARPRCY'] = $cur_year[0]['PER'];
		$field_appreq['TARPRLY'] = $last_year[0]['PER'];
		$field_appreq['BUDTYPE'] = $slt_submission;
		// if($slt_submission == 6 or $slt_submission == 7) {
			$field_appreq['BUDCODE'] = $slt_budgetmode;
		// }

		// 27-12-2016 AK Sir Instruction
		$field_appreq['IMDUEDT'] = $impldue_date;
		$field_appreq['IMUSRCD'] = '';
		$field_appreq['IMSTATS'] = 'N';
		$field_appreq['IMFINDT'] = '';
		$field_appreq['IMUSRIP'] = $slt_aprno;
		$field_appreq['TYPMODE'] = 'AP';
		$field_appreq['SUBCORE'] = $slt_subcore;

		$txtsup = explode(" - ", $txt_suppliercode);
		if(is_numeric($txtsup[0])) {
			$supcd = $txtsup[0];
			$supnm = $txtsup[1];
		} else {
			$supcd = '';
			$supnm = $txt_suppliercode;
		}
		$field_appreq['SUPCODE'] = $supcd;
		$field_appreq['SUPNAME'] = strtoupper($supnm);
		$field_appreq['SUPCONT'] = $txt_supplier_contactno;
		// 27-12-2016 AK Sir Instruction

		// Alternate Users
		if($frwrdemp[0]['EMPSRNO'] == 61579) {
			$field_appreq['INTPEMP'] = '59006'; // Ranganathan
			// $field_appreq['INTPEMP'] = 48237; // SARATH
			// $field_appreq['INTPEMP'] = 63624; // HARI BALA KRISHNAN 17940 - spt 5 & 6 - 2017
			// $field_appreq['INTPEMP'] = 76856; // SELVAGANAPATHI
		} elseif($frwrdemp[0]['EMPSRNO'] == 2158) {
			$field_appreq['INTPEMP'] = '13613'; // Praveen alternate for Gunasekar
		} elseif($frwrdemp[0]['EMPSRNO'] == 34593) {
			$field_appreq['INTPEMP'] = '1169'; // HW Karthik alternate for Saravanakumar
		} elseif($frwrdemp[0]['EMPSRNO'] == 55641) {
			$field_appreq['INTPEMP'] = '37048'; // HR Sathish alternate for HR Senthil
		} elseif($frwrdemp[0]['EMPSRNO'] == 188) { // Ashok - S-team
			$field_appreq['INTPEMP'] = 62762; // Ramakrishnan - S-team
		} elseif($frwrdemp[0]['EMPSRNO'] == 200) { // prem - advt-team
			$field_appreq['INTPEMP'] = 23684; // bala - advt-team
		} elseif($frwrdemp[0]['EMPSRNO'] == 14180) { // Manoharan - Project-team
			$field_appreq['INTPEMP'] = 82237; // Dhinesh Khanna - Project-team
		}  elseif($frwrdemp[0]['EMPSRNO'] == 53864) { // Madhan - HR Dept
			$field_appreq['INTPEMP'] = 86464; // Nanthakumar - HR Dept
		}
		/* elseif($frwrdemp[0]['EMPSRNO'] == 23682) { // MDU - Parthiban - Rajesh
			$field_appreq['INTPEMP'] = 16999;
		}*/
		// Alternate Users

		// 23-08-2017 AK Sir Instruction
		$rqby = explode(" - ", $txt_submission_respuser);
		$rqbyusr = $rqby[0];

		$altusr = explode(" - ", $txt_alternate_user);
		$altrusr = $altusr[0];

		$field_appreq['PRODWIS'] = $txt_prodwise_budget;
		$field_appreq['RESPUSR'] = $rqbyusr;
		$field_appreq['ALTRUSR'] = $altrusr;
		$field_appreq['RELAPPR'] = strtoupper($txt_related_approvals);
		$field_appreq['AGNSAPR'] = strtoupper($txt_against_approval);
		$field_appreq['AGEXPDT'] = $txt_agreement_expiry;
		$field_appreq['AGADVAM'] = strtoupper($txt_agreement_advance);

		$field_appreq['ORGRECV'] = 'N';
		$field_appreq['ORGRVUS'] = '';
		$field_appreq['ORGRVDT'] = '';
		$field_appreq['ORGRVDC'] = '';
		$field_appreq['CNVRMOD'] = strtoupper($slt_convertmode);
		$field_appreq['APPTYPE'] = strtoupper($slt_apptype);
		// $field_appreq['ADVAMNT'] = $txt_adv_amount;
		// 23-08-2017 AK Sir Instruction

		$field_appreq['PURHEAD'] = $txt_purhead;
		$exp_wrkinusr = explode(" - ", $txt_workintiator);
		$field_appreq['WRKINUSR'] = $exp_wrkinusr[0];
		if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
			$field_appreq['BDPLANR'] = $slt_fixbudget_planner;
		} else {
			$field_appreq['BDPLANR'] = '';
		}

		// Attachments
		$field_appreq['RMQUOTS'] = strtoupper($txt_submission_quotations_remarks);
		$field_appreq['RMBDAPR'] = strtoupper($txt_submission_fieldimpl_remarks);
		$field_appreq['RMCLRPT'] = strtoupper($txt_submission_clrphoto_remarks);
		$field_appreq['RMARTWK'] = strtoupper($txt_submission_artwork_remarks);
		$field_appreq['RMCONAR'] = strtoupper($txt_submission_othersupdocs_remarks);

		$field_appreq['WARQUAR'] = strtoupper($txt_warranty_guarantee);
		$field_appreq['CRCLSTK'] = strtoupper($txt_cur_clos_stock);
		$field_appreq['PAYPERC'] = strtoupper($txt_advpay_comperc);
		$field_appreq['FNTARDT'] = strtoupper($txt_workfin_targetdt);
		// Attachments

		// echo "<pre>";
		// exit();

		print_r($field_appreq); echo "<br>";
		if($hid_samearqsrno == 0) { // echo "UPD";
			$where_appreq = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('W') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$insert_appreq = update_dbquery($field_appreq, $tbl_appreq, $where_appreq);
		} else { // echo "INS";
			$insert_appreq = insert_test_dbquery($field_appreq, $tbl_appreq);
		}
		echo "!!!".$insert_appreq."@@@<pre>";
		// Insert in APPROVAL_REQUEST Table
		// exit;

		// Update in APPROVAL_REQUEST Table ARQSRNO = 1
		$tbl_apprq1 = "APPROVAL_REQUEST";
		$field_apprq1 = array();
		$field_apprq1['APPFVAL'] = $txtrequest_value;
		print_r($field_apprq1); echo "<br>";
		echo $where_apprq1 = "APRNUMB = '".$txt_approval_number."' and ARQYEAR = '".$hid_year."' ";
		$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
		echo "!!!".$update_apprq1."@@@<pre>";
		// Update in APPROVAL_REQUEST Table ARQSRNO = 1


		$addiv_return = 8;
		if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and $_REQUEST['hid_action'] == 'sbmt_forward' and $insert_appreq == 1 and $frwrdemp[0]['EMPSRNO'] == 21344 and $_SESSION['tcs_empsrno'] != 168) {
			/* $sql_akiv = select_query("select COUNT(*) CNT from APPROVAL_request where aprnumb = '".$txt_approval_number."' and ARQSRNO=".$maxarqcode[0]['MAXARQSRNO']." AND (REQSTFR = 21344 and REQSTBY <> 168)");
			if(count($sql_akiv) > 0) {
				$tbl_akiv = "APPROVAL_request";
				$field_akiv = array(
				$field_akiv['APPSTAT'] = 'F';
				// print_r($field_akiv);
				$where_akiv = " APRNUMB='".$txt_approval_number."' and ARQSRNO=".$maxarqcode[0]['MAXARQSRNO']." ";
				$insert_akiv = update_dbquery($field_akiv, $tbl_akiv, $where_akiv);

				// MR. AK Sir User - 21344
				$fstusr = select_query("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empsrno = 21344"); // MR. AK Sir User - 21344
				$fstusrdes = "designation"; $fstusrsec = "empsection";
				if($fstusr[0]['PAYCOMPANY'] == 2) {
					$fstusrdes = "new_designation"; $fstusrsec = "new_empsection";
				}
				$fstusrdesignation = select_query("Select DESNAME From ".$fstusrdes." where DESCODE = ".$fstusr[0][6]); // Req.forward user designation
				$fstusrsection = select_query("Select ESENAME From ".$fstusrsec." where deleted = 'N' and ESECODE = ".$fstusr[0][5]); // Req.forward user section
				// MR. AK Sir User - 21344

				// MR. Ashok Sir User - 168
				$fstusr1 = select_query("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empsrno = 168"); // MR. Ashok Sir User - 168
				$fstusrdes1 = "designation"; $fstusrsec1 = "empsection";
				if($fstusr1[0]['PAYCOMPANY'] == 2) {
					$fstusrdes1 = "new_designation"; $fstusrsec1 = "new_empsection";
				}
				$fstusrdesignation1 = select_query("Select DESNAME From ".$fstusrdes1." where DESCODE = ".$fstusr1[0][6]); // Req.forward user designation
				$fstusrsection1 = select_query("Select ESENAME From ".$fstusrsec1." where deleted = 'N' and ESECODE = ".$fstusr1[0][5]); // Req.forward user section
				// MR. Ashok Sir User - 168

				echo "++++++".delete_dbquery("INSERT INTO APPROVAL_request select ARQCODE,ARQYEAR,(".$maxarqcode[0]['MAXARQSRNO'].")+1,ATYCODE,ATMCODE,APMCODE,ATCCODE,APPRFOR,REQSTTO,APPRSUB,APPRDET,APPRSFR,APPRSTO,APPATTN,APRQVAL,APPDVAL,APPFVAL,BRNCODE,DEPCODE,TARNUMB,TARBALN,TARDESC,'".$fstusr[0][0]."','".$fstusr[0][2]." - ".$fstusr[0][3]."','".$fstusr[0][6]."','".$fstusr[0][5]."','".$fstusrdesignation[0][0]."','".$fstusrsection[0][0]."','".$fstusr1[0][0]."','".$fstusr1[0][2]." - ".$fstusr1[0][3]."','".$fstusr1[0][6]."','".$fstusr1[0][5]."','".$fstusrdesignation1[0][0]."','".$fstusrsection1[0][0]."',RQESTTO,RQTODES,RQTODSC,RQTOESC,RQTODSN,RQTOESN,APRNUMB,'N','I',APPINTP,INTPEMP,INTPDES,INTPDSC,INTPESC,INTPDSN,INTPESN,INTPAPR,INTSUGG,INTPFRD,INTPTOD,'".$fstusr[0][0]."',ADDDATE,EDTUSER,EDTDATE,DELETED,'".$fstusr[0][0]."',DELDATE,APRCODE,APRHURS,APRDAYS,APRDUED,APPRMRK,APRTITL,FINSTAT,FINUSER,FINCMNT,FINDATE,TARVLCY,TARVLLY,EXPNAME,TARPRCY,TARPRLY,USRSYIP,PRJPRCS,PLANVAL,IMDUEDT,IMUSRCD,IMSTATS,IMFINDT,IMUSRIP,TYPMODE,SUBCORE,BUDTYPE,BUDCODE,IMFNIMG,NXLVLUS,PRICODE,'','','' from APPROVAL_request where APRNUMB = '".$txt_approval_number."' and ARQSRNO=".$maxarqcode[0]['MAXARQSRNO'], "Centra", 'TEST');
			} */
			// echo "WRONG";
			$addiv_return = add_ivuser(21344, 168, $txt_approval_number, $maxarqcode[0]['MAXARQSRNO']); // MR. AK Sir User - 21344 & MR. NSM Sir User - 168
		}

		// $insert_appreq = 1;
		if($insert_appreq == 1) {
			// Product List Adding
			for($pdi = 0; $pdi < count($txt_prdcode); $pdi++) {
				$tbl_appdet = "APPROVAL_PRODUCTLIST";
				$field_appdet = array();
				$field_appdet['TOTLQTY'] = $txt_prdqty[$pdi];
				$field_appdet['TOTLVAL'] = 0;
				// echo "<br>IN".$pdi."IN"; print_r($field_appdet); echo "<br>";
				$where_appreq = "PBDCODE = '".$hid_reqqid."' and PBDYEAR = '".$hid_year."' and PBDLSNO = '".$txt_pbdlsno[$pdi]."'";
				$insert_appdet = update_dbquery($field_appdet, $tbl_appdet, $where_appreq);
				// exit;

				// print_r($txt_sltsupplier);
				$pdii = $pdi + 1;
				$qdii = 1;
				// Product List - Quotation Adding
				for($qdi = 0; $qdi < count($txt_sltsupcode[$pdii]); $qdi++) { // echo "***".$txt_sltsupplier[$pdii][$qdi]."***";
					$tbl_appdet1 = "APPROVAL_PRODUCT_QUOTATION";
					$field_appdet1 = array();
					if($txt_sltsupplier[$pdii][0] == $qdii) {
						$field_appdet1['SLTSUPP'] = 1;
					} else {
						$field_appdet1['SLTSUPP'] = 0;
					}
					$qdii++;
					$field_appdet1['PRDRATE'] = $txt_prdrate[$pdii][$qdi];
					$field_appdet1['SGSTVAL'] = $txt_prdsgst[$pdii][$qdi];
					$field_appdet1['CGSTVAL'] = $txt_prdcgst[$pdii][$qdi];
					$field_appdet1['IGSTVAL'] = $txt_prdigst[$pdii][$qdi];
					$field_appdet1['DISCONT'] = $txt_prddisc[$pdii][$qdi];
					$field_appdet1['NETAMNT'] = $hid_prdnetamount[$pdii][$qdi];
					$field_appdet1['SUPRMRK'] = $txt_suprmrk[$pdii][$qdi];
					$field_appdet1['ADVAMNT'] = $txt_advance_amount[$pdii][$qdi];
					// $field_appdet1['NETAMNT'] = 0;

					// print_r($field_appdet1); echo "<br>"; // exit;
					$where_appreq1 = "PBDCODE = '".$hid_reqqid."' and PBDYEAR = '".$hid_year."' and PBDLSNO = '".$txt_pbdlsno[$pdi]."' and PRLSTSR = '".$txt_prlstsr[$pdii][$qdi]."'";
					$insert_appdet1 = update_dbquery($field_appdet1, $tbl_appdet1, $where_appreq1);
				}
				// Product List - Quotation Adding
			}
			// Product List Adding
		}
		// exit;

		if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
			if(count($mnt_yr) > 0) {
				for($cntmntyr = 0; $cntmntyr < count($mnt_yr); $cntmntyr++) {

					// This is used Verify the current month and previous month
					$exp1 = explode(",", $mnt_yr[$cntmntyr]);
					$lastmonth = date("01-".$exp1[0]."-".$exp1[1]);
					$crntmonth = date("01-m-Y");
					$different = strtotime($crntmonth) - strtotime($lastmonth);
					echo "<br>****".$different."****".$mnt_yr_amt[$cntmntyr]."*****<br>";

					// This is for "IF ANY CHANGES OCCURS IN APPROVAL VALUE", it will update
					if($mnt_yr_amt[$cntmntyr] != $mnt_yr_amt1[$cntmntyr] and $mnt_yr_amt[$cntmntyr] > 0) {
						if($txt_tmporlive == 0) {
							$tbl_appplan = "approval_budget_planner_temp";
						} else {
							$tbl_appplan = "approval_budget_planner";
						}

						$field_appplan = array();
						if($slt_submission == 1) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
						} elseif($slt_submission == 6) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
							$field_appplan['RESVALU'] = $mnt_yr_amt1[$cntmntyr];
						} elseif($slt_submission == 7) {
							$field_appplan['APPRVAL'] = $mnt_yr_amt1[$cntmntyr];
							$field_appplan['EXTVALU'] = $mnt_yr_amt1[$cntmntyr];
						}
						$field_appplan['EXISTVL'] = $mnt_yr_amt[$cntmntyr];
						$field_appplan['EDTUSER'] = $_SESSION['tcs_usrcode'];
						$field_appplan['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
						// print_r($field_appplan);
						$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno." and APRSRNO = '".$mnt_yraprsrno[$cntmntyr]."'";
						$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
					}
					// This is for "IF ANY CHANGES OCCURS IN APPROVAL VALUE", it will update
				}
			}
		}
		exit;

	// Move the Temp Table to Live Table - PKN Login
	if($_SESSION['tcs_empsrno'] == 61579 and $txt_extarno == $slt_targetno) { // echo "STEP1";
		// Step 1 : Move the Temp Table records to LIVE Table
		$ivqry = delete_dbquery("INSERT INTO approval_budget_planner select APRNUMB,APRSRNO,APRPRID,APRMNTH,APPRVAL,APPMNTH,APPYEAR,TARNUMB,RESVALU,EXTVALU,BUDMODE,APRYEAR,ADDUSER,ADDDATE,'','','N','','',BRNCODE,APPMODE,EXPSRNO,'0','',DEPCODE from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."' and DELETED = 'N'", "Centra", 'TEST');

		// Step 2 : Deleted the Temp Table records
		$tbl_appplan = "approval_budget_planner_temp";
		$field_appplan = array();
		$field_appplan['DELETED'] = 'Y';
		$field_appplan['DELUSER'] = $_SESSION['tcs_usrcode'];
		$field_appplan['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		// print_r($field_appplan);
		$where_appplan = " APRNUMB='".$txt_approval_number."' and DELETED = 'N' ";
		$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
	} elseif($_SESSION['tcs_empsrno'] == 61579 and $txt_extarno != $slt_targetno and $tarnochang_allowornot == 1) { // echo "STEP2";


			// Step 2 : Move the Temp Table records to LIVE Table
			$ivqry = delete_dbquery("INSERT INTO approval_budget_planner select APRNUMB,APRSRNO,APRPRID,APRMNTH,APPRVAL,APPMNTH,APPYEAR,TARNUMB,RESVALU,EXTVALU,BUDMODE,APRYEAR,ADDUSER,ADDDATE,'','','N','','',BRNCODE,APPMODE,EXPSRNO,'0','',DEPCODE from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."' and DELETED = 'N'", "Centra", 'TEST');

			// Step 3 : Deleted the Temp Table records
			$tbl_appplan = "approval_budget_planner_temp";
			$field_appplan = array();
			$field_appplan['DELETED'] = 'Y';
			$field_appplan['DELUSER'] = $_SESSION['tcs_usrcode'];
			$field_appplan['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			// print_r($field_appplan);
			$where_appplan = " APRNUMB='".$txt_approval_number."' and DELETED = 'N' ";
			$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
	}
	// Move the Temp Table to Live Table - PKN Login




		$fm = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_fieldimpl_", 'i', 'fieldimpl');
		$ot = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_othersupdocs_", 'i', 'othersupdocs');
		$qu = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_", 'i', 'quotations');
		$cl = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_clrphoto_", 'i', 'clrphoto');
		$la = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_lastapproval_", 'i', 'lastapproval');
		$aw = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$current_year[0]['PORYEAR']."_artwork_", 'i', 'artwork');

		$maxarqcode[0]['MAXARQCODE'] = $hid_reqid;
		$slt_submission = $hid_slt_submission;
		$slt_topcore = $hid_slt_topcore;

		// fieldimpl
		for($i=0; $i<count($assign); $i++)
		{
			if($assign[$i] != '')
			{
				$fldimli = '-'; $complogos1 = '-';
				if($_FILES['txt_submission_fieldimpl']['type'][$i] != '') {
					$fldimli = find_indicator( $_FILES['txt_submission_fieldimpl']['type'][$i] );

					$imgfile1 = $_FILES['txt_submission_fieldimpl']['tmp_name'][$i];
					if($fldimli == 'i')
					{
						$info = getimagesize($imgfile1);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
						//save it
						imagejpeg($image, $imgfile1, 20);
					}

					$extn1 = '';
					switch($_FILES['txt_submission_fieldimpl']['type'][$i]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn1 = 'jpg';
								break;
						case 'application/pdf':
								$extn1 = 'pdf';
								break;
						default:
								$extn1 = 'pdf';
								break;
					}

					//$upload_img1 = $_FILES['txt_submission_fieldimpl']['name'];
					$expl = explode(".", $_FILES['txt_submission_fieldimpl']['name'][$i]);
					$upload_img1 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_fieldimpl_".$fldimli."_".($fm + $i).".".$extn1;
					$source = $imgfile1;
					$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
					$complogos1 = str_replace(" ", "-", $upload_img1);
					$complogos1 = strtolower($complogos1);

					//// Thumb start
					if($fldimli == 'i')
					{
						$upload_img1_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_fieldimpl_".$fldimli."_".($fm + $i).".jpg";
						$source_tmp = $imgfile1;
						$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
						$complogos1_tmp = strtolower($complogos1_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos1_tmp;
						$dest_thumbfile = "approval_desk/request_entry/fieldimpl/thumb_images/".$complogos1_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image1);
						imagedestroy($tmp1);
						//echo "^^^".$complogos1_tmp.'<br>';
						move_uploaded_file($source_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos1_tmp;
						$server_file = 'approval_desk/request_entry/fieldimpl/thumb_images/'.$complogos1;
					}
					//// Thumb end

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos1;
					$field_docs['APRHEAD'] = 'fieldimpl';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents
					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "tmp Succes";
						unlink($local_file);
					}

					$original_complogos1 = "uploads/request_entry/fieldimpl/".$complogos1;
					//echo '!!!'.$complogos1.'<br>';
					move_uploaded_file($source, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/fieldimpl/".$complogos1;
					$server_file = 'approval_desk/request_entry/fieldimpl/'.$complogos1;

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// fieldimpl

		// othersupdocs
		for($i=0; $i<count($assign0); $i++)
		{
			if($assign0[$i] != '')
			{
				$fldimli = '-'; $complogos1 = '-';
				if($_FILES['txt_submission_othersupdocs']['type'][$i] != '') {
					$fldimli = find_indicator( $_FILES['txt_submission_othersupdocs']['type'][$i] );

					$imgfile1 = $_FILES['txt_submission_othersupdocs']['tmp_name'][$i];
					if($fldimli == 'i')
					{
						$info = getimagesize($imgfile1);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
						//save it
						imagejpeg($image, $imgfile1, 20);
					}

					$extn1 = '';
					switch($_FILES['txt_submission_othersupdocs']['type'][$i]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn1 = 'jpg';
								break;
						case 'application/pdf':
								$extn1 = 'pdf';
								break;
						default:
								$extn1 = 'pdf';
								break;
					}

					//$upload_img1 = $_FILES['txt_submission_othersupdocs']['name'];
					$expl = explode(".", $_FILES['txt_submission_othersupdocs']['name'][$i]);
					$upload_img1 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_othersupdocs_".$fldimli."_".($ot + $i).".".$extn1;
					$source = $imgfile1;
					$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
					$complogos1 = str_replace(" ", "-", $upload_img1);
					$complogos1 = strtolower($complogos1);

					//// Thumb start
					if($fldimli == 'i')
					{
						$upload_img1_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_othersupdocs_".$fldimli."_".($ot + $i).".jpg";
						$source_tmp = $imgfile1;
						$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
						$complogos1_tmp = strtolower($complogos1_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos1_tmp;
						$dest_thumbfile = "approval_desk/request_entry/othersupdocs/thumb_images/".$complogos1_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image1);
						imagedestroy($tmp1);
						//echo "^^^".$complogos1_tmp.'<br>';
						move_uploaded_file($source_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos1_tmp;
						$server_file = 'approval_desk/request_entry/othersupdocs/thumb_images/'.$complogos1;
					}
					//// Thumb end

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos1;
					$field_docs['APRHEAD'] = 'othersupdocs';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents
					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "tmp Succes";
						unlink($local_file);
					}

					$original_complogos1 = "uploads/request_entry/othersupdocs/".$complogos1;
					//echo '!!!'.$complogos1.'<br>';
					move_uploaded_file($source, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/othersupdocs/".$complogos1;
					$server_file = 'approval_desk/request_entry/othersupdocs/'.$complogos1;

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// othersupdocs

		// quotations
		for($i1=0; $i1<count($assign1); $i1++)
		{
			if($assign1[$i1] != '')
			{
				$qutat1i = '-'; $complogos2 = '-';
				if($_FILES['txt_submission_quotations']['type'][$i1] != '') {
					$qutat1i = find_indicator( $_FILES['txt_submission_quotations']['type'][$i1] );

					$imgfile2 = $_FILES['txt_submission_quotations']['tmp_name'][$i1];
					if($qutat1i == 'i')
					{
						$info = getimagesize($imgfile2);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile2);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile2);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile2);
						//save it
						imagejpeg($image, $imgfile2, 20);
					}

					$extn2 = '';
					switch($_FILES['txt_submission_quotations']['type'][$i1]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn2 = 'jpg';
								break;
						case 'application/pdf':
								$extn2 = 'pdf';
								break;
						default:
								$extn2 = 'pdf';
								break;
					}

					//$upload_img1 = $_FILES['txt_submission_fieldimpl']['name'];
					$expl = explode(".", $_FILES['txt_submission_quotations']['name'][$i1]);

					$upload_img2 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".($qu + $i1).".".$extn2;
					$source2 = $imgfile2;
					$complogos2 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2); //str_replace(" ", "_", $upload_img2));
					$complogos2 = str_replace(" ", "-", $upload_img2);
					$complogos2 = strtolower($complogos2);

					//// Thumb start
					if($qutat1i == 'i')
					{
						$upload_img2_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".($qu + $i1).".jpg";
						$source2_tmp = $imgfile2;
						$complogos2_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos2_tmp = str_replace(" ", "-", $upload_img2_tmp);
						$complogos2_tmp = strtolower($complogos2_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image2,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos2_tmp;
						$dest_thumbfile = "approval_desk/request_entry/quotations/thumb_images/".$complogos2_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image2);
						imagedestroy($tmp1);
						//echo "^^^".$complogos2_tmp.'<br>';
						move_uploaded_file($source2_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos2_tmp;
						$server_file = 'approval_desk/request_entry/quotations/thumb_images/'.$complogos2_tmp;

						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
										//echo "tmp Succes";
							unlink($local_file);
						}
					}
					//// Thumb end

					$original_complogos1 = "uploads/request_entry/quotations/".$complogos2;
					//echo '!!!'.$complogos2.'<br>';
					move_uploaded_file($source2, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/quotations/".$complogos2;
					$server_file = 'approval_desk/request_entry/quotations/'.$complogos2;

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos2;
					$field_docs['APRHEAD'] = 'quotations';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// quotations


		// clrphoto
		for($i2=0; $i2<count($assign2); $i2++)
		{
			if($assign2[$i2] != '')
			{
				$smplpti = '-'; $complogos7 = '-';
				if($_FILES['txt_submission_clrphoto']['type'][$i2] != '') {
					$smplpti = find_indicator( $_FILES['txt_submission_clrphoto']['type'][$i2] );

					$imgfile7 = $_FILES['txt_submission_clrphoto']['tmp_name'][$i2];
					if($smplpti == 'i')
					{
						$info = getimagesize($imgfile7);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile7);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile7);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile7);
						//save it
						imagejpeg($image, $imgfile7, 20);
					}

					$extn3 = '';
					switch($_FILES['txt_submission_clrphoto']['type'][$i2]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn3 = 'jpg';
								break;
						case 'application/pdf':
								$extn3 = 'pdf';
								break;
						default:
								$extn3 = 'pdf';
								break;
					}

					$expl = explode(".", $_FILES['txt_submission_clrphoto']['name'][$i2]);
					$upload_img7 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_clrphoto_".$smplpti."_".($cl + $i2).".".$extn3;
					$source7 = $imgfile7;
					$complogos7 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img7); //str_replace(" ", "_", $upload_img2));
					$complogos7 = str_replace(" ", "-", $upload_img7);
					$complogos7 = strtolower($complogos7);


					//// Thumb start
					if($smplpti == 'i')
					{
						$upload_img7_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_clrphoto_".$smplpti."_".($cl + $i2).".jpg";
						$source7_tmp = $imgfile7;
						$complogos7_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img7_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos7_tmp = str_replace(" ", "-", $upload_img7_tmp);
						$complogos7_tmp = strtolower($complogos7_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image7,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos7_tmp;
						$dest_thumbfile = "approval_desk/request_entry/clrphoto/thumb_images/".$complogos7_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image7);
						imagedestroy($tmp1);
						//echo "^^^".$complogos7_tmp.'<br>';
						move_uploaded_file($source7_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos7_tmp;
						$server_file = 'approval_desk/request_entry/clrphoto/thumb_images/'.$complogos7_tmp;

						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
										//echo "tmp Succes";
							unlink($local_file);
						}
					}
					//// Thumb end

					$original_complogos1 = "uploads/request_entry/clrphoto/".$complogos7;
					//echo '!!!'.$complogos7.'<br>';
					move_uploaded_file($source7, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/clrphoto/".$complogos7;
					$server_file = 'approval_desk/request_entry/clrphoto/'.$complogos7;

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos7;
					$field_docs['APRHEAD'] = 'clrphoto';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// clrphoto


		// lastapproval
		for($i3=0; $i3<count($assign3); $i3++)
		{
			if($assign3[$i3] != '')
			{
				$lstapri = '-'; $complogos8 = '-';
				if($_FILES['txt_submission_last_approval']['type'][$i3] != '') {
					$lstapri = find_indicator( $_FILES['txt_submission_last_approval']['type'][$i3] );

					$imgfile8 = $_FILES['txt_submission_last_approval']['tmp_name'][$i3];
					if($lstapri == 'i')
					{
						$info = getimagesize($imgfile8);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile8);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile8);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile8);
						//save it
						imagejpeg($image, $imgfile8, 20);
					}

					$extn4 = '';
					switch($_FILES['txt_submission_last_approval']['type'][$i3]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn4 = 'jpg';
								break;
						case 'application/pdf':
								$extn4 = 'pdf';
								break;
						default:
								$extn4 = 'pdf';
								break;
					}

					$expl = explode(".", $_FILES['txt_submission_last_approval']['name'][$i3]);

					$upload_img8 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_lastapproval_".$lstapri."_".($la + $i3).".".$extn4;
					$source8 = $imgfile8;
					$complogos8 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img8); //str_replace(" ", "_", $upload_img2));
					$complogos8 = str_replace(" ", "-", $upload_img8);
					$complogos8 = strtolower($complogos8);


					//// Thumb start
					if($lstapri == 'i')
					{
						$upload_img8_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_lastapproval_".$lstapri."_".($la + $i3).".jpg";
						$source8_tmp = $imgfile8;
						$complogos8_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img8_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos8_tmp = str_replace(" ", "-", $upload_img8_tmp);
						$complogos8_tmp = strtolower($complogos8_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image8,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos8_tmp;
						$dest_thumbfile = "approval_desk/request_entry/lastapproval/thumb_images/".$complogos8_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image8);
						imagedestroy($tmp1);
						//echo "^^^".$complogos8_tmp.'<br>';
						move_uploaded_file($source8_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos8_tmp;
						$server_file = 'approval_desk/request_entry/lastapproval/thumb_images/'.$complogos8_tmp;

						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
										//echo "tmp Succes";
							unlink($local_file);
						}
					}
					//// Thumb end

					$original_complogos1 = "uploads/request_entry/lastapproval/".$complogos8;
					//echo '!!!'.$complogos8.'<br>';
					move_uploaded_file($source8, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/lastapproval/".$complogos8;
					$server_file = 'approval_desk/request_entry/lastapproval/'.$complogos8;

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos8;
					$field_docs['APRHEAD'] = 'lastapproval';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// lastapproval


		// artwork
		for($i4=0; $i4<count($assign4); $i4++)
		{
			if($assign4[$i4] != '')
			{
				$lstapri = '-'; $complogos9 = '-';
				if($_FILES['txt_submission_artwork']['type'][$i4] != '') {
					$lstapri = find_indicator( $_FILES['txt_submission_artwork']['type'][$i4] );

					$imgfile9 = $_FILES['txt_submission_artwork']['tmp_name'][$i4];
					if($lstapri == 'i')
					{
						$info = getimagesize($imgfile9);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile9);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile9);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile9);
						//save it
						imagejpeg($image, $imgfile9, 20);
					}

					$extn4 = '';
					switch($_FILES['txt_submission_artwork']['type'][$i4]) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
								$extn4 = 'jpg';
								break;
						case 'application/pdf':
								$extn4 = 'pdf';
								break;
						default:
								$extn4 = 'pdf';
								break;
					}

					$expl = explode(".", $_FILES['txt_submission_artwork']['name'][$i4]);

					$upload_img9 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_artwork_".$lstapri."_".($aw + $i4).".".$extn4;
					$source9 = $imgfile9;
					$complogos9 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img9); //str_replace(" ", "_", $upload_img2));
					$complogos9 = str_replace(" ", "-", $upload_img9);
					$complogos9 = strtolower($complogos9);


					//// Thumb start
					if($lstapri == 'i')
					{
						$upload_img9_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_artwork_".$lstapri."_".($aw + $i4).".jpg";
						$source9_tmp = $imgfile9;
						$complogos9_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img9_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos9_tmp = str_replace(" ", "-", $upload_img9_tmp);
						$complogos9_tmp = strtolower($complogos9_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image9,0,0,0,0,$newwidth1,$newheight1,$width,$height);

						$resized_file = "uploaded_files/". $complogos9_tmp;
						$dest_thumbfile = "approval_desk/request_entry/artwork/thumb_images/".$complogos9_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image9);
						imagedestroy($tmp1);
						//echo "^^^".$complogos9_tmp.'<br>';
						move_uploaded_file($source9_tmp, $dest_thumbfile);
						$local_file = "uploaded_files/".$complogos9_tmp;
						$server_file = 'approval_desk/request_entry/artwork/thumb_images/'.$complogos9_tmp;

						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
										//echo "tmp Succes";
							unlink($local_file);
						}
					}
					//// Thumb end

					$original_complogos1 = "uploads/request_entry/artwork/".$complogos9;
					//echo '!!!'.$complogos9.'<br>';
					move_uploaded_file($source9, $original_complogos1);

					/* Upload into FTP */
					$local_file = "uploads/request_entry/artwork/".$complogos9;
					$server_file = 'approval_desk/request_entry/artwork/'.$complogos9;

					// Approval Documents
					$attch++;
					$tbl_docs = "APPROVAL_REQUEST_DOCS";
					$field_docs['APRNUMB'] = $txt_approval_number;
					$field_docs['APDCSRN'] = $attch;
					$field_docs['APRDOCS'] = $complogos9;
					$field_docs['APRHEAD'] = 'artwork';
					$insert_docs = insert_dbquery($field_docs, $tbl_docs);
					// print_r($field_docs);
					// Approval Documents

					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									//echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
			}
		}
		// artwork
	}

	if($insert_appreq == 1) { // exit();
		$addiv_return1 = 8;
		if($hid_int_verification == 'F' and $_REQUEST['hid_action'] == 'sbmt_forward' and $insert_appreq == 1 and $frwrdemp[0]['EMPSRNO'] == 21344 and $_SESSION['tcs_empsrno'] != 168 and $addiv_return == 0) {
			// echo "HAI";
			$addiv_return1 = add_ivuser(21344, 168, $txt_approval_number, $maxarqcode[0]['MAXARQSRNO']); // MR. AK Sir User - 21344 & MR. NSM Sir User - 168
		}
		// echo "RET";

		if($addiv_return1 == 0) {
			// echo "NO";
			// Update in APPROVAL_REQUEST Table
			$tbl_apprq = "APPROVAL_REQUEST";
			$field_apprq['APPSTAT'] = 'N';
			$where_apprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'F' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			// print_r($field_appreq); //echo "<br>";
			$update_apprq = update_dbquery($field_apprq, $tbl_apprq, $where_apprq);
			// echo "!!!".$update_apprq."@@@";
			// Update in APPROVAL_REQUEST Table
			$dl_mx = delete_dbquery("delete from approval_request where aprnumb = '".$txt_approval_number."' and ARQSRNO = '".$maxarqcode[0]['MAXARQSRNO']."'", "Centra", 'TEST'); ?>
				<script>window.location='<?=$rturl?>';</script>
			<?php exit;
		} // echo "HORN"; exit; ?>
			<script>window.location='<?=$next_urlpath?>';</script>
		<?php exit();
	} else {
		if($addiv_return == 0) {
			// echo "POP";
			$dl_mx = delete_dbquery("delete from approval_request where aprnumb = '".$txt_approval_number."' and ARQSRNO = '".$maxarqcode[0]['MAXARQSRNO']."'", "Centra", 'TEST');
		}
		// echo "FAIL"; exit;

		// Update in APPROVAL_REQUEST Table
		$tbl_apprq = "APPROVAL_REQUEST";
		$field_apprq['APPSTAT'] = 'N';
		$where_apprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'F' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
		//print_r($field_appreq); //echo "<br>";
		$update_apprq = update_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		//echo "!!!".$update_apprq."@@@";
		// Update in APPROVAL_REQUEST Table
		// exit(); ?>
			<script>window.location='<?=$rturl?>';</script>
		<?php exit();
	}
}


$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE,
                                        emp.EMPNAME, ast.EXPSRNO, ast.DEPNAME, (select ADDUSER from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1
                                        and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) addeduser, (select ARQYEAR from APPROVAL_REQUEST
                                        where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and ARQYEAR = req.ARQYEAR and deleted = 'N' and
                                        rownum <= 1) ARYR, (select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1 where e1.empsrno = r1.ADDUSER and
                                        r1.ARQCODE = req.ARQCODE and r1.ARQYEAR = req.ARQYEAR and r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and r1.deleted = 'N'
                                        and rownum <= 1) addedempuser, (select DELUSER from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and
                                        ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser, (select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r2,
                                        employee_office e2 where e2.empsrno = r2.DELUSER and r2.ARQCODE = req.ARQCODE and r2.ARQYEAR = req.ARQYEAR and r2.ARQSRNO = 1 and
                                        r2.ATCCODE = req.ATCCODE and r2.ATYCODE = req.ATYCODE and r2.deleted = 'N') deltempuser, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
                                        to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
                                        to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time
                                    from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast, employee_office emp
                                    where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and brn.DELETED = 'N'
                                    	and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K','T') and req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and
                                    	req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                    	req.ATYCODE = '".$_REQUEST['typeid']."' and (req.REQSTFR = '".$usrid."' or req.INTPEMP = '".$usrid."')
                                    order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TEST'); // req.ATMCODE = apm.ATMCODE and ---- for Approval Master

if(count($sql_reqid) <= 0) {
    $sql_reqid = select_query_json("select req.*, req.ARQCODE arcode, req.ARQSRNO arsrno, req.ATYCODE atcode, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME,
                                            regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, edl.EMPCODE, edl.EMPNAME, ast.DEPNAME, (select ADDUSER
                                            from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and
                                            rownum <= 1) addeduser, (select ARQYEAR from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and
                                            ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) ARYR, (select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1
                                            where e1.empsrno = r1.ADDUSER and r1.ARQCODE = req.ARQCODE and r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and
                                            r1.deleted = 'N' and rownum <= 1) addedempuser, (select DELUSER from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and
                                            ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM')
                                            APPRSFR_Time, to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
                                            to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                        	employee_office_deleted edl
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and brn.DELETED = 'N'
                                        	and ast.DELETED = 'N' and (edl.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T') and req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and
                                        	req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                        	req.ATYCODE = '".$_REQUEST['typeid']."' and (req.REQSTFR = '".$usrid."' or req.INTPEMP = '".$usrid."')
                                    union
                                        select req.*, req.ARQCODE arcode, req.ARQSRNO arsrno, req.ATYCODE atcode, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME,
                                            regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE, emp.EMPNAME, ast.DEPNAME, (select ADDUSER
                                            from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and
                                            rownum <= 1) addeduser, (select ARQYEAR from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and
                                            ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) ARYR, (select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1
                                            where e1.empsrno = r1.ADDUSER and r1.ARQCODE = req.ARQCODE and r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and
                                            r1.deleted = 'N' and rownum <= 1) addedempuser, (select DELUSER from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and
                                            ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM')
                                            APPRSFR_Time, to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
                                            to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                            employee_office emp
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and brn.DELETED = 'N'
                                        	and ast.DELETED = 'N' and (emp.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T')and req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and
                                        	req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                        	req.ATYCODE = '".$_REQUEST['typeid']."' and (req.REQSTFR = '".$usrid."' or req.INTPEMP = '".$usrid."')
                                        order by arcode, arsrno, atcode", "Centra", 'TEST'); // req.ATMCODE = apm.ATMCODE and ---- for Approval Master
}

if(count($sql_reqid) == 0) { ?>
	<script>alert('Already You have provided the remarks and status or you dont have rights to do this Operation'); window.location="<?=$rturl?>";</script>
<? exit();
}

if($_REQUEST['action'] == 'view')
{
    $title_tag = 'View';
}
elseif($_REQUEST['action'] == 'edit')
{
    $title_tag = 'Edit';
}
else {
    $title_tag = 'New';
}

$sql_vl = select_query_json("select distinct req.APRQVAL, max(req.arqsrno) mx, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET,
                                    req.DEPCODE, req.TARNUMB, req.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME, req.ADVAMNT
                                from APPROVAL_REQUEST req, department_asset ast
                                where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and req.DEPCODE = ast.DEPCODE and req.DELETED = 'N' and ast.DELETED = 'N'
                                group by req.APRQVAL, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE, req.TARNUMB,
                                    req.TARDESC, ast.EXPSRNO, ast.EXPNAME, ast.DEPNAME, req.ADVAMNT
                                order by mx desc", "Centra", 'TEST');

$sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'", "Centra", 'TEST');

$sql_tmporlive = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
                                        where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and but.aprsrno = 1", "Centra", 'TEST');
if(count($sql_tmporlive) > 0) {
    $rcrd = "approval_budget_planner";
} else {
    $rcrd = "approval_budget_planner_temp";
}

$appr_status = ''; $appr_clr = ''; $isshow = 0; $appr_class = ''; $appr_lblclass = '';
switch($sql_reqid[0]['APPSTAT'])
{
    case 'A':
        $appr_status = 'APPROVED';
        $appr_clr    = '#E3FDE8';
        $appr_class  = 'alert-success';
        $appr_lblclass  = 'label-success';
        $isshow = 1;
        break;
    case 'R':
        $appr_status = 'REJECTED';
        $appr_clr = '#F2DEDE';
        $appr_class  = 'alert-danger';
        $appr_lblclass  = 'label-danger';
        $isshow = 1;
        break;
    case 'P':
        $appr_status = 'PENDING';
        $appr_clr = '#FDF5E3';
        $appr_class  = 'alert-warning';
        $appr_lblclass  = 'label-warning';
        $isshow = 1;
        break;
    default:
        $appr_status = 'NOT YET APPROVED';
        $appr_clr = '#E3F1FD';
        $appr_class  = 'alert-info';
        $appr_lblclass  = 'label-info';
        $isshow = 1;
        break;
}

if($sql_reqid[0]['APPSTAT'] == 'A' or $sql_reqid[0]['APPSTAT'] == 'R') {
    $start_time = formatSeconds(strtotime($sql_vl[0]['ADDDATE']) - strtotime($sql_reqid[0]['ADDEDDATE']));
} else {
    $start_time = formatSeconds(strtotime('now') - strtotime($sql_reqid[0]['ADDEDDATE']));
}
$sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request
                                    where aprnumb like '".$sql_reqid[0]['APRNUMB']."' and appfrwd = 'I'
                                    order by arqsrno", "Centra", "TEST");
$duedate = 0;
switch ($sql_vl[0]['PRICODE']) {
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>View Approval :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<style type="text/css">
    .form-horizontal .control-label { padding-top: 0px !important; }
</style>
</head>
<body>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-top-fixed">

        <!-- START PAGE SIDEBAR -->
        <div class="page-sidebar">
            <!-- START X-NAVIGATION -->
            <? include 'lib/app_left_panel.php'; ?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li><a href="home.php">Dashboard</a></li>
                <li><a href="request_list.php">Approval Request List</a></li>
                <li class="active">View Request</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">

                <div class="row">
                    <div class="col-md-12">

                        <form class="form-horizontal" role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='request_entry' />
                        <div class="panel panel-default">
                            <div id="result"></div> <!-- Display the Process Status -->
                            <? $view = 0; if( $sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7 ) { $view = 1; } ?>

                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>View Request - <span class="highlight_redtitle"><?=$sql_reqid[0]['APRNUMB']?></span>
                                <input type='hidden' name='hid_aprnumb' id='hid_aprnumb' value='<?=$sql_reqid[0]['APRNUMB']?>'>
                                <input type='hidden' name='hid_appattn_cnt' id='hid_appattn_cnt' value='<?=$sql_reqid[0]['APPATTN']?>'></strong></h3>
                                <ul class="panel-controls">
                                    <li><span class="label label-info label-form" style="background-color:<?=$css_cls?>"><?=$sql_vl[0]['PRIORITY']?></span> <span class="label label-info label-form" style="background-color:<?=$css_clstime?>">Due Date : <?=$duedate?> Days & Process Date : <?=$start_time?> Days</span></li>
                                    <li class="label <?=$appr_lblclass?> label-form"><?=$appr_status?></li>
                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                </ul>
                            </div>
                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <!-- New -->
                                        <input type='hidden' name='slt_project_type' id='slt_project_type' value='R'>

                                        <input type='hidden' name='hidd_depcode' id='hidd_depcode' value=''>
                                        <input type='hidden' name='hidd_depname' id='hidd_depname' value=''>
                                        <input type='hidden' name='hidd_expsrno' id='hidd_expsrno' value=''>
                                        <input type='hidden' name='hidd_multireq' id='hidd_multireq' value='N'>
                                        <!-- New -->

                                        <input type='hidden' name='currentyr' id='currentyr' value='<?=$current_year[0]['PORYEAR']?>'>
                                        <input type='hidden' name='txt_purhead' id='txt_purhead' value='<?=$sql_reqid[0]['PURHEAD']?>'>
                                        <?  if($sql_reqid[0]['EXPSRNO'] != '') {
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '".$sql_reqid[0]['EXPSRNO']."'", "Centra", 'TCS');
                                            } else {
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '13'", "Centra", 'TCS');
                                            } ?>

                                        <div id='id_topcore' style="display: none;">
                                        <!-- Top Core -->
                                        <div class="form-group trbg"></div>
                                        <div class="tags_clear"></div>
                                        <!-- Top Core -->
                                        </div>

                                        <div id='id_subcore' style="display: none;">
                                        <!-- Sub Core -->
                                        <div class="form-group trbg"></div>
                                        <div class="tags_clear"></div>
                                        <!-- Sub Core -->
                                        </div>

                                        <? 	$expnam = '';
											if($accrights == 2) {
												$sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset where DELETED = 'N' and expsrno > 0 order by EXPNAME", "Centra", 'TCS'); ?>
												<input type='hidden' name='hid_slt_core_department' id='hid_slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
										<? 	} else {
												if($tmporlive == 0) {
													$sql_expplan = select_query_json("select ast.EXPNAME exphead from approval_budget_planner_temp but, department_asset ast
																							where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."'
																								and but.aprsrno = 1", "Centra", 'TCS');
													if(count($sql_expplan) == 0) {
														// $tmporlive = 1;
														$sql_expplan = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
																							where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."'
																								and but.aprsrno = 1", "Centra", 'TCS');
													}
												} else {
													$sql_expplan = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
																							where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."'
																								and but.aprsrno = 1", "Centra", 'TCS');
													if(count($sql_expplan) == 0) {
														// $tmporlive = 0;
														$sql_expplan = select_query_json("select ast.EXPNAME exphead from approval_budget_planner_temp but, department_asset ast
																							where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."'
																								and but.aprsrno = 1", "Centra", 'TCS');
													}
												}

											$expnam = $sql_expplan[0]['EXPHEAD'];
										} ?>

                                        <!-- Project -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Project <span style='color:red'>*</span></label>
                                            <div class="col-md-9">
                                                : <?=$sql_reqid[0]['APRNAME']?>
												<input type="hidden" name='slt_project' id='slt_project' value="<?=$sql_reqid[0]['APRCODE']?>">
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Project -->

                                        <!-- Type of Submission Type -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Type of Submission <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                : <? if((strtotime($sql_reqid[0]['APPRSFR']) <= strtotime('22-APR-18')) and $sql_reqid[0]['ATYNAME'] == 'NEW PROPOSAL')
                                                	 { $aptype_display = "EXTRA BUDGET"; }
									 				 else { $aptype_display = $sql_reqid[0]['ATYNAME']; } echo $aptype_display;  ?>
                                                <input type='hidden' name='hid_slt_core_department' id='hid_slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
                                                <input type='hidden' name='hid_slt_department_asset' id='hid_slt_department_asset' value='<?=$sql_reqid[0]['DEPCODE']?>'>
                                                <input type='hidden' name='hid_slt_targetno' id='hid_slt_targetno' value='<?=$sql_reqid[0]['TARNUMB']?>'>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Type of Submission Type -->

                                        <div id='id_branch'>
                                        <? if($view == 1) { ?>
                                            <!-- Expense Head -->
                                            <div class="form-group" style="display: none;">
                                                <label class="col-md-3 control-label">Expense Head <span style='color:red'>*</span></label>
                                                <div class="col-md-9 col-xs-12">
                                                    : <?=$sql_expplan[0]['EXPHEAD']?>
													<input type='hidden' name='slt_core_department' id='slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
                                                </div>
                                            </div>
                                            <div class="tags_clear"></div>
                                            <!-- Expense Head -->

                                            <div id='id_department'>
                                                <!-- Department -->
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-3 control-label">Department <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        : <?=$sql_reqid[0]['DEPNAME']?>
														<input type='hidden' name='slt_department_asset' id='slt_department_asset' value='<?=$sql_vl[0]['DEPCODE']?>'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Department -->
                                            </div>

                                            <div id='id_tarno'>
                                                <!-- Target No -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Target No <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        : <?=$sql_reqid[0]['TARNUMB']." - ".$sql_reqid[0]['TARDESC']?>
                                                		<input type='hidden' name='slt_targetnos' id='slt_targetnos' value='<?=$sql_reqid[0]['TARNUMB']?>'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Target No -->
                                            </div>
                                        <? } ?>
                                        </div>
                                        <div class="tags_clear"></div>

										<input type='hidden' name='txt_requestby' id='txt_requestby' value='<?=$sql_reqid[0]['REQSTBY']?>'>
										<input type='hidden' name='txt_requestfr' id='txt_requestfr' value='<?=$sql_reqid[0]['REQSTFR']?>'>
										<input type='hidden' name='txt_tmporlive' id='txt_tmporlive' value='<?=$tmporlive?>'>
										<input type='hidden' name='txt_extarno' id='txt_extarno' value='<?=$sql_reqid[0]['TARNUMB']?>'>

										<input type='hidden' name='slt_core_department' id='slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
										<input type="hidden" name='slt_targetno' id='slt_targetno' value="<?=$sql_reqid[0]['TARNUMB']?>">
										<input type='hidden' name='hidslt_core_department' id='hidslt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
										<input type='hidden' name='slt_aprno' id='slt_aprno' value='<?=$sql_reqid[0]['IMUSRIP']?>'>
										<input type='hidden' name='slt_approval_listings' id='slt_approval_listings' value='<?=$sql_reqid[0]['APMCODE']?>'>
										<input type='hidden' name='txt_dynamic_subject' id='txt_dynamic_subject' value='<?=$sql_reqid[0]['DYNSUBJ']?>'>
										<input type='hidden' name='txt_dynsubject' id='txt_dynsubject' value='<?=$sql_reqid[0]['TXTSUBJ']?>'>

										<input type='hidden' name='txt_approval_number' id='txt_approval_number' value='<?=$sql_reqid[0]['APRNUMB']?>'>
										<input type="hidden" name='slt_subtype' id='slt_subtype' value="<?=$sql_reqid[0]['ATMCODE']?>">
										<input type='hidden' name='slt_submitfor' id='slt_submitfor' value='<?=$sql_reqid[0]['APPRFOR']?>'>
										<input type="hidden" name='arqcode' id='arqcode' value='<?=$sql_reqid[0]['ARQCODE']?>'>
										<input type="hidden" name='atycode' id='atycode' value='<?=$sql_reqid[0]['ATYCODE']?>'>
										<input type="hidden" name='atccode' id='atccode' value='<?=$sql_reqid[0]['ATCCODE']?>'>
										<input type="hidden" name='arqyear' id='arqyear' value='<?=$sql_reqid[0]['ARYR']?>'>
										<input type='hidden' name='aprnumb' id='aprnumb' value='<?=$sql_reqid[0]['APRNUMB']?>' />
										<input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$sql_reqid[0]['SUBCORE']?>'>
										<input type='hidden' name='slt_subcore' id='slt_subcore' value='<?=$sql_reqid[0]['SUBCORE']?>'>
										<input type='hidden' name='txtdetails' id='txtdetails' maxlength='400' value='<? echo $sql_reqid[0]['APPRDET']; ?>'>

										<input type='hidden' maxlength='1' name='txt_prodwise_budget' id='txt_prodwise_budget' value='<?=$sql_reqid[0]['PRODWIS']?>'>
										<input type="hidden" name='deluser' id='deluser' value='<?=$sql_reqid[0]['DELTUSER']?>'>
										<input type='hidden' name='txt_kind_attn' id='txt_kind_attn' value='<?=$sql_reqid[0]['REQSTTO']?>'>
										<input type='hidden' name='slt_title' id='slt_title' value='<?=$sql_reqid[0]['APRTITL']?>'>
										<input type='hidden' name='hid_appattn_cnt' id='hid_appattn_cnt' value='<?=$sql_reqid[0]['APPATTN']?>'>
										<input class="form-control" type='hidden' placeholder="Subject" tabindex='4' required maxlength='150' name='txtsubject' id='txtsubject' value='<?=$sql_reqid[0]['APPRSUB']?>' data-toggle="tooltip" data-placement="top" title="Subject">
										<input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$sql_reqid[0]['ATCCODE']?>'>
										<input type='hidden' name='hid_slt_topcore' id='hid_slt_topcore' value='<?=$sql_reqid[0]['ATCCODE']?>'>
										<input type='hidden' name='slt_submission' id='slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
										<input type='hidden' name='hid_slt_submission' id='hid_slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
										<? 	$slt_submission = $sql_reqid[0]['ATMCODE'];
											if($slt_submission == 0 or $slt_submission == 1 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
												<input type="hidden" name='fieldimpl' id='fieldimpl' value='fieldimpl'>
												<input type="hidden" name='fldimpi' id='fldimpi' value='<?=$sql_reqid[0]['FLDIMPI']?>'>
												<input type="hidden" name='othersupdocs' id='othersupdocs' value='othersupdocs'>
												<input type="hidden" name='othersupdocsi' id='othersupdocsi' value='<?=$sql_reqid[0]['OTHERSUPDOCS']?>'>
											<? }
											if($slt_submission == 2 or $slt_submission == 5) { ?>
												<input type="hidden" name='lastapproval' id='lastapproval' value='lastapproval'>
												<input type="hidden" name='lstapri' id='lstapri' value='<?=$sql_reqid[0]['LSTAPRI']?>'>
										<? 	}
											if($slt_submission == 0 or $slt_submission == 1 or $slt_submission == 2 or $slt_submission == 3 or $slt_submission == 4 or $slt_submission == 6) { ?>
												<input type="hidden" name='quotations' id='quotations' value='quotations'>
												<input type="hidden" name='qutat1i' id='qutat1i' value='<?=$sql_reqid[0]['QUTAT1I']?>'>
										<? } ?>
										<? 	if($sql_reqid[0]['EXPSRNO'] != '') {
												$sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '".$sql_reqid[0]['EXPSRNO']."'", "Centra", 'TCS');
											} else {
												$sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '13'", "Centra", 'TCS');
											}
										?>
										<input type='hidden' name='txt_rptmode' id='txt_rptmode' value='<?=$sql_rptmode[0]['RPTMODE']?>'>
										<input type="hidden" name='clrphoto' id='clrphoto' value='clrphoto'>
										<input type="hidden" name='smplpti' id='smplpti' value='<?=$sql_reqid[0]['SMPLPTI']?>'>
										<input type='hidden' class="form-control" name='slt_tarbaln' id='slt_tarbaln' value='<?=$sql_reqid[0]['TARBALN']?>' data-toggle="tooltip" data-placement="top" title="Request Value">
										<input type='hidden' class="form-control" name='slt_tardesc' id='slt_tardesc' value='<?=$sql_reqid[0]['TARDESC']?>' data-toggle="tooltip" data-placement="top" title="Request Value">




                                        <!-- Approval Subject -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Approval Subject <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                : <?=$sql_reqid[0]['APMNAME'].$sql_reqid[0]['DYNSUBJ'].$sql_reqid[0]['TXTSUBJ']?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Approval Subject -->


                                        <!-- Initiator & Attachments Panel -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Initiator & Attachments</strong></h3>
                                                <? /* <ul class="panel-controls">
                                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                                </ul> */ ?>
                                            </div>
                                            <div class="panel-body">
                                                <!-- Work Initiate Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Work Initiate Person <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  $sql_emp = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME,
                                                                                                        sal.PAYCOMPANY
                                                                                                    from employee_office emp, empsection sec, designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."')
                                                                                                        and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                union
                                                                                                    select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME,
                                                                                                        sal.PAYCOMPANY
                                                                                                    from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."')
                                                                                                        and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                    order by EMPCODE", "Centra", 'TCS'); // 02052017
                                                            echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." "; ?>
                                                            <input type='hidden' class="form-control" tabindex='11' style="text-transform: uppercase;" required name='txt_workintiator' id='txt_workintiator' data-toggle="tooltip" data-placement="top" data-original-title="Work Initiate Person" value='<?=$sql_reqid[0]['WRKINUSR']?>'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Work Initiate Person -->

                                                <!-- Responsible Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Responsible Person <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  $sql_emp = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME,
                                                                                                        sal.PAYCOMPANY
                                                                                                    from employee_office emp, empsection sec, designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['RESPUSR']."'
                                                                                                        or emp.empcode = '".$sql_reqid[0]['ALTRUSR']."' or emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and
                                                                                                        sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                union
                                                                                                    select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME,
                                                                                                        sal.PAYCOMPANY
                                                                                                    from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['RESPUSR']."'
                                                                                                        or emp.empcode = '".$sql_reqid[0]['ALTRUSR']."' or emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and
                                                                                                        sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                    order by EMPCODE", "Centra", 'TCS'); // 02052017
                                                            echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." "; ?>
                                                            <input type='hidden' class="form-control" tabindex='11' style="text-transform: uppercase;" required name='txt_submission_reqby' id='txt_submission_reqby' data-toggle="tooltip" data-placement="top" data-original-title="Responsible Person" value='<?=$sql_emp[0]['RESPUSR']?>'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Responsible Person -->

                                                <!-- Alternate User -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Alternate User</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if(count($sql_emp) > 1) {
                                                                $emcd = $sql_emp[1]['EMPCODE'];
                                                                $emnm = $sql_emp[1]['EMPNAME'];
                                                                $dsnm = $sql_emp[1]['DESNAME'];
                                                                $senm = $sql_emp[1]['ESENAME'];
                                                            } else {
                                                                if($sql_reqid[0]['ALTRUSR'] != '') {
                                                                    $emcd = $sql_emp[0]['EMPCODE'];
                                                                    $emnm = $sql_emp[0]['EMPNAME'];
                                                                    $dsnm = $sql_emp[0]['DESNAME'];
                                                                    $senm = $sql_emp[0]['ESENAME'];
                                                                } else {
                                                                    $emcd = '';
                                                                    $emnm = '';
                                                                    $dsnm = '';
                                                                    $senm = '';
                                                                }
                                                            }

                                                            if($emcd != '')
                                                                echo ": ".$emcd." - <b>".$emnm."</b> (".$dsnm.") - ".substr($senm, 3)." "; ?>
                                                            <input type='hidden' class="form-control" style="text-transform: uppercase;" tabindex='11' name='txt_alternate_user' id='txt_alternate_user' data-toggle="tooltip" data-placement="top" data-original-title="Alternate User" value='<?=$sql_reqid[0]['ALTRUSR']?>'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Alternate User -->

                                                <!-- Attachments -->
                                                <!-- Quotations -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'quotations'", "Centra", 'TEST'); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Quotations & Estimations</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="Quotations & Estimations" tabindex='12' class="form-control fileselect" name='txt_submission_quotations[]' id='txt_submission_quotations' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Quotations & Estimations"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    $exp = explode("_", $filename);
                                                                    switch($exp[5])
                                                                    {
                                                                        case 'i':
                                                                                $folder_path = "approval_desk/request_entry/".$dataurl."/";
                                                                                $thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";

                                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                                                break;
                                                                        case 'n':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        default:
                                                                                echo $fieldindi = '';
                                                                                break;
                                                                    }
                                                                }
                                                                echo "</ul>";
                                                        // }

                                                            if($sql_reqid[0]["RMQUOTS"] != '') {
                                                                echo "<br> : ".$sql_reqid[0]["RMQUOTS"];
                                                            } ?>
                                                        </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Quotations -->


                                                <!-- Approval Supporting Documents -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'fieldimpl'", "Centra", 'TEST'); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Budget / Common / Reference Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="Approval Supporting Documents" tabindex='12' class="form-control fileselect" name='txt_submission_fieldimpl[]' id='txt_submission_fieldimpl' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Approval Supporting Documents"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                          for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                            $filename = $sql_docs[$ij]['APRDOCS'];
                                                            $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                            $exp = explode("_", $filename);
                                                            switch($exp[5])
                                                            {
                                                                case 'i':
                                                                        /* echo $fieldindi = "<a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" data-toggle=\"lightbox\" data-gallery=\"multiimages\" data-title=\"\" data-footer=\"<a target='_blank' download href='ftp://".$ftp_user_name_apdsk.':'.$ftp_user_pass_apdsk.'@'.$ftp_server_apdsk.$ftp_srvport_apdsk.'/approval_desk/request_entry/'.$dataurl.'/'.$filename."' class='btn btn-success'><i class='fa fa-fw fa-download'></i> Download Image</a>&nbsp;&nbsp;<a href='javascript:void(0)' class='idrotate btn btn-primary'><i class='fa fa-fw fa-rotate-right'></i> Rotate</a>&nbsp;&nbsp;<button class='btn btn-primary zoom-in'>Zoom In <i class='fa fa-fw fa-plus'></i></button>&nbsp;&nbsp;<button class='btn btn-primary zoom-out'>Zoom Out <i class='fa fa-fw fa-minus'></i></button>&nbsp;&nbsp;<button class='btn btn-warning reset'>Reset <i class='fa fa-fw fa-refresh'></i></button>\" style=\"float:left; margin-bottom:10px\"><img src=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" class=\"img-responsive style_box\" id='image' border=0 style=\"width:100px; height:100px; margin-left:5px\"></a>"; */

                                                                        $folder_path = "approval_desk/request_entry/".$dataurl."/";
                                                                        $thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";

                                                                        echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                                        break;
                                                                case 'n':
                                                                        echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                        break;
                                                                case 'w':
                                                                        echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                        break;
                                                                case 'e':
                                                                        echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                        break;
                                                                case 'p':
                                                                        echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                        break;
                                                                default:
                                                                        echo $fieldindi = '';
                                                                        break;
                                                            }
                                                          }
                                                          echo "</ul>";
                                                       // }

                                                            if($sql_reqid[0]["RMBDAPR"] != '') {
                                                                echo "<br> : ".$sql_reqid[0]["RMBDAPR"];
                                                            } ?>
                                                            </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Approval Supporting Documents -->

                                                <!-- Color Photo Sample / Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'clrphoto'", "Centra", 'TEST'); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Work Place Before / After Photo / Drawing Layout</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="Color Photo Sample / Artwork" tabindex='12' class="form-control fileselect" name='txt_submission_clrphoto[]' id='txt_submission_clrphoto' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Color Photo Sample / Artwork"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    $exp = explode("_", $filename);
                                                                    switch($exp[5])
                                                                    {
                                                                        case 'i':
                                                                                $folder_path = "approval_desk/request_entry/".$dataurl."/";
                                                                                $thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";

                                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                                                break;
                                                                        case 'n':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        default:
                                                                                echo $fieldindi = '';
                                                                                break;
                                                                    }
                                                                }
                                                                echo "</ul>";
                                                        // }

                                                            if($sql_reqid[0]["RMCLRPT"] != '') {
                                                                echo "<br> : ".$sql_reqid[0]["RMCLRPT"];
                                                            } ?>
                                                            </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Color Photo Sample / Artwork -->

                                                <!-- Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'artwork'", "Centra", 'TEST'); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Art Work Design with MD Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="ART WORK DESIGN WITH MD APPROVAL" tabindex='12' class="form-control fileselect" name='txt_submission_artwork[]' id='txt_submission_artwork' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="ART WORK DESIGN WITH MD APPROVAL"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    $exp = explode("_", $filename);
                                                                    switch($exp[5])
                                                                    {
                                                                        case 'i':
                                                                                $folder_path = "approval_desk/request_entry/".$dataurl."/";
                                                                                $thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";

                                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                                                break;
                                                                        case 'n':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        default:
                                                                                echo $fieldindi = '';
                                                                                break;
                                                                    }
                                                                }
                                                                echo "</ul>";
                                                        // }

                                                            if($sql_reqid[0]["RMARTWK"] != '') {
                                                                echo "<br> : ".$sql_reqid[0]["RMARTWK"];
                                                            } ?>
                                                            </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Artwork -->

                                                <!-- Consultant Approval -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'othersupdocs'", "Centra", 'TEST'); ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Consultant Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="Consultant Approval" tabindex='12' class="form-control fileselect" name='txt_submission_othersupdocs[]' id='txt_submission_othersupdocs' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Consultant Approval"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    $exp = explode("_", $filename);
                                                                    switch($exp[5])
                                                                    {
                                                                        case 'i':
                                                                                $folder_path = "approval_desk/request_entry/".$dataurl."/";
                                                                                $thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";

                                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                                                break;
                                                                        case 'n':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        default:
                                                                                echo $fieldindi = '';
                                                                                break;
                                                                    }
                                                                }
                                                                echo "</ul>";
                                                        // }

                                                            if($sql_reqid[0]["RMCONAR"] != '') {
                                                                echo "<br> : ".$sql_reqid[0]["RMCONAR"];
                                                            } ?>
                                                        </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Consultant Approval -->
                                                <!-- Attachments -->

                                                <!-- Warranty / Guarantee -->
                                                <? if($sql_reqid[0]["WARQUAR"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Warranty / Guarantee</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["WARQUAR"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Warranty / Guarantee -->

                                                <!-- Current / Closing Stock -->
                                                <? if($sql_reqid[0]["CRCLSTK"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Current / Closing Stock</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["CRCLSTK"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Current / Closing Stock -->

                                                <!-- Advance or Final Payment / Work Completion Percentage -->
                                                <? if($sql_reqid[0]["PAYPERC"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Advance or Final Payment / Work Completion Percentage</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["PAYPERC"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Advance or Final Payment / Work Completion Percentage -->

                                                <!-- Work Finish Target Date -->
                                                <? if($sql_reqid[0]["FNTARDT"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Work Finish Target Date</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["FNTARDT"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Work Finish Target Date -->

                                                <!-- Work Finish Target Date -->
                                                <? if($sql_reqid[0]["AGEXPDT"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Work Agreement Expiry Date</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["AGEXPDT"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Work Finish Target Date -->

                                                <!-- Work Finish Target Date -->
                                                <? if($sql_reqid[0]["AGADVAM"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Agreement Advance Amount</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            : <?=$sql_reqid[0]["AGADVAM"]?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Work Finish Target Date -->

												<input type='hidden' name='txt_submission_quotations_remarks' id='txt_submission_quotations_remarks' value='<?=$sql_reqid[0]['RMQUOTS']?>'>
												<input type='hidden' name='txt_submission_fieldimpl_remarks' id='txt_submission_fieldimpl_remarks' value='<?=$sql_reqid[0]['RMBDAPR']?>'>
												<input type='hidden' name='txt_submission_clrphoto_remarks' id='txt_submission_clrphoto_remarks' value='<?=$sql_reqid[0]['RMCLRPT']?>'>
												<input type='hidden' name='txt_submission_artwork_remarks' id='txt_submission_artwork_remarks' value='<?=$sql_reqid[0]['RMARTWK']?>'>
												<input type='hidden' name='txt_submission_othersupdocs_remarks' id='txt_submission_othersupdocs_remarks' value='<?=$sql_reqid[0]['RMCONAR']?>'>
												<input type='hidden' name='txt_warranty_guarantee' id='txt_warranty_guarantee' value='<?=$sql_reqid[0]['WARQUAR']?>'>
												<input type='hidden' name='txt_cur_clos_stock' id='txt_cur_clos_stock' value='<?=$sql_reqid[0]['CRCLSTK']?>'>
												<input type='hidden' name='txt_advpay_comperc' id='txt_advpay_comperc' value='<?=$sql_reqid[0]['PAYPERC']?>'>
												<input type='hidden' name='txt_workfin_targetdt' id='txt_workfin_targetdt' value='<?=strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['FNTARDT'])))?>'>
												<input type='hidden' name='txt_agreement_expiry' id='txt_agreement_expiry' value='<?=strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['AGEXPDT'])))?>'>
												<input type='hidden' name='txt_agreement_advance' id='txt_agreement_advance' value='<?=$sql_reqid[0]['AGADVAM']?>'>
												<input type='hidden' name='txtdue_date' id='txtdue_date' value='<?=$sql_reqid[0]['APRDUED_TIME']?>'>
                                            </div>
                                        </div>
                                        <!-- Initiator & Attachments Panel -->



                                        <!-- Process Flow Panel -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Process</strong></h3>
                                                <ul class="panel-controls">
                                                    <li style="font-weight: bold;">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                echo "Prepared By : ".$sql_reqid[0]["EMPCODE"]." - <b>".$sql_reqid[0]["EMPNAME"]."</b>";
                                                            } else {
                                                                if($_REQUEST['action'] == 'edit') { $sql_emp3 = select_query_json("select * from employee_office where empsrno = ".$sql_reqid[0]['ADDUSER']); } ?>
                                                                <input class="form-control" placeholder="Prepared By" tabindex='27' type='hidden' readonly required maxlength='100' name='txtrequest_by' id='txtrequest_by' <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_emp3[0]['EMPCODE']." - ".$sql_emp3[0]["EMPNAME"]?>'<? } else { ?>value='<?=$_SESSION['tcs_user']." - ".strtoupper($_SESSION['tcs_username'])?>'<? } ?> data-toggle="tooltip" data-placement="top" title="Prepared By">
                                                                <input class="form-control" placeholder="Prepared By" type='hidden' tabindex='27' readonly required maxlength='10' name='txtrequest_byid' <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_emp3[0]['EMPSRNO']?>'<? } else { ?>value='<?=$_SESSION['tcs_empsrno']?>'<? } ?> id='txtrequest_byid' data-toggle="tooltip" data-placement="top" title="Prepared By">
                                                                <? if($_REQUEST['action'] == 'edit') { echo "Prepared By : ".$sql_emp3[0]['EMPCODE']." - ".$sql_emp3[0]["EMPNAME"]; }
                                                                    else { echo "Prepared By : ".$_SESSION['tcs_user']." - ".strtoupper($_SESSION['tcs_username']); } ?>
                                                        <? } ?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <!-- Related Approval Nos -->
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-3 control-label">Related Approval Nos</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                $sql_rlapr = explode(",", $sql_reqid[0]['RELAPPR']);
                                                                for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                    $sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", 'TCS'); ?>
                                                                    <a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
                                                                <? }
                                                                // echo ": ".$sql_reqid[0]['RELAPPR'];
                                                           } else { ?>
                                                                <textarea class="form-control" tabindex='17' rows="3" placeholder="Related Approval Nos" maxlength='250' name='txt_related_approvals' id='txt_related_approvals' data-toggle="tooltip" data-placement="top" title="Related Approval Nos" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"><? echo $sql_reqid[0]['RELAPPR']; ?></textarea>
                                                                <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 250 CHARACTERS ALLOWED.. IF MORETHAN 1 APPROVALS ARE AVAILABLE SEPARATE WITH COMMA..</span>
                                                        <? } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Related Approval Nos -->

                                                <!-- Against Approval No -->
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-3 control-label">Against Approval No</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                $sql_rlapr = explode(",", $sql_reqid[0]['AGNSAPR']);
                                                                for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                    $sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", 'TCS'); ?>
                                                                    <a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
																	<input type='hidden' name='txt_against_approval' id='txt_against_approval' value='<? echo $sql_reqid[0]['AGNSAPR'];  ?>'>
                                                                <? }
                                                           } else { ?>
                                                                <input type='text' class="form-control" tabindex='17' style="text-transform: uppercase;" required name='txt_against_approval' id='txt_against_approval' data-toggle="tooltip" data-placement="top" maxlength="100" title="Against Approval No (Same Top Core Based Last 3 Months Approvals)" placeholder="Against Approval No (Same Top Core Based Last 3 Months Approvals)" value='<?=$sql_reqid[0]['AGNSAPR']?>'>
                                                        <? } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Related Approval Nos -->

                                                <div class="form-group trbg" id='getmonthwise_budget' <? if($view == 1 && $sql_reqid[0]['BDPLANR'] == 'MONTHWISE') { ?> style='display:block;' <? } else { ?> style='display:none;' <? } ?>>
                                                <? /* <div class="form-group trbg" id='getmonthwise_budget' <? if($view == 1) { ?> style='display:block;' <? } else { ?> style='display:none;' <? } ?>> */ ?>
                                                    <? if($_REQUEST['action'] != '') {
                                                    	// echo "++".count($sql_prdlist)."++";
														if(count($sql_prdlist) > 0) {
															// $edtvl = 0;
															$edtvl = 1;
															$displaynone = ' display: none; ';
														} else {
															$edtvl = 1;
															$displaynone = '';
														}

														$canedit = 1;
														if($sql_reqid[0]['APMCODE'] == '807' || $sql_reqid[0]['APMCODE'] == '777') {
															$canedit = 0;
														} ?>
													<div class="col-md-3 control-label">Budget Planner &#8377;</div>
													<div class="col-lg-9 col-xs-12"> :
														<div <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>>
														<table style='clear:both; float:left; width:100%;'>
														<tr><td><div id='id_budplanner' <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>></div></td></tr>
														<tr><td>
															<table class="monthyr_wrap" style='width:100%; line-height:22px; <?=$displaynone?>'>
																<? 	// echo "++".$tmporlive."++";
																	if($tmporlive == 0) {
																		if($_SESSION['tcs_user'] == 17108) {
																			$sql_plan = select_query_json("select * from approval_budget_planner_temp
																												where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", "Centra", 'TEST');
																		} else {
																			$sql_plan = select_query_json("select * from approval_budget_planner_temp
																												where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", "Centra", 'TEST');
																		}
																	} else {
																		$sql_plan = select_query_json("select * from approval_budget_planner
																											where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", "Centra", 'TEST');
																	}

																	for($plani = 0; $plani < count($sql_plan); $plani++) { ?>
																		<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRPRID']?>'><input type='hidden' name='mnt_yraprsrno[]' id='mnt_yraprsrno_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRSRNO']?>'><span><?=$sql_plan[$plani]['APRMNTH']?></span> : </td>
																		<td style='width:5%;'></td><td style='width:30%;'><? if($_REQUEST['action'] == 'edit') { ?><input type='text' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><?=moneyFormatIndia(floor($sql_plan[$plani]['APPRVAL']))?><? } ?></td>
																		<td style='width:40%;'><? if(floor($sql_plan[$plani]['APPRVAL']) > 0) { ?><input type='text' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt1[]' id='mnt_yr_amt1_<?=$plani?>' class='form-control ttlsum' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> name='mnt_yr_amt1[]' id='mnt_yr_amt1_<?=$plani?>' class='form-control ttlsum' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } ?></td></tr>
																	<? } ?>
																	<tr><td colspan='2' style='width:40%; padding-top:2%; text-align:right; padding-right:5%; font-weight:bold;'>TOTAL : </td><td style='width:30%; padding-top:2%; font-weight:bold;'><?=moneyFormatIndia(floor($sql_reqid[0]['APRQVAL']))?></td><td style='width:30%; padding-top:2%; font-weight:bold;'><span id='ttl_mntyr'><?=moneyFormatIndia(floor($sql_reqid[0]['APRQVAL']))?></span></td></tr>
																	<input type="hidden" id="ttl_lock" name="ttl_lock" value="<?=floor($sql_reqid[0]['APRQVAL'])?>">
															</table>
														</td></tr>
														</table>
														</div>
													</div>
													<div class='clear clear_both'></div>
												<? } else { ?>
													<div class="col-md-3 control-label">Budget Planner &#8377;</div>
													<div class="col-lg-9 col-xs-12"> :
														 <div id='id_budplanner'></div>
														<div>
															<table style='clear:both; float:left; width:100%;'>
															<tr><td>
																<table class="monthyr_wrap" style='width:100%;'></table>
															</td></tr>
															</table>
														</div>
														<div class='clear clear_both'></div>
													</div>
												<? } ?>
                                                    <div class="tags_clear"></div>
                                                </div>
                                                <div class="tags_clear"></div>





                                                <? if($view == 1) { ?>
                                                    <!-- Budget Mode -->
                                                    <div class="form-group" id='id_budgetmode' <? if($view == 1) { ?> style="display: block;" <? } else { ?> style="display: none;" <? } ?>>
                                                        <label class="col-md-3 control-label">Budget Mode </label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <? if($_REQUEST['action'] == 'view') {
                                                                    $sql_bdmd = select_query_json("select * from APPROVAL_BUDGET_MODE
                                                                                                        where DELETED = 'N' and BUDCODE = '".$sql_reqid[0]['BUDCODE']."' order by BUDNAME", "Centra", 'TCS'); ?>
                                                                : <? if($sql_bdmd[0]['BUDNAME'] != '') { if($sql_bdmd[0]['BUDCODE'] == 5) { ?>
                                                                    <span class="badge badge-success" style='background-color:#08a208; font-weight:bold;'><?=$sql_bdmd[0]['BUDNAME'];?></span>
                                                                <? } else { echo $sql_bdmd[0]['BUDNAME']; } } else { echo "-"; } ?>
                                                            <? } else { ?>
                                                                <select class="form-control custom-select chosn" tabindex='19' name='slt_budgetmode' id='slt_budgetmode' data-toggle="tooltip" data-placement="top" title="Budget Mode">
                                                                <?  $sql_project = select_query_json("select * from APPROVAL_BUDGET_MODE where DELETED = 'N' order by BUDNAME", "Centra", 'TCS');
                                                                    for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                        <option value='<?=$sql_project[$project_i]['BUDCODE']?>' <? if($sql_reqid[0]['BUDCODE'] == $sql_project[$project_i]['BUDCODE']) { ?> selected <? } ?>><?=$sql_project[$project_i]['BUDNAME']?></option>
                                                                <? } ?>
                                                                </select>
                                                            <? } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <!-- Budget Mode -->

                                                    <!-- Approval Type -->
                                                    <div class="form-group" id='id_budgetmode' style="display: none;">
                                                        <label class="col-md-3 control-label">Approval Type </label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo $sql_reqid[0]['APPTYPE'];
                                                                } else { ?>
                                                                <select class="form-control custom-select chosn" tabindex='19' name='slt_apptype' id='slt_apptype' data-toggle="tooltip" data-placement="top" title="Approval Type">
                                                                    <option value='EXPENSE' <? if($sql_reqid[0]['APPTYPE'] == 'EXPENSE') { ?> selected <? } ?>>EXPENSE</option>
                                                                </select>
                                                            <? } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <!-- Approval Type -->
                                                <? } ?>


                                                <!-- Process Duration -->
                                                <div class="form-group trbg" style='min-height:90px; display:none'>
                                                    <div class="col-lg-3 col-xs-3">
                                                        <label style='height:27px;'>Process Duration <span style='color:red'>*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-xs-9">

                                                    <div>
                                                    <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo "<b>From Date</b> : ".$sql_reqid[0]['APPRSFR_TIME'];
                                                           } else { ?>
                                                                <div class='input-group date' id='datetimepicker9' tabindex='19' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input1">
                                                                    <input type='text' class="form-control" size="20" tabindex='20' name='txtfrom_date' required placeholder='From Date' id='txtfrom_date' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    <input type='hidden' class="form-control" size="20" tabindex='20' name='txtfrom_date1' required placeholder='From Date' id='txtfrom_date1' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=date("m-d-Y")?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="dtp_input1" name='dtp_input1' value="" />
                                                        <? } ?>
                                                    </div>

                                                    <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo "<b>To Date</b> : ".$sql_reqid[0]['APPRSTO_TIME'];
                                                           } else { ?>
                                                                <div class='input-group date' id='datetimepicker10' tabindex='21' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input2" onblur="call_days()">
                                                                    <input type='text' class="form-control" size="20" tabindex='21' name='txtto_date' required placeholder='To Date' id='txtto_date' onblur="call_days()" type="text" <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSTO_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> onblur="call_days()" readonly data-toggle="tooltip" data-placement="top" title="To Date" />
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="dtp_input2" name='dtp_input2' value="" />
                                                        <? } ?>
                                                    </div>
                                                    </div>
                                                    <? if($_REQUEST['action'] != 'view') { ?><div class='clear' style='padding-top:10px;'></div><? } else { ?><div class='clear'></div><? } ?>

                                                        <div>
                                                        <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            <b>No of Hours</b> : <?=$sql_reqid[0]['APRHURS']?>
                                                        <? } else { ?>
                                                            <div class="input-group margin" title="No of Hours">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-warning">No of Hours</button>
                                                                </div><!-- /btn-group -->
                                                                <input class="form-control" placeholder="No of Hours" onKeyPress="return isNumber(event)" tabindex='22' maxlength='5' required name='txtnoofhours' id='txtnoofhours' readonly onfocus="date_diff()" <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRHURS']?>'<? } else { ?>value='24'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Hours">
                                                            </div>
                                                        <? } ?>
                                                        </div>

                                                        <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            <b>No of Days</b> : <?=$sql_reqid[0]['APRDAYS']?>
                                                        <? } else { ?>
                                                            <div class="input-group margin" title="No of Days">
                                                                <div class="input-group-btn">
                                                                    <button type="button" class="btn btn-warning">No of Days</button>
                                                                </div><!-- /btn-group -->
                                                                <input class="form-control" placeholder="No of Days" onKeyPress="return isNumber(event)" maxlength='3' tabindex='23' required name='txtnoofdays' id='txtnoofdays' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRDAYS']?>'<? } else { ?>value='1'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Days">
                                                            </div>
                                                        <? } ?>
                                                        </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Process Duration -->

                                                <input type='hidden' class="form-control" placeholder="Productwise Budget" onKeyPress="return isNumber(event)" required="required" maxlength='1' name='txt_prodwise_budget' id='txt_prodwise_budget' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['PRODWIS']?>'<? } else { ?>value='0'<? } ?> data-toggle="tooltip" data-placement="top" title="Productwise Budget">
                                                <div id='id_reqvalue_hidden'>
                                                    <input type='hidden' class="form-control hidn_balance" placeholder="Request Value" onKeyPress="return isNumber(event)" maxlength='9' name='hidrequest_value' id='hidrequest_value' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRQVAL']?>'<? } else { ?>value='0'<? } ?> data-toggle="tooltip" data-placement="top" title="Request Value">
                                                    <? if($_REQUEST['action'] == 'edit' && $sql_reqid[0]['ATYCODE'] != 1) { ?>
                                                        <input type='hidden' class="form-control hidn_balance" placeholder="Request Value" tabindex='24' onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRQVAL']?>'<? } else { ?>value='0'<? } ?> data-toggle="tooltip" data-placement="top" title="Request Value">
                                                    <? } ?>
                                                </div>



                                                <!-- Request Value -->
                                                <div id='id_reqvalue'>
                                                <? if($view == 1) { /* ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Reserved Budget Balance against Expense Head [ <span class="clrblue"><?=$expnam?></span> ]</b> &#8377;</label>
                                                        <div class="col-md-9 col-xs-12">
                                                        	<? $target_balance = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) +
																											sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE, (sum(distinct nvl(sm.BUDVALUE, 0)) -
																											sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) RESVALUE
																										from trandata.budget_planner_head_sum@tcscentr sm, trandata.approval_budget_planner_temp@tcscentr tm
																										where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N'
																											and sm.BRNCODE=".$sql_reqid[0]['BRNCODE']." and sm.BUDYEAR = '".$hid_year."' and
																											sm.EXPSRNO = ".$sql_reqid[0]['EXPSRNO']."");

																// Query for find the Reserved Budget balance
																$balance = 0;
																if($target_balance[0]['RESVALUE'] > 0) {
																	$balance = $target_balance[0]['RESVALUE'];
								                                    $expld = explode(".", $balance);
								                                    $mny = moneyFormatIndia($expld[0]);
								                                    if($expld[1] > 0) {
								                                        $mny = $mny.".".$expld[1];
								                                    } else{
								                                        $mny = $mny.".00";
								                                    }
																} else {
																	$balance = 0;
								                                    $mny = 0;
																}
																echo "<b style='padding-bottom:10px; font-size:16px; color:#FF0000'>".moneyFormatIndia($balance)."</b>";
															?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div> */ ?>

                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Request Value &#8377; </label>
                                                        <div class="col-md-9 col-xs-12">
                                                        	<input class="form-control" type='hidden' placeholder="Request Value" tabindex='24' onKeyPress="return isNumber(event)" required maxlength='9' name='txtrequest_value' id='txtrequest_value' <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APPFVAL']?>'<? } else { ?>value='<?=$sql_reqid[0]['APPFVAL']?>'<? } ?> onblur="calculate_sum();" data-toggle="tooltip" data-placement="top" title="Request Value">
															<div class="input-group" id='id_reqvlu'>
																<?=moneyFormatIndia($sql_reqid[0]['APPFVAL'])?>
															</div>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Request Value -->

                                                <!-- Implementation Due Date -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Implementation Due Date <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? 	echo ": ";
                                                            if($sql_reqid[0]['IMDUEDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT']))); }
                                                            else { echo strtoupper(date("d-M-Y")); } ?>
                                                            <input type="hidden" tabindex='24' name="impldue_date" id="impldue_date" class="form-control" value='<?=$sql_reqid[0]['IMDUEDT']?>' style='text-transform:uppercase;'>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Implementation Due Date -->


                                                <!-- Branch -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Branch <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            : <?=$sql_reqid[0]['BRANCH']?>
                                                        <? } else { $allow_branch = explode(",", $_SESSION['tcs_allowed_branch']);
                                                                if(($_SESSION['tcs_brncode'] == 888 or $_SESSION['tcs_brncode'] == 100) and ($brnch_y_n == 'Y')) {
                                                                    $sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
                                                                                                                where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') and
                                                                                                                    (brn.brncode in (select distinct brncode from budget_planner_head_sum) or
                                                                                                                    brn.brncode in (109,114,117,120, 300)) and brn.brncode not in (11, 22, 202, 205, 119)
                                                                                                                order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
                                                                } else {
                                                                    $sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
                                                                                                                where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') and
                                                                                                                    (brn.brncode in (".$_SESSION['tcs_brncode'].")) and
                                                                                                                    brn.brncode not in (11, 22, 202, 205, 119)
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
                                                                        <? if($project_i == 0) { ?><input type="hidden" class="form-control" name="slt_branch" id="slt_branch" value="<?=$sql_project[$project_i]['BRNCODE']?>" style="margin:2px;"><? } ?>
                                                                        <input type="text" class="form-control" name="txt_brnvalue[]" id="txt_brnvalue_<?=$project_i?>" readonly value="" style="margin:2px;">
                                                                    </div>
                                                                    <div class="tags_clear"></div>
                                                                </div>
                                                                <div class="tags_clear"></div>
                                                            <? }
                                                        } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Branch -->


                                                <div id="id_approval_listings">
                                                <? if($_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') { ?>
                                                    <div class="form-group trbg" style="text-align: right;">
                                                        <div class="col-lg-3 col-xs-3">
                                                            <label style='height:27px;'>Next Approval Flow <span style='color:red'>*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-xs-9" style="font-weight:normal; text-align: left;">
                                                            : <span style="font-weight: bold;"><? $flo = 0; $newentry = 0;
                                                                switch($_SESSION['tcs_empsrno']) {
                                                                    case 127:
                                                                        $usr_cd = '1333'; break;
                                                                    case 1202:
                                                                        $usr_cd = '1726'; break;
                                                                    default:
                                                                        $usr_cd = $_SESSION['tcs_user']; break;
                                                                }

                                                            $sql_app_hierarchy = select_query_json("select * from APPROVAL_MDHIERARCHY amh, employee_office emp
                                                                                                        where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and APRNUMB = '".$sql_reqid[0]['APRNUMB']."'
                                                                                                        order by amh.APMCODE, amh.AMHSRNO desc", "Centra", 'TEST'); // 02052017
                                                            if(count($sql_app_hierarchy) > 0) { $flo = 1; $newentry = 1; // echo "!!!";
                                                                for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) { ?>
                                                                    <? $last[] = $sql_app_hierarchy[$app_hierarchy_i]['EMPCODE']; ?>
                                                            <?  }

                                                                for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) {
                                                                    if($sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 1 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 2 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 3 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 4 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 5) {
                                                                            echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME']." <br>";
                                                                        } else {
                                                                        echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' - '.$sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'].' [ '.$sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'].' Day(s) ] <br>';
                                                                    }
                                                                    $appuser .= $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD']."~~";
                                                                }
                                                            } else { $flo = 0; $newentry = 0; // echo "@@@";
                                                                    $sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
                                                                                                                    where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and amh.DELETED = 'N'
                                                                                                                    order by amh.APMCODE, amh.AMHSRNO desc", "Centra", 'TEST'); // 02052017

                                                                    for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) { ?>
                                                                        <? $last[] = $sql_app_hierarchy[$app_hierarchy_i]['EMPCODE']; ?>
                                                                <?  }

                                                                    for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) {
                                                                        if($sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 1 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 2 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 3 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 4 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 5) {
                                                                            echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME']." <br>";
                                                                        } else {
                                                                            echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' - '.$sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'].' [ '.$sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'].' Day(s) ] <br>';
                                                                        }
                                                                        $appuser .= $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD']."~~";
                                                                    }
                                                                } ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="hid_noofdays" id="hid_noofdays" value="<? if($appdays != '') { echo $appdays; } else { echo $sql_reqid[0]['APRDAYS']; } ?>">
                                                    <input type="hidden" name="hid_appuser" id="hid_appuser" value="<?=$appuser?>">
                                                    <? if($flo == 1) { ?>
                                                        <input type="hidden" name="hid_newentry" id="hid_newentry" value="<?=$newentry?>">
                                                        <input type="hidden" name="hid_apmcd" id="hid_apmcd" value="<?=$sql_reqid[0]['APMCODE']?>">
                                                    <? } ?>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                </div>
                                                <div class="tags_clear"></div>


                                                <?  $balamt = 0; ?>
                                                <div id="id_salaryadvance" style="margin-left:15px;"></div>
                                                <div class="tags_clear"></div>


                                                <div id="id_advancedetails" style="margin-left:15px;"></div>
                                                <div class="tags_clear"></div>

                                            </div>
                                        </div>
                                        <!-- Process Flow Panel -->




                                    </div>
                                    <div class="col-md-6">

                                        <div class="tags_clear"></div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" style="text-align: left;">Details <span style='color:red'>*</span> : </label>
                                            <div class="tags_clear height10px"></div>
                                            <div class="col-md-12" style="border: 1px solid #dadada; padding: 5px !important;">
                                                <?  if($_REQUEST['action'] == 'view') {
                                                        if($sql_reqid[0]['APPRFOR'] == '1') {
                                                            $filepathname = $sql_reqid[0]['APPRSUB'];
                                                            $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                            $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_reqid[0]['APPRSUB']);
                                                            $contents = fread($handle, filesize($filename));
                                                            fclose($handle);
                                                            echo $contents;
                                                        } else {
                                                            echo ": ".$sql_reqid[0]['APPRDET'];
                                                        }
                                                   } else { ?>
                                                        <? /* <textarea class="form-control" <? if($_REQUEST['action'] != 'edit') { } ?> tabindex='17' rows="10" placeholder="Details" required maxlength='400' name='txtdetails' id='txtdetails' data-toggle="tooltip" data-placement="top" title="Details" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"><? echo $sql_reqid[0]['APPRDET']; ?></textarea>
                                                        <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 400 CHARACTERS ALLOWED..</span> */ ?>
                                                        <input type="hidden" name="hid_apprsub" id='hid_apprsub' value="<?=$sql_reqid[0]['APPRSUB']?>">
                                                        <textarea name="FCKeditor1" id="FCKeditor1" >
                                                            <?  if($_REQUEST['action'] == 'edit') {
                                                                    if($sql_reqid[0]['APPRFOR'] == '1') {
                                                                        $filepathname = $sql_reqid[0]['APPRSUB'];
                                                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_reqid[0]['APPRSUB']);
                                                                        $contents = fread($handle, filesize($filename));
                                                                        fclose($handle);
                                                                        echo $contents;
                                                                    } else {
                                                                        echo $sql_reqid[0]['APPRDET'];
                                                                    }
                                                                }
                                                            ?>
                                                        </textarea>
                                                        <script type="text/javascript">
                                                        var ckedit=CKEDITOR.replace("FCKeditor1",
                                                        {
                                                            height:"450", width:"100%",
                                                            filebrowserBrowseUrl : '/ckeditor/ckfinder/ckfinder.html',
                                                            filebrowserImageBrowseUrl : '/ckeditor/ckfinder/ckfinder.html?Type=Images',
                                                            filebrowserUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                            filebrowserImageUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
                                                        });
                                                        </script>
                                                <? } ?>
                                                <div class="tags_clear"></div>
                                            </div>
                                        </div>

                                        <? /* <div class="form-group" style="display: none;">
                                            <label class="col-md-3 control-label">Tags</label>
                                            <div class="col-md-9">
                                                <input type="text" class="tagsinput" value="First,Second,Third"/>
                                                <span class="help-block">Default textarea field</span>
                                            </div>
                                        </div> */ ?>




                                        <!-- Related Approvals & Tags -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Related Approvals & Tags</strong></h3>
                                            </div>
                                            <div class="panel-body">
                                                <?  $sql_approval_tags = select_query_json("select * from APPROVAL_TAGS
                                                                                                        where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' and TAGSTAT = 'N'
                                                                                                        order by TAGSRNO", "Centra", 'TEST');
                                                    echo "<ul class=\"list-tags\">";
                                                    foreach ($sql_approval_tags as $key => $tags_value) {
                                                        switch(rand(1, 4)) {
                                                            case 1: $li_cls = "li_greentags"; break;
                                                            case 2: $li_cls = "li_redtags"; break;
                                                            case 3: $li_cls = "li_greytags"; break;
                                                            default: $li_cls = "li_bluetags"; break;
                                                        } ?>
                                                       <li><a href="search-result.php?data=<?=$tags_value['TAGDATA']?>&term=<?=$tags_value['TAGTERM']?>&process=<?=$tags_value['TAGSDET']?>" target="_blank" class="<?=$li_cls?>"><span class="fa fa-tag"></span> <?=$tags_value['TAGSDET']?></a></li>
                                                    <? }
                                                    echo "</ul>"; ?>
                                                <div class='clear clear_both' style="height: 10px;"></div>
                                            </div>
                                            <div class="tags_clear"></div>
                                        </div>
                                        <!-- Related Approvals & Tags -->


                                        <!-- Approval Status & History -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Approval Status & History</strong></h3>
                                                <? /* <ul class="panel-controls">
                                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                                </ul> */ ?>
                                            </div>
                                            <div class="panel-body">
                                                <? if($sql_reqid[0]['ARQSRNO'] == 1) {
                                                        $sql_approval_levels = select_query_json("select req.REQDESN, req.REQESEN, req.APPRMRK, req.APPFVAL, req.APPFRWD, req.REQDESC, req.REQESEC,
                                                                                                            req.REQSTBY, (select EMPNAME from employee_office where empsrno = req.REQSTBY) frmemp,
                                                                                                            (select EMPNAME from employee_office where empsrno = req.REQSTFR) toemp, (select BRNNAME
                                                                                                            from branch where brncode = req.brncode) BRNNAME, to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM')
                                                                                                            INTPFRD_Time
                                                                                                        from APPROVAL_REQUEST req
                                                                                                        where req.ARQSRNO != 1 and req.ARQCODE = '".$_REQUEST['reqid']."' and
                                                                                                            req.ARQYEAR = '".$_REQUEST['year']."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                                                                                            req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted = 'N' and
                                                                                                            req.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'
                                                                                                        order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TEST');
                                                        for($sql_approval_levelsi = 0; $sql_approval_levelsi < count($sql_approval_levels); $sql_approval_levelsi++) { ?>
                                                    <div style='border:1px dashed #A0A0A0; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background-color:#F0F0F0; margin-bottom: 5px;'>
                                                        <div class="form-group trbg" style='min-height: 30px; line-height: 30px; margin-right: 0px; margin-left: 0px; margin-bottom: 5px;'>
                                                            <div class="col-lg-9 col-xs-9">
                                                                <label style='height:27px; text-transform:uppercase' class="blue_clr"><b><?=$sql_approval_levels[$sql_approval_levelsi]['FRMEMP']?> : </b></label><label style='height:27px; text-align:right; font-size:9px; text-transform:uppercase'><?=$sql_approval_levels[$sql_approval_levelsi]['REQDESN']?>, <?=$sql_approval_levels[$sql_approval_levelsi]['REQESEN']?>. <?=$sql_approval_levels[$sql_approval_levelsi]['BRNNAME']?></label>
                                                            </div>
                                                            <div class="col-lg-3 col-xs-3" style="text-align:right;">
                                                                <label style='height:27px; text-align:right; font-size:9px; text-transform:uppercase'><?=$sql_approval_levels[$sql_approval_levelsi]['INTPFRD_TIME']?></label>
                                                            </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                        <div class="form-group trbg" style='min-height: 30px; line-height: 30px; margin-right: 0px; margin-left: 0px; margin-bottom: 5px;'>
                                                            <div class="col-lg-12 col-md-12">
                                                                Remarks : <? if($sql_approval_levels[$sql_approval_levelsi]['APPRMRK'] != '') { echo $sql_approval_levels[$sql_approval_levelsi]['APPRMRK']; } ?>
                                                            </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                        <div class="form-group trbg" style='min-height: 30px; line-height: 30px; margin-right: 0px; margin-left: 0px; margin-bottom: 5px;'>
                                                            <div class="col-lg-12 col-md-12">
                                                                <? if($sql_approval_levels[$sql_approval_levelsi]['APPFVAL'] > 0) { ?>Approved Value &#8377; : <b class="red_clr"><?=moneyFormatIndia($sql_approval_levels[$sql_approval_levelsi]['APPFVAL'])?>.00</b>; <? } ?>Status : <? if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'N') { echo "<b class='green_clr'>NEW"; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'A') {
                                                                        if($sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 9 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 78) { // MD AUTHORIZED
                                                                            echo "<b class='green_clr'>AUTHORIZED";
                                                                        } else { echo "<b class='green_clr'>APPROVED"; }
                                                                    }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'R') { echo "<b class='red_clr'>REJECTED"; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'F') {
                                                                        if($sql_reqid[0]['REQESEC'] == $sql_approval_levels[$sql_approval_levelsi]['REQESEC'] and $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 132) { // Same Department HOD & Heads
                                                                            echo "<b class='green_clr'>REQUEST AUTHORIZED";
                                                                        } elseif($sql_approval_levels[$sql_approval_levelsi]['REQSTBY'] == 965 and $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 132) { // MIA HOD Verified
                                                                            echo "<b class='green_clr'>VERIFIED";
                                                                        } elseif($sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 19 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 165 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 132) { // SR.GM / GM / HOD Approved
                                                                            echo "<b class='green_clr'>APPROVED";
                                                                        } elseif($sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 9 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 78) { // MD AUTHORIZED
                                                                            echo "<b class='green_clr'>AUTHORIZED";
                                                                        } else {
                                                                            echo "<b class='green_clr'>VERIFIED";
                                                                        }
                                                                        echo "</b> and Next Person : <b class='green_clr'>".$sql_approval_levels[$sql_approval_levelsi]['TOEMP']; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'C') { echo "<b class='green_clr'>COMPLETED"; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'I') { echo "<b class='red_clr'>INTERNAL VERIFICATION"; echo "</b> and Next Person : <b class='green_clr'>".$sql_approval_levels[$sql_approval_levelsi]['TOEMP']; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'P') { echo "<b class='orange_clr'>PENDING"; echo "</b> and Next Person : <b class='green_clr'>".$sql_approval_levels[$sql_approval_levelsi]['TOEMP']; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'S') { echo "<b class='red_clr'>RESPONSE"; echo "</b> and Next Person : <b class='green_clr'>".$sql_approval_levels[$sql_approval_levelsi]['TOEMP']; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'Q') { echo "<b class='red_clr'>QUERY</b> raised to <b class='green_clr'>".$sql_approval_levels[$sql_approval_levelsi]['TOEMP']; } ?></b>
                                                            </div>
                                                        </div>
                                                        <div class="tags_clear"></div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <? } } ?>
                                                    <div class='clear clear_both' style="height: 10px;"></div>


                                                    <?  if($_REQUEST['action'] != '') { $isshow = 1; } else { $isshow = 0; }
                                                        if($isshow == 1) { ?>
                                                            <!-- Approval Status -->
                                                            <div class="alert <?=$appr_class?>" role="alert">
                                                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                                                <strong><?=$appr_status?></strong>
                                                            </div>
                                                        <? } ?>
                                                        <div class="tags_clear"></div>
                                                        </div>
                                                        <div class='clear clear_both' style="height: 10px;"></div>
                                                        <input type='hidden' name='hid_balance' id='hid_balance' value='<?=$balamt?>'>

                                            </div>
                                            <div class="tags_clear"></div>
                                        <!-- Approval Status & History -->

                                        <!-- Your Remarks & Process Priority -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Process Priority & Remarks </h3>
                                            </div>
                                            <div class="panel-body">
                                            	<!-- Process Priority -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Process Priority <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <select class="form-control custom-select chosn" tabindex='26' autofocus required name='slt_priority' id='slt_priority' data-toggle="tooltip" data-placement="top" title="Process Priority">
															<? $sql_process_priority = select_query_json("select * from approval_priority
																													where DELETED = 'N' and PRISRNO not in (4) order by PRISRNO Asc", "Centra", "TCS");
																for($process_priority_i = 0; $process_priority_i < count($sql_process_priority); $process_priority_i++) {
																	$txt_pri = $sql_process_priority[$process_priority_i]['PRICODE']." - ".$sql_process_priority[$process_priority_i]['PRINAME'];?>
																	<option value='<?=$sql_process_priority[$process_priority_i]['PRICODE']?>' <? if($sql_reqid[0]['PRICODE'] == $sql_process_priority[$process_priority_i]['PRICODE'] or $sql_reqid[0]['PRICODE'] == 3) { $tpcr = $sql_process_priority[$process_priority_i]['PRICODE']; ?> selected <? } ?>><?=$txt_pri?></option>
															<? } ?>
														</select>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Process Priority -->

                                            	<!-- Your Remarks -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Your Remarks </label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <textarea class="form-control" tabindex="26" name='txt_remarks' id='txt_remarks' maxlength="200" style='width:100%; text-transform:uppercase; height:75px;' required><? if($_SESSION['tcs_empsrno'] == '125' and $sql_approve_leads[0]['APPFVAL'] > 0) { ?>BUDGET HEADS ARE OK<? } else { ?>APPROVED<? } ?></textarea>
														<span style='color:#FF0000; font-size:10px;'>NOTE : Maximum 200 Characters Allowed..</span>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Your Remarks -->
                                            </div>
                                            <div class="tags_clear"></div>

                                        </div>
                                          <!-- Your Remarks & Process Priority -->

                                    </div>

                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th style="background:#1caf9a !important;color:#fff">S.NO</th>
                                          <th style="background:#1caf9a !important;color:#fff">TYPE OF SUBMISSION</th>
                                          <th style="background:#1caf9a !important;color:#fff">TARGET NUMBER</th>
                                          <th style="background:#1caf9a !important;color:#fff">SUBJECT</th>
                                          <th style="background:#1caf9a !important;color:#fff">TOP CORE</th>
                                          <th style="background:#1caf9a !important;color:#fff">SUB CORE</th>
                                          <th style="background:#1caf9a !important;color:#fff">EMPLOYEE</th>
                                        </tr>
                                      </thead>
                                      <tbody>

                  												<?
                  												$sql_descode=select_query_json("select distinct ATYCODE , TARNUMB , APMNAME , TOPCORE , SUBCORE , ENTSRNO from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST");
                  													$sno = '';
                  													foreach($sql_descode as $sectionrow) {
                  														$sno = $sno + 1;
                  												?>
                  												<tr class="active">
                  													<td><?echo $sno;?></td>
                  													<td><?
                  													$sql_descodee=select_query_json("SELECT ATYCODE , ATYNAME FROM APPROVAL_TYPE WHERE DELETED = 'N' and ATYCODE = '".$sectionrow['ATYCODE']."' ORDER BY ATYCODE", "Centra", "TCS");
                  														foreach($sql_descodee as $sectionroww) {
                  															$id = $sectionroww['ATYCODE'];
                  															if ($id == '1') {
                  																echo ltrim($sectionroww['ATYNAME'],"FIXED ");
                  															}else {
                  																echo $sectionroww['ATYNAME'];
                  															}
                  														}
                  													?></td>
                  													<td><?
                  													if ($sectionrow['TARNUMB'] == '0') {
                  														echo "- NILL -";
                  													}
                  													else {
                  														$sql_descode=select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
                  																from budget_planner_branch bpl, non_purchase_target tar, department_asset Dep
                  																where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode and tar.brncode=bpl.brncode and tar.PTNUMB=bpl.TARNUMB and bpl.TARYEAR=17 and bpl.TARMONT=4 and (bpl.tarnumb>8000 or bpl.tarnumb in (7632, 7630))
                  																group by bpl.tarnumb, bpl.depcode, bpl.brncode, tar.ptdesc, dep.depname order by Depname", "Centra", "TCS");
                  														foreach($sql_descode as $sectionroww) {
                  															if ($sectionrow['TARNUMB'] == $sectionroww['TARNUMB']) {
                  																echo $sectionroww['DEPNAME'];
                  															}
                  														}
                  													}?></td>
                  													<td><?echo $sectionrow['APMNAME'];?></td>
                  													<td><?
                  													if ($sectionrow['TOPCORE'] == '0') {
                  														echo "- NILL -";
                  													}else {
                  														$sql_descode=select_query_json("SELECT ATCNAME from APPROVAL_TOPCORE where ATCCODE = '".$sectionrow['TOPCORE']."' and DELETED = 'N' ORDER BY ATCSRNO", "Centra", "TCS");
                  														foreach($sql_descode as $sectionrowe) {
                  															echo $sectionrowe['ATCNAME'];
                  														}
                  													}
                  													?></td>
                  													<td><?
                  													if ($sectionrow['SUBCORE'] == '0') {
                  														echo "- NILL -";
                  													}else {
                  														$sql_descode=select_query_json("select distinct sec.esecode, substr(sec.esename, 4, 25) esename
                  																from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec
                  																where ESECODE = '".$sectionrow['SUBCORE']."' and sec.esecode = apm.subcore and apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N'
                  																order by ESENAME asc", "Centra", "TCS");
                  														foreach($sql_descode as $sectionroww) {
                  															echo $sectionroww['ESENAME'];
                  														}
                  													}
                  													?></td>

                  													<td>
                  														<?
                  															$sql_descode=select_query_json("select  EMPCODE , EMPNAME from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' AND ENTSRNO = '".$sectionrow['ENTSRNO']."'", "Centra", "TEST");
                  															foreach($sql_descode as $sectionrow_emp) {
                  																if ($sectionrow_emp['EMPCODE'] == '0') {
                  																	echo "- NILL -";
                  																}else {
                  																	echo $sectionrow_emp['EMPCODE']. " - " .$sectionrow_emp['EMPNAME'];
                  																}
                  																?>
                  																	<BR>
                  																<?
                  															}
                  														?></td>
                  												</tr>
                  												<?

                  													}
                  												?>
                  										</tbody>
                                    </table>
                                <div class="tags_clear"></div>

                                </div>
                                <div class="tags_clear"></div>


                        <!-- Supplier Quotation -->
                        <div id='id_supplier' style="margin: 0px 5px; text-align: center;">
                        <div class="parts3 fair_border">
                        <? 	if(count($sql_prdlist) > 0) { ?>
                            <div class="row" style="margin-right: -5px; min-height: 25px; text-transform: uppercase; background-color: #666666; color:#e2f5ff;  border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
                                <div class="col-sm-1 colheight" style="padding: 0px; border-top-left-radius:5px;">&nbsp;#</div>
                                <div class="col-sm-3 colheight" style="padding: 0px;">Product / Sub Product / Spec. / Image</div>
                                <div class="col-sm-3 colheight" style="padding: 0px;">Advt. Product Details</div>
                                <div class="col-sm-1 colheight" style="padding: 0px;">Qty</div>
                                <div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>
                                <div class="col-sm-1 colheight" style="padding: 0px;">Tax</div>
                                <div class="col-sm-1 colheight" style="padding: 0px;">Discount % </div>
                                <div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>
                            </div>
                            <?  }

                                $inc = 0;
                                foreach($sql_prdlist as $prdlist) { $inc++;
                                    $sql_slt_prdquotlist = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
                                                                                    where PBDCODE = '".$prdlist['PBDCODE']."' and PBDYEAR = '".$prdlist['PBDYEAR']."'
                                                                                        and PBDLSNO = '".$prdlist['PBDLSNO']."' and SLTSUPP = 1", "Centra", 'TEST');
                                    // GST VALUE GET :
									$sql_gst = select_query_json("Select CGSTPER,SGSTPER,IGSTPER from trandata.product_asset_gst_per@tcscentr
																			where prdcode = '".$prdlist['PRDCODE']."' and subcode = '".$prdlist['SUBCODE']."' and rownum = 1", "Centra", 'TEST');

									$sql_gst_n = select_query_json("select * from trandata.subproduct_asset@tcscentr
																			where prdcode='".$prdlist['PRDCODE']."' and subcode='".$prdlist['SUBCODE']."' and rownum =1", "Centra", 'TEST');
									if(count($sql_gst) > 0) {
										$val1 = $sql_gst[0]['SGSTPER'];
										$val2 = $sql_gst[0]['CGSTPER'];
										$val3 = $sql_gst[0]['IGSTPER'];
									} elseif($sql_tax_n[0]['PRDTAX'] == "N") {
										$val1 = 0;
										$val2 = 0;
										$val3 = 0;
									} ?>
                                    <div class="row part3" style="margin-right: -5px; min-height: 25px; display: flex; background-color: #FFFFFF; text-transform: uppercase;">
                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                            <div class="fg-line">&nbsp;<?=$inc?></div>
                                            <div style="clear: both;"></div>
                                            <div>
                                                <!-- Product Image -->
                                                <div><?
                                                  echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                    $dataurl = $prdlist['PBDYEAR'];
                                                    $filename = strtolower($prdlist['PRDIMAG']);
                                                    switch(strtolower(find_indicator_fromfile($prdlist['PRDIMAG'])))
                                                    {
                                                        case 'i':
                                                                $folder_path = "approval_desk/product_images/".$dataurl."/";
                                                                $thumbfolder_path = "approval_desk/product_images/".$dataurl."/thumb_images/";

                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' class=\"img-responsive style_box\" style=\"padding: 2px 5px;\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
                                                                break;
                                                        case 'n':
                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                break;
                                                        case 'w':
                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                break;
                                                        case 'e':
                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                break;
                                                        case 'p':
                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                break;
                                                        default:
                                                                echo $fieldindi = '';
                                                                break;
                                                    }
                                                  // }
                                                  echo "</ul>"; ?>
	                                            </div>
	                                            <div style="clear: both;"></div>
                                                <!-- Product Image -->
                                            </div>
                                        </div>

                                        <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                            <div style="clear: both;"></div>
                                            <div style="width: 100%; float: left; margin-left: 2px; text-align: left">
												Product : <?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?>
                                                <input type="hidden" name="txt_prdcode[]" id="txt_prdcode_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?>" required="required" maxlength="100" placeholder="Product" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty(<?=$inc?>)" style=" text-transform: uppercase; padding: 0px;height: 25px;">
                                            </div>
                                            <div style="width: 100%; float: left;margin-left: 2px; text-align: left">
                                                <input type="hidden" name="txt_pbdlsno[]" id="txt_pbdlsno_<?=$inc?>" value="<?=$prdlist['PBDLSNO']?>">
                                                <input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_<?=$inc?>" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" value="<?=$prdlist['USESECT']?>">
                                                Sup Product : <?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?>
                                                <input type="hidden" name="txt_subprdcode[]" id="txt_subprdcode_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?>" maxlength="100" placeholder="Sub Product" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Sub Product" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty(<?=$inc?>)" style=" text-transform: uppercase;height: 25px;">

                                                <input type="hidden" onKeyPress="enable_product();" name="txt_unitname[]" id="txt_unitname_<?=$inc?>" readonly="readonly" value="<?=$prdlist['UNTNAME']?>" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_<?=$inc?>" value="<?=$prdlist['UNTCODE']?>" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                            </div>
                                            <div style="clear: both; height: 1px;"></div>

                                            <div style=" margin-left: 2px; text-align: left">
                                            	Spec. : <?=$prdlist['PRDSPEC']?>
                                                <input type="hidden" name="txt_prdspec[]" id="txt_prdspec_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['PRDSPEC']?>" required="required" maxlength="100" placeholder="Product Specification" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty(<?=$inc?>)" style=" text-transform: uppercase;height: 25px;">
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>

                                        <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                            <div style="width: 49%; float: left;">
						            			<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "Ad. Duration : ".$prdlist['ADURATI']; } ?>
                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_<?=$inc?>" value="<?=$prdlist['ADURATI']?>" required="required" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                            </div>
                                            <div style="width: 49%; float: left; margin-left: 2px;">
												<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "Ad. Print Location : ".$prdlist['ADLOCAT']; } ?>
                                                <input type="hidden" name="txt_print_location[]" id="txt_print_location_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['ADLOCAT']?>" required="required" maxlength="25" placeholder="Ad. Print Location" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Ad. Print Location" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                            </div>
                                            <div style="clear: both;"></div>

                                            <div style="width: 49%; float: left;">
												<? if($prdlist['ADLENGT'] == '0') { echo ""; } else { echo "Ad. Size Length : ".$prdlist['ADLENGT']; } ?>
                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_<?=$inc?>" value="<?=$prdlist['ADLENGT']?>" required="required" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                            </div>
                                            <div style="width: 49%; float: left; margin-left: 2px;">
												<? if($prdlist['ADWIDTH'] == '0') { echo ""; } else { echo "Ad. Size width : ".$prdlist['ADWIDTH']; } ?>
                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_<?=$inc?>" value="<?=$prdlist['ADWIDTH']?>" required="required" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                            </div>
                                            <div style="clear: both;"></div>
                                        </div>

                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                            <input type="text" onKeyPress="enable_product();" return isNumber(event)" name="txt_prdqty[]" id="txt_prdqty_<?=$inc?>" value="<?=$prdlist['TOTLQTY']?>" required="required" maxlength="6" placeholder="Qty" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                        </div>

                                        <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px; text-align: center; padding-left: 2px;" id="id_sltrate_<?=$inc?>">
                                            <?=$sql_slt_prdquotlist[0]['PRDRATE']?>
                                        </div>
                                        <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
                                            <div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['SGSTVAL']?> </div>
                                            <div style="clear: both;"></div>
                                            <div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['CGSTVAL']?> </div>
                                            <div style="clear: both;"></div>
                                            <div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['IGSTVAL']?> </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
                                            <div style="float: left; width: 50%; text-align: right;">SPL.DIS. : </div><div style="float: left; width: 50%;" id="id_sltslds_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['SPLDISC']?> </div>
                                            <div style="clear: both;"></div>
                                            <div style="float: left; width: 50%; text-align: right;">PCELES. : </div><div style="float: left; width: 50%;" id="id_sltpcls_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['PIECLES']?> </div>
                                            <div style="clear: both;"></div>
                                            <div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC. : </div><div style="float: left; width: 50%;" id="id_sltdisc_<?=$inc?>"> <?=$sql_slt_prdquotlist[0]['DISCONT']?> </div>
                                            <div style="clear: both;"></div>
                                        </div>
                                        <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px; text-align: center; padding-left: 2px;" id="id_sltamnt_<?=$inc?>">
                                            <?=$sql_slt_prdquotlist[0]['NETAMNT']?>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-right: -5px; min-height: 25px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
                                        <div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>
                                        <!-- Quotation -->
                                        <div class="col-sm-10 colheight" style="padding: 0px; min-height: 25px; border-top-left-radius:5px;">
                                            <div class="fair_border" style="padding-left: 0px;">
                                                <div class="row" style="margin-right: -10px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">#</div>
                                                    <div class="col-sm-3 colheight" style="padding: 0px;">Supplier Details</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Delivery Duration</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Per Piece Rate / Adv. Amount</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Tax Val.</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Discount %</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>
                                                    <div class="col-sm-1 colheight" style="padding: 0px;">Quotation PDF</div>
                                                    <div class="col-sm-2 colheight" style="padding: 0px;">Remarks</div>
                                                </div>
                                            </div>
                                            <!-- Quotation -->
                                        </div>
                                        <div class="col-sm-1 colheight" style="padding: 0px; border: 1px solid #FFFFFF !important; background-color: #FFFFFF; border-top-left-radius:5px;"></div>
                                    </div>

                                    <div class="row" style="margin-right: -5px; min-height: 25px; text-transform: uppercase; display: flex; background-color: #FFFFFF; text-transform: uppercase;">
                                        <div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>
                                        <div class="col-sm-10 colheight" style="padding-left: 0px;">
                                            <!-- Quotation -->
                                            <div class="parts3_1 fair_border">
                                                <?  $sql_prdquotlist = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
                                                                                                where PBDCODE = '".$prdlist['PBDCODE']."' and PBDYEAR = '".$prdlist['PBDYEAR']."'
                                                                                                    and PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', 'TEST');
                                                    $inc1 = 0;
                                                    foreach($sql_prdquotlist as $prdquotlist) { $inc1++;
                                                    	// GST CALCULATION :
														$val1 = round($val1*$prdquotlist['PRDRATE']/100,4);
														$val2 = round($val2*$prdquotlist['PRDRATE']/100,4);
														$val3 = round($val3*$prdquotlist['PRDRATE']/100,4);

														if($prdquotlist['IGSTVAL'] != 0) {
															$igstval = $val3;
															$sgstval = 0;
															$cgstval = 0;
														} else {
															$sgstval = $val1;
															$cgstval = $val2;
															$igstval = 0;
														}

                                                        $selected_supplier = ""; $slttext = '';
                                                        if($prdquotlist['SLTSUPP'] == 1) {
                                                            // $selected_supplier = "background-color: #fff2e0; border: 1px solid #FF0000;";
                                                            // $slttext = 'Selected Supplier';
                                                        }

                                                        $gridclr = "#e6e6e6";
                                                        if($inc1 % 2 == 0) { $gridclr = "#f7f7f7"; }
                                                        if($prdquotlist['SLTSUPP'] == 1) { $gridclr = "#fff2e0"; }
                                                        $prd_sgst = (($prdquotlist['SGSTVAL'] / $prdquotlist['PRDRATE']) * 100);
                                                        $prd_cgst = (($prdquotlist['CGSTVAL'] / $prdquotlist['PRDRATE']) * 100);
                                                        $prd_igst = (($prdquotlist['IGSTVAL'] / $prdquotlist['PRDRATE']) * 100);
                                                        ?>
                                                        <div class="row" style="margin-right: -10px; background-color: <?=$gridclr?>; display: flex; <?=$selected_supplier?>" onMouseover="this.style.background='#d0cfcf'; this.style.color='#000000';" onmouseout="this.style.backgroundColor='<?=$gridclr?>'; this.style.color='#000000';">
                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <div class="fg-line">
																&nbsp;<input type="radio" onKeyPress="enable_product();" <? if($prdquotlist['SLTSUPP'] == 1) { ?> checked="checked" <? } ?> value='<?=$inc1?>' onclick="getrequestvalues(<?=$inc?>, <?=$inc1?>, <?=count($sql_prdquotlist)?>)" name="txt_sltsupplier[<?=$inc?>][]" id='txt_sltsupplier_<?=$inc?>_<?=$inc1?>' data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;<?=$inc1?></b>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                                            	<?=$prdquotlist['SUPCODE']." - ".$prdquotlist['SUPNAME']?>
                                                                <input type="hidden" name="txt_sltsupcode[<?=$inc?>][]" id="txt_sltsupcode_<?=$inc?>_<?=$inc1?>" onblur="find_tags();" value="<?=$prdquotlist['SUPCODE']." - ".$prdquotlist['SUPNAME']?>" onKeyPress="enable_product();" required="required" maxlength="100" placeholder="Supplier" onBlur="validate_supprdempty(<?=$inc?>, <?=$inc1?>)" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" style=" text-transform: uppercase;height: 25px;">
                                                                <input type="hidden" name="txt_prlstsr[<?=$inc?>][]" id="txt_prlstsr_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['PRLSTSR']?>">

                                                            </div>

                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
																<?=$prdquotlist['DELPRID']?>
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" onblur="find_tags();" name="txt_delivery_duration[<?=$inc?>][]" id="txt_delivery_duration_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['DELPRID']?>" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                            </div>

                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate[<?=$inc?>][]" id="txt_prdrate_<?=$inc?>_<?=$inc1?>" placeholder="Product Per Piece Rate" value="<?=$prdquotlist['PRDRATE']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">Adv.Amount Val.:
                                                                <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount[<?=$inc?>][]" id="txt_advance_amount_<?=$inc?>_<?=$inc1?>" required="required" maxlength="10" placeholder="Advance Amount Value" value="<?=$prdquotlist['ADVAMNT']?>" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                            </div>

                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdsgst[<?=$inc?>][]" id="txt_prdsgst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['SGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">SGST Value : <?=$prdquotlist['SGSTVAL']?>
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdcgst[<?=$inc?>][]" id="txt_prdcgst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['CGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">CGST Value : <?=$prdquotlist['CGSTVAL']?>
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdigst[<?=$inc?>][]" id="txt_prdigst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['IGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">IGST Value : <?=$prdquotlist['IGSTVAL']?>
                                                            </div>


                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc[<?=$inc?>][]" id="txt_spldisc_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['SPLDISC']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless[<?=$inc?>][]" id="txt_pieceless_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['PIECLES']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc[<?=$inc?>][]" id="txt_prddisc_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['DISCONT']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount[<?=$inc?>][]" id="hid_prdnetamount_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['NETAMNT']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" <? if($prdquotlist['SLTSUPP'] == 1) { ?> class="form-control supquot ttlcalc" <? } else { ?> class="form-control supquot" <? } ?> style=" text-transform: uppercase;height: 25px;">
                                                            </div>

                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_<?=$inc?>_<?=$inc1?>"><?=$prdquotlist['NETAMNT']?></div>

                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <!-- Uploaded Image -->
                                                            	<? // $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'othersupdocs'"); ?>
                                                                <div class='clear clear_both' style='min-height:10px;'></div>
                                                                <div><?
                                                                  echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                    // / * for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    // $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    // $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    // $exp = explode("_", $filename); * /
                                                                    $dataurl = $prdlist['PBDYEAR'];
                                                                    $filename = strtolower($prdquotlist['QUOTFIL']);
                                                                    switch(strtolower(find_indicator_fromfile($prdquotlist['QUOTFIL'])))
                                                                    {
                                                                        case 'i':
                                                                                $folder_path = "approval_desk/product_quotation/".$dataurl."/";
                                                                                $thumbfolder_path = "approval_desk/product_quotation/".$dataurl."/thumb_images/";

                                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' class=\"img-responsive style_box\" style=\"padding: 2px 5px;\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
                                                                                break;
                                                                        case 'n':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_quotation/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_quotation/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_quotation/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_quotation/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        default:
                                                                                echo $fieldindi = '';
                                                                                break;
                                                                    }
                                                                  // }
                                                                  echo "</ul>"; ?>
                                                                </div>
                                                                <div class='clear clear_both'></div>
                                                                <!-- Uploaded Image -->
                                                            </div>

                                                            <div class="col-sm-2 colheight" style="padding: 1px 0px;">
                                                            	<?=$prdquotlist['SUPRMRK']?>
                                                            	<input type="hidden" readonly="readonly" onKeyPress="enable_product(); return isNumber(event)" name="txt_suprmrk[<?=$inc?>][]" id="txt_suprmrk_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['SUPRMRK']?>">

                                                                <? /* <textarea onKeyPress="enable_product();" name="txt_suprmrk[<?=$inc?>][]" id="txt_suprmrk_<?=$inc?>_<?=$inc1?>" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();"class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"><?=$prdquotlist['SUPRMRK']?></textarea> */ ?>
                                                            </div>
                                                        </div>
                                                    <? } ?>
                                            </div>
                                            <!-- Quotation -->

                                        </div>
                                        <div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>
                                    </div>
                                    <? } ?>
		                        </div>
		                        <div class='clear clear_both'></div>
		                    </div>
                            <!-- Supplier Quotation -->
                            <div class="tags_clear"></div>

                            </div>
                            <div class="panel-footer">
                                <div class="col-lg-12 col-md-12" style="clear:both; border:0px solid red; clear:both; display:block;padding-top: 0px;position: absolute;" id="submit_fun" >
								<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
									<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
										<input type="hidden" name="urlstatus" id="urlstatus" value='<?=$urlstatus?>' />
										<input type="hidden" name="previous_urlpath" id="previous_urlpath" value='<?=$previous_url?>' />
										<input type="hidden" name="next_urlpath" id="next_urlpath" value='<?=$next_url?>' />
										<input type="hidden" name="hid_action" id="hid_action" />
										<input type='hidden' name='hid_arqpcod' id='hid_arqpcod' value='<?=$sql_reqid[0]['ARQPCOD']?>'>
										<input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
										<input type='hidden' name='slt_fixbudget_planner' id='slt_fixbudget_planner' value='<?=$sql_reqid[0]['BDPLANR']?>'>
										<input type='hidden' name='hid_reqqid' id='hid_reqqid' value='<?=$sql_reqid[0]['IMUSRIP']?>'>
										<input type='hidden' name='hid_year' id='hid_year' value='<?=$_REQUEST['year']?>'>
										<input type='hidden' name='hid_typeid' id='hid_typeid' value='<?=$_REQUEST['typeid']?>'>
										<input type='hidden' name='hid_creid' id='hid_creid' value='<?=$_REQUEST['creid']?>'>
										<input type='hidden' name='hid_rsrid' id='hid_rsrid' value='<?=$arsrno?>'>
										<input type='hidden' name='hid_original_rsrid' id='hid_original_rsrid' value='<?=$rsrid?>'>
										<? if($arsrno == $rsrid) { $samearqsrno = 1; } else { $samearqsrno = 0; } ?>
										<input type='hidden' name='hid_samearqsrno' id='hid_samearqsrno' value='<?=$samearqsrno?>'>
										<input type='hidden' name='hid_int_verification' id='hid_int_verification' value='<?=$intverify?>'>

										<?
										//////////////////////////// CLOSE THIS ////////////////////////////
										if(count($sql_reqid) > 0) {
											$sql_search = select_query_json("select ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APRQVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR,
																					ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N','NEW','F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY',
																					'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3',
																					'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1
																					and DELETED = 'N' and ARQCODE = ar.ARQCODE and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and APPSTAT = 'A') as APSTAT,
																					(select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office
																					where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQSRNO = 1 and
																					ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and deleted = 'N')) as reqby
																				from APPROVAL_REQUEST ar
																				where ar.DELETED = 'N' and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' or ar.INTPEMP = '".$_SESSION['tcs_empsrno']."')
																					and ( APPFRWD = 'F' or APPFRWD = 'I' ) and ar.APPSTAT in ('N') ".$mr_ak_date0."
																				order by APPORDER Asc, ar.APRQVAL Desc", "Centra", "TEST");
										if(count($sql_search) <= 0) {
											$sql_search = select_query_json("select ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APRQVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR,
																					ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N','NEW','F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY',
																					'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A',
																					'3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST
																					where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and
																					APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME
																					from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQSRNO = 1
																					and ATCCODE = ar.ATCCODE and ARQYEAR = ar.ARQYEAR and ATYCODE = ar.ATYCODE and deleted = 'N')) as reqby
																				from APPROVAL_REQUEST ar
																				where ar.DELETED = 'N' and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' or ar.INTPEMP = '".$_SESSION['tcs_empsrno']."')
																					and ( APPFRWD = 'F' or APPFRWD = 'I' ) and ar.APPSTAT in ('N') ".$mr_ak_date0."
																				order by APPORDER Asc, ar.APRQVAL Desc", "Centra", "TEST");
										}

										$next_url = "http://$_SERVER[HTTP_HOST]/approval_desk/".$rturl;
										$previous_url = "http://$_SERVER[HTTP_HOST]/approval_desk/".$rturl;
										for($searchi = 0; $searchi < count($sql_search); $searchi++) {
											if($sql_search[$searchi]['APRNUMB'] == $sql_reqid[0]['APRNUMB'] and $sql_search[$searchi]['APPORDER'] == 1)
											{
												$searchii = $searchi + 1;
												if($sql_search[$searchii]['APRNUMB'] != '' and $sql_search[$searchii]['APPORDER'] == 1)
													$next_url = 'view_waiting_approval_live.php?action=view&urlstatus=reports&reqid='.$sql_search[$searchii]['ARQCODE'].'&year='.$sql_search[$searchii]['ARQYEAR'].'&rsrid='.$sql_search[$searchii]['ARQSRNO'].'&creid='.$sql_search[$searchii]['ATCCODE'].'&typeid='.$sql_search[$searchii]['ATYCODE'];

												$searchiii = $searchi - 1;
												if($sql_search[$searchiii]['APRNUMB'] != '' and $sql_search[$searchiii]['APPORDER'] == 1)
													$previous_url = 'view_waiting_approval_live.php?action=view&urlstatus=reports&reqid='.$sql_search[$searchiii]['ARQCODE'].'&year='.$sql_search[$searchiii]['ARQYEAR'].'&rsrid='.$sql_search[$searchiii]['ARQSRNO'].'&creid='.$sql_search[$searchiii]['ATCCODE'].'&typeid='.$sql_search[$searchiii]['ATYCODE'];
											}
										}
										$intverify = $sql_reqid[0]['APPFRWD'];

										// APPROVAL FINISH OPTION
										$projid = array("13", "14", "15", "16", "19", "26", "27", "28", "29", "30", "4"); // PROJECT ID (5 Airport (CBE, HYD, MUM, MDU, CHN), Tailyou, Online, ZF, Kanmani, Clean Today) - Must Finish by Mr. AK Sir
										if(in_array($sql_reqid[0]['APRCODE'], $projid) and $sql_reqid[0]['PRJPRCS'] == 'F') {
											$finish_here = 0; $final_approval = 0;
										}
										if($sql_reqid[0]['APRQVAL'] >= 100000) {
											$finish_here = 0; $final_approval = 0;
										}
										// APPROVAL FINISH OPTION
										?>
										<? 	$open = 1;

										if($open == 1 and $mdaction == 'md') { // 20072016
										// if($open == 1) {
											// Approval Desk Peoples Forward to S-Team / Legal Team
										   if($_SESSION['tcs_user'] == $appdesk) { ?>
											<input type='radio' name='steam_legal' id='steam_legal' checked value='2'>&nbsp;Next Level User&nbsp;&nbsp;<input type='radio' name='steam_legal' id='steam_legal' value='1'>&nbsp;Cost Control<? // &nbsp;&nbsp;<input type='radio' name='steam_legal' id='steam_legal' value='0'>&nbsp;Legal Team ?>
										<? }
										   // Approval Desk Peoples Forward to S-Team / Legal Team ?>

										<? if($_REQUEST['action'] == 'view') {

											if(($sql_reqid[0]['REQSTFR'] == $sql_reqid[0]['RQESTTO']) or ($sql_reqid[0]['INTPEMP'] == $sql_reqid[0]['RQESTTO']))
											{
												$final_approval = 1;
											}

											if($sql_reqid[0]['APPFRWD'] != 'Q' and $sql_reqid[0]['APPFRWD'] != 'P') {
												if($_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 169 or $_SESSION['tcs_descode'] == 132 or $_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_empsrno'] == 61579 or $_SESSION['tcs_empsrno'] == 59006 or $_SESSION['tcs_empsrno'] == 188 or $_SESSION['tcs_empsrno'] == 34593 or $_SESSION['tcs_empsrno'] == 2158 or $_SESSION['tcs_empsrno'] == 1746) { }

												if($final_approval == 1 and $mdfin == 0) { } // Final Approval.. ?>

											<? ////////////////// if($final_approval == 0) { /* // Not Final Approval..
												if($sql_reqid[0]['APPFRWD'] == 'I') { // For Internal Verification ?>
													<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve"><i class="fa fa-fast-forward"></i>
													Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve
													<!-- Response -->
													</button>&nbsp;&nbsp;
												<? } // Not Final Approval.. ?>
										<?
										} else { ?>
											<button type="submit" name='sbmt_response' id='sbmt_response' tabindex='28' value='Response' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Response"><i class="fa fa-fast-forward"></i> Response</button>
											<? } ?>
										<? }
										elseif($_REQUEST['action'] == 'edit') { } else {
											if(count($sql_reqid) == 0) { } else { } } } else { } ?>

										<div class='clear clear_both'></div>
										<? } ?>

										<? if($sql_reqid[0]['APPFRWD'] != 'I' and $sql_reqid[0]['APPFRWD'] != 'Q' and $sql_reqid[0]['APPFRWD'] != 'P') { ?>
											<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Update' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-fast-forward"></i> Update</button>&nbsp;&nbsp;<a href="javascript:window.close();" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Back"><i class="fa fa-times"></i> Back</a>
										<? }
										//////////////////////////// CLOSE THIS //////////////////////////// ?>
									</div>
								<div class='clear clear_both'></div>
								</div>
								<div class='clear clear_both'>&nbsp;</div>

                            <div class="tags_clear"></div>
							</div>
                            <div class="tags_clear"></div>
                            </div>
                            <div class="tags_clear"></div>
                        </div>
                        </form>

                    </div>
                </div>

            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

<!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>

    <!-- END PLUGINS -->

    <!-- START THIS PAGE PLUGINS-->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>

    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- END TEMPLATE -->

    <!-- Light Box -->
    <link href="css/ekko-lightbox.css" rel="stylesheet">
    <!-- yea, yea, not a cdn, i know -->
    <script src="js/ekko-lightbox-min.js"></script>

    <link href="css/lightgallery.css" rel="stylesheet">
    <script src="js/picturefill.min.js"></script>
    <script src="js/lightgallery.js"></script>
    <script src="js/lg-fullscreen.js"></script>
    <script src="js/lg-thumbnail.js"></script>
    <script src="js/lg-video.js"></script>
    <script src="js/lg-autoplay.js"></script>
    <script src="js/lg-zoom.js"></script>
    <script src="js/lg-hash.js"></script>
    <script src="js/lg-pager.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.lightgallery').lightGallery();
    });

    $(function() {
        var showTotalChar = 200, showChar = "Show (+)", hideChar = "Hide (-)";
        $('.show_moreless').each(function() {
            var content = $(this).text();
            if (content.length > showTotalChar) {
                var con = content.substr(0, showTotalChar);
                var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                var txt= '<b>'+con +  '</b><span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" class="showmoretxt">' + showChar + '</a></span>';
                $(this).html(txt);
            }
        });
        $(".showmoretxt").click(function() {
            if ($(this).hasClass("sample")) {
                $(this).removeClass("sample");
                $(this).text(showChar);
            } else {
                $(this).addClass("sample");
                $(this).text(hideChar);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });


	$(document).ready(function() {
		$(".chosn").customselect();
		dynamic_template_load('<?=$_REQUEST['reqid']?>','<?=$_REQUEST['year']?>','<?=$_REQUEST['rsrid']?>','<?=$_REQUEST['creid']?>','<?=$_REQUEST['typeid']?>');
		$('#txt_suppliercode').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url : 'ajax/get_supplier_details.php',
					dataType: "json",
					data: {
					   name_startsWith: request.term,
					   action: 'supplier_details'
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
							return {
								label: item,
								value: item
							}
						}));
					}
				});
			},
			autoFocus: true,
			minLength: 0
		});
	});

	function calculatenetamount(opt1,opt2){
		var txt_prdrate = document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value;
		if(txt_prdrate==''){
			txt_prdrate = 0;
		}
		var txt_prdsgst = document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value;
		if(txt_prdsgst==''){
			txt_prdsgst = 0;
		}
		var txt_prdcgst = document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value;
		if(txt_prdcgst==''){
			txt_prdcgst = 0;
		}
		var txt_prdigst = document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value;
		if(txt_prdigst==''){
			txt_prdigst = 0;
		}
		var txt_prddisc = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
		if(txt_prddisc==''){
			txt_prddisc = 0;
		}

		var txt_spldisc = document.getElementById('txt_spldisc_'+opt1+'_'+opt2).value;
		if(txt_spldisc==''){
			txt_spldisc = 0;
		}
		var txt_pieceless = document.getElementById('txt_pieceless_'+opt1+'_'+opt2).value;
		if(txt_pieceless==''){
			txt_pieceless = 0;
		}

		var txt_prdqty = document.getElementById('txt_prdqty_'+opt1).value;
		if(txt_prdqty==''){
			txt_prdqty = 0;
		}

		var txt_ad_duration = document.getElementById('txt_ad_duration_'+opt1).value;
		if(txt_ad_duration==''){
			txt_ad_duration = 0;
		}

		var txt_size_length = document.getElementById('txt_size_length_'+opt1).value;
		if(txt_size_length==''){
			txt_size_length = 0;
		}

		var txt_size_width = document.getElementById('txt_size_width_'+opt1).value;
		if(txt_size_width==''){
			txt_size_width = 0;
		}

		/* netamount = + parseFloat(txt_prdrate) + +parseFloat(txt_prdsgst)+ +parseFloat(txt_prdcgst)+ +parseFloat(txt_prdigst);
		netamounttotal=Number(netamount) - Number(txt_prddisc);
		document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(netamounttotal*txt_prdqty);
		document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(netamounttotal*txt_prdqty); */


		// New Calculation - 22-09-2017 // GA
		var rptmode = $("#txt_rptmode").val();
		var slt_subcore = $("#slt_subcore").val();
		var pcless = 0;
		var spldis = 0;
		var prdqty = 0;
		var prdcst = 0;
		/* if(txt_prdqty == 0 || txt_prdqty == '') {
			txt_prdqty = 1;
		} */

		// console.log("!!"+rptmode+"!!");
		/*if(rptmode == 1 || rptmode == 2 || rptmode == 3 || rptmode == 4) { // Non ADVT Exp.
			prdqty = txt_prdqty;
			pcless = txt_pieceless;
			spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(txt_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
		} else if(rptmode == 5 || rptmode == 6) { // ADVT Exp. Ad Flex Exp.
			prdqty = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width);
			pcless = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) * parseFloat(txt_pieceless);
			spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(txt_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
		} else if(rptmode == 7) { // ADVT Exp. Ad Play Duration Exp.
			prdqty = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration);
			pcless = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) * parseFloat(txt_pieceless);
			spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(txt_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
		}*/
		// console.log("@@");

		if(rptmode == 1 || rptmode == 2 || rptmode == 3 || rptmode == 4) { // Non ADVT Exp.
			prdqty = txt_prdqty;
			tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty))/100 * parseFloat(txt_prddisc) ;
			tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
		} else if(rptmode == 5 || rptmode == 6) { // ADVT Exp. Ad Flex Exp.
			prdqty = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width);
			tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width))/100 * parseFloat(txt_prddisc) ;
			tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
			if(txt_size_length != ""  && txt_size_width != "" ){
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
			}else{
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
			}
		} else if(rptmode == 7) { // ADVT Exp. Ad Play Duration Exp.
			prdqty = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration);
			tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration))/100 * parseFloat(txt_prddisc) ;
			tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
			if(txt_ad_duration != "")
			{
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
			}else{
			prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
			}

		}

		prdcst = Math.round(prdcst).toFixed(2);

		document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(prdcst);
		document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(prdcst);

		if($('#txt_sltsupplier_'+opt1+'_'+opt2).is(":checked")) {

			// console.log("**"+txt_prdrate+"**"+prdcst+"**");
			$('#id_sltrate_'+opt1).html(txt_prdrate);
			$('#id_sltsgst_'+opt1).html(txt_prdsgst);
			$('#id_sltcgst_'+opt1).html(txt_prdcgst);
			$('#id_sltigst_'+opt1).html(txt_prdigst);
			$('#id_sltslds_'+opt1).html(txt_spldisc);
			$('#id_sltpcls_'+opt1).html(txt_pieceless);
			$('#id_sltdisc_'+opt1).html(txt_prddisc);
			$('#id_sltamnt_'+opt1).html(prdcst);

			var requestedvalue=0;
			var y = $('.parts30 .part30').length + 1;
			// console.log('##y##'+y+'##'+opt1+'##'+opt2+'##');
			for(var j=1;j<=y;j++){
				var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
				for(var i=0;i<x.length;i++){
					// console.log('**j-'+j+'**i-'+i+'**y-'+y+'**x-'+x.length+'**');
					if(x[i].checked){
						var z = i+1;
						if(document.getElementById('hid_prdnetamount_'+j+'_'+z).value==''){
							document.getElementById('hid_prdnetamount_'+j+'_'+z).value=0;
						}
						requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value)
					}
				}
			}
			document.getElementById('txtrequest_value').value = requestedvalue;
			document.getElementById('hidrequest_value').value = requestedvalue;
			document.getElementById('id_reqvlu').innerHTML = requestedvalue;
			// budget month add

			if(document.getElementById('npobudget'))
            {
                document.getElementById('mnt_yr_amt_0').value = requestedvalue;
                calculate_sum();
            }

			$('.hidn_balance').val(requestedvalue);
			// New Calculation - 22-09-2017 // GA

			var requestedvalue=0;
			var y = $('.parts3 .part3').length + 1;

			for(var j=1;j<=y;j++){
				var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
				for(var i=0;i<x.length;i++){
					if(x[i].checked){
						var z = i+1;
						requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
						// alert("***"+requestedvalue+"***"+j+"***"+z+"****");
					}
				}
			}
			document.getElementById('txtrequest_value').value = requestedvalue;
			document.getElementById('hidrequest_value').value = requestedvalue;

			// budget month add

			if(document.getElementById('npobudget'))
            {
                document.getElementById('mnt_yr_amt_0').value = requestedvalue;
                calculate_sum();
            }

			for(jvi = 1; jvi <= 10; jvi++) {
				// alert("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***");
				// console.log("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***"+opt1+"***"+opt2+"***"+ttlcnt+"***"+jvi+"***");
				$('#hid_prdnetamount_'+opt1+'_'+jvi).attr('class', 'form-control');
			}
			$('#hid_prdnetamount_'+opt1+'_'+opt2).attr('class', 'form-control ttlcalc');

			var requestedvalue = totcalc('ttlcalc');
			// console.log("###"+requestedvalue+"###");
			document.getElementById('txtrequest_value').value = requestedvalue;
			document.getElementById('hidrequest_value').value = requestedvalue;
			// budget month add

			if(document.getElementById('npobudget'))
            {
                document.getElementById('mnt_yr_amt_0').value = requestedvalue;
                calculate_sum();
            }
			$('.hidn_balance').val(requestedvalue);
		}
	}

	function totcalc(clsname){
		var dirq = 0;
		var list = document.getElementsByClassName(clsname);
		var values = [];
		if(list.length > 0) {
			for(var i = 0; i < list.length; ++i) {
				values.push(parseFloat(list[i].value));
			}
			dirq = values.reduce(function(previousValue, currentValue, index, array){
				return previousValue + currentValue;
			});
			// alert(clsname+"+++++++"+dirq);
		} else {
			dirq = 0;
		}
		return dirq;
	}

	function calculateqtyamount(gid){
		var x = document.getElementsByName('txt_sltsupcode['+gid+'][]');
		for(var i=1;i<=x.length;i++){
			calculatenetamount(gid,i);
		}
	}

	function getrequestvalue(opt1, opt2){
		calculatenetamount(opt1, opt2);

		var requestedvalue=0;
		var y = $('.parts30').length + 1;
		for(var j=1;j<=y;j++){
			var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
			for(var i=0;i<x.length;i++){
				if(x[i].checked){
					var z = i+1;
					requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
					// alert("***"+requestedvalue+"***"+j+"***"+z+"****");
				}
			}
		}
		document.getElementById('txtrequest_value').value = requestedvalue;
		document.getElementById('hidrequest_value').value = requestedvalue;
	}

	function getrequestvalues(iv, jv, ttlcnt){
		calculatenetamount(iv, jv);

		for(jvi = 1; jvi <= ttlcnt; jvi++) {
			// alert("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***");
			// console.log("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***"+iv+"***"+jv+"***"+ttlcnt+"***"+jvi+"***");
			$('#hid_prdnetamount_'+iv+'_'+jvi).attr('class', 'form-control');
			$('#redhighlight_'+iv+'_'+jvi).attr('class', 'row');
		}
		$('#hid_prdnetamount_'+iv+'_'+jv).attr('class', 'form-control ttlcalc');
		$('#redhighlight_'+iv+'_'+jv).attr('class', 'row red_highlighter');

		var requestedvalue = totcalc('ttlcalc');
		// console.log("###"+requestedvalue+"###");
		document.getElementById('txtrequest_value').value = requestedvalue;
		document.getElementById('hidrequest_value').value = requestedvalue;
		document.getElementById('id_reqvlu').innerHTML = requestedvalue;
		$('.hidn_balance').val(requestedvalue);
	}

	// validate the product textbox
	function validate_prdempty(iv) {
		var prdcode = $("#txt_prdcode_"+iv).val();
		var strURL="ajax/ajax_validate.php?action=product&validate_code="+prdcode;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data1) {
				if(data1 == 0) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					$("#txt_prdcode_"+iv).val('');
					// $("#txt_prdcode_"+iv).focus();
				}
			}
		});
	}
	// validate the product textbox

	// validate the sub product textbox
	function validate_subprdempty(iv) {
		var sub_prdcode = $("#txt_subprdcode_"+iv).val();
		var strURL="ajax/ajax_validate.php?action=sub_product&validate_code="+sub_prdcode;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data1) {
				if(data1 == 0) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "No Sub Product Available. Kindly Contact Admin Master Team!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					$("#txt_subprdcode_"+iv).val('');
					// $("#txt_subprdcode_"+iv).focus();
				}
			}
		});
		find_unitcode(iv);
	}
	// validate the sub product textbox

	// find the unit code from sub product textbox
	function find_unitcode(iv) {
		var sub_prdcode = $("#txt_subprdcode_"+iv).val();
		var strURL="ajax/ajax_validate.php?action=find_unitcode&validate_code="+sub_prdcode;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data1) {
				if(data1 == 0) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "No Unit code Available. Kindly Contact Admin Master Team!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					$("#txt_unitname_"+iv).val('');
				} else {
					var prd = data1.split(" - ");
					$("#txt_unitname_"+iv).val(prd[1]);
					$("#txt_unitcode_"+iv).val(prd[0]);
				}
			}
		});
	}
	// find the unit code from sub product textbox

	// validate the product specifiction textbox
	function validate_prdspcempty(iv) {
		var spc_prdcode = $("#txt_prdspec_"+iv).val();
		var strURL="ajax/ajax_validate.php?action=prod_spec&validate_code="+spc_prdcode;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data1) {
				if(data1 == 0) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "No Product Specifiction Available. Kindly Contact Admin Master Team!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					$("#txt_prdspec_"+iv).val('');
					// $("#txt_prdspec_"+iv).focus();
				}
			}
		});
	}
	// validate the product specifiction textbox

	// validate the supplier textbox
	function validate_supprdempty(iv, jv) {
		var sup_prdcode = $("#txt_sltsupcode_"+iv+"_"+jv).val();
		var strURL="ajax/ajax_validate.php?action=supplier&validate_code="+sup_prdcode;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data1) {
				if(data1 == 0) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					$("#txt_sltsupcode_"+iv+"_"+jv).val('');
					// $("#txt_sltsupcode_"+iv+"_"+jv).focus();
				}
			}
		});
	}
	// validate the supplier textbox

	//upload Only PDF file
	var _validFileExtensions = [".pdf",".PDF"];
	function ValidateSingleInput(oInput) {
	    if (oInput.type == "file") {
	        var sFileName = oInput.value;
	         if (sFileName.length > 0) {
	            var blnValid = false;
	            for (var j = 0; j < _validFileExtensions.length; j++) {
	                var sCurExtension = _validFileExtensions[j];
	                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
	                    blnValid = true;
	                    break;
	                }
	            }

	            if (!blnValid) {
	               // alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
	               alert("Sorry, Upload Only PDF file Format");
	                oInput.value = "";
	                return false;
	            }
	        }
	    }
	    return true;
	}

	function edit(element) {
		var parent = $(element).parent().parent();
		var placeholder = $(parent).find('.text-info').text();
		//hide label
		$(parent).find('label').hide();
		//show input, set placeholder
		var input = $(parent).find('input[type="text"]');
		var edit = $(parent).find('.controls-edit');
		var update = $(parent).find('.controls-update');
		$(input).show();
		$(edit).hide();
		$(update).show();

		//$(input).attr('placeholder', placeholder);
	}

	function get_advancedetails() {
		var deptid = $("#slt_department_asset").val();
		var approval_listings_id = document.getElementById('slt_approval_listings').value;
		var slt_branch = document.getElementById('slt_brnch_0').value;
		if(slt_branch == 888) { slt_branch = 100;  }
		if(deptid == 100) {
			var strURL="get_advancedetails.php?slt_branch="+slt_branch+"&deptid="+deptid+"&approval_listings_id="+approval_listings_id;
			var req = getXMLHTTP();
			if (req) {
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						if (req.status == 200) {
							document.getElementById('id_advancedetails').innerHTML=req.responseText;
							get_targetdates();
						} else {
							alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						}
					}
				}
				req.open("GET", strURL, true);
				req.send(null);
			}
		}

		var strURL1="ajax/ajax_target_no.php?slt_branch="+slt_branch+"&deptid="+deptid+"&approval_listings_id="+approval_listings_id;
		var req1 = getXMLHTTP();
		if (req1) {
			req1.onreadystatechange = function() {
				if (req1.readyState == 4) {
					if (req1.status == 200) {
						// $("#slt_targetno").select2();
						$.getScript("js/jquery-customselect.js");
						$(document).ready(function() {
							$(".chosn").customselect();
						});
						document.getElementById('id_tarno').innerHTML=req1.responseText;
						$('#slt_targetno').focus();
						get_targetdates();
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
					}
				}
			}
			req1.open("GET", strURL1, true);
			req1.send(null);
		}
	}

	function get_dept(core_deptid) {
		var slt_core_department = $("#slt_core_department").val();
		var slt_submission = $("#slt_submission").val();
		var hidslt_core_department = $("#hidslt_core_department").val();
		var slt_topcore = $("#slt_topcore").val();
		var nw_topcore = slt_topcore;
		var strURL="gettopcore.php?action=find_topcore&slt_core_department="+slt_core_department+"&slt_submission="+slt_submission;
		var req = getXMLHTTP();
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					if (req.status == 200) {
						nw_topcore = req.responseText;
						if(nw_topcore != slt_topcore) {
							$("#submit_fun").hide();

							/* var textToFind = hidslt_core_department;
							var dd = document.getElementById('slt_core_department');
							alert("***"+dd.options.length+"**");
							for (var i = 0; i < dd.options.length; i++) {
							    if (dd.options[i].value === textToFind) {
							        // dd.selectedIndex = i;
									alert("***"+dd.options[i].value+"***"+textToFind+"***"+dd.selectedIndex+"***"+i+"***"+dd.options[i].selected+"***");
							        dd.options[i].selected = true;
									alert("***"+dd.options[i].value+"***"+textToFind+"***"+dd.selectedIndex+"***"+i+"***"+dd.options[i].selected+"***");
							        break;
							    }
							} */

							var ALERT_TITLE = "Message";
							var ALERTMSG = "Topcore is not possible to change at this stage. So Kindly Reject this apprvoal and Process new approval!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							window.location.reload();
						} else {
							$("#submit_fun").show();
							var strURL1="ajax/ajax_get_dept.php?core_deptid="+core_deptid;
							var req1 = getXMLHTTP();
							if (req1) {
								req1.onreadystatechange = function() {
									if (req1.readyState == 4) {
										if (req1.status == 200) {
											// $("#slt_department_asset").select2();
											$.getScript("js/jquery-customselect.js");
											$(document).ready(function() {
												$(".chosn").customselect();
											});
											document.getElementById('id_department').innerHTML=req1.responseText;
											get_advancedetails();
											get_targetdates();
										} else {
											alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
										}
									}
								}
								req1.open("GET", strURL1, true);
								req1.send(null);
							}
						}
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
	}

	function call_days() {
		var cntdays = document.getElementById('hid_noofdays').value;
		var tt = document.getElementById('txtfrom_date1').value;
		var date = new Date(tt);
		var newdate = new Date(date);

		if(cntdays > 1)
		{
			newdate.setDate(newdate.getDate() + parseInt(cntdays));
		} else {
			newdate.setDate(newdate.getDate());
		}

		var dd = newdate.getDate();
		var mm = newdate.getMonth() + 1;
		var y = newdate.getFullYear();

		if(dd < 10)
			dd = '0' + dd;
		if(mm < 10)
			mm = '0' + mm;

		var someFormattedDate = dd + '-' + mm + '-' + y;

		document.getElementById('txtto_date').value = someFormattedDate + ' 12:00:00 AM';
		document.getElementById('txtnoofhours').value = cntdays * 24;
		document.getElementById('txtnoofdays').value = cntdays;
		date_diff();
	}

	function date_diff()
	{
		var date1 = document.getElementById('txtfrom_date').value;
		var date2 = document.getElementById('txtto_date').value;
		//alert(date1+"HI"+date2);

		var datefrom = date1.split(' ');
		var dateto = date2.split(' ');
		//alert(datefrom[0]+"!!!!!!"+dateto[0]); //alert(parseDate(datefrom[0]));

		Date.prototype.days=function(to){
			return  Math.abs(Math.floor( to.getTime() / (3600*24*1000)) -  Math.floor( this.getTime() / (3600*24*1000)))
		}
		var ga = new Date(parseDate(datefrom[0])).days(new Date(parseDate(dateto[0]))) // 3 days
		var cntdate = +ga + 1;
		//var cntdate = ga;
		document.getElementById('txtnoofdays').value = cntdate;
		document.getElementById('txtnoofhours').value = cntdate * 24;
	}

	function parseDate(str) {
		var mdy = str.split('-')
		//alert(mdy[2]+"~~"+mdy[0]+"~~"+mdy[1]);
		return mdy[1]+"-"+mdy[0]+"-"+mdy[2];
		//return new Date(mdy[1], mdy[0], "20"+mdy[2]);
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		// alert(charCode);
		if (charCode > 31 && charCode != 39 && charCode != 34 && charCode != 46 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function isQuotes(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		// alert(charCode);
		if (charCode == 39 || charCode == 34) {
			return false;
		}
		return true;
	}

	function update(element) {
		var parent = $(element).parent().parent();
		var input = $(parent).find('input[type="text"]');
		$('#aprqval_app').val($(input).val());
	}

	function update_save(element) {
		var parent = $(element).parent().parent();
		var placeholder = $(parent).find('.text-info').text();
		//hide label
		$(parent).find('label').show();
		//show input, set placeholder
		var input = $(parent).find('input[type="text"]');
		var edit = $(parent).find('.controls-edit');
		var update = $(parent).find('.controls-update');
		$(input).hide();
		$(edit).show();
		$(update).hide();
		//$(input).attr('placeholder', placeholder);
		$(parent).find('label p').text($(input).val());
		$('#aprqval_app').val($(input).val());
	}

	jQuery(document).ready(function($){
		$(".accordion_example2").smk_Accordion({
			closeAble: true, //boolean
		});
	});

	function calculate_sum(ivalue) {
		var total = 0;
		var $changeInputs = $('input.ttlsum');
		$changeInputs.each(function(idx, el) {
			total += Number($(el).val());
		});
		var ttl_lock = $('#ttl_lock').val();
		// var revertval  = $('#mnt_yr_amt_'+ivalue).val();

		if(parseInt(ttl_lock) >= parseInt(total)) {
			document.getElementById('hidrequest_value').value = total;
			document.getElementById('txtrequest_value').value = total;
			document.getElementById('ttl_mntyr').innerHTML = total;
			document.getElementById('id_reqvlu').innerHTML = total;
		} else {
			var ALERT_TITLE = "Message";
			var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here.";
			createCustomAlert(ALERTMSG, ALERT_TITLE);
			// alert(currentvalue+"**********"+ivalue);
			$('#mnt_yr_amt1_'+ivalue).val(0);
			$('#mnt_yr_amt1_'+ivalue).focus();
		}
	}

	function allow_zero(ivalue, currentvalue, lockvalue) {
		var fstmnth = $('#fstmnth').val();
		var lstmnth = $('#lstmnth').val();
		var mnt_yr  = $('#mnt_yr_'+ivalue).val();
		var mnt_yr_amt  = $('#mnt_yr_amt1_'+ivalue).val();
		var revertval  = $('#mnt_yr_amt_'+ivalue).val();
		var txtrequest_value = $('#txtrequest_value').val();
		var ttl_lock = $('#ttl_lock').val();

		/* // alert(parseInt(ttl_lock)+"-------"+parseInt(txtrequest_value));
		var ALERT_TITLE = "Message";
		var ALERTMSG = "-------"+parseInt(ttl_lock)+"-------"+parseInt(txtrequest_value)+"-------"+revertval+"-------"+currentvalue;
		createCustomAlert(ALERTMSG, ALERT_TITLE); */

		if(parseInt(ttl_lock) < parseInt(txtrequest_value)) {
			var ALERT_TITLE = "Message";
			var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here.";
			createCustomAlert(ALERTMSG, ALERT_TITLE);
			// alert(revertval+"**********"+mnt_yr_amt+"**********"+ivalue);
			$('#mnt_yr_amt1_'+ivalue).val(revertval);
			$('#mnt_yr_amt1_'+ivalue).focus();
		} else {
			/* if(mnt_yr_amt == 0) {
				// alert(mnt_yr_amt+"Zero is not allowed here!!"+ivalue);
				$('#mnt_yr_amt1_'+ivalue).val(revertval);
			} */ // Value 0 revert removed for all users
		}
		calculate_sum(ivalue);
	}

		$(document).ready(function ($) {
			// delegate calls to data-toggle="lightbox"
			$(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
				event.preventDefault();
				return $(this).ekkoLightbox({
					onShown: function() {
						if (window.console) {
							return console.log('Checking our the events huh?');
						}
					},// IMG ROTATE
					onContentLoaded: function() {
						var value = 0
						$(".idrotate").rotate({
							 bind:
							 {
								click: function(){
									value +=90;
									// $('.img-fluid').rotate({ animateTo:value})
									$('.img-responsive').rotate({ animateTo:value})
								}
							 }
						});

						var $section = $('.ekko-lightbox').first();
						$section.find('.ekko-lightbox-container').panzoom({
							$zoomIn: $section.find(".zoom-in"),
							$zoomOut: $section.find(".zoom-out"),
							$reset: $section.find(".reset")
						});
					}, // IMG ROTATE
					onNavigate: function(direction, itemIndex) {
						if (window.console) {
							return console.log('Navigating '+direction+'. Current item: '+itemIndex);
						}
					}
				});
			});



			var subtype_value_id = document.getElementById("slt_subtype").value;
			var project = document.getElementById('slt_project').value;
			var arqcode = document.getElementById('arqcode').value;
			var atycode = document.getElementById('atycode').value;
			var atccode = document.getElementById('atccode').value;
			var arqyear = document.getElementById('arqyear').value;
			var aprnumb = document.getElementById('aprnumb').value;

			var aand = '';
			if (typeof document.frm_request_entry[0].fieldimpl === "undefined") { } else {
				var fieldimpl = document.getElementById('fieldimpl').value;
				aand += "&fieldimpl="+fieldimpl;
			}

			if (typeof document.frm_request_entry[0].othersupdocs === "undefined") { } else {
				var othersupdocs = document.getElementById('othersupdocs').value;
				aand += "&othersupdocs="+othersupdocs;
			}

			if (typeof document.frm_request_entry[0].lastapproval === "undefined") { } else {
			   var lastapproval = document.getElementById('lastapproval').value;
				aand += "&lastapproval="+lastapproval;
			}

			if (typeof document.frm_request_entry[0].quotations === "undefined") { } else {
				var quotations = document.getElementById('quotations').value;
				aand += "&quotations="+quotations;
			}

			if (typeof document.frm_request_entry[0].clrphoto === "undefined") { } else {
				var clrphoto = document.getElementById('clrphoto').value;
				aand += "&clrphoto="+clrphoto;
			}

			var deluser = document.getElementById('deluser').value;
			<?php if($_SESSION['tcs_usrcode'] == '9938358' or $_SESSION['tcs_usrcode'] == '3000000' or $_SESSION['tcs_usrcode'] == '9193333') { ?>
				var strURL="";
				// var strURL="getsubtype_value_edit_md.php?action=view&subtype_value_id="+subtype_value_id+"&project="+project+"&arqcode="+arqcode+"&atycode="+atycode+"&atccode="+atccode+"&aprnumb="+aprnumb+"&arqyear="+arqyear+"&deluser="+deluser+aand;
			<? } else { ?>
				var strURL="getsubtype_value_edit.php?action=view&subtype_value_id="+subtype_value_id+"&project="+project+"&arqcode="+arqcode+"&atycode="+atycode+"&atccode="+atccode+"&aprnumb="+aprnumb+"&arqyear="+arqyear+"&deluser="+deluser+aand;
			<? } ?>
			// alert("***getsubtype_value_edit.php***"+strURL);
			var req = getXMLHTTP();
			if (req) {
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						if (req.status == 200) {
							var resp = req.responseText;
							// document.getElementById('id_subtype_value').innerHTML = resp;
						} else {
							alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						}
					}
				}
				if(strURL != '' ) {
					req.open("POST", strURL, true);
					req.send(null);
				}
			}


			var srno = document.getElementById("brn").value;
			var grpname = document.getElementById("asst").value;
			var depcode = document.getElementById("depcode").value;
			var tarnumb = document.getElementById("slt_targetno").value;
			var strURL="budget_sales_percentage_popupview.php?slt_branch="+srno+"&slt_department_asset="+grpname+"&depcode="+depcode+"&tarnumb="+tarnumb;
			// alert("***budget_sales_percentage_popupview.php***"+strURL);
			var req_view = getXMLHTTP();
			if (req_view) {
				req_view.onreadystatechange = function() {
					if (req_view.readyState == 4) {
						if (req_view.status == 200) {
							var resp = req_view.responseText;
							document.getElementById('budget_view').innerHTML = resp;
						} else {
							alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						}
					}
				}
				req_view.open("POST", strURL, true);
				req_view.send(null);
			}

			//Programatically call
			$('#open-image').click(function (e) {
				e.preventDefault();
				$(this).ekkoLightbox();
			});
			$('#open-youtube').click(function (e) {
				e.preventDefault();
				$(this).ekkoLightbox();
			});

			$(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
				event.preventDefault();
				return $(this).ekkoLightbox({
					onShown: function() {
						var a = this.modal_content.find('.modal-footer a');
						if(a.length > 0) {
							a.click(function(e) {
								e.preventDefault();
								this.navigateTo(2);
							}.bind(this));
						}
					}
				});
			});

		});

        function get_targetdates() {
			var core_deptid = document.getElementById('slt_core_department').value;
			var deptid = document.getElementById('slt_department_asset').value;
			var slt_branch = document.getElementById('slt_brnch_0').value;
			var target_no = document.getElementById('slt_targetno').value;
			var slt_submission = document.getElementById('slt_submission').value;
			if(slt_branch==888){ slt_branch = 100; }
			if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
				$("#getmonthwise_budget").css("display", "block");
				// $('#txtrequest_value').val(0);
				$('#txtrequest_value').attr('readonly', true);
				var strURL="ajax/ajax_get_targetdt.php?action=show_budgetvalue&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission;
				var req1 = getXMLHTTP();
				if (req1) {
					req1.onreadystatechange = function() {
						if (req1.readyState == 4) {
							if (req1.status == 200) {
								if(req1.responseText == "1") {
								} else {
									$("#submit_fun").show();
									document.getElementById('id_budplanner').innerHTML=req1.responseText;
									// alert($('#ttl_pndlock').val()+"***********"+$('#txtrequest_value').val());
									if(parseInt($('#ttl_pndlock').val()) < parseInt($('#txtrequest_value').val())) {
										// alert("Request Value exceeds for this Target Number. So kindly reject this approval and create new approval for this!");
										var ALERT_TITLE = "Message";
										var ALERTMSG = "Request Value exceeds for this Target Number. So kindly reject this approval and create new approval for this!";
										createCustomAlert(ALERTMSG, ALERT_TITLE);
										$("#submit_fun").hide();
										window.location.reload();
									}
								}
							} else {
								alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
							}
						}
					}
					req1.open("POST", strURL, true);
					req1.send(null);
				}
			}
		}

        function open_popup(srno, grpname){
			 var brnch = $("#search_branch").val();
			 var secgrp = $("#search_section_group").val();
		     var sendurl = "budget_sales_percentage_popup.php?slt_branch="+srno+"&slt_department_asset="+grpname;

			$('#load_page').show();
			$.ajax({
				url:sendurl,
				success:function(data){
					$("#myModal1").modal('show');
					document.getElementById('modal-body1').innerHTML=data;
					$('#load_page').hide();
				}
			});
		}

		function getXMLHTTP() { //fuction to return the xml http object
			var xmlhttp=false;
				try{
					xmlhttp=new XMLHttpRequest();
				}
				catch(e) {
					try{
						xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch(e){
						try{
							xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
						}
						catch(e1){
							xmlhttp=false;
						}
					}
				}
			return xmlhttp;
		}

        function PrintDiv() {
    	   window.print();
    	}

        $(window).load(function() {
         	$("#load_page").fadeOut("slow");
        })

        function blinker() {
    		$('.blink_me').fadeOut(100);
    		$('.blink_me').fadeIn(800);
    	}
    	setInterval(blinker, 1000);


	/******************** Change Default Alert Box ***********************/
	var ALERT_BUTTON_TEXT = "OK";
	/* if(document.getElementById) {
		window.alert = function(txt) {
			var ALERT_TITLE = "GA Title";

			var tga = document.getElementById("id_ga").value;
			createCustomAlert(tga, ALERT_TITLE);
		}
	} */
	function dynamic_template_load(reqid,year,rsrid,creid,typeid)
{
	var prdcnt = <?=count($sql_prdlist)?>;
	if(prdcnt == 0){
	var strURL="ajax/ajax_dynamic_load.php?reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
		$.ajax({
			type: "POST",
			url: strURL,
			success: function(data) {
				$("#id_supplier").html(data);
			}
		});
	}
}
	function createCustomAlert(txt, title) {
		d = document;
		if(d.getElementById("modalContainer")) return;

		mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
		mObj.id = "modalContainer";
		mObj.style.height = d.documentElement.scrollHeight + "px";

		alertObj = mObj.appendChild(d.createElement("div"));
		alertObj.id = "alertBox";
		if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
		alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
		alertObj.style.visiblity="visible";

		h1 = alertObj.appendChild(d.createElement("h1"));
		h1.appendChild(d.createTextNode(title));

		msg = alertObj.appendChild(d.createElement("p"));
		//msg.appendChild(d.createTextNode(txt));
		msg.innerHTML = txt;

		btn = alertObj.appendChild(d.createElement("a"));
		btn.id = "closeBtn";
		btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
		btn.href = "#";
		btn.focus();
		btn.onclick = function() { removeCustomAlert();return false; }

		alertObj.style.display = "block";
	}

	function removeCustomAlert() {
		document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
	}

	function ful(){
		//alert('Alert this pages');
	}
	/******************** Change Default Alert Box ***********************/
	</script>


	<!-- Light Box - New -->
	<link href="../ktmportal/css/lightgallery.css" rel="stylesheet">
	<script type="text/javascript">
	$(document).ready(function(){
		$('.lightgallery').lightGallery();
	});

	function enable_month() {
		// $('#id_supplier *').prop('disabled',true);
		$("#id_supplier").addClass("disabledbutton");
		// $('#id_supplier').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
		$('#id_supplier').find('input, textarea').attr('readonly', 'readonly');
		$('#id_supplier').find('button, select, radio').attr('disabled', 'disabled');
		$("#id_supplier").attr("readonly", "1");
	}

	function enable_product() {
		// $('#id_budplanner *').prop('disabled',true);
		$("#id_budplanner").addClass("disabledbutton");
		// $('#id_budplanner').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
		$('#id_budplanner').find('input, textarea').attr('readonly', 'readonly');
		$('#id_budplanner').find('button, select, radio').attr('disabled', 'disabled');
		$("#id_budplanner").attr("readonly", "1");
	}
    </script>

    <script src="js/picturefill.min.js"></script>
    <script src="js/lightgallery.js"></script>
    <script src="js/lg-fullscreen.js"></script>
    <script src="js/lg-thumbnail.js"></script>
    <script src="js/lg-video.js"></script>
    <script src="js/lg-autoplay.js"></script>
    <script src="js/lg-zoom.js"></script>
    <script src="js/lg-hash.js"></script>
    <script src="js/lg-pager.js"></script>
    <!-- Light Box - New -->
<!-- END SCRIPTS -->
</body>
</html>
