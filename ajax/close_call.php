<?php
header("Access-Control-Allow-Origin: *");
session_start();
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
echo("hi");

$check1=select_query_json("SELECT nvl(MAX(REQSRNO),0) MAXREQ FROM SERVICE_REQUEST WHERE REQNUMB='".$_REQUEST['reqnumb']."'", "Centra", 'TCS');
print_r($check1);
$check = select_query_json("SELECT nvl(MAX(RESSRNO),0) MAXRES FROM SERVICE_RESPONSE WHERE REQNUMB='".$_REQUEST['reqnumb']."' AND REQSRNO='".$check1[0]['MAXREQ']."'", "Centra", 'TCS');
print_r($check);

$check2 = select_query_json("SELECT COUNT(*) cal FROM SERVICE_RESPONSE WHERE REQNUMB='".$_REQUEST['reqnumb']."' AND REQSRNO='".$check1[0]['MAXREQ']."' AND RESFUSR='".$_SESSION['tcs_empsrno']."' ","Centra", 'TCS');
echo("SELECT COUNT(*) cal FROM SERVICE_RESPONSE WHERE REQNUMB='".$_REQUEST['reqnumb']."' AND REQSRNO='".$check1[0]['MAXREQ']."' AND RESFUSR='".$_SESSION['tcs_empsrno']."' ");
print_r($check2);
if($check[0]['MAXRES']!='0'){echo('true');}
echo($check2[0]['CAL']!=0);
if($check[0]['MAXRES']!=0 )
{	
	$g_table = "service_request";
	$g_fld4 = array();
	$g_fld4['REQSTAT'] = 'C';
	$g_fld4['RESLVUSER'] =$_SESSION['tcs_empsrno'];
	$g_fld4['RESLVDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appplan="reqnumb ='".$_REQUEST['reqnumb']."'";
	//print_r($g_fld4);
	$insert_appplan1 = update_dbquery($g_fld4, $g_table, $where_appplan);
	if($insert_appplan1==1)
	{
		$g_table3 = "service_response";
		$g_fld3 = array();
		$g_fld3['RESSTAT'] = 'C';
		$g_fld3['RESFUSR'] =$_SESSION['tcs_empsrno'];
		$g_fld3['RESDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$where_appplan3="reqnumb ='".$_REQUEST['reqnumb']."' and reqsrno='".$check1[0][MAXREQ]."' and ressrno='".$check[0][MAXRES]."'";
		//print_r($g_fld3);
		$insert_appplan3 = update_dbquery($g_fld3, $g_table3, $where_appplan3);
	}
	if($insert_appplan3==1)
	{
		$fcm= select_query_json("select dev.devicetoken id,dev.USERNAME mobile,to_char(sre.reqnumb) reqnum
								from trandata.devicetable@tcscentr dev,trandata.supplier@tcscentr sup,TRANDATA.SUPPLIER_USERID@TCSCENTR SUR,trandata.service_request@tcscentr sre 
								where sre.requser=sup.supcode AND SUP.SGRCODE=SUR.SGRCODE and dev.appcode='1' and dev.bundleid='0' and to_char(dev.username)=SUR.supmobi and sre.reqnumb='".$_REQUEST['reqnumb']."'", "Centra", 'TCS');
		if($fcm!='')
		{	
			ANDROID_FCM($fcm[0]['MOBILE'], $fcm[0]['ID']);
		}
	}
} 
else 
{
	echo 0;
}

function ANDROID_FCM($ph_no, $gcmcenterid) 
{
	// print_r($ph_no);
	// print_r('--'.$gcmcenterid);	
	$client = new SoapClient("http://mobile.thechennaisilks.com/TCSService.asmx?Wsdl"); 
	//print_r($client);
	try{
		$get_parameter->USERNAME = $ph_no;
		$get_parameter->KEY = "Response";
		$get_parameter->TITLE = "Service Response";
		$get_parameter->TAG =  "SRES" ;
		$get_parameter->DEVICEID = $gcmcenterid;
		$get_parameter->MSG = "Your Request is Closed";
		$get_parameter->ISACTIVE ="N"; 
		$get_parameter->ISSHOW =  "N";
		$get_result=$client->ANDROID_FCM_NOTIFY($get_parameter)->ANDROID_FCM_NOTIFYResult;
		// print_r($get_result);
		//echo(hi);
	}
	catch(SoapFault $fault)
	{
		//+echo('error');
		$get_result = 0;
		//echo "Fault code:{$fault->faultcode}".NEWLINE;
		//echo "Fault string:{$fault->faultstring}".NEWLINE;
		if ($client != null)
		{
			$client=null;
		}
	}
	$soapClient = null;
}
?>