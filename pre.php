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

if($_REQUEST['action'] == "edit"){ ?>
    <script>window.location="request_list.php";</script>
<?php exit();
}

$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
$sysip = $_SERVER['REMOTE_ADDR'];
$cur_mn = date("m");

if($_SESSION['auditor_login'] == 1) { ?>
    <script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
<? exit();
}

$sql_reqid = select_query_json("select req.*, prj.APRNAME, top.ATCNAME, typ.ATYNAME, apm.APMNAME, substr(brn.nicname,3,15) branch, emp.EMPCODE, emp.EMPNAME, ast.DEPNAME, ast.EXPNAME, ast.EXPSRNO,
                                            to_char(req.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time, to_char(req.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time,
                                            to_char(req.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time, to_char(req.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time,
                                            to_char(req.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, req.ATCCODE ATCCCODE
                                        from APPROVAL_REQUEST req, approval_type typ, approval_project prj, approval_topcore top, branch brn, approval_master apm, department_asset ast,
                                            employee_office emp
                                        where req.ATYCODE = typ.ATYCODE and req.APRCODE = prj.APRCODE and req.ATCCODE = top.ATCCODE and req.APMCODE = apm.APMCODE and req.BRNCODE = brn.BRNCODE and
                                            req.DEPCODE = ast.DEPCODE and req.deleted = 'N' and brn.DELETED = 'N' and ast.DELETED = 'N' and emp.empsrno = req.ADDUSER and brn.BRNMODE in ('B', 'K', 'T')
                                            and req.ARQCODE = '".$_REQUEST['reqid']."' and req.ARQYEAR = '".$_REQUEST['year']."' and req.ARQSRNO = '".$_REQUEST['rsrid']."'
                                            and req.ATCCODE = '".$_REQUEST['creid']."' and req.ATYCODE = '".$_REQUEST['typeid']."'
                                        order by req.ARQCODE, req.ARQSRNO, req.ATYCODE", "Centra", 'TEST');

if($sql_rdsrno[0]['CNT'] > 1 && $action == 'edit') { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="request_list.php";</script>
<? exit();
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
   <!DOCTYPE html>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Employee:: <?=$site_title?></title>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- END META SECTION -->
    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    <link href="css/jquery-customselect.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
</head>
<body>
    <div id="load_page" style='display:block;padding:12% 40%;'></div>

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

            <!-- PAGE CONTENT WRAPPER -->
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="viki/insert_req.php" method="post" enctype="multipart/form-data">
			<!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
					<div class="page-content-wrap">

						<div class="row">
							<div class="col-md-12">

								
												<div class="form-group">
                            <label class="col-md-3 control-label">Assignn Member</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <input type='text' class="form-control" tabindex='6' style="text-transform: uppercase;" name='txt_assign' id='txt_assign' data-toggle="tooltip" onblur="find_tags();" data-placement="top" data-original-title="Assign member" >														     <span class="help-block">NOTE : ASSIGN THE MEMBER</span>
                            </div>
                        </div>

												
    <? include "lib/app_footer.php"; ?>

    
    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    

    <!-- START TEMPLATE -->
   

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
    <script type="text/javascript">
    


    $(document).ready(function() {
        $(".chosn").customselect();
        $("#load_page").fadeOut("slow");

	$('#txt_assign').autocomplete({
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
						$('#txt_workintiator').val('');
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