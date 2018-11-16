<?php
error_reporting(0);
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');
extract($_REQUEST);

//Product Auto Complete
if($_GET['mode'] == 'product'){
    $result = select_query_json("SELECT Prd.PRDCODE, Prd.PRDNAME, Sec.Seccode, Sec.Secname, br.Brdcode, br.Brdname
									FROM product Prd, section Sec, brand br
									where Sec.Seccode = Prd.Seccode and Prd.Brdcode = br.Brdcode and ( Prd.PRDNAME LIKE '".strtoupper($_GET['name_startsWith'])."%' or 
										Prd.PRDCODE LIKE '".strtoupper($_GET['name_startsWith'])."%' ) and  Sec.seccode in (".$_SESSION['tcs_section'].") and Prd.PRDNAME Not LIKE '%TWINKLE%' and Prd.PRDNAME Not LIKE '%STAR%' and Prd.PRDNAME Not LIKE '%OLD%' and Prd.PRDNAME Not LIKE '%CODE%' and Prd.deleted = 'N' 
									order by Prd.PRDNAME Asc", "Centra", 'TCS');

    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi][0]." - ".$result[$rowi][1].", ".$result[$rowi][3]);
    }    
    echo json_encode($data);
}

//Brand Auto Generate
if($_REQUEST['mode'] == 'brand'){
    $result = select_query_json("SELECT Prd.PRDCODE, Prd.PRDNAME, Sec.Seccode, Sec.Secname, br.Brdcode, br.Brdname
									FROM product Prd, section Sec, brand br
									where Sec.Seccode = Prd.Seccode and Prd.Brdcode = br.Brdcode and Prd.Prdcode in ('".$_REQUEST['subtype_id']."') and Prd.PRDNAME Not LIKE '%TWINKLE%' and Prd.PRDNAME Not LIKE '%STAR%' and Prd.PRDNAME Not LIKE '%OLD%' and Prd.PRDNAME Not LIKE '%CODE%' and Prd.deleted = 'N' 
									order by Prd.PRDNAME Asc", "Centra", 'TCS');

									echo $result[0][4]."~".$result[0][5];
}


//Select Shop Auto Filling Targetno And Targetvalue
if($_GET['type']=='tp_target_fix'){
	$sql_year=select_query_json("select PORYEAR from trandata.codeinc@tcscentr", "Centra", 'TCS');

	$result = select_query_json("select TPTARNO,TPTARVAL,TPSCODE,TPTARYR FROM TP_TARGET_FIX WHERE TPSCODE='".$_REQUEST['subtype_id']."' and TPTARYR='".$sql_year[0]['PORYEAR']."'", "Centra", 'TCS');

	echo $result[0][0]."~".$result[0][1]."~".$result[0][2]."~".$result[0][3]; 
}

if($_GET['type']=='tp_target_balance'){
	$sql_year=select_query_json("select PORYEAR from trandata.codeinc@tcscentr", "Centra", 'TCS');

   $result = select_query_json("Select DISTINCT FIX.TPSCODE,sum(oth.TPTRATE) PURATE,sum(oth.TPTNETT) STPTNETT,ent.TPTDATE,fix.TPTARVAL,fix.TPTARYR,sh.TPSNAME,FIX.TPTARMON,fix.TPTARNO,cty.CTYNAME,ent.ADDUSER
    from Tp_Target_Fix fix, Tp_Target_Entry ent,Tp_Product_Details tcs, Tp_Shop_Details oth, Employee_Office emp, Userid usr, Section sec, City cty, Tp_Shops sh 
    where fix.TPTARYR=ent.TPTARYR and tcs.TPTARYR=oth.TPTARYR and fix.TPTARNO=ent.TPTARNO and ent.TPTARID=tcs.TPTARID and ent.TPTARID=oth.TPTARID and tcs.TPTARID = oth.TPTARID and tcs.TPTSRNO = oth.TPTSRNO and usr.EMPSRNO=emp.EMPSRNO and ent.ADDUSER=usr.USRCODE and tcs.SECCODE=sec.SECCODE and oth.SECCODE=sec.SECCODE and cty.CTYCODE = fix.CTYCODE and sh.TPSCODE = fix.TPSCODE and emp.EMPSRNO in ('".$_SESSION['tcs_userid']."') and FIX.TPSCODE='".$_REQUEST['subtype_id']."' and FIX.TPTARMON='".date("m")."' and fix.TPTARYR='".$sql_year[0]['PORYEAR']."' 
    GROUP BY FIX.TPSCODE,sh.TPSNAME,fix.TPTARYR,FIX.TPTARMON,ent.TPTDATE,fix.TPTARNO,fix.TPTARVAL,cty.CTYNAME,ent.ADDUSER order by ent.TPTDATE", "Centra", 'TCS'); 

	if($result[0][1]!=Null){
	$i=0;
    foreach($result as $show) { $i++;
		$puramt += $show[2];?>
	<div class="col-md-12" id="calculatediv" style=' text-transform:uppercase;padding-bottom:20px;'>
	    <div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>PREVIOUS TP AMT</label>
    	<input type="text" name="target_pr_amt[]"  id="target_pr_amt" readonly value="<?echo $show[2];?>" class="form-control" placeholder="TP PR AMOUNT">
		</div>
		<div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>TP BALANCE AMT</label>
		<input type="text" name="target_balance[]"  id="target_balance_<?echo $i;?>" readonly value="<?echo $show[4]-$puramt;?>"   class="form-control" placeholder="TP BALANCE AMT">
		</div>
		<div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>PREVIOUS TP DATE</label>
		<input type="text" name="target_date[]"  id="target_date" readonly value="<?echo $show[3]; ?>"  class="form-control" placeholder="Date">
		</div>
	</div>
	<?}
	}else{
	$sql_year=select_query_json("select PORYEAR from trandata.codeinc@tcscentr", "Centra", 'TCS');
	$resultfix = select_query_json("Select DISTINCT FIX.TPSCODE,fix.TPTARVAL,fix.TPTARYR,sh.TPSNAME,FIX.TPTARMON,fix.TPTARNO,cty.CTYNAME
    from Tp_Target_Fix fix, Employee_Office emp, Userid usr, City cty, Tp_Shops sh 
    where usr.EMPSRNO=emp.EMPSRNO and cty.CTYCODE = fix.CTYCODE and sh.TPSCODE = fix.TPSCODE and emp.EMPSRNO in ('".$_SESSION['tcs_userid']."') and FIX.TPSCODE='".$_REQUEST['subtype_id']."' and fix.TPTARYR='".$sql_year[0]['PORYEAR']."' GROUP BY FIX.TPSCODE,sh.TPSNAME,fix.TPTARYR,FIX.TPTARMON,fix.TPTARNO,fix.TPTARVAL,cty.CTYNAME", "Centra", 'TCS');
	?>
	<div class="col-md-12" id="calculatediv" style=' text-transform:uppercase;padding-bottom:20px;'>
	    <div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>PREVIOUS TP AMT</label>
    	<input type="text" name="target_pr_amt[]"  id="target_pr_amt" readonly value="<?echo 0;?>" class="form-control" placeholder="TP PR AMOUNT">
		</div>
		<div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>TP BALANCE AMT</label>
		<input type="text" name="target_balance[]"  id="target_balance_1" readonly value="<?echo $resultfix[0][1];?>"   class="form-control" placeholder="TP BALANCE AMT">
		</div>
		<div class="col-sm-2"> &nbsp; </div>
		<div class="col-sm-1">
		<label>PREVIOUS TP DATE</label>
		<input type="text" name="target_date[]"  id="target_date" readonly value="<?echo "-"; ?>"  class="form-control" placeholder="Date">
		</div>
	</div>
	<?
	}

	?>
	<input type="hidden" name="total"  id="total" readonly value="<?if(count($result)==0){echo 1;}else{echo count($result);}?>"   class="form-control" placeholder="TP BALANCE AMT">
	<?
}

//view Shop Name DropDown
if($_GET['mode']=='tp_shop'){

 $sql_section = select_query_json("Select TPSCODE,CTYCODE,TPSNAME from Trandata.TP_SHOPS@Tcscentr where CTYCODE='".$_REQUEST['subtype_id']."' order by TPSNAME ASC", "Centra", 'TCS');
 ?>
        <option value=""> Select TP Shop </option>
	<?	foreach($sql_section as $sectionrow) { ?>
			<option value="<?=$sectionrow['TPSCODE']?>" <? if($_REQUEST['shop_name'] == $sectionrow['TPSCODE']) { ?> selected <? } ?>><?=$sectionrow['TPSNAME']?></option>
	<? }
}
//view Employee Auto Complete
if($_GET['mode'] == 'employee'){
    $result = select_query_json("select br.BRNCODE, emp.EMPCODE, emp.EMPNAME from trandata.employee_office@tcscentr emp, trandata.branch@tcscentr br
									where ( emp.empname LIKE '".strtoupper($_GET['name_startsWith'])."%' or emp.empcode LIKE '".strtoupper($_GET['name_startsWith'])."%' ) and br.BRNCODE in (001,888) and br.BRNCODE=emp.BRNCODE and emp.empcode>1000
									order by emp.EMPCODE Asc", "Centra", 'TCS');    
    $data = array();
	for($rowi = 0; $rowi < count($result);$rowi++) {
		array_push($data, $result[$rowi][1]." - ".$result[$rowi][2]);
    }
    echo json_encode($data);
}
?>