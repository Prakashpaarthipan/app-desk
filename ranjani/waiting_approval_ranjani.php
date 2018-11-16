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

// AFTER MAR/1/2017 - FOR AK SIR
$mr_ak_date="";
if($_SESSION['tcs_usrcode'] == 3000000) {
    $mr_ak_date = " and trunc(ar.APPRSFR) >= TO_DATE('01-MAR-17','dd-Mon-yy') ";
}

if($prty != '') {
    $and_archive_cnt .= " and ar.PRICODE = '".$prty."' ";
    $sql_pri = select_query_json("select * from approval_priority where pricode = '".$prty."'", 'Centra', 'TCS');
    $prity_code1 = explode(" - ", $sql_pri[0]['PRINAME']);
    $prity_code = $prity_code1[0];
}
if($dept != '') {
    $and_archive_cnt .= " and ar.ATCCODE = '".$dept."' ";
    switch($dept) {
        case 1:
            $tpcore = 'S-TEAM'; break;
        case 2:
            $tpcore = 'MANAGEMENT'; break;
        case 3:
            $tpcore = 'OPERATION'; break;
        case 4:
            $tpcore = 'ADMIN'; break;
        case 5:
            $tpcore = 'GENERAL'; break;
        default:
            $tpcore = 'S-TEAM'; break;
    }
}

$apprval_status = " ";
$apprval_status1 = " ";
switch ($status) {
    case 'waiting':
        $apprval_status = " and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('N') ";
        $apprval_status1 = " and (ar.REQSTBY = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('W') ";
        break;

    case 'alternate':
        $apprval_status = " and (ar.INTPEMP = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('N') ";
        $apprval_status1 = " and (ar.INTPEMP = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('W') ";
        break;

    case 'bid':
        $apprval_status = " and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('Z') ";
        $apprval_status1 = " and (ar.REQSTBY = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('W') ";
        break;

    case 'post_audit':
        $apprval_status = " and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."') and ar.APPSTAT in ('N') and aprnumb = (select aprnumb from APPROVAL_REQUEST where appstat in ('A') and deleted = 'N' and arqsrno = 1 and arqpcod = ar.arqpcod and arqcode = ar.arqcode and arqyear = ar.arqyear and atycode = ar.atycode and atccode = ar.atccode and aprnumb = ar.aprnumb) ";
        $apprval_status1 = " and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."') and ar.APPSTAT in ('N') and aprnumb = (select aprnumb from APPROVAL_REQUEST where appstat in ('A') and deleted = 'N' and arqsrno = 1 and arqpcod = ar.arqpcod and arqcode = ar.arqcode and arqyear = ar.arqyear and atycode = ar.atycode and atccode = ar.atccode and aprnumb = ar.aprnumb) ";
        break;

    default:
        $apprval_status = " and (ar.REQSTFR = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('N') ";
        $apprval_status1 = " and (ar.REQSTBY = '".$_SESSION['tcs_empsrno']."') and ( APPFRWD = 'F' or APPFRWD = 'I' or APPFRWD = 'S' ) and ar.APPSTAT in ('W') ";
        break;
}

