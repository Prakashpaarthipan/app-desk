<?php 
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

if($action == 'profile_img') {
	/* $sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empsrno=".$profile_img, "Centra", "TCS");
	if(count($sql_sub) <= 0) {
		$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
	}
	$img = $sql_sub[0]['EMPPHOT']->load();
	header("Content-type: image/pjpeg");
	ob_start();
	echo $img; */

	$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empsrno=".$profile_img, "Centra", "TCS");
	if(count($sql_sub) <= 0) {
		$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
	}
	$data = base64_decode($sql_sub[0]['EMPPHOT']);
	$im = imagecreatefromstring($data);
	if ($im !== false) {
	    header('Content-Type: image/png');
	    imagepng($im);
	    imagedestroy($im);
	}
	else {
	    echo 'An error occurred.';
	}
} elseif($action == 'user_profile_img') {
	$result = select_query_json("select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, empsection sec, designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 1 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['profile_img'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['profile_img'])."%' ) and emp.brncode = '".$branch."' 
									union
										select distinct(emp.EMPSRNO), emp.EMPCODE, emp.EMPNAME, emp.ESECODE, sec.ESENAME, emp.DATEOFJOIN, des.DESNAME, sal.PAYCOMPANY 
										from employee_office emp, new_empsection sec, new_designation des, employee_salary sal 
										where emp.ESECODE = sec.ESECODE and emp.DESCODE = des.DESCODE and (emp.empcode not between 11 and 1000) and sec.deleted = 'N' and des.deleted = 'N' and 
											sal.PAYCOMPANY = 2 and emp.empsrno = sal.empsrno and ( emp.EMPCODE like '%".strtoupper($_GET['profile_img'])."%' or 
											emp.EMPNAME like '%".strtoupper($_GET['profile_img'])."%' ) and emp.brncode = '".$branch."' 
										order by EMPCODE Asc", "Centra", 'TCS');
	if(count($result) > 0) {
		$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
		$data = base64_decode($sql_sub[0]['EMPPHOT']);
		$im = imagecreatefromstring($data);
		if ($im !== false) {
			header('Content-Type: image/png');
			imagepng($im);
			// imagedestroy($im);
		}
		else {
			echo 'An error occurred.';
		}
	} else {
		echo 'An error occurred.';
	}
} else {
	/* $sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
	$img = $sql_sub[0]['EMPPHOT']->load();
	header("Content-type: image/pjpeg");
	ob_start();
	echo $img; */

	$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empsrno=".$profile_img, "Centra", "TCS");
	if(count($sql_sub) <= 0) {
		$sql_sub = select_query_json("select p.empphot from employee_personal p, employee_office o where p.empsrno=o.empsrno and o.empcode=".$profile_img, "Centra", "TCS");
	}
	$data = base64_decode($sql_sub[0]['EMPPHOT']);
	$im = imagecreatefromstring($data);
	if ($im !== false) {
	    header('Content-Type: image/png');
	    imagepng($im);
	    imagedestroy($im);
	}
	else {
	    echo 'An error occurred.';
	}
}
?>