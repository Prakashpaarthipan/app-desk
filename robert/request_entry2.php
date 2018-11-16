<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

/* include('../approval_desk-ftp/lib/config.php');
include('../db_connect/public_functions.php');
include('../approval_desk-ftp/general_functions.php'); */
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if($_REQUEST['action'] == "edit"){ ?>
    <script>window.location="request_list.php";</script>
<?php exit();
}

$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
$sysip = $_SERVER['REMOTE_ADDR'];
$cur_mn = date("m");

if($_SESSION['auditor_login'] == 1) { ?>
    <script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
<? exit();
}

$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, substr(brn.nicname,3,15) branch, emp.EMPCODE, emp.EMPNAME, ast.DEPNAME, ast.EXPNAME, ast.EXPSRNO,
                                            to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time, to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time,
                                            to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time, to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time,
                                            to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, req.ATCCODE ATCCCODE
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                            employee_office emp
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.BRNCODE = brn.BRNCODE and
                                            req.DEPCODE = ast.DEPCODE and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K', 'T')
                                            and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$_REQUEST['rsrid']."'
                                            and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
                                        order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TEST');

$sql_rdsrno =  select_query_json("select count(*) cnt from APPROVAL_REQUEST where aprnumb like '".$sql_reqid[0]['APRNUMB']."' order by ARQSRNO", "Centra", 'TEST');
if($sql_rdsrno[0]['CNT'] > 1 && $action == 'edit') { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="request_list.php";</script>
<? exit();
}

