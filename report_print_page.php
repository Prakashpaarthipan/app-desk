<?php 
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

?>
<? 
//$folder_path = "approval_desk/digital_signature/";
$cmt=select_query_json("select b.PRCDSC,a.TEMPCMNT,a.TEMPYR,a.TEMPNO,c.LANGDSC from SUPMAIL_PROCESS_ENTRY a,SUPMAIL_PROCESS b,SUPMAIL_PROCESS_LANGUAGE c where a.TEMPYR='".$_REQUEST['tempyr']."' and a.TEMPNO='".$_REQUEST['tempno']."' and a.PRCSYR=b.PRCSYR and a.PRCSNO=b.PRCSNO and c.PRCSYR=a.PRCSYR and c.PRCSNO=a.PRCSNO and a.DELETED='N' and b.DELETED='N' and c.DELETED='N'  order by a.TEMPNO desc");?>
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
<body onload='window.print()'>
	

	<div class="container" style="margin:50px 25px;">
		<div class="row" style="border:1px solid #000;padding:50px 10px;">
			<div class='col-md-12' style="padding:10px 50px">
			<div class="row">
				<div class="text-center" style="height:auto;margin-bottom:10px">
				<center><img src="images/logo.png" alt="logo" style="text-align:center;" /></center>
				</div>
			</div>	

		
		<div class="row">
			<table style="width:100%" class='reference'>
					<tr>
					    <td align="right" width='85%'>DATE : </td><td align="left" width='15%'><?php echo strtoupper(date('d-M-Y'));?></td></tr>
					  <tr>  <td align="right" width='85%'>REF NO : </td> <td align="left" width='15%'><?php echo $cmt[0]['TEMPYR'].' _ '.$cmt[0]['TEMPNO'];?></td></tr>	
					  </tr>
				</table>
			
				
		</div>
		<div class="row" style="height:100px;padding-top:10px">
				<div>
					<p>DEAR BUSINESS ASSOCIATES</p>
					<p>GREETINGS FROM THE CHENNAI SILKS</p>

				</div>
		</div>

				<h3 style="text-align:center;margin-top:10px"><u style="margin-top:5px"><?=$cmt[0]['PRCDSC']?></u></h3>
			<div class="row" style="height:auto;padding:10px">
				<div>
					<?

					$sign = "ftp_image_view.php?pic=".$cmt[0]['LANGDSC'].".png&path=".$folder_path."";?>
					    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; height:60px;'></label>
					

				</div>
			</div>
			<div class="row">
				<table style="width:100%" class="contact">
					  <?php 
				$field_name=select_query_json("select a.FIELDNM,a.FIELDVAL from SUPMAIL_PROCESS_VALUE a,SUPMAIL_PROCESS_ENTRY b where b.TEMPYR='".$_REQUEST['tempyr']."' and b.TEMPNO='".$_REQUEST['tempno']."' and b.TEMPYR=a.TEMPYR and b.TEMPNO=a.TEMPNO and a.DELETED='N' and b.DELETED='N' order by a.FIELDNM asc");

				for($i=0;$i<sizeof($field_name);$i++){
				?>
					  <tr>
					    <td align="left" width='30%'><?=strtoupper($field_name[$i]['FIELDNM'])?><span class="pull-right">:</span></td>
					    <td align="left" width='70%'><?=strtoupper($field_name[$i]['FIELDVAL'])?></td> 					   
					  </tr>
					  <?}?> 
					  
				</table>
			</div>
				

		<div class="row" style="margin-top:50px">
			<div style="height:auto;padding:5px 0px;margin-bottom:10px">
						<p>THANKS & REGARDS</p>
						<p>MANAGEMENT</p>
						<p>THE CHENNAI SILKS</p>

				</div>
	    </div>
		
		<div class="row" style="border:2px solid #000;border-radius: 10px">
		<div class="col-md-12" style="margin-top:0:padding:50px 0px;">
			
			<h5 style="font-weight:bold;margin-top:0;padding-top:5px;padding-left:5px"><u>SUGGESTION & COMMENTS</u></h5>
            <p style="margin-left:10px;margin-top:0"><?=$cmt[0]['TEMPCMNT']?> </p>
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