<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(E_ALL);
if($_REQUEST['action']=='update'){
	print_r($_REQUEST);
	if($_FILES['upload']['name'] != '')
      { 
        
        ///----------updating the index to attachment to local
        $q=$_FILES['upload']['name'];
        
        $tmp_name = $_FILES["upload"]["tmp_name"];       
        
      
        // echo "\n".$name."\n";
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);

       
      }
    $currentdate = strtoupper(date('d-M-Y h:i:s A'));
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNECODE']=$_REQUEST['znecode'];
    $g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
    $g_fld['REMARKS']=$_REQUEST['remarks'];
    $g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    
   echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    
    $up_table="ORDER_TRACKING_DETAIL";
    $up_fld=array();
    $up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
    $up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
    $up_fld['REMARKS']=$_REQUEST['remarks'];;
    $up_fld['ZNESTAT']='N';
   echo $where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
     
    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

}
else{
 if($_FILES['upload']['name'] != '')
      { 
        
        ///----------updating the index to attachment to local
       echo $q=$_FILES['upload']['name'];
        
        $tmp_name = $_FILES["upload"]["tmp_name"];       
        
      
        // echo "\n".$name."\n";
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);

       
    	}

		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		$count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
	  	print_r($_REQUEST);
		$g_table="ORDER_TRACKING_HISTORY";
		$g_fld=array();
		$g_fld['PORYEAR']=$_REQUEST['poryear'];
		$g_fld['PORNUMB']=$_REQUEST['pornumb'];
		$g_fld['ZNECODE']=$_REQUEST['znecode'];
		$g_fld['ZNEPCDE']=$_REQUEST['znepcode'];
		$g_fld['ZNEDAYS']=$_REQUEST['znecode']+1;
		$g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
		$g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
		$g_fld['REMARKS']=$_REQUEST['remarks'];
		$g_fld['ADDUSER']=$_SESSION['tcs_empsrno'];
		$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		print_r($g_fld);
		echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
 		
		$up_table="ORDER_TRACKING_DETAIL";
		$up_fld=array();
		$up_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
		$up_fld['REMARKS']=$_REQUEST['remarks'];
		$up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNEFIND']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$up_fld['ZNESTAT']='F';
		$where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".$_REQUEST['znecode']."' AND ZNEPCDE='".$_REQUEST['znepcode']."'";
	    print_r($up_fld);
	    print_r($where_appplan);
	    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

	   	$sel=select_query_json("SELECT ZNECODE,ZNEPCDE,SUPCODE from order_tracking_detail where ((select MAX(ZNECODE||'.'||ZNEPCDE) From order_tracking_detail WHERE (ZNECODE||'.'||ZNEPCDE) < ( SELECT Max(ZNECODE||'.'||ZNEPCDE) FROM order_tracking_detail))>('".$_REQUEST['znecode']."'||'.'||'".$_REQUEST['znepcde']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT!='F' and PORNUMB='".$_REQUEST['pornumb']."'", "Centra", 'TEST');
	   	print_r($sel);
        $up_table="ORDER_TRACKING_DETAIL";
		$up1_fld=array();
		$up1_fld['EDTUSER']=$_SESSION['tcs_empsrno'];
		$up1_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		
		$up1_fld['ZNESTAT']='N';
		$where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNECODE='".($sel[0]['ZNECODE'])."' AND ZNEPCDE='".$sel[0]['ZNEPCDE']."'";
	    print_r($up1_fld);
	    print_r($where_appplan1);
	    echo $insert_appplan1 = update_test_dbquery($up1_fld, $up_table, $where_appplan1);


}	   
?>
