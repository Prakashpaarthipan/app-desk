<?php 
require 'autoload.php';
use mikehaertl\wkhtmlto\Pdf;

$pdf = new Pdf(array(
    'ignoreWarnings' => true,
    'commandOptions' => array(
        'useExec' => true,      // Can help if generation fails without a useful error message
        'procEnv' => array(
            // Check the output of 'locale' on your system to find supported languages
            'LANG' => 'en_US.utf-8',
        ),
    ),
));
// $pdf->addPage('http://www.tcsportal.com/approval-desk/print_request.php?action=print&reqid=3232&year=2018-19&rsrid=1&creid=4&typeid=2');
$pdf->addPage('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ADMIN / INFO TECH 4003232 / 10-07-2018 / 3232 / 03:42 PM :: The Chennai Silks</title>
    <!-- Custom Fonts -->
    <link href="css/fontawesome/font-awesome.min.css" rel="stylesheet" type="text/css">

	<style>
	@charset "utf-8";
	/* CSS Document */
	@font-face {
		font-family: "freehand471";
		src: url("css/freehand471.ttf");
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
	.print_table th,
	.print_table td {
    	border:1px solid #a0a0a0 !important;
    	text-align: center !important;
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
         background: url("images/page-loader.gif") 50% 50% no-repeat #FFFFFF;
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

	#watermark {
		background:url("images/invalid_approval.png") center center no-repeat;
		opacity: 0.6;
		position: absolute;
		width: 100%;
		height: 100%;
		-webkit-transform: rotate(-45deg);
		-moz-transform: rotate(-45deg);
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
			// $( "#sbmt_print" ).trigger( "click" );
		});
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
		// $(".chosn").customselect();

        $(":submit").click(function () {
        	var rmrk = $("#txt_remarks").val();
        	var slt_intermediate_team = $("#slt_intermediate_team").val();
			if(rmrk == "") {
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Your Remarks is Empty. Kindly Add some Remarks here!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				$("#txt_remarks").val("");
				$("#txt_remarks").focus("");
			} else {
				var nm = this.name;
				var txt = nm.substring(5);
				var txt1 = txt;
				if(txt == "forward"){
					var txt = $("#sbmt_forward").val();
					// var txt = document.getElementById("sbmt_forward").value;
				}
				event.preventDefault();

				if(txt1 == "verification" && slt_intermediate_team == "") {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Kindly Choose Internal verification user first!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
					// $("#slt_intermediate_team").val("");
					$("#slt_intermediate_team").focus("");
				} else {
					okbtn = "OK";
					switch (txt1) {
						case "reject":
							okbtn = "REJECT";
							break;
						case "pending":
							okbtn = "PENDING";
							break;
						case "verification":
							okbtn = "OK";
							break;
						case "query":
							okbtn = "OK";
							break;
						case "approve":
							okbtn = "APPROVE";
							break;
						case "mdapprove":
							okbtn = "APPROVE";
							break;
						case "forward":
							okbtn = "APPROVE";
							break;
						case "response":
							okbtn = "RESPONSE";
							break;
						default:
							okbtn = "OK";
							break;
					}

					jConfirm("Are you sure to want to "+txt+" this!", "Confirmation Dialog",
					function(r) {
						// alert("**"+r); exit;
						if(r == true)
						{
							$("#hid_action").val(nm);
							$("#frm_print_request").submit();
						}
					}, okbtn, "CANCEL");
				}
    		}
		});
    });

	function call_iv() {
		var intermediate_team = $("#slt_intermediate_team").val();
		if(intermediate_team != "") {
			$("#sbmt_reject").prop("disabled", true);
			$("#sbmt_pending").prop("disabled", true);
			$("#sbmt_verification").prop("disabled", false);
			$("#sbmt_query").prop("disabled", true);
			$("#sbmt_approve").prop("disabled", true);
			$("#sbmt_mdapprove").prop("disabled", true);
			$("#sbmt_forward").prop("disabled", true);
			$("#sbmt_response").prop("disabled", true);
		} else {
			$("#sbmt_reject").prop("disabled", false);
			$("#sbmt_pending").prop("disabled", false);
			$("#sbmt_verification").prop("disabled", true);
			$("#sbmt_verification").css("background-color", "#428bca");
			$("#sbmt_query").prop("disabled", false);
			$("#sbmt_approve").prop("disabled", false);
			$("#sbmt_mdapprove").prop("disabled", false);
			$("#sbmt_forward").prop("disabled", false);
			$("#sbmt_response").prop("disabled", false);
		}
	}
	</script>
