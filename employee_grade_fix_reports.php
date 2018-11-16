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

$join=select_query_json("select hd.GRDFXNO, ef.EMPSRNO, ef.EMPCODE, ef.EMPNAME, hd.EMPGRAD, hd.EMPRMRK, to_char(hd.ADDDATE,'dd-MON-yyyy') HDADDDATE, 
                                    (select EMPCODE||' - '||EMPNAME from employee_office where empsrno = HD.emphdsr) HDUSER
                                from EMPLOYEE_GRADE_FIX HD, employee_office ef 
                                where HD.empsrno = ef.empsrno and HD.emphdsr='".$_SESSION['tcs_empsrno']."' and hd.deleted='N' 
                                order by HDADDDATE desc, ef.EMPSRNO", "Centra", "TEST");
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title>My Team Grade Fix Report :: <?php echo $site_title; ?></title>
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
                <li class="active">Employee Grade Fix</li>
            </ul>
            <!-- END BREADCRUMB -->

            <!-- PAGE CONTENT WRAPPER -->								
            <div class="page-content-wrap">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">MY TEAM EMPLOYEES GRADE REPORT</h3>

                        <div class="pull-right">
                            <a class="btn btn-danger" target="_blank" style="margin-right: 5px;" href="employee_add.php">(+) Add Employees</a>&nbsp;&nbsp;<a class="btn btn-danger" target="_blank" style="margin-right: 50px;" href="employee_grade_fix.php">Grade Fix</a>
                        </div>
                    </div>

						 <div class="panel-body">
                        
                        <table id="customers" class="table datatable" border="1">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">EMPLOYEE</th>
                                    <th style="text-align: center;">DATE</th>
                                    <th style="text-align: center;">GRADE FIX</th>
                                    <th style="text-align: center;">REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                            <? for($search_i = 0; $search_i < count($join); $search_i++) { ?>
                            <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                <td class="center" style="text-align: center;"><?=($search_i+1)?></td>
                                <td class="center"><?=$join[$search_i]['EMPCODE']?> - <?=$join[$search_i]['EMPNAME']?></td>
                                <td class="center" style="text-align: center;"><?=$join[$search_i]['HDADDDATE'];?></td>
                                <td class="center" style="text-align: center;"><?=$join[$search_i]['EMPGRAD'];?></td>
                                <td class="center" style="text-align: center; text-transform: uppercase;"><?=$join[$search_i]['EMPRMRK'];?></td>
                                </td>
                            </tr>
                            <? } ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        			
                                       
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
    <!-- END THIS PAGE PLUGINS-->

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

    function task_assign(empsrno, iv) {
        $('#load_page').show();
        var grade = $("#txt_employee_grade_"+iv).val();
        switch(grade) {
            case 'A+':
                    grade = 1; break;
            case 'A':
                    grade = 2; break;
            case 'B':
                    grade = 3; break;
            default :
                    grade = 4; break;
        }
        var strURL="ajax/fix_grade.php?action=fix_grade&empsrno="+empsrno+"&grade="+grade;
        if(grade != '') {
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    if(data1 == 0) {

                    }
                }
            });
        }
        $('#load_page').hide();
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
            $('#load_page').hide();

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
