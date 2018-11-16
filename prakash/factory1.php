<?php
header('Cache-Control: no cache'); //no cache // This is for avoid failure in submit form  pagination form details page
session_cache_limiter('private_no_expire, must-revalidate'); // works // This is for avoid failure in submit form  pagination form details page

try {
error_reporting(E_ALL);
include('lib/config.php');
include("db_connect/public_functions.php");
include('lib/pagination.class.php');
extract($_REQUEST);
if($_SESSION['tcs_userid'] == '') { ?>
	<script>window.location='logout.php?msg=session';</script>
<?php
exit();
}
/*$menu_name = 'NEW DESIGN ENTRY';
$inner_submenu = select_query("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' order by MNUCODE Asc");
if($_SESSION['tcs_empsrno'] != '') {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and ENTSRNO = '".$_SESSION['tcs_empsrno']."' order by MNUCODE Asc");
} else {
	$inner_menuaccess = select_query("select * from srm_menu_access where MNUCODE = ".$inner_submenu[0][0]." and SUPCODE = '".$_SESSION['tcs_userid']."' order by MNUCODE Asc");
}
if($inner_menuaccess[0][6] == 'N' or $inner_menuaccess[0][6] == '') { ?>
<script>alert("You dont have access to view this"); window.location='home.php';</script>
<?
 exit();
}*/

?>
<!DOCTYPE html>
<html>
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Factory Evaluation ::  <?php echo $site_title; ?> </title>
	 <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="dist/newlte/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="dist/newlte/bower_components/Ionicons/css/ionicons.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="dist/newlte/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="dist/newlte/bower_components/select2/dist/css/select2.min.css">
  <link href="plugins/select2/select2.css" rel="stylesheet">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="dist/newlte/iCheck/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/newlte/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link href="dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- select 2 -->
  <link href="bootstrap/css/select2.css" rel="stylesheet"/>
  <link rel="stylesheet" href="css/scroll_to_top.css"> <!-- Gem style Scroll to TOP -->
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="dist/newlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <style type="text/css"> 
	.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.4;
		background: url('images/l2.gif') 50% 50% no-repeat rgb(249,249,249);
	}
	.loadinggif {
    background:url('images/l_spin_n.gif') no-repeat left;
	}
	.mine{font-size: 20px;color: red;}
	.colr_red{color:red;}
	/*.col-align{text-align: right; padding-top: 15px;}*/
	.machinery_input{width:190px;text-align: center;}
	</style>
	
	 
  </head>