</head>
<body>
	<div id="load_page" class="loader" style="display:block;"></div>
		<div class="page">
			<form role="form" id="frm_print_request" name="frm_print_request" action="" method="post" enctype="multipart/form-data">
	<table border=0 cell-padding=1 cell-spacing=1 align="center" class="fixed" style="border:5px solid #303030; background-color: #ffffff; border-style:double; width:796.8px; max-height:1123.2px; padding:7px">

	<tr>
			<td style="width:70%; height:20px; font-weight:bold; vertical-align: top;">
				<label style="font-size:13px;">ADMIN / INFO TECH 4003232 / 10-07-2018 / 3232 / 03:42 PM</label> <!-- Approval Number -->
			</td>
			<td style="width:30%; height:20px; text-align:right;">
				<label style="font-weight:bold;">Page on : 1/2</label><br>
									<label>Print on : 12/07/2018 01:17 PM.(22805)</label> <!-- Current Date & Time -->
							</td>
		</tr>
	




		<tr>
			<td colspan=2 style="height: 140px;">
			<table width="100%">
				<tr>
					<td rowspan=3 style="width:20%; text-align:center;">
													<img src="images/approval_process.png" style="width:100px; height:100px;" border=0>
											</td>
					<td style="width:60%; height:25px; padding-top:10px; padding-bottom:10px; text-align:center;">
						<label style="color:#0088CC; font-weight:bold"><a target="_blank" href="index.php"><img src="images/logo.png" border="0"></a></label> <!-- Chennai Silks -->
					</td>
					<td rowspan=3 style="width:20%; text-align:center;">&nbsp;
											</td>
				</tr>

				<tr>
					<td style="width:60%; height:20px; text-align:center;">
						<label style="color:#0088CC; font-weight:bold">Inter Office Correspondence</label> <!-- Chennai Silks -->
					</td>
				</tr>

				<tr>
					<td style="width:60%; height:20px; text-align:center;">
						<label style="color:#000000; font-weight:bold">Submitting for Approval</label> <!-- Submitting For -->
					</td>
				</tr>

				<tr>
					<td colspan=3 style="width:100%; height:20px; text-align:right;">
						<label>Date : 10/07/2018<br>
							Process Priority : 									<span class="badge badge-success" style="font-size:20px; background-color:#299654; font-weight:bold;">AP-3</span>
														</label> <!-- Created Date -->
					</td>
				</tr>
			</table>
			</td>
		</tr>

				<tr>
		<td colspan=2 style="width:100%; vertical-align:top; text-align:left;">
			<table border=0 style="width:100%; max-width: 773px; ">

				<tr><td>
								<table border=0 style="width:100%;">
				<tr style="min-height:25px !important; line-height:25px !important;">
					<td colspan=2 style="width:100%; height:20px; text-align:left;">
						<label>Good Day Sir,</label> <!-- Good Day Sir, -->
					</td>
				</tr>

				
				<tr style="min-height:20px; line-height:20px;">
					<td style="font-size:16px; font-weight:bold; width:20%; text-align:left;">
						<label>Subject</label> <!-- Approval Listings -->
					</td>
					<td style="width:80%; text-align:left;">
						<label>: <span style=" font-size: 18px; font-weight: bold;" class="blue_highlight">SERVER IMPLEMENTATION </span></label> <!-- Approval Listings -->
					</td>
				</tr>

				<tr style="min-height:25px; line-height:25px;">
					<td style="width:20%; padding-top:5px; text-align:left;">
						<label style=" font-size:16px; font-weight:bold">Branch / Project</label> <!-- Project Name -->
					</td>
					<td style="width:80%; padding-top:5px; text-align:left;">
						<table style="width: 100%">
						<tr style="min-height:20px; line-height:20px;">
							<td style="width:70%; text-align:left;">
								<label>: <label style=" font-size:16px; font-weight:bold;">CWH / </label><label style=" font-size:16px; font-weight:bold;border: 0px solid #00a1ff;padding: 3px;">10 - CORPORATE OFFICE</label> [ REGULAR PROCESS APPROVAL ] <!-- Project Name -->
							</td>

													</tr>
						</table>
					</td>
				</tr>

									<tr style="min-height:20px; line-height:20px;">
						<td style="width:20%; text-align:left;">
							<label>Approval Mode</label> <!-- Specification -->
						</td>
						<td style="width:80%; text-align:left;">
							<label>: IMPLEMENTATION</label> <!-- Specification -->
						</td>
					</tr>
				
				
				
				
				
				
								<tr style="min-height:20px; line-height:20px;">
					<td colspan=2 style="width:100%; text-align:left;"></td>
				</tr>
				
				

				


								<!-- Approval Type & Responsible Person -->
				<tr style="height:20px;"><td></td></tr>


				<tr style="min-height:20px !important; max-width: 773px; line-height:20px !important;">
					<td colspan=2>
						<table border=0 width="100%" style="max-width: 773px; border: 1px solid #0088CC; min-height: 70px; height:auto; padding:3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
							<tr style="min-height:30px; vertical-align:top; line-height:30px; font-size: 11px;">
								<td style="width:20%; text-align:left;">
									<label>Details : </label> <!-- Details -->
								</td>
							</tr>

							<tr style="">
								<td style="width:100%; text-align:left; max-width: 760px !important; overflow-x: auto !important;">
									<label class="print_table"><p>Sir, We need to add the Monthwise Budget add purpose for current Quarter in Fixed Budget in Approval Desk Request Entry form.</p>

