<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
 include_once('../lib/function_connect.php');
print_r($_REQUEST);

 $current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
 //echo "current year".$current_year;
 //echo "------------1".$data."1----------------";
$EMP=explode(' - ',$_REQUEST['employee']);
$EMP_DET = select_query_json("select BRNCODE,EMPSRNO,EMPCODE,EMPNAME,ESECODE,DESCODE,ESECODE from EMPLOYEE_OFFICE where EMPCODE='".$EMP[0]."'","Centra","TEST");
echo("select BRNCODE,EMPSRNO,EMPCODE,EMPNAME,ESECODE,DESCODE,ESECODE from EMPLOYEE_OFFICE where EMPCODE='".$EMP[0]."'");
 $notval = select_query_json("select count(*)+1 MAXNUM from employee_notice_detail", "Centra", 'TEST');
 $usrnot = select_query_json("select count(*)+1 MAXNOT from employee_notice_detail WHERE EMPCODE='".$EMP[0]."'", "Centra", 'TEST');

 print_r($EMP_DET);
 print_r($notval);

		$g_table = "EMPLOYEE_NOTICE_ENTRY";
		$g_fld = array();
	    $g_fld4['BRNCODE']= $EMP_DET[0]['BRNCODE'];
        $g_fld4['NOTYEAR']= $current_yr[0]['PORYEAR'];
        $g_fld4['NOTNUMB']= $notval[0]['MAXNUM'];
        $g_fld4['EMPSRNO']= $EMP_DET[0]['EMPSRNO'];
        $g_fld4['EMPCODE']= $EMP_DET[0]['EMPCODE'];
        $g_fld4['EMPNAME']= $EMP_DET[0]['EMPNAME'];
        $g_fld4['ESECODE']= $EMP_DET[0]['ESECODE'];
        $g_fld4['DESCODE']= $EMP_DET[0]['DESCODE'];
        $g_fld4['NOTCODE']= '';
        $g_fld4['NOTNAME']= 'ALERT NOTICE-'.$usrnot[0]['MAXNOT'];
        $g_fld4['REMARKS']= $_REQUEST['message'];
        $g_fld4['AUTSRNO']= $_REQUEST['auth_by'];
        $g_fld4['STATUS']= 'N';
        $g_fld4['NOTMODE']= 'N';
        $g_fld4['EMP_STATUS']=''; 
        $g_fld4['EMP_REMARKS']= '';
        $g_fld4['ADDUSER']= $_SESSION['tcs_usrcode'];
        $g_fld4['ADDDATE']= 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $g_fld4['EDTUSER']= '';
        $g_fld4['EDTDATE']= '';
        $g_fld4['DELETED']= 'N';
        $g_fld4['DELUSER']= '';
        $g_fld4['DELDATE']='';
        print_r($g_fld4);
		$g_insert_subject = insert_test_dbquery($g_fld4,$g_table);
		
?>