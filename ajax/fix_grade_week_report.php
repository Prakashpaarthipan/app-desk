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
$sql_week = select_query_json("SELECT dt FROM (SELECT TRUNC(sysdate, 'DAY') + level - 1 dt FROM dual CONNECT BY level <= (TRUNC(sysdate) - TRUNC(sysdate, 'DAY'))+1)");
//$sql_week = select_query_json(" SELECT NEXT_DAY (SYSDATE - 7, 'SUN') AS PREV_SUNDAY ,SYSDATE AS TODAYS_DATE FROM DUAL");
//echo "1";
if($action == 'fix_grade') { 
//echo "2";
	//$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	//$field_apprq['DELETED'] = 'Y';
	//$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
	//$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$fromdate;
	//$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and DELETED='N'";
	

	//$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and GRDFXNO='".$fixnumber ."'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
	// Update in EMPLOYEE_GRADE_FIX Table


	 
	
	switch ($grade) {
		case 5:
			$grade = 'A++';
			break;
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
	
	//if($txt_empgrade_remarks!="")
	//{
	//$sql_date=1;
		foreach ($sql_week as $key => $week_value) {
			//echo "Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date."+1, 'yyyymmdd')||'0000000')+1) MAXGRDFXNO 
												//From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')";
												//echo $week_value[DT];
	 //$yyyymmdd_date = date("Ymd", strtotime($week_value['DT']));
	//$grade_fixno = select_query_json("Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date.", 'yyyymmdd')||$empsrno)) MAXGRDFXNO 
												//From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')", 'Centra', 'TEST');
												
	
												
											

			$field_apprq=array();
			
$yyyymmdd_date = "TO_DATE('".date("Ymd", strtotime($dt_value))."', 'YYYYMMDD')";
       $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT'])));
                                        //$and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";
		$grade_fixno = select_query_json("Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date."+1, 'yyyymmdd')||'0000000')+1) MAXGRDFXNO 
						From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')", 'Centra', 'TEST');					
						
    $tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['DELETED'] = 'Y';
	$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;					
    $where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and DELETED='N'";					
    $update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
				 
	$tbl_docs = "EMPLOYEE_GRADE_FIX";
	$field_docs['GRDFXNO'] =  $grade_fixno[0]['MAXGRDFXNO'];//$fixnumber;
	$field_docs['EMPSRNO'] = $empsrno;
	$field_docs['EMPHDSR'] = $_SESSION['tcs_empsrno'];
	$field_docs['EMPGRAD'] = $grade;
	$field_docs['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	$field_docs['ADDUSER'] = $_SESSION['tcs_usrcode'];
	$field_docs['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$dt_value;
	$field_docs['DELETED'] = 'N';
	
	$insert_docs = insert_test_dbquery($field_docs, $tbl_docs);
	if($txt_empgrade_remarks!="")
	{
		foreach ($sql_week as $key => $week_value) {
			$field_apprq=array();
                                      $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT'])));
                                        $and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";
		$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	
	echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		}
	}
	//print_r($field_docs);
		/*$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	
	echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);*/
	//$sql_date++;
	} 
//	}
	//print_r($field_docs);
	// INSERT in EMPLOYEE_GRADE_FIX Table
	//echo $insert_docs;
	/*if($grade!="")
	{
		foreach ($sql_week as $key => $week_value) {
			$field_apprq=array();
                                        $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT'])));
                                        $and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";

		$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['EMPGRAD'] = strtoupper($grade);
	
	echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		}
	}*/
} 


if($action == 'fix_grade1') { 
echo "2";
	//$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	//$field_apprq['DELETED'] = 'Y';
	//$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
	//$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$fromdate;
	//$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and DELETED='N'";
	

	//$where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and GRDFXNO='".$fixnumber ."'";
	// print_r($field_apprq); echo "<br>";
	//$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
	// Update in EMPLOYEE_GRADE_FIX Table


	 
	
	switch ($grade) {
		case 5:
			$grade = 'A++';
			break;
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
	
	//if($txt_empgrade_remarks!="")
	//{
	//$sql_date=1;
	
			//echo "Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date."+1, 'yyyymmdd')||'0000000')+1) MAXGRDFXNO 
												//From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')";
												//echo $week_value[DT];
	 //$yyyymmdd_date = date("Ymd", strtotime($week_value['DT']));
	//$grade_fixno = select_query_json("Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date.", 'yyyymmdd')||$empsrno)) MAXGRDFXNO 
												//From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')", 'Centra', 'TEST');
												
	
												
											

			$field_apprq=array();
			echo $fromdate;
 echo $yyyymmdd_date = "TO_DATE('".date("Ymd", strtotime($fromdate))."', 'YYYYMMDD')";
     echo $dt_value = strtoupper(date("d-M-Y", strtotime($fromdate)));
                                        //$and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";
		$grade_fixno = select_query_json("Select to_char(NVL(Max(GRDFXNO), TO_CHAR(".$yyyymmdd_date."+1, 'yyyymmdd')||'0000000')+1) MAXGRDFXNO 
						From EMPLOYEE_GRADE_FIX WHERE trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY')", 'Centra', 'TEST');					
						
    $tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['DELETED'] = 'Y';
	$field_apprq['DELUSER'] = $_SESSION['tcs_usrcode'];
	$field_apprq['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;					
    $where_apprq = "EMPSRNO = '".$empsrno."' and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') and DELETED='N'";	
    print_r($field_apprq);
    print_r($where_apprq);				
    $update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
				 
	$tbl_docs = "EMPLOYEE_GRADE_FIX";
	$field_docs['GRDFXNO'] =  $grade_fixno[0]['MAXGRDFXNO'];//$fixnumber;
	$field_docs['EMPSRNO'] = $empsrno;
	$field_docs['EMPHDSR'] = $_SESSION['tcs_empsrno'];
	$field_docs['EMPGRAD'] = $grade;
	$field_docs['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	$field_docs['ADDUSER'] = $_SESSION['tcs_usrcode'];
	$field_docs['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$dt_value;
	$field_docs['DELETED'] = 'N';
	print_r($field_docs);
	$insert_docs = insert_test_dbquery($field_docs, $tbl_docs);
	// if($txt_empgrade_remarks!="")
	// {
	// 	foreach ($sql_week as $key => $week_value) {
	// 		$field_apprq=array();
 //                                      $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT'])));
 //                                        $and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";
	// 	$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	// $field_apprq['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	
	// echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// // print_r($field_apprq); echo "<br>";
	// $update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
	// 	}
	// }
	//print_r($field_docs);
		/*$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['EMPRMRK'] = strtoupper($txt_empgrade_remarks);
	
	echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);*/
	//$sql_date++;
	//}
//	}
	//print_r($field_docs);
	// INSERT in EMPLOYEE_GRADE_FIX Table
	//echo $insert_docs;
	/*if($grade!="")
	{
		foreach ($sql_week as $key => $week_value) {
			$field_apprq=array();
                                        $dt_value = strtoupper(date("d-M-Y", strtotime($week_value['DT'])));
                                        $and = " and trunc(ADDDATE) = TO_DATE('".$dt_value."', 'DD-MON-YY') ";

		$tbl_apprq = "EMPLOYEE_GRADE_FIX";
	$field_apprq['EMPGRAD'] = strtoupper($grade);
	
	echo $where_apprq = "EMPSRNO = ".$empsrno.$and." and DELETED='N'";
	// print_r($field_apprq); echo "<br>";
	$update_apprq = update_test_dbquery($field_apprq, $tbl_apprq, $where_apprq);
		}
	}*/
} 

if($action == 'add_employee') {
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
		$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
	}
}
?>

