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


$join=select_query_json("select tg.TASSRNO,to_char(tg.TASKDATE,'ddMONyyyy')ADDDATE,tg.EMPSRNO,tg.TASKDET,tg.ENTTIME,tg.OUTTIME,ef.empname from task_grade tg, employee_office  ef where tg.empsrno=ef.empsrno and tg.empsrno<>43878 order by tg.TASKDATE", "Centra", "TEST");
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
</style>

	
</head>
<body>
        <script type="text/javascript" src="js/jquery.js"></script>
			
<body>
<p id="demo">
   <div class="page-content-wrap">
                <div class="panel panel-default">
                    
						 <div class="panel-body">
                        <table id="customers" class="table datatable" border="1">
                            <thead>
                                <tr>
                                  
                                    <th>SERIAL NO</th>
									<th>EMPLOYEE SERIAL NO</th>
									<th>EMPLOYEE NAME</th>
									<th>TASK DETAIL</th>
									<th>TASK DATE</th>
									<th>ENTER TIME</th>
									<th>OUTER TIME</th>
									<th>SET GRADE</th>
                                </tr>
                            </thead>
                            <tbody>
							<?
							
						

                                for($search_i = 0; $search_i < count($join); $search_i++) { 
								?>
							
<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                             
								<td class="center"><? echo $join[$search_i]['TASSRNO'];?></td>
								<td class="center"><span id="txt_employee_code"><? echo $join[$search_i]['EMPSRNO'];?></span></td>
								<td class="center"><? echo $join[$search_i]['EMPNAME'];?></td>
								<td class="center"><span id="txt_task_details"><? echo $join[$search_i]['TASKDET'];?></span></td>
							    <td class="center"><span id="txt_task_date"><? echo $join[$search_i]['ADDDATE'];?></span></td>
		                        <td class="center"><span id="txt_task_from_time"><? echo $join[$search_i]['ENTTIME'];?></span></td>
								<td class="center"><span id="txt_task_to_time"><? echo $join[$search_i]['OUTTIME'];?></span></td>
								<td class="center"><select id="txt_employee_grade<?=$search_i;?>" class="txt_employee_grade" onchange="task_assign('<? echo $join[$search_i]['TASSRNO'];?>','<?=$search_i;?>');">
								<option>---CHOOSE---</option>
                                <option>A+</option>
								<option>A</option>
								<option>B</option>
								<option>C</option>
								</select></td>
<!-- task_assign('<? echo $join[$search_i]['TASSRNO'];?>','<?=$search_i;?>'); -->
								

								
								<? } ?>	
                            </tr>
							
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

						               
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
      
    function task_assign(tassrno,row) {
                alert("uable to call");
                    var vurl = "grade_set.php";
                    $.ajax({
                        type: "POST",
                        url: vurl,
                        data:{
                            tassrno:tassrno,
							txt_task_details:$("#txt_task_details").html(),
				txt_task_from_time:$("#txt_task_from_time").html(),
				txt_task_to_time:$("#txt_task_to_time").html(),
                
                            txt_employee_grade:$('#txt_employee_grade'+row).val()
                        },
                            
                        dataType:'html',
                        success: function(data1) {
                           alert(data1);
                        },
                        error: function(response, status, error)
                        {       alert(error);
                                //alert(response);
                                //alert(status);
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

    
    </body>
</html>
