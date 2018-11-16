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
		      <input class="switch all_check" type="checkbox" checked="" name="all_check" id="all_check"  value="N"/>
			
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
				<input class="switch toggle entry_check" type="checkbox" id="chech_<?=$value[0]['ZNECODE'];?>" name="chech_<?=$value[0]['ZNECODE'];?>" value="N"  />
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
	echo "entered";
	print_r('ssss'.$_REQUEST['analyse']);
	$ponum=array();$poyear=array();
	foreach ($_REQUEST['analyse'] as $key => $value1) {
		echo $value1['name'];
		$exp=array();
		if($value1['name']!="all_check1" && $value1['value']=='A'){
			$exp=explode(' - ',$value1['name']);
			echo $exp[0];echo $exp[1];
			$ponum[]=$exp[1];
			$poyear[]=str_replace(' ','',$exp[0]);
		}
	}
    echo $checkedponumb=implode(',',$ponum);
    echo $checkedpoyear=implode(' , ',$poyear);
	$po_numb=explode(' - ',$_REQUEST['po_numb']);
	$po_year=$po_numb[0];
	$po_numb=$po_numb[1];
	for($i=0;$i<sizeof($ponum);$i++){
	foreach ($_REQUEST['check'] as $key => $value) {
		if($value['value']=='N' || $value['value']=='A')
		{		//print_r($value);
				$name=explode('_',$value['name']);
				if($_REQUEST['assign'][$name[1]]!='')
				{
					$emp = select_query_json("Select empsrno from employee_office where empcode='".$_REQUEST['assign'][$name[1]]."'", "Centra", 'TCS');	
				}
				//print_r($emp);
				echo $value['value'];
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
				$where="pornumb='".$ponum[$i]."' and znecode='".$name[1]."'";
				print_r($g_fld);
				
				print_r($where);
			    $insert_appplan1 = update_test_dbquery($g_fld, $g_table, $where);
		}	
	}
	
	$slct_min = select_query_json("Select znecode,znepcde,poryear,pornumb from order_tracking_detail where pornumb ='".$ponum[$i]."' and deleted='N' order by znecode,znepcde asc", "Centra", 'TEST');
	
	//print_r($slct_min);
	$g_table1="order_tracking_detail";
	$g_fld1['ZNESTAT']='N';
	$where1="pornumb='".$ponum[$i]."' and znecode='".$slct_min[0]['ZNECODE']."' and znepcde='".$slct_min[0]['ZNEPCDE']."'";
	$insert_appplan1 = update_test_dbquery($g_fld1, $g_table1, $where1);
}
}

if($_REQUEST['action']=='sec_load')
{	if($_REQUEST['sec']=='all')
	{
		$sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,pordate,porqty,porval,supcode,count(distinct znestat) ss from order_tracking_detail where deleted='N' group by poryear||' - '||pornumb,poryear,pornumb,pordate,porqty,porval,supcode having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");

	}
	else
	{
		$sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,pordate,porqty,porval,supcode,count(distinct znestat) ss from order_tracking_detail where deleted='N' and seccode='".$_REQUEST['sec']."' group by poryear||' - '||pornumb,poryear,pornumb,pordate,porqty,porval,supcode having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");
		
		
	}
	?>
	
                     
                           <div class="panel panel-default tabs nav-tabs-vertical hover-box-def" style="height:600px;overflow-y: scroll;overflow-x: hidden;box-shadow: 0 0 5px black; border-radius: 10px;" id="check_list1">   
						   <ul class="nav nav-tabs " style="width: 100%;border-radius: 10px;">
						   
						   <div class="row" style="margin-bottom: 10px;border-bottom: 1px solid black;">
 		<label class="switch switch-small" style="float: right;">
		      <input class="switch all_check1" type="checkbox" name="all_check1" id="all_check1" value="N" onclick="load_process('<?=$sql_po[0]['PONUMB']?>');"/>
			
		      <span></span>
		</label>
 		<label class="control-label text-left" style="float: right;font-size: 15px;margin-right: 10px;">All</label>
 	</div>
 
                                 
                                  <?for($k=0;$k<sizeof($sql_po);$k++){  
//$supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE=(select distinct SUPCODE from order_tracking_detail where PORNUMB='".$sql_po[$k]['PONUMB']."') and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");    
         $supname=select_query_json("select sup.SUPNAME,cty.CTYNAME from supplier sup, city cty where supcode='".$sql_po[$k]['SUPCODE']."' and sup.CTYCODE=cty.CTYCODE","Centra", "TEST");         
?>

 						<li>
				

								<div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;height:auto;max-width:100%">
									
                                        <div class="form-group" onclick="load_process('<?=$sql_po[$k]['PONUMB']?>');" style="border-radius: 10px;" >
										
									   <label class="switch switch-small" class="col-md-4" style="float: right;padding-top: 10px;">
				<input class="switch toggle1 po_check" type="checkbox" id="check_<?=$k?>" name="check_<?=$k?>" value="N" />
				<span></span>
			</label>

                                         <!--<div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
                                          
                                          <div class="col-md-12" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;">
										Order No :<?=$sql_po[$k]['PORYEAR']?> <?=$sql_po[$k]['PONUMB']?> [ <?=$sql_po[$k]['PORDATE']?>]
								
                                          </div>
                                          <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">
                                              Supplier : <?=$sql_po[$k]['SUPCODE']." - ".$supname[0]['SUPNAME'].", ".$supname[0]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_po[$k]['PORQTY']?>
                                          </div><!-- CGST -->
										  <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_po[$k]['PORVAL']/100000),2);?> 
                                          </div><!-- SGST -->
              
                                          <div style="clear: both;"></div>
                                       </div>
                                      
                                    </div>
                                   </div>
									</li>
									

                                  <?}?>
                                </ul>
								
                        
						
                        </div>
