<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
include_once('../lib/function_connect.php');
print_r($_REQUEST);
print_r($_FILES);
$RESSRNO = select_query_json("Select nvl(Max(RESSRNO),0)+1 RESSRNO From SERVICE_REGISTER_RESPONSE where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
$REQSRNO = select_query_json("Select nvl(Max(REQSRNO),0) REQSSRNO From SERVICE_REGISTER_ENTRY where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
print_r($REQSRNO);
print_r($RESSRNO);
$folder_exists = is_dir('ftp://'.$ftp_user_name_159.":".$ftp_user_pass_159."@".$ftp_server_159.'/CALL_CENTRE/REQUESTS/'.$_REQUEST['reqnumb'].'_'.$REQSRNO[0]['REQSSRNO'].'_'.$RESSRNO[0]['RESSRNO'].'/');
if($folder_exists==true){

}
else
{
  $ftp_conn = ftp_connect($ftp_server_159) or die("Could not connect to $ftp_server");
  $login = ftp_login($ftp_conn,$ftp_user_name_159, $ftp_user_pass_159);
  $dir = '/CALL_CENTRE/REQUESTS/'.$_REQUEST['reqnumb'].'_'.$REQSRNO[0]['REQSSRNO'].'_'.$RESSRNO[0]['RESSRNO'].'/';
  echo($dir);
  if (ftp_mkdir($ftp_conn, $dir))
  {
  echo "Successfully created $dir";
  }
  else
  {
  echo "Error while creating $dir";
  }
  ftp_close($ftp_conn);
}
$ftp_conn = ftp_connect($ftp_server_159) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn,$ftp_user_name_159, $ftp_user_pass_159);
$noa = sizeof($_FILES['attachments']['name']);
for($k=1;$k<=$noa;$k++)
{
  if($_FILES['attachments']['name'][$k-1] != null)
  {
    echo('came');
    $name=$_FILES['attachments']['name'][$k-1];
    $tmp_name = $_FILES["attachments"]["tmp_name"][$k-1];

    $a1local_file = $name;
    move_uploaded_file($tmp_name, $a1local_file);

    $path_parts = pathinfo($f);

    $alocal_file = $name;
    $server_file = '/CALL_CENTRE/REQUESTS/'.$_REQUEST['reqnumb'].'_'.$REQSRNO[0]['REQSSRNO'].'_'.$RESSRNO[0]['RESSRNO'].'/'.$name;
    $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
    echo('UPLOADED');
    unlink($alocal_file);
  }
}
if($_SESSION['tcs_user']!='')
{

  //$REQSRNO = select_query_json("Select nvl(Max(REQSRNO),0) REQSRNO From SERVICE_REGISTER_ENTRY where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
  $REQ = select_query_json("Select * From service_register_entry where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
  $txt_assign=explode(' - ',$_REQUEST['txt_assign']);
  $g_table = "SERVICE_REGISTER_RESPONSE";
  $g_fld = array();
  $g_fld['REQNUMB']=$_REQUEST['reqnumb'];
  $g_fld['REQSRNO']=$REQSRNO[0]['REQSSRNO'];
  $g_fld['RESSRNO']=$RESSRNO[0]['RESSRNO'];
  $g_fld['RESDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
  $g_fld['RESFUSR']=$_SESSION['tcs_user'];
  $g_fld['RESPATH']='ftp://'.$ftp_server_159.'/CALL_CENTRE/REQUESTS/'.$_REQUEST['reqnumb'].'_'.$REQSRNO[0]['REQSSRNO'].'_'.$RESSRNO[0]['RESSRNO'].'/';
  $g_fld['RESDEVID']=$_SERVER['REMOTE_ADDR'];
  if($_REQUEST['txt_assign']!='')
  {
    $g_fld['RESSTAT'] = 'A';
    $g_fld['RESMSG']=$_REQUEST['message'];
    $g_fld['RESTUSR'] = $txt_assign[0];
  }
  else {
    $g_fld['RESSTAT'] = $REQ[0]['REQSTAT'];
    $g_fld['RESMSG']=$_REQUEST['message'];
    $g_fld['RESTUSR'] = '';
  }
  //print_r($g_fld);
  $g_insert_subject = insert_test_dbquery($g_fld, $g_table);

  if($_REQUEST['txt_assign']!='')
  {          //updatin the rentry table for supplier
            $g_table = "service_register_entry";
            $g_fld4 = array();
            $g_fld4['REQSTAT'] = 'A';
            //$g_fld4['RESLVUSER'] =$_SESSION['tcs_user'];
            //$g_fld['ASGNMBR'] =$_REQUEST['txt_assign'];
            //$g_fld4['RESLVDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
            $where_appplan="reqnumb ='".$_REQUEST['reqnumb']."' AND REQUSRTYP='S'";
            //print_r($g_fld4);
            $insert_appplan1 = update_test_dbquery($g_fld4, $g_table, $where_appplan);
  }
}
else
{
  $REQSRNO = select_query_json("Select nvl(Max(REQSRNO),0)+1 REQSRNO From SERVICE_REGISTER_ENTRY where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
  $REQ = select_query_json("Select * From service_register_entry where REQNUMB = '".$_REQUEST['reqnumb']."'","Centra","TEST");
  $g_table = "SERVICE_REGISTER_ENTRY";
  $g_fld['REQNUMB'] = $_REQUEST['reqnumb'];
  $g_fld['REQSRNO'] = $REQSRNO[0]['REQSRNO'];
  $g_fld['REQUSRTYP'] = $REQ['REQUSRTYP'];
  $g_fld['REQUSER'] = $REQ['REQUSER'];// max+1
  $g_fld['REQCONT'] = $REQ[0]['REQCONT'];
  $g_fld['REQALTCONT'] = $REQ[0]['REQALTCONT'];
  $g_fld['REQMAIL'] =$REQ[0]['REQMAIL'] ;
  $g_fld['REQDESKNO'] = $REQ[0]['REQDESKNO'];
  $g_fld['REQDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
  $g_fld['REQMODE'] = $REQ[0]['REQMODE'];
  $g_fld['REQMSG'] =$_REQUEST['message'].'ASSIGNED MEMBER'.$_REQUEST['txt_assign'];
  $g_fld['REQPATH'] = 'ftp://'.$ftp_server_159.'/CALL_CENTRE/REQUESTS/'.$_REQUEST['reqnumb'].'_'.$REQSRNO[0]['REQSSRNO'].'/';
  $g_fld['REQSTAT'] = 'N';
  $g_fld['REQDEVID'] =$_SERVER['REMOTE_ADDR'];
  $g_fld['RESLVUSER'] ='';
  $g_fld['RESLVDATE'] ='';
  // $g_fld['RESLVDATE'] ='dd-Mon-yyyy HH:MI:SS AM~~'.$REQ[0]['RESLVDATE'];
  $g_fld['APPCODE'] =$REQ[0]['APPCODE'];
   //print_r($g_fld);
  $g_insert_subject = insert_test_dbquery($g_fld, $g_table);
}



?>
