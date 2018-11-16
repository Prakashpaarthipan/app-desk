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

$sql_rdsrno =  select_query_json("select count(*) cnt from APPROVAL_REQUEST where aprnumb like '".$sql_reqid[0]['APRNUMB']."' order by ARQSRNO", "Centra", 'TEST');
if($sql_rdsrno[0]['CNT'] > 1 && $action == 'edit') { ?>
    <script>alert('Already This request went for Approval / You dont have rights to edit this page.'); window.location="request_list.php";</script>
<? exit();
}

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
    <title><?=$title_tag?> Employee Notice Entry :: Approval Desk :: <?php echo $site_title; ?></title>
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
    <style>
    
/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
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
                <li class="active">Employee Notice Entry</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="viki/post_test.php" method="post" enctype="multipart/form-data">
            <!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
                    <div class="page-content-wrap">

                        <div class="row">
                            <div class="col-md-12">

                                <form class="form-horizontal">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><strong>Employee Notice Entry</strong></h3>
                                        <ul class="panel-controls">
                                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        </ul>
                                    </div>


                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- topcore drop down ValidateSingleInput(this, 'all'); -->
                                                <?//echo("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode='".$_SESSION['tcs_usrcode']."'");?>
                                               <div class="form-group">
                                                    <div class="col-md-3 control-label"><label>BRANCH <span style='color:red'>*</span></label></div>
                                                    <div class="col-md-9"  >
                                                        <select class="form-control custom-select chosn" autofocus tabindex='1' required name='branch' id='branch' onChange="clearme();" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                        <!-- <option value="0" >CHOOSE BRANCH</option> -->
                                                        <?  //$sql_project = select_query_json("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode=1595888", "Centra", 'TCS');
                                                        $sql_project = select_query_json("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode='".$_SESSION['tcs_usrcode']."'", "Centra", 'TCS');
                                                        echo("select usrcode,useracc,allbran,brncode,MULTIBRN from tcs_centra_attn where  usrcode='".$_SESSION['tcs_usrcode']."'");
                                                            //print_r($sql_project);
                                                                if($sql_project[0]['ALLBRAN']=='N')
                                                                {
                                                                     $sql_brn = select_query_json("Select Brncode,Substr(Nicname,3,10) Brnname From Branch Where brncode='".$sql_project[0]['BRNCODE']."' ORDER BY BRNCODE", "Centra", 'TCS');
                                                                    
                                                                     //print_r($sql_brn);
                                                                }
                                                                else
                                                                { $sql_brn = select_query_json("Select Brncode,Substr(Nicname,3,10) Brnname From Branch Where brncode IN (".$sql_project[0]['MULTIBRN'].") ORDER BY BRNCODE", "Centra", 'TCS');
                                                                //print_r($sql_brn);
                                                                
                                                                }
                                                            for($project_i = 0; $project_i < count($sql_brn); $project_i++) { ?>
                                                            <option value='<?=$sql_brn[$project_i]['BRNCODE']?>'><?=$sql_brn[$project_i]['BRNNAME']?></option>
                                                        <? } ?>
                                                    </select>

                                                    </div>
                                                </div>
                                                <div style="clear: both;"></div>
                                                <!-- priority filed drop down -->

                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">EMPLOYEE <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <b>
                                                          <input type='text' class="form-control" tabindex='2' required style="text-transform: uppercase;" name='employee' id='employee' data-toggle="tooltip" onblur="" onchange="getprofile_img()" data-placement="top" data-original-title="Assign member" value='<?=$sql_emp[0]['EMPCODE']." - ".$sql_emp[0]['EMPNAME']." - ".substr($sql_emp[0]['ESENAME'], 3)?>'>

                                                    </div>
                                                </div>
                                                <!-- tilte text feild -->
                                                <div style="clear: both;"></div>
                                                
                                                <div class="form-group">
                                                        <label class="col-md-3 control-label" id='remarks'>COMMENTS <span style='color:red'>*</span></label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <div class="">
                                                            <textarea required id="message" maxlength=250 name="message" type="text" tabindex="3" class="form-control" style="text-transform:uppercase; height:75px; padding-right: 5px;" multiple placeholder="YOUR COMMENTS.." required></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div style="clear: both;"></div>


                                               <div class="form-group">
                                                        <label class="col-md-3 control-label" id='remarks'>AUTHORIZED BY</label>
                                                    <div class="col-md-8 col-xs-12">
                                                         <select class="form-control custom-select chosn" autofocus tabindex='4' required name='auth_by' id='auth_by' onChange="" data-toggle="tooltip" data-placement="top" data-original-title="Top Core" >
                                                        <!-- <option value="choose AUTHORIZER" >CHOOSE THE AUTHORIZER</option> -->
                                                        <option value='20118'>MD</option>
                                                        <option value='43400'>PS MADAM</option>
                                                        <option value='21344'>SK SIR</option>
                                                        <option value='452'>ADMIN GM</option>
                                                    </select>
                                                    
                                                    </div>
                                                     <div class="col-md-1 col-xs-12">
                                                             <div class="checkbox">
                                                              <label><input type="checkbox" value="1" tabindex='5' id="all" name="all"/> All</label>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="tags_clear"></div>
                                                
                                            </div>



