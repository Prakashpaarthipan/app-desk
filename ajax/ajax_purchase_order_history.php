<?php 
session_start();
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');


if($_REQUEST['action'] == 'showHistory') { 
?>
	
	
	<div class="row" style="padding:0;margin:0">
		<div class="col-md-12" style="background-color: #fcc837; font-weight: bold; line-height: 25px; color: #000;padding:10px">Order : <?=$_REQUEST['pordate']?> / <?=$_REQUEST['pornumb']?></div>
	</div>
	
	<div class="row" style="margin-left:-10px;margin-right:-10px">
		<div class="col-md-12" >
			<table class="table table-striped" style="width:100%">
				<thead>
					<th style="text-align:center">#</th>
					<th style="text-align:center">Supplier</th>
					<th style="text-align:center">Order</th>
					<th style="text-align:center">Date</th>
					<th style="text-align:center">Process</th>

				</thead>
				<tbody>
				
		
			<? 
			
			$sql_employee_tlu = select_query_json("select to_char(tim.ADDDATE,'dd-MON-yyyy hh:mi:ss AM') ADDDATE,tim.PORNUMB,tim.PORYEAR,tim.ZNECODE from order_tracking_history tim where tim.PORNUMB='".$_REQUEST['pornumb']."' and tim.PORYEAR='".$_REQUEST['poryear']."' order by tim.ENTSRNO desc","Centra","TEST");
			
			$empi = 0;
			foreach ($sql_employee_tlu as $key => $employee_tlu_value) { $empi++; 
				
				$supcode=select_query_json("select distinct(SUPCODE) SUP from order_tracking_detail where  PORNUMB='".$_REQUEST['pornumb']."' and PORYEAR='".$_REQUEST['poryear']."'", "Centra", "TEST");
				
				 $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
				 
				 $prsname=select_query_json("select ZNENAME from order_tracking_master where ZNECODE='".$employee_tlu_value['ZNECODE']."' and ZNEMODE='R' and DELETED='N'", "Centra", "TEST");
				 ?>
				 <tr>
				 	<td><?= $empi;?></td>
					<td><?= $supcode[0]['SUP']?> / <?= $supname[0]['SUPNAME']?></td>
					<td><?=$employee_tlu_value['PORNUMB']?> / <?=$employee_tlu_value['PORYEAR']?></td>
					<td><?=$employee_tlu_value['ADDDATE']?></td>
					<td><?=$prsname[0]['ZNENAME']?></td>


				 </tr>
				                                   
					<?	}

					?>
				
				</tbody>
			</table>
            
		</div>
	<? } ?>