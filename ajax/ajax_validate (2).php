<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

if($action == 'product') {
	$expld = explode(" - ", strtoupper($validate_code));
	$prdcd = $expld[0];
	$result = select_query_json("select distinct PRD.PRDCODE, PRD.PRDNAME, PRD.HSNCODE from approval_budget_product bud, product_asset prd
										where prd.prdcode = BUD.prdcode and PRD.deleted = 'N' and (PRD.PRDCODE = '".strtoupper($prdcd)."')
										Order by PRD.PRDCODE, PRD.PRDNAME", "Centra", 'TCS');
	if(count($result) > 0) {
			$rtrn = 1;
		/*if($result[0]['HSNCODE'] == '') {
			$rtrn = 2;
		} else {
			$rtrn = 1;
		}*/
    } else {
		$rtrn = 0;
	}
	echo $rtrn;
} elseif($action == 'sub_product') {
	$expld1 = explode(" - ", strtoupper($prdcode));
	$prdcod = $expld1[0];

	$expld = explode(" - ", strtoupper($validate_code));
	$prdcd = $expld[0];
    $result = select_query_json("select distinct PRDCODE, SUBCODE, SUBNAME, HSNCODE,prdtax from subproduct_asset
										where deleted = 'N' and (SUBCODE = '".strtoupper($prdcd)."') AND (PRDCODE = '".strtoupper($prdcod)."')
										order by SUBCODE, SUBNAME, PRDCODE", "Centra", 'TCS');
    if(count($result) > 0) {
		if($result[0]['HSNCODE'] == '' && $result[0]['PRDTAX'] == 'Y') {
			$rtrn = 2;
		} else {
			$rtrn = 1;
		}
    } else {
		$rtrn = 0;
	}
	echo $rtrn;
} elseif($action == 'supplier') {
	$expld = explode(" - ", strtoupper($validate_code));
	$prdcd = $expld[0];
	if($slt_core_department == 31) {
	    $result = select_query_json("select distinct SUPCODE, SUPNAME, SUPMOBI from supplier
											where deleted = 'N' and (SUPCODE = ".$prdcd.")
											order by SUPCODE, SUPNAME", "Centra", 'TCS');

	    $sql_sup = select_query_json("select stacode from city where ctycode in (select ctycode from supplier where supcode=".$prdcd.")", "Centra", 'TCS');
	} else {
	    $result = select_query_json("select distinct SUPCODE, SUPNAME, SUPMOBI from supplier_asset
											where deleted = 'N' and (SUPCODE = ".$prdcd.")
											order by SUPCODE, SUPNAME", "Centra", 'TCS');
	    $sql_sup = select_query_json("select stacode from city where ctycode in (select ctycode from supplier_asset where supcode=".$prdcd.")", "Centra", 'TCS');
	}

	$sql_brn = select_query_json("select stacode from city where ctycode in (select ctycode from branch where brncode=".$brncode.")", "Centra", 'TCS');
    if(count($result) > 0) {
		$rtrn = 1;
    } else {
		$rtrn = 0;
	}

	if($sql_brn[0]['STACODE'] == $sql_sup[0]['STACODE'])
	{
		$rtrn .= "~1";
	}else{
		$rtrn .= "~0";
	}
	echo $rtrn;
} /* elseif($action == 'prod_spec') {
	$prdcd = strtoupper($validate_code);
	$result = select_dbquery("select distinct APSPCCD, APSPCNM from approval_product_specification
										where deleted = 'N' and (APSPCNM = '".$prdcd."')
										Order by APSPCNM");
	if(count($result) > 0) {
		$rtrn = 1;
    } else {
		$rtrn = 0;
	}
	echo $rtrn;
} */ elseif($action == 'find_unitcode') {
	$prdcode = explode(" - ", strtoupper($prdcode));
	$expld = explode(" - ", strtoupper($validate_code));
	$prdcd = $expld[0];
	$prdnm = $expld[1];
	if($expld[2] != '') {
		$prdnm .= " - ".$expld[2];
	}
	if($expld[3] != '') {
		$prdnm .= " - ".$expld[3];
	}
	if($expld[4] != '') {
		$prdnm .= " - ".$expld[4];
	}
	if($expld[5] != '') {
		$prdnm .= " - ".$expld[5];
	}
	if($expld[6] != '') {
		$prdnm .= " - ".$expld[6];
	}
    $result = select_query_json("select distinct unt.untcode, unt.untname from subproduct_asset sbp, unit unt
			where sbp.UNTCODE = unt.UNTCODE and unt.deleted = 'N' and sbp.deleted = 'N' and (SUBCODE = '".$prdcd."' ) and (SUBNAME like '%".$prdnm."%' )
				and sbp.HSNCODE is not null and sbp.prdcode like '%".$prdcode[0]."%'
				union
				select distinct unt.untcode, unt.untname from subproduct_asset sbp, unit unt
			where sbp.UNTCODE = unt.UNTCODE and unt.deleted = 'N' and sbp.deleted = 'N' and (SUBCODE = '".$prdcd."' ) and (SUBNAME like '%".$prdnm."%' )
				and sbp.prdtax='N' and sbp.prdcode like '%".$prdcode[0]."%'
		order by untcode, untname", "Centra", 'TCS');

	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			echo $result[$rowi]['UNTCODE']." - ".$result[$rowi]['UNTNAME'];
	    }
    } else {
		echo '';
	}
} elseif($action == 'find_rptmode') {
	$prdcd = strtoupper($validate_code);
    $result = select_query_json("select * from department_asset
										where EXPSRNO = '".$prdcd."' and depcode = '".$depcode."'", "Centra", 'TCS');

	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			echo $result[$rowi]['RPTMODE'];
	    }
    } else {
		echo '';
	}
} elseif($action == 'fix_nof_suppliers') {
	$prdcd = strtoupper($validate_code);
    $result = select_query_json("select PRDSRNO from product_asset where OLD_PRDCODE = '1' and deleted = 'N' and PRDCODE = '".strtoupper($prdcd)."'
										Order by PRDCODE, PRDNAME", "Centra", 'TCS');

	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			echo $result[$rowi]['PRDSRNO'];
	    }
    } else {
		echo '3';
	}
} /* elseif($action == 'find_hsncode') {
	$expld1 = explode(" - ", strtoupper($prdcode));
	$prdcd = $expld1[0];

	$expld2 = explode(" - ", strtoupper($sub_prdcode));
	$sbprd = $expld2[0];

    $result = select_query_json("select * from department_asset
										where EXPSRNO = '".$prdcd."'", "Centra", 'TCS');
	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			echo $result[$rowi]['RPTMODE'];
	    }
    } else {
		echo '';
	}
} */
elseif($action == 'fix_tax') {
	$expld1 = explode(" - ", strtoupper($prdcode));
	$prdcd = $expld1[0];

	$expld2 = explode(" - ", strtoupper($sub_prdcode));
	$sbprd = $expld2[0];

    $result = select_query_json("select CGSTPER||'-'||SGSTPER||'-'||IGSTPER TAX_PERCENTAGE
    								from product_asset_gst_per
    								where prdcode = '".$prdcd."' and subcode = '".$sbprd."' and rownum <= 1", "Centra", 'TCS');
    $sql_tax_n = select_query_json("select * from subproduct_asset where prdcode='".$prdcd."' and subcode=".$sbprd." and rownum = 1", "Centra", 'TCS');
	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			echo $result[$rowi]['TAX_PERCENTAGE'];
	    }
    } elseif($sql_tax_n[0]['PRDTAX'] == "N") {
		echo '0-0-0';
	}else{
		echo '';
	}
} elseif($action == 'fix_checklist') {
	if($slt_targetno != '' and $slt_targetno != 'N' and ($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7)) {
		$sql_chklist = select_query_json("select * from approval_checklist where deleted = 'N' and tarnumb = '".$slt_targetno."' order by apcklst", "Centra", 'TCS');
		if(count($sql_chklist) <= 0) {
			$sql_chklist = select_query_json("select * from approval_checklist where deleted = 'N' and tarnumb = '9001' order by apcklst", "Centra", 'TCS');
		}
		echo $sql_chklist[0]['CKLSTCD'];
	} else {
		echo 2;
	}
    /* foreach ($sql_chklist as $key => $chklist_value) {
        $exp_chklist = explode(",", $chklist_value['CKLSTCD']);
    } */
} elseif($action == 'tlu_style_category') { ?>
	<!-- Dynamic from Tlu_master -->
	<div class="row" style="margin-right: -5px; min-height: 25px; text-transform: uppercase; background-color: #666666; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
		<? /* <div class="col-sm-1 colheight"  style="padding: 0px; border-top-left-radius:5px;">
			<input type="hidden" name="partint3" id="partint3" value="1">
			<button class="btn btn-success btn-add3" id="addbtn" type="button" title="Add Employee" onclick="emp_night_add(1)"><span class="glyphicon glyphicon-plus"></span></button>
			<button id="removebtn" class="btn btn-remove btn-danger" type="button" title="Delete Product" onclick="emp_remove(1)"><span class="glyphicon glyphicon-minus"></span></button>&nbsp;#
		</div> */ ?>
        <div class="col-sm-2 colheight" style="padding: 0px;">#</div>
		<div class="col-sm-7 colheight" style="padding: 0px;">MEASUREMENT POINTS</div>
        <div class="col-sm-3 colheight" style="padding: 0px;">BASE SIZE</div>
        <? /*<div class="col-sm-2 colheight" style="padding: 0px;"></div>
        <div class="col-sm-2 colheight" style="padding: 0px;"></div> */ ?>
    </div>

    <? 	$sql_measurement = select_query_json("select * from TLU_STYLE_LIST where deleted = 'N' AND STYLEID = '".$slt_stlustyluser."' order by STYLEID, STLSTNO", "Centra", 'TEST');
    	$meas = 0; $crnt_min = '';
    	if(count($sql_measurement) > 0) {
        foreach ($sql_measurement as $key => $measurement_value) { $meas++; 
        	if($measurement_value['STYCMIN'] != $crnt_min) { ?>
	            <div class="row" style="margin-right: -5px; min-height: 25px; text-transform: uppercase; background-color: #a0a0a0; color:#FFFFFF; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-weight: bold;">
		            <div class="col-sm-12 colheight" style="padding: 0px;">NOTE : ALL VALUES IN <?=$measurement_value['STYCMIN']?></div>
	            </div>
	        <? } $crnt_min = $measurement_value['STYCMIN']; ?>

     	<div class="row" style="margin-right: -5px; min-height: 25px; display: flex; text-transform: uppercase;">
			<div class="col-sm-2 colheight" style="padding: 1px 0px;">
				<div class="fg-line">&nbsp;<?=$meas?></div>
			</div>

			<div class="col-sm-7 colheight" style="padding: 1px 0px;">
				<?=$measurement_value['STYTITL']?>
			</div>

            <div class="col-sm-3 colheight" style="padding: 1px 0px; text-align: center; padding-left: 2px;">
    			 <input type="text"  name="CURDES[]" id="curdes_<?=$meas?>" placeholder="SIZE" data-toggle="tooltip" data-placement="top" title="SIZE" class="form-control" style=" text-transform: uppercase;height: 25px;" required="required">
    		</div>
        </div>
    <? } } else { ?>
    	<div>No Data Found..</div>
    <? } ?>
    <!-- Dynamic from Tlu_master -->
<? } ?>
