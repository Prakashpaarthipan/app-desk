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
<title>Request List :: Approval Desk :: <?php echo $site_title; ?></title>
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
    .inp_txt{
        cursor:pointer; 
        text-transform:uppercase;
        display: inline-block;
        margin-top: 10px;
        vertical-align: center;
    }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
    .table-row
    {

    }
    .table-row-cur
    {
        background-color: rgba(224, 75, 74, 1);
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
<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="call_center_summary.php" method="post" enctype="multipart/form-data">
     <div id="load_page" style='display:block;padding:12% 40%;'></div> 
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
                <li class="active">Call Center Summary</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Call Center Summary</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                           <center>
                            <!-- <div class="col-lg-3 col-sm-6" style='text-align:center; padding:5px 5px 0 6px;display: inline-block;'> -->
                                        <input type='hidden'   name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                        <input type='text' style="height:28px;" type="text" class="inp_txt" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                        
                                        
                            <!-- </div> -->
                            <!-- <div class="col-lg-3 col-sm-6" style='text-align:center; padding:5px 5px 0 6px;display: inline-block;'> -->
                                    <input style="height:28px;" type='text' class="inp_txt" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date" >
                            <!-- </div> -->
                            <!-- <div class="col-lg-2 col-sm-3" style='padding:5px;'> -->
                                <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-success' value='SEARCH' title='SEARCH' style="display: inline-block;margin-top: 0px;vertical-align: center;">
                     
                            <!-- </div> -->
                            </center> 
                    <div class="panel-body">
                        <div class="col-md-12">
                            <!-- START TABS -->                                
                            <div class="panel panel-default tabs">                            
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active"><a role="tab" href="#tab-first" data-toggle="tab">Response Summary</a></li>
                                    <li><a role="tab" href="#tab-second" data-toggle="tab">Request Summary</a></li>
                                </ul>                            
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab-first">
                                        <div class="row">
                                            <div class="col-lg-2 col-sm-2" style='margin-bottom: 10px; float: right;'>
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

                                            $sql_search = select_query_json("select resfusr,count(reqnumb) CLOSED,eof.empname name,eof.empcode,
                                                                            (select count(reqnumb) from trandata.service_response@tcscentr where resstat='A' and resfusr = ser.resfusr) ASSGND, 
                                                                            (select count(reqnumb) from trandata.service_response@tcscentr where resstat='N' and resfusr = ser.resfusr) NOT_ASGND
                                                                            from trandata.service_response@tcscentr ser,trandata.employee_office@tcscentr eof where eof.empsrno=ser.resfusr and resstat='C' and ".$and." group by resfusr,empname,empcode", "Centra", 'TCS');?>

                                            
                                            </div>
                                            
                                        
                                        <?$search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(reqdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";

                                        $count_search = select_query_json("select to_char(reqnumb) REQNUMB,SER.REQSRNO,ser.adduser,reqmsg,to_char(reqdate,'dd-MM-yyyy HH:mi:ss AM') reqdate,reqstat,sup.supname supplier,requsrtyp user_typ,eof.empcode||'-'||eof.empname close_user,to_char(RESLVDATE,'dd-MM-yyyy HH:mi:ss AM') resdate,comname rmode 
                                            from trandata.service_request@tcscentr ser,trandata.APP_COMPLAINT_MASTER@tcscentr apc,trandata.employee_office@tcscentr eof,trandata.supplier@tcscentr sup 
                                            where eof.empsrno(+)=ser.RESLVUSER and ser.reqmode=apc.comcode and sup.supcode=ser.REQUSER and apc.deleted = 'N' and ".$and." 
                                            order by reqnumb desc", "Centra", 'TCS');
                                        //echo("<pre style='text-align:left;'>");
                                       // print_r($count_search);

                                            ?>
                                        <h3 class="panel-heading panel-title" style="margin-bottom: 20px;"><strong>Response Summary</strong>
                                        <a style="float: right;" href="download_files1.php?f=service_request_summary_1.csv&search_fromdate=<?=$search_fromdate;?>&search_todate=<?=$search_todate;?>" target="_blank" class="btn btn-success">
<span class="fa fa-download"></span></a></h3>
                                        
                                            
                                            <div style="">
                                        <table class="table datatable table-striped" style="margin: 10px 20px;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">S.No.</th>
                                                    <th style="text-align: center;">Mode</th>
                                                    <!-- <th style="text-align: center;">Type</th> -->
                                                    <th style="text-align: center;">Id</th>
                                                    <th style="text-align: center;">Queryt Request Person</th>
                                                    <th style="text-align: center;">Query Request List</th>
                                                    <th style="text-align: center;">Query Request Date</th>
                                                    <th style="text-align: center;">Diff. Days</th>
                                                    <th style="text-align: center;">Query Response</th>
                                                    <th style="text-align: center;">Query Response User</th>
                                                    <th style="text-align: center;">Response Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?for($i=0;$i<count($count_search);$i++){?>
                                                    <?$res_msg = select_query_json("select RESMSG from trandata.service_response@tcscentr where reqnumb='".$count_search[$i]['REQNUMB']."' and reqsrno='".$count_search[$i]['REQSRNO']."' and resstat='C'", "Centra", 'TCS');
                                                    //echo("<pre>");
                                                    //print_r($res_msg);
                                                    ?>
                                                    <?$name='';$bgr='';
                                                    if($count_search[$i]['USER_TYP']=='S'){
                                                            $name=$count_search[$i]['SUPPLIER'];
                                                            $bgr='';
                                                        }else{
                                                            $emp_name = select_query_json("select empcode||'-'||empname name from trandata.userid@tcscentr usr,trandata.employee_office@tcscentr eof where usr.usrcode='".$count_search[$i]['ADDUSER']."' and eof.empsrno=usr.empsrno", "Centra", 'TCS');
                                                           $name=$emp_name[0]['NAME'];
                                                           $bgr='#e7ebff';
                                                        }?>
                                                <tr >
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$i+1;?>
                                                    </td>
                                                    <!-- <td style="background: <?=$bgr;?> !important;">
                                                        <?=$count_search[$i]['USER_TYP']."-".$bgr?>
                                                    </td> -->
                                                    <td style="background: <?=$bgr;?> !important;" >
                                                        <?=$count_search[$i]['RMODE']?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$count_search[$i]['REQNUMB']?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$name;?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;" data-toggle="tooltip" data-placement="right" title="<?=$count_search[$i]['REQMSG']?>">
                                                        <?=substr($count_search[$i]['REQMSG'],0,10)."...";?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$count_search[$i]['REQDATE']?>
                                                    </td >
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?
                                                            $date1=date_create($count_search[$i]['REQDATE']);
                                                            $date2=date_create($count_search[$i]['RESDATE']);
                                                            $diff=date_diff($date1,$date2);
                                                            echo($diff->d);
                                                        ?> 
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;" data-toggle="tooltip" data-placement="right" title="<?$temp=count($res_msg)-1;
                                                        echo($res_msg[$temp]['RESMSG']);?>">
                                                        <?$temp=count($res_msg)-1;
                                                        echo(substr($res_msg[$temp]['RESMSG'],0,10)."...");?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$count_search[$i]['CLOSE_USER']?>
                                                    </td>
                                                    <td style="background: <?=$bgr;?> !important;">
                                                        <?=$count_search[$i]['RESDATE']?>
                                                    </td>
                                                </tr>
                                                <?}?>
                                            </tbody>
                                        </table>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab-second" style="text-align: center;">
                                        <center>
                                            <h3 class="panel-heading panel-title" style="margin-bottom: 20px;"><strong>Call Summary</strong><a style="float: right;" href="download_files1.php?f=service_request_summary_overall.csv&search_fromdate=<?=$search_fromdate;?>&search_todate=<?=$search_todate;?>" target="_blank" class="btn btn-success">
<span class="fa fa-download"></span></a></h3>
                                            

                                         <?$search_fromdate=$_REQUEST['search_fromdate'];
                                            if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_fromdate);
                                            $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

                                            $search_rodate=$_REQUEST['search_todate'];
                                            if($search_todate == '') { $search_todate = date("d-M-Y"); }
                                            $exp1 = explode("-", $search_todate);
                                            $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
                                            $and = " trunc(reqdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";

                                         $main_count = select_query_json("select (select count(reqnumb) from trandata.service_request@tcscentr where requsrtyp='S' and ".$and." ) call,(select count(reqnumb) from trandata.service_request@tcscentr where requsrtyp='S' and ".$and." and reqstat='C') closed ,'supplier' type from dual union select (select count(reqnumb) from trandata.service_request@tcscentr where requsrtyp='E' and ".$and." ) call,(select count(reqnumb) from trandata.service_request@tcscentr where requsrtyp='E' and ".$and." and reqstat='C') closed,'employee' type from dual order by type", "Centra", 'TCS');
                                         //echo("");
                                        ?>
                                        <table class="table table-striped" style="width: 40%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">User Type</th>
                                                    <th style="text-align: center;">No. of calls</th>
                                                    <th style="text-align: center;">NO. of closed</th>
                                                    <th style="text-align: center;">Pending</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: center;">Supplier</td>
                                                    <td style="text-align: center;"><?=$main_count[1]['CALL']?></td>
                                                    <td style="text-align: center;"><?=$main_count[1]['CLOSED']?></td>
                                                    <td style="text-align: center;"><?=(intval($main_count[1]['CALL'])-(intval($main_count[1]['CLOSED'])))?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center;">Employee</td>
                                                    <td style="text-align: center;"><?=$main_count[0]['CALL']?></td>
                                                    <td style="text-align: center;"><?=$main_count[0]['CLOSED']?></td>
                                                    <td style="text-align: center;"><?=(intval($main_count[0]['CALL'])-(intval($main_count[0]['CLOSED'])))?></td>
                                                </tr>
                                            </tbody>
                                        </table>







                                        <?/* <?$count_search = select_query_json("select to_char(reqnumb) REQNUMB,SER.REQSRNO,ser.adduser,reqmsg,to_char(reqdate,'dd-MM-yyyy HH:mi:ss AM') reqdate,reqstat,sup.supname supplier,requsrtyp user_typ,eof.empcode||'-'||eof.empname close_user,to_char(RESLVDATE,'dd-MM-yyyy HH:mi:ss AM') resdate,comname rmode 
                                            from trandata.service_request@tcscentr ser,trandata.APP_COMPLAINT_MASTER@tcscentr apc,trandata.employee_office@tcscentr eof,trandata.supplier@tcscentr sup 
                                            where eof.empsrno(+)=ser.RESLVUSER and ser.reqmode=apc.comcode and sup.supcode=ser.REQUSER and apc.deleted = 'N' and ".$and." 
                                            order by reqnumb desc", "Centra", 'TCS');
                                        //echo("<pre style='text-align:left;'>");
                                       // print_r($count_search);

                                            ?>
                                        <h3 class="panel-heading panel-title" style="margin-bottom: 20px;"><strong>Request Summary</strong></h3>
                                        
                                            <a style="float: right;" href="download_files1.php?f=service_request_summary_1.csv&search_fromdate=<?=$search_fromdate;?>&search_todate=<?=$search_todate;?>" target="_blank" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Download</a>
                                            <div style="">
                                        <table class="table datatable table-striped" style="margin: 10px 20px;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">S.No.</th>
                                                    <th style="text-align: center;">Mode</th>
                                                    <th style="text-align: center;">Id</th>
                                                    <th style="text-align: center;">Queryt Request Person</th>
                                                    <th style="text-align: center;">Query Request List</th>
                                                    <th style="text-align: center;">Query Request Date</th>
                                                    <th style="text-align: center;">Diff. Days</th>
                                                    <th style="text-align: center;">Query Response</th>
                                                    <th style="text-align: center;">Query Response User</th>
                                                    <th style="text-align: center;">Response Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?for($i=0;$i<count($count_search);$i++){?>
                                                    <?$res_msg = select_query_json("select RESMSG from trandata.service_response@tcscentr where reqnumb='".$count_search[$i]['REQNUMB']."' and reqsrno='".$count_search[$i]['REQSRNO']."'", "Centra", 'TCS');
                                                    //echo("<pre>");
                                                    //print_r($res_msg);
                                                    ?>
                                                    <?$name='';$bgr='';
                                                    if($count_search[$i]['USER_TYP']=='S'){
                                                            $name=$count_search[$i]['SUPPLIER'];
                                                            $bgr='';
                                                        }else{
                                                            $emp_name = select_query_json("select empcode||'-'||empname name from trandata.userid@tcscentr usr,trandata.employee_office@tcscentr eof where usr.usrcode='".$count_search[$i]['ADDUSER']."' and eof.empsrno=usr.empsrno", "Centra", 'TCS');
                                                           $name=$emp_name[0]['NAME'];
                                                           $bgr='rgba(254, 153, 11, 0.55)';
                                                        }?>
                                                <tr style="background: <?=$bgr;?>">
                                                    <td>
                                                        <?=$i+1;?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['RMODE']?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['REQNUMB']?>
                                                    </td>
                                                    <td>
                                                        <?=$name;?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['REQMSG']?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['REQDATE']?>
                                                    </td>
                                                    <td>
                                                        <?
                                                            $date1=date_create($count_search[$i]['REQDATE']);
                                                            $date2=date_create($count_search[$i]['RESDATE']);
                                                            $diff=date_diff($date1,$date2);
                                                            echo($diff->d);
                                                        ?> 
                                                    </td>
                                                    <td>
                                                        <?$temp=count($res_msg)-1;
                                                        echo($res_msg[$temp]['RESMSG']);?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['CLOSE_USER']?>
                                                    </td>
                                                    <td>
                                                        <?=$count_search[$i]['RESDATE']?>
                                                    </td>
                                                </tr>
                                                <?}?>
                                            </tbody>
                                        </table>
                                        </div> -->*/?>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="panel-heading panel-title" style="margin-bottom: 20px;"><strong>Call Closed Summary</strong><a style="float: right;" href="download_files1.php?f=service_request_summary_closed.csv&search_fromdate=<?=$search_fromdate;?>&search_todate=<?=$search_todate;?>" target="_blank" class="btn btn-success">
<span class="fa fa-download"></span></a></h3>
                                                 
                                                <?$count_search = select_query_json("select empname,empsrno,EMPCODE,sum(decode(ser.requsrtyp,'S',1,0)) no_of_sup_CALL,sum(decode(ser.requsrtyp,'E',1,0)) no_of_emp_Closed from trandata.service_request@tcscentr ser,trandata.employee_office@tcscentr usr where usr.empsrno=ser.RESLVUSER and ".$and." and reqstat='C'  group by reslvuser,empname,EMPCODE,empsrno order by empcode", "Centra", 'TCS');
                                                //echo("select empname,EMPCODE,sum(decode(ser.requsrtyp,'S',1,0)) no_of_sup_CALL,sum(decode(ser.requsrtyp,'E',1,0)) no_of_emp_Closed from trandata.service_request@tcscentr ser,trandata.employee_office@tcscentr usr where usr.empsrno=ser.RESLVUSER and ".$and." and reqstat='C'  group by reslvuser,empname,EMPCODE,empsrno order by empcode\n");
                                                $enter_search = select_query_json("select USRNAME,EOF.EMPSRNO,eof.empcode,COUNT(reqnumb) USRREQ from trandata.service_request@tcscentr SER,TRANDATA.USERID@TCSCENTR USR,trandata.employee_office@tcscentr eof where SER.ADDUSER=USR.USRCODE AND ".$and." and SER.requsrtyp='E' and usr.empsrno=eof.empsrno GROUP BY USRNAME,eof.empcode,EOF.EMPSRNO order by usrreq desc", "Centra", 'TCS');
                                               // echo("select USRNAME,empsrno,COUNT(reqnumb) USRREQ from trandata.service_request@tcscentr SER,TRANDATA.USERID@TCSCENTR USR where SER.ADDUSER=USR.USRCODE AND ".$and." and SER.requsrtyp='E' GROUP BY USRNAME,empsrno order by usrreq desc");
                                                $arr_emp=array();
                                                foreach($enter_search as $key => $value)
                                                {
                                                    $arr_emp[$value['EMPSRNO']]=$value;
                                                }
                                                $check=array();
                                                foreach ($count_search as $key => $value) 
                                                {
                                                    $check[$value['EMPSRNO']]=1;
                                                }
                                               // echo('<pre>');
                                                //print_r($arr_emp);
                                                //echo("select USRNAME,empsrno,COUNT(reqnumb) USRREQ from trandata.service_request@tcscentr SER,TRANDATA.USERID@TCSCENTR USR where SER.ADDUSER=USR.USRCODE AND ".$and." and SER.requsrtyp='E' GROUP BY USRNAME,empsrno order by usrreq desc ");
                                                
                                                ?>
                                                <table class="table datatable table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center;">Sr.No.</th>
                                                            <th style="text-align: center;">Employee name</th>
                                                            <th style="text-align: center;">Calls Entered</th>
                                                            <th style="text-align: center;">Supplier Call Closed</th>
                                                            <th style="text-align: center;">Employee Call Closed</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?for($i=0;$i<count($count_search);$i++){?>
                                                            <tr class="table-row">
                                                                <td><?=$i+1;?></td>
                                                                <td><?=$count_search[$i]['EMPCODE'].' - '.$count_search[$i]['EMPNAME']?></td>
                                                                <td style="text-align: center;"><?=$arr_emp[$count_search[$i]['EMPSRNO']]['USRREQ'];?></td>
                                                                <td style="text-align: center;"><?=$count_search[$i]['NO_OF_SUP_CALL']?></td>
                                                                <td style="text-align: center;"><?=$count_search[$i]['NO_OF_EMP_CLOSED']?></td>
                                                            </tr>
                                                        <?}?>
                                                        <?if(count($arr_emp)!=count($check))
                                                        { foreach ($arr_emp as $key => $value) 
                                                            {
                                                                if($check[$value['EMPSRNO']]!=1)
                                                                { $i++; 
                                                                    ?>
                                                                    <tr class="table-row">
                                                                        <td><?=$i;?></td>
                                                                        <td><?=$value['USRNAME'].' - '.$value['EMPCODE']; ?></td>
                                                                        <td style="text-align: center;"><?=$value['USRREQ']; ?></td>
                                                                        <td style="text-align: center;">0</td>
                                                                        <td style="text-align: center;">0</td>
                                                                    </tr>
                                                                <? }
                                                            }

                                                        }?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?/*<!-- <div class="col-md-6">
                                                <h3 class="panel-heading panel-title" style="margin-bottom: 20px;"><strong>Employee Call Closed (<?=$main_count[0]['CLOSED']?> Calls)</strong></h3>
                                                <?$count_search = select_query_json("select count(*) call,empname,EMPCODE from trandata.service_request@tcscentr ser,trandata.employee_office@tcscentr usr where usr.empsrno=ser.RESLVUSER and ".$and." and reqstat='C' and requsrtyp='E' group by reslvuser,empname,EMPCODE", "Centra", 'TCS');
                                                //echo("select count(*) call,empname,EMPCODE from trandata.service_request@tcscentr ser,trandata.employee_office@tcscentr usr where usr.empsrno=ser.RESLVUSER and ".$and." and reqstat='C' and requsrtyp='E' group by reslvuser,empname,EMPCODE");
                                                ?>
                                                <table class="table table-striped" style="width: 80%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center;">Employee name</th>
                                                            <th style="text-align: center;">No. of calls closed </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?if(count($count_search)==0){?>
                                                           <td class="dataTables_empty" valign="top" colspan="7">No data available in table</td>
                                                        <?}?>
                                                        <?for($i=0;$i<count($count_search);$i++){?>
                                                            <tr>
                                                                <td><?=$count_search[$i]['EMPCODE'].' - '.$count_search[$i]['EMPNAME']?></td>
                                                                <td style="text-align: center;"><?=$count_search[$i]['CALL']?></td>
                                                            </tr>
                                                        <?}?>
                                                    </tbody>
                                                </table>
                                            </div> -->*/?>
                                        </div>
                                    </center>
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
        $('#load_page').hide();
        $('#datepicker-example4').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example4')
    });
    $('#datepicker-example3').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
      pair: $('#datepicker-example3')
    });
    $('.table-row').mouseover(function(){
        console.log("hi")
        $(this).addClass('table-row-cur');
    });
    $('.table-row').mouseout(function(){
        console.log("hi")
        $(this).removeClass('table-row-cur');
    });

    
    </script>
   
<!-- END SCRIPTS -->
</body>
</html>
