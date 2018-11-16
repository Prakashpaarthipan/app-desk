<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
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
    /* $sql_rqsr = select_query_json("select max(arqsrno) arqsrno from approval_request 
                                            where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and 
                                                ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."'", "Centra", "TEST");
    $rqsrno = $sql_rqsr[0]['ARQSRNO']; */
}

function closetags($html) {
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i=0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</'.$openedtags[$i].'>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
} 

$sql_reqid = select_query_json("select req.*, top.ATCNAME, typ.ATYNAME, apm.APMNAME, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, emp.EMPCODE,
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
                                        (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME, pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority,
                                        (select ADDDATE from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and
                                        ATYCODE = req.ATYCODE and deleted = 'N') addeddate
                                    from APPROVAL_REQUEST req, approval_type typ, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                        employee_office emp, approval_priority pr
                                    where req.ATYCODE = typ.ATYCODE  and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.PRICODE = pr.pricode(+) and
                                        pr.deleted(+) = 'N' and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K','T') and
                                        req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and
                                        req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
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
                                            (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME, pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority,
                                            (select ADDDATE from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and
                                            ATYCODE = req.ATYCODE and deleted = 'N') addeddate
                                        from APPROVAL_REQUEST req, approval_type typ,  approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                            employee_office_deleted edl, approval_priority pr
                                        where req.ATYCODE = typ.ATYCODE  and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.PRICODE = pr.pricode(+)
                                            and pr.deleted(+) = 'N' and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and (edl.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T')
                                            and req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and
                                            req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
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
                                            (select ATMNAME from approval_type_mode where atmcode = req.atmcode) ATMNAME, pr.pricode, pr.priname, pr.pricode||' - '||pr.priname priority,
                                            (select ADDDATE from APPROVAL_REQUEST where ARQCODE = req.ARQCODE and ARQYEAR = req.ARQYEAR and ARQSRNO = 1 and ATCCODE = req.ATCCODE and
                                            ATYCODE = req.ATYCODE and deleted = 'N') addeddate
                                        from APPROVAL_REQUEST req, approval_type typ, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                            employee_office emp, approval_priority pr
                                        where req.ATYCODE = typ.ATYCODE  and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.PRICODE = pr.pricode(+)
                                            and pr.deleted(+) = 'N' and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and (emp.empsrno = req.ADDUSER) and brn.BRNMODE in ('B', 'K','T')
                                            and req.BRNCODE = brn.BRNCODE and req.DEPCODE = ast.DEPCODE and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and
                                            req.ARQSRNO = '".$rqsrno."' and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
                                        order by arcode, arsrno, atcode", "Centra", 'TEST'); // req.ATMCODE = apm.ATMCODE and ---- for Approval Master
}

/*
$sql_reqid_edit = select_query_json("select APPROVAL_REQUEST.REQSTBY from APPROVAL_REQUEST
                                            where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '".$rqsrno."' and
                                                ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and APPSTAT = 'N'
                                            order by ARQCODE, ARQSRNO, ATYCODE", "Centra", "TEST");

$sql_reqid_edit1 = select_query_json("select ARQCODE from APPROVAL_REQUEST
                                            where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = 2 and
                                                ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N'
                                            order by ARQCODE, ARQSRNO, ATYCODE", "Centra", "TEST");

if($_REQUEST['action'] == 'edit' and ( $sql_reqid_edit[0]['REQSTBY'] != $_SESSION['tcs_empsrno'] or count($sql_reqid_edit1[0][0]) > 0)) { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="pending_approvals.php";</script>
<? exit();
}
*/

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


$sql_vl = select_query_json("select distinct req.APRQVAL, req.ADDDATE, max(req.arqsrno) mx, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE, req.TARNUMB, 
                                        req.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME, req.ADVAMNT, pri.pricode, pri.priname, pri.pricode||' - '||pri.priname priority, req.TXTSUBJ, 
                                        req.DYNSUBJ, req.DYSBFDT, req.DYSBTDT 
                                    from APPROVAL_REQUEST req, department_asset ast, approval_priority pri
                                    where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and req.DEPCODE = ast.DEPCODE and req.PRICODE = pri.pricode(+) and pri.deleted(+) = 'N' 
                                        and req.DELETED = 'N' and ast.DELETED = 'N'
                                    group by req.APRQVAL, req.ADDDATE, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE, req.TARNUMB,
                                        req.TARDESC, ast.EXPSRNO, ast.EXPNAME, ast.DEPNAME, req.ADVAMNT, pri.pricode, pri.priname, req.TXTSUBJ, req.DYNSUBJ, req.DYSBFDT, req.DYSBTDT
                                    order by mx desc", "Centra", "TEST");

$sql_tmporlive = select_query_json("select ast.EXPNAME exphead from approval_budget_planner but, department_asset ast
                                        where ast.EXPSRNO = but.EXPSRNO and but.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and but.aprsrno = 1", "Centra", "TEST");
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
        $clrcod = 'badge-ap1';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
        $css_cls = "#FF0000";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
        break;
    case 2:
        $duedate = 2;
        $clrcod = 'badge-ap2';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
        $css_cls = "#D58B0A";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
        break;
    case 3:
        $duedate = 3;
        $clrcod = 'badge-ap3';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
        $css_cls = "#299654";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
        break;
    case 4:
        $duedate = 4;
        $clrcod = 'badge-ap4';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
        $css_cls = "#299654";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
        break;
    case 5:
        $duedate = 5;
        $clrcod = 'badge-ap5';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + ($sql_iv[0]['CNTAPPFRWD'] * 2); }
        $css_cls = "#299654";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
        break;

    default:
        $duedate = 1;
        $clrcod = 'badge-ap1';
        if($sql_iv[0]['CNTAPPFRWD'] > 0) { $duedate = $duedate + $sql_iv[0]['CNTAPPFRWD']; }
        $css_cls = "#FF0000";
        if($start_time <= $duedate) {
            $css_clstime = "#299654";
        } else {
            $css_clstime = "#FF0000";
        }
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
<style type="text/css">
    .form-horizontal .control-label { padding-top: 0px !important; }
    .print_table th,
    .print_table td {
        border:1px solid #a0a0a0 !important;
        text-align: center !important;
        font-weight: normal;
    }
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
                                    <li><? /* <span class="badge <?=$clrcod?>" style="font-size:16px; background-color:#FF0000; font-weight:bold;">AP-<?=$sql_vl[0]['PRIORITY']?></span> */ ?>
                                        <span class="label label-info label-form <?=$clrcod?>">AP-<?=$sql_vl[0]['PRIORITY']?></span> <span class="label label-info label-form" style="background-color:<?=$css_clstime?>">Due Date : <?=$duedate?> Days & Process Date : <?=$start_time?> Days</span></li>
                                    <li class="label <?=$appr_lblclass?> label-form"><?=$appr_status?></li>
                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                </ul>
                            </div>
                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <!-- New -->
                                        <input type='hidden' name='slt_project_type' id='slt_project_type' value='R'>
                                        <input type='hidden' name='slt_topcore_name' id='slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
                                        <input type='hidden' name='hid_slt_subcore_name' id='hid_slt_subcore_name' value='<?=$_SESSION['tcs_empsubcore']?>'>
                                        <input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>
                                        <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>
                                        <input type='hidden' name='slt_subcore' id='slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>

                                        <input type='hidden' name='hidd_depcode' id='hidd_depcode' value=''>
                                        <input type='hidden' name='hidd_depname' id='hidd_depname' value=''>
                                        <input type='hidden' name='hidd_expsrno' id='hidd_expsrno' value=''>
                                        <input type='hidden' name='hidd_multireq' id='hidd_multireq' value='N'>
                                        <input type='hidden' name='slt_targetno' id='slt_targetno' value='N'>
                                        <!-- New -->

                                        <input type='hidden' name='slt_subtype' id='slt_subtype' value='1'>
                                        <input type='hidden' name='slt_submitfor' id='slt_submitfor' value='0'>
                                        <input type='hidden' name='txt_kind_attn' id='txt_kind_attn' value='0'>
                                        <input type='hidden' name='txtsubject' id='txtsubject' value='0'>
                                        <input type='hidden' name='currentyr' id='currentyr' value='<?=$current_year[0]['PORYEAR']?>'>
                                        <input type='hidden' name='txt_purhead' id='txt_purhead' value='<?=$sql_reqid[0]['PURHEAD']?>'>
                                        <?  if($sql_reqid[0]['EXPSRNO'] != '') {
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '".$sql_reqid[0]['EXPSRNO']."'", "Centra", "TEST");
                                            } else {
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '13'", "Centra", "TEST");
                                            } ?>
                                        <input type='hidden' name='txt_rptmode' id='txt_rptmode' value='<?=$sql_rptmode[0]['RPTMODE']?>'>

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

                                        <!-- Project -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Project <span style='color:red'>*</span></label>
                                            <div class="col-md-9">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <?=$sql_reqid[0]['APRNAME']?>
                                                <? } else { ?>
                                                    <select class="form-control custom-select chosn" autofocus tabindex='1' required name='slt_project' id='slt_project' data-toggle="tooltip" data-placement="top" data-original-title="Project" onChange="gettopcore(this.value)">
                                                    <?  $sql_project = select_query_json("select * from approval_project where DELETED = 'N' order by APRCODE Asc", "Centra", "TEST");
                                                        for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                        <option value='<?=$sql_project[$project_i]['APRCODE']?>' <? if($sql_reqid[0]['APRCODE'] == $sql_project[$project_i]['APRCODE']) { $tpcr = $sql_project[$project_i]['ATCCODE']; ?> selected <? } ?>><?=$sql_project[$project_i]['APRCODE']." - ".$sql_project[$project_i]['APRNAME']?></option>
                                                    <? } ?>
                                                    </select>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Project -->

                                        <!-- Type of Submission Type -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Type of Submission <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <? // echo "**".strtotime($sql_reqid[0]['APPRSFR'])."**".strtotime('22-APR-18')."**".$sql_reqid[0]['ATYNAME']."**";
                                                        if((strtotime($sql_reqid[0]['APPRSFR']) <= strtotime('22-APR-18')) and $sql_reqid[0]['ATYNAME'] == 'NEW PROPOSAL' )
                                                            { $aptype_display = "EXTRA BUDGET"; }
                                                            else { $aptype_display = $sql_reqid[0]['ATYNAME']; } echo $aptype_display; ?>
                                                    <input type='hidden' name='hid_slt_submission' id='hid_slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                                    <input type='hidden' name='slt_submission' id='slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                                <? } else { ?>
                                                    <? if($_REQUEST['action'] == 'edit') { ?>
                                                        <input type='hidden' name='hid_slt_submission' id='hid_slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                                        <input type='hidden' name='hid_slt_core_department' id='hid_slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
                                                        <input type='hidden' name='hid_slt_department_asset' id='hid_slt_department_asset' value='<?=$sql_reqid[0]['DEPCODE']?>'>
                                                        <input type='hidden' name='hid_slt_targetno' id='hid_slt_targetno' value='<?=$sql_reqid[0]['TARNUMB']?>'>
                                                    <? } ?>
                                                    <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn" onblur="get_targetdates()" onChange="getsubtype(this.value)"<? } ?> tabindex='3' required name='slt_submission' id='slt_submission' data-toggle="tooltip" data-placement="top" data-original-title="Type of Submission">
                                                    <?  $sql_submission_type = select_query_json("select * from approval_type where DELETED = 'N' and ATYCODE not in (1) order by ATYSRNO", "Centra", "TEST"); // 1 for FIXED BUDGET, 7 for Extra Budget
                                                        ?>
                                                        <option value='' <? if($sql_reqid[0]['ATYCODE'] == '') { ?> selected <? } ?>>-- Choose Type of Submission --</option>
                                                        <?
                                                        for($submission_type_i = 0; $submission_type_i < count($sql_submission_type); $submission_type_i++) { ?>
                                                            <option value='<?=$sql_submission_type[$submission_type_i]['ATYCODE']?>' <? if($sql_reqid[0]['ATYCODE'] == $sql_submission_type[$submission_type_i]['ATYCODE']) { ?> selected <? } ?>><?=$sql_submission_type[$submission_type_i]['ATYNAME']?></option>
                                                    <? } ?>
                                                    </select>
                                                <? } ?>
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
                                                    <? if($_REQUEST['action'] == 'view') { ?>
                                                        : <?=$sql_vl[0]['EXPHEAD']?>
                                                    <? } else { ?>
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="form-control custom-select" <? } else { ?> class="form-control custom-select chosn" onblur="get_advancedetails(this.value)" onChange="get_dept(this.value)" <? } ?> tabindex='4' required name='slt_core_department' id='slt_core_department' data-toggle="tooltip" data-placement="top" data-original-title="Core Department">
                                                        <?  if($sql_vl[0]['EXPSRNO'] == '') {
                                                                $sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset
                                                                                                            where DELETED = 'N' and expsrno > 0 order by EXPNAME", "Centra", "TEST");
                                                            } else {
                                                                $sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset
                                                                                                            where DELETED = 'N' and expsrno > 0 and EXPSRNO = '".$sql_vl[0]['EXPSRNO']."' order by EXPNAME", "Centra", "TEST");
                                                            }

                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                <option value='<?=$sql_project[$project_i]['EXPSRNO']?>' <? if($sql_reqid[0]['EXPSRNO'] == $sql_project[$project_i]['EXPSRNO']) { ?> selected <? } ?>><?=$sql_project[$project_i]['EXPNAME']?></option>
                                                        <? } ?>
                                                        </select>
                                                    <? } ?>
                                                </div>
                                            </div>
                                            <div class="tags_clear"></div>
                                            <!-- Expense Head -->

                                            <div id='id_department'>
                                                <!-- Department -->
                                                <div class="form-group" style="display: none;">
                                                    <label class="col-md-3 control-label">Department <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            : <?=$sql_reqid[0]['DEPNAME']?>
                                                        <? } else { ?>
                                                            <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn" onblur="get_advancedetails(this.value)" onChange="get_advancedetails(this.value)"<? } ?> tabindex='5' required name='slt_department_asset' id='slt_department_asset' data-toggle="tooltip" data-placement="top" data-original-title="Department Asset">
                                                                <?  $sql_project = select_query_json("select * from department_asset where DELETED = 'N' and expsrno > 0 order by DEPNAME", "Centra", "TEST");
                                                                    for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                        <option value='<?=$sql_project[$project_i]['DEPCODE']?>' <? if($sql_reqid[0]['DEPCODE'] == $sql_project[$project_i]['DEPCODE']) { ?> selected <? } ?>><?=$sql_project[$project_i]['DEPNAME']?></option>
                                                                <? } ?>
                                                            </select>
                                                        <? } ?>
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
                                                        <? if($_REQUEST['action'] == 'view') { ?>
                                                            : <?=$sql_reqid[0]['TARNUMB']." - ".$sql_reqid[0]['TARDESC']?>
                                                        <? } else {
                                                            $brnc = 1;
                                                            if($sql_reqid[0]['BRNCODE']) {
                                                                $brnc = $sql_reqid[0]['BRNCODE'];
                                                            }

                                                            $depc = 98;
                                                            if($sql_reqid[0]['DEPCODE']) {
                                                                $depc = $sql_reqid[0]['DEPCODE'];
                                                            } ?>
                                                            <div id='id_targetno'>
                                                                <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn" onblur="get_targetdates()" onchange="get_targetdates()"<? } ?> tabindex='6' required name='slt_targetnos' id='slt_targetnos' data-toggle="tooltip" data-placement="top" data-original-title="Target No">
                                                                <?  $sql_tarno = select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||( select distinct
                                                                                                            decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname from non_purchase_target tar,
                                                                                                            department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and
                                                                                                            dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) Depname, (select distinct
                                                                                                            decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname from non_purchase_target tar,
                                                                                                            department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and
                                                                                                            dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) dpnm, (select distinct
                                                                                                            dep.depcode||'!!'||dep.depname||'!!'||dep.EXPSRNO||'!!'||dep.TOPCORE||'!!20!!N' Depname
                                                                                                            from non_purchase_target tar, department_asset Dep
                                                                                                            where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and
                                                                                                            tar.brncode=bpl.brncode) deptdet
                                                                                                        from budget_planner_branch bpl
                                                                                                        where TARYEAR=".(date("y")-1)." and TARMONT=".$cur_mn." and depcode in (113, 125)
                                                                                                        order by Depname", "Centra", "TEST"); // ||'-'||dep.ESECODE||'-'||dep.MULTIREQ

                                                                    for($tarno_i = 0; $tarno_i < count($sql_tarno); $tarno_i++) {
                                                                        if($sql_tarno[$tarno_i]['DPNM'] != '') { ?>
                                                                            <option value='<?=$sql_tarno[$tarno_i]['TARNUMB']."||".$sql_tarno[$tarno_i]['DEPTDET']?>' <? if($sql_reqid[0]['TARNUMB'] == $sql_tarno[$tarno_i]['TARNUMB']) { ?> selected <? } ?>><?=$sql_tarno[$tarno_i]['DEPNAME']?></option>
                                                                <? } } ?>
                                                                </select>
                                                            </div>
                                                        <? } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Target No -->
                                            </div>
                                        <? } ?>
                                        </div>
                                        <div class="tags_clear"></div>

                                        <!-- Approval Subject -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Approval Subject <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <? echo $sql_reqid[0]['APMNAME']." "; /* if(trim($sql_vl[0]['DYNSUBJ']) != '-') { echo $sql_vl[0]['DYNSUBJ']; } if(trim($sql_vl[0]['TXTSUBJ']) != '-') { echo " - ".$sql_vl[0]['TXTSUBJ']; } */ 
                                                        $exp_subj = explode(" -", $sql_reqid[0]['DYNSUBJ']);
                                                        $frm_mn_subj = trim($exp_subj[0]);
                                                        $to_mn_subj = trim($exp_subj[1]); 

                                                        if($sql_vl[0]['DYSBFDT'] != '') { 
                                                            echo strtoupper(date("d-M-Y", strtotime($sql_vl[0]['DYSBFDT'])) ." TO ". date("d-M-Y", strtotime($sql_vl[0]['DYSBTDT'])));
                                                        } elseif(trim($sql_vl[0]['DYNSUBJ']) != '-') { echo $sql_vl[0]['DYNSUBJ']; } ?>
                                                <? } else { ?>
                                                    <div id="id_appr_listings">
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="form-control custom-select" <? } else { ?> class="form-control custom-select chosn" onChange="getapproval_listings(this.value)" onblur="call_days()" <? } ?> tabindex='7' required name='slt_approval_listings' id='slt_approval_listings' data-toggle="tooltip" data-placement="top" data-original-title="Approval Subject">
                                                        <option value=''>Choose Approval Subject</option>
                                                        <?  if($_REQUEST['action'] == 'edit') {
                                                                if($sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7) {
                                                                    $attycode = 1;
                                                                } else {
                                                                    $attycode = $sql_reqid[0]['ATYCODE'];
                                                                }
                                                                $sql_approval_type_mode = select_query_json("select * from approval_master
                                                                                                                    where ATYCODE = '".$attycode."' and DELETED = 'N' and apmcode not in (83, 657, 624)
                                                                                                                    order by APMNAME Asc", "Centra", 'TEST');
                                                            } else {
                                                                $sql_approval_type_mode = select_query_json("select * from approval_master
                                                                                                                    where ATYCODE in (1, 6, 7) and DELETED = 'N' and apmcode not in (83, 657, 624)
                                                                                                                    order by APMNAME Asc", "Centra", "TEST");
                                                            }
                                                            for($approval_type_mode_i = 0; $approval_type_mode_i < count($sql_approval_type_mode); $approval_type_mode_i++) { ?>
                                                                <option value='<?=$sql_approval_type_mode[$approval_type_mode_i]['APMCODE']?>' <? if($sql_reqid[0]['APMCODE'] == $sql_approval_type_mode[$approval_type_mode_i]['APMCODE']) { ?> selected <? } ?>><?=$sql_approval_type_mode[$approval_type_mode_i]['APMNAME']?></option>
                                                        <? } ?>
                                                        </select>
                                                    </div>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Approval Subject -->

                                        <? if(trim($sql_vl[0]['TXTSUBJ']) != '-' or trim($sql_vl[0]['TXTSUBJ']) != '' or trim($sql_vl[0]['TXTSUBJ']) != ' ') { ?>
                                        <!-- Specific Subject -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Specific Subject</label>
                                            <div class="col-md-9 col-xs-12">
                                                : <?=$sql_vl[0]['TXTSUBJ']?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Specific Subject -->
                                        <? } ?>


                                        <!-- Initiator & Attachments Panel -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Initiator & Attachments</strong></h3>
                                                <? /* <ul class="panel-controls">
                                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                                </ul> */ ?>
                                            </div>
                                            <div class="panel-body">
                                                <? if($sql_reqid[0]['WRKINUSR'] != '') { ?>
                                                    <!-- Work Initiate Person -->
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Work Initiate Person <span style='color:red'>*</span></label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <?  $sql_emp = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                            des.DESNAME, sal.PAYCOMPANY
                                                                                                        from employee_office emp, empsection sec, designation des, employee_salary sal
                                                                                                        where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                            (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."') and sec.deleted = 'N'
                                                                                                            and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                    union
                                                                                                        select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                            des.DESNAME, sal.PAYCOMPANY
                                                                                                        from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                        where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                            (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."') and sec.deleted = 'N'
                                                                                                            and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                        order by EMPCODE", "Centra", "TEST"); // 02052017
                                                                    if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." ";
                                                                } else { ?>
                                                                    <input type='text' class="form-control" tabindex='11' style="text-transform: uppercase;" required name='txt_workintiator' id='txt_workintiator' data-toggle="tooltip" data-placement="top" data-original-title="Work Initiate Person" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>' onfocus="call_dynamic_option()" onblur="call_dynamic_option()">
                                                            <?  } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <!-- Work Initiate Person -->
                                                <? } ?>

                                                <!-- Responsible Person -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Responsible Person <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  $sql_emp = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                        des.DESNAME, sal.PAYCOMPANY
                                                                                                    from employee_office emp, empsection sec, designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                        (emp.empcode = '".$sql_reqid[0]['RESPUSR']."'
                                                                                                        or emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and sec.deleted = 'N'
                                                                                                        and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                union
                                                                                                    select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                        des.DESNAME, sal.PAYCOMPANY
                                                                                                    from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                        (emp.empcode = '".$sql_reqid[0]['RESPUSR']."'
                                                                                                        or emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and sec.deleted = 'N'
                                                                                                        and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                    order by EMPCODE", "Centra", "TEST"); // 02052017
                                                                if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>
                                                                <input type='text' class="form-control" tabindex='11' style="text-transform: uppercase;" required name='txt_submission_reqby' id='txt_submission_reqby' data-toggle="tooltip" data-placement="top" data-original-title="Responsible Person" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>' onfocus="call_dynamic_option()" onblur="call_dynamic_option()">
                                                        <?  } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Responsible Person -->

                                                <? if($sql_reqid[0]['ALTRUSR'] != '') { ?>
                                                    <!-- Alternate User -->
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Alternate User</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <?  /* if(count($sql_emp) > 1) {
                                                                    $emcd = $sql_emp[1]['EMPCODE'];
                                                                    $emnm = $sql_emp[1]['EMPNAME'];
                                                                    $dsnm = $sql_emp[1]['DESNAME'];
                                                                    $senm = $sql_emp[1]['ESENAME'];
                                                                } else { */
                                                                    if($sql_reqid[0]['ALTRUSR'] != '') {
                                                                        $sql_emp = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY
                                                                                                            from employee_office emp, empsection sec, designation des, employee_salary sal
                                                                                                            where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['ALTRUSR']."')
                                                                                                                and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                        union
                                                                                                            select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                                des.DESNAME, sal.PAYCOMPANY
                                                                                                            from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                            where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = '".$sql_reqid[0]['ALTRUSR']."')
                                                                                                                and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                            order by EMPCODE", "Centra", "TEST"); // 02052017
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
                                                                // }
                                                                if($_REQUEST['action'] == 'view') {
                                                                    if($emcd != '')
                                                                        echo ": ".$emcd." - <b>".$emnm."</b> (".$dsnm.") - ".substr($senm, 3)." ";
                                                                } else { ?>
                                                                    <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='11' name='txt_alternate_user' id='txt_alternate_user' data-toggle="tooltip" data-placement="top" data-original-title="Alternate User" value='<?=$emcd." - ".$emnm." - ".substr($senm, 3)?>'>
                                                            <?  } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                    <!-- Alternate User -->
                                                <? } ?>

                                                <!-- Attachments -->
                                                <!-- Quotations -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'quotations'", "Centra", "TEST");
                                                if(count($sql_docs) > 0) { ?>
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
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
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
                                                <? } if($sql_reqid[0]["RMQUOTS"] != '') { ?>
                                                    <div class="form-group">
                                                    <label class="col-md-3 control-label">Quotations & Estimations</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?=$sql_reqid[0]["RMQUOTS"];?>
                                                    </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                <? } ?>

                                                <div class="tags_clear"></div>
                                                <!-- Quotations -->


                                                <!-- Approval Supporting Documents -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'fieldimpl'", "Centra", "TEST");
                                                if(count($sql_docs) > 0) { ?>
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
                                                                        echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";

                                                                        /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */
                                                                        break;
                                                                case 'w':
                                                                        echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";

                                                                        /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */
                                                                        break;
                                                                case 'e':
                                                                        echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";

                                                                        /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */
                                                                        break;
                                                                case 'p':
                                                                        echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";

                                                                        /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */
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
                                                <? } if($sql_reqid[0]["RMBDAPR"] != '') { ?>
                                                    <div class="form-group">
                                                    <label class="col-md-3 control-label">Work Place Before / After Photo / Drawing Layout</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?=$sql_reqid[0]["RMBDAPR"];?>
                                                    </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                <? } ?>
                                                <div class="tags_clear"></div>
                                                <!-- Approval Supporting Documents -->

                                                <!-- Color Photo Sample / Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'clrphoto'", "Centra", "TEST");
                                                if(count($sql_docs) > 0) { ?>
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
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
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
                                                <? } if($sql_reqid[0]["RMCLRPT"] != '') { ?>
                                                    <div class="form-group">
                                                    <label class="col-md-3 control-label">Work Place Before / After Photo / Drawing Layout</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?=$sql_reqid[0]["RMCLRPT"];?>
                                                    </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                <? } ?>
                                                <div class="tags_clear"></div>
                                                <!-- Color Photo Sample / Artwork -->

                                                <!-- Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'artwork'", "Centra", "TEST");
                                                if(count($sql_docs) > 0) { ?>
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
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
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
                                                <? } if($sql_reqid[0]["RMARTWK"] != '') { ?>
                                                    <div class="form-group">
                                                    <label class="col-md-3 control-label">Art Work Design with MD Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?=$sql_reqid[0]["RMARTWK"];?>
                                                    </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                <? } ?>
                                                <div class="tags_clear"></div>
                                                <!-- Artwork -->

                                                <!-- Consultant Approval -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                            where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'othersupdocs'", "Centra", "TEST");
                                                if(count($sql_docs) > 0) { ?>
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
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'w':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'e':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                                break;
                                                                        case 'p':
                                                                                /* echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>"; */ 

                                                                                echo $fieldindi = "</ul><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/request_entry/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
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
                                                <? } if($sql_reqid[0]["RMCONAR"] != '') { ?>
                                                    <div class="form-group">
                                                    <label class="col-md-3 control-label">Consultant Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?=$sql_reqid[0]["RMCONAR"];?>
                                                    </div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                <? } ?>
                                                <div class="tags_clear"></div>
                                                <!-- Consultant Approval -->
                                                <!-- Attachments -->

                                                <!-- Warranty / Guarantee -->
                                                <? if($sql_reqid[0]["WARQUAR"] != '') { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Warranty / Guarantee</label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_reqid[0]["WARQUAR"];
                                                                } else { ?>
                                                                    <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='10' name='txt_warranty_guarantee' id='txt_warranty_guarantee' data-toggle="tooltip" data-placement="top" data-original-title="Warranty / Guarantee" placeholder="Warranty / Guarantee" value='<?=$sql_reqid[0]["WARQUAR"]?>'>
                                                            <?  } ?>
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
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_reqid[0]["CRCLSTK"];
                                                                } else { ?>
                                                                    <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='10' name='txt_cur_clos_stock' id='txt_cur_clos_stock' data-toggle="tooltip" data-placement="top" data-original-title="Current / Closing Stock" placeholder="Current / Closing Stock" value='<?=$sql_reqid[0]["CRCLSTK"]?>'>
                                                            <?  } ?>
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
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_reqid[0]["PAYPERC"];
                                                                } else { ?>
                                                                    <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='10' name='txt_advpay_comperc' id='txt_advpay_comperc' data-toggle="tooltip" data-placement="top" data-original-title="Advance or Final Payment / Work Completion Percentage" placeholder="Advance or Final Payment / Work Completion Percentage" value='<?=$sql_reqid[0]["PAYPERC"]?>'>
                                                            <?  } ?>
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
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_reqid[0]["FNTARDT"];
                                                                } else { ?>
                                                                    <input type="text" tabindex='10' name="txt_workfin_targetdt" id="datepicker_example4" class="form-control" readonly placeholder='Work Finish Target Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_reqid[0]['FNTARDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['FNTARDT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Work Finish Target Date'>
                                                            <?  } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Work Finish Target Date -->



                                                <!-- Agreement Expiry Date -->
                                                <? if($sql_reqid[0]["AGEXPDT"] != '') { ?>
                                                <div class="form-group" id="id_datepicker_example7">
                                                    <label class="col-md-3 control-label">Agreement Expiry Date</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                echo ": ".strtoupper(date("d-M-Y", strtotime($sql_reqid[0]["AGEXPDT"])));
                                                            } else { ?>
                                                                <input type="text" tabindex='10' name="txt_agreement_expiry" id="datepicker_example7" class="form-control" readonly placeholder='Agreement Expiry Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_reqid[0]['AGEXPDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['AGEXPDT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Agreement Expiry Date'>
                                                        <?  } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Agreement Expiry Date -->

                                                <!-- Agreement Advance Amount -->
                                                <? if($sql_reqid[0]["AGADVAM"] != '') { ?>
                                                <div class="form-group" id="id_txt_advpay_comperc">
                                                    <label class="col-md-3 control-label">Agreement Advance Amount</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]["AGADVAM"];
                                                            } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <? } ?>
                                                <!-- Agreement Advance Amount -->

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
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST"); ?>
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

                                                <div class="form-group trbg" id='getmonthwise_budget' <? if($view == 1 && $sql_reqid[0]['BDPLANR'] == 'MONTHWISE') { ?> style='display:block;' <? } else { ?> style='display:none;' <? } ?>>
                                                    <div class="col-lg-3 col-xs-3">Budget Planner &#8377;</div>
                                                    <div class="col-lg-9 col-xs-9"> :
                                                        <div id='id_budplanner'></div>
                                                        <div>
                                                            <table style='clear:both; float:left; width:100%;'>
                                                            <tr><td>
                                                                <table class="monthyr_wrap" style='width:100%;'>
                                                                    <?  $sql_plan = select_query_json("select * from ".$rcrd."
                                                                                                                where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO asc", "Centra", "TEST");
                                                                        $total_amt = 0;
                                                                        for($plani = 0; $plani < count($sql_plan); $plani++) {
                                                                            // $total_amt = $sql_plan[$plani]['APPRVAL'] + $sql_plan[$plani]['RESVALU'];
                                                                            $total_amt += $sql_plan[$plani]['APPRVAL']; ?>
                                                                            <tr>
                                                                                <td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRPRID']?>'><span><?=$sql_plan[$plani]['APRMNTH']?></span> : </td>
                                                                                <td style='width:5%;'></td><td style='width:40%;'><input type='hidden' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control ttlsum ttlsumrequired' value='<?=$sql_plan[$plani]['APPRVAL']?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero(<?=$plani?>, this.value);' maxlength='10' style='margin: 2px 0px;'><?=moneyFormatIndia($sql_plan[$plani]['APPRVAL'])?></td>
                                                                                <td style='width:30%;'><input type='hidden' id='ttl_lock_<?=$i?>' name='ttl_locks[]' value='<?=$total_amt?>'></td></tr>
                                                                        <? } ?>
                                                                        <tr><td colspan='2' style='width:40%; padding-top:2%; text-align:right; padding-right:5%; font-weight:bold;'>TOTAL : </td><td style='width:60%; padding-top:2%; font-weight:bold;'><span id='ttl_mntyr'><?=moneyFormatIndia($total_amt)?></span></td></tr>
                                                                </table>
                                                            </td></tr>
                                                            </table>
                                                        </div>
                                                        <div class="tags_clear"></div>
                                                    </div>
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
                                                                                                            where DELETED = 'N' and BUDCODE = '".$sql_reqid[0]['BUDCODE']."' order by BUDNAME", "Centra", "TEST"); ?>
                                                                : <? if($sql_bdmd[0]['BUDNAME'] != '') { if($sql_bdmd[0]['BUDCODE'] == 5) { ?>
                                                                    <span class="badge badge-success" style='background-color:#08a208; font-weight:bold;'><?=$sql_bdmd[0]['BUDNAME'];?></span>
                                                                <? } else { echo $sql_bdmd[0]['BUDNAME']; } } else { echo "-"; } ?>
                                                            <? } else { ?>
                                                                <select class="form-control custom-select chosn" tabindex='19' name='slt_budgetmode' id='slt_budgetmode' data-toggle="tooltip" data-placement="top" title="Budget Mode">
                                                                <?  $sql_project = select_query_json("select * from APPROVAL_BUDGET_MODE where DELETED = 'N' order by BUDNAME", "Centra", "TEST");
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
                                                <? if($view == 1) { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Actual Request Value &#8377; </label>
                                                        <div class="col-md-9 col-xs-12 red_clr" style="font-weight: bold;">
                                                            <? if($_REQUEST['action'] == 'view') { ?>
                                                                : <?=moneyFormatIndia($sql_reqid[0]['APRQVAL'])?>
                                                                <input type='hidden' class="form-control hidn_balance" placeholder="Actual Request Value" onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APRQVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Actual Request Value">
                                                            <? } else { ?>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">&#8377;</span>
                                                                    <input class="form-control hidn_balance" style="margin-top: 0px;" placeholder="Actual Request Value" tabindex='25' onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly onblur="find_balance(this.value)" <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APRQVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Actual Request Value">
                                                                    <span class="input-group-addon">.00</span>
                                                                </div>
                                                            <? } ?>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear"></div>

                                                    <div class="form-group" style="line-height: 25px;">
                                                        <label class="col-md-3 control-label">Final Approved Value &#8377; </label>
                                                        <div class="col-md-9 col-xs-12 green_clr" style="font-weight: bold; font-size: 20px;">
                                                            <? if($_REQUEST['action'] == 'view') { ?>
                                                                <?=moneyFormatIndia($sql_reqid[0]['APPFVAL'])?>
                                                                <input type='hidden' class="form-control hidn_balance" placeholder="Final Approved Value" onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APPFVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Final Approved Value">
                                                            <? } else { ?>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">&#8377;</span>
                                                                    <input class="form-control hidn_balance" style="margin-top: 0px;" placeholder="Final Approved Value" tabindex='25' onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly onblur="find_balance(this.value)" <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APPFVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Final Approved Value">
                                                                    <span class="input-group-addon">.00</span>
                                                                </div>
                                                            <? } ?>
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
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ";
                                                                if($sql_reqid[0]['IMDUEDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT']))); } else { echo strtoupper(date("d-M-Y")); }
                                                           } else { ?>
                                                                <input type="text" tabindex='24' name="impldue_date" id="datepicker_example3" class="form-control" required readonly placeholder='Implementation Due Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_reqid[0]['IMDUEDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Implementation Due Date'>
                                                        <? } ?>
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
                                                                                                                    brn.brncode in (109,114,117,120)) and brn.brncode not in (11, 22, 202, 205, 119)
                                                                                                                order by brn.BRNCODE", "Centra", "TEST"); // 108 - TRY Airport Not available
                                                                } else {
                                                                    $sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn
                                                                                                                where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') and
                                                                                                                    (brn.brncode in (".$_SESSION['tcs_brncode'].")) and brn.brncode not in (11, 22, 202, 205, 119)
                                                                                                                order by brn.BRNCODE", "Centra", "TEST"); // 108 - TRY Airport Not available
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
                                                                        <input type="text" class="form-control" name="txt_brnvalue[]" id="txt_brnvalue_<?=$project_i?>" value="" style="margin:2px;">
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
                                                                                                        order by amh.APMCODE, amh.AMHSRNO desc", "Centra", "TEST"); // 02052017
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
                                                                                                                    order by amh.APMCODE, amh.AMHSRNO desc", "Centra", "TEST"); // 02052017

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
                                                <!-- This is list details -->

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
                                            <? $sql_details = select_query_json("Select APPRSUB from APPROVAL_REQUEST req
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."'
                                                                                        order by arqsrno desc", "Centra", "TEST"); ?>
                                            <label class="col-md-3 control-label" style="text-align: left;">Details <span style='color:red'>*</span> : </label>
                                            <div class="tags_clear height10px"></div>
                                            <div class="col-md-12" style="border: 1px solid #dadada; padding: 5px !important;">
                                                <label class="print_table">
                                                <?  if($_REQUEST['action'] == 'view') {
                                                        if($sql_reqid[0]['APPRFOR'] == '1' or $sql_reqid[0]['APPRFOR'] == '2' or $sql_reqid[0]['APPRFOR'] == '3' or $sql_reqid[0]['APPRFOR'] == '4' or $sql_reqid[0]['APPRFOR'] == '5') {
                                                            $filepathname = $sql_details[0]['APPRSUB'];
                                                            $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                            $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_details[0]['APPRSUB']);
                                                            // $contents = fread($handle, filesize($filename));
                                                            $contents = file_get_contents($filename);
                                                            fclose($handle);
                                                            echo closetags($contents);
                                                            // echo $string = preg_replace("/<span[^>]+\>/i", "", closetags($contents));
                                                        } else {
                                                            echo $sql_reqid[0]['APPRDET'];
                                                        }
                                                   } else { ?>
                                                        <? /* <textarea class="form-control" <? if($_REQUEST['action'] != 'edit') { } ?> tabindex='17' rows="10" placeholder="Details" required maxlength='400' name='txtdetails' id='txtdetails' data-toggle="tooltip" data-placement="top" title="Details" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"><? echo $sql_reqid[0]['APPRDET']; ?></textarea>
                                                        <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 400 CHARACTERS ALLOWED..</span> */ ?>
                                                        <input type="hidden" name="hid_apprsub" id='hid_apprsub' value="<?=$sql_details[0]['APPRSUB']?>">
                                                        <textarea name="TEST1" id="FCKeditor1" >
                                                            <?  if($_REQUEST['action'] == 'edit') {
                                                                    if($sql_reqid[0]['APPRFOR'] == '1' or $sql_reqid[0]['APPRFOR'] == '2' or $sql_reqid[0]['APPRFOR'] == '3' or $sql_reqid[0]['APPRFOR'] == '4' or $sql_reqid[0]['APPRFOR'] == '5') {
                                                                        $filepathname = $sql_details[0]['APPRSUB'];
                                                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_details[0]['APPRSUB']);
                                                                        // $contents = fread($handle, filesize($filename));
                                                                        $contents = file_get_contents($filename);
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
                                                </label>
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





                                    <? if($sql_reqid[0]['RELAPPR'] != '' and $sql_reqid[0]['AGNSAPR'] != '') { ?>
                                        <!-- Reference Approvals & Tags -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Reference / Common / Against Approvals</strong></h3>
                                            </div>
                                            <div class="panel-body">
                                                <? if($sql_reqid[0]['RELAPPR'] != '') { ?>
                                                <!-- Reference / Common Approvals -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Reference / Common Approvals</label>
                                                    <div class="col-md-9 col-xs-12"> :
                                                        <?  $sql_rlapr = explode(",", $sql_reqid[0]['RELAPPR']);
                                                            for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                $sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST"); ?>
                                                                    <a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
                                                            <? } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Reference / Common Approvals -->
                                                <? } ?>

                                                <? if($sql_reqid[0]['AGNSAPR'] != '') { ?>
                                                <!-- Against Approval No -->
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Against Approval No</label>
                                                    <div class="col-md-9 col-xs-12"> :
                                                        <?  $sql_rlapr = explode(",", $sql_reqid[0]['AGNSAPR']);
                                                            for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                $sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST"); ?>
                                                                    <a target="_blank" href='print_request_test.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&agnpr=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
                                                            <? } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <? } ?>
                                            </div>
                                        <!-- Against Approval No --> 
                                        </div>
                                    <? } ?>


                                    <? $sql_approval_tags = select_query_json("select * from APPROVAL_TAGS
                                                                                        where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' and TAGSTAT = 'N'
                                                                                        order by TAGSRNO", "Centra", "TEST");
                                    if(count($sql_approval_tags) > 0) { ?>
                                        <!-- Related Approvals & Tags -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Approval Tags</strong></h3>
                                            </div>
                                            <div class="panel-body">
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
                                                <div class='clear clear_both' style="height: 10px;"></div>
                                            </div>
                                            <div class="tags_clear"></div>

                                        </div>
                                        <!-- Related Approvals & Tags -->
                                    <? } ?>


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
                                                        $sql_approval_levels = select_query_json("select req.REQDESN, req.REQESEN, req.APPRMRK, req.APRQVAL, req.APPFVAL, req.APPFRWD,
                                                                                                            req.REQDESC, req.REQESEC, req.REQSTBY, (select EMPNAME from employee_office
                                                                                                            where empsrno = req.REQSTBY) frmemp, (select EMPNAME from employee_office
                                                                                                            where empsrno = req.REQSTFR) toemp, (select BRNNAME from branch where brncode = req.brncode)
                                                                                                            BRNNAME, to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time
                                                                                                        from APPROVAL_REQUEST req
                                                                                                        where req.ARQSRNO != 1 and req.ARQCODE = '".$_REQUEST['reqid']."' and
                                                                                                            req.ARQYEAR = '".$_REQUEST['year']."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                                                                                            req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted = 'N' and
                                                                                                            req.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'
                                                                                                        order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", "TEST");
                                                        for($sql_approval_levelsi = 0; $sql_approval_levelsi < count($sql_approval_levels); $sql_approval_levelsi++) { ?>
                                                    <div style='border:1px dashed #A0A0A0; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background-color:#F0F0F0; margin-bottom: 5px;'>
                                                        <div class="form-group trbg" style='min-height: 30px; line-height: 30px; margin-right: 0px; margin-left: 0px; margin-bottom: 5px;'>
                                                            <div class="col-lg-9 col-xs-9">
                                                                <label style='height:27px; text-transform:uppercase' class="blue_clr"><b><?=$sql_approval_levels[$sql_approval_levelsi]['FRMEMP']?> : </b></label><label style='height:27px; text-align:right; font-size:9px; text-transform:uppercase'><?=$sql_approval_levels[$sql_approval_levelsi]['REQDESN']?>, <?=$sql_approval_levels[$sql_approval_levelsi]['REQESEN']?>. <? // $sql_approval_levels[$sql_approval_levelsi]['BRNNAME']?></label>
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
                                                                <? if($sql_approval_levels[$sql_approval_levelsi]['APRQVAL'] > 0) { ?>Approved Value &#8377; : <b class="red_clr"><?=moneyFormatIndia($sql_approval_levels[$sql_approval_levelsi]['APRQVAL'])?>.00</b>; <? } ?>Status : <? if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'N') { echo "<b class='green_clr'>NEW"; }
                                                                   if($sql_approval_levels[$sql_approval_levelsi]['APPFRWD'] == 'A') {
                                                                        if($sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 9 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 78 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 193 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 194 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 195) { // MD AUTHORIZED
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
                                                                        } elseif($sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 9 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 78 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 193 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 194 or $sql_approval_levels[$sql_approval_levelsi]['REQDESC'] == 195) { // MD AUTHORIZED
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
                                        </div>
                                        <!-- Approval Status & History -->

                                    </div>
                                  </div>
                              <table class="table table-bordered">
                                <? $sql_descode=select_query_json("select distinct ATYCODE , TARNUMB , APMNAME , TOPCORE , SUBCORE , ENTSRNO from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' order by ENTSRNO", "Centra", "TEST");
                                                    if(count($sql_descode) > 0) { ?>
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
                                  }
                                    $sno = '';
                                    foreach($sql_descode as $sectionrow) {
                                      $sno = $sno + 1;
                                  ?>
                                    <tr class="active">
                                      <td><?echo $sno;?></td>
                                      <td><?
                                      $sql_descodee=select_query_json("SELECT ATYCODE , ATYNAME FROM APPROVAL_TYPE WHERE DELETED = 'N' and ATYCODE = '".$sectionrow['ATYCODE']."' ORDER BY ATYCODE", "Centra", "TEST");
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
                                            group by bpl.tarnumb, bpl.depcode, bpl.brncode, tar.ptdesc, dep.depname order by Depname", "Centra", "TEST");
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
                                        $sql_descode=select_query_json("SELECT ATCNAME from APPROVAL_TOPCORE where ATCCODE = '".$sectionrow['TOPCORE']."' and DELETED = 'N' ORDER BY ATCSRNO", "Centra", "TEST");
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
                                            where ESECODE = '".$sectionrow['SUBCORE']."' and sec.esecode = apm.subcore and apm.topcore = atc.atccode and atc.deleted = 'N' and sec.deleted = 'N'
                                            order by ESENAME asc", "Centra", "TEST");
                                        foreach($sql_descode as $sectionroww) {
                                          echo $sectionroww['ESENAME'];
                                        }
                                      }
                                      ?></td>

                                      <td>
                                        <?
                                          $sql_descode=select_query_json("select  EMPCODE , EMPNAME from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' AND ENTSRNO = '".$sectionrow['ENTSRNO']."' ORDER BY BRNHDSR", "Centra", "TEST");
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


                        <!-- Supplier Quotation -->
                        <div id='id_supplier' style="margin: 0px 5px; text-align: center;">
                        <? $sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST
                                                                    where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'", 'Centra', "TEST");
                            if(count($sql_prdlist) > 0) { ?>
                                <div class="parts3 fair_border">
                                <div class="row" style="margin: 0px 5px; min-height: 25px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
                                    <div class="col-sm-1 colheight" style="padding: 0px; border-top-left-radius:5px;"># / Product Image</div>
                                    <div class="col-sm-3 colheight" style="padding: 0px;">Product / Sub Product / Spec.</div>
                                    <div class="col-sm-3 colheight" style="padding: 0px;">Advt. Product & Due Date Details</div>
                                    <div class="col-sm-1 colheight" style="padding: 0px;">Qty</div>
                                    <div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>
                                    <div class="col-sm-1 colheight" style="padding: 0px;">Tax</div>
                                    <div class="col-sm-1 colheight" style="padding: 0px;">Discount %</div>
                                    <div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>
                                </div>
                        <?  }

                            $inc = 0;
                            foreach($sql_prdlist as $prdlist) { $inc++;
                                $stat_rej = 0; $styl_bg = "#FFFFFF";
                                if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') {
                                    $stat_rej = 1; $styl_bg = "#ffd9d9";
                                }
                                // if($stat_rej == 0) {
                                $sql_slt_prdquotlist = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
                                                                                    where PBDCODE = '".$prdlist['PBDCODE']."' and PBDYEAR = '".$prdlist['PBDYEAR']."'
                                                                                        and PBDLSNO = '".$prdlist['PBDLSNO']."' and SLTSUPP = 1", 'Centra', "TEST"); ?>
                                <div class="row" style="margin: 0px 5px; min-height: 25px; display: flex; text-transform: uppercase; text-align: center; background-color: <?=$styl_bg?>">
                                    <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                        <div class="fg-line">&nbsp;<?=$inc?><br>
                                            <div><? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                if($prdlist['PRDIMAG'] != '-' and $prdlist['PRDIMAG'] != '') {
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
                                                }
                                              echo "</ul>"; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3 colheight" style="padding: 1px 3px; text-align: left;">
                                        <div style="clear: both;"></div>
                                        <div title="Sub-Product" style="width: 59%; float: left;">
                                            <?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?>
                                        </div>
                                        <div title="Unit" style="width: 39%; float: left;margin-left: 2px;">
                                            <?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?>
                                        </div>
                                        <div style="clear: both;"></div>

                                        <div title="Sub-Product" style="width: 59%; float: left;">
                                            <?=$prdlist['PRDSPEC']?>
                                        </div>
                                        <div title="Unit" style="width: 39%; float: left;margin-left: 2px;">
                                            <? if($prdlist['UNTNAME'] == '') { echo " - "; } else { echo $prdlist['UNTNAME']; } ?>
                                        </div>
                                        <div style="clear: both; height: 1px;"></div>

                                        <? if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') { ?>
                                            <img src="images/rejected.png" style="border: 0px;">
                                        <? } ?>
                                    </div>

                                    <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                        <div style="width: 49%; float: left;" title="Ad. Duration">
                                            <? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "Ad. Duration : ".$prdlist['ADURATI']; } ?>
                                        </div>
                                        <div style="width: 49%; float: left; margin-left: 2px;" title="Ad. Print Location">
                                            <? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "Ad. Print Location : ".$prdlist['ADLOCAT']; } ?>
                                        </div>
                                        <div style="clear: both;"></div>

                                        <div style="width: 49%; float: left;" title="Ad. Size Length">
                                            <? if($prdlist['ADLENGT'] == '0') { echo ""; } else { echo "Ad. Size Length : ".$prdlist['ADLENGT']; } ?>
                                        </div>
                                        <div style="width: 49%; float: left; margin-left: 2px;" title="Ad. Size width">
                                            <? if($prdlist['ADWIDTH'] == '0') { echo ""; } else { echo "Ad. Size width : ".$prdlist['ADWIDTH']; } ?>
                                        </div>
                                        <div style="clear: both;"></div>

                                        <div style="width: 100%; float: left;">
                                            <? if($prdlist['PRDDEDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($prdlist['PRDDEDT']))); } ?>
                                        </div>
                                        <div style="clear: both; height: 1px;"></div>
                                        <div style="width: 100%; float: left;">
                                            <? if($prdlist['PRDEDDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($prdlist['PRDEDDT']))); } ?>
                                        </div>
                                        <div style="clear: both; height: 1px;"></div>
                                    </div>

                                    <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                        <?=$prdlist['TOTLQTY']?>
                                    </div>

                                    <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;">
                                        <? $expl1 = explode(".", $sql_slt_prdquotlist[0]['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?>
                                    </div>
                                    <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
                                        <div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_<?=$inc?>"> <? $expl1 = explode(".", $sql_slt_prdquotlist[0]['SGSTVAL']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; }  ?> </div>
                                        <div style="clear: both;"></div>
                                        <div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_<?=$inc?>"> <? $expl1 = explode(".", $sql_slt_prdquotlist[0]['CGSTVAL']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; }  ?> </div>
                                        <div style="clear: both;"></div>
                                        <div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_<?=$inc?>"> <? $expl1 = explode(".", $sql_slt_prdquotlist[0]['IGSTVAL']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?> </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                    <div class="col-sm-1 colheight" style="padding: 1px 0px; text-align: left; padding-left: 2px;">
                                        <div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC. : </div><div style="float: left; width: 50%;" id="id_sltdisc_<?=$inc?>"> <?=moneyFormatIndia($sql_slt_prdquotlist[0]['DISCONT'])?> </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                    <div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px; text-align: center; padding-left: 2px;" id="id_sltamnt_<?=$inc?>">
                                        <?=moneyFormatIndia($sql_slt_prdquotlist[0]['NETAMNT'])?>
                                    </div>
                                </div>

                                <div class="row" style="margin: 0px 5px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold; text-align: center;">
                                    <div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>
                                    <!-- Quotation -->
                                    <div class="col-sm-10 colheight" style="padding: 0px; border-top-left-radius:5px;">
                                        <div class="fair_border" style="padding-left: 0px;">
                                            <div class="row" style="margin-right: -10px; min-height: 25px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">
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

                                <div class="row" style="margin: 0px 5px; min-height: 25px; display: flex; text-transform: uppercase; text-align: center; background-color: <?=$styl_bg?>">
                                    <div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>
                                    <div class="col-sm-10 colheight" style="padding-left: 0px;">
                                        <!-- Quotation -->
                                        <div class="parts3_1 fair_border">
                                            <?  $sql_prdquotlist = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
                                                                                            where PBDCODE = '".$prdlist['PBDCODE']."' and PBDYEAR = '".$prdlist['PBDYEAR']."'
                                                                                                and PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', "TEST");
                                                $inc1 = 0;
                                                foreach($sql_prdquotlist as $prdquotlist) { $inc1++;
                                                    $selected_supplier = ""; $slttext = '';
                                                    if($prdquotlist['SLTSUPP'] == 1) {
                                                        if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') {
                                                            $selected_supplier = "background-color: #ffd9d9; border: 1px solid #FF0000;";
                                                        } else {
                                                            $selected_supplier = "background-color: #fff2e0; border: 1px solid #FF0000;";
                                                        }

                                                        // $slttext = 'Selected Supplier';
                                                        $slttext = '1';
                                                    }

                                                    if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') {
                                                        $gridclr = "#ffd9d9";
                                                    } else {
                                                        $gridclr = "#e6e6e6";
                                                    }
                                                    if($inc1 % 2 == 0) { if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') { $gridclr = "#ffd9d9"; } else { $gridclr = "#f7f7f7"; } }
                                                    if($prdquotlist['SLTSUPP'] == 1) { if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') { $gridclr = "#ffd9d9"; } else { $gridclr = "#fff2e0"; } }
                                                    ?>
                                                    <div class="row" style="margin-right: -10px; background-color: <?=$gridclr?>; display: flex; <?=$selected_supplier?>" onMouseover="this.style.background='#d0cfcf'; this.style.color='#000000';" onmouseout="this.style.backgroundColor='<?=$gridclr?>'; this.style.color='#000000';">
                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            <div class="fg-line"><?=$inc1?><br><b><? // $slttext?></b><? if($prdlist['REJUSER'] != '' and $prdlist['REJRESN'] != '') { } elseif($slttext == '1') { ?><img src="images/seal4.png" style="border: 0px; width: 100%; height: 100%; max-width: 70px;"><? } ?></div>
                                                        </div>

                                                        <div class="col-sm-3 colheight" style="padding: 1px 3px; text-align: left;">
                                                            <?=$prdquotlist['SUPCODE']." - ".$prdquotlist['SUPNAME']?>
                                                        </div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            <?=$prdquotlist['DELPRID']?>
                                                        </div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            <b><? $expl1 = explode(".", $prdquotlist['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; }  ?></b><br>
                                                            <span style="font-size: 10px;">Adv. Amount Value : <? $expl2 = explode(".", $prdquotlist['ADVAMNT']); echo moneyFormatIndia($expl2[0]); if($expl2[1] != '') { echo ".".$expl2[1]; } ?></span>
                                                        </div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            SGST : <?=$prdquotlist['SGSTVAL']?><br>
                                                            CGST : <?=$prdquotlist['CGSTVAL']?><br>
                                                            IGST : <?=$prdquotlist['IGSTVAL']?>
                                                        </div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            <? if($prdquotlist['SPLDISC'] != '' and $prdquotlist['SPLDISC'] > 0) { ?>
                                                                SPL. DISC. : <?=moneyFormatIndia($prdquotlist['SPLDISC'])?><br>
                                                                PIECELESS : <?=moneyFormatIndia($prdquotlist['PIECLES'])?><br>
                                                            <? } ?>
                                                            DISCOUNT : <?=moneyFormatIndia($prdquotlist['DISCONT'])?>
                                                        </div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;"><?=moneyFormatIndia($prdquotlist['NETAMNT'])?></div>

                                                        <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                            <!-- Uploaded Image -->
                                                            <? // $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'othersupdocs'", 'Centra', "TEST"); ?>
                                                                <div class='clear clear_both' style='min-height:10px;'></div>
                                                                <div><?
                                                                  echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                                  /* for($ij = 0; $ij < count($sql_docs); $ij++) {
                                                                    $filename = $sql_docs[$ij]['APRDOCS'];
                                                                    $dataurl = $sql_docs[$ij]['APRHEAD'];
                                                                    $exp = explode("_", $filename); */
                                                                    if($prdquotlist['QUOTFIL'] != '-') {
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
                                                                    }
                                                                  echo "</ul>"; ?>
                                                            </div>
                                                            <div class='clear clear_both'></div>
                                                            <!-- Uploaded Image -->
                                                        </div>

                                                        <div class="col-sm-2 colheight" style="padding: 1px 0px;"><?=$prdquotlist['SUPRMRK']?></div>
                                                    </div>
                                                <div class='clear'></div>
                                            <? } ?>

                                        </div>
                                        <!-- Quotation -->

                                    </div>
                                    <div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px; background-color: #FFFFFF;"></div>
                                <div class='clear'></div>
                                </div>
                                <div class='clear'></div>

                            <? } ?>

                                </div>
                                <div class='clear'></div>

                            </div>
                            <!-- ----Supplier Quotation -->

                            <div class="tags_clear"></div>
                            <div class="tags_clear"></div>
                            <div class='clear'></div>


                            <? /* <div>
                              <table class="table table-bordered">
                                <thead>
                                  <tr style="background:#666666 !important">
                                    <th class="text-center">#</th>
                                    <th class="text-center">BRANCH CODE</th>
                                    <th class="text-center">BRANCH NAME</th>
                                    <th class="text-center">NUMBER OF EMPLOYEE</th>
                                    <th class="text-center">VALUE</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                              <?
                              $sql_project_branch = select_query_json("SELECT * from approval_branch_detail bd, approval_branch_list bl where bd.BRNCODE = bl.BRNCODE and bd.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'","Centra","TEST");
                              //echo "SELECT * from approval_branch_detail where APRNUMB = ".$sql_reqid[0]['APRNUMB']."","Centra","TEST";
                              for($project_i = 0; $project_i < count($sql_project_branch); $project_i++) {?>
                                  <tr>
                                    <td><?echo $project_i + 1;?></td>

                                    <td><?=$sql_project_branch[$project_i]['BRNCODE']?><input type="hidden" class="text-center form-control" name="txtesi_brncode[]" value="<?=$sql_project_branch[$project_i]['BRNCODE']?>" placeholder="" style="border:none;background: #f5f5f5 !important;color:#000"/></td>

                                    <td><?=$sql_project_branch[$project_i]['BRNNAME']?></td>
                                    <td><?=$sql_project_branch[$project_i]['NOFEMPL']?>
                                    </td>
                                    <td><?=$sql_project_branch[$project_i]['APRAMNT']?></td>
                                  </tr>
                              <? } ?>
                              </tbody>
                            </table>
                            </div> */ ?>
							
                         <div class="row">
						  <div class="form-group">
							<div class="col-md-12">

								<form class="form-horizontal">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><strong>POLICY APPROVAL</strong></h3>
										<ul class="panel-controls">
											<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
										</ul>
									</div>


									<div class="panel-body">

                                  
										
											
												<div class="col-md-6">
											   <div class="form-group">
													<label class="col-md-3 control-label" >POLICY SUBJECT<span style='color:red'>*</span></label>
													<div class="col-md-9 col-xs-12">
													 <? $sql_emp = select_query_json("select PLCSUBJ from approval_policy_form app  where app.aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');
																					    if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_emp[0]['PLCSUBJ']." ";
                                                            } else { ?>	
														<input type="text" class="form-control " autofocus tabindex='1' required name='txtdynamic_subject' id='txtdynamic_subject' value='-' data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
														
															<?}?>
														
													</div>
											</div>
												
												<!-- priority filed drop down -->
												
												
												<div class="form-group">
													<label class="col-md-3 control-label">EFFECTIVE DATE<span style='color:red'>*</span></label>
													<div class="col-md-9 col-xs-12">
                                                        <? $sql_emp=select_query_json("select EFCTDAT from approval_policy_form app where  aprnumb = '".$sql_reqid[0]['APRNUMB']."'","centra",'TEST'); 
														if($_REQUEST['action'] == 'view') {
                                                                  echo ": ";
                                                                if($sql_emp[0]['EFCTDAT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_emp[0]['EFCTDAT']))); } else { echo strtoupper(date("d-M-Y")); }
                                                           } else { ?>
                                                                <input type="text" tabindex='24' name="impldue_date" id="tar_date" class="form-control" required readonly placeholder='Implementation Due Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_emp[0]['EFCTDAT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_emp[0]['EFCTDAT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Implementation Due Date'>
                                                        <? } ?>
                                                    </div>
																</div>
																
												
												<!-- tilte text feild -->
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">VALID UPTO<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                       
                                                       <?   $sql_emp = select_query_json("select VALDUPT
                                                                                       from approval_policy_form  where  aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');   
														if($_REQUEST['action'] == 'view') {
                                                                   echo ": ";
                                                                if($sql_emp[0]['VALDUPT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_emp[0]['VALDUPT']))); } else { echo strtoupper(date("d-M-Y")); }
                                                                } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="due_date" id="due_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='' data-toggle="tooltip" data-placement="top" placeholder="To Date" title=""  >
                                                     <? } ?>
                                                    </div>
                                                </div>
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">APPROVAL DATE<span style='color:red'>*</span></label>
                                                      <div class="col-md-9 col-xs-12">
                                                        <?  $sql_emp = select_query_json("select APPRDAT 
                                                                                       from approval_policy_form  where  aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST'); 
														if($_REQUEST['action'] == 'view') {
                                                                 echo ": ";
                                                                if($sql_emp[0]['APPRDAT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_emp[0]['APPRDAT']))); } else { echo strtoupper(date("d-M-Y")); }
                                                           } else { ?>
                                                                <input type="text" tabindex='24' name="impldue_date" id="app_date" class="form-control" required readonly placeholder='Implementation Due Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_emp[0]['APPRDAT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_emp[0]['APPRDAT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Implementation Due Date'>
                                                        <? } ?>
                                                    </div>
                                                </div>
												<div class="form-group">
                            <label class="col-md-3 control-label">USERLIST </label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                   <?  $sql_emp = select_query_json("select   emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, des.DESNAME
                                                                                                  from employee_office emp, empsection sec, designation des,approval_policy_form app 
                                                                                                  where  app.aprnumb = '".$sql_reqid[0]['APRNUMB']."' and emp.EMPCODE=app.USERLST and emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE 
                                                                                                 ", "Centra", 'TEST');  
                                                   if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> -".$sql_emp[0]['DESNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>																							 
									<input type="text"  class="form-control "  tabindex='6' style="text-transform: uppercase;" required readonly name='txtdynamic_userlist' id='txtdynamic_userlist' data-toggle="tooltip" data-placement="top" tabindex="10" data-original-title="USER LIST"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'onfocus="call_dynamic_option()" onblur="call_dynamic_option()">     
                                    <?  } ?>     					    
                            </div>
							</div>
                        
												
										<? $sql_search_attachment = select_query_json("select APRNUMB, DESKPRO attachments from approval_policy_form
                                                                                                where  aprnumb = '".$sql_reqid[0]['APRNUMB']."'
                                                                                                ", "Centra", 'TEST');
                                                if(count($sql_search_attachment) > 0) { ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">DESK PROCEDURE</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="Approval policy" tabindex='12' class="form-control fileselect" name='attachments[]' id='attachments' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Approval Supporting Documents"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                        <? } ?>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                        <div><?  echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
                                                          
                                            for($k = 0; $k < count($sql_search_attachment); $k++) {
                                                $filename = $sql_search_attachment[$k]['ATTACHMENTS'];
                                                $dataurl = $sql_search_attachment[$k]['DESKPRO']."";
												echo $dataurl;
                                                $exp = explode(".", $filename);
                                                switch($exp[1])
                                                {
                                                    case 'jpg':
                                                    case 'jpeg':
                                                    case 'png':
                                                            //$folder_path = "approval_desk/requirement_entry/".$dataurl."/";
                                                            //echo $fieldindi = "<br><li data-responsive=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                            break;
                                                    default:
                                                            echo $fieldindi = "<li><br><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/text_approval_policy/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a></li>";
                                                            break;

                                                }
                                            }
											for($k = 0; $k < count($sql_search_attachment); $k++) {
                                                $filename = $sql_search_attachment[$k]['ATTACHMENTS'];
                                                $dataurl = $sql_search_attachment[$k]['DESKPRO']."";
                                                $exp = explode(".", $filename);
                                                switch($exp[1])
                                                {
                                                    case 'jpg':
                                                    case 'jpeg':
                                                    case 'png':
                                                            $folder_path = "approval_desk/text_approval_policy/".$dataurl."/";
                                                            echo $fieldindi = "<br><li data-responsive=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folderpath."\" data-src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                            break;
													case 'pdf':
                                                            $folder_path = "approval_desk/text_approval_policy/".$dataurl."/";
															
                                                           // echo $fieldindi = "<br><li data-responsive=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100px; height:100px;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view_pdf.php?pic=".$filename."&path=".$folder_path."\"></li>";
                                                            break;

                                                    default:
                                                            //echo $fieldindi = "<li><br><a href=\"ftp_image_view_pdf.php?pic=".$filename."&path=approval_desk/text_approval_policy/".$dataurl."/\" target=\"_blank\" class=\"style_box\">".$filename."</a></li>";
                                                            break;
                                                }
                                            }
                                            echo "</ul>";
									
                                    // }
                                        ?>
												</div>

                                                          
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <?} ?>
                                                    
                                                <div class="tags_clear"></div>
                                                <!-- Approval Supporting Documents -->

                                               
												
                                                <!-- Approval Supporting Documents -->
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">POLICY DOCUMENTS<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
													 <? $sql_emp = select_query_json("select PLCATTC
                                                                                       from approval_policy_form where  aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');
																					    if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_emp[0]['PLCATTC']." ";
                                                            } else { ?>	
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required readonly name='txtdynamic_policy_docs' id='txtdynamic_policy_docs' data-toggle="tooltip" data-placement="top" data-original-title="POLICY DOCUMENTS" value='<?=$sql_emp[0]['POLIDOC']?>'>
															<?}?>
													</div>
                                                </div>
												
												</DIV>
												<!-- Approval Supporting Documents -->
                                               



<!--fix me-->
											<div class="col-md-6">
												<!-- core drop down -->
												<div class="form-group">
													<label class="col-md-3 control-label">POLICY TYPE</label>
													<div class="col-md-9">
													 <? $sql_emp = select_query_json("select  PLCYTYP 
                                                           from approval_policy_form  where aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');
																					   if($_REQUEST['action'] == 'view') {
                                                               echo ": ".$sql_emp[0]['PLCYTYP']." ";
                                                            } else { ?>	
														<input type="text" class="form-control policy_approval_required" required readonly name='txtdynamic_policy_type' id='txtdynamic_policy_type'value='<?=$sql_emp[0]['POLTYPE']?>' data-toggle="tooltip" data-placement="top" data-original-title="POLICY TYPE">
															<?}?>
													</div>

												</div>
												<!--assign member-->

												<div class="form-group">
                            <label class="col-md-3 control-label">CREATOR ECNO / NAME</label>
                            <div class="col-md-9 col-xs-12"
                                <b>
								 <? $sql_emp = select_query_json("select emp.EMPCODE,emp.EMPNAME
                                                                                       from approval_policy_form app,employee_office emp  where app.aprnumb = '".$sql_reqid[0]['APRNUMB']."' and emp.EMPCODE=app.CRTECNO ", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  
                                    if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME'].substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>	 
                                  <input type="text" class="form-control policy_approval_required" style="text-transform: uppercase;" required readonly name='txtdynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
															<?}?>				   
												  
												 
							              
                            </div>
                        </div>

												<!-- Tag layout -->
												<div class="form-group">
                            <label class="col-md-3 control-label">CO-ORDINATOR ECNO / NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
								<? $sql_emp = select_query_json("select emp.EMPCODE,emp.EMPNAME
                                                                                       from approval_policy_form app,employee_office emp  where app.aprnumb = '".$sql_reqid[0]['APRNUMB']."' and emp.EMPCODE=app.CRDECNO ", "Centra", 'TEST');
																					   if($_REQUEST['action'] == 'view') {
                                                               echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME'].substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>	
                                  <input   class="form-control policy_approval_required" style="text-transform: uppercase;" required readonly name='txtdynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="COORDINATOR"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
															<?}?>			   
										
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-3 control-label">ASSIST BY ECNO / NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
								 <? $sql_emp = select_query_json("select emp.EMPCODE,emp.EMPNAME
                                                                                       from approval_policy_form app,employee_office emp  where app.aprnumb = '".$sql_reqid[0]['APRNUMB']."'and emp.EMPCODE=app.ASTECNO ", "Centra", 'TEST');
																					   if($_REQUEST['action'] == 'view') {
                                                               echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME'].substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>	
                                  <input type="text" class="form-control policy_approval_required" style="text-transform: uppercase;" required readonly name='txtdynamic_asistby' id='txtdynamic_asistby' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
															<?}?>				  
                            </div>
                        </div>
										</div>
										<div class="tags_clear"></div>
										<div class="row">
										<div class="col-md-6"></div>
										<div class="col-md-6">
												<!-- topcore drop down ValidateSingleInput(this, 'all'); -->
											<div class="row">
											<div class="col-md-12">
											   <div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-9" name="tags" id="id_tags_generation">
													</div>
												</div>
                                            </div>
										</div></div>
										<div class="tags_clear"></div>
											 <div class="col-md-6">
  
                                        <div class="tags_clear"></div>
                                        <div class="form-group">
                                           <? $sql_details = select_query_json("Select PLCDATA from APPROVAL_POLICY_FORM app
																					 where app.aprnumb = '".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST"); ?>
                                            <label class="col-md-3 control-label" style="text-align: left;">DETAILS <span style='color:red'>*</span> : </label>
                                           <div class="tags_clear height10px"></div>
                                            <div class="col-md-12" style="border: 1px solid #dadada; padding: 5px !important;">
                                               <?  if($_REQUEST['action'] == 'view') {
                                                        if($sql_reqid[0]['APPRFOR'] == '1' ) {
                                                            $filepathname = $sql_details[0]['PLCDATA'];
                                                            $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_policy/".$filepathname;
                                                            $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_details[0]['APPRSUB']);
                                                            // $contents = fread($handle, filesize($filename));
                                                            $contents = file_get_contents($filename);
                                                            fclose($handle);
                                                            echo closetags($contents);
                                                            // echo $string = preg_replace("/<span[^>]+\>/i", "", closetags($contents));
                                                        } else {
                                                            echo $sql_reqid[0]['APPRDET'];
                                                        }
                                                   } else { ?>
                                                        <? /* <textarea class="form-control" <? if($_REQUEST['action'] != 'edit') { } ?> tabindex='17' rows="10" placeholder="Details" required maxlength='400' name='txtdetails' id='txtdetails' data-toggle="tooltip" data-placement="top" title="Details" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"><? echo $sql_reqid[0]['APPRDET']; ?></textarea>
                                                        <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 400 CHARACTERS ALLOWED..</span> */ ?>
                                                      <input type="hidden" name="hid_apprsub" id='hid_apprsub' value="<?=$sql_details[0]['APPRSUB']?>">
                                                
                                                            <?  if($_REQUEST['action'] == 'edit') {
                                                                    if($sql_reqid[0]['APPRFOR'] == '1' ) {
                                                                        $filepathname = $sql_details[0]['PLCDATA'];
                                                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_policy/".$filepathname;
                                                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_details[0]['APPRSUB']);
                                                                        // $contents = fread($handle, filesize($filename));
                                                                        $contents = file_get_contents($filename);
                                                                        fclose($handle);
                                                                        echo $contents;
                                                                    } else {
                                                                        echo $sql_reqid[0]['APPRDET'];
                                                                    }
                                                                }?>
                                                            
                                                     
                                                        <script type="text/javascript">
                                                        var ckedit=CKEDITOR.replace("FCKeditor2",
                                                        {
                                                            height:"450", width:"100%",
                                                            filebrowserBrowseUrl : '/ckeditor/ckfinder/ckfinder.html',
                                                            filebrowserImageBrowseUrl : '/ckeditor/ckfinder/ckfinder.html?Type=Images',
                                                            filebrowserUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                            filebrowserImageUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
                                                        });
                                                        </script>
                                                <? } ?>
                                            </div>
                                        </div>
										</div>
													 

                            </div>

                                    </div>


                            </div>
							
                            <div class="tags_clear">&nbsp;</div>
                            <div class="panel-footer">
                                <div class="tags_clear">&nbsp;</div>
                                <a href='waiting_approval.php' class='btn btn-warning pull-right'><i class="fa fa-refresh"></i> Back</a>
                                <div class="tags_clear">&nbsp;</div>
                            </div>
                            <div class="tags_clear">&nbsp;</div>
                        </div>
                        <div class="tags_clear">&nbsp;</div>
                        </form>



            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>


    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:90%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body1" style="overflow: scroll;"><div id="load_page" style='display:block;padding:12% 40%;'></div></div>
            </div>
        </div>
    </div>
    <div class='clear'></div>


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

        dynamic_template_load('<?=$_REQUEST['reqid']?>', '<?=$_REQUEST['year']?>', '<?=$_REQUEST['rsrid']?>', '<?=$_REQUEST['creid']?>', '<?=$_REQUEST['typeid']?>');
        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                $(wrapper).append("<div style='padding-top:5px;'>"+
    "<a href='#' class='remove_field' title='Remove'><i class='fa fa-times-circle' style='color:#FF0000'></i></a>"+
"<table>"+
"<tr class='odd gradeX'>"+
    "<td style='width:7%;'>"+
    "<input type='text' name='txt_material[]' id='txt_material' value='<?=$sql_reqd[$rqdi]['PRDNAME']?>' class='form-control' placeholder='Material' data-toggle='tooltip' data-placement='top' data-original-title='Material' title='Material' maxlength='50' /><input type='hidden' name='txt_group[]' id='txt_group' value='"+x+"' class='form-control' maxlength='2' />"+

    "<input type='hidden' name='hid_atycode[]' id='hid_atycode' value='<?=$sql_reqd[$rqdi]['ATYCODE']?>' class='form-control' />"+
    "<input type='hidden' name='hid_rqdgrup[]' id='hid_rqdgrup' value='<?=$sql_reqd[$rqdi]['RQDGRUP']?>' class='form-control' />"+

    "</td>"+
    "<td>"+
        "<table class='table table-striped table-bordered table-hover' style='margin-bottom:0px; border:0px;'>"+
        "<thead>"+
            "<tr>"+
                "<td style='padding:0px; border:0px;width:4%;'>"+
                    "<select class='form-control' tabindex='30' required name='txt_sup1_unit[]' id='txt_sup1_unit_<?=$rqdi?>' data-toggle='tooltip' data-placement='top' title='Unit.'>"+
                        "<?  for($uniti = 0; $uniti < count($sql_unit); $uniti++) { ?>"+
                            "<option value='<?=$sql_unit[$uniti]['UNTCODE']?>' <? if($unit1[0]['UNTNAME'] == $sql_unit[$uniti]['UNTCODE']) { ?> selected <? } ?>><?=$sql_unit[$uniti]['UNTNAME']?></option>"+
                        "<? } ?>"+
                    "</select>"+
                "</td>"+

                "<td style='padding:0px; border:0px;width:4%;'><input type='text' name='txt_sup1_qty[]' id='txt_sup1_qty' value='<?=$sql_reqd[$rqdi]['PRDQTY1']?>' class='form-control' placeholder='Qty.' data-toggle='tooltip' data-placement='top' data-original-title='Qty.' title='Qty.' onKeyPress='return isNumber(event)' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px;width:4%;'><input type='text' name='txt_sup1_value[]' id='txt_sup1_value' value='<?=$sql_reqd[$rqdi]['PRDVALU1']?>' class='form-control' placeholder='Value.' data-toggle='tooltip' data-placement='top' data-original-title='Value.' onKeyPress='return isNumber(event)' title='Value.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='31' name='txt_sup1_discount[]' id='txt_sup1_discount' value='<?=$sql_reqd[$rqdi]['PRDDISC1']?>' class='form-control' placeholder='Discount' data-toggle='tooltip' data-placement='top' data-original-title='Discount' onKeyPress='return isNumber(event)' title='Discount' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='31' name='txt_sup1_tax[]' id='txt_sup1_tax' value='<?=$sql_reqd[$rqdi]['PRDTAX1']?>' class='form-control' placeholder='Tax' data-toggle='tooltip' data-placement='top' data-original-title='Tax' onKeyPress='return isNumber(event)' title='Tax' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px;width:5%;'><input type='text' name='txt_sup1_price[]' id='txt_sup1_price' value='<? echo $supplier1_price = calculate_total($sql_reqd[$rqdi]['PRDQTY1'], $sql_reqd[$rqdi]['PRDVALU1'], $sql_reqd[$rqdi]['PRDDISC1'], $sql_reqd[$rqdi]['PRDTAX1']); ?>' class='form-control' placeholder='Price' onKeyPress='return isNumber(event)' data-toggle='tooltip' data-placement='top' data-original-title='Price' title='Price' maxlength='6' style='background-color:<?=$high_light?>' /></td>"+
            "</tr>"+
        "</thead>"+
        "</table>"+
    "</td>"+
    "<td>"+

        "<table class='table table-striped table-bordered table-hover' style='margin-bottom:0px; border:0px;'>"+
        "<thead>"+
            "<tr>"+
                "<td style='padding:0px; border:0px;width:4%;'>"+
                    "<select class='form-control' tabindex='33' required name='txt_sup2_unit[]' id='txt_sup2_unit_<?=$rqdi?>' data-toggle='tooltip' data-placement='top' title='Unit.'>"+
                        "<?  for($uniti = 0; $uniti < count($sql_unit); $uniti++) { ?>"+
                            "<option value='<?=$sql_unit[$uniti]['UNTCODE']?>' <? if($unit2[0]['UNTNAME'] == $sql_unit[$uniti]['UNTCODE']) { ?> selected <? } ?>><?=$sql_unit[$uniti]['UNTNAME']?></option>"+
                        "<? } ?>"+
                    "</select>"+
                "</td>"+

                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup2_qty[]' id='txt_sup2_qty' value='<?=$sql_reqd[$rqdi]['PRDQTY2']?>' class='form-control' placeholder='Qty.' data-toggle='tooltip' data-placement='top' data-original-title='Qty.' title='Qty.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup2_value[]' id='txt_sup2_value' value='<?=$sql_reqd[$rqdi]['PRDVALU2']?>' class='form-control' placeholder='Value.' data-toggle='tooltip' data-placement='top' data-original-title='Value.' title='Value.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='34' name='txt_sup2_discount[]' id='txt_sup2_discount' value='<?=$sql_reqd[$rqdi]['PRDDISC2']?>' class='form-control' placeholder='Discount' data-toggle='tooltip' data-placement='top' data-original-title='Discount' onKeyPress='return isNumber(event)' title='Discount' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='34' name='txt_sup2_tax[]' id='txt_sup2_tax' value='<?=$sql_reqd[$rqdi]['PRDTAX2']?>' class='form-control' placeholder='Tax' data-toggle='tooltip' data-placement='top' data-original-title='Tax' onKeyPress='return isNumber(event)' title='Tax' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px; width:5%;'><input type='text' name='txt_sup2_price[]' id='txt_sup2_price' value='<? echo $supplier2_price = calculate_total($sql_reqd[$rqdi]['PRDQTY2'], $sql_reqd[$rqdi]['PRDVALU2'], $sql_reqd[$rqdi]['PRDDISC2'], $sql_reqd[$rqdi]['PRDTAX2']); ?>' class='form-control' placeholder='Price' onKeyPress='return isNumber(event)' data-toggle='tooltip' data-placement='top' data-original-title='Price' title='Price' maxlength='6' style='background-color:<?=$high_light?>' /></td>"+
            "</tr>"+
        "</thead>"+
        "</table>"+
    "</td>"+
    "<td>"+

        "<table class='table table-striped table-bordered table-hover' style='margin-bottom:0px; border:0px;'>"+
        "<thead>"+
            "<tr>"+
                "<td style='padding:0px; border:0px;width:4%;'>"+
                    "<select class='form-control' tabindex='33' required name='txt_sup3_unit[]' id='txt_sup3_unit_<?=$rqdi?>' data-toggle='tooltip' data-placement='top' title='Unit.'>"+
                        "<?  for($uniti = 0; $uniti < count($sql_unit); $uniti++) { ?>"+
                            "<option value='<?=$sql_unit[$uniti]['UNTCODE']?>' <? if($unit3[0]['UNTNAME'] == $sql_unit[$uniti]['UNTCODE']) { ?> selected <? } ?>><?=$sql_unit[$uniti]['UNTNAME']?></option>"+
                        "<? } ?>"+
                    "</select>"+
                "</td>"+

                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup3_qty[]' id='txt_sup3_qty' value='<?=$sql_reqd[$rqdi]['PRDQTY3']?>' class='form-control' placeholder='Qty.' data-toggle='tooltip' data-placement='top' data-original-title='Qty.' title='Qty.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup3_value[]' id='txt_sup3_value' value='<?=$sql_reqd[$rqdi]['PRDVALU3']?>' class='form-control' placeholder='Value.' data-toggle='tooltip' data-placement='top' data-original-title='Value.' title='Value.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='37' name='txt_sup3_discount[]' id='txt_sup3_discount' value='<?=$sql_reqd[$rqdi]['PRDDISC3']?>' class='form-control' placeholder='Discount' data-toggle='tooltip' data-placement='top' data-original-title='Discount' onKeyPress='return isNumber(event)' title='Discount' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='37' name='txt_sup3_tax[]' id='txt_sup3_tax' value='<?=$sql_reqd[$rqdi]['PRDTAX3']?>' class='form-control' placeholder='Tax' data-toggle='tooltip' data-placement='top' data-original-title='Tax' onKeyPress='return isNumber(event)' title='Tax' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px; width:5%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup3_price[]' id='txt_sup3_price' value='<? echo $supplier3_price = calculate_total($sql_reqd[$rqdi]['PRDQTY3'], $sql_reqd[$rqdi]['PRDVALU3'], $sql_reqd[$rqdi]['PRDDISC3'], $sql_reqd[$rqdi]['PRDTAX3']); ?>' class='form-control' placeholder='Price' data-toggle='tooltip' data-placement='top' data-original-title='Price' title='Price' maxlength='6' style='background-color:<?=$high_light?>' /></td>"+
            "</tr>"+
        "</thead>"+
        "</table>"+
    "</td>"+
    "<td>"+

        "<table class='table table-striped table-bordered table-hover' style='margin-bottom:0px; border:0px;'>"+
        "<thead>"+
            "<tr>"+
                "<td style='padding:0px; border:0px;width:4%;'>"+
                    "<select class='form-control' tabindex='33' required name='txt_sup4_unit[]' id='txt_sup4_unit_<?=$rqdi?>' data-toggle='tooltip' data-placement='top' title='Unit.'>"+
                        "<?  for($uniti = 0; $uniti < count($sql_unit); $uniti++) { ?>"+
                            "<option value='<?=$sql_unit[$uniti]['UNTCODE']?>' <? if($unit4[0]['UNTNAME'] == $sql_unit[$uniti]['UNTCODE']) { ?> selected <? } ?>><?=$sql_unit[$uniti]['UNTNAME']?></option>"+
                        "<? } ?>"+
                    "</select>"+
                "</td>"+

                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup4_qty[]' id='txt_sup4_qty' value='<?=$sql_reqd[$rqdi]['PRDQTY4']?>' class='form-control' placeholder='Qty.' data-toggle='tooltip' data-placement='top' data-original-title='Qty.' title='Qty.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px; width:4%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup4_value[]' id='txt_sup4_value' value='<?=$sql_reqd[$rqdi]['PRDVALU4']?>' class='form-control' placeholder='Value.' data-toggle='tooltip' data-placement='top' data-original-title='Value.' title='Value.' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='40' name='txt_sup4_discount[]' id='txt_sup4_discount' value='<?=$sql_reqd[$rqdi]['PRDDISC4']?>' class='form-control' placeholder='Discount' data-toggle='tooltip' data-placement='top' data-original-title='Discount' onKeyPress='return isNumber(event)' title='Discount' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+
                "<td style='padding:0px; border:0px;width:4%;'><input type='text' tabindex='40' name='txt_sup4_tax[]' id='txt_sup4_tax' value='<?=$sql_reqd[$rqdi]['PRDTAX4']?>' class='form-control' placeholder='Tax' data-toggle='tooltip' data-placement='top' data-original-title='Tax' onKeyPress='return isNumber(event)' title='Tax' maxlength='4' style='background-color:<?=$high_light?>' /></td>"+

                "<td style='padding:0px; border:0px; width:5%;'><input type='text' onKeyPress='return isNumber(event)' name='txt_sup4_price[]' id='txt_sup4_price' value='<? echo $supplier4_price = calculate_total($sql_reqd[$rqdi]['PRDQTY4'], $sql_reqd[$rqdi]['PRDVALU4'], $sql_reqd[$rqdi]['PRDDISC4'], $sql_reqd[$rqdi]['PRDTAX4']); ?>' class='form-control' placeholder='Price' data-toggle='tooltip' data-placement='top' data-original-title='Price' title='Price' maxlength='6' style='background-color:<?=$high_light?>' /></td>"+
            "</tr>"+
        "</thead>"+
        "</table>"+
    "</td>"+
"</tr>"+
"</table>"+
"</div>"); //add input box
            }
        });

        $(wrapper).on("click",".remove_field", function(e){ // user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
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


    function find_tablemaster(tblname) {
        $('#load_page').show();
        $.ajax({
            url:"ajax/ajax_product_details.php?action=table_master_view&tblname="+tblname,
            beforeSend: function() { $('#load_page').show(); },
            success:function(data)
            {
                $("#myModal1").modal('show');
                $('#modal-body1').html(data);
                $('#load_page').hide();
            }
        });
    }

    function makeFileList() {
        var input = document.getElementById("filesToUpload");
        document.getElementById("txt_files").value = input.files.length;
        if(input.files.length > 4) {
            alert('You cannot upload more than 4 files');
        }
    }

    var loadedobjects="";
    function loadobjs(){
        if (!document.getElementById)
            return
        for (i=0; i<arguments.length; i++){
            var file=arguments[i]
            var fileref=""
            if (loadedobjects.indexOf(file)==-1){ //Check to see if this object has not already been added to page before proceeding
                if (file.indexOf(".js")!=-1){ //If object is a js file
                    fileref=document.createElement('script')
                    fileref.setAttribute("type","text/javascript");
                    fileref.setAttribute("src", file);
                }
                else if (file.indexOf(".css")!=-1){ //If object is a css file
                    fileref=document.createElement("link")
                    fileref.setAttribute("rel", "stylesheet");
                    fileref.setAttribute("type", "text/css");
                    fileref.setAttribute("href", file);
                }
            }
            if (fileref!=""){
                document.getElementsByTagName("head").item(0).appendChild(fileref)
                loadedobjects+=file+" " //Remember this object as being already added to page
            }
        }
    }

    /*dynamic template details load*/
    function dynamic_template_load(reqid, year, rsrid, creid, typeid)
    {
        // alert("CAME");
        var hid_aprnumb = $('#hid_aprnumb').val();
        var prdcnt = <?=count($sql_prdlist)?>;
        if(prdcnt == 0){
        var strURL="ajax/ajax_dynamic_load.php?reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid+"&hid_aprnumb="+hid_aprnumb;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $("#id_supplier").html(data);
                }
            });
        }
    }
	/*function dynamic_template_load(reqid, year, rsrid, creid, typeid)
    {
        // alert("CAME");
        var hid_aprnumb = $('#hid_aprnumb').val();
        if(slt_approval_listings != '' && alow_prd == 1) {
                var strURL="ajax/ajax_dynamic_option1.php?action=add_edit&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view="+view;
           
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $("#id_policy_approval").html(data);
                }
            });
        }
    }*/


    </script>
<!-- END SCRIPTS -->
</body>
</html>
