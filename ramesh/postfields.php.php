<?php 
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
?>

<!DOCTYPE html>
<html>
<head>
<title>Project 1</title>
	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<style>

 @page 
        {
            size: auto;   
            margin: 0mm; 
        }

tr.highlight td {padding-top: 40px;}
body{
	font-weight:bold;
}
.sign{
	margin-top:150px;
}
	td{
		padding:10px;
	}
	.reference td{
		padding:5px;
	}
	@media print
{    
    .no-print
    {
        display: none !important;
    }
}
.subject td{
	
		line-height:2em;
	}

	/*table.signature tr td:first-child{
		
		padding-left:100px;
	}
	table.signature tr td:last-child{
		text-align:right;
		
	}*/
</style>
<body >
	<? $sql_brn = select_query_json("Select end.*,c.ctyname,esec.eseNAME From employee_notice_detail end,empsection esec,city c,branch b Where esec.esecode=end.esecode and c.ctycode=b.ctycode and b.brncode=end.brncode and notyear='".$_REQUEST['notyear']."' and notnumb='".$_REQUEST['notnumb']."'", "Centra", 'TEST');
	//print_r($sql_brn);
	$addname = select_query_json("Select EMPNAME From employee_OFFICE Where empsrno='".$_REQUEST['authby']."'", "Centra", 'TCS');
	//echo("Select USRNAME From userid Where USRCODE='".$_REQUEST['authby']."'");
	//print_r($addname);
	$authname = select_query_json("Select EMPNAME From employee_OFFICE Where EMPSRNO='".$sql_brn[0]['AUTSRNO']."'", "Centra", 'TCS');
	//print_r($authname);
	//print_r($sql_brn);
	//$sql_brn = select_query_json("Select * From employee_notice_detail Where notyear='".$_REQUEST['notyear']."' and notnumb='".$_REQUEST['notnumb']."'", "Centra", 'TEST');?>

	<div class="container">
		<div class="row">
			<div class='col-md-12' style="padding:10px 50px">
			<div class="row">
				<div class="text-center" style="height:auto">
				<center><img src="" alt="logo" style="text-align:center;" /></center>
				</div>
			</div>	

		
		<div class="row" style="height:25px">
			<p class='pull-right' style="float:right">DATE : <?php echo strtoupper(date('d-M-Y'));?></p>
				<!-- <table style="width:100%" class='reference'>
					<tr>
					    <td align="right" width='75%'>DATE : </td><td align="left" width='25%'><?php echo strtoupper(date('d-M-Y'));?></td></tr>
					 
				</table> -->
		</div>
		<div class="row" style="height:100px">
				<div>
					<p>Dear Business Associates</p>
					<p>Greeting from The Chennai Silks</p>

				</div>
		</div>


				<h3 style="text-align:center;margin-top:10px"><u>BOOKING BUNDLE SHORTAGE CONFIRMATION</u></h3>
			<div class="row" style="height:auto;padding:10px">
				<div>
					<img src="" />

				</div>
			</div>
			<div class="row">
				<table style="width:100%" class="contact">
					  
					  <tr>
					    <td align="left" width='30%'>Pjv No<span class="pull-right">:</span></td>
					    <td align="left" width='70%'></td> 					   
					  </tr>
					   <tr>
					    <td align="left" width='30%'>Bill No<span class="pull-right">:</span></td>
					    <td align="left" width='70%'></td> 					   
					  </tr>
					   <tr>
					    <td align="left" width='30%'>Bill Date<span class="pull-right">:</span></td>
					    <td align="left" width='70%'></td> 					   
					  </tr>
					   <tr>
					    <td align="left" width='30%'>Shortage Quantity<span class="pull-right">:</span></td>
					    <td align="left" width='70%'></td> 					   
					  </tr>
					   <tr>
					    <td align="left" width='30%'>Shortage Value<span class="pull-right">:</span></td>
					    <td align="left" width='70%'></td> 					   
					  </tr>
					  
				</table>
			</div>
				<!-- <div align="left" style="padding-left:60px">
					<h4>Sub :<?echo $sql_brn[0]['REMARKS'];?><h4>
				</div> -->

		<div class="row" style="margin-top:50px">
			<div style="height:auto;padding:5px 0px;">
						<p>Thanks & Regards</p>
						<p>Management</p>
						<p>The Chennai Silkks</p>

				</div>
	    </div>
		
		<div class="row">
		<div class="col-md-12" style="border:2px solid #000">
			<h3><u>SUGGESTION & COMMENTS</u></h3>

		</div>
	</div>
		</div>
		<!-- <div class="panel-footer">
			<center >
				<input class="btn btn-success no-print" id='btnPrint' style="text-align: center" type="button" onclick="window.print();" value="PRINT">
			</center>
		</div> -->
	</div>
</div>
</body>
</html>