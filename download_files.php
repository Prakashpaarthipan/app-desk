<?php
session_start();
error_reporting(0);
set_time_limit(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

$values1=$_REQUEST['f'];
$values2=explode(".",$values1);
$values=$values2[0];

if($values=='service_request_report') {
	$and = "";
	if($search_value != '') {
		$and .= " And TO_CHAR(SRE.REQNUMB) like '%".strtoupper($search_value)."%' ";
	}
	if($status_type != '') {
		$and .= " and SRE.REQSTAT = '".strtoupper($status_type)."' ";
	}
  if($request_type != '') {
      $and .= " and sre.REQMODE = '".strtoupper($request_type)."' ";
  }
	if($search_sprno != '') {
		$exp1 = explode(" - ", $search_sprno);
		$and .= " and (SUP.SUPCODE like '%".$exp1[0]."%' or SUP.SUPNAME like '%".$exp1[0]."%' or SUP.SUPMOBI like '%".$exp1[0]."%' or SRE.REQMAIL like '%".$exp1[0]."%' or SRE.REQCONT like '%".$exp1[0]."%')";
	}
	if($search_fromdate != '' or $search_todate != '') {
		if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
		$exp1 = explode("-", $search_fromdate);
		$frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

		if($search_todate == '') { $search_todate = date("d-M-Y"); }
		$exp2 = explode("-", $search_todate);
		$to_date = strtoupper($exp2[0]."-".$exp2[1]."-".substr($exp2[2], 2));

		$and .= " And trunc(SRE.REQDATE) BETWEEN TO_DATE('".$frm_date."', 'DD-MON-YY') AND TO_DATE('".$to_date."', 'DD-MON-YY') ";
	}

	$open_search = select_query_json("SELECT SRR.RESTUSR, to_char(SRE.REQDATE,'dd/MM/yyyy HH:mi:ss AM') REQDATE, SRE.REQCONT, SRE.REQMAIL, SRE.REQDESKNO, SRE.REQALTCONT, 
	                                            TO_CHAR(SRE.REQNUMB) REQNUMB, SUP.SUPCODE, SUP.SUPNAME, SUP.SUPMOBI, SRE.REQSTAT, SRE.REQDESKNO, SRE.REQMODE, (select app.comname 
	                                            from app_complaint_master app where app.comcode= sre.REQMODE) comname, (select eof.empcode||' - '||eof.empname 
	                                            from employee_office eof, service_response srr where srr.resfusr=eof.empsrno and rownum <= 1 and srr.reqnumb=SRE.REQNUMB and 
	                                            srr.reqsrno=(SELECT nvl(MAX(REQSRNO), 0) FROM SERVICE_REQUEST WHERE REQNUMB=SRE.REQNUMB) and srr.ressrno=(SELECT nvl(MAX(RESSRNO), 0) 
	                                            FROM SERVICE_REQUEST WHERE REQNUMB=SRE.REQNUMB AND REQSRNO=(SELECT nvl(MAX(REQSRNO), 0) FROM SERVICE_REQUEST WHERE REQNUMB=SRE.REQNUMB))) EMPCODENAME
	                                        FROM service_request SRE, SUPPLIER SUP, service_response SRR 
	                                        WHERE SRR.REQNUMB(+)=SRE.REQNUMB AND SRR.REQSRNO(+)=SRE.REQSRNO AND SRR.RESSRNO(+)='1' AND SUP.SUPCODE=SRE.REQUSER AND 
	                                            SRE.REQSTAT IN ('A','N','C') and sre.REQMODE > 0 ".$and." 
	                                        Order by REQMODE, REQNUMB desc, REQDATE desc", "Centra", 'TCS');
	$complete_csv_string_ex_ro = "#,REQUEST ID,DATE,SUPPLIER,SUPPLIER EMAIL,SUPPLIER CONTACT NO,REQUEST TYPE,RESPONSE MEMBER,DESK NO.,STATUS\n";
	$i = 0;
	// echo "**"; print_r($open_search); echo "**"; exit;
	foreach ($open_search as $product_fixing) { $i++;
		if($product_fixing['REQSTAT']=='A'){ $supstatus = "ASSIGNED"; }
		if($product_fixing['REQSTAT']=='N'){ $supstatus = "NOT ASSIGNED"; }
        if($product_fixing['REQSTAT']=='C'){ $supstatus = "CLOSED"; }
		$complete_csv_string_ex_ro .=  $i.",".$product_fixing['REQNUMB'].",".$product_fixing['REQDATE'].",".$product_fixing['SUPCODE']." - ".$product_fixing['SUPNAME'].",".$product_fixing['REQMAIL'].",".$product_fixing['REQCONT']." [ ".$product_fixing['REQALTCONT']." ], ".$product_fixing['COMNAME'].",".$product_fixing['EMPCODENAME'].",".$product_fixing['REQDESKNO'].",".$supstatus."\n";
	}
}
if($values=='service_request_summary') {
  $search_fromdate=$_REQUEST['search_fromdate'];
  if($search_fromdate == '') { $search_fromdate = date("d-M-Y"); }
  $exp1 = explode("-", $search_fromdate);
  $frm_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));

  $search_rodate=$_REQUEST['search_todate'];
  if($search_todate == '') { $search_todate = date("d-M-Y"); }
  $exp1 = explode("-", $search_todate);
  $to_date = strtoupper($exp1[0]."-".$exp1[1]."-".substr($exp1[2], 2));
  $and = " And trunc(resdate) between TO_DATE('".$frm_date."', 'DD-MON-YY') and  TO_DATE('".$to_date."', 'DD-MON-YY')";



  // $open_search = select_query_json("select resfusr,count(*) CLOSED, (select count(*)  from service_response
  //                                     where resstat='A' and resfusr = ser.resfusr) ASSGND,
  //                                     (select count(*)  from service_response where resstat='N' and resfusr = ser.resfusr) NOT_ASGND,
  //                                     (select empname from trandata.employee_office@tcscentr eof where eof.empsrno=ser.resfusr) NAME
  //                                     from service_response ser where resstat='C' ".$and." group by resfusr", "Centra", 'TCS');

  $open_search = select_query_json("select resfusr, count(*) CLOSED, (select count(*)  from service_response where resstat='A' and resfusr = ser.resfusr) ASSGND,
                                                                            (select count(*)  from service_response where resstat='N' and resfusr = ser.resfusr) NOT_ASGND,
                                                                            (select empcode||' - '||empname from trandata.employee_office@tcscentr eof where eof.empsrno=ser.resfusr) NAME
                                                                        from service_response ser 
                                                                        where resstat='C'".$and."
                                                                        group by resfusr
                                                                        Order by CLOSED desc", "Centra", 'TCS');
  $complete_csv_string_ex_ro = "#,RESPONSE EC - NAME,TOTAL CALL ID, CLOSED CALL,ASSIGNED CALL,NOT ASSIGNED\n";
  $i = 0;
  // echo "**"; print_r($open_search); echo "**"; exit;
  foreach ($open_search as $product_fixing) 
    { $i++;
    $TOTAL=$product_fixing['CLOSED']+$product_fixing['ASSGND']+$product_fixing['NOT_ASGND'];
    $complete_csv_string_ex_ro .=  $i.",".$product_fixing['NAME'].",".$TOTAL.",".$product_fixing['CLOSED'].",".$product_fixing['ASSGND'].",".$product_fixing['NOT_ASGND']."\n";
  }
}


if($values=='service_request_report') { $handle = fopen("uploads/csv/service_request_report.csv", "w"); }
fwrite($handle, $complete_csv_string_ex_ro);
fclose($handle);

if($values=='service_request_summary') { $handle = fopen("uploads/csv/service_request_summary.csv", "w"); }
fwrite($handle, $complete_csv_string_ex_ro);
fclose($handle);

// echo $_GET['f']; exit;
###############################################################
# File Download 1.3
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
###############################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###############################################################

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
define('BASE_DIR','uploads/csv/');
//define('BASE_DIR','/home/iteanzc/public_html/itEANz/Resume/sample resume/');

// log downloads?  true/false
define('LOG_DOWNLOADS',false);

// log file name
define('LOG_FILE','downloads.log');

// Allowed extensions list in format 'extension' => 'mime type'
// If myme type is set to empty string then script will try to detect mime type 
// itself, which would only work if you have Mimetype or Fileinfo extensions
// installed on server.
$allowed_ext = array (

  // archives
  'zip' => 'application/zip',

  // documents
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'xls' => 'application/vnd.ms-excel',
  'ppt' => 'application/vnd.ms-powerpoint',
  'csv' => 'application/csv',  
  // executables
  'exe' => 'application/octet-stream',

  // images
  'gif' => 'image/gif',
  'png' => 'image/png',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',

  // audio
  'mp3' => 'audio/mpeg',
  'wav' => 'audio/x-wav',

  // video
  'mpeg' => 'video/mpeg',
  'mpg' => 'video/mpeg',
  'mpe' => 'video/mpeg',
  'mov' => 'video/quicktime',
  'avi' => 'video/x-msvideo'
);



####################################################################
###  DO NOT CHANGE BELOW
####################################################################

// If hotlinking not allowed then make hackers think there are some server problems
if (ALLOWED_REFERRER !== ''
&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
) {
  die("Internal server error. Please contact system administrator.");
}

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);

