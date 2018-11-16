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
if($_REQUEST['action'] == 'deleted')
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	
	$tbl_appreq = "approval_branch_head";
	$field_appreq = array();
	$field_appreq['DELETED'] = 'Y'; // Y - Yes; N - No;
	$field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $where_appreq = "EMPCODE = '".$_REQUEST['reqid']."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
	
	
if($insert_appreq == 1) { ?>
		<script>
		alert("Details Delete Successfully");
		</script>
		<?php
		//exit();
	} else { ?>
		<script>
		alert("Failed to Delete Details");
		</script>
		<?php
		//exit();
	}
}
if($_REQUEST['action'] == 'alter')
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	
	$tbl_appreq = "approval_branch_head";
	$field_appreq = array();
	$field_appreq['BRNHDSR'] = ''; 
    $where_appreq = "EMPCODE = '".$_REQUEST['reqid']."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
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
	b span{
	float:right;
	color:red;
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
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from approval_master mas, approval_type typ,empsection sub, approval_topcore atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965 ) and tarnumb>9000 order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TEST');
                                            
                                            $arr=array();
                                            foreach ($sql_search as $key => $value) {
                                                    $temp=count($arr[$value['TARNUMB']]);
                                                    $arr[$value['TARNUMB']][$temp]=$value;
                                            }
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

                                           
                                             for($f=0;$f<count($sql_search);$f++)
                                           {
                                           	// echo "select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME from trandata.approval_branch_head@tcscentr apr, trandata.branch@tcscentr brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode";
                                                 $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME,apr.EMPCODE,apr.apmcode,apr.BRNHDSR from approval_branch_head apr, branch brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."'  GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME,apr.apmcode, apr.brncode,apr.brnhdsr  Order by apr.brnhdsr", "Centra", 'TEST');
												 
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
													
													<!--TUP -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP">
                                                        <input type="text" name="emp_<?=$f;?>_1" id="emp_<?=$f;?>_1" onblur="addselect('<?=$f;?>','1');" size="35" />
                                                        <ul name="drop_<?=$f;?>_1" id="columns">
                                                       
                                                       <li>CHOOSE NAME</li>
                                                         							  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragend="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><span class='branch_name_<?=$arr_brn[1][$i]['APMCODE']?>'><? echo $arr_brn[1][$i]['NAME'];?></span><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[1][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
														    
													   <? } ?>
												<!--	<button class='btn' type='button' onclick="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')">alter</button>-->
													
                                                                
                                                        </ul>
                                                    </td>
													<!--TUP --> 
													
													 <!--EKM -->
                                         <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="EKM">
                                                    <input type="text" name="emp_<?=$f;?>_2" id="emp_<?=$f;?>_2" onblur="addselect1('<?=$f;?>','2');" size="35" />
                                                        <ul name="drop_<?=$f;?>_2" id="columns">
													  <li>CHOOSE NAME</li>							  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[10][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[10][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                         
													   <? } ?>
                                                        </ul>
                                         </td>
										  <!--EKM -->
										 
										 
										               <!--MDU -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU">
                                                        <input type="text" name="emp_<?=$f;?>_3" id="emp_<?=$f;?>_3" onblur="addselect2('<?=$f;?>','3');" size="35" />
                                                        <ul name="drop_<?=$f;?>_3" id="columns">
														  <li>CHOOSE NAME</li>
                                                        	     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[14][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[14][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                               <? } ?>
                                                        </ul>
                                                            </td>
															<!--MDU -->
															
														<!--DGL -->	
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL">
                                                        <input type="text" name="emp_<?=$f;?>_4" id="emp_<?=$f;?>_4" onblur="addselect3('<?=$f;?>','4');" size="35" />
                                                        <ul name="drop_<?=$f;?>_4" id="columns">
														   <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">		
														  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[23][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[23][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                <? } ?>
                                                        </ul>
                                                        </td>
														<!--DGL -->
														
														<!--AIR-HYD -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-HYD">
                                                        <input type="text" name="emp_<?=$f;?>_5" id="emp_<?=$f;?>_5" onblur="addselect4('<?=$f;?>','5');" size="35" />
                                                        <ul name="drop_<?=$f;?>_5" id="columns">
														 <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">		
														  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[107][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[107][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                               <? } ?>  
                                                        </ul>
                                                       </td>
													   <!--AIR-HYD -->
													   
													   <!--AIR-MDU -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIT-MDU">
                                                        <input type="text" name="emp_<?=$f;?>_6" id="emp_<?=$f;?>_6" onblur="addselect5('<?=$f;?>','6');" size="35" />
                                                        <ul name="drop_<?=$f;?>_6" id="columns">
													 <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">	
														  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[112][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[112][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                 </div>    
																 <? } ?>
                                                        </ul>
                                                         </td>
														 <!--AIR-MDU-->
														 
														 <!--TAILYOU -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TAILYOU">
                                                        <input type="text" name="emp_<?=$f;?>_7" id="emp_<?=$f;?>_7" onblur="addselect6('<?=$f;?>','7');" size="35" />
                                                        <ul name="drop_<?=$f;?>_7" id="columns">
														   <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">	
														  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[114][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[114][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                     <? } ?>
                                                        </ul>
                                                        </td>
														 <!--TAILYOU -->
														
														 <!--KKV -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="KKV">
                                                        <input type="text" name="emp_<?=$f;?>_8" id="emp_<?=$f;?>_8" onblur="addselect7('<?=$f;?>','8');" size="35" />
                                                        <ul name="drop_<?=$f;?>_8" id="columns">
													  <li>CHOOSE NAME</li>
                                                     	 <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[115][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[115][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
														 <? } ?>
                                                        </ul>
                                                      </td>
													  	 <!--KKV -->
													  
													  	 <!--AIR-CHN-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-CHN">
                                                        <input type="text" name="emp_<?=$f;?>_9" id="emp_<?=$f;?>_9" onblur="addselect8('<?=$f;?>','9');" size="35" />
                                                        <ul name="drop_<?=$f;?>_9" id="columns">
														   <li>CHOOSE NAME</li>
                                                      	  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[116][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[116][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                       <? } ?>
													   </ul>
													   </td>
													  	 <!--AIR-CHN-->
														 
														 
													  	 <!--SCM-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="SCM">
                                                        <input type="text" name="emp_<?=$f;?>_10" id="emp_<?=$f;?>_10" onblur="addselect9('<?=$f;?>','10');" size="35" />
                                                        <ul name="drop_<?=$f;?>_10" id="columns">
												    <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[117][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[117][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                 <? } ?>
                                                            </ul>
                                                        </td>
														 <!--SCM-->
														
														 <!--CLT-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="CLT">
                                                        <input type="text" name="emp_<?=$f;?>_11" id="emp_<?=$f;?>_11" onblur="addselect10('<?=$f;?>','11');" size="35" />
                                                        <ul name="drop_<?=$f;?>_11" id="columns">
													  <li>CHOOSE NAME</li>
                                                         		  <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[120][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[120][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
													   <? } ?>
                                                           </ul>
                                                    </td>
													<!--CLT-->

													<!--TUP-KTM-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-KTM">
                                                       <input type="text" name="emp_<?=$f;?>_12" id="emp_<?=$f;?>_12" onblur="addselect11('<?=$f;?>','12');" size="35" />
                                                        <ul name="drop_<?=$f;?>_12" id="columns">
													<li>CHOOSE NAME</li>
                                                          				 <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[201][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[201][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
													   <? } ?>
                                                        </ul>
														</td>
														<!--TUP-KTM-->
														
														<!--MDU-TJ-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_13" id="emp_<?=$f;?>_13" onblur="addselect12('<?=$f;?>','13');" size="35" />
                                                        <ul name="drop_<?=$f;?>_13" id="columns">
													  <li>CHOOSE NAME</li>
                                                          <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[203][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[203][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                    <? } ?>
                                                            </ul>
                                                        </td>
														<!--MDU-TJ-->
														
														<!--TUP-TJ-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_14" id="emp_<?=$f;?>_14" onblur="addselect13('<?=$f;?>','14');" size="35" />
                                                        <ul name="drop_<?=$f;?>_14" id="columns">
														  <li>CHOOSE NAME</li>
                                                         			 <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[204][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[204][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                             <? } ?>
                                                           </ul>
                                                        </td>
														<!--TUP-TJ-->
														
														<!--DGL-TJ-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL-TJ">
                                                        <input type="text" name="emp_<?=$f;?>_15" id="emp_<?=$f;?>_15" onblur="addselect14('<?=$f;?>','15');" size="35" />
                                                        <ul name="drop_<?=$f;?>_15" id="columns">
														     <li>CHOOSE NAME</li>
                                                      			    <? for($i=0;$i<count($arr_brn[206]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[206][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[206][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                 <? } ?>
                                                             </ul>
                                                        </td>
														<!--DGL-TJ-->
														
														<!--COR-->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="COR">
                                                       <input type="text" name="emp_<?=$f;?>_16" id="emp_<?=$f;?>_16" onblur="addselect15('<?=$f;?>','16');" size="35" />
                                                        <ul name="drop_<?=$f;?>_16" id="columns">
														   <li>CHOOSE NAME</li>
                                                          <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li id="<?=$i;?>" class="column branch_1" draggable="true" ondragstart="update_names('branch_name_<?=$arr_brn[1][0]['APMCODE']?>')"><? echo $arr_brn[888][$i]['NAME'];?><a href='subject_with_approval_flow_2.php?action=deleted&reqid=<? echo $arr_brn[888][$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li>
                                                                      <? } ?> 
                                                        </ul>
                                                         </td>
														 <!--COR-->
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
                                            $sql_search = select_query_json("select ATYNAME, TARNUMB, apmcode, APMNAME, decode(SUBCORE, -1, 'TEXTILE', -2, 'READY MADE', ESENAME) DEPT, atc.atcname from approval_master mas,approval_type typ,empsection sub,approval_topcore atc where atc.atccode = mas.topcore and mas.atycode = typ.atycode and sub.esecode(+) = mas.SUBCORE and sub.deleted(+) = 'N' and mas.deleted = 'N' and typ.deleted = 'N' and ( apmcode = 954 or apmcode = 955 or apmcode = 888 or apmcode = 856 or apmcode > 965 ) and tarnumb is null order by ATYNAME, TARNUMB, DEPT, APMNAME, apmcode", "Centra", 'TEST');
                                            
                                           // echo("<pre>");
                                          //  print_r($sql_search[0]['ATYNAME']);
                                       //    echo("</pre>");
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
	<script type="text/javascript" src="js/jquery-customselect.js"></script>
        <script type="text/javascript">
   
    function  addselect(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
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
function  addselect1(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
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
function  addselect2(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li value="'+emp+'">'+emp+'</li>');
    }
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
function  addselect3(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect4(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect5(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect6(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect7(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect8(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect9(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect10(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect11(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect12(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect13(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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
function  addselect14(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li>');
    }
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
function  addselect15(row,brn){
        console.log('#emp_'+row+'_'+brn);
        var emp=$('#emp_'+row+'_'+brn).val();
        console.log(emp);
        $('#drop_'+row+'_'+brn).append('<li  value="'+emp+'">'+emp+'</li >');
    }
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

var dragSrcEl = null;

function handleDragStart(e) {
  // Target (this) element is the source node.
  dragSrcEl = this;

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
  this.classList.remove('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
  // this/e.target is current target element.

  if (e.stopPropagation) {
    e.stopPropagation(); // Stops some browsers from redirecting.
  }

  // Don't do anything if dropping the same column we're dragging.
  if (dragSrcEl != this) {
    // Set the source column's HTML to the HTML of the column we dropped on.
    //alert(this.outerHTML);
    //dragSrcEl.innerHTML = this.innerHTML;
    //this.innerHTML = e.dataTransfer.getData('text/html');
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
  // this/e.target is the source node.
  this.classList.remove('over');

  /*[].forEach.call(cols, function (col) {
    col.classList.remove('over');
  });*/
}

function addDnDHandlers(elem) {
  elem.addEventListener('dragstart', handleDragStart, false);
  elem.addEventListener('dragenter', handleDragEnter, false)
  elem.addEventListener('dragover', handleDragOver, false);
  elem.addEventListener('dragleave', handleDragLeave, false);
  elem.addEventListener('drop', handleDrop, false);
  elem.addEventListener('dragend', handleDragEnd, false);

}
var cols = document.querySelectorAll('#columns .branch_1');
[].forEach.call(cols, addDnDHandlers);



function update_names(name){
var spl; var emp;
var newemp=[];
var apm=name.split('_');
var apmcode=apm[2];

        var names=[];
        $('.'+name).each(function(){

            names.push($(this).html());
        });
        for(var i=0;i<names.length;i++){
            spl=names[i].split('-');
          
           newemp.push(spl[0]);


        }

        

        $.ajax({
            url:'ajax_drag.php',
            type:'post',
            data:{
                action:'alter',
                apmcode:apmcode,
                newflow:newemp
            },
            success:function(data){

                console.log(data);
            }




        });


}

    
    </script>
   
<!-- END SCRIPTS -->
</body>
</html>


