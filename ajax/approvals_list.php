<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include("../lib/function_connect.php");
	extract ($_REQUEST);
// if($aa == 'approved_approvals_list.php') {
	$appstat = " 'N' "; $appfrwd = " and ar.APPFRWD = 'F' or ar.APPFRWD = 'I' "; $stats = $_REQUEST['status'];
	if($_REQUEST['status'] == 'Forward') {
	    $appstat = " 'F' ";
	    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPSTAT in ( '', 'F' ) and ar.APPFRWD not in ( 'I' ) ";
	} elseif($_REQUEST['status'] == 'Approved') {
	    $appstat = " 'A', 'F' ";
	    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'A' or ar.APPFRWD = 'F' or ar.APPFRWD = 'N' ) and ar.APPSTAT in ( 'A', 'F', 'N' ) ";
	} elseif($_REQUEST['status'] == 'Pending') {
	    // $appstat = " 'N', 'P' ";
	    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' or ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'P' ) and ar.APPSTAT in ( 'N' ) ";
	} elseif($_REQUEST['status'] == 'Rejected') {
	    // $appstat = " 'R' ";
	    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'R' or ar.APPFRWD = 'F' ) and ar.APPSTAT in ( 'R' ) ";
	}  elseif($_REQUEST['status'] == 'IV') {
	    // $appstat = " 'I' ";
	    if ($_REQUEST["search_md"] == "") {
		    $stats = "Internal Verification";
		    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
	    }else{
		    $stats = "Internal Verification";
		    $appfrwd = " and ( ar.REQSTBY = '".$_REQUEST["search_md"]."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
	    }
	} 

	$sql_descode=select_query_json("select distinct ar.APRNUMB, ar.APPSTAT, ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.RQESTTO, 
												decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 
												'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT 
												from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and 
												ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, (select EMPNAME from employee_office 
												where empsrno = ar.RQESTTO) as reqto, ar.ARQSRNO, ar.APPRDET, ar.PRICODE, (select EMPNAME from employee_office where empsrno in (select REQSTBY 
												from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and 
												ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, (select EMPNAME from employee_office where empsrno = ar.REQSTFR) as 
	                                            pndingby, ar.APPRFOR, pr.PRICODE, pr.PRINAME 
	                                        from APPROVAL_REQUEST ar, approval_priority pr 
	                                        where pr.DELETED(+) = 'N' and pr.PRICODE(+) = ar.PRICODE and ar.DELETED = 'N' ".$appfrwd." ".$and." and rownum <= 1
	                                        order by ar.PRICODE Asc, ar.ADDDATE asc, APPORDER Asc, ar.APRNUMB desc", 'Centra', 'TCS'); 
	/*$sql_descode=select_query_json("select distinct ar.APRNUMB, pr.PRICODE, pr.PRINAME 
	                                        from APPROVAL_REQUEST ar, approval_priority pr 
	                                        where pr.DELETED(+) = 'N' and pr.PRICODE(+) = ar.PRICODE and ar.DELETED = 'N' ".$appfrwd." ".$and." and rownum <= 1
	                                        order by ar.APRNUMB, pr.PRICODE, pr.PRINAME", 'Centra', 'TCS');*/
	$outp = ""; $ij = 0;
	foreach($sql_descode as $sectionrow) { $ij++;
		if ($outp != "") {$outp .= ",";}

		if($sql_search[$search_i]['APPSTAT'] == 'A') { $appstatus = "3 - APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
        if($sql_search[$search_i]['APPSTAT'] == 'N') { $appstatus = "1 - NEW"; $editid = 1; }
        if($sql_search[$search_i]['APPSTAT'] == 'R') { $appstatus = "7 - REJECTED"; $bgclr = '#F2DEDE'; $clr = '#000000'; }
        if($sql_search[$search_i]['APPSTAT'] == 'F') { $appstatus = "2 - FORWARD"; }
        if($sql_search[$search_i]['APPSTAT'] == 'C') { $appstatus = "8 - COMPLETED"; }
        if($sql_search[$search_i]['APPSTAT'] == 'P') { $appstatus = "4 - PENDING"; $editid = 0; $bgclr = '#FAF4D1'; $clr = '#000000'; }
        if($sql_search[$search_i]['APPSTAT'] == 'S') { $appstatus = "5 - RESPONSE"; }
        if($sql_search[$search_i]['APPSTAT'] == 'Q') { $appstatus = "6 - QUERY"; }
        $filename = $sql_search[$search_i]['IMFNIMG'];

		$outp .= '{"APRNUMB":"AAA"}';
	}

	$outp ='{"records":['.$outp.']}';
	echo($outp);
// }

/* 
		$outp .= '{"APRNUMB":"'  . $sectionrow['APRNUMB'] . '",';
		$outp .= '"APPSTAT":"'  . $sectionrow['APPSTAT'] . '",';
		$outp .= '"APPSTATUS":"'  . $appstatus . '",';
		$outp .= '"FLENAME":"'  . $filename . '",';
		$outp .= '"TBL_SRNO":"'  . $ij . '",';
		$outp .= '"APPFRWD":"'  . $sectionrow['APPFRWD'] . '",';
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
		$outp .= '"APPRDET":"'  . $sectionrow['APPRDET'] . '",';
		$outp .= '"PRICODE":"'  . $sectionrow['PRICODE'] . '",';
		$outp .= '"REQBY":"'  . $sectionrow['REQBY'] . '",';
		$outp .= '"PNDINGBY":"'  . $sectionrow['PNDINGBY'] . '",';
		$outp .= '"APPRFOR":"'  . $sectionrow['APPRFOR'] . '",';
		$outp .= '"PRICODE":"'  . $sectionrow['PRICODE'] . '",';
		$outp .= '"PRINAME":"'  . $sectionrow['PRINAME'] . '"}'; */
?>