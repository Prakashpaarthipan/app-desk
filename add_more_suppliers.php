<?php 
session_start();
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

// $current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
/*
print_r($txt_sltsupcode);
if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['function'] == 'save_more_suppliers') {
	// Product List - Quotation Adding pdi
	for($qdi = 0; $qdi < count($txt_sltsupcode); $qdi++) { // echo "***";
		$sp_cd = explode(" - ", $txt_sltsupcode[$qdi]);
		$maxprlstsr = select_query_json("Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR 
												From APPROVAL_PRODUCT_QUOTATION 
												WHERE PBDYEAR = '".$hid_pbdyear."' and PBDCODE = ".$pbdcode." and PBDLSNO = '".$pbdlsno."' 
													and PRLSTYR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TEST'); 
		$tbl_appdet1 = "APPROVAL_PRODUCT_QUOTATION";
		$field_appdet1 = array();
		$field_appdet1['PBDYEAR'] = $hid_pbdyear;
		$field_appdet1['PBDCODE'] = $pbdcode;
		$field_appdet1['PBDLSNO'] = $pbdlsno;
		$field_appdet1['PRLSTYR'] = $hid_pbdyear;
		$field_appdet1['PRLSTNO'] = $pbdlsno;
		$field_appdet1['PRLSTSR'] = $maxprlstsr[0]['MAXPRLSTSR'];

		$field_appdet1['SUPCODE'] = $sp_cd[0];
		$field_appdet1['SUPNAME'] = strtoupper($sp_cd[1]);
		$field_appdet1['SLTSUPP'] = 0;
		$field_appdet1['DELPRID'] = 1;

		$field_appdet1['PRDRATE'] = $txt_prdrate[$qdi];
		$field_appdet1['SGSTVAL'] = $txt_prdsgst[$qdi];
		$field_appdet1['CGSTVAL'] = $txt_prdcgst[$qdi];
		$field_appdet1['IGSTVAL'] = $txt_prdigst[$qdi];
		$field_appdet1['DISCONT'] = $txt_prddisc[$qdi];
		$field_appdet1['NETAMNT'] = $hid_prdnetamount[$qdi];
		$field_appdet1['SUPRMRK'] = strtoupper($txt_suprmrk[$qdi]);
		$field_appdet1['ADVAMNT'] = $txt_advance_amount[$qdi];

		$field_appdet1['QUOTFIL'] = $complogos1;
		$field_appdet1['SPLDISC'] = $txt_spldisc[$qdi];
		$field_appdet1['PIECLES'] = $txt_pieceless[$qdi];
		print_r($field_appdet1);
		exit;
		// $insert_appdet1 = insert_dbquery($field_appdet1, $tbl_appdet1);
	}
	// Product List - Quotation Adding
}
*/

