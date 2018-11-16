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

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_update'] != '') 
{
	

	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$tbl_appreq = "approval_contractors";
	$field_appreq = array();
	$field_appreq['CMPCODE'] = $cmp_code;
	$field_appreq['CMPNAME'] = $cmp_name;
	$field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; 
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;

	
	$where_appreq = "CMPSRNO = '".$hid_reqid."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);


	if($insert_appreq == 1) { ?>
		<script>window.location='contractor_employee.php?status=update_success';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='contractor_employee.php?action=edit&reqid=<?=$hid_reqid?>&status=failure';</script>
		<?php
		exit();
	}
}
if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '') 
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$max_val = select_query_json("select count(*) max from approval_contractors", "Centra", 'TEST');
	$tbl_appreq = "approval_contractors";
	$field_appreq = array();
    $field_appreq['CMPCODE'] = $cmp_code;
	$field_appreq['CMPNAME'] = $cmp_name;
	$field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
print_r($field_appreq);
	$insert_appreq = insert_test_dbquery($field_appreq, $tbl_appreq);
	
	if($insert_appreq == 1) { ?>
		<script>window.location='contractor_employee.php?status=success';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='contractor_employee.php?action=add&status=failure';</script>
		<?php
		exit();
	}
}
if($_REQUEST['action'] == 'blur')
{
	if($sql_reqid2[0]['MAX'] !=0)
	{?>
		<script>window.location='contractor_employee.php?action=add&status=failure';</script>
	<?}
	else{
		
	}
}