<!--fix me-->
                                            <div class="col-md-6">
                                                <!-- core drop down -->
                                                
                                                <!--assign member-->

                                                <div class="form-group">
                                                    <div class="col-md-3 control-label"><label id='attachment'>NOTICE</label></div>
                                                    <div class="col-md-9 col-xs-12">
                                                        <div class="">
                                                            <input type="text" disabled class="form-control" id="notice" tabindex='6' name="notice" style="text-transform: uppercase;" maxlength="100" required />
                                                        </div>
                                                    </div>
                                                    <div style="clear: both;"></div>

                                                    <div class="col-md-3 control-label"></div>
                                                    <div class="col-md-9 col-xs-12">
                                                        <div id="profile_img" style="padding-top: 10px;"></div>
                                                        <!-- <img id="profile_img"  alt="Cinque Terre" src="" style="height:70px; width:70px; border: 1px solid #A0A0A0;" title=" " /> -->
                                                    </div>
                                                    <div class="tags_clear"></div>
                                                </div>


                                                <!-- Tag layout -->
                                                
                                        </div>
                                
                                        <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6">
                                                <!-- topcore drop down ValidateSingleInput(this, 'all'); --->
                                        
                                        </div>
                                        

                                         
                                                            
                                    
                                        
                                    
                                    <div class="tags_clear"></div>
                                </div>
                                

                                <div class="panel-footer">

                                        <button class="btn btn-success pull-right" onclick="nsubmit();" tabindex='7' type="button">Submit</button>
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
    <script type="text/javascript" src="js/bootbox.js"></script>
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
//////////



                  
        
        function alert1(msg){
           bootbox.alert({
                            title: " Warning !",
                            message: msg,
                            buttons: {
                                ok: {
                                    label: '<i class="fa fa-times"></i> OK'
                                }
                            },
                            callback: function (result) {
                                //console.log(result);
                            }
                        });
            
        }

        function alert2(msg){
           bootbox.confirm({
                            title: "Confirm",
                            message: msg,
                            buttons: {
                                cancel: {
                                    label: '<i class="fa fa-times"></i> No'
                                },
                                confirm: {
                                    label: '<i class="fa fa-check"></i> Yes'
                                }
                            },
                            callback: function (result) {
                                console.log(result);
                                if(result)
                                {   //console.log(1);
                                    return 1;
                                }
                                else{
                                    //console.log(2);
                                    return 2;
                                }
                                
                            }
                        });
            
        }



    ////
	var gerror=0;
   function clearme()
   {
	   $('#employee').val('');
	   $('#profile_img').html('');
	   $('#notice').val('');
   }

    $('#tar_date').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
      format: 'd-M-Y'
    });
    $('#due_date').Zebra_DatePicker({
      direction: ['<?=strtoupper(date("d-M-Y", strtotime("+14 days")))?>', false],
      format: 'd-M-Y'
    });


    $(document).ready(function() {
        $(".chosn").customselect();
        $("#load_page").fadeOut("slow");
        
        //getcore();

    $('#employee').autocomplete({
        source: function( request, response ) {
            $.ajax({
                url : 'ajax/ajax_employee_details.php',
                dataType: "json",
                data: {
                   name_startsWith: request.term,
                   branch: $('#branch').val(),
                   type: 'branch_employee'
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
    function print1(){
         alert1('printing');
        window.print();
        //var dataurl="print_page.php?notyear=2018&notnumb=2";
        //var popupWin = window.open(dataurl, '_blank', 'width=1000, height=700');
        
    }

    function getcore(){
        //alert("uable to call");
        var vurl = "viki/getcoredrop.php";
        //var vurl = "viki/insert_req.php"; // the script where you handle the form input.
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
            {       alert1(error);
                    //alert(response);
                    //alert(status);
            }
            });
    }
    

      span.onclick = function() {
            modal.style.display = "none";
              $("#modal_data").html(' ');
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                $("#modal_data").html(' ');
            }
        }

    function getprofile_img(){
		
 
        // alert("hi");
		var branch= $('#branch').val();
        var ececode=$('#employee').val();
        // alert(ececode);
        ececode=ececode.split(' - ');
        //alert(ececode[1]);
		//if(!isNaN(ececode[1]))
		if (typeof ececode[1]  !== "undefined")
		{
		
             $('#profile_img').html('<img src="profile_img.php?branch='+branch+'&action=user_profile_img&profile_img='+ececode[0]+'" style="width:200px; height:200px; border:1px solid #a0a0a0; text-align:center; border:0px;" />');
			 
		
			var vurl = "viki/notice_entry.php";
			//var vurl = "viki/insert_req.php"; // the script where you handle the form input.
			//alert($("#attachments").files);
			var ececode=$('#employee').val();
			// alert(ececode);
			ececode=ececode.split(' - ');
			$.ajax({
				type: "POST",
				url: vurl,
				data:{
					'empsrno':ececode[0],
					'action':'alert_txt'
				},
				dataType:'html',
				success: function(data1) {
				   $('#notice').val(data1);
				   //alert(data1);
				},
				error: function(response, status, error)
				{       alert1(error);
						//alert(response);
						//alert(status);
				}
			});
		
        }
        else{
           alert1("Invalid User");
		   $('#employee').val('');
        }

        //alert(" call");
		//alert($('#profile_img').val());
		
        
    }

    function nsubmit(){
        //alert("uable to call");
        $('#load_page').fadeIn('slow');
            var vurl = "viki/notice_entry.php";
        //var vurl = "viki/insert_req.php"; // the script where you handle the form input.
        //alert($("#attachments").files);
        var msg=$("#message").val();
        var emp=$("#employee").val();
        if(msg.trim()!='' && emp.trim()!='')
        {
          $.ajax({
            type: "POST",
            url: vurl,
            data:{
                'branch':$("#branch").val(),
                'employee':$("#employee").val(),
                'message':$("#message").val(),
                'auth_by':$("#auth_by").val(),
                'all':$("#all").val(),
                'action':'insert'
            },
            dataType:'html',
            success: function(data1) {
               //alert(data1);
               //print_page();
               var data=data1.split(',')
               //alert(data);
               var opt;
				var authby=$("#auth_by").val();
               if($("#all").prop('checked')==true)
               {opt=4;
               }
               else
               {
                opt=$("#auth_by").val();
               }
                var dataurl="print_page.php?notyear="+data[0]+"&notnumb="+data[1]+"&all="+opt+"&authby="+authby;
                //alert(dataurl);
                var popupWin = window.open(dataurl, '_blank', 'width=1024, height=700');
                //$("#load_page").fadeOut('fast');
                location.reload();
            },
            error: function(response, status, error)
            {       alert1(error);
                    //alert(response);
                    //alert(status);
            }
            }); 
        }
        else{
            alert1("Remarks or Employee name required");
        }
        
    }

    function reload_page() {
        $('#load_page').show();
        location.reload();
        $('#load_page').show();
    }
    </script>
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
    </script>
    <!-- Light Box -->

    <!-- Light Box - New -->
    <link href="css/lightgallery.css" rel="stylesheet">
    <script type="text/javascript">
    $(document).ready(function(){
        $('.lightgallery').lightGallery();
    });
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