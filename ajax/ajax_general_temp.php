<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
$theme_view = "css/theme-default.css";
if(isset($_COOKIE['assign_theme'])) { $theme_view = $_COOKIE['assign_theme']; }

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

if($_REQUEST['action'] == "COLADD")
{?>
<table class="table table-bordered table-striped table-hover">
 	<thead>
 		
 		<label style="font-size: large;color: #9c27b0;font-weight: bolder;" class="fa fa-check"> Select Check Box in Header  If Total Need</label>  
 		<tr style="background-color:#1D5B69; color:#FFFFFF; text-transform: uppercase;">
	 		<th>#</th>
	 		<?
	 		$alphas = range('A', 'Z');
	 		for($i=0;$i<$cnt;$i++){?>
	 			<th><?=$alphas[$i]?>
	 				&emsp; ( <input type="checkbox" name="b[<?=$i+1?>]" id="b_<?=$i+1?>" value="Y"> )
	 			</th>
	 		<?}?>
 		</tr>
 	</thead>
 	<tbody>
 		<tr>
 			<td><input class="form-control" type="text" name="a[0]" id="a_0" value="SR.NO" style="text-transform: uppercase;min-width: 50px;width: 80px;text-align: -webkit-center;" readonly="readonly"></td>
 			<?	if($slt_approval_listings == 856) { $ii = 1; ?>
 					<td><input class="form-control" type="text" name="a[1]" id="a_1" value="Master Table" style="text-transform: uppercase;min-width: 50px;text-align: -webkit-center;" readonly="readonly"></td>
 			<? 	} else { $ii = 0; } ?>
 			<? for($i=$ii;$i<$cnt;$i++){ ?>
	 			<td><input class="form-control headval" type="text" name="a[<?=$i+1?>]" id="a_<?=$i+1?>" value="" placeholder="Name Of Header" required style="text-transform: uppercase;">
	 				<?
	 				switch ($i) {
	 					case 0:
	 						?>
	 						<label>Eg: Product</label>
	 						<?
	 						break;
	 					case 1:
	 						?>
	 						<label>Eg: Description</label>
	 						<?
	 						break;
 						case 2:
	 						?>
	 						<label>Eg: Rate</label>
	 						<?
	 						break;
 						case 3:
	 						?>
	 						<label>Eg: Qty</label>
	 						<?
	 						break;
 						case 4:
	 						?>
	 						<label>Eg: Value</label>
	 						<?
	 						break;
	 					default:
	 						break;
	 				}
	 				?>
	 			</td>
	 		<?}?>
 		</tr>

 		

 	</tbody>
 </table>


 <table class="table table-bordered table-striped table-hover">
 	<thead>
 		<tr> <label style="font-size: large;color: red;font-weight: bolder;" class="blinking"> Add Calculation :  ( If need )</label> </tr>
 	</thead>
 	<tbody>
 		<tr>
 			<td>
 				<label style="font-weight: bolder;font-size: medium;">Select Head For Calculation:</label> <br>
 				<label style="color: red;font-weight: bolder;">Click to Select multiple</label><br> 
 				<select multiple style='' class="form-control custom-select chosn select2" name='slt_head' id='slt_head'> 	
 				<?
 				$alphas = range('A', 'Z');
 				for($i=0;$i<$cnt;$i++){?>
	 			<option value="<?=$i+1?>" style="text-align: -webkit-center;height: 25px;font-size: large;"><?=$alphas[$i]?></option>
	 			<?}?>
 				</select>
 			</td>
 			<td>
 				<label  style="font-weight: bolder;font-size: medium;">Select Operator : </label>
 				 
 				<div class="content">
 					<div class="col-md-3">
 						 <input type="radio" name="slt_cal" id="slt_cal" value="A"><label style="font-size: 20px;padding: 5px;" class="fa fa-plus"></label> 
					</div><div class="col-md-3">  
 						 <input type="radio" name="slt_cal" id="slt_cal" value="S"><label style="font-size: 20px;padding: 5px;" class="fa fa-minus"></label> 
 					</div>

 					<div class="col-md-3">

 						<input type="radio" name="slt_cal" id="slt_cal" value="M" checked="checked" > <label style="font-size: 20px;padding: 5px;" class="  fa fa-close"></label>  
					</div><div class="col-md-3">  
 						 <input type="radio" name="slt_cal" id="slt_cal" value="D"><label style="font-size: 2em;padding: 5px;font-weight: bolder;margin-top: -5px;"> % </label> 
 						
 					</div>
 					
 				</div>
 			</td>
 			<td>
 				<label  style="font-weight: bolder;font-size: medium;">Select Result head :</label> <br><br> 
 				<select  style='' class="form-control custom-select chosn select2" name='slt_res_head' id='slt_res_head'> 	
 				<?
 				$alphas = range('A', 'Z');
 				for($i=$cnt;$i>0;$i--){?>
	 			<option value="<?=$i?>"><?=$alphas[$i-1]?></option>
	 			<?}?>
 				</select>
 			</td>
 			<td>
 				<input type="button" name="addcal" id="addcal" Value="Add Calculation" onclick="Add_cal();" Class="btn btn-primary">
 			</td>
 		</tr>
 		
 	</tbody>
 </table>
<?}elseif($_REQUEST['action'] == "COLINSERT")
{
	$c =explode(',', $cal_head);
	$c_table = "approval_general_master";
	$currentdate = strtoupper(date('d-M-Y h:i:s A'));				
	$sql_temp = select_query_json("select nvl(max(TEMPID),0)+1 as CNT from approval_general_master", "Centra", 'TEST');
	for($i = 0; $i < sizeof($col);$i++) 
	{
		$currentdate = strtoupper(date('d-M-Y h:i:s A'));				
		$c_fld = array();
		$c_fld['TEMPID']  = $sql_temp[0]['CNT'];
		$c_fld['APMCODE'] = $apmcode;
		$c_fld['COLSRNO'] = $i+1;
		$c_fld['COLDET']  = strtoupper($col[$i]);
		$c_fld['ADDUSER'] = $_SESSION['tcs_usrcode'];
		$c_fld['ADDDATE'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
		$c_fld['DELETED'] = "N"; 
		$c_fld['COLTYPE'] = strtoupper($check[$i]);
		
		if($alw_cal == 1)
		{
			$frm_cnt = "";
		for($m=0;$m<sizeof($c);$m++)
		{
			$frm_cnt .=($c[$m]+1)."~";
			if($c[$m] == $i )
			{
				$c_fld['CALMODE'] = $cal_opt; 		
			}
		}

		if($i == $res_head)
		{
			$c_fld['CALMODE'] = $cal_opt;
		}

		$frm_cnt = rtrim($frm_cnt,'~');
		$c_fld['CALFILD'] = $frm_cnt; 
		$c_fld['CALRES']  = $res_head+1; 
	    }else{
	    	$c_fld['CALMODE'] = ""; 
	    	$c_fld['CALFILD'] = ""; 
			$c_fld['CALRES']  = ""; 		
	    }

	     
	    
		$c_insert = insert_dbquery($c_fld,$c_table);
	}
	echo $sql_temp[0]['CNT'];

}elseif ($_REQUEST['action'] == "LOADVIEW") {
	$sql_view = select_query_json("select * from approval_general_master where tempid=".$cnt." and colsrno not in 1 order by colsrno", "Centra", 'TEST'); ?>
		<input type="button" name="backcol" id="backcol" value="BACK" style="text-align: right;" class="btn btn-primary" onclick="back_col();">
	<? if($apmcode == 856) { ?>
		<input type="button" name="get_table_master" id="get_table_master" value="TABLE MASTER" class="btn btn-success" onclick="get_tablemaster();">
	<? } ?>
<table class="table table-bordered table-striped table-hover" id="foo">
 	<thead>
 		<tr style="background-color:#1D5B69; color:#FFFFFF; text-transform: uppercase;">
	 		<th style="min-width: 50px;width: 100px;"> SR.NO 
	 		<label>
	 			<i class="fa fa-plus-circle" style="font-size: 20px;" onclick="add_gen_row();"></i>
	 			<i class="fa fa-minus-circle" style="font-size: 20px;" onclick="remove_gen_row();"></i>
	 		</label>	
	 		</th>
	 		<?
	 		$cal_val = array();
	 		foreach ($sql_view as $col) {?>
	 		<th><?=$col['COLDET']?></th>	
	 		<?}?>
	 	</tr>
 	</thead>
 	<tbody class="parts3">
 		<input type="hidden" name="partint3" id="partint3" value="1">
 		<tr class="part3">
 			<td style="vertical-align: middle;text-align: -webkit-center;">1</td>
 			<input type="hidden" name="a[1][]" id="a_1_1" value="1">
 			<?	$peralw=0;
 				foreach($sql_view as $col){
 				if($col['CALMODE'] == "D")
 				{
 					$peralw=1;
 				}
 				?>
 				<td>
					<?if($col['CALRES'] == $col['COLSRNO'] and $peralw == 1){?>
					<div class="form-group">
					<div class="input-group">
						<input  maxlength="100"  type="number" style="text-transform: uppercase;text-align: right;
			  		margin-top: 0px;"   name="a[<?=$col['COLSRNO']?>][]" 
			  		id="a_<?=$col['COLSRNO']?>_<?=$row+1?>" 
			  		class="form-control gen_row_<?=$row+1?> ttlsum_gen_<?=$col['COLSRNO']?>" value=""  readonly="readonly">
					
						<div class="input-group-addon">
							%
						</div>
	                </div>
				 	</div>
					  <?}else{?>
					  	<input maxlength="100"  <? if($col['CALMODE'] != ""){?> type="number" style="text-transform: uppercase;text-align: right;margin-top: 0px;" onchange="gen_calculation('<?=$col['CALFILD']?>','<?=$col['CALMODE']?>','<?=$col['CALRES']?>','<?=$row+1?>','<?=$col['COLSRNO']?>')" <?}else{?> type="text" style="text-transform: uppercase;margin-top: 0px;" <?}?> name="a[<?=$col['COLSRNO']?>][]" id="a_<?=$col['COLSRNO']?>_<?=$row+1?>" class="form-control gen_row_<?=$row+1?> ttlsum_gen_<?=$col['COLSRNO']?>" value="" <?if($col['CALRES'] == $col['COLSRNO']){?> readonly="readonly" <?}?>>
					  <?}?>
				 </td>
 			<?}?>
 		</tr>
 	</tbody>
 		<? if($col['CALRES'] != ""){?>
 		<tr style="background: #1d5b69;color: white;font-size: medium;text-align: -webkit-center;">
 			<td> Total </td>
 			<?foreach($sql_view as $col){
 				if($col['COLTYPE'] == "Y"){?>
 				<td style="text-align: right;padding-right: 15px;" id="tot_<?=$col['COLSRNO']?>"></td>
			<?}else{?>
				<td></td>
			<?}}?>
		</tr>
		<?}?>
 </table>

<?
}elseif ($_REQUEST['action'] == "ADDROW") {
	$sql_view = select_query_json("select * from approval_general_master where tempid=".$cnt." and colsrno not in 1 order by colsrno", "Centra", 'TCS');?>
	<tr class="part3">
	<td style="vertical-align: middle;text-align: -webkit-center;"><?=$row+1?></td>
		<input type="hidden" name="a[1][]" id="a_1_<?=$row+1?>" value="<?=$row+1?>">
		<?
		$peralw=0;
		foreach($sql_view as $col){
			if($col['CALMODE'] == "D")
 				{
 					$peralw=1;
 				}
			?>
			<td>
				<?if($col['CALRES'] == $col['COLSRNO'] and $peralw == 1){?>
				<div class="form-group">
				<div class="input-group">
					<input maxlength="100"  type="number" style="text-transform: uppercase;text-align: right;
			  		margin-top: 0px;"   name="a[<?=$col['COLSRNO']?>][]" 
			  		id="a_<?=$col['COLSRNO']?>_<?=$row+1?>" 
			  		class="form-control ttlsum_gen_<?=$col['COLSRNO']?>" value=""  readonly="readonly">
				
					<div class="input-group-addon">
						<code id="avgrate">%</code>
					</div>
                </div>
            	</div>
			  	<?}else{?>
			  		<input maxlength="100" <? if($col['CALMODE'] != ""){?> type="number" style="text-transform: uppercase;text-align: right;margin-top: 0px;" onchange="gen_calculation('<?=$col['CALFILD']?>','<?=$col['CALMODE']?>','<?=$col['CALRES']?>','<?=$row+1?>','<?=$col['COLSRNO']?>')" <?}else{?> type="text" style="text-transform: uppercase;margin-top: 0px;" <?}?>  name="a[<?=$col['COLSRNO']?>][]" id="a_<?=$col['COLSRNO']?>_<?=$row+1?>" class="form-control ttlsum_gen_<?=$col['COLSRNO']?>" value="" <?if($col['CALRES'] == $col['COLSRNO']){?> readonly="readonly" <?}?>>
			  	<?}?>
			</td>
		<?}?>
	</tr>
<? }

elseif ($_REQUEST['action'] == "SENDMAIL") { ?>
	<div><label class="fa fa-comments-o" style="font-size: large;font-weight: bolder;">&emsp;Add Comment & Send Mail</label></div>
	<div class="comment-frame">
		<div class="comment-box">
			<textarea class="comment-box-input" tabindex="1" 
			onfocus="this.placeholder = ''"
			onblur="this.placeholder = 'Write a Comment ...'" placeholder="Write a Comment ..." 
			dir="auto" style="overflow: hidden; word-wrap: break-word; height: 75px;
			border: none;color:black;outline: none;font-size:initial;" name="txtmailcnt" id="txtmailcnt">
			</textarea>
		<div class="comment-box-options">
			<a class="comment-box-options-item" style="padding-bottom: 20px;" onclick="cmt_usr();" title="Add an Member" 
			onmouseover="this.style.backgroundColor='white';"
			onmouseout="this.style.backgroundColor='#f0eeee'">
				<? /* <i class="fa fa-at" style="height: 18px;font-size: 20px;line-height: 18px;width: 19px;color: #9E9E9E;"></i> */ ?>
				&#64;
			</a>
		</div>
	</div>
	</div>
	<div class="row" style="display:none" id="cmtusr">
	<div class="col-md-6"> 

			<select multiple class="form-control select2" id="mailusr" name="mailusr">
				<option> Select User</option>
				<?
		/*$sql_mail = select_query_json("select emp.empname,mail.emailid from approval_mdhierarchy@tcscentr hir,employee_office@tcscentr emp,approval_email_master@tcscentr mail
where hir.apphead=emp.empcode and emp.empsrno=mail.empsrno and 
hir.aprnumb in '".$aprnumb."' order by amhsrno desc", "Centra", 'TCS');
			if(count($sql_mail)>0){
			foreach ($sql_mail as $mail) {*/?>
		<!-- 	<option value="<?=$mail['EMAILID']?>"><?=$mail['EMPNAME']."(".$mail['EMAILID'].")"?></option> -->
			<?/*}}else{*/?>
			<!-- <option value=""> No Mail User Found ,Can't send mail..!</option> -->
			<?/*}*/?>		 
			</select>
	</div>
	<div class="col-md-6"> 
		<input type="button" class="btn btn-primary" name="btnmail" id="btnmail" value="SEND" onclick="add_mail('<?=$aprnumb?>');">
	</div>
	</div>
<?
}

elseif ($_REQUEST['action'] == "MAILINSERT") {
//$apprno = "ADMIN / INFO TECH 4006860 / 05-01-2018 / 6860 / 10:12 AM";
$sql_emp = select_query_json("select emp.empcode,emp.empname,'The Chennai Silks - '||substr(brn.nicname,3) brnname from employee_office@tcscentr emp,branch@tcscentr brn where emp.brncode=brn.brncode and emp.empsrno=".$_SESSION['tcs_empsrno']."", "Centra", 'TCS');
$mailcnt = "<html> <body>  <table border=0 cellpadding=1 cellspacing=1 width=100%>  <tr><td height=25 align=left colspan=2>Dear Sir/Madam.,</td></tr>  <BR><BR><BR><tr><td height=25 align=left colspan=2>".$content."</td></tr>  <tr height=25></tr>  <tr><td colspan=2>  <BR><BR><b>Thanks & Regards,</b>  <BR>".$sql_emp[0]['EMPCODE']."-".ucwords(strtolower($sql_emp[0]['EMPNAME']))." </BR>".$sql_emp[0]['BRNNAME']."</b></td></tr>  </table>  </body>  </html>";

$m_tbl = "mail_send_summary";
$s_tbl = "approval_mail_comments";
$year = select_query_json("select poryear from Codeinc@tcscentr", "Centra", 'TCS');
for ($i=0; $i <sizeof($mailusr); $i++) { 
	$mailid .= $mailusr[$i].",";
}
	$mailid = rtrim($mailid,',');
	$sql_mcnt = select_query_json("select nvl(max(mailnumb),0)+1 mailnumb from mail_send_summary where mailyear='".$year[0]['PORYEAR']."' ", "Centra", 'TCS');
	$m_fld = array();
	$m_fld['MAILYEAR'] = $year[0]['PORYEAR'];
	$m_fld['MAILNUMB'] = $sql_mcnt[0]['MAILNUMB'];
	$m_fld['DEPTID']   = "1";
	$m_fld['MAILSUB']  = strtoupper($apprno);
	$m_fld['MAILCON']  = $mailcnt;
	$m_fld['FILECNT']  = "0";
	$m_fld['ADDUSER']  = $_SESSION['tcs_usrcode'];
	$m_fld['ADDDATE']  = 'dd-Mon-yyyy HH:MI:SS AM~~'.strtoupper(date('d-M-Y h:i:s A'));
	$m_fld['EMAILID']  = $mailid;
	$m_fld['STATUS']   = "N";
	$m_fld['DEPNAME']  = "APP DESK";
	$m_insert = insert_dbquery($m_fld,$m_tbl);
	if($m_insert == 1)
	{
		$sql_scnt = select_query_json("select nvl(max(MAILSRNO),0)+1 MAILSRNO from approval_mail_comments where mailyear='".$year[0]['PORYEAR']."' and aprnumb in ('".$apprno."') ", "Centra", 'TEST');
		$s_fld = array();
		$s_fld['APRNUMB']  = $apprno;
		$s_fld['MAILYEAR'] = $year[0]['PORYEAR'];
		$s_fld['MAILSRNO'] = $sql_scnt[0]['MAILSRNO'];
		$s_fld['MAILNUMB'] = $sql_mcnt[0]['MAILNUMB'];
		$s_fld['MAILCON']  = $mailcnt;
		$s_insert = insert_dbquery($s_fld,$s_tbl);
	}

}
if($_REQUEST['action'] == "MAILUSER")
{
	 	$result = "select emp.empname,mail.emailid from employee_office@tcscentr emp,approval_email_master@tcscentr mail
				where  emp.empsrno=mail.empsrno and (emp.empname like '".strtoupper($_GET['q'])."%' or emp.empcode like '".strtoupper($_GET['q'])."%')order by empname ";
		$client = new SoapClient("http://172.16.0.166:8080/cdata.asmx?Wsdl"); 
		$get_parameter->Qry_String=$result;
		try{
			$get_result=$client->Get_Data_xml($get_parameter)->Get_Data_XMLResult;
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
			$result =  json_decode($get_result,true);
	$val = array();
		foreach($result as $res)
		{
			 $val[] = array('id'=>$res['EMAILID'], 'text'=>$res['EMPNAME']."(".$res['EMAILID'].")");
		}
	echo json_encode($val);
				
}








