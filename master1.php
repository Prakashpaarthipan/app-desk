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

/*
$menu_name = 'ADMIN DASHBOARD';
$inner_submenu = select_query("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc");
if($_SESSION['tcs_empsrno'] != '') {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc");
} else {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc");
}
if($inner_menuaccess[0][6] == 'N' or $inner_menuaccess[0][6] == '') { ?>
<script>alert("You dont have access to view this"); window.location='index.php';</script>
<?
 exit();
}

if($_SESSION['rights'] == 0 or $_SESSION['auditor_login'] == 1)
	?>
	<script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
	<?
	exit();
} */

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_update'] != '') 
{
	//$sql_employee = select_query("select EMPCODE from employee_office where EMPSRNO = ".$slt_employee);
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "NIGHTDUTY_APPROVAL";
	$field_appreq = array();
	$field_appreq['CMPNAME'] = $cmp_name;
	$field_appreq['CMPADDR'] = $cmp_addr;
	$field_appreq['CONTPER'] = $cmp_contact;
	$field_appreq['MOBILE'] = $cmp_mobile;
	$field_appreq['PHONE'] = $cmp_phone;
	$field_appreq['EMAIL'] = $cmp_email;
    $field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;

	
	//print_r($field_appreq);
	$where_appreq = "EMLSRNO = '".$hid_reqid."'";
	$insert_appreq = update_query($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table

	//exit;
	if($insert_appreq == 1) { ?>
		<script>window.location='master1.php?status=update_success';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='master1.php?action=edit&reqid=<?=$hid_reqid?>&status=failure';</script>
		<?php
		exit();
	}
}

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '') 
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$sql_maxsrno = select_query("select nvl(Max(EMLSRNO),0)+1 MAXEMLSRNO from APPROVAL_email_master");
	$sql_employee = select_query("select EMPCODE from employee_office where EMPSRNO = ".$slt_employee);
	
	// Insert in APPROVAL_email_master Table
	$tbl_appreq = "NIGHTDUTY_APPROVAL";
	$field_appreq = array();
	$field_appreq['CMPNAME'] = $cmp_name;
	$field_appreq['CMPADDR'] = $cmp_addr;
	$field_appreq['CONTPER'] = $cmp_contact;
	$field_appreq['MOBILE'] = $cmp_mobile;
	$field_appreq['PHONE'] = $cmp_phone;
	$field_appreq['EMAIL'] = $cmp_email;
    $field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;

	//print_r($field_appreq);
	$insert_appreq = insert_query($field_appreq, $tbl_appreq);
	// Insert in APPROVAL_email_master Table
	//exit;
	
	if($insert_appreq == 1) { ?>
		<script>window.location='master1.php?status=success';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='master1.php?action=add&status=failure';</script>
		<?php
		exit();
	}
}

