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
    switch($txt_process_type) {
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
            break;

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
                            <form role="form" id='frm_project' name='frm_project' action='' method='post' enctype="multipart/form-data">
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

                                    
                                    <!-- Top Core -->
                                    <div class="  row form-group">
                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Branch<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 ">
                                            <select class="form-control" autofocus required name='txt_branch_type' id='txt_branch_type' data-toggle="tooltip" data-placement="top" title="branch type">
                                                
                                            </select>
                                        </div>

                                        <div class="form-group ">
                                        <div class="col-lg-2 col-md-2">
                                            <label style='height:27px;'>Project Name<span style='color:red'>*</span></label>
                                        </div>
                                            <div class="col-lg-4 col-md-4">
                                           
                                                <input type='text' name='txt_project_name' id='txt_project_name' placeholder='ENTER THE PROJECT TITLE' title='enter the project title' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;'>
                                            </div>
                                        </div>
                                       </div>
                                                                          

                                    
                                     <div class="row form-group">                             

                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Top Core<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                             <select class="form-control" autofocus required name='txt_top_core' id='txt_top_core' data-toggle="tooltip" data-placement="top" title="choose the top core" >
                                             </select>
                                        </div>
                                        <div class="form-group ">
                                            <div class="col-lg-2 col-md-2">
                                                <label style='height:27px;'>Project Owner<span style='color:red'>*</span></label>
                                            </div>
                                            <div class="col-lg-3 col-md-3">
                                                 <div class="form-group multiple-form-group input-group">
                                                    <input type="text" name="project_owner['value'][]" class="form-control" placeholder='ENTER THE PROJECT OWNER' required >
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success btn-add">+</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="row form-group">                             
                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Core<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <select class="form-control" autofocus required name='txt_core' id='txt_core' data-toggle="tooltip" data-placement="top" title="choose the core" >
                                                 
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                        <div class="col-lg-2 col-md-2">
                                            <label style='height:27px;'>Project Head<span style='color:red'>*</span></label>
                                        </div>
                                            <div class="col-lg-3 col-md-3">
                                                      
                                                <div class="form-group multiple-form-group input-group">
                                                    <input type="text" name="project_head['value'][]" class="form-control" placeholder='ENTER THE PROJECT HEAD' required >
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success btn-add">+</button>
                                                    </span>
                                                </div>
                                                </div>
                                               <!-- <input type='text' name='txt_project_head' id='txt_project_head' placeholder='SELECT THE PROJECT HEAD' title='select the project head' data-toggle="tooltip" data-placement="top" required value='' class='form-control'> -->
                                            </div>
                                        </div>
                                    
                                   <div class="row form-group">                             

                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Ledger Code<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <select class="form-control" autofocus required name='txt_ledger_code' id='txt_ledger_code' data-toggle="tooltip" data-placement="top" title="choose the ledger code" >
                                              <option value="">--CHOOSE THE CORE--</option>

                                                <option value="HR" >HR</option>
                                                <option value="CIVIL" >CIVIL</option>
                                                                                              
                                            </select>
                                        </div>
                                        <div class="form-group ">
                                        <div class="col-lg-2 col-md-2">
                                            <label style='height:27px;'>Project Members<span style='color:red'>*</span></label>
                                        </div> 
                                      
                                            <div class="col-lg-3 col-md-3">
                                               
                                                    <div class="form-group multiple-form-group input-group">
                                                    <input type="text" name="project_member['value'][]" class="form-control" placeholder='ENTER THE PROJECT MEMBER' required >
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success btn-add">+</button>
                                                    </span>
                                                    </div>
                                                
                                            </div>
                                            
                                        </div>
                                  </div>
                                   
                                  <div class="row form-group">                             

                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Ledger Name<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                        <select class="form-control" autofocus required name='txt_ledger_name' id='txt_ledger_name' data-toggle="tooltip" data-placement="top" title="Ledger Name" >
                                             <option value="">--CHOOSE LEDGER NAME--</option>
                                                <option value="ESI" >ESI</option>
                                                <option value="PF" >PF</option>
                                               <option value="BOUNS EXPENSES" >BOUNS EXPENSES</option>
                                                <option value="SALARY EXPENSES" >SALARY EXPENSES</option>
                                                <option value="BUILDING MAINTENANCE" >BUILDING MAINTENANCE</option>
                                                
                                               
                                            </select>
                                        </div>
                                  </div>
                                  <div class="row form-group">                             

                                        <div class="col-lg-3 col-md-3">
                                            <label style='height:27px;'>Mode<span style='color:red'>*</span></label>
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <select class="form-control" autofocus required name='txt_mode_type' id='txt_mode_type' data-toggle="tooltip" data-placement="top" title="select mode type" >
                                                 <option value="">--CHOOSE THE MODE--</option>
                                                 <option value="BRANCH" >BRANCH</option>
                                                 <option value="NEW PROJECT" >NEW PROJECT</option>
                                            </select>
                                        </div>
                                  </div>
                                 

                                                                        


                                        <!-- <div class="col-lg-6 col-md-6">
                                            <input type='text' name='txt_aprnumb' id='txt_aprnumb' placeholder='ENTER THE FULL APPROVAL NO' title='ENTER THE FULL APPROVAL NO' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;'>
                                            <select class="form-control" required name='slt_project' id='slt_project' data-toggle="tooltip" data-placement="top" style="margin-top:5px; display: none;">
                                            <?  $sql_project = "select * from approval_project where DELETED = 'N' order by APRCODE Asc";
                                                $sql_project = select_query_json($sql_project);
                                                for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
                                                    <option value='<?=$sql_project[$project_i]['APRCODE']?>' <? if($sql_reqid[0]['APRCODE'] == $sql_project[$project_i]['APRCODE']) { $tpcr = $sql_project[$project_i]['ATCCODE']; ?> selected <? } ?>><?=$sql_project[$project_i]['APRCODE']." - ".$sql_project[$project_i]['APRNAME']?></option>
                                            <? } ?>
                                            </select>
                                        </div> -->
                                    </div>
                                    <div class='clear clear_both'>&nbsp;</div>
                                    <!-- Top Core -->
                                    
                                    <div class="form-group trbg" style='min-height:40px; padding-top:10px'>
                                        <div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
                                            <button type="submit" name='sbmt_request' id='sbmt_request' tabindex='2' value='submit' class="btn btn-success" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;<button type="reset" tabindex='3' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button>
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
        <!-- START PLUGINS -->
        <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->
        <link rel="stylesheet" href="css/default.css" type="text/css">
        <script type="text/javascript" src="prakash/multitextbox.js"> </script>
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


            // $("#txt_project_head").blur(function () {
              
            //     var branch= $('#txt_branch_type').val();
                
            //     var project_name =$('#txt_project_name').val();
            //     var topcore=$('#txt_top_core').val();
               
            //     var core=$('#txt_core').val();
            //     var ledgercode =$('#txt_ledger_code').val();
            //     var ledgername=$('#txt_ledger_name').val();
            //     var projecthead=$('#txt_project_head').val();
            //     var projectid=branch+"/"+topcore+"/"+core+"/"+ledgercode+"/"+ledgername+"/"+project_name;
            // // alert(projectid);
            //     $("#txt_project_id").val(projectid);

                
            // });
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
                  $("#txt_core").append("<option value='"+id+"'>"+name+"</option>");
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
                  $("#txt_branch_type").append("<option value='"+id+"'>"+name+"</option>");
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
                  $("#txt_top_core").append("<option value='"+id+"'>"+name+"</option>");
                 }
                }
            });
            
            $('.multipleSelect').fastselect();
                
        
    




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
        }
        </script>
    <!-- END SCRIPTS -->
    </body>
    </html>
<? } // Menu Permission is allowed
else { ?>
    <script>alert("You dont have access to view this"); window.location='home.php';</script>
<? exit();
} ?>
