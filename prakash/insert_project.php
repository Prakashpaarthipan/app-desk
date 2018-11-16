<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if($action =='newProject'){
$PRMSCOD = select_query_json("Select nvl(Max(PRMSCOD),0)+1 maxarqcode From approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
 //Referal Approval Project hierarchy
		$PRJHYCD = select_query_json("Select nvl(Max(PRJHYCD),0)+1 MAXARQCODE From approval_project_hierarchy ","Centra","TEST");
		//$APPROVE = select_query_json("select EMPSRNO, EMPNAME, EMPCODE from employee_office where EMPCODE = 1986 or empcode = 3 or empcode = 1657 or empcode = 19256 order by EMPNAME" ,"Centra","TCS");
		
		/*$APPROVE = select_query_json("select EMPSRNO, EMPNAME, EMPCODE from employee_office where EMPCODE in(1,2,3,1657,19256,1118) order by EMPNAME" ,"Centra","TCS");
		
		//New Update 30-9-2018
		
		
		//print_r ($APPROVE);
		//print_r($PRJHYCD);
		$text = array();
		$k =array('1657','19256','1118','1986','3','2','1');
		for($v = 0 ; $v <sizeof($APPROVE); $v++)
		{
			for($f = 0 ; $f <sizeof($APPROVE); $f++)
		{
			if ($APPROVE [$f]['EMPCODE'] == $k [$v]){
				$text [$v] = $APPROVE[$f];
			}
		}
		}
		//print_r($text);
		$APPROVE = $text;*/
		$PRJ = $PRJHYCD[0]['MAXARQCODE'];
		//print_r($APPROVE);
		$flow = explode("~~",$idlist);
		echo count($flow);
		for($d = 0 ; $d <sizeof($flow); $d++)
		{
			
			$APPROVE = select_query_json("select EMPSRNO, EMPNAME, EMPCODE from employee_office where EMPCODE = '".trim($flow[$d])."' " ,"Centra","TCS");
			
		$srno =1;
		$g_hie_table5 = "approval_project_hierarchy";
		$g_hie_table['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_hie_table['PRJHYCD'] = $PRJ;
		$g_hie_table['EMPSRNO'] = $APPROVE[0]['EMPSRNO'];
		$g_hie_table['EMPCODE'] = $APPROVE[0]['EMPCODE'];
		$g_hie_table['EMPNAME'] = trim($APPROVE[0]['EMPNAME']);
		$g_hie_table['APPSRNO'] = $srno + $d;
		$g_hie_table['APPSTAT'] = 'N';
		$g_hie_table['DELETED'] = 'N';
		$g_insert_subject = insert_test_dbquery($g_hie_table,$g_hie_table5);
		$PRJ += 1;
		print_r ($g_hie_table);
		}
//Checking multiple Owner list
		$branch = explode(" , ",$txt_branch_type);
		$project_name = strtoupper($txt_project_name);
		$project_name = trim($project_name);
		//$top_core = explode(" - ",$txt_top_core);
		//$sub_core = explode(" - ",$txt_core);
		
		$PRJCODE = select_query_json("Select nvl(Max(PRJCODE),0)+1 maxarqcode From approval_project_master where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");
		//$branchname = select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE = '".$branch[0];."' ORDER BY BRNCODE","Centra","TCS");
		//$branchname = $branchname [0][BRNNAME];
		$g_table = "approval_project_master";
		$g_fld = array();
		$g_fld['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_fld['BRN_PRJ'] = $txt_mode_type;//mode of eg B - branch and P - project
		$g_fld['PRJCODE'] = $PRJCODE[0]['MAXARQCODE'];// max+1
		$g_fld['PRJNAME'] = $project_name;
		$g_fld['BRNCODE'] = $branch[0];
		$g_fld['BRNNAME'] = $branch[1];
		$g_fld['IMPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DUEDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txt_due_date;
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['DELETED'] = 'N';
		$firstUser = select_query_json("select EMPSRNO,  EMPCODE from employee_office where EMPCODE = '".trim($flow[0])."' " ,"Centra","TCS");
		$g_fld['CAPPUSR'] = $firstUser[0]['EMPSRNO'];
		$g_fld['PRIMODE'] = $slt_priority;
		
		//$g_fld['APRUSER'] = $_SESSION['tcs_usrcode'];
		//$g_fld['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['PRJSTAT'] = 'N'; // N means not approved yet
		$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
		//var_dump($g_insert_subject);
		print_r($g_fld);
		
		
		
		///-->
		//Trigger Table
//		 BUDGET_PLANNER_HEAD_SUM
		$tri_table = "BUDGET_PLANNER_HEAD_SUM";
		$tri_field = array();
		$tri_field ['PRJCODE'] =$PRMSCOD[0]['MAXARQCODE'];
		$tri_field ['BUDYEAR'] =$current_year[0]['PORYEAR'];
		$tri_field ['EXPSRNO'] =0;
		$tri_insert = insert_test_dbquery($tri_field,$tri_table);
			
			

		///-->
		
		
		//----------------------------------
		// History Table Update //
		
		/*$g_table_history = "approval_project_history";
		$g_fld_history = array();
		$g_fld_history['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld_history['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
		$g_insert_subject1 = insert_test_dbquery($g_fld_history,$g_table_history);
		print_r($g_fld_history);*/
		
		
		//History Table End //
		
		//print_r($_FILES);
		
		
if($g_insert_subject == 1)	{
		$noa = sizeof($_FILES['attachments']['name']);	
		//print_r($noa);
		
		//-------------- Upload Attachments ---------------//
		//uploading the attachments 
		
		for($file=1;$file<=$noa;$file++)
		{
           $filesno = select_query_json("Select nvl(Max(FILESNO),0)+1 FILESNO From approval_project_attachments where PRMSYER='".$current_year[0]['PORYEAR']."' And PRMSCOD=".$PRMSCOD[0]['MAXARQCODE']."","Centra","TEST");			
			if($_FILES['attachments']['name'][$file-1] != null)
			{	
				
				///----------updating the index to attachment to local
				$q=$_FILES['attachments']['name'][$file-1];
				$path_parts = pathinfo($q);
				$tmp_name = $_FILES["attachments"]["tmp_name"][$file-1];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year[0]['PORYEAR'].'_'.$PRMSCOD[0]['MAXARQCODE'].'_'.$file.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$a1local_file = "../uploads/admin_projects_local/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

						///---------updating the index to attachment to server
				
				$nameforserver = $current_year[0]['PORYEAR'].'_'.$PRMSCOD[0]['MAXARQCODE'].'_'.$file.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				//$alocal_file = "../uploads/admin_projects_local/attachments/".$name;
				$a1local_file = "../uploads/admin_projects_local/attachments/".$name;
				//echo($a1local_file);
				//echo ($nameforserver);
				$server_file = 'approval_desk/approval_project_mgt/2018-19/'.$nameforserver;
				//echo ($server_file);
			
			    $upload = ftp_put($ftp_conn, $server_file, $a1local_file, FTP_BINARY);
					// echo($upload);

			
				$g_table_att = "approval_project_attachment";
				$g_fld_att = array();
				$g_fld_att['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld_att['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
				$g_fld_att['FILESNO'] = $file;
				$g_fld_att['FILENAM'] = $nameforserver;
				$g_fld_att['DELETED'] = 'N';
				$g_fld_att['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_att['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_insert_subject = insert_test_dbquery($g_fld_att,$g_table_att);
				//var_dump($g_insert_subject);
				
				print_r($g_fld_att);
				
				
				
						///----------updating the index to attachment database
				//$f=$_FILES['attachments']['name'][$file-1];
				//echo "\n".$f."\n";
				//$path_parts = pathinfo($f);
				//$name =  $current_year[0]['PORYEAR'].'_'.$PRMSCOD[0]['MAXARQCODE'].'_'.$file.'.'.strtolower($path_parts['extension']);
				/**/
				
				// echo "done";
			}
		}
	//------------------- Upload Finish -----------------/
		
			
		
		
		
		
		$prjslno = 0;
		$prjslnohead =0;
		$prjslnoowner = 0;
		$prjslnomember = 0;
		//echo sizeof($txt_ledger_name);
		//echo count($txt_ledger_name);
		//var_dump($txt_ledger_name);
		//echo ($txt_ledger_name);
		//print_r($txt_ledger_name);
		
		if($txt_ledger_name [0] != "") {
			for ($l=0 ; $l < sizeof($txt_ledger_name) ; $l++)
			{
				$ledger = explode(" - ",$txt_ledger_name[$l], 2);
				//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
				//$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_owner[0]."'","Centra","TCS");
				$top_sub = select_query_json("Select distinct sec.esecode, trim(substr(sec.esename, 4, 25)) esename, am.TOPCORE , atc.ATCNAME From approval_master am , APPROVAL_TOPCORE atc , empsection sec
					where sec.esecode = am.subcore and am.TOPCORE = atc.ATCCODE and am.deleted = 'N' and atc.deleted = 'N' and sec.deleted = 'N' and am.TARNUMB = '".$ledger[0]."'","Centra","TCS");
				$subcore_id = $top_sub[0]['ESECODE'];
				$subcore_name = $top_sub[0]['ESENAME'];
				$top_id = $top_sub[0]['TOPCORE'];
				$top_name = $top_sub[0]['ATCNAME'];
				$g_table4 = "approval_project_head";
				$prjslno = $l+1;
				$g_fld4 = array();
				$g_fld4['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld4['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
				$g_fld4['PRJSRNO'] = $prjslno;
				$g_fld4['PRJTITL'] = '4';//strtoupper($txt_project_name); REF TO APPROVAL_PROJECT_TITLE
				$g_fld4['EMPSRNO'] = '0';
				$g_fld4['EMPCODE'] = '0';
				$g_fld4['EMPNAME'] = '0';
				$g_fld4['TARNUMB'] = $ledger[0];// subcore txt
				$g_fld4['TARNAME'] = $ledger[1];// subcore txt
				$g_fld4['PRJVALU'] = $txt_value[$l];// PROJECT VALUE
				$g_fld4['ATCCODE'] = $top_id;// topcore id
				$g_fld4['TOPCORE'] = $top_name; // topcore txt
				$g_fld4['SUBCORE'] = $subcore_id; // subcore id
				$g_fld4['SUBCRNM'] = $subcore_name;// subcore txt
				$g_fld4['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld4['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld4['DELETED'] = 'N';
				$g_insert_subject = insert_test_dbquery($g_fld4,$g_table4);
				print_r($g_fld4);
				if($g_insert_subject != 1){
					echo '##';
					exit;
				}
				//var_dump($g_insert_subject);
			}
	
		}
				//Checking multiple Owner list
		if(sizeof($txt_project_owner) != 0) {
		for ($i= 0 ; $i < sizeof($txt_project_owner); $i++)
		{
		$p_owner = explode(" - ",$txt_project_owner[$i]);
		//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_owner[0]."'","Centra","TCS");
		$g_table1 = "approval_project_head";
		$g_fld1 = array();
		$prjslnoonwer = $i+$prjslno+1;
		$g_fld1['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld1['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
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
		$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
		print_r($g_fld1);
		//var_dump($g_insert_subject);
		}
		}
	////Checking multiple head list
		if(sizeof($txt_project_head) != 0) {
		for ($j= 0 ; $j < sizeof($txt_project_head); $j++)
		{
		$p_head = explode(" - ",$txt_project_head[$j]);
		//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_head[0]."'","Centra","TCS");
		$g_table2 = "approval_project_head";
		$g_fld2 = array();
		$prjslnohead = $j+$prjslnoonwer+1;
		$g_fld2['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld2['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
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
		$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
		print_r($g_fld2);
		//var_dump($g_insert_subject);
	}
		}
		if(sizeof($txt_project_member) != 0) {
		////Checking multiple member list

	for ($k= 0 ; $k < sizeof($txt_project_member); $k++)
	{
		$p_member = explode(" - ",$txt_project_member[$k]);
		//$PRJSRNO = select_query_json("Select nvl(Max(PRJSRNO),0)+1 maxarqcode From approval_project_head","Centra","TEST");
		$EMPSRNO = select_query_json("select EMPSRNO from employee_office where EMPCODE = '".$p_member[0]."'","Centra","TCS");
		$g_table3 = "approval_project_head";
		$g_fld3 = array();
		$prjslnomember = $k+$prjslnohead+1;
		$g_fld3['PRMSYER'] = $current_year[0]['PORYEAR'];
		$g_fld3['PRMSCOD'] = $PRMSCOD[0]['MAXARQCODE'];
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
		$g_insert_subject = insert_test_dbquery($g_fld3,$g_table3);
		print_r($g_fld3);
		//var_dump($g_insert_subject);

	}
		}
		
}
	
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
		echo '<input type="hidden" id="idlist" value = "'.$list.'" name = "idlist"/>';
		echo '<input type="button" id="changeFlow" value = "" name = "changeFlow" onclick="changeFlow('.$list.')" style="display:none" />';
		//print_r($result2);
}
?>
