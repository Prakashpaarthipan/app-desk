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

// AFTER MAR/1/2017 - FOR AK SIR
$mr_ak_date="";
if($_SESSION['tcs_usrcode'] == 3000000) {
    $mr_ak_date = " and trunc(APPRSFR) >= TO_DATE('01-MAR-17','dd-Mon-yy') ";
}

$menu_name1 = 'MD APPROVAL';
$inner_submenu1 = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name1."' order by MNUCODE Asc", "Centra", 'TCS');
if($_SESSION['tcs_empsrno'] != '') {
    $inner_menuaccess1 = select_query_json("select * from srm_menu_access 
                                                    where MNUCODE = ".$inner_submenu1[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc", "Centra", 'TCS');
} else {
    $inner_menuaccess1 = select_query_json("select * from srm_menu_access 
                                                    where MNUCODE = ".$inner_submenu1[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc", "Centra", 'TCS');
}
if($inner_menuaccess1[0]['VEWVALU'] == 'Y' and ($_SESSION['tcs_usrcode'] == 3000000 or $_SESSION['tcs_usrcode'] == 9938358 or $_SESSION['tcs_usrcode'] == 9193333)) { 
    $link_name = "waiting_approval_report.php";
} else {
    $link_name = "waiting_approval.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Dashboard :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<link rel="stylesheet" type="text/css" href="css/social-buttons.css"/>
<style>
.dash-box { margin: 50px 0 10px !important; }
.breadcrumb { padding: 6px 15px 0px !important; margin-bottom: 0px !important; }
#main_wrapper{
    background: url('images/ap1.png') #FFFFFF;
}

#main_content{
    background-color: #000000;
    height: 5em;
    width: 5em;
}
.view_grid{
  margin: 0 13%;
}
@media only screen and (max-width: 980px) {
  .view_grid{
    margin: 0;
  }
   .badge_1{
      font-size: 20px !important;
      margin-left:5px;
    }
}
@media only screen and (max-width: 768px) {
   /* clear: both !important;*/
    .badge_1{
      font-size: 20px !important;
      margin-left:5px;
    }
}
@media only screen and (min-width: 769px) and (max-width: 991px) {
   /* clear: both !important;*/
    .badge_1{
      font-size: 20px !important;
      margin-left:5px;
    }
}
@media only screen and (min-width: 992px) and (max-width: 1199px) {
   /* clear: both !important;*/
    .badge_1{
      font-size: 10px !important;
    /*margin-left:5px;*/
    }
}
@media only screen and (min-width: 1441px){
   /* clear: both !important;*/
    .badge_1{
      font-size: 20px !important;
      margin-left:7px;
    }
}
</style>
 <style type="text/css" media="screen">
        
.badge_1 {
    left: 81%;
    top: 50%;
    position: absolute;
    transform: translate(-50%, -50%);
    font-weight:bold;
    color:#000 !important;
}    
.logo{
 text-align:center;
  padding-top:10px;
  padding-bottom:10px;
}                             
 </style>
