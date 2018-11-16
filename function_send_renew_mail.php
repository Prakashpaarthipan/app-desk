<?

session_start();
error_reporting(0);
header('Content-Type: text/json; charset=utf-8');
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');

$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
//select to_char(AGEXPDT,'dd-MM-yyyy HH:mi:ss AM') AGEXPDT,apprsfr from approval_request where extract(month from apprsfr) = 8 and arqcode >=2800 DYNSUBJ
$renew_list = select_query_json("select *  from approval_request where atycode = 9 and arqcode >=2824 and deleted = 'N' and APPSTAT = 'A' ","Centra","TEST");


$curdate = date('d-M-Y');
$days = "-50 days";


echo "*********** Current Date :".$curdate."***********\n";
$datelist = array();

	for($list = 0;$list<count($renew_list) ; $list++){

		$duration = explode(" - ", $renew_list[$list]['DYNSUBJ']);

		$durdate = $duration[1];

		$aprnumb = $renew_list[$list]['APRNUMB'];

		$check= date('d-M-Y',strtotime($days,strtotime($durdate)));

		echo '####'.$duration[1]."#------------BEFORE ".$days."#---------->".$check."\n\n";
		echo '####'.$aprnumb."\n\n";


		$check1 = date('d-M-Y');
			 if($check1 === $curdate ){
			 	$sub = " Reminder : Your Approval Number  ".$aprnumb." is expired in ".$duration[1];
			 	$body = "<BR>Dear Sir,<BR>Your Approval Number <b>\"".$aprnumb."\"</b> is expired in 30 Days.<BR>Kindly verify the request.<BR><BR>Thank you,<BR>Approval Desk Team<BR>";
			  	echo "STATUS : Date is Match! \n";

			  	//$sql_mailsend = store_procedure_query_json("PORTAL_APPROVED_AUTO_MAIL('".$aprnumb."', ' ', '".$sub."', '".$body."', '".$_SESSION['tcs_usrcode']."')", 'Req', 'TCS');
			  	echo "\n\n%%%%%%%%%%".$sql_mailsend."%%%%%%%%%";
			  }
			  else{
			  	
			  	echo "STATUS : date does not match"."\n\n\n";

			  }

echo "----------------------------------------------\n\n";
	}
 



//print_r($renew_list);


/*
function send_mail_function($fncretr, $slt_approval_listings, $txt_approval_number, $slt_dynamic_subject) {
//*********** Mail Send Function to User - Approval ********************
	$sql_email = select_query_json("select * from approval_email_master where EMPSRNO not in (21344) and EMPSRNO = ".$fncretr, "Centra", "TCS");

	$txt_email = '';
	$tomail = $sql_email[0]['EMAILID'];
	if($tomail != '') {
		$txt_email .= rtrim($tomail, ',');
		$txt_email .= ',approvals@thechennaisilks.com';
	} else {
		$txt_email .= 'approvals@thechennaisilks.com';
	}
	if($txtrequest_value > 0) {
		$txt_email .= ',projectmanagement.support@thechennaisilks.com';
	}

	$to1 = $txt_email;
	$sql_aplist = select_query_json("select * from approval_master where APMCODE = ".$slt_approval_listings, "Centra", "TCS");
	$exl = explode(" / ", $txt_approval_number);
	$txt_approval_no = $exl[0]." / ".$exl[1];
	// $subject1 = substr("Reg:\"".$txt_approval_number."\" Request has been approved", 0, 100);
	$subject1 = substr("Reg:".$slt_dynamic_subject.$sql_aplist[0]['APMNAME']." - ".$txt_approval_no." Request has been approved", 0, 100);
	$mail_body1 = substr("<html><body><table border=0 cellpadding=1 cellspacing=1 width='100%'>
		<tr><td height='25' align='left' colspan=2>Dear Sir,</td></tr>
		<tr><td height='25' align='left' colspan=2>Congrats! <b>\"".$slt_dynamic_subject.$sql_aplist[0]['APMNAME']." - ".$txt_approval_number."\"</b> request has been approved.</td></tr>
		<tr><td height='25' align='left' colspan=2>Kindly contact to the approval desk team regarding this request.</td></tr>
		<tr height='25'></tr>
		<tr><td colspan=2>
		  Thank you,
		  <BR>Approval Desk Team.
		  <BR>The Chennai Silks - CORP</td></tr>
	</table></body></html>", 0, 2000);

	$sql_all_mail = select_query_json("select distinct emp.empsrno, mail.emailid, hir.amhsrno
												from approval_request req, approval_mdhierarchy hir, approval_email_master mail, employee_office emp
												where req.aprnumb = hir.aprnumb and emp.empcode=hir.apphead and emp.empsrno=mail.empsrno and
													hir.aprnumb = '".$txt_approval_number."' and req.appstat = 'A'
												order by amhsrno", "Centra", "TCS");
	if(count($sql_all_mail) > 0) {
		foreach ($sql_all_mail as $emails) {
			$sql_aprv = select_query_json("select * from approval_request where aprnumb = '".$txt_approval_number."' and appstat = 'A' and arqsrno = 1", "Centra", "TCS");
			if(count($sql_aprv) > 0) {
				$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", "Centra", "TCS");
				$tbl_name="mail_send_summary";
				
				
				$ivqry = delete_dbquery("insert into mail_send_summary (MAILYEAR, MAILNUMB, DEPTID, MAILSUB, MAILCON, FILECNT, ADDUSER, ADDDATE, EMAILID, STATUS, DEPNAME) values ('".$hidapryear."', (select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary), '1', '".$subject1."', '".$mail_body1."', '0', '".$_SESSION['tcs_usrcode']."', sysdate, '".$emails['EMAILID']."', 'N', 'APP DESK')");

				
				echo "insert into mail_send_summary (MAILYEAR, MAILNUMB, DEPTID, MAILSUB, MAILCON, FILECNT, ADDUSER, ADDDATE, EMAILID, STATUS, DEPNAME) values ('".$hidapryear."', (select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary), '1', '".$subject1."', '".$mail_body1."', '0', '".$_SESSION['tcs_usrcode']."', sysdate, '".$emails['EMAILID']."', 'N', 'APP DESK')";
				echo "++".$ivqry."++";
			}
		}

		$sql_mailnum = select_query_json("select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary", "Centra", "TCS");
		
		
		$ivqry = delete_dbquery("insert into mail_send_summary (MAILYEAR, MAILNUMB, DEPTID, MAILSUB, MAILCON, FILECNT, ADDUSER, ADDDATE, EMAILID, STATUS, DEPNAME) values ('".$hidapryear."', (select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary), '1', '".$subject1."', '".$mail_body1."', '0', '".$_SESSION['tcs_usrcode']."', sysdate, '".$to1."', 'N', 'APP DESK')");
		
		
		echo "insert into mail_send_summary (MAILYEAR, MAILNUMB, DEPTID, MAILSUB, MAILCON, FILECNT, ADDUSER, ADDDATE, EMAILID, STATUS, DEPNAME) values ('".$hidapryear."', (select nvl(max(MAILNUMB)+1,1) as MAILNUMB from mail_send_summary), '1', '".$subject1."', '".$mail_body1."', '0', '".$_SESSION['tcs_usrcode']."', sysdate, '".$to1."', 'N', 'APP DESK')";
		echo "--".$ivqry."--";
	
	}
}
*/
?>