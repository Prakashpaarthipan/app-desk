<?
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect_1.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if($_REQUEST['action'] == "edit"){ ?>
    <script>window.location="home.php";</script>
<?php exit();
}

if($_REQUEST['rsrid'] == '') {
    $rqsrno = 1;
} else {
    $rqsrno = $_REQUEST['rsrid'];
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
                                        to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, 
                                        (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME   
                                    from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast, 
                                        employee_office emp
                                    where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and 
                                        brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K','T') and req.BRNCODE = brn.BRNCODE and 
                                        req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and 
                                        req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' 
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
                                            to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, 
                                            (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME  
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast, 
                                            employee_office_deleted edl
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and 
                                            brn.DELETED = 'N' and ast.DELETED = 'N' and (edl.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T') and req.BRNCODE = brn.BRNCODE and 
                                            req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and 
                                            req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' 
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
                                            to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, 
                                            (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME  
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast, 
                                            employee_office emp
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.deleted = 'N' and 
                                            brn.DELETED = 'N' and ast.DELETED = 'N' and (emp.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T')and req.BRNCODE = brn.BRNCODE and 
                                            req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$rqsrno."' and 
                                            req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."' 
                                        order by arcode, arsrno, atcode", "Centra", 'TEST'); // req.ATMCODE = apm.ATMCODE and ---- for Approval Master
}

$sql_reqid_edit = select_query_json("select APPROVAL_REQUEST.REQSTBY from APPROVAL_REQUEST 
                                            where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$rqsrno."' and 
                                                ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and APPSTAT = 'N' 
                                            order by ARQCODE, ARQSRNO, ATYCODE", "Centra", 'TEST');

$sql_reqid_edit1 = select_query_json("select ARQCODE from APPROVAL_REQUEST 
                                            where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = 2 and 
                                                ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' 
                                            order by ARQCODE, ARQSRNO, ATYCODE", "Centra", 'TEST');

if($_REQUEST['action'] == 'edit' and ( $sql_reqid_edit[0]['REQSTBY'] != $_SESSION['tcs_empsrno'] or count($sql_reqid_edit1[0][0]) > 0)) { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="pending_approvals.php";</script>
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

function budget_planner_summary($field_values,$tbl_name){
	$insert_response = insert_dbquery($field_values, $tbl_name);
	if($insert_response == 1){
		return array($field_values['BPLYEAR'],$field_values['BPLNUMB']);
	}
	return 0;
}

function budget_planner_detail($field_values,$tbl_name){
	$insert_response = insert_testquery($field_values, $tbl_name);
	if($insert_response==1){
		return array($field_values['BPLNUMB'],$field_values['BPLYEAR']);
	}
	return 0;
}

function budget_planner_content($field_values,$tbl_name){
	$insert_response = insert_testquery($field_values, $tbl_name);
	if($insert_response==1){
		return array($field_values['BPLNUMB'],$field_values['BPLSRNO']);
	}
	return 0;
}

if(isset($_POST['sbmt_request'])){
	
	foreach($_POST['txt_prdcode'] as $key=>$rowdata){
		
		$aprnumbData = select_query_json("select aprnumb,depcode,tarnumb,PURHEAD,REQSTBY,RESPUSR from approval_request where aprnumb like '%".$_POST['txt_aprnumb']."%' and arqsrno=1","Centra", 'TEST');
		//BUDGET PLANNER SUMMARY INSERT
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		$lastdate = strtoupper(date('t-M-Y h:i:s A'));
		//var_dump($_REQUEST['txt_spldisc']);
		//var_dump($_REQUEST['txt_sltsupplier'][1][0]);
		//txt_sltsupcode
		//var_dump($_REQUEST['txt_pieceless']);
		//var_dump($_REQUEST['txt_prddesc']);
		//exit;
		$field_values = array();
		$tbl_name = "BUDGET_PLANNER_SUMMARY";
		$field_values['BPLYEAR'] = $_REQUEST['year'];
		$field_values['BPLDATE'] = date('d-M-y');
			$max_bplnumb = select_query_json("SELECT Nvl(Max(BPLNUMB),0)+1  BPLNUMB FROM BUDGET_PLANNER_SUMMARY WHERE BPLYEAR='2017-18'","Centra", 'TEST');
		
		$field_values['BPLNUMB'] = $max_bplnumb[0]["BPLNUMB"];//select aprnumb,depcode,tarnumb,PURHEAD,REQSTBY,RESPUSR from approval_request where aprnumb like '%ADMIN / INFO TECH 4008108 / 08-03-2018 / 8108 / 03:37 PM%'
		$field_values['BPLMODE'] = "R";
		//$com_code = select_query_json("SELECT COMP.COMCODE,COMP.COMNAME FROM COMPANY COMP,BRANCH_COMPANY BCOM WHERE BCOM.COMCODE=COMP.COMCODE AND BCOM.BRNCODE='".$aprnumbData[0]['BRNCODE']."' AND COMP.DELETED='N' ORDER BY COMP.COMCODE","Centra", 'TEST');
		$com_code = select_query_json("SELECT COMP.COMCODE,COMP.COMNAME FROM COMPANY COMP WHERE COMP.COMCODE='1' AND COMP.DELETED='N' ORDER BY COMP.COMCODE","Centra", 'TEST');
		$field_values['COMCODE'] = $com_code[0]['COMCODE'];
		$supcodeVal = $_REQUEST['txt_sltsupcode'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
		$supcodeVal = explode(" - ",$supcodeVal);
		$field_values['SUPCODE'] = $supcodeVal[0];
		$field_values['DEPCODE'] = $aprnumbData[0]['DEPCODE'];
		$field_values['BPLGRAD'] = "1*";
		$field_values['BPLDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$date = strtotime(date('d-M-y'));
		$date = strtotime("+".$_REQUEST['txt_delivery_duration'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]." day", $date);
		//$field_values['BPLEDDT'] = date('d-M-y', $date);
		$field_values['BPLEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
		$field_values['TRNCODE'] = $aprnumbData[0]['TARNUMB'];
		$field_values['PHDCODE'] = $_REQUEST['txt_phphead'];
		$field_values['PGRCODE'] = $_REQUEST['txt_phpgroup'];
		if($_REQUEST['txt_spldisc'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]==""){
			$_REQUEST['txt_spldisc'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]=0;
		}
		if($_REQUEST['txt_pieceless'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]==""){
			$_REQUEST['txt_pieceless'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]=0;
		}
		$field_values['BPLSDISC'] = $_REQUEST['txt_spldisc'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
		$field_values['BPLPLESS'] = $_REQUEST['txt_pieceless'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
		$field_values['BPLREMA'] = $_REQUEST['txt_prdspec'][$key];
		$field_values['BPLPURP'] = $_REQUEST['txt_prdspec'][$key];
 		$field_values['REQSRNO'] = $aprnumbData[0]['REQSTBY'];
		$field_values['AUTSRNO'] = $aprnumbData[0]['REQSTBY'];
		$field_values['TRANCHR'] = "";
		$field_values['ERECCHR'] = "";
		$field_values['LOADCHR'] = "";
		$field_values['LORPAID'] = "N";
		$field_values['GRDCODE'] = 1;
		$field_values['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$field_values['ADDDATE'] =  'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
		$field_values['DELETED'] = "N";
		$field_values['APPNUMB'] = $aprnumbData[0]['APRNUMB'];
		//$bpl_sum = budget_planner_summary($field_values, $tbl_name);
		$bpl_sum = array();
		
		if(is_array($bpl_sum)){
			$bpl_sum_num['bpl'][$key] = $bpl_sum;
			//Check
			$field_values = array();
			$tbl_name = "BUDGET_PLANNER_DETAIL";
			$field_values['BPLYEAR'] = '2017-18';
			$field_values['BPLNUMB'] = $max_bplnumb[0]["BPLNUMB"];		
				$max_bplsrno = select_query_json("SELECT Nvl(Max(BPLSRNO),0)+1  BPLSRNO FROM BUDGET_PLANNER_DETAIL WHERE BPLYEAR='2017-18' AND BPLNUMB='".$max_bplnumb[0]["BPLNUMB"]."'","Centra",'TEST');
			$field_values['BPLSRNO'] = $max_bplsrno[0]["BPLSRNO"];
			$prdcodeVal = $_POST['txt_prdcode'][$key];
			$prdcodeVal = explode(" - ",$prdcodeVal);
			$field_values['PRDCODE'] = $prdcodeVal[0];
			$subcodeVal = $_POST['txt_subprdcode'][$key];
			$subcodeVal = explode(" - ",$subcodeVal);
			$field_values['SUBCODE'] = $subcodeVal[0];
			$field_values['BPLPRAT'] = $_REQUEST['txt_prdrate'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['BPLPIEC'] = $_REQUEST['txt_prdqty'][$key];
			$field_values['UNTCODE'] = $_REQUEST['txt_untcode'][$key];
			$field_values['BPLDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$date = strtotime(date('d-M-y'));
			$date = strtotime("+".$_REQUEST['txt_delivery_duration'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]." day", $date);
			//$field_values['BPLEDDT'] = date('d-M-y', $date);
			$field_values['BPLEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
			$field_values['ESECODE'] = $aprnumbData[0]['DEPCODE'];
			$field_values['BPLDESC'] =  $_REQUEST['txt_prdspec'][$key];
			$field_values['BPLDISC'] = $_REQUEST['txt_prddisc'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['BPLDVAL'] = $_REQUEST['txt_prdcgst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]+$_REQUEST['txt_prdsgst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]+$_REQUEST['txt_prdigst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['BPLTAXP'] = $_REQUEST['txt_prdcgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]+$_REQUEST['txt_prdsgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]+$_REQUEST['txt_prdigst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['BPLTAXV'] =  $_REQUEST['txt_taxval'][$key];
			$field_values['BPL_LENGTH'] =$_REQUEST['txt_size_length'][$key];
			$field_values['BPL_WIDTH'] = $_REQUEST['txt_size_width'][$key];
			$field_values['BPL_LOCATION'] = $_REQUEST['txt_print_location'][$key];
			$field_values['BPL_DURATION'] = $_REQUEST['txt_ad_duration'][$key];
			$field_values['BPLTECH1'] = "";
			if($_REQUEST['txt_prdcgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]==""){
				$_REQUEST['txt_prdcgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]=0;
			}
			if($_REQUEST['txt_prdsgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]==""){
				$_REQUEST['txt_prdsgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]=0;
			}
			if($_REQUEST['txt_prdigst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]==""){
				$_REQUEST['txt_prdigst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1]=0;
			}
			$field_values['CGSTPER'] = $_REQUEST['txt_prdcgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['SGSTPER'] = $_REQUEST['txt_prdsgst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['IGSTPER'] = $_REQUEST['txt_prdigst_per'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['CGSTAMT'] = $_REQUEST['txt_prdcgst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['SGSTAMT'] = $_REQUEST['txt_prdsgst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$field_values['IGSTAMT'] = $_REQUEST['txt_prdigst'][$key+1][$_REQUEST['txt_sltsupplier'][$key+1][0]-1];
			$hsncode_po = select_query_json("select hsncode from SUBPRODUCT_ASSET where PRDCODE='".$prdcodeVal[0]."'and SUBCODE='".$subcodeVal[0]."'","Centra", 'TEST');
			//echo "select hsncode from SUBPRODUCT_ASSET where PRDCODE='".$prdcodeVal[0]."'and SUBCODE='".$subcodeVal[0]."'";
			$field_values['HSNCODE'] = $hsncode_po[0]['HSNCODE'];
			/*echo '1231321';*/
			//var_dump($field_values);
			//exit;
			//$insert_response = insert_testquery($field_values, $tbl_name);
			/*budget_planner_detail($field_values,$tbl_name);
			
			$ptvalue_po = select_query("select PTVALUE from trandata.NON_PURCHASE_TARGET@tcscentr where PTNUMB = '".$_REQUEST['slt_targetno']."' and BRNCODE='".$_REQUEST['slt_branch']."' and DEPCODE='".$_REQUEST['slt_core_department']."' and trunc(sysdate) between trunc(PTFDATE) and trunc(PTTDATE)");
			if(!empty($ptvalue_po)){
				$ptvalue = $ptvalue_po[0]['PTVALUE']+$_REQUEST['txt_netamt'][$key];
				$tbl_name1="NON_PURCHASE_TARGET";
				$edit_value['PTVALUE'] = $ptvalue;
				$wherecon="PTNUMB = '".$_REQUEST['slt_targetno']."' and BRNCODE='".$_REQUEST['slt_branch']."' and DEPCODE='".$_REQUEST['slt_core_department']."' and trunc(sysdate) between trunc(PTFDATE) and trunc(PTTDATE)";
				$update_value = update_testquery($edit_value, $tbl_name1, $wherecon);
			}
			
			$tbl_name1="APPROVAL_PRODUCTLIST";
			$edit_value['BPLYEAR'] = $ptvalue;
			$edit_value['BPLNUMB'] = $ptvalue;
			$wherecon="PBDYEAR = '".$_REQUEST['txt_pbdyear']."' and PBDCODE='".$_REQUEST['txt_pbdcode']."' and PBDLSNO='".$_REQUEST['txt_pbdlsno']."' and PRLSTYR='".$_REQUEST['txt_prlstyr']."' and PRLSTNO='".$_REQUEST['txt_prlstno']."'";
			$update_value = update_testquery($edit_value, $tbl_name1, $wherecon);
			$field_values = array();
			$tbl_name = "BUDGET_PLANNER_CONTENT";
			$field_values['BPLYEAR'] = $_REQUEST['year'];
			$field_values['BPLNUMB'] = $max_bplnumb[0]["BPLNUMB"];		
			$field_values['BPLSRNO'] = $max_bplsrno[0]["BPLSRNO"];
			$field_values['BRNCODE'] = $_REQUEST['slt_branch'];
			//based on unit code change Qty value
			$field_values['BPLPIEC'] = $_REQUEST['txt_prdqty'][$key];
			$field_values['BPLPIEC_CUR'] = 0;
			$field_values['BPLPIEC_BAL'] = $_REQUEST['txt_prdqty'][$key];
			$field_values['BPLRECV'] = 0;
			$field_values['BPLRECV_CUR'] = 0;
			budget_planner_content($field_values,$tbl_name);*/
		}//BUDGET PLANNER ENTRY
		//$insert_budmode = insert_dbquery($field_values, $tbl_name);
		//var_dump($field_values);
		var_dump($insert_budmode);
		
		//var_dump($field_values);
		exit;
	}
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
<link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
<link href="css/jquery-customselect.css" rel="stylesheet" />
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<link href="../approval_desk/css/monthpicker.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="../bootstrap/css/jquery-ui-1.10.3.custom.min.css" />
<!-- multiple file upload -->
<link href="css/jquery.filer.css" rel="stylesheet">
<!-- EOF CSS INCLUDE -->
<style type="text/css">
    .form-horizontal .control-label { padding-top: 0px !important; }
</style>
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>

    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>
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
                <li class="active">Monthwise Request</li>
            </ul>
            <!-- END BREADCRUMB -->
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
				<?php
					$aprnumbData = select_query_json("select aprnumb,depcode,tarnumb,PURHEAD,REQSTBY,RESPUSR from approval_request where aprnumb like '%ADMIN / INFO TECH 4008108 / 08-03-2018 / 8108 / 03:37 PM%' and arqsrno=1","Centra",'TEST');
					
					?>
            
                <div class="row">
                    <div class="col-md-12">
                        
                        <form class="form-horizontal" role="form" id='frm_request_entry_1' name='frm_request_entry_1' action='' method='post' enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='request_entry_1' />
						 <input type="hidden" class="form-control" name='txt_aprnumb' id='txt_aprnumb' tabindex="1" value='<?php echo $aprnumbData[0]['APRNUMB'];  ?>' />
                        <div class="panel panel-default">
                            <div id="result"></div> <!-- Display the Process Status -->
                            <? $view = 0; if( $sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7 ) { $view = 1; } ?>

                            <div class="panel-heading">
								<div class="col-md-12">
                                <h3 class="panel-title"><strong>Approval No 	- <span class="highlight_redtitle"><?=$sql_reqid[0]['APRNUMB']?></span>
                                <input type='hidden' name='hid_aprnumb' id='hid_aprnumb' value='<?=$sql_reqid[0]['APRNUMB']?>'>
                                <input type='hidden' name='hid_appattn_cnt' id='hid_appattn_cnt' value='<?=$sql_reqid[0]['APPATTN']?>'></strong></h3>
								</div>
								<div style="clear:both"></div>
								 <div class="col-md-4">
								<h3 class="panel-title"><strong>Target No 	- <span class="highlight_redtitle"><?php      echo $sql_reqid[0]['TARNUMB']." - ".$sql_reqid[0]['TARDESC'];
														?></span>
                               </strong></h3>
							    </div>
								<div class="col-md-4">
								<?php 
									$expHead = select_query_json("select distinct expsrno,EXPNAME from department_asset where deleted='N' AND expsrno in (select distinct expsrno from approval_budget_planner_temp where APRNUMB like '".$sql_reqid[0]['APRNUMB']."')", "Centra", 'TEST');
								?>
								<h3 class="panel-title"><strong>Expense Head   - <span class="highlight_redtitle"><?php      echo $expHead[0]['EXPNAME'];
														?></span>
                               </strong></h3>
							   </div>
							  <div class="col-md-4">
								&nbsp;
							  </div>
								
                                <ul class="panel-controls">
                                    <li class="label <?=$appr_lblclass?> label-form"><?=$appr_status?></li>
                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                </ul>
                            </div>
                            <div class="panel-body">
							
							
								<div class="col-md-6">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Details </strong></h3>
											</div>
                                            <div class="panel-body">
                                                <!-- Work Initiate Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Per Head <span style="color:red">*</span></label>
                                                    <div class="col-md-9 col-xs-12">       
														<select  tabindex='3' required name='txt_phphead' id='txt_phphead' data-toggle="tooltip" data-placement="top" data-original-title="Core Department" class="form-control">
                                                        <?  $sql_project = select_query_json("SELECT purh.PHDCODE,purh.PHDNAME,purhs.nseccode FROM PURHEAD purh,pur_head_section purhs WHERE purh.phdcode=purhs.phdcode and purhs.nseccode>0 and purhs.nseccode='".$aprnumbData[0]['DEPCODE']."' and purh.DELETED='N' ORDER BY purh.PHDNAME", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                            <option value='<?=$sql_project[$project_i]['PHDCODE']?>'><?=$sql_project[$project_i]['PHDNAME']?></option>
                                                        <? } ?>
                                                        </select>
													</div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Work Initiate Person -->

                                                <!-- Responsible Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Per Group <span style="color:red">*</span></label>
                                                    <div class="col-md-9 col-xs-12">
													
														<select class="form-control"  tabindex='3' required name='txt_phpgroup' id='txt_phpgroup'>
                                                        <?  $sql_project = select_query_json("SELECT PGRCODE,PGRNAME FROM PURGROUP WHERE DELETED='N' ORDER BY PGRCODE", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                            <option value='<?=$sql_project[$project_i]['PGRCODE']?>'><?=$sql_project[$project_i]['PGRNAME']?></option>
                                                        <? } ?>
                                                        </select>
													</div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Responsible Person -->

                                              

                                                <!-- Attachments -->
                                            </div>
                                        </div>
                                
                                 
										
                                <div class="tags_clear"></div>


                        <!-- Supplier Quotation -->
                        <div id='id_supplier' style="padding-left: 20px; text-align: center;">
                        <div class="parts3 fair_border">
                        
                            <!-- Supplier Quotation -->

                             
                             
                            </div>
                            <div class="panel-footer">
                                <a href='approved_approvals.php' class='btn btn-warning pull-right'><i class="fa fa-refresh"></i> Back</a>
                            </div>
                        </div>
						
						<div class="panel-footer">
                                <button type="reset" tabindex="24" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Clear Form" style="padding: 6px 12px;"><i class="fa fa-times"></i> Clear Form</button>
                                                                    <button onclick="submitForm()"  type="submit" name="sbmt_request" id="sbmt_request" tabindex="25" value="submit" class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Submit" style="padding: 6px 12px;"><i class="fa fa-save"></i> Submit</button>
																	
									
																	
																	
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
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>                
    <!-- END PLUGINS -->
    
    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>                
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS -->       
    
    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    
    <script type="text/javascript" src="js/plugins.js"></script>        
    <script type="text/javascript" src="js/actions.js"></script>        
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <link rel="stylesheet" href="../bootstrap/css/default.css" type="text/css">
    <script src="../bootstrap/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <script type="text/javascript" src="../approval_desk/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="../approval_desk/js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <!--<script src="../approval_desk/js/form-validation.js"></script>-->
    <script type="text/javascript" src="../bootstrap/js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="../bootstrap/js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
 <script type="text/javascript">
 
	function submitForm(){
			//document.getElementById('frm_request_entry').submit();
			return true;
	}
 
 
	var slt_branch= '1';
	var slt_approval_listings= '165';
	var deptid= '125';
	var slt_submission= '1';
	var target_no='7523';
	var core_deptid='8';
	var expensehead='<?php echo $expHead[0]['EXPSRNO']; ?>';
 //?action=add_edit&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view="+view
	
	
	var strURL="ajax/ajax_dynamic_option_edit.php?action=add_edit&expensehead="+expensehead+"&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view=undefined";
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    if(data1 == 0) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Dynamic Approval Listing loading failed. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $('#load_page').hide();
                    } else {

                        // alert(data1);
                        // $.getScript("chart/js/plugin/sample_order_script.js");
                        $("#id_supplier").html(data1);
                        $('#hid_default_lock').val(0);
                        if ( $( "#default_lock" ).length ) {
                            $("#sbmt_request").prop("disabled", true);
                        }

                        var id = 1;
                        $('#fle_supquot_'+id+'_1').filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
                        $('#txt_prdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       depcode: <?php echo $sql_reqid[0]['DEPCODE'];?>,
                                       slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'product'
                                    },
                                    success: function( data ) {
                                        // alert("###"+data+"###");
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

                        $('#txt_subprdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       product: $('#txt_prdcode_'+id).val(),
                                       depcode: <?php echo $sql_reqid[0]['DEPCODE'];?>,
                                       slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'sub_product'
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

                        $('#txt_prdspec_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_targetno: <?php echo $sql_reqid[0]['TARNUMB'];?>,
                                       action: 'product_specification'
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

                        $('#txt_sltsupcode_'+id+'_1').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'supplier_withcity'
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

                        $('#txt_suppliercode').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/get_supplier_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
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

                        $('#txt_staffcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_employee_details.php',
                                    dataType: "json",
                                    data: {
                                       slt_emp: request.term,
                                       brncode: $('#slt_brnch_0').val(),
                                       action: 'allemp'
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


                        /* if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { // 
                            calculate_sum();
                            $(".ttlsumrequired").attr('required', true);
                        } else {
                            $(".ttlsumrequired").attr('required', false);
                        } */
                        
                        if(slt_submission == 7)
                        {
                            $('#ttl_lock').val(10000000000000);
                        }
                        var ttl_lock = $('#ttl_lock').val();
                        if(ttl_lock != '') {
                            if(ttl_lock == 10000000000000) {
                                $('#budgt_vlu').html('');
                            } else {
                                $('#budgt_vlu').html(' - Budget Value - '+ttl_lock);
                            }
                        }
                        $('#load_page').hide();
                    }
                }
            });
			
			
			function get_prddet()
			{
				var depcode = "<?php echo $sql_reqid[0]['DEPCODE'];?>";
				var slt_targetno = "<?php echo $sql_reqid[0]['TARNUMB'];?>";
				$.ajax({
					url:"ajax/ajax_product_details.php?action=sub_prd&depcode="+depcode+"&slt_targetno="+slt_targetno,
					success:function(data)
					{
						$("#myModal1").modal('show');
						$('#modal-body1').html(data);
					}
				});
			}
			
			function find_taxvalue(opt1, opt2) {
				$('#load_page').show();
				var txt_regdis = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
				var txt_prdrate = (document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value);
				if(txt_regdis != "" && txt_regdis != 0)
				{
				var txt_dis = parseFloat(txt_prdrate)/100*parseFloat(txt_regdis);   
				txt_prdrate = parseFloat(txt_prdrate) - parseFloat(txt_dis);
				}
				var txt_prdsgst = document.getElementById('txt_prdsgst_per_'+opt1+'_'+opt2).value;
				var txt_prdcgst = document.getElementById('txt_prdcgst_per_'+opt1+'_'+opt2).value;
				var txt_prdigst = document.getElementById('txt_prdigst_per_'+opt1+'_'+opt2).value;
				//prdcst = Math.round(prdcst).toFixed(2);
				document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdsgst / 100) * txt_prdrate),4);
				document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdcgst / 100) * txt_prdrate),4);
				document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value = roundTo(((txt_prdigst / 100) * txt_prdrate),4);
				
				// document.getElementById('txt_hidprdsgst_'+opt1+'_'+opt2).value = ((txt_prdsgst / 100) * txt_prdrate);
				// document.getElementById('txt_hidprdcgst_'+opt1+'_'+opt2).value = ((txt_prdcgst / 100) * txt_prdrate);
				// document.getElementById('txt_hidprdigst_'+opt1+'_'+opt2).value = ((txt_prdigst / 100) * txt_prdrate);
				$('#load_page').hide();
			}

			 function roundTo(n, digits) {
				if (digits === undefined) {
					digits = 0;
				}

				var multiplicator = Math.pow(10, digits);
				n = parseFloat((n * multiplicator).toFixed(11));
				return (Math.round(n) / multiplicator).toFixed(4);
			}
			function calculatenetamount(opt1, opt2){
				$('#load_page').show();
				find_taxvalue(opt1, opt2);
				var txt_prdqty = document.getElementById('txt_prdqty_'+opt1).value;
				if(txt_prdqty==''){
					txt_prdqty = 0;
				}
				if(txt_prdqty == 0) {
					document.getElementById('txt_prdqty_'+opt1).value = 1;
					calculatenetamount(opt1, opt2);
				}

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
				document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(netamounttotal*txt_prdqty); txt_prdrate
				document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(netamounttotal*txt_prdqty); */

				// New Calculation - 22-09-2017 // GA
				var ttl_lock = $("#ttl_lock").val();
				var rptmode = $("#txt_rptmode").val();
				var slt_subcore = $("#slt_subcore").val();
				var pcless = 0;
				var spldis = 0;
				var prdqty = 0;
				var prdcst = 0;
				var tot_prddisc = 0; 
				/* if(txt_prdqty == 0 || txt_prdqty == '') {
					txt_prdqty = 1;
				} */

				// console.log("!!"+txt_prdrate+"!!"+opt1+"!!"+opt2+"!!");
				if(rptmode == 1 || rptmode == 2 || rptmode == 3 || rptmode == 4) { // Non ADVT Exp.
					prdqty = txt_prdqty;
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					//pcless = txt_pieceless;
					//spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
					//prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
				} else if(rptmode == 5 || rptmode == 6) { // ADVT Exp. Ad Flex Exp.
					prdqty = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width);
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					//pcless = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) * parseFloat(txt_pieceless);
					//spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
					//prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
					if(txt_size_length != ""  && txt_size_width != "" ){
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
					}else{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
					}
				} else if(rptmode == 7) { // ADVT Exp. Ad Play Duration Exp.
					prdqty = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration);
					tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration))/100 * parseFloat(txt_prddisc) ;
					tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty); 
					//pcless = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) * parseFloat(txt_pieceless);
					//spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
					//prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
					if(txt_ad_duration != "")
					{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);  
					}else{
					prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);    
					}   
					
				}
				// console.log("@@"+prdqty+"@@"+pcless+"@@"+spldis+"@@"+prdcst+"@@");
				prdcst = Math.round(prdcst).toFixed(2);
				document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(prdcst);
				document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(prdcst);

				if($('#txt_sltsupplier_'+opt1+'_'+opt2).is(":checked")) {

					//console.log("**"+txt_prdrate+"**"+prdcst+"**");
					txt_prdsgst = roundTo(txt_prdsgst,4);
					txt_prdcgst = roundTo(txt_prdcgst,4);
					txt_prdigst = roundTo(txt_prdigst,4);
					$('#id_sltrate_'+opt1).html(txt_prdrate);
					$('#id_sltsgst_'+opt1).html(txt_prdsgst);
					$('#id_sltcgst_'+opt1).html(txt_prdcgst);
					$('#id_sltigst_'+opt1).html(txt_prdigst);
					/*$('#id_sltslds_'+opt1).html(txt_spldisc);
					$('#id_sltpcls_'+opt1).html(txt_pieceless);*/ 
					$('#id_sltdisc_'+opt1).html(txt_prddisc);
					//alert(prdcst);
					$('#id_sltamnt_'+opt1).html(prdcst);

					var requestedvalue=0;
					var y = $('.parts3 .part3').length + 1;
					for(var j=1;j<=y;j++){
						var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
						for(var i=0;i<x.length;i++){
							if(x[i].checked){
								var z = i+1;
								if(document.getElementById('hid_prdnetamount_'+j+'_'+z).value==''){
									document.getElementById('hid_prdnetamount_'+j+'_'+z).value=0;
								}
								requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value)
							}
						}   
					}

					if(parseInt(requestedvalue) <= parseInt(ttl_lock) && parseInt(ttl_lock) > 0) {
						document.getElementById('txtrequest_value').value = requestedvalue;
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						$('.hidn_balance').val(requestedvalue);
						// New Calculation - 22-09-2017 // GA 

						if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>').value = requestedvalue;
							calculate_sum();
						}

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
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						
						/*
						
						if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
							calculate_sum();
						}
						
						*/

						for(jvi = 1; jvi <= 10; jvi++) {
							// alert("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***");
							// console.log("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***"+opt1+"***"+opt2+"***"+ttlcnt+"***"+jvi+"***");
							$('#hid_prdnetamount_'+opt1+'_'+jvi).attr('class', 'form-control');
						}
						$('#hid_prdnetamount_'+opt1+'_'+opt2).attr('class', 'form-control ttlcalc');

						var requestedvalue = totcalc('ttlcalc');
						// console.log("###"+requestedvalue+"###");
						document.getElementById('txtrequest_value').value = requestedvalue;
						document.getElementById('txt_brnvalue_0').value = requestedvalue;
						document.getElementById('hidrequest_value').value = requestedvalue;
						
						
						/*if(document.getElementById('npobudget'))
						{
							document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
							calculate_sum();
						}*/
						
						$('.hidn_balance').val(requestedvalue);
						/* console.log(document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value); */
					} else {
						var ALERT_TITLE = "Message";
						var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here..";
						createCustomAlert(ALERTMSG, ALERT_TITLE);
						document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value = 0;
						calculatenetamount(opt1, opt2);
					}
				}
				$('#load_page').hide();
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
			
			// validate the product textbox
			function validate_prdempty(iv) {
				$('#load_page').show();
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
						} else if(data1 == 2) {
							var ALERT_TITLE = "Message";
							var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
							createCustomAlert(ALERTMSG, ALERT_TITLE);
							$("#txt_prdcode_"+iv).val('');
						}
					}
				});
				// fix_tax(iv);
				$('#load_page').hide();
			}
			// validate the product textbox
			
		function getrequestvalue(opt1, opt2){
			$('#load_page').show();
			calculatenetamount(opt1, opt2);

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
			document.getElementById('txt_brnvalue_0').value = requestedvalue;
			document.getElementById('hidrequest_value').value = requestedvalue;
			$('#load_page').hide();
		}	

    function getrequestvalues(iv, jv, ttlcnt){
        $('#load_page').show();
        calculatenetamount(iv, jv);

        for(jvi = 1; jvi <= ttlcnt; jvi++) {
            // alert("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***");
            // console.log("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***"+iv+"***"+jv+"***"+ttlcnt+"***"+jvi+"***");
            $('#hid_prdnetamount_'+iv+'_'+jvi).attr('class', 'form-control');
        }
        $('#hid_prdnetamount_'+iv+'_'+jv).attr('class', 'form-control ttlcalc');

        var requestedvalue = totcalc('ttlcalc');
        // console.log("###"+requestedvalue+"###");
        document.getElementById('txtrequest_value').value = requestedvalue;
        document.getElementById('txt_brnvalue_0').value = requestedvalue;
        document.getElementById('hidrequest_value').value = requestedvalue;
        $('.hidn_balance').val(requestedvalue);
        $('#load_page').hide();
    }

    // validate the product textbox
    function validate_prdempty(iv) {
        $('#load_page').show();
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
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        });
        // fix_tax(iv);
        $('#load_page').hide();
    }
    // validate the product textbox

    // validate the sub product textbox
    function validate_subprdempty(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=sub_product&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // alert("***"+data1+"***");
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Sub Product Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                    // $("#txt_subprdcode_"+iv).focus(); 
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                }
            }
        });
        find_unitcode(iv);
        // fix_tax(iv);
        $('#load_page').hide();
    }
    // validate the sub product textbox

    // find the unit code from sub product textbox
    function find_unitcode(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_unitcode&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Unit code Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else {
                    var prd = data1.split(" - ");
                    $("#txt_unitname_"+iv).val(prd[1]);
                    $("#txt_unitcode_"+iv).val(prd[0]);
                }
            }
        });
        // find_hsncode(iv);
        $('#load_page').hide();
    }
    // find the unit code from sub product textbox

    // find the HSN Code based on the chosen product & sub product based
    /* function find_hsncode(iv) {
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var strURL="ajax/ajax_validate_1.php?action=find_hsncode&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                }
            }
        });
    } */
    // find the HSN Code based on the chosen product & sub product based

    // validate the product specifiction textbox 
    function validate_prdspcempty(iv) {
        /* var spc_prdcode = $("#txt_prdspec_"+iv).val();
        var strURL="ajax/ajax_validate_1.php?action=prod_spec&validate_code="+spc_prdcode;
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
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        }); */
    }
    // validate the product specifiction textbox

    // validate the supplier textbox
    function validate_supprdempty(iv, jv) {
        $('#load_page').show();
        var slt_core_department = $("#slt_core_department").val();
        var sup_prdcode = $("#txt_sltsupcode_"+iv+"_"+jv).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_validate.php?action=supplier&validate_code="+sup_prdcode+"&slt_core_department="+slt_core_department+"&brncode="+slt_brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                var data = data1.split("~");
                if(data[0] == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_sltsupcode_"+iv+"_"+jv).val('');
                    // $("#txt_sltsupcode_"+iv+"_"+jv).focus(); 
                } 
                document.getElementById('state'+iv+"_"+jv).value = data[1];
                fix_tax(iv, jv);
            }
        });
        $('#load_page').hide();
    }
    // validate the supplier textbox

    // assign tax based on the chosen product / sub product
    function fix_tax(iv, jv) {
        $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var ostate = $('#state'+iv+'_'+jv).val();
        var strURL="ajax/ajax_validate.php?action=fix_tax&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == '') {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Tax details Available. Kindly Contact MIS team to fix the HSN CODE!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                } else {
                    var reslt = data1.split("-");
                    if(ostate == 1)
                    {
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val(reslt[0]);
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val(reslt[1]);
                        $('#txt_prdigst_per_'+iv+'_'+jv).val('');
                    }else{
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdigst_per_'+iv+'_'+jv).val(reslt[2]);
                    }   
                }
                $('#load_page').hide();
            }
        });
    }
    // assign tax based on the chosen product / sub product

    function call_product_innergrid(gridid) {
        $('#load_page').show();
        // $("#addbtn").click(function () {
            // alert("CAME");
            if( ($('.parts3 .part3').length+1) > 99) {
                alert("Maximum 100 Products allowed.");
            } else {
                var slt_subcore = $('#slt_subcore').val();
                if(slt_subcore == 41) {
                    var rdnly = "";
                } else {
                    var rdnly = "readonly";
                }
                $('[data-toggle="tooltip"]').tooltip();
                var id = ($('.parts3 .part3').length + 2).toString();
                $('#partint3').val(id);
                $('.parts3').append('<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
                                        '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line">&nbsp;'+id+'</div>'+
                                        '</div>'+
                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" name="txt_prdcode[]" id="txt_prdcode_'+id+'" required="required" maxlength="100" placeholder="Product" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty('+id+')" style=" text-transform: uppercase; padding: 0px;height: 25px;"></div><div style="clear: both;">'+
                                            '</div>'+

                                            '<div style="width: 49%; float: left;margin-left: 2px;">'+
                                                '<input type="text" name="txt_subprdcode[]" id="txt_subprdcode_'+id+'" maxlength="100" placeholder="Sub Product" data-toggle="tooltip" data-placement="top" title="Sub Product" onKeyPress="enable_product();" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty('+id+')" style=" text-transform: uppercase;height: 25px;">'+

                                                '<input type="hidden" readonly="readonly" name="txt_unitname[]" id="txt_unitname_'+id+'" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                                '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_'+id+'" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both; height: 1px;"></div>'+

                                            '<div>'+
                                                '<input type="text" name="txt_prdspec[]" id="txt_prdspec_'+id+'" required="required" maxlength="100" placeholder="Product Specification" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty('+id+')" style=" text-transform: uppercase;height: 25px;">'+
                                            '</div><div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" name="txt_print_location[]" id="txt_print_location_'+id+'" maxlength="25" placeholder="Ad. Print Location" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Ad. Print Location" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div style="width: 49%; float: left;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_'+id+'" onblur="calculateqtyamount('+id+')" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div><input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_'+id+'" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="enable_product(); return numwodot(event)" name="txt_prdqty[]" id="txt_prdqty_'+id+'" required="required" maxlength="6" placeholder="Qty" onblur="calculateqtyamount('+id+')" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltrate_'+id+'">'+
                                            ' -'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+
                                        // discount hide 
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                        /*  '<div style="float: left; width: 50%; text-align: right;">SPL.DIS. : </div><div style="float: left; width: 50%;" id="id_sltslds_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">PCELES. : </div><div style="float: left; width: 50%;" id="id_sltpcls_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+*/
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC.% : </div><div style="float: left; width: 50%;" id="id_sltdisc_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltamnt_'+id+'">'+
                                            ' -'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row" style="margin-right: -5px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>'+
                                        '<!-- Quotation -->'+
                                        '<div class="col-sm-10 colheight" style="padding: 0px; border-top-left-radius:5px;">'+
                                            '<div class="fair_border" style="padding-left: 0px;">'+
                                                '<div class="row" style="margin-right: -10px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">#</div>'+
                                                    '<div class="col-sm-3 colheight" style="padding: 0px;">Supplier Details</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Delivery Duration</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Per Piece Rate / Adv. Amount</div>'+
                                                    // '<div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Tax Val.</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Discount % </div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Quotation PDF</div>'+
                                                    '<div class="col-sm-2 colheight" style="padding: 0px;">Remarks</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+
                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style="padding: 0px; border: 1px solid #FFFFFF !important; background-color: #FFFFFF; border-top-left-radius:5px;"></div>'+
                                    '</div> '+

                                    '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                        '<div class="col-sm-10 colheight" style="padding-left: 0px;">'+
                                            '<!-- Quotation -->'+
                                            '<div class="parts3_'+id+' fair_border">'+
                                                '<div class="row" style="margin-right: -10px; display: flex;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<div class="fg-line">'+
                                                            '<input type="hidden" name="partint3_'+id+'" id="partint3_'+id+'" value="1"><input type="hidden" name="txt_prdsgst_per['+id+'][]" id="txt_prdsgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdcgst_per['+id+'][]" id="txt_prdcgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdigst_per['+id+'][]" id="txt_prdigst_per_'+id+'_1" value="">'+
                                                            '<button class="btn btn-success btn-add3" id="addbtn_'+id+'" type="button" title="Add Suppliers" onclick="call_innergrid('+id+')" style="margin-right: 4px;"><span class="glyphicon glyphicon-plus"></span></button>'+
                                                            '<button id="removebtn_'+id+'" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" onclick="call_innergrid_remove('+id+')"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;<input type="radio" checked="checked" name="txt_sltsupplier['+id+'][]" id="txt_sltsupplier_'+id+'_1" value="1" onclick="getrequestvalue('+id+', 1)" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;1'+
                                                        '</div>'+
                                                    '</div>'+

                                                    '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" name="txt_sltsupcode['+id+'][]" id="txt_sltsupcode_'+id+'_1" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" onKeyPress="enable_product();" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+id+', 1)" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" name="state['+id+'][]" id="state'+id+'_1" value="">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_delivery_duration['+id+'][]" id="txt_delivery_duration_'+id+'_1" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate['+id+'][]" id="txt_prdrate_'+id+'_1" onblur="calculatenetamount('+id+',1)" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        'Adv.Amount Val.:'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+id+'][]" id="txt_advance_amount_'+id+'_1" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdsgst['+id+'][]" id="txt_prdsgst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdcgst['+id+'][]" id="txt_prdcgst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdigst['+id+'][]" id="txt_prdigst_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;"> '+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc['+id+'][]" id="txt_spldisc_'+id+'_1" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" onblur="calculatenetamount('+id+',1)" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless['+id+'][]" id="txt_pieceless_'+id+'_1" required="required" maxlength="5" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" onblur="calculatenetamount('+id+',1)" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc['+id+'][]" id="txt_prddisc_'+id+'_1" onblur="calculatenetamount('+id+',1)" required="required" maxlength="10" placeholder="Discount % " data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount['+id+'][]" id="hid_prdnetamount_'+id+'_1" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+id+'_1">0</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="file" name="fle_supquot['+id+'][]" id="fle_supquot_'+id+'_1" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                                        '<textarea onKeyPress="enable_product();" name="suprmrk['+id+'][]" id="suprmrk_'+id+'_1" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+id+',1)" class="form-control" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+

                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                    '</div>'+
                                    '</div><script>$("#fle_supquot_'+id+'_1").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                                    );
            }

            $('#txt_prdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product'
                        },
                        success: function( data ) {
                            // alert("###"+data+"###");
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

            $('#txt_subprdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           product: $('#txt_prdcode_'+id).val(),
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'sub_product'
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

            $('#txt_prdspec_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product_specification'
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

            $('#txt_sltsupcode_'+id+'_1').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'supplier_withcity'
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
        // });
        $('#load_page').hide();
    }
	
	function numwodot(evt)
    {
       if ((evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
        }
    }
	
	
	
    function call_product_innergrid_remove(gridid) {
        // $("#removebtn").click(function () {
           if ($('.parts3 .part3').length == 0) {
              alert("No more row to remove.");
           }
           var id = ($('.parts3 .part3').length - 1).toString();
           $('#partint3').val(id);
           $(".parts3 .part3:last").remove();
        // });
    }

    function call_innergrid(gridid) {
        $('#load_page').show();
        // alert("**"+gridid);
        // $("#addbtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if( ($('.parts3_'+gridid+' .part3_'+gridid).length+1) > 99) {
                alert("Maximum 100 Suppliers allowed.");
            } else {
                $('[data-toggle="tooltip"]').tooltip();
                var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();
                $('#partint3_'+gridid).val(gid);
                // alert("@@"+gid);
                $('.parts3_'+gridid).append('<div class="row part3_'+gridid+'" style="margin-right: -10px; display: flex;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line"><input type="hidden" name="txt_prdsgst_per['+gridid+'][]" id="txt_prdsgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdcgst_per['+gridid+'][]" id="txt_prdcgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdigst_per['+gridid+'][]" id="txt_prdigst_per_'+gridid+'_'+gid+'" value="">'+
                                                '<input type="radio" onclick="getrequestvalue('+gridid+', '+gid+')" name="txt_sltsupplier['+gridid+'][]" id="txt_sltsupplier_'+gridid+'_'+gid+'" value="'+gid+'" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;'+gid+''+
                                            '</div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+gridid+', '+gid+')" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" value="">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_delivery_duration['+gridid+'][]" id="txt_delivery_duration_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdrate['+gridid+'][]" id="txt_prdrate_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            'Adv.Amount Val.:'+
                                            '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+gridid+'][]" id="txt_advance_amount_'+gridid+'_'+gid+'" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdsgst['+gridid+'][]" id="txt_prdsgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')"  required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdcgst['+gridid+'][]" id="txt_prdcgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdigst['+gridid+'][]" id="txt_prdigst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+


                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_spldisc['+gridid+'][]" id="txt_spldisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_pieceless['+gridid+'][]" id="txt_pieceless_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="6" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prddisc['+gridid+'][]" id="txt_prddisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+')" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="hid_prdnetamount['+gridid+'][]" id="hid_prdnetamount_'+gridid+'_'+gid+'" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+gridid+'_'+gid+'">0</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="file" name="fle_supquot['+gridid+'][]" id="fle_supquot_'+gridid+'_'+gid+'" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                        '</div>'+

                                        '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                            '<textarea onKeyPress="enable_product();" name="txt_suprmrk['+gridid+'][]" id="txt_suprmrk_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+gridid+','+gid+')" class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                        '</div>'+
                                    '</div><script>$("#fle_supquot_'+gridid+'_'+gid+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                                    );
            }
        // });

        $('#txt_sltsupcode_'+gridid+'_'+gid).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url : 'ajax/ajax_product_details.php',
                    dataType: "json",
                    data: {
                       name_startsWith: request.term,
                       slt_core_department: $('#slt_core_department').val(),
                       slt_targetno: $('#slt_targetno').val(),
                       action: 'supplier_withcity'
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
        $('#load_page').hide();
    }

    function call_innergrid_remove(gridid) {
        // alert("**"+gridid);
        // $("#removebtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if ($('.parts3_'+gridid+' .part3_'+gridid).length == 0) {
                // alert("!!"+gridid);
                alert("No more row to remove.");
            }
            var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length - 1).toString();
            // alert(gridid+"@@"+gid);
            $('#partint3_'+gridid).val(gid);
            $('.parts3_'+gridid+' .part3_'+gridid+':last').remove();
        // });
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
	
	
	function addmonthwiseRequest(){
		var formElement = document.getElementById("frm_request_entry_1");
		var formData = new FormData(formElement);
		$.ajax({
				type: "POST",
				url: "",
				data: formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,   // tell jQuery not to set contentType
				success: function(results) {
						alert(results);
				}//success close
		});	
	}
	
    
			
			function enable_product() {
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
			
	
	
	
		
	
   
    </script>
   

<!-- END SCRIPTS -->         
</body>
</html>