/* if($action == 'get bid date') {

} else { */
	$sql_reqexsup = select_query_json("select distinct SUPCODE, SUPNAME from approval_productlist pdl, approval_product_quotation qut 
												where pdl.pbdyear = qut.pbdyear and pdl.pbdcode = qut.pbdcode and pdl.PBDLSNO = qut.PBDLSNO and pdl.pbdyear = '".$pbdyear."' and 
													pdl.pbdcode = '".$pbdcode."' and pdl.PBDLSNO = '".$pbdlsno."' and pdl.PRDCODE = '".$prdcode."' and pdl.SUBCODE = '".$supprdcode."'", "Centra", "TEST");
	$exsup = '';
	foreach ($sql_reqexsup as $key => $exsupvalue) {
		$exsup .= $exsupvalue['SUPCODE'].", ";
	}
	$exsup = rtrim($exsup, ", ");
	?>
	<form name="frm_addsupplier" id="frm_addsupplier" action="#" method="POST">
		<input type="hidden" name="hid_pbdyear" id="hid_pbdyear" value="<?=$pbdyear?>">
		<input type="hidden" name="hid_pbdcode" id="hid_pbdcode" value="<?=$pbdcode?>">
		<input type="hidden" name="hid_pbdlsno" id="hid_pbdlsno" value="<?=$pbdlsno?>">
		<input type="hidden" name="hid_supcode" id="hid_supcode" value="<?=$supcode?>">
		<input type="hidden" name="function" id="function" value="save_more_suppliers">

		<input type="hidden" name="hid_adreqid" id="hid_adreqid" value="<?=$reqid?>">
		<input type="hidden" name="hid_adyear" id="hid_adyear" value="<?=$year?>">
		<input type="hidden" name="hid_adrsrid" id="hid_adrsrid" value="<?=$rsrid?>">
		<input type="hidden" name="hid_adcreid" id="hid_adcreid" value="<?=$creid?>">
		<input type="hidden" name="hid_adtypeid" id="hid_adtypeid" value="<?=$typeid?>">
		<input type="hidden" name="hid_adtypeid" id="hid_adtypeid" value="<?=$typeid?>">
		<?
		/* $sql_search = select_query_json("select distinct sup.SUPCODE, sup.SUPNAME, sup.SUPMOBI, cty.ctyname from supplier_asset sup, city cty
												where sup.ctycode = cty.ctycode and sup.deleted = 'N' and cty.deleted = 'N' and sup.SUPCODE not in (".$exsup.") and SUPMAIL is not null
												order by sup.SUPCODE, sup.SUPNAME", "Centra", "TCS"); */
		$sql_search = select_query_json("select distinct sup.SUPCODE, sup.SUPNAME, sup.SUPMOBI, cty.ctyname, cty.ctyname, '!!'||CGSTPER||'!!'||SGSTPER||'!!'||IGSTPER||'!!'||cty.STACODE TAX_PERCENTAGE 
												from supplier_asset sup, city cty, nt_prd_sup_rate prd, product_asset_gst_per gst 
												where sup.SUPCODE = prd.SUPCODE and gst.prdcode = prd.prdcode and gst.subcode = prd.subcode and sup.ctycode = cty.ctycode and sup.deleted = 'N' and 
													cty.deleted = 'N' and (sup.SUPCODE not in (".$exsup.")) and SUPMAIL is not null and prd.PRDCODE = '".$prdcode."' and prd.SUBCODE = '".$supprdcode."' 
												order by sup.SUPCODE, sup.SUPNAME", "Centra", "TEST");
		$ivl = 0; 
		if(count($sql_search) > 0) { ?>
		<input type="submit" name="sbmt_addsupplier" id="sbmt_addsupplier" value="ADD SUPPLIER" style="border:1px solid #FFF; background-color: #299654; cursor: pointer; margin-bottom: 10px; color: #FFFFFF; font-weight: bold; line-height: 25px;">
		<div style="width: 100%; line-height: 25px;">
			<div style="width: 5%; background-color: #a0a0a0; color: #FFF; font-weight: bold; text-transform: uppercase; float: left; text-align: center;">SR.No</div>
			<div style="width: 10%; background-color: #a0a0a0; color: #FFF; font-weight: bold; text-transform: uppercase; float: left; text-align: center;">Choose</div>
			<div style="width: 83%; padding-left: 1%; background-color: #a0a0a0; color: #FFF; font-weight: bold; text-transform: uppercase; float: left;">Supplier Details</div>
		</div>
		<div style='clear:both'></div>
		<? 

		foreach ($sql_search as $key => $searchvalue) { $ivl++; 
			$ttax = $searchvalue['TAX_PERCENTAGE']; ?>
		<div style="width: 100%; line-height: 25px;">
			<div style="width: 5%; border:1px solid #a0a0a0; float: left; text-align: center;"><?=$ivl?></div>
			<div style="width: 10%; border:1px solid #a0a0a0; float: left; text-align: center;"><input type="checkbox" name="txt_sltsupcode[]" id='txt_sltsupcode_<?=$ivl?>' value="<?=$searchvalue['SUPCODE']?> - <?=$searchvalue['SUPNAME'].$ttax."!!".$brncode?>"></div>
			<div style="width: 83%; padding-left: 1%; border:1px solid #a0a0a0; float: left;"><?=$searchvalue['SUPCODE']?> - <?=$searchvalue['SUPNAME']?></div>
		</div>
		<div style='clear:both'></div>
		<? } 
		} else { ?>
			<div style="color: #FF0000">No Records Found..</div>
		<? } ?>
		<div style='clear:both'></div>
	</form>
<? // } ?>