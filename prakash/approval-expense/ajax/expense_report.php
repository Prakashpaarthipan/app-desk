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
	$sql_data = select_query_json("select * from approval_product_quotation_fix where aprnumb='".$_REQUEST['aprnumb']."'", "Centra", 'TEST');
	//print_r($sql_data);?>
	<!-- /////////////// -->
	<table id="appr_table"  class="table datatable table-striped no-footer" style="margin-left: 20px;">
        <thead>
            <tr>
              <th class="center" style='text-align:center'>s.no</th>
              <th class="center" style='text-align:center'>Tarnumb</th>
              <th class="center" style='text-align:center'>Prdcode</th>
              <th class="center" style='text-align:center'>Depcode</th>
              <th class="center" style='text-align:center'>Subcode</th>
              <th class="center" style='text-align:center'>Supplier Name</th>
              <th class="center" style='text-align:center'>Duration</th>
              <th class="center" style='text-align:left'>Quantity</th>
              <th class="center" style='text-align:left'>Attachments</th>
              <th class="center" style='text-align:left'>Remarks</th>
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
              <? echo $sql_data[$k]['TARNUMB'] ?>
            </td>
            <td class="center" style='text-align:left;'>
              <? echo $sql_data[$k]['PRDCODE'] ?>
            </td>
            <!-- /////////////// -->
            <td class="center" style='text-align:center;'>
              <? echo $sql_data[$k]['DEPCODE'] ?>
            </td>
            <td class="center" style='text-align:center;'>
			
              <? echo $sql_data[$k]['SUBCODE']; ?>
            </td>
            <td class="center" style='text-align:center;'>
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
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['PRDQTY'] ?>
            </td>
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['SUPQFILE'] ?>
            </td>
            <td class="center"  style='text-align:left'>
            	<? echo $sql_data[$k]['REMARKS'] ?>
            </td>
          </tr>
        <?
      } ?>
        </tbody>
    </table>
	<!-- ////////////////////// -->

<?}?>
