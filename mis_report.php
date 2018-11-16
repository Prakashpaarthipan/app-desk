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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
<title>Mis Report :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />

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
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
</head>
<body>
<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="call_center_summary1.php" method="post" enctype="multipart/form-data">
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> process_requirement_view.php*/ ?>
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
                <li class="active">Mis Report</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Mis Report</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="row"> 
                                 <a style="float: right;" href="download_files1.php?f=mis_report.csv" target="_blank" class="btn btn-success">
<span class="fa fa-download"></span></a></h3>
                            </div>
                            <!-- START TABS -->                                
                            <div class="panel panel-default tabs">                            
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active"><a role="tab" href="#tab-first" data-toggle="tab">Summary</a></li>
                                    <li><a role="tab" href="#tab-second" data-toggle="tab">S-Team</a></li>
                                    <li><a role="tab" href="#tab-third" data-toggle="tab">Management</a></li>
                                    <li><a role="tab" href="#tab-fourth" data-toggle="tab">Operation Team</a></li>
                                    <li><a role="tab" href="#tab-fifth" data-toggle="tab">Admin</a></li>
                                </ul>                            
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab-first">
                                        <table  class="table datatable table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Top Core</th>
                                                     <th class="center" style='text-align:center'>Sub core</th>
                                                    <th class="center" style='text-align:center'>Agreement</th>
                                                    <th class="center" style='text-align:center'>Fixed Budget</th>
                                                    <th class="center" style='text-align:center'>Implementation</th>
                                                    <th class="center" style='text-align:center'>Internal Request</th>
                                                    <th class="center" style='text-align:center'>Policy</th>
                                                    <th class="center" style='text-align:center'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";



                                            $sql_search1 = select_query_json("select MAS.TOPCORE,ATC.ATCNAME,decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT,sum(decode(atycode,'1',1,0)) FIXED_BUDGET,sum(decode(atycode,'2',1,0)) IMPLEMENTATION,sum(decode(atycode,'3',1,0)) POLICY,sum(decode(atycode,'4',1,0)) INTERNAL_REQUEST,sum(decode(atycode,'8',1,0)) AGREEMENT,count(APMCODE) Total from trandata.approval_master@tcscentr mas,trandata.approval_topcore@tcscentr atc,TRANDATA.EMPSECTION@TCSCENTR SUB where mas.deleted='N' and atc.deleted='N' and atc.atccode=mas.topcore AND SUB.ESECODE(+)=MAS.SUBCORE group by topcore,ATC.ATCNAME,ESENAME,subcore order by topcore,subcore", "Centra", 'TCS');
                                            $arr=array();
                                            foreach ($sql_search1 as $key => $value) 
                                            {   $temp=count($arr[$value['TOPCORE']]);
                                                $arr[$value['TOPCORE']][$temp]=$value;    
                                            }
                                            //echo('<pre>');
                                            //print_r($arr);
                                            //echo('</pre>');
                                            $ki = 0;
                                            $count_sql=count($sql_search1);
                                            for($i=0;$i<$count_sql;$i++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:right;'>
                                                        <? echo $i+1; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left;'><!-- for entryno-->
                                                        <? echo $sql_search1[$i]['ATCNAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for entryno-->
                                                        <? echo $sql_search1[$i]['DEPT']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['AGREEMENT']); ?> 
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['FIXED_BUDGET']); ?> 
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['IMPLEMENTATION']); ?> 
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['INTERNAL_REQUEST']); ?> 
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['POLICY']); ?> 
                                                    </td>
                                                    <td class="center" style='text-align:right'><!-- for top core-->
                                                        <? echo ($sql_search1[$i]['TOTAL']); ?> 
                                                    </td>
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane " id="tab-second">
                                        <table  class="table datatable table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Core</th>
                                                     <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";

                                            $sql_search = select_query_json("select atcname,sub.esename,ATYNAME,APMNAME from trandata.approval_topcore@tcscentr atc, trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ,trandata.empsection@tcscentr sub where atc.atccode=mas.topcore and mas.deleted='N' and atc.atccode=1 and typ.atycode=mas.atycode and sub.esecode(+)=mas.subcore and sub.deleted(+)='N'", "Centra", 'TCS');
                                           
                                            $ki = 0;
                                            $ki_size=sizeof($sql_search);
                                            for($k=0;$k<$ki_size;$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:right;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ESENAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ATYNAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for top core-->
                                                        <? echo ($sql_search[$k]['APMNAME']); ?> 
                                                    </td>
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane " id="tab-third">
                                       
                                        <table  class="table datatable table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Core</th>
                                                     <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";



                                            $sql_search = select_query_json("select atcname,sub.esename,ATYNAME,APMNAME from trandata.approval_topcore@tcscentr atc, trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ,trandata.empsection@tcscentr sub where atc.atccode=mas.topcore and mas.deleted='N' and atc.atccode=2 and typ.atycode=mas.atycode and sub.esecode(+)=mas.subcore and sub.deleted(+)='N'", "Centra", 'TCS');
                                           
                                            $ki = 0;
                                            $ki_size=sizeof($sql_search);
                                            for($k=0;$k<$ki_size;$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:right;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ESENAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ATYNAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for top core-->
                                                        <? echo ($sql_search[$k]['APMNAME']); ?> 
                                                    </td>
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane " id="tab-fourth">
                                       
                                        <table  class="table datatable table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Core</th>
                                                     <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";



                                            $sql_search = select_query_json("select atcname,sub.esename,ATYNAME,APMNAME from trandata.approval_topcore@tcscentr atc, trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ,trandata.empsection@tcscentr sub where atc.atccode=mas.topcore and mas.deleted='N' and atc.atccode=3 and typ.atycode=mas.atycode and sub.esecode(+)=mas.subcore and sub.deleted(+)='N'", "Centra", 'TCS');
                                           
                                            $ki = 0;
                                            $ki_size=sizeof($sql_search);
                                            for($k=0;$k<$ki_size;$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:right;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ESENAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ATYNAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for top core-->
                                                        <? echo ($sql_search[$k]['APMNAME']); ?> 
                                                    </td>
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane " id="tab-fifth">
                                        
                                        <table  class="table datatable table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Core</th>
                                                     <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";



                                            $sql_search = select_query_json("select atcname,sub.esename,ATYNAME,APMNAME from trandata.approval_topcore@tcscentr atc, trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ,trandata.empsection@tcscentr sub where atc.atccode=mas.topcore and mas.deleted='N' and  atc.atccode=4 and typ.atycode=mas.atycode and sub.esecode(+)=mas.subcore and sub.deleted(+)='N'", "Centra", 'TCS');
                                           
                                            $ki = 0;
                                            $ki_size=sizeof($sql_search);
                                            for($k=0;$k<$ki_size;$k++){//echo $sql_search[$k]['ENTSTAT'];
                                                 $ki++; ?>
                                                  <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:right;'>
                                                        <? echo $ki; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left;'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ESENAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for entryno-->
                                                        <? echo $sql_search[$k]['ATYNAME']; // SERIAL NUMBER OF THE RECORD ?>
                                                    </td>
                                                    <td class="center" style='text-align:left'><!-- for top core-->
                                                        <? echo ($sql_search[$k]['APMNAME']); ?> 
                                                    </td>
                                                </tr>
                                                <? 
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>                                                   
                            <!-- END TABS -->                        
                        </div>
                       
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>

                        
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
</form>
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

    <script type="text/javascript" src="js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/tableExport.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jquery.base64.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/html2canvas.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/jspdf.js"></script>
    <script type="text/javascript" src="js/plugins/tableexport/jspdf/libs/base64.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
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
    <script>
        
        $('#datepicker-example4').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example3')
    });
    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example3')
    });

    
    </script>
   
<!-- END SCRIPTS -->
</body>
</html>
