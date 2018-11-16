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
}?>
<?php
$ftp_conn = ftp_connect($ftp_server_apdsk, 5022) or die("Could not connect to $ftp_server_apdsk");
$login = ftp_login($ftp_conn, $ftp_user_name_apdsk, $ftp_user_pass_apdsk);
?>


			<body>
<form method="post">
 
Employee Code<input type="text" name="txt_employee_code" id="txt_employee_code" ><br><br>
Employee serial No<input type="number" name="txt_employee_serialno" id="txt_employee_serialno"><br><br>
Employee Name<input type="text" name="txt_employee_name" id="txt_employee_name"><br><br>
Task Detail<input type="text" name="txt_task_details" id="txt_task_details"><br><br>
Task Date<input type="Date" name="txt_task_entry_date" id="txt_task_entry_date"><br><br>
Enter Time<input type="time" name="txt_task_from_time" id="txt_task_from_time"><br><br>
Out Time<input type="time" name="txt_task_to_time" id="txt_task_to_time"><br><br>


slt_
chk_
rdo_

   


									<div class="panel-footer">

									<button class="btn btn-success pull-right" onclick="" type="submit">Submit</button>
									</div>
									<div class="tags_clear"></div>
								</div>
								<div class="tags_clear"></div>
   </form>
               
<script type="text/javascript">

	function nsubmit(){
		alert("uable to call");
			var vurl = "task.php";
			$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'employee code':$("#employee code").val(),
				'emp sno':$("#emp sno").val(),
				'emp name':$("#emp name").val(),
				'task det':$("#task det").val(),
				'task dat':$("#task dat").val(),
				'enter tim':$("#enter tim").val(),
				'out tim':$("#out tim").val(),
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
	