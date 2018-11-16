<?
session_start();
error_reporting(0);
header('X-UA-Compatible: IE=edge');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

$menu_name = 'ADMIN DASHBOARD';
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

if($_SERVER['REQUEST_METHOD']=="POST" and $_REQUEST['sbmt_request'] != '')
{
   /* switch($txt_process_type) {
        case 1 : // ORIGINAL APPROVAL NEED
            // Update into approval_request Table for Original Print Need
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for Original Print Need
            break;

        case 2 : // APPROVAL AUTO FORWARD
            /* Update into approval_request Table for APPROVAL AUTO FORWARD
            $tbl_approval_request = "approval_request";
            $field_approval_request = array();
            $field_approval_request['APPRMRK']  = "";
            $where_approval_request = " arqsrno = 1 and aprnumb like '".$txt_aprnumb."' ";
            // print_r($field_approval_request);
            // $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
            // Update into approval_request Table for APPROVAL AUTO FORWARD */
          /*  break;

        case 3 : // PROJECT ID CHANGE, AFTER APPROVAL
            $sql_apst = select_query_json("select * from approval_request where appstat = 'A' and aprnumb like '".$txt_aprnumb."'", "Centra", 'TCS');
            if(count($sql_apst) > 0) {
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
                $tbl_approval_request = "approval_request";
                $field_approval_request = array();
                $field_approval_request['APRCODE']  = $slt_project;
                $where_approval_request = " appstat = 'A' and aprnumb like '".$txt_aprnumb."' ";
                // print_r($field_approval_request);
                $update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
                // Update into approval_request Table for PROJECT ID CHANGE, AFTER APPROVAL
            } else { ?>
                <script>window.location='admin_process.php?action=add&msg=This approval is not yet approved.';</script>
                <?php exit();
            }
            break;
        default:
            break;
    }

    // exit;
    if($update_approval_request == 1) { ?>
        <script>window.location='admin_process.php?status=success';</script>
        <?php exit();
    } else { ?>
        <script>window.location='admin_process.php?action=add&status=failure';</script>
        <?php exit();
    }
    */


}

