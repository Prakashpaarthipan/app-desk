<?

include_once('lib/config.php');
include_once('general_functions_ftp.php');
extract($_REQUEST);

if($get_ftp_file != '') {
	  $limit = $file_limit;
    $filename = "ftp://".$ftp_user_name_apdsk.":".$ftp_user_pass_apdsk."@".$ftp_server_apdsk.$ftp_srvport_apdsk."/approval_desk/text_approval_source/".$get_ftp_file;
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    // echo strip_tags(str_replace("&nbsp;", " ", $contents));
    echo list_summary(strip_tags(str_replace("&nbsp;", " ", $contents)), $limit, $strip = false);
}
?>
