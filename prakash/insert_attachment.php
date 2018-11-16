
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Location: ../process_requirement_entry.php');
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
print_r($_REQUEST);
print_r($_FILES);






		print_r($_FILES);
		print_r($attachments);
		$noa = sizeof($_FILES['attachments']['name']);	
			print_r($noa);
		//-------------- Upload Attachments ---------------//
		//uploading the attachments 
		for($file=1;$file<=$noa;$file++)
		{	
			if($_FILES['attachments']['name'][$file-1] != null)
			{	
						///----------updating the index to attachment to local
				$f=$_FILES['attachments']['name'][$file-1];
				$path_parts = pathinfo($f);
				//echo "\n".$f."\n";
				//echo $path_parts['extension'];
				$tmp_name = $_FILES["attachments"]["tmp_name"][$file-1];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$file.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$a1local_file = "../uploads/admin_projects/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

				/*		///---------updating the index to attachment to server
				$f=$_FILES['attachments']['name'][$file-1];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				//echo $path_parts['extension'];
				$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$file.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$alocal_file = "../uploads/admin_projects/attachments/".$name;
				$server_file = 'approval_desk/admin_projects/'.$current_year.'/attachments/'.$name;
				if ((!$conn_id) || (!$login_result)) {
					 $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
					 // echo "file uploaded";
					 //unlink($alocal_file);
				}
						///----------updating the index to attachment database
				$f=$_FILES['attachments']['name'][$file-1];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				$name =  $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$file.'.'.strtolower($path_parts['extension']);
				$g_table1 = "PROCESS_REQUIREMENT_ATTACHMENT";
				$g_fld1 = array();
				$g_fld1['ENTRYYR'] = $current_year;
				$g_fld1['ENTRYNO'] = $ENTRYNO[0]['MAXENTRY'];
				$g_fld1['ENTSRNO'] = $file;
				$g_fld1['ATCNAME'] = $name;
				
				//$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
				print_r($g_fld1);
				// echo "done";*/
			}
		}
	//------------------- Upload Finish -----------------/
?>

	/
		