$usr = '';
$sql_search = select_query_json("select distinct ar.APRNUMB, ar.APPSTAT, ar.RQFRDES, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL AVAL, ar.APRTITL, max(ar.ARQSRNO) ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE,
                                            ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE',
                                            'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 
                                            'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                            ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, ar.ADDDATE, (select ADDDATE
                                            from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE 
                                            and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE) as ADDEDDATE, (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME
                                            from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and 
                                            ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, ar.APPRDET, ar.PRICODE, 
                                            pri.priname, ar.RQBYDES as prev_reqby, brn.brncode, (select nvl(appfrwd, '') appfrwdrow from APPROVAL_REQUEST R where aprnumb = ar.aprnumb and 
                                            arqsrno in (SELECT max(ARQSRNO) FROM APPROVAL_REQUEST where appfrwd='I' and REQSTFR = ar.REQSTFR and ARQCODE =ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                            ATYCODE = ar.ATYCODE and ATCCODE = ar.ATCCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APRNUMB = ar.APRNUMB and deleted = 'N')) as APPFRWDROW,
                                            regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, AR.INTPEMP, ar.pricode||' - '||pri.priname priority
                                        from APPROVAL_REQUEST ar, branch brn, approval_priority pri
                                        where brn.brncode = ar.brncode and pri.pricode(+) = ar.pricode and pri.deleted(+) = 'N' and ar.DELETED = 'N' 
                                            ".$apprval_status." ".$usr." ".$mr_ak_date." ".$and_archive_cnt."
                                        group by ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.RQFRDES, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, 
                                            ar.ADDDATE, ar.RQESTTO, ar.ATMCODE, ar.TARDESC, ar.APMCODE, ar.APPRDET, ar.PRICODE, brn.brncode, brn.nicname, ar.RQBYDES, pri.priname, ar.APPFRWD, 
                                            ar.REQSTFR, AR.INTPEMP 
                                    union
                                        select distinct ar.APRNUMB, ar.APPSTAT, ar.RQFRDES, ar.APPRSUB, ar.APPRFOR, ar.APPFVAL AVAL, ar.APRTITL, max(ar.ARQSRNO) ARQSRNO, ar.ARQCODE, ar.ATYCODE, 
                                            ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 
                                            'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N', '1', 'F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 
                                            'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and 
                                            ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT,
                                            ar.ADDDATE, (select ADDDATE from APPROVAL_REQUEST where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and 
                                            ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE) as ADDEDDATE, (select EMPNAME from employee_office 
                                            where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE 
                                            and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and 
                                            deleted = 'N')) as reqby, ar.APPRDET, ar.PRICODE, pri.priname, ar.RQBYDES as prev_reqby, brn.brncode, (select nvl(appfrwd, '') appfrwdrow 
                                            from APPROVAL_REQUEST R where aprnumb = ar.aprnumb and arqsrno in (SELECT max(ARQSRNO) FROM APPROVAL_REQUEST where appfrwd='I' and REQSTFR = ar.REQSTFR 
                                            and ARQCODE =ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATYCODE = ar.ATYCODE and ATCCODE = ar.ATCCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and
                                            APRNUMB = ar.APRNUMB and deleted = 'N')) as APPFRWDROW, regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch, AR.INTPEMP,  
                                            ar.pricode||' - '||pri.priname priority 
                                        from APPROVAL_REQUEST ar, branch brn, approval_priority pri
                                        where brn.brncode = ar.brncode and pri.pricode(+) = ar.pricode and pri.deleted(+) = 'N' and ar.DELETED = 'N' 
                                            ".$apprval_status1." ".$usr." ".$mr_ak_date." ".$and_archive_cnt."
                                        group by ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.RQFRDES, ar.APPRFOR, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, 
                                            ar.ADDDATE, ar.RQESTTO, ar.ATMCODE, ar.TARDESC, ar.APMCODE, ar.APPRDET, ar.PRICODE, brn.brncode, brn.nicname, ar.RQBYDES, pri.priname, ar.APPFRWD, 
                                            ar.REQSTFR, AR.INTPEMP 
                                        order by branch asc, AVAL Desc, APPORDER Asc", 'Centra', 'TEST');
$ttlvl = array_sum(array_map(function($item) {
                        return $item['AVAL'];
                     }, $sql_search));
$cnt_search = $sql_search;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Waiting for Approvals List :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
    .comment-frame {
        background-color: #f0eeee;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
        box-shadow: 0 1px 2px rgba(0,0,0,.23);
        margin: 4px 4px 12px 0;
    }
    .comment-box {
        position: relative;
    }
    .comment-box {
        position: relative;
    }
    .comment-box-input {
        background: none;
        height: 75px;
        margin: 0;
        min-height: 75px;
        padding: 9px 11px;
        padding-bottom: 0;
        width: 100%;
        resize: none;
    }
    .comment-box-options {
        position: absolute;
        bottom: 20px;
        right: 6px;
    }
    .comment-box-options-item {
        border-radius: 3px;
        float: left;
        height: 18px;
        margin-left: 4px;
        padding: 6px;
    }
    .fa-at:before {
        content: "\f1fa";
    }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
