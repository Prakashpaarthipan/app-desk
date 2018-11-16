<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
print_r($_REQUEST);
print_r($_REQUEST);
$noa = sizeof($_FILES['attachments']['name']);
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$current_year = $current_yr[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//echo "current year".$current_year;
$ENTRYNO = select_query_json("Select nvl(Max(ENTRYNO),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$current_year."'","Centra","TEST");
$ENTRYSRNO = 1;
		
		//$extension = pathinfo($_FILES['attachments']['name'][0]->getFilename(), PATHINFO_EXTENSION);
		
		
		
		
		
		
		
		
		
		//uploading the attachments 
		for($k=1;$k<=$noa;$k++)
		{	$f=$_FILES['attachments']['name'][$k-1];
			$path_parts = pathinfo($f);
			//echo "\n".$f."\n";
			//echo $path_parts['extension'];
			$tmp_name = $_FILES["attachments"]["tmp_name"][$k];
			// basename() may prevent filesystem traversal attacks;
			// further validation/sanitation of the filename may be appropriate
			//$name = basename($_FILES["attachments"]["name"][$k]);
			$name=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.$path_parts['extension'];
			echo "\n".$name."\n";
			$a1local_file = "../uploads/requirement_entry/attachments/".$name;
			move_uploaded_file($tmp_name, $a1local_file);
		}
		for($k=1;$k<=$noa;$k++)
		{	$f=$_FILES['attachments']['name'][$k-1];
			//echo "\n".$f."\n";
			$path_parts = pathinfo($f);
			//echo $path_parts['extension'];
			$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.$path_parts['extension'];
			echo "\n".$name."\n";
			$alocal_file = "../uploads/requirement_entry/attachments/".$name;
			$server_file = 'approval_desk/requirement_entry/'.$current_year.'/attachments/'.$name;
			if ((!$conn_id) || (!$login_result)) {
				 $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
				 echo "file uploaded";
				 unlink($alocal_file);
			}
		}
		//uploading the file text
		$txt_srcfilename=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$ENTRYSRNO.'.txt';
		
		echo $txt_srcfilename;
		echo "tcs user id ".$_SESSION['tcs_usrcode'];
		$local_file = "../uploads/requirement_entry/".$txt_srcfilename;
		$myfile = fopen($local_file, "w");
		fwrite($myfile, $data);
		fclose($myfile);
		echo "file created";
		$server_file = 'approval_desk/requirement_entry/'.$current_year.'/'.$txt_srcfilename;
		if ((!$conn_id) || (!$login_result)) {
			 $upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
			 echo "file uploaded";
			 //unlink($local_file);
		}
		
		//enrting the database for attachments
		for($k=1;$k<=$noa;$k++)
		{	$f=$_FILES['attachments']['name'][$k-1];
			//echo "\n".$f."\n";
			$path_parts = pathinfo($f);
			$name =  $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.$path_parts['extension'];
			$g_table1 = "PROCESS_ATTACHMENT_TAG";
			$g_fld1 = array();
			$g_fld1['ENTRYYR'] = $current_year;
			$g_fld1['ENTRYNO'] = $ENTRYNO[0]['MAXENTRY'];
			$g_fld1['ENTSRNO'] = $k;
			$g_fld1['ATCNAME'] = $name;
			
			$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
			//print_r($g_fld1);
			echo "done";
		}
		
		//entring the master table 
		//entering the database main for editor text
		$g_table = "PROCESS_REQUIREMENT_ENTRY";
		$g_fld = array();
		$g_fld['ENTRYYR'] = $current_year;
		$g_fld['ENTRYNO'] = $ENTRYNO[0]['MAXENTRY'];
		$g_fld['ATCCODE'] = $Top_Core;//mode of eg B - branch and P - project
		$g_fld['PRICODE'] = $Priority;// max+1
		$g_fld['ENTSRNO'] = $ENTRYSRNO;
		$g_fld['DSPFILE'] = $txt_srcfilename;
		$g_fld['DELETED'] = 'N';
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$g_fld['EDTUSER'] = null;
		$g_fld['EDTDATE'] = null;
		$g_fld['DELUSER'] = null; 
		$g_fld['DELDATE'] = null; 
		$g_fld['REQLIKE'] = 0; 
		$g_fld['REQFAVI'] = 0; 
		$g_fld['REQVIEW'] = 0; 
		$g_fld['REQDSLK'] = 0; 
		$g_insert_subject = insert_test_dbquery($g_fld,$g_table);
		//print_r($g_fld);
		echo "done";
?>
