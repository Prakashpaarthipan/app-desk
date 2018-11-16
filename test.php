<?
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);


  /* $sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empsrno=".$profile_img, "Centra", "TCS");
  if(count($sql_sub) <= 0) {
    $sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
  }
  $img = $sql_sub[0]['EMPPHOT']->load();
  header("Content-type: image/pjpeg");186,29
  ob_start();
  echo $img; */
//echo("hi");
$sql_sub = select_query_json("select eof.empname,eof.empsrno from employee_personal eop,employee_office eof,designation deg where eof.descode in ('100') and eof.descode=deg.descode and eop.empsrno=eof.empsrno and eop.gender='F' ", "Centra", "TCS");

echo($sql_sub[13]['EMPNAME']);
echo($sql_sub[13]['EMPSRNO']);
//$ch=$_REQUEST['profile_img'];
  // $data = base64_decode($sql_sub[$ch]['EMPNAME']);
  // $im = imagecreatefromstring($data);
  // if ($im !== false) {
  //     header('Content-Type: image/png');
  //     imagepng($im);
  //     imagedestroy($im);
  // }
  // else {
  //     echo 'An error occurred.';
  // }

?>