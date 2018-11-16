<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}
$currentdate = strtoupper(date('d-M-Y h:i:s A'));

if($action == 'fix_grade') { 
	/* $sql_empgrade = select_query_json("select * from EMPLOYEE_GRADE_FIX where EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	if(count($sql_empgrade) > 0) { */
		// Update in EMPLOYEE_GRADE_FIX Table
		$tbl_apprq = "EMPLOYEE_GRADE_FIX";
		$field_apprq['DELETED'] = 'Y';
		$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
		$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = trunc(sysdate)";
		// print_r($field_apprq); echo "<br>";
		$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		// Update in EMPLOYEE_GRADE_FIX Table
	// }

	$grade_fixno = select_query_json("Select NVL(Max(GRDFXNO), TO_CHAR(sysdate+1, 'yyyymmdd')||'000000')+1 MAXGRDFXNO From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	
	/* $sql_todayentry = select_query_json("select * from EMPLOYEE_GRADE_FIX where trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	if(count($sql_todayentry) > 0) {
		$grade_fixno = select_query_json("Select NVL(Max(GRDFXNO), TO_CHAR(sysdate+1, 'yyyymmdd')||'00000')+1 MAXGRDFXNO From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = trunc(sysdate)", 'Centra', 'TEST');
	} else {
		$grade_fixno[0]['MAXGRDFXNO'] = date("Ymd")."1";
	} */
	
	switch ($grade) {
		case 1:
			$grade = 'A+';
			break;
		case 2:
			$grade = 'A';
			break;
		case 3:
			$grade = 'B';
			break;
		
		default:
			$grade = 'C';
			break;
	}

	// INSERT in EMPLOYEE_GRADE_FIX Table
	$tbl_docs = "EMPLOYEE_GRADE_FIX";
	$field_docs['GRDFXNO'] = $grade_fixno[0]['MAXGRDFXNO'];
	$field_docs['EMPSRNO'] = $empsrno;
	$field_docs['EMPHDSR'] = $_SESSION['tcs_empsrno'];
	$field_docs['EMPGRAD'] = $grade;
	$field_docs['EMPRMRK'] = strtoupper($txt_empgrade_remarks);

	$field_docs['ADDUSER'] = $_SESSION['tcs_usrcode'];
	$field_docs['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$field_docs['DELETED'] = 'N';
	$insert_docs = insert_dbquery($field_docs, $tbl_docs);
	// print_r($field_docs);
	// INSERT in EMPLOYEE_GRADE_FIX Table
	echo $insert_docs;
} 
if($action == 'add_employee') {
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	$g_table = "EMPLOYEE_HEAD_USER";
	$head=explode(' - ', $_REQUEST['txt_employee_head'][0]);
	$g_fld4 = array();
	$sql_head = select_query_json("select empsrno from employee_office where empcode='".$head[0]."'", "Centra", "TEST");
	for($i=0;$i<count($_REQUEST['txt_employee_code']);$i++)
	{	
		$emp=explode(' - ',$_REQUEST['txt_employee_code'][$i]);
		$sql_emp = select_query_json("select empsrno from employee_office where empcode='".$emp[0]."'", "Centra", "TEST");
		$g_fld['EMPHDSR'] = $sql_head[0]['EMPSRNO'];
		$g_fld['EMPSRNO'] = $sql_emp[0]['EMPSRNO'];
		$g_fld['EMPCODE'] = $emp[0];
		$g_fld['EMPNAME'] = $emp[1];
		$g_fld['DELETED'] = 'N';
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		// echo("----------------");
		// print_r($g_fld);
		$g_insert_subject = insert_dbquery($g_fld,$g_table);
	
	}
	
	if ($g_insert_subject==1) {
		echo "1";
		}else{
			echo "0";
		}
}
?>