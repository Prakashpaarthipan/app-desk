<?php 
session_start();
include_once('lib/config.php');
include_once('lib/function_connect.php');
include_once('general_functions.php');
extract($_REQUEST);

$sql_aprmast = select_query_json("select * from tlu_stitching_detail pop, tlu_order_timer tim, stiching_measurement_detail mes
											where pop.BRNCODE = tim.BRNCODE and pop.ENTYEAR = tim.ENTYEAR and pop.ENTNUMB = tim.ENTNUMB and pop.ENTSRNO = tim.ENTSRNO and pop.BRNCODE = mes.BRNCODE 
												and pop.ENTYEAR = mes.ENTYEAR and pop.ENTNUMB = mes.ENTNUMB and pop.ENTSRNO = mes.ENTSRNO and pop.SUBSRNO = tim.SUBSRNO and tim.deleted = 'N' and 
												pop.deleted = 'N' and pop.BRNCODE = '".$brncode."' and pop.ENTYEAR = '".$entry_year."' and pop.ENTNUMB = '".$entry_no."' and 
												pop.ENTSRNO = '".$entry_srno."' and pop.SUBSRNO = '".$entry_sub_srno."' 
											order by pop.PRIORIT, pop.BRNCODE, pop.ENTYEAR, pop.ENTNUMB, pop.ENTSRNO, pop.SUBSRNO, tim.PRSCODE", '114', 'TCS');
if(count($sql_aprmast)) { ?>
<div class="row">
	<div class="row">
		<div class="col-md-6" style="text-align: center; background-color: #666666; line-height: 25px; color: #FFF; font-weight: bold;">ORDER NO : <?=$sql_aprmast[0]['BRNCODE']."-".$sql_aprmast[0]['ENTYEAR']."-".$sql_aprmast[0]['ENTNUMB']."-".$sql_aprmast[0]['ENTSRNO']."-".$sql_aprmast[0]['SUBSRNO']?></div>
		<div class="col-md-6"" style="text-align: center; background-color: #666666; line-height: 25px; color: #FFF; font-weight: bold;">PRODUCT : <?=$sql_aprmast[0]['PRDNAME']?></div>
	</div>
	<div style='clear:both'></div>
	<? $idt = 0; $data_measurement = "";
	foreach ($sql_aprmast as $key => $aprmast_value) {  
		switch ($aprmast_value['STIMODE']) {
			case 1: // Salwar Details
				$txttitle[] = 'LENGTH';
				$txtdata[]  = $aprmast_value['SB_LENGTH'];
				$txttitle[] = 'SOLDER';
				$txtdata[]  = $aprmast_value['SB_SOLDER'];
				$txttitle[] = 'CUT_SOLDER';
				$txtdata[]  = $aprmast_value['SB_CUT_SOLDER'];
				$txttitle[] = 'CHEST';
				$txtdata[]  = $aprmast_value['SB_CHEST'];
				$txttitle[] = 'BREAST';
				$txtdata[]  = $aprmast_value['SB_BREAST'];
				$txttitle[] = 'UNDER_BREAST';
				$txtdata[]  = $aprmast_value['SB_UNDER_BREAST'];
				$txttitle[] = 'WAIST_ROUND';
				$txtdata[]  = $aprmast_value['SB_WAIST_ROUND'];
				$txttitle[] = 'SLEAVE_LENGTH';
				$txtdata[]  = $aprmast_value['SB_SLEAVE_LENGTH'];
				$txttitle[] = 'SLEAVE_ROUND';
				$txtdata[]  = $aprmast_value['SB_SLEAVE_ROUND'];
				$txttitle[] = 'ARM_BIT';
				$txtdata[]  = $aprmast_value['SB_ARM_BIT'];
				$txttitle[] = 'FRONT_NECK_DRAPE';
				$txtdata[]  = $aprmast_value['SB_FRONT_NECK_DRAPE'];
				$txttitle[] = 'BACK_NECK_DRAPE';
				$txtdata[]  = $aprmast_value['SB_BACK_NECK_DRAPE'];
				$txttitle[] = 'NATURAL_WAIST_LENGTH';
				$txtdata[]  = $aprmast_value['SB_NATURAL_WAIST_LENGTH'];
				$txttitle[] = 'QL_LOOSE';
				$txtdata[]  = $aprmast_value['SB_QL_LOOSE'];
				$txttitle[] = 'OPEN';
				$txtdata[]  = $aprmast_value['SB_OPEN'];
				$txttitle[] = 'PLARE';
				$txtdata[]  = $aprmast_value['SB_PLARE'];
				$data_measurement = "CM";
				$data_cloth = "SALWAR";
				break;

			case 2: // Salwar Bottom Details
				$txttitle[] = 'LENGTH';
				$txtdata[]  = $aprmast_value['SBT_LENGTH'];
				$txttitle[] = 'WAIST_ROUND';
				$txtdata[]  = $aprmast_value['SBT_WAIST_ROUND'];
				$txttitle[] = 'SEAT_ROUND';
				$txtdata[]  = $aprmast_value['SBT_SEAT_ROUND'];
				$txttitle[] = 'THIGH_ROUND';
				$txtdata[]  = $aprmast_value['SBT_THIGH_ROUND'];
				$txttitle[] = 'KNEE_ROUND';
				$txtdata[]  = $aprmast_value['SBT_KNEE_ROUND'];
				$txttitle[] = 'CALF_ROUND';
				$txtdata[]  = $aprmast_value['SBT_CALF_ROUND'];
				$txttitle[] = 'LEG_ROUND';
				$txtdata[]  = $aprmast_value['SBT_LEG_ROUND'];
				$txttitle[] = 'PANT_MODEL';
				$txtdata[]  = $aprmast_value['SBT_PANT_MODEL'];
				$data_measurement = "INCH";
				$data_cloth = "SALWAR BOTTOM";
				break;

			case 3: // Pant Details
				$txttitle[] = 'LENGTH';
				$txtdata[]  = $aprmast_value['SBT_LENGTH'];
				$txttitle[] = 'INNER_LENGTH';
				$txtdata[]  = $aprmast_value['MP_INNER_LENGTH'];
				$txttitle[] = 'FORK_LOOSE';
				$txtdata[]  = $aprmast_value['MP_FORK_LOOSE'];
				$txttitle[] = 'SEAT';
				$txtdata[]  = $aprmast_value['MP_SEAT'];
				$txttitle[] = 'THIGH_LOOSE';
				$txtdata[]  = $aprmast_value['MP_THIGH_LOOSE'];
				$txttitle[] = 'KNEE_LOOSE';
				$txtdata[]  = $aprmast_value['MP_KNEE_LOOSE'];
				$txttitle[] = 'WAIST_ROUND';
				$txtdata[]  = $aprmast_value['MP_WAIST_ROUND'];
				$txttitle[] = 'TYPE_OF_HEM';
				$txtdata[]  = $aprmast_value['MP_TYPE_OF_HEM'];
				$data_measurement = "INCH";
				$data_cloth = "PANT";
				break;

			case 4: // Shirt Details
				$txttitle[] = 'LENGTH';
				$txtdata[]  = $aprmast_value['MS_LENGTH'];
				$txttitle[] = 'SHOULDER';
				$txtdata[]  = $aprmast_value['MS_SHOULDER'];
				$txttitle[] = 'SLEEVE_LENGTH';
				$txtdata[]  = $aprmast_value['MS_SLEEVE_LENGTH'];
				$txttitle[] = 'SLEEVE_ROUND';
				$txtdata[]  = $aprmast_value['MS_SLEEVE_ROUND'];
				$txttitle[] = 'ARM_BIT';
				$txtdata[]  = $aprmast_value['MS_ARM_BIT'];
				$txttitle[] = 'CALF_LOOSE';
				$txtdata[]  = $aprmast_value['MS_CALF_LOOSE'];
				$txttitle[] = 'CHEST_ROUND';
				$txtdata[]  = $aprmast_value['MS_CHEST_ROUND'];
				$txttitle[] = 'CHEST_FRONT';
				$txtdata[]  = $aprmast_value['MS_CHEST_FRONT'];
				$txttitle[] = 'HIP_ROUND';
				$txtdata[]  = $aprmast_value['MS_HIP_ROUND'];
				$txttitle[] = 'COLLAR_POINT';
				$txtdata[]  = $aprmast_value['MS_COLLAR_POINT'];

				$txttitle[] = 'POCKET1';
				$txtdata[]  = $aprmast_value['MS_POCKET1'];
				$txttitle[] = 'POCKET2';
				$txtdata[]  = $aprmast_value['MS_POCKET2'];
				$txttitle[] = 'POCKET_LENGTH';
				$txtdata[]  = $aprmast_value['MS_POCKET_LENGTH'];
				$data_measurement = "INCH";
				$data_cloth = "SHIRT";
				break;
			
			default:
				break;
		}

		for ($datai = 0; $datai < count($txtdata); $datai++) { 
			if($datai % 2 == 0) {
				$bgclr = "#c0c0c0";
			} else {
				$bgclr = "#d0d0d0";
			} 
		?>
		<div class="row">
			<div class="col-md-12"" style="text-align: center; background-color: #666666; line-height: 25px; color: #FFF; font-weight: bold;">STITCHING MATERIAL : <?=$data_cloth?></div>
		</div>
		<div style='clear:both'></div>

		<div class="row" style="background-color: <?=$bgclr?>; line-height: 25px;">
			<div class="col-md-6" style="padding-right: 0px;"><?=$txttitle[$datai]?></div><div class="col-md-6" style="font-weight: bold;">: <?=$txtdata[$datai]." ".$data_measurement?></div>
			<div style='clear:both'></div>
		</div>
		<div style='clear:both'></div>
	<? } 
	} ?>
</div>
<? } ?>
<div style='clear:both'></div>