<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include("../lib/function_connect.php");
/*
include_once('lib/config.php');
include_once('general_functions_ftp.php');
*/
$myData = json_decode($_POST['myData'], true);

$appstat = " 'N' "; $appfrwd = " and ( ar.APPFRWD = 'F' or ar.APPFRWD = 'I' ) "; $stats = $_REQUEST['status'];
if($_REQUEST['status'] == 'Forward') {
    $appstat = " 'F' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPSTAT in ( '', 'F' ) and ar.APPFRWD not in ( 'I' ) ";
} elseif($myData["id"] == '1') {
    $appstat = " 'A', 'F' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'A' or ar.APPFRWD = 'F' or ar.APPFRWD = 'S' or ar.APPFRWD = 'N' ) and ar.APPSTAT in ( 'A', 'F', 'N' ) ";
} elseif($myData["id"] == '2') {
    // $appstat = " 'N', 'P' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' or ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'P' or ar.APPFRWD = 'S' ) and ar.APPSTAT in ( 'N' ) ";
} elseif($myData["id"] == '3') {
    // $appstat = " 'R' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'R' or ar.APPFRWD = 'F' or ar.APPFRWD = 'S' ) and ar.APPSTAT in ( 'R' ) ";
}  elseif($myData["id"] == '4') {
    // $appstat = " 'I' ";
    if ($_REQUEST["search_md"] == "") {
	    $stats = "4";
	    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
    } else {
	    $stats = "4";
	    $appfrwd = " and ( ar.REQSTBY = '".$_REQUEST["search_md"]."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
    }
}


	/* $sql_descode=select_query_json("select distinct ar.APRNUMB, ar.APPSTAT, ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL,ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.RQESTTO,
												decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW')
												APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, ar.ARQSRNO, ar.APPRDET,
												ar.PRICODE, ar.APPRFOR, ar.RQTODES reqto, ar.RQFRDES pndingby, (select EMPNAME from employee_office where empsrno in (select REQSTBY
												from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and
												ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, pr.PRICODE||' - '||pr.PRINAME priority
											from APPROVAL_REQUEST ar, approval_priority pr
											where ar.PRICODE = pr.PRICODE and pr.deleted = 'N' and ar.DELETED = 'N' ".$appfrwd."
											order by APRNUMB desc", "Centra", "TEST"); */
	//".$_SESSION['tcs_empsrno']."

	$sql_descode=select_query_json("select distinct ar.APRNUMB, ar.ARQSRNO, ar.APPSTAT, to_char(ar.ADDDATE, 'dd-MM-yyyy') ADDDATE , ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.RQESTTO,
												decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW')
												APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, ar.APPRDET,
												ar.PRICODE, ar.APPRFOR, ar.RQTODES reqto, ar.RQFRDES pndingby, (select EMPNAME from employee_office where empsrno in (select REQSTBY
												from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and
												ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, pr.PRICODE||' - '||pr.PRINAME priority
                                            from APPROVAL_REQUEST ar, approval_priority pr
                                            where ar.PRICODE = pr.PRICODE and pr.deleted = 'N' and ar.DELETED = 'N' ".$appfrwd."
                                            order by ar.ARQYEAR desc, APRNUMB desc", "Centra", "TEST");



	$outp = "";
	$sno = '';
	foreach($sql_descode as $sectionrow) {
		if ($outp != "") {$outp .= ",";}
			$sno = $sno + 1;
      /*
      $limit = '100';
      $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$sectionrow['APPRSUB'];
      $handle = fopen($filename, "r");
      $contents = fread($handle, filesize($filename));
      fclose($handle);
      // echo strip_tags(str_replace("&nbsp;", " ", $contents));
*/
			$outp .= '{"sno":"'  . $sno . '",';
			$outp .= '"APRNUMB":"'  . $sectionrow['APRNUMB'] . '",';
      $outp .= '"APPSTAT":"'  . $sectionrow['APPSTAT'] . '",';
      $outp .= '"APPFRWD":"'  . $sectionrow['APPFRWD'] . '",';
			$outp .= '"ADDDATE":"'  . $sectionrow['ADDDATE'] . '",';

//      $outp .= '"APPRSUB":"'  .trim( preg_replace('/\r\n|\r|\n/', ' ', list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit, $strip = false) )). '",';

			$outp .= '"APPRSUB":"'  . $sectionrow['APPRSUB'] . '",';
			$outp .= '"APPFVAL":"'  . $sectionrow['APPFVAL'] . '",';
			$outp .= '"APRTITL":"'  . $sectionrow['APRTITL'] . '",';
			$outp .= '"ARQCODE":"'  . $sectionrow['ARQCODE'] . '",';
			$outp .= '"ATYCODE":"'  . $sectionrow['ATYCODE'] . '",';
			$outp .= '"ATCCODE":"'  . $sectionrow['ATCCODE'] . '",';
			$outp .= '"ARQYEAR":"'  . $sectionrow['ARQYEAR'] . '",';
			$outp .= '"RQESTTO":"'  . $sectionrow['RQESTTO'] . '",';
			$outp .= '"APPSTATUS":"'  . $sectionrow['APPSTATUS'] . '",';
			$outp .= '"APPORDER":"'  . $sectionrow['APPORDER'] . '",';
			$outp .= '"APSTAT":"'  . $sectionrow['APSTAT'] . '",';
			$outp .= '"REQTO":"'  . $sectionrow['REQTO'] . '",';
			$outp .= '"ARQSRNO":"'  . $sectionrow['ARQSRNO'] . '",';
			$outp .= '"APPRDET":"'  . trim(preg_replace('/\r\n|\r|\n/', ' ', $sectionrow['APPRDET'] )). '",';
			$outp .= '"PRICODE":"'  . $sectionrow['PRICODE'] . '",';
			$outp .= '"REQBY":"'  . $sectionrow['REQBY'] . '",';
			$outp .= '"PNDINGBY":"'  . $sectionrow['PNDINGBY'] . '",';
			$outp .= '"APPRFOR":"'  . $sectionrow['APPRFOR'] . '",';
			$outp .= '"PRICODE1":"'  . $sectionrow['PRICODE1'] . '",';
			$outp .= '"PRINAME":"'  . $sectionrow['PRIORITY'] . '"}';
		}
	$outp ='{"records":['.$outp.']}';
	echo($outp);
?>
