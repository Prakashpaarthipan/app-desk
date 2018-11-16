<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
//include_once('general_functions.php');
extract($_REQUEST);

		
		//$PRJCODE = select_query_json("select * from approval_request where APRNUMB like  '%ADMIN / INFO TECH 4000010 / 03-04-2018 / 0010 / 08:15 PM%'","Centra","TCS");
		$PRJCODE = select_query_json("select * from approval_request where trunc(ADDDATE) = to_date('01/07/2018','dd/MM/yyyy') order by APRNUMB desc","Centra","TCS");
		
		$size = count($PRJCODE);
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		//$branchname = select_query_json("SELECT BRNCODE, BRNNAME ,NICNAME FROM branch where BRNCODE = '".$branch[0];."' ORDER BY BRNCODE","Centra","TCS");
		//$branchname = $branchname [0][BRNNAME];
		$g_table = "approval_request";
		
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
	
$file['ARQCODE'] = $val['ARQCODE'];
$file['ARQYEAR'] = $val['ARQYEAR'];
$file['ARQSRNO'] = $val['ARQSRNO'];
$file['ATYCODE'] = $val['ATYCODE'];
$file['ATMCODE'] = $val['ATMCODE'];
$file['APMCODE'] = $val['APMCODE'];
$file['ATCCODE'] = $val['ATCCODE'];
$file['APPRFOR'] = $val['APPRFOR'];
$file['REQSTTO'] = $val['REQSTTO'];
$file['APPRSUB'] = $val['APPRSUB'];
$file['APPRDET'] = $val['APPRDET'];
$file['APPRSFR'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['APPRSFR']));

