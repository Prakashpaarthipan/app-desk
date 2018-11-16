<?php
header('Access-Control-Allow-Origin:http://rfq.thechennaisilks.com:8069');
header('Access-Control-Allow-Methods: GET,PUT,POST, DELETE');
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

function approved_create_new_list($txt_approval_number, $slt_topcore) {
	//echo "Select * From APPROVAL_BRANCH_DETAIL where APRNUMB = '".$txt_approval_number."'";
	$sql_descode=select_query_json("Select * From APPROVAL_BRANCH_DETAIL where APRNUMB = '".$txt_approval_number."'", "Centra", "TEST");
	foreach($sql_descode as $sectionrow) {

		$ARQCODE = select_query_json("Select nvl(Max(ARQCODE),0)+1 maxarqcode From APPROVAL_REQUEST","Centra","TEST");

		$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
		$IMUSRIP = select_query_json("Select nvl(Max(ARQCODE),0)+1 maxarqcode, nvl(Max(ARQSRNO),1) maxarqsrno
											From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$slt_topcore, "Centra", "TEST");
		switch($slt_topcore)
		{
			case 1:
					$startwith = 1;
					break;
			case 2:
					$startwith = 2;
					break;
			case 3:
					$startwith = 3;
					break;
			case 4:
					$startwith = 4;
					break;
			default:
					$startwith = 1;
					break;
		}

		$srno = $startwith.str_pad($IMUSRIP[0]['MAXARQCODE'], 6, '0', STR_PAD_LEFT);
		$f_value = select_query_json("Select IMUSRIP From APPROVAL_REQUEST where APRNUMB = '".$txt_approval_number."'","Centra","TEST");
		$vall = explode($f_value[0]['IMUSRIP'], $txt_approval_number);
		$valll = explode(substr($f_value[0]['IMUSRIP'],-4),$vall[1]);
		$apprno = "".$vall[0]." ".$srno." ".$valll[0]." ".substr($srno,-4)." ".$valll[1]."";

		//  INSERT INTO APPROVAL_REQUEST Table
		$ivqry = delete_query_json("INSERT INTO APPROVAL_REQUEST select '".$ARQCODE[0]['MAXARQCODE']."', ARQYEAR, ARQSRNO, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR, REQSTTO, APPRSUB, APPRDET, APPRSFR, APPRSTO, APPATTN, '".$sectionrow['APRAMNT']."', '".$sectionrow['APRAMNT']."', '".$sectionrow['APRAMNT']."', '".$sectionrow['BRNCODE']."', DEPCODE, TARNUMB, TARBALN, TARDESC, REQSTBY, RQBYDES, REQDESC,
		REQESEC, REQDESN, REQESEN, REQSTFR, RQFRDES, RQFRDSC, RQFRESC, RQFRDSN, RQFRESN, RQESTTO, RQTODES, RQTODSC, RQTOESC, RQTODSN, RQTOESN, '".$vall[0]." ".$srno." ".$valll[0]." ".substr($srno,-4)." ".$valll[1]."', APPSTAT, APPFRWD, APPINTP, INTPEMP, INTPDES, INTPDSC, INTPESC, INTPDSN, INTPESN, INTPAPR, INTSUGG, INTPFRD, INTPTOD, ADDUSER, ADDDATE, EDTUSER, EDTDATE,
		DELETED, DELUSER, DELDATE, APRCODE, APRHURS, APRDAYS, APRDUED, APPRMRK, APRTITL, FINSTAT, FINUSER, FINCMNT, FINDATE, TARVLCY, TARVLLY, EXPNAME, TARPRCY, TARPRLY, USRSYIP, PRJPRCS, PLANVAL, IMDUEDT, IMUSRCD, IMSTATS, IMFINDT, '".$srno."', TYPMODE, SUBCORE, BUDTYPE, BUDCODE, IMFNIMG, NXLVLUS, PRICODE,
		SUPCODE, SUPNAME, SUPCONT, PRODWIS, RESPUSR, ALTRUSR, RELAPPR, ORGRECV, ORGRVUS, ORGRVDT, ORGRVDC, CNVRMOD, PURHEAD, APPTYPE, ADVAMNT, WRKINUSR, ARQPCOD, BDPLANR, DYNSUBJ, TXTSUBJ, RMQUOTS, RMBDAPR, RMCLRPT, RMARTWK, RMCONAR, WARQUAR, CRCLSTK, PAYPERC, FNTARDT,  RPTUSER, ACKUSER, ACKSTAT,
		ACKDATE, AGNSAPR, AGEXPDT, AGADVAM from APPROVAL_REQUEST where APRNUMB = '".$txt_approval_number."'","Centra", "TEST");

		$budget_planner_temp = select_query_json("Select nvl(Max(APRSRNO),0)+1 MXAPRSRNO From approval_budget_planner_temp WHERE APRNUMB = '".$apprno."' ", "Centra", 'TEST');
		$budget_val = select_query_json("Select RESVALU, EXTVALU From approval_budget_planner_temp WHERE APRNUMB = '".$txt_approval_number."' ", "Centra", 'TEST');
		if($budget_val[0]['RESVALU'] == '0'){
			$RESVALU = 0;
		}else {
			$RESVALU = $sectionrow['APRAMNT'];
		}
		if($budget_val[0]['EXTVALU'] == '0'){
			$EXTVALU = 0;
		}else {
			$EXTVALU = $sectionrow['APRAMNT'];
		}

		$ivqry = delete_query_json("INSERT INTO approval_budget_planner_temp select '".$apprno."' , '".$budget_planner_temp[0]['MXAPRSRNO']."', APRPRID, APRMNTH, '".$sectionrow['APRAMNT']."', APPMNTH, APPYEAR, TARNUMB, '".$RESVALU."', '".$EXTVALU."', BUDMODE, APRYEAR,
			ADDUSER, ADDDATE, EDTUSER, EDTDATE, DELETED, DELUSER, DELDATE, '".$sectionrow['BRNCODE']."', APPMODE, EXPSRNO, EXISTVL, USEDVAL, ACCVRFY, TMTARNO, ATYCODE, PHDCODE, PGRCODE, ESECODE, DEPCODE
			from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."'","Centra", "TEST");


			$ivqry = delete_query_json("INSERT INTO approval_budget_planner select '".$apprno."' , '".$budget_planner_temp[0]['MXAPRSRNO']."', APRPRID, APRMNTH, '".$sectionrow['APRAMNT']."', APPMNTH, APPYEAR, TARNUMB, '".$RESVALU."', '".$EXTVALU."', BUDMODE, APRYEAR,
			ADDUSER, ADDDATE, EDTUSER, EDTDATE, DELETED, DELUSER, DELDATE, '".$sectionrow['BRNCODE']."', APPMODE, EXPSRNO, EXISTVL, USEDVAL, DEPCODE
			from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."'","Centra", "TEST");
/*
		$approval_budget_planner = select_query_json("Select * From approval_budget_planner WHERE APRNUMB = '".$txt_approval_number."' ", "Centra", 'TEST');
		if(!$approval_budget_planner){
			/*$ivqry = delete_query_json("INSERT INTO approval_budget_planner select '".$apprno." ".$valll[1]."' , '".$budget_planner_temp[0]['MXAPRSRNO']."', APRPRID, APRMNTH, '".$sectionrow['APRAMNT']."', APPMNTH, APPYEAR, TARNUMB, '".$RESVALU."', '".$EXTVALU."', B, APRYEAR,
				ADDUSER, ADDDATE, EDTUSER, EDTDATE, D, DELUSER, DELDATE, '".$sectionrow['BRNCODE']."', A, EXPSRNO, EXISTVL, USEDVAL, DEPCODE
				from approval_budget_planner_temp where APRNUMB = '".$txt_approval_number."'","Centra", "TEST");
				*/
				/*
		}else {
			echo "false";
		}
*/

		$amrsrno = 0;
		$sql_descode_approval_mdhierarchy=select_query_json("Select * From approval_mdhierarchy where APRNUMB = '".$txt_approval_number."'","Centra","TEST");
		foreach($sql_descode_approval_mdhierarchy as $sectionrow) {
			$amrsrno++;
			$ivqry = delete_query_json("INSERT INTO approval_mdhierarchy select APMCODE, '".$amrsrno."', APPHEAD, APPDESG, APPDAYS, A, APPTITL, VRFYREQ, '".$apprno."', PBDAPPR
				from approval_mdhierarchy where APRNUMB = '".$apprno."'","Centra", "TEST");
		}
	}
}

$txt_approval_number = "S-TEAM / HR DEPT 1000017 / 09-05-2018 / 0017 / 11:40 AM";
$slt_topcore = "1";
approved_create_new_list($txt_approval_number, $slt_topcore);
?>
