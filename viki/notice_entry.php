<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
 include_once('../lib/function_connect.php');
 //header('Location: ../employee_notice_entry.php');
//print_r($_REQUEST);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
if($_REQUEST['action']=='insert')
{
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');

//$currentdate = strtoupper(date('d-M-Y h:i:s A'));
 //echo "current year".$current_year;
 //echo "------------1".$data."1----------------";
$EMP=explode(' - ',$_REQUEST['employee']);
$EMP_DET = select_query_json("select BRNCODE,EMPSRNO,EMPCODE,EMPNAME,ESECODE,DESCODE,ESECODE from EMPLOYEE_OFFICE where EMPCODE='".$EMP[0]."'","Centra","TEST");
//echo("select BRNCODE,EMPSRNO,EMPCODE,EMPNAME,ESECODE,DESCODE,ESECODE from EMPLOYEE_OFFICE where EMPCODE='".$EMP[0]."'");
 $notval = select_query_json("select count(*)+1 MAXNUM from employee_notice_detail", "Centra", 'TEST');
 $usrnot = select_query_json("select count(*)+1 MAXNOT from employee_notice_detail WHERE EMPCODE='".$EMP[0]."'", "Centra", 'TEST');

 //print_r($EMP_DET);
 //print_r($notval);

        $g_table = "employee_notice_detail";
        $g_fld = array();
        $g_fld4['BRNCODE']= $EMP_DET[0]['BRNCODE'];
        $g_fld4['NOTYEAR']= $current_yr[0]['PORYEAR'];
        $g_fld4['NOTNUMB']= $notval[0]['MAXNUM'];
        $g_fld4['EMPSRNO']= $EMP_DET[0]['EMPSRNO'];
        $g_fld4['EMPCODE']= $EMP_DET[0]['EMPCODE'];
        $g_fld4['EMPNAME']= $EMP_DET[0]['EMPNAME'];
        $g_fld4['ESECODE']= $EMP_DET[0]['ESECODE'];
        $g_fld4['DESCODE']= $EMP_DET[0]['DESCODE'];
        $g_fld4['NOTCODE']= '1';
        $g_fld4['NOTNAME']= 'ALERT NOTICE-'.$usrnot[0]['MAXNOT'];
        $g_fld4['REMARKS']= strtoupper($_REQUEST['message']);
        $g_fld4['AUTSRNO']= $_REQUEST['auth_by'];
        $g_fld4['STATUS']= 'N';
        $g_fld4['NOTMODE']= 'N';
        $g_fld4['EMP_STATUS']='N'; 
        $g_fld4['EMP_REMARKS']= '';
        $g_fld4['ADDUSER']= $_SESSION['tcs_usrcode'];
        $g_fld4['ADDDATE']= 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $g_fld4['EDTUSER']= '';
        $g_fld4['EDTDATE']= '';
        $g_fld4['DELETED']= 'N';
        $g_fld4['DELUSER']= '';
        $g_fld4['DELDATE']='';
        //print_r($g_fld4);
        $g_insert_subject = insert_dbquery($g_fld4,$g_table);
        $val=$current_yr[0]['PORYEAR'].','.$notval[0]['MAXNUM'];
        print_r($val);
        die();
}

if($_REQUEST['action']=='alert_txt')
{        $usrnot = select_query_json("select count(*)+1 MAXNOT from employee_notice_detail WHERE EMPCODE='".$_REQUEST['empsrno']."'", "Centra", 'TEST');
	 echo('ALERT NOTICE-'.$usrnot[0]['MAXNOT']);
}

if($_REQUEST['action']=='reply')
{   //print_r($_REQUEST);
	$g_table = "employee_notice_detail";
	$g_fld4 = array();
	$g_fld['EMP_STATUS'] = 'Y'; 
	$g_fld['EMP_REMARKS'] = strtoupper($_REQUEST['remarks']);
	$g_fld['EDTUSER'] = $_SESSION['tcs_usrcode']; 
	$g_fld['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
	$where_appplan="NOTYEAR = '".$_REQUEST['notyear']."' and NOTNUMB = '".$_REQUEST['notnumb']."'";
	//print_r($g_fld);
	//print_r($where_appplan);
	$insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);
}
if($_REQUEST['action']=='approve')
{   //print_r($_REQUEST);
        $g_table = "employee_notice_detail";
        $g_fld4 = array();
        if($_REQUEST['val']=='1')
        {
                $g_fld['NOTSTAT'] = 'A'; 
        }
        if($_REQUEST['val']=='2')
        {
                $g_fld['NOTSTAT'] = 'R'; 
        }
        $g_fld['APPUSER'] = $_SESSION['tcs_usrcode']; 
        $g_fld['APPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        $where_appplan="NOTYEAR = '".$_REQUEST['notyear']."' and NOTNUMB = '".$_REQUEST['notnumb']."'";
        print_r($g_fld);
        print_r($where_appplan);
        $insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where_appplan);
}
?>