if($inner_menuaccess[0]['VEWVALU'] == 'Y') { // Menu Permission is allowed ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <!-- META SECTION -->
    <title>Admin Project Creation :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <!-- Select2 -->
    <link rel="stylesheet" href="../dist/newlte/bower_components/select2/dist/css/select2.min.css">
    <link href="css/jquery-customselect.css" rel="stylesheet" />
        <script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
        <link href="css/monthpicker.css" rel="stylesheet" type="text/css">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
        <!-- multiple file upload -->
        <link href="css/jquery.filer.css" rel="stylesheet">

    <style type="text/css">
		.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.4;
		background: url('images/l2.gif') 50% 50% no-repeat rgb(249,249,249);
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
    </style>
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <?  $theme_view = "css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    </head>
    <body>
	 
        <? /* <div id="load_page" style='display:block;padding:12% 40%;'></div> */ ?>
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
                    <li class="active">Admin Project Creation</li>
                </ul>
                <!-- END BREADCRUMB -->

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Admin Project Creation</h3>
                        </div>
                        <div class="panel-body">
                          <? $sql_reqid = select_query_json("select * from APPROVAL_TOPCORE where ATCCODE = '".$_REQUEST['reqid']."' order by ATCCODE", "Centra", "TCS"); ?>
                          <!-- action='prakash/insert_project.php' -->
                          <form role="form" id='frm_project_creation' name='frm_project_creation' method='post' enctype="multipart/form-data" >
                               <div class="col-lg-12 col-md-12 tooltip-demo" style='border-right: 1px solid #d4d4d4;'>

                                    <? if($_REQUEST['status'] == 'failure') { ?>
                                    <div class="form-group trbg">
                                        <div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            Failed in updation. Kindly try again!
                                        </div>
                                    </div>
                                    <? } elseif($_REQUEST['status'] == 'success') { ?>
                                    <div class="form-group trbg">
                                        <div class="alert alert-success alert-dismissable" style='font-weight:bold;'>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            Successfully updated. Kindly Verify!
                                        </div>
                                    </div>
                                    <? } elseif($_REQUEST['msg'] != '') { ?>
                                    <div class="form-group trbg">
                                        <div class="alert alert-danger alert-dismissable" style='font-weight:bold;'>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <?=$msg?>
                                        </div>
                                    </div>
                                    <? } ?>
                                    <div class='clear clear_both'></div>

                                </div>

								
                                    <!-- Top Core -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Branch<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8 ">
                                                    <select class="form-control" autofocus required name='txt_branch_type' id='txt_branch_type' data-toggle="tooltip" data-placement="top" title="branch type">

                                                    </select>
                                                </div>
                                             </div>
											 <!--
                                             <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Top Core<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                     <select class="form-control" autofocus required name='txt_top_core' id='txt_top_core' data-toggle="tooltip" data-placement="top" title="choose the top core" >
                                                     </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Core<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                    <select class="form-control" autofocus required name='txt_core' id='txt_core' data-toggle="tooltip" data-placement="top" title="choose the core" >

                                                    </select>
                                                </div>
                                           </div>-->

                                            <div class="row form-group">

                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Ledger Code-Name<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                    <!-- <div id="add_ledger" class="form-group"> -->
                                                    <!-- <input type="hidden" name="partint3" id="partint" value="1"> -->
                                                        <!-- <div class="form-group input-group"> -->


                                                          <!-- <input type="text" name="txt_ledger_name[]" id="txt_ledger_name"placeholder="SELECT THE LEDGER NAME" title="select the ledger name" class="form-control find_ledger" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;"> -->
                                                          <!--
                                                          <span class="input-group-btn">
                                                            <button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add">+</button> -->
                                                          <!-- </span> -->
                                                          <div id="add_ledger" class="form-group">
                                                                <input type="hidden" name="add_ledger" id="add_ledger_row" value="1">
                                                                <div class="form-group input-group">
																<div style="width:100%">
																	<div style="width:70%;float:left;">
																		<select class="form-control" autofocus required name='txt_ledger_name[]' id='txt_ledger_name1' data-toggle="tooltip" data-placement="top" title="ledger Name" >
                                                                    </select>
																	</div>
																	<div style="width:30%;float:right">
																		<input type="text" name = 'txt_value[]' class="form-control" id='txt_value' placeholder= "VALUE"  data-toggle ="tooltip" title ="values" required>
																	</div>
																</div>

                                                                <span class="input-group-btn"><button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                                  </span>

                                                                </div>
                                                            </div>


                                                </div>
                                            </div>
                                            <div class="row form-group">

                                        <div class="col-lg-4 col-md-4">
                                            <label style='height:27px;'>Mode<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-8 col-md-8">
                                            <select class="form-control" autofocus required name='txt_mode_type' id='txt_mode_type' data-toggle="tooltip" data-placement="top" title="select mode type" >
                                                 <option value="">CHOOSE THE MODE</option>
                                                 <option value="B" >BRANCH</option>
                                                 <option value="P" >NEW PROJECT</option>
                                            </select>
                                        </div>
										</div>
										<div class="row form-group">
												<div class="col-lg-4 col-md-4">
													<label style='height:27px;'>File Attachments</label>
													 <!--<span style='color:red'>*</span>-->
												</div>
												<div class="col-lg-8 col-md-8">
													<div>
														<p><input type="file" name="files[]" id="file_upload" multiple style="padding-bottom:5px"></p>
													</div>
													<div>
														<p><input type ="button" value = "Add Files"  onclick="add_files()" style="align:left"/></p>
													</div>
												</div>
												</div>
										</div>
										
                                        <div class="col-sm-6">
                                            <div class="row form-group " >
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Project Name<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">

                                                    <input type='text' name='txt_project_name' id='txt_project_name' placeholder='PROJECT TITLE' title='enter the project title' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;'>
                                                </div>
                                            </div>
                                            <div class="row form-group ">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Project Owner<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                  <div id="add_emp" class="form-group">
                                                    <input type="hidden" name="partint3" id="partint" value="1">
                                                        <div class="form-group input-group">

                                                          <input type="text" name="txt_project_owner[]" id="txt_project_owner1" placeholder="PROJECT OWNER" title="select the project owner" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" required>
                                                          <span class="input-group-btn"><button id="add_emp_button" type="button" onclick="subject_addnew()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                          </span>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group ">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Project Head<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                     <div id="add_emp1" class="form-group">
                                                        <input type="hidden" name="partint3" id="partint" value="1">
                                                        <div class="form-group input-group">

                                                          <input type="text" name="txt_project_head[]" id="txt_project_head1" maxlength="50" placeholder="PROJECT HEAD" title="select the project head" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" required>
                                                          <span class="input-group-btn"><button id="add_emp_button" type="button" onclick="subject_addnew1()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                          </span>

                                                        </div>
                                                    </div>

                                                </div>

                                                    </div>
                                            <div class="row form-group ">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Project Members<span style='color:red'>*</span></label>
                                                </div>

                                                <div class="col-lg-8 col-md-8">

                                                         <div id="add_emp2" class="form-group">
                                                        <input type="hidden" name="partint3" id="partint" value="1">
                                                        <div class="form-group input-group">

                                                          <input type="text" name="txt_project_member[]" id="txt_project_member1" maxlength="50" placeholder="PROJECT MEMBER" title="select the project member" class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;" required>
                                                          <span class="input-group-btn"><button id="add_emp_button" type="button" onclick="subject_addnew2()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                          </span>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    </div>
						<div class='clear clear_both'>&nbsp;</div>
						<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
							<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
								<!-- <div id="error_message" class="ajax_response" style="float:left;text-align:center">sda</div></br>
								<div id="success_message" class="ajax_response" style="float:left;text-align:center">asd</div>--> 
							  <input type="button" class="btn btn-success" name="btn_submit" id="btn_submit" value="Submit" onclick="return validate()"/>
							  <!--<input type ="submit" class ="btn btn-success" name="submit" id="submit" value ="Submit" data-toggle="tooltip" title ="submit" />-->
								<button type="reset" tabindex='3' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="reset"><i class="fa fa-times"></i> Reset</button>
							</div>
						<div class='clear clear_both'>&nbsp;</div>
						</div>

					</div>
				</form>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT WRAPPER -->
            </div>
            <!-- END PAGE CONTENT -->
        </div>
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

        <!-- plugins PRAKASH -->

               <script src="http://bladephp.co/download/js/jquery.validate.min.js"></script>

         <!-- START PLUGINS -->
                        <!-- <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script> -->

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

			function add_files(){
                var count = 0;
				count ++;
			var html = '<p><input type="file" name="files[]" id="file_upload" multiple style="padding-bottom:5px">'+
					'<a href= "" onclick="javascript=removeElement('file-'+ count +''); return false;">Delete</a>';
				 addElement('file_upload', 'p', 'file-' + count, html);
			}
			function addElement(parentId, elementTag, elementId, html) {
			// Adds an element to the document
			var p = document.getElementById(parentId);
			var newElement = document.createElement(elementTag);
			newElement.setAttribute('id', elementId);
			newElement.innerHTML = html;
			p.appendChild(newElement);
			}

			function removeElement(elementId) {
			// Removes an element from the document
			var element = document.getElementById(elementId);
			element.parentNode.removeChild(element);
			}
	</script>
        <script type="text/javascript">
			
			// insert data to db
			
			function insertdb()
                {
					
					var result = $('#frm_project_creation').serialize();
						 //alert (result);
						  $.ajax({
							url: 'prakash/insert_project_.php',
							type:'post',
							data: result,
							 cache:false,
							 dataType:'text',
							success:function(data)
							 {
								alert("Project Created");
								//document.getElementById("frm_project_creation").reset();
								 //window.location.reload();
								 console.log(data);
							 },
							error:function(msg){
							   alert("failed");
							}
						 });
			    }
		
			
				//Validation
				//$('#submit').click(function(){
					function validate(){
					var error = 0;
					var msg = 'Please enter all the required fields !! \n';
					var con = 0;
					var fieldno = document.getElementsByName("txt_ledger_name[]");
					var count = fieldno.length;
					var fieldArrayOwner = document.getElementsByName("txt_project_owner[]");
					var fieldArrayHead = document.getElementsByName("txt_project_head[]");
					var fieldArrayMember = document.getElementsByName("txt_project_member[]");
					//alert(msg);
					var fieldArray = [];
					var OwnerCount = [];
					var HeadCount = [];
					var MemberCount = [];
				
				for(con =0 ; con < count ; con++ )
					{
						fieldArray[con] = document.getElementsByName("txt_ledger_name[]")[con].value;
					}
				for(var owner =0 ; owner <fieldArrayOwner.length ; owner++ )
					{
						OwnerCount[owner] = document.getElementsByName("txt_project_owner[]")[owner].value;
					}
				for(var head =0 ; head <fieldArrayHead.length ; head++ )
					{
						HeadCount [head] = document.getElementsByName("txt_project_head[]")[head].value;
					}
				for(var member =0 ; member <fieldArrayMember.length ; member++ )
					{
						MemberCount [member] = document.getElementsByName("txt_project_member[]")[member].value;
					}
				
					var fieldArray1 = fieldArray.slice();
					var fieldOwner1 = OwnerCount.slice();
					var fieldHead1 = HeadCount.slice();
					var fieldMember1 = MemberCount.slice();
					var TotalList = fieldOwner1.concat(fieldHead1,fieldMember1);
					//alert(JSON.stringify(fieldOwner1));
					//alert(JSON.stringify(fieldArray1));
					//alert("Ledger Length is :"+field.length);
					//alert(fieldArray1[0]);
					
						$(':input[required]', '#frm_project_creation').each(function(){
							$(this).css('border','2px solid green');
							if($(this).val() == ''){
								 //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
								$(this).css('border','2px solid red');
								error = 1;
								alert(meg);
							}
						});
						
					function hasDupesInLedger(fieldArray1) 
					{
						  // temporary object 
						  var uniqOb = {};
						  // create object attribute with name=value in array, this will not keep dupes
						  for (var i in fieldArray1)
							uniqOb[fieldArray1[i]] = "";
						  // if object's attributes match array, then no dupes!
						  if (fieldArray1.length == Object.keys(uniqOb).length){
							//alert('Good'); 
							return true;
						  }
						  else{
							alert('Ledger Entry Has Duplicates. Please check');
						  }
					}
					hasDupesInLedger(fieldArray1);
				
				
					function hasDupesInOwner(fieldOwner1)
					{
					  // temporary object 
					  var uniqOb1 = {};
					  // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldOwner1)
						uniqOb1[fieldOwner1[i]] = "";
					  // if object's attributes match array, then no dupes! 
					  if (fieldOwner1.length == Object.keys(uniqOb1).length){
						//alert('Good');
						 return true;
					  }
					  else{
						  alert('Project Owner List Has Duplicates. Please Check');
					  }	
					}
					hasDupesInOwner(fieldOwner1);
					
					
					function hasDupesInHead(fieldHead1) {
					 // temporary object
					  var uniqOb2 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldHead1)
						uniqOb2[fieldHead1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldHead1.length == Object.keys(uniqOb2).length){
						//alert('Good');
					  return true;}
					  else{
					  alert('Project Head List Has Duplicates. Please Check');}
					}
				
					hasDupesInHead(fieldHead1);
					
					function hasDupesInMember(fieldMember1) {
					 // temporary object
					  var uniqOb3 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in fieldMember1)
						uniqOb3[fieldMember1[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (fieldMember1.length == Object.keys(uniqOb3).length){
						
					  return true;}
					  else{
						alert('Project Member List Has Duplicates. Please Check');
					  }
					}
					hasDupesInMember(fieldMember1);
					
					TotalPerson();
					
					function TotalPerson()
					{
					var txt = "Repeated Project Persons List is Present . Please Check";
					var title = "Error";
					function hasDupesInTotal(TotalList) {
					 // temporary object
					  var uniqOb4 = {};
					 // create object attribute with name=value in array, this will not keep dupes
					  for (var i in TotalList)
						uniqOb4[TotalList[i]] = "";
					  //if object's attributes match array, then no dupes! 
					  if (TotalList.length == Object.keys(uniqOb4).length){
						
					 // return true;
                    insertdb();
                    }
                      else{
						  //createCustomAlert(txt, title);
                        alert('Repeated Project Persons List is Present . Please Check');
                      }
                    }
                    
                    hasDupesInTotal(TotalList); 
                        
                    //alert(JSON.stringify(TotalList));
                    //alert('form has been submitted');
                    }
                    
                    return false;
					
					
					}
				//});
				/*function createCustomAlert(txt, title) {
				d = document;

				if(d.getElementById("modalContainer") return;

				mObj = d.getElementsByTagName("body"[0].appendChild(d.createElement("div");
				mObj.id = "modalContainer";
				mObj.style.height = d.documentElement.scrollHeight + "px";

				alertObj = mObj.appendChild(d.createElement("div");
				alertObj.id = "alertBox";
				if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
				alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
				alertObj.style.visiblity="visible";

				h1 = alertObj.appendChild(d.createElement("h1");
				h1.appendChild(d.createTextNode(title));

				msg = alertObj.appendChild(d.createElement("p");
				//msg.appendChild(d.createTextNode(txt));
				msg.innerHTML = txt;

				btn = alertObj.appendChild(d.createElement("a");
				btn.id = "closeBtn";
				btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
				btn.href = "#";
				btn.focus();
				btn.onclick = function() { removeCustomAlert();return false; }

				alertObj.style.display = "block";
				}

				function removeCustomAlert() {
				document.getElementsByTagName("body"[0].removeChild(document.getElementById("modalContainer");
				}

			*/
				
				
					
					
					
			
			
            // subcore fetching from db
            $.ajax({
                url: 'prakash/subcore.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 $("#txt_core").append("<option value='' selected hidden>CHOOSE THE CORE</option>");
                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  $("#txt_core").append("<option value='"+id+" - "+name+"'>"+name+"</option>");
                 }
                }
            });
            // branch fetching from db

            $.ajax({
                url: 'prakash/branch.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 $("#txt_branch_type").append("<option value='' selected hidden>CHOOSE THE BRANCH</option>");
                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  var brn = response[i]['brn'];
                  $("#txt_branch_type").append("<option value='"+id+" , "+name+"'>"+brn+"</option>");
                 }
                }
            });
            // Topcore fetching from db
             $.ajax({
                url: 'prakash/topcore.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 $("#txt_top_core").append("<option value='' selected hidden>CHOOSE THE TOP CORE</option>");
                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  $("#txt_top_core").append("<option value='"+id+" - "+name+"'>"+name+"</option>");
                 }
                }
            });


             $.ajax({
                url: 'prakash/ledger.php',
                type: 'post',
                //data: {top_core:top_core},
                dataType: 'json',
                success:function(response){
                 var len = response.length;
                 // $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
                 $("#txt_ledger_name1").append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

                 for( var i = 0; i<len; i++){
                  var id = response[i]['id'];
                  var name = response[i]['name'];
                  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
                  $("#txt_ledger_name1").append("<option value='"+name+"'>"+name+"</option>");

                 }
                }
            });

            
