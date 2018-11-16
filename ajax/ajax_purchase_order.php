<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
session_start();
error_reporting(E_ALL);
$currentdate1 = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
print_r($_REQUEST);
if($_REQUEST['action']=='update'){
	echo "entered";
	print_r($_REQUEST);
   $countfiles=count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name']);
   echo $countfiles;
	if(count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'])>0)
  { 
    $uploadfiles="";
    $time=strtotime('now');
    for($i=0;$i<count($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name']);$i++)
    {
      if($_FILES['upload'.$_REQUEST['id'].$_REQUEST['sta']]['name'][$i]!=null)
      {
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

    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['ZNCSRNO'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcde'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']="U";
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
    echo $v.'sfasfasfasfa';
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['ZNCSRNO'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcode'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']="F";
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
if($_REQUEST['action']=='sendmail'){
  print_r($_REQUEST);

$empsrno=implode(',',$_REQUEST['empsrno']);
echo $empsrno;

if($_REQUEST['prostat']==1){
  $status= 'UPDATE';
  $sta='U';
}
else if($_REQUEST['prostat']==2){
  $status='PROCESS';
  $sta='P';
}
else if($_REQUEST['prostat']==3){
  $status='COMPLETE';
  $sta='C';
}
echo $msg=$_REQUEST['mailremarks'.$_REQUEST["znecode"].$_REQUEST["znepcde"]];
echo "select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO IN (".$empsrno.") and BRNCODE='888'";
$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO IN (".$empsrno.") and BRNCODE='888'", 'Centra', 'TCS');
$tomailid=array();
if(count($sql_email)>0){
foreach($sql_email as $key=>$value){
$tomailid[]=$value['EMAILID'];
}
$tomail=implode(',',$tomailid);
}
//$tomail='infotech.rameshm@thechennaisilks.com,infotech.prakashp@thechennaisilks.com';

echo $tomail;
//$tomail='infotech.rameshm@thechennaisilks.com;infotech.prakashp@thechennaisilks.com';
//$txt_email = rtrim($tomail, ',');
//$to1 = $txt_email; 
$subject1 = substr("PO:".$_REQUEST['poryear']." - ".$_REQUEST['pornumb'], 0, 100);
// $mail_body1 = '<html><body><table border=0 cellpadding=1 cellspacing=1 width="100%">
// <tr><td height="25" align="left" colspan=2>Dear Sir,</td></tr>
// <tr><td height="25" align="left" colspan=2>'.$msg.'</td></tr>

// <tr height="25"></tr>
// <tr><td colspan=2>
//   Thank you,
//   <BR>Approval Desk Team
//  </td></tr>
// </table></body></html>';
echo $mail_body1 = "<BR>Dear Sir,<BR>&nbsp;&nbsp;&nbsp;".strtoupper($msg)."<BR><BR><BR><BR>Thank you";

//$sql_mailsend = store_procedure_query_json("PORTAL_APPROVED_AUTO_MAIL('".$txt_approval_number."', 'R', '".$subject1."', '".$mail_body1."', '".$_SESSION['tcs_usrcode']."')", 'Req', 'TCS'); 

$sql_mailsend = store_procedure_query_json("PROC_APP_MAIL_SENDING('".$subject1."','".$mail_body1."','0','".$_SESSION['tcs_usrcode']."','".$tomail."','1','APPDESK')", 'Req', 'TCS'); 




//$mail_body1 = "<BR>Dear Sir,<BR>".strtoupper($msg)."<BR>Thank you,<BR>Approval Desk Team<BR>";

//$mail_body1 = $_REQUEST['poryear'].' - '.$_REQUEST['pornumb'].'[ '.$_REQUEST['pordate'].' ]'." is in ".strtoupper($status);

// $sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary where MAILYEAR='".$current_yr[0]['PORYEAR']."'", 'Centra', 'TCS');
// $tbl_name="mail_send_summary";
// $field_values=array();
// $field_values['MAILYEAR'] = $current_yr[0]['PORYEAR'];
// $field_values['MAILNUMB'] = $sql_mailnum[0]['MAILNUMB'];
// $field_values['DEPTID']   = 1;
// $field_values['MAILSUB']  = $subject1;
// $field_values['MAILCON']  = $mail_body1;
// $field_values['FILECNT']  = 0;
// $field_values['ADDUSER']  = $_SESSION['tcs_usrcode'];
// $field_values['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
// $field_values['EMAILID']  = $tomail;
// $field_values['STATUS']   = 'N';
// $field_values['DEPNAME']  = 'APP DESK';
// print_r($field_values);
// echo $insert_response = insert_test_dbquery($field_values, $tbl_name);

$count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
      echo "select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['znecode']."' AND ZNPSRNO='".$_REQUEST['znepcde']."'";


       $data = select_query_json("select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['znecode']."' AND ZNPSRNO='".$_REQUEST['znepcde']."'", "Centra", 'TEST');
     $addeddate=date_create($data[0]['ADDDATE']);
     $currentdate = strtoupper(date('dd-Mon-yyyy h:i:s A'));                                      
     $curdate=date_create($currentdate);
     $diff=date_diff($addeddate,$curdate);
     $v= $diff->d;
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['znecode'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcde'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']="M";
    $g_fld['NOTEMP']=$empsrno;
    $g_fld['NOTMSG']=$msg;
    $g_fld['ZNEADDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$data[0]['ADDDATE'];
    $g_fld['ZNEEDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_SESSION['tcs_empsrno'];
    $g_fld['REMARKS']='Notification Sent';
    $g_fld['DELETED']="N";
    $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['IMGLOCA']="";
    print_r($g_fld);
   echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);  




}
if($_REQUEST['action']=='updateprocess'){
  print_r($_REQUEST);
if($_REQUEST['prostat']==1){
  $status= 'UPDATE';
  $sta='U';
}
else if($_REQUEST['prostat']==2){
  $status='PROCESS';
  $sta='P';
}
else if($_REQUEST['prostat']==3){
  $status='COMPLETE';
  $sta='C';
}

 $count = select_query_json("select nVL(count(*),0)+1 ENTSRNO FROM order_tracking_history", "Centra", 'TEST');
      echo "select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['znecode']."' AND ZNPSRNO='".$_REQUEST['znepcde']."'";


       $data = select_query_json("select to_char(ADDDATE,'dd-Mon-yyyy HH:MI:SS AM') ADDDATE FROM order_tracking_detail where PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['znecode']."' AND ZNPSRNO='".$_REQUEST['znepcde']."'", "Centra", 'TEST');
     $addeddate=date_create($data[0]['ADDDATE']);
     $currentdate = strtoupper(date('dd-Mon-yyyy h:i:s A'));                                      
     $curdate=date_create($currentdate);
     $diff=date_diff($addeddate,$curdate);
     $v= $diff->d;
    $g_table="ORDER_TRACKING_HISTORY";
    $g_fld=array();
    $g_fld['PORYEAR']=$_REQUEST['poryear'];
    $g_fld['PORNUMB']=$_REQUEST['pornumb'];
    $g_fld['ZNCSRNO']=$_REQUEST['znecode'];
    $g_fld['ZNPSRNO']=$_REQUEST['znepcde'];
    $g_fld['ZNEDAYS']=$v;
    $g_fld['ZNESTAT']="U";
    $g_fld['ZNEADDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$data[0]['ADDDATE'];
    $g_fld['ZNEEDDT']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['ENTSRNO']=$count[0]['ENTSRNO'];
    $g_fld['EMPSRNO']=$_REQUEST['empsrno'];
    $g_fld['REMARKS']='In '.$status;
    $g_fld['DELETED']="N";
    $g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
    $g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;
    $g_fld['IMGLOCA']="";
    print_r($g_fld);
   echo $g_insert_subject = insert_test_dbquery($g_fld, $g_table);  


 $up_table="ORDER_TRACKING_DETAIL";
    $up_fld=array();
    $up_fld['EDTUSER']=$_SESSION['tcs_usrcode'];
    $up_fld['EDTDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate1;    
    $up_fld['PROSTAT']=$_REQUEST['prostat'];
    print_r($up_fld);
   echo $where_appplan="PORNUMB='".$_REQUEST['pornumb']."' AND PORYEAR='".$_REQUEST['poryear']."' AND SUPCODE='".$_REQUEST['supcode']."' AND ZNCSRNO='".$_REQUEST['znecode']."' and DELETED='N'";
     
    echo $insert_appplan1 = update_test_dbquery($up_fld, $up_table, $where_appplan);


}
?>