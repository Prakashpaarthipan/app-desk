<?php
try {
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='index.php';</script>
<?php exit();
}

if($_REQUEST['finstat'] == 'c') {
	$sql_reqid = select_query_json("select ar.*, to_char(APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
											to_char(APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
											to_char(INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time,
											to_char(ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, (select ATMNAME from approval_type_mode where atmcode = ar.atmcode) ATMMNAME,
											(select ATYNAME from approval_type where ATYCODE = ar.atycode and DELETED = 'N') aptype,
											(select APMNAME from approval_master where APMCODE = ar.APMCODE and DELETED = 'N') apmaster,
											(select regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) BRNNAME from branch where DELETED = 'N'
											and BRNCODE = ar.BRNCODE) branch, (select ADDUSER||'!'||REQSTBY||'!'||RQBYDES||'!'||REQDESC||'!'||REQESEC||'!'||REQDESN||'!'||REQESEN from APPROVAL_REQUEST
											where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and deleted = 'N') addeduser
										from APPROVAL_REQUEST
										where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '1' and ATCCODE = '".$_REQUEST['creid']."' and
											ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and APPSTAT = 'A'
										order by ARQCODE, ARQSRNO, ATYCODE", "Centra", 'TEST');
} else {
	$sql_reqid = select_query_json("select ar.*, to_char(APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
											to_char(APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
											to_char(INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time,
											to_char(ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, (select ATMNAME from approval_type_mode where atmcode = ar.atmcode) ATMMNAME,
											(select ATYNAME from approval_type where ATYCODE = ar.atycode and DELETED = 'N') aptype,
											(select APMNAME from approval_master where APMCODE = ar.APMCODE and DELETED = 'N') apmaster,
											(select regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) BRNNAME from branch where DELETED = 'N'
											and BRNCODE = ar.BRNCODE) branch, (select ADDUSER||'!'||REQSTBY||'!'||RQBYDES||'!'||REQDESC||'!'||REQESEC||'!'||REQDESN||'!'||REQESEN from APPROVAL_REQUEST
											where ARQCODE = ar.ARQCODE and ARQYEAR = ar.ARQYEAR and ARQSRNO = 1 and ATCCODE = ar.ATCCODE and ATYCODE = ar.ATYCODE and deleted = 'N') addeduser
										from APPROVAL_REQUEST ar
										where ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and ARQSRNO = '1' and ATCCODE = '".$_REQUEST['creid']."' and
											ATYCODE = '".$_REQUEST['typeid']."' and deleted = 'N' and APPSTAT not in ('R')
										order by ARQCODE, ARQSRNO, ATYCODE", "Centra", 'TEST'); //  and APPSTAT = 'A' and FINSTAT = 'C'
}

$sql_tarbalance = select_query_json("select R.*, ast.EXPSRNO, ast.EXPNAME EXPHEAD, to_char(R.APPRSFR,'hh:mi:ss AM') APPRSFR_crt_Time, to_char(R.APPRSFR,'dd-MON-yyyy hh:mi:ss AM') APPRSFR_Time,
											to_char(R.APPRSTO,'dd-MON-yyyy hh:mi:ss AM') APPRSTO_Time, to_char(R.INTPFRD,'dd-MON-yyyy hh:mi:ss AM') INTPFRD_Time,
											to_char(R.INTPTOD,'dd-MON-yyyy hh:mi:ss AM') INTPTOD_Time, to_char(R.APRDUED,'dd-MON-yyyy hh:mi:ss AM') APRDUED_Time, ast.DEPNAME,
											to_char(R.ADDDATE,'dd/mm/yyyy') ADDDATE_DATE, R.APPRDET, R.DEPCODE, R.TARNUMB, R.TARDESC, ast.EXPSRNO, ast.EXPNAME EXPHEAD, ast.DEPNAME
										from approval_request R, department_asset ast
										where R.ARQSRNO in (SELECT max(ARQSRNO) FROM approval_request where ARQCODE = R.ARQCODE and ARQYEAR = R.ARQYEAR and ATYCODE = R.ATYCODE
											and ATCCODE = R.ATCCODE) and R.ARQCODE = '".$_REQUEST['reqid']."' and R.ARQYEAR = '".$_REQUEST['year']."' and R.ATCCODE = '".$_REQUEST['creid']."'
											and R.ATYCODE = '".$_REQUEST['typeid']."' and R.deleted = 'N' and R.DEPCODE = ast.DEPCODE and ast.DELETED = 'N'
										order by R.ARQCODE, R.ARQSRNO, R.ATYCODE", "Centra", 'TEST'); //  and R.APPSTAT = 'A'

if($_REQUEST['action'] == 'print' and $sql_reqid[0]['ARQCODE'] == '') { ?>
	<script>alert('This request is not getting any approval / You dont have rights to print this page.'); window.location="request_list.php";</script>
<? exit();
}

if($_REQUEST['action'] == 'print')
{
	$title_tag = 'Print';
}

if((strtotime($sql_reqid[0]['APPRSFR']) <= strtotime('22-APR-18')) and $sql_reqid[0]['APTYPE'] == 'NEW PROPOSAL') { $aptype_display = "EXTRA BUDGET"; }
else { $aptype_display = $sql_reqid[0]['APTYPE']; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?=$sql_reqid[0]['APRNUMB']?> :: <?=$site_title?></title>
    <!-- Custom Fonts -->
    <link href="css/fontawesome/font-awesome.min.css" rel="stylesheet" type="text/css">

	<style>
	@charset "utf-8";
	/* CSS Document */
	@font-face {
		font-family: "freehand471";
		src: url('css/freehand471.ttf');
		color:#ff0000;
	}
	table.fixed { table-layout:fixed; }

	body {
		margin: 0px;
		padding: 10px;
		color: #666;
		font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 12px;
		line-height: 18px;
		//background-image: url("images/The-Chennai-Silks-logo-light.png");
		//background-repeat:no-repeat;
		//background-position: center center;
        //background-attachment:fixed;
	}

	.style_box {
		 border:1px solid #a8a8a8;
		 background-color:#e8e8e8;
		 color:#FF0000;
		 padding:8px;
	     -border-radius: 8px;
		 -moz-border-radius: 8px;
		 border-radius: 8px;
	}

	.form-group { min-height: 25px; }
	@media print
    {
		body {
			//background-image: url("images/The-Chennai-Silks-logo-light.png");
			//background-repeat:no-repeat;
			//background-position: center center;
			//background-attachment:fixed;
		}
    	#non-printable { display: none; }
		#non-printables { display: none; }

		.pagebreak { page-break-before: always; } page-break-after works, as well
		tr td.cls_pagebreak  { display: block; page-break-before: always; }
		div.pagebreak
	    {
	    	page-break-after: always;
	    	page-break-inside: avoid;
	    }
    }

	.table>thead>tr>th
	{
		vertical-align: middle;
	}
	th { text-align:center; }

	table, td, th {
		border: 0px;
	}

	td {
		padding: 0px;
	}
	table {
		border-spacing: 0px;
	}

	.per th td{ border: 1px solid #d0d0d0; }
	td.special { border: 1px solid #80c1e1; }
	th.special { border: 1px solid #80c1e1; }
	.cls_rupees { font-size:10px; }
	.colheight { border: 1px solid #e0e0e0 !important; min-height: 20px !important; height: auto !important; word-wrap:break-word; }
	.colauto { border: 1px solid #aca2a2 !important; min-height: 20px !important; height: auto !important; word-wrap:break-word;text-align: -webkit-center }
	.coltext { border: 1px solid #aca2a2 !important; min-height: 20px !important; height: auto !important; word-wrap:break-word;text-align:left;padding-left:10px; }
	.colnum { border: 1px solid #aca2a2 !important; min-height: 20px !important; height: auto !important; word-wrap:break-word;text-align: right;padding-right: 10px; }

	.blue_highlight { color: #007cff; }
	.green_highlight { color: #00b907; }
	.red_highlight { color: #FF0000; }
	tr td.cls_pagebreak  { display: block; page-break-before: always; }
	.pagebreak { page-break-before: always; } /* page-break-after works, as well */
	.txt_underline:hover { text-decoration: underline; }

	.custom-select { font-size: 11px !important; }
	.btn-danger  { color: #FFFFFF; background-color: #d9534f; border-color: #d43f3a; }
	.btn-warning { color: #FFFFFF; background-color: #f0ad4e; border-color: #eea236; }
	.btn-default { color: #FFFFFF; background-color: #0088CC; border-color: #cccccc; }
	.btn-success { color: #FFFFFF; background-color: #5cb85c; border-color: #4cae4c; }
	.btn-primary { color: #FFFFFF; background-color: #428bca; border-color: #357ebd; }
	.btn {
	    display: inline-block;
	    padding: 6px;
	    margin: 3px 0 0 0;
	    font-size: 12px;
	    font-weight: 400;
	    line-height: 1.42857143;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: middle;
	    -ms-touch-action: manipulation;
	    touch-action: manipulation;
	    cursor: pointer;
	    -user-select: none;
	    -moz-user-select: none;
	    -ms-user-select: none;
	    user-select: none;
	    background-image: none;
	    border: 1px solid transparent;
	    border-radius: 4px;
	}
	.disabled { background-color:#428BCA !important; color: #FFFFFF !important; }
	.btn.disabled, .btn[disabled], fieldset[disabled] .btn {
   		pointer-events: none;
	    cursor: not-allowed;
	    filter: alpha(opacity=65);
	    -box-shadow: none;
	    box-shadow: none;
	    opacity: .65;
	}

	.modal-open {
	    overflow: hidden
	}

	.modal {
	    position: fixed;
	    top: 0;
	    right: 0;
	    bottom: 0;
	    left: 0;
	    z-index: 1040;
	    display: none;
	    overflow: hidden;
	    -overflow-scrolling: touch;
	    outline: 0
	}

	.modal.fade .modal-dialog {
	    -transition: -transform .3s ease-out;
	    -o-transition: -o-transform .3s ease-out;
	    transition: transform .3s ease-out;
	    -transform: translate(0, -25%);
	    -ms-transform: translate(0, -25%);
	    -o-transform: translate(0, -25%);
	    transform: translate(0, -25%)
	}

	.modal.in .modal-dialog {
	    -transform: translate(0, 0);
	    -ms-transform: translate(0, 0);
	    -o-transform: translate(0, 0);
	    transform: translate(0, 0)
	}

	.modal-open .modal {
	    overflow-x: hidden;
	    overflow-y: auto
	}

	.modal-dialog {
	    position: relative;
	    width: auto;
	    margin: 10px
	}

	.modal-content {
	    position: relative;
	    background-color: #fff;
	    -background-clip: padding-box;
	    background-clip: padding-box;
	    border: 1px solid #999;
	    border: 1px solid rgba(0, 0, 0, .2);
	    border-radius: 6px;
	    outline: 0;
	    -box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
	    box-shadow: 0 3px 9px rgba(0, 0, 0, .5)
	}

	.modal-backdrop {
	    position: fixed;
	    top: 0;
	    right: 0;
	    bottom: 0;
	    left: 0;
	    background-color: #000
	}

	.modal-backdrop.fade {
	    filter: alpha(opacity=0);
	    opacity: 0
	}

	.modal-backdrop.in {
	    filter: alpha(opacity=50);
	    opacity: .5
	}

	.modal-header {
	    min-height: 16.43px;
	    padding: 15px;
	    border-bottom: 1px solid #e5e5e5
	}

	.modal-header .close {
	    margin-top: -2px
	}

	.modal-title {
	    margin: 0;
	    line-height: 1.42857143
	}

	.modal-body {
	    position: relative;
	    padding: 15px
	}

	.modal-footer {
	    padding: 15px;
	    text-align: right;
	    border-top: 1px solid #e5e5e5
	}

	.modal-footer .btn+.btn {
	    margin-bottom: 0;
	    margin-left: 5px
	}

	.modal-footer .btn-group .btn+.btn {
	    margin-left: -1px
	}

	.modal-footer .btn-block+.btn-block {
	    margin-left: 0
	}

	.modal-scrollbar-measure {
	    position: absolute;
	    top: -9999px;
	    width: 50px;
	    height: 50px;
	    overflow: scroll
	}

	#load_page {
         position: fixed;
         left: 0px;
         top: 0px;
         width: 100%;
         height: 100%;
         z-index: 10;
         opacity: 0.7;
         background: url('images/page-loader.gif') 50% 50% no-repeat #FFFFFF;
    }
	/*.style_box{ padding:5px !important;}*/
	ul {
    list-style:none;padding: 0px;
	}
	.style_box{ padding:0px !important;}
	::-webkit-input-placeholder { /* Chrome */
	  color: black;
	  transition: opacity 250ms ease-in-out;
	}
	.comment-frame {
    background-color: #f0eeee;
    border-radius: 3px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.23);
    box-shadow: 0 1px 2px rgba(0,0,0,.23);
    margin: 4px 4px 12px 0;
	}
	.comment-box {
    position: relative;
	}
	.comment-box {
    position: relative;
	}
	.comment-box-input {
    background: none;
    height: 75px;
    margin: 0;
    min-height: 75px;
    padding: 9px 11px;
    padding-bottom: 0;
    width: 100%;
    resize: none;
	}
	.comment-box-options {
    position: absolute;
    bottom: 20px;
    right: 6px;
	}
	.comment-box-options-item {
    border-radius: 3px;
    float: left;
    height: 18px;
    margin-left: 4px;
    padding: 6px;
	}
	textarea {
    color: #333;
    font: 14px Helvetica Neue,Arial,Helvetica,sans-serif;
    line-height: 18px;
    font-weight: 400;
	}

	.badge {
	    display: inline-block;
	    min-width: 10px;
	    padding: 3px 7px;
	    font-size: 12px;
	    font-weight: 700;
	    line-height: 1;
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: baseline;
	    background-color: #777;
	    border-radius: 10px;
	}
	</style>

	<style type="text/css" media="print">
	@page
	{
		size: auto;   /* auto is the initial value */
		margin: 2mm 6mm 2mm 3mm;  /* this affects the margin in the printer settings */
	}
	</style>


	<style type="text/css" media="print">
      div.page
      {
        page-break-after: always;
        page-break-inside: avoid;
      }
    </style>

    <script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
	<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css" />
	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<link href="css/lightgallery.css" rel="stylesheet">

	<link href="css/facebook_alert.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery_facebook.alert.js"></script>
	<script type="text/javascript">
		$(window).load(function() {
			$(".loader").hide();
			$( "#sbmt_print" ).trigger( "click" );
		});
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
		// $(".chosn").customselect();

        $(":submit").click(function () {
        	var rmrk = $('#txt_remarks').val();
        	var slt_intermediate_team = $('#slt_intermediate_team').val();
			if(rmrk == '') {
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Your Remarks is Empty. Kindly Add some Remarks here!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				$("#txt_remarks").val('');
				$("#txt_remarks").focus('');
			} else {
				var nm = this.name;
				var txt = nm.substring(5);
				var txt1 = txt;
				if(txt == 'forward'){
					var txt = $('#sbmt_forward').val();
					// var txt = document.getElementById('sbmt_forward').value;
				}
				event.preventDefault();

				if(txt1 == 'verification' && slt_intermediate_team == '') {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Kindly Choose Internal verification user first!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					// $("#slt_intermediate_team").val('');
					$("#slt_intermediate_team").focus('');
				} else {
					okbtn = 'OK';
					switch (txt1) {
						case 'reject':
							okbtn = 'REJECT';
							break;
						case 'pending':
							okbtn = 'PENDING';
							break;
						case 'verification':
							okbtn = 'OK';
							break;
						case 'query':
							okbtn = 'OK';
							break;
						case 'approve':
							okbtn = 'APPROVE';
							break;
						case 'mdapprove':
							okbtn = 'APPROVE';
							break;
						case 'forward':
							okbtn = 'APPROVE';
							break;
						case 'response':
							okbtn = 'RESPONSE';
							break;
						default:
							okbtn = 'OK';
							break;
					}

					jConfirm('Are you sure to want to '+txt+' this!', 'Confirmation Dialog',
					function(r) {
						// alert("**"+r); exit;
						if(r == true)
						{
							$("#hid_action").val(nm);
							$("#frm_print_request").submit();
						}
					}, okbtn, 'CANCEL');
				}
    		}
		});
    });

	function call_iv() {
		var intermediate_team = $('#slt_intermediate_team').val();
		if(intermediate_team != '') {
			$('#sbmt_reject').prop("disabled", true);
			$('#sbmt_pending').prop("disabled", true);
			$('#sbmt_verification').prop("disabled", false);
			$('#sbmt_query').prop("disabled", true);
			$('#sbmt_approve').prop("disabled", true);
			$('#sbmt_mdapprove').prop("disabled", true);
			$('#sbmt_forward').prop("disabled", true);
			$('#sbmt_response').prop("disabled", true);
		} else {
			$('#sbmt_reject').prop("disabled", false);
			$('#sbmt_pending').prop("disabled", false);
			$('#sbmt_verification').prop("disabled", true);
			$('#sbmt_verification').css('background-color', '#428bca');
			$('#sbmt_query').prop("disabled", false);
			$('#sbmt_approve').prop("disabled", false);
			$('#sbmt_mdapprove').prop("disabled", false);
			$('#sbmt_forward').prop("disabled", false);
			$('#sbmt_response').prop("disabled", false);
		}
	}
	</script>
</head>
<body>
	<div id="load_page" class="loader" style='display:block;'></div>
	<?php
		$sql_docs = select_query_json("select * from APPROVAL_REQUEST_DOCS where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by apdcsrn", 'Centra', 'TEST');
		$pagecount = count($sql_docs);
		if($pagecount==0){
			$pagecount=1;
		}
		$pagearry['img'] = array();

		for($ij = 0; $ij < count($sql_docs); $ij++) {

			$filename = $sql_docs[$ij]['APRDOCS'];
			$dataurl = $sql_docs[$ij]['APRHEAD'];
			$exp = explode("_", $filename);
			switch($exp[5])
			{
				case 'i':
						$pagearry['img'][] = $ij;

						break;
				case 'n':

						$pagearry['doc'][] = $ij;

						break;
				case 'w':
						$pagearry['doc'][] = $ij;

						break;
				case 'e':
						$pagearry['doc'][] = $ij;

						break;
				case 'p':
						$pagearry['doc'][] = $ij;

						break;
				default:
						echo $fieldindi = '';
						break;
			}
		}
		$pagecount=0;
		$img_pagecount = count($pagearry['img']);
		$pagecount = $img_pagecount;

		// Non Image Docs
		$doc_pagecount = count($pagearry['doc']);
		if($doc_pagecount>0){
			$pagecount = $pagecount+1;
		}
		// Non Image Docs

		$sql_approve_leads = select_query_json("select * from APPROVAL_REQUEST ar, approval_project pr, approval_process_type pt, APPROVAL_BUDGET_MODE bm, approval_priority ap
													where ar.APRCODE = pr.APRCODE and pt.PRJPRCS(+) = ar.PRJPRCS and bm.BUDCODE(+) = ar.BUDCODE and ap.PRICODE(+) = ar.PRICODE and
														ap.deleted(+) = 'N' and bm.deleted(+) = 'N' and pt.DELETED(+) = 'N' and ar.DELETED = 'N' and pr.DELETED in ('N', 'W') and
														ar.ARQCODE = '".$_REQUEST['reqid']."' and ar.ARQYEAR = '".$_REQUEST['year']."' and ar.ATCCODE = '".$_REQUEST['creid']."' and
														ar.ATYCODE = '".$_REQUEST['typeid']."'
													order by ar.ATCCODE, ar.ARQCODE, ar.ARQSRNO desc, ar.ATYCODE", 'Centra', 'TEST');
		$sql_hir = select_query_json("with t as (select apphead from approval_mdhierarchy
			where aprnumb in ('".$sql_approve_leads[0]['APRNUMB']."')
			and apphead in (1,2,3))select * from t
			pivot(count(APPHEAD)for(apphead) in (1 as KS,2 as PS,3 as AK))", 'Centra', 'TEST');
	?>
	<div class="page">
	<form role="form" id='frm_print_request' name='frm_print_request' action='' method='post' enctype="multipart/form-data">
	<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; background-color: #ffffff; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>

	<?php if($viewonly == 0) { ?><tr>
			<td style='width:70%; height:20px; font-weight:bold; vertical-align: top;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>
			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo 1; ?>/<?php echo $pagecount+1; ?></label><br>
				<? if($_REQUEST['rsrid'] != 1){ ?>
					<label>Approve on : <?=$systemdate?><? /* .(<?=$_SESSION['tcs_user']?>) */ ?></label> <!-- Current Date & Time -->
				<? }else{ ?>
					<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
				<? } ?>
			</td>
		</tr>
	<?php }else{ ?>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo 1; ?>/<?php echo $pagecount+1; ?></label>
			</td>
		</tr>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				&nbsp;
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
			</td>
		</tr>


	<?php } ?>





		<tr>
			<td colspan=2 style="height: 140px;">
			<table width='100%'>
				<tr>
					<td rowspan=3 style='width:20%; text-align:center;'>
						<? if($sql_reqid[0]['APPSTAT'] == 'A') {
							if($sql_reqid[0]['APPRMRK'] == '') { ?>
								  <img src='images/original.png' style='width:100px; height:100px;' border=0>
							<? } else { ?>
								  <img src='images/duplicate.png' style='width:100px; height:100px;' border=0>
							<? }
						 } else { ?>
							<img src='images/approval_process.png' style='width:100px; height:100px;' border=0>
						<? } ?>
					</td>
					<td style='width:60%; height:25px; padding-top:10px; padding-bottom:10px; text-align:center;'>
						<label style='color:#0088CC; font-weight:bold'><? /* <span style='font-family: "freehand471","Helvetica Neue",Helvetica,Arial; color:#ff0000; font-weight:normal; font-size: 32px;'>The Chennai Silks</span> */ ?><a target="_blank" href="index.php"><img src='images/logo.png' border="0"></a></label> <!-- Chennai Silks -->
					</td>
					<td rowspan=3 style='width:20%; text-align:center;'>&nbsp;
						<? 	/* if($sql_reqid[0]['ATYCODE'] == 1 or $sql_reqid[0]['ATYCODE'] == 6 or $sql_reqid[0]['ATYCODE'] == 7) { /* ?>
								<img src='images/payment.png' style='width:110px; height:110px;' border=0>
						<? } */ ?>
					</td>
				</tr>

				<tr>
					<td style='width:60%; height:20px; text-align:center;'>
						<label style='color:#0088CC; font-weight:bold'>Inter Office Correspondence</label> <!-- Chennai Silks -->
					</td>
				</tr>

				<tr>
					<td style='width:60%; height:20px; text-align:center;'>
						<label style='color:#000000; font-weight:bold'>Submitting for Approval</label> <!-- Submitting For -->
					</td>
				</tr>

				<tr>
					<td colspan=3 style='width:100%; height:20px; text-align:right;'>
						<label>Date : <?=$sql_reqid[0]['ADDDATE_DATE']?><br>
							Process Priority : <? if($sql_approve_leads[0]['PRICODE'] == '') {
									if($sql_approve_leads[0]['PRICODE'] != '') {
										$sql_process_priority = select_query_json("select * from approval_priority
																						where DELETED = 'N' and PRISRNO not in (4) and prisrno in (".$sql_approve_leads[0]['PRICODE'].")
																						order by PRISRNO Asc", 'Centra', 'TEST');
									} else {
										$sql_process_priority = select_query_json("select * from approval_priority
																						where DELETED = 'N' and PRISRNO not in (4) and prisrno in (3)
																						order by PRISRNO Asc", 'Centra', 'TEST');
									}
									switch ($sql_process_priority[0]['PRICODE']) {
										case 1:
											$clrcod = 'badge-danger';
											$clrcod1 = '#FF0000';
											break;
										case 2:
											$clrcod = 'badge-warning';
											$clrcod1 = '#D58B0A';
											break;

										default:
											$clrcod = 'badge-success';
											$clrcod1 = '#299654';
											break;
									} ?>
									<span class="badge <?=$clrcod?>" style="font-size:20px; background-color:<?=$clrcod1?>; font-weight:bold;"><? echo $sql_process_priority[0]['PRICODE']; ?></span>
								<? } else { ?>
									<span class="badge badge-success" style="font-size:20px; background-color:#299654; font-weight:bold;"><? echo $sql_approve_leads[0]['PRICODE']; ?></span>
								<? } ?>
						</label> <!-- Created Date -->
					</td>
				</tr>
			</table>
			</td>
		</tr>

		<?	$kind_attn = 'Sri. KS Sir';
			switch ($sql_approve_leads[0]['REQSTBY']) {
				case 21344:
					$kind_attn = 'Mr. S KAARTHI';
					break;

				default:
					$kind_attn = 'Sri. KS Sir';
					break;
			}

			$sql_approve_comnts = select_query_json("select req.*, regexp_replace(SubStr(req.REQESEN,1,4),'[0-9]','')||SubStr(req.REQESEN,5,100) REQESEN,
																to_char(INTPFRD,'dd-MON-yyyy hh:mi:ss AM') HISTIME, to_char(ADDDATE,'dd-MON-yyyy') RQADDDATE
															from APPROVAL_REQUEST req
															where DELETED = 'N' and ARQCODE = '".$_REQUEST['reqid']."' and ARQYEAR = '".$_REQUEST['year']."' and
																ATCCODE = '".$_REQUEST['creid']."' and ATYCODE = '".$_REQUEST['typeid']."' and ARQSRNO not in (1)
															order by ATCCODE, ARQCODE, ARQSRNO desc, ATYCODE", 'Centra', 'TEST');

			$sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST
													where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'", 'Centra', 'TEST');

			$exp1 = explode("!", $sql_reqid[0]['ADDEDUSER']);
			$empsect = $exp1['4'];
			$creator = $exp1['2'];
			$adduser = $exp1['0'];
			$creator_dept1 = explode(" ", $exp1['6']);
			$creator_dept = $creator_dept1[1]." ".$creator_dept1[2]." ".$creator_dept1[3]." ".$creator_dept1[4]." ".$creator_dept1[5];
			$count_attachment = $sql_approve_leads[0]['APPATTN'];
			$find_lead = '';
			unset($gm_cmnts); unset($hod_sign); unset($all_cmnts); $alaprcomments = 0;
			$folder_path = "approval_desk/digital_signature/";
			for($sql_approve_leadsi = 0; $sql_approve_leadsi < count($sql_approve_comnts); $sql_approve_leadsi++) {
				// $alaprcomments++;
				$find_lead = $sql_approve_comnts[$alaprcomments]['REQDESC'];
				$his_time[] = $sql_approve_comnts[$alaprcomments]['HISTIME'];
				$find_leads[] = $find_lead;
				
				// echo "<br>**".$sql_approve_comnts[$alaprcomments]['RQBYDES']."**".$find_lead."**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_approve_comnts[$alaprcomments]['APPFRWD']."**".$alaprcomments."**";
				switch($find_lead)
				{
					case 3: // Manager
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 137) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Cost Control */
									$cc_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($cc_cmnts);
									for($cci = 0; ($cci < count($cc_cmnts1)) and ($cc_cmnts1[0]['APPRMRK'] != ''); $cci++) {
										// if($cc_cmnts1[$cci]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$cc_cmnts[] = $cc_cmnts1[$cci]['APPRMRK'];
										// }
									}

									$cc_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$cc_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$cc_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$cc_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $cc_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$cc_empsrno.".png";
									$cc_sign = "ftp_image_view.php?pic=".$cc_empsrno.".png&path=".$folder_path."";
									$cc_desg = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									// $comnts_user[] = 'COST CONTROL ';
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Cost Control */
							} elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 188 or $sql_approve_comnts[$alaprcomments]['REQSTBY'] == 62762) { // echo "!!";
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') { // echo "@@";
									/* Manager */
									$srexc_mgr_name_audit = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc_audit = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno_audit = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate_audit = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $srexc_mgr_sign_audit = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$srexc_mgr_empsrno_audit.".png";
									$srexc_mgr_sign_audit = "ftp_image_view.php?pic=".$srexc_mgr_empsrno_audit.".png&path=".$folder_path."";
									$srexc_mgr_desg = "S-AUDIT<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;

								break;
								/* Manager */
							} else {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Manager */
									$srexc_mgr_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
									$srexc_mgr_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Manager */
							}

					case 92: // TCS BM
					case 67: // TJ / KTM BM
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* Branch Manager
								$mgr_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$mgr_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$mgr_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$mgr_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								} */

								$bm_name[]    = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$bm_desc[]    = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$bm_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$bm_dept[]    = $sql_approve_comnts[$alaprcomments]['REQ_ESEN'];
								$bm_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									$bm_sign[] = "ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									$bm_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".'BM';
								} else {
									$bm_sign[] = "";
									$bm_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".'BM';
								}
								/* Read HOD sigature from FTP */
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* Branch Manager */

					case 96: // SENIOR MANAGER
							/* S-Team Audit */
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 188 or $sql_approve_comnts[$alaprcomments]['REQSTBY'] == 62762) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* S-Team Audit Senior Manager */
									$srexc_mgr_name_audit = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$srexc_mgr_desc_audit = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$srexc_mgr_empsrno_audit = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$srexc_mgr_adddate_audit = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $srexc_mgr_sign_audit = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$srexc_mgr_empsrno_audit.".png";
									$srexc_mgr_sign_audit = "ftp_image_view.php?pic=".$srexc_mgr_empsrno_audit.".png&path=".$folder_path."";
									$srexc_mgr_desg = "S-AUDIT<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* S-Team Audit Senior Manager */
							} elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							} else {
								/* S-Team Audit */
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* S-Team Audit */
							}
							break;
							/* S-Team Audit */

					case 133: // Sr. Executive
					case 142: // Project Manager
					case 201: // Team Lead
					case 202: // Tech Lead
					case 134: // Executive
					case 14:  // CC Incharge
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113 and $sql_approve_comnts[$alaprcomments]['REQSTBY'] == 188) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
									$srexc_mgr_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							} else {
								/* Sr. Executive */
								$srexc_mgr_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$srexc_mgr_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$srexc_mgr_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$srexc_mgr_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								$srexc_mgr_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".$sql_approve_comnts[$alaprcomments]['REQDESN'];

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Sr. Executive */
							}

					/* case 134: // EXECUTIVE
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 118) // DB
							{
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									// Executive
									$exc_mgr_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$exc_mgr_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$exc_mgr_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$exc_mgr_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								// Executive
							} else {
								// Executive
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								// Executive
							}
							break; */

					case 132: // TCS HOD
					case 69: // KTM / TJ HOD
							// echo "<br>CAME".$sql_approve_comnts[$alaprcomments]['RQBYDES'];
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								// echo "<br>**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_reqid[0]['REQESEC']."**";
								//////////////// if($sql_approve_comnts[$alaprcomments]['REQESEC'] == $sql_reqid[0]['REQESEC']) {
								/* HOD */
								$hod_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$hod_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$hod_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$hod_dept[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN'];
								$hod_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									// echo "<br>HOD-".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>"."HOD";
								} else {
									// echo "<br>HOD+".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>"."HOD";
								}
								/* Read HOD sigature from FTP */
							}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
							///////////////////////// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* HOD */

					case 189: // DGM - TCS
					case 110: // DGM - TJ / KTM
							// echo "<br>CAME".$sql_approve_comnts[$alaprcomments]['RQBYDES'];
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								// echo "<br>**".$sql_approve_comnts[$alaprcomments]['REQESEC']."**".$sql_reqid[0]['REQESEC']."**";
								//////////////// if($sql_approve_comnts[$alaprcomments]['REQESEC'] == $sql_reqid[0]['REQESEC']) {
								/* DGM */
								$hod_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$hod_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$hod_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$hod_dept[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN'];
								$hod_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								/* Read HOD sigature from FTP */
								// $hod_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png";
								if(file_exists("ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png")) {
									// echo "<br>HOD/".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "ftp_image_view.php?pic=".$sql_approve_comnts[$alaprcomments]['REQSTBY'].".png&path=".$folder_path."";
									// echo "++",$sql_approve_comnts[$alaprcomments]['REQ_ESEN'],"++++++++++++++++++++++++++++++";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".'DGM';
								} else {
									// echo "**",$sql_approve_comnts[$alaprcomments]['REQ_ESEN'],"**";
									// echo "<br>HOD*".$sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$hod_sign[] = "";
									$hod_dsgn[] = $sql_approve_comnts[$alaprcomments]['REQ_ESEN']."<br>".'DGM';
								}
								/* Read HOD sigature from FTP */
							}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
							///////////////////////// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* DGM */

					case 150: // TRAINEE
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 113) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							}
							elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 95) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - S-Team */
									$steam_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$steam_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$steam_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$steam_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - S-Team */
							}
							elseif($sql_approve_comnts[$alaprcomments]['REQESEC'] == 950) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Section - Legal */
									$legal_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$legal_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$legal_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$legal_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Section - Legal */
							} else {
								/* Others */
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* Others */
							}
							break;

					case 75: // MANAGER TRAINEE
							if($sql_approve_comnts[$alaprcomments]['REQESEC'] == 137) {
								if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
									/* Cost Control */
									$cc_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($cc_cmnts);
									for($cci = 0; ($cci < count($cc_cmnts1)) and ($cc_cmnts1[0]['APPRMRK'] != ''); $cci++) {
										// if($cc_cmnts1[$cci]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$cc_cmnts[] = $cc_cmnts1[$cci]['APPRMRK'];
										// }
									}

									$cc_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$cc_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$cc_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$cc_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									/* Read Cost Control sigature from FTP */
									// $cc_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$cc_empsrno.".png";
									$cc_sign = "ftp_image_view.php?pic=".$cc_empsrno.".png&path=".$folder_path."";
									/* Read Cost Control sigature from FTP */
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									// $comnts_user[] = 'COST CONTROL ';
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;
								/* Cost Control */
							} else {
								/* MANAGER */
								$mgr_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$mgr_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$mgr_empsrno[] = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$mgr_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								/* MANAGER */
								break;
							}

					case 19: // GENERAL MANAGER
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* GM */
								$gm_name[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$gm_desc[] = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$gm_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$gm_adddate[] = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$gm_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
								for($gmi = 0; ($gmi < count($gm_cmnts1)) and ($gm_cmnts1[0]['APPRMRK'] != ''); $gmi++) {
									// if($gm_cmnts1[$gmi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$gm_cmnts[] = $gm_cmnts1[$gmi]['APPRMRK'];
									// }
								}

								/* Read GM sigature from FTP */
								// $gm_sign[] = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$gm_empsrno.".png";
								$gm_sign[] = "ftp_image_view.php?pic=".$gm_empsrno.".png&path=".$folder_path."";
								/* Read GM sigature from FTP */
							}

							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 168) {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									// $comnts_user[] = 'MANAGEMENT GM ';
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_gmuser[] = 'MANAGEMENT ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							} elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 452) {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									// $comnts_user[] = 'ADMIN GM ';
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_gmuser[] = 'ADMIN ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							} // if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
							else {
								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									// $comnts_user[] = 'OPERATION GM ';
									$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$comnts_gmuser[] = 'OPERATION ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
							}
							break;
							/* GM */

					case 165: // SR.GENERAL MANAGER
							if($sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q') {
								/* SR. GM */
								$srgm_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$srgm_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$srgm_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$srgm_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$srgm_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
								unset($srgm_cmnts);
								for($srgmi = 0; ($srgmi < count($srgm_cmnts1)) and ($srgm_cmnts1[0]['APPRMRK'] != ''); $srgmi++) {
									// if($srgm_cmnts1[$srgmi]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$srgm_cmnts[] = $srgm_cmnts1[$srgmi]['APPRMRK'];
									// }
								}

								/* Read SR. GM sigature from FTP */
								// $srgm_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$srgm_empsrno.".png";
								$srgm_sign = "ftp_image_view.php?pic=".$srgm_empsrno.".png&path=".$folder_path."";
								/* Read SR. GM sigature from FTP */
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								// $comnts_user[] = 'SR GM ';
								$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
							/* SR. GM */

					case 78: // ADMINISTATIVE OFFICER
					case 195: // CEO & DIRECTOR
							$ceo_available = 0;
							// echo "**".$sql_approve_comnts[$alaprcomments]['REQSTBY']."**".$sql_approve_comnts[$alaprcomments]['APPFRWD']."**";
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 21344 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
							{
								/* AK */
								$ak_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$ak_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
								$ak_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
								$ak_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

								$ak_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																	where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																		ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																		ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																	order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
								unset($ak_cmnts);
								for($aki = 0; ($aki < count($ak_cmnts1)) and ($ak_cmnts1[0]['APPRMRK'] != ''); $aki++) {
									// if($ak_cmnts1[$aki]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$ak_cmnts[] = $ak_cmnts1[$aki]['APPRMRK'];
									// }
								}

								/* Read AK sigature from FTP */
								// $ak_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$ak_empsrno.".png";
								/////////////////// $ak_sign = "ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // OPEN
								$ak_sign = "ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // CLOSE
								/* Read AK sigature from FTP */
								/* AK */
							} else {
								$ceo_available = 1;
							}

							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = 'S KAARTHI ';
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;

					case 9: // DEFAULT / MD
					case 193: // MANAGING DIRECTOR / MD
					case 194: // DIRECTOR / MD
							if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 21344) {
								$ceo_available = 0;
								// echo "**".$sql_approve_comnts[$alaprcomments]['REQSTBY']."**".$sql_approve_comnts[$alaprcomments]['APPFRWD']."**";
								if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 21344 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
								{
									/* AK */
									$ak_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$ak_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$ak_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$ak_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									$ak_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($ak_cmnts);
									for($aki = 0; ($aki < count($ak_cmnts1)) and ($ak_cmnts1[0]['APPRMRK'] != ''); $aki++) {
										// if($ak_cmnts1[$aki]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$ak_cmnts[] = $ak_cmnts1[$aki]['APPRMRK'];
										// }
									}

									/* Read AK sigature from FTP */
									// $ak_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$ak_empsrno.".png";
									/////////////////// $ak_sign = "ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // OPEN
									$ak_sign = "ftp_image_view.php?pic=".$ak_empsrno.".png&path=".$folder_path.""; // CLOSE
									/* Read AK sigature from FTP */
									/* AK */
								} else {
									$ceo_available = 1;
								}

								// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
									$addcmnts++;
									$comnts_user[] = 'S KAARTHI ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
								// }
								$alaprcomments++;
								$row_inc[] = $alaprcomments;
								break;

							} else {
								$cao_available = 0;
								if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 43400 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
								{
									/* PS Madam */
									$ps_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$ps_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$ps_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$ps_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									$ps_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($ps_cmnts);
									for($psi = 0; ($psi < count($ps_cmnts1)) and ($ps_cmnts1[0]['APPRMRK'] != ''); $psi++) {
										// if($ps_cmnts1[$psi]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$ps_cmnts[] = $ps_cmnts1[$psi]['APPRMRK'];
										// }
									}

									/* Read PS sigature from FTP */
									// $ps_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$ps_empsrno.".png";
									$ps_sign = "ftp_image_view.php?pic=".$ps_empsrno.".png&path=".$folder_path."";
									/* Read PS sigature from FTP */

									// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$comnts_user[] = 'PS MADAM ';
										$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
									// }
									$alaprcomments++;
									$row_inc[] = $alaprcomments;
									/* PS Madam */
								} elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 43400) {
									$ps_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($ps_cmnts);
									for($psi = 0; ($psi < count($ps_cmnts1)) and ($ps_cmnts1[0]['APPRMRK'] != ''); $psi++) {
										// if($ps_cmnts1[$psi]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$ps_cmnts[] = $ps_cmnts1[$psi]['APPRMRK'];
										// }
									}
									$addcmnts++;
									$comnts_user[] = 'PS MADAM ';
									$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
									$alaprcomments++;
									$cao_available = 1;
								}

								$coo_available = 0;
								if($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 20118 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'I' || $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'P' || $sql_approve_comnts[$alaprcomments]['APPFRWD'] == 'Q')
								{
									$alaprcomments++;
									$row_inc[] = $alaprcomments;
								}
								elseif($sql_approve_comnts[$alaprcomments]['REQSTBY'] == 20118 && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'I' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'P' && $sql_approve_comnts[$alaprcomments]['APPFRWD'] != 'Q')
								{
									/* KS Sir */
									$ks_name = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
									$ks_desc = $sql_approve_comnts[$alaprcomments]['REQDESC'];
									$ks_empsrno = $sql_approve_comnts[$alaprcomments]['REQSTBY'];
									$ks_adddate = $sql_approve_comnts[$alaprcomments]['RQADDDATE'];

									$ks_cmnts1 = select_query_json("select APPRMRK from APPROVAL_REQUEST ar
																		where ARQCODE = '".$sql_reqid[0]['ARQCODE']."' and ATYCODE = '".$sql_reqid[0]['ATYCODE']."' and
																			ATMCODE = '".$sql_reqid[0]['ATMCODE']."' and APMCODE = '".$sql_reqid[0]['APMCODE']."' and
																			ATCCODE = '".$sql_reqid[0]['ATCCODE']."' and REQSTBY = '".$sql_approve_comnts[$alaprcomments]['REQSTBY']."'
																		order by ARQCODE, ARQSRNO asc, ATYCODE, ATMCODE, APMCODE, ATCCODE, APPRFOR", 'Centra', 'TCS');
									unset($ks_cmnts);
									for($ksi = 0; ($ksi < count($ks_cmnts1)) and ($ks_cmnts1[0]['APPRMRK'] != ''); $ksi++) {
										// if($ks_cmnts1[$ksi]['APPRMRK'] != 'APPROVED') {
											$addcmnts++;
											$ks_cmnts[] = $ks_cmnts1[$ksi]['APPRMRK'];
										// }
									}

									/* Read KS sigature from FTP */
									// $ks_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$ks_empsrno.".png";
									$ks_sign = "ftp_image_view.php?pic=".$ks_empsrno.".png&path=".$folder_path."";
									/* Read KS sigature from FTP */

									// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
										$addcmnts++;
										$comnts_user[] = 'KS SIR ';
										$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
									// }
									$alaprcomments++;
									$row_inc[] = $alaprcomments;
									/* KS Sir */
								} else {
									$coo_available = 1;
								}
							}
							break;
					default: // OTHERS
							// echo "---------".$sql_approve_comnts[$alaprcomments]['RQBYDES']."---------";
							// if($sql_approve_comnts[$alaprcomments]['APPRMRK'] != 'APPROVED') {
								$addcmnts++;
								$comnts_user[] = $sql_approve_comnts[$alaprcomments]['RQBYDES'];
								$comnts_rmrk[] = $sql_approve_comnts[$alaprcomments]['APPRMRK'];
							// }
							$alaprcomments++;
							$row_inc[] = $alaprcomments;
							break;
				}
			} ?>
		<tr>
		<td colspan=2 style='width:100%; vertical-align:top; text-align:left;'>
			<table border=0 style='width:100%; max-width: 773px; '>

				<tr><td>
				<? /* <table border=0 style='width:100%; min-height:660px; height:auto;'> */ ?>
				<table border=0 style='width:100%;'>
				<tr style='min-height:25px !important; line-height:25px !important;'>
					<td colspan=2 style='width:100%; height:20px; text-align:left;'>
						<label>Good Day Sir,</label> <!-- Good Day Sir, -->
					</td>
				</tr>
				<?
				$sql_period = select_query_json("select accyear from APPROVAL_PRD_QUOTATION_PERIOD where aprnumb='".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST");
				//echo('<pre>');
				//print_r($sql_period);
				//echo('</pre>');
				?>
				<? /* <tr style='min-height:25px !important; line-height:25px !important;'>
					<td style='width:20%; text-align:left;'>
						<label style=' font-size:16px; font-weight:bold'>Kind Attn.</label> <!-- Kind Attn. -->
					</td>
					<td style='width:80%; text-align:left;'>
						: <label style=' font-size:16px; font-weight:bold'><? echo $kind_attn; ?></label> <!-- Kind Attn. -->
					</td>
				</tr> */ ?>

				<tr style='min-height:20px; line-height:20px;'>
					<td style='font-size:16px; font-weight:bold; width:20%; text-align:left;'>
						<label>Subject</label> <!-- Approval Listings -->
					</td>
					<td style='width:80%; text-align:left;'>
						<label>: <span style=" font-size: 18px; font-weight: bold;" class="blue_highlight"><?=$sql_reqid[0]['APMASTER'].$sql_reqid[0]['DYNSUBJ'].$sql_reqid[0]['TXTSUBJ']?>( <?=$sql_period[0]['ACCYEAR'];?> )</span></label> <!-- Approval Listings -->
					</td>
				</tr>

				<tr style='min-height:25px; line-height:25px;'>
					<td style='width:20%; padding-top:5px; text-align:left;'>
						<label style=' font-size:16px; font-weight:bold'>Branch / Project</label> <!-- Project Name -->
					</td>
					<td style='width:80%; padding-top:5px; text-align:left;'>
						<table style="width: 100%">
						<tr style='min-height:20px; line-height:20px;'>
							<td style='width:70%; text-align:left;'>
								<label>: <label style=' font-size:16px; font-weight:bold;'><?=$sql_reqid[0]['BRANCH']?> / </label><label style=' font-size:16px; font-weight:bold;border: 0px solid #00a1ff;padding: 3px;'><?=$sql_approve_leads[0]['APRCODE']." - ".$sql_approve_leads[0]['APRNAME']?></label> [ <?=$sql_approve_leads[0]['PRSTYPE'];?> ] <!-- Project Name -->
							</td>

							
						</tr>
						</table>
					</td>
				</tr>
				<?$sql_expname = select_query_json("sELECT apr.txtsubj,DPA.DEPCODE,DPA.EXPSRNO,DPA.EXPNAME,APQF.TARNUMB,decode(tar.ptdesc,'-',DPA.depname,tar.ptdesc) tarname FROM approval_PRODUCT_QUOTATION_FIX APQF,DEPARTMENT_ASSET DPA,approval_request apr,non_purchase_target tar WHERE DPA.DEPCODE=APQF.DEPCODE and tar.depcode=DPA.depcode and APQF.tarnumb=tar.ptnumb and trunc(APQF.adddate) between trunc(ptfdate) and trunc(pttdate) AND apr.aprnumb=apqf.aprnumb and APQF.APRNUMB='".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');?>
				<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Exp. Head / Department</label> <!-- Exp. Head / Department -->
						</td>
						<td style='width:80%; text-align:left;'>
							<table style="width: 100%">
							<tr style='min-height:20px; line-height:20px;'>
								<td style='width:100%; text-align:left;'>
									<label>: <? echo $sql_expname[0]['EXPNAME'];?> ( <? echo $sql_expname[0]['TARNUMB'];?>  - <? echo $sql_expname[0]['TARNAME'];?> ) </label> <!-- Exp. Head / Department -->
								</td>
								<? /* <td style='width:15%; text-align:left;'>
									<label>Approval Mode</label> <!-- Specification -->
								</td>
								<td style='width:35%; text-align:left;'>
									<label>: <? echo $aptype_display; ?></label> <!-- Specification -->
								</td> */ ?>
							</tr>
							</table>
						</td>
					</tr>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Specific Subject</label> <!-- Exp. Head / Department -->
						</td>
						<td style='width:80%; text-align:left;'>
							<table style="width: 100%">
							<tr style='min-height:20px; line-height:20px;'>
								<td style='width:100%; text-align:left;'>
									<label>: <? echo $sql_expname[0]['TXTSUBJ'];?> </label> <!-- Exp. Head / Department -->
								</td>
								<? /* <td style='width:15%; text-align:left;'>
									<label>Approval Mode</label> <!-- Specification -->
								</td>
								<td style='width:35%; text-align:left;'>
									<label>: <? echo $aptype_display; ?></label> <!-- Specification -->
								</td> */ ?>
							</tr>
							</table>
						</td>
					</tr>
				<? if($sql_reqid[0]['APRQVAL'] > 0) { ?>
					
				<? } else { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Approval Mode</label> <!-- Specification -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <?=$aptype_display?></label> <!-- Specification -->
						</td>
					</tr>
				<? } ?>

				<? if($sql_reqid[0]['RELAPPR'] != '') { // echo "WRONG"; ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Related Approval Nos</label> <!-- Related Approval Nos -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>:
							<? 	$sql_rlapr = explode(",", $sql_reqid[0]['RELAPPR']);
								for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
									$sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
																	where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", 'Centra', 'TEST'); ?>
									<a target="_blank" href='view_pending_approval.php?action=view&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:normal; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
								<? } ?></label> <!-- Related Approval Nos -->
						</td>
					</tr>
				<? } ?>

				<? $appr_againstno = 0;
				if($sql_reqid[0]['AGNSAPR'] != '') { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Against Approval No</label> <!-- Against Approval No -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>:
							<? 	$sql_rlapr = explode(",", $sql_reqid[0]['AGNSAPR']);
								for ($rlapri = 0; $rlapri < count($sql_rlapr); $rlapri++) {
									$sql_apr = select_query_json("select ARQCODE, ARQYEAR, ATCCODE, ATYCODE, APRNUMB from APPROVAL_REQUEST
																		where aprnumb like '".trim($sql_rlapr[$rlapri])."' and ARQSRNO = 1", "Centra", "TEST");
									if(count($sql_apr) > 0) { $appr_againstno = 1; } ?>
									<a class="red_highlight" target="_blank" href='print_request.php?action=print&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&agnpr=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>' title='View' alt='View' style='color:<?=$clr?>; font-weight:bold; font-size:10px;'><? echo $sql_apr[0]['APRNUMB']; ?></a><br>
								<? } ?></label> <!-- Against Approval No -->
						</td>
					</tr>
				<? } ?>

				<?  if(count($sql_approve_leads) > 0 && $sql_reqid[0]['APRQVAL'] > 0) { ?>
				<tr style='min-height:20px; line-height:20px;'>
					<td style='width:20%; text-align:left;'>
						<label>Budget Mode</label> <!-- Budget Mode -->
					</td>
					<td style='width:80%; text-align:left;'>
						<table style="width: 100%">
						<tr style='min-height:20px; line-height:20px;'>
							<td style='width:100%; text-align:left;'>
								<label>: <span style="font-size: 14px;<? if ($aptype_display == "NEW PROPOSAL" or $aptype_display == "EXTRA BUDGET") { ?> font-weight:bolder;<?}?>" <? if ($aptype_display == "NEW PROPOSAL" or $aptype_display == "EXTRA BUDGET") { ?> class="red_highlight" <?}?> ><? echo $aptype_display; ?></span> / <span style="font-size: 12px; font-weight: bold; " class="blue_highlight"><? echo $sql_approve_leads[0]['BUDNAME']; if($sql_reqid[0]['APRQVAL'] > 0) { ?></span> <span style="font-size: 10px;">[ Target NO - <b><? echo $sql_tarbalance[0]['TARNUMB']." - ".$sql_tarbalance[0]['TARDESC']; ?></b> ]</span><? } if($sql_reqid[0]['ATYCODE'] == 7) { ?><br><b>Reserved Budget Balance against Expense Head [ <span class="clrblue"><?=$sql_tarbalance[0]['EXPHEAD']?></span> ]</b> &#8377;
									<? 	$target_balance = select_query_json("select sum(distinct nvl(sm.BUDVALUE, 0)) BUDVALUE, (sum(distinct nvl(sm.APPVALUE, 0)) +
																						sum(distinct nvl(tm.APPRVAL, 0))) APPVALUE, (sum(distinct nvl(sm.BUDVALUE, 0)) -
																						sum(distinct nvl(sm.APPVALUE, 0)) - sum(distinct nvl(tm.APPRVAL, 0))) RESVALUE
																					from budget_planner_head_sum sm, approval_budget_planner_temp tm
																					where sm.BUDYEAR=tm.APRYEAR AND sm.BRNCODE=tm.BRNCODE AND sm.EXPSRNO=tm.EXPSRNO and tm.deleted = 'N'
																						and sm.BRNCODE=".$sql_reqid[0]['BRNCODE']." and sm.BUDYEAR = '".$hidapryear."' and
																						sm.EXPSRNO = ".$sql_reqid[0]['EXPSRNO']."", 'Centra', 'TEST');
										// Query for find the Reserved Budget balance
										$balance = 0;
										if($target_balance[0]['RESVALUE'] > 0) {
											$balance = $target_balance[0]['RESVALUE'];
		                                    $expld = explode(".", $balance);
		                                    $mny = moneyFormatIndia($expld[0]);
		                                    if($expld[1] > 0) {
		                                        $mny = $mny.".".$expld[1];
		                                    } else{
		                                        $mny = $mny.".00";
		                                    }
										} else {
											$balance = 0;
		                                    $mny = 0;
										}
										echo "<b style='padding: 5px;font-size:16px;color:#FF0000;border: 1px solid #a0a0a0;'>".moneyFormatIndia($balance)."</b>";
									}
									?>
								</label> <!-- Budget Mode -->
							</td>
							<? /* <td style='width:15%; text-align:left;'>
								<label>Convert Mode</label> <!-- Convert Mode -->
							</td>
							<td style='width:35%; text-align:left;'>
								<label>: <? switch ($sql_reqid[0]['CNVRMOD']) {
											case 'REQUIREMENT':
												echo "PO BASED / REQUIREMENT";
												break;

											case 'SELFCHEQUE':
												echo "SELF CHEQUE";
												break;

											case 'CASH':
												echo "CASH";
												break;

											default:
												echo "PO BASED / REQUIREMENT";
												break;
										} ?></label> <!-- Convert Mode -->
							</td> */ ?>
						</tr>
						</table>
					</td>
				</tr>
				<? } ?>
				<?// advt audio and video?>

				<?
				 $sql_descode=select_query_json("select distinct PUBNAME , CAMPDES , SPONSTA from av7_ro_summ where APRNUMB = '".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST");
				if(count($sql_descode) > 0) { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Channel Name</label> <!-- Budget Mode -->
						</td>
						<td style='width:80%; text-align:left;'>
							: <?echo $sql_descode[0]['PUBNAME'];?>
						</td>
					</tr>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Desc of Campaign</label> <!-- Budget Mode -->
						</td>
						<td style='width:80%; text-align:left;'>
							: <?echo strtoupper($sql_descode[0]['CAMPDES']);?>
						</td>
					</tr>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Sponser Status</label> <!-- Budget Mode -->
						</td>
						<td style='width:80%; text-align:left;'>
							: <?echo strtoupper($sql_descode[0]['SPONSTA']);?>
						</td>
					</tr>
					<?
					}
					?>
					<?//advt audio and video?>



				<? /* <tr style='min-height:20px; line-height:20px;'>
					<td style='width:20%; text-align:left;'>
						<label>Approval Listings</label> <!-- Approval Listings -->
					</td>
					<td style='width:80%; text-align:left;'>
						<label>: <span style=" font-size: 13px; font-weight: bold;" class="blue_highlight"><?=$sql_reqid[0]['APMASTER']?></span></label> <!-- Approval Listings -->
					</td>
				</tr> */ ?>

				<? if($sql_reqid[0]['APRQVAL'] > 0 and $sql_tarbalance[0]['SUPNAME'] != '' and count($sql_prdlist) <= 0) { ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Supplier Details</label> <!-- Supplier Details -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <b><? if($sql_tarbalance[0]['SUPCODE'] != '') { echo $sql_tarbalance[0]['SUPCODE']." - "; } echo $sql_tarbalance[0]['SUPNAME']." - ".$sql_tarbalance[0]['SUPCONT']; ?></b></label> <!-- Supplier Details -->
						</td>
					</tr>
				<? } ?>

				<? 	$show_supplierlist = 0;
					if(count($sql_reqd) > 0) { $show_supplierlist = 1; }
					if($_REQUEST['action'] == '') { $show_supplierlist = 1; }

				if($show_supplierlist == 0) { ?>
				<tr style='min-height:20px; line-height:20px;'>
					<td colspan=2 style='width:100%; text-align:left;'></td>
				</tr>
				<? } ?>

				<? /* <tr style='min-height:20px; line-height:20px;'>
					<td style='width:20%; text-align:left;'>
						<label>Process Priority</label> <!-- Process Priority -->
					</td>
					<td style='width:80%; text-align:left;'>
						<label>: <b class="red_highlight"><? if($sql_approve_leads[0]['PRICODE'] == '') { $sql_process_priority = select_query_json("select * from approval_priority where DELETED = 'N' and PRISRNO not in (4) and prisrno in (3) order by PRISRNO Asc"); echo $sql_process_priority[0]['PRICODE']." - ".$sql_process_priority[0]['PRINAME']; } else { echo $sql_approve_leads[0]['PRICODE']." - ".$sql_approve_leads[0]['PRINAME']; } ?></b></label> <!-- Process Priority -->
					</td>
				</tr> */ ?>


				<? if($sql_reqid[0]['APMCODE'] == 802) {
					$sql_proj = select_query_json("select * from approval_project where deleted = 'W' order by APRCODE desc", 'Centra', 'TEST'); ?>
					<tr style='min-height:20px; line-height:20px;'>
						<td style='width:20%; text-align:left;'>
							<label>Project ID & Name</label> <!-- Project ID & Name -->
						</td>
						<td style='width:80%; text-align:left;'>
							<label>: <b class="blue_highlight" style="font-size: 16px;">
									<? 	if(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[0]['APRCODE'])) { echo $sql_proj[0]['APRCODE']." - ".$sql_proj[0]['APRNAME']; }
										elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[1]['APRCODE'])) { echo $sql_proj[1]['APRCODE']." - ".$sql_proj[1]['APRNAME']; }
										elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[2]['APRCODE'])) { echo $sql_proj[2]['APRCODE']." - ".$sql_proj[2]['APRNAME']; }
										elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[3]['APRCODE'])) { echo $sql_proj[3]['APRCODE']." - ".$sql_proj[3]['APRNAME']; }
										elseif(strcmp($sql_approve_leads[0]['APPRDET'], $sql_proj[4]['APRCODE'])) { echo $sql_proj[4]['APRCODE']." - ".$sql_proj[4]['APRNAME']; } ?>
										</b></label> <!-- Project ID & Name -->
						</td>
					</tr>
				<? } ?>



				<? /* <!-- Approval Type & Responsible Person -->
				<tr style='min-height:20px; line-height:20px;'>
					<td style='width:20%; text-align:left;'>
						<label>Responsible Person</label> <!-- Responsible Person -->
					</td>
					<td style='width:80%; text-align:left;'>
						<table style="width: 100%">
						<tr style='min-height:20px; line-height:20px;'>
							<td style='width:55%; text-align:left;'>
								<label>: <span style="font-weight: bold;">
										 <? $sql_usr = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																			from employee_office emp, empsection sec,
																				designation des, employee_salary sal
																			where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																				and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
																		union
																			select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																			from employee_office emp, new_empsection sec,
																				new_designation des, employee_salary sal
																			where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																				and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
																			order by EMPCODE");
											echo $sql_usr[0]['EMPCODE']." - ".$sql_usr[0]['EMPNAME']; ?></span></label> <!-- Responsible Person -->
							</td>

							<? if($sql_reqid[0]['APRQVAL'] > 0 and $sql_reqid[0]['APPTYPE'] != '') { ?>
								<td style='width:15%; text-align:left;'>
									<label>Approval Type</label> <!-- Approval Type -->
								</td>
								<td style='width:30%; text-align:left;'>
									<label>: <span style=" font-size: 14px; font-weight: bold;" <? if($sql_reqid[0]['APPTYPE'] == 'ASSET') { ?> class="green_highlight" <? } else { ?> class="red_highlight" <? } ?>><? echo $sql_reqid[0]['APPTYPE']; ?></span></label> <!-- Approval Type -->
								</td>
							<? } ?>
						</tr>
						</table>
					</td>
				</tr> */ ?>
				<!-- Approval Type & Responsible Person -->
				<tr style='height:20px;'><td></td></tr>


				<tr style='min-height:20px !important; max-width: 773px; line-height:20px !important;'>
					<td colspan=2>
						<table border=0 width='100%' style='max-width: 773px; border: 1px solid #0088CC; min-height: 70px; height:auto; padding:3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;'>
							<tr style='min-height:30px; vertical-align:top; line-height:30px; font-size: 11px;'>
								<td style='width:20%; text-align:left;'>
									<label>Details : </label> <!-- Details -->
								</td>
							</tr>

							<tr style=''>
								<td style='width:100%; text-align:left;'>
									<label><? // echo $sql_approve_leads[0]['APPRDET'];
										if($sql_approve_leads[0]['APPRFOR'] == '1') {
	                                        $filepathname = $sql_approve_leads[0]['APPRSUB'];
	                                        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.":5022/approval_desk/text_approval_source_test/".$filepathname;
	                                        $handle = fopen($filename, "r") or die("The content might not be available!. Contact Admin - ". $sql_approve_leads[0]['APPRSUB']);
	                                        $contents = fread($handle, filesize($filename));
	                                        fclose($handle);
	                                        echo $contents;
	                                    } else {
	                                        echo $sql_approve_leads[0]['APPRDET'];
	                                    } ?></label> <!-- Details -->
								</td>
							</tr>

							<tr><td colspan='6'>
								<table style="width:100%">
									<? $sql_descode=select_query_json("select distinct ATYCODE , TARNUMB , APMNAME , TOPCORE , SUBCORE , ENTSRNO from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."'", "Centra", "TCS");
									if(count($sql_descode) > 0) { ?>
									<thead>
										<tr>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">S.NO</th>
											<th  class="colheight" style="padding:5px 0px;width: 20%;background:#007cff;color:#fff">TYPE OF SUBMISSION</th>
											<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">TARGET NUMBER</th>
											<th  class="colheight" style="padding:5px 0px;width: 20%;background:#007cff;color:#fff">SUBJECT</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">TOP CORE</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">SUB CORE</th>
											<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">EMPLOYEE</th>
										</tr>
									</thead>
									<tbody>

										<?
										}
											$sno = '';
											foreach($sql_descode as $sectionrow) {
												$sno = $sno + 1;
										?>
											<tr class="active">
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?echo $sno;?></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
												$sql_descodee=select_query_json("SELECT ATYCODE , ATYNAME FROM APPROVAL_TYPE WHERE DELETED = 'N' and ATYCODE = '".$sectionrow['ATYCODE']."' ORDER BY ATYCODE", "Centra", "TCS");
													foreach($sql_descodee as $sectionroww) {
														$id = $sectionroww['ATYCODE'];
														if ($id == '1') {
															echo ltrim($sectionroww['ATYNAME'],"FIXED ");
														}else {
															echo $sectionroww['ATYNAME'];
														}
													}
												?></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
												if ($sectionrow['TARNUMB'] == '0') {
													echo "- NILL -";
												}
												else {
													$sql_descode=select_query_json("select distinct round(tarnumb) tarnumb, round(bpl.tarnumb)||' - '||decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
															from budget_planner_branch bpl, non_purchase_target tar, department_asset Dep
															where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode and tar.brncode=bpl.brncode and tar.brncode=bpl.brncode and tar.PTNUMB=bpl.TARNUMB and bpl.TARYEAR=17 and bpl.TARMONT=4 and (bpl.tarnumb>8000 or bpl.tarnumb in (7632, 7630))
															group by bpl.tarnumb, bpl.depcode, bpl.brncode, tar.ptdesc, dep.depname order by Depname", "Centra", "TCS");
													foreach($sql_descode as $sectionroww) {
														if ($sectionrow['TARNUMB'] == $sectionroww['TARNUMB']) {
															echo $sectionroww['DEPNAME'];
														}
													}
												}?></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?echo $sectionrow['APMNAME'];?></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
												if ($sectionrow['TOPCORE'] == '0') {
													echo "- NILL -";
												}else {
													$sql_descode=select_query_json("SELECT ATCNAME from APPROVAL_TOPCORE where ATCCODE = '".$sectionrow['TOPCORE']."' and DELETED = 'N' ORDER BY ATCSRNO", "Centra", "TCS");
													foreach($sql_descode as $sectionrowe) {
														echo $sectionrowe['ATCNAME'];
													}
												}
												?></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center"><?
												if ($sectionrow['SUBCORE'] == '0') {
													echo "- NILL -";
												}else {
													$sql_descode=select_query_json("select distinct sec.esecode, substr(sec.esename, 4, 25) esename
															from APPROVAL_master apm, APPROVAL_topcore atc, empsection sec
															where ESECODE = '".$sectionrow['SUBCORE']."' and sec.esecode = apm.subcore and apm.topcore = atc.atccode and apm.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N'
															order by ESENAME asc", "Centra", "TCS");
													foreach($sql_descode as $sectionroww) {
														echo $sectionroww['ESENAME'];
													}
												}
												?></td>

												<td class="colheight" style="padding: 0px;width: 10%;text-align:center">
													<?
														$sql_descode=select_query_json("select  EMPCODE , EMPNAME from approval_subject_add where APRNUMB = '".$sql_reqid[0]['APRNUMB']."' AND ENTSRNO = '".$sectionrow['ENTSRNO']."' ORDER BY BRNHDSR", "Centra", "TCS");
														foreach($sql_descode as $sectionrow_emp) {
															if ($sectionrow_emp['EMPCODE'] == '0') {
																echo "- NILL -";
															}else {
																echo $sectionrow_emp['EMPCODE']. " - " .$sectionrow_emp['EMPNAME'];
															}
															?>
																<BR>
															<?
														}
													?></td>
											</tr>
											<?

												}
											?>
									</tbody>
								</table>
								<table style="width:100%">
									<?
									$sql_descode=select_query_json("select distinct rd.FR_TIME , rd.TO_TIME , rd.RO_FCT , rd.RO_DURA , rd.COSTPSE , rd.RATEPSE , rd.NETAMNT , rd.RO_NUMB , rd.TOTAMNT , rd.GSTAMNT , rs.CGST , rs.SGST , rs.IGST from av7_ro_summ rs , av7_ro_det rd where rs.RO_NUMB = rd.RO_NUMB and rs.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST");
									if(count($sql_descode) > 0) { ?>
									<thead>
										<tr>
											<th  class="colheight" style="padding:5px 0px;width: 5%;background:#007cff;color:#fff">S.NO</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">DATE</th>
											<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">TIME</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">SPOT</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">DURATION (SEC)</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">COST/10 SEC (RS)</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">RATE / SPOT</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">TOTAL AMOUNT</th>
											<th  class="colheight" style="padding:5px 0px;width: 10%;background:#007cff;color:#fff">GST <br>(<?
												if ($sql_descode[0]['IGST'] == '0') {
													echo "CGST : ".$sql_descode[0]['CGST']." SGST : ".$sql_descode[0]['SGST'];
												}else {
													echo "IGST : ".$sql_descode[0]['IGST'];
												} ?>%)
											</th>
											<th  class="colheight" style="padding:5px 0px;width: 15%;background:#007cff;color:#fff">NET AMOUNT</th>
										</tr>
									</thead>
									<tbody>
										<?
											$sno = '';
											foreach($sql_descode as $sectionrow) {
												$sno = $sno + 1;
										?>
											<tr class="active">
												<td class="colheight" style="padding: 0px;width: 5%;text-align:center"><?echo $sno;?></td>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center">
													<?
														$sql_descode=select_query_json("select RO_DATE from av7_ro_det where RO_NUMB = '".$sectionrow['RO_NUMB']."' ORDER BY RO_SRNO", "Centra", "TEST");
														foreach($sql_descode as $sectionrow_emp) {
																echo date_format(date_create($sectionrow_emp['RO_DATE']), "d-m-Y");
															?>
																<BR>
															<?
														}
													?>
												</th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['FR_TIME']. " - " .$sectionrow['TO_TIME'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['RO_FCT'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['RO_DURA'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['COSTPSE'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['RATEPSE'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['TOTAMNT'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['GSTAMNT'];?></th>
												<th  class="colheight" style="padding: 0px;width: 10%;text-align:center"><? echo $sectionrow['NETAMNT'];?></th>

											</tr>

											<?

												}

											?>

											<tr>
												<td colspan="9" class="colheight" style="padding: 0px;width: 10%;text-align:center;text-align:right"><b>TOTAL : </b></td>
												<td class="colheight" style="padding: 0px;width: 10%;text-align:center">
													 <label style=' font-size:14px; font-weight:bold;'><? if($sql_approve_leads[0]['APPFVAL'] == 0)
														{
														if($app_val != "")
														{
															echo $tot[$app_val];
														}else{
														echo "--NIL--";
														}
													} else { $apprvl = moneyFormatIndia($sql_approve_leads[0]['APPFVAL']); ?><img src='images/rupee.png' width=8 height=12 border=0> <?=$apprvl?></label> <label class='cls_rupees'><?")</label>"; } ?>
													 <!-- Approved Value -->
												</td>
											</tr>
											<?
											}
											?>
									</tbody>
								</table>
								<table class="monthyr_wrap" style='width:100%; line-height:22px;'>
								<tr><td width="25%"></td><td width="25%"></td><td width="25%"></td><td width="25%"></td></tr>
								<tr style='border:1px solid #0088CC; width:100%;'>
								<?
									if(count($sql_prdlist) > 0) {
										$edtvl = 0;
										// $edtvl = 1;
										$displaynone = ' display: none; ';
									} else {
										$edtvl = 1;
										$displaynone = '';
									}

									if($edtvl == 1) {
										// echo "select * from approval_budget_planner where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO";
										if($tmporlive == 0) {
											if($_SESSION['tcs_user'] == 1118) {
												$sql_plan = select_query_json("select * from approval_budget_planner_temp
																					where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
											} else {
												$sql_plan = select_query_json("select * from approval_budget_planner_temp
																					where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
											}
										} else {
											$sql_plan = select_query_json("select * from approval_budget_planner
																					where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
										}

										// $sql_plan = select_query_json("select * from approval_budget_planner where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
										$ijk = 0;
										for($plani = 0; $plani < count($sql_plan); $plani++) {
											if($sql_plan[$plani]['APPRVAL'] > 0) { $ijk++;
												$total_amt = $sql_plan[$plani]['APPRVAL'] + $sql_plan[$plani]['RESVALU'];
												if($ijk == 1) { ?>
													<td width="25%" style='border:1px solid #0088CC; padding: 2px;'><table style='width:100%;'>
												<? } ?>
													<tr style='border:1px solid #0088CC; width:100%;'>
														<td width="40%" style='text-align:right;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRPRID']?>'><span><? $vlmn = explode(",", $sql_plan[$plani]['APRMNTH']); echo $vlmn[0]."-".$vlmn[1]; ?></span> : </td>
														<td width="58%" style='text-align:right;'><? if($_REQUEST['action'] == 'edit') { ?><input type='text' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control ttlsum ttlsumrequired' value='<?=$sql_plan[$plani]['APPRVAL']?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero(<?=$plani?>, this.value, <?=$total_amt?>);' maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control ttlsum ttlsumrequired' value='<?=$sql_plan[$plani]['APPRVAL']?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum()' onblur='calculate_sum(); allow_zero(<?=$plani?>, this.value);' maxlength='10' style='margin: 2px 0px;'><?=moneyFormatIndia($sql_plan[$plani]['APPRVAL'])?><? } ?></td>
														<td width="2%"></td>
													</tr>
											<? if($ijk == 3) { $ijk = 0; ?>
												</table></td>
										<? } } }

										if($ijk != 12) { ?></table><? }
								} elseif($edtvl == 0) { ?>
									<div <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>>
									<table style='clear:both; float:left; width:100%;'>
									<tr><td><div id='id_budplanner' <? if($canedit == 0 or $edtvl == 0) { ?> class="disabledbutton" readonly="readonly" <? } ?>></div></td></tr>
									<tr><td>
										<table class="monthyr_wrap" style='width:100%; line-height:22px; <?=$displaynone?>'>
											<? 	if($tmporlive == 0) {
													if($_SESSION['tcs_user'] == 1118) {
														$sql_plan = select_query_json("select * from approval_budget_planner_temp
																							where aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
													} else {
														$sql_plan = select_query_json("select * from approval_budget_planner_temp
																							where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
													}
												} else {
													$sql_plan = select_query_json("select * from approval_budget_planner
																						where deleted = 'N' and aprnumb = '".$sql_reqid[0]['APRNUMB']."' order by APRSRNO", 'Centra', 'TEST');
												}

												for($plani = 0; $plani < count($sql_plan); $plani++) { ?>
													<tr><td style='text-align:right; width:25%;'><input type='hidden' name='mnt_yr[]' id='mnt_yr_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRPRID']?>'><input type='hidden' name='mnt_yraprsrno[]' id='mnt_yraprsrno_<?=$plani?>' class='form-control' value='<?=$sql_plan[$plani]['APRSRNO']?>'><span><?=$sql_plan[$plani]['APRMNTH']?></span> : </td>
													<td style='width:5%;'></td><td style='width:30%;'><? if($_REQUEST['action'] == 'edit') { ?><input type='text' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt[]' id='mnt_yr_amt_<?=$plani?>' class='form-control' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><?=moneyFormatIndia(floor($sql_plan[$plani]['APPRVAL']))?><? } ?></td>
													<td style='width:40%;'><? if(floor($sql_plan[$plani]['APPRVAL']) > 0) { ?><input type='text' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> required name='mnt_yr_amt1[]' id='mnt_yr_amt1_<?=$plani?>' class='form-control ttlsum' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } else { ?><input type='hidden' tabindex='18' <? if($canedit == 0) { ?> readonly <? } ?> name='mnt_yr_amt1[]' id='mnt_yr_amt1_<?=$plani?>' class='form-control ttlsum' value='<?=floor($sql_plan[$plani]['APPRVAL'])?>' onkeypress='return isNumber(event)' onKeyup='calculate_sum(<?=$plani?>)' onblur="calculate_sum(<?=$plani?>); allow_zero(<?=$plani?>, this.value, '<?=floor($sql_reqid[0]['APRQVAL'])?>');" maxlength='10' style='margin: 2px 0px;'><? } ?></td></tr>
												<? } ?>
												<tr><td colspan='2' style='width:40%; padding-top:2%; text-align:right; padding-right:5%; font-weight:bold;'>TOTAL : </td><td style='width:30%; padding-top:2%; font-weight:bold;'><?=moneyFormatIndia(floor($sql_reqid[0]['APRQVAL']))?></td><td style='width:30%; padding-top:2%; font-weight:bold;'><span id='ttl_mntyr'><?=moneyFormatIndia(floor($sql_reqid[0]['APRQVAL']))?></span></td></tr>
												<input type="hidden" id="ttl_lock" name="ttl_lock" value="<?=floor($sql_reqid[0]['APRQVAL'])?>">
										</table>
									</td></tr>
									</table>
									</div>

									<!-- Supplier Quotation -->
									<table style="width: 100%; line-height: 15px;">
										<? $sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST
																					where PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."'
																						and REJUSER is null", 'Centra', 'TEST');
											if(count($sql_prdlist) > 0) {
												$inc = 0;
												foreach($sql_prdlist as $prdlist) { $inc++;
												$sql_prdquotlist = select_query_json("select qut.SUPCODE, qut.SUPNAME, cty.CTYNAME, sup.SUPMOBI, qut.DELPRID
																									from APPROVAL_PRODUCT_QUOTATION qut, supplier_asset sup, city CTY
											    													where qut.supcode = sup.supcode and cty.ctycode = sup.ctycode and qut.SLTSUPP = 1 and
											    														qut.PBDCODE = '".$prdlist['PBDCODE']."' and qut.PBDYEAR = '".$prdlist['PBDYEAR']."' and
											    														qut.PBDLSNO = '".$prdlist['PBDLSNO']."'
												    										union
												    											select qut.SUPCODE, qut.SUPNAME, cty.CTYNAME, sup.SUPMOBI, qut.DELPRID
																										from APPROVAL_PRODUCT_QUOTATION qut, supplier sup, city CTY
												    													where qut.supcode = sup.supcode and cty.ctycode = sup.ctycode and qut.SLTSUPP = 1 and
												    														qut.PBDCODE = '".$prdlist['PBDCODE']."' and qut.PBDYEAR = '".$prdlist['PBDYEAR']."' and
												    														qut.PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', 'TEST'); ?>
										    <tr><td>Supplier : <b style="font-size: 14px; font-weight: bold;"><?=$sql_prdquotlist[0]['SUPCODE']." - ".$sql_prdquotlist[0]['SUPNAME']; /*." - ".$sql_prdquotlist[0]['CTYNAME']." - ".$sql_prdquotlist[0]['SUPMOBI'] */?></b>; <span style="font-size: 9px;color: #a0a0a0;">( Delivery Duration : <?=$sql_prdquotlist[0]['DELPRID']?> Days )</span></td></tr>
											<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
												<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
												<td class="colheight" style="padding: 0px;width: 40%;">Product / Sub Product</td>
									            <td class="colheight" style="padding: 0px;width: 10%;">Per Piece Rate &#8377</td>
									            <td class="colheight" style="padding: 0px;width: 17%;">Tax &#8377</td>
									            <td class="colheight" style="padding: 0px;width: 11%;">Discount %</td>
									            <td class="colheight" style="padding: 0px;width: 7%;">Qty.</td>
									            <td class="colheight" style="padding: 0px;width: 12%;">Net Amount</td>
									            <? /* <td class="colheight" style="padding: 0px;width: 12%;">Unit</td>
									            <td class="colheight" style="padding: 0px;width: 25%;">Product Details</td>
									            <td class="colheight" style="padding: 0px;width: 17%;">Usage Section</td> */ ?>
											</tr>
										<? 	$sql_prdquotlist1 = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION
									    													where PBDCODE = '".$prdlist['PBDCODE']."' and SLTSUPP = 1 and
									    														PBDYEAR = '".$prdlist['PBDYEAR']."' and PBDLSNO = '".$prdlist['PBDLSNO']."'", 'Centra', 'TEST'); ?>
												<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
													<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$inc?></td>
													<td class="colheight" style="padding: 1px 0px 1px 3px; width: 39%; text-align: left;">
														<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?> / <?=$prdlist['SUBCODE']." - ".$prdlist['SUBNAME']?> <br>
														<span style="font-size: 9px;color: #a0a0a0;">( <?=$prdlist['PRDSPEC']?> )
															<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "AD. DURATION : ".$prdlist['ADURATI'].""; } ?>
											    			<? if($prdlist['ADLENGT'] == '0' and $prdlist['ADWIDTH'] == '0') { echo ""; }
											    			   else { echo "SIZE ( L X W ) : ".$prdlist['ADLENGT']." X ".$prdlist['ADWIDTH'].""; } ?>
											    			<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "AD. PRINT LOCATION : ".$prdlist['ADLOCAT'].""; } ?></span><br>

											    		<? echo "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin:0px;'>";
		                                                if($prdlist['PRDIMAG'] != '-' and $prdlist['PRDIMAG'] != '') {
		                                                    $dataurl = $prdlist['PBDYEAR'];
		                                                    $filename = strtolower($prdlist['PRDIMAG']);
		                                                    switch(strtolower(find_indicator_fromfile($prdlist['PRDIMAG'])))
		                                                    {
		                                                        case 'i':
		                                                                $folder_path = "approval_desk/product_images/".$dataurl."/";
		                                                                $thumbfolder_path = "approval_desk/product_images/".$dataurl."/thumb_images/";

		                                                                echo $fieldindi = "<li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:5px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:70px; height:70px;' class=\"img-responsive style_box\" style=\"padding: 2px 5px;\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
		                                                                break;
		                                                        case 'n':
		                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
		                                                                break;
		                                                        case 'w':
		                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
		                                                                break;
		                                                        case 'e':
		                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
		                                                                break;
		                                                        case 'p':
		                                                                echo $fieldindi = "</ul><a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/product_images/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\" style=\"padding: 2px 5px;\">".$filename."</a><br><br><ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:5px;'>";
		                                                                break;
		                                                        default:
		                                                                echo $fieldindi = '';
		                                                                break;
		                                                    }
		                                                }
		                                              echo "</ul>"; ?>
													</td>
										    		<td class="colheight" style="padding: 1px 0px; width: 10%;"><? $expl1 = explode(".", $sql_prdquotlist1[0]['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?><br><span style="font-size: 9px;color: #a0a0a0;">Adv. Amt. : <?=moneyFormatIndia($sql_prdquotlist1[0]['ADVAMNT'])?></span></td>

										            <td class="colheight" style="padding: 1px 0px; width: 17%;">
										    			<? if($sql_prdquotlist1[0]['SGSTVAL'] == '0' ) { echo ""; } else { $sc_per = 0; $sc_per = round(($sql_prdquotlist1[0]['SGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "SGST (".$sc_per." %) : ".$sql_prdquotlist1[0]['SGSTVAL']."<BR>"; } ?>
										    			<? if($sql_prdquotlist1[0]['CGSTVAL'] == '0' ) { echo ""; } else { $cc_per = 0; $cc_per = round(($sql_prdquotlist1[0]['CGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "CGST (".$cc_per." %) : ".$sql_prdquotlist1[0]['CGSTVAL']."<BR>"; } ?>
										    			<? if($sql_prdquotlist1[0]['IGSTVAL'] == '0' ) { echo ""; } else { $ic_per = 0; $ic_per = round(($sql_prdquotlist1[0]['IGSTVAL'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo "IGST (".$ic_per." %) : ".$sql_prdquotlist1[0]['IGSTVAL']."<BR>"; } ?>
										    		</td>
										            <td class="colheight" style="padding: 1px 0px; width: 11%;">
										    			<? /* if($sql_prdquotlist1[0]['SPLDISC'] == '0' ) { echo ""; } else { echo "SPL.DISCOUNT : ".$sql_prdquotlist1[0]['SPLDISC']."<BR>"; } ?>
										    			<? if($sql_prdquotlist1[0]['PIECLES'] == '0' ) { echo ""; } else { echo "PIECE LESS : ".$sql_prdquotlist1[0]['PIECLES']."<BR>"; } */ ?>
										    			<? if($sql_prdquotlist1[0]['DISCONT'] == '0' ) { echo ""; } else { $ds_per = 0; $ds_per = round(($sql_prdquotlist1[0]['DISCONT'] / $sql_prdquotlist1[0]['PRDRATE']) * 100, 2); echo /* "DISCOUNT : " */ "(".$ds_per." %) ".$sql_prdquotlist1[0]['DISCONT']."<BR>"; } ?>
										    		</td>

										            <td class="colheight" style="padding: 1px 0px; width: 7%;"><?=$prdlist['TOTLQTY']?></td>
										            <td class="colheight" style="padding: 1px 0px; width: 12%; margin-right: 0.6%;"><?=moneyFormatIndia($sql_prdquotlist1[0]['NETAMNT'])?></td>
										            <? /* <td class="colheight" style="padding: 1px 0px; width: 12%;">
										    			<? if($prdlist['UNTNAME'] == '') { echo ""; } else { echo $prdlist['UNTNAME']; } ?>
										    		</td>
										            <td class="colheight" style="padding: 1px 0px 1px 3px; width: 25%; text-align: left;">
										    			<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "AD. DURATION : ".$prdlist['ADURATI']."<br>"; } ?>
										    			<? 	if($prdlist['ADLENGT'] == '0' and $prdlist['ADWIDTH'] == '0') { echo ""; }
										    				else { echo "SIZE ( L X W ) : ".$prdlist['ADLENGT']." X ".$prdlist['ADWIDTH']."<br>"; } ?>
										    			<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "AD. PRINT LOCATION : ".$prdlist['ADLOCAT']."<br>"; } ?>
										    		</td>
										            <td class="colheight" style="padding: 1px 0px; width: 18%;">
										    			<?	$sql_sect = select_query_json("select * from empsection where esecode = '".$prdlist['USESECT']."'"); echo $sql_sect[0]['ESENAME']; ?>
										    		</td> */ ?>
												</tr>
											<? } } ?>
										</table>

									<? } ?>
								</tr>
							</table>

						<!-- General Master -->
			<?
			$sql_ap = select_query_json("select distinct aprnumb from approval_request where  ARQCODE = '".$_REQUEST['reqid']."' and  ARQYEAR = '".$_REQUEST['year']."'   and ATCCODE = '".$_REQUEST['creid']."' and  ATYCODE = '".$_REQUEST['typeid']."' and  deleted='N'", 'Centra', 'TEST');

			$sql_gen_det = select_query_json("select * from approval_general_detail where aprnumb='".$sql_ap[0]['APRNUMB']."' order by rowsrno,colsrno", 'Centra', 'TEST');
			$sql_gen_master = select_query_json("select * from approval_general_master where tempid=".$sql_gen_det[0]['TEMPID']." order by colsrno", 'Centra', 'TEST');
			$app_val = "";
			$app_val = $sql_gen_master[0]['CALRES'];
			if(count($sql_gen_det)>0){?>

		 <div style="margin:10px;">
		 <table style="margin:0 auto;width: 100%;" class="table table-bordered table-striped table-hover">
			<thead style="border-color: black;">
			 		<tr style="background-color: #f0f0f0;color: #1a0303;text-transform: uppercase;">
			 		<?	$totalw =0;
						$noofcol=0;
			 		foreach ($sql_gen_master as $col) {
						if($col['COLTYPE'] =="Y"){
							$totalw =1;
							if($noofcol == 0)
							{
								$colspan = $col['COLSRNO']-1;
								$noofcol =1;
							}
						}?>
			 		<th class="colauto">
			 			<?if($col['COLDET'] == "SR.NO"){ echo "#";}else{echo $col['COLDET'];}?>
		 			</th>
			 		<?}?>
			 	</tr>
			 	</thead>
			 	<tbody>
			 		<?$row = "";
			 		$tot = array();
		 		foreach($sql_gen_det as $col)
	 			{
	 				$ncol = $col['COLSRNO']-1;
	 				if($row != $col['ROWSRNO']) {
						$row1++; ?><tr style="color: black;">
					<? } ?>
	 				<td <?if($sql_gen_master[$ncol]['COLTYPE'] == "Y"){?> class="colnum"<?}else{?> class="coltext" <?}?>>
	 					<?	if($sql_gen_master[$ncol]['CALRES'] != $col['COLSRNO'])
	 						{
								if($col['COLSRNO'] == 1){ echo $row1; }
								else{ // echo "**";
									if($col['APMCODE'] == 856 and $col['COLSRNO'] == 2) {
										$expl = explode(",", $col['COLDET']);
										for($ij = 0; $ij < count($expl); $ij++) {
											$sql_tablemast = select_query_json("select * from master_table_detail where deleted = 'N' and MASTERID = '".$expl[$ij]."' order by TABNAME asc", 'Centra', 'TCS');
												if(count($sql_tablemast) > 0) {
													if($_SESSION['tcs_empsrno'] == 21344) { ?>
														<a href="javascript:void(0)" title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" data-title="<?=$sql_tablemast[0]['TABNAME']?> TABLE" onclick="find_tablemaster('<?=$sql_tablemast[0]['TABNAME']?>')"><?=$sql_tablemast[0]['TABNAME']?> (<?=$sql_tablemast[0]['MASTERID']?>) </a>;&nbsp;&nbsp;
											<? } else { echo $sql_tablemast[0]['TABNAME']." (".$sql_tablemast[0]['MASTERID']."), "; }
											} else { echo $col['COLDET']; }
										}
									} else { echo $col['COLDET']; }
								}
							}else{
								echo round($col['COLDET']);
							}
	 					?>
	 				</td>
				<?	$row = $col['ROWSRNO'];
					if($sql_gen_master[$ncol]['COLTYPE'] == "Y")
					{
						$tot[$col['COLSRNO']] += $col['COLDET'];
					}
				}
				?><tr style="font-weight: bolder;font-size: larger;color: black;"><?
				foreach ($sql_gen_master as $col)
				{
					if($totalw ==1)
					{
						if($col['COLSRNO'] == 1)
						{?>
						<td class="colauto" colspan="<?=$colspan?>">Total</td>
						<?}elseif($col['COLSRNO'] > $colspan){?>
						<td <?if($col['COLTYPE'] == "Y"){?> class="colnum"<?}else{?> class="coltext" <?}?>><?=$tot[$col['COLSRNO']]?></td>
						<?}
						?>
					<?}else{?>
						<td></td>
					<?}
				} ?>
			 	</tbody>
			</table>
			</div>
		 <?}?>
			<!-- General Master -->

	<!-- STAFF night Duty START -->
	<?	$sql_night = select_query_json("select * from approval_night_duty where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%' order by entsrno", 'Centra', 'TEST');
		if(count($sql_night)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Department</td>
		            <td class="colheight" style="padding: 0px;width: 20%;">Nature Of Work</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Working Hours</td>
		        </tr>
				<? $g=0;
				foreach ($sql_night as $gift) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['EMPCODE']."-".$gift['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;">
						<? if($gift['EMPCODE'] != 0) { ?>
							<img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>">
						<? } ?>
					</td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['DESNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['ESENAME']?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['WRKDESC']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['WRKHURS']?></td>
				</tr>
				<? } ?>
			</table>
		<? } ?>
	<!-- STAFF night Duty START -->

	<!-- ESI PF Multiple Branch -->
	<?	$sql_project_branch = select_query_json("SELECT * from approval_branch_detail bd, approval_branch_list bl
														where bd.BRNCODE = bl.BRNCODE and bd.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'","Centra","TEST");
		if(count($sql_project_branch)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:2px;">
				<thead>
					<tr>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">#</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">BRANCH CODE</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">BRANCH NAME</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">NOUMBER OF EMPLOYEE</th>
						<th style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 15px; color:#000;">VALUE</th>
					</tr>
				</thead>
				<tbody style="text-align:center">
			<? for($project_i = 0; $project_i < count($sql_project_branch); $project_i++) {?>
				<tr>
					<td class="highlight_column1 blue_highlight" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?echo $project_i + 1;?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['BRNCODE']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['BRNNAME']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['NOFEMPL']?></td>
					<td style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;"><?=$sql_project_branch[$project_i]['APRAMNT']?></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		<? } ?>
	<!-- ESI PF Multiple Branch -->

	<!-- STAFF MARRIAGE GIFT START -->
	<?
		$sql_gift = select_query_json("select * from approval_staff_marriage where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
		if(count($sql_gift)>0 && $sql_ap[0]['APRNUMB'] != ""){
			echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Own Gift/GRAM</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Trust Amount</td>
		        </tr>
				<? $g=0;
				foreach ($sql_gift as $gift) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$gift['EMPCODE']."-".$gift['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$gift['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($gift['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$gift['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$gift['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$gift['OWNGIFT']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$gift['TRUSTAMT']?></td>
				</tr>
				<?}?>
			</table>
			<?}
			// STAFF BRANCH CHANGE START
			$sql_branch = select_query_json("select * from approval_staff_branch_change where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_branch)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Designation</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Dept</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">New Designation</td>
		        </tr>
				<? $g=0;
				foreach ($sql_branch as $branch) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$branch['EMPCODE']."-".$branch['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$branch['EMPCODE']?>"  alt = "<? echo $gift['EMPNAME']; ?>" title="<? echo $gift['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($branch['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$branch['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$branch['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$branch['NEWDES']?></td>
				</tr>
				<?}?>
			</table>
			<?}

			// STAFF DESIGNATION CHANGE
			$sql_desg = select_query_json("select * from approval_staff_designation where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_desg)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;" rowspan="2">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;" rowspan="2">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Current Designation</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">New Designation</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">New Department</td>
		            <td class="colheight" style="padding: 0px;width: 18%;" rowspan="2">Reporting To</td>
		        </tr>

				<? $g=0;
				foreach ($sql_desg as $desg) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$desg['EMPCODE']."-".$desg['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$desg['EMPCODE']?>"  alt = "<? echo $desg['EMPNAME']; ?>" title="<? echo $desg['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($desg['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$desg['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$desg['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$desg['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;color: blue;font-weight: bold;"><?=$desg['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['NEWDES']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['NEWDEPT']?></td>
					<td class="colheight" style="padding: 0px;width: 18%;color: green;font-weight: bold;"><?=$desg['REPORTTO']?></td>
				</tr>
				<?}?>
			</table>
			<? }

			// STAFF DEPT CHANGE
			$sql_dept = select_query_json("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_dept)>0 && $sql_ap[0]['APRNUMB'] != ""){
				echo "<br>"; ?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Current Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Current Designation</td>

		            <? if($sql_reqid[0]['APRNUMB'] == 'S-TEAM / ATTENDANCE 1000068 / 26-04-2018 / 0068 / 02:23 PM') { ?>
			            <td class="colheight" style="padding: 0px;width: 10%;">New Department</td>
			            <td class="colheight" style="padding: 0px;width: 10%;" rowspan="2">New Designation</td>
			        <? } else { ?>
			            <td class="colheight" style="padding: 0px;width: 12%;">New Department</td>
			        <? } ?>
			        <td class="colheight" style="padding: 0px;width: 10%;">Reporting To</td>
		        </tr>
				<? $g=0;
				foreach ($sql_dept as $dept) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$dept['EMPCODE']."-".$dept['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$dept['EMPCODE']?>"  alt = "<? echo $dept['EMPNAME']; ?>" title="<? echo $dept['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($dept['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$dept['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$dept['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;color: blue;font-weight: bold;"><?=$dept['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;"><?=$dept['CURDES']?></td>

		            <? if($sql_reqid[0]['APRNUMB'] == 'S-TEAM / ATTENDANCE 1000068 / 26-04-2018 / 0068 / 02:23 PM') {
		            	$sql_des = select_testquery("select * from approval_staff_department where apryear='".$_REQUEST['year']."' and aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'"); ?>
						<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$dept['NEWDEP']?></td>
						<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$sql_des[0]['NEWDES']?></td>
		            <? } else { ?>
						<td class="colheight" style="padding: 0px;width: 12%;color: green;font-weight: bold;"><?=$dept['NEWDEP']?></td>
					<? } ?>
					<td class="colheight" style="padding: 0px;width: 10%;color: green;font-weight: bold;"><?=$dept['REPORTTO']?></td>
				</tr>
				<?}?>
			</table>
			<?}
			// STAFF SALARY CHANGE
			$sql_salary = select_query_json("select app.* from approval_staff_salary_change app,employee_office emp
													where emp.empsrno= app.empsrno and app.apryear='".$_REQUEST['year']."' and app.aprnumb like  '%".$sql_ap[0]['APRNUMB']."%'", 'Centra', 'TEST');
			if(count($sql_salary)>0 && $sql_ap[0]['APRNUMB'] != ""){
					echo "<br>";
			?>
			<table style="width: 100%; line-height: 15px;">
				<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
					<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 3%;">#</td>
					<td class="colheight" style="padding: 0px;width: 20%;">Employee</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Photo</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Doj</td>
		            <td class="colheight" style="padding: 0px;width: 5%;">Exp</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Branch</td>
		            <td class="colheight" style="padding: 0px;width: 10%;">Department</td>
		            <td class="colheight" style="padding: 0px;width: 12%;">Designation</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Current Basic</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">Increment amt</td>
		            <td class="colheight" style="padding: 0px;width: 8%;">New Basic</td>
		        </tr>
				<? $g=0;
				foreach ($sql_salary as $salary) { $g++;?>
				<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
					<td class="colheight" style="padding: 1px 0px; width: 3%;"><?=$g?></td>
					<td class="colheight" style="padding: 0px;width: 20%;"><?=$salary['EMPCODE']."-".$salary['EMPNAME']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><img class="img" style="width:50px;height: 40px; " align="center" src="profile_img.php?profile_img=<?=$salary['EMPCODE']?>"  alt = "<? echo $salary['EMPNAME']; ?>" title="<? echo $salary['EMPNAME']; ?>"></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=strtoupper(date("d-M-Y", strtotime($salary['DATEJOIN'])))?></td>
					<td class="colheight" style="padding: 0px;width: 5%;"><?=$salary['CUREXP']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;"><?=$salary['CURBRN']?></td>
					<td class="colheight" style="padding: 0px;width: 10%;"><?=$salary['CURDEP']?></td>
					<td class="colheight" style="padding: 0px;width: 12%;"><?=$salary['CURDES']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: blue;font-weight: bold;"><?=$salary['CURBAS']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: green;font-weight: bold;"><?=$salary['INCAMT']?></td>
					<td class="colheight" style="padding: 0px;width: 8%;color: green;font-weight: bold;"><?=$salary['NEWBAS']?></td>
				</tr>
				<?}?>
			</table>
			<? } ?>
				</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr style='height:20px;'><td></td></tr>

			<?php
			$sql_descode=select_query_json("Select p.APLCYNM, to_char(pf.EFCTDAT, 'dd-MM-yyyy') EFCTDAT, to_char(pf.VALDUPT, 'dd-MM-yyyy') VALDUPT, to_char(pf.APPRDAT, 'dd-MM-yyyy') APPRDAT, pf.APLCYCD, pf.PLCYTYP, pf.CRTECNO, pf.CRTUSNM, pf.CRDECNO, pf.CRDUSNM, pf.ASTECNO, pf.ASTUSNM, pf.USERLST, pf.APRVDBY, pf.APRVDUS, pf.DESKPRO, pf.PLCDATA, pf.PLCATTC From approval_policy_form pf, approval_policy_master p
			where pf.APLCYCD = p.APLCYCD and pf.APRNUMB = '".$sql_reqid[0]['APRNUMB']."'","Centra","TEST");
				foreach($sql_descode as $sectionrow) {
			?>
			<tr>
				<td colspan="3">
						<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC;padding:2px;">
							<thead>
								<tr>
									<th colspan="2" style="white-space: nowrap; border: 1px solid #e0e0e0; background-color: #f0f0f0;line-height: 25px; color:#000;text-align:left;"> SUBJECT  : <span class="blue_highlight"><?echo $sectionrow['APLCYNM'];?></span></th>
								</tr>
							</thead>
							<tbody style="text-align:center">
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> EFFECTIVE DATE : <span class="blue_highlight"><b><?echo $sectionrow['EFCTDAT'];?></b></span> </td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> POLICY TYPE : <span class="blue_highlight"><b><?echo $sectionrow['PLCYTYP'];?></b></span></td>
							</tr>
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> VALID UPTO : <span class="blue_highlight"><b><?echo $sectionrow['VALDUPT'];?></b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> CREATOR EC NO \ NAME : <span class="blue_highlight"><b><?echo $sectionrow['CRTECNO'];?> - <?echo $sectionrow['CRTUSNM'];?></b></span></td>
							</tr>
										<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%"> APPROVED DATE : <span class="blue_highlight"><b><?echo $sectionrow['APPRDAT'];?></b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> CO-ORDINATOR EC NO \ NAME : <span class="blue_highlight"><b><?echo $sectionrow['CRDECNO'];?> - <?echo $sectionrow['CRDUSNM'];?></b></span></td>
							</tr>
							<tr>
								<td class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  USER LIST : <span class="blue_highlight"><b><?echo $sectionrow['USERLST'];?></b></span></td>
								<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:65%"> ASSIST BY EC NO \ NAME : <span class="blue_highlight"><b><?echo $sectionrow['ASTECNO'];?> - <?echo $sectionrow['ASTUSNM'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  APPROVED BY : <span class="blue_highlight"><b><?echo $sectionrow['APRVDBY'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  DESK PROCEDURE : <span class="blue_highlight"><b><?echo $sectionrow['DESKPRO'];?></b></span></td>
							</tr>
							<tr>
								<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%">  MAINTENANCE OF POLICY : <span class="blue_highlight"><b><?echo $sectionrow['PLCDATA'];?></b></span></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr style='height:10px;'><td></td></tr>
			<tr colspan="3">
			</td>
							<table style="width:100%; max-width: 773px; min-height:60px; height:auto; border:1px solid #0088CC;padding:2px;">
								<tbody style="text-align:center">
								<tr>
									<td colspan="2" class="highlight_column1" style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:35%;padding:3px 5px"><br>  POLICY <span class="blue_highlight"><b>:</b></span>
										<br><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
									</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 01</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 02</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 03</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 04</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 05</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 06</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 07</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 08</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 09</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 10</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 11</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 12</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 13</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 14</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 15</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 16</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 17</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 18</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 19</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 20</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 21</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 22</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 23</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 24</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 25</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%"> 26</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 27</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 28</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 29</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.  </td>
								</tr>
								<tr>
									<td class="highlight_column1" style="text-align:center; font-weight: bold; border: 1px solid #e0e0e0;width:5%;padding:3px 5px"> 30</td>
									<td style="text-align:left; font-weight: bold; border: 1px solid #e0e0e0;width:95%;padding:3px 5px"> It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</td>
								</tr>
							</tbody>
						</table>
				</td>
			</tr>
			<? } ?>

			<tr style='height:20px;'><td></td></tr>






				<tr style='min-height:25px; line-height:25px;'>
					<td style='width:100%; text-align:left;' colspan=2>
					<table width='100%' style="max-width: 773px; " border="1">

					<tr style='min-height:25px; line-height:25px;'>
						<td style='width:16%; text-align:left;'>
							<label>No. of Attachment</label> <!-- No. of Attachment -->
						</td>
						<td style='width:30%; text-align:left;'>
							<label>: <? if($count_attachment == 0) { echo "--NIL--"; } else { ?>
										<a href='javascript:void(0)' onclick="popup_attachment('<? echo $sql_reqid[0]['ARQCODE']; ?>', '<? echo $sql_reqid[0]['ARQYEAR']; ?>', '1', '<? echo $sql_reqid[0]['ATCCODE']; ?>', '<? echo $sql_reqid[0]['ATYCODE']; ?>', '<? echo $sql_reqid[0]['APRNUMB']; ?>')" title='View' alt='View' style='font-weight:bold;' class="blue_highlight"><img src="images/attach.png" style="width: 32px; height: 32px; border: 0px;"> <?=$count_attachment;?></a>
									 <? } ?></label> <!-- No. of Attachment -->
						</td>
						<? /* <td rowspan=4 style='width:20%; text-align:center;'>
							<? $sql_verify = select_query_json("select * from APPROVAL_MODE_HIERARCHY where deleted = 'N' and VRFYREQ > 0 and APMCODE = '".$sql_reqid[0]['APMCODE']."' order by APMCODE", 'Centra', 'TEST');
								if($sql_verify[0]['VRFYREQ'] == 1 and $steam_name != '') { ?>
									<img src='images/s-team-audit.png' style='width:130px; height:102px;' border=0>
							<? } ?>
						</td> */ ?>

						<? if($sql_reqid[0]['IMDUEDT'] != '') { ?>
							<td style='width:35%; text-align:right;'>
								<label>Implementation Due Date</label> <!-- Implementation Due Date -->
							</td>
							<td style='width:17%; text-align:left; font-weight: bold;'>
								<label>: <?=strtoupper(date("d-M-Y", strtotime($sql_reqid[0]['IMDUEDT'])))?> </label> <!-- Implementation Due Date -->
							</td>
						<? } ?>

						<? /* <td style='width:15%; text-align:left;'>
							<label>Requested Value</label> <!-- Requested Value -->
						</td>
						<td style='width:20%; text-align:left; font-weight: bold;'>
							<label>: <? if($sql_reqid[0]['APRQVAL'] == 0) { echo "--NIL--"; } else { $reqvl = moneyFormatIndia($sql_reqid[0]['APRQVAL']); ?><img src='images/rupees.png' width=10 height=10 border=0> <?=$reqvl?> </label>
							<? // <label class='cls_rupees'>(<? echo ucwords(convert_rup($sql_reqid[0]['APRQVAL'])).")</label>"; } ?> <!-- Requested Value -->
						</td> */ ?>
					</tr>
					

					<!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
					
					<!-- ////////////////////////////////////// -->


				<? /* <tr style='min-height:25px; max-width: 773px; width: 100%; line-height:25px;'>
					<td style='width:20%; text-align:left;'>
						<label>Advance Amount Value</label> <!-- Advance Amount Value -->
					</td>
					<td colspan=5 style='width:80%; text-align:left;'>
						: <label style=' font-size:12px; font-weight:bold;'><img src='images/rs.png' width=12 height=12 border=0> <?=moneyFormatIndia($sql_approve_leads[0]['ADVAMNT'])?></label>
						 <!-- Advance Amount Value -->
					</td>
				</tr> */ ?>


				<!-- Expense Percentage -->
				<!-- //////////////////////////////////// -->
			</table>
				<tr >
				<td >
					<div class="row" style="border:1px solid #1c94c4;margin-top: 1%;border-radius: 3px;">
						<!-- //////////////////////////// -->
						<!-- <pre> -->
                                    <?//$sql_quote=select_query_json("select spa.subname,sup.supname,pa.prdname,apqf.* from approval_product_quotation_fix apqf,supplier_asset sup,SUBPRODUCT_ASSET SPA,PRODUCT_ASSET PA where pa.prdcode=apqf.prdcode AND spa.prdcode=apqf.prdcode and spa.subcode=apqf.subcode and sup.supcode=apqf.supcode and APRNUMB='".$sql_reqid[0]['APRNUMB']."'", "Centra", "TEST");
                                //  echo("select spa.subname,sup.supname,pa.prdname,apqf.* from approval_product_quotation_fix apqf,supplier_asset sup,SUBPRODUCT_ASSET SPA,PRODUCT_ASSET PA where pa.prdcode=apqf.prdcode AND spa.prdcode=apqf.prdcode and spa.subcode=apqf.subcode and sup.supcode=apqf.supcode and APRNUMB='".$sql_reqid[0]['APRNUMB']."'");
                                    $sql_quote=select_query_json("select spa.subname,unt.untname,sup.supname,pa.prdname,apqf.* from approval_product_quotation_fix apqf,supplier_asset sup,SUBPRODUCT_ASSET SPA,PRODUCT_ASSET PA,unit unt where pa.prdcode=apqf.prdcode AND spa.prdcode=apqf.prdcode and spa.subcode=apqf.subcode and sup.supcode=apqf.supcode and SPA.untcode=unt.untcode and APRNUMB='".$sql_reqid[0]['APRNUMB']."' and apqf.sltsupp=1 order by apqf.entnumb", "Centra", "TEST");
                                    $arr_prd=array();
                                    foreach($sql_quote as $key => $value)
                                    {
                                      $temp=count($arr_prd[$value['PRDCODE']]);
                                      $arr_prd[$value['PRDCODE']][$temp]=$value;
                                    }
                                    //print_r($sql_reqid[0]['APRNUMB']);
                                    ?>
                                <!-- </pre> -->
                                
                                    <?foreach ($arr_prd as $key => $value) {?>
                                    	<center>
                                    		 <h3 style="color: red;margin-left: 1%;">Product : <?=$key;?> - <?=$value[0]['SUBCODE']?> (<?=$value[0]['PRDNAME']?> - <?=$value[0]['SUBNAME']?>) - <?=$value[0]['UNTNAME']?> </h3> 
                                    	</center>
                              
                               <? //echo $sql_reqid[0]['APRNUMB']; ?>
                                <center style="margin-bottom: 1%;" ><table class="table table-bordered" style="box-shadow: 0 0 5px black;width: 99%;">
                                    <thead style="background: #666666 !important;">
                                        <tr>
                                            <th style="text-align: center;background: #666666;color:white;">S.No.</th>
                                            <th style="text-align: center;background: #666666;color:white;">Image</th>
                                            <th style="text-align: center;background: #666666;color:white;">Supplier</th>
                                            <th style="text-align: center;background: #666666;color:white;">Rate</th>
                                            <th style="text-align: center;background: #666666;color:white;">Discount</th>
                                            <th style="text-align: center;background: #666666;color:white;">Quantity</th>
                                            <th style="text-align: center;background: #666666;color:white;">CGST</th>
                                            <th style="text-align: center;background: #666666;color:white;">SGST</th>
                                            <th style="text-align: center;background: #666666;color:white;">IGST</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?for($i=0;$i<count($value);$i++){?>
                                        <tr >
                                            <?if($value[$i]['SLTSUPP']=='1'){$bgr="rgba(1, 137, 1, 0.28)";}else{$bgr="none";}?>
                                            <td style="background: <?=$bgr;?> !important;text-align:center;">
                                                <?=$i+1;?>
                                            </td>
                                            <?if($value[$i]['SUPQFILE']=='')
                                                    {
                                                        $img = "ftp_image_view.php?pic=no-image.png&path=approval_desk/product_quotation_fix/";
                                                    }
                                                    else
                                                    {
                                                        $img = "ftp_image_view.php?pic=".$value[$i]['SUPQFILE']."&path=approval_desk/product_quotation_fix/".$value[$i]['ARQYEAR']."/";
                                                    }
                                               ?>
                                            <td style="background: <?=$bgr;?> !important;text-align:center;">
                                                
                                                <a target="_blank" href="<?=$img;?>">
                                                <img src="<?=$img;?>" style="width: 100px;height: 100px;border-radius: 10px;border:2px solid #adadad;" />
                                               </a>
                                            </td>
                                            <td style="background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['SUPNAME'];?><br>
                                                <?if($value[$i]['SLTSUPP']=='1'){?>
                                                    <img src="images/seal4.png" style="width: 50px;height: 50px;" />
                                                <?}?>
                                            </td>
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['SUPRATE']?><br>
                                               <?if($value[$i]['SLTSUPP']=='1' && $value[$i]['SUPRATE']!=$value[$i]['QTRATE']){?>
                                                    (Sug : <?=$value[$i]['QTRATE'];?>)
                                                <?}?>
                                               
                                                
                                            </td>
                                            
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['SUPDISC']?><br>
                                               <?if($value[$i]['SLTSUPP']=='1'&& $value[$i]['SUPDISC']!=$value[$i]['QTDISC']){?>
                                                    (Sug : <?=$value[$i]['QTDISC'];?>)<br>
                                                <?}?>
                                            </td>
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                               <?=$value[$i]['PRDQTY']?>
                                            </td>
                                            
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['CGSTPER']?><br>
                                                <?if($value[$i]['SLTSUPP']=='1' && $value[$i]['CGSTPER']!=$value[$i]['QTCGST']){?>
                                                    
                                                    (Sug : <?=$value[$i]['QTCGST'];?>)<br>
                                                <?}?>
                                                
                                            </td>
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['SGSTPER']?><br>
                                                <?if($value[$i]['SLTSUPP']=='1' && $value[$i]['SGSTPER']!=$value[$i]['QTSGST']){?>
                                                    (Sug : <?=$value[$i]['QTSGST'];?>)<br>
                                                <?}?>
                                              
                                            </td>
                                            <td style="text-align: center;background: <?=$bgr;?> !important;">
                                                <?=$value[$i]['IGSTPER']?><br>
                                               <?if($value[$i]['SLTSUPP']=='1' && $value[$i]['QTIGST']!=$value[$i]['IGSTPER']){?>
                                                   (Sug : <?=$value[$i]['QTIGST'];?>)<br>
                                                <?}?>
                                               
                                            </td>
                                        </tr>
                                        <?}?>
                                    </tbody>
                                </table>
                                
                            </center>
                            <?}?>
                            <center>
                            <!-- <div class="row">
                            	<button type="button" name='sbmt_bid' id='sbmt_bid' tabindex='28' class="btn" title="BID" style="background-color:#d42c65;color: white;" onclick="get_bid_dateview('<?=$sql_reqid[0]['ARQYEAR']?>','<?=$sql_reqid[0]['IMUSRIP']?>','<?=$sql_reqid[0]['ATCCODE']?>','CREATE_QUOTE');"> <i class="fa fa-gavel"></i> BID</button>
                            	<button type="button" name='sbmt_bid' id='sbmt_bid' class="btn" title="REVERSE BID" tabindex='28' style="background-color:#d42c65;color: white;" onclick="get_bid_new('<?=$sql_reqid[0]['ARQYEAR']?>','<?=$sql_reqid[0]['IMUSRIP']?>','<?=$sql_reqid[0]['ATCCODE']?>','REVERSE_QUOTE','<?=$sql_reqid[0]['APRNUMB']?>');"> <i class="fa fa-gavel"></i> REVERSE BID</button>
                            </div> -->
                           </center>
						<!-- //////////////////////////////////// viki-->
					</div>
				</td>
			</tr>
				<!-- ///////////////////////////////////////// -->
				<!-- Expense Percentage -->


				<? if(count($comnts_user) > 0 and count($comnts_rmrk) > 0 and $addcmnts > 0) { ?>
				<tr style='min-height:25px; max-width: 773px; line-height:25px;'>

					<td colspan=6 style='width:100%; text-align:left;'>
					<table border=1 style='width:100%; max-width: 773px; min-height:100px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px;'>
						<tr style='min-height:25px; line-height:25px;'>
							<td style='width:100%; padding:0 10px; font-weight:bold; text-align:left;'>
								<label>COMMENTS HISTORY</label> <!-- Comments History -->
							</td>
						</tr>

						<?  for($usri = 0; $usri < count($comnts_user) && $comnts_user[$usri] != ''; $usri++) { ?>
								<tr style='min-height:25px; line-height:25px;'>
									<td style='width:100%; padding:0 10px; text-align:left;'>
										<label><b><?=$comnts_user[$usri]?> Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( <?=$his_time[$usri]?> )</label> : <?=$comnts_rmrk[$usri]?></label> <!-- All User Comments -->
									</td>
								</tr>
							<? 	if($usri == (count($comnts_user) - 1) and count($ks_cmnts) <= 0) {
									$sql_md = select_query_json("select INTSUGG, INTPESN, RQBYDES, ADDDATE, REQSTBY from APPROVAL_REQUEST req
																		where req.aprnumb like '".$sql_reqid[0]['APRNUMB']."' and arqsrno in (select max(arqsrno)
																			from APPROVAL_REQUEST where aprnumb = req.aprnumb)
																		order by req.ARQSRNO desc", 'Centra', 'TEST');
									if($sql_md[0]['INTSUGG'] != '' and $sql_md[0]['INTSUGG'] != '-') {
										if($sql_md[0]['INTPESN'] != '' and $sql_md[0]['INTPESN'] != '-') {
											$sircmnts = $sql_md[0]['INTPESN'];
											$ks_name = $sql_md[0]['RQBYDES'];
											$ks_adddate = $sql_md[0]['ADDDATE'];
											$ks_empsrno = 20118;

											/* Read KS sigature from FTP */
											// $ks_sign = "ftp://$ftp_user_name:$ftp_user_pass@$ftp_server/approval_desk/digital_signature/".$ks_empsrno.".png";
											$ks_sign = "ftp_image_view.php?pic=".$ks_empsrno.".png&path=".$folder_path."";
											/* Read KS sigature from FTP */

										} else { $sircmnts = $sql_md[0]['INTSUGG']; } /* ?>
										<tr style='min-height:25px; line-height:25px;'>
											<td style='width:100%; padding:0 10px; text-align:left;'>
												<label><b>KS SIR Comments </b> : <?=$sircmnts?></label> <!-- KS Sir Comments -->
											</td>
										</tr>
								<? */ }
								}
							} ?>
					</table>
					</td>
				</tr>
				<? } ?>

				</table>
				</td>
				</tr>
				
				<tr style='height:20px;'><td></td></tr>

				<tr>
					<td colspan=2 style='width:100%; max-width: 773px; padding-top:0px; text-align:left;'>
						<label>Thanks & Regards</label> <!-- Thanks & Regards -->
					</td>
				</tr>

				<tr>
				<td colspan=2 style='width:100%; font-size:11px; text-align:left;'>
					<table border=0 style='max-width: 773px; width:100%;'>
					<tr>
						<td style='width:35%; text-align:left;'>
							<label style='color:#000000; font-size:10px; '><b><?=$sql_reqid[0]['RQBYDES']?></b> - <? $sql_emp_section1 = explode(" ", $sql_reqid[0]['REQESEN']); echo $sql_emp_section1[1]." ".$sql_emp_section1[2]." ".$sql_emp_section1[3]." ".$sql_emp_section1[4]." ".$sql_emp_section1[5]; ?><br>
								<b>Work Initiate Person : <? $sql_usr = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, empsection sec, designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['WRKINUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
																						union
																							select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																							from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
																							where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['WRKINUSR'].")
																								and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
																							order by EMPCODE", 'Centra', 'TCS');
								echo $sql_usr[0]['EMPCODE']." - ".$sql_usr[0]['EMPNAME']; ?><br>
								<b>Responsible Person : <? $sql_usr = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																								from employee_office emp, empsection sec, designation des, employee_salary sal
																								where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																									and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno
																							union
																								select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME
																								from employee_office emp, new_empsection sec, new_designation des, employee_salary sal
																								where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode = ".$sql_reqid[0]['RESPUSR'].")
																									and sec.deleted = 'N' and sec.deleted = 'N' and sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno
																								order by EMPCODE", 'Centra', 'TCS');
									echo $sql_usr[0]['EMPCODE']." - ".$sql_usr[0]['EMPNAME'];?></b></span></label> <!-- Responsible Person -->
							</label> <!-- Request Creator -->
						</td>

						<? //////
						if($steam_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($steam_name != '') { echo $steam_name; } ?></label> <!-- S-Team Audit -->
							</td>
						<? } ?>

						<? if($exc_mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($exc_mgr_name != '') { echo $exc_mgr_name; } ?></label> <!-- DB -->
							</td>
						<? } ?>

						<? if($mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#000000; font-size:10px; '><? if($mgr_name != '') { echo $mgr_name; } ?></label> <!-- Manager -->
							</td>
						<? } ?>

						<? if($legal_name != '') { ?>
							<td style='width:15%; text-align:right;'>
								<label style='color:#000000; font-size:10px; '><? echo $legal_name; ?></label> <!-- Legal Audit -->
							</td>
						<? } ?>

						<? 	$srexc_mgr_name = array_values(array_unique($srexc_mgr_name));
							$srexc_mgr_sign = array_values(array_unique($srexc_mgr_sign));
							$srexc_mgr_dept = array_values(array_unique($srexc_mgr_dept));

							// echo "**".count($srexc_mgr_name)."**";
							if(count($srexc_mgr_name) > 0) {
							for($hodi = 0; $hodi < count($srexc_mgr_name); $hodi++) {
								if($srexc_mgr_sign[$hodi] != '') { ?>
									<td style='width:15%; text-align:right;>'>
										<label style='color:#0088CC; width:14%;'><img src='<?=$srexc_mgr_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- HOD / Branch Manager -->
									</td>
								<? } else { ?>
									<td style='width:15%; text-align:right;'>
										<label style='width:14%;'><?=$srexc_mgr_name[$hodi]?></label> <!-- HOD / Branch Manager -->
									</td>
							<? } ?>
						<? }
						} ///////////////////////////////// ?>
					</tr>

					<tr>
						<td style='width:35%; text-align:left;'>
							<label style='color:#0088CC; font-weight:bold'><? echo find_employee_branch($sql_reqid[0]['RQBYDES']); ?></label> <!-- Chennai Silks -->
						</td>

						<? ////////////////////
						if($steam_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($steam_name != '') { ?>S-Team Audit<? } ?></label> <!-- S-Team Audit -->
							</td>
						<? } ?>

						<? if($exc_mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($exc_mgr_name != '') { ?>DB<? } ?></label> <!-- DB -->
							</td>
						<? } ?>

						<? if($mgr_name != '') { ?>
							<td style='width:15%; text-align:center;'>
								<label style='color:#0088CC; font-weight:bold'><? if($mgr_name != '') { ?>Manager / Trainee<? } ?></label> <!-- Manager / Trainee -->
							</td>
						<? } ?>

						<? if($legal_name != '') { ?>
							<td style='width:15%; text-align:right;'>
								<label style='color:#0088CC; font-weight:bold'><? if($legal_name != '') { ?>Legal Audit<? } ?></label> <!-- Legal Audit -->
							</td>
						<? } ?>

						<? if(count($srexc_mgr_name) > 0) {
							for($hodi = 0; $hodi < count($srexc_mgr_name); $hodi++) { ?>
								<td style='width:14%; font-size:11px; text-align:right;'>
									<label style='color:#0088CC; font-weight:bold'><?=$srexc_mgr_adddate[$hodi]?><br><? if($srexc_mgr_dsgn[$hodi] != '') { echo $srexc_mgr_dsgn[$hodi]; } else { echo "Manager / Sr.Exe"; } ?></label> <!-- Manager / Sr.Exe -->
								</td>
						<? } }
						/////////////////////// ?>
					</tr>

					</table>
				</td>
				</tr>
				<tr style='height:20px;'><td></td></tr>



				<!-- Approval Desk Purpose Only -->
				<tr>
					<td style='width:70%; height:20px; border-left:1px solid #0088CC; border-right: 1px solid #0088CC; border-top: 1px solid #0088CC; font-weight:bold;'>
						<label style='font-size:13px; padding-left: 5px; color: #007cff;'>APPROVAL NO : <?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
					</td>
				</tr>
				<tr>
				<td colspan=2 style='width:100%; font-size:10px; text-align:left;'>
					<table border=0 style='max-width: 773px; width:100%; min-height: 75px; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px;'>

					<? 	$sql_desname = select_query_json("select DESNAME from designation 
																	where descode in (select descode from employee_office where empcode in (3))", 'Centra', 'TCS');
						$txtalign = 'text-align:center;'; if(count($find_leads) == 2) { $txtalign = 'text-align:right;'; } ?>

						<tr><td colspan=4>
							<table border=0 cell-padding=1 cell-spacing=1 style='width:100%; padding-left:2px; padding-right:2px;'>
								<?	$bm_name = array_values(array_unique($bm_name));
									$hod_name = array_values(array_unique($hod_name));
									$gm_name = array_values(array_unique(array_reverse($gm_name)));
									// print_r($gm_adddate);
									$gm_adddate = array_values(array_reverse($gm_adddate));

									$tot_len = sizeof($bm_name);
									$tot_len += sizeof($hod_name);
									$tot_len += sizeof($srexc_mgr_name_audit);
									$tot_len += sizeof($cc_name);
									$tot_len += sizeof($gm_name);
									$tot_len += sizeof($srgm_name);
									if($ak_name != '') {
									 	$tot_len += sizeof($ak_name);
									}elseif($ceo_available == 1) {
								 		$tot_len += 1;
								 	}elseif($sql_hir[0]['AK'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
								 		$tot_len += 1;
								 	}

							 		if($ps_name != '') {
							 			$tot_len += sizeof($ps_name);
							 		}elseif($sql_hir[0]['PS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
							 			$tot_len +=1;
							 		}

									if($ks_name != '') {
										$tot_len += sizeof($ks_name);
									} elseif($sql_hir[0]['KS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){
										$tot_len += 1;
									}
									?>

								<? if($tot_len >7){
										?>
								<tr>
										<td></td>
										<td></td>
										<?
									if($ak_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
									<td style='<?=$txtalign?>'>
										<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ak_sign?>' border=0 style='border:0px solid #d8d8d8; height:60px;'></label> <!-- AK -->
									</td>
									<? } ?>

									<? if($ps_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
									<td style='<?=$txtalign?>'>
										<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ps_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- PS Madam -->
									</td>
									<? } ?>

									<? if($ks_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
									<td style='<?=$txtalign?>'>
										<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ks_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- DIRECTOR / MD -->
									</td>
									<? }?>
								</tr>
								<?}
								if($tot_len >7){?>
									<tr  style="white-space: nowrap;">
										<td></td>
										<td></td>
									<?
									if($ak_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ak_adddate?><br>
											<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "S KAARTHI";?>
											</label><br><?=$sql_desname[0]['DESNAME']?></label> <!-- CEO & DIRECTOR / AK -->
										</td>
									<? } elseif($ceo_available == 1) { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<br>
											<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "S KAARTHI";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'><?=$sql_desname[0]['DESNAME']?></label> <!-- CEO & DIRECTOR / AK -->
										</td>
									<? }elseif($sql_hir[0]['AK'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br><label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "S KAARTHI";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'><?=$sql_desname[0]['DESNAME']?></label>
										</td>
									<?} ?>



									<? if($ps_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ps_adddate?><br>
											<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "PADHMA SIVLINGAM";?>
											</label><br>DIRECTOR</label> <!-- DIRECTOR / PS Madam -->
										</td>
									<? }elseif($sql_hir[0]['PS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br>
											<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "PADHMA SIVLINGAM";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'>DIRECTOR</label>
										</td>
									<?}  ?>

									<? if($ks_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ks_adddate?><br>
												<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "K.SIVALINGAM";?>
											</label><br><? if($ks_adddate != '') { ?>MANAGING DIRECTOR<? } else { ?>DIRECTOR / MANAGING DIRECTOR<? } ?></label> <!-- MANAGING DIRECTOR / MD -->
										</td>
									<? } elseif($sql_hir[0]['KS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br>
											<label style="color: black;font-weight:bold;margin-right:  15px;">
												<?echo "K.SIVALINGAM";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'>MANAGING DIRECTOR</label>
										</td>
									<?} ?>
								</tr>
								 <tr><td colspan="7" style="padding:10px;"></td></tr>
								<?}?>

								<? 	// print_r($hod_sign); echo "<br>--------";
									// $bm_name = array_values(array_unique($bm_name));
									$bm_sign = array_values(array_unique($bm_sign));
									$bm_dept = array_values(array_unique($bm_dept));
									// print_r($bm_sign); echo "<br>--------";

									if(count($bm_name) > 0) {
									for($hodi = 0; $hodi < count($bm_name); $hodi++) {
											if($bm_sign[$hodi] != '') { ?>
												<td style='<?=$txtalign?>'>
													<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$bm_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Branch Manager -->
												</td>
											<? } else { ?>
												<td style='<?=$txtalign?>'>
													<label style='width:14%; font-weight:bold' class="green_highlight"><?=$bm_name[$hodi]?></label> <!-- Branch Manager -->
												</td>
										<? }
									} }

									// print_r($hod_sign); echo "<br>--------";
									//$hod_name = array_values(array_unique($hod_name));
									$hod_sign = array_values(array_unique($hod_sign));
									$hod_dept = array_values(array_unique($hod_dept));
									// print_r($hod_sign); echo "<br>--------";

									if(count($hod_name) > 0) {
									for($hodi = 0; $hodi < count($hod_name); $hodi++) {
											if($hod_sign[$hodi] != '') { ?>
												<td style='<?=$txtalign?>'>
													<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$hod_sign[$hodi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- HOD / Branch Manager -->
												</td>
											<? } else { ?>
												<td style='<?=$txtalign?>'>
													<label style='width:14%; font-weight:bold' class="green_highlight"><?=$hod_name[$hodi]?></label> <!-- HOD / Branch Manager -->
												</td>
										<? }
									} } ?>

								<? if($srexc_mgr_name_audit != '') { // echo "CAM".$srexc_mgr_empsrno_audit."E"; ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#000000; width:14%; font-size:10px;'><? if($srexc_mgr_sign_audit != '') { ?>
										<img src='<?=$srexc_mgr_sign_audit?>' border=0 <? if($srexc_mgr_empsrno_audit == 62762) { ?> style='border:0px solid #d8d8d8; width:45px;' <? } else { ?> style='border:0px solid #d8d8d8; width:85px; height:25px;' <? } ?>>
									<? } else { echo $srexc_mgr_name_audit; } ?></label> <!-- S-Audit -->
								</td>
								<? } ?>

								<? if($cc_name != '') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#000000; width:14%; font-size:10px;'><img src='<?=$cc_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Cost Control -->
								</td>
								<? } ?>

								<?	//$gm_name = array_values(array_unique(array_reverse($gm_name)));
									$gm_sign = array_values(array_unique(array_reverse($gm_sign)));
									$comnts_gmuser = array_values(array_unique(array_reverse($comnts_gmuser)));
									// print_r($gm_name); print_r($gm_sign);
									if(count($gm_name) > 0) {
									for($gmi = 0; $gmi < count($gm_name); $gmi++) { ?>
										<td style='<?=$txtalign?>'>
											<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$gm_sign[$gmi]?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- GM -->
										</td>
								<? } } ?>

								<? if($srgm_name != '') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$srgm_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- Sr.GM -->
								</td>
								<? } ?>

								<?
								if($tot_len <=7){
								if($ak_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ak_sign?>' border=0 style='border:0px solid #d8d8d8; height:45px;'></label> <!-- AK -->
								</td>
								<? } ?>

								<? if($ps_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ps_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- PS Madam -->
								</td>
								<? } ?>

								<? if($ks_name != '') { ///////////////// and $sql_reqid[0]['APPSTAT'] != 'A') { ?>
								<td style='<?=$txtalign?>'>
									<label style='color:#0088CC; width:14%; font-weight:bold'><img src='<?=$ks_sign?>' border=0 style='border:0px solid #d8d8d8; width:85px; height:25px;'></label> <!-- CEO & DIRECTOR / MD -->
								</td>
								<? }} ?>
							</tr>

							<tr style="vertical-align: top;">
								<? $dept_n = "";
								if(count($bm_name) > 0) {
									for($hodi = 0; $hodi < count($bm_name); $hodi++) {
										$dept_n = substr($bm_dept[$hodi], 3); ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$bm_adddate[$hodi]?><br>
											<label style="color: black;"><?$b_name = explode(' - ', $bm_name[$hodi]);
											echo $b_name[1];?></label><br>
												<? if($bm_dsgn[$hodi] != '') { echo $bm_dsgn[$hodi]; } else { echo "BM"; } ?></label> <!-- Branch Manager -->
										</td>
								<? } } elseif($dept_n != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$dept_n?> BM</label> <!-- Branch Manager -->
									</td>
								<? }
								$dept_n = "";
								if(count($hod_name) > 0) {
									for($hodi = 0; $hodi < count($hod_name); $hodi++) {
										$dept_n = substr($hod_dept[$hodi], 3); ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold;'><?=$hod_adddate[$hodi]?><br>
												<label style="color: black;"><?$h_name = explode(' - ', $hod_name[$hodi]);
											echo $h_name[1];?></label><br>
											<? if($hod_dsgn[$hodi] != '') { echo $hod_dsgn[$hodi]; } else { echo "DGM/HOD"; } ?></label> <!-- DGM / HOD -->

										</td>
								<? } } elseif($dept_n != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'><?=$dept_n?> DGM/HOD/BM</label> <!-- DGM / HOD -->
									</td>
								<? } ?>

								<? if($srexc_mgr_name_audit != '') { ?>
								<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
									<label style='color:#0088CC; font-weight:bold'><?=$srexc_mgr_adddate_audit?><br>
									<label style="color: black;"><?$sr_m_name = explode(' - ', $srexc_mgr_name_audit);
											echo $sr_m_name[1];?></label><br>
										<?=$srexc_mgr_desg?></label> <!-- S-Audit -->
								</td>
								<? } ?>

								<? if($cc_name != '') { ?>
								<td style='<?=$txtalign?> width:14%; font-size:11px;'>
									<label style='color:#0088CC; font-weight:bold'><?=$cc_adddate?><br>
									<label style="color: black;">
										<?$c_name = explode(' - ', $cc_name);
										echo $c_name[1];?>
									</label><br>
									<?=$cc_desg?></label> <!-- Cost Control -->
								</td>
								<? } ?>

								<? // print_r($gm_name);
								if($_REQUEST['typeid'] ==3)
								{
									echo "<br>";
								}
								if(count($gm_name) > 0) {
									for($gmi = 0; $gmi < count($gm_name); $gmi++) { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$gm_adddate[$gmi]?><br>
											<label style="color: black;">
												<?$g_name = explode(' - ', $gm_name[$gmi]);
												echo $g_name[1];?>
											</label><br>
											<? if($gm_adddate[$gmi] != '') { echo $comnts_gmuser[$gmi]; ?>GM<? } else { ?>Sr.GM / GM / DGM<? } ?></label> <!-- GM -->
										</td>
								<? } } elseif($sql_reqid[0]['APPSTAT'] != 'A' and $srgm_name == "" ) { /* ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;'>
										<label style='color:#0088CC; font-weight:bold'>Sr.GM / GM / DGM</label> <!-- GM -->
									</td>
								<? */ } ?>

								<? if($srgm_name != '') { ?>
									<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
										<label style='color:#0088CC; font-weight:bold'><?=$srgm_adddate?><br>
										<label style="color: black;">
											<?$srg_name = explode(' - ', $srgm_name);
											echo $srg_name[1];?>
										</label><br>
										<? if($srgm_adddate != '') { ?>Sr.GM<? } else { ?>Sr.GM / GM / DGM<? } ?></label> <!-- Sr.GM -->
									</td>
								<? } ?>

								<?
									if($tot_len <= 7){
									if($ak_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ak_adddate?><br>
											<label style="color: black;font-weight:bold;">
												<?echo "S KAARTHI";?>
											</label><br><?=$sql_desname[0]['DESNAME']?></label> <!-- CEO & DIRECTOR / AK -->
										</td>
									<? } elseif($ceo_available == 1) { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<br>
											<label style="color: black;font-weight:bold;">
												<?echo "S KAARTHI";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'><?=$sql_desname[0]['DESNAME']?></label> <!-- CEO & DIRECTOR / AK -->
										</td>
									<? }elseif($sql_hir[0]['AK'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br><label style="color: black;font-weight:bold;">
												<?echo "S KAARTHI";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'><?=$sql_desname[0]['DESNAME']?></label>
										</td>
									<?} ?>


									<?/* if($ps_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ps_adddate?><br>DIRECTOR</label> <!-- DIRECTOR / PS Madam -->
										</td>
									<? } elseif($sql_reqid[0]['APPSTAT'] != 'A') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<label style='color:#0088CC; font-weight:bold'>DIRECTOR / MANAGING DIRECTOR</label> <!-- DIRECTOR / PS Madam -->
										</td>
									<? }*/ ?>

									<?/* if($ks_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ks_adddate?><br><? if($ks_adddate != '') { ?>MANAGING DIRECTOR<? } else { ?>DIRECTOR / MANAGING DIRECTOR<? } ?></label> <!-- MANAGING DIRECTOR / MD -->
										</td>
									<? }*/ /*elseif($sql_reqid[0]['APPSTAT'] != 'A') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<label style='color:#0088CC; font-weight:bold'>MANAGING DIRECTOR</label> <!-- MANAGING DIRECTOR / MD -->
										</td>
									<? } */ ?>

									<? if($ps_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ps_adddate?><br>
											<label style="color: black;font-weight:bold;">
												<?echo "PADHMA SIVLINGAM";?>
											</label><br>DIRECTOR</label> <!-- DIRECTOR / PS Madam -->
										</td>
									<? } /*elseif($sql_reqid[0]['APPSTAT'] != 'A') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<label style='color:#0088CC; font-weight:bold'>DIRECTOR / MANAGING DIRECTOR</label> <!-- DIRECTOR / PS Madam -->
										</td>
									<? }*/elseif($sql_hir[0]['PS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br>
											<label style="color: black;font-weight:bold;">
												<?echo "PADHMA SIVLINGAM";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'>DIRECTOR</label>
										</td>
									<?}  ?>

									<? if($ks_name != '') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<label style='color:#0088CC; font-weight:bold'><?=$ks_adddate?><br>
												<label style="color: black;font-weight:bold;">
												<?echo "K.SIVALINGAM";?>
											</label><br><? if($ks_adddate != '') { ?>MANAGING DIRECTOR<? } else { ?>DIRECTOR / MANAGING DIRECTOR<? } ?></label> <!-- MANAGING DIRECTOR / MD -->
										</td>
									<? } elseif($sql_hir[0]['KS'] != 0 && $sql_reqid[0]['APPSTAT'] != 'A'){?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;vertical-align: top;'>
											<br>
											<label style="color: black;font-weight:bold;">
												<?echo "K.SIVALINGAM";?>
											</label><br>
											<label style='color:#0088CC; font-weight:bold'>MANAGING DIRECTOR</label>
										</td>
									<?}
									}
									/*elseif($sql_reqid[0]['APPSTAT'] != 'A') { ?>
										<td style='<?=$txtalign?> width:14%; font-size:11px;'>
											<label style='color:#0088CC; font-weight:bold'>MANAGING DIRECTOR</label> <!-- MANAGING DIRECTOR / MD -->
										</td>
									<? } */ ?>
								</tr>
							</table>
						</td></tr>

					</table>
				</td>
				</tr>
				<!-- Approval Desk Purpose Only -->


				</table>
				</td></tr>
			</table>
		</td>
		</tr>
		</table>


		<? if(count($sql_prdlist) > 0 and $edtvl == 0) {
			/* $sql_prdlist = select_query_json("select * from APPROVAL_PRODUCTLIST PRD, APPROVAL_PRODUCT_QUOTATION QUT
			    												where PRD.PBDYEAR = QUT.PBDYEAR AND PRD.PBDCODE = QUT.PBDCODE and PRD.PBDLSNO = QUT.PBDLSNO and
			    													QUT.PBDYEAR = '".$sql_reqid[0]['ARQYEAR']."' AND QUT.PBDCODE = '".$sql_reqid[0]['IMUSRIP']."' and QUT.SLTSUPP = 0
			    												order by PRD.PBDYEAR, PRD.PBDCODE, PRD.PBDLSNO", 'Centra', 'TEST');
			if(count($sql_prdlist) > 0) { ?>
			<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='margin-top: 5px; border:1px solid #303030; background-color: #ffffff; border-style:dashed; width:796.8px; max-height:1123.2px; padding:7px'>
			<tr><td class="cls_pagebreak"></td></tr>
			<!-- Page 2 -->
			<tr><td colspan="2" style="text-align: center; padding-top: 0px; font-weight: bold;"> Competitive Suppliers </td></tr>
			<tr><td colspan='2'>
			<table class="monthyr_wrap" style='width:100%; line-height:22px;'>
			<tr><td width="25%"></td><td width="25%"></td><td width="25%"></td><td width="25%"></td></tr>
			<tr style='border:1px solid #0088CC; width:100%;'>
				<!-- Supplier Quotation -->
				<table style="width: 100%; line-height: 15px;">
					<tr class="row" style="margin-right: -5px; text-align: center; text-transform: uppercase; background-color: #f0f0f0; color:#000; border-top-left-radius:5px; border-top-right-radius: 5px; display: flex; font-size: 11px; font-weight: bold; width: 100%;">
						<td class="colheight" style="padding: 0px; border-top-left-radius:5px;width: 2%;">#</td>
						<td class="colheight" style="padding: 0px;width: 25%;">Supplier</td>
						<td class="colheight" style="padding: 0px;width: 28%;">Product</td>
			            <td class="colheight" style="padding: 0px;width: 8%;">Per Piece Rate</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Tax</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Discount %</td>
			            <td class="colheight" style="padding: 0px;width: 7%;">Qty.</td>
			            <td class="colheight" style="padding: 0px;width: 10%;">Net Amount</td>
					</tr>
				<?

					$inc = 0;
					foreach($sql_prdlist as $prdlist) { $inc++; ?>
						<tr class="row" style="margin-right: -5px; text-align: center; background-color: transparent; color:#000; display: flex; font-size: 11px; text-transform: uppercase;">
							<td class="colheight" style="padding: 1px 0px; width: 2%;"><?=$inc?></td>
							<td class="colheight" style="padding: 1px 0px 1px 3px; width: 25%; text-align: left;">
								<?=$prdlist['SUPCODE']." - ".$prdlist['SUPNAME']?></span>
							</td>
							<td class="colheight" style="padding: 1px 0px 1px 3px; width: 28%; text-align: left;">
								<?=$prdlist['PRDCODE']." - ".$prdlist['PRDNAME']?> <br>
								<span style="font-size: 9px;color: #a0a0a0;">( <?=$prdlist['PRDSPEC']?> )
									<? if($prdlist['ADURATI'] == '0') { echo ""; } else { echo "AD. DURATION : ".$prdlist['ADURATI'].""; } ?>
					    			<? if($prdlist['ADLENGT'] == '0' and $prdlist['ADWIDTH'] == '0') { echo ""; }
					    			   else { echo "SIZE ( L X W ) : ".$prdlist['ADLENGT']." X ".$prdlist['ADWIDTH'].""; } ?>
					    			<? if($prdlist['ADLOCAT'] == '0') { echo ""; } else { echo "AD. PRINT LOCATION : ".$prdlist['ADLOCAT'].""; } ?></span>
							</td>
				    		<td class="colheight" style="padding: 1px 0px; width: 8%;"><? $expl1 = explode(".", $prdlist['PRDRATE']); echo moneyFormatIndia($expl1[0]); if($expl1[1] != '') { echo ".".$expl1[1]; } ?><br><span style="font-size: 9px;color: #a0a0a0;">Adv. Amt. : <?=moneyFormatIndia($prdlist['ADVAMNT'])?></span></td>

				            <td class="colheight" style="padding: 1px 0px; width: 10%;">
				    			<? 	$ttlqty = 0;
				    				$ttlqty = $prdlist['PRDRATE'] * $prdlist['TOTLQTY'];
				    				if($prdlist['SGSTVAL'] == '0' ) { echo ""; } else { $sc_per = 0; $sc_per = round(($prdlist['SGSTVAL'] / $ttlqty) * 100, 2); echo "SGST (".$sc_per." %) : ".$prdlist['SGSTVAL']."<BR>"; } ?>
				    			<? if($prdlist['CGSTVAL'] == '0' ) { echo ""; } else { $cc_per = 0; $cc_per = round(($prdlist['CGSTVAL'] / $ttlqty) * 100, 2); echo "CGST (".$cc_per." %) : ".$prdlist['CGSTVAL']."<BR>"; } ?>
				    			<? if($prdlist['IGSTVAL'] == '0' ) { echo ""; } else { $ic_per = 0; $ic_per = round(($prdlist['IGSTVAL'] / $ttlqty) * 100, 2); echo "IGST (".$ic_per." %) : ".$prdlist['IGSTVAL']."<BR>"; } ?>
				    		</td>
				            <td class="colheight" style="padding: 1px 0px; width: 10%;">
				    			<? if($prdlist['DISCONT'] == '0' ) { echo ""; } else { $ds_per = 0; $ds_per = round(($prdlist['DISCONT'] / $prdlist['PRDRATE']) * 100, 2); echo "(".$ds_per." %) ".$prdlist['DISCONT']."<BR>"; } ?>
				    		</td>

				            <td class="colheight" style="padding: 1px 0px; width: 7%;"><?=$prdlist['TOTLQTY']?></td>
				            <td class="colheight" style="padding: 1px 0px; width: 10%; margin-right: 0.6%;"><?=moneyFormatIndia($prdlist['NETAMNT'])?></td>
						</tr>
					<? } ?>
				</table>
			</tr>
			<!-- Page 2 -->
			</table>
			</td>
		</tr>
		</table>
		<? } */ } ?>

			<div id='non-printable' style="text-align: center;">
				<!-- <button type="button" name='sbmt_print' id='sbmt_print' tabindex='26' value='print' class="btn btn-success" onclick="PrintDiv('<?=$sql_reqid[0]['APRNUMB']?>', '<?=$sql_reqid[0]['APPRMRK']?>');" data-toggle="tooltip" data-placement="top" style='cursor:pointer; text-align: center;' title="Print"><i class="fa fa-print"></i> Print</button> -->
				<center>
					<strong>
						<input type="button" class="btn btn-success" style="width: 10%;font-size: 17px;" onclick="PrintDiv();" value="Print">
					</strong> 
				</center> 
			</div>
<?

$addpage = 1;
if($appr_againstno == 1) {
	$addpage++; ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label style="font-weight:bold;">Page on : <?php echo $addpage; ?>/<?php echo $pagecount+$addpage; ?></label>
			</td>
		</tr>
		<tr>
			<td style='width:70%; height:20px; font-weight:bold;'>
				&nbsp;
			</td>

			<td style='width:30%; height:20px; text-align:right;'>
				<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
			</td>
		</tr>
		<tr>
			<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
				<iframe src="print_request.php?action=print&reqid=<? echo $sql_apr[0]['ARQCODE']; ?>&year=<? echo $sql_apr[0]['ARQYEAR']; ?>&rsrid=1&agnpr=1&creid=<? echo $sql_apr[0]['ATCCODE']; ?>&typeid=<? echo $sql_apr[0]['ATYCODE']; ?>" frameborder="0" height="800" width="100%"></iframe>
			</td>
		</tr>
	</table>
</div>
<? }

for($ij = 0; $ij < count($pagearry['img']); $ij++) { ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label style="font-weight:bold;">Page on : <?php echo $ij+$addpage+1; ?>/<?php echo $pagecount+$addpage; ?></label>
		</td>
	</tr>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			&nbsp;
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
		</td>
	</tr>
	<tr>
		<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
		<?
			$filename = $sql_docs[$pagearry['img'][$ij]]['APRDOCS'];
			$dataurl = $sql_docs[$pagearry['img'][$ij]]['APRHEAD'];

			$folder_path = "approval_desk/request_entry/".$dataurl."/";
			$thumbfolder_path = "approval_desk/request_entry/".$dataurl."/thumb_images/";
			echo $fieldindi = "<ul id=\"lightgallery\" class=\"list-unstyled row lightgallery\" style='float:left; margin-left:0px; margin-top: 0px;'><li data-responsive=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" data-src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\" style='cursor:pointer; float:left; margin-left:0px;' data-sub-html='<p><a href=\"javascript:void(0)\" onclick=\"call_rotatefunc()\" id=\"cboxRight\" style=\"color:#FFFFFF; font-size:20px;\"><img src=\"images/rotate.png\" alt=\"Rotate\" style=\"width:24px; height:24px; border:0px;\"> ROTATE</a></p>'><img style='width:100%; height:100%;' alt=".$filename." class=\"img-responsive style_box\" src=\"ftp_image_view.php?pic=".$filename."&path=".$folder_path."\"></li></ul>";
			?>
		</td>
	</tr>
</table>
</div>
<? }


if(count($pagearry['doc'])>0){ ?>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align='center' class="fixed" style='border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px'>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			<label style='font-size:13px;'><?=$sql_reqid[0]['APRNUMB']?></label> <!-- Approval Number -->
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label style="font-weight:bold;">Page on : <?php echo count($pagearry['img'])+$addpage+1; ?>/<?php echo $pagecount+$addpage; ?></label>
		</td>
	</tr>
	<tr>
		<td style='width:70%; height:20px; font-weight:bold;'>
			&nbsp;
		</td>

		<td style='width:30%; height:20px; text-align:right;'>
			<label>Print on : <?=date("d/m/Y h:i A")?>.(<?=$_SESSION['tcs_user']?>)</label> <!-- Current Date & Time -->
		</td>
	</tr>
	<tr>
		<td colspan="2" style='width:70%; height:20px; font-weight:bold;'>
		<? 	for($ij = 0; $ij < count($pagearry['doc']); $ij++){
				$filename = $sql_docs[$pagearry['doc'][$ij]]['APRDOCS'];
				$dataurl = $sql_docs[$pagearry['doc'][$ij]]['APRHEAD'];

				echo $fieldindi = "<a href=\"ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/request_entry/".$dataurl."/".$filename."\" target=\"_blank\" class=\"style_box\">".$filename."</a><br><br>";
			} ?>
		</td>
	</tr>
</table>
</div>
<? } ?>

	</form>
    <!-- /#wrapper -->

	<!-- Send Email -->
	<div id="myModal1" class="modal fade">
		<div class="modal-dialog" style='width:85%'>
			<div class="modal-content">
				<div class="modal-head" style='text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;'>Attachements</div>
				<div class="modal-body" id="modal-body1"></div>
			</div>
		</div>
	</div>
	<div id="myModal2" class="modal fade">
				<div class="modal-dialog" style='width:85%'>
					<div class="modal-content">
						<div class="modal-body" id="modal-body2"></div>
					</div>
				</div>
			</div>
	<!-- Send Email -->

    <script src="js/jquery_1.9.js"></script>
    <!-- Select2 -->
	<script src="js/select2.full.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
	<script src="js/jquery-customselect.js"></script>
	<link href="css/jquery-customselect.css" rel="stylesheet" />
	<script src="js/lightgallery.js"></script>
	<script type="text/javascript">
		function PrintDiv(aprnumb, cnt) {
			window.print();
		}

		jQuery.browser = {};
		(function () {
		    jQuery.browser.msie = false;
		    jQuery.browser.version = 0;
		    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
		        jQuery.browser.msie = true;
		        jQuery.browser.version = RegExp.$1;
		    }
		})();

		$(document).ready(function() {
			$(".chosn").customselect();
			//$('#load_page').hide();
			$("#comments_history").toggle(500);
			$('.lightgallery').lightGallery();
		});

		$(document).keydown(function(e) {
			// alert(e.keyCode+"***");
		    if (e.keyCode == 27) {
		        // $("#myModal1").fadeOut(500);
				$("#myModal1").modal('hide');
		    }
		});

		$("#comments_history_btn").click(function(){
			// alert("OPEN / CLOSE");
		    $("#comments_history").toggle(500);
		});

	function cmnt_mail(aprnumb)
	{
		var sendurl = "ajax/ajax_general_temp.php?action=SENDMAIL&aprnumb="+aprnumb;
		$.ajax({
		url:sendurl,
		success:function(data){
			$("#myModal2").modal('show');
			$('#modal-body2').html(data);
			$('#txtmailcnt').val("");
			}
		});
	}

	function cmt_usr()
	{
		$('#cmtusr').css("display", "block");
		$('.select2').select2();
		$('#mailusr').focus();
		//$("#mailusr").select2("open");
		$('#mailusr').select2({
        placeholder: 'Enter EC No / Name to Select an mail user',
		allowClear: true,
		dropdownAutoWidth: true,
		minimumInputLength: 3,
		maximumSelectionLength: 3,
	 	width: '50%',
		ajax: {
          url: 'ajax/ajax_general_temp.php?action=MAILUSER',
          dataType: 'json',
		  delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
	}
	function add_mail(aprnumb)
	{
		var mail = $('#mailusr').val();
		var content = $('#txtmailcnt').val();
		var sendurl = "ajax/ajax_general_temp.php?action=MAILINSERT&apprno="+aprnumb;
		$.ajax({
		url:sendurl,
		data: {
			mailusr:mail,
			content:content
		},
		success:function(data){
			$("#myModal2").modal('hide');
			}
		});
	}

		function popup_attachment(arqcode, arqyear, reqid, atccode, atycode, aprnumb) {
			$('#load_page').show();
			// var sendurl = "ftp_attachment.php?arqcode="+arqcode+"&arqyear="+arqyear+"&reqid="+reqid+"&atccode="+atccode+"&atycode="+atycode;
			var sendurl = "ftp_attachment.php?aprnumb="+aprnumb;
			$.ajax({
			url:sendurl,
			success:function(data){
					$("#myModal1").modal('show');
					$('#load_page').hide();
					document.getElementById('modal-body1').innerHTML=data;
					$('#load_page').hide();
					$('.lightgallery').lightGallery();
				}
			});
		}

		/******************** Change Default Alert Box ***********************/
		var ALERT_BUTTON_TEXT = "OK";
		/* if(document.getElementById) {
			window.alert = function(txt) {
				var ALERT_TITLE = "GA Title";

				var tga = document.getElementById("id_ga").value;
				createCustomAlert(tga, ALERT_TITLE);
			}
		} */

		function createCustomAlert(txt, title) {
			d = document;
			if(d.getElementById("modalContainer")) return;

			mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
			mObj.id = "modalContainer";
			mObj.style.height = d.documentElement.scrollHeight + "px";

			alertObj = mObj.appendChild(d.createElement("div"));
			alertObj.id = "alertBox";
			if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
			alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
			alertObj.style.visiblity="visible";

			h1 = alertObj.appendChild(d.createElement("h1"));
			h1.appendChild(d.createTextNode(title));

			msg = alertObj.appendChild(d.createElement("p"));
			//msg.appendChild(d.createTextNode(txt));
			msg.innerHTML = txt;

			btn = alertObj.appendChild(d.createElement("a"));
			btn.id = "closeBtn";
			btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
			btn.href = "#";
			btn.focus();
			btn.onclick = function() { removeCustomAlert();return false; }

			alertObj.style.display = "block";
		}

		function removeCustomAlert() {
			document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
		}

		function ful(){
			//alert('Alert this pages');
		}
		/******************** Change Default Alert Box ***********************/
	</script>
</body>
</html>
<?
	// Update into approval_request Table for Verify the Duplicate or Original Print
	if($sql_reqid[0]['APPSTAT'] == 'A'){
		$sql_list = select_query_json("select * from approval_request where arqsrno = 1 and aprnumb like '".$sql_reqid[0]['APRNUMB']."'", "Centra", 'TEST');
		$tbl_approval_request = "approval_request";
		$field_approval_request = array();
		$field_approval_request['APPRMRK'] 	= $sql_list[0]['APPRMRK'].$_SESSION['tcs_user']."-".date("d-m-y")."||";
		$where_approval_request = " arqsrno = 1 and aprnumb like '".$sql_reqid[0]['APRNUMB']."' ";
		// print_r($field_approval_request);
		$update_approval_request = update_dbquery($field_approval_request, $tbl_approval_request, $where_approval_request);
	}
	// Update into approval_request Table for Verify the Duplicate or Original Print */

ftp_close($ftp_conn); // Close FTP Connection.
}
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>
