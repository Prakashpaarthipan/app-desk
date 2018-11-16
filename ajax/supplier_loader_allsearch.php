<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
// print_r($_REQUEST);
//echo('1');
extract($_REQUEST);

$supplierAll = select_query_json("select sup.supname,sup.supcode ,to_char(srr.reqnumb),srr.* 
										from service_request srr ,supplier sup 
										where sup.supcode = srr.requser and ((srr.REQUSER like '%".$_POST["searchall"]."%') or (srr.REQCONT like '%".$_POST["searchall"]."%') or 
											(srr.REQMAIL like '%".$_POST["searchall"]."%') or (srr.REQNUMB like '%".$_POST["searchall"]."%') or (sup.supname like '%".$_POST["searchall"]."%' )) and srr.REQSTAT IN ('N','A','C')
										order by srr.REQNUMB", "Centra", "TCS"); 
if($action=='searchAll'){?>
	<div class = "border-bottom " id="notassign" style="min-height:270px;max-height:270px;overflow-y: scroll;position: relative;">
		<?	if($supplierAll[0]==''){?>
			<div class="list-group-item" >
				<div class="row">
					<label class="control-label" ><span>No Record's Found</span></label>
				</div>
			</div>
		<?}?>

		<?for($k=0;$k<count($supplierAll);$k++) {?>
			<a onclick="details(<?echo $supplierAll[$k]['REQNUMB'];?>,<?echo $supplierAll[$k]['REQSRNO'];?>)" class="list-group-item">
				<div class="list-group-status status-away"></div>
				<?//$sup = select_query_json("select supname supcode from supplier where supname ='".."'","Centra","TCS");?>
				<img src="images/customers.png" class="pull-left" alt="<?echo $supplierAll[$k]['SUPNAME'];?>">
				<span class="contacts-title">Request Id: <?echo $supplierAll[$k]['REQNUMB'];?> </span>
				<p><?echo $supplierAll[$k]['SUPCODE'];?> - <?echo $supplierAll[$k]['SUPNAME'];?></p>
				<p><?echo $supplierAll[$k]['REQMAIL'];?><br><?echo $supplierAll[$k]['REQCONT'];?></p>
			</a>
		<? } ?>
	</div>
<?}?>
<div style="clear: both;padding-top:5px"></div>