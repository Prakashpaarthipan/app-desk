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

/*
ds = Load_data("select prdcode,Subcode,nvl(PrdTax,'N') PrdTax,NVL(HSNCode,0) HSNCode  from subproduct_asset where prdcode='" & txtPRDCODE.Tag & "' and subcode='" & LookupCode & "'")
If ds.Tables(0).Rows(0).Item("PrdTax").ToString = "Y" Then

    If ds.Tables(0).Rows(0).Item("HSNCode").ToString <> "0" Then
        Hsn_TextBox.Text = ds.Tables(0).Rows(0).Item("HSNCode").ToString
        DvGstPrd = TCS_Centra_Lib.Get_Cmd_View("select CGSTPER,SGSTPER,IGSTPER from PRODUCT_ASSET_GST_PER where prdcode='" & txtPRDCODE.Tag & "' and subcode='" & LookupCode & "' ")

        If DvGstPrd.Count > 0 Then
            CgstTextBox.Text = DvGstPrd(0)("CGSTPER")
            SgstTextBox.Text = DvGstPrd(0)("SGSTPER")
            IgstTextBox.Text = DvGstPrd(0)("IGSTPER")
            txtPURTAX.Text = Val(DvGstPrd(0)("SGSTPER") + DvGstPrd(0)("CGSTPER"))
            Hsn_TextBox.Text = ds.Tables(0).Rows(0).Item("HSNCode").ToString
        Else
            MessageBox.Show("Fix Product Gst Details", "TCS Accounts", MessageBoxButtons.OK, MessageBoxIcon.Information)
            Exit Sub
        End If
    Else
        MessageBox.Show("HSN Code Not Fixed This SUB Product Code", "TCS Accounts", MessageBoxButtons.OK, MessageBoxIcon.Information)
        Exit Sub
    End If
Else
    CgstTextBox.Text = 0
    SgstTextBox.Text = 0
    IgstTextBox.Text = 0
End If
*/

