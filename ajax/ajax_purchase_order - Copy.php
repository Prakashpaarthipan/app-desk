<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(E_ALL);
$currentdate1 = strtoupper(date('d-M-Y h:i:s A'));
print_r($_REQUEST);
if($_REQUEST['action']=='update'){
  $status='';
	echo "entered";
	print_r($_REQUEST);
   $countfiles=count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name']);
   echo $countfiles;
	if(count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'])>0)
      { 
        $uploadfiles="";
        $time=strtotime('now');
        for($i=0;$i<count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name']);$i++){
          if($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i]!=null){
        echo $time.$_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i];
        $count=$i+1;
        $t= $_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i];
        
        $path_parts = pathinfo($t);
        $q=$_REQUEST['ZNCSRNO'].'_'.$_REQUEST['znepcde'].'_'.$_REQUEST['sta'].$count.$time.'.'.strtolower($path_parts['extension']);
        //        
        $tmp_name = $_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['tmp_name'][$i];    
      
        
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);
        $uploadfiles.=$q.',';

        $nameforserver = $_REQUEST['ZNCSRNO'].'_'.$_REQUEST['znepcde'].'_'.$_REQUEST['sta'].$count.$time.'.'.strtolower($path_parts['extension']);
        // echo "\n".$name."\n";
        //$alocal_file = "../uploads/admin_projects_local/attachments/".$name;
        $a1local_file =  "../uploads/purchase_order/".$q;
        //echo($a1local_file);
        //echo ($nameforserver);
        echo $server_file = 'Order_tracking_detail/'.$_REQUEST['poryear'].'_'.$_REQUEST['pornumb'].'/'.$nameforserver;
        //echo ($server_file);
        
        $upload = ftp_put($ftp_conn, $server_file, $a1local_file, FTP_BINARY);
          // echo($upload);
           
      }
          }
       
      }
    
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    $data = select_query_json("select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' AND ZNPSRNO='".$_REQUEST['znepcde']."'", "Centra", 'TEST');
     $currentdate = strtoupper(date('d/m/Y h:i:s A'));                                      
     $curdate=date_create($currentdate);
     $diff=date_diff($addeddate,$curdate);
     $v= $diff->d;
     if($_REQUEST['prostat']==1){
      $status='U';
     }
     else if($_REQUEST['prostat']==2){
      $status='P';
     }
     else if($_REQUEST['prostat']==3){
      $status='C';
     }else{
      $status='U';
     }
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['ZNCSRNO'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcde'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']=$status;
    $g_fld['ZNEADDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$data[0]['ADDDATE'];
    $g_fld['ZNEEDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_usrcode'];
    $g_fld['REMARKS']=$_REQUEST['remarks'.$_REQUEST['id'].$_REQUEST['sta']];
    $g_fld['DELETED']="N";
    $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['IMGLOCA']=$uploadfiles;
    print_r($g_fld);
    echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    
    $up_table="ORDER_TRACKING_DETAIL";
    $up_fld=array();
    $up_fld['EDTUSER']=$_SESSION['tcs_usrcode'];
    $up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $up_fld['REMARKS']=$_REQUEST['remarks'.$_REQUEST['id'].$_REQUEST['sta']];
    $up_fld['ZNESTAT']='N';
    $up_fld['PROSTAT']=$_REQUEST['prostat'];
    print_r($up_fld);
   echo $where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' AND ZNPSRNO='".$_REQUEST['znepcde']."' and DELETED='N'";
     
    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

     $up_table1="ORDER_TRACKING_DETAIL";
    $up_fld1=array();
    
    $up_fld1['PROSTAT']=$_REQUEST['prostat'];
    print_r($up_fld);
   echo $where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' and DELETED='N'";
     
    echo $insert_appplan11 = update_test_dbquery($up_fld1, $up_table1, $where_appplan1);



}
if($_REQUEST['action']=='revert'){
	
	print_r($_REQUEST);
	
    $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
    
   $data = select_query_json("select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' AND ZNPSRNO='".$_REQUEST['znepcode']."'", "Centra", 'TEST');
     $addeddate=date_create($data[0]['ADDDATE']);
     $currentdate = strtoupper(date('d/m/Y h:i:s A'));                                      
     $curdate=date_create($currentdate);
     $diff=date_diff($addeddate,$curdate);
     $v= $diff->d;

    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['ZNCSRNO'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']="R";
    $g_fld['ZNEADDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$data[0]['ADDDATE'];
    $g_fld['ZNEEDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_usrcode'];
    $g_fld['REMARKS']=$_REQUEST['remarks'.$_REQUEST['id'].$_REQUEST['sta']];
    $g_fld['DELETED']="N";
    $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['IMGLOCA']="";

     echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
    	$up_table="ORDER_TRACKING_DETAIL";
		$up_fld=array();
		$up_fld['EDTUSER']=$_SESSION['tcs_usrcode'];
		$up_fld['REMARKS']=$_REQUEST['remarks'];
		$up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
		$up_fld['ZNEFIND']='';
		$up_fld['ZNESTAT']='R';
    $up_fld['PROSTAT']=0;
		$where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' and DELETED='N' AND ZNPSRNO='".$_REQUEST['znepcode']."'";
	    print_r($up_fld);
	    print_r($where_appplan);
	    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);

	   echo	$sel=select_query_json("SELECT ZNCSRNO,ZNPSRNO,SUPCODE from order_tracking_detail where ((select MAX(ZNCSRNO||'.'||ZNPSRNO) From order_tracking_detail WHERE (ZNCSRNO||'.'||ZNPSRNO) < ( SELECT Max(ZNCSRNO||'.'||ZNPSRNO) FROM order_tracking_detail))>=('".$_REQUEST['ZNCSRNO']."'||'.'||'".$_REQUEST['znepcode']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT='F' and PORNUMB='".$_REQUEST['pornumb']."' and DELETED='N' order by ZNCSRNO desc,ZNPSRNO desc", "Centra", 'TEST');
	   
        $up_table="ORDER_TRACKING_DETAIL";
		$up1_fld=array();
		$up1_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
		$up1_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
		$up1_fld['EDTUSER']="";
		$up1_fld['EDTDATE']="";
		$up1_fld['ZNESTAT']='N';
    $up_fld1['PROSTAT']=0;
		$where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".($sel[0]['ZNCSRNO'])."' and DELETED='N' AND ZNPSRNO='".$sel[0]['ZNPSRNO']."'";
	    print_r($up1_fld);
	    print_r($where_appplan1);
	   echo $insert_appplan1 = update_test_dbquery($up1_fld, $up_table, $where_appplan1);

}
if($_REQUEST['action']=='finish'){
  $status='';
print_r($_REQUEST);
$countfiles=count($_FILES['upload']['name']);
	if(count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'])>0)
      { 
        $uploadfiles="";
        $time=strtotime('now');
        for($i=0;$i<count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name']);$i++){
          if($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i]!=null){
        echo $time.$_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i];
        $count=$i+1;
        $t= $_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i];
        
        $path_parts = pathinfo($t);
        $q=$_REQUEST['ZNCSRNO'].'_'.$_REQUEST['znepcde'].'_'.$_REQUEST['sta'].$count.$time.'.'.strtolower($path_parts['extension']);
        //        
        $tmp_name = $_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['tmp_name'][$i];    
      
        
        $a1local_file = "../uploads/purchase_order/".$q;
        move_uploaded_file($tmp_name, $a1local_file);
        $uploadfiles.=$q.',';

        $nameforserver = $_REQUEST['ZNCSRNO'].'_'.$_REQUEST['znepcde'].'_'.$_REQUEST['sta'].$count.$time.'.'.strtolower($path_parts['extension']);
        // echo "\n".$name."\n";
        //$alocal_file = "../uploads/admin_projects_local/attachments/".$name;
        $a1local_file =  "../uploads/purchase_order/".$q;
        //echo($a1local_file);
        //echo ($nameforserver);
        echo $server_file = 'Order_tracking_detail/'.$_REQUEST['poryear'].'_'.$_REQUEST['pornumb'].'/'.$nameforserver;
        //echo ($server_file);
        
           $upload = ftp_put($ftp_conn, $server_file, $a1local_file, FTP_BINARY);
          // echo($upload);
           
      }
          }
       
      }
    
		
		$count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
	  	$data = select_query_json("select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' AND ZNPSRNO='".$_REQUEST['znepcode']."'", "Centra", 'TEST');
     $addeddate=date_create($data[0]['ADDDATE']);
     $currentdate = strtoupper(date('dd-Mon-yyyy h:i:s A'));                                      
     $curdate=date_create($currentdate);
     $diff=date_diff($addeddate,$curdate);
     $v= $diff->d;
     if($_REQUEST['prostat']==1){
      $status='U';
     }
     else if($_REQUEST['prostat']==2){
      $status='P';
     }
     else if($_REQUEST['prostat']==3){
      $status='C';
     }else{
      $status='F';
     }
    echo $v.'sfasfasfasfa';
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['ZNCSRNO'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']=$status;
    $g_fld['ZNEADDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$data[0]['ADDDATE'];
    $g_fld['ZNEEDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_usrcode'];
    $g_fld['REMARKS']=$_REQUEST['remarks'.$_REQUEST['id'].$_REQUEST['sta']];
    $g_fld['DELETED']="N";
    $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['IMGLOCA']=$uploadfiles;
 	 echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);	
		$up_table="ORDER_TRACKING_DETAIL";
		$up_fld=array();
		$up_fld['EDTUSER']=$_SESSION['tcs_usrcode'];
		$up_fld['REMARKS']=$_REQUEST['remarks'.$_REQUEST['id'].$_REQUEST['sta']];
    $up_fld['PROSTAT']=$_REQUEST['prostat'];
		$up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
		$up_fld['ZNEFIND']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
		$up_fld['ZNESTAT']='F';
		$where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' AND ZNPSRNO='".$_REQUEST['znepcode']."' and DELETED='N'";


    $up_table1="ORDER_TRACKING_DETAIL";
    $up_fld1=array();
    
    $up_fld1['PROSTAT']=$_REQUEST['prostat'];
    print_r($up_fld1);
   echo $where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['ZNCSRNO']."' and DELETED='N'";
     
    echo $insert_appplan11 = update_test_dbquery($up_fld1, $up_table1, $where_appplan1);
	    print_r($up_fld);
	    print_r($where_appplan);
	    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);
	    echo "SELECT ZNCSRNO,ZNPSRNO,SUPCODE from order_tracking_detail where ((select MAX(ZNCSRNO||'.'||ZNPSRNO) From order_tracking_detail WHERE (ZNCSRNO||'.'||ZNPSRNO) < ( SELECT Max(ZNCSRNO||'.'||ZNPSRNO) FROM order_tracking_detail))>('".$_REQUEST['ZNCSRNO']."'||'.'||'".$_REQUEST['znepcde']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT!='F' and PORNUMB='".$_REQUEST['pornumb']."'";
	   $sel=select_query_json("SELECT ZNCSRNO,ZNPSRNO,SUPCODE from order_tracking_detail where ((select MAX(ZNCSRNO||'.'||ZNPSRNO) From order_tracking_detail WHERE (ZNCSRNO||'.'||ZNPSRNO) < ( SELECT Max(ZNCSRNO||'.'||ZNPSRNO) FROM order_tracking_detail))>('".$_REQUEST['ZNCSRNO']."'||'.'||'".$_REQUEST['znepcde']."')) and SUPCODE='".$_REQUEST['supcode']."' and ZNESTAT!='F' and DELETED='N' and PORNUMB='".$_REQUEST['pornumb']."'", "Centra", 'TEST');
	   	if($sel){
	   	print_r($sel);
        $up_table="ORDER_TRACKING_DETAIL";
		$up1_fld=array();
		$up1_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
		$up1_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
		$up1_fld['EDTUSER']="";
		$up1_fld['EDTDATE']="";
		$up1_fld['PROSTAT']=0;
		$up1_fld['ZNESTAT']='N';
		$where_appplan1="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".($sel[0]['ZNCSRNO'])."' AND DELETED='N' AND ZNPSRNO='".$sel[0]['ZNPSRNO']."'";
	    print_r($up1_fld);
	    print_r($where_appplan1);
	    echo $insert_appplan1 = update_test_dbquery($up1_fld, $up_table, $where_appplan1);
	    	}
	    
}	
?>