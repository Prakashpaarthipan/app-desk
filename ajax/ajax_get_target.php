<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
if($_GET['mode'] == "VAL")
{
	$sql_validate = select_query_json("select * from trandata.approval_productwise_budget where brncode=".$brncode." and  aprcode=".$project." and  
								atycode=".$slt_submission." and  expsrno=".$core_deptid." and  depcode=".$deptid." and  tarnumb=".$target_no." and atccode=".$top_core." and subcore=".$sub_core." and appstat='A'", "Centra", 'TCS');
	echo count($sql_validate);
}

if($_GET['mode'] == "RES")
{
	$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); 
	$frdt = date("01-M-y");
	$todt = "31-MAR-18";
	$nxtyr = $current_year[0]['PORYEAR'];

	$minvl = date('m/Y', strtotime($frdt));
	$maxvl = date('m/Y', strtotime($todt));
	$minvl1 = date('m,Y', strtotime($frdt));
	$maxvl1 = date('m,Y', strtotime($todt));

	$budyr = date('Y', strtotime($frdt));
	$budmn = date('m', strtotime($frdt));

	$fdt = explode("/", $minvl);
	$tdt = explode("/", $maxvl);
	$ivl = 0; $ii = ''; $fstmnth = ''; $lstmnth = '';

	$sql_prd = select_query_json("select lst.pbdyear,lst.pbdcode,lst.pbdlsno,lst.prdcode,lst.prdname,lst.prdspec,lst.subcode,lst.subname,lst.TOTLQTY,lst.TOTLVAL,qua.supcode,qua.supname,qua.prdrate,qua.sgstval,qua.cgstval,qua.DISCONT,qua.netamnt
	from trandata.approval_productlist lst ,trandata.approval_product_quotation qua 
	where lst.pbdyear=qua.pbdyear and lst.pbdcode=qua.pbdcode and lst.pbdlsno=qua.pbdlsno and lst.pbdyear='".$nxtyr."' and lst.pbdcode=1 and qua.sltsupp=1", "Centra", 'TCS');
	//print_r($sql_prd);
?>
	<div>
	<table class="table table-bordered table-hover" style='clear:both; float:left; width:90%;'>
	<thead>
	<tr>
		<th>#</th>
		<th>Srno</th>
		<th>Product</th>
		<th>Sub Product</th>
		<th>Specifi</th>
		<th>S Code</th>
		<th>Supplier Name</th>
		<th>Rate</th>
		<th>Sgst</th>
		<th>Cgst</th>
		<th>Dis</th>
		<th>Net Amt</th>
	</tr>
	</thead>
	<tbody>
	<? $i_val=0;
	foreach($sql_prd as $prd)
	{ $i_val++;
	?>
	<? /* <tr id="tr_<?=$i_val?>" data-toggle="collapse" data-target="#demo_<?=$i_val?>" class="accordion-toggle" >
	<td><i class="fa fa-plus"></i></td> */ ?>
	<tr id="tr_<?=$i_val?>" >
	<td>RADIO BUTTON</td>
	<td><?=$i_val?></td>
	<td><?=$prd['PRDNAME']?></td>
	<td><?=$prd['SUBNAME']?></td>
	<td><?=$prd['PRDSPEC']?></td>
	<td><?=$prd['SUPCODE']?></td>
	<td><?=$prd['SUPNAME']?></td>
	<td><?=$prd['PRDRATE']?></td>
	<td><?=$prd['SGSTVAL']?></td>
	<td><?=$prd['CGSTVAL']?></td>
	<td><?=$prd['DISCONT']?></td>
	<td><?=$prd['NETAMNT']?></td>
	</tr>
	<tr style="padding: 0px;">
	<td colspan="12" class="hiddenRow" style="padding: 0px;background-color: white;">
		  <div id="demo_<?=$i_val?>" class="accordian-body collapse">
			<table style='clear:both; float:left; width:90%;'>
	<tr><td><table class="monthyr_wrap" style='width:100%;'>
	<tr>
			<td colspan="3" style='text-align: center; font-weight:bold;'>
				Total Qty : <? if($prd['TOTLQTY'] == '') { echo "0"; } else { echo $prd['TOTLQTY']; } ?>
			</td>
		</tr>
	<? 

	if($fdt[1] == $tdt[1]) {
		for($i = $fdt[0]; $i <= $tdt[0]; $i++) { $ivl++;
			if($i < 10 && strlen($i) == 2) {
				$i = ltrim($i, '0');
			}
			$ii = findmonth($i);
			if($ivl == 1) {
				$fstmnth = $i.",".$fdt[1];
			}
			?>
				<tr>
					<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?><?=$i_val?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span><?=$ii?>, <?=$fdt[1]?></span> : </td>
					<td style='width:5%;'></td>
					<td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?><?=$i_val?>' class='form-control  mnt_tt_<?=$i?>   ttlsum<?=$i_val?> ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='return isNumber(event)' onKeyup='calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>')' onblur="calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>'); allow_zero_prd(<?=$i?>, this.value, '<?=$prd['TOTLQTY']?>',<?=$i_val?>);" maxlength='10' style='margin: 2px 0px;'></td>
					<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?>
					<input type="hidden" name="hid_prd[]" id="hid_prd_<?=$i?><?=$i_val?>" value="<?=$prd['PBDYEAR']."~".$prd['PBDCODE']."~".$prd['PBDLSNO']?>">
					
				</tr>
			<?
		if ($i_val == 1)
			{?>
				<input type="hidden" name="hid_mnt_val[]" class="mnt_val<?=$i?>" id="hid_mnt_val<?=$i?>" value="0">
			<?}
		} ?>
	</td></tr>
	<? 
} else { 
		for($i = $fdt[0]; $i <= 12; $i++) { $ivl++;
			if($i < 10 && strlen($i) == 2) {
				$i = ltrim($i, '0');
			}
			$ii = findmonth($i);
			if($ivl == 1) {
				$fstmnth = $i+","+$fdt[1];
			}
			?>
				<tr>
					<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?><?=$i_val?>' class='form-control' value='<?=$i?>,<?=$fdt[1]?>'><span><?=$ii?>, <?=$fdt[1]?></span> : </td>
					<td style='width:5%;'></td>
					<td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?><?=$i_val?>' 
					class='form-control ttlsum<?=$i_val?>   mnt_tt_<?=$i?>   ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='return isNumber(event)' onKeyup='calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>')' onblur="calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>'); allow_zero_prd(<?=$i?>, this.value, '<?=$prd['TOTLQTY']?>',<?=$i_val?>);" maxlength='10' style='margin: 2px 0px;'></td>
					<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?>
					<input type="hidden" name="hid_prd[]" id="hid_prd_<?=$i?><?=$i_val?>" value="<?=$prd['PBDYEAR']."~".$prd['PBDCODE']."~".$prd['PBDLSNO']?>">
				</tr>
			<?
			if ($i_val == 1)
				{?>
					<input type="hidden" name="hid_mnt_val[]" class="mnt_val<?=$i?>" id="hid_mnt_val<?=$i?>" value="0">
				<?}
		}
		$lstmnth = ($i-1)+","+$fdt[1];
		
		for($i = 1; $i <= $tdt[0]; $i++) { $ivl++;
			if($i < 10 && strlen($i) == 2) {
				$i = ltrim($i, '0');
			}
			$ii = findmonth($i);
			if($ivl == 1) {
				$fstmnth = $i+","+$tdt[1];
			}
			?>
				<tr>
					<td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$i?><?=$i_val?>' class='form-control' value='<?=$i?>,<?=$tdt[1]?>'><span><?=$ii?>, <?=$tdt[1]?></span> : </td>
					<td style='width:5%;'></td>
					<td style='width:40%;'><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$i?><?=$i_val?>' 
					class='form-control ttlsum<?=$i_val?>  mnt_tt_<?=$i?> ttlsumrequired' <? /* if($slt_submission == 6) { ?>value='<?=$sql_yrly[0]['BUDVALU']?>' readonly<? } else { */ ?>value="0"<? /* } */ ?> onkeypress='return isNumber(event)' onKeyup='calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>')' onblur="calculate_sum_prd('<?=$i?>','<?=$i_val?>','<?=$prd['TOTLQTY']?>','<?=$prd['NETAMNT']?>'); allow_zero_prd(<?=$i?>, this.value, '<?=$prd['TOTLQTY']?>',<?=$i_val?>);" maxlength='10' style='margin: 2px 0px;'></td>
					<td style='width:30%; text-align:center; font-weight:bold;'><? /* <span id='id_remainingvalue_<?=$i?>'><?=moneyFormatIndia($sql_yrly[0]['BUDVALU'])?></span> */ ?>
					<input type="hidden" name="hid_prd[]" id="hid_prd_<?=$i?><?=$i_val?>" value="<?=$prd['PBDYEAR']."~".$prd['PBDCODE']."~".$prd['PBDLSNO']?>">
				</tr>
			<?
			if ($i_val == 1)
			{?>
				<input type="hidden" name="hid_mnt_val[]"  class="mnt_val<?=$i?>" id="hid_mnt_val<?=$i?>" value="0">
			<?}
		}
		$lstmnth = ($i-1)+","+$tdt[1];
	}

	 ?>
	<tr><td colspan='2' style='width:40%; text-align:right; padding-right:10%; font-weight:bold;'>TOTAL : </td><td style='width:60%; font-weight:bold;'><span id='ttl_mntyr<?=$i_val?>'><? /* if($slt_submission == 6) { echo $ttl_lock; } else { */ ?>0<? /* } */ ?></span></td></tr>
	</table></td></tr>
	</table>
		  	<input type='hidden' id='ttl_lock<?=$i_val?>' name='ttl_lock<?=$i_val?>' value='<?=$prd['TOTLQTY']?>'>
			<input type='hidden' id='add_tot<?=$i_val?>' name='add_tot<?=$i_val?>' class = "add_tot" value='0'>
			<input type='hidden' id='add_qty<?=$i_val?>' name='add_qty<?=$i_val?>' class = "add_qty" value='0'>
			</div>
	</td>
	</tr>
	<?}
	?>
	<input type='hidden' id='tot_row' name='tot_row'  value='<?=$i_val?>'>
	</tbody>
	</table>
	</div>

	<div>
	
	</div>

	<input type='hidden' id='frmdate' name='frmdate' value='<?=$minvl?>'>
	<input type='hidden' id='todate' name='todate' value='<?=$maxvl?>'>
	<input type='hidden' id='minvl' name='minvl' value='<?=$minvl?>'>
	<input type='hidden' id='maxvl' name='maxvl' value='<?=$maxvl?>'>
	<input type='hidden' id='fstmnth' name='fstmnth' value='<?=$fstmnth?>'>
	<input type='hidden' id='lstmnth' name='lstmnth' value='<?=$lstmnth?>'>
	<input type='hidden' id='hidapryear' name='hidapryear' value='<?=$nxtyr?>'>
<? } ?>