<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	//Checking multiple Owner list
	$branch = explode(" , ",$txt_branch_type);
	$top_core = explode(" - ",$txt_top_core);
	$sub_core = explode(" - ",$txt_core);
	$ledger = explode(" - ",$txt_ledger_name);

	$PRMSCOD = select_query_json("Select nvl(Max(PRMSCOD),0)+1 maxarqcode From approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
	$PRJCODE = select_query_json("Select nvl(Max(PRJCODE),0)+1 maxarqcode From approval_project_master","Centra","TEST");
	$g_table = "approval_project_master";
	$g_fld = array();
	$g_fld['PRMSYER'] = $current_year[0]['PORYEAR'];
	$g_fld['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
	$g_fld['BRN_PRJ'] = $txt_mode_type;//mode of eg B - branch and P - project
	$g_fld['PRJCODE'] = $PRJCODE[0]['MAXARQCODE'];// own create
	$g_fld['PRJNAME'] = strtoupper($txt_project_name);
	$g_fld['BRNCODE'] = $branch[0];
	$g_fld['BRNNAME'] = $branch[1];
	$g_fld['ATCCODE'] = $top_core[0];// topcore id
	$g_fld['TOPCORE'] = $top_core[1]; // topcore txt
	$g_fld['SUBCORE'] = $sub_core[0]; // subcore id
	$g_fld['SUBCRNM'] = $sub_core[1];// subcore txt
	$g_fld['TARNUMB'] = $ledger[0];// subcore txt
	$g_fld['TARNAME'] = $ledger[1];// subcore txt
	$g_fld['IMPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld['DUEDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld['DELETED'] = 'N';
	$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
	print_r($g_fld);

		//Checking multiple Owner list

	for ($i= 0 ; $i < sizeof($txt_project_owner); $i++)
	{

		$p_owner = explode(" - ",$txt_project_owner[$i]);

		$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_owner[0]."'","Centra","TCS");

		$g_table1 = "approval_project_head";
		$g_fld1 = array();
		$g_fld1['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld1['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_fld1['PRJSRNO'] = $PRJSRNO[0]['MAXARQCODE'];
		$g_fld1['PRJTITL'] = '1';//strtoupper($txt_project_name);
		$g_fld1['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
		$g_fld1['EMPCODE'] = $p_owner[0];
		$g_fld1['EMPNAME'] = $p_owner[1];
		$g_fld1['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld1['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld1['DELETED'] = 'N';

		$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
		print_r($g_fld1);

	}
	////Checking multiple head list

	for ($j= 0 ; $j < sizeof($txt_project_head); $j++)
	{
		$p_head = explode(" - ",$txt_project_head[$j]);
		$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_head[0]."'","Centra","TCS");

		$g_table2 = "approval_project_head";
		$g_fld2 = array();
		$g_fld2['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld2['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_fld2['PRJSRNO'] = $PRJSRNO[0]['MAXARQCODE'];
		$g_fld2['PRJTITL'] = '2';//strtoupper($txt_project_name); // reference to approval_project_title table;
		$g_fld2['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
		$g_fld2['EMPCODE'] = $p_head[0];
		$g_fld2['EMPNAME'] = $p_head[1];
		$g_fld2['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld2['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld2['DELETED'] = 'N';

		$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
		print_r($g_fld2);

	}
		////Checking multiple member list

	for ($k= 0 ; $k < sizeof($txt_project_member); $k++)
	{
		$p_member = explode(" - ",$txt_project_member[$k]);
		$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_member[0]."'","Centra","TCS");

		$g_table3 = "approval_project_head";
		$g_fld3 = array();
		$g_fld3['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld3['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_fld3['PRJSRNO'] = $PRJSRNO[0]['MAXARQCODE'];
		$g_fld3['PRJTITL'] = '3';//strtoupper($txt_project_name);
		$g_fld3['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
		$g_fld3['EMPCODE'] = $p_member[0];
		$g_fld3['EMPNAME'] = $p_member[1];
		$g_fld3['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld3['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld3['DELETED'] = 'N';

		$g_insert_subject = insert_test_dbquery($g_fld3,$g_table3);
		print_r($g_fld3);

	}

/*
	if(g_insert_subject == 1){
		echo "Project inserted";
	}else{
		echo "";
	}
*/

?>
