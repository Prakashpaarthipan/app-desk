<?
include_once('../../lib/config.php');
include_once('../../lib/function_connect.php');
include_once('../../general_functions.php');
//print_r($_REQUEST);
if($_REQUEST['action']=='login')
{
	$resp = select_query_login_check_json($_REQUEST['username'], $_REQUEST['password'], "Centra", 'TCS');
	echo($resp);	
}
if($_REQUEST['action']=='user_profile')
{
	$resp = select_query_json("select empname,desname,MOBILENO,empphot,BLOODGROUP from trandata.employee_office@tcscentr eof,trandata.employee_personal@tcscentr eop,trandata.userid@tcscentr usr,trandata.designation@tcscentr des where des.descode=eof.descode and usr.usrcode='".$_REQUEST['usrcode']."' and usr.empsrno=eof.empsrno and usr.empsrno=eop.empsrno", "Centra", 'TCS');
	echo(json_encode($resp));
}
?>