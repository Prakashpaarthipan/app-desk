<?php
error_reporting(0);
header('X-UA-Compatible: IE=edge');
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 365);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);

// Assign the URL / Title of the website
$siteurl		= "http://".$_SERVER['HTTP_HOST']."/";
$adminurl		= "http://".$_SERVER['HTTP_HOST']."/supadmin/";
$sitepath		= "/uploads";
$site_title 	= 'The Chennai Silks';
$site_email		= 'info@thechennaisilks.com';
// Assign the URL / Title of the website

const ftp_server = "172.16.0.49";
const ftp_server1 = "tcstextile.in";
const ftp_user_name = "ituser";
const ftp_user_pass = "S0ft@369";

define("ftpvri_server_apdsk", "ftp1.thechennaisilks.com");
define("ftpvri_user_name_apdsk", "ituser");
define("ftpvri_user_pass_apdsk", "S0ft@369");

$ftp_server = "172.16.0.49";
$ftp_server1 = "tcstextile.in";
$ftp_user_name = "ituser";
$ftp_user_pass = "S0ft@369";

$ftp_server_159 = "172.16.0.159";
$ftp_server1_159 = "tcstextile.in";
$ftp_user_name_159 = "ituser";
$ftp_user_pass_159 = "S0ft@369";

$ftp_server_ftp = "172.16.0.159";
$ftp_server1_ftp = "ftp://ftp1.thechennaisilks.com";
$ftp_user_name_ftp = "ituser";
$ftp_user_pass_ftp = "S0ft@369";

$ftp_server_apdsk = "ftp1.thechennaisilks.com";
$ftp_server1_apdsk = "ftp://ftp1.thechennaisilks.com:5022";
$ftp_srvport_apdsk = ":5022";
$ftp_user_name_apdsk = "ituser";
$ftp_user_pass_apdsk = "S0ft@369";

$ftp_server_docs = "172.16.0.130";
$ftp_user_name_docs = "accounts.document";
$ftp_user_pass_docs = "scancopy@369";
	
$ftp_server_8_2 = "172.16.8.2";
$ftp_user_name_8_2 = "ftpuser";
$ftp_user_pass_8_2 = "p@ssw0rd";

$ftp_server_16_2 = "172.16.16.2";
$ftp_user_name_16_2 = "ftpuser";
$ftp_user_pass_16_2 = "p@ssw0rd";

$ftp_server_24_2 = "172.16.24.2";
$ftp_user_name_24_2 = "ftpuser";
$ftp_user_pass_24_2 = "p@ssw0rd";

$ftp_server_32_2 = "172.16.32.2";
$ftp_user_name_32_2 = "ftpuser";
$ftp_user_pass_32_2 = "p@ssw0rd";

$ftp_server_40_2 = "172.16.40.2";
$ftp_user_name_40_2 = "ftpuser";
$ftp_user_pass_40_2 = "p@ssw0rd";

$ftp_server_56_2 = "172.16.56.2";
$ftp_user_name_56_2 = "ftpuser";
$ftp_user_pass_56_2 = "p@ssw0rd";

$ftp_server_64_2 = "172.16.64.2";
$ftp_user_name_64_2 = "ftpuser";
$ftp_user_pass_64_2 = "p@ssw0rd";

$ftp_server_72_2 = "172.16.72.2";
$ftp_user_name_72_2 = "ftpuser";
$ftp_user_pass_72_2 = "p@ssw0rd";

$ftp_server_80_2 = "172.16.80.2";
$ftp_user_name_80_2 = "ftpuser";
$ftp_user_pass_80_2 = "p@ssw0rd";

$ftp_server_88_2 = "172.16.88.2";
$ftp_user_name_88_2 = "ftpuser";
$ftp_user_pass_88_2 = "p@ssw0rd";

$ftp_server_104_2 = "172.16.104.2";
$ftp_user_name_104_2 = "ftpuser";
$ftp_user_pass_104_2 = "p@ssw0rd";

$ftp_server_112_2 = "172.16.112.2";
$ftp_user_name_112_2 = "ftpuser";
$ftp_user_pass_112_2 = "p@ssw0rd";

$ftp_server_120_2 = "172.16.120.2";
$ftp_user_name_120_2 = "ftpuser";
$ftp_user_pass_120_2 = "p@ssw0rd";

$ftp_server_128_2 = "172.16.128.2";
$ftp_user_name_128_2 = "ftpuser";
$ftp_user_pass_128_2 = "p@ssw0rd";

