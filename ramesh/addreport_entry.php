<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
$_SERVER['GATEWAY_INTERFACE'];
/* include('../approval_desk-ftp/lib/config.php');
include('../db_connect/public_functions.php');
include('../approval_desk-ftp/general_functions.php'); */

if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="../index.php";</script>
<?php exit();
}


$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);



if($_SESSION['auditor_login'] == 1) { ?>
    <script>alert('You dont have rights to access this page.'); window.location="index.php";</script>
<? exit();
}


    // $PRCENTRYNO = select_query_json("Select nvl(Max(PRCSNO),0)+1 MAXENTRY From SUPMAIL_PROCESS where PRCSYR = '".$current_year."'","Centra","TEST");
    // $table5='SUPMAIL_PROCESS_ENTRY';
    // $entry=array();
    // $entry['TEMPYR']=$current_yr;
    // $entry['TEMPNO']=$PRCENTRYNO;
    // $entry['PRCSYR']=$current_yr;
    // $entry['PRCSNO']=$ENTRYNO;
    // $entry['LANGCOD']=$langcode[strtoupper($_REQUEST['lang_name'][$k])];
    // $entry['TEMCMNT']=$_REQUEST['comments'];
    // $entry['DELETED']='N';
    // $entry['ADDUSER']=$_SESSION['tcs_usrcode'];
    // $entry['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    // $entry['EDTUSER']="";
    // $entry['EDTDATE']="";
    // $entry['DELUSER']="";
    // $entry['DELDATE']="";
    // $entry_insert_subject = insert_test_dbquery($entry,$table5);







