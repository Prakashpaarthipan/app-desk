<?
session_start();
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
//extract($_REQUEST);
if($_SESSION['tcs_userid'] == ""){ ?>
<script>window.location="index.php";</script>
<?php exit();
} 
$menu_name = 'TAILYOU ADMIN';
$inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
if($_SESSION['tcs_empsrno'] != '') {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc", "Centra", 'TCS');
} else {
    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc", "Centra", 'TCS');
}
if($inner_menuaccess[0]['VEWVALU'] == 'N' or $inner_menuaccess[0]['MNUCODE'] == 'VEWVALU') { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
}
if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_update'] != '') 
{
    $sql_empcode = select_query_json("select * from employee_office where EMPCODE='".$txt_empcode."'");

	$sql_employee = select_query_json("select EMPCODE from employee_office where EMPSRNO = ".$slt_employee);

	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "tlu_employee_rights";
	$field_appreq = array();
    $field_appreq['EMPCODE'] = $sql_empcode[0]['EMPCODE'];
    $field_appreq['PRSCODE'] = $txt_prscode;
    $field_appreq['EMPSRNO'] = $sql_empcode[0]['EMPSRNO'];

    
	$field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	
	//print_r($field_appreq);
	$where_appreq = "EMPCODE = '".$hid_reqid."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table
	if($insert_appreq == 1) { ?>
		<script>window.location='tailor.php?';</script>
		<?php
		exit();
	} else { ?>
		<script>window.location='tailor.php?action=edit&reqid=<?=$hid_reqid?>';</script>
		<?php
		exit();
	}
}
if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '') 
{
    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
    $sql_maxsrno = select_query_json("select nvl(Max(EMLSRNO),0)+1 MAXEMLSRNO from tlu_employee_rights", "Centra", 'TCS');
    $sql_empcode = select_query_json("select * from employee_office where EMPCODE='".$txt_empcode."'");
    $sql_employee = select_query_json("select EMPCODE from employee_office where EMPSRNO = ".$slt_employee, "Centra", 'TCS');
    
    // Insert in APPROVAL_email_master Table
    $tbl_appreq = "tlu_employee_rights";
    $field_appreq = array();
    $field_appreq['EMPCODE'] = $sql_empcode[0]['EMPCODE'];
    $field_appreq['PRSCODE'] = $txt_prscode;
	$field_appreq['EMPSRNO'] = $sql_empcode[0]['EMPSRNO'];

    
    $field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    
  //  print_r($field_appreq);
     $insert_appreq = insert_test_dbquery($field_appreq, $tbl_appreq);
    // Insert in APPROVAL_email_master Table
    
    if($insert_appreq == 1) { ?>
        <script>window.location='tailor.php?';</script>
        <?php
        exit();
    } else { ?>
        <script>window.location='tailor.php?action=add';</script>
        <?php
        exit();
    }
}
if($_REQUEST['action'] == 'deleted')
{
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "tlu_employee_rights";
	$field_appreq = array();
	$field_appreq['DELETED'] = 'Y'; // Y - Yes; N - No;
	$field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
$where_appreq = "EMPCODE = '".$hid_reqid."'";
$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table

	if($insert_appreq == 1) { ?>
		<script>window.location='tailor.php?status=delete_success';</script>
		<?php
		//exit();
	} else { ?>
		<script>window.location='tailor.php?status=failure';</script>
		<?php
		//exit();
	}
}
?>
<!DOCTYPE html>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employee:: <?=$site_title?></title>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />

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
    
    
    <link rel="stylesheet" href="css/default.css" type="text/css">

	<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
	
<style type="text/css">

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

<!-- CSS INCLUDE -->
<?  $theme_view = "css/theme-defaults.css";
    if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
<link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
<link href="css/jquery-customselect.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />


