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

/* $menu_name = 'EMPLOYEE GRADE FIX';
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
} */

// Select & display the Current Week Sunday to Saturday
$sql_week = select_query_json("SELECT dt FROM (SELECT TRUNC(sysdate, 'DAY') + level - 1 dt FROM dual CONNECT BY level <= (TRUNC(sysdate) - TRUNC(sysdate, 'DAY'))+1)");
// Select & display the Current Week Sunday to Saturday
// $and = " and trunc(ADDDATE) between trunc(sysdate) and ";

$join=select_query_json("select ef.EMPCODE, ef.empname, ef.EMPSRNO 
                                from EMPLOYEE_HEAD_USER HD, employee_office ef 
                                where HD.empsrno = ef.empsrno and HD.emphdsr='".$_SESSION['tcs_empsrno']."' and deleted='N'
                                order by EMPSRNO", "Centra", "TCS");
?>
<!DOCTYPE html>
<html lang="en" ng-app="myApp">
<head>
    <!-- META SECTION -->
    <title>My Team Grade Fix :: <?php echo $site_title; ?></title>
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
    .label { font-size: 12px; }
    .label-info, .badge-info { background-color: #0089ff; }
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
                        <h3 class="panel-title">MY TEAM EMPLOYEES GRADE FIX</h3>



                        <div class="pull-right">
                            <?  $menu_name = 'EMPLOYEE GRADE MASTER';
                                $inner_submenu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc", "Centra", 'TCS');
                                if($_SESSION['tcs_empsrno'] != '') {
                                    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' 
                                                                                    order by MNUCODE Asc", "Centra", 'TCS');
                                } else {
                                    $inner_menuaccess = select_query_json("select * from srm_menu_access
                                                                                    where MNUCODE = ".$inner_submenu[0]['MNUCODE']." and SUPCODE = '".$_SESSION['tcs_userid']."' 
                                                                                    order by MNUCODE Asc", "Centra", 'TCS');
                                }
                                if($inner_menuaccess[0]['VEWVALU'] == 'Y') { ?><a class="btn btn-danger" target="_blank" style="margin-right: 5px;" href="employee_add.php">(+) Add Employees</a>&nbsp;&nbsp;<? } ?><a class="btn btn-danger" target="_blank" style="margin-right: 50px;" href="employee_grade_fix_reports.php">Grade Fix Report</a>
                        </div>
                    </div>


                    <div class="form-group trbg non-printable">
                        <form role="form" id='frm_request_entry' name='frm_request_entry' action='' method='post' enctype="multipart/form-data">
                            <? /* <div class="col-xs-3" style='text-align:center; padding:5px;'></div>
                            <div class="col-xs-2" style='text-align:center; padding:8px 5px 0 6px;'>
                                <input type='text' style="cursor:pointer; text-transform:uppercase" type="text" class="form-control" name="search_fromdate" id="datepicker-example3" autocomplete="off" readonly maxlength="11" tabindex='5' value='<? if($_REQUEST['search_fromdate'] != '') { echo $_REQUEST['search_fromdate']; } else { echo date("d-M-Y"); } ?>' data-toggle="tooltip" data-placement="top" placeholder='From Date' title="From Date">
                            </div>

                            <div class="col-xs-1" style='text-align:left; padding:5px;'>
                                <input type='submit' name='search_frm' id='search_frm' tabindex='7' class='btn btn-success' style='padding:6px 12px !important' value='Search' title='Search' >
                            </div> */ ?>

                                                        <div class="col-xs-12" style='text-align:center; padding:5px;'>
                                <div style="line-height: 35px; text-align: center;"><small class="label label-info">A++ = Ultimate</small> <small class="label label-success">A+ = Excellent</small> <small class="label label-primary">A = Good</small> <small class="label label-warning">B = Normal</small> <small class="label label-danger">C = Need Attention</small></div><div style="clear: both;"></div>
                            </div>
                        </form>
                    </div>
                    <div class="non-printable" style='clear:both; border-bottom:1px solid #ADADAD; margin-bottom:10px; margin-top: 10px;'></div>


                    

                     <div class="panel-body">
                    <table id="customers" class="table datatable" border="1">
                        <thead>
                            <tr>
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">NAME</th>
								<?php foreach ($sql_week as $key => $week_value) {
                            $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT']))); ?><th style="text-align: center;"><? echo $dt_value;?>
								<? }?>
                        
						</th>
						                              <th style="text-align: center;">REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <? $cnt_join = count($join); $search_ii = 0;
                        foreach ($sql_week as $key => $week_value) {
                            $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT']))); 
                            for($search_i = 0; $search_i < $cnt_join; $search_i++) { $search_ii++;?>
                            <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                                <td class="center" style="text-align: center;"><?=($search_ii)?></td>

								<td class="center"><? echo $join[$search_i]['EMPCODE'];?> - <? echo $join[$search_i]['EMPNAME'];?></td>
																<?php foreach ($sql_week as $key => $week_value) {
                            $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT']))); ?>
								<td class="center" style="text-align: center;">
                                    <select id="txt_employee_grade_<?=$search_i;?>" name='txt_employee_grade_<?=$search_i;?>' class="txt_employee_grade" onchange="task_assign('<?=$join[$search_i]['EMPSRNO']?>', '<?=$search_i?>', '<?=$dt_value?>');" style="min-height: 25px;">
                                        <?  // $and = " and trunc(ADDDATE) = trunc(sysdate) ";
                                            // if($search_fromdate) { $and = " and trunc(ADDDATE) = TO_DATE('".$search_fromdate."', 'DD-MON-YY') "; }

                                        $and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";
                                        $sql_empgrade = select_query_json("select EMPGRAD, EMPRMRK from EMPLOYEE_GRADE_FIX 
                                                                                    where deleted = 'N' and EMPSRNO = '".$join[$search_i]['EMPSRNO']."' ".$and."", 'Centra', 'TCS'); ?>
                                        <option value='' selected>---CHOOSE---</option>
                                        <option value='A++' <? if($sql_empgrade[0]['EMPGRAD'] == 'A++') { ?> selected <? } ?>>A++</option>
                                        <option value='A+'  <? if($sql_empgrade[0]['EMPGRAD'] == 'A+') { ?> selected <? } ?>>A+</option>
                                        <option value='A'   <? if($sql_empgrade[0]['EMPGRAD'] == 'A')  { ?> selected <? } ?>>A</option>
                                        <option value='B'   <? if($sql_empgrade[0]['EMPGRAD'] == 'B')  { ?> selected <? } ?>>B</option>
                                        <option value='C'   <? if($sql_empgrade[0]['EMPGRAD'] == 'C')  { ?> selected <? } ?>>C</option>
                                    </select>
								                               </td>
                                
<? } ?>
 
                                
                                
                                <td class="center"><input type="text" name="txt_empgrade_remarks_<?=$search_i?>" id='txt_empgrade_remarks_<?=$search_i?>' maxlength="100" class="form-control" placeholder="Employees Remarks" title="Employee Remarks" value="<?=$sql_empgrade[0]['EMPRMRK']?>" onblur="task_assign('<?=$join[$search_i]['EMPSRNO']?>', '<?=$search_i?>', '<?=$dt_value?>');" style="width: 100%; text-transform: uppercase; border: 1xp solid #000"></td>
                            </tr>
                        <? } } ?> 
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
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script type="text/javascript">
    $('#datepicker-example3').Zebra_DatePicker({
        direction: false, // 1,
        format: 'd-M-Y'
    });

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

    function task_assign(empsrno, iv, fromdate) {
        $('#load_page').show();
        var grade = $("#txt_employee_grade_"+iv).val();
        // var fromdate = $("#datepicker-example3").val();
        var txt_empgrade_remarks = $("#txt_empgrade_remarks_"+iv).val();
        switch(grade) {
            case 'A++':
                    grade = 5; break;
            case 'A+':
                    grade = 1; break;
            case 'A':
                    grade = 2; break;
            case 'B':
                    grade = 3; break;
            default :
                    grade = 4; break;
        }
        var strURL="ajax/fix_grade_test.php?action=fix_grade&empsrno="+empsrno+"&grade="+grade+"&txt_empgrade_remarks="+txt_empgrade_remarks+"&fromdate="+fromdate;
        /* if(txt_empgrade_remarks == '') {
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Kindly fill the Grade Remarks for save the rating";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            $('#load_page').hide();
        } */

        if(grade != '') {
            $.ajax({
                type: "POST",
                url: strURL,
                success: function(data1) {
                    if(data1 == 0) {

                    }
                    $('#load_page').hide();
                }
            });
            $('#load_page').hide();
        }
        
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
<!-- END SCRIPTS -->
</body>
</html>

    <!-- Light Box - New -->
    <!-- Custom Scripts - Arun Rama Balan.G -->
<!-- END SCRIPTS -->
</body>
</html>
