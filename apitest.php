<?php
  session_start();

define("ftpvri_server_apdsk", "ftp1.thechennaisilks.com");
define("ftpvri_user_name_apdsk", "ituser");
define("ftpvri_user_pass_apdsk", "S0ft@369");
define("ftpvri_server_port", ":5022");
$image_folder = 'ftp://'.$ftpvri_user_name_apdsk.':'.$ftpvri_user_pass_apdsk.'@'.$ftpvri_server_apdsk.$ftpvri_server_port.'/approval-desk-test/ramesh/Capture001.png';
$ftp_conn = ftp_connect(ftpvri_server_apdsk, 5022) or die("Could not connect to ftpvri_server_apdsk");
$login = ftp_login($ftp_conn, ftpvri_user_name_apdsk, ftpvri_user_pass_apdsk);
    if($login){
           echo "pass";
           $fp=fopen($image_folder,'rb');
           fread($fp);
    }
    else
          echo "fail";




  $curl_handle=curl_init();
  curl_setopt($curl_handle,CURLOPT_URL,'http://www.google.com'); 
  curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER,FALSE);
  curl_setopt($curl_handle,CURLOPT_SSL_VERIFYHOST,2);   
  curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
  $buffer = curl_exec($curl_handle);
  $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
  $err=curl_error($curl_handle);
  curl_close($curl_handle);
  //print_r($buffer);
?>