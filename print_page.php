<?php 
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

$sql_brn = select_query_json("Select end.*,c.ctyname,esec.eseNAME From employee_notice_detail end,empsection esec,city c,branch b 
									Where esec.esecode=end.esecode and c.ctycode=b.ctycode and b.brncode=end.brncode and notyear='".$_REQUEST['notyear']."' and notnumb='".$_REQUEST['notnumb']."'", "Centra", 'TEST');
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$sql_brn[0]['NOTYEAR']."-".$sql_brn[0]['NOTNUMB'];?> :: Employee Notice</title>
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
<body>
	<? 
	//print_r($sql_brn);
	$addname = select_query_json("Select EMPNAME From employee_OFFICE Where empsrno='".$_REQUEST['authby']."'", "Centra", 'TCS');
	//echo("Select USRNAME From userid Where USRCODE='".$_REQUEST['authby']."'");
	//print_r($addname);
	$authname = select_query_json("Select EMPNAME From employee_OFFICE Where EMPSRNO='".$sql_brn[0]['AUTSRNO']."'", "Centra", 'TCS');
	$descname = select_query_json("select DESNAME from designation where descode='".$sql_brn[0]['DESCODE']."'", "Centra", 'TCS');
	$authname1 = select_query_json("select empname from employee_office where empsrno='".$_REQUEST['authby']."'", "Centra", 'TCS');
	//print_r($authname);
	//print_r($sql_brn);
	//$sql_brn = select_query_json("Select * From employee_notice_detail Where notyear='".$_REQUEST['notyear']."' and notnumb='".$_REQUEST['notnumb']."'", "Centra", 'TEST');?>
	<?$folder_path = "approval_desk/digital_signature/";

	?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 style="text-align:center;"><u>EMPLOYEE NOTICE</u></h1>
				<table style="width:100%" class='reference'>
					<tr>
					    <td align="right" width='85%'>DATE : </td><td align="left" width='15%'><?php echo strtoupper(date('d-M-Y'));?></td></tr>
					  <tr>  <td align="right" width='85%'>REF NO : </td> <td align="left" width='15%'><?php echo $sql_brn[0]['NOTYEAR']."-".$sql_brn[0]['NOTNUMB'];?></td></tr>	
					  </tr>
				</table>
				
				<table style="width:100%" class="contact">
					  
					  <tr>
					    <td align="right" width='15%'>TO :</td>
					    <td align="left"></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left"><?echo $sql_brn[0]['EMPNAME'];?></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left">EC NO : <?echo $sql_brn[0]['EMPCODE'];?></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left"><?echo $sql_brn[0]['ESENAME'];?>&nbsp(<?echo $descname[0]['DESNAME'];?>)</td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left">THE CHENNAI SILKS - <?if($sql_brn[0]['BRNCODE']=='888'){echo("( CORPORATE OFFICE )");}?></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left">TIRUPPUR</td> 					   
					  </tr>

					   <tr>
					    <td align="right" width='15%'>FROM :</td>
					    <td align="left"></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left"><?=$authname1[0]['EMPNAME'];?></td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left">THE CHENNAI SILKS - ( CORPORATE OFFICE )</td> 					   
					  </tr>
					  <tr>
					    <td align="right" width='15%'></td>
					    <td align="left"><?echo $sql_brn[0]['CTYNAME'];?></td> 					   
					  </tr>
					  <tr class="highlight">
					    <td align="right" width='15%'>SUBJECT :</td>
					    <td align="left"></td> 						     
					  </tr>
					  <tr class="subject">
					  	<td align="right" width='15%'></td> 	
					  	<td align="left" width='85%'><?echo $sql_brn[0]['REMARKS'];?></td>  
					  </tr>
					  
				</table>
				<!-- <div align="left" style="padding-left:60px">
					<h4>Sub :<?echo $sql_brn[0]['REMARKS'];?><h4>
				</div> -->

			</div>
		</div>
		<?//$SIGN=array("MD","PS MADAM","S KAARTHI","ADMIN GM");
		$SIGNAME=array("KUMARAN K", "S KAARTHI", "PADHMA SIVLINGAM S", "SIVALINGAM K");
		$SIGN=array('452', '21344', '43400', '20118');
		//print_r($SIGN);?>
		<div class="row sign">
			<div class="col-md-12">
			<table style="width:100%" class="signature">
				<? if($_REQUEST['all']==4) { ?>
					<tr>
					    <td align='center' width='20%' height='100%'></td>
					    <?for($i=0;$i<4;$i++){?>
					    	<td style="text-align: center; width: 20%;" >
					    		<?if($SIGN[$i]=='43400'){?>
					    			<?$sign = "ftp_image_view.php?pic=".$SIGN[$i].".png&path=".$folder_path."";?>
					    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
					    		<?}?>
					    		<?if($SIGN[$i]=='452'){?>
					    			<?$sign = "ftp_image_view.php?pic=".$SIGN[$i].".png&path=".$folder_path."";?>
					    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
					    		<?}?>
					    		<?if($SIGN[$i]=='21344'){?>
					    			<?$sign = "ftp_image_view.php?pic=".$SIGN[$i].".png&path=".$folder_path."";?>
					    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; height:60px;'></label>
					    		<?}?>
					    		<?if($SIGN[$i]=='20118'){?>
					    			<?$sign = "ftp_image_view.php?pic=".$SIGN[$i].".png&path=".$folder_path."";?>
					    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
					    		<?}?>
					    	</td>
					    <? } ?>	   
					</tr>
				<? } else { ?>
					<tr>
				    <td style='text-align: center; width: 20%;'></td>
				    <td style='text-align: center; width: 20%;'>
				    	<?if($_REQUEST['authby']=='43400'){?>
			    			<?$sign = "ftp_image_view.php?pic=".$_REQUEST['authby'].".png&path=".$folder_path."";?>
			    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
			    		<?}?>
			    		<?if($_REQUEST['authby']=='452'){?>
			    			<?$sign = "ftp_image_view.php?pic=".$_REQUEST['authby'].".png&path=".$folder_path."";?>
			    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
			    		<?}?>
			    		<?if($_REQUEST['authby']=='21344'){?>
			    			<?$sign = "ftp_image_view.php?pic=".$_REQUEST['authby'].".png&path=".$folder_path."";?>
			    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; height:60px;'></label>
			    		<?}?>
			    		<?if($_REQUEST['authby']=='20118'){?>
			    			<?$sign = "ftp_image_view.php?pic=".$_REQUEST['authby'].".png&path=".$folder_path."";?>
			    			<label style='color:#0088CC; font-weight:bold'><img src='<?=$sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label>
			    		<?}?>
				    </td>
					</tr>
				<? } ?>
			</table>
		</div>
		<div class="row sign">
			<div class="col-md-12">
			<table style="width:100%" class="signature">
				<? if($_REQUEST['all'] == 4){ ?>
					<tr>
					    <td style="text-align: center; width: 20%;">RECEIVER</td>
					    <? for($i=0; $i<4; $i++) { ?>
					    	<td style="text-align: center; padding-left: 10px; width: 20%;"><?echo ($SIGNAME[$i]);?></td>
					    <? } ?>	   
					</tr>
				<? } else { ?>
					<tr>
				    <td style='text-align: center; width: 20%;'>RECEIVER</td>
				    <td style='text-align: center; width: 20%;'><?echo $addname[0]['EMPNAME'];?></td>
					</tr>
				<? } ?>
			</table>
		</div>
		</div>
		<div class="panel-footer">
			<center >
				<input class="btn btn-success no-print" id='btnPrint' style="text-align: center" type="button" onclick="window.print();" value="PRINT">
			</center>
		</div>
	</div>
</body>
</html>