if($action == 'product') { 
	$result = select_query_json("select distinct PRDCODE, PRDNAME, HSNCODE from product_asset 
										where depcode = '".$depcode."' and deleted = 'N' and (PRDCODE like '%".strtoupper($_GET['name_startsWith'])."%' 
											or PRDNAME like '%".strtoupper($_GET['name_startsWith'])."%') 
										Order by PRDCODE, PRDNAME", "Centra", 'TCS'); // and HSNCODE is not null
	$data = array();
	// print_r($result);
	if(count($result) > 0) {
		/* if($result[0]['HSNCODE'] == '') { // approval_budget_product
			array_push($data, 'HSN Code no available. Kindly Contact MIS Team Regarding this!!');
		} else { */
			for($rowi = 0; $rowi < count($result);$rowi++) {
				array_push($data, $result[$rowi]['PRDCODE']." - ".$result[$rowi]['PRDNAME']);
		    }
		// }
    } else {
		array_push($data, '');
	} 
    echo json_encode($data);
} elseif($action == 'sub_product') {
	if($_GET['product'] != '') {
		$expl = explode(" - ", $_GET['product']);
	    $result = select_query_json("select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax 
	    									from subproduct_asset sub, product_asset prd 
											where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$depcode."' and sub.deleted = 'N' and prd.deleted = 'N' and 
												(sub.SUBCODE like '%".strtoupper($_GET['name_startsWith'])."%' or sub.SUBNAME like '%".strtoupper($_GET['name_startsWith'])."%') and 
												(prd.prdcode = '".strtoupper($expl[0])."') and sub.HSNCODE is not null
									union 
										select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax 
	    									from subproduct_asset sub, product_asset prd 
											where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$depcode."' and sub.deleted = 'N' and prd.deleted = 'N' and 
												(sub.SUBCODE like '%".strtoupper($_GET['name_startsWith'])."%' or sub.SUBNAME like '%".strtoupper($_GET['name_startsWith'])."%') and 
												(prd.prdcode = '".strtoupper($expl[0])."') and sub.prdtax='N'
											order by SUBCODE, SUBNAME, PRDCODE", "Centra", 'TCS');  
	} else {
		$result = select_query_json("select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax 
											from subproduct_asset sub, product_asset prd 
											where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$depcode."' and sub.deleted = 'N' and prd.deleted = 'N' and sub.HSNCODE is not null and 
												(sub.SUBCODE like '%".strtoupper($_GET['name_startsWith'])."%' or sub.SUBNAME like '%".strtoupper($_GET['name_startsWith'])."%') and  
												(prd.prdcode like '%".strtoupper($_GET['name_startsWith'])."%' or prd.prdname like '%".strtoupper($_GET['name_startsWith'])."%')
									Union
										select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax
											from subproduct_asset sub, product_asset prd 
											where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$depcode."' and sub.deleted = 'N' and prd.deleted = 'N' and sub.prdtax='N' and 
												(sub.SUBCODE like '%".strtoupper($_GET['name_startsWith'])."%' or sub.SUBNAME like '%".strtoupper($_GET['name_startsWith'])."%') and  
												(prd.prdcode like '%".strtoupper($_GET['name_startsWith'])."%' or prd.prdname like '%".strtoupper($_GET['name_startsWith'])."%')
											order by SUBCODE, SUBNAME, PRDCODE", "Centra", 'TCS');
	}

    $data = array();
	if(count($result) > 0) {
		if($result[0]['HSNCODE'] == '' && $result[0]['PRDTAX'] == "Y") {
			array_push($data, 'HSN Code no available. Kindly Contact MIS Team Regarding this!!');
		} else {
			for($rowi = 0; $rowi < count($result);$rowi++) {
				array_push($data, $result[$rowi]['SUBCODE']." - ".$result[$rowi]['SUBNAME']);
		    }
		}
    } else {
		array_push($data, '');
	} 
    echo json_encode($data);
} elseif($action == 'supplier') {
    $result = select_query_json("select distinct SUPCODE, SUPNAME, SUPMOBI from supplier_asset 
										where deleted = 'N' and (SUPCODE like '%".strtoupper($_GET['name_startsWith'])."%' or SUPNAME like '%".strtoupper($_GET['name_startsWith'])."%') 
										order by SUPCODE, SUPNAME", "Centra", 'TCS');
    $data = array();
	if(count($result) > 0) {
		if($result[0]['HSNCODE'] == '') {
			array_push($data, 'HSN Code no available. Kindly Contact MIS Team Regarding this!!');
		} else {
			for($rowi = 0; $rowi < count($result);$rowi++) {
				array_push($data, $result[$rowi]['SUPCODE']." - ".$result[$rowi]['SUPNAME']." - ".$result[$rowi]['SUPMOBI']);
		    }
		}
	} else {
		array_push($data, '');
	} 
    echo json_encode($data);
} elseif($action == 'supplier_withcity') {
    if($slt_core_department == 31) {
    	$result = select_query_json("select distinct sup.SUPCODE, sup.SUPNAME, sup.SUPMOBI, cty.ctyname from supplier sup, city cty
											where sup.ctycode = cty.ctycode and sup.deleted = 'N' and cty.deleted = 'N' and (sup.SUPCODE like '%".strtoupper($_GET['name_startsWith'])."%' 
												or sup.SUPNAME like '%".strtoupper($_GET['name_startsWith'])."%') and sup.supcode >= 7000
											order by sup.SUPCODE, sup.SUPNAME", "Centra", 'TCS');
    } else {
	    $result = select_query_json("select distinct sup.SUPCODE, sup.SUPNAME, sup.SUPMOBI, cty.ctyname from supplier_asset sup, city cty
											where sup.ctycode = cty.ctycode and sup.deleted = 'N' and cty.deleted = 'N' and (sup.SUPCODE like '%".strtoupper($_GET['name_startsWith'])."%' 
												or sup.SUPNAME like '%".strtoupper($_GET['name_startsWith'])."%') and SUPMAIL is not null
											order by sup.SUPCODE, sup.SUPNAME", "Centra", 'TCS');
	}
    $data = array();
	if(count($result) > 0) {
		for($rowi = 0; $rowi < count($result);$rowi++) {
			// array_push($data, $result[$rowi]['SUPCODE']." - ".str_replace("-", " ", $result[$rowi]['SUPNAME'])." - ".$result[$rowi]['CTYNAME']." - ".$result[$rowi]['SUPMOBI']);
			array_push($data, $result[$rowi]['SUPCODE']." - ".str_replace("-", " ", $result[$rowi]['SUPNAME'])." - - ");
	    }
	} else {
		array_push($data, '');
	} 
    echo json_encode($data);
} /* elseif($action == 'product_specification') { 
	$result = select_query_json("select distinct APSPCCD, APSPCNM from approval_product_specification 
										where deleted = 'N' and (APSPCNM like '%".strtoupper($_GET['name_startsWith'])."%' or APSPCCD like '%".strtoupper($_GET['name_startsWith'])."%') 
										Order by APSPCNM");
	$data = array();
	if(count($result) > 0) {
		if($result[0]['HSNCODE'] == '') {
			array_push($data, 'HSN Code no available. Kindly Contact MIS Team Regarding this!!');
		} else {
			for($rowi = 0; $rowi < count($result);$rowi++) {
				array_push($data, $result[$rowi]['APSPCNM']);
		    }
		}
    } else {
		array_push($data, '');
	}
    echo json_encode($data);
} */ 
elseif($action == 'sub_prd'){
	$sql_subprd = select_query_json("select distinct prd.PRDCODE, prd.PRDNAME, sub.subcode,sub.subname,sub.HSNCODE,sub.prdtax,decode(sub.prdtax,'Y','YES','N','NO','NO') TAX 
												from product_asset prd, subproduct_asset sub 
												where prd.prdcode = sub.prdcode and prd.depcode = ".$_REQUEST['depcode']." and prd.deleted = 'N' and sub.deleted = 'N' 
												Order by prd.PRDCODE,sub.subcode", "Centra", 'TCS');
	if($sql_subprd[0]['PRDCODE']!="") { ?>
	 <h3 style="font-weight:bold;text-align: center;font-size: 2em;" > Product Detail </h3>
	 <table class="table table-bordered table-striped table-hover" >
	<thead style="font-size: medium;">
		<th>SRNO</th>
		<th>PRDCODE</th>
		<th>NAME</th>
		<th>SUB CODE</th>
		<th>SUB NAME</th>
		<th>HSNCODE</th>
		<th>TAX</th>
	</thead>
	<tbody>
	<?
	$no=0;
	foreach($sql_subprd as $prd){
		$no++; 
	?>
	<tr style="font-size: 15px;">
		<td><?=$no?></td>
		<td style="min-width: 50px;"><?=$prd['PRDCODE']?></td>
		<td style="min-width: 150px;"><?=$prd['PRDNAME']?></td>
		<td style="min-width: 50px;"><?=$prd['SUBCODE']?></td>
		<td style="min-width: 150px;"><?=$prd['SUBNAME']?></td>
		<td style="min-width: 50px;"><?=$prd['HSNCODE']?></td>
		<td style="min-width: 50px;"><?=$prd['TAX']?></td>
	<tr>
	<?
	} ?> 
	</tbody>
	</table>
 <?	}
	else
	{ ?>
		<h3 style="font-weight:bold;text-align: center;"> No Product Details </h3>
 <?	} 
}  elseif($action == 'table_master') {
	$sql_subprd = select_query_json("select * from master_table_detail where deleted = 'N' order by TABNAME asc", "Centra", 'TCS');
	if(count($sql_subprd) > 0) { ?>
	<h3 style="font-weight:bold;text-align: center;font-size: 2em;" > Master Table Detail </h3>
	<table class="table table-bordered table-striped table-hover" >
	<thead style="font-size: medium;">
		<th>#</th>
		<th>Table ID</th>
		<th>Table Name</th>
	</thead>
	<tbody>
		<? $no=0;
		foreach($sql_subprd as $prd) { $no++; ?>
		<tr style="font-size: 15px;">
			<td style="width: 10%; text-align: center;"><?=$no?></td>
			<td style="width: 30%; text-align: center;"><?=$prd['MASTERID']?></td>
			<td style="width: 60%; text-align: left;"><?=$prd['TABNAME']?></td>
		<tr>
		<?
		} ?> 
	</tbody>
	</table>
 <?	} 
	else { ?><h3 style="font-weight:bold;text-align: center;"> No Master Table Details available. </h3><?	} 
} elseif($action == 'table_master_view') {
	$sql_mastable = select_query_json("select * from master_table_detail where deleted = 'N' and TABNAME like '".$tblname."' order by TABNAME asc", "Centra", 'TCS');
	$exp_mastable = explode(",", $sql_mastable[0]['FLDNAME']);
	$exp_mastable1 .= " and ( ";
	foreach ($exp_mastable as $mastable_value) {
		$exp_mastable1 .= " COLUMN_NAME like '".$mastable_value."' or ";
	}
	$exp_mastable1 = rtrim($exp_mastable1, " or ");
	$exp_mastable1 .= " ) ";

	$sql_tabltitl = select_query("SELECT table_name, column_name FROM USER_TAB_COLUMNS WHERE table_name = '".$tblname."' ".$exp_mastable1." order by COLUMN_ID asc");
	$dlt_available = 0;
	foreach ($sql_tabltitl as $tabltitl) {
		if($tabltitl['COLUMN_NAME'] == 'DELETED') {
			$dlt_available++;
		}
	}

	if($dlt_available == 0) {
		$sql_tablrecr = select_query("SELECT ".$sql_mastable[0]['FLDNAME']." FROM ".$tblname." order by ".$sql_tabltitl[0]['COLUMN_NAME']." asc");
	} else {
		$sql_tablrecr = select_query("SELECT ".$sql_mastable[0]['FLDNAME']." FROM ".$tblname." WHERE deleted = 'N' order by ".$sql_tabltitl[0]['COLUMN_NAME']." asc");
	}
	
	if(count($sql_tabltitl) > 0) { ?>
	<h3 style="font-weight:bold;text-align: center;font-size: 2em; text-transform: uppercase;" > Master Table Detail - "<?=strtoupper($tblname)?>"</h3>
	<table class="table table-bordered table-striped table-hover" id="myModal_wap">
	<thead style="font-size: medium;">
		<th style="text-align: center;">#</th>
		<? foreach ($sql_tabltitl as $tabltitl) { ?>
			<th style="text-align: center;"><?=$tabltitl['COLUMN_NAME']?></th>
		<? } ?>
	</thead>
	<tbody>
		<? $no=0;
		foreach($sql_tablrecr as $prd) { $no++; ?>
		<tr style="font-size: 15px;">
			<td style="text-align: center;"><?=$no?></td>
			<? foreach ($sql_tabltitl as $tabltitl) { 
					$titl = $tabltitl['COLUMN_NAME']; ?>
				<td style="text-align: center;"><?=$prd[$titl]?></td>
			<? } ?>
		<tr>
		<?
		} ?> 
	</tbody>
	</table>
 <?	} 
	else { ?><h3 style="font-weight:bold;text-align: center;"> No Master Table Details available. </h3><?	} 
} ?>