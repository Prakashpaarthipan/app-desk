<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
include_once('../lib/function_connect.php');
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
$current_yr = select_query_json("Select Poryear From Codeinc", "Centra", 'TCS'); // Get the Current Year
//print_r($_REQUEST);
if($_REQUEST['action']=='load')
{
	$po_numb=explode(' - ',$_REQUEST['po_numb']);
	$po_year=$po_numb[0];
	$po_numb=$po_numb[1];
//echo("--".$po_numb."--");
 $sql_zne= select_query_json("select otm.znecode,otm.znename,sum(znedays) duedate,(select empcode||' - '||empname from employee_office eof where eof.empsrno=otm.empsrno) res_person
from order_tracking_detail otd,order_tracking_master otm 
where otd.poryear='".$po_year."' and otd.pornumb='".$po_numb."' and otd.deleted='N' and otm.deleted='N' and otd.znecode=otm.znecode and otd.znepcde=otm.znepcde 
group by otm.znecode,otm.znename,otm.empsrno order by znecode", "Centra", 'TEST'); 
 //echo("select otm.znecode,otm.znename,otm.znepcde,otm.ZNEPNME from order_tracking_detail otd,order_tracking_master otm where otd.poryear='".$po_year."' and otd.pornumb='".$po_numb."' and otd.deleted='N' and otm.deleted='N' and otd.znecode=otm.znecode and otd.znepcde=otm.znepcde group by otm.znecode,otm.znename,otm.znepcde,otm.ZNEPNME,otm.ZNCSRNO,otm.ZNPSRNO order by otm.ZNCSRNO,otm.ZNPSRNO");
 //print_r($sql_zne);
 $arr=array();
 foreach ($sql_zne as $key => $value) {
 	$temp=count($arr[$value['ZNECODE']]);
 	$arr[$value['ZNECODE']][$temp]=$value;
 }
 //echo('<pre>');
 //print_r($arr);
 ?>
 	<div class="row" style="margin-bottom: 10px;border-bottom: 1px solid black;">
 		<label class="switch" style="float: right;">
		      <input class="switch all_check" type="checkbox" checked="" name="all_check"  value="N"/>
			
		      <span></span>
		</label>
 		<label class="control-label text-left" style="float: right;font-size: 15px;margin-right: 10px;">All</label>
 	</div>
 <?foreach ($arr as $key => $value)
 {?>	
 	 <div class="form-group hover-box-def" style="box-shadow: 0 0 5px black;padding: 10px; border-radius: 10px;background-color: rgba(213, 213, 213, 1);transition: all 1.5s linear;">
 	 	<div class="row" >
 	 		<label class="col-md-3 control-label text-left"><?=$value[0]['ZNENAME'];?></label>
		    <label class="col-md-3 control-label text-left">Due Days : <?=$value[0]['DUEDATE'];?></label>
		    <label class="col-md-4 control-label text-left">Responsible Person :<br> 
		    	<input type='text' class="form-control auto_complete" style="text-transform: uppercase;" name='txt_assign_<?=$value[0]['ZNECODE'];?>' id='txt_assign' value='<?=$value[0]['RES_PERSON'];?>'>
		    </label>
			<label class="switch" class="col-md-4" style="float: right;padding-top: 10px;">
				<input class="switch toggle check" type="checkbox" id="chech_<?=$value[0]['ZNECODE'];?>" name="chech_<?=$value[0]['ZNECODE'];?>" value="N"  />
				<span></span>
			</label>
 	 	</div>
	  </div>	
 <?}?>
 	<div class="row" style="margin-bottom: 10px;border-top: 1px solid black;padding-top: 10px" >
 		<input type="button" id="submit" class="btn btn-success" style="float:right; " name="" onclick="nsubmit();" value="Submit">
 	</div>
<?}?>
<?
if($_REQUEST['action']=='submit')
{		//print_r($_REQUEST);
	//exit();
	$po_numb=explode(' - ',$_REQUEST['po_numb']);
	$po_year=$po_numb[0];
	$po_numb=$po_numb[1];
	foreach ($_REQUEST['check'] as $key => $value) {
		if($value['value']=='N' || $value['value']=='A')
		{		//print_r($value);
				$name=explode('_',$value['name']);
				if($_REQUEST['assign'][$name[1]]!='')
				{
					$emp = select_query_json("Select empsrno from employee_office where empcode='".$_REQUEST['assign'][$name[1]]."'", "Centra", 'TCS');	
				}
				//print_r($emp);
				
				$g_table="order_tracking_detail";
				if($value['value']=='N')
				{
					$g_fld['DELETED']='Y';
				}
				else{
					$g_fld['DELETED']='N';
				}
				
				$g_fld['DELUSER']=$_SESSION['tcs_usrcode'];
				$g_fld['DELDATE']='dd-Mon-yyyy HH:MI:SS AM~~'.$currentdate;
				$g_fld['EMPSRNO']=$emp[0]['EMPSRNO'];
				$where="poryear='".$po_year."' and pornumb='".$po_numb."' and znecode='".$name[1]."'";
				print_r($g_fld);
				print_r($where);
				$insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where);
		}	
	}
	$slct_min = select_query_json("Select znecode,znepcde from order_tracking_detail where poryear='".$po_year."' and pornumb='".$po_numb."' and deleted='N' order by znecode,znepcde asc", "Centra", 'TEST');
	//print_r($slct_min);
	$g_table1="order_tracking_detail";
	$g_fld1['ZNESTAT']='N';
	$where1="poryear='".$po_year."' and pornumb='".$po_numb."' and znecode='".$slct_min[0]['ZNECODE']."' and znepcde='".$slct_min[0]['ZNEPCDE']."'";
	$insert_appplan1 = update_test_dbquery($g_fld1, $g_table1, $where1);
}
if($_REQUEST['action']=='sec_load')
{	if($_REQUEST['sec']=='all')
	{
		$sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,count(distinct znestat) ss from order_tracking_detail where deleted='N' group by poryear||' - '||pornumb,poryear,pornumb having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");
	}
	else
	{
		$sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,count(distinct znestat) ss from order_tracking_detail where deleted='N' and seccode='".$_REQUEST['sec']."' group by poryear||' - '||pornumb,poryear,pornumb having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");
	}
	?>
	<div class="panel panel-default tabs nav-tabs-vertical hover-box-def" style="height:600px;overflow-y: scroll;overflow-x: hidden;box-shadow: 0 0 5px black; border-radius: 10px;">    <ul class="nav nav-tabs " style="width: 100%;border-radius: 10px;">
          <?for($k=0;$k<sizeof($sql_po);$k++){?>
            <li><a data-toggle="tab" onclick="load_process('<?=$sql_po[$k]['PONUMB']?>');" style="border-radius: 10px;"><?=$sql_po[$k]['PONUMB']?></a></li>
          <?}?>
        </ul>
  </div>
<?}
?>