<!-- EOF CSS INCLUDE -->
</head>
<body>
    <!-- START PAGE CONTAINER -->
    <div class="page-container page-navigation-toggled page-container-wide page-navigation-top-fixed">

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
           <? {  ?>
            <div id="page-wrapper" style="width:1309px; height: 330px;">
            <div class="row">
                <div class="col-lg-12" style='margin-top:20px;'>
               <h1 class="page-header">TailYou::Employee Management</h1>

                </div>
			<!-- /.panel -->
			<div class="panel-body" style='display:none;'>
				<div id="morris-area-chart"></div>
				<div id="morris-donut-chart"></div>
				<div id="morris-bar-chart"></div>
			</div>
			<? if($_REQUEST['action'] == 'add' or $_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') { 
        $sql_reqid = select_query_json("select EMPCODE,PRSCODE from tlu_employee_rights  where EMPCODE = '".$_REQUEST['reqid']."' order by EMPCODE");
		$sql_reqid1 = select_query_json("select EMPCODE,EMPNAME from employee_office  where EMPCODE = '".$_REQUEST['reqid']."' order by EMPCODE");
		?>
		<form role="form" id='frm_project' name='frm_project' action='' method='post' enctype="multipart/form-data">
			<div class="col-lg-12 col-md-12 tooltip-demo" style='border-right: 1px solid #d4d4d4;'>

				<? if($_REQUEST['status'] == 'failure') { ?>
				<div class="form-group trbg">
					<div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						Failed in Approval Management Creation / updation. Kindly try again!
					</div>
				</div>
				<? } ?>
				<div class='clear clear_both'></div>
<!-- process -->

					<!-- Branch -->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'><span style='color:red'>Employee*</span></label>
						</div>
						<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['EMPCODE']."-".$sql_reqid1[0]['EMPNAME']?>
								<input type='hidden' name='txt_empcode' id='txt_empcode' value='<?=$sql_reqid[0]['EMPCODE']." -".$sql_reqid[0]['EMPNAME']?>'>
							<? } else { ?>
	              <input class="form-control" tabindex='6' style="text-transform: uppercase;" name='txt_empcode' id='txt_empcode' data-toggle="tooltip" onblur="find_tags();" data-placement="top" data-original-title="Assign member" value='<?=$sql_reqid[0]['EMPCODE']." - ".$sql_reqid1[0]['EMPNAME']?>'>

							<? } ?>
						</div>
					</div>
					<div class='clear clear_both'></div>
					<!-- Branch -->
					
					<!-- Section -->
					<div class="form-group trbg">
						<div class="col-lg-3 col-md-3">
							<label style='height:27px;'><span style='color:red'>process*</span></label>
						</div>
						<div class="col-lg-9 col-md-9">
							<? if($_REQUEST['action'] == 'view') { ?>
								: <?=$sql_reqid[0]['PRSCODE']?>
								<input type='hidden' name='txt_prscode' id='txt_prscode' value='<?=$sql_reqid[0]['PRSCODE']?>'>
							<? } else { ?>
								<select class="form-control" tabindex='1' required name='txt_prscode' id='txt_prscode' autofocus required data-toggle="tooltip" data-placement="top" title="Process Code" value='<?=$sql_emp[0]['EMPCODE']." -".$sql_emp[0]['EMPNAME']?>'>
							<option>---Select----</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
								
					</select>
							<? } ?>
						</div>
					</div>
					<div class='clear clear_both'></div>
					<!-- Section -->
				
				<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
					<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
						<? if($_REQUEST['action'] == 'view') { ?><a href='tailor.php'><i class="fa fa-refresh"></i> Back</a><? } elseif($_REQUEST['action'] == 'edit') { ?>

						<input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>

						<button type="submit" name='sbmt_update' onclick='update_detail()' id='sbmt_update'tabindex='2' value='SB' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-save"></i> Update</button>&nbsp;&nbsp;<button type="reset" tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } else { ?><button type="submit" name='sbmt_request' id='sbmt_request' tabindex='2' value='submit' onclick='showMessage()' class="btn btn-default" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;<button type="reset" tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button><? } ?>
					</div>
				<div class='clear clear_both'></div>

			</div>
				
			</div>
		</form>
		<? } else { ?>
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
									<li><a href="tailor.php?action=add">Add</a></li>
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
										
				<? 	$sql_project = select_query_json("select distinct emp.EMPCODE,emp.EMPNAME,tim.PRSCODE from tlu_employee_rights tim,employee_office emp where tim.EMPCODE=emp.EMPCODE"); 
					for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					<tr>
						<td style='text-align:center'><?=($project_i+1)?></td>
						<td style='text-align:center'><?=$sql_project[$project_i]['EMPCODE']."-".$sql_project[$project_i]['EMPNAME']?></td>
						<td style='text-align:center'><?=$sql_project[$project_i]['PRSCODE']?></td>
					    <td style='text-align:center'><?=($sql_project[$project_i]['DELETED'] == 'N')?'Deleted':'Active'?></td>
						<td style='text-align:center'><a href='tailor.php?action=edit&reqid=<? echo $sql_project[$project_i]['EMPCODE']; ?>' title='Edit' alt='Edit'><i class="fa fa-edit"></i> Edit</a> / <a href='tailor.php?action=view&reqid=<? echo $sql_project[$project_i]['EMPCODE']; ?>' title='View' alt='View'><i class="fa fa-eye"></i> View</a> / <a href='javascript:void(0)' id="delete_confirm" onclick="call_confirm(<?=($project_i+1)?>, 'tailor.php?action=deleted&reqid=<? echo $sql_project[$project_i]['EMPCODE']; ?>')" title='Delete' alt='Delete'><i class="fa fa-trash-o"></i> Delete</a></td>
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
	<? } ?>
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
<script src="js/bootstrap.min.js"></script>

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

function showMessage(){
var answer = confirm ("insert successfully.")
if (answer)
window.location="tailor.php";
}

function update_detail(){
		var vurl = "tailor.php";
	
		$.ajax({
            type: "POST",
            url: vurl,
			data:{

				'txt_empcode':$("#txt_empcode").val(),
				'txt_prscode':$("#txt_prscode").val(),

			},
			dataType:'html',
            success: function(data1) {
              alert("updated successfully");
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});

	}
// tooltip demo
$('.tooltip-demo').tooltip({
	selector: "[data-toggle=tooltip]",
	container: "body"
})
// popover demo
$("[data-toggle=popover]")
	.popover()
	
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
</script>
		</body>
		</html>
		