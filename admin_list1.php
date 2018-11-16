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
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }
	.nav-tabs > li > a{
		border: 0px;
		background-color:#BABABA !important;
		-moz-border-radius: 3px 3px 0px 0px;
		-webkit-border-radius: 3px 3px 0px 0px !important;
		border-radius: 3px 3px 0px 0px;
	}
	
	.nav-tabs > li > a:hover{
	
	color: #fff !important;
    cursor: pointer;
    background-color: #a7a9ad !important;
	border: 0px;
    border-top: 2px solid #1b1e24 !important;
	-moz-border-radius: 3px 3px 0px 0px;
	-webkit-border-radius: 3px 3px 0px 0px;
	border-radius: 3px 3px 0px 0px;
	}
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus {
    border: 0px;
    border-top: 2px solid #1b1e24;
    background: #3f444c !important;
    -moz-border-radius: 3px 3px 0px 0px;
    -webkit-border-radius: 3px 3px 0px 0px;
    border-radius: 3px 3px 0px 0px;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    color: #fff !important;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}

.trcenter {
	text-align:center
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
    <? /* <div id="load_page" style='display:block;padding:12% 40%;'> </div> */ ?>
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
                <li>Admin Project List</li>
            </ul>
			
			
			
            <!-- END BREADCRUMB -->
			<div class="nav-tabs-custom ">
				<ul class="nav nav-tabs">
				  <li class="active" ><a href="#tab_1" data-toggle= "tab" title ="waiting approval" >Waiting Approval</a></li>
				  <li class=""><a href="#tab_2" data-toggle="tab"  title ="approved list">Approved List</a></li>
				  <li class=""><a href="#tab_3" data-toggle="tab"  title ="rejected list">Rejected List</a></li>
				  
				  <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
				</ul>
				<div class="tab-content">
				  <div class="tab-pane active" id="tab_1">
				  <div style ="padding-top:10px; background: #3f444c;"></div>
				  <div class="page-content-wrap">
					<div class="panel panel-default">
						<div class="panel-heading">
                        <h3 class="panel-title">Admin Project List</h3>
						</div>
						<div class="panel-body">
                        <!-- <div class="form-group trbg non-printable">
                            <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                                <div class="col-xs-2" style='text-align:center; padding:5px;'></div>

                                <? /* <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 0;'>
                                    <input type='text' class="form-control" tabindex='1' autofocus name='search_subject' id='search_subject' value='<?=$_REQUEST['search_subject']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Details' title="Details" style='text-transform: uppercase;'>
                                </div> */ ?>

                                <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 5px;'>
                                    <input type='text' class="form-control" tabindex='1' name='search_aprno' id='search_aprno' value='<?=$_REQUEST['search_aprno']?>' maxlength="100" data-toggle="tooltip" data-placement="top" placeholder='Approval No' title="Approval No" style='text-transform: uppercase;'>
                                </div>

                                <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                    <input type='text' class="form-control" tabindex='2' name='search_value' id='search_value' value='<?=$_REQUEST['search_value']?>' data-toggle="tooltip" data-placement="top" maxlength="10" placeholder='Value' title="Value" style='text-transform: uppercase;'>
                                </div>

                                <div class="col-xs-2" style='text-align:center; padding:5px 5px 0 6px;'>
                                    <input type='hidden' name='search_add_findate' id='search_add_findate' value='ADDDATE' >
                                    <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                                </div>

                                <div class="col-xs-2" style='text-align:center; padding:5px;'>
                                    <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly maxlength="11" tabindex='6' value='<? if($_REQUEST['search_todate'] != '') { echo $_REQUEST['search_todate']; } else { /* echo date("d-M-Y"); */ } ?>' data-toggle="tooltip" data-placement="top" placeholder='To Date' title="To Date">
                                </div>

                                <div class="col-xs-2" style='text-align:left; padding:5px;'>
                                    <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-primary' style='padding:6px 12px !important' value='Search' title='Search' >
                                </div>
                            </form>
                        </div> -->
						
					<form role="form" id='frm_project_list_edit' name='frm_project_list_edit' method='post' enctype="multipart/form-data">
                        <div class="non-printable" style='clear:both; border-bottom:1px solid #000; margin-bottom:10px; margin-top: 10px;'></div>
						
						<table id="tbl_project_list" class="table datatable" style="font-size: 12px;"  >
						<thead >
							<tr >
								<th class="center" style="text-align:center;font-size: 12px;">S No</th>
								<th class="center" style="text-align:center;font-size: 12px;">Project ID</th>
								<th class="center" style="text-align:center;font-size: 12px;">Project Name</th>
								<th class="center" style="text-align:center;font-size: 12px;">Branch</th>
								<th class="center" style="text-align:center;font-size: 12px;" >Mode</th>
								<th class="center" style="text-align:center;font-size: 12px;">Add Date</th>
								<th class="center" style="text-align:center;font-size: 12px;">Action</th>
								<!--<th class="center" style="text-align:center">Approve Stage</th>-->
							</tr>
						</thead>
							<tbody>
								<!-----------Current User Project List------------->
								<?
							/*$project_current_user = select_query_json("select * from approval_project_master pm , approval_project_head ph  
							where pm.PRMSYER = ph.PRMSYER and pm.PRMSCOD = ph.PRMSCOD and PRJTITL = '4' and ph.DELETED = 'N' and
							ph.ADDUSER = ".$_SESSION['tcs_usrcode']." ORDER BY PM.PRMSCOD", "Centra", "TEST"); "*/
							
							$sql_project = select_query_json("select to_char(ap.ADDDATE,'dd-MM-yyyy HH:mi:ss AM') ADDDATE,ap.* from approval_project_master ap where ap.ADDUSER = '".$_SESSION['tcs_usrcode']."' and ap.DELETED = 'N' ORDER BY ap.PRMSCOD,ap.ADDDATE ASC ","Centra","TEST");
								
      								for($project_i = 0; $project_i < count($sql_project); $project_i++) {
										
									?>
									<tr class="center">
									<td class="center" style="text-align:center"><? echo ($project_i+1);?></td>
									<td class="center" style="text-align:center"><? echo $sql_project[$project_i]['PRMSCOD'];?></td>
									<td class="center" style="text-align:center"><? echo $sql_project[$project_i]['PRJNAME'];?></td>
									<td class="center" style="text-align:center"><? echo $sql_project[$project_i]['BRNNAME'];?></td>
									<td class="center" style="text-align:center"><? if ($sql_project[$project_i]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {  echo "BRANCH"; }?>
								</td>
								<td class="center" style="text-align:center"><? echo $sql_project[$project_i]['ADDDATE'];?></td>
								<?
								$view = select_query_json("select * from approval_project_hierarchy where prmscod = '".$sql_project[$project_i]['PRMSCOD']."' and Appstat ='Y'","Centra","TEST");
								//var_dump($view);
								//if(count($view) == 1){
								if($view[0]['APPSTAT'] == 'Y'){ ?> 
								<td class="center" style="text-align:center">
								<button onclick=" javascript:var pjid = <? echo $sql_project[$project_i]['PRMSCOD'];?>; 
								var pjyr = '<? echo $sql_project[$project_i]['PRMSYER'];?>';getProjectDetails(pjid,pjyr)" data-toggle="modal" type = "button" data-target="#modal_large" data-id="<? echo $sql_project[$project_i]['PRMSCOD']; ?>" id="getProject" class="btn btn-sm btn-info" style="background-color: #397ce0;!important; border:none;"><i class="glyphicon glyphicon-retweet"></i> Processing</button>
								<!--<a class='btn btn-info btn-sm' style="background-color: #397ce0;!important; border:none;"
								 href="" data-toggle="modal" data-target="#modal_large">
								<span class="glyphicon glyphicon-retweet"></span> Processing...</a>-->
							 </td> 
								<!--<td><div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" id="defaultUnchecked">
								<label class="custom-control-label" for="defaultUnchecked">Default unchecked</label>
								</div>
								</td>-->							 <?} else {?>
							 <td class="center" style="text-align:center">
							<a class='btn btn-info btn-sm'
						 href="/approval-desk-test/admin_project_modify_prakash.php?c_year=<?echo $sql_project[$project_i]['PRMSYER'];?>&id=<?echo $sql_project[$project_i]['PRMSCOD'];?>">
							<span class="glyphicon glyphicon-log-in"></span> View</a>
							 </td>
							
							 <?}?>
							 </tr>
							<?} ?>
								<!---------END--------->	
							   <?
								if ($_SESSION['tcs_empsrno'] == 188)//188
								{ //condition to check first stage of approval process
							
							 /* $sql_project = select_query_json("select * from approval_project_master pm , approval_project_head ph where pm.PRMSYER = ph.PRMSYER and pm.PRMSCOD = ph.PRMSCOD and PRJTITL = '4' and ph.DELETED = 'N' ORDER BY PM.PRMSCOD", "Centra", 'TEST'); //ledger wise list
							 
							 // List Based on project code
							 $sql_project1 = select_query_json("select distinct pm.PRMSYER, pm.PRMSCOD, pm.PRJNAME, pm.BRNNAME, ph.TOPCORE, ph.SUBCRNM, ph.TARNUMB, ph.TARNAME, pm.BRN_PRJ, ph.PRMSYER, ph.PRMSCOD,ah.EMPSRNO from approval_project_master pm ,  approval_project_head ph ,approval_project_hierarchy ah where pm.PRMSYER = ph.PRMSYER and pm.PRMSCOD = ph.PRMSCOD and PRJTITL = '4' and ph.DELETED = 'N' and ph.PRMSCOD = ah.PRMSCOD and ah.EMPSRNO = '188' and ah.APPSTAT = 'N' ORDER BY pm.PRMSCOD","Centra","TEST");*/
							 $sql_project1 = select_query_json("select to_char(ap.ADDDATE,'dd-MM-yyyy HH:mi:ss AM') ADDDATE,ap.* from approval_project_master ap  where ap.DELETED = 'N' and ap.PRJSTAT = 'N' and ap.CAPPUSR = '0' ORDER BY ap.ADDDATE,ap.PRMSCOD","Centra","TEST");
								
      							for($project_j = 0; $project_j < count($sql_project1); $project_j++) {
										
									?>
								<tr>
								<td class="center" style="text-align:center"><?echo ($project_j+1);?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRMSCOD'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRJNAME'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['BRNNAME'];?></td>
								<td class="center" style="text-align:center"><?if ($sql_project1[$project_j]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								} ?></td>
								<td class="center" style="text-align:center"><? echo $sql_project1[$project_j]['ADDDATE'];?></td>
								<td class="text-center">
								<a class='btn btn-info btn-sm'
								 href="/approval-desk-test/admin_project_modify_prakash.php?c_year=<?echo $sql_project1[$project_j]['PRMSYER'];?>&id=<?echo $sql_project1[$project_j]['PRMSCOD'];?>">
													 <span class="glyphicon glyphicon-log-in"></span> View</a> 
								</td>
									
								</tr>	<? 	 }  }
											?>	
								
								
								<!--  Condition to check Second stage of approval process-->
								<? if ($_SESSION['tcs_empsrno'] == 83815){
									$sql_project1 = select_query_json("select to_char(ap.ADDDATE,'dd-MM-yyyy HH:mi:ss AM') ADDDATE,ap.* from approval_project_master ap where ap.CAPPUSR = 188 and ap.DELETED = 'N' and ap.PRJSTAT = 'N' ORDER BY ap.ADDDATE,ap.PRMSCOD","Centra","TEST");
									/* $sql_project1 = select_query_json("select * from approval_project_master pm, approval_project_hierarchy ah where pm.PRMSCOD = ah.PRMSCOD and pm.DELETED = 'N' and ((ah.EMPSRNO = '188' and ah.APPSTAT = 'Y') and (ah.EMPSRNO = '61579' and ah.APPSTAT = 'N')) ORDER BY pm.PRMSCOD","Centra","TEST");*/

									for($project_j = 0; $project_j < count($sql_project1); $project_j++) {									
									?>
									<tr>
								<td class="center" style="text-align:center"><?echo ($project_j+1);?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRMSCOD'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRJNAME'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['BRNNAME'];?></td>
								<td class="center" style="text-align:center"><?if ($sql_project1[$project_j]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								} ?></td>
								<td class="center" style="text-align:center"><? echo $sql_project1[$project_j]['ADDDATE'];?></td>
								<td class="text-center">
								<a class='btn btn-info btn-sm'
								 href="/approval-desk-test/admin_project_modify_prakash.php?c_year=<?echo $sql_project1[$project_j]['PRMSYER'];?>&id=<?echo $sql_project1[$project_j]['PRMSCOD'];?>">
													 <span class="glyphicon glyphicon-log-in"></span> View</a> 
								</td>							  
								</tr>	<? 	 }  }				
											?>	
							<!--  Condition to check Third stage of approval process-->		
							<? if ($_SESSION['tcs_empsrno'] == 452){
									$sql_project1 = select_query_json("select to_char(ap.ADDDATE,'dd-MM-yyyy HH:mi:ss AM') ADDDATE,ap.* from approval_project_master ap where ap.CAPPUSR = 83815 and ap.DELETED = 'N' and ap.PRJSTAT = 'N' ORDER BY ap.ADDDATE,ap.PRMSCOD","Centra","TEST");
									for($project_j = 0; $project_j < count($sql_project1); $project_j++) {									
									?>
									<tr>
								<td class="center" style="text-align:center"><?echo ($project_j+1);?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRMSCOD'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRJNAME'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['BRNNAME'];?></td>
								<td class="center" style="text-align:center"><?if ($sql_project1[$project_j]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								} ?></td>
								<td class="center" style="text-align:center"><? echo $sql_project1[$project_j]['ADDDATE'];?></td>
								<td class="text-center">
								<a class='btn btn-info btn-sm'
								 href="/approval-desk-test/admin_project_modify_prakash.php?c_year=<?echo $sql_project1[$project_j]['PRMSYER'];?>&id=<?echo $sql_project1[$project_j]['PRMSCOD'];?>">
													 <span class="glyphicon glyphicon-log-in"></span> View</a> 
								</td>							  
								</tr>	<? 	 }  }				
											?>	
								<!--  Condition to check Final stage of approval process-->		
								<? if ($_SESSION['tcs_empsrno'] == 21344){
									$sql_project1 = select_query_json("select to_char(ap.ADDDATE,'dd-MM-yyyy HH:mi:ss AM') ADDDATE,ap.* from approval_project_master ap where ap.CAPPUSR = 452 and ap.DELETED = 'N' and ap.PRJSTAT = 'N' ORDER BY ap.ADDDATE,ap.PRMSCOD","Centra","TEST");
									for($project_j = 0; $project_j < count($sql_project1); $project_j++) {									
									?>
									<tr>
								<td class="center" style="text-align:center"><?echo ($project_j+1);?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRMSCOD'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['PRJNAME'];?></td>
								<td class="center" style="text-align:center"><?echo $sql_project1[$project_j]['BRNNAME'];?></td>
								<td class="center" style="text-align:center"><?if ($sql_project1[$project_j]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								} ?></td>
								<td class="center" style="text-align:center"><? echo $sql_project1[$project_j]['ADDDATE'];?></td>
								<td class="text-center">
								<a class='btn btn-info btn-sm'
								 href="/approval-desk-test/admin_project_modify_prakash.php?c_year=<?echo $sql_project1[$project_j]['PRMSYER'];?>&id=<?echo $sql_project1[$project_j]['PRMSCOD'];?>">
													 <span class="glyphicon glyphicon-log-in"></span> View</a> 
								</td>							  
								</tr>	<? 	 }  }				
											?>	
											
											
							</tbody>
						</table>
							</form>
						
						</div>
					  </div>
				  </div>
				  
				  <!-- /.tab-pane -->
				  
				
            <!-- /.tab-content -->
			</div>
			<div class="tab-pane" id="tab_2">
			 <div style ="padding-top:10px; background: #3f444c;"></div>
			   <div class="page-content-wrap">
					<div class="panel panel-default">
						<div class="panel-heading">
						<h3 class="panel-title">Approved List</h3>
						</div>
					<div class = "panel-body">
						
					<form role ="form" id ="frm_approved_list" name="frm_approved_list" action ='' >
					<div class="non-printable" style='clear:both; border-bottom:1px solid #000; margin-bottom:10px; margin-top: 10px;'></div>
						<table id="tbl_approved_list" class="table datatable" style="font-size: 12px;"  >
							<thead>
								<tr>
								  <th>S No</th>
								  <th>Project ID</th>
								  <th>Project Name</th>
								  <th>Branch</th>
								  <th >Mode</th>
								  <th >Approved Date</th>
								  <th >Action</th>
								</tr>
							</thead>
							<tbody>
								<?
								if ($_SESSION['tcs_empsrno'] == 188)
								{ //condition to check first stage of approval process
							$sql_project = select_query_json("select to_char(ah.APPDATE,'dd-MM-yyyy HH:mi:ss AM') APPDATE,am.* from approval_project_master am ,approval_project_hierarchy ah  where ah.prmscod = am.prmscod and ah.EMPSRNO = 188 and ah.APPSTAT = 'Y' ORDER BY ah.PRMSCOD","Centra","TEST");
								
      								for($project_i = 0; $project_i < count($sql_project); $project_i++) {
										$row = 1;
									?>
									<tr>
									<td><? echo ($project_i+1);?></td>
									<td><? echo $sql_project[$project_i]['PRMSCOD'];?></td>
									<td><? echo $sql_project[$project_i]['PRJNAME'];?></td>
									<td><? echo $sql_project[$project_i]['BRNNAME'];?></td>
									<td><? if ($sql_project[$project_i]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								}?></td>
									<td><? echo $sql_project[$project_i]['APPDATE'];?></td>
									<td><a class='btn btn-success btn-sm'
								 href="#"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>
									
									</tr>
								<?}
								}?>
								
								<?
								if ($_SESSION['tcs_empsrno'] == 83815)
								{ //condition to check second stage of approval process
							$sql_project = select_query_json("select to_char(ah.APPDATE,'dd-MM-yyyy HH:mi:ss AM') APPDATE,am.*  from approval_project_master am ,approval_project_hierarchy ah  where ah.prmscod = am.prmscod and ah.EMPSRNO = 83815 and ah.APPSTAT = 'Y' ORDER BY ah.PRMSCOD","Centra","TEST");
								
      								for($project_i = 0; $project_i < count($sql_project); $project_i++) {
										$row = 1;
									?>
									<tr>
									<td><? echo ($project_i+1);?></td>
									<td><? echo $sql_project[$project_i]['PRMSCOD'];?></td>
									<td><? echo $sql_project[$project_i]['PRJNAME'];?></td>
									<td><? echo $sql_project[$project_i]['BRNNAME'];?></td>
									<td><? if ($sql_project[$project_i]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								}?></td>
									<td><? echo $sql_project[$project_i]['APPDATE'];?></td>
									<td><a class='btn btn-success btn-sm'
								 href="#"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>
									
									</tr>
								<?}
								}?>
								
								<?
								if ($_SESSION['tcs_empsrno'] == 452)
								{ //condition to check third stage of approval process
							$sql_project = select_query_json("select to_char(ah.APPDATE,'dd-MM-yyyy HH:mi:ss AM') APPDATE,am.*  from approval_project_master am ,approval_project_hierarchy ah  where ah.prmscod = am.prmscod and ah.EMPSRNO = 452 and ah.APPSTAT = 'Y' ORDER BY ah.PRMSCOD","Centra","TEST");
								
      								for($project_i = 0; $project_i < count($sql_project); $project_i++) {
										$row = 1;
									?>
									<tr>
									<td><? echo ($project_i+1);?></td>
									<td><? echo $sql_project[$project_i]['PRMSCOD'];?></td>
									<td><? echo $sql_project[$project_i]['PRJNAME'];?></td>
									<td><? echo $sql_project[$project_i]['BRNNAME'];?></td>
									<td><? if ($sql_project[$project_i]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								}?></td>
									<td><? echo $sql_project[$project_i]['APPDATE'];?></td>
									<td><a class='btn btn-success btn-sm'
								 href="#"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>
									
									</tr>
								<?}
								}?>
								
								<?
								if ($_SESSION['tcs_empsrno'] == 21344)
								{ //condition to check final stage of approval process
							$sql_project = select_query_json("select to_char(ah.APPDATE,'dd-MM-yyyy HH:mi:ss AM') APPDATE,am.*  from approval_project_master am ,approval_project_hierarchy ah  where ah.prmscod = am.prmscod and ah.EMPSRNO = 21344 and ah.APPSTAT = 'Y' ORDER BY ah.PRMSCOD","Centra","TEST");
								
      								for($project_i = 0; $project_i < count($sql_project); $project_i++) {
										$row = 1;
									?>
									<tr>
									<td><? echo ($project_i+1);?></td>
									<td><? echo $sql_project[$project_i]['PRMSCOD'];?></td>
									<td><? echo $sql_project[$project_i]['PRJNAME'];?></td>
									<td><? echo $sql_project[$project_i]['BRNNAME'];?></td>
									<td><? if ($sql_project[$project_i]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								}?></td>
									<td><? echo $sql_project[$project_i]['APPDATE'];?></td>
									<td><a class='btn btn-success btn-sm'
								 href="#"><span class="glyphicon glyphicon-ok"></span> Approved</a></td>
									
									</tr>
								<?}
								}?>
								
								
								
							</tbody>
						
						</table>
					</form>
					</div>
					</div>
					</div>
			</div>
			<div class="tab-pane" id="tab_3">
			 <div style ="padding-top:10px; background: #3f444c;"></div>
			   <div class="page-content-wrap">
					<div class="panel panel-default">
						<div class="panel-heading">
						<h3 class="panel-title">Rejected List</h3>
						</div>
					<div class = "panel-body">
						
					<form role ="form" id ="frm_rejected_list" name="frm_rejected_list" action ='' >
					<div class="non-printable" style='clear:both; border-bottom:1px solid #000; margin-bottom:10px; margin-top: 10px;'></div>
						<table id="frm_rejected_list" class="table datatable" style="font-size: 12px;"  >
							<thead>
								<tr>
								  <th>S No</th>
								  <th>Project ID</th>
								  <th>Project Name</th>
								  <th>Branch</th>
								  <th >Mode</th>
								  <th >Rejected Date</th>
								  <th >Action</th>
								</tr>
							</thead>
							<tbody>
								<?
								$sql_project_reject = select_query_json("select to_char(ap.DELDATE,'dd-MM-yyyy HH:mi:ss AM') DELDATE,ap.* from approval_project_master ap where ap.ADDUSER = '".$_SESSION['tcs_usrcode']."' and ap.DELETED = 'Y' ORDER BY PRMSCOD","Centra","TEST");
								
      								for($project_ij = 0; $project_ij < count($sql_project_reject); $project_ij++) {
										
									?>
								
									<tr>
									<td><? echo ($project_ij+1);?></td>
									<td><? echo $sql_project[$project_ij]['PRMSCOD'];?></td>
									<td><? echo $sql_project[$project_ij]['PRJNAME'];?></td>
									<td><? echo $sql_project[$project_ij]['BRNNAME'];?></td>
									<td><? if ($sql_project[$project_ij]['BRN_PRJ'] == 'P') {
								  echo "NEW PROJECT";
								}else {
								  echo "BRANCH";
								}?></td>
									<td><? echo $sql_project[$project_ij]['DELDATE'];?></td>
									<td><a class='btn btn-warning btn-sm'
								 href="#"><span class="glyphicon glyphicon-remove"></span> Rejected</a></td>
									
									</tr>
								<?}
								
								?>
								
								
								
								
							</tbody>
						
						</table>
					</form>
					</div>
					</div>
					</div>
			</div>
					 
				
			</div>		
		</div>
			
            <!-- PAGE CONTENT WRAPPER -->
					
						
                       
				  
  </div>
			
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <? include "lib/app_footer.php"; ?>
	
	<!--  Modal Designed By Prakash -->
	
	<!-- Modal -->
			
		<!-- Modal for project creation -->
		<div class="modal" id="modal_large" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" style="width:60%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="largeModalHead">Overview</h4>
                    </div>
                    <div class="modal-body">
                       <!--<div class="row pull-right">						
						<a id="history-button" href = "#" data-toggle="modal" data-target="#historyModal" tabindex='2' class="btn btn-info pull-right" title="History"  ><i class="fa fa-history"></i> History</a>
						</div>-->
							<div id="modal-loader" style="display: none; text-align: center;">
						   <!-- ajax loader -->
						   <img src="prakash/ajax-loader.gif">
						   </div>
					   
					   <div id="dynamic-content"></div>
						
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
						<!--<button type="button" class="btn btn-primary">Save changes</button>	-->					
                    </div>
                </div>
            </div>
        </div>
		
		
		<!-- End  -->
		
		

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
    <script type="text/javascript">
	$(document).ready(function() {
        $("#load_page").fadeOut("slow");
        $(".finish_confirm").click( function() {
        });
    });
	
	$(window).load(function() {
		$(".loader").fadeOut("slow");
	});

	//Display popup using button
	
	

		function getProjectDetails(pjid1,pjyr1){
			$(".loader").show();
		var proid = pjid1;
		var proyear = pjyr1;
		//alert(pjid1+" "+pjyr1);
		 //$('#dynamic-content').html(''); // leave this div blank
		 //$('#modal-loader').show();      // load ajax loader on button click
		$('#dynamic-content').html(''); // leave this div blank
		$('#modal-loader').show();
				 $.ajax({
						url: 'prakash/getProject.php',
						type: 'post',
						data: {pjid:proid,pjyr:proyear},
						dataType: 'html',
						success:function(response){
						 console.log(response);
						 $('#dynamic-content').html('');
						 $('#dynamic-content').html(response);
						  // blank before load.
						 $('#modal-loader').hide(); // hide loader  
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
						} 
					});
				 /*.fail(function(){
					  $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
					  //$('#modal-loader').hide();
				 });*/
		$(".loader").fadeOut("fast");
		}

	
	function getHistorytDetails2(pjid2,pjyr2){
		alert("has");
	}
	
	
	
	
	
	
	
	
	//Displaying Edit button based on project code

/*
					$(document).ready(function(){
							function groupTable($rows, startIndex, total){
							if (total === 0){
							return;
							}
							var i , currentIndex = startIndex, count=1, lst=[];
							var tds = $rows.find('th:eq('+ currentIndex +')');
							var ctrl = $(tds[0]);
							lst.push($rows[0]);
								for (i=1;i<=tds.length;i++){
									if (ctrl.text() ==  $(tds[i]).text()){
									count++;
									$(tds[i]).addClass('deleted');
									lst.push($rows[i]);
									}
							else{
								if (count>1){
									ctrl.attr('rowspan',count);
									groupTable($(lst),startIndex+1,total-1)
									}
							count=1;
							lst = [];
							ctrl=$(tds[i]);
							lst.push($rows[i]);
							}
							}
							}
							groupTable($('#tbl_project_list tr:has(th)'),0,10);
							$('#tbl_project_list .deleted').remove();
							});


*/
    // function goToedit()
    //    {
		/*democode
		//console.clear();
				var table = $("table");
				var rows = table.find($("tr"));
				var colsLength = $(rows[0]).find($("th")).length;
				var removeLater = new Array();
				for(var i=0; i<colsLength; i++){
					var startIndex = 0;
					var lastIndex = 0;
					var startText = $($(rows[0]).find("td")[i]).text();
					for(var j=1; j<rows.length; j++){
						var cRow =$(rows[j]);
						var cCol = $(cRow.find("td")[i]);
						var currentText = cCol.text();
						if(currentText==startText){
							cCol.css("background","gray");
						   // console.log(cCol);
							removeLater.push(cCol);
							lastIndex=j;
						}else{
							var spanLength = lastIndex-startIndex;
							if(spanLength>=1){
								//console.log(lastIndex+" - "+startIndex)
								//console.log($($(rows[startIndex]).find("td")[i]))
								$($(rows[startIndex]).find("td")[i]).attr("rowspan",spanLength+1);
							}
							lastIndex = j;
							startIndex = j;
							startText = currentText;
						}

					}
					var spanLength = lastIndex-startIndex;
							if(spanLength>=1){
								//console.log(lastIndex+" - "+startIndex)
								//console.log($($(rows[startIndex]).find("td")[i]))
								$($(rows[startIndex]).find("td")[i]).attr("rowspan",spanLength+1);
							}
					//console.log("---");
				}

				for(var i in removeLater){
					$(removeLater[i]).remove();
				}
					});


		*/


    //     // window.location.href = "/approval-desk-test/admin_project_modify_prakash.php";

    //     }
/*

    function PrintDiv(dataurl) {
        var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
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
    }*/
    
	
</script>
<!-- END SCRIPTS -->
</body>
</html>
