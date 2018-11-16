<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect_1.php');
include_once('../general_functions.php');
extract($_REQUEST);


if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$sql_lastday = select_query_json("select trunc(last_day(sysdate)) ldate from dual", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$lastdate = strtoupper(date('t-M-Y h:i:s A'));
$cur = strtoupper(date('Y')-1);
$lat = strtoupper(date('Y')-2);
$cur_mon = strtoupper(date('m'));
$lat_mon = strtoupper(date('m'));
$sysip = $_SERVER['REMOTE_ADDR'];
// echo "<pre>";
$cur_mn = date("m");

// connect and login to FTP server
$ftp_conn = ftp_connect(ftpvri_server_apdsk, 5022) or die("Could not connect to ftpvri_server_apdsk");
$login = ftp_login($ftp_conn, ftpvri_user_name_apdsk, ftpvri_user_pass_apdsk);

 

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
												or sup.SUPNAME like '%".strtoupper($_GET['name_startsWith'])."%')
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
}  
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
}if($action == 'product_val') {
	
	$result = select_query_json("select distinct PRDCODE, PRDNAME, HSNCODE from trandata.product_asset@tcscentr 
										where deleted = 'N' and (PRDCODE = '".strtoupper($prdcd)."')
										Order by PRDCODE, PRDNAME", "Centra", 'TCS'); 
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
}if($action == 'budget_submit'){
	/*$sql_reqid = select_query_json("select * from trandata.approval_request  where (aprnumb,arqsrno) in 
	(select aprnumb,max(arqsrno) from trandata.approval_request  where aprnumb like '%".$_REQUEST['txt_aprnumb']."%' group by aprnumb)","Centra", 'TEST');*/

	$sql_reqid = select_query_json("select * from trandata.approval_request where aprnumb like '%".$_REQUEST['txt_aprnumb']."%' and arqsrno=1","Centra", 'TEST');
	$topcore = select_query_json("Select ATCNAME, ATCCODE From APPROVAL_TOPCORE where deleted = 'N' and ATCCODE = ".$sql_reqid[0]['ATCCODE']); // Topcore
	$maxarqpcod = select_query_json("Select nvl(Max(ARQPCOD),0)+1 MAXARQPCOD
												From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$sql_reqid[0]['ATCCODE'], "Centra", 'TEST'); 
	$maxarqcode = select_query_json("Select nvl(Max(ARQCODE),0)+1 maxarqcode, nvl(Max(ARQSRNO),1) maxarqsrno 
													From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$sql_reqid[0]['ATCCODE'], "Centra", 'TEST');

	/* Query for find the target balance */
	$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal, 
													sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease 
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn 
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode and 
													brn.brncode=".$sql_reqid[0]['BRNCODE']." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$sql_reqid[0]['DEPCODE']." and 
													non.PTNUMB=".$sql_reqid[0]['TARNUMB']." 
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
	if(count($target_balance) == '') {
		$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal, 
													sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease 
												from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn 
												where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode and 
													brn.brncode=".$sql_reqid[0]['BRNCODE']." and dep.depcode=".$sql_reqid[0]['DEPCODE']." and non.PTNUMB=".$sql_reqid[0]['TARNUMB']." 
												group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
	}

	$expname = select_query_json("select distinct round(tarnumb) tarnumb, ( select distinct decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname 
											from non_purchase_target tar, department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode 
											and tar.brncode=bpl.brncode) Depname 
										from budget_planner_branch bpl 
										where depcode=".$sql_reqid[0]['DEPCODE']." and brncode=".$sql_reqid[0]['BRNCODE']." and tarnumb=".$sql_reqid[0]['TARNUMB']."
										order by Depname", "Centra", 'TCS');
	$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empsrno = ".$_SESSION['tcs_empsrno'], "Centra", 'TCS');
	$empdes = "designation"; $empsec = "empsection";
	if($emp[0]['PAYCOMPANY'] == 2) {
		$empdes = "new_designation"; $empsec = "new_empsection";
	} 

	$bydesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$_SESSION['tcs_descode'], "Centra", 'TCS'); // Req.By user designation
	$bysection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], "Centra", 'TCS'); // Req.By user section
	$subcore_name = select_query_json("select CORNAME from empcore_section where DELETED = 'N' and ESECODE = ".$sql_reqid[0]['SUBCORE'], "Centra", 'TCS'); // Sub Core Name 

	$srno = $sql_reqid[0]['IMUSRIP'];
	$txtfrom_date1 = strtotime($txtfrom_date);
	$txtfrom_date2 = strtoupper(date('d-M-Y h:i:s A', $txtfrom_date1));
	$txtto_date1 = strtotime($txtto_date);
	$txtto_date2 = strtoupper(date('d-M-Y h:i:s A', $txtto_date1));
	
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$currenttime = strtoupper(date('H:i A'));
	
	$currentdate1 = strtoupper(date('d-m-Y'));
	$currenttime1 = strtoupper(date('h:i A'));
	
	
	switch($sql_reqid[0]['ATCCODE'])
	{
		case 1:
				$startwith = 1;
				break;
		case 2:
				$startwith = 2;
				break;
		case 3:
				$startwith = 3;
				break;
		case 4:
				$startwith = 4;
				break;
		case 5:
				$startwith = 1;
				break;
		case 6:
				$startwith = 1;
				break;
		case 7:
				$startwith = 1;
				break;
		default:
				$startwith = 1;
				break;
	}
	// echo "**".$apusr."**".$apfrwrdusr."**<br>";
	
	if($txtdue_date == '')
		$txtdue_date = $currentdate;
	$srno = $startwith.str_pad($maxarqcode[0]['MAXARQCODE'], 6, '0', STR_PAD_LEFT);

	//$srno = 4008104;
	$apprno = strtoupper($topcore[0]['ATCNAME'].' / '.$subcore_name[0]['CORNAME'].' '.$srno.' / '.$currentdate1.' / '.substr($srno, -4).' / '.$currenttime1);

	$apr['ARQPCOD']=$maxarqpcod[0]['MAXARQPCOD'];
	$apr['ARQCODE']=$maxarqcode[0]['MAXARQCODE'];
	$apr['ARQYEAR']=$current_year[0]['PORYEAR'];
	$apr['ARQSRNO']=1;
	$apr['ATYCODE']=$slt_budgetmode;
	$apr['ATMCODE']=$sql_reqid[0]['ATMCODE'];
	$apr['APMCODE']=$slt_approval_listings;
	$apr['ATCCODE']=$sql_reqid[0]['ATCCODE'];
	$apr['APPRFOR']=1;
	$apr['REQSTTO']=$sql_reqid[0]['REQSTTO'];

	// Detail Content generate in a txt file
	$description = $_REQUEST['txtdetails'];
	$lpdyear = $current_year[0]['PORYEAR'];
	$txt_srcfilename = "apd_".$lpdyear."_".$srno."_1.txt";
	 

	$local_file = "uploads/text_approval_source/".$txt_srcfilename;
	$myfile = fopen($local_file, "w");
	fwrite($myfile, $description);
	fclose($myfile);

	$server_file = 'approval_desk/text_approval_source/'.$lpdyear.'/'.$txt_srcfilename;
	if ((!$conn_id) || (!$login_result)) {
		$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
		unlink($local_file);
	}
	// Detail Content generate in a txt file
	$txtdetails = "1";
	
	$apr['APPRSUB'] = str_replace("'", "", $lpdyear.'/'.$txt_srcfilename);
	$apr['APPRDET'] = str_replace("'", "", $txtdetails);

	$apr['APPRSFR']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$apr['APPRSTO']='dd-Mon-yyyy HH:MI:SS AM~~'.$impldue_date;
	$apr['APPATTN']=0;
	$apr['APRQVAL']=$txtrequest_value;
	$apr['APPDVAL']=$txtrequest_value;
	$apr['APPFVAL']=$txtrequest_value;
	$apr['BRNCODE']=$sql_reqid[0]['BRNCODE'];
	$apr['DEPCODE']=$sql_reqid[0]['DEPCODE'];
	$apr['TARNUMB']=$sql_reqid[0]['TARNUMB'];
	if($target_balance[0]['BALRELEASE'] != ""){
	$apr['TARBALN']=$target_balance[0]['BALRELEASE'];
		}else{
	$apr['TARBALN']=0;
		}
	$apr['TARDESC']=$sql_reqid[0]['TARDESC'];
	$apr['REQSTBY']=$_SESSION['tcs_empsrno'];
	$apr['RQBYDES']=$_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
	$apr['REQDESC']=$_SESSION['tcs_descode'];
	$apr['REQESEC']=$_SESSION['tcs_esecode'];
	$apr['REQDESN']=$bydesignation[0]['DESNAME'];
	$apr['REQESEN']=$bysection[0]['ESENAME'];
	$apr['REQSTFR']=$sql_reqid[0]['REQSTFR'];
	$apr['RQFRDES']=$sql_reqid[0]['RQFRDES'];
	$apr['RQFRDSC']=$sql_reqid[0]['RQFRDSC'];
	$apr['RQFRESC']=$sql_reqid[0]['RQFRESC'];
	$apr['RQFRDSN']=$sql_reqid[0]['RQFRDSN'];
	$apr['RQFRESN']=$sql_reqid[0]['RQFRESN'];
	$apr['RQESTTO']=$sql_reqid[0]['RQESTTO'];
	$apr['RQTODES']=$sql_reqid[0]['RQTODES'];
	$apr['RQTODSC']=$sql_reqid[0]['RQTODSC'];
	$apr['RQTOESC']=$sql_reqid[0]['RQTOESC'];
	$apr['RQTODSN']=$sql_reqid[0]['RQTODSN'];
	$apr['RQTOESN']=$sql_reqid[0]['RQTOESN'];
	$apr['APRNUMB']=$apprno;
	$apr['APPSTAT']="N";
	$apr['APPFRWD']="N";
	$apr['APPINTP']="N";
	$apr['INTPEMP']=$sql_reqid[0]['INTPEMP'];
	$apr['INTPDES']=0;
	$apr['INTPDSC']=0;
	$apr['INTPESC']=0;
	$apr['INTPDSN']=$sql_reqid[0]['INTPESN'];
	$apr['INTPESN']='-';
	$apr['INTPAPR']='-';
	$apr['INTSUGG']='-';
	$apr['INTPFRD']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$apr['INTPTOD']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$apr['ADDUSER']=$_SESSION['tcs_empsrno'];
	$apr['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$apr['EDTUSER']='';
	$apr['EDTDATE']='';
	$apr['DELETED']='N';
	$apr['DELUSER']='';
	$apr['DELDATE']='';
	$apr['APRCODE']=$sql_reqid[0]['APRCODE'];
	$apr['APRHURS']=$sql_reqid[0]['APRHURS'];
	$apr['APRDAYS']=$sql_reqid[0]['APRDAYS'];
	$apr['APRDUED']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$apr['APPRMRK']='';
	$apr['APRTITL']=$sql_reqid[0]['APRTITL'];
	$apr['FINSTAT']='N';
	$apr['FINUSER']='';
	$apr['FINCMNT']='';
	$apr['FINDATE']='';

	// Current Year Record
	$cur_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal, 
											decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per 
										from budget_planner_branch bpl, non_sales_target non
										where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($cur,-2)."' 
											and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
										group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL 
										order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS');

	// Last Year Record
	$last_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal, 
											decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per 
										from budget_planner_branch bpl, non_sales_target non
										where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($lat,-2)."' 
											and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
										group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL 
										order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS'); 

	$apr['TARVLCY']=$cur_year[0]['BUDGETVAL'];
	$apr['TARVLLY']=$last_year[0]['BUDGETVAL'];
	$apr['EXPNAME']=$expname[0]['DEPNAME'];
	$apr['TARPRCY']=$cur_year[0]['PER'];
	$apr['TARPRLY']=$last_year[0]['PER'];
	$apr['USRSYIP']=$sysip;
	$apr['PRJPRCS']=$sql_reqid[0]['PRJPRCS'];
	$apr['PLANVAL']='';
	$apr['IMDUEDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$impldue_date;
	$apr['IMUSRCD']='';
	$apr['IMSTATS']='N';
	$apr['IMFINDT']='';
	$apr['IMUSRIP']=$srno;
	$apr['TYPMODE']='AP';
	$apr['SUBCORE']=$sql_reqid[0]['SUBCORE'];
	//$apr['BUDTYPE']=$sql_reqid[0]['BUDTYPE'];
	$apr['BUDTYPE']=6;
	$apr['BUDCODE']=$sql_reqid[0]['BUDCODE'];
	$apr['IMFNIMG']=$sql_reqid[0]['IMFNIMG'];
	$apr['NXLVLUS']=1;
	$apr['PRICODE']=$sql_reqid[0]['PRICODE'];
	$apr['SUPCODE']='';
	$apr['SUPNAME']='';
	$apr['SUPCONT']='';
	$apr['PRODWIS']=$sql_reqid[0]['PRODWIS'];
	$apr['RESPUSR']=$sql_reqid[0]['RESPUSR'];
	$apr['ALTRUSR']=$sql_reqid[0]['ALTRUSR'];
	$apr['RELAPPR']=$sql_reqid[0]['APRNUMB'];
	$apr['ORGRECV']='N';
	$apr['ORGRVUS']='';
	$apr['ORGRVDT']='';
	$apr['ORGRVDC']='';
	$apr['CNVRMOD']=$sql_reqid[0]['CNVRMOD'];
	$apr['PURHEAD']=$sql_reqid[0]['PURHEAD'];
	$apr['APPTYPE']=$sql_reqid[0]['APPTYPE'];
	$apr['ADVAMNT']='';
	$apr['WRKINUSR']=$sql_reqid[0]['WRKINUSR'];
	$apr['BDPLANR']=$sql_reqid[0]['BDPLANR'];
	$apr['DYNSUBJ']=$sql_reqid[0]['DYNSUBJ'];
	$insert_appreq = insert_test_dbquery($apr, 'APPROVAL_REQUEST');

	$sql_hir = select_query_json("select * from approval_mdhierarchy where aprnumb in '".$sql_reqid[0]['APRNUMB']."' order by amhsrno","Centra","TCS");

	foreach ($sql_hir as $hir) 
	{
		$apphier = array();
		$apphier['APMCODE']=$hir['APMCODE']; 
		$apphier['AMHSRNO']=$hir['AMHSRNO']; 
		$apphier['APPHEAD']=$hir['APPHEAD']; 
		$apphier['APPDESG']=$hir['APPDESG']; 
		$apphier['APPDAYS']=$hir['APPDAYS']; 
		$apphier['APPRIOR']=$hir['APPRIOR']; 
		$apphier['APPTITL']=$hir['APPTITL']; 
		$apphier['VRFYREQ']=$hir['VRFYREQ']; 
		$apphier['APRNUMB']=$apprno; 
		$apphier['PBDAPPR']=$hir['PBDAPPR']; 
		$appinsert = insert_test_dbquery($apphier,"approval_mdhierarchy");
	}
	
	if($insert_appreq == 1) {
		// Product List Adding
		$uploadimg = $current_year[0]['PORYEAR'];
		for($pdi = 0; $pdi < count($txt_prdcode); $pdi++) {
			if($txt_prdcode[$pdi] != '') {
				$prd_cd = explode(" - ", $txt_prdcode[$pdi]);
				$sprd_cd = explode(" - ", $txt_subprdcode[$pdi]);

				$maxprlstno = select_query_json("Select nvl(Max(PRLSTNO),0)+1 MAXPRLSTNO 
														From APPROVAL_PRODUCTLIST 
														WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PRLSTYR = '".$current_year[0]['PORYEAR']."' and PRLSTNO = '".$maxprlstno[0]['MAXPRLSTNO']."'", "Centra", 'TEST'); 
				$tbl_appdet = "APPROVAL_PRODUCTLIST";
				$field_appdet = array();
				$field_appdet['PBDYEAR'] = $current_year[0]['PORYEAR'];
				$field_appdet['PBDCODE'] = $srno;
				$field_appdet['PBDLSNO'] = $maxprlstno[0]['MAXPRLSTNO'];
				$field_appdet['PRLSTYR'] = $current_year[0]['PORYEAR'];
				$field_appdet['PRLSTNO'] = $maxprlstno[0]['MAXPRLSTNO'];

				$field_appdet['PRDCODE'] = $prd_cd[0];
				$field_appdet['PRDNAME'] = strtoupper($prd_cd[1]);
				$field_appdet['PRDSPEC'] = strtoupper($txt_prdspec[$pdi]);
				$field_appdet['SUBCODE'] = $sprd_cd[0];
				$field_appdet['SUBNAME'] = strtoupper($sprd_cd[1]);
				$field_appdet['TOTLQTY'] = $txt_prdqty[$pdi];
				$field_appdet['TOTLVAL'] = 0;

				if($txt_ad_duration[$pdi] != '') {
					$field_appdet['ADURATI'] = $txt_ad_duration[$pdi];
				} else {
					$field_appdet['ADURATI'] = 0;
				}

				if($txt_size_length[$pdi] != '') {
					$field_appdet['ADLENGT'] = $txt_size_length[$pdi];
				} else {
					$field_appdet['ADLENGT'] = 0;
				}

				if($txt_size_width[$pdi] != '') {
					$field_appdet['ADWIDTH'] = $txt_size_width[$pdi];
				} else {
					$field_appdet['ADWIDTH'] = 0;
				}

				if($txt_print_location[$pdi] != '') {
					$field_appdet['ADLOCAT'] = strtoupper($txt_print_location[$pdi]);
				} else {
					$field_appdet['ADLOCAT'] = 0;
				}

				/* $field_appdet['ADURATI'] = $txt_ad_duration[$pdi];
				$field_appdet['ADLENGT'] = $txt_size_length[$pdi];
				$field_appdet['ADWIDTH'] = $txt_size_width[$pdi];
				$field_appdet['ADLOCAT'] = strtoupper($txt_print_location[$pdi]); */ // 1!!!1@@@==1==1180==
				$field_appdet['UNTCODE'] = $txt_unitcode[$pdi];
				$field_appdet['UNTNAME'] = strtoupper($txt_unitname[$pdi]);
				$field_appdet['USESECT'] = $slt_usage_section[$pdi];

				// Product Image
				if($_FILES['fle_prdimage']['type'][$pdi] == "image/jpeg" or $_FILES['fle_prdimage']['type'][$pdi] == "image/gif" or $_FILES['fle_prdimage']['type'][$pdi] == "image/png" or $_FILES['fle_prdimage']['type'][$pdi] == "application/pdf") {
					$fldimli = find_indicator( $_FILES['fle_prdimage']['type'][$pdi] );
					$imgfile1 = $_FILES['fle_prdimage']['tmp_name'][$pdi];
					if($fldimli == 'i') 
					{ 
						$info = getimagesize($imgfile1);
						$image1 = imagecreatefromjpeg($imgfile1);
						if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
						elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
						elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
						//save it
						imagejpeg($image, $imgfile1, 20);
					}
					
					switch($_FILES['fle_prdimage']['type'][$pdi]) { 
						case 'image/jpeg':
						case 'image/jpg':
						case 'image/gif':
						case 'image/png':
								// echo "##";
								$extn1 = 'jpg';
								break;
						case 'application/pdf':
								// echo "$$";
								$extn1 = 'pdf';
								break;
					}

					$yrdir = $uploadimg;
					$yrfolder_exists = is_dir($yrdir);
					if($yrfolder_exists) { }
					else { 
						if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
					}
					
					$expl = explode(".", $_FILES['fle_prdimage']['name'][$pdi]);
					$upload_img1 = $current_year[0]['PORYEAR']."_".$srno."_".$maxprlstno[0]['MAXPRLSTNO'].".".$extn1;
					$source = $imgfile1;
					$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
					$complogos1 = str_replace(" ", "-", $upload_img1);
					$complogos1 = strtolower($complogos1);
					
					//// Thumb start
					if($fldimli == 'i') 
					{
						// echo "%%";
						$upload_img1_tmp = $current_year[0]['PORYEAR']."_".$srno."_".$maxprlstno[0]['MAXPRLSTNO'].".jpg";
						$source_tmp = $imgfile1;
						$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
						$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
						$complogos1_tmp = strtolower($complogos1_tmp);

						$width = $info[0];
						$height = $info[1];
						$newwidth1=200;
						$newheight1=200;
						$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
						imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);
							
						$resized_file = "uploaded_files/thumb_images/". $complogos1_tmp;
						$dest_thumbfile = "approval_desk/product_images/".$uploadimg."/thumb_images/".$complogos1_tmp;
						imagejpeg($tmp1, $resized_file, 50);
						imagedestroy($image1);
						imagedestroy($tmp1);
						// echo "^^^".$source_tmp."^^".$dest_thumbfile."^^".'<br>';
						$llll = move_uploaded_file($source_tmp, $dest_thumbfile);
						// echo "^^".$llll."^^"; 
						// exit;
						$local_file = "uploaded_files/thumb_images/".$complogos1_tmp;
						$server_file = 'approval_desk/product_images/'.$uploadimg.'/thumb_images/'.$complogos1;
						
						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
										//echo "tmp Succes";
							unlink($local_file);
						}
					}
					//// Thumb end
					// exit;
					
					$original_complogos1 = "../uploads/product_images/".$uploadimg."/".$complogos1;
					// echo '!!!'.$complogos1.'<br>';
					move_uploaded_file($source, $original_complogos1);
					
					/* Upload into FTP */
					$local_file = "../uploads/product_images/".$uploadimg."/".$complogos1;
					$server_file = 'approval_desk/product_images/'.$uploadimg.'/'.$complogos1;
					if ((!$conn_id) || (!$login_result)) {
						$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
						// echo "lar Succes";
						unlink($local_file);
					}
					/* Upload into FTP */
				}
				// Product Image
				$field_appdet['PRDIMAG'] = $complogos1;
				$insert_appdet = insert_test_dbquery($field_appdet, $tbl_appdet);
				//print_r($field_appdet);
				
				$pdii = $pdi + 1;
				$qdii = 1; 
				// Product List - Quotation Adding pdi
				for($qdi = 0; $qdi < count($txt_sltsupcode[$pdii]); $qdi++) {  echo "***";
					$sp_cd = explode(" - ", $txt_sltsupcode[$pdii][$qdi]);
					$maxprlstsr = select_query_json("Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR 
															From APPROVAL_PRODUCT_QUOTATION 
															WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PBDLSNO = '".$maxprlstno[0]['MAXPRLSTNO']."' and PRLSTYR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TEST'); 
					/* echo "<br>Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR 
															From APPROVAL_PRODUCT_QUOTATION 
															WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PBDLSNO = '".$maxprlstno[0]['MAXPRLSTNO']."' and PRLSTYR = '".$current_year[0]['PORYEAR']."' 
																and PRLSTNO = ".$maxarqcode[0][0]." "; */
					$tbl_appdet1 = "APPROVAL_PRODUCT_QUOTATION";
					$field_appdet1 = array();
					$field_appdet1['PBDYEAR'] = $current_year[0]['PORYEAR'];
					$field_appdet1['PBDCODE'] = $srno;
					$field_appdet1['PBDLSNO'] = $maxprlstno[0]['MAXPRLSTNO'];
					$field_appdet1['PRLSTYR'] = $current_year[0]['PORYEAR'];
					$field_appdet1['PRLSTNO'] = $maxprlstno[0]['MAXPRLSTNO'];
					$field_appdet1['PRLSTSR'] = $maxprlstsr[0]['MAXPRLSTSR'];

					$field_appdet1['SUPCODE'] = $sp_cd[0];
					$field_appdet1['SUPNAME'] = strtoupper($sp_cd[1]);
					// echo "**".$txt_sltsupplier[$pdii][0]."**".$qdii."**".$qdi."**".$iqdi."**".$pdii."**";
					if($txt_sltsupplier[$pdii][0] == $qdii) {
						$field_appdet1['SLTSUPP'] = 1;
					} else {
						$field_appdet1['SLTSUPP'] = 0;
					}
					$qdii++;
					if($txt_delivery_duration[$pdii][$qdi] == 0) {
						$field_appdet1['DELPRID'] = 1;
					} else {
						$field_appdet1['DELPRID'] = strtoupper($txt_delivery_duration[$pdii][$qdi]);
					}
					$field_appdet1['PRDRATE'] = $txt_prdrate[$pdii][$qdi];
					$field_appdet1['SGSTVAL'] = $txt_prdsgst[$pdii][$qdi];
					$field_appdet1['CGSTVAL'] = $txt_prdcgst[$pdii][$qdi];
					$field_appdet1['IGSTVAL'] = $txt_prdigst[$pdii][$qdi];
					$field_appdet1['DISCONT'] = $txt_prddisc[$pdii][$qdi];
					$field_appdet1['NETAMNT'] = $hid_prdnetamount[$pdii][$qdi];
					$field_appdet1['SUPRMRK'] = strtoupper($txt_suprmrk[$pdii][$qdi]);
					$field_appdet1['ADVAMNT'] = $txt_advance_amount[$pdii][$qdi];
					// $field_appdet1['NETAMNT'] = 0;

					//echo "++".$_FILES['fle_supquot']['type'][$pdii][$qdi]."++".$_FILES['fle_supquot']['tmp_name'][$pdii][$qdi]."++".$_FILES['fle_supquot']['name'][$pdii][$qdi]."++";
					$fldimli = '-'; $complogos1 = '-'; 
					if($_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/jpeg" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/gif" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/png" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "application/pdf") {
						$fldimli = find_indicator( $_FILES['fle_supquot']['type'][$pdii][$qdi] );
						
						$imgfile1 = $_FILES['fle_supquot']['tmp_name'][$pdii][$qdi];
						if($fldimli == 'i') 
						{ 
							$info = getimagesize($imgfile1);
							$image1 = imagecreatefromjpeg($imgfile1);
							if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
							elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
							elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
							//save it
							imagejpeg($image, $imgfile1, 20);
						}
						
						switch($_FILES['fle_supquot']['type'][$pdii][$qdi]) { 
							case 'image/jpeg':
							case 'image/jpg':
							case 'image/gif':
							case 'image/png':
									// echo "##";
									$extn1 = 'jpg';
									break;
							case 'application/pdf':
									// echo "$$";
									$extn1 = 'pdf';
									break;
						}

						$yrdir = $uploadimg;
						$yrfolder_exists = is_dir($yrdir);
						if($yrfolder_exists) { }
						else { 
							if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
						}
						
						$expl = explode(".", $_FILES['fle_supquot']['name'][$pdii][$qdi]);
						$upload_img1 = $current_year[0]['PORYEAR']."_".$srno."_".$current_year[0]['PORYEAR']."_".$maxprlstno[0]['MAXPRLSTNO']."_".$maxprlstsr[0]['MAXPRLSTSR'].".".$extn1;
						$source = $imgfile1;
						$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
						$complogos1 = str_replace(" ", "-", $upload_img1);
						$complogos1 = strtolower($complogos1);
						
						//// Thumb start
						if($fldimli == 'i') 
						{
							// echo "%%";
							$upload_img1_tmp = $current_year[0]['PORYEAR']."_".$srno."_".$current_year[0]['PORYEAR']."_".$maxprlstno[0]['MAXPRLSTNO']."_".$maxprlstsr[0]['MAXPRLSTSR'].".jpg";
							$source_tmp = $imgfile1;
							$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
							$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
							$complogos1_tmp = strtolower($complogos1_tmp);

							$width = $info[0];
							$height = $info[1];
							$newwidth1=200;
							$newheight1=200;
							$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
							imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);
								
							$resized_file = "uploaded_files/thumb_images/". $complogos1_tmp;
							$dest_thumbfile = "approval_desk/product_quotation/".$uploadimg."/thumb_images/".$complogos1_tmp;
							imagejpeg($tmp1, $resized_file, 50);
							imagedestroy($image1);
							imagedestroy($tmp1);
							// echo "^^^".$source_tmp."^^".$dest_thumbfile."^^".'<br>';
							$llll = move_uploaded_file($source_tmp, $dest_thumbfile);
							// echo "^^".$llll."^^"; 
							// exit;
							$local_file = "uploaded_files/thumb_images/".$complogos1_tmp;
							$server_file = 'approval_desk/product_quotation/'.$uploadimg.'/thumb_images/'.$complogos1;
							
							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "tmp Succes";
								unlink($local_file);
							}
						}
						//// Thumb end
						// exit;
						
						$original_complogos1 = "../uploads/product_quotation/".$uploadimg."/".$complogos1;
						// echo '!!!'.$complogos1.'<br>';
						move_uploaded_file($source, $original_complogos1);
						
						/* Upload into FTP */
						$local_file = "../uploads/product_quotation/".$uploadimg."/".$complogos1;
						$server_file = 'approval_desk/product_quotation/'.$uploadimg.'/'.$complogos1;
						if ((!$conn_id) || (!$login_result)) {
							$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
							// echo "lar Succes";
							unlink($local_file);
						}
						/* Upload into FTP */
					}
					
					$field_appdet1['QUOTFIL'] = $complogos1;
					$field_appdet1['SPLDISC'] = $txt_spldisc[$pdii][$qdi];
					$field_appdet1['PIECLES'] = $txt_pieceless[$pdii][$qdi];
					$insert_appdet1 = insert_test_dbquery($field_appdet1, $tbl_appdet1);
				}
				// Product List - Quotation Adding
			}
			// Product List Adding
		}
	}

	// approval budget planner temp
	$sql_expense = select_query_json("select * from department_asset where depcode=".$sql_reqid[0]['DEPCODE']."", "Centra", 'TEST');
	$maxarsrno = select_query_json("Select nvl(Max(APRSRNO),0)+1 MXAPRSRNO From approval_budget_planner_temp WHERE APRNUMB = '".$apprno."' ", "Centra", 'TEST');
	$mnt_yr_cntmntyr = date("m,Y");
	$mnt_yr_amt_cntmntyr = $txtrequest_value;
	$apmnth = explode(",", $mnt_yr_cntmntyr);
	$tbl_budmode = "approval_budget_planner_temp";
	$field_budmode = array();
	$field_budmode['APRNUMB'] = $apprno;
	$field_budmode['APRSRNO'] = $maxarsrno[0]['MXAPRSRNO'];
	$field_budmode['APRPRID'] = $mnt_yr_cntmntyr;
	$field_budmode['APRMNTH'] = get_month($mnt_yr_cntmntyr);
	$field_budmode['ADDUSER'] = $_SESSION['tcs_usrcode'];
	$field_budmode['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$field_budmode['DELETED'] = 'N'; // Y - Yes; N - No;
	$field_budmode['APPMNTH'] = $apmnth[0];
	$field_budmode['APPYEAR'] = $apmnth[1];
	$field_budmode['TARNUMB'] = $sql_reqid[0]['TARNUMB'];
	$field_budmode['APRYEAR'] = $current_year[0]['PORYEAR'];
	$field_budmode['BRNCODE'] = $sql_reqid[0]['BRNCODE'];
	$field_budmode['APPMODE'] = 'N';
	$field_budmode['EXPSRNO'] = $sql_expense[0]['EXPSRNO'];
	$field_budmode['EXISTVL'] = 0;
	$field_budmode['USEDVAL'] = 6;// slt_submission 6 - reserved budget
	$field_budmode['DEPCODE'] = $sql_reqid[0]['DEPCODE'];
	$field_budmode['ACCVRFY'] = 0; // 0 - NOT VERIFY BY PKN - ACCOUNTS / 1 - VERIFY BY PKN - ACCOUNTS
	$field_budmode['TMTARNO'] = 0; // TEMP. TARGETNO FIELD.. 0 - NOT UPDATE THE TARGET NO (ORIGINAL TARGET NO) / > 0 THIS IS THE EXISTING TARGET NO, NEW TARGET NO IS IN TARGETNUMB
	$field_budmode['APPRVAL'] = floor($mnt_yr_amt_cntmntyr);
	$field_budmode['RESVALU'] = 0;
	$field_budmode['EXTVALU'] = 0;
	$field_budmode['BUDMODE'] = 'R';
	/* $field_budmode['PHDCODE'] = $txt_phphead;
	$field_budmode['PGRCODE'] = $txt_phpgroup;
	$field_budmode['ESECODE'] = $slt_usesec; */
	
	// print_r($field_budmode); echo "<br>";
	$insert_budmode = insert_test_dbquery($field_budmode, $tbl_budmode);

	$next_yr = explode("-", $current_year[0]['PORYEAR']);
	$viewyr = "20".$next_yr[1];
	$begin = new DateTime(date('Y-m'));
	$end = new DateTime($viewyr.'-04');
	$daterange = new DatePeriod($begin, new DateInterval('P1M'), $end); // P1D - incress date P1M - incress mdate
	foreach($daterange as $dates){
	    $view_aa[] = strtoupper($dates->format("M, Y"))."<BR>";
	    $view_bb[] = $dates->format("m,Y")."<BR>";
	}
	// print_r($view_aa);

	// $cur_month = ltrim(date('m'), 0);
	// $crmnth = find_finiancial_year($cur_month));
	for($cntmntyr = 1; $cntmntyr < count($view_aa); $cntmntyr++) {
		$maxarsrno = select_query_json("Select nvl(Max(APRSRNO),0)+1 MXAPRSRNO From approval_budget_planner_temp WHERE APRNUMB = '".$apprno."' ", "Centra", 'TEST');
		$apmnth = explode(",", $view_bb[$cntmntyr]);
		$tbl_budmode = "approval_budget_planner_temp";
		$field_budmode = array();
		$field_budmode['APRNUMB'] = $apprno;
		$field_budmode['APRSRNO'] = $maxarsrno[0]['MXAPRSRNO'];
		$field_budmode['APRPRID'] = $view_bb[$cntmntyr];
		$field_budmode['APRMNTH'] = get_month($view_bb[$cntmntyr]);
		$field_budmode['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$field_budmode['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$field_budmode['DELETED'] = 'N'; // Y - Yes; N - No; 
		$field_budmode['APPMNTH'] = $apmnth[0];
		$field_budmode['APPYEAR'] = $apmnth[1];
		$field_budmode['TARNUMB'] = $sql_reqid[0]['TARNUMB'];
		$field_budmode['APRYEAR'] = $current_year[0]['PORYEAR'];
		$field_budmode['BRNCODE'] = $sql_reqid[0]['BRNCODE'];
		$field_budmode['APPMODE'] = 'N';
		$field_budmode['EXPSRNO'] = $sql_expense[0]['EXPSRNO'];
		$field_budmode['EXISTVL'] = 0;
		$field_budmode['USEDVAL'] = 6; // slt_submission 6 - reserved budget 
		$field_budmode['DEPCODE'] = $sql_reqid[0]['DEPCODE'];
		$field_budmode['ACCVRFY'] = 0; // 0 - NOT VERIFY BY PKN - ACCOUNTS / 1 - VERIFY BY PKN - ACCOUNTS 
		$field_budmode['TMTARNO'] = 0; // TEMP. TARGETNO FIELD.. 0 - NOT UPDATE THE TARGET NO (ORIGINAL TARGET NO) / > 0 THIS IS THE EXISTING TARGET NO, NEW TARGET NO IS IN TARGETNUMB 
		$field_budmode['APPRVAL'] = 0;
		$field_budmode['RESVALU'] = 0;
		$field_budmode['EXTVALU'] = 0;
		$field_budmode['BUDMODE'] = 'R';
		$insert_budmode = insert_dbquery($field_budmode, $tbl_budmode);
		// exit;
	} 
}
if($action == "budget_convert")
{
	$apprno = "MANAGEMENT / 2000001 / 03-04-2018 / 0001 / 10:30 AM";
	$sql_app = select_query_json("select * from approval_request where aprnumb in '".$apprno."'","Centra", 'TEST');
	$sql_app_last = select_query_json("select * from trandata.approval_request  where (aprnumb,arqsrno) in 
											(select aprnumb,max(arqsrno) from trandata.approval_request  where aprnumb like '%".$_REQUEST['txt_aprnumb']."%' group by aprnumb)", "Centra", 'TEST');
	
	$com_code = select_query_json("SELECT COMP.COMCODE,COMP.COMNAME FROM COMPANY COMP,BRANCH_COMPANY BCOM 
											WHERE BCOM.COMCODE=COMP.COMCODE AND BCOM.BRNCODE='".$sql_app[0]['BRNCODE']."' AND COMP.DELETED='N' ORDER BY COMP.COMCODE", "Centra", 'TEST');

	$tarupdate = array();
	$tarupdate['PTVALUE'] = $sql_app_last[0]['APPFVAL'];
	$tarwhere = "brncode = ".$sql_app[0]['BRNCODE']." and ptnumb=".$sql_app[0]['TARNUMB']." and  trunc(sysdate) between trunc(ptfdate) and trunc(pttdate)";
	$tarup = update_dbquery($tarupdate,'non_purchase_target',$tarwhere);

 	// approval budget planner temp to live 
 	$sql_temp = select_query_json("select temp.*, to_char(temp.adddate,'dd/mm/yyyy') addd, to_char(temp.edtdate,'dd/mm/yyyy')edtd, to_char(temp.deldate,'dd/mm/yyyy')deld from approval_budget_planner_temp temp where aprnumb in '".$sql_app[0]['APRNUMB']."' order by aprsrno","Centra","TEST");

 	foreach ($sql_temp as $temp) {
 		$budlive = array();
 		$budlive['APRNUMB']=$temp['APRNUMB'];
		$budlive['APRSRNO']=$temp['APRSRNO'];
		$budlive['APRPRID']=$temp['APRPRID'];
		$budlive['APRMNTH']=$temp['APRMNTH'];
		$budlive['APPRVAL']=$temp['APPRVAL'];
		$budlive['APPMNTH']=$temp['APPMNTH'];
		$budlive['APPYEAR']=$temp['APPYEAR'];
		$budlive['TARNUMB']=$temp['TARNUMB'];
		$budlive['RESVALU']=$temp['RESVALU'];
		$budlive['EXTVALU']=$temp['EXTVALU'];
		$budlive['BUDMODE']=$temp['BUDMODE'];
		$budlive['APRYEAR']=$temp['APRYEAR'];
		$budlive['ADDUSER']=$temp['ADDUSER'];
		$budlive['ADDDATE']=$temp['ADDD'];
		$budlive['EDTUSER']=$temp['EDTUSER'];
		$budlive['EDTDATE']=$temp['EDTD'];
		$budlive['DELETED']=$temp['DELETED'];
		$budlive['DELUSER']=$temp['DELUSER'];
		$budlive['DELDATE']=$temp['DELD'];
		$budlive['BRNCODE']=$temp['BRNCODE'];
		$budlive['APPMODE']=$temp['APPMODE'];
		$budlive['EXPSRNO']=$temp['EXPSRNO'];
		$budlive['EXISTVL']=$temp['EXISTVL'];
		$budlive['USEDVAL']=$temp['USEDVAL'];
		$budlive['DEPCODE']=$sql_app[0]['DEPCODE'];
		$budliveinsert = insert_test_dbquery($budlive,'approval_budget_planner');
 	}
 
 	//enable during live and comment below...
	$sql_prdq = select_query_json("select distinct pbdcode,pbdyear,supcode from approval_product_quotation 
											where pbdcode=".$sql_app[0]['IMUSRIP']." and pbdyear='".$sql_app[0]['ARQYEAR']."' and sltsupp=1","Centra","TEST");

	/*$sql_prdq = select_query_json("select distinct pbdcode,pbdyear,supcode from approval_product_quotation where pbdcode=2002771 and pbdyear='2017-18' and sltsupp=1","Centra","TCS");*/
	foreach ($sql_prdq as $quo) {

		$max_bplnumb = select_query_json("SELECT Nvl(Max(BPLNUMB),0)+1  BPLNUMB FROM BUDGET_PLANNER_SUMMARY WHERE BPLYEAR='".$current_year[0]['PORYEAR']."'","Centra", 'TEST');

		$max_nrqnumb = select_query_json("SELECT Nvl(Max(NRQNUMB),0)+1  NRQNUMB FROM NON_REQ_SUMMARY WHERE NRQYEAR='".$current_year[0]['PORYEAR']."'","Centra", 'TEST');
		
		$sql_quote = select_query_json("select * from approval_product_quotation where pbdyear='".$quo['PBDYEAR']."' and pbdcode=".$quo['PBDCODE']." and supcode=".$quo['SUPCODE']." and sltsupp=1","Centra","TEST");
		
		$bplsum = array();
		$bplsum['BPLYEAR'] = $current_year[0]['PORYEAR'];
		$bplsum['BPLNUMB'] = $max_bplnumb[0]['BPLNUMB'];
		$bplsum['BPLDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
		$bplsum['BPLMODE'] = "R";
		$bplsum['DEPCODE'] = $sql_app[0]['DEPCODE'];
		$bplsum['COMCODE'] = $com_code[0]['COMCODE'];
		$bplsum['SUPCODE'] = $quo['SUPCODE'];
		$bplsum['BPLGRAD'] = "1*";
		$bplsum['BPLDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$bplsum['BPLEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
		$bplsum['TRNCODE'] = $sql_app[0]['TARNUMB'];
		$bplsum['PHDCODE'] = "11";
		$bplsum['PGRCODE'] = "11";
		$bplsum['BPLSDISC'] =0;
		$bplsum['BPLPLESS'] =0;
		$bplsum['BPLREMA'] = $sql_quote[0]['SUPRMRK'];
		$bplsum['LORPAID'] = "N";
		$bplsum['TRANCHR'] = "";
		$bplsum['ERECCHR'] = "";
		$bplsum['LOADCHR'] = "";
		$bplsum['REQSRNO'] = $sql_app[0]['REQSTBY'];
		$bplsum['AUTSRNO'] = $sql_app[0]['REQSTBY'];
		$bplsum['BPLPURP'] = $sql_quote[0]['SUPRMRK'];
		$bplsum['GRDCODE'] = 1;
		$bplsum['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$bplsum['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$bplsum['EDTUSER'] = "";
		$bplsum['EDTDATE'] = "";
		$bplsum['DELETED'] = "N";
		$bplsum['DELUSER'] = "";
		$bplsum['DELDATE'] = "";
		$bplsum['APPNUMB'] = $sql_app[0]['APRNUMB'];
		$bplsum['ORDCODE'] = "";
		$bplsum['CONMODE'] = "";
		$bplsum['CGSTAMT'] = "0";
		$bplsum['SGSTAMT'] = "0";
		$bplsum['IGSTAMT'] = "0";
		$suminsert = insert_test_dbquery($bplsum,"BUDGET_PLANNER_SUMMARY");

		$reqsum = array();
		$reqsum['NRQYEAR'] = $current_year[0]['PORYEAR'];
		$reqsum['NRQNUMB'] = $max_nrqnumb[0]['NRQNUMB'];
		$reqsum['NRQDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
		$reqsum['DEPCODE'] = $sql_app[0]['DEPCODE'];
		$reqsum['COMCODE'] = $com_code[0]['COMCODE'];
		$reqsum['SUPCODE'] = $quo['SUPCODE'];
		$reqsum['NRQGRAD'] = "1*";
		$reqsum['NRQDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$reqsum['NRQEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
		$reqsum['TRNCODE'] = $sql_app[0]['TARNUMB'];
		$reqsum['PHDCODE'] = "11";
		$reqsum['PGRCODE'] = "11";
		$reqsum['NRQSDISC'] = 0;
		$reqsum['NRQPLESS'] = 0;
		$reqsum['NRQREMA'] = $sql_quote[0]['SUPRMRK'];
		$reqsum['LORPAID'] = "N";
		$reqsum['TRANCHR'] = "";
		$reqsum['ERECCHR'] = "";
		$reqsum['LOADCHR'] = "";
		$reqsum['REQSRNO'] = $sql_app[0]['REQSTBY'];
		$reqsum['AUTSRNO'] = $sql_app[0]['REQSTBY'];
		$reqsum['NRQPURP'] = $sql_quote[0]['SUPRMRK'];
		$reqsum['GRDCODE'] = 1;
		$reqsum['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$reqsum['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
		$reqsum['EDTUSER'] = "";
		$reqsum['EDTDATE'] = "";
		$reqsum['DELETED'] = "N";
		$reqsum['DELUSER'] = "";
		$reqsum['DELDATE'] = "";
		$reqsum['BPLYEAR'] = $current_year[0]['PORYEAR'];
		$reqsum['BPLNUMB'] = $max_bplnumb[0]['BPLNUMB'];
		$reqsum['NRQMODE'] = "";
		$reqsum['APPNUMB'] = $sql_app[0]['APRNUMB'];
		$reqsum['CGSTAMT'] = "0";
		$reqsum['SGSTAMT'] = "0";
		$reqsum['IGSTAMT'] = "0";
		$reqsuminsert = insert_test_dbquery($reqsum,"non_req_summary");

		$i=0;
		foreach ($sql_quote as $res) { $i++;
			$sql_prd = select_query_json("select * from approval_productlist where pbdyear='".$res['PBDYEAR']."' and pbdcode='".$res['PBDCODE']."' and pbdlsno=".$res['PBDLSNO']."","Centra","TEST");
			$sql_hsn = select_query_json("select * from trandata.subproduct_asset@tcscentr where prdcode='".$sql_prd[0]['PRDCODE']."' and subcode=".$sql_prd[0]['SUBCODE']."","Centra","TCS");
			$sql_tax = select_query_json("select * from trandata.product_asset_gst_per@tcscentr where prdcode='".$sql_prd[0]['PRDCODE']."' and subcode=".$sql_prd[0]['SUBCODE']."","Centra","TCS");

			if($res['IGSTVAL'] != 0){$txv = $res['IGSTVAL'];}else{$txv =$res['CGSTVAL']+$res['SGSTVAL'];};  
			if($sql_tax[0]['IGSTPER'] != 0){$txp = $sql_tax[0]['IGSTPER'];}else{$txp =$sql_tax[0]['CGSTPER']+$sql_tax[0]['SGSTPER'];};
			
			$bpldet = array();
			$bpldet['BPLYEAR'] = $current_year[0]['PORYEAR'];
			$bpldet['BPLNUMB'] = $max_bplnumb[0]['BPLNUMB'];
			$bpldet['BPLSRNO'] = $i;
			$bpldet['PRDCODE'] = $sql_prd[0]['PRDCODE'];
			$bpldet['SUBCODE'] = $sql_prd[0]['SUBCODE'];
			$bpldet['BPLPRAT'] = $res['PRDRATE'];
			$bpldet['BPLPIEC'] = $sql_prd[0]['TOTLQTY'];
			$bpldet['UNTCODE'] = $sql_prd[0]['UNTCODE'];
			$bpldet['BPLDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$bpldet['BPLEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
			$bpldet['BPLDESC'] = $sql_prd[0]['PRDSPEC'];
			$bpldet['BPLDISC'] = $res['DISCONT'];
			$bpldet['BPLDVAL'] = $res['PRDRATE']*$sql_prd[0]['TOTLQTY']*$res['DISCONT']/100;
			$bpldet['BPLTAXP'] = $txp;
			$bpldet['BPLTAXV'] = $txv;
			$bpldet['BPL_LENGTH']   = $sql_prd[0]['ADLENGT'];
			$bpldet['BPL_WIDTH']    = $sql_prd[0]['ADWIDTH'];
			$bpldet['BPL_LOCATION'] = $sql_prd[0]['ADLOCAT'];
			$bpldet['BPL_DURATION'] = $sql_prd[0]['ADURATI'];
			$bpldet['ESECODE']  = $sql_app[0]['DEPCODE'];
			$bpldet['BPLTECH1'] = "";
			$bpldet['BPLTECH2'] = "";
			$bpldet['BPLTECH3'] = "";
			$bpldet['BPLTECH4'] = "";
			$bpldet['BPLTECH5'] = "";
			$bpldet['CGSTPER'] = $sql_tax[0]['CGSTPER'];
			$bpldet['SGSTPER'] = $sql_tax[0]['SGSTPER'];
			$bpldet['IGSTPER'] = $sql_tax[0]['IGSTPER'];
			$bpldet['CGSTAMT'] = $res['CGSTVAL'];
			$bpldet['SGSTAMT'] = $res['SGSTVAL'];
			$bpldet['IGSTAMT'] = $res['IGSTVAL'];
			$bpldet['HSNCODE'] = $sql_hsn[0]['HSNCODE'];
			$detinsert = insert_test_dbquery($bpldet,"budget_planner_detail");

			$reqdet = array();
			$reqdet['NRQYEAR'] = $current_year[0]['PORYEAR'];
			$reqdet['NRQNUMB'] = $max_nrqnumb[0]['NRQNUMB'];
			$reqdet['NRQSRNO'] = $i;
			$reqdet['PRDCODE'] = $sql_prd[0]['PRDCODE'];
			$reqdet['SUBCODE'] = $sql_prd[0]['SUBCODE'];
			$reqdet['NRQPRAT'] = $res['PRDRATE'];
			$reqdet['NRQPIEC'] = $sql_prd[0]['TOTLQTY'];
			$reqdet['UNTCODE'] = $sql_prd[0]['UNTCODE'];
			$reqdet['NRQDEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$reqdet['NRQEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
			$reqdet['NRQDESC'] = $sql_prd[0]['PRDSPEC'];
			$reqdet['NRQDISC'] = $res['DISCONT'];
			$reqdet['NRQDVAL'] = $res['PRDRATE']*$sql_prd[0]['TOTLQTY']*$res['DISCONT']/100;
			$reqdet['NRQTAXP'] = $txp;
			$reqdet['NRQTAXV'] = $txv;
			$reqdet['NRQ_LENGTH']   = $sql_prd[0]['ADLENGT'];
			$reqdet['NRQ_WIDTH']    = $sql_prd[0]['ADWIDTH'];
			$reqdet['NRQ_LOCATION'] = $sql_prd[0]['ADLOCAT'];
			$reqdet['NRQ_DURATION'] = $sql_prd[0]['ADURATI'];
			$reqdet['PRDCODE_OLD']  = "";
			$reqdet['ESECODE']      = $sql_app[0]['DEPCODE'];
			$reqdet['ACT_NRQPRAT']  = $res['PRDRATE'];
			$reqdet['NRQTECH1'] = "";
			$reqdet['NRQTECH2'] = "";
			$reqdet['NRQTECH3'] = "";
			$reqdet['NRQTECH4'] = "";
			$reqdet['NRQTECH5'] = "";
			$reqdet['CGSTPER'] = $sql_tax[0]['CGSTPER'];
			$reqdet['SGSTPER'] = $sql_tax[0]['SGSTPER'];
			$reqdet['IGSTPER'] = $sql_tax[0]['IGSTPER'];
			$reqdet['CGSTAMT'] = $res['CGSTVAL'];
			$reqdet['SGSTAMT'] = $res['SGSTVAL'];
			$reqdet['IGSTAMT'] = $res['IGSTVAL'];
			$reqdet['HSNCODE'] = $sql_hsn[0]['HSNCODE'];
			$reqdetinsert = insert_test_dbquery($reqdet,"non_req_detail");

			$bplcon = array();
			$bplcon['BPLYEAR'] = $current_year[0]['PORYEAR'];
			$bplcon['BPLNUMB'] = $max_bplnumb[0]['BPLNUMB'];
			$bplcon['BPLSRNO'] = $i;
			$bplcon['BRNCODE'] = $sql_app[0]['BRNCODE'];
			$bplcon['BPLPIEC'] = $sql_prd[0]['TOTLQTY'];
			$bplcon['BPLPIEC_CUR'] = 0;
			$bplcon['BPLPIEC_BAL'] =$sql_prd[0]['TOTLQTY'] ;
			$bplcon['BPLRECV'] = 0;
			$bplcon['BPLRECV_CUR'] = 0;
			$coninsert = insert_test_dbquery($bplcon,"budget_planner_content");

			$reqcon = array();
			$reqcon['NRQYEAR'] = $current_year[0]['PORYEAR'];
			$reqcon['NRQNUMB'] = $max_nrqnumb[0]['NRQNUMB'];
			$reqcon['NRQSRNO'] = $i;
			$reqcon['BRNCODE'] = $sql_app[0]['BRNCODE'];
			$reqcon['NRQPIEC'] = $sql_prd[0]['TOTLQTY'];
			$reqcon['NRQRECV'] = 0;
			$reqconinsert = insert_test_dbquery($reqcon,"non_req_content");	
		}
	}
}
if($action == "po_convert")
{
	$aprnumb = "MANAGEMENT / 2000001 / 03-04-2018 / 0001 / 10:30 AM";
	$sql_nrqs = select_query_json("select * from  non_req_summary where APPNUMB in '".$aprnumb."'","Centra","TEST");
	$sql_appbud = select_query_json("select * from approval_budget_planner_temp where aprnumb in '".$aprnumb."'","Centra","TEST");
	 
	foreach ($sql_nrqs as $summ) {

		$max_ord = select_query_json("select Nvl(Max(NTONUMB),0)+1  NTONUMB FROM non_ord_summary WHERE NTOYEAR='".$current_year[0]['PORYEAR']."'","Centra", 'TEST');
		$nordsumm['NTOYEAR'] = $current_year[0]['PORYEAR'];
		$nordsumm['NTONUMB'] = $max_ord[0]['NTONUMB'];
		$nordsumm['NTODATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$nordsumm['COMCODE'] = $summ['COMCODE'];
		$nordsumm['SUPCODE'] = $summ['SUPCODE'];
		$nordsumm['NTOGRAD'] = $summ['NRQGRAD'];
		$nordsumm['NTODEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$nordsumm['NTOEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
		$nordsumm['TRNCODE'] = $summ['TRNCODE'];
		$nordsumm['PHDCODE'] = $summ['PHDCODE'];
		$nordsumm['PGRCODE'] = $summ['PGRCODE'];
		$nordsumm['NTOSDISC'] = $summ['NRQSDISC'];
		$nordsumm['NTOPLESS'] = $summ['NRQPLESS'];
		$nordsumm['NTOREMA'] = $summ['NRQREMA'];
		$nordsumm['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$nordsumm['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$nordsumm['EDTUSER'] = "";
		$nordsumm['EDTDATE'] = "";
		$nordsumm['DELETED'] = "N";
		$nordsumm['DELUSER'] = "";
		$nordsumm['DELDATE'] = "";
		$nordsumm['LORPAID'] = $summ['LORPAID'];
		$nordsumm['REQNUMB'] = $summ['TARNUMB'];
		$nordsumm['TRANCHR'] = $summ['TRANCHR'];
		$nordsumm['ERECCHR'] = $summ['ERECCHR'];
		$nordsumm['LOADCHR'] = $summ['LOADCHR'];
		$nordsumm['GRDCODE'] = $summ['GRDCODE'];
		$nordsumm['ORDTYPE'] = "";
		$nordsumm['APPNUMB'] = $summ['APPNUMB'];
		$nordsumm['CGSTAMT'] = $summ['CGSTAMT'];
		$nordsumm['SGSTAMT'] = $summ['SGSTAMT'];
		$nordsumm['IGSTAMT'] = $summ['IGSTAMT'];
		$nordsumm_insert = insert_test_dbquery($nordsumm,"non_ord_summary");

		$sql_nreqdet = select_query_json("select * from non_req_detail where nrqyear='".$summ['NRQYEAR']."' and nrqnumb=".$summ['NRQNUMB']." order by nrqsrno","Centra","TEST");
		foreach ($sql_nreqdet as $det) {
			$norddet = array();
			$norddet['NTOYEAR'] = $current_year[0]['PORYEAR'];
			$norddet['NTONUMB'] = $max_ord[0]['NTONUMB'];
			$norddet['NTOSRNO'] = $det['NRQSRNO'];
			$norddet['PRDCODE'] = $det['PRDCODE'];
			$norddet['SUBCODE'] = $det['SUBCODE'];
			$norddet['NTOPRAT'] = $det['NRQPRAT'];
			$norddet['NTOPIEC'] = $det['NRQPIEC'];
			$norddet['UNTCODE'] = $det['UNTCODE'];
			$norddet['NTODEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$norddet['NTOEDDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$lastdate;
			$norddet['NTODESC'] = $det['NRQDESC'];
			$norddet['NTODISC'] = $det['NRQDISC'];
			$norddet['NTODVAL'] = $det['NRQDVAL'];
			$norddet['NTOTAXP'] = $det['NRQTAXP'];
			$norddet['NTOTAXV'] = $det['NRQTAXV'];
			$norddet['NTO_LENGTH'] = $det['NRQ_LENGTH'] ;
			$norddet['NTO_WIDTH'] = $det['NRQ_WIDTH'];
			$norddet['NTO_LOCATION'] = $det['NRQ_LOCATION'];
			$norddet['NTO_DURATION'] = $det['NRQ_DURATION'];
			$norddet['PRDCODE_OLD'] = $det['PRDCODE_OLD'];
			$norddet['ESECODE'] = $det['ESECODE'];
			$norddet['NTOTECH1'] = $det['NRQTECH1'];
			$norddet['NTOTECH2'] = $det['NRQTECH2'];
			$norddet['NTOTECH3'] = $det['NRQTECH3'];
			$norddet['NTOTECH4'] = $det['NRQTECH4'];
			$norddet['NTOTECH5'] = $det['NRQTECH5'];
			$norddet['CGSTPER'] = $det['CGSTPER'];
			$norddet['SGSTPER'] = $det['SGSTPER'];
			$norddet['IGSTPER'] = $det['IGSTPER'];
			$norddet['CGSTAMT'] = $det['CGSTAMT'];
			$norddet['SGSTAMT'] = $det['SGSTAMT'];
			$norddet['IGSTAMT'] = $det['IGSTAMT'];
			$norddet['HSNCODE'] = $det['HSNCODE'];
			$norddetinsert = insert_test_dbquery($norddet,"non_ord_detail");
		}

		$sql_nordcon = select_query_json("select * from non_req_content where nrqyear='".$summ['NRQYEAR']."' and nrqnumb=".$summ['NRQNUMB']." order by nrqsrno","Centra","TEST");
		foreach ($sql_nordcon as $con) {
			$nordcon = array();
			$nordcon['NTOYEAR'] = $current_year[0]['PORYEAR'];
			$nordcon['NTONUMB'] = $max_ord[0]['NTONUMB'];
			$nordcon['NTOSRNO'] = $con['NRQSRNO'];
			$nordcon['BRNCODE'] = $con['BRNCODE'];
			$nordcon['NTOPIEC'] = $con['NRQPIEC'];
			$nordcon['NTORECV'] = $con['NRQRECV'];
			$nordcon['TARYEAR'] = "";
			$nordcon['TARMONT'] = "";
			$nordcon['MATDATE'] = "";
			$nordcon_insert = insert_test_dbquery($nordcon,"non_ord_content");

			$nreqpo = array();
			$nreqpo['NRQYEAR'] = $sql_nrqs[0]['NRQYEAR'];
			$nreqpo['NRQNUMB'] = $sql_nrqs[0]['NRQNUMB'];
			$nreqpo['NRQSRNO'] = $con['NRQSRNO'];
			$nreqpo['NTOYEAR'] = $current_year[0]['PORYEAR'];
			$nreqpo['NTONUMB'] = $max_ord[0]['NTONUMB'];
			$nreqpo['NTOSRNO'] = $con['NRQSRNO'];
			$nreqpo['BRNCODE'] = $con['BRNCODE'];
			$nreqpo['CHKSRNO'] = $sql_appbud[0]['PHDCODE'];
			$nreqpo['APPSRNO'] = $sql_appbud[0]['PGRCODE'];
			$nreqpo['ADDUSER'] = $_SESSION['tcs_usrcode'];
			$nreqpo['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$nreqpoinsert = insert_test_dbquery($nreqpo,"non_req_po_convert");
		}
	}
}
?>