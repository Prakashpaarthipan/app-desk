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

if($action == 'piecedet') { 
	$sql_piecedet = select_query_json("select SUM(NTOPIEC) PURPIECE, SUM(NTORECV) RECPIECE from NON_ord_content where ntoyear='".$slt_orderyear."' and ntonumb=".$txt_orderno."", "Centra", 'TCS'); 
	if(count($sql_piecedet) > 0) {
		$diff_vl = $sql_piecedet[0]['PURPIECE'] - $sql_piecedet[0]['RECPIECE'];
		if($diff_vl <= 0) {
			echo "1";
		} else {
			echo "0";
		}
	}
} elseif($action == 'supplier_details'){
	if($slt_core_department == 31) {
		$result = select_query_json("select SUPCODE, SUPNAME from supplier 
											where DELETED = 'N' and ( SUPCODE like '".strtoupper($_GET['name_startsWith'])."%' or 
												SUPNAME like '".strtoupper($_GET['name_startsWith'])."%' ) and rownum<=10 
											order by SUPNAME Asc", "Centra", 'TCS');
	} else {
	    $result = select_query_json("select SUPCODE, SUPNAME from supplier_asset 
											where DELETED = 'N' and ( SUPCODE like '".strtoupper($_GET['name_startsWith'])."%' or 
												SUPNAME like '".strtoupper($_GET['name_startsWith'])."%' ) and rownum<=10
											order by SUPNAME Asc", "Centra", 'TCS');
	}
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi]['SUPCODE']." - ".$result[$rowi]['SUPNAME']);
    }    
    echo json_encode($data);
} elseif($action == 'supdet') { 
	$sql_supdet = select_query_json("select ntoyear, Ntonumb, NTODATE Orddate, NTODEDT Fdate, NTOEDDT Tdate, sup.supcode, sup.supname, sup.SUPADD1, sup.SUPADD2, sup.SUPADD3, cty.ctyname, sup.SUPMOBI 
											from trandata.non_ord_summary@tcscentr nto, trandata.supplier_Asset@tcscentr sup, trandata.City@tcscentr cty 
											where nto.supcode=sup.supcode and nto.deleted='N' and sup.ctycode=cty.ctycode and ntoyear='".$slt_orderyear."' and NTONUMB = ".$txt_orderno."", "Centra", 'TCS'); 
?>
<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Supplier Details <span style='color:red'>*</span></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		: 	<b><?=$sql_supdet[0]['SUPCODE']." - ".$sql_supdet[0]['SUPNAME']?></b><br>
			<? if($sql_supdet[0]['SUPADD1'] != '') { echo $sql_supdet[0]['SUPADD1']."<br>"; } ?>
			<? if($sql_supdet[0]['SUPADD2'] != '') { echo $sql_supdet[0]['SUPADD2']."<br>"; } ?>
			<? if($sql_supdet[0]['SUPADD3'] != '') { echo $sql_supdet[0]['SUPADD3']."<br>"; } ?>
			<? if($sql_supdet[0]['CTYNAME'] != '') { echo $sql_supdet[0]['CTYNAME']; } 
				if($sql_supdet[0]['SUPMOBI'] != '') { echo " - ".$sql_supdet[0]['SUPMOBI']; } ?>
		<input type="hidden" class="form-control" tabindex="3" required name="txt_supcode" id="txt_supcode" data-toggle="tooltip" data-placement="top" title="Supplier Code" autocomplete="off" data-original-title="Supplier Code" value="<?=$sql_supdet['SUPCODE']?>">
	</div>
</div>
<div class='clear clear_both'></div>
<? } elseif($action == 'tabledet') { 
	$nxt_mon = (date("m")+1);
	$mnval = findmonth(date("m")+1);
	$txt_frdate = date("01-").$mnval.date("-Y");
	$txt_enddt  = date('t', strtotime(date("01-".$nxt_mon."-Y")));
	$txt_todate = $txt_enddt."-".$mnval.date("-Y"); ?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width: 100% !important;" >
	<thead>
		<tr>
			<th>#</th>
			<th>Product Code</th>
			<th>Purchase Rate</th>
			<th>Pieces</th>
			<th>Old From Date</th>
			<th>Old To Date</th>
			<th>
				<input type="text" name="txt_design_duedate" id="txt_design_duedate" placeholder='New From Date' readonly autocomplete='off' value='<?=strtoupper($txt_frdate)?>' class="form-control dateformats" maxlength='9' title='Please Confirm New From Date' OnBlur="Apply_All_Duedate()" style='text-transform:uppercase;'>New From Date</th>
			<th>New To Date</th>
			<th>Reason</th>
			<th style='text-align:center;'>
				<input type="checkbox" class="ios-switch bigswitch" name="brand_selectall" id="brand_selectall" onClick="Select_All()">
				<label for="brand_selectall" style="margin-top:5px;">Select All</label>
			</th>
		</tr>
	</thead>
	<tbody>
	<? 	$sql_search = select_query_json("select con.Ntosrno, Prdcode, Ntoprat, con.Ntopiec, to_char(NTODEDT,'dd-MON-yyyy') OldFrmDate, to_char(NTOEDDT,'dd-MON-yyyy') OldTodate, to_char(NTODEDT,'dd-MON-yyyy') NewFrmDate, 
													to_char(NTODEDT,'dd-MON-yyyy') NewToDate, '' Reason, 'False' Change 
												from trandata.non_ord_detail@tcscentr det, trandata.non_ord_content@tcscentr con 
												where det.ntoyear=con.ntoyear and det.ntonumb=con.ntonumb and det.ntosrno=con.ntosrno and con.ntopiec>con.ntorecv and det.ntoyear||'-'||det.ntonumb||'-'||det.ntosrno not in(select ntoyear||'-'||ntonumb||'-'||ntosrno from trandata.non_ord_duedate@tcscentr due where due.ntoyear=con.ntoyear and due.ntonumb=con.ntonumb and due.ntosrno=con.ntosrno 
												and stacode=0) and det.ntoyear='".$slt_orderyear."' and det.ntonumb=".$txt_orderno."", "Centra", 'TCS');
		$ij = -1;
		foreach($sql_search as $orderdata) { $ij++;
			$editid = 0; $bgclr = ''; $clr = '#000000'; ?>
		<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
			<td style='text-align:center'><?=($ij+1)?></td>
			<td style='text-align:center'><? echo $orderdata['PRDCODE']; ?></td>
			<td style='text-align:center'><? echo $orderdata['NTOPRAT']; ?></td>
			<td style='text-align:center'><? echo $orderdata['NTOPIEC']; ?></td>
			<td style='text-align:center'><?php echo $orderdata['OLDFRMDATE']; ?></td>
			<td style='text-align:center'><?php echo $orderdata['OLDTODATE']; ?></td> 
			<td style='text-align:center'>
				<input type="hidden" name="txt_ntosrno[]" id="txt_ntosrno_<?=$ij?>" required value='<?php echo $orderdata['NTOSRNO']; ?>' >
				<input type="hidden" name="txt_oldfrmdate[]" id="txt_oldfrmdate_<?=$ij?>" required value='<?php echo $orderdata['OLDFRMDATE']; ?>' >
				<input type="hidden" name="txt_oldtodate[]" id="txt_oldtodate_<?=$ij?>" required value='<?php echo $orderdata['OLDTODATE']; ?>' >
				
				<input type="text" name="txt_newfrmdate[]" id="txt_newfrmdate_<?=$ij?>" tabindex="11" class="form-control dateformats" placeholder='Select Date' required readonly autocomplete='off' value='<?php echo $txt_frdate; ?>' onKeyPress="return isNumberwithDot(event)" maxlength='20' title='Choose Due From Date' style='text-transform:uppercase;' >
			</td>
			<td style='text-align:center'>
				<input type="text" name="txt_newtodate[]" id="txt_newtodate_<?=$ij?>" tabindex="12" class="form-control dateformats" placeholder='Select Date' required readonly autocomplete='off' value='<?php echo $txt_todate; ?>' onKeyPress="return isNumberwithDot(event)" maxlength='20' title='Choose Due To Date' style='text-transform:uppercase;' >
			</td>
			<td style='text-align:center'>
				<input type="text" class="form-control" tabindex="13" maxlength="50" name="txt_reason[]" id="txt_reason_<?=$ij?>" value="" data-toggle="tooltip" data-placement="top" title="Enter Reason" autocomplete="off" data-original-title="Enter Reason" style='text-transform:uppercase;' >
			</td>
			<td style='text-align:center'>
				<small class="">
					<input type="checkbox" class="brand chkbx" title='SELECT' name="slt_changeduedate[]" id="slt_changeduedate_<?=$ij?>" value="<?=$ij?>" >
					<label for="slt_changeduedate_<?=$ij?>">SELECT</label>
				</small>
			</td>
		</tr>
	<? } ?>
	<input type="hidden" name="hid_cntdata" id="hid_cntdata" value="<?=count($sql_search)?>">
	</tbody>
</table>
</div>
<? } elseif($action == 'approvaldet') { 
	$nxt_mon = (date("m")+1);
	$mnval = findmonth(date("m")+1);
	$txt_frdate = date("01-").$mnval.date("-Y");
	$txt_enddt  = date('t', strtotime(date("01-".$nxt_mon."-Y")));
	$txt_todate = $txt_enddt."-".$mnval.date("-Y"); ?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width: 100% !important;" >
	<thead>
		<tr>
			<th>#</th>
			<th>Product Code</th>
			<th>Purchase Rate</th>
			<th>Pieces</th>
			<th>Old From Date</th>
			<th>Old To Date</th>
			<th>New From Date</th>
			<th>New To Date</th>
			
			<th>Added User</th>
			<th>Added Date</th>
			<th>Reason</th>
		</tr>
	</thead>
	<tbody>
	<? 	$sql_search = select_query_json("select due.NTOYEAR, due.NTONUMB, due.NTOSRNO, Prdcode, Ntoprat, Ntopiec, to_char(due.NTODEDT_OLD,'dd-MON-yyyy') OldFrmDate, to_char(due.NTOEDDT_OLD,'dd-MON-yyyy') OldTodate, 
													to_char(due.NTODEDT_NEW,'dd-MON-yyyy') NewFrmDate, to_char(due.NTOEDDT_NEW,'dd-MON-yyyy') NewToDate, due.REFERENCE Reason, due.ADDUSER addeduserid, 
													usr.usrname addedusername, due.ADDDATE addeddate 
												from NON_ORD_DUEDATE due, non_ord_summary summ, non_ord_detail det, userid usr 
												where det.ntoyear=due.ntoyear and det.ntoyear=due.ntoyear and det.ntonumb=due.ntonumb and summ.NTOYEAR = due.NTOYEAR and summ.NTONUMB = due.NTONUMB and 
													usr.usrcode = due.ADDUSER and due.appuser=".$_SESSION['tcs_user']." and due.APPSTAT = 'N' and due.ntoyear='".$poyr."' and due.ntonumb=".$pono."", "Centra", 'TCS');
		$ij = -1;
		foreach($sql_search as $orderdata) { $ij++;
			$editid = 0; $bgclr = ''; $clr = '#000000'; ?>
		<tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
			<td style='text-align:center'><?=($ij+1)?></td>
			<td style='text-align:center'><? echo $orderdata['PRDCODE']; ?></td>
			<td style='text-align:center'><? echo $orderdata['NTOPRAT']; ?></td>
			<td style='text-align:center'><? echo $orderdata['NTOPIEC']; ?></td>
			<td style='text-align:center'><?php echo $orderdata['OLDFRMDATE']; ?></td>
			<td style='text-align:center'><?php echo $orderdata['OLDTODATE']; ?></td> 
			<td style='text-align:center'><?php echo $orderdata['NEWFRMDATE']; ?></td> 
			<td style='text-align:center'><?php echo $orderdata['NEWTODATE']; ?></td> 
			<td style='text-align:left'><?php echo $orderdata['ADDEDUSERID']." - ".$orderdata['ADDEDUSERNAME']; ?></td> 
			<td style='text-align:center'><?php echo $orderdata['ADDEDDATE']; ?></td> 
			<td style='text-align:left'><?php echo $orderdata['REASON']; ?></td> 
		</tr>
	<? } ?>
	<input type="hidden" name="hid_cntdata" id="hid_cntdata" value="<?=count($sql_search)?>">
	</tbody>
</table>
</div>
<? } ?>