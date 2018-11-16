<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);

		
		//$PRJCODE = select_query_json("select * from approval_request where APRNUMB like  '%ADMIN / INFO TECH 4000010 / 03-04-2018 / 0010 / 08:15 PM%'","Centra","TCS");
		$PRJCODE = select_query_json("select * from APPROVAL_PRODUCT_QUOTATION where PBDCODE in (1000843,1000860,1000859,1000858,1000857,1000856,1000855,1000854,1000853,1000852,1000851,1000848,1000838,1000837,1000813,1000774,1000861,3000892,3000820,3000818,3000805,3000752,3000834,3000906,3000905,3000904,3000903,3000902,3000901,3000900,3000899,3000898,3000897,3000896,3000895,3000894,3000893,3000891,3000890,3000889,3000888,3000887,3000886,3000885,3000884,3000883,3000882,3000881,3000880,3000879,3000878,3000877,3000876,3000875,3000874,3000873,3000872,3000871,3000870,3000866,3000849,3000848,3000847,3000846,3000845,3000844,3000843,3000842,3000840,3000838,3000837,3000836,3000826,3000814,3000812,3000811,3000798,3000770,3000741,2000745,2000744,2000743,2000742,2000740,2000739,2000738,2000731,4002774,4002062,4002793,4008659,4008658,4002821,4002820,4002818,4002817,4002816,4002815,4002814,4002813,4002812,4002811,4002772,4002721,4002810,4002794,4002627,4002782,4002766,4002764,4002763,4002762,4002819)","Centra","TCS");
		
		$size = count($PRJCODE);
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		//$branchname = select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE = '".$branch[0];."' ORDER BY BRNCODE","Centra","TCS");
		//$branchname = $branchname [0][BRNNAME];
		$g_table = "APPROVAL_PRODUCT_QUOTATION";
		
		
$file = array();
foreach($PRJCODE  as $val)
{
 $file['PBDYEAR'] = $val['PBDYEAR'];                  
 $file['PBDCODE'] = $val['PBDCODE'];                  
 $file['PBDLSNO'] = $val['PBDLSNO'];                  
 $file['PRLSTYR'] = $val['PRLSTYR'];                  
 $file['PRLSTNO'] = $val['PRLSTNO'];                  
 $file['PRLSTSR'] = $val['PRLSTSR'];                  
 $file['SUPCODE'] = $val['SUPCODE'];                  
 $file['SUPNAME'] = $val['SUPNAME'];                  
 $file['SLTSUPP'] = $val['SLTSUPP'];                  
 $file['DELPRID'] = $val['DELPRID'];                  
 $file['PRDRATE'] = $val['PRDRATE'];                  
 $file['SGSTVAL'] = $val['SGSTVAL'];                  
 $file['CGSTVAL'] = $val['CGSTVAL'];                  
 $file['IGSTVAL'] = $val['IGSTVAL'];                  
 $file['DISCONT'] = $val['DISCONT'];                  
 $file['NETAMNT'] = $val['NETAMNT'];                  
 $file['QUOTFIL'] = $val['QUOTFIL'];                  
 $file['SPLDISC'] = $val['SPLDISC'];                           
 $file['PIECLES'] = $val['PIECLES'];                           
 $file['SUPRMRK'] = $val['SUPRMRK'];                           
 $file['ADVAMNT'] = $val['ADVAMNT'];                          
 
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