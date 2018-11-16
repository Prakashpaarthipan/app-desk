<?php
include('lib/config.php');
include("db_connect/public_functions.php");
extract($_REQUEST);


if($_GET['mode']=='supp_detail')
	{
		$sql_supp = select_query("select * from supplier where deleted = 'N' and supcode = ".$supcode."  order by supcode,supname");
		echo $sql_supp[0]['SUPMOBI']."~".$sql_supp[0]['SUPMAIL'];
	}

if($_GET['mode']=='SAVE')
	{

	$date = new DateTime();
	$request_date = $date->format('d-M-Y H:i:s');
	
	if ($_SESSION['tcs_empsrno']!="") {
		$usertype = 'E';
		$adduser = $_SESSION['tcs_usrcode'];
	}else{
		$usertype = 'S';
		$adduser = 100001;
	}
	
	$request_device_id = $_SERVER['REMOTE_ADDR'];
	
	$val = array();
	if ($_FILES['desfile']['tmp_name'][0]=="") {
	$withattach = 'false';
	}else{
	$withattach = 'true';	
	}

	if ($reopen =="YES") {
		$isreopen = "true";
		$val['ISREOPEN'] = $isreopen;
		$val['REQNUMB'] = $reqnumb;
		$val['REQSRNO'] = $reqsrno;	
		$val['MESSAGE'] = strtoupper($tcsComment);
		$val['WITHATT'] = $withattach;
	}else{
		$isreopen = "false";
		$val['ISREOPEN'] = $isreopen;
		$val['REQMODE'] = $comp_name;
		$val['DESKNO']  = $desk_no;
		$val['MOBILE']  = $contact_no;
		$val['ALTCONT'] = $alternate_no;
		$val['MAIL']    = $email;
		$val['MESSAGE'] = strtoupper($tcsComment);
		$val['USRTYPE'] = $usertype;
		$val['SUPCODE'] = $supplier;
		$val['ADDUSER'] = $adduser;
		$val['DEVID']   = $request_device_id;
		$val['APPCODE'] = '3';
		$val['WITHATT'] = $withattach;
	}
	
	//get filesize
	foreach ($_FILES['desfile']['tmp_name'] as $key => $tmp_name){
		$fileSize = $_FILES['desfile']['size'][$key]; 
	}
	
	$client = new SoapClient("http://172.16.0.167:8090/TCSservice.asmx?Wsdl");
	$data = '['.json_encode($val).']';
	$insert_parameter->DATA=$data;
	try{
		if ($fileSize<=20971520) {
		$save_result=$client->RR_REQUESTSUBMIT($insert_parameter)->RR_REQUESTSUBMITResult;
		}else{
			echo "size";
		}
	}
	catch(SoapFault $fault){
		echo "Fault code:{$fault->faultcode}".NEWLINE;
		echo "Fault string:{$fault->faultstring}".NEWLINE;
		if ($client != null)
		{
			$client=null;
		}
		exit();
	}
	$soapClient = null;
			
	   if ($_FILES['desfile']['tmp_name'][0] =="") {
			echo $save_result;
	   }else{
			$result = json_decode($save_result);
			if ($result->Success==1) {
				foreach ($_FILES['desfile']['tmp_name'] as $key => $tmp_name) {
				$imgfile1 = $_FILES['desfile']['tmp_name'][$key];
				$ftp = ftp_connect($ftp_server_159);
				$login = ftp_login($ftp, $ftp_user_name_159, $ftp_user_pass_159);
				$dir1 = "CALL_CENTRE/REQUESTS/".$result->Code."_".$result->Name."/";
				
				$folder_exists = is_dir($dir1);
					if($folder_exists) { }
					else {
						ftp_mkdir($ftp, $dir1);
					}
				$upload_img1=$_FILES['desfile']['name'][$key];
				$source = $imgfile1;
				$original_complogos1 = "uploads/feedimage/".$upload_img1;
				move_uploaded_file($source, $original_complogos1);
				
				$local_file = "uploads/feedimage/".$upload_img1;
				$server_file = $dir1.$upload_img1;
				if ((!$conn_id) || (!$login_result)) {
					$upload = ftp_put($ftp, $server_file, $local_file, FTP_BINARY); 
					unlink($local_file);
				}
				}  //foreach
				
				if ($upload ==1) {
					$update_parameter->RNUM=$result->Code;  //request Num
					$update_parameter->RSRNO=$result->Name;  //request Srno
					try{
						$update_result=$client->RR_UPDPATH($update_parameter)->RR_UPDPATHResult;
						}
					catch(SoapFault $fault){
							echo "Fault code:{$fault->faultcode}".NEWLINE;
							echo "Fault string:{$fault->faultstring}".NEWLINE;
							if ($client != null)
							{
								$client=null;
							}
							exit();
					}
					$soapClient = null;
					echo $update_result;
					}  //if $upload
			}  //if $result->Success
		} //else	

	} //mode

