
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Location: ../request_entry1_test.php');
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
//print_r($_REQUEST);
//print_r($_FILES);

$noa = sizeof($_FILES['attachments']['name']);
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$current_year = $current_yr[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//echo "current year".$current_year;
$data=$_REQUEST['FCKeditor1'];
// echo "------------1".$data."1----------------";
$ENTRYNO = select_query_json("Select nvl(Max(ENTRYNO),0)+1 MAXENTRY From PROCESS_REQUIREMENT_ENTRY where ENTRYYR = '".$current_year."'","Centra","TEST");
$ENTRYSRNO = 1;
		
		//$extension = pathinfo($_FILES['attachments']['name'][0]->getFilename(), PATHINFO_EXTENSION);

		//uploading the attachments 
		for($k=1;$k<=$noa;$k++)
		{	
			if($_FILES['attachments']['name'][$k-1] != null)
			{	/// updating the index to attachment to local
				$f=$_FILES['attachments']['name'][$k-1];
				$path_parts = pathinfo($f);
				//echo "\n".$f."\n";
				//echo $path_parts['extension'];
				$tmp_name = $_FILES["attachments"]["tmp_name"][$k-1];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$a1local_file = "../uploads/requirement_entry/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

				/// updating the index to attachment to server
				$f=$_FILES['attachments']['name'][$k-1];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				//echo $path_parts['extension'];
				$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$alocal_file = "../uploads/requirement_entry/attachments/".$name;
				$server_file = 'approval_desk/requirement_entry/'.$current_year.'/attachments/'.$name;
				if ((!$conn_id) || (!$login_result)) {
					 $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
					 // echo "file uploaded";
					 //unlink($alocal_file);
				}
				/// updating the index to attachment database
				$f=$_FILES['attachments']['name'][$k-1];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				$name =  $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.strtolower($path_parts['extension']);
				$g_table1 = "PROCESS_REQUIREMENT_ATTACHMENT";
				$g_fld1 = array();
				$g_fld1['ENTRYYR'] = $current_year;
				$g_fld1['ENTRYNO'] = $ENTRYNO[0]['MAXENTRY'];
				$g_fld1['ENTSRNO'] = $k;
				$g_fld1['ATCNAME'] = $name;
				
				$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
				//print_r($g_fld1);
				// echo "done";
			}
		}

		//uploading the file text
		$txt_srcfilename=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$ENTRYSRNO.'.txt';
		// echo $txt_srcfilename;
		// echo "tcs user id ".$_SESSION['tcs_usrcode'];
		$local_file = "../uploads/requirement_entry/".$txt_srcfilename;
		$myfile = fopen($local_file, "w");
		fwrite($myfile, $data);
		fclose($myfile);
		// echo "file created";
		$server_file = 'approval_desk/requirement_entry/'.$current_year.'/'.$txt_srcfilename;
		if ((!$conn_id) || (!$login_result)) {
			 $upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
			 // echo "file uploaded";
			 //unlink($local_file);
		}
		//entering the database for tag table

		$g_table2 = "PROCESS_REQUIREMENT_TAG";
		$g_fld2 = array();
		$tsz=sizeof($txttag_process);
		for($k=1;$k<=$tsz;$k++)
		{
			$g_fld2['ENTRYYR'] = $current_year;
			$g_fld2['ENTRYNO'] = $ENTRYNO[0]['MAXENTRY'];
			$g_fld2['ENTSRNO'] = $ENTRYSRNO;
			$g_fld2['TAGSRNO'] = $k;//mode of eg B - branch and P - project
			$g_fld2['TAGCOLR'] =  'RED';// max+1
			
			$g_fld2['TAGSDET'] = $txttag_process[$k-1];
			$g_fld2['TAGDATA'] = $txttag_data[$k-1];
			$g_fld2['TAGTERM'] = $txttag_term[$k-1];
			$g_fld2['TAGSTAT'] = 'N';
			
			$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
			//print_r($g_fld2);
			// echo "done";
		}
		
		$expl = explode(" - ", $txtdynamic_userlist);
		$sql_empsrno = select_query_json("select empsrno from employee_office where EMPCODE = '".$expl[0]."' order by EMPCODE", 'Centra', 'TCS'); 
		 
		//entring the master table 
		//entering the database main for editor text
		$g_table = "APPROVAL_SALARY_POLICY";
		$g_fld = array();
                //$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
				//$g_fld['APRNUMB'] = $apprno;
				$g_fld['POLSUBJ'] = $txtdynamic_subject;				
				$g_fld['EFFDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$REQUEST['datepicker_example11'];
				$g_fld['POLTYPE'] = $txtdynamic_policy_type;
				$g_fld['VALUPTO'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$REQUEST['datepicker_example12'];
				$g_fld['CRENAME'] = $txtdynamic_creator;
                $g_fld['APPDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$REQUEST['datepicker_example13'];
				$g_fld['COODATE'] = $txtdynamic_coordinator;
				$g_fld['USELIST'] = $txt_dynamic_uselist;
				$g_fld['ASISTBY'] = $txtdynamic_assistby;
				$g_fld['DESKPRO'] = $txtdynamic_deskpro;
				$g_fld['POLIDOC'] = $txtdynamic_policy_docs;
				$g_fld['FILEPOL'] = $FCKeditor1;
				//print_r($g_fld);
		$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
		print_r($g_fld);
		// echo "done";
		die();
?>
