<?php 
session_start();
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

$sql_search = select_query_json("select * from approval_request where aprnumb like '".$aprnumb."' and arqsrno = 1", "Centra", "TEST"); ?>
<div>
<?  if($sql_search[0]['APPRFOR'] == '1') {
        $filepathname = $sql_search[0]['APPRSUB'];
        $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$filepathname;
        $handle = fopen($filename, "r"); 
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        echo $contents;
    } else {
        echo $sql_search[0]['APPRDET'];
    } ?>
</div>
<div style='clear:both'></div>