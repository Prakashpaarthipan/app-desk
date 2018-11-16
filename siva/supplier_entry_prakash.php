<?php
header('Cache-Control: no cache'); //no cache // This is for avoid failure in submit form  pagination form details page
session_cache_limiter('private_no_expire, must-revalidate'); // works // This is for avoid failure in submit form  pagination form details page

try {
error_reporting(0);
include('../lib/config.php');
include("db_connect/public_functions.php");
include('includes/config.php');
// include('approval_desk/general_functions.php');
extract($_REQUEST);
print_r($_REQUEST);

if($_SESSION['tcs_userid'] == '') { ?>
	<script>window.location='logout.php?msg=session';</script>
<?php exit();
}

	  $menu_name = 'SUPPLIER PURCHASE PROFILE';
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
	}  
	 
	 
	//Add
	
	if(isset($sbmt_supplier))
	{
		print_r($_REQUEST);
		$sql_year=select_ktmquery("select PORYEAR from trandata.codeinc@ktmcentr");
			$sql_entnum=select_ktmquery("SELECT Nvl(Max(ENTNUMB),0)+1  ENTNUMB FROM Supplier_Touch_Add_Request WHERE ENTYEAR='".$sql_year[0]['PORYEAR']."'");
			$sql_entsrno=select_ktmquery("SELECT Nvl(Max(ENTSRNO),0)+1  ENTSRNO FROM SUPPLIER_TOUCH_ADD_REQUEST WHERE ENTNUMB='".$sql_entnum[0]['ENTNUMB']."' and ENTYEAR='".$sql_year[0]['PORYEAR']."'");
			$field_appreqdt_m = array();
			$field_appreqdt_mc = array();
			if(count($slt_supcode) !=0){
				
				$tbl_appreqdt_m = "SUPPLIER_TOUCH_ADD_REQUEST";
				for($m =0;$m<count($slt_supcode) ; $m++){
					
					$sql_entnum=select_ktmquery("SELECT Nvl(Max(ENTNUMB),0)+1  ENTNUMB FROM Supplier_Touch_Add_Request WHERE ENTYEAR='".$sql_year[0]['PORYEAR']."'");
					$sup_code=explode(" - ",$slt_supcode[$m]);
					$prd_code=explode(" - ",$prdcode[$m]);
					$sql_Prdtouch = select_ktmquery("Select Prdtouch from Product where prdcode='".$prd_code[0]."'");
					$field_appreqdt_m['ENTYEAR'] = $sql_year[0]['PORYEAR'];
					$field_appreqdt_m['ENTNUMB'] = $sql_entnum[0]['ENTNUMB'];
					$field_appreqdt_m['ENTSRNO'] = $sql_entsrno[0]['ENTSRNO']+$m;
					$field_appreqdt_m['SUPCODE'] = $sup_code[0];
					$field_appreqdt_m['PRDCODE'] = $prd_code[0];					
					if ($weight_range[$m]=='0')
						{ 
						$field_appreqdt_m['ALLRANGE'] = 'Y';
						
						} 		
						else 
						{
						$field_appreqdt_m['ALLRANGE'] = 'N';						
						
						}
					$field_appreqdt_m['WGTSRNO'] = $weight_range[$m];
					$field_appreqdt_m['WGTRANGE'] = $weight_range[$m];
					$field_appreqdt_m['PRDTOUCH'] = $sql_Prdtouch[0]['PRDTOUCH'];
					$field_appreqdt_m['PRDCALC'] = $touch[$m] - $sql_Prdtouch[0]['PRDTOUCH'];
					$field_appreqdt_m['TOTCALC'] = $touch[$m];
					$field_appreqdt_m['NTOTCALC'] = $touch[$m];
					//
					$field_appreqdt_m['PAYMODE'] = 'R';
					$field_appreqdt_m['PM_STATUS'] = 'N';
					$field_appreqdt_m['HOD_STATUS'] = 'N';
					$field_appreqdt_m['GM_STATUS'] = 'N';
					$field_appreqdt_m['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$field_appreqdt_m['ADDDATE'] = strtoupper(date('d-M-y'));
					$field_appreqdt_m['DELETED'] = 'N';
					print_r($field_appreqdt_m);
					$insert_appreqdt = insert_ktmquery($field_appreqdt_m, $tbl_appreqdt_m);
				}
							
				for($n=0;$n<count($slt_supcode);$n++)
				{			
					$sup_code=explode(" - ",$slt_supcode[$n]);
					$prd_code=explode(" - ",$prdcode[$n]);
							$sql_entnum=select_ktmquery("SELECT Nvl(Max(ENTNUMB),0)+1  ENTNUMB FROM Supplier_Touch_Add_Request WHERE ENTYEAR='".$sql_year[0]['PORYEAR']."'");
							$tbl_appreqdt_mc = "SUPPLIER_TOUCH_MC";
							$field_appreqdt_mc['ENTYEAR'] = $sql_year[0]['PORYEAR'];
							$field_appreqdt_mc['ENTNUMB'] = $sql_entnum[0]['ENTNUMB'];
							$field_appreqdt_mc['ENTSRNO'] = $sql_entsrno[0]['ENTSRNO']+$n;
							$field_appreqdt_mc['SUPCODE'] = $sup_code[0];
							$field_appreqdt_mc['PRDCODE'] = $prd_code[0];
							$field_appreqdt_mc['MAKSRNO'] = $n+1;
							$field_appreqdt_mc['FR_PRDWGHT'] = $fr_wgt[$n];
							$field_appreqdt_mc['TO_PRDWGHT'] = $to_wgt[$n];
							$field_appreqdt_mc['OPTMKCH'] = $slt_mc_opt[$n];
							$field_appreqdt_mc['PRDMKCH'] = $mak_charge[$n];
							print_r($field_appreqdt_mc);
							$insert_appreqdt_mc = insert_ktmquery($field_appreqdt_mc, $tbl_appreqdt_mc);
				}
				
			}
	}
	
	
	/*if(isset($sbmt_supplier)) 
	{
		
		//exit;
		$sup_code=explode(" - ",$slt_supcode);
		$prd_code=explode(" - ",$prdcode);
		
		$sqlExistQuery =select_ktmquery("SELECT * FROM SUPP_PURCHASE_PROFILE WHERE prdcode='".$prd_code[0]."' and SUPCODE='".$sup_code[0]."'");
		//$sqlExistQuerymc =select_ktmquery("SELECT * FROM SUPP_PURCHASE_PROFILE_MC WHERE prdcode='".$prd_code[0]."' and SUPCODE='".$sup_code[0]."'");
		//var_dump($sqlExistQuery);
		//$str ='';		
		//for($z=0;$z < count($weight_range);$z++)
        //{
		
			//echo 'gg'.$weight_range[$z];
		//}
		//echo 'value ='.$str;
		//exit;
		
		if(empty($sqlExistQuery)) 
		{
			// Insert in SUPPLIER_TOUCH_ADD_REQUEST Table
			//$weightrange3=explode(" - ",$weightrange2);
			//echo $ij;
			//count($weightrange2);
			//exit();
			
			
			
			
			
			for($i=0; $i < count($prdcode); $i++) 
			{ 
		        for($k=1;$k<=count($weight_range);$k++)
				{
					//$nweight[] = implode(',',$weight_range[$k]);
					$tweight[] = $weight_range[$k];
					
					//print_r($weight_range[$k]);
				}
				//echo $i+1;
				$ij=0; 
				foreach($tweight[$i] as $twgtsrno)
				{
					$ij++;
					//echo $i;
					//echo $ij;
					//echo $twgtsrno;
					//echo $i+1;
					//var_dump($pay_mode[$i+1]);
					$tbl_appreqdt = "SUPPLIER_TOUCH_ADD_REQUEST";
					$sup_code=explode(" - ",$slt_supcode[$i]);
					$prd_code=explode(" - ",$prdcode[$i]);
					//exit;
					$sql_year=select_ktmquery("select PORYEAR from trandata.codeinc@ktmcentr");
					//var_dump($sql_year);
					$sql_entsrno=select_ktmquery("SELECT Nvl(Max(ENTSRNO),0)+1  ENTSRNO FROM SUPPLIER_TOUCH_ADD_REQUEST WHERE ENTNUMB='".$sql_entnum[0]['ENTNUMB']."' and ENTYEAR='".$sql_year[0]['PORYEAR']."'");
					//var_dump($sql_entsrno);
					//echo $sql_entsrno[0]['ENTSRNO'];
					//var_dump($sql_entsrno);
					$sql_Prdtouch = select_ktmquery("Select Prdtouch from Product where prdcode='".$prd_code[0]."'");
					$field_appreqdt['ENTYEAR'] = $sql_year[0]['PORYEAR'];
					$field_appreqdt['ENTNUMB'] = $sql_entnum[0]['ENTNUMB'];
					$field_appreqdt['ENTSRNO'] = $sql_entsrno[0]['ENTSRNO'];
					$field_appreqdt['SUPCODE'] = $sup_code[0];
					$field_appreqdt['PRDCODE'] = $prd_code[0];
					//echo "<pre>";
					//print_r($weight_range);
					//echo "@@@<br>";
					//echo count($weight_range);
					//print_r($tweight[$i]);
					//exit;
					//echo count($weight_range[$i])."mo";
					//echo count($nweight[$i])."mh";
						//print_r($nweight);
						//echo $nweight[$i];
						//echo $nweight;
						//$weightrange1=join(",",$weight_range[$i]);
						//$field_appreqdt['WGTSRNO'] = $weightrange1[$i];
						//$weightrange2=explode(",",$nweight[$i]);
						//print_r($weightrange2);
					    //echo $twgtsrno;
						
						//exit;
						//var_dump($tweight[$i]);
						if ($twgtsrno=='0')
						{ 
						$field_appreqdt['ALLRANGE'] = 'Y';
						$field_appreqdt['WGTSRNO'] = $twgtsrno;
						} 		
						else 
						{
						$field_appreqdt['ALLRANGE'] = 'N';						
						$field_appreqdt['WGTSRNO'] = $twgtsrno;
						}
						
					$field_appreqdt['WGTRANGE'] = $twgtsrno;
					$field_appreqdt['PRDTOUCH'] = $sql_Prdtouch[0]['PRDTOUCH'];
					$field_appreqdt['PRDCALC'] = $touch[$i] - $sql_Prdtouch[0]['PRDTOUCH'];
					$field_appreqdt['TOTCALC'] = $touch[$i];
					$field_appreqdt['NTOTCALC'] = $touch[$i];
					$field_appreqdt['PAYMODE'] = 'R';
					$field_appreqdt['PM_STATUS'] = 'N';
					$field_appreqdt['HOD_STATUS'] = 'N';
					$field_appreqdt['GM_STATUS'] = 'N';
					$field_appreqdt['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$field_appreqdt['ADDDATE'] = strtoupper(date('d-M-y'));
					$field_appreqdt['DELETED'] = 'N';
					print_r($twgtsrno);
					print_r($tweight[$i]); 
					
					//exit;
					$ii = $i + 1;
					//$j = 1;
					for($j=0; $j < count($slt_mc_opt[$ii]); $j++)
					{
						// if ($need_mc_.$ii=='M')
						//{  
					$sqlExistQuerymc =select_ktmquery("SELECT * FROM SUPP_PURCHASE_PROFILE_MC WHERE prdcode='".$prd_code[0]."' and SUPCODE='".$sup_code[0]."'");
					    
							$tbl_appreqdt_mc = "SUPPLIER_TOUCH_MC";
							$field_appreqdt_mc['ENTYEAR'] = $sql_year[0]['PORYEAR'];
							$field_appreqdt_mc['ENTNUMB'] = $sql_entnum[0]['ENTNUMB'];
							$field_appreqdt_mc['ENTSRNO'] = $sql_entsrno[0]['ENTSRNO'];
							$field_appreqdt_mc['SUPCODE'] = $sup_code[0];
							$field_appreqdt_mc['PRDCODE'] = $prd_code[0];
							$field_appreqdt_mc['MAKSRNO'] = $j+1;
							$field_appreqdt_mc['FR_PRDWGHT'] = $fr_wgt[$ii][$j];
							$field_appreqdt_mc['TO_PRDWGHT'] = $to_wgt[$ii][$j];
							$field_appreqdt_mc['OPTMKCH'] = $slt_mc_opt[$ii][$j];
							$field_appreqdt_mc['PRDMKCH'] = $mak_charge[$ii][$j];
							print_r($field_appreqdt_mc);
							$insert_appreqdt_mc = insert_ktmquery($field_appreqdt_mc, $tbl_appreqdt_mc);
						
							//var_dump($insert_appreqdt_mc);
							//var_dump($field_appreqdt_mc);
						//}
						//echo $slt_mc_opt[$i];
						echo $pay_mode[$i+1];
					}
					print_r($field_appreqdt);
					$insert_appreqdt = insert_ktmquery($field_appreqdt, $tbl_appreqdt);
					//var_dump($weightrange1);
					//count($weightrange1);
					//var_dump($weight_range);
					//echo "<br />";
					//echo $pay_mode[$i]."p";
					//var_dump($insert_appreqdt);
					//var_dump($field_appreqdt);
					//echo $insert_appreqdt;
					//echo count($prdcode);
					//echo $weightrange1[$i]."<br />";
					//echo $weightrange1."V";
					//echo $weight_range[0];
					//echo "hi".$i;
					 exit();
					
				}
			}
			exit();	
			//exit;
			if($insert_appreqdt == 1 || $insert_appreqdt_mc == 1) 
			{ ?>
					<script>alert("Touch Request Entry Added Succesfully");</script>
					<script>window.location='supplier_entry.php';</script>
			<?
					//exit();
			} 
			else 
			{ ?>
					<script>alert("Touch Request Entry Failed.. Kindly try Again!!");</script>
					<script>window.location='supplier_entry.php';</script>
				<?
					//exit();
			}
				
		}else{

			?>
				<script>alert("Touch Request Entry Already Exists!!");</script>
				<script>window.location='supplier_entry.php';</script>
			<?
			//exit;
		
		} 
				
	}*/
$bx_hdr = "#326296";
$tlhdft_tr = "#326296"; 
$bx_prm = "#A4CCF7"; 
$tl_hover = "#B8C3CB";
$tl_trodd = "#C2CEDB"; 
$tl_treven = "#E4E9ED";

if($_SESSION['loggedin_category'] == 'JEWELLERY') {
	$tcs_ktmtj = 'TJ';
	$tcs_ktmtj_img = '../images/jewellery.png';
} else {
	$tcs_ktmtj = 'TCS';
	$tcs_ktmtj_img = '../images/logo.png';
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?//=$tcs_ktmtj?>TJ Portal :: Supplier Purchase Profile :: <?php echo $site_title; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="dist/fonts/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="dist/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/scroll_to_top.css"> <!-- Gem style Scroll to TOP -->
	<link href="bootstrap/css/tabelizer.min.css" media="all" rel="stylesheet" type="text/css" />
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
	.select2-container--default .select2-selection--multiple{
		width:80%;
	}
	#wgt_range
		{
			height:150px;
			overflow-y: scroll;
			overflow-x:hidden;
		}
	
	.nbs-flexisel-ul { width: 99999999999999999999px; }
	#imageID { background: url(http://edigi.co/Video-Intro.jpg) no-repeat; height: 300px; width: 300px; }
	#imageID:hover { background: url(http://edigi.co/Video-Intro-Hover.jpg)  no-repeat; }
	#video-player:-webkit-full-screen { width: 100%; height: 100%; background-color: black; }
	#video-player:-webkit-full-screen video { width: 100%; }
	.nbs-flexisel-container { min-height:700px; }
	.banner 
	{
		<? // if($active == 203) { ?>
			background:  url(images/stj-jewellery-logo.png) no-repeat; 
		<? /* } else { ?>
			background:  url(images/ktm-jewellery.png) no-repeat; 
		<? } */ ?>
	}
	.cnt_number { font-size: 34px; font-weight:bold; }
	.alert-close,.alert-close1,.alert-close2,.alert-close,.alert-close3,.alert-close4,.alert-close6,.alert-close7
	{
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
	.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.4;
		// background: url('images/cassette.jpg') 50% 50% no-repeat rgb(249,249,249);
		background: url('images/load_1.gif') 50% 50% no-repeat rgb(249,249,249);
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
	.table > tbody > tr > td{
		padding:8px;
	}
	
	.select2-container--default .select2-selection--multiple {
		overflow-y: scroll;
		height: 75px;
	}
</style>
<script>
		function submitbtn() {
			document.getElementById("sbmt_supplier").disabled = true;
		}

		var submitting = false;
		function Validate()
		{			
			if ((document.getElementById("slt_supcode").value) == "" ) { 
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Please Select Supplier Code!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				document.getElementById('slt_supcode').focus();
				return false ;
			}

			if ((document.getElementById("prdcode_1").value) == "" ) {
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Please Select Product Code!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				document.getElementById('prdcode_1').focus();
				return false ;
			}

            /* if ((document.getElementById("weight_range").value) == "" ) { 
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Please Enter the TP Amount!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				document.getElementById('weight_range').focus();
				return false ;
			} */

			if ((document.getElementById("touch_1").value) == "" ) {
				var ALERT_TITLE = "Message";
				var ALERTMSG = "Please Select Touch!!";
				createCustomAlert(ALERTMSG, ALERT_TITLE);
				document.getElementById('touch_1').focus();
				return false ;
			}


			return true;
		}


		function frm_submit(){
			this.form.submit();
		}
		$(document).ready(function(){
   

    $("#btn1").click(function(){
        $("ol").append("<table><tr><td>Appended item</td><td>1</td><td>3</td><td>5</td></tr></table>");
    });
});
<script type="text/javascript">
    $(document).ready(function () {
        var the_table = '<table border="1" style="width:700px"><tr><td>Name</td><td>Email</td><td>Mobile</td><td> Phone</td><td>active status</td></tr>';
        $.getJSON("registered_user.php", function (resp) {
            console.log(resp);
            $.each(resp, function (i, item) {
                the_table = the_table + '<tr>' + '<td>' + item.first_name + '</td>' + '<td>' + item.email + '</td>' + '<td>' + item.mobile + '</td>' + item.phone + '</td>'+ item.active + '</td></tr>';

            });
            the_table = the_table + '</table>';
            //  console.log(the_table);
            $('#output').html(the_table);
        });
    });


	</script>

</head>
<?
/*if(isset($_POST['add']))
{
$x=$_POST['slt_supcode'];		
$y=$_POST['prdcode_1'];	
$range=$_POST['weight_range'];
$touch=$_POST['touch'];	
$pay=$_POST['pay_mode'];
$mc=$_POST['slt_mc_opt'];
$frwgt=$_POST['fr_wgt'];
$towgt=$_POST['to_wgt'];
$makchg=$_POST['mak_charge'];		
		 
//echo "$x";	
//echo "$y";
//echo "$range";
//echo "$touch";
//echo "$pay";	
//echo "$mc";
//echo"$frwgt";
//echo"$towgt";
//echo"$makchg";
}*/
?>
<body oncopy="return false" oncut="return false" onpaste="return false"	ondragstart="return false" onselectstart="return false" oncontextmenu="return false" class="skin-black sidebar-collapse" onkeydown="ESCclose(event)">
	<div id='pageloader' class="loader"></div>
    <div class="wrapper">
	<? include("includes/header.php"); ?>
	<!-- Left side column. contains the logo and sidebar -->
	<? include("includes/left_panelnew.php"); ?>
	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style='min-height: 825px !important; background-color:#ffffff; font-size:14px; padding-top: 10px; margin-bottom: -20px;'>
	<!-- banner -->
	<div class="nav-tabs-custom" style="padding: 10px; margin-top: 5px;">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#tab_1" style="padding: 10px;" data-toggle="tab" aria-expanded="true">Entry</a></li>
			  <li class=""><a href="#tab_5" style="padding: 10px;" data-toggle="tab" aria-expanded="true">Entry Status Report</a></li>
			    <?//Purchase Manager Approval
				if($_SESSION['tcs_descode'] == 136 or $_SESSION['tcs_descode'] == 68 or $_SESSION['tcs_descode'] == 68   or $_SESSION['tcs_esecode'] == 20) 
				{ ?>
              <li class=""><a href="#tab_2" style="padding: 10px;" data-toggle="tab" aria-expanded="false">PM Approval</a></li>
			  <?}?>
				<?//DGM Approval  
				if($_SESSION['tcs_descode'] == 189 or $_SESSION['tcs_descode'] == 110 or $_SESSION['tcs_esecode'] == 20) 
				{ ?>
				<li class=""><a href="#tab_3" style="padding: 10px;" data-toggle="tab" aria-expanded="false">DGM Approval</a></li>
			  <?}?>
			  <?//Gm Approval  
				if($_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 1 or $_SESSION['tcs_esecode'] == 20) 
				if($_SESSION['tcs_descode'] == 19 or $_SESSION['tcs_descode'] == 1 or $_SESSION['tcs_esecode'] == 20) 
				{ ?>
				<li class=""><a href="#tab_4" style="padding: 10px;" data-toggle="tab" aria-expanded="false">GM Approval</a></li>
              <?}?>
            </ul>
			
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
				<section class="content-header">
					<h1 style='text-transform:uppercase; padding:0px 0 0 0px'>
					  SUPPLIER TOUCH ADD REQUEST
					</h1>
				</section>
			<section class="content">
				<div class="row">
				<!-- /.Left column -->
				<div class="col-md-2"></div>
				<!-- /.Left column -->
				<form name="counter_master" id="counter_master" method="post" action="">
					<!-- Middle column -->
					<!-- <div class="col-md-8 col-xs-8"> -->
					<div class="">
									<? if($_REQUEST['msg'] != '') { ?>
										<div class="box box-primary box-solid" id="entry_successmsg">
											<div class="box-header with-border">
											  <h3 class="box-title">Message</h3>
											  <div class="box-tools pull-right">
												<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
											  </div><!-- /.box-tools -->
											</div><!-- /.box-header -->
											<div class="box-body">
											  <?=$_REQUEST['msg']?>
											</div><!-- /.box-body -->
											<script type="text/javascript">
												alert('<?=$_REQUEST['msg']?>');
											</script>
										</div>
									<? } ?>
						<div class="box box-primary" id='supplier_view'>
							<div class="box-header">
							  <h3 class="box-title">SUPPLIER TOUCH ADD REQUEST</h3>
							</div><!-- /.box-header -->
											
						<div class="col-md-12" style='text-transform:uppercase;padding-bottom:20px;'>
							<div class="box-body">
								<div class="form-group">
									<div class="col-md-12" style="padding-top:20px;">
										<div class="fair_border">
											<div class="col-md-12" id="calculatediv" style=' text-transform:uppercase;'>
												<div class="form-group" style='padding-left:15px;'>
													<label for="exampleInputPassword1">Supplier :<span style='color:red'>*</span></label>
													<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input"  id="slt_supcode" name="slt_supcode"  placeholder="Supplier Code / Name"  autocomplete="off" data-original-title="Supplier Code / Name">
												</div>
											</div>
											<div class="col-md-12" id="calculatediv" style=' text-transform:uppercase;'>
												<div class="form-group" style='padding-left:15px;'>
													<label for="exampleInputPassword1">Section Group :<span style='color:red'>*</span></label>
													<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input"  id="slt_prdgroup_1"   placeholder="Select Product Group" onchange="select_product(1);" autocomplete="off" data-original-title="Select Product Group">
													
													<?/*  $sqlProduct = select_ktmquery("select SECGRNO,GRPNAME from section_group where rownum<5 order by SECGRNO Asc");
													foreach ($sqlProduct as $key => $prd) {?>
														<option value="<?=$prd['SECGRNO']?>"> <?=$prd['SECGRNO']?>-<?=$prd['GRPNAME']?></option>	
													<?	}*/?>
												
												</div>
											</div>
												<!--<div class="col-md-12" style=' text-transform:uppercase;padding-bottom:20px;'>
													<div class="col-sm-1 text-right" style="float: right;">
														 <button id="removebtn" onclick="call_product_innergrid_remove(1)" style="background: #d73925 !important; color:white;" class="btn btn-remove btn-danger" type="button" title="Delete Row"><span class="glyphicon glyphicon-minus"></span></button>
													</div>
													<div class="col-sm-1 text-right" style="float: right;">
														 <input type="hidden" name="partint3" id="partint3" value="1">
														 <button class="btn btn-success btn-add3" onclick="call_product_innergrid(1)" style="background: #00a65a !important; color:white;"  type="button" title="Add Row"><span class="glyphicon glyphicon-plus"></span></button>
													</div>	
											</div>-->
											
											<div class="col-md-12" style='text-transform:uppercase;'>
												<div class="col-sm-6">
													<label for="exampleInputPassword1">Product  :<span style='color:red'>*</span></label>
													<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
													<!-- <input type="text" class="form-control" name="prdcode_1" id="prdcode_1" value="" onchange="select_range('1');" placeholder=" Product Code / Name"> -->
													<input type="text"   class="form-control"  id="prdcode_1" value="" onchange="select_range('1');" placeholder=" Product Code / Name">
												
													
												</div>
												<div class="col-sm-6">
													<label for="exampleInputPassword1">Weight Range  :<span style='color:red'>*</span></label>
													<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
													<?/*<select input type='text'  name="weight_range[1][]" id="weight_range_1" multiple class="form-control " style="margin-left:0px; width:200px;">*/?>
													<select input type='text' multiple class="form-control custom select" style="text-transform: uppercase;"  name="weight_range" id="weight_range_1" value="<?php echo $weight_range_1;?>" data-toggle="tooltip" data-placement="top" data-original-title="weight range" >
														<option value="0">Select All Weight Range </option>
														
														<?$prd_code=explode("-",$prdcode);
														$sql_branch = select_ktmquery("SELECT  WGTSRNO,FWEIGHT||'-'||TWEIGHT||'-'||MWEIGHT WGT from SECTION_WEIGHT where seccode='".$_REQUEST['subtype_id']."' order by wgtsrno");
														foreach($sql_branch as $branchrow) 
														{ 
															$wgt=explode("-",$branchrow['WGT']);?>
															<option value="<?php echo $branchrow['WGTSRNO'];?>" <? if($_REQUEST['weight_range'] == $branchrow['WGTSRNO']) { ?>selected<? } ?>><?php echo number_format($wgt[0],3)." - ".number_format($wgt[1],3)." - ".number_format($wgt[2],3);?>
															</option>
													 <? } ?>
													</select>
												</div>
												</div>
											<div class="col-md-12" style='text-transform:uppercase;'>
											<div class="col-sm-6">	
													 <label for="exampleInputPassword1">Touch : <span style='color:red'>*</span></label> 
													 <span role="status" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input number" maxlength="6" id="touch_1"  value="<?php echo $touch_1;?>" placeholder="Touch" data-original-title="Touch">
												</div>
													<div class="col-sm-6">	
													<label for="exampleInputPassword1">Payment Mode : </label><br />
											       &nbsp;<input type="radio" name="pay_mode[]" id="pay_mode" value="R" title="Regular" checked=""> Regular
											       &nbsp;<input type="radio" name="pay_mode[]" id="pay_mode" value="S" title="Spot"> Spot
											       &nbsp;<input type="radio" name="pay_mode[]" id="pay_mode" value="A" title="Advance"> Advance
												</div>
										</div>
											<div class="parts3_1 fair_border">
												<div class="form-group" style='padding-left:55px;'>
													<label for="exampleInputPassword1">MC : </label>
													<span role="status" class="ui-helper-hidden-accessible"></span>
													<input type="checkbox" name="need_mc_1" id="need_mc_1" value="<? $M?>" title="MC" onchange="checkBlock(1)">
												</div>
												
												<div id="text_1" style="display:none">
												<div class="col-md-12" id="count_mc" style='text-transform:uppercase;padding-bottom:20px;'>
													<div class="box-body">
														<div class="form-group">
															<div class="col-md-12" style="padding-top:20px;">
																	<div class="row">
																	<?	/*<div class="col-md-12" style=' text-transform:uppercase;padding-bottom:20px;'>
																			<div class="col-sm-1 text-right" style="float: right;">
																			<input type="hidden" name="partint3_1" id="partint3_1" value="1">
																				 <button id="removebtn_1" onclick="call_innergrid_remove(1)" style="background: #65261e !important; color:white;" class="btn btn-remove btn-danger" type="button" title="Delete Row"><span class="glyphicon glyphicon-minus"></span></button>
																			</div>
																			<div class="col-sm-1 text-right" style="float: right;">
																				 
																				 <button class="btn btn-success btn-add3" id="addbtn_1" onclick="call_innergrid(1)" style="background: #224233 !important; color:white;"  type="button" title="Add Row"><span class="glyphicon glyphicon-plus"></span></button>
																			</div>	
																		</div>*/?>
																		<div class="col-md-12">
																	</div>

																		<div class="col-md-12" style='text-transform:uppercase;'>
																			<div class="col-sm-3">
																				<label for="exampleInputPassword1">MC Option: </label>
																				<span role="status" class="ui-helper-hidden-accessible"></span>
																				<select tabindex="3" name="slt_mc_opt" id="slt_mc_opt_1_1"  class="form-control custom-select" style="margin-left:0px">
																						<option value="">Select MC Option </option>
																							<option value="G">GRAM</option>
																							<option value="K">KG</option>
																							<option value="Q">QTY</option>
																				</select>
																			</div>
																			<div class="col-sm-3">
																				<label for="exampleInputPassword1">From Weight : </label>
																				 <span role="status" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input"  id="fr_wgt_1_1" name="fr_wgt"  placeholder="From Weight" data-original-title="From Weight">
																			</div>
																			<div class="col-sm-3">	
																				<label for="exampleInputPassword1">To Weight : </label>
																				 <span role="status" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input"  id="to_wgt_1_1" name="to_wgt"  placeholder="To Weight" data-original-title="To Weight">
																			</div>
																			<div class="col-sm-3">	
																				 <label for="exampleInputPassword1">Making Charge : </label>
																				  <span role="status" class="ui-helper-hidden-accessible"></span><input type="text" class="form-control ui-autocomplete-input number"  id="mak_charge_1_1" name="mak_charge"  placeholder="Making Charge" data-original-title="Making Charge">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														 <!-- right column -->
														</div><!-- /.row -->
													</div>
												</div>					
											</div>
										</div>
									</div>
								 <!-- right column -->
								</div><!-- /.row -->
								<div class="form-group" align="center">
							<input tabindex="11" class="btn btn-success btn-add3" type="button" name='add' id='add'  onclick="return addrow(1);" style="background: #00a65a !important; color:white;"  style='width:100px' value="ADD">
						</div>
						<div id = "supplier_dynamic_table" name ="supplier_dynamic_table"> <h4>Supplier Touch Value Details</h4></div>
						
								<table>
								<thead>
									<?//for($prtouch_i=0; $prtouch_i<count($sql_touch); $prtouch_i++) {?>
									<tr>
									<th colspan="3"style="text-align: center;">Supplier </th>
									<th rowspan="2" style="text-align: center;">Section Group</th>
									<th colspan="2" style="text-align: center;">Product</th>
									<th rowspan="2" style="text-align: center;">Weight Range</th>
									<th rowspan="2" style="text-align: center;">Touch</th>
									<th rowspan="2" style="text-align: center;">Pay type</th>
									<th colspan="4" style="text-align: center;">MC</th>
									<tr>
									  <th>CODE</th>
									  <th>NAME</th>
									  <th>CITY</th>
									  <th>CODE</th>
									  <th>NAME</th>
									  <th>MC.OPTION</th>
									  <th>FROM.WEIGHT</th>
									  <th>TO.WEIGHT</th>
									  <th>CHARGE</th>
									</tr>
									</thead>
										<tbody class="parts3">
										</tbody>
									</table>
						
									 <?/*<tr>
									 <td></td>
										<td class="linehgh30px myCell add"><?php echo $i;?></td> 
										<td class="linehgh30px myCell add"  style="cell-padding:100px;" id="slt_supcode_<?$prtouch[id]?>"><?php echo $x;?></td>
										<td></td>
										<td class="linehgh30px myCell add" id="prdcode_1_<?$prtouch[id]?>"><?php echo $y;?></td>
										<td></td>
										<td></td>
										<td class="linehgh30px myCell add" id="weight_range_1_<?$prtouch[id]?>"><?php  if($range=='ALL WGT RANGE'){ echo $range; } else { echo number_format($wgt[0],3)." - ".number_format($wgt[1],3)." - ".number_format($wgt[2],3);}?></td>
										<td class="linehgh30px myCell add" id="touch_1_<?$prtouch[id]?>"><?php echo $touch;?>
										<td class="linehgh30px myCell add" id="pay_mode_<?$prtouch[id]?>"><?php echo REGULAR;?></td>
										<td class="linehgh30px myCell add" id="slt_mc_opt_1_1<?$prdtouch[id]?>"><?php echo $mc;?></td>
										<td class="linehgh30px myCell add" id="fr_wgt_1_1_<?$prtouch[id]?>"><?php echo $frwgt;?></td>
										<td class="linehgh30px myCell add" id="to_wgt_1_1_<?$prtouch[id]?>"><?php  echo $towgt;?></td>
										<td class="linehgh30px myCell add" id="mak_charge_1_1_<?$prtouch[id]?>"><?php echo $makchg;?></td>
								<?//}?>
									</tr>									
									</tr>*/?>
									 
							</div>
							<div id="output"></div>
						</div>									 
						<div class="form-group" align="center">
							<input tabindex="11" type="submit" name='sbmt_supplier' id='sbmt_supplier' class="btn btn-success" style="background: #00a65a !important; color:white;" onClick=" " style='width:100px' value="SUBMIT">
						</div>
						</div><!-- /.row -->	
					</div>
				  <!--</div>-->
				  <!--<div class="row" style="text-align:center;line-height: 50px;border-top: 1px solid #e0e0e0;margin: 0px;">				
				  </div><!-- /.Middle column -->
				</form>
				</div>
			</section>
		</div>
       <div class="tab-pane" id="tab_5">
			<section class="content-header">
				<h1 style='text-transform:uppercase; padding:0px 0 0 0px'>
				  SUPPLIER TOUCH ADD PENDING STATUS REPORT
				</h1>
				<div class="box-header ui-sortable-handle">
				  <a class="btn-sm" style="color:#FFFFFF;"><h3 class="box-title">Supplier Touch Add Pending Status Report </h3></a>
				  <!--<div class="pull-right box-tools">
					  <button class="btn btn-block btn-primary" onclick="window.location.reload()" style="padding: 5px 10px;"><i class="fa fa-repeat"></i></button>
				  </div>-->
				</div><!-- /.box-header -->  
				<div class="tab-pane" id="tab_5" style="display:block; padding:5px; border: 1px solid #e0e0e0;min-height: 350px;margin:0 0 px;">
					<div class="box-body table-responsive" style="display: block;">
					<div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id=scrollme> 
						<table id="example0" class="table table-bordered table-striped table-hover controller" style="border-collapse:collapse; overflow: hidden;">
							<!--<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2">DGM Status</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">GM Status</th>
							<th rowspan="2">GM Comments</th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>-->
							<tbody> 
						  <?php
							$sql_touch = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.GM_STATUS,Decode(Allrange,'Y','ALL WGT RANGE',WGTRANGE)  Wgtrange,Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode and R.Deleted='N' And R.PM_STATUS in ('Y','N','R','P') And R.HOD_STATUS in ('Y','N','R','P') And R.GM_STATUS in ('N','R','P')  And Allrange='Y' and R.ADDUSER in ('".$_SESSION['tcs_usrcode']."')
							union
							Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.GM_STATUS,Min(E.FWEIGHT)||'-'||max(E.TWEIGHT)  Wgtrange,R.Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S,section_weight E
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And E.seccode=P.seccode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode And R.WGTSRNO=E.WGTSRNO and R.Deleted='N' And Allrange='N' And R.PM_STATUS in ('Y','N','R','P') And R.HOD_STATUS in ('Y','N','R','P') And R.GM_STATUS in ('N','R','P') and R.ADDUSER in ('".$_SESSION['tcs_usrcode']."')  
							Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.GM_STATUS,R.Ntotcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) order by SECGRNO");
							//and R.ADDUSER in ('".$_SESSION['tcs_usrcode']."')
							$i=0;
                            $check_grpcode = '';							
							foreach($sql_touch as $prtouch) 
							{ $i++; 
								$sql_compariosion = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supp_purchase_profile R,Supplier S,City C,Product P
								Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode and R.Deleted='N'  and P.Prdcode='".$prtouch['PRDCODE']."'
								Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE'))");
								$sql_touch_mc = select_ktmquery("select M.ENTYEAR,M.ENTNUMB,M.ENTSRNO,M.SUPCODE,M.MAKSRNO,P.PRDCODE,P.Prdname,M.FR_PRDWGHT,M.TO_PRDWGHT,M.OPTMKCH,M.PRDMKCH from supplier_touch_mc M,Product P where  M.prdcode=P.prdcode And M.ENTYEAR='".$prtouch['ENTYEAR']."' and M.ENTNUMB=".$prtouch['ENTNUMB']." and M.ENTSRNO='".$prtouch['ENTSRNO']."' order by M.MAKSRNO");
								 //echo $sql_compariosion[0]['PRDCODE'];
								 $wgtrange=explode("-",$prtouch['WGTRANGE']);
							?>
                          							
						  <?php if($prtouch['SECGRNO'] != $check_grpcode)
								{ ?>
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th> 
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2">DGM Status</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">GM Status</th>
							<th rowspan="2">GM Comments</th>
							<th rowspan="2">Entry User Comments</th>
							<th rowspan="2"><input type='button' name='save_frm' id='save_frm' value='SAVE' title="SAVE" class="btn btn-success" onclick="entryapproval();" style="background: #00a65a !important; color:white;"><br></th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
								<tr>
									<td style="line-height: 35px !important; height: 20px !important; background-color: #326296; color: #FFFFFF; font-size: 16px; font-weight: bold;" colspan="19"> SECTION GROUP: <?php echo $prtouch['SECGRNO']?> - <?php  echo $prtouch['GRPNAME']; ?></td>
								</tr>
						 <?php  } 
						$check_grpcode = $prtouch['SECGRNO']; ?>							
								<tr>
									<td class="linehgh30px myCell"><?php echo $i;?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['SUPCODE'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['SUPNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['CTYNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDCODE'];?>
									<input type="hidden" name="entry_prdcode" size="2" id="entry_prdcode_<?=$i?>" readonly value="<?=$prtouch['PRDCODE']?>"></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['PRDNAME'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php if($prtouch['WGTRANGE']=='ALL WGT RANGE'){ echo $prtouch['WGTRANGE']; } else { echo number_format($wgtrange[0],3).'-'.number_format($wgtrange[1],3);}?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PAYMODE'];?></td> 
									<td class="linehgh30px myCell"><?php echo number_format($prtouch['NTOTCALC'],2);?></td> 
									<td class="linehgh30px myCell"><?php  if($sql_touch_mc[0]['ENTSRNO'] == '') { ?> <font color="red"><?php  echo "No MC"; } else { ?></font><a href="#MC" onclick="mc_det('<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>')"><?php echo "MC";?><a/><?php } ?></td>
									<!--<td class="linehgh30px myCell"><?php if($prtouch['OPTMKCH'] == 'G') { echo "GRAM"; }
																		 elseif($prtouch['OPTMKCH'] == 'K') { echo "KG"; }
																		 elseif($prtouch['OPTMKCH'] == 'Q') { echo "QTY"; }?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['FR_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['TO_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDMKCH'];?></td>--> 
									<td class="linehgh30px myCell"><?php if($sql_compariosion[0]['PRDCODE'] == '') {?> <font color="red"><?php echo "No Comparision"; } else {?></font><a href="#Comparision" onclick="comparision_det('<?php echo $prtouch['PRDCODE'];?>')"><?php echo "Comparision";?><?php } ?><a/></td> 
									<td class="linehgh30px myCell"><?php if($prtouch['PM_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['PM_STATUS'] == 'R') {?><font color="red"><?php echo "REJECTED";?></font><?php } elseif($prtouch['PM_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }elseif($prtouch['PM_STATUS'] == 'N'){ echo "WAITING";}?></font></td>
									<td class="linehgh30px myCell"><?php if($prtouch['PMREASON']==''){ echo "WAITING";}else{ echo $prtouch['PMREASON']; }?></td>
									<td class="linehgh30px myCell"><?php if($prtouch['HOD_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['HOD_STATUS'] == 'R') {?><font color="red"><?php echo "REJECTED";?></font><?php } elseif($prtouch['HOD_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }elseif($prtouch['HOD_STATUS'] == 'N'){ echo "WAITING";}?></font></td>
							        <td class="linehgh30px myCell"><?php if($prtouch['HODREASON']==''){ echo "WAITING";}else{ echo $prtouch['HODREASON']; }?></td><td class="linehgh30px myCell"><?php if($prtouch['GM_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['GM_STATUS'] == 'R') {?><font color="red"><?php echo "REJECTED";?></font><?php } elseif($prtouch['GM_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }elseif($prtouch['GM_STATUS'] == 'N'){ echo "WAITING";}?></font></td> 											
									<td class="linehgh30px myCell">
									<?php if($prtouch['GMREASON']==''){ echo "WAITING";}else{ echo $prtouch['GMREASON']; }?>
									</td>
									<td class="linehgh30px myCell"><?php if($prtouch['PM_STATUS'] == 'P') {?>
									<textarea name="entry_comments" style='text-transform:uppercase;' placeholder="Enter Comments" size="5" maxlength="10" id="entry_comments_<?=$i?>" style="width:120px; height:10px;" value="OK"></textarea>
									<?php  }else{?>
									<input type="hidden"     name="entry_comments[]"  id="entry_comments_<?=$i?>" value="" >
									<?}// else {?> <font color="red"> <?php //echo $prtouch['PMREASON']; } ?></font></td>
									<td align="center" class="linehgh30px myCell" style="vertical-align:middle;"><?php if($prtouch['PM_STATUS'] == 'P') {?>
									<small class="label label-warning" style="background-color:#3c8dbc;">
										<input type="checkbox" class="ch" title='SELECT' name="entry_approval[]" onClick="entryapproval1()" id="entry_approval_<?=$i?>" 
										value="<?=$prtouch['ENTYEAR']."/".$prtouch['ENTNUMB']."/".$prtouch['ENTSRNO']?>" >
										<label for="entry_approval_<?=$i?>">SELECT</label>
									</small>
									<!--<input type='button' name='save_frm' id='save_frm' onclick="save_approval('<?=$i?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>');window.location.reload();" style="background: #00a65a !important; color:white;" class='btn btn-primary' value='SAVE' >-->
									<?php  }else{?>
										<input type="hidden" class="ch" title='SELECT' name="entry_approval[]" onClick="entryapproval1()" id="entry_approval_<?=$i?>" 
										value="<?=$prtouch['ENTYEAR']."/".$prtouch['ENTNUMB']."/".$prtouch['ENTSRNO']?>" >
										
									<?} //else {?> <!--<font color="red"> <?php //echo 'REJECTED'; } ?></font>-->
									</td>
								</tr>
					  <?php }?>
							</tbody>
						</table>
						<input type="hidden" id="entry_param" value="">
						<input type="hidden" id="entry_comments_param" value="">
						<input type="hidden" id="entry_prdcode_param" value="">
						</div>
					</div>		
				</div>
			</section>
			<!--<div id="myModal1" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
					   <div class="modal-body" id="modal-body1"></div>
					</div>
				</div>
			</div>-->
		</div>		
		<div class="tab-pane" id="tab_2">
			<section class="content-header">
				<h1 style='text-transform:uppercase; padding:0px 0 0 0px'>
				  SUPPLIER TOUCH ADD PENDING PM APPROVAL
				</h1>
				<div class="box-header ui-sortable-handle">
				  <a class="btn-sm" style="color:#FFFFFF;"><h3 class="box-title">Supplier Touch Add Pending PM Approval </h3></a>
				  <!--<div class="pull-right box-tools">
					  <button class="btn btn-block btn-primary" onclick="window.location.reload()" style="padding: 5px 10px;"><i class="fa fa-repeat"></i></button>
				  </div>-->
				</div><!-- /.box-header -->  
				<div class="tab-pane" id="tab_2" style="display:block; padding:5px; border: 1px solid #e0e0e0;min-height: 350px;margin:0 0 px;">
					<div class="box-body table-responsive" style="display: block;">
					<div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id=scrollme> 
						<table id="supplier_touch_request" class="table table-bordered table-striped table-hover controller" style="border-collapse:collapse; overflow: hidden;">
							<!--<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">PM Approval</th>
							<th rowspan="2">PM Touch Edit</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2">Action</th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>-->  
							<tbody> 
						  <?php
							$sql_touch = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.WGTRANGE EUCOM,R.HOD_STATUS,R.HOD_TOTCALC,R.HODREASON,R.PMREASON,Decode(Allrange,'Y','ALL WGT RANGE',WGTRANGE)  Wgtrange,Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode and R.Deleted='N' and (PM_STATUS in ('N') or HOD_STATUS in ('P'))  And Allrange='Y'
							union
							Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.WGTRANGE EUCOM,R.HOD_STATUS,R.HOD_TOTCALC,R.HODREASON,R.PMREASON,Min(E.FWEIGHT)||'-'||max(E.TWEIGHT)  Wgtrange,R.Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S,section_weight E
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode And E.seccode=P.seccode And R.WGTSRNO=E.WGTSRNO and R.Deleted='N' and (PM_STATUS in ('N') or HOD_STATUS in ('P')) And Allrange='N'
							Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.WGTRANGE,R.HOD_STATUS,R.HOD_TOTCALC,R.HODREASON,R.PMREASON,R.Ntotcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) order by SECGRNO");
							   
							$i=0;
                            $check_grpcode = '';							
							foreach($sql_touch as $prtouch) 
							{ $i++; 
								$sql_compariosion = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supp_purchase_profile R,Supplier S,City C,Product P
								Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode and R.Deleted='N'  and P.Prdcode='".$prtouch['PRDCODE']."' 
								Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE'))");
								$sql_touch_mc = select_ktmquery("select M.ENTYEAR,M.ENTNUMB,M.ENTSRNO,M.SUPCODE,M.MAKSRNO,P.PRDCODE,P.Prdname,M.FR_PRDWGHT,M.TO_PRDWGHT,M.OPTMKCH,M.PRDMKCH from supplier_touch_mc M,Product P where  M.prdcode=P.prdcode And M.ENTYEAR='".$prtouch['ENTYEAR']."' and M.ENTNUMB=".$prtouch['ENTNUMB']." and M.ENTSRNO='".$prtouch['ENTSRNO']."' order by M.MAKSRNO");
								 //echo $sql_compariosion[0]['PRDCODE'];
								 $wgtrange=explode("-",$prtouch['WGTRANGE']);
							?>	
                            <?php if($prtouch['SECGRNO'] != $check_grpcode)
								{ ?>
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">DGM Status</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2">Entry User Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">PM Approval</th>
							<th rowspan="2">PM Touch Edit</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2"><input type='button' name='save_frm' id='save_frm' value='SAVE' title="SAVE" class="btn btn-success" onclick="pmapproval();" style="background: #00a65a !important; color:white;"><br></th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
								<tr>
									<td style="line-height: 35px !important; height: 20px !important; background-color: #326296; color: #FFFFFF; font-size: 16px; font-weight: bold;" colspan="20"> SECTION GROUP: <?php echo $prtouch['SECGRNO']?> - <?php  echo $prtouch['GRPNAME']; ?></td>
								</tr>
						 <?php  } 
						 $check_grpcode = $prtouch['SECGRNO']; ?>							
								<tr id="pmstatus_<?php echo $prtouch['ENTYEAR']."_".$prtouch['ENTNUMB']."_".$prtouch['ENTSRNO']; ?>">
									<td class="linehgh30px myCell"><?php echo $i;?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['SUPCODE'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['SUPNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['CTYNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDCODE'];?>
									<input type="hidden" name="pm_prdcode" size="2" id="pm_prdcode_<?=$i?>" readonly value="<?=$prtouch['PRDCODE']?>"></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['PRDNAME'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php if($prtouch['WGTRANGE']=='ALL WGT RANGE'){ echo $prtouch['WGTRANGE']; } else { echo number_format($wgtrange[0],3).'-'.number_format($wgtrange[1],3);}?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PAYMODE'];?></td> 
									<td class="linehgh30px myCell"><?php echo number_format($prtouch['NTOTCALC'],2);?></td>
									<td class="linehgh30px myCell"><?php  if($sql_touch_mc[0]['ENTNUMB'] == '') { ?> <font color="red"><?php echo "No MC"; } else {?></font><a href="#MC" onclick="mc_det('<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>')"><?php echo "MC";?><a/><?php } ?></td>
									<!--<td class="linehgh30px myCell"><?php if($prtouch['OPTMKCH'] == 'G') { echo "GRAM"; }
																		 elseif($prtouch['OPTMKCH'] == 'K') { echo "KG"; }
																		 elseif($prtouch['OPTMKCH'] == 'Q') { echo "QTY"; }?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['FR_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['TO_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDMKCH'];?></td>-->
									<td class="linehgh30px myCell"><?php if($sql_compariosion[0]['PRDCODE'] == '') {?> <font color="red"><?php echo "No Comparision"; } else {?></font><a href="#Comparision" onclick="comparision_det('<?php echo $prtouch['PRDCODE'];?>')"><?php echo "Comparision";?><?php } ?><a/></td> 
									<!--<td class="linehgh30px myCell"><a href="#Profit" onclick="profit_det('<?php echo $prtouch['PRDCODE'];?>')"><?php echo "Profit";?><a/></td>-->
									<td class="linehgh30px myCell"><?php if($prtouch['HOD_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['HOD_STATUS'] == 'R') {?></font><font color="red"><?php echo "REJECTED";} elseif($prtouch['HOD_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }else{ echo "WAITING";}?></font></td>
									<td class="linehgh30px myCell"><?php if($prtouch['HODREASON']==''){ echo "WAITING";}else{ echo $prtouch['HODREASON']; }?></td>
									<td class="linehgh30px myCell">
									<?php  /* if($prtouch['PM_STATUS']=='P'){ */ echo $prtouch['EUCOM'];/* }else{ echo " - ";  } */?>
									</td>
									<td class="linehgh30px myCell">
										<select style='margin-left:0px; height: 30px; font-size: 10px;' class="form-control" required name='slt_approval' id='slt_approval_<?=$i?>'> 	
											<option <? if($prtouch['PM_STATUS'] == 'Y') { ?> selected <? } ?> value='Y'> Approved </option>
											<option <? if($prtouch['PM_STATUS'] == 'R') { ?> selected <? } ?> value='R'> Reject </option>
											<option <? if($prtouch['PM_STATUS'] == 'P') { ?> selected <? } ?> value='P'> Pending </option>
										</select>
										<!--<div id="comment_container"  <?if($_REQUEST['slt_approval'] == 'N') { ?>style="display:block;"<? }else{ ?> style="display:none;" <? } ?>>-->
										
										<!--</div>-->
									</td>
									<td class="linehgh30px myCell">
									<? if($prtouch['PM_STATUS'] == 'Y') { ?>
									<input type="text" name="pm_totcalc" class="number" maxlength="6" size="2" id="pm_totcalc_<?=$i?>" readonly value="<?=number_format($prtouch['PM_TOTCALC'],2);?>">
									<? }
									elseif($prtouch['HOD_STATUS'] == 'P')
									{ ?>
									<input type="text" name="pm_totcalc" class="number" maxlength="6" size="2" id="pm_totcalc_<?=$i?>" value="<?=number_format($prtouch['HOD_TOTCALC'],2);?>">
									<? }
									else
									{ ?>
									<input type="text" name="pm_totcalc" class="number" maxlength="6" size="2" id="pm_totcalc_<?=$i?>" value="<?=number_format($prtouch['NTOTCALC'],2);?>">
									<? } ?>
									</td>
									<td class="linehgh30px myCell"><?php //if($prtouch['PMREASON'] == '') {?>
									<textarea name="pm_comments" style='text-transform:uppercase;' placeholder="Enter Comments" size="5" maxlength="50" id="pm_comments_<?=$i?>" style="width:120px; height:10px;" value="OK">OK</textarea>
									<?php  //} else {?> <font color="red"> <?php //echo $prtouch['PMREASON']; } ?></font></td>
									<td class="linehgh30px myCell">
									<small class="label label-warning" style="background-color:#3c8dbc;">
										<input type="checkbox" class="ch" title='SELECT' name="pm_approval[]" onClick="pmapproval1()" id="pm_approval_<?=$i?>" 
										value="<?=$prtouch['ENTYEAR']."/".$prtouch['ENTNUMB']."/".$prtouch['ENTSRNO']?>" >
										<label for="pm_approval_<?=$i?>">SELECT</label>
									</small>
									<?php //if($prtouch['PMREASON'] == '') {?>
									<!--<input type='button' name='save_frm' id='save_frm' onclick="save_approval('<?=$i?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>');window.location.reload();" style="background: #00a65a !important; color:white;" class='btn btn-primary' value='SAVE' >
									<?php  //} else {?> <font color="red"> <?php //echo 'REJECTED'; } ?></font>-->
									</td>
								</tr>
					  <?php }?>
							</tbody>
						</table>
						<input type="hidden" id="pm_param" value="">
						<input type="hidden" id="slt_approval_param" value="">
						<input type="hidden" id="pm_comments_param" value="">
						<input type="hidden" id="pm_totcalc_param" value="">
						<input type="hidden" id="pm_prdcode_param" value="">
						</div>	
					</div>		
				</div>
			</section>
			<!--<div id="myModal1" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
					   <div class="modal-body" id="modal-body1"></div>
					</div>
				</div>
			</div>-->
		</div>
		<div class="tab-pane" id="tab_3">
			<section class="content-header">
				<h1 style='text-transform:uppercase; padding:0px 0 0 0px'>
				  SUPPLIER TOUCH ADD PENDING DGM APPROVAL
				</h1>
				<div class="box-header ui-sortable-handle">
				  <a class="btn-sm" style="color:#FFFFFF;"><h3 class="box-title">Supplier Touch Add Pending DGM Approval </h3></a>
				  <!--<div class="pull-right box-tools">
					  <button class="btn btn-block btn-primary" onclick="window.location.reload()" style="padding: 5px 10px;"><i class="fa fa-repeat"></i></button>
				  </div>-->
				</div><!-- /.box-header -->  
				<div class="tab-pane" id="tab_3" style="display:block; padding:5px; border: 1px solid #e0e0e0;min-height: 350px;margin:0 0 px;">
					<div class="box-body table-responsive" style="display: block;">
					<div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id=scrollme> 
						<table id="supplier_touch_request" class="table table-bordered table-striped table-hover controller" style="border-collapse:collapse; overflow: hidden;">
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">Profit</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">DGM Approval</th>
							<th rowspan="2">DGM Touch Edit</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2">Action</th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
							<tbody> 
						  <?php
							$sql_touch = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.GM_STATUS,R.HODREASON,R.PMREASON,R.GMREASON,R.PM_STATUS,R.HOD_STATUS,R.PM_TOTCALC,R.GM_TOTCALC,Decode(Allrange,'Y','ALL WGT RANGE',WGTRANGE)  Wgtrange,Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode and R.Deleted='N' and R.PM_STATUS in ('Y') and (R.HOD_STATUS in ('N') or R.GM_STATUS in ('P'))   And Allrange='Y'
							union
							Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.GM_STATUS,R.HODREASON,R.PMREASON,R.GMREASON,R.PM_STATUS,R.HOD_STATUS,R.PM_TOTCALC,R.GM_TOTCALC,Min(E.FWEIGHT)||'-'||max(E.TWEIGHT)  Wgtrange,R.Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S,section_weight E
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode And E.seccode=P.seccode And R.WGTSRNO=E.WGTSRNO and R.Deleted='N' and PM_STATUS in ('Y') and (R.HOD_STATUS in ('N') or R.GM_STATUS in ('P'))  And Allrange='N'
							Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.GM_STATUS,R.HODREASON,R.PMREASON,R.GMREASON,R.PM_STATUS,R.HOD_STATUS,R.PM_TOTCALC,R.GM_TOTCALC,R.Ntotcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) order by SECGRNO");
							   
							$i=0;
                            $check_grpcode = '';							
							foreach($sql_touch as $prtouch) 
							{ $i++; 
								$sql_compariosion = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supp_purchase_profile R,Supplier S,City C,Product P
								Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode and R.Deleted='N'  and P.Prdcode='".$prtouch['PRDCODE']."'
								Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE'))");
								$sql_touch_mc = select_ktmquery("select M.ENTYEAR,M.ENTNUMB,M.ENTSRNO,M.SUPCODE,M.MAKSRNO,P.PRDCODE,P.Prdname,M.FR_PRDWGHT,M.TO_PRDWGHT,M.OPTMKCH,M.PRDMKCH from supplier_touch_mc M,Product P where  M.prdcode=P.prdcode And M.ENTYEAR='".$prtouch['ENTYEAR']."' and M.ENTNUMB=".$prtouch['ENTNUMB']." and M.ENTSRNO='".$prtouch['ENTSRNO']."' order by M.MAKSRNO");
								 //echo $sql_compariosion[0]['PRDCODE'];
								 $wgtrange=explode("-",$prtouch['WGTRANGE']);
							?>	
                            <?php if($prtouch['SECGRNO'] != $check_grpcode)
								{ ?>
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">Profit</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">GM Status</th>
							<th rowspan="2">GM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">DGM Approval</th>
							<th rowspan="2">DGM Touch Edit</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2"><input type='button' name='save_frm' id='save_frm' value='SAVE' title="SAVE" class="btn btn-success" onclick="dgmapproval();" style="background: #00a65a !important; color:white;"><br></th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
								<tr>
									<td style="line-height: 35px !important; height: 20px !important; background-color: #326296; color: #FFFFFF; font-size: 16px; font-weight: bold;" colspan="20"> SECTION GROUP: <?php echo $prtouch['SECGRNO']?> - <?php  echo $prtouch['GRPNAME']; ?></td>
								</tr>
						 <?php  } 
						 $check_grpcode = $prtouch['SECGRNO']; ?>							
								<tr id="dgmstatus_<?php echo $prtouch['ENTYEAR']."_".$prtouch['ENTNUMB']."_".$prtouch['ENTSRNO']; ?>">
									<td class="linehgh30px myCell"><?php echo $i;?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['SUPCODE'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['SUPNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['CTYNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDCODE'];?>
									<input type="hidden" name="dgm_prdcode" size="2" id="dgm_prdcode_<?=$i?>" readonly value="<?=$prtouch['PRDCODE']?>"></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['PRDNAME'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php if($prtouch['WGTRANGE']=='ALL WGT RANGE'){ echo $prtouch['WGTRANGE']; } else { echo number_format($wgtrange[0],3).'-'.number_format($wgtrange[1],3);}?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PAYMODE'];?></td> 
									<td class="linehgh30px myCell"><?php echo number_format($prtouch['NTOTCALC'],2);?></td>
									<td class="linehgh30px myCell"><?php  if($sql_touch_mc[0]['ENTSRNO'] == '') { ?> <font color="red"><?php  echo "No MC"; } else { ?></font><a href="#MC" onclick="mc_det('<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>')"><?php echo "MC";?><a/><?php } ?></td>
									<!--<td class="linehgh30px myCell"><?php if($prtouch['OPTMKCH'] == 'G') { echo "GRAM"; }
																		 elseif($prtouch['OPTMKCH'] == 'K') { echo "KG"; }
																		 elseif($prtouch['OPTMKCH'] == 'Q') { echo "QTY"; }?></td>
									<td class="linehgh30px myCell"><?php echo $prtouch['FR_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['TO_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDMKCH'];?></td>-->											
									<td class="linehgh30px myCell"><?php if($sql_compariosion[0]['PRDCODE'] == '') {?> <font color="red"><?php echo "No Comparision"; } else {?></font><a href="#Comparision" onclick="comparision_det('<?php echo $prtouch['PRDCODE'];?>')"><?php echo "Comparision";?><?php } ?><a/></td> 
									<td class="linehgh30px myCell"><a href="#Profit" onclick="profit_det('<?php echo $prtouch['PRDCODE'];?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>')"><?php echo "Profit";?><a/></td>
									<td class="linehgh30px myCell"><?php if($prtouch['PM_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['PM_STATUS'] == 'R') {?></font><font color="red"><?php echo "REJECTED";} elseif($prtouch['PM_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }else{ echo "WAITING";}?></font></td>
									<td class="linehgh30px myCell"><?php if($prtouch['PMREASON']==''){ echo "WAITING";}else{ echo $prtouch['PMREASON']; }?></td>
                                    <td class="linehgh30px myCell"><?php if($prtouch['GM_STATUS'] == 'Y') {?><font color="green"><?php echo "Approved"; } elseif($prtouch['GM_STATUS'] == 'R') {?></font><font color="red"><?php echo "REJECTED";} elseif($prtouch['GM_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }else{ echo "WAITING";}?></font></td>
                                    <td class="linehgh30px myCell"><?php if($prtouch['GMREASON']==''){ echo "WAITING";}else{ echo $prtouch['GMREASON']; }?></td>									
									<td class="linehgh30px myCell">
										<select style='margin-left:0px; height: 30px; font-size: 10px;' class="form-control" required name='dgm_slt_approval' id='dgm_slt_approval_<?=$i?>'> 	
											<option <? if($prtouch['HOD_STATUS'] == 'Y') { ?> selected <? } ?> value='Y'> Approved </option>
											<option <? if($prtouch['HOD_STATUS'] == 'R') { ?> selected <? } ?> value='R'> Reject </option>
											<option <? if($prtouch['HOD_STATUS'] == 'P') { ?> selected <? } ?> value='P'> Pending </option>
										</select>
										<!--<div id="comment_container"  <?if($_REQUEST['slt_approval'] == 'N') { ?>style="display:block;"<? }else{ ?> style="display:none;" <? } ?>>-->
										
										<!--</div>-->
									</td>
									<td class="linehgh30px myCell">
									<? if($prtouch['PM_STATUS'] == 'N') { ?>
									<input type="text" name="hod_totcalc" size="2" maxlength="6" class="number" id="hod_totcalc_<?=$i?>" value="<?=number_format($prtouch['NTOTCALC'],2);?>">
									<? }
									elseif($prtouch['PM_STATUS'] == 'Y' AND $prtouch['GM_STATUS'] == 'P')
									{ ?>
									<input type="text" name="hod_totcalc" size="2" maxlength="6" class="number" id="hod_totcalc_<?=$i?>" value="<?=number_format($prtouch['GM_TOTCALC'],2);?>">
									<? }
									elseif($prtouch['HOD_STATUS'] == 'N' AND $prtouch['GM_STATUS'] == 'N' )
									{ ?>
									<input type="text" name="hod_totcalc" size="2" maxlength="6" class="number" id="hod_totcalc_<?=$i?>" value="<?=number_format($prtouch['PM_TOTCALC'],2);?>">
									<? } ?>
									</td>
									<td class="linehgh30px myCell">
									<?php //if($prtouch['HODREASON'] == '') {?>
									<textarea name="hod_comments" style='text-transform:uppercase;' placeholder="Enter Comments" size="5" maxlength="50" id="hod_comments_<?=$i?>" style="width:120px; height:10px;" value="OK">OK</textarea>
									<?php  //} else {?> <font color="red"> <?php //echo $prtouch['HODREASON']; } ?></font>
									</td>
									<td class="linehgh30px myCell">
									<small class="label label-warning" style="background-color:#3c8dbc;">
										<input type="checkbox" class="ch" title='SELECT' name="dgm_approval[]" onClick="dgmapproval1()" id="dgm_approval_<?=$i?>" 
										value="<?=$prtouch['ENTYEAR']."/".$prtouch['ENTNUMB']."/".$prtouch['ENTSRNO']?>" >
										<label for="dgm_approval_<?=$i?>">SELECT</label>
									</small>
									<?php //if($prtouch['HODREASON'] == '') {?>
									<!--<input type='button' name='save_frm' id='save_frm' onclick="save_dgmapproval('<?=$i?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>');window.location.reload();" style="background: #00a65a !important; color:white;" class='btn btn-primary' value='SAVE' >
									<?php  //} else {?> <font color="red"> <?php //echo 'REJECTED'; } ?></font>-->
									</td>
								</tr>
					  <?php }?>
							</tbody>
						</table>
						<input type="hidden" id="dgm_param" value="">
						<input type="hidden" id="dgm_slt_approval_param" value="">
						<input type="hidden" id="hod_comments_param" value="">
						<input type="hidden" id="hod_totcalc_param" value="">
						<input type="hidden" id="dgm_prdcode_param" value="">
					</div>
					</div>		
				</div>
			</section>
			<!--<div id="myModal1" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
					   <div class="modal-body" id="modal-body1"></div>
					</div>
				</div>
			</div>-->
		</div>
		<div class="tab-pane" id="tab_4">
			<section class="content-header">
				<h1 style='text-transform:uppercase; padding:0px 0 0 0px'>
				  SUPPLIER TOUCH ADD PENDING GM APPROVAL
				</h1>
				<div class="box-header ui-sortable-handle">
				  <a class="btn-sm" style="color:#FFFFFF;"><h3 class="box-title">Supplier Touch Add Pending GM Approval </h3></a>
				  <!--<div class="pull-right box-tools">
					  <button class="btn btn-block btn-primary" onclick="window.location.reload()" style="padding: 5px 10px;"><i class="fa fa-repeat"></i></button>
				  </div>-->
				</div><!-- /.box-header -->  
				<div class="tab-pane" id="tab_4" style="display:block; padding:5px; border: 1px solid #e0e0e0;min-height: 350px;margin:0 0 px;">
					<div class="box-body table-responsive" style="display: block;">
					<div onscroll='scroller("scrollme", "scroller")' style="overflow:scroll; height:650px" id=scrollme> 
						<table id="example0" class="table table-bordered table-striped table-hover controller" style="border-collapse:collapse; overflow: hidden;">
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">Profit</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2">DGM Status</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">GM Approval</th>
							<th rowspan="2">GM Touch Edit</th>
							<th rowspan="2">GM Comments</th>
							<th rowspan="2">Action</th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
							<tbody> 
						  <?php
							$sql_touch = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.HOD_TOTCALC,Decode(Allrange,'Y','ALL WGT RANGE',WGTRANGE)  Wgtrange,Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode and R.Deleted='N' and HOD_STATUS in ('Y') and GM_STATUS in ('N') And Allrange='Y'
							union
							Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.HOD_TOTCALC,Min(E.FWEIGHT)||'-'||max(E.TWEIGHT)  Wgtrange,R.Ntotcalc,
							Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supplier_Touch_Add_Request R,Supplier S,City C,Product P, Section_Group G, Section S,section_weight E
							Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode And G.SECGRNO=S.SECGRNO And P.Seccode=S.Seccode And E.seccode=P.seccode And R.WGTSRNO=E.WGTSRNO and R.Deleted='N' and HOD_STATUS in ('Y') and GM_STATUS in ('N') And Allrange='N'
							Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,S.Seccode,G.SECGRNO,S.Secname,G.Grpname,R.entyear,R.entnumb,R.ENTSRNO,R.HODREASON,R.PMREASON,R.GMREASON,R.HOD_STATUS,R.PM_STATUS,R.HOD_TOTCALC,R.Ntotcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) order by SECGRNO");
							   
							$i=0;
                            $check_grpcode = '';								
							foreach($sql_touch as $prtouch) 
							{ $i++; 
								$sql_compariosion = select_ktmquery("Select S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE')) Paymode  From Supp_purchase_profile R,Supplier S,City C,Product P
								Where R.Supcode=S.Supcode And S.Ctycode=C.Ctycode And R.prdcode=P.prdcode and R.Deleted='N'  and P.Prdcode='".$prtouch['PRDCODE']."'
								Group by S.Supcode,S.Supname,C.Ctyname,P.Prdcode,P.Prdname,R.Totcalc,Decode(R.Paymode,'R','REGULAR',Decode(R.paymode,'S','SPOT','ADVANCE'))");
								$sql_touch_mc = select_ktmquery("select M.ENTYEAR,M.ENTNUMB,M.ENTSRNO,M.SUPCODE,M.MAKSRNO,P.PRDCODE,P.Prdname,M.FR_PRDWGHT,M.TO_PRDWGHT,M.OPTMKCH,M.PRDMKCH from supplier_touch_mc M,Product P where  M.prdcode=P.prdcode And M.ENTYEAR='".$prtouch['ENTYEAR']."' and M.ENTNUMB=".$prtouch['ENTNUMB']." and M.ENTSRNO='".$prtouch['ENTSRNO']."' order by M.MAKSRNO");
								 //echo $sql_compariosion[0]['PRDCODE'];
								 $wgtrange=explode("-",$prtouch['WGTRANGE']);
							?>
                            <?php if($prtouch['SECGRNO'] != $check_grpcode)
								{ ?>
							<thead>
							<tr>
							<th rowspan="2">#</th>
							<th colspan="3">Supplier</th>
							<th colspan="2">Product</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Weight Range</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">Pay Type</th>
							<th rowspan="2">Touch</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">MC</th>
							<th rowspan="2">Comparision</th>
							<th rowspan="2">Profit</th>
							<th rowspan="2">PM Status</th>
							<th rowspan="2">PM Comments</th>
							<th rowspan="2">DGM Status</th>
							<th rowspan="2">DGM Comments</th>
							<th rowspan="2" style="text-align: left; white-space: nowrap;">GM Approval</th>
							<th rowspan="2">GM Touch Edit</th>
							<th rowspan="2">GM Comments</th>
							<th rowspan="2"><input type='button' name='save_frm' id='save_frm' value='SAVE' title="SAVE" class="btn btn-success" onclick="gmapproval();" style="background: #00a65a !important; color:white;"><br></th>
							</tr>
							<tr>
							  <th>Code</th>
							  <th>Name</th>
							  <th>City</th>
							  <th>Code</th>
							  <th>Name</th>
							</tr>
							</thead>
								<tr>
									<td style="line-height: 35px !important; height: 20px !important; background-color: #326296; color: #FFFFFF; font-size: 16px; font-weight: bold;" colspan="20"> SECTION GROUP: <?php echo $prtouch['SECGRNO']?> - <?php  echo $prtouch['GRPNAME']; ?></td>
								</tr>
						 <?php  } 
						 $check_grpcode = $prtouch['SECGRNO']; ?>							
								<tr id="gmstatus_<?php echo $prtouch['ENTYEAR']."_".$prtouch['ENTNUMB']."_".$prtouch['ENTSRNO']; ?>">
									<td class="linehgh30px myCell"><?php echo $i;?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['SUPCODE'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['SUPNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['CTYNAME'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDCODE'];?>
									<input type="hidden" name="gm_prdcode" size="2" id="gm_prdcode_<?=$i?>" readonly value="<?=$prtouch['PRDCODE']?>"></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php echo $prtouch['PRDNAME'];?></td> 
									<td class="linehgh30px myCell" style="text-align: left; white-space: nowrap;"><?php if($prtouch['WGTRANGE']=='ALL WGT RANGE'){ echo $prtouch['WGTRANGE']; } else { echo number_format($wgtrange[0],3).'-'.number_format($wgtrange[1],3);}?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PAYMODE'];?></td> 
									<td class="linehgh30px myCell"><?php echo number_format($prtouch['NTOTCALC'],2);?></td> 
									<td class="linehgh30px myCell"><?php  if($sql_touch_mc[0]['ENTSRNO'] == '') { ?> <font color="red"><?php  echo "No MC"; } else { ?></font><a href="#MC" onclick="mc_det('<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>')"><?php echo "MC";?><a/><?php } ?></td>
									<!--<td class="linehgh30px myCell"><?php if($prtouch['OPTMKCH'] == 'G') { echo "GRAM"; }
																		 elseif($prtouch['OPTMKCH'] == 'K') { echo "KG"; }
																		 elseif($prtouch['OPTMKCH'] == 'Q') { echo "QTY"; }?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['FR_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['TO_PRDWGHT'];?></td> 
									<td class="linehgh30px myCell"><?php echo $prtouch['PRDMKCH'];?></td>--> 
									<td class="linehgh30px myCell"><?php if($sql_compariosion[0]['PRDCODE'] == '') {?> <font color="red"><?php echo "No Comparision"; } else {?></font><a href="#Comparision" onclick="comparision_det('<?php echo $prtouch['PRDCODE'];?>')"><?php echo "Comparision";?><?php } ?><a/></td> 
									<td class="linehgh30px myCell"><a href="#Profit" onclick="profit_det('<?php echo $prtouch['PRDCODE'];?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>')"><?php echo "Profit";?><a/></td>
									<td class="linehgh30px myCell"><?php if($prtouch['PM_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['PM_STATUS'] == 'R') {?></font><font color="red"><?php echo "REJECTED";} elseif($prtouch['PM_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }else{ echo "WAITING";}?></font></td>
									<td class="linehgh30px myCell"><?php if($prtouch['PMREASON']==''){ echo "WAITING";}else{ echo $prtouch['PMREASON']; }?></td>
									<td class="linehgh30px myCell"><?php if($prtouch['HOD_STATUS'] == 'Y') {?><font color="green"><?php echo "APPROVED"; } elseif($prtouch['HOD_STATUS'] == 'R') {?></font><font color="red"><?php echo "REJECTED";} elseif($prtouch['HOD_STATUS'] == 'P') { ?><font color="blue"><?php echo "PENDING"; }else{ echo "WAITING";}?></font></td>
									<td class="linehgh30px myCell"><?php if($prtouch['HODREASON']==''){ echo "WAITING";}else{ echo $prtouch['HODREASON']; }?></td> 											
									<td class="linehgh30px myCell">
										<select style='margin-left:0px; height: 30px; font-size: 10px;' class="form-control" required name='gm_slt_approval' id='gm_slt_approval_<?=$i?>'> 	
											<option <? if($prtouch['GM_STATUS'] == 'Y') { ?> selected <? } ?> value='Y'> Approved </option>
											<option <? if($prtouch['GM_STATUS'] == 'R') { ?> selected <? } ?> value='R'> Reject </option>
											<option <? if($prtouch['GM_STATUS'] == 'P') { ?> selected <? } ?> value='P'> Pending </option>
										</select>
										<!--<div id="comment_container"  <?if($_REQUEST['slt_approval'] == 'N') { ?>style="display:block;"<? }else{ ?> style="display:none;" <? } ?>>-->
										
										<!--</div>-->
									</td>
									<td class="linehgh30px myCell">
									<? if($prtouch['HOD_STATUS'] == 'Y' OR $prtouch['HOD_STATUS'] == 'P') { ?>
									<input type="text" name="gm_totcalc" size="2" maxlength="6" class="number" id="gm_totcalc_<?=$i?>" value="<?=number_format($prtouch['HOD_TOTCALC'],2);?>">
									<? }
									else
									{ ?>
									<input type="text" name="gm_totcalc" size="2" maxlength="6" class="number" id="gm_totcalc_<?=$i?>" value="<?=number_format($prtouch['NTOTCALC'],2)?>">
									<? } ?>
									</td>
									<td class="linehgh30px myCell">
									<?php //if($prtouch['GMREASON'] == '') {?>
									<textarea name="gm_comments" style='text-transform:uppercase;' placeholder="Enter Comments" maxlength="50" size="5" id="gm_comments_<?=$i?>" style="width:120px; height:10px;" value="OK">OK</textarea>
									<?php  //} else {?> <font color="red"> <?php //echo $prtouch['GMREASON']; } ?></font>
									</td>
									<td class="linehgh30px myCell">
									<small class="label label-warning" style="background-color:#3c8dbc;">
										<input type="checkbox" class="ch" title='SELECT' name="gm_approval[]" onClick="gmapproval1()" id="gm_approval_<?=$i?>" 
										value="<?=$prtouch['ENTYEAR']."/".$prtouch['ENTNUMB']."/".$prtouch['ENTSRNO']?>" >
										<label for="gm_approval_<?=$i?>">SELECT</label>
									</small>
									<?php //if($prtouch['GMREASON'] == '') {?>
									<!--<input type='button' name='save_frm' id='save_frm' onclick="save_gmapproval('<?=$i?>', '<?=$prtouch['ENTYEAR']?>', '<?=$prtouch['ENTNUMB']?>', '<?=$prtouch['ENTSRNO']?>');window.location.reload();" style="background: #00a65a !important; color:white;" class='btn btn-primary' value='SAVE' >
									<?php  //} else {?> <font color="red"> <?php //echo 'REJECTED'; } ?></font>-->
									</td>
								</tr>
					  <?php }?>
							</tbody>
						</table>
						<input type="hidden" id="gm_param" value="">
						<input type="hidden" id="gm_slt_approval_param" value="">
						<input type="hidden" id="gm_comments_param" value="">
						<input type="hidden" id="gm_totcalc_param" value="">
						<input type="hidden" id="gm_prdcode_param" value="">
						</div>	

						</div>		
				</div>
			</section>
			<!--<div id="myModal1" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
					   <div class="modal-body" id="modal-body1"></div>
					</div>
				</div>
			</div>-->
		</div>	
		<div id="myModal1" class="modal fade">
			<div class="modal-dialog" style="width:1000px;">
				<div class="modal-content">
				   <div class="modal-body" id="modal-body1"></div>
				</div>
			</div>
		</div>				
	</div>
	</div>  
</div>
</div>  
	
	<div style='clear:both'></div>
    </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->
	<div style='clear:both'></div>
    <? include("includes/footer.php"); ?>
    </div><!-- ./wrapper -->
	<div style='clear:both'></div>

    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
	<link href="bootstrap/css/select2.css" rel="stylesheet"/>
	<script src="bootstrap/js/select2.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- DATA TABES SCRIPT -->
	<script src="plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="js/dataTables.tableTools.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
	<script src="dist/js/app.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="bootstrap/js/zebra_datepicker1.js"></script>
	<script src="js/scroll_to_top.js"></script> <!-- Gem jQuery Scroll to TOP -->

    <!-- page script -->
	<link rel="stylesheet" href="bootstrap/css/jquery-ui-1.10.3.custom.min.css" />
	<script src="bootstrap/js/jquery-ui-1.10.3.custom.min.js"></script>
	<? /* <script src="bootstrap/js/lightGallery.js"></script> */ ?>
	<script type="text/javascript" src="bootstrap/js/fresco.js"></script>
	<script src="js/fSelect.js"></script>
	<link href="css/fSelect.css" rel="stylesheet">

	<script type="text/javascript">
	/*function check()
	{
		alert("ok");
	}

   //$("#weight_range").select2();
  

   /* $("#weight_range_1").fSelect({selectOnClose: true}).on("fSelect:selecting", function(evt) 
	{
          if(evt.params.args.data.id == '0') {
            $("#weight_range_1").val(null).trigger("");
          } else {
            $('#weight_range_1 > option[value=0]').prop("selected", false);
          }
    }); */
	 $("#weight_range_1").select2({selectOnClose: true}).on("select2:selecting", function(evt) 
	{
          if(evt.params.args.data.id == '0') {
            $("#weight_range_1").val(null).trigger("change");
          } else {
            $('#weight_range_1 > option[value=0]').prop("selected", false);
          }
    });
	
	$(document).ready(function()
	{
		$('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
			sessionStorage.setItem('activeTab', $(e.target).attr('href'));
			
		});
		var activeTab = sessionStorage.getItem('activeTab');
		if(activeTab){
			$('#myTab a[href="' + activeTab + '"]').tab('show');
		}
	});
	
	function checkBlock(id) 
	{
		//alert('mohan');
		//var checkBox = document.getElementsByName('need_mc['+id+']');
		console.log("need_mc_"+id);
		console.log("text_"+id);
		
		var checkBox = document.getElementById("need_mc_"+id);
		var text = document.getElementById("text_"+id);
		console.log(text);
		console.log(checkBox);
		
		if (checkBox.checked == true){
			text.style.display = "block";
		} else {
		   text.style.display = "none";
		   document.getElementById('slt_mc_opt_'+id).value = '';
		   document.getElementById('fr_wgt_'+id).value = '';
		   document.getElementById('to_wgt_'+id).value = '';
		   document.getElementById('mak_charge').value = '';
		} 
	}
	function addrow(gridid) 
	{
		var supplier = $('#slt_supcode').val();
		var seccode = supplier.split("-");
		var product = $('#prdcode_1').val();
		//var prdname = $('#prdcode_1 option:selected').text();
		//prdname = $.trim(prdname);
		var prd=product.split("-");
		var section=$('#slt_prdgroup_1').val();
		group = section.split("-");
		var weight = $('#weight_range_1').val();
		var touch = $('#touch_1').val();
		var paymode = $('#pay_mode').val();
		var mc = $('#slt_mc_opt_1_1').val();
		var frwgt = $('#fr_wgt_1_1').val();
		var towgt = $('#to_wgt_1_1').val();
		var mckchg = $('#mak_charge_1_1').val();

	   //$("#addbtn").click(function () {
		
	   if( ($('.parts3 .part3').length+1) > 50)
		{
			alert("Only 50 rows allowed.");
			return false;	
		}
        

		var id = ($('.parts3 .part3').length + 2).toString();

		$('#partint3').val(id);
		 $('.parts3').append('<tr class="part5 part3">'+
	   '<td><input type="textbox" name="slt_supcode[]" class="form-control" id="supplier" readonly="" value='+seccode[0]+'></td>'+
						'<td><input type="textbox" class="form-control" id="supplier" readonly="" value='+seccode[1]+'></td>'+
						'<td><input type="textbox"  class="form-control" id="supplier" readonly="" value='+seccode[2]+'></td>'+
						'<td><input type="textbox" class="form-control" id="section" readonly="" value='+group[1]+'></td>'+
						'<td><input type="textbox" name="prdcode[]" class="form-control" id="product" readonly="" value='+prd[0]+'></td>'+
						'<td><input type="textbox"  class="form-control" id="product" readonly="" value='+prd[1]+'></td>'+
						'<td><input type="textbox" name="weight_range[]" class="form-control" id="weight" readonly="" value='+weight+'></td>'+
						'<td><input type="textbox" name="touch[]"  class="form-control"id="touch" readonly="" value='+touch+'></td>'+
						'<td><input type="textbox" name=pay_mode_save[]" class="form-control" id="paymode" readonly="" value='+paymode+'></td>'+
						'<td><input type="textbox" name="slt_mc_opt[]" id="mc" class="form-control" readonly="" value='+mc+'></td>'+
						'<td><input type="textbox" name="fr_wgt[]" class="form-control" id="frwgt" readonly="" value='+frwgt+'></td>'+
						'<td><input type="textbox" name="to_wgt[]" class="form-control" id="towgt" readonly="" value='+towgt+'></td>'+
						'<td><input type="textbox" name="mak_charge[]" class="form-control" id="mckchg" readonly="" value='+mckchg+'></td>'+
							  '<tr>');

		 return false;
	   }

$('#add').click(function(){
            $('#counter_master')[0].reset();
 });
    
 
	function call_product_innergrid1(gridid) 
	{
	   //$("#addbtn").click(function () {
		
	   if( ($('.parts3 .part3').length+1) > 50)
		{
			alert("Only 50 rows allowed.");
			return false;	
		}
        

		var id = ($('.parts3 .part3').length + 2).toString();

		$('#partint3').val(id);
		 $('.parts3').append('<div class="row part3" style="padding-top:10px;">'+
							  '<div class="col-md-12" style="text-transform:uppercase;padding-top: 20px;">'+
							 ' <label for="exampleInputPassword1">Supplier :</label><input type="text" class="form-control ui-autocomplete-input"  id="slt_supcode" name="slt_supcode"  placeholder="Supplier Code / Name"  data-original-title="Supplier Code / Name">'+
							 '</div>'+
							 '</div>'+
							 '<div class="col-md-12" style="text-transform:uppercase;padding-top: 20px;">'+
							 ' <label for="exampleInputPassword1">Section Group :</label><input type="text" class="form-control ui-autocomplete-input"  id="slt_prdgroup"   placeholder="Supplier Code / Name"  data-original-title="Supplier Code / Name">'+
							 '</div>'+
							 '</div>'+
							  '<div class="col-md-12" style="text-transform:uppercase;padding-top: 20px;">'+
							    '<div class="col-sm-6"><label for="exampleInputPassword1">Product : </label><input type="text" class="form-control" name="prdcode[]" id="prdcode_'+id+'" onchange="select_range('+id+')" placeholder=" Product Code / Name">'+
							   '</div>'+
							 '<div class="col-sm-6"><label for="exampleInputPassword1">Weight Range : <select input type="text"tabindex="3" name="weight_range['+id+'][]" id="weight_range_'+id+'"   multiple class="form-control custom-select" style="margin-left:0px; width:200px;"><option value="0">Select All Weight Range </option><?$prd_code=explode("-",$prdcode);$sql_branch = select_ktmquery("SELECT  WGTSRNO,FWEIGHT||'-'||TWEIGHT||'-'||MWEIGHT WGT from SECTION_WEIGHT where seccode='".$prd_code[2]."' order by wgtsrno");
								 foreach($sql_branch as $branchrow){ $wgt=explode("-",$branchrow['WGT']);?><option value="<?php echo $branchrow['WGTSRNO'];?>" <? if($_REQUEST['weight_range'] == $branchrow['WGTSRNO']) { ?>selected<? } ?>><?php echo number_format($wgt[0],3)." - ".number_format($wgt[1],3)." - ".number_format($wgt[2],3);?></option><? } ?></select>'+
							  '</div>'+
							  '</div>'+
							   '<div class="col-md-12" style="text-transform:uppercase;padding-top: 20px;">'+
							   '<div class="col-sm-6"><label for="exampleInputPassword1">Touch : <img src="images/star1.png" style="width:8px; height:8px; border:0px;" border="0"></label><input type="text" class="form-control ui-autocomplete-input number"  id="touch_'+id+'" name="touch[]" placeholder="Touch" data-original-title="Touch">'+
							 '</div>'+
							 '<div class="col-sm-6">&nbsp;<label for="exampleInputPassword1">Payment Mode : </label><br /><input type="radio" name="pay_mode['+id+']" id="pay_mode" value="REGULAR" title="R" checked=""> Regular&nbsp;'+
							     '<input type="radio" name="pay_mode['+id+']" id="pay_mode" value="S" title="Spot"> Spot&nbsp;'+
							     '<input type="radio" name="pay_mode['+id+']" id="pay_mode" value="A" title="Advance"> Advance'+
							  '</div>'+ 
							 '</div>'+
						'<div class="parts3_'+id+' fair_border">'+
				            '<div class="form-group" style="padding-left:55px;">'+
				        		  '<label for="exampleInputPassword1">MC : </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
				        		    '<input type="checkbox" name="need_mc_'+id+'" id="need_mc_'+id+'" value="M" title="MC" onchange="checkBlock('+id+')">'+
				           '</div>'+ 
						    '<div id="text_'+id+'" style="display:none">'+ 
								'<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
									'<div class="box-body">'+
									   '<div class="form-group">'+
										   '<div class="col-md-12" style="padding-top:20px;">'+
											   '<div class="row">'+
												  '<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
													   '<div class="col-sm-1 text-right" style="float: right;">'+
														   '<button id="removebtn_'+id+'" onclick="call_innergrid_remove('+id+')" style="background: #65261e !important; color:white;" class="btn btn-remove btn-danger" type="button" title="Delete Row"><span class="glyphicon glyphicon-minus"></span></button>'+
														'</div>'+
														'<div class="col-sm-1 text-right" style="float: right;">'+
															'<input type="hidden" name="partint3_'+id+'" id="partint_'+id+'" value="1">'+
															'<button class="btn btn-success btn-add3" id="addbtn_'+id+'" onclick="call_innergrid('+id+')" style="background: #224233 !important; color:white;"  type="button" title="Add Row"><span class="glyphicon glyphicon-plus"></span></button>'+
														'</div>'+
												   '</div>'+
												   '<div class="col-md-12" style="text-transform:uppercase;">'+
													   '<div class="col-sm-3"><label for="exampleInputPassword1">MC Option: </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
														   '<select tabindex="3" name="slt_mc_opt['+id+'][]" id="slt_mc_opt_'+id+'_1"  class="form-control custom-select" style="margin-left:0px"><option value="">Select MC Option </option><option value="G">GRAM</option><option value="K">KG</option><option value="Q">QTY</option></select>'+
													   '</div>'+
													   '<div class="col-sm-3"><label for="exampleInputPassword1">From Weight : </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
							                               '<input type="text" class="form-control ui-autocomplete-input"  id="fr_wgt_'+id+'_1" name="fr_wgt['+id+'][]"  placeholder="From Weight" data-original-title="From Weight">'+
													   '</div>'+
													   '<div class="col-sm-3"><label for="exampleInputPassword1">To Weight : </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
							                             '<input type="text" class="form-control ui-autocomplete-input"  id="to_wgt_'+id+'_1" name="to_wgt['+id+'][]"  placeholder="To Weight" data-original-title="To Weight">'+
													   '</div>'+
													  '<div class="col-sm-3"><label for="exampleInputPassword1">Making Charge : </label><span role="status"  class="ui-helper-hidden-accessible"></span>'+
							                              '<input type="text" class="form-control ui-autocomplete-input number"  id="mak_charge_'+id+'_1" name="mak_charge['+id+'][]"  placeholder="Making Charge" data-original-title="Making Charge">'+
													  '</div>'+
													'</div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							 '</div>'+ 
                       '</div>'+
				   '</div>');
			 
		$("#weight_range_"+id).select2({selectOnClose: true}).on("select2:selecting", function(evt) 
		{
			  if(evt.params.args.data.id == '0') {
				$("#weight_range_"+id).val(null).trigger("change");
			  } else {
				$('#weight_range_'+id+' > option[value=0]').prop("selected", false);
			  }
		}); 
			 
	    //$(document).ready(function(){
		
		$("#slt_mc_opt_"+id+'_1').change(function () 
		{
			//alert($(this).val());
			if($(this).val()=='G' || $(this).val()=='K')
			{
				document.getElementById('fr_wgt_'+id+'_1').value = '0.001';
				document.getElementById('to_wgt_'+id+'_1').value = '9999999';
			}
			else if($(this).val()=='Q')
			{
				document.getElementById('fr_wgt_'+id+'_1').value = '1';
				document.getElementById('to_wgt_'+id+'_1').value = '9999999';
			}
			 else{
				document.getElementById('comment_container').style.display = 'none';
			} 

		});
		
	    //});
	}
	
	function call_product_innergrid_remove(gridid) {
		// $("#removebtn").click(function () {
	       if ($('.parts3 .part3').length == 0) {
	          alert("No more row to remove.");
	       }
		   var id = ($('.parts3 .part3').length - 1).toString();
	       $('#partint3').val(id);
	       $(".parts3 .part3:last").remove();
	    // });
	}
	
	/* $("#addbtn_"+id).click(function (id){*/ 
    /* function call_innergrid(gridid) 
	{
	
		if( ($('.parts3_'+gridid+' .part3_'+gridid).length+1) > 50)
		{
			alert("Only 50 rows allowed.");
			return false;	
		}
        
		var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();

		$('#partint3_'+gridid).val(gid);
		 $('.parts3_'+gridid).append('<div class="parts3_'+gridid+' fair_border">'+
					 '<div class="form-group" style="padding-left:55px;">'+
							  '<label for="exampleInputPassword1">MC : </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
								'<input type="checkbox" name="need_mc['+id+']" id="need_mc_'+id+'" value="M" title="MC" onclick="myFunction('+id+')">'+
					   '</div>'+ 
					    '<div id="text_'+id+'" style="display:none">'+ 
							'<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
								'<div class="box-body">'+
								   '<div class="form-group">'+
									   '<div class="col-md-12" style="padding-top:20px;">'+
										   '<div class="row">'+
											    '<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
												   '<div class="col-sm-1 text-right" style="float: right;">'+
													   '<button id="removebtn_'+id+'" style="background: #d73925 !important; color:white;" class="btn btn-remove btn-danger" type="button" title="Delete Row"><span class="glyphicon glyphicon-minus"></span></button>'+
													'</div>'+
													'<div class="col-sm-1 text-right" style="float: right;">'+
														'<input type="hidden" name="partint3_'+id+'" id="partint_'+id+'" value="1">'+
														'<button class="btn btn-success btn-add3" id="addbtn_'+id+'" onclick="call_innergrid('+id+')" style="background: #00a65a !important; color:white;"  type="button" title="Add Row"><span class="glyphicon glyphicon-plus"></span></button>'+
													'</div>'+
											   '</div>'+ 
											   '<div class="col-md-12" style="text-transform:uppercase;">'+
												   '<div class="col-sm-3"><span role="status" class="ui-helper-hidden-accessible"></span>'+
													   '<select tabindex="3" name="slt_mc_opt['+gridid+'][]" id="slt_mc_opt_'+gridid+'_'+gid+'"  class="form-control custom-select" style="margin-left:0px"><option value="">Select MC Option </option><option value="G">GRAM</option><option value="K">KG</option><option value="Q">QTY</option></select>'+
												   '</div>'+
												   '<div class="col-sm-3">'+
													 '<span role="status" class="ui-helper-hidden-accessible"></span>'+
													   '<input type="text" class="form-control ui-autocomplete-input"  id="fr_wgt_'+gridid+'_'+gid+'" name="fr_wgt['+gridid+'][]"  placeholder="From Weight" data-original-title="From Weight">'+
												   '</div>'+
												   '<div class="col-sm-3"><span role="status" class="ui-helper-hidden-accessible"></span>'+
													 '<input type="text" class="form-control ui-autocomplete-input"  id="to_wgt_'+gridid+'_'+gid+'" name="to_wgt['+gridid+'][]"  placeholder="To Weight" data-original-title="To Weight">'+
												   '</div>'+
												  '<div class="col-sm-3"><span role="status"  class="ui-helper-hidden-accessible"></span>'+
													  '<input type="text" class="form-control ui-autocomplete-input number"  id="mak_charge_'+gridid+'_'+gid+'" name="mak_charge['+gridid+'][]"  placeholder="Making Charge" data-original-title="Making Charge">'+
												  '</div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						 '</div>'+ 
				   '</div>');
			 
	
    } */

    function call_innergrid(gridid) 
	{
		// alert("**"+gridid);
	    // $("#addbtn_"+gridid).click(function () {
	    	// alert("!!"+gridid);
		    if( ($('.parts3_'+gridid+' .part3_'+gridid).length+1) > 50) {
				alert("Maximum 100 Suppliers allowed.");
	        } else {
	        	$('[data-toggle="tooltip"]').tooltip();
				var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length + 2).toString();
		        $('#partint3_'+gridid).val(gid);
		        // alert("@@"+gid);
		        $('.parts3_'+gridid).append('<div class="row part3_'+gridid+'">'+
										'<div class="col-sm-1 colheight" style="padding: 1px 0px;">'+
											'<div class="fg-line"><input type="hidden" name="txt_prdsgst_per['+gridid+'][]" id="txt_prdsgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdcgst_per['+gridid+'][]" id="txt_prdcgst_per_'+gridid+'_'+gid+'" value=""><input type="hidden" name="txt_prdigst_per['+gridid+'][]" id="txt_prdigst_per_'+gridid+'_'+gid+'" value="">'+
												/* '<input type="radio" onclick="getrequestvalue('+gridid+', '+gid+')" name="txt_sltsupplier['+gridid+'][]" id="txt_sltsupplier_'+gridid+'_'+gid+'" value="'+gid+'" data-toggle="tooltip" data-placement="top" title="Select This Supplier" placeholder="Select This Supplier" class="calc"> &nbsp;'+gid+''+*/
											'</div>'+
										'</div>'+

						/* '<div class="form-group" style="padding-left:55px;">'+
							  '<label for="exampleInputPassword1">MC : </label><span role="status" class="ui-helper-hidden-accessible"></span>'+
								'<input type="checkbox" name="need_mc['+id+']" id="need_mc_'+id+'" value="M" title="MC" onclick="myFunction('+id+')">'+
					   '</div>'+ */
					   /* '<div id="text_'+id+'" style="display:none">'+ */
							'<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
								'<div class="box-body">'+
								   '<div class="form-group">'+
									   '<div class="col-md-12" style="padding-top:20px;">'+
										   '<div class="row">'+
											   /* '<div class="col-md-12" style="text-transform:uppercase;padding-bottom:20px;">'+
												   '<div class="col-sm-1 text-right" style="float: right;">'+
													   '<button id="removebtn_'+id+'" style="background: #d73925 !important; color:white;" class="btn btn-remove btn-danger" type="button" title="Delete Row"><span class="glyphicon glyphicon-minus"></span></button>'+
													'</div>'+
													'<div class="col-sm-1 text-right" style="float: right;">'+
														'<input type="hidden" name="partint3_'+id+'" id="partint_'+id+'" value="1">'+
														'<button class="btn btn-success btn-add3" id="addbtn_'+id+'" onclick="call_innergrid('+id+')" style="background: #00a65a !important; color:white;"  type="button" title="Add Row"><span class="glyphicon glyphicon-plus"></span></button>'+
													'</div>'+
											   '</div>'+ */
											   '<div class="col-md-12" style="text-transform:uppercase;">'+
												   '<div class="col-sm-3"><span role="status" class="ui-helper-hidden-accessible"></span>'+
													   '<select tabindex="3" name="slt_mc_opt['+gridid+'][]" id="slt_mc_opt_'+gridid+'_'+gid+'"  class="form-control custom-select" style="margin-left:0px"><option value="">Select MC Option </option><option value="G">GRAM</option><option value="K">KG</option><option value="Q">QTY</option></select>'+
												   '</div>'+
												   '<div class="col-sm-3">'+
													 '<span role="status" class="ui-helper-hidden-accessible"></span>'+
													   '<input type="text" class="form-control ui-autocomplete-input"  id="fr_wgt_'+gridid+'_'+gid+'" name="fr_wgt['+gridid+'][]"  placeholder="From Weight" data-original-title="From Weight">'+
												   '</div>'+
												   
												   '<div class="col-sm-3"><span role="status" class="ui-helper-hidden-accessible"></span>'+
													 '<input type="text" class="form-control ui-autocomplete-input"  id="to_wgt_'+gridid+'_'+gid+'" name="to_wgt['+gridid+'][]"  placeholder="To Weight" data-original-title="To Weight">'+
												   '</div>'+
												  '<div class="col-sm-3"><span role="status"  class="ui-helper-hidden-accessible"></span>'+
													  '<input type="text" class="form-control ui-autocomplete-input number"  id="mak_charge_'+gridid+'_'+gid+'" name="mak_charge['+gridid+'][]"  placeholder="Making Charge" data-original-title="Making Charge">'+
												  '</div>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						/* '</div>'+ */
									'</div><script>$("#fle_supquot_'+gridid+'_'+gid+'").filer({showThumbs: true,addMore: false,limit:1,allowDuplicates: false});'
									);
	$("#slt_mc_opt_"+gridid+'_'+gid+'').change(function () 
	{
		//alert($(this).val());
		if($(this).val()=='G' || $(this).val()=='K')
		{
			document.getElementById('fr_wgt_'+gridid+'_'+gid+'').value = '0.001';
			document.getElementById('to_wgt_'+gridid+'_'+gid+'').value = '9999999';
		}
		else if($(this).val()=='Q')
		{
			document.getElementById('fr_wgt_'+gridid+'_'+gid+'').value = '1';
			document.getElementById('to_wgt_'+gridid+'_'+gid+'').value = '9999999';
		}
		/* else{
			document.getElementById('comment_container').style.display = 'none';
		} */

	});
			
	}
    
    $("#supplier_touch_request").dataTable({
			  "iDisplayLength": 5,
			  "aLengthMenu": [5,10,25,50,100],
			  "language": {
				"zeroRecords": "No results available"
			  },
				  "aoColumnDefs": [
			{ 'bSortable': false, 'aTargets': ['no-sort'] }]
			});
	
		 function myFunction(id) 
		{
			var checkBox = document.getElementById("need_mc");
			var text = document.getElementById("text");
			text += text +'_'+id;
			
			if (checkBox.checked == true){
				text.style.display = "block";
			} else {
			   text.style.display = "none";
			   document.getElementById('slt_mc_opt_'+id).value = '';
			   document.getElementById('fr_wgt_'+id).value = '';
			   document.getElementById('to_wgt_'+id).value = '';
			   document.getElementById('mak_charge').value = '';
			}
		}		 	
    
	}	

	function call_innergrid_remove(gridid) {
		// alert("**"+gridid);
		// $("#removebtn_"+gridid).click(function () {
			// alert("!!"+gridid);
			if ($('.parts3_'+gridid+' .part3_'+gridid).length == 0) {
				// alert("!!"+gridid);
				alert("No more row to remove.");
			}
			var gid = ($('.parts3_'+gridid+' .part3_'+gridid).length - 1).toString();
			// alert(gridid+"@@"+gid);
			$('#partint3_'+gridid).val(gid);
			$('.parts3_'+gridid+' .part3_'+gridid+':last').remove();
	    // });
	}
	

	 /* $("#removebtn").click(function () {
		   if ($('.parts3 .part3').length == 0) {
			  // alert("No more row to remove");

			  alert("No more row to remove.");
		   }
		   var id = ($('.parts3 .part3').length - 1).toString();
		   $('#partint3').val(id);
		   $(".parts3 .part3:last").remove();
	 }); */
	 

    $(document).ready(function(){
		$('#slt_supcode').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url : "ajax_supp_purchase_profile_1.php",
					dataType: "json",
					async: true,
					data: {
					   name_startsWith: request.term,
					   type: 'supplier_name'
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
						return {
								label: item,
								value: item
							}
						}));
					}
				});
			},
			autoFocus: true,
			minLength: 0
		});
	});
	
	$(document).ready(function(){
		$('#slt_prdgroup_1').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url : "ajax_supp_purchase_profile_1.php",
					dataType: "json",
					async: true,
					data: {
					   name_startsWith: request.term,
					   type: 'product_group'
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
						return {
								label: item,
								value: item
							}
						}));
					}
				});
			},
			autoFocus: true,
			minLength: 0
		});
	});
	
	$(document).ready(function(){
		$('#prdcode_1').autocomplete({
			source: function( request, response ) {
				$.ajax({
					url : 'ajax_supp_purchase_profile_1.php',
					dataType: "json",
					data: {
					   name_startsWith: request.term,
					   mode: 'product'
					},
					success: function( data ) {
						response( $.map( data, function( item ) {
							return {
								label: item,
								value: item
							}
						}));
					}
				});
			},
			autoFocus: true,
			minLength: 0
		});
	});
	
 
    function select_range(id)
	{ 
	    $(".loader").show();
		//alert("hello");
		//alert(id);
		var sel_prdcode=document.getElementById('prdcode_'+id).value;
		var prddet = sel_prdcode.split(" - ");
		var seccode = prddet[2];
		//alert(sel_prdcode);
		//alert(prddet);
		//alert(seccode);
		if (id == "") {
			document.getElementById("weight_range_"+id).innerHTML = "";
		return;
		} else { 
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			 xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					//alert(xmlhttp.responseText);
					document.getElementById("weight_range_"+id).style.display = 'block';
					document.getElementById("weight_range_"+id).innerHTML = xmlhttp.responseText;
					$(".loader").fadeOut("fast");
				}
				
			}
			xmlhttp.open("GET","ajax_supp_purchase_profile_1.php?mode=wgt_range&subtype_id="+seccode,true);
			xmlhttp.send();
		}
	} 
	
	/*function select_range(id)
	{
		 var txt=$("#prdcode_1 option:selected").val();
		var res =txt.split(" - ");
		alert(res);
		
	}*/
	
	/* function select_product(id)
	{ 
	    $(".loader").show();
		alert("hello");
		alert(id);
		var sel_prdcode1=document.getElementById('slt_prdgroup_'+id).value;
		var prddet1 = sel_prdcode1.split(" - ");
		var seccode1 = prddet1[0];
		alert(sel_prdcode1);
		alert (prddet1)
		alert(seccode1)
		if (id == "") {
			document.getElementById("prdcode_"+id).innerHTML = "";
		return;
		} else { 
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			 xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					alert(xmlhttp.responseText);
					document.getElementById("prdcode_"+id).style.display = 'block';
					document.getElementById("prdcode_"+id).innerHTML = xmlhttp.responseText;
					$(".loader").fadeOut("fast");
				}
				
			}
			xmlhttp.open("GET","ajax_supp_purchase_profile_1.php?mode=product&subtype1_id="+seccode1,true);
			
			xmlhttp.send();
		}
	}*/


	function select_product(id)
	{
		var sel_prdcode1=document.getElementById('slt_prdgroup_'+id).value;
		var prddet1 = sel_prdcode1.split(" - ");
		var seccode1 = prddet1[0];
		if (id == "") {
			document.getElementById("prdcode_"+id).innerHTML = "";
		return;
		} else { 
			$(".loader").show();
			  $.ajax({
			 	data:{subtype1_id:seccode1},
			 	url:"ajax_supp_purchase_profile_1.php?mode=product",
				 
			 	success:function(data)
			 	{
			 		$('#prdcode_'+id).html(data);
			 		$(".loader").fadeOut("fast");
			 	}

			 });
		}
	}
	//http://www.tcsportal.com/productionstatus.php
    $(document).ready(function()
	{
		$checks = $(".chk1");
		
		$checks.on('change', function() {
			alert("hi");
			alert($(this).val());
			var string = $checks.filter(":checked").map(function(i,v){
				return this.value;
			}).get().join(',');
			alert(string);
			$('#weight_range').val(string);
		});
	});

    $('.number').keypress(function(event) 
	{
		var $this = $(this);
		if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
		   ((event.which < 48 || event.which > 57) &&
		   (event.which != 0 && event.which != 8))) {
			   event.preventDefault();
		}

		var text = $(this).val();
		if ((event.which == 46) && (text.indexOf('.') == -1)) {
			setTimeout(function() {
				if ($this.val().substring($this.val().indexOf('.')).length > 3) {
					$this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
				}
			}, 1);
		}

		if ((text.indexOf('.') != -1) &&
			(text.substring(text.indexOf('.')).length > 2) &&
			(event.which != 0 && event.which != 8) &&
			($(this)[0].selectionStart >= text.length - 2)) {
				event.preventDefault();
		}      
    });
	
	$('.number').bind("paste", function(e) {
	var text = e.originalEvent.clipboardData.getData('Text');
	if ($.isNumeric(text)) {
		if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
			e.preventDefault();
			$(this).val(text.substring(0, text.indexOf('.') + 3));
	   }
	}
	else {
			e.preventDefault();
		 }
	});
  
    // only 1 dot
	function isNumberwithDot1(evt,id){
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		var dot = document.getElementById(id).value;
		if(dot.indexOf('.') !== -1)
		{
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
		}
		else if (charCode > 31 && charCode != 46 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

    function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		// alert(evt+"****"+charCode);
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	<!-- allow only numeric -->
    $(".numeric").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

    $(window).load(function() {
		
		$(".loader").fadeOut("slow");
	})
	
	/* $("#example0").dataTable
	({
			  "iDisplayLength": 10,
			  "aLengthMenu": [5,10,25,50,100],
			  "language": {
				"zeroRecords": "No results available"
			  },
				  "aoColumnDefs": [
			{ 'bSortable': false, 'aTargets': ['no-sort'] }]
	}); */
	
	$("#slt_mc_opt_1_1").change(function () 
	{
		//alert($(this).val());
		if($(this).val()=='G' || $(this).val()=='K')
		{
			document.getElementById('fr_wgt_1_1').value = '0.001';
			document.getElementById('to_wgt_1_1').value = '9999999';
		}
		else if($(this).val()=='Q')
		{
			document.getElementById('fr_wgt_1_1').value = '1';
			document.getElementById('to_wgt_1_1').value = '9999999';
		}
		/* else{
			document.getElementById('comment_container').style.display = 'none';
		} */

	}); 
	
	
	 /* function checkBlock() 
	{
		var checkBox = document.getElementById("need_mc_1");
		var text = document.getElementById("text_1");
		if (checkBox.checked == true){
			text.style.display = "block";
		} else {
		   text.style.display = "none";
		   document.getElementById('slt_mc_opt_1_1').value = '';
		   document.getElementById('fr_wgt_1_1').value = '';
		   document.getElementById('to_wgt_1_1').value = '';
		   document.getElementById('mak_charge_1_1').value = '';
		}
    }  */ 
	
	function mc_det(entyear, entnumb, entsrno)
	{

		  $(".loader").show();
		  $.ajax({
			url:"ajax_supp_purchase_profile_1.php?entsrno="+entsrno+"&entyear="+entyear+"&entnumb="+entnumb+"&mode=mc",  
			success:function(data)
			{
			$(".loader").show();
			$("#myModal1").modal('show');
			document.getElementById('modal-body1').innerHTML=data;
			$(".loader").fadeOut("slow");
			}
		});
	}
	
	function comparision_det(prdcode)
	{

		  $(".loader").show();
		  $.ajax({
			url:"ajax_supp_purchase_profile_1.php?prdcode="+prdcode+"&mode=comparision",  
			success:function(data)
			{
			$(".loader").show();
			$("#myModal1").modal('show');
			document.getElementById('modal-body1').innerHTML=data;
			$(".loader").fadeOut("slow");
			}
		});
	}

			
	function profit_det(prdcode, entyear, entnumb)
	{
		  $(".loader").show();
		  $.ajax({
			url:"ajax_supp_purchase_profile_1.php?prdcode="+prdcode+"&entyear="+entyear+"&entnumb="+entnumb+"&mode=profit",  
			success:function(data)
			{
			$(".loader").show();
			$("#myModal1").modal('show');
			document.getElementById('modal-body1').innerHTML=data;
			$(".loader").fadeOut("slow");
			}
		});
	}
	
	function ESCclose(evt) 
	{
		if (evt.keyCode == 27) 
		 $('#myModal1').modal('toggle');
	}
	
	function entryapproval1()
	{
		var choices = [];
		var entry_comments = [];
		var entry_prdcode = [];
		var els = document.getElementsByName('entry_approval[]');
		for (var i=0;i<els.length;i++){
		  if ( els[i].checked ) {
			choices.push(els[i].value);
			var j=i+1;
			entry_comments.push(document.getElementById('entry_comments_'+j).value);
			entry_prdcode.push(document.getElementById('entry_prdcode_'+j).value);
			
		  }
		}
		$('#entry_param1').val(choices);
		$('#entry_comments_param').val(entry_comments);
		$('#entry_prdcode_param').val(entry_prdcode);
		//alert(choices);
		//alert(entry_comments);
		//alert(entry_prdcode);
	}
	
	function entryapproval()
	{
		//$(".loader").show();
		var entry_param = document.getElementById('entry_param').value;
		var entry_comments= document.getElementById('entry_comments_param').value;
		var entry_prdcode= document.getElementById('entry_prdcode_param').value;
		
		
		
		if(entry_param == '')
		{
			alert("Please Select An Atleast One Item");
		}
		else
		{
		
		if (confirm("Are you sure Request?")) {
		
		$.ajax({
			method : 'GET',
			url:"ajax_supp_purchase_profile_1.php?entry_param="+entry_param+"&entry_comments="+entry_comments+"&entry_prdcode="+entry_prdcode+"&type=POST&action=entryapproval",
			success: function(entry_param) 
			{
				alert('Touch User Comment Saved Successfully..!!');
				var entry_param = document.getElementById('entry_param').value;
				//alert(entry_param);
				var str_array = entry_param.split(',');
				for(var i=0; i< str_array.length; i++)
				{
                   //alert(str_array[i]);
				   str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
				   var str_array1 = str_array[i].split('/');
				   //alert(str_array1[1]);
				   //document.getElementById('status_'+str_array1[0]+'_'+str_array1[1]+'_'+str_array1[2]).style.display = "none";
				   
				}
				window.location.reload();
			}
		})
		}
	   }
	}
	
	function pmapproval1()
	{
		var choices = [];
		var slt_approval = [];
		var pm_comments = [];
		var pm_totcalc = [];
		var pm_prdcode = [];
		var els = document.getElementsByName('pm_approval[]');
		for (var i=0;i<els.length;i++){
		  if ( els[i].checked ) {
			choices.push(els[i].value);
			var j=i+1;
			slt_approval.push(document.getElementById('slt_approval_'+j).value);
			pm_comments.push(document.getElementById('pm_comments_'+j).value);
			pm_totcalc.push(document.getElementById('pm_totcalc_'+j).value);
			pm_prdcode.push(document.getElementById('pm_prdcode_'+j).value);
			
		  }
		}
		$('#pm_param').val(choices);
		$('#slt_approval_param').val(slt_approval);
		$('#pm_comments_param').val(pm_comments);
		$('#pm_totcalc_param').val(pm_totcalc);
		$('#pm_prdcode_param').val(pm_prdcode);
		//alert(choices);
		//alert(slt_approval);
		//alert(pm_comments);
		//alert(pm_totcalc);
		//alert(pm_prdcode);
	}
	
	function pmapproval()
	{
		//$(".loader").show();
		var pm_param = document.getElementById('pm_param').value;
		var slt_approval= document.getElementById('slt_approval_param').value;
		var pm_comments= document.getElementById('pm_comments_param').value;
		var pm_totcalc= document.getElementById('pm_totcalc_param').value;
		var pm_prdcode= document.getElementById('pm_prdcode_param').value;
		
		
		if(pm_param == '')
		{
			alert("Please Select An Atleast One Item");
		}
		else
		{
		
		if (confirm("Are you sure Request?")) {
		
		$.ajax({
			method : 'GET',
			url:"ajax_supp_purchase_profile_1.php?pm_param="+pm_param+"&pm_comments="+pm_comments+"&pm_prdcode="+pm_prdcode+"&pm_totcalc="+pm_totcalc+"&slt_approval="+slt_approval+"&type=POST&action=approval",
			success: function(pm_param) 
			{
				alert('Touch PM Approval Saved Successfully..!!');
				var pm_param = document.getElementById('pm_param').value;
				var str_array = pm_param.split(',');
				for(var i=0; i< str_array.length; i++)
				{
                   //alert(str_array[i]);
				   str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
				   var str_array1 = str_array[i].split('/');
				   //alert(str_array1[1]);
				   document.getElementById('pmstatus_'+str_array1[0]+'_'+str_array1[1]+'_'+str_array1[2]).style.display = "none";
				   
				}
				window.location.reload();
			}
		})
		}
	   }
	}
	
	/* function save_approval(iv, entyear, entnumb, entsrno) {
		$('.loader').show(); // show the loading message.
		
		var slt_approval= $("#slt_approval_"+iv).val();
		var pm_comments= $("#pm_comments_"+iv).val();
		var pm_totcalc= $("#pm_totcalc_"+iv).val();
		var pm_prdcode= $("#pm_prdcode_"+iv).val();
		
		 $.ajax({
			method:'POST',
			url:"ajax_supp_purchase_profile_1.php?action=approval&i="+iv+"&entyear="+entyear+"&entnumb="+entnumb+"&entsrno="+entsrno+"&pm_comments="+pm_comments+"&pm_prdcode="+pm_prdcode+"&pm_totcalc="+pm_totcalc+"&slt_approval="+slt_approval,
			success:function(data){
				$('.loader').hide(); // hide the loading message.
				if(data == 1) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Touch PM Approval Added Successfully..!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				} else {					
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Failed to update.. Kindly try again!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				}
				
				//$(".loader").fadeOut("fast");
			}
		})
	} */
	
	function dgmapproval1()
	{
		var choices = [];
		var dgm_slt_approval = [];
		var hod_comments = [];
		var hod_totcalc = [];
		var dgm_prdcode = [];
		var els = document.getElementsByName('dgm_approval[]');
		for (var i=0;i<els.length;i++){
		  if ( els[i].checked ) {
			choices.push(els[i].value);
			var j=i+1;
			dgm_slt_approval.push(document.getElementById('dgm_slt_approval_'+j).value);
			hod_comments.push(document.getElementById('hod_comments_'+j).value);
			hod_totcalc.push(document.getElementById('hod_totcalc_'+j).value);
			dgm_prdcode.push(document.getElementById('dgm_prdcode_'+j).value);
			
		  }
		}
		$('#dgm_param').val(choices);
		$('#dgm_slt_approval_param').val(dgm_slt_approval);
		$('#hod_comments_param').val(hod_comments);
		$('#hod_totcalc_param').val(hod_totcalc);
		$('#dgm_prdcode_param').val(dgm_prdcode);
		//alert(choices);
		//alert(dgm_slt_approval);
		//alert(hod_comments);
		//alert(hod_totcalc);
		//alert(dgm_prdcode);
	}
	
	function dgmapproval()
	{
		//$(".loader").show();
		var dgm_param = document.getElementById('dgm_param').value;
		var dgm_slt_approval= document.getElementById('dgm_slt_approval_param').value;
		var hod_comments= document.getElementById('hod_comments_param').value;
		var hod_totcalc= document.getElementById('hod_totcalc_param').value;
		var dgm_prdcode= document.getElementById('dgm_prdcode_param').value;
		
		
		if(dgm_param == '')
		{
			alert("Please Select An Atleast One Item");
		}
		else
		{
		
		if (confirm("Are you sure Request?")) {
		
		$.ajax({
			method : 'GET',
			url:"ajax_supp_purchase_profile_1.php?dgm_param="+dgm_param+"&hod_comments="+hod_comments+"&dgm_prdcode="+dgm_prdcode+"&hod_totcalc="+hod_totcalc+"&dgm_slt_approval="+dgm_slt_approval+"&type=POST&action=dgmapproval",
			success: function(dgm_param) 
			{
				alert('Touch DGM Approval Saved Successfully..!!');
				var dgm_param = document.getElementById('dgm_param').value;
				var str_array = dgm_param.split(',');
				for(var i=0; i< str_array.length; i++)
				{

				   str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
				   var str_array1 = str_array[i].split('/');
				   //alert(str_array1[1]);
				   document.getElementById('dgmstatus_'+str_array1[0]+'_'+str_array1[1]+'_'+str_array1[2]).style.display = "none";
				   
				}
				window.location.reload();
			}
		})
		}
	   }
	}
	
	/* function save_dgmapproval(iv, entyear, entnumb, entsrno) {
		$('.loader').show(); // show the loading message.
		
		var dgm_slt_approval= $("#dgm_slt_approval_"+iv).val();
		var hod_comments= $("#hod_comments_"+iv).val();
		var hod_totcalc= $("#hod_totcalc_"+iv).val();
		var dgm_prdcode= $("#dgm_prdcode_"+iv).val();
		
		 $.ajax({
			method:'POST',
			url:"ajax_supp_purchase_profile_1.php?action=dgmapproval&i="+iv+"&entyear="+entyear+"&entnumb="+entnumb+"&entsrno="+entsrno+"&hod_comments="+hod_comments+"&dgm_prdcode="+dgm_prdcode+"&hod_totcalc="+hod_totcalc+"&dgm_slt_approval="+dgm_slt_approval,
			success:function(data){
				$('.loader').hide(); // hide the loading message.
				if(data == 1) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Touch DGM Approval Added Successfully..!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				} else {					
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Failed to update.. Kindly try again!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				}
				
				//$(".loader").fadeOut("fast");
			}
		})
	} */
	
	function gmapproval1()
	{
		var choices = [];
		var gm_slt_approval = [];
		var gm_comments = [];
		var gm_totcalc = [];
		var gm_prdcode = [];
		var els = document.getElementsByName('gm_approval[]');
		for (var i=0;i<els.length;i++){
		  if ( els[i].checked ) {
			choices.push(els[i].value);
			var j=i+1;
			gm_slt_approval.push(document.getElementById('gm_slt_approval_'+j).value);
			gm_comments.push(document.getElementById('gm_comments_'+j).value);
			gm_totcalc.push(document.getElementById('gm_totcalc_'+j).value);
			gm_prdcode.push(document.getElementById('gm_prdcode_'+j).value);
			
		  }
		}
		$('#gm_param').val(choices);
		$('#gm_slt_approval_param').val(gm_slt_approval);
		$('#gm_comments_param').val(gm_comments);
		$('#gm_totcalc_param').val(gm_totcalc);
		$('#gm_prdcode_param').val(gm_prdcode);
		//alert(choices);
		//alert(gm_slt_approval);
		//alert(gm_comments);
		//alert(gm_totcalc);
		//alert(gm_prdcode);
		
	}
	
	function gmapproval()
	{
		//$(".loader").show();
		var gm_param = document.getElementById('gm_param').value;
		var gm_slt_approval= document.getElementById('gm_slt_approval_param').value;
		var gm_comments= document.getElementById('gm_comments_param').value;
		var gm_totcalc= document.getElementById('gm_totcalc_param').value;
		var gm_prdcode= document.getElementById('gm_prdcode_param').value;
		
		
		if(gm_param == '')
		{
			alert("Please Select An Atleast One Item");
		}
		else
		{
		
		if (confirm("Are you sure Request?")) {
		
		$.ajax({
			method : 'GET',
			url:"ajax_supp_purchase_profile_1.php?gm_param="+gm_param+"&gm_comments="+gm_comments+"&gm_prdcode="+gm_prdcode+"&gm_totcalc="+gm_totcalc+"&gm_slt_approval="+gm_slt_approval+"&type=POST&action=gmapproval",
			success: function(gm_param) 
			{
				alert('Touch GM Approval Saved Successfully..!!');
				var gm_param = document.getElementById('gm_param').value;
				var str_array = gm_param.split(',');
				for(var i=0; i< str_array.length; i++)
				{

				   str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
				   var str_array1 = str_array[i].split('/');
				   //alert(str_array1[1]);
				   document.getElementById('gmstatus_'+str_array1[0]+'_'+str_array1[1]+'_'+str_array1[2]).style.display = "none";
				   
				}
				window.location.reload();
			}
		})
		}
	   }
	}
	
	/* function save_gmapproval(iv, entyear, entnumb, entsrno) {
		$('.loader').show(); // show the loading message.
		
		var gm_slt_approval= $("#gm_slt_approval_"+iv).val();
		var gm_comments= $("#gm_comments_"+iv).val();
		var gm_totcalc= $("#gm_totcalc_"+iv).val();
		var gm_prdcode= $("#gm_prdcode_"+iv).val();
		
		 $.ajax({
			method:'POST',
			url:"ajax_supp_purchase_profile_1.php?action=gmapproval&i="+iv+"&entyear="+entyear+"&entnumb="+entnumb+"&entsrno="+entsrno+"&gm_comments="+gm_comments+"&gm_prdcode="+gm_prdcode+"&gm_totcalc="+gm_totcalc+"&gm_slt_approval="+gm_slt_approval,
			success:function(data){
				$('.loader').hide(); // hide the loading message.
				if(data == 1) {
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Touch GM Approval Added Successfully..!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				} else {					
					var ALERT_TITLE = "Message";
					var ALERTMSG = "Failed to update.. Kindly try again!!";
					createCustomAlert(ALERTMSG, ALERT_TITLE);
				}
				
				//$(".loader").fadeOut("fast");
			}
		})
	} */
	 
	 
	 /* function select_range(range)
	{
		var sel_prdcode=document.getElementById('prdcode').value;
		var prddet = sel_prdcode.split(" - ");
		var seccode = prddet[2];
		if (range == "") {
			document.getElementById("weight_range").innerHTML = "";
		return;
		} else { 
			
			$.ajax({
					url : 'ajax_supp_purchase_profile_1.php',
					data: {
					   subtype_id: seccode,
					   mode: 'wgt_range'
					},
					success: function( data ) {
						$(".loader").show();
						document.getElementById("wgt_range").innerHTML = data;
						$(".loader").fadeOut("fast");
					
					}
				});
	    	}
	} */
	 
  var ALERT_BUTTON_TEXT = "OK";
		function createCustomAlert(txt, title) 
		{
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

		function removeCustomAlert() 
		{
			document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
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
</body>
</html>
<? 
} 
catch(Exception $e) {
	echo 'Unknown Error. Try again.';
}
?>