if($_REQUEST['action'] == 'deleted')
{
	

	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$tbl_appreq = "approval_contractors";
	$field_appreq = array();
	$field_appreq['DELETED'] = 'Y';
	$field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appreq = "EMPCODE = '".$hid_reqid."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);

if($insert_appreq == 1) { ?>
		<script>window.location='contractor_employee.php?status=delete_success';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='contractor_employee.php?status=failure';</script>
		<?php
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title><?=$title_tag?>WORKDONE REPORT :: <?php echo $site_title; ?></title>
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
     <link href="css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
    <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    <script src="js/angular.js"></script>
	<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

.col-lg-12 {
		padding-right:0px;
		}
	.panel-body {
		padding: 10px !important;
		margin: 4px 4px 12px 0;

	}
	
	.table {
		margin-bottom: 0px;
		}
	#page-wrapper { 
	position: relative;
    float: right;
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
    textarea {
        color: #333;
        font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
        line-height: 18px;
        font-weight: 400;
    }

	.dataTables_length {
			width:40%;
			float:left;
			padding-right: 5px;
		    padding: 0px 0px 5px;
			border-bottom: none !important;
			font-size: 15px;
		}

	.dataTables_filter {
		width: 50%;
		float: right;
		padding-left: 5px;
		padding: 0px 0px 5px;
		border-bottom: none !important;
		font-size: 15px;
	}
</style>

	
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
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
			<? //sif( $_REQUEST['action'] == 'nedit' )  {

//$sql_reqid = select_query_json("select taskdet,enttime,outtime,empsrno from task_grade where tassrno = '".$_REQUEST['reqid']."'","Centra","TEST");} ?>  
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" method="post" enctype="multipart/form-data">
			<!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
					<div class="page-content-wrap">
                        <input type="hidden" id="tassrno" val=""/>

						<div class="row">
							<div class="col-md-12">

								<form class="form-horizontal">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><strong>Approval Contractors</strong></h3>
										<ul class="panel-controls">
											<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
										</ul>
									</div>

			<? if($_REQUEST['action'] == 'add' or $_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') { 
			
        $sql_reqid = select_query_json("select * from APPROVAL_CONTRACTORS where CMPCODE = '".$_REQUEST['reqid']."'", "Centra", 'TEST' );
		//$sql_reqid1 = select_query_json("select EMPCODE,EMPNAME from employee_office  where EMPCODE = '".$_REQUEST['reqid']."' order by EMPCODE");
			
		?>
		<form role="form" id='frm_project' name='frm_project' action='' method='post' enctype="multipart/form-data">
			<div class="col-lg-12 col-md-12 tooltip-demo" style='border-right: 1px solid #d4d4d4;'>

				<? if($_REQUEST['status'] == 'failure') { ?>
				<div class="form-group trbg">
					<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					                    Employee detail Failed To Create!
					</div>
				</div>
				<? } ?>
				<div class='clear clear_both'></div>
	<div class="panel-body">


										<div class="row">
											<div class="col-md-6">  
<!-- company code -->
					<div class="form-group">
                    <label class="col-md-3 control-label">COMPANY CODE<span style='color:red'>*</span></label>
                    <div class="col-md-9 col-xs-12">
				    <? if($_REQUEST['action'] == 'view') { ?>
                              :<b><input id="text" name='cmp_code' id='cmp_code' value='<?=$sql_reqid[0]['CMPNAME']?>' size="70" disabled></b><br><br>

					<? } else { ?>
                              <input class="form-control" placeholder="ENTER THE COMPANY CODE" tabindex='4' required autocomplete="on" maxlength='50' name='cmp_code' id='cmp_code' value='<?=$sql_reqid[0]['CMPNAME']?>' data-toggle="tooltip" data-placement="top" title="Company Name"><br><br>
                    <? } ?>
                    </div>
                    </div>
<!-- company code -->

<!-- company name -->
					<div class="form-group">
                    <label class="col-md-3 control-label">COMPANY NAME<span style='color:red'>*</span></label>
                    <div class="col-md-9 col-xs-12">
				    <? if($_REQUEST['action'] == 'view') { ?>
                              :<b><input id="text" name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['CMPNAME']?>' size="70" disabled></b><br><br>

					<? } else { ?>
                              <input class="form-control" placeholder="ENTER THE COMPANY NAME" tabindex='4' required autocomplete="on" maxlength='50' name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['CMPNAME']?>' data-toggle="tooltip" data-placement="top" title="Company Name"><br><br>
                    <? } ?>
                    </div>
                    </div>
<!-- company name -->
			                
				<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
					<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
						<? if($_REQUEST['action'] == 'view') { ?><a href='contractor_employee.php'><i class="fa fa-refresh"></i> Back</a><? } elseif($_REQUEST['action'] == 'edit') { ?>

						<input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>

						<button  name='sbmt_update' onclick='update_detail()' id='sbmt_update'tabindex='2' value='SB' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-save"></i> Update</button>&nbsp;&nbsp;<button type="reset" tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } else { ?><button name='sbmt_request' id='sbmt_request' tabindex='2' value='submit' onclick='showMessage()' class="btn btn-default" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;<button tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } ?>
					</div>
				<div class='clear clear_both'></div>

			</div>
				
			</div>
		</form>
				
		<? } else { ?>
		<!-- /.row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
				
					<? if($_REQUEST['status'] == 'delete_success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Tail You Employee Deleted Successfully
						</div>
					</div>
					<? } 
					
					if($_REQUEST['status'] == 'failure') { ?>
					<div class="form-group trbg">
						<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								Your Employee details Already Exist!

						</div>
					</div>
					<? }
					
					if($_REQUEST['status'] == 'update_success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Employee Details Updated Successfully
						</div>
					</div>
					<? } 
					
					if($_REQUEST['status'] == 'success') { ?>
					<div class="form-group trbg">
						<div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							Your Employee Details Created Successfully
						</div>
					</div>
					<? } ?>
				
			<div class="panel panel-default">
					<div class="panel-heading">
						<center><i class="fa fa-check-circle-o fa-fw"></i>TailYou Employee Management</center>
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
									Actions
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right" role="menu">
									<li><a href="contractor_company.php?action=add">Add</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
										<thead>
											<tr>
												<th style='text-align:center'>S.No</th>
												<th style='text-align:center'>Employee</th>
												<th style='text-align:center'>Process</th>
												<th style='text-align:center'>Status</th>
												<th style='text-align:center'>Action</th>
											</tr>
										</thead>
										<tbody>
										
				<? 	$sql_project = select_query_json("select distinct emp.EMPCODE,emp.EMPNAME,tim.PRSCODE,tim.TLUSRNO from tailu_emp tim,employee_office emp where tim.EMPCODE=emp.EMPCODE"); 
					for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					<tr>
						<!--<td style='text-align:center'><?=($project_i+1)?></td>-->
												<td style='text-align:center'><?=$sql_project[$project_i]['TLUSRNO']?></td>

						<td style='text-align:center' id='alert'><?=$sql_project[$project_i]['EMPCODE']."-".$sql_project[$project_i]['EMPNAME']?></td>
						<td style='text-align:center'><?=$sql_project[$project_i]['PRSCODE']?></td>
					    <td style='text-align:center'><?=($sql_project[$project_i]['DELETED'] == 'N')?'Deleted':'Active'?></td>
						<td style='text-align:center'><a href='contractor_company.php?action=edit&reqid=<? echo $sql_project[$project_i]['TLUSRNO']; ?>' title='Edit' alt='Edit'><i class="fa fa-edit"></i> Edit</a> / <a href='contractor_company.php?action=view&reqid=<? echo $sql_project[$project_i]['TLUSRNO']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a> / <a href='javascript:void(0)' id="delete_confirm" onclick="call_confirm(<?=($project_i+1)?>, 'contractor_company.php?action=deleted&reqid=<? echo $sql_project[$project_i]['TLUSRNO']; ?>')" title='Delete' alt='Delete'><i class="fa fa-trash-o"></i> Delete</a></td>
					</tr>
				<? } ?>
				
										
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.col-lg-4 (nested) -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.panel-body -->
				</div>
			</div>
			</div>
		<? } ?>
		
	</div>

	<!-- /#page-wrapper -->


</div>
<div class='clear'></div>

<div>
	<? include("lib/app_footer.php"); ?>
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

<!-- Bootstrap Core JavaScript -->
<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>
<script src="js/custom.js" type="text/javascript"></script>
 <script type="text/javascript" src="js/jquery-customselect.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="js/plugins/metisMenu/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>

<!-- Custom Theme JavaScript -->
<script src="js/sb-admin-2.js"></script>


<script>
$(document).ready(function() {
	$('#dataTables-example').dataTable();
});

function checkform()
 {
 	if (frm_project.txt_empcode.value == "") {
 		alert( "Please enter the Employee Code" );
 		frm_project.txt_empcode.focus();
 		return false ;
 	}
 		if (frm_project.txt_prscode.value == "") {
 		alert( "Please choose the Grade" );
		frm_project.txt_prscode.focus();
		return false ;
		}
 	return true;
 }
 $(document).ready(function(){
    $("input").blur(function(){
		var txt=$("#txt_empcode").val();
		var res = txt.split(" - ");
		$.post("blur.php?action=blur&em="+res[0], function(result) {
	if(result==1)	{
		alert("exist");
		
	}
else if	(result==0)
{
	alert("new User");
}

console.log(result);			
			
		});
    });
});

	$(document).ready(function() {
        $(".chosn").customselect();

	$('#txt_empcode').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'ajax/ajax_employee_details.php',
				dataType: "json",
				data: {
				   name_startsWith: request.term,
				   // topcr: $('#slt_topocore').val(),
				   // subcr: $('#slt_subcore').val(),
				   type: 'employee'
				},
				success: function( data ) {
					if(data == 'No User Available in this Top core and Sub Core') {
						var ALERT_TITLE = "Message";
						var ALERTMSG = "No User Available in this Top core. Kindly Change this!!";
						createCustomAlert(ALERTMSG, ALERT_TITLE);
					} else {
						response( $.map( data, function( item ) {
							return {
								label: item,
								value: item
							}
						}));
					}
				}
			});
		},
		autoFocus: true,
		minLength: 0
		});
    });
	
</script>
		</body>
		</html>
		