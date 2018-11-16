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

       /* $sql_emp = select_query_json("select *
                                     from employee_office    
                                     order by EMPCODE", "Centra", 'TCS'); */
									 $sql_emp = select_query_json("select emp.EMPSRNO, emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, des.DESNAME
                                                                                                    from employee_office emp, empsection sec, designation des
                                                                                                    where  emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and EMPCODE >1000
                                                                                                    order by EMPCODE", "Centra", 'TEST');                                                                                                                                                                                                                                                                                                                  
?>

<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title> Request Entry :: Approval Desk :: </title>
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
	
	<center><img src="images/logo-.png" alt="The Chennai Silks - Logo"/>
</center>
<center><font size="5px">POLICY-APPROVAL</font></center>
            
					   <center><table border="2" ><center>
					   
                                    <div id='id_policy_approval' style="padding-left: 10px; text-align: center; display: none;">
                                        <div class="parts3 fair_border">
                                            <div class="row colheight" style="margin-right: 0px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; line-height: 25px; font-weight: bold; text-align: center;">
                                                POLICY - APPROVAL
                                            </div>
											<thead><th colspan="2">
                                            <div class="row" style="margin-right: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: left; line-height: 30px;">POLICY SUBJECT : </div>
                                                <div class="col-sm-10 colheight" style="padding: 0px; line-height: 30px;">
                                                    <select class="form-control policy_approval_required" required name='txtdynamic_subject' id='txtdynamic_subject' data-toggle="tooltip" data-placement="top" data-original-title="POLICY SUBJECT">
                                                        <?  $sql_project = select_query_json("select * from approval_policy_master where DELETED = 'N' order by aplcysr", "Centra", 'TCS');
                                                            for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                                <option value='<?=$sql_project[$project_i]['APLCYCD']?>' <? if($sql_reqid[0]['APLCYCD'] == $sql_project[$project_i]['APLCYCD']) { ?> selected <? } ?>><?=$sql_project[$project_i]['APLCYNM']?></option>
                                                        <? } ?>
                                                    </select>
                                                </div>
                                                </th></thead>
                                                <tbody>
												<tr>
												<td>
                                            <div class="row" style="margin-left: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-5 colheight" style="padding: 0px; text-align: left; line-height: 30px;">EFFECTIVE DATE : </div>
                                                <div class="col-sm-7 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly="readonly" maxlength="11" tabindex="5" value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
                                                </div></td><td>

                                                <div class="col-sm-6 colheight" style="padding: 0px; text-align: left; line-height: 30px;">POLICY TYPE : </div>
                                                <div class="col-sm-5 colheight" style="padding: 0px; line-height: 30px;">
                                                    <select class="form-control policy_approval_required" required name='txtdynamic_policy_type' id='txtdynamic_policy_type' data-toggle="tooltip" data-placement="top" data-original-title="POLICY TYPE">
                                                        <option value='ORIGINAL'>ORIGINAL</option>
                                                        <option value='RENEWAL'>RENEWAL</option>
                                                    </select>
                                                </div>
                                            </div></td></tr>
                                             <tr><td>
                                            <div class="row" style="margin-left: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-5 colheight" style="padding: 5px; text-align: left; line-height: 30px;">VALID UPTO : </div>
                                                <div class="col-sm-7 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="search_todate" id="datepicker-example4" autocomplete="off" readonly="readonly" maxlength="11" tabindex="10" value='' data-toggle="tooltip" data-placement="top" placeholder="To Date" title=""  >
                                                </div></td><td>

                                                <div class="col-sm-6 colheight" style="padding: 0px; text-align: left; line-height: 30px;">CREATOR EC NO \ NAME : </div>
                                                <div class="col-sm-5 colheight" style="padding: 0px; line-height: 30px;">
												
                                                 <!--<input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="CREATOR" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>-->
                                                  <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_creator' id='txtdynamic_creator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												  <option>--Select User--</option>
												  <option>1 - SIVALINGAM K </option>
												<option>2 - PADHMA SIVALINGAM K </option>
												<option>3 - S KAARTHI - DIRECTOR </option>
												<option>4 - ANUMALERVILI S </option>
												<option>5 - SIVASANKARI BABU </option>
												  <? 	for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									              <option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
					                
								
							                </select>                                                       
												
												</div>
                                            </div></td></tr>
                                              <tr><td>
                                            <div class="row" style="margin-right: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-5 colheight" style="padding: 0px; text-align: left; line-height: 30px;">APPROVAL DATE : </div>
                                                <div class="col-sm-7 colheight" style="padding: 0px; line-height: 30px;">
                                                    <!--<input type="text" name="txtdynamic_valid_upto" id="datepicker_example5" class="form-control policy_approval_required" required readonly placeholder='VALID UPTO' autocomplete='off' value=' ' maxlength='11' title='APPROVAL DATE'>-->
                                               <input type="text" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="search_fromdate" id="datepicker-example5" autocomplete="off" readonly="readonly" maxlength="11" tabindex="10" value="<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>" data-toggle="tooltip" data-placement="top" placeholder="From Date" title=""  >
											   
												</div></td><td>

                                                <div class="col-sm-6 colheight" style="padding: 0px; text-align: left; line-height: 30px;">CO-ORDINATOR EC NO \ NAME : </div>
                                                <div class="col-sm-5 colheight" style="padding: 0px; line-height: 30px; ">
                                                    <!--<input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="ASSIST BY" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>-->
                                                 <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_coordinator' id='txtdynamic_coordinator' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPNAME']." - ".$sql_emp[0]['EMPCODE']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												   <option>--Select User--</option>
												   <option>1 - SIVALINGAM K </option>
												<option>2 - PADHMA SIVALINGAM K </option>
												<option>3 - S KAARTHI - DIRECTOR </option>
												<option>4 - ANUMALERVILI S </option>
												<option>5 - SIVASANKARI BABU </option>
												   <? 	for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									<option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		
                                                        <?  } ?>
					                
								
							                </select>
												</div>
                                            </div></td></tr>
                                              <tr><td>
                                            <div class="row" style="margin-right: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-5 colheight" style="padding: 0px; text-align: left; line-height: 30px;">USER LIST : </div>
                                                <div class="col-sm-7 colheight" style="padding: 0px; line-height: 30px;">
                                               <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_userlist' id='txtdynamic_userlist' data-toggle="tooltip" data-placement="top" data-original-title="USER LIST"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
                                                <option>--Select one--</option>
												<option>1 - SIVALINGAM K - MANAGING DIRECTOR - 01 ADMINISTRATION</option>
												<option>2 - PADHMA SIVALINGAM K - DIRECTOR - 01 ADMINISTRATION</option>
												<option>3 - S KAARTHI - DIRECTOR - 96 S TEAM</option>
												<option>4 - ANUMALERVILI S - MANAGER - 01 ADMINISTRATION</option>
												<option>5 - SIVASANKARI BABU - MANAGER - 01 ADMINISTRATION</option>
                                                              
                                                                <? 	for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									<option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME']." - ". $sql_emp[$sql_emp_i]['DESNAME']." - ".  $sql_emp[$sql_emp_i]['ESENAME'].""?></option>
		
                                                        <?  } ?>
					                
								
							                 </select>
												</div></td><td>

                                                <div class="col-sm-6 colheight" style="padding: 0px; text-align: left; line-height: 30px;">ASSIST BY EC NO \ NAME : </div>
                                                <div class="col-sm-5 colheight" style="padding: 0px; line-height: 30px;">
                                                    <!--<input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_assistby' id='txtdynamic_assistby' data-toggle="tooltip" data-placement="top" data-original-title="APPROVED BY" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>-->
                                                   <select  class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_assistby' id='txtdynamic_assistby' data-toggle="tooltip" data-placement="top" data-original-title="ASSISTBY"  value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>     
												  <option>--Select User--</option>
												  <option>1 - SIVALINGAM K </option>
												<option>2 - PADHMA SIVALINGAM K </option>
												<option>3 - S KAARTHI - DIRECTOR </option>
												<option>4 - ANUMALERVILI S </option>
												<option>5 - SIVASANKARI BABU </option>
												    	<?for($sql_emp_i = 0; $sql_emp_i < count($sql_emp); $sql_emp_i++) { ?>
																
									<option value='<?=$sql_emp[$sql_emp_i]['EMPCODE']?>'><?=$sql_emp[$sql_emp_i]['EMPCODE']." - ".$sql_emp[$sql_emp_i]['EMPNAME'].""?></option>
		                                                     
                                                        <? } ?>
					                
								
							                </select>
												</div>
                                            </div></td></tr>
                                            <tr><td colspan="2">
                                            <div class="row" style="margin-left: 0px; display: flex; text-transform: uppercase;">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: left; line-height: 30px;">DESK PROCEDURE : </div>
                                                <div class="col-sm-10 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='file' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_deskprocedure' id='txtdynamic_deskprocedure' data-toggle="tooltip" data-placement="top" data-original-title="DESK PROCEDURE" value=''>
                                                </div></td></tr>
                                                <tr><td colspan="2">
                                                <div class="col-sm-2 colheight" style="padding: 0px; text-align: left; line-height: 30px;">POLICY DOCUMENTS : </div>
                                                <div class="col-sm-10 colheight" style="padding: 0px; line-height: 30px;">
                                                    <input type='text' class="form-control policy_approval_required" style="text-transform: uppercase;" required name='txtdynamic_policy_docs' id='txtdynamic_policy_docs' data-toggle="tooltip" data-placement="top" data-original-title="POLICY DOCUMENTS" value=''>
                                                </div>
                                            </div>
                                            </div>
                                        </div></td></tr>
										<tr><td colspan="2">
										
										<?  if($_REQUEST['action'] == 'view') {
                                                        echo ": ".$sql_reqid[0]['APPRDET'];
                                                   } else { ?>
										                <input type="hidden" name="hid_apprsub" id='hid_apprsub' value="<?=$sql_reqid[0]['APPRSUB']?>" onblur="find_tags();">
                                                        <textarea name="FCKeditor1" id="FCKeditor1" tabindex='14'>
                                                            <?  if($_REQUEST['action'] == 'edit') {
                                                                    if($sql_reqid[0]['APPRFOR'] == '1') {
                                                                        $filepathname = $sql_reqid[0]['APPRSUB'];
                                                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.":5022/approval_desk/text_approval_source/".$filepathname;
                                                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_reqid[0]['APPRSUB']);
                                                                        $contents = fread($handle, filesize($filename));
                                                                        fclose($handle);
                                                                        echo $contents;
                                                                    } else {
                                                                        echo ": ".$sql_reqid[0]['APPRDET'];
                                                                    }
                                                                }?>
                                                            
                                                        </textarea>
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
                                                <? } ?>
										</td></tr>
										
                                        <div class='clear clear_both'></div>
                                    </div>  
                                    <div class='clear clear_both'></div>
                                </div>
								</tbody></table>
                                <!-- Policy Docs-->
		
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
	<link href="css/fSelect.css" rel="stylesheet">
    <script src="js/fSelect.js"></script>
    <script type="text/javascript">
	$('#txtdynamic_userlist').fSelect();

	function reject_reason(iv) {
		var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		var cnt = $('input.common_style:checked').length;
		if(cnt >= 1) {
			if(aa == false) {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", true);
				$("#id_reason_reject_"+iv).css('display', 'block');
			} else {                            
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", false);
				$("#id_reason_reject_"+iv).css('display', 'none');
			}
		} else {
			alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			$("#chk_reject_reason_"+iv).prop('checked', true);
		}
	}
	
	$('#txtdynamic_creator').fSelect();

	function reject_reason(iv) {
		var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		var cnt = $('input.common_style:checked').length;
		if(cnt >= 1) {
			if(aa == false) {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", true);
				$("#id_reason_reject_"+iv).css('display', 'block');
			} else {                            
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", false);
				$("#id_reason_reject_"+iv).css('display', 'none');
			}
		} else {
			alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			$("#chk_reject_reason_"+iv).prop('checked', true);
		}
	}
   
   
        $('#txtdynamic_assistby').fSelect();

	function reject_reason(iv) {
		var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		var cnt = $('input.common_style:checked').length;
		if(cnt >= 1) {
			if(aa == false) {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", true);
				$("#id_reason_reject_"+iv).css('display', 'block');
			} else {                            
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", false);
				$("#id_reason_reject_"+iv).css('display', 'none');
			}
		} else {
			alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			$("#chk_reject_reason_"+iv).prop('checked', true);
		}
	}
	     $('#txtdynamic_coordinator').fSelect();

	function reject_reason(iv) {
		var aa = document.getElementById("chk_reject_reason_"+iv).checked;
		var cnt = $('input.common_style:checked').length;
		if(cnt >= 1) {
			if(aa == false) {
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", true);
				$("#id_reason_reject_"+iv).css('display', 'block');
			} else {                            
				$("#hid_reason_reject_"+iv).val("");
				$("#hid_reason_reject_"+iv).attr("required", false);
				$("#id_reason_reject_"+iv).css('display', 'none');
			}
		} else {
			alert("Atleast one Product must need to proceed further. Or kindly reject this approval!!");
			$("#chk_reject_reason_"+iv).prop('checked', true);
		}
	}
		$(document).ready(function() {
        $("#load_page").fadeOut("slow");
        $(".finish_confirm").click( function() {
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
     
    });

    $('#datepicker-example4').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+1 days")))?>', false],
      format: 'd-M-Y'
    });

	$('#datepicker-example5').Zebra_DatePicker({
      direction: false, // 1,
      format: 'd-M-Y',
     
    });
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
			  $('#datepicker_example3').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
      format: 'd-M-Y'
    });

    $('#datepicker_example4').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
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

	</script>										

      </body>
</html> 