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

$appstat = " 'N' "; $appfrwd = " and ( ar.APPFRWD = 'F' or ar.APPFRWD = 'I' ) "; $stats = $_REQUEST['status'];
if($_REQUEST['status'] == 'Forward') {
    $appstat = " 'F' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPSTAT in ( '', 'F' ) and ar.APPFRWD not in ( 'I' ) ";
} elseif($_REQUEST['status'] == 'Approved') {
    $appstat = " 'A', 'F' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'A' or ar.APPFRWD = 'F' or ar.APPFRWD = 'S' or ar.APPFRWD = 'N' ) and ar.APPSTAT in ( 'A', 'F', 'N' ) ";
} elseif($_REQUEST['status'] == 'Pending') {
    // $appstat = " 'N', 'P' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' or ar.REQSTFR = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'P' or ar.APPFRWD = 'S' ) and ar.APPSTAT in ( 'N' ) ";
} elseif($_REQUEST['status'] == 'Rejected') {
    // $appstat = " 'R' ";
    $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ( ar.APPFRWD = 'R' or ar.APPFRWD = 'F' or ar.APPFRWD = 'S' ) and ar.APPSTAT in ( 'R' ) ";
}  elseif($_REQUEST['status'] == 'IV' or $_REQUEST['status'] == 'Internal Verification') {
    // $appstat = " 'I' ";
    if ($_REQUEST["search_md"] == "") {
        $stats = "Internal Verification";
        $appfrwd = " and ( ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
    } else {
        $stats = "Internal Verification";
        $appfrwd = " and ( ar.REQSTBY = '".$_REQUEST["search_md"]."' ) and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') ";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>        
<!-- META SECTION -->
<title><?=$stats?> Approvals List :: Approval Desk :: <?php echo $site_title; ?></title>             
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
</head>
<body>
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
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
                <li class="active">Reports - <?=$stats?> Approvals List</li>
            </ul>
            <!-- END BREADCRUMB -->                       
            
            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?=$stats?> Approvals List</h3>
                        
                    </div>
                    <div class="panel-body">
                        <div class="form-group trbg non-printable">
                            <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                
                                <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                    <input type='text' class="form-control" style="text-transform:uppercase" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details">
                                </div>
                                
                                <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 5px;'>
                                    <input type='text' class="form-control" style="text-transform:uppercase" tabindex='1' name='search_aprno' id='search_aprno' value='<?=$_REQUEST['search_aprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Approval No' title="Approval No">
                                </div>

                                <div class="col-xs-1" style='text-align:center; padding:5px;'>
                                    <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='Value' title="Value" style='text-transform: uppercase;'>
                                </div>

                                <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 6px;'>
                                    <input type='hidden' style="cursor:pointer; text-transform:uppercase" class="form-control" name="search_add_findate" id="search_add_findate" value='ADDDATE'>
                                    <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                </div>

                                <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                    <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                </div>

                                <div class="col-xs-1" style='text-align:center; padding:5px;'>
                                    <select class="form-control chosen-select" name='search_topcore' id='search_topcore' style="padding: 0px;" title="All Topcore">
                                        <option <? if($_REQUEST['search_topcore'] == '') { ?> selected <? } ?> value=''>--All Topcore--</option>
                                        <?  $sql_section_group = select_query_json("select * from approval_topcore where DELETED = 'N' order by ATCSRNO Asc", "Centra", 'TEST');
                                            for($s=0;$s<count($sql_section_group);$s++) { ?>
                                              <option <? if($sql_section_group[$s]['ATCCODE'] == $_REQUEST['search_topcore']) { ?> selected <? } ?> value='<?=$sql_section_group[$s]['ATCCODE']?>'> <?=$sql_section_group[$s]['ATCNAME']?> </option>
                                        <? } ?>
                                    </select>
                                </div>

                                <? 
                                $mds = array('35613','74266','43878','1280','452','52717'); //D saravanan,M Mohanraj,Arun Rama Balan,S saravanan,Kumaran,Thangadurai
                                if(in_array($_SESSION['tcs_empsrno'], $mds)){ ?>    
                                <div class="col-xs-1" style='text-align:center; padding:5px;'>
                                    <select class="form-control chosen-select" name='search_md' id='search_md' style="padding: 0px;" title="S KAARTHI Sir">
                                        <option value=''> Select MD </option> <?
                                        if($_SESSION['tcs_empsrno'] == 35613){ ?>
                                        <option value='21344' <? if ($_REQUEST['search_md'] == 21344) { ?> selected <? } ?> > S KAARTHI Sir </option>
                                         <?}else{?>
                                        <option value='20118' <? if ($_REQUEST['search_md'] == 20118) { ?> selected <? } ?> > KS SIR </option>
                                        <option value='43400' <? if ($_REQUEST['search_md'] == 43400) { ?> selected <? } ?> > PS MADAM </option>
                                        <option value='21344' <? if ($_REQUEST['search_md'] == 21344) { ?> selected <? } ?> > S KAARTHI Sir </option>
                                         <?}?>
                                    </select>
                                </div> <?}else{?>
                                <div class="col-xs-1"></div> <?}?>  
                                    
                                <div class="col-xs-1" style='text-align:left; padding:5px;'>
                                    <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                </div>
                            </form>
                        </div>
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                        <? /* <table id="customers2" class="table datatable"> */ ?>
                        <table class="table" id="customers2">
                            <thead>
                                <tr>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>#</th>
                                    <th>Approval Number</th>
                                    <th>Details</th>
                                    <th>Value &#8377;</th>
                                    <th>Request by</th>
                                    <? if($_REQUEST['status'] == 'IV') { ?><th>Waiting Person</th><? } ?>
                                    <th>Request to</th>
                                    <th style='text-align:center;'>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?  $and = "";
                                    if($search_aprno != '') {
                                        $and .= " And ar.APRNUMB like '%".strtoupper($search_aprno)."%' ";
                                    }
                                    if($search_subject != '') {
                                        $and .= " And ar.APPRDET like '%".strtoupper($search_subject)."%' ";
                                    }
                                    if($dept != '') {
                                        $and .= " And ar.ATCCODE = '".$dept."' ";
                                    }
                                    if($prty != '') {
                                        $and .= " And ar.APPSTAT in ('A') And ar.PRICODE = '".$prty."' ";
                                    }
                                    if($search_topcore != '') {
                                        $and .= " And ar.ATCCODE = '".$search_topcore."' ";
                                    }
                                    if($search_fromdate != '' or $search_todate != '') {
                                        if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                        $exp1 = explode("-", $search_fromdate);
                                        $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                        
                                        if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                        $exp2 = explode("-", $search_todate);
                                        $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));
                                        
                                        if($search_add_findate == 'ADDDATE') {
                                            $and .= " And trunc(ar.ADDDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        } elseif($search_add_findate == 'FINDATE') {
                                            $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                        }
                                    } else {
                                        if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                        $exp2 = explode("-", $search_todate);
                                        $to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));
                                        $and .= " And trunc(ar.FINDATE) BETWEEN TO_DATE('01-JAN-15', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
                                    }
                                    if($search_value != '') {
                                        $and .= " And ar.APPFVAL like '%".strtoupper($search_value)."%' ";
                                    }
                                    if($search_finishornot != '') {
                                        if($search_finishornot == 1) {
                                            $and .= " And ar.appstat in ('A', 'R') ";
                                        } elseif($search_finishornot == 2) {
                                            $and .= " And ar.appstat not in ('A', 'R') ";
                                        } 
                                    }

                                    if($and != '') {
                                        $sql_search = select_query_json("select * from 
                                                                               ( select a.*, ROWNUM rnum from 
                                                                                    ( select distinct ar.APRNUMB, ar.APPSTAT, ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL,ar.ARQCODE, ar.ATYCODE, 
                                                                                            ar.ATCCODE, ar.ARQYEAR, ar.RQESTTO, decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 
                                                                                            'P', 'PENDING', 'Q', 'QUERY', 'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, 
                                                                                            decode(ar.APPSTAT, 'N','1','F', '2', 'A', '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') 
                                                                                            APPORDER, ar.ARQSRNO, ar.APPRDET, ar.PRICODE, ar.APPRFOR, ar.RQTODES reqto, ar.RQFRDES pndingby, 
                                                                                            (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST 
                                                                                            where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and 
                                                                                            ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and deleted = 'N')) as reqby, 
                                                                                            pr.PRICODE||' - '||pr.PRINAME priority 
                                                                                        from APPROVAL_REQUEST ar, approval_priority pr 
                                                                                        where ar.PRICODE = pr.PRICODE and pr.deleted = 'N' and ar.DELETED = 'N' ".$appfrwd." ".$and." 
                                                                                        order by ar.ARQYEAR desc, APRNUMB desc ) a 
                                                                                where ROWNUM <= 50 )
                                                                            where rnum > 0", "Centra", 'TEST'); // ".$appfrwd." 
                                        /* if($sql_search[0]['REQBY'] == '') {
                                            $sql_search = select_query_json("select distinct ar.APRNUMB, ar.APPSTAT, ar.APPFRWD, ar.APPRSUB, ar.APPFVAL, ar.APRTITL, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, 
                                                                                    ar.ARQYEAR, ar.RQESTTO, decode(ar.APPSTAT, 'N', 'NEW', 'F', 'FORWARD', 'A', 'APPROVED', 'P', 'PENDING', 'Q', 'QUERY', 
                                                                                    'S', 'RESPONSE', 'C', 'COMPLETED', 'R', 'REJECTED', 'NEW') APPSTATUS, decode(ar.APPSTAT, 'N','1','F', '2', 'A', 
                                                                                    '3', 'P', '4', 'Q', '5', 'S', '6', 'C', '7', 'R', '8', '9') APPORDER, (select APPSTAT from APPROVAL_REQUEST 
                                                                                    where ARQSRNO = 1 and DELETED = 'N' and ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ATCCODE = ar.ATCCODE 
                                                                                    and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE and APPSTAT = 'A') as APSTAT, 
                                                                                    (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, ar.ARQSRNO, ar.APPRDET, ar.PRICODE, 
                                                                                    (select EMPNAME from employee_office where empsrno in (select REQSTBY from APPROVAL_REQUEST where ARQCODE = ar.ARQCODE 
                                                                                    and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and ATMCODE = ar.ATMCODE and APMCODE = ar.APMCODE 
                                                                                    and deleted = 'N' and rownum = 1)) as reqby, (select EMPNAME from employee_office where empsrno = ar.REQSTFR) as 
                                                                                    pndingby, ar.APPRFOR
                                                                                from APPROVAL_REQUEST ar 
                                                                                where ar.DELETED = 'N' ".$appfrwd." ".$and." 
                                                                                order by PRICODE Asc, ADDDATE asc, ARQYEAR desc, APPORDER Asc, APRNUMB desc", "Centra", 'TEST');
                                        } */

                                $ij = 0;
                                for($search_i = 0; $search_i < count($sql_search); $search_i++) { $ij++;
                                    // A - Approved; N - Normal / Newly Created; R - Rejected; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
                                    $editid = 0; $bgclr = ''; $clr = '#000000';
                                    if($sql_search[$search_i]['APPSTAT'] == 'A') { $appstatus = "3 - APPROVED"; $bgclr = '#DFF0D8'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'N') { $appstatus = "1 - NEW"; $editid = 1; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'R') { $appstatus = "7 - REJECTED"; $bgclr = '#F2DEDE'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'F') { $appstatus = "2 - FORWARD"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'C') { $appstatus = "8 - COMPLETED"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'P') { $appstatus = "4 - PENDING"; $editid = 0; $bgclr = '#FAF4D1'; $clr = '#000000'; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'S') { $appstatus = "5 - RESPONSE"; }
                                    if($sql_search[$search_i]['APPSTAT'] == 'Q') { $appstatus = "6 - QUERY"; }
                                    $filename = $sql_search[$search_i]['IMFNIMG'];
                                    ?>
                                    <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                        <td><?=$sql_search[$search_i]['PRIORITY']?></td>
                                        <td class="center" style='text-align:center;'><? echo $appstatus; ?></td>
                                        <td style='text-align:center'><?=$sql_search[$search_i]['RNUM']?></td>
                                        <td><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_search[$search_i]['APRNUMB']; ?></a></td>
                                        <td class="center show_moreless">
                                        <?  if($sql_search[$search_i]['APPRFOR'] == '1') {
                                                $filepathname = $sql_search[$search_i]['APPRSUB'];
                                                $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
                                                $handle = fopen($filename, "r"); // or die("The content might not be available!. Contact Admin - ". $sql_search[$search_i]['APPRSUB']);
                                                $contents = fread($handle, filesize($filename));
                                                fclose($handle);
                                                // echo strip_tags(str_replace("&nbsp;", " ", $contents));
                                                echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit=1000, $strip = false);
                                            } else {
                                                echo $sql_search[$search_i]['APPRDET'];
                                            }
                                        ?></td>
                                        <td><? if($sql_search[$search_i]['APPFVAL'] > 0) { echo moneyFormatIndia($sql_search[$search_i]['APPFVAL']); } else { echo "-Nil-"; } ?></td>
                                        <td class="center"><? echo $sql_search[$search_i]['REQBY']; ?></td>
                                        <? if($_REQUEST['status'] == 'IV') { ?>
                                            <td class="center"><? echo $sql_search[$search_i]['PNDINGBY']; ?></td>
                                        <? } ?>
                                        <td class="center"><? echo $sql_search[$search_i]['APRTITL']." ".$sql_search[$search_i]['REQTO']; ?></td>
                                        <td class="center" style='text-align:center;'>
                                            <? if($sql_search[$search_i]['APPSTAT'] == 'N' or $sql_search[$search_i]['APPSTAT'] == 'P') { ?><a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=<? echo $sql_search[$search_i]['ARQSRNO']; ?>&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a><? } else { ?>
                                                <a href='view_pending_approval.php?action=view&reqid=<? echo $sql_search[$search_i]['ARQCODE']; ?>&year=<? echo $sql_search[$search_i]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_search[$search_i]['ATCCODE']; ?>&typeid=<? echo $sql_search[$search_i]['ATYCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a>
                                            <? } ?></td>
                                    </tr>
                                    <? } ?>
                                        <input type="hidden" id="result_approvals" value="<?=count($sql_search)?>">
                                        <input type="hidden" id="and_content" value="<?=$and?>">
                                        <input type="hidden" id="appfrwd_content" value="<?=$appfrwd?>">
                                        <tr class='aload1'><td class='aload1' colspan="8" style="text-align:center; width:100%;"><a href='javascript:void(0)' id="load_1" class='aload1' onclick="loadmore_1()"><i class="fa fa-spinner"></i> Load More!!</a></td></tr>
                                        <tr><td colspan="8" id='shownotification' style="display: none;">
                                            <img src='images/page-loader.gif'>
                                        </td></tr>
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
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>        
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>
    
    <? /* <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script> */ ?>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->        

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    
    <script type="text/javascript" src="js/plugins.js"></script>        
    <script type="text/javascript" src="js/actions.js"></script>
    
    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <!-- END TEMPLATE -->

    <? /* <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script> */ ?>
    <script type="text/javascript">
    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
    }

    function loadmore_1()
    {
        $('#shownotification').show();
        var val = document.getElementById("result_approvals").value;
        var val_cnt = +val + 50;
        var and_content = $('#and_content').val();
        var appfrwd_content = $('#appfrwd_content').val();
        $.ajax({
            type: 'post',
            url: 'ajax/ajax_loadmore.php?action=loadmore_detail&frm_cnt='+val+'&to_cnt='+val_cnt+'&and='+and_content+'&appfrwd='+appfrwd_content,
            data: {
                getresult:val
            },
            success: function (response) {
                $('.aload1').html('');
                $('#customers2 tbody').append(response);

                // $('#allnotification').removeClass('dropdown').addClass('dropdown open');
                // $('.aload1').html($('.aload1 a#load_1').text());
                // var content = document.getElementById("shownotification");
                // content.innerHTML = content.innerHTML + response;
                // We increase the value by 50 because we limit the results by 50
                document.getElementById("result_approvals").value = Number(val) + 50;
                $('#shownotification').hide();
            }
        });
    }
    
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
            $("#load_page").fadeOut("slow");
            $(".finish_confirm").click( function() {
            });
        });

        $(document).keypress(function(e) { 
            if (e.keyCode == 27) { 
                $("#myModal1").fadeOut(500);
            } 
        });
        
        $('#datepicker-example3').Zebra_DatePicker({
          direction: false, // 1, 
          format: 'd-M-Y',
          pair: $('#datepicker-example4')
        });
        
        $('#datepicker-example4').Zebra_DatePicker({
          direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0, 
          format: 'd-M-Y'
        });

        function call_confirm(ivalue, reqid, year, rsrid, creid, typeid, aprnumb)
        {
            $('#load_page').show();
            var send_url = "final_finish.php?aprnumb="+aprnumb+"&reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
            $.ajax({
            url:send_url,
            type: "POST",
            success:function(data){
                    $("#myModal1").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body1').innerHTML=data;
                    $('#load_page').hide();
                }
            });
        }
    </script>
<!-- END SCRIPTS -->         
</body>
</html>