<body oncopy="return false" oncut="return false" onpaste="return false"	ondragstart="return false" onselectstart="return false" oncontextmenu="return false" class="skin-black side">
   <div id='pageloader' class="loader"></div>
      <? include("includes/header.php"); ?>
      <? include("includes/left_panel.php"); ?>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
		<!-- Content Header (Page header) -->


		<!-- Main content -->
		<section class="content" style="padding: 0px;">

      <div class="box box-primary" >
        <div class="box-header with-border">
          <h1 style="text-transform:uppercase; float:left; margin: 0; font-size: 24px;"> Factory Evaluation </h1>
        </div>
        <form name="entry_form" id="entry_form" method="post" action="" enctype="multipart/form-data">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
             <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li  <? /*onclick='view_tab(1);' */?> class="active" id="tab1"><a href="#tab_1" data-toggle="tab">CONTACT</a></li>
              <li <? /*onclick='view_tab(2);' */?> id="tab2"><a href="#tab_2" data-toggle="tab">ORGANIZATION</a></li>
              <li <? /*onclick='view_tab(3);' */?> id="tab3"><a href="#tab_3" data-toggle="tab">PRODUCTION CONTROL</a></li>
              <li <? /*onclick='view_tab(4);' */?> id="tab4"><a href="#tab_4" data-toggle="tab">QUALITY MANAGEMENT</a></li>
              <li style="padding-left: 30%"> <!--<input type="button" name="newsave" id="newsave" value="SAVE" class="form-control btn-info" style="width: 85px;border-radius: 5px;">--> </li>
            </ul>

            <div class="tab-content col-md-12">
            <div class="tab-pane active" id="tab_1">
				<ul class="nav nav-tabs">
	              <li class="active" id="tab1_1"><a href="#tab_1_1" data-toggle="tab">GENERAL</a></li>
	              <li id="tab1_2"><a href="#tab_1_2" data-toggle="tab">COMPANY</a></li>
	            </ul>

	            <div class="tab-content col-md-12">
	            	<div class="tab-pane active" id="tab_1_1" style="padding-top: 10px;">
            			<table class="table">
	                	<thead>
	                		<th  colspan="6" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> GENERAL AUDIT DETAIL</th>
	                	</thead>
	                	<tbody>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Audit Type <span class="colr_red">*</span> </td>
	                			<td><select class="form-control" name="audit_type" id="audit_type" onchange="summary_data()" required style="width: 50%;">
	                			<option value="">Select Audit Type</option>
	                			<option value="I">Initial</option>
	                			<option value="F">Follow-up</option>
	                			<option value="D">During Production</option>
	                			<option value="O">Other</option>	
	                			</select>
	                			</td>
	                			<td style="text-align:right;padding-top: 15px;">Product Category <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="product_category" id="product_category" maxlength="1" onchange="summary_data()" required style="width: 50%;text-transform:uppercase;"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Audit Date <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" readonly="readonly" class="form-control" name="audit_date" id="audit_date" onchange="summary_data()" required style="width: 50%;"></td>
	                			<td style="text-align:right;padding-top: 15px;">Auditor's Name <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="audit_name" id="audit_name" onchange="summary_data()" maxlength="100" required style="width: 50%;text-transform:uppercase;"></td>
	                		</tr>
	                	</tbody>
	                </table>
	                <table class="table">
	                	<thead>
	                		<th colspan="6" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> SUPPLIER ADDRESS AND CONTACT</th>
	                	</thead>
	                </table>

	                <table class="table">
	                      		<tr>
	                			<div id="div_supp"></div>
	                			<td></td>
	                			<td></td>
	                			<td style="text-align: right;vertical-align: middle;"><b>Supplier Code / Name </b> <span class="colr_red">*</span> </td>
	                			<td style="width: 50%"><select name="slt_supplier" id="slt_supplier" class="form-control" required style="width: 50%" onchange="get_supplier();">
							<option value=""> select supplier </option>
								<?if($_REQUEST['slt_supplier'] != ""){ 
								$sql_supp =select_query ("SELECT distinct sup.supcode as CODE,sup.supname as NAME,cty.ctyname,sup.supmobi,sup.SUPADD1,sup.SUPADD2,sup.SUPADD3,sup.SUPPHN1,sup.SUPMOBI,sup.SUPMAIL
										FROM trandata.supplier@tcscentr sup, trandata.City@tcscentr cty 
										where sup.ctyCODE = cty.ctyCODE and sup.DELETED = 'N' and sup.SUPADD1 >= 7000 
										and sup.supcode=".$_REQUEST['slt_supplier']."
										order by sup.SUPCODE Asc");

							 ?>
							<option value="<?=$sql_supp[0]['SUPCODE']?>" <? if($_REQUEST['slt_supplier'] != '') { ?> selected <? } ?>> </option><?}?>
							</select>
	                		</tr>
	                	</table>

	                	<table class="table">	
	                		<tr>
	               				<!--<input type="textbox" class="form-control" name="slt_supplier" id="slt_supplier" style="width: 50%;">--></td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Address 1 <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="supp_add1" id="supp_add1" readonly="readonly" required style="width: 70%;text-transform:uppercase;">
	                				<input type="hidden" class="form-control" name="supp_code" id="supp_code" readonly="readonly" style="width: 70%;">
	                			</td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Phone</td>
	                			<td><input type="textbox" class="form-control" name="supp_phone" id="supp_phone" value="<?=$res['PHONE']?>" readonly="readonly" style="width: 70%"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Address 2 </td>
	                			<td><input type="textbox" class="form-control" name="supp_add2" id="supp_add2" readonly="readonly" style="width: 70%;text-transform:uppercase;"></td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Mobile <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="supp_mob" id="supp_mob" readonly="readonly" required style="width: 70%"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Address 3</td>
	                			<td><input type="textbox" class="form-control" name="supp_add3" id="supp_add3" readonly="readonly" style="width: 70%;text-transform:uppercase;"></td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">E mail</td>
	                			<td><input type="textbox" class="form-control" name="supp_email" id="supp_email" readonly="readonly" style="width: 70%;text-transform:uppercase;"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Country <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="supp_country" id="supp_country" value="India" readonly="readonly" required style="width: 70%;text-transform:uppercase;"></td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">City <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="supp_cty" id="supp_cty" readonly="readonly" required style="width: 70%;text-transform:uppercase;"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Position</td>
	                			<td><input type="textbox" class="form-control" name="supp_position" id="supp_position" readonly="readonly" maxlength="50" style="width: 70%;text-transform:uppercase;"></td>
	                			<td></td>
	                			<td style="text-align:right;padding-top: 15px;">Fax</td>
	                			<td><input type="textbox" class="form-control" name="supp_fax" id="supp_fax" readonly="readonly" maxlength="50" style="width: 70%"></td>
	                		</tr>
	                </table>
					</div>
        			<div class="tab-pane" id="tab_1_2" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                		<th colspan="6" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> Company Details</th>
	                	</thead>
	                	<tbody>
	                		<tr>
	                			<td style="text-align:right;padding-top: 15px;">Company Name <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="cmpy_name" id="cmpy_name" maxlength="50" onchange="summary_data()" required style="width: 50%;text-transform:uppercase;" tabindex="1"></td>
	                			<td style="text-align:right;padding-top: 15px;">City <span class="colr_red">*</span> </td>
	                			<td>
	                				<select tabindex="5" name="city_name" id="city_name" onchange="summary_data()" class="form-control" required style="margin-left:0px;width: 50%;">
									<option value=""> Choose Any City </option>
									<? $sql_city = select_query("Select CTYCODE,CTYNAME from City where deleted='N' order by CTYNAME ASC");
										foreach($sql_city as $cityrow) { ?>
											<option value="<?=$cityrow['CTYCODE']?>" <? if($_REQUEST['city_name'] == $cityrow['CTYCODE']) { ?>selected<? } ?>><?=$cityrow['CTYNAME']?></option>
									<? } ?>
									</select>

	                			<!--	<input type="textbox" class="form-control company_input" name="cmpy_city" id="cmpy_city" maxlength="5" onchange="summary_data()" style="width: 50%;text-transform:uppercase;"> -->

	                			</td>
	                			<td style="text-align:right;padding-top: 15px;">Country <span class="colr_red">*</span> </td>
	                			<td>
	                				<select tabindex="9" name="country_name" id="country_name" onchange="summary_data()" class="form-control" required style="margin-left:0px;width: 50%;">
									<option value=""> Select Country </option>
									<? $sql_country = select_query("Select CONCODE,CONNAME from Country where deleted='N' order by CONNAME ASC");
										foreach($sql_country as $countryrow) { ?>
											<option value="<?=$countryrow['CONCODE']?>" <? if($_REQUEST['country_name'] == $countryrow['CONCODE']) { ?>selected<? } ?>><?=$countryrow['CONNAME']?></option>
									<? } ?>
									</select>
									<input type="hidden" class="form-control" name="city_code_hidden" id="city_code_hidden" readonly="readonly" style="width: 70%;">
	                				<input type="hidden" class="form-control" name="country_name_hidden" id="country_name_hidden" readonly="readonly" style="width: 70%;">
	                				<input type="hidden" class="form-control" name="city_name_hidden" id="city_name_hidden" value="<?=$cityrow['CTYNAME']?>" readonly="readonly" style="width: 70%;">
				                	<!--<input type="textbox" class="form-control company_input" name="cmpy_country" id="cmpy_country" maxlength="5" style="width: 50%;text-transform:uppercase;">-->
	                			</td>
	                		</tr>

	                		<tr>
	                			<td style="text-align:right;padding-top: 15px;">Address 1 <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="cmpy_add1" id="cmpy_add1" maxlength="50" required style="width: 50%;text-transform:uppercase;" tabindex="2"></td>
	                			<td style="text-align:right;padding-top: 15px;">Phone</td>
	                			<td><input type="textbox" class="form-control" name="cmpy_phone" id="cmpy_phone" maxlength="25" style="width: 50%;" tabindex="6"></td>
	                			<td style="text-align:right;padding-top: 15px;">Position</td>
	                			<td><input type="textbox" class="form-control" name="cmpy_position" id="cmpy_position" maxlength="25" style="width: 50%;text-transform: uppercase;" tabindex="10"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Address 2 <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="cmpy_add2" id="cmpy_add2" maxlength="50" required style="width: 50%;text-transform:uppercase;" tabindex="3"></td>
	                			<td style="text-align:right;padding-top: 15px;">Mobile <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="cmpy_mob" id="cmpy_mob" maxlength="50" required style="width: 50%;" tabindex="7"></td>
	                			<td style="text-align:right;padding-top: 15px;">Fax</td>
	                			<td><input type="textbox" class="form-control" name="cmpy_fax" id="cmpy_fax" maxlength="50" style="width: 50%;text-transform: uppercase;" tabindex="11"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Address 3</td>
	                			<td><input type="textbox" class="form-control" name="cmpy_add3" id="cmpy_add3" maxlength="50" style="width: 50%;text-transform:uppercase;" tabindex="3"></td>
	                			<td style="text-align:right;padding-top: 15px;">Email Id <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="cmpy_email" id="cmpy_email" maxlength="50" required style="width: 50%;text-transform:uppercase;" tabindex="8"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
	                	<thead>
	                	<tr><th colspan="5" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> Summary</th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align: center;"> Section Max Score </th>
						<th> Factory Score </th>
						<th> Factory % </th>
						</tr>
						</thead>
	                	<tbody>
	                		<tr>
	                			<td>1. QUALITY MANAGEMENT QUESTIONS</td>
	                			<td style="text-align: center;">30</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="qmq_score" id="qmq_score" maxlength="10" readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="qmq_per" id="qmq_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>2. LAB TESTING</td>
	                			<td style="text-align: center;">15</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="lab_test_score" id="lab_test_score" maxlength="10"
	                				  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="lab_test_per" id="lab_test_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>3. SAMPLING</td>
	                			<td style="text-align: center;">21</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="sampling_score" id="sampling_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="sampling_per" id="sampling_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>4. PRODUCTION PLANNING & CONTROL</td>
	                			<td style="text-align: center;">9</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="ppc_score" id="ppc_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="ppc_per" id="ppc_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>5. WAREHOUSE</td>
	                			<td style="text-align: center;">9</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="warehouse_score" id="warehouse_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="warehouse_per" id="warehouse_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>6. FABRIC & ACCESSORIES</td>
	                			<td style="text-align: center;">12</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="fab_acc_score" id="fab_acc_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="fab_acc_per" id="fab_acc_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>7. SPREADING & CUTTING</td>
	                			<td style="text-align: center;">33</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="spread_cut_score" id="spread_cut_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="spread_cut_per" id="spread_cut_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>8. WORK IN PROCESS</td>
	                			<td style="text-align: center;">45</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="wrk_pro_score" id="wrk_pro_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="wrk_pro_per" id="wrk_pro_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>9. FINISHING / PACKING</td>
	                			<td style="text-align: center;">48</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="fin_pac_score" id="fin_pac_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="fin_pac_per" id="fin_pac_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td>10. INSPECTION</td>
	                			<td style="text-align: center;">15</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_total" name="inspection_score" id="inspection_score" maxlength="10"
	                			  readonly></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;" class="form-control grand_per" name="inspection_per" id="inspection_per" maxlength="10" readonly></td>
	                		</tr>

							<tr>
	                			<td style="text-align:center;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="font-weight: bold;font-size:15px;text-align: center;">237</td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;font-weight:bold;font-size:15px;" class="form-control" name="final_grand_total" id="final_grand_total" maxlength="10" readonly></td>
	                			<td></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;padding-top: 15px;">GRADE</td>
	                			<td></td>
	                			<td><input type="textbox" style="width: 50%;text-align: center;font-weight:bold;font-size:15px;" class="form-control" name="final_grade" id="final_grade"  maxlength="1" readonly></td>
	                			<td></td>
	                		</tr>
							<tr style="font-size: large;font-weight: bold;color: red; "> 
							<td style="text-align: center;"> GRADE - A : 100-90 </td>
							<td> GRADE - B : 89-75 </td>
							<td> GRADE - C : 74-50 </td>
							<td> GRADE - D : 49-31 </td>
							<td> GRADE - E : 30-0 </td>
							</tr>

						<!--	<tr style="font-size: large;font-weight: bold;"> 
								<td></td>
							<td><center><input type="button" name="save" id="save" value="SAVE" class="form-control btn-info" style="width: 85px;border-radius: 5px;" onclick="save_entry();"></center> 
							</td>
							</tr>  -->
							</form>	
	                	</tbody>
	                </table>
	                </div>
       			</div> 
      		</div>

              <!-- /.tab-pane 1 -->

            <!-- /.tab-pane 2 start  -->
              <div class="tab-pane" id="tab_2">

				<ul class="nav nav-tabs">
	              <li class="active" id="tab2_1"><a href="#tab_2_1" data-toggle="tab">ORAGANIZATION</a></li>
	              <li id="tab2_2"><a href="#tab_2_2" data-toggle="tab">MACHINE</a></li>
	              <li id="tab2_3"><a href="#tab_2_3" data-toggle="tab">FACTORY</a></li>
	            </ul>

	            <div class="tab-content col-md-12">
	            	<div class="tab-pane active" id="tab_2_1" style="padding-top: 10px;">
            		<table class="table">
					<thead>
					<tr> <th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> ORGANIZATION </th>
					</tr>
					</thead>
					<tr>
					<td style="text-align:right;"> Management Met During The Audit <span class="colr_red">*</span> </td>
					<td> &nbsp;&nbsp;&nbsp; <input type="radio" class="" name="meet_during_audit" id="meet_during_audit1" value="T" checked> Technical Manger</td>
					<td> &nbsp;&nbsp;&nbsp; <input type="radio" class="" name="meet_during_audit" id="meet_during_audit2" value="Q"> QC/QA Supervisor</td>
					<td> &nbsp;&nbsp;&nbsp; <input type="radio" class="" name="meet_during_audit" id="meet_during_audit3" value="P"> Production Manager / Factory Manager</td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Ownership <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control" name="ownership" id="ownership" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					<td style="text-align:right;padding-top: 15px;"> In Operation Since <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control organization_input" name="operation_since" id="operation_since" maxlength="10" required style="width: 50%;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Major Customers / Markets <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control" name="major_customers" id="major_customers" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					<td style="text-align:right;padding-top: 15px;"> Area Assigned For The Production <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control" name="assign_product" id="assign_product" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Main Products <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control" name="main_product" id="main_product" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					<td style="text-align:right;"> Production Capacity <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control organization_input" name="product_capacity" id="product_capacity" maxlength="10" required style="width: 50%;">
					&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="produnits" required value="D" checked> Units Per Days &nbsp; &nbsp; / 
					&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="produnits" value="M"> Units Per Month</td>
					</tr>
					<tr>
					<td style="text-align:right;"> Is Part Of The Manufacturing Subcontracted? <span class="colr_red">*</span>  </td>
					<td>&nbsp;&nbsp;&nbsp; <input type="radio" class="subcontract" name="subcontract" value="Y" id="subcontract1"> Yes 
					&nbsp;&nbsp;&nbsp; <input type="radio" class="subcontract" name="subcontract" value="N" id="subcontract2" checked> No</td>
					<td style="text-align:right;padding-top: 15px;"> If Yes, Specify : </td>
					<td><input type="textbox" class="form-control" id="subcontract_comment" name="subcontract_comment" maxlength="100" disabled style="width: 50%;text-transform: uppercase;">
					</td>
					</tr>
				</table>

				<table class="table">
					<thead>
					<tr> <th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> ACCREDITATION </th>
					</tr>
					</thead>
					<tr>
					<td> </td>
					<td style="text-align:right;"> Currently Available <span class="colr_red">*</span>  </td>
					<td style="width:575px;">
					<input type="radio" class="accred" name="accred" value="ISO 9001" id="accr1" checked> ISO 9001 &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="accred" name="accred" value="ISO 14001" id="accr2"> ISO 14001 &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="accred" name="accred" value="BRC" id="accr3"> BRC &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="accred" name="accred" value="ICTI" id="accr4"> ICTI &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="accred" name="accred" value="BSCI" id="accr5"> BSCI &nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="accred" name="accred" value="OTHERS" id="accr6"> OTHERS
					</td>
					<!--	<td> <select tabindex="3" name="audit_type" id="audit_type" class="form-control custom-select" style="margin;width:275px;">
							<option value=""> Choose Any Type </option>
									<option value='1' <? if($sel_audit_type == '1') { ?> selected <? } ?>>ISO 9001</option>
									<option value='2' <? if($sel_audit_type == '2') { ?> selected <? } ?>>ISO 14001</option>
									<option value='3' <? if($sel_audit_type == '3') { ?> selected <? } ?>>BRC</option>
									<option value='4' <? if($sel_audit_type == '4') { ?> selected <? } ?>>ICTI</option>
									<option value='5' <? if($sel_audit_type == '4') { ?> selected <? } ?>>BSCI</option>
									<option value='6' <? if($sel_audit_type == '4') { ?> selected <? } ?>>OTHERS</option>
							   </select>
					</td> -->
					<td> <input type="textbox" class="form-control" id="oth_accred" name="oth_accred" disabled maxlength="50" style="width: 50%;text-transform: uppercase;"> </td>
					</tr>
			  </table>

			  <table class="table">
	                	<thead>
	                	<tr><th colspan="5" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> FABRIC & ACCESSORIES </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align: right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
	                		<tr>
	                			<td style="width: 650px;">1. Does Factory Have A Fabric Inspection Machine <span class="colr_red">*</span> </td>
	                			<td style="text-align: right;">3</td>
	                			<td style="width: 300px;"><input type="textbox" class="form-control fabric_input" name="fab_ins_score" id="fab_ins_score" maxlength="5" onchange="fabric_validate(this.value,this.id);" required style="width: 50%;text-align: center;">
	                			</td>
	                		</tr>

							<tr>
	                			<td>2. Fabric Are Inspected Randomly Using The 4 or 10 Point System or Any Other System <span class="colr_red">*</span>  <br/>
										(Must Be Proven By Current Records Reflecting The Number Of Defects, Type Of Defects & Frequency)
								</td>
	                			<td style="text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control fabric_input" name="random_score" id="random_score" maxlength="5" onchange="fabric_validate(this.value,this.id);" required style="width: 50%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Inspection Records Properly Show The Defect Identified & Result Of Inspection <span class="colr_red">*</span>  </td>
	                			<td style="text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control fabric_input" name="result_score" id="result_score" maxlength="5" onchange="fabric_validate(this.value,this.id);" required style="width: 50%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>4. Does Factory Establish A Wish Blanket Development Procedures For Washed / Dyed Garment? <span class="colr_red">*</span> </td>
	                			<td style="text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control fabric_input" name="wash_score" id="wash_score"  maxlength="5" onchange="fabric_validate(this.value,this.id);" required style="width: 50%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align: right;">12</td>
	                			<td style="font-weight:bold;font-size:15px;text-align: center;">
	                				<input type="textbox" class="form-control" name="fabric_tot_score" id="fabric_tot_score" readonly style="width: 50%;text-align: center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="fabric_comment" id="fabric_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea></td>
					</tr>
					</table>
					</div>


        			<div class="tab-pane" id="tab_2_2" style="padding-top: 10px;">
        				<table class="table">
                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> MACHINERY DETAILS </th> </tr>
	                	</thead>
	                	<tbody>
	                		<tr>
	                			<td style="text-align:right;">Does The Factory Have A Generator <span class="colr_red">*</span> </td>
	                			<td><input type="radio" class="generator" name="generator" value="Y" id="generator1" checked> Yes 
								&nbsp;&nbsp;&nbsp; <input type="radio" class="generator" name="generator" value="N" id="generator2"> No</td>
	                		</tr>

							<tr>
							<th style="text-align:right;"> Machinery Details </th>
							<th> Qty </th>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Single Needle <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="single_score" id="single_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Double Needle <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="double_score" id="double_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Feed Of Arm <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="arm_score" id="arm_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Over Lock <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="lock_score" id="lock_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Button Hole <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="hole_score" id="hole_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Button Sewing <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="sewing_score" id="sewing_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Bartack <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="bartack_score" id="bartack_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Cutting Machine Straight Knife <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="straight_score" id="straight_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Cutting Machine Band Knife <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="band_score" id="band_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Cutting Table <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="cutting_score" id="cutting_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Checking Table <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="checking_score" id="checking_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Fusing Machine <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="fuse_score" id="fuse_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Other Special Machines <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="spl_mac_score" id="spl_mac_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Power Source <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="power_score" id="power_score" maxlength="10" required></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;padding-top: 15px;">Others <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control machinery_input" name="oth_factory_score" id="oth_factory_score" maxlength="10" required></td>
	                		</tr>
						<!--	<tr style="font-size: large;font-weight: bold;"> 
							<td> </td>
							<td><input type="button" name="save" id="save" value="SAVE" class="form-control btn-info" style="width: 85px;border-radius:6px;" onclick="save_entry();">
							</td>
							</tr> -->
							</form>
	                	</tbody>
	                </table>
	                </div>

	                <div class="tab-pane" id="tab_2_3" style="padding-top: 10px;">
        				 <table class="table">
					<thead>
					<tr> <th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> FACTORY DETAILS </th>
					</tr>
					</thead>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Total Workforce <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="total_employees" id="total_employees" required maxlength="10" style="width: 50%;"></td>
					<td style="text-align:right;padding-top: 15px;"> Number Of Sewing Lines  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="no_sew_lines" id="no_sew_lines" maxlength="10" required style="width: 50%;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Male <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="male_employees" id="male_employees" maxlength="10" onchange="check_male()" required style="width: 50%;"></td>
					<td style="text-align:right;padding-top: 15px;"> Female <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="female_employees" id="female_employees" maxlength="10" onchange="check_female()" required style="width: 50%;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Factory Manager <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control textonly" name="fac_mngr" id="fac_mngr" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					<td style="text-align:right;padding-top: 15px;"> QC's (In-line / End-Line/ Final)  <span class="colr_red">*</span> </td>
					<td><input type="textbox" class="form-control" name="qc_in_ens_fin" id="qc_in_ens_fin" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Production Manager <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control textonly" name="prod_mngr" id="prod_mngr" maxlength="100" required style="width: 50%;text-transform: uppercase;"> </td>
					<td style="text-align:right;padding-top: 15px;"> Production Workers :</td>
					<td><input type="textbox" class="form-control factory_det_input" name="prod_workers" id="prod_workers" maxlength="10" required style="width: 50%;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> QA Manager <span class="colr_red">*</span> </td>
					<td><input type="textbox" class="form-control textonly" name="qa_mngr" id="qa_mngr" maxlength="100" required style="width: 50%;text-transform: uppercase;"> </td>
					<td style="text-align:right;padding-top: 15px;"> Production Supervisors <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control factory_det_input" name="prod_supr" id="prod_supr" maxlength="10" required style="width: 50%;"> </td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Total QA <span class="colr_red">*</span> </td>
					<td><input type="textbox" class="form-control factory_det_input" name="tot_qa" id="tot_qa" maxlength="10" required style="width: 50%;"> </td>
					<td style="text-align:right;padding-top: 15px;"> Average Number Of Sewers Per Line <span class="colr_red">*</span>  </td>
					<td><input type="textbox" class="form-control factory_det_input" name="avg_sew_line" id="avg_sew_line" maxlength="5" required style="width: 50%;"> </td>
					</tr>
					</table>
					<table class="table">
					<tr>
					<td style="text-align:right"> House Keeping Comments  <span class="colr_red">*</span> </td>
					<td> <select class="form-control" name="house_kep_cmnt" id="house_kep_cmnt" required style="width:222px">
	                			<option value="G">GOOD</option>
	                			<option value="A">AVERAGE</option>
	                			<option value="P">POOR</option>
	                </select> </td>
					</tr>
					<tr>
					<td style="text-align:right"> Working In  <span class="colr_red">*</span>  </td>
					<td><input type="radio" class="shift" name="shift" value="1" id="shift1" checked> 1st Shift 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="shift" name="shift" value="2" id="shift2" required> 2nd Shift
							&nbsp;&nbsp;&nbsp; <input type="radio" class="shift" name="shift" value="3" id="shift3"> 3rd Shift
					</td>
					</tr>
				</table>
				<table class="table">
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Production Flow Chart  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control" name="prod_flow_chart" id="prod_flow_chart" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					<td style="text-align:right;padding-top: 15px;"> New Facilities / Building For Extension  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control" name="faci_bild_ext" id="faci_bild_ext" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
					</tr>
					<tr>
					<td style="text-align:right;padding-top: 15px;"> Forecasted Investments  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="forcast_inv" id="forcast_inv" maxlength="10" required style="width: 50%;"></td>
					<td style="text-align:right;padding-top: 15px;"> Retour  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control factory_det_input" name="retour" id="retour" maxlength="11" required style="width: 50%;"></td>
					</tr>
					<tr>
					<td style="text-align:right;"> Seasonality  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="season" name="season" value="Y" id="season1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="season" name="season" value="N" id="season2" checked> No</td>
					<td style="text-align:right;padding-top: 15px;"> Peak Period  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control" name="peak_period" id="peak_period" maxlength="50" required style="width: 50%;text-transform: uppercase;"></td>
					</tr>
				</table>
				<table class="table">
					<tr>
					<td style="text-align:right;"> Organigramm : Independent QA Organization <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="gramm" name="gramm" value="Y" id="gramm1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="gramm" name="gramm" value="N" id="gramm2" checked> No </td>
					<td style="text-align:right;"> Temporary Personal  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="personal" name="personal" value="Y" id="personal1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="personal" name="personal" value="N" id="personal2" checked> No </td>
					<td style="text-align:right;"> Permanent <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="permanent" name="permanent" value="Y" id="permanent1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="permanent" name="permanent" value="N" id="permanent2" checked> No </td>
					<td style="text-align:right;"> Welcome Book  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="welcome" name="welcome" value="Y" id="welcome1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="welcome" name="welcome" value="N" id="welcome2" checked> No </td>
					</tr>
					<tr>
					<td></td>
					<td style="text-align:right;"> Temporary  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="temp" name="temp" value="Y" id="temp1"> Yes 
							&nbsp;&nbsp;&nbsp; <input type="radio" class="temp" name="temp" value="N" id="temp2" checked> No </td>
					<td style="text-align:right;padding-top: 15px;"> Training Schedule  <span class="colr_red">*</span>  </td>
					<td> <input type="textbox" class="form-control" name="training_schedule" id="training_schedule" maxlength="100" required style="text-transform: uppercase;"> </td>
					</tr>
				</table>
	                </div>

   			</div> 
		</div>
       		<!-- /.tab-pane 2 end  -->

           	<!-- /.tab-pane 3 start  -->
              <div class="tab-pane" id="tab_3">
				<ul class="nav nav-tabs">
	              <li class="active" id="tab3_1"><a href="#tab_3_1" data-toggle="tab">SAMPLING</a></li>
	              <li id="tab3_2"><a href="#tab_3_2" data-toggle="tab">SPREADING & CUTTING</a></li>
	              <li id="tab3_3"><a href="#tab_3_3" data-toggle="tab">WAREHOUSE</a></li>
	            </ul>

	            <div class="tab-content col-md-12">
	            	<div class="tab-pane active" id="tab_3_1" style="padding-top: 10px;">
            		<table class="table">
					<thead>
					<tr> <th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> SAMPLING </th>
					</tr>
					</thead>
					<tr>
					<td style="text-align:right"> Pattern Generation  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="" name="generation" value="M" checked> Manual
					&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="generation" value="C"> Computerized </td>
					</tr>
					<tr>
					<td style="text-align:right"> Pattern Grading  <span class="colr_red">*</span> </td>
					<td> <input type="radio" class="" name="grading" value="M" checked> Manual
					&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="grading" value="C"> Computerized </td>
					</tr>
					<tr>
					<td style="text-align:right"> Marker Making  <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="" name="making" value="M" checked> Manual
					&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="making" value="C"> Computerized </td>
					</tr>
					<tr>
					<td style="text-align:right"> Marker Duplication <span class="colr_red">*</span>  </td>
					<td> <input type="radio" class="" name="duplicate" value="M" checked> Manual
					&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" class="" name="duplicate" value="C"> Computerized </td>
					</tr>
					</table>
					<table class="table">
					<tr>
					<th><b>#</b></th>
					<th style="text-align:right"><b> Max Score</b> </th>
					<th><b> Supplier Score</b> </th>
					</tr>
				</table>

				<table class="table">
							<tr>
								<td style="width: 650px;">1. Sampling Score For Pattern & Marker? <span class="colr_red">*</span> </td>
								<td style="text-align:right"> 3 </td>
								<td> <input type="textbox" class="form-control sampling_input" name="samp_score" id="samp_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width:183px;text-align: center;"> </td>
							</tr>
	                		<tr>
	                			<td style="width: 650px;">2. Does The Factory Have As Sample Making or Engineering Department? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="engr_dept_score" id="engr_dept_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

	                		<tr>
	                			<td style="text-align:center">If No, Please Indicate The Vendor They Use: </td>
								<td></td>
								<td><input type="textbox" class="form-control" name="vendor_use" id="vendor_use" maxlength="100" style="width: 183px;"></td>
	                		</tr>

							<tr>
	                			<td>3. Sample Made In Production Line  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="prd_lin_score" id="prd_lin_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>4. Sample Made By Vendor  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="vendor_score" id="vendor_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;" ></td>
	                		</tr>

							<tr>
	                			<td>5. Sample Subcontracted Outside <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="outside_score" id="outside_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>6. Consultant Used <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="consult_score" id="consult_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>7. Does The Factory Have Dummy In Sample Room?</td>
	                			<td></td>
	                			<td></td>
	                		</tr>

							<tr>
	                			<td align="center">If Yes, How Many, What Size? If No, How Does Factory Fit Their Sample?  <span class="colr_red">*</span> 
								<br/> Dummy Not Available As Facility Only Made Scarves.</td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control sampling_input" name="scarve_score" id="scarve_score" maxlength="5" onchange="sampling_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">21</td>
	                			<td><input type="textbox" class="form-control" name="sampling_tot_score" id="sampling_tot_score" readonly style="width: 183px;text-align: center;font-weight:bold;font-size:15px;"></td>
	                		</tr>
	                </table>

				<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="sampling_comment" id="sampling_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea>
					</td>
					</tr>
			    </table>

				<table class="table">
					<thead>
					<th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> PRODUCTION PLANNING AND CONTROLS </th>
					</thead>
					<tbody>
							<tr>
	                			<td style="width: 650px;">1. Does Factory Have A Daily Production Report? <span class="colr_red">*</span> </td>
	                			<td style="width: 135px;text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control ppc_input" name="prod_rpt_score" id="prod_rpt_score" maxlength="5" onchange="ppc_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
								<td></td>
	                		</tr>

							<tr>
	                			<td>2. Does Factory Have A Weekly Production Planning Status Report To Identify Which Orders Are In Process? <span class="colr_red">*</span> 
								</td>
	                			<td style="width: 135px;text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control ppc_input" name="ord_prc_score" id="ord_prc_score" maxlength="5" onchange="ppc_validate(this.value,this.id);" required style="width: 183px;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Does Factory Conduct A Formal Pre-Production Meeting For Every New Style Before Production Start?  <span class="colr_red">*</span> </td>
	                			<td style="width: 135px;text-align: right;">3</td>
	                			<td><input type="textbox" class="form-control ppc_input" name="meet_score" id="meet_score" maxlength="5" onchange="ppc_validate(this.value,this.id);" required style="width: 183px; text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">9</td>
	                			<td><input type="textbox" class="form-control" name="ppc_tot_score" id="ppc_tot_score" readonly style="width: 183px;text-align: center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

				<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="ppc_comment" id="ppc_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea>
					</td>
					</tr>
			    </table>
			</div>


        			<div class="tab-pane" id="tab_3_2" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                	<tr><th colspan="5" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> SPREADING AND CUTTING </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
	                		<tr>
	                			<td style="width: 650px;">1. Where Is The Cutting? <span class="colr_red">*</span> </td>
	                			<td> <input type="radio" class="cutting" name="cutting" value="Y" id="cutting1" checked> Same Site As Production </td>
								<td><input type="radio" class="cutting" name="cutting" value="N" id="cutting2"> Other, Please State </td>
	                		</tr>

							<tr>
	                			<td>2. How Many Cutting Tables? <span class="colr_red">*</span>  </td>
	                			<td><input type="textbox" class="form-control cutting_table_input" name="cuttingtab" id="cuttingtab" maxlength="10" required></td>
	                			<td><input type="textbox" class="form-control" name="othstate" id="othstate" maxlength="25" disabled style="text-transform: uppercase;"></td>
	                		</tr>

							<tr>
	                			<td>3. Automatic Spreading Equipment : If Yes, Please Describe  <span class="colr_red">*</span> &nbsp;&nbsp;&nbsp;
	                			<input type="textbox" class="form-control" name="equipment" id="equipment" style="text-transform: uppercase;"></td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control spread_cut_input" name="equip_score" id="equip_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;">
								</td>
	                		</tr>

							<tr>
	                			<td>4. Fabric Is Stored By Shade Lots To Spreading Operation <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="operation_score" id="operation_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;">
	                			</td>
	                		</tr>

							<tr>
	                			<td>5. Proper Cutting Methods & Equipment Are Used According To Type Of Fabric & Design <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="proper_score" id="proper_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"> </td>
	                		</tr>

							<tr>
	                			<td>6. Spreading & Cutting Machines Are Properly Maintained (Including Oiling/Sharpening/Cleaning) <span class="colr_red">*</span> </td>

	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="maintain_score" id="maintain_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>7. Fabric Cut Piles Are Marked, Numbered Properly <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="mark_score" id="mark_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"> </td>
	                		</tr>

							<tr>
	                			<td>8. Cut Parts Are Inspected So That Defective or Shaded Parts Are Subject To Replacement  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="extbox" class="form-control spread_cut_input" name="replacement_score" id="replacement_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>9. Procedures Are In Place To Allow Sufficient Time For Knitted Fabric To Relax Before And After Spreading  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="procedure_score" id="procedure_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>10. Spreading Tables And Bundle Tables Are Generally Clean And Free Of Sharp Edges <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="edge_score" id="edge_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>11. A Standard For Maximum Cutting Height or Number Of Piles Applied Correctly <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="standard_score" id="standard_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>12. Relax Light Weight Fabric Before Cutting. Eg:Chiffon, Rayon, Georgette, Stretched Fabric etc.., <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="relax_score" id="relax_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>13. Speacial Method or Facilities To Match Plaid, Stripe, Check, One_way & Repeat Design <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control spread_cut_input" name="stripe_score" id="stripe_score" maxlength="5" onchange="spr_cut_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>
							
							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;">33</td>
	                			<td><input type="textbox" class="form-control" name="spread_cut_tot_score" id="spread_cut_tot_score" readonly style="width: 40%;text-align: center;font-weight:bold;font-size:15px;"></td>
	                		</tr>
	                	</tbody>
	                </table>
					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="cutting_spreading_comment" id="cutting_spreading_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea></td>
					</tr>
				</table>
              </div>


	          <div class="tab-pane" id="tab_3_3" style="padding-top: 10px;">
        		<table class="table">
				<thead>
					<th colspan="7" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> FUSING </th>
				</thead>
				<tbody>	
				<tr>
					<td style="text-align:right;"> How Does The Factory Fuse Interlining  <span class="colr_red">*</span>  </td>
					<td><input type="radio" class="fuse" name="fuse" value="H" id="fuse1" checked> Head Iron </td>
					<td><input type="radio" class="fuse" name="fuse" value="S" id="fuse2"> Steam Press </td>
					<td><input type="radio" class="fuse" name="fuse" value="F" id="fuse3"> Fusing Press </td>
					<td><input type="radio" class="fuse" name="fuse" value="O" id="fuse4"> Other </td>
					<td><input type="radio" class="fuse" name="fuse" value="R" id="fuse5"> Records Kept </td>
					<td><input type="radio" class="fuse" name="fuse" value="C" id="fuse6"> Cont. Roller Press </td>
				</tr>
				<tr>
					<td style="text-align:right;"> Do They Check For Correct Conditions  <span class="colr_red">*</span>  </td>
					<td><input type="radio" class="condition" name="condition" value="T" id="condition1" checked> Time </td>
					<td><input type="radio" class="condition" name="condition" value="H" id="condition2"> Heat </td>
					<td><input type="radio" class="condition" name="condition" value="S" id="condition3"> Steam </td>
					<td><input type="radio" class="condition" name="condition" value="P" id="condition4"> Pressure </td>
				</tr>
				</tbody>
				</table>
				<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="fusing_comment" id="fusing_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea></td>
					</tr>
			    </table>

				<table class="table">
	                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> WAREHOUSE </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>1. Fabric Storage Is Organized And Fabrics Are Stored Off The Floors And Remain In Protective Wrap or Box Until Opened For Use  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control warehouse_input" name="fabric_score" id="fabric_score" maxlength="5" onchange="warehouse_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>2. Trims And Accessories Are Stored Properly And Inventory Record Maintained <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control warehouse_input" name="trims_score" id="trims_score" maxlength="5" onchange="warehouse_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Factory Has A Logical System For Incoming Materials / Accessories / WIP <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control warehouse_input" name="incoming_score" id="incoming_score" maxlength="5" onchange="warehouse_validate(this.value,this.id);" required style="width: 40%;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;">9</td>
	                			<td><input type="textbox" class="form-control" name="warehouse_tot_score" id="warehouse_tot_score" readonly style="width: 40%;text-align: center;font-weight:bold;font-size:15px;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="warehouse_comment" id="warehouse_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea></td>
					</tr>
			    </table>

	          </div>
   			</div> 
		</div>
       			<!-- /.tab-pane 4 end  -->

              	<!-- /.tab-pane 4 start  -->
              <div class="tab-pane" id="tab_4">

				<ul class="nav nav-tabs">
	              <li class="active" id="tab4_1"><a href="#tab_4_1" data-toggle="tab">QMQ</a></li>
	              <li id="tab4_2"><a href="#tab_4_2" data-toggle="tab">LAB TESTING</a></li>
	              <li id="tab4_3"><a href="#tab_4_3" data-toggle="tab">FINISH</a></li>
	              <li id="tab4_4"><a href="#tab_4_4" data-toggle="tab">WORK IN PROCESS</a></li>
	              <li id="tab4_5"><a href="#tab_4_5" data-toggle="tab">INSPECTION</a></li>
	              <li id="tab4_6"><a href="#tab_4_6" data-toggle="tab">CAPA</a></li>
	            </ul>

	            <div class="tab-content col-md-12">

	            	<div class="tab-pane active" id="tab_4_1" style="padding-top: 10px;">
            		<table class="table">
	                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> QUALITY MANAGEMENT QUESTIONS </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>1. Is The Quality Program Primarily Passive (Inspection) or Active (Proactive Prevention)?  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control qmq_input" name="passive_score" id="passive_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>2. Is There An Adequate Training Scheme For All Quality Staff? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="scheme_score" id="scheme_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Are There Documented Training Records? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="training_records_score" id="training_records_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>4. Is The QC/QA team Demonstrating A Strong Quality Mind Concept? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="concept_score" id="concept_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>5. Is There Proper QC/QA Supervision In All Shifts? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="shift_score" id="shift_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>6. Does The QC Team Conduct Root-Cause-Analysis To Determine Cause Of Defects? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="root_score" id="root_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>7. Do Internal Inspection Reports Reflect That The QC Procedures Are Followed Products Are Properly Checked? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="check_score" id="check_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>8. Is There A Continuous Improvement Program To Enhance Production Quality? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="improve_score" id="improve_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>9. Is There An Effective Corrective Action Plan Driven By Customer Complaints or Internal Deficiencies? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="effective_score" id="effective_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>10. Are There Records Of Internal or Third Party Audits Of The Quality System In The Past Year? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control qmq_input" name="third_score" id="third_score" maxlength="5" onchange="qmq_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;">30</td>
	                			<td><input type="textbox" class="form-control" name="qmq_tot_score" id="qmq_tot_score" style="width:32%;font-weight:bold;font-size:15px;text-align:center;" readonly></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="qmq_comment" id="qmq_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea></td>
					</tr>
			    </table>
				</div>


       			<div class="tab-pane" id="tab_4_2" style="padding-top: 10px;">
       			<table class="table">
	                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> LAB TESTING </th> </tr>
	                	<tr>
						<th> # </th>
						<th colspan="1" style="text-align:right"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>a) Does The Factory Test Any Material, Accessories or Finished Product?  <span class="colr_red">*</span> </td>
								<td style="text-align:right;">3</td>
								<td><input type="textbox" class="form-control labtest_input" name="accessories_score" id="accessories_score" maxlength="5" onchange="labtest_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:center;">If Yes, Please Describe What Is Being Tested: </td>
								<td></td>
								<td><input type="textbox" class="form-control" name="tested" id="tested" maxlength="100" style="text-transform: uppercase;"></td>
	                		</tr>

							<tr>
	                			<td>b) Is There An In-House Lab? If No, Please List Labs Used For Testing - If Applicable <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;">3</td>
	                			<td><input type="textbox" class="form-control labtest_input" name="inhouse_score" id="inhouse_score" maxlength="5" onchange="labtest_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:center">If Yes, Please Comment On Procedure & Equipment: </td>
	                			<td></td>
								<td><input type="textbox" class="form-control" name="quality_score" id="quality_score" maxlength="100" style="text-transform: uppercase;"></td>
	                		</tr>

							<tr>
	                			<td>c) Does The Factory Have Record Of Lab Test Results On All Fabrics? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;">3</td>
	                			<td><input type="textbox" class="form-control labtest_input" name="allfab_score" id="allfab_score" maxlength="5" onchange="labtest_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>d) Other Components: <span class="colr_red">*</span> </td>
								<td style="text-align:right;">3</td>
	                			<td><input type="textbox" class="form-control labtest_input" name="othcomp_score" id="othcomp_score" maxlength="5" onchange="labtest_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>e) Garments: <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;">3</td>
	                			<td><input type="textbox" class="form-control labtest_input" name="garment_score" id="garment_score" maxlength="5" onchange="labtest_validate(this.value,this.id);" required style="width:32%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td style="text-align:right;font-weight:bold;font-size:15px;">TOTAL</td>
								<td style="font-weight:bold;font-size:15px;text-align:right;">15</td>
	                			<td><input type="textbox" class="form-control" name="labtest_tot_score" id="labtest_tot_score" readonly style="width:32%;font-weight:bold;font-size:15px;text-align:center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="lab_test_comment" id="lab_test_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea>
					</td>
					</tr>
			    </table>
	                </div>

	                <div class="tab-pane" id="tab_4_3" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> FINISHING PACKING</th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>1. Trimming / Packing Is Done In A Well Lighted Area With Adequate Work Space For Assorting Size/Color, A Clean Table Utilize Proper, Equipment Is Also Required  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control finish_input" name="trimming_score" id="trimming_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>2. Goods Are Consistenly Classified According To Style, Color & Size <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="goods_score" id="goods_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Steam Pressing Is Adequate <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="steam_score" id="steam_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>4. Pressing Tabletops Clean</td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="clean_score" id="clean_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>5. Size Marking On Tabletops <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="marking_score" id="marking_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>6. Equipment Suitable For The Product <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="suitable_score" id="suitable_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>7. Shade Standards & Guides Are Readily Available & Accessible In Packing Area <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="pack_area_score" id="pack_area_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>8. For Sweater, What Types Of Frames Are Used?  <span class="colr_red">*</span> 
								&nbsp; &nbsp; <input type="radio" class="frame" name="frame" value="B" id="frame1" checked>Board
								&nbsp; &nbsp; <input type="radio" class="frame" name="frame" value="W" id="frame2"> Wire
								&nbsp; &nbsp; <input type="radio" class="frame" name="frame" value="F" id="frame3"> Forms 
								&nbsp; &nbsp; <input type="radio" class="frame" name="frame" value="O" id="frame4"> Other:</td>
	                			<td></td>
								<td><input type="textbox" style="width:40%;text-transform: uppercase;" class="form-control" id="other_frames" name="other_frames" maxlength="25" disabled>
								</td>
	                		</tr>

							<tr>
	                			<td>9. Does Factory Keep Garments In A Suitable Method After Pressing To Allow Water To Evaporate? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="evaporate_score" id="evaporate_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>10. Is There A Sample Hanging Up Of Finished Pressed Garment? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="hanging_score" id="hanging_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>11. Where Are Pressed Garments Stored? <span class="colr_red">*</span> 
								<input type="textbox" class="form-control" name="press_stored_score" id="press_stored_score" maxlength="100" style="width:60%;text-transform: uppercase;"></td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="stored_score" id="stored_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
									<td> Any Exessive Stack &nbsp; &nbsp; &nbsp; &nbsp;  Stack Height  <span class="colr_red">*</span> 
									<input type="textbox" style="width:22%" class="form-control finishing_input" name="stack_height_input" id="stack_height_input" maxlength="5" required style="width:40%">
									</td>
							</tr>

							<tr>
	                			<td>12. Packing Instruction Available? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="instruct_score" id="instruct_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>13. Enough Hanging Space For GOH?  <span class="colr_red">*</span> 
	                			&nbsp; &nbsp; <input type="radio" class="goh" name="goh" value="Y" id="goh1" checked> Yes
								&nbsp; &nbsp; <input type="radio" class="goh" name="goh" value="N" id="goh2"> No
								&nbsp; &nbsp; <input type="radio" class="goh" name="goh" value="A" id="goh3"> N/A
	                		</td>
							</tr>

							<tr>
	                			<td>14. Suitable Storage For Packed Goods <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="packgoods_score" id="packgoods_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>15. Packing Area Organized <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="organize_score" id="organize_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>16. Indoor Storage or Protected From Moisture & Dust <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="indoor_score" id="indoor_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>17. Are Cartons Grouped By P.O? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="cartons_score" id="cartons_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>18. Broken Needle Detection. If Yes, Please List Equipment Used  <span class="colr_red">*</span>
								<input type="textbox" class="form-control" name="broken_input" id="broken_input" maxlength="100" style="width:60%;text-transform: uppercase;">
								</td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control finish_input" name="broken_score" id="broken_score" maxlength="5" onchange="finishing_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>19. Records Kept  <span class="colr_red">*</span> 
								&nbsp; &nbsp; <input type="radio" class="record_kept" name="record_kept" value="Y" id="record_kept1" checked> Yes
								&nbsp; &nbsp; <input type="radio" class="record_kept" name="record_kept" value="N" id="record_kept2"> No
								<font style="font-weight:bold;font-size:15px;padding-left:59%;padding-top: 15px;"> TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;padding-top: 15px;">48</td>
	                			<td><input type="textbox" class="form-control" name="finish_tot_score" id="finish_tot_score" readonly style="width:40%;font-weight:bold;font-size:15px;text-align:center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="finishing_comment" id="finishing_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea>
					</td>
					</tr>
			    </table>
	                </div>

	                <div class="tab-pane" id="tab_4_4" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                	<tr><th colspan="4" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> WORK IN PROCESS </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>1. Sewing / Linking Machineries Area Maintained In Good Working Condition  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control wip_input" name="condition_score" id="condition_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>2. Sewing / Linking Lines Are Efficiency Organized In Accordance With The Process Flow, Well Spaced / Well Lighted <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="well_score" id="well_score" maxlength="5" required onchange="wip_validate(this.value,this.id);" style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>3. Bundles Are Moved From Operator To Operator In A Manner To Maintain Them In A Clean, Undamaged Condition <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="undamage_score" id="undamage_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>4. Work In Process Is Kept In :  <span class="colr_red">*</span> 
								&nbsp; &nbsp; <input type="radio" class="wip" name="wip" value="B" id="wip1" checked> Bags
								&nbsp; &nbsp; <input type="radio" class="wip" name="wip" value="C" id="wip2"> Cartons
								&nbsp; &nbsp; <input type="radio" class="wip" name="wip" value="N" id="wip3"> Bins
								&nbsp; &nbsp; <input type="radio" class="wip" name="wip" value="T" id="wip3"> Trolleys 
								&nbsp; &nbsp; <input type="radio" class="wip" name="wip" value="O" id="wip4"> Other:
								</td>
								<td></td>
								<td><input type="textbox" style="width:40%;text-transform: uppercase;" class="form-control" maxlength="50" id="othwip" name="othwip" disabled>
								</td>
	                		</tr>

							<tr>
	                			<td>5. Sufficient Inline Station Is Properly Positioned & Supplier With Production Specification <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="specific_score" id="specific_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>6. In Process Goods Are Randomly Measured & Discrepancies Acted Upon Appropriately <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="discrepancies_score" id="discrepancies_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>7. QC Has A Quick & Adequate Feedback Channel To Resolve Problems <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="adequate_score" id="adequate_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>8. Factory Has A Well Organized & Supervised Training Program For Sewing Operators <span class="colr_red">*</span> </td>
								<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="supervised_score" id="supervised_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>9. Proper Attachments (Presser Foot & Folders) And Work Aids Facilities Are Installed On The Machines <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="installed_score" id="installed_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>10. Factory Has The System To Monitor Correct Needle Type & Size, Correct Tension In Needle, Thread & Bobbin, Proper Level Of Feed-Dog, Correct Type & Size <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="tension_score" id="tension_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>11. Approved Sample Reference Are Available In Production Area (Properly Marked) <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="properly_score" id="properly_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>12. Sections or Workshops Are Marked As To Type Of Operation <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="workshop_score" id="workshop_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>13. Broken Needle Procedure / Policy In Place? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="needle_score" id="needle_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>14. Records Kept Of Missing / Replaced Needles? <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="missing_score" id="missing_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>15. Records Kept For Routine Needle Replacement Frequency: Daily Day <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="day_score" id="day_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>16. Snap Pulling Testing Procedure In Place Frequency: Times / Day  <span class="colr_red">*</span> 
								&nbsp; &nbsp; <input type="radio" class="snap_place" name="snap_place" value="Y" id="snap1" checked> Yes
								&nbsp; &nbsp; <input type="radio" class="snap_place" name="snap_place" value="N" id="snap2"> No
								&nbsp; &nbsp; <input type="radio" class="snap_place" name="snap_place" value="A" id="snap3"> N/A </td>
	                		</tr>

							<tr>
	                			<td>17. Records Kept  <span class="colr_red">*</span> 
								&nbsp; &nbsp; <input type="radio" class="record_kepts" name="record_kepts" value="Y" id="record1" checked> Yes
								&nbsp; &nbsp; <input type="radio" class="record_kepts" name="record_kepts" value="N" id="record2"> No </td>
	                		</tr>

							<tr>
	                			<td>18. How Many Operators Per Line?  <span class="colr_red">*</span> </td>
								<td></td>
	                			<td><input type="textbox" class="form-control wip_num_input" name="opr_per_line" id="opr_per_line" maxlength="10" required style="width:40%;text-transform: uppercase;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>19. Any Bottleneck Observed In Flow? <span class="colr_red">*</span> </td>
	                			<td></td>
								<td><input type="textbox" class="form-control wip_num_input" name="obs_in_flow" id="obs_in_flow" maxlength="10" required style="width:40%;text-transform: uppercase;text-align: center;"></td>
	                		</tr>

							<tr>
	                			<td>20. Ratio Of Line QC To Production Workers  <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control wip_input" name="line_score" id="line_score" maxlength="5" onchange="wip_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td style="font-weight:bold;font-size:15px;padding-left:60%;"> TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;">45</td>
	                			<td><input type="textbox" class="form-control" name="wip_tot_score" id="wip_tot_score" readonly style="width:40%;font-weight:bold;font-size:15px;text-align:center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="wip_comment" id="wip_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;"></textarea>
					</td>
					</tr>
					</table>
	                </div>

	                <div class="tab-pane" id="tab_4_5" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                	<tr><th colspan="3" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> INSPECTION </th> </tr>
	                	<tr>
						<th> # </th>
						<th style="text-align:right;"> Max Score </th>
						<th> Supplier Score </th>
						</tr>
						</thead>
	                	<tbody>
							<tr>
	                			<td>1. How Many Inspection Tables / Positions? <span class="colr_red">*</span> </td>
	                			<td></td>
								<td><input type="textbox" class="form-control inspect_input" name="table_position_input" id="table_position_input" maxlength="10" style="width: 40%;" required></td>
	                		</tr>

							<tr>
	                			<td>2. How Many Quality Controllers Do They Have? <span class="colr_red">*</span> </td>
	                			<td></td>
	                			<td><input type="textbox" class="form-control inspect_input" name="qty_control_input" id="qty_control_input" maxlength="10" style="width: 40%;" required></td>
	                		</tr>

							<tr>
	                			<td>3. Inspection Area Clean & Spacious <span class="colr_red">*</span> </td>
								<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control inspection_input" name="spacious_score" id="spacious_score" maxlength="5" onchange="inspection_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>4. 100% Of All Products Are Inspected Prior To Packing <span class="colr_red">*</span> </td>
								<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control inspection_input" name="prior_score" id="prior_score" maxlength="5" onchange="inspection_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>
							
							<tr>
	                			<td>5. Inspectors Have Guidelines & Information Readily Available In The Work Area <span class="colr_red">*</span> </td>
								<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control inspection_input" name="readily_score" id="readily_score" maxlength="5" onchange="inspection_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>6. Defective Garments Are Properly Segregated For Further Review <span class="colr_red">*</span> </td>
	                			<td style="text-align:right;padding-top: 15px;">3</td>
	                			<td><input type="textbox" class="form-control inspection_input" name="segragated_score" id="segragated_score" maxlength="5" onchange="inspection_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>
	                		</tr>

							<tr>
	                			<td>7. For Sweaters, Sufficient Number Of Light Forms  <span class="colr_red">*</span> <br/>
								If Yes, Number Of Light Forms: &nbsp;&nbsp;
								<input type="textbox" class="form-control" id="othframe" name="othframe" maxlength="100" style="text-transform: uppercase;">
								</td>
								<td style="text-align:right;padding-top: 15px;">3</td>
								<td><input type="textbox" class="form-control inspection_input" name="sufficient_score" id="sufficient_score" maxlength="5" onchange="inspection_validate(this.value,this.id);" required style="width:40%;text-align:center;"></td>

							<tr>
	                			<td>8. Measurements Are Made On Finished Goods Prior To Packing <span class="colr_red">*</span> </td>
	                			<td></td>
								<td><input type="radio" class="pack" name="pack" value="R" id="pack1" checked> Random
									&nbsp; &nbsp;
								<input type="radio" class="pack" name="pack" value="F" id="pack2"> 100% </td>
	                		</tr>
							<tr>
								<td><font style="font-weight:bold;font-size:15px;padding-left:60%;text-align:right;"> TOTAL</td>
	                			<td style="font-weight:bold;font-size:15px;text-align:right;">15</td>
	                			<td><input type="textbox" class="form-control" name="inspection_tot_score" id="inspection_tot_score" readonly style="width:40%;font-weight:bold;font-size:15px;text-align:center;"></td>
	                		</tr>
	                	</tbody>
	                </table>

					<table class="table">
					<tr>
					<td style="width:100px;font-style:oblique;font-weight:bold;font-size:18px;"> Comments: </td>
					<td> <textarea class="form-control" name="inspection_comment" id="inspection_comment" maxlength="200" style="height:80px;border-radius:10px;text-transform: uppercase;">
					</textarea>
					</td>
					</tr>
					</table>
	                </div>

	                <div class="tab-pane" id="tab_4_6" style="padding-top: 10px;">
        				<table class="table">
	                	<thead>
	                		<th colspan="6" style="background-color:  cadetblue;text-align: -webkit-center;color: white;"> CORRECTIVE ACTION PLAN</th>
	                	</thead>
	                	<tbody>
	                		<tr>
	                			<td></td>
	                			<td style="text-align: right;padding-top: 15px;">Supplier Name  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="sup_name_summ" id="sup_name_summ" readonly="readonly" required style="text-transform:uppercase;"></td>
	                			<td style="text-align: right;padding-top: 15px;">Product Catagory  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="product_category_summ" id="product_category_summ" readonly="readonly" required style="text-transform:uppercase;"></td>
	                		</tr>
	                		<tr>
	                			<td></td>
	                			<td style="text-align: right;padding-top: 15px;">Factory Name  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="fac_name_summ" id="fac_name_summ" readonly="readonly" required style="text-transform:uppercase;"></td>
	                			<td style="text-align: right;padding-top: 15px;">Factory Location  <span class="colr_red">*</span> </td>
	                			<td>
	                				<select tabindex="3" readonly="readonly" name="city_name_summ" id="city_name_summ" onchange="summary_data()" class="form-control" required style="margin-left:0px;">
									<? $sql_city = select_query("Select CTYCODE,CTYNAME from City where deleted='N' order by CTYNAME ASC");
										foreach($sql_city as $cityrow) { ?>
										<option value="<?=$cityrow['CTYCODE']?>" <? if($_REQUEST['city_name_summ'] == $cityrow['city_name_summ']) { ?>selected<? } ?>><?=$cityrow['CTYNAME']?></option>
									<? } ?>
									</select>


	                			<!--<input type="textbox" class="form-control" name="fac_location_summ" id="fac_location_summ" readonly="readonly" style="text-transform:uppercase;">-->
	                			</td>
	                		</tr>
	                		<tr>
	                			<td style="text-align: right;padding-top: 15px;">Audit Date  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" readonly="readonly" class="form-control" name="audit_date_summ" id="audit_date_summ" readonly="readonly" required></td>
	                			<td style="text-align: right;padding-top: 15px;">Audit Type  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="auditor_type_summ" id="auditor_type_summ" readonly="readonly" required style="text-transform:uppercase;"></td>
	                			<td style="text-align: right;padding-top: 15px;">Auditor Name  <span class="colr_red">*</span> </td>
	                			<td><input type="textbox" class="form-control" name="auditor_name_summ" id="auditor_name_summ" readonly="readonly" required style="text-transform:uppercase;"></td>
	                		</tr>
	                	</tbody>
	                </table>

	                <table class="table">
	                	<tr>
	                		<td>
	                			Date Of Visit <span class="colr_red">*</span> 
	                		</td>
	                		
	                		<td>
	                			Action Required <span class="colr_red">*</span> 
	                		</td>
	                		
	                		<td>
	                			Completion Date <span class="colr_red">*</span> 
	                		</td>
	                		<td></td>
	                		<td>
	                			Evidence Required For Sign Off <span class="colr_red">*</span> 
	                		</td>
	                		<td></td>
	                		<td>
	                			Verified By Audit Company <span class="colr_red">*</span> 
	                		</td>
	                		<td></td>
	                		<td>
	                			Remarks <span class="colr_red">*</span> 
	                		</td>
	                	</tr>
	                	<tr>
	                		<td><input type="textbox" readonly="readonly" class="form-control" name="visit_date" id="visit_date" required style="width: 50%">
	                		</td>
	                		
	                		<td><input type="textbox" class="form-control" name="action_required" id="action_required" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
	                		
	                		<td><input type="textbox" readonly="readonly" class="form-control" name="complete_date" id="complete_date" required style="width: 50%">
	                		</td>
	                		<td></td>
	                		<td><input type="textbox" class="form-control" name="evidence_required" id="evidence_required" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
	                		<td></td>
	                		<td><input type="textbox" readonly="readonly" class="form-control" name="verify_date" id="verify_date" required style="width: 50%">
	                		</td>
	                		<td></td>
	                		<td><input type="textbox" class="form-control" name="remarks" id="remarks" maxlength="100" required style="width: 50%;text-transform: uppercase;"></td>
	                	</tr>
	                </table>
					</div>
				  </div> 
      			</div>
			   <!-- /.tab-pane 4 end  -->
			  </div>
            </div>
          </div>
        </div>
      </div>
	<center><input type="button" name="newsave" id="newsave" value="SAVE" class="form-control btn-info" style="width: 85px;border-radius: 5px;"> </center>
	<br/> <br/>
	  <input type="hidden" name="req" id="req" value="">
		 <div class="modal  fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Message</h4>
              </div>
              <div class="modal-body" style="color: red;font-size: x-large;font-weight: bolder;text-align: -webkit-center;">
                <p id="div_p">&hellip;</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
		</section>
		<!-- /.content -->
		</div>
		<a href="#0" class="cd-top">Top</a>
		<div style="clear:both;"></div>
    	<? include("includes/footer.php"); ?>
	  	<div style="clear:both;"></div>
		<!-- /.content-wrapper -->


	<!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- angular js -->
    <script src="bootstrap/js/angular.min.js"> </script>
    <!-- Bootstrap 3.3.7 -->
	<script src="dist/newlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="dist/newlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- FastClick -->
	<script src="dist/newlte/bower_components/fastclick/lib/fastclick.js"></script>
	<!-- Select2 -->
	<script src="dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
    <link href="bootstrap/css/select2.css" rel="stylesheet"/>
    <script src="bootstrap/js/select2.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/newlte/app.min.js" type="text/javascript"></script>
	<!-- iCheck 1.0.1 -->
	<script src="dist/newlte/iCheck/icheck.min.js"></script>
	
	<script type="text/javascript" src="bootstrap/js/zebra_datepicker.js"></script>
    <script type="text/javascript" src="bootstrap/js/core.js"></script>
	
	<script src="js/scroll_to_top.js"></script> <!-- Gem jQuery Scroll to TOP -->
	<script src="bootstrap/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/fresco.js"></script>
	<script src="bootstrap/js/drag.js"> </script>
	<script src="bootstrap/js/click.js"> </script>
	<!-- Select2 -->
	<script src="               dist/newlte/bower_components/select2/dist/js/select2.full.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="dist/newlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script  type="text/javascript">
	$(window).load(function() {
		$(".loader").fadeOut("slow");
	});

	// Required Validation Checking
	$('#newsave').click(function(){
	var error = 0;
	var msg = 'Please enter all the required fields !! \n';
	$(':input[required]', '#entry_form').each(function(){
	    $(this).css('border','2px solid green');
	    if($(this).val() == ''){
	        msg; //+= '\n' + $(this).attr('id') + ' Is A Required Field..';
	        $(this).css('border','2px solid red');
	        if(error == 0){ $(this).focus(); }
	        error = 1;
	    }
	});
	if(error == 0){
            $(this).focus();
            var tab = $(this).closest('.tab-pane').attr('id');
            $('#myTab a[href="#' + tab + '"]').tab('show');
	}
	if(error == 1) {
	    alert(msg);
	    return false;
	} else {
		save_entry();
	    return true;
	}
	});
	 
	 $('#audit_date').datepicker({
      autoclose: true,
	  format: 'dd-mon-yyyy',
	  startDate: '-366d',
	  endDate: '1d',
    })

	$('#visit_date').datepicker({
      autoclose: true,
	  format: 'dd-M-yyyy',
	  startDate: '-366d',
	  endDate: '1d',
    })

	$('#complete_date').datepicker({
      autoclose: true,
	  format: 'dd-M-yyyy',
	  startDate: '-366d',
	  endDate: '-1d',
    })

    $('#verify_date').datepicker({
      autoclose: true,
	  format: 'dd-M-yyyy',
	  startDate: '-366d',
	  endDate: '-1d',
    })

    $('#audit_dates').datepicker({
      autoclose: true,
	  format: 'dd/mm/yyyy',
	  startDate: '-366d',
	  endDate: '-1d',
    })
	/*function view_tab(tabid){
         	if(tabid == '1'){
				document.getElementById('tab_1').style.display='block';
				document.getElementById('tab_2').style.display='none';
				document.getElementById('tab_3').style.display='none';
				document.getElementById('tab_4').style.display='none';
				$("#tab1").addClass("active");
				$("#tab2").removeClass("active");
				$("#tab3").removeClass("active");
				$("#tab4").removeClass("active");
			}else if(tabid == '2'){
				document.getElementById('tab_2').style.display='block';
				document.getElementById('tab_1').style.display='none';
				document.getElementById('tab_3').style.display='none';
				document.getElementById('tab_4').style.display='none';
				$("#tab2").addClass("active");
				$("#tab1").removeClass("active");
				$("#tab3").removeClass("active");
				$("#tab4").removeClass("active");
			}else if(tabid == '3'){
				document.getElementById('tab_3').style.display='block';
				document.getElementById('tab_1').style.display='none';
				document.getElementById('tab_2').style.display='none';
				document.getElementById('tab_4').style.display='none';
				$("#tab3").addClass("active");
				$("#tab1").removeClass("active");
				$("#tab2").removeClass("active");
				$("#tab4").removeClass("active");
         	} else {
				document.getElementById('tab_4').style.display='block';
				document.getElementById('tab_1').style.display='none';
				document.getElementById('tab_2').style.display='none';
				document.getElementById('tab_3').style.display='none';
				$("#tab4").addClass("active");
				$("#tab2").removeClass("active");
				$("#tab3").removeClass("active");
				$("#tab1").removeClass("active");
			}
        }*/

        $(document).ready(function()
	{
		$("#city_name").select2();
		$("#country_name").select2();
	});

        function summary_data()
        {
        	var auditor_types = document.getElementById('audit_type').value;
        	var product_cat = document.getElementById('product_category').value;
        	var auditor_date = document.getElementById('audit_date').value;
        	var auditor_name = document.getElementById('audit_name').value;
        	var factory_name = document.getElementById('cmpy_name').value;
        	var company_city_code = document.getElementById('city_name').value;
        	var company_country = document.getElementById('country_name').value;
        	//var factory_city = document.getElementById('cmpy_city').value;
        	//alert(company_city);
        	//alert(company_country);
        	var type_name = '';
        	if(auditor_types == 'I'){
        		type_name = 'Initial';
        	}
        	else if(auditor_types == 'F'){
        		type_name = 'Follow-up';
        	}
        	else if(auditor_types == 'D'){
        		type_name = 'During Production';
        	}
        	else if(auditor_types == 'O'){
        		type_name = 'Other'
        	}
        	document.getElementById('auditor_type_summ').value = type_name;
        	document.getElementById('product_category_summ').value = product_cat;
        	document.getElementById('audit_date_summ').value = auditor_date;
        	document.getElementById('auditor_name_summ').value = auditor_name;
        	document.getElementById('fac_name_summ').value = factory_name;
        	document.getElementById('city_code_hidden').value = company_city_code;
        	document.getElementById('city_name_summ').value = company_city_code;
        	document.getElementById('country_name_hidden').value = company_country;
        	//document.getElementById('fac_location_summ').value = factory_city;
        }




		$(".subcontract").click(function()
		{
			if($("input[name=subcontract]:checked").val() == "Y")
			{
				$("#subcontract_comment").attr("disabled",false);
			}
			else
			{
				$("#subcontract_comment").attr("disabled",true);
				$("#subcontract_comment").val('');
			}
		});

		$(".accred").click(function()
		{
			if($("input[name=accred]:checked").val() == "OTHERS")
			{
				$("#oth_accred").attr("disabled",false);
			}
			else
			{
				$("#oth_accred").attr("disabled",true);
				$("#oth_accred").val('');
			}
		});

		$(".cutting").click(function()
		{
			if($("input[name=cutting]:checked").val() == "N")
			{
				$("#othstate").attr("disabled",false);
			}
			else
			{
				$("#othstate").attr("disabled",true);
				$("#othstate").val('');
			}
		});
		
		$(".frame").click(function()
		{
			if($("input[name=frame]:checked").val() == "O")
			{
				$("#other_frames").attr("disabled",false);
			}
			else
			{
				$("#other_frames").attr("disabled",true);
				$("#other_frames").val('');
			}
		});

		$(".wip").click(function()
		{
			if($("input[name=wip]:checked").val() == "O")
			{
				$("#othwip").attr("disabled",false);
			}
			else
			{
				$("#othwip").attr("disabled",true);
				$("#othwip").val('');
			}
		});

	/*	$('#slt_supplier').select2({
        placeholder: 'Select an Supplier',
		allowClear: true,
		dropdownAutoWidth: true,
		minimumInputLength: 4,
		ajax: {
          url: 'ajax_factory.php.php?mode=ASUPP',
          dataType: 'json',
		  delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }); */

