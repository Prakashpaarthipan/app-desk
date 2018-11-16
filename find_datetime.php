<?php
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('general_functions.php');
include_once('lib/function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

function find_end_time($approval_no, $current_date, $current_time, $time_duration) {
	$strdtme = strtotime($current_date.strtoupper("10:00:00 AM"));
	$enddtme = strtotime($current_date.strtoupper("07:00:00 PM"));

	echo "<br>**".$current_time."**".$current_date."**".$time_duration."**<br>";
	
	/*
	$timestamp = strtotime($current_date.$current_time) + 60 * 60 * $time_duration;
	echo "<br>**".$approval_no."**".$current_date."**".$time_duration."**".$timestamp."**".$strdtme."**".$enddtme."**";
	$end_time = $enddtme - $timestamp;
	echo "<br>**".$end_time."**";
	*/

	// $checkTime = strtotime('07:10:00 PM');
	// $checkTime = strtotime('06:59:14 PM');
	$checkTime = strtotime($current_time) + 60 * 60 * $time_duration;
	// echo 'Check Time : '.date('h:i:s A', $current_time);
	// echo '<br>';

	$loginTime = strtotime('07:00:00 PM');
	$diff = $loginTime - $checkTime;
	// echo 'Login Time : '.date('h:i:s A', $checkTime).'<br>';
	// echo ($diff < 0)? 'Late!' : 'Right time!'; echo '<br>';
	// echo 'Time diff in sec: '.abs($diff);

	// echo '<br>';
	echo "***".$diff_time = $diff;

	switch ($diff_time) {
		case $diff_time < 0 :
			// Move to next day
			$time1 = "10:00:00";
			if($checkTime >= '07:00:00 PM') {
				echo "came".$time2 = gmdate("H:i:s", abs($diff_time + 3600));
			} else {
				$time2 = gmdate("H:i:s", abs($diff_time));
			}

			$secs = strtotime($time2) - strtotime("00:00:00");
			$final_date = strtoupper(date('d-M-Y', strtotime("+1 days"))." ".date("h:i:s A",strtotime($time1) + $secs));
			break;

		case $diff_time >= 0 :
			// process current day
			$final_date = strtoupper(date('d-M-Y')." ".date("h:i:s A", $checkTime));
			break;
		
		default:
			// # code...
			break;
	}
	echo "******".$final_date;
}





/* 
$loginTime = strtotime('09:00:59');
$diff = $checkTime - $loginTime;
echo 'Login Time : '.date('H:i:s', $loginTime).'<br>';
echo ($diff < 0)? 'Late!' : 'Right time!';

echo '<hr>';

$loginTime = strtotime('09:00:00');
$diff = $checkTime - $loginTime;
echo 'Login Time : '.date('H:i:s', $loginTime).'<br>';
echo ($diff < 0)? 'Late!' : 'Right time!';
*/

$sql_duration = select_query_json("select * from approval_duration 
										where deleted = 'N' and pricode = 3 and (DESCODE in ".$_SESSION['tcs_descode']." or ESECODE = ".$_SESSION['tcs_esecode']." 
											or empsrno = ".$_SESSION['tcs_empsrno']." or (EMPSRNO = 0 and ESECODE = 0 and DESCODE = 0)) 
										order by ADRCODE desc", "Centra", 'TEST');
$currentdate = strtoupper(date('d-M-Y'));
$currenttime = strtoupper(date('h:i:s A'));
$apprno = "ADMIN / OFFICE 4000025 / 20-10-2018 / 0025 / 01:33 PM";
// $endtime = find_end_time($apprno, $currentdate, $currenttime, $sql_duration[0]['RGFLWTM']);
$endtime = find_end_time($apprno, $currentdate, $currenttime, 2);


echo "<br><br>";
?>