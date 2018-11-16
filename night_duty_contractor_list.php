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
	//$expl = explode(" - ", $txt_empcode);

   //$sql_empcode = select_query_json("select EMPCODE,EMPSRNO from employee_office where EMPCODE='".$expl[0]."'");

	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "approval_contractors";
	$field_appreq = array();
	$field_appreq['CMPNAME'] = strtoupper($cmp_name);
	$field_appreq['CONADDR'] = strtoupper($con_addr);
	$field_appreq['COMOBILE'] = $con_mobile;
	$field_appreq['ADDUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['EDTUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
    $field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
    $field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;

	//print_r($field_appreq);
	$where_appreq = "CMPCODE = '".$_REQUEST['reqid']."' ";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table
//exit
	if($insert_appreq == 1) { ?>
		<script>window.location='night_duty_contractor_list.php?status=update_success';
		alert("Contractor Entry saved");
		</script>
		<?php
	exit();
	} else { ?>
		<script>window.location='night_duty_contractor_list.php?action=edit&reqid=<?=$hid_reqid?>&status=failure';
		alert("Contractor Entry failed");
		</script>
		<?php
		exit();
	}
}


if($_REQUEST['action'] == 'deleted')
{
	//$sql_empcode = select_query_json("delete from APPROVAL_CONTRACTORS where CMPCODE = '".$_REQUEST['reqid']."'", "Centra", 'TEST');

	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	// Update in APPROVAL_email_master Table
	$tbl_appreq = "approval_contractors";
	$field_appreq = array();
	$field_appreq['DELETED'] = 'Y'; // Y - Yes; N - No;
	$field_appreq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_appreq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $where_appreq = "CMPCODE = '".$_REQUEST['reqid']."'";
	$insert_appreq = update_test_dbquery($field_appreq, $tbl_appreq, $where_appreq);
	// Update in APPROVAL_email_master Table
if($insert_appreq == 1) { ?>
		<script>window.location='night_duty_contractor_list.php?status=delete_success';
		
		alert("Contractor Details Delete Successfully");
		</script>
		<?php
		//exit();
	} else { ?>
		<script>window.location='night_duty_contractor_list.php?status=failure';
		
		alert("Failed to Delete Contractor Details");
		</script>
		<?php
		//exit();
	}
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title><?=$title_tag?>APPROVAL CONTRACTORS :: <?php echo $site_title; ?></title>
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
	#sbmt_update
	{
	background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
	}
	#sbmt_reset
	{
	background-color: #4CAF50;
    border: none;
    color: white;
    padding: 10px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
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

									<? if( $_REQUEST['action'] == 'edit' or $_REQUEST['action'] == 'view') { 
			
        $sql_reqid = select_query_json("select * from APPROVAL_CONTRACTORS where CMPCODE = '".$_REQUEST['reqid']."'", "Centra", 'TEST' );
									
		//$sql_reqid1 = select_query_json("select EMPCODE,EMPNAME from employee_office  where EMPCODE = '".$_REQUEST['reqid']."' order by EMPCODE");
			
		?>

											
												<div class="panel-body">


								

<!-- company name -->
					<div class="form-group">
                    <label class="col-md-2 control-label">COMPANY NAME<span style='color:red'>*</span></label>
                    <div class="col-md-5 col-xs-5">
				   <? if($_REQUEST['action'] == 'view') { ?>
                              :<b><input id="text" name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['CMPNAME']?>' size="70" disabled></b><br><br>

					<? } else { ?>
                              <input style=" text-transform: uppercase;" class="form-control" placeholder="ENTER THE COMPANY NAME" tabindex='4' required autocomplete="on" maxlength='50' name='cmp_name' id='cmp_name' value='<?=$sql_reqid[0]['CMPNAME']?>' data-toggle="tooltip" data-placement="top" title="Company Name"><br><br>
                    <? } ?>
                     </div>
                    </div>
<!-- company name -->
<!-- company address -->
					<div class="form-group">
                    <label class="col-md-2 control-label">COMPANY ADDRESS<span style='color:red'>*</span></label>
                    <div class="col-md-5 col-xs-5">
					<? if($_REQUEST['action'] == 'view') { ?>
                              :<b><input id="text" name='con_addr' id='con_addr' value='<?=$sql_reqid[0]['CONADDR']?>' size="70" disabled></b><br><br>

					<? } else { ?>
                              <input style=" text-transform: uppercase;" class="form-control" placeholder="ENTER THE COMPANY ADDRESS" tabindex='4' required autocomplete="on" maxlength='50' name='con_addr' id='con_addr' value='<?=$sql_reqid[0]['CONADDR']?>' data-toggle="tooltip" data-placement="top" title="Company Address"><br><br>
                    <? } ?>
                    </div>
                    </div>
<!-- company address -->

<!-- company mobile -->
					<div class="form-group">
                    <label class="col-md-2 control-label">MOBILE<span style='color:red'>*</span></label>
                    <div class="col-md-5 col-xs-5">
					<? if($_REQUEST['action'] == 'view') { ?>
                              :<b><input id="text" name='con_mobile' id='con_mobile' value='<?=$sql_reqid[0]['COMOBILE']?>' size="70" disabled></b><br><br>

					<? } else { ?>
                              <input style=" text-transform: uppercase;" class="form-control" placeholder="ENTER THE MOBILE NUMBER" tabindex='4' required autocomplete="on" maxlength='10' name='con_mobile' id='con_mobile' value='<?=$sql_reqid[0]['COMOBILE']?>' data-toggle="tooltip" data-placement="top" title="Mobile"><br><br>
                    <? } ?>
                    </div>
                    </div>
<!-- company mobile -->

			                

				
<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
					<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
				

				

						<button  name='sbmt_update' onclick='update_detail()' id='sbmt_update'tabindex='2' value='SB' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-save"></i> Update</button>&nbsp;&nbsp;<button tabindex='3' id="sbmt_reset" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button> 	
						</div>
				<div class='clear clear_both'></div>
							
				
			</div>
									
					</div>		
									<? } ?>					
</form>
<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">
										<thead>
											<tr>
											<!--	<th style='text-align:center'>S.No</th> -->
												<th style='text-align:center'>Company Code</th>
												<th style='text-align:center'>Company Name</th>
												<th style='text-align:center'>Company Address</th>
												<th style='text-align:center'>Mobile</th>
												<th style='text-align:center'>Action</th>
											</tr>
										</thead>
										<tbody>
										
				<? 	$sql_project = select_query_json("select CMPCODE,CMPNAME,CONADDR,COMOBILE from APPROVAL_CONTRACTORS WHERE deleted='N' order by CMPCODE", "Centra", 'TEST' ); 
					for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
					<tr>
						<td style='text-align:center'><?=($project_i+1)?></td>
						<td><?=$sql_project[$project_i]['CMPNAME']?></td>
						<td><?=$sql_project[$project_i]['CONADDR']?></td>
						<td style='text-align:center'><?=$sql_project[$project_i]['COMOBILE']?></td>
						<td style='text-align:center'><a href='night_duty_contractor_list.php?action=edit&reqid=<? echo $sql_project[$project_i]['CMPCODE']; ?>' onclick="return confirm('Are you sure you want to Edit?');" title='Edit' alt='Edit' ><i class="fa fa-edit"></i> Edit</a> / <a href='night_duty_contractor_list.php?action=deleted&reqid=<? echo $sql_project[$project_i]['CMPCODE']; ?>' title='Delete' alt='Delete'><i class="fa fa-trash-o"></i> Delete</a></td>
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
		
		
		
		
		
		