$file['APPRSTO'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['APPRSTO']));
$file['APPATTN'] = $val['APPATTN'];
$file['APRQVAL'] = $val['APRQVAL'];
$file['APPDVAL'] = $val['APPDVAL'];
$file['APPFVAL'] = $val['APPFVAL'];
$file['BRNCODE'] = $val['BRNCODE'];
$file['DEPCODE'] = $val['DEPCODE'];
$file['TARNUMB'] = $val['TARNUMB'];
$file['TARBALN'] = $val['TARBALN'];
$file['TARDESC'] = $val['TARDESC'];
$file['REQSTBY'] = $val['REQSTBY'];
$file['RQBYDES'] = $val['RQBYDES'];
$file['REQDESC'] = $val['REQDESC'];
$file['REQESEC'] = $val['REQESEC'];
$file['REQDESN'] = $val['REQDESN'];
$file['REQESEN'] = $val['REQESEN'];
$file['REQSTFR'] = $val['REQSTFR'];
$file['RQFRDES'] = $val['RQFRDES'];
$file['RQFRDSC'] = $val['RQFRDSC'];
$file['RQFRESC'] = $val['RQFRESC'];
$file['RQFRDSN'] = $val['RQFRDSN'];
$file['RQFRESN'] = $val['RQFRESN'];
$file['RQESTTO'] = $val['RQESTTO'];
$file['RQTODES'] = $val['RQTODES'];
$file['RQTODSC'] = $val['RQTODSC'];
$file['RQTOESC'] = $val['RQTOESC'];
$file['RQTODSN'] = $val['RQTODSN'];
$file['RQTOESN'] = $val['RQTOESN'];
$file['APRNUMB'] = $val['APRNUMB'];
$file['APPSTAT'] = $val['APPSTAT'];
$file['APPFRWD'] = $val['APPFRWD'];
$file['APPINTP'] = $val['APPINTP'];
$file['INTPEMP'] = $val['INTPEMP'];
$file['INTPDES'] = $val['INTPDES'];
$file['INTPDSC'] = $val['INTPDSC'];
$file['INTPESC'] = $val['INTPESC'];
$file['INTPDSN'] = $val['INTPDSN'];
$file['INTPESN'] = $val['INTPESN'];
$file['INTPAPR'] = $val['INTPAPR'];
$file['INTSUGG'] = $val['INTSUGG'];
$file['INTPFRD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['INTPFRD']));
$file['INTPTOD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['INTPTOD']));
$file['ADDUSER'] = $val['ADDUSER'];
$file['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['ADDDATE']));
$file['EDTUSER'] = $val['EDTUSER'];
$file['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['ADDDATE']));
$file['DELETED'] = $val['DELETED'];
$file['DELUSER'] = $val['DELUSER'];
$file['DELDATE'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['DELDATE']));
$file['APRCODE'] = $val['APRCODE'];
$file['APRHURS'] = $val['APRHURS'];
$file['APRDAYS'] = $val['APRDAYS'];
$file['APRDUED'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['APRDUED']));
$file['APPRMRK'] = $val['APPRMRK'];
$file['APRTITL'] = $val['APRTITL'];
$file['FINSTAT'] = $val['FINSTAT'];
$file['FINUSER'] = $val['FINUSER'];
$file['FINCMNT'] = $val['FINCMNT'];
$file['FINDATE'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['FINDATE']));
$file['TARVLCY'] = $val['TARVLCY'];
$file['TARVLLY'] = $val['TARVLLY'];
$file['EXPNAME'] = $val['EXPNAME'];
$file['TARPRCY'] = $val['TARPRCY'];
$file['TARPRLY'] = $val['TARPRLY'];
$file['USRSYIP'] = $val['USRSYIP'];
$file['PRJPRCS'] = $val['PRJPRCS'];
$file['PLANVAL'] = $val['PLANVAL'];
$file['IMDUEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['IMDUEDT']));
$file['IMUSRCD'] = $val['IMUSRCD'];
$file['IMSTATS'] = $val['IMSTATS'];
$file['IMFINDT'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['IMFINDT']));
$file['IMUSRIP'] = $val['IMUSRIP'];
$file['TYPMODE'] = $val['TYPMODE'];
$file['SUBCORE'] = $val['SUBCORE'];
$file['BUDTYPE'] = $val['BUDTYPE'];
$file['BUDCODE'] = $val['BUDCODE'];
$file['IMFNIMG'] = $val['IMFNIMG'];
$file['NXLVLUS'] = $val['NXLVLUS'];
$file['PRICODE'] = $val['PRICODE'];
$file['SUPCODE'] = $val['SUPCODE'];
$file['SUPNAME'] = $val['SUPNAME'];
$file['SUPCONT'] = $val['SUPCONT'];
$file['PRODWIS'] = $val['PRODWIS'];
$file['RESPUSR'] = $val['RESPUSR'];
$file['ALTRUSR'] = $val['ALTRUSR'];
$file['RELAPPR'] = $val['RELAPPR'];
$file['ORGRECV'] = $val['ORGRECV'];
$file['ORGRVUS'] = $val['ORGRVUS'];
$file['ORGRVDT'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['ORGRVDT']));
$file['ORGRVDC'] = $val['ORGRVDC'];
$file['CNVRMOD'] = $val['CNVRMOD'];
$file['PURHEAD'] = $val['PURHEAD'];
$file['APPTYPE'] = $val['APPTYPE'];
$file['ADVAMNT'] = $val['ADVAMNT'];
$file['AGNSAPR'] = $val['AGNSAPR'];
$file['WRKINUSR'] = $val['WRKINUSR'];
$file['ARQPCOD'] = $val['ARQPCOD'];
$file['BDPLANR'] = $val['BDPLANR'];
$file['DYNSUBJ'] = $val['DYNSUBJ'];
$file['TXTSUBJ'] = $val['TXTSUBJ'];
$file['RMQUOTS'] = $val['RMQUOTS'];
$file['RMBDAPR'] = $val['RMBDAPR'];
$file['RMCLRPT'] = $val['RMCLRPT'];
$file['RMARTWK'] = $val['RMARTWK'];
$file['RMCONAR'] = $val['RMCONAR'];
$file['WARQUAR'] = $val['WARQUAR'];
$file['CRCLSTK'] = $val['CRCLSTK'];
$file['PAYPERC'] = $val['PAYPERC'];
$file['FNTARDT'] = $val['FNTARDT'];
$file['RPTUSER'] = $val['RPTUSER'];
$file['ACKUSER'] = $val['ACKUSER'];
$file['ACKSTAT'] = $val['ACKSTAT'];
$file['ACKDATE'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['ACKDATE']));
$file['AGEXPDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['AGEXPDT']));
$file['AGADVAM'] = $val['AGADVAM'];
$file['DYSBFDT'] = '';//'dd-Mon-yyyy HH:MI:SS AM~~'.date("d-M-Y h:i:s A", strtotime($val['DYSBFDT']));
$file['DYSBTDT'] = $val['DYSBTDT'];
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