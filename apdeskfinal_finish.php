<?php 
session_start();
include('lib/config.php');
include('../db_connect/public_functions.php');
include('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

// ar.FINSTAT = 'C' and 
$sql_search = select_query("select ar.ADDDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APRQVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, 
										decode(ar.FINSTAT, 'C','COMPLETED', 'NOT APPROVED') APPSTATUS, decode(ar.FINSTAT, 'C','2','1') APPORDER, ar.APPATTN, (select EMPNAME from employee_office 
										where empsrno = ar.ADDUSER) as reqby, (select EMPNAME from employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office 
										where empsrno = ar.FINUSER) as FINUSER, ar.FINSTAT, ar.FINCMNT, ar.FINDATE, ar.APPRDET, ar.ORGRECV, ar.ORGRVUS, ar.ORGRVDT, ar.ORGRVDC 
									from APPROVAL_REQUEST ar 
									where ar.ARQSRNO = 1 and ar.deleted = 'N' and appstat = 'A' and IMSTATS = 'N' and IMSTATS != 'Y' and ORGRECV = 'N' and 
										ar.ARQCODE = '".$arqcode."' and ar.ATYCODE = '".$atycode."' and ar.ATCCODE = '".$atccode."' and ar.ARQYEAR = '".$arqyear."'
									order by APPORDER asc, ar.FINDATE desc, APRNUMB desc");
?>
<form role="form" id='frm_final_finish' name='frm_final_finish' action='' method='post' enctype="multipart/form-data">
<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Approval NO : </label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<div><b><?=$aprnumb?></b>
			<input type='hidden' name='action' id='action' value='finish'>
			<input type='hidden' name='aprnumb' id='aprnumb' value='<?=$aprnumb?>'>
			<input type='hidden' name='reqid' id='reqid' value='<?=$reqid?>'>
			<input type='hidden' name='year' id='year' value='<?=$year?>'>
			<input type='hidden' name='rsrid' id='rsrid' value='<?=$rsrid?>'>
			<input type='hidden' name='creid' id='creid' value='<?=$creid?>'>
			<input type='hidden' name='typeid' id='typeid' value='<?=$typeid?>'>

			<input type='hidden' name='search_subject' id='search_subject' value='<?=strtoupper($search_subject)?>'>
			<input type='hidden' name='search_aprno' id='search_aprno' value='<?=strtoupper($search_aprno)?>'>
			<input type='hidden' name='search_value' id='search_value' value='<?=strtoupper($search_value)?>'>
			<input type='hidden' name='search_add_findate' id='search_add_findate' value='<?=strtoupper($search_add_findate)?>'>
			<input type='hidden' name='search_fromdate' id='search_fromdate' value='<?=strtoupper($search_fromdate)?>'>
			<input type='hidden' name='search_todate' id='search_todate' value='<?=strtoupper($search_todate)?>'>
		</div>
	</div>
</div>
<div style='clear:both'></div>

<? /* <div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Enter Comments :</label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<div><input class="form-control" placeholder="Approval Desk Final Finish - Enter Comments" maxlength='100' type='text' required name='txt_finalcomments' id='txt_finalcomments' value='' data-toggle="tooltip" data-placement="top" title="Approval Desk Final Finish - Enter Comments"></div>
	</div>
</div>
<div style='clear:both'>&nbsp;</div> */ ?>

<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Attach Original Received Document :</label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<div><input class="form-control" placeholder="Final Finish - Attach Original Received Document" maxlength='150' type='file' required name='txt_finaldoc' id='txt_finaldoc' accept="application/pdf,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Final Finish - Attach Original Received Document">( Allow only 1 pdf Document )</div>
	</div>
</div>
<div style='clear:both'>&nbsp;</div>

<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<button type="submit" name='sbmt_finalfinish' id='sbmt_finalfinish' value='submit' class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Submit"><i class="fa fa-save"></i> Submit</button>
	</div>
</div>
</form>