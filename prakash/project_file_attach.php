<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once('../lib/function_connect.php');
include_once('../lib/config.php');
extract($_REQUEST);

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$HISSRNO = select_query_json("Select nvl(Max(HISSRNO),0)+1 maxarqcode From approval_project_history where PRMSYER = '".$current_year[0]['PORYEAR']."'","Centra","TEST");


// new file attachment start
		//uploading the attachments 
		$noa = sizeof($_FILES['attachments']['name']);	
		//echo ($noa);
		for($file=1;$file<=$noa;$file++)
		{
           $filesno = select_query_json("Select nvl(Max(FILESNO),0)+1 FILESNO From approval_project_attachments where PRMSYER='".$current_year[0]['PORYEAR']."' And PRMSCOD= '".$pjid."'","Centra","TEST");	
			$filecount = select_query_json("SELECT COUNT(FILESNO)+1 fileno FROM approval_project_attachment where PRMSYER = '".$current_year[0]['PORYEAR']."' and PRMSCOD = '".$pjid."'","Centra","TEST");
			//echo($filecount);
			
			$filenos = ($filecount[0]['FILENO']);
			//echo($filenos);
			if($_FILES['attachments']['name'][$file-1] != null)
			{	
				
				///----------updating the index to attachment to local
				$q=$_FILES['attachments']['name'][$file-1];
				$path_parts = pathinfo($q);
				$tmp_name = $_FILES["attachments"]["tmp_name"][$file-1];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year[0]['PORYEAR'].'_'.$pjid.'_'.$filenos.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$a1local_file = "../uploads/admin_projects_local/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

						///---------updating the index to attachment to server
				
				$nameforserver = $current_year[0]['PORYEAR'].'_'.$pjid.'_'.$filenos.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				//$alocal_file = "../uploads/admin_projects_local/attachments/".$name;
				$a1local_file = "../uploads/admin_projects_local/attachments/".$name;
				//echo($a1local_file);
				//echo ($nameforserver);
				$server_file = 'approval_desk/approval_project_mgt/2018-19/'.$nameforserver;
				//echo ($server_file);
				if ((!$conn_id) || (!$login_result)) {
					 $upload = ftp_put($ftp_conn, $server_file, $a1local_file, FTP_BINARY);
					// echo($upload);
					  echo "file uploaded";
					 //unlink($alocal_file);
				}
				else{
					echo ("error");
				}
			
				$g_table_att = "approval_project_attachment";
				$g_fld_att = array();
				$g_fld_att['PRMSYER'] = $current_year[0]['PORYEAR'];
				$g_fld_att['PRMSCOD'] = $pjid;
				$g_fld_att['FILESNO'] = $filenos;
				$g_fld_att['FILENAM'] = $nameforserver;
				$g_fld_att['DELETED'] = 'N';
				$g_fld_att['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld_att['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_insert_subject = insert_test_dbquery($g_fld_att,$g_table_att);
				var_dump($g_insert_subject);
				
				print_r($g_fld_att);
				
			}
			
		}
		
		//file attachment end
		
					$g_att_history_table = "approval_project_history";
					$g_att_history = array();
					$g_att_history['PRMSYER'] = $current_year[0]['PORYEAR'];
					$g_att_history['PRMSCOD'] = $pjid;
					$g_att_history['HISSRNO'] = $HISSRNO[0]['MAXARQCODE'];
					//$g_att_history['PRJSRNO'] = $ownerDetails[0] ['PRJSRNO'];
					//$g_att_history['PRJTITL'] = $ownerDetails[0] ['PRJTITL'];
					//$g_att_history['EMPCODE'] = $ownerDetails[0] ['EMPCODE'];
					//$g_att_history['EMPNAME'] = $ownerDetails[0] ['EMPNAME'];
					$g_att_history['EDTUSER'] = $_SESSION['tcs_usrcode'];
					$g_att_history['EDTDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$g_att_history['HSTATUS'] = 'AT';
					$g_att_history['PSTATUS'] = 'N';
					$g_att_history['REMARKS'] = 'NEW ('.$noa.')- ATTACHMENT(S) HAS ADDED';
					$update_read = insert_test_dbquery($g_att_history,$g_att_history_table);
					print_r($g_att_history);
		
		
		
		?>