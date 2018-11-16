<?php 
session_start();
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

// AFTER MAR/1/2017 - FOR AK SIR
$mr_ak_date="";
if($_SESSION['tcs_usrcode'] == 3000000) {
    $mr_ak_date = " and trunc(APPRSFR) >= TO_DATE('01-MAR-17','dd-Mon-yy') ";
}

$sql_aprmast = select_query_json("select mast.bidcode, mast.bidtitl, sum(decode(bidstat,'N',1,0)) appcnt
										from APPROVAL_BID_MASTER mast, APPROVAL_BID_STATUS stat, APPROVAL_REQUEST reqs 
										where stat.aprnumb = reqs.aprnumb and reqs.REQSTFR = ".$_SESSION['tcs_empsrno']." and reqs.APPSTAT in ('Z', 'W') and reqs.deleted = 'N' and 
											mast.bidcode = stat.bidcode(+) and mast.deleted='N' ".$mr_ak_date."
										group by MAST.BIDSRNO, mast.bidcode, mast.bidtitl 
										order by MAST.BIDSRNO, mast.bidcode, mast.bidtitl", 'Centra', 'TEST');
if(count($sql_aprmast)) { ?>
<div class="row">
	<? $idt = 0;
	foreach ($sql_aprmast as $key => $aprmast_value) { $idt++; 
		if($idt % 2 == 1) {
			$bgclr = "#c0c0c0";
		} else {
			$bgclr = "#d0d0d0";
		} ?>
		<div class="row" style="background-color: <?=$bgclr?>; line-height: 25px;">
			<div class="col-md-8" style="padding-right: 0px;"><?=$aprmast_value['BIDTITL']?></div><div class="col-md-4" style="font-weight: bold;">: <?=$aprmast_value['APPCNT']?></div>
			<div style='clear:both'></div>
		</div>
		<div style='clear:both'></div>
	<? } ?>
</div>
<? } ?>
<div style='text-align: center; width: 100%; padding-top: 20px;'>
	<button onclick="location.href='waiting_approval.php?status=bid';">More Info</button>
</div>
<div style='clear:both'></div>