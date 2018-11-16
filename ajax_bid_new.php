<?php
session_start();
error_reporting(0);
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS');
if($_REQUEST['action'] == "CREATE")
{
	$today = date("Y-m-d"); 
	$pbdcode = $code;
	$sql_list = select_query_json("select * from approval_productlist where pbdyear='".$year."' and pbdcode= ".$pbdcode."", "Centra", 'TCS');
	$sql_qua  = select_query_json("select * from approval_product_quotation where pbdyear='".$year."' and pbdcode= ".$pbdcode."", "Centra", 'TCS');
	$sql_aprnumb  = select_query_json("select APRNUMB from approval_request where arqyear = '".$year."' and IMUSRIP = '".$pbdcode."' and arqsrno = 1", "Centra", 'TCS');
	// echo "select * from approval_product_quotation where pbdyear='".$year."' and pbdcode= ".$pbdcode."";

	// Store into Approval Product Quotation History for tracking 
	$sql_quot_history = delete_dbquery("INSERT INTO approval_prd_quot_history select PBDYEAR, PBDCODE, PBDLSNO, PRLSTYR, PRLSTNO, PRLSTSR, 1, SUPCODE, SUPNAME, SLTSUPP, DELPRID, PRDRATE, SGSTVAL, 
														CGSTVAL, IGSTVAL, DISCONT, NETAMNT, QUOTFIL, SPLDISC, PIECLES, SUPRMRK, ADVAMNT, '".$_SESSION['tcs_usrcode']."', SYSDATE, '', '', 'N', '', '', 'C' 
													from approval_product_quotation 
													where pbdcode='".$pbdcode."' and PBDYEAR='".$year."'"); // A - Approval Level Value Change, B - Reverse Bid value change, C - Created Value
	// Store into Approval Product Quotation History for tracking

	if(count($sql_qua)>0)
	{
		foreach ($sql_list as $res) {
			$list[] = array('product_id' => $res['PRDCODE']."-".$res['SUBCODE'], 'qty' => $res['TOTLQTY']);
		}

	 	foreach ($sql_qua as $res) 
	 	{
	 		$q_list = array();
	 		foreach ($sql_list as $li) 
			{
	 			if($li['PBDLSNO'] == $res['PRLSTNO'])
	 			{
	 				// $q_list[] = array('product_id' => $li['PRDCODE']."-".$li['SUBCODE'], 'unit_price' => $res['PRDRATE'], 'qty' => $li['TOTLQTY'], 'supplier_ref' => $res['PBDYEAR']."~".$res['PBDCODE']."~".$res['PBDLSNO']."~".$res['PRLSTSR'], 'product_hsncode' => $li['PRODHSN'], 'sub_product_hsncode' => $li['SPRDHSN'], 'bid_expiry_date' => $bid_expiry_date);
	 				$q_list[] = array('product_id' => $li['PRDCODE']."-".$li['SUBCODE'], 'unit_price' => $res['PRDRATE'], 'qty' => $li['TOTLQTY'], 'supplier_ref' => $res['PBDYEAR']."~".$res['PBDCODE']."~".$res['PBDLSNO']."~".$res['PRLSTSR']);
	 			}
			}
			$qua[] = array('partner_id' => $res['SUPCODE'], 'dept_id' => "STAFF WELFARE", 'line_ids' => $q_list);
	  	}

		$bid_expiry_dt = date("Y-m-d", strtotime($bid_expiry_date)); 
		$fin_val = array();
		$fin_val[] = array('user_id' => 'Administrator', 'date_order' => $today, 'date_tender' => $bid_expiry_dt, 'date_end' => $bid_expiry_dt, 'line_ids' => $list, 'partner' => $qua );
		$fin_val = json_encode($fin_val);
		$fin_val = str_replace('"', "'", $fin_val);

		$pagename = $year."_".$code;
		$newFileName = 'uploaded_files/'.$pagename.'.txt';
		$newFileContent = "http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq?db=tcs_demo&token=584309ec40aa476e868113cd5d2a9614&create_vals=".$fin_val; 
		// exit;
		$file = file_put_contents($newFileName, $newFileContent);

		/* $ftp_conn = ftp_connect($ftp_server1_159) or die("Could not connect to $ftp_server");
		$login    = ftp_login($ftp_conn, $ftp_user_name_159, $ftp_user_pass_159); */

		$complogos1  = $pagename.'.txt';
		$server_file = 'approval_desk/Bid/'.$complogos1;
		$local_file  = "uploaded_files/".$complogos1;
		$ret = ftp_nb_put($ftp_conn, $server_file, $local_file, FTP_BINARY, FTP_AUTORESUME);
		unlink($local_file); 

		$tbl = "Approval_request";
		$fld = array();
		$fld['APPSTAT'] = 'Z'; 
		$wre = " arqyear='".$year."' and IMUSRIP=".$code." and atccode=".$head." and appstat ='N' ";
		$update = update_dbquery($fld,$tbl,$wre);

		$tblb = "Approval_bid_status";
		$fldb = array();
		$fldb['APRNUMB'] = $sql_aprnumb[0]['APRNUMB']; 
		$fldb['BIDCODE'] = 2; 
		$fldb['TENDRCD'] = '0'; 
		$fldb['BIDSTAT'] = 'N'; 
		$fldb['BDEXPDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$bid_expiry_date; 
		$wreb = " aprnumb = '".$sql_aprnumb[0]['APRNUMB']." ";
		$updateb = insert_dbquery($fldb, $tblb, $wreb);
		echo $update; 
	} else {
		echo "2";
	}
}
if($_REQUEST['action'] == "CREATE_QUOTE")
{
	$today = date("Y-m-d"); 
	$pbdcode = $code;
	print_r($_REQUEST);
	$cur=date_create(date("Y-m-d H:i:s"));
    date_add($cur,date_interval_create_from_date_string("55 hours"));
	$bid_expiry_date=date_format($cur,"Y-m-d H:i:s");
	
	$sql_list = select_query_json("select apqf.*,sup.supname from approval_product_quotation_fix apqf,supplier_asset sup where sup.supcode=apqf.supcode and aprnumb='".$_REQUEST['aprnumb']."'", "Centra", 'TEST');
	$ki=count($sql_list);
	$max=select_query_json("select nvl(max(PRQUHSR),0)+1 maxval from approval_prd_quot_history  where PBDYEAR='".$sql_list[0]['ARQYEAR']."' and PBDCODE='".$code."' and PRQUHSR='".$sql_list[0]['ENTNUMB']."'", "Centra", 'TEST');
	$g_table="approval_prd_quot_history";
	for($i=0;$i<$ki;$i++)
	{
		$g_fld['PBDYEAR']=$sql_list[$i]['ARQYEAR'];
		$g_fld['PBDCODE']=$code; 
		$g_fld['PBDLSNO']=$sql_list[$i]['ENTNUMB'];// for primary constraint by the bid insert(create quote)
		$g_fld['PRLSTYR']=$sql_list[$i]['ARQYEAR'];
		$g_fld['PRLSTNO']='1';
		$g_fld['PRLSTSR']='1';
		$g_fld['PRQUHSR']=$max[0]['MAXVAL'];
		$g_fld['SUPCODE']=$sql_list[$i]['SUPCODE'];
		$g_fld['SUPNAME']=$sql_list[$i]['SUPNAME'];
		$g_fld['SLTSUPP']=$sql_list[$i]['SLTSUPP'];
		$g_fld['DELPRID']='1';
		$g_fld['PRDRATE']=$sql_list[$i]['SUPRATE'];
		$g_fld['SGSTVAL']=$sql_list[$i]['SGSTPER'];
		$g_fld['CGSTVAL']=$sql_list[$i]['CGSTPER'];
		$g_fld['IGSTVAL']=$sql_list[$i]['IGSTPER'];
		$g_fld['DISCONT']=$sql_list[$i]['SUPDISC'];
		$g_fld['NETAMNT']=$sql_list[$i]['NETAMT'];
		$g_fld['QUOTFIL']='C';
		$g_fld['SPLDISC']='';
		$g_fld['PIECLES']='';
		$g_fld['SUPRMRK']='';
		$g_fld['ADVAMNT']='0';
		$g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
		$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
		$g_fld['EDTUSER']='';
		$g_fld['EDTDATE']='';
		$g_fld['DELETED']='N';
		$g_fld['DELUSER']='';
		$g_fld['DELDATE']='';
		$g_fld['QUTMODE']='C';
	//	print_r($g_fld);
	//	echo("-------------------------------------------------------------------------\n");
		$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
	//	echo("-------------------------------------------------------------------------\n");
	}
	//echo("select * from approval_product_quotation_fix where aprnumb='ADMIN / OFFICE 4005954 / 17-10-2018 / 5954 / 06:39 PM'");
	//print_r($sql_list);
	//$sql_qua  = select_query_json("select * from approval_product_quotation where pbdyear='".$year."' and pbdcode= ".$pbdcode."", "Centra", 'TEST');
	//$sql_aprnumb  = select_query_json("select APRNUMB from approval_request where arqyear = '".$year."' and IMUSRIP = '".$pbdcode."' and arqsrno = 1", "Centra", 'TEST');
	// echo "select * from approval_product_quotation where pbdyear='".$year."' and pbdcode= ".$pbdcode."";

	// Store into Approval Product Quotation History for tracking 
	/*$sql_quot_history = delete_dbquery("INSERT INTO approval_prd_quot_history select PBDYEAR, PBDCODE, PBDLSNO, PRLSTYR, PRLSTNO, PRLSTSR, 1, SUPCODE, SUPNAME, SLTSUPP, DELPRID, PRDRATE, SGSTVAL, 
														CGSTVAL, IGSTVAL, DISCONT, NETAMNT, QUOTFIL, SPLDISC, PIECLES, SUPRMRK, ADVAMNT, '".$_SESSION['tcs_usrcode']."', SYSDATE, '', '', 'N', '', '', 'C' 
													from approval_product_quotation 
													where pbdcode='".$pbdcode."' and PBDYEAR='".$year."'"); // A - Approval Level Value Change, B - Reverse Bid value change, C - Created Value
	// Store into Approval Product Quotation History for tracking*/

	if(count($sql_list)>0)
	{	//echo('<pre>');
		$arr_prd=array();
        foreach($sql_list as $key => $value)
        {
          $temp=count($arr_prd[$value['PRDCODE']]);
          $arr_prd[$value['PRDCODE']][$temp]=$value;
        }
        foreach ($arr_prd as $key => $value) {
        	$list[] = array('product_id' => $value[0]['PRDCODE']."-".$value[0]['SUBCODE'], 'qty' => $value[0]['PRDQTY']);
        }
		$fin=array();
		foreach ($sql_list as $res) {
			//$list[] = array('product_id' => $res['PRDCODE']."-".$res['SUBCODE'], 'qty' => $res['PRDQTY']);
			$q_list[] = array('product_id' => $res['PRDCODE']."-".$res['SUBCODE'], 'unit_price' => $res['SUPRATE'], 'qty' => $res['PRDQTY'], 'supplier_ref' => $res['ARQYEAR']."~".$res['ENTNUMB']."~".$res['PRDSRNO']);
			$qua[] = array('partner_id' => $res['SUPCODE'], 'dept_id' => "STAFF WELFARE", 'line_ids' => $q_list);
			//array_push($fin,$qua);
			//$qua='';
			$q_list='';
		}
		//print_r($fin);
		//echo("++");
	 	// foreach ($sql_qua as $res) 
	 	// {
	 	// 	$q_list = array();
	 	// 	foreach ($sql_list as $li) 
			// {
	 	// 		if($li['PBDLSNO'] == $res['PRLSTNO'])
	 	// 		{
	 	// 			// $q_list[] = array('product_id' => $li['PRDCODE']."-".$li['SUBCODE'], 'unit_price' => $res['PRDRATE'], 'qty' => $li['TOTLQTY'], 'supplier_ref' => $res['PBDYEAR']."~".$res['PBDCODE']."~".$res['PBDLSNO']."~".$res['PRLSTSR'], 'product_hsncode' => $li['PRODHSN'], 'sub_product_hsncode' => $li['SPRDHSN'], 'bid_expiry_date' => $bid_expiry_date);
	 	// 			$q_list[] = array('product_id' => $li['PRDCODE']."-".$li['SUBCODE'], 'unit_price' => $res['PRDRATE'], 'qty' => $li['TOTLQTY'], 'supplier_ref' => $res['PBDYEAR']."~".$res['PBDCODE']."~".$res['PBDLSNO']."~".$res['PRLSTSR']);
	 	// 		}
			// }
			// $qua[] = array('partner_id' => $res['SUPCODE'], 'dept_id' => "STAFF WELFARE", 'line_ids' => $q_list);
	  // 	}

		$bid_expiry_dt = $bid_expiry_date;
		$fin_val = array();
		$fin_val[] = array('user_id' => 'Administrator', 'date_order' => $today, 'date_tender' => $bid_expiry_dt, 'date_end' => $bid_expiry_dt, 'line_ids' => $list, 'partner' => $qua );
		$fin_val = json_encode($fin_val);
		$fin_val1 = json_encode($fin_val);
		$fin_val = str_replace('"', "'", $fin_val);
		$fin_val1 = str_replace('"', "'", $fin_val1);

		$pagename = $year."_".$code;
		$newFileName = 'uploaded_files/'.$pagename.'.txt';
		//$newFileName = 'uploaded_files/vikibid.txt';
		$newFileContent = "http://rfq.thechennaisilks.com:8069/api/purchase.requisition/create_rfq?db=tcs_demo&token=584309ec40aa476e868113cd5d2a9614&create_vals=".$fin_val; 
		// exit;
		echo("-------------------------------------------------------------------------\n");
		print_r($newFileContent);
		echo("-------------------------------------------------------------------------\n");

		$file = file_put_contents($newFileName, $newFileContent);
		
		/* $ftp_conn = ftp_connect($ftp_server1_159) or die("Could not connect to $ftp_server");
		$login    = ftp_login($ftp_conn, $ftp_user_name_159, $ftp_user_pass_159); */

		$complogos1  = $pagename.'.txt';
		$server_file = 'approval_desk/Bid/'.$complogos1;
		$local_file  = "uploaded_files/".$complogos1;
		$ret = ftp_nb_put($ftp_conn, $server_file, $local_file, FTP_BINARY, FTP_AUTORESUME);
		unlink($local_file); 

		$tbl = "Approval_request";
		$fld = array();
		$fld['APPSTAT'] = 'Z'; 
		$wre = " arqyear='".$year."' and IMUSRIP=".$code." and atccode=".$head." and appstat ='N' ";
		$update = update_test_dbquery($fld,$tbl,$wre);

		$tblb = "Approval_bid_status";
		$fldb = array();
		$fldb['APRNUMB'] = $_REQUEST['aprnumb']; 
		$fldb['BIDCODE'] = 2; 
		$fldb['TENDRCD'] = '0'; 
		$fldb['BIDSTAT'] = 'N'; 
		$fldb['BDEXPDT'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$bid_expiry_date; 
		$wreb = " aprnumb = '".$sql_aprnumb[0]['APRNUMB']." ";
		$updateb = insert_test_dbquery($fldb, $tblb, $wreb);
		echo $update; 
	} else {
		echo "2";
	}
}
elseif ($_REQUEST['action'] == "REVERSE_QUOTE") 
{
	//$sql_list = select_query_json("select * from approval_productlist where pbdyear='".$year."' and pbdcode= ".$code."", "Centra", 'TCS');
	$sql_json = select_query_json("select * from approval_product_quotation_fix where aprnumb='".$_REQUEST['aprnumb']."'", "Centra", 'TEST');
	//$sql_aprnumb  = select_query_json("select APRNUMB from approval_request where arqyear = '".$year."' and IMUSRIP = '".$code."' and arqsrno = 1", "Centra", 'TEST');
	foreach ($sql_json as $res) {
		$qua[] = array('supplier_ref' => $res['ARQYEAR']."~".$res['ENTNUMB']."~".$res['PRDSRNO']);
	}
	
	$qua = json_encode($qua);
	$qua = str_replace('"', "'", $qua);
	print_r($qua);
	$url = "http://rfq.thechennaisilks.com:8069/api/purchase.order.line/get_rfq?db=tcs_demo&token=584309ec40aa476e868113cd5d2a9614&create_vals=".$qua;
	echo("--".$url."--");
	$result = file_get_contents($url);

	

	
	$result = json_decode($result,true);
	//exit();
	if($result['responce'] == 'success')
	{	
		// echo("result<pre>\n");
		// echo("=======");
		// print_r($result);
		// echo("========</pre>\n");
		$i = 0; $j = 0; $a = 1; $net = 0;
		foreach ($result['success'] as $res) 
		{	echo("success\n");
		 	foreach ($res['success'] as $key) 
		 	{	//echo("success1\n");
		 		if($key['net_amount'] > 0) 
		 		{//	echo("success2\n");
					$sup = explode("~",$key['supplier_ref']);
					$table = "approval_product_quotation_fix";

					$sql_exist_tax = select_query_json("select * from approval_product_quotation_fix 
																where ARQYEAR='".$sup[0]."' and ENTNUMB='".$sup[1]."' and PRDSRNO='".$sup[2]."'", "Centra", 'TEST');
					// echo "select * from approval_product_quotation where pbdyear = '".$sup[0]."' and pbdcode=".$sup[1]." and prlstno=".$sup[2]." and prlstsr=".$sup[3]."";
					// echo "++".$sql_exist_tax[0]['IGSTVAL']."++";
					//echo("success5\n");
					if($sql_exist_tax[0]['IGSTVAL'] > 0) {
						$igst=$key['tax_pecentage'][0];
						$igst=explode("@",$igst);
						$igst=explode("%",$igst[1]);
						$igst=$igst[0];
						//print_r($igst);
						
						$fld_up['SGSTPER'] = 0;
						$fld_up['CGSTPER'] = 0;
						$fld_up['IGSTPER'] = $igst;
					} else {
						$igst=$key['tax_pecentage'][0];
						$igst=explode("@",$igst);
						$igst=explode("%",$igst[1]);
						$igst=$igst[0];
						//print_r();
						$fld_up['SGSTPER'] = 0;
						$fld_up['CGSTPER'] = 0;
						$fld_up['IGSTPER'] = $igst;
					}
					
					echo("success6\n");
					$fld_up['SLTSUPP'] = "1";
					$fld_up['SUPRATE'] = $key['rate_confirmed'];
					$fld_up['NETAMT'] = round($key['net_amount']);
					
					$wre_up = "ARQYEAR='".$sup[0]."' and ENTNUMB='".$sup[1]."' and PRDSRNO='".$sup[2]."'";

					//$fld_up1['SLTSUPP'] = "0";
					//$wre_up1 = "pbdyear = '".$sup[0]."' and pbdcode=".$sup[1]." and prlstno=".$sup[2]." ";

					// print_r($fld_up); print_r($key);
					echo("success5\n");
					echo('<pre>');
					print_r($fld_up);
					print_r($table);
					print_r($wre_up);

					if($key['status'] == 'Cost Control Approved')
					{
						$net += round($key['net_amount']);
						//$update1 = update_dbquery($fld_up1,$table,$wre_up1);
						$update  = update_test_dbquery($fld_up,$table,$wre_up);
						
						$i = $i+1;
					}else{
						$j = $j+1;
					}
				}echo("success4\n");
			}
		}
		
		if($update == 1) { 
		$sql_list = select_query_json("sELECT prd.old_prdcode,apqf.*,req.RQESTTO,req.adddate aprdate FROM  approval_product_quotation_fix APQF,approval_request req,product_asset prd WHERE prd.prdcode=apqf.prdcode and req.APRNUMB=apqf.aprnumb and apqf.aprnumb='".$_REQUEST['aprnumb']."'", "Centra", 'TEST');
		$ki=count($sql_list);
		
		$g_tablei="NON_PRODUCT_RATE_FIX";

		for($i=0;$i<$ki;$i++)
		{	if($sql_list[$i]['SLTSUPP']=='1')
			{
				$g_fldi['PRDCODE']=$sql_list[$i]['PRDCODE'];
				$g_fldi['SUBCODE']=$sql_list[$i]['SUBCODE'];
				$g_fldi['SUPCODE']=$sql_list[$i]['SUPCODE'];
				$g_fldi['PRDRATE']=$sql_list[$i]['SUPRATE'];
				$g_fldi['PRDTAXP']=intval($sql_list[$i]['SGSTPER'])+intval($sql_list[$i]['CGSTPER'])+intval($sql_list[$i]['IGSTPER']);
				$adddate = strtotime($sql_list[$i]['APRDATE']);
				$adddate = strtoupper(date('d-M-Y h:i:s A', $adddate));
				$g_fldi['AFFDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$adddate;
				$g_fldi['AUTSRNO']=$sql_list[$i]['RQESTTO'];
				$g_fldi['FIXREMA']='-';
				$g_fldi['ADDUSER']=$_SESSION['tcs_usrcode'];
				$g_fldi['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;;
				$g_fldi['EDTUSER']='';
				$g_fldi['EDTDATE']='';
				$g_fldi['DELETED']='N';
				$g_fldi['DELUSER']='';
				$g_fldi['DELDATE']='';
				$g_fldi['OLD_PRDCODE']=$sql_list[$i]['OLD_PRDCODE'];;
				$g_fldi['CGSTPER']=$sql_list[$i]['CGSTPER'];
				$g_fldi['SGSTPER']=$sql_list[$i]['SGSTPER'];
				$g_fldi['IGSTPER']=$sql_list[$i]['IGSTPER'];
				$g_fldi['SUPDISC']=$sql_list[$i]['SUPDISC'];
				$g_fldi['ACCYEAR']=$sql_list[$i]['ARQYEAR'];
				$g_fldi['SUP_ACTIVE']='Y';
				$g_fldi['APRNUMB']=$_REQUEST['aprnumb'];
				//print_r($g_fldi);
				//echo("-------------------------------------------------------------------------\n");
				$g_insert_subject = insert_test_dbquery($g_fldi, $g_tablei);
				//echo("-------------------------------------------------------------------------\n");
			}
		}
		$max=select_query_json("select nvl(max(PRQUHSR),0)+1 maxval from approval_prd_quot_history  where PBDYEAR='".$sql_list[0]['ARQYEAR']."' and PBDCODE='".$code."' and PRQUHSR='".$sql_list[0]['ENTNUMB']."'", "Centra", 'TEST');
		$g_table="approval_prd_quot_history";
		for($i=0;$i<$ki;$i++)
		{
			$g_fld['PBDYEAR']=$sql_list[$i]['ARQYEAR'];
			$g_fld['PBDCODE']=$code; 
			$g_fld['PBDLSNO']=$sql_list[$i]['ENTNUMB'];// for primary constraint by the bid insert(create quote)
			$g_fld['PRLSTYR']=$sql_list[$i]['ARQYEAR'];
			$g_fld['PRLSTNO']='1';
			$g_fld['PRLSTSR']='1';
			$g_fld['PRQUHSR']=$max[0]['MAXVAL'];
			$g_fld['SUPCODE']=$sql_list[$i]['SUPCODE'];
			$g_fld['SUPNAME']=$sql_list[$i]['SUPNAME'];
			$g_fld['SLTSUPP']=$sql_list[$i]['SLTSUPP'];
			$g_fld['DELPRID']='1';
			$g_fld['PRDRATE']=$sql_list[$i]['SUPRATE'];
			$g_fld['SGSTVAL']=$sql_list[$i]['SGSTPER'];
			$g_fld['CGSTVAL']=$sql_list[$i]['CGSTPER'];
			$g_fld['IGSTVAL']=$sql_list[$i]['IGSTPER'];
			$g_fld['DISCONT']=$sql_list[$i]['SUPDISC'];
			$g_fld['NETAMNT']=$sql_list[$i]['NETAMT'];
			$g_fld['QUOTFIL']='C';
			$g_fld['SPLDISC']='';
			$g_fld['PIECLES']='';
			$g_fld['SUPRMRK']='';
			$g_fld['ADVAMNT']='0';
			$g_fld['ADDUSER']=$_SESSION['tcs_usrcode'];
			$g_fld['ADDDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
			$g_fld['EDTUSER']='';
			$g_fld['EDTDATE']='';
			$g_fld['DELETED']='N';
			$g_fld['DELUSER']='';
			$g_fld['DELDATE']='';
			$g_fld['QUTMODE']='C';
			//print_r($g_fld);
			//echo("-------------------------------------------------------------------------\n");
			$g_insert_subject = insert_test_dbquery($g_fld, $g_table);
			//echo("-------------------------------------------------------------------------\n");
		}
		////////////////////////////HISTORY INSERT COMPLETET

		$tbl = "Approval_request";
		$fld = array();
		$fld['APPSTAT'] = 'N'; 
		$fld['PURHEAD'] = '2'; 
		$fld['APRQVAL'] = round($net);
		$fld['APPDVAL'] = round($net);
		$fld['APPFVAL'] = round($net);
		$wre = " arqyear='".$year."' and IMUSRIP=".$code." and atccode=".$head." and appstat ='Z' ";
		print_r($fld);
		print_r($tbl);
		print_r($wre);
		echo("\n");
		$up_fld = update_test_dbquery($fld,$tbl,$wre);
		echo("\n");

		$u_table = "Approval_budget_planner";
		$u_fld['APPRVAL'] = round($net);
		$u_fld['RESVALU'] = round($net); ///////////////**
		$u_wre = " aprnumb like '%".$code."%' and APRYEAR='".$year."' and APPRVAL not in 0 ";
		print_r($u_fld);
		print_r($u_table);
		print_r($u_wre);
		echo("\n");
		$u_up = update_test_dbquery($u_fld,$u_table,$u_wre);
		echo("\n");

		$tblb = "Approval_bid_status";
		$fldb = array();
		$fldb['BIDSTAT'] = 'A'; 
		$wreb = " aprnumb = '".$_REQUEST['aprnumb']." ";
		print_r($fldb);
		print_r($tblb);
		print_r($wreb);
		echo("\n");
		$updateb = insert_test_dbquery($fldb, $tblb, $wreb);
		echo("\n");
		echo $i."~".$j;
	} else {
		echo "failed";
	}
}
}