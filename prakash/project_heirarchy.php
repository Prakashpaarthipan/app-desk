<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
extract($_REQUEST);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
//$PRJHYCD = select_query_json("Select nvl(Max(PRJHYCD),0)+1 maxarqcode From approval_project_hierarchy ","Centra","TEST");
//$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_emp."'","Centra","TCS");
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$approval_id = $_POST['a_id'];
	//$approval_pc = $_POST['a_pc'];
	//$approval_ip = $_POST['a_ip'];
	
	//print_r($_SERVER);
	$host = gethostname();
	//$PRMSCOD = select_query_json("select distinct(ap.PRMSCOD) from approval_project_master ap,approval_project_head ph, //approval_project_hierarchy ah  where ap.PRMSYER =ph.PRMSYER AND ap.prmscod = '".$approval_id."' ORDER BY ","Centra","TCSTEST");
	
		$g_table = "approval_project_hierarchy";
		$where = "PRMSCOD ='".$approval_id."' AND EMPSRNO = '".$_SESSION['tcs_empsrno']."' AND APPSTAT = 'N'";
		$g_fld = array();
		
		$g_fld['APPUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['APPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['APPSYSIP'] = $_SERVER['REMOTE_ADDR'];
		$g_fld['APPSYSNM'] = $host;	//$_SESSION['HOST NAME'];
		$g_fld['APPSTAT'] = 'Y';
		//$g_fld['APPSTAT'] = $txt_project_name;
		
		$update_read = update_test_dbquery($g_fld, $g_table, $where);
		
		print_r($g_fld);
		
		
				
				//$name = select_query_json("select USRNAME,EMPSRNO from userid where USRCODE = '".$_SESSION['tcs_usrcode']."'","Centra","TEST");
				$g_table1 = "approval_project_master";
				$where1 = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$approval_id."' ";
				$g_fld1 = array();
				$g_fld1['CAPPUSR'] = $name[0]['EMPSRNO'];	
				if($_SESSION['tcs_empsrno'] == 21344){
					$g_fld1['PRJSTAT'] = 'A';
					$g_fld1['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_fld1['APRUSER'] = $_SESSION['tcs_empsrno'];
				}
				$update_read = update_test_dbquery($g_fld1, $g_table1, $where1);
				print_r($g_fld1);
				
				$name = select_query_json("select USRNAME,EMPSRNO from userid where USRCODE = '".$_SESSION['tcs_usrcode']."'","Centra","TCS");
		$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
				//$valuehis_m = select_query_json("select * from approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD ='".$pid."'","Centra","TEST");
				$g_table_his_m = "approval_project_history";
				$g__his_m['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g__his_m['PRMSCOD'] = $approval_id;
				$g__his_m['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
				//$g__his_m['PRJNAME'] = $project_name;
				$g__his_m['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g__his_m['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g__his_m['HSTATUS'] = 'L1';
				$g__his_m['PSTATUS'] = 'N';
				$g__his_m['REMARKS'] = 'PROJECT APPROVED BY - ' .$_SESSION['tcs_usrcode'].' - '.$_SESSION['tcs_empname'];
				
				$update_read_his_m = insert_test_dbquery($g__his_m,$g_table_his_m);
		//print_r($_SESSION);
		print_r($g__his_m);


?>