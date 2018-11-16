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
        <script type="text/javascript" src="js/jquery.js"></script>
			<script type="text/javascript">

	function nsubmit(){
		alert("uable to call");
			var vurl = "connection1.php";
			$.ajax({
            type: "POST",
            url: vurl,
			data:{
				'txt_employee_code':$("#txt_employee_code").val(),
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
<form method="post">
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

</form>