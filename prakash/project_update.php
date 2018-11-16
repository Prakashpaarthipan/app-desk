<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
extract($_REQUEST);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));


//$projectcode = $_POST['pid'];
//$projectname = $_POST['id_name'];


$newLedger = $_POST['fieldArray3'];

//$ledger = $_POST['result'];

	if($action == 'update')
	{
				$project_name = strtoupper($txt_project_name);
				$project_name = trim($project_name);
			//echo $pid;
			
		for ($l=0 ; $l < sizeof($txt_ledger_name); $l++)
			{
				$g_table2 = "approval_project_head";
				$ledger = explode(" - ",$txt_ledger_name[$l]);
				$where_ledger = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'and TARNUMB = '".$ledger[0]."' and PRJTITL = '4'";
				
				$g_fld2['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld2['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				//$g_fld2['TARNUMB'] = trim($ledger[0]);// subcore txt
				//$g_fld2['TARNAME'] = trim($ledger[1]);// subcore txt
				$g_fld2['PRJVALU'] = $txt_values[$l];// PROJECT VALUE
				$update_read = update_test_dbquery($g_fld2, $g_table2, $where_ledger);
				print_r($g_fld2);
				
			}
				//echo $pid;
				// Update to history table start //
				/*
		for ($his=0 ; $his < sizeof($txt_ledger_name); $his++)
			{
				$g_table_his = "approval_project_history";
				$ledger = explode(" - ",$txt_ledger_name[$his]);
				$valuehis = select_query_json("select PRJSRNO,PRJTITL,TARNAME,TARNUMB,PRJVALU from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and TARNUMB = '".$ledger[0]."' and PRMSCOD ='".$pid."' and PRJTITL = '4' ","Centra","TEST");
				$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
				$g_fld_his['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld_his['PRMSCOD'] = $pid;
				$g_fld_his['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
				$g_fld_his['PRJSRNO'] = $valuehis[0] ['PRJSRNO'];
				$g_fld_his['PRJTITL'] = $valuehis[0] ['PRJTITL'];
				$g_fld_his['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_his['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld_his['HSTATUS'] = 'PV';
				$g_fld_his['PSTATUS'] = 'N';
				$g_fld_his['REMARKS'] = 'PROJECT VALUE - ' .$txt_values[$his].' HAS UPDATED';
				//$g_fld_his['PRJVALU'] = $txt_values[$his];// PROJECT VALUE
				$update_read_his = insert_test_dbquery($g_fld_his,$g_table_his);
				print_r($g_fld_his);
				
			}*/
			
			// Update to history table end //
			
			
				/////<!---- Final Approve Table Start--->/////
				$g_table = "approval_project_master";
				$where = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' ";
				$g_fld = array();
				$g_fld['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld['PRJNAME'] = $project_name;
				$update_read = update_test_dbquery($g_fld, $g_table, $where);
				print_r($g_fld);
				
				
				$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
				//$valuehis_m = select_query_json("select * from approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD ='".$pid."'","Centra","TEST");
				$g_table_his_m = "approval_project_history";
				$g__his_m['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g__his_m['PRMSCOD'] = $pid;
				$g__his_m['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
				$g__his_m['PRJNAME'] = $project_name;
				$g__his_m['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g__his_m['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g__his_m['HSTATUS'] = 'PN';
				$g__his_m['PSTATUS'] = 'N';
				$g__his_m['REMARKS'] = 'PROJECT NAME - ' .$project_name.' HAS ALTERED';
				
				$update_read_his_m = insert_test_dbquery($g__his_m,$g_table_his_m);
				
				//print_r($ledger);
				//print_r($txt_ledger_name);
				//echo $pid;
				//echo $txt_project_name;
				//echo $pname;
				
				/////<!---- Final Approve Table End --->/////
			
    
	}


	$prjsno = select_query_json("SELECT COUNT(PRJSRNO) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST");
	$prjsno = $prjsno [0]['COUNT(PRJSRNO)'];
	
	$ledgerCount = select_query_json("SELECT COUNT(PRJTITL) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST");
	
	
	///  New Table Query  ///
	
if($action == 'newentry')
	{
		
		
		//print_r($ledgerCount);
		//print_r($newledgeritems);
		/*
		for ($nledger = 0; $nledger){
		$ledgerNew = explode(" , ",$newledgeritems[$nl]);
		}*/
		$ledgerdecode = json_decode($newledgeritems);
		print_r($ledgerdecode);
		//echo sizeof($Decode);
		//echo "\n";
		//print_r($Decode);
		if (!empty($ledgerdecode)) {
     // list is empty.

				for ($nl=0 ; $nl < sizeof($ledgerdecode) ; $nl++)
					
				{
					
					$ledger = explode(" - ",$ledgerdecode[$nl], 2);
					
					$top_sub = select_query_json("Select distinct sec.esecode, substr(sec.esename, 4, 25) esename, am.TOPCORE , atc.ATCNAME From approval_master am , APPROVAL_TOPCORE atc , empsection sec
						where sec.esecode = am.subcore and am.TOPCORE = atc.ATCCODE and am.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' and am.TARNUMB = '".$ledger[0]."'","Centra","TCS");
						//$prjsno = count($prjsno);
					$subcore_id = $top_sub[0]['ESECODE'];
					$subcore_name = $top_sub[0]['ESENAME'];
					$top_id = $top_sub[0]['TOPCORE'];
					$top_name = $top_sub[0]['ATCNAME'];
					$g_table4 = "approval_project_head";
					$g_fld4 = array();
					$g_fld4['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_fld4['PRMSCOD'] = $pid;
					$g_fld4['PRJSRNO'] = $prjsno+1+$nl;
					$g_fld4['PRJTITL'] = '4';//strtoupper($txt_project_name); REF TO APPROVAL_PROJECT_TITLE
					$g_fld4['EMPSRNO'] = '0';
					$g_fld4['EMPCODE'] = '0';
					$g_fld4['EMPNAME'] = '0';
					$g_fld4['TARNUMB'] = $ledger[0];// subcore txt
					$g_fld4['TARNAME'] = $ledger[1];// subcore txt
					$g_fld4['PRJVALU'] = '0';// PROJECT VALUE
					$g_fld4['ATCCODE'] = $top_id;// topcore id
					$g_fld4['TOPCORE'] = $top_name; // topcore txt
					$g_fld4['SUBCORE'] = $subcore_id; // subcore id
					$g_fld4['SUBCRNM'] = $subcore_name;// subcore txt
					$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_fld4['DELETED'] = 'N';
					$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_insert_subject = insert_test_dbquery($g_fld4,$g_table4);
					print_r($g_fld4);
					//echo $prjsno;
				
				}
				
					$newvalues = json_decode($newledgervalues);
					for ($v=0 ; $v < sizeof($newvalues) ; $v++)
					
				{
					/*$prjsno = select_query_json("SELECT COUNT(PRJSRNO) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST"); and PRJSRNO = '".$prjsno+1+$v."'
					$prjsno = $prjsno [0]['COUNT(PRJSRNO)'];*/
					$g_table5 = "approval_project_head";
					$prjsno = $prjsno + 1 + $v;
					$where = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and PRJVALU = '0' and PRJSRNO = '".$prjsno."' ";
					$g_fld5 = array();
					$g_fld5['PRJVALU'] = $newvalues[$v];
					$update_read = update_test_dbquery($g_fld5, $g_table5, $where);
					$prjsno = $prjsno - 1;
					print_r($g_fld5);
				}
				
				// History Table Adding Ledger New Start //
				for ($hnl=0 ; $hnl < sizeof($ledgerdecode) ; $hnl++)
					
				{
					$Hledger = explode(" - ",$ledgerdecode[$hnl]);
					$g_led_history_table = "approval_project_history";
					$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
					//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
					$ledgerDetails = select_query_json("select PRJSRNO,PRJTITL,TARNAME,TARNUMB,PRJVALU from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and TARNUMB = '".$Hledger[0]."' and PRMSCOD ='".$pid."'","Centra","TEST");
					$g_led_history['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_led_history['PRMSCOD'] = $pid;
					$g_led_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
					$g_led_history['PRJSRNO'] = $ledgerDetails[0] ['PRJSRNO'];
					$g_led_history['PRJTITL'] = $ledgerDetails[0] ['PRJTITL'];
					$g_led_history['TARNAME'] = $ledgerDetails[0] ['TARNAME'];
					$g_led_history['TARNUMB'] = $ledgerDetails[0] ['TARNUMB'];
					$g_led_history['PRJVALU'] = $ledgerDetails[0] ['PRJVALU'];
					$g_led_history['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_led_history['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_led_history['HSTATUS'] = 'NL';
					$g_led_history['PSTATUS'] = 'N';
					$g_led_history['REMARKS'] = 'NEW LEDGER DATA - '.$Hledger[0].'-'.$Hledger[1].' HAS ADDED';
					$insert_history = insert_test_dbquery($g_led_history,$g_led_history_table);
					print_r($g_led_history);
					
				}
				
				//Flow User Alter
				$PRJHYCD1 = select_query_json("Select nvl(Max(PRJHYCD),0)+1 MAXARQCODE From approval_project_hierarchy where PRMSCOD ='".$pid."'  ","Centra","TEST");
				$SRNO1 = select_query_json("Select nvl(Max(APPSRNO),0)+1 MAXARQCODE From approval_project_hierarchy where PRMSCOD ='".$pid."'  ","Centra","TEST");
				$PRJ = $PRJHYCD1[0]['MAXARQCODE'];
				$srno =$SRNO1[0]['MAXARQCODE'];
				//print_r($APPROVE);
				$flow = explode("~~",$idlist1);
				//echo count($flow);
				print_r($idlist1);
				for($d = 0 ; $d <sizeof($flow); $d++)
				{
				$flowch = select_query_json("Select EMPCODE From approval_project_hierarchy where PRMSCOD ='".$pid."' and EMPCODE ='".trim($flow[$d])."'   ","Centra","TEST");
				echo "Select EMPCODE From approval_project_hierarchy where PRMSCOD ='".$pid."' and EMPCODE ='".trim($flow[$d])."'";
				
					if(count($flowch) != 1){
					$APPROVE = select_query_json("select EMPSRNO, EMPNAME, EMPCODE from employee_office where EMPCODE = '".trim($flow[$d])."' " ,"Centra","TCS");
					
					echo "select EMPSRNO, EMPNAME, EMPCODE from employee_office where EMPCODE = '".trim($flow[$d])."' ";
					$g_hie_table5 = "approval_project_hierarchy";
					$g_hie_table['PRMSCOD'] = $pid;
					$g_hie_table['PRJHYCD'] = $PRJ;
					$g_hie_table['EMPSRNO'] = $APPROVE[0]['EMPSRNO'];
					$g_hie_table['EMPCODE'] = $APPROVE[0]['EMPCODE'];
					$g_hie_table['EMPNAME'] = trim($APPROVE[0]['EMPNAME']);
					$g_hie_table['APPSRNO'] = $d+1;
					$g_hie_table['APPSTAT'] = 'N';
					$g_hie_table['DELETED'] = 'N';
					$g_insert_subject = insert_test_dbquery($g_hie_table,$g_hie_table5);
					$PRJ += 1;
					print_r ($g_hie_table);
					}else{
						$g_hie_table1=array();
						$g_hie_table1['APPSRNO'] = $d+1;
						$where1="PRMSCOD ='".$pid."' and  EMPCODE ='".trim($flow[$d])."'";
						$update_read = update_test_dbquery($g_hie_table1, $g_hie_table5, $where1);
					}
				}
						//Flow User End
		}
		
        // History Table Adding Ledger End //
		
		

		
		$ownerdecode = json_decode($newowner);
		
		$prjslownerno = select_query_json("SELECT COUNT(PRJSRNO) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST");
		$prjslownerno = $prjslownerno [0]['COUNT(PRJSRNO)'];
		echo $prjslownerno;
		if(sizeof($ownerdecode) != 0){
		for ($i= 0 ; $i < sizeof($ownerdecode); $i++)
				{
					$p_owner = explode(" - ",$ownerdecode[$i]);
					//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
					$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_owner[0]."'","Centra","TCS");
					$g_table1 = "approval_project_head";
					$g_fld1 = array();
					$prjslnoonwer = $i+$prjslownerno+1;
					$g_fld1['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_fld1['PRMSCOD'] = $pid;//$PRMSCOD[0]['MAXARQCODE'];
					$g_fld1['PRJSRNO'] = $prjslnoonwer;
					$g_fld1['PRJTITL'] = '1';//strtoupper($txt_project_name); REF TO APPROVAL_PROJECT_TITLE
					$g_fld1['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
					$g_fld1['EMPCODE'] = $p_owner[0];
					$g_fld1['EMPNAME'] = $p_owner[1];
					$g_fld1['TARNUMB'] = '0';// subcore txt
					$g_fld1['TARNAME'] = '0';// subcore txt
					$g_fld1['PRJVALU'] = '0';
					$g_fld1['ATCCODE'] = '0';// topcore id
					$g_fld1['TOPCORE'] = '0'; // topcore txt
					$g_fld1['SUBCORE'] = '0'; // subcore id
					$g_fld1['SUBCRNM'] = '0';// subcore txt
					$g_fld1['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld1['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_fld1['DELETED'] = 'N';
					$g_fld1['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
					print_r($g_fld1);
					
				}
				
				// History Table Adding Owner New Start //
				for ($hno=0 ; $hno < sizeof($ownerdecode) ; $hno++)
					
				{
					$Howner = explode(" - ",$ownerdecode[$hno]);
					$g_own_history_table = "approval_project_history";
					$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
					//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
					$ownerDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$Howner[0]."' and PRMSCOD = '".$pid."'","Centra","TEST");
					$g_own_history['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_own_history['PRMSCOD'] = $pid;
					$g_own_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
					$g_own_history['PRJSRNO'] = $ownerDetails[0] ['PRJSRNO'];
					$g_own_history['PRJTITL'] = $ownerDetails[0] ['PRJTITL'];
					$g_own_history['EMPCODE'] = $ownerDetails[0] ['EMPCODE'];
					$g_own_history['EMPNAME'] = $ownerDetails[0] ['EMPNAME'];
					$g_own_history['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_own_history['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_own_history['HSTATUS'] = 'NO';
					$g_own_history['PSTATUS'] = 'N';
					$g_own_history['REMARKS'] = 'NEW PROJECT OWNER - ' .$Howner[0]. '-' .$Howner[1]. ' HAS ADDED';
					$update_read = insert_test_dbquery($g_own_history,$g_own_history_table);
					print_r($g_own_history);
					
				}
				
		}
				// History Table Adding Owner End //
				
				
				
			$headdecode = json_decode($newhead);
			$prjslheadno = select_query_json("SELECT COUNT(PRJSRNO) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST");
			$prjslheadno = $prjslheadno [0]['COUNT(PRJSRNO)'];
			for ($j= 0 ; $j < sizeof($headdecode); $j++)
				{
					$p_head = explode(" - ",$headdecode[$j]);
					//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
					$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_head[0]."'","Centra","TCS");
					$g_table2 = "approval_project_head";
					$g_fld2 = array();
					$prjslnohead = $j+$prjslheadno+1;
					$g_fld2['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_fld2['PRMSCOD'] = $pid;
					$g_fld2['PRJSRNO'] = $prjslnohead;
					$g_fld2['PRJTITL'] = '2';//strtoupper($txt_project_name); // reference to approval_project_title table;
					$g_fld2['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
					$g_fld2['EMPCODE'] = $p_head[0];
					$g_fld2['EMPNAME'] = $p_head[1];
					$g_fld2['TARNUMB'] = '0';// subcore txt
					$g_fld2['TARNAME'] = '0';// subcore txt
					$g_fld2['PRJVALU'] = '0';
					$g_fld2['ATCCODE'] = '0';// topcore id
					$g_fld2['TOPCORE'] = '0'; // topcore txt
					$g_fld2['SUBCORE'] = '0'; // subcore id
					$g_fld2['SUBCRNM'] = '0';// subcore txt
					$g_fld2['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld2['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_fld2['DELETED'] = 'N';
					$g_fld2['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld2['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
					print_r($g_fld2);
					
				}
				
				
					// History Table Adding Head New Start //
				for ($hnh=0 ; $hnh < sizeof($headdecode) ; $hnh++)
					
				{
					$Hhead = explode(" - ",$headdecode[$hnh]);
					$g_hed_history_table = "approval_project_history";
					$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
					//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
					$headDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$Hhead[0]."' and PRMSCOD = '".$pid."'","Centra","TEST");
					$g_hed_history['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_hed_history['PRMSCOD'] = $pid;
					$g_hed_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
					$g_hed_history['PRJSRNO'] = $headDetails[0] ['PRJSRNO'];
					$g_hed_history['PRJTITL'] = $headDetails[0] ['PRJTITL'];
					$g_hed_history['EMPCODE'] = $headDetails[0] ['EMPCODE'];
					$g_hed_history['EMPNAME'] = $headDetails[0] ['EMPNAME'];
					$g_hed_history['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_hed_history['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_hed_history['HSTATUS'] = 'NH';
					$g_hed_history['PSTATUS'] = 'N';
					$g_hed_history['REMARKS'] = 'NEW PROJECT HEAD - ' .$Hhead[0].'-' .$Hhead[1].' HAS ADDED';
					$update_read = insert_test_dbquery($g_hed_history,$g_hed_history_table);
					print_r($g_hed_history);
					
				}
		
		
					// History Table Adding Head End //*/
		
			$memberdecode = json_decode($newmember);
			$prjslmemberno = select_query_json("SELECT COUNT(PRJSRNO) FROM approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."'","Centra","TEST");	
			$prjslmemberno = $prjslmemberno [0]['COUNT(PRJSRNO)'];	
			for ($k= 0 ; $k < sizeof($memberdecode); $k++)
				{
					$p_member = explode(" - ",$memberdecode[$k]);
					//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
					$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_member[0]."'","Centra","TCS");
					$g_table3 = "approval_project_head";
					$g_fld3 = array();
					$prjslnomember = $k+$prjslmemberno+1;
					$g_fld3['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_fld3['PRMSCOD'] = $pid;
					$g_fld3['PRJSRNO'] = $prjslnomember;
					$g_fld3['PRJTITL'] = '3';//strtoupper($txt_project_name); REF TO APPROVAL_PROJECT_TITLE
					$g_fld3['EMPSRNO'] = $EMPSRNO[0]['EMPSRNO'];
					$g_fld3['EMPCODE'] = $p_member[0];
					$g_fld3['EMPNAME'] = $p_member[1];
					$g_fld3['TARNUMB'] = '0';// subcore txt
					$g_fld3['TARNAME'] = '0';// subcore txt
					$g_fld3['PRJVALU'] = '0';
					$g_fld3['ATCCODE'] = '0';// topcore id
					$g_fld3['TOPCORE'] = '0'; // topcore txt
					$g_fld3['SUBCORE'] = '0'; // subcore id
					$g_fld3['SUBCRNM'] = '0';// subcore txt
					$g_fld3['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld3['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_fld3['DELETED'] = 'N';
					$g_fld3['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_fld3['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_insert_subject = insert_test_dbquery($g_fld3,$g_table3);
					print_r($g_fld3);
				}
				
				// History Table Adding Member New Start //
			for ($hnm=0 ; $hnm < sizeof($memberdecode) ; $hnm++)
					
				{
					$Hmember = explode(" - ",$memberdecode[$hnm]);
					$g_mem_history_table = "approval_project_history";
					$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
					//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
					$memberDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$Hmember[0]."'and PRMSCOD = '".$pid."'","Centra","TEST");
					$g_mem_history['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_mem_history['PRMSCOD'] = $pid;
					$g_mem_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
					$g_mem_history['PRJSRNO'] = $memberDetails[0] ['PRJSRNO'];
					$g_mem_history['PRJTITL'] = $memberDetails[0] ['PRJTITL'];
					$g_mem_history['EMPCODE'] = $memberDetails[0] ['EMPCODE'];
					$g_mem_history['EMPNAME'] = $memberDetails[0] ['EMPNAME'];
					$g_mem_history['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_mem_history['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_mem_history['HSTATUS'] = 'NM';
					$g_mem_history['PSTATUS'] = 'N';
					$g_mem_history['REMARKS'] = 'NEW PROJECT MEMBER - ' .$Hmember[0].'-' .$Hmember[1].' HAS ADDED';
					$update_read = insert_test_dbquery($g_mem_history,$g_mem_history_table);
					print_r($g_mem_history);
					
				}
				
				// Flow Process
				$approveUser = select_query_json("select EMPCODE,EMPNAME,EMPSRNO from approval_project_hierarchy where PRMSCOD = '".$pid."' and deleted = 'N' order by APPSRNO","Centra","TEST");
				$a = array();
				foreach($approveUser as $ap){
					foreach($ap as $key => $value){
						$a[]=$value;
					}
				}
					
						if(in_array($_SESSION['tcs_empsrno'],$a)){
					$allow = 1;
					
						}
						else{
							$allow = 0;
							
						}
			// End
				
				
				if($allow == 1)  {
					echo "####################";
					
					$host = gethostname();
			//$PRMSCOD = select_query_json("select distinct(ap.PRMSCOD) from approval_project_master ap,approval_project_head ph, //approval_project_hierarchy ah  where ap.PRMSYER =ph.PRMSYER AND ap.prmscod = '".$approval_id."' ORDER BY ","Centra","TCSTEST");
			
				$g_table = "approval_project_hierarchy";
				$where = "PRMSCOD ='".$pid."' AND EMPSRNO = '".$_SESSION['tcs_empsrno']."' AND APPSTAT = 'N'";
				$g_fld = array();
				
				$g_fld['APPUSER'] = $_SESSION['tcs_empsrno'];
				$g_fld['APPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld['APPSYSIP'] = $_SERVER['REMOTE_ADDR'];
				$g_fld['APPSYSNM'] = $host;	//$_SESSION['HOST NAME'];
				$g_fld['APPSTAT'] = 'Y';
				//$g_fld['APPSTAT'] = $txt_project_name;
				
				$update_read = update_test_dbquery($g_fld, $g_table, $where);
				
				print_r($g_fld);
				
				// Have to check whenflow user has changed!!!!!!!!!
				
				//$name = select_query_json("select USRNAME,EMPSRNO from userid where USRCODE = '".$_SESSION['tcs_empsrno']."'","Centra","TCS");
				$name = select_query_json("select EMPNAME,EMPSRNO ,APPSRNO from approval_project_hierarchy where EMPSRNO = '".$_SESSION['tcs_empsrno']."' and PRMSCOD='".$pid."' and deleted = 'N' order by APPSRNO","Centra","TEST");
				$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
						//$valuehis_m = select_query_json("select * from approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD ='".$pid."'","Centra","TEST");
						
						$cLevel = $name[0]['APPSRNO']+1;
						$name1 = select_query_json("select EMPSRNO  from approval_project_hierarchy where APPSRNO = '".$cLevel."' and PRMSCOD='".$pid."' and deleted='N' order by APPSRNO","Centra","TEST");
						
						
						
						
						
						//$name = select_query_json("select USRNAME,EMPSRNO from userid where USRCODE = '".$_SESSION['tcs_empsrno']."'","Centra","TEST");
						$g_table1 = "approval_project_master";
						$where1 = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' ";
						$g_fld1 = array();
						$g_fld1['CAPPUSR'] = $name1[0]['EMPSRNO'];	
						if($_SESSION['tcs_empsrno'] == 21344){
							$g_fld1['PRJSTAT'] = 'A';
							$g_fld1['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
							$g_fld1['APRUSER'] = $_SESSION['tcs_empsrno'];
						}
						$update_read = update_test_dbquery($g_fld1, $g_table1, $where1);
						print_r($g_fld1);

							// History Table APPROVAL Start //
							$g_table_his_m = "approval_project_history";
						$g__his_m['PRMSYER'] = $current_year[0]['PORYEAR'];
						$g__his_m['PRMSCOD'] = $pid;
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
			
					
				}
						
				
	}
	
	



if($action == 'removeledger'){

	$g_table4 = "approval_project_head";
	$where = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
	$g_fld4 = array();
	$g_fld4['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld4['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld4['DELETED'] = 'Y';
	$update_read = update_test_dbquery($g_fld4, $g_table4, $where);
	print_r($g_fld4);
	
	// History Table Remove Ledget start//
	$g_history_table = "approval_project_history";
	$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history","Centra","TEST");
	//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
	$ledgerDetails = select_query_json("select PRJSRNO,PRJTITL,TARNAME,PRJVALU from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and TARNUMB = '".$ledgerdata."' and PRMSCOD = '".$pid."'","Centra","TEST");
	$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
	$g_fld_history['PRMSCOD'] = $pid;
	$g_fld_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
	$g_fld_history['PRJSRNO'] = $ledgerDetails[0] ['PRJSRNO'];
	$g_fld_history['PRJTITL'] = $ledgerDetails[0] ['PRJTITL'];
	$g_fld_history['TARNAME'] = $ledgerDetails[0] ['TARNAME'];
	$g_fld_history['PRJVALU'] = $ledgerDetails[0] ['PRJVALU'];
	$g_fld_history['TARNUMB'] = $ledgerdata;
	$g_fld_history['DELUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld_history['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld_history['HSTATUS'] = 'RL';
	$g_fld_history['PSTATUS'] = 'N';
	$g_fld_history['REMARKS'] = 'LEDGER DATA - ' .$ledgerdata.'-'.$ledgerDetails[0] ['TARNAME']. ' HAS REMOVED';
	$update_read_ledger = insert_test_dbquery($g_fld_history,$g_history_table);
	print_r($g_fld_history);
	
	// History Table Remove Ledget end//
}

if($action == 'removeowner'){

	$g_table5 = "approval_project_head";
	$where = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and EMPCODE = '".$ownerdata."' ";
	$g_fld5 = array();
	$g_fld5['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld5['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld5['DELETED'] = 'Y';
	$update_read = update_test_dbquery($g_fld5, $g_table5, $where);
	print_r($g_fld5);
	
	// History Table remove Owner Start //
	$g_history_table = "approval_project_history";
	$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
	//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
	$ownerDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$ownerdata."' and PRMSCOD = '".$pid."' ","Centra","TEST");
	$ownername = select_query_json("select EMPNAME from employee_office where EMPCODE = '".$ownerdata."'","Centra","TCS");
	$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
	$g_fld_history['PRMSCOD'] = $pid;
	$g_fld_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
	$g_fld_history['PRJSRNO'] = $ownerDetails[0] ['PRJSRNO'];
	$g_fld_history['PRJTITL'] = $ownerDetails[0] ['PRJTITL'];
	$g_fld_history['EMPCODE'] = $ownerDetails[0] ['EMPCODE'];
	$g_fld_history['EMPNAME'] = $ownerDetails[0] ['EMPNAME'];
	$g_fld_history['DELUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld_history['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld_history['HSTATUS'] = 'RO';
	$g_fld_history['PSTATUS'] = 'N';
	$g_fld_history['REMARKS'] = 'PROJECT OWNER - ' .$ownerdata. '-'.$ownername[0]['EMPNAME'].' HAS REMOVED';
	$update_history = insert_test_dbquery($g_fld_history,$g_history_table);
	print_r($g_fld_history);
	
	// History Table remove Owner End //
}

if($action == 'removehead'){

	$g_table6 = "approval_project_head";
	$where = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and EMPCODE = '".$headdata."' ";
	$g_fld6 = array();
	$g_fld6['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld6['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld6['DELETED'] = 'Y';
	$update_read = update_test_dbquery($g_fld6, $g_table6, $where);
	print_r($g_fld6);
	
	// History Table remove Head Start //
	$g_history_table = "approval_project_history";
	$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
	//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
	$headDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$headdata."' and PRMSCOD = '".$pid."' ","Centra","TEST");
	$headname = select_query_json("select EMPNAME from employee_office where EMPCODE = '".$headdata."'","Centra","TCS");
	$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
	$g_fld_history['PRMSCOD'] = $pid;
	$g_fld_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
	$g_fld_history['PRJSRNO'] = $headDetails[0] ['PRJSRNO'];
	$g_fld_history['PRJTITL'] = $headDetails[0] ['PRJTITL'];
	$g_fld_history['EMPCODE'] = $headDetails[0] ['EMPCODE'];
	$g_fld_history['EMPNAME'] = $headDetails[0] ['EMPNAME'];
	$g_fld_history['DELUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld_history['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld_history['HSTATUS'] = 'RH';
	$g_fld_history['PSTATUS'] = 'N';
	$g_fld_history['REMARKS'] = 'PROJECT HEAD - ' .$headdata. '-'.$headname[0]['EMPNAME'].' HAS REMOVED';
	$update_owner_history = insert_test_dbquery($g_fld_history,$g_history_table);
	print_r($g_fld_history);
	
	// History Table remove Head End //
}

if($action == 'removemember'){

	$g_table7 = "approval_project_head";
	$where = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and EMPCODE = '".$memberdata."' ";
	$g_fld7 = array();
	$g_fld7['EDTUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld7['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld7['DELETED'] = 'Y';
	$update_read = update_test_dbquery($g_fld7, $g_table7, $where);
	print_r($g_fld7);
	
	// History Table remove Member Start //
	$g_history_table = "approval_project_history";
	$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
	//$where_history = "PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and TARNUMB = '".$ledgerdata."' ";
	$memberDetails = select_query_json("select PRJSRNO,PRJTITL,EMPCODE,EMPNAME from approval_project_head where PRMSYER = '".$current_year[0]['PORYEAR']."' and EMPCODE = '".$memberdata."' and PRMSCOD = '".$pid."' ","Centra","TEST");
		$membername = select_query_json("select EMPNAME from employee_office where EMPCODE = '".$memberdata."'","Centra","TCS");
		
	$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
	$g_fld_history['PRMSCOD'] = $pid;
	$g_fld_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
	$g_fld_history['PRJSRNO'] = $memberDetails[0] ['PRJSRNO'];
	$g_fld_history['PRJTITL'] = $memberDetails[0] ['PRJTITL'];
	$g_fld_history['EMPCODE'] = $memberDetails[0] ['EMPCODE'];
	$g_fld_history['EMPNAME'] = $memberDetails[0] ['EMPNAME'];
	$g_fld_history['DELUSER'] = $_SESSION['tcs_usrcode'];
	$g_fld_history['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$g_fld_history['HSTATUS'] = 'RM';
	$g_fld_history['PSTATUS'] = 'N';
	$g_fld_history['REMARKS'] = 'PROJECT MEMBER - ' .$memberdata.'-'.$membername[0]['EMPNAME']. ' HAS REMOVED';
	$update_member_history = insert_test_dbquery($g_fld_history,$g_history_table);
	print_r($g_fld_history);
	
	// History Table remove Member End //
	
}


if($action == 'duedate'){
	$g_table_due = "approval_project_master";
				$where_due = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' ";
				$g_fld_due = array();
				$g_fld_due['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_due['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld_due['DUEDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$project_date;
				$update_read = update_test_dbquery($g_fld_due, $g_table_due, $where_due);
				print_r($g_fld_due);
				
				$g_history_table = "approval_project_history";
				$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
				$g_fld_fue_his['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld_fue_his['PRMSCOD'] = $pid;
				$g_fld_fue_his['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
				//$g_fld_fue_his['PRJSRNO'] = $memberDetails[0] ['PRJSRNO'];
				$g_fld_fue_his['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_fue_his['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld_fue_his['HSTATUS'] = 'DD';
				$g_fld_fue_his['PSTATUS'] = 'N';
				$g_fld_fue_his['REMARKS'] = 'PROJECT DUEDATE - '.$project_date.' HAS UPDATED';
				$update_due = insert_test_dbquery($g_fld_fue_his,$g_history_table);
				print_r($g_fld_fue_his);
				
}
if($action == 'priorityChange'){
				
				$g_table_level = "approval_project_master";
				$where_level = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' ";
				$g_fld_level = array();
				$g_fld_level['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_level['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld_level['PRIMODE'] = $level;
				$update_read = update_test_dbquery($g_fld_level, $g_table_level, $where_level);
				print_r($g_fld_level);
				
				$g_history_table = "approval_project_history";
				$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
				$g_fld_fue_his['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld_fue_his['PRMSCOD'] = $pid;
				$g_fld_fue_his['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
				//$g_fld_fue_his['PRJSRNO'] = $memberDetails[0] ['PRJSRNO'];
				$g_fld_fue_his['EDTUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_fue_his['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld_fue_his['HSTATUS'] = 'PL';
				$g_fld_fue_his['PSTATUS'] = 'N';
				$g_fld_fue_his['REMARKS'] = 'PROJECT PRIORITY LEVEL - '.$level.'-AP HAS UPDATED';
				$update_due = insert_test_dbquery($g_fld_fue_his,$g_history_table);
				print_r($g_fld_fue_his);
}


if($action == 'checkFlowuser'){
	// Predefined Flow user_error
	$commonFlowuser = select_query_json("select EMPNAME,EMPCODE,trim(EMPNAME||' - '||EMPCODE) flowusr from trandata.employee_office@tcscentr where EMPCODE in(1,2,3,1657,19256,1118,1986) order by EMPNAME","Centra","TCS");
	//print_r($commonFlowuser);
	$finalList = array();
	$sortFlow = array();
	$temparray = array();
	
		$k =array('1657','19256','1118','1986','3','2','1'); // Order the flowuser here
		for($v = 0 ; $v <sizeof($commonFlowuser); $v++)
		{
			for($f = 0 ; $f <sizeof($commonFlowuser); $f++)
		{
			if ($commonFlowuser [$f]['EMPCODE'] == $k [$v]){
				$sortFlow [$v] = $commonFlowuser[$f];
				array_push($temparray,$sortFlow [$v]['FLOWUSR']);
			}
		}
		}
		$tem = array_reverse($temparray);
		
	//print_r($filter);
	$subcore = array();
	$getsubcore = select_query_json("select SUBCORE from trandata.approval_master@tcscentr where tarnumb in (".$filter.")","Centra","TCS");
	
	for($u = 0 ; $u < sizeof($getsubcore); $u++){
		array_push($subcore,$getsubcore[$u]['SUBCORE']);
	}
	$result = array_unique($subcore);
	$finalsub = implode(",",$result);
	//echo $finalsub;
	
	
$getHOD = select_query_json("select ESECODE, EMPNAME, EMPCODE APPHEAD, 'HOD' APPTITL from trandata.employee_office@tcscentr 
										where esecode in (".$finalsub.")  and DESCODE in (132, 189, 19, 169) and EMPCODE >= 1000 and brncode in (1, 10, 14, 23, 888, 100, 300, 201, 202, 203, 204, 205, 206) order by ESECODE, APPHEAD","Centra","TCS");
										
$filterHod = array();
for($h = 0 ; $h < sizeof($getHOD); $h++){
		array_push($filterHod,$getHOD[$h]['EMPNAME']." - ".$getHOD[$h]['APPHEAD']);
	}
	//$result2 = array_unique($filterHod);
	//print_r($filterHod);
	

 
		echo '<ol id ="list" name = "finallist[]"><b>';
		
		/*$count = 1;
		foreach($result2 as $ur){
			echo $count.". ".$ur.", ".'</br>';
			$count++;
		}*/
		$result3 = array_unique($result2);
		//$result4 =array_values($result3);
		$result4 = array_reverse(array_values(array_unique(array_merge($tem,$filterHod))));
		//print_r($result3);
		for($v = 0 ; $v<count($result4); $v++)
		{
				//echo $result2[$v].'</br>';
				echo '<li>'.$result4[$v].'</li>';
				
		}
		
		echo '</b></ol>';
		//print_r($result4);
		
		$arr = array();
		for($j= 0 ; $j<count($result4);$j++){
			$var = explode("-",$result4[$j]);
			array_push($arr,$var[1]);
			
		}
		$temp = array_filter($arr);
		$list = implode("~~",$temp);
		echo '<input type="hidden" id="idlist1" value = "'.$list.'" name = "idlist1"/>';
		echo '<input type="button" id="changeFlow1" value = "" name = "changeFlow1" onclick="changeFlow1('.$list.')" style="display:none" />';
		//print_r($result2);
}


// $pvalues = $_POST['data1'];
// $d1 = array();

// $count = sizeof($pvalues);
// echo $count;

/*
$myArray = explode(',', $pvalues);
		$d1 = array();
		
		for($f = 0 ; $f <sizeof($myArray); $f++)
		{
			 $d1[$f] = $myArray [$f];
  
		}
		//$ledger = implode(" , ",$pvalues);
		//print_r($myArray);
		$g_table1 = "approval_project_head";
		$where1 = " PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pid."' and PRJTITL = '4'" ;
		$g_fld1 = array();
		for ($v = 0 ; $v <sizeof($d1); $v++) {
		
		$g_fld1['EDTUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld1['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld1['PRJVALU'] =  $d1[$v];
		//$update_read = update_test_dbquery($g_fld1, $g_table1, $where1);
		//print_r($g_fld1);
		}
*/
// 
		?>