if($_GET['mode']=='chat')
	{
		
                	$client = new SoapClient("http://www.tcstextile.in/tcs_service/tcsservice.asmx?Wsdl");
                	$complaint_parameter->LOGINID=$_SESSION['tcs_usrcode'];
                	$complaint_parameter->PAGE=20;
					try{
						$complaint_result=$client->App_Complaint_History($complaint_parameter)->App_Complaint_HistoryResult;
					 }
					catch(SoapFault $fault){
						echo "Fault code:{$fault->faultcode}".NEWLINE;
						echo "Fault string:{$fault->faultstring}".NEWLINE;
						if ($client != null)
						{
							$client=null;
						}
						exit();
					}
					$soapClient = null;
					$complaint =  json_decode($complaint_result,true);
					?>
					<input type="hidden" name="total_chat" id="total_chat" value="<? echo count($complaint)?>">
					<?
					$i=0;				
					 if(count($complaint)>0){		
					 foreach ($complaint as $key => $com) { 
					 $i++;	
                	 
                	 //if($com['FEDSTAT']=='F'){ $status_color = "#017701";  }else{	$status_color = "#b79a06"; }?>
                	<div class="col-md-12" style="padding: 5px;">
                		<div id="chat1_left<?=$i?>" class="col-md-6" style="float: left;">
                		&nbsp;<?=$com['FEDDATE']?><br>	
                		&nbsp;<span id="chat_name1<?=$i?>">TCS Team</span>
                		<div id="content_box1<?=$i?>" style="background-color: #017701;color: #fff;border-radius: 10px;padding: 8px;">
                		<? if ($com['FIMG_PATH'] != "NIL") { ?>
                		<center><img target="_blank" src="data:image/png;base64,<?=$com['FIMG_PATH']?>" style="width: 150px;height: 115px;"></center>
                		<? } ?>
                		<p>&nbsp;&nbsp;Ref No:<?=$com['REFRNUM']?></p>
                		<p>&nbsp;&nbsp;Status:<? if($com['FEDSTAT']=='F'){ ?> Closed Successfully <? }else{?> Processing <? } ?></p>	               			
                		<p>&nbsp;&nbsp;<?=$com['FEDCONT']?></p>
                		</div>
                		</div>
                		<div id="chat1_right<?=$i?>" class="col-md-6"></div>
                	</div>
                	<!-- You -->
                	<div class="col-md-12">
                		<div id="chat2_left<?=$i?>" class="col-md-6"></div>
                		<div id="chat2_right<?=$i?>" class="col-md-6" style="float: right;">
                		&nbsp;<?=$com['REPDATE']?><br>		
                		&nbsp;<span id="chat_name2<?=$i?>">You</span>
                		<div id="content_box2<?=$i?>" style="background-color: #c1c3c1;color: black;border-radius: 10px;padding: 8px;min-height: 140px;">
                		<? if ($com['REPIMG'] != "") { ?>
                		<center><img target="_blank" src="data:image/png;base64,<?=$com['REPIMG']?>" style="width: 150px;height: 115px;"></center>
                		<? } ?>
                		<p>&nbsp;&nbsp;Ref No:<?=$com['REFRNUM']?></p>
                		<p>&nbsp;&nbsp;<?=$com['REPMESG']?></p>
                		<p>&nbsp;&nbsp;<? if($com['FEDSTAT']=='F'){ ?> Closed Successfully <? }else{?>Processing<?}?></p>
                		<div class="col-xs-12" style="padding: 0px;">
                		<div class="col-xs-8" style="float: left;padding: 0px;">               			
                		 <p>If You Not statisfied Commend Here:</p>
                		 </div>
                		 <div class="col-xs-4" style="float: right;padding: 0px;">
                		 <button type="button" id="reopen" name="reopen" class="btn btn-danger" onclick="reopen_cmd(2018080100001,1);"><i class="fa fa-commenting"></i>&nbsp;Repoen</button>
                		 </div>
                		 </div>
                		</div>
                		</div>
                	</div>
					<? } 
					}else{ ?>
						<center><h3 style="color: red;">No Records in this User...!</h3></center>
					<? } 	
	}

if($mode=='REOPEN')
	{ ?>
		hello		
<?	}	


if($_GET['mode']=='supplier')
	{		
		echo 'supplier details';
	 	$result = select_query("select supcode,supname,supcode||' - '||supname as SUP
						FROM supplier where deleted = 'N' and
						 (supname like '%".strtoupper($_GET['q'])."%' or supcode like '".strtoupper($_GET['q'])."%'  ) 
						order by supcode,supname"); 

			$val = array();
				foreach($result as $res)
				{
					 $val[] = array('id'=>$res['SUPCODE'], 'text'=>$res['SUP']);
				}
			echo json_encode($val);
			
	}
?>