$sql_vl = select_query_json("select distinct req.APRQVAL, max(req.arqsrno) mx, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE,
                                        req.TARNUMB, req.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME
                                    from APPROVAL_REQUEST req, department_asset ast
                                    where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and req.DEPCODE = ast.DEPCODE and req.DELETED = 'N' and ast.DELETED = 'N'
                                    group by req.APRQVAL, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE, req.TARNUMB, req.TARDESC,
                                        ast.EXPSRNO, ast.EXPNAME, ast.DEPNAME
                                    order by mx desc", "Centra", 'TEST');
$sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'", "Centra", 'TEST');

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

$view = 1; // Budget Related Approvals show or not
if($sql_reqid[0]['ATYCODE'] != 1 and $sql_reqid[0]['ATYCODE'] != 6 and $sql_reqid[0]['ATYCODE'] != 7 and ( $_REQUEST['action'] == 'view' or $_REQUEST['action'] == 'edit' )) {
    $view = 0; // Budget Related Approvals show or not
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title><?=$title_tag?> Request Entry :: Approval Desk :: <?php echo $site_title; ?></title>
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
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    <script src="js/angular.js"></script>
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
                <li><a href="request_list.php">Approval Request</a></li>
                <li class="active"><?=$title_tag?> Request Entry</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">

                <div class="row">
                    <div class="col-md-12">

                        <form class="form-horizontal" role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                            <? if($_REQUEST['action'] == 'edit') { ?>
                                <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='edit_request_entry' />
                            <? } else { ?>
                                <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='request_entry' />
                            <? } ?>
                        <div class="panel panel-default">
                            <div id="result"></div> <!-- Display the Process Status -->

                            <div class="panel-heading">
                                <h3 class="panel-title"><strong><?=$title_tag?> Request Entry <? if($_REQUEST['action'] == 'view' or $_REQUEST['action'] == 'edit') { ?> - <span class="highlight_redtitle"><?=$sql_reqid[0]['APRNUMB']?></span>
                                <input type='hidden' name='hid_aprnumb' id='hid_aprnumb' value='<?=$sql_reqid[0]['APRNUMB']?>'>
                                <input type='hidden' name='hid_appattn_cnt' id='hid_appattn_cnt' value='<?=$sql_reqid[0]['APPATTN']?>'><? } ?></strong></h3>
                                <ul class="panel-controls">
                                    <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                </ul>
                            </div>
                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <!-- New -->
                                        <? /* echo "**".$_SESSION['tcs_emptopcore']."**<br>"; echo "**".$_SESSION['tcs_empsubcore']."**<br>";
                                        echo "**".$_SESSION['tcs_emptopcore_code']."**<br>"; echo "**".$_SESSION['tcs_empsubcore_code']."**<br>"; 
                                        <input type='hidden' name='slt_project_type' id='slt_project_type' value='R'>
                                        <input type='hidden' name='hid_slt_subcore_name' id='hid_slt_subcore_name' value='<?=$_SESSION['tcs_empsubcore']?>'>

                                        <? /* <input type='hidden' name='slt_topcore_name' id='slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
                                        <input type='hidden' name='slt_topcore' id='slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>
                                        <input type='hidden' name='org_slt_topcore_name' id='org_slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
                                        <input type='hidden' name='org_slt_topcore' id='org_slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>
                                        <div id="id_subcore_list" style="display: block;">
                                            <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>
                                            <input type='hidden' name='slt_subcore' id='slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>
                                        </div> */ ?>

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
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '".$sql_reqid[0]['EXPSRNO']."'", "Centra", 'TCS');
                                            } else {
                                                $sql_rptmode = select_query_json("select RPTMODE from department_asset where EXPSRNO = '13'", "Centra", 'TCS');
                                            } ?>
                                        <input type='hidden' name='txt_rptmode' id='txt_rptmode' value='<?=$sql_rptmode[0]['RPTMODE']?>'>

                                        <!-- Project -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Project <span style='color:red'>*</span></label>
                                            <div class="col-md-9">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <?=$sql_reqid[0]['APRNAME']?>
                                                <? } else { ?>
                                                    <select class="form-control custom-select chosn" autofocus tabindex='1' required name='slt_project' id='slt_project' data-toggle="tooltip" data-placement="top" data-original-title="Project" onChange="gettopcore(this.value)" onblur="gettopcore(this.value);">
                                                    <?  $sql_project = select_query_json("select * from approval_project where DELETED = 'N' order by APRCODE Asc", "Centra", 'TEST');
                                                        for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                        <option value='<?=$sql_project[$project_i]['APRCODE']?>' <? if($sql_reqid[0]['APRCODE'] == $sql_project[$project_i]['APRCODE']) { $tpcr = $sql_project[$project_i]['ATCCODE']; ?> selected <? } ?>><?=$sql_project[$project_i]['APRCODE']." - ".$sql_project[$project_i]['APRNAME']?></option>
                                                    <? } ?>
                                                    </select>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Project -->

                                        <? if($_REQUEST['action'] == 'view') { ?>
                                            <input type='hidden' name='hid_slt_submission' id='hid_slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                            <input type='hidden' name='slt_submission' id='slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                        <? } elseif($_REQUEST['action'] == 'edit') { ?>
                                            <input type='hidden' name='hid_slt_submission' id='hid_slt_submission' value='<?=$sql_reqid[0]['ATYCODE']?>'>
                                            <input type='hidden' name='hid_slt_core_department' id='hid_slt_core_department' value='<?=$sql_reqid[0]['EXPSRNO']?>'>
                                            <input type='hidden' name='hid_slt_department_asset' id='hid_slt_department_asset' value='<?=$sql_reqid[0]['DEPCODE']?>'>
                                            <input type='hidden' name='hid_slt_targetno' id='hid_slt_targetno' value='<?=$sql_reqid[0]['TARNUMB']?>'>
                                        <? } ?>
                                        <input type='hidden' name='org_slt_topcore_name' id='org_slt_topcore_name' value='<?=$_SESSION['tcs_emptopcore']?>'>
                                        <input type='hidden' name='org_slt_topcore' id='org_slt_topcore' value='<?=$_SESSION['tcs_emptopcore_code']?>'>

                                        <!-- Type of Submission Type -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Type of Submission <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12" id="id_submission_type">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <?=$sql_reqid[0]['ATYNAME']?>
                                                <? } else { ?>
                                                    <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?> class="form-control custom-select chosn" onblur="get_targetdates()" onChange="getsubtype(this.value)" <? } ?> tabindex='2' required name='slt_submission' id='slt_submission' data-toggle="tooltip" data-placement="top" data-original-title="Type of Submission">
                                                    <?  if($sql_reqid[0]['APRCODE'] != '') {
                                                            $sql_project = select_query_json("select * from approval_project
                                                                                                    where DELETED = 'N' and APRCODE = '".$sql_reqid[0]['APRCODE']."' order by APRCODE Asc", "Centra", 'TEST');
                                                        } else {
                                                            $sql_project = select_query_json("select * from approval_project
                                                                                                    where DELETED = 'N' order by APRCODE Asc", "Centra", 'TEST');
                                                        }
                                                        if($sql_project[0]['BRNPROJ'] == 'P') {
                                                            $sql_submission_type = select_query_json("select * from approval_type
                                                                                                                where DELETED = 'N' order by ATYSRNO", "Centra", 'TCS');
                                                                                                                // 1 for FIXED BUDGET, 7 for Extra Budget
                                                        } elseif($sql_project[0]['BRNPROJ'] == 'B') {
                                                            $sql_submission_type = select_query_json("select * from approval_type
                                                                                                                where DELETED = 'N' order by ATYSRNO", "Centra", 'TCS');
                                                                                                                // 1 for FIXED BUDGET, 7 for Extra Budget
                                                        } ?>
                                                        <option value='' <? if($sql_reqid[0]['ATYCODE'] == '') { ?> selected <? } ?>>-- Choose Type of Submission --</option>
                                                        <? for($submission_type_i = 0; $submission_type_i < count($sql_submission_type); $submission_type_i++) { ?>
                                                            <option value='<?=$sql_submission_type[$submission_type_i]['ATYCODE']?>' <? if($sql_reqid[0]['ATYCODE'] == $sql_submission_type[$submission_type_i]['ATYCODE']) { ?> selected <? } ?>><?=$sql_submission_type[$submission_type_i]['ATYNAME']?></option>
                                                    <? } ?>
                                                    </select>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Type of Submission Type -->


                                        <!-- Fixed Budget Planner -->
                                        <div class="form-group" id="id_fixbudget_planner" style="display: none">
                                            <label class="col-md-3 control-label">Fixed Budget Planner <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <?=$sql_reqid[0]['ATYNAME']?>
                                                <? } else { ?>
                                                    <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="form-control custom-select" <? } else { ?> class="form-control custom-select chosn" onchange="get_targetdates(); call_dynamic_option(); " <? } ?> tabindex='2' required name='slt_fixbudget_planner' id='slt_fixbudget_planner' data-toggle="tooltip" data-placement="top" data-original-title="Fixed Budget Planner">
                                                        <? /* <option value='MONTHWISE' <? if($sql_reqid[0]['ATYCODE'] == 'MONTHWISE') { ?> selected <? } ?>>MONTHWISE BUDGET</option> */ ?>
                                                        <option value='PRODUCTWISE' <? if($sql_reqid[0]['ATYCODE'] == 'PRODUCTWISE') { ?> selected <? } ?>>PRODUCTWISE BUDGET</option>
                                                    </select>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Fixed Budget Planner -->

                                        <div id='id_branch'>
                                        <? if($view == 1) { ?>
                                            <!-- Expense Head -->
                                            <div class="form-group" style="display: none;">
                                                <label class="col-md-3 control-label">Expense Head <span style='color:red'>*</span></label>
                                                <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] == 'view') { ?>
                                                        : <?=$sql_vl[0]['EXPHEAD']?>
                                                    <? } else { ?>
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="form-control custom-select" <? } else { ?> class="form-control custom-select chosn" onblur="get_advancedetails(this.value);" onChange="get_dept(this.value);" <? } ?> tabindex='3' required name='slt_core_department' id='slt_core_department' data-toggle="tooltip" data-placement="top" data-original-title="Core Department">
                                                        <?  if($sql_vl[0]['EXPSRNO'] == '') {
                                                                $sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset
                                                                                                            where DELETED = 'N' and expsrno > 0 order by EXPNAME", "Centra", 'TCS');
                                                            } else {
                                                                $sql_project = select_query_json("select distinct EXPSRNO, EXPNAME from department_asset
                                                                                                            where DELETED = 'N' and expsrno > 0 and EXPSRNO = '".$sql_vl[0]['EXPSRNO']."'
                                                                                                            order by EXPNAME", "Centra", 'TCS');
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
                                                            <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn" onblur="get_advancedetails(this.value);" onChange="get_advancedetails(this.value);"<? } ?> tabindex='4 required name='slt_department_asset' id='slt_department_asset' data-toggle="tooltip" data-placement="top" data-original-title="Department Asset">
                                                                <?  $sql_project = select_query_json("select * from department_asset
                                                                                                            where DELETED = 'N' and expsrno > 0 order by DEPNAME", "Centra", 'TCS');
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
                                                                <select <? if($_REQUEST['action'] == 'edit') { ?>disabled class="form-control custom-select"<? } else { ?>class="form-control custom-select chosn" onblur="get_targetdates();" onchange="get_targetdates();"<? } ?> tabindex='3' required name='slt_targetnos' id='slt_targetnos' data-toggle="tooltip" data-placement="top" data-original-title="Target No">
                                                                <?  $sql_tarno = select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||( select distinct
                                                                                                            decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname from non_purchase_target tar,
                                                                                                            department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and
                                                                                                            dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) Depname, (select distinct
                                                                                                            decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname from non_purchase_target tar,
                                                                                                            department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and
                                                                                                            dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) dpnm, (select distinct
                                                                                                            dep.depcode||'!!'||dep.depname||'!!'||dep.EXPSRNO||'!!'||dep.TOPCORE||'!!20!!N' Depname
                                                                                                            from non_purchase_target tar, department_asset Dep where tar.depcode=dep.depcode and
                                                                                                            tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode) deptdet
                                                                                                        from budget_planner_branch bpl
                                                                                                        where TARYEAR=".(date("y")-1)." and TARMONT=".$cur_mn." and (tarnumb>8000
                                                                                                            or tarnumb in (7632, 7630))
                                                                                                        order by Depname", "Centra", 'TCS'); // ||'-'||dep.ESECODE||'-'||dep.MULTIREQ

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


                                        <div id='id_topcore' style="display: none;">
                                        <!-- Top Core -->
                                        <div class="form-group trbg"></div>
                                        <div class="tags_clear"></div>
                                        <!-- Top Core -->
                                        </div>

                                        <div id='id_subcore' style="display: block;">
                                            <input type='hidden' name='hid_slt_subcore' id='hid_slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>
                                            <input type='hidden' name='slt_subcore' id='slt_subcore' value='<?=$_SESSION['tcs_empsubcore_code']?>'>
                                        </div>


                                        <!-- Approval Subject -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Approval Subject <span style='color:red'>*</span></label>
                                            <div class="col-md-9 col-xs-12">
                                                <? if($_REQUEST['action'] == 'view') { ?>
                                                    : <?=$sql_reqid[0]['APMNAME'].$sql_reqid[0]['DYNSUBJ'].$sql_reqid[0]['TXTSUBJ']?>
                                                <? } else { ?>
                                                    <div id="id_appr_listings">
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="form-control custom-select" <? } else { ?> class="form-control custom-select chosn" onChange="get_advancedetails(); getapproval_listings(this.value)" onblur="call_days()" <? } ?> tabindex='6' required name='slt_approval_listings' id='slt_approval_listings' data-toggle="tooltip" data-placement="top" data-original-title="Approval Subject">
                                                        <option value=''>Choose Approval Subject</option>
                                                        <?  if($_REQUEST['action'] == 'edit') {
                                                                if($sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7) {
                                                                    $attycode = 1;
                                                                } else {
                                                                    $attycode = $sql_reqid[0]['ATYCODE'];
                                                                }
                                                                $sql_approval_type_mode = select_query_json("select * from approval_master
                                                                                                                    where ATYCODE = '".$attycode."' and DELETED = 'N' and DELUSER is null
                                                                                                                    order by APMNAME Asc", "Centra", 'TCS');
                                                            } else {
                                                               $sql_approval_type_mode = select_query_json("select * from approval_master
                                                                                                                    where ATYCODE in (1, 6, 7) and DELETED = 'N' and DELUSER is null
                                                                                                                   order by APMNAME Asc", "Centra", 'TCS');
                                                            }
                                                            for($approval_type_mode_i = 0; $approval_type_mode_i < count($sql_approval_type_mode); $approval_type_mode_i++) { ?>
                                                               <option value='<?=$sql_approval_type_mode[$approval_type_mode_i]['APMCODE']?>' <? if($sql_reqid[0]['APMCODE'] == $sql_approval_type_mode[$approval_type_mode_i]['APMCODE']) { ?> selected <? } ?>><?=$sql_approval_type_mode[$approval_type_mode_i]['APMNAME']?></option>
                                                        <? } ?>
                                                        </select>
                                                    </div>
                                                    <div class="tags_clear height5px"></div>

                                                    <div id="dynamic_subject" style="display: block !important;">
                                                        <div class="col-md-5 col-xs-12" style="padding-left: 0px !important;">
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="custom-select" <? } else { ?> class="custom-select chosn" <? } ?> tabindex='6' name='txt_dynamic_subject1' id='txt_dynamic_subject1' onblur="find_tags();" data-toggle="tooltip" data-placement="top" data-original-title="Approval Subject From Month" >
                                                            <option value=''>Approval Subject From Month</option>
                                                            <option value='APR, 2017'>APR, 2017</option>
                                                            <option value='MAY, 2017'>MAY, 2017</option>
                                                            <option value='JUN, 2017'>JUN, 2017</option>
                                                            <option value='JUL, 2017'>JUL, 2017</option>
                                                            <option value='AUG, 2017'>AUG, 2017</option>
                                                            <option value='SEP, 2017'>SEP, 2017</option>
                                                            <option value='OCT, 2017'>OCT, 2017</option>
                                                            <option value='NOV, 2017'>NOV, 2017</option>
                                                            <option value='DEC, 2017'>DEC, 2017</option>
                                                            <option value='JAN, 2018'>JAN, 2018</option>
                                                            <option value='FEB, 2018'>FEB, 2018</option>
                                                            <option value='MAR, 2018'>MAR, 2018</option>

                                                            <option value='APR, 2018'>APR, 2018</option>
                                                            <option value='MAY, 2018'>MAY, 2018</option>
                                                            <option value='JUN, 2018'>JUN, 2018</option>
                                                            <option value='JUL, 2018'>JUL, 2018</option>
                                                            <option value='AUG, 2018'>AUG, 2018</option>
                                                            <option value='SEP, 2018'>SEP, 2018</option>
                                                            <option value='OCT, 2018'>OCT, 2018</option>
                                                            <option value='NOV, 2018'>NOV, 2018</option>
                                                            <option value='DEC, 2018'>DEC, 2018</option>
                                                            <option value='JAN, 2019'>JAN, 2019</option>
                                                            <option value='FEB, 2019'>FEB, 2019</option>
                                                            <option value='MAR, 2019'>MAR, 2019</option>
                                                        </select>
                                                        </div>
                                                        <div class="col-md-2 col-xs-12" style="text-align: center;">&nbsp;-&nbsp;</div>
                                                        <div class="col-md-5 col-xs-12" style="padding-right: 0px !important;">
                                                        <select <? if($_REQUEST['action'] == 'edit') { ?> disabled class="custom-select" <? } else { ?> class="custom-select chosn" <? } ?> tabindex='6' name='txt_dynamic_subject2' id='txt_dynamic_subject2' onblur="find_tags();" data-toggle="tooltip" data-placement="top" data-original-title="Approval Subject To Month">
                                                            <option value=''>Approval Subject To Month</option>
                                                            <option value='APR, 2017'>APR, 2017</option>
                                                            <option value='MAY, 2017'>MAY, 2017</option>
                                                            <option value='JUN, 2017'>JUN, 2017</option>
                                                            <option value='JUL, 2017'>JUL, 2017</option>
                                                            <option value='AUG, 2017'>AUG, 2017</option>
                                                            <option value='SEP, 2017'>SEP, 2017</option>
                                                            <option value='OCT, 2017'>OCT, 2017</option>
                                                            <option value='NOV, 2017'>NOV, 2017</option>
                                                            <option value='DEC, 2017'>DEC, 2017</option>
                                                            <option value='JAN, 2018'>JAN, 2018</option>
                                                            <option value='FEB, 2018'>FEB, 2018</option>
                                                            <option value='MAR, 2018'>MAR, 2018</option>

                                                            <option value='APR, 2018'>APR, 2018</option>
                                                            <option value='MAY, 2018'>MAY, 2018</option>
                                                            <option value='JUN, 2018'>JUN, 2018</option>
                                                            <option value='JUL, 2018'>JUL, 2018</option>
                                                            <option value='AUG, 2018'>AUG, 2018</option>
                                                            <option value='SEP, 2018'>SEP, 2018</option>
                                                            <option value='OCT, 2018'>OCT, 2018</option>
                                                            <option value='NOV, 2018'>NOV, 2018</option>
                                                            <option value='DEC, 2018'>DEC, 2018</option>
                                                            <option value='JAN, 2019'>JAN, 2019</option>
                                                            <option value='FEB, 2019'>FEB, 2019</option>
                                                            <option value='MAR, 2019'>MAR, 2019</option>
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="tags_clear height5px"></div>

                                                    <div>
                                                        <div class="col-md-3 col-xs-12" style="text-align: right; padding: 0px; line-height: 30px;">
                                                            Specific Subject&nbsp;:&nbsp;
                                                        </div>
                                                        <div class="col-md-9 col-xs-12" style="padding-right: 0px !important;">
                                                            <input type='text' class="form-control" tabindex='6' style="text-transform: uppercase;" name='txt_dynsubject' id='txt_dynsubject' data-toggle="tooltip" onblur="find_tags();" data-placement="top" data-original-title="Specific Subject" placeholder="Specific Subject" value='<?=$sql_reqid[0]['TXTSUBJ']?>'>
                                                        </div>
                                                    </div>
                                                <? } ?>
                                            </div>
                                            <div class="tags_clear"></div>
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
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and sec.deleted = 'N' and
                                                                                                        sec.deleted = 'N' and (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."')  and sal.PAYCOMPANY = 1
                                                                                                        and emp.empsrno = sal.empsrno
                                                                                                union
                                                                                                    select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                        des.DESNAME, sal.PAYCOMPANY
                                                                                                    from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                        (emp.empcode = '".$sql_reqid[0]['WRKINUSR']."') and sec.deleted = 'N' and sec.deleted = 'N' and
                                                                                                        sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                    order by EMPCODE", "Centra", 'TCS'); // 02052017
                                                                if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>
                                                                <input type='text' class="form-control" tabindex='6' style="text-transform: uppercase;" required name='txt_workintiator' id='txt_workintiator' data-toggle="tooltip" onblur="find_tags();" data-placement="top" data-original-title="Work Initiate Person" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                        <?  } ?>
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
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                        (emp.empcode = '".$sql_reqid[0]['RESPUSR']."' or emp.empcode = '".$sql_reqid[0]['ALTRUSR']."' or
                                                                                                        emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and sec.deleted = 'N' and sec.deleted = 'N' and
                                                                                                        sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
                                                                                                union
                                                                                                    select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN,
                                                                                                        des.DESNAME, sal.PAYCOMPANY
                                                                                                    from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
                                                                                                    where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and
                                                                                                        (emp.empcode = '".$sql_reqid[0]['RESPUSR']."' or emp.empcode = '".$sql_reqid[0]['ALTRUSR']."' or
                                                                                                        emp.empsrno = '".$sql_reqid[0]['DELUSER']."') and sec.deleted = 'N' and sec.deleted = 'N' and
                                                                                                        sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
                                                                                                    order by EMPCODE", "Centra", 'TCS'); // 02052017
                                                                if($_REQUEST['action'] == 'view') {
                                                                    echo ": ".$sql_emp[0]['EMPCODE']." - <b>".$sql_emp[0]['EMPNAME']."</b> (".$sql_emp[0]['DESNAME'].") - ".substr($sql_emp[0]['ESENAME'], 3)." ";
                                                            } else { ?>
                                                                <input type='text' class="form-control" tabindex='6' onblur="find_tags();" style="text-transform: uppercase;" required name='txt_submission_reqby' id='txt_submission_reqby' data-toggle="tooltip" data-placement="top" data-original-title="Responsible Person" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                        <?  } ?>
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
                                                            if($_REQUEST['action'] == 'view') {
                                                                if($emcd != '')
                                                                    echo ": ".$emcd." - <b>".$emnm."</b> (".$dsnm.") - ".substr($senm, 3)." ";
                                                            } else { ?>
                                                                <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='7' onblur="find_tags();" name='txt_alternate_user' id='txt_alternate_user' data-toggle="tooltip" data-placement="top" data-original-title="Alternate User" value='<?=$emcd." - ".$emnm." - ".substr($senm, 3)?>'>
                                                        <?  } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Alternate User -->

                                                <!-- Attachments -->
                                                <!-- Quotations -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'quotations'", "Centra", 'TCS'); ?>
                                                <div class="form-group" id="id_txt_submission_quotations">
                                                    <label class="col-md-3 control-label">Quotations & Estimations</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD" tabindex='8' onblur="find_tags();" class="form-control fileselect" name='txt_submission_quotations[]' id='txt_submission_quotations' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                            <div class="tags_clear"></div>
                                                        <div><input type="text" placeholder="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD - REMARKS" tabindex='8' class="form-control bgclr_f8f8f8" name='txt_submission_quotations_remarks' id='txt_submission_quotations_remarks' maxlength="100" value='<?=$sql_reqid[0]['RMQUOTS']?>' data-toggle="tooltip" data-placement="top" title="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD - REMARKS" style="text-transform: uppercase;"></div>
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
                                                        // } ?></div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Quotations -->


                                                <!-- Approval Supporting Documents -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'fieldimpl'", "Centra", 'TCS'); ?>
                                                <div class="form-group" id="id_txt_submission_fieldimpl">
                                                    <label class="col-md-3 control-label">Budget / Common / Reference Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="BUDGET APPROVAL/COMMON RATE APPROVAL/LAST YEAR OR PREVIOUS APPROVAL/REFERENCE APPROVAL" tabindex='8' onblur="find_tags();" class="form-control fileselect" name='txt_submission_fieldimpl[]' id='txt_submission_fieldimpl' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="BUDGET APPROVAL/COMMON RATE APPROVAL/LAST YEAR OR PREVIOUS APPROVAL/REFERENCE APPROVAL"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                            <div class="tags_clear"></div>
                                                        <div><input type="text" placeholder="BUDGET APPROVAL/COMMON RATE APPROVAL/LAST YEAR OR PREVIOUS APPROVAL/REFERENCE APPROVAL - REMARKS" tabindex='8' class="form-control bgclr_f8f8f8" name='txt_submission_fieldimpl_remarks' id='txt_submission_fieldimpl_remarks' maxlength="100" value='<?=$sql_reqid[0]['RMBDAPR']?>' data-toggle="tooltip" data-placement="top" title="BUDGET APPROVAL/COMMON RATE APPROVAL/LAST YEAR OR PREVIOUS APPROVAL/REFERENCE APPROVAL - REMARKS" style="text-transform: uppercase;"></div>
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
                                                                        /* echo $fieldindi = "<a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" data-toggle=\"lightbox\" data-gallery=\"multiimages\" data-title=\"\" data-footer=\"<a target='_blank' download href='ftp://".$ftp_user_name.':'.$ftp_user_pass.'@'.$ftp_server.'/approval_desk/request_entry/'.$dataurl.'/'.$filename."' class='btn btn-success'><i class='fa fa-fw fa-download'></i> Download Image</a>&nbsp;&nbsp;<a href='javascript:void(0)' class='idrotate btn btn-primary'><i class='fa fa-fw fa-rotate-right'></i> Rotate</a>&nbsp;&nbsp;<button class='btn btn-primary zoom-in'>Zoom In <i class='fa fa-fw fa-plus'></i></button>&nbsp;&nbsp;<button class='btn btn-primary zoom-out'>Zoom Out <i class='fa fa-fw fa-minus'></i></button>&nbsp;&nbsp;<button class='btn btn-warning reset'>Reset <i class='fa fa-fw fa-refresh'></i></button>\" style=\"float:left; margin-bottom:10px\"><img src=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" class=\"img-responsive style_box\" id='image' border=0 style=\"width:100px; height:100px; margin-left:5px\"></a>"; */

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
                                                       // } ?></div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Approval Supporting Documents -->


                                                <!-- Color Photo Sample / Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'clrphoto'", "Centra", 'TCS'); ?>
                                                <div class="form-group" id="id_txt_submission_clrphoto">
                                                    <label class="col-md-3 control-label">Work Place Before / After Photo / Drawing Layout</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="WORK PLACE BEFORE / AFTER PHOTO COPY / DRAWING LAYOUT" tabindex='9' onblur="find_tags();" class="form-control fileselect" name='txt_submission_clrphoto[]' id='txt_submission_clrphoto' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="WORK PLACE BEFORE / AFTER PHOTO COPY / DRAWING LAYOUT"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                            <div class="tags_clear"></div>
                                                        <div><input type="text" placeholder="WORK PLACE BEFORE / AFTER PHOTO COPY / DRAWING LAYOUT - REMARKS" tabindex='9' class="form-control bgclr_f8f8f8" name='txt_submission_clrphoto_remarks' id='txt_submission_clrphoto_remarks' maxlength="100" value='<?=$sql_reqid[0]['RMCLRPT']?>' data-toggle="tooltip" data-placement="top" title="WORK PLACE BEFORE / AFTER PHOTO COPY / DRAWING LAYOUT - REMARKS" style="text-transform: uppercase;"></div>
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
                                                        // } ?></div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Color Photo Sample / Artwork -->


                                                <!-- Artwork -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'artwork'", "Centra", 'TCS'); ?>
                                                <div class="form-group" id="id_txt_submission_artwork">
                                                    <label class="col-md-3 control-label">Art Work Design with MD Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="ART WORK DESIGN WITH MD APPROVAL" tabindex='10' onblur="find_tags();" class="form-control fileselect" name='txt_submission_artwork[]' id='txt_submission_artwork' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="ART WORK DESIGN WITH MD APPROVAL"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                            <div class="tags_clear"></div>
                                                        <div><input type="text" placeholder="ART WORK DESIGN WITH MD APPROVAL - REMARKS" tabindex='10' class="form-control bgclr_f8f8f8" name='txt_submission_artwork_remarks' id='txt_submission_artwork_remarks' maxlength="100" value='<?=$sql_reqid[0]['RMARTWK']?>' data-toggle="tooltip" data-placement="top" title="ART WORK DESIGN WITH MD APPROVAL - REMARKS" style="text-transform: uppercase;"></div>
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
                                                        // } ?></div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Artwork -->


                                                <!-- CONSULTANT APPROVAL -->
                                                <? $sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS
                                                                                        where aprnumb = '".$sql_reqid[0]['APRNUMB']."' and APRHEAD = 'othersupdocs'", "Centra", 'TCS'); ?>
                                                <div class="form-group" id="id_txt_submission_othersupdocs">
                                                    <label class="col-md-3 control-label">Consultant Approval</label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <? if($_REQUEST['action'] != 'view') { ?>
                                                        <div><input type="file" placeholder="CONSULTANT APPROVAL" tabindex='10' onblur="find_tags();" class="form-control fileselect" name='txt_submission_othersupdocs[]' id='txt_submission_othersupdocs' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="CONSULTANT APPROVAL"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
                                                            <div class="tags_clear"></div>
                                                        <div><input type="text" placeholder="CONSULTANT APPROVAL - REMARKS" tabindex='10' class="form-control bgclr_f8f8f8" name='txt_submission_othersupdocs_remarks' id='txt_submission_othersupdocs_remarks' maxlength="100" value='<?=$sql_reqid[0]['RMCONAR']?>' data-toggle="tooltip" data-placement="top" title="CONSULTANT APPROVAL - REMARKS" style="text-transform: uppercase;"></div>
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
                                                        // } ?></div>
                                                        <div class='clear clear_both' style='min-height:1px;'></div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- CONSULTANT APPROVAL -->
                                                <!-- Attachments -->

                                                <!-- Warranty / Guarantee -->
                                                <div class="form-group" id="id_txt_warranty_guarantee">
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
                                                <!-- Warranty / Guarantee -->

                                                <!-- Current / Closing Stock -->
                                                <div class="form-group" id="id_txt_cur_clos_stock">
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
                                                <!-- Current / Closing Stock -->

                                                <!-- Advance or Final Payment / Work Completion Percentage -->
                                                <div class="form-group" id="id_txt_advpay_comperc">
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
                                                <!-- Advance or Final Payment / Work Completion Percentage -->

                                                <!-- Work Finish Target Date -->
                                                <div class="form-group" id="id_datepicker_example4">
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
                                                <!-- Work Finish Target Date -->

                                                <!-- Agreement Expiry Date -->
                                                <div class="form-group" id="id_datepicker_example7">
                                                    <label class="col-md-3 control-label">Agreement Expiry Date</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]["AGEXPDT"];
                                                            } else { ?>
                                                                <input type="text" tabindex='10' name="txt_agreement_expiry" id="datepicker_example7" class="form-control" readonly placeholder='Agreement Expiry Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_reqid[0]['AGEXPDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['AGEXPDT']))); } else { echo strtoupper(date("d-M-Y")); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Agreement Expiry Date'>
                                                        <?  } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Agreement Expiry Date -->

                                                <!-- Agreement Advance Amount -->
                                                <div class="form-group" id="id_txt_advpay_comperc">
                                                    <label class="col-md-3 control-label">Agreement Advance Amount</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <?  if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]["AGADVAM"];
                                                            } else { ?>
                                                                <input type='text' class="form-control" style="text-transform: uppercase;" tabindex='10' name='txt_agreement_advance' id='txt_agreement_advance' data-toggle="tooltip" data-placement="top" data-original-title="Agreement Advance Amount" placeholder="Agreement Advance Amount" value='<?=$sql_reqid[0]["AGADVAM"]?>'>
                                                        <?  } ?>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
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
                                                                <input class="form-control" placeholder="Prepared By" tabindex='10' onblur="find_tags();" type='hidden' readonly required maxlength='100' name='txtrequest_by' id='txtrequest_by' <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_emp3[0]['EMPCODE']." - ".$sql_emp3[0]["EMPNAME"]?>'<? } else { ?>value='<?=$_SESSION['tcs_user']." - ".strtoupper($_SESSION['tcs_username'])?>'<? } ?> data-toggle="tooltip" data-placement="top" title="Prepared By">
                                                                <input class="form-control" placeholder="Prepared By" type='hidden' tabindex='10' readonly required maxlength='10' name='txtrequest_byid' <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_emp3[0]['EMPSRNO']?>'<? } else { ?>value='<?=$_SESSION['tcs_empsrno']?>'<? } ?> id='txtrequest_byid' data-toggle="tooltip" data-placement="top" title="Prepared By">
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
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                <!-- Related Approval Nos -->

                                                <div class="form-group trbg" id='getmonthwise_budget' style='display:none;'>
                                                    <div class="col-lg-3 col-xs-3 control-label" style="text-align: right;">Budget Planner &#8377;</div>
                                                    <div class="col-lg-9 col-xs-9">
                                                        <div id='id_budplanner'></div>
                                                        <div>
                                                            <table style='clear:both; float:left; width:100%;'>
                                                            <tr><td>
                                                                <table class="monthyr_wrap" style='width:100%;'></table>
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
                                                    <div class="form-group" id='id_budgetmode' style="display: none;">
                                                        <label class="col-md-3 control-label">Budget Mode </label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <? if($_REQUEST['action'] == 'view') {
                                                                    $sql_bdmd = select_query_json("select * from APPROVAL_BUDGET_MODE
                                                                                                            where DELETED = 'N' and BUDCODE = '".$sql_reqid[0]['BUDCODE']."' order by BUDNAME", "Centra", 'TCS'); ?>
                                                                : <? if($sql_bdmd[0]['BUDNAME'] != '') { if($sql_bdmd[0]['BUDCODE'] == 5) { ?>
                                                                    <span class="badge badge-success" style='background-color:#08a208; font-weight:bold;'><?=$sql_bdmd[0]['BUDNAME'];?></span>
                                                                <? } else { echo $sql_bdmd[0]['BUDNAME']; } } else { echo "-"; } ?>
                                                            <? } else { ?>
                                                                <select class="form-control custom-select chosn" tabindex='11' name='slt_budgetmode' id='slt_budgetmode' data-toggle="tooltip" data-placement="top" title="Budget Mode">
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
                                                    <div class="form-group" id='id_apptype' style="display: none;">
                                                        <label class="col-md-3 control-label">Approval Type </label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <?  if($_REQUEST['action'] == 'view') {
                                                                    echo $sql_reqid[0]['APPTYPE'];
                                                                } else { ?>
                                                                <select class="form-control custom-select chosn" tabindex='13' name='slt_apptype' id='slt_apptype' data-toggle="tooltip" data-placement="top" title="Approval Type">
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
                                                                <div class='input-group date' id='datetimepicker9' tabindex='14' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input1">
                                                                    <input type='text' class="form-control" size="20" tabindex='15' name='txtfrom_date' required placeholder='From Date' id='txtfrom_date' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    <input type='hidden' class="form-control" size="20" tabindex='16' name='txtfrom_date1' required placeholder='From Date' id='txtfrom_date1' <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSFR_TIME']?>"<? } else { ?>value="<?=date("m-d-Y")?>"<? } ?> readonly data-toggle="tooltip" data-placement="top" title="From Date" />
                                                                    </span>
                                                                </div>
                                                                <input type="hidden" id="dtp_input1" name='dtp_input1' value="" />
                                                        <? } ?>
                                                    </div>

                                                    <div <? if($_REQUEST['action'] == 'view') { ?>class="col-lg-12 col-md-12"<? } else { ?>class="col-lg-6 col-md-6"<? } ?>>
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo "<b>To Date</b> : ".$sql_reqid[0]['APPRSTO_TIME'];
                                                           } else { ?>
                                                                <div class='input-group date' id='datetimepicker10' tabindex='17' data-date='<? echo date("Y-m-d"); ?>' data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input2" onblur="call_days()">
                                                                    <input type='text' class="form-control" size="20" tabindex='17' name='txtto_date' required placeholder='To Date' id='txtto_date' onblur="call_days()" type="text" <? if($_REQUEST['action'] == 'edit') { ?>value="<?=$sql_reqid[0]['APPRSTO_TIME']?>"<? } else { ?>value="<?=strtoupper(date("d-M-Y h:i:s A"))?>"<? } ?> onblur="call_days()" readonly data-toggle="tooltip" data-placement="top" title="To Date" />
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
                                                                <input class="form-control" placeholder="No of Hours" onKeyPress="return isNumber(event)" tabindex='18' maxlength='5' required name='txtnoofhours' id='txtnoofhours' readonly onfocus="date_diff()" <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRHURS']?>'<? } else { ?>value='24'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Hours">
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
                                                                <input class="form-control" placeholder="No of Days" onKeyPress="return isNumber(event)" maxlength='3' tabindex='19' required name='txtnoofdays' id='txtnoofdays' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRDAYS']?>'<? } else { ?>value='1'<? } ?> data-toggle="tooltip" data-placement="top" title="No of Days">
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
                                                        <input type='hidden' class="form-control hidn_balance" placeholder="Request Value" tabindex='20' onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly <? if($_REQUEST['action'] == 'edit') { ?>value='<?=$sql_reqid[0]['APRQVAL']?>'<? } else { ?>value='0'<? } ?> data-toggle="tooltip" data-placement="top" title="Request Value">
                                                    <? } ?>
                                                </div>



                                                <!-- Request Value -->
                                                <div id='id_reqvalue'>
                                                <? if($view == 1) { ?>
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">Request Value &#8377; </label>
                                                        <div class="col-md-9 col-xs-12">
                                                            <? if($_REQUEST['action'] == 'view') { ?>
                                                                : <?=moneyFormatIndia($sql_reqid[0]['APRQVAL'])?>
                                                                <input type='hidden' class="form-control hidn_balance" placeholder="Request Value" onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' onblur="find_tags();" readonly <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APRQVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Request Value">
                                                            <? } else { ?>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon">&#8377;</span>
                                                                    <input class="form-control hidn_balance" style="margin-top: 0px;" placeholder="Request Value" onKeyPress="return isNumber(event)" maxlength='9' name='txtrequest_value' id='txtrequest_value' readonly onblur="find_balance(this.value); find_tags();" <? if($_REQUEST['action'] == 'edit') { ?> value='<?=$sql_reqid[0]['APRQVAL']?>' <? } else { ?> value='0' <? } ?> data-toggle="tooltip" data-placement="top" title="Request Value">
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
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                                <input type="text" tabindex='22' onblur="find_tags();" name="impldue_date" id="datepicker_example3" class="form-control" required readonly placeholder='Implementation Due Date' <?=$rdonly;?> autocomplete='off' value='<? if($sql_reqid[0]['IMDUEDT'] != '') { echo strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT']))); } else { echo strtoupper(date("d-M-Y", strtotime("+14 days"))); } ?>' style='text-transform:uppercase; ' maxlength='11' title='Implementation Due Date'>
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
                                                                    $sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch
                                                                                                                from branch brn
                                                                                                                where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K', 'T') and
                                                                                                                    (brn.brncode in (select distinct brncode from budget_planner_head_sum) or
                                                                                                                    brn.brncode in (30,109,114,117,120,300)) and
                                                                                                                    brn.brncode not in (11, 22, 202, 205, 119)
                                                                                                                order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
                                                                } else {
                                                                    $sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch
                                                                                                                from branch brn
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
                                                                    <div class="col-xs-8" style="border: 1px solid #c0c0c0; display: none">
                                                                        <input type="hidden" class="form-control slt_brnchcls" name="slt_brnch[]" tabindex='13' id="slt_brnch_<?=$project_i?>" value="<?=$sql_project[$project_i]['BRNCODE']?>" style="margin:2px;">
                                                                        <input type="text" class="form-control" onblur="find_tags();" name="txt_brnvalue[]" id="txt_brnvalue_<?=$project_i?>" value="" style="margin:2px;">
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
                                                    <div class="form-group trbg">
                                                        <div class="col-lg-3 col-xs-3" style=" text-align: right;">
                                                            <label style='height:27px; text-align: right;'>Next Approval Flow <span style='color:red'>*</span></label>
                                                        </div>
                                                        <div class="col-lg-9 col-xs-9" style="font-weight:bold;">
                                                            : <? $flo = 0; $newentry = 0;
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
                                            <div class="col-md-12">
                                                <?  if($_REQUEST['action'] == 'view') {
                                                        echo ": ".$sql_reqid[0]['APPRDET'];
                                                   } else {
                                                        /* <textarea class="form-control" <? if($_REQUEST['action'] != 'edit') { } ?> tabindex='23' rows="10" placeholder="Details" required maxlength='400' name='txtdetails' id='txtdetails' data-toggle="tooltip" data-placement="top" title="Details" style='text-transform:uppercase' onKeyPress="return isQuotes(event)"><? echo $sql_reqid[0]['APPRDET']; ?></textarea>
                                                        <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 400 CHARACTERS ALLOWED..</span> */ ?>
                                                        <input type="hidden" name="hid_apprsub" id='hid_apprsub' value="<?=$sql_reqid[0]['APPRSUB']?>" onblur="find_tags();">
                                                        <textarea name="FCKeditor1" id="FCKeditor1" tabindex='14'>
                                                            <?  if($_REQUEST['action'] == 'edit') {
                                                                    if($sql_reqid[0]['APPRFOR'] == '1') {
                                                                        $filepathname = $sql_reqid[0]['APPRSUB'];
                                                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.":5022/approval_desk/text_approval_source/".$filepathname;
                                                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_reqid[0]['APPRSUB']);
                                                                        $contents = fread($handle, filesize($filename));
                                                                        fclose($handle);
                                                                        echo $contents;
                                                                    } else {
                                                                        echo ": ".$sql_reqid[0]['APPRDET'];
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
                                            </div>
                                        </div>

                                        <div class="form-group" style="display: none;">
                                            <label class="col-md-3 control-label">Tags</label>
                                            <div class="col-md-9">
                                                <input type="text" class="tagsinput" value="First,Second,Third"/>
                                                <span class="help-block">Default textarea field</span>
                                            </div>
                                        </div>




                                        <!-- Reference Approvals & Tags -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Reference / Common Approvals & Tags</strong></h3>
                                            </div>
                                            <div class="panel-body">
                                            <!-- Reference / Common Approvals -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Reference / Common Approvals</label>
                                                <div class="col-md-9 col-xs-12">
                                                    <?  /* if($_REQUEST['action'] == 'view') {
                                                            $sql_rlapr = explode(",", $sql_reqid[0]['RELAPPR']);
                                                            for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                $sql_apr = select_query("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1"); ?>
                                                                <a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
                                                            <? }
                                                       } else { */ ?>
                                                            <textarea class="form-control" tabindex='14' rows="3" placeholder="Reference / Common Approval Nos" maxlength='250' name='txt_related_approvals' id='txt_related_approvals' data-toggle="tooltip" data-placement="top" title="Reference / Common Approval Nos" style='text-transform:uppercase' onKeyPress="return isQuotes(event)" onblur="find_tags();"><? echo $sql_reqid[0]['RELAPPR']; ?></textarea>
                                                            <span style='color:#FF0000; font-size:10px;'>NOTE : MAXIMUM 250 CHARACTERS ALLOWED.. IF MORETHAN 1 APPROVALS ARE AVAILABLE SEPARATE WITH COMMA..</span>
                                                    <? // } ?>
                                                </div>
                                            </div>
                                            <div class="tags_clear"></div>
                                            <!-- Reference / Common Approvals -->

                                            <!-- Against Approval No -->
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Against Approval No</label>
                                                <div class="col-md-9 col-xs-12">
                                                    <?  if($_REQUEST['action'] == 'view') {
                                                            $sql_rlapr = explode(",", $sql_reqid[0]['AGNSAPR']);
                                                            for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
                                                                $sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
                                                                                                    where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TCS"); ?>
                                                                <a target="_blank" href='print_request.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
                                                            <? }
                                                       } else { ?>
                                                            <input type='text' class="form-control" tabindex='17' style="text-transform: uppercase;" maxlength="100" name='txt_against_approval' id='txt_against_approval' data-toggle="tooltip" data-placement="top" title="Against Approval No (Same Top Core Based Last 3 Months Approvals)" placeholder="Against Approval No (Same Top Core Based Last 3 Months Approvals)" value='<?=$sql_reqid[0]['AGNSAPR']?>'>
                                                    <? } ?>
                                                </div>
                                            </div>
                                            <div class="tags_clear"></div>
                                            <!-- Against Approval No -->

                                            <div id="id_tags_generation">
                                            </div>
                                        </div>
                                        </div>
                                        <div class="tags_clear"></div>
                                        <!-- Reference Approvals & Tags -->

                                        <!-- Approval Status & History -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong>Approval Status & History</strong></h3>
                                            </div>
                                            <div class="panel-body">
                                                <? if($sql_reqid[0]['ARQSRNO'] == 1) {
                                                        $sql_approval_levels = select_query_json("select req.REQDESN, req.REQESEN, req.APPRMRK, req.APPFVAL, req.APPFRWD, req.REQDESC, req.REQESEC,
                                                                                                            req.REQSTBY, (select EMPNAME from employee_office where empsrno = req.REQSTBY) frmemp,
                                                                                                            (select EMPNAME from employee_office where empsrno = req.REQSTFR) toemp,
                                                                                                            (select BRNNAME from branch where brncode = req.brncode) BRNNAME,
                                                                                                            to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time
                                                                                                        from APPROVAL_REQUEST req
                                                                                                        where req.ARQSRNO != 1 and req.ARQCODE = '".$_REQUEST['reqid']."' and
                                                                                                            req.ARQYEAR = '".$_REQUEST['year']."' and req.ATCCODE = '".$_REQUEST['creid']."' and
                                                                                                            req.ATYCODE = '".$_REQUEST['typeid']."' and req.deleted = 'N' and
                                                                                                            req.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'
                                                                                                        order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TCS');
                                                        for($sql_approval_levelsi = 0; $sql_approval_levelsi < count($sql_approval_levels); $sql_approval_levelsi++) { ?>
                                                    <div style='margin-top:15px; margin-left:15px; border:1px dashed #A0A0A0; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background-color:#F0F0F0'>
                                                        <div class="form-group trbg" style='min-height:40px;'>
                                                            <div class="col-lg-9 col-xs-9">
                                                                <label style='height:27px; text-transform:uppercase' class="blue_clr"><b><?=$sql_approval_levels[$sql_approval_levelsi]['FRMEMP']?> : </b></label><label style='height:27px; text-align:right; font-size:9px; text-transform:uppercase'><?=$sql_approval_levels[$sql_approval_levelsi]['REQDESN']?>, <?=$sql_approval_levels[$sql_approval_levelsi]['REQESEN']?>. <?=$sql_approval_levels[$sql_approval_levelsi]['BRNNAME']?></label>
                                                            </div>
                                                            <div class="col-lg-3 col-xs-3">
                                                                <label style='height:27px; text-align:right; font-size:9px; text-transform:uppercase'><?=$sql_approval_levels[$sql_approval_levelsi]['INTPFRD_TIME']?></label>
                                                            </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                        <div class="form-group trbg" style='min-height:40px;'>
                                                            <div class="col-lg-12 col-md-12">
                                                                Remarks : <? if($sql_approval_levels[$sql_approval_levelsi]['APPRMRK'] != '') { echo $sql_approval_levels[$sql_approval_levelsi]['APPRMRK']; } ?>
                                                            </div>
                                                        </div>
                                                        <div class="tags_clear"></div>

                                                        <div class="form-group trbg" style='min-height:40px;'>
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


                                                    <?  $appr_status = ''; $appr_clr = ''; $isshow = 0; $appr_class = '';
                                                    switch($sql_reqid[0]['APPSTAT'])
                                                    {
                                                        case 'A':
                                                            $appr_status = 'APPROVED';
                                                            $appr_clr    = '#E3FDE8';
                                                            $appr_class  = 'alert-success';
                                                            $isshow = 1;
                                                            break;
                                                        case 'R':
                                                            $appr_status = 'REJECTED';
                                                            $appr_clr = '#F2DEDE';
                                                            $appr_class  = 'alert-danger';
                                                            $isshow = 1;
                                                            break;
                                                        case 'P':
                                                            $appr_status = 'PENDING';
                                                            $appr_clr = '#FDF5E3';
                                                            $appr_class  = 'alert-warning';
                                                            $isshow = 1;
                                                            break;
                                                        default:
                                                            $appr_status = 'NOT YET APPROVED';
                                                            $appr_clr = '#E3F1FD';
                                                            $appr_class  = 'alert-info';
                                                            $isshow = 1;
                                                            break;
                                                    }
                                                    if($_REQUEST['action'] != '') { $isshow = 1; } else { $isshow = 0; }

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
                                        </div>
                                        <!-- Approval Status & History -->

                                    </div>

                                </div>
                                <div class="tags_clear"></div>



                                <?  if($_REQUEST['action'] == 'edit' and count($sql_prdlist) > 0) { ?>
                                <!-- Supplier Quotation -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><strong>Supplier Quotation</strong></h3>
                                        <? /* <ul class="panel-controls">
                                            <li><a href="javascript:void(0)" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        </ul> */ ?>
                                    </div>
                                    <div class="panel-body" style="padding: 0px 5px 0 0;">

                                        <!-- Supplier Quotation -->
                                        <div id='id_supplier' style="text-align: center; border: 0px solid #e2f5ff; padding: 5px;">
                                            <div class="parts3 fair_border">
                                                <? if(count($sql_prdlist) > 0) { ?>
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
                                                                                                            and PBDLSNO = '".$prdlist['PBDLSNO']."' and SLTSUPP = 1", "Centra", 'TEST'); ?>
                                                        <div class="row" style="margin-right: -5px; min-height: 25px; display: flex; background-color: #FFFFFF; text-transform: uppercase;">
                                                            <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                <div class="fg-line">&nbsp;<?=$inc?></div>
                                                            </div>

                                                            <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                                                <div style="clear: both;"></div>
                                                                <div style="width: 49%; float: left;">
                                                                    <input type="text" name="txt_prdcode[]" id="txt_prdcode_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?>" required="required" maxlength="100" placeholder="Product" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty(<?=$inc?>)" style=" text-transform: uppercase; padding: 0px;height: 25px;">
                                                                </div>
                                                                <div style="width: 49%; float: left;margin-left: 2px;">
                                                                    <input type="hidden" name="txt_pbdlsno[]" id="txt_pbdlsno_<?=$inc?>" value="<?=$prdlist['PBDLSNO']?>">
                                                                    <input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_<?=$inc?>" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" value="<?=$prdlist['USESECT']?>">
                                                                    <input type="text" name="txt_subprdcode[]" id="txt_subprdcode_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?>" maxlength="100" placeholder="Sub Product" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Sub Product" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty(<?=$inc?>)" style=" text-transform: uppercase;height: 25px;">

                                                                    <input type="hidden" onKeyPress="enable_product();" name="txt_unitname[]" id="txt_unitname_<?=$inc?>" readonly="readonly" value="<?=$prdlist['UNTNAME']?>" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                                                    <input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_<?=$inc?>" value="<?=$prdlist['UNTCODE']?>" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                                                </div>
                                                                <div style="clear: both; height: 1px;"></div>

                                                                <div>
                                                                    <input type="text" name="txt_prdspec[]" id="txt_prdspec_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['PRDSPEC']?>" required="required" maxlength="100" placeholder="Product Specification" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty(<?=$inc?>)" style=" text-transform: uppercase;height: 25px;">
                                                                </div>
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
                                                                    <!-- Product Image -->
                                                                </div>
                                                                </div>
                                                                <div style="clear: both;"></div>
                                                            </div>

                                                            <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                                                <div style="width: 49%; float: left;">
                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_<?=$inc?>" value="<?=$prdlist['ADURATI']?>" required="required" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                                                </div>
                                                                <div style="width: 49%; float: left; margin-left: 2px;">
                                                                    <input type="text" name="txt_print_location[]" id="txt_print_location_<?=$inc?>" onblur="find_tags();" value="<?=$prdlist['ADLOCAT']?>" required="required" maxlength="25" placeholder="Ad. Print Location" onKeyPress="enable_product();" data-toggle="tooltip" data-placement="top" title="Ad. Print Location" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >
                                                                </div>
                                                                <div style="clear: both;"></div>

                                                                <div style="width: 49%; float: left;">
                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_<?=$inc?>" value="<?=$prdlist['ADLENGT']?>" required="required" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
                                                                </div>
                                                                <div style="width: 49%; float: left; margin-left: 2px;">
                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_<?=$inc?>" value="<?=$prdlist['ADWIDTH']?>" required="required" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot" onblur="calculateqtyamount('<?=$inc?>'); find_tags();" style=" text-transform: uppercase;height: 25px;" >
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
                                                                                        <? /* if($inc1 == 1) { ?>
                                                                                            <input type="hidden" name="partint3_1" id="partint3_1" value="<?=count($sql_prdquotlist)?>">
                                                                                            <button class="btn btn-success btn-add3" id="addbtn_1" type="button" title="Add Suppliers" onclick="call_innergrid(1)"><span class="glyphicon glyphicon-plus"></span></button>
                                                                                            <button id="removebtn_1" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" onclick="call_innergrid_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>
                                                                                        <? } */ ?>
                                                                                        <input type="hidden" name="txt_prdsgst_per[<?=$inc?>][]" id="txt_prdsgst_per_<?=$inc?>_<?=$inc1?>" value="<?=$prd_sgst?>"><input type="hidden" name="txt_prdcgst_per[<?=$inc?>][]" id="txt_prdcgst_per_<?=$inc?>_<?=$inc1?>" value="<?=$prd_cgst?>"><input type="hidden" name="txt_prdigst_per[<?=$inc?>][]" id="txt_prdigst_per_<?=$inc?>_<?=$inc1?>" value="<?=$prd_igst?>">

                                                                                        &nbsp;<input type="radio" onKeyPress="enable_product();" <? if($prdquotlist['SLTSUPP'] == 1) { ?> checked="checked" <? } ?> value='<?=$inc1?>' onclick="getrequestvalues(<?=$inc?>, <?=$inc1?>, <?=count($sql_prdquotlist)?>)" name="txt_sltsupplier[<?=$inc?>][]" id='txt_sltsupplier_<?=$inc?>_<?=$inc1?>' data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;<?=$inc1?></b>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-3 colheight" style="padding: 1px 0px;">
                                                                                    <input type="text" name="txt_sltsupcode[<?=$inc?>][]" id="txt_sltsupcode_<?=$inc?>_<?=$inc1?>" onblur="find_tags();" value="<?=$prdquotlist['SUPCODE']." - ".$prdquotlist['SUPNAME']?>" onKeyPress="enable_product();" required="required" maxlength="100" placeholder="Supplier" onBlur="validate_supprdempty(<?=$inc?>, <?=$inc1?>)" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" style=" text-transform: uppercase;height: 25px;">
                                                                                    <input type="hidden" name="txt_prlstsr[<?=$inc?>][]" id="txt_prlstsr_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['PRLSTSR']?>">

                                                                                </div>

                                                                                <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" onblur="find_tags();" name="txt_delivery_duration[<?=$inc?>][]" id="txt_delivery_duration_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['DELPRID']?>" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                </div>

                                                                                <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate[<?=$inc?>][]" id="txt_prdrate_<?=$inc?>_<?=$inc1?>" placeholder="Product Per Piece Rate" value="<?=$prdquotlist['PRDRATE']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">Adv.Amount Val.:
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount[<?=$inc?>][]" id="txt_advance_amount_<?=$inc?>_<?=$inc1?>" required="required" maxlength="10" placeholder="Advance Amount Value" value="<?=$prdquotlist['ADVAMNT']?>" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                </div>

                                                                                <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdsgst[<?=$inc?>][]" id="txt_prdsgst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['SGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdcgst[<?=$inc?>][]" id="txt_prdcgst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['CGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdigst[<?=$inc?>][]" id="txt_prdigst_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['IGSTVAL']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                </div>


                                                                                <div class="col-sm-1 colheight" style="padding: 1px 0px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc[<?=$inc?>][]" id="txt_spldisc_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['SPLDISC']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless[<?=$inc?>][]" id="txt_pieceless_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['PIECLES']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
                                                                                    <input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc[<?=$inc?>][]" id="txt_prddisc_<?=$inc?>_<?=$inc1?>" value="<?=$prdquotlist['DISCONT']?>" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">
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
                                                                                    <textarea onKeyPress="enable_product();" name="txt_suprmrk[<?=$inc?>][]" id="txt_suprmrk_<?=$inc?>_<?=$inc1?>" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('<?=$inc?>','<?=$inc1?>'); find_tags();"class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"><?=$prdquotlist['SUPRMRK']?></textarea>
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
                                    </div>

                                    </div>
                                </div>
                                <!-- Supplier Quotation -->
                                <div class="tags_clear"></div>
                                <? } else { ?>
                                    <!-- Supplier Quotation -->
                                    <div id='id_supplier' style="text-align: left; border: 0px solid #e2f5ff; padding: 5px;">
                                        <div class='clear clear_both'></div>
                                    </div>
                                    <!-- Supplier Quotation -->
                                <? } ?>



                                <!-- Policy Docs -->
                                <? /* <div>
                                    <div id='id_policy_approval' style="padding-left: 10px; text-align: center; display: none;">
                                        <div class="parts3 fair_border">
                                            <div class="row colheight" style="margin-right: -5px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; line-height: 25px; font-weight: bold; text-align: center;">
                                                POLICY - APPROVAL
                                            </div>
                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">POLICY SUBJECT : </div>
                                                <div class="col-sm-9 colheight" style="padding: 0px; line-height: 30px;">
                                                    <select class="form-control policy_approval_required" required name='txtdynamic_subject' id='txtdynamic_subject' data-toggle="tooltip" data-placement="top" data-original-title="POLICY SUBJECT">
                                                        <?  $sql_project = select_query_json("select * from approval_policy_master where DELETED = 'N' order by aplcysr", "Centra", 'TEST');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                <option value='<?=$sql_project[$project_i]['APLCYCD']?>' <? if($sql_reqid[0]['APLCYCD'] == $sql_project[$project_i]['APLCYCD']) { ?> selected <? } ?>><?=$sql_project[$project_i]['APLCYNM']?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1 colheight" style="padding: 0px; text-align: center; line-height: 30px; font-weight: bold;"><?=strtoupper(date('d-M-Y'));?></div>
                                            </div>

                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">EFFECTIVE DATE : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type="text" name="txtdynamic_efct_date" id="datepicker_example5" class="form-control policy_approval_required" required readonly placeholder='EFFECTIVE DATE' autocomplete='off' value='<?=strtoupper(date("d-M-Y"));?>' style='text-transform:uppercase; ' maxlength='11' title='EFFECTIVE DATE'>
                                                </div>

                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">POLICY TYPE : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <select class="form-control policy_approval_required" required name='txtdynamic_policy_type' id='txtdynamic_policy_type' data-toggle="tooltip" data-placement="top" data-original-title="POLICY TYPE">
                                                        <option value='ORIGINAL'>ORIGINAL</option>
                                                        <option value='RENEWAL'>RENEWAL</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">VALID UPTO : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type="text" name="txtdynamic_valid_upto" id="datepicker_example6" class="form-control policy_approval_required" required readonly placeholder='VALID UPTO' autocomplete='off' value='<?=strtoupper(date("d-M-Y"));?>' style='text-transform:uppercase; ' maxlength='11' title='VALID UPTO'>
                                                </div>

                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">CREATOR : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="CREATOR" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">CO-ORDINATOR : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="CO-ORDINATOR" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                </div>

                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">ASSIST BY : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_assistby' id='txtdynamic_assistby' data-toggle="tooltip" data-placement="top" data-original-title="ASSIST BY" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">USER LIST : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_userlist' id='txtdynamic_userlist' data-toggle="tooltip" data-placement="top" data-original-title="USER LIST" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                </div>

                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">APPROVED BY : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_approvedby' id='txtdynamic_approvedby' data-toggle="tooltip" data-placement="top" data-original-title="APPROVED BY" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">DESK PROCEDURE : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='file' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_deskprocedure' id='txtdynamic_deskprocedure' data-toggle="tooltip" data-placement="top" data-original-title="DESK PROCEDURE" value=''>
                                                </div>

                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: right; line-height: 30px;">POLICY DOCUMENTS : </div>
                                                <div class="col-sm-4 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='file' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_policy_docs' id='txtdynamic_policy_docs' data-toggle="tooltip" data-placement="top" data-original-title="POLICY DOCUMENTS" value=''>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class='clear clear_both'></div>
                                    </div> */ ?>
                                    <div class='clear clear_both'></div>
                                </div>
                                <!-- Policy Docs-->
                            </div>
                            <div class="panel-footer">
                                <button type="reset" tabindex='40' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Clear Form" onclick="return reload_page();" style="padding: 6px 12px;"><i class="fa fa-times"></i> Clear Form</button>
                                    <input type='hidden' name='hid_gtvl' id='hid_gtvl' value='0'>
                                <? if($_REQUEST['action'] == 'edit' && $sql_reqid[0]['APRNUMB'] != '') { ?>
                                    <input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
                                    <input type='hidden' name='hid_reqqid' id='hid_reqqid' value='<?=$sql_reqid[0]['IMUSRIP']?>'>
                                    <input type='hidden' name='hid_year' id='hid_year' value='<?=$_REQUEST['year']?>'>
                                    <input type='hidden' name='hid_typeid' id='hid_typeid' value='<?=$_REQUEST['typeid']?>'>
                                    <input type='hidden' name='hid_creid' id='hid_creid' value='<?=$_REQUEST['creid']?>'>
                                    <input type='hidden' name='hid_rsrid' id='hid_rsrid' value='<?=$_REQUEST['rsrid']?>'>
                                    <button type="submit" name='sbmt_update' id='sbmt_update' tabindex='40' value='SB' class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" title="Update"style="padding: 6px 12px;"><i class="fa fa-save"></i> Update</button>
                                <? } else { ?>
                                    <button type="submit" name='sbmt_request' id='sbmt_request' tabindex='40' value='submit' class="btn btn-success pull-right" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit" style="padding: 6px 12px;"><i class="fa fa-save"></i> Submit</button>
                                <? } ?>
                                <input type="hidden" name="hid_default_lock" id="hid_default_lock" value="0">
                                <input type="hidden" name="tempidcnt" id="tempidcnt" value="0">
                                <input type="hidden" name="allcal" id="allcal" value="0">
                                <input type="hidden" name="editor_detail" id="editor_detail" value="">
                            </div>
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

    <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS -->

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
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
    <script type="text/javascript" src="js/angular-route.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/angular-route-segment.min.js"></script>
    <script type="text/javascript">
    /* var app = angular.module('myApp', []);
    app.controller('tags_generation', function($scope, $http) {
       $http.get("php/json.php").then(function (response) {$scope.names = response.data.records;});
       $scope.onchange = function(id){
            $scope.filt = id.id;
            //alert(""+$scope.filt);
            $http.get("json_filter2.php?subtype_id="+$scope.filt+"").then(function (response) {$scope.name = response.data.records;});
        }

    }); */

    $(document).ready(function() {
        $(".chosn").customselect();
        $("#load_page").fadeOut("slow");
        find_checklist();
    });

    function find_checklist() {
        $('#load_page').show();
        var slt_targetno = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var strURL="ajax/ajax_validate.php?action=fix_checklist&slt_targetno="+slt_targetno+"&slt_submission="+slt_submission;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // alert("++++++++++++"+data1);
                var chklist = data1.split(",");

                $("#id_txt_submission_quotations").css("display", "none");
                $('#txt_submission_quotations').prop('required', false);
                $("#id_txt_submission_fieldimpl").css("display", "none");
                $('#txt_submission_fieldimpl').prop('required', false);
                $("#id_txt_submission_clrphoto").css("display", "none");
                $('#txt_submission_clrphoto').prop('required', false);
                $("#id_txt_submission_artwork").css("display", "none");
                $('#txt_submission_artwork').prop('required', false);
                $("#id_txt_submission_othersupdocs").css("display", "none");
                $('#txt_submission_othersupdocs').prop('required', false);
                $("#id_txt_warranty_guarantee").css("display", "none");
                $('#txt_warranty_guarantee').prop('required', false);
                $("#id_txt_cur_clos_stock").css("display", "none");
                $('#txt_cur_clos_stock').prop('required', false);
                $("#id_txt_advpay_comperc").css("display", "none");
                $('#txt_advpay_comperc').prop('required', false);
                $("#id_datepicker_example4").css("display", "none");
                $('#datepicker_example4').prop('required', false);

                var enable1 = 0; var enable2 = 0; var enable3 = 0; var enable4 = 0;
                var enable5 = 0; var enable6 = 0; var enable7 = 0; var enable8 = 0; var enable9 = 0;
                for (var exp_chklisti=0; exp_chklisti < chklist.length; exp_chklisti++) {
                    // alert(chklist[exp_chklisti]+"**"+exp_chklisti+"**"+chklist.length);

                    if(chklist[exp_chklisti] == 1) {
                        enable1 = 1;
                        $("#id_txt_submission_quotations").css("display", "block");
                        // $('#txt_submission_quotations').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 2) {
                        enable2 = 1;
                        $("#id_txt_submission_fieldimpl").css("display", "block");
                        // $('#txt_submission_fieldimpl').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 3) {
                        enable3 = 1;
                        $("#id_txt_submission_clrphoto").css("display", "block");
                        // $('#txt_submission_clrphoto').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 4) {
                        enable4 = 1;
                        $("#id_txt_submission_artwork").css("display", "block");
                        // $('#txt_submission_artwork').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 5) {
                        enable5 = 1;
                        $("#id_txt_submission_othersupdocs").css("display", "block");
                        // $('#txt_submission_othersupdocs').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 6) {
                        enable6 = 1;
                        $("#id_txt_warranty_guarantee").css("display", "block");
                        // $('#txt_warranty_guarantee').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 7) {
                        enable7 = 1;
                        $("#id_txt_cur_clos_stock").css("display", "block");
                        // $('#txt_cur_clos_stock').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 8) {
                        enable8 = 1;
                        $("#id_txt_advpay_comperc").css("display", "block");
                        // $('#txt_advpay_comperc').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 9) {
                        enable9 = 1;
                        $("#id_datepicker_example4").css("display", "block");
                        // $('#datepicker_example4').prop('required', true);
                    }
                }


                $('#load_page').hide();
            }
        });
    }

    function find_tags() {
        // alert("**CAME**");
        $('#load_page').show();
        CKEDITOR.instances.FCKeditor1.updateElement();
        var data_serialize = $("#frm_request_entry").serializeArray();
        $.ajax({
            type: 'post',
            url: "ajax/ajax_tags_generator.php",
            // dataType: 'json',
            data: data_serialize,
            beforeSend: function() {
                $('#load_page').show();
            },
            success: function(response)
            {
                $("#id_tags_generation").html('');
                $("#id_tags_generation").html(response);
                $('#load_page').hide();
            },
            error: function(response, status, error)
            {
                /* var ALERT_TITLE = "Message";
                var ALERTMSG = "Tags Generation Failure. Kindly try again!!";
                createCustomAlert(ALERTMSG, ALERT_TITLE); */
                $('#load_page').hide();
            }
        });
    }

    function reload_page() {
        $('#load_page').show();
        location.reload();
        $('#load_page').show();
    }

    function checkform() {
        $("#frm_request_entry").on('submit',(function(e) {
            // alert("~~");
            e.preventDefault();
            checkform_check();

            // var flag = checkform();
            var flag = $('#hid_gtvl').val();
            alert("=##="+flag+"=##=");
            if(flag == 1) {
                /* var value = CKEDITOR.instances['FCKeditor1'].getData();
                $('#editor_detail').val(value);
                // alert("**"+value); exit; */

                CKEDITOR.instances.FCKeditor1.updateElement();
                var data_serialize = $("#frm_request_entry").serialize();
                // var editor = CKEDITOR.instances.FCKeditor1;
                // for (instance in CKEDITOR.instances) {
                    // CKEDITOR.instances.FCKeditor1.updateElement();
                // }

                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: "lib/process_connect.php",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    // dataType: 'json',
                    // data: data_serialize,
                    beforeSend: function() {
                        $('#sbmt_request').attr('disabled', true);
                        // $('#loader').html('<span class="wait">&nbsp;<img src="images/loading.gif" alt="" /></span>');
                        $('#load_page').show();
                    },
                    complete: function() {
                        $('#sbmt_request').attr('disabled', false);
                        // $('.wait').remove();
                        ///////** $('#load_page').hide();
                    },
                    success: function(response)
                    {
                        // alert("++++++"+response+"+++++++");
                        if(response.type == 'error') {
                            /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('#sbmt_request').attr('disabled', false);
                            // $('#loader').html('');
                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("err-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        } else {
                            /* output = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                            output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Created Successfully!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('input[type=text]').val('');

                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("suc-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        }
                        $('#load_page').hide();

                        // $("#result").hide().html(output).slideDown();
                    },
                    error: function(response, status, error)
                    {
                        // var err = eval("(" + response.responseText + ")");
                        // alert(err.Message);

                        /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                        output += response.msg+'</div>';
                        $('#sbmt_request').attr('disabled', false); */
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);

                        // $('#loader').html('');
                        /////** window.location="request_entry.php";
                        /////** $('#load_page').show();

                        // alert(response.info + "err0r-" + response.msg);
                        //if(response.info != '')
                            // window.location = response.info;
                    }
                });
            } else {
                e.preventDefault();
            }
        }));
    }



        // alert("||");
        // $("#sbmt_request").click(function() {
            $("#frm_request_entry").validate({
        // $("#frm_request_entry").on('submit',(function(e) {
            // alert("~~");
            // e.preventDefault();

            submitHandler: function(form) {
            // var flag = checkform();
            var flag = $('#hid_gtvl').val();
            alert("=#="+flag+"=#=");
            if(flag == 1)
            {
                /* var value = CKEDITOR.instances['FCKeditor1'].getData();
                $('#editor_detail').val(value);
                // alert("**"+value); exit; */

                CKEDITOR.instances.FCKeditor1.updateElement();
                var data_serialize = $("#frm_request_entry").serialize();
                // var editor = CKEDITOR.instances.FCKeditor1;
                // for (instance in CKEDITOR.instances) {
                    // CKEDITOR.instances.FCKeditor1.updateElement();
                // }

                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: "lib/process_connect.php",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    // dataType: 'json',
                    // data: data_serialize,
                    beforeSend: function() {
                        $('#sbmt_request').attr('disabled', true);
                        // $('#loader').html('<span class="wait">&nbsp;<img src="images/loading.gif" alt="" /></span>');
                        $('#load_page').show();
                    },
                    complete: function() {
                        $('#sbmt_request').attr('disabled', false);
                        // $('.wait').remove();
                        ///////** $('#load_page').hide();
                    },
                    success: function(response)
                    {
                        // alert("++++++"+response+"+++++++");
                        if(response.type == 'error') {
                            /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('#sbmt_request').attr('disabled', false);
                            // $('#loader').html('');
                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("err-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        } else {
                            /* output = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                            output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Created Successfully!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('input[type=text]').val('');

                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("suc-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        }
                        $('#load_page').hide();

                        // $("#result").hide().html(output).slideDown();
                    },
                    error: function(response, status, error)
                    {
                        // var err = eval("(" + response.responseText + ")");
                        // alert(err.Message);

                        /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                        output += response.msg+'</div>';
                        $('#sbmt_request').attr('disabled', false); */
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);

                        // $('#loader').html('');
                        /////** window.location="request_entry.php";
                        /////** $('#load_page').show();

                        // alert(response.info + "err0r-" + response.msg);
                        //if(response.info != '')
                            // window.location = response.info;
                    }
                });
            } else {
                e.preventDefault();
            }
        }
        }); 
        // );

    //upload Only PDF file
    function ValidateSingleInput(oInput, file_ext) {
        $('#load_page').show();
        if(file_ext == 'pdf') {
            var _validFileExtensions = [".pdf",".PDF"];
        } else {
            var _validFileExtensions = [".jpg",".jpeg",".png",".gif",".pdf",".JPG",".JPEG",".PNG",".GIF",".PDF"];
        }
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
                    // alert("Sorry, Upload Only PDF file Format");
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Kindly Upload Only PDF file. Other Formats not allowed!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    oInput.value = "";
                    return false;
                }
            }
            $('#load_page').hide();
        }
        return true;
    }


    function fix_maxpercentage(maxpercentage) {
        var crnt_percent = $('#txt_adv_amount').val();
        if(crnt_percent > maxpercentage) {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Not allowed more than "+maxpercentage+" %. So Kindly reduce this!!";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            $('#txt_adv_amount').val(0)
        }
    }

    function addempnit(id)
    {
        var empcode = $("#txt_staffcode_"+id).val();
        var nempcode = empcode.split('-');
        var str2 = "-";
        if(empcode.indexOf(str2) != -1){
            var brncode = $("#slt_branch").val();
            var strURL="ajax/ajax_staffchange.php?action=night&empcode="+empcode+"&id="+id+"&brncode="+brncode;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    if(data != 0){
                        $("#photo_"+id).html(data);
                        var hid_data = $("#hid_emp_"+id).val();
                        var val = hid_data.split("~");
                        $('#curdep_'+id).val(val[3]);
                        $('#curdes_'+id).val(val[4]);

                        $('#descode_'+id).val(val[5]);
                        $('#esecode_'+id).val(val[6]);

                        $('#empsrno_'+id).val(val[7]);
                        $('#curbrn_'+id).val(val[2]);
                    }else{
                        $('#curdep_'+id).attr('readonly',false);
                        $('#curdes_'+id).attr('readonly',false);
                    }
                }
            });
        }else{
            $('#curdep_'+id).attr('readonly',false);
            $('#curdes_'+id).attr('readonly',false);
        }
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
        $('#load_page').show(); // call_dynamic_option
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
            // console.log("**"+txt_prdrate+"**"+prdcst+"**");
            txt_prdsgst = roundTo(txt_prdsgst,4);
            txt_prdcgst = roundTo(txt_prdcgst,4);
            txt_prdigst = roundTo(txt_prdigst,4);
            $('#id_sltrate_'+opt1).html(txt_prdrate);
            $('#id_sltsgst_'+opt1).html(txt_prdsgst);
            $('#id_sltcgst_'+opt1).html(txt_prdcgst);
            $('#id_sltigst_'+opt1).html(txt_prdigst);
            /* $('#id_sltslds_'+opt1).html(txt_spldisc);
            $('#id_sltpcls_'+opt1).html(txt_pieceless); */
            $('#id_sltdisc_'+opt1).html(txt_prddisc);
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
                if(document.getElementById('npobudget'))
                {
                    document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
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
                document.getElementById('txt_brnvalue_0').value = requestedvalue;
                document.getElementById('hidrequest_value').value = requestedvalue;
                if(document.getElementById('npobudget'))
                {
                    document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
                    calculate_sum();
                }

                $('.hidn_balance').val(requestedvalue);
                /* console.log(document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value); */
                getapproval_listings_only();
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
        // getapproval_listings();
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
        // getapproval_listings();
        $('#load_page').hide();
    }

    // validate the product textbox
    function validate_prdempty(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=product&validate_code="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
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
        // $('#load_page').hide();
    }
    // validate the product textbox

    // validate the sub product textbox
    function validate_subprdempty(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=sub_product&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
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
        // $('#load_page').hide();
    }
    // validate the sub product textbox

    // find the unit code from sub product textbox
    function find_unitcode(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_unitcode&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
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
		find_nof_supplier_product(iv);
        // find_hsncode(iv);
        // $('#load_page').hide();
    }
    // find the unit code from sub product textbox
	
	// Fix Nof Suppliers based on chosen Product
    function find_nof_supplier_product(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=fix_nof_suppliers&sub_prdcode="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
				$("#hid_nof_suppliers_"+iv).val(data1);
            }
        });
        $('#load_page').hide();
    }
    // Fix Nof Suppliers based on chosen Product

    // find the HSN Code based on the chosen product & sub product based
    /* function find_hsncode(iv) {
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_hsncode&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
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
        $('#load_page').hide();
        // $('#load_page').show();
        var slt_core_department = $("#slt_core_department").val();
        var sup_prdcode = $("#txt_sltsupcode_"+iv+"_"+jv).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_validate.php?action=supplier&validate_code="+sup_prdcode+"&slt_core_department="+slt_core_department+"&brncode="+slt_brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
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
        // $('#load_page').hide();
    }
    // validate the supplier textbox

    // assign tax based on the chosen product / sub product
    function fix_tax(iv, jv) {
        $('#load_page').hide();
        // $('#load_page').show();
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

                        $('#txt_taxpercentage_'+iv+'_'+jv).html('SGST - '+reslt[0]+' %; CGST - '+reslt[1]+' %;');
                    }else{
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdigst_per_'+iv+'_'+jv).val(reslt[2]);
                        $('#txt_taxpercentage_'+iv+'_'+jv).html('IGST - '+reslt[2]+' %;');
                    }
                }
                // $('#load_page').hide();
            }
        });
        // $('#load_page').hide();
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
                // var id = ($('.parts3 .part3').length + 2).toString();
                var id = (+$('#partint3').val() + 1);
                $('#partint3').val(id);
                var apnd_content = '<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
                                        '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line">&nbsp;'+id+'</div>'+
                                        '</div>'+
                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" name="txt_prdcode[]" id="txt_prdcode_'+id+'" required="required" maxlength="100" placeholder="Product" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty('+id+'); find_tags();" style=" text-transform: uppercase; padding: 0px;height: 25px;"></div>'+

                                            '<div style="width: 49%; float: left;margin-left: 2px;">'+
                                                '<input type="text" name="txt_subprdcode[]" id="txt_subprdcode_'+id+'" maxlength="100" placeholder="Sub Product" data-toggle="tooltip" data-placement="top" title="Sub Product" onKeyPress="enable_product();" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty('+id+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+

                                                '<input type="hidden" readonly="readonly" name="txt_unitname[]" id="txt_unitname_'+id+'" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                                '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_'+id+'" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both; height: 1px;"></div>'+

                                            '<div>'+
                                                '<input type="text" name="txt_prdspec[]" id="txt_prdspec_'+id+'" required="required" maxlength="100" placeholder="Product Specification" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty('+id+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div>'+
                                                '<!-- Product Image -->'+
                                                '<input type="file" name="fle_prdimage[]" id="fle_prdimage_'+id+'" data-toggle="tooltip" onchange="ValidateSingleInput(this); find_tags();" accept="image/jpg,image/jpeg,image/png,image/jpg" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Product Image" placeholder="Product Image" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : ONLY JPG, PNG IMAGES ALLOWED.</span>'+
                                                '<!-- Product Image -->'+
                                            '</div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" name="txt_print_location[]" id="txt_print_location_'+id+'" maxlength="25" placeholder="Ad. Print Location" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Ad. Print Location" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div style="width: 49%; float: left;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div><input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_'+id+'" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="enable_product(); return numwodot(event)" name="txt_prdqty[]" id="txt_prdqty_'+id+'" required="required" maxlength="6" placeholder="Qty" onblur="calculateqtyamount('+id+'); find_tags();" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
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
                                                            '<input type="hidden" name="partint3_'+id+'" id="partint3_'+id+'" value="1"><input type="hidden" name="hid_nof_suppliers_'+id+'" id="hid_nof_suppliers_'+id+'" value="1"><input type="hidden" name="txt_prdsgst_per['+id+'][]" id="txt_prdsgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdcgst_per['+id+'][]" id="txt_prdcgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdigst_per['+id+'][]" id="txt_prdigst_per_'+id+'_1" value="">'+
                                                            '<button class="btn btn-success btn-add3" id="addbtn_'+id+'" type="button" title="Add Suppliers" onclick="call_innergrid('+id+')" style="padding: 2px 5px; margin-right: 4px !important;"><span class="glyphicon glyphicon-plus"></span></button>'+
                                                            '<button id="removebtn_'+id+'" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" onclick="call_innergrid_remove('+id+')" style="padding: 2px 5px; margin-right: 0px !important;"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;<br><input type="radio" checked="checked" name="txt_sltsupplier['+id+'][]" id="txt_sltsupplier_'+id+'_1" value="1" onclick="getrequestvalue('+id+', 1)" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;1<br>';

                                                            if(id == 1) {
                                                                apnd_content += '<input type="checkbox" name="chk_apply_supplier['+id+'][]" id="chk_apply_supplier_'+id+'_1" onclick="apply_supplier('+id+')">';
                                                            }

                                                apnd_content += '</div>'+
                                                    '</div>'+

                                                    '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" name="txt_sltsupcode['+id+'][]" id="txt_sltsupcode_'+id+'_1" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" onKeyPress="enable_product();" data-placement="top" title="Supplier" class="form-control supquot find_supcode sub_prd_1" onBlur="validate_supprdempty('+id+', 1); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" name="state['+id+'][]" id="state'+id+'_1" value="" class="sub_prd_1"><span id="txt_taxpercentage_'+id+'_1"></span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_delivery_duration['+id+'][]" id="txt_delivery_duration_'+id+'_1" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate['+id+'][]" id="txt_prdrate_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        'Adv.Amount Val.:'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+id+'][]" id="txt_advance_amount_'+id+'_1" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdsgst['+id+'][]" id="txt_prdsgst_'+id+'_1"  onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdcgst['+id+'][]" id="txt_prdcgst_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdigst['+id+'][]" id="txt_prdigst_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;"> '+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc['+id+'][]" id="txt_spldisc_'+id+'_1" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless['+id+'][]" id="txt_pieceless_'+id+'_1" required="required" maxlength="5" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc['+id+'][]" id="txt_prddisc_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="Discount % " data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount['+id+'][]" id="hid_prdnetamount_'+id+'_1" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+id+'_1">0</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="file" name="fle_supquot['+id+'][]" id="fle_supquot_'+id+'_1" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" onblur="find_tags();" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                                        '<textarea onKeyPress="enable_product();" name="suprmrk['+id+'][]" id="suprmrk_'+id+'_1" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+

                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                    '</div>'+
                                    '</div><script>$("#fle_prdimage_'+id+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});$("#fle_supquot_'+id+'_1").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                $('.parts3').append(apnd_content);
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

        var nofsup = $("#hid_nof_suppliers_"+iv).val();
        if(nofsup > 1) {
            for(var nofsupi = 1; nofsupi <= nofsup; nofsupi++){
                call_innergrid(id);
            }
        }
        $('#load_page').hide();
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
                // alert("--"+gridid+"--"+$('#txt_sltsupcode_'+gridid).length);
                // var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();
                var gid = (+$('#partint3_'+gridid).val() + 1);
                $('#partint3_'+gridid).val(gid);
                // alert("@@"+gid);
                var apnd_content = '<div class="row part3_'+gridid+'" style="margin-right: -10px; display: flex;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line"><input type="hidden" name="txt_prdsgst_per['+gridid+'][]" id="txt_prdsgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdcgst_per['+gridid+'][]" id="txt_prdcgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdigst_per['+gridid+'][]" id="txt_prdigst_per_'+gridid+'_'+gid+'" value="">'+
                                                '<input type="radio" onclick="getrequestvalue('+gridid+', '+gid+')" name="txt_sltsupplier['+gridid+'][]" id="txt_sltsupplier_'+gridid+'_'+gid+'" value="'+gid+'" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;'+gid+'<br>';

                                    if(gridid == 1) {
                                        apnd_content += '<input type="checkbox" name="chk_apply_supplier['+gridid+'][]" id="chk_apply_supplier_'+gridid+'_'+gid+'" onclick="apply_supplier('+gid+')">';
                                    }

                                    apnd_content += '</div>'+
                                        '</div><div class="col-sm-3 colheight" style="padding: 1px 0px;">';

                                    if(gridid != 1) {
                                        apnd_content += '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode sub_prd_'+gid+'" onBlur="validate_supprdempty('+gridid+', '+gid+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" class="sub_prd_'+gid+'" value=""><span id="txt_taxpercentage_'+gridid+'_'+gid+'"></span>';
                                    } else {
                                        apnd_content += '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+gridid+', '+gid+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" value=""><span id="txt_taxpercentage_'+gridid+'_'+gid+'"></span>';
                                    }

                                    apnd_content += '</div>'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_delivery_duration['+gridid+'][]" id="txt_delivery_duration_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdrate['+gridid+'][]" id="txt_prdrate_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            'Adv.Amount Val.:'+
                                            '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+gridid+'][]" id="txt_advance_amount_'+gridid+'_'+gid+'" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdsgst['+gridid+'][]" id="txt_prdsgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();"  required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" readonly style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdcgst['+gridid+'][]" id="txt_prdcgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" readonly required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdigst['+gridid+'][]" id="txt_prdigst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" readonly required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+


                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_spldisc['+gridid+'][]" id="txt_spldisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_pieceless['+gridid+'][]" id="txt_pieceless_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="6" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prddisc['+gridid+'][]" id="txt_prddisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="hid_prdnetamount['+gridid+'][]" id="hid_prdnetamount_'+gridid+'_'+gid+'" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+gridid+'_'+gid+'">0</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="file" name="fle_supquot['+gridid+'][]" id="fle_supquot_'+gridid+'_'+gid+'" onchange="ValidateSingleInput(this); find_tags();" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                        '</div>'+

                                        '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                            '<textarea onKeyPress="enable_product();" name="txt_suprmrk['+gridid+'][]" id="txt_suprmrk_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                        '</div>'+
                                    '</div><script>$("#fle_supquot_'+gridid+'_'+gid+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                $('.parts3_'+gridid).append(apnd_content);
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
            if ($('.parts3_'+gridid+' .part3_'+gridid).length == 2) {
                // alert("!!"+gridid);
                alert("No more row to remove, Mantatory 3 Suppliers Quote Required..");
            }else{
                var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length - 1).toString();
                // alert(gridid+"@@"+gid);
                $('#partint3_'+gridid).val(gid);
                $('.parts3_'+gridid+' .part3_'+gridid+':last').remove();
            }
        // });
    }


    $('#frmdate').Monthpicker({
        minValue: $('#minvl').val(),
        maxValue: $('#maxvl').val(),
        onSelect: function () {
            $('#todate').Monthpicker('option', { minValue: $('#frmdate').val() });
            grid_date();
        }
    });

    $('#todate').Monthpicker({
        minValue: $('#minvl').val(),
        maxValue: $('#maxvl').val(),
        onSelect: function () {
            grid_date();
        }
    });

    function apply_supplier(iv) {
        var sltsupcode = $('#txt_sltsupcode_1_'+iv).val();
        var state = $('#state1_'+iv).val();
        var cnt = ($('#partint3').val() + 2);

        if($("#chk_apply_supplier_1_"+iv).is(':checked')) {
            if(sltsupcode != '') {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() == '') { // if empty means update this
                        $('#txt_sltsupcode_'+ii+'_'+iv).val(sltsupcode);
                        $('#state'+ii+'_'+iv).val(state);
                    }
                }
            } else {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                        $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                        $('#state'+ii+'_'+iv).val('');
                    }
                }
            }
        } else {
            for(var ii = 2; ii <= cnt; ii++) {
                if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                    $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                    $('#state'+ii+'_'+iv).val('');
                }
            }
        }
    }

    function get_targetdates_readonly() {
        $('#load_page').show();
        var core_deptid = document.getElementById('slt_core_department').value;
        var deptid = document.getElementById('slt_department_asset').value;
        // var slt_branch = document.getElementById('slt_brnch').value;
        var slt_branch = 1;
        var target_no = document.getElementById('slt_targetno').value;
        var slt_submission = document.getElementById('slt_submission').value;
        var slt_approval_listings = document.getElementById('slt_approval_listings').value;
        var currentyr = document.getElementById('currentyr').value;
        if(slt_branch==888){ slt_branch = 100; }

        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget
        if(slt_submission == 1) {
            // console.log("**1");
            $("#id_fixbudget_planner").css("display", "block");
        } else {
            // console.log("**0");
            $("#id_fixbudget_planner").css("display", "none");
        }
        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget

        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
            $("#getmonthwise_budget").css("display", "none");
            // $('#txtrequest_value').val(0);
            $('#txtrequest_value').attr('readonly', true);

            var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
            var alow_prd = 0;
            if(slt_submission == 6 || slt_submission == 7) {
                alow_prd = 1;
            } else if(slt_submission == 1 && slt_fixbudget_planner == 'PRODUCTWISE') {
                alow_prd = 1;
            }

            if(alow_prd == 1) {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {
                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    // $("#txtrequest_value").val(0);
                                    // $("#hidrequest_value").val(0);
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("!1!");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    $('#load_page').hide();
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }

            } else {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {
                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    // $("#txtrequest_value").val(0);
                                    // $("#hidrequest_value").val(0);
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("@2@");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        /* if(slt_submission == 1) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        } */
                                        if(slt_submission == 1 && alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                        $('#load_page').hide();
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
            }
            // $('#load_page').hide();
        } else {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', false);
            $('#load_page').hide();
        }
        find_tags();
    }

    function get_targetdates() {
        $('#load_page').show();
        var target_no = document.getElementById('slt_targetnos').value;
        ////////////// ** ////////////// 113!!AIR CONDITIONER!!10!!4!!20!!Y
        var target_no1 = target_no.split("||");
        var target_no2 = target_no1[1].split('!!');
        $('#hidd_depcode').val(target_no2[0]);
        $('#hidd_depname').val(target_no2[1]);
        $('#hidd_expsrno').val(target_no2[2]);
        $('#hidd_multireq').val(target_no2[3]);
        $('#slt_targetno').val(target_no1[0]);
        $('#slt_department_asset').val(target_no2[0]);
        $('#slt_core_department').val(target_no2[2]);
        var slt_submission = document.getElementById('slt_submission').value;

        var brnch_y_n = target_no2[5];
        var strURL1 = "ajax/ajax_branchview.php?action=edit&brnch_y_n="+brnch_y_n;
        $.ajax({
            type: "POST",
            url: strURL1,
            success: function(data) {
                $('#id_branchview').html(data);
            }
        });
        find_checklist();

        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget
        if(slt_submission == 1) {
            // console.log("**1");
            $("#id_fixbudget_planner").css("display", "block");
        } else {
            // console.log("**0");
            $("#id_fixbudget_planner").css("display", "none");
        }
        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget

        // alert('row-cal');
        var core_deptid = document.getElementById('slt_core_department').value;
        var deptid = document.getElementById('slt_department_asset').value;
        // var slt_branch = document.getElementById('slt_brnch').value;
        var slt_branch = 1;
        var slt_approval_listings = document.getElementById('slt_approval_listings').value;
        var currentyr = document.getElementById('currentyr').value;
        if(slt_branch==888){ slt_branch = 100; }

        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', true);
            var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
            var alow_prd = 0;
            if(slt_submission == 6 || slt_submission == 7) {
                alow_prd = 1;
            } else if(slt_submission == 1 && slt_fixbudget_planner == 'PRODUCTWISE') {
                alow_prd = 1;
            }

            if(alow_prd == 1) {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {

                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("#3#");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                    // grid_date();
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper id_supplier
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
                getsubtype_value();
                $('#load_page').hide();
            } else { // Only for Fixed Budget getmonthwise_budget
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {

                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("$4$");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(slt_submission == 1 && alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                    // grid_date();
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper id_supplier
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
            }
            getsubtype_value();
            $('#load_page').hide();
        } else {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', false);
            $('#load_page').hide();
        }
        // gettopcore();
        get_sub_core();
        find_tags();
    }

    function fix_top_subcore() {
    }

    function grid_date() {
        $('#load_page').show();
        var frdt    = $('#frmdate').val();
        var todt    = $('#todate').val();
        var ii      = '';
        var fstmnth = '';
        var lstmnth = '';
        var ivl     = 0;
        var wrapper = $(".monthyr_wrap"); //Fields wrapper
        $(wrapper).html('');
        if(todt != '') {
            var fdt = frdt.split("/");
            var tdt = todt.split("/");
            if(fdt[1] == tdt[1]) {
                for(var i = fdt[0]; i <= tdt[0]; i++) { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+fdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+fdt[1]+"'><span>"+ii+", "+fdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+fdt[1];
            } else {
                for(var i = fdt[0]; i <= 12; i++) { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+fdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+fdt[1]+"'><span>"+ii+", "+fdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+fdt[1];

                for(var i = 1; i <= tdt[0]; i++)
                { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+tdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+tdt[1]+"'><span>"+ii+", "+tdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+tdt[1];
            }

            // alert("FIR - "+fstmnth); alert("LST - "+lstmnth);
            $('#fstmnth').val(fstmnth);
            $('#lstmnth').val(lstmnth);
            var dirq = 0;

            $(wrapper).append(
                "<tr><td colspan='2' style='width:40%; text-align:right; padding-right:10%; font-weight:bold;'>TOTAL : </td><td style='width:60%; font-weight:bold;'><span id='ttl_mntyr'>"+dirq+"</span></td></tr>"
            );

            $("#frmdate").css("display", "none");
            $("#todate").css("display", "none");
            $(".monthpicker_input").css("display", "none");
            $(".monthpicker_selector").css("display", "none");
        }
        $('#load_page').hide();
    }

    function allow_zero(ivalue, currentvalue, lockvalue) {
        $('#load_page').show();
        var fstmnth = $('#fstmnth').val();
        var lstmnth = $('#lstmnth').val();
        var mnt_yr  = $('#mnt_yr_'+ivalue).val();
        var mnt_yr_amt  = $('#mnt_yr_amt_'+ivalue).val();
        var txtrequest_value = $('#txtrequest_value').val();
        var ttl_lock = $('#ttl_lock').val();
        // console.log(ttl_lock+"********"+txtrequest_value);
        if(parseInt(ttl_lock) >= parseInt(txtrequest_value)) {
            if(((fstmnth == mnt_yr) || (lstmnth == mnt_yr)) && currentvalue == 0) {
                alert("Zero is not allowed here!!");
                $('#mnt_yr_amt_'+ivalue).val('0');
            }
        } else {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            $('#mnt_yr_amt_'+ivalue).val('');
            $('#mnt_yr_amt_'+ivalue).focus();
        }
        $('#load_page').hide();
    }

    function showmode() {
        var budgettype = $('#slt_submission').val();
        if(budgettype == 1 || budgettype == 6 || budgettype == 7) {
        // if(budgettype == 1 || budgettype == 6) {
            $("#id_budgetmode").css("display", "block");
        } else {
            $("#id_budgetmode").css("display", "none");
        }
    }

    function findmonth(i) {
        var ii = '';
        if(i == 1){
            ii = 'JAN';
        } else if(i == 2){
            ii = 'FEB';
        } else if(i == 3){
            ii = 'MAR';
        } else if(i == 4){
            ii = 'APR';
        } else if(i == 5){
            ii = 'MAY';
        } else if(i == 6){
            ii = 'JUN';
        } else if(i == 7){
            ii = 'JUL';
        } else if(i == 8){
            ii = 'AUG';
        } else if(i == 9){
            ii = 'SEP';
        } else if(i == 10){
            ii = 'OCT';
        } else if(i == 11){
            ii = 'NOV';
        } else if(i == 12){
            ii = 'DEC';
        }
        return ii;
    }

    function calculate_sum() {
        $('#load_page').show();
        var ttl_lock = $("#ttl_lock").val();
        var total = 0;
        var $changeInputs = $('input.ttlsum');
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });

        var lockchk = 1;
        var slt_submission = $('#slt_submission').val();
        if((parseInt(ttl_lock) <= 0 && parseInt(total) <= parseInt(ttl_lock)) && (slt_submission == 6 || slt_submission == 1)) {
            lockchk = 0;
        }

        // console.log("||"+parseInt(ttl_lock)+"||"+parseInt(total)+"||"+lockchk+"||"+slt_submission+"||");
        if(parseInt(ttl_lock) > 0 && lockchk == 1) {
            document.getElementById('hidrequest_value').value = total;
            document.getElementById('txtrequest_value').value = total;
            document.getElementById('txt_brnvalue_0').value = total;
            document.getElementById('ttl_mntyr').innerHTML = total;
        }else{
            total = 0;
            document.getElementById('hidrequest_value').value = total;
            document.getElementById('txtrequest_value').value = total;
            document.getElementById('txt_brnvalue_0').value = total;
            document.getElementById('ttl_mntyr').innerHTML = total;
        }
        // getapproval_listings();
        $('#load_page').hide();
    }

    function calculate_sum_total(val , class_name) {

        var $changeInputs = $('input.'+class_name);
        var $checkInputs = $('input#txtesi_no_emp_'+val).val();
        var $checkInputs_value = $('input#txtesi_value_'+val).val();
        if ($checkInputs != '' && $checkInputs_value != '') {
          var total = 0;
          $changeInputs.each(function(idx, el) {
                total += Number($(el).val());
          });
          document.getElementById('hidrequest_value').value = total;
          document.getElementById('txtrequest_value').value = total;
          document.getElementById('txt_brnvalue_0').value = total;
          document.getElementById('ttl_mntyr').innerHTML = total;
          //$('#load_page').hide();
        }else {
          $('input#txtesi_value_'+val).val('');
        }
    }

    function addempgift(id)
    {
        $('#load_page').show();
        var empcode = $("#txt_staffcode_"+id).val();
        var brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_staffchange.php?action=marriagegift&empcode="+empcode+"&id="+id+"&brncode="+brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#photo_"+id).html(data);
                var hid_data = $("#hid_emp_"+id).val();
                var val = hid_data.split("~");
                $('#curexp_'+id).val(val[0]);
                $('#curdoj_'+id).val(val[1]);
                $('#curbrn_'+id).val(val[2]);
                $('#curdep_'+id).val(val[3]);
                $('#curdes_'+id).val(val[4]);
                $('#owngift_'+id).val(val[5]);
                $('#trustamt_'+id).val(val[6]);
                $('#empsrno_'+id).val(val[7]);
                calculate_sum();
                $('#load_page').hide();
            }
        });
    }

    function coladd_new()
    {
        $('#load_page').show();
        var cnt = $("#noofcol").val();
        $("#default_lock").val(cnt);
        var slt_approval_listings = $('#slt_approval_listings').val();
        if (cnt != ""){
        var strURL="ajax/ajax_general_temp.php?action=COLADD&cnt="+cnt+"&slt_approval_listings="+slt_approval_listings;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $("#genearl_temp").html(data);
                    $("#createtemp").css("display", "block");
                    $.getScript("js/jquery-customselect.js");
                        //$(".chosn").customselect();
                        $("#allcal").val(0);
                        // $('.select2').select2();
                    $("#sbmt_request").prop("disabled", true);
                }
            });
        }else{
            alert("Enter No Of Columns Required ...!");
            $("#noofcol").focus();
        }
        $('#load_page').hide();
    }

    function create_temp()
    {
        $('#load_page').show();
        var col = [];
        var  empty = 0;
        var cnt = $("#noofcol").val();
        for (i=0;i<=cnt;i++)
        {
            col[i]  = $('#a_'+i).val();
            if(col[i] == "")
            {
                empty = 1;
                empty = parseInt(empty);
            }
        }

        var check = [];
        for (i=0;i<=cnt;i++)
        {
           if ($('#b_'+i).is(':checked'))
            {
            check[i]  = $('#b_'+i).val();
            }else{
                check[i]  = "N";
            }

        }

        if(empty == 1)
        {
            alert(" Head Details Cannot Be Empty ...!");
        }else{
            var cnt = $("#default_lock").val();
        $("#hid_default_lock").val(cnt);
        var apmcode = $("#slt_approval_listings").val();
        var alw_cal = $("#allcal").val();
        var res_head = $("#slt_res_head").val();
        var cal_head = $("#slt_head").val();
        //var cal_opt = $("#slt_cal").val();
        var cal_opt = $('input[name="slt_cal"]:checked').val();
        var strURL="ajax/ajax_general_temp.php?action=COLINSERT&cnt="+cnt+"&apmcode="+apmcode+"&alw_cal="+alw_cal+"&res_head="+res_head+"&cal_head="+cal_head+"&cal_opt="+cal_opt;
            $.ajax({
                type: "POST",
                url: strURL,
                data: {col:col,check:check},
                success: function(data) {
                    $("#general").css("display", "none");
                    data = data.replace(/\s/g,'');
                    $("#tempidcnt").val(data);
                    load_gen_temp(data);
                    $("#sbmt_request").prop("disabled", false);
                }
            });
        }
        $('#load_page').hide();
    }

    function get_tablemaster() {
        var depcode = $('#slt_department_asset').val();
        $.ajax({
            url:"ajax/ajax_product_details.php?action=table_master&depcode="+depcode,
            success:function(data)
            {
                $("#myModal1").modal('show');
                $('#modal-body1').html(data);
            }
        });
    }

    function load_gen_temp(cnt)
    {
        $('#load_page').show();
        var apmcode = $("#slt_approval_listings").val();
        var strURL="ajax/ajax_general_temp.php?action=LOADVIEW&cnt="+cnt+"&apmcode="+apmcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#id_supplier").html(data);
                $("#partint3").val(1);
                $('#load_page').hide();
            }
        });
    }

    function blinker() {
        $('.blinking').fadeOut(500);
        $('.blinking').fadeIn(500);
    }
    setInterval(blinker, 1000);

    function back_col()
    {
        call_dynamic_option();
        $("#tempidcnt").val(0);
        $("#allcal").val(0);
    }

    function gen_calculation(field,calmode,resfld,index,colsrno)
    {
        $('#load_page').show();
        var val = field.split("~");
        var tot = 0;
        for(i=0;i<val.length;i++){
            var n_val = $("#a_"+val[i]+'_'+index).val();
            console.log("#a_"+val[i]+'_'+index);
            tot = parseFloat(tot);
            n_val = parseFloat(n_val);
            if(tot == 0)
            {
                tot = n_val;
            }else{
                switch(calmode){
                    case "M":
                        tot = +tot * +n_val;
                    break;
                    case "D":
                        tot = (+n_val / +tot) * 100 ;
                    break;
                    case "A":
                        tot = +tot + +n_val;
                    break;
                    case "S":
                        tot = +tot - +n_val;
                    break;
                }
            if (isNaN(tot))
            {
            }else{
                tot = tot.toFixed(2);
                $("#a_"+resfld+'_'+index).val(tot);
            }
            }
        }
        var total = 0;
        var $changeInputs = $('input.ttlsum_gen_'+colsrno);
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });
        $("#tot_"+colsrno).html(total);

        var total = 0;
        var $changeInputs = $('input.ttlsum_gen_'+resfld);
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });

        $("#tot_"+resfld).html(total);
        $('#load_page').hide();
    }

    function default_view()
    {
        if ($('#alw_create').is(':checked')) {
            $("#general").css("display", "block");
        }else{
            $("#general").css("display", "none");
        }
    }

    function Add_cal()
    {
        var mulhead = "";
        mulhead = $("#slt_head").val();
        if(mulhead == null)
        {
            alert("Select Calculation Fields ...!");
            $('#slt_head').focus();
        }else{
            var res = $('#slt_res_head').val();
            $('#b_'+res).prop('checked',true);
            $("#allcal").val(1);
            $("#addcal").prop("disabled", true);
        }
    }

    function add_gen_row()
    {
        var row = $("#partint3").val();
        var nrow = parseInt(row)+1;
        var apmcode = $("#slt_approval_listings").val();
        var cnt = $("#tempidcnt").val();
        var strURL="ajax/ajax_general_temp.php?action=ADDROW&cnt="+cnt+"&apmcode="+apmcode+"&row="+row;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#partint3").val(nrow);
                $('.parts3').append(data);
            }
        });
    }

    function remove_gen_row() {
       if ($('.part3').length == 1) {
          alert("No more row to remove.");
       }else{
           var id = ($('.part3').length - 1).toString();
           $(".part3:last").remove();
           $('#partint3').val(id);
       }
    }

    function call_dynamic_option() {
        $('#load_page').show();
        var core_deptid = $("#slt_core_department").val();
        var deptid = $("#slt_department_asset").val();
        var slt_branch = $("#slt_brnch_0").val();
        var target_no = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var slt_fixbudget_planner = $("#slt_fixbudget_planner").val();
        var slt_approval_listings = $("#slt_approval_listings").val();
        var currentyr = $("#currentyr").val();
        var view = $("#view_stat").val();
        if(slt_branch == 888) { slt_branch = 100; }

        var alow_prd = 1;
        if(slt_submission == 1 && slt_fixbudget_planner == 'MONTHWISE') {
            alow_prd = 0;
        }
        // console.log('********'+alow_prd+'********');

        $("#id_supplier").html('');
        $("#getmonthwise_budget").css("display", "none");
        if(slt_approval_listings != '' && alow_prd == 1) {
            var strURL="ajax/ajax_entry1.php?action=add_edit&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view="+view;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    /* $.getScript("js/zebra_datepicker.js");
                    $.getScript("js/plugins/jquery/jquery-ui.min.js");
                    $.getScript("js/jquery-ui-1.10.3.custom.min.js");

                    $('#datepicker_example5').Zebra_DatePicker({
                      direction: true,
                      format: 'd-M-Y'
                    });

                    $('#datepicker_example6').Zebra_DatePicker({
                      direction: true,
                      format: 'd-M-Y'
                    }); */
                    $.getScript("ajax/ajax_staff_change.js");


                    if(data1 == 0) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Dynamic Approval Listing loading failed. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $('#load_page').hide();
                    } else {

                        // alert(data1);
                        // $.getScript("chart/js/plugin/sample_order_script.js");
                        $("#id_supplier").html(data1);
                        change_readonly();
                        $('#hid_default_lock').val(0);
                        if ( $( "#default_lock" ).length ) {
                            $("#sbmt_request").prop("disabled", true);
                        }

                        $("#id_policy_approval").css("display", "none");
                        $(".policy_approval_required").attr('required', false);
                        var pathid = $('#hid_app_path').val();
                        if(pathid == 1)
                        {
                            var nofsup = $("#hid_nof_suppliers_1").val();
                            if(nofsup > 1) {
                                for(var nofsupi = 1; nofsupi <= nofsup; nofsupi++){
                                    call_innergrid(1);
                                }
                            }
                        }
                        else if(pathid == 13)
                        {
                            $("#id_policy_approval").css("display", "block");
                            $(".policy_approval_required").attr('required', true);
                        }

                        var id = 1;
                        $('#fle_supquot_'+id+'_1').filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
                        $('#fle_prdimage_'+id).filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
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

                        $('#txt_reportto_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_employee_details.php',
                                    dataType: "json",
                                    data: {
                                       slt_emp: request.term,
                                       brncode: $('#slt_branch').val(),
                                       action: 'allbrnemp'
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
                                if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
                                    $('#budgt_vlu').html(' - Budget Value - '+ttl_lock);
                                }
                            }
                        }
                        $('#load_page').hide();
                    }
                }
            });
        }
        find_tags();
    }

    function getbrn(id) {
        $('#load_page').show();
        var sendurl = "ajax/ajax_staffchange.php?action=GETBRN&id="+id;
        $.ajax({
        url:sendurl,
        success:function(data){
                document.getElementById('staff_branch_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }


    function getdept(id) {
        $('#load_page').show();
        var brncode = $("#newbrn_"+id).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var sendurl = "ajax/ajax_staffchange.php?brncode="+brncode+"&action=GETDEPT&id="+id+"&slt_brncode="+slt_brncode;
        $.ajax({
        url:sendurl,
        success:function(data){
                getdes(id);
                document.getElementById('staff_dept_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }

    function getdes(id) {
        $('#load_page').show();
        var brncode = $("#newbrn_"+id).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var sendurl = "ajax/ajax_staffchange.php?brncode="+brncode+"&action=GETDES&id="+id+"&slt_brncode="+slt_brncode;
        $.ajax({
        url:sendurl,
        success:function(data){
                document.getElementById('staff_des_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }


    function addstaff(id,mode)
    {
        $('#load_page').show();
        var empcode = $("#txt_staffcode_"+id).val();
        var brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_staffchange.php?action=transfer&empcode="+empcode+"&id="+id+"&brncode="+brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {

                $("#photo_"+id).html(data);

                var hid_data = $("#hid_emp_"+id).val();
                var val = hid_data.split("~");
                el  = parseFloat(val[0]);

                $('#curexp_'+id).val(el);
                $('#curdoj_'+id).val(val[1]);
                // $('#empphoto_'+id).val(val[10]);
                $('#empsrno_'+id).val(val[11]);

                $('#curbrn_'+id).val(val[2]);
                $('#curdep_'+id).val(val[3]);
                $('#curdes_'+id).val(val[4]);

                if(mode == 2)
                {
                    $('#curbas_'+id).val(val[5]);
                }
                if(mode == 3)
                {
                    $('#cbas_'+id).html(val[5]);
                    $('#ccomm_'+id).html(val[6]);
                }
                $('#load_page').hide();
            }
        });
    }

    function newbasic(id)
    {
        var basic = parseInt($("#curbas_"+id).val());
        var nbasic  = parseInt($("#newbas_"+id).val());
        tot = nbasic-basic;
        $('#incamt_'+id).val(tot);
    }

    function checkform_check() {
    $("#sbmt_request").click(function(e){
        $('#load_page').show();
        // alert("***************************");
        var enable1 = 0; var enable2 = 0; var enable3 = 0; var enable4 = 0; var gtvl = 1;
        var enable5 = 0; var enable6 = 0; var enable7 = 0; var enable8 = 0; var enable9 = 0;
        var slt_targetno   = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var strURL = "ajax/ajax_validate.php?action=fix_checklist&slt_targetno="+slt_targetno+"&slt_submission="+slt_submission;
        $.ajax({
            type: "POST",
            url: strURL,
            // async: false,
            success: function(data1) {
                var chklist = data1.split(",");
                for (var exp_chklisti=0; exp_chklisti < chklist.length; exp_chklisti++) {
                    if(chklist[exp_chklisti] == 1) {
                        enable1 = 1;
                    }

                    if(chklist[exp_chklisti] == 2) {
                        enable2 = 1;
                    }

                    if(chklist[exp_chklisti] == 3) {
                        enable3 = 1;
                    }

                    if(chklist[exp_chklisti] == 4) {
                        enable4 = 1;
                    }

                    if(chklist[exp_chklisti] == 5) {
                        enable5 = 1;
                    }

                    if(chklist[exp_chklisti] == 6) {
                        enable6 = 1;
                    }

                    if(chklist[exp_chklisti] == 7) {
                        enable7 = 1;
                    }

                    if(chklist[exp_chklisti] == 8) {
                        enable8 = 1;
                    }

                    if(chklist[exp_chklisti] == 9) {
                        enable9 = 1;
                    }
                }

        var tot_d= $("#hid_default_lock").val();
        if ( tot_d != 0 ) {
            var gtd = 0;
            j=1;
            for(i=0;i<tot_d;i++)
            {j++;
                var gtd_val = $("#a_"+j+"_1").val();
                if(gtd_val != "")
                {
                    gtd++;
                }
            }
            if(gtd == 0)
            {
                var ALERT_TITLE = "Message";
                var ALERTMSG = "General Format Cannot Be Empty";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                $('#load_page').hide();
                gtvl = 0 ;
            }
        }
        if (frm_request_entry.slt_submission.value == "") {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose the type of submission";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            // frm_request_entry.slt_submission.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.slt_approval_listings.value == "") {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose the approval listings";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            // frm_request_entry.slt_approval_listings.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txt_workintiator.value == "" || frm_request_entry.txt_workintiator.value == " -  - ") {
            frm_request_entry.txt_workintiator.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the Work Initiate Person user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_workintiator.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txt_workintiator.value == "" || frm_request_entry.txt_workintiator.value == " -  - ") {
            frm_request_entry.txt_workintiator.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the Work Initiate Person user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_workintiator.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        } // txt_submission_reqby



    // Attachments
    <?  /* $sql_chklist = select_query_json("select * from approval_checklist where deleted = 'N' and tarnumb = 7515 order by apcklst");
        // echo "select * from approval_checklist where deleted = 'N' and tarnumb = ".$('#slt_targetno').val()." order by apcklst";
        foreach ($sql_chklist as $key => $chklist_value) {
            $exp_chklist = explode(",", $chklist_value['CKLSTCD']);
        }

        $enable1 = 0; $enable2 = 0; $enable3 = 0; $enable4 = 0;
        $enable5 = 0; $enable6 = 0; $enable7 = 0; $enable8 = 0; $enable9 = 0;
        for ($exp_chklisti=0; $exp_chklisti < count($exp_chklist); $exp_chklisti++) {
            switch ($exp_chklist[$exp_chklisti]) {
                case 1:
                    $enable1 = 1;
                    break;
                case 2:
                    $enable2 = 1;
                    break;
                case 3:
                    $enable3 = 1;
                    break;
                case 4:
                    $enable4 = 1;
                    break;
                case 5:
                    $enable5 = 1;
                    break;

                case 6:
                    $enable6 = 1;
                    break;
                case 7:
                    $enable7 = 1;
                    break;
                case 8:
                    $enable8 = 1;
                    break;
                case 9:
                    $enable9 = 1;
                    break;

                default:
                    # code...
                    break;
            }
        } */ ?>

        alert("=="+enable1+"=="+enable2+"=="+enable3+"=="+enable4+"=="+enable5+"=="+enable6+"=="+enable7+"=="+enable8+"=="+enable9+"==");
        if(enable1 == 1) {
            if (frm_request_entry.txt_submission_quotations.value == "" && frm_request_entry.txt_submission_quotations_remarks.value == "") {
                frm_request_entry.txt_submission_quotations_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Quotations & Estimations..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_quotations_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable2 == 1) {
        // alert("=="+frm_request_entry.txt_submission_fieldimpl.value+"=="+frm_request_entry.txt_submission_fieldimpl_remarks.value+"==");
            if (frm_request_entry.txt_submission_fieldimpl.value == "" && frm_request_entry.txt_submission_fieldimpl_remarks.value == "") {
                // alert("CAME1");
                frm_request_entry.txt_submission_fieldimpl_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Budget / Common Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_fieldimpl_remarks.focus();
                $('#load_page').hide();
                // alert("CAME2");
                gtvl = 0;
            }
            // alert("CAME3");
        }

        if(enable3 == 1) {
            if (frm_request_entry.txt_submission_clrphoto.value == "" && frm_request_entry.txt_submission_clrphoto_remarks.value == "") {
                frm_request_entry.txt_submission_clrphoto_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Work Place Before / After Photo / Drawing Layout..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_clrphoto_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable4 == 1) {
            if (frm_request_entry.txt_submission_artwork.value == "" && frm_request_entry.txt_submission_artwork_remarks.value == "") {
                frm_request_entry.txt_submission_artwork_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Art Work Design with MD Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_artwork_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable5 == 1) {
            if (frm_request_entry.txt_submission_othersupdocs.value == "" && frm_request_entry.txt_submission_othersupdocs_remarks.value == "") {
                frm_request_entry.txt_submission_othersupdocs_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Consultant Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_othersupdocs_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable6 == 1) {
            if (frm_request_entry.txt_warranty_guarantee.value == "") {
                frm_request_entry.txt_warranty_guarantee.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Warranty / Guarantee details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_warranty_guarantee.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable7 == 1) {
            if (frm_request_entry.txt_cur_clos_stock.value == "") {
                frm_request_entry.txt_cur_clos_stock.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Current / Closing Stock details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_cur_clos_stock.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable8 == 1) {
            if (frm_request_entry.txt_advpay_comperc.value == "") {
                frm_request_entry.txt_advpay_comperc.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Advance or Final Payment / Work Completion Percentage details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_advpay_comperc.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable9 == 1) {
            if (frm_request_entry.datepicker_example4.value == "") {
                frm_request_entry.datepicker_example4.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Work Finish Target Date details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.datepicker_example4.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }
        // Attachments

        // exit;

        if (frm_request_entry.txt_submission_reqby.value == "" || frm_request_entry.txt_submission_reqby.value == " -  - ") {
            frm_request_entry.txt_submission_reqby.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the responsible user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_submission_reqby.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txt_submission_reqby.value == frm_request_entry.txt_alternate_user.value) {
            frm_request_entry.txt_alternate_user.value = '';
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Responsible User & Alternate User must be the different users.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_alternate_user.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        description1=CKEDITOR.instances['FCKeditor1'].getData().replace(/<[^>]*>/gi, '');
        if(description1=='')
        {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the details";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            CKEDITOR.instances['FCKeditor1'].focus();
            $('#load_page').hide();
            gtvl = 0;
        }
        if (frm_request_entry.txtfrom_date.value == "") {
            // alert( "Please enter the from date" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the from date";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtfrom_date.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtto_date.value == "") {
            // alert( "Please enter the to date" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the to date";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtto_date.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txtnoofhours.value == "") {
            // alert( "Please enter the no of hours" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the no of hours";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtnoofhours.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtnoofdays.value == "") {
            // alert( "Please enter the no of days" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the no of days";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtnoofdays.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if($('#getmonthwise_budget').css('display') == 'block') {


             var myElem = document.getElementById('frmdate');
            if (myElem === null) {
                // alert( "Budget Planner is not available here. Kindly Verify the Target No here.." );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Budget Planner is not available here. Kindly Verify the Target No here..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                document.getElementById('slt_targetno').focus();
                $('#slt_targetno').focus();
                frm_request_entry.slt_targetno.focus();
                $('#load_page').hide();
                gtvl = 0;
            } else {
                /*if (frm_request_entry.frmdate.value == "") {
                    // alert( "Please enter the from month" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter the from month";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.frmdate.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
                }
                if (frm_request_entry.todate.value == "") {
                    // alert( "Please enter the to month" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter the to month";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.todate.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
                }
                if (frm_request_entry.txtrequest_value.value == "0" || frm_request_entry.txtrequest_value.value == "") {
                    // alert( "Please enter some value here" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter some value here";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.txtrequest_value.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
            } */
            }
        }

        if($("#slt_submission").val() == 1 || $("#slt_submission").val() == 6 || $("#slt_submission").val() == 7) {
            $(".supquot").prop('required', true); // Add required field option for all productwise budget page
            /* if (frm_request_entry.txt_suppliercode.value == "") {
                // alert( "Please enter the details" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter the Supplier Details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_suppliercode.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            }
            if (frm_request_entry.txt_supplier_contactno.value == "") {
                // alert( "Please enter the details" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter the Supplier Contact No";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_supplier_contactno.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            } */

            if (frm_request_entry.txtrequest_value.value == "0" || frm_request_entry.txtrequest_value.value == "") {
                // alert( "Please enter some value here" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter some value here";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txtrequest_value.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            }

            /* var fileselect = (".fileselect");
            $(fileselect).each(function () {
                validate_upload(this);
            }); */
        } else {
            // remove required field option for all productwise budget page
            $(".supquot").prop('required', false);
            // remove required field option for all productwise budget page
        }

        if (frm_request_entry.datepicker_example3.value == "") {
            // alert( "Please choose any implementation due date here" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose any implementation due date here";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.datepicker_example3.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtrequest_by.value == "") {
            // alert( "Please enter the submission prepared by user" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the submission prepared by user";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtrequest_by.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtrequest_byid.value == "") {
            // alert( "Please enter the submission prepared by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the submission prepared by user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtrequest_byid.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        // alert("WR"+gtvl+"ONG");
        $('#load_page').hide();

        if(gtvl == 1) {
            $('#hid_gtvl').val(gtvl);
            $('#frm_request_entry').submit();
        }
        }
    });
});
}

    function validate_upload(field) {
        var fieldVal = $(field).val();
        if(!fieldVal) {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "No Supplier Quotation PDF Attached. Kindly add 1 Quotation PDF!!";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
        }
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

    function isNumberWithoutDot(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode > 31 && charCode != 39 && charCode != 34 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function numwodot(evt)
    {
       if ((evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
        }
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

    $('#datepicker_example3').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
      format: 'd-M-Y'
    });

    $('#datepicker_example4').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example5').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example6').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example7').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datetimepicker8').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $('#datetimepicker9').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $('#datetimepicker10').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $("#datetimepicker9").on("dp.change",function (e) {
       $('#datetimepicker10').data("DateTimePicker").setMinDate(e.date);
    });
    $("#datetimepicker10").on("dp.change",function (e) {
       $('#datetimepicker9').data("DateTimePicker").setMaxDate(e.date);
    });

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        //format: 'dd.M.yyyy HH:ii P',
        format: 'dd.mm.yyyy HH:ii:ss P',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    $('.formto_datetime').datetimepicker({
        //language:  'fr',
        format: 'dd.mm.yyyy HH:ii:ss P',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

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

    function find_balance(reqvalue)
    {
        $('#load_page').show();
        var bal = document.getElementById("hid_balance").value;
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var deptid = document.getElementById('slt_department_asset').value;
        var branch = document.getElementById('slt_brnch_0').value;

        if($('#getmonthwise_budget').css('display') == 'none') {
            $('#hidrequest_value').val($('#txtrequest_value').val());
        }

        if(deptid == 100 && approval_listings_id == 4 && reqvalue > bal)
        {
            document.getElementById("txtrequest_value").value = '';
            document.getElementById('txt_brnvalue_0').value = '';
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Request value is greater than the Target Balance";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            document.getElementById("sbmt_request").disabled = true;
            //document.getElementById("sbmt_update").disabled = true;
        } else {
            document.getElementById("sbmt_request").disabled = false;
            //document.getElementById("sbmt_update").disabled = false;
        }

        /* var accnoid = document.getElementById('hid_accnoid').value;
        if(accnoid == 1)
        {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "This user dont have Salary account. So this user is not eligible for salary advance process.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            document.getElementById("sbmt_request").disabled = true;
            //document.getElementById("sbmt_update").disabled = true;
        } else {
            document.getElementById("sbmt_request").disabled = false;
            //document.getElementById("sbmt_update").disabled = false;
        } */
        $('#load_page').hide();
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

    function branch_visit(){
        var slt_approval_listings=document.getElementById('slt_approval_listings').value;
        var userid=document.getElementById('txt_submission_reqby').value;
        if(slt_approval_listings == 2) {
            // getapproval_salaryadvance(userid);
        }
    }

    function change_readonly() {
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(slt_subtopcore == '41') {
            $(".ad_category").attr("readonly", false);
        } else {
            $(".ad_category").attr("readonly", true);
            $(".ad_category").val("");
        }
    }

    function getapproval_listings_only() {
        $('#load_page').show();
        var approval_listings_id = $('#slt_approval_listings').val();
        var slt_brnch = $('.slt_brnchcls');
        if(slt_brnch.length > 1) {
            slt_brnch = 100;
        } else{
            slt_brnch = $('#slt_brnch_0').val();
        }
        var slt_subtype = '';
        var kind_attn = '';
        var type_app = document.getElementById('slt_submission').value;
        var slt_core_department = document.getElementById('slt_core_department').value;
        var budgettype = '';
        var budgetidtype = $('#slt_submission').val();
        var slt_project = $('#slt_project').val();
        var slt_project_type = $('#slt_project_type').val();
        var slt_topcore = document.getElementById('slt_topcore').value;
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(budgetidtype == 1) {
            budgettype = 'FIXED';
        } else if(budgetidtype == 7) {
            budgettype = 'EXTRA';
        }
        var deptid = $("#slt_department_asset").val();
        var tarnum = $("#slt_targetno").val();
        var txtrequest_value = $("#txtrequest_value").val();

        var strURL="getapproval_listings.php?approval_listings_id="+approval_listings_id+"&kind_attn="+kind_attn+"&type="+type_app+"&budgettype="+budgettype+"&budgetidtype="+budgetidtype+"&topcore="+slt_topcore+"&sub_topcore="+slt_subtopcore+"&slt_core_department="+slt_core_department+"&slt_project_type="+slt_project_type+"&slt_project="+slt_project+"&deptid="+deptid+"&tarnum="+tarnum+"&slt_brnch="+slt_brnch+"&txtrequest_value="+txtrequest_value;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_approval_listings').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    // get_targetdates();
                    $('#load_page').hide();
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }
    }

    function getapproval_listings() {
        $('#load_page').show();
        call_dynamic_option();
        // change_readonly();
        var approval_listings_id = $('#slt_approval_listings').val();
        var slt_brnch = $('.slt_brnchcls');
        // alert("**"+slt_brnch.length);
        if(slt_brnch.length > 1) {
            slt_brnch = 100;
        } else{
            slt_brnch = $('#slt_brnch_0').val();
        }
        // var slt_subtype=document.getElementById('slt_subtype').value;
        var slt_subtype = '';
        // var kind_attn = document.getElementById('txt_kind_attn').value;
        var kind_attn = '';
        var type_app = document.getElementById('slt_submission').value;
        var slt_core_department = document.getElementById('slt_core_department').value;
        // var budgettype = document.getElementById('slt_budgettype').value;
        var budgettype = '';
        var budgetidtype = $('#slt_submission').val();
        var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
        var slt_project = $('#slt_project').val();
        var slt_project_type = $('#slt_project_type').val();
        var slt_topcore = document.getElementById('slt_topcore').value;
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(budgetidtype == 1) {
            budgettype = 'FIXED';
        } else if(budgetidtype == 7) {
            budgettype = 'EXTRA';
        }
        var deptid = $("#slt_department_asset").val();
        var tarnum = $("#slt_targetno").val();
        var txtrequest_value = $("#txtrequest_value").val();

        get_targetdates_readonly();
        /* if(approval_listings_id == 807 || approval_listings_id == 777) {
            enable_month();
        } else {
            enable_all(); brncode
        } */

        var alow_prd = 1;
        if(slt_submission == 1 && slt_fixbudget_planner == 'MONTHWISE') {
            alow_prd = 0;
        }
        // console.log('###'+alow_prd+'###');

        $("#id_supplier").html('');
        $("#getmonthwise_budget").css("display", "none");
        // alert("!!");
        if(alow_prd == 0) {
            // alert("@@");
            $("#getmonthwise_budget").css("display", "block");
        }
        if(slt_approval_listings != '' && alow_prd == 1) {
            // alert("##"); sub_topcore
            var strURL="getapproval_listings.php?approval_listings_id="+approval_listings_id+"&kind_attn="+kind_attn+"&type="+type_app+"&budgettype="+budgettype+"&budgetidtype="+budgetidtype+"&topcore="+slt_topcore+"&sub_topcore="+slt_subtopcore+"&slt_core_department="+slt_core_department+"&slt_project_type="+slt_project_type+"&slt_project="+slt_project+"&deptid="+deptid+"&tarnum="+tarnum+"&slt_brnch="+slt_brnch+"&txtrequest_value="+txtrequest_value;
            var req = getXMLHTTP();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            document.getElementById('id_approval_listings').innerHTML=req.responseText;
                            // if(approval_listings_id == 4) { getapproval_salaryadvance(approval_listings_id); } // Salary Advance
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                        // get_targetdates();
                        $('#load_page').hide();
                    }
                }
                req.open("POST", strURL, true);
                req.send(null);
            }
        }
    }

    function getapproval_salaryadvance(userid) {
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        // var project = document.getElementById('slt_project').value;
        var project = '';
        var brncode = document.getElementById('slt_brnch_0').value;
        var strURL="get_salaryadvance.php?userid="+userid+"&approval_listings_id="+approval_listings_id+"&brncode="+brncode+"&project="+project;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_salaryadvance').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    get_eligibleadvance(userid);
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function get_eligibleadvance(userid) {
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var strURL="get_eligibleadvance.php?userid="+userid+"&approval_listings_id="+approval_listings_id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.responseText == 1) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Due to your previous request, You are not eligible to raise another request";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $("#button_set").hide();
                    } else {
                        $("#button_set").show();
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function get_dept(core_deptid) {
        $('#load_page').show();
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
        find_tags();
        $('#load_page').hide();
    }

    function get_advancedetails() {
        $('#load_page').show();
        // Find the RPTMODE from department_asset table
        var deptid = $("#slt_department_asset").val();
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var slt_branch = document.getElementById('slt_brnch_0').value;
        if(slt_branch == 888) { slt_branch = 100;  }

        var slt_core_department = $("#slt_core_department").val();
        var depcode = $("#slt_department_asset").val();
        var strURL="ajax/ajax_validate.php?action=find_rptmode&validate_code="+slt_core_department+"&depcode="+depcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                $("#txt_rptmode").val(data1);
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_rptmode").val(data1);
                    $('#load_page').hide();
                }
            }
        });
        // Find the RPTMODE from department_asset table

        /*
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
        } */

        /* // 12-05-2018 GA Commented this
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
        } */

        // Find the Approval Type
        var strURL="get_advancedetails.php?action=find_apptype&slt_core_department="+slt_core_department+"&depcode="+depcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                $("#id_apptype").html(data1);
            }
        });
        // Find the Approval Type
        find_tags();
        $('#load_page').hide();
    }

    function call_days() {
        /* get_targetdates();
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
        date_diff(); */
    }

    function gettopcore(project_id) {
        $('#load_page').show();
        /* var slt_core_department = $("#slt_core_department").val();
        var slt_submission = $("#slt_submission").val();
        var strURL="gettopcore.php?project_id="+project_id+"&slt_core_department="+slt_core_department+"&slt_submission="+slt_submission;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_topcore').innerHTML=req.responseText;
                        getsubcore();
                        // get_targetdates();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        } */

        var slt_project = $('#slt_project').val();
        var strURL = "ajax/ajax_project.php?action=find_subtype&slt_project="+slt_project;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#id_submission_type").html(data);
                $('#load_page').hide();
            }
        });
        find_tags();
    }

    function get_topcore(slt_subcore) {
        $('#load_page').show();
        var slt_subcores = $('#slt_subcore').val();
        var slt_core_department = $("#slt_core_department").val();
        // var slt_subcore = $("#slt_subcore").val(); get_targetdates()
        var strURL="gettopcore.php?action=find_topcore_withname&slt_core_department="+slt_core_department+"&slt_subcore="+slt_subcores;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {

                        var ss1 = req.responseText;
                        var ss = ss1.split("!!");
                        document.getElementById('slt_topcore').value = ss[0];
                        document.getElementById('slt_topcore_name').value = ss[1];
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        getsubtype_value();
        $('#load_page').hide();
    }

    function getbudget() {
        var subtype_id = $('#slt_submission').val();
        if(subtype_id == 1 || subtype_id == 6 || subtype_id == 7) {
            $(".ttlsumrequired").attr('required', true);
            $("#getbudget").css("display", "block");
            // if(subtype_id != 7) {
                $("#getmonthwise_budget").css("display", "block");
            // }
        } else {
            $(".ttlsumrequired").attr('required', false);
            $("#getbudget").css("display", "none");
            // if(subtype_id != 7) {
                $("#getmonthwise_budget").css("display", "none");
            // }
        }
    }


    function getsubcore() {
        /* $('#load_page').show();
        var topcore_id = $("#slt_topcore").val();
        var targetno = $("#slt_targetno").val();
        var strURL="getsubcore.php?topcore_id="+topcore_id+"&targetno="+targetno;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        // document.getElementById('id_subcore').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    getapproval_listings();
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        $('#load_page').hide(); */
    }

    function get_sub_core() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        var slt_targetno=document.getElementById('slt_targetno').value;
        if(slt_submission == 2 || slt_submission == 3 || slt_submission == 4 || slt_submission == 8) {
            var strURL="getsubcore.php?action=find_subcore&tarnumb="+slt_targetno;
        } else {
            var strURL="getsubcore.php?action=find_org_subcore&tarnumb="+slt_targetno;
        }
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_subcore').innerHTML=req.responseText;
                        get_topcore($('#slt_subcore').val());
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        $('#load_page').hide();
    }

    function getsubtype(subtype_id) {
        $('#load_page').show();
        if(subtype_id == 1 || subtype_id == 6 || subtype_id == 7)
        {
            document.getElementById('id_branch').style.display = "block";
            document.getElementById('id_reqvalue').style.display = "block";
            document.getElementById('id_supplier').style.display = "block";
            // document.getElementById('getbudget').style.display = "block";
            if(subtype_id != 7) {
                document.getElementById('getmonthwise_budget').style.display = "block";
            }
            document.getElementById('id_reqvalue_hidden').style.display = "none";
        } else {
            document.getElementById('id_branch').style.display = "none";
            document.getElementById('id_reqvalue').style.display = "none";
            //document.getElementById('id_supplier').style.display = "none";
            // document.getElementById('getbudget').style.display = "none";
            if(subtype_id != 7) {
                document.getElementById('getmonthwise_budget').style.display = "none";
            }
            document.getElementById('id_reqvalue_hidden').style.display = "block";
        }

        var slt_submission=document.getElementById('slt_submission').value;
        /* if(slt_submission == 4) {
            $('#dynamic_subject').css('display', 'block');
        } else {
            $('#dynamic_subject').css('display', 'none');
        } */
        getsubtype_value();
        showmode();
        get_targetdates();
        find_tags();
        $('#load_page').hide();
    }

    function getsubtype_value() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        var slt_targetno=document.getElementById('slt_targetno').value;
        var project = '';
        var subtype_value_id = '';
        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
            slt_submission = 1;
        }
        var slt_subcore = $('#slt_subcore').val()

        /* if(slt_submission == 4) {
            $('#dynamic_subject').css('display', 'block');
        } else {
            $('#dynamic_subject').css('display', 'none');
        } */

        /* if(slt_submission == 2 || slt_submission == 3 || slt_submission == 4 || slt_submission == 8) {
            $('#id_subcore_list').css('display', 'none'); slt_subcore
            $('#id_subcore').css('display', 'block');
        } else {
            $('#id_subcore_list').css('display', 'block');
            $('#id_subcore').css('display', 'none');
        } */

        var strURL1="app_type_mode.php?subtype_value_id="+subtype_value_id+"&slt_submission="+slt_submission+"&slt_targetno="+slt_targetno+"&slt_subcore="+slt_subcore;
        var req1 = getXMLHTTP();
        if (req1) {
            req1.onreadystatechange = function() {
                if (req1.readyState == 4) {
                    if (req1.status == 200) {
                        // alert("hi");
                        // $(document).ready(function() {
                            $.getScript("js/jquery-customselect.js");
                            $(".chosn").customselect();
                            // document.getElementById('id_app_type_mode').innerHTML=req1.responseText;
                        // });
                        document.getElementById('id_appr_listings').innerHTML=req1.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                    }
                }
                $('#load_page').hide();
            }
            req1.open("GET", strURL1, true);
            req1.send(null);
        }

        getsubtype_value1();
        /* if(subtype_value_id == 3) {
            document.getElementById('due_dt').style.display = "block";
        } else {
            document.getElementById('due_dt').style.display = "none";
        } */
    }

    function getsubtype_value1() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        // var project = document.getElementById('slt_project').value;
        var project = '';
        var subtype_value_id = '';
        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
            slt_submission = 1;
        }

        /* var strURL="getsubtype_value.php?subtype_value_id="+subtype_value_id+"&project="+project;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        //alert("hi");
                        var resp = req.responseText;
                        // document.getElementById('id_subtype_value').innerHTML = resp;

                        $(document).ready(function ($) {
                            $.getScript("js/jquery-customselect.js");
                            $(".chosn").customselect();
                            $('#txt_submission_reqby').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
                                        },
                                        success: function( data ) {
                                            if(data == 'No User Available in this Top core and Sub Core') {
                                                $('#txt_submission_reqby').val('');
                                                var ALERT_TITLE = "Message";
                                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                            } else {
                                                response( $.map( data, function( item ) {
                                                    return {
                                                        label: item,
                                                        value: item
                                                    }
                                                }));
                                            }
                                        }
                                    });
                                },
                                autoFocus: true,
                                minLength: 0
                            });

                            $('#txt_workintiator').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
                                        },
                                        success: function( data ) {
                                            if(data == 'No User Available in this Top core and Sub Core') {
                                                $('#txt_workintiator').val('');
                                                var ALERT_TITLE = "Message";
                                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                            } else {
                                                response( $.map( data, function( item ) {
                                                    return {
                                                        label: item,
                                                        value: item
                                                    }
                                                }));
                                            }
                                        }
                                    });
                                },
                                autoFocus: true,
                                minLength: 0
                            });

                            $('#txt_alternate_user').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
                                        },
                                        success: function( data ) {
                                            if(data == 'No User Available in this Top core and Sub Core') {
                                                $('#txt_alternate_user').val('');
                                                var ALERT_TITLE = "Message";
                                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                            } else {
                                                response( $.map( data, function( item ) {
                                                    return {
                                                        label: item,
                                                        value: item
                                                    }
                                                }));
                                            }
                                        }
                                    });
                                },
                                autoFocus: true,
                                minLength: 0
                            });
                        });
                        $('#load_page').hide();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        $('#load_page').hide();
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }

       if(subtype_value_id == 3) {
            document.getElementById('due_dt').style.display = "block";
        } else {
            document.getElementById('due_dt').style.display = "none";
        } */
    }

    $('#txtdue_date').Zebra_DatePicker({
      direction: true,
      format: 'd-M-y'
    });

    function makeFileList() {
        var input = document.getElementById("filesToUpload");
        //alert("=="+input.files.length+"**");
        document.getElementById("txt_files").value = input.files.length;
        if(input.files.length > 4) {
            alert('You cannot upload more than 4 files');
        }
    }
    var loadedobjects="";

    function get_prddet()
    {
        var depcode = $('#slt_department_asset').val();
        var slt_targetno = $('#slt_targetno').val();
        $.ajax({
            url:"ajax/ajax_product_details.php?action=sub_prd&depcode="+depcode+"&slt_targetno="+slt_targetno,
            success:function(data)
            {
                $("#myModal1").modal('show');
                $('#modal-body1').html(data);
            }
        });
    }

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
    </script>

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>

    <!-- Light Box -->
    <link href="css/ekko-lightbox.css" rel="stylesheet">
    <!-- yea, yea, not a cdn, i know -->
    <script src="js/ekko-lightbox-min.js"></script>
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('#txt_submission_reqby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txt_submission_reqby').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_against_approval').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcore: $('#slt_topcore').val(),
                           type: 'approval_no'
                        },
                        success: function( data ) {
                            if(data == 'Approval No is not available') {
                                $('#txt_against_approval').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "Approval No is not available!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_workintiator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txt_workintiator').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_alternate_user').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txt_alternate_user').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });


            // Policy approval
            $('#txtdynamic_creator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_creator').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txtdynamic_coordinator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_coordinator').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txtdynamic_assistby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_assistby').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txtdynamic_userlist').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_userlist').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txtdynamic_approvedby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_approvedby').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });
            // Policy approval



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

            var prdid = '';
            $(".find_prdcode").autocomplete({
                source: function( request, response ) {
                    $('.find_prdcode').on('focus', function() {
                        prdid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           depcode: $('#slt_department_asset').val(),
                           action: 'product'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+prdid+"**"); */
                            if(data == 'NO PRODUCT') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+prdid).val('');
                                $('#'+prdid).focus();
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            var spdid = ''; var pr = ""; var prd = "";
            $('.find_subprdcode').autocomplete({
                source: function( request, response ) {
                    $('.find_subprdcode').on('focus', function() {
                        spdid = $(this).attr("id");
                        // alert("**"+spdid+"**");
                        pr = spdid.split("_");
                        prd = pr[2];
                        // alert("**"+pr+"**"+pr[2]);
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           // product: $('#txt_prdcode_'+prd).val(),
                           depcode: $('#slt_department_asset').val(),
                           action: 'sub_product'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+spdid+"**"); */
                            if(data == 'NO SUB PRODUCT') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Sub Product Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+spdid).val('');
                                $('#'+spdid).focus();
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            var spcid = '';
            $('.find_prdspec').autocomplete({
                source: function( request, response ) {
                    $('.find_prdspec').on('focus', function() {
                        spcid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           action: 'product_specification'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+spcid+"**"); */
                            if(data == 'NO SPECIFICATION') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Specification Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);

                                // $('.find_prdspec').on('keypress', function() {
                                    // var spcid = $(this).attr("id");
                                    // alert("**"+data+"**"+spcid+"**");
                                    $('#'+spcid).val('');
                                    $('#'+spcid).focus();
                                // });
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            var supid = '';
            $('.find_supcode').autocomplete({
                source: function( request, response ) {
                    $('.find_supcode').on('focus', function() {
                        supid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           action: 'supplier_withcity'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+supid+"**"); */
                            if(data == 'NO SUPPLIER') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Supplier Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+supid).val('');
                                $('#'+supid).focus();
                            } else {
                                response( $.map( data, function( item ) {
                                    return {
                                        label: item,
                                        value: item
                                    }
                                }));
                            }
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            // delegate calls to data-toggle="lightbox"
            $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    onShown: function() {
                        if (window.console) {
                            return console.log('Checking our the events huh?');
                        }
                    },
                    onNavigate: function(direction, itemIndex) {
                        if (window.console) {
                            return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                        }
                    }
                });
            });

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
    <!-- Light Box -->

    <!-- Light Box - New -->
    <link href="css/lightgallery.css" rel="stylesheet">
    <script type="text/javascript">
    $(document).ready(function(){
        $('.lightgallery').lightGallery();
    });

    function enable_all() {
        $("#id_budplanner").removeClass("disabledbutton");
        $('#id_budplanner').find('input, textarea').attr('readonly', false);
        $('#id_budplanner').find('button, select, radio').attr('disabled', false);
        $("#id_budplanner").attr("readonly", false);

        $("#id_supplier").removeClass("disabledbutton");
        $('#id_supplier').find('input, textarea').attr('readonly', false);
        $('#id_supplier').find('button, select, radio').attr('disabled', false);
        $("#id_supplier").attr("readonly", false);
    }

    function enable_month() {
        /* // $('#id_supplier *').prop('disabled',true);
        $("#id_supplier").addClass("disabledbutton");
        // $('#id_supplier').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
        $('#id_supplier').find('input, textarea').attr('readonly', 'readonly');
        $('#id_supplier').find('button, select, radio').attr('disabled', 'disabled');
        $("#id_supplier").attr("readonly", "1");
        $('#id_supplier').find('input, textarea').val('');

        $("#id_budplanner").removeClass("disabledbutton");
        $('#id_budplanner').find('input, textarea').attr('readonly', false);
        $('#id_budplanner').find('button, select, radio').attr('disabled', false);
        $("#id_budplanner").attr("readonly", false); */
    }

    function enable_product() {
        /* // $('#id_budplanner *').prop('disabled',true);
        $("#id_budplanner").addClass("disabledbutton");
        // $('#id_budplanner').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
        $('#id_budplanner').find('input, textarea').attr('readonly', 'readonly');
        $('#id_budplanner').find('button, select, radio').attr('disabled', 'disabled');
        $("#id_budplanner").attr("readonly", "1");
        $('#id_budplanner').find('input, textarea').val('');

        $("#id_supplier").removeClass("disabledbutton");
        $('#id_supplier').find('input, textarea').attr('readonly', false);
        $('#id_supplier').find('button, select, radio').attr('disabled', false);
        $("#id_supplier").attr("readonly", false); */
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
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>
