<?php 
session_start();
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
include_once('../general_functions.php');


if($_REQUEST['action'] == 'showHistory') { 
?>
	
	
	<div class="row" style="padding:0;margin:0">
		<div class="col-md-12" style="text-align: center; background-color: #fcc837; font-weight: bold; line-height: 25px; color: #000;">Order : <?=$_REQUEST['poryear']?> - <?=$_REQUEST['pornumb']?> - <?=$_REQUEST['pordate']?> </div>
	</div>
	
	<div class="row" style="padding:0;margin:0">
		<div class="col-md-12" style="padding:0;margin:0">
			<div class="row" style="text-align: center; font-weight: bold; background-color: #fcc837; color: #000; line-height: 25px;padding:0;margin:0">
				<div class="col-md-1" style="padding:0;border:1px solid #FFF; min-height: 52px;">#</div>
				<div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process</div>	
				<div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Process Stage</div>				
				<div class="col-md-3" style="padding:0;border:1px solid #FFF; min-height: 52px;">Start Date</div>
				<div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">End Date</div>
				<div class="col-md-2" style="padding:0;border:1px solid #FFF; min-height: 52px;">Order Status </div>
				
			</div>
		</div>
		<div class="col-md-12" style="padding:0;margin:0">
			
			<? 
			
			$sql_employee_tlu = select_query_json("select to_char(ZNEADDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEADDT,to_char(ZNEEDDT,'dd-Mon-yyyy HH:MI:SS AM') ZNEEDDT,ZNESTAT,ZNCSRNO,ZNPSRNO from order_tracking_history where PORNUMB='".$_REQUEST['pornumb']."' and PORYEAR='".$_REQUEST['poryear']."' order by ENTSRNO desc","Centra","TEST");
			
			$empi = 0;
			foreach ($sql_employee_tlu as $key => $employee_tlu_value) { $empi++; 
				
				$supcode=select_query_json("select distinct(ord.SUPCODE) SUP,sup.SUPNAME,city.CTYNAME,mas.ZNENAME,mas.ZNEPNME from order_tracking_detail ord,order_tracking_master mas,supplier sup,city city where  ord.PORNUMB='".$_REQUEST['pornumb']."' and ord.PORYEAR='".$_REQUEST['poryear']."' and sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE and mas.ZNECODE='".$employee_tlu_value['ZNCSRNO']."' and mas.ZNEMODE='R' and mas.DELETED='N' and mas.ZNEPCDE='".$employee_tlu_value['ZNPSRNO']."'", "Centra", "TEST");
				
				 // $supname=select_query_json("select sup.SUPNAME,city.CTYNAME from supplier sup,city city where sup.SUPCODE='".$_REQUEST['supcode']."' and sup.CTYCODE=city.CTYCODE", "Centra", "TEST");
				 
				 // $prsname=select_query_json("select ZNENAME from order_tracking_master where ZNCSRNO='".$employee_tlu_value['ZNCSRNO']."' and ZNEMODE='R' and DELETED='N'", "Centra", "TEST");
				
					
				 ?>
				<div class="row" style="background-color:#fcc8372e;padding:0;margin:0; line-height: 25px; text-align: center;">
					<div class="col-md-1" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?= $empi;?></div>
					<div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNENAME']?></div>
					<div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$supcode[0]['ZNEPNME']?></div>
					<div class="col-md-3" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEADDT']?></div>
					<div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?=$employee_tlu_value['ZNEEDDT']?></div>
					<div class="col-md-2" style="padding:0;border:1px solid #fff; min-height: 62px; line-height: 20px;"><?php if($employee_tlu_value['ZNESTAT']=='F') { echo "FINISHED";}else if($employee_tlu_value['ZNESTAT']=='R'){ echo "REVERTED";} else if($employee_tlu_value['ZNESTAT']=='U'){ echo "UPDATED";} ?></div>
					
						
                 </div>                                        
					<?	}

					?>
				
				
            
		</div>
	<? } ?>