?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title><?=$title_tag?> Request Entry :: Approval Desk :: <?php echo $site_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->
    <!-- CSS INCLUDE -->
    <?  $theme_view = "../css/theme-default.css";
        if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; } ?>
    <link rel="stylesheet" type="text/css" id="theme" href="<?=$theme_view?>"/>
    <!-- EOF CSS INCLUDE -->
    <link href="../css/jquery-customselect.css" rel="stylesheet" />
    <script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
    <link href="../css/monthpicker.css" rel="stylesheet" type="text/css">
    <link href="../css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="../css/jquery-ui-1.10.3.custom.min.css" />
    <!-- multiple file upload -->
    <link href="../css/jquery.filer.css" rel="stylesheet">
    <script src="../js/angular.js"></script>
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
            <? include '../lib/app_left_panel.php'; ?>
            <!-- END X-NAVIGATION -->
        </div>
        <!-- END PAGE SIDEBAR -->

        <!-- PAGE CONTENT -->
        <div class="page-content">

            <!-- START X-NAVIGATION VERTICAL -->
            <? include "../lib/app_header.php"; ?>
            <!-- END X-NAVIGATION VERTICAL -->

            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                <li><a href="home.php">Dashboard</a></li>
                <li><a href="request_list.php">Report Entry</a></li>
               <!--  <li class="active"><?=$title_tag?> Request Entry</li> -->
            </ul>
            <!-- END BREADCRUMB -->
            <style>
                .input_group{
                    width:100%;
                }
            </style>
            <!-- PAGE CONTENT WRAPPER -->
           <div class="page-content-wrap">
                
                    <div class="row">
                        <div class="col-md-12">
                            
                               <!-- action='prakash/insert_project.php' -->
                          <form role="form" id='frm_project_creation' name='frm_project_creation' method='post' enctype="multipart/form-data" action="postfields.php">
                               
                                    <!-- Top Core -->
                                    <div class="row">
                                        <div class="col-md-6">
                                             <div class="row form-group " >
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Process Name<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">

                                                    <input type='text' name='process_name' id='process_name' placeholder='PROCESS TITLE' title='enter the process title' data-toggle="tooltip" data-placement="top" required value='' class='form-control' maxlength="100" style='text-transform:uppercase;'>
                                                </div>
                                            </div>

                                             <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Fields<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                     <div id="add_fields" class="form-group">
                                                        <input type="hidden" name="add_field" id="add_field_row" value="1">
                                                        <div class="form-group input-group">
                                                        <div style="width:100%">
                                                            <div style="width:50%;float:left;">
                                                                <input type="text" name = 'field_name[]' class="form-control" id='field_name1' placeholder= "NAME"  data-toggle ="tooltip" title ="ENTER NAME" required>
                                                            
                                                            </div>
                                                            <div style="width:50%;float:right">
                                                                <input type="text" name = 'field_value[]' class="form-control" id='field_value1' placeholder= "VALUE"  data-toggle ="tooltip" title ="values" required>
                                                            </div>
                                                        </div>

                                                        <span class="input-group-btn"><button id="add_field_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                          </span>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Language<span style='color:red'>*</span></label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                    <div id="add_language" class="form-group">
                                                        <input type="hidden" name="add_language" id="add_language_row" value="1">
                                                        <div class="form-group input-group">
                                                        <div style="width:100%">
                                                            <div style="width:50%;float:left;">
                                                                <input type="text" name = 'lang_name[]' class="form-control" id='field_name1' placeholder= "NAME"  data-toggle ="tooltip" title ="ENTER NAME" required>
                                                            
                                                            </div>
                                                            <div style="width:50%;float:right">
                                                               <input type="file" name="langfiles[]" id="lang_upload1" class="form-control"  style="padding-bottom:5px">
                                                            </div>
                                                        </div>

                                                        <span class="input-group-btn"><button id="add_lang_button" type="button" onclick="subject_addnew3()" class="btn btn-success btn-add" data-toggle="tooltip" title ="Add More">+</button>
                                                          </span>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                        
                                      
                                         <div class="row form-group">

                                                <div class="col-lg-4 col-md-4">
                                                    <label style='height:27px;'>Comments</label>
                                                </div>
                                                <div class="col-lg-8 col-md-8">
                                                   
                                                   
                                                       


                                                          <textarea  name="comments" id="comments" placeholder="ENTER COMMENTS" title="Enter comments" class="form-control find_ledger" data-toggle="tooltip" data-placement="top" row="50" style=" text-transform: uppercase; padding: 2px;"></textarea>
                                                          <!--
                                                          <span class="input-group-btn">
                                                            <button id="add_ledger_button" type="button" onclick="subject_addnew4()" class="btn btn-success btn-add">+</button> -->
                                                          <!-- </span> -->
                                                  

                                                </div>
                                            </div>
                                            
                                       </div>
                                    </div>
                                    </div>
                        <div class='clear clear_both'>&nbsp;</div>
                        <div class="form-group trbg" style='min-height:40px; padding-top:10px'>
                            <div class="col-lg-6 col-md-6" style=' text-align:center; padding-right:10px;'>
                                <!-- <div id="error_message" class="ajax_response" style="float:left;text-align:center">sda</div></br>
                                <div id="success_message" class="ajax_response" style="float:left;text-align:center">asd</div>--> 
                            <input type="submit" class="btn btn-success" name="btn_submit" id="btn_submit" value="Submit" />
                              <!--<input type ="submit" class ="btn btn-success" name="submit" id="submit" value ="Submit" data-toggle="tooltip" title ="submit" />-->
                              <!--   <button type="reset" tabindex='3' class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="reset"><i class="fa fa-times"></i> Reset</button> -->
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

    <? include "../lib/app_footer.php"; ?>

   
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/plugins/jquery/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap.min.js"></script>
    <!-- END PLUGINS -->

    <!-- THIS PAGE PLUGINS -->
    <script type='text/javascript' src='../js/plugins/icheck/icheck.min.js'></script>
    <script type="text/javascript" src="../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
    <script type="text/javascript" src="../js/plugins/scrolltotop/scrolltopcontrol.js"></script>

    <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-file-input.js"></script>
    <script type="text/javascript" src="../js/plugins/bootstrap/bootstrap-select.js"></script>
    <script type="text/javascript" src="../js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
    <!-- END THIS PAGE PLUGINS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="../js/settings.js"></script>
    <script type="text/javascript" src="../js/plugins.js"></script>
    <script type="text/javascript" src="../js/actions.js"></script>
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="../ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="../css/default.css" type="text/css">
    <script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="../js/moment.js"></script>
    <? /* <script type="text/javascript" src="js/bootstrap-datetimepicker.js" charset="UTF-8"></script> */ ?>
    <script src="../js/monthpicker.min.js"></script>
    <script type="text/javascript" src="../js/jquery-migrate-3.0.0.min.js" charset="UTF-8"></script>

    <!-- Validate Form using Jquery -->
    <script src="../js/form-validation.js"></script>
    <script type="text/javascript" src="../js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="../js/core.js"></script>
    <script src="../js/jquery.filer.js" type="text/javascript"></script>
    <script src="../js/custom.js" type="text/javascript"></script>

    <script type="text/javascript" src="../js/jquery-customselect.js"></script>
    <script type="text/javascript" src="../js/angular-route.js"></script>
    <script type="text/javascript" src="../js/app.js"></script>
    <script type="text/javascript" src="../js/angular-route-segment.min.js"></script>
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


    

   
   

        function subject_addnew4() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#add_field_row').val();
        var id = (parseInt(value) + 1).toString();
        //var id = value +1;
        $('#add_field_row').val(id);
        /*
        if(document.getElementById('txt_ledger_name'+id)==ledgercode)
        {
            alert('Duplicate value');
        }
        */
        $('#add_fields').append(
              '<div class="form-group input-group">'+
                '<div style="width:100%">'+
                    '<div style="width:50%;float:left;">'+
                        ' <input type="text" name = "field_name[]" class="form-control" id="field_name'+id+'" placeholder= "NAME"  data-toggle ="tooltip" title ="ENTER NAME" required>'+
                    '</div>'+
                    '<div style="width:50%;float:right">'+
                        '<input type="text" name = "field_value[]" id= "field_value'+id+'"class="form-control" placeholder= "VALUE" data-toggle="tooltip" title ="values" required>'+
                    '</div>'+
                '</div>'+
                '<span class="input-group-btn"><button id="add_field_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip">-</button></span>'+
              '</div>');

                
                 $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });


        }
         function subject_addnew3() {

        $('[data-toggle="tooltip"]').tooltip();
        //var id = ($('#add_emp_txt_'+gridid+' .form-group').length + 1).toString();

        var value = $('#add_language_row').val();
        var id = (parseInt(value) + 1).toString();
        //var id = value +1;
        $('#add_language_row').val(id);
        /*
        if(document.getElementById('txt_ledger_name'+id)==ledgercode)
        {
            alert('Duplicate value');
        }
        */
        $('#add_language').append(
              '<div class="form-group input-group">'+
                '<div style="width:100%">'+
                    '<div style="width:50%;float:left;">'+
                        ' <input type="text" name = "lang_name[]" class="form-control" id="lang_name'+id+'" placeholder= LANGUAGE NAME"  data-toggle ="tooltip" title ="ENTER LANGUAGE NAME" required>'+
                    '</div>'+
                    '<div style="width:50%;float:right">'+
                        ' <input type="file" name="langfiles[]" id="lang_upload'+id+'" class="form-control"  style="padding-bottom:5px">'+
                    '</div>'+
                '</div>'+
                '<span class="input-group-btn"><button id="add_field_button" type="button" class="btn btn-danger btn-remove" data-toggle="tooltip">-</button></span>'+
              '</div>');

                 
                 $( document ).on( 'click', '.btn-remove', function ( event ) {
                event.preventDefault();
                $(this).closest( '.form-group' ).remove();
           });


        }
    
   </script>
    <!-- <script src="../js/picturefill.min.js"></script>
    <script src="../js/lightgallery.js"></script>
    <script src="../js/lg-fullscreen.js"></script>
    <script src="../js/lg-thumbnail.js"></script>
    <script src="../js/lg-video.js"></script>
    <script src="../js/lg-autoplay.js"></script>
    <script src="../js/lg-zoom.js"></script>
    <script src="../js/lg-hash.js"></script>
    <script src="../js/lg-pager.js"></script> -->
    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>
