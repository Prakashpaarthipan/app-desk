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
                                    <li class="active"><a role="tab" href="#tab-first" data-toggle="tab">Value Based Budget</a></li>
                                    <li><a role="tab" href="#tab-second" data-toggle="tab">Non Value Budget</a></li>
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
                                                    <th class="center" style='text-align:center'>Top Core</th>
                                                    <th class="center" style='text-align:center'>Department</th>
                                                    <th class="center" style='text-align:center'>Type Of Submission</th>
                                                    <th class="center" style='text-align:center'>Subject</th>
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
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from approval_master mas, approval_type typ,empsection sub, approval_topcore atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965 ) and tarnumb>9000 and rownum<10 order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TEST');
                                            
                                            $arr=array();
                                            foreach ($sql_search as $key => $value) {
                                                    $temp=count($arr[$value['TARNUMB']]);
                                                    $arr[$value['TARNUMB']][$temp]=$value;
                                            }
                                             
                                           
                                             for($f=0;$f<count($sql_search);$f++)
                                           {
                                            
                                                 $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from approval_branch_head apr, branch brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode", "Centra", 'TEST');
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
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="Type Of Submission">
                                                       
                                                           
                                                                <? 
                                                                $name=array();
                                                                $name=explode(' ',$sql_search[$f]['ATYNAME']);
                                                                echo $name[1]; ?>
                                                           
                                                    </td>
                                                    <td data-toggle="tooltip" data-placement="top" title="Subject">
                                                        <!-- 6 -->
                                                        <?=$sql_search[$f]['APMNAME']; ?>
                                                    </td>
													
													
                                                   <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP">
                                                        <input type="text" name="emp_<?=$f;?>_1" id="emp_<?=$f;?>_1" class="auto_complete"  onblur="insert('<?=$f;?>','1');" />
                                                        <ul name="drop_<?=$f;?>_1" id="drop_<?=$f;?>_1">
                                                            <?for($i=0;$i<count($arr_brn[1]);$i++){?>
                                                                <li value="<?=$arr_brn[1][$i]['NAME'];?>"><?=$arr_brn[1][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                    </td>
                                         <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="EKM">
                                                    <input type="text" name="emp_<?=$f;?>_2" id="emp_<?=$f;?>_2" class="auto_complete" onblur="addselect1('<?=$f;?>','2');" />
                                                        <ul name="drop_<?=$f;?>_2" id="drop_<?=$f;?>_2">
                                                            <?for($i=0;$i<count($arr_brn[10]);$i++){?>
                                                                <li value="<?=$arr_brn[10][$i]['NAME'];?>"><?=$arr_brn[10][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                         </td>
                                                    <!-- ///////////////////// -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU">
                                                        <input type="text" name="emp_<?=$f;?>_3" id="emp_<?=$f;?>_3"    class="auto_complete" onblur="addselect2('<?=$f;?>','3');" />
                                                        <ul name="drop_<?=$f;?>_3" id="drop_<?=$f;?>_3">
                                                            <?for($i=0;$i<count($arr_brn[14]);$i++){?>
                                                                <li value="<?=$arr_brn[14][$i]['NAME'];?>"><?=$arr_brn[14][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                                                             </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL">
                                                        <input type="text" name="emp_<?=$f;?>_4" id="emp_<?=$f;?>_4" class="auto_complete" onblur="addselect3('<?=$f;?>','4');" />
                                                        <ul name="drop_<?=$f;?>_4" id="drop_<?=$f;?>_4">
                                                            <?for($i=0;$i<count($arr_brn[23]);$i++){?>
                                                                <li value="<?=$arr_brn[23][$i]['NAME'];?>"><?=$arr_brn[23][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-HYD">
                                                        <input type="text" name="emp_<?=$f;?>_5" id="emp_<?=$f;?>_5" class="auto_complete" onblur="addselect4('<?=$f;?>','5');" />
                                                        <ul name="drop_<?=$f;?>_5" id="drop_<?=$f;?>_5">
                                                            <?for($i=0;$i<count($arr_brn[107]);$i++){?>
                                                                <li value="<?=$arr_brn[107][$i]['NAME'];?>"><?=$arr_brn[107][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIT-MDU">
                                                        <input type="text" name="emp_<?=$f;?>_6" id="emp_<?=$f;?>_6" class="auto_complete" onblur="addselect5('<?=$f;?>','6');" />
                                                        <ul name="drop_<?=$f;?>_6" id="drop_<?=$f;?>_6">
                                                            <?for($i=0;$i<count($arr_brn[112]);$i++){?>
                                                                <li value="<?=$arr_brn[112][$i]['NAME'];?>"><?=$arr_brn[112][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TAILYOU">
                                                        <input type="text" name="emp_<?=$f;?>_7" id="emp_<?=$f;?>_7" class="auto_complete" onblur="addselect6('<?=$f;?>','7');" />
                                                        <ul name="drop_<?=$f;?>_7" id="drop_<?=$f;?>_7">
                                                            <?for($i=0;$i<count($arr_brn[114]);$i++){?>
                                                                <li value="<?=$arr_brn[114][$i]['NAME'];?>"><?=$arr_brn[114][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                                                                            </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="KKV">
                                                        <input type="text" name="emp_<?=$f;?>_8" id="emp_<?=$f;?>_8" class="auto_complete" onblur="addselect7('<?=$f;?>','8');" />
                                                        <ul name="drop_<?=$f;?>_8" id="drop_<?=$f;?>_8">
                                                            <?for($i=0;$i<count($arr_brn[115]);$i++){?>
                                                                <li value="<?=$arr_brn[115][$i]['NAME'];?>"><?=$arr_brn[115][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-CHN">
                                                        <input type="text" name="emp_<?=$f;?>_9" id="emp_<?=$f;?>_9" class="auto_complete" onblur="addselect8('<?=$f;?>','9');" />
                                                        <ul name="drop_<?=$f;?>_9" id="drop_<?=$f;?>_9">
                                                            <?for($i=0;$i<count($arr_brn[116]);$i++){?>
                                                                <li value="<?=$arr_brn[116][$i]['NAME'];?>"><?=$arr_brn[116][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="SCM">
                                                        <input type="text" name="emp_<?=$f;?>_10" id="emp_<?=$f;?>_10" class="auto_complete" onblur="addselect9('<?=$f;?>','10');" />
                                                        <ul name="drop_<?=$f;?>_10" id="drop_<?=$f;?>_10">
                                                            <?for($i=0;$i<count($arr_brn[117]);$i++){?>
                                                                <li value="<?=$arr_brn[117][$i]['NAME'];?>"><?=$arr_brn[117][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="CLT">
                                                        <input type="text" name="emp_<?=$f;?>_11" id="emp_<?=$f;?>_11"  class="auto_complete" onblur="addselect10('<?=$f;?>','11');" />
                                                        <ul name="drop_<?=$f;?>_11" id="drop_<?=$f;?>_11">
                                                            <?for($i=0;$i<count($arr_brn[120]);$i++){?>
                                                                <li value="<?=$arr_brn[120][$i]['NAME'];?>"><?=$arr_brn[120][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>

                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-KTM">
                                                       <input type="text" name="emp_<?=$f;?>_12" id="emp_<?=$f;?>_12" class="auto_complete" onblur="addselect11('<?=$f;?>','12');" />
                                                        <ul name="drop_<?=$f;?>_12" id="drop_<?=$f;?>_12">
                                                            <?for($i=0;$i<count($arr_brn[201]);$i++){?>
                                                                <li value="<?=$arr_brn[201][$i]['NAME'];?>"><?=$arr_brn[201][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                                                                            </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_13" id="emp_<?=$f;?>_13" class="auto_complete" onblur="addselect12('<?=$f;?>','13');" />
                                                        <ul name="drop_<?=$f;?>_13" id="drop_<?=$f;?>_13">
                                                            <?for($i=0;$i<count($arr_brn[203]);$i++){?>
                                                                <li value="<?=$arr_brn[203][$i]['NAME'];?>"><?=$arr_brn[203][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_14" id="emp_<?=$f;?>_14" class="auto_complete" onblur="addselect13('<?=$f;?>','14');" />
                                                        <ul name="drop_<?=$f;?>_14" id="drop_<?=$f;?>_14">
                                                            <?for($i=0;$i<count($arr_brn[204]);$i++){?>
                                                                <li value="<?=$arr_brn[204][$i]['NAME'];?>"><?=$arr_brn[204][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_15" id="emp_<?=$f;?>_15" class="auto_complete" onblur="addselect14('<?=$f;?>','15');" />
                                                        <ul name="drop_<?=$f;?>_15" id="drop_<?=$f;?>_15">
                                                            <?for($i=0;$i<count($arr_brn[206]);$i++){?>
                                                                <li value="<?=$arr_brn[206][$i]['NAME'];?>"><?=$arr_brn[206][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
                                                    </td>
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="COR">
                                                       <input type="text" name="emp_<?=$f;?>_16" id="emp_<?=$f;?>_16" class="auto_complete" onblur="addselect15('<?=$f;?>','16');" />
                                                        <ul name="drop_<?=$f;?>_16" id="drop_<?=$f;?>_16">
                                                            <?for($i=0;$i<count($arr_brn[888]);$i++){?>
                                                                <li value="<?=$arr_brn[888][$i]['NAME'];?>"><?=$arr_brn[888][$i]['NAME'];?></li>
                                                            <?}?>
                                                        </ul>
                                                        
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
                                    <div style="height: 700px;overflow-x: scroll;overflow-y: scroll;">
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
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from approval_master mas,approval_type typ,empsection sub,approval_topcore atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954  ) and tarnumb is null  and rownum<1 order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TEST');
                                            //or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965
                                           // echo("<pre>");
                                          //  print_r($sql_search[0]['ATYNAME']);
                                       //    echo("</pre>");
                                           for($f=0;$f<count($sql_search);$f++)
                                           {
                                                 $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from approval_branch_head apr, branch brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."'     GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode", "Centra", 'TEST');
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
                                </div>
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
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
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

	<script src="js/auto_complete.js"></script>
    <script src="js/jquery_org.js"></script>
    <script src="js/jQuery_ui_org.js"></script>
    
   <script>
   $(document).ready(function(){
    auto_complete();
    });

    function  addselect(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
       
function  addselect1(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
       
function  addselect2(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
       
function  addselect3(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect4(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect5(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
        
    
function  addselect6(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
      
function  addselect7(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
        
function  addselect8(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect9(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect10(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect11(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect12(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
       
function  addselect13(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
     
function  addselect14(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li>');
    }
     
function  addselect15(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
    
       function insert(row,brn){
         var emp=$('#emp_'+row+'_'+brn).val();
         //var brncode = $('#')
         console.log(emp);
           $.ajax({
                url:"ajax/ajax_subject1.php",
                type: "POST",
                data: {
                    id:emp,
                    //brncode : var 

                    action:'insert'
                },                
                dataType:"html",
                success:function(data){
                   
                   console.log(data);
                  
                   // $('#load_page').show();
                  //location.reload();
                }
               
            });
    }
	    </script>
   
<!-- END SCRIPTS -->
</body>
</html>
 