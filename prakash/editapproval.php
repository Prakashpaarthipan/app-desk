<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	
	$projid = $_POST['id1'];
	$prostat = $_POST['id2'];
	
		$g_table = "approval_project_master";
		$where = "PRMSYER = '2018-19' and PRMSCOD ='".$projid."'";
		$g_fld = array();
		
		$g_fld['APRUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['PRJSTAT'] = 'Y';
		
		$update_read = update_test_dbquery($g_fld, $g_table, $where);
		echo $projid;
		echo $prostat;
		
		
		
/*


		for ($l=0 ; $l < sizeof($txt_ledger_name_new); $l++)
	{
		$ledger = explode(" - ",$txt_ledger_name_new[$l]);
				$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		
		//$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_owner[0]."'","Centra","TCS");
		
		$top_sub = select_query_json("Select distinct sec.esecode, substr(sec.esename, 4, 25) esename, am.TOPCORE , atc.ATCNAME From approval_master am , APPROVAL_TOPCORE atc , empsection sec
			where sec.esecode = am.subcore and am.TOPCORE = atc.ATCCODE and am.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' and am.TARNUMB = '".$ledger[0]."'","Centra","TCS");
			
			
		$g_table4 = "approval_project_head";
		$subcore_id = $top_sub[0]['ESECODE'];
		$subcore_name = $top_sub[0]['ESENAME'];
		$top_id = $top_sub[0]['TOPCORE'];
		$top_name = $top_sub[0]['ATCNAME'];
		
		$g_fld4['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld4['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_fld4['PRJSRNO'] = $PRJSRNO[0]['MAXARQCODE'];
		$g_fld4['PRJTITL'] = '4';//strtoupper($txt_project_name); REF TO APPROVAL_PROJECT_TITLE
		$g_fld4['EMPSRNO'] = '0';
		$g_fld4['EMPCODE'] = '0';
		$g_fld4['EMPNAME'] = '0';
		$g_fld4['TARNUMB'] = $ledger[0];// subcore txt
		$g_fld4['TARNAME'] = $ledger[1];// subcore txt
		$g_fld4['PRJVALU'] = $txt_value[$l];// PROJECT VALUE

		$g_fld4['ATCCODE'] = $subcore_id;// topcore id
		$g_fld4['TOPCORE'] = $subcore_name; // topcore txt
		$g_fld4['SUBCORE'] = $top_id; // subcore id
		$g_fld4['SUBCRNM'] = $top_name;// subcore txt

		$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld4['DELETED'] = 'N';

		$g_insert_subject = insert_test_dbquery($g_fld4,$g_table4);
				
		
		$whereledger = "PRMSYER = '2018-19' and PRMSCOD ='".$projid."'";
		$g_fld1 = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld1['APRUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld1['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld1['APRSTAT'] = 'Y';
		$update_read1 = update_test_dbquery($g_fld1, $g_table1, $whereledger);
		//print_r($g_fld1);
	}	


				//Checking multiple Owner list

		//for ($i= 0 ; $i < sizeof($txt_project_owner); $i++)
		//{

		//$p_owner = explode(" - ",$txt_project_owner[$i]);
		$g_table2 = "approval_project_head";
		$whereowner = "PRMSYER = '2018-19' and PRMSCOD ='1' ";
		$g_fld2 = array();
		//$g_fld = ['PRMSCOD'] = 1;
		$g_fld2['DELUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld2['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld2['DELETED'] = 'Y';
		$update_read2 = update_test_dbquery($g_fld2, $g_table2, $whereowner);
				
		print_r($g_fld2);

		//}
	////Checking multiple head list

		//for ($j= 0 ; $j < sizeof($txt_project_head); $j++)
		//{
		//$p_head = explode(" - ",$txt_project_head[$j]);
		
		$g_table3 = "approval_project_head";
		$wherehead = "PRMSYER = '2018-19' and PRMSCOD ='1' ";
		$g_fld3 = array();
		$g_fld3['DELUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld3['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld3['DELETED'] = 'Y';

		//$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
		$update_read3 = update_test_dbquery($g_fld3, $g_table3, $wherehead);
		print_r($g_fld3);
	}
		////Checking multiple member list

	//for ($k= 0 ; $k < sizeof($txt_project_member); $k++)
	//{
		//$p_member = explode(" - ",$txt_project_member[$k]);
		
		$wheremember = "PRMSYER = '2018-19' and PRMSCOD ='1' ";
		$g_table4 = "approval_project_head";
		$g_fld4 = array();
		$g_fld4['DELUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld4['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld4['DELETED'] = 'Y';

		$update_read4 = update_test_dbquery($g_fld4, $g_table4, $wherememeber);
		//$g_insert_subject = insert_test_dbquery($g_fld3,$g_table3);
		print_r($g_fld4);

	}
*/		

echo 'Project Approved';

?>
