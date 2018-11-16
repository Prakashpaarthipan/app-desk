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
$join=select_query_json("select * from task_gradee  tg, employee_office  ef where tg.EMPCODE=ef.EMPCODE and tg.EMPCODE<>14442 order by tg.OUTTIME", "Centra", "TEST");


//$sql_search=select_query_json("select EMPCODE, TASKDET, TASKDATE, ENTTIME, OUTTIME from TASK_GRADEE where EMPCODE<>14442", "Centra", "TEST");
//$sql_search1=select_query_json("select TASGRADE from TASK_GRADE11 where EMPCODE=14442", "Centra", "TEST");
?>

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

<html>
<body>
<!--<form method="post" id="txt_employee_grade1">
Employee Code<input type="text" name="txt_employee_code" id="txt_employee_code" ><br><br>
<select name="GRADE" id="txt_employee_grade">

<option>A+</option>
<option>A</option>
<option>B</option>
<option>c</option>
</select>

									<div class="panel-footer">

									<button class="btn btn-success pull-right" onclick="nsubmit()" type="button">Submit</button>
									</div>
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>

</form>!-->
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