// Project Head List out Scripts
            $('#txt_project_head1').autocomplete({
                  source: function( request, response ) {
                    $.ajax({
                      url : 'ajax/ajax_employee_details.php',
                      dataType: "json",
                      data: {
                         slt_emp: request.term,
                         brncode: 888,
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


             // Project Member List out scripts
            $('#txt_project_member1').autocomplete({
                  source: function( request, response ) {
                    $.ajax({
                      url : 'ajax/ajax_employee_details.php',
                      dataType: "json",
                      data: {
                         slt_emp: request.term,
                         brncode: 888,
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
		
		// Project owner list script
            $('#txt_project_owner1').autocomplete({
            source: function( request, response ) {
              $.ajax({
                url : 'ajax/ajax_employee_details.php',
                dataType: "json",
                data: {
                   slt_emp: request.term,
                   brncode: 888,
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

        // Multiple Ledger Generate
		

        function subject_addnew4() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#add_ledger_row').val();
        var id = (parseInt(value) + 1).toString();
		//var id = value +1;
        $('#add_ledger_row').val(id);
		/*
		if(document.getElementById('txt_ledger_name'+id)==ledgercode)
		{
			alert('Duplicate value');
		}
		*/
        $('#add_ledger').append(
              '<div class="form-group input-group">'+
				'<div style="width:100%">'+
					'<div style="width:70%;float:left;">'+
						'<select class="form-control" autofocus required name="txt_ledger_name[]" id="txt_ledger_name'+id+'" data-toggle="tooltip" data-placement="top" title="ledger Name" ></select>'+
					'</div>'+
					'<div style="width:30%;float:right">'+
						'<input type="text" name = "txt_value[]" id= "txt_value'+id+'"class="form-control" placeholder= "VALUE" data-toggle="tooltip" title ="values" required>'+
					'</div>'+
				'</div>'+
				'<span class="input-group-btn"><button id="add_ledger_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
			  '</div>');

                 $.ajax({
					url: 'prakash/ledger.php',
					type: 'post',
					//data: {top_core:top_core},
					dataType: 'json',
					success:function(response){
					 
					 
					 var len = response.length;
					 
					 // $("#txt_ledger_code").append("<option value='' selected hidden>CHOOSE THE LEDGER CODE</option>");
					 $("#txt_ledger_name"+id).append("<option value='' selected hidden>CHOOSE THE LEDGER NAME</option>");

					 for( var i = 0; i<len; i++){
					  var idd = response[i]['id'];
					  var name = response[i]['name'];
					  // $("#txt_ledger_code").append("<option value='"+id+"'>"+id+"</option>");
					  $("#txt_ledger_name"+id).append("<option value='"+name+"'>"+name+"</option>");

					 }
					}
				});
                 $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });


        }

		


        function subject_addnew() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#partint').val();
        var id = (parseInt(value) + 1).toString();
        $('#partint').val(id);
        $('#add_emp').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_owner[]" id="txt_project_owner'+id+'" required placeholder="PROJECT OWNER"  class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove data-toggle="tooltip" title ="remove"">-</button></span>'+
          '</div>');

               $('#txt_project_owner'+id).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
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
          $( document ).on( 'click', '.btn-remove', function ( event ) {
            	event.preventDefault();
            	$(this).closest( '.form-group' ).remove();
           });
    }

    function subject_addnew1() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#partint').val();
        var id = (parseInt(value) + 1).toString();
        $('#partint').val(id);
        $('#add_emp1').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_head[]" id="txt_project_head'+id+'" required maxlength="50" placeholder="PROJECT HEAD"  class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
          '</div>');

               $('#txt_project_head'+id).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
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
          $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
    }

    function subject_addnew2() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id1 = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#partint').val();
		var id = (parseInt(value) + 1).toString();
        $('#partint').val(id);
        $('#add_emp2').append(
          '<div class="form-group input-group">'+

            '<input type="text" name="txt_project_member[]" id="txt_project_member'+id+'" required maxlength="50" placeholder="PROJECT MEMBER"  class="form-control find_empcode" data-toggle="tooltip" data-placement="top" style=" text-transform: uppercase; padding: 2px;">'+'<span class="input-group-btn"><button id="add_emp_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip" title ="remove">-</button></span>'+
          '</div>');

               $('#txt_project_member'+id).autocomplete({
               source: function( request, response ) {
                 $.ajax({
                   url : 'ajax/ajax_employee_details.php',
                   dataType: "json",
                   data: {
                      slt_emp: request.term,
                      brncode: 888,
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
          $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });
        }

















                                // function PrintDiv(dataurl) {
                                //     var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
                                // }

                                // $(function() {
                                //     var showTotalChar = 200, showChar = "Show (+)", hideChar = "Hide (-)";
                                //     $('.show_moreless').each(function() {
                                //         var content = $(this).text();
                                //         if (content.length > showTotalChar) {
                                //             var con = content.substr(0, showTotalChar);
                                //             var hcon = content.substr(showTotalChar, content.length - showTotalChar);
                                //             var txt= '<b>'+con +  '</b><span class="dots">...</span><span class="morectnt"><span>' + hcon + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" class="showmoretxt">' + showChar + '</a></span>';
                                //             $(this).html(txt);
                                //         }
                                //     });

                                //     $(".showmoretxt").click(function() {
                                //         if ($(this).hasClass("sample")) {
                                //             $(this).removeClass("sample");
                                //             $(this).text(showChar);
                                //         } else {
                                //             $(this).addClass("sample");
                                //             $(this).text(hideChar);
                                //         }
                                //         $(this).parent().prev().toggle();
                                //         $(this).prev().toggle();
                                //         return false;
                                //     });
                                // });

                                // $(document).ready(function() {
                                //     $("#load_page").fadeOut("slow");
                                //     $(".finish_confirm").click( function() {
                                //     });
                                // });

                                // $(document).keypress(function(e) {
                                //     if (e.keyCode == 27) {
                                //         $("#myModal1").fadeOut(500);
                                //     }
                                // });

                                // $('#datepicker-example3').Zebra_DatePicker({
                                //   direction: false, // 1,
                                //   format: 'd-M-Y',
                                //   pair: $('#datepicker-example4')
                                // });

                                // $('#datepicker-example4').Zebra_DatePicker({
                                //   direction: [1, '<?=date("d-M-Y")?>'], // ['<?=date("d-M-Y")?>', 0], // 0,
                                //   format: 'd-M-Y'
                                // });

                                // function call_confirm(ivalue, reqid, year, rsrid, creid, typeid, aprnumb)
                                // {
                                //     $('#load_page').show();
                                //     var send_url = "final_finish.php?aprnumb="+aprnumb+"&reqid="+reqid+"&year="+year+"&rsrid="+rsrid+"&creid="+creid+"&typeid="+typeid;
                                //     $.ajax({
                                //     url:send_url,
                                //     type: "POST",
                                //     success:function(data){
                                //             $("#myModal1").modal('show');
                                //             $('#load_page').hide();
                                //             document.getElementById('modal-body1').innerHTML=data;
                                //             $('#load_page').hide();
                                //         }
                                //     });
                                // }

                                // function cmnt_mail(aprnumb)
                                // {
                                //     var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
                                //     $.ajax({
                                //     url:sendurl,
                                //     success:function(data){
                                //         $("#myModal2").modal('show');
                                //         $('#modal-body2').html(data);
                                //         $('#txtmailcnt').val("");
                                //         }
                                //     });
                                // }

                                // function cmt_usr()
                                // {
                                //     $('#cmtusr').css("display", "block");
                                //     $('.select2').select2();
                                //     $('#mailusr').focus();
                                //     //$("#mailusr").select2("open");
                                //     $('#mailusr').select2({
                                //     placeholder: 'Enter EC No/Name to Select an mail user',
                                //     allowClear: true,
                                //     dropdownAutoWidth: true,
                                //     minimumInputLength: 3,
                                //     maximumSelectionLength: 3,
                                //     ajax: {
                                //       url: 'ajax/ajax_general_temp.php?action=MAILUSER',
                                //       dataType: 'json',
                                //       delay: 250,
                                //       processResults: function (data) {
                                //         return {
                                //           results: data
                                //         };
                                //       },
                                //       cache: true
                                //     }
                                //   });
                                // }
        </script>
    <!-- END SCRIPTS -->
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