if (!isset($_GET['f']) || empty($_GET['f'])) {
  die("Please specify file name for download.");
}

// Get real file name.
// Remove any path info to avoid hacking by adding relative path, etc.
$fname = basename($_GET['f']);
//echo($fname);
// Check if the file exists
// Check in subfolders too
function find_file ($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);
  
  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file(
		$dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} // find_file

// get full file path (including subfolders)
$file_path = '';
find_file(BASE_DIR, $fname, $file_path);
echo($file_path);
if (!is_file($file_path)) {
  die("File does not exist. Make sure you specified correct file name."); 
}

// file size in bytes
$fsize = filesize($file_path); 

// file extension
$fext = strtolower(substr(strrchr($fname,"."),1));

// check if allowed extension
if (!array_key_exists($fext, $allowed_ext)) {
  die("Not allowed file type."); 
}

// get mime type
if ($allowed_ext[$fext] == '') {
  $mtype = '';
  // mime type is not set, get from server settings
  if (function_exists('mime_content_type')) {
    $mtype = mime_content_type($file_path);
  }
  else if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME); // return mime type
    $mtype = finfo_file($finfo, $file_path);
    finfo_close($finfo);  
  }
  if ($mtype == '') {
    $mtype = "application/force-download";
  }
}
else {
  // get mime type defined by admin
  $mtype = $allowed_ext[$fext];
}

// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.

if (!isset($_GET['fc']) || empty($_GET['fc'])) {
  $asfname = $fname;
}
else {
  // remove some bad chars
  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
  if ($asfname === '') $asfname = 'NoName';
}

// set headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// download
// @readfile($file_path);
$file = @fopen($file_path,"rb");
if ($file) {
  while(!feof($file)) {
    print(fread($file, 1024*8));
    flush();
    if (connection_status()!=0) {
      @fclose($file);
      die();
    }
  }
  @fclose($file);
}

// log downloads
if (!LOG_DOWNLOADS) die();

$f = @fopen(LOG_FILE, 'a+');
if ($f) {
  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
  @fclose($f);
}
?>