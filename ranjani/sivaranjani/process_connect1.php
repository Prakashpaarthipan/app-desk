<?php
session_start();
error_reporting(0);
include_once('config.php');
include_once('../general_functions.php');
include_once('function_connect.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

// connect and login to FTP server
$ftp_conn = ftp_connect(ftpvri_server_apdsk, 5022) or die("Could not connect to ftpvri_server_apdsk");
$login = ftp_login($ftp_conn, ftpvri_user_name_apdsk, ftpvri_user_pass_apdsk);

// *** Sign In / Login Function ***
if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['function'] == 'signin') {
	try{
	//check $_POST vars are set, exit if any missing
	if (!isset($_POST["uname"]) || !isset($_POST["password"])) {
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$uname = filter_var(trim($_POST["uname"]), FILTER_SANITIZE_STRING);
	$password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING);
	// $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_EMAIL);

	//additional php validation
	if (strlen($uname) < 7) { // If length is less than 7 it will throw an HTTP error.
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Username is too short!'));
		die($output);
	}
	if (strlen($password) < 3) { // If length is less than 3 it will throw an HTTP error.
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Password is too short!'));
		die($output);
	}
	/* if (!filter_var($password, FILTER_VALIDATE_EMAIL)) { //email validation
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Please enter a valid email!'));
		die($output);
	} */

	/* $fn_username = isset($_POST['uname']) ? mysqli_real_escape_string($_POST['uname']) : "";
	$fn_password = isset($_POST['password']) ? mysqli_real_escape_string($_POST['password']) : "";
	$fn_rememberme = isset($_POST['rememberme']) ? mysqli_real_escape_string($_POST['rememberme']) : "";

	if(!empty($_POST['uname']) && !empty($_POST['password'])){
		$qur = mysqli_query($conn, "select user_id, user_name, user_email from `prl_users` where user_status = 1 and user_name='".$_POST['uname']."' and user_password='".md5($_POST['password'])."'");
		$result =array();
		if(mysqli_num_rows($qur)) {
			while($r = mysqli_fetch_array($qur, MYSQLI_ASSOC)){
				extract($r);
				$_SESSION['prl_userid'] = $user_id;
				$_SESSION['prl_username'] = $user_name;
				$result[] = array("user_id" => $user_id, "user_name" => $user_name, 'user_email' => $user_email);
				mysqli_free_result($result);
			}
			$json = json_encode(array('type' => 'success', "info" => $result, "msg" => "Please Wait!!!"));
		} else {
			$_SESSION['prl_userid'] = '';
			$_SESSION['prl_username'] = '';
			$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!"));
		}
	} else {
		$_SESSION['prl_userid'] = '';
		$_SESSION['prl_username'] = '';
		$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!"));
	}
	@mysqli_close($conn);

	/* $username = $_POST['uname'];
	$password = $_POST['password'];
	$client = new SoapClient("http://172.16.0.166:8080/cdata.asmx?Wsdl");
	$brn_parameter->Brn_Code_='$username';
	$brn_parameter->Dep_Code_='$password';
    try{
        $brn_result=$client->Get_PerCentage($brn_parameter)->Get_PerCentageResult;
    }
    catch(SoapFault $fault){
        echo "Fault code:{$fault->faultcode}".NEWLINE;
        echo "Fault string:{$fault->faultstring}".NEWLINE;
        if ($client != null)
        {
            $client=null;
        }
       // exit();
    }
    $soapClient = null;
    $brn =  json_decode($brn_result,true);
    echo '<script>window.location="home.php";</script>';

	echo "<pre>";
	print_r($brn); */


	$fn_username = isset($_POST['uname']) ? ($_POST['uname']) : "";
	$fn_password = isset($_POST['password']) ? ($_POST['password']) : "";
	$fn_rememberme = isset($_POST['rememberme']) ? ($_POST['rememberme']) : "";
	$fn_selected_section_group = isset($_POST['selected_section_group']) ? ($_POST['selected_section_group']) : "";

	$currentip = get_client_ip();
	$valid_user = 0;
	$top_level = 0;
	$no_need_chk = array(9938358, 2000000, 3000000, 1986888, 1049888, 6666003, 8888002, 4003579, 7292222); // No Need to Check these users are Thumb login or not.
	if(in_array($fn_username, $no_need_chk)) {
		$valid_user = 1;
	} else {
		$sql_noneedchk = select_query_json("Select User_Access(".$fn_username.",'M') lock_ From dual", "Centra", 'TCS');
		$valid_user = 1;

		/* if($sql_noneedchk[0]['LOCK_'] == 'Y') {
			$valid_user = 1;
		} else {
			$valid_user = chk_usr_logins_json($fn_username, $currentip);
		} */
	}

	if($valid_user != 1) {
		$json = json_encode(array('type' => 'error', "info" => '', "msg" => $valid_user));
	} elseif($valid_user == 1) {
		$rememberme = strip_tags($fn_rememberme);
		if ($rememberme)
		{
			setcookie("loggedIn", "yes", time()+31536000);
			setcookie("uname", $fn_username, time()+31536000);
			setcookie("password", $fn_password, time()+31536000);

			$_SESSION['uname']=$fn_username;
			$_SESSION['password']=$fn_password;
		}
		setcookie("selected_section_group", $fn_selected_section_group, time()+31536000);
		$_SESSION['selected_section_group']=$fn_selected_section_group;

		if($fn_password !='')
		{
			$encrpted=encrypt_pwd(substr($fn_password, 0, 10));
			$resp = select_query_login_check_json($fn_username, $fn_password, "Centra", 'TCS');
			// echo "**".$resp."**";
			if($resp == 1) { // echo "::";
				// $response1 = select_query_json("select * from userid where USRCODE = ".$_REQUEST['uname']."", "Centra", 'TCS');
				$response1 = select_query_json("select * from userid where USRCODE = ".$_REQUEST['uname']."", "Centra", 'TEST');
			} else { // echo "==+".$resp."+==";
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => $resp));
				die($json);
			}

			if($fn_username == 8888002) {
				$resvalue = 8237;
				$empname = 'ANGURAJ';
				$top_level = 1;
			} elseif($fn_username == 6666003) {
				$resvalue = 2758;
				$empname = 'KANNAPEERAN';
				$top_level = 1;
			} else {
				$resvalue = $response1[0]['EMPSRNO'];
				$empname = '';
				$top_level = 0;
			}

			if($fn_username == 3000000) {
				$add_date = "1";
			}

			$sql_emp = select_query_json("select e.*, (select ATCNAME from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore, (select CORNAME from empcore_section where esecode = e.esecode) subcore,
												(select ATCCODE from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore_code, ESECODE subcore_code
											from employee_office e where EMPSRNO = ".$resvalue, "Centra", 'TEST');

			$empcode = select_query_json("select * from employee_office where EMPSRNO = ".$resvalue, "Centra", 'TEST');

			$_SESSION['tcs_username'] 		 = $response1[0]['USRNAME'];
			$_SESSION['tcs_user'] 		 	 = $empcode[0]['EMPCODE'];
			$_SESSION['tcs_userid'] 		 = $empcode[0]['EMPSRNO'];
			$_SESSION['tcs_empsrno'] 		 = $empcode[0]['EMPSRNO'];
			$_SESSION['tcs_brncode'] 		 = $empcode[0]['BRNCODE'];
			if($empname == '') {
				$_SESSION['tcs_empname'] 	 = $empcode[0]['EMPNAME'];
			} else {
				$_SESSION['tcs_empname'] 	 = $empname;
			}
			$_SESSION['tcs_esecode'] 		 = $empcode[0]['ESECODE'];
			$_SESSION['tcs_originalesecode'] = $empcode[0]['ESECODE'];
			$_SESSION['tcs_descode'] 		 = $empcode[0]['DESCODE'];
			$_SESSION['tcs_usrcode'] 		 = $response1[0]['USRCODE'];
			$_SESSION['loggedin_category'] 	 = strtoupper($fn_selected_section_group);

			$_SESSION['tcs_emptopcore']		 = $sql_emp[0]['TOPCORE'];
			$_SESSION['tcs_empsubcore']		 = $sql_emp[0]['SUBCORE'];
			$_SESSION['tcs_emptopcore_code'] = $sql_emp[0]['TOPCORE_CODE'];
			$_SESSION['tcs_empsubcore_code'] = $sql_emp[0]['SUBCORE_CODE'];

			setcookie("cookietcs_empsrno", $empcode[0]['EMPSRNO'], time()+31536000);
			setcookie("cookietcs_empcode", $empcode[0]['EMPCODE'], time()+31536000);
			$_SESSION['auditor_login']	 	 = 0;
			if($response1[0]['USRCODE'] == 1049888)
			{
				$_SESSION['auditor_login'] 	 = 1;
			}

			$_SESSION['websiteurl']		= "http://".$_SERVER['HTTP_HOST']."/";
			$redirect_url = '';
			if($hid_action == '') {
				switch(strtoupper($fn_selected_section_group))
				{
					case 'SYSTEM':
							$redirect_url = 'home.php';
							break;
					case 'ADMIN':
							$redirect_url = 'home.php';
							break;
					case 'PURCHASE':
							$redirect_url = 'home.php';
							break;
					case 'CRM':
							$redirect_url = 'home.php';
							break;
					case 'JEWELLERY':
							$redirect_url = 'ktmportal/index.php';
							break;
					case 'APPROVAL DESK':
							$redirect_url = 'home.php';
							break;
					case 'OFFLINE REPORT':
							$redirect_url = 'offline_report/index.php';
							break;
					default:
							$redirect_url = 'home.php';
							break;
				}
			} elseif($hid_action == 'cognos_suprolfix') {
				$redirect_url = 'suprolfix_group_mode.php';
			}

			$response = '';
			$response = select_query_json("select PRTCODE, SUPCODE, EMPSRNO, USRNAME, PASALOW, PARSECT, PARTSUP, USRSTAT, USRMOBL, ALLSECTION, BRNCODE
													from srm_userid where empsrno = '".$empcode[0]['EMPSRNO']."'", "Centra", 'TEST');
			// print_r($response);
			if($response[0]['ALLSECTION'] == 'N')
			{
				$_SESSION['tcs_section'] = $response[0]['PARSECT'];
				$_SESSION['tcs_section_a'] = $response[0]['PARSECT'];
			} elseif($response[0]['ALLSECTION'] == 'Y') {
				$sql_section = select_query_json("select SECCODE from section where deleted = 'N' and secname not like '%ALBUM%' order by SECSRNO Asc", "Centra", 'TEST');
				$sql_section_a = select_query_json("select SECCODE from section where  deleted = 'N' and secname not like '%ALBUM%' order by SECSRNO Asc", "Centra", 'TEST');
				$seccode = '';
				foreach($sql_section as $section)
				{
					$seccode .= $section['SECCODE'].",";
				}
				$sec = rtrim($seccode, ",");
				$_SESSION['tcs_section'] = $sec;

				$seccode_a = '';
				foreach($sql_section_a as $section_a)
				{
					$seccode_a .= $section_a['SECCODE'].",";
				}
				$sec_a = rtrim($seccode_a, ",");
				$_SESSION['tcs_section_a'] = $sec_a;
			}
			$_SESSION['tcs_partsup'] 		= $response[0]['PARTSUP'];
			$_SESSION['tcs_supemp'] 		= $response[0]['USRSTAT'];
			$_SESSION['tcs_mobile'] 		= $response[0]['USRMOBL'];
			$_SESSION['tcs_section_rights'] = $response[0]['ALLSECTION'];

			$sql_brnch = select_query_json("select brn.brncode from branch brn
													where brn.DELETED = 'N' and brncode in (1,2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,19,20,21,22,23,24,25,26,100,102,104,107,108,110,112,113,115,116,301,302,303,304,305,306,888)
													order by brn.BRNCODE", "Centra", 'TCS');
			$branch = '';
			foreach($sql_brnch as $brnch) {
				$branch .= $brnch['BRNCODE'].',';
			}
			$branch = rtrim($branch, ",");
			$_SESSION['tcs_all_allowed_branch'] = $branch;
			// print_r($response); exit;

			$sql_brnch1 = select_query_json("select brn.BRNCODE from srm_userid brn
														where brn.DELETED = 'N' and empsrno=".$_SESSION['tcs_empsrno']." order by brn.BRNCODE", "Centra", 'TCS');

			$branch1 = '';
			foreach($sql_brnch1 as $brnch1) {
				$branch1 .= $brnch1['BRNCODE'].',';
			}
			$branch1 = rtrim($branch1, ",");
			$_SESSION['tcs_booking_branch'] = $branch1;

			if($top_level == 1) {
				$_SESSION['tcs_allowed_branch'] = '2,3,7,13';
			} else {
				if($response[0]['BRNCODE'] == '') {
					$_SESSION['tcs_allowed_branch'] = $branch;
				} elseif($response[0]['BRNCODE'] != '') {
					$_SESSION['tcs_allowed_branch'] = $response[0]['BRNCODE'];
				}
			}
			$iijj = 1;
			$_SESSION['iijj'] = 1;

			// echo "***".$resvalue."###".$response[0]['SUPCODE']."***"; exit;
			if($response[0]['SUPCODE'] > 0 and $resvalue == '') {
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Supplier Cannot Login Here!!"));
			} elseif($resvalue != '') {
				$sql_menuaccess = select_query_json("select distinct ma.mnucode, mainmenu, submenu from srm_menu_access ma, srm_menu mu
															where mu.mnucode = ma.mnucode and (ma.SUPCODE = '".$resvalue."' or ma.ENTSRNO = '".$resvalue."')
															order by ma.mnucode Asc", "Centra", 'TCS');
				for($ij = 0; $ij < count($sql_menuaccess); $ij++)
				{
					$mainmenu_access[] = $sql_menuaccess[$ij][1];
					$submenu_access[] = $sql_menuaccess[$ij][2];
				}
				$_SESSION['tcs_mainmenu_access'] = $mainmenu_access;
				$_SESSION['tcs_submenu_access'] = $submenu_access;
				$rights = find_user_rights(strtoupper($fn_selected_section_group), $empcode[0]['EMPSRNO']);

				if($rights == 0) {
					$redirect_url = 'logout.php';
					$json = json_encode(array('type' => 'error', "info" => $redirect_url, "msg" => "You dont have access rights to view this"));
				} else {
					// Mr. AK Sir Approvals - Directly load from Login
					if($fn_username == 3000000 and strtoupper($fn_selected_section_group) == 'APPROVAL DESK') {
						// $redirect_url = "waiting_mdapproval_reports.php"; // Correct url
						$redirect_url = "home.php"; // wrong url
					}
					// Mr. AK Sir Approvals - Directly load from Login
					$userip = array("9938358" => "172.16.50.5", // KS Sir - MD
									"2000000" => "172.16.50.6", // PS Madam - MD
									"3000000" => "", 			// AK Sir - MD

									"2444001" => "172.16.48.12", // RDTM - Sr. GM
									"2001002" => "172.16.52.11", // Ganesh - GM
									"1986888" => "172.16.50.2", // Kumaran - GM
									"1062001" => "172.16.52.33", // NSM - GM

									"1118001" => "172.16.51.11", // PKN - Accounts HOD
									"1389888" => "172.16.50.13", // Senthil - Admin HOD
									"1112001" => "172.16.48.30", // UC Muthukumaar - Admin HOD
									"1228001" => "172.16.52.5", // SP Saravanan - IT HOD
									"1340005" => "172.16.48.43", // Venkat Durairaj - Sales HOD
									"1384004" => "172.16.50.21", // Karthikeyan - HW HOD
									"1366003" => "172.16.48.72", // Mohan - AK Sir PA
									"1367002" => "172.16.51.12", // Narayana Moorthy - S-Team HOD
									"6635888" => "172.16.9.152" // Chandra Sekaran - Designing HOD
								   );

					if (array_key_exists($response1[0]['USRCODE'], $userip)) {
						$_SESSION['tcs_userip'] = UserIPAddress($response1[0]['USRCODE'], $currentip);
					}

					if($response[0]['EMPSRNO'] == 'Y') {
						$redirect_url = 'index.php';
						$json = json_encode(array('type' => 'success', "info" => $redirect_url, "msg" => "Please Wait!!!"));
					}
					else {
						$json = json_encode(array('type' => 'success', "info" => $redirect_url, "msg" => "Please Wait!!!"));
					}
				}
			}
			else
			{
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!"));
			}
		}
		else
		{
			$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!"));
		}
	}
}
catch(Exception $e) {
	$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!!"));
}

/* Output header */
// header('Content-type: application/json');
die($json);
}
// *** Sign In / Login Function ***

// *** Sign In / Login Function - TEST ***
if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['function'] == 'signin_test') {
	// print_r($txttag_data); print_r($txttag_term); print_r($txttag_process); // exit;
	try{
	//check $_POST vars are set, exit if any missing
	if (!isset($_POST["uname"]) || !isset($_POST["password"])) {
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$uname = filter_var(trim($_POST["uname"]), FILTER_SANITIZE_STRING);
	$password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_STRING);
	// $password = filter_var(trim($_POST["password"]), FILTER_SANITIZE_EMAIL);

	//additional php validation
	if (strlen($uname) < 7) { // If length is less than 7 it will throw an HTTP error.
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Username is too short!'));
		die($output);
	}
	if (strlen($password) < 3) { // If length is less than 3 it will throw an HTTP error.
		$output = json_encode(array('type' => 'error', "info" => '', 'msg' => 'Password is too short!'));
		die($output);
	}

	$fn_username = isset($_POST['uname']) ? ($_POST['uname']) : "";
	$fn_password = isset($_POST['password']) ? ($_POST['password']) : "";
	$fn_rememberme = isset($_POST['rememberme']) ? ($_POST['rememberme']) : "";
	$fn_selected_section_group = isset($_POST['selected_section_group']) ? ($_POST['selected_section_group']) : "";

	$currentip = get_client_ip();
	$valid_user = 0;
	$top_level = 0;
	$no_need_chk = array(9938358, 2000000, 3000000, 1986888, 1049888, 6666003, 8888002, 4003579, 7292222); // No Need to Check these users are Thumb login or not.
	if(in_array($fn_username, $no_need_chk)) {
		$valid_user = 1;
	} else {
		$sql_noneedchk = select_query_json("Select User_Access(".$fn_username.",'M') lock_ From dual", "Centra", 'TCS');
		$valid_user = 1;

		/* if($sql_noneedchk[0]['LOCK_'] == 'Y') {
			$valid_user = 1;
		} else {
			$valid_user = chk_usr_logins_json($fn_username, $currentip);
		} */
	}

	if($valid_user != 1) {
		$json = json_encode(array('type' => 'error', "info" => '', "msg" => $valid_user));
	} elseif($valid_user == 1) {
		$rememberme = strip_tags($fn_rememberme);
		if ($rememberme)
		{
			setcookie("loggedIn", "yes", time()+31536000);
			setcookie("uname", $fn_username, time()+31536000);
			setcookie("password", $fn_password, time()+31536000);

			$_SESSION['uname']=$fn_username;
			$_SESSION['password']=$fn_password;
		}
		setcookie("selected_section_group", $fn_selected_section_group, time()+31536000);
		$_SESSION['selected_section_group']=$fn_selected_section_group;

		if($fn_password !='')
		{
			$encrpted=encrypt_pwd(substr($fn_password, 0, 10));
			$resp = select_query_login_check_json($fn_username, $fn_password, "Centra", 'TEST');
			// echo "**".$resp."**";
			if($resp == 1) { // echo "::";
				// $response1 = select_query_json("select * from userid where USRCODE = ".$_REQUEST['uname']."", "Centra", 'TCS');
				$response1 = select_query_json("select * from userid where USRCODE = ".$_REQUEST['uname']."", "Centra", 'TEST');
			} else { // echo "==+".$resp."+==";
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => $resp));
				die($json);
			}

			if($fn_username == 8888002) {
				$resvalue = 8237;
				$empname = 'ANGURAJ';
				$top_level = 1;
			} elseif($fn_username == 6666003) {
				$resvalue = 2758;
				$empname = 'KANNAPEERAN';
				$top_level = 1;
			} else {
				$resvalue = $response1[0]['EMPSRNO'];
				$empname = '';
				$top_level = 0;
			}

			if($fn_username == 3000000) {
				$add_date = "1";
			}

			$sql_emp = select_query_json("select e.*, (select ATCNAME from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore, (select CORNAME from empcore_section where esecode = e.esecode) subcore,
												(select ATCCODE from APPROVAL_TOPCORE where ATCCODE in ((select topcore from empcore_section
												where esecode = e.esecode))) topcore_code, ESECODE subcore_code
											from employee_office e where EMPSRNO = ".$resvalue, "Centra", 'TEST');

			$empcode = select_query_json("select * from employee_office where EMPSRNO = ".$resvalue, "Centra", 'TEST');

			$_SESSION['tcs_username'] 		 = $response1[0]['USRNAME'];
			$_SESSION['tcs_user'] 		 	 = $empcode[0]['EMPCODE'];
			$_SESSION['tcs_userid'] 		 = $empcode[0]['EMPSRNO'];
			$_SESSION['tcs_empsrno'] 		 = $empcode[0]['EMPSRNO'];
			$_SESSION['tcs_brncode'] 		 = $empcode[0]['BRNCODE'];
			if($empname == '') {
				$_SESSION['tcs_empname'] 	 = $empcode[0]['EMPNAME'];
			} else {
				$_SESSION['tcs_empname'] 	 = $empname;
			}
			$_SESSION['tcs_esecode'] 		 = $empcode[0]['ESECODE'];
			$_SESSION['tcs_originalesecode'] = $empcode[0]['ESECODE'];
			$_SESSION['tcs_descode'] 		 = $empcode[0]['DESCODE'];
			$_SESSION['tcs_usrcode'] 		 = $response1[0]['USRCODE'];
			$_SESSION['loggedin_category'] 	 = strtoupper($fn_selected_section_group);

			$_SESSION['tcs_emptopcore']		 = $sql_emp[0]['TOPCORE'];
			$_SESSION['tcs_empsubcore']		 = $sql_emp[0]['SUBCORE'];
			$_SESSION['tcs_emptopcore_code'] = $sql_emp[0]['TOPCORE_CODE'];
			$_SESSION['tcs_empsubcore_code'] = $sql_emp[0]['SUBCORE_CODE'];

			setcookie("cookietcs_empsrno", $empcode[0]['EMPSRNO'], time()+31536000);
			setcookie("cookietcs_empcode", $empcode[0]['EMPCODE'], time()+31536000);
			$_SESSION['auditor_login']	 	 = 0;
			if($response1[0]['USRCODE'] == 1049888)
			{
				$_SESSION['auditor_login'] 	 = 1;
			}

			$_SESSION['websiteurl']		= "http://".$_SERVER['HTTP_HOST']."/";
			$redirect_url = '';
			if($hid_action == '') {
				switch(strtoupper($fn_selected_section_group))
				{
					case 'SYSTEM':
							$redirect_url = 'home.php';
							break;
					case 'ADMIN':
							$redirect_url = 'home.php';
							break;
					case 'PURCHASE':
							$redirect_url = 'home.php';
							break;
					case 'CRM':
							$redirect_url = 'home.php';
							break;
					case 'JEWELLERY':
							$redirect_url = 'ktmportal/index.php';
							break;
					case 'APPROVAL DESK':
							$redirect_url = 'home.php';
							break;
					case 'OFFLINE REPORT':
							$redirect_url = 'offline_report/index.php';
							break;
					default:
							$redirect_url = 'home.php';
							break;
				}
			} elseif($hid_action == 'cognos_suprolfix') {
				$redirect_url = 'suprolfix_group_mode.php';
			}

			$response = '';
			$response = select_query_json("select PRTCODE, SUPCODE, EMPSRNO, USRNAME, PASALOW, PARSECT, PARTSUP, USRSTAT, USRMOBL, ALLSECTION, BRNCODE
													from srm_userid where empsrno = '".$empcode[0]['EMPSRNO']."'", "Centra", 'TEST');
			// print_r($response);
			if($response[0]['ALLSECTION'] == 'N')
			{
				$_SESSION['tcs_section'] = $response[0]['PARSECT'];
				$_SESSION['tcs_section_a'] = $response[0]['PARSECT'];
			} elseif($response[0]['ALLSECTION'] == 'Y') {
				$sql_section = select_query_json("select SECCODE from section where deleted = 'N' and secname not like '%ALBUM%' order by SECSRNO Asc", "Centra", 'TEST');
				$sql_section_a = select_query_json("select SECCODE from section where  deleted = 'N' and secname not like '%ALBUM%' order by SECSRNO Asc", "Centra", 'TEST');
				$seccode = '';
				foreach($sql_section as $section)
				{
					$seccode .= $section['SECCODE'].",";
				}
				$sec = rtrim($seccode, ",");
				$_SESSION['tcs_section'] = $sec;

				$seccode_a = '';
				foreach($sql_section_a as $section_a)
				{
					$seccode_a .= $section_a['SECCODE'].",";
				}
				$sec_a = rtrim($seccode_a, ",");
				$_SESSION['tcs_section_a'] = $sec_a;
			}
			$_SESSION['tcs_partsup'] 		= $response[0]['PARTSUP'];
			$_SESSION['tcs_supemp'] 		= $response[0]['USRSTAT'];
			$_SESSION['tcs_mobile'] 		= $response[0]['USRMOBL'];
			$_SESSION['tcs_section_rights'] = $response[0]['ALLSECTION'];

			$sql_brnch = select_query_json("select brn.brncode from branch brn
													where brn.DELETED = 'N' and brncode in (1,2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,19,20,21,22,23,24,25,26,100,102,104,107,108,110,112,113,115,116,301,302,303,304,305,306,888)
													order by brn.BRNCODE", "Centra", 'TCS');
			$branch = '';
			foreach($sql_brnch as $brnch) {
				$branch .= $brnch['BRNCODE'].',';
			}
			$branch = rtrim($branch, ",");
			$_SESSION['tcs_all_allowed_branch'] = $branch;
			// print_r($response); exit;

			$sql_brnch1 = select_query_json("select brn.BRNCODE from srm_userid brn
														where brn.DELETED = 'N' and empsrno=".$_SESSION['tcs_empsrno']." order by brn.BRNCODE", "Centra", 'TCS');

			$branch1 = '';
			foreach($sql_brnch1 as $brnch1) {
				$branch1 .= $brnch1['BRNCODE'].',';
			}
			$branch1 = rtrim($branch1, ",");
			$_SESSION['tcs_booking_branch'] = $branch1;

			if($top_level == 1) {
				$_SESSION['tcs_allowed_branch'] = '2,3,7,13';
			} else {
				if($response[0]['BRNCODE'] == '') {
					$_SESSION['tcs_allowed_branch'] = $branch;
				} elseif($response[0]['BRNCODE'] != '') {
					$_SESSION['tcs_allowed_branch'] = $response[0]['BRNCODE'];
				}
			}
			$iijj = 1;
			$_SESSION['iijj'] = 1;

			// echo "***".$resvalue."###".$response[0]['SUPCODE']."***"; exit;
			if($response[0]['SUPCODE'] > 0 and $resvalue == '') {
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Supplier Cannot Login Here!!"));
			} elseif($resvalue != '') {
				$sql_menuaccess = select_query_json("select distinct ma.mnucode, mainmenu, submenu from srm_menu_access ma, srm_menu mu
															where mu.mnucode = ma.mnucode and (ma.SUPCODE = '".$resvalue."' or ma.ENTSRNO = '".$resvalue."')
															order by ma.mnucode Asc", "Centra", 'TCS');
				for($ij = 0; $ij < count($sql_menuaccess); $ij++)
				{
					$mainmenu_access[] = $sql_menuaccess[$ij][1];
					$submenu_access[] = $sql_menuaccess[$ij][2];
				}
				$_SESSION['tcs_mainmenu_access'] = $mainmenu_access;
				$_SESSION['tcs_submenu_access'] = $submenu_access;
				$rights = find_user_rights(strtoupper($fn_selected_section_group), $empcode[0]['EMPSRNO']);

				if($rights == 0) {
					$redirect_url = 'logout.php';
					$json = json_encode(array('type' => 'error', "info" => $redirect_url, "msg" => "You dont have access rights to view this"));
				} else {
					// Mr. AK Sir Approvals - Directly load from Login
					if($fn_username == 3000000 and strtoupper($fn_selected_section_group) == 'APPROVAL DESK') {
						// $redirect_url = "waiting_mdapproval_reports.php"; // Correct url
						$redirect_url = "home.php"; // wrong url
					}
					// Mr. AK Sir Approvals - Directly load from Login
					$userip = array("9938358" => "172.16.50.5", // KS Sir - MD
									"2000000" => "172.16.50.6", // PS Madam - MD
									"3000000" => "", 			// AK Sir - MD

									"2444001" => "172.16.48.12", // RDTM - Sr. GM
									"2001002" => "172.16.52.11", // Ganesh - GM
									"1986888" => "172.16.50.2", // Kumaran - GM
									"1062001" => "172.16.52.33", // NSM - GM

									"1118001" => "172.16.51.11", // PKN - Accounts HOD
									"1389888" => "172.16.50.13", // Senthil - Admin HOD
									"1112001" => "172.16.48.30", // UC Muthukumaar - Admin HOD
									"1228001" => "172.16.52.5", // SP Saravanan - IT HOD
									"1340005" => "172.16.48.43", // Venkat Durairaj - Sales HOD
									"1384004" => "172.16.50.21", // Karthikeyan - HW HOD
									"1366003" => "172.16.48.72", // Mohan - AK Sir PA
									"1367002" => "172.16.51.12", // Narayana Moorthy - S-Team HOD
									"6635888" => "172.16.9.152" // Chandra Sekaran - Designing HOD
								   );

					if (array_key_exists($response1[0]['USRCODE'], $userip)) {
						$_SESSION['tcs_userip'] = UserIPAddress($response1[0]['USRCODE'], $currentip);
					}

					if($response[0]['EMPSRNO'] == 'Y') {
						$redirect_url = 'index.php';
						$json = json_encode(array('type' => 'success', "info" => $redirect_url, "msg" => "Please Wait!!!"));
					}
					else {
						$json = json_encode(array('type' => 'success', "info" => $redirect_url, "msg" => "Please Wait!!!"));
					}
				}
			}
			else
			{
				$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!"));
			}
		}
		else
		{
			$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!"));
		}
	}
}
catch(Exception $e) {
	$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Invalid Access Details. Kindly try again!!!"));
}

/* Output header */
// header('Content-type: application/json');
die($json);
}
// *** Sign In / Login Function - TEST ***


// *** Save Request Entry ***
if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_POST['function'] == 'request_entry') {
try{
	//echo "!=".$hidrequest_value."!=".$txtrequest_value."!=CA1ME"; exit;
	if($hidrequest_value != $txtrequest_value) {
		$json = json_encode(array('type' => 'error', "info" => 'ttlfailure', "msg" => "Failed in Request Creation. Kindly try again!"));
	} else {
		// echo "CA2ME";
		/* // Detail Content generate in a txt file
		$srno = '4001000';
		$description = $_REQUEST['FCKeditor1'];
		$lpdyear = $current_year[0]['PORYEAR'];
		$txt_srcfilename = "apd_".$lpdyear."_".$srno."_1.txt";

		// Dynamic folder creation
		echo "<br>**".$yrdir = $current_year[0]['PORYEAR']."/";
		echo "<br>**".$yrfolder_exists = is_dir($yrdir);
		echo "<br>**".ftp_mkdir($ftp_conn, $yrdir)."**";
		if($yrfolder_exists) { }
		else {
			if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
		}
		echo "<br>**".$mndir = $current_year[0]['PORYEAR']."/".$srno."/";
		echo "<br>**".$mnfolder_exists = is_dir($mndir);
		echo "<br>**".ftp_mkdir($ftp_conn, $mndir)."**";
		if($mnfolder_exists) { }
		else {
			if(ftp_mkdir($ftp_conn, $mndir)) { } else { }
		}
		// Dynamic folder creation

		$local_file = "../uploads/text_approval_source/".$txt_srcfilename;
		$myfile = fopen($local_file, "w");
		fwrite($myfile, $description);
		fclose($myfile);

		// echo "<br>++".$server_file = 'approval_desk/text_approval_source/'.$lpdyear.'/'.$srno."/".$txt_srcfilename;
		$server_file = 'approval_desk/text_approval_source/'.$lpdyear.'/'.$txt_srcfilename;
		echo "<br>%%".$ftp_conn;
		echo "<br>$$".$server_file;
		echo "<br>##".$local_file;
		if ((!$conn_id) || (!$login_result)) {
			$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
			if (ftp_put($ftp_conn, $server_file, $local_file, FTP_ASCII)) {
			 echo "successfully uploaded $file\n";
			} else {
			 echo "There was a problem while uploading $file\n";
			}

			echo "<br>==".$upload."==";
			// unlink($local_file);
		}
		// Detail Content generate in a txt file
		exit; */

	$sysip = $_SERVER['REMOTE_ADDR'];
	$exapusr1 = explode("~~", $hid_appuser);
	$exapusr1 = array_reverse($exapusr1);
	$maxarqpcod = select_query_json("Select nvl(Max(ARQPCOD),0)+1 MAXARQPCOD
											From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$slt_topcore, "Centra", 'TEST');

	//echo "+++++++#Select nvl(Max(ARQPCOD),0)+1 MAXARQPCOD From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$slt_topcore"";
		//	echo "Select nvl(Max(ARQPCOD),0)+1 MAXARQPCOD	From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = '".$slt_topcore."'", 'Centra', 'TEST';
											// Get the Last record + 1 from APPROVAL_REQUEST
	/* echo "Select nvl(Max(ARQPCOD),0)+1 MAXARQPCOD
											From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$slt_topcore;
	print_r($txt_brnvalue); */
	for($keybrn = 0; $keybrn < count($slt_brnch); $keybrn++) { // branch for loop
		// echo "--".$txt_brnvalue[$keybrn]."--".$keybrn."--";
		// if($txt_brnvalue[$keybrn] != '') {
			// echo "++".$txt_brnvalue[$keybrn]."++".$keybrn."++";
		if(count($txt_brnvalue) > 1 && $txt_brnvalue[$keybrn] != '') {
			$txtrequest_value = $txt_brnvalue[$keybrn];
		}
		$slt_branch = $slt_brnch[$keybrn];
		if($slt_branch == 888) { $slt_branch = '100'; }
		$topcore = $slt_topcore_name;

		$slt_targetnos_1 = explode("||", $slt_targetnos);
		$slt_targetnos_2 = explode("!!", $slt_targetnos_1[1]);
		$slt_department_asset = $slt_targetnos_2[0];

		$maxarqcode = select_query_json("Select nvl(Max(ARQCODE),0)+1 maxarqcode, nvl(Max(ARQSRNO),1) maxarqsrno
												From APPROVAL_REQUEST WHERE ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = 1 and ATCCODE = ".$slt_topcore, "Centra", 'TEST');

		// echo "##".$apusr."##".$apfrwrdusr."##<br>";
		$txtfrom_date1 = strtotime($txtfrom_date);
		$txtfrom_date2 = strtoupper(date('d-M-Y h:i:s A', $txtfrom_date1));
		// $txtfrom_date2 = strtoupper(date('Y-M-d', $txtfrom_date1));
		$txtto_date1 = strtotime($txtto_date);
		$txtto_date2 = strtoupper(date('d-M-Y h:i:s A', $txtto_date1));
		// $txtto_date2 = strtoupper(date('Y-M-d', $txtto_date1));
		// $impldue_date1 = strtoupper(date('Y-M-d', strtotime($impldue_date)));
		$impldue_date1 = $impldue_date;

		$currentdate = strtoupper(date('d-M-Y h:i:s A'));
		// $currentdate = strtoupper(date('Y-M-d'));
		$currentdate1 = strtoupper(date('d-m-Y'));
		$currenttime = strtoupper(date('H:i A'));
		$currenttime1 = strtoupper(date('h:i A'));
		// echo "!!".$apusr."!!".$apfrwrdusr."!!<br>";

		switch($slt_topcore)
		{
			case 1:
					$startwith = 1;
					break;
			case 2:
					$startwith = 2;
					break;
			case 3:
					$startwith = 3;
					break;
			case 4:
					$startwith = 4;
					break;
			case 5:
					$startwith = 1;
					break;
			case 6:
					$startwith = 1;
					break;
			case 7:
					$startwith = 1;
					break;
			default:
					$startwith = 1;
					break;
		}
		// echo "**".$apusr."**".$apfrwrdusr."**<br>";

		if($txtdue_date == '')
			$txtdue_date = $currentdate;
		$srno = $startwith.str_pad($maxarqcode[0]['MAXARQCODE'], 6, '0', STR_PAD_LEFT);
		$noofattachment = 0; $attch = 0;
		if($_FILES['txt_submission_fieldimpl']['name'][0] != '') {
			$assign=$_FILES['txt_submission_fieldimpl']['name'];
			$noofattachment += count($_FILES['txt_submission_fieldimpl']['name']);
		}

		/* if($_FILES['txt_submission_othersupdocs']['name'][0] != '') {
			$assign0=$_FILES['txt_submission_othersupdocs']['name'];
			$noofattachment += count($_FILES['txt_submission_othersupdocs']['name']);
		} */

		if($_FILES['txt_submission_quotations']['name'][0] != '') {
			$assign1=$_FILES['txt_submission_quotations']['name'];
			$noofattachment += count($_FILES['txt_submission_quotations']['name']);
		}

		if($_FILES['txt_submission_clrphoto']['name'][0] != '') {
			$assign2=$_FILES['txt_submission_clrphoto']['name'];
			$noofattachment += count($_FILES['txt_submission_clrphoto']['name']);
		}

		/* if($_FILES['txt_submission_last_approval']['name'][0] != '') {
			$assign3=$_FILES['txt_submission_last_approval']['name'];
			$noofattachment += count($_FILES['txt_submission_last_approval']['name']);
		}

		if($_FILES['txt_submission_artwork']['name'][0] != '') {
			$assign4=$_FILES['txt_submission_artwork']['name'];
			$noofattachment += count($_FILES['txt_submission_artwork']['name']);
		} */

		$apuser = explode("~~", $hid_appuser);
		for($apusri = 0; $apusri < count($apuser)-1; $apusri++)
		{
			$apusr = $apuser[$apusri];
			$apfrwrdusr = $apuser[0];
		}
		// echo "@@".$apusr."@@".$apfrwrdusr."@@<br>";

		$emp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apusr, "Centra", 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($emp[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}
		$todesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$emp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
		$tosection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$emp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section

		$frwrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$apfrwrdusr, "Centra", 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($frwrdemp[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}
		$frdesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$frwrdemp[0]['DESCODE'], "Centra", 'TCS'); // Req.To user designation
		$frsection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$frwrdemp[0]['ESECODE'], "Centra", 'TCS'); // Req.To user section

		$bywrdemp = select_query_json("select * from employee_office emp, employee_salary sal where emp.empsrno = sal.empsrno and emp.empcode = ".$_SESSION['tcs_user'], "Centra", 'TCS');
		$empdes = "designation"; $empsec = "empsection";
		if($bywrdemp[0]['PAYCOMPANY'] == 2) {
			$empdes = "new_designation"; $empsec = "new_empsection";
		}
		$bydesignation = select_query_json("Select DESNAME From ".$empdes." where DESCODE = ".$_SESSION['tcs_descode'], "Centra", 'TCS'); // Req.By user designation
		$bysection = select_query_json("Select ESENAME From ".$empsec." where deleted = 'N' and ESECODE = ".$_SESSION['tcs_esecode'], "Centra", 'TCS'); // Req.By user section

		// if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
			$subcore_name = select_query_json("select CORNAME from empcore_section where DELETED = 'N' and ESECODE = ".$slt_subcore, "Centra", 'TCS'); // Sub Core Name
			// echo "--select CORNAME from empcore_section where DELETED = 'N' and ESECODE = ".$slt_subcore;
		/* } else {
			$subcore_name = select_query_json("select CORNAME from empcore_section where DELETED = 'N' and CORCODE = ".$slt_subcore, "Centra", 'TCS'); // Sub Core Name
			// echo "++select CORNAME from empcore_section where DELETED = 'N' and CORCODE = ".$slt_subcore;
		} */
		// echo "**".$slt_submission."**"; print_r($subcore_name); exit;

		if($txtrequest_value == '')
			$txtrequest_value = 0;

		/* Query for find the target balance */
		$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal,
														sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease
													from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
													where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode and
														brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset." and
														non.PTNUMB=".$slt_targetno."
													group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		if(count($target_balance) == '') {
			$target_balance = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch, sum(TARVALU) ReqVal, sum(PTVALUE) PlanVal,
														sum(PTORDER) OrderVal, sum(PTVALUE- PTORDER) balrelease
													from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
													where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode and
														brn.brncode=".$slt_branch." and dep.depcode=".$slt_department_asset." and non.PTNUMB=".$slt_targetno."
													group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		}

		$expname = select_query_json("select distinct round(tarnumb) tarnumb, ( select distinct decode(nvl(tar.ptdesc,'-'),'-',dep.depname,tar.ptdesc) Depname
												from non_purchase_target tar, department_asset Dep where tar.depcode=dep.depcode and tar.ptnumb=bpl.tarnumb and dep.depcode=bpl.depcode
												and tar.brncode=bpl.brncode) Depname
											from budget_planner_branch bpl
											where depcode=".$slt_department_asset." and brncode=".$slt_branch." and tarnumb=".$slt_targetno."
											order by Depname", "Centra", 'TCS');

		$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
													from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
													where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode and
														brn.brncode=".$slt_branch." and trunc(sysdate) between trunc(ptfdate) and trunc(pttdate) and dep.depcode=".$slt_department_asset." and
														non.PTNUMB=".$slt_targetno."
													group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		if(count($sql_targetno) == '') {
			$sql_targetno = select_query_json("select PTNUMB Tarnumber, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5) branch
														from non_purchase_target non, Budget_planner_branch Bpl, department_asset dep, branch brn
														where bpl.depcode=non.depcode and bpl.brncode=non.brncode and non.ptnumb=bpl.tarnumb and non.depcode=dep.depcode and non.brncode=brn.brncode
															and brn.brncode=".$slt_branch." and dep.depcode=".$slt_department_asset." and non.PTNUMB=".$slt_targetno."
														group by PTNUMB, dep.depcode, dep.depname, brn.brncode, substr(brn.nicname,3,5)", "Centra", 'TCS');
		}
		/* Query for find the target balance */

		$insert_budmode = 0;
		/* $sql_reqexist = select_query_json("select * from APPROVAL_request
													where ARQYEAR = '".$current_year[0]['PORYEAR']."' and ARQSRNO = '1' and ATYCODE = '".$slt_submission."' and ATMCODE = '".$slt_subtype."'
														and APMCODE = '".$slt_approval_listings."' and ATCCODE = '".$slt_topcore."' and deleted = 'N' and ADDUSER = ".$_SESSION['tcs_empsrno']."
														AND APPRDET = '".strtoupper(str_replace("'", "", $txtdetails))."'
														and trunc(ADDDATE) = TO_DATE('".strtoupper(date('d/M/Y'))."','dd/mon/yyyy')", "Centra", 'TCS'); */
		// if(count($sql_reqexist) <= 0) {
			$apprno = strtoupper($topcore.' / '.$subcore_name[0]['CORNAME'].' '.$srno.' / '.$currentdate1.' / '.substr($srno, -4).' / '.$currenttime1);
			
			echo 'this is test'.$topcore.' / '.$subcore_name[0]['CORNAME'].' '.$srno.' / '.$currentdate1.' / '.substr($srno, -4).' / '.$currenttime1;
			// Budget Type
			// print_r($mnt_yr_amt); echo "<Br><br>+++++++++++"; print_r($ttl_locks); // exit;
			if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
				if(count($mnt_yr) > 0 && ($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7)) {
					for($cntmntyr = 0; $cntmntyr < count($mnt_yr); $cntmntyr++) {
						$maxarsrno = select_query_json("Select nvl(Max(APRSRNO),0)+1 MXAPRSRNO From approval_budget_planner_temp WHERE APRNUMB = '".$apprno."' ", "Centra", 'TEST');
						$apmnth = explode(",", $mnt_yr[$cntmntyr]);
						$tbl_budmode = "approval_budget_planner_temp";
						$field_budmode = array();
						$field_budmode['APRNUMB'] = $apprno;
						$field_budmode['APRSRNO'] = $maxarsrno[0]['MXAPRSRNO'];
						$field_budmode['APRPRID'] = $mnt_yr[$cntmntyr];
						$field_budmode['APRMNTH'] = get_month($mnt_yr[$cntmntyr]);
						$field_budmode['ADDUSER'] = $_SESSION['tcs_usrcode'];
						$field_budmode['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
						$field_budmode['DELETED'] = 'N'; // Y - Yes; N - No;
						$field_budmode['APPMNTH'] = $apmnth[0];
						$field_budmode['APPYEAR'] = $apmnth[1];
						$field_budmode['TARNUMB'] = $slt_targetno;
						$field_budmode['APRYEAR'] = $hidapryear;
						$field_budmode['BRNCODE'] = $target_balance[0]['BRNCODE'];
						$field_budmode['APPMODE'] = 'N';
						$field_budmode['EXPSRNO'] = $slt_core_department;
						$field_budmode['EXISTVL'] = 0;
						$field_budmode['USEDVAL'] = $slt_submission;
						$field_budmode['DEPCODE'] = $slt_department_asset;
						$field_budmode['ACCVRFY'] = 0; // 0 - NOT VERIFY BY PKN - ACCOUNTS / 1 - VERIFY BY PKN - ACCOUNTS
						$field_budmode['TMTARNO'] = 0; // TEMP. TARGETNO FIELD.. 0 - NOT UPDATE THE TARGET NO (ORIGINAL TARGET NO) / > 0 THIS IS THE EXISTING TARGET NO, NEW TARGET NO IS IN TARGETNUMB
						if($slt_submission == 1) {
							$field_budmode['APPRVAL'] = floor($mnt_yr_amt[$cntmntyr]);
							// $field_budmode['RESVALU'] = ($ttl_locks[$cntmntyr] - $mnt_yr_amt[$cntmntyr]);
							$field_budmode['RESVALU'] = 0;
							$field_budmode['EXTVALU'] = 0;
							$field_budmode['BUDMODE'] = 'F';
						} elseif($slt_submission == 6) {
							$field_budmode['APPRVAL'] = floor($mnt_yr_amt[$cntmntyr]);
							$field_budmode['RESVALU'] = floor($mnt_yr_amt[$cntmntyr]);
							$field_budmode['EXTVALU'] = 0;
							$field_budmode['BUDMODE'] = 'R';
						} elseif($slt_submission == 7) {
							$field_budmode['APPRVAL'] = floor($mnt_yr_amt[$cntmntyr]);
							$field_budmode['RESVALU'] = 0;
							$field_budmode['EXTVALU'] = floor($mnt_yr_amt[$cntmntyr]);
							$field_budmode['BUDMODE'] = 'E';
						}
						print_r($field_budmode); echo "##<br>";
						$insert_budmode = insert_dbquery($field_budmode, $tbl_budmode);
						// exit;
					}
				} else {
					$maxarsrno = select_query_json("Select nvl(Max(APRSRNO),0)+1 MXAPRSRNO From approval_budget_planner_temp WHERE APRNUMB = '".$apprno."' ", "Centra", 'TEST');
					$mnt_yr_cntmntyr = date("m,Y");
					$mnt_yr_amt_cntmntyr = $txtrequest_value;
					$apmnth = explode(",", $mnt_yr_cntmntyr);
					$tbl_budmode = "approval_budget_planner_temp";
					$field_budmode = array();
					$field_budmode['APRNUMB'] = $apprno;
					$field_budmode['APRSRNO'] = $maxarsrno[0]['MXAPRSRNO'];
					$field_budmode['APRPRID'] = $mnt_yr_cntmntyr;
					$field_budmode['APRMNTH'] = get_month($mnt_yr_cntmntyr);
					$field_budmode['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$field_budmode['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$field_budmode['DELETED'] = 'N'; // Y - Yes; N - No;
					$field_budmode['APPMNTH'] = $apmnth[0];
					$field_budmode['APPYEAR'] = $apmnth[1];
					$field_budmode['TARNUMB'] = $slt_targetno;
					$field_budmode['APRYEAR'] = $current_year[0]['PORYEAR'];
					$field_budmode['BRNCODE'] = $target_balance[0]['BRNCODE'];
					$field_budmode['APPMODE'] = 'N';
					$field_budmode['EXPSRNO'] = $slt_core_department;
					$field_budmode['EXISTVL'] = 0;
					$field_budmode['USEDVAL'] = $slt_submission;
					$field_budmode['DEPCODE'] = $slt_department_asset;
					$field_budmode['ACCVRFY'] = 0; // 0 - NOT VERIFY BY PKN - ACCOUNTS / 1 - VERIFY BY PKN - ACCOUNTS
					$field_budmode['TMTARNO'] = 0; // TEMP. TARGETNO FIELD.. 0 - NOT UPDATE THE TARGET NO (ORIGINAL TARGET NO) / > 0 THIS IS THE EXISTING TARGET NO, NEW TARGET NO IS IN TARGETNUMB
					if($slt_submission == 1) {
						$field_budmode['APPRVAL'] = floor($mnt_yr_amt_cntmntyr);
						$field_budmode['RESVALU'] = 0;
						$field_budmode['EXTVALU'] = 0;
						$field_budmode['BUDMODE'] = 'F';
					} elseif($slt_submission == 6) {
						$field_budmode['APPRVAL'] = floor($mnt_yr_amt_cntmntyr);
						$field_budmode['RESVALU'] = floor($mnt_yr_amt_cntmntyr);
						$field_budmode['EXTVALU'] = 0;
						$field_budmode['BUDMODE'] = 'R';
					} elseif($slt_submission == 7) {
						$field_budmode['APPRVAL'] = floor($mnt_yr_amt_cntmntyr);
						$field_budmode['RESVALU'] = 0;
						$field_budmode['EXTVALU'] = floor($mnt_yr_amt_cntmntyr);
						$field_budmode['BUDMODE'] = 'E';
					}
					print_r($field_budmode); echo "@@<br>";
					$insert_budmode = insert_dbquery($field_budmode, $tbl_budmode);
				}
			} else {
				$insert_budmode = 1;
			}
			// Budget Type
			// exit;
			// $insert_budmode = 1;
			// echo "****".$insert_budmode."+++".$hid_newentry."---".$slt_submission."***".$insert_budmode."***"; print_r($hid_appuser); // slt_subcore

			$amrsrno = 0;
			if($insert_budmode) {
				// Add MD User Dynamically
				if($hid_newentry == 1) {
					$exapusr = explode("~~", $hid_appuser);
					$exapusr = array_reverse($exapusr);
					for($exapusri = 0; $exapusri < count($exapusr); $exapusri++) {
						$sql_user = select_query_json("select emp.EMPSRNO, emp.EMPCODE, emp.EMPCODE||' - '||emp.EMPNAME empname, emp.DESCODE, emp.ESECODE, sec.ESENAME, des.DESNAME
																from employee_office emp, empsection sec, designation des
																where emp.DESCODE = des.DESCODE and emp.ESECODE = sec.ESECODE and emp.empcode = ".$exapusr[$exapusri]."", "Centra", 'TCS');
						if(count($sql_user) > 0) {
							$amrsrno++;
							// Insert in APPROVAL_MDHIERARCHY Table
							$tbl_mdhier = "APPROVAL_MDHIERARCHY";
							$field_mdhier = array();
							$field_mdhier['APMCODE'] = $hid_apmcd;
							$field_mdhier['AMHSRNO'] = $amrsrno;
							$field_mdhier['APPHEAD'] = $sql_user[0]['EMPCODE'];
							$field_mdhier['APPDESG'] = $sql_user[0]['DESCODE'];
							$field_mdhier['APPDAYS'] = 1;
							$field_mdhier['APPRIOR'] = 'Y';
							$field_mdhier['APPTITL'] = $sql_user[0]['DESNAME'];
							$field_mdhier['VRFYREQ'] = '0';
							$field_mdhier['APRNUMB'] = $apprno;
							// echo "<br><br>"; print_r($field_mdhier); // exit;
							$insert_mdhier = insert_dbquery($field_mdhier, $tbl_mdhier);
						}
					}
				}
				// Add MD User Dynamically
				// exit;

				// Insert in APPROVAL_REQUEST Table
				$tbl_appreq = "APPROVAL_REQUEST";
				$field_appreq = array();
				$field_appreq['ARQPCOD'] = $maxarqpcod[0]['MAXARQPCOD'];
				$field_appreq['ARQCODE'] = $maxarqcode[0]['MAXARQCODE'];
				$field_appreq['ARQYEAR'] = $current_year[0]['PORYEAR'];
				$field_appreq['ARQSRNO'] = 1;
				$field_appreq['ATYCODE'] = $slt_submission;
				$field_appreq['ATMCODE'] = $slt_subtype;
				$field_appreq['APMCODE'] = $slt_approval_listings;
				if($txt_dynamic_subject2 != '') {
					$field_appreq['DYNSUBJ'] = " ".strtoupper($txt_dynamic_subject1)." - ".strtoupper($txt_dynamic_subject2)." - ";
				} else {
					$field_appreq['DYNSUBJ'] = " ".strtoupper($txt_dynamic_subject1)." - ";
				}
				$field_appreq['TXTSUBJ'] = " ".strtoupper($txt_dynsubject);
				$field_appreq['ATCCODE'] = $slt_topcore;
				$field_appreq['APPRFOR'] = 1;
				$field_appreq['REQSTTO'] = $txt_kind_attn;

				// Detail Content generate in a txt file
				$description = $_REQUEST['FCKeditor1'];
				$lpdyear = $current_year[0]['PORYEAR'];
				$txt_srcfilename = "apd_".$lpdyear."_".$srno."_1.txt";
				// $txt_srcfilename = "apd_2017-18_4008105_1.txt";

				/* // Dynamic folder creation
				echo "<br>**".$yrdir = $current_year[0]['PORYEAR']."/";
				echo "<br>**".$yrfolder_exists = is_dir($yrdir);
				echo "<br>**".ftp_mkdir($ftp_conn, $yrdir)."**";
				if($yrfolder_exists) { }
				else {
					if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
				}
				echo "<br>**".$mndir = $current_year[0]['PORYEAR']."/".$srno."/";
				echo "<br>**".$mnfolder_exists = is_dir($mndir);
				echo "<br>**".ftp_mkdir($ftp_conn, $mndir)."**";
				if($mnfolder_exists) { }
				else {
					if(ftp_mkdir($ftp_conn, $mndir)) { } else { }
				}
				// Dynamic folder creation */

				$local_file = "uploads/text_approval_source/".$txt_srcfilename;
				$myfile = fopen($local_file, "w");
				fwrite($myfile, $description);
				fclose($myfile);

				$server_file = 'approval_desk/text_approval_source/'.$lpdyear.'/'.$txt_srcfilename;
				if ((!$conn_id) || (!$login_result)) {
					$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
					//unlink($local_file);
				}
				// Detail Content generate in a txt file
				$txtdetails = "1";
				// exit;

				$field_appreq['APPRSUB'] = str_replace("'", "", $lpdyear.'/'.$txt_srcfilename);
				$field_appreq['APPRDET'] = str_replace("'", "", $txtdetails);
				$field_appreq['APPRSFR'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtfrom_date2;
				$field_appreq['APPRSTO'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtto_date2;
				$field_appreq['APPATTN'] = $noofattachment;
				if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) { // From here
					$field_appreq['APRQVAL'] = $txtrequest_value;
					$field_appreq['APPDVAL'] = $txtrequest_value;
					$field_appreq['APPFVAL'] = $txtrequest_value;
				} else { // From here
					$field_appreq['APRQVAL'] = 0;
					$field_appreq['APPDVAL'] = 0;
					$field_appreq['APPFVAL'] = 0;
				} // to here

				if(count($target_balance) > 0) {
					$field_appreq['BRNCODE'] = $target_balance[0]['BRNCODE'];
					$field_appreq['DEPCODE'] = $target_balance[0]['DEPCODE'];
					$field_appreq['TARNUMB'] = $target_balance[0]['TARNUMBER'];
					$field_appreq['TARBALN'] = $target_balance[0]['BALRELEASE'];
					$field_appreq['TARDESC'] = $expname[0]['DEPNAME'];
				} else {
					$field_appreq['BRNCODE'] = $slt_branch;
					$field_appreq['DEPCODE'] = '113';
					$field_appreq['TARNUMB'] = '3838';
					$field_appreq['TARBALN'] = '0';
					$field_appreq['TARDESC'] = 'A/C';
				}

				// $field_appreq['REQSTBY'] = $txt_submission_reqby;
				$field_appreq['REQSTBY'] = $_SESSION['tcs_empsrno'];
				$field_appreq['RQBYDES'] = $_SESSION['tcs_user']." - ".$_SESSION['tcs_empname'];
				$field_appreq['REQDESC'] = $_SESSION['tcs_descode'];
				$field_appreq['REQESEC'] = $_SESSION['tcs_esecode'];
				$field_appreq['REQDESN'] = $bydesignation[0]['DESNAME'];
				$field_appreq['REQESEN'] = $bysection[0]['ESENAME'];
				$field_appreq['REQSTFR'] = $frwrdemp[0]['EMPSRNO'];
				$field_appreq['RQFRDES'] = $frwrdemp[0]['EMPCODE']." - ".$frwrdemp[0]['EMPNAME'];

				$field_appreq['RQFRDSC'] = $frwrdemp[0]['DESCODE'];
				$field_appreq['RQFRESC'] = $frwrdemp[0]['ESECODE'];
				$field_appreq['RQFRDSN'] = $frdesignation[0]['DESNAME'];
				$field_appreq['RQFRESN'] = $frsection[0]['ESENAME'];
				$field_appreq['RQESTTO'] = $emp[0]['EMPSRNO'];
				$field_appreq['RQTODES'] = $emp[0]['EMPCODE']." - ".$emp[0]['EMPNAME'];
				$field_appreq['RQTODSC'] = $emp[0]['DESCODE'];
				$field_appreq['RQTOESC'] = $emp[0]['ESECODE'];
				$field_appreq['RQTODSN'] = $todesignation[0]['DESNAME'];
				$field_appreq['RQTOESN'] = $tosection[0]['ESENAME'];

				$field_appreq['APRNUMB'] = $apprno;
				$field_appreq['APPSTAT'] = 'N'; // N - Normal / Newly Created; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
				$field_appreq['APPFRWD'] = 'F'; // N - Normal / Newly Created; R - Rejected; S - Response; H - Hold; F - Forward; C - Completed; P - Pending; Q - Query;
				$field_appreq['APPINTP'] = 'N'; // Y - Yes; N - No;

				if($frwrdemp[0]['EMPSRNO'] == 2) {
					$field_appreq['INTPEMP'] = '127'; // ALTERNATE FOR MDU BR.MGR
				} elseif($frwrdemp[0]['EMPSRNO'] == 12702) {
					$field_appreq['INTPEMP'] = '1202'; // ALTERNATE FOR TUP BR.MGR
				} elseif($frwrdemp[0]['EMPSRNO'] == 61579) {
				// $field_appreq['INTPEMP'] = 48237; // SARATH Altername for Cost Control Selva Muthu Kumar
					 $field_appreq['INTPEMP'] = 63624; // HARI Altername for Cost Control Selva Muthu Kumar
					// $field_appreq['INTPEMP'] = 76856; // SELVAGANAPATHI Altername for Cost Control Selva Muthu Kumar
				} elseif($frwrdemp[0]['EMPSRNO'] == 34593) {
					$field_appreq['INTPEMP'] = '1169'; // HW Karthik alternate for Saravanakumar
				} elseif($frwrdemp[0]['EMPSRNO'] == 188) { // Ashok - S-team
					$field_appreq['INTPEMP'] = 62762; // Ramakrishnan - S-team
				} elseif($frwrdemp[0]['EMPSRNO'] == 14180) { // Manoharan - Project-team
				$field_appreq['INTPEMP'] = 82237; // Dhinesh Khanna - Project-team alternate for Manoharan
			}
				else {
					$field_appreq['INTPEMP'] = 0;
				}

				$field_appreq['INTPDES'] = 0;
				$field_appreq['INTPDSC'] = 0; // This 1 is indicate us, this is coming from gpanel home screen; This 0 is indicate us, this is coming from direct approval screen; This 2 is indicate us, this is coming from print screen approval page
				$field_appreq['INTPESC'] = 0; // This 1 is indicate us, this approval is read by approval user
				if($sql_targetno[0]['TARNUMBER'] != '') {
					$field_appreq['INTPDSN'] = $sql_targetno[0]['TARNUMBER'];
				} else {
					$field_appreq['INTPDSN'] = 0;
				}
				$field_appreq['INTPESN'] = '-';

				$field_appreq['INTPAPR'] = '-';
				$field_appreq['INTSUGG'] = '-';
				$field_appreq['INTPFRD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$field_appreq['INTPTOD'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$field_appreq['ADDUSER'] = $_SESSION['tcs_empsrno']; // $_SESSION['tcs_usrcode'];
				$field_appreq['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$field_appreq['EDTUSER'] = '';
				$field_appreq['EDTDATE'] = '';
				$field_appreq['DELETED'] = 'N'; // Y - Yes; N - No;
				//$field_appreq['DELUSER'] = '';

				$userid = explode(" - ", $txt_submission_reqby);
				$users = select_query_json("select empsrno from employee_office where empcode = '".$userid[0]."'", "Centra", 'TCS');
				if($users[0]['EMPSRNO'] == '') {
					$field_appreq['DELUSER'] = $_SESSION['tcs_empsrno'];
				} else {
					$field_appreq['DELUSER'] = $users[0]['EMPSRNO'];
				}

				$field_appreq['DELDATE'] = '';
				$field_appreq['PRJPRCS'] = $slt_project_type;

				$field_appreq['APRCODE'] = $slt_project;
				$field_appreq['APRHURS'] = $txtnoofhours;
				$field_appreq['APRDAYS'] = $txtnoofdays;
				$field_appreq['APRDUED'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txtdue_date;
				$field_appreq['APPRMRK'] = '';
				$field_appreq['APRTITL'] = $slt_title;

				$field_appreq['FINSTAT'] = 'N';
				$field_appreq['FINUSER'] = '';
				$field_appreq['FINCMNT'] = '';
				$field_appreq['FINDATE'] = '';

				// Current Year Record
				$cur_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal,
														decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per
													from budget_planner_branch bpl, non_sales_target non
													where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($cur,-2)."'
														and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
													group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL
													order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS');

				// Last Year Record
				$last_year = select_query_json("select bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL, sum(PURTVAL+EXTRVAL+RESRVAL) BudgetVal,
														decode(non.SALESVAL,0,0, round(sum(PURTVAL+EXTRVAL+RESRVAL)/non.SALESVAL*100,2)) Per
													from budget_planner_branch bpl, non_sales_target non
													where bpl.brncode=non.brncode and bpl.taryear+1=substr(non.salyear,3,2) and bpl.tarmont=non.SALMONT and bpl.taryear='".substr($lat,-2)."'
														and bpl.tarmont='".$cur_mon."' and bpl.brncode=".$target_balance[0]['BRNCODE']." and bpl.depcode=".$target_balance[0]['DEPCODE']."
													group by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont, non.salyear, non.salmont, non.SALESVAL
													order by bpl.brncode, bpl.depcode, bpl.taryear, bpl.tarmont", "Centra", 'TCS');

				$field_appreq['TARVLCY'] = $cur_year[0]['BUDGETVAL'];
				$field_appreq['TARVLLY'] = $last_year[0]['BUDGETVAL'];
				$field_appreq['EXPNAME'] = $expname[0]['DEPNAME'];
				$field_appreq['TARPRCY'] = $cur_year[0]['PER'];
				$field_appreq['TARPRLY'] = $last_year[0]['PER'];
				$field_appreq['USRSYIP'] = $sysip;
				$field_appreq['BUDTYPE'] = $slt_submission;
				if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
					$field_appreq['BUDCODE'] = $slt_budgetmode;
				} else {
					$field_appreq['BUDCODE'] = '1';
				}

				// 27-12-2016 AK Sir Instruction
				$field_appreq['IMDUEDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$impldue_date1;
				$field_appreq['IMUSRCD'] = '';
				$field_appreq['IMSTATS'] = 'N';
				$field_appreq['IMFINDT'] = '';
				$field_appreq['IMUSRIP'] = $srno;
				$field_appreq['TYPMODE'] = 'AP';
				$field_appreq['SUBCORE'] = $slt_subcore;
				$field_appreq['NXLVLUS'] = 1;
				$field_appreq['PRICODE'] = 3; // $slt_priority;

				$txtsup = explode(" - ", $txt_suppliercode);
				if(is_numeric($txtsup[0])) {
					$supcd = $txtsup[0];
					$supnm = $txtsup[1];
				} else {
					$supcd = '';
					$supnm = $txt_suppliercode;
				}
				$field_appreq['SUPCODE'] = $supcd;
				$field_appreq['SUPNAME'] = strtoupper($supnm);
				$field_appreq['SUPCONT'] = $txt_supplier_contactno;
				// 27-12-2016 AK Sir Instruction

				// 23-08-2017 AK Sir Instruction
				$rqby = explode(" - ", $txt_submission_reqby);
				$rqbyusr = $rqby[0];

				$altusr = explode(" - ", $txt_alternate_user);
				$altrusr = $altusr[0];

				$field_appreq['PRODWIS'] = $txt_prodwise_budget;
				$field_appreq['RESPUSR'] = $rqbyusr;
				$field_appreq['ALTRUSR'] = $altrusr;
				$field_appreq['RELAPPR'] = strtoupper($txt_related_approvals);
				$agnsapr = explode(" ( ", $txt_against_approval);
				$field_appreq['AGNSAPR'] = strtoupper($agnsapr[0]);
				$field_appreq['AGEXPDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$txt_agreement_expiry;
				$field_appreq['AGADVAM'] = strtoupper($txt_agreement_advance);

				$field_appreq['ORGRECV'] = 'N';
				$field_appreq['ORGRVUS'] = '';
				$field_appreq['ORGRVDT'] = '';
				$field_appreq['ORGRVDC'] = '';
				// 23-08-2017 AK Sir Instruction

				$field_appreq['CNVRMOD'] = strtoupper($slt_convertmode);
				$field_appreq['APPTYPE'] = strtoupper($slt_apptype);
				$field_appreq['PURHEAD'] = $txt_purhead;
				// $field_appreq['ADVAMNT'] = $txt_adv_amount;
				$exp_wrkinusr = explode(" - ", $txt_workintiator);
				$field_appreq['WRKINUSR'] = $exp_wrkinusr[0];
				if($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) {
					$field_appreq['BDPLANR'] = $slt_fixbudget_planner;
				} else {
					$field_appreq['BDPLANR'] = '';
				}

				// Attachments
				$field_appreq['RMQUOTS'] = strtoupper($txt_submission_quotations_remarks);
				$field_appreq['RMBDAPR'] = strtoupper($txt_submission_fieldimpl_remarks);
				$field_appreq['RMCLRPT'] = strtoupper($txt_submission_clrphoto_remarks);
				$field_appreq['RMARTWK'] = strtoupper($txt_submission_artwork_remarks);
				$field_appreq['RMCONAR'] = strtoupper($txt_submission_othersupdocs_remarks);

				$field_appreq['WARQUAR'] = strtoupper($txt_warranty_guarantee);
				$field_appreq['CRCLSTK'] = strtoupper($txt_cur_clos_stock);
				$field_appreq['PAYPERC'] = strtoupper($txt_advpay_comperc);
				$field_appreq['FNTARDT'] = strtoupper($txt_workfin_targetdt);
				// Attachments

				print_r($field_appreq); echo "<br>"; // exit;
				$sql_reqst_exist = select_query_json("select count(ARQCODE) CNTARQCODE from APPROVAL_REQUEST
															where arqyear = '".$current_year[0]['PORYEAR']."' and arqcode = ".$maxarqcode[0]['MAXARQCODE']." and ATCCODE = ".$slt_topcore."", "Centra", 'TEST');
				//renewal test
				echo "select count(ARQCODE) CNTARQCODE from APPROVAL_REQUEST
								where arqyear = '".$current_year[0]['PORYEAR']."' and arqcode = ".$maxarqcode[0]['MAXARQCODE']." and ATCCODE = ".$slt_topcore." ";

				if($sql_reqst_exist[0]['CNTARQCODE'] <= 0) {
					 $insert_appreq = insert_dbquery($field_appreq, $tbl_appreq);
					 echo 'done';
				}

				else {
					 $sl_dlt = delete_dbquery("delete From APPROVAL_mdhierarchy WHERE aprnumb like '".$apprno."' and apmcode = ".$hid_apmcd."", "Centra", 'TCS');
				}
				echo "!!!".$insert_appreq."@@@";
				// Insert in APPROVAL_REQUEST Table 
				// echo "<br>***";
				// print_r($advarray);
				// echo "<br>***".$slt_approval_listings."***".count($advance_array)."****"; // exit;

				///// REMOVE THIS /////
				if(($slt_submission == 1 or $slt_submission == 6 or $slt_submission == 7) and $insert_appreq != 1) {
					$tbl_appplan = "approval_budget_planner_temp";
					$field_appplan = array();
					$field_appplan['DELETED'] = 'Y';
					$field_appplan['DELUSER'] = $_SESSION['tcs_usrcode'];
					$field_appplan['DELDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$where_appplan = " APRNUMB = '".$apprno."' and ADDUSER = '".$_SESSION['tcs_usrcode']."' ";
					$insert_appplan = update_dbquery($field_appplan, $tbl_appplan, $where_appplan);
				}
				///// REMOVE THIS /////
			// } exist appr


			$txttag_data[] = 'APPROVAL_NO';
			$txttag_term[] = $apprno;
			$txttag_process[] = $apprno;

			if($insert_appreq == 1)
			{
				for($tem = 0; $tem < sizeof($txttag_data); $tem++)
				{
					$maxtagsrno = select_query_json("Select nvl(Max(TAGSRNO),0)+1 maxarqcode From approval_tags
															WHERE APRNUMB = '".$apprno."' and EMPCODE = ".$_SESSION['tcs_usrcode'], "Centra", 'TEST');
					$tbl_tags = "approval_tags";
					$field_tags = array();
				 	$field_tags['APRNUMB'] = $apprno;
					$field_tags['TAGSRNO'] = $maxtagsrno[0]['MAXARQCODE'];
					$field_tags['EMPCODE'] = $_SESSION['tcs_usrcode'];
					$field_tags['TAGCOLR'] = 'RED';
					$field_tags['TAGSDET'] = strtoupper($txttag_process[$tem]);
					$field_tags['TAGDATA'] = strtoupper($txttag_data[$tem]);
					$field_tags['TAGTERM'] = strtoupper($txttag_term[$tem]);
					$field_tags['TAGSTAT'] = 'N';
					$field_tags['ADDUSER'] = $_SESSION['tcs_usrcode'];
					$field_tags['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
					$insert_tags = insert_dbquery($field_tags, $tbl_tags);
				}
			}

			if($insert_appreq == 1 and $_REQUEST['tempidcnt'] !=0)
			{
				$tempid = $_REQUEST['tempidcnt'];
				$sql_temp = select_query_json("select * from approval_general_master where tempid=".$tempid." order by colsrno", "Centra", 'TCS');
				$t_table = "approval_general_detail";
				for($tem = 0; $tem < sizeof($a[1]);$tem++)
				{
					 foreach ($sql_temp as $val) {
					 	if(strtoupper($a[$val['COLSRNO']][$tem]) != '') {
						 	$t_fld=array();
						 	$det=array();
						 	$t_fld['TEMPID']  = (int)$tempid ;
						 	$t_fld['APRNUMB'] = $apprno;
						 	$t_fld['APMCODE'] = $slt_approval_listings ;
						 	$t_fld['ROWSRNO'] = $tem+1 ;
						 	$t_fld['COLSRNO'] = $val['COLSRNO'] ;
							$t_fld['COLDET']  = strtoupper($a[$val['COLSRNO']][$tem]);
						 	$t_insert = insert_dbquery($t_fld,$t_table);
						}
					}
				}
			}

			if($insert_appreq == 1 and ($slt_approval_listings==909 or $slt_approval_listings==1368))
			{
				$g_table = "approval_night_duty";
				for($emp = 0; $emp < sizeof($empname);$emp++)
				{
					$g_fld = array();
					$g_empcode = explode(" - ", $empname[$emp]);

					if($g_empcode[1] == "" and $empsrno[$emp] == '')
					{
						$g_empcode[1] = $g_empcode[0];
						$g_empcode[0] = 0;
					}
					if($empsrno[$emp] == "")
					{
						$empsrno[$emp] = 0;
						$descode[$emp] = 0;
						$esecode[$emp] = 0;
					}

					$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
					$g_fld['APRNUMB'] = $apprno;
					$g_fld['ENTSRNO'] = $emp+1;
					$g_fld['EMPSRNO'] = $empsrno[$emp];
					$g_fld['EMPCODE'] = $g_empcode[0];
					$g_fld['EMPNAME'] = strtoupper($g_empcode[1]);
					$g_fld['BRNCODE'] = $slt_branch;
					$g_fld['ESECODE'] = $esecode[$emp];
					$g_fld['ESENAME'] = strtoupper($CURDEP[$emp]);
					$g_fld['DESCODE'] = $descode[$emp];
					$g_fld['DESNAME'] = strtoupper($CURDES[$emp]);
					$g_fld['WRKDESC'] = strtoupper($CURWORK[$emp]);
					$g_fld['WRKHURS'] = $TOTWORK[$emp];
					$g_insert = insert_dbquery($g_fld,$g_table);
				}
			}

			//echo "<br>||";
		//	echo "insert_appreq = ".$insert_appreq;
			if($insert_appreq == 1 and $slt_approval_listings == 967)
			{
				//echo "<br>!!";
				//echo sizeof($txtesi_no_emp);
				for($emp = 0; $emp < sizeof($txtesi_brncode); $emp++)
				{
					//echo "<br>@@";
					if($txtesi_no_emp[$emp] != '' and $txtesi_value[$emp] != '')
					{

						//echo "<br>##";
							$maxtagsrno = select_query_json("Select nvl(Max(BRDTSRN),0)+1 maxarqcode From approval_branch_detail where APRNUMB = '".$apprno."'","Centra","TEST");

							echo "Select nvl(Max(BRDTSRN),0)+1 maxarqcode From approval_branch_detail where APRNUMB = '".$apprno."'";

							$g_table = "approval_branch_detail";
							$g_fld = array();

							$g_fld['APRNUMB'] = $apprno;
							$g_fld['BRDTSRN'] = $maxtagsrno[0]['MAXARQCODE'];
							$g_fld['BRNCODE'] = $txtesi_brncode[$emp];
							$g_fld['NOFEMPL'] = $txtesi_no_emp[$emp];
							$g_fld['APRAMNT'] = $txtesi_value[$emp];
							//$g_fld['EMPSRNO'] = $empsrno[$emp];
							$g_insert = insert_dbquery($g_fld,$g_table);
							print_r($g_fld);
							//echo "<br>$$";

					}
				}
			}
			if($insert_appreq == 1 and $slt_approval_listings == 659)
			{
				$g_table = "approval_staff_marriage";

				for($emp = 0; $emp < sizeof($empname);$emp++)
				{
					$g_fld = array();
					$g_empcode = explode(" - ", $empname[$emp]);
					$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
					$g_fld['APRNUMB'] = $apprno;
					$g_fld['ENTSRNO'] = $emp+1;
					$g_fld['EMPSRNO'] = $empsrno[$emp];
					$g_fld['EMPCODE'] = $g_empcode[0];
					$g_fld['EMPNAME'] = $g_empcode[1];
					$g_fld['CUREXP']  = $CUREXP[$emp];
					$g_fld['DATEJOIN']= 'dd-Mon-yyyy~~'.date("d-M-Y", strtotime($DATEOFJOIN[$emp]));
					$g_fld['CURBRN']  = $CURBRN[$emp];
					$g_fld['CURDEP']  = $CURDEP[$emp];
					$g_fld['CURDES']  = $CURDES[$emp];
					$g_fld['TRUSTAMT']= $TRUSTAMT[$emp];
					$g_fld['OWNGIFT'] = $OWNGIFT[$emp];
					$g_insert = insert_dbquery($g_fld,$g_table);
				}
			}

			if($insert_appreq == 1 and ($slt_approval_listings == 623 or $slt_approval_listings == 842))
			{
				$g_table = "approval_staff_branch_change";
				for($emp = 0; $emp < sizeof($EMPNAME);$emp++)
				{
					$g_fld = array();
					$g_empcode = explode(" - ", $EMPNAME[$emp]);
					$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
					$g_fld['APRNUMB'] = $apprno;
					$g_fld['ENTSRNO'] = $emp+1;
					$g_fld['EMPSRNO'] = $EMPSRNO[$emp];
					$g_fld['EMPCODE'] = $g_empcode[0];
					$g_fld['EMPNAME'] = $g_empcode[1];
					$g_fld['CUREXP']  = $CUREXP[$emp];
					$g_fld['DATEJOIN']= 'dd-Mon-yyyy~~'.date("d-M-Y", strtotime($DATEOFJOIN[$emp]));
					$g_fld['CURBRN']  = $CURBRN[$emp];
					$g_fld['CURDEP']  = $CURDEP[$emp];
					$g_fld['CURDES']  = $CURDES[$emp];
					$g_fld['NEWBRN'] = $NEWBRN[$emp];
					$g_fld['NEWDEP'] = $NEWDEP[$emp];
					$g_fld['NEWDES'] = $NEWDES[$emp];
					$g_insert = insert_dbquery($g_fld,$g_table);
				}
			}

			if($insert_appreq == 1 and ($slt_approval_listings == 669 or  $slt_approval_listings == 6 or $slt_approval_listings == 829 or $slt_approval_listings == 668 or $slt_approval_listings == 725 or $slt_approval_listings == 712 or $slt_approval_listings == 710 or $slt_approval_listings == 711 or $slt_approval_listings == 1274 or $slt_approval_listings == 1275 or $slt_approval_listings == 1276 or $slt_approval_listings == 1280 or $slt_approval_listings == 1281 ) )
			{
				$g_table = "approval_staff_designation";
				for($emp = 0; $emp < sizeof($EMPNAME);$emp++)
				{
					$g_fld = array();
					$g_empcode = explode(" - ", $EMPNAME[$emp]);
					$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
					$g_fld['APRNUMB'] = $apprno;
					$g_fld['ENTSRNO'] = $emp+1;
					$g_fld['EMPSRNO'] = $EMPSRNO[$emp];
					$g_fld['EMPCODE'] = $g_empcode[0];
					$g_fld['EMPNAME'] = $g_empcode[1];
					$g_fld['CUREXP']  = $CUREXP[$emp];
					$g_fld['DATEJOIN']= 'dd-Mon-yyyy~~'.date("d-M-Y", strtotime($DATEOFJOIN[$emp]));
					$g_fld['CURBRN']  = $CURBRN[$emp];
					$g_fld['CURDEP']  = $CURDEP[$emp];
					$g_fld['CURDES']  = $CURDES[$emp];
					$g_fld['NEWDES']  = $NEWDES[$emp];
					$g_fld['NEWDEPT'] = $NEWDEP[$emp];
					$g_fld['REPORTTO']= $REPORTTO[$emp];
					print_r($g_fld);
					echo "+++++++++".$g_insert = insert_dbquery($g_fld,$g_table);
				}
			}
			exit;

			if($insert_appreq == 1 and ($slt_approval_listings == 8 or $slt_approval_listings == 671 or $slt_approval_listings == 715 or $slt_approval_listings == 714 or $slt_approval_listings == 670 or $slt_approval_listings == 716 or $slt_approval_listings == 958 or $slt_approval_listings == 960))
			{
				$g_table = "approval_staff_department";
				for($emp = 0; $emp < sizeof($EMPNAME);$emp++)
				{
					$g_fld = array();
					$g_empcode = explode(" - ", $EMPNAME[$emp]);
					$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
					$g_fld['APRNUMB'] = $apprno;
					$g_fld['ENTSRNO'] = $emp+1;
					$g_fld['EMPSRNO'] = $EMPSRNO[$emp];
					$g_fld['EMPCODE'] = $g_empcode[0];
					$g_fld['EMPNAME'] = $g_empcode[1];
					$g_fld['CUREXP']  = $CUREXP[$emp];
					$g_fld['DATEJOIN']= 'dd-Mon-yyyy~~'.date("d-M-Y", strtotime($DATEOFJOIN[$emp]));
					$g_fld['CURBRN']  = $CURBRN[$emp];
					$g_fld['CURDEP']  = $CURDEP[$emp];
					$g_fld['CURDES']  = $CURDES[$emp];
					$g_fld['NEWDEP']  = $NEWDEP[$emp];
					$g_fld['NEWDES']  = $NEWDES[$emp];
					$g_fld['REPORTTO']= $REPORTTO[$emp];
					$g_insert = insert_dbquery($g_fld,$g_table);
				}
			}

			if($insert_appreq == 1 and ($slt_approval_listings == 5 or $slt_approval_listings == 844))
			{
				$g_table = "approval_staff_salary_change";
				for($emp = 0; $emp < sizeof($EMPNAME);$emp++)
					{
						$g_fld = array();
						$g_empcode = explode(" - ", $EMPNAME[$emp]);
						$g_fld['APRYEAR'] = $current_year[0]['PORYEAR'];
						$g_fld['APRNUMB'] = $apprno;
						$g_fld['ENTSRNO'] = $emp+1;
						$g_fld['EMPSRNO'] = $EMPSRNO[$emp];
						$g_fld['EMPCODE'] = $g_empcode[0];
						$g_fld['EMPNAME'] = $g_empcode[1];
						$g_fld['CUREXP']  = $CUREXP[$emp];
						$g_fld['DATEJOIN']= 'dd-Mon-yyyy~~'.date("d-M-Y", strtotime($DATEOFJOIN[$emp]));
						$g_fld['CURBRN']  = $CURBRN[$emp];
						$g_fld['CURDEP']  = $CURDEP[$emp];
						$g_fld['CURDES']  = $CURDES[$emp];
						$g_fld['CURBAS']  = $CURBAS[$emp];
						$g_fld['NEWBAS']  = $NEWBAS[$emp];
						$g_fld['INCAMT']  = $INCAMT[$emp];
						$g_insert = insert_dbquery($g_fld,$g_table);
					}

				}

			 //$insert_appreq = 1;
			// echo "||==".$insert_appreq."==".$txtrequest_value."==||";
			if($insert_appreq == 1 and $txtrequest_value > 0) {
				// Product List Adding
				$uploadimg = $current_year[0]['PORYEAR'];
				for($pdi = 0; $pdi < count($txt_prdcode); $pdi++) {
					if($txt_prdcode[$pdi] != '') {
						$prd_cd = explode(" - ", $txt_prdcode[$pdi]);
						$sprd_cd = explode(" - ", $txt_subprdcode[$pdi]);
						$prdhsn = select_query_json("Select distinct PRDCODE, PRDNAME, HSNCODE From product_asset
															WHERE depcode = '".$slt_department_asset."' and deleted = 'N' and PRDCODE = '".$prd_cd."'", "Centra", 'TEST');
						$sub_prdhsn = select_query_json("select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax
							    									from subproduct_asset sub, product_asset prd
																	where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$slt_department_asset."' and sub.deleted = 'N' and prd.deleted = 'N'
																		and (sub.SUBCODE like '".$sprd_cd."') and sub.HSNCODE is not null
															union
																select distinct sub.PRDCODE, prd.prdname, sub.SUBCODE, sub.SUBNAME, sub.HSNCODE,sub.prdtax
							    									from subproduct_asset sub, product_asset prd
																	where sub.PRDCODE = prd.PRDCODE and prd.depcode = '".$slt_department_asset."' and sub.deleted = 'N' and prd.deleted = 'N'
																		and (sub.SUBCODE = '".$sprd_cd."') and sub.prdtax='N'
																	order by SUBCODE, SUBNAME, PRDCODE", "Centra", 'TEST');
						$maxprlstno = select_query_json("Select nvl(Max(PRLSTNO),0)+1 MAXPRLSTNO
																From APPROVAL_PRODUCTLIST
																WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PRLSTYR = '".$current_year[0]['PORYEAR']."' and PRLSTNO = '".$maxprlstno[0]['MAXPRLSTNO']."'", "Centra", 'TEST');
						$tbl_appdet = "APPROVAL_PRODUCTLIST";
						$field_appdet = array();
						$field_appdet['PBDYEAR'] = $current_year[0]['PORYEAR'];
						$field_appdet['PBDCODE'] = $srno;
						$field_appdet['PBDLSNO'] = $maxprlstno[0]['MAXPRLSTNO'];
						$field_appdet['PRLSTYR'] = $current_year[0]['PORYEAR'];
						$field_appdet['PRLSTNO'] = $maxprlstno[0]['MAXPRLSTNO'];

						$field_appdet['PRDCODE'] = $prd_cd[0];
						$field_appdet['PRDNAME'] = strtoupper($prd_cd[1]);
						$field_appdet['PRDSPEC'] = strtoupper($txt_prdspec[$pdi]);
						$field_appdet['SUBCODE'] = $sprd_cd[0];
						$field_appdet['SUBNAME'] = strtoupper($sprd_cd[1]);
						$field_appdet['TOTLQTY'] = $txt_prdqty[$pdi];
						$field_appdet['TOTLVAL'] = 0;

						if($txt_ad_duration[$pdi] != '') {
							$field_appdet['ADURATI'] = $txt_ad_duration[$pdi];
						} else {
							$field_appdet['ADURATI'] = 0;
						}

						if($txt_size_length[$pdi] != '') {
							$field_appdet['ADLENGT'] = $txt_size_length[$pdi];
						} else {
							$field_appdet['ADLENGT'] = 0;
						}

						if($txt_size_width[$pdi] != '') {
							$field_appdet['ADWIDTH'] = $txt_size_width[$pdi];
						} else {
							$field_appdet['ADWIDTH'] = 0;
						}

						if($txt_print_location[$pdi] != '') {
							$field_appdet['ADLOCAT'] = strtoupper($txt_print_location[$pdi]);
						} else {
							$field_appdet['ADLOCAT'] = 0;
						}

						/* $field_appdet['ADURATI'] = $txt_ad_duration[$pdi];
						$field_appdet['ADLENGT'] = $txt_size_length[$pdi];
						$field_appdet['ADWIDTH'] = $txt_size_width[$pdi];
						$field_appdet['ADLOCAT'] = strtoupper($txt_print_location[$pdi]); */ // 1!!!1@@@==1==1180==
						$field_appdet['UNTCODE'] = $txt_unitcode[$pdi];
						$field_appdet['UNTNAME'] = strtoupper($txt_unitname[$pdi]);
						$field_appdet['USESECT'] = $slt_usage_section[$pdi];

						$field_appdet['PRODHSN'] = $prdhsn['HSNCODE'];
						$field_appdet['SPRDHSN'] = $sub_prdhsn['HSNCODE'];

						// Product Image
						if($_FILES['fle_prdimage']['type'][$pdi] == "image/jpeg" or $_FILES['fle_prdimage']['type'][$pdi] == "image/gif" or $_FILES['fle_prdimage']['type'][$pdi] == "image/png" or $_FILES['fle_prdimage']['type'][$pdi] == "application/pdf") {
								$fldimli = find_indicator( $_FILES['fle_prdimage']['type'][$pdi] );
								$imgfile1 = $_FILES['fle_prdimage']['tmp_name'][$pdi];
								if($fldimli == 'i')
								{
									$info = getimagesize($imgfile1);
									$image1 = imagecreatefromjpeg($imgfile1);
									if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
									elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
									elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
									//save it
									imagejpeg($image, $imgfile1, 20);
								}

								switch($_FILES['fle_prdimage']['type'][$pdi]) {
									case 'image/jpeg':
									case 'image/jpg':
									case 'image/gif':
									case 'image/png':
											// echo "##";
											$extn1 = 'jpg';
											break;
									case 'application/pdf':
											// echo "$$";
											$extn1 = 'pdf';
											break;
								}

								$yrdir = $uploadimg;
								$yrfolder_exists = is_dir($yrdir);
								if($yrfolder_exists) { }
								else {
									if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
								}

								$expl = explode(".", $_FILES['fle_prdimage']['name'][$pdi]);
								$upload_img1 = $current_year[0]['PORYEAR']."_".$srno."_".$maxprlstno[0]['MAXPRLSTNO'].".".$extn1;
								$source = $imgfile1;
								$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
								$complogos1 = str_replace(" ", "-", $upload_img1);
								$complogos1 = strtolower($complogos1);

								//// Thumb start
								if($fldimli == 'i')
								{
									// echo "%%";
									$upload_img1_tmp = $current_year[0]['PORYEAR']."_".$srno."_".$maxprlstno[0]['MAXPRLSTNO'].".jpg";
									$source_tmp = $imgfile1;
									$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
									$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
									$complogos1_tmp = strtolower($complogos1_tmp);

									$width = $info[0];
									$height = $info[1];
									$newwidth1=200;
									$newheight1=200;
									$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
									imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

									$resized_file = "uploaded_files/thumb_images/". $complogos1_tmp;
									$dest_thumbfile = "approval_desk/product_images/".$uploadimg."/thumb_images/".$complogos1_tmp;
									imagejpeg($tmp1, $resized_file, 50);
									imagedestroy($image1);
									imagedestroy($tmp1);
									// echo "^^^".$source_tmp."^^".$dest_thumbfile."^^".'<br>';
									$llll = move_uploaded_file($source_tmp, $dest_thumbfile);
									// echo "^^".$llll."^^";
									// exit;
									$local_file = "uploaded_files/thumb_images/".$complogos1_tmp;
									$server_file = 'approval_desk/product_images/'.$uploadimg.'/thumb_images/'.$complogos1;

									if ((!$conn_id) || (!$login_result)) {
										$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
													//echo "tmp Succes";
										unlink($local_file);
									}
								}
								//// Thumb end
								// exit;

								$original_complogos1 = "../uploads/product_images/".$uploadimg."/".$complogos1;
								// echo '!!!'.$complogos1.'<br>';
								move_uploaded_file($source, $original_complogos1);

								/* Upload into FTP */
								$local_file = "../uploads/product_images/".$uploadimg."/".$complogos1;
								$server_file = 'approval_desk/product_images/'.$uploadimg.'/'.$complogos1;
								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									// echo "lar Succes";
									unlink($local_file);
								}
								/* Upload into FTP */
						}
						// Product Image
						$field_appdet['PRDIMAG'] = $complogos1;

						$insert_appdet = insert_dbquery($field_appdet, $tbl_appdet);
						// echo "<br>IN".$pdi."IN".$insert_appdet."IN"; print_r($field_appdet); echo "<br>";

						$pdii = $pdi + 1;
						$qdii = 1;
						// Product List - Quotation Adding pdi
						for($qdi = 0; $qdi < count($txt_sltsupcode[$pdii]); $qdi++) { // echo "***";
							$sp_cd = explode(" - ", $txt_sltsupcode[$pdii][$qdi]);
							$maxprlstsr = select_query_json("Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR
																	From APPROVAL_PRODUCT_QUOTATION
																	WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PBDLSNO = '".$maxprlstno[0]['MAXPRLSTNO']."' and PRLSTYR = '".$current_year[0]['PORYEAR']."'", "Centra", 'TEST');
							/* echo "<br>Select nvl(Max(PRLSTSR),0)+1 MAXPRLSTSR
																	From APPROVAL_PRODUCT_QUOTATION
																	WHERE PBDYEAR = '".$current_year[0]['PORYEAR']."' and PBDCODE = ".$srno." and PBDLSNO = '".$maxprlstno[0]['MAXPRLSTNO']."' and PRLSTYR = '".$current_year[0]['PORYEAR']."'
																		and PRLSTNO = ".$maxarqcode[0][0]." "; */
							$tbl_appdet1 = "APPROVAL_PRODUCT_QUOTATION";
							$field_appdet1 = array();
							$field_appdet1['PBDYEAR'] = $current_year[0]['PORYEAR'];
							$field_appdet1['PBDCODE'] = $srno;
							$field_appdet1['PBDLSNO'] = $maxprlstno[0]['MAXPRLSTNO'];
							$field_appdet1['PRLSTYR'] = $current_year[0]['PORYEAR'];
							$field_appdet1['PRLSTNO'] = $maxprlstno[0]['MAXPRLSTNO'];
							$field_appdet1['PRLSTSR'] = $maxprlstsr[0]['MAXPRLSTSR'];

							$field_appdet1['SUPCODE'] = $sp_cd[0];
							$field_appdet1['SUPNAME'] = strtoupper($sp_cd[1]);
							// echo "**".$txt_sltsupplier[$pdii][0]."**".$qdii."**".$qdi."**".$iqdi."**".$pdii."**";
							if($txt_sltsupplier[$pdii][0] == $qdii) {
								$field_appdet1['SLTSUPP'] = 1;
							} else {
								$field_appdet1['SLTSUPP'] = 0;
							}
							$qdii++;
							if($txt_delivery_duration[$pdii][$qdi] == 0) {
								$field_appdet1['DELPRID'] = 1;
							} else {
								$field_appdet1['DELPRID'] = strtoupper($txt_delivery_duration[$pdii][$qdi]);
							}
							$field_appdet1['PRDRATE'] = $txt_prdrate[$pdii][$qdi];
							$field_appdet1['SGSTVAL'] = $txt_prdsgst[$pdii][$qdi];
							$field_appdet1['CGSTVAL'] = $txt_prdcgst[$pdii][$qdi];
							$field_appdet1['IGSTVAL'] = $txt_prdigst[$pdii][$qdi];
							$field_appdet1['DISCONT'] = $txt_prddisc[$pdii][$qdi];
							$field_appdet1['NETAMNT'] = $hid_prdnetamount[$pdii][$qdi];
							$field_appdet1['SUPRMRK'] = strtoupper($txt_suprmrk[$pdii][$qdi]);
							$field_appdet1['ADVAMNT'] = $txt_advance_amount[$pdii][$qdi];
							// $field_appdet1['NETAMNT'] = 0;

							//echo "++".$_FILES['fle_supquot']['type'][$pdii][$qdi]."++".$_FILES['fle_supquot']['tmp_name'][$pdii][$qdi]."++".$_FILES['fle_supquot']['name'][$pdii][$qdi]."++";
							$fldimli = '-'; $complogos1 = '-';
							if($_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/jpeg" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/gif" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "image/png" or $_FILES['fle_supquot']['type'][$pdii][$qdi] == "application/pdf") {
								$fldimli = find_indicator( $_FILES['fle_supquot']['type'][$pdii][$qdi] );

								$imgfile1 = $_FILES['fle_supquot']['tmp_name'][$pdii][$qdi];
								if($fldimli == 'i')
								{
									$info = getimagesize($imgfile1);
									$image1 = imagecreatefromjpeg($imgfile1);
									if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
									elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
									elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
									//save it
									imagejpeg($image, $imgfile1, 20);
								}

								switch($_FILES['fle_supquot']['type'][$pdii][$qdi]) {
									case 'image/jpeg':
									case 'image/jpg':
									case 'image/gif':
									case 'image/png':
											// echo "##";
											$extn1 = 'jpg';
											break;
									case 'application/pdf':
											// echo "$$";
											$extn1 = 'pdf';
											break;
								}

								$yrdir = $uploadimg;
								$yrfolder_exists = is_dir($yrdir);
								if($yrfolder_exists) { }
								else {
									if(ftp_mkdir($ftp_conn, $yrdir)) { } else { }
								}

								$expl = explode(".", $_FILES['fle_supquot']['name'][$pdii][$qdi]);
								$upload_img1 = $current_year[0]['PORYEAR']."_".$srno."_".$current_year[0]['PORYEAR']."_".$maxprlstno[0]['MAXPRLSTNO']."_".$maxprlstsr[0]['MAXPRLSTSR'].".".$extn1;
								$source = $imgfile1;
								$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
								$complogos1 = str_replace(" ", "-", $upload_img1);
								$complogos1 = strtolower($complogos1);

								//// Thumb start
								if($fldimli == 'i')
								{
									// echo "%%";
									$upload_img1_tmp = $current_year[0]['PORYEAR']."_".$srno."_".$current_year[0]['PORYEAR']."_".$maxprlstno[0]['MAXPRLSTNO']."_".$maxprlstsr[0]['MAXPRLSTSR'].".jpg";
									$source_tmp = $imgfile1;
									$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
									$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
									$complogos1_tmp = strtolower($complogos1_tmp);

									$width = $info[0];
									$height = $info[1];
									$newwidth1=200;
									$newheight1=200;
									$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
									imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

									$resized_file = "uploaded_files/thumb_images/". $complogos1_tmp;
									$dest_thumbfile = "approval_desk/product_quotation/".$uploadimg."/thumb_images/".$complogos1_tmp;
									imagejpeg($tmp1, $resized_file, 50);
									imagedestroy($image1);
									imagedestroy($tmp1);
									// echo "^^^".$source_tmp."^^".$dest_thumbfile."^^".'<br>';
									$llll = move_uploaded_file($source_tmp, $dest_thumbfile);
									// echo "^^".$llll."^^";
									// exit;
									$local_file = "uploaded_files/thumb_images/".$complogos1_tmp;
									$server_file = 'approval_desk/product_quotation/'.$uploadimg.'/thumb_images/'.$complogos1;

									if ((!$conn_id) || (!$login_result)) {
										$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
													//echo "tmp Succes";
										unlink($local_file);
									}
								}
								//// Thumb end
								// exit;

								$original_complogos1 = "../uploads/product_quotation/".$uploadimg."/".$complogos1;
								// echo '!!!'.$complogos1.'<br>';
								move_uploaded_file($source, $original_complogos1);

								/* Upload into FTP */
								$local_file = "../uploads/product_quotation/".$uploadimg."/".$complogos1;
								$server_file = 'approval_desk/product_quotation/'.$uploadimg.'/'.$complogos1;
								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
									// echo "lar Succes";
									unlink($local_file);
								}
								/* Upload into FTP */
							}

							$field_appdet1['QUOTFIL'] = $complogos1;
							$field_appdet1['SPLDISC'] = $txt_spldisc[$pdii][$qdi];
							$field_appdet1['PIECLES'] = $txt_pieceless[$pdii][$qdi];
							// print_r($field_appdet1); echo "<br>"; // exit;
							$insert_appdet1 = insert_dbquery($field_appdet1, $tbl_appdet1);
						}
						// Product List - Quotation Adding
					}
					// Product List Adding
				}
			}
			// exit;

			// $insert_appreq = 1; echo "***".$slt_submission."***".$assign."***".count($assign)."***<pre>";
			// print_r($_FILES['txt_submission_fieldimpl']);

			if($insert_appreq == 1) {
				// fieldimpl
				for($i=0; $i<count($assign); $i++)
				{
					if($assign[$i] != '')
					{
						$fldimli = '-'; $complogos1 = '-';
						if($_FILES['txt_submission_fieldimpl']['type'][$i] == "image/jpeg" or $_FILES['txt_submission_fieldimpl']['type'][$i] == "image/gif" or $_FILES['txt_submission_fieldimpl']['type'][$i] == "image/png" or $_FILES['txt_submission_fieldimpl']['type'][$i] == "application/pdf") {
							$fldimli = find_indicator( $_FILES['txt_submission_fieldimpl']['type'][$i] );

							$imgfile1 = $_FILES['txt_submission_fieldimpl']['tmp_name'][$i];
							if($fldimli == 'i')
							{
								$info = getimagesize($imgfile1);
								$image1 = imagecreatefromjpeg($imgfile1);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
								//save it
								imagejpeg($image, $imgfile1, 20);
							}

							switch($_FILES['txt_submission_fieldimpl']['type'][$i]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn1 = 'jpg';
										break;
								case 'application/pdf':
										$extn1 = 'pdf';
										break;
							}

							// $upload_img1 = $_FILES['txt_submission_fieldimpl']['name'];
							$expl = explode(".", $_FILES['txt_submission_fieldimpl']['name'][$i]);
							$upload_img1 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_fieldimpl_".$fldimli."_".$i.".".$extn1;
							$source = $imgfile1;
							$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
							$complogos1 = str_replace(" ", "-", $upload_img1);
							$complogos1 = strtolower($complogos1);

							//// Thumb start
							if($fldimli == 'i')
							{
								$upload_img1_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_fieldimpl_".$fldimli."_".$i.".jpg";
								$source_tmp = $imgfile1;
								$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
								$complogos1_tmp = strtolower($complogos1_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos1_tmp;
								$dest_thumbfile = "approval_desk/request_entry/fieldimpl/thumb_images/".$complogos1_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image1);
								imagedestroy($tmp1);
								// echo "^^^".$complogos1_tmp."^^^".$source_tmp."^^^".$dest_thumbfile."^^^".$complogos1.'<br>';
								move_uploaded_file($source_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos1_tmp;
								$server_file = 'approval_desk/request_entry/fieldimpl/thumb_images/'.$complogos1;
								// exit;

								// echo "===".$ftp_conn."===".$server_file."===".$local_file."===<pre>";
								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												// echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/fieldimpl/".$complogos1;
							move_uploaded_file($source, $original_complogos1);

							/* Upload into FTP */
							$local_file = "../uploads/request_entry/fieldimpl/".$complogos1;
							$server_file = 'approval_desk/request_entry/fieldimpl/'.$complogos1;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos1;
							$field_docs['APRHEAD'] = 'fieldimpl';
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
								// echo "<br>lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							/* Upload into FTP */
						}
					}
				}
				// fieldimpl

				// othersupdocs
				for($i=0; $i<count($assign0); $i++)
				{
					if($assign0[$i] != '')
					{
						$fldimli = '-'; $complogos1 = '-';
						if($_FILES['txt_submission_othersupdocs']['type'][$i] == "image/jpeg" or $_FILES['txt_submission_othersupdocs']['type'][$i] == "image/gif" or $_FILES['txt_submission_othersupdocs']['type'][$i] == "image/png" or $_FILES['txt_submission_othersupdocs']['type'][$i] == "application/pdf") {
							$fldimli = find_indicator( $_FILES['txt_submission_othersupdocs']['type'][$i] );

							$imgfile1 = $_FILES['txt_submission_othersupdocs']['tmp_name'][$i];
							if($fldimli == 'i')
							{
								$info = getimagesize($imgfile1);
								$image1 = imagecreatefromjpeg($imgfile1);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile1);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile1);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile1);
								//save it
								imagejpeg($image, $imgfile1, 20);
							}

							switch($_FILES['txt_submission_othersupdocs']['type'][$i]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn1 = 'jpg';
										break;
								case 'application/pdf':
										$extn1 = 'pdf';
										break;
							}

							//$upload_img1 = $_FILES['txt_submission_othersupdocs']['name'];
							$expl = explode(".", $_FILES['txt_submission_othersupdocs']['name'][$i]);
							$upload_img1 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_othersupdocs_".$fldimli."_".$i.".".$extn1;
							$source = $imgfile1;
							$complogos1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1); //str_replace(" ", "_", $upload_img1));
							$complogos1 = str_replace(" ", "-", $upload_img1);
							$complogos1 = strtolower($complogos1);

							//// Thumb start
							if($fldimli == 'i')
							{
								$upload_img1_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_othersupdocs_".$fldimli."_".$i.".jpg";
								$source_tmp = $imgfile1;
								$complogos1_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img1_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos1_tmp = str_replace(" ", "-", $upload_img1_tmp);
								$complogos1_tmp = strtolower($complogos1_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image1,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos1_tmp;
								$dest_thumbfile = "approval_desk/request_entry/othersupdocs/thumb_images/".$complogos1_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image1);
								imagedestroy($tmp1);
								// echo "^^^".$complogos1_tmp.'<br>';
								move_uploaded_file($source_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos1_tmp;
								$server_file = 'approval_desk/request_entry/othersupdocs/thumb_images/'.$complogos1;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/othersupdocs/".$complogos1;
							// echo '!!!'.$complogos1.'<br>';
							move_uploaded_file($source, $original_complogos1);

							// Upload into FTP
							$local_file = "../uploads/request_entry/othersupdocs/".$complogos1;
							$server_file = 'approval_desk/request_entry/othersupdocs/'.$complogos1;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos1;
							$field_docs['APRHEAD'] = 'othersupdocs';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
								// echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							// Upload into FTP
						}
					}
				}
				// othersupdocs


				// quotations
				for($i1=0; $i1<count($assign1); $i1++)
				{
					if($assign1[$i1] != '')
					{
						$qutat1i = '-'; $complogos2 = '-';
						if($_FILES['txt_submission_quotations']['type'][$i1] == "image/jpeg" or $_FILES['txt_submission_quotations']['type'][$i1] == "image/gif" or $_FILES['txt_submission_quotations']['type'][$i1] == "image/png" or $_FILES['txt_submission_quotations']['type'][$i1] == "application/pdf") {
							$qutat1i = find_indicator( $_FILES['txt_submission_quotations']['type'][$i1] );

							$imgfile2 = $_FILES['txt_submission_quotations']['tmp_name'][$i1];
							if($qutat1i == 'i')
							{
								$info = getimagesize($imgfile2);
								$image2 = imagecreatefromjpeg($imgfile2);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile2);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile2);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile2);
								//save it
								imagejpeg($image, $imgfile2, 20);
							}

							switch($_FILES['txt_submission_quotations']['type'][$i1]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn2 = 'jpg';
										break;
								case 'application/pdf':
										$extn2 = 'pdf';
										break;
							}

							// $upload_img1 = $_FILES['txt_submission_fieldimpl']['name'];
							$expl = explode(".", $_FILES['txt_submission_quotations']['name'][$i1]);

							$upload_img2 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".$i1.".".$extn2;
							$source2 = $imgfile2;
							$complogos2 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2); //str_replace(" ", "_", $upload_img2));
							$complogos2 = str_replace(" ", "-", $upload_img2);
							$complogos2 = strtolower($complogos2);

							//// Thumb start
							if($qutat1i == 'i')
							{
								$upload_img2_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_quotations_".$qutat1i."_".$i1.".jpg";
								$source2_tmp = $imgfile2;
								$complogos2_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img2_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos2_tmp = str_replace(" ", "-", $upload_img2_tmp);
								$complogos2_tmp = strtolower($complogos2_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image2,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos2_tmp;
								$dest_thumbfile = "approval_desk/request_entry/quotations/thumb_images/".$complogos2_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image2);
								imagedestroy($tmp1);
								//echo "^^^".$complogos2_tmp.'<br>';
								move_uploaded_file($source2_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos2_tmp;
								$server_file = 'approval_desk/request_entry/quotations/thumb_images/'.$complogos2_tmp;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/quotations/".$complogos2;
							//echo '!!!'.$complogos2.'<br>';
							move_uploaded_file($source2, $original_complogos1);

							/* Upload into FTP */
							$local_file = "../uploads/request_entry/quotations/".$complogos2;
							$server_file = 'approval_desk/request_entry/quotations/'.$complogos2;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos2;
							$field_docs['APRHEAD'] = 'quotations';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							/* Upload into FTP */
						}
					}
				}
				// quotations


				// clrphoto
				for($i2=0; $i2<count($assign2); $i2++)
				{
					if($assign2[$i2] != '')
					{
						$smplpti = '-'; $complogos7 = '-';
						if($_FILES['txt_submission_clrphoto']['type'][$i2] == "image/jpeg" or $_FILES['txt_submission_clrphoto']['type'][$i2] == "image/gif" or $_FILES['txt_submission_clrphoto']['type'][$i2] == "image/png" or $_FILES['txt_submission_clrphoto']['type'][$i2] == "application/pdf") {
							$smplpti = find_indicator( $_FILES['txt_submission_clrphoto']['type'][$i2] );

							$imgfile7 = $_FILES['txt_submission_clrphoto']['tmp_name'][$i2];
							if($smplpti == 'i')
							{
								$info = getimagesize($imgfile7);
								$image7 = imagecreatefromjpeg($imgfile7);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile7);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile7);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile7);
								//save it
								imagejpeg($image, $imgfile7, 20);
							}

							switch($_FILES['txt_submission_clrphoto']['type'][$i2]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn3 = 'jpg';
										break;
								case 'application/pdf':
										$extn3 = 'pdf';
										break;
							}

							$expl = explode(".", $_FILES['txt_submission_clrphoto']['name'][$i2]);
							$upload_img7 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_clrphoto_".$smplpti."_".$i2.".".$extn3;
							$source7 = $imgfile7;
							$complogos7 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img7); //str_replace(" ", "_", $upload_img2));
							$complogos7 = str_replace(" ", "-", $upload_img7);
							$complogos7 = strtolower($complogos7);

							//// Thumb start
							if($smplpti == 'i')
							{
								$upload_img7_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_clrphoto_".$smplpti."_".$i2.".jpg";
								$source7_tmp = $imgfile7;
								$complogos7_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img7_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos7_tmp = str_replace(" ", "-", $upload_img7_tmp);
								$complogos7_tmp = strtolower($complogos7_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image7,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos7_tmp;
								$dest_thumbfile = "approval_desk/request_entry/clrphoto/thumb_images/".$complogos7_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image7);
								imagedestroy($tmp1);
								//echo "^^^".$complogos7_tmp.'<br>';
								move_uploaded_file($source7_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos7_tmp;
								$server_file = 'approval_desk/request_entry/clrphoto/thumb_images/'.$complogos7_tmp;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/clrphoto/".$complogos7;
							//echo '!!!'.$complogos7.'<br>';
							move_uploaded_file($source7, $original_complogos1);

							/* Upload into FTP */
							$local_file = "../uploads/request_entry/clrphoto/".$complogos7;
							$server_file = 'approval_desk/request_entry/clrphoto/'.$complogos7;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos7;
							$field_docs['APRHEAD'] = 'clrphoto';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							/* Upload into FTP */
						}
					}
				}
				// clrphoto


				/* // lastapproval
				for($i3=0; $i3<count($assign3); $i3++)
				{
					if($assign3[$i3] != '')
					{
						$lstapri = '-'; $complogos8 = '-';
						if($_FILES['txt_submission_last_approval']['type'][$i3] == "image/jpeg" or $_FILES['txt_submission_last_approval']['type'][$i3] == "image/gif" or $_FILES['txt_submission_last_approval']['type'][$i3] == "image/png" or $_FILES['txt_submission_last_approval']['type'][$i3] == "application/pdf") {
							$lstapri = find_indicator( $_FILES['txt_submission_last_approval']['type'][$i3] );

							$imgfile8 = $_FILES['txt_submission_last_approval']['tmp_name'][$i3];
							if($lstapri == 'i')
							{
								$info = getimagesize($imgfile8);
								$image8 = imagecreatefromjpeg($imgfile8);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile8);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile8);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile8);
								//save it
								imagejpeg($image, $imgfile8, 20);
							}

							switch($_FILES['txt_submission_last_approval']['type'][$i3]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn4 = 'jpg';
										break;
								case 'application/pdf':
										$extn4 = 'pdf';
										break;
							}

							$expl = explode(".", $_FILES['txt_submission_last_approval']['name'][$i3]);
							$upload_img8 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_lastapproval_".$lstapri."_".$i3.".".$extn4;
							$source8 = $imgfile8;
							$complogos8 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img8); //str_replace(" ", "_", $upload_img2));
							$complogos8 = str_replace(" ", "-", $upload_img8);
							$complogos8 = strtolower($complogos8);

							//// Thumb start
							if($lstapri == 'i')
							{
								$upload_img8_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_lastapproval_".$lstapri."_".$i3.".jpg";
								$source8_tmp = $imgfile8;
								$complogos8_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img8_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos8_tmp = str_replace(" ", "-", $upload_img8_tmp);
								$complogos8_tmp = strtolower($complogos8_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image8,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos8_tmp;
								$dest_thumbfile = "approval_desk/request_entry/lastapproval/thumb_images/".$complogos8_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image8);
								imagedestroy($tmp1);
								//echo "^^^".$complogos8_tmp.'<br>';
								move_uploaded_file($source8_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos8_tmp;
								$server_file = 'approval_desk/request_entry/lastapproval/thumb_images/'.$complogos8_tmp;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/lastapproval/".$complogos8;
							//echo '!!!'.$complogos8.'<br>';
							move_uploaded_file($source8, $original_complogos1);

							// Upload into FTP
							$local_file = "../uploads/request_entry/lastapproval/".$complogos8;
							$server_file = 'approval_desk/request_entry/lastapproval/'.$complogos8;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos8;
							$field_docs['APRHEAD'] = 'lastapproval';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							// Upload into FTP
						}
					}
				}
				// lastapproval */

				// print_r($_FILES['txt_submission_artwork']);
				// artwork
				for($i4=0; $i4<count($assign4); $i4++)
				{
					if($assign4[$i4] != '')
					{
						$lstapri = '-'; $complogos9 = '-';
						if($_FILES['txt_submission_artwork']['type'][$i4] == "image/jpeg" or $_FILES['txt_submission_artwork']['type'][$i4] == "image/gif" or $_FILES['txt_submission_artwork']['type'][$i4] == "image/png" or $_FILES['txt_submission_artwork']['type'][$i4] == "application/pdf") {
							$lstapri = find_indicator( $_FILES['txt_submission_artwork']['type'][$i4] );

							$imgfile9 = $_FILES['txt_submission_artwork']['tmp_name'][$i4];
							if($lstapri == 'i')
							{
								$info = getimagesize($imgfile9);
								$image9 = imagecreatefromjpeg($imgfile9);
								if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($imgfile9);
								elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($imgfile9);
								elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($imgfile9);
								// save it
								imagejpeg($image, $imgfile9, 20);
							}

							switch($_FILES['txt_submission_artwork']['type'][$i4]) {
								case 'image/jpeg':
								case 'image/jpg':
								case 'image/gif':
								case 'image/png':
										$extn4 = 'jpg';
										break;
								case 'application/pdf':
										$extn4 = 'pdf';
										break;
							}

							$expl = explode(".", $_FILES['txt_submission_artwork']['name'][$i4]);
							$upload_img9 = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_artwork_".$lstapri."_".$i4.".".$extn4;
							$source9 = $imgfile9;
							$complogos9 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img9); //str_replace(" ", "_", $upload_img2));
							$complogos9 = str_replace(" ", "-", $upload_img9);
							$complogos9 = strtolower($complogos9);

							//// Thumb start
							if($lstapri == 'i')
							{
								$upload_img9_tmp = $maxarqcode[0]['MAXARQCODE']."_".$slt_submission."_".$slt_topcore."_".$current_year[0]['PORYEAR']."_artwork_".$lstapri."_".$i4.".jpg";
								$source9_tmp = $imgfile9;
								$complogos9_tmp = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $upload_img9_tmp); //str_replace(" ", "_", $upload_img1));
								$complogos9_tmp = str_replace(" ", "-", $upload_img9_tmp);
								$complogos9_tmp = strtolower($complogos9_tmp);

								$width = $info[0];
								$height = $info[1];
								$newwidth1=200;
								$newheight1=200;
								$tmp1=imagecreatetruecolor($newwidth1, $newheight1);
								imagecopyresampled($tmp1,$image9,0,0,0,0,$newwidth1,$newheight1,$width,$height);

								$resized_file = "../uploaded_files/". $complogos9_tmp;
								$dest_thumbfile = "approval_desk/request_entry/artwork/thumb_images/".$complogos9_tmp;
								imagejpeg($tmp1, $resized_file, 50);
								imagedestroy($image9);
								imagedestroy($tmp1);
								//echo "^^^".$complogos9_tmp.'<br>';
								move_uploaded_file($source9_tmp, $dest_thumbfile);
								$local_file = "../uploaded_files/".$complogos9_tmp;
								$server_file = 'approval_desk/request_entry/artwork/thumb_images/'.$complogos9_tmp;

								if ((!$conn_id) || (!$login_result)) {
									$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
												//echo "tmp Succes";
									unlink($local_file);
								}
							}
							//// Thumb end

							$original_complogos1 = "../uploads/request_entry/artwork/".$complogos9;
							//echo '!!!'.$complogos9.'<br>';
							move_uploaded_file($source9, $original_complogos1);

							// Upload into FTP
							$local_file = "../uploads/request_entry/artwork/".$complogos9;
							$server_file = 'approval_desk/request_entry/artwork/'.$complogos9;

							// Approval Documents
							$attch++;
							$tbl_docs = "APPROVAL_REQUEST_DOCS";
							$field_docs['APRNUMB'] = $apprno;
							$field_docs['APDCSRN'] = $attch;
							$field_docs['APRDOCS'] = $complogos9;
							$field_docs['APRHEAD'] = 'artwork';
							// $insert_docs = insert_dbquery($field_docs, $tbl_docs);
							// print_r($field_docs);
							// Approval Documents

							if ((!$conn_id) || (!$login_result)) {
								$upload = ftp_put($ftp_conn, $server_file, $local_file, FTP_BINARY);
											//echo "lar Succes";
								unlink($local_file);
							}
							if ($upload) {
								$insert_docs = insert_dbquery($field_docs, $tbl_docs);
							}
							// Upload into FTP
						}
					}
				}
				// artwork
			}
		}
	} // branch for loop
	// }
	// echo "==".$insert_appreq."==";
	// exit();

	if($insert_appreq == 1) { $redirect_url = 'request_list.php';
		$json = json_encode(array('type' => 'success', "info" => $redirect_url, "msg" => "Your Request Created Successfully"));
	} else {
		$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Failed in Request Creation. Kindly try again!!"));
	}
}
}
catch(Exception $e) {
	$json = json_encode(array('type' => 'error', "info" => '', "msg" => "Failed in Request Creation. Kindly try again!!!"));
}

	/* Output header */
	// header('Content-type: application/json');
	die($json);

}
// *** Request Entry ***


/* // *** Acknowledge Approval ***
if ($_POST['action'] == 'acknowledge_approvals') {
	// echo "*********";
	$update_apprq = 0;
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));
	print_r($design_approval);
    for ($ik = 0; $ik < count($design_approval); $ik++) {
        $ex1 = explode("||", $desapr_value[$ik]);
        // Update in APPROVAL_REQUEST Table
        $tbl_finapprq = "APPROVAL_REQUEST";
        $field_finapprq = array();
        $field_finapprq['ACKUSER'] = $_SESSION['tcs_usrcode'];
        $field_finapprq['ACKSTAT'] = "A";
        $field_finapprq['ACKDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
        echo $where_finapprq = " APRNUMB = '".$ex1[2]."' and ARQSRNO = '".$ex1[1]."'";
        print_r($field_finapprq); //echo "<br>";
        // $update_apprq = update_dbquery($field_finapprq, $tbl_finapprq, $where_finapprq);
        // Update in APPROVAL_REQUEST Table
    }
	echo $update_apprq;
}
// *** Acknowledge Approval *** */
?>