<p>This Monthwise Budget is allowed only for the below 5 Target Numbers&nbsp;</p>

<p>9020 - CANTEEN EXPENSES<br />
9022 - DELIVER<br />
9025 - GIFT EXPENSES<br />
9075 - PETTY CASH<br />
9146 - TEST PURCHASE</p>

<p>And Secimal Point Add in Product Qty Purpose for Approval Request Entry form.</p>
</label> <!-- Details -->
								</td>
							</tr>

							<tr><td colspan="6">
								<table style="width:100%">
																		</tbody>
								</table>
								<table class="monthyr_wrap" style="width:100%; line-height:22px;">
								<tr><td width="25%"></td><td width="25%"></td><td width="25%"></td><td width="25%"></td></tr>
								<tr style="border:1px solid #0088CC; width:100%;">
								</table>								</tr>
							</table>

						<!-- General Master -->
			
		 <div style="margin:10px;">
		 <table style="margin:0 auto;width: 100%;" class="table table-bordered table-striped table-hover">
			<thead style="border-color: black;">
			 		<tr style="background-color: #f0f0f0;color: #1a0303;text-transform: uppercase;">
			 					 		<th class="colauto">
			 			#		 			</th>
			 					 		<th class="colauto">
			 			MASTER TABLE		 			</th>
			 					 		<th class="colauto">
			 			DESCRIPTION		 			</th>
			 					 	</tr>
			 	</thead>
			 	<tbody>
			 		<tr style="color: black;">
						 				<td  class="coltext" >
	 					1	 				</td>
					 				<td  class="coltext" >
	 					DEPARTMENT_ASSET (36), APPROVAL_MASTER (32), 	 				</td>
					 				<td  class="coltext" >
	 					MONTHWISE BUDGET PROCESS FOR CURRENT QUARTER	 				</td>
				<tr style="color: black;">
						 				<td  class="coltext" >
	 					2	 				</td>
					 				<td  class="coltext" >
	 					PRODUCT_ASSET (20), APPROVAL_MASTER (32), 	 				</td>
					 				<td  class="coltext" >
	 					PRODUCT QTY DECIMAL POINT VALUE	 				</td>
				<tr style="font-weight: bolder;font-size: larger;color: black;">						<td></td>
											<td></td>
											<td></td>
								 	</tbody>
			</table>
			</div>
		 			<!-- General Master -->

	<!-- STAFF night Duty START -->
		<!-- STAFF night Duty START -->

	<!-- ESI PF Multiple Branch -->
		<!-- ESI PF Multiple Branch -->

	<!-- STAFF MARRIAGE GIFT START -->
					</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr style="height:20px;"><td></td></tr>

			
			<tr style="height:20px;"><td></td></tr>






				<tr style="min-height:25px; line-height:25px;">
					<td style="width:100%; text-align:left;" colspan=2>
					<table width="100%" style="max-width: 773px; " border="1">

					<tr style="min-height:25px; line-height:25px;">
						<td style="width:16%; text-align:left;">
							<label>No. of Attachment</label> <!-- No. of Attachment -->
						</td>
						<td style="width:30%; text-align:left;">
							<label>: 										<a href="javascript:void(0)" onclick="popup_attachment("3232", "2018-19", "1", "4", "2", "ADMIN / INFO TECH 4003232 / 10-07-2018 / 3232 / 03:42 PM")" title="View" alt="View" style="font-weight:bold;" class="blue_highlight"><img src="images/attach.png" style="width: 32px; height: 32px; border: 0px;"> 1</a>
									 </label> <!-- No. of Attachment -->
						</td>
						
													<td style="width:35%; text-align:right;">
								<label>Implementation Due Date</label> <!-- Implementation Due Date -->
							</td>
							<td style="width:17%; text-align:left; font-weight: bold;">
								<label>: 24-JUL-2018 </label> <!-- Implementation Due Date -->
							</td>
						
											</tr>


				
				
				
				
				
				
				
				
									<tr style="min-height:20px; line-height:20px;">
						<td style="width:20%; text-align:left;">
							<label>Work Finish Target Date</label> <!-- Work Finish Target Date -->
						</td>
						<td style="width:80%; text-align:left;" colspan="3">
							<label>: 10-JUL-2018</label> <!-- Work Finish Target Date -->
						</td>
					</tr>
				
									<tr style="min-height:20px; line-height:20px;">
						<td style="width:20%; text-align:left;">
							<label>Agreement Expiry Date</label> <!-- Agreement Expiry Date -->
						</td>
						<td style="width:80%; text-align:left;" colspan="3">
							<label>: 10-JUL-2018</label> <!-- Agreement Expiry Date -->
						</td>
					</tr>
				
									<tr style="min-height:20px; line-height:20px;">
						<td style="width:20%; text-align:left;">
							<label>Agreement Advance Amount</label> <!-- Agreement Advance Amount -->
						</td>
						<td style="width:80%; text-align:left;" colspan="3">
							<label>: -</label> <!-- Agreement Advance Amount -->
						</td>
					</tr>
				
				<tr style="min-height:25px; max-width: 773px; width: 100%; line-height:25px;">
					<td style="width:20%; text-align:left;">
						<label>Approved Value</label> <!-- Approved Value -->
					</td>
					<td colspan=5 style="width:80%; text-align:left;">
						: <label style=" font-size:32px; font-weight:bold;" class="blue_highlight">--NIL--						 <!-- Approved Value -->
					</td>
				</tr>


				

				<!-- Expense Percentage -->
								<!-- Expense Percentage -->


								<tr style="min-height:25px; max-width: 773px; line-height:25px;">
					<td colspan=6 style="width:100%; text-align:left;">
					<table border=1 style="width:100%; max-width: 773px; min-height:100px; height:auto; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px;">
						<tr style="min-height:25px; line-height:25px;">
							<td style="width:100%; padding:0 10px; font-weight:bold; text-align:left;">
								<label>COMMENTS HISTORY</label> <!-- Comments History -->
							</td>
						</tr>

														<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>1062 - SIVALINGAM.N.  Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 06:49:11 PM )</label> : APPROVED (SIR, APPROVAL DESK OPTION ALTER APPROVAL)</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>S KAARTHI  Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 05:07:43 PM )</label> : VERIFY THIS APPROVAL</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>1986 - KUMARAN K  Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 05:07:43 PM )</label> : APPROVED</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>17108 - SELVA MUTHU KUMAR M.A Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 05:06:32 PM )</label> : APPROVED</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>1986 - KUMARAN K  Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 05:00:45 PM )</label> : ACKNOWLEDGE</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>2676 - SARAVANAN.P  Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 11-JUL-2018 03:39:10 PM )</label> : APPROVED</label> <!-- All User Comments -->
									</td>
								</tr>
															<tr style="min-height:25px; line-height:25px;">
									<td style="width:100%; padding:0 10px; text-align:left;">
										<label><b>13391 - PREMKUMAR K Comments </b><label style="height:27px;text-align:right;font-size: 8px;text-transform:uppercase;padding: 2px;font-weight: bolder;">( 10-JUL-2018 04:03:28 PM )</label> : APPROVED</label> <!-- All User Comments -->
									</td>
								</tr>
												</table>
					</td>
				</tr>
				
				</table>
				</td>
				</tr>
				<tr style="height:20px;"><td></td></tr>

				<tr>
					<td colspan=2 style="width:100%; max-width: 773px; padding-top:0px; text-align:left;">
						<label>Thanks & Regards</label> <!-- Thanks & Regards -->
					</td>
				</tr>

				<tr>
				<td colspan=2 style="width:100%; font-size:11px; text-align:left;">
					<table border=0 style="max-width: 773px; width:100%;">
					<tr>
						<td style="width:35%; text-align:left;">
							<label style="color:#000000; font-size:10px; "><b>14442 - ARUN RAMA BALAN G</b> - INFO TECH   <br>
								<b>Work Initiate Person : 14442 - ARUN RAMA BALAN G<br>
								<b>Responsible Person : 14442 - ARUN RAMA BALAN G</b></span></label> <!-- Responsible Person -->
							</label> <!-- Request Creator -->
						</td>

						
						
						
						
											</tr>

					<tr>
						<td style="width:35%; text-align:left;">
							<label style="color:#0088CC; font-weight:bold"><img src="images/tcs-logo.png" border=0 style="max-width:160px; height:auto; vertical-align:middle;" align="middle"><span style="vertical-align:middle"> - COR</span></label> <!-- Chennai Silks -->
						</td>

						
						
						
						
											</tr>

					</table>
				</td>
				</tr>
				<tr style="height:20px;"><td></td></tr>


								<!-- Approval Desk Purpose Only -->
				<tr>
				<td colspan=2 style="width:100%; font-size:10px; text-align:left;">
					<table border=0 style="max-width: 773px; width:100%; border:1px solid #0088CC; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding:5px 0px;min-height: 50px;">

					
						<tr><td colspan=4>
							<table border=0 cell-padding=1 cell-spacing=1 style="width:100%; padding-left:2px; padding-right:2px;">
								
								
								<tr>
																						<td style="text-align:center;">
														<label style="color:#0088CC; width:14%; font-weight:bold"><img src="ftp_image_view.php?pic=1280.png&path=approval_desk/digital_signature/" border=0 style="border:0px solid #d8d8d8; width:85px; height:25px;"></label> <!-- HOD / Branch Manager -->
													</td>
																									<td style="text-align:center;">
														<label style="width:14%; font-weight:bold" class="green_highlight">13391 - PREMKUMAR K</label> <!-- HOD / Branch Manager -->
													</td>
											

									
																		<td style="text-align:center;">
										<label style="color:#000000; width:14%; font-size:10px;"><img src="ftp_image_view.php?pic=61579.png&path=approval_desk/digital_signature/" border=0 style="border:0px solid #d8d8d8; width:85px; height:25px;"></label> <!-- Cost Control -->
									</td>
									
																				<td style="text-align:center;">
												<label style="color:#0088CC; width:14%; font-weight:bold"><img src="ftp_image_view.php?pic=452.png&path=approval_desk/digital_signature/" border=0 style="border:0px solid #d8d8d8; width:85px; height:25px;"></label> <!-- GM -->
											</td>
																				<td style="text-align:center;">
												<label style="color:#0088CC; width:14%; font-weight:bold"><img src="ftp_image_view.php?pic=168.png&path=approval_desk/digital_signature/" border=0 style="border:0px solid #d8d8d8; width:85px; height:25px;"></label> <!-- GM -->
											</td>
									
									
									
									
																	</tr>

								<tr>
																				<td style="text-align:center; width:14%; font-size:11px;vertical-align: top;">
												<label style="color:#0088CC; font-weight:bold;">11-JUL-2018<br>
													<label style="color: black;">SARAVANAN.P </label><br>
												 INFO TECH<br>DGM</label> <!-- DGM / HOD -->

											</td>
																				<td style="text-align:center; width:14%; font-size:11px;vertical-align: top;">
												<label style="color:#0088CC; font-weight:bold;">10-JUL-2018<br>
													<label style="color: black;">PREMKUMAR K</label><br>
												 INFO TECH<br>HOD</label> <!-- DGM / HOD -->

											</td>
									
									
																		<td style="text-align:center; width:14%; font-size:11px;vertical-align: top;">
										<label style="color:#0088CC; font-weight:bold">11-JUL-2018<br>
										<label style="color: black;">
											SELVA MUTHU KUMAR M.A										</label><br>
										 COST CONTROL<br>MANAGER</label> <!-- Cost Control -->
									</td>
									
																				<td style="text-align:center; width:14%; font-size:11px;vertical-align: top;">
												<label style="color:#0088CC; font-weight:bold">11-JUL-2018<br>
												<label style="color: black;">
													KUMARAN K 												</label><br>
												ADMIN GM</label> <!-- GM -->
											</td>
																				<td style="text-align:center; width:14%; font-size:11px;vertical-align: top;">
												<label style="color:#0088CC; font-weight:bold">11-JUL-2018<br>
												<label style="color: black;">
													SIVALINGAM.N. 												</label><br>
												MANAGEMENT GM</label> <!-- GM -->
											</td>
									
									


																			<td style="text-align:center; width:14%; font-size:11px;">
											<br>
											<label style="color: black;font-weight:bold;">
												S KAARTHI											</label><br>
											<label style="color:#0088CC; font-weight:bold">DIRECTOR</label> <!-- CEO & DIRECTOR / AK -->
										</td>
									

									
									
									
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


		
			<div id="non-printable" style="text-align: center;">
				<button type="button" name="sbmt_print" id="sbmt_print" tabindex="26" value="print" class="btn btn-success" onclick="PrintDiv("ADMIN / INFO TECH 4003232 / 10-07-2018 / 3232 / 03:42 PM", "");" data-toggle="tooltip" data-placement="top" style="cursor:pointer; text-align: center;" title="Print"><i class="fa fa-print"></i> Print</button>
			</div>
