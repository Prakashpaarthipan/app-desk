<?php 
session_start();
include('lib/config.php');
include('../db_connect/public_functions.php');
ini_set('max_execution_time', 6000);
set_time_limit(0);
exit;

// connect and login to FTP server
$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass);
$listdir = 'fieldimpl';
$dir = 'approval_desk/request_entry/'.$listdir.'/';

$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass);
$files = ftp_nlist($ftp_conn, $dir);
// print_r($files);
$attch = 0; $exist = ''; 

for($ij = 0; $ij < count($files); $ij++) {
	$remove_dir = explode("/", $files[$ij]);
	$exp = explode("_", $remove_dir[3]);
	$expdot = explode(".", $files[$ij]);
	$filename = $exp[5].'_'.$exp[6];
	if($exp[3] == '2017-18' and $exp[2] == 4) {
	// if($exp[3] == '2016-17') {
		$sql_reqexist = select_query("select APRNUMB from APPROVAL_REQUEST where deleted = 'N' and ARQSRNO = 1 and ARQCODE = '".$exp[0]."' and ATYCODE = '".$exp[1]."' and ATCCODE = '".$exp[2]."' and ARQYEAR = '".$exp[3]."' ");
		$sql_failexist = select_query("select * from APPROVAL_REQUEST_DOCS where APRNUMB = '".$sql_reqexist[0]['APRNUMB']."' and aprdocs = '".$remove_dir[3]."' order by apdcsrn");
		$sql_docexist = select_query("select max(TO_NUMBER(APDCSRN)) MXAPDCSRN from APPROVAL_REQUEST_DOCS where APRNUMB = '".$sql_reqexist[0]['APRNUMB']."' order by apdcsrn");
		echo "<br>***".$remove_dir[3]."***"; // $filename.print_r($exp);

		if($sql_reqexist[0]['APRNUMB'] != $exist) {
			$attch = 0;
		}
		$exist = $sql_reqexist[0]['APRNUMB'];
		if($sql_docexist[0]['MXAPDCSRN'] != '') {
			if(count($sql_failexist) > 0) {
				$attch = 0;
			} else {
				$attch = $sql_docexist[0]['MXAPDCSRN'];
			}
		}
		if($sql_reqexist[0]['APRNUMB'] != '') {
			// Approval Documents
			$attch++;
			$tbl_docs = "APPROVAL_REQUEST_DOCS";
			$field_docs['APRNUMB'] = $sql_reqexist[0]['APRNUMB'];
			$field_docs['APDCSRN'] = $attch;
			$field_docs['APRDOCS'] = $remove_dir[3];
			$field_docs['APRHEAD'] = $listdir;
			$insert_docs = insert_query($field_docs, $tbl_docs);
			print_r($field_docs);
			// Approval Documents
		}
	}
}
?>