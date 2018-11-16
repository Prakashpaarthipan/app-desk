<?php
header('Cache-Control: no cache'); //no cache // This is for avoid failure in submit form  pagination form details page
session_cache_limiter('private_no_expire, must-revalidate'); // works // This is for avoid failure in submit form  pagination form details page

try {
error_reporting(0);
include('includes/config.php');
include("db_connect/public_functions.php");
//include_once('approval-desk-test/lib/function_connect.php');
// include('approval_desk/general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_userid'] == '') { ?>
	<script>window.location='logout.php?msg=session';</script>
<?php exit();
}

$bx_hdr = "#326296";
$tlhdft_tr = "#326296"; 
$bx_prm = "#A4CCF7"; 
$tl_hover = "#B8C3CB";
$tl_trodd = "#C2CEDB"; 
$tl_treven = "#E4E9ED";

if($_SESSION['loggedin_category'] == 'JEWELLERY') {
	$tcs_ktmtj = 'TJ';
	$tcs_ktmtj_img = '../images/jewellery.png';
} elseif($_SESSION['loggedin_category'] == 'BRANCH') {
	$tcs_ktmtj = 'BRANCH';
	$tcs_ktmtj_img = '../images/logo.png';
} else {
	$tcs_ktmtj = 'TCS';
	$tcs_ktmtj_img = '../images/logo.png';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?=$tcs_ktmtj?> Portal :: Dashboard :: <?php echo $site_title; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <link href="js/jqueryconfirm.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="bootstrap/css/style_slider.css" /
	<link rel="stylesheet" href="selection_process_design/bootstrap/popup/css/lightbox.css">
	<!------------------pie----------------------->
	<!------------------end pie----------------------->
		
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<style>
	.cnt_number { font-size: 34px; font-weight:bold; }
	.alert-close,.alert-close1,.alert-close2,.alert-close,.alert-close3,.alert-close4,.alert-close6,.alert-close7{
		background: url('images/close.png') no-repeat 0px 0px;
		cursor: pointer;
		height: 15px;
		width: 15px;
		position: relative;
		-webkit-transition: color 0.2s ease-in-out;
		-moz-transition: color 0.2s ease-in-out;
		-o-transition: color 0.2s ease-in-out;
		transition: color 0.2s ease-in-out;
		z-index: 1;
	}
	table { width:100%; height:150px; }
	.table_head { background-color: rgb(142, 0, 255); color: #fff; height: 40px; }
	.order_status th { padding: 15px; border: 1px solid #ccc; }
	.inner_tr { height: 40px; background-color: #0079A3; COLOR:#FFF; }
	.inner_tr td { border: 1px solid #ccc; padding: 15px }
	.bgclr { background-color: #FDEB88 !important; }
	.blink_me { color: #FF0000; }
	.row { margin-right: 0px !important; margin-left: -15px; }
	.btn { padding: 5px 10px !important; background: #F0F0F0 !important; color: #326296; }
	.next { background: #00A65A !important; color: #FFFFFF; padding: 6px 10px; }
	.dp_header { height: 30px; }
	.dp_footer { height: 30px; }
	#datepicker-date1 { text-transform : uppercase; } 
	#load_page {
         position: fixed;
         left: 0px;
         top: 0px;
         width: 100%;
         height: 100%;
         z-index: 10;
         opacity: 0.4;
         background: url('images/gears.gif') 50% 50% no-repeat rgb(249,249,249);
    }

	.box-header { background-color: <?=$bx_hdr?>; }
	.box.box-primary { border-top-color: <?=$bx_prm?>; }
	table thead tr { background-color: <?=$tlhdft_tr?>; }
	table tfoot tr { background-color: <?=$tlhdft_tr?>; }
	table tbody tr { color: #FFFFFF; }
	.table-striped > tbody > tr:nth-of-type(odd) { background-color: <?=$tl_trodd?>; color:#000; }
	.table-striped > tbody > tr:nth-of-type(even) { background-color: <?=$tl_treven?>; color:#000; }
	.table-hover > tbody > tr:hover { background-color: <?=$tl_hover?>; }
	.content-header {padding:0px !important;}
	.dataTables_paginate { text-align:right; }
	.dataTables_filter { text-align:right; }
	.next { padding: 0px !important; }
	.table { margin-bottom: 0px; }
	.pagination { margin: 0px 0; }

	.marquee {
		width: 450px;
		margin: 0 auto;
		margin-left: 35%;
		overflow: hidden;
		white-space: nowrap;
		box-sizing: border-box;
		animation: marquee 50s linear infinite;
		font-size: 18px;
		font-weight: bold;
		float:left;
		padding:10px;
		color:red;
	}
    .form-control
    {
        border-radius: 10px !important;
        border:1.5px solid #adadad;
    }
</style>
<link rel="stylesheet" href="css/jQuery_ui_org.css">
</head>
<body oncopy="return false" oncut="return false" onpaste="return false"	ondragstart="return false" onselectstart="return false" oncontextmenu="return false" class="skin-black">
	<div id="load_page" style='display:block;padding:12% 40%;'></div>
    <div class="wrapper">
	<? $marguee_text="COUNTER ROL FIXING";
	include("includes/header.php"); ?>
	<!-- Left side column. contains the logo and sidebar -->
	<? include("includes/left_panel.php"); ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper" style='min-height: 825px !important; background-color:#ffffff; font-size:14px; padding-top: 10px; margin-bottom: -15px;'>
	<?php
	$inner_menuaccess_pordappr = select_query("select * from trandata.SRM_MENU@tcscentr mnu, trandata.SRM_MENU_ACCESS@tcscentr acc 
														where mnu.MNUCODE = acc.MNUCODE and mnu.MAINMENU = 'PURCHASE' and mnu.SUBMENU = 'PURCHASE ORDER APPROVAL' and 
															( acc.SUPCODE = ".$_SESSION['tcs_userid']." or acc.entsrno = ".$_SESSION['tcs_empsrno']." ) and acc.VEWVALU = 'Y'
														order by mnu.MNUCODE Asc");
	if($_SESSION['loggedin_category'] == 'PURCHASE') { 
		if($inner_menuaccess_pordappr[0]['VEWVALU'] == 'Y') {
			$po_app = select_query("Select count(DISTINCT summ.poryear||'*'||summ.pornumb) CNT, summ.poryear, summ.pornumb 
											from trandata.purchase_order_summary@tcscentr summ, trandata.purchase_order_detail@tcscentr det, trandata.purchase_order_content@tcscentr con, 
												trandata.supplier@tcscentr sup, trandata.city@tcscentr cty, trandata.product@tcscentr prd, trandata.section@tcscentr sec, 
												trandata.purchasE_order_process_summary@tcscentr psum 
											where summ.poryear=det.poryear and summ.pornumb=det.pornumb and det.poryear=con.poryear and det.pornumb=con.pornumb and det.porsrno=con.porsrno and 
												summ.deleted='N' and prd.deleted='N' and summ.supcode=sup.supcode and sup.CtyCode=Cty.CtyCode and det.prdcode=prd.prdcode and 
												prd.secCode=Sec.SecCode and summ.poryear=psum.poryear and summ.pornumb=psum.pornumb and psum.autstat='N' and psum.deleted='N' and 
												Sec.SECCODE IN (".$_SESSION['tcs_section'].") 
											Group by summ.poryear, summ.pornumb, nvl(psum.entmode,'-') having (Select sum(porpiec-porrecv) from trandata.purchase_order_content@tcscentr 
												where poryear=summ.poryear and pornumb=summ.pornumb)>0"); ?>
		
				<? 	} 
				$ip=$_SERVER['REMOTE_ADDR'];
				$ip=explode('.',$ip);
				$ip=$ip[2];
				if($ip>=8 && $ip<=12)
					$brn=1;
				else if($ip>=16 && $ip<=20)
					$brn=2;
				else if($ip>=24 && $ip<=28)
					$brn=3;
				else if($ip>=32 && $ip<=36)
					$brn=4;
				else if($ip>=39 && $ip<=39)
					$brn=21;
				else if($ip>=40 && $ip<=44)
					$brn=5;
				else if($ip>=56 && $ip<=60)
					$brn=7;
				else if($ip>=64 && $ip<=68)
					$brn=8;
				else if($ip>=72 && $ip<=76)
					$brn=9;
				else if($ip>=80 && $ip<=84)
					$brn=10;
				

				else if($ip>=88 && $ip<=92)
					$brn=11;
				else if($ip>=40 && $ip<=44)
					$brn=5;
				else if($ip>=56 && $ip<=60)
					$brn=7;
				else if($ip>=64 && $ip<=68)
					$brn=8;
				else if($ip>=72 && $ip<=76)
					$brn=9;
				else if($ip>=80 && $ip<=84)
					$brn=11;


				 
                else if($ip>=88 && $ip<=92)
                    $brn=11;                
                else if($ip>=48 && $ip<=50 || $ip==52 || $ip==53)
                    $brn=888;
                else if($ip==51)
                    $brn=109;  
                else if($ip>=104 && $ip<=108)
                    $brn=12;  
                else if($ip>=112 && $ip<=116)
                    $brn=13;  
                else if($ip>=120 && $ip<=124)
                    $brn=14;  
                else if($ip==1000)
                    $brn=102;  
                else if($ip==1001)
                    $brn=103;  
                else if($ip>=96 && $ip<=100)
                    $brn=104;  
                else if($ip==10)
                    $brn=105; 
                else if($ip==1002)
                    $brn=107;  
                else if($ip==1003)
                    $brn=108;  
                else if($ip==1004)
                    $brn=111;  
                else if($ip==1005)
                    $brn=112;  
                else if($ip==1006)
                    $brn=113;  
                else if($ip==1007)
                    $brn=114;  
                else if($ip==1008)
                    $brn=116;  
                else if($ip==1)
                    $brn=888;  
                else if($ip>=128 && $ip<=132)
                    $brn=15;  
                else if($ip>=144 && $ip<=148)
                    $brn=17;  
                else if($ip>=136 && $ip<=140)
                    $brn=16;  
                else if($ip>=152 && $ip<=160)
                    $brn=19;  
                else if($ip==30)
                    $brn=20;  
                
               
                


				?>
					 
	  
	<!-- ////////////////////////////////// -->
	<div class="page-container page-navigation-top-fixed">
		  <form class="form-horizontal" role="form" id="customer_order" name="customer_order" action="viki/insert_req.php" method="post" enctype="multipart/form-data">
        <div class="page-content">
            <ul class="breadcrumb">
                <li><a href="home.php">Dashboard</a></li>
                <li class="active">Customer Order</li>
            </ul>
            <h2 style="margin-bottom: 20px;">Customer Order</h2>

            <div class="row" style="margin-left: 10px;margin-right: 10px;">
            	<div class="col-md-6">
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Company (or) Name : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="company_name" name="company_name" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Address 1 : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="address1" name="address1" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Address 2 : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="address2" name="address2" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Address 3 : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="address3" name="address3" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<?$sql_city = select_query("select ctycode,ctyname from city where deleted='N' order by ctycode");?>
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">City: <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<select class="form-control cls_required" id="txt_city" name="txt_city" style="text-transform: uppercase;" maxlength="100" ">
            					<?$ki=count($sql_city);
            					for($i=0;$i<$ki;$i++){?>
            						<option value="<?if($sql_city[$i]['CTYCODE']!='0'){echo($sql_city[$i]['CTYCODE']);}?>"><?=$sql_city[$i]['CTYNAME']?></option>
            					<?}?>
            				</select>
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Pin : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_pin" name="txt_pin" style="text-transform: uppercase;" maxlength="8" />
            			</div>
            		</div>
            		<!-- /////////////////////// -->
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Contact Person : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="txt_contact_person" name="txt_contact_person" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Phone : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_contact_person_phone" name="txt_contact_person_phone" style="text-transform: uppercase;" maxlength="12" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Mobile : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_contact_person_mobile" name="txt_contact_person_mobile" style="text-transform: uppercase;" maxlength="12" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">E-Mail : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="txt_email" name="txt_email" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="col-md-12 control-label">Adavance Receipt : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6" >
            				 <div class="col-md-10" style="padding-left:0px;">
		            		 	<input type="text" disabled class="col-md-4 form-control cls_required" id="txt_file1" name="txt_file1" style="text-transform: uppercase;" maxlength="100" />
		            		 </div>
		            		 <div class="col-md-2">
		            		 	 <div class="btn btn-danger btn-file"> <i class="glyphicon glyphicon-folder-open"></i><input name="advance_receipt" id="file-simple" type="file" onchange="loadfile(this,'txt_file1');"></div>
		            		 </div>
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="col-md-12 control-label">Customer Sample Pc`s : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				 <div class="col-md-10" style="padding-left:0px;">
		            		 	<input type="text" disabled class="col-md-4 form-control cls_required" id="txt_file2" name="txt_file2" style="text-transform: uppercase;" maxlength="100" />
		            		 </div>
		            		 <div class="col-md-2">
		            		 	 <div class="btn btn-danger btn-file"> <i class="glyphicon glyphicon-folder-open"></i><input id="file-simple" name="customer_sample" type="file" onchange="loadfile(this,'txt_file2');"></div>
		            		 </div>
            			</div>
            		</div>
            	</div>
            	<div class="col-md-6" style="padding-right: 10px !important;">
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Supplier Code  : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_supcode" name="txt_supcode" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Product Code : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_prdcode" name="txt_prdcode" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Sales Rate : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="sales_rate" name="sales_rate" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Qty : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_qty" name="txt_qty" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Mtrs : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_mtrs" name="txt_mtrs" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label ">BM ECNO : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required auto_complete" id="txt_bmsrno" name="txt_bmsrno" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		 <div style="margin:10px 10px;border-top: 1px solid #adadad;"></div>
            		 <!-- ////////////////////////////////////// -->
            		 <div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Adv. Amt : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_advamt" name="txt_advamt" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Payment Mode : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="txt_paymode" name="txt_paymode" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<?$sql_sec = select_query("select seccode,secname from trandata.section@tcscentr where deleted='N' order by seccode");?>
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Section : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<select class="form-control cls_required" id="txt_sec" name="txt_sec" style="text-transform: uppercase;" maxlength="100" >
            					<?$ki=count($sql_sec);
            					for($i=0;$i<$ki;$i++){?>
            						<option value="<?=$sql_sec[$i]['SECCODE']?>"><?=$sql_sec[$i]['SECNAME']?></option>
            					<?}?>
            				</select>
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Order Taken By : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required auto_complete" id="txt_order_taken_by" name="txt_order_taken_by" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Due Date : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required date" id="due_date" name="due_date" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Order Value : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required Number" id="txt_order_value" name="txt_order_value" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		<div class="row" style="margin-top: 7px;margin-bottom: 7px;">
            			<div class="col-md-4" style="vertical-align: middle;text-align: right;">
            				<label class="control-label">Order Detail : <span style='color:red'>*</span></label>
            			</div>
            			<div class="col-md-6">
            				<input type="text" class="form-control cls_required" id="txt_order_detail" name="txt_order_detail" style="text-transform: uppercase;" maxlength="100" />
            			</div>
            		</div>
            		 <!-- ////////////////////////////////////// -->
            	</div>
            </div>
            <!-- row 1 ends here -->
            <!-- row 2 ends here -->
            <div style="margin:10px 10px;border-top: 1px solid #adadad;"></div>
            <input type="button" style="margin-top: 10px;margin-left: 50%; font-size: 15px;" class="btn btn-primary" value="Submit" onclick="nsubmit()"/>
        </div>
    </form>
    </div>
	<!-- ///////////////////////////////////// -->
	<div style='clear:both'></div>
	
	<? } else { ?>
	<div class="full_w">
		<div class="welcomemsg" align="center">
			<p><br/><a href="welcome.php"><img src="<?=$tcs_ktmtj_img?>" alt="Logo" border="0" <? if($tcs_ktmtj == 'TJ') { ?>style="margin-left: 1px; margin-bottom: 10px; width: 300px; height:150px;"<? } else { ?>style="margin-left: 13px; margin-bottom: 10px;"<?/* ?>style="margin-left: 13px; margin-bottom: 10px; width: 320px; height:72px;"<? */ } ?> /></a><br/>Welcome to <b>Portal Dashboard</b></br>
			Please select a Menu from the left Panel.<br />
			<span align="center">Thanks!</span></p>
		</div>
	</div>
	<? } ?>
	
	<div style='clear:both'></div>
    </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->
	<div style='clear:both'></div>
    <? include("includes/footer.php"); ?>
    </div><!-- ./wrapper -->
	<div style='clear:both'></div>

    <!-- jQuery 2.1.3 --->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
	<script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0-->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes 
    <script src="dist/js/demo.js" type="text/javascript"></script>-->
	<!----------------------slider----------------------------------->
	
    <script src="chart/js/jquery.mousewheel.js" type="text/javascript"></script>
    <script src="chart/js/jquery.jqChart.min.js" type="text/javascript"></script>
    <? /* <script src="chart/js/jquery.jqRangeSlider.min.js" type="text/javascript"></script> */ ?>
	<script src="chart/js/plugin/sample_order_script.js" type="text/javascript"></script>
	<script src="selection_process_design/bootstrap/popup/js/lightbox.js"></script>
	<script type="text/javascript" src="bootstrap/js/jquery.leanModal.min.js"></script>
	<!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />-->
	<script type="text/javascript" src="bootstrap/js/zebra.js"></script>
    <script type="text/javascript" src="bootstrap/js/core2.js"></script>
	<!-- ECharts JavaScript -->
	<script src="approval_desk/js/echart/echarts-all.js"></script>
	<script src="approval_desk/js/echart/green.js"></script>
    <script src="js/plugins/fileinput/fileinput.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>

    <!-- custom js -->
    <script src="js/jquery_org.js"></script>
    <script src="js/jQuery_ui_org.js"></script>
    <script src="js/auto_complete.js"></script>
    <script type="text/javascript" src="js/jqueryconfirm.js"></script>
    <!-- custom js -->

    


	<!----------------------end of slider----------------------------------->
<script>
$('.Number').keypress(function (event) {
  var keycode = event.which;
  if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
      event.preventDefault();
  }
});
$('.date').keypress(function (event) {
  var keycode = event.which;
  event.preventDefault();
});

$(window).load(function() {
	$("#load_page").fadeOut("slow");
	$('#due_date').datepicker();
	
});
$(document).ready(function(){
auto_complete();
});
function loadfile(ele,id){
	//alert($(ele).val());
	var name=$(ele).val();
	name=name.split('.');
	if(name[1] == 'pdf' || name[1]=='doc' || name[1]=='docx' || name[1]=='jpg' || name[1]=='png' || name[1]=='jpeg' || name[1]=='bmp')
	{
		$('#'+id).val($(ele).val());
	}
	else
	{
		$(ele).val('');
		$.alert({
            title:"<span style='color : orange;'>Warning !</span>",
            content:"Only pdf and documents are Allowed"
        });
	}
}

function nsubmit(){
	var flagok=0;
	$('.cls_required').each(function(){
		if($(this).val()=='')
		{
			$(this).css('border','2px solid red');
			flagok=1;
		}
		else
		{
			$(this).css('border','2px solid #adadad');
		}
	});
	if(flagok==1)
	{
		//alert("Warning : All Fileds are Required!");
        $.alert({
            title:"<span style='color : orange;'>Warning !</span>",
            content:" All Fileds are Required!"
        });
		return;
	}
	var email=$('#txt_email').val();
	email=email.split('@');
	if(typeof email[1] == 'undefined')
	{
		// alert("Warning : Invalid Email !");
        $.alert({
            title:"<span style='color : orange;'>Warning !</span>",
            content:" Invalid Email !"
        });
		$('#txt_email').css('border','2px solid red');
		return;
	}
	email=email[1].split('.');
	if(typeof email[1] == 'undefined')
	{
		//alert("Warning : Invalid Email !");
        $.alert({
            title:"<span style='color : orange;'>Warning !</span>",
            content:" Invalid Email !"
        });
		$('#txt_email').css('border','2px solid red');
		return;
	}
	//alert("cont");
    // $.confirm({
    //     title: '<span style="color : orange;">Confirm !</span>',
    //     content:"Are you Sure to Commit ?",
    //     confirm: function(){
    //         alert('Confirmed!');
    //     },
    //     cancel: function(){
    //         alert('Canceled!');
    //     }
    // });
    // return;
	if(confirm("Are u Sure ?"))
    {
      $('#load_page').fadeIn('slow');
      var form_data = new FormData(document.getElementById('customer_order'));
      form_data.append("action","approve_user");
      $.ajax({
        url:"ajax/customer_order.php",
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        async:true,
        dataType:"html",
        success:function(data){

          //nview();
          console.log(data);
          //var action=$('#appstat').val();
          // alert("Action Successfull !");
          $.alert({
            title:"<span style='color : green;'>Success !</span>",
            content:"Action Successfull !"
        });
          //window.location.reload();
          $('#load_page').fadeOut('fast');
        }
      });
    }
}

	

</script>
	<script src="chart/js/line/raphael-min.js"></script>
	<script src="chart/js/line/morris.min.js" type="text/javascript"></script>
	<script src="chart/js/bar/amcharts.js"></script>
	<script src="chart/js/bar/serial.js"></script>
	<script src="chart/js/bar/light.js"></script>

    <!-- DATA TABES SCRIPT -->
    <script src="plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="js/dataTables.tableTools.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
	<script>

</script>
</body>
</html>
<? 
} 
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>