<div class="page">
<table border=0 cell-padding=1 cell-spacing=1 align="center" class="fixed" style="border:5px solid #303030; border-style:double; width:796.8px; max-height:1123.2px; padding:7px">
	<tr>
		<td style="width:70%; height:20px; font-weight:bold;">
			<label style="font-size:13px;">ADMIN / INFO TECH 4003232 / 10-07-2018 / 3232 / 03:42 PM</label> <!-- Approval Number -->
		</td>

		<td style="width:30%; height:20px; text-align:right;">
			<label style="font-weight:bold;">Page on : 2/2</label>
		</td>
	</tr>
	<tr>
		<td style="width:70%; height:20px; font-weight:bold;">
			&nbsp;
		</td>

		<td style="width:30%; height:20px; text-align:right;">
			<label>Print on : 12/07/2018 01:17 PM.(22805)</label> <!-- Current Date & Time -->
		</td>
	</tr>
	<tr>
		<td colspan="2" style="width:70%; height:20px; font-weight:bold;">
		<a href="ftp_image_view_pdf.php?pic=3232_2_4_2018-19_fieldimpl_p_0.pdf&path=approval_desk/request_entry/fieldimpl/" target="_blank" class="style_box">3232_2_4_2018-19_fieldimpl_p_0.pdf</a><br><br>		</td>
	</tr>
