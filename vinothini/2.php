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


$join=select_query_json("select * from task_gradee  tg, employee_office  ef where tg.EMPCODE=ef.EMPCODE and tg.EMPCODE<>14442 order by tg.OUTTIME", "Centra", "TEST");


//$sql_search=select_query_json("select EMPCODE, TASKDET, TASKDATE, ENTTIME, OUTTIME from TASK_GRADEE where EMPCODE<>14442", "Centra", "TEST");
//$sql_search1=select_query_json("select TASGRADE from TASK_GRADE11 where EMPCODE=14442", "Centra", "TEST");
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
			<script type="text/javascript">

	function msubmit(){
		alert("uable to call");
//var vurl = "SAVE_TASKK.php";
			var vurl = "connection11.php";
			$.ajax({
type: "POST",
            url: vurl,
			data:{
                'txt_employee_code':$("#txt_employee_code").html(),
				'txt_task_details':$("#txt_task_details").html(),
				'txt_task_from_time':$("#txt_task_from_time").html(),
				'txt_task_to_time':$("#txt_task_to_time").html(),
       			'txt_employee_grade':$("#txt_employee_grade").val(),
				
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

	
</script>
<body>
   <div class="page-content-wrap">
                <div class="panel panel-default">
                    
						 <div class="panel-body">
                        <table id="suppliers" class="table datatable" border="1">
                            <thead>
                                <tr>
                                    <? /* <th>Priority</th> */ ?>
                                    <th>SERIAL NO</th>
									<th>EMPLOYEE CODE</th>
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
							
							$empi = 0;

                                for($search_i = 0; $search_i < count($join); $search_i++) { $empi++;
								?>
							
<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
                              
                               <td class="center" style='text-align:center'><?=$empi?></td>
								
								<td class="center"><span id="txt_employee_code"><? echo $join[$search_i]['EMPCODE'];?></span></td>
								<td class="center"><? echo $join[$search_i]['EMPNAME'];?></td>
								<td class="center"><span id="txt_task_details"><? echo $join[$search_i]['TASKDET'];?></span></td>
							    <td class="center"><span id="txt_task_date"><? echo $join[$search_i]['TASKDATE'];?></span></td>
		                        <td class="center"><span id="txt_task_from_time"><? echo $join[$search_i]['ENTTIME'];?></span></td>
								<td class="center"><span id="txt_task_to_time"><? echo $join[$search_i]['OUTTIME'];?></span></td>
								<td class="center"><select id="txt_employee_grade"><option>A+</option>
								<option>A</option>
								<option>B</option>
								<option>C</option>
								</select>



			
</td>
								

								
								<? } ?>	
                            </tr>
							
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<div class="panel-footer">
<button class="btn btn-success pull-right" onclick="msubmit()" type="button">Submit</button>
									</div>
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>
						
                                       
   
    
    </body>
</html>