if($_REQUEST['action'] == 'deleted')
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "NIGHTDUTY_APPROVAL";
	$field_appreq = array();
	$field_appreq['DELETED'] = 'Y'; // Y - Yes; N - No;
	$field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appreq = "EMLSRNO = '".$hid_reqid."'";
	$insert_appreq = update_query($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table

	if($insert_appreq == 1) { ?>
		<script>window.location='master1.php?status=delete_success';</script>
		<?php
		//exit();
	} else { ?>
		<script>window.location='master1.php?status=failure';</script>
		<?php
		//exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- META SECTION -->
    <title><?=$title_tag?> Requirement Entry :: Approval Desk :: <?php echo $site_title; ?></title>
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
                <li class="active">Requirement Entry</li>
            </ul>
        <div id="page-wrapper" style='min-height:700px !important; height:auto;'>
            <div class="row">
                <div class="col-lg-12" style='margin-top: 70px;'>
                    <h3 class="page-header">Approval Desk Process - Night Duty For Contractors</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
			
			<!-- /.panel -->
			<div class="panel-body" style='display:none;'>
				<div id="morris-area-chart"></div>
				<div id="morris-donut-chart"></div>
				<div id="morris-bar-chart"></div>
			</div>
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Approval Desk Process :: Night Duty  <?=$site_title?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">
	
	<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
	<script type="text/javascript">
	$(document).ready( function() {
		$(".delete_confirm").click( function() 
		{
		});
	});
	
	function call_confirm(ivalue, sendurl)
	{
		jConfirm('Are you sure to want to delete this!', 'Confirmation Dialog', 
		function(r) {
			if(r == true)
			{
				window.location=sendurl;
			}
		});
	}
	</script>

	<style>
	.col-lg-12 { padding-right:0px; }
	.panel-body {
		padding: 10px !important;
	}
	.table { margin-bottom: 0px; }
	#page-wrapper { padding: 0 15px 0 15px; }
	</style>
	
    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            
			<? include("lib/app_header.php"); ?>
            <!-- /.navbar-header -->

			<? include("lib/app_notification.php"); ?>
            <!-- /.navbar-top-links -->

			<? include("lib/app_leftpanel.php"); ?>
            <!-- /.navbar-static-side -->
			
        </nav>

		<? if($inner_menuaccess[0][6] == 'Y') { ?>
        <div id="page-wrapper" style='min-height:700px !important; height:auto;'>
            <div class="row">
                <div class="col-lg-12" style='margin-top: 70px;'>
                    <h1 class="page-header">Approval Desk Process - Night Duty For Contractors</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
			
			<!-- /.panel -->
			<div class="panel-body" style='display:none;'>
				<div id="morris-area-chart"></div>
				<div id="morris-donut-chart"></div>
				<div id="morris-bar-chart"></div>
			</div>
			
			
			
		<form role="form" id='frm_project' name='frm_project' action='' method='post' enctype="multipart/form-data">
			<? if($_REQUEST['action'] == 'add' or $_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') { 
				$sql_reqid = select_query("select eml.*, brn.*, des.*, sec.*, emp.*, substr(brn.NICNAME,3,5) branch 
													from APPROVAL_email_master eml, BRANCH brn, DESIGNATION des, empsection sec, employee_office emp 
													where eml.brncode = brn.brncode and sec.esecode = eml.esecode and emp.descode = des.descode and emp.empsrno = eml.empsrno and brn.deleted = 'N' and 
														sec.deleted = 'N' and des.deleted = 'N' and eml.emlsrno = '".$_REQUEST['reqid']."' 
													order by eml.adddate desc");
			?>
                <div class="col-lg-12 col-md-12 tooltip-demo" style='border-right: 1px solid #d4d4d4;'>

					<? if($_REQUEST['status'] == 'failure') { ?>
					<div class="form-group trbg">
						<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Failed in Email Master Creation / updation. Kindly try again!
						</div>
					</div>
					<? } ?>
					<div class='clear clear_both'></div>

					
					<!-- company name -->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'>COMPANY NAME<span style='color:red'>*</span></label>
						</div>
                         <div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='text' name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE COMPANY NAME" tabindex='4' required autocomplete="on" maxlength='40' name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Company Name">
							<? } ?>
						</div>
	</div>
					<div class='clear clear_both'></div>
					<!-- company name -->
					
					<!-- address -->
										<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'>ADDRESS<span style='color:red'>*</span></label>
						</div>
                         <div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='text' name='cmp_addr' id='cmp_addr' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE ADDRESS" tabindex='4' required autocomplete="on" maxlength='40' name='cmp_addr' id='cmp_addr' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Address">
							<? } ?>
						</div>
	</div>
					<div class='clear clear_both'></div>
					
					
									<!-- address -->
					
					
					<div id="id_employee">
						<!-- contact person -->
						<div class="form-group trbg">
							<div class="col-lg-3 col-md-3">
								<label style='height:27px;'>CONTACT PERSON <span style='color:red'>*</span></label>
							</div>
							<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='text' name='cmp_contact' id='cmp_contact' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE PERSON NAME" type='text' tabindex='4' required autocomplete="on" maxlength='50' name='cmp_contact' id='cmp_contact' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Contact Person">
							<? } ?>
						</div>

							</div>
						</div>
						<div class='clear clear_both'></div>
						<!-- contact person -->
					</div>
					
					<!-- mobile  -->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'>MOBILE <span style='color:red'>*</span></label>
						</div>
						<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='number' name='cmp_mobile' id='cmp_mobile' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE MOBILE NUMBER" type='text' tabindex='4' required autocomplete="on" maxlength='10' name='cmp_mobile' id='cmp_mobile' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Mobile"><br>
							<? } ?>
						</div>
					</div>
					<div class='clear clear_both'></div>
					<!--mobile-->
					
					<!--phone-->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'>PHONE<span style='color:red'>*</span></label>
						</div>
						<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='number' name='cmp_phone' id='cmp_phone' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE PHONE NUMBER" type='text' tabindex='4' required autocomplete="on" maxlength='15' name='cmp_phone' id='cmp_phone' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Phone">
							<? } ?>
						</div>
					</div>
					<div class='clear clear_both'></div>
					<!--phone-->
					
					<!--email-->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'>EMAIL<span style='color:red'>*</span></label>
						</div>
						<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMAILID']?>
								<input type='email' name='cmp_email' id='cmp_email' value='<?=$sql_reqid[0]['EMAILID']?>'>
							<? } else { ?>
								<input class="form-control" placeholder="ENTER THE EMAIL ID" type='text' tabindex='4' required autocomplete="on" maxlength='100' name='cmp_email' id='cmp_email' value='<?=$sql_reqid[0]['EMAILID']?>' data-toggle="tooltip" data-placement="top" title="Email ID">
							<? } ?>
						</div>
					</div>
					<div class='clear clear_both'></div>
					
					
					<!-- Email ID -->
					
					<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
						<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
							<? if($_REQUEST['action'] == 'view') { ?><a href='master1.php' class='btn btn-warning'><i class="fa fa-refresh"></i> Back</a><? } elseif($_REQUEST['action'] == 'edit') { ?>
							<input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
							<button type="submit" name='sbmt_update' id='sbmt_update' tabindex='5' value='SB' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-save"></i> Update</button>&nbsp;&nbsp;<button type="reset" tabindex='6' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } else { ?><button type="submit" name='sbmt_request' id='sbmt_request' tabindex='5' value='submit' class="btn btn-default" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;<button type="reset" tabindex='6' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } ?>
						</div>
					<div class='clear clear_both'></div>

				</div>
					
				</div>
			
			<? } else { ?>
			<!-- /.row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
				
					<? if($_REQUEST['status'] == 'delete_success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Email Master Deleted Successfully
						</div>
					</div>
					<? } 
					
					if($_REQUEST['status'] == 'failure') { ?>
					<div class="form-group trbg">
						<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Failed in Email Master Creation / updation. Kindly try again!
						</div>
					</div>
					<? }
					
					if($_REQUEST['status'] == 'update_success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Email Master Updated Successfully
						</div>
					</div>
					<? } 
					
					if($_REQUEST['status'] == 'success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Email Master Created Successfully
						</div>
					</div>
					<? } ?>
				
				<div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-envelope-o fa-fw"></i> Email Master
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="master1.php?action=add">Add</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th style='text-align:center'>#</th>
											<th style='text-align:center'>COMPANY NAME</th>
											<th style='text-align:center'>ADDRESS</th>
											<th style='text-align:center'>CONTACT PERSON</th>
											<th style='text-align:center'>MOBILE</th>
											<th style='text-align:center'>PHONE</th>
											<th style='text-align:center'>EMAIL</th>
											<th style='text-align:center'>Action</th>
										</tr>
									</thead>
									<tbody>
									
									<? $sql_emailmaster = select_query("select eml.*, brn.*, des.*, sec.*, emp.*, substr(brn.NICNAME,3,5) branch 
																				from APPROVAL_email_master eml, BRANCH brn, DESIGNATION des, empsection sec, employee_office emp 
																				where eml.brncode = brn.brncode and sec.esecode = eml.esecode and emp.descode = des.descode and emp.empsrno = eml.empsrno and 
																					brn.deleted = 'N' and sec.deleted = 'N' and des.deleted = 'N' 
																				order by eml.EMLSRNO desc, eml.adddate desc"); 
										for($emailmaster_i = 0; $emailmaster_i < count($sql_emailmaster); $emailmaster_i++) { ?>
										<tr>
											<td style='text-align:center'><?=($emailmaster_i+1)?></td>
											<td style='text-align:center'><?=$sql_emailmaster[$emailmaster_i]['BRANCH']?></td>
											<td style='text-align:center'><?=$sql_emailmaster[$emailmaster_i]['ESENAME']?></td>
											<td style='text-align:center'><?=$sql_emailmaster[$emailmaster_i]['EMPNAME']?> (<?=$sql_emailmaster[$emailmaster_i]['EMPCODE']?>)</td>
											<td style='text-align:center'><?=$sql_emailmaster[$emailmaster_i]['DESNAME']?></td>
											<td style='text-align:center'><?=$sql_emailmaster[$emailmaster_i]['EMAILID']?></td>
											
											<td style='text-align:center'><?=($sql_emailmaster[$emailmaster_i]['DELETED'] == 'N')?'Active':'Deleted'?></td>
											<td style='text-align:center'><a href='master1.php?action=edit&reqid=<? echo $sql_emailmaster[$emailmaster_i]['EMLSRNO']; ?>' title='Edit' alt='Edit'><i class="fa fa-edit"></i> Edit</a> / <a href='master1.php?action=view&reqid=<? echo $sql_emailmaster[$emailmaster_i]['EMLSRNO']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a> / <a href='javascript:void(0)' id="delete_confirm" onclick="call_confirm(<?=($emailmaster_i+1)?>, 'master1.php?action=deleted&reqid=<? echo $sql_emailmaster[$emailmaster_i]['EMLSRNO']; ?>')" title='Delete' alt='Delete'><i class="fa fa-trash-o"></i> Delete</a></td>
										</tr>
									<? } ?>
									
									</tbody>
								</table>
                                <!-- /.col-lg-4 (nested) -->
								</div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                </div>
			<? } ?>
			
				<div class="col-lg-6 col-md-12">
					<div class="form-group">
						
					</div>
				</div>
				
			</form>
			
		</div>
		<? } ?>
        <!-- /#page-wrapper -->


    </div>
	<div class='clear'></div>
	
	<div>
		<? include("lib/app_footer.php"); ?>
	</div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
	
    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

    <!-- DataTables JavaScript -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
	
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
	<!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
	$(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
	
	function checkform()
	{
		if (frm_project.slt_branch.value == "") {
			alert( "Please choose any branch" );
			frm_project.slt_branch.focus();
			return false ;
		}
		if (frm_project.slt_designation.value == "") {
			alert( "Please choose any designation" );
			frm_project.slt_designation.focus();
			return false ;
		}
		if (frm_project.txt_expfromyear.value == "") {
			alert( "Please enter any experience from year" );
			frm_project.txt_expfromyear.focus();
			return false ;
		}
		if (frm_project.txt_exptoyear.value == "") {
			alert( "Please enter any experience to year" );
			frm_project.txt_exptoyear.focus();
			return false ;
		}
		if (frm_project.txt_sovqty.value == "") {
			alert( "Please enter any qty for sovereign" );
			frm_project.txt_sovqty.focus();
			return false ;
		}
		if (frm_project.txt_trustamt.value == "") {
			alert( "Please enter any amount from AKNC Trust" );
			frm_project.txt_trustamt.focus();
			return false ;
		}
		return true;
	}

    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

	function getemployee(employeeid) {
		var strURL1="getemployee.php?employeeid="+employeeid;
		var req1 = getXMLHTTP();
		if (req1) {
			req1.onreadystatechange = function() {
				if (req1.readyState == 4) {
					if (req1.status == 200) {
						document.getElementById('id_employee').innerHTML = req1.responseText;
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
					}
				}
			}
			req1.open("GET", strURL1, true);
			req1.send(null);
		}
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

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>

</body>

</html>