</table>
</div>

	</form>
    <!-- /#wrapper -->

	<!-- Send Email -->
	<div id="myModal1" class="modal fade">
		<div class="modal-dialog" style="width:85%">
			<div class="modal-content">
				<div class="modal-head" style="text-align:center; text-transform:uppercase; line-height: 40px; height: 40px; font-weight: bold; background-color: #f0f0f0;">Attachements</div>
				<div class="modal-body" id="modal-body1"></div>
			</div>
		</div>
	</div>
	<div id="myModal2" class="modal fade">
				<div class="modal-dialog" style="width:85%">
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
			//$("#load_page").hide();
			$("#comments_history").toggle(500);
			$(".lightgallery").lightGallery();
		});

		$(document).keydown(function(e) {
			// alert(e.keyCode+"***");
		    if (e.keyCode == 27) {
		        // $("#myModal1").fadeOut(500);
				$("#myModal1").modal("hide");
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
			$("#myModal2").modal("show");
			$("#modal-body2").html(data);
			$("#txtmailcnt").val("");
			}
		});
	}

	function cmt_usr()
	{
		$("#cmtusr").css("display", "block");
		$(".select2").select2();
		$("#mailusr").focus();
		//$("#mailusr").select2("open");
		$("#mailusr").select2({
        placeholder: "Enter EC No / Name to Select an mail user",
		allowClear: true,
		dropdownAutoWidth: true,
		minimumInputLength: 3,
		maximumSelectionLength: 3,
	 	width: "50%",
		ajax: {
          url: "ajax/ajax_general_temp.php?action=MAILUSER",
          dataType: "json",
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
		var mail = $("#mailusr").val();
		var content = $("#txtmailcnt").val();
		var sendurl = "ajax/ajax_general_temp.php?action=MAILINSERT&apprno="+aprnumb;
		$.ajax({
		url:sendurl,
		data: {
			mailusr:mail,
			content:content
		},
		success:function(data){
			$("#myModal2").modal("hide");
			}
		});
	}

		function popup_attachment(arqcode, arqyear, reqid, atccode, atycode, aprnumb) {
			$("#load_page").show();
			// var sendurl = "ftp_attachment.php?arqcode="+arqcode+"&arqyear="+arqyear+"&reqid="+reqid+"&atccode="+atccode+"&atycode="+atycode;
			var sendurl = "ftp_attachment.php?aprnumb="+aprnumb;
			$.ajax({
			url:sendurl,
			success:function(data){
					$("#myModal1").modal("show");
					$("#load_page").hide();
					document.getElementById("modal-body1").innerHTML=data;
					$("#load_page").hide();
					$(".lightgallery").lightGallery();
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
			//alert("Alert this pages");
		}
		/******************** Change Default Alert Box ***********************/
	</script>
</body>
</html>
');

if (!$pdf->saveAs('page.pdf')) {
    echo $pdf->getError();
}
$pdf->send('page.pdf');

?>