<!-- EOF CSS INCLUDE -->
</head>
<body style="overflow: hidden;">
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
                <li><a href="home.php">Home</a></li>
                <li class="active">Dashboard</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">


            <form role="form" id='frm_archive_search' name='frm_archive_search' action='' method='post' >
            <div class="row" style="margin: 0px;">
                <?  $sql_ccnt = select_query_json("select nvl(sum(decode(APPSTAT||APPFRWD||REQSTFR,'NF".$_SESSION['tcs_empsrno']."', 1, 'NI".$_SESSION['tcs_empsrno']."', 1,
                                                                'NS".$_SESSION['tcs_empsrno']."', 1, 0)), 0) cntstat, ATCCODE, decode(ATCCODE, '1', 'S-TEAM', '2', 'MANAGEMENT', '3', 'OPERATION',
                                                                '4', 'ADMIN', 'GENERAL') ATCCODE_Status
                                                            from APPROVAL_REQUEST
                                                            where ATCCODE in (1,2,3,4,5) and APPSTAT = 'N' and APPFRWD in ('F', 'I', 'S') and DELETED = 'N' ".$mr_ak_date."
                                                            group by ATCCODE order by ATCCODE", 'Centra', 'TEST');

                    $sql_ccnt_priority = select_query_json("select nvl(sum(decode(PRICODE, '1', 1, '2', 1, '3', 1, '4', 1, '5', 1, 0)), 0) cntstat, PRICODE, decode(PRICODE, '1', 'DO RIGHT AWAY',
                                                                        '2', 'PLAN TO DO ASAP', '3', 'DELEGATE', 4, 'FUTURE WORK ORDERS', 5, 'PENDING / CANCEL / REJECT', 'PENDING / CANCEL / REJECT') ATCCODE_Status
                                                                    from APPROVAL_REQUEST
                                                                    where ATCCODE in (1,2,3,4,5) and DELETED = 'N' AND APPSTAT = 'N' and APPFRWD in ('F', 'I', 'S') and
                                                                        (REQSTFR = '".$_SESSION['tcs_empsrno']."') ".$mr_ak_date."
                                                                    group by PRICODE order by PRICODE", 'Centra', 'TEST');

                    $and_archive_cnt = ""; $ttlvl = 0; $cnt_search = 0;
                    if($txt_apprno != '') {
                        $and_archive_cnt .= " and ar.aprnumb like '%".$txt_apprno."%' ";
                    } else {
                        if($prty != '') {
                            $and_archive_cnt .= " and ar.PRICODE = '".$prty."' ";
                            $sql_pri = select_query_json("select * from approval_priority where pricode = '".$prty."'", 'Centra', 'TEST');
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
                    }

                    foreach ($sql_ccnt as $key => &$entry) {
                        // echo "<br>==".$entry."==".$sql_search1[$entry['ATCCODE_STATUS']][$key]."==".$key."==".$entry['ATCCODE_STATUS']."==";
                        $sql_search1[$entry['ATCCODE_STATUS']][$key] = $entry;
                    }
                    // print_r($sql_search1);

                    foreach ($sql_ccnt_priority as $key => &$entry) {
                        $sql_search2[$entry['PRICODE']][0] = $entry;
                    }

                    $gm1 = count(array_keys($sql_search1['S-TEAM']));
                    $gm2 = count(array_keys($sql_search1['MANAGEMENT']));
                    $gm3 = count(array_keys($sql_search1['OPERATION']));
                    $gm4 = count(array_keys($sql_search1['ADMIN']));
                    $gm5 = count(array_keys($sql_search1['GENERAL']));

                    $pr1 = count(array_keys($sql_search2['1']));
                    $pr2 = count(array_keys($sql_search2['2']));
                    $pr3 = count(array_keys($sql_search2['3']));
                    $pr4 = count(array_keys($sql_search2['4']));
                    $pr5 = count(array_keys($sql_search2['5'])); ?>

               
                     <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" >
                        <div style="clear: both;" class=".hidden-sm"></div>
                      <div>
                     <div class="res_das" style="align-content: center">
                                <? $sql_priority = select_query_json("select * from approval_priority where deleted = 'N'", 'Centra', 'TEST'); ?>
                         <div class="col-md-1"></div>       
                      
                        <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12 cursor_pointer logo" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                            <!-- <div class="dash-box dash-box-color-1"> 
                                <div class="dash-box-icon">
                                    <i class="fa fa-question-circle"></i>
                                </div>-->
                                <a href="<?=$link_name?>?status=waiting&prty=1" >
                                      <img src="images/dashboard/image1.png" width="90%" style="position:relative">
                                     

                                       <div class="badge_1">2</div> 
                                    </a>
                            
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12 cursor_pointer logo" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                          <a href="">
                              <img src="images/dashboard/image2.png" width="90%" style="position:relative">
                              
                                <!-- <div class="pull-right" style='font-size:18px; font-weight:bold;  background-color:#FFFFFF;color:#000000;min-width: 40px;text-align: center;border-radius: 30px;margin: 48px 64px;padding:12px;right:1%;bottom:-6px;position:absolute;border:solid 2px #00a651'><? if($sql_search2['2'][0]['CNTSTAT'] > 0) { echo $sql_search2['2'][0]['CNTSTAT']; } else { echo "0"; } ?></div> -->
                                <div class="badge_1"> 999 </div> 
                            </a>
                        </div>
                         <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12 cursor_pointer logo" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                         <a href="">
                              <img src="images/dashboard/image3.png" width="90%"  style="position:relative">
                              
                                <!-- <div class="pull-right" style='font-size:18px; font-weight:bold; background-color:#FFFFFF;color:#000000;min-width: 40px;text-align: center;border-radius: 30px;margin: 48px 64px;padding:12px;right:1%;bottom:-6px;position:absolute;border:solid 2px #fff200'><? if($sql_search2['3'][0]['CNTSTAT'] > 0) { echo $sql_search2['3'][0]['CNTSTAT']; } else { echo "0"; } ?></div> -->
                                <div class="badge_1"> 2 </div> 
                            </a>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12 cursor_pointer logo" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                             <a href="">
                              <img src="images/dashboard/image4.png" width="90%"  style="position:relative">
                              
                                <div class="badge_1">  6</div> 

                                </a>
                        </div>
                         <div class="col-md-2 col-lg-2 col-sm-4 col-xs-12 cursor_pointer logo" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                             <a href="">
                              <img src="images/dashboard/image5.png" width="90%"  style="position:relative">
                                <!-- <div class="pull-right" style='font-size:16px; font-weight:bold;  background-color:#FFFFFF;color:#000000;min-width: 40px;text-align: center;border-radius:30px;margin: 48px 64px;padding: 12px;right:1%;bottom:-6px;position:absolute;border:solid 2px #32327b'><? if($sql_search2['5'][0]['CNTSTAT'] > 0) { echo $sql_search2['5'][0]['CNTSTAT']; } else { echo "0"; } ?></div> -->
                                <div class="badge_1"> 7</div> 
                            </a>
                         </div>
                       

                        
                    </div>
                </div>  
            </div>

            <!-- APP LEVEL -->
                
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div style="clear: both;" class=".hidden-sm"></div>
                    <!-- Waiting with Query! -->
                    <?  /* $sql_new_request = select_query_json("select 1, 'new_request' stat, nvl(sum(count(APPSTAT)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ARQSRNO = 1 and REQSTBY = '".$_SESSION['tcs_empsrno']."' and DELETED = 'N' and APPSTAT='N' ".$mr_ak_date." group by 1
                                                                    union
                                                                        select 2, 'query_request' stat, nvl(sum(count(APPSTAT)),0)  as CNTNWREQ from APPROVAL_REQUEST
                                                                        where (REQSTFR = '".$_SESSION['tcs_empsrno']."' or INTPEMP = '".$_SESSION['tcs_empsrno']."') and APPSTAT='N' and DELETED = 'N'
                                                                            and ( APPFRWD = 'Q' or APPFRWD = 'P' ) ".$mr_ak_date." group by 2
                                                                    union
                                                                        select 3, 'waiting_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((( REQSTFR = ".$_SESSION['tcs_empsrno']." ) and APPSTAT in ('N') and ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I'))
                                                                            or ((REQSTBY = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('W'))) and DELETED = 'N' ".$mr_ak_date." group by 3
                                                                    union
                                                                        select 4, 'alternate_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((( INTPEMP = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('N') and ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I'))
                                                                            or ((REQSTBY = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('W'))) and DELETED = 'N' ".$mr_ak_date." group by 4
                                                                    union
                                                                        select 5, 'bid_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((( REQSTFR = ".$_SESSION['tcs_empsrno']." or INTPEMP = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('Z') and
                                                                            ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I')) or ((REQSTBY = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('Z')))
                                                                            and DELETED = 'N' ".$mr_ak_date." group by 5
                                                                    union
                                                                        select 6, 'post_audit_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST ar
                                                                        where REQSTFR = ".$_SESSION['tcs_empsrno']." and APPSTAT in ('N') and DELETED = 'N' and aprnumb = (select aprnumb from APPROVAL_REQUEST
                                                                            where appstat in ('A') and deleted = 'N' and arqsrno = 1 and arqpcod = ar.arqpcod and arqcode = ar.arqcode and arqyear = ar.arqyear
                                                                            and atycode = ar.atycode and atccode = ar.atccode and aprnumb = ar.aprnumb) ".$mr_ak_date." group by 6", 'Centra', 'TCS'); */
                        $sql_new_request = select_query_json("select 1, 'new_request' stat, nvl(sum(count(APPSTAT)),0) as CNTNWREQ from APPROVAL_REQUEST where ARQSRNO = 1 and
                                                                        REQSTBY = '".$_SESSION['tcs_empsrno']."' and DELETED = 'N' and APPSTAT='N' ".$mr_ak_date." group by 1
                                                                    union
                                                                        select 2, 'query_request' stat, nvl(sum(count(APPSTAT)),0)  as CNTNWREQ from APPROVAL_REQUEST
                                                                        where (REQSTFR = '".$_SESSION['tcs_empsrno']."' or INTPEMP = '".$_SESSION['tcs_empsrno']."') and APPSTAT='N' and DELETED = 'N'
                                                                            and ( APPFRWD = 'Q' or APPFRWD = 'P' ) ".$mr_ak_date." group by 2
                                                                    union
                                                                        select 3, 'waiting_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((REQSTFR = ".$_SESSION['tcs_empsrno']." and APPSTAT in ('N') and ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I')) or
                                                                            ((REQSTBY = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('W'))) and DELETED = 'N' ".$mr_ak_date." group by 3
                                                                    union
                                                                        select 4, 'alternate_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((INTPEMP = ".$_SESSION['tcs_empsrno']."  and APPSTAT in ('N') and ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I')) or
                                                                            ((INTPEMP = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('W'))) and DELETED = 'N' ".$mr_ak_date." group by 4
                                                                    union
                                                                        select 5, 'bid_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST
                                                                        where ((( REQSTFR = ".$_SESSION['tcs_empsrno'].") and APPSTAT in ('Z') and ( APPFRWD = 'F' or APPFRWD = 'S' or APPFRWD = 'I')) or
                                                                            ((REQSTBY = '".$_SESSION['tcs_empsrno']."') and APPSTAT in ('W'))) and DELETED = 'N' ".$mr_ak_date." group by 5
                                                                    union
                                                                        select 6, 'post_audit_request' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST ar
                                                                        where REQSTFR = ".$_SESSION['tcs_empsrno']." and APPSTAT in ('U') and DELETED = 'N' and aprnumb = (select aprnumb
                                                                            from APPROVAL_REQUEST where appstat in ('A') and deleted = 'N' and arqsrno = 1 and arqpcod = ar.arqpcod and
                                                                            arqcode = ar.arqcode and arqyear = ar.arqyear and atycode = ar.atycode and atccode = ar.atccode and
                                                                            aprnumb = ar.aprnumb) ".$mr_ak_date." group by 6
                                                                    union
                                                                        select 7, 'acknowledge_approvals' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST ar
                                                                        where ar.RPTUSER = '".$_SESSION['tcs_empsrno']."' and ar.deleted = 'N' and ACKUSER is null ".$mr_ak_date." group by 7
                                                                    union
                                                                        select 8, 'sending_iv_approvals' stat, nvl(sum(count(atycode)),0) as CNTNWREQ from APPROVAL_REQUEST ar
                                                                        where ar.deleted = 'N' and ar.REQSTBY = '".$_SESSION['tcs_empsrno']."' and ar.APPFRWD = 'I' and ar.APPSTAT in ('N') group by 8",
                                                                'Centra', 'TEST');
                        // if($_SESSION['md_rights'] == 0) {
                            $wwqr = 0; if($sql_new_request[1]["CNTNWREQ"] != '') { $wwqr = $sql_new_request[1]["CNTNWREQ"]; }
                            $wapp = 0; if($sql_new_request[2]["CNTNWREQ"] != '') { $wapp = $sql_new_request[2]["CNTNWREQ"]; }
                            $nwrq = 0; if($sql_new_request[0]["CNTNWREQ"] != '') { $nwrq = $sql_new_request[0]["CNTNWREQ"]; }
                            $nwal = 0; if($sql_new_request[3]["CNTNWREQ"] != '') { $nwal = $sql_new_request[3]["CNTNWREQ"]; }
                            $nwbd = 0; if($sql_new_request[4]["CNTNWREQ"] != '') { $nwbd = $sql_new_request[4]["CNTNWREQ"]; }
                            $poad = 0; if($sql_new_request[5]["CNTNWREQ"] != '') { $poad = $sql_new_request[5]["CNTNWREQ"]; }
                            $akap = 0; if($sql_new_request[6]["CNTNWREQ"] != '') { $akap = $sql_new_request[6]["CNTNWREQ"]; }
                            $snqr = 0; if($sql_new_request[7]["CNTNWREQ"] != '') { $snqr = $sql_new_request[7]["CNTNWREQ"]; } ?>
                            <? /* <div class="col-md-4">
                                <!-- START WIDGET SLIDER -->
                                <div class="widget widget-danger widget-item-icon cursor_pointer" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                                    <div class="widget-item-left">
                                        <span class="fa fa-question-circle"></span>
                                    </div>
                                    <div class="widget-data">
                                        <div class="widget-int num-count"><? echo $wwqr; ?></div>
                                        <div class="widget-title">Waiting with Query</div>
                                    </div>
                                    <div class="widget-controls">
                                        <a href="javascript:void(0)" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                                    </div>
                                </div>
                                <!-- END WIDGET SLIDER -->
                            </div>
                    <!-- Waiting with Query! -->

                    <!-- Waiting for Approval! -->
                    <div class="col-md-4">
                        <!-- START WIDGET MESSAGES -->
                        <div class="widget widget-success widget-item-icon cursor_pointer" title="Waiting for Approval - <? echo $wapp; ?>" onclick="location.href='<? if($_SESSION['tcs_usrcode'] == '9938358' or $_SESSION['tcs_usrcode'] == '9193333' or $_SESSION['tcs_usrcode'] == '3000000' or $_SESSION['tcs_usrcode'] == '7292222' or $_SESSION['tcs_usrcode'] == '4003579') { ?><?=$link_name?><? } else { ?><?=$link_name?><? } ?>';">
                            <div class="widget-item-left">
                                <span class="fa fa-list-alt"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-int num-count"><? echo $wapp; ?></div>
                                <div class="widget-title">Waiting for Approval</div>
                            </div>
                            <div class="widget-controls">
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>
                        </div>
                        <!-- END WIDGET MESSAGES -->
                    </div>
                    <!-- Waiting for Approval! -->

                    <!-- Waiting with Query! -->
                    <div class="col-md-4">
                        <!-- START WIDGET REGISTRED -->
                        <div class="widget widget-info widget-item-icon cursor_pointer" title="New Requests - <? echo $nwrq; ?>" onclick="location.href='request_list.php';">
                            <div class="widget-item-left">
                                <span class="fa fa-edit"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-int num-count"><? echo $nwrq; ?></div>
                                <div class="widget-title">New Requests</div>
                            </div>
                            <div class="widget-controls">
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>
                        </div>
                        <!-- END WIDGET REGISTRED -->
                    </div>
                    <!-- Waiting with Query! --> */ ?>




                <? /* <div class="row">
                <div class="container-fluid">
                    <div class="animated fadeIn">
                    <div class="row row-equal">
                        <div class="col-sm-6 col-lg-4" title="Waiting with Query - <? echo $wwqr; ?>">
                            <div class="card card-inverse card-danger">
                                <div class="card-block p-b-0">
                                    <div class="btn-group pull-right">
                                    </div>
                                    <h4 class="m-b-0" style="color: #FFF; font-size: 20px;"><? echo $wwqr; ?></h4>
                                    <p style="color: #FFF; font-size: 14px; text-transform: uppercase;">Waiting with Query</p>
                                </div>
                                <div class="chart-wrapper p-x-1">
                                    <div style="text-align: right;">
                                        <span class="sparkline" sparkFillColor="#FFF" sparkLineWidth="2" sparkLineColor="#a44af1" sparkWidth="200" sparkHeight="50" >15,4,3,82,49,5,6,7,48,6,4,88</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/col-->
                        <div class="col-sm-6 col-lg-4" title="Waiting for Approval - <? echo $wapp; ?>">
                            <div class="card card-inverse card-success">
                                <div class="card-block p-b-0">
                                    <div class="btn-group pull-right">
                                    </div>
                                    <h4 class="m-b-0" style="color: #FFF; font-size: 20px;"><? echo $wapp; ?></h4>
                                    <p style="color: #FFF; font-size: 14px; text-transform: uppercase;">Waiting for Approval</p>
                                </div>
                                <div class="chart-wrapper">
                                    <div class="chart-wrapper">
                                        <div style="text-align: right;">
                                            <span class="sparkline" sparkType="bar" sparkBarColor="#a44af1" sparkWidth="200" sparkHeight="50" sparkBarWidth="10">5,4,3,2,4,5,6,7,8,6,4,5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/col-->
                        <div class="col-sm-6 col-lg-4" title="New Requests - <? echo $nwrq; ?>">
                            <div class="card card-inverse card-primary">
                                <div class="card-block p-b-0">
                                    <div class="btn-group pull-right">
                                    </div>
                                    <h4 class="m-b-0" style="color: #FFF; font-size: 20px;"><? echo $nwrq; ?></h4>
                                    <p style="color: #FFF; font-size: 14px; text-transform: uppercase;">New Requests</p>
                                </div>
                                <div class="chart-wrapper p-x-1">
                                    <div style="text-align: right;">
                                        <span class="sparkline" sparkType="pie" sparkWidth="200" sparkHeight="50" >5,44,13,25</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/col-->
                    </div>
                </div>
                </div>
                </div> */ ?>
              
                <div>
                 
                    <div class="res_das">
                        <? if($_SESSION['auditor_login'] == 0) { ?>
                        <div class="col-md-4 cursor_pointer" title="Waiting with Query - <? echo $wwqr; ?>" onclick="location.href='waiting_query.php';">
                            <div class="dash-box dash-box-color-1">
                                <div class="dash-box-icon">
                                    <i class="fa fa-question-circle"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $wwqr; ?></span>
                                    <span class="dash-box-title">Waiting with Query</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='waiting_query.php';">More Info</button>
                                </div>
                            </div>
                        </div>
                        <? } ?>

                        <div class="col-md-4 cursor_pointer" title="Waiting for Approval - <? echo $wapp; ?>" onclick="location.href='<? if($_SESSION['tcs_usrcode'] == '9938358' or $_SESSION['tcs_usrcode'] == '9193333' or $_SESSION['tcs_usrcode'] == '3000000' or $_SESSION['tcs_usrcode'] == '7292222' or $_SESSION['tcs_usrcode'] == '4003579') { ?><?=$link_name?>?status=waiting<? } else { ?><?=$link_name?>?status=waiting<? } ?>';">
                            <div class="dash-box dash-box-color-2">
                                <div class="dash-box-icon">
                                    <i class="fa fa-list-alt"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $wapp; ?></span>
                                    <span class="dash-box-title">Waiting for Approval</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='<? if($_SESSION['tcs_usrcode'] == '9938358' or $_SESSION['tcs_usrcode'] == '9193333' or $_SESSION['tcs_usrcode'] == '3000000' or $_SESSION['tcs_usrcode'] == '7292222' or $_SESSION['tcs_usrcode'] == '4003579') { ?><?=$link_name?>?status=waiting<? } else { ?><?=$link_name?>?status=waiting<? } ?>';">More Info</button>
                                </div>
                            </div>
                        </div>
                        <div style='display:none;'></div>

                        <div class="col-md-4 cursor_pointer" title="New Requests - <? echo $nwrq; ?>" onclick="location.href='request_list.php';">
                            <div class="dash-box dash-box-color-3">
                                <div class="dash-box-icon">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $nwrq; ?></span>
                                    <span class="dash-box-title">New Requests</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='request_list.php';">More Info</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 cursor_pointer" title="Sending With Query - <? echo $snqr; ?>" onclick="location.href='http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Internal Verification Approvals';">
                            <div class="dash-box dash-box-color-8">
                                <div class="dash-box-icon">
                                    <i class="fa fa-check-square-o"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $snqr; ?></span>
                                    <span class="dash-box-title">Sending With Query</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='http://www.tcsportal.com/approval-desk/approved_approvals_list.php#/Internal Verification Approvals';">More Info</button>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>

                  <div>
                    <div class="res_das">
                        <div class="col-md-4 cursor_pointer" title="Alternate User Requests - <? echo $nwal; ?>" onclick="location.href='<?=$link_name?>?status=alternate';">
                            <div class="dash-box dash-box-color-4">
                                <div class="dash-box-icon">
                                    <i class="fa fa-thumbs-up"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $nwal; ?></span>
                                    <span class="dash-box-title">Alternate User Approvals</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='<?=$link_name?>?status=alternate';">More Info</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 cursor_pointer" title="Post Audit Approvals - <? echo $poad; ?>" onclick="location.href='<?=$link_name?>?status=post_audit';">
                            <div class="dash-box dash-box-color-6">
                                <div class="dash-box-icon">
                                    <i class="fa fa-check-circle-o"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $poad; ?></span>
                                    <span class="dash-box-title">Post Audit Approvals</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" onclick="location.href='<?=$link_name?>?status=post_audit';">More Info</button>
                                </div>
                            </div>
                        </div>
                        <div style='display:none;'></div>

                    <!-- Start template Design -->
                        <div class="col-md-4 cursor_pointer" title="Acknowledge Approvals - <? echo $akap; ?>" onclick="location.href='acknowledge_approvals.php';">
                          <div class="dash-box dash-box-color-7">
                              <div class="dash-box-icon">
                                  <i class="fa fa-user"></i>
                              </div>
                              <div class="dash-box-body">
                                  <span class="dash-box-count"><? echo $akap; ?></span>
                                  <span class="dash-box-title">Acknowledge Approvals</span>
                              </div>
                              <div class="dash-box-action">
                                  <button type="button" onclick="location.href='acknowledge_approvals.php';">More Info</button>
                              </div>
                          </div>
                      </div>
                      <!-- End template Design -->

                    <? // if($nwbd > 0) { ?>
                        <div class="col-md-4 cursor_pointer" <? if($nwbd > 0 and $_SESSION['tcs_esecode'] == 137) { ?> onclick="popup_biddisplay()" <? } else { ?> onclick="location.href='<?=$link_name?>?status=bid';" <? } ?>>
                            <div class="dash-box dash-box-color-5">
                                <div class="dash-box-icon">
                                    <i class="fa fa-gavel"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><? echo $nwbd; ?></span>
                                    <span class="dash-box-title">Waiting in BID</span>
                                </div>

                                <div class="dash-box-action">
                                    <button type="button" <? if($nwbd > 0 and $_SESSION['tcs_esecode'] == 137) { ?> onclick="popup_biddisplay()" <? } else { ?> onclick="location.href='<?=$link_name?>?status=bid';" <? } ?>>More Info</button>
                                </div>
                            </div>
                        </div>
                    <? // } ?>
                  </div>
                </div>
                </div>
                </form>
                <!-- END WIDGETS -->
                <? // } ?>




                <div class="row" style="display: none">
                    <div class="col-md-6">

                        <!-- START SIMPLE LINE CHART -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Simple Line Chart</h3>
                            </div>
                            <div class="panel-body">
                                <div id="chart-1" style="height: 300px;"><svg></svg></div>
                            </div>
                        </div>
                        <!-- END SIMPLE LINE CHART -->

                    </div>
                    <div class="col-md-6">

                        <!-- START DISCRETE CHART -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Discrete Bar Chart</h3>
                            </div>
                            <div class="panel-body">
                                <div id="chart-4" style="height: 300px;"><svg></svg></div>
                            </div>
                        </div>
                        <!-- END DISCRETE CHART -->

                    </div>
                </div>

                <div class="row" style="display: none">
                    <? /* <div class="col-md-6">

                        <!-- START REGULAR PIE CHART -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Regular pie chart</h3>

                            </div>
                            <div class="panel-body">
                                <div id="chart-9" style="height: 300px;"><svg></svg></div>
                            </div>
                        </div>
                        <!-- END REGULAR PIE CHART -->

                    </div>
                    <div class="col-md-6">

                        <!-- START DOUNT CHART -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Donut chart</h3>
                            </div>
                            <div class="panel-body">
                                <div id="chart-10" style="height: 300px;"><svg></svg></div>
                            </div>
                        </div>
                        <!-- END DOUNT CHART -->

                    </div> */ ?>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>

    <? /* <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS --> */ ?>

    <!-- Send Email -->
    <div id="myModal1" class="modal fade">
        <div class="modal-dialog" style='width: 350px;'>
            <div class="modal-content">
                <div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>WAITING IN BID</div>
                <div class="modal-body" id="modal-body1" style="padding: 10px 0px 10px 0px;"></div>
            </div>
        </div>
    </div>
    <!-- Send Email -->

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

    <? /* <script type="text/javascript" src="js/plugins/morris/raphael-min.js"></script>
    <script type="text/javascript" src="js/plugins/morris/morris.min.js"></script> */ ?>
    <script type="text/javascript" src="js/plugins/rickshaw/d3.v3.js"></script>
    <script type="text/javascript" src="js/plugins/rickshaw/rickshaw.min.js"></script>
    <script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
    <script type='text/javascript' src='js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap-datepicker.js'></script>
    <script type="text/javascript" src="js/plugins/owl/owl.carousel.min.js"></script>

    <script type="text/javascript" src="js/plugins/moment.min.js"></script>
    <script type="text/javascript" src="js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- END THIS PAGE PLUGINS-->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>

    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>

    <script type="text/javascript" src="js/demo_dashboard.js"></script>
    <script type="text/javascript" src="js/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="js/plugins/nvd3/lib/d3.v3.js"></script>
    <script type="text/javascript" src="js/plugins/nvd3/nv.d3.min.js"></script>
    <script type="text/javascript" src="js/demo_charts_nvd3.js"></script>
    <? /* <script src="js/Chart.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/widgets.js"></script> */ ?>
    <!-- END TEMPLATE -->
    <script type="text/javascript">
        function popup_biddisplay() {
            $('#load_page').show();
            var sendurl = "ajax_bid.php";
            $.ajax({
                url:sendurl,
                success:function(data){
                    $("#myModal1").modal('show');
                    $('#load_page').hide();
                    document.getElementById('modal-body1').innerHTML = data;
                    $('#load_page').hide();
                    $('.lightgallery').lightGallery();
                }
            });
        }
    </script>
<!-- END SCRIPTS -->
</body>
</html>
