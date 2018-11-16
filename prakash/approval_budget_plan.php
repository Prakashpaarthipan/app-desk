<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);

		
		//$PRJCODE = select_query_json("select * from approval_request where APRNUMB like  '%ADMIN / INFO TECH 4000010 / 03-04-2018 / 0010 / 08:15 PM%'","Centra","TCS");
		$PRJCODE = select_query_json("select * from APPROVAL_BUDGET_PLANNER where trunc(ADDDATE) = to_date('01/07/2018','dd/MM/yyyy')","Centra","TCS");
		
		$size = count($PRJCODE);
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		//$branchname = select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE = '".$branch[0];."' ORDER BY BRNCODE","Centra","TCS");
		//$branchname = $branchname [0][BRNNAME];
		$g_table = "APPROVAL_BUDGET_PLANNER";
		
		/*$g_fld = array();
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
		//$g_fld['APRUSER'] = $_SESSION['tcs_usrcode'];
		//$g_fld['APRDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['PRJSTAT'] = 'N'; // N means not approved yet*/
		/*for($i = 0 ; $i < $size; $i++ ){
		$array1 = array();
		//$array1 =$PRJCODE[i];
		$array1['ARQYEAR'] = $prjcode[$i]['ARQYEAR'];
		*/
		
$file = array();
foreach($PRJCODE  as $val)
{
 $file['APRNUMB'] = $val['APRNUMB'];
 $file['APRSRNO'] = $val['APRSRNO'];
 $file['APRPRID'] = $val['APRPRID'];
 $file['APRMNTH'] = $val['APRMNTH'];
 $file['APPRVAL'] = $val['APPRVAL'];
 $file['APPMNTH'] = $val['APPMNTH'];
 $file['APPYEAR'] = $val['APPYEAR'];
 $file['TARNUMB'] = $val['TARNUMB'];
 $file['RESVALU'] = $val['RESVALU'];
 $file['EXTVALU'] = $val['EXTVALU'];
 $file['BUDMODE'] = $val['BUDMODE'];
 $file['APRYEAR'] = $val['APRYEAR'];
 $file['ADDUSER'] = $val['ADDUSER'];
 $file['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['ADDDATE'])); 
 $file['EDTUSER'] = $val['EDTUSER'];
 $file['EDTDATE'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['EDTDATE'])); 
 $file['DELETED'] = $val['DELETED'];
 $file['DELUSER'] = $val['DELUSER'];
 $file['DELDATE'] = '';//$val['DELDATE'];
 $file['BRNCODE'] = $val['BRNCODE'];
 $file['APPMODE'] = $val['APPMODE'];
 $file['EXPSRNO'] = $val['EXPSRNO'];
 $file['EXISTVL'] = $val['EXISTVL'];
 $file['USEDVAL'] = $val['USEDVAL'];
 $file['DEPCODE'] = $val['DEPCODE'];

$g_insert_subject = insert_test_dbquery($file,$g_table);
//		print_r($array1);	
}
		
		//}
		//var_dump($g_insert_subject);
		//echo $size;
		//print_r($PRJCODE[0]);
		


?>
 <!DOCTYPE html>
    <html lang="en" >
    <head>
        <!-- META SECTION -->
        <title><?=$title_tag?> Request Entry :: Approval Desk :: </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
		
		</head>
		<body>
		<h1><?echo $size ;?></h1>
		<h1><?//print_r($array1);?></h1>
		</body>
		</html>