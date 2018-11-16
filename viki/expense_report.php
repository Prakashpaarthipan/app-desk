<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();
error_reporting(0);
$currentdate = strtoupper(date('d-M-Y h:i:s A'));
include_once('../lib/function_connect.php');
//$currentdate = strtoupper(date('d-M-Y h:i:s A'));
if($_REQUEST['action']=='load' )
{
	$sql_data = select_query_json("select * from approval_product_quotation_fix where aprnumb='".trim($_REQUEST['aprnumb'])."'", "Centra", 'TEST');
  //echo("select * from approval_product_quotation_fix where aprnumb='".trim($_REQUEST['aprnumb'])."'");
	/*$arr=array();
	foreach($sql_data as $key => $value)
	{
	$temp=count($arr[$value['EXPSRNO']]);
	$arr[$value['TARNUMB']][$temp]=$value;
	}
	?>
	<div class="panel-group accordion" id="acco" style="padding: 10px 20px;">
          <?$flag=0;foreach($arr as $key => $value){
            $flag++;
             $expname = select_query_json("select distinct(expname) from department_asset WHERE expsrno='".$value[0]['EXPSRNO']."'", "Centra", 'TCS');
           // $exp_part = select_query_json("select distinct(ptdesc) from non_purchase_target where depcode='".$sql_search[$k]['DEPCODE']."' and brncode='".$sql_search[$k]['BRNCODE']."' and ptnumb='".$sql_search[$k]['PTNUMB']."'order by ptnumb", "Centra", 'TCS');?>
                <div class="panel panel-primary">
                    <div class="panel-heading ui-draggable-handle">
                       <div class="row">
                         <div class="col-md-4">
                           <h2 class="panel-title">
                             <a href="#acc_<?=$value[0]['TARNUMB']?>"> 
                                <?=$value[0]['TARNUMB'];?>
                             </a>
                          </h2>
                         </div>
                       </div>
                    </div>          
                    <?if($flag==1){?>
                         <div class="panel-body panel-body-open" id="acc_<?=$value[0]['TARNUMB']?>" style="display: block; border: 1px solid rgba(149, 183, 93, 1);border-radius: 10px;padding:20px 20px;">    
                    <?}else{?>
                          <div class="panel-body" id="acc_<?=$value[0]['TARNUMB']?>" style="display: none; border: 1px solid rgba(149, 183, 93, 1);border-radius: 10px;padding:20px 20px;">
                    <?}?>    
                      <h1>hi</h1>
                    </div>                                
                </div>
              <?}?>
            </div>
        <!-- /////////////////////// -->
    </div>*/?>

<table id="appr_table"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
        <thead>
            <tr>
              <th class="center" style='text-align:center'>S.NO</th>
              <th class="center" style='text-align:center'>TARGET NAME</th>
              <th class="center" style='text-align:center'>PRODUCT NAME</th>
              <th class="center" style='text-align:center'>DEP.CODE</th>
              <th class="center" style='text-align:center'>SUB-PRODUCT NAME</th>
              <th class="center" style='text-align:center'>SUPPLIER NAME</th>
              <th class="center" style='text-align:center'>DURATION</th>
              <th class="center" style='text-align:left'>QUANTITY</th>
			  <th class="center" style='text-align:left'>RATE/per</th>
              <th class="center" style='text-align:left'>DISCOUNT %</th>
              <th class="center" style='text-align:left'>REMARKS</th>
			  <th class="center" style='text-align:left'>ACTION</th>
            </tr>
        </thead>
        <tbody>
      
    <?for($k=0;$k<count($sql_data);$k++)
    {?>
        <tr class="odd gradeX" style='background-color:<?=$bgclr?>; color:<?=$clr?>;'>
            <td class="center" style='text-align:center;'>
             <?=$k+1;?>
            </td>
            <td class="center" style='text-align:center'>
			
              <?	$tardetails = select_query_json("select distinct  Tar.Ptnumb,Dep.expname,Dep.Depname Department,dep.depcode
 from trandata.non_purchase_target@tcscentr Tar, trandata.Department_asset@tcscentr Dep,trandata.branch@tcscentr Brn 
 where tar.ptnumb ='".$sql_data[$k]['TARNUMB']."'  and tar.brncode=1 and tar.depcode=dep.depcode and tar.brncode=brn.brncode 
 and dep.Deleted='N'","Centra","TCS");

			  echo $sql_data[$k]['TARNUMB'].'-'.$tardetails[0]['EXPNAME'];?>
            </td>
            <td class="center" style='text-align:left;'>
              <?	$prodetails = select_query_json("select  pst.PRDNAME from trandata.product_asset@tcscentr pst where  pst.PRDCODE = '".$sql_data[$k]['PRDCODE']."' and pst.deleted='N'","Centra","TCS");
			  echo $sql_data[$k]['PRDCODE'].'-'.$prodetails[0]['PRDNAME']; ?>
            </td>
            <!-- /////////////// -->
            <td class="center" style='text-align:center;'>
              <? echo $sql_data[$k]['DEPCODE'] ?>
            </td>
            <td class="center" style='text-align:center;'>
			
              <? echo $sql_data[$k]['SUBCODE']; ?>
            </td>
            <td class="center" style='text-align:left;'>
			<?
		 $result = select_query_json("select distinct sup.SUPCODE, sup.SUPNAME, sup.SUPMOBI, cty.ctyname from supplier_asset sup, city cty
									where sup.ctycode = cty.ctycode and sup.deleted = 'N' and cty.deleted = 'N' and sup.SUPCODE ='".$sql_data[$k]['SUPCODE']."' and SUPMAIL is not null order by sup.SUPCODE, sup.SUPNAME", "Centra", 'TCS');
			?>
             <? echo $sql_data[$k]['SUPCODE']."-".$result[0]['SUPNAME']; ?>
            </td>
            <!-- ////////////////// -->
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['SUPDLVY'] ?>
            </td>
            <td class="center"  style='text-align:center'>
            	<? echo $sql_data[$k]['PRDQTY'] ?>
            </td>
			 <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['SUPRATE'] ?>
            </td>
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['SUPDISC'] ?>
            </td>
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['REMARKS'] ?>
            </td>
			 <td class="center"  style='text-align:left'>
            	View
            </td>
          </tr>
        <?
      } ?>
        </tbody>
    </table> 

<?}?>
