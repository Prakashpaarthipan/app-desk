<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);
$current_year = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year

if($_SESSION['tcs_user'] == '') { ?>
	<script>window.location='../index.php';</script>
<?php exit();
}

if($_REQUEST['action'] == "transfer"){
	$empcode = explode(" - ", $empcode);
	$table1 = "empsection";
	$table2 = "designation";
	if($brncode == '201' or $brncode == '202' or $brncode == '203' or $brncode == '204' or $brncode == '205' or $brncode =='206')
	{
		$table1 = "new_empsection";
		$table2 = "new_designation";
	}

	$sql_employee= select_query_json("select emp.brncode,emp.descode,emp.esecode,emp.empsrno,emp.empcode,emp.empname,round((trunc(sysdate)-trunc(emp.dateofjoin))/365,1) as Exp,emp.dateofjoin ,brn.nicname,ese.esename,des.desname 
	from employee_office emp,".$table1." ese,".$table2." des,branch brn  
	where emp.esecode=ese.esecode and emp.descode=des.descode and emp.brncode=brn.brncode  and emp.empcode=".$empcode[0]." and rownum=1", "Centra", 'TCS');

	$sql_basic = select_query_json("select * from employee_salary where empsrno=".$sql_employee[0]['EMPSRNO']."", "Centra", 'TCS');
	$sql_com = select_query_json("select * from super_comm where empsrno=".$sql_employee[0]['EMPSRNO']."", "Centra", 'TCS');

	if($sql_com[0]['EMPCOMP'] != 0)
	{
		$comm = $sql_com[0]['EMPCOMP']; 
	}elseif($sql_com[0]['NETCOMM'] == "Y")
	{
		$comm = "NETCOMM";
	}else{
		$comm = 0;
	}

	$dept = preg_replace('/[0-9]+/', '', $sql_employee[0]['ESENAME']);
	$branch = preg_replace('/[0-9]+/', '', $sql_employee[0]['NICNAME']);
	?>
	<input type="hidden" name="hid_emp_<?=$id?>" id="hid_emp_<?=$id?>" value="<?=$sql_employee[0]['EXP']."~".$sql_employee[0]['DATEOFJOIN']."~".$branch."~".$dept."~".$sql_employee[0]['DESNAME']."~".$sql_basic[0]['BASIC']."~".$comm."~".$sql_employee[0]['BRNCODE']."~".$sql_employee[0]['ESECODE']."~".$sql_employee[0]['DESCODE']."~".$sql_employee[0]['EMPCODE']."~".$sql_employee[0]['EMPSRNO']?>">
	<img class="img" style="width:80px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$sql_employee[0]['EMPCODE']?>"  title="<? echo $sql_employee[0]['EMPNAME']; ?>">
	<? /* <img class="img" style="width:80px;height: 80px; " align="center" src="../profile_img.php?p=<?=$sql_employee[0]['EMPCODE']?>"  title="<? echo $sql_employee[0]['EMPNAME']; ?>"> */ ?>
	<?
}
elseif($_REQUEST['action'] == "GETDEPT"){
	if($brncode == "")
	{
		$brncode=$slt_brncode;
		$sql_brn = select_query_json("select * from branch where   brncode=".$brncode." ", "Centra", 'TCS');
		$brncode = $sql_brn[0]['NICNAME'];
	}

	$table = "empsection";
	 
	if (strpos($brncode,'TJ')!== false or strpos($brncode,'KTM')!== false ){
		$table = "new_empsection";
	}
	$sql_dep = select_query_json( " select * from ".$table." emp where deleted='N' order by esename ", "Centra", 'TCS');?>

	<select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWDEP[]' id='newdep_<?=$id?>' data-toggle="tooltip" data-placement="top"    title="Department">
	<? foreach($sql_dep as $dep)
	{
	$dept = preg_replace('/[0-9]+/', '', $dep['ESENAME']);
	?>
	<option value="<?=$dept?>"><?=$dept?></option>
	<?}?>	
	</select>
<?
}
elseif($_REQUEST['action'] == "GETDES"){
	if($brncode == "" or $brncode == "undefined")
	{
		$brncode=$slt_brncode;
		$sql_brn = select_query_json("select * from branch where   brncode=".$brncode." ", "Centra", 'TCS');
		$brncode = $sql_brn[0]['NICNAME'];
	}

	$table = "designation";
	if (strpos($brncode,'TJ')!== false or strpos($brncode,'KTM')!== false ){
		$table = "new_designation";
	}
	$sql_des = select_query_json( " select * from ".$table." emp where deleted='N' order by desname ", "Centra", 'TCS'); ?>
	<select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWDES[]' id='newdes_<?=$id?>' data-toggle="tooltip" data-placement="top"   title="designation" >
	<? foreach($sql_des as $des) {
		$desval = preg_replace('/[0-9]+/', '', $des['DESNAME']); ?>
		<option value="<?=$desval?>"><?=$desval?></option>
	<? } ?>
	</select>
<?
}
elseif($_REQUEST['action'] == "GETBRN"){?>
	<select class="form-control custom-select chosn" tabindex='1' autoFocus required name='NEWBRN[]' id='newbrn_<?=$id?>' data-toggle="tooltip" data-placement="top"    title="Branch" onchange="getdept(<?=$id?>);">
		    			 	<option value=""> BRANCH</option>
	<? 	if($_SESSION['rights'] == 1) {
		$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn 
													where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and (brn.brncode in (select distinct brncode 
														from budget_planner_head_sum) or brn.brncode in (109,114,117,119)) 
													order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
	} elseif(count($allow_branch) <= 5) {
		$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn 
													where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and brn.brncode in (".$_SESSION['tcs_allowed_branch'].") 
														and (brn.brncode in (select distinct brncode from budget_planner_head_sum) 
														or brn.brncode in (109,114,117,119)) 
													order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
	} else {
		$sql_project = select_query_json("select brn.*, regexp_replace(SubStr(nicname,1,4),'[0-9]','')||SubStr(nicname,5,10) branch from branch brn 
													where brn.DELETED = 'N' and brn.BRNMODE in ('B', 'K') and (brn.brncode in (select distinct brncode 
														from budget_planner_head_sum) or brn.brncode in (109,114,117,119)) 
													order by brn.BRNCODE", "Centra", 'TCS'); // 108 - TRY Airport Not available
	}
	for($project_i = 0; $project_i < count($sql_project); $project_i++) { ?>
		<option value='<?=$sql_project[$project_i]['BRANCH']?>'><?=$sql_project[$project_i]['BRANCH']?></option>
<? } ?>
</select>
<?
}



if($_REQUEST['action'] == 'marriagegift')
{
	$empcode = explode(" - ", $empcode);
	$table1 = "empsection";
	$table2 = "designation";
	if($brncode == '201' or $brncode == '202' or $brncode == '203' or $brncode == '204' or $brncode == '205' or $brncode =='206')
	{
		$table1 = "new_empsection";
		$table2 = "new_designation";
	}

	$sql_employee= select_query_json("select emp.brncode, emp.descode, emp.esecode, emp.empsrno, emp.empcode, emp.empname, round((trunc(sysdate)-trunc(emp.dateofjoin))/365,1) as Exp, 
												emp.dateofjoin , brn.nicname, ese.esename, des.desname 
											from employee_office emp, ".$table1." ese, ".$table2." des, branch brn  
											where emp.esecode=ese.esecode and emp.descode=des.descode and emp.brncode=brn.brncode and emp.empcode=".$empcode[0]." and rownum=1", "Centra", 'TCS');
	$dept = preg_replace('/[0-9]+/', '', $sql_employee[0]['ESENAME']);
	$branch = preg_replace('/[0-9]+/', '', $sql_employee[0]['NICNAME']);
	$sql_gift = select_query_json("select * from approval_staff_marriage_master 
											where descode=".$sql_employee[0]['DESCODE']." and expto > ".$sql_employee[0]['EXP']." and expfrom <= ".$sql_employee[0]['EXP']." ", "Centra", 'TEST'); ?>
	<input type="hidden" name="hid_emp_<?=$id?>" id="hid_emp_<?=$id?>" value="<?=$sql_employee[0]['EXP']."~".$sql_employee[0]['DATEOFJOIN']."~".$branch."~".$dept."~".$sql_employee[0]['DESNAME']."~".$sql_gift[0]['OWNGIFT']."~".$sql_gift[0]['TRUSTAMT']."~".$sql_employee[0]['EMPSRNO']?>">
	<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$sql_employee[0]['EMPCODE']?>"  alt = "<? echo $sql_employee[0]['EMPNAME']; ?>" title="<? echo $sql_employee[0]['EMPNAME']; ?>">
	<?
}
if($_REQUEST['action'] == 'night')
{
	$empcode = explode(" - ", $empcode);
	$table1 = "empsection";
	$table2 = "designation";
	if($brncode == '201' or $brncode == '202' or $brncode == '203' or $brncode == '204' or $brncode == '205' or $brncode =='206')
	{
		$table1 = "new_empsection";
		$table2 = "new_designation";
	}

	$sql_employee = select_query_json("select emp.brncode, emp.descode, emp.esecode, emp.descode, emp.empsrno, emp.empcode, emp.empname, round((trunc(sysdate)-trunc(emp.dateofjoin))/365,1) as Exp, 
											emp.dateofjoin, brn.nicname, ese.esename, des.desname 
										from employee_office emp, ".$table1." ese, ".$table2." des, branch brn  
										where emp.esecode=ese.esecode and emp.descode=des.descode and emp.brncode=brn.brncode and emp.empcode=".$empcode[0]." and rownum=1", "Centra", 'TCS');
	if(count($sql_employee)>0){
		$dept = preg_replace('/[0-9]+/', '', $sql_employee[0]['ESENAME']);
		$branch = preg_replace('/[0-9]+/', '', $sql_employee[0]['NICNAME']); ?>
			<input type="hidden" name="hid_emp_<?=$id?>" id="hid_emp_<?=$id?>" value="<?=$sql_employee[0]['EXP']."~".$sql_employee[0]['DATEOFJOIN']."~".$branch."~".$dept."~".$sql_employee[0]['DESNAME']."~".$sql_employee[0]['DESCODE']."~".$sql_employee[0]['ESECODE']."~".$sql_employee[0]['EMPSRNO']?>">
			<img class="img" style="width:100px;height: 80px; " align="center" src="profile_img.php?profile_img=<?=$sql_employee[0]['EMPCODE']?>"  alt = "<? echo $sql_employee[0]['EMPNAME']; ?>" title="<? echo $sql_employee[0]['EMPNAME']; ?>"> 	
		<?
	} else {
		echo"0";
	} 
}
?>