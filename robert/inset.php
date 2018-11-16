
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
include_once('lib/config.php');
include_once('general_functions.php');
extract($_REQUEST);

print_r($_REQUEST);

$noa = sizeof($_FILES['attachments']['name']);
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$current_year = $current_yr[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//echo "current year".$current_year;
$data=$_REQUEST['FCKeditor2'];
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
				$a1local_file = "../uploads/request_entry/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

				/// updating the index to attachment to server
				$f=$_FILES['attachments']['name'][$k-1];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				//echo $path_parts['extension'];
				$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$k.'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$alocal_file = "../uploads/request_entry/attachments/".$name;
				$server_file = 'approval_desk/request_entry/'.$current_year.'/attachments/'.$name;     
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
				$g_table1 = "APPROVAL_ATTACHMENT";
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
		$txt_srcfilename=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$srno.'.txt';
		// echo $txt_srcfilename;
		// echo "tcs user id ".$_SESSION['tcs_usrcode'];
		$local_file = "../uploads/request_entry/".$txt_srcfilename;
		$myfile = fopen($local_file, "w");
		fwrite($myfile, $data);
		fclose($myfile);
		// echo "file created";
		$server_file = 'approval_desk/request_entry/'.$current_year.'/'.$txt_srcfilename;
		if ((!$conn_id) || (!$login_result)) {
			 $upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
			 // echo "file uploaded";
			 //unlink($local_file);
		}
		//entering the database for tag table
/*
		$g_table2 = "APPROVAL_TAG";
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
		$expl = explode(" - ", $txtassign);
		$sql_empsrno = select_query_json("select empsrno from employee_office where EMPCODE = '".$expl[0]."' order by EMPCODE", 'Centra', 'TEST'); 
		//entering the database for tag table*/
		$slt_topcore_name=select_query_json("select ATCCODE,ATCNAME from approval_topcore","centra","TEST");
		$topcore = $slt_topcore_name;
        		$currentdate1 = strtoupper(date('d-m-Y'));
				$currenttime1 = strtoupper(date('h:i A'));
				
				$srno = $startwith.str_pad($maxarqcode[0]['MAXARQCODE'], 6, '0', STR_PAD_LEFT);
		$sql_emp = select_query_json("select e.*, (select ATCNAME from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore, (select CORNAME from empcore_section where esecode = e.esecode) subcore,
												(select ATCCODE from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore_code, ESECODE subcore_code
											from employee_office e where EMPSRNO = ".$resvalue, "Centra", 'TEST');
  $subcore_name = select_query_json("select CORNAME from empcore_section where DELETED = 'N' and ESECODE = ".$slt_subcore, "Centra", 'TEST'); // Sub Core Name
		 $srno = '4001000';
$apprno = strtoupper($topcore.' / '.$subcore_name[0]['CORNAME'].' '.$srno.' / '.$currentdate1.' / '.substr($srno, -4).' / '.$currenttime1);
		//entring the master table 
		//entering the database main for editor text
		
		$g_table = "APPROVAL_SALARY";
		$g_fld = array();
                $g_fld['APRYEAR'] = $current_year ;
				$g_fld['APRNUMB'] = $apprno;
				$g_fld['POLSUBJ'] = $txtdynamic_subject;				
				$g_fld['EFFDATE'] = $tar_date;
				$g_fld['POLTYPE'] = $txtdynamic_policy_type;
				$g_fld['VALUPTO'] = $due_date;
				$g_fld['CRENAME'] = $txtdynamic_creator;
                $g_fld['APPDATE'] = $app_date;
				$g_fld['COONAME'] = $txtdynamic_coordinator;
				$g_fld['USELIST'] = $txtdynamic_userlist;
				$g_fld['ASISTBY'] = $txtdynamic_asistby;
				$g_fld['DESKPRO'] = $name;
				$g_fld['POLIDOC'] = $txtdynamic_policy_docs;
				$g_fld['FILEPOL'] = $txt_srcfilename;
				//print_r($g_fld);
		$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
		print_r($g_fld);
		die();
		


			
		// echo "done";
		
?>
