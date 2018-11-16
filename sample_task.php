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
<?php 
}
if($_SESSION['tcs_empsrno'] !== ""){
$join=select_query_json("select tg.TASSRNO,to_char(tg.TASKDATE,'ddMONyyyy')ADDDATE,tg.EMPSRNO,tg.TASKDET,tg.ENTTIME,tg.OUTTIME,ef.empname from TASK_GRADE  tg, employee_office  ef where tg.empsrno=ef.empsrno and ef.empsrno='".$_SESSION['tcs_empsrno']."' order by tg.TASKDATE", "Centra", "TEST");
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

        <script type="text/javascript" src="js/jquery.js"></script>
		<script src="js/plugins/dataTables/jquery.dataTables.js"></script>
         <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
		<script type="text/javascript">
       $(document).ready(function() {
      $('#dataTables-example').dataTable();
});
	function nsubmit(action){
		alert("uable to call");
			var vurl = "task_details.php";
        //var vurl="viki/post_test.php";

			$.ajax({
            type: "POST",
            url: vurl,
			data:{
				txt_task_details:$("#txt_task_details").val(),
				txt_task_from_time:$("#txt_task_from_time").val(),
				txt_task_to_time:$("#txt_task_to_time").val(),
                tassrno: $('#tassrno').val(),
                sbmt_update:action
				//'txt_employee_grade':$("#txt_employee_grade").val(),
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
function update_detail(){
 		var vurl = "task_details.php?sbmt_update=update";
	
 		$.ajax({
             type: "POST",
             url: vurl,
 			data:{

 				'txt_task_details':$("#txt_task_details").val(),
				'txt_task_from_time':$("#txt_task_from_time").val(),
				'txt_task_to_time':$("#txt_task_to_time").val(),
				
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

	
</script>

   

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
			<? if( $_REQUEST['action'] == 'nedit' )  {

$sql_reqid = select_query_json("select taskdet,enttime,outtime,empsrno from task_grade where tassrno = '".$_REQUEST['reqid']."'","Centra","TEST");} ?>  
            <form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" method="post" enctype="multipart/form-data">
			<!--<form class="form-horizontal" role="form" id="frm_requirement_entry" name="frm_requirement_entry" action="" method="post" enctype="multipart/form-data">    -->
					<div class="page-content-wrap">
                        <input type="hidden" id="tassrno" val=""/>

						<div class="row">
							<div class="col-md-12">

								<form class="form-horizontal">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><strong>DAILY WORKDONE</strong></h3>
										<ul class="panel-controls">
											<li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
										</ul>
									</div>


									<div class="panel-body">


										<div class="row">
											
												
										<!--		<div class="col-md-6">
												<div class="form-group">
													<label class="col-md-3 control-label">EMPLOYEE CODE<span style='color:red'></span></label>
													<div class="col-md-9 col-xs-12">

                                                    <input type="text" style=" text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="txt_employee_code" id="txt_employee_code" autocomplete="off"  maxlength="5" tabindex="5" placeholder="ENTER THE EMPLOYEE CODE" >
                                                       
																</div>
												</div>
												<!-- tilte text feild -->
												<div class="form-group">
                                                    <label class="col-md-3 control-label">TASK DETAILS<span style='color:red'></span></label>
                                                    <div class="col-md-6 col-xs-8">
                                                      
                                                    <input type="text" style=" text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="txt_task_details" id="txt_task_details" autocomplete="off"  maxlength="100" tabindex="5" value='' data-toggle="tooltip" data-placement="top" placeholder="ENTER THE DETAILS" title=""  >
                                                       
                                                    </div>
                                                </div>
												<div class="form-group">
                                                    <label class="col-md-3 control-label">ENTER TIME<span style='color:red'></span></label>
                                                    <div class="col-md-2 col-xs-4">
                                                       
                                                    <input type="time" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="txt_task_from_time" id="txt_task_from_time" autocomplete="off"  maxlength="11" tabindex="5" value='' data-toggle="tooltip" data-placement="top"  title=""  >
                                                        
                                                    
                                                </div>
												
												<div class="form-group">
                                                    <label class="col-md-2 control-label">OUTER TIME<span style='color:red'></span></label>
                                                    <div class="col-md-2 col-xs-4">
                                                       
                                                    <input type="time" style="cursor: pointer; text-transform: uppercase; position: relative; top: auto; right: auto; bottom: auto; left: auto;" class="form-control" name="txt_task_to_time" id="txt_task_to_time" autocomplete="off"  maxlength="11" tabindex="5" value='' data-toggle="tooltip" data-placement="top" title=""  >
                                                        
                                                    </div>
                                                </div>
												<div class="tags_clear"></div>
										<div class="tags_clear"></div>
<!--<div class="panel-footer">

										<button class="btn btn-success pull-right" onclick="nsubmit()">Submit</button>
									</div>
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>!-->
								<div class="form-group trbg" style='min-height:40px; padding-top:10px'>
					<div class="col-lg-12 col-md-12" style=' text-align:center; padding-right:10px;'>
				
                          
						          <input type='hidden' name='hid_reqid' id='hid_reqid' value='<?=$_REQUEST['reqid']?>'>
                                        <!-- <button name='sbmt_update' onclick='update_detail()' id='sbmt_update'tabindex='2' value='SB' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Update"> -->
                                            <input type="button" id="update_btn" class="btn btn-primary" name="" onclick="nsubmit('update');" style="display: none;" value="update">&nbsp;
                                            <!-- <i class="fa fa-save"></i> Update</button>&nbsp;&nbsp; -->
                                            <!-- <button  tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button> -->

                        
                                <!-- <button name='sbmt_request' id='sbmt_request' tabindex='2' value='submit' onclick='nsubmit()' class="btn btn-default" data-toggle="tooltip" data-placement="top" onclick="return checkform()" title="Submit"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp; -->
                                                            <input type="button" id="submit_btn" class="btn btn-primary" name="" onclick="nsubmit('save');" value="submit">&nbsp;
                                <button  tabindex='3' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-times"></i> Reset</button>

                           
				
								</form><br><br>
								
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">WORK DONE REPORT</h3></div>
						 <div class="panel-body">
                        <table id="customers" class="table datatable" border="1">
                            <thead>
                                <tr>
                                    <? /* <th>Priority</th> */ ?>
                                    <th>SERIAL NO</th>
									<th>EMPLOYEE SERIAL NO</th>
									<th>EMPLOYEE NAME</th>
									<th>TASK DETAIL</th>
									<th>TASK DATE</th>
									<th>ENTER TIME</th>
									<th>OUTER TIME</th>
									<th>ACTION</th>
<!--<th>SET GRADE</th>!-->
                               
		
									<!--<th>GRADE</th>!-->
                                </tr>
                            </thead>
                           <tbody>
							<?
							
              

                           for($search_i = 0; $search_i < count($join); $search_i++) { 
					?>
							
<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                              
                                <td class="center" ><? echo $join[$search_i]['TASSRNO'];?></td>
								<td class="center"><? echo $join[$search_i]['EMPSRNO'];?></td>
								<td class="center"><? echo $join[$search_i]['EMPNAME'];?></td>
								<td class="center"><? echo $join[$search_i]['TASKDET'];?></td>
							    <td class="center"><? echo $join[$search_i]['ADDDATE'];?></td>
		                        <td class="center"><? echo $join[$search_i]['ENTTIME'];?></td>
								<td class="center"><? echo $join[$search_i]['OUTTIME'];?></td>
							<!--	<td class="center"><? echo $sql_search1[$search_i]['TASGRADE'];?></td>!-->
						<!--	<td><select id="txt_employee_grade"><option>A+</option>
								<option>A</option>
								<option>B</option>
								<option>C</option>
								</select></td>!-->
								
<td>
                                    <input type="button" class="btn btn-primary" name="edit" onclick="nedit('<? echo $join[$search_i]['TASSRNO'];?>','<? echo $join[$search_i]['TASKDET'];?>','<? echo $join[$search_i]['ENTTIME'];?>','<? echo $join[$search_i]['OUTTIME'];?>');" value="edit"/>
                                </td>


								
								<? } ?>	
                            </tr>
                           </tbody>
                        </table>
                    </div>
                </div>
            </div><br><br>
        			
                                       
    <? include "lib/app_footer.php"; ?>

    <!-- START PRELOADS -->
    
    <!-- END PRELOADS -->

    <!-- START TEMPLATE -->
    <script type="text/javascript" src="js/settings.js"></script>
    <script type="text/javascript" src="js/plugins.js"></script>
    <script type="text/javascript" src="js/actions.js"></script>
    <!-- END TEMPLATE -->

    <!-- Custom Scripts - Arun Rama Balan.G -->
    <script src="ajax/ajax_staff_change.js"></script>
    <link rel="stylesheet" href="css/default.css" type="text/css">
    
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
	function nedit(tassrno,taskdet,endtim,outtim)
        {   
            $('#submit_btn').hide();
            $('#update_btn').show();
            console.log(taskdet+" "+endtim+" "+outtim);
            $('#txt_task_details').val(taskdet);
            $('#tassrno').val(tassrno);
            $('#txt_task_from_time').val(endtim);
            $('#txt_task_to_time').val(outtim);
        }

	
	
/*$(function() {
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
        });*/
		 

        $(document).ready(function() {
            $('#customers2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customers2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
        });

    function cmnt_mail(aprnumb)
    {
        var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
		//var sendurl="viki/post_test.php";
        $.ajax({
        url:sendurl,
        success:function(data){
            $("#myModal2").modal('show');
            $('#modal-body2').html(data);
            $('#txtmailcnt').val("");
            }
        });
    }
 $(document).ready(function() {
            $('#customers2').dataTable({
                "columnDefs": [
                    { "visible": false, "targets": 0 }
                ],
                "order": [[ 0, 'asc' ]],
                "language": {
                    "zeroRecords": "No results available"
                },
                "displayLength": 25,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="10">'+group+'</td></tr>' // 05052017
                            );

                            last = group;
                        }
                    } );

                    api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                            );

                            last = group;
                        }
                    } );
                }
            });

            // Order by the grouping
            $('#customers2 tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
                    table.order( [ 0, 'desc' ] ).draw();
                }
                else {
                    table.order( [ 0, 'asc' ] ).draw();
                }
            });
        });

   
    </script>
<!-- END SCRIPTS -->
</body>
</html>

    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>
