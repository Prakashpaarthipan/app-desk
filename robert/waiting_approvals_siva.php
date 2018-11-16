<?php
header('Access-Control-Allow-Origin:http://rfq.thechennaisilks.com:8069');
header('Access-Control-Allow-Methods: GET,PUT,POST, DELETE');
try {
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='index.php';</script>
<?php exit();
}

if($status != 'archive') {
	// AFTER MAR/1/2017 - FOR SK SIR
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
										where req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.appstat = 'W' and req.ATCCODE = '".$_REQUEST['creid']."'
											and req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted='N' and req.REQSTBY = '".$usrid."' ".$usr." ".$mr_ak_date."
										order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", 'Centra', 'TEST');
$arsrno = $_REQUEST['rsrid'];
if(count($sql_exist) > 0) {
	$arsrno = $sql_exist[0]['ARQSRNO'];
}



$sql_requsr = select_query_json("select REQSTFR from APPROVAL_REQUEST req
										where req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ATCCODE = '".$_REQUEST['creid']."'
											and req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted='N' and req.ARQSRNO = ".$_REQUEST['rsrid']."
										order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", 'Centra', 'TEST');
// echo "**".$sql_requsr[0]['REQSTFR']."**REQSTFR**";
// Define user rights
$accrights = 1;
$tmporlive = 0; // 0 - TEMP Table / 1 - LIVE Table
// switch ($_SESSION['tcs_empsrno']) {
switch ($sql_requsr[0]['REQSTFR']) {
	case 61579: 	// Selva Muthu Kumar - 17108
	case 63624: 	// Hari Bala Krishnan - 17940
	case 59006: 	// Ranganathan - 15613
	case 48237: 	// SELVAGANAPATHI - 20446
		$accrights = 2;
		// $tmporlive = 0;
		$sql_expkn = select_query_json("select * from APPROVAL_REQUEST
												where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ATCCODE = '".$_REQUEST['creid']."' and
													ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and REQSTBY = 61579
												order by ARQSRNO", 'Centra', 'TEST');
		if(count($sql_expkn) > 0) {
			$tmporlive = 1;
		} else {
			$tmporlive = 0;
		}
		break;

	case 125: 		// PKN - 1118
	case 188: 		// Ashok - 1657
	case 62762: 	// Ramakrishnan - 14659
	case 200: 		// 1845 BALAMURUGAN R
	case 23684: 	// 5078 PREM KUMAR R
	case 14180: 	// Manoharan - 4317
	case 82237: 	// Dhinesh Khanna - 24262
	case 53864: 	// Madhan - 12232
	case 86464: 	// Nanthakumar - 15601
		$accrights = 3;
		$sql_expkn = select_query_json("select * from APPROVAL_REQUEST
												where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ATCCODE = '".$_REQUEST['creid']."' and
													ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and REQSTBY = 61579
												order by ARQSRNO", 'Centra', 'TEST');
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
	case 21344:		// Mr. SK Sir - 3
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
											order by ARQSRNO", 'Centra', 'TEST');
		$sql_tmporlive = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
												where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_expkn[0]['APRNUMB']."' and but.aprsrno = 1", 'Centra', 'TEST');
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
$where_read = " ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$arsrno."' and ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."'";
$update_read = update_dbquery($field_read, $tbl_read, $where_read);
// echo "*******".$update_read."*******";

// exit;

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

$stmlgl_desk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", 'Centra', 'TCS');
$appdesk = $stmlgl_desk[0]['DESKUSR'];
$steam = $stmlgl_desk[0]['STAMUSR'];
$legalteam = $stmlgl_desk[0]['LEGLUSR'];

$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
// $current_year = select_query_json("Select Poryear From Codeinc", 'Centra', 'TCS'); // Get the Current Year
$hidapryear = $current_year[0]['PORYEAR'];
$systemdate = strtoupper(date('d-M-Y h:i:s A'));
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$sysip = $_SERVER['REMOTE_ADDR'];
// $ftp_server = "www.tcstextile.in";
$ftp_server = "ftp1.thechennaisilks.com";

if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['function'] == 'save_more_suppliers') {
	// 107 - MANIPAL STORES!!14!!14!!28!!1!!100
	// print_r($txt_sltsupcode); exit;
	// Product List - Quotation Adding pdi
	$exsupplier = select_query_json("Select * From APPROVAL_PRODUCT_QUOTATION
												WHERE PBDYEAR = '".$hid_pbdyear."' and PBDCODE = ".$hid_pbdcode." and PBDLSNO = '".$hid_pbdlsno."'
													and PRLSTYR = '".$hid_pbdyear."' and supcode = '".$hid_supcode."'", "Centra", 'TEST');
	for($qdi = 0; $qdi < count($txt_sltsupcode); $qdi++) { // echo "***";
		$tx = explode("!!", $txt_sltsupcode[$qdi]);
		if($tx[5] == 100 or $tx[5] == 888) {
			$tx[5] = 1;
		}
		$sp_cd = explode(" - ", $txt_sltsupcode[$qdi]);
		$spcod = explode("!!", $sp_cd[1]);
		$maxprlstsr = select_query_json("Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR
												From APPROVAL_PRODUCT_QUOTATION
												WHERE PBDYEAR = '".$hid_pbdyear."' and PBDCODE = ".$hid_pbdcode." and PBDLSNO = '".$hid_pbdlsno."' and PRLSTYR = '".$hid_pbdyear."'", "Centra", 'TEST');
		$tbl_appdet1 = "APPROVAL_PRODUCT_QUOTATION";
		$field_appdet1 = array();
		$field_appdet1['PBDYEAR'] = $hid_pbdyear;
		$field_appdet1['PBDCODE'] = $hid_pbdcode;
		$field_appdet1['PBDLSNO'] = $hid_pbdlsno;
		$field_appdet1['PRLSTYR'] = $hid_pbdyear;
		$field_appdet1['PRLSTNO'] = $hid_pbdlsno;
		$field_appdet1['PRLSTSR'] = $maxprlstsr[0]['MAXPRLSTSR'];
		$field_appdet1['SUPCODE'] = $sp_cd[0];
		$field_appdet1['SUPNAME'] = strtoupper($spcod[0]);
		$field_appdet1['SLTSUPP'] = 0;
		$field_appdet1['DELPRID'] = '1';

		$field_appdet1['PRDRATE'] = $exsupplier[0]['PRDRATE'];
		if($tx[4] == $tx[5]) {
			$vlu = $exsupplier[0]['IGSTVAL'] > 0 ? $exsupplier[0]['IGSTVAL'] : ($exsupplier[0]['SGSTVAL'] + $exsupplier[0]['CGSTVAL']);
			$field_appdet1['SGSTVAL'] = round($vlu / 2, 2);
			$field_appdet1['CGSTVAL'] = round($vlu / 2, 2);
			$field_appdet1['IGSTVAL'] = 0;
		} else {
			$vlu = $exsupplier[0]['IGSTVAL'] > 0 ? $exsupplier[0]['IGSTVAL'] : ($exsupplier[0]['SGSTVAL'] + $exsupplier[0]['CGSTVAL']);
			$field_appdet1['SGSTVAL'] = 0;
			$field_appdet1['CGSTVAL'] = 0;
			$field_appdet1['IGSTVAL'] = $vlu;
		}
		$field_appdet1['DISCONT'] = 0;
		$field_appdet1['NETAMNT'] = $exsupplier[0]['NETAMNT'];
		$field_appdet1['SUPRMRK'] = 'COST CONTROL BID SELECTION';
		$field_appdet1['ADVAMNT'] = 0;

		$field_appdet1['QUOTFIL'] = "-";
		$field_appdet1['SPLDISC'] = "0";
		$field_appdet1['PIECLES'] = "0";
		// print_r($field_appdet1);
		$insert_appdet1 = insert_dbquery($field_appdet1, $tbl_appdet1);
	}
	// Product List - Quotation Adding

	if($insert_appdet1) { ?>
		<script>window.location='waiting_approvals.php?action=view&reqid=<?=$hid_adreqid?>&year=<?=$hid_adyear?>&rsrid=<?=$hid_adrsrid?>&creid=<?=$hid_adcreid?>&typeid=<?=$hid_adtypeid?>';</script>
	<?php exit;
	} else {?>
		<script>alert('Failure in supplier adding!. Kindly try again!!.'); window.location='waiting_approvals.php?action=view&reqid=<?=$hid_adreqid?>&year=<?=$hid_adyear?>&rsrid=<?=$hid_adrsrid?>&creid=<?=$hid_adcreid?>&typeid=<?=$hid_adtypeid?>';</script>
	<?php exit;
	}
	exit;
}

if($_SERVER['REQUEST_METHOD'] == "POST" and $_POST['function'] != 'save_more_suppliers')
{
	echo "***FIRST"; print_r($_REQUEST); echo "***<br>"; exit;
	/* echo "***".$_REQUEST['sbmt_approve']."***".$_REQUEST['sbmt_verification']."***".$_REQUEST['sbmt_forward']."***".$_REQUEST['sbmt_pending']."***".$_REQUEST['sbmt_reject']."***".$_REQUEST['sbmt_query']."***".$_REQUEST['sbmt_response']."***".$_REQUEST['sbmt_update']."***".$_REQUEST['sbmt_request']."***".$_REQUEST['hid_action']."***";
	exit; */// txt_tmporlive
	$fnupdt = 0; $fncretr = '';
	$sql_live = select_query_json("select * from APPROVAL_REQUEST
										where ARQCODE = '".$hid_reqid."' and APPSTAT = 'N' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and
											ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
									union
										select * from APPROVAL_REQUEST
										where ARQCODE = '".$hid_reqid."' and APPSTAT = 'W' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_rsrid."' and
											ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'", 'Centra', 'TEST');

	$cntlive = count($sql_live);
	if($cntlive > 0) {
		// Update in APPROVAL_REQUEST Table
		$tbl_apprq = "APPROVAL_REQUEST";
		$field_apprq['APPSTAT'] = 'F';
		$where_apprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'N' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
		// print_r($field_apprq); echo "<br>";
		///////** $update_apprq = update_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		// echo "!!!".$update_apprq."@@@";
		// Update in APPROVAL_REQUEST Table
	} else { $update_apprq = 0; ?>
			<script>alert('Already You have provided the remarks and status'); window.location='<?=$rturl?>';</script>
		<?php exit;
	}
	// exit;

	$update_apprq = 1; $cntlive = 1;
	if($update_apprq == 1 and $cntlive > 0) {
		if($slt_branch == 888) { $slt_branch = '100'; }

		/* echo "<br>chk_reject_reason";
		print_r($chk_reject_reason);
		echo "<br>hidchk_reject_reason";
		print_r($hidchk_reject_reason);
		echo "<br>hid_reason_reject";
		print_r($hid_reason_reject);
		// PBDLSNO */

		$rejct_vlu = 0;
		for ($aai = 0; $aai < count($hidchk_reject_reason); $aai++) {
			// echo "<br>**".$hidchk_reject_reason[$aai]."**".$chk_reject_reason[$hidchk_reject_reason[$aai]]."**".$hid_reason_reject[$hidchk_reject_reason[$aai]][0]."**".count($chk_reject_reason[$hidchk_reject_reason[$aai]])."**";
			if(count($chk_reject_reason[$hidchk_reject_reason[$aai]]) <= 0) {
				$maxprlstno = select_query_json("Select * From APPROVAL_PRODUCTLIST
														WHERE PBDYEAR = '".$arqyear."' and PBDCODE = '".$slt_aprno."' and PBDLSNO = '".$hidchk_reject_reason[$aai]."'", "Centra", 'TEST');
				// Update in APPROVAL_PRODUCTLIST Table
				$tbl_prlist = "APPROVAL_PRODUCTLIST";
				if($hid_reason_reject[$hidchk_reject_reason[$aai]][0] == '') {
					$field_prlist['REJRESN'] = 'THIS PRODUCT IS NOT REQUIRED..';
				} else {
					$field_prlist['REJRESN'] = strtoupper($hid_reason_reject[$hidchk_reject_reason[$aai]][0]);
				}
				$field_prlist['REJUSER'] = $_SESSION['tcs_usrcode'];
				$field_prlist['REJDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				// print_r($field_prlist);
				$where_prlist = "PBDYEAR = '".$arqyear."' and PBDCODE = '".$slt_aprno."' and PBDLSNO = '".$hidchk_reject_reason[$aai]."'";
				$update_prlist = update_dbquery($field_prlist, $tbl_prlist, $where_prlist);
				$update_prlist."@@@";
				// Update in APPROVAL_PRODUCTLIST Table

				$sql_rjvlu = select_query_json("Select * From APPROVAL_product_quotation
														WHERE PBDYEAR = '".$arqyear."' and PBDCODE = ".$slt_aprno." and PBDLSNO != '".$hidchk_reject_reason[$aai]."' and SLTSUPP = 1", "Centra", 'TEST');
				$rejct_vlu += $sql_rjvlu[0]['NETAMNT'];
			}

			/* //*********** Mail Send Function to User ********************
			$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO = ".$fncretr, 'Centra', 'TCS');

			$txt_email = '';
			$tomail = $sql_email[0]['EMAILID'];
			$txt_email = rtrim($tomail, ',');

			$to1 = $txt_email;
			$subject1 = substr("Reg:\"".$txt_approval_number."\" Request has been rejected", 0, 100);
			$mail_body1 = "<html><body><table border=0 cellpadding=1 cellspacing=1 width='100%'>
				<tr><td height='25' align='left' colspan=2>Dear Sir,</td></tr>
				<tr><td height='25' align='left' colspan=2>Sorry! <b>\"".$txt_approval_number."\"</b> request has been rejected. Reason - ".strtoupper($txt_remarks)."</td></tr>
				<tr><td height='25' align='left' colspan=2>Kindly verify the request.</td></tr>
				<tr height='25'></tr>
				<tr><td colspan=2>
				  Thank you,
				  <BR>Approval Desk Team
				  <BR>".$site_title."</td></tr>
			</table></body></html>";

			$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", 'Centra', 'TCS');
			$tbl_name="mail_send_summary";
			$field_values=array();
			$field_values['MAILYEAR'] = $hidapryear;
			$field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
			$field_values['DEPTID']   = 1;
			$field_values['MAILSUB']  = $subject1;
			$field_values['MAILCON']  = $mail_body1;
			$field_values['FILECNT']  = 0;
			$field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
			$field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$field_values['EMAILID']  = $to1;
			$field_values['STATUS']   = 'N';
			$field_values['DEPNAME']  = 'APP DESK';
			// print_r($field_values);
			$succ = 0;
			$insert_response = insert_dbquery($field_values, $tbl_name);
			// exit;
			//*********** Mail Send Function to User ********************/
		}
		// exit;

		// echo "..".$txtrequest_value."..";
		if($rejct_vlu > 0) {
			$txtrequest_value = $rejct_vlu;
		}
		// echo "**".$txtrequest_value."**";
		// exit;


		// Mdhierarchy table add
		if($slt_appflow_users != '') {
			$apl_users = add_approval_flow_users($slt_appflow_users, $txt_approval_number);
			$hid_appuser = $apl_users.$hid_appuser;
		}
		// echo "**".$hid_appuser."**";
		// Mdhierarchy table add
		// exit;

		// Move the Temp Table to Live Table - PKN Login
		$tarnochang_allowornot = 1; // 0 - Not Allowed / 1 - Allowed
		if($_SESSION['tcs_empsrno'] == 61579 and $txt_extarno != $slt_targetno and $txtrequest_value > 0) {
			$sql_extr = select_query_json("select sum(nvl(APPRVAL, 0)) aprvlu from approval_budget_planner_temp
													where BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$slt_core_department." and deleted = 'N'", 'Centra', 'TEST');
			if(count($sql_extr) > 0) {
				$sql_yrlyttl = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) + sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE,
															(sum(distinct nvl(sm.BUDVALUE, 0)) - sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) pendingvalue
														from budget_planner_head_sum sm, approval_budget_planner_temp tm
														where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N' and sm.BRNCODE=".$slt_branch." and
															sm.BUDYEAR = '".$hid_year."' and sm.EXPSRNO = ".$slt_core_department."", 'Centra', 'TEST');
			} else {
				$sql_yrlyttl = select_query_json("select sum(nvl(BUDVALUE, 0)) BUDVALUE, sum(nvl(APPVALUE, 0)) APPVALUE, (sum(nvl(BUDVALUE, 0)) - sum(nvl(APPVALUE, 0))) pendingvalue
														from budget_planner_head_sum where BRNCODE=".$slt_branch." and BUDYEAR = '".$hid_year."' and EXPSRNO = ".$slt_core_department."", 'Centra', 'TEST');
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

		$maxarqcode = select_query_json("Select nvl(Max(ARQCODE),1) maxarqcode, nvl(Max(ARQSRNO),0)+1 maxarqsrno From APPROVAL_REQUEST
												WHERE ATCCODE = ".$slt_topcore." and APMCODE = '".$slt_approval_listings."' and ATMCODE = '".$slt_subtype."' and
													ATYCODE = '".$slt_submission."' and ARQYEAR = '".$hid_year."' and ARQCODE = '".$hid_reqid."'", 'Centra', 'TEST'); // Get the Last record + 1 from APPROVAL_REQUEST

		$txtfrom_date1 = strtotime($txtfrom_date);
		$txtfrom_date2 = strtoupper(date('d-M-Y h:i:s A', $txtfrom_date1));
		// $txtfrom_date2 = strtoupper(date('Y-M-d', $txtfrom_date1));
		$txtto_date1 = strtotime($txtto_date);
		$txtto_date2 = strtoupper(date('d-M-Y h:i:s A', $txtto_date1));
		// $txtto_date2 = strtoupper(date('Y-M-d', $txtto_date1));
		$impldue_date1 = strtoupper(date('Y-M-d', strtotime($impldue_date)));

		// $txtdue_date_1 = explode("-", $txtdue_date);
		// $txtdue_date1 = strtoupper(date('Y-M-d', strtotime($txtdue_date)));
		$txtdue_date1 = $txtdue_date;

		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		$currentdate1 = strtoupper(date('d-m-Y'));
		$currenttime = strtoupper(date('H:i A'));
		// $srno = str_pad($hid_reqid, 6, '0', STR_PAD_LEFT);
		$dept = select_query_json("select DEPNAME from approval_department where ESECODE like '%".$_SESSION['tcs_esecode']."%'", 'Centra', 'TCS'); // Get user department from approval_department

		$noofattachment = $_REQUEST['hid_appattn_cnt'];
		$attch = $_REQUEST['hid_appattn_cnt'];
		if($_FILES['txt_submission_fieldimpl']['name'][0] != '') {
			$assign=$_FILES['txt_submission_fieldimpl']['name'];
			$noofattachment += count($_FILES['txt_submission_fieldimpl']['name']);
		}

		/* if($_FILES['txt_submission_fieldimpl']['name'][0] != '') {
			$assign0=$_FILES['txt_submission_fieldimpl']['name'];
			$noofattachment += count($_FILES['txt_submission_fieldimpl']['name']);
		} */

		if($_FILES['txt_submission_quotations']['name'][0] != '') {
			$assign1=$_FILES['txt_submission_quotations']['name'];
			$noofattachment += count($_FILES['txt_submission_quotations']['name']);
		}

		if($_FILES['txt_submission_clrphoto']['name'][0] != '') {
			$assign2=$_FILES['txt_submission_clrphoto']['name'];
			$noofattachment += count($_FILES['txt_submission_clrphoto']['name']);
		}

		/* if($_FILES['txt_submission_last_approval']['name'][0] != '') {
			$assign3=$_FILES['txt_submission_last_approval']['name'];
			$noofattachment += count($_FILES['txt_submission_last_approval']['name']);
		}

		if($_FILES['txt_submission_artwork']['name'][0] != '') {
			$assign4=$_FILES['txt_submission_artwork']['name'];
			$noofattachment += count($_FILES['txt_submission_artwork']['name']);
		} */

		$t = 0;
		$apuser = explode("~~", $hid_appuser);
		// print_r($apuser); echo "<br>***".$apfrwrdusr."*****".$apusr."*****".$hid_appuser."*****".$apuser."*******########<br>"; exit;
		if($apuser[0] == '' or $apuser[0] == 0)
		{
			if($_SESSION['tcs_user'] == $appdesk or $_SESSION['tcs_user'] == $steam or $_SESSION['tcs_user'] == $legalteam) {
				// echo "+++++++++++";
				$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", 'Centra', 'TCS');
				if($_REQUEST['steam_legal'] == 1)
				{
					$apfrwrdusr = $sql_apdesk[0]['STAMUSR'];
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
					$cnt_v = count($sql_app_hierarchy) - 1;
					$apusr = $sql_app_hierarchy[$cnt_v]['APPHEAD'];
				} elseif($_REQUEST['steam_legal'] == 2) {
					$apfrwrdusr = $apuser[0];
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
					$cnt_v = count($sql_app_hierarchy) - 1;
					$apusr = $sql_app_hierarchy[$cnt_v]['APPHEAD'];
				} else {
					$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
															where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' and amh.APPDESG = '132' and amh.DELETED = 'N'
															order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
					$ampsrno = "";
					if(count($sql_cur_hier) > 0) {
						$ampsrno = "and amh.AMHSRNO > '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
					} else {
						$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
					}

					$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$slt_approval_listings."' ".$ampsrno." and amh.DELETED = 'N'
																order by amh.APMCODE, amh.AMHSRNO", 'Centra', 'TEST');
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
				$empg = select_query_json("select * from employee_office where EMPCODE = ".$apuser[$apusri], 'Centra', 'TCS');
				$emapr = select_query_json("select APPROVAL_REQUEST.*, to_char(APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time, to_char(APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time,
													to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time, to_char(INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time,
													to_char(APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time
												from APPROVAL_REQUEST
												where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
													deleted = 'N' and APPSTAT in ('F', 'P') and RQBYDES like '".$apuser[$apusri]." - %'
												order by ARQCODE", 'Centra', 'TEST'); // reqid=$hid_reqid&year=$hid_year&rsrid=$hid_rsrid&creid=$hid_creid&typeid=$hid_typeid

				// if($empg[0][0] != $_SESSION['tcs_empsrno'] and count($emapr) == 0) {
				if($empg[0]['EMPSRNO'] != $_SESSION['tcs_empsrno'] and count($emapr) <= 1) {
					$sql_ex_intverify2 = select_query_json("select REQSTBY, appfrwd from APPROVAL_REQUEST
																where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
																	deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and aprnumb like '".$txt_approval_number."'
																order by ARQSRNO asc", 'Centra', 'TEST');
					if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and count($sql_ex_intverify2) > 0) {
						$usr_apr = select_query_json("select RQTODES from APPROVAL_REQUEST where aprnumb like '".$txt_approval_number."' and arqsrno = 1", 'Centra', 'TEST');
						$exusr_apr = explode(" - ", $usr_apr[0]['RQTODES']);
						$apusr = $exusr_apr[0];
					} else {
						$apusr = $apuser[$apusri];
					}

					if($t == 0) {
						//echo "<br>***".$_SESSION['tcs_user']."***".$appdesk;

						if($_SESSION['tcs_descode'] == 132) {

							// Enable this
							$sql_apdesk_requiredornot = select_query_json("select VRFYREQ from APPROVAL_MODE_HIERARCHY
																				where APMCODE = '".$slt_approval_listings."' and DELETED = 'N' order by APMCODE, AMHSRNO desc", 'Centra', 'TEST');
							$avoid_steam = 0; // Avoid S-Team, Legal and Approval Desk
							if($sql_apdesk_requiredornot[0]['VRFYREQ'] == 0) {
								$avoid_steam = 1;
							} else {
								$avoid_steam = 0;
							}
							// Enable this
							//exit;

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
								$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", 'Centra', 'TCS');
								$apfrwrdusr = $sql_apdesk[0]['DESKUSR'];
							}
						} // Forward to Approval desk from HOD
						elseif($_SESSION['tcs_user'] == $appdesk) {
							$sql_apdesk = select_query_json("select * from APPROVAL_DESK where APRDESK = 1", 'Centra', 'TCS');
							if($_REQUEST['steam_legal'] == 1) { $apfrwrdusr = $sql_apdesk[0]['STAMUSR']; }
							elseif($_REQUEST['steam_legal'] == 2) { $apfrwrdusr = $apuser[0]; }
							else { $apfrwrdusr = $sql_apdesk[0]['LEGLUSR']; }
						} // Forward to S-Team or Legal Team from Approval desk
						else { $apfrwrdusr = $apuser[$apusri]; } // Forward to Next Level Approval
					}
					$t++;
				} else {
					 $apusr = $apuser[$apusri];
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

		$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apusr, 'Centra', 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($emp[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}
		$todesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$emp[0]['DESCODE'], 'Centra', 'TCS'); // Req.To user designation
		$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], 'Centra', 'TCS'); // Req.To user section
		// echo $_REQUEST['hid_action']."-----select * from employee_office where EMPCODE = ".$apusr;

		// echo "<br>^^^^^^^^".$_REQUEST['slt_intermediate_team']."^^^^".$_REQUEST['hid_action']."^^^^^^^^<br>";
		if($_REQUEST['hid_action'] == 'sbmt_query') {
			$sql_creator = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
														and deleted = 'N' and APPSTAT in ('F', 'P')
													order by ARQCODE", 'Centra', 'TEST');
			if(count($sql_creator) <= 0) {
				$sql_creator = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
															deleted = 'N' and APPSTAT in ('F', 'P')
														order by ARQCODE", 'Centra', 'TEST');
			}

			$apfrwrdusr = $sql_creator[0]['REQSTBY'];
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section
		} elseif($_REQUEST['hid_action'] == 'sbmt_response') {
			$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'N', 'P')
													order by ARQCODE", 'Centra', 'TEST');
			if(count($sql_raiser) <= 0) {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."' and
															ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'N', 'P')
														order by ARQCODE", 'Centra', 'TEST');
			}

			$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$sql_lastuser = select_query_json("select * from APPROVAL_mdhierarchy where aprnumb like '".$txt_approval_number."' and AMHSRNO = 1", 'Centra', 'TEST');
			$lstur = $sql_lastuser[0]['APPHEAD'];
			if($sql_lastuser[0]['APPHEAD'] == '') {
				$sql_lastuser = select_query_json("select RQTODES from APPROVAL_REQUEST where aprnumb like '".$txt_approval_number."' and ARQSRNO = 1", 'Centra', 'TEST');
				$lstur1 = explode(" - ", $sql_lastuser[0]['RQTODES']);
				$lstur = $lstur1[0];
			}
			$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$lstur, 'Centra', 'TCS');
			$todesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$emp[0]['DESCODE'], 'Centra', 'TCS'); // Req.To user designation
			$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], 'Centra', 'TCS'); // Req.To user section
		} elseif($_REQUEST['slt_intermediate_team'] != '' and $_REQUEST['hid_action'] == 'sbmt_verification') {
			$apfrwrdusr = $slt_intermediate_team;
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apfrwrdusr, 'Centra', 'TCS');
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section
		} else {
			// echo "<br>***".$hid_int_verification."***<br>";
			$sql_ex_intverify1 = select_query_json("select ARQSRNO from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
															deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and REQSTBY = ".$txt_requestby."
														order by ARQSRNO desc", 'Centra', 'TEST');

			$sql_ex_intverify2 = select_query_json("select REQSTBY, appfrwd from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
															deleted = 'N' and APPFRWD in ('I', 'P') and REQSTFR = ".$txt_requestfr." and aprnumb like '".$txt_approval_number."'
														order by ARQSRNO asc", 'Centra', 'TEST');

			if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and count($sql_ex_intverify2) > 0) {
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empsrno = ".$sql_ex_intverify2[0]['REQSTBY'], 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			} elseif($hid_int_verification == 'I') {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."'
															and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P', 'N')
														order by ARQCODE", 'Centra', 'TEST');
				if(count($sql_raiser) <= 0) {
					$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
															where ARQCODE = '".$reqid."' and ARQSRNO = '".($maxarqcode[0]['MAXARQSRNO'] - 1)."' and ATCCODE = '".$hid_creid."' and
																ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P', 'N')
															order by ARQCODE", 'Centra', 'TEST');
				}

				$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where  DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

				$sql_creator = select_query_json("select RQESTTO from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'
														and deleted = 'N' and APPSTAT in ('F', 'P')
													order by ARQCODE", 'Centra', 'TEST');
				$apfrwrdusr = $sql_creator[0]['RQESTTO'];
				$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($emp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$todesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$emp[0]['DESCODE'], 'Centra', 'TCS'); // Req.To user designation
				$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], 'Centra', 'TCS'); // Req.To user section
			} elseif($_REQUEST['hid_action'] == 'sbmt_approve') {
				$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and
															ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('A', 'F', 'P')
														order by ARQCODE", 'Centra', 'TEST');
				if(count($sql_raiser) <= 0) {
					$sql_raiser = select_query_json("select REQSTBY from APPROVAL_REQUEST
															where ARQCODE = '".$reqid."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and
																deleted = 'N' and APPSTAT in ('A', 'F', 'P')
															order by ARQCODE", 'Centra', 'TEST');
				}

				// echo "select REQSTBY from APPROVAL_REQUEST where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N' and APPSTAT in ('F', 'P') order by ARQCODE";
				$apfrwrdusr = $sql_raiser[0]['REQSTBY'];
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

				$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.EMPSRNO = ".$apfrwrdusr, 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($emp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$todesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$emp[0]['DESCODE'], 'Centra', 'TCS'); // Req.To user designation
				$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], 'Centra', 'TCS'); // Req.To user section
			} else {
				$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apfrwrdusr, 'Centra', 'TCS');
				$empdes = "designation"; $empsec = "empsection";
				if($frwrdemp[0]['PAYCOMPANY'] == 2) {
					$empdes = "new_designation"; $empsec = "new_empsection";
				}
				$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
				$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section
			}
		}

		$frwrdemp_g = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$_SESSION['tcs_user'], 'Centra', 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($frwrdemp_g[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}

		// print_r($frwrdemp); echo "<br>***".$_REQUEST['hid_action']."*****".$frwrdemp[0]['EMPSRNO']."*****".$emp[0]['EMPSRNO']."*****"; exit();
		$bydesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$_SESSION['tcs_descode'], 'Centra', 'TCS'); // Req.By user designation
		$bysection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], 'Centra', 'TCS'); // Req.By user section
		if(count($bysection) <= 0) {
			$bysection = select_query_json("Select ESENAME From new_empsection where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], 'Centra', 'TCS'); // Req.To user section
		}

		/* Query for find the target balance */
		$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal,
													sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode
													and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset."
													and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", 'Centra', 'TCS');

		$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
											from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
											where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode
												and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset."
												and decode(nvl(non.ptdesc,'-'),'-',dep.depname,non.ptdesc) = '".$slt_tardesc."'
											group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", 'Centra', 'TCS');
		if(count($sql_targetno) <= 0) {
			$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode
													and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset."
													and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", 'Centra', 'TCS');
		}

		if(count($sql_targetno) <= 0) {
			$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode
													and brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset."
													and non.PTNUMB=".$slt_targetno."
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", 'Centra', 'TCS');
		}
		$expname = select_query_json("select distinct round(tarnumb) tarnumb, ( select distinct decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
											from non_purchase_target tar, department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode
											and tar.brncode=bpl.brncode) Depname
										from budget_planner_branch bpl
										where depcode=".$slt_department_asset." and brncode=".$slt_branch." and tarnumb=".$slt_targetno."
										order by Depname", 'Centra', 'TCS');
		$group_wise = select_query_json("select EXPSRNO from department_asset where deleted = 'N' and depcode in (".$slt_department_asset.") order by expsrno", 'Centra', 'TCS');
		/* Query for find the target balance */

		$ot = find_datacount($hid_reqid."_".$hid_slt_submission."_".$hid_slt_topcore."_".$hid_year."_fieldimpl_", 'i', 'fieldimpl');
		// fieldimpl
		// echo "CAME"; exit;
		for($i=0; $i<count($assign0); $i++)
		{
			if($assign0[$i] != '')
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

					$expl = explode(".", $_FILES['txt_submission_fieldimpl']['name'][$i]);
					$upload_img1 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$hid_year."_fieldimpl_".$fldimli."_".($ot + $i).".".$extn1;
					$source = $imgfile1;
					$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
					$complogos1 = str_replace(" ", "-", $upload_img1);
					$complogos1 = strtolower($complogos1);

					//// Thumb start
					if($fldimli == 'i')
					{
						$upload_img1_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$hid_year."_fieldimpl_".$fldimli."_".($ot + $i).".jpg";
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
					//echo '!!!'.$complogos1.'<br>'; RESVALU
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

		if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
			if(count($mnt_yr) > 0) {

				if($slt_approval_listings != 807 and $slt_approval_listings != 777) {
					// Remove existing month value and move that value to current month
					$currnt_mnth = ltrim(date("m,Y"), 0);
					if($txt_tmporlive == 0) {
						$tbl_appplan = "approval_budget_planner_temp";
					} else {
						$tbl_appplan = "approval_budget_planner";
					}

					$sql_ext_curr_month = select_query_json("select APRSRNO, APPRVAL from ".$tbl_appplan."
																	where aprnumb like '".$txt_approval_number."' and APRPRID = '".$currnt_mnth."' order by aprsrno", 'Centra', 'TEST');
					$sql_ext_curr_month_all = select_query_json("select * from ".$tbl_appplan."
																	where aprnumb like '".$txt_approval_number."' and aprsrno <= '".$sql_ext_curr_month[0]['APRSRNO']."' order by aprsrno", 'Centra', 'TEST');
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


				for($cntmntyr = 0; $cntmntyr < count($mnt_yr); $cntmntyr++) {
					// This is used Verify the current month and previous month
					$exp1 = explode(",", $mnt_yr[$cntmntyr]);
					$lastmonth = date("01-".$exp1[0]."-".$exp1[1]);
					$crntmonth = date("01-m-Y");
					$different = strtotime($crntmonth) - strtotime($lastmonth);

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
					}
					// This is for "IF ANY CHANGES OCCURS IN APPROVAL VALUE", it will update
				}
			}
		}
		// exit;


	$tbl_appreq = "APPROVAL_REQUEST";
	$field_appreq = array();
	if($hid_samearqsrno == 0) {
		// Update in APPROVAL_REQUEST Table
		$field_appreq['REQSTBY'] = $_SESSION['tcs_empsrno'];
		$field_appreq['RQBYDES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
		$field_appreq['REQDESC'] = $_SESSION['tcs_descode'];
		$field_appreq['REQESEC'] = $_SESSION['tcs_esecode'];
		$field_appreq['REQDESN'] = $bydesignation[0]['DESNAME'];
		$field_appreq['REQESEN'] = $bysection[0]['ESENAME'];

		if($frwrdemp[0]['EMPSRNO'] == 452 and $slt_topcore == 2 and $_SESSION['tcs_empsrno'] != '168' and $txt_requestby != '168' and $slt_approval_listings != '909') { /// 07092017
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = 1062", 'Centra', 'TCS'); // NSM sir added dynamically
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

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
		$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
		$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
		$field_appreq['APPINTP'] = 'N'; // Y - Yes; N - No;
		$field_appreq['INTPDES'] = 0;
		$field_appreq['INTPDSC'] = 2; // This 1 is indicate us, this is coming from gpanel home screen; This 0 is indicate us, this is coming from direct approval screen; This 2 is indicate us, this is coming from print screen approval page
		$field_appreq['INTPESC'] = 0; // This 1 is indicate us, this approval is read by approval user
		$field_appreq['APPRMRK'] = strtoupper($txt_remarks);

		if($end == 1) {
			$fnupdt = 1;
			$fncretr = $emp[0]['EMPSRNO'];
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			$field_apprq1['TARNUMB'] = $slt_targetno;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			} elseif($_REQUEST['hid_action'] == 'sbmt_reject') {
				$field_apprq1['APPSTAT'] = 'R';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			} elseif($_REQUEST['hid_action'] == 'sbmt_pending') {
				$field_apprq1['APPSTAT'] = 'P';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			}
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_mdapprove') { // GM approve this instead of MD
			$fnupdt = 1;
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			$field_apprq1['TARNUMB'] = $slt_targetno;
			if($_REQUEST['hid_action'] == 'sbmt_mdapprove') {
				$field_apprq1['APPSTAT'] = 'A';
				$field_apprq1['RQESTTO'] = $_SESSION['tcs_empsrno'];
				$field_apprq1['RQTODES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
				$field_apprq1['RQTODSC'] = $_SESSION['tcs_descode'];
				$field_apprq1['RQTOESC'] = $_SESSION['tcs_esecode'];
				$field_apprq1['RQTODSN'] = $bydesignation[0]['DESNAME'];
				$field_apprq1['RQTOESN'] = $bysection[0]['ESENAME'];
			}
			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT not in ('W') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$fncretr = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['APPINTP'] = 'G'; // MD Level Approval, but GM Finished
			// $field_appreq['APPRMRK'] = strtoupper($txt_remarks." - BELOW RS 100000. So GM LEVEL FINISH");


			if($sql_requsr[0]['REQSTFR'] == 61579 or $_SESSION['tcs_esecode'] == 137) {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT COST CONTROL APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT COST CONTROL APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - COST CONTROL LEVEL FINISH");
			} elseif($_SESSION['tcs_empsrno'] == 43400) {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT PS MADAM APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT PS MADAM APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - PS MADAM LEVEL FINISH");
			} else {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT GM APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT GM APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - GM LEVEL FINISH");
			}

			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQTODES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQTODSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQTOESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQTODSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $frsection1[0]['ESENAME'];
			$field_appreq['PRICODE'] = $slt_priority;

			$field_appreq['APPSTAT'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'N';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = "APRCODE = '".$subj[1]."' and DELETED = 'W'";
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_approve') {
			$fnupdt = 1;
			$fncretr = $emp[0]['EMPSRNO'];
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
			}

			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT not in ('W') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$field_appreq['APPSTAT'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			// echo "<br>!!<br>";
			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'N';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = " APRCODE = '".$subj[1]."' and DELETED = 'W' ";
				// print_r($field_apprq_1);
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}
			// echo "##".count($target_balance)."##".$target_balance[0]['BRNCODE']."##<br>";

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				// print_r($field_appplan);
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_verification') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'I'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		if($_REQUEST['hid_action'] == 'sbmt_response') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'S'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		//if($_REQUEST['hid_action'] == 'sbmt_reject' and $end == 1) {
		if($_REQUEST['hid_action'] == 'sbmt_reject') {
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_reject') {
				$field_apprq1['APPSTAT'] = 'R';
				// $field_apprq1['USRSYIP'] = $sysip;
			}
			// print_r($field_apprq1);
			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT not in ('W') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			// exit;

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$fncretr = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where  DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQTODES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQTODSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQTOESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQTODSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['APPSTAT'] = 'R'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'R'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'Y';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = "APRCODE = '".$subj[1]."' and DELETED = 'W'";
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['DELETED'] = 'Y';
				// $field_appplan['BUDMODE'] = 'R';
				$field_appplan['DELUSER'] = $_SESSION['tcs_usrcode'];
				$field_appplan['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno."";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}


			//*********** Mail Send Function to User ********************
			$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO = ".$fncretr, 'Centra', 'TCS');

			$txt_email = '';
			$tomail = $sql_email[0]['EMAILID'];
			$txt_email = rtrim($tomail, ',');

			$to1 = $txt_email;
			$subject1 = substr("Reg:\"".$txt_approval_number."\" Request has been rejected", 0, 100);
			$mail_body1 = "<html><body><table border=0 cellpadding=1 cellspacing=1 width='100%'>
				<tr><td height='25' align='left' colspan=2>Dear Sir,</td></tr>
				<tr><td height='25' align='left' colspan=2>Sorry! <b>\"".$txt_approval_number."\"</b> request has been rejected. Reason - ".strtoupper($txt_remarks)."</td></tr>
				<tr><td height='25' align='left' colspan=2>Kindly verify the request.</td></tr>
				<tr height='25'></tr>
				<tr><td colspan=2>
				  Thank you,
				  <BR>Approval Desk Team
				  <BR>".$site_title."</td></tr>
			</table></body></html>";

			$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", 'Centra', 'TCS');
			$tbl_name="mail_send_summary";
			$field_values=array();
			$field_values['MAILYEAR'] = $hidapryear;
			$field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
			$field_values['DEPTID']   = 1;
			$field_values['MAILSUB']  = $subject1;
			$field_values['MAILCON']  = $mail_body1;
			$field_values['FILECNT']  = 0;
			$field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
			$field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$field_values['EMAILID']  = $to1;
			$field_values['STATUS']   = 'N';
			$field_values['DEPNAME']  = 'APP DESK';
			// print_r($field_values);
			$succ = 0;
			$insert_response = insert_dbquery($field_values, $tbl_name);
			// exit;
			//*********** Mail Send Function to User ********************
		}

		if($_REQUEST['hid_action'] == 'sbmt_query') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'Q'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		//echo "***".$_REQUEST['sbmt_pending']."***";
		//if($_REQUEST['hid_action'] == 'sbmt_pending' and $end == 1) {
		if($_REQUEST['hid_action'] == 'sbmt_pending') {

			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_pending') {
				$field_apprq1['APPSTAT'] = 'P';
				// $field_apprq1['USRSYIP'] = $sysip;
			}

			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT not in ('W') and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			if(count($sql_creator1) <= 0) {
				$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N'
														order by ARQCODE", 'Centra', 'TEST');
			}

			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where  DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $_SESSION['tcs_empsrno'];
			$field_appreq['RQTODES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
			$field_appreq['RQTODSC'] = $_SESSION['tcs_descode'];
			$field_appreq['RQTOESC'] = $_SESSION['tcs_esecode'];
			$field_appreq['RQTODSN'] = $bydesignation[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $bysection[0]['ESENAME'];

			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'P'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		if($_REQUEST['hid_action'] == 'sbmt_forward') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		// Alternate Users
		$sql_frdesusr = select_query_json("select * from userid where empsrno = '".$txt_requestfr."'");
		$valid_user = chk_usr_logins_json($sql_frdesusr[0]['USRCODE'], $sysip);
		if($valid_user != 1) {
			$sql_altuser = select_query_json("select alt.*, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.ALTSRNO = emp.empsrno and al.apdsrno = alt.apdsrno)
														ALTERNATE_USER, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.RPTSRNO = emp.empsrno and
														al.apdsrno = alt.apdsrno) reporting_user, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.ELGUSER = emp.empsrno
														and al.apdsrno = alt.apdsrno) Eligible_user
													from APPROVAL_ALTERNATE_daily alt
													where deleted = 'N' and trunc(ALTDATE) = trunc(sysdate) and EMPSRNO = '".$frwrdemp[0]['EMPSRNO']."'
													order by apdsrno desc", "Centra", "TEST");
			if($frwrdemp[0]['EMPSRNO'] == $sql_altuser[0]['EMPSRNO']) { // Verify / Approval User
				$field_appreq['INTPEMP'] = $sql_altuser[0]['ELGUSER']; // Alternate User
			}
		}

		/* if($frwrdemp[0]['EMPSRNO'] == 61579) {
			// $field_appreq['INTPEMP'] = '59006'; // Ranganathan
			// $field_appreq['INTPEMP'] = 48237; // SARATH
			 $field_appreq['INTPEMP'] = 63624; // HARI BALA KRISHNAN 17940 - spt 5 & 6 - 2017
			// $field_appreq['INTPEMP'] = 76856; // SELVAGANAPATHI
		} elseif($frwrdemp[0]['EMPSRNO'] == 2158) {
			$field_appreq['INTPEMP'] = '13613'; // Praveen alternate for Gunasekar
		} elseif($frwrdemp[0]['EMPSRNO'] == 34593) {
			$field_appreq['INTPEMP'] = '1169'; // HW Karthik alternate for Saravanakumar
		} elseif($frwrdemp[0]['EMPSRNO'] == 188) { // Ashok - S-team
			$field_appreq['INTPEMP'] = 62762; // Ramakrishnan - S-team
		} elseif($frwrdemp[0]['EMPSRNO'] == 200) { // BALAMURUGAN - Advt-team
			$field_appreq['INTPEMP'] = 23684; // PREM KUMAR R - advt-team
		}  elseif($frwrdemp[0]['EMPSRNO'] == 14180) { // Manoharan - Project-team
			$field_appreq['INTPEMP'] = 82237; // Dhinesh Khanna - Project-team
		}  elseif($frwrdemp[0]['EMPSRNO'] == 53864) { // Madhan - HR Dept
			$field_appreq['INTPEMP'] = 86464; // Nanthakumar - HR Dept
		} */
		// Alternate Users

		$field_appreq['DYNSUBJ'] = strtoupper($slt_dynamic_subject);
		$field_appreq['TXTSUBJ'] = strtoupper($txt_dynsubject);
		$field_appreq['BDPLANR'] = $slt_fixbudget_planner;
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
		$field_appreq['AGEXPDT'] = $txt_agreement_expiry;
		$field_appreq['AGADVAM'] = strtoupper($txt_agreement_advance);

		if($alt_user_approval == 1) {
			$field_appreq['RPTUSER'] = $txt_requestfr;
			$field_appreq['ACKUSER'] = "";
			$field_appreq['ACKSTAT'] = "";
			$field_appreq['ACKDATE'] = "";
		}
		// Attachments

		/* elseif($frwrdemp[0]['EMPSRNO'] == 23682) { // MDU - Parthiban - Rajesh
			$field_appreq['INTPEMP'] = 16999;
		}*/

		// print_r($field_appreq); echo "<br>";
		/* echo $where_appreq = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno.""; */
		$where_appreq = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('W') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
		$insert_appreq = update_dbquery($field_appreq, $tbl_appreq, $where_appreq);
		// echo "!!!!!!!!!!!!!!!".$insert_appreq."@@@<pre>";

		// exit;





		// Update in APPROVAL_REQUEST Table
	} else {
		// Insert in APPROVAL_REQUEST Table
		$field_appreq['ARQPCOD'] = $hid_arqpcod;
		$field_appreq['ARQCODE'] = $hid_reqid;
		// $field_appreq['ARQYEAR'] = $hidapryear;
		$field_appreq['ARQYEAR'] = $hid_year;
		$field_appreq['ARQSRNO'] = $maxarqcode[0]['MAXARQSRNO'];
		$field_appreq['ATYCODE'] = $slt_submission;
		$field_appreq['ATMCODE'] = $slt_subtype;
		$field_appreq['APMCODE'] = $slt_approval_listings;
		$field_appreq['ATCCODE'] = $slt_topcore;
		$field_appreq['APPRFOR'] = $slt_submitfor;
		$field_appreq['REQSTTO'] = $txt_kind_attn;
		$field_appreq['APPRSUB'] = $txtsubject;

		$field_appreq['APPRDET'] = strtoupper($txtdetails);
		$field_appreq['APPRSFR'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtfrom_date2;
		$field_appreq['APPRSTO'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtto_date2;
		$field_appreq['APPATTN'] = $noofattachment;
		$field_appreq['APRQVAL'] = $txtrequest_value;
		$field_appreq['APPDVAL'] = $txtrequest_value;
		$field_appreq['APPFVAL'] = $txtrequest_value;

		$tbl_appplanrq = "approval_request";
		$field_appplanrq = array();
		$field_appplanrq['APPFVAL'] = $txtrequest_value;
		$where_appplanrq = " APRNUMB = '".$txt_approval_number."'";
		$insert_appplanrq = update_dbquery($field_appplanrq, $tbl_appplanrq, $where_appplanrq);

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

		if($frwrdemp[0]['EMPSRNO'] == 452 and $slt_topcore == 2 and $_SESSION['tcs_empsrno'] != '168' and $txt_requestby != '168' and $slt_approval_listings != '909') { /// 07092017
			$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = 1062", 'Centra', 'TCS'); // NSM sir added dynamically
			$empdes = "designation"; $empsec = "empsection";
			if($frwrdemp[0]['PAYCOMPANY'] == 2) {
				$empdes = "new_designation"; $empsec = "new_empsection";
			}
			$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

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
		$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
		$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
		$field_appreq['APPINTP'] = 'N'; // Y - Yes; N - No;
		$field_appreq['INTPDES'] = 0;
		$field_appreq['INTPDSC'] = 2; // This 1 is indicate us, this is coming from gpanel home screen; This 0 is indicate us, this is coming from direct approval screen; This 2 is indicate us, this is coming from print screen approval page
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
		$field_appreq['APRDUED'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtdue_date1;
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
		if($end == 1) {
			$fnupdt = 1;
			$fncretr = $emp[0]['EMPSRNO'];
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			$field_apprq1['TARNUMB'] = $slt_targetno;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			} elseif($_REQUEST['hid_action'] == 'sbmt_reject') {
				$field_apprq1['APPSTAT'] = 'R';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			} elseif($_REQUEST['hid_action'] == 'sbmt_pending') {
				$field_apprq1['APPSTAT'] = 'P';
				// $field_apprq1['USRSYIP'] = $sysip;
				$where_apprq1 = "ARQCODE = '".$hid_reqid."' and APPSTAT in ('F', 'P') and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			}
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_mdapprove') { // GM approve this instead of MD
			$fnupdt = 1;
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			$field_apprq1['TARNUMB'] = $slt_targetno;
			if($_REQUEST['hid_action'] == 'sbmt_mdapprove') {
				$field_apprq1['APPSTAT'] = 'A';
				$field_apprq1['RQESTTO'] = $_SESSION['tcs_empsrno'];
				$field_apprq1['RQTODES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
				$field_apprq1['RQTODSC'] = $_SESSION['tcs_descode'];
				$field_apprq1['RQTOESC'] = $_SESSION['tcs_esecode'];
				$field_apprq1['RQTODSN'] = $bydesignation[0]['DESNAME'];
				$field_apprq1['RQTOESN'] = $bysection[0]['ESENAME'];
			}
			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$fncretr = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['APPINTP'] = 'G'; // MD Level Approval, but GM Finished
			// $field_appreq['APPRMRK'] = strtoupper($txt_remarks." - BELOW RS 100000. So GM LEVEL FINISH");
			if($sql_requsr[0]['REQSTFR'] == 61579 or $_SESSION['tcs_esecode'] == 137) {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT COST CONTROL APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT COST CONTROL APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - COST CONTROL LEVEL FINISH");
			} elseif($_SESSION['tcs_empsrno'] == 43400) {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT PS MADAM APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT PS MADAM APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - PS MADAM LEVEL FINISH");
			} else {
				$field_appreq['INTSUGG'] = 'MD APPOVAL BUT GM APPROVED. WAITING MD VERIFY'; // MD APPOVAL BUT GM APPROVED. WAITING MD VERIFY
				$field_appreq['APPRMRK'] = strtoupper($txt_remarks." - GM LEVEL FINISH");
			}
			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQTODES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQTODSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQTOESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQTODSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $frsection1[0]['ESENAME'];
			$field_appreq['PRICODE'] = 1;

			$field_appreq['APPSTAT'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'N';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = "APRCODE = '".$subj[1]."' and DELETED = 'W'";
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_approve') {
			$fnupdt = 1;
			$fncretr = $emp[0]['EMPSRNO'];
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
			}

			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$field_appreq['APPSTAT'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'A'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			// echo "<br>!!<br>";
			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'N';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = " APRCODE = '".$subj[1]."' and DELETED = 'W' ";
				// print_r($field_apprq_1);
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}
			// echo "##".count($target_balance)."##".$target_balance[0]['BRNCODE']."##<br>";

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['APPMODE'] = 'Y';
				// print_r($field_appplan);
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and TARNUMB = ".$slt_targetno." and deleted = 'N' and APPMODE = 'N'";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}
		}

		if($_REQUEST['hid_action'] == 'sbmt_verification') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'I'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		if($_REQUEST['hid_action'] == 'sbmt_response') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'S'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		//if($_REQUEST['hid_action'] == 'sbmt_reject' and $end == 1) {
		if($_REQUEST['hid_action'] == 'sbmt_reject') {
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
			} elseif($_REQUEST['hid_action'] == 'sbmt_reject') {
				$field_apprq1['APPSTAT'] = 'R';
				// $field_apprq1['USRSYIP'] = $sysip;
			} elseif($_REQUEST['hid_action'] == 'sbmt_pending') {
				$field_apprq1['APPSTAT'] = 'P';
				// $field_apprq1['USRSYIP'] = $sysip;
			}
			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and ARQYEAR = '".$hid_year."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$fncretr = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where  DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQTODES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQTODSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQTOESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQTODSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['APPSTAT'] = 'R'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'R'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;

			if($subj[0] == 'PROJECT ID') {
				$tbl_apprq_1 = "APPROVAL_PROJECT";
				$field_apprq_1 = array();
				$field_apprq_1['DELETED'] = 'Y';
				$field_apprq_1['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$field_apprq_1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_apprq_1 = "APRCODE = '".$subj[1]."' and DELETED = 'W'";
				$update_apprq_1 = update_dbquery($field_apprq_1, $tbl_apprq_1, $where_apprq_1);
			}

			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if($txt_tmporlive == 0) {
					$tbl_appplan = "approval_budget_planner_temp";
				} else {
					$tbl_appplan = "approval_budget_planner";
				}

				$field_appplan = array();
				$field_appplan['DELETED'] = 'Y';
				// $field_appplan['BUDMODE'] = 'R';
				$field_appplan['DELUSER'] = $_SESSION['tcs_usrcode'];
				$field_appplan['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$where_appplan = " APRNUMB='".$txt_approval_number."' and BRNCODE=".$slt_branch." and APRYEAR = '".$hid_year."' and EXPSRNO = ".$group_wise[0]['EXPSRNO']." and deleted = 'N' and TARNUMB = ".$slt_targetno."";
				$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
			}


			//*********** Mail Send Function to User ********************
			$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO = ".$fncretr, 'Centra', 'TCS');

			$txt_email = '';
			$tomail = $sql_email[0]['EMAILID'];
			$txt_email = rtrim($tomail, ',');

			$to1 = $txt_email;
			$subject1 = substr("Reg:\"".$txt_approval_number."\" Request has been rejected", 0, 100);
			$mail_body1 = "<html><body><table border=0 cellpadding=1 cellspacing=1 width='100%'>
				<tr><td height='25' align='left' colspan=2>Dear Sir,</td></tr>
				<tr><td height='25' align='left' colspan=2>Sorry! <b>\"".$txt_approval_number."\"</b> request has been rejected. Reason - ".strtoupper($txt_remarks)."</td></tr>
				<tr><td height='25' align='left' colspan=2>Kindly verify the request.</td></tr>
				<tr height='25'></tr>
				<tr><td colspan=2>
				  Thank you,
				  <BR>Approval Desk Team
				  <BR>".$site_title."</td></tr>
			</table></body></html>";

			$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", 'Centra', 'TCS');
			$tbl_name="mail_send_summary";
			$field_values=array();
			$field_values['MAILYEAR'] = $hidapryear;
			$field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
			$field_values['DEPTID']   = 1;
			$field_values['MAILSUB']  = $subject1;
			$field_values['MAILCON']  = $mail_body1;
			$field_values['FILECNT']  = 0;
			$field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
			$field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$field_values['EMAILID']  = $to1;
			$field_values['STATUS']   = 'N';
			$field_values['DEPNAME']  = 'APP DESK';
			// print_r($field_values);
			$succ = 0;
			$insert_response = insert_dbquery($field_values, $tbl_name);
			// exit;
			//*********** Mail Send Function to User ********************
		}

		if($_REQUEST['hid_action'] == 'sbmt_query') {
			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'Q'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
		}

		//echo "***".$_REQUEST['sbmt_pending']."***";
		//if($_REQUEST['hid_action'] == 'sbmt_pending' and $end == 1) {
		if($_REQUEST['hid_action'] == 'sbmt_pending') {

			// Update in APPROVAL_REQUEST Table ARQSRNO = 1
			$tbl_apprq1 = "APPROVAL_REQUEST";
			$field_apprq1 = array();
			if($sql_targetno[0]['TARNUMBER'] != '') {
				$field_apprq1['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
			} else {
				$field_apprq1['INTPDSN'] = 0;
			}
			$field_apprq1['TARNUMB'] = $slt_targetno;
			$field_apprq1['APPFVAL'] = $txtrequest_value;
			if($_REQUEST['hid_action'] == 'sbmt_approve') {
				// $apprvd = 1;
				$field_apprq1['APPSTAT'] = 'A';
				// $field_apprq1['USRSYIP'] = $sysip;
			} elseif($_REQUEST['hid_action'] == 'sbmt_reject') {
				$field_apprq1['APPSTAT'] = 'R';
				// $field_apprq1['USRSYIP'] = $sysip;
			} elseif($_REQUEST['hid_action'] == 'sbmt_pending') {
				$field_apprq1['APPSTAT'] = 'P';
				// $field_apprq1['USRSYIP'] = $sysip;
			}

			$where_apprq1 = "ARQCODE = '".$hid_reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			$update_apprq1 = update_dbquery($field_apprq1, $tbl_apprq1, $where_apprq1);
			// Update in APPROVAL_REQUEST Table ARQSRNO = 1

			$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
													where ARQCODE = '".$reqid."' and ARQYEAR = '".$hid_year."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and
														ATYCODE = '".$hid_typeid."' and deleted = 'N'
													order by ARQCODE", 'Centra', 'TEST');
			if(count($sql_creator1) <= 0) {
				$sql_creator1 = select_query_json("select REQSTBY from APPROVAL_REQUEST
														where ARQCODE = '".$reqid."' and ARQSRNO = 1 and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."' and deleted = 'N'
														order by ARQCODE", 'Centra', 'TEST');
			}

			$apfrwrdusr1 = $sql_creator1[0]['REQSTBY'];
			$frwrdemp1 = select_query_json("select * from employee_office where EMPSRNO = ".$apfrwrdusr1, 'Centra', 'TCS');
			$frdesignation1 = select_query_json("Select DESNAME From designation where  DESCODE = ".$frwrdemp1[0]['DESCODE'], 'Centra', 'TCS'); // Req.forward user designation
			$frsection1 = select_query_json("Select ESENAME From empsection where deleted = 'N' and ESECODE = ".$frwrdemp1[0]['ESECODE'], 'Centra', 'TCS'); // Req.forward user section

			$field_appreq['REQSTFR'] = $frwrdemp1[0]['EMPSRNO'];
			$field_appreq['RQFRDES'] = $frwrdemp1[0]['EMPCODE']." - ".$frwrdemp1[0]['EMPNAME'];
			$field_appreq['RQFRDSC'] = $frwrdemp1[0]['DESCODE'];
			$field_appreq['RQFRESC'] = $frwrdemp1[0]['ESECODE'];
			$field_appreq['RQFRDSN'] = $frdesignation1[0]['DESNAME'];
			$field_appreq['RQFRESN'] = $frsection1[0]['ESENAME'];

			$field_appreq['RQESTTO'] = $_SESSION['tcs_empsrno'];
			$field_appreq['RQTODES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
			$field_appreq['RQTODSC'] = $_SESSION['tcs_descode'];
			$field_appreq['RQTOESC'] = $_SESSION['tcs_esecode'];
			$field_appreq['RQTODSN'] = $bydesignation[0]['DESNAME'];
			$field_appreq['RQTOESN'] = $bysection[0]['ESENAME'];

			$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
			$field_appreq['APPFRWD'] = 'P'; // N - Normal / Newly Created; A - Approved; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; I - Internal Verification; Q - Query;
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
											order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", 'Centra', 'TCS');

		// Last Year Record
		$last_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal,
												decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per
											from budget_planner_branch bpl, non_sales_target non
											where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($lat,-2)."'
												and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
											group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL
											order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", 'Centra', 'TCS');

		$field_appreq['TARVLCY'] = $cur_year[0]['BUDGETVAL'];
		$field_appreq['TARVLLY'] = $last_year[0]['BUDGETVAL'];
		$field_appreq['EXPNAME'] = $expname[0]['DEPNAME'];
		$field_appreq['TARPRCY'] = $cur_year[0]['PER'];
		$field_appreq['TARPRLY'] = $last_year[0]['PER'];
		$field_appreq['BUDTYPE'] = $slt_submission;
		// if($slt_submission == 6 or $slt_submission == 7) {
			$field_appreq['BUDCODE'] = $slt_budgetmode;
		// }

		// 27-12-2016 SK Sir Instruction
		$field_appreq['IMDUEDT'] = 'dd-Mon-yyyy~~'.$impldue_date;
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
		// 27-12-2016 SK Sir Instruction

		// Alternate Users
		$sql_frdesusr = select_query_json("select * from userid where empsrno = '".$txt_requestfr."'");
		$valid_user = chk_usr_logins_json($sql_frdesusr[0]['USRCODE'], $sysip);
		if($valid_user != 1) {
			$sql_altuser = select_query_json("select alt.*, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.ALTSRNO = emp.empsrno and al.apdsrno = alt.apdsrno)
														ALTERNATE_USER, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.RPTSRNO = emp.empsrno and
														al.apdsrno = alt.apdsrno) reporting_user, (select emp.empname from APPROVAL_ALTERNATE_daily al, employee_office emp where al.ELGUSER = emp.empsrno
														and al.apdsrno = alt.apdsrno) Eligible_user
													from APPROVAL_ALTERNATE_daily alt
													where deleted = 'N' and trunc(ALTDATE) = trunc(sysdate) and EMPSRNO = '".$frwrdemp[0]['EMPSRNO']."'
													order by apdsrno desc", "Centra", "TEST");
			if($frwrdemp[0]['EMPSRNO'] == $sql_altuser[0]['EMPSRNO']) { // Verify / Approval User
				$field_appreq['INTPEMP'] = $sql_altuser[0]['ELGUSER']; // Alternate User
			}
		}

		/* if($frwrdemp[0]['EMPSRNO'] == 61579) {
			// $field_appreq['INTPEMP'] = '59006'; // Ranganathan
			// $field_appreq['INTPEMP'] = 48237; // SARATH
			 $field_appreq['INTPEMP'] = 63624; // HARI BALA KRISHNAN 17940 - spt 5 & 6 - 2017
			// $field_appreq['INTPEMP'] = 76856; // SELVAGANAPATHI
		} elseif($frwrdemp[0]['EMPSRNO'] == 2158) {
			$field_appreq['INTPEMP'] = '13613'; // Praveen alternate for Gunasekar
		} elseif($frwrdemp[0]['EMPSRNO'] == 34593) {
			$field_appreq['INTPEMP'] = '1169'; // HW Karthik alternate for Saravanakumar
		} elseif($frwrdemp[0]['EMPSRNO'] == 188) { // Ashok - S-team
			$field_appreq['INTPEMP'] = 62762; // Ramakrishnan - S-team
		} elseif($frwrdemp[0]['EMPSRNO'] == 200) { // BALAMURUGAN - Advt-team
			$field_appreq['INTPEMP'] = 23684; // PREM KUMAR R - advt-team
		}  elseif($frwrdemp[0]['EMPSRNO'] == 14180) { // Manoharan - Project-team
			$field_appreq['INTPEMP'] = 82237; // Dhinesh Khanna - Project-team
		}  elseif($frwrdemp[0]['EMPSRNO'] == 53864) { // Madhan - HR Dept
			$field_appreq['INTPEMP'] = 86464; // Nanthakumar - HR Dept
		} */
		// Alternate Users

		// 23-08-2017 SK Sir Instruction
		$rqby = explode(" - ", $txt_submission_respuser);
		$rqbyusr = $rqby[0];

		$altusr = explode(" - ", $txt_alternate_user);
		$altrusr = $altusr[0];

		$field_appreq['PRODWIS'] = $txt_prodwise_budget;
		$field_appreq['RESPUSR'] = $rqbyusr;
		$field_appreq['ALTRUSR'] = $altrusr;
		$field_appreq['WRKINUSR']= $hid_wrkinusr;
		$field_appreq['BDPLANR'] = $slt_fixbudget_planner;
		$field_appreq['RELAPPR'] = strtoupper($txt_related_approvals);
		$field_appreq['AGNSAPR'] = strtoupper($txt_against_approval);

		$field_appreq['ORGRECV'] = 'N';
		$field_appreq['ORGRVUS'] = '';
		$field_appreq['ORGRVDT'] = '';
		$field_appreq['ORGRVDC'] = '';
		$field_appreq['CNVRMOD'] = strtoupper($slt_convertmode);
		$field_appreq['APPTYPE'] = strtoupper($slt_apptype);
		// $field_appreq['ADVAMNT'] = $txt_adv_amount;
		// 23-08-2017 SK Sir Instruction

		$field_appreq['DYNSUBJ'] = $slt_dynamic_subject;
		$field_appreq['TXTSUBJ'] = $txt_dynsubject;
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
		$field_appreq['AGEXPDT'] = 'dd-Mon-yyyy~~'.$txt_agreement_expiry;
		$field_appreq['AGADVAM'] = strtoupper($txt_agreement_advance);

		if($alt_user_approval == 1) {
			$field_appreq['RPTUSER'] = $txt_requestfr;
			$field_appreq['ACKUSER'] = "";
			$field_appreq['ACKSTAT'] = "";
			$field_appreq['ACKDATE'] = "";
		}
		// Attachments

		echo "<pre>";
		// exit();
		print_r($field_appreq); echo "<br>";
		$insert_appreq = insert_dbquery($field_appreq, $tbl_appreq);
		echo "############".$insert_appreq."@@@<pre>";
		// Insert in APPROVAL_REQUEST Table
	}
	// exit;


	$addiv_return = 8;
	if(($hid_int_verification == 'F' or $hid_int_verification == 'S') and $_REQUEST['hid_action'] == 'sbmt_forward' and $insert_appreq == 1 and $frwrdemp[0]['EMPSRNO'] == 21344 and $_SESSION['tcs_empsrno'] != 168) {
		$addiv_return = add_ivuser(21344, 168, $txt_approval_number, $maxarqcode[0]['MAXARQSRNO']); // MR. SK Sir User - 21344 & MR. NSM Sir User - 168
	}

	if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
		if(count($mnt_yr) > 0) {
			for($cntmntyr = 0; $cntmntyr < count($mnt_yr); $cntmntyr++) {

				// This is used Verify the current month and previous month
				$exp1 = explode(",", $mnt_yr[$cntmntyr]);
				$lastmonth = date("01-".$exp1[0]."-".$exp1[1]);
				$crntmonth = date("01-m-Y");
				$different = strtotime($crntmonth) - strtotime($lastmonth);
				// echo "<br>****".$different."****".$mnt_yr_amt[$cntmntyr]."*****<br>";

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
	// exit;

	// Move the Temp Table to Live Table - PKN Login
	if($_SESSION['tcs_empsrno'] == 61579 and $txt_extarno == $slt_targetno) { // echo "STEP1";
		// Step 1 : Move the Temp Table records to LIVE Table
		$ivqry = delete_dbquery("INSERT INTO approval_budget_planner select APRNUMB,APRSRNO,APRPRID,APRMNTH,APPRVAL,APPMNTH,APPYEAR,TARNUMB,RESVALU,EXTVALU,BUDMODE,APRYEAR,ADDUSER,ADDDATE,'','','N','','',BRNCODE,APPMODE,EXPSRNO,'0','',DEPCODE from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."' and DELETED = 'N'");

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
			$ivqry = delete_dbquery("INSERT INTO approval_budget_planner select APRNUMB,APRSRNO,APRPRID,APRMNTH,APPRVAL,APPMNTH,APPYEAR,TARNUMB,RESVALU,EXTVALU,BUDMODE,APRYEAR,ADDUSER,ADDDATE,'','','N','','',BRNCODE,APPMODE,EXPSRNO,'0','',DEPCODE from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."' and DELETED = 'N'");

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
	}


	// echo "==".$update_apprq."==".$insert_appreq."=="; exit;
	if($insert_appreq == 1) { // exit();
		if($fnupdt == 1) {
			// To Generate Multiple Branch approvals from only one approval
			$sql_brndet = select_query_json("select * from approval_branch_detail where APRNUMB = '".$txt_approval_number."' order by BRDTSRN, BRNCODE");
			if(count($sql_brndet) > 0) {
				$brndet = generate_multiple_branch($txt_approval_number, $slt_topcore); // After approval generate the multiple branch approval.
			}
			// To Generate Multiple Branch approvals from only one approval

			//*********** Mail Send Function to User - Approval ********************
			$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO = ".$fncretr, 'Centra', 'TCS');

			$txt_email = '';
			$tomail = $sql_email[0]['EMAILID'];
			if($tomail != '') {
				$txt_email .= rtrim($tomail, ',');
				$txt_email .= ',approvals@thechennaisilks.com';
			} else {
				$txt_email .= 'approvals@thechennaisilks.com';
			}
			if($txtrequest_value > 0) {
				$txt_email .= ',projectmanagement.support@thechennaisilks.com';
			}

			$to1 = $txt_email;
			$sql_aplist = select_query_json("select * from approval_master where APMCODE = ".$slt_approval_listings, 'Centra', 'TCS');
			$exl = explode(" / ", $txt_approval_number);
			$txt_approval_no = $exl[0]." / ".$exl[1];
			// $subject1 = substr("Reg:\"".$txt_approval_number."\" Request has been approved", 0, 100);
			$subject1 = substr("Reg:".$slt_dynamic_subject.$sql_aplist[0]['APMNAME']." - ".$txt_approval_no." Request has been approved", 0, 100);
			$mail_body1 = "<html><body><table border=0 cellpadding=1 cellspacing=1 width='100%'>
				<tr><td height='25' align='left' colspan=2>Dear Sir,</td></tr>
				<tr><td height='25' align='left' colspan=2>Congrats! <b>\"".$slt_dynamic_subject.$sql_aplist[0]['APMNAME']." - ".$txt_approval_number."\"</b> request has been approved.</td></tr>
				<tr><td height='25' align='left' colspan=2>Kindly contact to the approval desk team regarding this request.</td></tr>
				<tr height='25'></tr>
				<tr><td colspan=2>
				  Thank you,
				  <BR>Approval Desk Team.
				  <BR>".$site_title."</td></tr>
			</table></body></html>";

			$sql_all_mail = select_query_json("select distinct emp.empsrno, mail.emailid, hir.amhsrno
														from approval_request req, approval_mdhierarchy hir, approval_email_master mail, employee_office emp
														where req.aprnumb = hir.aprnumb and emp.empcode=hir.apphead and emp.empsrno=mail.empsrno and
															hir.aprnumb = '".$txt_approval_number."' and req.appstat = 'A'
														order by amhsrno", 'Centra', 'TCS');
			if(count($sql_all_mail) > 0) {
				foreach ($sql_all_mail as $emails) {
					$sql_aprv = select_query_json("select * from approval_request where aprnumb = '".$txt_approval_number."' and appstat = 'A' and arqsrno = 1", 'Centra', 'TEST');
					if(count($sql_aprv) > 0) {
						$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", 'Centra', 'TCS');
						$tbl_name="mail_send_summary";
						$field_values=array();
						$field_values['MAILYEAR'] = $hidapryear;
						$field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
						$field_values['DEPTID']   = 1;
						$field_values['MAILSUB']  = $subject1;
						$field_values['MAILCON']  = $mail_body1;
						$field_values['FILECNT']  = 0;
						$field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
						$field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
						$field_values['EMAILID']  = $emails['EMAILID'];
						$field_values['STATUS']   = 'N';
						$field_values['DEPNAME']  = 'APP DESK';
						// print_r($field_values);
						$succ = 0;
						$insert_response = insert_dbquery($field_values, $tbl_name);
					}
				}

				$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", 'Centra', 'TCS');
				$tbl_name="mail_send_summary";
				$field_values=array();
				$field_values['MAILYEAR'] = $hidapryear;
				$field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
				$field_values['DEPTID']   = 1;
				$field_values['MAILSUB']  = $subject1;
				$field_values['MAILCON']  = $mail_body1;
				$field_values['FILECNT']  = 0;
				$field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
				$field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$field_values['EMAILID']  = $to1;
				$field_values['STATUS']   = 'N';
				$field_values['DEPNAME']  = 'APP DESK';
				// print_r($field_values);
				$succ = 0;
				$insert_response = insert_dbquery($field_values, $tbl_name);
				// exit;
				//*********** Mail Send Function to User - Approval ********************
			}

			////// HIDE FINISH APPRVOAL //////
			// Update in APPROVAL_REQUEST Table
			$tbl_finapprq = "APPROVAL_REQUEST";
			$field_finapprq = array();
			$field_finapprq['FINSTAT'] = 'C';
			$field_finapprq['FINUSER'] = $_SESSION['tcs_empsrno'];
			$field_finapprq['FINCMNT'] = 'FINISHED';
			$field_finapprq['FINDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$where_finapprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'A' and ARQYEAR = '".$hid_year."' and ARQSRNO = '1' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
			// print_r($field_finapprq); //echo "<br>";
			$update_apprq = update_dbquery($field_finapprq, $tbl_finapprq, $where_finapprq);
			//echo "!!!".$update_apprq."@@@";
			// Update in APPROVAL_REQUEST Table
			////// HIDE FINISH APPRVOAL //////
			// exit;
		}

		$addiv_return1 = 8;
		if($hid_int_verification == 'F' and $_REQUEST['hid_action'] == 'sbmt_forward' and $insert_appreq == 1 and $frwrdemp[0]['EMPSRNO'] == 21344 and $_SESSION['tcs_empsrno'] != 168 and $addiv_return == 0) {
			// echo "HAI";
			$addiv_return1 = add_ivuser(21344, 168, $txt_approval_number, $maxarqcode[0]['MAXARQSRNO']); // MR. SK Sir User - 21344 & MR. NSM Sir User - 168
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
			$dl_mx = delete_dbquery("delete from approval_request where aprnumb = '".$txt_approval_number."' and ARQSRNO = '".$maxarqcode[0]['MAXARQSRNO']."'"); ?>
				<script>window.location='<?=$rturl?>';</script>
			<?php exit;
		} // echo "HORN"; exit; ?>
			<script>window.location='<?=$next_urlpath?>';</script>
		<?php exit();
	} else {
		if($addiv_return == 0) {
			// echo "POP";
			$dl_mx = delete_dbquery("delete from approval_request where aprnumb = '".$txt_approval_number."' and ARQSRNO = '".$maxarqcode[0]['MAXARQSRNO']."'");
		}
		// echo "FAIL"; exit;

		// Update in APPROVAL_REQUEST Table
		$tbl_apprq = "APPROVAL_REQUEST";
		$field_apprq['APPSTAT'] = 'N';
		$where_apprq = "ARQCODE = '".$hid_reqid."' and APPSTAT = 'F' and ARQYEAR = '".$hid_year."' and ARQSRNO = '".$hid_original_rsrid."' and ATCCODE = '".$hid_creid."' and ATYCODE = '".$hid_typeid."'";
		//print_r($field_appreq); //echo "<br>";
		$update_apprq = update_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		// echo "!!!".$update_apprq."@@@";
		// Update in APPROVAL_REQUEST Table
		// exit(); ?>
			<script>window.location='<?=$rturl?>';</script>
		<?php exit();
	}
}

$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE,
										emp.EMPNAME, ast.EXPSRNO, ast.DEPNAME, ast.EXPNAME EXPNNAME, (select
										ADDUSER||'!'||REQSTBY||'!'||RQBYDES||'!'||REQDESC||'!'||REQESEC||'!'||REQDESN||'!'||REQESEN from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and
										ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N') addeduser, (select ARQYEAR
										from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N') ARYR,
										(select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1 where e1.empsrno = r1.ADDUSER and r1.ARQCODE = req.ARQCODE and
										r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and r1.deleted = 'N') addedempuser, (select DELUSER from APPROVAL_REQUEST
										where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser,
										(select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r2, employee_office e2 where e2.empsrno = r2.DELUSER and r2.ARQCODE = req.ARQCODE and
										r2.ARQYEAR = req.ARQYEAR and r2.ARQSRNO = 1 and r2.ATCCODE = req.ATCCODE and r2.ATYCODE = req.ATYCODE and r2.deleted = 'N') deltempuser,
										to_char(req.APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
										to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
										to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time,
										to_char(req.ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMMNAME, (select ATYNAME from approval_type
										where ATYCODE = req.atycode and DELETED = 'N') aptype, (select APMNAME from approval_master where APMCODE = req.APMCODE and DELETED = 'N') apmaster,
										(select regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) BRNNAME from branch where DELETED = 'N' and BRNCODE = req.BRNCODE) branch,
										(select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ATCCODE = req.ATCCODE and
										ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE) as ADDEDDATE
									from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
										employee_office emp, approval_priority pri
									where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.BRNCODE = brn.BRNCODE
										and pri.pricode(+) = req.pricode and pri.deleted(+) = 'N' and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and
										req.ARQSRNO = '".$arsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted = 'N'
										and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K', 'T') and (req.REQSTFR = '".$usrid."' or
										req.INTPEMP = '".$usrid."') and req.appstat in ('W', 'N', 'Z') and prj.deleted = 'N' ".$usr." ".$mr_ak_date."
									order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", 'Centra', 'TEST'); // or req.REQSTBY = '".$usrid."'

if(count($sql_reqid) <= 0) {
	$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE,
											emp.EMPNAME, ast.EXPSRNO, ast.DEPNAME, ast.EXPNAME EXPNNAME, (select
											ADDUSER||'!'||REQSTBY||'!'||RQBYDES||'!'||REQDESC||'!'||REQESEC||'!'||REQDESN||'!'||REQESEN from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and
											ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N') addeduser, (select ARQYEAR
											from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and ARQYEAR = req.ARQYEAR
											and deleted = 'N') ARYR, (select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1 where e1.empsrno = r1.ADDUSER and
											r1.ARQCODE = req.ARQCODE and r1.ARQYEAR = req.ARQYEAR and r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and
											r1.deleted = 'N') addedempuser, (select DELUSER from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and
											ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser, (select EMPCODE||' - '||EMPNAME
											from APPROVAL_REQUEST r2, employee_office e2 where e2.empsrno = r2.DELUSER and r2.ARQCODE = req.ARQCODE and r2.ARQYEAR = req.ARQYEAR and
											r2.ARQSRNO = 1 and r2.ATCCODE = req.ATCCODE and r2.ATYCODE = req.ATYCODE and r2.deleted = 'N') deltempuser,
											to_char(req.APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
											to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
											to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time,
											to_char(req.ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMMNAME,
											(select ATYNAME from approval_type where ATYCODE = req.atycode and DELETED = 'N') aptype, (select APMNAME from approval_master
											where APMCODE = req.APMCODE and DELETED = 'N') apmaster, (select regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) BRNNAME
											from branch where DELETED = 'N' and BRNCODE = req.BRNCODE) branch, (select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and
											ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE)
											as ADDEDDATE
										from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
											employee_office emp, approval_priority pri
										where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.BRNCODE = brn.BRNCODE
											and pri.pricode(+) = req.pricode and pri.deleted(+) = 'N' and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and
											req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$arsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
											and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K', 'T') and
											(req.REQSTFR = '".$usrid."' or req.INTPEMP = '".$usrid."') and req.appstat in ('W', 'N', 'Z') and prj.deleted = 'N' ".$usr." ".$mr_ak_date."
										order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", 'Centra', 'TEST'); // or req.REQSTBY = '".$usrid."'
}

$viewonly = 0;
if(($_SESSION['tcs_usrcode'] == 9938358 or $_SESSION['tcs_usrcode'] == 9193333 or $_SESSION['tcs_usrcode'] == 3000000) and (count($sql_reqid) <= 0)) {
	$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE,
										emp.EMPNAME, ast.EXPSRNO, ast.DEPNAME, ast.EXPNAME EXPNNAME, (select
										ADDUSER||'!'||REQSTBY||'!'||RQBYDES||'!'||REQDESC||'!'||REQESEC||'!'||REQDESN||'!'||REQESEN from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and
										ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N') addeduser, (select ARQYEAR
										from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N') ARYR,
										(select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r1, employee_office e1 where e1.empsrno = r1.ADDUSER and r1.ARQCODE = req.ARQCODE and
										r1.ARQSRNO = 1 and r1.ATCCODE = req.ATCCODE and r1.ATYCODE = req.ATYCODE and r1.deleted = 'N') addedempuser, (select DELUSER from APPROVAL_REQUEST
										where ARQCODE = req.ARQCODE and ARQSRNO = 1 and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and deleted = 'N' and rownum <= 1) deltuser,
										(select EMPCODE||' - '||EMPNAME from APPROVAL_REQUEST r2, employee_office e2 where e2.empsrno = r2.DELUSER and r2.ARQCODE = req.ARQCODE and
										r2.ARQYEAR = req.ARQYEAR and r2.ARQSRNO = 1 and r2.ATCCODE = req.ATCCODE and r2.ATYCODE = req.ATYCODE and r2.deleted = 'N') deltempuser,
										to_char(req.APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
										to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
										to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time,
										to_char(req.ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMMNAME,
										(select ATYNAME from approval_type where ATYCODE = req.atycode and DELETED = 'N') aptype, (select APMNAME from approval_master
										where APMCODE = req.APMCODE and DELETED = 'N') apmaster,(select regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) BRNNAME
										from branch where DELETED = 'N' and BRNCODE = req.BRNCODE) branch, (select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and
										ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ATCCODE = req.ATCCODE and ATYCODE = req.ATYCODE and ATMCODE = req.ATMCODE and APMCODE = req.APMCODE) as
										ADDEDDATE
									from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
										employee_office emp, approval_priority pri
									where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.BRNCODE = brn.BRNCODE
										and pri.pricode(+) = req.pricode and pri.deleted(+) = 'N' and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and
										req.ARQYEAR = '".$_REQUEST['year']."' and prj.deleted = 'N' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' and
										req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K', 'T') and
										req.appstat in ('W', 'N', 'Z') ".$usr." ".$mr_ak_date."
									order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", 'Centra', 'TEST');
	if($sql_reqid[0]['APPFRWD'] == 'I')
		$viewonly = 0;
	else
		$viewonly = 1;
}

if($viewonly == 0 and count($sql_reqid) == 0) { ?>
	<script>alert('Already You have provided the remarks and status or you dont have rights to do this Operation'); window.location="<?=$rturl?>";</script>
<? exit();
}

$sql_tmporlive = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
											where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and but.aprsrno = 1", 'Centra', 'TEST');

$sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."' and REJUSER is null", 'Centra', 'TEST');

if(count($sql_tmporlive) > 0) {
	$rcrd = "approval_budget_planner";
} else {
	$rcrd = "approval_budget_planner_temp";
}

$sql_tarbalance = select_query_json("select R.*, ast.EXPSRNO, ast.EXPNAME EXPHEAD, to_char(R.APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(R.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
												to_char(R.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(R.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
												to_char(R.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(R.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, ast.DEPNAME,
												to_char(R.ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, R.APPRDET, R.DEPCODE, R.TARNUMB, R.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME
											from approval_request R, department_asset ast
											where R.ARQSRNO in (SELECT max(ARQSRNO) FROM approval_request where ARQCODE = R.ARQCODE and ARQYEAR = R.ARQYEAR and ATYCODE = R.ATYCODE
												and ATCCODE = R.ATCCODE) and R.ARQCODE = '".$_REQUEST['reqid']."' and R.ARQYEAR = '".$_REQUEST['year']."' and R.ATCCODE = '".$_REQUEST['creid']."'
												and R.ATYCODE = '".$_REQUEST['typeid']."' and R.deleted = 'N' and R.DEPCODE = ast.DEPCODE and ast.DELETED = 'N'
											order by R.ARQCODE, R.ARQSRNO, R.ATYCODE", 'Centra', 'TEST'); //  and R.APPSTAT = 'A'

if($_REQUEST['action'] == 'print' and $sql_reqid[0]['ARQCODE'] == '') { ?>
	<script>alert('This request is not getting any approval / You dont have rights to print this page.'); window.location="request_list.php";</script>
<? exit();
}

if($_REQUEST['action'] == 'print')
{
	$title_tag = 'Print';
}

if((strtotime($sql_reqid[0]['APPRSFR']) <= strtotime('22-APR-18')) and $sql_reqid[0]['APTYPE'] == 'NEW PROPOSAL') { $aptype_display = "EXTRA BUDGET"; }
else { $aptype_display = $sql_reqid[0]['APTYPE']; }

function approved_create_new_list($txt_approval_number) {
    //echo $txt_approval_number;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?=$sql_reqid[0]['APRNUMB']?> :: <?=$site_title?></title>
    <!-- Custom Fonts -->
    <link href="css/fontawesome/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="css/print_style.css"/>

	<style type="text/css" media="print">
	@page
	{
		size: auto;   /* auto is the initial value */
		margin: 2mm 6mm 2mm 3mm;  /* this affects the margin in the printer settings */
	}
    div.page
    {
        page-break-after: always;
        page-break-inside: avoid;
    }
    </style>

    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
	<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<link href="css/lightgallery.css" rel="stylesheet">

	<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
	<script type="text/javascript">
	$(window).load(function() {
		$(".loader").hide();
		$( "#sbmt_print" ).trigger( "click" );
	});
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
		// $(".chosn").customselect();

        $(":submit").click(function () {
        	var rmrk = $('#txt_remarks').val();
        	var slt_intermediate_team = $('#slt_intermediate_team').val();
			if(rmrk == '') {
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Your Remarks is Empty. Kindly Add some Remarks here!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				$("#txt_remarks").val('');
				$("#txt_remarks").focus('');
			} else {
				var nm = this.name;
				var txt = nm.substring(5);
				var txt1 = txt;
				if(txt == 'forward'){
					var txt = $('#sbmt_forward').val();
					// var txt = document.getElementById('sbmt_forward').value;
				}
				event.preventDefault();

				if(txt1 == 'verification' && slt_intermediate_team == '') {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Kindly Choose Internal verification user first!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					// $("#slt_intermediate_team").val('');
					$("#slt_intermediate_team").focus('');
				} else {
					okbtn = 'OK';
					switch (txt1) {
						case 'reject':
							okbtn = 'REJECT';
							break;
						case 'pending':
							okbtn = 'PENDING';
							break;
						case 'verification':
							okbtn = 'OK';
							break;
						case 'query':
							okbtn = 'OK';
							break;
						case 'approve':
							okbtn = 'APPROVE';
							break;
						case 'mdapprove':
							okbtn = 'APPROVE';
							break;
						case 'forward':
							okbtn = 'APPROVE';
							break;
						case 'response':
							okbtn = 'RESPONSE';
							break;
						default:
							okbtn = 'OK';
							break;
					}

					jConfirm('Are you sure to want to '+txt+' this!', 'Confirmation Dialog',
					function(r) {
						// alert("**"+r); exit;
						if(r == true)
						{
							$("#hid_action").val(nm);
							$("#frm_print_request").submit();
						}
					}, okbtn, 'CANCEL');
				}
    		}
		});
    });

	function call_iv() {
		var intermediate_team = $('#slt_intermediate_team').val();
		if(intermediate_team != '') {
			$('#sbmt_reject').prop("disabled", true);
			$('#sbmt_pending').prop("disabled", true);
			$('#sbmt_verification').prop("disabled", false);
			$('#sbmt_query').prop("disabled", true);
			$('#sbmt_approve').prop("disabled", true);
			$('#sbmt_mdapprove').prop("disabled", true);
			$('#sbmt_forward').prop("disabled", true);
			$('#sbmt_response').prop("disabled", true);
		} else {
			$('#sbmt_reject').prop("disabled", false);
			$('#sbmt_pending').prop("disabled", false);
			$('#sbmt_verification').prop("disabled", true);
			$('#sbmt_verification').css('background-color', '#428bca');
			$('#sbmt_query').prop("disabled", false);
			$('#sbmt_approve').prop("disabled", false);
			$('#sbmt_mdapprove').prop("disabled", false);
			$('#sbmt_forward').prop("disabled", false);
			$('#sbmt_response').prop("disabled", false);
		}
	}
	</script>
</head>
<body>
	<div id="load_page" class="loader" style='display:block;'></div>
	<?php
		$sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by apdcsrn", 'Centra', 'TEST');
		$pagecount = count($sql_docs);
		if($pagecount==0){
			$pagecount=1;
		}
		$pagearry['img'] = array();

		for($ij = 0; $ij < count($sql_docs); $ij++) {

			$filename = $sql_docs[$ij]['APRDOCS'];
			$dataurl = $sql_docs[$ij]['APRHEAD'];
			$exp = explode("_", $filename);
			switch($exp[5])
			{
				case 'i':
						$pagearry['img'][] = $ij;
						break;
				case 'n':
						$pagearry['doc'][] = $ij;
						break;
				case 'w':
						$pagearry['doc'][] = $ij;
						break;
				case 'e':
						$pagearry['doc'][] = $ij;
						break;
				case 'p':
						$pagearry['doc'][] = $ij;
						break;
				default:
						echo $fieldindi = '';
						break;
			}
		}
		$pagecount=0;
		$img_pagecount = count($pagearry['img']);
		$pagecount = $img_pagecount;

		// Non Image Docs
		$doc_pagecount = count($pagearry['doc']);
		if($doc_pagecount>0){
			$pagecount = $pagecount+1;
		}
		// Non Image Docs

		$sql_approve_leads = select_query_json("select * from APPROVAL_REQUEST ar, approval_project pr, approval_process_type pt, APPROVAL_BUDGET_MODE bm, approval_priority ap
													where ar.APRCODE = pr.APRCODE and pt.PRJPRCS(+) = ar.PRJPRCS and bm.BUDCODE(+) = ar.BUDCODE and ap.PRICODE(+) = ar.PRICODE and
														ap.deleted(+) = 'N' and bm.deleted(+) = 'N' and pt.DELETED(+) = 'N' and ar.DELETED = 'N' and pr.DELETED in ('N', 'W') and
														ar.ARQCODE = '".$_REQUEST['reqid']."' and ar.ARQYEAR = '".$_REQUEST['year']."' and ar.ATCCODE = '".$_REQUEST['creid']."' and
														ar.ATYCODE = '".$_REQUEST['typeid']."'
													order by ar.ATCCODE, ar.ARQCODE, ar.ARQSRNO desc, ar.ATYCODE", 'Centra', 'TEST');
		$sql_hir = select_query_json("with t as (select apphead from approval_mdhierarchy
											where aprnumb in ('".$sql_approve_leads[0]['APRNUMB']."')
											and apphead in (1, 2, 3))select * from t
											pivot(count(APPHEAD)for(apphead) in (1 as KS, 2 as PS, 3 as AK))", 'Centra', 'TEST'); ?>
	<div class="page">
	<form role="form" id='frm_print_request' name='frm_print_request' action='' method='post' enctype="multipart/form-data">
	<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; background-color: #ffffff; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>

	<?php if($viewonly == 0) { ?><tr>
			<td style='width:70%; height:20px; font-weight:bold; vertical-align: top;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>
			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo 1; ?>/<?php echo $pagecount+1; ?></label><br>
				<? if($_REQUEST['rsrid'] != 1){ ?>
					<label>Approve on : <?=$systemdate?><? /* .(<?=$_SESSION['tcs_user']?>) */ ?></label> <!-- Current Date & Time -->
				<? }else{ ?>
					<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
				<? } ?>
			</td>
		</tr>
	<?php }else{ ?>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo 1; ?>/<?php echo $pagecount+1; ?></label>
			</td>
		</tr>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				&nbsp;
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
			</td>
		</tr>
	<?php } ?>





		<tr>
			<td colspan=2 style="height: 140px;">
			<table width='100%'>
				<tr>
					<td rowspan=3 style='width:20%; text-align:center;'>
						<? /* if($sql_reqid[0]['APPRMRK'] == '') { ?>
							  <img src='images/original.png' style='width:100px; height:100px;' border=0>
						<? } else { ?>
							  <img src='images/duplicate.png' style='width:100px; height:100px;' border=0>
						<? } */ ?>
						<img src='images/approval_process.png' style='width:100px; height:100px;' border=0>
					</td>
					<td style='width:60%; height:25px; padding-top:10px; padding-bottom:10px; text-align:center;'>
						<label style='color:#0088CC; font-weight:bold'><? /* <span style='font-family: "freehand471","Helvetica Neue",Helvetica,Arial; color:#ff0000; font-weight:normal; font-size: 32px;'>The Chennai Silks</span> */ ?><a target="_blank" href="index.php"><img src='images/logo.png' border="0"></a></label> <!-- Chennai Silks -->
					</td>
					<td rowspan=3 style='width:20%; text-align:center;'>&nbsp;
						<? 	/* if($sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7) { /* ?>
								<img src='images/payment.png' style='width:110px; height:110px;' border=0>
						<? } */ ?>
					</td>
				</tr>

				<tr>
					<td style='width:60%; height:20px; text-align:center;'>
						<label style='color:#0088CC; font-weight:bold'>Inter Office Correspondence</label> <!-- Chennai Silks -->
					</td>
				</tr>

				<tr>
					<td style='width:60%; height:20px; text-align:center;'>
						<label style='color:#000000; font-weight:bold'>Submitting for Approval</label> <!-- Submitting For -->
					</td>
				</tr>

				<tr>
					<td colspan=3 style='width:100%; height:20px; text-align:right;'>
						<label>Date : <?=$sql_reqid[0]['ADDDATE_DATE']?><br>
							<div id="id_priority"><?
							if($sql_reqid[0]['APPSTAT'] == 'A' or $sql_reqid[0]['APPSTAT'] == 'R') {
								$sql_vl = select_query_json("select ADDDATE from APPROVAL_REQUEST
                                                                    where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO)
                                                                        from APPROVAL_REQUEST where aprnumb = '".$sql_reqid[0]['APRNUMB']."')", "Centra", 'TEST');

							    $start_time = formatSeconds(strtotime($sql_vl[0]['ADDDATE']) - strtotime($sql_reqid[0]['ADDEDDATE']));
							} else {
							    $start_time = formatSeconds(strtotime('now') - strtotime($sql_reqid[0]['ADDEDDATE']));
							}
							$sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request
                                                                where aprnumb like '".$sql_reqid[0]['APRNUMB']."' and appfrwd = 'I'
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
                            }
                            ?>
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

							// echo "**".$allow_priority."**".$_SESSION['tcs_descode']."**";
							/* if($sql_approve_leads[0]['PRICODE'] == '') {
								$sql_process_priority = select_query_json("select * from approval_priority
																				where DELETED = 'N' and PRISRNO not in (4) and prisrno in (".$sql_approve_leads[0]['PRICODE'].")
																				order by PRISRNO Asc", "Centra", "TCS");
								switch ($sql_process_priority[0]['PRICODE']) {
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

								if($allow_priority == 1) { ?>
									<input type="radio" name="slt_priority" id="slt_priority" value="1" onclick="save_priority(1, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" <? if($sql_process_priority[0]['PRICODE'] == 1) { ?> checked <? } ?>>&nbsp;<span class="badge badge-danger" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" style="font-size:20px; background-color:#FF0000; font-weight:bold;">1</span>&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority" value="2" onclick="save_priority(2, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" <? if($sql_process_priority[0]['PRICODE'] == 2) { ?> checked <? } ?>>&nbsp;<span class="badge badge-warning" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" style="font-size:20px; background-color:#D58B0A; font-weight:bold;">2</span>&nbsp;
									<input type="radio" name="slt_priority" id="slt_priority" value="3" onclick="save_priority(3, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" <? if($sql_process_priority[0]['PRICODE'] == 3) { ?> checked <? } elseif($sql_approve_leads[0]['PRICODE'] == '') { ?> checked <? } ?>>&nbsp;<span class="badge badge-success" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:20px; background-color:#299654; font-weight:bold;">3</span>&nbsp;
								<? } else { ?>
									<span class="badge badge-success" style="font-size:20px; background-color:#299654; font-weight:bold;"><? if($sql_approve_leads[0]['PRICODE'] != '') { echo $sql_approve_leads[0]['PRICODE']; } else { echo "3"; } ?></span>
							<? 	}
							} else { */

							switch ($sql_approve_leads[0]['PRICODE']) {
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
								<input type="radio" name="slt_priority" id="slt_priority_1" value="1" onclick="save_priority(1, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 1) { ?> checked <? } ?>>&nbsp;<span class="badge badge-danger" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" style="font-size:20px; background-color:#FF0000; font-weight:bold;">1</span>&nbsp;
								<input type="radio" name="slt_priority" id="slt_priority_2" value="2" onclick="save_priority(2, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 2) { ?> checked <? } ?>>&nbsp;<span class="badge badge-warning" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" style="font-size:20px; background-color:#D58B0A; font-weight:bold;">2</span>&nbsp;
								<input type="radio" name="slt_priority" id="slt_priority_3" value="3" onclick="save_priority(3, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 3) { ?> checked <? } elseif($sql_approve_leads[0]['PRICODE'] == '') { ?> checked <? } ?>>&nbsp;<span class="badge badge-success" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:20px; background-color:#299654; font-weight:bold;">3</span>&nbsp;
							<? }
							elseif($allow_priority == 2) { // HOD can move 3rd to 2nd Priority ?>
								<input type="radio" disabled name="slt_priority" id="slt_priority_1" value="1" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 1) { ?> checked <? } ?>>&nbsp;<span class="badge badge-danger" title="DO RIGHT AWAY - URGENT AND IMPORTANT [ Maximum 1 Days Allowed ]" style="font-size:20px; background-color:#FF0000; font-weight:bold;">1</span>&nbsp;
								<input type="radio" name="slt_priority" id="slt_priority_2" value="2" onclick="save_priority(2, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 2) { ?> checked <? } ?>>&nbsp;<span class="badge badge-warning" title="PLAN TO DO ASAP - NOT URGENT BUT IMPORTANT [ Maximum 2 Days Allowed ]" style="font-size:20px; background-color:#D58B0A; font-weight:bold;">2</span>&nbsp;
								<input type="radio" name="slt_priority" id="slt_priority_3" value="3" onclick="save_priority(3, '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$rsrid?>')" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" <? if($sql_approve_leads[0]['PRICODE'] == 3) { ?> checked <? } elseif($sql_approve_leads[0]['PRICODE'] == '') { ?> checked <? } ?>>&nbsp;<span class="badge badge-success" title="DELEGATE - URGENT BUT NOT IMPORTANT [ Maximum 3 Days Allowed ]" style="font-size:20px; background-color:#299654; font-weight:bold;">3</span>&nbsp;
							<? } else { ?>
								<span class="badge badge-success" style="font-size:20px; background-color:<?=$clrcod1?>; font-weight:bold;"><? if($sql_approve_leads[0]['PRICODE'] != '') { echo $sql_approve_leads[0]['PRICODE']; } else { echo "3"; } ?></span>
									<input type="hidden" name="slt_priority" id="slt_priority" value="<?=$sql_approve_leads[0]['PRICODE']?>">
							<? } ?>
							</div>
						</label> <!-- Created Date -->
					</td>
				</tr>
			</table>
			</td>
		</tr>

		<?	$kind_attn = 'Sri. KS Sir';
			switch ($sql_approve_leads[0]['REQSTBY']) {
				case 21344:
					$kind_attn = 'Mr. SK Sir';
					break;

				default:
					$kind_attn = 'Sri. KS Sir';
					break;
			}

			$sql_approve_comnts = select_query_json("select req.*, regexp_replace(SubStr(req.REQESEN,1,4),'[0-9]','')||SubStr(req.REQESEN,5,100) REQESEN,
																to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') HISTIME, to_char(ADDDATE,'dd-MON-yyyy') RQADDDATE
															from APPROVAL_REQUEST req
															where DELETED = 'N' and ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and
																ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and ARQSRNO not in (1)
															order by ATCCODE, ARQCODE, ARQSRNO desc, ATYCODE", 'Centra', 'TEST');

			$exp1 = explode("!", $sql_reqid[0]['ADDEDUSER']);
			$empsect = $exp1['4'];
			$creator = $exp1['2'];
			$adduser = $exp1['0'];
			$creator_dept1 = explode(" ", $exp1['6']);
			$creator_dept = $creator_dept1[1]." ".$creator_dept1[2]." ".$creator_dept1[3]." ".$creator_dept1[4]." ".$creator_dept1[5];
			$count_attachment = $sql_approve_leads[0]['APPATTN'];
			$find_lead = '';
			unset($gm_cmnts); unset($hod_sign); unset($all_cmnts); $alaprcomments = 0;
			$folder_path = "approval_desk/digital_signature/";
			for($sql_approve_leadsi = 0; $sql_approve_leadsi < count($sql_approve_comnts); $sql_approve_leadsi++) {
				// $alaprcomments++;
				$find_lead = $sql_approve_comnts[$alaprcomments]['REQDESC'];
				$his_time[] = $sql_approve_comnts[$alaprcomments]['HISTIME'];
				$find_leads[] = $find_lead;
				// echo "<br>**".$sql_approve_comnts[$alaprcomments]['RQBYDES']."**".$find_lead."**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_approve_comnts[$alaprcomments]['APPFRWD']."**".$alaprcomments."**".$find_lead."**";
				switch($find_lead)
				{
					case 3: // Manager
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 137) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Cost Control */
									$cc_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
									unset($cc_cmnts);
									for($cci = 0; ($cci < count($cc_cmnts1)) and ($cc_cmnts1[0]['APPRMRK'] != ''); $cci++) {
										// if($cc_cmnts1[$cci]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$cc_cmnts[] = $cc_cmnts1[$cci]['APPRMRK'];
										// }
									}

									$cc_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$cc_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$cc_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$cc_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $cc_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$cc_empsrno.".png";
									$cc_sign = "../ftp_image_view.php?pic=".$cc_empsrno.".png&path=".$folder_path."";
									$cc_desg = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'COST CONTROL ';
									/* if($sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'I') {
										$comnts_rmrk[] = ' IV TO '.$sql_approve_comnts[$alaprcomments]['RQFRDES'];
									} */ // IV to users
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Cost Control */
							} elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 188 or $sql_approve_comnts[$alaprcomments]['REQSTBY'] == 62762) { // echo "!!";
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') { // echo "@@";
									/* Manager */
									$srexc_mgr_name_audit = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc_audit = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno_audit = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate_audit = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $srexc_mgr_sign_audit = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$srexc_mgr_empsrno_audit.".png";
									$srexc_mgr_sign_audit = "../ftp_image_view.php?pic=".$srexc_mgr_empsrno_audit.".png&path=".$folder_path."";
									$srexc_mgr_desg = "S-AUDIT<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;

								break;
								/* Manager */
							} else {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Manager */
									$srexc_mgr_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
									$srexc_mgr_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Manager */
							}

					case 92: // TCS BM
					case 67: // TJ / KTM BM
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* Branch Manager
								$mgr_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$mgr_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$mgr_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$mgr_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								} */

								$bm_name[]    = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$bm_desc[]    = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$bm_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$bm_dept[]    = $sql_approve_comnts[$alaprcomments]['REQESEN'];
								$bm_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									$bm_sign[] = "../ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									$bm_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".'BM';
								} else {
									$bm_sign[] = "";
									$bm_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".'BM';
								}
								/* Read HOD sigature from FTP */
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* Branch Manager */

					case 96:
							/* S-Team Audit */
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 188 or $sql_approve_comnts[$alaprcomments]['REQSTBY'] == 62762) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* S-Team Audit Senior Manager */
									$srexc_mgr_name_audit = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc_audit = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno_audit = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate_audit = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $srexc_mgr_sign_audit = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$srexc_mgr_empsrno_audit.".png";
									$srexc_mgr_sign_audit = "../ftp_image_view.php?pic=".$srexc_mgr_empsrno_audit.".png&path=".$folder_path."";
									$srexc_mgr_desg = "S-AUDIT<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* S-Team Audit Senior Manager */
							} elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							} else {
								/* S-Team Audit */
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* S-Team Audit */
							}
							break;
							/* S-Team Audit */

					case 133:
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							} else {
								/* Sr. Executive */
								$srexc_mgr_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$srexc_mgr_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$srexc_mgr_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$srexc_mgr_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Sr. Executive */
							}

					case 134:
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 118) // DB
							{
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Executive */
									$exc_mgr_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$exc_mgr_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$exc_mgr_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$exc_mgr_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Executive */
							} else {
								/* Executive */
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Executive */
							}
							break;

					case 132: // TCS HOD
					case 69: // KTM / TJ HOD
							// echo "<br>CAME".$sql_approve_comnts[$alaprcomments]['RQBYDES'];
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								// echo "<br>**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_reqid[0]['REQESEC']."**";
								//////////////// if($sql_approve_comnts[$alaprcomments]['REQESEC'] == $sql_reqid[0]['REQESEC']) {
								/* HOD */
								$hod_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$hod_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$hod_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$hod_dept[] = $sql_approve_comnts[$alaprcomments]['REQESEN'];
								$hod_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									// echo "<br>HOD-".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "../ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>"."HOD";
								} else {
									// echo "<br>HOD+".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>"."HOD";
								}
								/* Read HOD sigature from FTP */
							}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
							///////////////////////// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* HOD */
					case 189:
							// echo "<br>CAME".$sql_approve_comnts[$alaprcomments]['RQBYDES'];
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								// echo "<br>**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_reqid[0]['REQESEC']."**";
								//////////////// if($sql_approve_comnts[$alaprcomments]['REQESEC'] == $sql_reqid[0]['REQESEC']) {
								/* DGM */
								$hod_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$hod_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$hod_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$hod_dept[] = $sql_approve_comnts[$alaprcomments]['REQESEN'];
								$hod_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								// echo "++++".file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")."++++"."ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png"."++++";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									// echo "<br>HOD/".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "../ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".'DGM';
								} else {
									// echo "<br>HOD*".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQESEN']."<br>".'DGM';
								}
								/* Read HOD sigature from FTP */
							}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
							///////////////////////// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* DGM */

					case 150:
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							}
							elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 95) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							}
							elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 950) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - Legal */
									$legal_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$legal_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$legal_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$legal_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - Legal */
							} else {
								/* Others */
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Others */
							}
							break;

					case 75:
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 137) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Cost Control */
									$cc_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
									unset($cc_cmnts);
									for($cci = 0; ($cci < count($cc_cmnts1)) and ($cc_cmnts1[0]['APPRMRK'] != ''); $cci++) {
										// if($cc_cmnts1[$cci]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$cc_cmnts[] = $cc_cmnts1[$cci]['APPRMRK'];
										// }
									}

									$cc_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$cc_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$cc_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$cc_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $cc_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$cc_empsrno.".png";
									$cc_sign = "../ftp_image_view.php?pic=".$cc_empsrno.".png&path=".$folder_path."";
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'COST CONTROL ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Cost Control */
							} else {
								/* MANAGER */
								$mgr_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$mgr_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$mgr_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$mgr_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* MANAGER */
								break;
							}

					case 19:
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* GM */
								$gm_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$gm_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$gm_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$gm_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$gm_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
								for($gmi = 0; ($gmi < count($gm_cmnts1)) and ($gm_cmnts1[0]['APPRMRK'] != ''); $gmi++) {
									// if($gm_cmnts1[$gmi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$gm_cmnts[] = $gm_cmnts1[$gmi]['APPRMRK'];
									// }
								}

								/* Read GM sigature from FTP */
								// $gm_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$gm_empsrno.".png";
								$gm_sign[] = "../ftp_image_view.php?pic=".$gm_empsrno.".png&path=".$folder_path."";
								/* Read GM sigature from FTP */
							}

							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 168) {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'MANAGEMENT GM ';
									$comnts_gmuser[] = 'MANAGEMENT ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
							} elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 452) {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'ADMIN GM ';
									$comnts_gmuser[] = 'ADMIN ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
							} elseif($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'OPERATION GM ';
									$comnts_gmuser[] = 'OPERATION ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
							}
							$row_inc[] = $alaprcomments;
							break;
							/* GM */

					case 165:
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* SR. GM */
								$srgm_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$srgm_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$srgm_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$srgm_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$srgm_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
								unset($srgm_cmnts);
								for($srgmi = 0; ($srgmi < count($srgm_cmnts1)) and ($srgm_cmnts1[0]['APPRMRK'] != ''); $srgmi++) {
									// if($srgm_cmnts1[$srgmi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$srgm_cmnts[] = $srgm_cmnts1[$srgmi]['APPRMRK'];
									// }
								}

								/* Read SR. GM sigature from FTP */
								// $srgm_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$srgm_empsrno.".png";
								$srgm_sign = "../ftp_image_view.php?pic=".$srgm_empsrno.".png&path=".$folder_path."";
								/* Read SR. GM sigature from FTP */
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = 'SR GM ';
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* SR. GM */

					case 78:
							$ceo_available = 0;
							// echo "**".$sql_approve_comnts[$alaprcomments]['REQSTBY']."**".$sql_approve_comnts[$alaprcomments]['APPFRWD']."**";
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 21344 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
							{
								/* AK */
								$ak_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$ak_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$ak_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$ak_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$ak_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
								unset($ak_cmnts);
								for($aki = 0; ($aki < count($ak_cmnts1)) and ($ak_cmnts1[0]['APPRMRK'] != ''); $aki++) {
									// if($ak_cmnts1[$aki]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$ak_cmnts[] = $ak_cmnts1[$aki]['APPRMRK'];
									// }
								}

								/* Read AK sigature from FTP */
								// $ak_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$ak_empsrno.".png";
								/////////////////// $ak_sign = "../ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // OPEN
								$ak_sign = "../ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // CLOSE
								/* Read AK sigature from FTP */
								/* AK */
							} else {
								$ceo_available = 1;
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = 'S KAARTHI SIR ';
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;

					case 9:
							$cao_available = 0;
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 43400 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
							{
								/* PS Madam */
								$ps_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$ps_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$ps_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$ps_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$ps_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TEST");
								unset($ps_cmnts);
								for($psi = 0; ($psi < count($ps_cmnts1)) and ($ps_cmnts1[0]['APPRMRK'] != ''); $psi++) {
									// if($ps_cmnts1[$psi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$ps_cmnts[] = $ps_cmnts1[$psi]['APPRMRK'];
									// }
								}

								/* Read PS sigature from FTP */
								// $ps_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$ps_empsrno.".png";
								$ps_sign = "../ftp_image_view.php?pic=".$ps_empsrno.".png&path=".$folder_path."";
								/* Read PS sigature from FTP */

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'PS MADAM ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* PS Madam */
							} else {
								$cao_available = 1;
							}

							$coo_available = 0;
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 20118 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'I' || $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'P' || $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'Q')
							{
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							}
							elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 20118 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
							{
								/* KS Sir */
								$ks_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$ks_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$ks_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$ks_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$ks_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", "Centra", "TCS");
								unset($ks_cmnts);
								for($ksi = 0; ($ksi < count($ks_cmnts1)) and ($ks_cmnts1[0]['APPRMRK'] != ''); $ksi++) {
									// if($ks_cmnts1[$ksi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$ks_cmnts[] = $ks_cmnts1[$ksi]['APPRMRK'];
									// }
								}

								/* Read KS sigature from FTP */
								// $ks_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server$ftp_srvport_apdsk/approval_desk/digital_signature/".$ks_empsrno.".png";
								$ks_sign = "../ftp_image_view.php?pic=".$ks_empsrno.".png&path=".$folder_path."";
								/* Read KS sigature from FTP */

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'KS SIR ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* KS Sir */
							} else {
								$coo_available = 1;
							}
							break;
					default:
							// echo "---------".$sql_approve_comnts[$alaprcomments]['RQBYDES']."---------";
							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
				}
			} ?>
		<tr>
		<td colspan=2 style='width:100%; vertical-align:top; text-align:left;'>
			<table border=0 style='width:100%; max-width: 773px; '>

				<tr><td>
				<? /* <table border=0 style='width:100%; min-height:660px; height:auto;'> */ ?>
				<table border=0 style='width:100%;'>
				<tr style='min-height:25px !important; line-height:25px !important;'>
					<td colspan=2 style='width:100%; height:20px; text-align:left;'>
						<label>Good Day Sir,</label> <!-- Good Day Sir, -->
					</td>
				</tr>

				<tr style='min-height:20px; line-height:20px;'>
					<td style='font-size:16px; font-weight:bold; width:20%; text-align:left;'>
						<label>Subject</label> <!-- Approval Listings -->
					</td>
					<td style='width:80%; text-align:left;'>
						<label>: <span style=" font-size: 18px; font-weight: bold;" class="blue_highlight"><?=$sql_reqid[0]['APMASTER'].$sql_reqid[0]['DYNSUBJ'].$sql_reqid[0]['TXTSUBJ']?></span></label> <!-- Approval Listings -->
					</td>
				</tr>

				<tr style='min-height:25px; line-height:25px;'>
					<td style='width:20%; padding-top:5px; text-align:left;'>
						<label style=' font-size:16px; font-weight:bold'>Branch / Project</label> <!-- Project Name -->
					</td>
					<td style='width:80%; padding-top:5px; text-align:left;'>
						<table style="width: 100%">
						<tr style='min-height:20px; line-height:20px;'>
							<td style='width:70%; text-align:left;'>
								<label>: <label style=' font-size:16px; font-weight:bold;'><?=$sql_reqid[0]['BRANCH']?> / </label><label style=' font-size:16px; font-weight:bold;border: 0px solid #00a1ff;padding: 3px;'><?=$sql_approve_leads[0]['APRCODE']." - ".$sql_approve_leads[0]['APRNAME']?></label> [ <?=$sql_approve_leads[0]['PRSTYPE'];?> ] <!-- Project Name -->
							</td>

							<? if($sql_reqid[0]['APRQVAL'] > 0 and $sql_reqid[0]['APPTYPE'] != '') { ?>
								<td style='width:15%; text-align:left;'>
									<label>Approval Type</label> <!-- Approval Type -->
								</td>
								<td style='width:15%; text-align:left;'>
									<label>: <span style=" font-size: 14px; font-weight: bold;" <? if($sql_reqid[0]['APPTYPE'] == 'ASSET') { ?> class="green_highlight" <? } else { ?> class="red_highlight" <? } ?>><? echo $sql_reqid[0]['APPTYPE']; ?></span></label> <!-- Approval Type -->
								</td>
							<? } ?>
						</tr>
						</table>
					</td>
				</tr>

				<? if($sql_reqid[0]['APRQVAL'] > 0) { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Exp. Head / Department</label> <!-- Exp. Head / Department -->
						</td>
						<td style='width:80%; text-align:left;'>
							<table style="width: 100%">
							<tr style='min-height:20px; line-height:20px;'>
								<td style='width:100%; text-align:left;'>
									<label>: <?=$sql_tarbalance[0]['EXPHEAD']?> / <?=$sql_tarbalance[0]['DEPNAME']?></label> <!-- Exp. Head / Department -->
								</td>
							</tr>
							</table>
						</td>
					</tr>
				<? } else { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Approval Mode</label> <!-- Specification -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <?=$aptype_display?></label> <!-- Specification -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RELAPPR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Related Approval Nos</label> <!-- Related Approval Nos -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>:
							<? 	$sql_rlapr = explode(",", $sql_reqid[0]['RELAPPR']);
								for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
									$sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
																		where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST"); ?>
									<a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
								<? } ?></label> <!-- Related Approval Nos -->
						</td>
					</tr>
				<? } ?>

				<? $appr_againstno = 0;
				if($sql_reqid[0]['AGNSAPR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Against Approval No</label> <!-- Against Approval No -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>:
							<? 	$sql_rlapr = explode(",", $sql_reqid[0]['AGNSAPR']);
								for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
									$sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
																		where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST");
									if(count($sql_apr) > 0) { $appr_againstno = 1; } ?>
									<a class="red_highlight" target="_blank" href='print_request.php?action=print&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&agnpr=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:bold; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
								<? } ?></label> <!-- Against Approval No -->
						</td>
					</tr>
				<? } ?>

				<?  if(count($sql_approve_leads) > 0 && $sql_reqid[0]['APRQVAL'] > 0) { ?>
				<tr style='min-height:20px; line-height:20px;'>
					<td style='width:20%; text-align:left;'>
						<label>Budget Mode</label> <!-- Budget Mode -->
					</td>
					<td style='width:80%; text-align:left;'>
						<table style="width: 100%">
						<tr style='min-height:20px; line-height:20px;'>
							<td style='width:100%; text-align:left;'>
								<label>: <span style="font-size: 14px;<? if($aptype_display == "NEW PROPOSAL" or $aptype_display == "EXTRA BUDGET"){?> font-weight:bolder;<?}?>" <? if($aptype_display == "NEW PROPOSAL" or $aptype_display == "EXTRA BUDGET"){?> class="red_highlight" <?}?> ><? echo $aptype_display; ?></span> / <span style="font-size: 12px; font-weight: bold; " class="blue_highlight"><? echo $sql_approve_leads[0]['BUDNAME']; if($sql_reqid[0]['APRQVAL'] > 0) { ?></span> <span style="font-size: 10px;">[ Target NO - <b><? echo $sql_tarbalance[0]['TARNUMB']." - ".$sql_tarbalance[0]['TARDESC']; ?></b> ]</span><? } if($sql_reqid[0]['ATYCODE'] == 7) { ?><br><b>Reserved Budget Balance against Expense Head [ <span class="clrblue"><?=$sql_tarbalance[0]['EXPHEAD']?></span> ]</b> &#8377;
									<? 	$target_balance = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) +
																						sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE, (sum(distinct nvl(sm.BUDVALUE, 0)) -
																						sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) RESVALUE
																					from budget_planner_head_sum sm, approval_budget_planner_temp tm
																					where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N'
																						and sm.BRNCODE=".$sql_reqid[0]['BRNCODE']." and sm.BUDYEAR = '".$hidapryear."' and
																						sm.EXPSRNO = ".$sql_reqid[0]['EXPSRNO']."", 'Centra', 'TEST');
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
										echo "<b style='padding: 5px;font-size:16px;color:#FF0000;border: 1px solid #a0a0a0;'>".moneyFormatIndia($balance)."</b>";
									}
									?>
								</label> <!-- Budget Mode -->
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<? } ?>

				<? if($sql_reqid[0]['APRQVAL'] > 0 and $sql_tarbalance[0]['SUPNAME'] != '' and count($sql_prdlist) <= 0) { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Supplier Details</label> <!-- Supplier Details -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <b><? if($sql_tarbalance[0]['SUPCODE'] != '') { echo $sql_tarbalance[0]['SUPCODE']." - "; } echo $sql_tarbalance[0]['SUPNAME']." - ".$sql_tarbalance[0]['SUPCONT']; ?></b></label> <!-- Supplier Details -->
						</td>
					</tr>
				<? } ?>

				<? 	$show_supplierlist = 0;
					if(count($sql_reqd) > 0) { $show_supplierlist = 1; }
					if($_REQUEST['action'] == '') { $show_supplierlist = 1; }

				if($show_supplierlist == 0) { ?>
				<tr style='min-height:20px; line-height:20px;'>
					<td colspan=2 style='width:100%; text-align:left;'></td>
				</tr>
				<? } ?>

				<? /* if($sql_reqid[0]['APMCODE'] == 802) {
					$sql_proj = select_query_json("select * from approval_project where deleted = 'W' order by APRCODE desc", 'Centra', 'TEST'); ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Project ID & Name</label> <!-- Project ID & Name -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <b class="blue_highlight" style="font-size: 16px;">
								<? 	if(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[0]['APRCODE'])) { echo $sql_proj[0]['APRCODE']." - ".$sql_proj[0]['APRNAME']; }
									elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[1]['APRCODE'])) { echo $sql_proj[1]['APRCODE']." - ".$sql_proj[1]['APRNAME']; }
									elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[2]['APRCODE'])) { echo $sql_proj[2]['APRCODE']." - ".$sql_proj[2]['APRNAME']; }
									elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[3]['APRCODE'])) { echo $sql_proj[3]['APRCODE']." - ".$sql_proj[3]['APRNAME']; }
									elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[4]['APRCODE'])) { echo $sql_proj[4]['APRCODE']." - ".$sql_proj[4]['APRNAME']; } ?>
									</b></label> <!-- Project ID & Name -->
						</td>
					</tr>
				<? } */ ?>
				<!-- Approval Type & Responsible Person -->
				<tr style='height:20px;'><td></td></tr>


				<tr style='min-height:20px !important; max-width: 773px; line-height:20px !important;'>
					<td colspan=2>
						<table border=0 width='100%' style='max-width: 773px; border: 1px solid #0088CC; min-height: 70px; height:auto; padding:3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;'>
							<tr style='min-height:30px; vertical-align:top; line-height:30px; font-size: 11px;'>
								<td style='width:20%; text-align:left;'>
									<table style="width: 100%;">
									<tr style='min-height:30px; width: 100%; vertical-align:top; line-height:30px; font-size: 11px;'>
										<td style='width:40%; text-align:left;'>
											<label>Details : </label> <!-- Details -->
										</td>
										<td style='width:60%; text-align:right;'>
											<? if($sql_approve_leads[0]['APPRFOR'] == '1') { ?>
												<label><a href="javascript:void(0)" onclick="popup_original_details('<? echo $sql_reqid[0]['ARQCODE']; ?>', '<? echo $sql_reqid[0]['ARQYEAR']; ?>', '<? echo $sql_reqid[0]['APRNUMB']; ?>')" style="text-transform: uppercase; font-weight: bold;" title="Original Details" class="blue_highlight"><i class="fa fa-paperclip"></i> Original Details</a></label> <!-- Original Details -->
											<? } ?>
										</td>
									</tr>
									</table>
								</td>
							</tr>

							<tr>
								<td style='width:100%; text-align:left;'>
									<label><?
										if($sql_approve_leads[0]['APPRFOR'] == '1') {
	                                        $filepathname = $sql_approve_leads[0]['APPRSUB'];
	                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
	                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_approve_leads[0]['APPRSUB']);
	                                        $contents = fread($handle, filesize($filename));
	                                        fclose($handle);
	                                        echo $contents;
	                                    } else {
	                                        echo $sql_approve_leads[0]['APPRDET'];
	                                    } ?></label> <!-- Details -->
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<table style="width:100%">
										<thead>
											<tr>
												<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">S.NO</th>
												<th  class="colheight" style="padding:5px 0px;width: 20%;background:#007cff;color:#fff">TYPE OF SUBMISSION</th>
												<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">TARGET NUMBER</th>
												<th  class="colheight" style="padding:5px 0px;width: 20%;background:#007cff;color:#fff">SUBJECT</th>
												<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">TOP CORE</th>
												<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">SUB CORE</th>
												<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">EMPLOYEE</th>
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
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?echo $sno;?></td>
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
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
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
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
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?echo $sectionrow['APMNAME'];?></td>
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
													if ($sectionrow['TOPCORE'] == '0') {
														echo "- NILL -";
													}else {
														$sql_descode=select_query_json("SELECT ATCNAME from APPROVAL_TOPCORE where ATCCODE = '".$sectionrow['TOPCORE']."' and DELETED = 'N' ORDER BY ATCSRNO", "Centra", "TCS");
														foreach($sql_descode as $sectionrowe) {
															echo $sectionrowe['ATCNAME'];
														}
													}
													?></td>
													<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
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

													<td class="colheight" style="padding: 0px;width: 10%;text-align:center">
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
								</td>
							</tr>

							<tr><td colspan='2'>
								<table class="monthyr_wrap" style='width:100%; line-height:22px;'>
								<tr><td width="25%"></td><td width="25%"></td><td width="25%"></td><td width="25%"></td></tr>
								<tr style='border:1px solid #0088CC; width:100%;'>
								<?
									if(count($sql_prdlist) > 0) {
										$edtvl = 0;
										// $edtvl = 1;
										$displaynone = ' display: none; ';
									} else {
										$edtvl = 1;
										$displaynone = '';
									}

									if($edtvl == 1) {
										// echo "select * from approval_budget_planner where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO";
										if($tmporlive == 0) {
											if($_SESSION['tcs_user'] == 17108) {
												$sql_plan = select_query_json("select * from approval_budget_planner_temp
																					where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
											} else {
												$sql_plan = select_query_json("select * from approval_budget_planner_temp
																					where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
											}
										} else {
											$sql_plan = select_query_json("select * from approval_budget_planner
																					where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
										}

										$ijk = 0;
										for($plani = 0; $plani < count($sql_plan); $plani++) {
											if($sql_plan[$plani]['APPRVAL'] > 0) { $ijk++;
												$total_amt = $sql_plan[$plani]['APPRVAL'] + $sql_plan[$plani]['RESVALU'];
												if($ijk == 1) { ?>
													<td width="25%" style='border:1px solid #0088CC; padding: 2px;'><table style='width:100%;'>
												<? } ?>
													<tr style='border:1px solid #0088CC; width:100%;'>
														<td width="40%" style='text-align:right;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRPRID']?>'><span><? $vlmn = explode(",", $sql_plan[$plani]['APRMNTH']); echo $vlmn[0]."-".$vlmn[1]; ?></span> : </td>
														<td width="58%" style='text-align:right;'><? if($_REQUEST['action'] == 'edit') { ?><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control ttlsum ttlsumrequired' value='<?=$sql_plan[$plani]['APPRVAL']?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero(<?=$plani?>, this.value, <?=$total_amt?>);' maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control ttlsum ttlsumrequired' value='<?=$sql_plan[$plani]['APPRVAL']?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero(<?=$plani?>, this.value);' maxlength='10' style='margin: 2px 0px;'><?=moneyFormatIndia($sql_plan[$plani]['APPRVAL'])?><? } ?></td>
														<td width="2%"></td>
													</tr>
											<? if($ijk == 3) { $ijk = 0; ?>
												</table></td>
										<? } } }

										if($ijk != 12) { ?></table><? }
								} elseif($edtvl == 0) { ?>
									<div <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>>
									<table style='clear:both; float:left; width:100%;'>
									<tr><td><div id='id_budplanner' <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>></div></td></tr>
									<tr><td>
										<table class="monthyr_wrap" style='width:100%; line-height:22px; <?=$displaynone?>'>
											<? 	if($tmporlive == 0) {
													if($_SESSION['tcs_user'] == 17108) {
														$sql_plan = select_query_json("select * from approval_budget_planner_temp
																							where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
													} else {
														$sql_plan = select_query_json("select * from approval_budget_planner_temp
																							where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
													}
												} else {
													$sql_plan = select_query_json("select * from approval_budget_planner
																						where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
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

									<!-- Supplier Quotation -->
									<table style="width: 100%; line-height: 15px;">
										<? if(count($sql_prdlist) > 0) {
											$inc = 0; $prdcnt = 0;
											foreach($sql_prdlist as $prdlist) { $inc++; $prdcnt++;
											$sql_prdquotlist = select_query_json("select qut.SUPCODE, qut.SUPNAME, cty.CTYNAME, sup.SUPMOBI, qut.DELPRID, qut.SUPRMRK
																								from APPROVAL_PRODUCT_QUOTATION qut, supplier_asset sup, city CTY
										    													where qut.supcode = sup.supcode and cty.ctycode = sup.ctycode and qut.SLTSUPP = 1 and
										    														qut.PBDCODE = '".$prdlist['PBDCODE']."' and qut.PBDYEAR = '".$prdlist['PBDYEAR']."' and
										    														qut.PBDLSNO = '".$prdlist['PBDLSNO']."'
											    										union
											    											select qut.SUPCODE, qut.SUPNAME, cty.CTYNAME, sup.SUPMOBI, qut.DELPRID, qut.SUPRMRK
																								from APPROVAL_PRODUCT_QUOTATION qut, supplier sup, city CTY
										    													where qut.supcode = sup.supcode and cty.ctycode = sup.ctycode and qut.SLTSUPP = 1 and
										    														qut.PBDCODE = '".$prdlist['PBDCODE']."' and qut.PBDYEAR = '".$prdlist['PBDYEAR']."' and
										    														qut.PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', 'TEST'); ?>
									    <tr><td>Supplier : <b style="font-size: 14px; font-weight: bold;"><?=$sql_prdquotlist[0]['SUPCODE']." - ".$sql_prdquotlist[0]['SUPNAME']; ?></b>; <span style="font-size: 9px;color: #a0a0a0;">( Delivery Duration : <?=$sql_prdquotlist[0]['DELPRID']?> Days ) ( <?=$sql_prdquotlist[0]['SUPRMRK']?> )</span></td></tr>
										<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
											<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
											<td class="colheight" style="padding: 0px;width: 40%;">Product / Sub Product</td>
								            <td class="colheight" style="padding: 0px;width: 10%;">Per Piece Rate &#8377</td>
								            <td class="colheight" style="padding: 0px;width: 17%;">Tax &#8377</td>
								            <td class="colheight" style="padding: 0px;width: 11%;">Discount %</td>
								            <td class="colheight" style="padding: 0px;width: 7%;">Qty.</td>
								            <td class="colheight" style="padding: 0px;width: 12%;">Net Amount &#8377</td>
										</tr>
									<? 	$sql_prdquotlist1 = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
								    													where PBDCODE = '".$prdlist['PBDCODE']."' and SLTSUPP = 1 and
								    														PBDYEAR = '".$prdlist['PBDYEAR']."' and PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', 'TEST'); ?>
											<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
												<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$inc?><br>
													<input type="checkbox" name="chk_reject_reason[<?=$prdlist['PBDLSNO']?>]" id="chk_reject_reason_<?=$prdlist['PBDLSNO']?>" class="common_style" checked onclick="reject_reason(<?=$prdlist['PBDLSNO']?>)">
													<input type="hidden" name="hidchk_reject_reason[]" id="hidchk_reject_reason_<?=$prdlist['PBDLSNO']?>" value="<?=$prdlist['PBDLSNO']?>">
												</td>
												<td class="colheight" style="padding: 1px 0px 1px 3px; width: 39%; text-align: left;">
													<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?> / <?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?> <br>
													<span style="font-size: 9px;color: #a0a0a0;">( <?=$prdlist['PRDSPEC']?> )
														<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "AD. DURATION : ".$prdlist['ADURATI'].""; } ?>
										    			<? if($prdlist['ADLENGT'] == '0' and $prdlist['ADWIDTH'] == '0') { echo ""; }
										    			   else { echo "SIZE ( L X W ) : ".$prdlist['ADLENGT']." X ".$prdlist['ADWIDTH'].""; } ?>
										    			<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "AD. PRINT LOCATION : ".$prdlist['ADLOCAT'].""; } ?></span><br>

													<? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin:0px;'>";
		                                                if($prdlist['PRDIMAG'] != '-' and $prdlist['PRDIMAG'] != '') {
		                                                    $dataurl = $prdlist['PBDYEAR'];
		                                                    $filename = strtolower($prdlist['PRDIMAG']);
		                                                    switch(strtolower(find_indicator_fromfile($prdlist['PRDIMAG'])))
		                                                    {
		                                                        case 'i':
		                                                                $folder_path = "approval_desk/product_images/".$dataurl."/";
		                                                                $thumbfolder_path = "approval_desk/product_images/".$dataurl."/thumb_images/";

		                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:70px; height:70px;' class=\"img-responsive style_box\" style=\"padding: 2px 5px;\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
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
		                                                }
		                                              echo "</ul>"; ?><div style="display: none;" id='id_reason_reject_<?=$inc?>'>&nbsp;&nbsp;&nbsp;Reject Reason : <br><input type="text" name="hid_reason_reject[<?=$prdlist['PBDLSNO']?>][]" id="hid_reason_reject_<?=$inc?>" placeholder="Reject Reason" maxlength="100" value="" style="text-transform: uppercase;"></div>
												</td>
									    		<td class="colheight" style="padding: 1px 0px; width: 10%;"><? $expl1 = explode(".", $sql_prdquotlist1[0]['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?><br><span style="font-size: 9px;color: #a0a0a0;">Adv. Amt. : <?=moneyFormatIndia($sql_prdquotlist1[0]['ADVAMNT'])?></span></td>

									            <td class="colheight" style="padding: 1px 0px; width: 17%;">
									    			<? if($sql_prdquotlist1[0]['SGSTVAL'] == '0' ) { echo ""; } else { $sc_per = 0; $sc_per = round(($sql_prdquotlist1[0]['SGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "SGST (".$sc_per." %) : ".$sql_prdquotlist1[0]['SGSTVAL']."<BR>"; } ?>
									    			<? if($sql_prdquotlist1[0]['CGSTVAL'] == '0' ) { echo ""; } else { $cc_per = 0; $cc_per = round(($sql_prdquotlist1[0]['CGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "CGST (".$cc_per." %) : ".$sql_prdquotlist1[0]['CGSTVAL']."<BR>"; } ?>
									    			<? if($sql_prdquotlist1[0]['IGSTVAL'] == '0' ) { echo ""; } else { $ic_per = 0; $ic_per = round(($sql_prdquotlist1[0]['IGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "IGST (".$ic_per." %) : ".$sql_prdquotlist1[0]['IGSTVAL']."<BR>"; } ?>
									    		</td>
									            <td class="colheight" style="padding: 1px 0px; width: 11%;">
									    			<? if($sql_prdquotlist1[0]['DISCONT'] == '0' ) { echo ""; } else { $ds_per = 0; $ds_per = round(($sql_prdquotlist1[0]['DISCONT'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo /* "DISCOUNT : " */ "(".$ds_per." %) ".$sql_prdquotlist1[0]['DISCONT']."<BR>"; } ?>
									    		</td>

									            <td class="colheight" style="padding: 1px 0px; width: 7%;"><?=$prdlist['TOTLQTY']?></td>
									            <td class="colheight" style="padding: 1px 0px; width: 12%; margin-right: 0.6%;"><?=moneyFormatIndia(round($sql_prdquotlist1[0]['NETAMNT']))?><br>
									            	<? if($sql_requsr[0]['REQSTFR'] == 61579) { /* ?>
									            		<a href="javascript:void(0)" onclick="add_more_suppliers('<?=$inc?>', '<?=$reqid?>', '<?=$year?>', '<?=$rsrid?>', '<?=$creid?>', '<?=$typeid?>', '<?=$sql_ap[0]['APRNUMB']?>', '<?=$prdlist['PRDCODE']?>', '<?=$prdlist['SUBCODE']?>');" class="red_highlight">Add More Suppliers (+)</a>
									            	<? */ ?>
									            		<a href="javascript:void(0)" onclick="add_more_suppliers('<?=$inc?>', '<?=$reqid?>', '<?=$year?>', '<?=$rsrid?>', '<?=$creid?>', '<?=$typeid?>', '<?=$prdlist['PBDYEAR']?>', '<?=$prdlist['PBDCODE']?>', '<?=$prdlist['PBDLSNO']?>', '<?=$sql_reqid[0]['APRNUMB']?>', '<?=$prdlist['PRDCODE']?>', '<?=$prdlist['SUBCODE']?>', '<?=$sql_reqid[0]['BRNCODE']?>', '<?=$sql_prdquotlist[0]['SUPCODE']?>');" class="red_highlight">Add More Suppliers (+)</a>
									            	<? } ?>
									            </td>
											</tr>
										<? } } ?>
									</table>

								<? } ?>
								</tr>
							</table>

	<!-- General Master -->
	<?
	$sql_ap = select_query_json("select distinct aprnumb from approval_request where ARQCODE = '".$_REQUEST['reqid']."' and  ARQYEAR = '".$_REQUEST['year']."'   and ATCCODE = '".$_REQUEST['creid']."' and  ATYCODE = '".$_REQUEST['typeid']."' and  deleted='N'", 'Centra', 'TEST');

	$sql_gen_det = select_query_json("select * from approval_general_detail where aprnumb='".$sql_ap[0]['APRNUMB']."' order by rowsrno,colsrno", 'Centra', 'TEST');
	$sql_gen_master = select_query_json("select * from approval_general_master where tempid=".$sql_gen_det[0]['TEMPID']." order by colsrno", 'Centra', 'TEST');
	$app_val = "";
	$app_val = $sql_gen_master[0]['CALRES'];
	if(count($sql_gen_det)>0){?>

	<div style="margin:10px;">
	<table style="margin:0 auto;width: 100%;" class="table table-bordered table-striped table-hover">
		<thead style="border-color: black;">
	 		<tr style="background-color: #f0f0f0;color: #1a0303;text-transform: uppercase;">
	 		<?	$totalw =0;
				$noofcol=0;

	 		foreach ($sql_gen_master as $col) {
				if($col['COLTYPE'] =="Y"){
					$totalw =1;
					if($noofcol == 0)
					{
						$colspan = $col['COLSRNO']-1;
						$noofcol =1;
					}
				}?>
	 		<th class="colauto">
	 			<?if($col['COLDET'] == "SR.NO"){ echo "#";}else{echo $col['COLDET'];}?>
 			</th>
	 		<?}?>
	 	</tr>
	 	</thead>
	 	<tbody>
		 		<?$row = "";
		 		$tot = array();
				$row1 = 0;
	 			foreach($sql_gen_det as $col)
	 			{
	 				$ncol = $col['COLSRNO']-1;
	 				if($row != $col['ROWSRNO']) {
						$row1++; ?><tr style="color: black;">
					<? } ?>
	 				<td <?if($sql_gen_master[$ncol]['COLTYPE'] == "Y"){?> class="colnum"<?}else{?> class="coltext" <?}?>>
	 					<?	if($sql_gen_master[$ncol]['CALRES'] != $col['COLSRNO'])
	 						{
								if($col['COLSRNO'] == 1){ echo $row1; }
								else{ // echo "**";
									if($col['APMCODE'] == 856 and $col['COLSRNO'] == 2) {
										$expl = explode(",", $col['COLDET']);
										for($ij = 0; $ij < count($expl); $ij++) {
											$sql_tablemast = select_query_json("select * from master_table_detail where deleted = 'N' and MASTERID = '".$expl[$ij]."' order by TABNAME asc", 'Centra', 'TEST');
												if(count($sql_tablemast) > 0) {
													if($_SESSION['tcs_empsrno'] == 21344 or $_SESSION['tcs_empsrno'] == 482) { ?>
														<a href="javascript:void(0)" title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" data-title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" onclick="find_tablemaster('<?=$sql_tablemast[0]['TABNAME']?>')"><?=$sql_tablemast[0]['TABNAME']?> (<?=$sql_tablemast[0]['MASTERID']?>) </a>;&nbsp;&nbsp;
											<? } else { echo $sql_tablemast[0]['TABNAME']." (".$sql_tablemast[0]['MASTERID']."), "; }
											} else { echo $col['COLDET']; }
										}
									} else { echo $col['COLDET']; }
								}
							}else{
								echo round($col['COLDET']);
							}
	 					?>
	 				</td>
				<?	$row = $col['ROWSRNO'];
					if($sql_gen_master[$ncol]['COLTYPE'] == "Y")
					{
						$tot[$col['COLSRNO']] += $col['COLDET'];
					}
				}
			?><tr style="font-weight: bolder;font-size: larger;color: black;"><?
			foreach ($sql_gen_master as $col)
			{
				if($totalw ==1)
				{
					if($col['COLSRNO'] == 1)
					{?>
					<td class="colauto" colspan="<?=$colspan?>">Total</td>
					<?}elseif($col['COLSRNO'] > $colspan){?>
					<td <?if($col['COLTYPE'] == "Y"){?> class="colnum"<?}else{?> class="coltext" <?}?>><?=$tot[$col['COLSRNO']]?></td>
					<?}
					?>
				<?}else{?>
					<td></td>
				<?}
			} ?>
		 	</tbody>
		</table>
		</div>
	<?}?>
	<!-- General Master -->

	<!-- STAFF night Duty START -->
	<?	$sql_night = select_query_json("select * from approval_night_duty  where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%' order by entsrno", 'Centra', 'TEST');
		if(count($sql_night)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Department</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Nature Of Work</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Working Hours</td>
		        </tr>
				<? $g=0;
				foreach ($sql_night as $gift) { $g++; ?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['EMPCODE']."-".$gift['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;">
						<? if($gift['EMPCODE'] != 0) { ?>
						<img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>">
						<? } ?>
					</td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['DESNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['ESENAME']?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['WRKDESC']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['WRKHURS']?></td>
				</tr>
				<? } ?>
			</table>
		<? } ?>
	<!-- STAFF night Duty START -->

	<!-- ESI PF Multiple Branch -->
	<?	$sql_project_branch = select_query_json("SELECT * from approval_branch_detail bd, approval_branch_list bl
														where bd.BRNCODE = bl.BRNCODE and bd.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'","Centra","TEST");
		if(count($sql_project_branch)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:2px;">
				<thead>
					<tr>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">#</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">BRANCH CODE</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">BRANCH NAME</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">NOUMBER OF EMPLOYEE</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">VALUE</th>
					</tr>
				</thead>
				<tbody style="text-align:center">
			<? for($project_i = 0; $project_i < count($sql_project_branch); $project_i++) {?>
				<tr>
					<td class="highlight_column1 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?echo $project_i + 1;?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['BRNCODE']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['BRNNAME']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['NOFEMPL']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['APRAMNT']?></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		<? } ?>
	<!-- ESI PF Multiple Branch -->

	<!-- STAFF MARRIAGE GIFT START -->
	<?
		$sql_gift = select_query_json("select * from approval_staff_marriage where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
		if(count($sql_gift)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Own Gift/GRAM</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Trust Amount</td>
		        </tr>
				<? $g=0;
				foreach ($sql_gift as $gift) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['EMPCODE']."-".$gift['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($gift['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$gift['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$gift['OWNGIFT']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$gift['TRUSTAMT']?></td>
				</tr>
				<?}?>
			</table>
			<?}
			// STAFF BRANCH CHANGE START
			$sql_branch = select_query_json("select * from approval_staff_branch_change where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_branch)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Designation</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Designation</td>
		        </tr>
				<? $g=0;
				foreach ($sql_branch as $branch) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$branch['EMPCODE']."-".$branch['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$branch['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($branch['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$branch['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWDES']?></td>
				</tr>
				<?}?>
			</table>
			<?}

			// STAFF DESIGNATION CHANGE
			$sql_desg = select_query_json("select * from approval_staff_designation where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_desg)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;" rowspan="2">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;" rowspan="2">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Current Designation</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">New Designation</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">New Department</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">Reporting To</td>
		        </tr>

				<? $g=0;
				foreach ($sql_desg as $desg) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$desg['EMPCODE']."-".$desg['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$desg['EMPCODE']?>"  alt = "<? echo $desg['EMPNAME']; ?>" title="<? echo $desg['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($desg['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$desg['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$desg['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$desg['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;color: blue;font-weight: bold;"><?=$desg['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['NEWDES']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['NEWDEPT']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['REPORTTO']?></td>
				</tr>
				<?}?>
			</table>
			<? }

			// STAFF DEPT CHANGE
			$sql_dept = select_query_json("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_dept)>0 && $sql_ap[0]['APRNUMB'] != ""){
				echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Current Designation</td>

		            <? if($sql_reqid[0]['APRNUMB'] == 'S-TEAM / ATTENDANCE 1000068 / 26-04-2018 / 0068 / 02:23 PM') { ?>
			            <td class="colheight" style="padding: 0px;width: 10%;">New Department</td>
			            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">New Designation</td>
			        <? } else { ?>
			            <td class="colheight" style="padding: 0px;width: 12%;">New Department</td>
			        <? } ?>
			        <td class="colheight" style="padding: 0px;width: 10%;">Reporting To</td>
		        </tr>
				<? $g=0;
				foreach ($sql_dept as $dept) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$dept['EMPCODE']."-".$dept['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$dept['EMPCODE']?>"  alt = "<? echo $dept['EMPNAME']; ?>" title="<? echo $dept['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($dept['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$dept['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$dept['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$dept['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;"><?=$dept['CURDES']?></td>

		            <? if($sql_reqid[0]['APRNUMB'] == 'S-TEAM / ATTENDANCE 1000068 / 26-04-2018 / 0068 / 02:23 PM') {
		            	$sql_des = select_testquery("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'"); ?>
						<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$dept['NEWDEP']?></td>
						<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$sql_des[0]['NEWDES']?></td>
		            <? } else { ?>
						<td class="colheight" style="padding: 0px;width: 12%;color: green;font-weight: bold;"><?=$dept['NEWDEP']?></td>
					<? } ?>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$dept['REPORTTO']?></td>
				</tr>
				<?}?>
			</table>
			<? }
			// STAFF DEPT CHANGE

			$sql_salary = select_query_json("select app.* from approval_staff_salary_change app, employee_office emp
													where emp.empsrno= app.empsrno and app.apryear='".$_REQUEST['year']."' and app.aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_salary)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Current Basic</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Increment amt</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">New Basic</td>
		        </tr>
				<? $g=0;
				foreach ($sql_salary as $salary) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$salary['EMPCODE']."-".$salary['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$salary['EMPCODE']?>"  alt = "<? echo $salary['EMPNAME']; ?>" title="<? echo $salary['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($salary['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$salary['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;"><?=$salary['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$salary['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;"><?=$salary['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: blue;font-weight: bold;"><?=$salary['CURBAS']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: green;font-weight: bold;"><?=$salary['INCAMT']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: green;font-weight: bold;"><?=$salary['NEWBAS']?></td>
				</tr>
				<?}?>
			</table>
			<? } ?>
				</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr style='height:20px;'><td></td></tr>
			<?php
			$sql_descode=select_query_json("Select p.APLCYNM, to_char(pf.EFCTDAT, 'dd-MM-yyyy') EFCTDAT, to_char(pf.VALDUPT, 'dd-MM-yyyy') VALDUPT, to_char(pf.APPRDAT, 'dd-MM-yyyy') APPRDAT, pf.APLCYCD, pf.PLCYTYP, pf.CRTECNO, pf.CRTUSNM, pf.CRDECNO, pf.CRDUSNM, pf.ASTECNO, pf.ASTUSNM, pf.USERLST, pf.APRVDBY, pf.APRVDUS, pf.DESKPRO, pf.PLCDATA, pf.PLCATTC From approval_policy_form pf, approval_policy_master p
			where pf.APLCYCD = p.APLCYCD and pf.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'","Centra","TEST");
				foreach($sql_descode as $sectionrow) {
			?>
			<tr>
				<td colspan="3">
						<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC;padding:2px;">
							<thead>
								<tr>
									<th colspan="2" style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 25px; color:#000;text-align:left;"> SUBJECT  : <span class="blue_highlight"><?echo $sectionrow['APLCYNM'];?></span></th>
								</tr>
							</thead>
							<tbody style="text-align:center">
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> EFFECTIVE DATE : <span class="blue_highlight"><b><?echo $sectionrow['EFCTDAT'];?></b></span> </td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> POLICY TYPE : <span class="blue_highlight"><b><?echo $sectionrow['PLCYTYP'];?></b></span></td>
							</tr>
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> VALID UPTO : <span class="blue_highlight"><b><?echo $sectionrow['VALDUPT'];?></b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> CREATOR EC NO \ NAME : <span class="blue_highlight"><b><?echo $sectionrow['CRTECNO'];?> - <?echo $sectionrow['CRTUSNM'];?></b></span></td>
							</tr>
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> APPROVED DATE : <span class="blue_highlight"><b><?echo $sectionrow['APPRDAT'];?></b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> CO-ORDINATOR EC NO \ NAME : <span class="blue_highlight"><b>12.20.2018</b></span></td>
							</tr>
							<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  USER LIST : <span class="blue_highlight"><b>12.20.2018</b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> ASSIST BY EC NO \ NAME : <span class="blue_highlight"><b><?echo $sectionrow['CRDECNO'];?> - <?echo $sectionrow['CRDUSNM'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  APPROVED BY : <span class="blue_highlight"><b><?echo $sectionrow['APRVDBY'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  DESK PROCEDURE : <span class="blue_highlight"><b><?echo $sectionrow['DESKPRO'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  MAINTENANCE OF POLICY : <span class="blue_highlight"><b><?echo $sectionrow['PLCDATA'];?></b></span></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr style='height:10px;'><td></td></tr>
			<tr colspan="3">
			</td>
							<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC;padding:2px;">
								<tbody style="text-align:center">
								<tr>
									<td colspan="2">
										<?
														$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
														$lpdyear = $current_year[0]['PORYEAR'];

														$filepathname = $sectionrow['PLCATTC'];
														$filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_policy/".$lpdyear."/".$filepathname;
														$handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
														$contents = fread($handle, filesize($filename));
														fclose($handle);
														// echo substr(strip_tags(str_replace("&nbsp;", " ", $contents)), 0, 500);
														echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
										?>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%;padding:3px 5px"><br>  POLICY <span class="blue_highlight"><b>:</b></span>
										<br><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
									</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 01</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 02</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 03</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 04</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 05</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 06</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 07</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 08</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 09</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 10</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 11</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 12</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 13</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 14</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 15</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 16</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 17</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 18</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 19</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 20</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 21</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 22</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 23</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 24</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 25</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 26</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 27</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 28</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 29</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 30</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
							</tbody>
						</table>
				</td>
			</tr>
			<? } ?>

			<tr style='height:20px;'><td></td></tr>


				<tr style='min-height:25px; line-height:25px;'>
					<td style='width:100%; text-align:left;' colspan=2>
					<table width='100%' style="max-width: 773px; " border="1">

					<tr style='min-height:25px; line-height:25px;'>
						<td style='width:16%; text-align:left;'>
							<label>No. of Attachment</label> <!-- No. of Attachment -->
						</td>
						<td style='width:30%; text-align:left;'>
							<label>: <? if($count_attachment == 0) { echo "--NIL--"; } else { ?>
								<a href='javascript:void(0)' onclick="popup_attachment('<? echo $sql_reqid[0]['ARQCODE']; ?>', '<? echo $sql_reqid[0]['ARQYEAR']; ?>', '1', '<? echo $sql_reqid[0]['ATCCODE']; ?>', '<? echo $sql_reqid[0]['ATYCODE']; ?>', '<? echo $sql_reqid[0]['APRNUMB']; ?>')" title='View Attachements' alt='View Attachements' style='font-weight:bold;' class="blue_highlight"><img src="images/attach.png" style="border: 0px;"> <?=$count_attachment;?></a>
							 <? } ?></label> <!-- No. of Attachment -->
						</td>

						<? if($sql_reqid[0]['IMDUEDT'] != '') { ?>
							<td style='width:35%; text-align:right;'>
								<label>Implementation Due Date</label> <!-- Implementation Due Date -->
							</td>
							<td style='width:17%; text-align:left; font-weight: bold;'>
								<label>: <?=strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT'])))?> </label> <!-- Implementation Due Date -->
							</td>
						<? } else { ?>
							<td style='width:35%; text-align:right;'>
							</td>
							<td style='width:17%; text-align:left; font-weight: bold;'>
							</td>
						<? } ?>
					</tr>





				<? if($sql_reqid[0]['RMQUOTS'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Quotations & Estimations</label> <!-- Quotations & Estimations -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['RMQUOTS']; ?></label> <!-- Quotations & Estimations -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RMBDAPR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Budget / Common / Reference Approval</label> <!-- Budget / Common / Reference Approval -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['RMBDAPR']; ?></label> <!-- Budget / Common / Reference Approval -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RMCLRPT'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Work Place Before / After Photo / Drawing Layout</label> <!-- Work Place Before / After Photo / Drawing Layout -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['RMCLRPT']; ?></label> <!-- Work Place Before / After Photo / Drawing Layout -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RMARTWK'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Art Work Design with MD Approval</label> <!-- Art Work Design with MD Approval -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['RMARTWK']; ?></label> <!-- Art Work Design with MD Approval -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RMCONAR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Consultant Approval</label> <!-- Consultant Approval -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['RMCONAR']; ?></label> <!-- Consultant Approval -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['WARQUAR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Warranty / Guarantee</label> <!-- Warranty / Guarantee -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['WARQUAR']; ?></label> <!-- Warranty / Guarantee -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['CRCLSTK'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Current / Closing Stock</label> <!-- Current / Closing Stock -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['CRCLSTK']; ?></label> <!-- Current / Closing Stock -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['PAYPERC'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Advance or Final Payment / Work Completion Percentage</label> <!-- Advance or Final Payment / Work Completion Percentage -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['PAYPERC']; ?></label> <!-- Advance or Final Payment / Work Completion Percentage -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['FNTARDT'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Work Finish Target Date</label> <!-- Work Finish Target Date -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['FNTARDT']))); ?></label> <!-- Work Finish Target Date -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['AGEXPDT'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Agreement Expiry Date</label> <!-- Agreement Expiry Date -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['AGEXPDT']))); ?></label> <!-- Agreement Expiry Date -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['AGADVAM'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Agreement Advance Amount</label> <!-- Agreement Advance Amount -->
						</td>
						<td style='width:80%; text-align:left;' colspan="3">
							<label>: <? echo $sql_reqid[0]['AGADVAM']; ?></label> <!-- Agreement Advance Amount -->
						</td>
					</tr>
				<? } ?>

				<tr style='min-height:25px; max-width: 773px; width: 100%; line-height:25px;'>
					<td style='width:20%; text-align:left;'>
						<label>Approved Value</label> <!-- Approved Value -->
					</td>
					<td colspan=5 style='width:80%; text-align:left;'>
						: <label style=' font-size:32px; font-weight:bold;' class="blue_highlight"><? if($sql_approve_leads[0]['APPFVAL'] == 0)
							{
							if($app_val != "")
							{
								echo $tot[$app_val];
							}else{
							echo "--NIL--";
							}
						} else { $apprvl = moneyFormatIndia($sql_approve_leads[0]['APPFVAL']); ?><img src='images/rupee.png' width=16 height=20 border=0> <?=$apprvl?></label> <label class='cls_rupees'>(<? echo ucwords(convert_rup($sql_approve_leads[0]['APPFVAL'])).")</label>"; } ?>
						 <!-- Approved Value -->
					</td>
				</tr>


				<!-- Expense Percentage -->
				<? // if(($_SESSION['tcs_empsrno'] == 20118 or $_SESSION['tcs_empsrno'] == 43400 or $_SESSION['tcs_empsrno'] == 21344 or $_SESSION['tcs_empsrno'] == 43878) and $sql_reqid[0]['APPFVAL'] > 0) {
				if($sql_reqid[0]['APPFVAL'] > 0) { // For All User can View ?>
				<tr style='min-height:25px; max-width: 773px; line-height:25px;'>
					<td colspan=6 style='width:100%; text-align:left;'>
					<table border=1 style='width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:2px;'>
						<tr>
							<th rowspan="2" style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 40%;">Department Title</th>
							<th colspan="3" style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 30%;">Branch</th>
							<th colspan="3" style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 30%;">Department</th>
						</tr>

						<tr>
							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Yearly Budget %</th>
							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Yearly Exp %</th>
							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Monthly Exp %</th>

							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Yearly Budget %</th>
							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Yearly Exp %</th>
							<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000; width: 10%;">Monthly Exp %</th>
						</tr>
						<tr>
							<td class="highlight_column" style="text-align:center; border: 1px solid #e0e0e0;"><? if($sql_reqid[0]['APPFVAL'] > 0) { ?>
							<? /* <a target='_blank' href='departmentwise_expense.php?slt_branch=<?=$sql_reqid[0]['BRNCODE']?>&slt_department_asset=<?=$sql_tarbalance[0]['EXPSRNO']?>&slt_appmode=<?=$_REQUEST['slt_appmode']?>' title='View' alt='View' style='font-weight:bold;' class="blue_highlight txt_underline">
							<b><? echo $sql_tarbalance[0]['DEPNAME']; ?></b></a> */ ?>
							<b><? echo $sql_tarbalance[0]['DEPNAME']; ?></b>
							<? } else { echo "--"; } ?></td>
							<? if($sql_reqid[0]['APPFVAL'] > 0) {
								$app_var = "";

								if($_REQUEST['slt_appmode'] == "A"){
									$app_var = "and app.appstat='A' and bpl.appmode='Y'";
								}

								$lastyr = date("Y") - 1; $currentyr = date("Y"); $nextyr = date("Y") + 1; $currentmonth = date("m"); // $ij = 0;
								$exlastyr = date("Y") - 2; $excurrentyr = date("Y") - 1; $exnextyr = date("Y");

								if($currentyr == "2017") {
									$exp_var="((APPYEAR='".$currentyr."' and APPMNTH between 4 and ".$currentmonth."))";
									$xp_var = "((to_char(tar.ptfdate,'YYYY') = '".$currentyr."' and to_char(ptfdate,'MM') between 4 and ".$currentmonth.")) ";
									$xp_var1 = "((to_char(summ.BPLDATE,'YYYY') = '".$currentyr."' and to_char(summ.BPLDATE,'MM') between 4 and ".$currentmonth.")) ";

									$advt_bvar = "and  to_char(non.ADDDATE,'YYYY') = '2017' and to_char(non.ADDDATE,'MM')  between 4 and ".$currentmonth."";
									$cash_var = "and  to_char(fnddate,'YYYY') = '2017' and to_char(fnddate,'MM')  between 4 and ".$currentmonth."";
								} else {
									$exp_var="((APPYEAR='2017' and APPMNTH between 4 and 12) or (APPYEAR='".$currentyr."' and APPMNTH between 1 and ".$currentmonth."))";
									$xp_var = "((to_char(tar.ptfdate,'YYYY') = '2017' and to_char(ptfdate,'MM') between 4 and 12) or (to_char(tar.ptfdate,'YYYY') = '".$currentyr."' and to_char(ptfdate,'MM') between 1 and ".$currentmonth.")) ";
									$xp_var1= "((to_char(summ.BPLDATE,'YYYY') = '2017' and to_char(summ.BPLDATE,'MM') between 4 and 12) or (to_char(summ.BPLDATE,'YYYY') = '".$currentyr."' and to_char(summ.BPLDATE,'MM') between 1 and ".$currentmonth.")) ";
									$advt_bvar = "and  ((to_char(non.ADDDATE,'YYYY') = '2017' and to_char(non.ADDDATE,'MM')  between 4 and ".$currentmonth.") or ((to_char(non.ADDDATE,'YYYY') = '".$currentyr."' and to_char(non.ADDDATE,'MM')  between 1 and ".$currentmonth.")))";
									$cash_var = "and  ((to_char(fnddate,'YYYY') = '2017' and to_char(fnddate,'MM')  between 4 and ".$currentmonth.")or (to_char(fnddate,'YYYY') = '".$currentyr."' and to_char(fnddate,'MM')  between 1 and ".$currentmonth."))";
								}

								if($sql_reqid[0]['BRNCODE'] == "100") {
									$exp_notin = "and dep.expsrno not in (26,29,31,32,8,21)";
									$exp_in = "and dep.expsrno  in (26,28,29,31,32,8,21)";
									$yr_expnotin = "and dep.expsrno not in (26,29,31,32,8,21)";
								} else {
									$exp_notin = "and dep.expsrno not in (26,29,31,32)";
									$exp_in = "and dep.expsrno  in (26,28,29,31,32,8)";
									$yr_expnotin = "and dep.expsrno not in (26,29,31,32,8)";
								}

								$sal_var=" and ((sal.salmont between 4 and 12 and sal.salyear='".$lastyr."') or ( sal.salmont between 1 and 3 and sal.salyear=".$currentyr.")) ";
								$sql_val = select_query_json("select round(sum(salesval)/100000,2)as salval from non_sales_target sal
																where sal.brncode=".$sql_reqid[0]['BRNCODE']." ".$sal_var." and sal.salesval>0", 'Centra', 'TCS');
								$salval = $sql_val[0]['SALVAL'];

								$sal_var1=" and ((sal.salmont between 4 and 12 and sal.salyear='".$exlastyr."') or ( sal.salmont between 1 and 3 and sal.salyear=".$excurrentyr.")) ";
								$sql_val1 = select_query_json("select round(sum(salesval)/100000,2) as salval from non_sales_target sal
																where sal.brncode=".$sql_reqid[0]['BRNCODE']." ".$sal_var1." and sal.salesval>0", 'Centra', 'TCS');
								$salval1 = $sql_val1[0]['SALVAL'];

								$sal_var2=" and sal.salmont = ".$currentmonth." and sal.salyear=".$currentyr."";
								$sql_val2 = select_query_json("select round(sum(salesval)/100000,2) as salval from non_sales_target sal
																where sal.brncode=".$sql_reqid[0]['BRNCODE']." ".$sal_var2." and sal.salesval>0", 'Centra', 'TCS');
								$salval2 = $sql_val2[0]['SALVAL'];

								$sql_yr = select_query_json("select nvl(round(sum(((det.BPLPRAT-summ.BPLPLESS)-((det.BPLPRAT-summ.BPLPLESS)*(summ.BPLSDISC+det.BPLDISC)/100)) *
																	con.BPLPIEC)+sum(det.BPLTAXV),2)/100000,0) as val
																from BUdget_planner_detail det, Budget_planner_summary summ, budget_planner_content con, non_purchase_target tar,
																	department_asset dep
																where det.bplyear=summ.bplyear and det.bplnumb=summ.bplnumb and det.bplyear = con.bplyear And det.bplnumb = con.bplnumb
																	And det.bplsrno = con.bplsrno And summ.trncode=tar.ptnumb and  tar.depcode = dep.depcode and summ.deleted='N' and
																	con.brncode=tar.brncode and trunc(summ.adddate) between trunc(tar.ptfdate) and trunc(tar.pttdate) and ".$xp_var1."
																	".$yr_expnotin." and dep.depcode not in (124,215) and con.brncode=".$sql_reqid[0]['BRNCODE']." ", 'Centra', 'TCS');

								$sql_mnth = select_query_json("select nvl(round(sum(((det.BPLPRAT-summ.BPLPLESS)-((det.BPLPRAT-summ.BPLPLESS)*(summ.BPLSDISC+det.BPLDISC)/100)) *
																	con.BPLPIEC)+sum(det.BPLTAXV),2)/100000,0) as val
																from BUdget_planner_detail det, Budget_planner_summary summ, budget_planner_content con, non_purchase_target tar,
																	department_asset dep
																where det.bplyear=summ.bplyear and det.bplnumb=summ.bplnumb and det.bplyear = con.bplyear And det.bplnumb = con.bplnumb
																	And det.bplsrno = con.bplsrno And summ.trncode=tar.ptnumb and  tar.depcode = dep.depcode and summ.deleted='N' and
																	con.brncode=tar.brncode and trunc(summ.adddate) between trunc(tar.ptfdate) and trunc(tar.pttdate) and
																	dep.depcode not in (124,215) and ((to_char(summ.BPLDATE,'YYYY') = '".$currentyr."' and
																	to_char(summ.BPLDATE,'MM')= ".$currentmonth.")) ".$exp_notin." and con.brncode=".$sql_reqid[0]['BRNCODE']."", 'Centra', 'TCS');

								$sql_mnth1 = select_query_json("select nvl(round(sum(((det.BPLPRAT-summ.BPLPLESS)-((det.BPLPRAT-summ.BPLPLESS)*(summ.BPLSDISC+det.BPLDISC)/100)) *
																	con.BPLPIEC)+sum(det.BPLTAXV),2)/100000,0) as val
																from BUdget_planner_detail det, Budget_planner_summary summ, budget_planner_content con, non_purchase_target tar,
																	department_asset dep
																where det.bplyear=summ.bplyear and det.bplnumb=summ.bplnumb and det.bplyear = con.bplyear And det.bplnumb = con.bplnumb
																	And det.bplsrno = con.bplsrno And summ.trncode=tar.ptnumb and  tar.depcode = dep.depcode and summ.deleted='N' and
																	con.brncode=tar.brncode and trunc(summ.adddate) between trunc(tar.ptfdate) and trunc(tar.pttdate) and
																	dep.depcode not in (124,215) and ((to_char(summ.BPLDATE,'YYYY') = '".$currentyr."' and
																	to_char(summ.BPLDATE,'MM')= ".$currentmonth.")) ".$exp_notin." and con.brncode=".$sql_reqid[0]['BRNCODE']."
																	and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');

								$sql_expense = select_query_json("select expsrno,expname,sum(apprval) apprval,sum(round(planval,2))planval from (select dep.expsrno,dep.expname,
																		round(sum(apprval)/100000,2)as apprval,0 as planval
																	from approval_request app, approval_budget_planner bpl, department_asset dep
																	where ".$exp_var." and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']." and app.aprnumb=bpl.aprnumb and
																		app.tarnumb=bpl.tarnumb ".$app_var." and bpl.expsrno=dep.expsrno and app.depcode=dep.depcode and app.arqsrno=1
																		and bpl.deleted='N' and app.brncode=".$sql_reqid[0]['BRNCODE']."  and dep.depcode not in (124,215)
																	group by dep.expsrno,dep.expname
																union
																	select distinct dep.expsrno,dep.expname,0 as apprval,  nvl(round(sum(((det.BPLPRAT-summ.BPLPLESS) - ((
																		det.BPLPRAT-summ.BPLPLESS)*(summ.BPLSDISC+det.BPLDISC)/100))*con.BPLPIEC)+sum(det.BPLTAXV),2)/100000,0) as planval
																	from BUdget_planner_detail det, Budget_planner_summary summ, budget_planner_content con, non_purchase_target tar,
																		department_asset dep
																	where det.bplyear=summ.bplyear and det.bplnumb=summ.bplnumb and det.bplyear = con.bplyear And det.bplnumb = con.bplnumb
																		And det.bplsrno = con.bplsrno And summ.trncode=tar.ptnumb and tar.depcode = dep.depcode and summ.deleted='N' and
																		con.brncode=tar.brncode and trunc(summ.adddate) between trunc(tar.ptfdate) and trunc(tar.pttdate) and ".$xp_var1."
																		and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']." and dep.depcode not in (124,215) and
																		con.brncode=".$sql_reqid[0]['BRNCODE']." group by dep.expsrno,dep.expname) having sum(apprval)>0 or sum(planval)>0
																	group by expsrno, expname
																	order by expsrno", 'Centra', 'TCS');

								$sql_cash = select_query_json("select round(sum(pl.amount)/100000,2) ENTAMNT
																from fund_status_daywise pl, fund_status_master mas, fund_status_sub_master sub, fund_status_grp_master grp
																where pl.fndcode=mas.fndcode and mas.subcode<>0 and mas.FNDSUBC=sub.subcode and grp.grpcode in(6) and
																	grp.grpcode=sub.grpcode and Mas.ordsrno=21 and pl.fndcode not in(544,53,57,59,30,6,218,202) and
																	mas.ordsrno not between 74 and 250  and pl.deleted='N' and pl.ADLSDES<>'0' and pl.amount<>0
																	and brncode=".$sql_reqid[0]['BRNCODE']." ".$cash_var."", 'Centra', 'TCS');
								$cashval = $sql_cash[0]['ENTAMNT'];

								$sql_cash1 = select_query_json("select round(sum(pl.amount)/100000,2) ENTAMNT
																from fund_status_daywise pl, fund_status_master mas, fund_status_sub_master sub, fund_status_grp_master grp
																where pl.fndcode=mas.fndcode and mas.subcode<>0 and mas.FNDSUBC=sub.subcode and grp.grpcode in(6) and
																	grp.grpcode=sub.grpcode and Mas.ordsrno=21 and pl.fndcode not in(544,53,57,59,30,6,218,202) and
																	mas.ordsrno not between 74 and 250  and pl.deleted='N' and pl.ADLSDES<>'0' and pl.amount<>0
																	and brncode=".$sql_reqid[0]['BRNCODE']." ".$cash_var." and mas.ordsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');
								$cashval1 = $sql_cash1[0]['ENTAMNT'];

								$sql_cash_mon = select_query_json("select round(sum(pl.amount)/100000,2) ENTAMNT
																	from fund_status_daywise pl, fund_status_master mas, fund_status_sub_master sub, fund_status_grp_master grp
																	where pl.fndcode=mas.fndcode and mas.subcode<>0 and mas.FNDSUBC=sub.subcode and grp.grpcode in(6) and
																		grp.grpcode=sub.grpcode and Mas.ordsrno=21 and pl.fndcode not in(544,53,57,59,30,6,218,202)
																		and mas.ordsrno not between 74 and 250  and pl.deleted='N' and pl.ADLSDES<>'0' and pl.amount<>0
																		and brncode=".$sql_reqid[0]['BRNCODE']."  and  to_char(fnddate,'YYYY') = '".$currentyr."'
																		and to_char(fnddate,'MM') = ".$currentmonth." ", 'Centra', 'TCS');
								$cashmon = $sql_cash_mon[0]['ENTAMNT'];

								$sql_cash_mon1 = select_query_json("select round(sum(pl.amount)/100000,2) ENTAMNT
																	from fund_status_daywise pl, fund_status_master mas, fund_status_sub_master sub, fund_status_grp_master grp
																	where pl.fndcode=mas.fndcode and mas.subcode<>0 and mas.FNDSUBC=sub.subcode and grp.grpcode in(6) and
																		grp.grpcode=sub.grpcode and Mas.ordsrno=21 and pl.fndcode not in(544,53,57,59,30,6,218,202)
																		and mas.ordsrno not between 74 and 250  and pl.deleted='N' and pl.ADLSDES<>'0' and pl.amount<>0
																		and brncode=".$sql_reqid[0]['BRNCODE']."  and  to_char(fnddate,'YYYY') = '".$currentyr."'
																		and to_char(fnddate,'MM') = ".$currentmonth." and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');
								$cashmon1 = $sql_cash_mon1[0]['ENTAMNT'];

								$sql_advt_branch_mon = select_query_json("select round(sum(round(NETAMNT*INVPERC/100,2))/100000,2) Ntoval
																			from non_invoice_summary non,department_asset dep
																			where non.depcode=dep.depcode and  to_char(non.ADDDATE,'YYYY') = '".$currentyr."' and
																				to_char(non.ADDDATE,'MM')= ".$currentmonth." and non.deleted='N' and non.INVMODE='I' and
																				dep.depcode in (118,125) and INV_BRNCODE=".$sql_reqid[0]['BRNCODE']."", 'Centra', 'TCS');
								$adv_mon = $sql_advt_branch_mon[0]['NTOVAL'];
								$mnth = $sql_mnth[0]['VAL'];
								// echo "**".$mnth."**".$cashmon."**".$adv_mon."**";
								$mnth = $mnth+$cashmon+$adv_mon;

								$sql_advt_branch_mon1 = select_query_json("select round(sum(round(NETAMNT*INVPERC/100,2))/100000,2) Ntoval
																			from non_invoice_summary non,department_asset dep
																			where non.depcode=dep.depcode and  to_char(non.ADDDATE,'YYYY') = '".$currentyr."' and
																				to_char(non.ADDDATE,'MM')= ".$currentmonth." and non.deleted='N' and non.INVMODE='I' and
																				dep.depcode in (118,125) and INV_BRNCODE=".$sql_reqid[0]['BRNCODE']."
																				and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');
								$adv_mon1 = $sql_advt_branch_mon1[0]['NTOVAL'];
								$mnth1 = $sql_mnth1[0]['VAL'];
								$mnth1 = $mnth1+$cashmon1+$adv_mon1;

								$sql_advt_branch = select_query_json("select round(sum( round(NETAMNT*INVPERC/100,2))/100000,2)  Ntoval
																		from non_invoice_summary non, department_asset dep
																		where non.depcode=dep.depcode ".$advt_bvar." and non.deleted='N' and non.INVMODE='I' and dep.depcode=118 and
																			INV_BRNCODE=".$sql_reqid[0]['BRNCODE']."", 'Centra', 'TCS');
								$brn = $sql_advt_branch[0]['NTOVAL'];

								$sql_advt_branch1 = select_query_json("select round(sum( round(NETAMNT*INVPERC/100,2))/100000,2)  Ntoval
																		from non_invoice_summary non, department_asset dep
																		where non.depcode=dep.depcode ".$advt_bvar." and non.deleted='N' and non.INVMODE='I' and dep.depcode=118 and
																			INV_BRNCODE=".$sql_reqid[0]['BRNCODE']." and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');
								$brn1 = $sql_advt_branch1[0]['NTOVAL'];

								$sql_advt_gen = select_query_json("select round(sum( round(NETAMNT*INVPERC/100,2))/100000,2)  Ntoval
																	from non_invoice_summary non, department_asset dep
																	where non.depcode=dep.depcode ".$advt_bvar." and non.deleted='N' and non.INVMODE='I' and dep.depcode=125
																		and INV_BRNCODE=".$sql_reqid[0]['BRNCODE']."", 'Centra', 'TCS');
								$gen = $sql_advt_gen[0]['NTOVAL'];

								$sql_advt_gen1 = select_query_json("select round(sum( round(NETAMNT*INVPERC/100,2))/100000,2)  Ntoval
																	from non_invoice_summary non, department_asset dep
																	where non.depcode=dep.depcode ".$advt_bvar." and non.deleted='N' and non.INVMODE='I' and dep.depcode=125
																		and INV_BRNCODE=".$sql_reqid[0]['BRNCODE']." and dep.expsrno=".$sql_tarbalance[0]['EXPSRNO']."", 'Centra', 'TCS');
								$gen1 = $sql_advt_gen1[0]['NTOVAL'];
								$appval = round($sql_reqid[0]['APPFVAL']/100000,2);

								if($_REQUEST['slt_appmode'] == "A"){
									$yrbudval = round(($sql_yr[0]['VAL']+$brn+$gen+$cashval+$appval)/$salval1*100,2);
									$yrval = round(($sql_yr[0]['VAL']+$brn+$gen+$cashval+$appval)/$salval*100,2);
									$mnval = round(($mnth+$appval)/$salval2*100,2);
								}else{
									$yrbudval = round(($sql_yr[0]['VAL']+$brn+$gen+$cashval)/$salval1*100,2);
									$yrval = round(($sql_yr[0]['VAL']+$brn+$gen+$cashval)/$salval*100,2);
									$mnval = round($mnth/$salval2*100,2);
								}

								if($sql_tarbalance[0]['EXPSRNO'] == "1"){
									if($_REQUEST['slt_appmode'] == "A"){
										$depyrbud = round(($sql_expense[0]['APPRVAL']+$cashval1+$appval)/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL']+$cashval1+$appval)/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}else{
										$depyrbud = round(($sql_expense[0]['APPRVAL']+$cashval1)/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL']+$cashval1)/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}
								}elseif($sql_tarbalance[0]['EXPSRNO'] == "8" or $sql_tarbalance[0]['EXPSRNO'] == "9"){
									if($_REQUEST['slt_appmode'] == "A"){
										$depyrbud = round(($sql_expense[0]['APPRVAL']+$brn1+$gen1+$appval)/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL']+$brn1+$gen1+$appval)/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}else{
										$depyrbud = round(($sql_expense[0]['APPRVAL']+$brn1+$gen1)/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL']+$brn1+$gen1)/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}
								}else{
									if($_REQUEST['slt_appmode'] == "A"){
										$depyrbud = round(($sql_expense[0]['APPRVAL']+$appval)/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL']+$appval)/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}else{
										$depyrbud = round(($sql_expense[0]['APPRVAL'])/$salval1*100,2);
										$yrexp = round(($sql_expense[0]['PLANVAL'])/$salval*100,2);
										$yrmonexp = round($mnth1/$salval2*100,2);
									}
								}
							?>
							<td class="highlight_column1 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><? if($yrbudval>0) {echo $yrbudval." %";}else{echo"--";} ?></td>
							<td class="highlight_column1 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><? if($yrval>0) {echo $yrval." %";}else{echo"--";} ?></td>
							<td class="highlight_column1 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><? if($mnval>0) {echo $mnval." %";}else{echo"--";} ?></td>

							<td class="highlight_column2 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;">
							<? if($depyrbud>0) { // echo $salval1."!!"; ?>
								<span class="blink_me highlight_textred blue_highlight"><?=$depyrbud?> % </span></td>
							<? } else { echo"--"; } ?>
								<td class="highlight_column2 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;">
							<? if($yrexp>0) { // echo $salval."**"; ?>
								<span class="blink_me highlight_textred blue_highlight"><?=$yrexp?> % </span></td>
							<? } else { echo"--"; } ?>
								<td class="highlight_column2 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;">
							<? if($yrmonexp>0) { // echo $salval2."@@"; ?>
								<span class="blink_me highlight_textred blue_highlight"><?=$yrmonexp?> % </span>
							<? } else { echo"--"; } ?>
							</td>
							<? } else { ?>
								<td class="highlight_column1" style="text-align:center; border: 1px solid #e0e0e0;">--</td>
								<td class="highlight_column1" style="text-align:center; border: 1px solid #e0e0e0;">--</td>
								<td class="highlight_column2" style="text-align:center; border: 1px solid #e0e0e0;">--</td>
								<td class="highlight_column2" style="text-align:center; border: 1px solid #e0e0e0;">--</td>
							<? } ?>
						</tr>
					</table>
					</td>
				</tr>

				<? } ?>
				<!-- Expense Percentage -->


				<!-- Comments History -->
				<? $alluser = 0;
				if($_SESSION['tcs_empsrno'] == 21344) {
					$alluser = 1;
					$sk_flwadd = " and emp.EMPCODE not in (3) ";
				} elseif($_SESSION['tcs_empsrno'] == 20118) {
					$alluser = 1;
					$sk_flwadd = " and emp.EMPCODE not in (1) ";
				} elseif($_SESSION['tcs_empsrno'] == 43400) {
					$sk_flwadd = " and emp.EMPCODE not in (2) ";
				} else {
					$alluser = 1;
					$sk_flwadd = " and emp.EMPCODE not in (1, 2, 3) ";
				}

				if($sql_reqid[0]['REQSTBY'] == $_SESSION['tcs_empsrno']) {
					$sql_inteam = select_query_json("select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME, SUBSTR(sec.esename, 4, 100) esename,
																decode(emp.empsrno, ".$sql_reqid[0]['REQSTFR'].", 0, 1) ordersrno
															from employee_office emp, branch brn, designation des, empsection sec
															where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd."
																and emp.empsrno = ".$sql_reqid[0]['REQSTFR']." and emp.brncode in (1,10,14,23,30,31,32,888,102,104,107,112,113,114,116,118,120,121,100)
													union
														select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME, SUBSTR(sec.esename, 4,100) esename,
																decode(emp.empsrno, ".$sql_reqid[0]['REQSTFR'].", 0, 1) ordersrno
															from employee_office emp, branch brn, new_designation des, new_empsection sec
															where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd."
																and emp.empsrno = ".$sql_reqid[0]['REQSTFR']." and emp.brncode in(201,203,204,206,207,300)
															order by ordersrno, EMPNAME Asc", 'Centra', 'TEST');
				} else {
					if($alluser == 0) { // Not MD Users
						$sql_inteam = select_query_json("select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME, SUBSTR(sec.esename, 4, 100) esename,
																	decode(emp.empsrno, ".$adduser.", 4, 20118, 1, 43400, 2, 21344, 3, 5) ordersrno
																from employee_office emp, branch brn, designation des, empsection sec
																where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd." and (emp.EMPCODE > 1000 or
																	emp.EMPCODE in (1, 2, 3, 557, 530)) and (((des.dessrno <= 16 and des.DESSRNO > 0) or (des.dessrno = 99 and (des.descode = 179 or
																	des.descode = 187))) or (emp.empsrno = ".$adduser." or emp.empsrno = 21344 or emp.empsrno = 20118 or emp.empsrno = 43400 or
																	emp.empsrno = 61579 or emp.empsrno = 59006 or emp.empsrno = 1689 or emp.empsrno = 59003 or emp.empsrno = 49277 or emp.empsrno = 26255
																	or emp.empsrno = 55641 or emp.empsrno = 14180 or emp.empsrno = 2158 or emp.empsrno = 14180 or emp.empsrno = 2158 or
																	emp.empsrno = 14180 or emp.empsrno = 82237 or emp.empsrno = 53864 or emp.empsrno = 86464)) and
																	emp.brncode in (1,10,14,23,30,31,32,888,102,104,107,112,113,114,116,118,120,121,100)
															union
																select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME,
																	SUBSTR(sec.esename, 4,100) esename, decode(emp.empsrno, ".$adduser.", 4, 20118, 1, 43400, 2, 21344, 3, 5) ordersrno
																from employee_office emp, branch brn, new_designation des, new_empsection sec
																where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd." and (emp.EMPCODE > 1000 or
																	emp.EMPCODE in (1, 2, 3, 557, 530)) and (( des.dessrno <= 7 and des.DESSRNO > 0) or (emp.empsrno = ".$adduser." or
																	emp.empsrno = 21344 or emp.empsrno = 20118 or emp.empsrno = 43400 or emp.empsrno = 61579 OR emp.empsrno = 59006 or
																	emp.empsrno = 1689 or emp.empsrno = 59003 or emp.empsrno = 49277 or emp.empsrno = 26255 or emp.empsrno = 55641 or
																	emp.empsrno = 14180 or emp.empsrno = 2158 or emp.empsrno = 14180 or emp.empsrno = 2158 or emp.empsrno = 14180 or
																	emp.empsrno = 82237 or emp.empsrno = 53864 or emp.empsrno = 86464)) and emp.brncode in (201,203,204,206,207,300)
																order by ordersrno, EMPNAME Asc", 'Centra', 'TEST');
					} elseif($alluser == 1) { // MD Users
						$sql_inteam = select_query_json("select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME,
																	SUBSTR(sec.esename, 4, 100) esename, decode(emp.empsrno, ".$adduser.", 4, 20118, 1, 43400, 2, 21344, 3, 5) ordersrno
																from employee_office emp, branch brn, designation des, empsection sec
																where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd."
																	and (emp.EMPCODE > 1000 or emp.EMPCODE in (1, 2, 3, 557, 530)) and
																	emp.brncode in (1,10,14,23,30,31,32,888,102,104,107,112,113,114,116,118,120,121,100)
															union
																select distinct trim(emp.EMPNAME) EMPNAME, emp.EMPCODE, substr(brn.NICNAME,3,10) brnname, des.DESNAME,
																	SUBSTR(sec.esename, 4,100) esename, decode(emp.empsrno, ".$adduser.", 4, 20118, 1, 43400, 2, 21344, 3, 5) ordersrno
																from employee_office emp, branch brn, new_designation des, new_empsection sec
																where emp.brncode = brn.brncode and emp.descode = des.descode and emp.ESECODE = sec.ESECODE ".$sk_flwadd."
																	and (emp.EMPCODE > 1000 or emp.EMPCODE in (1, 2, 3, 557, 530)) and emp.brncode in (201,203,204,206,207,300)
																order by ordersrno, EMPNAME Asc", 'Centra', 'TEST');
					}
				}

			// tnv and tut remove 202,205,11,22 and dessrno upto 15 for TCS and upto for TJ Mr.Kumaran Instr
			// emp.empsrno = 43878 or
			// and ((des.dessrno not between 25 and 35) and (des.dessrno not between 37 and 56)) ?>
				<tr style='min-height:25px; max-width: 773px; line-height:25px; display: none;' id="comments_history">
					<td colspan=6 style='width:100%; text-align:left;'>
					<table border=1 style='width:100%; max-width: 773px; min-height:100px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px; margin-top: 5px;'>
						<tr style='min-height:25px; line-height:25px;'>
							<td style='width:100%; padding:0 10px; font-weight:bold; text-align:left;'>
								<label>COMMENTS HISTORY</label> <!-- Comments History -->
							</td>
						</tr>

						<? $sql_othcmd = select_query_json("select emp.empcode,emp.empname,emp.esecode
																	from approval_mdhierarchy  hir,employee_office emp
																	where hir.apphead=emp.empcode and aprnumb like '".$sql_reqid[0]['APRNUMB']."'and apphead not in (select empcode
																		from employee_office where empsrno in (select reqstby from approval_request  where aprnumb like '".$sql_reqid[0]['APRNUMB']."'
																		and arqsrno not in 1 and appfrwd='F')) order by amhsrno asc", 'Centra', 'TEST');
						// print_r($sql_othcmd);
						foreach ($sql_othcmd as $cmd) {
							// echo "<br>**".$cmd['EMPCODE']."**";
							if($cmd['EMPCODE'] == 1)
							{?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b>KS SIR Comments </b> : Not Yet Appproved</label> <!-- KS Sir not yet Comments -->
									</td>
								</tr>

							<?}elseif ($cmd['EMPCODE'] == 2) {?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b>PS Madam Comments </b> : Not Yet Appproved</label> <!-- PS Madam not yet Comments -->
									</td>
								</tr>
							<?}elseif ($cmd['EMPCODE'] == 3) {?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b>SK SIR Comments </b> : Not Yet Appproved</label> <!-- SK Sir not yet Comments -->
									</td>
								</tr>
							<?}else{
								$sql_check = select_query_json("select * from approval_request
																		where aprnumb in ('".$sql_reqid[0]['APRNUMB']."') and REQESEC=".$cmd['ESECODE']." and appstat='F'
																			and RQFRDES like '".$cmd['EMPCODE']." - %'
																		order by arqsrno", 'Centra', 'TEST');
								if($sql_check[0]['ARQCODE'] == ""){
								?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b><?=$cmd['EMPCODE']." - ".$cmd['EMPNAME']?> Comments </b> : Not Yet Appproved</label> <!-- All User Not Yet Appproved Comments -->
									</td>
								</tr>
							<?}
							}
						}

						// print_r($comnts_user);
						for($usri = 0; $usri < count($comnts_user) && $comnts_user[$usri] != ''; $usri++) { ?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b><?=$comnts_user[$usri]?> Comments </b> <label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( <?=$his_time[$usri]?> )</label> : <?=$comnts_rmrk[$usri]?></label> <!-- All User Comments -->
									</td>
								</tr>
							<? 	if($usri == (count($comnts_user) - 1) and count($ks_cmnts) <= 0) {
									$sql_md = select_query_json("select INTSUGG, INTPESN, RQBYDES, ADDDATE, REQSTBY from APPROVAL_REQUEST req
																	where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and arqsrno in (select max(arqsrno)
																		from APPROVAL_REQUEST where aprnumb = req.aprnumb)
																	order by req.ARQSRNO desc", 'Centra', 'TEST');
									if($sql_md[0]['INTSUGG'] != '' and $sql_md[0]['INTSUGG'] != '-') {
										if($sql_md[0]['INTPESN'] != '' and $sql_md[0]['INTPESN'] != '-') {
											$sircmnts = $sql_md[0]['INTPESN'];
											$ks_name = $sql_md[0]['RQBYDES'];
											$ks_adddate = $sql_md[0]['ADDDATE'];
											$ks_empsrno = 20118;

											// Read KS sigature from FTP
											// $ks_sign = "ftp://$ftp_user_name_apdsk:$ftp_user_pass_apdsk@$ftp_server_apdsk.$ftp_srvport_apdsk/approval_desk/digital_signature/".$ks_empsrno.".png";
											$ks_sign = "ftp_image_view.php?pic=".$ks_empsrno.".png&path=".$folder_path."";
											// Read KS sigature from FTP

										} else { $sircmnts = $sql_md[0]['INTSUGG']; } ?>
										<tr style='min-height:25px; line-height:25px;'>
											<td style='width:100%; padding:0 10px; text-align:left;'>
												<label><b>KS SIR Comments </b> : <?=$sircmnts?></label> <!-- KS Sir Comments -->
											</td>
										</tr>
								<? }
								}
							} ?>
					</table>
					</td>
				</tr>

				<? if($_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_descode'] == 9) { // DGM / GM / Sr.GM / MD's can Add Approval flow User dynamically ?>
					<!-- Add Approval flow User dynamically -->
					<tr><td colspan="4"><label for="slt_appflow_users">Add Approval Flow User Dynamically : </label></td></tr>
					<tr>
						<td colspan="4">
							<select input type="text" name="slt_appflow_users[]" id="slt_appflow_users" style="width: 773px; margin-top: 5px; vertical-align: top;" data-placement="top">
	                        <option input type="checkbox" multiple class="form-control select" name='slt_appflow_users[]' value='checked' id='slt_appflow_users' data-toggle="tooltip" style="width: 773px; margin-top: 5px; vertical-align: top;" data-placement="top" title="Approval flow Users"  onchange='call_iv()' onblur='call_iv()'></option>
							<option value=''>-- Select Flow USer --</option>
								<? 	for($sql_inteam_i = 0; $sql_inteam_i < count($sql_inteam); $sql_inteam_i++) { ?>
									<option value='<?=$sql_inteam[$sql_inteam_i]['EMPCODE']?>'><?=$sql_inteam[$sql_inteam_i]['EMPNAME']." - ".$sql_inteam[$sql_inteam_i]['EMPCODE']." - ".$sql_inteam[$sql_inteam_i]['DESNAME']." - ".$sql_inteam[$sql_inteam_i]['BRNNAME']." - ".$sql_inteam[$sql_inteam_i]['ESENAME'].""?></option>
								<? } ?>
							</select>

							<? /* <? 	for($sql_inteam_i = 0; $sql_inteam_i < count($sql_inteam); $sql_inteam_i++) {
									$valu_team .= str_replace(",", "", $sql_inteam[$sql_inteam_i]['EMPNAME'])." - ".$sql_inteam[$sql_inteam_i]['EMPCODE']." - ".str_replace(",", "", $sql_inteam[$sql_inteam_i]['DESNAME'])." - ".str_replace(",", "", $sql_inteam[$sql_inteam_i]['BRNNAME'])." - ".str_replace(",", "", $sql_inteam[$sql_inteam_i]['ESENAME']).", ";
								} ?>

							<input type="text" name='slt_appflow_users[]' id='slt_appflow_users' class="slt_appflow_users demo-default" value="<?=$valu_team?>">*/ ?>
						</td>
					</tr>
					<!-- Add Approval flow User dynamically -->
				<? } ?>
				<!-- Comments History -->

				</table>
				</td>
				</tr>
				<tr style='height:10px;'><td></td></tr>

				<tr>
					<td colspan=2 style='width:100%; max-width: 773px; padding-top:0px; text-align:left;'>
						<label>Thanks & Regards</label> <!-- Thanks & Regards -->
					</td>
				</tr>

				<tr>
				<td colspan=2 style='width:100%; font-size:11px; text-align:left;'>
					<table border=0 style='max-width: 773px; width:100%;'>
					<tr>
						<td style='width:35%; text-align:left;'>
							<label style='color:#000000; font-size:10px; '><b><?=$creator?></b> - <?=$creator_dept?><br>
							<b>Work Initiate Person : <? $sql_usr = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, empsection sec, designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['WRKINUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
																						union
																							select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['WRKINUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
																							order by EMPCODE", 'Centra', 'TCS');
								echo $sql_usr[0]['EMPCODE']." - ".$sql_usr[0]['EMPNAME']; ?><br>
							<b>Responsible Person : <? $sql_usr = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, empsection sec, designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
																						union
																							select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
																							order by EMPCODE", 'Centra', 'TCS');
								echo $sql_usr[0]['EMPCODE']." - ".$sql_usr[0]['EMPNAME']; ?></b></label> <!-- Request Creator -->
						</td>

						<? //////
						if($steam_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($steam_name != '') { echo $steam_name; } ?></label> <!-- S-Team Audit -->
							</td>
						<? } ?>

						<? if($exc_mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($exc_mgr_name != '') { echo $exc_mgr_name; } ?></label> <!-- DB -->
							</td>
						<? } ?>

						<? if($mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($mgr_name != '') { echo $mgr_name; } ?></label> <!-- Manager -->
							</td>
						<? } ?>

						<? if($legal_name != '') { ?>
							<td style='width:15%; text-align:right;'>
								<label style='color:#000000; font-size:10px; '><? echo $legal_name; ?></label> <!-- Legal Audit -->
							</td>
						<? } ?>

						<? 	$srexc_mgr_name = array_values(array_unique($srexc_mgr_name));
							$srexc_mgr_sign = array_values(array_unique($srexc_mgr_sign));
							$srexc_mgr_dept = array_values(array_unique($srexc_mgr_dept));

							if(count($srexc_mgr_name) > 0) {
							for($hodi = 0; $hodi < count($srexc_mgr_name); $hodi++) {
								if($srexc_mgr_sign[$hodi] != '') { ?>
									<td style='width:15%; text-align:right;>'>
										<label style='color:#0088CC; width:14%;'><img src='<?=$srexc_mgr_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- HOD / Branch Manager -->
									</td>
								<? } else { ?>
									<td style='width:15%; text-align:right;'>
										<label style='width:14%;'><?=$srexc_mgr_name[$hodi]?></label> <!-- HOD / Branch Manager -->
									</td>
							<? } ?>
						<? }
						} ///////////////////////////////// ?>
					</tr>

					<tr>
						<td style='width:35%; text-align:left;'>
							<label style='color:#0088CC; font-weight:bold'><? echo find_employee_branch($creator); ?></label> <!-- Chennai Silks -->
						</td>

						<? ////////////////////
						if($steam_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($steam_name != '') { ?>S-Team Audit<? } ?></label> <!-- S-Team Audit -->
							</td>
						<? } ?>

						<? if($exc_mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($exc_mgr_name != '') { ?>DB<? } ?></label> <!-- DB -->
							</td>
						<? } ?>

						<? if($mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($mgr_name != '') { ?>Manager / Trainee<? } ?></label> <!-- Manager / Trainee -->
							</td>
						<? } ?>

						<? if($legal_name != '') { ?>
							<td style='width:15%; text-align:right;'>
								<label style='color:#0088CC; font-weight:bold'><? if($legal_name != '') { ?>Legal Audit<? } ?></label> <!-- Legal Audit -->
							</td>
						<? } ?>

						<? if(count($srexc_mgr_name) > 0) {
							for($hodi = 0; $hodi < count($srexc_mgr_name); $hodi++) { ?>
								<td style='width:14%; font-size:11px; text-align:right;'>
									<label style='color:#0088CC; font-weight:bold'><?=$srexc_mgr_adddate[$hodi]?><br><? if($srexc_mgr_dsgn[$hodi] != '') { echo $srexc_mgr_dsgn[$hodi]; } else { echo "Manager / Sr.Exe"; } ?></label> <!-- Manager / Sr.Exe -->
								</td>
						<? } }
						/////////////////////// ?>
					</tr>

					</table>
				</td>
				</tr>
				<tr style='height:20px;'><td></td></tr>



				<!-- Approval Desk Purpose Only -->
				<tr>
				<td colspan=2 style='width:100%; font-size:10px; text-align:left;'>
					<table border=0 style='max-width: 773px; width:100%; min-height: 75px; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px;'>

					<? $txtalign = 'text-align:center;'; if(count($find_leads) == 2) { $txtalign = 'text-align:right;'; } ?>

						<tr><td colspan=4>
							<? if($viewonly == 0) { ?>
								<table border=0 cell-padding=1 cell-spacing=1 style='width:100%; padding-left:2px; padding-right:2px;'>
							<? } else { ?>
								<table border=0 cell-padding=1 cell-spacing=1 style='width:100%; padding-top: 35px; padding-left:2px; padding-right:2px;'>
							<? } ?>
								<tr>
							<?
								$sql_apphir = select_query_json("select * from approval_mdhierarchy where aprnumb in ('".$sql_reqid[0]['APRNUMB']."' ) and apphead=1 order by amhsrno ", 'Centra', 'TEST');
								$sql_appak = select_query_json("select * from approval_mdhierarchy where aprnumb in ('".$sql_reqid[0]['APRNUMB']."' ) and apphead=3 order by amhsrno ", 'Centra', 'TEST');
								$sql_appps = select_query_json("select * from approval_mdhierarchy where aprnumb in ('".$sql_reqid[0]['APRNUMB']."' ) and apphead=2 order by amhsrno ", 'Centra', 'TEST');
								$sql_all_md = select_query_json("select count(*) VAL from approval_mdhierarchy where  aprnumb in ('".$sql_reqid[0]['APRNUMB']."' ) and  apphead in (1,2)", 'Centra', 'TEST');

								if($sql_all_md[0]['VAL'] == 2)
								{
									$single = 1;
								}else{
									$single = 0;
								}

								$bm_name = array_values(array_unique($bm_name));
								$hod_name = array_values(array_unique($hod_name));
								$gm_name = array_values(array_unique(array_reverse($gm_name)));

								$tot_len = sizeof($bm_name);
								$tot_len += sizeof($hod_name);
								$tot_len += sizeof($srexc_mgr_name_audit);
								$tot_len += sizeof($cc_name);
								$tot_len += sizeof($gm_name);
								$tot_len += sizeof($srgm_name);
								$md = 0;
								if($ak_name != '') {
								 	$tot_len += sizeof($ak_name);
								 	$md += 1;
								}elseif($ceo_available == 1) {
							 		$tot_len += 1;
							 		$md += 1;
							 	}elseif($sql_hir[0]['AK'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
							 		$tot_len += 1;
							 		$md += 1;
							 	}

						 		if($ps_name != '') {
						 			$tot_len += sizeof($ps_name);
						 			$md += 1;
						 		}elseif($sql_hir[0]['PS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
						 			$tot_len +=1;
						 			$md += 1;
						 		}

								if($ks_name != '') {
									$tot_len += sizeof($ks_name);
									$md += 1;
								} elseif($sql_hir[0]['KS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
									$tot_len += 1;
									$md += 1;
								}

							 	if($tot_len >7){
							 		?>
							 		<tr>
										<?if($md == 1){?>
											<td></td>
											<td></td>
											<td></td>
										<?}else{?>
											<td></td>
											<td></td>
										<?}
							 	if($ak_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ak_sign?>' border=0 style='border:0px solid #d8d8d8; height:45px;'></label> <!-- AK -->
								</td>
								<? } ?>

								<? if($ps_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ps_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- PS Madam -->
								</td>
								<? } ?>

								<? if($ks_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ks_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- CEO / MD -->
								</td>
								<? }?></tr><?
								}

								if($tot_len >7){?>
									<tr>
										<?if($md == 1){?>
											<td></td>
											<td></td>
											<td></td>
										<?}else{?>
											<td></td>
											<td></td>
										<?}

								if($ak_name != '' && $sql_appak[0]['APPHEAD'] == 3) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: bottom;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ak_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "S KAARTHI";?>
										</label><br>CEO</label> <!-- CEO / AK -->
									</td>
								<? } elseif($sql_hir[0]['AK'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: bottom;'>
										<br><label style="color: black;font-weight:bold;">
											<?echo "S KAARTHI";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>CEO</label>
									</td>
								<?} ?>

								<? if($ps_name != '' && $sql_appps[0]['APPHEAD'] == 2) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ps_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "PADHMA SIVLINGAM";?>
										</label><br>
										<?
										echo "CAO";
										?>
										 </label> <!-- CAO / PS Madam -->
									</td>
								<? } elseif($sql_hir[0]['PS'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: bottom;'>
										<br>
										<label style="color: black;font-weight:bold;">
											<?echo "PADHMA SIVLINGAM";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>CAO</label>
									</td>
								<?} ?>

								<? if($ks_name != '' && $sql_apphir[0]['APPHEAD'] == 1) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ks_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "K.SIVALINGAM";?>
										</label><br>
										<?
										echo "COO";
										?>
										</label> <!-- COO / MD -->
									</td>
								<? } elseif($sql_hir[0]['KS'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: bottom;'>
										<br>
										<label style="color: black;font-weight:bold;">
											<?echo "K.SIVALINGAM";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>COO</label>
									</td>
								<?}?>
								</tr>
							 <tr><td colspan="7" style="padding:10px;"></td></tr>
							<?} ?>

								<? 	// print_r($hod_sign); echo "<br>--------";
									// $bm_name = array_values(array_unique($bm_name));
									$bm_sign = array_values(array_unique($bm_sign));
									$bm_dept = array_values(array_unique($bm_dept));
									// print_r($bm_sign); echo "<br>--------";

									if(count($bm_name) > 0) {
									for($hodi = 0; $hodi < count($bm_name); $hodi++) {
											if($bm_sign[$hodi] != '') { ?>
												<td style='<?=$txtalign?>'>
													<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$bm_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Branch Manager -->
												</td>
											<? } else { ?>
												<td style='<?=$txtalign?>'>
													<label style='width:14%; font-weight:bold' class="green_highlight"><?=$bm_name[$hodi]?></label> <!-- Branch Manager -->
												</td>
										<? }
									} }

									// print_r($hod_sign); echo "<br>--------";
									//$hod_name = array_values(array_unique($hod_name));
									$hod_sign = array_values(array_unique($hod_sign));
									$hod_dept = array_values(array_unique($hod_dept));
									// print_r($hod_sign); echo "<br>--------";

									if(count($hod_name) > 0) {
									for($hodi = 0; $hodi < count($hod_name); $hodi++) {
											if($hod_sign[$hodi] != '') { ?>
												<td style='<?=$txtalign?>'>
													<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$hod_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- HOD / Branch Manager -->
												</td>
											<? } else { ?>
												<td style='<?=$txtalign?>'>
													<label style='width:14%; font-weight:bold' class="green_highlight"><?=$hod_name[$hodi]?></label> <!-- HOD / Branch Manager -->
												</td>
										<? }
									} } ?>

								<? if($srexc_mgr_name_audit != '') { // echo "CAM".$srexc_mgr_empsrno_audit."E"; ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#000000; width:14%; font-size:10px;'><? if($srexc_mgr_sign_audit != '') { ?>
										<img src='<?=$srexc_mgr_sign_audit?>' border=0 <? if($srexc_mgr_empsrno_audit == 62762) { ?> style='border:0px solid #d8d8d8; width:45px;' <? } else { ?> style='border:0px solid #d8d8d8; width:85px; height:25px;' <? } ?>>
									<? } else { echo $srexc_mgr_name_audit; } ?></label> <!-- S-Audit -->
								</td>
								<? } ?>

								<? if($cc_name != '') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#000000; width:14%; font-size:10px;'><img src='<?=$cc_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Cost Control -->
								</td>
								<? } ?>

								<?	//$gm_name = array_values(array_unique(array_reverse($gm_name)));
									$gm_sign = array_values(array_unique(array_reverse($gm_sign)));
									$comnts_gmuser = array_values(array_unique(array_reverse($comnts_gmuser)));
									// print_r($gm_name); print_r($gm_sign);
									if(count($gm_name) > 0) {
									for($gmi = 0; $gmi < count($gm_name); $gmi++) { ?>
										<td style='<?=$txtalign?>'>
											<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$gm_sign[$gmi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- GM -->
										</td>
								<? } } ?>

								<? if($srgm_name != '') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$srgm_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Sr.GM -->
								</td>
								<? } ?>

								<?
								if($tot_len <=7){
								if($ak_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ak_sign?>' border=0 style='border:0px solid #d8d8d8; height:45px;'></label> <!-- AK -->
								</td>
								<? } ?>

								<? if($ps_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ps_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- PS Madam -->
								</td>
								<? } ?>

								<? if($ks_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ks_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- CEO / MD -->
								</td>
								<? }} ?>
							</tr>

							<tr style="vertical-align: top;">
								<? $dept_n = "";
								if(count($bm_name) > 0) {
									for($hodi = 0; $hodi < count($bm_name); $hodi++) {
										$dept_n = substr($bm_dept[$hodi], 3); ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$bm_adddate[$hodi]?><br>
											<label style="color: black;"><?$b_name = explode(' - ', $bm_name[$hodi]);
											echo $b_name[1];?></label><br>
												<? if($bm_dsgn[$hodi] != '') { echo $bm_dsgn[$hodi]; } else { echo "BM"; } ?></label> <!-- Branch Manager -->
										</td>
								<? } } elseif($dept_n != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$dept_n?> BM</label> <!-- Branch Manager -->
									</td>
								<? }
								$dept_n = "";
								if(count($hod_name) > 0) {
									for($hodi = 0; $hodi < count($hod_name); $hodi++) {
										$dept_n = substr($hod_dept[$hodi], 3); ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold;'><?=$hod_adddate[$hodi]?><br>
												<label style="color: black;"><?$h_name = explode(' - ', $hod_name[$hodi]);
											echo $h_name[1];?></label><br>
											<? if($hod_dsgn[$hodi] != '') { echo $hod_dsgn[$hodi]; } else { echo "DGM/HOD"; } ?></label> <!-- DGM / HOD -->

										</td>
								<? } } elseif($dept_n != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$dept_n?> DGM/HOD/BM</label> <!-- DGM / HOD -->
									</td>
								<? } ?>

								<? if($srexc_mgr_name_audit != '') { ?>
								<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
									<label style='color:#0088CC; font-weight:bold'><?=$srexc_mgr_adddate_audit?><br>
									<label style="color: black;"><?$sr_m_name = explode(' - ', $srexc_mgr_name_audit);
											echo $sr_m_name[1];?></label><br>
										<?=$srexc_mgr_desg?></label> <!-- S-Audit -->
								</td>
								<? } ?>

								<? if($cc_name != '') { ?>
								<td style='<?=$txtalign?> width:14%; font-size:11px;'>
									<label style='color:#0088CC; font-weight:bold'><?=$cc_adddate?><br>
									<label style="color: black;">
										<?$c_name = explode(' - ', $cc_name);
										echo $c_name[1];?>
									</label><br>
									<?=$cc_desg?></label> <!-- Cost Control -->
								</td>
								<? } ?>

								<? // print_r($gm_name);
								if($_REQUEST['typeid'] ==3)
								{
									echo "<br>";
								}
								if(count($gm_name) > 0) {
									for($gmi = 0; $gmi < count($gm_name); $gmi++) { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$gm_adddate?><br>
											<label style="color: black;">
												<?$g_name = explode(' - ', $gm_name[$gmi]);
												echo $g_name[1];?>
											</label><br>
											<? if($gm_adddate != '') { echo $comnts_gmuser[$gmi]; ?>GM<? } else { ?>Sr.GM / GM<? } ?></label> <!-- GM -->
										</td>
								<? } } elseif($sql_reqid[0]['APPSTAT'] != 'A' and $srgm_name == "" ) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'>Sr.GM / GM</label> <!-- GM -->
									</td>
								<? } ?>

								<? if($srgm_name != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
										<label style='color:#0088CC; font-weight:bold'><?=$srgm_adddate?><br>
										<label style="color: black;">
											<?$srg_name = explode(' - ', $srgm_name);
											echo $srg_name[1];?>
										</label><br>
										<? if($srgm_adddate != '') { ?>Sr.GM<? } else { ?>Sr.GM / GM<? } ?></label> <!-- Sr.GM -->
									</td>
								<? } ?>

								<? if($tot_len <=7){

								if($ak_name != '' && $sql_appak[0]['APPHEAD'] == 3) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ak_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "S KAARTHI";?>
										</label><br>CEO</label> <!-- CEO / AK -->
									</td>
								<? } elseif($sql_hir[0]['AK'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
										<br><label style="color: black;font-weight:bold;">
											<?echo "S KAARTHI";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>CEO</label>
									</td>
								<?} ?>

								<? if($ps_name != '' && $sql_appps[0]['APPHEAD'] == 2) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ps_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "PADHMA SIVLINGAM";?>
										</label><br>
										<?
										echo "CAO";
										?>
										 </label> <!-- CAO / PS Madam -->
									</td>
								<? } elseif($sql_hir[0]['PS'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
										<br>
										<label style="color: black;font-weight:bold;">
											<?echo "PADHMA SIVLINGAM";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>CAO</label>
									</td>
								<?} ?>

								<? if($ks_name != '' && $sql_apphir[0]['APPHEAD'] == 1) { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$ks_adddate?><br>
										<label style="color: black;font-weight:bold;">
											<?echo "K.SIVALINGAM";?>
										</label><br>
										<?
										echo "COO";
										?>
										</label> <!-- COO / MD -->
									</td>
								<? } elseif($sql_hir[0]['KS'] != 0){?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: bottom;'>
										<br>
										<label style="color: black;font-weight:bold;">
											<?echo "K.SIVALINGAM";?>
										</label><br>
										<label style='color:#0088CC; font-weight:bold'>COO</label>
									</td>
								<?} } ?>
								</tr>
							</table>
						</td></tr>

					</table>
				</td>
				</tr>
				<!-- Approval Desk Purpose Only -->
				</table>
				</td></tr>
			</table>
		</td>
		</tr>
		</table>


		<? if(count($sql_prdlist) > 0 and $edtvl == 0) {
			$sql_prdlist1 = select_query_json("select * from APPROVAL_PRODUCTLIST PRD, APPROVAL_PRODUCT_QUOTATION QUT
			    												where PRD.PBDYEAR = QUT.PBDYEAR AND PRD.PBDCODE = QUT.PBDCODE and PRD.PBDLSNO = QUT.PBDLSNO and
			    													QUT.PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."' AND QUT.PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and QUT.SLTSUPP = 0 and REJUSER is null
			    												order by PRD.PBDYEAR, PRD.PBDCODE, PRD.PBDLSNO", 'Centra', 'TEST');
			if(count($sql_prdlist1) > 0) { ?>
			<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='margin-top: 5px; border:1px solid #303030; background-color: #ffffff; border-style:dashed; width:796.8px; max-height:1123.2px; padding:7px'>
			<tr><td class="cls_pagebreak"></td></tr>
			<!-- Page 2 -->
			<tr><td colspan="2" style="text-align: center; padding-top: 0px; font-weight: bold;"> Competitive Suppliers </td></tr>
			<tr><td colspan='2'>
			<table class="monthyr_wraps" style='width:100%; line-height:22px;'>
			<tr><td width="25%"></td><td width="25%"></td><td width="25%"></td><td width="25%"></td></tr>
			<tr style='border:1px solid #0088CC; width:100%;'>
				<!-- Supplier Quotation -->
				<table style="width: 100%; line-height: 15px;">
					<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
						<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 2%;">#</td>
						<td class="colheight" style="padding: 0px;width: 25%;">Supplier</td>
						<td class="colheight" style="padding: 0px;width: 28%;">Product</td>
			            <td class="colheight" style="padding: 0px;width: 8%;">Per Piece Rate &#8377</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Tax &#8377</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Discount %</td>
			            <td class="colheight" style="padding: 0px;width: 7%;">Qty.</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Net Amount &#8377</td>
					</tr>
				<?
					$inc = 0;
					foreach($sql_prdlist1 as $prdlist) { $inc++; ?>
						<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
							<td class="colheight" style="padding: 1px 0px; width: 2%;"><?=$inc?></td>
							<td class="colheight" style="padding: 1px 0px 1px 3px; width: 25%; text-align: left;">
								<?=$prdlist['SUPCODE']." - ".$prdlist['SUPNAME']?></span>
							</td>
							<td class="colheight" style="padding: 1px 0px 1px 3px; width: 28%; text-align: left;">
								<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?> <br>
								<span style="font-size: 9px;color: #a0a0a0;">( <?=$prdlist['PRDSPEC']?> )
									<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "AD. DURATION : ".$prdlist['ADURATI'].""; } ?>
					    			<? if($prdlist['ADLENGT'] == '0' and $prdlist['ADWIDTH'] == '0') { echo ""; }
					    			   else { echo "SIZE ( L X W ) : ".$prdlist['ADLENGT']." X ".$prdlist['ADWIDTH'].""; } ?>
					    			<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "AD. PRINT LOCATION : ".$prdlist['ADLOCAT'].""; } ?></span>
							</td>
				    		<td class="colheight" style="padding: 1px 0px; width: 8%;"><? $expl1 = explode(".", $prdlist['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?><br><span style="font-size: 9px;color: #a0a0a0;">Adv. Amt. : <?=moneyFormatIndia($prdlist['ADVAMNT'])?></span></td>

				            <td class="colheight" style="padding: 1px 0px; width: 10%;">
				    			<? 	$ttlqty = 0;
				    				$ttlqty = $prdlist['PRDRATE'] * $prdlist['TOTLQTY'];
				    				if($prdlist['SGSTVAL'] == '0' ) { echo ""; } else { $sc_per = 0; $sc_per = round(($prdlist['SGSTVAL'] / $prdlist['PRDRATE']) * 100, 2); echo "SGST (".$sc_per." %) : ".$prdlist['SGSTVAL']."<BR>"; } ?>
				    			<? if($prdlist['CGSTVAL'] == '0' ) { echo ""; } else { $cc_per = 0; $cc_per = round(($prdlist['CGSTVAL'] / $prdlist['PRDRATE']) * 100, 2); echo "CGST (".$cc_per." %) : ".$prdlist['CGSTVAL']."<BR>"; } ?>
				    			<? if($prdlist['IGSTVAL'] == '0' ) { echo ""; } else { $ic_per = 0; $ic_per = round(($prdlist['IGSTVAL'] / $prdlist['PRDRATE']) * 100, 2); echo "IGST (".$ic_per." %) : ".$prdlist['IGSTVAL']."<BR>"; } ?>
				    		</td>
				            <td class="colheight" style="padding: 1px 0px; width: 10%;">
				    			<? if($prdlist['DISCONT'] == '0' ) { echo ""; } else { $ds_per = 0; $ds_per = round(($prdlist['DISCONT'] / $prdlist['PRDRATE']) * 100, 2); echo /* "DISCOUNT : " */ "(".$ds_per." %) ".$prdlist['DISCONT']."<BR>"; } ?>
				    		</td>

				            <td class="colheight" style="padding: 1px 0px; width: 7%;"><?=$prdlist['TOTLQTY']?></td>
				            <td class="colheight" style="padding: 1px 0px; width: 10%; margin-right: 0.6%;"><?=moneyFormatIndia($prdlist['NETAMNT'])?></td>
						</tr>
					<? } ?>
				</table>
			</tr>
			<!-- Page 2 -->
			</table>
			</td>
		</tr>
		</table>
		<? } } ?>

		<? if($viewonly == 0) {
			$sql_approval_tags = select_query_json("select * from APPROVAL_TAGS
                                                            where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' and TAGSTAT = 'N'
                                                            order by TAGSRNO", "Centra", 'TEST'); ?>
		<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='margin-top: 5px; margin-bottom: 5px; border:1px solid #303030; border-style:dotted; background-color: #ffffff; width:796.8px; padding:7px;'>
			<? if(count($sql_approval_tags) > 0) { ?>
				<tr>
					<td style="width: 100%; vertical-align: top;" colspan="2">Approval Tags :
					<?  echo "<ul class=\"list-tags\">";
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
		           	</td>
				</tr>
			<? } ?>


		<tr>
			<td style="width: 49%; vertical-align: top;">Your Remarks <span style='color:red'>*</span> : </td>
			<td style="width: 51%; vertical-align: top;">Supporting Documents Attachment <span style='color:red'>*</span> : </td>
		</tr>
		<tr>
			<td style="width: 49%; vertical-align: top;">
				<textarea class="form-control" tabindex="26" name='txt_remarks' id='txt_remarks' maxlength="200" style='width:95%; text-transform:uppercase; height:75px; padding: 5px;' required><? if($_SESSION['tcs_empsrno'] == '125' and $sql_approve_leads[0]['APPFVAL'] > 0) { ?>BUDGET HEADS ARE OK<? } else { ?>APPROVED<? } ?></textarea><span style='color:#FF0000; font-size:10px;'>NOTE : Maximum 200 Characters Allowed..</span>
			</td>
			<td style="width: 51%; vertical-align: top;">
				<input class="form-control" placeholder="Supporting Documents" tabindex='10' maxlength='150' type='file' name='txt_submission_fieldimpl[]' id='txt_submission_fieldimpl' multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Supporting Documents"><br><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span>
				<? // if($_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 92 or $_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 169 or $_SESSION['tcs_descode'] == 132 or $_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_empsrno'] == 61579 or $_SESSION['tcs_empsrno'] == 59006 or $_SESSION['tcs_empsrno'] == 188 or $_SESSION['tcs_empsrno'] == 62762 or $_SESSION['tcs_empsrno'] == 1746 or $_SESSION['tcs_empsrno'] == 34593) { ?>
				<br><br>Intermediate Team <span style='color:red'>*</span> : <br>
				<select <? if($_SESSION['tcs_empsrno'] != '452') { ?>class="form-control custom-select chosn"<? } else { ?>class="form-control"<? } ?> tabindex='27' name='slt_intermediate_team' id='slt_intermediate_team' data-toggle="tooltip" style="width: 100%; vertical-align: top;" data-placement="top" title="Intermediate Team" >
					<? 	for($sql_inteam_i = 0; $sql_inteam_i < count($sql_inteam); $sql_inteam_i++) {
							if($sql_inteam[$sql_inteam_i]['EMPCODE'] != 1 and $sql_inteam[$sql_inteam_i]['EMPCODE'] != 2) { ?>
								<option value='<?=$sql_inteam[$sql_inteam_i]['EMPCODE']?>'><?=$sql_inteam[$sql_inteam_i]['EMPNAME']." - ".$sql_inteam[$sql_inteam_i]['EMPCODE']." - ".$sql_inteam[$sql_inteam_i]['DESNAME']." - ".$sql_inteam[$sql_inteam_i]['BRNNAME']." - ".$sql_inteam[$sql_inteam_i]['ESENAME'].""?></option>
					<? 		}
						} ?>
				</select>
			<? // } ?>
			</td>
		</tr>

		<? // Assign Finish User & Finish Option - 18-09-2017
			$final_approval = 0; $mdfin = 0; $finish_here = 0; $ccfinish_here = 0;
			// Cost Control Finish Operations
			$targetnos_chk = array("7580", "7515", "7572", "7604", "7655", "7581", "7501", "7502", "7601", "7602", "7603", "7662", "7663", "7682", "7585", "7588", "7583", "7584", "7676"); // 7580,7515,7572,7604,7655,7581,7501,7502,7503,7601,7602,7603,7662,7663,7682,7585,7588,7583,7584,7676 - Target Numbers "7503" - Salary Exp - Removed as per Admin Senthil instruction
			if((in_array($sql_reqid[0]['TARNUMB'], $targetnos_chk) or $sql_reqid[0]['PRJPRCS'] == 'M') and $_SESSION['tcs_esecode'] == '137' and $sql_reqid[0]['APPFVAL'] > 0) { //  and $_SESSION['tcs_empsrno'] == '61579'
				$ccfinish_here = 1; $finish_here = 1; $final_approval = 2;
			}
			// Cost Control Finish Operations

			if($sql_requsr[0]['REQSTFR'] == 452 and (($sql_reqid[0]['PRJPRCS'] == 'C' or $sql_reqid[0]['PRJPRCS'] == 'M' or (($sql_reqid[0]['APRQVAL'] < 100000 and $sql_reqid[0]['APRQVAL'] > 0) or ($sql_reqid[0]['APMCODE'] == 817 or $sql_reqid[0]['APMCODE'] == 447 or $sql_reqid[0]['APMCODE'] == 601 or $sql_reqid[0]['APMCODE'] == 91 or $sql_reqid[0]['APMCODE'] == 92))) and ($sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7 or $sql_reqid[0]['ATYCODE'] == 2 or $sql_reqid[0]['ATYCODE'] == 3 or $sql_reqid[0]['ATYCODE'] == 4 or $sql_reqid[0]['ATYCODE'] == 5 or $sql_reqid[0]['APMCODE'] == 817 or $sql_reqid[0]['APMCODE'] == 447))) { // GM / SR.GM approval with below Rs 100000 and ALL Type 	of Submission.. & Not approve the STEAM and 	MANAGEMENT Approvals & Project Process Type - C / M -  and $sql_reqid[0]['ATCCODE'] != 1 and $sql_reqid[0]['ATCCODE'] != 2 || if Approval Listing is in "LATE BILL PAYMENT 817" OR "LATE DELIVERY APPROVAL 447" OR "TABLE CREATE 91" OR "TABLE UPDATE 92" can finish by Admin GM
					$mdfin++; $finish_here = 1; $ccfinish_here = 0;
			}
			// Assign Finish User & Finish Option - 18-09-2017 ?>
		<div id="id_approval_listings" style="display: none">
		<div class="form-group trbg" style='min-height:40px;'>
			<div class="col-lg-3 col-xs-3">
				<label style='height:27px;'>Next Approval Flow <span style='color:red'>*</span></label>
			</div>
			<div class="col-lg-9 col-xs-9" style="font-weight:bold;">
				: <? // if(($_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') and ($ccfinish_here == 0)) {
					if($_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') {

						///////
						$usr_cd = $_SESSION['tcs_user'];
						if($sql_reqid[0]['INTPEMP'] == $_SESSION['tcs_empsrno']) {
							$exfr = explode(" - ", $sql_reqid[0]['RQFRDES']);
							$usr_cd = $exfr[0];
						}
						///////

						/* switch($_SESSION['tcs_empsrno']) {
							case 127: // RAJA MDU - Empsrno
								// $usr_cd = '1333'; break; // RATHEESH KUMAR R D MDU - EMPCODE
								$usr_cd = $_SESSION['tcs_user']; break;

							case 1202: // PALANISAMY.V - Empsrno
								$usr_cd = '1726'; break; // SIVAKUMAR P - EMPCODE

							case 76856: // SELVAGANAPATHI - Empsrno
							case 48237: // SARATH - Empsrno
							case 63624: // HARI - Empsrno
							case 59006: // Ranganathan - Empsrno
								$usr_cd = '17108'; break; // Selva Muthu Kumar - EMPCODE

							case 1169: // HW Karthik - Empsrno
								$usr_cd = '1430'; break; // Saravanakumar - EMPCODE

							case 37048: // HR Sathish - Empsrno
								$usr_cd = '18294'; break; // HR Senthil - EMPCODE

							case 13613: // Project Praveen - Empsrno
								$usr_cd = '5174'; break; // Gunasekar - EMPCODE

							case 62762: // Ramakrishnan - Empsrno
								$usr_cd = '1657'; break; // Ashok - EMPCODE

							case 23684: // prem - Empsrno
								$usr_cd = '1845'; break; // bala advt - EMPCODE

							case 572: // Selvaraj - Empsrno
								$usr_cd = '3486'; break; // Rathessh - EMPCODE

							case 82237: 	// Dhinesh Khanna - Empsrno
								$usr_cd = '4317'; break; // Manoharan - EMPCODE

							case 86464: 	// Nanthakumar - Empsrno
								$usr_cd = '12232'; break; // Madhan - EMPCODE

							default:
								$usr_cd = $_SESSION['tcs_user']; break;
						} */

						$flo = 0; $newentry = 0;
						$sql_app_hierarchy1 = select_query_json("select * from APPROVAL_MDHIERARCHY amh, employee_office emp
																		where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			APRNUMB = '".$sql_reqid[0]['APRNUMB']."'
																		order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
						if(count($sql_app_hierarchy1) > 0) { $flo = 1; $newentry = 1;
							$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MDHIERARCHY amh, employee_office emp
																	where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		APRNUMB = '".$sql_reqid[0]['APRNUMB']."' and amh.APPHEAD = '".$usr_cd."'
																	order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
							$ampsrno = "";
							if(count($sql_cur_hier) > 0) {
								$ampsrno = "and amh.AMHSRNO < '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
							} else {
								if($sql_reqid[0]['APPFRWD'] == 'Q' or $sql_reqid[0]['APPFRWD'] == 'I') {
									$ex_bydes = explode(" - ", $sql_reqid[0]['RQBYDES']);
									$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MDHIERARCHY amh, employee_office emp
																			where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																				APRNUMB = '".$sql_reqid[0]['APRNUMB']."' and amh.APPHEAD = '".$ex_bydes[0]."'
																			order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
									$ampsrno = "and amh.AMHSRNO < '".($sql_cur_hier[0]['AMHSRNO'] + 1)."'"; // From Query Response Level Approval..
								} else {
									$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
								}
							}

							// echo "**".$sql_reqid[0]['APPSTAT']."**".$sql_reqid[0]['APPFRWD']."**";
							if($sql_reqid[0]['APPSTAT'] == 'W') {
								$sql_rsruser = select_query_json("select * from APPROVAL_request where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and arqsrno = '".$rsrid."' order by arqsrno desc", 'Centra', 'TEST');
								if($sql_rsruser[0]['APPFRWD'] == 'P') {
									$expld1 = explode(" - ", $sql_rsruser[0]['RQTODES']);
									echo $expld1[1]." - ".$expld1[0]." [ 1 Day(s) ] <br>";
									$appdays += 1;
									$appuser .= $expld1[0]."~~";
								}
							}

							if($sql_reqid[0]['APPSTAT'] == 'P') {
								$sect = select_query_json("select ESECODE, BRNCODE from employee_office where DESCODE != 132 and empsrno = ".$_SESSION['tcs_empsrno'], 'Centra', 'TCS');
								if($sect[0]['BRNCODE'] == 888) {
									$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office where ESECODE = ".$sect[0]['ESECODE']." and DESCODE = 132 and EMPCODE >= 1000", 'Centra', 'TCS');
								} else {
									$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office
																		where brncode = ".$sect[0]['BRNCODE']." and DESCODE in (92) and EMPCODE >= 1000
																		order by DESCODE", 'Centra', 'TCS');
								}

								if(count($sql_hod) > 0) {
									if($sql_hod[0]['EMPSRNO'] != $_SESSION['tcs_empsrno']) {
										echo $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
										$appdays += 1;
										$appuser .= $sql_hod[0]['APPHEAD']."~~";
									}
								}
							}

							$sql_app_hierarchy = select_query_json("select * from APPROVAL_MDHIERARCHY amh, employee_office emp
																			where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																				APRNUMB = '".$sql_reqid[0]['APRNUMB']."' ".$ampsrno."
																			order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
							for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) {
								if($sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 1 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 2 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 3 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 4 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 5) {
									echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' <br>';
								} else {
									echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' - '.$sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'].' [ '.$sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'].' Day(s) ] <br>';
								}
								$appdays += $sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'];
								$appuser .= $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD']."~~";
							} ?>
					<? } else { $flo = 0; $newentry = 0;
							$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																	where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		amh.APPHEAD = '".$usr_cd."' and amh.DELETED = 'N'
																	order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
							$ampsrno = "";
							if(count($sql_cur_hier) > 0) {
								$ampsrno = "and amh.AMHSRNO < '".$sql_cur_hier[0]['AMHSRNO']."'"; // After HOD Level Approval..
							} else {
								if($sql_reqid[0]['APPFRWD'] == 'Q' or $sql_reqid[0]['APPFRWD'] == 'I') {
									$ex_bydes = explode(" - ", $sql_reqid[0]['RQBYDES']);
									$sql_cur_hier = select_query_json("select AMHSRNO from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																			where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																				amh.APPHEAD = '".$ex_bydes[0]."'
																			order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
									$ampsrno = "and amh.AMHSRNO < '".($sql_cur_hier[0]['AMHSRNO'] + 1)."'"; // From Query Response Level Approval..
								} else {
									$ampsrno = "and amh.AMHSRNO > 0"; // HOD Level Approval
								}
							}

							if($sql_reqid[0]['APPSTAT'] == 'P') {
								$sect = select_query_json("select ESECODE, BRNCODE from employee_office where DESCODE != 132 and empsrno = ".$_SESSION['tcs_empsrno'], 'Centra', 'TCS');
								if($sect[0]['BRNCODE'] == 888) {
									$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office
																		where ESECODE = ".$sect[0]['ESECODE']." and DESCODE = 132 and EMPCODE >= 1000", 'Centra', 'TCS');
								} else {
									$sql_hod = select_query_json("select EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from employee_office
																		where brncode = ".$sect[0]['BRNCODE']." and DESCODE in (92) and EMPCODE >= 1000
																		order by DESCODE", 'Centra', 'TCS');
								}

								if(count($sql_hod) > 0) {
									if($sql_hod[0]['EMPSRNO'] != $_SESSION['tcs_empsrno']) {
										echo $sql_hod[0]['EMPNAME']." - ".$sql_hod[0]['APPHEAD']." [ 1 Day(s) ] <br>";
										$appdays += 1;
										$appuser .= $sql_hod[0]['APPHEAD']."~~";
									}
								}
							}

							$sql_app_hierarchy = select_query_json("select * from APPROVAL_MODE_HIERARCHY amh, employee_office emp
																		where amh.APPHEAD = emp.empcode and amh.APMCODE = '".$sql_reqid[0]['APMCODE']."' ".$ampsrno."
																			and amh.DELETED = 'N'
																		order by amh.APMCODE, amh.AMHSRNO desc", 'Centra', 'TEST');
							for($app_hierarchy_i = 0; $app_hierarchy_i < count($sql_app_hierarchy); $app_hierarchy_i++) {
								if($sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 1 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 2 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 3 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 4 or $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'] == 5) {
									echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' <br>';
								} else {
									echo $sql_app_hierarchy[$app_hierarchy_i]['EMPNAME'].' - '.$sql_app_hierarchy[$app_hierarchy_i]['APPHEAD'].' [ '.$sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'].' Day(s) ] <br>';
								}
								$appdays += $sql_app_hierarchy[$app_hierarchy_i]['APPDAYS'];
								$appuser .= $sql_app_hierarchy[$app_hierarchy_i]['APPHEAD']."~~";
							} ?>
					<? 	}
				} ?>
				<div class='clear clear_both'></div>
			</div>
			<div class='clear clear_both'></div>
		</div>
		<input type="hidden" name="hid_noofdays" id="hid_noofdays" value="<?=$appdays?>">
		<input type="hidden" name="hid_appuser" id="hid_appuser" value="<?=$appuser?>">
		<? if($flo == 1) { ?>
			<input type="hidden" name="hid_newentry" id="hid_newentry" value="<?=$newentry?>">
			<input type="hidden" name="hid_apmcd" id="hid_apmcd" value="<?=$sql_reqid[0]['APMCODE']?>">
		<? } ?>

		<input type='hidden' name='txt_approval_number' id='txt_approval_number' value='<?=$sql_reqid[0]['APRNUMB']?>'>
		<input type='hidden' name='slt_branch' id='slt_branch' value='<?=$sql_reqid[0]['BRNCODE']?>'>
		<input type='hidden' name='txt_requestby' id='txt_requestby' value='<?=$sql_reqid[0]['REQSTBY']?>'>
		<input type='hidden' name='txt_requestfr' id='txt_requestfr' value='<?=$sql_reqid[0]['REQSTFR']?>'>
		<input type='hidden' name='txt_requestfrdes' id='txt_requestfrdes' value='<?=$sql_reqid[0]['RQFRDES']?>'>
		<input type='hidden' name='txt_tmporlive' id='txt_tmporlive' value='<?=$tmporlive?>'>
		<input type='hidden' name='txt_extarno' id='txt_extarno' value='<?=$sql_reqid[0]['TARNUMB']?>'>
		<input type='hidden' name='hidslt_core_department' id='hidslt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
		<input type='hidden' name='slt_aprno' id='slt_aprno' value='<?=$sql_reqid[0]['IMUSRIP']?>'>
		<input type='hidden' name='slt_dynamic_subject' id='slt_dynamic_subject' value='<?=$sql_reqid[0]['DYNSUBJ']?>'>
		<input type='hidden' name='txt_dynsubject' id='txt_dynsubject' value='<?=$sql_reqid[0]['TXTSUBJ']?>'>

		<input type='hidden' name='txt_submission_quotations_remarks' id='txt_submission_quotations_remarks' value='<?=$sql_reqid[0]['RMQUOTS']?>'>
		<input type='hidden' name='txt_submission_fieldimpl_remarks' id='txt_submission_fieldimpl_remarks' value='<?=$sql_reqid[0]['RMBDAPR']?>'>
		<input type='hidden' name='txt_submission_clrphoto_remarks' id='txt_submission_clrphoto_remarks' value='<?=$sql_reqid[0]['RMCLRPT']?>'>
		<input type='hidden' name='txt_submission_artwork_remarks' id='txt_submission_artwork_remarks' value='<?=$sql_reqid[0]['RMARTWK']?>'>
		<input type='hidden' name='txt_submission_othersupdocs_remarks' id='txt_submission_othersupdocs_remarks' value='<?=$sql_reqid[0]['RMCONAR']?>'>
		<input type='hidden' name='txt_warranty_guarantee' id='txt_warranty_guarantee' value='<?=$sql_reqid[0]['WARQUAR']?>'>
		<input type='hidden' name='txt_cur_clos_stock' id='txt_cur_clos_stock' value='<?=$sql_reqid[0]['CRCLSTK']?>'>
		<input type='hidden' name='txt_advpay_comperc' id='txt_advpay_comperc' value='<?=$sql_reqid[0]['PAYPERC']?>'>
		<input type='hidden' name='txt_workfin_targetdt' id='txt_workfin_targetdt' value='<?=$sql_reqid[0]['FNTARDT']?>'>
		<input type='hidden' name='txt_agreement_expiry' id='txt_agreement_expiry' value='<?=strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['AGEXPDT'])))?>'>
		<input type='hidden' name='txt_agreement_advance' id='txt_agreement_advance' value='<?=$sql_reqid[0]['AGADVAM']?>'>

		<input type='hidden' name='slt_approval_listings' id='slt_approval_listings' value='<?=$sql_reqid[0]['APMCODE']?>'>
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
		<input type='hidden' name='txtrequest_value' id='txtrequest_value' value='<?=$sql_approve_leads[0]['APPFVAL']?>'>
		<input type='hidden' name='txtdetails' id='txtdetails' maxlength='400' value='<? echo $sql_reqid[0]['APPRDET']; ?>'>
		<input type="hidden" name='slt_project' id='slt_project' value="<?=$sql_approve_leads[0]['APRCODE']?>">

		<input type='hidden' name='txt_related_approvals' id='txt_related_approvals' value='<? echo $sql_reqid[0]['RELAPPR'];  ?>'>
		<input type='hidden' name='txt_against_approval' id='txt_against_approval' value='<? echo $sql_reqid[0]['AGNSAPR']; ?>'>
		<input type='hidden' name='txtfrom_date' id='txtfrom_date' value='<?=$sql_reqid[0]['APPRSFR_TIME']?>'>
		<input type='hidden' name='txtto_date' id='txtto_date' value='<?=$sql_reqid[0]['APPRSTO_TIME']?>'>
		<input type='hidden' name='slt_convertmode' id='slt_convertmode' value='<?=$sql_reqid[0]['CNVRMOD']?>'>
		<input type='hidden' name='slt_apptype' id='slt_apptype' value='<?=$sql_reqid[0]['APPTYPE']?>'>
		<input type='hidden' name='txt_adv_amount' id='txt_adv_amount' value='<?=$sql_reqid[0]['ADVAMNT']?>'>
		<input type='hidden' name='txt_submission_respuser' id='txt_submission_respuser' value='<?=$sql_reqid[0]['RESPUSR']?>'>
		<input type='hidden' name='txt_alternate_user' id='txt_alternate_user' value='<?=$sql_reqid[0]['ALTRUSR']?>'>

		<? 	$alt_user_approval = 1;
			if($sql_requsr[0]['REQSTFR'] == $_SESSION['tcs_empsrno']) {
				$alt_user_approval = 0;
			} ?>
		<input type='hidden' name='alt_user_approval' id='alt_user_approval' value='<?=$alt_user_approval?>'>
		<input type='hidden' name='alt_user_approval' id='alt_user_approval' value='<?=$alt_user_approval?>'>

		<? /* <input type="hidden" tabindex='26' name="slt_priority" id="slt_priority" class="form-control" value='<? if($sql_approve_leads[0]['PRICODE'] == '') { echo "3"; } else { echo $sql_approve_leads[0]['PRICODE']; } ?>'> */ ?>
		<input type='hidden' name='txt_suppliercode' id='txt_suppliercode' value='<? if($sql_tarbalance[0]['SUPCODE'] != '') { echo $sql_tarbalance[0]['SUPCODE']." - "; } echo $sql_tarbalance[0]['SUPNAME']; ?>'>
		<input type='hidden' name='txt_supplier_contactno' id='txt_supplier_contactno' value='<? echo $sql_tarbalance[0]['SUPCONT']; ?>'>
		<input type='hidden' name='slt_budgetmode' id='slt_budgetmode' value='<?=$sql_tarbalance[0]['BUDCODE']?>'>
		<input type="hidden" tabindex='24' name="impldue_date" id="impldue_date" class="form-control" value='<?=strtoupper(date("d-M-Y", strtotime($sql_tarbalance[0]['IMDUEDT'])))?>' style='text-transform:uppercase;'>
		<input type="hidden" name='slt_project_type' id='slt_project_type' value="<?=$sql_tarbalance[0]['PRJPRCS']?>">
		<input type='hidden' name='txtdue_date' id='txtdue_date' value='<?=$sql_tarbalance[0]['APRDUED_TIME']?>'>
		<input type='hidden' name='txtdue_date_01' id='txtdue_date_01' value='<?=$sql_reqid[0]['APRDUED']?>'>
		<input type='hidden' name='txtnoofdays' id='txtnoofdays' value='<?=$sql_tarbalance[0]['APRDAYS']?>'>
		<input type='hidden' name='txtnoofhours' id='txtnoofhours' value='<?=$sql_tarbalance[0]['APRHURS']?>'>
		<input type='hidden' name='slt_core_department' id='slt_core_department' value='<?=$sql_tarbalance[0]['EXPSRNO']?>'>
		<input type='hidden' name='slt_department_asset' id='slt_department_asset' value='<?=$sql_tarbalance[0]['DEPCODE']?>'>
		<input type="hidden" name='slt_targetno' id='slt_targetno' value="<?=$sql_tarbalance[0]['TARNUMB']?>">
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
				$sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '".$sql_reqid[0]['EXPSRNO']."'", 'Centra', 'TCS');
			} else {
				$sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '13'", 'Centra', 'TCS');
			}
		?>
		<input type='hidden' name='txt_rptmode' id='txt_rptmode' value='<?=$sql_rptmode[0]['RPTMODE']?>'>
		<input type="hidden" name='clrphoto' id='clrphoto' value='clrphoto'>
		<input type="hidden" name='smplpti' id='smplpti' value='<?=$sql_reqid[0]['SMPLPTI']?>'>
		<? /* <input type='hidden' class="form-control" name='slt_targetno' id='slt_targetno' value='<?=$sql_reqid[0]['TARNUMB']?>' data-toggle="tooltip" data-placement="top" title="Request Value"> */ ?>
		<input type='hidden' class="form-control" name='slt_tarbaln' id='slt_tarbaln' value='<?=$sql_reqid[0]['TARBALN']?>' data-toggle="tooltip" data-placement="top" title="Request Value">
		<input type='hidden' class="form-control" name='slt_tardesc' id='slt_tardesc' value='<?=$sql_reqid[0]['TARDESC']?>' data-toggle="tooltip" data-placement="top" title="Request Value">
		<div class='clear clear_both'></div>
	</div>
	<div class='clear clear_both'></div>

		<tr><td colspan="4" style='margin-top:5px; width:100%; text-align:center;'>
			<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:0px;'>
				<?if($_SESSION['tcs_empsrno'] == '20118' or $_SESSION['tcs_empsrno'] == '43400' or $_SESSION['tcs_empsrno'] == '21344'){?>
				<a target="_blank" href='waiting_approvals.php?action=view&reqid=<? echo $_REQUEST['reqid']; ?>&year=<? echo $_REQUEST['year']; ?>&rsrid=1&creid=<? echo $_REQUEST['creid']; ?>&typeid=<? echo $_REQUEST['typeid']; ?>' title='View' alt='View' class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
				<?}?>
				<a target="_blank" href="view_waiting_approval_live.php?action=edit&reqid=<?=$reqid?>&year=<?=$year?>&rsrid=<?=$rsrid?>&creid=<?=$creid?>&typeid=<?=$typeid?>" name='sbmt_query' id='sbmt_query' tabindex='30' value='Edit' class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;<a href="javascript:void(0)" name='comments_history_btn' id='comments_history_btn' tabindex='30' value='View Comments History' class="btn btn-success" data-toggle="tooltip" data-placement="top" title="View Comments History"><i class="fa fa-comment"></i></a>&nbsp;<a href="javascript:void(0)" name='cmntmail' id='cmntmail' tabindex='30'  onclick="cmnt_mail('<?=$sql_reqid[0]['APRNUMB']?>');" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Mail"><i class="fa fa-envelope"></i></a><a style="margin-left: 5px; background: #b2229a;" href="index.php" target="_blank" class="btn btn-success"><i class="fa fa-home fa-lg"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?  if(count($sql_reqid) > 0) {
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
														order by APPORDER Asc, ar.APRQVAL Desc", 'Centra', 'TEST');
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
														order by APPORDER Asc, ar.APRQVAL Desc", 'Centra', 'TEST');
				}

				$next_url = "http://$_SERVER[HTTP_HOST]/approval-desk/".$rturl;
				$previous_url = "http://$_SERVER[HTTP_HOST]/approval-desk/".$rturl;
				for($searchi = 0; $searchi < count($sql_search); $searchi++) {
					if($sql_search[$searchi]['APRNUMB'] == $sql_reqid[0]['APRNUMB'] and $sql_search[$searchi]['APPORDER'] == 1)
					{
						$searchii = $searchi + 1;
						if($sql_search[$searchii]['APRNUMB'] != '' and $sql_search[$searchii]['APPORDER'] == 1)
							$next_url = 'waiting_approvals.php?action=view&urlstatus=reports&reqid='.$sql_search[$searchii]['ARQCODE'].'&year='.$sql_search[$searchii]['ARQYEAR'].'&rsrid='.$sql_search[$searchii]['ARQSRNO'].'&creid='.$sql_search[$searchii]['ATCCODE'].'&typeid='.$sql_search[$searchii]['ATYCODE'];

						$searchiii = $searchi - 1;
						if($sql_search[$searchiii]['APRNUMB'] != '' and $sql_search[$searchiii]['APPORDER'] == 1)
							$previous_url = 'waiting_approvals.php?action=view&urlstatus=reports&reqid='.$sql_search[$searchiii]['ARQCODE'].'&year='.$sql_search[$searchiii]['ARQYEAR'].'&rsrid='.$sql_search[$searchiii]['ARQSRNO'].'&creid='.$sql_search[$searchiii]['ATCCODE'].'&typeid='.$sql_search[$searchiii]['ATYCODE'];
					}
				}
				$intverify = $sql_reqid[0]['APPFRWD'];
				?>

				<input type="hidden" name="urlstatus" id="urlstatus" value='<?=$urlstatus?>' />
				<input type="hidden" name="previous_urlpath" id="previous_urlpath" value='<?=$previous_url?>' />
				<input type="hidden" name="next_urlpath" id="next_urlpath" value='<?=$next_url?>' />
				<input type="hidden" name="hid_action" id="hid_action" />
				<input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
				<input type='hidden' name='hid_arqpcod' id='hid_arqpcod' value='<?=$sql_reqid[0]['ARQPCOD']?>'>
				<input type='hidden' name='hid_wrkinusr' id='hid_wrkinusr' value='<?=$sql_reqid[0]['WRKINUSR']?>'>
				<input type='hidden' name='slt_fixbudget_planner' id='slt_fixbudget_planner' value='<?=$sql_reqid[0]['BDPLANR']?>'>
				<input type='hidden' name='hid_year' id='hid_year' value='<?=$_REQUEST['year']?>'>
				<input type='hidden' name='hid_typeid' id='hid_typeid' value='<?=$_REQUEST['typeid']?>'>
				<input type='hidden' name='hid_creid' id='hid_creid' value='<?=$_REQUEST['creid']?>'>
				<input type='hidden' name='hid_rsrid' id='hid_rsrid' value='<?=$arsrno?>'>
				<input type='hidden' name='hid_original_rsrid' id='hid_original_rsrid' value='<?=$rsrid?>'>
				<? if($arsrno == $rsrid) { $samearqsrno = 1; } else { $samearqsrno = 0; } ?>
				<input type='hidden' name='hid_samearqsrno' id='hid_samearqsrno' value='<?=$samearqsrno?>'>
				<input type='hidden' name='hid_int_verification' id='hid_int_verification' value='<?=$intverify?>'>

				<? 	$open = 1;
				if($open == 1 and $mdaction == 'md') { // 20072016
				// if($open == 1) {
					/* Approval Desk Peoples Forward to S-Team / Legal Team */
				   if($_SESSION['tcs_user'] == $appdesk) { ?>
					<input type='radio' name='steam_legal' id='steam_legal' checked value='2'>&nbsp;Next Level User&nbsp;&nbsp;<input type='radio' name='steam_legal' id='steam_legal' value='1'>&nbsp;Cost Control
				<? }
				   /* Approval Desk Peoples Forward to S-Team / Legal Team */ ?>


				<? if($_REQUEST['action'] == 'view') {

					if(($sql_reqid[0]['REQSTFR'] == $sql_reqid[0]['RQESTTO']) or ($sql_reqid[0]['INTPEMP'] == $sql_reqid[0]['RQESTTO']))
					{
						$final_approval = 1;
					}

					/* if($_SESSION['tcs_empsrno'] == 43400 or $_SESSION['tcs_empsrno'] == 20118)
					{
						if($_SESSION['tcs_empsrno'] == 20118 and $final_approval == 0) {
							$finish_here = 1;
						} elseif($_SESSION['tcs_empsrno'] == 43400) {
							$finish_here = 1;
						} else {
							$finish_here = 0;
						}
					} */

					if(($sql_reqid[0]['APPFRWD'] != 'Q' and $sql_reqid[0]['APPFRWD'] != 'P' and $sql_reqid[0]['APPFRWD'] != 'I') or $_SESSION['tcs_usrcode'] == '1558888' or $_SESSION['tcs_usrcode'] == '1228001') { //  or $_SESSION['tcs_usrcode'] == '1657888' or $_SESSION['tcs_usrcode'] == '1367002'
						if($sql_reqid[0]['APPSTAT'] != 'Z'){ ?>
							<button type="submit" name='sbmt_reject' id='sbmt_reject' tabindex='28' value='Reject' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Reject"><i class="fa fa-times"></i> Reject</button>&nbsp;
							<? if($steam == $_SESSION['tcs_user'] or $legalteam == $_SESSION['tcs_user']) { ?>
								<button type="submit" name='sbmt_query' id='sbmt_query' tabindex='28' value='Query' class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Query"><i class="fa fa-question-circle"></i> Query</button>
							<? } ?>

					<button type="submit" name='sbmt_pending' id='sbmt_pending' tabindex='28' value='Pending' class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Pending"><i class="fa fa-eye-slash"></i> Pending</button>&nbsp;

					<? $intverif = 0;
					//if($_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 92 or $_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 169 or $_SESSION['tcs_descode'] == 132 or $_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_empsrno'] == 61579 or $_SESSION['tcs_empsrno'] == 59006 or $_SESSION['tcs_empsrno'] == 188 or $_SESSION['tcs_empsrno'] == 62762 or $_SESSION['tcs_empsrno'] == 1746 or $_SESSION['tcs_empsrno'] == 34593) {
						$intverif++; // HOD / BM / DGM / GM / SR.GM / MD Verification.. // HW Saravana Kumar ?>
						<button type="submit" name='sbmt_verification' id='sbmt_verification' tabindex='28' value='Internal Verification' class="btn btn-default" disabled="disabled" data-toggle="tooltip" data-placement="top" title="Internal Verification"><i class="fa fa-check-circle-o"></i> Internal Verification</button>&nbsp;
					<? // }
					}


					/* $isstage = 0;
						if($final_approval == 1 and $mdfin == 0) { $isstage = 1; // Final Approval.. ?>
							<button type="submit" name='sbmt_approve' id='sbmt_approve' tabindex='28' value='Final Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Final Approve"><i class="fa fa-check-square-o"></i> Final Approve</button>&nbsp;
						<? } // Final Approval.. ?>

					<? //$isstage = 0;
					if($final_approval == 0) { $isstage = 1; // Not Final Approval..?>
							<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve"><i class="fa fa-fast-forward"></i> Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve</button>&nbsp;
						<? } // Not Final Approval.. ?>
					<? } else { $isstage = 1; ?>
					<button type="submit" name='sbmt_response' id='sbmt_response' tabindex='28' value='Response' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Response"><i class="fa fa-fast-forward"></i> Response</button>
					<? if($intverif == 0 and $_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 92 or $_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 169 or $_SESSION['tcs_descode'] == 132 or $_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_empsrno'] == 61579 or $_SESSION['tcs_empsrno'] == 59006 or $_SESSION['tcs_empsrno'] == 188 or $_SESSION['tcs_empsrno'] == 1746 or $_SESSION['tcs_empsrno'] == 62762 or $_SESSION['tcs_empsrno'] == 34593) { // HOD / BM / DGM / GM / SR.GM / MD Verification.. // HW Saravana Kumar ?>
						<button type="submit" name='sbmt_verification' id='sbmt_verification' tabindex='28' value='Internal Verification' class="btn btn-default" disabled="disabled" data-toggle="tooltip" data-placement="top" title="Internal Verification"><i class="fa fa-check-circle-o"></i> Internal Verification</button>&nbsp;
					<? } } */


				$isstage = 0;
				// RFQ - Bid
				// echo "**".$_SESSION['tcs_empsrno']."**".$sql_reqid[0]['APPSTAT']."**".$prdcnt."**".$sql_reqid[0]['PURHEAD']."**";
				if($sql_requsr[0]['REQSTFR'] == 61579 && $sql_reqid[0]['APPSTAT'] =='N' )
				{
					if($prdcnt>0 && $sql_reqid[0]['PURHEAD'] == "") { ?>
						<button type="button" name='sbmt_bid' id='sbmt_bid' tabindex='28' class="btn" title="BID" style="background-color:#d42c65;color: white;" onclick="get_bid_dateview('<?=$sql_reqid[0]['ARQYEAR']?>','<?=$sql_reqid[0]['IMUSRIP']?>','<?=$sql_reqid[0]['ATCCODE']?>','CREATE');"> <i class="fa fa-gavel"></i> BID</button>
					 <? }

					 if($final_approval == 1 and $mdfin == 0) { // Final Approval.. ?>
						<button type="submit" name='sbmt_approve' id='sbmt_approve' tabindex='28' value='Final Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Final Approve"><i class="fa fa-check-square-o"></i> Final Approve</button>&nbsp;
					<? }

					 if($final_approval == 0) { $isstage = 1; // Not Final Approval..?>
						<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve"><i class="fa fa-fast-forward"></i> Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve</button>&nbsp;
					<? } // Not Final Approval..
				} elseif($sql_requsr[0]['REQSTFR'] == 61579 && $sql_reqid[0]['APPSTAT'] =='Z' ) {
					if($prdcnt>0) { ?>
						<button type="button" name='sbmt_bid' id='sbmt_bid' class="btn" title="REVERSE BID" tabindex='28' style="background-color:#d42c65;color: white;" onclick="get_bid_dateview('<?=$sql_reqid[0]['ARQYEAR']?>','<?=$sql_reqid[0]['IMUSRIP']?>','<?=$sql_reqid[0]['ATCCODE']?>','REVERSE');"> <i class="fa fa-gavel"></i> REVERSE BID</button>
						<div id="mybody" class="red_highlight"></div>
					<? }
				} elseif($sql_requsr[0]['REQSTFR'] != 61579) {
					if($final_approval == 1 and $mdfin == 0) { $isstage = 1; // Final Approval.. ?>
						<button type="submit" name='sbmt_approve' id='sbmt_approve' tabindex='28' value='Final Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Final Approve"><i class="fa fa-check-square-o"></i> Final Approve</button>&nbsp;
					<? }

					if($final_approval == 0) { $isstage = 1; // Not Final Approval..?>
						<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve"><i class="fa fa-fast-forward"></i> Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve</button>&nbsp;
					<? } } // Not Final Approval..
				} else {
					if($sql_reqid[0]['REQSTBY'] == $_SESSION['tcs_empsrno']) { $isstage = 1; ?>
						<button type="submit" name='sbmt_verification' id='sbmt_verification' tabindex='28' value='Internal Verification' class="btn btn-default" disabled="disabled" data-toggle="tooltip" data-placement="top" title="Internal Verification"><i class="fa fa-check-circle-o"></i> Internal Verification</button>&nbsp;
				<? } else { $isstage = 1; ?>
					<? //if($intverif == 0 and $_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 92 or $_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 165 or $_SESSION['tcs_descode'] == 169 or $_SESSION['tcs_descode'] == 132 or $_SESSION['tcs_descode'] == 9 or $_SESSION['tcs_descode'] == 78 or $_SESSION['tcs_empsrno'] == 61579 or $_SESSION['tcs_empsrno'] == 59006 or $_SESSION['tcs_empsrno'] == 188 or $_SESSION['tcs_empsrno'] == 1746 or $_SESSION['tcs_empsrno'] == 37048 or $_SESSION['tcs_empsrno'] == 62762 or $_SESSION['tcs_empsrno'] == 34593 or $_SESSION['tcs_empsrno'] == 78324) { // HOD / BM / DGM / GM / SR.GM / MD Verification.. // HW Saravana Kumar ?>
						<button type="submit" name='sbmt_verification' id='sbmt_verification' tabindex='28' value='Internal Verification' class="btn btn-default" disabled="disabled" data-toggle="tooltip" data-placement="top" title="Internal Verification"><i class="fa fa-check-circle-o"></i> Internal Verification</button>&nbsp;
					<? // } ?>
						<button type="submit" name='sbmt_response' id='sbmt_response' tabindex='28' value='Response' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Response"><i class="fa fa-fast-forward"></i> Response</button>
				<? }
				  }
				}
				// RFQ - Bid

				elseif($_REQUEST['action'] == 'edit') { ?>
					<button type="submit" name='sbmt_update' id='sbmt_update' tabindex='28' value='SB' class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-save"></i> Update</button>&nbsp;<button type="reset" tabindex='28' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><?
				} else {
					if(count($sql_reqid) == 0) { ?>
						<button type="submit" name='sbmt_request' id='sbmt_request' tabindex='28' value='submit' class="btn btn-success" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;<button type="reset" tabindex='28' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button>
					<? } else { ?>
						<a href="javascript:history.go(-1)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" tabindex='28' title="Back"><i class="fa fa-times"></i> Back</a>
				<? } } } else { ?>
						<a href="javascript:window.close();" class="btn btn-warning" data-toggle="tooltip" data-placement="top" tabindex='28' title="Back"><i class="fa fa-times"></i> Back</a>
				<? } ?>

				<? if($previous_url != 'http://www.tcsportal.com/approval-desk/'.$rturl) { ?>
					<a href="<?=$previous_url?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Previous" tabindex='28' style="text-decoration: none !important;"><i class="fa fa-angle-double-left"></i>&nbsp;</a>&nbsp;
				<? } ?>

				<? if($next_url != 'http://www.tcsportal.com/approval-desk/'.$rturl) { ?>
					<a href="<?=$next_url?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Next" tabindex='28' style="text-decoration: none !important;">&nbsp;<i class="fa fa-angle-double-right"></i></a>&nbsp;
				<? } ?>

				<?
				// APPROVAL FINISH OPTION
				$projid = array("13", "14", "15", "16", "19", "26", "27", "28", "29", "30"); // PROJECT ID (5 Airport (CBE, HYD, MUM, MDU, CHN), Tailyou, Online, ZF, Kanmani, Clean Today) - Must Finish by Mr. SK Sir
				// if(in_array($sql_reqid[0]['APRCODE'], $projid) and $sql_reqid[0]['PRJPRCS'] == 'F') {
				if(in_array($sql_reqid[0]['APRCODE'], $projid)) {
					$finish_here = 0; $final_approval = 0;
				}
				/* if($sql_reqid[0]['APRQVAL'] >= 100000) {
					$finish_here = 0; $final_approval = 0;
				} */

				// APPROVAL FINISH OPTION
				if($sql_requsr[0]['REQSTFR'] == 61579 && $sql_reqid[0]['APPSTAT'] =='N' )
				{
					if($finish_here == 1) { ?>
						<button type="submit" name='sbmt_mdapprove' id='sbmt_mdapprove' tabindex='28' value='Final Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Use this Final Approve if value is low and no need for MD Approval." style='margin-left:15px;'><i class="fa fa-check-square-o"></i> Final Approve</button>&nbsp;
					<? }
				} elseif($sql_requsr[0]['REQSTFR'] == 61579 && $sql_reqid[0]['APPSTAT'] =='Z' ) {

				} elseif($sql_requsr[0]['REQSTFR'] != 61579) {
					if($finish_here == 1) { ?>
						<button type="submit" name='sbmt_mdapprove' id='sbmt_mdapprove' tabindex='28' value='Final Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Use this Final Approve if value is low and no need for MD Approval." style='margin-left:15px;'><i class="fa fa-check-square-o"></i> Final Approve</button>&nbsp;
					<? } elseif($isstage == 0 ) { ?>
						<button type="submit" name='sbmt_forward' id='sbmt_forward' tabindex='28' value='Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve' class="btn btn-success delete_confirm" data-toggle="tooltip" data-placement="top" title="Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve"><i class="fa fa-fast-forward"></i> Stage <?=$sql_reqid[0]['ARQSRNO']?> Approve</button>&nbsp;
					<? }
				} ?>
				<div class='clear clear_both'></div>
				<? } ?>
			</div>
			<div class='clear clear_both'></div>

			<? /* <div class="modal-body" id="modal-body5" style="display: none;">
				Assign Bid Expiry Date : <input type="text" name="txt_bid_expiry_date" id="datepicker_example4" class="form-control" readonly placeholder='Assign Bid Expiry Date' <?=$rdonly;?> autocomplete='off' value='<? echo strtoupper(date("d-M-Y")); ?>' style='text-transform:uppercase; ' maxlength='11' title='Assign Bid Expiry Date'>
			</div> */ ?>
		</td></tr>
		</table>
		</div>


		<? } else { ?>
			<div id='non-printable' style="text-align: center;">
				<button type="button" name='sbmt_print' id='sbmt_print' tabindex='29' value='print' class="btn btn-default" onclick="PrintDiv('<?=$sql_reqid[0]['APRNUMB']?>', '<?=$sql_reqid[0]['APPRMRK']?>');" data-toggle="tooltip" data-placement="top" style='cursor:pointer; text-align: center;' title="Print"><i class="fa fa-print"></i> Print</button>
			</div>
		<? }

$addpage = 1;
if($appr_againstno == 1) {
	$addpage++; ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo $addpage; ?>/<?php echo $pagecount+$addpage; ?></label>
			</td>
		</tr>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				&nbsp;
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
			</td>
		</tr>
		<tr>
			<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
				<iframe src="print_request.php?action=print&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&agnpr=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>" frameborder="0" height="800" width="100%"></iframe>
			</td>
		</tr>
	</table>
</div>
<? }

for($ij = 0; $ij < count($pagearry['img']); $ij++) { ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label style="font-weight:bold;">Page on : <?php echo $ij+$addpage+1; ?>/<?php echo $pagecount+$addpage; ?></label>
		</td>
	</tr>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			&nbsp;
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
		</td>
	</tr>
	<tr>
		<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
		<?
			$filename = $sql_docs[$pagearry['img'][$ij]]['APRDOCS'];
			$dataurl = $sql_docs[$pagearry['img'][$ij]]['APRHEAD'];

			$folder_path = "approval_desk/request_entry/".$dataurl."/";
			$thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";
			echo $fieldindi = "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:0px; margin-top: 0px;'><li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:0px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100%; height:100%;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
			?>
		</td>
	</tr>
</table>
</div>
<? }


if(count($pagearry['doc'])>0){ ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label style="font-weight:bold;">Page on : <?php echo count($pagearry['img'])+$addpage+1; ?>/<?php echo $pagecount+$addpage; ?></label>
		</td>
	</tr>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			&nbsp;
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
		</td>
	</tr>
	<tr>
		<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
		<? 	for($ij = 0; $ij < count($pagearry['doc']); $ij++){
				$filename = $sql_docs[$pagearry['doc'][$ij]]['APRDOCS'];
				$dataurl = $sql_docs[$pagearry['doc'][$ij]]['APRHEAD'];

				echo $fieldindi = "<a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br>";
			} ?>
		</td>
	</tr>
</table>
</div>
<? } ?>
	</form>
    <!-- /#wrapper -->



	<!-- Send Email -->
	<div id="myModal1" class="modal fade">
		<div class="modal-dialog" style='width:100%'>
			<div class="modal-content">
				<div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Attachements</div>
				<div class="modal-body" id="modal-body1"></div>
			</div>
		</div>
	</div>

	<div id="myModal2" class="modal fade">
		<div class="modal-dialog" style='width:100%'>
			<div class="modal-content">
				<div class="modal-body" id="modal-body2"></div>
			</div>
		</div>
	</div>

	<div id="myModal3" class="modal fade">
		<div class="modal-dialog" style='width:100%'>
			<div class="modal-content">
				<div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Original Details</div>
				<div class="modal-body" id="modal-body3"></div>
			</div>
		</div>
	</div>

	<div id="myModal4" class="modal fade">
		<div class="modal-dialog" style='width:100%'>
			<div class="modal-content">
				<div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Add More Supplier</div>
				<div class="modal-body" id="modal-body4"></div>
			</div>
		</div>
	</div>

	<div id="myModal5" class="modal fade" style="display: none;">
		<div class="modal-dialog" style='width:100%'>
			<div class="modal-content">
				<div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Assign Bid Expiry Date</div>
				<div class="modal-body" id="modal-body5" style="text-align: center;">
					Assign Bid Expiry Date : <input type="text" name="txt_bid_expiry_date" id="datepicker_example4" class="form-control" readonly placeholder='Assign Bid Expiry Date' <?=$rdonly;?> autocomplete='off' value='<? echo strtoupper(date("d-M-Y")); ?>' style='text-transform:uppercase; padding-left: 30px;' maxlength='11' title='Assign Bid Expiry Date'>&nbsp;&nbsp;<button type="button" name='sbmt_expbid' id='sbmt_expbid' class="btn" title="BID" style="background-color:#d42c65;color: white;" onclick="get_bid_new('<?=$sql_reqid[0]['ARQYEAR']?>','<?=$sql_reqid[0]['IMUSRIP']?>','<?=$sql_reqid[0]['ATCCODE']?>','CREATE');"> <i class="fa fa-gavel"></i> BID</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Send Email -->

    <script src="js/jquery_1.9.js"></script>
    <!-- Select2 -->
	<script src="js/select2.full.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
	<script src="js/jquery-customselect.js"></script>
	<link href="css/jquery-customselect.css" rel="stylesheet" />
	<script src="js/lightgallery.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/plugins/jquery/jqueryin.min.js"></script>
    
	
	<link rel="stylesheet" href="../bootstrap/css/default.css" type="text/css">
	<link href="css/multiple-select.css" rel="stylesheet"/>
	<link href="css/jquery.multiselect.css" rel="stylesheet" type="text/css"> 
	<script src="js/jquery.multiselect.js"></script>
	<script src="js/multiple-select.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
	

	 <script src="js/jquery.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<link href="css/selectize.default.css" rel="stylesheet"/>
	<script src="js/selectize.js"></script>
	<script src="js/selectize_index.js"></script>  

	<script type="text/javascript">
	function PrintDiv(aprnumb, cnt) {
		window.print();
	}
	 $('#slt_appflow_users').multipleSelect();

	function reject_reason(iv) {
		var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		var cnt = $('input.common_style:checked').length;
		if(cnt >= 1) {
			if(aa == false) {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", true);
				$("#id_reason_reject_"+iv).css('display', 'block');
			} else {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", false);
				$("#id_reason_reject_"+iv).css('display', 'none');
			}
		} else {
			alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			$("#chk_reject_reason_"+iv).prop('checked', true);
		}
	}

	/*$('.slt_appflow_users').selectize({
		plugins: ['drag_drop'],
		persist: false,
		create: true
	}); */
   
	$(function () {
        $('select[multi].slt_appflow_users').multiselect({
            columns: 3,
            placeholder: 'Select States',
            search: true,
            searchOptions: {
                'default': 'Search States'
            },
            selectAll: true
        });
    });
	jQuery.browser = {};
	(function () {
	    jQuery.browser.msie = false;
	    jQuery.browser.version = 0;
	    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
	        jQuery.browser.msie = true;
	        jQuery.browser.version = RegExp.$1;
	    }
	})();

	$(document).ready(function() {
		$(".chosn").customselect();
		//$('#load_page').hide();
		$("#comments_history").toggle(500);
		$('.lightgallery').lightGallery();
	});

	$(document).keydown(function(e) {
		// alert(e.keyCode+"***");
	    if (e.keyCode == 27) {
	        // $("#myModal1").fadeOut(500);
			$("#myModal1").modal('hide');
			$("#myModal2").modal('hide');
			$("#myModal3").modal('hide');
			$("#myModal4").modal('hide');
	    }
	});

	$("#comments_history_btn").click(function(){
		// alert("OPEN / CLOSE");
	    $("#comments_history").toggle(500);
	});

	function cmnt_mail(aprnumb)
	{
		var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
		$.ajax({
		url:sendurl,
		success:function(data){
			$("#myModal2").modal('show');
			$('#modal-body2').html(data);
			$('#txtmailcnt').val("");
			}
		});
	}

	function save_priority(priority_code, aprnumb, arqsrno) {
		/* var slt_priority_1 = $('input[id="slt_priority_1"]').filter(':checked').val();
		var slt_priority_2 = $('input[id="slt_priority_2"]').filter(':checked').val();
		var slt_priority_3 = $('input[id="slt_priority_3"]').filter(':checked').val();
		// alert(slt_priority_1+"**"+slt_priority_2+"**"+slt_priority_3);

		// if(confirm("Sir, Are you sure you want to change this approval priority?")){ */

	        var sendurl = "ajax/ajax_employee_details.php?action=save_priority&aprnumb="+aprnumb+"&arqsrno="+arqsrno+"&priority_code="+priority_code;
			$.ajax({
				url:sendurl,
				success:function(data){
					/* if(data == 1) {
						window.location.reload();
					} */
					$('#id_priority').html(data);
				}
			});

	    /* }
	    else{
	        // $("#slt_priority_2").prop('checked', true);
	    } */
	}

	function cmt_usr()
	{
		$('#cmtusr').css("display", "block");
		$('.select2').select2();
		$('#mailusr').focus();
		//$("#mailusr").select2("open");
		$('#mailusr').select2({
        placeholder: 'Enter EC No / Name to Select an mail user',
		allowClear: true,
		dropdownAutoWidth: true,
		minimumInputLength: 3,
		maximumSelectionLength: 3,
	 	width: '50%',
		ajax: {
          url: 'ajax/ajax_general_temp.php?action=MAILUSER',
          dataType: 'json',
		  delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
	}

	function add_mail(aprnumb)
	{
		var mail = $('#mailusr').val();
		var content = $('#txtmailcnt').val();
		var sendurl = "ajax/ajax_general_temp.php?action=MAILINSERT&apprno="+aprnumb;
		$.ajax({
		url:sendurl,
		data: {
			mailusr:mail,
			content:content
		},
		success:function(data){
			$("#myModal2").modal('hide');
			}
		});
	}

	function get_bid_dateview(year,code,head,stat)
	{
		$('#load_page').show();
		$("#myModal5").css('display', 'block');
		$("#myModal5").modal('show');
		$("#modal-body5").css('display', 'block');
		$('#load_page').hide();
		// get_bid_new(year, code, head, stat);
	}

	$('#datepicker_example4').Zebra_DatePicker({
		direction: true,
		format: 'd-M-Y'
    });

	function get_bid_new(year,code,head,stat)
	{
		var bid_expiry_date = $("#datepicker_example4").val();
		// alert("**"+bid_expiry_date+"**"); exit;
		$(".loader").show();
		$.ajax({
			url:"ajax_bid_new.php?action="+stat+"&year="+year+"&code="+code+"&head="+head+"&bid_expiry_date="+bid_expiry_date,
			success:function(data)
			{
				if(stat == "CREATE")
				{
					$(".loader").fadeOut("slow");
					if(data == 1){
					    $("#modal-default").modal('show');
						$('#sbmt_bid').prop('disabled', true);
						if ($('#sbmt_approve').length)
							{ $('#sbmt_approve').prop('disabled', true); }
						else
							{ $('#sbmt_forward').prop('disabled', true); }
		      		}else if(data == 'Login'){
						document.getElementById('mybody').innerHTML="Session Finished , Login again...! Please Wait!!";
					    $("#modal-default1").modal('show');
					}else if(data == 0){
						document.getElementById('mybody').innerHTML="Bid Send Failed..! Please Wait!!";
					    $("#modal-default1").modal('show');
					}else if(data == 2){
						document.getElementById('mybody').innerHTML="No Product Quotation Found..! Please Wait!!";
					    $("#modal-default1").modal('show');
					}else if(data == "nobid"){
						document.getElementById('mybody').innerHTML="No reverse bid found..! Please Wait!!";
					    $("#modal-default1").modal('show');
					}
					window.location.reload();
				}else{
					$(".loader").fadeOut("slow");
					data = data.split("~");
					if(data[0] == "failed"){
						document.getElementById('mybody').innerHTML="Failed in reverse Bid...! Please Wait!!";
						$("#modal-default1").modal('show');
					}else if(data[0] != 0){
					    document.getElementById('mybody').innerHTML="Reverse Bid Done ...! Please Wait!!";
					    $("#modal-default1").modal('show');
						$('#sbmt_bid').prop('disabled', true);
					}else if(data[0] == 0 && data[1] != 0){
						document.getElementById('mybody').innerHTML="Tender Closed...! Please Wait!!";
					    $("#modal-default1").modal('show');
						$('#sbmt_bid').prop('disabled', true);
					}
					window.location.reload();
				}
			}
		});
	}

	function popup_attachment(arqcode, arqyear, reqid, atccode, atycode, aprnumb) {
		$('#load_page').show();
		// var sendurl = "ftp_attachment.php?arqcode="+arqcode+"&arqyear="+arqyear+"&reqid="+reqid+"&atccode="+atccode+"&atycode="+atycode;
		var sendurl = "ftp_attachment.php?aprnumb="+aprnumb;
		$.ajax({
			url:sendurl,
			success:function(data){
				$("#myModal1").modal('show');
				$('#load_page').hide();
				document.getElementById('modal-body1').innerHTML=data;
				$('#load_page').hide();
				$('.lightgallery').lightGallery();
			}
		});
	}

	function popup_original_details(arqcode, arqyear, aprnumb) {
		$('#load_page').show();
		var sendurl = "view_original_docs.php?arqcode="+arqcode+"&arqyear="+arqyear+"&aprnumb="+aprnumb;
		$.ajax({
			url:sendurl,
			success:function(data){
				$("#myModal3").modal('show');
				$('#load_page').hide();
				document.getElementById('modal-body3').innerHTML=data;
				$('#load_page').hide();
				$('.lightgallery').lightGallery();
			}
		});
	}

	function add_more_suppliers(srno, reqid, year, rsrid, creid, typeid, pbdyear, pbdcode, pbdlsno, aprnumb, prdcode, supprdcode, brncode, supcode) {
		$('#load_page').show();
		var sendurl = "add_more_suppliers.php?srno="+srno+"&prdcode="+prdcode+"&supprdcode="+supprdcode+"&year="+year+"&reqid="+reqid+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid+"&pbdyear="+pbdyear+"&pbdcode="+pbdcode+"&pbdlsno="+pbdlsno+"&aprnumb="+aprnumb+"&brncode="+brncode+"&supcode="+supcode;
		$.ajax({
			url:sendurl,
			success:function(data){
				$("#myModal4").modal('show');
				$('#load_page').hide();
				document.getElementById('modal-body4').innerHTML=data;
				$('#load_page').hide();
				$('.lightgallery').lightGallery();
			}
		});
	}

	/******************** Change Default Alert Box ***********************/
	var ALERT_BUTTON_TEXT = "OK";
	/* if(document.getElementById) {
		window.alert = function(txt) {
			var ALERT_TITLE = "GA Title";

			var tga = document.getElementById("id_ga").value;
			createCustomAlert(tga, ALERT_TITLE);
		}
	} */

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
</body>
</html>
<?
	/* Update into approval_request Table for Verify the Duplicate or Original Print
	$sql_list = select_query_json("select * from approval_request where arqsrno = 1 and aprnumb like '".$sql_reqid[0]['APRNUMB']."'", 'Centra', 'TEST');
	$tbl_approval_request = "approval_request";
	$field_approval_request = array();
	$field_approval_request['APPRMRK'] 	= $sql_list[0]['APPRMRK'].$_SESSION['tcs_user']."-".date("d-m-y")."||";
	$where_approval_request = " arqsrno = 1 and aprnumb like '".$sql_reqid[0]['APRNUMB']."' ";
	// print_r($field_approval_request);
	$update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
	/* Update into approval_request Table for Verify the Duplicate or Original Print */

ftp_close($ftp_conn); // Close FTP Connection.
}
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>
