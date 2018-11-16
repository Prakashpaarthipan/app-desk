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

$sql_rdsrno =  select_query_json("select count(*) cnt from APPROVAL_REQUEST where aprnumb like '".$sql_reqid[0]['APRNUMB']."' order by ARQSRNO", "Centra", 'TEST');
if($sql_rdsrno[0]['CNT'] > 1 && $action == 'edit') { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="request_list.php";</script>
<? exit();
}

$sql_vl = select_query_json("select distinct req.APRQVAL, max(req.arqsrno) mx, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE,
                                        req.TARNUMB, req.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME
                                    from APPROVAL_REQUEST req, department_asset ast
                                    where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and req.DEPCODE = ast.DEPCODE and req.DELETED = 'N' and ast.DELETED = 'N'
                                    group by req.APRQVAL, req.pricode, req.PRJPRCS, req.BUDCODE, req.SUPCODE, req.SUPNAME, req.SUPCONT, req.APPRDET, req.DEPCODE, req.TARNUMB, req.TARDESC,
                                        ast.EXPSRNO, ast.EXPNAME, ast.DEPNAME
                                    order by mx desc", "Centra", 'TEST');
$sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'", "Centra", 'TEST');

if($_REQUEST['action'] == 'view')
{
    $title_tag = 'View';
}
elseif($_REQUEST['action'] == 'edit')
{
    $title_tag = 'Edit';
}
else {
    $title_tag = 'New';
}

$view = 1; // Budget Related Approvals show or not
if($sql_reqid[0]['ATYCODE'] != 1 and $sql_reqid[0]['ATYCODE'] != 6 and $sql_reqid[0]['ATYCODE'] != 7 and ( $_REQUEST['action'] == 'view' or $_REQUEST['action'] == 'edit' )) {
    $view = 0; // Budget Related Approvals show or not
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
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
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="nancy/insert_req.php" method="post" enctype="multipart/form-data">
			<!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
					<div class="page-content-wrap">

						<div class="row">
							<div class="col-md-12">

								<form class="form-horizontal">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><strong>POLICY APPROVAL</strong></h3>
										<ul class="panel-controls">
											<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
										</ul>
									</div>


									<div class="panel-body">


										<div class="row">
											<div class="col-md-6">
												<!-- topcore drop down ValidateSingleInput(this, 'all'); -->
											   <div class="form-group">
													<label class="col-md-3 control-label" >POLICY SUBJECT<span style='color:red'>*</span></label>
													<div class="col-md-9"  >
														<select class="form-control " autofocus tabindex='1' required name='txtdynamic_subject' id='txtdynamic_subject' onChange="find_tags(); getcore();" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
														
														<?  $sql_project = select_query_json("select * from approval_policy_master where DELETED = 'N' order by aplcysr", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                <option value='<?=$sql_project[$project_i]['APLCYCD']?>' <? if($sql_reqid[0]['APLCYCD'] == $sql_project[$project_i]['APLCYCD']) { ?> selected <? } ?>><?=$sql_project[$project_i]['APLCYNM']?></option>
                                                        <? } ?>
                                                    </select>
														<span class="help-block">SELECT POLICY</span>
													</div>
												</div>
												<!-- priority filed drop down -->
												<div class="form-group">
													<label class="col-md-3 control-label">EFFECTIVE DATE<span style='color:red'>*</span></label>
													<div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="tar_date" id="tar_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
                                                        <? } ?>
																</div>
												</div>
												<!-- tilte text feild -->
												<div class="form-group">
                                                    <label class="col-md-3 control-label">VALID UPTO<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="due_date" id="due_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='' data-toggle="tooltip" data-placement="top" placeholder="To Date" title=""  >
                                                        <? } ?>
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">APPROVAL DATE<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <? if($_REQUEST['action'] == 'view') {
                                                                echo ": ".$sql_reqid[0]['IMDUEDT'];
                                                           } else { ?>
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="app_date" id="app_date" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
                                                        <? } ?>
                                                    </div>
                                                </div>
												<div class="form-group">
                            <label class="col-md-3 control-label">USERLISTS</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  
									<select  class="form-control "  tabindex='6' style="text-transform: uppercase;" required name='txtdynamic_userlist' id='txtdynamic_userlist' data-toggle="tooltip" data-placement="top" tabindex="10"> data-original-title="USER LIST"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
                                                <option>--Select one--</option>												                                                              
                                                                 <?  $sql_emp = select_query_json("select emp.EMPSRNO, emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, des.DESNAME
                                                                                                  from employee_office emp, empsection sec, designation des
                                                                                                  where  emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.EMPCODE > 1000 or emp.EMPCODE in (1, 2, 3,4,5))
                                                                                                 order by EMPCODE", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  
                                                                                                        
																for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									<option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME']." - ". $sql_emp[$sql_emp_i]['DESNAME']." - ".  $sql_emp[$sql_emp_i]['ESENAME'].""?></option>
		
                                                        <?  } ?>					                								
							                 </select>					    
                            </div>
                        </div>
												<div class="form-group">
													<label class="col-md-3 control-label" id='attachment'>DESK PROCEDURE</label>
													<div class="col-md-9 col-xs-12">
														<div><input type="file" placeholder="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD" tabindex='8' onblur="find_tags();" class="form-control fileselect" name='attachments[]' id='attachments' onchange="ValidateSingleInput(this, 'all');" multiple accept="image/jpg,image/jpeg,image/gif,image/png,image/jpg,.pdf" value='' data-toggle="tooltip" data-placement="top" title="QUOTATION OR ESTIMATE IN SUPPLIER LETTER PAD"><span class="help-block">NOTE : ALLOWED ONLY PDF / IMAGES</span></div>
														<div id="attachments_detail">
														<a ></a>
														</div>

                                                        <div class="tags_clear"></div>
													</div>
												</div>
												
												<div class="form-group">
                                                    <label class="col-md-3 control-label">POLICY DOCUMENTS<span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_policy_docs' id='txtdynamic_policy_docs' data-toggle="tooltip" data-placement="top" data-original-title="POLICY DOCUMENTS" value=''>
                                                    </div>
                                                </div>
												</DIV>



