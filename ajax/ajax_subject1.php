<?
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
/*include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
 include('../approval_desk-ftp/lib/config.php');
include('../db_connect/public_functions.php');
include('../approval_desk-ftp/general_functions.php'); 
*/
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));


extract($_REQUEST);
print_r($_REQUEST);
 
if($_SESSION['tcs_userid'] == ""){ ?>
    <script>window.location="index.php";</script>
<?php exit();
}

if($_REQUEST['action'] == "edit"){ ?>
    <script>window.location="request_list.php";</script>
<?php exit();
}



	if($_REQUEST['action'] == "insert"){ 

			$sr=explode(' - ',$_REQUEST['id']);
			//print_r($_REQUEST['id']);
			//echo $sr;
		echo "select empsrno from employee_office where empcode=".$sr[0]."";
		//echo "select min(brnhdsr) BRNHDSR from approval_branch_head where brncode='".$_REQUEST['brncode']."' and apmcode='".$_REQUEST['id1']."' and deleted='N'";
			$sql_emp = select_query_json("select empsrno from employee_office where empcode=".$sr[0]."","Centra", "TCS");
			$sql_emp1=select_query_json("select min(brnhdsr) BRNHDSR from approval_branch_head where brncode='".$_REQUEST['brncode']."' and apmcode='".$_REQUEST['id1']."' and deleted='N'","Centra","TEST");

			//SELECT *FROM pieces WHERE price =  ( SELECT MIN(price) FROM pieces )
			//echo(rand() . "<br>");
            //echo(rand() . "<br>");
            //echo(rand(10,100));

 echo $sql_emp1[0]['BRNHDSR']." minimum branch hdsr";
			 $a=rand(1,10);
			  $b=rand(100,1000);
			  //$c=rand(1,50);
			 $g_table = "APPROVAL_BRANCH_HEAD";
			 $g_fld = array();
			 $g_fld['BRNHDCD'] =  $a;
			 $g_fld['BRNCODE'] =  $_REQUEST['brncode'];
			 $g_fld['EMPSRNO'] =  $sql_emp[0]['EMPSRNO'];
			 $g_fld['EMPCODE'] =  $sr[0];
			 $g_fld['EMPNAME'] =  $sr[1];
			 $g_fld['ADDUSER'] =  $_SESSION['tcs_usrcode'];
			 $g_fld['ADDDATE'] =  'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			 $g_fld['EDTUSER'] =  '';
			 $g_fld['EDTDATE'] =  '';
		     $g_fld['DELETED'] =  'N';
			 $g_fld['DELUSER'] =  '';
			 $g_fld['DELDATE'] =  '';
			 $g_fld['BRNHDSR'] =  $sql_emp1[0]['BRNHDSR']-1;
			 $g_fld['DEPCODE'] =  '';
			 $g_fld['TARNUMB'] =  '';
			 $g_fld['APRVALU'] =  $b;
			 $g_fld['APMCODE'] =  $_REQUEST['id1'];
			
			 //echo("----------------");
			 print_r($g_fld);
			
			 $g_insert_subject = insert_test_dbquery($g_fld,$g_table);
	}
?>