$ftp_server_136_2 = "172.16.136.2";
$ftp_user_name_136_2 = "ftpuser";
$ftp_user_pass_136_2 = "p@ssw0rd";

$ftp_server_144_2 = "172.16.144.2";
$ftp_user_name_144_2 = "ftpuser";
$ftp_user_pass_144_2 = "p@ssw0rd";
		
$donthaveaccess = 'You dont have access to see this';


// $inactive = 1800; // Inactive After 1800 Seconds or 30 Mins
$inactive = 3600; // Inactive After 3600 Seconds or 60 Mins
if(isset($_SESSION['start']) ) {
	$session_life = time() - $_SESSION['start'];
	if($session_life > $inactive){
	
	if (in_array($menu_name, $_SESSION['tcs_submenu_access']))
	{
		$tbl_apply = 'srm_userlog';
		$exist_apply = select_query_json("select nvl(max(USERLOG)+1,1) from ".$tbl_apply."", "Centra", 'TEST');
		$exist_menu = select_query_json("select MNUCODE from srm_menu where SUBMENU = '".$menu_name."' and MAINMENU = 'Supplier' order by MNUCODE Asc", "Centra", 'TEST');

	//echo "<br><br>===".$_SESSION['cur_filename']."***".$exist_menu[0][0]."*****";
		if($_SESSION['cur_filename'] != $exist_menu[0][0]) {
			if($_SESSION['cur_filename'] != '') {
				$tbl_name='srm_userlog';
				$field_value1=array();
				$field_value1['OUTTIME'] = date('d-m-Y H:i:s A');
				$field_value1['LOGSTAT'] = 'Y';
				$where_conditions = "MNUCODE = '".$_SESSION['cur_filename']."' and USERLOG = '".$_SESSION['lastid']."' and LOGTIME like '".date('d-m-Y')."%' and ( SUPCODE = '".$_SESSION['tcs_userid']."' or EMPSRNO = '".$_SESSION['tcs_userid']."' )";
				$ilo = update_query($field_value1, $tbl_name, $where_conditions);
				//echo "!!*******";
			}	
			$_SESSION['cur_filename'] = $exist_menu[0][0];

			/* $tbl_name="srm_userlog";
			$field_value=array();
			$field_value['USERLOG'] = $exist_apply[0][0];	
			
			if($_SESSION['tcs_empsrno'] != '') {
				$field_value['SUPCODE'] = '0';
				$field_value['EMPSRNO'] = $_SESSION['tcs_userid'];	
			} else {
				$field_value['SUPCODE'] = $_SESSION['tcs_userid'];
				$field_value['EMPSRNO'] = '0';
			}
			
			$field_value['MNUCODE'] = $exist_menu[0][0];	
			$field_value['LOGTIME'] = date('d-m-Y H:i:s A');
			$field_value['OUTTIME'] = null; 
			$field_value['LOGSTAT'] = 'N';
			//print_r($field_value);
			$lol = insert_query($field_value, $tbl_name); */
			//echo "@@******";
			//echo "***".$lol."***"; //exit();
		}
	} else {
	?>
	<script>/* alert('Error GA'); window.history.back(); */ </script>
	<?
	}
	
	session_destroy(); 
	?>
	<script>window.location="<?=$siteurl?>logout.php?mode=session";</script>
	<?
	exit();
	}
}
$_SESSION['start'] = time();


function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function find_ip($ipaddress)
{
	$expl_ip = explode(".", $ipaddress);
	
	/* Tirupur Office IP Addresses */
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.14')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.48')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.50')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.51')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.52')
	{
		return 1;
	}
	/* Tirupur Office IP Addresses */
	
	/* All Purchase offices */
	for($ipi = 0; $ipi <= 15; $ipi++) {
		if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.20.'.$ipi)
		{
			return 1;
		}
	}
	/* All Purchase offices */
	
	/* All Branches */
	for($ipii = 16; $ipii <= 152; $ipii++) {
		if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.'.$ipii)
		{
			return 1;
		}
	}
	/* All Branches */

	return 0;
}


function find_tup_tcsip($ipaddress)
{
	$expl_ip = explode(".", $ipaddress);
	
	/* Tirupur Office IP Addresses */
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.12')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.14')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.48')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.49')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.50')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.51')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.52')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.54')
	{
		return 1;
	}
	if($expl_ip[0].".".$expl_ip[1].".".$expl_ip[2] == '172.16.55')
	{
		return 1;
	}
	/* Tirupur Office IP Addresses */
	
	return 0;
}
?>