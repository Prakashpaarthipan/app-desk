
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//header('Location: ../process_requirement_entry.php');
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
print_r($_REQUEST);
print_r($_FILES);
if($_REQUEST['action']=="process")
{
$noa = sizeof($_REQUEST['txt_language']);
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$current_year = $current_yr[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//echo "current year".$current_year;
$data=$_REQUEST['FCKeditor1'];
// echo "------------1".$data."1----------------";
$ENTRYNO = select_query_json("Select nvl(Max(PRCSNO),0)+1 MAXENTRY From SUPMAIL_PROCESS where PRCSYR = '".$current_year."'","Centra","TEST");
//echo("Select nvl(Max(TEMPNO),0)+1 MAXENTRY From SUPMAIL_PROCESS where TEMPYR = '".$current_year."'");
//print_r($ENTRYNO);
$ENTRYSRNO = 1;		
		//$extension = pathinfo($_FILES['attachments']['name'][0]->getFilename(), PATHINFO_EXTENSION);

		//uploading the attachments 
echo($noa);
		for($k=0;$k<=$noa;$k++)
		{	

			if($_FILES['file_upload']['name'][$k] != null)
			{	/// updating the index to attachment to local
				echo('1');
				$f=$_FILES['file_upload']['name'][$k];
				$path_parts = pathinfo($f);
				echo "\n".$f."\n";
				//echo $path_parts['extension'];
				$tmp_name = $_FILES["file_upload"]["tmp_name"][$k];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$_REQUEST['txt_language'][$k].'.'.strtolower($path_parts['extension']);
				 //echo "\n".$name."\n";
				$a1local_file = "../uploads/requirement_entry/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

				/// updating the index to attachment to server
				$f=$_FILES['file_upload']['name'][$k];
				echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				//echo $path_parts['extension'];
				$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$_REQUEST['txt_language'][$k].'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$alocal_file = "../uploads/requirement_entry/attachments/".$name;
				$server_file = 'approval_desk/requirement_entry/'.$current_year.'/attachments/'.$name;
				if ((!$conn_id) || (!$login_result)) {
					 $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
					  echo "file uploaded";
					 //unlink($alocal_file);
				}
				/// updating the index to attachment database
				$f=$_FILES['file_upload']['name'][$k];
				echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				$name =  $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$_REQUEST['txt_language'][$k].'.'.strtolower($path_parts['extension']);
				$g_table1 = "SUPMAIL_PROCESS_LANGUAGE";
				$g_fld1 = array();
				$g_fld1['PRCSYR'] = $current_year;
				$g_fld1['PRCSNO']=$ENTRYNO[0]['MAXENTRY'];
				$g_fld1['LANGCOD']=$k+1;
				$g_fld1['LANGNAM']=strtoupper($_REQUEST['txt_language'][$k]);
				$g_fld1['LANGDSC'] = $name;
				$g_fld1['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld1['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld1['DELETED'] = 'N';
				//print_r($g_fld1);
				//print_r($g_table1);
				$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
			}
		}
		$g_table2 = "SUPMAIL_PROCESS_FIELD";
		$g_fld2 = array();
		//echo(count($_REQUEST['txt_value']));
		$tsz=count($_REQUEST['txt_value']);
		for($k=0;$k<$tsz;$k++)
		{
			$g_fld2['PRCSYR'] = $current_year;
			$g_fld2['FIELDNO'] = $k+1;
			$g_fld2['PRCSNO']=$ENTRYNO[0]['MAXENTRY'];
			$g_fld2['FIELDNM'] = strtoupper($_REQUEST['txt_value'][$k]);
			$g_fld2['FIELDTY'] = $_REQUEST['txt_mode_type'][$k];//mode of eg B - branch and P - project
			$g_fld2['DELETED'] =  'N';// max+1
			$g_fld2['ADDUSER'] = $_SESSION['tcs_usrcode'];
			$g_fld2['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			//print_r($g_fld2);
			//print_r($g_table2);
			
			$g_insert_subject = insert_test_dbquery($g_fld2,$g_table2);
			//print_r($g_fld2);
			// echo "done";
		}
		//entring the master table 
		//entering the database main for editor text
		$g_table = "SUPMAIL_PROCESS";
		$g_fld = array();
		$g_fld['PRCSYR'] = $current_year;
		$g_fld['PRCDSC'] = strtoupper($_REQUEST['process_name']);
		$g_fld['PRCSNO'] = $ENTRYNO[0]['MAXENTRY'];//mode of eg B - branch and P - project
		$g_fld['DELETED'] = 'N';// max+1
		$g_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		//print_r($g_fld);
		//print_r($g_table);
		$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
		//print_r($g_fld);
		// echo "done";
		die();
}
if($_REQUEST['action']=="language")
{
$noa = sizeof($_REQUEST['txt_lang']);
$current_year = $current_yr[0]['PORYEAR'];
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$PRCS=explode('_',$_REQUEST['txtprocess']);
$ENTRYNO = select_query_json("Select nvl(Max(LANGCOD),0)+1 MAXENTRY From SUPMAIL_PROCESS_LANGUAGE where PRCSYR = '".$PRCS[0]."' AND PRCSNO='".$PRCS[1]."'","Centra","TEST");
	for($k=0;$k<=$noa;$k++)
		{	

			if($_FILES['file_uploadl']['name'][$k] != null)
			{	/// updating the index to attachment to local
				//echo('1');
				$f=$_FILES['file_uploadl']['name'][$k];
				$path_parts = pathinfo($f);
				//echo "\n".$f."\n";
				//echo $path_parts['extension'];
				$tmp_name = $_FILES["file_uploadl"]["tmp_name"][$k];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				
				$name=$current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$_REQUEST['txt_lang'][$k].'.'.strtolower($path_parts['extension']);
				 //echo "\n".$name."\n";
				$a1local_file = "../uploads/requirement_entry/attachments/".$name;
				move_uploaded_file($tmp_name, $a1local_file);

				/// updating the index to attachment to server
				$f=$_FILES['file_upload']['name'][$k];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				//echo $path_parts['extension'];
				$name = $current_year.'_'.$ENTRYNO[0]['MAXENTRY'].'_'.$_REQUEST['txt_lang'][$k].'.'.strtolower($path_parts['extension']);
				// echo "\n".$name."\n";
				$alocal_file = "../uploads/requirement_entry/attachments/".$name;
				$server_file = 'approval_desk/requirement_entry/'.$current_year.'/attachments/'.$name;
				if ((!$conn_id) || (!$login_result)) {
					 $upload = ftp_put($ftp_conn, $server_file, $alocal_file, FTP_BINARY);
					 /// echo "file uploaded";
					 //unlink($alocal_file);
				}
				/// updating the index to attachment database
				$f=$_FILES['file_uploadl']['name'][$k];
				//echo "\n".$f."\n";
				$path_parts = pathinfo($f);
				$name =  $PRCS[0].'_'.$PRCS[1].'_'.$_REQUEST['txt_lang'][$k].'.'.strtolower($path_parts['extension']);
				$g_table1 = "SUPMAIL_PROCESS_LANGUAGE";
				$g_fld1 = array();
				$g_fld1['PRCSYR'] = $PRCS[0];
				$g_fld1['PRCSNO']=$PRCS[1];
				$g_fld1['LANGCOD']=$ENTRYNO[0]['MAXENTRY']+$k;
				$g_fld1['LANGNAM']=strtoupper($_REQUEST['txt_lang'][$k]);
				$g_fld1['LANGDSC'] = $name;
				$g_fld1['ADDUSER'] = $_SESSION['tcs_usrcode'];
				$g_fld1['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld1['DELETED'] = 'N';
				//print_r($g_fld1);
				//print_r($g_table1);
				$g_insert_subject = insert_test_dbquery($g_fld1,$g_table1);
			}
		}
}
?>