<!--fix me-->
											<div class="col-md-6">
												<!-- core drop down -->
												<div class="form-group">
													<label class="col-md-3 control-label">POLICY TYPE</label>
													<div class="col-md-9">
														<select class="form-control policy_approval_required" required name='txtdynamic_policy_type' id='txtdynamic_policy_type' data-toggle="tooltip" data-placement="top" data-original-title="POLICY TYPE">
                                                        <option value='ORIGINAL'>ORIGINAL</option>
                                                        <option value='RENEWAL'>RENEWAL</option>
                                                    </select>
													</div>

												</div>
												<!--assign member-->

												<div class="form-group">
                            <label class="col-md-3 control-label">CREATOR ECNO/NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>

												<!-- Tag layout -->
												<div class="form-group">
                            <label class="col-md-3 control-label">CO-ORDINATOR ECNO/NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-3 control-label">ASSIST BY ECNO/NAME</label>
                            <div class="col-md-9 col-xs-12">
                                <b>
                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_asistby' id='txtdynamic_asistby' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												  
												    <? $sql_emp = select_query_json("select  EMPCODE,EMPNAME from employee_office  where   (EMPCODE > 1000 or EMPCODE in (1, 2, 3,4,5))                                                                                   
                                                                                    order by EMPCODE", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  

												   for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									            <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
							                </select>
                            </div>
                        </div>
										</div>
										<div class="tags_clear"></div>
										<div class="row">
										<div class="col-md-6"></div>
										<div class="col-md-6">
												<!-- topcore drop down ValidateSingleInput(this, 'all'); -->
											<div class="row">
											<div class="col-md-12">
											   <div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-9" name="tags" id="id_tags_generation">
													</div>
												</div>
                                            </div>
										</div></div>
										<div class="tags_clear"></div>
											<div class="row">
												<label class="col-md-3 control-label" style="text-align: left;">Requirement Details <span style='color:red'>*</span> : </label>
												<div class="tags_clear height10px"></div>
												<div class="col-md-12">
													<textarea name="FCKeditor1" id="FCKeditor1" tabindex='14' onblur="find_tags();"></textarea>
                                                    <script type="text/javascript">
                                                    var ckedit=CKEDITOR.replace("FCKeditor1",
                                                    {
                                                        height:"450", width:"100%",
                                                        filebrowserBrowseUrl : '/ckeditor/ckfinder/ckfinder.html',
                                                        filebrowserImageBrowseUrl : '/ckeditor/ckfinder/ckfinder.html?Type=Images',
                                                        filebrowserUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                        filebrowserImageUploadUrl : '/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
                                                    });
                                                    </script>
												</div>
										</div>
										<div class="tags_clear"></div>
										<div class="tags_clear"></div>
									<div class="panel-footer">

										<button class="btn btn-success pull-right" onclick="" type="submit">Submit</button>
									</div>
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>
								</form>
						<!-- page content wrapper ends here   onclick="nsubmit();"   -->
                    </div>
                </div>
    <? include "lib/app_footer.php"; ?>

    <!-- START PRELOADS -->
    <audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
    <audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
    <!-- END PRELOADS -->

    <!-- START SCRIPTS -->
    <!-- START PLUGINS -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="js/monthpicker.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="js/form-validation.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script src="js/jquery.filer.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/jquery-customselect.js"></script>
    <script type="text/javascript" src="js/angular-route.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript" src="js/angular-route-segment.min.js"></script>
    <script type="text/javascript">
    /* var app = angular.module('myApp', []);
    app.controller('tags_generation', function($scope, $http) {
       $http.get("php/json.php").then(function (response) {$scope.names = response.data.records;});
       $scope.onchange = function(id){
            $scope.filt = id.id;
            //alert(""+$scope.filt);
            $http.get("json_filter2.php?subtype_id="+$scope.filt+"").then(function (response) {$scope.name = response.data.records;});
        }

    }); */

	$('#tar_date').Zebra_DatePicker({
      direction: false,
      format: 'd-M-Y'
    });
	$('#due_date').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+1 days")))?>', false],
      format: 'd-M-Y'
    });
	$('#app_date').Zebra_DatePicker({
      direction: false,
      format: 'd-M-Y'
    });

    $(document).ready(function() {
        $(".chosn").customselect();
        $("#load_page").fadeOut("slow");
        find_checklist();
		getcore();

	$('#txtsaaign').autocomplete({
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

	function getcore(){
		//alert("uable to call");
		var vurl = "nancy/getcoredrop.php";
		//var vurl = "ranjani/post_req.php"; // the script where you handle the form input.
		//alert($("#attachments").files);
		$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'topcore':$("#txtTopcore").val()
			},
			dataType:'html',
            success: function(data1) {
               $('#get_core').html(data1);
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});
	}

	function nsubmit(){
		alert("uable to call");
			var vurl = "nancy/insert_req.php";
		//var vurl = "ranjani/post_req.php"; // the script where you handle the form input.
		//alert($("#attachments").files);
		$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'txtdynamic_subject':$("#txtdynamic_subject").val(),
				'txtdynamic_policy_type':$("#txtdynamic_policy_type").val(),
				
				'FCKeditor1':CKEDITOR.instances.FCKeditor1.getData(),
				'attachments':$("#attachments").val(),
			},
			dataType:'html',
            success: function(data1) {
               alert(data1);
            },
			error: function(response, status, error)
			{		alert(error);
					//alert(response);
					//alert(status);
			}
			});
	}

    function find_checklist() {
        $('#load_page').show();
        var slt_targetno = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var strURL="ajax/ajax_validate.php?action=fix_checklist&slt_targetno="+slt_targetno+"&slt_submission="+slt_submission;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // alert("++++++++++++"+data1);
                var chklist = data1.split(",");

                $("#id_txt_submission_quotations").css("display", "none");
                $('#txt_submission_quotations').prop('required', false);
                $("#id_txt_submission_fieldimpl").css("display", "none");
                $('#txt_submission_fieldimpl').prop('required', false);
                $("#id_txt_submission_clrphoto").css("display", "none");
                $('#txt_submission_clrphoto').prop('required', false);
                $("#id_txt_submission_artwork").css("display", "none");
                $('#txt_submission_artwork').prop('required', false);
                $("#id_txt_submission_othersupdocs").css("display", "none");
                $('#txt_submission_othersupdocs').prop('required', false);
                $("#id_txt_warranty_guarantee").css("display", "none");
                $('#txt_warranty_guarantee').prop('required', false);
                $("#id_txt_cur_clos_stock").css("display", "none");
                $('#txt_cur_clos_stock').prop('required', false);
                $("#id_txt_advpay_comperc").css("display", "none");
                $('#txt_advpay_comperc').prop('required', false);
                $("#id_datepicker_example4").css("display", "none");
                $('#datepicker_example4').prop('required', false);

                var enable1 = 0; var enable2 = 0; var enable3 = 0; var enable4 = 0;
                var enable5 = 0; var enable6 = 0; var enable7 = 0; var enable8 = 0; var enable9 = 0;
                for (var exp_chklisti=0; exp_chklisti < chklist.length; exp_chklisti++) {
                    // alert(chklist[exp_chklisti]+"**"+exp_chklisti+"**"+chklist.length);

                    if(chklist[exp_chklisti] == 1) {
                        enable1 = 1;
                        $("#id_txt_submission_quotations").css("display", "block");
                        // $('#txt_submission_quotations').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 2) {
                        enable2 = 1;
                        $("#id_txt_submission_fieldimpl").css("display", "block");
                        // $('#txt_submission_fieldimpl').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 3) {
                        enable3 = 1;
                        $("#id_txt_submission_clrphoto").css("display", "block");
                        // $('#txt_submission_clrphoto').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 4) {
                        enable4 = 1;
                        $("#id_txt_submission_artwork").css("display", "block");
                        // $('#txt_submission_artwork').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 5) {
                        enable5 = 1;
                        $("#id_txt_submission_othersupdocs").css("display", "block");
                        // $('#txt_submission_othersupdocs').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 6) {
                        enable6 = 1;
                        $("#id_txt_warranty_guarantee").css("display", "block");
                        // $('#txt_warranty_guarantee').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 7) {
                        enable7 = 1;
                        $("#id_txt_cur_clos_stock").css("display", "block");
                        // $('#txt_cur_clos_stock').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 8) {
                        enable8 = 1;
                        $("#id_txt_advpay_comperc").css("display", "block");
                        // $('#txt_advpay_comperc').prop('required', true);
                    }

                    if(chklist[exp_chklisti] == 9) {
                        enable9 = 1;
                        $("#id_datepicker_example4").css("display", "block");
                        // $('#datepicker_example4').prop('required', true);
                    }
                }


                $('#load_page').hide();
            }
        });
    }

    function find_tags() {
        //alert("**CAME**");
        $('#load_page').show();
        CKEDITOR.instances.FCKeditor1.updateElement();
        var data_serialize = $("#frm_requirement_entry").serializeArray();
        $.ajax({
            type: 'post',
            url: "ajax/ajax_tags_generator.php",
            // dataType: 'json',
            data: data_serialize,
            beforeSend: function() {
                $('#load_page').show();
            },
            success: function(response)
            {
                $("#id_tags_generation").html('');
                $("#id_tags_generation").html(response);
                $('#load_page').hide();
            },
            error: function(response, status, error)
            {
                /* var ALERT_TITLE = "Message";
                var ALERTMSG = "Tags Generation Failure. Kindly try again!!";
                createCustomAlert(ALERTMSG, ALERT_TITLE); */
                $('#load_page').hide();
            }
        });
    }

    function reload_page() {
        $('#load_page').show();
        location.reload();
        $('#load_page').show();
    }

    function checkform() {
        $("#frm_request_entry").on('submit',(function(e) {
            // alert("~~");
            e.preventDefault();
            checkform_check();

            // var flag = checkform();
            var flag = $('#hid_gtvl').val();
            alert("=##="+flag+"=##=");
            if(flag == 1) {
                /* var value = CKEDITOR.instances['FCKeditor1'].getData();
                $('#editor_detail').val(value);
                // alert("**"+value); exit; */

                CKEDITOR.instances.FCKeditor1.updateElement();
                var data_serialize = $("#frm_request_entry").serialize();
                // var editor = CKEDITOR.instances.FCKeditor1;
                // for (instance in CKEDITOR.instances) {
                    // CKEDITOR.instances.FCKeditor1.updateElement();
                // }

                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: "lib/process_connect.php",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    // dataType: 'json',
                    // data: data_serialize,
                    beforeSend: function() {
                        $('#sbmt_request').attr('disabled', true);
                        // $('#loader').html('<span class="wait">&nbsp;<img src="images/loading.gif" alt="" /></span>');
                        $('#load_page').show();
                    },
                    complete: function() {
                        $('#sbmt_request').attr('disabled', false);
                        // $('.wait').remove();
                        ///////** $('#load_page').hide();
                    },
                    success: function(response)
                    {
                        // alert("++++++"+response+"+++++++");
                        if(response.type == 'error') {
                            /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('#sbmt_request').attr('disabled', false);
                            // $('#loader').html('');
                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("err-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        } else {
                            /* output = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                            output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Created Successfully!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('input[type=text]').val('');

                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("suc-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        }
                        $('#load_page').hide();

                        // $("#result").hide().html(output).slideDown();
                    },
                    error: function(response, status, error)
                    {
                        // var err = eval("(" + response.responseText + ")");
                        // alert(err.Message);

                        /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                        output += response.msg+'</div>';
                        $('#sbmt_request').attr('disabled', false); */
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);

                        // $('#loader').html('');
                        /////** window.location="request_entry.php";
                        /////** $('#load_page').show();

                        // alert(response.info + "err0r-" + response.msg);
                        //if(response.info != '')
                            // window.location = response.info;
                    }
                });
            } else {
                e.preventDefault();
            }
        }));
    }



        // alert("||");
        // $("#sbmt_request").click(function() {
            $("#frm_request_entry").validate({
        // $("#frm_request_entry").on('submit',(function(e) {
            // alert("~~");
            // e.preventDefault();

            submitHandler: function(form) {
            // var flag = checkform();
            var flag = $('#hid_gtvl').val();
            alert("=#="+flag+"=#=");
            if(flag == 1)
            {
                /* var value = CKEDITOR.instances['FCKeditor1'].getData();
                $('#editor_detail').val(value);
                // alert("**"+value); exit; */

                CKEDITOR.instances.FCKeditor1.updateElement();
                var data_serialize = $("#frm_request_entry").serialize();
                // var editor = CKEDITOR.instances.FCKeditor1;
                // for (instance in CKEDITOR.instances) {
                    // CKEDITOR.instances.FCKeditor1.updateElement();
                // }

                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: "lib/process_connect.php",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    // dataType: 'json',
                    // data: data_serialize,
                    beforeSend: function() {
                        $('#sbmt_request').attr('disabled', true);
                        // $('#loader').html('<span class="wait">&nbsp;<img src="images/loading.gif" alt="" /></span>');
                        $('#load_page').show();
                    },
                    complete: function() {
                        $('#sbmt_request').attr('disabled', false);
                        // $('.wait').remove();
                        ///////** $('#load_page').hide();
                    },
                    success: function(response)
                    {
                        // alert("++++++"+response+"+++++++");
                        if(response.type == 'error') {
                            /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('#sbmt_request').attr('disabled', false);
                            // $('#loader').html('');
                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("err-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        } else {
                            /* output = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                            output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                            output += response.msg+'</div>'; */
                            var ALERT_TITLE = "Message";
                            var ALERTMSG = "Request Created Successfully!!";
                            createCustomAlert(ALERTMSG, ALERT_TITLE);
                            $('input[type=text]').val('');

                            /////** window.location="request_entry.php";
                            /////** $('#load_page').show();

                            // alert("suc-" + response.info);
                            // if(response.info != '')
                                // window.location = response.info;
                        }
                        $('#load_page').hide();

                        // $("#result").hide().html(output).slideDown();
                    },
                    error: function(response, status, error)
                    {
                        // var err = eval("(" + response.responseText + ")");
                        // alert(err.Message);

                        /* output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                        output += response.msg+'</div>';
                        $('#sbmt_request').attr('disabled', false); */
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Request Creation Failure. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);

                        // $('#loader').html('');
                        /////** window.location="request_entry.php";
                        /////** $('#load_page').show();

                        // alert(response.info + "err0r-" + response.msg);
                        //if(response.info != '')
                            // window.location = response.info;
                    }
                });
            } else {
                e.preventDefault();
            }
        }
        });
        // );

    //upload Only PDF file
    function ValidateSingleInput(oInput, file_ext) {
        $('#load_page').show();

        if(file_ext == 'pdf') {
            var _validFileExtensions = [".pdf",".PDF"];
			//alert(oInput+" 1\n "+file_ext);
        } else {
            var _validFileExtensions = [".jpg",".jpeg",".png",".gif",".pdf",".JPG",".JPEG",".PNG",".GIF",".PDF"];
			//	alert(oInput+" 2\n "+file_ext);
        }
        if (oInput.type == "file") {
            var sFileName = oInput.value;
			//alert(sFileName+" 3\n "+file_ext);
             if (sFileName.length > 0) {
                var blnValid = false;
                for (var j = 0; j < _validFileExtensions.length; j++) {
                    var sCurExtension = _validFileExtensions[j];
                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    // alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                    // alert("Sorry, Upload Only PDF file Format");
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Kindly Upload Only PDF file. Other Formats not allowed!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    oInput.value = "";
                    return false;
                }
            }
            $('#load_page').hide();
        }
        return true;
    }


    function fix_maxpercentage(maxpercentage) {
        var crnt_percent = $('#txt_adv_amount').val();
        if(crnt_percent > maxpercentage) {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Not allowed more than "+maxpercentage+" %. So Kindly reduce this!!";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            $('#txt_adv_amount').val(0)
        }
    }

    function addempnit(id)
    {
        var empcode = $("#txt_staffcode_"+id).val();
        var nempcode = empcode.split('-');
        var str2 = "-";
        if(empcode.indexOf(str2) != -1){
            var brncode = $("#slt_branch").val();
            var strURL="ajax/ajax_staffchange.php?action=night&empcode="+empcode+"&id="+id+"&brncode="+brncode;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    if(data != 0){
                        $("#photo_"+id).html(data);
                        var hid_data = $("#hid_emp_"+id).val();
                        var val = hid_data.split("~");
                        $('#curdep_'+id).val(val[3]);
                        $('#curdes_'+id).val(val[4]);

                        $('#descode_'+id).val(val[5]);
                        $('#esecode_'+id).val(val[6]);

                        $('#empsrno_'+id).val(val[7]);
                        $('#curbrn_'+id).val(val[2]);
                    }else{
                        $('#curdep_'+id).attr('readonly',false);
                        $('#curdes_'+id).attr('readonly',false);
                    }
                }
            });
        }else{
            $('#curdep_'+id).attr('readonly',false);
            $('#curdes_'+id).attr('readonly',false);
        }
    }

    function find_taxvalue(opt1, opt2) {
        $('#load_page').show();
        var txt_regdis = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
        var txt_prdrate = (document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value);
        if(txt_regdis != "" && txt_regdis != 0)
        {
        var txt_dis = parseFloat(txt_prdrate)/100*parseFloat(txt_regdis);
        txt_prdrate = parseFloat(txt_prdrate) - parseFloat(txt_dis);
        }
        var txt_prdsgst = document.getElementById('txt_prdsgst_per_'+opt1+'_'+opt2).value;
        var txt_prdcgst = document.getElementById('txt_prdcgst_per_'+opt1+'_'+opt2).value;
        var txt_prdigst = document.getElementById('txt_prdigst_per_'+opt1+'_'+opt2).value;
        //prdcst = Math.round(prdcst).toFixed(2);
        document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdsgst / 100) * txt_prdrate),4);
        document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value = roundTo(((txt_prdcgst / 100) * txt_prdrate),4);
        document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value = roundTo(((txt_prdigst / 100) * txt_prdrate),4);
        // document.getElementById('txt_hidprdsgst_'+opt1+'_'+opt2).value = ((txt_prdsgst / 100) * txt_prdrate);
        // document.getElementById('txt_hidprdcgst_'+opt1+'_'+opt2).value = ((txt_prdcgst / 100) * txt_prdrate);
        // document.getElementById('txt_hidprdigst_'+opt1+'_'+opt2).value = ((txt_prdigst / 100) * txt_prdrate);
        $('#load_page').hide();
    }

     function roundTo(n, digits) {
        if (digits === undefined) {
            digits = 0;
        }

        var multiplicator = Math.pow(10, digits);
        n = parseFloat((n * multiplicator).toFixed(11));
        return (Math.round(n) / multiplicator).toFixed(4);
    }
    function calculatenetamount(opt1, opt2){
        $('#load_page').show(); // call_dynamic_option
        find_taxvalue(opt1, opt2);
        var txt_prdqty = document.getElementById('txt_prdqty_'+opt1).value;
        if(txt_prdqty==''){
            txt_prdqty = 0;
        }
        if(txt_prdqty == 0) {
            document.getElementById('txt_prdqty_'+opt1).value = 1;
            calculatenetamount(opt1, opt2);
        }

        var txt_prdrate = document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value;
        if(txt_prdrate==''){
            txt_prdrate = 0;
        }
        var txt_prdsgst = document.getElementById('txt_prdsgst_'+opt1+'_'+opt2).value;
        if(txt_prdsgst==''){
            txt_prdsgst = 0;
        }
        var txt_prdcgst = document.getElementById('txt_prdcgst_'+opt1+'_'+opt2).value;
        if(txt_prdcgst==''){
            txt_prdcgst = 0;
        }
        var txt_prdigst = document.getElementById('txt_prdigst_'+opt1+'_'+opt2).value;
        if(txt_prdigst==''){
            txt_prdigst = 0;
        }
        var txt_prddisc = document.getElementById('txt_prddisc_'+opt1+'_'+opt2).value;
        if(txt_prddisc==''){
            txt_prddisc = 0;
        }

        var txt_spldisc = document.getElementById('txt_spldisc_'+opt1+'_'+opt2).value;
        if(txt_spldisc==''){
            txt_spldisc = 0;
        }
        var txt_pieceless = document.getElementById('txt_pieceless_'+opt1+'_'+opt2).value;
        if(txt_pieceless==''){
            txt_pieceless = 0;
        }

        var txt_ad_duration = document.getElementById('txt_ad_duration_'+opt1).value;
        if(txt_ad_duration==''){
            txt_ad_duration = 0;
        }

        var txt_size_length = document.getElementById('txt_size_length_'+opt1).value;
        if(txt_size_length==''){
            txt_size_length = 0;
        }

        var txt_size_width = document.getElementById('txt_size_width_'+opt1).value;
        if(txt_size_width==''){
            txt_size_width = 0;
        }

        /* netamount = + parseFloat(txt_prdrate) + +parseFloat(txt_prdsgst)+ +parseFloat(txt_prdcgst)+ +parseFloat(txt_prdigst);
        netamounttotal=Number(netamount) - Number(txt_prddisc);
        document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(netamounttotal*txt_prdqty); txt_prdrate
        document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(netamounttotal*txt_prdqty); */

        // New Calculation - 22-09-2017 // GA
        var ttl_lock = $("#ttl_lock").val();
        var rptmode = $("#txt_rptmode").val();
        var slt_subcore = $("#slt_subcore").val();
        var pcless = 0;
        var spldis = 0;
        var prdqty = 0;
        var prdcst = 0;
        var tot_prddisc = 0;
        /* if(txt_prdqty == 0 || txt_prdqty == '') {
            txt_prdqty = 1;
        } */

        // console.log("!!"+txt_prdrate+"!!"+opt1+"!!"+opt2+"!!");
        if(rptmode == 1 || rptmode == 2 || rptmode == 3 || rptmode == 4) { // Non ADVT Exp.
            prdqty = txt_prdqty;
            tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty))/100 * parseFloat(txt_prddisc) ;
            tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
            //pcless = txt_pieceless;
            //spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
            //prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
            prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
        } else if(rptmode == 5 || rptmode == 6) { // ADVT Exp. Ad Flex Exp.
            prdqty = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width);
            tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width))/100 * parseFloat(txt_prddisc) ;
            tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
            //pcless = parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) * parseFloat(txt_pieceless);
            //spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
            //prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
            if(txt_size_length != ""  && txt_size_width != "" ){
            prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_size_length) * parseFloat(txt_size_width)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
            }else{
            prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
            }
        } else if(rptmode == 7) { // ADVT Exp. Ad Play Duration Exp.
            prdqty = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration);
            tot_prddisc = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration))/100 * parseFloat(txt_prddisc) ;
            tot_gst = +(parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst)) * parseFloat(txt_prdqty);
            //pcless = parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) * parseFloat(txt_pieceless);
            //spldis = (parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration) - parseFloat(pcless) - parseFloat(txt_prddisc)) * (parseFloat(txt_spldisc) / 100);
            //prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(txt_prdsgst) + +parseFloat(txt_prdcgst) + +parseFloat(txt_prdigst);
            if(txt_ad_duration != "")
            {
            prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty) * parseFloat(txt_ad_duration)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
            }else{
            prdcst = +parseFloat(prdcst) + +((parseFloat(txt_prdrate) * parseFloat(txt_prdqty)) - parseFloat(pcless) - parseFloat(spldis) - parseFloat(tot_prddisc)) + +parseFloat(tot_gst);
            }

        }
        // console.log("@@"+prdqty+"@@"+pcless+"@@"+spldis+"@@"+prdcst+"@@");
        prdcst = Math.round(prdcst).toFixed(2);
        document.getElementById('id_prdnetamount_'+opt1+'_'+opt2).innerHTML = parseFloat(prdcst);
        document.getElementById('hid_prdnetamount_'+opt1+'_'+opt2).value = parseFloat(prdcst);

        if($('#txt_sltsupplier_'+opt1+'_'+opt2).is(":checked")) {
            // console.log("**"+txt_prdrate+"**"+prdcst+"**");
            txt_prdsgst = roundTo(txt_prdsgst,4);
            txt_prdcgst = roundTo(txt_prdcgst,4);
            txt_prdigst = roundTo(txt_prdigst,4);
            $('#id_sltrate_'+opt1).html(txt_prdrate);
            $('#id_sltsgst_'+opt1).html(txt_prdsgst);
            $('#id_sltcgst_'+opt1).html(txt_prdcgst);
            $('#id_sltigst_'+opt1).html(txt_prdigst);
            /* $('#id_sltslds_'+opt1).html(txt_spldisc);
            $('#id_sltpcls_'+opt1).html(txt_pieceless); */
            $('#id_sltdisc_'+opt1).html(txt_prddisc);
            $('#id_sltamnt_'+opt1).html(prdcst);

            var requestedvalue=0;
            var y = $('.parts3 .part3').length + 1;
            for(var j=1;j<=y;j++){
                var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
                for(var i=0;i<x.length;i++){
                    if(x[i].checked){
                        var z = i+1;
                        if(document.getElementById('hid_prdnetamount_'+j+'_'+z).value==''){
                            document.getElementById('hid_prdnetamount_'+j+'_'+z).value=0;
                        }
                        requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value)
                    }
                }
            }

            if(parseInt(requestedvalue) <= parseInt(ttl_lock) && parseInt(ttl_lock) > 0) {
                document.getElementById('txtrequest_value').value = requestedvalue;
                document.getElementById('txt_brnvalue_0').value = requestedvalue;
                document.getElementById('hidrequest_value').value = requestedvalue;
                $('.hidn_balance').val(requestedvalue);
                // New Calculation - 22-09-2017 // GA

                if(document.getElementById('npobudget'))
                {
                    document.getElementById('mnt_yr_amt_<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>').value = requestedvalue;
                    calculate_sum();
                }

                var requestedvalue=0;
                var y = $('.parts3 .part3').length + 1;
                for(var j=1;j<=y;j++){
                    var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
                    for(var i=0;i<x.length;i++){
                        if(x[i].checked){
                            var z = i+1;
                            requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
                            // alert("***"+requestedvalue+"***"+j+"***"+z+"****");
                        }
                    }
                }
                document.getElementById('txtrequest_value').value = requestedvalue;
                document.getElementById('txt_brnvalue_0').value = requestedvalue;
                document.getElementById('hidrequest_value').value = requestedvalue;
                if(document.getElementById('npobudget'))
                {
                    document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
                    calculate_sum();
                }

                for(jvi = 1; jvi <= 10; jvi++) {
                    // alert("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***");
                    // console.log("***"+'#hid_prdnetamount_'+opt1+'_'+jvi+"***"+opt1+"***"+opt2+"***"+ttlcnt+"***"+jvi+"***");
                    $('#hid_prdnetamount_'+opt1+'_'+jvi).attr('class', 'form-control');
                }
                $('#hid_prdnetamount_'+opt1+'_'+opt2).attr('class', 'form-control ttlcalc');

                var requestedvalue = totcalc('ttlcalc');
                // console.log("###"+requestedvalue+"###");
                document.getElementById('txtrequest_value').value = requestedvalue;
                document.getElementById('txt_brnvalue_0').value = requestedvalue;
                document.getElementById('hidrequest_value').value = requestedvalue;
                if(document.getElementById('npobudget'))
                {
                    document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value = requestedvalue;
                    calculate_sum();
                }

                $('.hidn_balance').val(requestedvalue);
                /* console.log(document.getElementById('mnt_yr_amt_'+<? if($cur_mon < 10) { echo $input = ltrim($cur_mon, '0'); } else { echo $cur_mon; } ?>).value); */
                getapproval_listings_only();
            } else {
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                document.getElementById('txt_prdrate_'+opt1+'_'+opt2).value = 0;
                calculatenetamount(opt1, opt2);
            }
        }
        $('#load_page').hide();
    }

    function totcalc(clsname){
        var dirq = 0;
        var list = document.getElementsByClassName(clsname);
        var values = [];
        if(list.length > 0) {
            for(var i = 0; i < list.length; ++i) {
                values.push(parseFloat(list[i].value));
            }
            dirq = values.reduce(function(previousValue, currentValue, index, array){
                return previousValue + currentValue;
            });
            // alert(clsname+"+++++++"+dirq);
        } else {
            dirq = 0;
        }
        return dirq;
    }

    function calculateqtyamount(gid){
        var x = document.getElementsByName('txt_sltsupcode['+gid+'][]');
        for(var i=1;i<=x.length;i++){
            calculatenetamount(gid,i);
        }
    }

    function getrequestvalue(opt1, opt2){
        $('#load_page').show();
        calculatenetamount(opt1, opt2);

        var requestedvalue=0;
        var y = $('.parts3 .part3').length + 1;
        for(var j=1;j<=y;j++){
            var x = document.getElementsByName('txt_sltsupplier['+j+'][]');
            for(var i=0;i<x.length;i++){
                if(x[i].checked){
                    var z = i+1;
                    requestedvalue += parseFloat(document.getElementById('hid_prdnetamount_'+j+'_'+z).value);
                    // alert("***"+requestedvalue+"***"+j+"***"+z+"****");
                }
            }
        }
        document.getElementById('txtrequest_value').value = requestedvalue;
        document.getElementById('txt_brnvalue_0').value = requestedvalue;
        document.getElementById('hidrequest_value').value = requestedvalue;
        // getapproval_listings();
        $('#load_page').hide();
    }

    function getrequestvalues(iv, jv, ttlcnt){
        $('#load_page').show();
        calculatenetamount(iv, jv);

        for(jvi = 1; jvi <= ttlcnt; jvi++) {
            // alert("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***");
            // console.log("***"+'#hid_prdnetamount_'+iv+'_'+jvi+"***"+iv+"***"+jv+"***"+ttlcnt+"***"+jvi+"***");
            $('#hid_prdnetamount_'+iv+'_'+jvi).attr('class', 'form-control');
        }
        $('#hid_prdnetamount_'+iv+'_'+jv).attr('class', 'form-control ttlcalc');

        var requestedvalue = totcalc('ttlcalc');
        // console.log("###"+requestedvalue+"###");
        document.getElementById('txtrequest_value').value = requestedvalue;
        document.getElementById('txt_brnvalue_0').value = requestedvalue;
        document.getElementById('hidrequest_value').value = requestedvalue;
        $('.hidn_balance').val(requestedvalue);
        // getapproval_listings();
        $('#load_page').hide();
    }

    // validate the product textbox
    function validate_prdempty(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=product&validate_code="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                    // $("#txt_prdcode_"+iv).focus();
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        });
        // fix_tax(iv);
        // $('#load_page').hide();
    }
    // validate the product textbox

    // validate the sub product textbox
    function validate_subprdempty(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=sub_product&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
                // alert("***"+data1+"***");
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Sub Product Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                    // $("#txt_subprdcode_"+iv).focus();
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_subprdcode_"+iv).val('');
                }
            }
        });
        find_unitcode(iv);
        // fix_tax(iv);
        // $('#load_page').hide();
    }
    // validate the sub product textbox

    // find the unit code from sub product textbox
    function find_unitcode(iv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_unitcode&validate_code="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Unit code Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_unitname_"+iv).val('');
                } else {
                    var prd = data1.split(" - ");
                    $("#txt_unitname_"+iv).val(prd[1]);
                    $("#txt_unitcode_"+iv).val(prd[0]);
                }
            }
        });
		find_nof_supplier_product(iv);
        // find_hsncode(iv);
        // $('#load_page').hide();
    }
    // find the unit code from sub product textbox

	// Fix Nof Suppliers based on chosen Product
    function find_nof_supplier_product(iv) {
        $('#load_page').show();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=fix_nof_suppliers&sub_prdcode="+sub_prdcode+"&prdcode="+prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
				$("#hid_nof_suppliers_"+iv).val(data1);
            }
        });
        $('#load_page').hide();
    }
    // Fix Nof Suppliers based on chosen Product

    // find the HSN Code based on the chosen product & sub product based
    /* function find_hsncode(iv) {
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=find_hsncode&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                }
            }
        });
    } */
    // find the HSN Code based on the chosen product & sub product based

    // validate the product specifiction textbox
    function validate_prdspcempty(iv) {
        /* var spc_prdcode = $("#txt_prdspec_"+iv).val();
        var strURL="ajax/ajax_validate.php?action=prod_spec&validate_code="+spc_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Product Specifiction Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdspec_"+iv).val('');
                    // $("#txt_prdspec_"+iv).focus();
                } else if(data1 == 2) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "HSN Code not yet fixed. Kindly Contact MIS Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_prdcode_"+iv).val('');
                }
            }
        }); */
    }
    // validate the product specifiction textbox

    // validate the supplier textbox
    function validate_supprdempty(iv, jv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var slt_core_department = $("#slt_core_department").val();
        var sup_prdcode = $("#txt_sltsupcode_"+iv+"_"+jv).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_validate.php?action=supplier&validate_code="+sup_prdcode+"&slt_core_department="+slt_core_department+"&brncode="+slt_brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                // $('#load_page').hide();
                var data = data1.split("~");
                if(data[0] == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_sltsupcode_"+iv+"_"+jv).val('');
                    // $("#txt_sltsupcode_"+iv+"_"+jv).focus();
                }
                document.getElementById('state'+iv+"_"+jv).value = data[1];
                fix_tax(iv, jv);
            }
        });
        // $('#load_page').hide();
    }
    // validate the supplier textbox

    // assign tax based on the chosen product / sub product
    function fix_tax(iv, jv) {
        $('#load_page').hide();
        // $('#load_page').show();
        var prdcode = $("#txt_prdcode_"+iv).val();
        var sub_prdcode = $("#txt_subprdcode_"+iv).val();
        var ostate = $('#state'+iv+'_'+jv).val();
        var strURL="ajax/ajax_validate.php?action=fix_tax&prdcode="+prdcode+"&sub_prdcode="+sub_prdcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                if(data1 == '') {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Tax details Available. Kindly Contact MIS team to fix the HSN CODE!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                } else {
                    var reslt = data1.split("-");
                    if(ostate == 1)
                    {
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val(reslt[0]);
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val(reslt[1]);
                        $('#txt_prdigst_per_'+iv+'_'+jv).val('');

                        $('#txt_taxpercentage_'+iv+'_'+jv).html('SGST - '+reslt[0]+' %; CGST - '+reslt[1]+' %;');
                    }else{
                        $('#txt_prdsgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdcgst_per_'+iv+'_'+jv).val('');
                        $('#txt_prdigst_per_'+iv+'_'+jv).val(reslt[2]);
                        $('#txt_taxpercentage_'+iv+'_'+jv).html('IGST - '+reslt[2]+' %;');
                    }
                }
                // $('#load_page').hide();
            }
        });
        // $('#load_page').hide();
    }
    // assign tax based on the chosen product / sub product

    function call_product_innergrid(gridid) {
        $('#load_page').show();
        // $("#addbtn").click(function () {
            // alert("CAME");
            if( ($('.parts3 .part3').length+1) > 99) {
                alert("Maximum 100 Products allowed.");
            } else {
                var slt_subcore = $('#slt_subcore').val();
                if(slt_subcore == 41) {
                    var rdnly = "";
                } else {
                    var rdnly = "readonly";
                }
                $('[data-toggle="tooltip"]').tooltip();
                // var id = ($('.parts3 .part3').length + 2).toString();
                var id = (+$('#partint3').val() + 1);
                $('#partint3').val(id);
                var apnd_content = '<div class="part3" style="margin-right: -5px; text-transform: uppercase;">'+
                                        '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line">&nbsp;'+id+'</div>'+
                                        '</div>'+
                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" name="txt_prdcode[]" id="txt_prdcode_'+id+'" required="required" maxlength="100" placeholder="Product" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product" class="form-control supquot find_prdcode" onBlur="validate_prdempty('+id+'); find_tags();" style=" text-transform: uppercase; padding: 0px;height: 25px;"></div>'+

                                            '<div style="width: 49%; float: left;margin-left: 2px;">'+
                                                '<input type="text" name="txt_subprdcode[]" id="txt_subprdcode_'+id+'" maxlength="100" placeholder="Sub Product" data-toggle="tooltip" data-placement="top" title="Sub Product" onKeyPress="enable_product();" class="form-control supquot find_subprdcode" onBlur="validate_subprdempty('+id+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+

                                                '<input type="hidden" readonly="readonly" name="txt_unitname[]" id="txt_unitname_'+id+'" required="required" maxlength="3" placeholder="Unit" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Unit" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                                '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_unitcode[]" id="txt_unitcode_'+id+'" required="required" maxlength="3" placeholder="Unit Code" data-toggle="tooltip" data-placement="top" title="Unit Code" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both; height: 1px;"></div>'+

                                            '<div>'+
                                                '<input type="text" name="txt_prdspec[]" id="txt_prdspec_'+id+'" required="required" maxlength="100" placeholder="Product Specification" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Product Specification" class="form-control supquot find_prdspec" onBlur="validate_prdspcempty('+id+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div>'+
                                                '<!-- Product Image -->'+
                                                '<input type="file" name="fle_prdimage[]" id="fle_prdimage_'+id+'" data-toggle="tooltip" onchange="ValidateSingleInput(this); find_tags();" accept="image/jpg,image/jpeg,image/png,image/jpg" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Product Image" placeholder="Product Image" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : ONLY JPG, PNG IMAGES ALLOWED.</span>'+
                                                '<!-- Product Image -->'+
                                            '</div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                            '<div style="width: 49%; float: left;"><input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_ad_duration[]" id="txt_ad_duration_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="3" placeholder="Ad. Duration" data-toggle="tooltip" data-placement="top" title="Ad. Duration" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" name="txt_print_location[]" id="txt_print_location_'+id+'" maxlength="25" placeholder="Ad. Print Location" data-toggle="tooltip" data-placement="top" onKeyPress="enable_product();" title="Ad. Print Location" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div>'+

                                            '<div style="width: 49%; float: left;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_length[]" id="txt_size_length_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="7" placeholder="Size Length" data-toggle="tooltip" data-placement="top" title="Size Length" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div>'+
                                            '<div style="width: 49%; float: left; margin-left: 2px;">'+
                                                '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_size_width[]" id="txt_size_width_'+id+'" onblur="calculateqtyamount('+id+'); find_tags();" maxlength="7" placeholder="Size width" data-toggle="tooltip" data-placement="top" title="Size width" class="form-control supquot ad_category" '+rdnly+' style=" text-transform: uppercase;height: 25px;" >'+
                                            '</div><div style="clear: both;"></div><input type="hidden" readonly="readonly" name="slt_usage_section[]" id="slt_usage_section_'+id+'" required="required" maxlength="3" placeholder="Usage Section" data-toggle="tooltip" data-placement="top" title="Usage Section" onKeyPress="enable_product();" class="form-control supquot custom-select chosn" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="enable_product(); return numwodot(event)" name="txt_prdqty[]" id="txt_prdqty_'+id+'" required="required" maxlength="6" placeholder="Qty" onblur="calculateqtyamount('+id+'); find_tags();" data-toggle="tooltip" data-placement="top" title="Qty" class="form-control supquot" style=" text-transform: uppercase;height: 25px;" >'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltrate_'+id+'">'+
                                            ' -'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div style="float: left; width: 50%; text-align: right;">SGST : </div><div style="float: left; width: 50%;" id="id_sltsgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">CGST : </div><div style="float: left; width: 50%;" id="id_sltcgst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;IGST : </div><div style="float: left; width: 50%;" id="id_sltigst_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+
                                        // discount hide
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                        /*  '<div style="float: left; width: 50%; text-align: right;">SPL.DIS. : </div><div style="float: left; width: 50%;" id="id_sltslds_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                            '<div style="float: left; width: 50%; text-align: right;">PCELES. : </div><div style="float: left; width: 50%;" id="id_sltpcls_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+*/
                                            '<div style="float: left; width: 50%; text-align: right;">&nbsp;&nbsp;DISC.% : </div><div style="float: left; width: 50%;" id="id_sltdisc_'+id+'"> - </div>'+
                                            '<div style="clear: both;"></div>'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight red_clrhighlight" style="padding: 1px 0px;" id="id_sltamnt_'+id+'">'+
                                            ' -'+
                                        '</div>'+
                                    '</div>'+

                                    '<div class="row" style="margin-right: -5px; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 0px; border-top-left-radius:5px;"></div>'+
                                        '<!-- Quotation -->'+
                                        '<div class="col-sm-10 colheight" style="padding: 0px; border-top-left-radius:5px;">'+
                                            '<div class="fair_border" style="padding-left: 0px;">'+
                                                '<div class="row" style="margin-right: -10px; background-color: #666666; color:#FFFFFF; display: flex; font-weight: bold;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">#</div>'+
                                                    '<div class="col-sm-3 colheight" style="padding: 0px;">Supplier Details</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Delivery Duration</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Per Piece Rate / Adv. Amount</div>'+
                                                    // '<div class="col-sm-1 colheight" style="padding: 0px;">Rate</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Tax Val.</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Discount % </div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Total Amount</div>'+
                                                    '<div class="col-sm-1 colheight" style="padding: 0px;">Quotation PDF</div>'+
                                                    '<div class="col-sm-2 colheight" style="padding: 0px;">Remarks</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+
                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style="padding: 0px; border: 1px solid #FFFFFF !important; background-color: #FFFFFF; border-top-left-radius:5px;"></div>'+
                                    '</div> '+

                                    '<div class="row" style="margin-right: -5px; display: flex; text-transform: uppercase;">'+
                                        '<div class="col-sm-1 colheight" style="background-color: #FFFFFF; border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                        '<div class="col-sm-10 colheight" style="padding-left: 0px;">'+
                                            '<!-- Quotation -->'+
                                            '<div class="parts3_'+id+' fair_border">'+
                                                '<div class="row" style="margin-right: -10px; display: flex;">'+
                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<div class="fg-line">'+
                                                            '<input type="hidden" name="partint3_'+id+'" id="partint3_'+id+'" value="1"><input type="hidden" name="hid_nof_suppliers_'+id+'" id="hid_nof_suppliers_'+id+'" value="1"><input type="hidden" name="txt_prdsgst_per['+id+'][]" id="txt_prdsgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdcgst_per['+id+'][]" id="txt_prdcgst_per_'+id+'_1" value=""><input type="hidden" name="txt_prdigst_per['+id+'][]" id="txt_prdigst_per_'+id+'_1" value="">'+
                                                            '<button class="btn btn-success btn-add3" id="addbtn_'+id+'" type="button" title="Add Suppliers" onclick="call_innergrid('+id+')" style="padding: 2px 5px; margin-right: 4px !important;"><span class="glyphicon glyphicon-plus"></span></button>'+
                                                            '<button id="removebtn_'+id+'" class="btn btn-remove btn-danger" type="button" title="Delete Suppliers" onclick="call_innergrid_remove('+id+')" style="padding: 2px 5px; margin-right: 0px !important;"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;<br><input type="radio" checked="checked" name="txt_sltsupplier['+id+'][]" id="txt_sltsupplier_'+id+'_1" value="1" onclick="getrequestvalue('+id+', 1)" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;1<br>';

                                                            if(id == 1) {
                                                                apnd_content += '<input type="checkbox" name="chk_apply_supplier['+id+'][]" id="chk_apply_supplier_'+id+'_1" onclick="apply_supplier('+id+')">';
                                                            }

                                                apnd_content += '</div>'+
                                                    '</div>'+

                                                    '<div class="col-sm-3 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" name="txt_sltsupcode['+id+'][]" id="txt_sltsupcode_'+id+'_1" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" onKeyPress="enable_product();" data-placement="top" title="Supplier" class="form-control supquot find_supcode sub_prd_1" onBlur="validate_supprdempty('+id+', 1); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" name="state['+id+'][]" id="state'+id+'_1" value="" class="sub_prd_1"><span id="txt_taxpercentage_'+id+'_1"></span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_delivery_duration['+id+'][]" id="txt_delivery_duration_'+id+'_1" required="required" maxlength="4" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prdrate['+id+'][]" id="txt_prdrate_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        'Adv.Amount Val.:'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+id+'][]" id="txt_advance_amount_'+id+'_1" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdsgst['+id+'][]" id="txt_prdsgst_'+id+'_1"  onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdcgst['+id+'][]" id="txt_prdcgst_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" readonly name="txt_prdigst['+id+'][]" id="txt_prdigst_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;"> '+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_spldisc['+id+'][]" id="txt_spldisc_'+id+'_1" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="txt_pieceless['+id+'][]" id="txt_pieceless_'+id+'_1" required="required" maxlength="5" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_prddisc['+id+'][]" id="txt_prddisc_'+id+'_1" onblur="calculatenetamount('+id+',1); find_tags();" required="required" maxlength="10" placeholder="Discount % " data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                        '<input type="hidden" onKeyPress="enable_product(); return isNumber(event)" name="hid_prdnetamount['+id+'][]" id="hid_prdnetamount_'+id+'_1" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                                    '</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+id+'_1">0</div>'+

                                                    '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                                        '<input type="file" name="fle_supquot['+id+'][]" id="fle_supquot_'+id+'_1" onchange="ValidateSingleInput(this);" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" onblur="find_tags();" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                                    '</div>'+

                                                    '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                                        '<textarea onKeyPress="enable_product();" name="suprmrk['+id+'][]" id="suprmrk_'+id+'_1" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+id+',1); find_tags();" class="form-control" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<!-- Quotation -->'+

                                        '</div>'+
                                        '<div class="col-sm-1 colheight" style=" border: 1px solid #FFFFFF !important; padding: 1px 0px;"></div>'+
                                    '</div>'+
                                    '</div><script>$("#fle_prdimage_'+id+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});$("#fle_supquot_'+id+'_1").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                $('.parts3').append(apnd_content);
            }

            $('#txt_prdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product'
                        },
                        success: function( data ) {
                            // alert("###"+data+"###");
                            response( $.map( data, function( item ) {
                                return {
                                    label: item,
                                    value: item
                                }
                            }));
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_subprdcode_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           product: $('#txt_prdcode_'+id).val(),
                           depcode: $('#slt_department_asset').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'sub_product'
                        },
                        success: function( data ) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item,
                                    value: item
                                }
                            }));
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_prdspec_'+id).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'product_specification'
                        },
                        success: function( data ) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item,
                                    value: item
                                }
                            }));
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            $('#txt_sltsupcode_'+id+'_1').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           slt_targetno: $('#slt_targetno').val(),
                           action: 'supplier_withcity'
                        },
                        success: function( data ) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item,
                                    value: item
                                }
                            }));
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });
        // });

        var nofsup = $("#hid_nof_suppliers_"+iv).val();
        if(nofsup > 1) {
            for(var nofsupi = 1; nofsupi <= nofsup; nofsupi++){
                call_innergrid(id);
            }
        }
        $('#load_page').hide();
    }

    function call_product_innergrid_remove(gridid) {
        // $("#removebtn").click(function () {
           if ($('.parts3 .part3').length == 0) {
              alert("No more row to remove.");
           }
           var id = ($('.parts3 .part3').length - 1).toString();
           $('#partint3').val(id);
           $(".parts3 .part3:last").remove();
        // });
    }

    function call_innergrid(gridid) {
        $('#load_page').show();
        // alert("**"+gridid);
        // $("#addbtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if( ($('.parts3_'+gridid+' .part3_'+gridid).length+1) > 99) {
                alert("Maximum 100 Suppliers allowed.");
            } else {
                $('[data-toggle="tooltip"]').tooltip();
                // alert("--"+gridid+"--"+$('#txt_sltsupcode_'+gridid).length);
                // var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();
                var gid = (+$('#partint3_'+gridid).val() + 1);
                $('#partint3_'+gridid).val(gid);
                // alert("@@"+gid);
                var apnd_content = '<div class="row part3_'+gridid+'" style="margin-right: -10px; display: flex;">'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<div class="fg-line"><input type="hidden" name="txt_prdsgst_per['+gridid+'][]" id="txt_prdsgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdcgst_per['+gridid+'][]" id="txt_prdcgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdigst_per['+gridid+'][]" id="txt_prdigst_per_'+gridid+'_'+gid+'" value="">'+
                                                '<input type="radio" onclick="getrequestvalue('+gridid+', '+gid+')" name="txt_sltsupplier['+gridid+'][]" id="txt_sltsupplier_'+gridid+'_'+gid+'" value="'+gid+'" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc">&nbsp;'+gid+'<br>';

                                    if(gridid == 1) {
                                        apnd_content += '<input type="checkbox" name="chk_apply_supplier['+gridid+'][]" id="chk_apply_supplier_'+gridid+'_'+gid+'" onclick="apply_supplier('+gid+')">';
                                    }

                                    apnd_content += '</div>'+
                                        '</div><div class="col-sm-3 colheight" style="padding: 1px 0px;">';

                                    if(gridid != 1) {
                                        apnd_content += '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode sub_prd_'+gid+'" onBlur="validate_supprdempty('+gridid+', '+gid+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" class="sub_prd_'+gid+'" value=""><span id="txt_taxpercentage_'+gridid+'_'+gid+'"></span>';
                                    } else {
                                        apnd_content += '<input type="text" name="txt_sltsupcode['+gridid+'][]" id="txt_sltsupcode_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier" data-toggle="tooltip" data-placement="top" title="Supplier" class="form-control supquot find_supcode" onBlur="validate_supprdempty('+gridid+', '+gid+'); find_tags();" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" name="state['+gridid+'][]" id="state'+gridid+'_'+gid+'" value=""><span id="txt_taxpercentage_'+gridid+'_'+gid+'"></span>';
                                    }

                                    apnd_content += '</div>'+
                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" name="txt_delivery_duration['+gridid+'][]" id="txt_delivery_duration_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Delivery Duration / Period" data-toggle="tooltip" data-placement="top" title="Delivery Duration / Period" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdrate['+gridid+'][]" id="txt_prdrate_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" placeholder="Product Per Piece Rate" required="required" maxlength="10" data-toggle="tooltip" data-placement="top" title="Product Per Piece Rate" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            'Adv.Amount Val.:'+
                                            '<input type="text" onKeyPress="enable_product(); return isNumber(event)" name="txt_advance_amount['+gridid+'][]" id="txt_advance_amount_'+gridid+'_'+gid+'" required="required" maxlength="10" placeholder="Advance Amount Value" data-toggle="tooltip" data-placement="top" title="Advance Amount Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdsgst['+gridid+'][]" id="txt_prdsgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();"  required="required" maxlength="10" placeholder="SGST Value" data-toggle="tooltip" data-placement="top" title="SGST Value" class="form-control supquot" readonly style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdcgst['+gridid+'][]" id="txt_prdcgst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" readonly required="required" maxlength="10" placeholder="CGST Value" data-toggle="tooltip" data-placement="top" title="CGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prdigst['+gridid+'][]" id="txt_prdigst_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" readonly required="required" maxlength="10" placeholder="IGST Value" data-toggle="tooltip" data-placement="top" title="IGST Value" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+


                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_spldisc['+gridid+'][]" id="txt_spldisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="5" placeholder="Spl. Discount" data-toggle="tooltip" data-placement="top" title="Spl. Discount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="txt_pieceless['+gridid+'][]" id="txt_pieceless_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="6" placeholder="Piece Less" data-toggle="tooltip" data-placement="top" title="Piece Less" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="text" onKeyPress="return isNumber(event)" name="txt_prddisc['+gridid+'][]" id="txt_prddisc_'+gridid+'_'+gid+'" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" required="required" maxlength="10" placeholder="Discount %" data-toggle="tooltip" data-placement="top" title="Discount %" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                            '<input type="hidden" onKeyPress="return isNumber(event)" name="hid_prdnetamount['+gridid+'][]" id="hid_prdnetamount_'+gridid+'_'+gid+'" required="required" maxlength="12" placeholder="Net Amount" data-toggle="tooltip" data-placement="top" title="Net Amount" class="form-control supquot" style=" text-transform: uppercase;height: 25px;">'+
                                        '</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;" id="id_prdnetamount_'+gridid+'_'+gid+'">0</div>'+

                                        '<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
                                            '<input type="file" name="fle_supquot['+gridid+'][]" id="fle_supquot_'+gridid+'_'+gid+'" onchange="ValidateSingleInput(this); find_tags();" accept=".pdf" data-toggle="tooltip" class="form-control supquot fileselect" data-placement="left" data-toggle="tooltip" data-placement="top" title="Upload Supplier Quotation PDF Document" placeholder="Supplier Quotation" style="height: 25px;"><span style="color:#FF0000; font-size:8px;">NOTE : MANDATORY FIELD WITH ALLOWED ONLY 1 PDF</span>'+
                                        '</div>'+

                                        '<div class="col-sm-2 colheight" style="padding: 1px 0px;">'+
                                            '<textarea onKeyPress="enable_product();" name="txt_suprmrk['+gridid+'][]" id="txt_suprmrk_'+gridid+'_'+gid+'" required="required" maxlength="100" placeholder="Supplier description / Warranty Period / Selected Reason" data-toggle="tooltip" data-placement="top" title="Supplier description / Warranty Period / Selected Reason" onblur="calculatenetamount('+gridid+','+gid+'); find_tags();" class="form-control supquot" style=" text-transform: uppercase; height: 75px; width: 100%;"></textarea>'+
                                        '</div>'+
                                    '</div><script>$("#fle_supquot_'+gridid+'_'+gid+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
                $('.parts3_'+gridid).append(apnd_content);
            }
        // });

        $('#txt_sltsupcode_'+gridid+'_'+gid).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url : 'ajax/ajax_product_details.php',
                    dataType: "json",
                    data: {
                       name_startsWith: request.term,
                       slt_core_department: $('#slt_core_department').val(),
                       slt_targetno: $('#slt_targetno').val(),
                       action: 'supplier_withcity'
                    },
                    success: function( data ) {

                        response( $.map( data, function( item ) {
                            return {
                                label: item,
                                value: item
                            }
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 0
        });
        $('#load_page').hide();
    }

    function call_innergrid_remove(gridid) {
        // alert("**"+gridid);
        // $("#removebtn_"+gridid).click(function () {
            // alert("!!"+gridid);
            if ($('.parts3_'+gridid+' .part3_'+gridid).length == 2) {
                // alert("!!"+gridid);
                alert("No more row to remove, Mantatory 3 Suppliers Quote Required..");
            }else{
                var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length - 1).toString();
                // alert(gridid+"@@"+gid);
                $('#partint3_'+gridid).val(gid);
                $('.parts3_'+gridid+' .part3_'+gridid+':last').remove();
            }
        // });
    }


    $('#frmdate').Monthpicker({
        minValue: $('#minvl').val(),
        maxValue: $('#maxvl').val(),
        onSelect: function () {
            $('#todate').Monthpicker('option', { minValue: $('#frmdate').val() });
            grid_date();
        }
    });

    $('#todate').Monthpicker({
        minValue: $('#minvl').val(),
        maxValue: $('#maxvl').val(),
        onSelect: function () {
            grid_date();
        }
    });

    function apply_supplier(iv) {
        var sltsupcode = $('#txt_sltsupcode_1_'+iv).val();
        var state = $('#state1_'+iv).val();
        var cnt = ($('#partint3').val() + 2);

        if($("#chk_apply_supplier_1_"+iv).is(':checked')) {
            if(sltsupcode != '') {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() == '') { // if empty means update this
                        $('#txt_sltsupcode_'+ii+'_'+iv).val(sltsupcode);
                        $('#state'+ii+'_'+iv).val(state);
                    }
                }
            } else {
                for(var ii = 2; ii <= cnt; ii++) {
                    if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                        $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                        $('#state'+ii+'_'+iv).val('');
                    }
                }
            }
        } else {
            for(var ii = 2; ii <= cnt; ii++) {
                if($('#txt_sltsupcode_'+ii+'_'+iv).val() != '') {
                    $('#txt_sltsupcode_'+ii+'_'+iv).val('');
                    $('#state'+ii+'_'+iv).val('');
                }
            }
        }
    }

    function get_targetdates_readonly() {
        $('#load_page').show();
        var core_deptid = document.getElementById('slt_core_department').value;
        var deptid = document.getElementById('slt_department_asset').value;
        // var slt_branch = document.getElementById('slt_brnch').value;
        var slt_branch = 1;
        var target_no = document.getElementById('slt_targetno').value;
        var slt_submission = document.getElementById('slt_submission').value;
        var slt_approval_listings = document.getElementById('slt_approval_listings').value;
        var currentyr = document.getElementById('currentyr').value;
        if(slt_branch==888){ slt_branch = 100; }

        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget
        if(slt_submission == 1) {
            // console.log("**1");
            $("#id_fixbudget_planner").css("display", "block");
        } else {
            // console.log("**0");
            $("#id_fixbudget_planner").css("display", "none");
        }
        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget

        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
            $("#getmonthwise_budget").css("display", "none");
            // $('#txtrequest_value').val(0);
            $('#txtrequest_value').attr('readonly', true);

            var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
            var alow_prd = 0;
            if(slt_submission == 6 || slt_submission == 7) {
                alow_prd = 1;
            } else if(slt_submission == 1 && slt_fixbudget_planner == 'PRODUCTWISE') {
                alow_prd = 1;
            }

            if(alow_prd == 1) {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {
                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    // $("#txtrequest_value").val(0);
                                    // $("#hidrequest_value").val(0);
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("!1!");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    $('#load_page').hide();
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }

            } else {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {
                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    // $("#txtrequest_value").val(0);
                                    // $("#hidrequest_value").val(0);
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("@2@");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        /* if(slt_submission == 1) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        } */
                                        if(slt_submission == 1 && alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                        $('#load_page').hide();
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
            }
            // $('#load_page').hide();
        } else {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', false);
            $('#load_page').hide();
        }
        find_tags();
    }

    function get_targetdates() {
        $('#load_page').show();
        var target_no = document.getElementById('slt_targetnos').value;
        ////////////// ** ////////////// 113!!AIR CONDITIONER!!10!!4!!20!!Y
        var target_no1 = target_no.split("||");
        var target_no2 = target_no1[1].split('!!');
        $('#hidd_depcode').val(target_no2[0]);
        $('#hidd_depname').val(target_no2[1]);
        $('#hidd_expsrno').val(target_no2[2]);
        $('#hidd_multireq').val(target_no2[3]);
        $('#slt_targetno').val(target_no1[0]);
        $('#slt_department_asset').val(target_no2[0]);
        $('#slt_core_department').val(target_no2[2]);
        var slt_submission = document.getElementById('slt_submission').value;

        var brnch_y_n = target_no2[5];
        var strURL1 = "ajax/ajax_branchview.php?action=edit&brnch_y_n="+brnch_y_n;
        $.ajax({
            type: "POST",
            url: strURL1,
            success: function(data) {
                $('#id_branchview').html(data);
            }
        });
        find_checklist();

        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget
        if(slt_submission == 1) {
            // console.log("**1");
            $("#id_fixbudget_planner").css("display", "block");
        } else {
            // console.log("**0");
            $("#id_fixbudget_planner").css("display", "none");
        }
        // To Display Monthwise Budget or Productwise Budget only for Fixed Budget

        // alert('row-cal');
        var core_deptid = document.getElementById('slt_core_department').value;
        var deptid = document.getElementById('slt_department_asset').value;
        // var slt_branch = document.getElementById('slt_brnch').value;
        var slt_branch = 1;
        var slt_approval_listings = document.getElementById('slt_approval_listings').value;
        var currentyr = document.getElementById('currentyr').value;
        if(slt_branch==888){ slt_branch = 100; }

        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', true);
            var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
            var alow_prd = 0;
            if(slt_submission == 6 || slt_submission == 7) {
                alow_prd = 1;
            } else if(slt_submission == 1 && slt_fixbudget_planner == 'PRODUCTWISE') {
                alow_prd = 1;
            }

            if(alow_prd == 1) {
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {

                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("#3#");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                    // grid_date();
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper id_supplier
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
                getsubtype_value();
                $('#load_page').hide();
            } else { // Only for Fixed Budget getmonthwise_budget
                var strURL="ajax/ajax_get_targetdt.php?slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings;
                var req1 = getXMLHTTP();
                if (req1) {
                    req1.onreadystatechange = function() {
                        if (req1.readyState == 4) {
                            if (req1.status == 200) {
                                if(req1.responseText == "1") {

                                } else {
                                    document.getElementById('id_budplanner').innerHTML=req1.responseText;
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        calculate_sum();
                                    }

                                    // alert("$4$");
                                    if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                                        if(slt_submission == 1 && alow_prd == 0) {
                                            $("#getmonthwise_budget").css("display", "block");
                                        }
                                        $(".ttlsumrequired").attr('required', true);
                                        fix_top_subcore();
                                    } else {
                                        $(".ttlsumrequired").attr('required', false);
                                    }
                                    // grid_date();
                                }
                            } else {
                                alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                            }
                        }
                    }
                    req1.open("POST", strURL, true);
                    req1.send(null);
                }

                if(req1.responseText != "1") {
                    var wrapper = $(".monthyr_wrap"); //Fields wrapper id_supplier
                    $(wrapper).html('');
                    $(wrapper).append(req1.responseText);
                }
            }
            getsubtype_value();
            $('#load_page').hide();
        } else {
            $("#getmonthwise_budget").css("display", "none");
            $('#txtrequest_value').attr('readonly', false);
            $('#load_page').hide();
        }
        // gettopcore();
        get_sub_core();
        find_tags();
    }

    function fix_top_subcore() {
    }

    function grid_date() {
        $('#load_page').show();
        var frdt    = $('#frmdate').val();
        var todt    = $('#todate').val();
        var ii      = '';
        var fstmnth = '';
        var lstmnth = '';
        var ivl     = 0;
        var wrapper = $(".monthyr_wrap"); //Fields wrapper
        $(wrapper).html('');
        if(todt != '') {
            var fdt = frdt.split("/");
            var tdt = todt.split("/");
            if(fdt[1] == tdt[1]) {
                for(var i = fdt[0]; i <= tdt[0]; i++) { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+fdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+fdt[1]+"'><span>"+ii+", "+fdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+fdt[1];
            } else {
                for(var i = fdt[0]; i <= 12; i++) { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+fdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+fdt[1]+"'><span>"+ii+", "+fdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+fdt[1];

                for(var i = 1; i <= tdt[0]; i++)
                { ivl++;
                    if(i < 10 && i.length == 2) {
                        i = i.substring(1);
                    }
                    ii = findmonth(i);
                    if(ivl == 1) {
                        fstmnth = i+","+tdt[1];
                    }

                    $(wrapper).append(
                        "<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_"+i+"' class='form-control' value='"+i+","+tdt[1]+"'><span>"+ii+", "+tdt[1]+"</span> : </td>"+
                        "<td style='width:5%;'></td><td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_"+i+"' class='form-control ttlsum' value='' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero("+i+", this.value);' maxlength='10' style='margin: 2px 0px;'></td><td style='width:30%;'><span id='id_remainingvalue_"+i+"'></span></td></tr>"
                    );
                }
                lstmnth = (i-1)+","+tdt[1];
            }

            // alert("FIR - "+fstmnth); alert("LST - "+lstmnth);
            $('#fstmnth').val(fstmnth);
            $('#lstmnth').val(lstmnth);
            var dirq = 0;

            $(wrapper).append(
                "<tr><td colspan='2' style='width:40%; text-align:right; padding-right:10%; font-weight:bold;'>TOTAL : </td><td style='width:60%; font-weight:bold;'><span id='ttl_mntyr'>"+dirq+"</span></td></tr>"
            );

            $("#frmdate").css("display", "none");
            $("#todate").css("display", "none");
            $(".monthpicker_input").css("display", "none");
            $(".monthpicker_selector").css("display", "none");
        }
        $('#load_page').hide();
    }

    function allow_zero(ivalue, currentvalue, lockvalue) {
        $('#load_page').show();
        var fstmnth = $('#fstmnth').val();
        var lstmnth = $('#lstmnth').val();
        var mnt_yr  = $('#mnt_yr_'+ivalue).val();
        var mnt_yr_amt  = $('#mnt_yr_amt_'+ivalue).val();
        var txtrequest_value = $('#txtrequest_value').val();
        var ttl_lock = $('#ttl_lock').val();
        // console.log(ttl_lock+"********"+txtrequest_value);
        if(parseInt(ttl_lock) >= parseInt(txtrequest_value)) {
            if(((fstmnth == mnt_yr) || (lstmnth == mnt_yr)) && currentvalue == 0) {
                alert("Zero is not allowed here!!");
                $('#mnt_yr_amt_'+ivalue).val('0');
            }
        } else {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Maximum "+ttl_lock+" value only allowed here.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            $('#mnt_yr_amt_'+ivalue).val('');
            $('#mnt_yr_amt_'+ivalue).focus();
        }
        $('#load_page').hide();
    }

    function showmode() {
        var budgettype = $('#slt_submission').val();
        if(budgettype == 1 || budgettype == 6 || budgettype == 7) {
        // if(budgettype == 1 || budgettype == 6) {
            $("#id_budgetmode").css("display", "block");
        } else {
            $("#id_budgetmode").css("display", "none");
        }
    }

    function findmonth(i) {
        var ii = '';
        if(i == 1){
            ii = 'JAN';
        } else if(i == 2){
            ii = 'FEB';
        } else if(i == 3){
            ii = 'MAR';
        } else if(i == 4){
            ii = 'APR';
        } else if(i == 5){
            ii = 'MAY';
        } else if(i == 6){
            ii = 'JUN';
        } else if(i == 7){
            ii = 'JUL';
        } else if(i == 8){
            ii = 'AUG';
        } else if(i == 9){
            ii = 'SEP';
        } else if(i == 10){
            ii = 'OCT';
        } else if(i == 11){
            ii = 'NOV';
        } else if(i == 12){
            ii = 'DEC';
        }
        return ii;
    }

    function calculate_sum() {
        $('#load_page').show();
        var ttl_lock = $("#ttl_lock").val();
        var total = 0;
        var $changeInputs = $('input.ttlsum');
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });

        var lockchk = 1;
        var slt_submission = $('#slt_submission').val();
        if((parseInt(ttl_lock) <= 0 && parseInt(total) <= parseInt(ttl_lock)) && (slt_submission == 6 || slt_submission == 1)) {
            lockchk = 0;
        }

        // console.log("||"+parseInt(ttl_lock)+"||"+parseInt(total)+"||"+lockchk+"||"+slt_submission+"||");
        if(parseInt(ttl_lock) > 0 && lockchk == 1) {
            document.getElementById('hidrequest_value').value = total;
            document.getElementById('txtrequest_value').value = total;
            document.getElementById('txt_brnvalue_0').value = total;
            document.getElementById('ttl_mntyr').innerHTML = total;
        }else{
            total = 0;
            document.getElementById('hidrequest_value').value = total;
            document.getElementById('txtrequest_value').value = total;
            document.getElementById('txt_brnvalue_0').value = total;
            document.getElementById('ttl_mntyr').innerHTML = total;
        }
        // getapproval_listings();
        $('#load_page').hide();
    }

    function calculate_sum_total(val , class_name) {

        var $changeInputs = $('input.'+class_name);
        var $checkInputs = $('input#txtesi_no_emp_'+val).val();
        var $checkInputs_value = $('input#txtesi_value_'+val).val();
        if ($checkInputs != '' && $checkInputs_value != '') {
          var total = 0;
          $changeInputs.each(function(idx, el) {
                total += Number($(el).val());
          });
          document.getElementById('hidrequest_value').value = total;
          document.getElementById('txtrequest_value').value = total;
          document.getElementById('txt_brnvalue_0').value = total;
          document.getElementById('ttl_mntyr').innerHTML = total;
          //$('#load_page').hide();
        }else {
          $('input#txtesi_value_'+val).val('');
        }
    }

    function addempgift(id)
    {
        $('#load_page').show();
        var empcode = $("#txt_staffcode_"+id).val();
        var brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_staffchange.php?action=marriagegift&empcode="+empcode+"&id="+id+"&brncode="+brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#photo_"+id).html(data);
                var hid_data = $("#hid_emp_"+id).val();
                var val = hid_data.split("~");
                $('#curexp_'+id).val(val[0]);
                $('#curdoj_'+id).val(val[1]);
                $('#curbrn_'+id).val(val[2]);
                $('#curdep_'+id).val(val[3]);
                $('#curdes_'+id).val(val[4]);
                $('#owngift_'+id).val(val[5]);
                $('#trustamt_'+id).val(val[6]);
                $('#empsrno_'+id).val(val[7]);
                calculate_sum();
                $('#load_page').hide();
            }
        });
    }

    function coladd_new()
    {
        $('#load_page').show();
        var cnt = $("#noofcol").val();
        $("#default_lock").val(cnt);
        var slt_approval_listings = $('#slt_approval_listings').val();
        if (cnt != ""){
        var strURL="ajax/ajax_general_temp.php?action=COLADD&cnt="+cnt+"&slt_approval_listings="+slt_approval_listings;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data) {
                    $("#genearl_temp").html(data);
                    $("#createtemp").css("display", "block");
                    $.getScript("js/jquery-customselect.js");
                        //$(".chosn").customselect();
                        $("#allcal").val(0);
                        // $('.select2').select2();
                    $("#sbmt_request").prop("disabled", true);
                }
            });
        }else{
            alert("Enter No Of Columns Required ...!");
            $("#noofcol").focus();
        }
        $('#load_page').hide();
    }

    function create_temp()
    {
        $('#load_page').show();
        var col = [];
        var  empty = 0;
        var cnt = $("#noofcol").val();
        for (i=0;i<=cnt;i++)
        {
            col[i]  = $('#a_'+i).val();
            if(col[i] == "")
            {
                empty = 1;
                empty = parseInt(empty);
            }
        }

        var check = [];
        for (i=0;i<=cnt;i++)
        {
           if ($('#b_'+i).is(':checked'))
            {
            check[i]  = $('#b_'+i).val();
            }else{
                check[i]  = "N";
            }

        }

        if(empty == 1)
        {
            alert(" Head Details Cannot Be Empty ...!");
        }else{
            var cnt = $("#default_lock").val();
        $("#hid_default_lock").val(cnt);
        var apmcode = $("#slt_approval_listings").val();
        var alw_cal = $("#allcal").val();
        var res_head = $("#slt_res_head").val();
        var cal_head = $("#slt_head").val();
        //var cal_opt = $("#slt_cal").val();
        var cal_opt = $('input[name="slt_cal"]:checked').val();
        var strURL="ajax/ajax_general_temp.php?action=COLINSERT&cnt="+cnt+"&apmcode="+apmcode+"&alw_cal="+alw_cal+"&res_head="+res_head+"&cal_head="+cal_head+"&cal_opt="+cal_opt;
            $.ajax({
                type: "POST",
                url: strURL,
                data: {col:col,check:check},
                success: function(data) {
                    $("#general").css("display", "none");
                    data = data.replace(/\s/g,'');
                    $("#tempidcnt").val(data);
                    load_gen_temp(data);
                    $("#sbmt_request").prop("disabled", false);
                }
            });
        }
        $('#load_page').hide();
    }

    function get_tablemaster() {
        var depcode = $('#slt_department_asset').val();
        $.ajax({
            url:"ajax/ajax_product_details.php?action=table_master&depcode="+depcode,
            success:function(data)
            {
                $("#myModal1").modal('show');
                $('#modal-body1').html(data);
            }
        });
    }

    function load_gen_temp(cnt)
    {
        $('#load_page').show();
        var apmcode = $("#slt_approval_listings").val();
        var strURL="ajax/ajax_general_temp.php?action=LOADVIEW&cnt="+cnt+"&apmcode="+apmcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#id_supplier").html(data);
                $("#partint3").val(1);
                $('#load_page').hide();
            }
        });
    }

    function blinker() {
        $('.blinking').fadeOut(500);
        $('.blinking').fadeIn(500);
    }
    setInterval(blinker, 1000);

    function back_col()
    {
        call_dynamic_option();
        $("#tempidcnt").val(0);
        $("#allcal").val(0);
    }

    function gen_calculation(field,calmode,resfld,index,colsrno)
    {
        $('#load_page').show();
        var val = field.split("~");
        var tot = 0;
        for(i=0;i<val.length;i++){
            var n_val = $("#a_"+val[i]+'_'+index).val();
            console.log("#a_"+val[i]+'_'+index);
            tot = parseFloat(tot);
            n_val = parseFloat(n_val);
            if(tot == 0)
            {
                tot = n_val;
            }else{
                switch(calmode){
                    case "M":
                        tot = +tot * +n_val;
                    break;
                    case "D":
                        tot = (+n_val / +tot) * 100 ;
                    break;
                    case "A":
                        tot = +tot + +n_val;
                    break;
                    case "S":
                        tot = +tot - +n_val;
                    break;
                }
            if (isNaN(tot))
            {
            }else{
                tot = tot.toFixed(2);
                $("#a_"+resfld+'_'+index).val(tot);
            }
            }
        }
        var total = 0;
        var $changeInputs = $('input.ttlsum_gen_'+colsrno);
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });
        $("#tot_"+colsrno).html(total);

        var total = 0;
        var $changeInputs = $('input.ttlsum_gen_'+resfld);
        $changeInputs.each(function(idx, el) {
            // alert("****"+$(el).val());
            total += Number($(el).val());
        });

        $("#tot_"+resfld).html(total);
        $('#load_page').hide();
    }

    function default_view()
    {
        if ($('#alw_create').is(':checked')) {
            $("#general").css("display", "block");
        }else{
            $("#general").css("display", "none");
        }
    }

    function Add_cal()
    {
        var mulhead = "";
        mulhead = $("#slt_head").val();
        if(mulhead == null)
        {
            alert("Select Calculation Fields ...!");
            $('#slt_head').focus();
        }else{
            var res = $('#slt_res_head').val();
            $('#b_'+res).prop('checked',true);
            $("#allcal").val(1);
            $("#addcal").prop("disabled", true);
        }
    }

    function add_gen_row()
    {
        var row = $("#partint3").val();
        var nrow = parseInt(row)+1;
        var apmcode = $("#slt_approval_listings").val();
        var cnt = $("#tempidcnt").val();
        var strURL="ajax/ajax_general_temp.php?action=ADDROW&cnt="+cnt+"&apmcode="+apmcode+"&row="+row;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#partint3").val(nrow);
                $('.parts3').append(data);
            }
        });
    }

    function remove_gen_row() {
       if ($('.part3').length == 1) {
          alert("No more row to remove.");
       }else{
           var id = ($('.part3').length - 1).toString();
           $(".part3:last").remove();
           $('#partint3').val(id);
       }
    }

    function call_dynamic_option() {
        $('#load_page').show();
        var core_deptid = $("#slt_core_department").val();
        var deptid = $("#slt_department_asset").val();
        var slt_branch = $("#slt_brnch_0").val();
        var target_no = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var slt_fixbudget_planner = $("#slt_fixbudget_planner").val();
        var slt_approval_listings = $("#slt_approval_listings").val();
        var currentyr = $("#currentyr").val();
        var view = $("#view_stat").val();
        if(slt_branch == 888) { slt_branch = 100; }

        var alow_prd = 1;
        if(slt_submission == 1 && slt_fixbudget_planner == 'MONTHWISE') {
            alow_prd = 0;
        }
        // console.log('********'+alow_prd+'********');

        $("#id_supplier").html('');
        $("#getmonthwise_budget").css("display", "none");
        if(slt_approval_listings != '' && alow_prd == 1) {
            var strURL="ajax/ajax_dynamic_option.php?action=add_edit&slt_branch="+slt_branch+"&deptid="+deptid+"&core_deptid="+core_deptid+"&target_no="+target_no+"&slt_submission="+slt_submission+"&slt_approval_listings="+slt_approval_listings+"&view="+view;
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    /* $.getScript("js/zebra_datepicker.js");
                    $.getScript("js/plugins/jquery/jquery-ui.min.js");
                    $.getScript("js/jquery-ui-1.10.3.custom.min.js");

                    $('#datepicker_example5').Zebra_DatePicker({
                      direction: true,
                      format: 'd-M-Y'
                    });

                    $('#datepicker_example6').Zebra_DatePicker({
                      direction: true,
                      format: 'd-M-Y'
                    }); */
                    $.getScript("ajax/ajax_staff_change.js");


                    if(data1 == 0) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Dynamic Approval Listing loading failed. Kindly try again!!";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $('#load_page').hide();
                    } else {

                        // alert(data1);
                        // $.getScript("chart/js/plugin/sample_order_script.js");
                        $("#id_supplier").html(data1);
                        change_readonly();
                        $('#hid_default_lock').val(0);
                        if ( $( "#default_lock" ).length ) {
                            $("#sbmt_request").prop("disabled", true);
                        }

                        $("#id_policy_approval").css("display", "none");
                        $(".policy_approval_required").attr('required', false);
                        var pathid = $('#hid_app_path').val();
                        if(pathid == 1)
                        {
                            var nofsup = $("#hid_nof_suppliers_1").val();
                            if(nofsup > 1) {
                                for(var nofsupi = 1; nofsupi <= nofsup; nofsupi++){
                                    call_innergrid(1);
                                }
                            }
                        }
                        else if(pathid == 13)
                        {
                            $("#id_policy_approval").css("display", "block");
                            $(".policy_approval_required").attr('required', true);
                        }

                        var id = 1;
                        $('#fle_supquot_'+id+'_1').filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
                        $('#fle_prdimage_'+id).filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});
                        $('#txt_prdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       depcode: $('#slt_department_asset').val(),
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'product'
                                    },
                                    success: function( data ) {
                                        // alert("###"+data+"###");
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_subprdcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       product: $('#txt_prdcode_'+id).val(),
                                       depcode: $('#slt_department_asset').val(),
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'sub_product'
                                    },
                                    success: function( data ) {
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_prdspec_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'product_specification'
                                    },
                                    success: function( data ) {
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_sltsupcode_'+id+'_1').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_product_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
                                       slt_targetno: $('#slt_targetno').val(),
                                       action: 'supplier_withcity'
                                    },
                                    success: function( data ) {
                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_suppliercode').autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/get_supplier_details.php',
                                    dataType: "json",
                                    data: {
                                       name_startsWith: request.term,
                                       slt_core_department: $('#slt_core_department').val(),
                                       action: 'supplier_details'
                                    },
                                    success: function( data ) {

                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_staffcode_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_employee_details.php',
                                    dataType: "json",
                                    data: {
                                       slt_emp: request.term,
                                       brncode: $('#slt_brnch_0').val(),
                                       action: 'allemp'
                                    },
                                    success: function( data ) {

                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });

                        $('#txt_reportto_'+id).autocomplete({
                            source: function( request, response ) {
                                $.ajax({
                                    url : 'ajax/ajax_employee_details.php',
                                    dataType: "json",
                                    data: {
                                       slt_emp: request.term,
                                       brncode: $('#slt_branch').val(),
                                       action: 'allbrnemp'
                                    },
                                    success: function( data ) {

                                        response( $.map( data, function( item ) {
                                            return {
                                                label: item,
                                                value: item
                                            }
                                        }));
                                    }
                                });
                            },
                            autoFocus: true,
                            minLength: 0
                        });


                        /* if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
                            calculate_sum();
                            $(".ttlsumrequired").attr('required', true);
                        } else {
                            $(".ttlsumrequired").attr('required', false);
                        } */

                        if(slt_submission == 7)
                        {
                            $('#ttl_lock').val(10000000000000);
                        }
                        var ttl_lock = $('#ttl_lock').val();
                        if(ttl_lock != '') {
                            if(ttl_lock == 10000000000000) {
                                $('#budgt_vlu').html('');
                            } else {
                                if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) {
                                    $('#budgt_vlu').html(' - Budget Value - '+ttl_lock);
                                }
                            }
                        }
                        $('#load_page').hide();
                    }
                }
            });
        }
        find_tags();
    }

    function getbrn(id) {
        $('#load_page').show();
        var sendurl = "ajax/ajax_staffchange.php?action=GETBRN&id="+id;
        $.ajax({
        url:sendurl,
        success:function(data){
                document.getElementById('staff_branch_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }


    function getdept(id) {
        $('#load_page').show();
        var brncode = $("#newbrn_"+id).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var sendurl = "ajax/ajax_staffchange.php?brncode="+brncode+"&action=GETDEPT&id="+id+"&slt_brncode="+slt_brncode;
        $.ajax({
        url:sendurl,
        success:function(data){
                getdes(id);
                document.getElementById('staff_dept_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }

    function getdes(id) {
        $('#load_page').show();
        var brncode = $("#newbrn_"+id).val();
        var slt_brncode = $("#slt_brnch_0").val();
        var sendurl = "ajax/ajax_staffchange.php?brncode="+brncode+"&action=GETDES&id="+id+"&slt_brncode="+slt_brncode;
        $.ajax({
        url:sendurl,
        success:function(data){
                document.getElementById('staff_des_'+id).innerHTML=data;
                $.getScript("js/jquery-customselect.js");
                $(".chosn").customselect();
                $('#load_page').hide();
            }
        });
    }


    function addstaff(id,mode)
    {
        $('#load_page').show();
        var empcode = $("#txt_staffcode_"+id).val();
        var brncode = $("#slt_brnch_0").val();
        var strURL="ajax/ajax_staffchange.php?action=transfer&empcode="+empcode+"&id="+id+"&brncode="+brncode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {

                $("#photo_"+id).html(data);

                var hid_data = $("#hid_emp_"+id).val();
                var val = hid_data.split("~");
                el  = parseFloat(val[0]);

                $('#curexp_'+id).val(el);
                $('#curdoj_'+id).val(val[1]);
                // $('#empphoto_'+id).val(val[10]);
                $('#empsrno_'+id).val(val[11]);

                $('#curbrn_'+id).val(val[2]);
                $('#curdep_'+id).val(val[3]);
                $('#curdes_'+id).val(val[4]);

                if(mode == 2)
                {
                    $('#curbas_'+id).val(val[5]);
                }
                if(mode == 3)
                {
                    $('#cbas_'+id).html(val[5]);
                    $('#ccomm_'+id).html(val[6]);
                }
                $('#load_page').hide();
            }
        });
    }

    function newbasic(id)
    {
        var basic = parseInt($("#curbas_"+id).val());
        var nbasic  = parseInt($("#newbas_"+id).val());
        tot = nbasic-basic;
        $('#incamt_'+id).val(tot);
    }

    function checkform_check() {
    $("#sbmt_request").click(function(e){
        $('#load_page').show();
        // alert("***************************");
        var enable1 = 0; var enable2 = 0; var enable3 = 0; var enable4 = 0; var gtvl = 1;
        var enable5 = 0; var enable6 = 0; var enable7 = 0; var enable8 = 0; var enable9 = 0;
        var slt_targetno   = $("#slt_targetno").val();
        var slt_submission = $("#slt_submission").val();
        var strURL = "ajax/ajax_validate.php?action=fix_checklist&slt_targetno="+slt_targetno+"&slt_submission="+slt_submission;
        $.ajax({
            type: "POST",
            url: strURL,
            // async: false,
            success: function(data1) {
                var chklist = data1.split(",");
                for (var exp_chklisti=0; exp_chklisti < chklist.length; exp_chklisti++) {
                    if(chklist[exp_chklisti] == 1) {
                        enable1 = 1;
                    }

                    if(chklist[exp_chklisti] == 2) {
                        enable2 = 1;
                    }

                    if(chklist[exp_chklisti] == 3) {
                        enable3 = 1;
                    }

                    if(chklist[exp_chklisti] == 4) {
                        enable4 = 1;
                    }

                    if(chklist[exp_chklisti] == 5) {
                        enable5 = 1;
                    }

                    if(chklist[exp_chklisti] == 6) {
                        enable6 = 1;
                    }

                    if(chklist[exp_chklisti] == 7) {
                        enable7 = 1;
                    }

                    if(chklist[exp_chklisti] == 8) {
                        enable8 = 1;
                    }

                    if(chklist[exp_chklisti] == 9) {
                        enable9 = 1;
                    }
                }

        var tot_d= $("#hid_default_lock").val();
        if ( tot_d != 0 ) {
            var gtd = 0;
            j=1;
            for(i=0;i<tot_d;i++)
            {j++;
                var gtd_val = $("#a_"+j+"_1").val();
                if(gtd_val != "")
                {
                    gtd++;
                }
            }
            if(gtd == 0)
            {
                var ALERT_TITLE = "Message";
                var ALERTMSG = "General Format Cannot Be Empty";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                $('#load_page').hide();
                gtvl = 0 ;
            }
        }
        if (frm_request_entry.slt_submission.value == "") {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose the type of submission";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            // frm_request_entry.slt_submission.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.slt_approval_listings.value == "") {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose the approval listings";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            // frm_request_entry.slt_approval_listings.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txt_workintiator.value == "" || frm_request_entry.txt_workintiator.value == " -  - ") {
            frm_request_entry.txt_workintiator.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the Work Initiate Person user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_workintiator.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txt_workintiator.value == "" || frm_request_entry.txt_workintiator.value == " -  - ") {
            frm_request_entry.txt_workintiator.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the Work Initiate Person user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_workintiator.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        } // txt_submission_reqby



    // Attachments
    <?  /* $sql_chklist = select_query_json("select * from approval_checklist where deleted = 'N' and tarnumb = 7515 order by apcklst");
        // echo "select * from approval_checklist where deleted = 'N' and tarnumb = ".$('#slt_targetno').val()." order by apcklst";
        foreach ($sql_chklist as $key => $chklist_value) {
            $exp_chklist = explode(",", $chklist_value['CKLSTCD']);
        }

        $enable1 = 0; $enable2 = 0; $enable3 = 0; $enable4 = 0;
        $enable5 = 0; $enable6 = 0; $enable7 = 0; $enable8 = 0; $enable9 = 0;
        for ($exp_chklisti=0; $exp_chklisti < count($exp_chklist); $exp_chklisti++) {
            switch ($exp_chklist[$exp_chklisti]) {
                case 1:
                    $enable1 = 1;
                    break;
                case 2:
                    $enable2 = 1;
                    break;
                case 3:
                    $enable3 = 1;
                    break;
                case 4:
                    $enable4 = 1;
                    break;
                case 5:
                    $enable5 = 1;
                    break;

                case 6:
                    $enable6 = 1;
                    break;
                case 7:
                    $enable7 = 1;
                    break;
                case 8:
                    $enable8 = 1;
                    break;
                case 9:
                    $enable9 = 1;
                    break;

                default:
                    # code...
                    break;
            }
        } */ ?>

        alert("=="+enable1+"=="+enable2+"=="+enable3+"=="+enable4+"=="+enable5+"=="+enable6+"=="+enable7+"=="+enable8+"=="+enable9+"==");
        if(enable1 == 1) {
            if (frm_request_entry.txt_submission_quotations.value == "" && frm_request_entry.txt_submission_quotations_remarks.value == "") {
                frm_request_entry.txt_submission_quotations_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Quotations & Estimations..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_quotations_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable2 == 1) {
        // alert("=="+frm_request_entry.txt_submission_fieldimpl.value+"=="+frm_request_entry.txt_submission_fieldimpl_remarks.value+"==");
            if (frm_request_entry.txt_submission_fieldimpl.value == "" && frm_request_entry.txt_submission_fieldimpl_remarks.value == "") {
                // alert("CAME1");
                frm_request_entry.txt_submission_fieldimpl_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Budget / Common Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_fieldimpl_remarks.focus();
                $('#load_page').hide();
                // alert("CAME2");
                gtvl = 0;
            }
            // alert("CAME3");
        }

        if(enable3 == 1) {
            if (frm_request_entry.txt_submission_clrphoto.value == "" && frm_request_entry.txt_submission_clrphoto_remarks.value == "") {
                frm_request_entry.txt_submission_clrphoto_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Work Place Before / After Photo / Drawing Layout..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_clrphoto_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable4 == 1) {
            if (frm_request_entry.txt_submission_artwork.value == "" && frm_request_entry.txt_submission_artwork_remarks.value == "") {
                frm_request_entry.txt_submission_artwork_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Art Work Design with MD Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_artwork_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable5 == 1) {
            if (frm_request_entry.txt_submission_othersupdocs.value == "" && frm_request_entry.txt_submission_othersupdocs_remarks.value == "") {
                frm_request_entry.txt_submission_othersupdocs_remarks.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the remarks for the Consultant Approval..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_submission_othersupdocs_remarks.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable6 == 1) {
            if (frm_request_entry.txt_warranty_guarantee.value == "") {
                frm_request_entry.txt_warranty_guarantee.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Warranty / Guarantee details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_warranty_guarantee.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable7 == 1) {
            if (frm_request_entry.txt_cur_clos_stock.value == "") {
                frm_request_entry.txt_cur_clos_stock.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Current / Closing Stock details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_cur_clos_stock.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable8 == 1) {
            if (frm_request_entry.txt_advpay_comperc.value == "") {
                frm_request_entry.txt_advpay_comperc.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Advance or Final Payment / Work Completion Percentage details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_advpay_comperc.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }

        if(enable9 == 1) {
            if (frm_request_entry.datepicker_example4.value == "") {
                frm_request_entry.datepicker_example4.value = '';
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Kindly fill the Work Finish Target Date details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.datepicker_example4.focus();
                $('#load_page').hide();
                gtvl = 0;
            }
        }
        // Attachments

        // exit;

        if (frm_request_entry.txt_submission_reqby.value == "" || frm_request_entry.txt_submission_reqby.value == " -  - ") {
            frm_request_entry.txt_submission_reqby.value = '';
            // alert( "Please enter the submission request by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the responsible user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_submission_reqby.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txt_submission_reqby.value == frm_request_entry.txt_alternate_user.value) {
            frm_request_entry.txt_alternate_user.value = '';
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Responsible User & Alternate User must be the different users.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txt_alternate_user.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        description1=CKEDITOR.instances['FCKeditor1'].getData().replace(/<[^>]*>/gi, '');
        if(description1=='')
        {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the details";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            CKEDITOR.instances['FCKeditor1'].focus();
            $('#load_page').hide();
            gtvl = 0;
        }
        if (frm_request_entry.txtfrom_date.value == "") {
            // alert( "Please enter the from date" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the from date";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtfrom_date.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtto_date.value == "") {
            // alert( "Please enter the to date" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the to date";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtto_date.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if (frm_request_entry.txtnoofhours.value == "") {
            // alert( "Please enter the no of hours" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the no of hours";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtnoofhours.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtnoofdays.value == "") {
            // alert( "Please enter the no of days" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the no of days";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtnoofdays.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }

        if($('#getmonthwise_budget').css('display') == 'block') {


             var myElem = document.getElementById('frmdate');
            if (myElem === null) {
                // alert( "Budget Planner is not available here. Kindly Verify the Target No here.." );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Budget Planner is not available here. Kindly Verify the Target No here..";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                document.getElementById('slt_targetno').focus();
                $('#slt_targetno').focus();
                frm_request_entry.slt_targetno.focus();
                $('#load_page').hide();
                gtvl = 0;
            } else {
                /*if (frm_request_entry.frmdate.value == "") {
                    // alert( "Please enter the from month" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter the from month";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.frmdate.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
                }
                if (frm_request_entry.todate.value == "") {
                    // alert( "Please enter the to month" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter the to month";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.todate.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
                }
                if (frm_request_entry.txtrequest_value.value == "0" || frm_request_entry.txtrequest_value.value == "") {
                    // alert( "Please enter some value here" );
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "Please enter some value here";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    frm_request_entry.txtrequest_value.focus();
                    $('#load_page').hide();
                    gtvl = 0 ;
            } */
            }
        }

        if($("#slt_submission").val() == 1 || $("#slt_submission").val() == 6 || $("#slt_submission").val() == 7) {
            $(".supquot").prop('required', true); // Add required field option for all productwise budget page
            /* if (frm_request_entry.txt_suppliercode.value == "") {
                // alert( "Please enter the details" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter the Supplier Details";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_suppliercode.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            }
            if (frm_request_entry.txt_supplier_contactno.value == "") {
                // alert( "Please enter the details" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter the Supplier Contact No";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txt_supplier_contactno.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            } */

            if (frm_request_entry.txtrequest_value.value == "0" || frm_request_entry.txtrequest_value.value == "") {
                // alert( "Please enter some value here" );
                var ALERT_TITLE = "Message";
                var ALERTMSG = "Please enter some value here";
                createCustomAlert(ALERTMSG, ALERT_TITLE);
                frm_request_entry.txtrequest_value.focus();
                $('#load_page').hide();
                gtvl = 0 ;
            }

            /* var fileselect = (".fileselect");
            $(fileselect).each(function () {
                validate_upload(this);
            }); */
        } else {
            // remove required field option for all productwise budget page
            $(".supquot").prop('required', false);
            // remove required field option for all productwise budget page
        }

        if (frm_request_entry.datepicker_example3.value == "") {
            // alert( "Please choose any implementation due date here" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please choose any implementation due date here";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.datepicker_example3.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtrequest_by.value == "") {
            // alert( "Please enter the submission prepared by user" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the submission prepared by user";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtrequest_by.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        if (frm_request_entry.txtrequest_byid.value == "") {
            // alert( "Please enter the submission prepared by user id" );
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the submission prepared by user id";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            frm_request_entry.txtrequest_byid.focus();
            $('#load_page').hide();
            gtvl = 0 ;
        }
        // alert("WR"+gtvl+"ONG");
        $('#load_page').hide();

        if(gtvl == 1) {
            $('#hid_gtvl').val(gtvl);
            $('#frm_request_entry').submit();
        }
        }
    });
});
}

    function validate_upload(field) {
        var fieldVal = $(field).val();
        if(!fieldVal) {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "No Supplier Quotation PDF Attached. Kindly add 1 Quotation PDF!!";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
        }
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode > 31 && charCode != 39 && charCode != 34 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function isNumberWithoutDot(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode > 31 && charCode != 39 && charCode != 34 && charCode != 46 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function numwodot(evt)
    {
       if ((evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
        }
    }

    function isQuotes(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        // alert(charCode);
        if (charCode == 39 || charCode == 34) {
            return false;
        }
        return true;
    }

    $('#datepicker_example3').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
      format: 'd-M-Y'
    });

    $('#datepicker_example4').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example5').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example6').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datepicker_example7').Zebra_DatePicker({
      direction: true,
      format: 'd-M-Y'
    });

    $('#datetimepicker8').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $('#datetimepicker9').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $('#datetimepicker10').datetimepicker({
        format: 'DD-MM-YYYY hh:ii:ss A' // 26-01-2015 10:20:00 AM
    });
    $("#datetimepicker9").on("dp.change",function (e) {
       $('#datetimepicker10').data("DateTimePicker").setMinDate(e.date);
    });
    $("#datetimepicker10").on("dp.change",function (e) {
       $('#datetimepicker9').data("DateTimePicker").setMaxDate(e.date);
    });

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        //format: 'dd.M.yyyy HH:ii P',
        format: 'dd.mm.yyyy HH:ii:ss P',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    $('.formto_datetime').datetimepicker({
        //language:  'fr',
        format: 'dd.mm.yyyy HH:ii:ss P',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 0,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    function date_diff()
    {
        var date1 = document.getElementById('txtfrom_date').value;
        var date2 = document.getElementById('txtto_date').value;
        //alert(date1+"HI"+date2);

        var datefrom = date1.split(' ');
        var dateto = date2.split(' ');
        //alert(datefrom[0]+"!!!!!!"+dateto[0]); //alert(parseDate(datefrom[0]));

        Date.prototype.days=function(to){
            return  Math.abs(Math.floor( to.getTime() / (3600*24*1000)) -  Math.floor( this.getTime() / (3600*24*1000)))
        }
        var ga = new Date(parseDate(datefrom[0])).days(new Date(parseDate(dateto[0]))) // 3 days
        var cntdate = +ga + 1;
        //var cntdate = ga;
        document.getElementById('txtnoofdays').value = cntdate;
        document.getElementById('txtnoofhours').value = cntdate * 24;
    }

    function parseDate(str) {
        var mdy = str.split('-')
        //alert(mdy[2]+"~~"+mdy[0]+"~~"+mdy[1]);
        return mdy[1]+"-"+mdy[0]+"-"+mdy[2];
        //return new Date(mdy[1], mdy[0], "20"+mdy[2]);
    }

    function find_balance(reqvalue)
    {
        $('#load_page').show();
        var bal = document.getElementById("hid_balance").value;
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var deptid = document.getElementById('slt_department_asset').value;
        var branch = document.getElementById('slt_brnch_0').value;

        if($('#getmonthwise_budget').css('display') == 'none') {
            $('#hidrequest_value').val($('#txtrequest_value').val());
        }

        if(deptid == 100 && approval_listings_id == 4 && reqvalue > bal)
        {
            document.getElementById("txtrequest_value").value = '';
            document.getElementById('txt_brnvalue_0').value = '';
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Request value is greater than the Target Balance";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            document.getElementById("sbmt_request").disabled = true;
            //document.getElementById("sbmt_update").disabled = true;
        } else {
            document.getElementById("sbmt_request").disabled = false;
            //document.getElementById("sbmt_update").disabled = false;
        }

        /* var accnoid = document.getElementById('hid_accnoid').value;
        if(accnoid == 1)
        {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "This user dont have Salary account. So this user is not eligible for salary advance process.";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            document.getElementById("sbmt_request").disabled = true;
            //document.getElementById("sbmt_update").disabled = true;
        } else {
            document.getElementById("sbmt_request").disabled = false;
            //document.getElementById("sbmt_update").disabled = false;
        } */
        $('#load_page').hide();
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

    function branch_visit(){
        var slt_approval_listings=document.getElementById('slt_approval_listings').value;
        var userid=document.getElementById('txt_submission_reqby').value;
        if(slt_approval_listings == 2) {
            // getapproval_salaryadvance(userid);
        }
    }

    function change_readonly() {
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(slt_subtopcore == '41') {
            $(".ad_category").attr("readonly", false);
        } else {
            $(".ad_category").attr("readonly", true);
            $(".ad_category").val("");
        }
    }

    function getapproval_listings_only() {
        $('#load_page').show();
        var approval_listings_id = $('#slt_approval_listings').val();
        var slt_brnch = $('.slt_brnchcls');
        if(slt_brnch.length > 1) {
            slt_brnch = 100;
        } else{
            slt_brnch = $('#slt_brnch_0').val();
        }
        var slt_subtype = '';
        var kind_attn = '';
        var type_app = document.getElementById('slt_submission').value;
        var slt_core_department = document.getElementById('slt_core_department').value;
        var budgettype = '';
        var budgetidtype = $('#slt_submission').val();
        var slt_project = $('#slt_project').val();
        var slt_project_type = $('#slt_project_type').val();
        var slt_topcore = document.getElementById('slt_topcore').value;
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(budgetidtype == 1) {
            budgettype = 'FIXED';
        } else if(budgetidtype == 7) {
            budgettype = 'EXTRA';
        }
        var deptid = $("#slt_department_asset").val();
        var tarnum = $("#slt_targetno").val();
        var txtrequest_value = $("#txtrequest_value").val();

        var strURL="getapproval_listings.php?approval_listings_id="+approval_listings_id+"&kind_attn="+kind_attn+"&type="+type_app+"&budgettype="+budgettype+"&budgetidtype="+budgetidtype+"&topcore="+slt_topcore+"&sub_topcore="+slt_subtopcore+"&slt_core_department="+slt_core_department+"&slt_project_type="+slt_project_type+"&slt_project="+slt_project+"&deptid="+deptid+"&tarnum="+tarnum+"&slt_brnch="+slt_brnch+"&txtrequest_value="+txtrequest_value;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_approval_listings').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    // get_targetdates();
                    $('#load_page').hide();
                }
            }
            req.open("POST", strURL, true);
            req.send(null);
        }
    }

    function getapproval_listings() {
        $('#load_page').show();
        call_dynamic_option();
        // change_readonly();
        var approval_listings_id = $('#slt_approval_listings').val();
        var slt_brnch = $('.slt_brnchcls');
        // alert("**"+slt_brnch.length);
        if(slt_brnch.length > 1) {
            slt_brnch = 100;
        } else{
            slt_brnch = $('#slt_brnch_0').val();
        }
        // var slt_subtype=document.getElementById('slt_subtype').value;
        var slt_subtype = '';
        // var kind_attn = document.getElementById('txt_kind_attn').value;
        var kind_attn = '';
        var type_app = document.getElementById('slt_submission').value;
        var slt_core_department = document.getElementById('slt_core_department').value;
        // var budgettype = document.getElementById('slt_budgettype').value;
        var budgettype = '';
        var budgetidtype = $('#slt_submission').val();
        var slt_fixbudget_planner = $('#slt_fixbudget_planner').val();
        var slt_project = $('#slt_project').val();
        var slt_project_type = $('#slt_project_type').val();
        var slt_topcore = document.getElementById('slt_topcore').value;
        var slt_subtopcore = document.getElementById('slt_subcore').value;
        if(budgetidtype == 1) {
            budgettype = 'FIXED';
        } else if(budgetidtype == 7) {
            budgettype = 'EXTRA';
        }
        var deptid = $("#slt_department_asset").val();
        var tarnum = $("#slt_targetno").val();
        var txtrequest_value = $("#txtrequest_value").val();

        get_targetdates_readonly();
        /* if(approval_listings_id == 807 || approval_listings_id == 777) {
            enable_month();
        } else {
            enable_all(); brncode
        } */

        var alow_prd = 1;
        if(slt_submission == 1 && slt_fixbudget_planner == 'MONTHWISE') {
            alow_prd = 0;
        }
        // console.log('###'+alow_prd+'###');

        $("#id_supplier").html('');
        $("#getmonthwise_budget").css("display", "none");
        // alert("!!");
        if(alow_prd == 0) {
            // alert("@@");
            $("#getmonthwise_budget").css("display", "block");
        }
        if(slt_approval_listings != '' && alow_prd == 1) {
            // alert("##"); sub_topcore
            var strURL="getapproval_listings.php?approval_listings_id="+approval_listings_id+"&kind_attn="+kind_attn+"&type="+type_app+"&budgettype="+budgettype+"&budgetidtype="+budgetidtype+"&topcore="+slt_topcore+"&sub_topcore="+slt_subtopcore+"&slt_core_department="+slt_core_department+"&slt_project_type="+slt_project_type+"&slt_project="+slt_project+"&deptid="+deptid+"&tarnum="+tarnum+"&slt_brnch="+slt_brnch+"&txtrequest_value="+txtrequest_value;
            var req = getXMLHTTP();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            document.getElementById('id_approval_listings').innerHTML=req.responseText;
                            // if(approval_listings_id == 4) { getapproval_salaryadvance(approval_listings_id); } // Salary Advance
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                        // get_targetdates();
                        $('#load_page').hide();
                    }
                }
                req.open("POST", strURL, true);
                req.send(null);
            }
        }
    }

    function getapproval_salaryadvance(userid) {
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        // var project = document.getElementById('slt_project').value;
        var project = '';
        var brncode = document.getElementById('slt_brnch_0').value;
        var strURL="get_salaryadvance.php?userid="+userid+"&approval_listings_id="+approval_listings_id+"&brncode="+brncode+"&project="+project;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_salaryadvance').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    get_eligibleadvance(userid);
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function get_eligibleadvance(userid) {
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var strURL="get_eligibleadvance.php?userid="+userid+"&approval_listings_id="+approval_listings_id;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.responseText == 1) {
                        var ALERT_TITLE = "Message";
                        var ALERTMSG = "Due to your previous request, You are not eligible to raise another request";
                        createCustomAlert(ALERTMSG, ALERT_TITLE);
                        $("#button_set").hide();
                    } else {
                        $("#button_set").show();
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }

    function get_dept(core_deptid) {
        $('#load_page').show();
        var strURL1="ajax/ajax_get_dept.php?core_deptid="+core_deptid;
        var req1 = getXMLHTTP();
        if (req1) {
            req1.onreadystatechange = function() {
                if (req1.readyState == 4) {
                    if (req1.status == 200) {
                        // $("#slt_department_asset").select2();
                        $.getScript("js/jquery-customselect.js");
                        $(document).ready(function() {
                            $(".chosn").customselect();
                        });
                        document.getElementById('id_department').innerHTML=req1.responseText;
                        get_advancedetails();
                        get_targetdates();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                    }
                }
            }
            req1.open("GET", strURL1, true);
            req1.send(null);
        }
        find_tags();
        $('#load_page').hide();
    }

    function get_advancedetails() {
        $('#load_page').show();
        // Find the RPTMODE from department_asset table
        var deptid = $("#slt_department_asset").val();
        var approval_listings_id = document.getElementById('slt_approval_listings').value;
        var slt_branch = document.getElementById('slt_brnch_0').value;
        if(slt_branch == 888) { slt_branch = 100;  }

        var slt_core_department = $("#slt_core_department").val();
        var depcode = $("#slt_department_asset").val();
        var strURL="ajax/ajax_validate.php?action=find_rptmode&validate_code="+slt_core_department+"&depcode="+depcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                $("#txt_rptmode").val(data1);
                if(data1 == 0) {
                    var ALERT_TITLE = "Message";
                    var ALERTMSG = "No Supplier Available. Kindly Contact Admin Master Team!!";
                    createCustomAlert(ALERTMSG, ALERT_TITLE);
                    $("#txt_rptmode").val(data1);
                    $('#load_page').hide();
                }
            }
        });
        // Find the RPTMODE from department_asset table

        /*
        if(deptid == 100) {
            var strURL="get_advancedetails.php?slt_branch="+slt_branch+"&deptid="+deptid+"&approval_listings_id="+approval_listings_id;
            var req = getXMLHTTP();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            document.getElementById('id_advancedetails').innerHTML=req.responseText;
                            get_targetdates();
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }
                }
                req.open("GET", strURL, true);
                req.send(null);
            }
        } */

        /* // 12-05-2018 GA Commented this
        var strURL1="ajax/ajax_target_no.php?slt_branch="+slt_branch+"&deptid="+deptid+"&approval_listings_id="+approval_listings_id;
        var req1 = getXMLHTTP();
        if (req1) {
            req1.onreadystatechange = function() {
                if (req1.readyState == 4) {
                    if (req1.status == 200) {
                        // $("#slt_targetno").select2();
                        $.getScript("js/jquery-customselect.js");
                        $(document).ready(function() {
                            $(".chosn").customselect();
                        });
                        document.getElementById('id_tarno').innerHTML=req1.responseText;
                        $('#slt_targetno').focus();
                        get_targetdates();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                    }
                }
            }
            req1.open("GET", strURL1, true);
            req1.send(null);
        } */

        // Find the Approval Type
        var strURL="get_advancedetails.php?action=find_apptype&slt_core_department="+slt_core_department+"&depcode="+depcode;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data1) {
                $("#id_apptype").html(data1);
            }
        });
        // Find the Approval Type
        find_tags();
        $('#load_page').hide();
    }

    function call_days() {
        /* get_targetdates();
        var cntdays = document.getElementById('hid_noofdays').value;
        var tt = document.getElementById('txtfrom_date1').value;
        var date = new Date(tt);
        var newdate = new Date(date);

        if(cntdays > 1)
        {
            newdate.setDate(newdate.getDate() + parseInt(cntdays));
        } else {
            newdate.setDate(newdate.getDate());
        }

        var dd = newdate.getDate();
        var mm = newdate.getMonth() + 1;
        var y = newdate.getFullYear();

        if(dd < 10)
            dd = '0' + dd;
        if(mm < 10)
            mm = '0' + mm;

        var someFormattedDate = dd + '-' + mm + '-' + y;

        document.getElementById('txtto_date').value = someFormattedDate + ' 12:00:00 AM';
        document.getElementById('txtnoofhours').value = cntdays * 24;
        document.getElementById('txtnoofdays').value = cntdays;
        date_diff(); */
    }

    function gettopcore(project_id) {
        $('#load_page').show();
        /* var slt_core_department = $("#slt_core_department").val();
        var slt_submission = $("#slt_submission").val();
        var strURL="gettopcore.php?project_id="+project_id+"&slt_core_department="+slt_core_department+"&slt_submission="+slt_submission;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_topcore').innerHTML=req.responseText;
                        getsubcore();
                        // get_targetdates();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        } */

        var slt_project = $('#slt_project').val();
        var strURL = "ajax/ajax_project.php?action=find_subtype&slt_project="+slt_project;
        $.ajax({
            type: "POST",
            url: strURL,
            success: function(data) {
                $("#id_submission_type").html(data);
                $('#load_page').hide();
            }
        });
        find_tags();
    }

    function get_topcore(slt_subcore) {
        $('#load_page').show();
        var slt_subcores = $('#slt_subcore').val();
        var slt_core_department = $("#slt_core_department").val();
        // var slt_subcore = $("#slt_subcore").val(); get_targetdates()
        var strURL="gettopcore.php?action=find_topcore_withname&slt_core_department="+slt_core_department+"&slt_subcore="+slt_subcores;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {

                        var ss1 = req.responseText;
                        var ss = ss1.split("!!");
                        document.getElementById('slt_topcore').value = ss[0];
                        document.getElementById('slt_topcore_name').value = ss[1];
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        getsubtype_value();
        $('#load_page').hide();
    }

    function getbudget() {
        var subtype_id = $('#slt_submission').val();
        if(subtype_id == 1 || subtype_id == 6 || subtype_id == 7) {
            $(".ttlsumrequired").attr('required', true);
            $("#getbudget").css("display", "block");
            // if(subtype_id != 7) {
                $("#getmonthwise_budget").css("display", "block");
            // }
        } else {
            $(".ttlsumrequired").attr('required', false);
            $("#getbudget").css("display", "none");
            // if(subtype_id != 7) {
                $("#getmonthwise_budget").css("display", "none");
            // }
        }
    }


    function getsubcore() {
        /* $('#load_page').show();
        var topcore_id = $("#slt_topcore").val();
        var targetno = $("#slt_targetno").val();
        var strURL="getsubcore.php?topcore_id="+topcore_id+"&targetno="+targetno;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        // document.getElementById('id_subcore').innerHTML=req.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                    getapproval_listings();
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        $('#load_page').hide(); */
    }

    function get_sub_core() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        var slt_targetno=document.getElementById('slt_targetno').value;
        if(slt_submission == 2 || slt_submission == 3 || slt_submission == 4 || slt_submission == 8) {
            var strURL="getsubcore.php?action=find_subcore&tarnumb="+slt_targetno;
        } else {
            var strURL="getsubcore.php?action=find_org_subcore&tarnumb="+slt_targetno;
        }
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        document.getElementById('id_subcore').innerHTML=req.responseText;
                        get_topcore($('#slt_subcore').val());
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
        $('#load_page').hide();
    }

    function getsubtype(subtype_id) {
        $('#load_page').show();
        if(subtype_id == 1 || subtype_id == 6 || subtype_id == 7)
        {
            document.getElementById('id_branch').style.display = "block";
            document.getElementById('id_reqvalue').style.display = "block";
            document.getElementById('id_supplier').style.display = "block";
            // document.getElementById('getbudget').style.display = "block";
            if(subtype_id != 7) {
                document.getElementById('getmonthwise_budget').style.display = "block";
            }
            document.getElementById('id_reqvalue_hidden').style.display = "none";
        } else {
            document.getElementById('id_branch').style.display = "none";
            document.getElementById('id_reqvalue').style.display = "none";
            //document.getElementById('id_supplier').style.display = "none";
            // document.getElementById('getbudget').style.display = "none";
            if(subtype_id != 7) {
                document.getElementById('getmonthwise_budget').style.display = "none";
            }
            document.getElementById('id_reqvalue_hidden').style.display = "block";
        }

        var slt_submission=document.getElementById('slt_submission').value;
        /* if(slt_submission == 4) {
            $('#dynamic_subject').css('display', 'block');
        } else {
            $('#dynamic_subject').css('display', 'none');
        } */
        getsubtype_value();
        showmode();
        get_targetdates();
        find_tags();
        $('#load_page').hide();
    }

    function getsubtype_value() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        var slt_targetno=document.getElementById('slt_targetno').value;
        var project = '';
        var subtype_value_id = '';
        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
            slt_submission = 1;
        }
        var slt_subcore = $('#slt_subcore').val()

        /* if(slt_submission == 4) {
            $('#dynamic_subject').css('display', 'block');
        } else {
            $('#dynamic_subject').css('display', 'none');
        } */

        /* if(slt_submission == 2 || slt_submission == 3 || slt_submission == 4 || slt_submission == 8) {
            $('#id_subcore_list').css('display', 'none'); slt_subcore
            $('#id_subcore').css('display', 'block');
        } else {
            $('#id_subcore_list').css('display', 'block');
            $('#id_subcore').css('display', 'none');
        } */

        var strURL1="app_type_mode.php?subtype_value_id="+subtype_value_id+"&slt_submission="+slt_submission+"&slt_targetno="+slt_targetno+"&slt_subcore="+slt_subcore;
        var req1 = getXMLHTTP();
        if (req1) {
            req1.onreadystatechange = function() {
                if (req1.readyState == 4) {
                    if (req1.status == 200) {
                        // alert("hi");
                        // $(document).ready(function() {
                            $.getScript("js/jquery-customselect.js");
                            $(".chosn").customselect();
                            // document.getElementById('id_app_type_mode').innerHTML=req1.responseText;
                        // });
                        document.getElementById('id_appr_listings').innerHTML=req1.responseText;
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req1.statusText);
                    }
                }
                $('#load_page').hide();
            }
            req1.open("GET", strURL1, true);
            req1.send(null);
        }

        getsubtype_value1();
        /* if(subtype_value_id == 3) {
            document.getElementById('due_dt').style.display = "block";
        } else {
            document.getElementById('due_dt').style.display = "none";
        } */
    }

    function getsubtype_value1() {
        $('#load_page').show();
        var slt_submission=document.getElementById('slt_submission').value;
        // var project = document.getElementById('slt_project').value;
        var project = '';
        var subtype_value_id = '';
        if(slt_submission == 1 || slt_submission == 6 || slt_submission == 7) { //
            slt_submission = 1;
        }

        /* var strURL="getsubtype_value.php?subtype_value_id="+subtype_value_id+"&project="+project;
        var req = getXMLHTTP();
        if (req) {
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        //alert("hi");
                        var resp = req.responseText;
                        // document.getElementById('id_subtype_value').innerHTML = resp;

                        $(document).ready(function ($) {
                            $.getScript("js/jquery-customselect.js");
                            $(".chosn").customselect();
                            $('#txt_submission_reqby').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
                                        },
                                        success: function( data ) {
                                            if(data == 'No User Available in this Top core and Sub Core') {
                                                $('#txt_submission_reqby').val('');
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

                            $('#txt_workintiator').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
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

                            $('#txt_alternate_user').autocomplete({
                                source: function( request, response ) {
                                    $.ajax({
                                        url : 'ajax/ajax_employee_details.php',
                                        dataType: "json",
                                        data: {
                                           name_startsWith: request.term,
                                           topcr: $('#slt_topcore').val(),
                                           subcr: $('#slt_subcore').val(),
                                           type: 'core_employee'
                                        },
                                        success: function( data ) {
                                            if(data == 'No User Available in this Top core and Sub Core') {
                                                $('#txt_alternate_user').val('');
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
                        $('#load_page').hide();
                    } else {
                        alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        $('#load_page').hide();
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }

       if(subtype_value_id == 3) {
            document.getElementById('due_dt').style.display = "block";
        } else {
            document.getElementById('due_dt').style.display = "none";
        } */
    }

    $('#txtdue_date').Zebra_DatePicker({
      direction: true,
      format: 'd-M-y'
    });

    function makeFileList() {
        var input = document.getElementById("filesToUpload");
        //alert("=="+input.files.length+"**");
        document.getElementById("txt_files").value = input.files.length;
        if(input.files.length > 4) {
            alert('You cannot upload more than 4 files');
        }
    }
    var loadedobjects="";

    function get_prddet()
    {
        var depcode = $('#slt_department_asset').val();
        var slt_targetno = $('#slt_targetno').val();
        $.ajax({
            url:"ajax/ajax_product_details.php?action=sub_prd&depcode="+depcode+"&slt_targetno="+slt_targetno,
            success:function(data)
            {
                $("#myModal1").modal('show');
                $('#modal-body1').html(data);
            }
        });
    }

    function loadobjs(){
        if (!document.getElementById)
            return
        for (i=0; i<arguments.length; i++){
            var file=arguments[i]
            var fileref=""
            if (loadedobjects.indexOf(file)==-1){ //Check to see if this object has not already been added to page before proceeding
                if (file.indexOf(".js")!=-1){ //If object is a js file
                    fileref=document.createElement('script')
                    fileref.setAttribute("type","text/javascript");
                    fileref.setAttribute("src", file);
                }
                else if (file.indexOf(".css")!=-1){ //If object is a css file
                    fileref=document.createElement("link")
                    fileref.setAttribute("rel", "stylesheet");
                    fileref.setAttribute("type", "text/css");
                    fileref.setAttribute("href", file);
                }
            }
            if (fileref!=""){
                document.getElementsByTagName("head").item(0).appendChild(fileref)
                loadedobjects+=file+" " //Remember this object as being already added to page
            }
        }
    }
    </script>

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    // popover demo
    $("[data-toggle=popover]")
        .popover()
    </script>

    <!-- Light Box -->
    <link href="css/ekko-lightbox.css" rel="stylesheet">
    <!-- yea, yea, not a cdn, i know -->
    <script src="js/ekko-lightbox-min.js"></script>
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('#txt_submission_reqby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txt_submission_reqby').val('');
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

            $('#txt_against_approval').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcore: $('#slt_topcore').val(),
                           type: 'approval_no'
                        },
                        success: function( data ) {
                            if(data == 'Approval No is not available') {
                                $('#txt_against_approval').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "Approval No is not available!!";
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

            $('#txt_workintiator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
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

            $('#txt_alternate_user').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           topcr: $('#slt_topcore').val(),
                           subcr: $('#slt_subcore').val(),
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txt_alternate_user').val('');
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


            // Policy approval
            $('#txtdynamic_creator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_creator').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
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

            $('#txtdynamic_coordinator').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_coordinator').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
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

            $('#txtdynamic_assistby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_assistby').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
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

            $('#txtdynamic_userlist').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_userlist').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
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

            $('#txtdynamic_approvedby').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/ajax_employee_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           type: 'employee'
                        },
                        success: function( data ) {
                            if(data == 'No User Available in this Top core and Sub Core') {
                                $('#txtdynamic_approvedby').val('');
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No User Available. Kindly Change this!!";
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
            // Policy approval



            $('#txt_suppliercode').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : 'ajax/get_supplier_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           action: 'supplier_details'
                        },
                        success: function( data ) {

                            response( $.map( data, function( item ) {
                                return {
                                    label: item,
                                    value: item
                                }
                            }));
                        }
                    });
                },
                autoFocus: true,
                minLength: 0
            });

            var prdid = '';
            $(".find_prdcode").autocomplete({
                source: function( request, response ) {
                    $('.find_prdcode').on('focus', function() {
                        prdid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           depcode: $('#slt_department_asset').val(),
                           action: 'product'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+prdid+"**"); */
                            if(data == 'NO PRODUCT') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Product Available. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+prdid).val('');
                                $('#'+prdid).focus();
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

            var spdid = ''; var pr = ""; var prd = "";
            $('.find_subprdcode').autocomplete({
                source: function( request, response ) {
                    $('.find_subprdcode').on('focus', function() {
                        spdid = $(this).attr("id");
                        // alert("**"+spdid+"**");
                        pr = spdid.split("_");
                        prd = pr[2];
                        // alert("**"+pr+"**"+pr[2]);
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           // product: $('#txt_prdcode_'+prd).val(),
                           depcode: $('#slt_department_asset').val(),
                           action: 'sub_product'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+spdid+"**"); */
                            if(data == 'NO SUB PRODUCT') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Sub Product Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+spdid).val('');
                                $('#'+spdid).focus();
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

            var spcid = '';
            $('.find_prdspec').autocomplete({
                source: function( request, response ) {
                    $('.find_prdspec').on('focus', function() {
                        spcid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           action: 'product_specification'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+spcid+"**"); */
                            if(data == 'NO SPECIFICATION') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Specification Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);

                                // $('.find_prdspec').on('keypress', function() {
                                    // var spcid = $(this).attr("id");
                                    // alert("**"+data+"**"+spcid+"**");
                                    $('#'+spcid).val('');
                                    $('#'+spcid).focus();
                                // });
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

            var supid = '';
            $('.find_supcode').autocomplete({
                source: function( request, response ) {
                    $('.find_supcode').on('focus', function() {
                        supid = $(this).attr("id");
                    }),
                    $.ajax({
                        url : 'ajax/ajax_product_details.php',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,
                           slt_core_department: $('#slt_core_department').val(),
                           action: 'supplier_withcity'
                        },
                        success: function( data ) {
                            /* alert("**"+data+"**"+supid+"**"); */
                            if(data == 'NO SUPPLIER') {
                                var ALERT_TITLE = "Message";
                                var ALERTMSG = "No Supplier Available for this Product. Kindly Contact Admin Master Team!!";
                                createCustomAlert(ALERTMSG, ALERT_TITLE);
                                $('#'+supid).val('');
                                $('#'+supid).focus();
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

            // delegate calls to data-toggle="lightbox"
            $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    onShown: function() {
                        if (window.console) {
                            return console.log('Checking our the events huh?');
                        }
                    },
                    onNavigate: function(direction, itemIndex) {
                        if (window.console) {
                            return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                        }
                    }
                });
            });

            //Programatically call
            $('#open-image').click(function (e) {
                e.preventDefault();
                $(this).ekkoLightbox();
            });
            $('#open-youtube').click(function (e) {
                e.preventDefault();
                $(this).ekkoLightbox();
            });

            $(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    onShown: function() {
                        var a = this.modal_content.find('.modal-footer a');
                        if(a.length > 0) {
                            a.click(function(e) {
                                e.preventDefault();
                                this.navigateTo(2);
                            }.bind(this));
                        }
                    }
                });
            });

        });

    /******************** Change Default Alert Box ***********************/
    var ALERT_BUTTON_TEXT = "OK";
    /* if(document.getElementById) {
        window.alert = function(txt) {
            var ALERT_TITLE = "GA Title";

            var tga = document.getElementById("id_ga").value;
            createCustomAlert(tga, ALERT_TITLE);
        }
    } */

    function createCustomAlert(txt, title) {
        d = document;

        if(d.getElementById("modalContainer")) return;

        mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
        mObj.id = "modalContainer";
        mObj.style.height = d.documentElement.scrollHeight + "px";

        alertObj = mObj.appendChild(d.createElement("div"));
        alertObj.id = "alertBox";
        if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
        alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
        alertObj.style.visiblity="visible";

        h1 = alertObj.appendChild(d.createElement("h1"));
        h1.appendChild(d.createTextNode(title));

        msg = alertObj.appendChild(d.createElement("p"));
        //msg.appendChild(d.createTextNode(txt));
        msg.innerHTML = txt;

        btn = alertObj.appendChild(d.createElement("a"));
        btn.id = "closeBtn";
        btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
        btn.href = "#";
        btn.focus();
        btn.onclick = function() { removeCustomAlert();return false; }

        alertObj.style.display = "block";
    }

    function removeCustomAlert() {
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }

    function ful(){
        //alert('Alert this pages');
    }
    /******************** Change Default Alert Box ***********************/
    </script>
    <!-- Light Box -->

    <!-- Light Box - New -->
    <link href="css/lightgallery.css" rel="stylesheet">
    <script type="text/javascript">
    $(document).ready(function(){
        $('.lightgallery').lightGallery();
    });

    function enable_all() {
        $("#id_budplanner").removeClass("disabledbutton");
        $('#id_budplanner').find('input, textarea').attr('readonly', false);
        $('#id_budplanner').find('button, select, radio').attr('disabled', false);
        $("#id_budplanner").attr("readonly", false);

        $("#id_supplier").removeClass("disabledbutton");
        $('#id_supplier').find('input, textarea').attr('readonly', false);
        $('#id_supplier').find('button, select, radio').attr('disabled', false);
        $("#id_supplier").attr("readonly", false);
    }

    function enable_month() {
        /* // $('#id_supplier *').prop('disabled',true);
        $("#id_supplier").addClass("disabledbutton");
        // $('#id_supplier').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
        $('#id_supplier').find('input, textarea').attr('readonly', 'readonly');
        $('#id_supplier').find('button, select, radio').attr('disabled', 'disabled');
        $("#id_supplier").attr("readonly", "1");
        $('#id_supplier').find('input, textarea').val('');

        $("#id_budplanner").removeClass("disabledbutton");
        $('#id_budplanner').find('input, textarea').attr('readonly', false);
        $('#id_budplanner').find('button, select, radio').attr('disabled', false);
        $("#id_budplanner").attr("readonly", false); */
    }

    function enable_product() {
        /* // $('#id_budplanner *').prop('disabled',true);
        $("#id_budplanner").addClass("disabledbutton");
        // $('#id_budplanner').find('input, textarea, button, select, radio').attr('readonly', 'readonly');
        $('#id_budplanner').find('input, textarea').attr('readonly', 'readonly');
        $('#id_budplanner').find('button, select, radio').attr('disabled', 'disabled');
        $("#id_budplanner").attr("readonly", "1");
        $('#id_budplanner').find('input, textarea').val('');

        $("#id_supplier").removeClass("disabledbutton");
        $('#id_supplier').find('input, textarea').attr('readonly', false);
        $('#id_supplier').find('button, select, radio').attr('disabled', false);
        $("#id_supplier").attr("readonly", false); */
    }

	function nfiles()
	{	var input=document.getElementById("attachments").value;
		alert(input.files[0].name);
		//alert(files.length);
		//var attchments=document.getElementById("attacments_detail").value;
		//attachments.appendChild("<p>".files.</p>);
		var para = document.createElement("a");                       // Create a <p> node
		var t = document.createTextNode(files);      // Create a text node
		para.appendChild(t);                                          // Append the text to <p>
		document.getElementById("attachments_detail").innerHTML="";
		document.getElementById("attachments_detail").appendChild(para);


	}
    </script>
    <script src="js/picturefill.min.js"></script>
    <script src="js/lightgallery.js"></script>
    <script src="js/lg-fullscreen.js"></script>
    <script src="js/lg-thumbnail.js"></script>
    <script src="js/lg-video.js"></script>
    <script src="js/lg-autoplay.js"></script>
    <script src="js/lg-zoom.js"></script>
    <script src="js/lg-hash.js"></script>
    <script src="js/lg-pager.js"></script>
    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>
