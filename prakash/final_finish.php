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
$sql_search = select_query("select ar.ADDDATE, ar.APRNUMB, ar.APPSTAT, ar.APPRSUB, ar.APRQVAL, ar.APRTITL, ar.ARQSRNO, ar.ARQCODE, ar.ATYCODE, ar.ATCCODE, ar.ARQYEAR, ar.ADDUSER, ar.RQESTTO, decode(ar.FINSTAT, 'C','COMPLETED', 
										'NOT APPROVED') APPSTATUS, decode(ar.FINSTAT, 'C','2','1') APPORDER, ar.APPATTN, (select EMPNAME from employee_office where empsrno = ar.ADDUSER) as reqby, (select EMPNAME from 
										employee_office where empsrno = ar.RQESTTO) as reqto, (select EMPNAME from employee_office where empsrno = ar.FINUSER) as FINUSER, ar.FINSTAT, ar.FINCMNT, ar.FINDATE 
									from APPROVAL_REQUEST ar 
									where ar.ARQSRNO = 1 and ar.DELETED = 'N' and ar.APPSTAT in ('A', 'F', 'P') and ar.ARQCODE = '".$arqcode."' and ar.ATYCODE = '".$atycode."' and ar.ATCCODE = '".$atccode."' and 
										ar.ARQYEAR = '".$arqyear."'
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
			<input type='hidden' name='typeid' id='typeid' value='<?=$typeid?>'></div>
	</div>
</div>
<div style='clear:both'></div>

<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'>Attach Final Document :</label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<div><input class="form-control" placeholder="Final Finish - Attach your Final Document" maxlength='150' type='file' required name='txt_finaldoc' id='txt_finaldoc' accept="application/pdf,.pdf" value='' data-toggle="tooltip" data-placement="top" title="Final Finish - Attach your Final Document">( Allow only 1 pdf Document )</div>
	</div>
</div>
<div style='clear:both'>&nbsp;</div>

<div class="form-group trbg">
	<div class="col-lg-3 col-xs-3">
		<label style='height:27px;'></label>
	</div>
	<div class="col-lg-9 col-xs-9">
		<button type="submit" name='sbmt_finalfinish' id='sbmt_finalfinish' value='submit' class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Submit"><i class="fa fa-save"></i> Submit</button>
	</div>
<div style='clear:both'></div>
</div>
<div style='clear:both'></div>
</form>