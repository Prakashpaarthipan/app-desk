<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);

		
		//$PRJCODE = select_query_json("select * from approval_request where APRNUMB like  '%ADMIN / INFO TECH 4000010 / 03-04-2018 / 0010 / 08:15 PM%'","Centra","TCS");
		$PRJCODE = select_query_json("select * from APPROVAL_MDHIERARCHY where APMCODE in (974,1211,1721,1309,1203,1488,1459,1435)","Centra","TCS");
		
		$size = count($PRJCODE);
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		//$branchname = select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE = '".$branch[0];."' ORDER BY BRNCODE","Centra","TCS");
		//$branchname = $branchname [0][BRNNAME];
		$g_table = "APPROVAL_MDHIERARCHY";
		
		
$file = array();
foreach($PRJCODE  as $val)
{
  $file['APMCODE'] = $val['APMCODE'];
 $file['AMHSRNO'] = $val['AMHSRNO']; 
 $file['APPHEAD'] = $val['APPHEAD']; 
 $file['APPDESG'] = $val['APPDESG']; 
 $file['APPDAYS'] = $val['APPDAYS']; 
 $file['APPRIOR'] = $val['APPRIOR']; 
 $file['APPTITL'] = $val['APPTITL']; 
 $file['VRFYREQ'] = $val['VRFYREQ']; 
 $file['APRNUMB'] = $val['APRNUMB']; 
 $file['PBDAPPR'] = $val['PBDAPPR']; 
 
$g_insert_subject = insert_test_dbquery($file,$g_table);
//		print_r($array1);	
}
		
			


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