<?}
if($_REQUEST['action']=='order_search')
{
	
		$sql_po = select_query_json("select poryear||' - '||pornumb PONUMB,pordate,porqty,porval,supcode,count(distinct znestat) ss from order_tracking_detail where deleted='N'and PORNUMB like '%".$_REQUEST['selectval']."%' group by poryear||' - '||pornumb,poryear,pornumb,pordate,porqty,porval,supcode having count(distinct znestat)=1 order by poryear,pornumb", "Centra", "TEST");
	?>
	 <div class="panel panel-default tabs nav-tabs-vertical hover-box-def" style="height:600px;overflow-y: scroll;overflow-x: hidden;box-shadow: 0 0 5px black; border-radius: 10px;">
	 <ul class="nav nav-tabs " style="width: 100%;border-radius: 10px;">
                                  <?for($k=0;$k<sizeof($sql_po);$k++){
$supname=select_query_json("select sup.SUPNAME,cty.CTYNAME from supplier sup, city cty where supcode='".$sql_po[$k]['SUPCODE']."' and sup.CTYCODE=cty.CTYCODE","Centra", "TEST");
?>
               
								<li>
								<div class="row" style="border:1px solid #ccc;margin:0;font-size:12px !important;max-width:100%;cursor:pointer">
                                    <div class="col-md-12"  style="padding-top:5px;padding-left:0;padding-right:0;height:auto;max-width:100%">
									
                                        <div class="form-group" onclick="load_process('<?=$sql_po[$k]['PONUMB']?>');" style="border-radius: 10px;">
                                         <!--<div class="col-md-1">
                                             <span id='dropdown<?=$tme.$t?>' style="font=size:12px;font-weight:bold;color:#ffebcc;border:1px solid #000;cursor:pointer !important;padding:2px 5px;background-color:#29a329"  onclick="selecttoggle('<?=$tme.$t?>')"> + </span>
                                          </div><!-- duration -->
										  
                                          
                                          <div class="col-md-12" style="font-size: 14px; padding-top: 8px; padding-right: 2px;font-weight:bold;margin-right:5px; color: #002eff;">
										Order No :<?=$sql_po[$k]['PORYEAR']?> <?=$sql_po[$k]['PONUMB']?> [ <?=$sql_po[$k]['PORDATE']?> ]
								
                                          </div>
										  
                                          <div style="clear: both;"></div>
                                          <div class="col-md-12" style="padding: 5px 10px;">

                                              Supplier : <?=$sql_po[$k]['SUPCODE']." - ".$supname[0]['SUPNAME'].", ".$supname[0]['CTYNAME']?>
                                          </div><!-- rate -->
                                          <div style="clear: both;"></div>                                         
                                         
                                          <div class="col-md-6" style="padding: 5px 10px; padding-left: 10px;font-weight:bold;color: #FF0000;">
                                              Qty : <?=$sql_po[$k]['PORQTY']?>
                                          </div><!-- CGST -->
										  <div class="col-md-6" style="padding:0; text-align: right; padding-right: 10px; padding: 5px 10px; padding-left: 2px;font-weight:bold;color: #FF0000;">
                                              Val : <?=number_format(($sql_po[$k]['PORVAL']/100000),2);?> L
                                          </div><!-- SGST -->
                                          
              
                                          <div style="clear: both;"></div>
                                       </div>
                                      
                                    </div>

									</li>

                                  <?}?>
                                </ul>
                          </div>
<? }

?>
