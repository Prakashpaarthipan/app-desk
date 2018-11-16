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
		<script>window.location='subject_with_approval_flow_1.php?status=delete_success';
		
		alert("Details Delete Successfully");
		</script>
		<?php
		//exit();
	} else { ?>
		<script>window.location='subject_with_approval_flow_1.php?status=failure';
		
		alert("Failed to Delete Details");
		</script>
		<?php
		//exit();
	}
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
 <link rel="stylesheet" href="css/subject.css" type="text/css">
  <script type="text/javascript" src="js/subject.js"></script>
   <script type="text/javascript" src="js/subject1.js"></script>

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
    .dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 12px 16px;
    z-index: 1;
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
                                                    <th class="center" style="padding:100px;">Subject</th>
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
                                                 $sql_brn = select_query_json("select APR.BRNCODE,apr.EMPCODE||'-'||EMPNAME NAME,apr.EMPCODE from approval_branch_head apr, branch brn where apr.brncode = brn.brncode and apr.deleted = 'N' and brn.deleted = 'N' AND apr.apmcode ='".$sql_search[$f]['APMCODE']."' GROUP BY apr.BRNCODE, apr.TARNUMB, apr.EMPSRNO, apr.EMPCODE, apr.EMPNAME, apr.brncode Order by apr.brncode", "Centra", 'TEST');
												 
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
                                            <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP" id="branchname" name="branchname" onchange="branchload();">
													
													<input type="text" name="txt_tup" id="txt_tup" size="30">	
                                                     <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[1][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>           
													   <? } ?>
                                                    </td>
													<!--TUP -->
													
													<!--EKM -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="EKM">
													<input type="text" name="txt_ekm" id="txt_ekm" size="30">	
                                                       <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[10][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>           
													   <? } ?>
                                                    </td>
                                                <!--EKM -->
												
												<!--MDU -->
                                                 <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU">
												 <input type="text" name="txt_mdu" id="txt_mdu" size="30">
												  <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[14][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
                                                    </td>
													<!--MDU -->
													
													<!--DGL -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL">
													<input type="text" name="txt_dgl" id="txt_dgl" size="30">	
                                                       <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[23][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
                                                    </td>
													<!--DGL -->
													
													<!--AIR-HYD -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-HYD">
														<input type="text" name="txt_hyd" id="txt_hyd" size="30">
                                                         <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[107][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 														
                                                    </td>
													<!--AIR-HYD -->
													
													<!--AIT-MDU -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIT-MDU">
                                                     <input type="text" name="txt_ait" id="txt_ait" size="30">	
                                                             <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[112][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 		
                                                    </td>
													<!--AIT-MDU -->
													
													<!--TAILYOU -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TAILYOU">
													<input type="text" name="txt_tail" id="txt_tail" size="30">		
                                                       <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[114][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 													
														</td>
														<!--TAILYOU -->
														
														<!--KKV -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="KKV">
                                                      <input type="text" name="txt_kkv" id="txt_kkv" size="30"> 
													   <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[115][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
                                                    </td>
													<!--KKV -->
													
													<!--AIR-CHN -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="AIR-CHN">
                                                     	<input type="text" name="txt_chn" id="txt_chn" size="30">
														 <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[116][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
														</td>
														<!--AIR-CHN -->
														
														<!--SCM -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="SCM">
                                                      <input type="text" name="txt_scm" id="txt_scm" size="30">
													   <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[117][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
                                                    </td>
													<!--SCM -->
													
													<!--CLT -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="CLT">
                                                        <input type="text" name="txt_clt" id="txt_clt" size="30">
                                                                  <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[120][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
                                                    </td>
													<!--CLT -->
													
													<!--TUP-KTM -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-KTM">
                                                       <input type="text" name="txt_ktm" id="txt_ktm" size="30">
													    <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[201][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
														 </td>
														 <!--TUP-KTM -->
														 
														 <!--MDU-TJ -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="MDU-TJ">
                                                      <input type="text" name="txt_mtj" id="txt_mtj" size="30">
													   <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[203][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?>
														</td>
														<!--MDU-TJ -->
														
														<!--TUP-TJ -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="TUP-TJ">
                                                       <input type="text" name="txt_ttj" id="txt_ttj" size="30"> 
													    <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[204][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
														</td>
														<!--TUP-TJ -->
														
														<!--DGL-TJ -->
                                                    <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="DGL-TJ">
                                                    <input type="text" name="txt_dgl" id="txt_dgl" size="30">
                                                         <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[206][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
																   <? } ?> 
														 </td>
														 <!--DGL-TJ -->
														 
														 <!--COR -->
                                                   <td style='text-align:left;width: 10%;' data-toggle="tooltip" data-placement="top" title="COR">
												    <input type="text" name="txt_cor" id="txt_cor" size="30">
                  										 <div class="dropdown">
                                                       <li>CHOOSE NAME</li>
                                                          <div class="dropdown-container">									  
														     <? for($i=0;$i<count($arr_brn[1]);$i++) {?>
                                                         <li><? echo $arr_brn[888][$i]['NAME']; ?><a href='subject_with_approval_flow_1.php?action=deleted&reqid=<? echo $sql_brn[$i]['EMPCODE'];?>' title='Delete' alt='Delete'><b><span class="fa fa-times-circle"></span></b></a></li></div>
                                                                 </div>   
															 <? } ?>
													</td>
													<!--COR -->
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
    <script>

 function task_assign(empsrno,row) {
                alert("uable to call");
                    var vurl = "approval_branch_head.php";
                    $.ajax({
                        type: "POST",
                        url: vurl,
                        data:{
                            tassrno:tassrno,
							txt_tup:$("#NAME").html(),
				
                           
                        },
                            
                        dataType:'html',
                        success: function(data1) {
                           alert(data1);
                        },
                        error: function(response, status, error)
                        {       alert(error);
                                //alert(response);
                                //alert(status);
                        }
                    });
            }
			
 function branchload(){
                //alert("load");
                //console.log("load");
                $('#load_page').fadeIn('fast');
                var tarnumb=$('#modal_title').html();
                var branch=$('#branchname').val();
                $.ajax({
                    url:'kamatchi/subject.php',
                    type: "POST",
                    dataType:'html',
                    data:{
                        action:'branchload',
                        tarnumb:tarnumb,
                        branch:branch
                    },
                    success:function(data){
                        $('#branch').html(data);
                        var cols1 = document.querySelectorAll('#branches .column');
                        [].forEach.call(cols1, addDnDHandlers);
                        $('#load_page').fadeOut('slow');
                    }
                });
            }
    </script>
   
<!-- END SCRIPTS -->
</body>
</html>