</style>

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
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
                <li class="active">Waiting for Approvals List</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Waiting for Approvals List</h3>
                        <? /* <div class="btn-group pull-right">
                            <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'false'});"><img src='img/icons/json.png' width="24"/> JSON</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'false',ignoreColumn:'[2,3]'});"><img src='img/icons/json.png' width="24"/> JSON (ignoreColumn)</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'json',escape:'true'});"><img src='img/icons/json.png' width="24"/> JSON (with Escape)</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'xml',escape:'false'});"><img src='img/icons/xml.png' width="24"/> XML</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'sql'});"><img src='img/icons/sql.png' width="24"/> SQL</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'csv',escape:'false'});"><img src='img/icons/csv.png' width="24"/> CSV</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'txt',escape:'false'});"><img src='img/icons/txt.png' width="24"/> TXT</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'excel',escape:'false'});"><img src='img/icons/xls.png' width="24"/> XLS</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'doc',escape:'false'});"><img src='img/icons/word.png' width="24"/> Word</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'powerpoint',escape:'false'});"><img src='img/icons/ppt.png' width="24"/> PowerPoint</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'png',escape:'false'});"><img src='img/icons/png.png' width="24"/> PNG</a></li>
                                <li><a href="#" onClick ="$('#customers2').tableExport({type:'pdf',escape:'false'});"><img src='img/icons/pdf.png' width="24"/> PDF</a></li>
                            </ul>
                        </div> */ ?>

                        <div class="btn-group pull-right">
                            <div class="marquee huge" style='font-size: 20px;'>
                                <p><? if($tpcore != '') { echo $tpcore; if($prity_code != '') { echo " - ".$prity_code; } echo " APPROVALS - "; } ?> Total Value &#8377; : <?=round($ttlvl / 100000, 2)?> (L) (<?=count($cnt_search)?> APPROVALS)</p>
                            </div>

                            <? /* <marquee behavior="alternate"><div class="huge" style='font-size: 20px;'><? if($tpcore != '') { echo $tpcore; if($prity_code != '') { echo " - ".$prity_code; } echo " APPROVALS - "; } ?> Total Value &#8377; : <?=round($ttlvl / 100000, 2)?> (L) (<?=count($cnt_search)?> APPROVALS)</div></marquee> */ ?>
                        </div>

                    </div>
                    <div class="panel-body">
                        <table id="customers2" class="table datatable">
                            <thead>
                                <tr>
                                    <? /* <th>Priority</th> */ ?>
                                    <th>Status</th>
                                    <th>#</th>
                                    <th>Branch</th>
                                    <th>Approval Number</th>
                                    <th>Details</th>
                                    <th>Value &#8377;</th>
                                    <th>Request by</th>
                                    <th>From Person</th>
                                    <th>Request to</th>
                                    <th style='text-align:center;'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?  $ij = 0;
                                for($search_i = 0; $search_i < count($sql_search); $search_i++) { $ij++;
                                    // A - Approved; N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; I - Internal Verification; P - Pending; Q - Query;
                                    $bgclr = '';
                                    $clr = '#000000';
                                    if($sql_search[$search_i]['APPSTAT'] == 'A') { $appstatus = "APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'N') { $appstatus = "NEW"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'R') { $appstatus = "REJECTED"; $bgclr = '#F2DEDE'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'F') {
                                        if($sql_search[$search_i]['APSTAT'] == 'A') { $appstatus = "APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
                                        else { $appstatus = "FORWARD"; }
                                    }
                                    if($sql_search[$search_i]['APPSTAT'] == 'C') { $appstatus = "COMPLETED"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'I') { $appstatus = "INTERNAL VERIFICATION"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'P') { $appstatus = "PENDING"; $bgclr = '#FAF4D1'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'S') { $appstatus = "RESPONSE"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'Q') { $appstatus = "QUERY"; }
                                ?>
                            <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                <? /* <td><? echo $sql_search[$search_i]['PRICODE']." - ".$sql_search[$search_i]['PRINAME']; ?></td> */ ?>
                                <td class="center" style='text-align:center;'>
                                    <? echo $appstatus; ?>
                                </td>
                                <td style='text-align:center'><?=$ij?></td>
                                <td><b><? echo $sql_search[$search_i]['BRANCH']; ?></b></td>
                                <td>
                                    <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a>
                                    <?  $userid = $_SESSION['tcs_empsrno'];
                                        if($sql_search[$search_i]['APPFRWDROW'] == 'I') { ?>
                                            <br><span class="label label-danger" style="">INTERNAL VERIFICATION</span>
                                        <? }
                                        if($sql_search[$search_i]['INTPEMP'] == $_SESSION['tcs_empsrno']) { ?>
                                            <br><span class="label label-warning" style="">ALTERNATE APPROVAL FOR <br><?=$sql_search[$search_i]['RQFRDES']?></span>
                                        <? }
                                        if($sql_search[$search_i]['APPSTAT'] == 'Z') { ?>
                                            <br><span class="label label-success" style="">BID APPROVAL</span>
                                        <? } ?>
                                </td>
                                <td class="center show_moreless">
                                    <?  if($sql_search[$search_i]['APPRFOR'] == '1') {
                                        $filepathname = $sql_search[$search_i]['APPRSUB'];
                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.":5022/approval_desk/text_approval_source/".$filepathname;
                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                        $contents = fread($handle, filesize($filename));
                                        fclose($handle);
                                        // echo strip_tags(str_replace("&nbsp;", " ", $contents));
                                        echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
                                    } else {
                                        echo $sql_search[$search_i]['APPRDET'];
                                    } ?>
                                </td>
                                <td><? if($sql_search[$search_i]['AVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['AVAL']); } else { echo "-Nil-"; } ?></td>
                                <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
                                <td class="center"><? echo $sql_search[$search_i]['PREV_REQBY']; ?></td>
                                <td class="center"><? if($sql_search[$search_i]['RQESTTO'] == 43400) { echo "Mrs."; } else { echo "Mr."; }; echo " ".$sql_search[$search_i]['REQTO']; ?></td>
                                <td class="center" style='text-align:center;'>
                                    <? if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'P' or $sql_search[$search_i]['APPSTAT'] == 'R' or $sql_search[$search_i]['APPSTAT'] == 'Z') { ?><a target="_blank" href='waiting_approvals.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a><? }

                                    elseif($sql_search[$search_i]['APPSTAT'] == 'W') { ?><a target="_blank" href='waiting_approvals.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo ($sql_search[$search_i]['ARQSRNO']-1); ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a><? }

                                    else { ?>
                                        <a target="_blank" href='waiting_approvals.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a>
                                    <? }
                                    if($sql_search[$search_i]['APSTAT'] == 'A') {
                                        if($_SESSION['auditor_login'] == 0) { } } ?>
                                        <?  if($sql_search[$search_i]['APPSTAT'] != 'A') { ?> / <a href='javascript:void(0)' onclick="cmnt_mail('<?=$sql_search[$search_i]['APRNUMB']?>');" title='mail' alt='mail' style='color:<?=$clr?>;'><i class="fa fa-envelope"></i> mail</a><? } ?>
                                        <?

                                        if($sql_search[$search_i]['APPSTAT'] == 'A' or $sql_search[$search_i]['APPSTAT'] == 'R') {
                                            $sql_vl = select_query_json("select ADDDATE from APPROVAL_REQUEST
                                                                                      where aprnumb = '".$sql_search[$search_i]['APRNUMB']."' and ARQSRNO = (select max(ARQSRNO)
                                                                                          from APPROVAL_REQUEST where aprnumb = '".$sql_search[$search_i]['APRNUMB']."')", "Centra", 'TEST');
                                            $start_time = formatSeconds(strtotime($sql_vl[0]['ADDDATE']) - strtotime($sql_search[$search_i]['ADDEDDATE']));
                                        } else {
                                            $start_time = formatSeconds(strtotime('now') - strtotime($sql_search[$search_i]['ADDEDDATE']));
                                        }
                                        $sql_iv = select_query_json("select count(appfrwd) CNTAPPFRWD from approval_request 
                                                                            where aprnumb like '".$sql_search[$search_i]['APRNUMB']."' and appfrwd = 'I' 
                                                                            order by arqsrno", "Centra", "TEST");
                                        $duedate = 0; 
                                        // echo "++".$sql_search[$search_i]['PRICODE']."++".count($sql_iv)."++".$sql_iv[0]['CNTAPPFRWD']."++";
                                        switch ($sql_search[$search_i]['PRICODE']) {
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

                                        echo '<br><span class="label label-info label-form" style="background-color:'.$css_cls.'">'.$sql_search[$search_i]['PRIORITY']."</span>";
                                        echo '<br><span class="label label-info label-form" style="background-color:'.$css_clstime.'">Due Date : '.$duedate.' Days & Process Date : '.$start_time.' Days</span>';
                                        ?>
                                </td>
                                </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <!-- Collect Document -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>APPROVAL FINAL FINISH</div>
                <div class="modal-body" id="modal-body1"></div>
            </div>
        </div>
    </div>

    <div id="myModal2" class="modal fade">
        <div class="modal-dialog" style='width:85%'>
            <div class="modal-content">
                <div class="modal-body" id="modal-body2"></div>
            </div>
        </div>
    </div>
    <!-- Collect Document -->
    <div class='clear'></div>


    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

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
    <!-- Select2 -->
    <script src="../dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- END TEMPLATE -->

    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
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
            $('#customers2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customers2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
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

    function cmt_usr()
    {
        $('#cmtusr').css("display", "block");
        $('.select2').select2();
        $('#mailusr').focus();
        //$("#mailusr").select2("open");
        $('#mailusr').select2({
        placeholder: 'Enter EC No/Name to Select an mail user',
        allowClear: true,
        dropdownAutoWidth: true,
        minimumInputLength: 3,
        maximumSelectionLength: 3,
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
    </script>
<!-- END SCRIPTS -->
</body>
</html>
