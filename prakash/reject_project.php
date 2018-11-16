<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
			//		}
		$projid = $_POST['id'];
		//if($uid != 0 ){
		

		$g_table = "approval_project_master";
		$where = "PRMSYER = '2018-19' and PRMSCOD ='".$projid."'";
		$g_fld = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld['DELUSER'] = $_SESSION['tcs_empsrno'];
		$g_fld['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DELETED'] = 'Y';
		$update_read = update_test_dbquery($g_fld, $g_table, $where);
		print_r($g_fld);



		//for ($l=0 ; $l < sizeof($txt_ledger_name); $l++)
	//{
		//$ledger = explode(" - ",$txt_ledger_name[$l]);
				
		$g_table1 = "approval_project_head";
		$whereledger = "PRMSYER = '2018-19' and PRMSCOD ='".$projid."'";
		$g_fld1 = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld1['DELUSER'] = $_SESSION['tcs_empsrno'];
		$g_fld1['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld1['DELETED'] = 'Y';
		$update_read1 = update_test_dbquery($g_fld1, $g_table1, $whereledger);
		print_r($g_fld1);
		
		
		//Project reject
		$g_history_table = "approval_project_history";
		$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
		$PROJNAME =  select_query_json("SELECT PRJNAME from approval_project_master where PRMSCOD = '".$projid."'","Centra","TEST");
		//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
		
			$rejname = select_query_json("select EMPCODE,EMPNAME,EMPSRNO from approval_project_hierarchy where USRCODE = '".$_SESSION['tcs_empsrno']."'","Centra","TCS");
			
		$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld_history['PRMSCOD'] = $projid;
		$g_fld_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
		//$g_fld_history['PRJSRNO'] = '11';
		//$g_fld_history['PRJTITL'] = '11';
		//$g_fld_history['EMPCODE'] = $_SESSION['tcs_usrcode'];
		//$g_fld_history['EMPNAME'] = $_SESSION['tcs_empname'];
		$g_fld_history['DELUSER'] = $_SESSION['tcs_empsrno'];
		$g_fld_history['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld_history['HSTATUS'] = 'PR';
		$g_fld_history['PSTATUS'] = 'R';
		$g_fld_history['REMARKS'] = 'PROJECT IS REJECTED BY - ' .$rejname[0]['EMPCODE'].' - '.$rejname[0]['EMPNAME'];
		$update_member_history = insert_test_dbquery($g_fld_history,$g_history_table);
	print_r($g_fld_history);
		
		
	//}
			//if(isset($_POST['id']))
			//	{$uid = $_POST['id'];
		
		//}
    // Do whatever you want with the $uid

		echo 'Project is rejected';
	

		

?>
