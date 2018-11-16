<?	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	session_start();
	error_reporting(0);
	include_once('../lib/function_connect.php');

	$expl = explode(" - ", $txt_assign);
	$sql_empsrno = select_query_json("select empsrno from employee_office where EMPCODE = '".$expl[0]."' order by EMPCODE", 'Centra', 'TCS');
	
	$g_table = "PROCESS_REQUIREMENT_ENTRY";
	$g_fld4 = array();
	$g_fld['CORCODE'] = $_REQUEST['txt_core']; 
	$g_fld['ASGNMBR'] = $sql_empsrno[0]['EMPSRNO'];
	//$g_fld['TARDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$_REQUEST['tar_date']; 
	//$g_fld['DUEDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$_REQUEST['due_date'];
	$where_appplan="ENTRYYR = '".$_REQUEST['entryyr']."' and ENTRYNO = '".$_REQUEST['entryno']."' and ENTSRNO = '".$_REQUEST['entsrno']."'";
	$insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);
?>