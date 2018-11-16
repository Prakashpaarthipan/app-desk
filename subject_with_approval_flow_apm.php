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
<title>Subject with Approval Flow :: Approval Desk :: <?php echo $site_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="icon" href="favicon.ico" type="image/x-icon" />

<!-- Select2 -->
<link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
   modal {
display: none; /* Hidden by default */
position:FIXED; /* Stay in place */
z-index: 1; /* Sit on top */
padding-top: 100px; /* Location of the box */
left: 0;
top: 0;
width: auto; /* Full width */
height: auto; /* Full height */
overflow: auto; /* Enable scroll if needed */
background-color: rgb(0,0,0); /* Fallback color */
background.-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.cus {
    background: #cce5ff;
    margin: 5px;
    list-style: none;
    padding: 10px 10px;
    border: 1px solid #666666;
}
.modal {
    display: none; /* Hidden by default */
    position:FIXED; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: auto; /* Full width */
    height: auto; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    height :600px;
    border-radius: 20px;
    text-align: center;
   
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

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
    .box{
        height: 200px;
        width: 200px;
        position: relative;
        display: none;
    }
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
    ul{
        padding:0px !important;
    }
    .list-li{
        border:2px solid #a0a0a0;
        background: rgba(140, 184, 222, 0.89);
        width: 99%;
        height: 1%;
        list-style: none;
        margin: 5px 1px;
        padding: 5px;
    }
</style>
<!-- END META SECTION -->

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-default.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<!-- EOF CSS INCLUDE -->
<!-- <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" /> -->
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <link href="css/jquery.filer.css" rel="stylesheet">
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
                <li class="active">Subject with Approval Flow</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Subject with Approval Flow</strong></h3>
                        <ul class="panel-controls">
                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <!-- START TABS -->                                
                            <div class="panel panel-default tabs">                            
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active"><a role="tab" href="#tab-first" data-toggle="tab">Budget Based Subject</a></li>
                                    <li><a role="tab" href="#tab-second" data-toggle="tab">Non Budget Subject</a></li>
                                </ul>                            
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab-first">
                                        <!-- ///////////////////////////// -->
                                        <div class="col-md-12">
                            <!-- START TABS -->                                
                            <div class="panel panel-default tabs">                            
                               
                                    <div style="height: 700px;overflow-x: scroll;overflow-y: scroll;">
                                        <table  class="table  table-striped" name="copy_pre" >
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                    <th class="center" style='text-align:center'>Target No.</th>
                                                    <th class="center" style='text-align:center'>Top Core</th>
                                                    <th class="center" style='text-align:center'>Department</th>
                                                    <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>TUP</th>
                                                    <th class="center" style='text-align:center'>EKM</th>
                                                    <th class="center" style='text-align:center'>MDU</th>
                                                    <th class="center" style='text-align:center'>DGL</th>
                                                    <th class="center" style='text-align:center'>AIR-HYD</th>
                                                    <th class="center" style='text-align:center'>AIR-MDU</th>
                                                    <th class="center" style='text-align:center'>TAILYOU</th>
                                                    <th class="center" style='text-align:center'>KKV</th>
                                                    <th class="center" style='text-align:center'>AIR-CHN</th>
                                                    <th class="center" style='text-align:center'>SCM</th>
                                                    <th class="center" style='text-align:center'>CLT</th>
                                                    <th class="center" style='text-align:center'>TUP-KTM</th>
                                                    <th class="center" style='text-align:center'>MDU-TJ</th>
                                                    <th class="center" style='text-align:center'>TUP-TJ</th>
                                                    <th class="center" style='text-align:center'>DGL-TJ</th> 
                                                    <th class="center" style='text-align:center'>COR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ, trandata.empsection@tcscentr sub, trandata.approval_topcore@tcscentr atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode AND TARNUMB=9057 and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965 ) and tarnumb>9000 order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TCS');
                                            
                                            $arr=array();
                                            foreach ($sql_search as $key => $value) {
                                                    $temp=count($arr[$value['TARNUMB']]);
                                                    $arr[$value['TARNUMB']][$temp]=$value;
                                            }
                                            $arr1=array();
                                            foreach ($sql_search as $key => $value) {
                                                    $temp=count($arr[$value['APMCODE']]);
                                                    $arr1[$value['APMCODE']][$temp]=$value;
                                            }
                                            echo('<pre>');
                                           // print_r($arr1);
                                            echo('</pre>');
                                            //$sql_brn = select_query_json("select distinct  apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode, apr.brnhdsr, regexp_replace(SubStr(brn.nicname,1,4),'[0-9]','')||SubStr(nicname,5,15) branch from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.tarnumb =9001  and apr.APRVALU > 0 and 0 < aprvalu Order by apr.brncode, apr.brnhdsr", "Centra", 'TCS');
                                            //$arr_brn=array();
                                            //foreach ($sql_brn as $key => $value) {
                                              //      $temp=count($arr_brn[$value['BRNCODE']]);
                                                //    $arr_brn[$value['BRNCODE']][$temp]=$value;
                                            //}
                                           // echo("<pre>");
                                            //print_r($sql_search);
                                            //foreach ($arr as $key => $value) {
                                             //  echo($key." => ".count($value)."\n");
                                           // }
                                            //print_r($arr['9001'][0]['TARNUMB']);
                                           // echo("</pre>");

                                           
                                            $k = 0;
                                            foreach ($arr1 as $key => $value)
                                            {   $k++;
                                                if($value[0]['TARNUMB']>9000)
                                                {
                                                     $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head apr, trandata.branch brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.APMCODE ='".$key."'  and apr.APRVALU > 100000 GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode,apr.brnhdsr Order by apr.brncode,apr.brnhdsr", "Centra", 'TEST');
                                                }
                                                else{
                                                    /* for($f=0;$f<count($value);$f++)
                                                    {
                                                        //$sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$value[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode", "Centra", 'TCS');
                                                    //echo("*************<br>select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$value[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode");
                                                    } */
                                                    
                                                    
                                                }
                                                $arr_brn=array();
                                                    foreach ($sql_brn as $key1 => $value1) {
                                                            $temp1=count($arr_brn[$value1['BRNCODE']]);
                                                            $arr_brn[$value1['BRNCODE']][$temp1]=$value1;
                                                    }
                                                ?>
                                                <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                                    <td class="center" style='text-align:center;width: 5%;'>
                                                        <? echo $k; ?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="Subject">
                                                        <? echo $value[0]['APMNAME']." ".$value[0]['APMCODE']; ?>
                                                    </td>
                                                     <td  style='text-align:left;width: 1%;' data-toggle="tooltip" data-placement="right" title=" Target No."><!-- for top core-->
                                                        <? if($value[0]['TARNUMB']==''){echo('-');}else{echo ($value[0]['TARNUMB']);} ?> 
                                                    </td>
                                                    <td style='text-align:left;width: 1%;' data-toggle="tooltip" data-placement="top" title="Top Core ">
                                                        <? echo $value[0]['ATCNAME']; ?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="Department">
                                                        <? echo $value[0]['DEPT']; ?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="Type Of Submission">
                                                        <? echo $value[0]['ATYNAME']; ?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP">

                                                        <span id="td_1_<?=$value[0]['APMCODE']?>" onclick="loaduser('1','<?=$value[0]['APMCODE']?>');">
                                                            <?$exist=array();
                                                            for($i=0;$i<count($arr_brn[1]);$i++)
                                                            {    $temp=explode('-',$arr_brn[1][$i]['NAME']);
                                                                if(!in_array($temp[0], $exist)){?>
                                                                <div class="row" style="padding-top: 4px;">
                                                                    <? $user.=$arr_brn[1][$i]['NAME'].'~~';
                                                                        
                                                                        $exist[$i]=$temp[0];
                                                                    echo $arr_brn[1][$i]['NAME']; ?>
                                                                </div>
                                                                <?}}?>
                                                            <input type="hidden" id="users_1_<?=$value[0]['APMCODE']?>" value="<?=$user;?>">
                                                        </span>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="EKM">
                                                        <?for($i=0;$i<count($arr_brn[10]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[10][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <!-- ///////////////////// -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU">
                                                        <?for($i=0;$i<count($arr_brn[14]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[14][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL">
                                                        <?for($i=0;$i<count($arr_brn[23]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[23][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-HYD">
                                                        <?for($i=0;$i<count($arr_brn[107]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[107][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIT-MDU">
                                                        <?for($i=0;$i<count($arr_brn[112]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[112][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TAILYOU">
                                                        <?for($i=0;$i<count($arr_brn[114]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[114][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="KKV">
                                                        <?for($i=0;$i<count($arr_brn[115]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[115][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-CHN">
                                                        <?for($i=0;$i<count($arr_brn[116]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[116][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="SCM">
                                                        <?for($i=0;$i<count($arr_brn[117]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[117][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="CLT">
                                                        <?for($i=0;$i<count($arr_brn[120]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[120][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-KTM">
                                                        <?for($i=0;$i<count($arr_brn[201]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[201][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU-TJ">
                                                        <?for($i=0;$i<count($arr_brn[203]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[203][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-TJ">
                                                        <?for($i=0;$i<count($arr_brn[204]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[204][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL-TJ">
                                                        <?for($i=0;$i<count($arr_brn[206]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[206][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="COR">
                                                        <?for($i=0;$i<count($arr_brn[888]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[888][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                </tr>
                                                 
                                           <? } ?>
                                            </tbody>
                                        </table>
                                </div>
                            </div>                                                   
                            <!-- END TABS -->                        
                        </div>
                                        <!-- ////////////////////////////////// -->
                                    </div>
                                    <div class="tab-pane " id="tab-second">
                                        <!-- /////////////////////////////////// -->
                                        <div class="col-md-12">
                            <!-- START TABS -->                                
                            <div class="panel  panel-default tabs">                            
                               <!-- style="height: 700px;overflow-x: scroll;overflow-y: scroll;" -->
                                   <?/* <div style="height: 700px;overflow-x: scroll;overflow-y: scroll;">
                                        <table  class="table  table-striped" name="copy_pre" >
                                            <thead>
                                                <tr>
                                                    <th class="center" style='text-align:center'>S.No</th>
                                                   
                                                    <th class="center" style='text-align:center'>Top Core</th>
                                                    <th class="center" style='text-align:center'>Department</th>
                                                    <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
                                                    <th class="center" style='text-align:center'>COR</th>
                                                    <th class="center" style='text-align:center'>TAILYOU</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?  
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from trandata.approval_master@tcscentr mas, trandata.approval_type@tcscentr typ, trandata.empsection@tcscentr sub, trandata.approval_topcore@tcscentr atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965 ) and tarnumb is null order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TCS');
                                            
                                           // echo("<pre>");
                                          //  print_r($sql_search[0]['ATYNAME']);
                                       //    echo("</pre>");
                                           for($f=0;$f<count($sql_search);$f++)
                                           {
                                                 $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode", "Centra", 'TCS');
                                                $arr_brn=array();
                                                foreach ($sql_brn as $key => $value) {
                                                       $temp=count($arr_brn[$value['BRNCODE']]);
                                                       $arr_brn[$value['BRNCODE']][$temp]=$value;
                                                }
                                                //echo("<pre>");
                                                //print_r($arr_brn);
                                                //echo("</pre>");
                                                 ?>
                                                <tr>
                                                    <td>
                                                        <?=$f+1;?>
                                                        <!-- 1 -->
                                                    </td>
                                                    
                                                     <td data-toggle="tooltip" data-placement="top" title="Top Core">
                                                        <!-- 3 -->
                                                        <?=$sql_search[$f]['ATCNAME']; ?>
                                                    </td>
                                                     <td data-toggle="tooltip" data-placement="top" title="Department">
                                                        <!-- 4 -->
                                                        <?=$sql_search[$f]['DEPT']; ?>
                                                    </td>
                                                     <td data-toggle="tooltip" data-placement="top" title="Type Of Submission">
                                                        <!-- 5 -->
                                                        <?=$sql_search[$f]['ATYNAME']; ?>
                                                    </td>
                                                     <td data-toggle="tooltip" data-placement="top" title="Subject">
                                                        <!-- 6 -->
                                                        <?=$sql_search[$f]['APMNAME']; ?>
                                                    </td>
                                                     <td data-toggle="tooltip" data-placement="top" title="COR">
                                                        <!-- 7 -->
                                                        <?for($i=0;$i<count($arr_brn[888]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[888][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                    <td data-toggle="tooltip" data-placement="top" title="TAILYOU">
                                                        <?for($i=0;$i<count($arr_brn[114]);$i++)
                                                        {?>
                                                            <div class="row" style="padding-top: 4px;">
                                                                <? echo $arr_brn[114][$i]['NAME']; ?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                </tr>
                                           
                                           <?}
                                           
                                            $k = 0;
                                            ?>
                                            </tbody>
                                        </table>
                                </div>*/?>
                            </div>                                                   
                            <!-- END TABS -->                        
                        </div>
                                        <!-- //////////////////////////////////// -->
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
     <div id="myModal" class="modal">
            <style type="text/css">
                .ui-front {
                    z-index: 9999;
                }
            </style>
          <!-- Modal content -->
          <span class="close">&times;</span>
          <div id="modal_data" class="modal-content">
            

          </div>
        </div>
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
 <!-- custom scrtipt -->
     <script src="js/jquery_org.js"></script>
    <script src="js/jQuery_ui_org.js"></script>
    <script src="viki/js/custom_viki.js"></script>
    <!-- custom scrtipt -->
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

    var gbrn;
    var gtarnumb;
    var modal = document.getElementById('myModal');
     var btn = document.getElementById("view_msg");
     var span = document.getElementsByClassName("close")[0];



      /* Custom Drop */
    var dragSrcEl = null;

    function handleDragStart(e) {
      // Target (this) element is the source node.
      dragSrcEl = this;
      $(this).css('background','#ce7676');
      //console.log("hi");
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/html', this.outerHTML);

      this.classList.add('dragElem');
    }
    function handleDragOver(e) {
      if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
      }
      this.classList.add('over');

      e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
      return false;
    }

    function handleDragEnter(e) {
      // this / e.target is the current hover target.
    }

    function handleDragLeave(e) {
      this.classList.remove('over');
    }

    function handleDrop(e) {
      // this/e.target is current target element.
      
      //this.classList.remove('dragElem');
      if (e.stopPropagation) {
        e.stopPropagation(); // Stops some browsers from redirecting.
      }
      if (dragSrcEl != this) {
        this.parentNode.removeChild(dragSrcEl);
        var dropHTML = e.dataTransfer.getData('text/html');
        this.insertAdjacentHTML('beforebegin',dropHTML);
        var dropElem = this.previousSibling;
        addDnDHandlers(dropElem);
      }
      this.classList.remove('over');

      return false;
    }

    function handleDragEnd(e) {
      this.classList.remove('over');
      this.classList.remove('dragElem');
      alter();
    }

    function addDnDHandlers(elem) {
      elem.addEventListener('dragstart', handleDragStart, false);
      elem.addEventListener('dragenter', handleDragEnter, false)
      elem.addEventListener('dragover', handleDragOver, false);
      elem.addEventListener('dragleave', handleDragLeave, false);
      elem.addEventListener('drop', handleDrop, false);
      elem.addEventListener('dragend', handleDragEnd, false);

    }
          /* Custom Drop */
    span.onclick = function() {
        modal.style.display = "none";
        gbrn='';
        gtarnumb='';
    }
     
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            gbrn='';
            gtarnumb='';
        }
    }



    function insertuser(user)
    {   if(user=='')
        {
            return;
        }
        emp=user.split(' - ');
        emp=emp[0];
        var users=$('#users_'+gbrn+'_'+gtarnumb).val();
        users=users.split('~~');
        for(var i=0;i<Object.keys(users).length;i++)
        {
            users[i]=users[i].split('-')[0];
        }
        console.log(users);
        console.log(emp);
        var flag=0;
        for(var i=0;i<Object.keys(users).length;i++)
        {
            if(users[i]==emp)
            {
                flag=1;break;
            }
        }
        if(flag==1)
        {
            alert("warning : user exist already !");
            return;
        }
        $.ajax({
            url:"viki/admin_subject_flow_update.php",
            type:"POST",
            data:{
                gbrn,gtarnumb,
                action:'insert',
                user
            },
            dataType:'html',
            success:function(data)
            {
                console.log(data);
                reload_success(gbrn,gtarnumb);
                modal.style.display = "none";
                gbrn='';
                gtarnumb='';
            }
        });
    }

     function loaduser(brn,tarnumb)
    {   
        $('#myModal').show();
        var users=$('#users_'+brn+'_'+tarnumb).val();
        users=users.split('~~');
        gbrn=brn;
        gtarnumb=tarnumb;
        console.log(users);
        var val="<ul id='branches'>";

        for(i=0;i<Object.keys(users).length;i++)
        {   var emp=users[i].split('-')[0];
            if(users[i]!='')
            {
                val+="<li class='list-li column users' draggable='true'>"+users[i]+"<span class='fa fa-times' style='float: right;' onclick='deleteuser("+emp+");'></span></li>";
            }
        }
        val+="</ul>";
        val+="<input type='text' class='form-control auto_complete1'  id='txt_value1' style='width: 99%;margin:5px 1px;'' id='add_user' placeholder='NEW USER' onblur='insertuser(this.value,"+brn+","+tarnumb+")'/>";
        $('#modal_data').html(val);
        auto_complete1();
        var cols1 = document.querySelectorAll('#branches .column');
        [].forEach.call(cols1, addDnDHandlers);
    }  
    
    function deleteuser(user)
    {
        $.ajax({
            url:"viki/admin_subject_flow_update.php",
            type:"POST",
            data:{
                gbrn,gtarnumb,
                action:'delete',
                user
            },
            dataType:'html',
            success:function(data)
            {
                console.log(data);
                reload_success(gbrn,gtarnumb);
                modal.style.display = "none";
                gbrn='';
                gtarnumb='';
            }
        });
    }

     function alter()
    {
        var newflow=[];
        var i=0;
        $('.users').each(function(){
            newflow[i++]=$(this).html().split('-')[0];
        });
        var oldflow=$('#users_'+gbrn+'_'+gtarnumb).val();
        oldflow=oldflow.split('~~');
        console.log(newflow);
        $.ajax({
            url:"viki/admin_subject_flow_update.php",
            type:"POST",
            dataType:'html',
            data:{
                 gbrn,gtarnumb,
                action:'alterflow',
                newflow,oldflow
            },
            success:function(data){
                console.log(data);
                reload_success(gbrn,gtarnumb);
                modal.style.display = "none";
                gbrn='';
                gtarnumb='';
            }
        });
    }
    function reload_success(brn,tarnumb)
    {
        $.ajax({
            url:"viki/admin_subject_flow_update.php",
            type:"POST",
            data:{
                brn,tarnumb,
                action:'load'
            },
            dataType:"html",
            success:function(data){
                console.log(data);
                $('#td_'+brn+"_"+tarnumb).html(data);
            }
        });
    }
    </script>
   
<!-- END SCRIPTS -->
</body>
</html>
