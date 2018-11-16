<?php
header('Content-type:application/json');
$data=array();
$data['cookietcs_empcode']=$_COOKIE['cookietcs_empcode'];
if(isset($_SESSION['uname'])!='' && isset($_SESSION['password'])!=''){
$data['tcs_username']=$_SESSION['tcs_username'];
$data['tcs_user']=$_SESSION['tcs_user'];
$data['tcs_userid']=$_SESSION['tcs_userid'];
$data['tcs_empsrno']=$_SESSION['tcs_empsrno'];
$data['tcs_brncode']=$_SESSION['tcs_brncode'];
$data['tcs_empname']=$_SESSION['tcs_empname'];
$data['tcs_esecode']=$_SESSION['tcs_esecode'];
$data['tcs_originalesecode']=$_SESSION['tcs_originalesecode'];
$data['tcs_descode']=$_SESSION['tcs_descode'];
$data['tcs_usrcode']=$_SESSION['tcs_usrcode'];
$data['loggedin_category']=$_SESSION['loggedin_category'];
$data['tcs_emptopcore']=$_SESSION['tcs_emptopcore'];
$data['tcs_empsubcore']=$_SESSION['tcs_empsubcore'];
$data['tcs_emptopcore_code']=$_SESSION['tcs_emptopcore_code'];
$data['tcs_empsubcore_code']=$_SESSION['tcs_empsubcore_code']; 
$data['websiteurl']=$_SESSION['websiteurl']; 
$data['tcs_section']=$_SESSION['tcs_section'];
$data['tcs_section_a']=$_SESSION['tcs_section_a'];
$data['tcs_partsup']= $_SESSION['tcs_partsup'];
$data['tcs_supemp']= $_SESSION['tcs_supemp'];
$data['tcs_mobile'] =$_SESSION['tcs_mobile'];
$data['tcs_section_rights'] = $_SESSION['tcs_section_rights'];
}
else{
	$data['error']='you are not logged in at tcs portal';
}
echo json_encode($data);
?>