// loading Supplier Code & Name

		$('#slt_supplier').select2({
        placeholder: 'Select an Supplier',
		allowClear: true,
		dropdownAutoWidth: true,
		minimumInputLength: 4,
		ajax: {
          url: '/ajax_factory.php?mode=ASUPP',
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

// loading Supplier Details

		function get_supplier()
		{
			var supcode=$('#slt_supplier').val();
			//alert(supcode);
			$.ajax({
				url:'/ajax_factory.php?supcode='+supcode+'&mode=get_supplier',
				success:function(data){
					var supplier =  jQuery.parseJSON(data);
					$('#supp_code').val(supplier[0]['SUPCODE']);
					$('#sup_name_summ').val(supplier[0]['SUPNAME']);
					$('#supp_add1').val(supplier[0]['SUPADD1']);
					$('#supp_add2').val(supplier[0]['SUPADD2']);
					$('#supp_add3').val(supplier[0]['SUPADD3']);
					$('#supp_cty').val(supplier[0]['CTYNAME']);
					$('#supp_phone').val(supplier[0]['SUPPHN1']);
					$('#supp_mob').val(supplier[0]['SUPMOBI']);
					$('#supp_email').val(supplier[0]['SUPMAIL']);
					$('#supp_fax').val(supplier[0]['SUPFAXN']);
				}
			});
		}

$(".organization_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

$(".company_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

$(".machinery_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

$(".factory_det_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

	function check_male()
	{
		var tot = document.getElementById('total_employees').value;
		var male = document.getElementById('male_employees').value;
		var female = document.getElementById('female_employees').value;
		if(male > (tot - female)){
			alert("The Male Workforce is Exceed !!");
			document.getElementById('male_employees').value = '';
			document.getElementById('male_employees').focus();
		}
	}

	function check_female()
	{
		var tot = document.getElementById('total_employees').value;
		var female = document.getElementById('female_employees').value;
		var male = document.getElementById('male_employees').value;
		if(female > (tot - male)){
			alert("The Female Workforce is Exceed !!");
			document.getElementById('female_employees').value = '';
			document.getElementById('female_employees').focus();
		}
	}

/*
	function check_workforce()
	{
		var tot = document.getElementById('total_employees').value;
		var female = document.getElementById('female_employees').value;
		var male = document.getElementById('male_employees').value;
		if(tot > (male + female)){
			alert("The Total Workforce is Exceed !!");
			document.getElementById('total_employees').value = '';
			document.getElementById('total_employees').focus();
		}
	}
*/



$(".cutting_table_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });


$(".finishing_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

$(".inspect_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

$(".wip_num_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });


$(document).ready(function(){
    $(".textonly").keypress(function(event){
        var inputValue = event.charCode;
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)){
            event.preventDefault();
        }
    });
});


// Save Process
        function save_entry(){
				//$('.loader').show();	
				var formElement = document.getElementById("entry_form");
				var formData = new FormData(formElement);
				$.ajax({
					type: "POST",
					data: formData,
					url:  "ajax_factory.php?mode=save_entry",
					contentType: false,
           			processData: false,
					success: function(data){
						$('.loader').hide();
						if (data == 1) {
						alert("Factory Evaluation Entry Added Successfully.!");
						window.location.reload();
						}else{
						alert("Factory Evaluation Entry Added Failed.!");
						}
					},
					error: function(){}
				});
			}


// Fabric Validation
	function fabric_validate(val,id) 
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	fabric_totcalc();
     	}
	}

	$(".fabric_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Fabric Calculation
	function fabric_totcalc() {
     var arr = document.getElementsByClassName('fabric_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('fabric_tot_score').value  = tot.toFixed(2);
		document.getElementById('fab_acc_score').value  = tot.toFixed(2);
        var fabric_factory_score = document.getElementById('fab_acc_score').value;
	   	var fabric_factory_percent = (fabric_factory_score/12)*100;
	    document.getElementById('fab_acc_per').value = fabric_factory_percent.toFixed(2);
	    final_total();
    }


// Sampling Validation
	function sampling_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	sampling_totcalc();
     	}
	}

	$(".sampling_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Sampling Calculation
	function sampling_totcalc() {
     var arr = document.getElementsByClassName('sampling_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('sampling_tot_score').value  = tot.toFixed(2);
		document.getElementById('sampling_score').value  = tot.toFixed(2);
        var sampling_factory_score = document.getElementById('sampling_score').value;
	   	var sampling_factory_percent = (sampling_factory_score/21)*100;
	    document.getElementById('sampling_per').value = sampling_factory_percent.toFixed(2);
	    final_total();
    }

// Production, Planning, Controls Validation
	function ppc_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	ppc_totcalc();
     	}
	}

	$(".ppc_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Production, Planning, Controls Calculation
	function ppc_totcalc() {
     var arr = document.getElementsByClassName('ppc_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('ppc_tot_score').value  = tot.toFixed(2);
		document.getElementById('ppc_score').value  = tot.toFixed(2);
        var ppc_factory_score = document.getElementById('ppc_score').value;
	   	var ppc_factory_percent = (ppc_factory_score/9)*100;
	    document.getElementById('ppc_per').value = ppc_factory_percent.toFixed(2);
	    final_total();
    }

// Spreading & Cutting Validation
	function spr_cut_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	spr_cut_totcalc();
     	}
	}

	$(".spread_cut_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Spreading & Cutting Calculation
	function spr_cut_totcalc() {
     var arr = document.getElementsByClassName('spread_cut_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('spread_cut_tot_score').value  = tot.toFixed(2);
		document.getElementById('spread_cut_score').value  = tot.toFixed(2);
        var cut_factory_score = document.getElementById('spread_cut_score').value;
	   	var cut_factory_percent = (cut_factory_score/33)*100;
	    document.getElementById('spread_cut_per').value = cut_factory_percent.toFixed(2);
	    final_total();
    }

    // Warehouse Validation
	function warehouse_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	warehouse_totcalc();
     	}
	}

	$(".warehouse_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Warehouse Calculation
	function warehouse_totcalc() {
     var arr = document.getElementsByClassName('warehouse_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('warehouse_tot_score').value  = tot.toFixed(2);
		document.getElementById('warehouse_score').value  = tot.toFixed(2);
        var warehouse_factory_score = document.getElementById('warehouse_score').value;
	   	var warehouse_factory_percent = (warehouse_factory_score/9)*100;
	    document.getElementById('warehouse_per').value = warehouse_factory_percent.toFixed(2);
	    final_total();
    }

// Quality Management Questions Validation
	function qmq_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	qmq_totcalc();
     	}
	}

	$(".qmq_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Quality Management Questions Calculation
	function qmq_totcalc() {
     var arr = document.getElementsByClassName('qmq_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('qmq_tot_score').value  = tot.toFixed(2);
		document.getElementById('qmq_score').value  = tot.toFixed(2);
        var qmq_factory_score = document.getElementById('qmq_score').value;
	   	var qmq_factory_percent = (qmq_factory_score/30)*100;
	    document.getElementById('qmq_per').value = qmq_factory_percent.toFixed(2);
	    final_total();
    }


// Lab Testing Validation
	function labtest_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	labtest_totcalc();
     	}
	}

	$(".labtest_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Lab Testing Calculation
	function labtest_totcalc() {
     var arr = document.getElementsByClassName('labtest_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('labtest_tot_score').value  = tot.toFixed(2);
		document.getElementById('lab_test_score').value  = tot.toFixed(2);
        var labtest_factory_score = document.getElementById('lab_test_score').value;
	   	var labtest_factory_percent = (labtest_factory_score/15)*100;
	    document.getElementById('lab_test_per').value = labtest_factory_percent.toFixed(2);
	    final_total();
    }


// Finishing Packing Testing Validation
	function finishing_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	finishing_totcalc();
     	}
	}

	$(".finish_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Finishing Packing Calculation
	function finishing_totcalc() {
     var arr = document.getElementsByClassName('finish_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('finish_tot_score').value  = tot.toFixed(2);
		document.getElementById('fin_pac_score').value  = tot.toFixed(2);
        var finish_factory_score = document.getElementById('fin_pac_score').value;
	   	var finish_factory_percent = (finish_factory_score/48)*100;
	    document.getElementById('fin_pac_per').value = finish_factory_percent.toFixed(2);
	    final_total();
    }


// Work in Process Testing Validation
	function wip_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	wip_totcalc();
     	}
	}

	$(".wip_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Work in Process Calculation
	function wip_totcalc() {
     var arr = document.getElementsByClassName('wip_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('wip_tot_score').value  = tot.toFixed(2);
		document.getElementById('wrk_pro_score').value  = tot.toFixed(2);
        var wip_factory_score = document.getElementById('wrk_pro_score').value;
	   	var wip_factory_percent = (wip_factory_score/45)*100;
	    document.getElementById('wrk_pro_per').value = wip_factory_percent.toFixed(2);
	    final_total();
    }


// Work in Process Testing Validation
	function inspection_validate(val,id)
	{
     	if (val>3 || val<0) {
     	alert("Supplier score shouldn't exceed than maximum score !!");
     	$('#'+id).val('');
     	$('#'+id).focus();
     	}else{
     	inspection_totcalc();
     	}
	}

	$(".inspection_input").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
      }
    });

// Work in Process Calculation
	function inspection_totcalc() {
     var arr = document.getElementsByClassName('inspection_input');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
        }
		document.getElementById('inspection_tot_score').value  = tot.toFixed(2);
		document.getElementById('inspection_score').value  = tot.toFixed(2);
        var inspection_factory_score = document.getElementById('inspection_score').value;
	   	var inspection_factory_percent = (inspection_factory_score/15)*100;
	    document.getElementById('inspection_per').value = inspection_factory_percent.toFixed(2);
	    final_total();
    }

    function final_total() {
     var arr = document.getElementsByClassName('grand_total');
     var tot=0;
      for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value))
          tot += parseFloat(arr[i].value);
    }

        document.getElementById('final_grand_total').value = tot.toFixed(2);

        var arr_per = document.getElementsByClassName('grand_per');
     	var tot_per=0;
     	for(var j=0;j<arr_per.length;j++){
        if(parseFloat(arr_per[j].value))
          tot_per += parseFloat(arr_per[j].value);
        }

        var per = (tot_per/10).toFixed(2);

        var grade = 'E';
        if(per<=100 && per>=90) {
	       	grade = 'A';
        }
        else if(per<=89 && per>=75) {
    	   	grade = 'B';
        }
        else if(per<=74 && per>=50) {
        	grade = 'C';
        }
        else if (per<=49 && per>=31) {
       		grade = 'D';
        }
        document.getElementById('final_grade').value = grade;
	}


	</script>
  </body>
</html>
<?
}
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>